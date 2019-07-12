<?php

/**
 * $Id: app_EE_PanelEnfermeria_user.php,v 1.4 2011/03/10 15:10:42 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_PanelEnfermeria_user extends classModulo
{

/**************************************************************************************
	FUNCIONES LOGUEO DE ESTACION
**************************************************************************************/

   /**
    * Retorna los datos de una estacion de enfermeria.
    *
    * @return array
    * @access private
    */
    function GetDatosEstacion($estacion_id)
    {
        if(!$_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id])
        {
            list($dbconn) = GetDBconn();
            global $ADODB_FETCH_MODE;
            $query="SELECT
                            f.razon_social as empresa_descripcion,
                            e.descripcion as centro_utilidad_descripcion,
                            d.descripcion as unidad_funcional_descripcion,
                            c.descripcion as departamento_descripcion,
                            b.descripcion as estacion_descripcion,
                            b.titulo_atencion_pacientes,
                            c.empresa_id,
                            c.centro_utilidad,
                            c.unidad_funcional,
                            c.departamento,
                            b.estacion_id,
                            b.hc_modulo_medico,
                            b.hc_modulo_enfermera,
                            b.hc_modulo_consulta_urgencias,
                            b.sw_consulta_urgencia,
                            b.sw_estacion_cirugia,
                            b.hc_modulo_cirujano,
                            b.hc_modulo_anestesiologo,
                            b.hc_modulo_ayudante,
                            b.hc_modulo_circulante,
                            b.hc_modulo_instrumentador,
                            b.hc_modulo_enfermeria

                    FROM    estaciones_enfermeria b,
                            departamentos c,
                            unidades_funcionales d,
                            centros_utilidad e,
                            empresas f

                    WHERE
                            b.estacion_id = '$estacion_id'
                            AND c.departamento = b.departamento
                            AND d.unidad_funcional = c.unidad_funcional
                            AND d.centro_utilidad = c.centro_utilidad
                            AND d.empresa_id = c.empresa_id
                            AND e.centro_utilidad = c.centro_utilidad
                            AND e.empresa_id = c.empresa_id
                            AND f.empresa_id = c.empresa_id";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "EE_PanelEnfermeria - main - SQL ERROR 1";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($resultado->EOF)
            {
                return NULL;
            }

            $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id] = $resultado->FetchRow();
            $resultado->Close();
        }
        return $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id];
    }
    
    function GetRegIngresoPaciente($paciente_id)
    {
      list($dbconn) = GetDBconn();
      $query = "SELECT * FROM ingresos WHERE paciente_id = ".$paciente_id." AND estado = 1 ORDER BY ingreso DESC LIMIT 1";
      $resultado = $dbconn->Execute($query);

      if ($dbconn->ErrorNo() != 0) {
        $this->error = "EE_PanelEnfermeria - main - SQL ERROR 1";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
      }

      while (!$resultado->EOF)
      {
        $vector2[]=$resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      return $vector2;
    }
    
	 /**
     *	@author: Jonier Murillo Hurtado
     *	@fecha: Agosto 18 de 2011
     *	Observaciones: Trae el último registo de atención de paciente
     */
  function ActualizarN($ingreso, $ingcue, $estadoi, $estadoc, $paciente_id, $ruta, $botgua, $id){

//    if ($botgua == $id){
        $VecIng = $this->getEstadosIngresos($ingreso);
        $estadod = $VecIng[0]['estado'];
        $esteaid = $VecIng[0]['estado_act_ina'];

        $VecCue = $this->getEstadosCuentas($ingcue);
        $estadoe = $VecIng[0]['estado'];
        $esteaie = $VecIng[0]['estado_act_ina'];
        
        
        if ($estadoi == 1 and $estadoc == 1){
            //ingreso
            $query  = "UPDATE ingresos SET estado_act_ina = '$estadod', estado = '2' WHERE ingreso = ".$ingreso;
            //cuenta
            $queryc = "UPDATE cuentas  SET estado_act_ina = '$estadoe', estado = '2' WHERE numerodecuenta = ".$ingcue;
            $this->ActualizarEstados($query);
            $this->ActualizarEstados($queryc);
        }else{
          if ($estadoi == 2 and $estadoc == 2){
              //ingreso
              $query = "UPDATE ingresos SET estado = '$esteaid', estado_act_ina = '0' WHERE ingreso = ".$ingreso;
              //cuenta
              $queryc = "UPDATE cuentas SET estado = '$esteaie', estado_act_ina = '0' WHERE numerodecuenta = ".$ingcue;
              $this->ActualizarEstados($query);
              $this->ActualizarEstados($queryc);
          }
        }
        return true;
      
//    }
//      return ($ingreso." - ".$ingcue." - ".$estadoi." - ".$estadoc." - ".$paciente_id." - ".$ruta);
  }
     
	 /**
     *	@author: Jonier Murillo Hurtado
     *	@fecha: Agosto 18 de 2011
     *	Observaciones: Trae el último registo de atención de paciente
     */
	function GetPermisoIngAct()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT sw_permiso 
              FROM estaciones_enfermeria_usuarios_componentes 
              WHERE usuario_id = ".UserGetUID()." and estacion_componente_id = '08' and estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'";
		$resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "EE_PanelEnfermeria - main - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while (!$resultado->EOF)
		{
			$useper[]=$resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
		}

		return $useper;
	}

     /**
     *	@author: Jonier Murillo Hurtado
     *	@fecha: Agosto 18 de 2011
     *	Observaciones: Trae el último registo de atención de paciente
     */
	function GetAtencion($paciente_id)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT i.ingreso, i.departamento, i.departamento_actual, i.estado, i.estado_act_ina,
					     (SELECT d.descripcion FROM departamentos d WHERE d.departamento = i.departamento_actual) as dpto
				  FROM ingresos i
				  WHERE i.paciente_id = '$paciente_id' AND i.estado = '1'
				  ORDER BY i.ingreso DESC LIMIT 1;";
		$resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "EE_PanelEnfermeria - main - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while (!$resultado->EOF)
		{
			$vector2[]=$resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
		}

		return $vector2;
	}

    /**
    * Metodo para obtener las Estacion de Enfermeria a las que tiene derecho un usuario
    *
    * @return array
    * @access private
    */
    function GetUserEstaciones()
    {
        if(!UserGetUID()) return null;

        if(empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()]))
        {
            list($dbconn) = GetDBconn();
            global $ADODB_FETCH_MODE;
            
            $query_tipop = "SELECT tipo_profesional FROM profesionales
            			   WHERE usuario_id = ".UserGetUID().";";
	       $resultado = $dbconn->Execute($query_tipop);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "EE_PanelEnfermeria - GetUserEstaciones - SQL ERROR 1";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            list($tipo_profesional) = $resultado->FetchRow();

            $query="
                    SELECT
                        f.razon_social as empresa_descripcion,
                        e.descripcion as centro_utilidad_descripcion,
                        d.descripcion as unidad_funcional_descripcion,
                        c.descripcion as departamento_descripcion,
                        b.descripcion as estacion_descripcion,
                        b.titulo_atencion_pacientes,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.unidad_funcional,
                        c.departamento,
                        a.estacion_id,
                        b.hc_modulo_medico,
                        b.hc_modulo_enfermera,
                        b.hc_modulo_consulta_urgencias,
                        b.hc_modulo_cirujano,
                        b.hc_modulo_anestesiologo,
                        b.hc_modulo_ayudante,
                        b.hc_modulo_circulante,
                        b.hc_modulo_instrumentador,
                        b.hc_modulo_enfermeria,
                        a.estacion_componente_id,
                        b.sw_consulta_urgencia,
                        b.sw_estacion_cirugia
                    
                    FROM
                        (
                            SELECT  a.*
                            FROM
                                (
                                    (
                                        SELECT a.estacion_id, b.estacion_componente_id

                                        FROM estaciones_enfermeria_usuarios a,
                                        estaciones_enfermeria_perfiles_componentes as b

                                        WHERE
                                        a.usuario_id = ".UserGetUID()."
                                        AND b.estacion_perfil_id = a.estacion_perfil_id
                                    )
                                    UNION
                                    (
                                        SELECT a.estacion_id, a.estacion_componente_id

                                        FROM estaciones_enfermeria_usuarios_componentes as a

                                        WHERE
                                        a.usuario_id = ".UserGetUID()."
                                        AND sw_permiso = '1'
                                    )
                                ) as a LEFT JOIN
                                (
                                    SELECT a.estacion_id, a.estacion_componente_id
                                    FROM estaciones_enfermeria_usuarios_componentes as a
                                    WHERE a.usuario_id = ".UserGetUID()."  AND sw_permiso = '0'
                                ) as b
                                ON (b.estacion_id = a.estacion_id AND b.estacion_componente_id = a.estacion_componente_id)

                            WHERE b.estacion_id IS NULL
                        ) as a,
                        estaciones_enfermeria b,
                        departamentos c,
                        unidades_funcionales d,
                        centros_utilidad e,
                        empresas f

                    WHERE
                        b.estacion_id = a.estacion_id
                        AND c.departamento = b.departamento
                        AND d.unidad_funcional = c.unidad_funcional
                        AND d.centro_utilidad = c.centro_utilidad
                        AND d.empresa_id = c.empresa_id
                        AND e.centro_utilidad = c.centro_utilidad
                        AND e.empresa_id = c.empresa_id
                        AND f.empresa_id = c.empresa_id

                    ORDER BY c.empresa_id, c.centro_utilidad, c.unidad_funcional, c.departamento, a.estacion_id";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "EE_PanelEnfermeria - GetUserEstaciones - SQL ERROR 1";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($resultado->EOF) return null;

            while($fila = $resultado->FetchRow())
            {
                $_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$fila['estacion_id']]['COMPONENTES'][$fila['estacion_componente_id']] = $fila['estacion_componente_id'];
                if(!$_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$fila['estacion_id']])
                {
                    unset($fila['estacion_componente_id']);
                    $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$fila['estacion_id']] = $fila;
                }
            }
            
            $resultado->Close();
            $_SESSION['EE_PanelEnfermeria']['TIPO_PROFESIONAL'][UserGetUID()]=$tipo_profesional;
        }

        return $_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()];
    }


    /**
    * Retorna si el usuario tiene permisos en una estacion o en un componente especifico
    *
    * @param $estacion_id obligatorio Estacion en la que esta validando que tenga permisos
    * @param $componente opcional
    * @return boolean
    * @access private
    */
    function GetUserPermisos($estacion_id,$componente=null)
    {
        if(!$estacion_id)
        {
            $estacion_id = $this->GetEstacionActiva($ID=true);
            if(empty($estacion_id)) return NULL;
        }

        if($componente)
        {
            if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]['COMPONENTES'][$componente]))
            {
                return true;
            }
            else
            {
                return null;
            }
        }

        if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]))
        {
            return true;
        }
        else
        {
            return null;
        }
    }
    
    /**
    * Metodo que retorna los datos de la estacion activa
    *
    * @param boolean $ID Indica si retorna unicamente el id de la estacion activa
    * @return array
    * @access private
    */
    function GetUserPerfil()
    {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
     	
          list($dbconn) = GetDBconn();
		$query = "SELECT estacion_perfil_id 
          		FROM estaciones_enfermeria_usuarios
                    WHERE usuario_id = ".UserGetUID()."
                    AND estacion_id = '$estacion_id';";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "EE_PanelEnfermeria - GetUserEstaciones - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($perfil) = $resultado->FetchRow();
          $this->USERPERFIL = $perfil;
          return true;
    }
   
    /**
    * Metodo que retorna los datos de la estacion activa
    *
    * @param boolean $ID Indica si retorna unicamente el id de la estacion activa
    * @return array
    * @access private
    */
    function &GetEstacionActiva($ID=false)
    {
        $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
        if(empty($estacion_id)) return NULL;

        $datosEstacionSeleccionada = &$this->GetDatosEstacion($estacion_id);
        if($ID && is_array($datosEstacionSeleccionada))
        {
            return $estacion_id;
        }
        else
        {
            return $datosEstacionSeleccionada;
        }
    }
    
   /**
    * Metodo para establecer la estacion activa..
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function SetEstacionActiva()
    {
        $this->DelEstacionActiva();
        if(empty($_REQUEST['estacion_id'])) return null;

        if($this->GetUserPermisos($_REQUEST['estacion_id']))
        {
            $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()] = $_REQUEST['estacion_id'];
            unset($_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
            return true;
        }
        return null;
    }
    
   /**
    * Metodo para borrar la estacion activa..
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function DelEstacionActiva()
    {
        unset($_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]);
        unset($_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
        return true;
    }   
    
   /**
    * Metodo para establecer la estacion activa..
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function SetEstacionActivaConsulta()
    {
        if(empty($_REQUEST['ActivarConsultaUrgencias'])) return null;
        
        $datosEE = &$this->GetEstacionActiva($ID=false);
        if($datosEE['sw_consulta_urgencia'])
        {
               if($_REQUEST['TipoConsultaUrgencias'])
               {
                    $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] = $_REQUEST['TipoConsultaUrgencias'];
               }
               else
               {
                    $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] = 1;
               }
        }
        
        
        
        if($this->GetUserPermisos($_REQUEST['estacion_id']))
        {
            $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()] = $_REQUEST['estacion_id'];
            if($_REQUEST['pacientes_en_consulta'])
            {
            	$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] = $_REQUEST['estacion_id'];
            }
            else
            {
            	unset($_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
            }
            return true;
        }
        return null;
    }
 
     /**
    * Metodo que retorna los datos de la estacion activa
    *
    * @param boolean $ID Indica si retorna unicamente el id de la estacion activa
    * @return array
    * @access private
    */
    function &GetEstacionActivaConsulta()
    {        
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          
          if(empty($estacion_id)) return NULL;
          if($_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] == $estacion_id)
          {
               return $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()];
          }
          else
          {
               return false;
          }
    }  
    
   /**
    * Metodo para borrar la estacion activa..
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function DelEstacionActivaConsulta()
    {
          unset($_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
          return true;
    }   

/**************************************************************************************
	FUNCIONES LOGUEO DE ESTACION
**************************************************************************************/

/**************************************************************************************
	ESTADISTICAS EE
**************************************************************************************/
           
   /**
    * Metodo que obtiene los datos estadisticos de la EE.
    *
    * @return boolean array()
    * @access private
    */
    function EstadisticasEE()
    {
		list($dbconn) = GetDBconn();
		
          //Conteo de Pacientes hospitalizados
          $query_H = "SELECT count(*)
          		  FROM movimientos_habitacion
                      WHERE estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                      AND fecha_egreso ISNULL;";
          $resultado = $dbconn->Execute($query_H);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($hospitalizados) = $resultado->FetchRow();
          
          //Conteo de Pacientes X ingresar
          $query_PI = "SELECT count(*)
          		  FROM estaciones_enfermeria_ingresos_pendientes
                      WHERE estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                      AND sw_estado = '1';";
          $resultado = $dbconn->Execute($query_PI);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($p_x_ingresar) = $resultado->FetchRow();



          //Conteo de Pacientes en conslta de URG
          $query_C = "SELECT count(*) 
          		  FROM pacientes_urgencias AS A, ingresos AS B
                      WHERE A.estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                      AND A.sw_estado IN ('1','7')
                      AND B.ingreso = A.ingreso
                      AND ( B.estado = '1');"; // OR  B.estado = '2'

          $permisoingresoactivo = 0;
          $permi = $this->GetPermisoIngAct();
          if (!empty($permi)){
            if ($permi[0]['sw_permiso'] == 1){
              $permisoingresoactivo = 1;
              $query_C = "SELECT count(*) 
                    FROM pacientes_urgencias AS A, ingresos AS B
                          WHERE A.estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                          AND A.sw_estado IN ('1','7')
                          AND B.ingreso = A.ingreso
                          AND ( B.estado = '1' OR  B.estado = '2');"; // OR  B.estado = '2'
            }
          }

          $resultado = $dbconn->Execute($query_C);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($consulta) = $resultado->FetchRow();
         
          //Conteo de Pacientes X egresar
	     $query_E = "SELECT (SELECT count(*)
          		   FROM hc_ordenes_medicas a, pacientes_urgencias b
                       WHERE b.estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                       AND a.ingreso = b.ingreso
                       AND a.sw_estado = '1'
                       AND a.hc_tipo_orden_medica_id IN ('06','07','99')
                       AND (b.sw_estado = '1' OR b.sw_estado = '7')) as salida_URG,
                      (SELECT count(*)
          		   FROM hc_ordenes_medicas a, movimientos_habitacion b
                       WHERE b.estacion_id = '".$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                       AND a.ingreso = b.ingreso
                       AND a.sw_estado = '1'
                       AND a.hc_tipo_orden_medica_id IN ('06','07','99')
                       AND b.fecha_egreso ISNULL) as salida_H;";
          $resultado = $dbconn->Execute($query_E);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $p_x_egresar = $resultado->FetchRow();
         	$p_egresar = $p_x_egresar[0] + $p_x_egresar[1];
          
          $estadisticas = array('hospitalizados'=>$hospitalizados,'p_x_ingresar'=>$p_x_ingresar,'en_consulta'=>$consulta,'p_x_egresar'=>$p_egresar);
          return $estadisticas;
    }
    
   /**
    * Metodo que retorna la informacion de los pacientes asignados a cada profesional.
    *
    * @return boolean array()
    * @access private
    */
    function Distribucion_PacientesConsultorios($estacion_id)
    {
		list($dbconn) = GetDBconn();
          
 		$query0= "SELECT count(*) 
          		FROM pacientes_urgencias AS A, ingresos AS B
                    WHERE A.estacion_id = '".$estacion_id."'
                    AND A.usuario_id = ".UserGetUID()."
                    AND A.sw_estado IN ('1','7')
                    AND B.ingreso = A.ingreso
                    AND B.estado = '1';";
          $resultado = $dbconn->Execute($query0);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($mi_consultorio) = $resultado->FetchRow();
                    
          $query1= "SELECT count(*) 
          		FROM pacientes_urgencias AS A, ingresos AS B
                    WHERE A.estacion_id = '".$estacion_id."'
                    AND A.usuario_id != ".UserGetUID()."
                    AND A.sw_estado IN ('1','7')
                    AND B.ingreso = A.ingreso
                    AND B.estado = '1';";
          $resultado = $dbconn->Execute($query1);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($otros_consultorios) = $resultado->FetchRow();
                    
		$query2= "SELECT count(*) 
          		FROM pacientes_urgencias AS A, ingresos AS B
                    WHERE A.estacion_id = '".$estacion_id."'
                    AND A.usuario_id ISNULL
                    AND A.tipo_id_tercero ISNULL
				AND A.tercero_id ISNULL
                    AND A.sw_estado IN ('1','7')
                    AND B.ingreso = A.ingreso
                    AND B.estado = '1';";
          $resultado = $dbconn->Execute($query2);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Obtener las Estadisticas de la EE.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          list($sin_consultorios) = $resultado->FetchRow();
          
          $pacientes = array('mi_consultorio'=>$mi_consultorio,'otros_consultorios'=>$otros_consultorios,'sin_consultorios'=>$sin_consultorios);
          return $pacientes;
    }

/**************************************************************************************
	ESTADISTICAS EE
**************************************************************************************/
    
/**************************************************************************************
	FUNCIONES DE LA ESTACION DE HOSPITALIZACION
**************************************************************************************/
    
    /**
    * Metodo para obtener los pacientes internados en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesInternados($estacion_id=null)
    {
        if(!$estacion_id)
        {
            $estacion_id = $this->GetEstacionActiva(true);
            if(!$estacion_id) return null;
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT a.*, b.evolucion_id
                    FROM
                        (
                            SELECT (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                                a.movimiento_id,
                                a.numerodecuenta,
                                a.fecha_ingreso AS fecha_hospitalizacion,
                                b.pieza,
                                a.cama,
                                d.ingreso,
                                d.fecha_ingreso,
                                d.paciente_id,
                                d.tipo_id_paciente,
                                e.primer_nombre,
                                e.segundo_nombre,
                                e.primer_apellido,
                                e.segundo_apellido,
                                e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
                                f.plan_id,
                                f.plan_descripcion,
                                f.tercero_id,
                                f.tipo_tercero_id,
                                g.nombre_tercero,
                                (SELECT estado FROM cuentas WHERE ingreso = d.ingreso ORDER BY estado ASC LIMIT 1 OFFSET 0) as cuentaestado,
                                d.estado as ingresosestado
                            FROM
                                movimientos_habitacion a,
                                camas b,
                                cuentas c,
                                ingresos d,
                                pacientes e,
                                planes f,
                                terceros g
                            WHERE
                                a.fecha_egreso IS NULL
                                AND estacion_id = '$estacion_id'
                                AND b.cama = a.cama
                                AND c.numerodecuenta = a.numerodecuenta
                                AND d.ingreso = a.ingreso
                                AND e.paciente_id = d.paciente_id
                                AND e.tipo_id_paciente = d.tipo_id_paciente
                                AND f.plan_id = c.plan_id
                                AND g.tercero_id = f.tercero_id
                                AND g.tipo_id_tercero = f.tipo_tercero_id
                        ) AS a LEFT JOIN hc_evoluciones b
                                ON (b.ingreso = a.ingreso
                                    AND b.usuario_id = ".UserGetUID()."
                                    AND b.estado = '1')
                    ORDER BY a.cama, a.pieza;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesInternados";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;

    }//fin del metodo

    /**
    * Metodo para obtener los pacientes pendientes por ingreso en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesPorIngresar($estacion_id=null)
    {
        if(!$estacion_id)
        {
            $estacion_id = $this->GetEstacionActiva(true);
            if(!$estacion_id) return null;
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "SELECT    a.*,
                            b.descripcion as descripcion_estacion_origen,
                            c.descripcion as descripcion_tipo_cama,
                            d.diagnostico_nombre,
                            CASE WHEN e.nombre_tercero IS NOT NULL THEN e.nombre_tercero ELSE a.nombre_medico_externo END as profesional
                    FROM
                        (
                        SELECT
                            a.numero_registro,
                            a.estacion_origen,
                            a.tipo_cama_id,
                            a.diagnostico_id,
                            a.observaciones,
                            a.sw_aislamiento,
                            a.tipo_id_tercero,
                            a.tercero_id,
                            a.nombre_medico_externo,
                            a.fecha_registro,
                            a.usuario_registro,
                            a.numerodecuenta,
                            (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                            c.ingreso,
                            c.fecha_ingreso,
                            d.plan_descripcion,
                            e.paciente_id,
                            e.tipo_id_paciente,
                            e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo

                        FROM
                            estaciones_enfermeria_ingresos_pendientes a,
                            cuentas b,
                            ingresos c,
                            planes d,
                            pacientes e

                        WHERE
                            a.estacion_id = '$estacion_id'
                            AND a.sw_estado = '1'
                            AND b.numerodecuenta = a.numerodecuenta
                            AND c.ingreso = b.ingreso
                            AND d.plan_id = b.Plan_id
                            AND e.paciente_id = c.paciente_id
                            AND e.tipo_id_paciente = c.tipo_id_paciente
                        ) AS a
                        LEFT JOIN estaciones_enfermeria b ON (a.estacion_origen = b.estacion_id)
                        LEFT JOIN tipos_camas c ON (c.tipo_cama_id = a.tipo_cama_id)
                        LEFT JOIN diagnosticos d ON (d.diagnostico_id = a.diagnostico_id)
                        LEFT JOIN terceros e ON (e.tipo_id_tercero = a.tipo_id_tercero AND e.tercero_id = a.tercero_id)
                    ORDER BY a.fecha_registro";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesPorIngresar";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;
    }
    
/**************************************************************************************
	FUNCIONES DE LA ESTACION DE HOSPITALIZACION
**************************************************************************************/
    
/**************************************************************************************
	FUNCIONES DE LA ESTACION DE CONSULTA DE URGENCIAS
**************************************************************************************/

    /**
    * Metodo para obtener los pacientes en consulta de urgencias en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesConsultaUrgencias($estacion_id=null,$sw)
    {
        if(!$estacion_id)
        {
            $estacion_id = $this->GetEstacionActiva(true);
            if(!$estacion_id) return null;
        }
        
        if($sw=='1')
        {
        	$filtro = "AND a.usuario_id = ".UserGetUID()."";
        }elseif($sw=='2')
        {
        	$filtro = "AND a.usuario_id != ".UserGetUID()."";
        }elseif($sw=='3')
        {
        	$filtro = "AND a.tercero_id ISNULL AND a.tipo_id_tercero ISNULL AND a.usuario_id ISNULL";
        }elseif($sw=='4')
        {
        	$filtro = "";
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        
        $sql = "SELECT  a.*,
                        b.evolucion_id,
                        CASE WHEN c.nivel_triage_id <> 0 THEN c.nivel_triage_id
                        ELSE c.nivel_triage_asistencial END AS nivel_triage_id,
                        c.plan_id as plan_id_triage,
                        c.triage_id,
                        c.punto_triage_id,
                        c.punto_admision_id,
                        c.sw_no_atender,
                        d.descripcion as descripcion_triage,
                        p.marca_prioridad_atencion,
                        (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
						CASE WHEN cant_evo_prof IS NULL THEN 0 ELSE 1 END as cant_evo
               FROM
               (
                    SELECT
                         c.paciente_id,
                         c.tipo_id_paciente,
                         c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo,
                         c.fecha_nacimiento,
                         b.ingreso,
                         b.fecha_ingreso,
                         a.estacion_id,
                         a.triage_id,
                         a.sw_estado,
                         (SELECT numerodecuenta 
                          FROM cuentas 
                          WHERE ingreso = b.ingreso 
                          ORDER BY estado ASC
                          LIMIT 1 OFFSET 0) as numerodecuenta,
                         (SELECT plan_id
                          FROM cuentas 
                          WHERE ingreso = b.ingreso 
                          ORDER BY estado ASC
                          LIMIT 1 OFFSET 0) as plan_id,
                          (SELECT estado
                          FROM cuentas 
                          WHERE ingreso = b.ingreso 
                          ORDER BY estado ASC
                          LIMIT 1 OFFSET 0) as cuentaestado,
                          b.estado as ingresosestado,
						  (SELECT	MAX(HC.ingreso)
						   FROM		hc_evoluciones HC,
									profesionales P
						   WHERE	HC.ingreso = b.ingreso
						   AND      HC.usuario_id = P.usuario_id
						   AND      P.tipo_profesional IN ('1','2')
						   GROUP BY HC.ingreso
						   ) AS cant_evo_prof
                         
                    FROM
                         pacientes_urgencias a,
                         ingresos as b,
                         pacientes as c
     
                    WHERE
                         a.estacion_id = '$estacion_id'
                         AND a.sw_estado IN ('1','7')
                         $filtro                  
                         AND b.ingreso = a.ingreso 
                         AND (b.estado = '1')
                         AND c.paciente_id = b.paciente_id
                         AND c.tipo_id_paciente = b.tipo_id_paciente
                         
                    ORDER BY b.fecha_ingreso DESC
               ) as a
               LEFT JOIN hc_evoluciones b ON ( b.ingreso = a.ingreso
                                               AND b.usuario_id = ".UserGetUID()."
                                               AND b.estado = '1' )
			   							
               LEFT JOIN triages c ON (c.triage_id = a.triage_id)
               LEFT JOIN niveles_triages d ON ( d.nivel_triage_id = c.nivel_triage_id
                                                AND c.nivel_triage_id != 0
                                                AND c.sw_estado != '9'),
               planes p
               WHERE a.plan_id = p.plan_id
               ORDER BY cant_evo ASC,nivel_triage_id ASC, A.fecha_ingreso DESC";
               
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - BuscarPacientesConsulta_Urgencias";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;
    }

    function GetPacientesConsultaUrgenciasConPermiso($estacion_id=null,$sw)
    {
        if(!$estacion_id)
        {
            $estacion_id = $this->GetEstacionActiva(true);
            if(!$estacion_id) return null;
        }
        
        if($sw=='1')
        {
        	$filtro = "AND a.usuario_id = ".UserGetUID()."";
        }elseif($sw=='2')
        {
        	$filtro = "AND a.usuario_id != ".UserGetUID()."";
        }elseif($sw=='3')
        {
        	$filtro = "AND a.tercero_id ISNULL AND a.tipo_id_tercero ISNULL AND a.usuario_id ISNULL";
        }elseif($sw=='4')
        {
        	$filtro = "";
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        
        $sql = "SELECT  a.*,
                        b.evolucion_id,
                        CASE WHEN c.nivel_triage_id <> 0 THEN c.nivel_triage_id
                        ELSE c.nivel_triage_asistencial END AS nivel_triage_id,
                        c.plan_id as plan_id_triage,
                        c.triage_id,
                        c.punto_triage_id,
                        c.punto_admision_id,
                        c.sw_no_atender,
                        d.descripcion as descripcion_triage,
                        p.marca_prioridad_atencion,
                        (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
						CASE WHEN cant_evo_prof IS NULL THEN 0 ELSE 1 END as cant_evo
               FROM
               (
                    SELECT
                         c.paciente_id,
                         c.tipo_id_paciente,
                         c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo,
                         c.fecha_nacimiento,
                         b.ingreso,
                         b.fecha_ingreso,
                         a.estacion_id,
                         a.triage_id,
                         a.sw_estado,
                         (SELECT numerodecuenta 
                          FROM cuentas 
                          WHERE ingreso = b.ingreso 
                          ORDER BY estado ASC
                          LIMIT 1 OFFSET 0) as numerodecuenta,
                         (SELECT plan_id
                          FROM cuentas 
                          WHERE ingreso = b.ingreso 
                          ORDER BY estado ASC
                          LIMIT 1 OFFSET 0) as plan_id,
                          (SELECT estado
                          FROM cuentas 
                          WHERE ingreso = b.ingreso 
                          ORDER BY estado ASC
                          LIMIT 1 OFFSET 0) as cuentaestado,
                          b.estado as ingresosestado,
						  (SELECT	MAX(HC.ingreso)
						   FROM		hc_evoluciones HC,
									profesionales P
						   WHERE	HC.ingreso = b.ingreso
						   AND      HC.usuario_id = P.usuario_id
						   AND      P.tipo_profesional IN ('1','2')
						   GROUP BY HC.ingreso
						   ) AS cant_evo_prof
                         
                    FROM
                         pacientes_urgencias a,
                         ingresos as b,
                         pacientes as c
     
                    WHERE
                         a.estacion_id = '$estacion_id'
                         AND a.sw_estado IN ('1','7')
                         $filtro                  
                         AND b.ingreso = a.ingreso 
                         AND (b.estado = '1' OR b.estado = '2')
                         AND c.paciente_id = b.paciente_id
                         AND c.tipo_id_paciente = b.tipo_id_paciente
                         
                    ORDER BY b.fecha_ingreso DESC
               ) as a
               LEFT JOIN hc_evoluciones b ON ( b.ingreso = a.ingreso
                                               AND b.usuario_id = ".UserGetUID()."
                                               AND b.estado = '1' )
			   							
               LEFT JOIN triages c ON (c.triage_id = a.triage_id)
               LEFT JOIN niveles_triages d ON ( d.nivel_triage_id = c.nivel_triage_id
                                                AND c.nivel_triage_id != 0
                                                AND c.sw_estado != '9'),
               planes p
               WHERE a.plan_id = p.plan_id
               ORDER BY cant_evo ASC,nivel_triage_id ASC, A.fecha_ingreso DESC";
               
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - BuscarPacientesConsulta_Urgencias";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;
    }
    
/**************************************************************************************
	FUNCIONES DE LA ESTACION DE CONSULTA DE URGENCIAS
**************************************************************************************/
        
/**************************************************************************************
	FUNCIONES DE LA ESTACION DE CIRUGIA
**************************************************************************************/

    /**
    * Metodo para obtener los pacientes internados en una estacion de Cirugia
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesInternadosCirugia($departamento)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT a.*, b.evolucion_id
        			 	
                    FROM
                        (
                  		  SELECT
                            	 (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                                a.numero_registro,
                                a.numerodecuenta,
                                a.fecha_ingreso AS fecha_ingreso_cirugia,
                                a.departamento,
                                a.programacion_id,
                                a.usuario_id,
                                a.sw_estado,
                                a.estacion_origen,
                                a.observaciones,
                                a.fecha_egreso,
                                d.ingreso,
                                d.fecha_ingreso,
                                d.paciente_id,
                                d.tipo_id_paciente,
                                e.primer_nombre,
                                e.segundo_nombre,
                                e.primer_apellido,
                                e.segundo_apellido,
                                e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
                                f.plan_id,
                                f.plan_descripcion,
                                f.tercero_id,
                                f.tipo_tercero_id,
                                g.nombre_tercero

                            FROM
                                estacion_enfermeria_qx_pacientes_ingresados a,
                                cuentas c,
                                ingresos d,
                                pacientes e,
                                planes f,
                                terceros g
                            WHERE
						  (a.sw_estado = '0' OR a.sw_estado = '1')
                                AND a.fecha_egreso IS NULL
                                AND a.departamento = '".$departamento."'
                                AND c.numerodecuenta = a.numerodecuenta
                                AND d.ingreso = c.ingreso
                                AND e.paciente_id = d.paciente_id
                                AND e.tipo_id_paciente = d.tipo_id_paciente
                                AND f.plan_id = c.plan_id
                                AND g.tercero_id = f.tercero_id
                                AND g.tipo_id_tercero = f.tipo_tercero_id
                        ) AS a LEFT JOIN hc_evoluciones b
                                ON (b.ingreso = a.ingreso
                                    AND b.usuario_id = ".UserGetUID()."
                                    AND b.estado = '1')
                    ORDER BY fecha_ingreso_cirugia;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesInternados";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;

    }//fin del metodo

    
    /**
    * Metodo para obtener los pacientes pendientes por ingreso en una estacion de Cirugia
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesPorIngresarCirugia($departamento)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "SELECT    a.*,
                            b.descripcion as descripcion_estacion_origen
                    FROM
                        (
                        SELECT
                            a.numero_registro,
                            a.numerodecuenta,
                            a.departamento,
                            a.sw_estado,
                            a.estacion_origen,
                            a.fecha_registro,
                            a.observaciones,
                            a.usuario_id,
                            a.fecha_ingreso_estacion,
                            a.programacion_id,
                            c.ingreso,
                            c.fecha_ingreso,
                            d.plan_descripcion,
                            e.paciente_id,
                            e.tipo_id_paciente,
                            e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo

                        FROM
                            estacion_enfermeria_qx_pendientes_ingresar a,
                            cuentas b,
                            ingresos c,
                            planes d,
                            pacientes e

                        WHERE
                            a.sw_estado = '1'
                            AND a.departamento = '".$departamento."'
                            AND b.numerodecuenta = a.numerodecuenta
                            AND c.ingreso = b.ingreso
                            AND d.plan_id = b.Plan_id
                            AND e.paciente_id = c.paciente_id
                            AND e.tipo_id_paciente = c.tipo_id_paciente
                        ) AS a
                        LEFT JOIN estaciones_enfermeria b ON (a.estacion_origen = b.estacion_id)
                    ORDER BY a.fecha_registro";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesPorIngresar";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;
    }

    /**
    * Metodo para obtener los datos del quirofano donde se realizara Procedimiento QX.
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
 	function QuirofanoPaciente($programacion)
     {
          list($dbconn) = GetDBconn();
		$sql = "SELECT quirofano_id 
                  FROM qx_quirofanos_programacion
                  WHERE programacion_id = ".$programacion.";";
          $resultado = $dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
		return $resultado->fields[0];
     
     }
     
    /**
    * Busco si el paciente tiene pendiente una programacion para Cirugia.
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
     function ValidarProgramacion_Cirugia($datosPaciente)
     {
          list($dbconn) = GetDBconn();
          $query_programacion = "SELECT A.programacion_id
                         
                                 FROM qx_programaciones AS A
                                 LEFT JOIN estacion_enfermeria_qx_pendientes_ingresar AS C ON (A.programacion_id=C.programacion_id),
                                      qx_quirofanos_programacion AS B 
                                   
                                 WHERE A.paciente_id = '".$datosPaciente[paciente_id]."'
                                 AND A.tipo_id_paciente = '".$datosPaciente[tipo_id_paciente]."'
                                 AND A.programacion_id = B.programacion_id 
                                 AND B.qx_tipo_reserva_quirofano_id = '3' 
                                 AND A.estado = '1' 
                                 AND C.programacion_id IS NULL;";
          $result = $dbconn->Execute($query_programacion);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          list($programacion) = $result->FetchRow();
          return $programacion;
     }
     
     /*
     * PacienteRemitidoOservacioQX
     * Si el paciente fue remitido a Observacion, se actualiza la conducta y su estado.
     */
     function PacienteRemitidoOservacionQX($cuenta,$conducta,$sw)
     {
     	list($dbconn) = GetDBconn();
          
          if($sw == "0")
          {
          	//Actualizo Conducta
               $query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
               			    WHERE ingreso = ".$conducta[ingreso]."
                                  AND evolucion_id = ".$conducta[evolucion_id]."
                                  AND hc_tipo_orden_medica_id = '10';";
               $result = $dbconn->Execute($query_Conducta);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          	
               //Actualizo Estado
               $query_Estado = "UPDATE estacion_enfermeria_qx_pacientes_ingresados
               			  SET sw_estado = '0'
                                WHERE numerodecuenta = ".$cuenta."
                                AND fecha_egreso IS NULL;";
               $result = $dbconn->Execute($query_Estado);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               return true;
          }
          else
          {
               //Pacientes en Observacion de Cirugia.
               $query_OBS = "SELECT COUNT(*)
               		    FROM estacion_enfermeria_qx_pacientes_ingresados
                             WHERE numerodecuenta = ".$cuenta."
                             AND sw_estado = '0';";
               $result = $dbconn->Execute($query_OBS);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               if($result->fields[0] >= 1)
               { return '1';}else{ return '0';}
          }
          
     }
     
/**************************************************************************************
	FUNCIONES DE LA ESTACION DE CIRUGIA
**************************************************************************************/
     
/**************************************************************************************
	FUNCIONES DE BUSQUEDA DE INFORMACION DE LOS DATOS DE INGRESO DEL PACIENTE
**************************************************************************************/
    
     /**
     *	CallMostrarDatosIngreso
     *
     *	Llamado desde la vista 1 -> link ver datos ingreso
     *	CallMostrarDatosIngreso
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallMostrarDatosIngreso()
     {
          if(!$this->MostrarDatosIngreso($_REQUEST['ingresoID'],$_REQUEST['retorno'],$_REQUEST['datos_estacion'],$_REQUEST['modulito'],$_REQUEST['datos']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"MostrarDatosIngreso\"";
               return false;
          }
          return true;
     }
    
    /**
    * Metodo para obtener los datos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
    function GetDatosPaciente($ingreso)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query=" SELECT  a.ingreso, 
                                    b.historia_numero, 
                                    b.historia_prefijo,
                                    c.primer_apellido,
                                    c.segundo_apellido, 
                                    c.primer_nombre, 
                                    c.segundo_nombre, 
                                    sexo_id, 
                                    c.fecha_nacimiento,
                                    c.residencia_direccion,
                                    c.residencia_telefono, 
                                    c.tipo_pais_id, 
                                    c.tipo_dpto_id,
                                    c.tipo_mpio_id, 
                                    i.pais, 
                                    j.departamento, 
                                    h.municipio,
                                    e.tercero_id, 
                                    e.tipo_tercero_id,
                                    g.nombre_tercero, 
                                    e.plan_id, 
                                    e.plan_descripcion, 
                                    f.tipo_afiliado_nombre, 
                                    c.paciente_id,
                                    c.tipo_id_paciente, 
                                    a.estado, 
                                    gestacion.estado as gestacion,
                                    c.observaciones as observaciones_pacien
                       FROM    ingresos as a, historias_clinicas as b
                       LEFT JOIN gestacion on (b.paciente_id=gestacion.paciente_id and b.tipo_id_paciente=gestacion.tipo_id_paciente),
                                     pacientes as c
                       LEFT JOIN tipo_mpios as h on (c.tipo_pais_id=h.tipo_pais_id and c.tipo_dpto_id=h.tipo_dpto_id and   c.tipo_mpio_id=h.tipo_mpio_id)
                       LEFT JOIN tipo_pais as i on (c.tipo_pais_id=i.tipo_pais_id)
                       LEFT JOIN tipo_dptos as j on (c.tipo_pais_id=j.tipo_pais_id and c.tipo_dpto_id=j.tipo_dpto_id),
                                     cuentas as d 
                       LEFT JOIN tipos_afiliado as f on (d.tipo_afiliado_id=f.tipo_afiliado_id),
                                     planes as e, 
                                     terceros as g
                       WHERE    a.ingreso=".$ingreso." 
                       AND         a.tipo_id_paciente=b.tipo_id_paciente 
                       AND         a.paciente_id=b.paciente_id 
                       AND         a.tipo_id_paciente=c.tipo_id_paciente 
                       AND         a.paciente_id=c.paciente_id 
                       AND         d.ingreso=a.ingreso 
                       AND         d.plan_id=e.plan_id 
                       AND         e.tipo_tercero_id=g.tipo_id_tercero 
                       AND         e.tercero_id=g.tercero_id;";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                return false;
            }
            else {
                if (!$result) {
                    $this->error = "Error al tratar de realizar la consulta.<br>";
                    $this->mensajeDeError = $query;
                    return false;
                }
                $paciente = $result->GetRowAssoc($ToUpper = false);
            }
            return $paciente;
    }

    /**
    * Metodo para obtener los contactos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
    function &GetContactosPaciente($ingreso)
    {
        if(empty($ingreso)) return null;
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $query = "SELECT
                        C.nombre_completo,
                        C.telefono,
                        C.direccion,
                        T.descripcion AS parentesco

                  FROM  hc_contactos_paciente C,
                        tipos_parentescos T

                  WHERE C.ingreso = $ingreso
                        AND T.tipo_parentesco_id = C.tipo_parentesco_id";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al ejecutar la conexion";
            $this->mensajeDeError = "Ocurrió un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
            return false;
        }

        if($result->EOF) return null;
        $ContactosPaciente = $result->GetRows();
        $result->Close();
        return $ContactosPaciente;
    }
    

/**************************************************************************************
	FUNCIONES DE BUSQUEDA DE INFORMACION DE LOS DATOS DE INGRESO DEL PACIENTE
**************************************************************************************/

/**************************************************************************************
	FUNCIONES PARA CONTROLES INFORMATIVOS DE PACIENTES
**************************************************************************************/
        
     /**
     * Metodo que permite Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
     *
     * @param timestamp fecha de ingreso del paciente
     * @return integer
     * @access Public
     */
     function BusquedaConducta($ingreso)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;
     
          $query = "SELECT A.hc_tipo_orden_medica_id, B.descripcion, A.ingreso, A.evolucion_id
                    FROM hc_ordenes_medicas A, 
                         hc_tipos_ordenes_medicas B
                    WHERE A.ingreso = ".$ingreso."
                    AND A.hc_tipo_orden_medica_id = B.hc_tipo_orden_medica_id
                    AND sw_estado = '1';"; 

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al intentar seleccionar el tipo de conducta medica.";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $conducta = $resultado->FetchRow();
          return $conducta;
     }
    
     /**
     * Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
     *
     * @param timestamp fecha de ingreso del paciente
     * @return integer
     * @access Public
     */
     function GetDiasHospitalizacion($fecha_ingreso)
     {
          if(empty($fecha_ingreso)) return null;
     
          $date1 = date('Y-m-d H:i:s');
     
          $fecha_in=explode(".",$fecha_ingreso);
          $date2=$fecha_in[0];
     
          $s = strtotime($date1)-strtotime($date2);
          $d = intval($s/86400);
          $s -= $d*86400;
          $h = intval($s/3600);
          $s -= $h*3600;
          $m = intval($s/60);
          $s -= $m*60;
     
          if($d>0)
          {
               $dif= "$d  dias ";
          }
          else
          {
               $dif = "$h:$m horas ";
          }
          return $dif;
     }

     /**
	*		GetPacMedicamentosPorSolicitar
	*
	*		obtiene los pacientes que tengan medicamentos recetados vigentes
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacMedicamentosPorSolicitar($ingreso)
	{
          list($dbconn) = GetDBconn();
          $query="SELECT COUNT(*)
          	     FROM hc_formulacion_medicamentos
                    WHERE ingreso = ".$ingreso."
                    AND sw_estado IN ('1','2');";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if($resulta->fields[0]>0)
          {
               return 1;
          }else
          {
			$query = "";
               $query="SELECT COUNT(*)
          	        FROM hc_formulacion_mezclas
                       WHERE ingreso = ".$ingreso."
                       AND sw_estado IN ('1','2');";
			$resulta=$dbconn->execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               if($resulta->fields[0]>0)
               {
                    return 1;
               }
          }
		return "ShowMensaje";
	}
     
     /**
     *	BusquedaSolicitudes_Estacion
     *
     *	Obtiene las solicitudes despachadas desde la bodega y q fueron solicitadas por la
     *	respectiva EE.
     *
     *	@Author Tizziano Perea.
     *	@access Public
     *	@return array, false ó string
     *	@param array => datos de la ubicacion actual ($datos_estacion)
     */
     function BusquedaSolicitudes_Estacion($datos_estacion)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT count(*)
                    FROM hc_solicitudes_suministros_estacion A, 
                         hc_solicitudes_suministros_estacion_detalle B
                    WHERE A.estacion_id= '".$datos_estacion[estacion_id]."'
                    AND (B.sw_estado = '2' OR B.sw_estado = '1' OR B.sw_estado = '0');";
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $result = $dbconn->Execute($query);
          list($conteo) = $result->FetchRow();
          return $conteo;
     }

     /**
     * Funcion de verificacion de despacho de medicamentos.
     *
     * @return integer
     * @access Public
     */
	function GetPacientesConMedicamentosPorDesp($letra,$estacion,$op)
	{
          list($dbconn) = GetDBconn();

          for($i=0;$i<2;$i++)
          {
               if($i==0)
               {$datosPaciente = $_SESSION['EE_PanelEnfermeria']['listadoPacientes'];}
               else
               {$datosPaciente = $_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'];}
               
               foreach($datosPaciente as $k=>$filaPaciente)
               {
                    $query="SELECT SUM(cantidad_pendiente_por_recibir) 
                    	   FROM bodega_paciente
                            WHERE ingreso=".$filaPaciente['ingreso'].";";
                    $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    if($resulta->fields[0]>0)
                    {
                         return 1;
                         break;
                    }
               }
          }
          return '';
	}

     /**
     * Funcion de verificacion de despacho de medicamentos.
     *
     * @return integer
     * @access Public
     */
	function GetPacientesConMedicamentosPorSolicitar()
	{
          list($dbconn) = GetDBconn();
          for($i=0;$i<2;$i++)
          {
               if($i==0)
               {$datosPaciente = $_SESSION['EE_PanelEnfermeria']['listadoPacientes'];}
               else
               {$datosPaciente = $_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'];}
               
               foreach($datosPaciente as $k=>$filaPaciente)
               {
                    $query="SELECT SUM(cantidad_en_solicitud) 
                    	   FROM bodega_paciente
                            WHERE ingreso=".$filaPaciente['ingreso'].";";
                    $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    if($resulta->fields[0]>0)
                    {
                         return 1;
                         break;
                    }
               }
          }
          return '';
	}

     /**
     * Funcion de verificacion de devoluciones de medicamentos e insumos.
     *
     * @return integer
     * @access Public
     */
	function GetDevolucion_IM_Pendientes($letra)
	{
          list($dbconn) = GetDBconn();

          for($i=0;$i<2;$i++)
          {
               if($i==0)
               {$datosPaciente = $_SESSION['EE_PanelEnfermeria']['listadoPacientes'];}
               else
               {$datosPaciente = $_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'];}
               
               foreach($datosPaciente as $k=>$filaPaciente)
               {
                    $query="SELECT SUM(stock_almacen) 
                    	   FROM bodega_paciente
                            WHERE ingreso=".$filaPaciente['ingreso']."
                            AND sw_tipo_producto = '$letra';";
                    $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    if($resulta->fields[0]>0)
                    {
                         return 1;
                         break;
                    }
               }
          }
          return '';
	}
          
     /*
     * Esta funcion nos permite realizar una revision de las Ordenes de Servicio
     * Que tiene el paciente para ser autorizadas.
     */
     function ConteoOrdenesPaciente($ingreso, $paciente_id, $tipo_id_paciente)
     {
          list($dbconn) = GetDBconn();
          
          $sql="SELECT count(A.hc_os_solicitud_id)
                FROM hc_os_solicitudes AS A, hc_evoluciones AS I
                WHERE A.paciente_id = '".$paciente_id."'
                AND A.tipo_id_paciente = '".$tipo_id_paciente."'
                AND I.ingreso = ".$ingreso."
                AND I.evolucion_id = A.evolucion_id
                AND A.sw_estado = '1';";
          
          $res=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $contador=$res->fields[0];
          $res->Close();
          if($contador >0){return 1;}

          $sql="SELECT count(A.hc_os_solicitud_id)
			 FROM hc_os_solicitudes AS A, hc_evoluciones AS I, 
     		 	 os_maestro AS C
                WHERE A.paciente_id = '".$paciente_id."'
                AND A.tipo_id_paciente = '".$tipo_id_paciente."'
                AND A.hc_os_solicitud_id = C.hc_os_solicitud_id 
                AND C.sw_estado IN ('1','2')
                AND I.ingreso = ".$ingreso."
                AND I.evolucion_id = A.evolucion_id";

          $result=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $contador2=$result->fields[0];
          $result->Close();
          if($contador2 >0){return 1;}else{return 0;}
     }
 
/**************************************************************************************
	FUNCIONES PARA CONTROLES INFORMATIVOS DE PACIENTES
**************************************************************************************/
     
/**************************************************************************************
	FUNCIONES DE PARA EL MANEJO DE LOS PRODUCTOS DESPACHADOS A USUARIOS DE LA ESTACION
**************************************************************************************/

     /*
     * Esta funcion nos permite Obtener los datos de las solicitudes de medicamentos o insumos que fueron 
     * realizadas por los profesionales de enfermeria y que posteriormentes seran cargadas a las
     * cuentas de los respectivos pacientes
     */
     function BuscarDatos_ResponsableIyM($datos_estacion)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
          $query  ="SELECT SUM(B.cantidad-B.cantidad_ajustada) AS cantidad, A.usuario_id, 
          			  A.bodega, A.estacion_id, B.codigo_producto, C.descripcion,
                           A.inv_solicitudes_iym_id, B.consecutivo
                    FROM inv_solicitudes_iym_responsable AS A, 
                    	inv_solicitudes_iym_responsable_d AS B, inventarios_productos AS C
                    WHERE A.responsable_solicitud = ".UserGetUID()."
                    AND A.estacion_id = '".$datos_estacion[estacion_id]."'
                    AND A.inv_solicitudes_iym_id = B.inv_solicitudes_iym_id
                    AND B.codigo_producto = C.codigo_producto
                    AND B.sw_estado = '1'
                    GROUP BY A.usuario_id, A.bodega, A.estacion_id, 
                    	 B.codigo_producto, C.descripcion,
                          A.inv_solicitudes_iym_id, B.consecutivo
                    ORDER BY cantidad DESC, C.descripcion;";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while($data = $result->FetchRow())
          {
          	$datos_IyM[$data[inv_solicitudes_iym_id]][] = $data;
          }
          return $datos_IyM;
     }
     
     /*
     * Funcion que obtiene el nombre del profesional de enfermeria
     */
     function TraerUsuario($usuario)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT usuario, nombre
          		FROM system_usuarios
                    WHERE usuario_id = ".$usuario.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
     /*
     * Funcion que obtiene el nombre de la Estacion de Enfermeria.
     */     
     function TraerEstacion($EE)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query  ="SELECT descripcion
          		FROM estaciones_enfermeria
                    WHERE estacion_id = ".$EE.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
     /*
     * Funcion que obtiene el nombre de la Bodega asociada a la EE.
     */     
     function TraerBodega($bodega,$datos_estacion)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT descripcion
          		FROM bodegas
                    WHERE bodega = '".$bodega."'
                    AND empresa_id = '".$datos_estacion[empresa_id]."'
                    AND centro_utilidad = '".$datos_estacion[centro_utilidad]."';";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
     /**
	*		GetEstacionBodega
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetEstacionBodega($datos,$sw)
	{
		if($sw==1)
		{
			$filtro="AND b.sw_consumo_directo='0'";
		}
		elseif($sw==2)
		{
			$filtro="AND b.sw_consumo_directo='1'";
		}
		list($dbconn) = GetDBconn();
     	$query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion
	             FROM bodegas_estaciones a,bodegas b
                  WHERE  a.estacion_id='".$datos[estacion_id]."'
                  AND a.centro_utilidad=b.centro_utilidad
                  AND a.empresa_id=b.empresa_id
                  AND a.bodega=b.bodega
                  $filtro
                  AND a.centro_utilidad='".$datos[centro_utilidad]."'
                  AND a.empresa_id='".$datos[empresa_id]."'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     	$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          	return false;
		}

          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
     	return $vector;
	}
     
     /*
     * Funcion que cuenta Permite devolver las solicitudes de los despachos
     * realizados a responsables.
     */
     function CancelacionProductos()
     {
		list($dbconn) = GetDBconn();
          if($_REQUEST['Cancelacion'] == '1')
          {
          	$sql = "UPDATE inv_solicitudes_iym_responsable_d SET sw_estado = '3'
               	   WHERE inv_solicitudes_iym_id = ".$_REQUEST['Solicitud']."
                       AND sw_estado != '2';";
               $dbconn->Execute($sql);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar el estado de la solicitud de las devoluciones<br><br>".$dbconn->ErrorMsg()."<br><br>".$sql;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          else
          {
           	for($i=0; $i<sizeof($_REQUEST['Op']); $i++)
               {
                    $sql = "UPDATE inv_solicitudes_iym_responsable_d SET sw_estado = '3'
                            WHERE inv_solicitudes_iym_id = ".$_REQUEST['Solicitud']."
                            AND codigo_producto = '".$_REQUEST['Op'][$i]."';";
                    $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Ocurrió un error al actualizar el estado de la solicitud de las devoluciones<br><br>".$dbconn->ErrorMsg()."<br><br>".$sql;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
         
          }

          $insert = "INSERT INTO inv_solicitudes_iym_responsable_devolucion (inv_solicitudes_iym_id,
          													  usuario_id,
                                                                             observacion,
                                                                             fecha_registro,
                                                                             estacion_id,
                                                                             bodega)
          												VALUES (".$_REQUEST['Solicitud'].",
                                                                      	   ".UserGetUID().",
                                                                              '".$_REQUEST['obs']."',
                                                                              'NOW()',
                                                                              '".$_REQUEST['estacion_id']."',
                                                                              '".$_REQUEST['Bodega']."');";
          $dbconn->Execute($insert);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al insertar en inv_solicitudes_iym_responsable_devolucion<br><br>".$dbconn->ErrorMsg()."<br><br>".$insert;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $dbconn->CommitTrans();
          $mensaje = "DEVOLUCION REALIZADA SATISFACTORIAMENTE";
          $titulo = "MENSAJE";
		$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "PANEL ENFERMERIA";
          $this->FrmMSG($url,$titulo,$mensaje,$link);
          return true;
     }

          
/**************************************************************************************
	FUNCIONES DE PARA EL MANEJO DE LOS PRODUCTOS DESPACHADOS A USUARIOS DE LA ESTACION
**************************************************************************************/
     
     
     /*
     * Funcion que cuenta si un paciente presenta o no controles.
     */
     function CountControles($ingreso)
     {
          list($dbconn) = GetDBconn();
          $controles=array();
          $query="SELECT COUNT(*)
                  FROM  hc_controles_paciente
                  WHERE ingreso=".$ingreso."";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado) {
               $this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
     	if($resultado->fields[0]>0)
          {return 1;}else{return 0;}
     }
    
     /*
     *	GetControles
     */
     function GetControles($ingreso,$control_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT COUNT(*)
                    FROM  hc_controles_paciente cp,
                         hc_tipos_controles_paciente tc
                    WHERE cp.ingreso=".$ingreso." AND
                         cp.control_id = tc.control_id
                         AND cp.control_id='$control_id';";

          $resultado=$dbconn->Execute($query);
          if (!$resultado) {
               $this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
          if($resultado->fields[0]>0)
          {return 1;}else{return 0;}
     }
	
	// funcion que obtiene las fechas de las programaciones de los APD          	
     function GetFechasHcApoyos($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT fecha
                    FROM hc_control_apoyosd_pendientes WHERE ingreso='$ingreso'
                    AND usuario_confirma ISNULL
                    AND fecha_registro_confirma ISNULL";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer las fechas de los apoyos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		     return false;
          }

          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
	     return $var;
     }

     function GetConteo_Hc_control_apoyod($ingreso)
     {
          $query = "SELECT hc_control_apoyod($ingreso)";
          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer los controles de apoyos";
               $this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          return $result->fields[0];
     }

     
/*************************************************************************************
	FUNCIONES DE PARA EL EGRESO DE LOS PACIENTES DE LA ESTACION (SALIDA DE PACIENTES)
*************************************************************************************/
     
     /**
     * Funcion que obtiene las evoluciones abiertas de cada paciente.
     */
	function BuscarEvolucion_Pac($ingreso,$sw)
	{
          list($dbconn) = GetDBconn();
          
          if(empty($sw))
          {
               $query = "select COUNT(evolucion_id) from hc_evoluciones
                         where ingreso='$ingreso'
                         and estado='1'";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               return $result->fields[0];
          }
			
          $query = "select usuario_id,evolucion_id from hc_evoluciones
                    where ingreso='$ingreso'
                    and estado='1'";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
							
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
		
          for($r=0;$r<sizeof($vector);$r++)
          {
               $query = "SELECT x.tipo_profesional, x.descripcion,
               			  b.nombre,
                                c.fecha, c.evolucion_id
                         FROM profesionales_usuarios a, profesionales b,hc_evoluciones c,
                         	tipos_profesionales x
                         WHERE a.tipo_tercero_id=b.tipo_id_tercero and
                         a.tercero_id=b.tercero_id and
                         a.usuario_id=".$vector[$r][usuario_id]."
                         AND c.evolucion_id=".$vector[$r][evolucion_id]."
                         AND x.tipo_profesional=b.tipo_profesional";
               $result=$dbconn->Execute($query);
               while (!$result->EOF)
               {
                    $vector2[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
               
               if(!$vector2)
               {
                    $query = "SELECT '5' AS tipo_profesional, 'OTRO' AS descripcion,
                                   b.nombre,
                                   c.fecha, c.evolucion_id
                              FROM system_usuarios b, hc_evoluciones c
                              WHERE c.evolucion_id=".$vector[$r][evolucion_id]."
                              AND c.usuario_id = ".$vector[$r][usuario_id]."
                              AND c.usuario_id = b.usuario_id;";
                    $result=$dbconn->Execute($query);
                    while (!$result->EOF)
                    {
                         $vector2[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                    }
               }
          }		
          return $vector2;
	}
     
     
     /**
     * Funcion que obtiene la informacion de las devoluciones pendientes.
     */
	function CerrarEvolucionesAbiertas()
     {
     	$evolucion = $_REQUEST['evolucion'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente  = $_REQUEST['datosPaciente'];
          $estado = $_REQUEST['estado'];
          $conducta = $_REQUEST['conducta'];     
          
          list($dbconnect) = GetDBconn();
     	$query = "UPDATE hc_evoluciones 
          		SET estado = '0'
                    WHERE evolucion_id = ".$evolucion.";";
          $dbconnect->Execute($query);         
          if ($dbconnect->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $this->FrmPacientePendiente_Egreso($datos_estacion,$datosPaciente,$conducta,$estado);
          return true;
     }
     
          
     /**
     * Funcion que obtiene la informacion de las devoluciones pendientes.
     */
	function GetInformacionDevolucion_BodegaPaciente($ingreso)
     {
          list($dbconnect) = GetDBconn();
     	$query = "SELECT SUM(cantidad_en_devolucion)
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso.";";
          $result = $dbconnect->Execute($query);
          if($result->fields[0] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }
     }
     
     /**
     * Funcion que obtiene la informacion de medicamentos en bodega.
     */
     function GetInformacionMedicamentos_BodegaPaciente($ingreso, $filtro)
     {
          list($dbconnect) = GetDBconn();
		  
     	/*$query = "SELECT SUM(cantidad_en_solicitud), SUM(stock_almacen), SUM(cantidad_pendiente_por_recibir)
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso."
                    AND sw_tipo_producto = '$filtro';";*/
			$query = "	SELECT 	(	select 	count (a.*) 
									from 	hc_solicitudes_medicamentos a 
									where 	a.ingreso = ".$ingreso." 
									and 	a.tipo_solicitud = '".$filtro."' 
									and 	a.sw_estado = '0'
								) , 
								SUM(stock_almacen), 
								(	select 	count (a.*)
									from 	hc_solicitudes_medicamentos a 
									where 	a.ingreso = ".$ingreso." 
									and 	a.tipo_solicitud = '".$filtro."' 
									and 	a.sw_estado = '1'
								)
		
						FROM 	bodega_paciente
						WHERE 	ingreso = ".$ingreso."
						AND 	sw_tipo_producto = '".$filtro."';";		
          $result = $dbconnect->Execute($query);
          if($result->fields[0] > 0 OR $result->fields[1] > 0 OR $result->fields[2] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }
     }
     
     /**
     * Funcion que obtiene la informacion de insumos en bodega.
     */
     function GetInformacionSuministros_BodegaPaciente($ingreso, $filtro)
     {
          list($dbconnect) = GetDBconn();
		  
     	/*$query = "SELECT SUM(cantidad_en_solicitud), SUM(cantidad_pendiente_por_recibir)
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso."
                    AND sw_tipo_producto = '$filtro';";*/
			$query = "	SELECT 	(	select 	count (a.*) 
									from 	hc_solicitudes_medicamentos a 
									where 	a.ingreso = ".$ingreso." 
									and 	a.tipo_solicitud = '".$filtro."' 
									and 	a.sw_estado = '0'
								) , 
								(	select 	count (a.*)
									from 	hc_solicitudes_medicamentos a 
									where 	a.ingreso = ".$ingreso." 
									and 	a.tipo_solicitud = '".$filtro."' 
									and 	a.sw_estado = '1'
								);";			
          $result = $dbconnect->Execute($query);
          
          if($result->fields[0] > 0 OR $result->fields[1] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }
     }
     
     /**
     * Funcion que obtiene la informacion de insumos en bodega.
     */
     function GetInfoCuentasActivas($ingreso)
     {
          list($dbconnect) = GetDBconn();
     	$query = "SELECT COUNT(numerodecuenta)
                    FROM cuentas
                    WHERE ingreso = ".$ingreso."
                    AND estado = '1';";
          $result = $dbconnect->Execute($query);
          
          if($result->fields[0] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }     
     }
     
     /**
     * Funcion en la cual insertamos o modificamos la nota de enfermeria final.
     */
     function Insertar_Nota_Enfermeria()
     {
          list($dbconn) = GetDBconn();
          $datos_estacion=$_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $estado = $_REQUEST['estado'];
          $conducta = $_REQUEST['conducta'];     
          $tipo=$datosPaciente['tipo_id_paciente'];
          $pac=$datosPaciente['paciente_id'];
          $ingreso=$datosPaciente['ingreso'];
          $nombre=$datosPaciente['nombre'];
          $cama= $datosPaciente['cama'];


          $query = "SELECT COUNT(*)
                    FROM  hc_notas_enfermeria_descripcion
                    WHERE ingreso='$ingreso'
                    AND date(fecha_registro)='".date("Y-m-d")."'";
          $result=$dbconn->Execute($query);
          if($result->fields[0]>0)
          {
               $query = "UPDATE hc_notas_enfermeria_descripcion
                                   SET descripcion='".$_REQUEST['obs']."'
                                   WHERE ingreso='$ingreso'
                                   AND date(fecha_registro)='".date("Y-m-d")."'";
               $result=$dbconn->Execute($query);
          }
          else
          {
               $evol=$this->BuscarEvolucion($ingreso);
               if(empty($evol)){$evol='NULL';}
               $query = "INSERT INTO hc_notas_enfermeria_descripcion
                              (descripcion,
                              evolucion_id,
                              usuario_id,
                              fecha_registro,
                              ingreso)VALUES
                              ('".$_REQUEST['obs']."',
                               $evol,
                              ".UserGetUID().",
                              '".date("Y-m-d")."',
                              $ingreso
                              )";
               $result=$dbconn->Execute($query);
          }

          $this->FrmPacientePendiente_Egreso($datos_estacion,$datosPaciente,$conducta,$estado);
          return true;
	}
     
     /**
     * Funcion que obtiene la evolucion de un paciente.
     */
     function BuscarEvolucion($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "select b.evolucion_id from hc_evoluciones as b
                    where b.ingreso='$ingreso'
                    and b.estado='1'
                    and b.fecha_cierre=(select max(fecha_cierre) from hc_evoluciones	where ingreso='$ingreso')";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
	     }
          return $result->fields[0];
     }
     
     //damos salida al paciente [esta es de prueba]
     function DarSalida()
     {
          $ingreso = $_REQUEST['ingreso'];
          $cama = $_REQUEST['cama'];
          $estado = $_REQUEST['estado'];
          $conducta = $_REQUEST['conducta'];
          $dpto_egreso = $_REQUEST['datos_estacion']['departamento'];

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          if($estado == 'ConsultaURG')
          {
               if($conducta[hc_tipo_orden_medica_id] == '06')
               { $estado = '5'; }
               elseif($conducta[hc_tipo_orden_medica_id] == '07')
               { $estado = '6'; }
               elseif($conducta[hc_tipo_orden_medica_id] == '99')
               { $estado = '4'; }
               
               $query = "UPDATE pacientes_urgencias SET sw_estado = '$estado'
                         WHERE ingreso=".$ingreso."";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               $query = "DELETE from pendientes_x_hospitalizar
                         WHERE ingreso=".$ingreso.";";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al eliminar de pendientes_x_hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          else
          {
               $query = "DELETE from pendientes_x_hospitalizar
                         WHERE ingreso=".$ingreso.";";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al eliminar de pendientes_x_hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               $query = "UPDATE movimientos_habitacion
                         SET fecha_egreso = '".date("Y-m-d H:i:s")."'
                         WHERE fecha_egreso ISNULL AND
                         ingreso = $ingreso";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
              
               $query = "UPDATE camas
                         SET estado = '1'
                         WHERE cama = '".$cama."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          if($conducta[hc_tipo_orden_medica_id] == '06' OR $conducta[hc_tipo_orden_medica_id] == '07' OR $conducta[hc_tipo_orden_medica_id] == '99')
          {
          	$query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
               			    WHERE ingreso = ".$conducta[ingreso]."
                                  AND evolucion_id = ".$conducta[evolucion_id].";";
               $result = $dbconn->Execute($query_Conducta);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          $queryIngreso = "UPDATE ingresos
                    	  SET estado = '2'
                    	  WHERE ingreso = ".$ingreso.";";
          $result = $dbconn->Execute($queryIngreso);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $Observaciones = "Salida desde la Estacion de Enfermeria, El paciente se encuentra a Paz y Salvo con la Institución.";
          $querySalida = "INSERT INTO ingresos_salidas   (ingreso,
          									   fecha_registro,
                                                          usuario_id,
                                                          observacion_salida,
                                                          departamento_egreso)
          								VALUES (".$ingreso.",
                                                  	   now(),
                                                          ".UserGetUID().",
                                                          '".$Observaciones."',
                                                          '".$dpto_egreso."');";
          $result = $dbconn->Execute($querySalida);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }          
          
          $dbconn->CommitTrans();
          $mensaje = "SOLICITUD REALIZADA SATISFACTORIAMENTE";
          $titulo = "MENSAJE";
		$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "PANEL ENFERMERIA";
          $this->FrmMSG($url,$titulo,$mensaje,$link);
          return true;
     }
     
     /*
	* Funcion que obtiene los datos si desde otros departamentos se aprobo la salida del paciente
	*/
	function BusquedaVistos_ok_salida($conducta)
     {
          $query = "SELECT A.*, B.* 
          		FROM hc_tiposvistosok_salida AS A LEFT JOIN hc_vistosok_salida_detalle AS B ON (a.visto_id = b.visto_id)
				WHERE B.ingreso = ".$conducta[ingreso]."
				AND B.evolucion_id = ".$conducta[evolucion_id]."
                    ORDER BY A.visto_id ASC;";
		GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          while($data = $result->FetchRow())
          {
          	$vistos[$data['visto_id']] = $data; 
          }
     	return $vistos;
     }
     
     /*
	* Inserta el visto ok por parte de la EE (PACIENTE A PAS Y SALVO) 
	*/
     function Insertar_Vistobueno()
     {
     	$conducta = $_REQUEST['conducta'];
          $query = "INSERT INTO hc_vistosok_salida_detalle (ingreso,
     											evolucion_id,
          										visto_id,
                                                            usuario,
                                                            observacion)
          								VALUES   (".$conducta[ingreso].",
                                                  		".$conducta[evolucion_id].",
                                                            '01',
                                                            ".UserGetUID().",
                                                            'Visto bueno desde EE');";
          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $this->FrmPacientePendiente_Egreso($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$conducta,$_REQUEST['estado']);
     	return true;
     }
	
    /*
	* Eliminar el visto ok por parte de la EE (PACIENTE A PAZ Y SALVO) 
	*/
     function Eliminar_Vistobueno()
     {
     	  $conducta = $_REQUEST['conducta'];
        $query = "  DELETE FROM  hc_vistosok_salida_detalle
                         WHERE            ingreso=".$conducta[ingreso]."
                         AND                 evolucion_id=".$conducta[evolucion_id]." ;";
          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar eliminar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $this->FrmPacientePendiente_Egreso($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$conducta,$_REQUEST['estado']);
     	return true;
     }
	/**
     * Funcion que obtiene si el usuario tiene permiso para eliminar el visto bueno.
     */
     function BuscarUsuarioParaElimVistoOk($estacion_id)
     {
          list($dbconn) = GetDBconn();
          $query = " SELECT  count(*) 
                           FROM    estaciones_enfermeria_usuarios
                           WHERE  estacion_id='".$estacion_id."'
                           AND      usuario_id=".UserGetUID()."
                           AND      sw_eliminar_vistok 	='1' ";
                          // print_r($query);
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
	     }
          return $result->fields[0];
     }
     
     /*
	* Cambiamos el formato timestamp a un formato de fecha legible para el usuario
	*/
	function FormateoFechaLocal($fecha)
	{
          if(!empty($fecha))
          {
               $f=explode(".",$fecha);
               $fecha_arreglo=explode(" ",$f[0]);
               $fecha_real=explode("-",$fecha_arreglo[0]);
               return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));
          }
          else
          {
               return "-----";
          }
		return true;
	}
     
     
/*************************************************************************************
	FUNCIONES DE PARA EL EGRESO DE LOS PACIENTES DE LA ESTACION (SALIDA DE PACIENTES)
*************************************************************************************/
     
     /**
     *	Profesionales_Atencion()
     *	Obtiene la informacion pertinente a las atenciones recibidas 
     *	anteriormente por el paciente.
     *
     *	@author: Tizziano Perea
     *	@param: Ingreso paciente
     *	@return: Vector_Informacion
     */
     
     function Profesionales_Atencion($ingreso)
     {
     	list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;          
          $query = "SELECT A.evolucion_id, A.fecha,
          			  A.usuario_id, A.estado, A.ingreso,
          			  B.nombre
          		FROM hc_evoluciones AS A, 
                    	profesionales AS B
                    WHERE A.ingreso = '".$ingreso."'
                    --AND A.usuario_id <> ".UserGetUID()."
                    AND A.usuario_id = B.usuario_id
                    AND A.estado IN ('0','1')
                    AND B.tipo_profesional IN ('1','2')
                    ORDER BY A.evolucion_id DESC
                    LIMIT 1 OFFSET 0;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          
          if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          	return false;
		}
          $infoAtenciones = $resultado->FetchRow();
     	return $infoAtenciones;
     }
     
     /**
     *	LlamarImpresionSolicitudes()
     *	Obtiene la informacion de las solicitudes de impresion pendientes para el paciente.
     *
     *	@author: Tizziano Perea
     */
     function LlamarImpresionSolicitudes()
	{
          unset($_SESSION['ADMISIONES']['PACIENTE']);
          unset($_SESSION['ADMISIONES']['DATOS']);

		$_SESSION['EE_ESTACION'] = true;
          
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
          $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']=$_REQUEST['evolucion'];
          $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['contenedor']='app';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['modulo']='EE_PanelEnfermeria';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['tipo']='user';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['metodo']='FormaImpresionSolicitudes';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['argumentos']=array('datos_estacion'=>$_REQUEST['datos_estacion'],'ruta'=>$_REQUEST['ruta']);

          $this->ReturnMetodoExterno('app','Admisiones','user','ImpresionSolicitudesExt');
          return true;
	}
     
     /**
     *	GetColorGround()
     *	Obtiene la informacion referente al color del nivel del triage.
     *
     *	@author: Tizziano Perea
     */
     function GetColorGround()
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;          
          $query = "SELECT nivel_triage_id, color, bgcolor
          		FROM niveles_triages;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          	return false;
		}
          while($data = $resultado->FetchRow())
          {
          	$_NivelesTriage[$data['nivel_triage_id']] = $data;
          } 
          return $_NivelesTriage;
     }


      function GetEstudiosImagenologia($ingreso)
      {
        list($dbconn) = GetDBconn();
        $query="SELECT B.*, A.estudio_id, A.admision
                FROM estudios_pacs A INNER JOIN pacs B ON (B.id_pacs = A.id_pacs)
                WHERE A.ingreso = ".$ingreso;
					  
        $result = $dbconn->Execute($query);
  
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar SQL";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
            return false;
        }
        else
        {
          while(!$result->EOF)
          {
            $vars[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
          }
        }
        $dbconn->CommitTrans();
        return $vars;
      }

      function GetEstudiosImagenologiaPaciente($paciente)
      {
          $vars = Array();
          list($dbconn) = GetDBconn();
          $query="SELECT B.*
                  FROM estudios_pacs A INNER JOIN pacs B ON (B.id_pacs = A.id_pacs)
                  WHERE paciente_id = '".$paciente."'";
					  
          $result = $dbconn->Execute($query);

          if($dbconn->ErrorNo() != 0)
          {
              $this->error = "Error al Cargar SQL";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
              return false;
          }
          else
          {
              while(!$result->EOF)
              {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
              }
          }
          $dbconn->CommitTrans();
          return $vars;
      }

}//end of class

?>
