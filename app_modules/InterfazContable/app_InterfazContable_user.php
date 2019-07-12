 <?php

 /**
 * $Id: app_InterfazContable_user.php,v 1.7 2006/02/07 20:14:07 alex Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */


/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_InterfazContable_user extends classModulo
{

    var $limit;
    var $conteo;

        function app_InterfazContable_user()
        {
            $this->limit=GetLimitBrowser();
                //$this->limit=5;
                return true;
        }

        /**
        *
        */
        function main()
        {
            list($dbconn) = GetDBconn();
            unset($_SESSION['SEGURIDAD']['INTERFAZCG1']);
           /* if(!empty($_SESSION['SEGURIDAD']['INTERFAZCG1']))
            {
                        $this->salida.= gui_theme_menu_acceso('CENTRO AUTORIZACION',$_SESSION['SEGURIDAD']['INTERFAZCG1']['arreglo'],$_SESSION['SEGURIDAD']['INTERFAZCG1']['centro'],$_SESSION['SEGURIDAD']['INTERFAZCG1']['url'],ModuloGetURL('system','Menu'));
                        return true;
            }*/
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $query = "SELECT a.*, b.razon_social as descripcion1
                                            FROM userpermisos_interfaz_contable as a, empresas as b
                                            WHERE a.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resulta=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->fileError = __FILE__;
                                        $this->lineError = __LINE__;
                    return false;
            }
            while ($data = $resulta->FetchRow()) {
                $centro[$data['descripcion1']]=$data;
                $seguridad[$data['empresa_id']]=1;
            }
            $url[0]='app';
            $url[1]='InterfazContable';
            $url[2]='user';
            $url[3]='Principal';
            $url[4]='CG1';
            $arreglo[0]='EMPRESA';


            $_SESSION['SEGURIDAD']['INTERFAZCG1']['arreglo']=$arreglo;
            $_SESSION['SEGURIDAD']['INTERFAZCG1']['centro']=$centro;
            $_SESSION['SEGURIDAD']['INTERFAZCG1']['url']=$url;
            $_SESSION['SEGURIDAD']['INTERFAZCG1']['puntos']=$seguridad;
            $this->salida.= gui_theme_menu_acceso('INTERFAZ CONTABLE CG1',$_SESSION['SEGURIDAD']['INTERFAZCG1']['arreglo'],$_SESSION['SEGURIDAD']['INTERFAZCG1']['centro'],$_SESSION['SEGURIDAD']['INTERFAZCG1']['url'],ModuloGetURL('system','Menu'));
         return true;
        }

        function Principal()
        {
                    unset($_SESSION['INTERFAZCG1']['VECTOR']);
                    unset($_SESSION['INTERFAZCG1']['ERROR']);
                    unset($_SESSION['INTERFAZCG1']['DOCUMENTO']['ERROR']);
                    unset($_SESSION['INTERFAZCG1']['DETALLE']);
                    if(empty($_SESSION['INTERFAZCG1']['EMPRESA']))
                    {
                                    /*if(empty($_SESSION['SEGURIDAD']['INTERFAZCG1']['puntos'][$_REQUEST['Centro']['empresa_id']][$_REQUEST['Centro']['plan_id']]))
                                    {
                                                    $this->error = "Error de Seguridad.";
                                                    $this->mensajeDeError = "Violación a la Seguridad.";
                                                    return false;
                                    }*/
                                    $_SESSION['INTERFAZCG1']['EMPRESA']=$_REQUEST['CG1']['empresa_id'];
                }

                    $this->FormaPrincipal();
                    //$this->FormaBuscar();
                    return true;
        }


        function Buscar()
        {
                if(!empty($_REQUEST['diaF']) AND empty($_REQUEST['diaI']))
                {
                        $this->frmError["diaF"]=1;
                        $this->frmError["MensajeError"]="DEBE ELEGIR EL DIA INICIAL";
                        $this->FormaBuscar();
                        return true;
                }

                if(empty($_REQUEST['Tipo']))
                {
                        $this->frmError["Tipo"]=1;
                        $this->frmError["MensajeError"]="DEBE ELEGIR EL TIPO DE DOCUMENTO";
                        $this->FormaBuscar();
                        return true;
                }

                unset($_SESSION['INTERFAZCG1']['VECTOR']);
                unset($_SESSION['INTERFAZCG1']['ERROR']);
                unset($_SESSION['INTERFAZCG1']['DOCUMENTO']['ERROR']);

                if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1.class.php"))
                {
                        die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
                }

                if(!class_exists('InterfaseCG1'))
                {
                        die(MsgOut("NO SE CARGO LA CLASE","InterfaseCG1 - NO EXISTE"));
                }

                $a= new InterfaseCG1;

                //0 numero 1 descripcion
                $v = explode('||',$_REQUEST['Tipo']);

                //SELECCION DEL LAPSO CONTABLE (AÑO, MES, DIAINICIAL, DIAFINAL)
                $a->SetLapsoContable($_REQUEST['ano'],$_REQUEST['mes'],$_REQUEST['diaI'],$_REQUEST['diaF']);

                //CONFIGURACION DEL TIPO DE DOCUMENTO SELECCIONADO (EMPRESA_ID,DOCUMENTO_ID)
                $a->setTipoDeDocumento($_SESSION['INTERFAZCG1']['EMPRESA'],$v[0]);

                //RETORNA LA INFORMACION DEL DOCUMENTO QUE SELECCIONO CON $a->setTipoDeDocumento();
                $tipoDeDocSeleccionado = $a->getTipoDeDocumento();

                //RETORNA UN ARREGLO CON TODOS LOS DOCUMENTOS QUE DEBEN PASAR POR LA INTERFASE Y SU ESTADO
                $retorno = $a->GetDocumentos('',$_REQUEST['paso']);
                    //print_r($retorno);

                unset($_SESSION['INTERFAZCG1']['DETALLE']);
                if(!empty($retorno))
                {
                        $_SESSION['INTERFAZCG1']['DETALLE']['Tipo']=$v[0];
                        $_SESSION['INTERFAZCG1']['DETALLE']['Descripcion']=$v[1];
                        $_SESSION['INTERFAZCG1']['DETALLE']['ano']=$_REQUEST['ano'];
                        $_SESSION['INTERFAZCG1']['DETALLE']['mes']=$_REQUEST['mes'];
                        $_SESSION['INTERFAZCG1']['DETALLE']['diaI']=$_REQUEST['diaI'];
                        $_SESSION['INTERFAZCG1']['DETALLE']['diaF']=$_REQUEST['diaF'];
                }

                $this->FormaBuscar($retorno);
                return true;
        }

        function MostrarTodas()
        {
                if($_REQUEST['TODO']==1)
                {       //mostra todo
                        $this->FormaBuscar($_SESSION['INTERFAZCG1']['VECTOR'],1);
                        return true;
                }
                else
                {       //ocultar
                        $this->FormaBuscar($_SESSION['INTERFAZCG1']['VECTOR'],0);
                        return true;
                }
        }

        function VolverBuscar()
        {
                        $this->FormaBuscar($_SESSION['INTERFAZCG1']['VECTOR']);
                        return true;
        }

        function DetalleDocumento()
        {
                unset($_SESSION['INTERFAZCG1']['DOCUMENTO']['ERROR']);
                if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1.class.php"))
                {
                        die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
                }

                if(!class_exists('InterfaseCG1'))
                {
                        die(MsgOut("NO SE CARGO LA CLASE","InterfaseCG1 - NO EXISTE"));
                }

                $a= new InterfaseCG1;

                //SELECCION DEL LAPSO CONTABLE (AÑO, MES, DIAINICIAL, DIAFINAL)
                $a->SetLapsoContable($_SESSION['INTERFAZCG1']['DETALLE']['ano'],$_SESSION['INTERFAZCG1']['DETALLE']['mes'],$_SESSION['INTERFAZCG1']['DETALLE']['diaI'],$_SESSION['INTERFAZCG1']['DETALLE']['diaF']);

                //CONFIGURACION DEL TIPO DE DOCUMENTO SELECCIONADO (EMPRESA_ID,DOCUMENTO_ID)
                $a->setTipoDeDocumento($_SESSION['INTERFAZCG1']['EMPRESA'],$_SESSION['INTERFAZCG1']['DETALLE']['Tipo']);

                //RETORNA LA INFORMACION DEL DOCUMENTO QUE SELECCIONO CON $a->setTipoDeDocumento();
                $tipoDeDocSeleccionado = $a->getTipoDeDocumento();

                //RETORNA UN ARREGLO CON TODOS LOS DOCUMENTOS QUE DEBEN PASAR POR LA INTERFASE Y SU ESTADO
                $retorno = $a->GetDetalleDocumento($_REQUEST['factura']);
                $_SESSION['INTERFAZCG1']['DOCUMENTO']['ERROR']=$retorno;
                    //print_r($retorno);
                $this->FormaDetalle($retorno);
                return true;
        }

        function Interfase()
        {
                    unset($_SESSION['INTERFAZCG1']['DOCUMENTO']['ERROR']);
                    if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1.class.php"))
                    {
                            die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
                    }

                    if(!class_exists('InterfaseCG1'))
                    {
                            die(MsgOut("NO SE CARGO LA CLASE","InterfaseCG1 - NO EXISTE"));
                    }
                    $a= new InterfaseCG1;

                    //SELECCION DEL LAPSO CONTABLE (AÑO, MES, DIAINICIAL, DIAFINAL)
                    $a->SetLapsoContable($_SESSION['INTERFAZCG1']['DETALLE']['ano'],$_SESSION['INTERFAZCG1']['DETALLE']['mes'],$_SESSION['INTERFAZCG1']['DETALLE']['diaI'],$_SESSION['INTERFAZCG1']['DETALLE']['diaF']);
                    //CONFIGURACION DEL TIPO DE DOCUMENTO SELECCIONADO (EMPRESA_ID,DOCUMENTO_ID)
                    $a->setTipoDeDocumento($_SESSION['INTERFAZCG1']['EMPRESA'],$_SESSION['INTERFAZCG1']['DETALLE']['Tipo']);
                    //RETORNA LA INFORMACION DEL DOCUMENTO QUE SELECCIONO CON $a->setTipoDeDocumento();
                    $tipoDeDocSeleccionado = $a->getTipoDeDocumento();

                    if(!empty($_REQUEST['Contabilizacion']))
                    {       //va a contabilizar
                            foreach($_REQUEST as $k => $v)
                            {
                                    if(substr_count($k,'Contabilizar'))
                                    {
                                            $f=1; $vec='';
                                            //0 prefijo 1 numero
                                            $vec=explode('||',$v);
                                            $a->ContabilizarDocumento($vec[1],'');
                                    }
                            }

                            if($f==0)
                            {
                                            $this->frmError["MensajeError"]="ERRO DATOS VACIOS: DEBE ELEGIR ALGUN DOCUMENTO A CONTABILIZAR.";
                                            $this->FormaBuscar($_SESSION['INTERFAZCG1']['VECTOR']);
                                            return true;
                            }
                            else
                            {
                                            $this->frmError["MensajeError"]="SE TERMINO EL PROCESO DE CONTABILIZACION.";
                                            //RETORNA UN ARREGLO CON TODOS LOS DOCUMENTOS QUE DEBEN PASAR POR LA INTERFASE Y SU ESTADO
                                            $retorno = $a->GetDocumentos();

                                            $this->FormaBuscar($retorno);
                                            return true;
                            }
                    }
                    elseif(!empty($_REQUEST['GenerarInterfase']))
                    {       //va a generar la interfase
                            $batch = $a->GenerarInterfase();
                            if(!$batch)
                            {
                                    $this->frmError["MensajeError"]='ERROR AL GENERAR LA INTERFAZ <BR>'.$a->Err().' - '.$a->ErrMsg();
                            }
                            else
                            {
                            $this->frmError["MensajeError"]="GENERADA LA INTERFAZ No. $batch";
                            $this->rutaInterface = $a->GetPathInterfaseCG1();
                            }
                            $this->FormaBuscar();
                            return true;
                    }
        }

        function LlamarManejoInterfaz()
        {
                $this->FormaManejoInterfaz();
                return true;
        }

        function BuscarBatch()
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT a.interfase_cg1_batch_generado_id,a.fecha_registro, a.documento_id,
                                    b.descripcion, a.fecha_inicial, a.fecha_final,
                                    a.documento_id, a.empresa_id
                                    FROM interfase_cg1_batch_generados a, documentos b
                                    WHERE a.empresa_id='".$_SESSION['INTERFAZCG1']['EMPRESA']."'
                                    AND a.estado='1'
                                    AND a.documento_id=b.documento_id
                                    AND a.empresa_id=b.empresa_id";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error select ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                }
                while(!$result->EOF)
                {
                        $var[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                }
                $result->Close();
                return $var;
        }

        function GuardarPasoInterfaz()
        {
                $f=0;
                foreach($_REQUEST as $k => $v)
                {
                        if(substr_count($k,'paso'))
                        {       $f=1;  }
                }

                if(empty($f))
                {
                            $this->frmError["MensajeError"]="DEBE SELECCIONAR SI EL ARCHIVO PASO O NO LA INTERFAZ.";
                            $this->FormaManejoInterfaz();
                            return true;
                }

                $query = '';
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();

                foreach($_REQUEST as $k => $v)
                {
                        if(substr_count($k,'paso'))
                        {       //0=>si paso o no 1=>inicial 2=>final 3=>empresa 4=>documento 5=>id
                                $a = explode('||',$v);
                                $query = "UPDATE interfase_cg1_batch_generados SET estado='$a[0]'
                                                    WHERE interfase_cg1_batch_generado_id=$a[5]";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error INSERT INTO pagares";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $this->fileError = __FILE__;
                                                $this->lineError = __LINE__;
                                                $dbconn->RollbackTrans();
                                                return false;
                                }

                                //2 es cuando paso la interfaz
                                if($a[0] == 2)
                                {
                                        $query = "UPDATE cg_movimientos_contables SET tipo_bloqueo_id='04'
                                                            WHERE empresa_id='$a[3]' AND documento_id=$a[4]
                                                            AND date_trunc('day',fecha_documento)>= '$a[1]'
                                                            AND date_trunc('day',fecha_documento)<= '$a[2]'";
                                        $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                                        $this->error = "Error INSERT INTO pagares";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $this->fileError = __FILE__;
                                                        $this->lineError = __LINE__;
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                        }
                                }
                        }
                }

                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                $this->FormaManejoInterfaz();
                return true;
        }

//------------------------------------------------------------------------------
}//fin clase user
?>

