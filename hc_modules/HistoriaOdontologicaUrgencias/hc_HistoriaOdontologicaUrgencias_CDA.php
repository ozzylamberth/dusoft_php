<?php

/**
* $Id: hc_HistoriaOdontologicaUrgencias_CDA.php,v 1.1 2005/06/23 16:26:27 carlos Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo MotivoConsulta
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class HistoriaOdontologicaUrgencias_CDA extends Extenciones_CDA_HC
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
    function HistoriaOdontologicaUrgencias_CDA()
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

// 		function GetXML_Local($XML_Consulta)
// 		{
// 					//$salida.="<caption><center>ODONTOLOGIA URGENCIAS</center></caption>";
// 					$FechaM = $this->FechaStamp($v[4]);
// 					$HoraM = $this->HoraStamp($v[4]);
// 					$User=$this->GetDatosUsuarioSistema($v[3]);
// 					$salida.="<br>";
// 					$salida.="<center><b>".$FechaM." - ".$HoraM." - ".$User[0][nombre]."</b></center>";
// 
// 					$salida.="<br><br>";
// 					$salida.="<table width=\"80%\" border=\"0\" align=\"center\">";
// 					$salida.="<tr>";
// 					$salida.="<td colspan=\"4\" align=\"center\">";
// 					$salida.="<b>EVOLUCIÓN URGENCIAS</b>";
// 					$salida.="</td>";
// 					$salida.="</tr>";
// 					$salida.="<tr>";
// 					$salida.="<td width=\"6%\" align=\"center\">";
// 					$salida.="<b>DIENTE</b>";
// 					$salida.="</td>";
// 					$salida.="<td width=\"17%\" align=\"center\">";
// 					$salida.="<b>SUPERFICIE</b>";
// 					$salida.="</td>";
// 					$salida.="<td width=\"25%\" align=\"center\">";
// 					$salida.="<b>HALLAZGO</b>";
// 					$salida.="</td>";
// 					$salida.="<td width=\"27%\" align=\"center\">";
// 					$salida.="<b>SOLUCIÓN</b>";
// 					$salida.="</td>";
// 					$salida.="</tr>";
// 					$ciclo=sizeof($XML_Consulta);
// 					for($i=0; $i<$ciclo; $i++)
// 					{
// 						if(!empty($XML_Consulta))
// 						{
// 							$salida.="<tr>";
// 							$salida.="<td align=\"center\">";
// 							$salida.="".$XML_Consulta[$i][hc_tipo_ubicacion_diente_id]."";
// 							$salida.="</td>";
// 							$salida.="<td align=\"center\">";
// 							$salida.="".$XML_Consulta[$i][des1]."";
// 							$salida.="</td>";
// 							$salida.="<td align=\"center\">";
// 							$salida.="".$XML_Consulta[$i][des2]."";
// 							$salida.="</td>";
// 							$salida.="<td align=\"center\">";
// 							$salida.="".$XML_Consulta[$i][des3]."";
// 							$salida.="</td>";
// 							$salida.="</tr>";
// 						}
// 					}
// 					$salida.="</table>";
// 					$salida.="<br>";
// 					return $salida;
// 		}

    function GetXML_Local($XML_Consulta)
    {
          $salida.="<caption>EVOLUCIÓN URGENCIAS</caption>";
          $salida.="<TABLE border=\"1\" width=\"80%\">";

          $salida.="<COLGROUP align=\"center\">";
          $salida.="<COLGROUP align=\"left\">";

          $salida.="<THEAD valign=\"top\">";
          $salida.="<TR>";
          $salida.="<TH>DIENTE</TH>";
          $salida.="<TH>SUPERFICIE</TH>";
          $salida.="<TH>HALLAZGO</TH>";
          $salida.="<TH>SOLUCIÓN</TH>";
          $salida.="</TR>";
          $salida.="</THEAD>";
          $ciclo=sizeof($XML_Consulta);
          for($i=0; $i<$ciclo; $i++)
						{
            $salida.="<TBODY>";
            $salida.="<TR>";
            $salida.="<TD width=\"20%\">".$XML_Consulta[$i][hc_tipo_ubicacion_diente_id]."</TD>";
            $salida.="<TD width=\"40%\">".$XML_Consulta[$i][des1]."</TD>";
            $salida.="<TD width=\"20%\">".$XML_Consulta[$i][des2]."</TD>";
            $salida.="<TD width=\"20%\">".$XML_Consulta[$i][des3]."</TD>";
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
                $query="SELECT A.hc_odontologia_evolucion_urgencias_detalle_id,
                      A.hc_tipo_cuadrante_id,
                      A.hc_tipo_ubicacion_diente_id,
                      A.hc_tipo_problema_diente_id,
                      A.hc_tipo_producto_diente_id,
                      A.fecha_registro,
                    B.descripcion AS des1,
                    C.descripcion AS des2,
                    D.descripcion AS des3,
                    C.sw_cariado,
                    C.sw_obturado,
                    C.sw_perdidos,
                    C.sw_sanos
                    FROM hc_odontologia_evolucion_urgencias_detalle AS A,
                    hc_tipos_cuadrantes_dientes AS B,
                    hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D
                    WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
                    AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                    AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
                    AND evolucion_id=".$Paramdatos[evolucion].";";
                $resultado = $dbconn->Execute($query);
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
               break;
                              
               case '2':
               $query="SELECT A.hc_odontologia_evolucion_urgencias_detalle_id,
                      A.hc_tipo_cuadrante_id,
                      A.hc_tipo_ubicacion_diente_id,
                      A.hc_tipo_problema_diente_id,
                      A.hc_tipo_producto_diente_id,
                      A.fecha_registro,
                    B.descripcion AS des1,
                    C.descripcion AS des2,
                    D.descripcion AS des3,
                    C.sw_cariado,
                    C.sw_obturado,
                    C.sw_perdidos,
                    C.sw_sanos
                    FROM hc_odontologia_evolucion_urgencias_detalle AS A,
                    hc_tipos_cuadrantes_dientes AS B,
                    hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D,
                    hc_evoluciones AS E
                    WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
                    AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                    AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
                    AND E.evolucion_id=".$Paramdatos[evolucion]."
                    AND E.ingreso=".$Paramdatos[ingreso].";";
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
               $salida = $this->GetXML_Local($XML_Consulta);
								return $salida;
               break;

               case '3':
               $query="SELECT A.hc_odontologia_evolucion_urgencias_detalle_id,
                      A.hc_tipo_cuadrante_id,
                      A.hc_tipo_ubicacion_diente_id,
                      A.hc_tipo_problema_diente_id,
                      A.hc_tipo_producto_diente_id,
                    B.descripcion AS des1,
                    C.descripcion AS des2,
                    D.descripcion AS des3,
                    C.sw_cariado,
                    C.sw_obturado,
                    C.sw_perdidos,
                    C.sw_sanos
                    FROM hc_odontologia_evolucion_urgencias_detalle AS A,
                    hc_tipos_cuadrantes_dientes AS B,
                    hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D,
                    hc_evoluciones AS E
                    WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
                    AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                    AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
                    AND E.evolucion_id=".$Paramdatos[evolucion]."
                    AND E.ingreso=".$Paramdatos[ingreso].";";
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
               $salida = $this->GetXML_Local($XML_Consulta);
							return $salida;
               break;
               
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


}//fin de la clase

?>
