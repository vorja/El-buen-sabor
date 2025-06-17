// Función para actualizar las métricas del dashboard
function actualizarMetricas() {
    // Obtener todas las estadísticas de una sola vez
    fetch('../Controllers/dashboard.php?action=getEstadisticasPrincipales')
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                // Actualizar ventas de hoy
                document.getElementById('ventasHoy').textContent = '$' + data.ventas_hoy.toLocaleString();
                // Actualizar pedidos activos
                document.getElementById('pedidosActivos').textContent = data.pedidos_activos;
                // Actualizar clientes atendidos
                document.getElementById('clientesHoy').textContent = data.clientes_atendidos;
                // Actualizar producto popular
                document.getElementById('ProductoPopular').textContent = data.producto_popular;
                // Actualizar producto popular Ventas
                document.getElementById('ppVendidoHoy').textContent = data.cantidad_vendida +' Vendidos hoy';
                 // Actualizar porcentaje
                document.getElementById('porcentaje').textContent = data.variacion_porcentual +'%';
                // Actualizar total pedidos
                document.getElementById('total_pedidos').textContent = data.pedidos_hoy +' pedidos realizados';
            } else {
                console.error('Error al obtener estadísticas:', response.message);
            }
        })
        .catch(error => console.error('Error al obtener estadísticas:', error));
}

// Actualizar las métricas cada 30 segundos
document.addEventListener('DOMContentLoaded', function() {
    actualizarMetricas();
    setInterval(actualizarMetricas, 30000);
});