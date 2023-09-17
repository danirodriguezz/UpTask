<?php include_once __DIR__ . "/header-dashboard.php";?>
<div class="contenedor-l">
    <a href="/cambiar-password" class="enlace">Cambiar Password</a>
    <?php include_once __DIR__ . "/../templates/alertas.php"?>
    <form class="formulario" method="POST" action="/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" value="<?php echo $usuario->nombre ?? ""?>" name="nombre" placeholder="Tu nombre">
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input type="email" id="email" value="<?php echo $usuario->email ?? ""?>" name="email" placeholder="Tu e-mail">
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
</div>
<?php include_once __DIR__ . "/footer-dashboard.php";?>