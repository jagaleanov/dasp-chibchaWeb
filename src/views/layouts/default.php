<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <title>Chibchaweb</title>
</head>

<body>
    <div class="container-fluid p-0">


        <?php if ($this->getMessages()) { ?>
            <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <?php foreach ($this->getMessages() as $type => $messages) { ?>

                            <div class="modal-header bg-<?= $type ?>-subtle text-emphasis-<?= $type ?>">
                                <h5 class="modal-title" id="modalLabel">Mensaje del sistema</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <?php if (is_array($messages)) { ?>
                                    <ul>
                                        <?php foreach ($messages as $msg) { ?>
                                            <li><?= $msg ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } else { ?>
                                    <p><?= $messages ?></p>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>




        <!-- Contenedor de la barra de navegacion -->
        <?= $navBar ?>

        <!-- Contenedor principal donde se cargarán dinámicamente las vistas -->
        <?= $contentForLayout ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script>
        <?= $this->getScript() ?>
    </script>
    <script>
        $(document).ready(function() {
            // Muestra el modal automáticamente al cargar la página
            $("#miModal").modal('show');
        });
    </script>
</body>

</html>