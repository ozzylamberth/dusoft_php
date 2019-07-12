<?php
  $VISTA = "HTML";
  $_ROOT="../../../";  
  include  "../../../includes/enviroment.inc.php";  
  include  "../../../classes/rs_server/rs_server.class.php";
  
  
  
  class procesos_admin extends rs_server
  {
      /**
      * Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
      * @return array
      */
        function TiposFrecuenciasSuministrosGases($valor){
      //echo '<br>function TiposFrecuenciasSuministrosGases...';
              list($dbconn) = GetDBconn();
              //echo '<br><br><br><br><br><br>'.
	      $query = "SELECT frecuencia_id,unidad
              FROM tipos_frecuencia_gases
              WHERE tipo_suministro_id='".$valor[0]."'";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
              }else{
                if($result->RecordCount()){
                          while(!$result->EOF){
                              //$TiposFrecuencias[$result->fields[0]]=$result->fields[0].' '.$result->fields[1];
			      //jab
			      $TiposFrecuencias[$result->fields[0]]=$result->fields[0].'/'.$result->fields[1];
                              $result->MoveNext();
                          }
                      }
                  }
              $result->Close();
              $cadena.="<select name=\"FrecuenciaSuministroGas\" class=\"select\">";
              $cadena.="    <option value=\"-1\" selected>---seleccione---</option>";              
              foreach($TiposFrecuencias as $value=>$titulo){          
                $cadena.="  <option value=\"$value\">".$titulo."</option>";          
              }
              $cadena.="  </select>";
              return $cadena;
          }
          
          function InsertarDatosGasesSuministrados($arreglo){
            //echo '<br>function InsertarDatosGasesSuministrados...';
              $cont=sizeof($_SESSION['Liquidacion_QX']['GASES']);              
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['TipoGas']=$arreglo[0];           
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['TipoGasDes']=$arreglo[1];           
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['MetodoGas']=$arreglo[2];           
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['MetodoGasDes']=$arreglo[3];            
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['FrecuenciaGas']=$arreglo[4];           
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['FrecuenciaGasDes']=$arreglo[5];           
              $_SESSION['Liquidacion_QX']['GASES'][$cont]['MinutosGas']=$arreglo[6];                                       
              $cadena .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
              $cadena .= "<tr class=\"modulo_list_oscuro\">";
              $cadena .= "<td align=\"center\" width=\"30%\" class=\"label\">TIPO GAS</td>";
              $cadena .= "<td align=\"center\" width=\"30%\" class=\"label\">METODO SUMINISTRO</td>";
              $cadena .= "<td align=\"center\" width=\"20%\" class=\"label\">FRECUENCIA SUMINISTRO(L/m)</td>";
              $cadena .= "<td align=\"center\" width=\"15%\" class=\"label\">MINUTOS</td>";
              $cadena .= "<td align=\"center\" width=\"5%\" class=\"label\">&nbsp;</td>";
              $cadena .= "</tr>";
              foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector){
                $cadena .= "<tr class=\"modulo_list_oscuro\">";
                $cadena .= "<td width=\"30%\">".$vector[TipoGasDes]."</td>";
                $cadena .= "<td width=\"30%\">".$vector[MetodoGasDes]."</td>";
                $cadena .= "<td width=\"20%\">".$vector[FrecuenciaGas]."/".$vector[FrecuenciaGasDes]."</td>";
                $cadena .= "<td width=\"20%\">".$vector[MinutosGas]."</td>";
                $accionElimina=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarGasQuirurgico',array("contador"=>$i,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $cadena .= "<td width=\"5%\"><a href=\"javascript:EliminarGasAnestesico(new Array('$i'))\"><img title=\"Eliminar Gas\" border=\"0\" src=\"".SessionGetVar("RutaImagen")."/images/elimina.png\"></a></td>";
                $cadena .= "</tr>";
              }
              $cadena .= "</table>";
              return $cadena;
          
          }
          
          function EliminarGasAnestesicoVector($vector){
	  //echo '<br>function EliminarGasAnestesicoVector...';
	  //echo '<br><br><br>OJO: <pre>'; print_r($vector);
            for($i=$vector[0];$i<sizeof($_SESSION['Liquidacion_QX']['GASES']);$i++){
              $_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['TipoGas'];           
              $_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['TipoGasDes'];           
              $_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MetodoGas'];           
              $_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MetodoGasDes'];            
              $_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['FrecuenciaGas'];           
              $_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['FrecuenciaGasDes'];           
              $_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MinutosGas'];
	      
	     // $_SESSION['Liquidacion_QX']['GASES'][$i]['GasId']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['GasId'];
            }   
            unset($_SESSION['Liquidacion_QX']['GASES'][$i-1]);  
            $cadena .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
            $cadena .= "<tr class=\"modulo_list_oscuro\">";
            $cadena .= "<td align=\"center\" width=\"30%\" class=\"label\">TIPO GAS</td>";
            $cadena .= "<td align=\"center\" width=\"30%\" class=\"label\">METODO SUMINISTRO</td>";
            $cadena .= "<td align=\"center\" width=\"20%\" class=\"label\">FRECUENCIA SUMINISTRO(L/m)</td>";
            $cadena .= "<td align=\"center\" width=\"15%\" class=\"label\">MINUTOS</td>";
            $cadena .= "<td align=\"center\" width=\"5%\" class=\"label\">&nbsp;</td>";
            $cadena .= "</tr>";
            foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector){
              $cadena .= "<tr class=\"modulo_list_oscuro\">";
              $cadena .= "<td width=\"30%\">".$vector[TipoGasDes]."</td>";
              $cadena .= "<td width=\"30%\">".$vector[MetodoGasDes]."</td>";
              $cadena .= "<td width=\"20%\">".$vector[FrecuenciaGasDes]."</td>";
              $cadena .= "<td width=\"20%\">".$vector[MinutosGas]."</td>";
              //$accionElimina=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarGasQuirurgico',array("contador"=>$i,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
              $cadena .= "<td width=\"5%\"><a href=\"javascript:EliminarGasAnestesico(new Array('$i'))\"><img title=\"Eliminar Gas\" border=\"0\" src=\"".SessionGetVar("RutaImagen")."/images/elimina.png\"></a></td>";
              $cadena .= "</tr>";
            }
            $cadena .= "</table>";
            return $cadena;  
          }
  }     
  
  $oRS = new procesos_admin( array( 'Tipo_Afiliado','Niveles'));

  // el metodo action es el que recoge los datos (POST) y actua en consideraci�n ;-)
  $oRS->action();

?>