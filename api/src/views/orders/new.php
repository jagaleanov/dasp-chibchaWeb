<form method="post">
    <h4 class="mb-4">Nuevo cliente</h4>
    <div class="row">

        <h5 class="mb-4">Datos del cliente</h5>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="last_name" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="last_name" name="last_name" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="address" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="address" name="address" />
            </div>
        </div>

        <h5 class="mb-4">Datos del hosting</h5>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <label for="host_plan" class="form-label">Plan</label>
                <select class="form-control" id="host_plan" name="host_plan">
                    <option value=""></option>
                    <?php
                    foreach ($hostPlans as $hostPlan) {
                    ?>
                        <option value="<?= $hostPlan->id ?>">
                            <?= $hostPlan->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="operative_system" class="form-label">Sistema operativo</label>
                <select class="form-control" id="operative_system" name="operative_system">
                    <option value=""></option>
                    <?php
                    foreach ($operativeSystems as $operativeSystem) {
                    ?>
                        <option value="<?= $operativeSystem->id ?>">
                            <?= $operativeSystem->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="payment_plan" class="form-label">Plan de pagos</label>
                <select class="form-control" id="payment_plan" name="payment_plan">
                    <option value=""></option>
                    <?php
                    foreach ($paymentPlans as $paymentPlan) {
                    ?>
                        <option value="<?= $paymentPlan->id ?>">
                            <?= $paymentPlan->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <p>Total: $<span id="totalValue">0</span></p>
        <input type="hidden" class="form-control" id="amount" name="amount" value=""/>

        <h5 class="mb-4">Datos del pago</h5>
        <h6 class="mb-4">Tarjeta de crédito</h6>

        <div class="row">
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_number" class="form-label">Número</label>
                <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" />
            </div>
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_type" class="form-label">Emisor</label>
                <input type="text" class="form-control" id="credit_card_type" name="credit_card_type" readonly/>

            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="credit_card_name" class="form-label">Nombre en la tarjeta</label>
                <input type="text" class="form-control" id="credit_card_name" name="credit_card_name" />
            </div>
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_month" class="form-label">Mes</label>
                <select class="form-control" id="credit_card_month" name="credit_card_month">
                    <option value=""></option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option value="<?= $i ?>">
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
                        <option value="<?= date('Y') + $i ?>">
                            <?= date('Y') +  $i ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="credit_card_code" class="form-label">Código de verificación</label>
                <input type="text" class="form-control" id="credit_card_code" name="credit_card_code" />
            </div>
        </div>


        <div class="col-12 mb-3">
            <button class="btn btn-primary me-2" type="submit" name="submit">Comprar</button>
        </div>
    </div>

</form>