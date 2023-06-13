// Este script se utiliza para ajustar el tamaño de los anuncios mostrados en la página principal. Como cada anuncio puede tener título y descripción de longitudes diferentes, para que el recuadro de cada anuncio no quede descompensado (ya que los recuadros generados con el grid son todos igual de grandes) y en unos el precio, iconos y botón salgan cada uno a una altura, fijamos la altura de los textos, haciendo a todas igual de altas a la más alta de las 3.
document.addEventListener("DOMContentLoaded", resize);

function resize()
{
    // Recuperamos los titulos y descripciones
    const titulo_anuncio = document.querySelectorAll(".contenido-anuncio__titulo");
    const descripcion_anuncio = document.querySelectorAll(".contenido-anuncio__descripcion");
    // Almacenamos sus alturas
    const titulos_heights = [];
    const descripcion_heights = [];
    titulo_anuncio.forEach(element=>titulos_heights.push(element.getBoundingClientRect().height));
    descripcion_anuncio.forEach(element=>descripcion_heights.push(element.getBoundingClientRect().height));
    // Asignamos a los elementos las alturas correspondientes
    const height_tit_final = Math.max(...titulos_heights)+20; // Como la altura es fija, al encoger el navegador puede hacer cosas raras de superposición de textos, así que le metemos un poquito más para curarnos en salud. No hace falta mucho más porque al encoger lo suficiente se cambia el display del contenedor y no hay problema
    const height_desc_final = Math.max(...descripcion_heights) + 20;
    titulo_anuncio.forEach(element=>element.setAttribute("style",`height:${height_tit_final}px;`));
    descripcion_anuncio.forEach(element=>element.setAttribute("style",`height:${height_desc_final}px;`));
}