<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Repositorio para gestionar operaciones relacionadas con los empleados en la base de datos
class EmployeeModel extends Model
{
    // Método para encontrar un empleado por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT e.id, u.name, u.last_name, u.email, u.password, u.role_id, e.mobile_phone, e.user_id, e.created_at, e.updated_at
            FROM employees e 
            JOIN users u ON e.user_id = u.id 
            WHERE e.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el empleado, se retorna null
        return null;
    }

    // Método para encontrar un empleado por su user ID
    public function findByUserId($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT e.id, u.name, u.last_name, u.email, u.password, u.role_id, e.mobile_phone, e.user_id, e.created_at, e.updated_at
            FROM employees e 
            JOIN users u ON e.user_id = u.id 
            WHERE e.user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el empleado, se retorna null
        return null;
    }

    // Método para encontrar todos los empleados, con opción de filtro por nombre o email
    public function findAll($search = null)
    {
        $query =
            "SELECT e.id, u.name, u.last_name, u.email, u.password, u.role_id, e.mobile_phone, e.user_id, e.created_at, e.updated_at
        FROM employees e
        JOIN users u ON e.user_id = u.id";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= " WHERE u.name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $employees = [];
        foreach ($data as $employeeData) {
            $employees[] = (object) $employeeData;
        }

        return $employees;
    }

    // Método para insertar un empleado en la base de datos
    public function save($employee)
    {
        // Iniciar una transacción
        $this->connection->beginTransaction();

        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO users (name, last_name, email, password, role_id) VALUES (:name, :last_name, :email, :password, :role_id)"
            );
            $stmt->execute([
                'name' => $employee->name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'password' => $employee->password,
                'role_id' => $employee->role_id
            ]);
            $userId = $this->connection->lastInsertId();

            // Inserción del empleado 
            $stmt = $this->connection->prepare(
                "INSERT INTO employees (user_id, mobile_phone) VALUES (:user_id, :mobile_phone)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'mobile_phone' => $employee->mobile_phone,
            ]);

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            return $this->findByUserId($userId);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un empleado en la base de datos
    public function update($employee)
    {
        // Iniciar una transacción
        $this->connection->beginTransaction();
        try {
            //Validación de la relación del user y el employee
            $stmt = $this->connection->prepare(
                "SELECT e.id
                FROM employees e 
                JOIN users u ON e.user_id = u.id 
                WHERE e.id = :id
                AND e.user_id = :user_id"
            );
            $stmt->execute([
                'id' => $employee->id,
                'user_id' => $employee->user_id
            ]);

            // Iniciar una transacción
            $this->connection->beginTransaction();

            // Actualización del usuario
            $stmt = $this->connection->prepare(
                "UPDATE users SET 
                    name = :name, 
                    last_name = :last_name,
                    email = :email ,
                    password = :password ,
                    role_id = :role_id ,
                    updated_at = :updated_at
                    WHERE id = :id"
            );
            $stmt->execute([
                'name' => $employee->name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'password' => $employee->password,
                'role_id' => $employee->role_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $employee->user_id
            ]);

            // Actualización del empleado
            $stmt = $this->connection->prepare(
                "UPDATE employees SET 
                    mobile_phone = :mobile_phone,
                    updated_at = :updated_at
                    WHERE id = :id"
            );
            $stmt->execute([
                'mobile_phone' => $employee->mobile_phone,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $employee->id,
            ]);

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            return $this->find($employee->id);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un empleado por su ID
    public function delete($id)
    {
        try {
            // Iniciar una transacción
            $this->connection->beginTransaction();

            //Validación de la relación del user y el employee
            $stmt = $this->connection->prepare("DELETE FROM employees WHERE id = :id");
            $stmt->execute(['id' => $id]);

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}