function inciarApp(){hamburguesaMenu()}function hamburguesaMenu(){document.querySelector(".sidebar-hamburguesa").addEventListener("click",animateBars)}function animateBars(){var e=document.querySelector(".line1-haburguesa"),t=document.querySelector(".line2-haburguesa"),a=document.querySelector(".line3-haburguesa");const u=document.querySelector(".sidebar-nav");"-230px"==window.getComputedStyle(u).left?u.style.left=0:u.style.left="-230px",e.classList.toggle("activeline1-haburguesa"),t.classList.toggle("activeline2-haburguesa"),a.classList.toggle("activeline3-haburguesa")}document.addEventListener("DOMContentLoaded",()=>{inciarApp()});