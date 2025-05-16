const rowsPerPage = 5;
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

    // Prev button
    const prevLi = document.createElement('li');
    prevLi.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
    const prevLink = document.createElement('a');
    prevLink.className = 'page-link';
    prevLink.href = '#';
    prevLink.setAttribute('aria-label', 'Previous');
    prevLink.innerHTML = '<span aria-hidden="true">&laquo;</span>';
    prevLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });
    prevLi.appendChild(prevLink);
    pagination.appendChild(prevLi);

    // Page number buttons
    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i === currentPage ? ' active' : '');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = i;
        a.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = i;
            updatePagination();
        });
        li.appendChild(a);
        pagination.appendChild(li);
    }

    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
    const nextLink = document.createElement('a');
    nextLink.className = 'page-link';
    nextLink.href = '#';
    nextLink.setAttribute('aria-label', 'Next');
    nextLink.innerHTML = '<span aria-hidden="true">&raquo;</span>';
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
