<template>
  <div>
    <canvas ref="pieChart"></canvas>
  </div>
</template>

<script>
import {
  Chart,
  ArcElement,
  Tooltip,
  Legend,
  PieController // ✅ Needed for "pie" charts
} from 'chart.js';

Chart.register(ArcElement, Tooltip, Legend, PieController);

export default {
  name: 'ChartCircu',
  mounted() {
    const ctx = this.$refs.pieChart.getContext('2d');

    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
          data: [30, 45, 25],
          backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }
};
</script>
