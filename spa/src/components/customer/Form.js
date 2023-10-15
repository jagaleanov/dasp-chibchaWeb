// Importa las funciones necesarias desde el archivo de servicio de cliente.
import { getCustomerById, createCustomer, updateCustomer } from '../../services/customerService.js';

export const Form = {
    // Método para renderizar el contenido HTML del formulario de cliente.
    render: async (mode = 'create', customerId = null) => {
        let customerName = '';
        let customerEmail = '';

        try {
            // Si se proporciona un ID de cliente, obtiene los detalles del cliente del servicio.
            if (customerId) {
                const customer = await getCustomerById(customerId);
                customerName = customer.name;
                customerEmail = customer.email;
            }

            // Devuelve el contenido HTML del formulario con los campos prellenados si es necesario.
            return `
                <form id="customerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name" value="${customerName}" placeholder="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" value="${customerEmail}" placeholder="Email" required>
                    </div>

                    <!-- El botón mostrará 'Crear' o 'Actualizar' dependiendo del modo. -->
                    <button type="submit" class="btn btn-primary">${mode === 'create' ? 'Crear' : 'Actualizar'} Cliente</button>
                </form>
            `;

        } catch (error) {
            // En caso de error, se registra y se muestra un mensaje en la interfaz.
            console.error("Error al obtener los datos del cliente:", error);
            return `<div>Error al obtener los datos del cliente: ${error.message}</div>`;
        }
    },

    // Método 'afterRender' para agregar manejadores de eventos al formulario.
    afterRender: async (mode = 'create', customerId = null) => {
        // Selecciona el formulario por su ID.
        const form = document.getElementById('customerForm');

        // Escucha el evento de envío del formulario.
        form.addEventListener('submit', async (event) => {
            // Evita la recarga de la página que es el comportamiento predeterminado al enviar un formulario.
            event.preventDefault();

            // Crea un objeto para almacenar los datos del cliente que se enviarán.
            const customerData = {
                name: form.name.value,  // Obtiene el valor del campo de nombre.
                email: form.email.value  // Obtiene el valor del campo de correo electrónico.
            };

            try {
                // Verifica si el modo es 'create' o 'edit'.
                if (mode === 'create') {
                    // Si es 'create', llama a la función para crear un nuevo cliente.
                    await createCustomer(customerData);
                    alert('Usuario creado exitosamente');
                } else if (mode === 'edit' && customerId) {
                    // Si es 'edit' y se proporciona un ID de cliente, llama a la función para actualizar el cliente.
                    await updateCustomer(customerId, customerData);
                    alert('Usuario actualizado exitosamente');
                }

                // Después de crear o actualizar, redirige al usuario a la lista de clientes.
                window.location.hash = '/customer';

            } catch (error) {
                // En caso de error, se registra y se muestra un mensaje en la interfaz.
                console.error("Error al procesar el formulario:", error);
                alert(`Error al procesar el formulario: ${error.message}`);
            }
        });
    }
}