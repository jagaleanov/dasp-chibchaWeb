<div class="container">
    <h4 class="modal-title my-4">Dominios</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Host id</th>
                <th scope="col">Host IP</th>
                <th scope="col">Dominio</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($domains as $domain) { ?>
                <tr>
                    <td><?= $domain->id ?></td>
                    <td><?= $domain->host_id ?></td>
                    <td><?= $domain->ip ?></td>
                    <td><?= $domain->domain ?></td>
                    <td><?= $domain->status ?></td>
                    <td><?= $domain->created_at ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>