// Importa la función get desde el módulo api.js en la carpeta services
import { get } from './services/api.js';

// Define las rutas de la aplicación y sus respectivos componentes
const routes = {
    '/': 'home/Home',
    '/customer': 'customer/List',
    '/customer/create': 'customer/Form',
    '/customer/edit/:id': 'customer/Form',
    '/customer/:id': 'customer/Detail',
    '/employee': 'employee/List'
};

// Obtiene los parámetros de la ruta
function getRouteParams(route, pathname) {
    const routeParts = route.split('/');
    const pathParts = pathname.split('/');
    const params = {};

    // Itera sobre las partes de la ruta
    for (let i = 0; i < routeParts.length; i++) {
        // Si una parte de la ruta comienza con ':', es un parámetro
        if (routeParts[i].startsWith(':')) {
            const paramName = routeParts[i].slice(1);  // Elimina el ':' para obtener el nombre del parámetro
            params[paramName] = pathParts[i];  // Guarda el valor del parámetro
        }
    }

    return params;  // Retorna los parámetros encontrados
}

// Verifica si una ruta coincide con un patrón específico
function match(route, pathname) {
    const routeParts = route.split('/');
    const pathParts = pathname.split('/');

    // Si las rutas tienen diferente longitud, no coinciden
    if (routeParts.length !== pathParts.length) { return false; }

    // Verifica cada parte de la ruta
    for (let i = 0; i < routeParts.length; i++) {
        if (routeParts[i].startsWith(':')) {
            if (!pathParts[i]) { return false; }  // Si falta un parámetro, las rutas no coinciden
        } else if (routeParts[i] !== pathParts[i]) {
            return false;  // Si las partes no coinciden, las rutas no coinciden
        }
    }

    return true;  // Las rutas coinciden
}

// Inicializa el router
export function initRouter(containerElement) {
    async function navigate(pathname) {
        const hashPath = pathname.split('#')[1] || '/';
        let routePath = Object.keys(routes).find(route => match(route, hashPath));  // Busca una ruta que coincida
        let componentPath = routes[routePath];
        const [folder, componentKey] = componentPath.split('/');  // Separa el componente del folder
        const componentModule = await import(`./components/${componentPath}.js`);  // Importa el componente
        const component = componentModule[componentKey];  // Obtiene el componente
        const params = getRouteParams(routePath, hashPath);  // Obtiene los parámetros de la ruta

        // Dependiendo del componente y los parámetros, renderiza y ejecuta el afterRender correspondiente
        if (params.id && componentKey === "Form") {
            containerElement.innerHTML = await component.render('edit', params.id);
            await component.afterRender('edit', params.id);
        } else if (params.id && componentKey === "Detail") {
            containerElement.innerHTML = await component.render(params.id);
            await component.afterRender(params.id);
        } else {
            containerElement.innerHTML = await component.render();
            if (component.afterRender) {
                await component.afterRender();
            }
        }
    }

    // Navega a la ruta actual al cargar la página
    navigate(location.hash);
    // Si la URL cambia (por ejemplo, al usar los botones atrás/adelante del navegador), navega a la nueva ruta
    window.onpopstate = () => navigate(location.hash);
}
