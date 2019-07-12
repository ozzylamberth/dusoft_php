<?php

/**
* $Id: hc_Apoyos_Diagnosticos_Control_CDA.php,v 1.1 2009/07/30 12:38:06 johanna Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo MotivoConsulta
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class Apoyos_Diagnosticos_Control_CDA extends Extenciones_CDA_HC
{
    /**
    * Variable que contendra el Parametro de Busqueda
    *
    * @var $datos
    * @access private
    */
    var $datos;
    
    /**
    * Variable que contendra el Parametro para el Metodo Busqueda
    *
    * @var $TipoMetodo
    * @access private
    */
    var $TipoMetodo;
 
    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public    
    */
    function Apoyos_Diagnosticos_Control_CDA()
    {
        $this->Extenciones_CDA_HC();
        return true;
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EVOLUCION
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Evolucion($evolucion_id)
    {
      if (empty($evolucion_id))
      {
          return '';
      }
      else
      {
          $this->datos[evolucion] = $evolucion_id;
          $this->TipoMetodo = '1';
          $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
          $this->salida = $XML;
          return $this->salida;
      }
    }

    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EPICRISIS DE UN INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Epicrisis($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->datos[ingreso] = $ingreso;
               $this->TipoMetodo = '2';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Ingreso($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->datos[ingreso] = $ingreso;
               $this->TipoMetodo = '3';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una HISTORIA CLINICA DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Historia($paciente_id,$tipoidpaciente)
    {
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '4';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un RESUMEN DE ATENCIONES DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Resumen_Historia($paciente_id,$tipoidpaciente)
    { 
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '5';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }

		/*		GetXML_Local
     *
     *		Crea la vista de los datos en XML para su posterior traspaso
     *		a HTML y generacion de impresion.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param array => $XML_Consulta - Vector de datos.
     */

    function GetXML_Local($XML_Consulta)
    { //print_r($XML_Consulta);
			//$XML_Consulta = $this->Consulta_General();
			if ($XML_Consulta)
			{
					$salida.="<BR>";
					$salida.="<caption><center><b>EXAMENES SOLICITADOS AL PACIENTE POR:</b></center></caption>";

					//primer ciclo de busqueda
					$paso = 1;	$paso1 = 1;	$paso2 = 1;
					for($i=0;$i<sizeof($XML_Consulta);$i++)
					{
							//if ($XML_Consulta[$i][usuario_id] == UserGetUID())
							//{
									if ($paso==1)
									{
											$nombre  = $this->ConsultaNombreMedico($XML_Consulta[$i][usuario_id]);

											$salida.="<TABLE border=\"1\" width=\"100%\" align=\"center\">";

											$salida.="<COLGROUP align=\"center\">";
											$salida.="<COLGROUP align=\"left\">";

											$salida.="<THEAD valign=\"top\">";
											$salida.="<TR>";
											$salida.="<TH colspan=\"3\">".$nombre[descripcion]." - ".$nombre[nombre_tercero]."</TH>";
											$salida.="</TR>";
											$salida.="<TR>";
											$salida.="<TH>FECHA EVOLUCIÓN</TH>";
											$salida.="<TH>EXAMEN</TH>";
											$salida.="<TH>FECHA REALIZACIÓN</TH>";
											$salida.="</TR>";

											$salida.="<TR>";
											$salida.="<TH>".$XML_Consulta[$i][fecha]."</TH>";
											$salida.="<TH>".$XML_Consulta[$i][titulo_examenes]."</TH>";
											$salida.="<TH>".$XML_Consulta[$i][fecha_realizado]."</TH>";
											$salida.="</TR>";
											$salida.="</THEAD>";
											$salida.="</TABLE>";
											$paso++;
									}
									$salida.="<BR>";
									$salida.=$this->GetPlantillaApoyoDiagnostico($XML_Consulta[$i][resultado_id], $XML_Consulta[$i][sw_modo_resultado]);
									$salida.="<TABLE border=\"1\" width=\"80%\" align=\"center\">";

									$salida.="<COLGROUP align=\"center\">";
									$salida.="<COLGROUP align=\"left\">";
									for($i=0;$i<sizeof($vector);$i++)
									{
										$salida.="<TBODY>";
										$salida.="<TR>";
										$salida.="<TH>EXAMEN</TH>";
										$salida.="<TH>RESULTADO</TH>";
										$salida.="<TH>RANGO NORMAL</TH>";
										$salida.="</TR>";

										$salida.="<TR>";
										$salida.="<TH>".strtoupper($vector[$i][nombre_examen])."</TH>";
										if ($vector[$i][sw_alerta] == '1')
										{
											$salida.="<TH>".$vector[$i][resultado]." ".$vector[$i][unidades]."</TH>";
										}
										else
										{
											$salida.="<TH>".$vector[$i][resultado]." ".$vector[$i][unidades]."</TH>";
										}
										$salida.="<TH>".$vector[$i][rango_min]." - ".$vector[$i][rango_max]." ".$vector[$i][unidades]."</TH>";
										$salida.="</TR>";
										$salida.="</TBODY>";
									}
									$salida.="</TABLE>";
							//}

							$NoSolicitados = $this->ConsultaResultadosNoSolicitadosLeidos();
							//print_r($NoSolicitados);
							if($NoSolicitados)
							{
									$salida.="<TABLE border=\"1\" width=\"80%\" align=\"center\">";
									$salida.="<COLGROUP align=\"center\">";
									$salida.="<COLGROUP align=\"left\">";
									$salida.="<TR>";
									$salida.="<TH>OTROS APOYOS DIAGNOSTICOS </TH>";
									$salida.="</TR>";
									$salida.="</TABLE>";
									for($i=0;$i<sizeof($NoSolicitados);$i++)
									{
										$salida.=$this->GetPlantillaApoyoDiagnostico($NoSolicitados[$i][resultado_id], $NoSolicitados[$i][sw_modo_resultado]);
									}
							}
					}
				}
          return $salida;
		}

     /*		GetConsultaSubmodulo
     *
     *		Realiza la consulta de datos a partir de parametros como los datos
     *		del paciente y el tipo de impresion a realizar.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param integer => ingreso, evolucion_id, paciente_id, tipoidpaciente.
     */

    function GetConsultaSubmodulo($Paramdatos, $ParamTipo)
    { 
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

          switch($ParamTipo)
          {
               case '1':
/*               $query="SELECT evolucion_id,
               			descripcion,
                              enfermedadactual,
                              usuario_id,
                              fecha_registro,
                              ingreso
                       FROM hc_motivo_consulta
               	   WHERE evolucion_id=".$Paramdatos[evolucion].";";*/
            $this->ConsultaOwner_Antecedentes($Paramdatos[evolucion], $Paramdatos[ingreso]);
            //$this->ConsultaEvolucion($Paramdatos[ingreso]);
                $query="SELECT h.sw_modo_resultado, a.hc_os_solicitud_id, a.cargo, a.os_tipo_solicitud_id,
                b.usuario_id, b.departamento, b.fecha, k.informacion, case when d.hc_os_solicitud_id is
                not null then '1' else '0' end as autorizado, case when e.sw_estado is null
                then '0' else e.sw_estado end as realizacion, case when f.resultado_id is null
                then '0' else f.resultado_id end as resultados_sistema, case when g.resultado_id
                is null then '0' else g.resultado_id end as resultado_manual, e.numero_orden_id,
                h.fecha_realizado, case when k.titulo_examen is not null then k.titulo_examen
                else l.descripcion end as titulo_examenes, i.*, j.sw_prof, j.sw_prof_dpto,
                j.sw_prof_todos FROM hc_os_solicitudes as a left join apoyod_cargos as k on
                (a.cargo = k.cargo) left join cups as l on (a.cargo=l.cargo) left join
                hc_os_autorizaciones as d on (a.hc_os_solicitud_id=d.hc_os_solicitud_id)
                left join os_maestro as e on (a.hc_os_solicitud_id=e.hc_os_solicitud_id)
                left join hc_resultados_sistema as f on(e.numero_orden_id=f.numero_orden_id)
                left join hc_resultados_manuales as g on(e.numero_orden_id=g.numero_orden_id)
                left join hc_resultados as h on ((h.tipo_id_paciente='".$this->datos_paciente[tipo_id_paciente]."'
                and h.paciente_id='".$this->datos_paciente[paciente_id]."') and
                (f.resultado_id=h.resultado_id or g.resultado_id=h.resultado_id))

                left join hc_apoyod_resultados_detalles
                as i on (h.resultado_id=i.resultado_id) left join hc_apoyod_lecturas_profesionales
                as j on (h.resultado_id=j.resultado_id), hc_evoluciones as b, ingresos as c
                WHERE a.evolucion_id=b.evolucion_id and b.ingreso=c.ingreso and
                c.tipo_id_paciente='".$this->datos_paciente[tipo_id_paciente]."' and c.paciente_id='".$this->datos_paciente[paciente_id]."'
                and j.evolucion_id = ".$Paramdatos[evolucion]." order  by a.os_tipo_solicitud_id;";
                $resultado = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else
                {
                  while (!$resultado->EOF)
                  {
                    $XML_Consulta[]=$resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                }
                $resultado->Close();
                $salida = $this->GetXML_Local($XML_Consulta);
                return $salida;
            
/*								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
              if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($motivo = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $motivo;
               }
               	$salida = $this->GetXML_Local($XML_Consulta);
								return $salida;
               break;*/
                              
               case '2':
                $this->ConsultaOwner_Antecedentes($Paramdatos[evolucion], $Paramdatos[ingreso]);
                $this->ConsultaEvolucion($Paramdatos[ingreso]);

                $query = "SELECT h.sw_modo_resultado, a.hc_os_solicitud_id, a.cargo, a.os_tipo_solicitud_id,
                b.usuario_id, b.departamento, b.fecha, k.informacion, case when d.hc_os_solicitud_id is
                not null then '1' else '0' end as autorizado, case when e.sw_estado is null
                then '0' else e.sw_estado end as realizacion, case when f.resultado_id is null
                then '0' else f.resultado_id end as resultados_sistema, case when g.resultado_id
                is null then '0' else g.resultado_id end as resultado_manual, e.numero_orden_id,
                h.fecha_realizado, case when k.titulo_examen is not null then k.titulo_examen
                else l.descripcion end as titulo_examenes, i.*, j.sw_prof, j.sw_prof_dpto,
                j.sw_prof_todos FROM hc_os_solicitudes as a left join apoyod_cargos as k on
                (a.cargo = k.cargo) left join cups as l on (a.cargo=l.cargo) left join
                hc_os_autorizaciones as d on (a.hc_os_solicitud_id=d.hc_os_solicitud_id)
                left join os_maestro as e on (a.hc_os_solicitud_id=e.hc_os_solicitud_id)
                left join hc_resultados_sistema as f on(e.numero_orden_id=f.numero_orden_id)
                left join hc_resultados_manuales as g on(e.numero_orden_id=g.numero_orden_id)
                left join hc_resultados as h on ((h.tipo_id_paciente='".$this->datos_paciente[tipo_id_paciente]."'
                and h.paciente_id='".$this->datos_paciente[paciente_id]."') and
                (f.resultado_id=h.resultado_id or g.resultado_id=h.resultado_id))

                left join hc_apoyod_resultados_detalles
                as i on (h.resultado_id=i.resultado_id) left join hc_apoyod_lecturas_profesionales
                as j on (h.resultado_id=j.resultado_id), hc_evoluciones as b, ingresos as c
                WHERE a.evolucion_id=b.evolucion_id and b.ingreso=c.ingreso and
                c.tipo_id_paciente='".$this->datos_paciente[tipo_id_paciente]."' and c.paciente_id='".$this->datos_paciente[paciente_id]."'
                and j.evolucion_id = ".$this->evolucion_id[evolucion_id]." order  by a.os_tipo_solicitud_id;"; 
                $resultado = $dbconn->Execute($query);                
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else
                {
                  while (!$resultado->EOF)
                  {
                    $XML_Consulta[]=$resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                }
                $resultado->Close();
                $salida = $this->GetXML_Local($XML_Consulta);
                return $salida;
           
//                if ($dbconn->ErrorNo() != 0)
//                {
//                     $this->error = "Error al Cargar el Modulo";
//                     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                     return false;
//                }
//                while($motivo = $resultado->FetchRow())
//                {
//                	$XML_Consulta[] = $motivo;
//                }
//                $salida = $this->GetXML_Local($XML_Consulta);
// 								return $salida;
//                break;

               case '3':
							 //
							 //echo $Paramdatos[ingreso];
								$this->ConsultaOwner_Antecedentes($Paramdatos[evolucion], $Paramdatos[ingreso]);
								$this->ConsultaEvolucion($Paramdatos[ingreso]);

								$query = "SELECT h.sw_modo_resultado, a.hc_os_solicitud_id, a.cargo, a.os_tipo_solicitud_id,
								b.usuario_id, b.departamento, b.fecha, k.informacion, case when d.hc_os_solicitud_id is
								not null then '1' else '0' end as autorizado, case when e.sw_estado is null
								then '0' else e.sw_estado end as realizacion, case when f.resultado_id is null
								then '0' else f.resultado_id end as resultados_sistema, case when g.resultado_id
								is null then '0' else g.resultado_id end as resultado_manual, e.numero_orden_id,
								h.fecha_realizado, case when k.titulo_examen is not null then k.titulo_examen
								else l.descripcion end as titulo_examenes, i.*, j.sw_prof, j.sw_prof_dpto,
								j.sw_prof_todos FROM hc_os_solicitudes as a left join apoyod_cargos as k on
								(a.cargo = k.cargo) left join cups as l on (a.cargo=l.cargo) left join
								hc_os_autorizaciones as d on (a.hc_os_solicitud_id=d.hc_os_solicitud_id)
								left join os_maestro as e on (a.hc_os_solicitud_id=e.hc_os_solicitud_id)
								left join hc_resultados_sistema as f on(e.numero_orden_id=f.numero_orden_id)
								left join hc_resultados_manuales as g on(e.numero_orden_id=g.numero_orden_id)
								left join hc_resultados as h on ((h.tipo_id_paciente='".$this->datos_paciente[tipo_id_paciente]."'
								and h.paciente_id='".$this->datos_paciente[paciente_id]."') and
								(f.resultado_id=h.resultado_id or g.resultado_id=h.resultado_id))

								left join hc_apoyod_resultados_detalles
								as i on (h.resultado_id=i.resultado_id) left join hc_apoyod_lecturas_profesionales
								as j on (h.resultado_id=j.resultado_id), hc_evoluciones as b, ingresos as c
								WHERE a.evolucion_id=b.evolucion_id and b.ingreso=c.ingreso and
								c.tipo_id_paciente='".$this->datos_paciente[tipo_id_paciente]."' and c.paciente_id='".$this->datos_paciente[paciente_id]."'
								and j.evolucion_id = ".$this->evolucion_id[evolucion_id]." order  by a.os_tipo_solicitud_id";

								$resultado = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								else
								{
									while (!$resultado->EOF)
									{
										$XML_Consulta[]=$resultado->GetRowAssoc($ToUpper = false);
										$resultado->MoveNext();
									}
								}
								$resultado->Close();
								$salida = $this->GetXML_Local($XML_Consulta);
								return $salida;
								break;
/*               $resultado = $dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
              if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($motivo = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $motivo;
               }
								$salida = $this->GetXML_Local($XML_Consulta);
								return $salida;
								break;*/

               case '4':
               $sql="SELECT ingreso
               	 			FROM ingresos
                     WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
                     AND paciente_id='".$Paramdatos[paciente_id]."'
                     ORDER BY ingreso DESC;";
               $resulta = $dbconn->Execute($sql);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($data = $resulta->FetchRow())
               {
                    $ingreso[] = $data;
               }

               if(!empty($ingreso))
			{
				for($i=0; $i<sizeof($ingreso); $i++)
				{
                          $query="SELECT evolucion_id,
                                        descripcion,
                                        enfermedadactual,
                                        usuario_id,
                                        fecha_registro,
                                        ingreso
                              FROM hc_motivo_consulta
                              WHERE ingreso=".$ingreso[$i][0].";";
                         $resultado = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         while($motivo = $resultado->FetchRow())
                         {
                              $XML_Consulta[] = $motivo;
                         }
                    }
               }
                    
               $salida = $this->GetXML_Local($XML_Consulta);
							return $salida;
               break;

               case '5':
               $sql="SELECT ingreso
               	 FROM ingresos
                     WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
                     AND paciente_id='".$Paramdatos[paciente_id]."'
                     ORDER BY ingreso DESC;";
               $resulta = $dbconn->Execute($sql);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($data = $resulta->FetchRow())
               {
                    $ingreso[] = $data;
               }

			if(!empty($ingreso))
			{
				for($i=0; $i<sizeof($ingreso); $i++)
				{
                          $query="SELECT evolucion_id,
                                        descripcion,
                                        enfermedadactual,
                                        usuario_id,
                                        fecha_registro,
                                        ingreso
                              FROM hc_motivo_consulta
                              WHERE ingreso=".$ingreso[$i][0].";";
                         $resultado = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         while($motivo = $resultado->FetchRow())
                         {
                              $XML_Consulta[] = $motivo;
                         }
                    }
               }
                    
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               default:
               return false;                        
           }

    }

 /*		FechaStamp
 *
 *		Convierte los datos en Fechas a partir de la Fecha Registro.
 *
 *		@Author Alexander Giraldo.
 *		@access Public
 *		@param integer => fecha_registro
 */
	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}

 /*		HoraStamp
 *
 *		Convierte los datos en Horas a partir de la Fecha Registro.
 *
 *		@Author Alexander Giraldo.
 *		@access Public
 *		@param integer => fecha_registro
 */
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
     
	 /*		GetDatosUsuarioSistema
	 *
	 *		Obtiene el nombre de usuario del sistema
	 *
	 *		@Author Tizziano Perea O.
	 *		@access Public
	 *		@return bool
	 *		@param integer => usuario_id
	 */
	 function GetDatosUsuarioSistema($usuario)
	 {
				$pfj=$this->frmPrefijo;
				$query = "SELECT usuario,
									nombre
									FROM system_usuarios
									WHERE usuario_id = $usuario";

				GLOBAL $ADODB_FETCH_MODE;
				list($dbconn) = GetDBconn();
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if ($dbconn->ErrorNo() != 0)
				{
						 $this->error = "Error al ejecutar la conexion";
						 $this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						 return false;
				}
				else
				{
						 if($result->EOF){
									return "ShowMensaje";
						 }
						 else
						 {
									while ($data = $result->FetchRow()){
											 $DatosUser[] = $data;
									}
									return $DatosUser;
						 }
				}
	 }/// GetDatosUsuarioSistema

	//DETALLE DE LA CONSULTA
	function ConsultaDetalle($resultado_id)
	{
		list($dbconnect) = GetDBconn();
		$query=   "SELECT DISTINCT
								a.lab_examen_id, a.resultado_id, a.resultado,	a.sw_alerta,
								a.rango_max, a.rango_min, a.unidades,
								b.lab_plantilla_id, b.nombre_examen
								FROM hc_apoyod_resultados_detalles a, lab_examenes b
								WHERE  a.resultado_id = ".$resultado_id." AND a.lab_examen_id=b.lab_examen_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar los resultados de los examenes";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
		}
		$result->Close();
		return $fact;
	}

	 /*		GetConsultaSubmodulo
	 *
	 *		Realiza la consulta de datos a partir de parametros como los datos
	 *		del paciente y el tipo de impresion a realizar.
	 *
	 *		@Author Tizziano Perea O.
	 *		@access Public
	 *		@param integer => ingreso, evolucion_id, paciente_id, tipoidpaciente.
	 */
	function ConsultaOwner_Antecedentes($evolucion, $ingreso)
	{
				GLOBAL $ADODB_FETCH_MODE;
				list($dbconn) = GetDBconn();

				if(!empty ($evolucion))
				{
						 $query = "SELECT A.tipo_id_paciente, A.paciente_id
											 FROM ingresos AS A, hc_evoluciones AS B
											 WHERE B.evolucion_id = $evolucion
											 AND B.ingreso = A.ingreso;";
				}
				elseif(!empty ($ingreso))
				{
						 $query = "SELECT tipo_id_paciente, paciente_id
											 FROM ingresos
											 WHERE ingreso = $ingreso;";
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if ($dbconn->ErrorNo() != 0)
				{
						 $this->error = "Error al Cargar el Modulo";
						 $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						 return false;
				}
				while($data = $resultado->FetchRow())
				{
						 $this->datos_paciente = $data;
				}
				return true;
	}

//FUNCION QUE RETORNA LA EVOLUCION SI NO EXISTE PERO EL INGRESO SI
	function ConsultaEvolucion($ingreso)
	{
				GLOBAL $ADODB_FETCH_MODE;
				list($dbconn) = GetDBconn();

				if(!empty ($ingreso))
				{
						 $query = "SELECT MAX(evolucion_id) AS evolucion_id
											 FROM hc_evoluciones
											 WHERE ingreso = $ingreso AND estado=0;";
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if ($dbconn->ErrorNo() != 0)
				{
						 $this->error = "Error al Cargar el Modulo";
						 $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						 return false;
				}
				while($data = $resultado->FetchRow())
				{
						 $this->evolucion_id = $data;
				}
				return true;
	}

//CONSULTA LOS DATOS DEL MEDICO
function ConsultaNombreMedico($usuario_id_evolucion)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= " SELECT d.nombre_tercero, c.descripcion
							FROM profesionales_usuarios a, profesionales b, tipos_profesionales c,
							terceros d
							WHERE a.tipo_tercero_id = b.tipo_id_tercero AND
							a.tercero_id = b.tercero_id AND
							a.tipo_tercero_id = d.tipo_id_tercero AND
							a.tercero_id = d.tercero_id AND
							a.usuario_id = ".$usuario_id_evolucion." AND
							b.tipo_profesional = c.tipo_profesional";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al buscar el nombre del profesional";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
}

//ad - esta funcion se llama para la consulta general
function ConsultaResultadosNoSolicitadosLeidos()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query = "SELECT b.cargo, b.fecha_realizado, b.resultado_id,
		c.titulo_examen, d.sw_prof,    d.sw_prof_dpto,d.sw_prof_todos,
		d.evolucion_id, e.fecha    FROM hc_resultados_nosolicitados as a
		left join hc_resultados as b on (a.resultado_id = b.resultado_id)
		left join apoyod_cargos as c    on (b.cargo = c.cargo) left join
		hc_apoyod_lecturas_profesionales as d on (b.resultado_id = d.resultado_id)
		left join hc_evoluciones as e on (d.evolucion_id = e.evolucion_id)
		WHERE b.tipo_id_paciente = '".$this->datos_paciente[tipo_id_paciente]."'
		AND b.paciente_id = '".$this->datos_paciente[paciente_id]."'
		AND d.evolucion_id = ".$this->evolucion_id[evolucion_id]."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta general de apoyos no solictados";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

	function GetPlantillaApoyoDiagnostico($resultado_id, $sw_modo_resultado, $evolucion_id, $accion_observacion)
	{
    $salida.= '';
    $salida=$this->Plantilla_Apoyos($resultado_id, $sw_modo_resultado, $evolucion_id, $accion_observacion);
    return $salida;
	}

//inicio función plantillas
	function Plantilla_Apoyos($resultado_id, $sw_modo_resultado, $evolucion_id, $accion_observacion)
	{
			//$pfj=$this->frmPrefijo;
			$examenes = $this->ConsultaExamenesPaciente($resultado_id, $sw_modo_resultado);

			//verificacion de lecturas
			$registro = $this->RegistroLecturas($resultado_id);

			$prof = 0;
			for($k=0;$k<sizeof($registro);$k++)
			{
					if ($registro[$k][sw_prof] == '1'){	$prof = 1;}
			}
			//fin de verificacion

			$salida.="<TABLE  align=\"center\" border=\"1\"  width=\"100%\">";
      $salida.="<COLGROUP align=\"center\">";
      $salida.="<COLGROUP align=\"left\">";
			$salida.="<TR>";
			$salida.="<TH align=\"center\" colspan=\"1\" width=\"84%\">".$examenes[descripcion]."</TH>";

			//opciones de observacion por examen, e informacion del examen
			$salida.="<TH align=\"center\" colspan=\"1\" width=\"8%\">INFO.<input type='image' name='submit' src='".GetThemePath()."/images/EstacionEnfermeria/info.png' border='0' title='Laboratorio: $examenes[laboratorio]\nRealizado: $examenes[fecha_realizado]\nProfesional: $examenes[profesional]'></TH>";
			if ($evolucion_id != '')
			{
				if ($prof == 1)
				{
						$salida.="<TH align=\"center\" colspan=\"1\" width=\"8%\"><a href='$accion_observacion'>OBS.<img src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0' ></a></TH>";
				}
				else
				{
						$salida.="<TH align=\"center\" colspan=\"1\" width=\"8%\"><a href='$accion_observacion'>OBS.<img src=\"".GetThemePath()."/images/EstacionEnfermeria/edita.png\" border='0' ></a></TH>";
				}
			}
			else
			{
					$salida.="<TH align=\"center\" colspan=\"1\" width=\"8%\">OBS.<img src=\"".GetThemePath()."/images/EstacionEnfermeria/edita.png\" border='0' ></TH>";
			}
			//fin de observaciones.
			$salida.="</TR>";
			$salida.="</TABLE>";

			$vector = $this->ConsultaDetalle($resultado_id);

			if($vector)
			{
					$salida.="<TABLE  align=\"center\" border=\"1\"  width=\"100%\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"left\">";

					for($i=0;$i<sizeof($vector);$i++)
					{ 
						switch ($vector[$i][lab_plantilla_id])
						{
								case "1": {
															$salida.="<TR class=\"hc_table_submodulo_list_title\">";
															$salida.="<TH width=\"25%\">EXAMEN</TH>";
															$salida.="<TH width=\"55%\">RESULTADO</TH>";
															$salida.="<TH width=\"20%\">RANGO NORMAL</TH>";
															$salida.="</TR>";

															$salida.="<TR class=\"modulo_list_claro\">";
															$salida.="<TH align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</TH>";
															if ($vector[$i][sw_alerta] == '1')
															{
																	$salida.="<TH class=label_error align=\"center\">".$vector[$i][resultado]." ".$vector[$i][unidades]."</TH>";
															}
															else
															{
																	$salida.="<TH align=\"center\">".$vector[$i][resultado]." ".$vector[$i][unidades]."</TH>";
															}
															$salida.="<TH align=\"center\" >".$vector[$i][rango_min]." - ".$vector[$i][rango_max]." ".$vector[$i][unidades]."</TH>";
															$salida.="</TR>";
															break;
													}

								case "2": {
															$salida.="<TR class=\"hc_table_submodulo_list_title\">";
															$salida.="<TH width=\"25%\">EXAMEN</TH>";
															$salida.="<TH width=\"55%\">RESULTADO</TH>";
															$salida.="<TH width=\"20%\">RANGO NORMAL</TH>";
															$salida.="</TR>";
															$salida.="<TR class=\"modulo_list_claro\">";
															$salida.="<TH align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</TH>";
															$salida.="<TH align=\"center\" >".$vector[$i][resultado]."</TH>";
															$salida.="<TH align=\"center\">&nbsp;</TH>";
															$salida.="</TR>";
															break;
													}

								case "3": {
															$salida.="<TR>";
															$salida.="  <TH width=\"25%\">EXAMEN</TH>";
															$salida.="  <TH width=\"75%\">RESULTADO</TH>";
															$salida.="</TR>";
															$salida.="<TR>";
															$salida.="  <TH  align=\"center\" width=\"25%\">".strtoupper($vector[$i][nombre_examen])."</TH>";
															$vector[$i][resultado]=str_replace("\x0a","<p></p>",$vector[$i][resultado]);
															$salida.="  <TH  align=\"justify\" width=\"75%\">".$vector[$i][resultado]."</TH>";
															$salida.="</TR>";
															return $salida;
															break;
													}

								case "0": {
															$salida.="<tr class=\"hc_table_submodulo_list_title\">";
															$salida.="<td width=\"25%\" colspan=\"1\">EXAMEN</td>";
															$salida.="<td width=\"75%\" colspan=\"2\">RESULTADO</td>";
															$salida.="</tr>";
															$salida.="<tr class=\"$estilo\">";
															$salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
															$salida.="<td align=\"center\" colspan=\"2\">".$vector[$i][resultado]."</td>";
															$salida.="</tr>";
															break;
													}
							}//cierra el switche
					}//cierra el for

					$observaciones = $this->ConsultaObservaciones($resultado_id);
					if ($examenes[informacion]!= '' OR $examenes[observacion_prestacion_servicio]!= ''
					OR (!empty($observaciones)) OR (sizeof($examenes[observaciones_adicionales])>=1))
					{
							$salida.="<TR class=\"$estilo\">";
							$salida.="<TH colspan=\"3\">";
							$salida.="<TABLE  align=\"center\" border=\"0\"  width=\"100%\">";
              $salida.="<COLGROUP align=\"center\">";
              $salida.="<COLGROUP align=\"left\">";

							if ($examenes[informacion])
							{
								$salida.="<TR class=\"modulo_list_claro\" >";
								$salida.="<TH colspan=\"1\" width=\"25%\" align=\"left\">INFORMACION: </TH>";
								$salida.="<TH colspan=\"2\" width=\"75%\" align=\"left\"><FONT size='1'>".$examenes[informacion]."</FONT></TH>";
								$salida.="</TR>";
							}

							if ($examenes[observacion_prestacion_servicio])
							{
								$salida.="<TR class=\"modulo_list_claro\" >";
								$salida.="<TH colspan=\"1\" width=\"25%\" align=\"left\">OBSERVACION</TH>";
								$salida.="<TH colspan=\"2\" width=\"75%\" align=\"left\">".$examenes[observacion_prestacion_servicio]."</TH>";
								$salida.="</TR>";
							}

							//listado de las observaciones adicionales al resultado
							if(sizeof($examenes[observaciones_adicionales])>=1)
							{
									$salida.="<TR class=\"modulo_list_claro\" >";
									$salida.="<TH align=\"left\" colspan=\"1\" width=\"25%\" >OBSERVACIONES ADICIONALES REALIZADAS AL RESULTADO</TH>";
									$salida.="<TH align=\"left\" colspan=\"2\" width=\"75%\" class=\"modulo_list_oscuro\">";
									$salida.="<TABLE align=\"center\" border=\"0\" width=\"100%\">";
                  $salida.="<COLGROUP align=\"center\">";
                  $salida.="<COLGROUP align=\"left\">";
									$salida.="<TR>";
									$salida.="<TH align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"5%\">No.</TH>";
									$salida.="<TH align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"10%\">REGISTRO</TH>";
									$salida.="<TH align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"30%\">PROFESIONAL</TH>";
									$salida.="<TH align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"55%\">OBSERVACION ADICIONAL AL RESULTADO</TH>";
									$salida.="</TR>";
									for($i=0;$i<sizeof($examenes[observaciones_adicionales]);$i++)
									{
											if( $i % 2)    {$estilo='modulo_list_claro';}
											else{$estilo='modulo_list_oscuro';}
											$salida.="<TR>";
											$salida.="<TH align=\"center\" class=\"$estilo\" >".($i+1)."</TH>";
											$salida.="<TH align=\"center\" class=\"$estilo\" >".$this->FechaStampMostrar($examenes[observaciones_adicionales][$i][fecha_registro_observacion])." - ".$this->HoraStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])."</TH>";
											$salida.="<TH align=\"center\" class=\"$estilo\" >".$examenes[observaciones_adicionales][$i][usuario_observacion]."</TH>";
											$salida.="<TH align=\"left\" class=\"$estilo\" >".$examenes[observaciones_adicionales][$i][observacion_adicional]."</TH>";
											$salida.="</TR>";
									}
									$salida.="</TABLE>";
									$salida.="</TH>";
									$salida.="</TR>";
							}
							//fin de las observaciones adicionales

							if ($observaciones)
							{
								$salida.="<TR class=\"modulo_list_claro\" >";
								//$salida.="<td colspan=\"1\" align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"25%\">OBSERVACIONES MEDICAS</td>";
								$salida.="<TH colspan=\"1\" width=\"25%\" align=\"left\">OBSERVACIONES MEDICAS</TH>";
								$salida.="<TH colspan=\"2\" align=\"left\" width=\"75%\">";
								$salida.="<TABLE  align=\"center\" border=\"0\"  width=\"100%\">";
		            $salida.="<COLGROUP align=\"center\">";
                $salida.="<COLGROUP align=\"left\">";
      					for($i=0;$i<sizeof($observaciones);$i++)
								{
										$salida.="<TR>";
										$salida.="<TH align=\"left\" class=\"hc_table_submodulo_list_title\" >".$observaciones[$i][descripcion]." - ".$observaciones[$i][nombre]."</TH>";
										$salida.="</TR>";

										$salida.="<TR>";
										$salida.="<TH align=\"left\"class=\"$estilo\" >".$observaciones[$i][observacion_prof]."</TH>";
										$salida.="</TR>";
								}
								$salida.="</TABLE>";
								$salida.="</TH>";
								$salida.="</TR>";
							}


							$salida.="</TABLE>";
							$salida.="</TH>";
							$salida.="</TR>";
					}
					$salida.="</TABLE>";
			}
	}//fin de la funcion Plantilla_Apoyos

	function ConsultaExamenesPaciente($resultado_id, $sw_modo_resultado)
{
		list($dbconnect) = GetDBconn();

		//esta consulta la referencia a los examens en resultado manual, en resultados
		//no solicitados y en resultados sistema.
    $query = '';
		if ($sw_modo_resultado == '1')
		{
				$query="  SELECT b.numero_orden_id, a.resultado_id, a.fecha_realizado,
				          a.observacion_prestacion_servicio,
									i.nombre_tercero as profesional, case when f.razon_social is not null then
									f.razon_social else k.nombre_tercero end as laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_sistema as b, profesionales_usuarios as g,
									profesionales as h, terceros as i, os_maestro as c left join os_internas as d on
									(c.numero_orden_id=d.numero_orden_id) left join departamentos as e on
									(d.departamento=e.departamento) left join empresas as f on(e.empresa_id=f.empresa_id)
									left join os_externas as j on(c.numero_orden_id=j.numero_orden_id) left join
									terceros as k on(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id)

									, cups l,	apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id." and
									b.numero_orden_id=c.numero_orden_id and b.usuario_id_profesional=g.usuario_id and
									g.tipo_tercero_id=h.tipo_id_tercero and g.tercero_id=h.tercero_id and
									h.tipo_id_tercero=i.tipo_id_tercero and h.tercero_id=i.tercero_id

									and c.cargo_cups = l.cargo and l.cargo = m.cargo
									;";

		}
		elseif ($sw_modo_resultado == '2')
		{
				$query="  SELECT b.numero_orden_id, a.resultado_id, a.fecha_realizado,
				          a.observacion_prestacion_servicio,
									b.profesional, case when f.razon_social is not null then f.razon_social else
									k.nombre_tercero end as laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_manuales as b,    os_maestro as c
									left join os_internas as d on(c.numero_orden_id=d.numero_orden_id) left join
									departamentos as e on(d.departamento=e.departamento) left join empresas as f
									on(e.empresa_id=f.empresa_id) left join os_externas as j on
									(c.numero_orden_id=j.numero_orden_id)    left join terceros as k on
									(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id)

									, cups l,	apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."
									and b.numero_orden_id=c.numero_orden_id

                  and c.cargo_cups = l.cargo and l.cargo = m.cargo
									;";
		}
		elseif ($sw_modo_resultado == '3')
		{
				$query="  SELECT a.resultado_id, a.fecha_realizado,
									a.observacion_prestacion_servicio, b.profesional, b.laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_nosolicitados as b

									, cups l,	apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."

									and a.cargo = l.cargo and l.cargo = m.cargo

									;";
		}

		if ($query !='')
		{
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
						{
								$this->error = "Error al Consultar los datos del examen";
								$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
								return false;
				}
				$a=$result->GetRowAssoc($ToUpper = false);

				//cargando las observaciones adicionales
				$query="SELECT a.resultado_id, a.observacion_adicional,
				a.fecha_registro_observacion, c.nombre_tercero as usuario_observacion
				FROM hc_resultados_observaciones_adicionales as a,
				profesionales_usuarios as b, terceros as c
				WHERE resultado_id = ".$resultado_id." AND
				a.usuario_id = b.usuario_id
				and b.tipo_tercero_id = c.tipo_id_tercero and b.tercero_id = c.tercero_id
				order by a.observacion_resultado_id";

				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al consultar las observaciones adicionales al resultado del apoyo";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				else
				{ 
					while (!$result->EOF)
					{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
				}
				$a[observaciones_adicionales]=$vector;
			//fin de las observaciones adicionales
				$result->Close();
				return $a;
		}
		else
		{
        return false;
		}
}

//ad*
//esta funcion busca en la tabla hc_lecturas_profesionales el registro de las lecturas
// realizadas para cada resultado_id
function RegistroLecturas($resultado_id)
{
		list($dbconnect) = GetDBconn();
		$query = "select resultado_id, sw_prof, sw_prof_dpto, sw_prof_todos, evolucion_id
		from hc_apoyod_lecturas_profesionales where resultado_id = ".$resultado_id."
		order by resultado_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta de lecturas profesionales";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$fact[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $fact;
}
//pendiente borrarlo en el origen
function ConsultaObservaciones($resultado_id)
{
		list($dbconnect) = GetDBconn();
		$query =" SELECT a.resultado_id, a.evolucion_id, a.observacion_prof, d.nombre, e.descripcion
							FROM hc_apoyod_lecturas_profesionales as a, hc_evoluciones as b,
							profesionales_usuarios as c, profesionales d, tipos_profesionales e
							WHERE a.resultado_id = ".$resultado_id." AND a.evolucion_id = b.evolucion_id
							AND b.usuario_id = c.usuario_id AND c.tipo_tercero_id = d.tipo_id_tercero
							AND    c.tercero_id = d.tercero_id AND d.tipo_profesional = e.tipo_profesional";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar las observaciones realizadas al Examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

}//fin de la clase

?>
