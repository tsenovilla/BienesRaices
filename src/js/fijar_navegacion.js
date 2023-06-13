// Este script se usa para fijar la barra de navegación en los dispositivos grandes si hacemos suficiente scroll hacia abajo

document.addEventListener("DOMContentLoaded", block_nav);

function block_nav(){
    const header = document.querySelector(".header"); 
    const checkpoint = document.querySelector(".seccion"); //Después del header siempre viene una seccion
    const check_home_page = header.classList.contains("inicio"); // Este booleano chequeará si estamos en la home_page para quitar la imagen de fondo de la barra fija así como añadirle estilos para que no sea demasiado grande ni contenga el texto de inicio. Además revertirá este comportamiento cuando volvamos hacia arriba

    // Si estamos en la home page, detectamos el H1 que nos queremos cargar
    let homepage_header_text = null;
    if(check_home_page)
    {
        homepage_header_text = document.querySelector(".contenido-header__texto");
    }
    // Evento de scroll que dispara la funcionalidad
    window.addEventListener("scroll", () => 
        {
            if(check_home_page)
            {
                if(checkpoint.getBoundingClientRect().top<0)
                {
                    header.classList.add("header__fijo");
                    checkpoint.classList.add("salto-homepage"); // Hay que añadirle un padding acorde al tamaño del header. En esta página es más grande que en las otras
                    homepage_header_text.remove();
                    header.classList.remove("inicio");
                }
                if(checkpoint.getBoundingClientRect().top>-780)
                {
                    header.classList.remove("header__fijo");
                    checkpoint.classList.remove("salto-homepage");
                    header.classList.add("inicio");     
                    document.querySelector(".contenido-header").appendChild(homepage_header_text);          
                }
            }
            else
            {
                if(checkpoint.getBoundingClientRect().top<0)
                {
                    header.classList.add("header__fijo");
                    checkpoint.classList.add("salto");
                }
                else
                {
                    header.classList.remove("header__fijo");
                    checkpoint.classList.remove("salto");
                }
            }
        }
    )
}