<div class="container">
    <h4 class="modal-title my-4">Tickets</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Host id</th>
                <th scope="col">Host IP</th>
                <th scope="col">Descripción</th>
                <th scope="col">Nivel de soporte</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de registro</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket) { ?>
                <form method="post">
                    <tr>
                        <td><?= $ticket->id ?></td>
                        <td><?= $ticket->host_id ?></td>
                        <td><?= $ticket->ip ?></td>
                        <td><?= $ticket->description ?></td>
                        <td>
                            <?php if ($ticket->role_id) { ?>
                                <?= $ticket->role_name ?>
                            <?php } else { ?>

                                <?php if ($changeRoleAvailable) { ?>
                                <select class="form-control" id="role_id" name="role_id">
                                    <option value=""></option>
                                    <?php foreach ($roles as $role) { ?>
                                        <option value="<?= $role->id ?>" <?= $post->get('role_id') == $role->id ? 'selected' : '' ?>>
                                            <?= $role->name ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <?php } else { ?>
                                    <?= $ticket->role_name ?>
                                <?php } ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($ticket->status) { ?>
                                Finalizado
                            <?php } else { ?>

                                <?php if ($changeStatusAvailable) { ?>
                                    <button class="btn btn-primary me-2" type="submitStatus" name="submitStatus" value="submitStatus"><i class="bi bi-check-lg" title="Finalizar"></i></button>
                                <?php } else { ?>
                                    Pendiente
                                <?php } ?>

                            <?php } ?>
                        </td>
                        <td><?= $ticket->created_at ?></td>
                        <td>
                            <input type="hidden" name="ticket_id" value="<?= $ticket->id ?>">
                            <?php if (!$ticket->role_id) { ?>
                                <button class="btn btn-primary me-2" type="submitRole" name="submitRole" value="submitRole"><i class="bi bi-save" title="Guardar"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                </form>
            <?php } ?>
        </tbody>
    </table>
</div>