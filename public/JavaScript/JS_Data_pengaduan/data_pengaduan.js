 // Tangkap semua elemen dengan class clickable-row
        document.querySelectorAll('.clickable-row').forEach((row) => {
            row.addEventListener('click', () => {
                const id = row.getAttribute('data-id');
                // Ganti URL sesuai route kamu, misal route('pengaduan.detail', id)
                window.location.href = '/pengaduan/detail/' + id;
            });
        });