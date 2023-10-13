export const CustomerDetails = {
    render: async (customerId) => {
        const customer = await api.get(`customers/${customerId}`);
        return `
            <h2>${customer.name}</h2>
            ...
            <button id="editButton">Edit</button>
            <button id="deleteButton">Delete</button>
        `;
    },
    afterRender: async (customerId) => {
        // Agregar manejadores de eventos para editar y eliminar
    }
}
