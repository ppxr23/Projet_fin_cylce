<template>
  <div>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

// Enregistrement des composants Chart.js
ChartJS.register(BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend)

export default {
  name: 'BarChart',
  components: { Bar },
  data() {
    return {
      chartData: {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'],
        datasets: [
          {
            backgroundColor: '#0096C7',
            data: [40, 20, 12, 39, 10, 5, 100],
            label: 'Performance',
          }
        ]
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            ticks: {
              stepSize: 10
            },
            beginAtZero: true // Ajout recommandé
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        }
      }
    }
  }
}
</script>

<style scoped>
div {
  width: 100%;
  height: 400px;
}
</style>