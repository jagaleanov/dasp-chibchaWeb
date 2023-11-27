<div class="container">
    <h1 class="modal-title my-4">Hosts</h1><hr>
    <p>Listado de hosts registrados en el sistema</p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">IP</th>
                <th scope="col">Email cliente</th>
                <th scope="col">Fecha de registro</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hosts as $host) { ?>
                <tr>
                    <td><?= $host->id ?></td>
                    <td><?= $host->ip ?></td>
                    <td><?= $host->email ?></td>
                    <td><?= $host->created_at ?></td>
                    <td>
                        <a href="/hosts/details/<?= $host->id ?>" class="btn btn-primary btn-sm ms-1 mb-1">
                            <i class="bi bi-eye" title="Ver detalles"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>