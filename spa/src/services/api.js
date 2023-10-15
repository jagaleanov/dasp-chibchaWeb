// URL base para las llamadas a la API.
const baseURL = "http://chibchaweb.com:82/api";

/**
 * Realiza una solicitud GET a la API.
 * @param {string} resource - El recurso específico de la API al que se quiere acceder.
 * @returns {Promise<Object>} - La respuesta de la API en formato JSON.
 */
export async function get(resource) {
    try {
        // Hace una solicitud GET a la URL compuesta.
        const response = await fetch(`${baseURL}/${resource}`);

        // Verifica si la respuesta es exitosa (códigos de estado en el rango 200-299).
        if (!response.ok) {
            // Intenta leer el cuerpo de la respuesta para obtener detalles del error.
            const errorData = await response.json();
            throw new Error(`API error: ${response.status} - ${errorData.message || response.statusText}`);
        }

        // Devuelve la respuesta en formato JSON.
        return await response.json();
    } catch (error) {
        // Registra el error y lo relanza.
        console.error("Hubo un error en la llamada a la API", error);
        throw error;
    }
}

/**
 * Realiza una solicitud POST a la API.
 * @param {string} resource - El recurso específico de la API al que se quiere acceder.
 * @param {Object} data - El cuerpo de la solicitud en formato JSON.
 * @returns {Promise<Object>} - La respuesta de la API en formato JSON.
 */
export async function post(resource, data) {
    try {
        // Hace una solicitud POST a la URL compuesta.
        const response = await fetch(`${baseURL}/${resource}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`API error: ${response.status} - ${errorData.message || response.statusText}`);
        }

        // Devuelve la respuesta en formato JSON.
        return await response.json();
    } catch (error) {
        // Registra el error y lo relanza.
        console.error("Hubo un error en la llamada a la API", error);
        throw error;
    }
}

/**
 * Realiza una solicitud PUT a la API.
 * @param {string} resource - El recurso específico de la API al que se quiere acceder.
 * @param {Object} data - El cuerpo de la solicitud en formato JSON.
 * @returns {Promise<Object>} - La respuesta de la API en formato JSON.
 */
export async function put(resource, data) {
    try {
        // Hace una solicitud PUT a la URL compuesta.
        const response = await fetch(`${baseURL}/${resource}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`API error: ${response.status} - ${errorData.message || response.statusText}`);
        }

        // Devuelve la respuesta en formato JSON.
        return await response.json();
    } catch (error) {
        // Registra el error y lo relanza.
        console.error("Hubo un error en la llamada a la API", error);
        throw error;
    }
}
