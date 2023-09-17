<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();
        isAuth();
        $id = $_SESSION["id"];
        $proyectos = Proyecto::belongsTo("propietarioid", $id);
        $script = "<script src='build/js/dashboard.js'></script>";
        $router->render("dashboard/index", [
            "titulo" => "Proyectos",
            "proyectos" => $proyectos,
            "script" => $script
        ]);
    }
    public static function crear_proyectos(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        $script = "<script src='build/js/dashboard.js'></script>";
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $proyecto = new Proyecto($_POST);
            //validación
            $alertas = $proyecto->validarProyecto();
            if (empty($alertas)) {
                //Generamos una url unica
                $proyecto->url = md5(uniqid());
                //Almacenar el creador del proyecto
                $proyecto->propietarioid = $_SESSION["id"];
                //Guardamos el proyecto
                $proyecto->guardar();
                //Redireccionamos
                header("Location: /proyecto?url=" . $proyecto->url);
            }
        };
        $router->render("dashboard/crear-proyectos", [
            "titulo" => "Crear Proyectos",
            "alertas" => $alertas,
            "script" => $script
        ]);
    }
    public static function perfil(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION["id"]);
        $script = " <script src='build/js/dashboard.js'></script> ";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();
            if (empty($alertas)) {
                //Comporbar si existe un Usuario
                $existeUsuario = Usuario::where("email", $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta("error", "El email ya esta registrado");
                    $alertas = $usuario->getAlertas();
                } else {
                    //Guardar los cambios
                    $usuario->guardar();
                    Usuario::setAlerta("exito", "Guardado Correctamente");
                    $alertas = $usuario->getAlertas();
                    //Asignar el nuevo nombre a la sesion
                    $_SESSION["nombre"] = $usuario->nombre;
                }
            }
        }
        $router->render("dashboard/perfil", [
            "titulo" => "Perfil",
            "script" => $script,
            "alertas" => $alertas,
            "usuario" => $usuario
        ]);
    }
    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();
        $token = $_GET["url"];
        if (!$token) header("Location: /dashboard");
        //Revisamos que la persona que visita el  proyecto es igual a quien lo creo
        $proyecto = Proyecto::where("url", $token);
        if ($proyecto->propietarioid !== $_SESSION["id"]) header("Location: /dashboard");
        $script = "<script src='build/js/dashboard.js'></script>";
        $script .= " <script src='build/js/tareas.js'></script>";
        $script .= " <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        $router->render("dashboard/proyecto", [
            "titulo" => $proyecto->proyecto,
            "script" => $script
        ]);
    }

    public static function cambiar_password(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        $script = "<script src='build/js/dashboard.js'></script>";
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = Usuario::find("id", $_SESSION["id"]);
            $passwordNuevo = $_POST["password_nuevo"];
            $passwordActual = $_POST["password_actual"];
            $alertas = $usuario->nuevo_password($passwordActual, $passwordNuevo);
            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password($passwordActual);
                if($resultado) {
                    //Asignamos el nuevo password
                    $usuario->password = $passwordNuevo;
                    $usuario->hashPassword();
                    //Guardamos los cambios
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        Usuario::setAlerta("exito", "Contraseña cambiada con exito");
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta("error", "La contraseña es incorrecta");
                    $alertas = $usuario->getAlertas();
                }
            }
        }
        $router->render("dashboard/cambiar-password", [
            "titulo" => "Cambiar Password",
            "script" => $script,
            "alertas" => $alertas
        ]);
    }
}
