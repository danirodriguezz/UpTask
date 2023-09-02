<div class="contenedor reestablecer">
    <?php include_once __DIR__ . "./../templates/nombre-sitio.php" ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Introduce Tu Nuevo Password</p>
        <?php include_once __DIR__ . "./../templates/alertas.php" ?>
        <?php if($mostrar): ?>        
        <form class="formulario" method="POST">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Nuevo Password" name="password">
            </div>
            <input type="submit" class="boton" value="Guardar Password">
        </form>
        <?php endif ?>        
        <div class="acciones clearfix">
            <a href="/">¿Ya Tienes una Cuenta? Inicia Sesión </a>
            <a href="/crear">¿Aun no tienes una cuenta? Crea una</a>
        </div>
    </div>
</div>