<?php
/** 
    * $Id: hc_UV_CicloFamiliar_LogicaCF.class.php,v 1.1 2008/09/03 18:50:27 hugo Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.1 $ 
    * 
    * @autor J gomez
    */

class LogicaCF 
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
	function LogicaCF($objeto=null)
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
    * Funcion que ELIMINA los datos del paciente en ciclo_vital_factores_riesgo_paciente
    * @param integer $ingreso ingreso
    * @param string $tip_pac tipo de id del paciente
    * @param string $id_pac id del paciente
    * @param string $factor_riesgo_id 
    * @param boolean true si todo esta OK $cad con el error en el SQL
    **/
        
    function EliminarFactorRiesgo($ingreso,$tip_pac,$id_pac,$factor_riesgo_id)
    {  
        
                  $query1 ="DELETE FROM
                            ciclo_vital_factores_riesgo_paciente
                            WHERE
                            ingreso=".$ingreso."
                            AND tipo_id_paciente='".$tip_pac."'
                            AND paciente_id='".$id_pac."'
                            AND factor_riesgo_id=".$factor_riesgo_id.";";


                            
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL ELIMINACION".$query1;
                    return $cad;
                 }
                else
                {
                    $cad=true;
                    return $cad;
                }   
    
       
       
    }

	
	
	
/**
* Esta funcion registra que tipos de riesgos se encuentra de paciente.
* @access private
* @return array $factores_riesgo.
*/

	function RegistrarFactorRiesgoPaciente($ingreso,$tipo_id_paciente,$paciente_id,$factor_riesgo_id,$ciclo_vital_individual_id)
	{
		$sql  = "INSERT INTO ciclo_vital_factores_riesgo_paciente ";
		$sql .= "   (	 ";
		$sql .= "     ingreso, ";
    $sql .= "     tipo_id_paciente, ";
    $sql .= "     paciente_id, ";
    $sql .= "     factor_riesgo_id, ";
    $sql .= "     ciclo_vital_individual_id  ";
    $sql .= "   ) ";
		$sql .= "VALUES(";
    $sql .= "     ".$ingreso.",";
    $sql .= "    '".$tipo_id_paciente."',";
    $sql .= "    '".$paciente_id."',";
    $sql .= "     ".$factor_riesgo_id.", ";
    $sql .= "     ".$ciclo_vital_individual_id." ";
    $sql .= " ) ";
	
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		 {  $cad="falla en SQL insercion".$sql;
			//return $cad;
			return $cad;
		 }
		 else
		 {
			$cad =true;
		 }

       return $cad;
	}
	/**
  * Esta funcion verifica en que tipos de riesgos se encuentra expuesto el paciente.
  * @access private
  * @return array $factores_riesgo.
  */
	function ObtenerFactoresRiesgoPaciente($edad,$ingreso,$tipo_id_paciente,$paciente_id)
	{  
    $query1 = "SELECT DISTINCT a.*, 
                            case when (a.edad_min <= '".$edad."'
                                      AND a.edad_max >= '".$edad."'
                                      AND a.sw_mostrar='1') then 1 END as checksito,
                  					case when (
                  								b.ingreso=".$ingreso." 	AND
                  								b.tipo_id_paciente='".$tipo_id_paciente."'	AND
                  								b.paciente_id='".$paciente_id."' AND	 	
                  								a.factor_riesgo_id=b.factor_riesgo_id
                  							   ) then 'activo' ELSE 'inactivo' END as factor_seleccionado
                    FROM  ciclo_vital_factores_riesgo as a 
                          LEFT JOIN
                          ciclo_vital_factores_riesgo_paciente AS b
                          ON (a.factor_riesgo_id=b.factor_riesgo_id)
                  	ORDER BY 1";
                            
          $result = $this->ConexionBaseDatos($query1);
          $factores_riesgo=Array();
           while(!$result->EOF)
           {
            $factores_riesgo[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
           }
           $result->Close();
           return $factores_riesgo;
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
    *
    */
    function ObtenerDatosCicloFamiliar($ingreso)
    {
      $sql  = "SELECT 'CICLO VITAL FAMILIAR' AS grupo, ";
      $sql .= "       CV.descripcion ";
      $sql .= "FROM   ciclo_vital_familiar CV, ";
      $sql .= "       ciclo_vital_familiar_detalle CD ";
      $sql .= "WHERE  CD.ingreso = ".$ingreso." ";
      $sql .= "AND    CD.ciclo_vital_familiar_id = CV.ciclo_vital_familiar_id ";
      $sql .= "UNION ";
      $sql .= "SELECT 'FACTORES DE RIESGO' AS grupo, ";
      $sql .= "       CR.descripcion ";
      $sql .= "FROM   ciclo_vital_factores_riesgo CR, ";
      $sql .= "       ciclo_vital_factores_riesgo_paciente CP ";
      $sql .= "WHERE  CR.factor_riesgo_id = CP.factor_riesgo_id ";
      $sql .= "AND    CP.ingreso = ".$ingreso." ";
      
      $rst = $this->ConexionBaseDatos($sql);
      
      $datos = array();         
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerDatosCicloIndividual($ingreso)
    {
      $sql  = "SELECT 'CICLO VITAL INDIVIDUAL' AS grupo, ";
      $sql .= "       CI.descripcion ";
      $sql .= "FROM   ingresos IG, ";
      $sql .= "       pacientes PA, ";
      $sql .= "       ciclo_vital_individual CI ";
      $sql .= "WHERE  IG.paciente_id = PA.paciente_id  ";
      $sql .= "AND    IG.tipo_id_paciente = PA.tipo_id_paciente  ";
      $sql .= "AND    edad(PA.fecha_nacimiento) >= edad_min  ";
      $sql .= "AND    edad(PA.fecha_nacimiento) <= edad_max  ";
      $sql .= "AND    IG.ingreso = ".$ingreso."  ";
      
      $rst = $this->ConexionBaseDatos($sql);
      
      $datos = array();         
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
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