import './bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;

const chartRegistry = new Map();

window.initAdminChart = function (canvasId, config) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        return null;
    }

    if (chartRegistry.has(canvasId)) {
        chartRegistry.get(canvasId).destroy();
        chartRegistry.delete(canvasId);
    }

    const chart = new Chart(canvas.getContext('2d'), config);
    chartRegistry.set(canvasId, chart);

    return chart;
};

window.destroyAdminChart = function (canvasId) {
    if (chartRegistry.has(canvasId)) {
        chartRegistry.get(canvasId).destroy();
        chartRegistry.delete(canvasId);
    }
};

document.addEventListener('livewire:init', () => {
    Livewire.on('chart-data-updated', ({ id, config }) => {
        window.initAdminChart(id, config);
    });
});
