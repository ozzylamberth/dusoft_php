<?php

/**
 * $Id: app_AtencionInterconsulta_user.php,v 1.4 2006/02/24 15:16:18 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AtencionInterconsulta_user extends classModulo
{

	function app_AtencionInterconsulta_user()
	{
		return true;
	}

   
   /**
    * Retorna los datos de una estacion de enfermeria.
    *
    * @return array
    * @access private
    */
    function GetDatosEstacion($estacion_id)
    { 
        if(!$_SESSION['EE_PanelInterconsultas']['DATOS_ESTACION'][$estacion_id])
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

            $_SESSION['EE_PanelInterconsultas']['DATOS_ESTACION'][$estacion_id] = $resultado->FetchRow();
            $resultado->Close();
        }
        return $_SESSION['EE_PanelInterconsultas']['DATOS_ESTACION'][$estacion_id];
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

        if(empty($_SESSION['EE_PanelInterconsultas']['ESTACIONES_USUARIO'][UserGetUID()]))
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
                                        AND b.estacion_componente_id = '65'
                                    )
                                    UNION
                                    (
                                        SELECT a.estacion_id, a.estacion_componente_id

                                        FROM estaciones_enfermeria_usuarios_componentes as a

                                        WHERE
                                        a.usuario_id = ".UserGetUID()."
                                        AND sw_permiso = '1'
                                        AND a.estacion_componente_id = '65'
                                    )
                                ) as a LEFT JOIN
                                (
                                    SELECT a.estacion_id, a.estacion_componente_id
                                    FROM estaciones_enfermeria_usuarios_componentes as a
                                    WHERE a.usuario_id = ".UserGetUID()."  AND sw_permiso = '0'
                                    AND a.estacion_componente_id = '65'
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
                $_SESSION['EE_PanelInterconsultas']['ESTACIONES_USUARIO'][UserGetUID()][$fila['estacion_id']]['COMPONENTES'][$fila['estacion_componente_id']] = $fila['estacion_componente_id'];
                if(!$_SESSION['EE_PanelInterconsultas']['DATOS_ESTACION'][$fila['estacion_id']])
                {
                    unset($fila['estacion_componente_id']);
                    $_SESSION['EE_PanelInterconsultas']['DATOS_ESTACION'][$fila['estacion_id']] = $fila;
                }
            }
            
            $resultado->Close();
            $_SESSION['EE_PanelInterconsultas']['TIPO_PROFESIONAL'][UserGetUID()]=$tipo_profesional;
        }

        return $_SESSION['EE_PanelInterconsultas']['ESTACIONES_USUARIO'][UserGetUID()];
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
            if(!empty($_SESSION['EE_PanelInterconsultas']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]['COMPONENTES'][$componente]))
            {
                return true;
            }
            else
            {
                return null;
            }
        }

        if(!empty($_SESSION['EE_PanelInterconsultas']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]))
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
     	$estacion_id = $_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()];
     	
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
        $estacion_id = $_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()];
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
            $_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()] = $_REQUEST['estacion_id'];
            unset($_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
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
        unset($_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()]);
        unset($_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
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
               	$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] = $_REQUEST['TipoConsultaUrgencias'];
               }
               else
               {
               	$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] = 1;
               }
        }
        
        
        
        if($this->GetUserPermisos($_REQUEST['estacion_id']))
        {
            $_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()] = $_REQUEST['estacion_id'];
            if($_REQUEST['pacientes_en_consulta'])
            {
            	$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] = $_REQUEST['estacion_id'];
            }
            else
            {
            	unset($_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
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
        $estacion_id = $_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()];
        
        if(empty($estacion_id)) return NULL;
        if($_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()] == $estacion_id)
        {
        	return $_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()];
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
        unset($_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA_CONSULTA'][UserGetUID()]);
        return true;
    }   
    
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
                      WHERE estacion_id = '".$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()]."'
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
                      WHERE estacion_id = '".$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()]."'
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
          		  FROM pacientes_urgencias
                      WHERE estacion_id = '".$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                      AND (sw_estado = '1' OR sw_estado = '7');";
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
                       WHERE b.estacion_id = '".$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()]."'
                       AND a.ingreso = b.ingreso
                       AND a.sw_estado = '1'
                       AND a.hc_tipo_orden_medica_id IN ('06','07','99')
                       AND (b.sw_estado = '1' OR b.sw_estado = '7')) as salida_URG,
                      (SELECT count(*)
          		   FROM hc_ordenes_medicas a, movimientos_habitacion b
                       WHERE b.estacion_id = '".$_SESSION['EE_PanelInterconsultas']['ESTACION_SELECCIONADA'][UserGetUID()]."'
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
        
	
     function BuscarPacientesEstacion()
	{
		//GLOBAL $ADODB_FETCH_MODE;
		//$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		list($dbconn) = GetDBconn();
		$sql="SELECT c.paciente_id, c.tipo_id_paciente, c.primer_nombre || ' ' ||
					c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre,
					e.color, d.hora_llegada, e.tiempo_atencion, b.ingreso, f.evolucion_id,
					to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha, f.usuario_id, h.nombre,
					a.estacion_id, d.nivel_triage_id, d.plan_id, d.triage_id, d.punto_triage_id,
					d.punto_admision_id, d.sw_no_atender, i.numerodecuenta

					FROM pacientes_urgencias as a join ingresos as b
					on (a.ingreso=b.ingreso and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."')
					join pacientes as c on (b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente
					and b.estado=1)
					left join triages as d on (a.triage_id=d.triage_id)
					left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id
					and e.nivel_triage_id!=0) left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado=1)
					left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id)
					left join profesionales as h on (g.tercero_id=h.tercero_id
					and g.tipo_tercero_id=h.tipo_id_tercero)
					left join cuentas as i on(a.ingreso=i.ingreso and i.estado=1)
					order by e.indice_de_orden, d.hora_llegada;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$spy=0;
			while (!$result->EOF)
			{
				if(!empty($result->fields[4]))
				{
					$a=explode("-",$result->fields[4]);
					$b=explode(" ",$a[2]);
					$c=explode(":",$b[1]);
					if(date("Y-m-d H:i:s",mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]))<date("Y-m-d H:i:s",mktime(date("H"), (date("i")-$result->fields[5]), 0, date("m"), date("d"), date("Y"))))
					{
						$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=1;
						if($result->fields[3]=='AZUL')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelazulclaro';
						}
						elseif($result->fields[3]=='ROJO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelrojoclaro';
						}
						elseif($result->fields[3]=='VERDE')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelverdeclaro';
						}
						elseif($result->fields[3]=='AMARILLO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelamarilloclaro';
						}
						else
						{
							if($spy==0)
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
								$spy=1;
							}
							else
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
								$spy=0;
							}
						}
					}
					else
					{
						$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=2;
						if($result->fields[3]=='AZUL')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelazulclaro';
						}
						elseif($result->fields[3]=='ROJO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelrojoclaro';
						}
						elseif($result->fields[3]=='VERDE')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelverdeclaro';
						}
						elseif($result->fields[3]=='AMARILLO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelamarilloclaro';
						}
						else
						{
							if($spy==0)
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
								$spy=1;
							}
							else
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
								$spy=0;
							}
						}
					}
					$total=(mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"))-mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]));
					$total=floor($total/60);
					$minutos=($total%60);
					$total=floor($total/60);
					$horas=($total%24);
					$total=floor($total/24);
					$mostrar="";
					if(!empty($total))
					{
						$mostrar=$total.' dias, ';
					}
					$mostrar.=$horas.':'.$minutos;
					$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][1]=$mostrar;
				}
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][0]=1;
				if($spy==0 and empty($prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]))
				{
					$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
					$spy=1;
				}
				else
				{
					if(empty($prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]))
					{
						$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
						$spy=0;
					}
				}
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][3]=$result->fields[6];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[7];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[8];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[9];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[10];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[11];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[12];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[13];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[14];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[15];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[16];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[17];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[18];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $prueba;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
			return false;
		}
	}

	function ClasificarTriage()
	{
		$_SESSION['Atencion']['estacion_id']=$_REQUEST['estacion_id'];
		$_SESSION['Atencion']['ingreso']=$_REQUEST['ingreso'];
		$_SESSION['Atencion']['modulo']=$_REQUEST['moduloh'];
		$_SESSION['TRIAGE']['ATENCION']['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
		$_SESSION['TRIAGE']['ATENCION']['paciente_id']=$_REQUEST['paciente_id'];
		$_SESSION['TRIAGE']['ATENCION']['plan_id']=$_REQUEST['plan_id'];
		$_SESSION['TRIAGE']['ATENCION']['triage_id']=$_REQUEST['triage_id'];
		$_SESSION['TRIAGE']['ATENCION']['punto_triage_id']=$_REQUEST['punto_triage_id'];
		$_SESSION['TRIAGE']['ATENCION']['punto_admision_id']=$_REQUEST['punto_admision_id'];
		$_SESSION['TRIAGE']['ATENCION']['sw_no_atender']=$_REQUEST['sw_no_atender'];
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor']='app';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo']='AtencionInterconsulta';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo']='user';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo']='RetornoTriage';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos']=array();
		$this->ReturnMetodoExterno('app','Triage','user','LlamarClasificacionMedico');
		return true;
	}

	function RetornoTriage()
	{
		unset($_SESSION['TRIAGE']['ATENCION']);
		if(empty($_SESSION['RETORNO']['TRIAGE']['ATENCION']) or empty($_SESSION['Atencion']['ingreso']))
		{
			unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
			$this->ListadoPaciente();
		}
		else
		{
			unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
			$this->ContinuarHistoria();
		}
		return true;
	}


	function BuscarPacienteHosptalizados($estacion_id)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT MH.cama, B.pieza, C.ingreso, D.paciente_id, D.tipo_id_paciente,
					E.primer_nombre || ' ' || E.segundo_nombre || ' ' || E.primer_apellido || ' ' ||
					E.segundo_apellido as nombretotal, G.evolucion_id, G.usuario_id,
					to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha, I.nombre,
					MH.numerodecuenta
							
                    FROM movimientos_habitacion AS MH,
					camas B, cuentas C, ingresos D 
                         LEFT JOIN hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado=1) 
                         LEFT JOIN profesionales_usuarios as H on(G.usuario_id=H.usuario_id)
					LEFT JOIN profesionales as I on(H.tercero_id=I.tercero_id
							and H.tipo_tercero_id=I.tipo_id_tercero), 
                    	pacientes E, departamentos F
							
                    WHERE MH.fecha_egreso IS NULL
                    AND MH.estacion_id = '".$estacion_id."'
				AND MH.cama = B.cama
                    AND MH.ingreso = D.ingreso
                    AND C.ingreso = D.ingreso 
                    AND C.numerodecuenta = MH.numerodecuenta
                    AND C.estado = '1'
				AND D.paciente_id = E.paciente_id
				AND D.tipo_id_paciente = E.tipo_id_paciente
				AND F.departamento = MH.departamento
				ORDER BY MH.cama, B.pieza,G.evolucion_id;";
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
			$spy=0;
			while (!$result->EOF)
			{
				$prueba[$result->fields[3]][$result->fields[4]][$result->fields[5]][]=$result->GetRowAssoc(false);
				$hospitaesta[0][$i]=$result->fields[0];
				$hospitaesta[1][$i]=$result->fields[1];
				$hospitaesta[2][$i]=$result->fields[2];
				$hospitaesta[3][$i]=$result->fields[3];
				$hospitaesta[4][$i]=$result->fields[4];
				$hospitaesta[5][$i]=$result->fields[5];
				$i++;
				$result->MoveNext();
			}
		}
		$hospitaesta1[]=$hospitaesta;
		$hospitaesta1[]=$prueba;
		if($i<>0)
		{
			return $hospitaesta1;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
			return false;
		}
	}

     function BuscarPacienteConsultaURG($estacion_id)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT D.ingreso, D.paciente_id, D.tipo_id_paciente, 
                              E.primer_nombre || ' ' || E.segundo_nombre || ' ' || E.primer_apellido || ' ' || E.segundo_apellido as nombretotal, 
                              G.evolucion_id, G.usuario_id, to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha, 
                              I.nombre, 
                              C.numerodecuenta, 
                              D.fecha_ingreso 
                              
                         FROM pacientes_urgencias AS PU, 
                              ingresos D 
                              LEFT JOIN hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado=1) 
                              LEFT JOIN profesionales_usuarios as H on(G.usuario_id=H.usuario_id) 
                              LEFT JOIN profesionales as I on(H.tercero_id=I.tercero_id and H.tipo_tercero_id=I.tipo_id_tercero),
                              cuentas C, 
                              pacientes E
                              
                         WHERE PU.sw_estado IN ('1','7') 
                         AND PU.estacion_id = '".$estacion_id."' 
                         AND PU.ingreso = D.ingreso 
                         AND D.paciente_id = E.paciente_id 
                         AND D.tipo_id_paciente = E.tipo_id_paciente
                         AND C.ingreso = PU.ingreso 
                         AND C.estado = '1' 

					ORDER BY D.fecha_ingreso DESC;";
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
			$spy=0;
			while (!$result->EOF)
			{
				$prueba[$result->fields[1]][$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc(false);
				$hospitaesta[2][$i]=$result->fields[0];
				$hospitaesta[3][$i]=$result->fields[1];
				$hospitaesta[4][$i]=$result->fields[2];
				$hospitaesta[5][$i]=$result->fields[3];
				$hospitaesta[6][$i]=$result->fields[4];
				$hospitaesta[7][$i]=$result->fields[5];
				$i++;
				$result->MoveNext();
			}
		}
		$hospitaesta1[]=$hospitaesta;
		$hospitaesta1[]=$prueba;
		if($i<>0)
		{
			return $hospitaesta1;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
			return false;
		}
	}

     function PacientesClasificacionTriage()
	{
		list($dbconn) = GetDBconn();
		$sql="select b.tipo_id_paciente, b.paciente_id, b.plan_id, b.triage_id,
					b.punto_triage_id, b.punto_admision_id, b.sw_no_atender, c.primer_nombre || ' ' ||
					c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre
					from triage_no_atencion as a join triages as b on(a.triage_id=b.triage_id
					and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."'
					and b.nivel_triage_id='0')
					join pacientes as c on (b.tipo_id_paciente=c.tipo_id_paciente
					and b.paciente_id=c.paciente_id);";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while(!$result->EOF)
			{
				$pacientestriage[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc(false);
				$result->MoveNext();
				$i=1;
			}
			if($i==1)
			{
				return $pacientestriage;
			}
			else
			{
				return false;
			}
		}
	}

	
     function TipoModulo()
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$sql="select hc_modulo from system_hc_modulos where rips_tipo_id=11";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}

	
     function ReconocerProfesional()
	{
		list($dbconn) = GetDBconn();
		$a=UserGetUID();
		if(!empty($a))
		{
			$sql="SELECT b.tipo_profesional
						FROM profesionales_usuarios as a,
						profesionales as b
						WHERE a.usuario_id=".$a."
						and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id;";
		}
		else
		{
			return false;
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				return $result->fields[0];
			}
			else
			{
				return false;
			}
		}
	}


	function RevisarInterConsultas($ingreso)
	{
          list($dbconn) = GetDBconn();
          $a=UserGetUID();
          $sql="SELECT b.especialidad,a.evolucion_id,v.descripcion,a.plan_id,a.os_tipo_solicitud_id,a.hc_os_solicitud_id,
                              em.hc_modulo
                    FROM hc_os_solicitudes a,hc_os_solicitudes_interconsultas b,planes c,
                         especialidades v 
                         left join especialidades_modulos em on (v.especialidad = em.especialidad)
                    ,hc_evoluciones m
                    ,profesionales_especialidades z
                    ,profesionales_usuarios p
                    
                    WHERE a.evolucion_id=m.evolucion_id
                    AND a.plan_id=c.plan_id
                    AND v.especialidad=b.especialidad
                    AND a.hc_os_solicitud_id=b.hc_os_solicitud_id
                    AND m.ingreso='$ingreso'
                    AND m.estado='0'
                    AND b.especialidad in(z.especialidad)
                    AND z.tercero_id=p.tercero_id
                    AND z.tipo_id_tercero=p.tipo_tercero_id
                    AND p.usuario_id=$a";

          $result = $dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer las Interconsultas";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $i=0;
          while (!$result->EOF)
          {
                         $var[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
          }
          return $var;
	}


}
?>
