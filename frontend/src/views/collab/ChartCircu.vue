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
import api from "../../api";

ChartJS.register(ArcElement, Tooltip, Legend, PieController);

export default {
  name: 'ChartCircu',

  data() {
    return {
      abs_month: 0,
      retard_month: 0,
      sanction_month: 0,
      chart: null
    };
  },

  async mounted() {
    try {
      const [abs, sanction, retard] = await Promise.all([
        api.get('count_absence_month'),
        api.get('count_sanction_month'),
        api.get('count_retard_month')
      ]);

      this.abs_month = abs.data;
      this.sanction_month = sanction.data;
      this.retard_month = retard.data;

      this.renderChart();
    } catch (error) {
      console.error(error);
    }
  },

  methods: {
    renderChart() {
      const ctx = this.$refs.pieChart.getContext('2d');

      if (this.chart) this.chart.destroy();

      this.chart = new ChartJS(ctx, {
        type: 'pie',
        data: {
          labels: ['Retard', 'Absence', 'Sanction'],
          datasets: [{
            data: [this.retard_month, this.abs_month, this.sanction_month],
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
                label(context) {
                  const label = context.label || '';
                  const value = context.raw || 0;
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = total ? Math.round((value / total) * 100) : 0;
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
    if (this.chart) {
      this.chart.destroy();
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
