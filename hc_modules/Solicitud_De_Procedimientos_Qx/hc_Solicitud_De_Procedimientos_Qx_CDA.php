<?php

/**
* $Id: hc_Solicitud_De_Procedimientos_Qx_CDA.php,v 1.1 2005/06/29 18:18:11 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo Solicitud_De_Procedimientos_Qx
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class Solicitud_De_Procedimientos_Qx_CDA extends Extenciones_CDA_HC
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
    function Solicitud_De_Procedimientos_Qx_CDA()
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
    {
          $salida.="<caption>SOLICITUD DE PROCEDIMIENTOS QUIRURGICOS</caption>";
          $xx = 0;
		foreach($XML_Consulta as $k => $v)
          {
               $salida.="<TABLE border=\"1\" width=\"100%\">";
               if($xx != 1)
               {
                    $xx = 1;
                    $salida.="<COLGROUP align=\"center\">";
                    $salida.="<COLGROUP align=\"center\">";
                    $salida.="<COLGROUP align=\"center\">";
                    $salida.="<COLGROUP align=\"center\">";
                    
                    
                    $salida.="<THEAD valign=\"top\">";
                    $salida.="<TR>";
                    $salida.="<TH>TIPO</TH>";
                    $salida.="<TH>CARGO</TH>";
                    $salida.="<TH>DESCRIPCION</TH>";
                    $salida.="<TH>PRINCIPAL</TH>";
                    $salida.="</TR>";
                    $salida.="</THEAD>";
               }
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD width=\"30%\" align=\"center\">".$v[tipo]."</TD>";
               $salida.="<TD width=\"10%\" align=\"center\">".$v[cargo]."</TD>";
               $salida.="<TD width=\"50%\" align=\"justify\">".$v[descripcion]."</TD>";
               if ($v[principal] == 1)
               {
               	$salida.="<TD width=\"10%\" align=\"center\"> X </TD>";
               }
               else
               {
               	$salida.="<TD width=\"10%\" align=\"center\">&nbsp;</TD>";
               }
               $salida.="</TR>";
               $salida.="</TBODY>";
                 
               /*******************PROCEDIMIENTOS QUIRURGICOS ADICIONALES**************************/
               $sol_Alternas = $this->Solicitudes_Alternas($v[hc_os_solicitud_id]);
               if(!empty($sol_Alternas))
               {
                    foreach ($sol_Alternas as $key => $v2)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"30%\" align=\"center\">".$v2[tipo]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v2[procedimiento_id]."</TD>";
                         $salida.="<TD width=\"50%\" align=\"justify\">".$v2[descripcion]."</TD>";
                         if ($v2[principal] == 1)
                         {
                              $salida.="<TD width=\"10%\" align=\"center\"> X </TD>";
                         }
                         else
                         {
                              $salida.="<TD width=\"10%\" align=\"center\">&nbsp;</TD>";
                         }
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /*******************PROCEDIMIENTOS QUIRURGICOS ADICIONALES**************************/
               /**************************OBSERVACIONES DEL PROCEDIMIENTO**************************/
               $datos_Sol = $this->Datos_Procedimiento($v[hc_os_solicitud_id]);
               if(!empty($datos_Sol))
               {
                    foreach ($datos_Sol as $key2 => $v3)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"50%\" align=\"justify\" colspan=\"2\"><b>AMBITO: </b>".$v3[ambito]."</TD>";
                         $salida.="<TD width=\"50%\" align=\"justify\" colspan=\"2\"><b>FINALIDAD: </b>".$v3[finalidad_qx]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                         
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"50%\" align=\"justify\" colspan=\"2\"><b>TIPO CIRUGIA: </b>".$v3[tipo_qx]."</TD>";
					$salida.="<TD width=\"50%\" align=\"justify\" colspan=\"2\"><b>OBSERVACION: </b>".$v3[observacion]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
			/**************************OBSERVACIONES DEL PROCEDIMIENTO**************************/
               /**************************************DIAGNOSTICOS*********************************/
               $diag =$this->Diagnosticos_Solicitados($v[hc_os_solicitud_id]);
               if(!empty($diag))
               {
               	foreach ($diag as $key0 => $v0)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\">CODIGO</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\" colspan=\"3\">DESCRIPCION DIAGNOSTICOS</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v0[diagnostico_id]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"justify\" colspan=\"3\">".$v0[diagnostico_nombre]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /**************************************DIAGNOSTICOS*********************************/
               /*******************************APOYOS DIAGNOSTICOS QX******************************/
               $sol_Apoyos = $this->Busqueda_Apoyos($v[hc_os_solicitud_id]);
               if(!empty($sol_Apoyos))
               {
                    foreach ($sol_Apoyos as $key3 => $v4)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\">CARGO</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\">CANTIDAD</TH>";
                         $salida.="<TH width=\"70%\" align=\"center\" colspan=\"2\">DESCRIPCION APOYO DX</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v4[cargo]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v4[cantidad]."</TD>";
                         $salida.="<TD width=\"70%\" align=\"justify\" colspan=\"2\">".$v4[descripcion]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /*******************************APOYOS DIAGNOSTICOS QX******************************/
               /*******************************MATERIALES QX SOLICITADOS***************************/
               $sol_Materiales = $this->Busqueda_Materiales($v[hc_os_solicitud_id]);
               if(!empty($sol_Materiales))
               {
                    foreach ($sol_Materiales as $key4 => $v5)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\">CODIGO</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\">CANTIDAD</TH>";
                         $salida.="<TH width=\"70%\" align=\"center\" colspan=\"2\">DESCRIPCION MATERIAL</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v5[codigo_producto]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v5[cantidad]."</TD>";
                         $salida.="<TD width=\"70%\" align=\"justify\" colspan=\"2\">".$v5[descripcion]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /*******************************MATERIALES QX SOLICITADOS***************************/
               /*******************************EQUIPOS QX SOLICITADOS*****************************/
               $sol_Equipos = $this->Busqueda_EquiposQX($v[hc_os_solicitud_id]);
               if(!empty($sol_Equipos))
               {
                    foreach ($sol_Equipos as $key5 => $v6)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\">TIPO</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\">CANTIDAD</TH>";
                         $salida.="<TH width=\"70%\" align=\"center\" colspan=\"2\">DESCRIPCION EQUIPO QX</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v6[tipo]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v6[cantidad]."</TD>";
                         $salida.="<TD width=\"70%\" align=\"justify\" colspan=\"2\">".$v6[descripcion]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /*******************************EQUIPOS QX SOLICITADOS*****************************/
	          /*******************************ESTANCIA QX SOLICITADOS*****************************/
               $sol_Estancia = $this->Busqueda_EstanciaQX($v[hc_os_solicitud_id]);
               if(!empty($sol_Estancia))
               {
                    foreach ($sol_Estancia as $key6 => $v7)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\">PRE QX</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\">POS QX</TH>";
                         $salida.="<TH width=\"70%\" align=\"center\">TIPO CLASE CAMA</TH>";
                         $salida.="<TH width=\"70%\" align=\"center\">DIAS</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v7[sw_pre_qx]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v7[sw_pos_qx]."</TD>";
                         $salida.="<TD width=\"70%\" align=\"justify\">".$v7[descripcion]."</TD>";
                         $salida.="<TD width=\"70%\" align=\"center\">".$v7[cantidad_dias]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /*******************************ESTANCIA QX SOLICITADOS*****************************/
	          /********************************COMPONENTES SANGUINEOS*****************************/
               $sol_Componente = $this->Busqueda_Componente($v[hc_os_solicitud_id]);
               if(!empty($sol_Componente))
               {
               	$D = 0;
                    foreach ($sol_Componente as $key7 => $v8)
                    {
                         if($D != 1)
                         {
                              $D = 1;
                              $salida.="<TBODY>";
                              $salida.="<TR>";
                              $salida.="<TH width=\"70%\" align=\"center\" colspan=\"4\">COMPONENTES SANGUINEOS</TH>";
                              $salida.="</TR>";
                              $salida.="</TBODY>";
                         }
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\" colspan=\"3\">COMPONENTE</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\">CANTIDAD</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"justify\" colspan=\"3\">".$v8[componente]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v8[cantidad_componente]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
	          /********************************COMPONENTES SANGUINEOS*****************************/
               /*******************************APOYO COMPONENTES SANGUINEOS************************/
               $sol_Apoyo_Componente = $this->Busqueda_Apoyo_Componente($v[hc_os_solicitud_id]);
               if(!empty($sol_Apoyo_Componente))
               {
               	foreach ($sol_Apoyo_Componente as $key8 => $v9)
                    {
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TH width=\"10%\" align=\"center\">CARGO</TH>";
                         $salida.="<TH width=\"10%\" align=\"center\" colspan=\"3\">DESCRIPCION APOYO COMPONENTE</TH>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
    
                         $salida.="<TBODY>";
                         $salida.="<TR>";
                         $salida.="<TD width=\"10%\" align=\"center\">".$v9[cargo]."</TD>";
                         $salida.="<TD width=\"10%\" align=\"justify\" colspan=\"3\">".$v9[descripcion]."</TD>";
                         $salida.="</TR>";
                         $salida.="</TBODY>";
                    }
			}
               /*******************************APOYO COMPONENTES SANGUINEOS************************/
               $salida.="</TABLE>";
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

          switch($ParamTipo)
          {
               case '1':
               $query="SELECT a.hc_os_solicitud_id, a.cargo, b.descripcion,
               			c.descripcion as tipo, '1' AS principal
                       FROM hc_os_solicitudes a, cups b, grupos_tipos_cargo c
                       WHERE a.evolucion_id=".$Paramdatos[evolucion]."
                       AND a.cargo=b.cargo 
                       AND b.grupo_tipo_cargo=c.grupo_tipo_cargo";

               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($sol_qx = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $sol_qx;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $Plan = $this->BuscarPlan('',$Paramdatos[ingreso]);
               $criterio='';
			if(!empty($Plan)){
                    $criterio = ",informacion_cargo('".$Plan[plan_id]."',a.cargo,'".$Plan[departamento]."')";
			}

              $query="SELECT d.evolucion_id, d.ingreso, a.cargo, a.cantidad,
               			a.hc_os_solicitud_id, b.descripcion, d.fecha,
                              e.observacion, h.descripcion as tipo, b.sw_cantidad
               			$criterio
                    
                        FROM hc_os_solicitudes a, hc_os_solicitudes_no_quirurgicos e,
                             cups b, hc_evoluciones d, grupos_tipos_cargo h
                        WHERE d.ingreso =".$Paramdatos[ingreso]."
                        AND a.evolucion_id = d.evolucion_id
                        AND a.cargo = b.cargo 
                        AND b.grupo_tipo_cargo = h.grupo_tipo_cargo
                        AND a.hc_os_solicitud_id = e.hc_os_solicitud_id
                        ORDER BY a.hc_os_solicitud_id";

               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($interconsulta = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $interconsulta;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $Plan = $this->BuscarPlan('',$Paramdatos[ingreso]);
               $criterio='';
			if(!empty($Plan)){
                    $criterio = ",informacion_cargo('".$Plan[plan_id]."',a.cargo,'".$Plan[departamento]."')";
			}

              $query="SELECT d.evolucion_id, d.ingreso, a.cargo, a.cantidad,
               			a.hc_os_solicitud_id, b.descripcion, d.fecha,
                              e.observacion, h.descripcion as tipo, b.sw_cantidad
               			$criterio
                    
                        FROM hc_os_solicitudes a, hc_os_solicitudes_no_quirurgicos e,
                             cups b, hc_evoluciones d, grupos_tipos_cargo h
                        WHERE d.ingreso =".$Paramdatos[ingreso]."
                        AND a.evolucion_id = d.evolucion_id
                        AND a.cargo = b.cargo 
                        AND b.grupo_tipo_cargo = h.grupo_tipo_cargo
                        AND a.hc_os_solicitud_id = e.hc_os_solicitud_id
                        ORDER BY a.hc_os_solicitud_id";

               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($interconsulta = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $interconsulta;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '4':
/*               $sql="SELECT ingreso
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
                    
               $salida = $this->GetXML_Local($XML_Consulta);*/
			return true;
               break;

               case '5':
/*               $sql="SELECT ingreso
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
                    
               $salida = $this->GetXML_Local($XML_Consulta);*/
			return true;
               break;

               default:
               return false;                        
           }
    }
    
    //clzc - si
	function Diagnosticos_Solicitados($hc_os_solicitud_id)
	{
          list($dbconnect) = GetDBconn();
          $query= "select a.diagnostico_id, a.diagnostico_nombre
          FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
          WHERE b.hc_os_solicitud_id = ".$hc_os_solicitud_id." AND a.diagnostico_id = b.diagnostico_id";

          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los diagnosticos asignados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { $i=0;
               while (!$result->EOF)
               {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
               }
          }
          $result->Close();
	     return $vector;
	}

	
     function Solicitudes_Alternas($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT a.procedimiento_id, b.descripcion, 
          			 c.descripcion as tipo
                    FROM hc_os_solicitudes_otros_procedimientos_qx a, 
                    	cups b, grupos_tipos_cargo c
                    WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."' 
                    AND a.procedimiento_id=b.cargo 
                    AND b.grupo_tipo_cargo=c.grupo_tipo_cargo";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Otras Solicitudes Asignadas";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }
     
     function Datos_Procedimiento($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT a.observacion, a.tipo_cirugia_id,
          			 a.ambito_cirugia_id, a.finalidad_procedimiento_id,
                          a.nivel_autorizacion, a.fecha_tentativa_cirugia,
                          b.descripcion AS ambito,
                          c.descripcion AS finalidad_qx,
                          d.descripcion AS tipo_qx
                          
				FROM hc_os_solicitudes_datos_acto_qx AS a, qx_ambitos_cirugias AS b,
                    	qx_finalidades_procedimientos AS c, qx_tipos_cirugia AS d
				
                    WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."'
                    AND a.ambito_cirugia_id = b.ambito_cirugia_id
                    AND a.finalidad_procedimiento_id = c.finalidad_procedimiento_id
                    AND a.tipo_cirugia_id = d.tipo_cirugia_id;";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }


     function Busqueda_Apoyos($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT a.cargo, b.descripcion,
          			d.grupo_tipo_cargo as tipo, a.cantidad
			   FROM hc_os_solicitudes_procedimientos_apoyos a, cups b, 
                       hc_os_solicitudes_qx_grupos_cargos c, grupos_tipos_cargo d
                  WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."'
                  AND a.cargo = b.cargo 
                  AND b.grupo_tipo_cargo = c.grupo_tipo_cargo 
                  AND c.grupo_tipo_cargo = d.grupo_tipo_cargo;";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }
     
     
     function Busqueda_Materiales($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT a.codigo_producto, b.descripcion,
          			a.cantidad
			   FROM hc_os_solicitudes_otros_productos_inv a, 
                       inventarios_productos b
			   WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."' 
                  AND a.codigo_producto=b.codigo_producto;";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }

     
     function Busqueda_EquiposQX($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT 'FIJO' as tipo, a.tipo_equipo_fijo_id as codigo, 
          			b.descripcion,a.cantidad
          	   FROM hc_os_solicitudes_requerimientos_equipo_quirofano a,
                       qx_tipo_equipo_fijo b
                  WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."' 
                  AND a.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id
                  
                  UNION
                  
                  SELECT 'MOVIL' as tipo, a.tipo_equipo_id as codigo,
		               b.descripcion, a.cantidad
	             FROM hc_os_solicitudes_requerimientos_equipos_moviles a,
                       qx_tipo_equipo_movil b
                  WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."' 
                  AND a.tipo_equipo_id=b.tipo_equipo_id;";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }
     
     
     function Busqueda_EstanciaQX($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT a.tipo_clase_cama_id, b.descripcion, a.sw_pre_qx,
          			a.sw_pos_qx, a.cantidad_dias
          	   FROM hc_os_solicitudes_estancia a, tipos_clases_camas b
                  WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."'
                  AND a.tipo_clase_cama_id=b.tipo_clase_cama_id";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }
 
         
     function Busqueda_Componente($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT b.tipo_componente_id, b.cantidad_componente, 
          			c.componente
                  FROM banco_sangre_reserva_hc a, banco_sangre_reserva_detalle b,
                       hc_tipos_componentes c
                  WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."' 
                  AND a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id 
                  AND b.sw_estado='1' 
                  AND b.tipo_componente_id=c.hc_tipo_componente;";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }

     
     function Busqueda_Apoyo_Componente($hc_os_solicitud_id)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT b.cargo, c.descripcion
          	   FROM banco_sangre_reserva_hc a,banco_sangre_reserva_otros_servicios b,
                       cups c
                  WHERE a.hc_os_solicitud_id='".$hc_os_solicitud_id."' 
                  AND a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id 
                  AND b.cargo=c.cargo;";
          
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los Datos Asignados a el Procedimiento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
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


}//fin de la clase

?>
