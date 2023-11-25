<div class="container">
    <form method="post">
        <h4 class="modal-title my-4">Nuevo pago</h4>
        <h5 class="mb-4">Host: <?= $host->ip ?></h5>
        <p>
            Plan de host: <?= $hostPlan->name ?><br>
            Plan de pagos: <?= $paymentPlan->name ?><br>
            Sistema operativo: <?= $operativeSystem->name ?>
        </p>
        <p>
            <strong>Ultimo pago</strong><br> 
            Fecha: <?= $lastPayment->created_at ?><br> 
            Valor: $<?= $lastPayment->amount ?>
        </p>

        <?php if ($active) { ?>
            <p>Tienes un pago pendiente por valor de $<?= $amount ?> desde : <?= $nextPaymentDate ?></p>
            <div class="col-12 mb-3">
                <button class="btn btn-primary me-2" type="submit" name="submit" value="submit">Realizar pago</button>
            </div>
        <?php } else { ?>
            <p>No tienes pagos pendientes, tu proxima fecha de pago es : <?= $nextPaymentDate ?></p>
        <?php } ?>
    </form>
</div>