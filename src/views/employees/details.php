<div class="container">
    <h4 class="modal-title my-4">Detalles de empleado</h4>
    <div class="row">
        <div class="col">
            <dl class="row">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9"><?= $employee->name ?></dd>

                <dt class="col-sm-3">Apellido</dt>
                <dd class="col-sm-9"><?= $employee->last_name ?></dd>

                <dt class="col-sm-3">Correo electrónico</dt>
                <dd class="col-sm-9"><?= $employee->email ?></dd>

                <dt class="col-sm-3">Celular</dt>
                <dd class="col-sm-9"><?= $employee->mobile_phone ?></dd>

                <dt class="col-sm-3">Fecha de registro</dt>
                <dd class="col-sm-9"><?= $employee->created_at ?></dd>
            </dl>
        </div>
        <div class="col">
        </div>
    </div>

    <h4 class="modal-title my-4">Tickets de soporte solucionados</h4>
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

</div>