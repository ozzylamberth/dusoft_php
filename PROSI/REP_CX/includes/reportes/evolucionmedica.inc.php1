<?php
/**
* Reporte Evolución médica diliginciada.
*
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
* $Id: evolucionmedica.inc.php,v 1.3 2007/01/22 23:00:47 tizziano Exp $
*/

    include_once("classes/fpdf/html_class.php");
    
    function GetMembrete()
    {
        $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
                                                                'subtitulo'=>'',
                                                                'logo'=>'logocliente.png',
                                                                'align'=>'left'));
        return $Membrete;
    }

     function CrearReporteEvolucionMedica($ingreso, $evolucion, $cuenta, $usuario_id, $plan, $servicio, $tipoidpaciente, $paciente)
     {
          $Dir="cache/evolucionmedica".$cuenta.".pdf";
          
          define('FPDF_FONTPATH','font/');
          $pdf=new PDF();
          $pdf->AddPage();
          $pdf->SetFont('Arial','',12);

          $datos	=	GetEvolucion_Diligenciadas($evolucion, $tipoidpaciente, $paciente);
          $Salida ="<br><table width=\"830%\" border=\"0\" align=\"center\">";
          $Salida .="<tr>";
          $Salida .="<td width='760' align='CENTER'>--------------------------------------------------------------------------------------------------------------------------------------</td>";
          $Salida .="</tr>";
          $Salida .="<tr class=\"hc_table_submodulo_list_title\">";
          $Salida .="<td>FECHA</td>";
          $Salida .="<td align=\"center\">LISTADO DE EVOLUCIONES MEDICAS DILIGENCIADAS</td>";
          $Salida .="</tr>";
          $Salida .="<tr>";
          $Salida .="<td width='760' align='CENTER'>--------------------------------------------------------------------------------------------------------------------------------------</td>";
          $Salida .="</tr>";

          foreach($datos as $k=>$v)
          {
               $Salida.="<tr>";
               $Salida .="<td><table border='0' width='100%'>";
               foreach($v as $k2=>$vector)
               {
                    $Salida .="<tr class=\"hc_submodulo_list_oscuro\">";
                    $Salida .="<td width='120%' align='center'><b>$k</b></td>";
                    $Salida .="<td width='60%'><b>$vector[hora]</b></td>";
                    $Salida .="<td><b>";
                    $Salida .=$vector[usuario].' - '.$vector[nombre];
                    $Salida .="</b></td>";
                    $Salida .="</tr>";
                    $Salida .="<tr>";
                    $descripcion=strtolower($vector[descripcion]);
                    $Salida .="<td width='100%'><font size='2'>".substr($descripcion,0,90)."-</font></td>";
                    $Salida .="</tr>";
                    $Salida .="<tr>";
                    $Salida .="<td width='100%'><font size='2'>".substr($descripcion,91,100)."</font></td>";
                    $Salida .="</tr>";
                    $Salida .="<tr>";
                    $Salida .="<td width='100%'><font size='2'>".substr($descripcion,191,100)."</font></td>";
                    $Salida .="</tr>";
                    $Salida .="<tr>";
                    $Salida .="<td width='100%'><font size='2'>".substr($descripcion,291,100)."</font></td>";
                    $Salida .="</tr>";
                    $Salida .="<tr>";
                    $Salida .="<td width='100%'>&nbsp;</td>";
                    $Salida .="</tr>";
               }

          $Salida .="</table></td>";
          $Salida .="</tr>";
          }
          $Salida .="<tr>";
          $Salida .="<td width='760' align='RIGHT'>Fecha impresión: ".date('Y-m-d h:m')."</td>";
          $Salida .="</tr>";
          $Salida.="</table>";
          $pdf->WriteHTML($Salida);
          $pdf->SetLineWidth(0.2);
          $pdf->RoundedRect(7, 7, 196, 284, 3.5, '');      
          $pdf->Output($Dir,'F');
          return true;
     }

			
     function GetEvolucion_Diligenciadas($evolucion, $tipoidpaciente, $paciente)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT ingreso 
                   FROM ingresos
                   WHERE tipo_id_paciente = '".$tipoidpaciente."'
                   AND paciente_id = '".$paciente."';";
          $resultado1 = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while(!$resultado1->EOF)
          {
               $datosfila[]=$resultado1->fields[0];
               $resultado1->MoveNext();
          }
          
          $k=0;
          foreach($datosfila AS $i=>$v)
          {
               $query= "SELECT B.hc_evolucion_descripcion_id,
                               B.fecha_registro, B.descripcion,
                               B.sw_epicrisis, C.nombre, C.usuario, b.evolucion_id,A.ingreso
                        FROM hc_evoluciones AS A,
                             hc_evolucion_descripcion AS B,
                             system_usuarios AS C
                        WHERE A.evolucion_id=B.evolucion_id 
                        AND A.ingreso=".$v."
                        AND A.evolucion_id<>".$evolucion."
                        AND C.usuario_id=B.usuario_id
                        ORDER BY B.fecha_registro DESC;";
               $resultado = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while(!$resultado->EOF)
               {
                    $datosfila=$resultado->GetRowAssoc($ToUpper = false);
                    list($fecha,$hora) = explode(" ",PartirFecha1($datosfila['fecha_registro']));
                    list($ano,$mes,$dia) = explode("-",$fecha);
                    list($hora,$min) = explode(":",$hora);
                    $datosfila[hora]=$hora.":".$min;
                    $fecha = $fecha;
                    $Primera_Evolucion[$fecha][$k]=$datosfila;
                    $k++;
                    $resultado->MoveNext();
               }
               $resultado->Close();
          } 
          return $Primera_Evolucion;
     }

     function BuscarCuentas($cuenta)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT tipo_afiliado_id,
                         rango,
                         semanas_cotizadas
                  FROM cuentas
                  WHERE numerodecuenta=".$cuenta.";";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $odonto[0]=$resulta->fields[0];
          $odonto[1]=$resulta->fields[1];
          $odonto[2]=$resulta->fields[2];
          return $odonto;
     }

     function NombreUs($user)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT nombre
                  FROM system_usuarios
                  WHERE usuario_id=".$user.";";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($usuario) = $resulta->FetchRow();
          return $usuario;
     }

	function FechaStamp($fecha)
     {
          if($fecha)
          {
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
     }

     function HoraStamp($hora)
     {
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }
          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
     }

     function PartirFecha1($fecha)
     {
          $a=explode('-',$fecha);
          $b=explode(' ',$a[2]);
          $c=explode(':',$b[1]);
          $d=explode('.',$c[2]);
          return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
     }
    //---------------------------------------
?>
