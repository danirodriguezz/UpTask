(function () {
    //Boton para mostrar el Modal para Agregar Tarea
    const nuevaTareaBtn = document.querySelector("#agregar-tarea");
    nuevaTareaBtn.addEventListener("click", mostrarFormulario);

    function mostrarFormulario() {
        const modal = document.createElement("DIV");
        modal.classList.add("modal");
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Añade una Nueva Tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="Nueva Tarea" id="tarea"/>
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea"/>
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>`;
        setTimeout(() => {
            const formulario = document.querySelector(".formulario");
            formulario.classList.add("animar");
        }, 0);

        modal.addEventListener("click", function (e) {
            e.preventDefault();
            if (e.target.classList.contains("cerrar-modal")) {
                const formulario = document.querySelector(".formulario");
                formulario.classList.add("cerrar");
                setTimeout(() => {
                    modal.remove();
                }, 500);
            };
            if (e.target.classList.contains("submit-nueva-tarea")) {
                submitFormularioNuevaTarea();
            }
        });

        document.querySelector(".dashboard").appendChild(modal);
    }

    function submitFormularioNuevaTarea() {
        const tarea = document.querySelector("#tarea").value.trim();
        if (tarea === "") {
            //Mostramos una alerta de error
            mostrarAlerta("El nombre de la tarea es obligatorio", "error", document.querySelector(".formulario legend"));
            return;
        }
        agregarTarea(tarea);
    }

    //Muestra un mensjae en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {
        //Prevenir la creacion de multiples alertas
        const alertaPrevia = document.querySelector(".alerta");
        if (alertaPrevia) {
            alertaPrevia.remove();
        }
        const alerta = document.createElement("DIV");
        alerta.classList.add("alerta", tipo);
        alerta.textContent = mensaje;
        // referencia.appendChild(alerta);
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
        //Eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 4000);
    }

    //Consultar el servidor para añadir una nueva tarea al proyecto actual
    async function agregarTarea(tarea) {
        //Contruimos la peticion
        const datos = new FormData();
        datos.append("nombre", tarea);
        datos.append("proyectoId", obtenerProyecto());

        try {
            const url = "http://localhost:8000/api/tareas";
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });

            const resultado = await respuesta.json();
            console.log(resultado);
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".formulario legend"));
            if(resultado.tipo === "exito") {
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    const formulario = document.querySelector(".formulario");
                    formulario.classList.add("cerrar");
                    setTimeout(() => {
                        modal.remove();
                    }, 500);
                }, 1000);
            }

        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url;
    }
})();