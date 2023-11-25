<div class="container">
<form method="post">
    <h4 class="modal-title my-4">Nuevo cliente</h4>
    <div>
        <h5 class="mb-4">Datos del cliente</h5>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $post->get('name') ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="last_name" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $post->get('last_name') ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $post->get('email') ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" value="<?= $post->get('password') ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="address" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= $post->get('address') ?>" />
            </div>
        </div>

        <h5 class="mb-4">Datos del hosting</h5>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <label for="host_plan_id" class="form-label">Plan</label>
                <select class="form-control" id="host_plan_id" name="host_plan_id">
                    <option value=""></option>
                    <?php
                    foreach ($hostPlans as $hostPlan) {
                    ?>
                        <option value="<?= $hostPlan->id ?>" <?= $post->get('host_plan_id') == $hostPlan->id ? 'selected' : '' ?>>
                            <?= $hostPlan->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="operative_system_id" class="form-label">Sistema operativo</label>
                <select class="form-control" id="operative_system_id" name="operative_system_id">
                    <option value=""></option>
                    <?php
                    foreach ($operativeSystems as $operativeSystem) {
                    ?>
                        <option value="<?= $operativeSystem->id ?>" <?= $post->get('operative_system_id') == $operativeSystem->id ? 'selected' : '' ?>>
                            <?= $operativeSystem->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="payment_plan_id" class="form-label">Plan de pagos</label>
                <select class="form-control" id="payment_plan_id" name="payment_plan_id">
                    <option value=""></option>
                    <?php
                    foreach ($paymentPlans as $paymentPlan) {
                    ?>
                        <option value="<?= $paymentPlan->id ?>" <?= $post->get('payment_plan_id') == $paymentPlan->id ? 'selected' : '' ?>>
                            <?= $paymentPlan->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <p>Total: $<span id="totalValue">0</span></p>
        <input type="hidden" class="form-control" id="amount" name="amount" value="0" />

        <h5 class="mb-4">Datos del pago</h5>
        <h6 class="mb-4">Tarjeta de crédito</h6>

        <div class="row">
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_number" class="form-label">Número</label>
                <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" value="<?= $post->get('credit_card_number') ?>" />
            </div>
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_type" class="form-label">Emisor</label>
                <input type="text" class="form-control" id="credit_card_type" name="credit_card_type" value="<?= $post->get('credit_card_type') ?>" readonly />

            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="credit_card_name" class="form-label">Nombre en la tarjeta</label>
                <input type="text" class="form-control" id="credit_card_name" name="credit_card_name" value="<?= $post->get('credit_card_name') ?>" />
            </div>
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_month" class="form-label">Mes</label>
                <select class="form-control" id="credit_card_month" name="credit_card_month">
                    <option value=""></option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option value="<?= $i ?>" <?= $post->get('credit_card_month') == $i ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_year" class="form-label">Año</label>
                <select class="form-control" id="credit_card_year" name="credit_card_year">
                    <option value=""></option>
                    <?php
                    for ($i = 0; $i < 15; $i++) {
                    ?>
                        <option value="<?= date('Y') + $i ?>" <?= $post->get('credit_card_year') == date('Y') + $i ? 'selected' : '' ?>>
                            <?= date('Y') +  $i ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="credit_card_code" class="form-label">Código de verificación</label>
                <input type="text" class="form-control" id="credit_card_code" name="credit_card_code" value="<?= $post->get('credit_card_code') ?>" />
            </div>
        </div>


        <div class="col-12 mb-3">
            <button class="btn btn-primary me-2" type="submit" name="submit" value="submit">Comprar</button>
        </div>
    </div>

</form>
</div>