// Chart bootstrap for Vite build
import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';
import ApexCharts from 'apexcharts';

// Expose to window so existing inline scripts can use window.Chart and ApexCharts
if (typeof window !== 'undefined') {
    window.Chart = Chart;
    window.ApexCharts = ApexCharts;
}

export default Chart;
export { ApexCharts };
