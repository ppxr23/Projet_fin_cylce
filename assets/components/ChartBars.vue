<template>
  <div>
    <Bar :chart-data="chartData" :chart-options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import {
  Chart,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip
} from 'chart.js'

Chart.register(BarElement, CategoryScale, LinearScale, Title, Tooltip)

export default {
  components: { Bar },
  data() {
    return {
      chartData: {
        labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'],
        datasets: [
          {
            backgroundColor: '#0096C7',
            data: [40, 20, 12, 39, 10, 5, 100],
            label: 'performance',
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
                    stepSize : 10
                }
            }
        }
      }
    }
  }
}
</script>
