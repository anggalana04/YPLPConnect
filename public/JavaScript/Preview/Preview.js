function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = ''; // Hapus pratinjau sebelumnya

    const file = event.target.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];

    if (file && allowedTypes.includes(file.type)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.createElement('div');
            container.style.position = 'relative';
            container.style.display = 'inline-block';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.borderRadius = '8px';
            img.style.marginTop = '10px';

            const closeBtn = document.createElement('span');
            closeBtn.innerHTML = '&times;';
            closeBtn.style.position = 'absolute';
            closeBtn.style.top = '0px';
            closeBtn.style.right = '0px';
            closeBtn.style.color = 'white';
            closeBtn.style.backgroundColor = 'grey';
            closeBtn.style.borderRadius = '50%';
            closeBtn.style.padding = '2px 7px';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.fontWeight = 'bold';
            closeBtn.style.transform = 'translateY(-50%)';

            closeBtn.onclick = function () {
                preview.innerHTML = '';
                document.getElementById('buktiInput').value = '';
            };

            container.appendChild(img);
            container.appendChild(closeBtn);
            preview.appendChild(container);
        };
        reader.readAsDataURL(file);
    } else {
        alert('Format file tidak didukung. Harap unggah file gambar seperti JPG, PNG, GIF, BMP, atau WEBP.');
        event.target.value = ''; // Reset input jika format tidak valid
    }
}
