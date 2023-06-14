document.addEventListener("DOMContentLoaded",show_choosen_contact);// Esta función permite seleccionar los radio button y mostrar las opciones de contacto seleccionadas
document.addEventListener("DOMContentLoaded",activate_map_button); // Esta función activa el botón que despliega el mapa.
document.addEventListener("DOMContentLoaded",obtener_prefijo); // Esta función sirve para mostrar la lista de países según el input del usuario con los respectivos prefijos y permitirle seleccionar el suyo
document.addEventListener("DOMContentLoaded",submit_contact_form); // El formulario de contacto no se envía directamente desde el servidor, ya que necesitamos enviar los datos metidos por pantalla y además las coordenadas del usuario si desea ser contactado por teléfono y el prefijo telefónico. Estas coordenadas se recuperan en el cliente vía el mapa interactivo de Leaflet, por lo que debemos enviar el formulario via Fetch API.

let client_obtained_data = {latitud: null, longitud: null, prefijo: null}; // Las coordenadas deben estar definidas globalmente en el script ya que vamos a utilizarlas en varias funciones. De primeras son null, ya que si el usuario no pide ser contactado por teléfono no las necesitamos para nada
const prefijo = document.querySelector(".prefijo");

// Esta función devuelve una promesa que sirve para dar un tiempo máximo de respuesta de 7 segundos a las llamadas Fetch API, sino, muestra una alerta para que se intente de nuevo. Solo la creamos si estamos en la página de contacto
const formulario = document.querySelector(".formulario-contacto");
let timeoutPromise = null;
let alerta = "";
const idioma = document.querySelector(".navegacion__idiomas--selected").innerHTML.substring(0,2).toLowerCase();//Recuperamos el idioma en el que está servida la página, ya que lo necesitamos para hacer el fetch mediante FETCH API
if(formulario)
{
    switch(idioma)
    {
        case "en":
            alerta = "There was a problem with your request. Try it again in a few minutes or switch to email communication.";
            break;
        case "es":
            alerta = "Hubo un problema procesando su solicitud. Íntentelo de nuevo en unos minutos o cambie el método de contacto a email.";
            break;
    }
    timeoutPromise = () => new Promise((resolve, reject) => setTimeout(() => reject(new Error(alerta)),7000));
}


function show_choosen_contact()
{
    const radio_telefono = document.querySelector("#contactar-telefono");
    const radio_email = document.querySelector("#contactar-email");
    const contacto_telefono = document.querySelector(".contacto-telefono");
    const contacto_email = document.querySelector(".contacto-email");
    if(radio_telefono && radio_email)
    {
        if(radio_telefono.hasAttribute("checked"))
        {
            contacto_telefono.removeAttribute("style","display");
        }
        if(radio_email.hasAttribute("checked"))
        {
            contacto_email.removeAttribute("style","display");
        }
        radio_telefono.addEventListener("click",()=>
            {
                contacto_telefono.removeAttribute("style","display");
                contacto_email.setAttribute("style","display:none");
            }
        );
        radio_email.addEventListener("click",()=>
        {
            contacto_email.removeAttribute("style","display");
            contacto_telefono.setAttribute("style","display:none");
        }
    );
    }
}

function submit_contact_form()
{
    if(formulario)
    {
        formulario.addEventListener("submit", event=>
            {
                event.preventDefault(); // Evitamos que se envíe el formulario directamente
                const form = Object.fromEntries(new FormData(formulario).entries()); // Recuperamos los valores puestos en el formulario
                // Y construimos el POST. Hay que indicarle submit=true, ya que la ruta de contacto POST tiene dos variantes. Esta variante es la principal y es la que permite el envío de los datos.
                const post = {submit:true,...client_obtained_data,...form};
                // Mandamos el post al servidor mediante FETCH API en formato JSON
                const fetch_config = 
                {
                    method: "POST",
                    headers:
                    {
                        "Content-Type":"application/json"
                    },
                    body: JSON.stringify(post)
                };
                Promise.race([fetch("/"+idioma+"/contacto",fetch_config),timeoutPromise()])
                .then(response=>response.text())
                // La redirección mediante header de PHP a la misma página puede no funcionar bien. Esto es porque la función header manda una petición HTTP al navegador, pero como la manda a la misma página, el navegador ya habrá cargado contenido HTML y no ejecutará la redirección. No podemos hacer un render normal, ya que, a diferencia de otras páginas de la aplicación, el envío del formulario se ha prevenido en esta promesa de fetch API. Por lo tanto, si no hay errores en la respuesta del servidor, redireccionamos. No confundir con errores que en la lógica de los datos, esos errores los gestiona el modelo de Contacto en el servidor y se muestran cuando redireccionamos igualmente, ya que se transmiten por la sesión. El redireccionado se produce si no hay errores de funcionamiento del servidor, NO si hay errores en la lógica de los datos
                .then(()=>window.location.href = "/"+idioma+"/contacto")
                .catch(e=>alert(e))
            }
        );
    }
}

function activate_map_button()
{
    const boton_timezone = document.querySelector(".timezone-select"); // Seleccionamos el botón que despliega el mapa
    if(boton_timezone){boton_timezone.addEventListener("click",desplegar_mapa)}
}

function desplegar_mapa()
{
    // Creamos los elementos: El overlay que cubra toda la ventana y sus hijos, el mapa y el botón para cerrar
    const overlay = document.createElement("DIV");
    const map_container = document.createElement("DIV");
    const close_map = document.createElement("P");
    // Configuramos estos elementos.
    overlay.classList.add("overlay");
    map_container.id = "map_container";
    map_container.classList.add("map_container");
    close_map.textContent="X";
    close_map.classList.add("boton-cerrar");
    close_map.addEventListener("click",cerrar_mapa);// Si click en el botón, se cierra el mapa
    // Añadimos los hijos al overlay
    overlay.appendChild(map_container);
    overlay.appendChild(close_map);
    // Añadimos el overlay al body para que cubra toda la ventana e inmovilizamos el resto de la página mientras el mapa esté desplegado.
    const body = document.querySelector("body");
    body.classList.add("fixed-page");
    body.appendChild(overlay);

    // Una vez que el DOM tiene el contenedor para al mapa (map_container) creado, podemos incluir el mapa de Leaflet
    const map = L.map("map_container").setView([0,0],3); // Creamos el mapa, lo metemos en el contenedor creado para ello y centramos la vista con un zoom inicial de 3.
    // Agregamos la capa visual, que nos ofrece openstreetmap.
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);
    // Añadimos al mapa un marcador interactivo que se pueda arrastrar a la ubicación deseada para marcar las coordenadas. 
    const marker = L.marker([0,0],{draggable:true}).addTo(map);
    // Aseguramos que el marcador se queda en el centro del mapa visible si el usuario hace zoom o se desplaza por el mapa
    map.on("zoomend",()=>
    {
        const center = map.getCenter();
        marker.setLatLng([center.lat,center.lng]);
    }
    );
    map.on("moveend",()=>
        {
            const center = map.getCenter();
            marker.setLatLng([center.lat,center.lng]);
        }
    );
    // Si el usuario suelta el marcador en un punto, le pedimos confirmación de que esa es su ubicación para saber a que hora llamarle, y si confirma, registramos las coordenadas en el objeto de coordenadas
    marker.on("dragend", event=>registrar_coordenadas(event));
}

// Esta función registra las coordenadas donde se suelta el marcador. Además, si el usuario confirma las coordenadas, se cierra el mapa.
function registrar_coordenadas(event)
{
    const markerLatLng = event.target.getLatLng(); // Recuperación de las coordenadas
    const confirmado = confirm("Necesitamos una ubicación aproximada para conocer su hora local y así poder llamarle a la hora que especifique. ¿Desea confirmar esta selección?");
    if(confirmado)
    {
        // Guardamos los datos y los reenviamos
        client_obtained_data.latitud = markerLatLng.lat;
        client_obtained_data.longitud = markerLatLng.lng;
        cerrar_mapa();
        // Cuando las coordenadas están seleccionadas, montamos un POST que no se envíe, simplemente para mandar las coordenadas al servidor y que este nos pueda devolver mediante fetch API el nombre del país obtenido de la API de TimeZoneDB
        const post = {submit: false, ...client_obtained_data}
        const fetch_config = 
        {
            method: "POST",
            headers:
            {
                "Content-Type":"application/json"
            },
            body: JSON.stringify(post)
        };
        Promise.race([fetch("/"+idioma+"/contacto",fetch_config),timeoutPromise()])
        .then(response=>response.json()) // El servidor en este caso nos responde con el nombre del país y la abreviación del nombre de su zona horaria
        .then(response=> // Como tenemos el nombre del país, podemos llamar a la API de RestCountries para recuperar un prefijo telefónico de ese país y mostrarlo por defecto.
            {
                const boton_timezone = document.querySelector(".timezone-select");
                boton_timezone.innerHTML = response.abbreviation+" ("+response.countryName+")"; // Además, marcamos el botón de seleccionar zona horaria con la seleccionada, para que el usuario sepa que se ha marcado su zona correctamente.
                Promise.race([fetch(`https://restcountries.com/v3.1/name/${response.countryName}`),timeoutPromise()])
                .then(response=>response.json())
                .then(response=>
                    {
                        prefijo.value = response[0].flag+" "+response[0].idd.root+response[0].idd.suffixes[0];
                        client_obtained_data.prefijo = response[0].idd.root+response[0].idd.suffixes[0];
                    }
                )
                .catch(e=>alert(e))
            }
        ).catch(e=>alert(e)) 
    }
}

// Esta función destruye el mapa desplegado
function cerrar_mapa()
{
    const body = document.querySelector("body");
    const overlay = document.querySelector(".overlay");
    body.removeChild(overlay);
    body.classList.remove("fixed-page");
}


// Esta función gestiona la parte de obtención de prefijos telefónicos.
function obtener_prefijo()
{
    // Toda la lógica se activa cuando el usuario escribe algo en el prefijo
    if(prefijo)
    {
        client_obtained_data.prefijo = prefijo.value; // Asignamos al post el prefijo si ya está escrito (si se ha escrito en un submit anterior pero no se lanzó correctamente). Esto previene error en el fetch de submit si el servidor no acepta un objeto con todos los valores a null
        prefijo.addEventListener("input",e=>
            {
                let input = e.target.value; 
                if(!input) // Si se ha borrado el input a mano, borramos la lista y el valor del post, para evitar que se postee el prefijo seleccionado en una llamada previa
                {
                    delete_prefix_list();
                    client_obtained_data.prefijo = "";
                }
                else if(/^\+|\d/.test(input))
                {
                    // Añadimos el + al input en caso de que el usuario esté metiendo el prefijo a pelo
                    input = input.startsWith("+") ? input : "+"+input;
                    // En este caso recuperamos todos los países, ya que la API RestCountries v3 no ofrece busqueda por prefijo telefónico
                    Promise.race([fetch("https://restcountries.com/v3.1/all"),timeoutPromise()])
                    .then(response=>response.json())
                    .then(response=>{create_prefix_list(input,response)})
                    .catch(()=>prefix_not_found()) // Si hay error en la llamada a la API, mostramos mensaje de que no se encuentra ningún prefijo en la lista
                }
                else
                {
                    input = input.toLowerCase().replace(/(?:^|\s)\w/g, match=>match.toUpperCase()); // Si estamos buscando por nombre, convertimos a title case, ya que la API de RestCountries guarda los nombres con ese formato
                    Promise.race([fetch(`https://restcountries.com/v3.1/name/${input}`),timeoutPromise()])
                    .then(response=>response.json())
                    .then(response=>create_prefix_list(input, response, true))
                    .catch(()=>prefix_not_found())
                }
            }
        )
    }
}

// Esta función permite crear el desplegable de países para seleccionar el prefijo. Recibe la lista sin filtrar de países que se obtiene cuando Fetch API resuelve la promesa, y el nombre del país (total o parcial) que ha escrito el usuario. El tercer parámetro indica si se busco por nombre del país (true) o prefijo (false)
function create_prefix_list (input, lista_sin_filtrar, search_by_name=false)
{
    const lista_paises = document.querySelector(".lista-paises");
    // Comenzamos borrando la lista anterior para que no se acumule con los países que vamos a meter nuevos
    delete_prefix_list(); 
    let filter;
    if(search_by_name)
    {
        // Filtramos la lista recuperada de la API con el input del usuario ya que la API no trae solo los países que comienzan por lo que introduce el usuario, sino que trae otros países que contienen ese string en el nombre. Eliminamos todo lo que tenga que ver con la Antártida ya que tiene propiedades no definidas y puede causar problemas. Mantenemos solo los países que empiecen por el string introducido por el usuario, ya sea en inglés o en nativo (españa sale tanto si escribimos esp como si escribimos spa).
        // Para filtrar el nombre nativo, hay un objeto intermedio en los objetos nativeName de la API cuyo nombre varía en cada objeto padre, así que tenemos que recuperar implicitamente el nombre de ese objeto intermedio en el filtro.
        filter = lista_sin_filtrar.filter(country=>!country.continents.includes("Antarctica") && (country.name.common.startsWith(input) || country.name.nativeName[Object.keys(country.name.nativeName)[0]].common.startsWith(input)));
    }
    else
    {
        // Filtramos los países que tienen al menos un prefijo que comience como el input. 
        filter = lista_sin_filtrar.filter(country=>
            { 
                if(country.continents.includes("Antarctica"))
                {
                    return false;
                }
                const calling_code_root = country.idd.root;
                const calling_code_suffixes = country.idd.suffixes;
                let filter_pass = false;
                calling_code_suffixes.forEach(suffix=>
                    {
                        if((calling_code_root+suffix).startsWith(input))
                        {
                            filter_pass = true;
                        }
                    }
                )
                return filter_pass;
            }
        )
    }
    // Para cada registro filtrado recuperamos las características de la API
    filter.forEach(country=>
        {
            const country_name = country.name.common;
            const country_native_name = country.name.nativeName[Object.keys(country.name.nativeName)[0]].common;
            // Si el nombre en inglés y en nativo es diferente, mostramos los dos
            const name = country_name===country_native_name ? country_name : country_name+" ("+country_native_name+")";
            // Bandera
            const flag = country.flag;
            // Raíz de los prefijos telefónicos del país
            const calling_code_root = country.idd.root;
            // Lista con los sufijos del país, para cada uno, creamos un elemento nuevo para la lista y lo añadimos
            const calling_code_suffixes = country.idd.suffixes;
            for(let i=0; i<calling_code_suffixes.length; i++)
            {
                const prefix = calling_code_root+calling_code_suffixes[i];
                // En el caso de estar buscando por prefijo, los prefijos que no inicien con el input se descartan, en el caso de un país con multiples prefijos, ya que esta comprobación en países con un solo prefijo nunca se efectuará porque si el país está en el filtro, ya ha pasado esta comprobación. Ejemplo: EEUU tiene (entre otros) los prefijos 1209 y 1210. Si el input empieza con 12, ambos apareceran en la lista, pero si el input lleva 120, entonces el 1210 no aparece en la lista.
                if(!search_by_name && !(prefix).startsWith(input))
                {
                    continue;
                }
                const calling_code = document.createElement("P");
                calling_code.innerHTML = flag+" "+prefix+" "+name;
                calling_code.classList.add("pais");
                // Registramos el prefijo en nuestro objeto client_obtained_data que enviaremos al servidor. Además, dejamos en el recuadro del prefijo mostrado el prefijo seleccionado
                calling_code.addEventListener("click",()=>
                    {
                        client_obtained_data.prefijo = prefix
                        prefijo.value = flag+" "+prefix;
                        delete_prefix_list();
                    }
                ); 
                lista_paises.appendChild(calling_code);
            }
        }
    )
    if(!lista_paises.hasChildNodes()) // Puede ser que la API devuelva cosas pero ninguna pase el filtro, por ejemplo al buscar por prefijo volvemos con toda la API, y si buscamos por país, se devuelven los países que contengan el input, sin necesidad de que sea el comienzo, por eso debemos indicar que no se ha encontrado nada en este caso también.
    {
        prefix_not_found();
    }
}

// Esta función borra la lista de prefijos telefónicos mostrada en pantalla.
function delete_prefix_list()
{
    const lista_paises = document.querySelector(".lista-paises");
    while(lista_paises.hasChildNodes())
    {
        lista_paises.removeChild(lista_paises.firstChild);
    }
}

// Esta función se llama cuando hay algún error recuperando de la API RestCountries, indica que no hay prefijos válidos
function prefix_not_found()
{
    delete_prefix_list(); // Borramos lo anterior
    const not_found_message = document.createElement("P");
    const lista_paises = document.querySelector(".lista-paises");
    let message;
    switch(idioma)
    {
        case "en":
            message = "There's no prefix matching the search";
            break;
        case "es":
            message = "Ningún prefijo coincide con la búsqueda";
            break;
    }
    not_found_message.innerHTML = message;
    not_found_message.classList.add("pais"); // Para que tenga el mismo estilo que la búsqueda
    lista_paises.appendChild(not_found_message);
}
