<div class="container">
    <form method="post">
        <h4 class="modal-title my-4">Editar Empleado</h4>
        <div class="row">
            <div class="col-sm-6 col-12 mb-3">
                <label for="role_id" class="form-label">Rol</label>
                <select class="form-control" id="role_id" name="role_id">
                    <option value=""></option>
                    <?php
                    foreach ($roles as $role) {
                    ?>
                        <option value="<?= $role->id ?>" <?= ($post->get('role_id') ? $post->get('role_id') : $employee->role_id) == $role->id ? 'selected' : '' ?>>
                            <?= $role->name ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= ($post->get('name') ? $post->get('name') : $employee->name) ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="last_name" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= ($post->get('last_name') ? $post->get('last_name') : $employee->last_name) ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="<?= ($post->get('email') ? $post->get('email') : $employee->email) ?>" />
            </div>
            <div class="col-sm-6 col-12 mb-3">
                <label for="mobile_phone" class="form-label">Celular</label>
                <input type="tel" class="form-control" id="mobile_phone" name="mobile_phone" value="<?= ($post->get('mobile_phone') ? $post->get('mobile_phone') : $employee->mobile_phone) ?>" />
            </div>
        </div>
        <div class="col-12 mb-3">
            <button class="btn btn-primary me-2" type="submit" name="submitEmployee" value="submitEmployee">Guardar empleado</button>
        </div>
    </form>
    <form method="post">
        <h5 class="modal-title my-4">Editar Constraseña</h5>
        <div class="col-sm-6 col-12 mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" value="<?= $post->get('password') ?>" />
        </div>
        <div class="col-12 mb-3">
            <button class="btn btn-primary me-2" type="submit" name="submitPassword" value="submitPassword">Guardar contraseña</button>
        </div>
    </form>
</div>