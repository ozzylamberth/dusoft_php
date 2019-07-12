<?php
	/**************************************************************************************
	* $Id: hc_AntecedentesFamiliares.php,v 1.7 2006/12/19 23:10:41 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @author 
	* @version 1.0
	* @package SIIS
	* $Id: hc_AntecedentesFamiliares.php,v 1.7 2006/12/19 23:10:41 hugo Exp $
	*
	***************************************************************************************/
	class AntecedentesFamiliares extends hc_classModules
	{
		/**
		* Esta funciï¿½ Inicializa las variable de la clase
		*
		* @access public
		* @return boolean Para identificar que se realizo.
		*/
		function AntecedentesFamiliares()
		{
       	SessionSetVar("IngresoFami",$this->ingreso);
       	SessionSetVar("Submodulo",'AntecedentesFamiliares');
				return true;
		}
	 /**
		* Esta función retorna los datos de concernientes a la version del submodulo
		* @access private
		*/

// 		function GetVersion()
// 		{
// 			$informacion=array(
// 			'version'=>'1',
// 			'subversion'=>'0',
// 			'revision'=>'0',
// 			'fecha'=>'01/27/2005',
// 			'autor'=>'JAIME ANDRES VALENCIA',
// 			'descripcion_cambio' => '',
// 			'requiere_sql' => false,
// 			'requerimientos_adicionales' => '',
// 			'version_kernel' => '1.0'
// 			);
// 			return $informacion;
//     }
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
		* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
		function GetEstado()
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$query="SELECT count(*)
				FROM hc_antecedentes_familiares
				WHERE evolucion_id=".$this->evolucion.";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$estado=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}

			if ($estado[count] == 0)
			{
				return false;
			}
			else
			{
			 	return true;
			}
		}
		/**
		* Esta funciï¿½ retorna la presentaciï¿½ del submodulo (consulta o inserciï¿½).
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
				if($_REQUEST['accion'.$pfj]=='ocultar')
				{
					$this->frmForma();
				}
				else
				{
					if($_REQUEST['accion'.$pfj]=='modificar')
					{
						$this->UpdateDatos();
						$_REQUEST['accion'.$pfj]='ocultar';
						$this->frmForma();
					}
					else
					{
						if($_REQUEST['accion'.$pfj]=='otros')
						{
							$_SESSION['ANTECEDENTES'.$pfj]['otros']=1;
							$this->frmForma();
						}
						else
						{
							if($_REQUEST['accion'.$pfj]=='ocultarotros')
							{
								unset($_SESSION['ANTECEDENTES'.$pfj]['otros']);
								$this->frmForma();
							}
							else
							{
								if($this->InsertDatos()==true)
								{
									$this->frmForma();
								}
							}
						}
					}
				}
			}
			return $this->salida;
		}
		/*********************************************************************************************
		*
		**********************************************************************************************/
		function BusquedaAntecedentesTotal()
		{
			$sql .= "SELECT	HD.nombre_tipo,";
			$sql .= "				HD.riesgo, ";
			$sql .= "				HF.detalle, "; 
			$sql .= "				HF.destacar, ";
			$sql .= "				HE.evolucion_id,"; 
			$sql .= "				HD.hc_tipo_antecedente_familiar_id AS hctap, ";
			$sql .= "				HD.hc_tipo_antecedente_detalle_familiar_id AS hctad, ";
			$sql .= "				HT.descripcion,";
			$sql .= "				HT.sexo, "; 
			$sql .= "				HT.edad_min,"; 
			$sql .= "				HT.edad_max,"; 
			$sql .= "				HF.sw_riesgo,"; 
			$sql .= "			 	TO_CHAR(HF.fecha_registro,'YYYY-MM-DD') AS fecha, ";
			$sql .= "				COALESCE(HF.ocultar,'0') AS ocultar, ";
			$sql .= "				HF.hc_antecedente_familiar_id AS hcid ";
			$sql .= "FROM		hc_evoluciones HE "; 
			$sql .= "				JOIN ingresos IG "; 
			$sql .= "				ON(	HE.evolucion_id <= ".$this->evolucion." AND "; 
			$sql .= "						HE.ingreso = IG.ingreso AND "; 
			$sql .= "						IG.paciente_id = '".$this->paciente."' AND ";
			$sql .= "						IG.tipo_id_paciente = '".$this->tipoidpaciente."') ";
			$sql .= "				JOIN hc_antecedentes_familiares HF "; 
			$sql .= "				ON(HE.evolucion_id = HF.evolucion_id) ";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_detalle_familiares HD "; 
			$sql .= "				ON(	HF.hc_tipo_antecedente_detalle_familiar_id = HD.hc_tipo_antecedente_detalle_familiar_id AND ";
			$sql .= "						HF.hc_tipo_antecedente_familiar_id = HD.hc_tipo_antecedente_familiar_id) ";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_familiares HT ";
			$sql .= "				ON(	HD.hc_tipo_antecedente_familiar_id = HT.hc_tipo_antecedente_familiar_id) ";
			$sql .= "ORDER BY HD.hc_tipo_antecedente_familiar_id, HD.nombre_tipo;";
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[$rst->fields[7]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			return $antecedentes;
		}
		/*********************************************************************************************
		*
		**********************************************************************************************/
		function BusquedaAntecedentes($dat)
		{
			list($dbconn) = GetDBconn();
			unset($query);
			if(empty($dat))
			{
				$query = "select d.nombre_tipo, d.riesgo, c.detalle, c.destacar,
								 a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
								 d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
								 c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro
							from hc_evoluciones as a join ingresos as b on(a.evolucion_id<=".$this->evolucion." and a.ingreso=b.ingreso and b.paciente_id='".$this->paciente."' and b.tipo_id_paciente='".$this->tipoidpaciente."')
							join hc_antecedentes_familiares as c on(a.evolucion_id=c.evolucion_id)
							right join hc_tipos_antecedentes_detalle_familiares as d on(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id and c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
							right join hc_tipos_antecedentes_familiares as e on(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
							order by d.hc_tipo_antecedente_detalle_familiar_id, c.fecha_registro ASC;";
			}
			else
			{
				$query = "select d.nombre_tipo, d.riesgo, c.detalle, c.destacar,
								 a.evolucion_id, d.hc_tipo_antecedente_familiar_id,
								 d.hc_tipo_antecedente_detalle_familiar_id, e.descripcion,
								 c.ocultar, c.hc_antecedente_familiar_id, c.sw_riesgo, c.fecha_registro
							from hc_evoluciones as a join ingresos as b on(a.evolucion_id<=".$this->evolucion." and a.ingreso=b.ingreso and b.paciente_id='".$this->paciente."' and b.tipo_id_paciente='".$this->tipoidpaciente."')
							join hc_antecedentes_familiares as c on(a.evolucion_id=c.evolucion_id and c.ocultar=0)
							right join hc_tipos_antecedentes_detalle_familiares as d on(c.hc_tipo_antecedente_detalle_familiar_id=d.hc_tipo_antecedente_detalle_familiar_id and c.hc_tipo_antecedente_familiar_id=d.hc_tipo_antecedente_familiar_id)
							right join hc_tipos_antecedentes_familiares as e on(d.hc_tipo_antecedente_familiar_id=e.hc_tipo_antecedente_familiar_id)
							order by d.hc_tipo_antecedente_detalle_familiar_id, c.fecha_registro ASC";
			}

	    $result = $dbconn->Execute($query);
	    $i=0;
			if ($dbconn->ErrorNo() != 0)
		  {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	      return false;
	    }
			else
			{
	      while (!$result->EOF)
				{
					if(!empty($result->fields[2]))
					{
						$tipo_ant[0][$i]=$result->fields[0];
						$tipo_ant[1][$i]=$result->fields[1];
						$tipo_ant[2][$i]=$result->fields[2];
						$tipo_ant[3][$i]=$result->fields[3];
						$tipo_ant[4][$i]=$result->fields[4];
						$tipo_ant[5][$i]=$result->fields[5];
						$tipo_ant[6][$i]=$result->fields[6];
						$tipo_ant[7][$i]=$result->fields[7];
						$tipo_ant[8][$i]=$result->fields[8];
						$tipo_ant[9][$i]=$result->fields[9];
						$tipo_ant[10][$i]=$result->fields[10];
	                         $tipo_ant[11][$i]=$result->fields[11];
						$i++;
					}
					$result->MoveNext();
				 }
				 $result->close();
			}
			return $tipo_ant;
		}
		/************************************************************************************
		*
		*************************************************************************************/
    function PartirFecha($fecha)
    {
      $a=explode('-',$fecha);
      $b=explode(' ',$a[2]);
      $c=explode(':',$b[1]);
      $d=explode('.',$c[2]);
      return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
    }
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaAntecedentesTotal2()
		{
			$sql .= "SELECT	HD.nombre_tipo,";
			$sql .= "				HD.riesgo, ";
			$sql .= "				HF.detalle, "; 
			$sql .= "				HF.destacar, ";
			$sql .= "				HE.evolucion_id,"; 
			$sql .= "				HD.hc_tipo_antecedente_familiar_id AS hctap, ";
			$sql .= "				HD.hc_tipo_antecedente_detalle_familiar_id AS hctad, ";
			$sql .= "				HT.descripcion,";
			$sql .= "				HT.sexo, "; 
			$sql .= "				HT.edad_min,"; 
			$sql .= "				HT.edad_max,"; 
			$sql .= "				HF.sw_riesgo,"; 
			$sql .= "			 	TO_CHAR(HF.fecha_registro,'YYYY-MM-DD') AS fecha, ";
			$sql .= "				COALESCE(HF.ocultar,'0') AS ocultar, ";
			$sql .= "				HF.hc_antecedente_familiar_id AS hcid ";
			$sql .= "FROM		hc_evoluciones HE "; 
			$sql .= "				JOIN ingresos IG "; 
			$sql .= "				ON(	HE.evolucion_id <= ".$this->evolucion." AND "; 
			$sql .= "						HE.ingreso = IG.ingreso AND "; 
			$sql .= "						IG.paciente_id = '".$this->paciente."' AND ";
			$sql .= "						IG.tipo_id_paciente = '".$this->tipoidpaciente."') ";
			$sql .= "				JOIN hc_antecedentes_familiares HF "; 
			$sql .= "				ON(HE.evolucion_id = HF.evolucion_id) ";
			$sql .= "				JOIN hc_tipos_antecedentes_detalle_familiares HD "; 
			$sql .= "				ON(	HF.hc_tipo_antecedente_detalle_familiar_id = HD.hc_tipo_antecedente_detalle_familiar_id AND ";
			$sql .= "						HF.hc_tipo_antecedente_familiar_id = HD.hc_tipo_antecedente_familiar_id) ";
			$sql .= "				JOIN hc_tipos_antecedentes_familiares HT ";
			$sql .= "				ON(	HD.hc_tipo_antecedente_familiar_id = HT.hc_tipo_antecedente_familiar_id) ";
			$sql .= "ORDER BY HD.hc_tipo_antecedente_familiar_id, HD.nombre_tipo;";
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[$rst->fields[7]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			return $antecedentes;
		}
		/************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta 
		* sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>
