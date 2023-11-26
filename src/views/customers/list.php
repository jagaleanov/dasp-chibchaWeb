<div class="container">
    <h1 class="modal-title my-4">Clientes</h1><hr>
    <p>Listado de clientes del sistema</p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Correo electrónico</th>
                <th scope="col">Dirección</th>
                <th scope="col">Fecha de registro</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer) { ?>
                <tr>
                    <td><?= $customer->id ?></td>
                    <td><?= $customer->name ?></td>
                    <td><?= $customer->last_name ?></td>
                    <td><?= $customer->email ?></td>
                    <td><?= $customer->address ?></td>
                    <td><?= $customer->created_at ?></td>
                    <td>
                        <a href="/customers/details/<?= $customer->id ?>" class="btn btn-primary btn-sm ms-1 mb-1">
                            <i class="bi bi-eye" title="Ver detalles"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>