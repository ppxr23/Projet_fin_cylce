<template>
  <div class="chart-container">
    <canvas ref="pieChart"></canvas>
  </div>
</template>

<script>
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  PieController
} from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend, PieController);

export default {
  name: 'ChartCircu',
  mounted() {
    this.renderChart();
  },
  methods: {
    renderChart() {
      const ctx = this.$refs.pieChart.getContext('2d');
      
      new ChartJS(ctx, {
        type: 'pie',
        data: {
          labels: ['Retard', 'Absence', 'Sanction'],
          datasets: [{
            data: [30, 45, 25],
            backgroundColor: ['#ff6384', '#6C757D', '#ffce56'],
            borderColor: '#fff',
            borderWidth: 2,
            hoverOffset: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 20,
                usePointStyle: true
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.raw || 0;
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = Math.round((value / total) * 100);
                  return `${label}: ${value} (${percentage}%)`;
                }
              }
            }
          }
        }
      });
    }
  },
  beforeUnmount() {
    // Nettoyage optionnel pour éviter les fuites mémoire
    if (this.$refs.pieChart && this.$refs.pieChart.chart) {
      this.$refs.pieChart.chart.destroy();
    }
  }
};
</script>

<style scoped>
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}
</style>