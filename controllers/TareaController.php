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
                "mensaje" => "Tu tarea se ha agregado correctamente",
                "proyectoId" => $proyecto->id
            ];
            echo json_encode($respuesta);
        };
    }

    public static function actualizar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            session_start();
            //Validamos que el proyecto exista
            $proyecto = Proyecto::where("url", $_POST["proyectoId"]);
            if(!$proyecto || $proyecto->propietarioid !== $_SESSION["id"]) {
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un Error al Actualizar la tarea"
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if($resultado) {
                $respuesta = [
                    "tipo" => "exito",
                    "id" => $tarea->id,
                    "proyectoId" => $proyecto->id,
                    "mensaje" => "Actualizado correctamente"
                ];
                echo json_encode(["respuesta" => $respuesta]);
                return;
            }
        };
    }

    public static function eliminar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            session_start();
            $tarea = Tarea::where("id", $_POST["id"]);
            $proyecto = Proyecto::where("url", $_POST["proyectoId"]);
            if(!$tarea || $proyecto->propietarioid !== $_SESSION["id"]) {
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "No se ha podido eliminar la tarea"
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();
            echo json_encode($resultado);
        };
    }
}
