<div class="container">
    <h4 class="modal-title my-4">Tickets</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Host id</th>
                <th scope="col">Host IP</th>
                <th scope="col">Descripción</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket) { ?>
                <tr>
                    <td><?= $ticket->id ?></td>
                    <td><?= $ticket->host_id ?></td>
                    <td><?= $ticket->ip ?></td>
                    <td><?= $ticket->description ?></td>
                    <td><?= $ticket->status ?></td>
                    <td><?= $ticket->created_at ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>