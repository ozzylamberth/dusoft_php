<?php
/** 
    * $Id: hc_UV_DatosSaludOcupacional_LogicaSO.class.php,v 1.1 2009/06/09 19:13:58 hugo Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.1 $ 
    * 
    * @autor J gomez
    */

class LogicaSO 
{

 /**
* Codigo de error
* @var string
* @access private
*/
var $error;

/**
* Esta funci� Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function LogicaSO($objeto=null)
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
    * Funcion que sirve para consultar algunos datos de la interfaz uv_funcionarios
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $forma con los datos
    **/
    function ObtenerDatosFuncionario($tipo_id_paciente,$paciente_id)
    {

       $sql="SELECT
            a.fecha_ingreso_laboral,
            a.sw_tiempo_completo,
            a.jornada,
            a.tipo_pais_id,
            a.tipo_dpto_id,
            a.tipo_mpio_id,
            b.pais,
            c.departamento,
            d.municipio
            FROM
            interfaz_uv.funcionarios_univalle AS a,
            tipo_pais AS b,
            tipo_dptos AS c,
            tipo_mpios AS d

            WHERE

            a.funcionario_tipo_id='".$tipo_id_paciente."'
            AND a.funcionario_id='".$paciente_id."'
            AND a.tipo_pais_id =b.tipo_pais_id  
            AND a.tipo_pais_id =c.tipo_pais_id
            AND a.tipo_dpto_id =c.tipo_dpto_id
            AND a.tipo_pais_id =d.tipo_pais_id
            AND a.tipo_dpto_id =d.tipo_dpto_id
            AND a.tipo_mpio_id =d.tipo_mpio_id";

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
    * Funcion que sirve para consultar los agentes de riesgo de una ocupacion asignada  a un paciente
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $forma con los datos
    **/
    function MostrarDatosOcupacion($tipo_id_paciente,$paciente_id)
    {
          $sql="SELECT
                b.ocupacion_id,
                b.descripcion as nombre_ocupacion,
                e.tipo_riesgo_id,
                e.descripcion as nombre_tipo_riesgo,
                e.color,    
                d.agente_riesgo_id,
                d.descripcion as nombre_agente
                   
                   FROM
                    uv_info_paciente_ocupacion as a,
                    UV_ocupaciones_sd as b,
                    uv_agentes_de_riesgo_por_ocupacion as c,
                    uv_agentes_de_riesgos as d,
                    uv_tipos_de_riesgos as e
    
    
                WHERE
                    a.tipo_id_paciente = '".$tipo_id_paciente."'
                    AND a.paciente_id = '".$paciente_id."'
                    AND a.ocupacion_id=b.ocupacion_id
                    AND b.ocupacion_id=c.ocupacion_id
                    AND c.agente_riesgo_id=d.agente_riesgo_id
                    AND d.tipo_riesgo_id=e.tipo_riesgo_id
                    ORDER BY 1,3";
    

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
        while(!$rst->EOF)
        {
            $datos[$rst->fields[1]][$rst->fields[3]][] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    
    }
    
    /**
    * Funcion que sirve para consultar los agentes de riesgo de un espacio asignado a  a un paciente
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $forma con los datos
    **/
    function MostrarDatosEspacio($tipo_id_paciente,$paciente_id)
    {
          $sql="SELECT 
                b.tipo_espacio_id,
                b.descripcion as nombre_espacio,
                e.tipo_riesgo_id,
                e.descripcion as nombre_tipo_riesgo,
                e.color,    
                d.agente_riesgo_id,
                d.descripcion as nombre_agente
            
                
                FROM
                    UV_info_paciente_espacio as a,
                    UV_tipos_de_espacios as b,
                    uv_agentes_de_riesgo_por_tipos_de_espacios as c,
                    uv_agentes_de_riesgos as d,
                    uv_tipos_de_riesgos as e
    
    
                WHERE
                    a.tipo_id_paciente = '".$tipo_id_paciente."'
                    AND a.paciente_id = '".$paciente_id."'
                    AND a.tipo_espacio_id=b.tipo_espacio_id
                    AND b.tipo_espacio_id=c.tipo_espacio_id
                    AND c.agente_riesgo_id=d.agente_riesgo_id
                    AND d.tipo_riesgo_id=e.tipo_riesgo_id
                    ORDER BY 1,3";
    

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
        while(!$rst->EOF)
        {
            $datos[$rst->fields[1]][$rst->fields[3]][] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    
    }
    /**
    * Funcion que sirve para registrar las ocupaciones de un paciente
    * @param integer $ocupaciones
    * @param char $tipo_id_paciente
    * @param char $paciente_id
    * @return boolean true (ok),  false (exite un error)
    *
    **/
    function RegistrarInfoO($ocupaciones,$tipo_id_paciente,$paciente_id,$usuario)
    {
                    
        $query2 ="INSERT INTO
                UV_info_paciente_ocupacion
                (
                tipo_id_paciente,
                paciente_id,
                ocupacion_id,
                usuario_registro,
                fecha_registro
                )
                values('".$tipo_id_paciente."',
                        '".$paciente_id."',
                        ".$ocupaciones.",
                        ".$usuario.",
                        NOW())";
                if(!$rst = $this->ConexionBaseDatos($query2))
                {  $cad="falla en SQL insercion".$query2;
                //return $cad;
                return $cad;
                }
        
        
        return true;

    }



 /**
 * Funcion que sirve para registrar los espacios de un paciente
 * @param integer $espacios_x
 * @param char $tipo_id_paciente
 * @param char $paciente_id
 * @return boolean true (ok),  false (exite un error)
 *
 **/
    function RegistrarInfoE($espacios_x,$tipo_id_paciente,$paciente_id,$usuario)
    {
                    
          $query1 ="INSERT INTO
                  UV_info_paciente_espacio
                  (
                    tipo_id_paciente,
                    paciente_id,
                    tipo_espacio_id,
                    usuario_registro,
                    fecha_registro
                  )
                  values('".$tipo_id_paciente."',
                         '".$paciente_id."',
                         ".$espacios_x.",
                         ".$usuario.",
                         NOW())";
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }
        


       return true;



    }
    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function ObtenerOcupaciones()
    {
        $sql  = "   SELECT *
                    FROM uv_ocupaciones_sd
                    WHERE sw_estado='1'
                    ";//ORDER BY 2

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
    * Funcion para realizar la consulta de los espacios
    *
    * @return array
    */
    function ObtenerEspacios()
    {
        $sql  = "   SELECT *
                    FROM uv_tipos_de_espacios
                    WHERE sw_estado='1'
                    ";//ORDER BY 2

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
* Esta funcion verifica el ciclo vital individual en que se encuentra el paciente.
* @access private
* @return array $ciclo_individual.
*/

	function Obtenercicloindividual($edad)
	{  
          $query1 = "SELECT *
                        FROM
                        ciclo_vital_individual
                        WHERE
                        edad_min <= '".$edad."'
                        AND edad_max >= '".$edad."'
                        AND sw_mostrar='1'
                        order by 1";
          
          $result = $this->ConexionBaseDatos($query1);
          $ciclo_individual=Array();
           while(!$result->EOF)
           {
            $ciclo_individual[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
          }
         
            $result->Close();
           return $ciclo_individual;

	}
     
 /**
 *Consulta de la lista de ciclo familiares disponibles por el sistema
 * @return array $ciclo_familiar.
 **/
	
  function ConsultaCicloFamiliares()
	{  
	     $query1 = "   SELECT *
                        FROM
                        ciclo_vital_familiar
                        WHERE
                        sw_mostrar='1'
                        order by 1";
          
          $resultado = $this->ConexionBaseDatos($query1);
          $ciclo_familiar=Array();
          while(!$resultado->EOF)
          {
            $ciclo_familiar[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
          }
        
           $resultado->Close();
           return $ciclo_familiar;
	}


 /**
 *  Consulta del ciclo individual en que se encuentra el paciente en esa evolucion
 *  @return array $ciclo_familiar_seleccionados.
 **/
    
  function ConsultaCicloIndividualPaciente($ingreso,$ti_pac,$id_pac)
    {  
         $query1 = "   SELECT *
                       FROM
                       ciclo_vital_individual_detalle
                       WHERE
                       ingreso=".$ingreso."
                       AND tipo_id_paciente = '".$ti_pac."'
                       AND paciente_id = '".$id_pac."'
                       order by 1";
          
          $resultado = $this->ConexionBaseDatos($query1);
          $ciclo_familiar_seleccionados=Array();
          while(!$resultado->EOF)
          {
            $ciclo_familiar_seleccionados[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
          }
        
           $resultado->Close();
           return $ciclo_familiar_seleccionados;
    }



    
 /**
 *  Consulta de los ciclos familiares en que se encuentra el paciente en esa evolucion
 *  @return array $ciclo_familiar_seleccionados.
 **/
    
  function ConsultaCiclosFamiliaresPaciente($ingreso,$ti_pac,$id_pac)
    {  
         $query1 = "   SELECT *
                       FROM
                       ciclo_vital_familiar_detalle
                       WHERE
                       ingreso=".$ingreso."
                       AND tipo_id_paciente = '".$ti_pac."'
                       AND paciente_id = '".$id_pac."'
                       order by 1";
          
          $resultado = $this->ConexionBaseDatos($query1);
          $ciclo_familiar_seleccionados=Array();
          while(!$resultado->EOF)
          {
            $ciclo_familiar_seleccionados[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
          }
        
           $resultado->Close();
           return $ciclo_familiar_seleccionados;
    }


 /**
 *  Consulta del ciclo familiar en que se encuentra el paciente en esa evolucion
 *  @return array $ciclo_familiar_seleccionado.
 **/
    
  function ConsultaCiclosFamiliaresPacienteSeleccionado($ingreso,$ti_pac,$id_pac,$cvf)
    {  
         $query1 ="    SELECT *
                       FROM
                       ciclo_vital_familiar_detalle
                       WHERE
                       ingreso=".$ingreso."
                       AND tipo_id_paciente = '".$ti_pac."'
                       AND paciente_id = '".$id_pac."'
                       AND ciclo_vital_familiar_id = ".$cvf." order by 1";
          
          $resultado = $this->ConexionBaseDatos($query1);
           // var_dump($resultado);
          $ciclo_familiar_seleccionado=Array();
          if(!empty($resultado))
          {
                
                while(!$resultado->EOF)
                {
                    $ciclo_familiar_seleccionado[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
          }      
           return $ciclo_familiar_seleccionado;
    }



    /**
    * Funcion que inserta los datos del paciente en ciclo_vital_individual_detalle
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param integer $cvi ciclo vital individual
    * @param string $fc factor de riesgo
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function InsertarCicloIndividual($ingreso,$tip_pac,$id_pac,$cvi,$fc)
    {  
        $query1 ="INSERT INTO
                  ciclo_vital_individual_detalle
                  (
                   ingreso,
                   tipo_id_paciente,
                   paciente_id,
                   ciclo_vital_individual_id,
                   factores_de_riesgo
                  )
                  values(".$ingreso.",
                         '".$tip_pac."',
                         '".$id_pac."',
                         ".$cvi.",
                         '".$fc."');";
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }

       return true;
	}



    /**
    * Funcion que inserta los datos del paciente en ciclo_vital_familiar_detalle
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param string $cvf ciclo vital individual
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function InsertarCiclosFamiliares($ingreso,$tip_pac,$id_pac,$cvf)
    {  
        
                  $query1 ="INSERT INTO
                            ciclo_vital_familiar_detalle
                            (
                            ingreso,
                            tipo_id_paciente,
                            paciente_id,
                            ciclo_vital_familiar_id
                            )
                            values(".$ingreso.",
                                    '".$tip_pac."',
                                    '".$id_pac."',
                                    ".$cvf.");";
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL insercion".$query1;
                    return $cad;
                 }


       return $cad="CICLO FAMILIAR SELECCIONADO SATISFACTORIAMENTE";
    }   


    /**
    * Funcion que ELIMINA los datos del paciente en ciclo_vital_familiar_detalle
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param string $cvf ciclo vital individual
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function EliminarCicloFamiliar($ingreso,$tip_pac,$id_pac,$cvf)
    {  
        
                  $query1 ="DELETE FROM
                            ciclo_vital_familiar_detalle
                            WHERE
                            ingreso=".$ingreso."
                            AND tipo_id_paciente='".$tip_pac."'
                            AND paciente_id='".$id_pac."'
                            AND ciclo_vital_familiar_id=".$cvf.";";


                            
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL ELIMINACION".$query1;
                    return $cad;
                 }
                else
                {
                    $cad="Movimiento Eliminado Correctamente";
                    return $cad;
                }   
    
       
       
    }

    
    
    /**
    * Funcion que ELIMINA las observaciones del paciente en ciclo_vital_familiar_detalle
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function EliminarCFO($ingreso,$tip_pac,$id_pac)
    {  
        
                  $query1 ="DELETE FROM
                            ciclo_familiar_observaciones
                            WHERE
                            ingreso=".$ingreso."
                            AND tipo_id_paciente='".$tip_pac."'
                            AND paciente_id='".$id_pac."';";


                            
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL ELIMINACION OBSERVACION".$query1;
                    return $cad;
                 }
                else
                {
                    return true;
                }   
    
       
       
    }


    /**
    * Funcion que ELIMINA las observaciones del paciente en ciclo_vital_familiar_detalle
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function EliminarFR($ingreso,$tip_pac,$id_pac)
    {  
        
                  $query1 ="DELETE FROM
                            ciclo_vital_individual_detalle
                            WHERE
                            ingreso=".$ingreso."
                            AND tipo_id_paciente='".$tip_pac."'
                            AND paciente_id='".$id_pac."';";

                 if(!$rst = $this->ConexionBaseDatos($query1))
                 {
                    $cad="falla en SQL ELIMINACION OBSERVACION".$query1;
                    return $cad;
                 }
                 else
                 {
                    return true;
                 }   
    
       
       
    }

    /**
    * Funcion que inserta los datos del paciente en las observaciones ciclo_vital_observaciones
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param text $observaciones del paciente respecto a su ciclo familiar
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function InsertarCicloFamiliaresObservaciones($ingreso,$tip_pac,$id_pac,$observaciones)
    {  
                  $query1 ="INSERT INTO
                            ciclo_familiar_observaciones
                            (
                            ingreso,
                            tipo_id_paciente,
                            paciente_id,
                            observaciones
                            )
                            values(".$ingreso.",
                                    '".$tip_pac."',
                                    '".$id_pac."',
                                    '".$observaciones."');";


                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL insercion".$query1;
                    return $cad;
                 }


       return $cad="OBSERVACION CICLO VITAL FAMILIAR REGISTRADA EXITOSAMENTE";
    }   

    

    /**
    * Funcion que inserta el ciclo vital individual del paciente y los factores de riesgo
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param string $cvi id del paciente
    * @param text $fr factores de riesgo del paciente respecto a su ciclo individual
    * @param string $cad con mensaje de error o de exito
    **/
        
    function InsertarFR($ingreso,$tip_pac,$id_pac,$cvi,$fr)
    {  
                  $query1 ="INSERT INTO
                            ciclo_vital_individual_detalle
                            (
                            ingreso,
                            tipo_id_paciente,
                            paciente_id,
                            ciclo_vital_individual_id,
                            factores_de_riesgo
                            )
                            values(".$ingreso.",
                                  '".$tip_pac."',
                                  '".$id_pac."',
                                  '".$cvi."',
                                  '".$fr."');";


                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL insercion".$query1;
                    return $cad;
                 }


       return $cad="OBSERVACION CICLO VITAL INDIVIDUAL REGISTRADA EXITOSAMENTE";
    }   

 /**
 * Consulta del ciclo familiar en que se encuentra el paciente en esa evolucion
 * @return array $ciclo_familiar_seleccionado.
 **/
    
  function ConsultaCiclosObservaciones($ingreso,$ti_pac,$id_pac)
    {  
         $query1 ="SELECT *
                       FROM
                       ciclo_familiar_observaciones
                       WHERE
                       ingreso=".$ingreso."
                       AND tipo_id_paciente = '".$ti_pac."'
                       AND paciente_id = '".$id_pac."'";
          
          $resultado = $this->ConexionBaseDatos($query1);
           // var_dump($resultado);
          $ciclo_familiar_seleccionado=Array();
          if(!empty($resultado))
          {
                
                while(!$resultado->EOF)
                {
                    $ciclo_familiar_seleccionado[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
          }      
          return $ciclo_familiar_seleccionado;
    }


 /**
 * Consulta del ciclo familiar en que se encuentra el paciente en esa evolucion
 * @return array $ciclo_familiar_seleccionado.
 **/
    
  function ConsultaCiclosFR($ingreso,$tip_pac,$id_pac)
    {  
         $query1 ="SELECT *
                       FROM
                       ciclo_vital_individual_detalle
                       WHERE
                       ingreso=".$ingreso."
                       AND tipo_id_paciente = '".$tip_pac."'
                       AND paciente_id = '".$id_pac."'";
          
          $resultado = $this->ConexionBaseDatos($query1);
           // var_dump($resultado);
          $FR=Array();
          if(!empty($resultado))
          {
                
                while(!$resultado->EOF)
                {
                    $FR[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
          }      
          return $FR;
    }


    /**
    * Funcion que inserta los datos del paciente TOMANDO EL CICCLO VITAL INDIVIDUAL Y LOS FATORES DE RIESGO
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param text $fr del paciente respecto a su ciclo individual
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function InsertarCicloIndyFR($ingreso,$tip_pac,$id_pac,$fr)
    {  
                  $query1 ="INSERT INTO
                            ciclo_familiar_observaciones
                            (
                            ingreso,
                            tipo_id_paciente,
                            paciente_id,
                            observaciones
                            )
                            values(".$ingreso.",
                                    '".$tip_pac."',
                                    '".$id_pac."',
                                    '".$observaciones."');";


                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL insercion".$query1;
                    return $cad;
                 }


       return $cad="OBSERVACION CICLO VITAL FAMILIAR REGISTRADA EXITOSAMENTE";
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
//                 
             if ($dbconn->ErrorNo() != 0)
             {
                 $this->Error['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
                  "<b class=\"label\">".$Error['MensajeError']."</b>";
                 return false;
             }
            
             return $rst;
        }
}
?>