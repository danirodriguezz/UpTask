<?php include_once __DIR__ . "/header-dashboard.php";?>
<div class="contenedor-l">
    <a href="/perfil" class="enlace">Volver al Perfil</a>
    <?php include_once __DIR__ . "/../templates/alertas.php"?>
    <form class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="nombre">Password Actual</label>
            <input type="password" id="nombre" name="password_actual" placeholder="Tu password actual">
        </div>
        <div class="campo">
            <label for="password_nuevo">Password Nuevo</label>
            <input type="password" id="password_nuevo" name="password_nuevo" placeholder="Tu password nuevo">
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
</div>
<?php include_once __DIR__ . "/footer-dashboard.php";?>