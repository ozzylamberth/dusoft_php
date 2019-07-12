<?php
	/*
	* incluimos rs_server.class.php que contiene la class rs_server que será la que "extenderemos"
	*/
		
	$VISTA = "HTML";
     $_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
     include  "../../../includes/enviroment.inc.php";
    
    class procesos_admin extends rs_server {
    
    /*
    * Definimos tantos métodos como funciones queremos que nuestro servidor "sirva"
    */
       

     /*
     * EstablecerFactorConversion
     *
     * Funcion que permite parametrizar el factor de conversion de un medicamento
     */
     function EstablecerFactorConversion( $parametros )
     {
          list($dbconn) = GetDBconn();

          if($parametros[0] == 1)
          {
               $Factores = "UPDATE hc_formulacion_factor_conversion
                         SET factor_conversion = '".$parametros[4]."'
                         WHERE codigo_producto = '".$parametros[1]."'
                         AND unidad_id = '".trim($parametros[2])."'
                         AND unidad_dosificacion = '".trim($parametros[3])."';";
          }else
          {
               $Factores = "INSERT INTO hc_formulacion_factor_conversion
                                                  (codigo_producto,
                                                  unidad_id,
                                                  unidad_dosificacion,
                                                  factor_conversion,
                                                  usuario_id,
                                                  fecha_registro)
                                        VALUES    ('".$parametros[1]."',
                                                   '".trim($parametros[2])."',
                                                   '".trim($parametros[3])."',
                                                   '".$parametros[4]."',
                                                   '".SessionGetVar("Usuario")."',
                                                   'Now()');";
          }
          $dbconn->Execute($Factores);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al seleccionar el Factor de conversion";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
     }
   
	
     function ValidarPermisosFactor()
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('59'))
          {
               return true;
          }
     }
     
     /**
     * Valida si el usuario esta logueado en La Estacion de Enfermeria y si tiene permiso
     * Para este componente ('01'= Admision - Asignacion Cama)
     *
     * @return boolean
     * @access private
     */
     function GetUserPermisos($componente=null)
     {
          $_VESTACION = SessionGetVar("V_estacion");
          $_UESTACION = SessionGetVar("U_estacion");
          
          $estacion_id = $_VESTACION[SessionGetVar("Usuario")];
          
          if($componente)
          {
               if(!empty($_UESTACION[SessionGetVar("Usuario")][$estacion_id]['COMPONENTES'][$componente]))
               {
                    return true;
               }
               else
               {
                    return null;
               }
          }
     
          if(!empty($_UESTACION[SessionGetVar("Usuario")][$estacion_id]))
          {
               
               return true;
          }
          else
          {
               return null;
          }
     }
}//end of class


    /*
        cuando creamos el objeto que tiene los procesos debemos indicar como único parámetro un
        array con todas las funciones posibles ... esto se hace para evitar que se pueda llamar
        a cualquier método del objeto.
    */

    $oRS = new procesos_admin( array( 'EstablecerFactorConversion', 'ValidarPermisosFactor' ));

    // el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
    $oRS->action();


?>