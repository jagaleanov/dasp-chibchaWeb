<div class="container">
    <form method="post">
        <h4 class="modal-title my-4">Nuevo dominio</h4>
        <h5 class="mb-4">Host: <?= $host->ip ?></h5>
        <p>Llene la solicitud de dominio y seleccione un proveedor de doiminios. 
            Al finalizar su solicitud quedara pendiente para ser aprobada por el proveedor.</p>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <div class="mb-3">
                    <label for="domain" class="form-label">Dominio</label>
                    <input type="text" id="domain" class="form-control" name="domain" value="<?= $post->get('domain') ?>" />
                </div>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="provider_id" class="form-label">Proveedor</label>
                <select class="form-control" id="provider_id" name="provider_id">
                    <option value=""></option>
                    <?php
                    foreach ($providers as $provider) {
                    ?>
                        <option value="<?= $provider->id ?>" <?= $post->get('provider_id') == $provider->id ? 'selected' : '' ?>>
                            <?= $provider->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>


            <div class="col-12 mb-3">
                <button class="btn btn-primary me-2" type="submit" name="submit" value="submit">Solicitar</button>
            </div>
        </div>
    </form>
</div>