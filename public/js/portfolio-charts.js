// Portfolio Health Trend Chart
function initHealthTrendChart(data) {
    if (!data || !Array.isArray(data) || data.length === 0) {
        console.warn('Health trend chart: No valid data provided');
        return;
    }
    const el = document.querySelector("#health-trend-chart");
    if (!el) {
        console.warn('Health trend chart: target element #health-trend-chart not found');
        return;
    }
    
    let options = {
        series: [
            {
                name: 'Sağlıklı',
                data: data.map(d => d.healthy || 0)
            },
            {
                name: 'Riskli', 
                data: data.map(d => d.risky || 0)
            },
            {
                name: 'Kritik',
                data: data.map(d => d.critical || 0)
            }
        ],
        chart: {
            type: 'area',
            height: 350,
            stacked: true,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        colors: ['#2ab57d', '#f1b44c', '#f46a6a'],
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 0,
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100]
            }
        },
        xaxis: {
            categories: data.map(d => d.month || 'Bilinmeyen'),
            title: {
                text: 'Ay'
            }
        },
        yaxis: {
            title: {
                text: 'Proje Sayısı'
            },
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val + " proje"
                }
            }
        }
    };

    try {
        let chart = new ApexCharts(el, options);
        chart.render();
    } catch (error) {
        console.error('Health trend chart render error:', error);
    }
}

// Budget Time Performance Scatter Plot
function initBudgetTimeScatter(data) {
    if (!data || !Array.isArray(data) || data.length === 0) {
        console.warn('Budget scatter chart: No valid data provided');
        return;
    }
    const el = document.querySelector("#budget-time-scatter");
    if (!el) {
        console.warn('Budget scatter chart: target element #budget-time-scatter not found');
        return;
    }
    
    let options = {
        series: [{
            name: "Projeler",
            data: data.map(d => ({
                x: d.budget_variance || 0,
                y: d.schedule_variance || 0,
                name: d.name || 'Bilinmeyen'
            }))
        }],
        chart: {
            height: 350,
            type: 'scatter',
            zoom: {
                type: 'xy'
            }
        },
        xaxis: {
            tickAmount: 10,
            title: {
                text: 'Bütçe Sapması (%)',
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + '%'
                }
            }
        },
        yaxis: {
            tickAmount: 7,
            title: {
                text: 'Zaman Sapması (%)',
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + '%'
                }
            }
        },
        markers: {
            size: 8
        },
        colors: ['#556ee6'],
        legend: {
            show: false
        },
        grid: {
            borderColor: '#f1f1f1'
        },
        tooltip: {
            shared: false,
            intersect: true,
            x: {
                show: true
            },
            y: {
                formatter: function(val) {
                    return val.toFixed(1) + '%'
                }
            }
        }
    };

    try {
        let chart = new ApexCharts(el, options);
        chart.render();
    } catch (error) {
        console.error('Budget scatter chart render error:', error);
    }
}

// Performance Comparison Chart
function initPerformanceComparisonChart(data) {
    if (!data || !Array.isArray(data) || data.length === 0) {
        console.warn('Performance comparison chart: No valid data provided');
        return;
    }
    const el = document.querySelector("#performance-comparison-chart");
    if (!el) {
        console.warn('Performance comparison chart: target element #performance-comparison-chart not found');
        return;
    }
    
    let options = {
        series: [{
            name: 'Zamanında Tamamlanma',
            data: data.map(d => d.on_time_rate || 0)
        }, {
            name: 'Karlılık Marjı',
            data: data.map(d => d.profit_margin || 0)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        colors: ['#34c38f', '#556ee6'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: data.map(d => d.category || 'Bilinmeyen'),
            title: {
                text: 'Proje Tipi / Bölge'
            }
        },
        yaxis: {
            title: {
                text: 'Oran (%)'
            },
            labels: {
                formatter: function (val) {
                    return val.toFixed(1) + '%'
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toFixed(1) + '%'
                }
            }
        }
    };

    try {
        let chart = new ApexCharts(el, options);
        chart.render();
    } catch (error) {
        console.error('Performance comparison chart render error:', error);
    }
} 