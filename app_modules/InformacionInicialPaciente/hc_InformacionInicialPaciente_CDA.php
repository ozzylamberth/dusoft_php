<?php

/**
* $Id: hc_InformacionInicialPaciente_CDA.php,v 1.1 2008/09/03 13:41:32 hugo Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo ExamenFisico
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class ExamenFisico_CDA extends Extenciones_CDA_HC
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
    function ExamenFisico_CDA()
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
          $salida.="<caption>EXAMEN FISICO</caption>";
          $salida.="<TABLE border=\"1\" width=\"100%\">";
          
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"left\">";
          $salida.="<COLGROUP align=\"left\">";
          
          $salida.="<THEAD valign=\"top\">";
          $salida.="<TR>";
          $salida.="<TH>SISTEMA</TH>";
          $salida.="<TH>ESTADO</TH>";
          $salida.="<TH>OBSERVACION</TH>";
          $salida.="</TR>";
          $salida.="</THEAD>";

          foreach($XML_Consulta as $k => $v)
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD width=\"30%\">".$v[nombre]."</TD>";

               if($v[normal] == '0')
               {
               	if($v[sw_mostrar_normal_si] == '0')
                    {$estado = 'SI';}else{$estado = 'NORMAL';}
	               $salida.="<TD align=\"center\" width=\"10%\">".$estado."</TD>";
               }
               elseif($v[normal] == '1')
               {
                    if($v[sw_mostrar_normal_si] == '0')
                    {$estado = 'NO';}else{$estado = 'ANORMAL';}
	               $salida.="<TD align=\"center\" width=\"10%\">".$estado."</TD>";
               }
               
               $salida.="<TD width=\"60%\">".$v[anormal]."</TD>";
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
               $query ="SELECT nombre, normal, anormal, sw_mostrar_normal_si 
               	    FROM hc_sistemas AS A, hc_tipos_sistemas AS C
                        WHERE A.tipo_sistema_id=C.tipo_sistema_id
                        AND A.evolucion_id=".$Paramdatos[evolucion]."
                        ORDER BY C.tipo_sistema_id;";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($ex = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $ex;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $query ="SELECT nombre, normal, anormal, sw_mostrar_normal_si 
               	    FROM hc_sistemas AS A, hc_tipos_sistemas AS C
                        WHERE A.tipo_sistema_id=C.tipo_sistema_id
                        AND A.ingreso=".$Paramdatos[ingreso]."
                        ORDER BY C.tipo_sistema_id;";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($ex = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $ex;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query ="SELECT nombre, normal, anormal, sw_mostrar_normal_si 
               	    FROM hc_sistemas AS A, hc_tipos_sistemas AS C
                        WHERE A.tipo_sistema_id=C.tipo_sistema_id
                        AND A.ingreso=".$Paramdatos[ingreso]."
                        ORDER BY C.tipo_sistema_id;";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($ex = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $ex;
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
//                                           A.evolucion_id, A.sw_principal,
//                                           A.descripcion, A.tipo_diagnostico, A.usuario_id 
//                                    FROM hc_diagnosticos_ingreso AS A, diagnosticos AS B,
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
// 			return $salida;
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
//                                           A.evolucion_id, A.sw_principal,
//                                           A.descripcion, A.tipo_diagnostico, A.usuario_id 
//                                    FROM hc_diagnosticos_ingreso AS A, diagnosticos AS B,
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
// 			return $salida;
			return true;
               break;

               default:
               return false;                        
           }

    }
}//fin de la clase

?>
