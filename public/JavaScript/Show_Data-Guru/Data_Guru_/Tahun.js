document.addEventListener('DOMContentLoaded', function () {
    const tahunList = window.tahunList;
    const bulanList = window.bulanList;

    // Render awal chart
    let chartKeuanganInstance = null;

    function renderBarChart(canvasId, data, gradientColors, labels) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, gradientColors[0]);
        gradient.addColorStop(1, gradientColors[1]);

        // Jika sudah ada chart sebelumnya, destroy dulu supaya gak duplikat
        if (canvas.chartInstance) {
            canvas.chartInstance.destroy();
        }

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: gradient,
                    borderRadius: 10,
                    borderSkipped: false,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'Jumlah: ' + context.parsed.y;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'start',
                        align: 'bottom',
                        color: '#1e1e1e',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: Math.round,
                        offset: -30,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { display: false },
                        grid: { display: false },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Simpan instance chart ke elemen canvas supaya bisa di-destroy nanti
        canvas.chartInstance = newChart;

        return newChart;
    }

    // Render chart guru dan siswa seperti awal
    renderBarChart('chartGuru', window.guruData, ['#4facfe', '#00f2fe'], tahunList);
    renderBarChart('chartSiswa', window.siswaData, ['#4facfe', '#00f2fe'], tahunList);
    // Render chart keuangan awal dengan data bulan
    renderBarChart('chartKeuangan', window.keuanganData, ['#43e97b', '#38f9d7'], bulanList);

    // --- Bagian update chart keuangan dan total saat tahun dipilih ---
    const selectTahun = document.getElementById('kategori');
    const totalKeuanganElem = document.getElementById('totalKeuanganTahun');

    selectTahun.addEventListener('change', function () {
        const tahunTerpilih = this.value;
        if (!tahunTerpilih) return; // jika kosong, skip

        fetch(`yayasan/keuangan/by-tahun?tahun=${tahunTerpilih}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            // Hitung total keuangan (jumlah_spp)
            const total = data.reduce((sum, item) => sum + item.jumlah_spp, 0);
            totalKeuanganElem.textContent = 'Rp.' + new Intl.NumberFormat('id-ID').format(total);

            // Map data per bulan sesuai bulanList, jika bulan tidak ada data isi 0
            const dataPerBulan = bulanList.map(bulan => {
                const bulanData = data.find(k => k.bulan === bulan);
                return bulanData ? bulanData.jumlah_spp : 0;
            });

            // Update chart keuangan (destroy dan render ulang)
            renderBarChart('chartKeuangan', dataPerBulan, ['#43e97b', '#38f9d7'], bulanList);
        })
        .catch(e => console.error('Error fetch keuangan:', e));
    });
});
