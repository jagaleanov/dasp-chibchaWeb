import { get } from '../../services/api.js';

export const List = {
    render: async () => {
        try {
            const customers = await get('customers');

            if (!customers || !Array.isArray(customers.data)) {
                throw new Error('Formato de respuesta inesperado');
            }

            return `
                <h1>Clientes</h1>

                <nav class="nav justify-content-end">
                    <a class="btn btn-primary" href="#" role="button">Nuevo cliente</a>
                </nav>


                <table  class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col-3">Id</th>
                            <th scope="col-3">Nombre</th>
                            <th scope="col-3">Correo electrónico</th>
                            <th scope="col-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${customers.data.map(customer => `
                            <tr>
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <a href="#" title="Editar" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a> 
                                    <a href="#" title="Ver detalle" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } catch (error) {
            console.error("Error al obtener la lista de clientes:", error);
            return `<div>Error al obtener los clientes: ${error.message}</div>`;
        }
    }
}
