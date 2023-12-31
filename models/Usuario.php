<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "email", "password", "token", "confirmado"];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->token = $args["token"] ?? "";
        $this->confirmado = $args["confirmado"] ?? 0;
    }

    //Validacion para Cuentas Nuevas
    public function validarNuevaCuenta($password2): array
    {
        if (!$this->nombre) {
            self::$alertas["error"][] = "El nombre del usuario es obligatorio";
        }
        if (!$this->email) {
            self::$alertas["error"][] = "El email del usuario es obligatorio";
        }
        if (!$this->password) {
            self::$alertas["error"][] = "El password no puede ir vacio";
        }
        if (strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password debe contener al menos 6 caracteres";
        }
        if ($this->password !== $password2) {
            self::$alertas["error"][] = "Los password no coinciden";
        }
        return self::$alertas;
    }
    //Comporbamos el password
    public function comprobar_password($password_actual): bool
    {
        return password_verify($password_actual, $this->password);
    }
    //Hasheamos el Password
    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    //Generar un Token
    public function crearToken()
    {
        $this->token = uniqid();
    }
    //Validamos el email 
    public function validarEmail(): array
    {
        if (!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "Email no valido";
        }
        return self::$alertas;
    }
    // Validamos el password
    public function validarPassword(): array
    {
        if (!$this->password) {
            self::$alertas["error"][] = "El password no puede ir vacio";
        }
        if (strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password debe contener al menos 6 caracteres";
        }
        return self::$alertas;
    }
    // validamos el inicio de sesion del usuario
    public function validarLogin(): array
    {
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email es incorrecto";
        }
        if (!$this->password || strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password es incorrecto";
        }
        return self::$alertas;
    }
    //Validamos la actalizacion de los datos del perfil
    public function validarPerfil(): array
    {
        if (!$this->nombre) {
            self::$alertas["error"][] = "El nombre del usuario es obligatorio";
        }
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email no es valido";
        }
        return self::$alertas;
    }

    public function nuevo_password($password_actual, $password_nuevo)
    {
        if (!$password_actual) {
            self::$alertas["error"][] = "El password actual es obligatorio";
        }
        if (!$password_nuevo) {
            self::$alertas["error"][] = "El password nuevo es obligatorio";
        }
        if (strlen($password_nuevo) < 6) {
            self::$alertas["error"][] = "El password nuevo no puede tener menos de 6 caracteres";
        }
        return self::$alertas;
    }
}
