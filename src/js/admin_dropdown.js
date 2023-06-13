document.addEventListener("DOMContentLoaded",drop_down_sellers);

function drop_down_sellers()
{
    const header = document.querySelector(".header");
    const menu_vendedores = document.querySelector(".admin-menu__vendedores");
    if (menu_vendedores)
    {
        const vendedor_selected = menu_vendedores.querySelector(".admin-menu__vendedores--selected");
        const vendedores_dropdown = menu_vendedores.querySelector(".admin-menu__vendedores--dropdown");
        vendedor_selected.addEventListener("click", ()=>
            {
                vendedores_dropdown.classList.toggle("display-none");
                menu_vendedores.classList.toggle("front-element"); //Si desplegamos el menú, decimos que vendedores tiene z-index 0. Hay que darselo al div que contiene las opciones y no al menú desplegado, porque el que choca con la tabla de debajo es el div, es el que tiene la altura máxima especificada para que al añadir los vendedores no alejen la tabla
            }
        );
        window.addEventListener("scroll",()=> // Importante quitar el desplegable si bajamos sobrepasamos el principio del menú, sino se pondrá encima de la tabla y del header
            {
                if(header.getBoundingClientRect().bottom>menu_vendedores.getBoundingClientRect().top)
                {
                    vendedores_dropdown.classList.add("display-none");
                    menu_vendedores.classList.remove("front-element");
                }
            }
        );
        
        // Además, tenemos que poner todos los botones del mismo width, para que no quede extraño
        const vendedores = menu_vendedores.querySelectorAll(".admin-menu__vendedores--vendedor");
        const enlaces = document.querySelectorAll(".admin-menu__enlace");
        const widths = [];
        vendedores.forEach(vendedor=>widths.push(vendedor.getBoundingClientRect().width));
        enlaces.forEach(enlace=>widths.push(enlace.getBoundingClientRect().width));
        const standard_width = Math.max(...widths);
        vendedores.forEach(vendedor=>vendedor.setAttribute("style",`width:${standard_width}px;`));
        enlaces.forEach(enlace=>enlace.setAttribute("style",`width:${standard_width}px;`));
    }
}