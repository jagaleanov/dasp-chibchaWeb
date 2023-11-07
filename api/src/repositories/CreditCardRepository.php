<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\CreditCard;

// Repositorio para gestionar operaciones relacionadas con los creditCards en la base de datos
class CreditCardRepository extends Repository
{
    // Método para encontrar un proveedor de tarjetas de crédito por su ID
    public function find($customer_id,$number)
    {
        $stmt = $this->connection->prepare(
            "SELECT *
            FROM credit_cards cc 
            WHERE cc.customer_id = :customer_id
            AND cc.number = :number"
        );
        $stmt->execute([
            'customer_id' => $customer_id,
            'number' => $number,
        ]);
        $data = $stmt->fetch();

        if ($data) {
            return new CreditCard($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM credit_cards";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= " WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $creditCards = [];
        foreach ($data as $creditCardData) {
            $creditCards[] = new CreditCard($creditCardData);
        }

        return $creditCards;
    }

    // Método para insertar un proveedor de tarjetas de crédito en la base de datos
    public function save(CreditCard $creditCard)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO credit_cards (customer_id, number, type, expiration_year, expiration_month, security_code, name) VALUES (:customer_id, :number, :type, :expiration_year, :expiration_month, :security_code, :name)"
            );
            $stmt->execute([
                'customer_id' => $creditCard->customer_id,
                'number' => $creditCard->number,
                'type' => $creditCard->type,
                'expiration_year' => $creditCard->expiration_year,
                'expiration_month' => $creditCard->expiration_month,
                'security_code' => $creditCard->security_code,
                'name' => $creditCard->name,
            ]);

            //Respuesta
            return $this->find($creditCard->customer_id,$creditCard->number);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un proveedor de tarjetas de crédito por su ID
    public function delete($customer_id,$number)
    {
        try {
            //Validación de la relación del user y el creditCard
            $stmt = $this->connection->prepare("DELETE FROM credit_cards WHERE cc.customer_id = :customer_id AND cc.number = :number");
            $stmt->execute([
                'customer_id' => $customer_id,
                'number' => $number,
            ]);

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
