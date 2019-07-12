<?php

     /**
     * Conducta Examen Fisico Xajax
     *
     * @author Tizziano Perea
     * @version 1.0
     * @package SIIS
     * $Id: TrabajosRealizados_Xajax.php,v 1.1 2007/11/30 20:57:09 tizziano Exp $
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
          $query = "SELECT * FROM hc_psicologia_tipo_trabajos_realizados_detalle
          		WHERE trabajo_id = ".$Tipo1.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$error = "Error al Consultar en hc_psicologia_tipo_trabajos_realizados_detalle";
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
               $html.= "	<td class=\"modulo_table_title\">TRABAJOS</td>";
               $html.= "	</tr>";
               for($i=0; $i<sizeof($datos); $i++)
               {
                    if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $html.= "	<tr>";
                    $html.= "	<td class=\"$estilo\" width=\"40%\" align=\"left\"><input type=\"checkbox\" name=\"tipos_consulta\" id=\"tipos_consulta\" value=\"".$datos[$i]['trabajo_detalle_id']."\" onclick=\"javascript:LlenarVectorDX('".$datos[$i]['trabajo_detalle_id']."', '', '');\">&nbsp;&nbsp;&nbsp;".utf8_encode($datos[$i]['descripcion'])."</td>";
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
          
          $query = "INSERT INTO hc_psicologia_trabajos_realizados
          						  ( ingreso,
                                            evolucion_id,
                                            usuario_id,
                                            fecha_registro
                                          ) 
                                   VALUES (  ".SessionGetVar("Ingreso").",
                                             ".SessionGetVar("Evolucion").",
                                             ".SessionGetVar("Usuario").",
                                             'now()'
								  );";
          
          $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $error = "Error al Cargar el Modulo - No se pudo insertar en la tabla - hc_psicologia_trabajos_realizados. $query";
               return $error;
          }

          for($i=0; $i<sizeof($Vector); $i++)
          {
               $query = "INSERT INTO hc_psicologia_trabajos_realizados_detalle 
                                        VALUES (  ".SessionGetVar("Ingreso").",
                                                  ".SessionGetVar("Evolucion").",
                                                  '".$Categoria."',
                                                  '".$Vector[$i]."');";
               $dbconn->Execute($query);
          
               if ($dbconn->ErrorNo() != 0)
               {
                    $error = "Error al Cargar el Modulo - No se pudo insertar en la tabla - hc_psicologia_tipo_trabajos_realizados_detalle. $query";
                    return $error;
               }
          }
     	
          $html = "Ok";
          return $html;
     }
     
?>