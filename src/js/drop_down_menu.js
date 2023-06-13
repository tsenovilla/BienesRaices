// Este script sirve para habilitar el menú desplegable en los dispositivos móviles

document.addEventListener("DOMContentLoaded", drop_down); // Con drop_down, en tamaños pequeños desplegamos y replegamos el menú de hamburguesa
window.addEventListener("resize", default_navigation); // Con default_navigation dejamos la navegación por defecto en cada uno de los tamaños: en pequeños debe aparecer replegado de primeras y en los grandes debe ser siempre visible. Sin esta función, si ocultamos la navegación del DOM en pantalla pequeña y luego ampliamos la ventana, la navegación ya no sale! y en caso contrario, si reducimos la ventana, sale la navegación siempre visible

// Inicializamos las variables y ocultamos la navegación en la primera carga en pantallas pequeñas
const dropdown = document.querySelector(".drop-down");
const navegacion = document.querySelector(".navegacion");
if (window.getComputedStyle(dropdown).display == "block")
{
    navegacion.setAttribute("style","display:none");
}

function drop_down()
{
    // Con este click desplegamos el menú en dispositivos pequeños
    dropdown.addEventListener("click", ()=>
        {
            if(!navegacion.classList.contains("dropdown-displayed")){
                navegacion.removeAttribute("style","display");
                setTimeout(()=>navegacion.classList.add("dropdown-displayed"), 2); // Le metemos un delay casi imperceptible (2 milisegundos) para que añada la clase de dropdown-displayed, que incluye la transición y le da la visibilidad a los enlaces
            }
            else
            {
                navegacion.setAttribute("style","display:none");
                navegacion.classList.remove("dropdown-displayed");
            }
        }
    );
}

function default_navigation()
{
    if(window.getComputedStyle(navegacion).flexDirection=="row")
    {
        navegacion.removeAttribute("style","display");
        navegacion.classList.remove("dropdown-displayed"); //Siempre que pasemos a tamaño grande, esta clase desaparece
    }
    else
    {
        if(!navegacion.classList.contains("dropdown-displayed"))
        {
            navegacion.setAttribute("style","display:none"); //Ocultamos la navegación pero solo si el menú no está desplegado. Sin este if, a cada resize en tamaño pequeño desaparece la navegacion
        }
    }
}



