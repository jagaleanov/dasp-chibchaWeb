<div class="container">
    <h4 class="modal-title my-4">Pagos</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Host id</th>
                <th scope="col">Host IP</th>
                <th scope="col">Monto</th>
                <th scope="col">Fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment) { ?>
                <tr>
                    <td><?= $payment->id ?></td>
                    <td><?= $payment->host_id ?></td>
                    <td><?= $payment->ip ?></td>
                    <td>$ <?= $payment->amount ?></td>
                    <td><?= $payment->created_at ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>