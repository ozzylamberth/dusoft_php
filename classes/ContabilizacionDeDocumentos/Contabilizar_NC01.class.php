<?php
/**
* $Id: Contabilizar_NC01.class.php,v 1.2 2008/08/13 14:33:05 hugo Exp $
*/

/**
* Clase para la contabilizacion de documentos de tipo NC01 (NOTAS CREDITO)
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.2 $
* @package SIIS
*/
class Contabilizar_NC01 extends ContabilizarDocumento
{

    /**
    * Datos del Recibo de caja
    *
    * @var array
    * @access private
    */
    var $DatosDocumento;


    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_NC01()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar el documento
    *
    * @param string $empresa_id
    * @param string $centro_utilidad
    * @param integer $recibo_caja
    * @param string $prefijo
    * @param boolean $actualizar   false:IGNORE   true:ACTUALIZAR
    *
    * @return string
    * @access public
    */
    function ContabilizarDoc($empresa_id, $prefijo, $numero, $actualizar=false)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();


       $sql = "
                SELECT
                    a.*,
                    a.nota_credito_id as numero,
                    a.fecha_registro as fecha_documento,
                    'NC01' as tipo_doc_general_id,
                    a.estado as sw_estado

                FROM
                    notas_credito as a

                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.nota_credito_id = $numero
                ";
//echo '<br><br>';
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "NOTAS CREDITO [" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
	//echo '<br><br>';
	if($result->EOF)
	{
			$sql = "
							SELECT
									a.*,
									a.numero as numero,
									a.fecha_registro as fecha_documento,
									a.valor_aceptado as valor_nota,
									'NC01' as tipo_doc_general_id,
									ff.tipo_id_tercero,
									ff.tercero_id,
									g.prefijo as prefijo_factura,
									g.factura_fiscal as factura_fiscal,
									g.glosa_id

							FROM
									notas_credito_glosas as a,
									glosas g, fac_facturas ff

							WHERE
									a.empresa_id = '$empresa_id'
									AND a.prefijo = '$prefijo'
									AND a.numero = $numero
									AND a.glosa_id = g.glosa_id
									AND g.empresa_id = ff.empresa_id
									AND g.prefijo = ff.prefijo
									AND g.factura_fiscal = ff.factura_fiscal
							";
			//echo '<br><br>';
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if($dbconn->ErrorNo() != 0)
			{
					$this->error = "NOTAS CREDITO GLOSAS [" . get_class($this) . "][" . __LINE__ . "]";
					$this->mensajeDeError = $dbconn->ErrorMsg();
					return false;
			}
			
			if($result->EOF)
			{
				//$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
				//$this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL DOCUMENTO (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
				//return false;
					$sql = "
									SELECT
											a.*,
											a.estado AS sw_estado,
											a.nota_credito_ajuste as numero,
											a.fecha_registro as fecha_documento,
											ncdc.valor as valor_nota,
											'NC01' as tipo_doc_general_id,
											ff.tipo_id_tercero,
											ff.tercero_id,
											ncdf.prefijo_factura as prefijo_factura,
											ncdf.factura_fiscal as factura_fiscal
											
									FROM
											notas_credito_ajuste as a,
											notas_credito_ajuste_detalle_conceptos ncdc,
											notas_credito_ajuste_detalle_facturas ncdf,
											fac_facturas ff
									WHERE
											a.empresa_id = '$empresa_id'
											AND a.prefijo = '$prefijo'
											AND a.nota_credito_ajuste = $numero
											AND a.empresa_id = ncdc.empresa_id 
											AND a.nota_credito_ajuste = ncdc.nota_credito_ajuste
											AND a.prefijo = ncdc.prefijo
											AND ncdc.nc_ajuste_concepto_id = ncdf.nc_ajuste_id
											AND ncdf.empresa_id = ff.empresa_id
											AND ncdf.prefijo_factura = ff.prefijo
											AND ncdf.factura_fiscal = ff.factura_fiscal;
									";
	//echo '<br><br>';
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
						$this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL DOCUMENTO (NOTAS CREDITO AJUSTE) (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
						return false;
					}
			}
	}

        unset($this->DatosDocumento);
        $this->DatosDocumento =$result->FetchRow();
        $result->Close();
        $DatosDocumento_tmp = array();
        $DatosDocumento_tmp = $this->DatosDocumento;

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosDocumento)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetDocumento() retorno false";
            }
            return false;
        }
        //CONSULTAR SI EL DOCUMENTO YA ESTA CONTABILIZADO

        $retorno =& $this->ValidarActualizacionDelDocumentoContable($actualizar);
        if($retorno !== null) return $retorno;

       //echo '<pre>';
       $this->DatosDocumento = $DatosDocumento_tmp;

//echo '<pre>';
       //$this->DatosDocumento = $DatosDocumento_tmp;


      if(!empty($this->DatosDocumento['nota_credito_ajuste']))
      {
        // SI EL ESTADO ES DISTINTO DE CERO LO CONTABILIZO COMO UN DOCUMENTO ANULADO
        if($this->DatosDocumento['sw_estado'] != '1')
        {
            if($this->GenerarDocumentoAnulado()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarDocumentoAnulado() retorno false";
                }
                return false;
            }

            //RETORNO LA CONTABILIZACION DEL DOCUMENTO ANULADO
            return $this->RetornarDocumentoContable();
        }
      }

      if(empty($this->DatosDocumento['glosa_id']))
      {
        // SI EL ESTADO ES DISTINTO DE CERO LO CONTABILIZO COMO UN DOCUMENTO ANULADO
        if($this->DatosDocumento['sw_estado'] != '1')
        {
            if($this->GenerarDocumentoAnulado()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarDocumentoAnulado() retorno false";
                }
                return false;
            }

            //RETORNO LA CONTABILIZACION DEL DOCUMENTO ANULADO
            return $this->RetornarDocumentoContable();
        }
      }
			
			if($this->DatosDocumento[glosa_id])
			{
					if($this->Contabilizar_NCG()===false)
					{
							if(empty($this->error))
							{
									$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
									$this->mensajeDeError = "El metodo Contabilizar_NC() retorno false";
							}
							return false;
					}
			}
			elseif(!empty($this->DatosDocumento['nota_credito_ajuste']))
			{
				if($this->Contabilizar_NCA()===false)
				{
						if(empty($this->error))
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = "El metodo Contabilizar_NC() retorno false";
						}
						return false;
				}	
			}
			else
			{
				if($this->Contabilizar_NC()===false)
				{
						if(empty($this->error))
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = "El metodo Contabilizar_NC() retorno false";
						}
						return false;
				}	
			}
			
			if($this->GenerarDocumentoContable()===false)
			{
					if(empty($this->error))
					{
							$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
							$this->mensajeDeError = "El metodo GenerarDocumentoContable() retorno false";
					}
					return false;
			}

			//UNA VEZ TERMINO LA CONTABILIZACION RETORNO EL RESULTADO.
			return $this->RetornarDocumentoContable();

    }//fin de ContabilizarDoc()


    /**
    * Metodo para contabilizar el costo de venta.
    *
    * @return
    * @access private
    */
    function Contabilizar_NC()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE FACTURA (CREDITO, CONTADO, CAPITACION)
        $sql="  SELECT tipo_factura
                FROM   public.fac_facturas as a

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumento['factura_fiscal'].";";

        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El tipo_factura no existe.";
            return false;
        }

        list($tipo_factura)=$resultado->FetchRow();
        $resultado->Close();
//echo '<br><br>';
        //CONTABILIZAR LA PARTIDA ADECUADA (CREDITO)
        //0 => PACIENTE 
        //1 => CLIENTE
        //2 => PARTICULAR
        //3 => AGRUPADA CAPITACION
        //4 => AGRUPADA NO CAPITACION
        //5 => CONCEPTOS 
        //6 => PRODUCTOS INVENTARIO

       switch($tipo_factura)
        {
            case '4':
                if($this->Contabilizar_NC_FacturaCapitacion()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCapitacion() retorno false";
                    }
                    return false;
                }

            break;

            case '1':
                if($this->Contabilizar_NC_FacturaCredito()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCredito() retorno false";
                    }
                    return false;
                }
            break;

            default:

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El tipo_factura de la nota no es valido";
                return false;

        }

        //CONTABILIZACION DE LOS CONCEPTOS (DEBITO)
/*
 echo       $sql = "
                SELECT
                    a.*,
                    b.cuenta

                FROM public.notas_credito_detalle_conceptos as a
                LEFT JOIN cg_conf.doc_nc01_conceptos as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.concepto_id = a.concepto_id
                )

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
                AND a.nota_credito_id = ".$this->DatosDocumento['numero'].";
        ";

echo '<br><br>';

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($FILA = $result->FetchRow())
        {
            if($FILA['valor']>0)
            {
                if(empty($FILA['cuenta']))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "EL PARMETRO PARA VALOR DE LA NOTA CREDITO [".$FILA['prefijo'].$FILA['nota_credito_id']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
                    return false;
                }

                $CC = $this->GetCentroDeCostoDepartamento($FILA['empresa_id'], $FILA['departamento']);

                if($CC===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
                    }

                    return false;
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $FILA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $FILA['costo_total'];
                $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "";

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }

                    return false;
                }

                $this->AddMOV($VectorMOV);


                //CONTABILIZACION DEL INVENTARIO (CREDITO)
                $CUENTA = $this->GetCuentaContableInvProducto($FILA['grupo_id'],$FILA['clase_id'],$FILA['subclase_id'],$FILA['codigo_producto']);

                if($CUENTA===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetCuentaContableInvProducto() retorno false";
                    }

                    return false;
                }

                $Datos['cuenta']             = $CUENTA;
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $FILA['costo_total'];
                $Datos['centro_de_costo_id'] = "";
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "";

    //             if($fila['porcentaje_gravamen']>0)
    //             {
    //                 $Datos['centro_de_operacion_id'] = "90";
    //             }
    //             else
    //             {
    //                 $Datos['centro_de_operacion_id'] = "80";
    //             }

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);


                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }
                    return false;
                }

                $this->AddMOV($VectorMOV);
            }
        }
*/

        return true;
    }


    /**
    * Metodo para contabilizar el costo de venta.
    *
    * @return
    * @access private
    */
    function Contabilizar_NCG()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE FACTURA (CREDITO, CONTADO, CAPITACION)
        $sql="  SELECT tipo_factura
                FROM   public.fac_facturas as a

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumento['factura_fiscal'].";";
        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El tipo_factura no existe.";
            return false;
        }

        list($tipo_factura)=$resultado->FetchRow();
        $resultado->Close();
//echo '<br><br>';
        //CONTABILIZAR LA PARTIDA ADECUADA (CREDITO)
        //0 => PACIENTE 
        //1 => CLIENTE
        //2 => PARTICULAR
        //3 => AGRUPADA CAPITACION
        //4 => AGRUPADA NO CAPITACION
        //5 => CONCEPTOS 
        //6 => PRODUCTOS INVENTARIO

       switch($tipo_factura)
        {
            case '4':
                if($this->Contabilizar_NC_FacturaCapitacion()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCapitacion() retorno false";
                    }
                    return false;
                }

            break;

            case '1':
                if($this->Contabilizar_NC_FacturaCredito()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCredito() retorno false";
                    }
                    return false;
                }
            break;

            case '3':
                if($this->Contabilizar_NC_FacturaAgrupada()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCredito() retorno false";
                    }
                    return false;
                }
            break;

            default:

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El tipo_factura de la nota no es valido";
                return false;

        }

        return true;
    }

    /**
    * Metodo para contabilizar el costo de venta.
    *
    * @return
    * @access private
    */
    function Contabilizar_NCA()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE FACTURA (CREDITO, CONTADO, CAPITACION)
        $sql="  SELECT tipo_factura
                FROM   public.fac_facturas as a

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumento['factura_fiscal'].";";

        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El tipo_factura no existe.";
            return false;
        }

        list($tipo_factura)=$resultado->FetchRow();
        $resultado->Close();
//echo '<br><br>';
        //CONTABILIZAR LA PARTIDA ADECUADA (CREDITO)
        //0 => PACIENTE 
        //1 => CLIENTE
        //2 => PARTICULAR
        //3 => AGRUPADA CAPITACION
        //4 => AGRUPADA NO CAPITACION
        //5 => CONCEPTOS 
        //6 => PRODUCTOS INVENTARIO

       switch($tipo_factura)
        {
            case '4':
                if($this->Contabilizar_NC_FacturaCapitacion()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCapitacion() retorno false";
                    }
                    return false;
                }

            break;

            case '1':
                if($this->Contabilizar_NCA_FacturaCredito()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_NC_FacturaCredito() retorno false";
                    }
                    return false;
                }
            break;

            default:

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El tipo_factura de la nota no es valido";
                return false;

        }
        return true;
    }
		
    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_NC_FacturaCredito()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE CLIENTE DESDE LA CONTRATACION
        $sql="  SELECT tipo_cliente
                FROM
                    public.fac_facturas as a,
                    public.planes as b

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumento['factura_fiscal']."
                AND b.plan_id = a.plan_id;";
//echo '<br><br>';
        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no existe.";
            return false;
        }

        list($tipo_cliente)=$resultado->FetchRow();
        $resultado->Close();

        if(empty($tipo_cliente))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no tiene parametrizado el campo [tipo_cliente].";
            return false;
        }

        $INFO_CTA = $this->GetParametizacionDoc("TIPO_CLIENTE_".$tipo_cliente);

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($this->DatosDocumento['empresa_id'],$this->DatosDocumento['prefijo_factura'],$this->DatosDocumento['factura_fiscal']);

//echo '==>DOC_CRUCE';print_r($DOC_CRUCE); 

//echo '==>DOC_CRUCE';print_r($DOC_CRUCE); exit;

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = "C";
        $Datos['valor']              = $this->DatosDocumento['valor_nota'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "TOTAL NOTA C";
//print_r($Datos);

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);
//print_r($VectorMOV);exit;

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (0) PARA EL REGISTRO DEL TOTAL DEL DOCUMENTO
        if(!$this->AddTmpTransaccion_d(0, 0, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

       $this->AddMOV($VectorMOV);
      
//CONTABILIZACION DEL DEBITO DE LA NOTA
//echo '<br><br>DEBITO>>>';
//
       $sql = "
                SELECT DISTINCT
                    a.*,
                    b.cuenta,
                    b.empresa_id,
                    CASE WHEN a.departamento IS NULL THEN b.departamento 
                      ELSE a.departamento END AS departamento

                FROM public.notas_credito_detalle_conceptos as a
                LEFT JOIN cg_conf.doc_ncnd_conceptos as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.concepto_id = a.concepto_id
		    AND b.departamento = a.departamento
                )

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
                AND a.nota_credito_id  = ".$this->DatosDocumento['numero'].";
        ";

//echo '<br><br>';

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
//
    if($result->EOF)
    {
       $sql = "
                SELECT
                    a.*,
                    b.cuenta,
                    b.empresa_id,
                    b.departamento

                FROM public.notas_credito_ajuste_detalle_conceptos as a
                LEFT JOIN cg_conf.doc_ncnd_conceptos as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.concepto_id = a.concepto_id
                )

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
                AND a.nota_credito_ajuste  = ".$this->DatosDocumento['numero'].";
        ";
//echo '<br><br>';

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

				//CASO NOTAS CREDITO GLOSAS
         if($result->EOF)
				 {
						/*$sql = "SELECT
												a.*,
												a.numero as numero,
												a.glosa_id,
												a.fecha_registro as fecha_documento,
												a.valor_aceptado as valor,
												CASE WHEN GDC.motivo_glosa_id IS NOT NULL THEN GDC.motivo_glosa_id
												WHEN GDCU.motivo_glosa_id IS NOT NULL THEN GDCU.motivo_glosa_id
												WHEN g.motivo_glosa_id IS NOT NULL THEN g.motivo_glosa_id
												WHEN GDI.motivo_glosa_id IS NOT NULL THEN GDI.motivo_glosa_id END AS concepto_id,
												NCGM.cuenta,
												I.departamento
										FROM
												notas_credito_glosas as a,
												glosas g
												LEFT JOIN glosas_detalle_cargos GDC ON (g.glosa_id = GDC.glosa_id)
												LEFT JOIN glosas_detalle_cuentas GDCU ON (g.glosa_id = GDCU.glosa_id)
												LEFT JOIN glosas_detalle_inventarios GDI ON (g.glosa_id = GDI.glosa_id)
												LEFT JOIN cg_conf.doc_nota_credito_glosas_motivos NCGM ON (NCGM.motivo_glosa_id = GDC.motivo_glosa_id 
																																								OR NCGM.motivo_glosa_id = GDCU.motivo_glosa_id
																																								OR NCGM.motivo_glosa_id = GDI.motivo_glosa_id
																																								OR NCGM.motivo_glosa_id = g.motivo_glosa_id),
												fac_facturas_cuentas FFC,
												cuentas C,
												ingresos I
										WHERE
												a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
												AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
												AND a.numero = ".$this->DatosDocumento['numero']."
												AND a.glosa_id = g.glosa_id
												AND FFC.prefijo = g.prefijo
												AND FFC.factura_fiscal = g.factura_fiscal
												AND FFC.numerodecuenta = C.numerodecuenta
												AND C.ingreso = I.ingreso;";*/
            $sql = "SELECT DISTINCT
                            a.*,
                            a.numero as numero,
                            a.glosa_id,
                            a.fecha_registro as fecha_documento,
                            a.valor_aceptado as valor,
			    --x.valor_glosa as valor,
                            x.concepto_id,
                            NCGM.cuenta,
                            I.departamento
                        FROM
                            notas_credito_glosas as a,                
                            (
                              SELECT CASE WHEN GDC.motivo_glosa_id IS NOT NULL THEN GDC.motivo_glosa_id
                                          WHEN GDCU.motivo_glosa_id IS NOT NULL THEN GDCU.motivo_glosa_id
                                          WHEN g.motivo_glosa_id IS NOT NULL THEN g.motivo_glosa_id
                                          WHEN GDI.motivo_glosa_id IS NOT NULL THEN GDI.motivo_glosa_id END AS concepto_id,
                                      g.prefijo,
                                      g.factura_fiscal,
                                      g.empresa_id,
                                      g.glosa_id,
                                      CASE WHEN GDC.valor_glosa IS NOT NULL THEN GDC.valor_glosa
                                          WHEN GDCU.valor_glosa IS NOT NULL THEN GDCU.valor_glosa
                                          WHEN GDI.valor_glosa IS NOT NULL THEN GDI.valor_glosa END AS valor_glosa 
                              FROM  glosas g
                              			LEFT JOIN glosas_detalle_cargos GDC ON (g.glosa_id = GDC.glosa_id)
                              			LEFT JOIN glosas_detalle_cuentas GDCU ON (g.glosa_id = GDCU.glosa_id)
                              			LEFT JOIN glosas_detalle_inventarios GDI ON (g.glosa_id = GDI.glosa_id)
                            ) AS x
                            LEFT JOIN cg_conf.doc_nota_credito_glosas_motivos NCGM 
                            ON (NCGM.motivo_glosa_id = x.concepto_id),
                            fac_facturas_cuentas FFC,
                            cuentas C,
                            ingresos I
                        WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
												AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
												AND a.numero = ".$this->DatosDocumento['numero']."
                        AND a.glosa_id = x.glosa_id
                        AND FFC.prefijo = x.prefijo
                        AND FFC.empresa_id = x.empresa_id
                        AND FFC.factura_fiscal = x.factura_fiscal
                        AND FFC.numerodecuenta = C.numerodecuenta
                        AND C.ingreso = I.ingreso;";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$result = $dbconn->Execute($sql);
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
						if($dbconn->ErrorNo() != 0)
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								return false;
						}
				 }
				//CASO NOTAS CREDITO GLOSAS
			}

				//while($FILA = $result->FetchRow())
				while($FILA = $result->FetchRow())
				{
						if($FILA['valor']>0)
						{
								if(empty($FILA['cuenta']))
								{
										$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
										$this->mensajeDeError = "EL PARMETRO PARA VALOR DE LA NOTA CREDITO [".$FILA['prefijo'].$FILA['numero']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
										return false;
								}

               //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
								$CC = $this->GetCentroDeCostoDepartamento($FILA['empresa_id'], $FILA['departamento']);
								if($CC===false)
								{
										if(empty($this->error))
										{
												$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
												$this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
										}
	
										return false;
								}
 
								//DATOS DEL MOVIMIENTO
								$Datos['cuenta']             = $FILA['cuenta'];
								$Datos['naturaleza']         = 'D';
								$Datos['valor']              = $FILA['valor'];
								$Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
								$Datos['documento_cruce_id'] = -1;
								$Datos['base_rtf']           = 0;
								$Datos['porcentaje_rtf']     = 0;
								$Datos['tipo_id_tercero']    = "";
								$Datos['tercero_id']         = "";
								$Datos['detalle']            = "TOTAL NOTA D";
//print_r($Datos); exit;
								$VectorMOV = $this->GenerarVectorMovimiento($Datos);
// echo '<br><br><br>';
// print_r($VectorMOV);exit;

								if($VectorMOV===false)
								{
										if(empty($this->error))
										{
												$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
												$this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
										}

										return false;
								}

								$this->AddMOV($VectorMOV);
						}
				}

//FIN CONTABILIZACION DEL DEBITO DE LA NOTA 

    }

    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_NCA_FacturaCredito()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE CLIENTE DESDE LA CONTRATACION
        $sql="  SELECT tipo_cliente
                FROM
                    public.fac_facturas as a,
                    public.planes as b

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumento['factura_fiscal']."
                AND b.plan_id = a.plan_id;";
//echo '<br><br>';
        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no existe.";
            return false;
        }

        list($tipo_cliente)=$resultado->FetchRow();
        $resultado->Close();

        if(empty($tipo_cliente))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no tiene parametrizado el campo [tipo_cliente].";
            return false;
        }

        $INFO_CTA = $this->GetParametizacionDoc("TIPO_CLIENTE_".$tipo_cliente);

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }
//print_r($this->DatosDocumento);
        $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($this->DatosDocumento['empresa_id'],$this->DatosDocumento['prefijo_factura'],$this->DatosDocumento['factura_fiscal']);
//echo '==>DOC_CRUCE';print_r($DOC_CRUCE); 
        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = "C";
        $Datos['valor']              = $this->DatosDocumento['valor_nota'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "TOTAL NOTA C";

//print_r($Datos);
        $VectorMOV = $this->GenerarVectorMovimiento($Datos);
//print_r($VectorMOV);exit;

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (0) PARA EL REGISTRO DEL TOTAL DEL DOCUMENTO
        if(!$this->AddTmpTransaccion_d(0, 0, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

       $this->AddMOV($VectorMOV);
      
//CONTABILIZACION DEL DEBITO DE LA NOTA
//echo '<br><br>DEBITO>>>';
       $sql = "
                SELECT
                    a.*,
                    b.cuenta,
                    b.empresa_id,
                    b.departamento

                FROM public.notas_credito_ajuste_detalle_conceptos as a
                LEFT JOIN cg_conf.doc_notas_creditos_ajuste as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.concepto_id = a.concepto_id
                )

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
                AND a.nota_credito_ajuste = ".$this->DatosDocumento['numero'].";
        ";
//echo '<br><br>';

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
					$this->error = "EL CONCEPTO DE LA FACTURA [".$this->DatosDocumento['prefijo'].$this->DatosDocumento['numero']."] NO ESTA EN LA TABLA cg_conf.doc_notas_creditos_ajuste [" . get_class($this) . "][" . __LINE__ . "]";
					return false;
				}
				//CASO NOTAS CREDITO GLOSAS
/*         if($result->EOF)
				 {
		echo    $sql = "SELECT
												a.*,
												a.numero as numero,
												a.glosa_id,
												a.fecha_registro as fecha_documento,
												a.valor_aceptado as valor,
												CASE WHEN GDC.motivo_glosa_id IS NOT NULL THEN GDC.motivo_glosa_id
												WHEN GDCU.motivo_glosa_id IS NOT NULL THEN GDCU.motivo_glosa_id
												WHEN GDI.motivo_glosa_id IS NOT NULL THEN GDI.motivo_glosa_id END AS concepto_id,
												NCGM.cuenta,
												I.departamento
										FROM
												notas_credito_glosas as a,
												glosas g
												LEFT JOIN glosas_detalle_cargos GDC ON (g.glosa_id = GDC.glosa_id)
												LEFT JOIN glosas_detalle_cuentas GDCU ON (g.glosa_id = GDCU.glosa_id)
												LEFT JOIN glosas_detalle_inventarios GDI ON (g.glosa_id = GDI.glosa_id)
												LEFT JOIN cg_conf.doc_nota_credito_glosas_motivos NCGM ON (NCGM.motivo_glosa_id = GDC.motivo_glosa_id 
																																								OR NCGM.motivo_glosa_id = GDCU.motivo_glosa_id
																																								OR NCGM.motivo_glosa_id = GDI.motivo_glosa_id),
												fac_facturas_cuentas FFC,
												cuentas C,
												ingresos I
										WHERE
												a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
												AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
												AND a.numero = ".$this->DatosDocumento['numero']."
												AND a.glosa_id = g.glosa_id
												AND FFC.prefijo = g.prefijo
												AND FFC.factura_fiscal = g.factura_fiscal
												AND FFC.numerodecuenta = C.numerodecuenta
												AND C.ingreso = I.ingreso;";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$result = $dbconn->Execute($sql);
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
						if($dbconn->ErrorNo() != 0)
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								return false;
						}
				 }*/
				//CASO NOTAS CREDITO GLOSAS

				while($FILA = $result->FetchRow())
				{
						if($FILA['valor']>0)
						{
								if(empty($FILA['cuenta']))
								{
										$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
										$this->mensajeDeError = "EL PARMETRO PARA VALOR DE LA NOTA CREDITO [".$FILA['prefijo'].$FILA['numero']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
										return false;
								}

               //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
								$CC = $this->GetCentroDeCostoDepartamento($FILA['empresa_id'], $FILA['departamento']);
								if($CC===false)
								{
										if(empty($this->error))
										{
												$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
												$this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
										}
	
										return false;
								}
 
								//DATOS DEL MOVIMIENTO
								$Datos['cuenta']             = $FILA['cuenta'];
								$Datos['naturaleza']         = 'D';
								$Datos['valor']              = $FILA['valor'];
								$Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
								$Datos['documento_cruce_id'] = -1;
								$Datos['base_rtf']           = 0;
								$Datos['porcentaje_rtf']     = 0;
								$Datos['tipo_id_tercero']    = "";
								$Datos['tercero_id']         = "";
								$Datos['detalle']            = "TOTAL NOTA D";
//print_r($Datos); exit;
								$VectorMOV = $this->GenerarVectorMovimiento($Datos);
// echo '<br><br><br>';
// print_r($VectorMOV);exit;

								if($VectorMOV===false)
								{
										if(empty($this->error))
										{
												$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
												$this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
										}

										return false;
								}

								$this->AddMOV($VectorMOV);
						}
				}

//FIN CONTABILIZACION DEL DEBITO DE LA NOTA 

    }

    /**
    * Metodo para insertar en la tabla temporal un error en la contabilizacion de una transaccion.
    *
    * @param integer $NumeroDeCuenta
    * @param integer $transaccion
    * @param boolean $error TRUE = Contabilizacion OK,  FALSE = error.
    * @return boolean
    * @access private
    */
    function AddTmpTransaccion_d($NumeroDeCuenta, $transaccion, $Datos)
    {
        list($dbconn) = GetDBconn();

        if(empty($Datos['documento_cruce_id']))
        {
            $documento_cruce_id = "NULL";
        }
        else
        {
            $documento_cruce_id = $Datos['documento_cruce_id'];
        }

        if(empty($Datos['tipo_id_tercero']))
        {
            $tipo_id_tercero = "NULL";
        }
        else
        {
            $tipo_id_tercero = "'".$Datos['tipo_id_tercero']."'";
        }

        if(empty($Datos['tercero_id']))
        {
            $tercero_id = "NULL";
        }
        else
        {
            $tercero_id = "'".$Datos['tercero_id']."'";
        }

        if(empty($Datos['centro_de_costo_id']))
        {
            $centro_de_costo_id = "NULL";
        }
        else
        {
            $centro_de_costo_id = "'".$Datos['centro_de_costo_id']."'";
        }

        if(empty($Datos['paquete_codigo_id']))
        {
            $paquete_id = "NULL";
            $paquete_sw = '0';
        }
        else
        {
            $paquete_id = $Datos['paquete_codigo_id'];
            $paquete_sw = $Datos['sw_paquete_facturado'];
        }



        $sql = "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero']."
                     AND numerodecuenta = $NumeroDeCuenta
                     AND transaccion = $transaccion
                     AND cuenta = '".$Datos['cuenta']."';

                INSERT INTO cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                (
                    empresa_id,
                    prefijo,
                    numero,
                    numerodecuenta,
                    transaccion,
                    documento_cruce_id,
                    cuenta,
                    tipo_id_tercero,
                    tercero_id,
                    debito,
                    credito,
                    detalle,
                    centro_de_costo_id,
                    base_rtf,
                    porcentaje_rtf,
                    paquete_id,
                    paquete_sw
                )
                VALUES
                (
                    '".$this->DatosDocumento['empresa_id']."',
                    '".$this->DatosDocumento['prefijo']."',
                    ".$this->DatosDocumento['numero'].",
                    $NumeroDeCuenta,
                    $transaccion,
                    $documento_cruce_id,
                    '".$Datos['cuenta']."',
                    $tipo_id_tercero,
                    $tercero_id,
                    ".$Datos['debito'].",
                    ".$Datos['credito'].",
                    '".$Datos['detalle']."',
                    $centro_de_costo_id,
                    ".$Datos['base_rtf'].",
                    ".$Datos['porcentaje_rtf'].",
                    $paquete_id,
                    $paquete_sw
                );
        ";
//echo $sql; 
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "<pre>$sql</pre>" . $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }


    /**
    * Metodo para borrar los datos de la contabilizacion de una factura en las tablas temporales.
    *
    * @return boolean
    * @access private
    */
    function DelTemporalesFacturacion()
    {
        list($dbconn) = GetDBconn();

        $sql= "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";

               DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";

               DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";
               ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }


    /**
    * Metodo que retorna un listado de errores unificado de la contabilizacion de una factura.
    *
    * @return boolean
    * @access private
    */
    function RetornarResumenErrores()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER LOS TEMPORALES DE CUENTAS
        $sql= "SELECT * FROM cg_conf.tmp_contabilizacion_facturas_cuentas
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";
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

        $DATOS_CUENTAS = array();
        while($fila = $result->FetchRow())
        {
            $DATOS_CUENTAS[$fila['numerodecuenta']] = $fila;
        }

        $result->Close();

        //OBTENER LOS TEMPORALES DE CARGOS
        $sql= "SELECT * FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";
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

        $DATOS_CARGOS = array();
        while($fila = $result->FetchRow())
        {
            $DATOS_CARGOS[$fila['numerodecuenta']][$fila['transaccion']] = $fila;
        }

        $result->Close();

        $salida = "<PRE>\n";

        foreach($DATOS_CUENTAS as $NumCuenta=>$DatosCuenta)
        {
            if($DatosCuenta['sw_estado']==='0')
            {
                $salida .= "CUENTA No.$NumCuenta \n";
                $salida .= "    MENSAJE : ".$DatosCuenta['error_titulo']." \n";
                $salida .= "    DETALLE : ".$DatosCuenta['error_detalle']." \n";
                $salida .= "\n";

                foreach($DATOS_CARGOS[$NumCuenta] as $NumTransaccion=>$DatosTransaccion)
                {
                    if($DatosTransaccion['sw_estado']==='0')
                    {
                        $salida .= "    TRANSACCION No.$NumTransaccion \n";
                        $salida .= "    MENSAJE : ".$DatosTransaccion['error_titulo']." \n";
                        $salida .= "    DETALLE : ".$DatosTransaccion['error_detalle']." \n";
                        $salida .= "\n";
                    }
                }
            }
        }

        $salida .= "</PRE>\n";

        return $salida;
    }

    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_NC_FacturaCapitacion()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "El metodo No definido Contabilizar_NC_FacturaCapitacion() retorno false";
        return false;

        //CONTABILIZACION DE APROVECHAMIENTO O PERDIDAS SI LA FACTURA ES DE CAPITACION
        if($this->DatosFactura['tipo_factura'] == '3')
        {
            if($this->Contabilizar_Capitacion()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_Capitacion() retorno false";
                }

                return false;
            }
        }

        return true;
    }

    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito AGRUPADA CAPITACION.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_NC_FacturaAgrupada()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE CLIENTE DESDE LA CONTRATACION
        $sql="  SELECT tipo_cliente
                FROM
                    public.fac_facturas as a,
                    public.planes as b

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumento['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumento['factura_fiscal']."
                AND b.plan_id = a.plan_id;";
//echo '<br><br>';
        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no existe.";
            return false;
        }

        list($tipo_cliente)=$resultado->FetchRow();
        $resultado->Close();

        if(empty($tipo_cliente))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no tiene parametrizado el campo [tipo_cliente].";
            return false;
        }

        $INFO_CTA = $this->GetParametizacionDoc("TIPO_CLIENTE_".$tipo_cliente);

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

//print_r($this->DatosDocumento); 
        $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($this->DatosDocumento['empresa_id'],$this->DatosDocumento['prefijo_factura'],$this->DatosDocumento['factura_fiscal']);
//echo '==>DOC_CRUCE';print_r($DOC_CRUCE); 
        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = "C";
        $Datos['valor']              = $this->DatosDocumento['valor_nota'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "TOTAL NOTA C";
//print_r($Datos);

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (0) PARA EL REGISTRO DEL TOTAL DEL DOCUMENTO
        if(!$this->AddTmpTransaccion_d(0, 0, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

       $this->AddMOV($VectorMOV);
      

				//CASO NOTAS CREDITO GLOSAS
						$sql = "SELECT
												a.*,
												a.numero as numero,
												a.glosa_id,
												a.fecha_registro as fecha_documento,
												a.valor_aceptado as valor,
												CASE WHEN GDC.motivo_glosa_id IS NOT NULL THEN GDC.motivo_glosa_id
												WHEN GDCU.motivo_glosa_id IS NOT NULL THEN GDCU.motivo_glosa_id
												WHEN GDI.motivo_glosa_id IS NOT NULL THEN GDI.motivo_glosa_id END AS concepto_id,
												NCGM.cuenta,
												I.departamento
										FROM
												notas_credito_glosas as a,
												glosas g
												LEFT JOIN glosas_detalle_cargos GDC ON (g.glosa_id = GDC.glosa_id)
												LEFT JOIN glosas_detalle_cuentas GDCU ON (g.glosa_id = GDCU.glosa_id)
												LEFT JOIN glosas_detalle_inventarios GDI ON (g.glosa_id = GDI.glosa_id)
												LEFT JOIN cg_conf.doc_nota_credito_glosas_motivos NCGM ON (NCGM.motivo_glosa_id = GDC.motivo_glosa_id 
																																								OR NCGM.motivo_glosa_id = GDCU.motivo_glosa_id
																																								OR NCGM.motivo_glosa_id = GDI.motivo_glosa_id),
												fac_facturas_cuentas FFC,
												cuentas C,
												ingresos I
										WHERE
												a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
												AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
												AND a.numero = ".$this->DatosDocumento['numero']."
												AND a.glosa_id = g.glosa_id
												AND FFC.prefijo = g.prefijo
												AND FFC.factura_fiscal = g.factura_fiscal
												AND FFC.numerodecuenta = C.numerodecuenta
												AND C.ingreso = I.ingreso;";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$result = $dbconn->Execute($sql);
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
						if($dbconn->ErrorNo() != 0)
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								return false;
						}
				//CASO NOTAS CREDITO GLOSAS

				//while($FILA = $result->FetchRow())
				if($FILA = $result->FetchRow())
				{
						if($FILA['valor']>0)
						{
								if(empty($FILA['cuenta']))
								{
										$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
										$this->mensajeDeError = "EL PARMETRO PARA VALOR DE LA NOTA CREDITO [".$FILA['prefijo'].$FILA['numero']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
										return false;
								}

               //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
								$CC = $this->GetCentroDeCostoDepartamento($FILA['empresa_id'], $FILA['departamento']);
								if($CC===false)
								{
										if(empty($this->error))
										{
												$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
												$this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
										}
	
										return false;
								}
 
								//DATOS DEL MOVIMIENTO
								$Datos['cuenta']             = $FILA['cuenta'];
								$Datos['naturaleza']         = 'D';
								$Datos['valor']              = $FILA['valor'];
								$Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
								$Datos['documento_cruce_id'] = -1;
								$Datos['base_rtf']           = 0;
								$Datos['porcentaje_rtf']     = 0;
								$Datos['tipo_id_tercero']    = "";
								$Datos['tercero_id']         = "";
								$Datos['detalle']            = "TOTAL NOTA D";
//print_r($Datos);
								$VectorMOV = $this->GenerarVectorMovimiento($Datos);
// echo '<br><br>';
// print_r($VectorMOV);

								if($VectorMOV===false)
								{
										if(empty($this->error))
										{
												$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
												$this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
										}

										return false;
								}

								$this->AddMOV($VectorMOV);
						}
				}

//FIN CONTABILIZACION DEL DEBITO DE LA NOTA 
    }
}//fin de la clase