<div class="container">
    <h1 class="modal-title my-4">Pagos</h1><hr>
    <p>Listado de pagos de host registrados en el sistema</p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Host id</th>
                <th scope="col">Host IP</th>
                <th scope="col">Email cliente</th>
                <th scope="col">Tarjeta de crédito</th>
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
                    <td><?= $payment->email ?></td>
                    <td><?= $payment->credit_card_number ?></td>
                    <td>$ <?= $payment->amount ?></td>
                    <td><?= $payment->created_at ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>