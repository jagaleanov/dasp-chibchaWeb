const baseURL = "http://chibchaweb.com/api";  

export async function get(resource) {
    try {
        const response = await fetch(`${baseURL}/${resource}`);

        // Si la respuesta no es exitosa (no está en el rango 200-299)
        if (!response.ok) {
            const errorData = await response.json();  // Puedes intentar leer el cuerpo de la respuesta por si contiene información adicional del error.
            throw new Error(`API error: ${response.status} - ${errorData.message || response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Hubo un error en la llamada a la API", error);
        throw error;  // Lanzar el error para que pueda ser manejado por quien llama a la función.
    }
}
