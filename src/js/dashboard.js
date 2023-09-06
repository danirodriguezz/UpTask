document.addEventListener("DOMContentLoaded", () => {
    inciarApp();
})

function inciarApp() {
    hamburguesaMenu();
}

function hamburguesaMenu() {
    const botonMenu = document.querySelector(".sidebar-hamburguesa");
    botonMenu.addEventListener("click", animateBars);
}

function animateBars() {
    var linea1_hamburguesa = document.querySelector(".line1-haburguesa");
    var linea2_hamburguesa = document.querySelector(".line2-haburguesa");
    var linea3_hamburguesa = document.querySelector(".line3-haburguesa");
    const sidebar = document.querySelector(".sidebar-nav");
    let estilosidebar = window.getComputedStyle(sidebar);
    if(estilosidebar.left == "-230px") {
        sidebar.style.left = 0;
    } else {
        sidebar.style.left = "-230px";
    }
    linea1_hamburguesa.classList.toggle("activeline1-haburguesa");
    linea2_hamburguesa.classList.toggle("activeline2-haburguesa");
    linea3_hamburguesa.classList.toggle("activeline3-haburguesa");
}