<?php
/**
* $Id: ManejoTerceros.class.php,v 1.5 2009/10/19 22:20:30 mauricio Exp $
*/

/**
* Clase para la consulta y de terceros
*
* @author J_Gomez
* @version $Revision: 1.5 $
* @package SIIS
*/
class ManejoTerceros
{

    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError;

    
    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/


    /**
    * Constructor
    *
    * @return boolean
    * @access public
    */
    function Terceros()
    {
    
    }


    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }


    /**
    * Metodo para obtener terceros filtrados por un tipo de identificacion
    *
    * @param string $filtro (CRITERIO DE BUSQUEDA)
    * @param integer $limite (cantidad de refistros a mostrar por pagina)
    * @param integer $offset (desde que registro inicia la consulta)
    * @return array
    * @access public
    */
    function GetTerceros($filtro,$limit,$offset)
    {
        
        if(empty($filtro))
        {
          $filtro = "";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                tipo_id_tercero,
                tercero_id,
                tipo_pais_id,
                tipo_dpto_id,
                tipo_mpio_id,
                direccion,
                telefono,
                fax,
                email,
                celular,
                sw_persona_juridica,
                nombre_tercero
                
                FROM
                terceros

                $filtro
                
                $filtro_limit";
        //return $sql;
		//$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTEN TERCEROS CON EL CRITERIO =[$filtro]";
            return false;
        }

        $retorno = array();
        while($fila = $result->FetchRow())
        {
            $retorno[$fila['tipo_id_tercero']][]=$fila;
        }
        $result->Close();
        return  $retorno;
    }


    /**
    * Metodo para obtener un tercero especifico.
    *
    * @param string $tipo_id_tercero (tipo de identificacion del tercero)
    * @param string $tercero_id 
    * @return array
    * @access public
    */
    function GetTercero($tipo_id_tercero,$tercero_id)
    {
        if(empty($tipo_id_tercero))
        {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "PARAMETRO [tipo_id_tercero] ES REQUERIDO.";
                return false;
        }


        if(empty($tercero_id))
        {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "PARAMETRO [tercero_id] ES REQUERIDO.";
                return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT *

                FROM
                terceros

                WHERE
                tipo_id_tercero= '$tipo_id_tercero'
                AND tercero_id = '$tercero_id'
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        elseif($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE UN TERCERO CON TIPO DE IDENTIFICACION =[$tipo_id_tercero] Y CON NUMERO DE IDENTIFICACION=[$tercero_id].";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;
    }



    /**
    * Metodo para crear un nuevo tercero.
    * @param array    $datos (atos del nuevo tercero)
    * @return integer $sw_creado(1 tercero creado)
    * @access public
    */
    function NewTercero($datos)
    {
        if(empty($datos))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "VECTOR DE DATOS NO PESENTE";
            return false;
        }

        if(empty($datos['tipo_id_tercero']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_id_tercero] ES REQUERIDO.";
            return false;
        }

        if(empty($datos['tercero_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tercero_id] ES REQUERIDO.";
            return false;
        }

        if(empty($datos['tipo_pais_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_pais_id] ES REQUERIDO.";
            return false;
        }

        if(empty($datos['tipo_dpto_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_dpto_id] ES REQUERIDO.";
            return false;
        }

       if(empty($datos['tipo_mpio_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_mpio_id] ES REQUERIDO.";
            return false;
        }

        if($datos['direccion']=="")
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [direccion] ES REQUERIDO.";
            return false;
        }

        
        if(!isset($datos['telefono']))
        {
          $datos['telefono']="NULL";
        }
        elseif($datos['telefono']=='')
        {
          $datos['telefono']="NULL";
        }
        else
        {
          $datos['telefono']="'".$datos['telefono']."'";
        }

        if(!isset($datos['fax']))
        {
          $datos['fax']="NULL";
        }
        elseif($datos['fax']=='')
        {
          $datos['fax']="NULL";
        }
        else
        {
          $datos['fax']="'".$datos['fax']."'";
        }

        if(!isset($datos['email']))
        {
          $datos['email']="NULL";
        }
        elseif($datos['email']=='')
        {
          $datos['email']="NULL";
        }
        else
        {
          $datos['email']="'".$datos['email']."'";
        }
        
        if(!isset($datos['celular']))
        {
          $datos['celular']="NULL";
        }
        elseif($datos['celular']=='')
        {
          $datos['celular']="NULL";
        }
        else
        {
          $datos['celular']="'".$datos['celular']."'";
        }

        if($datos['sw_persona_juridica']=="")
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [sw_persona_juridica] ES REQUERIDO.";
          return false;
        }

        if(empty($datos['usuario_id']))
        {
          $datos['usuario_id'] = UserGetUID();
        }
        
        if($datos['nombre_tercero']=='')
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [nombre_tercero] ES REQUERIDO.";
          return false;
        }
        
        if(!isset($datos['dv']))
        {
          $datos['dv']="NULL";
        }
        elseif($datos['dv']=='')
        {
          $datos['dv']="NULL";
        }
        else
        {
          $datos['dv']="'".$datos['dv']."'";
        }
        
        $sql = "INSERT INTO terceros
                    (
                        tipo_id_tercero,
                        tercero_id,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        direccion,
                        telefono,
                        fax,
                        email,
                        celular,
                        sw_persona_juridica,
                        usuario_id,
                        nombre_tercero,
                        dv
                    )
                VALUES
                    (
                       '".$datos['tipo_id_tercero']."',
                       '".$datos['tercero_id']."',
                       '".$datos['tipo_pais_id']."',
                       '".$datos['tipo_dpto_id']."',
                       '".$datos['tipo_mpio_id']."',
                       '".$datos['direccion']."',
                        ".$datos['telefono'].",
                        ".$datos['fax'].",
                        ".$datos['email'].",
                        ".$datos['celular'].",
                       '".$datos['sw_persona_juridica']."',
                        ".$datos['usuario_id'].",
                       '".$datos['nombre_tercero']."',
                        ".$datos['dv']."
                    )";
        
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $sw_creado=1;
        return $sw_creado;
    }

    
    /**
    * Metodo para obtener un vector de proveedores buscados por un metodo especifico.
    * @param string $filtro (CRITERIO DE BUSQUEDA DEBE COMENZAR POR AND)
    * @param integer $limite (cantidad de refistros a mostrar por pagina)
    * @param integer $offset (desde que registro inicia la consulta)
    * @return array
    * @access public
    */
    function GetTercerosProveedores($filtro,$limit,$offset)
    {
        if(empty($filtro))
        {
            $filtro ="";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = " LIMIT 10 OFFSET 0 ";
        }


        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                a.tipo_id_tercero,
                a.tercero_id,
                a.tipo_pais_id,
                a.tipo_dpto_id,
                a.tipo_mpio_id,
                a.direccion,
                a.telefono,
                a.fax,
                a.email,
                a.celular,
                a.sw_persona_juridica,
                a.nombre_tercero,
                a.dv,
                b.codigo_proveedor_id,
                b.estado,
                b.dias_gracia,
                b.dias_credito,
                b.tiempo_entrega,
                b.descuento_por_contado,
                b.sw_regimen_comun,
                b.sw_gran_contribuyente,
                b.actividad_id,
                b.porcentaje_rtf,
                b.porcentaje_ica,
                c.descripcion,
                b.representante_ventas,
                b.telefono_representante_ventas,
                b.nombre_gerente,
                b.prioridad_compra,
                b.telefono_gerente
                         
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                LEFT JOIN
                actividades_industriales AS c
                ON (b.actividad_id=c.actividad_id)
                $filtro
                $filtro_limit";
       // return $sql;
       $this->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        elseif($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTEN PROVEEDORES CON LOS CRITERIOS.".$filtro."";
            return false;
        }


        $retorno = array();
        while($fila = $result->FetchRow())
        {
            $retorno[$fila['tipo_id_tercero']][]=$fila;
        }
        $result->Close();
        return  $retorno; 

    }

    /**
    * Metodo para obtener un proveedor especifico.
    *
    * @param string $proveedor_id 
    * @return array con los datos del proveedor.
    * @access public
    */
    function GetProveedor($proveedor_id)
    {
        
        if(empty($proveedor_id))
        {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "PARAMETRO [proveedor_id] ES REQUERIDO.";
                return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
         $sql="SELECT 
                x.*,
                c.actividad_id, 
                c.descripcion as descripcion_actividad
               FROM
                (SELECT 
                a.tipo_id_tercero, 
                a.tercero_id, 
                a.tipo_pais_id, 
                a.tipo_dpto_id, 
                a.tipo_mpio_id, 
                a.direccion, 
                a.telefono, 
                a.fax, 
                a.email, 
                a.celular, 
                a.sw_persona_juridica, 
                a.nombre_tercero, 
                a.dv, 
                b.codigo_proveedor_id, 
                b.estado, 
                b.dias_gracia, 
                b.dias_credito, 
                b.tiempo_entrega, 
                b.descuento_por_contado, 
                b.sw_regimen_comun, 
                b.sw_gran_contribuyente, 
                b.actividad_id, 
                b.porcentaje_rtf, 
                b.porcentaje_ica,
                b.representante_ventas,
                b.telefono_representante_ventas,
                b.nombre_gerente,
                b.prioridad_compra,
                b.telefono_gerente
                FROM 
                terceros AS a, 
                terceros_proveedores AS b 
                WHERE 
                b.codigo_proveedor_id=$proveedor_id
                AND a.tipo_id_tercero=b.tipo_id_tercero 
                AND a.tercero_id=b.tercero_id ) as x 
                LEFT JOIN 
                actividades_industriales AS c 
                ON(x.actividad_id=c.actividad_id)";
         
               // return $sql;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        elseif($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE UN TERCERO CON TIPO DE IDENTIFICACION =[$tipo_id_tercero] Y CON NUMERO DE IDENTIFICACION=[$tercero_id].";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();
           
        return  $retorno;
    }



     /**
    * Metodo para crear un nuevo tercero proveedor.PRIMERO GUARDA EN TERCEROS Y DESPUES LO HACE EN TERCEROS PROVEEDORES
    * @param array    $datos (datos del nuevo tercero proveedor)
                              $datos['tipo_id_tercero'],
                              $datos['tercero_id'],
                              $datos['nombre_tercero'],
                              $datos['tipo_pais_id'],
                              $datos['tipo_dpto_id'],
                              $datos['tipo_mpio_id'],
                              $datos['direccion'],
                              $datos['telefono'],
                              $datos['fax'],
                              $datos['email'],
                              $datos['celular'],
                              $datos['sw_persona_juridica'],
                              $datos['dv'],
                              $datos['sw_regimen_comun'],
                              $datos['sw_gran_contribuyente'],
                              $datos['actividad_id'],
                              $datos['porcentaje_rtf'],
                              $datos['porcentaje_ica']
    * @return integer $sw_creado_prov(1 tercero_pro creado)
    * @access public
    */
    function NewTerceroProveedor($datos)
    {
                              
    
    if(empty($datos))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "VECTOR DE DATOS NO PESENTE";
            return false;
        }
        if(empty($datos['tipo_id_tercero']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_id_tercero] ES REQUERIDO.";
            return false;
        }
          
        if(empty($datos['tercero_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tercero_id] ES REQUERIDO.";
            return false;
            
        }
          
        if(empty($datos['tipo_pais_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_pais_id] ES REQUERIDO.";
            return false;
                              
        }
          
        if(empty($datos['tipo_dpto_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_dpto_id] ES REQUERIDO.";
            return false;
        }
          
       if(empty($datos['tipo_mpio_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [tipo_mpio_id] ES REQUERIDO.";
            return false;
        }
        
        if($datos['direccion']=="")
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CREACION [direccion] ES REQUERIDO.";
            return false;
        }

        if(!isset($datos['telefono']))
        {
          $datos['telefono']="NULL";
        }
        elseif($datos['telefono']=='')
        {
          $datos['telefono']="NULL";
        }
        else
        {
          $datos['telefono']="'".$datos['telefono']."'";
        }

        if(!isset($datos['fax']))
        {
          $datos['fax']="NULL";
        }
        elseif($datos['fax']=='')
        {
          $datos['fax']="NULL";
        }
        else
        {
          $datos['fax']="'".$datos['fax']."'";
        }

        if(!isset($datos['email']))
        {
          $datos['email']="NULL";
        }
        elseif($datos['email']=='')
        {
          $datos['email']="NULL";
        }
        else
        {
          $datos['email']="'".$datos['email']."'";
        }
        
        if(!isset($datos['celular']))
        {
          $datos['celular']="NULL";
        }
        elseif($datos['celular']=='')
        {
          $datos['celular']="NULL";
        }
        else
        {
          $datos['celular']="'".$datos['celular']."'";
        }
          
        if($datos['sw_persona_juridica']=="")
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [sw_persona_juridica] ES REQUERIDO.";
          return false;
        }
        
        if(empty($datos['usuario_id']))
        {
          $datos['usuario_id'] = UserGetUID();
        }
          
        if($datos['nombre_tercero']=='')
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [nombre_tercero] ES REQUERIDO.";
          return false;
        }
        
        if(!isset($datos['dv']))
        {
          $datos['dv']="NULL";
        }
        elseif($datos['dv']=='')
        {
          $datos['dv']="NULL";
        }
        else
        {
          $datos['dv']="'".$datos['dv']."'";
        }

        $sql="SELECT nextval('terceros_proveedores_codigo_proveedor_id_seq'::regclass)";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($sql);
        $retorno = $result->FetchRow();
        //var_dump($retorno); 
        $result->Close();

        $datos['codigo_proveedor_id']=$retorno[0];

        if($datos['sw_regimen_comun']=="")
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [sw_regimen_comun] ES REQUERIDO.";
          return false;
        }
       
        if($datos['sw_gran_contribuyente']=="")
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [sw_gran_contribuyente] ES REQUERIDO.";
          return false;
        }
        
        if($datos['actividad_id']=="")
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [actividad_id] ES REQUERIDO.";
          return false;
        }
        
        if($datos['porcentaje_rtf']=="")
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO DE CREACION [porcentaje_rtf] ES REQUERIDO.";
          return false;
        }

        $pais=GetVarConfigAplication('DefaultPais');
        $depto=GetVarConfigAplication('DefaultDpto');
        $mpio=GetVarConfigAplication('DefaultMpio');
        $rtica="0";

        if( $datos['tipo_pais_id']==$pais && $datos['tipo_dpto_id']==$depto && $datos['tipo_mpio_id']==$mpio)
        {
            if($datos['porcentaje_ica']=="")
            {
              $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
              $this->mensajeDeError = "PARAMETRO DE CREACION [porcentaje_ica] ES REQUERIDO.";
              return false;
            }
         $rtica="1";
        }
        
        if(!isset($datos['dias_gracia']))
        {
          $datos['dias_gracia']="0";
        }
        elseif($datos['dias_gracia']=='')
        {
          $datos['dias_gracia']="0";
        }
        else
        {
          $datos['dias_gracia']="'".$datos['dias_gracia']."'";
        }
        
        if(!isset($datos['dias_credito']))
        {
          $datos['dias_credito']="0";
        }
        elseif($datos['dias_credito']=='')
        {
          $datos['dias_credito']="0";
        }
        else
        {
          $datos['dias_credito']="'".$datos['dias_credito']."'";
        }
        
        if(!isset($datos['tiempo_entrega']))
        {
          $datos['tiempo_entrega']="0";
        }
        elseif($datos['tiempo_entrega']=='')
        {
          $datos['tiempo_entrega']="0";
        }
        else
        {
          $datos['tiempo_entrega']="'".$datos['tiempo_entrega']."'";
        }
                        
        if(!isset($datos['descuento_por_contado']))
        {
          $datos['descuento_por_contado']="0";
        }
        elseif($datos['descuento_por_contado']=='')
        {
          $datos['descuento_por_contado']="0";
        }
        else
        {
          $datos['descuento_por_contado']="'".$datos['descuento_por_contado']."'";
        }  
       
         
         $dbconn->BeginTrans();
         $sql = "INSERT INTO terceros
                    (
                        tipo_id_tercero,
                        tercero_id,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        direccion,
                        telefono,
                        fax,
                        email,
                        celular,
                        sw_persona_juridica,
                        usuario_id,
                        nombre_tercero,
                        dv,
                        empresa_id
                    )
                VALUES
                    (
                       '".$datos['tipo_id_tercero']."',
                       '".$datos['tercero_id']."',
                       '".$datos['tipo_pais_id']."',
                       '".$datos['tipo_dpto_id']."',
                       '".$datos['tipo_mpio_id']."',
                       '".$datos['direccion']."',
                        ".$datos['telefono'].",
                        ".$datos['fax'].",
                        ".$datos['email'].",
                        ".$datos['celular'].",
                       '".$datos['sw_persona_juridica']."',
                        ".$datos['usuario_id'].",
                       '".$datos['nombre_tercero']."',
                        ".$datos['dv'].",
                        '".$datos['empresa_id']."'
                    );";

        

        $sql.= "INSERT INTO terceros_proveedores
                    (
                        codigo_proveedor_id,
                        empresa_id,
                        tipo_id_tercero,
                        tercero_id,
                        dias_gracia,
                        dias_credito,
                        tiempo_entrega,
                        descuento_por_contado,
                        sw_regimen_comun,
                        sw_gran_contribuyente,
                        actividad_id,
                        porcentaje_rtf,
                        representante_ventas,
                        telefono_representante_ventas,
                        nombre_gerente,
                        prioridad_compra,
                        telefono_gerente";
                        //if($rtica==1)
                        //{
                          $sql.=",porcentaje_ica";
                        //}
                        
                        
       $sql.="      )
                VALUES
                    (  '".$datos['codigo_proveedor_id']."',
                        '".$datos['empresa_id']."',
                       '".$datos['tipo_id_tercero']."',
                       '".$datos['tercero_id']."',
                       ".$datos['dias_gracia'].",
                       ".$datos['dias_credito'].",
                       ".$datos['tiempo_entrega'].",
                       ".$datos['descuento_por_contado'].",
                       '".$datos['sw_regimen_comun']."',
                       '".$datos['sw_gran_contribuyente']."',
                       '".$datos['actividad_id']."',
                        '".$datos['porcentaje_rtf']."',
                        '".$datos['representante_ventas']."',
                        '".$datos['telefono_representante_ventas']."',
                        '".$datos['nombre_gerente']."',
                        '".$datos['prioridad_compra']."',
                        '".$datos['telefono_gerente']."'
                        
                        ";
                       // if($rtica==1)
                        //{
                          $sql.=",'".$datos['porcentaje_ica']."'";
                        //}
                        
        $sql.="      )";
        
       // return $sql;
       //print_r($sql);
        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg() . $sql ;
            return false;
        }

        $dbconn->CommitTrans();
        $sw_creado_prov=1;
        return $sw_creado_prov;
    }



    /**
    * Metodo para actualizar un tercero
    *
    * @param array $llave. vector con los valores de la llave primaria de la tabla.
    * @param array $datos los datos a modificar.
    * @return integer $sw_ok (1 si fue hecha la actualizacion)
    * @access public
    */
    function UpdateTercero($llave=array(),$datos=array())
    {
        if(empty($datos))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "VECTOR DE DATOS NO PRESENTE";
            return false;
        }

        if(empty($llave))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTA LA LLAVE PRIMARIA DE LA TABLA";
            return false;
        }
         

        
      $sql="Update terceros SET";
        
      foreach($datos as $k=>$valor) // set de los datos y sus valores
      {
        $sql.=" ".$k."='".$valor."',";
      }
      $sql=substr($sql,0,(strlen($sql)-1));
      $sql.=" WHERE"; 
      $ban=0;    
      foreach($llave as $k=>$valor)//set de la llave con sus valores
      { if($ban==1)
        {
          $sql.=" AND";
          $sql.=" ".$k."='".$valor."'";
        }
        else
        {
            $sql.=" ".$k."='".$valor."'";
            $ban=1;
        }
      }
      //echo $sql;

      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn) = GetDBconn();

      $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg() . $sql ;
            return false;
        }

        $sw_ok=1;
        return $sw_ok; 

    }

  /**
    * Metodo para actualizar un proveedor
    *
    * @param array $llave. vector con los valores de la llave primaria de la tabla.
    * @param array $datos los datos a modificar.
    * @return integer $sw_ok (1 si fue hecha la actualizacion)
    * @access public
    */
    function UpdateProveedor($llave=array(),$datos=array())
    {
        if(empty($datos))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "VECTOR DE DATOS NO PRESENTE";
            return false;
        }

        if(empty($llave))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTA LA LLAVE PRIMARIA DE LA TABLA";
            return false;
        }
         

        
      $sql="Update terceros_proveedores SET";
        
      foreach($datos as $k=>$valor) // set de los datos y sus valores
      {
        $sql.=" ".$k."='".$valor."',";
      }
      $sql=substr($sql,0,(strlen($sql)-1));
      $sql.=" WHERE"; 
      $ban=0;    
      foreach($llave as $k=>$valor)//set de la llave con sus valores
      { if($ban==1)
        {
          $sql.=" AND";
          $sql.=" ".$k."='".$valor."'";
        }
        else
        {
            $sql.=" ".$k."='".$valor."'";
            $ban=1;
        }
      }
     
      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn) = GetDBconn();

      $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg() . $sql ;
            return false;
        }
 $this->debug=true;

        $sw_ok=1;
        return $sw_ok; 








    }

}

?>