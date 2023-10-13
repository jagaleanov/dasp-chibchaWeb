const routes = {
    '/': 'home/Home',
    '/customer': 'customer/List',
    '/customer/create': 'customer/Form',
    '/customer/:id': 'customer/Details',
    '/employee': 'employee/List'
};

export function initRouter(containerElement) {
    async function navigate(pathname) {
        let routePath = Object.keys(routes).find(route => match(route, pathname));
        let componentPath = routes[routePath];

        // Descomponer el path en partes para poder cargar desde subdirectorios
        const [folder, componentKey] = componentPath.split('/');
        // console.log(`./components/${componentPath}.js`);

        const componentModule = await import(`./components/${componentPath}.js`);
        const component = componentModule[componentKey];
        // console.log(componentModule)

        containerElement.innerHTML = await component.render();  // Asegúrate de usar 'await' aquí
    }

    navigate(location.pathname);

    window.onpopstate = () => navigate(location.pathname);
}

// Función helper para ver si una ruta coincide con un patrón
function match(route, pathname) {
    const routeParts = route.split('/');
    const pathParts = pathname.split('/');
    if (routeParts.length !== pathParts.length) return false;

    for (let i = 0; i < routeParts.length; i++) {
        if (routeParts[i].startsWith(':')) continue;  // es un parámetro
        if (routeParts[i] !== pathParts[i]) return false;
    }

    return true;
}
