import { get, post, put } from './api.js';

export async function getAllCustomers() {
    const customers = await get('customers');
    if (!customers || !Array.isArray(customers.data)) {
        throw new Error('Formato de respuesta inesperado');
    }
    return customers.data;
}

export async function getCustomerById(customerId) {
    const customer = await get(`customers/${customerId}`);
    if (!customer || typeof customer.data == "undefined") {
        throw new Error('Formato de respuesta inesperado');
    }
    return customer.data;
}

export async function createCustomer(data) {
    const newCustomer = await post('customers', data);
    if (!newCustomer || typeof newCustomer.data == "undefined") {
        throw new Error('Formato de respuesta inesperado');
    }
    return newCustomer.data;
}

export async function updateCustomer(customerId, data) {
    const updatedCustomer = await put(`customers/${customerId}`, data);
    if (!updatedCustomer || typeof updatedCustomer.data == "undefined") {
        throw new Error('Formato de respuesta inesperado');
    }
    return updatedCustomer.data;
}
