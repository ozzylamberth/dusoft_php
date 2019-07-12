<?php

	class EvolucionesNotasMetodos extends ConexionBD{
		
		//Contructor de la clase
		function EvolucionesNotasMetodos(){
		
		}
		
		/**
		*	Funcion para consultar las notas de Evolucion
		*/
		function ConsultarNotasEvoluc($evoluId, $ingrId){
			
			$sql .= "SELECT id_nota_evol, fecha, txt_nota_evol, usuario_id, evolucion_id, ingreso  
					FROM notas_evolucion ";
			
			if($evoluId)
				$sql .= "WHERE evolucion_id = ".$evoluId."; ";
			else
				$sql .= "WHERE ingreso = ".$ingrId."; ";

			if(!$rst = $this->ConexionBaseDatos($sql))  return false;
			
			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			} 
			
			$rst->Close();
			return $datos; 
		
		}
		
		
		/**
		*	Funcion para Consultar el nombre del profesional 
		*/
		function ConsulProfesional($usua_id){
		
			$sql .= "SELECT usuario_id, nombre 
					FROM profesionales 
					WHERE usuario_id = ".$usua_id." 
					; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))  return false;
			
			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			} 
			
			$rst->Close();
			return $datos; 
		}
		
		/**
		*	Funcion para Consultar el nombre de la Entidad
		*/
		function ConsulEntidad($empre_id){
			
			$sql .= "SELECT empresa_id, razon_social 
					FROM empresas 
					WHERE empresa_id = '".$empre_id."' 
					; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))  return false;
			
			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			} 
			
			$rst->Close();
			return $datos; 
		}
		
		/**
		*	Funcion que sirve para insertar un Nota de Evolucion
		*/
		function InsertarNotasEvoluc($solicitud, $empreId, $usuarId, $evoluId, $pacientId, $tipoPacientId, $ingr){
		
			$indice = array();
			$sql = "SELECT NEXTVAL('notas_evolucion_id_nota_evol_seq') AS sq ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))  return false;
			
			if(!$rst->EOF)
			{
				$indice = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();     
			}
			
			$rst->Close(); 
			
			$sqlerror = "SELECT SETVAL('notas_evolucion_id_nota_evol_seq', ".($indice['sq']-1).") ";  
			
			$this->ConexionTransaccion();
			
			$sql = "INSERT INTO notas_evolucion(
						id_nota_evol,
						fecha, 
						txt_nota_evol, 
						empresa_id,  
						usuario_id,
						evolucion_id,
						paciente_id,
						tipo_id_paciente,
						ingreso 
					) 
					VALUES(
						".$indice['sq'].", 
						NOW(), 
						'".$solicitud['txtNotEvolu']."', 
						'".$empreId."', 
						".$usuarId.",
						".$evoluId.",
						'".$pacientId."',
						'".$tipoPacientId."',
						".$ingr." 
					); "; 
			
			//var_dump($sql);
			
			//echo "".$sql;
			
			//$strSql =  $sql;
			
			if(!$rst = $this->ConexionTransaccion($sql))
			{
				if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
				return false;      
			}
			
			$this->Commit();
			
			return true;
		}
	}

?>