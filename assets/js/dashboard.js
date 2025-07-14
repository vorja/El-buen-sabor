// assets/js/dashboard.js
document.addEventListener('DOMContentLoaded', () => {
  // Datos incrustados desde PHP (asegúrate de imprimir estas variables en tu view)
  const ventasLabels = <?= json_encode($labelsVentas) ?>;
  const ventasData   = <?= json_encode($dataVentas) ?>;
  const prodLabels   = <?= json_encode($labelsProd) ?>;
  const prodData     = <?= json_encode($dataProd) ?>;

  // Gráfica de ventas
  new Chart(document.getElementById('chartVentas'), {
    type: 'line',
    data: {
      labels: ventasLabels,
      datasets: [{
        label: 'Total Ventas (USD)',
        data: ventasData,
        fill: true,
        tension: 0.3,
        borderColor: '#A67C52',
        backgroundColor: 'rgba(166,124,82,0.2)'
      }]
    },
    options: {
      scales: {
        x: { title: { display: true, text: 'Fecha' } },
        y: { title: { display: true, text: 'Ventas (USD)' } }
      }
    }
  });

  // Gráfica de productos
  new Chart(document.getElementById('chartProductos'), {
    type: 'bar',
    data: {
      labels: prodLabels,
      datasets: [{
        label: 'Cantidad vendida',
        data: prodData,
        borderRadius: 5,
        backgroundColor: 'rgba(111,78,55,0.7)'
      }]
    },
    options: {
      indexAxis: 'y',
      scales: {
        x: { title: { display: true, text: 'Cantidad' } },
        y: { title: { display: true, text: 'Producto' } }
      }
    }
  });
});
