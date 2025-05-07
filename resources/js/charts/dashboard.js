import Chart from 'chart.js/auto';

const movimientosChartData = window.dashboardMovimientosData;

const ctx = document.getElementById('movimientosChart');

if (ctx && movimientosChartData) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: movimientosChartData.labels,
            datasets: [{
                label: 'Movimientos semanales',
                data: movimientosChartData.data,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#fff'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#fff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: '#fff',
                        maxRotation: 0,
                        autoSkip: true,
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    }
                }
            }
        }
    });
}

const rankingChartData = window.dashboardRankingData;
console.log(rankingChartData);

if (rankingChartData) {

    const labels = rankingChartData.map((user, index) => {
        if (index === 0) {
            return '👑 '+ user.name;
        }
        return user.name;
    });

    const data = rankingChartData.map(user => user.total);

    const ctxRanking  = document.getElementById('rankingChart');
    if (ctxRanking ) {
        new Chart(ctxRanking , {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Movimientos Realizados',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff',
                            maxRotation: 0,
                            autoSkip: true,
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        title: {
                            display: true,
                            text: 'Usuarios',
                        }
                    }
                }
            }
        });
    }
}
