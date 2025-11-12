<template>
  <div>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend
} from 'chart.js';
import api from '../api';

ChartJS.register(BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend);

export default {
  name: 'BarChart',
  components: { Bar },

  data() {
    return {
      chartData: {
        labels: [],
        datasets: []
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, ticks: { stepSize: 10 } }
        },
        plugins: {
          legend: { display: true, position: 'top' }
        }
      }
    };
  },

  async mounted() {
    try {
      const vigie = await api.get('all_vigie');
      const vigies = vigie.data;

      const labels = vigies.map(v => v.name || 'N/A');

      this.chartData = {
        labels: labels,
        datasets: [
          {
            label: 'Performance',
            backgroundColor: '#0096C7',
            data: [40, 20, 12, 39, 100]
          }
        ]
      };
    } catch (err) {
      console.error(err);
    }
  }

};
</script>

<style scoped>
div {
  width: 100%;
  height: 400px;
}
</style>
