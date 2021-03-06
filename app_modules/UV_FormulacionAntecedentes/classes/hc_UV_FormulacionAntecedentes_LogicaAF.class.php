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

class LogicaAF 
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
	function LogicaAF($objeto=null)
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
		'fecha'=>'03/13/2006',
		'autor'=>'JAIME ANDRES GOMEZ',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


    /**
        Funcion que MODIFICA los datos del medicamento que utiliza el paciente
        * @param array $vector con todos los datos a guardar
        * @return boolean true si todo esta OK $cad con el error en el SQL
    **/
    
    function ModificarMedicamento($vector)
    {

      $query1 ="UPDATE hc_formulacion_antecedentes
                  SET
                
                    fecha_finalizacion =".$vector['fecha_finalizacion'].",
                    frecuencia='".$vector['frecuencia']."',
                    sw_permanente=".$vector['sw_permanente'].",
                    descripcion=".$vector['descripcion'].",
                    medico_id_update=".UserGetUID()."
 
                  WHERE
                  tipo_id_paciente='".$vector['tipo_id_paciente']."'
                  AND paciente_id='".$vector['paciente_id']."'
                  AND codigo_medicamento='".$vector['cod_med']."'
                  AND evolucion_id=".$vector['evolucion_id']."";


                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }
                
                return true;
    }
    
    /**
    * Funcion que sirve para extraer los datos de un producto seleccionado
    * @param string $tipo_id_paciente codigo de medicamento
    * @param string $paciente_id nombre del medicamento
    * @param string $codigo_medicamento 
    * @param string $evolucion_id evolucion en la que se encuntra el paciente
    * @return array $vector con los datos del paciente
    **/
    function DatosExtraidos($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion_id)
    {

        $query1 = "      SELECT
                            a.*,
                            b.descripcion as nombre_medicamento
                            
                           FROM
                           (
                                SELECT
                                w.*,
                                y.nombre,
                                r.nombre AS medico_update
                                FROM
                                (
                                    SELECT
                                      tipo_id_paciente,
                                      paciente_id,
                                      codigo_medicamento,
                                      TO_CHAR(fecha_registro,'DD-MM-YYYY') AS fecha_registro,
                                      TO_CHAR(fecha_finalizacion,'DD-MM-YYYY') AS fecha_finalizacion,
                                      medico_id,
                                      dosis,
                                      unidad_dosificacion,
                                      frecuencia,
                                      sw_permanente,
                                      sw_formulado,
                                      tiempo_total,
                                      perioricidad_entrega,
                                      descripcion,
                                      evolucion_id,
                                      fecha_formulacion,
                                      medico_id_update
                                    FROM
                                    hc_formulacion_antecedentes
                                    
                                    WHERE
                                    tipo_id_paciente = '".$tipo_id_paciente."'
                                    AND paciente_id = '".$paciente_id."'
                                    AND codigo_medicamento='".$codigo_medicamento."'
                                    AND evolucion_id=".$evolucion_id."
                                ) as w
                                LEFT JOIN system_usuarios as y
                                ON(w.medico_id=y.usuario_id)
                                LEFT JOIN system_usuarios as r
                                ON(w.medico_id_update=r.usuario_id)
                            ) as a
                            LEFT JOIN inventarios_productos as b
                            ON(a.codigo_medicamento = b.codigo_producto)
                            order by a.fecha_registro";
          
          $result = $this->ConexionBaseDatos($query1);
          $vector=Array();
           while(!$result->EOF)
           {
            $vector[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
          }
         
            $result->Close();
           return $vector;

    }

    /**
        Funcion que inserta los datos del medicamento no formulado pero que utiliza el paciente
        * @param array $vector con todos los datos a guardar
        * @return boolean true si todo esta OK $cad con el error en el SQL
    **/
    function GuardarNoformulado($vector)
    {
        $query1 ="INSERT INTO
                  hc_formulacion_antecedentes
                  (
                    tipo_id_paciente,
                    paciente_id,
                    codigo_medicamento,
                    fecha_registro,
                    fecha_finalizacion,
                    medico_id,
                    dosis,
                    unidad_dosificacion,
                    frecuencia,
                    sw_permanente,
                    sw_formulado,
                    descripcion,
                    evolucion_id
                  )
                  values('".$vector['tipo_id_paciente']."',
                         '".$vector['paciente_id']."',
                         '".$vector['cod_med']."',
                         '".$vector['fecha_registro']."',
                         ".$vector['fecha_finalizacion'].",
                         ".$vector['medico_id'].",
                         '".$vector['dosis']."',
                         '".$vector['unidad_dosificacion']."',
                         '".$vector['frecuencia']."',
                         ".$vector['sw_permanente'].",
                         ".$vector['sw_formulado'].",
                         ".$vector['descripcion'].",
                         '".$vector['evolucion_id']."');";


                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }

                
                return true;

    
    
    }

    
       /**
       *funcion que obtiene los tipos de dosificacion
       *
       *@return array() $unidades.
       **/
        function ObtenerUnidadesDosificacion()
        {
            $query1 = "SELECT *
                        FROM
                        hc_unidades_dosificacion
                        order by 1";
            
            $result = $this->ConexionBaseDatos($query1);
            $unidades=Array();
            while(!$result->EOF)
            {
                $unidades[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            
            $result->Close();
            return $unidades;
        
        }
    
       /**
       * Funcion que sirve para seleccionar cuanto registros mostrar
       *
       **/
        function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
        { 
            $this->offset = 0;
            $this->paginaActual = 1;
            if($limite == null)
            {
                $this->limit = GetLimitBrowser();
            }
            else
            {
                $this->limit = $limite;
            }
            
            if($offset)
            {
                $this->paginaActual = intval($offset);
                if($this->paginaActual > 1)
                {
                    $this->offset = ($this->paginaActual - 1) * ($this->limit);
                }
            }       

            if(!$result = $this->ConexionBaseDatos($consulta))
                return false;

            if(!$result->EOF)
            {
                $this->conteo = $result->fields[0];
                $result->MoveNext();
            }
            $result->Close();
      
      
            return true;
          
    }



    

    /**
    * Funcion que colsulta medicamentos 
    * @param integer $opcion ingreso
    * @param string $producto tipo de id del paciente
    * @param string $principio_activo id del paciente
    * @return array $var con la lista de los medicamentos
    **/
    function Busqueda_Avanzada_Medicamentos($opcion,$producto,$principio_activo,$ban,$offset)
{
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $producto =STRTOUPPER($producto);
        $principio_activo =STRTOUPPER($principio_activo);

        $busqueda1  = '';
        $busqueda2  = '';
        $dpto = '';
        $espe = '';
        $declaracion = '';
        $condicion = '';

        if ($producto != '')
        {
              $busqueda1 =" AND a.descripcion LIKE '%$producto%'";
        }

        if ($principio_activo != '')
        {
                $busqueda2 ="AND c.descripcion LIKE '%$principio_activo%'";
        }

        if($opcion == '002')
            {
                $declaracion = ", inv_solicitud_frecuencia as m ";
                $condicion   = "AND a.codigo_producto = m.codigo_producto";
                if ($this->departamento != '' )
                    {
                        $dpto = "AND m.departamento = '".$this->departamento."'";
                    }
                if ($this->especialidad != '' )
                    {
                        $espe = "AND m.especialidad = '".$this->especialidad."'";
                    }
                if ($dpto == '' AND $espe == '')
                    {
                        return false;
                    }
            }


            if($ban=='0')
            {
                $query = "SELECT count(*)
                            FROM
                            inventarios_productos as a,
                            medicamentos as b,
                            inv_med_cod_principios_activos as c,
                            inv_med_cod_forma_farmacologica as d $declaracion
                            WHERE
                            a.codigo_producto = b.codigo_medicamento
                            AND b.cod_principio_activo = c.cod_principio_activo
                            AND b.cod_forma_farmacologica = d.cod_forma_farmacologica
                            AND a.estado = '1'
                            $condicion $dpto $espe $busqueda1 $busqueda2";
                            
                    //echo $query;

                    $resulta = $dbconn->Execute($query);
        
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $var=array();
                    list($var['CUANTOS_HAY'])=$resulta->fetchRow();
                    SessionDelVar("CUANTOS_HAY");
                    SessionSetVar("CUANTOS_HAY",$var['CUANTOS_HAY']);
        
            }


                         $query = "SELECT count(*)
                            FROM
                            inventarios_productos as a,
                            medicamentos as b,
                            inv_med_cod_principios_activos as c,
                            inv_med_cod_forma_farmacologica as d $declaracion
                            WHERE
                            a.codigo_producto = b.codigo_medicamento
                            AND b.cod_principio_activo = c.cod_principio_activo
                            AND b.cod_forma_farmacologica = d.cod_forma_farmacologica
                            AND a.estado = '1'
                            $condicion $dpto $espe $busqueda1 $busqueda2";
                            $this->ProcesarSqlConteo($sql1,10,$offset);  
                    //echo $query;


                        $query ="
                        SELECT
                        CASE WHEN b.sw_pos = 1 then 'POS'
                             ELSE 'NO POS'
                        END as item,
                        a.codigo_producto,
                        a.descripcion as producto,
                        c.descripcion as principio_activo,
                        d.descripcion as forma,
                        d.unidad_dosificacion,
                        b.concentracion_forma_farmacologica,
                        b.unidad_medida_medicamento_id,
                        b.factor_conversion,
                        b.factor_equivalente_mg,
                        d.cod_forma_farmacologica

                        FROM
                        inventarios_productos as a,
                        medicamentos as b,
                        inv_med_cod_principios_activos as c,
                        inv_med_cod_forma_farmacologica as d
                        $declaracion

                        WHERE
                        a.codigo_producto = b.codigo_medicamento
                        AND b.cod_principio_activo = c.cod_principio_activo
                        AND b.cod_forma_farmacologica = d.cod_forma_farmacologica
                        AND a.estado = '1'
                        $condicion $dpto $espe $busqueda1 $busqueda2
                        order by a.codigo_producto
                        limit ".$this->limit." OFFSET ".$this->offset."";
  


        $resulta = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        
        
        while(!$resulta->EOF)
        {
            $var['MEDICAMENTOS'][]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }

        $resulta->Close();
        return $var;
}




        function Busqued_Avanzada_Medicamentos($producto,$principio_activo)
        {
            $pfj = $this->frmPrefijo;
            $where = "";
            
            $sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
            $sql .= "               ELSE 'NO POS' END AS item,";
            $sql .= "               IM.codigo_producto, ";
            $sql .= "               IM.descripcion as producto, ";
            $sql .= "               IU.descripcion AS umm, ";
            $sql .= "               ME.concentracion_forma_farmacologica AS cff,";
            $sql .= "               ME.unidad_medida_medicamento_id AS ummi,";
            $sql .= "               ME.factor_conversion, ";
            $sql .= "               ME.factor_equivalente_mg,";
            $sql .= "               IA.descripcion AS principio_activo,";
            $sql .= "               IF.descripcion AS forma,";
            $sql .= "               IF.unidad_dosificacion,";
            $sql .= "               IF.cod_forma_farmacologica, ";
            $sql .= "               IM.sw_solicita_autorizacion ";
            

            $where .= "FROM         inventarios_productos IM, ";
            $where .= "             inv_med_cod_principios_activos IA,  ";
            $where .= "             inv_med_cod_forma_farmacologica IF,  ";
            $where .= "             inventarios IT,  ";
            $where .= "             medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
            $where .= "             ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id) ";
            $where .= "WHERE    IM.codigo_producto = ME.codigo_medicamento ";
            $where .= "AND      ME.cod_principio_activo = IA.cod_principio_activo ";
            $where .= "AND      ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
            $where .= "AND      IM.estado = '1' ";
            $where .= "AND      IT.estado = '1' ";
            $where .= "AND      IT.empresa_id = '".$this->empresa_id."' ";
            $where .= "AND      IT.codigo_producto = IM.codigo_producto ";
            
            $producto = $_REQUEST['producto'.$pfj];
            $principio_activo = $_REQUEST['principio_activo'.$pfj];
            
            if ($producto != '') $where .= "AND     IM.descripcion ILIKE '%".$producto."%'";
            if ($principio_activo != '') $where .= "AND         IA.descripcion ILIKE '%".$principio_activo."%'";
            
            $this->ProcesarSqlConteo("SELECT COUNT(*) $where");
            
            //$orden = "producto";
            //if($_REQUEST['orden']) $orden = $_REQUEST['orden'];
            $sql .= $where;
            $sql .= "ORDER BY IM.codigo_producto ";
            //$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
            
            if(!$rst = $this->ConexionBaseDatos($sql)) return false;
            
            $datos = SessionGetVar("MedicamentosSeleccionados");
            $retorno = array();
            while(!$rst->EOF)
            {
                $retorno[] = $rst->GetRowAssoc($ToUpper = false);
                $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
            SessionSetVar("MedicamentosSeleccionados",$datos);
            return $retorno;
        }
    /**
    * Funcion que colsulta medicamentos que le han sido asignados al usuario
    * @param string  $tip_pac tipo de id del paciente
    * @param string  $id_pac id del paciente
    * @param integer $evolucion 
    * @return array $medicamentos_usu  lista de medicamentos de usuario.
    **/
    function Busqueda_Medicamentos_Usuario($tip_pac,$id_pac,$ingreso)
    {
      $sql  = "SELECT a.*,";
      $sql .= "       b.descripcion ";
      $sql .= "FROM  ( SELECT  w.*, ";
      $sql .= "                y.nombre ";
      $sql .= "        FROM   ( ";
      $sql .= "                  SELECT HA.* ";
      $sql .= "                  FROM  hc_formulacion_antecedentes HA,";
      $sql .= "                        hc_evoluciones HE ";
      $sql .= "                  WHERE HA.evolucion_id = HE.evolucion_id "; 
      if($ingreso)
        $sql .= "                  AND   HE.ingreso = ".$ingreso." ";
      else
      {
        $sql .= "                  AND  HA.tipo_id_paciente = '".$tip_pac."' ";
        $sql .= "                  AND  HA.paciente_id = '".$id_pac."' "; 
      }
      $sql .= "                ) as w ";
      $sql .= "                LEFT JOIN system_usuarios as y ";
      $sql .= "                ON(w.medico_id=y.usuario_id) ";
      $sql .= "      ) as a ";
      $sql .= "      LEFT JOIN inventarios_productos as b ";
      $sql .= "      ON(a.codigo_medicamento = b.codigo_producto) ";
      $sql .= "ORDER BY a.fecha_registro DESC ";
      
      $result = $this->ConexionBaseDatos($sql);
      $medicamentos_usu=Array();
      while(!$result->EOF)
      {
        $medicamentos_usu[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
     
      $result->Close();
      return $medicamentos_usu;
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

       $cad=true;          
       return $cad;
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