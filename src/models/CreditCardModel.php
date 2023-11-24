<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Repositorio para gestionar operaciones relacionadas con los creditCards en la base de datos
class CreditCardModel extends Model
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
            return (object) $data;
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
            $creditCards[] = (object) $creditCardData;
        }

        return $creditCards;
    }

    // Método para insertar un proveedor de tarjetas de crédito en la base de datos
    public function save($creditCard)
    {
        try {
            $type = $this->getCreditCardType($creditCard->number);

            if(!$type){
                return false;
            }
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO credit_cards (customer_id, number, type, expiration_year, expiration_month, security_code, name) VALUES (:customer_id, :number, :type, :expiration_year, :expiration_month, :security_code, :name)"
            );
            $stmt->execute([
                'customer_id' => $creditCard->customer_id,
                'number' => $creditCard->number,
                'type' => $type,
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

    // Método para obtener el emisor de una tarjeta de crédito
    public function getCreditCardType($number){

            // Verifica la marca de la tarjeta basándose en los primeros dígitos
            if (preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $number)) {
                return 'Visa';
            } elseif (preg_match("/^(5[1-5]|222[1-9]|22[3-9]\d|2[3-6]\d{2}|27[01]\d|2720)\d{12}$/", $number)) {
                return 'MasterCard';
            } elseif (preg_match("/^3[47]\d{13}$/", $number)) {
                return 'American Express';
            } elseif (preg_match("/^3(0[0-5]|[68])\d{11}$/", $number)) {
                return 'Diners Club';
            } elseif (preg_match("/^6(011|5\d{2}|64[4-9]\d|62212[6-9]|6221[3-9]\d|622[2-8]\d{2}|6229[01]\d|62292[0-5])(\d{10})$/", $number)) {
                return 'Discover';
            } elseif (preg_match("/^(352[89]|35[3-8]\d)\d{13}$/", $number)) {
                return 'JCB';
            } else {
                return false;
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
