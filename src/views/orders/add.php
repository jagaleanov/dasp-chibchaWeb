<div class="container">
<form method="post">
    <h1 class="modal-title my-4">Nueva orden</h1>
    <div>
        <h2 class="mb-4">Datos del cliente</h2>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $user->name ?>" readonly disabled/>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="last_name" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user->last_name ?>" readonly disabled/>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user->email ?>" readonly disabled/>
            </div>
        </div>

        <h2 class="mb-4">Datos del hosting</h2>
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

        <h2 class="mb-4">Datos del pago</h2>
        <h3 class="mb-4">Tarjeta de crédito</h3>

        <div class="row">
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_number" class="form-label">Número</label>
                <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" value="<?= $creditCard->number ?>" readonly disabled/>
            </div>
            <div class="col-sm-3 col-6 mb-3">
                <label for="credit_card_type" class="form-label">Emisor</label>
                <input type="text" class="form-control" id="credit_card_type" name="credit_card_type" value="<?= $creditCard->type ?>" readonly disabled/>

            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="credit_card_name" class="form-label">Nombre en la tarjeta</label>
                <input type="text" class="form-control" id="credit_card_name" name="credit_card_name" value="<?= $creditCard->name ?>" readonly disabled/>
            </div>
        </div>


        <div class="col-12 mb-3">
            <button class="btn btn-primary me-2" type="submit" name="submit" value="submit">Comprar</button>
        </div>
    </div>

</form>
</div>