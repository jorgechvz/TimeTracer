<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu mt-4">
        <div class="nav">
            <a class="nav-link" href="index.php">
                <div class="sb-nav-link-icon"><i class="bi bi-house-door-fill"></i></div>
                Inicio
            </a>
            <a class="nav-link" href="principal.php">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <a class="nav-link" href="distritos.php">
                <div class="sb-nav-link-icon"><i class="bi bi-compass-fill"></i></div>
                Distritos
            </a>

            <?php if ($tipo_usuario == 1) { ?>
                <div class="sb-sidenav-menu-heading">Administrador</div>
                <a class="nav-link" href="tabla.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Usuarios
                </a>
                <?php } ?>
                
            <div class="sb-sidenav-menu-heading">Perfil</div>
            <a class="nav-link" href="perfil.php">
                <div class="sb-nav-link-icon"><i class="bi bi-person-circle"></i></div>
                Mi Perfil
            </a>
                <!-- -->
        </div>
    </div>
</nav>