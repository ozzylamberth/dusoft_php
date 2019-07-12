<?php

/**
 * $Id: app_AsignacionCitasTipoPlan_user.php,v 1.2 2008/05/28 15:14:05 juanpablo Exp $
 */

class app_AsignacionCitasTipoPlan_user extends classModulo
{
	var $limit;
        var $conteo;

	function app_AsignacionCitasTipoPlan_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
    
	function main()
	{
		$this->FormaTiposPlanesCitas();
		return true;
	}
	
	function GetDefaultCitasTiposPlanes()
	{
		list($dbconn) = GetDBconn();
        	$query="SELECT *
        	FROM citas_tipo_plan WHERE tipo_id_tercero ='CC' AND tercero_id = '01'";
        	$result = $dbconn->Execute($query);
        	if ($dbconn->ErrorNo() != 0) {
            		$this->error = "Error al Cargar el Modulo";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            		return false;
        	}else{
            		$datos=$result->RecordCount();
            		if($datos){
        			$i=0;
				while (!$result->EOF)
				{
					$vars[$i][0]=$result->fields[1];
					$vars[$i][1]=$result->fields[2];
					$i++;
					$result->MoveNext();
				}
            		}
        	}
        	$result->Close();
        	return $vars;
	
	
	
	}
	function GetTiposPlanes()
	{
        	list($dbconn) = GetDBconn();
        	$query="SELECT *
        	FROM tipos_planes";
        	$result = $dbconn->Execute($query);
        	if ($dbconn->ErrorNo() != 0) {
            		$this->error = "Error al Cargar el Modulo";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            		return false;
        	}else{
            		$datos=$result->RecordCount();
            		if($datos){
        			$i=0;
				while (!$result->EOF)
				{
					$vars[0][$i]=$result->fields[0];
					$vars[1][$i]=$result->fields[1];
					$i++;
					$result->MoveNext();
				}
            		}
        	}
        	$result->Close();
        	return $vars;
	}
	function GuardarCitasTiposPlanesProfesionales($cantidades,$profe1,$profe2,$profe3)
	{
			$tipos_planes = $this->GetTiposPlanes();
			list($dbconn) = GetDBconn();
			     /////////////////////////////////////////////////////////////////////
			////Determina si el profesional tiene realcionado citastiposplanes X dpto///
			    /////////////////////////////////////////////////////////////////////
			$consulta = $this->GetCitasTiposPlanes_ProfesionalDpto($profe1,$profe2,$profe3);
			if($consulta){
				
				$i=0;
				while(!$consulta->EOF){
					$query="UPDATE citas_tipo_plan SET cantidad_citas='".$cantidades[$i]."' 
					WHERE tipo_plan = '".$tipos_planes[0][$i]."' 
					AND tipo_id_tercero='".$consulta->fields[3]."'
					AND tercero_id='".$consulta->fields[4]."'
					AND departamento_id='".$consulta->fields[7]."'";
					
					$result = $dbconn->Execute($query);
        				if ($dbconn->ErrorNo() != 0) {
           					$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            					return false;
        				}else{
						$result->Close();
					}
					$consulta->MoveNext();
					$i++;
				}
				$consulta->Close();
				return true;
			}
			else{
				
				
        			$query="INSERT INTO citas_tipo_plan (tipo_plan, cantidad_citas, tipo_id_tercero, tercero_id, fecha_registro,
				usuario_id, departamento_id)
				VALUES('".$tipos_planes[0][0]."',".$cantidades[0].",'".$profe1."','".$profe2."',now(),'".UserGetUID()."','".$profe3."');";
        			$result = $dbconn->Execute($query);
        			if ($dbconn->ErrorNo() != 0) {
           				$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            				return false;
        			}
				else{
					$query="INSERT INTO citas_tipo_plan (tipo_plan, cantidad_citas, tipo_id_tercero, tercero_id, fecha_registro,
						usuario_id, departamento_id)
						VALUES('".$tipos_planes[0][1]."',".$cantidades[1].",'".$profe1."','".$profe2."',now(),'".UserGetUID()."','".$profe3."');";	
					$result = $dbconn->Execute($query);
        				if ($dbconn->ErrorNo() != 0) {
           					$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            					return false;
        				}
					else{
						$query="INSERT INTO citas_tipo_plan (tipo_plan, cantidad_citas, tipo_id_tercero, tercero_id, fecha_registro,
							usuario_id, departamento_id)
							VALUES('".$tipos_planes[0][2]."',".$cantidades[2].",'".$profe1."','".$profe2."',now(),'".UserGetUID()."','".$profe3."');";	
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
           						$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            						return false;
        					}
						else{
							$query="INSERT INTO citas_tipo_plan (tipo_plan, cantidad_citas, tipo_id_tercero, tercero_id, fecha_registro,
								usuario_id, departamento_id)
								VALUES('".$tipos_planes[0][3]."',".$cantidades[3].",'".$profe1."','".$profe2."',now(),'".UserGetUID()."','".$profe3."');";	
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
           							$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            							return false;
        						}
							else{
								return true;				
							}
							
						}
						
					}
				}	
			}
			//////////////////////////////////////////////////////
			//////////////////////////////////////////////////////
	
	}
	
	
	function GuardarCitasTiposPlanes()
	{
		$sqlcomplemento =SessionGetVar('sqlcomplemento');
			
			
		if($_REQUEST['DefaultAll']){
			$this->offset= $_REQUEST['offset1'];
			$canti= array($_REQUEST['c1'],$_REQUEST['c2'],$_REQUEST['c3'],$_REQUEST['c4']);
			$profesionales = $this->ConsultaProfesionales($sqlcomplemento); 
			while(!$profesionales->EOF){
				if(!$this->GuardarCitasTiposPlanesProfesionales($canti,$profesionales->fields[0],$profesionales->fields[1],$profesionales->fields[3])){
					$msg="E2: DE REGISTRO".$profesionales->fields[0]."-".$profesionales->fields[1]."-".$profesionales->fields[3];
				}
				else{
					$msg="DATOS GUARDADOS SATISFACTORIAMENTE";
				}
				$profesionales->MoveNext();
			}
			$profesionales->Close();
			
		}
		elseif($_REQUEST['profesional']){
			$profesional = explode("-",$_REQUEST['profesional']);
			$canti = array($_REQUEST['c1'],$_REQUEST['c2'],$_REQUEST['c3'],$_REQUEST['c4']);
			
			if($this->GuardarCitasTiposPlanesProfesionales($canti,$profesional[0],$profesional[1],$profesional[2])){
				$msg="DATOS GUARDADOS SATISFACTORIAMENTE";
			}
			else{
				$msg="E1: ERROR DE REGISTRO".$_REQUEST['profresional'];
			}
			
		}
		
		$this->FormaTiposPlanesCitas($msg,$_REQUEST['offset1'],$sqlcomplemento);
		
		return true;
	}
	
	function GetCitasTiposPlanes_ProfesionalDpto($TipoDocumento,$DocumentoId,$Departamento)
	{
        	list($dbconn) = GetDBconn();
        	$query="SELECT * FROM citas_tipo_plan WHERE tipo_id_tercero='".$TipoDocumento."' AND tercero_id='".$DocumentoId."' AND departamento_id='".$Departamento."';";
        	$result = $dbconn->Execute($query);
        	if ($dbconn->ErrorNo() != 0) {
           		$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            		return false;
        	}else{
            		$datos=$result->RecordCount();
			if($datos)
				return $result;
			else
				return false;	
            		
        	}
        	
        	
	}	
	
	function ConsultaProfesionales($sql="")
	{
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'])){
		$query = "SELECT a.tipo_id_tercero, a.tercero_id, a.nombre, b.departamento, b.descripcion FROM 
			 	  profesionales AS a, departamentos AS b, profesionales_especialidades AS c, tipos_consulta as d WHERE
				  ".$sql."
			 	  a.tipo_id_tercero = c.tipo_id_tercero AND a.tercero_id = c.tercero_id AND 
			 	  b.departamento = d.departamento AND c.especialidad = d.especialidad ORDER BY a.nombre ASC";
				$result = $dbconn->Execute($query);
				list($this->conteo)=$result->RecordCount();
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo [citas_tipo_plan]";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
      			SessionSetVar("conteo",$result->RecordCount());
			$this->conteo=$result->RecordCount();

		}
		else{
      			$this->conteo=$_REQUEST['conteo'];
        	}
		$query = "SELECT a.tipo_id_tercero, a.tercero_id, a.nombre, b.departamento, b.descripcion FROM 
			 	  profesionales AS a, departamentos AS b, profesionales_especialidades AS c, tipos_consulta as d WHERE
				  ".$sql."
			 	  a.tipo_id_tercero = c.tipo_id_tercero AND a.tercero_id = c.tercero_id AND 
			 	  b.departamento = d.departamento AND c.especialidad = d.especialidad ORDER BY a.nombre ASC LIMIT " . $this->limit . " OFFSET ".$this->offset." ";
				$result = $dbconn->Execute($query);
				list($this->conteo)=$result->RecordCount();
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo [citas_tipo_plan]";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
		
		return $result;
	}
	
	
	function InsertarDatosCitas()
	{
        	
		list($dbconn) = GetDBconn();
		for($i=0; $i<$_REQUEST[vector]; $i++)
		{
			$query="SELECT count(*)
			FROM citas_tipo_plan
			WHERE tipo_plan = $i
			-- AND fecha = '".date('Y-m-d')."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
				return false;
			}
			if($_REQUEST[$i] AND $result->fields[0]==0)
			{
				$query="INSERT INTO citas_tipo_plan VALUES ('".$i."',".$_REQUEST[$i].",'now()','".date('Y-m-d')."',".UserGetUID().");";
						//echo '<br>';
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Insertar en citas_tipo_plan";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
				return false;
				}
			}
			elseif($_REQUEST[$i] AND $result->fields[0]>0)
			{
				$query="UPDATE citas_tipo_plan SET cantidad_citas = ".$_REQUEST[$i].", fecha_registro = 'now()',fecha = '".date('Y-m-d')."',usuario_id =".UserGetUID()."
				WHERE tipo_plan = $i
				-- AND fecha = '".date('Y-m-d')."'
				";
				//echo '<br>';
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Actualizar en citas_tipo_plan";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
			}
		}
		$msg = 'DATOS GUARDADOS';
        	$this->FormaTiposPlanesCitas($msg);
        	return true;
	}	
}//fin clase user

?>




