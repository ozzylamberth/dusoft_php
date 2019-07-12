<?php

/**
* Submodulo de Signos Vitales.
*
* Submodulo para manejar los signos vitales de un paciente en una evoluciï¿½.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitalesOdontologia.php,v 1.9 2006/12/19 21:00:15 jgomez Exp $
*/


/**
* SignosVitales
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta del
* submodulo de signos vitales.
*/

class SignosVitalesOdontologia extends hc_classModules
{

     /**
     * Esta funciï¿½ Inicializa las variable de la clase
     *
     * @access public
     * @return boolean Para identificar que se realizo.
     */
     function SignosVitalesOdontologia()
     {
          return true;
     }


     /**
     * Esta función retorna los datos de concernientes a la version del submodulo
     * @access private
     */

//      function GetVersion()
//      {
//           $informacion=array(
//           'version'=>'1',
//           'subversion'=>'0',
//           'revision'=>'0',
//           'fecha'=>'01/27/2005',
//           'autor'=>'JAIME ANDRES VALENCIA',
//           'descripcion_cambio' => '',
//           'requiere_sql' => false,
//           'requerimientos_adicionales' => '',
//           'version_kernel' => '1.0'
//           );
//           return $informacion;
//      }


     /**
     * Esta funciï¿½ retorna los datos de la impresiï¿½ de la consulta del submodulo.
     *
     * @access private
     * @return text Datos HTML de la pantalla.
     */
     function GetConsulta()
     {
          if($this->frmConsulta()==false)
          {
               return true;
          }
          return $this->salida;
     }

     /**
     * Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
     *
     * @access private
     * @return text Datos HTML de la pantalla.
     */

	function GetEstado()
	{	
		$pfj=$this->frmPrefijo;
          return true;
     }

     /**
     * Esta metodo captura los datos de la impresión de la Historia Clinica.
     * @access private
     * @return text Datos HTML de la pantalla.
     */

     function GetReporte_Html()
     {
          $imprimir=$this->frmHistoria();
          if($imprimir==false)
          {
               return true;
          }
          return $imprimir;
     }


     /**
     * Esta funciï¿½ retorna la presentaciï¿½ del submodulo (consulta inserciï¿½).
     *
     * @access public
     * @return text Datos HTML de la pantalla.
     * @param text Determina la acciï¿½ a realizar.
     */
     function GetForma()
     {
          $pfj=$this->frmPrefijo;
          if(empty($_REQUEST['accion'.$pfj]))
          {
               $this->frmForma();
          }
          else
          {
               if($this->InsertDatos()==true)
               {
                    $this->frmForma();
               }
          }
          return $this->salida;
     }

     /**
     * Esta funciï¿½ inserta los datos del submodulo.
     *
     * @access private
     * @return boolean Informa si lo logro o no.
     */
     function InsertDatos()
     {
          $pfj=$this->frmPrefijo;
          $sql="";
          $sql="select signos_vitales_consulta_id from hc_signos_vitales_consultas where evolucion_id=".$this->evolucion."";
          list($dbconn) = GetDBconn();
          $result=$dbconn->Execute($sql);
          $fc='fc'.$pfj;
          $temperatura='temperatura'.$pfj;
          $taalta='taalta'.$pfj;
          $tabaja='tabaja'.$pfj;
          $fr='fr'.$pfj;
          if($_REQUEST[$fc]==='' or !is_numeric($_REQUEST[$fc]))
          {
               if(!is_numeric($_REQUEST[$fc]) and !($_REQUEST[$fc]===''))
               {
                    $this->frmError['MensajeError']="Se Escribieron letras en FC";
                    return true;
               }
               $_REQUEST[$fc]='NULL';
          }
          if($_REQUEST[$temperatura]==='' or !is_numeric($_REQUEST[$temperatura]))
          {
               if(!is_numeric($_REQUEST[$temperatura]) and !($_REQUEST[$temperatura]===''))
               {
                    $this->frmError['MensajeError']="Se Escribieron letras en Temperatura";
                    return true;
               }
               $_REQUEST[$temperatura]='NULL';
          }

          if($_REQUEST[$temperatura] > 43)
          {
               $this->frmError['MensajeError']="La Temperatura excede el valor.";
               return true;
          }

          if($_REQUEST[$taalta]==='' or $_REQUEST[$tabaja]===''  or (!is_numeric($_REQUEST[$taalta]) or !is_numeric($_REQUEST[$tabaja])))
          {
               if((!is_numeric($_REQUEST[$taalta]) or !is_numeric($_REQUEST[$tabaja])) and (!($_REQUEST[$taalta]==='') or !($_REQUEST[$tabaja]==='')))
               {
                    $this->frmError['MensajeError']="Se Escribieron letras en Tensiòn Arterial";
                    return true;
               }
               $_REQUEST[$taalta]='NULL';
               $_REQUEST[$tabaja]='NULL';
          }
          if($_REQUEST[$fr]==='' or !is_numeric($_REQUEST[$fr]))
          {
               if(!is_numeric($_REQUEST[$fr]) and !($_REQUEST[$fr]===''))
               {
                    $this->frmError['MensajeError']="Se Escribieron letras en FR";
                    return true;
               }
               $_REQUEST[$fr]='NULL';
          }
          if($_REQUEST[$fr]=='NULL' and $_REQUEST[$tabaja]=='NULL' and $_REQUEST[$taalta]=='NULL' and $_REQUEST[$temperatura]=='NULL' and $_REQUEST[$fc]=='NULL')
          {
               $this->frmError['MensajeError']="Debe Ingresar Datos Para la Inserciòn";
               return true;
          }
          if($result->RecordCount()==0)
          {
               $sql="insert into hc_signos_vitales_consultas (fc,temperatura,peso,taalta,tabaja,fr,talla,evolucion_id,fecha_registro)
               values(".$_REQUEST[$fc].",".$_REQUEST[$temperatura].",NULL
               ,".$_REQUEST[$taalta].",".$_REQUEST[$tabaja]."
               ,".$_REQUEST[$fr].",NULL,'$this->evolucion',now())";
                              // Reportar errores para depuracion.
               error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
               list($dbconn) = GetDBconn();
               if(!$dbconn->Execute($sql))
               {
                    echo $dbconn->ErrorMsg();
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else
               {
                    $this->frmError['MensajeError']="Datos Guardados Satisfactoriamente";
                     $this->RegistrarSubmodulo($this->GetVersion());            
                    return true;
               }
          }
          else
          {
               $sql="update hc_signos_vitales_consultas set fc=".$_REQUEST[$fc].", temperatura=".$_REQUEST[$temperatura].", taalta=".$_REQUEST[$taalta].", tabaja=".$_REQUEST[$tabaja].", fr=".$_REQUEST[$fr].", fecha_registro = now() where evolucion_id='$this->evolucion';";
               list($dbconn) = GetDBconn();
               if(!$dbconn->Execute($sql))
               {
                    echo $dbconn->ErrorMsg();
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else
               {
                    $this->frmError['MensajeError']="Datos Guardados Satisfactoriamente";
                     $this->RegistrarSubmodulo($this->GetVersion());            
                    return true;
               }
          }
     }

     function DatosSignos()
     {
          $sql="select taalta,tabaja,fc,temperatura,fr,peso,talla,fecha_registro from hc_signos_vitales_consultas where evolucion_id=".$this->evolucion.";";
          list($dbconn) = GetDBconn();
          $result=$dbconn->Execute($sql);
          return $result;
     }
     
     function BusquedaDatosSignos()
     {
     	$pfj = $this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $select = "SELECT taalta, tabaja, fc, temperatura, fr, fecha_registro
          		 FROM hc_signos_vitales_consultas
                     WHERE evolucion_id=".$this->evolucion.";";
     	$resultado = $dbconnect->Execute($select);

          if($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
	          return false;
          }
          else
		{	
          	$i = 0;
			while (!$resultado->EOF)
			{
				$dato[0][$i]=$resultado->fields[0];
				$dato[1][$i]=$resultado->fields[1];
				$dato[2][$i]=$resultado->fields[2];
				$dato[3][$i]=$resultado->fields[3];
				$dato[4][$i]=$resultado->fields[4];
 				$dato[5][$i]=$resultado->fields[5];
				$resultado->MoveNext();
				$i++;
			}
		}
		return $dato;
     }

               
     function Get_Signos_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT evolucion_id 
                    FROM hc_odontogramas_primera_vez
                    WHERE tipo_id_paciente = '".$this->tipoidpaciente."'
                    AND paciente_id = '".$this->paciente."'
                    AND (sw_activo = '1' OR sw_activo = '0');";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($primera_Evo) = $resultado->FetchRow();
          if (!empty($primera_Evo))
          {
			$sql = "SELECT taalta,tabaja,fc,temperatura,fr,peso,talla, fecha_registro
               	   FROM hc_signos_vitales_consultas
                       WHERE evolucion_id=".$primera_Evo.";";
               $resultado = $dbconn->Execute($sql);        
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while ($sig = $resultado->FetchRow())
               {
               	$signos[] = $sig;
               }
          }        
          return $signos;
     }
     
     function PartirFecha($fecha)
     {
          $a=explode('-',$fecha);
          $b=explode(' ',$a[2]);
          $c=explode(':',$b[1]);
          $d=explode('.',$c[2]);
          return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
     }

}
?>
