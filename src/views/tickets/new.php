<div class="container">
<form method="post">
    <h4 class="modal-title my-4">Nuevo ticket de soporte</h4>
    <h5 class="mb-4">Host: <?=$host->ip?></h5>
    <div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción del problema</label>
            <textarea class="form-control" id="description" name="description" rows="7"><?= $post->get('description') ?></textarea>
        </div>


        <div class="col-12 mb-3">
            <button class="btn btn-primary me-2" type="submit" name="submit" value="submit">Enviar</button>
        </div>
    </div>

</form>
</div>