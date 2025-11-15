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
import api from '../../api';

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
      const note = await api.get('all_note');
      const notes = note.data;

      const labels = notes.map(v => v.name || 'N/A');
      const datas = notes.map(v => v.moyenne || 'N/A');

      this.chartData = {
        labels: labels,
        datasets: [
          {
            label: 'Performance',
            backgroundColor: '#0096C7',
            data: datas
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
