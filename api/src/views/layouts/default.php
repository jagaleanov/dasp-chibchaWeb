<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <title>Chibchaweb</title>
</head>

<body>
    <!-- Aquí podrías incluir tu barra de navegación, por ejemplo -->

    <!-- Contenido de la Vista -->


    <!-- Contenedor principal fluido que se expande al ancho completo -->
    <div class="container-fluid p-0">
    <?=$this->getModule('navBar') ?>

        <!--MENSAJES DEL SISTEMA------------------------------------------------->
        <div style="position:fixed; z-index:10000; top:30px; left:0; width:100%;">
            <?php
            if ($this->getMessages()) {
                foreach ($this->getMessages() as $type => $messages) {
            ?>
                    <div class="alert d-block text-center p-0" style="width:90%; max-width:800px; margin:auto; border: solid 1px #333;">
                        <div class="card shadow">
                            <div class="card-header bg-<?= $type ?> text-white p-1">
                                <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="font-size: 2em; margin: -0.1em 0.3em;">
                                    <span aria-hidden="true">&times;</span>
                                </button> -->
                                <button type="button" class="btn-close" aria-label="Close" onclick="closeAlert(this)"></button>
                            </div>
                            <div id="message" class="card-body pt-2">
                                <?php foreach ($messages as $msg) { ?>
                                    <p style="margin: 0;"><?php print_r($msg) ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

            <?php
                }
            }
            ?>
        </div>
        <!-- Contenedor principal donde se cargarán dinámicamente los componentes de la SPA -->
        <?=$content_for_layout ?>
        <!-- built files will be auto injected -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script>
        <?= $this->getScript() ?>
    </script>
</body>

</html>