<?php

namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                $usuario = Usuario::where("email", $auth->email);
                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta("error", "El usuario no existe o no esta confirmado");
                } else {
                    //El Usuario existe y tenemos que comprobar su password
                    if(password_verify($_POST["password"], $usuario->password)) {
                        //Iniciamos la sesion del usuario
                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;
                        //Redireccionamos
                        header("Location: /proyectos");
                    } else {
                        Usuario::setAlerta("error", "El usuario o contraseña es incorrecta");
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        //Render de la vista
        $router->render("auth/login", [
            "titulo" => "Iniciar Sesión",
            "alertas" => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario;
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta($_POST["password2"]);
            if (empty($alertas)) {
                $existeUsuario = Usuario::where("email", $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta("error", "El Usuario ya Esta Registrado");
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hasheamos el password
                    $usuario->hashPassword();
                    //Generamos un Token
                    $usuario->crearToken();
                    //Guardamos El nuevo Usuario
                    $resultado = $usuario->guardar();
                    //Enviamos Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    if ($resultado) {
                        header("Location: /mensaje");
                    }
                }
            }
        }
        //Render de la vista
        $router->render("auth/crear", [
            "titulo" => "Crea tu Cuenta",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)) {
                //Buscamos el usuario
                $usuario = Usuario::where("email", $usuario->email);
                if($usuario && $usuario->confirmado) {
                    //Generamos  un nuevo token
                    $usuario->crearToken();
                    //Actualizamos el usuario
                    $usuario->guardar();
                    //Enviamos email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //imprimir Alerta
                    Usuario::setAlerta("exito", "Hemos enviado las instrucciones a tu email");
                } else {
                    Usuario::setAlerta("error", "El usuario no existe o no esta confirmado");
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/olvide", [
            "titulo" => "Olvide mi Password",
            "alertas" => $alertas
        ]);
    }


    public static function reestablecer(Router $router)
    {
        $token = s($_GET["token"]);
        $mostrar = true;
        if(!$token) header("Location: /");
        // Identificar el usuario con este token
        $usuario = Usuario::where("token", $token);
        if(empty($usuario)) {
            Usuario::setAlerta("error", "Token no válido");
            $mostrar = false;
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //Añadir el nuevo password
            $usuario->sincronizar($_POST);
            //Validamos el password
            $alertas = $usuario->validarPassword();
            if(empty($alertas)) {
                //Hasheamos el nuevo password
                $usuario->hashPassword();
                //Eliminar el Token 
                $usuario->token = null;
                //Guardar el Usuario
                $resultado = $usuario->guardar();
                //redireccionar al usuario
                if($resultado) {
                    header("Location: /");
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/reestablecer", [
            "titulo" => "Reestabelecer Password",
            "alertas" => $alertas,
            "mostrar" => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render("auth/mensaje", [
            "titulo" => "Mensaje Confirmado"
        ]);
    }

    public static function confirmar(Router $router)
    {
        $token = s($_GET["token"]);
        if(!$token) header("Location: /");
        //Encontrar al Usuario
        $usuario = Usuario::where("token", $token);
        if(empty($usuario)) {
            //No se encontro un usuario con ese token
            Usuario::setAlerta("error", "Token no válido");
        } else {
            //Confirmar la Cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            //Guardamos El Usuario
            $usuario->guardar();
            Usuario::setAlerta("exito", "El usuario ha sido confirmado correctamente");
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/confirmar", [
            "titulo" => "Cuenta Confirmada",
            "alertas" => $alertas
        ]);
    }
}
