// Importa la función 'initRouter' del archivo 'router.js'.
import { initRouter } from './router.js';

// Se ejecuta cuando la página ha terminado de cargar.
window.onload = () => {
    // Obtiene el contenedor principal de la aplicación.
    const container = document.getElementById('app');

    // Inicializa el enrutador con el contenedor principal.
    initRouter(container);

    // Obtiene el enlace dinámico del dominio y establece su href al dominio actual.
    const dynamicDomainLink = document.getElementById('dynamicDomainLink');
    dynamicDomainLink.href = window.location.origin;

    // Agrega un oyente de eventos para todos los clics en el documento.
    document.addEventListener('click', (event) => {
        // Verifica si el elemento clicado es un enlace que comienza con '#'.
        if (event.target.matches('a[href^="#"]')) {
            // Evita la acción predeterminada del enlace (navegación).
            event.preventDefault();

            // Obtiene el valor del atributo 'href' del enlace.
            const hashPath = event.target.getAttribute('href');

            // Actualiza el historial del navegador con la nueva ruta.
            history.pushState(null, null, hashPath);

            // Dispara un evento 'popstate' para que el manejador de rutas lo capture y actualice la vista.
            window.dispatchEvent(new Event('popstate'));
        }
    });

    // Agrega un oyente de eventos para todos los formularios en el documento.
    document.addEventListener('submit', (event) => {
        // Evita el comportamiento predeterminado de envío del formulario.
        event.preventDefault();
    });
}
