<?php

/**
 * $Id: reporte_detalle_auditoria.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * //
 */
IncludeClass('ConexionBD');
IncludeClass('ClaseUtil');
IncludeClass("DMLs_pqrs", "classes", "app", "ESM_AdminPqrs");

class Caso_report {

    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
    var $datos;
    //PARAMETROS PARA LA CONFIGURACION DEL REPORTE
    //NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
    var $title = '';
    var $author = '';
    var $sizepage = 'leter';
    var $Orientation = '';
    var $grayScale = false;
    var $headers = array();
    var $footers = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function Caso_report($datos = array()) {
        $this->datos = $datos;
        return true;
    }

    /**
     *
     */
    function GetMembrete() {
       // $nc = new FacturasDespachoSQL();

      /*  $parametro['tipo_id_tercero'] = '-1';

        $est = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:20pt\"";
        $html = "	<table width=\"70%\" align=\"center\" $est rules=\"all\">\n";
        $html .= "		<tr class=\"label\">\n";
        $html .= "			<td align=\"center\">GESTION SERVICIO AL CLIENTE</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
        $html .= "			<td align=\"center\">" . $this->datos['texto2'] . "</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
        $html .= "			<td align=\"center\">" . $this->datos['municipio_empresa'] . "</td>\n";
        $html .= "		</tr>\n";
        $html .= "</table>";
        $titulo .= "<b $est >REPORTE DE AUDITORIA SELECCIONADA<br>";
        $titulo .= $html;

        $Membrete = array('file' => false, 'datos_membrete' => array('titulo' => $titulo,
                'subtitulo' => ' ',
                'logo' => 'logocliente.png',
                'align' => 'left'));
        return $Membrete;*/
    }

    //FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
  /*  function CrearReporte() {
        $detalles = $this->datos["detalle"];
                  $encabezado = $this->datos["encabezado"];
                  
                  //calificacion
                  $satifsfaccion = "";
                  
                  if($encabezado["calificacion"] == 4){
                      $satifsfaccion = "EXCELENTE";
                      
                  } else  if($encabezado["calificacion"] == 3){
                      $satifsfaccion = "BUENO";
                      
                  } else  if($encabezado["calificacion"] == 2){
                      $satifsfaccion = "ACEPTABLE";
                      
                  } else  if($encabezado["calificacion"] == 1){
                      $satifsfaccion = "MALO";
                  }
                  
                  
                  $tiemporespuesta = "";
                  $fecharespuesta = "";
                  $fechacerrado    = "";
                  
                  //tiempo de respuesta
                  if(count($detalles) > 1){
                      $fechaapertura = $detalles[0]["fecha_registro"];
                      $fecharespuesta = $detalles[1]["fecha_registro"];
                      
                      $tiemporespuesta = $this->intervalo_fecha($fechaapertura, $fecharespuesta);                      
                  }
                  
                  
                  //fecha de cierre
                  
                  if(trim($encabezado["estadoCaso"]) == "Cerrado" ){
                      $fechacerrado = $detalles[(count($detalles) - 1)]["fecha_registro"];
                  }
                  
    }*/
    
    function intervalo_fecha($init,$finish){
            //formateamos las fechas a segundos tipo 1374998435
            $diferencia = strtotime($finish) - strtotime($init);

            //comprobamos el tiempo que ha pasado en segundos entre las dos fechas
            //floor devuelve el n�mero entero anterior, si es 5.7 devuelve 5
           // echo "diferencia ".$diferencia;
            
            if($diferencia < 60){
                $tiempo =  floor($diferencia) . " segundo(s)";
            }else if($diferencia > 60 && $diferencia < 3600){
                $tiempo =  floor($diferencia/60) . " minuto(s)'";
            }else if($diferencia > 3600 && $diferencia < 86400){
                $tiempo =  floor($diferencia/3600) . " hora(s)";
            }else if($diferencia > 86400 && $diferencia < 2592000){
                $tiempo =  floor($diferencia/86400) . " d�a(s)";
            }else if($diferencia > 2592000 && $diferencia < 31104000){
                $tiempo =  floor($diferencia/2592000) . " mese(s)";
            }else if($diferencia > 31104000){
                $tiempo =  floor($diferencia/31104000) . " a�o(s)";
            }else{
                $tiempo = "Error";
            }
            return $tiempo;
        }

      function CrearReporte() {
                  $bdobj = new DMLs_pqrs();
                  
                 $encabezados = $bdobj->buscarEncabezadoCaso($this->datos["encabezado"]["caso"]);
                 
               //  echo print_r($this->datos);
                 
              //    echo print_r($encabezado)."</br>";
                 //  echo print_r($detalles);
                 
                $cliente =  (is_null($encabezados[0]["farmacia"]))?$encabezados[0]['nombre_tercero']:$encabezados[0]["farmacia"];
                 
                 $html = "
                        <style>
                                .tdtitle{
                                    background-color:#C0C0C0;
                                }
                                
                                .tdcontent{
                                    background-color:white;
                                }
                                
                                .tablereport{
                                        border-spacing:0px;
                                        border-collapse:collapse;
                                }
                                
                                .tablereport td{
                                        border:1px solid black;
                                        height:30px;
                                }
                                
                                .tdnoborder{
                                    border:none!important;
                                }
                                
                                .tdnovedad{
                                    background-color:blue;
                                    color:white;
                                }
                                
                                .reportitle{
                                    text-align:center;
                                }
                                
                                .tdmaintitle{
                                    font-weight:bold;
                                }
                                
                                .tdfirma{
                                    padding-left:30px;
                                }
                                
                                .parrafo1{
                                    border-width: 0.5px;border-style: solid;float: left; 
                                    height: 10px;
                                    width: 70%;
                                    float: center;
                                    margin: 2px; 
                                    padding: 10px;
                                }
                                .parrafo2{
                                    border: black 0px solid;
                                    float: left; 
                                    height: 10px;                                                 
                                    margin: 7px; 
                                    padding: 3px;                                                 
                                }
                                .parrafo3{
                                    border: black 0px solid;
                                    float: left; 
                                    height: 5px; 
                                    margin: 7px; 
                                    padding: 3px; 
                                                                                             
                                }
                                .parrafo4{
                                    border: black 0px solid;
                                    float: right; 
                                    height: 5px; 
                                    margin: 7px; 
                                    padding: 3px;                                       
                                }
                        </style>

                  ";
                 
                 $html .= '<table   width="70%" align="center" border="1">
                                <tr >
                                    <td colspan="11">
                                        <table width="100%" >
                                                <tr>
                                                         <td rowspan="2" colspan="1" width="200"><img src="' . GetThemePath() . '/images/logoreportepqrs.png" border="0"></td>
                                                        <td colspan="7" align="center" class="tdmaintitle">GESTION LOGISTICA</td>
                                                         <td colspan="2" class="tdmaintitle">GLG-FT-10</BR>VERSION 1</td>
                                                </tr>
                                                
                                                <tr>
                                                                 <td colspan="7" align="center">REPORTE DE NO CONFORMIDADES EN DESPACHOS DE MEDICAMENTOS Y DISPOSITIVOS MEDICOS</td>
                                                                 <td colspan="2">PAGINA 1 DE 1</td>
                                                </tr>
                                         </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>El reporte de la novedad debe ser tres dias habiles laborales una vez recibido el pedido<b></td>
                                    <td><b>El tiempo de respuesta de la novedad es tres dias habiles laborales una vez radicada la novedad<b></td>
                                    <td colspan="3" width="400" align="center"><p class="parrafo2" ><b>AREA</b></p><p class="parrafo1">'.$cliente.'</p></td>
                                     
                                </tr>
                                <tr  align=\"center\">
                                    <td colspan="11" class="trtitle">
                                        <table width="100%" class="tablereport">
                                            <tr>
                                             <td class="tdtitle reportitle" >
                                                    <b>Fecha de </br>Recepcion pedido</br>DD-MM-AAAA</b>
                                            </td>
                                            <td class="tdtitle reportitle">
                                                    <b>Tipo de</br>Documento</b>
                                            </td>
                                            <td class="tdtitle reportitle">
                                                    <b>Numero de </br>Documento</b>
                                            </td>
                                            <td class="tdtitle reportitle" >
                                                    <b>Descripcion del medicamento y/o</br>dispositivos medicos</b>
                                            </td>
                                                                                                         <td class="tdtitle reportitle">
                                                    <b>Presentacion</b>
                                            </td>
                                                                                                         <td class="tdtitle reportitle">
                                                    <b>Laboratorio</b>
                                            </td>
                                                                                                         <td class="tdtitle reportitle">
                                                    <b>Cantidad</br>Remisionada</b>
                                            </td>
                                                                                                         <td class="tdtitle reportitle">
                                                    <b>Cantidad</br>Recibida</b>
                                            </td>
                                                                                                         <td class="tdtitle reportitle">
                                                    <b>Costo</br>Unitario</b>
                                            </td>
                                                                                                         <td class="tdtitle tdnovedad reportitle">
                                                    <b>Tipo de</br>novedad</b>
                                            </td>
                                            <td width="322" class="tdtitle reportitle">
                                                    <b>Observaciones Adicionales a la No conformidad:</b> 
                                            </td>
                                       </tr> ';
                 $tama=sizeof($encabezados);
                 $entrar=true;
                 foreach ($encabezados as $key => $encabezado) {
                                   $html .= '<tr>
                                                <td class="tdcontent">
                                                        ' . $encabezado["fecha_recepcion"] . '
                                                </td>
                                                <td class="tdcontent">
                                                         ' . $encabezado["tipo_documento"] . '
                                                </td>
                                                <td class="tdcontent">
                                                         ' . $encabezado["numero_documento"] . '
                                                </td>
                                                <td class="tdcontent">
                                                        ' . $encabezado["descripcion_producto"] . '
                                                </td>
                                                <td class="tdcontent">
                                                         ' . $encabezado["presentacion"] . '
                                                </td>
                                                <td class="tdcontent">
                                                         ' . $encabezado["laboratorio"] . '
                                                </td>
                                                <td class="tdcontent">
                                                         ' . $encabezado["cantidad_despachada"] . '
                                                </td>
                                                <td class="tdcontent">
                                                        ' . $encabezado["cantidad_recibida"] . '
                                                </td>
                                                 <td class="tdcontent">

                                                </td>
                                                <td class="tdcontent">
                                                        ' . $encabezado["categoria"] . '
                                                </td>';
                                                if($entrar){
                                                $entrar=false;
                                  $html .= '    <td class="tdcontent" rowspan="'.$tama.'">
                                                       ' . $this->datos['detalle'][0]['observacion'] . '
                                                </td>';
                                                }
                                                
                                               // echo "<pre>"; print_r(UserGetVars($this->datos['detalle'][0]['usuario_id']));
                                  $html .= '   </td>
                                                </tr>';
                                        }        //UserGetVars( 
                                        $usuario=UserGetVars($this->datos['detalle'][0]['usuario_id']);
                                 $html .= ' </table>
                                            </tr>                                
                                            <tr>
                                                <td colspan="11">
                                                        <table width="100%" border="0" class="tablereport">
                                                                <tr>
                                                                        <td colspan="2" class="tdtitle tdnoborder">Nombre del Cliente o farmacia</td>
                                                                        <td colspan="3" class="tdtitle" width="250">'.$cliente.'</td>
                                                                        <td colspan="7" class="tdnoborder"></td>
                                                                </tr>
                                                                 <tr>
                                                                        <td colspan="2" class="tdtitle tdnoborder">Nombre y cargo de la persona que realiza el hallazgo</td>
                                                                        <td colspan="3" class="tdtitle" width="250">' . $usuario['nombre'] . '</td>
                                                                        <td colspan="7" class="tdnoborder tdfirma">Recibido area responsable ______________________________________________</td>
                                                                </tr>
                                                                  <tr>
                                                                        <td colspan="2" class="tdtitle tdnoborder">Fecha de reporte</td>
                                                                        <td colspan="3" class="tdtitle" width="250" >' .$this->datos['detalle'][0][fecha_registro]. '</td>
                                                                        <td colspan="7" class="tdnoborder tdfirma">Fecha (DD/MM/AAAA)</td>
                                                                </tr>

                                                           </table>
                                                  </td>
                                        </tr>
                                        <tr>
                                            <td colspan="11">
                                                <table width="100%">
                                                        <tr >
                                                                <td colspan="11" ><p style="border-width: 0.5px;border-style: solid;">Solucion de la No Conformidad (diligenciado area responsable)</p></td>
                                                        </tr>
                                                        <tr>
                                                                <td height="80"></td>
                                                        </tr>
                                                        <tr>
                                                                <td  rowspan="3"><img src="' . GetThemePath() . '/images/tipo_novedad.png" border="0" WIDTH=700 HEIGHT=150></td>
                                                                <td width="800" align="center" valign="top">Autorizacion jefe de area</td>
                                                        </tr>
                                                   </table>
                                              </td>
                                        </tr>
                                
                                <tr border="0">
                                    <td colspan="11" align="center">
                                           <p class="parrafo3" align="left" font-family: "arial", serif; font-size: 50%; >Para Mayor Informaci&oacute;n puede comunicarse con <b> GESTION LOGISTICA  al correo electr&oacute;nico novedadesenpedido@duanaltda.com </b></p>
                                           <p class="parrafo4" align="right" font-family: "arial", serif;  font-size: 50%; >Fecha de Vigencia 16/02/2016</p>
                                       
                                    </td>
                                </tr>
                            </table>
                            </br>
                            </br>
                            <center><a href="#" onclick="window.print(); return false;"><b>IMPRIMIR</b></a></center>';
                 
                 return $html;
                  
      }
}

?>