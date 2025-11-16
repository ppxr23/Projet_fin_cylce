<template>
  <div>
    <Bar
      :data="chartData"
      :options="chartOptions"
    />
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
import { parseJwt } from '../../utils/jwt';

ChartJS.register(BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend);

export default {
  name: 'BarChartManager',
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
      let connected = null

      if (!sessionStorage.getItem('token')) {
        this.$router.push('/')
        return
      } else {
        connected = parseJwt(sessionStorage.getItem('token'))
      }

      const note = await api.post('all_note_team',{
            matricule: connected.matricule,
            roles: 'MANAGER',
            all: false
        });
      const notes = note.data;

      const labels = notes.map(v => v.firstname || 'N/A');
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
