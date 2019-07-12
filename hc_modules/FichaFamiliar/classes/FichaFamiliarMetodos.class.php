<?php

class FichaFamiliarMetodos extends ConexionBD{
	
	//	Construcctor de la clase
	function FichaFamiliarMetodos(){
	
	}
	
	/**
	*	Funcion para consultar datos del paciente
	*/
	function ConsultarPaciente(){
	
		$sql .= "SELECT paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, 
				primer_nombre, segundo_nombre, fecha_nacimiento, residencia_direccion, 
				residencia_telefono, zona_residencia, sexo_id, tipo_estado_civil_id, 
				tipo_pais_id, tipo_dpto_id, tipo_mpio_id 
				FROM pacientes 
				WHERE tipo_id_paciente = 'CC' AND paciente_id = '29500379';";
	
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
    *Funcion con la que se consultan los familiares del paciente
    */
	function ConsultarFamiliar($difunto, $idPaciente){
	
// 		$sql .= "SELECT familiar_id, paciente_id, primer_apellido, segundo_apellido, 
// 					primer_nombre, segundo_nombre,  parentesco, fecha_nacim,  
// 					(SELECT edad(fecha_nacim)) as edad, sexo, 
// 					escolaridad, esquema_vacunas,  salud_bucal, rie_enf_disca, 
// 					hist_clinica, no_identi_fam, ocupacion, embarazada 
// 				FROM miembro_familiar; ";
		//$sql .= "WHERE paciente_id = '5465464';";

	$sql .= "SELECT  
	(CASE 
		WHEN (SELECT edad(fecha_nacim)) < 1 
			THEN 'MENOR 1 AÑO' 
		WHEN (SELECT edad(fecha_nacim)) >= 1 
			AND (SELECT edad(fecha_nacim)) <= 4 
			THEN '1 - 4 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 5 
			AND (SELECT edad(fecha_nacim)) <= 9 
			THEN '5 - 9 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 10 
			AND (SELECT edad(fecha_nacim)) <= 19 
			THEN '10 - 19 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 20 
			AND (SELECT edad(fecha_nacim)) <= 64 
			THEN '20 - 64 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) <= 65 
			THEN '65 AÑOS o MAS' 
		ELSE 'OTRA' 
	END) As rango, (SELECT edad(fecha_nacim)) as edad, familiar_id, paciente_id, 
			primer_apellido, segundo_apellido, primer_nombre, segundo_nombre,  
			parentesco, fecha_nacim, sexo, escolaridad, esquema_vacunas, 
			salud_bucal, rie_enf_disca, hist_clinica, no_identi_fam, ocupacion, 
			embarazada, edad_fallece, causa, tipo_identi_fam 
FROM miembro_familiar 
WHERE difunto = '".$difunto."' AND paciente_id = '".$idPaciente."' 
GROUP BY edad, familiar_id, paciente_id, 
			primer_apellido, segundo_apellido, primer_nombre, segundo_nombre,  
			parentesco, fecha_nacim, sexo, escolaridad, esquema_vacunas, 
			salud_bucal, rie_enf_disca, hist_clinica, no_identi_fam, ocupacion, 
			embarazada, edad_fallece, causa, tipo_identi_fam ;";

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
	*Funcion con la que se consultan los familiares en estado de 
	*embarazo del familiar
	*/
	function ConsultarFamiliarEmbzd(){

		$sql .= "SELECT primer_apellido, segundo_apellido, primer_nombre, embarazada_id, 
		miembro_familiar.familiar_id AS familiar_id, fecha_ult_menstruacion, 
		fecha_prob_parto, semanas_gesta, pri_dosis, seg_dosis, refuerzo_dosis, gestas, 
		partos, abortos, cesareas, ante_pato_obstre
		FROM miem_fam_embarazadas, miembro_familiar 
		WHERE miem_fam_embarazadas.familiar_id = miembro_familiar.familiar_id  ;";

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
    *Funcion con la que se insertan los familiares
    */
	function InsertarFamiliar($solicitud, $idPaciente){
	
 		$indice = array();
 		$sql = "SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq ";

		if(!$rst = $this->ConexionBaseDatos($sql))  return false;
		
		if(!$rst->EOF)
		{
		$indice = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();     
		}
		
		$rst->Close(); 
		
		$sqlerror = "SELECT SETVAL('miembro_familiar_familiar_id_seq', ".($indice['sq']-1).") ";  
		
 		$this->ConexionTransaccion();
		
		if($solicitud['difuntoFam'] == "1"){
		//if(false){
			$valor['priApell'] = $solicitud['priApellFam'];
			$valor['secApell'] = $solicitud['secApellFam']; 
			$valor['priNom'] = $solicitud['priNomFam']; 
			$valor['secNom'] = $solicitud['secNomFam']; 
			$valor['parent'] = $solicitud['parentFam']; 
			$valor['fechaNacim'] = "'".$solicitud['fechaNacim']."'"; 
			$valor['sexo'] = "'".$solicitud['sexoFam']."'"; 
			$valor['escolar'] = "".$solicitud['escolarFam']; 
			$valor['esqVac'] = "'".$solicitud['esqVacFam']."'";
			$valor['saludBucal'] = "'".$solicitud['saludBucalFam']."'"; 
			$valor['riesEnfDisc'] = "'".$solicitud['riesEnfDiscFam']."'";
			$valor['histClin'] = "'".$solicitud['histClinFam']."'";
			$valor['noIdenti'] = "'".$solicitud['noIdentiFam']."'";
			$valor['ocupa'] = "'".$solicitud['ocupacion_id']."'";
			$valor['embaraz'] = "'".$solicitud['embarazFam']."'";
			$valor['edadFalle'] = "NULL";
			$valor['causa'] = "NULL";
			$valor['difuntoFam'] = "'".$solicitud['difuntoFam']."'";
			$valor['tipoIdFam'] = "'".$solicitud['tipoIdentiFam']."'";
		}
		else{
			$valor['priApell'] = $solicitud['priApellFamMort'];
			$valor['secApell'] = $solicitud['secApellFamMort']; 
			$valor['priNom'] = $solicitud['priNomFamMort']; 
			$valor['secNom'] = $solicitud['secNomFamMort']; 
			$valor['parent'] = $solicitud['parentFamMort']; 
			$valor['fechaNacim'] = "NULL"; 
			$valor['sexo'] = "NULL";
			$valor['escolar'] = "NULL"; 
			$valor['esqVac'] = "NULL";
			$valor['saludBucal'] = "NULL"; 
			$valor['riesEnfDisc'] = "NULL";
			$valor['histClin'] = "NULL";
			$valor['noIdenti'] = "NULL";
			$valor['ocupa'] = "NULL";
			$valor['embaraz'] = "NULL";
			$valor['edadFalle'] = $solicitud['edadFalleFamMort'];
			$valor['causa'] = "'".$solicitud['causaFamMort']."'";
			$valor['difuntoFam'] = "'".$solicitud['difuntoFam']."'";
			$valor['tipoIdFam'] = "NULL";
		}

			$sql = "INSERT INTO miembro_familiar(
					familiar_id, 
					paciente_id, 
					primer_apellido, 
					segundo_apellido,
					primer_nombre,
					segundo_nombre,
					parentesco,
					fecha_nacim,
					sexo,
					escolaridad, 
					esquema_vacunas,
					salud_bucal,
					rie_enf_disca,  
					hist_clinica,
					no_identi_fam,
					ocupacion, 
					embarazada,
					edad_fallece,
					causa, 
					difunto,
					tipo_identi_fam
				)
				VALUES(
					".$indice['sq'].", 
					'".$idPaciente."', 
					'".$valor['priApell']."', 
					'".$valor['secApell']."', 
					'".$valor['priNom']."', 
					'".$valor['secNom']."', 
					'".$valor['parent']."', 
					".$valor['fechaNacim'].", 
					".$valor['sexo'].", 
					".$valor['escolar'].", 
					".$valor['esqVac'].", 
					".$valor['saludBucal'].", 
					".$valor['riesEnfDisc'].", 
					".$valor['histClin'].",
					".$valor['noIdenti'].",
					".$valor['ocupa'].",
					".$valor['embaraz'].",
					".$valor['edadFalle'].",
					".$valor['causa'].",
					".$valor['difuntoFam'].", 
					".$valor['tipoIdFam']."
				); ";


		//$strVal = $sql;
		
		if(!$rst = $this->ConexionTransaccion($sql))
		{
			if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
 			return false;      
 		}
		
 		$this->Commit();
		
		return true;

	}
	
	/**
	*	Funcion con la que se inserta una familiar en estado de embarazo
	*/
	function InsertarFamiliarEmbzd($solicitud, $idFamiliar){
	
		$indice = array();
		$sql = "SELECT NEXTVAL('miem_fam_embarazadas_embarazada_id_seq') AS sq ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))  return false;
		
		if(!$rst->EOF)
		{
		$indice = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();     
		}
		
		$rst->Close(); 
		
		$sqlerror = "SELECT SETVAL('miem_fam_embarazadas_embarazada_id_seq',  ".($indice['sq']-1).") ";    
		
		$this->ConexionTransaccion();

// 		$sql .= "INSERT INTO miem_fam_embarazadas(
// 					embarazada_id,
// 					familiar_id,
// 					fecha_ult_menstruacion,
// 					fecha_prob_parto,
// 					semanas_gesta,
// 					pri_dosis,
// 					seg_dosis,
// 					refuerzo_dosis,
// 					gestas,
// 					partos,
// 					abortos,
// 					cesareas,
// 					ante_pato_obstre
// 				)
// 				VALUES(
// 					(SELECT NEXTVAL('miem_fam_embarazadas_embarazada_id_seq') AS sq),
// 					26,
// 					'03/02/2008',
// 					'09/12/2008',
// 					4,
// 					'30/05/2008',
// 					'15/06/2008',
// 					'07/12/2008',
// 					2,
// 					3,
// 					1,
// 					4,
// 					'Antecedentes Patologicos Obstreticos'
// 				);";

		$sql = "INSERT INTO miem_fam_embarazadas(
					embarazada_id,
					familiar_id,
					fecha_ult_menstruacion,
					fecha_prob_parto,
					semanas_gesta,
					pri_dosis,
					seg_dosis,
					refuerzo_dosis,
					gestas,
					partos,
					abortos,
					cesareas,
					ante_pato_obstre
				)
				VALUES(
					".$indice['sq'].",
					".$solicitud['idFamiliar'].",
					'".$solicitud['fechaUltMenstr']."',
					'".$solicitud['fechaProbParto']."',
					".$solicitud['semGestac'].",
					'".$solicitud['priDosis']."',
					'".$solicitud['segDosis']."',
					'".$solicitud['rfzDosis']."',
					".$solicitud['agoGestas'].",
					".$solicitud['agoPartos'].",
					".$solicitud['agoAbortos'].",
					".$solicitud['agoCesareas'].",
					'".$solicitud['antPatObst']."'
				); ";

		if(!$rst = $this->ConexionTransaccion($sql))
		{
			if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
			return false;      
		}    
			
		$this->Commit();
		
		return $indice['sq'];
	}
	
	/**
	*	Funcion que lista los tipos de Comunidad
	*/
	function ObtenListComunidad(){
		$sql = "SELECT comunidad_id, descripcion FROM tipo_comunidad;";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
	
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
	*	Funcion que lista los tipos de GrupoCultural
	*/
	function ObtenListGrupCultu(){
		$sql = "SELECT grup_cult_id, descripcion FROM grupo_cultural;";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
	
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
    *	Funcion con la lista de los tipos de Intruccion
    */
    function ObtenListInstruccion(){
      $sql = "SELECT instruccion_id, descripcion  
              FROM tipo_instruccion;";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
    
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
	*	Funcion con la que se obtiene un tipo de Instruccion especifico
	*/
    function ObtenerInstruccion($idInst){
      $sql = "SELECT instruccion_id, descripcion  
              FROM tipo_instruccion
			  WHERE instruccion_id = ".$idInst." ;";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
    
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
    *	Funcion que con la que se listan los tipos de Ocupaciones
    */
    function ObtenListOcupacion(){
      $sql = "SELECT ocupacion_id, ocupacion_descripcion  
              FROM ocupaciones ;";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
    
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
	*	Funcion con la que se obtiene el nombre de la Ocupacion 
	*/
	function ObtenerOcupacion($ocupa)
	{
		$sql  = "SELECT	ocupacion_descripcion 
				FROM ocupaciones 
				WHERE ocupacion_id = '".$ocupa."' ; ";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
	
		$datos = array();
		if(!$rst->EOF)
		{
			$datos = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
	}
	
	/**
	*	Funcion en la que se listan los tipos de Parentescos
	*/
	function ObtenListParentesco(){
		$sql = "SELECT tipo_parentesco_id, descripcion 
				FROM tipos_parentescos;";

		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
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
	*	Funcion con la que se obtiene el nombre de el Parentesco
	*/
	function ObtenerParentesco($parent){
	
		$sql = "SELECT descripcion 
				FROM tipos_parentescos 
				WHERE tipo_parentesco_id = '".$parent."' ;";
	
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
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
	*	Funcion en la que se listan los TipoIdPaciente
	*/
	function ObtenListTipoIdPaciente(){
		$sql = "SELECT tipo_id_paciente, descripcion 
				FROM tipos_id_pacientes;";

		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
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
	*	Funcion que permite obtener la descripcion del TipoIdPaciente
	*/
	function ObtenerTipoIdPaciente($tipIdPacien){
	
		$sql = "SELECT descripcion  
				FROM tipos_id_pacientes 
				WHERE tipo_id_paciente = '".$tipIdPacien."' ;";
	
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
		$datos = array();
		while(!$rst->EOF)
		{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		$rst->Close();
		
		return $datos; 
	
	}
	

// 	function ObtenNumFichFamil(){
// 		$indice = array();
// 		$sql = "SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq ";
// 		
// 		if(!$rst = $this->ConexionBaseDatos($sql))  return false;
// 		
// 		if(!$rst->EOF)
// 		{
// 		$indice = $rst->GetRowAssoc($ToUpper = false);
// 		$rst->MoveNext();     
// 		}
// 		
// 		$rst->Close(); 
// 		
// 		$sqlerror = "SELECT SETVAL('ficha_familar_num_ficha_fam_seq', ".($indice['sq']-1).") ";    
// 		
// 		$this->ConexionTransaccion();
// 		
// 		if(!$rst = $this->ConexionTransaccion($sql))
// 		{
// 			if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
// 			return false;      
// 		}    
// 			
// 		$this->Commit();
// 		
// 		return $indice['sq'];
// 	}
	
	/**
	*	Funcion para obtener el nombre de un usuario, apartir de su ID
	*/
	function ObtenUsuario(){
		
		$sql = "SELECT usuario_id, nombre 
				FROM system_usuarios 
				WHERE usuario_id = ".UserGetUID()."; ";
				
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
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
	*	Funcion para obtener el numero de la ficha familiar
	*/
	function ObtenNumFichFamil(){
	
		$sql = "SELECT SETVAL('ficha_familar_num_ficha_fam_seq', ((SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq)-1));";
		
		//$sql = "SELECT (SETVAL('ficha_familar_num_ficha_fam_seq', ((SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq)-1))+1) AS n_fi_fam;";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
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
	*	Funcion que permite Insertar una Ficha Familiar 
	*/
	function InsertarFichaFamil($solicitud){
		
		$indice = array();
		$sql = "SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))  return false;
		
		if(!$rst->EOF)
		{
		$indice = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();     
		}
		
		$rst->Close(); 
		
		$sqlerror = "SELECT SETVAL('ficha_familar_num_ficha_fam_seq', ".($indice['sq']-1).") ";  
		
		$this->ConexionTransaccion();
		
		if(empty($solicitud['latitud']))
			$solicitud['latitud'] = "NULL";
		
		if(empty($solicitud['longitud']))
			$solicitud['longitud'] = "NULL";
		
		if(empty($solicitud['altitud']))
			$solicitud['altitud'] = "NULL";
			
		if(empty($solicitud['numFamilia']))
			$solicitud['numFamilia'] = "NULL";
			
		$sql = "INSERT INTO ficha_familar(
					num_ficha_fam,
					paciente_id,
					cod_respon,
					fecha_llenado,
					num_carpeta,
					latitud,
					longitud,
					altitud,
					comunidad,
					grup_cultu,
					nom_comp_jefe_fam,
					num_familia 
				)VALUES(
					".$indice['sq'].",
					'".$solicitud['paciente_id']."',
					".UserGetUID().",
					'".$solicitud['fechaLlena']."',
					".$solicitud['noCarpeta'].",
					".$solicitud['latitud'].",
					".$solicitud['longitud'].",
					".$solicitud['altitud'].",
					'".$solicitud['comunidad']."',
					'".$solicitud['grupCult']."',
					'".$solicitud['nombApellJefeFam']."',
					".$solicitud['numFamilia']." 
				);";
				
		if(!$rst = $this->ConexionTransaccion($sql))
		{
			if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
			return false;      
		}    
			
		$this->Commit();
		
		return true;
				
	}
	
	/**
	*	Funcion que permite Modificar si una Familiar se encuentra embarazada o no
	*/
	function ModificarFamiliEmbzd($idFamiliar, $val){
		
		$this->ConexionTransaccion();
		
		$sql = "UPDATE miembro_familiar SET 
					embarazada = '".$val."' 
				WHERE familiar_id = ".$idFamiliar." ;";

		//var_dump($sql);
				
		if(!$rst = $this->ConexionTransaccion($sql)) return false;
		
		$this->Commit();
		
		return true;
	
	}
}
?>