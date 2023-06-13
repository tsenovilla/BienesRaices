document.addEventListener("DOMContentLoaded", igualar_alturas); // Esta función se encarga de hacer que el contenido de todas las solicitudes sea igual, para una mejor experiencia visual.
document.addEventListener("DOMContentLoaded", gestionar_solicitud); // Esta función pide confirmación de que la solicitud ha sido confirmada y ejecuta el POST a la url de solicitudes para eliminar la solicitud

function igualar_alturas()
{
    const solicitudes = document.querySelectorAll(".solicitud");
    if(solicitudes.length)
    {
        // En este JS object almacenamos las alturas que pueden variar en cada solicitud
        const heights = 
        {
            nombre: [],
            mensaje:[]
        }
        // En el caso de la fecha, todas tienen la misma altura así que basta con pillar una para añadirle esa altura al relleno en los casos de contacto por mail
        const fecha_height = document.querySelector(".solicitud__fecha").getBoundingClientRect().height;
        solicitudes.forEach(solicitud=>
            {
                const nombre = solicitud.querySelector(".solicitud__nombre");
                const mensaje = solicitud.querySelector(".solicitud__mensaje");
                const relleno = solicitud.querySelector(".solicitud__relleno");
                if(relleno) // En caso de que sea una solicitud de email, añadimos al relleno la altura de la fecha
                {
                    relleno.setAttribute("style",`height:${fecha_height}px`);
                }
                heights.nombre.push(nombre.getBoundingClientRect().height);
                heights.mensaje.push(mensaje.getBoundingClientRect().height);
            }
        )
        const nombre_height = Math.max(...heights.nombre);
        const mensaje_height = Math.max(...heights.mensaje);
        solicitudes.forEach(solicitud=>
            {
                const nombre = solicitud.querySelector(".solicitud__nombre");
                const mensaje = solicitud.querySelector(".solicitud__mensaje");
                nombre.setAttribute("style",`height:${nombre_height}px`);
                mensaje.setAttribute("style",`height:${mensaje_height}px`);
            }
        );
    }
}

function gestionar_solicitud()
{
    const botones = document.querySelectorAll(".solicitud__gestionada");
    if(botones)
    {
        const idioma = document.querySelector(".navegacion__idiomas--selected").innerHTML.substring(0,2).toLowerCase();//Recuperamos el idioma en el que está servida la página, ya que lo necesitamos para hacer el fetch mediante FETCH API
        let alert = "";
        switch(idioma)
        {
            case "en":
                alert = "Do you want to confirm that this request has been managed? Once done, the request will be completely deleted";
                break;
            case "es":
                alert = "¿Confirma que esta solicitud ha sido gestionada? Una vez confirmado, la solicitud se borrará permanentemente";
                break;
        }
        botones.forEach(boton=>boton.addEventListener("click",()=>
                {
                    const confirmado = confirm(alert);
                    if(confirmado)
                    {
                        const fetch_config = 
                        {
                            method: "POST",
                            headers:
                            {
                                "Content-type":"application/json"
                            },
                            body: JSON.stringify({id:boton.id})
                        };
                        Promise.race([fetch("/"+idioma+"/admin/solicitudes",fetch_config),new Promise((resolve,reject)=>setTimeout(()=>reject(new Error("Hubo un problema procesando su solicitud, intentelo de nuevo en unos minutos o contacte con soporte.")),7000))])
                        .then(response=>response.text()) // Resolvemos la promesa solo para saber si el servidor ha completado sus acciones, en cuyo caso redireccionaremos, y sino, saltará alerta
                        .then(()=>window.location.href="/"+idioma+"/admin/solicitudes")
                        .catch(e=>alert(e))
                    }
                }
            )
        )
    }
}
