<div class="container">
<form method="post">
    <h1 class="modal-title my-4">Nuevo ticket de soporte</h1><hr>
    <p class="mb-4 h5">Host: <?=$host->ip?></p>
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