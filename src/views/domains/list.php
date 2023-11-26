<div class="container">
    <h1 class="modal-title my-4">Dominios</h1><hr>
    <p>Listado de dominios registrados en el sistema</p>
    <a href="/domains/export" class="btn btn-primary btn-sm ms-1 mb-1"><i class="bi bi-file-arrow-down" title="Exportar XML"></i> Exportar XML</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Host id</th>
                <th scope="col">Host IP</th>
                <th scope="col">Dominio</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de registro</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($domains as $domain) { ?>
                <form method="post">
                    <tr>
                        <td><?= $domain->id ?></td>
                        <td><?= $domain->host_id ?></td>
                        <td><?= $domain->ip ?></td>
                        <td><?= $domain->domain ?></td>
                        <td>
                            <?php if ($domain->status > 0) { ?>
                                <?= $domain->status == 1 ? 'APROBADO' : 'RECHAZADO' ?>
                            <?php } else { ?>
                                <select class="form-control" id="status" name="status">
                                    <option value="0" <?= $post->get('status') == 0 ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="1" <?= $post->get('status') == 1 ? 'selected' : '' ?>>Aprobado</option>
                                    <option value="2" <?= $post->get('status') == 2 ? 'selected' : '' ?>>Rechazado</option>
                                </select>
                            <?php } ?>
                        </td>
                        <td><?= $domain->created_at ?></td>
                        <td>
                            <?php if (!$domain->status) { ?>
                                <input type="hidden" name="domain_id" value="<?= $domain->id ?>">
                                <button class="btn btn-primary me-2" type="submit" name="submit" value="submit"><i class="bi bi-save" title="Guardar"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                </form>
            <?php } ?>
        </tbody>
    </table>
</div>