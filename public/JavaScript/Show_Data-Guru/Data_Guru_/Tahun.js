document.addEventListener('DOMContentLoaded', function () {
    const tahunList = window.tahunList;

    function renderBarChart(canvasId, data, gradientColors) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, gradientColors[0]);
        gradient.addColorStop(1, gradientColors[1]);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: tahunList,
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
    }

    renderBarChart('chartGuru', window.guruData, ['#4facfe', '#00f2fe']);
    renderBarChart('chartSiswa', window.siswaData, ['#4facfe', '#00f2fe']);
    renderBarChart('chartKeuangan', window.keuanganData, ['#43e97b', '#38f9d7']); // bar chart keuangan
});
