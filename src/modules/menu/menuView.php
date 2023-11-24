<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <a id="dynamicDomainLink" class="navbar-brand border rounded px-2" href="<?= BASE_URL ?>/home"><i class="bi bi-globe2"></i>
            ChibchaWeb</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php foreach ($links as $link) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url ?>">
                            <i class="bi bi-<?= $link->icon ?>"></i> <?= $link->label ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="d-flex">
                <?php if ($login) { ?>
                    <a class="btn btn-outline-light" href="<?= BASE_URL ?>/login">Iniciar sesión</a>
                <?php } else { ?>
                    <a class="btn btn-outline-light" href="<?= BASE_URL ?>/logout">Cerrar sesión</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>