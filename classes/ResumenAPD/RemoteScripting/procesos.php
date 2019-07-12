<?php
    /*
    incluimos rs_server.class.php que contiene la class rs_server que será la que "extenderemos"
    */
		$VISTA = "HTML";
		$_ROOT="../../../";
    include  "../../../classes/rs_server/rs_server.class.php";
		include	 "../../../includes/enviroment.inc.php";
    class procesos_admin extends rs_server {
    /*
    * Definimos tantos métodos como funciones queremos que nuestro servidor "sirva"
    */
       

     /**
     *
     */
     function get_valores_grupotipocargo ( $parameters )  
     {

          $valor = $this-> TipoCargo($parameters);
          $cad[] = "-Todos";
          
          foreach($valor as $val){
               $cad[$val['tipo_cargo']] = $val['tipo_cargo']."-".substr($val['descripcion'],0,50);
          }
          return implode('~',$cad);
     }
 
     function TipoCargo($parameters)
     {
          
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
         
          $sql="select * from tipos_cargos where grupo_tipo_cargo='".$parameters[0]."';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta de Apoyos Diagnosticos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while ($result_paso = $result->FetchRow()) 
               {
                    //$datos[$result_paso['tipo_cargo']]=$result_paso;
                    $datos[]=$result_paso;
               }
          }
          //return $salida.="<select name=\"tipocargo\" class=\"select\" onchange=\"valores_GrupoCargo(document.formulario.grupotipo.value, this.form)\"></select></td>\n";
          return $datos;
     }

		
   }//end of class


    /*
        cuando creamos el objeto que tiene los procesos debemos indicar como único parámetro un
        array con todas las funciones posibles ... esto se hace para evitar que se pueda llamar
        a cualquier método del objeto.

    */

    $oRS = new procesos_admin( array( 'get_valores','get_valores_grupotipocargo', "get_valores_GrupoCargo"));

    // el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
    $oRS->action();


?>