<div class="contenedor login">
    <h1 class="uptask">UpTaks</h1>
    <p class="tagline">Crea y Administra tus Proyectos</p>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesion</p>
        <form class="formulario" action="/" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password" />
            </div>
            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aun no tienes una Cuenta? Crea Una </a>
            <a href="/olvide">Olvidáste tu password</a>
        </div>
    </div>
</div>