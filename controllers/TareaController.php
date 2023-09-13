<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController
{
    public static function index()
    {
        session_start();
        $proyectoID = $_GET["url"];
        if(!$proyectoID) {
            header("Location: /dashboard");
        };
        $proyecto = Proyecto::where("url", $proyectoID);
        if(!$proyecto || $proyecto->propietarioid !== $_SESSION["id"]) {
            header("Location: /dashboard");
        };
        $tareas = Tarea::belongsTo("proyectoId", $proyecto->id);
        echo json_encode(["tareas" => $tareas]);
    }

    public static function crear()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            session_start();
            $proyectoId = $_POST["proyectoId"];
            $proyecto = Proyecto::where("url", $proyectoId);
            if(!$proyecto || $proyecto->propietarioid !== $_SESSION["id"]) {
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un error al agregar la tarea"
                ];
                echo json_encode($respuesta);
                return;
            }
            //Todo ha salido bien y tenemos que guardar la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                "tipo" => "exito",
                "id" => $resultado["id"],
                "mensaje" => "Tu tarea se ha agregado correctamente"
            ];
            echo json_encode($respuesta);
        };
    }

    public static function actualizar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
        };
    }

    public static function eliminar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
        };
    }
}
