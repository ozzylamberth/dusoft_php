<?php

// app_Hospitalizacion_user.php  17/10/2003
// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware tda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------
// Autor: Lorena Aragon
// Proposito del Archivo: Manejo logico de la admision de Hospitalizacion  de los pacientes.
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la admision de Hospitalizacion de los pacientes
*/

class app_Hospitalizacion_user extends classModulo
{
  //$empresa=$_SESSION['SYSTEM_EMPRESA'];
  //$Departamento=$_SESSION['SYSTEM_DEPARTAMENTO'];
	//$CentroUtilidad=$_SESSION['SYSTEM_CENTRO_UTILIDAD'];
	var $Departamento='01';//Variable de ambiente que tiene el numero del departamento
  var $SW='0'; //Determina '0' si se debe tener en cuenta los departamentos o '1' si todas la areas de hospitalizacion
	var $Historia_clinica_externa='1';//indica si la ips utiliza el numero de historia clinica diferente del documento

	function app_Hospitalizacion_user()
	{
    return true;
	}

	/**
	*La funcion main es la principal y donde se llama ListadoAdmisionHospitalizacion de los
	pacientes pendientes por hospitalizacion
	*/

	function main()
	{
			unset($_SESSION['EMPRESA']);
			unset($_SESSION['CENTROUTILIDAD']);
			$SystemId=UserGetUID();
			list($dbconn) = GetDBconn();

			$query = "select b.tipo_admision_id, b.descripcion as descadmon, c.empresa_id,
								c.centro_utilidad, b.sw_todos_cu, d.razon_social, e.descripcion,
								b.punto_admision_id, b.sw_triage, b.departamento, c.descripcion as descdpto,
								f.unidad_funcional, f.descripcion as decunid
								from puntos_admisiones_usuarios as a, puntos_admisiones as b,
								departamentos as c, empresas as d, centros_utilidad as e,
								unidades_funcionales as f
								where a.usuario_id=$SystemId and b.tipo_admision_id='HS'
								and a.punto_admision_id=b.punto_admision_id and b.departamento=c.departamento
								and d.empresa_id=c.empresa_id and c.empresa_id=e.empresa_id
								and c.centro_utilidad=e.centro_utilidad and e.empresa_id=f.empresa_id
								and e.centro_utilidad=f.centro_utilidad and c.unidad_funcional=f.unidad_funcional
								order by f.empresa_id, f.centro_utilidad, f.unidad_funcional";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while($data = $resulta->FetchRow())
			{
							$vect[$data[5]][$data[6]][$data[12]][$data[10]][$data[1]]=1;//cant de admisiones
							$emp[$data[5]]+=1; //cant de empresas
							$cu[$data[5]][$data[6]]+=1; //cant de centros utilidad
							$dpto[$data[5]][$data[6]][$data[10]][$data[12]]+=1; //cant de deptos
							$unid[$data[5]][$data[6]][$data[12]]+=1; //cant de unidades
			}
	/*		$query = " select b.tipo_admision_id, b.descripcion as descadmon, b.empresa_id, b.centro_utilidad, b.sw_todos_cu,
									c.razon_social, d.descripcion
									from puntos_admisiones_usuarios as a, puntos_admisiones as b, empresas as c, centros_utilidad as d
									where a.usuario_id=$SystemId and b.tipo_admision_id='HS' and a.punto_admision_id=b.punto_admision_id
									and b.empresa_id=c.empresa_id and c.empresa_id=d.empresa_id and b.centro_utilidad=d.centro_utilidad";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while($data = $resulta->FetchRow())
			{
							$vect[$data[5]][$data[6]][$data[1]]=1;//cant de admisiones
							$emp[$data[5]]+=1; //cant de empresas
							$cu[$data[5]][$data[6]]+=1; //cant de centros utilidad
			}*/
			$resulta=$dbconn->Execute($query);
			$i=0;
			while(!$resulta->EOF)
			{
					$arreglo[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
			}
			$resulta->Close();

			if(!$this->FormaElegirAdmision($vect,$emp,$cu,$arreglo)){
					return false;
			}
			return true;

  }


	function LlamaListado(){

    if(!$this->ListadoAdmisionHospitalizacion($arreglo,'','','','')){
        return false;
    }
		return true;
  }



 /**
	*La funcion tipo_id_paciente se encarga de obtener de la base de datos
	*los diferentes tipos de identificacion de los paciente
	*/

	function tipo_id_paciente(){

		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'paciente' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
/*
La funcion BuscarPacientesOrdenes busca los pacientes pendientes por hospitalizacion ya sea
teniendo en cuenta los departamentos o de forma general
*/

	function BuscarPacientesOrdenes(){

	  list($dbconn) = GetDBconn();
    //Tiene en cuenta departamento variabel de ambiente
		if($this->SW==0){
		  $query = "SELECT b.orden_hospitalizacion_id,b.tipo_orden_id,b.fecha_orden,b.fecha_programacion,b.tipo_id_paciente,b.paciente_id FROM (SELECT orden_hospitalizacion_id FROM ordenes_hospitalizacion WHERE hospitalizado='0' AND departamento='$this->Departamento' EXCEPT SELECT orden_hospitalizacion_id FROM pendientes_x_hospitalizar) as a,ordenes_hospitalizacion as b WHERE a.orden_hospitalizacion_id=b.orden_hospitalizacion_id";
		  $result = $dbconn->Execute($query);
    //seleccion General
		}else{
		  $query = "SELECT b.orden_hospitalizacion_id,b.tipo_orden_id,b.fecha_orden,b.fecha_programacion,b.tipo_id_paciente,b.paciente_id FROM (SELECT orden_hospitalizacion_id FROM ordenes_hospitalizacion WHERE hospitalizado='0' EXCEPT SELECT orden_hospitalizacion_id FROM pendientes_x_hospitalizar) as a,ordenes_hospitalizacion as b WHERE a.orden_hospitalizacion_id=b.orden_hospitalizacion_id";
		  $result = $dbconn->Execute($query);
		}
		if ($dbconn->ErrorNo() != 0) {
			  $this->error = "Error al Cargar el Modulo";
			  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			  return false;
		}else{
			$i=0;
			while (!$result->EOF) {
				$arr[$i]=$result->fields[0]."*".$result->fields[1]."*".$result->fields[2]."*".$result->fields[3]."*".$result->fields[4]."*".$result->fields[5];
				$i++;
				$result->MoveNext();
			}
		}
 		return $arr;
		$result->Close();
  }

	/**
	*La funcion BuscarNombresPaciente se encarga de buscar en la base de datos
	*los nombres de los pacientes
	*/

  function BuscarNombresPaciente($tipo,$documento){

	  list($dbconn) = GetDBconn();
	  $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
	  $result = $dbconn->Execute($query);

	  if ($dbconn->ErrorNo() != 0) {
	    $this->error = "Error al Cargar el Modulo";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
	  }else{
      if($result->EOF){
		    $this->error = "Error al Cargar el Modulo";
			  $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
			  return false;
		  }
	  }

	  $Nombres=$result->fields[0]." ".$result->fields[1];
	  $result->Close();
	  return $Nombres;
  }

	/**
	*La funcion BuscarApellidosPaciente se encarga de buscar en la base de datos
	*los apellidos de los pacientes
	*/

	function BuscarApellidosPaciente($tipo,$documento){

		list($dbconn) = GetDBconn();
		$query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'paciente' esta vacia ";
				return false;
			}
		}
		$result->Close();
		$Apellidos=$result->fields[0]." ".$result->fields[1];

		return $Apellidos;
	}

/*
La funcion BuscarEvolucion busca el numero de la evolucion de la orden de hospitalizacion
de un paciente a partir del numero de la orden

*/
	function BuscarEvolucion($numeroOrden){
    list($dbconn) = GetDBconn();
		$query = "SELECT evolucion_id FROM ordenes_hospitalizacion_internas WHERE orden_hospitalizacion_id='$numeroOrden'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->EOF){
			  $this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'ordenes_hospitalizacion_internas' esta vacia ";
				return false;
			}
		}
		$evolucion=$result->fields[0];
		return $evolucion;
		$result->Close();
	}

	/*
  La funcion BuscarProfesional busca los datos del profesional a cargo de la orden de hospitalizacion
	de un paciente
	*/
	function BuscarProfesional($evolucion){
    list($dbconn) = GetDBconn();
		$query = "SELECT profesional.profesional_id,profesional.especialidad_id FROM hc_evoluciones,profesional WHERE hc_evoluciones.evolucion_id='$evolucion' AND hc_evoluciones.profesional_id=profesional.profesional_id";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->EOF){
			  $this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesional o hc_evoluciones' esta vacia ";
				return false;
			}
		}
		$result->Close();
		$profesional=$result->fields[0].'-'.$result->fields[1];
		return $profesional;
	}

/*
La funcion TraerPersonaParaAdmision Busca a una persona a partir de sus datos principales
en las ordenes de hospitalizacion para identificar si esta se encuentra en el listado
de las ordenes pendientes
*/
	function TraerPersonaParaAdmision(){

		$PacienteId=$_REQUEST['PacienteId'];
    $TipoId=$_REQUEST['TipoId'];
    $Buscar=$_REQUEST['Buscar'];
		$BuscarCompleto=$_REQUEST['BuscarCompleto'];
		$Cancelar=$_REQUEST['Cancelar'];
		$Crear=$_REQUEST['Crear'];
		$Busqueda=$_REQUEST['TipoBusqueda'];
    $Aceptar=$_REQUEST['Aceptar'];
    $TipoBuscar=$_REQUEST['TipoBuscar'];
    list($dbconn) = GetDBconn();
    //Busqueda solo de las areas de hospitalizacion de un departamento
		if($Buscar){
			if($TipoBuscar=='1'){
				$TipoDocumento=$_REQUEST['TipoDocumento'];
        $Documento=$_REQUEST['Documento'];
				if($TipoDocumento!='AS' && $TipoDocumento!='MS' && $Documento==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
		      $query = "SELECT orden_hospitalizacion_id,tipo_orden_id,fecha_orden,fecha_programacion,tipo_id_paciente,paciente_id FROM ordenes_hospitalizacion WHERE hospitalizado='0' AND departamento='$this->Departamento' AND tipo_id_paciente='$TipoDocumento' AND  paciente_id='$Documento'";
		      $result = $dbconn->Execute($query);
				}
		  }elseif($TipoBuscar=='2'){
				$nombres=$_REQUEST['nombres'];
        $apellidos=$_REQUEST['apellidos'];
        if($nombres=="" && $apellidos==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
				  $apellidos=strtoupper($apellidos);
          $nombres=strtoupper($nombres);
          if($apellidos!="" && $nombres==""){
            $listaApellidos = explode(" ", $apellidos);
            if($listaApellidos[0] != "" && $listaApellidos[1] != "" ){
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion  WHERE hola.x='$apellidos' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
						  $result = $dbconn->Execute($query);
            }else{
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion WHERE hola.x LIKE '$apellidos%' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
						  $result = $dbconn->Execute($query);
            }
			    }elseif($nombres!="" && $apellidos==""){
            $listaNombres = explode(" ",$nombres);
            if($listaNombres[0]!= "" && $listaNombres[1]!= "" ){
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_nombre||' '||segundo_nombre as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion  WHERE hola.x='$nombres' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
						  $result = $dbconn->Execute($query);
            }else{
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_nombre||' '||segundo_nombre as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion WHERE hola.x LIKE '$nombres%' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
						  $result = $dbconn->Execute($query);
				    }
          }elseif($nombres!="" && $apellidos!=""){
            $listaApellidos = explode(" ", $apellidos);
            $listaNombres = explode(" ",$nombres);
            if($listaApellidos[0] != "" && $listaApellidos[1] != "" && $listaNombres[0]!= "" && $listaNombres[1]!= ""){
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x, primer_nombre||' '||segundo_nombre as y,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion  WHERE hola.x='$apellidos' AND hola.y='$nombres' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
						  $result = $dbconn->Execute($query);
            }else{
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x, primer_nombre||' '||segundo_nombre as y,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion WHERE hola.x LIKE '$apellidos%' AND hola.y LIKE '$nombres%' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
						  $result = $dbconn->Execute($query);
            }
				  }
				}
			}elseif($TipoBuscar=='3'){
        $NumCuenta=$_REQUEST['NumCuenta'];
				if($NumCuenta==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
          $query = "SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM cuentas,hc_evoluciones,ordenes_hospitalizacion_internas WHERE cuentas.numerodecuenta='$NumCuenta' AND cuentas.ingreso=hc_evoluciones.ingreso AND cuentas.ingreso=ingresos.ingreso AND ingresos.estado='1' AND hc_evoluciones.evolucion_id=ordenes_hospitalizacion_internas.evolucion_id AND ordenes_hospitalizacion_internas.orden_hospitalizacion_id=ordenes_hospitalizacion.orden_hospitalizacion_id AND  ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
		      $result = $dbconn->Execute($query);
				}
			}elseif($TipoBuscar=='4'){
        $prefijo=$_REQUEST['prefijo'];
        $numerohistoria=$_REQUEST['numerohistoria'];
				if($prefijo=="" && $numerohistoria==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
				  if($this->Historia_clinica_externa){
		        $query = "SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM historias_clinicas,ordenes_hospitalizacion WHERE historias_clinicas.historia_prefijo='$prefijo' AND historias_clinicas.historia_numero='$numerohistoria' AND historias_clinicas.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND  historias_clinicas.paciente_id=ordenes_hospitalizacion.paciente_id AND ordenes_hospitalizacion.hospitalizado='0' AND ordenes_hospitalizacion.departamento='$this->Departamento'";
		        $result = $dbconn->Execute($query);
					}
				}
			}
		//Busqueda solo de las areas de hospitalizacion de la ips
		}elseif($BuscarCompleto){
      if($TipoBuscar=='1'){
			  $TipoDocumento=$_REQUEST['TipoDocumento'];
        $Documento=$_REQUEST['Documento'];
				if($TipoDocumento!='AS' && $TipoDocumento!='MS' && $Documento==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
		      $query = "SELECT orden_hospitalizacion_id,tipo_orden_id,fecha_orden,fecha_programacion,tipo_id_paciente,paciente_id FROM ordenes_hospitalizacion WHERE hospitalizado='0' AND tipo_id_paciente='$TipoDocumento' AND  paciente_id='$Documento'";
		      $result = $dbconn->Execute($query);
				}
			}elseif($TipoBuscar=='2'){
        $nombres=$_REQUEST['nombres'];
        $apellidos=$_REQUEST['apellidos'];
        if($nombres=="" && $apellidos==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
				  $apellidos=strtoupper($apellidos);
          $nombres=strtoupper($nombres);
          if($apellidos!="" && $nombres==""){
            $listaApellidos = explode(" ", $apellidos);
            if($listaApellidos[0] != "" && $listaApellidos[1] != "" ){
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion  WHERE hola.x='$apellidos' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0'";
						  $result = $dbconn->Execute($query);
            }else{
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion WHERE hola.x LIKE '$apellidos%' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0'";
						  $result = $dbconn->Execute($query);
            }
			    }elseif($nombres!="" && $apellidos==""){
            $listaNombres = explode(" ",$nombres);
            if($listaNombres[0]!= "" && $listaNombres[1]!= "" ){
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_nombre||' '||segundo_nombre as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion  WHERE hola.x='$nombres' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0'";
						  $result = $dbconn->Execute($query);
            }else{
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_nombre||' '||segundo_nombre as x,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion WHERE hola.x LIKE '$nombres%' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0'";
						  $result = $dbconn->Execute($query);
				    }
          }elseif($nombres!="" && $apellidos!=""){
            $listaApellidos = explode(" ", $apellidos);
            $listaNombres = explode(" ",$nombres);
            if($listaApellidos[0] != "" && $listaApellidos[1] != "" && $listaNombres[0]!= "" && $listaNombres[1]!= ""){
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x, primer_nombre||' '||segundo_nombre as y,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion  WHERE hola.x='$apellidos' AND hola.y='$nombres' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0'";
						  $result = $dbconn->Execute($query);
            }else{
              $query="SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM (SELECT primer_apellido||' '||segundo_apellido as x, primer_nombre||' '||segundo_nombre as y,paciente_id,tipo_id_paciente from pacientes) as hola,ordenes_hospitalizacion WHERE hola.x LIKE '$apellidos%' AND hola.y LIKE '$nombres%' AND hola.paciente_id=ordenes_hospitalizacion.paciente_id AND hola.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND ordenes_hospitalizacion.hospitalizado='0'";
						  $result = $dbconn->Execute($query);
            }
				  }
				}
			}elseif($TipoBuscar=='3'){
				$NumCuenta=$_REQUEST['NumCuenta'];
				if($NumCuenta==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
          $query = "SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM cuentas,hc_evoluciones,ordenes_hospitalizacion_internas WHERE cuentas.numerodecuenta='$NumCuenta' AND cuentas.ingreso=hc_evoluciones.ingreso AND cuentas.ingreso=ingresos.ingreso AND ingresos.estado='1' AND hc_evoluciones.evolucion_id=ordenes_hospitalizacion_internas.evolucion_id AND ordenes_hospitalizacion_internas.orden_hospitalizacion_id=ordenes_hospitalizacion.orden_hospitalizacion_id AND  ordenes_hospitalizacion.hospitalizado='0'";
		      $result = $dbconn->Execute($query);
				}
			}elseif($TipoBuscar=='4'){
        $prefijo=$_REQUEST['prefijo'];
        $numerohistoria=$_REQUEST['numerohistoria'];
				if($prefijo=="" && $numerohistoria==""){
				  $bandera='2';
          $this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		      return true;
				}else{
				  if($this->Historia_clinica_externa){
				    $TipoDocumento=$_REQUEST['TipoDocumento'];
            $Documento=$_REQUEST['Documento'];
		        $query = "SELECT ordenes_hospitalizacion.orden_hospitalizacion_id,ordenes_hospitalizacion.tipo_orden_id,ordenes_hospitalizacion.fecha_orden,ordenes_hospitalizacion.fecha_programacion,ordenes_hospitalizacion.tipo_id_paciente,ordenes_hospitalizacion.paciente_id FROM historias_clinicas,ordenes_hospitalizacion WHERE historias_clinicas.historia_prefijo='$prefijo' AND historias_clinicas.historia_numero='$numerohistoria' AND historias_clinicas.tipo_id_paciente=ordenes_hospitalizacion.tipo_id_paciente AND  historias_clinicas.paciente_id=ordenes_hospitalizacion.paciente_id AND ordenes_hospitalizacion.hospitalizado='0'";
		        $result = $dbconn->Execute($query);
				  }
				}
			}
		}elseif($Cancelar){
      $this->ListadoAdmisionHospitalizacion('','','','','');
		  return true;
		}elseif($Crear){
			$this->BuscarPaciente($TipoId,$PacienteId);
		  return true;
		}elseif($Aceptar){
			$this->ListadoAdmisionHospitalizacion('','','','',$Busqueda);
		  return true;
		}
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->EOF){
				 $bandera='1';
      }
			$arreglo[0]=$result->fields[0]."*".$result->fields[1]."*".$result->fields[2]."*".$result->fields[3]."*".$result->fields[4]."*".$result->fields[5];
		}
		$this->ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoDocumento,$Documento,$TipoBuscar);
		$result->Close();
		return true;
	}

/*
  La funcion AdmitirHospitalizacionInterna Realiza la admision de hospitalizacion
	a partir de la orden e identifica si el plan requiere o no de una autorizacion para la
	hospitalizacion o si ya se tiene una autorizacion para este ingreso
 */
	function AdmitirHospitalizacionInterna(){

		$OrdenId=$_REQUEST['OrdenHospitalizacion'];
		$PacienteId=$_REQUEST['PacienteId'];
    $TipoId=$_REQUEST['TipoId'];

		$evolucion=$this->BuscarEvolucion($OrdenId);

		list($dbconn) = GetDBconn();
		$query = "SELECT ingresos.estado,ingresos.ingreso,ingresos.nivel FROM hc_evoluciones,ingresos WHERE hc_evoluciones.evolucion_id='$evolucion' AND hc_evoluciones.ingreso=ingresos.ingreso";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->EOF){
			  $this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'hc_evoluciones o ingresos' esta vacia ";
				return false;
			}
		}
		$numeroIngreso=$result->fields[1];
    $nivel=$result->fields[2];
		//Ingreso Activo
		if($result->fields[0]=='1'){
		  list($dbconn) = GetDBconn();
		  $query = "SELECT planes.plan_id FROM planes,cuentas WHERE cuentas.ingreso='$numeroIngreso' AND cuentas.plan_id=planes.plan_id";
		  $result = $dbconn->Execute($query);
		  if ($dbconn->ErrorNo() != 0) {
			  $this->error = "Error al Cargar el Modulo";
			  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			  return false;
		  }else{
        if($result->EOF){
			    $this->error = "Error al Cargar el Modulo";
				  $this->mensajeDeError = "La tabla 'planes' esta vacia ";
				  return false;
			  }
				$numeroPlan=$result->fields[0];
		  }
      $query1 = "SELECT sw_autorizacion FROM planes WHERE planes.plan_id='$numeroPlan'";
		  $result1 = $dbconn->Execute($query1);
      $autorizacion=$result1->fields[0];
			if($autorizacion=='0'){
        $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarAsignacionEstacionEnfermeria',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"OrdenId"=>$OrdenId,"Ingreso"=>$numeroIngreso,"PlanId"=>$numeroPlan));
				$this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$numeroPlan,"accion"=>$accion,"IngresoId"=>$numeroIngreso));
        $result->Close();
		    return true;
			}else{
			  $query1 = "SELECT * FROM autorizaciones,autorizaciones_detalle WHERE autorizaciones.ingreso='$numeroIngreso' AND autorizaciones.autorizacion_id=autorizaciones_detalle.autorizacion_id AND (autorizaciones_detalle.tipo_atencion_id='03' OR autorizaciones_detalle.tipo_atencion_id='0')";
		    $result1 = $dbconn->Execute($query1);
				$datos=$result1->RecordCount();
				if($datos!=0){
          $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarAsignacionEstacionEnfermeria',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"OrdenId"=>$OrdenId,"Ingreso"=>$numeroIngreso,"PlanId"=>$numeroPlan));
				  $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$numeroPlan,"accion"=>$accion,"IngresoId"=>$numeroIngreso));
          $result->Close();
		      return true;
				}else{
          $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarAsignacionEstacionEnfermeria',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"OrdenId"=>$OrdenId,"Ingreso"=>$numeroIngreso,"PlanId"=>$numeroPlan));
				  $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$numeroPlan,"accion"=>$accion,"IngresoId"=>$numeroIngreso));
          $result->Close();
		      return true;
				}
			}
		//Ingreso Inactivo
		}else{
			$mensage="El paciente no tiene un ingreso Activo.";
			$this->FormaPedirDatosNuevo($PacienteId,$TipoId,$mensage,$OrdenId);
			return true;
		}
	}

/*
La funcion BuscarResponsableServicios Busca el responsable del paciente a partir del plan
que lo cubre
*/
function BuscarResponsableServicios($plan){
  list($dbconn) = GetDBconn();
  $query="SELECT planes.plan_id,planes.plan_descripcion,planes.tipo_tercero_id,tipo_id_terceros.descripcion,planes.tercero_id,terceros.nombre_tercero FROM planes,tipo_id_terceros,terceros WHERE planes.plan_id='$plan' AND planes.tipo_tercero_id=tipo_id_terceros.tipo_id_tercero AND planes.tercero_id=terceros.tercero_id";
	$result = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}else{
		if($result->EOF){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "La tabla  'autorizaciones' esta vacia";
			return false;
		}
		$arreglo=$result->fields[0]."-".$result->fields[1]."-".$result->fields[2]."-".$result->fields[3]."-".$result->fields[4]."-".$result->fields[5];
		$result->Close();
		return $arreglo;
	}

}

/**la fucntion responsable busca en la base de datos los diferentes
*tipos de responsables
*/
  function responsables()
	{
			list($dbconn) = GetDBconn();
			$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla 'planes' esta vacia ";
						return false;
					}
						$i=0;
						while (!$result->EOF) {
						$planes[$i]=$result->fields[0].'-'.$result->fields[1].'-'.$result->fields[2].'-'.$result->fields[3];
						$result->MoveNext();
						$i++;
						}
				}
			$result->Close();
		return $planes;
	}

/*
  La funcion BuscarNombreTercero trae el nombre del tercero o clientes de la ips a partir de
	su codigo y el tipo
*/
	function BuscarNombreTercero($TerceroId,$TipoTercero){

	  list($dbconn) = GetDBconn();
		$query="SELECT nombre_tercero FROM terceros WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$result->Close();
		return $result->fields[0];
	 }

/*
  La funcion verificarDocumentosHomonimos Retorna las personas que tiene el mismo numero de
	Documento que la persona que se esta ingresando
*/

	function verificarDocumentosHomonimos($tipoDocumento,$numeroDocumento)
	{
		list($dbconn) = GetDBconn();
		$query  = "SELECT tipo_id_paciente,paciente_id FROM pacientes WHERE paciente_id='$numeroDocumento' AND tipo_id_paciente!='$tipoDocumento'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos!=0){
				while (!$result->EOF) {
					$homonimos[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
				$result->Close();
				return $homonimos;
			}else{
				return false;
			}
		}
	}


	/*
  La funcion nombreHomonimo Retorna los datos de las personas que tiene el mismo numero de
	Documento que la persona que se esta ingresando
*/

  function nombreHomonimo($documento,$tipo)
	{
		list($dbconn) = GetDBconn();
			$query = "SELECT * FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				if($result->EOF){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "La tabla maestra 'pacientes' esta vacia";
					return false;
				}
			}
			$result->Close();
			$cadenaTotal=$result->fields[4].'-'.$result->fields[5].'-'.$result->fields[2].'-'.$result->fields[3];
		return $cadenaTotal;
	}

/*
 La funcion mostrar_id_paciente retorna los tipo del documento de puede tener una persona
*/

  function mostrar_id_paciente($TipoId)
  {
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM tipos_id_pacientes WHERE tipo_id_paciente='$TipoId'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'tipo_id_paciente' esta vacia ";
				return false;
			}
			$datos=$result->RecordCount();
		}
		if($datos){
		  $Tipo=$result->fields[1];
			$result->Close();
		}
		return $Tipo;
	}

	/*
    La funcion ValidarDerechos valida los derechos de un afiliaco dependiendo del
		plan al que este pertenece
	*/

  function ValidarDerechos($PacienteId,$TipoId,$Responsable,$nivel,$OrdenId){

		list($dbconn) = GetDBconn();
	  $query = "SELECT sw_afiliacion,
		  							 sw_autorizacion,
			  						 sw_soat
				  		FROM planes
					  	WHERE plan_id='$Responsable'";
	  $results = $dbconn->Execute($query);
	  $Soat=$results->fields[2];

	  if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Cargar el Modulo";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
	  }
    if($results->fields[2]){
			$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaFormaIngreso',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Responsable"=>$Responsable,"OrdenId"=>$OrdenId));
			$this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$Responsable,"accion"=>$accion));
		  return true;
	  }
	  if($results->fields[0]){
		  list($dbconn) = GetDBconn();
		  $query = "SELECT primer_apellido,
													segundo_apellido,
													primer_nombre,
													segundo_nombre,
													fecha_nacimiento,
													residencia_direccion,
													residencia_telefono,
													sexo_id,
													fecha_registro,
													tipo_estado_afiliado_id
									FROM afiliados
									WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId' AND plan_id='$Responsable'";
			$result = $dbconn->Execute($query);
		  if ($dbconn->ErrorNo() != 0) {
		    $this->error = "Error al Cargar el Modulo";
			  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			  return false;
		  }
		  $datos=$result->RecordCount();
		  if($datos){
				$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaFormaIngreso',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Responsable"=>$Responsable,"OrdenId"=>$OrdenId,"nivel"=>$nivel));
			  $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$Responsable,"accion"=>$accion));
		    return true;
		  }
		  else{
			  if($results->fields[1]){
					$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaFormaIngreso',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Responsable"=>$Responsable,"OrdenId"=>$OrdenId,"nivel"=>$nivel));
			    $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$Responsable,"accion"=>$accion));
		      return true;
		    }
		    else{
					$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaFormaIngreso',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Responsable"=>$Responsable,"OrdenId"=>$OrdenId,"nivel"=>$nivel));
			    $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$Responsable,"accion"=>$accion));
		      return true;
		    }
		  }
    }
		else{
			if($results->fields[1]){
			  $accion=ModuloGetURL('app','Hospitalizacion','user','LlamaFormaIngreso',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Responsable"=>$Responsable,"OrdenId"=>$OrdenId,"nivel"=>$nivel));
			  $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$Responsable,"accion"=>$accion));
		    return true;
			}
			else{
				$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaFormaIngreso',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Responsable"=>$Responsable,"OrdenId"=>$OrdenId,"nivel"=>$nivel));
			  $this->salida.=ReturnModulo('app','Autorizacion','user','LlamaListado',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$nivel,"PlanId"=>$Responsable,"accion"=>$accion));
		    return true;
			}
		}
	}

	/*
  La funcion LlamaFormaIngreso Llama la forma ingreso pasando a la forma las variable que necesita para
	realizar el ingreso de un paciente
	*/

	function LlamaFormaIngreso(){

    $PacienteId=$_REQUEST['PacienteId'];
    $TipoId=$_REQUEST['TipoId'];
    $Responsable=$_REQUEST['Responsable'];
    $nivel=$_REQUEST['nivel'];
    $OrdenId=$_REQUEST['OrdenId'];

		list($dbconn) = GetDBconn();
		$query="SELECT * FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        $this->FormaIngreso($TipoId,$PacienteId,$Responsable,$nivel,$OrdenId,'','','','','');
				return true;
				$result->Close();
      }else{
        $this->FormaPedirDatos($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensage,$nivel,$Responsable);
				return true;
				$result->Close();
			}
		}
  }

/*
    La funcion InsertarDatosPaciente Inserta en la tabla pacientelos los datos principales de un paciente como
		el documento y el nombre
*/
	function InsertarDatosPaciente()
	{
    $nivel=$_REQUEST['nivel'];
		$Responsable=$_REQUEST['Responsable'];
		$PacienteId=$_REQUEST['PacienteId'];
		$PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
		$SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
		$PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
		$SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
		$FechaNacimiento=$_REQUEST['FechaNacimiento'];
		$FechaNacimientoCalculada=0; $FechaNacimientoCalculada;
		//$FechaNacimientoCalculada=$_REQUEST['FechaNacimientoCalculada'];
		$Direccion=$_REQUEST['Direccion'];
		$Telefono=$_REQUEST['Telefono'];
		$Ocupacion=$_REQUEST['Ocupacion'];
		$FechaRegistro=date("d/m/Y H:i:s");
		$TipoId=$_REQUEST['TipoId'];
		$Sexo=$_REQUEST['Sexo'];
		$EstadoCivil=$_REQUEST['EstadoCivil'];
		$Pais=$_REQUEST['pais'];
		$Dpto=$_REQUEST['dpto'];
		$Mpio=$_REQUEST['mpio'];
		$SystemId=UserGetUID();

    if(!$PacienteId || !$TipoId || !$FechaNacimiento || $Sexo==-1 || !$PrimerNombre || !$PrimerApellido){
			if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
				if(!$TipoId){ $this->frmError["TipoId"]=1; }
					if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
						if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
							if(!$FechaNacimiento){ $this->frmError["FechaNacimiento"]=1; }
								if(!$Sexo){ $this->frmError["Sexo"]=1; }
									$this->frmError["MensajeError"]="Faltan datos obligatorios.";
									$accion=ModuloGetURL('app','Triage','user','InsertarDatosPaciente');
									if(!$this->FormaPedirDatos($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensage,$nivel,$Responsable)){
										return false;
									}
									return true;
		}

		if(!$EstadoCivil) $EstadoCivil=0;
		list($dbconn) = GetDBconn();
				 	$query = "INSERT INTO pacientes (
																paciente_id,
																tipo_id_paciente,
																primer_apellido,
																segundo_apellido,
																primer_nombre,
																segundo_nombre,
																fecha_nacimiento,
																fecha_nacimiento_es_calculada,
																residencia_direccion,
																residencia_telefono,
																zona_residencia,
																ocupacion,
																fecha_registro,
																sexo_id,
																tipo_estado_civil_id,
																foto,
																tipo_pais_id,
																tipo_dpto_id,
																tipo_mpio_id,
																usuario_id)
				VALUES ($PacienteId,'$TipoId','$PrimerApellido','$SegundoApellido','$PrimerNombre','$SegundoNombre','$FechaNacimiento','$FechaNacimientoCalculada','$Direccion','$Telefono','1','$Ocupacion','$FechaRegistro','$Sexo','$EstadoCivil','$foto',$Pais,$Dpto,$Mpio,$SystemId)";
				$dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
         $this->error = "Error al Guardar en la Base de Datos";
         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
 				 return false;
    }else{
      $this->FormaIngreso($TipoId,$PacienteId,$Responsable,$nivel,$OrdenId,'','','','','');
			return true;
			$result->Close();
		}
	}

//busca el nombre del tercero
  function Responsable($Responsable)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tercero_id FROM planes WHERE plan_id='$Responsable'";
			$result = $dbconn->Execute($query);
			$TerceroId=$result->fields[0];
			$query = "SELECT nombre_tercero FROM terceros WHERE tercero_id='$TerceroId'";
			$result = $dbconn->Execute($query);
			$NomTercero=$result->fields[0];
			return $NomTercero;
	}

/*
 Retorna las clases de causas  externas por las que se realiza una atencion que exisaten en la base de datos
*/

	function Causa_Externa()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM causas_externas order by causa_externa_id";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'causas_externas' esta vacia ";
				return false;
			}
				while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
		}
		$result->Close();
	  return $vars;
	}

	/*
 Retorna los tipos de vias de ingreso por los que puede ingresar un paciente cuando
 requiere una atencion
*/

	function Via_Ingreso()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT via_ingreso_id,via_ingreso_nombre FROM vias_ingreso order by via_ingreso_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'vias_ingreso' esta vacia ";
				return false;
			}
				while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
		}
		$result->Close();
	  return $vars;
	}
/*
Retorna los tipos de afiliados de un paciente cuando pertenece a una entidad prestadora de
salud
*/
	function Tipo_Afiliado()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM tipos_afiliados";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
				if($result->EOF){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "La tabla maestra 'tipos_afiliados' esta vacia ";
					return false;
				}
				while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
		}
		$result->Close();
	  return $vars;
		}
/*
Retorna los tipos de estados que puede tener un afiliados cuando pertenece a una entidad prestadora de
salud
*/
 function Estado_Afiliado()
 {
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_estado_afiliado_id,descripcion FROM tipo_estados_afiliados";
		$results = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
				if($results->EOF){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "La tabla maestra 'estados_afiliados' esta vacia ";
					return false;
				}
				while (!$results->EOF) {
						$vars[$results->fields[0]]=$results->fields[1];
						$results->MoveNext();
				}
		}
		$results->Close();
		return $vars;
	}

/**
	*La funcion BuscarDatosPacienteModificar se encarga de buscar en la base de datos
	*los datos del paciente que se va ha ingresar para mostrarlos y var si es
  *necesaria alguna modificacion
	*/

	function BuscarDatosPacienteModificar($TipoId,$PacienteId)
	{
 	  list($dbconn) = GetDBconn();
  	$query = "SELECT  primer_apellido,
											segundo_apellido,
											primer_nombre,
											segundo_nombre,
											fecha_nacimiento,
											fecha_nacimiento_es_calculada,
											residencia_direccion,
											residencia_telefono,
											zona_residencia,
											ocupacion,
											fecha_registro,
											sexo_id,
											tipo_estado_civil_id,
											foto,
											tipo_pais_id,
											tipo_dpto_id,
											tipo_mpio_id
               FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }

		$i=0;
      while($i<=16){
        $vars[$i]=$result->fields[$i];
				$i++;
			}
      $result->Close();
		return $vars;
	}

/**
	*La funcion nombre_pais se encarga de obtener de la base de datos
	*el nombre del pais
	*/

	function nombre_pais($Pais)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_pais WHERE tipo_pais_id='$Pais'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
						return false;
					}
				}
				$result->Close();
		return $result->fields[1];
	}

/**
	*La funcion BuscarNumeroHistoria se encarga de obtener de la base de datos
	*el numero de hidtoria clinica del paceinte
	*/

	function BuscarNumeroHistoria($TipoId,$PacienteId){
    list($dbconn) = GetDBconn();
		$query = "SELECT historia_prefijo,historia_numero FROM historias_clinicas WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->EOF){
			  $this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
				return false;
			}
		}
		$result->Close();
		$cadena=$result->fields[0].''.$result->fields[1];
		return $cadena;
	}

	/**
	*La funcion nombre_dpto se encarga de obtener de la base de datos
	*el nombre del dpto
	*/

	function nombre_dpto($Pais,$Dpto)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_dptos WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
						return false;
					}
				}
				$result->Close();
		return $result->fields[2];
	}

	/**
	*La funcion nombre_ciudad se encarga de obtener de la base de datos
	*el nombre de la ciudad o municipio
	*/

	function nombre_ciudad($Pais,$Dpto,$Mpio)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_mpios WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
						return false;
					}
				}
				$result->Close();
		return $result->fields[3];
	}

/**
	*La funcion sexo se encarga de obtener de la base de datos
	*los diferentes tipos de sexo utilizados en la aplicacion
	*/

  function sexo()
  {
			list($dbconn) = GetDBconn();
			$result="";
			$query = "SELECT sexo_id,descripcion FROM tipo_sexo ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	/**
	*La funcion estadocivil se encarga de obtener de la base de datos
	*los diferentes tipos de estado civil utilizados en la aplicacion
	*/

	function estadocivil()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_estado_civil WHERE tipo_estado_civil_id!=0 ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	/**
	*La funcion ocupacion se encarga de obtener de la base de datos
	*los diferentes tipos ocupaciones de los pacientes
	*/

  function ocupacion()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM ocupaciones ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

/*
La funcion Edad Retorna la fecha de nacimiento de un paciente a pertir de su documento
*/

	function Edad($TipoId,$PacienteId)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha_nacimiento FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'paciente' esta vacia ";
				return false;
			}
		}
		$result->Close();
		$FechaNacimiento=$result->fields[0];
		return $FechaNacimiento;
	}



/*
La funcion NombreSexo retorna el valor descripotivo del sexo del paciente a partir de su identificacion
*/
	function NombreSexo($TipoId,$PacienteId)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT sexo_id FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				$s=$result->fields[0];
				list($dbconn) = GetDBconn();
				$query = "SELECT descripcion FROM tipo_sexo WHERE sexo_id='$s'";
				$results = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				$result->Close();
				return $results->fields[0];
	}

/**
	*La funcion ModficarDatosPaciente se encarga de actualizar en la base de datos
  *los datos del paciente cuando se modifican en la admision
	*/
	function ModficarDatosPaciente()
	{
		$PacienteId1=$_REQUEST['PacienteId1'];
		$TipoId1=$_REQUEST['TipoId1'];
		$PacienteId=$_REQUEST['PacienteId'];
		$TipoId=$_REQUEST['TipoId'];

		$PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
		$SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
		$PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
		$SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
		$FechaNacimiento=$_REQUEST['FechaNacimiento'];
		$FechaNacimientoCalculada=0; $FechaNacimientoCalculada;
		$Direccion=$_REQUEST['Direccion'];
		$Telefono=$_REQUEST['Telefono'];
		$Ocupacion=$_REQUEST['Ocupacion'];
		$FechaRegistro=$_REQUEST['FechaRegistro'];
		$Sexo=$_REQUEST['Sexo'];
		$EstadoCivil=$_REQUEST['EstadoCivil'];
    $Pais=$_REQUEST['pais'];
		$Dpto=$_REQUEST['dpto'];
		$Mpio=$_REQUEST['mpio'];
		$SystemId=UserGetUID();
		$accion=$_REQUEST['accion'];
		$TipoForma=$_REQUEST['TipoForma'];
		$Responsable=$_REQUEST['Responsable'];


		if(!$PacienteId1 || !$TipoId1 || !$FechaNacimiento || !$Sexo || !$PrimerNombre || !$PrimerApellido){
			if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
			if(!$TipoId){ $this->frmError["TipoId"]=1; }
			if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
			if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
			if(!$FechaNacimiento){ $this->frmError["FechaNacimiento"]=1; }
			if(!$Sexo){ $this->frmError["Sexo"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			$accion=ModuloGetURL('app','Triage','user','ModficarDatosPaciente');
			if(!$this->FormaIngreso($TipoId1,$PacienteId1,$Responsable,'','','','','','','')){
				return false;
			}
			return true;
		}

		list($dbconn) = GetDBconn();
		$query = "UPDATE pacientes SET
																		paciente_id='$PacienteId',
																		tipo_id_paciente='$TipoId',
																		primer_apellido='$PrimerApellido',
																		segundo_apellido='$SegundoApellido',
																		primer_nombre='$PrimerNombre',
																		segundo_nombre='$SegundoNombre',
																		fecha_nacimiento='$FechaNacimiento',
																		fecha_nacimiento_es_calculada='$FechaNacimientoCalculada',
																		residencia_direccion='$Direccion',
																		residencia_telefono='$Telefono',
																		zona_residencia=1,
																		ocupacion='$Ocupacion',
																		fecha_registro='$FechaRegistro',
																		sexo_id='$Sexo',
																		tipo_estado_civil_id='$EstadoCivil',
																		foto='$foto',
																		tipo_pais_id='$Pais',
																		tipo_dpto_id='$Dpto',
																		tipo_mpio_id='$Mpio',
																		usuario_id='$SystemId'
								WHERE paciente_id=$PacienteId1 AND tipo_id_paciente='$TipoId1'";
				$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		if(!$this->FormaIngreso($TipoId,$PacienteId,$Responsable,'','','','','','','')){
			return false;
		}
		return true;
	}
/*
La funcion InsertarDatosIngreso inserta los datos en la table de ingreso dejandolo activo asi como
la cuenta que se la abre al paciente al realizar el ingreso diferenciando si se trata de un
ingreso por soat
*/
  function InsertarDatosIngreso()
	{
		$Responsable=$_REQUEST['Responsable'];
		$PacienteId=$_REQUEST['PacienteId'];
		$TipoId=$_REQUEST['TipoId'];
    $nivel=$_REQUEST['nivel'];
    $OrdenId=$_REQUEST['OrdenId'];
		$Soat=$_REQUEST['Soat'];
		$TipoForma=$_REQUEST['TipoForma'];
		$Poliza=$_REQUEST['poliza'];
		$FechaIngreso=$_REQUEST['fechaIngreso'];
		$CausaExterna=$_REQUEST['CausaExterna'];
		$ViaIngreso=$_REQUEST['ViaIngreso'];
		$TipoAfiliado=$_REQUEST['TipoAfiliado'];
//		$Departamento=$_REQUEST['Departamento'];
		$Estado1='1';
		$TipoAfiliado=$_REQUEST['TipoAfiliado'];
		$Estado=$_REQUEST['Estado'];
		$Comentarios=$_REQUEST['Comentarios'];
    $fechaSistema=date("d/m/Y H:i:s");
		$PacienteId=$_REQUEST['PacienteId'];
		$TipoId=$_REQUEST['TipoId'];
		$SystemId=UserGetUID();
		$dpto=$this->Departamento;
		$IngresoId=rand();

    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "SELECT empresa_id,
		                 sw_soat,
										 sw_autorizacion
			  		    		FROM planes
				    		  	WHERE plan_id='$Responsable'";
		$results = $dbconn->Execute($query);
		$EmpresaId=$results->fields[0];
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($results->fields[1]){
        if($CausaExterna==-1 || $ViaIngreso==-1 || $Estado==-1 || $Poliza==''){
				  if($CausaExterna==-1){ $this->frmError["CausaExterna"]=1; }
				  if($ViaIngreso==-1){ $this->frmError["ViaIngreso"]=1; }
				  if($Estado==-1){ $this->frmError["Estado"]=1; }
				  if($Poliza==''){ $this->frmError["poliza"]=1; }
				  $this->frmError["MensajeError"]="Faltan datos obligatorios.";
				  if(!$this->FormaIngreso($TipoId,$PacienteId,$Responsable,$nivel,$OrdenId,$CausaExterna,$ViaIngreso,$TipoAfiliado,$Estado,$Poliza)){
					  return false;
				  }
				  return true;
			  }
		    $Evento=1;
			  $query = "INSERT INTO ingresos (ingreso,
			                          					tipo_id_paciente,
																					paciente_id,
																					fecha_ingreso,
																					causa_externa_id,
																					via_ingreso_id,
																					tipo_afiliado_id,
																					comentario,
																					departamento,
																					nivel,
																					estado,
																					fecha_registro,
																					usuario_id,
																					departamento_ultimo)
										VALUES($IngresoId,'$TipoId',$PacienteId,'$FechaIngreso','$CausaExterna','$ViaIngreso',NULL,'$Comentarios','$dpto','$nivel','$Estado1','$fechaSistema','$SystemId','$u_funcional')";
			  $dbconn->Execute($query);
			  if ($dbconn->ErrorNo() != 0) {
				  $this->error = "Error al Guardar en la Base de Datos";
				  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  $dbconn->RollbackTrans();
				  return false;
			  }else{
			    $query = "INSERT INTO ingresos_soat ( ingreso,
					  																		poliza,
																								evento)
										VALUES($IngresoId,'$Poliza',$Evento)";
			    $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
				    $this->error = "Error al Guardar en la Base de Datos";
				    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				    $dbconn->RollbackTrans();
				    return false;
			    }else{
            $Cuenta=rand();
			      $query = "INSERT INTO cuentas ( empresa_id,
																							numerodecuenta,
																							ingreso,
																							plan_id,
																							usuario_id,
																							fecha_registro,
																							estado)
												VALUES($EmpresaId,'$Cuenta',$IngresoId,'$Responsable','$SystemId','$fechaSistema','$Estado1')";
						$dbconn->Execute($query);
				    if ($dbconn->ErrorNo() != 0) {
					    $this->error = "Error al Guardar en la Base de Datos";
					    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
					    return false;
				    }else{
				      if($OrdenId){
					      $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarAsignacionEstacionEnfermeria',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"OrdenId"=>$OrdenId,"Ingreso"=>$IngresoId,"PlanId"=>$Responsable));
              }else{
                $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarOrdenHospitalizacion',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Ingreso"=>$IngresoId,"PlanId"=>$Responsable));
				      }
				      $mensaje='El paciente fue ingresado Correctamente';
				      $titulo='DATOS INGRESO';
							$dbconn->CommitTrans();
			        $this->salida.=ReturnModulo('app','Autorizacion','user','InsertarTmpAutorizacion',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"IngresoId"=>$IngresoId,"mensaje"=>$mensaje,"titulo"=>$titulo,"accion"=>$accion));
		          return true;
						}
					}
				}
			}else{
        if($CausaExterna==-1 || $ViaIngreso==-1 || $Estado==-1 || $TipoAfiliado==-1){
				  if($CausaExterna==-1){ $this->frmError["CausaExterna"]=1; }
				  if($ViaIngreso==-1){ $this->frmError["ViaIngreso"]=1; }
				  if($Estado==-1){ $this->frmError["Estado"]=1; }
				  if($TipoAfiliado==-1){ $this->frmError["TipoAfiliado"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					if(!$this->FormaIngreso($TipoId,$PacienteId,$Responsable,$nivel,$OrdenId,$CausaExterna,$ViaIngreso,$TipoAfiliado,$Estado,$Poliza)){
						return false;
					}
					return true;
				}
			  list($dbconn) = GetDBconn();
			  $query = "INSERT INTO ingresos (ingreso,
																				tipo_id_paciente,
																					paciente_id,
																					fecha_ingreso,
																					causa_externa_id,
																					via_ingreso_id,
																					tipo_afiliado_id,
																					comentario,
																					departamento,
																					nivel,
																					estado,
																					fecha_registro,
																					usuario_id,
																					departamento_ultimo)
										VALUES($IngresoId,'$TipoId',$PacienteId,'$FechaIngreso','$CausaExterna','$ViaIngreso','$TipoAfiliado','$Comentarios','$dpto','$nivel','$Estado1','$fechaSistema','$SystemId','$u_funcional')";
			  $dbconn->Execute($query);
			  if ($dbconn->ErrorNo() != 0) {
				  $this->error = "Error al Guardar en la Base de Datos";
				  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
				  return false;
			  }else{
          $Cuenta=rand();
				  $query = "INSERT INTO cuentas ( empresa_id,
				  															numerodecuenta,
																							ingreso,
																							plan_id,
																							usuario_id,
																							fecha_registro,
																							estado)
												VALUES($EmpresaId,'$Cuenta',$IngresoId,'$Responsable','$SystemId','$fechaSistema','$Estado1')";
					$dbconn->Execute($query);
				  if ($dbconn->ErrorNo() != 0) {
					  $this->error = "Error al Guardar en la Base de Datos";
					  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
					  return false;
				  }else{
            if($OrdenId){
					    $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarAsignacionEstacionEnfermeria',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"OrdenId"=>$OrdenId,"Ingreso"=>$IngresoId,"PlanId"=>$Responsable));
            }else{
              $accion=ModuloGetURL('app','Hospitalizacion','user','LlamarOrdenHospitalizacion',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Ingreso"=>$IngresoId,"PlanId"=>$Responsable));
					  }
					  $mensaje='El paciente fue ingresado Correctamente';
					  $titulo='DATOS INGRESO';
			      $this->salida.=ReturnModulo('app','Autorizacion','user','InsertarTmpAutorizacion',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"IngresoId"=>$IngresoId,"mensaje"=>$mensaje,"titulo"=>$titulo,"accion"=>$accion));
						$dbconn->CommitTrans();
		        return true;
				  }
				}
			}
		}
	}

/*
La funcion BuscarPaciente se encarga de busacar un paceinte en la base de datos
cuando este no cuenta con un ingreso activo
*/

	function BuscarPaciente($TipoDocumento,$Documento){

	  if($TipoDocumento=='MS' || $TipoDocumento=='AS')
		{
		  $mensaje='El paciente es NN.';
			if(!$this->FormaNN($TipoDocumento,$Documento,$mensaje,'','','')){
				return false;
			}
			return true;
		}
		list($dbconn) = GetDBconn();
  	$query = "SELECT * FROM pacientes WHERE tipo_id_paciente='$TipoDocumento' AND paciente_id='$Documento'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
			$datos=$result->RecordCount();
			if($datos!=0){
				//muestra la forma de los datos llenos
				$mensage="El paciente se encuentra registrado.";
				$this->FormaPedirDatosNuevo($Documento,$TipoDocumento,$mensage,'');
				return true;
			}else{
				$mensage="El paciente no se encuentra registrado.";
				$this->FormaPedirDatosNuevo($Documento,$TipoDocumento,$mensage,'');
				return true;
			}
		}
  }

/*
La funcion InsertarDatosPacienteNN se encarga de insertar los pocos datos
de un paceinte cuando este es un NN
*/

function InsertarDatosPacienteNN()
	{
		$Responsable=$_REQUEST['Responsable'];
		$PacienteId=$_REQUEST['PacienteId'];
		$TipoId=$_REQUEST['TipoId'];
		$Sexo=$_REQUEST['Sexo'];
		$PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
		$PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
		$FechaNacimiento=$_REQUEST['FechaNacimiento'];
		$SystemId=UserGetUID();
		$fechaSistema=date("d/m/Y H:i:s");
		$Pais=$_REQUEST['pais'];
		$Dpto=$_REQUEST['dpto'];
		$Mpio=$_REQUEST['mpio'];
		$nivel='1';
    if(!$PacienteId){
		  list($dbconn) = GetDBconn();
		  $query="SELECT nextval('disparadornn')";
		  $result=$dbconn->Execute($query);
		  $PacienteId=$result->fields[0];
		}
		if($Responsable==-1 || !$FechaNacimiento || $Sexo==-1){
			if($Responsable==-1){
        $mensaje='Debe Seleccionar un Responsable';
				$this->FormaNN($TipoId,$PacienteId,$mensaje,$Responsable,$Sexo,$FechaNacimiento);
				return true;
			}elseif(!$FechaNacimiento){
			  $mensaje='Debe Seleccionar una Fecha de Nacimiento';
				$this->FormaNN($TipoId,$PacienteId,$mensaje,$Responsable,$Sexo,$FechaNacimiento);
				return true;
			}elseif(!$Sexo){
			  $mensaje='Debe Seleccionar una Fecha de Nacimiento';
				$this->FormaNN($TipoId,$PacienteId,$mensaje,$Responsable,$Sexo,$FechaNacimiento);
				return true;
			}
		}else{
      list($dbconn) = GetDBconn();
			$query = "INSERT INTO pacientes (
														paciente_id,
														tipo_id_paciente,
														primer_apellido,
														primer_nombre,
														fecha_nacimiento,
														sexo_id,
														tipo_estado_civil_id,
														fecha_registro,
														tipo_pais_id,
														tipo_dpto_id,
														tipo_mpio_id,
														usuario_id)
				VALUES ($PacienteId,'$TipoId','$PrimerApellido','$PrimerNombre','$FechaNacimiento','$Sexo','0','$fechaSistema','$Pais','$Dpto','$Mpio',$SystemId)";
				$dbconn->Execute($query);

      if ($dbconn->ErrorNo() != 0) {
         $this->error = "Error al Guardar en la Base de Datos";
         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
 				 return false;
      }else{
        $niveles=$this->nivelesEps($Responsable);
		    $contador=sizeof($niveles);
        //print_r($niveles);
		    if($contador>1){
          $this->FormaPedirNivel($TipoId,$PacienteId,$Responsable,$OrdenId);
			    return true;
	      }else{
          $nivel=$niveles[0];
			    $this->ValidarDerechos($PacienteId,$TipoId,$Responsable,$nivel,$OrdenId);
		      return true;
				}
      }
    }
  }

/*
La funcion AdmitirHospitalizacionExterna Realiza la admision externa de la hospitalizacion
*/
	function AdmitirHospitalizacionExterna(){
		$OrdenId=$_REQUEST['OrdenHospitalizacion'];
		$PacienteId=$_REQUEST['PacienteId'];
    $TipoId=$_REQUEST['TipoId'];
		$this->BuscarPaciente($TipoId,$PacienteId);
		return true;
	}

/*
La funcion departamentos_destinos se encarga de retornar los tipo de departamentos
de la ips
*/

function departamentos_destinos(){

		list($dbconn) = GetDBconn();
		$query = "SELECT departamento,departamento_nombre FROM departamentos";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'departamentos' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/*
La funcion estaciones_destinos se encarga de retornar lasestacioens de enfermeria
con las que cuenta la ips y a las que se le pueden asignar afiliados
*/
	function estaciones_destinos($departamento){

		list($dbconn) = GetDBconn();
		$query = "SELECT estacion_id,descripcion FROM estaciones_enfermeria WHERE departamento='$departamento'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'estaciones_enfermeria' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/*
La funcion tipos_orden_Hospi retorna los tipo de ordenes de hospitalizacion(interna o externas) que existen
*/

	function tipos_orden_Hospi(){

		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_orden_id,descripcion FROM tipos_orden";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'tipo_ordenes' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/*
La funcion entidades_Origen retorna los tipo de entidades que pueden remitir un paciente
a la ips y que hacen parte del sistema de gestion se seguridad social de salud
*/

	function entidades_Origen(){

		list($dbconn) = GetDBconn();
		$query = "SELECT sgsss,nombre_sgsss FROM sgsss";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'sgsss' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
/*
La funcion diagnosticos retorna los tipo de diagnosticos que existen en la base de datos
Para registrarlo en la oreden de hospitalizacion
*/
	function diagnosticos(){

		list($dbconn) = GetDBconn();
		$query = "SELECT diagnostico_id,diagnostico_nombre FROM diagnosticos";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/*
La funcion LlamarAsignacionEstacionEnfermeria llama a la forma de asignacion de estacion de enfermeria
pasandole como paramentros los datos necesarios para realizar esta asignacion
*/

	function LlamarAsignacionEstacionEnfermeria(){

		$OrdenId=$_REQUEST['OrdenId'];
		$Ingreso=$_REQUEST['Ingreso'];
    $TipoId=$_REQUEST['TipoId'];
    $PacienteId=$_REQUEST['PacienteId'];
    $PlanId=$_REQUEST['PlanId'];

		$this->AsignacionEstacionEnfermeria($OrdenId,$Ingreso,$TipoId,$PacienteId,$PlanId,'');
		return true;

  }

/*
La funcion InsertarAsignacionEnfermeria Inserta los datos en la tabla pendientes_x_hospitalizar
lo que significa que ya se encuentra con una estacion de enfermeria destino
*/
function InsertarAsignacionEnfermeria(){

  $Ingreso=$_REQUEST['Ingreso'];
  $OrdenId=$_REQUEST['OrdenId'];
	$estacion_destino=$_REQUEST['estacion'];
  $dpto=$_REQUEST['departamento'];
	$TipoId=$_REQUEST['TipoId'];
  $PacienteId=$_REQUEST['PacienteId'];
  $PlanId=$_REQUEST['PlanId'];

	if($estacion_destino=='-1' || !$estacion_destino){
		$this->AsignacionEstacionEnfermeria($OrdenId,$Ingreso,$TipoId,$PacienteId,$PlanId,$dpto,$estacion_destino);
		return true;
	}else{
		list($dbconn) = GetDBconn();
		$query = "INSERT INTO pendientes_x_hospitalizar(ingreso,
		  										orden_hospitalizacion_id,
													estacion_destino,
													estacion_origen,
													traslado)
												VALUES('$Ingreso','$OrdenId','$estacion_destino',NULL,NULL)";
						$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $this->ListadoAdmisionHospitalizacion('','','','','','');
		  return true;
		}
	}
}
/*
La funcion LlamarOrdenHospitalizacion Llama a la forma de ordenes de hospitalizacion
pasando los datos necesarios para realizar la orden
*/
  function LlamarOrdenHospitalizacion(){
    $TipoId=$_REQUEST['TipoId'];
    $PacienteId=$_REQUEST['PacienteId'];
    $Responsable=$_REQUEST['PlanId'];
		$IngresoId=$_REQUEST['Ingreso'];
		$this->OrdenHospitalizacion($PacienteId,$TipoId,$Responsable,$IngresoId,'','','','','','','');
		return true;
	}
/*

La funcion InsertarOrdenHospitalizacion inserta los datos de la orden de hospitalizacion

*/
  function InsertarOrdenHospitalizacion(){

	  $TipoId=$_REQUEST['TipoId'];
    $PacienteId=$_REQUEST['PacienteId'];
    $Responsable=$_REQUEST['Responsable'];
		$IngresoId=$_REQUEST['IngresoId'];
    $departamento=$_REQUEST['departamento'];
		$fechaSistema=date("d/m/Y H:i:s");
    $FechaProgram=$_REQUEST['FechaProgramacion'];
    $Hora=$_REQUEST['HoraOrden'];
    $Minutos=$_REQUEST['MinutosOrden'];
    $TipoOrden=$_REQUEST['tipoOrden'];
    $nombreMedico=$_REQUEST['nombreMedico'];
    $observaciones=$_REQUEST['observaciones'];
    $diagnostico=$_REQUEST['diagnostico'];
    $entOrigen=$_REQUEST['entOrigen'];
		$Programacion=$FechaProgram.' '.$Hora.':'.$Minutos.':'.'00';
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query ="SELECT nextval('ordenes_hospitalizacion_orden_hospitalizacion_id_seq')";
		$result = $dbconn->Execute($query);
		$id_orden=$result->fields[0];

		if($FechaProgram=='' || $Hora=='' || $Minutos=='' || $departamento==-1 || $nombreMedico=='' || $diagnostico==-1 || $entOrigen==-1){
			if($FechaProgram==''){ $this->frmError["FechaProgramacion"]=1; }
			if($Hora==''){ $this->frmError["HoraOrden"]=1; }
			if($Minutos==''){ $this->frmError["HoraOrden"]=1; }
			if($departamento==-1){ $this->frmError["departamento"]=1; }
      if($nombreMedico==''){ $this->frmError["nombreMedico"]=1; }
      if($diagnostico==-1){ $this->frmError["diagnostico"]=1; }
			if($entOrigen==-1){ $this->frmError["entOrigen"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			if(!$this->OrdenHospitalizacion($PacienteId,$TipoId,$Responsable,$IngresoId,$FechaProgram,$Hora,$Minutos,$departamento,$nombreMedico,$diagnostico,$entOrigen)){
				return false;
			}
			return true;
		}

	  $query = "INSERT INTO ordenes_hospitalizacion(orden_hospitalizacion_id,
		    									fecha_orden,
			  									fecha_programacion,
				  								hospitalizado,
					  							paciente_id,
													tipo_id_paciente,
													departamento,
													tipo_orden_id,
													unidad_funcional)
						  						VALUES('$id_orden','$fechaSistema','$Programacion','0','$PacienteId','$TipoId','$departamento','0','$u_funcional')";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en la Base de Datos";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
		  return false;
	  }else{

		  $query = "INSERT INTO ordenes_hospitalizacion_externas(orden_hospitalizacion_id,
		    									nombre_medico,
                          observaciones,
													diagnostico_id,
													entidad_origen)
													VALUES('$id_orden','$nombreMedico','$observaciones','$diagnostico','$entOrigen')";
		  $dbconn->Execute($query);
	    if ($dbconn->ErrorNo() != 0) {
		    $this->error = "Error al Guardar en la Base de Datos";
		    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
		    return false;
	    }else{
        $dbconn->CommitTrans();
				$this->AsignacionEstacionEnfermeria($id_orden,$IngresoId,$TipoId,$PacienteId,$Responsable,$departamento);
		    return true;
	    }
		}
  }
/*
La funcion nivelesEps realiza un conteo de los niveles de copaco que requiere la eps o el
responsable
*/

	function nivelesEps($Responsable){
		list($dbconn) = GetDBconn();
		$query="SELECT nivel FROM cuota_paciente WHERE plan_id='$Responsable'";
		$result=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$i=0;
				while(!$result->EOF){
					$niveles[$i]=$result->fields[0];
					$i++;
					$result->MoveNext();
				}
			return $niveles;
			$result->Close();
			}
		}
	}

/*
La funcion VerificarPedirNivel verifica si se debe pedir el nivel del paciente que
requiere la eps a partir del numero de niveles con los que cuenta
*/
	function VerificarPedirNivel(){

    $TipoId=$_REQUEST['TipoId'];
    $PacienteId=$_REQUEST['PacienteId'];
    $Responsable=$_REQUEST['Responsable'];
    $OrdenId=$_REQUEST['OrdenId'];

	  $niveles=$this->nivelesEps($Responsable);
		$contador=sizeof($niveles);
    //print_r($niveles);
    if($Responsable!=-1){
		  if($contador>1){
        $this->FormaPedirNivel($TipoId,$PacienteId,$Responsable,$OrdenId);
			  return true;
	    }else{
        $nivel=$niveles[0];
			  $this->ValidarDerechos($PacienteId,$TipoId,$Responsable,$nivel,$OrdenId);
		    return true;
	    }
		}else{
      $mensage='Debe Seleccionar un Responsable';
			$this->FormaPedirDatosNuevo($PacienteId,$TipoId,$mensage,$OrdenId);
		  return true;
		}
}
/*
La funcion LlamarValidarDerechos Llama a la funcion que valida los derechos pasando
los datos requeridos para realizar esta validacion
*/
  function LlamarValidarDerechos(){

    $TipoId=$_REQUEST['TipoId'];
    $PacienteId=$_REQUEST['PacienteId'];
    $Responsable=$_REQUEST['Responsable'];
    $OrdenId=$_REQUEST['OrdenId'];
    $nivel=$_REQUEST['nivel'];
		$this->ValidarDerechos($PacienteId,$TipoId,$Responsable,$nivel,$OrdenId);
		return true;

	}

/*
La funcion verificarNombresHomonimos retorna todo los homonimos por nombres y apellidos
del paciente que se esta insertando
*/
	function verificarNombresHomonimos($tipoDocumento,$numeroDocumento,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido)
	{
		$primerApellido=strtoupper($primerApellido);
		$segundoApellido=strtoupper($segundoApellido);
		$primerNombre=strtoupper($primerNombre);
		$segundoNombre=strtoupper($segundoNombre);

		list($dbconn) = GetDBconn();
		//Pregunta si los parametros de segundo apellido y segundo nombre son nulos para realizar el select con los datos existentes

		if($segundoApellido!="" OR $segundoNombre!="" OR $primerApellido!="" OR $primerNombre!=""){
			if($segundoApellido=="" AND $segundoNombre==""){
				$query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') AND paciente_id!='$numeroDocumento'";
				$result= $dbconn->Execute($query);
					//Pregunta si el parametro de segundo apellido es nulo para realizar el select con los datos existentes
				}elseif($segundoApellido==""){
					$query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') AND(primer_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '') AND paciente_id!='$numeroDocumento'";
					$result = $dbconn->Execute($query);
					//Pregunta si el parametro de segundo nombre es nulo para realizar el select con los datos existentes
				}elseif($segundoNombre==""){
					$query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '') AND (primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') AND paciente_id!='$numeroDocumento'";
					$result = $dbconn->Execute($query);
					//si todos los datos estos completo realiza el select con todos los parametros
				}else{
					$query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '') AND ((primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') OR (primer_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '')) AND paciente_id!='$numeroDocumento'";
					$result = $dbconn->Execute($query);
				}
			}

		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
    $datos=$result->RecordCount();
		if($datos!=0){
			while (!$result->EOF) {
				$homonimos[$result->fields[1]]=$result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
			return $homonimos;
		}else{
		  return false;
		}
 }

  function ListadoImpresion(){
    if(!$this->ListadoPendientes()){
        return false;
    }
	  return true;
	}

	function BuscarPacientesAdmitidos()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT ingresos.ingreso,
			                 ingresos.tipo_id_paciente,
											 ingresos.paciente_id,
											 ingresos.fecha_registro,
											 ingresos.estado,
											 ingresos.tipo_afiliado_id,
											 ingresos.comentario
			          FROM ingresos,pendientes_x_hospitalizar,ordenes_hospitalizacion WHERE ingresos.ingreso=pendientes_x_hospitalizar.ingreso AND pendientes_x_hospitalizar.orden_hospitalizacion_id=ordenes_hospitalizacion.orden_hospitalizacion_id AND ordenes_hospitalizacion.hospitalizado='0'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($result->EOF){
			$mensaje='No hay pacientes ingresados.';
			$accion=ModuloGetURL('app','Hospitalizacion','user','BuscarPacientesAdmitidos');
			$boton='Refrescar';
			if(!$this->FormaMensaje($mensaje,'LISTADO PACIENTES INGRESO',$accion,$boton)){
				return false;
			}
				return true;
		}
    $i=0;
		while(!$result->EOF){
			$Estado=$result->fields[4];
			$TipoAfiliado=$result->fields[5];
      if(!$result->fields[4]){ $Estado='-';}
      if(!$result->fields[5]){ $TipoAfiliado='-';}
			$vars[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$Estado.'/'.$TipoAfiliado.'/'.$result->fields[6];
      $result->MoveNext();
			$i++;
		}
    $result->Close();
    return $vars;
	}
function BuscarDatosPaciente($tipo,$documento)
 {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
						if($result->EOF){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
							return false;
						}
						while (!$result->EOF) {
							$vars[$result->fields[12]]=$result->fields[4]." ".$result->fields[2];
							$result->MoveNext();
						}
				}
				$result->Close();

		return $vars;
 }

 function Imprimir()
	{
	  $TipoId=$_REQUEST['TipoId'];
		$PacienteId=$_REQUEST['PacienteId'];
		$Ingreso=$_REQUEST['Ingreso'];
		$FechaIngreso=$_REQUEST['FechaIngreso'];
		$Estado=$_REQUEST['Estado'];

		if(!$this->FormaImpresion($TipoId,$PacienteId,$Ingreso,$FechaIngreso,$Estado)){
			return false;
		}
		return true;
	}

	function DatosPaciente($tipo,$documento)
 {
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha_nacimiento,
											residencia_direccion,
											residencia_telefono,
											tipo_estado_civil_id,
											sexo_id,
											primer_nombre,
											primer_apellido,
											tipo_pais_id,
											tipo_dpto_id,
											tipo_mpio_id
							FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
				return false;
			}
			$vars[0]=$result->fields[0];
			$vars[1]=$result->fields[1];
			$vars[2]=$result->fields[2];
			$vars[3]=$result->fields[3];
			$vars[4]=$result->fields[4];
			$vars[5]=$result->fields[5];
			$vars[6]=$result->fields[6];
			$vars[7]=$result->fields[7];
			$vars[8]=$result->fields[8];
			$vars[9]=$result->fields[9];
		}
		$result->Close();
		return $vars;
 }


}//fin clase user

?>

