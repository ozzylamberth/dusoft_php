<?php
	/********************************************************************************* 
 	* $Id: hc_DatosRecienNacidos_Nacidos.class.php,v 1.2 2007/02/01 20:44:46 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionCPN_Inscripcion
	* 
 	**********************************************************************************/
	
	class Nacidos
	{
		
		function Nacidos()
		{
			return true;
		}

		function GuardarDatosNacidos($inscripcion,$evolucion,$datos)
		{
			$pfj=SessionGetVar("Prefijo");
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="INSERT INTO pyp_cpn_datos_recien_nacidos
						(evolucion_id,
						inscripcion_id,
						no_historia_rn,
						nombre_madre,
						nombre_rn,
						nombre_padre,
						sw_sexo,
						peso_nacer,
						talla,
						perimetro_cefalico,
						grupo_sanguineo,
						rh,
						sw_vdrl,
						sw_tsh,
						sw_bcg,
						sw_hepatitis,
						sw_polio,
						sw_vitaminak,
						peso_egreso,
						edad_ex_fisico,
						sw_tipo_egreso,
						sw_rn_conmadre,
						horas_traslado,
						horas_fallece,
						tipo_alimentacion,
						sw_muerte_materna,
						tipo_muerte_materna
						)
						VALUES
						(
							$evolucion,
							$inscripcion,
							'".$datos[0]."',
							'".$datos[1]."',
							'".$datos[2]."',
							'".$datos[3]."',
							'".$datos[4]."',
							".$datos[5].",
							".$datos[6].",
							".$datos[7].",
							'".$datos[8]."',
							'".$datos[9]."',
							'".$datos[10]."',
							'".$datos[11]."',
							'".$datos[12]."',
							'".$datos[13]."',
							'".$datos[14]."',
							'".$datos[15]."',
							".$datos[16].",
							".$datos[17].",
							'".$datos[18]."',
							'".$datos[19]."',
							'".$datos[20]."',
							'".$datos[21]."',
							'".$datos[22]."',
							'".$datos[23]."',
							'".$datos[24]."'
						)";

			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo DatosRecienNacidos - GuardarDatosNacidos - SQL ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			return true;
		}
		
		function NumeroHijos($inscripcion,$evolucion)
		{
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();

			$query="SELECT count(*)
							FROM pyp_cpn_datos_recien_nacidos
							WHERE inscripcion_id=$inscripcion
							AND evolucion_id=$evolucion";
						
			$result = $dbconn->Execute($query);
			
			$numero[0]=$result->fields[0]+1;
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo DatosRecienNacidos - GuardarDatosNacidos - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			
			$query="SELECT num_hijos_vivos
							FROM pyp_cpn_cierre_caso
							WHERE inscripcion_id=$inscripcion
							AND evolucion_id=$evolucion";
						
			$result = $dbconn->Execute($query);
			
			$numero[1]=$result->fields[0];
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo DatosRecienNacidos - GuardarDatosNacidos - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			
			$dbconn->CommitTrans();
			return $numero;
		}
		
		function ConsultaInformacion($inscripcion,$evolucion)
		{
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();

			$query="SELECT 	no_historia_rn,
											nombre_madre,
											nombre_rn,
											nombre_padre,
											CASE sw_sexo
											WHEN '1' THEN 'FEMENINO'
											WHEN '2' THEN 'MASCULINO'
											END AS sexo
							FROM pyp_cpn_datos_recien_nacidos
							WHERE inscripcion_id=$inscripcion
							AND evolucion_id=$evolucion";
						
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo DatosRecienNacidos - GuardarDatosNacidos - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}
		
		function ErrorDB()
		{
			$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
			return $this->frmErrorBD;
		}
		
	}
?>