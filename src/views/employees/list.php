<div class="container">
    <h4 class="modal-title my-4">Empleados</h4>
    <a href="/employees/new" class="btn btn-primary btn-sm ms-1 mb-1"><i class="bi bi-person-fill" title="Nuevo empleado"></i> Nuevo empleado</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Correo electrónico</th>
                <th scope="col">Celular</th>
                <th scope="col">Fecha de registro</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee) { ?>
                <tr>
                    <td><?= $employee->id ?></td>
                    <td><?= $employee->name ?></td>
                    <td><?= $employee->last_name ?></td>
                    <td><?= $employee->email ?></td>
                    <td><?= $employee->mobile_phone ?></td>
                    <td><?= $employee->created_at ?></td>
                    <td>
                        <a href="/employees/edit/<?= $employee->id ?>" class="btn btn-primary btn-sm ms-1 mb-1">
                            <i class="bi bi-pencil-square" title="Editar empleado"></i>
                        </a>
                        <a href="/employees/details/<?= $employee->id ?>" class="btn btn-primary btn-sm ms-1 mb-1">
                            <i class="bi bi-eye" title="Ver detalles"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>