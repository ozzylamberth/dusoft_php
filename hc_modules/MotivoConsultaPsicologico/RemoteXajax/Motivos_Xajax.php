<?php

     /**
     * Conducta Examen Fisico Xajax
     *
     * @author Tizziano Perea
     * @version 1.0
     * @package SIIS
     * $Id: Motivos_Xajax.php,v 1.1 2007/11/30 20:47:02 tizziano Exp $
     */
	
     function SelectTipos($Tipo1)
	{
		$objResponse = new xajaxResponse();
          $html = GetFormaTiposCre($Tipo1);
          if($html)
		{
               $objResponse->assign("tipos_cre","style.display","block");
               $objResponse->assign("tipos_cre","innerHTML",$html);
		}
		return $objResponse;
	}

     function GetFormaTiposCre($Tipo1)
     {
		list($dbconn) = GetDBconn();
		
          //Consulta seq.
          $query = "SELECT * FROM hc_psicologia_tipo_motivos_consulta_detalle
          		WHERE motivo_id = ".$Tipo1.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$error = "Error al Consultar en hc_psicologia_tipo_motivos_consulta_detalle";
			return $error;
		}
          
          $datos = array();
          while(!$resulta->EOF)
          {
               $datos[] = $resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }

          $html = "";
          if(is_array($datos))
          {
               //Vista de datos
               $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $html.= "	<tr>";
               $html.= "	<td class=\"modulo_table_title\">MOTIVOS DE CONSULTA (Crecimiento Personal)</td>";
               $html.= "	</tr>";
               for($i=0; $i<sizeof($datos); $i++)
               {
                    if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $html.= "	<tr>";
                    $html.= "	<td class=\"$estilo\" width=\"40%\" align=\"left\"><input type=\"checkbox\" name=\"tipos_consulta\" id=\"tipos_consulta\" value=\"".$datos[$i]['motivo_detalle_id']."\" onclick=\"javascript:LlenarVectorDX('".$datos[$i]['motivo_detalle_id']."', '', '');\">&nbsp;&nbsp;&nbsp;".utf8_encode($datos[$i]['descripcion'])."</td>";
                    $html.= "	</tr>";
               }
               $html.= "	<tr>";
               $html.= "	<td class=\"$estilo\" width=\"40%\" align=\"center\"><input type=\"button\" class=\"input-submit\" name=\"save_consulta\" id=\"save_consulta\" value=\"INSERTAR\" onclick=\"javascript:LlenarVectorDX('', '1', '".$Tipo1."');\"></td>";
               $html.= "	</tr>";
               $html.= "	</table>";
          }
          
          return $html;
     }
     
     function InsertarDetalle($Vector, $Categoria)
     {
          $objResponse = new xajaxResponse();
          $html = SeleccionDetalle($Vector, $Categoria);
          
          if($html == "Ok")
          {
               $objResponse->call("RecargarPage");
          }
          return $objResponse;
     }
     
     function SeleccionDetalle($Vector, $Categoria)
     {
          list($dbconn) = GetDBconn();
          
          for($i=0; $i<sizeof($Vector); $i++)
          {
               $query = "INSERT INTO hc_psicologia_motivo_consulta_detalle 
                                        VALUES (  ".SessionGetVar("Ingreso").",
                                                  ".SessionGetVar("Evolucion").",
                                                  '".$Categoria."',
                                                  '".$Vector[$i]."');";
               $dbconn->Execute($query);
          
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo - No se pudo insertar en la tabla - hc_psicologia_motivo_consulta_detalle. $query";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return $this->error;
               }
          }
     	
          $html = "Ok";
          return $html;
     }
     
?>