// Este script permite eliminar el mensaje de error creado al introducir un formulario erroneo. El mensaje desaparecerá automáticamente a los 10 segundos si no se toca
document.addEventListener("DOMContentLoaded",error_management);

function error_management()
{
    const alertas = document.querySelectorAll(".alerta");
    alertas.forEach(alerta=>
        {
            const alerta_close = alerta.querySelector(".alerta__close");
            alerta_close.addEventListener("click",()=>alerta.remove());
            setTimeout(()=>alerta.remove(),15000);
        }
    );
}