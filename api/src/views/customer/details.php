<div class="container">
    <h4 class="modal-title my-4">Detalles</h4>

    <dl class="row">
        <dt class="col-sm-3">Nombre</dt>
        <dd class="col-sm-9"><?=$customer->name?></dd>

        <dt class="col-sm-3">Apellido</dt>
        <dd class="col-sm-9"><?=$customer->last_name?></dd>

        <dt class="col-sm-3">Correo electrónico</dt>
        <dd class="col-sm-9"><?=$customer->email?></dd>

        <dt class="col-sm-3">Fecha de registro</dt>
        <dd class="col-sm-9"><?=$customer->created_at?></dd>

        <dt class="col-sm-3">Fecha de actualización</dt>
        <dd class="col-sm-9"><?=$customer->updated_at?></dd>
    </dl>

    <h4 class="modal-title my-4">Hosts</h4>

    <div class="list-group">
        <?php foreach($hosts as $host){ ?> 
        <div class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?=$host->ip?></h5>
                <small class="text-muted">3 days ago</small>
            </div>
            <p class="mb-1">Chibcha-Platino</p>
            <p class="mb-1"><small class="text-muted">Linux/Mensual</small></p>
            <a href="#" class="btn btn-primary btn-lg btn-sm me-1" role="button" aria-pressed="true">Iniciar ticket de
                soporte</a>
            <a href="#" class="btn btn-primary btn-lg btn-sm" role="button" aria-pressed="true">Solicitar dominio</a>
        </div>
        <!-- <div class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">127.0.0.1</h5>
                <small class="text-muted">3 days ago</small>
            </div>
            <p class="mb-1">Chibcha-Platino</p>
            <p class="mb-1"><small class="text-muted">Linux/Mensual</small></p>
            <a href="#" class="btn btn-primary btn-lg btn-sm me-1" role="button" aria-pressed="true">Iniciar ticket de
                soporte</a>
            <a href="#" class="btn btn-primary btn-lg btn-sm" role="button" aria-pressed="true">Solicitar dominio</a>
        </div> -->
        <?php } ?>
    </div>
    <div class="d-flex flex-row-reverse my-2">
        <a href="#" class="btn btn-primary btn-lg btn-sm" role="button" aria-pressed="true">Comprar un nuevo hosting</a>
    </div>

    <h4 class="modal-title my-4">Tickets de soporte</h4>

    <h4 class="modal-title my-4">Solicitudes de dominios</h4>

</div>