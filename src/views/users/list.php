<div class="container">
    <h1 class="modal-title my-4">Usuarios</h1><hr>
    <p>Listado de usuarios del sistema</p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Correo electrónico</th>
                <th scope="col">Rol</th>
                <th scope="col">Fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->role_name ?></td>
                    <td><?= $user->created_at ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>