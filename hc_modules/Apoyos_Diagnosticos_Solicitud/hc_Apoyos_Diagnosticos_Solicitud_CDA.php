<?php

/**
* $Id: hc_Apoyos_Diagnosticos_Solicitud_CDA.php,v 1.2 2005/06/23 19:04:54 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo Apoyos_Diagnosticos_Solicitud
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.2 $
* @package SIIS
*/ 
class Apoyos_Diagnosticos_Solicitud_CDA extends Extenciones_CDA_HC
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
    function Apoyos_Diagnosticos_Solicitud_CDA()
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
          $salida.="<caption>APOYOS DIAGNOSTICOS SOLICITADOS</caption>";
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
                    $salida.="<TH>FECHA</TH>";
                    $salida.="</TR>";
                    $salida.="</THEAD>";
               }
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD width=\"10%\" align=\"center\">".$v[tipo]."</TD>";
               $salida.="<TD width=\"10%\" align=\"center\">".$v[cargo]."</TD>";
               $salida.="<TD width=\"50%\" align=\"justify\">".$v[descripcion]."</TD>";
               $FechaI = $this->FechaStamp($v[fecha]);
               $salida.="<TD width=\"10%\" align=\"center\">".$FechaI."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="</TABLE>";
  
               $salida.="<section>";
               $salida.="<paragraph>";
               $salida.="<content>";
               
               if(!empty($v[observacion]))
               {
                    $salida.="<p></p>";
               	$salida.="<b>OBSERVACION: </b>".$v[observacion]."";
               }
               $salida.="</content>";
               $salida.="</paragraph>";
               
               $salida.="<paragraph>";
               $salida.="<content>";
               
               if(!empty($v[informacion_cargo]))
               {
                    $salida.="<p></p>";
               	$salida.="<b>INFORMACION: </b>".$v[informacion_cargo]."";
               }
               $salida.="</content>";
               $salida.="</paragraph>";
          
               $diag =$this->Diagnosticos_Solicitados($v[hc_os_solicitud_id]);
               if(!empty($diag))
               {
                    $salida.="<paragraph>";
                    $salida.="<content>";
               	$salida.="<p></p>";
                    $salida.="<caption><b>DIAGNOSTICOS:</b></caption>";
    				for($j=0;$j<sizeof($diag);$j++)
				{
                         $salida.="<p></p>";
					$salida.="<li>".$diag[$j][diagnostico_id]." - ".$diag[$j][diagnostico_nombre]."</li>";
				}
                    $salida.="</content>";
                    $salida.="</paragraph>";
               }
               $salida.="</section>";
               $salida.="<br></br>";
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
               $Plan = $this->BuscarPlan($Paramdatos[evolucion],'');
               $criterio='';
			if(!empty($Plan)){
                    $criterio = ",informacion_cargo('".$Plan[plan_id]."',a.cargo,'".$Plan[departamento]."')";
			}

               $query= "SELECT d.evolucion_id, d.ingreso, a.cargo, 
                               a.hc_os_solicitud_id, b.descripcion, c.descripcion as tipo, 
                               d.fecha, e.observacion
                               $criterio
               
                        FROM hc_os_solicitudes a 
                        LEFT JOIN hc_os_solicitudes_apoyod e ON(a.hc_os_solicitud_id = e.hc_os_solicitud_id),
                        cups b, apoyod_tipos c, hc_evoluciones d
                        WHERE d.evolucion_id =".$Paramdatos[evolucion]." 
                        AND a.evolucion_id = d.evolucion_id
                        AND a.cargo = b.cargo 
                        AND e.apoyod_tipo_id = c.apoyod_tipo_id
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
                              
               case '2':
               $Plan = $this->BuscarPlan('',$Paramdatos[ingreso]);
               $criterio='';
			if(!empty($Plan)){
                    $criterio = ",informacion_cargo('".$Plan[plan_id]."',a.cargo,'".$Plan[departamento]."')";
			}

               $query= "SELECT d.evolucion_id, d.ingreso, a.cargo, 
                               a.hc_os_solicitud_id, b.descripcion, c.descripcion as tipo, 
                               d.fecha, e.observacion
                               $criterio
          
                        FROM hc_os_solicitudes a 
                        LEFT JOIN hc_os_solicitudes_apoyod e ON(a.hc_os_solicitud_id = e.hc_os_solicitud_id),
                        cups b, apoyod_tipos c, hc_evoluciones d
                        WHERE d.ingreso =".$Paramdatos[ingreso]." 
                        AND a.evolucion_id = d.evolucion_id
                        AND a.cargo = b.cargo 
                        AND e.apoyod_tipo_id = c.apoyod_tipo_id
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

               $query= "SELECT d.evolucion_id, d.ingreso, a.cargo, 
                               a.hc_os_solicitud_id, b.descripcion, c.descripcion as tipo, 
                               d.fecha, e.observacion
                               $criterio
          
                        FROM hc_os_solicitudes a 
                        LEFT JOIN hc_os_solicitudes_apoyod e ON(a.hc_os_solicitud_id = e.hc_os_solicitud_id),
                        cups b, apoyod_tipos c, hc_evoluciones d
                        WHERE d.ingreso =".$Paramdatos[ingreso]." 
                        AND a.evolucion_id = d.evolucion_id
                        AND a.cargo = b.cargo 
                        AND e.apoyod_tipo_id = c.apoyod_tipo_id
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
    
    function BuscarPlan($evolucion,$ingreso)
    {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		if(!empty($evolucion))
          {
          	$query="SELECT A.plan_id, B.departamento
               	   FROM cuentas AS A, hc_evoluciones AS B
                       WHERE A.numerodecuenta = B.numerodecuenta
                       AND B.evolucion_id = $evolucion;";
          }
          elseif(!empty($ingreso))
          {
               $query="SELECT A.plan_id, B.departamento
               	   FROM cuentas AS A, hc_evoluciones AS B
                       WHERE A.numerodecuenta = B.numerodecuenta
                       AND A.ingreso = B.ingreso
                       AND A.ingreso = $ingreso;";
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
          $Plan = $resultado->FetchRow();
		return $Plan;
    }
    
    //clzc - si
	function Diagnosticos_Solicitados($hc_os_solicitud_id)
	{
          $pfj=$this->frmPrefijo;
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
