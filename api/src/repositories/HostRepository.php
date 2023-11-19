<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\Host;

// Repositorio para gestionar operaciones relacionadas con los hosts en la base de datos
class HostRepository extends Repository
{
    // Método para encontrar un proveedor de dominios por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM hosts WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Host($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar un proveedor de dominios por su ID
    public function findByIp($ip)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM hosts WHERE ip = :ip"
        );
        $stmt->execute(['ip' => $ip]);
        $data = $stmt->fetch();

        if ($data) {
            return new Host($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($customer_id = null)
    {
        // $query =
        //     "SELECT h.*,
        //     os.name AS operative_system_name
        //     FROM hosts h
        //     JOIN operative_systems os ON h.operative_system_id = os.id
        //     JOIN payment_plans pp ON h.payment_plan_id = pp.id
        //     JOIN host_plans hp ON h.host_plan_id = hp.id";
        $query =
            "SELECT * FROM hosts";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($customer_id)) {
            $query .= " WHERE customer_id LIKE :customer_id";
            $params['customer_id'] = '%' . $customer_id . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $hosts = [];
        foreach ($data as $hostData) {
            $hosts[] = new Host($hostData);
        }

        return $hosts;
    }

    // Método para insertar un proveedor de dominios en la base de datos
    public function save(Host $host)
    {
        try {
            do {
                // IP address format: xxx.xxx.xxx.xxx
                // Each xxx should be a number between 0 and 255.
                $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
            } while ($this->findByIp($ip != null));

            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO hosts (customer_id, host_plan_id, payment_plan_id, operative_system_id,ip) VALUES (:customer_id, :host_plan_id, :payment_plan_id, :operative_system_id,:ip)"
            );


            $stmt->execute([
                'customer_id' => $host->customer_id,
                'host_plan_id' => $host->host_plan_id,
                'payment_plan_id' => $host->payment_plan_id,
                'operative_system_id' => $host->operative_system_id,
                'ip' => $ip,
            ]);
            $hostId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($hostId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un proveedor de dominios en la base de datos
    public function update(Host $host)
    {
        try {
            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE hosts SET 
                customer_id = :customer_id,
                host_plan_id = :host_plan_id,
                payment_plan_id = :payment_plan_id,
                operative_system_id = :operative_system_id,
                updated_at = :updated_at
                WHERE id = :id"
            );
            $stmt->execute([
                'customer_id' => $host->customer_id,
                'host_plan_id' => $host->host_plan_id,
                'payment_plan_id' => $host->payment_plan_id,
                'operative_system_id' => $host->operative_system_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $host->id
            ]);

            //Respuesta
            return $this->find($host->id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un proveedor de dominios por su ID
    public function delete($id)
    {
        try {
            //Validación de la relación del user y el host
            $stmt = $this->connection->prepare("DELETE FROM hosts WHERE id = :id");
            $stmt->execute(['id' => $id]);

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
