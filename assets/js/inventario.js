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
    stock = parseFloat(stock);
    stock_minimo = parseFloat(stock_minimo);
    if (isNaN(stock) || isNaN(stock_minimo)) {
        return { status: 'Desconocido', class: 'status-bajo' };
    }
    if (stock >= stock_minimo) {
        return { status: 'Normal', class: 'status-normal' };
    } else if (stock > stock_minimo / 2) {
        return { status: 'Bajo', class: 'status-bajo' };
    } else {
        return { status: 'Crítico', class: 'status-critico' };
    }
}

function cargarCategorias() {
    const categoryFilter = document.getElementById('categoryFilter');
    categoryFilter.innerHTML = '<option value="insumos">Insumos</option>';
    fetch('../Controllers/inventario.php?action=getCategoriasProductos')
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                response.data.forEach(cat => {
                    categoryFilter.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                });
            }
        });
}

function llenarTabla() {
    const categoria = document.getElementById('categoryFilter').value;
    const estado = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    const tbody = document.getElementById('inventoryTableBody');
    tbody.innerHTML = '<tr><td colspan="9" style="text-align:center; color:#888;">Cargando...</td></tr>';

    let url;
    const params = new URLSearchParams();
    if (search) params.append('search', search);

    if (categoria === '' || categoria === 'insumos') {
        url = '../Controllers/inventario.php?action=getIngredientes';
        if (estado) params.append('estado_stock', estado);
    } else {
        url = '../Controllers/inventario.php?action=getProductosPorCategoriaYStock';
        params.append('categoria_id', categoria);
        if (estado) params.append('estado_stock', estado);
    }

    const queryString = params.toString();
    if (queryString) {
        url += (url.includes('?') ? '&' : '?') + queryString;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(response => {
            tbody.innerHTML = '';
            if (response.success) {
                const data = response.data;
                if (!Array.isArray(data) || data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:#888;">No se encontraron resultados.</td></tr>`;
                    return;
                }
                data.forEach(item => {
                    const estado = calcularEstado(item.stock, item.stock_minimo);
                    const valorTotal = (parseFloat(item.stock) * parseFloat(item.precio_unitario)).toFixed(2);
                    tbody.innerHTML += `
                        <tr>
                            <td>${item.nombre}</td>
                            <td>${item.categoria || 'Insumo'}</td>
                            <td>${item.stock !== null ? item.stock : ''} ${item.unidad || ''}</td>
                            <td>${item.stock_minimo !== null ? item.stock_minimo : ''} ${item.unidad || ''}</td>
                            <td>${item.precio_unitario !== null ? item.precio_unitario : ''}</td>
                            <td>${!isNaN(valorTotal) ? valorTotal : ''}</td>
                            <td><span class="status-badge ${estado.class}">${estado.status}</span></td>
                            <td>${item.actualizado ? item.actualizado : '-'}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-delete btn-sm"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } else {
                 tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:#888;">Error: ${response.message}</td></tr>`;
            }
        })
        .catch(error => {
            tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:#888;">Error de conexión.</td></tr>`;
            console.error('Error de fetch:', error);
        });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('statusFilter').value = '';
    llenarTabla();
}

document.addEventListener('DOMContentLoaded', function() {
    cargarCategorias();
    actualizarMetricas();
    llenarTabla();

    document.getElementById('categoryFilter').addEventListener('change', llenarTabla);
    document.getElementById('statusFilter').addEventListener('change', llenarTabla);
    document.getElementById('searchInput').addEventListener('input', llenarTabla);
    
    // El botón de limpiar no necesita su propio listener si ya lo tiene en el HTML
    // o si se quiere manejar aquí, asegurarse de que el id exista.
});