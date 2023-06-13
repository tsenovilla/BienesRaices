// Este script registra el cambio a Dark Mode de la web según las preferencias del sistema, así como cambio manual si se aprieta el botón de dark mode.
document.addEventListener("DOMContentLoaded", darkmode);

function darkmode()
{
    const body = document.querySelector("body");
    const botonDarkMode = document.querySelector(".dark-mode-button");
    const default_color_scheme = window.matchMedia("(prefers-color-scheme:dark)"); // Este bool es true si está habilitado el modo oscuro en el dispositivo, por lo que si es true añadimos el dark-mode nada más cargar
    if(default_color_scheme.matches || sessionStorage.getItem("dark-mode")=="activated") 
    {
        body.classList.add("dark-mode");
    }
    if(sessionStorage.getItem("dark-mode")=="deactivated") // Si está desactivado, lo quitamos. Esto cubre el caso en el que el ajuste del dispositivo busque temas oscuros pero el usuario lo haya desactivado manualmente
    {
        body.classList.remove("dark-mode");
    }
    default_color_scheme.addEventListener("change", ()=> 
        {
            // No puedo usar toggle porque si se pulsó el botón de dark-mode, al cambiar con toggle iriamos al reves.
            if (default_color_scheme.matches)
            {
                body.classList.add("dark-mode");
            }
            else
            {
                body.classList.remove("dark-mode");
            }
        }
    )

    botonDarkMode.addEventListener("click", ()=>
        {
            body.classList.toggle("dark-mode");
            if(body.classList.contains("dark-mode")) // Mantenemos en sessionStorage que se clico el botón para cambiar la configuración
            {
                sessionStorage.setItem("dark-mode","activated");
            }
            else
            {
                sessionStorage.setItem("dark-mode","deactivated");
            }
        }
    );

}



