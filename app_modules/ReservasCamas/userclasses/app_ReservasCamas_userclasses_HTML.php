 <?php
/**
* Modulo de reservas de camas.
*
* Modulo para el manejo de reservas de camas.
*
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
*/

/**
*  app_ReservasCamas_userclasses_HTML.php
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del modulo de reservas de camas.
* utilizados los metodos de esta clase en la anterior.
*/
class app_ReservasCamas_userclasses_HTML extends app_ReservasCamas_user
{


    /**
    * Constructor de la clase app_ReservasCamas_userclasses_HTML
    * El constructor de la clase app_ReservasCamas_userclasses_HTML se encarga de llamar
    * a la clase app_ReservasCamas_userclasses_user quien se encarga de el tratamiento
    * de la base de datos.
    * @return boolean
    */

        function app_ReservasCamas_userclasses_HTML()
        {
                    $this->salida='';
                    $this->app_ReservasCamas_user();
                    return true;
        }


        function SetStyle($campo)
        {
                    if ($this->frmError[$campo] || $campo=="MensajeError"){
                        if ($campo=="MensajeError"){
                $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
                        }
                        return ("label_error");
                    }
                return ("label");
        }

}//fin clase

?>

