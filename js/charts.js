const ctx = document.getElementById('barChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['A', 'B', 'C'],
    datasets: [
      {
        label: 'Yellow',
        data: [10, 7, 7],
        backgroundColor: 'rgba(255, 221, 51, 0.8)',
      },
      {
        label: 'Blue',
        data: [9, 10, 10],
        backgroundColor: 'rgba(102, 102, 255, 0.9)',
      }
    ]
  },
  options: {
    responsive: false,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true
      }
    },
    plugins: {
      legend: {
        display: false
      }
    }
  }
});

const doughnutCtx = document.getElementById('topProducts').getContext('2d');
  new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: ['Top selling Products'],
      datasets: [{
        data: [100],
        backgroundColor: ['#5a86b0'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: false,
      cutout: '0%',
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: 'Top selling Products',
          align: 'start',
          color: '#1a1a40',
          font: {
            size: 14,
            weight: 'bold'
          }
        }
      }
    }
  });