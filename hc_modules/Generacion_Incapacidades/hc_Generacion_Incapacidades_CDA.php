<?php

/**
* $Id: hc_Generacion_Incapacidades_CDA.php,v 1.1 2005/06/24 20:39:36 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo Generacion_Incapacidades
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class Generacion_Incapacidades_CDA extends Extenciones_CDA_HC
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
    function Generacion_Incapacidades_CDA()
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
          $salida.="<caption>INCAPACIDADES MEDICAS</caption>";

		foreach($XML_Consulta as $k => $v)
          {
               $FechaM = $this->FechaStamp($v[fecha]);
               $HoraM = $this->HoraStamp($v[fecha]);

               $salida.="<TABLE border=\"1\" width=\"100%\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"left\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"left\">";
               
               $salida.="<THEAD valign=\"top\">";
               $salida.="<TR>";
               $salida.="<TH>EVOLUCION</TH>";
               $salida.="<TH>TIPO INCAPACIDAD</TH>";
               $salida.="<TH>DIAS INCAPACIDAD</TH>";
               $salida.="<TH>FECHA EMISION</TH>";
               $salida.="</TR>";
               $salida.="</THEAD>";

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD width=\"15%\" align=\"center\">".$v[evolucion_id]."</TD>";
               $salida.="<TD width=\"25%\" align=\"center\">".$v[tipo_incapacidad_descripcion]."</TD>";
               $salida.="<TD width=\"10%\" align=\"center\">".$v[dias_de_incapacidad]."</TD>";
               $salida.="<TD width=\"15%\" align=\"center\">".$FechaM." - ".$HoraM."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH width=\"15%\">OBSERVACION INCAPACIDAD";
               $salida.="<TD colspan=\"3\" width=\"85%\">".$v[observacion_incapacidad]."";
               $salida.="</TR>";
               $salida.="</TBODY>";
 
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
               $query="SELECT a.sw_prorroga, a.tipo_atencion_incapacidad_id,
               			a.evolucion_id, a.tipo_incapacidad_id, 
                              c.descripcion AS tipo_incapacidad_descripcion,
                              a.observacion_incapacidad, 
                              a.dias_de_incapacidad, 
                              b.fecha 
               	   FROM hc_incapacidades AS a, hc_evoluciones AS b,
               		   hc_tipos_incapacidad AS c 
                       WHERE a.evolucion_id = ".$Paramdatos[evolucion]."
                       AND a.evolucion_id = b.evolucion_id
                       AND a.tipo_incapacidad_id = c.tipo_incapacidad_id";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($incapacidad = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $incapacidad;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $query="SELECT a.sw_prorroga, a.tipo_atencion_incapacidad_id,
               			a.evolucion_id, a.tipo_incapacidad_id, 
                              c.descripcion AS tipo_incapacidad_descripcion,
                              a.observacion_incapacidad, 
                              a.dias_de_incapacidad, 
                              b.fecha 
               	   FROM hc_incapacidades AS a, hc_evoluciones AS b,
               		   hc_tipos_incapacidad AS c 
                       WHERE b.ingreso = ".$Paramdatos[ingreso]."
                       AND a.evolucion_id = b.evolucion_id
                       AND a.tipo_incapacidad_id = c.tipo_incapacidad_id";
              
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($incapacidad = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $incapacidad;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query="SELECT a.sw_prorroga, a.tipo_atencion_incapacidad_id,
               			a.evolucion_id, a.tipo_incapacidad_id, 
                              c.descripcion AS tipo_incapacidad_descripcion,
                              a.observacion_incapacidad, 
                              a.dias_de_incapacidad, 
                              b.fecha 
               	   FROM hc_incapacidades AS a, hc_evoluciones AS b,
               		   hc_tipos_incapacidad AS c 
                       WHERE b.ingreso = ".$Paramdatos[ingreso]."
                       AND a.evolucion_id = b.evolucion_id
                       AND a.tipo_incapacidad_id = c.tipo_incapacidad_id";
              
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($incapacidad = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $incapacidad;
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
                         $query = "SELECT B.diagnostico_id, B.diagnostico_nombre,
                                          A.evolucion_id, A.sw_principal,
                                          A.tipo_diagnostico, A.usuario_id 
                                   FROM hc_diagnosticos_egreso AS A, diagnosticos AS B,
                                        hc_evoluciones C
                                   WHERE A.tipo_diagnostico_id=B.diagnostico_id
                                   AND A.evolucion_id = C.evolucion_id
                                   AND C.ingreso = ".$ingreso[$i][0]."
                                   ORDER BY B.diagnostico_id;";
               
                         $resultado = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         while($dx = $resultado->FetchRow())
                         {
                              $XML_Consulta[] = $dx;
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
                         $query = "SELECT B.diagnostico_id, B.diagnostico_nombre,
                                          A.evolucion_id, A.sw_principal,
                                          A.tipo_diagnostico, A.usuario_id 
                                   FROM hc_diagnosticos_egreso AS A, diagnosticos AS B,
                                        hc_evoluciones C
                                   WHERE A.tipo_diagnostico_id=B.diagnostico_id
                                   AND A.evolucion_id = C.evolucion_id
                                   AND C.ingreso = ".$ingreso[$i][0]."
                                   ORDER BY B.diagnostico_id;";
               
                         $resultado = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         while($dx = $resultado->FetchRow())
                         {
                              $XML_Consulta[] = $dx;
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
