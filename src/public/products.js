document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = '/products';
    const productsTable = document.querySelector('#products-table tbody');
    const productForm = document.querySelector('#product-form');
    const expiryDateHeader = document.querySelector('#expiry-date-header'); // Encabezado de la columna "Fecha de Vencimiento"
    let editingProductId = null; // Variable para manejar el ID del producto en edición
    let sortDirection = 'asc'; // Dirección inicial para la ordenación

    // Obtener el token CSRF desde la metaetiqueta
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Función para cargar productos en la tabla
    function loadProducts() {
        fetch(baseUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                productsTable.innerHTML = '';
                data.forEach(product => {
                    productsTable.innerHTML += `
                        <tr>
                            <td>${product.code || 'N/A'}</td>
                            <td>${product.name || 'N/A'}</td>
                            <td>${product.quantity || '0'}</td>
                            <td>Q${parseFloat(product.price || 0).toFixed(2)}</td>
                            <td>${product.entry_date || 'N/A'}</td>
                            <td>${product.expiry_date || 'N/A'}</td>
                            <td>
                                ${
                                    product.photo
                                        ? `<img src="/storage/${product.photo}" style="width: 100px; height: 100px; object-fit: cover;" alt="${product.name}">`
                                        : 'Sin Imagen'
                                }
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit" data-id="${product.id}">Editar</button>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="${product.id}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                attachEventListeners();
            })
            .catch(error => {
                console.error('Error al cargar productos:', error);
                alert('No se pudieron cargar los productos.');
            });
    }
    document.getElementById('search').addEventListener('input', function (e) {
        const searchValue = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#products-table tbody tr');
    
        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const code = row.cells[0].textContent.toLowerCase();
    
            if (name.includes(searchValue) || code.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    

    // Función para manejar la edición de un producto
    function handleEdit(productId) {
        fetch(`${baseUrl}/${productId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(product => {
                // Rellenar el formulario con los datos del producto
                document.getElementById('product-code').value = product.code;
                document.getElementById('product-name').value = product.name;
                document.getElementById('product-quantity').value = product.quantity;
                document.getElementById('product-price').value = product.price;
                document.getElementById('product-entry-date').value = product.entry_date;
                document.getElementById('product-expiry-date').value = product.expiry_date;

                // Actualizar la variable global para manejar la edición
                editingProductId = productId;

                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error al cargar el producto:', error);
                alert('No se pudieron cargar los datos del producto.');
            });
    }

    // Validaciones de JavaScript
    productForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Obtener los valores del formulario
        const code = document.getElementById('product-code').value.trim();
        const name = document.getElementById('product-name').value.trim();
        const quantity = document.getElementById('product-quantity').value.trim();
        const price = document.getElementById('product-price').value.trim();
        const entryDate = document.getElementById('product-entry-date').value.trim();
        const expiryDate = document.getElementById('product-expiry-date').value.trim();
        const photo = document.getElementById('product-photo').files[0];

        // Validar código de producto
        const codeRegex = /^[a-zA-Z0-9]+$/; // Solo permite letras y números
        if (!codeRegex.test(code)) {
            alert('El código de producto solo puede contener letras y números.');
            return;
        }

        // Validar nombre del producto
        const nameRegex = /^[a-zA-Z\s]+$/; // Solo permite letras y espacios
        if (!nameRegex.test(name)) {
            alert('El nombre del producto solo puede contener letras y espacios.');
            return;
        }

        // Validar cantidad
        if (isNaN(quantity) || quantity <= 0) {
            alert('La cantidad debe ser un número entero mayor a 0.');
            return;
        }

        // Validar precio
        if (isNaN(price) || price < 0) {
            alert('El precio debe ser un número mayor o igual a 0.');
            return;
        }

        // Validar formato de fecha (DD/MM/YYYY)
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!dateRegex.test(entryDate) || !dateRegex.test(expiryDate)) {
            alert('Las fechas deben estar en formato YYYY-MM-DD.');
            return;
        }

        // Validar que la fecha de vencimiento sea mayor a la de ingreso
        const entryDateObj = new Date(entryDate);
        const expiryDateObj = new Date(expiryDate);

        if (expiryDateObj <= entryDateObj) {
            alert('La fecha de vencimiento debe ser mayor a la fecha de ingreso.');
            return;
        }

        // Validar fotografía
        if (photo) {
            const allowedExtensions = ['jpg', 'jpeg', 'png'];
            const fileExtension = photo.name.split('.').pop().toLowerCase();
            const fileSizeInMB = photo.size / (1024 * 1024);

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Solo se permiten fotografías en formato JPG y PNG.');
                return;
            }

            if (fileSizeInMB > 1.5) {
                alert('El tamaño de la fotografía no puede exceder 1.5 MB.');
                return;
            }
        }

        // Validar que el código de producto no esté repetido
        const existingCodes = Array.from(productsTable.querySelectorAll('tr')).map(
            row => row.cells[0]?.textContent.trim()
        );
        if (existingCodes.includes(code)) {
            alert('El código de producto ya existe.');
            return;
        }

        // Si todas las validaciones pasan, guardar el producto
        saveProduct();
    });

    // Función para guardar el producto
    function saveProduct() {
        const formData = new FormData(productForm);
        const url = editingProductId ? `${baseUrl}/${editingProductId}` : baseUrl;

        if (editingProductId) {
            formData.append('_method', 'PUT'); // Simula el método PUT
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(() => {
                alert('Producto guardado exitosamente.');
                productForm.reset();
                editingProductId = null;
                const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                modal.hide();
                loadProducts();
            })
            .catch(error => {
                console.error('Error al guardar el producto:', error);
                alert('No se pudo guardar el producto.');
            });
    }

    // Función para ordenar la tabla por la columna "Fecha de Vencimiento"
    function sortTableByExpiryDate() {
        const rows = Array.from(productsTable.querySelectorAll('tr'));

        // Ordenar las filas por la columna de fecha
        rows.sort((a, b) => {
            const dateA = new Date(a.cells[5]?.textContent.trim() || '');
            const dateB = new Date(b.cells[5]?.textContent.trim() || '');

            return sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
        });

        // Alternar la dirección para el próximo clic
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';

        // Vaciar la tabla y volver a añadir las filas ordenadas
        productsTable.innerHTML = '';
        rows.forEach(row => productsTable.appendChild(row));
    }

    // Asignar eventos a botones de edición y eliminación
    function attachEventListeners() {
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function () {
                handleEdit(this.getAttribute('data-id'));
            });
        });

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                if (confirm('¿Estás seguro de eliminar este producto?')) {
                    fetch(`${baseUrl}/${this.getAttribute('data-id')}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Error ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(() => {
                            alert('Producto eliminado exitosamente.');
                            loadProducts();
                        })
                        .catch(error => {
                            console.error('Error al eliminar el producto:', error);
                            alert('No se pudo eliminar el producto.');
                        });
                }
            });
        });

        expiryDateHeader.addEventListener('click', sortTableByExpiryDate);
    }

    // Cargar productos al iniciar
    loadProducts();
});
