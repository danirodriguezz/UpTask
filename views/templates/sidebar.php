<aside class="sidebar">
    <h2>UpTask</h2>
    <button class="sidebar-hamburguesa">
        <div class="line1-haburguesa"></div>
        <div class="line2-haburguesa"></div>
        <div class="line3-haburguesa"></div>
    </button>
    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo === "Proyectos") ? "activo" : ""; ?> nav_link" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === "Crear Proyectos") ? "activo" : ""; ?> nav_link" href="/crear-proyectos">Crear Proyectos</a>
        <a class="<?php echo ($titulo === "Perfil") ? "activo" : ""; ?> nav_link" href="/perfil">Perfil</a>
    </nav>
</aside>