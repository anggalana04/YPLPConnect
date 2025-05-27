const rowsPerPage = 10;
const table = document.querySelector('.table-konten tbody');
const rows = table.querySelectorAll('tr');
const pagination = document.getElementById('pagination');
const totalRows = rows.length;
const totalPages = Math.ceil(totalRows / rowsPerPage);

let currentPage = 1;

function displayRows(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
    });
}

function setupPagination() {
    pagination.innerHTML = '';

    // Tombol Prev
    const prevLi = document.createElement('li');
    prevLi.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
    const prevLink = document.createElement('a');
    prevLink.className = 'page-link';
    prevLink.href = '#';
    prevLink.innerHTML = '&laquo;';
    prevLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });
    prevLi.appendChild(prevLink);
    pagination.appendChild(prevLi);

    const addPage = (page) => {
        const li = document.createElement('li');
        li.className = 'page-item' + (page === currentPage ? ' active' : '');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = page;
        a.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = page;
            updatePagination();
        });
        li.appendChild(a);
        pagination.appendChild(li);
    };

    const addDots = () => {
        const li = document.createElement('li');
        li.className = 'page-item disabled';
        const span = document.createElement('span');
        span.className = 'page-link';
        span.textContent = '...';
        li.appendChild(span);
        pagination.appendChild(li);
    };

    // Tampilkan halaman 1
    addPage(1);

    // Tampilkan ... jika currentPage > 2
    if (currentPage > 2) {
        addDots();
    }

    // Tampilkan currentPage jika bukan halaman 1 atau terakhir
    if (currentPage !== 1 && currentPage !== totalPages) {
        addPage(currentPage);
    }

    // Tampilkan ... jika currentPage < totalPages - 1
    if (currentPage < totalPages - 1) {
        addDots();
    }

    // Tampilkan halaman terakhir jika totalPages > 1
    if (totalPages > 1) {
        addPage(totalPages);
    }

    // Tombol Next
    const nextLi = document.createElement('li');
    nextLi.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
    const nextLink = document.createElement('a');
    nextLink.className = 'page-link';
    nextLink.href = '#';
    nextLink.innerHTML = '&raquo;';
    nextLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    });
    nextLi.appendChild(nextLink);
    pagination.appendChild(nextLi);
}


function updatePagination() {
    displayRows(currentPage);
    setupPagination();
}

// Initialize
displayRows(currentPage);
setupPagination();
