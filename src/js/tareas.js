(function () {
    obtenerTareas();
    let tareas = [];
    let filtradas = [];
    //Boton para mostrar el Modal para Agregar Tarea
    const nuevaTareaBtn = document.querySelector("#agregar-tarea");
    nuevaTareaBtn.addEventListener("click", () => {
        mostrarFormulario();
    });

    //Filtros de Busqueda
    const filtros = document.querySelectorAll("#filtros input[type='radio']");
    filtros.forEach(filtro => {
        filtro.addEventListener("input", filtrarTarea);
    });

    function filtrarTarea(e) {
        const filtro = e.target.value;
        if (filtro !== "") {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
            mostrarTareas();
        } else {
            filtradas = [];
            mostrarTareas();
        }
    }

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    };

    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletas();
        const arrayTareas = filtradas.length ? filtradas : tareas;
        if (arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector("#listado-tareas");
            const textoNoTareas = document.createElement("LI");
            textoNoTareas.textContent = "No hay Tareas";
            textoNoTareas.classList.add("textoNoTareas");
            contenedorTareas.appendChild(textoNoTareas);
            return;
        };

        const estados = {
            0: "Pendiente",
            1: "Completa"
        };

        arrayTareas.forEach(tarea => {
            //Creamos el contenedor donde guardaremos las tareas
            const contenedorTarea = document.createElement("LI");
            contenedorTarea.dataset.tareaID = tarea.id;
            contenedorTarea.classList.add("tarea");
            //Ahora creamos el parrafo que contendrá el nombre de la tarea
            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = tarea.nombre
            nombreTarea.onclick = function () {
                mostrarFormulario(true, { ...tarea });
            }
            //Tambien creamos un div para las opciones de estado de una tarea
            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");
            //Creamos los botones de estado
            const btnEstadoTarea = document.createElement("BUTTON");
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.onclick = function () {
                cambiarEstadoTarea({ ...tarea });
            }
            //Creamos el boton de eliminar tarea
            const btnEliminarTarea = document.createElement("BUTTON");
            btnEliminarTarea.classList.add("eliminar-tarea");
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = "Eliminar";
            btnEliminarTarea.onclick = function () {
                confirmarEliminarTarea({ ...tarea });
            };
            //Agregamos a opcinesDiv los botones de las tarea
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            //Ahora agregamos al contenedorTarea el nombre de la tareay despues agregamos opcionesDiv
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            // Y por ultimo insertamos en cada contenedorTareas el contenedor de cada tarea
            const contenedorTareas = document.querySelector("#listado-tareas");
            contenedorTareas.appendChild(contenedorTarea);
        });
    };

    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendienteRadio = document.querySelector("#pendientes");
        if (totalPendientes.length === 0) {
            pendienteRadio.disabled = true;
        } else {
            pendienteRadio.disabled = false;
        }
    };

    function totalCompletas() {
        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        const completadasRadio = document.querySelector("#completadas");
        if (totalCompletas.length === 0) {
            completadasRadio.disabled = true;
        } else {
            completadasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = document.createElement("DIV");
        modal.classList.add("modal");
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? "Editar Tarea" : "Añadir una nueva tarea"}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="${editar ? "Actializa tu Tarea" : "Añade una nueva tarea"}" id="tarea" value="${editar ? tarea.nombre : ""}"/>
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${editar ? "Guardar Cambios" : "Añadir Tarea"}"/>
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
                const nombreTarea = document.querySelector("#tarea").value.trim();
                if (nombreTarea === "") {
                    //Mostramos una alerta de error
                    mostrarAlerta("El nombre de la tarea es obligatorio", "error", document.querySelector(".formulario legend"));
                    return;
                }
                if (editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea)
                } else {
                    agregarTarea(nombreTarea);
                }
            }
        });

        document.querySelector(".dashboard").appendChild(modal);
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
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".formulario legend"));
            if (resultado.tipo === "exito") {
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    const formulario = document.querySelector(".formulario");
                    formulario.classList.add("cerrar");
                    setTimeout(() => {
                        modal.remove();
                    }, 500);
                }, 200);
                //Agregar el objeto de tarea al global de tareas
                const tareasObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                };
                tareas = [...tareas, tareasObj];
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const { estado, id, nombre, proyectoId } = tarea;
        const datos = new FormData();
        datos.append("id", id);
        datos.append("nombre", nombre);
        datos.append("estado", estado);
        datos.append("proyectoId", obtenerProyecto());

        try {
            const url = "/api/tareas/actualizar";
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });
            const resultado = await respuesta.json();
            if (resultado.respuesta.tipo === "exito") {
                Swal.fire(
                    'Se ha actualizado correctamente!',
                    'Sigue organizando tus proyectos!',
                    'success'
                );
                const modal = document.querySelector(".modal");
                if (modal) {
                    setTimeout(() => {
                        const formulario = document.querySelector(".formulario");
                        formulario.classList.add("cerrar");
                        setTimeout(() => {
                            modal.remove();
                        }, 500);
                    }, 200);
                };
                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    };
                    return tareaMemoria;
                });
                mostrarTareas();
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

    function limpiarTareas() {
        const listadoTareas = document.querySelector("#listado-tareas");
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Seguro que quieres eliminarlo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            cancelButtonText: "Cancelar",
            confirmButtonText: 'Si, elimínalo!'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        })
    }

    async function eliminarTarea(tarea) {
        const { estado, id, nombre, proyectoId } = tarea;
        const datos = new FormData();
        datos.append("id", id);
        datos.append("nombre", nombre);
        datos.append("estado", estado);
        datos.append("proyectoId", obtenerProyecto());

        try {
            const url = "/api/tareas/eliminar";
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });
            const resultado = await respuesta.json();
            if (resultado) {
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
                Swal.fire(
                    'Eliminado!',
                    'Tu tarea ha sido eliminada.',
                    'success'
                );
            }
        } catch (error) {
            console.log(error);
        }
    }
})();