<div class="container">
    <h1 class="modal-title my-4">Proveedores</h1><hr>
    <p>Listado de proveedores de dominios del sistema</p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($providers as $provider) { ?>
                <tr>
                    <td><?= $provider->id ?></td>
                    <td><?= $provider->name ?></td>
                    <td>
                        <a href="/providers/details/<?= $provider->id ?>" class="btn btn-primary btn-sm ms-1 mb-1">
                            <i class="bi bi-eye" title="Ver detalles"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>