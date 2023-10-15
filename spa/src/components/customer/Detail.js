// Importa la función necesaria desde el archivo de servicio de cliente.
import { getCustomerById } from '../../services/customerService.js';

export const Detail = {
    // Método para renderizar el contenido HTML de los detalles del cliente.
    render: async (customerId) => {
        try {
            // Obtiene los detalles del cliente desde el servicio.
            const customer = await getCustomerById(customerId);

            // Devuelve el contenido HTML con los detalles del cliente.
            return `
            <h1>${customer ? customer.name : ''}</h1>  <!-- Nombre del cliente -->
            <span>Cliente</span>  <!-- Etiqueta indicando que se trata de un cliente -->
            <hr>  <!-- Separador horizontal -->
            <p>Email: ${customer ? customer.email : ''}</p>  <!-- Dirección de correo electrónico del cliente -->
            `;

        } catch (error) {
            // En caso de error, se registra y se muestra un mensaje en la interfaz.
            console.error("Error al obtener los detalles del cliente:", error);
            return `<div>Error al obtener los detalles del cliente: ${error.message}</div>`;
        }
    },
    afterRender: async (customerId) => { 

    }
}
