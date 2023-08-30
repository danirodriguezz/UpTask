<div class="contenedor crear">
    <?php include_once __DIR__ . "./../templates/nombre-sitio.php" ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        <form class="formulario" action="/" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre Completo" name="nombre">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password" />
            </div>
            <div class="campo">
                <label for="password2">Repite tu Password</label>
                <input type="password" id="password2" placeholder="Repite Tu Password" name="password2" />
            </div>
            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/">¿Ya Tenias una Cuenta? Inicia Sesión </a>
            <a href="/olvide">¿Olvidáste tu password?</a>
        </div>
    </div>
</div>