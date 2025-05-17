function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = ''; // Hapus pratinjau sebelumnya

    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Bungkus dalam div container
            const container = document.createElement('div');
            container.style.position = 'relative';
            container.style.display = 'inline-block';

            // Gambar
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '60px';
            img.style.borderRadius = '8px';
            img.style.marginTop = '10px';

            // Tombol X
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

            // Hapus gambar saat tombol X diklik
            closeBtn.onclick = function () {
                preview.innerHTML = ''; // Hapus konten preview
                document.getElementById('buktiInput').value = ''; // Reset input file
            };

            container.appendChild(img);
            container.appendChild(closeBtn);
            preview.appendChild(container);
        }
        reader.readAsDataURL(file);
    }
}