<?php
// FUNCIONES

/**
 * Esta función crea alertas de que una acción de la aplicación se realizó correctamente y las almacena en la sesión. Estas alertas deben ser borradas una vez que se muestran por pantalla
 * INPUTS:
 * - string $alert: Mensaje que contiene la alerta
 */

function OK_alert(string $alert):void
{
    $_SESSION["OK_alert"] = 
    '
        <div class="alerta OK">
            <p class="alerta__message">
    ' . $alert .
    '
            </p>
            <div class="right-align">
                <p class="alerta__close">X</p>
            </div>
        </div>
    ';
}


/**
 * Esta función crea alertas de que una acción de la aplicación no se completó y las almacena en la sesión. Estas alertas deben ser borradas una vez que se muestran por pantalla
 * INPUTS:
 * - string $alert: Mensaje que contiene la alerta
 */

function KO_alert(string $alert):void
{
    $_SESSION["KO_alert"] = 
    '
        <div class="alerta KO">
            <p class="alerta__message">
    ' . $alert .
    '
            </p>
            <div class="right-align">
                <p class="alerta__close">X</p>
            </div>
        </div>
    ';
}

        
/**
 * Esta función sirve para cortar la ejecución del código php si la propiedad check no existe o vale false, y redireccionar a la url especificada
 * INPUTS:
 *  - string $url: La dirección de la web a la que debe ser redireccionado el usuario
 *  - mixed $check: Dato que debe existir para que la ejecución pueda continuar. Si no existe, o su valor es false, el flujo de ejecución se detiene. Por defecto está definido a false, por lo que si se llama a la función sin especificar este campo, se produce automáticamente la redirección
 */
function break_and_redirect(string $url, $check = false)
{
    if(!$check)
    {
        header("location: $url");
        exit;
    }
}
