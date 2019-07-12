<?php

/**
* $Id: hc_SignosVitalesOdontologia_CDA.php,v 1.1 2005/07/05 15:34:07 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo SignosVitalesOdontologia
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class SignosVitalesOdontologia_CDA extends Extenciones_CDA_HC
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
    function SignosVitalesOdontologia_CDA()
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
          $salida.="<caption>SIGNOS VITALES ODONTOLOGIA</caption>";
               
          $salida.="<TABLE border=\"1\" width=\"100%\">";
          
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"center\">";
          
          $salida.="<THEAD valign=\"top\">";
          $salida.="<TR>";
          $salida.="<TH>FECHA</TH>";
          $salida.="<TH>HORA</TH>";
          $salida.="<TH>TENSION A.</TH>";
          $salida.="<TH>F.C.</TH>";
          $salida.="<TH>F.R.</TH>";
          $salida.="<TH>TEMPERATURA</TH>";
          $salida.="</TR>";
          $salida.="</THEAD>";

          foreach($XML_Consulta as $k => $v)
		{              
 			$FechaM = $this->FechaStamp($v[fecha_registro]);
               $HoraM = $this->HoraStamp($v[fecha_registro]);
			
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD align=\"center\">".$FechaM."</TD>";
               $salida.="<TD align=\"center\">".$HoraM."</TD>";
                
               if($v[taalta] AND $v[tabaja])
               {$tension=$v[taalta]." / ".$v[tabaja];}else{$tension='--';}
               $salida.="<TD align=\"center\">".$tension."</TD>";
              
               if($v[fc] == ''){$v[fc] = '--';}
               $salida.="<TD align=\"center\">".$v[fc]."</TD>";
               
               if($v[fr] == ''){$v[fr] = '--';}
               $salida.="<TD align=\"center\">".$v[fr]."</TD>";
               
               if($v[temperatura] == ''){$v[temperatura] = '--';}
               $salida.="<TD align=\"center\">".$v[temperatura]."</TD>";
               
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
               $query = "SELECT taalta, tabaja, fc,
               			  temperatura, fr, fecha_registro 
               		FROM hc_signos_vitales_consultas 
                         WHERE evolucion_id=".$Paramdatos[evolucion].";";

               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($signos = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $signos;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $query = "SELECT A.taalta, A.tabaja, A.fc,
               			  A.temperatura, A.fr, A.fecha_registro 
               		FROM hc_signos_vitales_consultas AS A, hc_evoluciones AS B
                         WHERE A.evolucion_id = B.evolucion_id
                         AND B.ingreso=".$Paramdatos[ingreso].";";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($signos = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $signos;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query = "SELECT A.taalta, A.tabaja, A.fc,
               			  A.temperatura, A.fr, A.fecha_registro 
               		FROM hc_signos_vitales_consultas AS A, hc_evoluciones AS B
                         WHERE A.evolucion_id = B.evolucion_id
                         AND B.ingreso=".$Paramdatos[ingreso].";";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($signos = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $signos;
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
		if($fecha)
          {
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
     
}//fin de la clase

?>
