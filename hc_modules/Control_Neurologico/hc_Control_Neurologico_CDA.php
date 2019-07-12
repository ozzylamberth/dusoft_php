<?php

/**
* $Id: hc_Control_Neurologico_CDA.php,v 1.1 2005/06/24 20:38:59 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo Control_Neurologico
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class Control_Neurologico_CDA extends Extenciones_CDA_HC
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
    function Control_Neurologico_CDA()
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
          $salida.="<caption>CONTROLES NEUROLOGICOS</caption>";
          $salida.="<TABLE border=\"1\" width=\"100%\">";
          
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          
          $salida.="<TR>";
          $salida.="<TH rowspan=\"2\">FECHA</TH>";
          $salida.="<TH rowspan=\"2\">HORA</TH>";
          $salida.="<TH colspan=\"2\">PUPILA DERECHA</TH>";
          $salida.="<TH colspan=\"2\">PUPILA IZQUIERDA</TH>";
          $salida.="<TH rowspan=\"2\">CONCIENCIA</TH>";
          $salida.="<TH colspan=\"4\">FUERZA</TH>";
          $salida.="<TH colspan=\"4\">ESCALA DE GLASGOW</TH>";
          $salida.="</TR>";
          
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";

          $salida.="<TR>";
          $salida.="<TH>TALLA</TH>";
          $salida.="<TH>REACCION</TH>";
          $salida.="<TH>TALLA</TH>";
          $salida.="<TH>REACCION</TH>";
          $salida.="<TH>B. DER.</TH>";
          $salida.="<TH>B. IZQ.</TH>";
          $salida.="<TH>P. DER.</TH>";
          $salida.="<TH>P. IZQ.</TH>";
          $salida.="<TH>A.OCU.</TH>";
          $salida.="<TH>R.VER.</TH>";
          $salida.="<TH>R.MOT.</TH>";
          $salida.="<TH>E.G.</TH>";
          $salida.="</TR>";

          foreach($XML_Consulta as $k => $v)
		{              
 			$FechaM = $this->FechaStamp($v[fecha_registro]);
               $HoraM = $this->HoraStamp($v[fecha_registro]);
               $User=$this->GetDatosUsuarioSistema($v[usuario_id]);
			
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD align=\"center\">".$FechaM."</TD>";
               $salida.="<TD align=\"center\">".$HoraM."</TD>";
               
               if($v[pupila_talla_d] == 0){$v[pupila_talla_d] = '--';}
               $salida.="<TD align=\"center\">".$v[pupila_talla_d]."</TD>";
               
               if($v[pupila_reaccion_d] == ''){$v[pupila_reaccion_d] = '--';}
               $salida.="<TD align=\"center\">".$v[pupila_reaccion_d]."</TD>";
               
               if($v[pupila_talla_i] == 0){$v[pupila_talla_i] = '--';}
               $salida.="<TD align=\"center\">".$v[pupila_talla_i]."</TD>";
               
               if($v[pupila_reaccion_i] == ''){$v[pupila_reaccion_i] = '--';}
               $salida.="<TD align=\"center\">".$v[pupila_reaccion_i]."</TD>";
               
               if($v[descripcion] == ''){$v[descripcion] = '--';}
               $salida.="<TD align=\"center\">".$v[descripcion]."</TD>";
               
               if($v[fuerza_brazo_d] == ''){$v[fuerza_brazo_d] = '--';}
               $salida.="<TD align=\"center\">".$v[fuerza_brazo_d]."</TD>";
               
               if($v[fuerza_brazo_i] == ''){$v[fuerza_brazo_i] = '--';}
               $salida.="<TD align=\"center\">".$v[fuerza_brazo_i]."</TD>";

               if($v[fuerza_pierna_d] == ''){$v[fuerza_pierna_d] = '--';} 
               $salida.="<TD align=\"center\">".$v[fuerza_pierna_d]."</TD>";
               
               if($v[fuerza_pierna_i] == ''){$v[fuerza_pierna_i] = '--';} 
               $salida.="<TD align=\"center\">".$v[fuerza_pierna_i]."</TD>";
               
               if($v[tipo_apertura_ocular_id] == 0){$v[tipo_apertura_ocular_id] = '--';}
               $salida.="<TD align=\"center\">".$v[tipo_apertura_ocular_id]."</TD>";
               
               if($v[tipo_respuesta_verbal_id] == 0){$v[tipo_respuesta_verbal_id] = '--';}
               $salida.="<TD align=\"center\">".$v[tipo_respuesta_verbal_id]."</TD>";

               if($v[tipo_respuesta_motora_id] == 0){$v[tipo_respuesta_motora_id] = '--';} 
               $salida.="<TD align=\"center\">".$v[tipo_respuesta_motora_id]."</TD>";
               
               $AO = $v[tipo_apertura_ocular_id];
               $RV = $v[tipo_respuesta_verbal_id];
               $RM = $v[tipo_respuesta_motora_id];
               $EG = $AO + $RV + $RM;
               if($EG == 0){$EG = "--";}
               $salida.="<TD align=\"center\"><b>".$EG."</b></TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH>PROFESIONAL</TH>";
               $salida.="<TD colspan=\"14\">".'&nbsp;'.$User[0][nombre]."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          $salida.="</TABLE>";
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
               $query = "SELECT A.*, B.descripcion
                         FROM hc_controles_neurologia
                         AS A left join hc_tipos_nivel_consciencia AS B
                         on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
                         WHERE A.evolucion_id='".$Paramdatos[evolucion]."'
                         ORDER BY fecha_registro DESC;";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($neuro = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $neuro;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $query = "SELECT A.*, B.descripcion
                         FROM hc_controles_neurologia
                         AS A left join hc_tipos_nivel_consciencia AS B
                         on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
                         WHERE ingreso='".$Paramdatos[ingreso]."'
                         ORDER BY fecha_registro DESC;";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($neuro = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $neuro;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query = "SELECT A.*, B.descripcion
                         FROM hc_controles_neurologia
                         AS A left join hc_tipos_nivel_consciencia AS B
                         on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
                         WHERE ingreso='".$Paramdatos[ingreso]."'
                         ORDER BY fecha_registro DESC;";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($neuro = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $neuro;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '4':
//                $sql="SELECT ingreso
//                	 FROM ingresos
//                      WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
//                      AND paciente_id='".$Paramdatos[paciente_id]."'
//                      ORDER BY ingreso DESC;";
//                $resulta = $dbconn->Execute($sql);
//                if($dbconn->ErrorNo() != 0)
//                {
//                     $this->error = "Error al Cargar el Modulo";
//                     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                     return false;
//                }
//                while($data = $resulta->FetchRow())
//                {
//                     $ingreso[] = $data;
//                }
// 
//                if(!empty($ingreso))
// 			{
// 				for($i=0; $i<sizeof($ingreso); $i++)
// 				{
//                          $query = "SELECT B.diagnostico_id, B.diagnostico_nombre,
//                                           A.evolucion_id, A.usuario_id 
//                                    FROM hc_diagnosticos_muerte AS A, diagnosticos AS B,
//                                         hc_evoluciones C
//                                    WHERE A.tipo_diagnostico_id=B.diagnostico_id
//                                    AND A.evolucion_id = C.evolucion_id
//                                    AND C.ingreso = ".$ingreso[$i][0]."
//                                    ORDER BY B.diagnostico_id;";
//                
//                          $resultado = $dbconn->Execute($query);
//                          if ($dbconn->ErrorNo() != 0)
//                          {
//                               $this->error = "Error al Cargar el Modulo";
//                               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                               return false;
//                          }
//                          while($dx = $resultado->FetchRow())
//                          {
//                               $XML_Consulta[] = $dx;
//                          }
//                     }
//                }
//                     
//                $salida = $this->GetXML_Local($XML_Consulta);
			return true;
               break;

               case '5':
//                $sql="SELECT ingreso
//                	 FROM ingresos
//                      WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
//                      AND paciente_id='".$Paramdatos[paciente_id]."'
//                      ORDER BY ingreso DESC;";
//                $resulta = $dbconn->Execute($sql);
//                if($dbconn->ErrorNo() != 0)
//                {
//                     $this->error = "Error al Cargar el Modulo";
//                     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                     return false;
//                }
//                while($data = $resulta->FetchRow())
//                {
//                     $ingreso[] = $data;
//                }
// 
//                if(!empty($ingreso))
// 			{
// 				for($i=0; $i<sizeof($ingreso); $i++)
// 				{
//                          $query = "SELECT B.diagnostico_id, B.diagnostico_nombre,
//                                           A.evolucion_id, A.usuario_id 
//                                    FROM hc_diagnosticos_muerte AS A, diagnosticos AS B,
//                                         hc_evoluciones C
//                                    WHERE A.tipo_diagnostico_id=B.diagnostico_id
//                                    AND A.evolucion_id = C.evolucion_id
//                                    AND C.ingreso = ".$ingreso[$i][0]."
//                                    ORDER BY B.diagnostico_id;";
//                
//                          $resultado = $dbconn->Execute($query);
//                          if ($dbconn->ErrorNo() != 0)
//                          {
//                               $this->error = "Error al Cargar el Modulo";
//                               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                               return false;
//                          }
//                          while($dx = $resultado->FetchRow())
//                          {
//                               $XML_Consulta[] = $dx;
//                          }
//                     }
//                }
//                     
//                $salida = $this->GetXML_Local($XML_Consulta);
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


	/**
	*		GetSignosVitalesSitios
	*
	*		Obtiene el sitios donde o modo como se tomaran los signos vitales.
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool
	*/
	function GetSignosVitalesSitios($sitio)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
          $query="SELECT *
                    FROM hc_signos_vitales_sitios
                    WHERE sitio_id='$sitio'
                    ORDER BY sitio_id";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
          while ($data = $resultado->FetchRow())
          {
               $sitios[]=$data;
          }
          return $sitios;
	}

}//fin de la clase

?>
