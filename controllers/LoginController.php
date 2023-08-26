<?php
namespace Controllers;

use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        if($_SERVER["REQUEST_METHOD"] === "POST") {

        }
        //Render de la vista
        $router->render("auth/login", [
            "titulo" => "Iniciar Sesión"
        ]);
    }

    public static function logout() {
        echo "Desde Logout";
    }

    public static function crear(Router $router) {
        if($_SERVER["REQUEST_METHOD"] === "POST") {

        }
        //Render de la vista
        $router->render("auth/crear", [
            "titulo" => "Crea tu Cuenta"
        ]);
    }

    public static function olvide() {
        echo "Desde Olvide";
        if($_SERVER["REQUEST_METHOD"] === "POST") {

        }
    }


    public static function reestablecer() {
        echo "Desde reestablecer";
        if($_SERVER["REQUEST_METHOD"] === "POST") {

        }
    }

    public static function mensaje() {
        echo "Desde Mensaje";
    }

    public static function confirmar() {
        echo "Desde Confirmar";
    }
}