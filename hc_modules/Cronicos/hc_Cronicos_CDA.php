<?php

/**
* $Id: hc_Cronicos_CDA.php,v 1.1 2005/06/29 18:17:00 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo Cronicos
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class Cronicos_CDA extends Extenciones_CDA_HC
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
    function Cronicos_CDA()
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
          $salida.="<caption>PROMOCION Y PREVENCION</caption>";
          $xx = 0;
          foreach($XML_Consulta as $k => $v)
          {
          	$salida.="<TABLE border=\"1\" width=\"100%\">";
               if($xx != 1)
               {
               	$xx = 1;
                    $salida.="<COLGROUP align=\"center\">";
                    $salida.="<COLGROUP align=\"center\">";
                    
                    $salida.="<THEAD valign=\"top\">";
                    $salida.="<TR>";
                    $salida.="<TH align=\"center\">PROGRAMA</TH>";
                    $salida.="<TH align=\"center\">INSCRITO</TH>";
                    $salida.="</TR>";
                    $salida.="</THEAD>";
               }
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD width=\"70%\" align=\"justify\">".$v[nombre]."</TD>";
               if($v[sino] == '1')
               {
               	$inscrito = 'SI';
               	$salida.="<TD align=\"center\" width=\"30%\">".$inscrito."</TD>";
               }
               if($v[sino] == '0')
               {
               	$inscrito = 'NO';
               	$salida.="<TD align=\"center\" width=\"30%\">".$inscrito."</TD>";
               }
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
               $this->ConsultaOwner_Antecedentes($Paramdatos[evolucion],'');
			$query ="SELECT tipo_cronicos.tipo_cronico_id,
               			 nombre, sino 
                        FROM tipo_cronicos
                        LEFT JOIN cronicos ON(tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id)
                        WHERE tipo_id_paciente='".$this->id_paciente[tipo_id_paciente]."'
                        AND paciente_id='".$this->id_paciente[paciente_id]."'
                        ORDER BY tipo_cronicos.tipo_cronico_id;";               
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($cronico = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $cronico;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $this->ConsultaOwner_Antecedentes('',$Paramdatos[ingreso]);
			$query ="SELECT tipo_cronicos.tipo_cronico_id,
               			 nombre, sino 
                        FROM tipo_cronicos
                        LEFT JOIN cronicos ON(tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id)
                        WHERE tipo_id_paciente='".$this->id_paciente[tipo_id_paciente]."'
                        AND paciente_id='".$this->id_paciente[paciente_id]."'
                        ORDER BY tipo_cronicos.tipo_cronico_id;";               
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($cronico = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $cronico;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $this->ConsultaOwner_Antecedentes('',$Paramdatos[ingreso]);
			$query ="SELECT tipo_cronicos.tipo_cronico_id,
               			 nombre, sino 
                        FROM tipo_cronicos
                        LEFT JOIN cronicos ON(tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id)
                        WHERE tipo_id_paciente='".$this->id_paciente[tipo_id_paciente]."'
                        AND paciente_id='".$this->id_paciente[paciente_id]."'
                        ORDER BY tipo_cronicos.tipo_cronico_id;";               
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($cronico = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $cronico;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '4':
			$query ="SELECT tipo_cronicos.tipo_cronico_id,
               			 nombre, sino 
                        FROM tipo_cronicos
                        LEFT JOIN cronicos ON(tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id)
                        WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
                        AND paciente_id='".$Paramdatos[paciente_id]."'
                        ORDER BY tipo_cronicos.tipo_cronico_id;";               
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($cronico = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $cronico;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '5':
			$query ="SELECT tipo_cronicos.tipo_cronico_id,
               			 nombre, sino 
                        FROM tipo_cronicos
                        LEFT JOIN cronicos ON(tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id)
                        WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
                        AND paciente_id='".$Paramdatos[paciente_id]."'
                        ORDER BY tipo_cronicos.tipo_cronico_id;";               
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($cronico = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $cronico;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               default:
               return false;                        
           }

    }
    
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

}//fin de la clase

?>
