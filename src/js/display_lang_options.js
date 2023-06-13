document.addEventListener("DOMContentLoaded", display_lang); // Esta función sirve para mostrar u ocultar las opciones de idioma

function display_lang()
{
    const boton_lang = document.querySelector(".navegacion__idiomas--selected");
    const idiomas = document.querySelectorAll(".navegacion__idiomas--idioma");
    boton_lang.addEventListener("click",()=>idiomas.forEach(idioma=>idioma.classList.toggle("display-none"))); 
    window.addEventListener("scroll",()=>idiomas.forEach(idioma=>idioma.classList.add("display-none")));
    window.addEventListener("resize",()=>idiomas.forEach(idioma=>idioma.classList.add("display-none")));
}