<?php

/**
* $Id: hc_AntecedentesFamiliares_CDA.php,v 1.1 2005/06/20 22:17:50 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo AntecedentesFamiliares
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class AntecedentesFamiliares_CDA extends Extenciones_CDA_HC
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
    function AntecedentesFamiliares_CDA()
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
          $salida.="<caption>ANTECEDENTES FAMILIARES</caption>";
          $salida.="<TABLE border=\"1\" width=\"100%\">";
          
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          
          $salida.="<THEAD valign=\"top\">";
          $salida.="<TR>";
          $salida.="<TH colspan=\"2\">ANTECEDENTES</TH>";
          $salida.="<TH>RIESGO</TH>";
          $salida.="<TH>DETALLE</TH>";
          $salida.="<TH>FECHA</TH>";
          $salida.="</TR>";
          $salida.="</THEAD>";
		
          for($i=0; $i<sizeof($XML_Consulta); $i++)
          {
          	if(!empty($XML_Consulta[$i][evolucion_id]))
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TH rowspan=\"6\" align=\"center\" width=\"15%\">".$XML_Consulta[$i][descripcion]."</TH>";
                    $salida.="<TD align=\"justify\" width=\"25%\">".$XML_Consulta[$i][nombre_tipo]."</TD>";
                    if($XML_Consulta[$i][sw_riesgo] == '1')
                    {$riesgo = 'SI';}
                    else{$riesgo = 'NO';}
                    $salida.="<TD align=\"center\" width=\"10%\">".$riesgo."</TD>";
                    $salida.="<TD align=\"justify\" width=\"40%\">".$XML_Consulta[$i][detalle]."</TD>";
                    $fecha = $this->FechaStamp($XML_Consulta[$i][fecha_registro]);
                    $salida.="<TD align=\"center\" width=\"10%\">".$fecha."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
               }
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
               $this->id_paciente = $data;
          }
          return true;
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
               $this->ConsultaOwner_Antecedentes($Paramdatos[evolucion],'');
               $query = "SELECT d.nombre_tipo, d.riesgo, c.detalle, c.destacar, 
               			  a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
                                d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
                                c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro

                         FROM hc_evoluciones AS a 
                         JOIN ingresos AS b ON(a.ingreso=b.ingreso AND b.paciente_id='".$this->id_paciente[paciente_id]."' AND b.tipo_id_paciente='".$this->id_paciente[tipo_id_paciente]."')
                         JOIN hc_antecedentes_familiares AS c ON(a.evolucion_id=c.evolucion_id)
                         RIGHT JOIN hc_tipos_antecedentes_detalle_familiares AS d ON(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id AND c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
					RIGHT JOIN hc_tipos_antecedentes_familiares AS e ON(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
					ORDER BY d.hc_tipo_antecedente_familiar_id, d.hc_tipo_antecedente_detalle_familiar_id;";

               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
             
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($antecedentes = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $antecedentes;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $this->ConsultaOwner_Antecedentes('',$Paramdatos[ingreso]);
               $query = "SELECT d.nombre_tipo, d.riesgo, c.detalle, c.destacar, 
               			  a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
                                d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
                                c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro

                         FROM hc_evoluciones AS a 
                         JOIN ingresos AS b ON(a.ingreso=b.ingreso AND b.paciente_id='".$this->id_paciente[paciente_id]."' AND b.tipo_id_paciente='".$this->id_paciente[tipo_id_paciente]."')
                         JOIN hc_antecedentes_familiares AS c ON(a.evolucion_id=c.evolucion_id)
                         RIGHT JOIN hc_tipos_antecedentes_detalle_familiares AS d ON(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id AND c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
					RIGHT JOIN hc_tipos_antecedentes_familiares AS e ON(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
					ORDER BY d.hc_tipo_antecedente_familiar_id, d.hc_tipo_antecedente_detalle_familiar_id;";
                                   
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($antecedentes = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $antecedentes;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '3':
               $this->ConsultaOwner_Antecedentes('',$Paramdatos[ingreso]);
               $query = "SELECT d.nombre_tipo, d.riesgo, c.detalle, c.destacar, 
               			  a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
                                d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
                                c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro

                         FROM hc_evoluciones AS a 
                         JOIN ingresos AS b ON(a.ingreso=b.ingreso AND b.paciente_id='".$this->id_paciente[paciente_id]."' AND b.tipo_id_paciente='".$this->id_paciente[tipo_id_paciente]."')
                         JOIN hc_antecedentes_familiares AS c ON(a.evolucion_id=c.evolucion_id)
                         RIGHT JOIN hc_tipos_antecedentes_detalle_familiares AS d ON(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id AND c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
					RIGHT JOIN hc_tipos_antecedentes_familiares AS e ON(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
					ORDER BY d.hc_tipo_antecedente_familiar_id, d.hc_tipo_antecedente_detalle_familiar_id;";
                                   
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($antecedentes = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $antecedentes;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '4':
               $query = "SELECT d.nombre_tipo, d.riesgo, c.detalle, c.destacar, 
               			  a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
                                d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
                                c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro

                         FROM hc_evoluciones AS a 
                         JOIN ingresos AS b ON(a.ingreso=b.ingreso AND b.paciente_id='".$Paramdatos[paciente_id]."' AND b.tipo_id_paciente='".$Paramdatos[tipoidpaciente]."')
                         JOIN hc_antecedentes_familiares AS c ON(a.evolucion_id=c.evolucion_id)
                         RIGHT JOIN hc_tipos_antecedentes_detalle_familiares AS d ON(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id AND c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
					RIGHT JOIN hc_tipos_antecedentes_familiares AS e ON(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
					ORDER BY d.hc_tipo_antecedente_familiar_id, d.hc_tipo_antecedente_detalle_familiar_id;";
                                   
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($antecedentes = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $antecedentes;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '5':
               $query = "SELECT d.nombre_tipo, d.riesgo, c.detalle, c.destacar, 
               			  a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
                                d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
                                c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro

                         FROM hc_evoluciones AS a 
                         JOIN ingresos AS b ON(a.ingreso=b.ingreso AND b.paciente_id='".$Paramdatos[paciente_id]."' AND b.tipo_id_paciente='".$Paramdatos[tipoidpaciente]."')
                         JOIN hc_antecedentes_familiares AS c ON(a.evolucion_id=c.evolucion_id)
                         RIGHT JOIN hc_tipos_antecedentes_detalle_familiares AS d ON(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id AND c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
					RIGHT JOIN hc_tipos_antecedentes_familiares AS e ON(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
					ORDER BY d.hc_tipo_antecedente_familiar_id, d.hc_tipo_antecedente_detalle_familiar_id;";
                                   
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($antecedentes = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $antecedentes;
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

}//fin de la clase

?>
