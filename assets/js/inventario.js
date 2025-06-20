function actualizarMetricas() {
    fetch('../Controllers/inventario.php?action=getInventarioPrincipales')
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                document.getElementById('totalProducts').textContent = data.total_productos;
                document.getElementById('normalStock').textContent = data.stock_normal;
                document.getElementById('lowStock').textContent = data.stock_bajo;
                document.getElementById('criticalStock').textContent = data.stock_critico;
            } else {
                console.error('Error al obtener estadísticas:', response.message);
            }
        })
        .catch(error => console.error('Error al obtener estadísticas:', error));
}

function calcularEstado(stock, stock_minimo) {
    if (stock >= stock_minimo) {
        return { status: 'Normal', class: 'status-normal' };
    } else if (stock > stock_minimo / 2) {
        return { status: 'Bajo', class: 'status-bajo' };
    } else {
        return { status: 'Crítico', class: 'status-critico' };
    }
}

function llenarTabla() {
    fetch('../Controllers/inventario.php?action=getIngredientes')
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                const tbody = document.getElementById('inventoryTableBody');
                tbody.innerHTML = '';
                if (!Array.isArray(data) || data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#888;">Sin productos</td></tr>`;
                    return;
                }
                data.forEach(product => {
                    const estado = calcularEstado(product.stock, product.stock_minimo);
                    tbody.innerHTML += `
                        <tr>
                            <td>${product.nombre}</td>
                            <td>${product.stock} ${product.unidad}</td>
                            <td>${product.stock_minimo}</td>
                            <td><span class="status-badge ${estado.class}">${estado.status}</span></td>
                            <td>${product.actualizado ? product.actualizado : '-'}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-edit btn-sm" onclick="openModal('edit', ${product.id})"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-delete btn-sm" onclick="deleteProduct(${product.id})"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } else {
                console.error('Error al obtener inventario:', response.message);
            }
        })
        .catch(error => console.error('Error al obtener inventario:', error));
}

// Actualizar las métricas y la tabla al cargar

document.addEventListener('DOMContentLoaded', function() {
    actualizarMetricas();
    llenarTabla();
    setInterval(actualizarMetricas, 30000);
});