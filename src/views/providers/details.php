<div class="container">
    <h4 class="modal-title my-4">Detalles de proveedor de dominios</h4>
    <div class="row">
        <div class="col">
            <dl class="row">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9"><?= $provider->name ?></dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Precio anual por dominio</dt>
                <dd class="col-sm-9">$ <?= $provider->price ?></dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Solicitudes de dominio</dt>
                <dd class="col-sm-9"><?= $requestsCounter ?></dd>
            </dl>
            <!-- <dl class="row">
                <dt class="col-sm-3">Solicitudes aprobadas</dt>
                <dd class="col-sm-9"><?= $approvedCounter ?></dd>
            </dl> -->
        </div>
        <div class="col">
            <div class="card border-dark mb-3">
                <div class="card-header">Comisión</div>
                <div class="card-body text-dark">
                    <div class="row">
                        <div class="col">

                            <h6 class="card-title">Solicitudes aprobadas</h6>
                            <p class="card-text"><?= $approvedCounter ?></p>
                            <h6 class="card-title">Total costo anual</h6>
                            <p class="card-text">$ <?= $commission['totalCost'] ?></p>
                        </div>
                        <div class="col">
                            <h6 class="card-title">Tipo de proveedor</h6>
                            <p class="card-text"><?=$commission['providerType'] ?></p>
                            <h6 class="card-title">Valor de la comisión</h6>
                            <p class="card-text">(<?=$commission['comissionValue'] ?>%) $ <?=$commission['comissionTotal'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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