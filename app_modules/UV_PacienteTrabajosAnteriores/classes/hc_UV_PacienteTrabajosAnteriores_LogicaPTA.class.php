<?php
/** 
    * $Id: ExamenFisico_signos_HTML.class.php,v 1.2 2007/10/12 14:40:56 jgomez Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.2 $ 
    * 
    * @autor J gomez
    */

class LogicaPTA 
{

 /**
* Codigo de error
* @var string
* @access private
*/
var $error;

/**
* Esta funci� Inicializa las variable de la clase
* @access public
* @return boolean Para identificar que se realizo.
*/
	function LogicaPTA($objeto=null)
	{
     	$this->obj=$objeto;
          return true;
	}




/**
* Esta funci� retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'10/25/2006',
		'autor'=>'JAIME ANDRES GOMEZ',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

    /**
    * Funcion que se utiliza para CONSULTAR LA INFORMACION de la ultima eps, arp y fondo de pensiones que tuvo el ususario.
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function SacarUltimoRegistroEPS($tipo_id_paciente,$paciente_id)
    {
        $sql="select *
              from
              UV_paciente_EPS_Anterior
              where registro_eps_id=(
                                        select
                                        max(registro_eps_id)
                                        from UV_paciente_EPS_Anterior
                                    )
                AND tipo_id_paciente = '".$tipo_id_paciente."'
                AND paciente_id  = '".$paciente_id."';
              ";


        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
        $eps_ant=Array();
        while(!$resultado->EOF)
        {
        $eps_ant[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
        }
        
        $resultado->Close();
        return $eps_ant;


    }
    
    /**
    * Funcion que se utiliza para CONSULTAR LA INFORMACION del historial  eps, arp y fondo de pensiones que tuvo el ususario.
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    Function ConsultarEPS_Anterior($tipo_id_paciente,$paciente_id)
    {
        $sql="
                SELECT *
                FROM
                UV_paciente_EPS_Anterior 
                WHERE
                tipo_id_paciente = '".$tipo_id_paciente."'
                AND paciente_id  = '".$paciente_id."'
                
        ";

        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
        $eps_ant=Array();
        while(!$resultado->EOF)
        {
        $eps_ant[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
        }
        
        $resultado->Close();
        return $eps_ant;


    }

    /**
    * Funcion que se utiliza para CONSULTAR LA INFORMACION del historial de trabajos que tuvo el ususario anteriormente
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function ConsultarTrabajosAnteriores($tipo_id_paciente,$paciente_id)
    {
        $sql="
                SELECT
                a.trabajo_id,
                a.empleador,
                a.tipo_pais_id,
                b.pais,
                a.tipo_dpto_id,
                c.departamento,
                a.tipo_mpio_id,
                d.municipio,
                a.cargo,
                a.fecha_ini,
                a.fecha_fin,
                a.dias_por_semana,
                a.horas_dia,
                a.intensidad,
                a.empresa_elemetos_protectores,
                a.uso_elemetos_protectores
                
                
                FROM
                UV_Trabajos_Anteriores AS a,
                tipo_pais AS b,
                tipo_dptos AS c,
                tipo_mpios AS d
                
                WHERE
                a.tipo_id_paciente = '".$tipo_id_paciente."'
                AND a.paciente_id  = '".$paciente_id."'
                AND a.tipo_pais_id = b.tipo_pais_id
                AND a.tipo_pais_id = c.tipo_pais_id
                AND a.tipo_dpto_id = c.tipo_dpto_id
                AND a.tipo_pais_id = d.tipo_pais_id
                AND a.tipo_dpto_id = d.tipo_dpto_id
                AND a.tipo_mpio_id = d.tipo_mpio_id
                ORDER BY a.trabajo_id

        ";




        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
        $datos=Array();
        while(!$resultado->EOF)
        {
            $datos[$resultado->fields[0]] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        $sql="
              SELECT
              a.agente_riesgo_id,
              a.tipo_riesgo_id,
              a.trabajo_id,
              b.descripcion as agente_de_riesgo_nom,
              c.color,
              c.descripcion as tipo_de_riesgo_nom
              FROM
              UV_paciente_trabajos_anteriores_riesgos AS a,
              uv_agentes_de_riesgos AS b,
              uv_tipos_de_riesgos AS c
              WHERE
              a.tipo_id_paciente = '".$tipo_id_paciente."'
              AND a.paciente_id  = '".$paciente_id."'
              AND a.agente_riesgo_id = b.agente_riesgo_id
              AND a.tipo_riesgo_id = c.tipo_riesgo_id
              ORDER BY a.trabajo_id";





        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

        $datos1=Array();
        while(!$resultado->EOF)
        {
            $datos1['RIESGOS'][$resultado->fields[2]][$resultado->fields[5]][] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
         $datox=Array();
         $datox[0]=$datos;
         $datox[1]=$datos1['RIESGOS'];
        return $datox;


    }

    /**
    * Funcion que se utiliza para GUARDAR LA INFORMACION del historial de las eps, arp y fondo de pensiones que tuvo el ususario anteriormente
    * @param array $vector
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @param string $usuario
    * @return string $salida
    **/
    function Guardar_EPS_Anterior($vector,$tipo_id_paciente,$paciente_id,$usuario)
    {
      $sql="INSERT INTO UV_paciente_EPS_Anterior
              (
                  tipo_id_paciente,
                  paciente_id,
                  nombre_arp_anterior,
                  nombre_eps_anterior,
                  nombre_pensiones_anterior,
                  fecha_ingreso,
                  fecha_retiro,
                  usuario_registro,
                  fecha_registro
              )
               values(
              '".$tipo_id_paciente."',
              '".$paciente_id."',
              '".$vector["arp"]."',
              '".$vector["eps"]."',
              '".$vector["pension"]."',
              '".$this->DividirFecha($vector["fecha3"],"-")."',
              '".$this->DividirFecha($vector["fecha4"],"-")."',
              ".$usuario.",
              NOW())";

       if(!$rst = $this->ConexionBaseDatos($sql))
      {  $cad="ERROR INSERCION EPS ANTERIOR".$sql;
      //return $cad;
      return $cad;
      }

      return true;
    }
    /**
    * Funcion que se utiliza para GUARDAR LA INFORMACION de trabajos anteriores y los riesgos a los que estuvo expuesto por ese trabajo
    * @param array $vector
    * @param string $tipo_id
    * @param string $id_paciente
    * @param string $usuario
    * @return string $salida
    **/
    function Guardar_Trabajos_Anteriores($vector,$tipo_id,$id_paciente,$usuario)
    {
       
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();

        $sql="SELECT  nextval('uv_trabajos_anteriores_trabajo_id_seq'::regclass) AS trabajo_id";
        $result = $dbconn->Execute($sql);
        
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "ERROR AL SELECCIONAE EL ID DE TRABAJO_ID [" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
         
        list($trabajo_id) = $result->FetchRow();
       
        $result->Close();

          $sql="INSERT INTO
               UV_Trabajos_Anteriores
                (
                     trabajo_id,
                     tipo_id_paciente,
                     paciente_id,
                     empleador,
                     tipo_pais_id,
                     tipo_dpto_id,
                     tipo_mpio_id,
                     cargo,
                     fecha_ini,
                     fecha_fin,
                     dias_por_semana,
                     horas_dia,
                     intensidad,
                     empresa_elemetos_protectores,
                     uso_elemetos_protectores,
                     usuario_registro,
                     fecha_registro
                )
                 values( ".$trabajo_id.",
                         '".$tipo_id."',
                         '".$id_paciente."',
                         '".$vector["empleador"]."',
                         'CO',
                         '".$vector["departamentos"]."',
                         '".$vector["ciudades"]."',
                         '".$vector["cargo"]."',
                         '".$this->DividirFecha($vector["fecha1"],"-")."',
                         '".$this->DividirFecha($vector["fecha2"],"-")."',
                         '".$vector["dias_sem"]."',
                         '".$vector["horas_dia"]."',
                         '".$vector["intensidad"]."',
                         '".$vector["emp_prot"]."',
                         '".$vector["usu_prot"]."',
                         ".$usuario.",
                         NOW())";
           $result = $dbconn->Execute($sql);

             if($dbconn->ErrorNo() != 0)
             {
                 $dbconn->RollbackTrans();
                 $this->error = "ERROR INSERCION TRABAJOS ANTERIORES [" . get_class($this) . "][" . __LINE__ . "]";
                 $this->mensajeDeError = $dbconn->ErrorMsg();
                 return false;
             }

            foreach($vector['NORKER'] as $key=>$valor)
            {
       
                list($tipo_riesgo_id,$agente_riesgo_id) = explode("@",$key);
                  
                  
                
                    $sql="INSERT INTO
                                UV_paciente_trabajos_anteriores_riesgos
                                (
                                        tipo_id_paciente,
                                        paciente_id,
                                        agente_riesgo_id,
                                        tipo_riesgo_id,
                                        trabajo_id,
                                        usuario_registro,
                                        fecha_registro
                                )
                                    values('".$tipo_id."',
                                    '".$id_paciente."',
                                    '".$agente_riesgo_id."',
                                    '".$tipo_riesgo_id."',
                                    '".$trabajo_id."',
                                    ".$usuario.",
                                    NOW())";

                     $result = $dbconn->Execute($sql);

                    if($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollbackTrans();
                        $this->error = "ERROR INSERCION DE RIESGOS  [" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }



            }

        $dbconn->CommitTrans();
 
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        return true;

    }
    
    /**
    * Funcion util para la consulta de ciudades segun el depatamento solo para colombia
    * @param $string $dpto
    * @return array $ciudades
    **/
    
    function ConsultarCiudades($departamento)
    {
         $sql="select * 
       from tipo_mpios
       where tipo_pais_id='CO'    
       and tipo_dpto_id='".$departamento."'
       order by municipio"; 
       
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $ciudades=Array();
      while(!$resultado->EOF)
      {
        $ciudades[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $ciudades;


    }
    
    /**
    * Funcion que se utiliza para GUARDAR LA INFORMACION de enfermedades o accidentes laborales
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @param string $enfermedad
    * @param string $accidentes
    * @param string $enfermedades_sw
    * @param string $accidentes_sw
    * @return string $salida
    **/
     function GuardarInfoEnfermedades($tipo_id_paciente,$paciente_id,$enfermedad,$accidentes,$enfermedades_sw,$accidentes_sw,$usuario)
    {
        $sql="INSERT INTO
              UV_paciente_enfermedades_Y_accidentes_profesionales
               (
                    tipo_id_paciente,
                    paciente_id,
                    enfermedad_profesional,
                    descripcion_enfermedad,
                    accidente_laboral,
                    descripcion_accidente,
                    usuario_registro,
                    fecha_registro
               )
                values('".$tipo_id_paciente."',
                        '".$paciente_id."',
                        '".$enfermedades_sw."',
                        '".$enfermedad."',
                        '".$accidentes_sw."',
                        '".$accidentes."',
                        ".$usuario.",
                        NOW())";
                if(!$rst = $this->ConexionBaseDatos($sql))
                {  $cad="falla en SQL insercion".$sql;
                //return $cad;
                return $cad;
                }

        return true;
    }

    
    /**
    * Funcion util para consultar las enfermedades y accidentes profesionales que ha tenido el paciente
    * @param string $tipo_id_paciente con el listado de agentes de riesgo.
    * @param string $paciente_id con el listado de agentes de riesgo.
    * @return $salida con el listado de agentes de riesgo.
    **/
    function ObtenerDatosEnfermedades($tipo_id_paciente,$paciente_id)
    {
         $sql="SELECT  *
                FROM
                    UV_paciente_enfermedades_Y_accidentes_profesionales
                WHERE
                tipo_id_paciente='".$tipo_id_paciente."'
                AND paciente_id='".$paciente_id."'
                ORDER BY fecha_registro DESC";


        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        
        $rst->Close();

        return $datos;
    }
    
    /**
    * Funcion util para consultar los tipos de riesgo y los agentes de riesgo
    * @return $salida con el listado de agentes de riesgo.
    **/
    function ObtenerTipos_Agentes_de_riesgo()
    {
         $sql="SELECT
                a.tipo_riesgo_id,
                b.agente_riesgo_id,
                a.descripcion as tipo_de_riesgo_nom,
                b.descripcion as agente_de_riesgo_nom
                FROM
                UV_tipos_de_riesgos AS a
                INNER JOIN
                UV_agentes_de_riesgos as b
                ON(a.tipo_riesgo_id=b.tipo_riesgo_id)
                GROUP BY 1,2,3,4 ORDER BY 1,2,3,4";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
        while(!$rst->EOF)
        {
            $datos[$rst->fields[2]][] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $datos['CUANTOS']=$rst->RecordCount();
        $rst->Close();

        return $datos;

    }
    /**
    * Funcion que sirve para consultar las ciudades de colombia
    * @param string $tipo_dpto_id
    * @return string $forma con los datos
    **/
    function ObtenerCiudades($tipo_dpto_id)
    {
        $sql="SELECT a.*
                FROM
                    tipo_mpios AS a,
                    tipo_dptos as b
                WHERE
                    a.tipo_pais_id  ='CO'
                    b.tipo_dpto_id='".$tipo_dpto_id."'";
                    
        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;


    }
    
    /**
    * Funcion que sirve para consultar los departamentos de colombia
    * @param string $tipo_pais_id
    * @return string $forma con los datos
    **/
    function ObtenerDepartamentos()
    {

        $sql="SELECT *
                FROM
                    tipo_dptos AS a
                WHERE
                    a.tipo_pais_id  ='CO'";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    * @param string $sql
    * @return rst
    ***/
        function ConexionBaseDatos($sql)
        {
         
             list($dbconn)=GetDBConn();
             //$dbconn->debug=true;
             $rst = $dbconn->Execute($sql);
                 
             if ($dbconn->ErrorNo() != 0)
             {
                 $this->Error['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
                  "<b class=\"label\">".$Error['MensajeError']."</b>";
                 return false;
             }
            
             return $rst;
        }
    /**
    * Funcion donde se parte la fecha y se devuelve la fecha en formato yyyy-MM-DD
    *
    * @param strinmg $fecha Fecha pasada por parametro 
    *
    * @return string
    */
    function DividirFecha($fecha,$marca)
    {
      $f = explode($marca,$fecha);
      if(sizeof($f) == 3 )
        $fecha = $f[2]."-".$f[1]."-".$f[0];
      
      return $fecha;
    }  
  }
?>