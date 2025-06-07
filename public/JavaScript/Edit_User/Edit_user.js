function toggleDropdown(button) {
    const dropdown = button.nextElementSibling;

    // Tutup semua dropdown lainnya
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== dropdown) menu.style.display = 'none';
    });

    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-menu') && !event.target.closest('.dropdown-toggle')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});

function enableRowEditing(el) {
    const tr = el.closest('tr');
    if (!tr || tr.dataset.editing === "true") return;
    tr.dataset.editing = "true";

    // Sembunyikan dropdown & tampilkan tombol Simpan
    const aksiCell = tr.querySelector('td.aksi');
    aksiCell.querySelector('.dropdown-toggle').style.display = 'none';
    aksiCell.querySelector('.btn-simpan').style.display = 'inline-block';

    tr.querySelectorAll('td.editable').forEach(td => {
        if (td.querySelector('input')) return;
        const currentText = td.textContent.trim();
        td.innerHTML = `<input type="text" class="edit-input" value="${currentText}" />`;
    });

    const inputs = tr.querySelectorAll('td.editable input');

    // Fokus ke input pertama
    inputs.forEach((input, index) => {
        if (index === 0) {
            input.focus();
            input.select();
        }

        // Hanya tangani Escape (bukan Enter)
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                cancelRowEdit(tr);
            }
        });
    });
}

function simpanLangsung(button) {
    const tr = button.closest('tr');
    saveRowEdit(tr);
}

function saveRowEdit(tr) {
    const userId = tr.getAttribute('data-id');
    const inputs = tr.querySelectorAll('td.editable input');
    let data = {};

    inputs.forEach(input => {
        const td = input.closest('td');
        const field = td.getAttribute('data-field');
        data[field] = input.value.trim();
    });

    fetch(`/user-manage/update-inline/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const text = await res.text();
        try {
            const response = JSON.parse(text);
            if (response.success) {
                inputs.forEach(input => {
                    const td = input.closest('td');
                    td.textContent = input.value.trim() || '-';
                });
                tr.dataset.editing = "false";

                // Kembalikan tombol aksi seperti semula
                const aksiCell = tr.querySelector('td.aksi');
                aksiCell.querySelector('.btn-simpan').style.display = 'none';
                aksiCell.querySelector('.dropdown-toggle').style.display = 'inline-block';
            } else {
                alert('Gagal menyimpan data');
                cancelRowEdit(tr);
            }
        } catch (err) {
            console.error('Bukan JSON:', text);
            alert('Response bukan JSON:\n' + text);
            cancelRowEdit(tr);
        }
    })
    .catch(() => {
        alert('Error saat menyimpan data');
        cancelRowEdit(tr);
    });
}

function cancelRowEdit(tr) {
    const inputs = tr.querySelectorAll('td.editable input');
    inputs.forEach(input => {
        const td = input.closest('td');
        td.textContent = input.defaultValue || '-';
    });
    tr.dataset.editing = "false";

    // Kembalikan tombol aksi
    const aksiCell = tr.querySelector('td.aksi');
    aksiCell.querySelector('.btn-simpan').style.display = 'none';
    aksiCell.querySelector('.dropdown-toggle').style.display = 'inline-block';
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}
