document.addEventListener("DOMContentLoaded",toggle_password);

function toggle_password()
{
    const password = document.querySelector(".password__text");
    const password_buttons = document.querySelectorAll(".password__button"); // Hay dos iconos diferentes, uno para mostrar y otro para ocultar la contraseña. Con cada click cambiamos el tipo del input password para mostrar/ocultar y cambiamos el icono
    if(password_buttons.length) //Esta comprobación es porque en las páginas donde no existen estos elementos, muestra un error por consola de que no están definidos los indices 0 y 1. Si no estamos en una página que tenga contraseña, omitimos este script
    {
        password_buttons[0].addEventListener("click",()=>
            {
                password.type="text";
                password_buttons[0].setAttribute("style","display:none");
                password_buttons[1].removeAttribute("style","display");
            }
        )
        password_buttons[1].addEventListener("click",()=>
        {
            password.type="password";
            password_buttons[1].setAttribute("style","display:none");
            password_buttons[0].removeAttribute("style","display");
        }
        ) 
    }
}