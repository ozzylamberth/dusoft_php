<?php
	/**************************************************************************************
	* $Id: EECargoXajax.php,v 1.3 2007/12/06 16:03:21 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Tizziano Perea O.
	**************************************************************************************/
	IncludeClass("app_ModuloAdminPsicologia_user","","app","ModuloAdminPsicologia");


    function ConsultaTaller($DatosTaller)
    {
          $objResponse = new xajaxResponse();
          list($dbconn) = GetDBconn();

          $query = "SELECT  * 
               	FROM  hc_psicologia_talleres_psicologicos
                    WHERE taller_id = ".$DatosTaller."";
     	$result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
                    return false;
               }
               while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
          }
          
          $query = "SELECT  * 
               	FROM  system_usuarios
                    WHERE usuario_id = ".$vars[0]['responsable_id']."";
     	$result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
                    return false;
               }
               while (!$result->EOF) {
                    $user[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
          }
          
          $salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $salida .= "  <tr class=\"modulo_table_title\">\n";
          $salida .= "      <td colspan='2' height='30'>TALLER PSICOLOGICO</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\" width=\"50%\">NOMBRE DEL TALLER</td>\n";
          $salida .= "      <td align=\"left\" width=\"50%\" class=\"modulo_list_claro\">".$vars[0]['nombre_taller']."</td>\n";
		$salida .= "  </tr>\n";          
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">INTRODUCCION DEL TALLER</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['introduccion']."</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">OBJETIVOS DEL TALLER</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['objetivos']."</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">CONTENIDOS GENERALES</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['contenido']."</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">METODOLOGIA</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['metodologia']."</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">RESPONSABLE</td>\n";
		$salida .= "	<td colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$user[0]['nombre']."</td>";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">PORCENTAJE DE SESIONES MINIMAS</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['sesiones_minimas']."</tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">INTENSIDAD</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['intensidad']."</td>\n";
          $salida .= "  </tr>\n";
          if($vars[0]['autonomia'] == "1")
          { $autonomia_r = "PROPIO"; }else{ $autonomia_r = "APOYO DE OTRA AREA"; }
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">AUTONOMIA DEL TALLER:  <font color=\"red\">".$autonomia_r."</font></td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[0]['areas_apoyo']."<td>\n";
          $salida .= "  </tr>\n";
          $salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          if ($vars[0]['sw_estado'] == 1)
          {
          	$salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"ModTaller\" onclick=\"ActivarDesactivar('".$vars[0]['taller_id']."','".$vars[0]['sw_estado']."');\" value=\"DESACTIVAR\">\n";
          }
          else
          {
          	$salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"ModTaller\" onclick=\"ActivarDesactivar('".$vars[0]['taller_id']."','".$vars[0]['sw_estado']."');\" value=\"ACTIVAR\">\n";
          }
          $salida .= "                    </td>\n";
          $salida .= "                </tr>\n";
          $salida .= "            </table>\n";
          
          $objResponse->assign("ContenidoConTaller","innerHTML",$salida);
          return $objResponse;
    }
    
    
    function ActivarTaller($Taller, $Estado)
    {
          $objResponse = new xajaxResponse();
          list($dbconn) = GetDBconn();
          
          if($Estado == "1")
          { $estadio = "0"; }
          else
          { $estadio = "1"; }
          
          $query = "UPDATE hc_psicologia_talleres_psicologicos
          		SET sw_estado = '".$estadio."'
                    WHERE taller_id = '".$Taller."'";
                    
          $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Desactivar el Taller";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          
          $objResponse->Call("CerrarCapaConsulta");
          return $objResponse;
    }

     function ProgramacionesTalleres($DatosTaller)
     {
          $objResponse = new xajaxResponse();
          list($dbconn) = GetDBconn();

          $query = "SELECT  * 
                    FROM  hc_psicologia_talleres_psicologicos
                    WHERE taller_id = ".$DatosTaller."";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
                    return false;
               }
               while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
          }
          
          $salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $salida .= "  <tr class=\"modulo_table_title\">\n";
          $salida .= "      <td colspan='4' height='30'>PROGRAMAR TALLER PSICOLOGICO</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\" colspan='2' width=\"50%\">NOMBRE DEL TALLER</td>\n";
          $salida .= "      <td align=\"left\" colspan='2' width=\"50%\" class=\"modulo_list_claro\">".$vars[0]['nombre_taller']."</td>\n";
          $salida .= "  </tr>\n";          
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">FECHA DE INICIO</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"fechaini\" id=\"fechaini\" size='11' maxlength=\"10\"> <b>[dd/mm/aaaa]</b>";
          $salida .= "      <td align=\"left\">PERIODICIDAD</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">";
          $salida .= "      <select name=\"periodicidad\" id=\"periodicidad\" class=\"select\">";
          $salida .= "      <option value=\"-1\" selected>---Seleccione---</option>";
          $salida .= "      <option value=\"1\">Todos los dias</option>";
          $salida .= "      <option value=\"7\">Cada Semana</option>";
		$salida .= "      <option value=\"14\">Cada 15 Dias</option>";
		$salida .= "      <option value=\"30\">Cada Mes</option>";          
          $salida .= "      </select>";
          $salida .= "      </td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">NUMERO DE SESIONES</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"sesiones\" id=\"sesiones\" size='5' maxlength=\"2\">&nbsp;&nbsp;<b>Sesiones</b></td>\n";
          $salida .= "      <td align=\"left\">UBICACION</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">";
          $salida .= "      <input type=\"text\" class=\"input-text\" name=\"ubicacion\" id=\"ubicacion\"  size='40' maxlength=\"256\">";
          $salida .= "      </td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"left\">HORA INICIO SESION</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"Horaini\" id=\"Horaini\" size='10' maxlength=\"5\">&nbsp;&nbsp;<b>[HH:MM]</b></td>\n";
          $salida .= "      <td align=\"left\">HORA FIN SESION</td>\n";
          $salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"Horafin\" id=\"Horafin\" size='10' maxlength=\"5\">&nbsp;&nbsp;<b>[HH:MM]</b></td>\n";
          $salida .= "  </tr>\n";
          $salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "                    <td colspan=\"4\" align=\"center\">\n";
          $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"ProgTaller\" id=\"ProgTaller\" onclick=\"ProgramarTallerSave('".$vars[0]['taller_id']."');\" value=\"PROGRAMAR\">\n";
          $salida .= "                    </td>\n";
          $salida .= "                </tr>\n";
          $salida .= "            </table><br>\n";
          $objResponse->assign("ContenidoProgramacion","innerHTML",$salida);
          return $objResponse;
     }
     
     function CrearProgramacionT($fecha, $intervalo, $HoraInicio, $HoraFin, $Nsesiones, $Taller, $ubicacion)
     {
     	$objResponse = new xajaxResponse();
          list($dbconn) = GetDBconn();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_programaciones_programacion_id_seq');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar Secuencia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          $prog_id = $result->fields[0];
          
          //Insert
          $query = "INSERT INTO hc_psicologia_programaciones_talleres
          		VALUES (".$prog_id.", ".$Taller.", '1', 'now');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_roles_profesionales";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
        
          for($i = 0; $i < $Nsesiones; $i++)
          {
               $query = "INSERT INTO hc_psicologia_talleres_programaciones_sesiones (taller_id,
                                                                            	     fecha_inicio,
                                                                            		ubicacion,
                                                                            		hora_inicio,
                                                                            		hora_fin,
                                                                            		programacion_id)
                                                                           VALUES (".$Taller.",
                                                                                   '".$fecha."',
                                                                                   '".$ubicacion."',
                                                                                   '".$HoraInicio."',
                                                                                   '".$HoraFin."',
                                                                                   ".$prog_id.");";
               
               $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               list($dia, $mes, $año) = split("/", $fecha);
               $fechanueva = mktime(0, 0, 0, $mes, $dia, $año) + $intervalo * 24 * 60 * 60; 
               $fecha = date("d/m/Y",$fechanueva);
          }
          $objResponse->Call("CerrarCapaProg");
          return $objResponse;
     }
     
     
     function CancelarTalleres($DatosTaller)
     {
          $objResponse = new xajaxResponse();
          list($dbconn) = GetDBconn();

          $query = "SELECT  A.*, B.sw_estado AS estado, B.programacion_id, C.nombre_taller
                    FROM  hc_psicologia_talleres_programaciones_sesiones AS A,
                          hc_psicologia_programaciones_talleres AS B,
					 hc_psicologia_talleres_psicologicos AS C
                    WHERE A.taller_id = ".$DatosTaller."
                    AND A.taller_id = B.taller_id
                    AND A.taller_id = C.taller_id
                    AND B.sw_estado = '1';";
          $objResponse->alert($query);
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
                    return false;
               }
               while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
          }
          
          $salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $salida .= "  <tr class=\"modulo_table_title\">\n";
          $salida .= "      <td colspan='5'>CANCELAR PROGRAMACION: ".$vars[0]['nombre_taller']."</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td>SESION</td>\n";
          $salida .= "      <td>FECHA</td>\n";
          $salida .= "      <td>HORA INICIO</td>\n";
          $salida .= "      <td>HORA FIN</td>\n";
          $salida .= "      <td>UBICACION</td>\n";
          $salida .= "  </tr>\n";          
          for($i=0; $i<sizeof($vars); $i++)
          {
               $a = $i + 1;
               $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$a."</td>\n";
               $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[$i]['fecha_inicio']."</td>\n";
               $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[$i]['hora_inicio']."</td>\n";
               $salida .= "      <td align=\"left\" class=\"modulo_list_claro\">".$vars[$i]['hora_fin']."</td>\n";
               $salida .= "      <td align=\"center\" class=\"modulo_list_claro\">".$vars[$i]['ubicacion']."</td>\n";
               $salida .= "  </tr>\n";          
          }
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "      <td align=\"center\" colspan='5'><input type=\"button\" class=\"input-submit\" name=\"CancelTaller\" id=\"CancelTaller\" onclick=\"CancelTallerP('".$vars[0]['programacion_id']."', '".$vars[0]['taller_id']."');\" value=\"CANCELAR PROGRAMACION\"></td>\n";
          $salida .= "  </tr>\n";
          $salida .= "</table><br>\n";
          $objResponse->assign("ContenidoCancelacion","innerHTML",$salida);
          return $objResponse;
     }
     
     function CancelProgramacionT($Programacion, $Taller)
     {
     	$objResponse = new xajaxResponse();
          $centinela = false;
          list($dbconn) = GetDBconn();
          
          $query = "UPDATE hc_psicologia_programaciones_talleres
          		SET sw_estado = '2'
                    WHERE programacion_id = ".$Programacion.";";
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $objResponse->Call("CerrarCapaCancel");
          return $objResponse;
     }
     
    

?>