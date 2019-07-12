<?php

/**
 * $Id: despacho_medicamentos.inc.php,v 1.22 2006/10/27 00:09:22 alex Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function DocumentoDespachoMedicamentos(){

    $cuenta=$_SESSION['DESPACHO']['MEDICAMENTOS']['CUENTA'];
    $solicitud=$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD'];
        $PlanId=$_SESSION['DESPACHO']['MEDICAMENTOS']['PLAN'];
        //LA VARIABLE PLAN NO LLEGO
    if(!$_SESSION['DESPACHO']['MEDICAMENTOS']['PLAN']){
      $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=1;
            $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='NO EXISTE LA VARIABLE PLAN';
            return true;
        }
        if(!$_SESSION['DESPACHO']['MEDICAMENTOS']['CUENTA']){
      $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=1;
            $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='NO EXISTE LA VARIABLE CUENTA';
            return true;
        }
        //LA VARIABLE SOLICITUD NO LLEGO
        if(!$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']){
      $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=1;
            $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='NO EXISTE LA VARIABLE SOLICTUD';
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="SELECT a.ingreso,b.departamento,c.empresa_id,c.centro_utilidad,c.servicio,a.tipo_solicitud
        FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,departamentos c
        WHERE a.solicitud_id='$solicitud' AND a.estacion_id=b.estacion_id AND b.departamento=c.departamento";
        //$query="SELECT a.departamento,b.descripcion FROM estaciones_enfermeria a,departamentos b WHERE a.estacion_id='".$_REQUEST['EstacionId']."' AND a.departamento=b.departamento";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
        $vars=$result->GetRowAssoc($toUpper=false);
            }else{
              //ERROR EN LA SOLICITUD
        $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=1;
                $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='ERROR EN LA SELECCION DE LA SOLICITUD DE LA BASE DE DATOS';
                return true;
            }
        }
        $TipoSolicitud=$vars['tipo_solicitud'];
        $query="SELECT b.bodegas_doc_id,c.empresa_id,c.centro_utilidad,c.bodega
        FROM hc_solicitudes_medicamentos a,bodegas_documento_despacho_med b,bodegas_doc_numeraciones c
        WHERE a.solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."' AND
        a.documento_despacho=b.documento_despacho_id AND b.bodegas_doc_id=c.bodegas_doc_id";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
        $datosresut=$result->GetRowAssoc($toUpper=false);
            }
        }
        $EmpresaDespacho=$datosresut['empresa_id'];
        $CentroUDespacho=$datosresut['centro_utilidad'];
        $BodegaDespacho=$datosresut['bodega'];
        $concepto=$datosresut['bodegas_doc_id'];
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $query = "INSERT INTO bodegas_documentos(bodegas_doc_id,numeracion,fecha,
                                                            total_costo,transaccion,observacion,
                                                            usuario_id,fecha_registro)
                            SELECT $concepto,$numeracion,b.fecha,
                                  b.total_costo,NULL,b.observacion,
                                        b.usuario_id,b.fecha_registro
                            FROM hc_solicitudes_medicamentos a,bodegas_documento_despacho_med b
                            WHERE a.solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."' AND
                        a.documento_despacho=b.documento_despacho_id";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false);
            return false;
        }else{
            $query = "SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq')";
            $result = $dbconn->Execute($query);
            $codigoAgrupamiento=$result->fields[0];
            $query = "INSERT INTO cuentas_codigos_agrupamiento(codigo_agrupamiento_id,
                                                                                                                descripcion,
                                                                                                                bodegas_doc_id,
                                                                                                                numeracion)
                                                                                                                VALUES('$codigoAgrupamiento',
                                                                                                                'DESCARGO DE MEDICAMENTOS',
                                                                                                                '$concepto',
                                                                                                                '$numeracion')";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumentoDespa($commit=false);
                return false;
            }else{
              if($TipoSolicitud!='I'){
          $query = "SELECT b.codigo_producto,b.cantidad,b.total_costo,c.evolucion_id
                          FROM hc_solicitudes_medicamentos a,bodegas_documento_despacho_med_d b,hc_solicitudes_medicamentos_d c
                                WHERE a.solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."' AND
                      a.documento_despacho=b.documento_despacho_id AND b.consecutivo_solicitud=c.consecutivo_d";
                }else{
          $query = "SELECT b.codigo_producto,b.cantidad,b.total_costo
                          FROM hc_solicitudes_medicamentos a,bodegas_documento_despacho_ins_d b
                                WHERE a.solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."' AND
                      a.documento_despacho=b.documento_despacho_id ";
                }
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    GuardarNumeroDocumentoDespa($commit=false);
                    return false;
                }else{
                    $datos=$result->RecordCount();
                    if($datos){
            while(!$result->EOF) {
                        $productos[]=$result->GetRowAssoc($toUpper=false);
                        $result->MoveNext();
                        }
                    }
                    for($i=0;$i<sizeof($productos);$i++){
            $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                        $result = $dbconn->Execute($query);
            $consec=$result->fields[0];
            $query = "INSERT INTO bodegas_documentos_d(consecutivo,codigo_producto,
                                                                                            cantidad,total_costo,bodegas_doc_id,
                                                                                            numeracion)VALUES(
                                                                  $consec,'".$productos[$i]['codigo_producto']."',
                                                                  '".$productos[$i]['cantidad']."','".$productos[$i]['total_costo']."',
                                                                          '$concepto','$numeracion')";

                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            GuardarNumeroDocumentoDespa($commit=false);
                            return false;
                        }else{
              if(LiquidacionDespachoMed($consec,date('Y-m-d H:i:s'),$_SESSION['DESPACHO']['MEDICAMENTOS']['CUENTA'],
                                $productos[$i]['codigo_producto'],$productos[$i]['cantidad'],'precio',$codigoAgrupamiento,$PlanId,$vars['servicio'],
                                $vars['empresa_id'],$vars['centro_utilidad'],$vars['departamento'],$productos[$i]['evolucion_id'],'0','IMD')==false){
                $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=1;
                                $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='ERROR AL LIQUIDAR A LA CUENTA';
                                return true;
                            }
                            $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$EmpresaDespacho' AND
                            centro_utilidad='$CentroUDespacho' AND bodega='$BodegaDespacho' AND codigo_producto='".$productos[$i]['codigo_producto']."'";
                            $result = $dbconn->Execute($query);
                            $Existencias=$result->fields[0];
                            if(($Existencias-$productos[$i]['cantidad'])<0){
                $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=1;
                                $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='NO HAY SUFICIENTES EXISTENCIAS EN LA BODEGA PARA DESPACHAR LOS PRODUCTOS';
                                return true;
                            }
                            $ModifExist=ModificacionExistenciasBodega($Existencias,$productos[$i]['cantidad'],
                            $EmpresaDespacho,$CentroUDespacho,$BodegaDespacho,$productos[$i]['codigo_producto']);
                            if($result->fields[1]=='1'){
                                DescargarLotesBodega($EmpresaDespacho,$CentroUDespacho,$BodegaDespacho,
                                $productos[$i]['codigo_producto'],$productos[$i]['cantidad']);
                            }
                            /*$query="INSERT INTO hc_medicamento_bodega_paciente(ingreso,medicamento_id,
                            cantidad,fecha_registro)VALUES('".$vars['ingreso']."',
                            '".$productos[$i]['codigo_producto']."','".$productos[$i]['cantidad']."','".date('Y-m-d H:i:s')."')";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Guardar en la Base de Datos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                GuardarNumeroDocumentoDespa($commit=false);
                                return false;
                            }*/
                        }
                    }
                    if($TipoSolicitud!='I'){
                      $query1="SELECT a.documento_despacho FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d b
                        WHERE a.solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."' AND a.sw_estado='1' AND a.solicitud_id=b.solicitud_id";
                    }else{
            $query1="SELECT a.documento_despacho FROM hc_solicitudes_medicamentos a,hc_solicitudes_insumos_d b
                        WHERE a.solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."' AND a.sw_estado='1' AND a.solicitud_id=b.solicitud_id";
                    }
                    $result = $dbconn->Execute($query1);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        GuardarNumeroDocumentoDespa($commit=false);
                        return false;
                    }
                    $Arr_solicitud[]=$result->GetRowAssoc($toUpper=false);
                    $conteo_solic=$result->RecordCount();
                    if($TipoSolicitud!='I'){
                    $query2="SELECT COUNT(*) FROM bodegas_documento_despacho_med_d a,bodegas_documento_despacho_med b
                    WHERE a.documento_despacho_id=b.documento_despacho_id
                    AND a.documento_despacho_id='".$Arr_solicitud[0][documento_despacho]."'";
                    }else{
          $query2="SELECT COUNT(*) FROM bodegas_documento_despacho_ins_d a,bodegas_documento_despacho_med b
                    WHERE a.documento_despacho_id=b.documento_despacho_id
                    AND a.documento_despacho_id='".$Arr_solicitud[0][documento_despacho]."'";
                    }
                    $result = $dbconn->Execute($query2);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        GuardarNumeroDocumentoDespa($commit=false);
                        return false;
                    }
                    $conteo_desp=$result->fields[0];
                    if($conteo_desp==$conteo_solic){
                            $query="UPDATE hc_solicitudes_medicamentos
                            SET sw_estado='2',bodegas_doc_id='$concepto',numeracion='$numeracion'
                            WHERE solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."'";
                    }else{
                $query="UPDATE hc_solicitudes_medicamentos
                            SET sw_estado='5',bodegas_doc_id='$concepto',numeracion='$numeracion'
                            WHERE solicitud_id='".$_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']."'";
                    }
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        GuardarNumeroDocumentoDespa($commit=false);
                        return false;
                    }
                }
            }
        }
        GuardarNumeroDocumentoDespa($commit=true);
    $_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']=4;
        $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje']='DATOS GUARDADOS CORRECTAMENTE';
        //$this->ReturnMetodoExterno($_SESSION['INVENTARIOS']['RETORNO']['contenedor'],$_SESSION['INVENTARIOS']['RETORNO']['modulo'],$_SESSION['INVENTARIOS']['RETORNO']['tipo'],$_SESSION['INVENTARIOS']['RETORNO']['metodo'],$_SESSION['INVENTARIOS']['RETORNO']['argurmentos']);
        return true;        //fin por cada solicitud
    }//fin functionUpdateX

    //esta funcion de asignar numero de documento es de entregar una numeracion
    //segun el tipo de doc(caja,etc..) en factura o recibos de caja.
    function AsignarNumeroDocumentoDespacho($concepto,&$dbconn){
        if(!is_object($dbconn)){
            list($dbconn) = GetDBconn();
        }
        if((!empty($concepto))){
            $sql="BEGIN WORK;  LOCK TABLE bodegas_doc_numeraciones IN ROW EXCLUSIVE MODE";
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0){
                die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
                return false;
            }
            $sql="UPDATE bodegas_doc_numeraciones set numeracion=numeracion + 1
                        WHERE  bodegas_doc_id= $concepto";
            $result = $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0){
                die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
                return false;
            }
            if($dbconn->Affected_Rows() == 0){
                die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$concepto' no existe."));
                return false;
            }
            $sql="SELECT numeracion FROM bodegas_doc_numeraciones WHERE bodegas_doc_id=$concepto";
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0){
                die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
                return false;
            }
            if($result->EOF){
                die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$concepto' no existe."));
                return false;
            }
            list($numerodoc['numeracion'])=$result->fetchRow();
            return $numerodoc;
        }
    }

    function GuardarNumeroDocumentoDespa($commit=true,&$dbconn){
        if(!is_object($dbconn)){
            list($dbconn) = GetDBconn();
        }
        if($commit){
            $sql="COMMIT;";
        }else{
            $sql="ROLLBACK;";
        }
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            die(MsgOut("Error al terminar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
            return false;
        }
        return true;
    }

//  function GuardarNumeroDocumentoDespa($commit=true,&$dbconn){
//      if(!is_object($dbconn)){
//          echo 'CREE NUEVO';
//          list($dbconn) = GetDBconn();
//      }
//      if($commit)
//      {
//          //$sql="CommitTrans();";
//          $dbconn->CommitTrans();
//      }
//      else
//      {
//          $dbconn->RollbackTrans();
//          //$sql="RollbackTrans();";
//      }
//
//      //$result = $dbconn->Execute($sql);
//      if ($dbconn->ErrorNo() != 0) {
//          die(MsgOut("Error al terminar la transaccion","Error DB : " . $dbconn->ErrorMsg().'linea'.$this->lineError));
//          return false;
//      }
//      return true;
//  }

    /**
* Funcion que inserta y calcula los valore del cargos del medicamento o insumo
* @return array
* @param string codigo unico que el identifica el registro de insercion del medicamento o insumo
*/
  function LiquidacionDespachoMed($Consecutivo,$fechaCargo,$cuenta,$codigo,$cantidad,$precio,$codigoAgrupamiento,$planId,$Servicio,$Empresa,$CentroUtili,$departamento,$evolucion_id,$devolucion,$tipoCargo,&$dbconn){

      IncludeLib("tarifario_cargos");
      if(empty($Consecutivo)){
      $Consecutivo=$_REQUEST['Consecutivo'];
        }
        if(!is_object($dbconn)){
            list($dbconn) = GetDBconn();
        }
        $varsCuenDet=LiquidarIyM($cuenta,$codigo,$cantidad,$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$departamento,$Empresa,$evolucion_id,&$dbconn);
        $autorizacion_int=$varsCuenDet['autorizacion_int'];
        if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
        $autorizacion_ext=$varsCuenDet['autorizacion_ext'];
        if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}
        $query="SELECT nextval('cuentas_detalle_transaccion_seq')";
        $result=$dbconn->Execute($query);
        $Transaccion=$result->fields[0];
        if($devolucion=='1'){
          $valor_cargo=($varsCuenDet['valor_cargo']*-1);
            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
        }else{
      $valor_cargo=$varsCuenDet['valor_cargo'];
            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
            $valor_cubierto=$varsCuenDet['valor_cubierto'];
        }
        if(empty($tipoCargo)){
      $tipoCargo='IMD';
        }
        $query = "INSERT INTO cuentas_detalle(transaccion,empresa_id,centro_utilidad,
                    numerodecuenta,departamento,tarifario_id,
                    cargo,cantidad,precio,
                    porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                    valor_cubierto,facturado,fecha_cargo,
                    usuario_id,fecha_registro,sw_liq_manual,
                    valor_descuento_empresa,valor_descuento_paciente,porcentaje_descuento_paciente,
                    servicio_cargo,autorizacion_int,autorizacion_ext,
                    porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
                    codigo_agrupamiento_id,consecutivo,cargo_cups,sw_cargue,departamento_al_cargar)VALUES
                    ('$Transaccion','$Empresa','$CentroUtili',
                    $cuenta,'$departamento','SYS',
                    '$tipoCargo','$cantidad','".$varsCuenDet['precio_plan']."',
                    '".$varsCuenDet['porcentaje_descuento_empresa']."','".$valor_cargo."','".$valor_nocubierto."',
                    '".$valor_cubierto."','".$varsCuenDet['facturado']."','$fechaCargo',
                    '".UserGetUID()."','".date('Y/m/d H:i:s')."','0',
                    '".$varsCuenDet['valor_descuento_empresa']."','".$varsCuenDet['valor_descuento_paciente']."','".$varsCuenDet['porcentaje_descuento_paciente']."',
                    '$Servicio',$autorizacion_int1,$autorizacion_ext1,
                    '".$varsCuenDet['porcentaje_gravamen']."','".$varsCuenDet['sw_cuota_paciente']."','".$varsCuenDet['sw_cuota_moderadora']."',
                    '$codigoAgrupamiento','$Consecutivo',NULL,'3','$departamento')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
            return false;
        }else{
          $query = "SELECT a.transaccion,a.cargo,a.cantidad,a.departamento_al_cargar
            FROM cuentas_detalle a, bodegas_documentos_d b
            WHERE a.numerodecuenta='$cuenta' AND a.consecutivo=b.consecutivo AND
            b.codigo_producto='$codigo' AND a.consecutivo <> '$Consecutivo' AND a.sw_liq_manual='0'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                return false;
            }else{
        $datos=$result->RecordCount();
                if($datos){
                    $i=0;
                    while(!$result->EOF){
            $vars[$i]=$result->GetRowAssoc($toUpper=false);
                        $varsCuenDet=LiquidarIyM($cuenta,$codigo,$vars[$i]['cantidad'],$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$vars[$i]['departamento_al_cargar'],$Empresa,$evolucion_id,&$dbconn);
                        if($vars[$i]['cargo']=='DIMD'){
              $valor_cargo=($varsCuenDet['valor_cargo']*-1);
                            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
                            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
                        }else{
              $valor_cargo=$varsCuenDet['valor_cargo'];
                            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
              $valor_cubierto=$varsCuenDet['valor_cubierto'];
                        }
                        $query = "UPDATE cuentas_detalle
                        SET precio='".$varsCuenDet['precio_plan']."',
                        porcentaje_descuento_empresa='".$varsCuenDet['porcentaje_descuento_empresa']."',
                        valor_cargo='".$valor_cargo."',valor_nocubierto='".$valor_nocubierto."',
                        valor_cubierto='".$valor_cubierto."',
                        facturado='".$varsCuenDet['facturado']."',valor_descuento_empresa='".$varsCuenDet['valor_descuento_empresa']."',
                        valor_descuento_paciente='".$varsCuenDet['valor_descuento_paciente']."',porcentaje_descuento_paciente='".$varsCuenDet['porcentaje_descuento_paciente']."',
                        porcentaje_gravamen='".$varsCuenDet['porcentaje_gravamen']."',sw_cuota_paciente='".$varsCuenDet['sw_cuota_paciente']."',
                        sw_cuota_moderadora='".$varsCuenDet['sw_cuota_moderadora']."'
                        WHERE transaccion='".$vars[$i]['transaccion']."'";
            $result1 = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                            return false;
                        }
                        $result->MoveNext();
                        $i++;
                    }
                }
                return true;
            }
        }
        return false;
    }

/**
* Funcion que modifica las existencias en bodega de un producto
* @return boolean
* @param integer valor de las existencias en la bodega del producto
* @param integer valor de la cantidad pedida en la solicitud
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica al producto
*/

  function ModificacionExistenciasBodega($Existencias,$cantidadSolici,$Empresa,$CentroUtili,$BodegaId,$Codigo){

        list($dbconn) = GetDBconn();
        $ExistenciasTotal= $Existencias - $cantidadSolici;
        $query="UPDATE existencias_bodegas SET existencia='$ExistenciasTotal' WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$Codigo'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false);
            return false;
        }
    return 1;
    }


    function DescargarLotesBodega($Empresa,$CentroUtilidad,$Bodega,$codigoProducto,$Cantidad,&$dbconn){
        if(!is_object($dbconn)){
        list($dbconn) = GetDBconn();
        }
    $query="SELECT numero_registro,cantidad
        FROM bodegas_documentos_d_fvencimiento_lotes
        WHERE empresa_id='$Empresa'
        AND centro_utilidad='$CentroUtilidad'
        AND bodega='$Bodega'
        AND codigo_producto='$codigoProducto'
        ORDER BY fecha_vencimiento";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            return false;
        }else{
      $lotes=$result->GetArray();
            $resta=0;
            $querys .= "";
            foreach($lotes as $x=>$lote){
        $resta=$lote[1]-$Cantidad;
                if($resta>0){
          $querys .= "UPDATE bodegas_documentos_d_fvencimiento_lotes
                    SET cantidad=$resta
                    WHERE numero_registro='".$lote[0]."'; ";
                    break;
                }elseif($resta<=0){
          $querys .= "DELETE FROM bodegas_documentos_d_fvencimiento_lotes
                    WHERE numero_registro='".$lote[0]."'; ";
          $Cantidad=abs($resta);
                    if($Cantidad==0){
            break;
                    }
                }
            }
            $dbconn->Execute($querys);
            if($dbconn->ErrorNo() != 0){
                return false;
            }
      }
    return true;
    }

    /**
* funcion para insumis y medicamentos para las ordenes de servicio
* @return boolean
*/

    function LiquidarIYMOrdenServicio($cuenta,&$dbconn){

      $query="SELECT empresa_id,centro_utilidad,bodega FROM tmp_cuenta_imd WHERE numerodecuenta='$cuenta' GROUP BY empresa_id,centro_utilidad,bodega,numerodecuenta";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'tmp_cuenta_imd' esta vacia ";
                return false;
            }else{
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
                  $result->MoveNext();
              }
            }
        }
        if($vars){
        for($i=0;$i<sizeof($vars);$i++){
      $Empresa=$vars[$i]['empresa_id'];
            $CentroUtili=$vars[$i]['centro_utilidad'];
            $BodegaId=$vars[$i]['bodega'];
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
            empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto,&$dbconn);
            $numeracion=$numeracion['numeracion'];
            $codigoAgrupamiento=InsertarBodegasDocumentosLib($concepto,$numeracion,date("Y/m/d"),'','IMD',&$dbconn);
            if($codigoAgrupamiento!='0'){
                $query="SELECT codigo_producto,cantidad,departamento,precio,fecha_cargo,plan_id,servicio_cargo FROM tmp_cuenta_imd WHERE numerodecuenta='$cuenta' AND empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId'";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() !=0 ){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }else{
                    $datosCont=$result->RecordCount();
                    if($datosCont){
                        while(!$result->EOF){
                            $varsPr[]=$result->GetRowAssoc($toUpper=false);
                            $result->MoveNext();
                        }
                    }
                }
            }else{
        $_SESSION['INV_MENSAJE_ERROR']="Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega";
                GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                return false;
            }
            for($j=0;$j<sizeof($varsPr);$j++){
              $Cantidad=$varsPr[$j]['cantidad'];
        $codigoProducto=$varsPr[$j]['codigo_producto'];
                $departamento=$varsPr[$j]['departamento'];
                $FechaCargo=$varsPr[$j]['fecha_cargo'];
                $Plan=$varsPr[$j]['plan_id'];
                $Servicio=$varsPr[$j]['servicio_cargo'];
        $costoProducto=HallarCostoProductoLib($Empresa,$codigoProducto,&$dbconn);
                $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result=$dbconn->Execute($query);
                $Consecutivo=$result->fields[0];
                $InsertarDocumentod=InsertarBodegasDocumentosdLib($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto,&$dbconn);
                if($InsertarDocumentod==1){
                  if(LiquidacionDespachoMed($Consecutivo,$varsPr[$j]['fecha_cargo'],$cuenta,$codigoProducto,$Cantidad,$varsPr[$j]['precio'],$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','0','IMD',&$dbconn)==false){
                        $_SESSION['INV_MENSAJE_ERROR']="Error al Guardar en la Cuenta del Paciente Verifique la Contratacion";
                        GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                        return false;
                    }else{
            $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
                        $result = $dbconn->Execute($query);
                        $Existencias=$result->fields[0];
                        if(($Existencias-$Cantidad)<0){
                            $_SESSION['INV_MENSAJE_ERROR']="Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles";
                            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                            return false;
                        }
                        $ModifExist=ModificacionExistenciasBodegaLib($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto,&$dbconn);
                        if($result->fields[1]=='1'){
                            DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad,&$dbconn);
                        }
                    }
                }else{
                    $_SESSION['INV_MENSAJE_ERROR']="Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega";
                    GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                    return false;
                }
            }
            $totalizCostoDoc=TotalizarCostoDocumentoLib($numeracion,$concepto,&$dbconn);
            $query="DELETE FROM tmp_cuenta_imd WHERE numerodecuenta='$cuenta' AND empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                return false;
            }
        }
        //GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
        //print_r($dbconn); echo "<br><br><br>";
        return true;
        }else{
            $_SESSION['INV_MENSAJE_ERROR']="Error Consulte al Administrador";
            //GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
            return false;
        }


        //fin por cada solicitud
    }//fin functionUpdateX

    /**
* Funcion que realiza la insercion de la cabecera del documento cuando se realiza una solicitud
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param date fecha de realizacion del documento
* @param string obesrvaciones realizadas al documento
* @param string prefijo del documento
* @param boolean indicador de destino de la funcion
*/

    function InsertarBodegasDocumentosLib($concepto,$numeracion,$Fecha,$observaciones,$tipoCargo,&$dbconn){

        //list($dbconn) = GetDBconn();
        $query = "INSERT INTO bodegas_documentos(
                                                            bodegas_doc_id,
                                                            numeracion,
                                                            fecha,
                                                            total_costo,
                                                            transaccion,
                                                            observacion,
                                                            usuario_id,
                                                            fecha_registro)VALUES('$concepto','$numeracion','$Fecha','0',NULL,
                                                        '$observaciones','".UserGetUID()."','".date("Y-m-d H:i:s")."')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
            return false;
        }else{
            $query = "SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq')";
            $result = $dbconn->Execute($query);
            $codigoAgrupamiento=$result->fields[0];
      if($tipoCargo=='DIMD'){
        $descrip='DEVOLUCION DE MEDICAMENTOS';
      }else{
        $descrip='DESCARGO DE MEDICAMENTOS';
      }
      if(!empty($_SESSION['LIQUIDACION_QX']['NoLIQUIDACION'])){
        $NoLiquidacion="'".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."'";
      }else{
        $NoLiquidacion='NULL';
      }
            $query = "INSERT INTO cuentas_codigos_agrupamiento(codigo_agrupamiento_id,
                                                                                                                descripcion,
                                                                                                                bodegas_doc_id,
                                                                                                                numeracion,
                                                        cuenta_liquidacion_qx_id)
                                                                                                                VALUES('$codigoAgrupamiento',
                                                                                                                '".$descrip."',
                                                                                                                '$concepto',
                                                                                                                '$numeracion',
                                                        $NoLiquidacion)";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
                return false;
            }else{
                return $codigoAgrupamiento;
            }
        }
        return '0';
    }

/**
* Funcion que inserta el detalle del documento
* @return boolean
* @param string codigo unico que el registro de insercion
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica el producto
* @param integer costo del producto
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function InsertarBodegasDocumentosdLib($Consecutivo,$numeracion,$concepto,$Codigo,$Cantidad,$costoProducto,&$dbconn){

        //list($dbconn) = GetDBconn();
        $query = "INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                            codigo_producto,
                                                                                            cantidad,
                                                                                            total_costo,
                                                                                            bodegas_doc_id,
                                                                                            numeracion)VALUES(
                                                                                            '$Consecutivo',
                                                                                            '$Codigo',
                                                                                            '$Cantidad',
                                                                                            '$costoProducto',
                                                                                            '$concepto',
                                                                                            '$numeracion')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
            return false;
        }else{
      return 1;
        }
    }

    /**
* Funcion que halla el costo de un producto
* @return array
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string codigo unico que identifica la producto
*/

  function HallarCostoProductoLib($Empresa,$Codigo,&$dbconn){

        //list($dbconn) = GetDBconn();
        $query="SELECT costo FROM inventarios WHERE empresa_id='$Empresa' AND codigo_producto='$Codigo'";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datosCont=$result->RecordCount();
            if($datosCont){
                $vars=$result->GetRowAssoc($toUpper=false);
                $costoProducto=$vars['costo'];
            }
        }
        return $costoProducto;
    }

     /**
* Funcion que totaliza los valores del detalle y lo actualiza en el documento
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param integer codigo unico que identifica el documento
* @param integer prefijo del documento
* @param integer codigo de que identifica el movimiento de la cuenta del paciente
*/

  function TotalizarCostoDocumentoLib($numeracion,$concepto,&$dbconn){
    //list($dbconn) = GetDBconn();
        $query="SELECT sum(total_costo*cantidad) as sumaCosto FROM bodegas_documentos_d  WHERE bodegas_doc_id='$concepto' AND numeracion='$numeracion'";
        $result = $dbconn->Execute($query);
        $sumaCosto=$result->fields[0];
        $query="UPDATE bodegas_documentos SET total_costo='$sumaCosto' WHERE bodegas_doc_id='$concepto' AND numeracion='$numeracion'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
            return false;
        }else{
      return 1;
        }
    }


    /**
* Funcion que modifica las existencias en bodega de un producto
* @return boolean
* @param integer valor de las existencias en la bodega del producto
* @param integer valor de la cantidad pedida en la solicitud
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica al producto
*/

  function ModificacionExistenciasBodegaLib($Existencias,$cantidadSolici,$Empresa,$CentroUtili,$BodegaId,$Codigo,&$dbconn){

        //list($dbconn) = GetDBconn();
        $ExistenciasTotal= $Existencias - $cantidadSolici;
        $query="UPDATE existencias_bodegas SET existencia='$ExistenciasTotal' WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$Codigo'";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumentoDespa($commit=false,&$dbconn);
            return false;
        }
    return 1;
    }




?>
