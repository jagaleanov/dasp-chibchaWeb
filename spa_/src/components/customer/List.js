import { getAllCustomers } from '../../services/customerService.js';

export const List = {
    render: async () => {
        try {
            const customers = await getAllCustomers();

            return `
                <h1>Clientes</h1>

                <!-- Barra de navegación con un botón para crear un nuevo cliente. -->
                <nav class="nav justify-content-end">
                    <a class="btn btn-primary" href="#/customer/create" role="button"><i class="bi bi-person"></i> Nuevo cliente</a>
                </nav>

                <!-- Tabla que muestra la lista de clientes. -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col-3">Id</th>
                            <th scope="col-3">Nombre</th>
                            <th scope="col-3">Correo electrónico</th>
                            <th scope="col-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mapea la lista de clientes a filas de tabla. -->
                        ${customers.map(customer => `
                            <tr>
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <!-- Botones para editar y ver detalles del cliente. -->
                                    <a href="#/customer/edit/${customer.id}" title="Editar" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a> 
                                    <a href="#/customer/${customer.id}" title="Ver detalle" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } catch (error) {
            // En caso de error, registra el error y devuelve un mensaje de error en el contenido.
            console.error("Error al obtener la lista de clientes:", error);
            return `<div>Error al obtener los clientes: ${error.message}</div>`;
        }
    }
}
