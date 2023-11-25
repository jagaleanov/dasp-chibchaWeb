<div class="container">
    <h4 class="modal-title my-4">Detalles</h4>
    <div class="row">
        <div class="col">
            <dl class="row">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9"><?= $customer->name ?></dd>

                <dt class="col-sm-3">Apellido</dt>
                <dd class="col-sm-9"><?= $customer->last_name ?></dd>

                <dt class="col-sm-3">Correo electrónico</dt>
                <dd class="col-sm-9"><?= $customer->email ?></dd>

                <dt class="col-sm-3">Fecha de registro</dt>
                <dd class="col-sm-9"><?= $customer->created_at ?></dd>
            </dl>
        </div>
        <div class="col">
            <div class="card border-dark mb-3">
                <div class="card-header">Tarjeta de crédito</div>
                <div class="card-body text-dark">
                    <div class="row">
                        <div class="col">

                            <h5 class="card-title"><?= $creditCard->number ?></h5>
                            <p class="card-text"><?= $creditCard->type ?></p>
                            <p class="card-text"><?= $creditCard->name ?></p>
                        </div>
                        <div class="col">
                            <p class="card-text pt-5"><?= $creditCard->expiration_month ?>/<?= $creditCard->expiration_year ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="modal-title my-4">Hosts</h4>

    <div class="list-group">
        <?php foreach ($hosts as $host) { ?>
            <div class="list-group-item list-group-item-action flex-column ">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <h5 class="mb-1"><?= $host->ip ?></h5>
                        <?php if (count($host->domains) > 0) { ?>
                            <p class="mb-1"><small>Dominios:</small></p>

                            <?php foreach ($host->domains as $domain) { ?>
                                <p class="mb-1"><?= $domain->domain ?></p>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div>
                        <p class="mb-1"><?= $host->host_plan_name ?></p>
                        <p class="mb-1"><small class="text-muted">Sistema operativo:<br> <?= $host->operative_system_name ?></small></p>
                        <p class="mb-1"><small class="text-muted">Plan de pagos:<br> <?= $host->payment_plan_name ?></small></p>
                    </div>
                    <div class="text-right align-items-end">
                        <p><small class="text-muted"><?= $host->created_at ?></small></p>
                        <p>
                            <a href="<?= BASE_URL ?>/tickets/new/<?= $host->id ?>" class="btn btn-primary btn-lg btn-sm me-1" role="button" aria-pressed="true">
                                Nuevo ticket
                            </a>
                        </p>
                        <p>
                            <a href="<?= BASE_URL ?>/domains/new/<?= $host->id ?>" class="btn btn-primary btn-lg btn-sm me-1" role="button" aria-pressed="true">
                                Nuevo dominio
                            </a>
                        </p>
                        <p>
                            <a href="<?= BASE_URL ?>/payments/new/<?= $host->id ?>" class="btn btn-primary btn-lg btn-sm me-1" role="button" aria-pressed="true">
                                Realizar pago
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="d-flex flex-row-reverse my-2">
        <!-- <a href="#" class="btn btn-primary btn-lg btn-sm" role="button" aria-pressed="true">Nuevo hosting</a> -->
    </div>

    <h4 class="modal-title my-4">Tickets de soporte</h4>
    <div class="list-group">
        <?php foreach ($tickets as $ticket) { ?>
            <div class="list-group-item list-group-item-action flex-column ">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <h5 class="mb-1"><?= $ticket->description ?></h5>
                        <p class="mb-1"><small>Host:</small> <?= $ticket->ip ?></p>
                    </div>
                    <div>
                        <p class="mb-1"><small class="text-muted"><?= $ticket->status == 1 ? 'SOLUCIONADO' : 'PENDIENTE' ?></small></p>
                    </div>
                    <div class="text-right align-items-end">
                        <p><small class="text-muted"><?= $ticket->created_at ?></small></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <h4 class="modal-title my-4">Solicitudes de dominios</h4>
    <div class="list-group">
        <?php foreach ($domains as $domain) { ?>
            <div class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <h5 class="mb-1"><?= $domain->domain ?></h5>
                        <p class="mb-1"><small>Host:</small> <?= $domain->ip ?></p>
                    </div>
                    <div>
                        <p class="mb-1"><small class="text-muted"><?= $domain->status == 1 ? 'APROBADO' : ($domain->status == 2 ? 'NO APROBADO' : 'PENDIENTE') ?></small></p>
                    </div>
                    <div>
                        <p><small class="text-muted"><?= $domain->created_at ?></small></p>
                        <p><small class="text-muted"><?= $domain->updated_at ?></small></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>