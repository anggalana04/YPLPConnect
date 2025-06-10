<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function index()
    {
        $users = User::with('sekolah')->get(); // ambil relasi sekolah berdasarkan NPSN

        return view('operator_yayasan.v_ManageUser.index', compact('users'));
    }

    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:operator_sekolah,operator_yayasan'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'npsn' => ['nullable', 'string', 'size:8', 'exists:sekolah,npsn'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'npsn' => $request->role === 'operator_sekolah' ? $request->npsn : null, // ensure npsn is set only for operator_sekolah
            'password' => Hash::make($request->password),
        ]);

        // Do not auto-login the new user (keep admin logged in)
        // event(new Registered($user));
        // Auth::login($user);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function updateInline(Request $request, $id)
    {
        Log::info('Update inline user, data diterima:', $request->all());

        try {
            $user = User::with('sekolah')->findOrFail($id);

            $allowedUserFields = ['name', 'role', 'no_hp'];
            $allowedSekolahFields = ['sekolah_nama', 'sekolah_alamat'];

            foreach ($request->all() as $field => $value) {
                if (in_array($field, $allowedUserFields)) {
                    // Update atribut user
                    $user->$field = $value;
                } elseif (in_array($field, $allowedSekolahFields)) {
                    // Update atribut sekolah, pastikan sekolah ada
                    if ($user->sekolah) {
                        $attr = str_replace('sekolah_', '', $field);
                        $user->sekolah->$attr = $value;
                    } else {
                        Log::warning("User ID {$id} tidak memiliki sekolah terkait, tidak bisa update $field");
                    }
                }
            }

            $user->save();

            if ($user->sekolah) {
                $user->sekolah->save();
            }

            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('QueryException updateInline:', [
                'message' => $ex->getMessage(),
                'sql' => $ex->getSql(),
                'bindings' => $ex->getBindings(),
            ]);
            return response()->json(['success' => false, 'message' => 'Database error'], 500);
        } catch (Throwable $e) {
            Log::error('Error updateInline:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    /**
     * Remove the specified user from storage (AJAX).
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('User delete failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal hapus user!'], 500);
        }
    }
}
