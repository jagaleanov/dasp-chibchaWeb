<div class="container">
    <h1 class="modal-title my-4">Detalles de cliente</h1>
    <hr>
    <div class="row">
        <div class="col">
            <dl class="row">
                <dt class="col-sm-3">Id</dt>
                <dd class="col-sm-9"><?= $customer->id ?></dd>

                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9"><?= $customer->name ?></dd>

                <dt class="col-sm-3">Apellido</dt>
                <dd class="col-sm-9"><?= $customer->last_name ?></dd>

                <dt class="col-sm-3">Correo electrónico</dt>
                <dd class="col-sm-9"><?= $customer->email ?></dd>

                <dt class="col-sm-3">Dirección</dt>
                <dd class="col-sm-9"><?= $customer->address ?></dd>

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

                            <p class="card-text h4"><?= $creditCard->number ?></p>
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

    <h2 class="modal-title my-4">Hosts</h2>

    <div class="list-group">
        <?php foreach ($hosts as $host) { ?>
            <div class="list-group-item list-group-item-action flex-column ">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= $host->ip ?></h3>
                        <p class="mb-1"><small>Id: <?= $host->id ?></small></p>
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
                        <?php if ($showActions) { ?>
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
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if ($showActions) { ?>
        <div class="d-flex flex-row-reverse my-2">
            <a href="<?= BASE_URL ?>/orders/add" class="btn btn-primary btn-lg btn-sm" role="button" aria-pressed="true">Nuevo hosting</a>
        </div>
    <?php } ?>

    <h2 class="modal-title my-4">Tickets de soporte</h2>
    <div class="list-group">
        <?php foreach ($tickets as $ticket) { ?>
            <div class="list-group-item list-group-item-action flex-column ">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <p class="mb-1 h5"><?= $ticket->description ?></p>
                        <p class="mb-1"><small>Id:</small> <?= $ticket->id ?></p>
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

    <h2 class="modal-title my-4">Solicitudes de dominios</h2>
    <div class="list-group">
        <?php foreach ($domains as $domain) { ?>
            <div class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <p class="mb-1 h5"><?= $domain->domain ?></p>
                        <p class="mb-1"><small>Id:</small> <?= $domain->id ?></p>
                        <p class="mb-1"><small>Host:</small> <?= $domain->ip ?></p>
                    </div>
                    <div>
                        <p class="mb-1"><small class="text-muted"><?= $domain->status == 1 ? 'APROBADO' : ($domain->status == 2 ? 'RECHAZADO' : 'PENDIENTE') ?></small></p>
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