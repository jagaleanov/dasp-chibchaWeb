export const Form = {
    render: async (mode = 'create', customer = null) => {
        return `
            <form id="customerForm">
                <input type="text" name="name" value="${customer ? customer.name : ''}" placeholder="Name">
                ...
                <button type="submit">${mode === 'create' ? 'Create' : 'Update'} Customer</button>
            </form>
        `;
    },
    afterRender: async (mode = 'create', customer = null) => {
        document.getElementById('customerForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            
            // Aquí, recopila los datos del formulario y realiza una solicitud a la API para crear o actualizar
            if (mode === 'create') {
                // Llamada API para crear
            } else {
                // Llamada API para actualizar
            }
        });
    }
}
