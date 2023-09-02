<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '6ce4fcb665c659';
        $mail->Password = 'ec10046f2f9d8e';

        $mail->setFrom("cuentas@uptask.com");
        $mail->addAddress("cuentas@uptask.com", "uptask.com");
        $mail->Subject = "Confirma Tu Cuenta";
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Creado una cuenta en Uptask, solo debes confirmarla en el siguinete enlace</p>";
        $contenido .= "<p>Presione Aquí: <a href='http://localhost:8000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";
        //Asignamos el contenido al body del email
        $mail->Body = $contenido;
        //Enviamos el email
        $mail->send();
    }

    public function enviarInstrucciones()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '6ce4fcb665c659';
        $mail->Password = 'ec10046f2f9d8e';

        $mail->setFrom("cuentas@uptask.com");
        $mail->addAddress("cuentas@uptask.com", "uptask.com");
        $mail->Subject = "Recupera tu contraseña";
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Solicitado recuperar tu contraseña en Uptask, solo debes dar click en el siguinete enlace</p>";
        $contenido .= "<p>Presione Aquí: <a href='http://localhost:8000/reestablecer?token=" . $this->token . "'>Recuperar Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";
        //Asignamos el contenido al body del email
        $mail->Body = $contenido;
        //Enviamos el email
        $mail->send();
    }
}
