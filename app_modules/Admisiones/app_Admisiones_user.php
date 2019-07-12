 <?php

 /**
 * $Id: app_Admisiones_user.php,v 1.28 2006/08/25 13:29:03 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */


/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_Admisiones_user extends classModulo
{

    var $limit;
    var $conteo;

        function app_Admisiones_user()
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

        }
/**
* Esta funci” retorna los datos de concernientes a la version del submodulo
* @access private
*/

    function ValidarDatos()
    {
                if(empty($_SESSION['ADMISIONES']['RETORNO']))
                {
                                $this->error = "ADMISIONES ";
                                $this->mensajeDeError = "El retorno esta vacio.";
                                return false;
                }

                if(empty($_SESSION['ADMISIONES']['EMPRESA']) OR empty($_SESSION['ADMISIONES']['CENTROUTILIDAD']))
                {
                                $this->error = "ADMISIONES ";
                                $this->mensajeDeError = "La Empresa o el Centro de Utilidad estan vacios.";
                                return false;
                }

                if(empty($_SESSION['ADMISIONES']['DPTO']) OR empty($_SESSION['ADMISIONES']['SERVICIO']) OR empty($_SESSION['ADMISIONES']['TIPO']))
                {
                                $this->error = "ADMISIONES ";
                                $this->mensajeDeError = "Faltan Datos.";
                                return false;
                }

                $this->FormaBuscar();
                return true;
    }

    /**
    *
    */
    function BuscarPaciente()
    {
            unset($_SESSION['ADMISIONES']['BUSQUEDA']);
            $_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
            $_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id']=$_REQUEST['Documento'];
            $_SESSION['ADMISIONES']['BUSQUEDA']['Nombres']=$_REQUEST['nombre'];
            $_SESSION['ADMISIONES']['BUSQUEDA']['prefijo']=strtoupper($_REQUEST['prefijo']);
            $_SESSION['ADMISIONES']['BUSQUEDA']['historia']=$_REQUEST['historia'];

            if($_REQUEST['TipoDocumento']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'])
            {
                        $this->frmError["MensajeError"]="DEBE ELEGIR CRITERIOS PARA LA BUSQUEDA.";
                        $this->FormaBuscar();
                        return true;
            }

            list($dbconn) = GetDBconn();
            if($_REQUEST['prefijo'] OR $_REQUEST['historia'])
            {
                        $query = "SELECT tipo_id_paciente, paciente_id FROM historias_clinicas
                                            WHERE historia_numero='".$_REQUEST['historia']."'
                                            AND historia_prefijo='".strtoupper($_REQUEST['prefijo'])."'";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar SELECT en historias_clinicas";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        if($result->EOF)
                        {
                                $this->frmError["MensajeError"]="LA HISTORIA NO EXISTE.";
                                $this->FormaBuscar();
                                return true;
                        }
                        else
                        {
                                    $_REQUEST['Documento']=$result->fields[1];
                                    $_REQUEST['Tipo']=$result->fields[0];
                        }
                        $result->Close();
            }
            elseif($_REQUEST['Tipo']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['nombre'])
            {
                        if($_REQUEST['Tipo']==-1){ $this->frmError["Tipo"]=1; }
                        if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
                        $this->frmError["MensajeError"]="ELIJA CRITERIOS PARA LA BUSQUEDA.";
                        $this->FormaBuscar();
                        return true;
            }

            IncludeLib("funciones_admision");
            $tipo_documento=$_REQUEST['TipoDocumento'];
            $documento=$_REQUEST['Documento'];
            $nombres = strtoupper($_REQUEST['nombre']);

            //buscar si esta pendiente de ser clasificaco en un punto
            $var=BuscarPacienteTriage($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,1);
                    return true;
            }

            $var='';
            //buscar paciente pendiente admitir
            $var=BuscarPacientePteAdmision($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,2);
                    return true;
            }

            $var='';
            //buscar paciente que esta en pacientes_urgencias pte que medico lo atienda
            $var=BuscarPacientePteAtencion($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,3);
                    return true;
            }

            $var='';
            //buscar paciente que esta pte de ser ingressado a la estacion
            $var=BuscarPacientePteIngresar($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,4);
                    return true;
            }

            $var='';
            //buscar paciente que esta en una estacion
            $var=BuscarPteClasificacionMedica($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,8);
                    return true;
            }


            $var='';
            //buscar paciente que esta en una estacion
            $var=BuscarPacienteEstacion($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,6);
                    return true;
            }

            $var='';
            //buscar paciente que el asistencial pidio remision
            $var=BuscarPteRemisionMedica($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,7);
                    return true;
            }

            $var='';
            //buscar paciente que fue remitiso
            $var=BuscarPacienteRemitido($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,5);
                    return true;
            }

            $this->frmError["MensajeError"]="EL PACIENTE NO SE ENCUENTRA.";
            $this->FormaBuscar('','');
            return true;
    }
//-------------------------------------------------------------------------

    function ValidarDatosSalida()
    {
                if(empty($_SESSION['ADMISIONES']['RETORNO']))
                {
                                $this->error = "ADMISIONES ";
                                $this->mensajeDeError = "El retorno esta vacio.";
                                return false;
                }

                if(empty($_SESSION['ADMISIONES']['EMPRESA']) OR empty($_SESSION['ADMISIONES']['CENTROUTILIDAD']))
                {
                                $this->error = "ADMISIONES ";
                                $this->mensajeDeError = "La Empresa o el Centro de Utilidad estan vacios.";
                                return false;
                }

                if(empty($_SESSION['ADMISIONES']['TIPOSALIDA']))
                {
                                $this->error = "ADMISIONES ";
                                $this->mensajeDeError = "Faltan Datos, EL TIPO DE SALIDA.";
                                return false;
                }

                $this->FormaBuscar();
                return true;
    }


    /**
    *
    */
    function BuscarPacienteSalida()
    {
            unset($_SESSION['ADMISIONES']['BUSQUEDA']);
            $_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
            $_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id']=$_REQUEST['Documento'];
            $_SESSION['ADMISIONES']['BUSQUEDA']['Nombres']=$_REQUEST['Nombres'];
            $_SESSION['ADMISIONES']['BUSQUEDA']['prefijo']=strtoupper($_REQUEST['prefijo']);
            $_SESSION['ADMISIONES']['BUSQUEDA']['historia']=$_REQUEST['historia'];

            if($_REQUEST['TipoDocumento']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'])
            {
                        $this->frmError["MensajeError"]="DEBE ELEGIR CRITERIOS PARA LA BUSQUEDA.";
                        $this->FormaBuscar();
                        return true;
            }

            list($dbconn) = GetDBconn();
            if($_REQUEST['prefijo'] OR $_REQUEST['historia'])
            {
                        $query = "SELECT tipo_id_paciente, paciente_id FROM historias_clinicas
                                            WHERE historia_numero='".$_REQUEST['historia']."'
                                            AND historia_prefijo='".strtoupper($_REQUEST['prefijo'])."'";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar SELECT en historias_clinicas";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        if($result->EOF)
                        {
                                $this->frmError["MensajeError"]="LA HISTORIA NO EXISTE.";
                                $this->FormaBuscar();
                                return true;
                        }
                        else
                        {
                                    $_REQUEST['Documento']=$result->fields[1];
                                    $_REQUEST['Tipo']=$result->fields[0];
                        }
                        $result->Close();
            }
            elseif($_REQUEST['Tipo']==-1 OR !$_REQUEST['Documento'])
            {
                        if($_REQUEST['Tipo']==-1){ $this->frmError["Tipo"]=1; }
                        if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
                        $this->frmError["MensajeError"]="PARA BUSCA POR DOCUMENTOS DEBE DIGITAR LAS DOS OPCIONES.";
                        $this->FormaBuscar();
                        return true;
            }

            IncludeLib("funciones_admision");
            $tipo_documento=$_REQUEST['TipoDocumento'];
            $documento=$_REQUEST['Documento'];
            $nombres = strtoupper($_REQUEST['Nombres']);

            $var='';
            //buscar paciente que esta en una estacion
            $var=BuscarPacienteEstacion($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,6);
                    return true;
            }

            //buscar si esta pendiente de ser clasificaco en un punto
            $var=BuscarPacienteTriage($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,1);
                    return true;
            }

            $var='';
            //buscar paciente pendiente admitir
            $var=BuscarPacientePteAdmision($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,2);
                    return true;
            }

            $var='';
            //buscar paciente que esta en pacientes_urgencias pte que medico lo atienda
            $var=BuscarPacientePteAtencion($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,3);
                    return true;
            }

            $var='';
            //buscar paciente que esta pte de ser ingressado a la estacion
            $var=BuscarPacientePteIngresar($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,4);
                    return true;
            }

            $var='';
            //buscar paciente que esta en una estacion
            $var=BuscarPteClasificacionMedica($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,8);
                    return true;
            }


            $var='';
            //buscar paciente que esta en una estacion
            $var=BuscarPacienteEstacion($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,6);
                    return true;
            }

            $var='';
            //buscar paciente que el asistencial pidio remision
            $var=BuscarPteRemisionMedica($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,7);
                    return true;
            }

            $var='';
            //buscar paciente que fue remitiso
            $var=BuscarPacienteRemitido($_SESSION['ADMISIONES']['EMPRESA'],$tipo_documento,$documento,$_REQUEST['prefijo'],$_REQUEST['historia'],$nombres,$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
            if(!empty($var))
            {
                    $this->FormaBuscar($var,5);
                    return true;
            }

            $this->frmError["MensajeError"]="EL PACIENTE NO SE ENCUENTRA.";
            $this->FormaBuscar('','');
            return true;
    }


//-----------------------------ACCIONES DE LAS OPCIONES------------------------------
    /**
    *
    */
    function AdmitirTriage()
    {
            /*list($dbconn) = GetDBconn();
            $query ="update triages set sw_estado='3' where triage_id=".$var[triage_id]."";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }*/

            unset($_SESSION['ADMISIONES']['PACIENTE']);

            $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
            $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
            $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
            $_SESSION['ADMISIONES']['PACIENTE']['plan_id']=$_REQUEST['plan'];
            $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
            $_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id']=$_REQUEST['ptoadmon'];

            $this->AutorizarPaciente();
            return true;
    }

        /**
        *
        */
        function ModificarDatosPaciente()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);
                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];
/*
                $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['contenedor']='app';
                $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['modulo']='Admisiones';
                $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['tipo']='user';
                $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['metodo']='FormaModificarDatosPaciente';
                $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['argumentos']=array('tipoid'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
*/
                $this->FormaModificarDatosPaciente();
                return true;
        }
        /**
        *
        */
        function ModificarDatosPacienteExt()
        {
                if(empty($_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']))
                {
                            $this->error = "ADMISIONES ";
                            $this->mensajeDeError = "EL RETORNO DE LA ADMISIONES ESTA VACIO.";
                            return false;
                }

                $this->FormaModificarDatosPaciente();
                return true;
        }

        /**
        *
        */
        function CambiarPtoTriage()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);
                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['NOM_PTO']=$_REQUEST['nompto'];
                $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];

                $this->FormaCambiarPtoTriage();
                return true;
        }

        /*
        **
        */
        function ConsultaTriage()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);
                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
                $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];

                $this->FormaTriage();
                return true;
        }

        /*
        **
        */
        function ConsultaTriageExt()
        {
                $this->FormaTriage();
                return true;
        }

        /**
        *
        */
        function CambiarEstacion()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);
                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
                $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];
                $_SESSION['ADMISIONES']['PACIENTE']['estacion_id']=$_REQUEST['estacion'];
                $_SESSION['ADMISIONES']['PACIENTE']['nombre_estacion']=$_REQUEST['nomestacion'];

                $this->FormaCambiarEstacion();
                return true;
        }

        /**
        *
        */
        function UbicacionPacienteEstacion()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);
                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
                $_SESSION['ADMISIONES']['PACIENTE']['nombre_estacion']=$_REQUEST['nombre_estacion'];
                $_SESSION['ADMISIONES']['PACIENTE']['cama']=$_REQUEST['cama'];
                $_SESSION['ADMISIONES']['PACIENTE']['pieza']=$_REQUEST['pieza'];
                $_SESSION['ADMISIONES']['PACIENTE']['ubicacion']=$_REQUEST['ubicacion'];
                $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];

                $this->FormaUbicacionEstacion();
                return true;
        }

        /**
        *
        */
        function SacarPacienteLista()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);
                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
                if(empty($_SESSION['ADMISIONES']['PACIENTE']['triage_id']))
                {  $_SESSION['ADMISIONES']['PACIENTE']['triage_id']='NULL'; }
                $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];
                if(empty( $_SESSION['ADMISIONES']['PACIENTE']['ingreso']))
                {  $_SESSION['ADMISIONES']['PACIENTE']['ingreso']='NULL';  }
                $_SESSION['ADMISIONES']['PACIENTE']['lista']=$_REQUEST['lista'];
                $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];

                $this->FormaSacarLista();
                return true;
        }

        /**
        *
        */
        function AdmisionLista()
        {
            unset($_SESSION['ADMISIONES']['PACIENTE']);

            $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
            $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
            $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
            $_SESSION['ADMISIONES']['PACIENTE']['plan_id']=$_REQUEST['plan'];
            $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
            $_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id']=$_REQUEST['ptoadmon'];

            $this->Admitir();
            return true;
        }

        /***
        *
        */
        function PacienteRemitido()
        {
                unset($_SESSION['ADMISIONES']['PACIENTE']);

                $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
                $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
                $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];

                IncludeLib("funciones_admision");
                $_SESSION['ADMISIONES']['PACIENTE']['DATOSREMISION']=DatosImpresionRemision($_SESSION['ADMISIONES']['PACIENTE']['triage_id']);

                $this->FormaRemision();
                return true;
        }

    /**
    *
    */
    function ImpresionSolicitudes()
    {
          unset($_SESSION['ADMISIONES']['PACIENTE']);
          unset($_SESSION['ADMISIONES']['DATOS']);

          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
          $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']=$_REQUEST['evolucion'];
          $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

          $this->FormaImpresionSolicitudes();
          return true;
    }

    function ImpresionSolicitudesExt()
    {
          if(empty($_SESSION['ADMISIONES']['IMPRESION']['RETORNO']))
          {
               $this->error = "ADMISIONES ";
               $this->mensajeDeError = "EL RETORNO DE LA ADMISIONES ESTA VACIO.";
               return false;
          }
          
          $this->FormaImpresionSolicitudes();
          return true;
    }

    /**
    *
    */
    function RemisionMedica()
    {
            unset($_SESSION['ADMISIONES']['PACIENTE']);
            unset($_SESSION['ADMISIONES']['DATOS']);
            unset($_SESSION['ADMISIONES']['CENTROS']);

            $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
            $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
            $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
            $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']=$_REQUEST['evolucion'];
            $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

            $this->FormaRemisionMedica();
            return true;
    }
    /**
    *
    */
    function RemisionMedicaExt()
    {
            $this->FormaRemisionMedica();
            return true;
    }

    /**
    *
    */
    function SalidaPaciente()
    {
            unset($_SESSION['ADMISIONES']['PACIENTE']);
            unset($_SESSION['ADMISIONES']['DATOS']);

            $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
            $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
            $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
            $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']=$_REQUEST['evolucion'];
            $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

            $this->FormaSalidaPaciente();
            return true;
    }
    
     /*
     * Funcion Obtiene los datos del profesional que formulo los medicamentos a solicitar.
     */
     function ProfesionalFormulacion_Medicamento($usuario_id)
     {
          list($dbconn) = GetDBconn();
     	$query="SELECT usuario ||' - '|| nombre 
                  FROM system_usuarios
                  WHERE usuario_id = ".$usuario_id.";";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurri”en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
     	list($Profesional) = $resultado->FetchRow();
          return $Profesional;
     }

    
    /**
    *
    */
    function VerCuenta()
    {
            unset($_SESSION['ADMISIONES']['PACIENTE']);
            unset($_SESSION['ADMISIONES']['DATOS']);

            //$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
            //$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
            //$_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
            //$_SESSION['ADMISIONES']['PACIENTE']['cuenta']=$_REQUEST['cuenta'];

            $_SESSION['FACTURACION']['CUENTA']=$_REQUEST['cuenta'];
            $_SESSION['FACTURACION']['paciente_id']=$_REQUEST['paciente'];
            $_SESSION['FACTURACION']['tipo_id_paciente']=$_REQUEST['tipoid'];
            $_SESSION['FACTURACION']['plan_id']=$_REQUEST['plan'];
            $_SESSION['FACTURACION']['ingreso']=$_REQUEST['ingreso'];
            $_SESSION['FACTURACION']['nivel']=$_REQUEST['rango'];
            $_SESSION['FACTURACION']['fecha']=$_REQUEST['fecha'];
            $_SESSION['FACTURACION']['estado']=$_REQUEST['estado'];

            $_SESSION['FACTURACION']['PREFIJOCONTADO']=$_SESSION['SALIDA']['CONTADO'];
            $_SESSION['FACTURACION']['PREFIJOCREDITO']=$_SESSION['SALIDA']['CREDITO'];
            $_SESSION['FACTURACION']['EMPRESA']=$_SESSION['ADMISIONES']['EMPRESA'];
            $_SESSION['FACTURACION']['RETORNO']['contenedor']='app';
            $_SESSION['FACTURACION']['RETORNO']['modulo']='Admisiones';
            $_SESSION['FACTURACION']['RETORNO']['tipo']='user';
            $_SESSION['FACTURACION']['RETORNO']['metodo']='BuscarPaciente';
            $_SESSION['FACTURACION']['RETORNO']['argumentos']=array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']);

            $this->ReturnMetodoExterno('app','Facturacion_Fiscal','user','LlamadoFacturacion');
            return true;
    }

//-----------------------------------------------------------------------------------

    /**
    * Llama el modulo de pacientes para la unificacion de historias.
    * @access public
    * @return boolean
    */
     function UnificarHistorias()
     {
                    $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'];
                    $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'];
                    $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                    $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                    $_SESSION['PACIENTES']['RETORNO']['modulo']='Admisiones';
                    $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                    $_SESSION['PACIENTES']['RETORNO']['metodo']='FormaModificarDatosPaciente';

                    $this->ReturnMetodoExterno('app','Pacientes','user','UnificarHistorias');
                    return true;
     }

    /**
    * Llama el metodo CambiarIdentificacionPaciente del modulo pacientes para cambiar la identificacion
    * @access public
    * @return boolean
    */
     function CambioIdentificacion()
     {
                    $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'];
                    $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'];
                    $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                    $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                    $_SESSION['PACIENTES']['RETORNO']['modulo']='Admisiones';
                    $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                    $_SESSION['PACIENTES']['RETORNO']['metodo']='FormaModificarDatosPaciente';

                    $this->ReturnMetodoExterno('app','Pacientes','user','CambiarIdentificacionPaciente');
                    return true;
     }

        /**
        *
        */
        function TodosPuntosTriage()
        {
            list($dbconn) = GetDBconn();
            $query = "  SELECT b.punto_triage_id, b.descripcion || ' [ ' || (select count(*) from triages where sw_estado='0' and punto_triage_id=b.punto_triage_id) || ' ]' as descripcion
                                    FROM puntos_triage as b";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al eliminar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            return $var;
        }

        /**
        *
        */
        function ActualizarPtoTriage()
        {
                if($_REQUEST['Punto']==-1)
                {
                        $this->frmError["Punto"]=1;
                        $this->frmError["MensajeError"]='Debe Elegir el Punto de Admisi”.';
                        if(!$this->FormaCambiarPtoTriage()){
                            return false;
                        }
                        return true;
                }

                list($dbconn) = GetDBconn();
                $query ="update triages set punto_triage_id=".$_REQUEST['Punto']."
                                    where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error update triages";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }

                $nom=$this->NombrePtoTriage($_REQUEST['Punto']);
                $mensaje='El paciente pasa a clasificaci” de Triage al Punto '.$nom;
                $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
                            return false;
                }
                return true;
        }

        /**
        *
        */
        function NombrePtoTriage($pto)
        {
                list($dbconn) = GetDBconn();
                $query = " SELECT descripcion FROM puntos_triage
                                        WHERE punto_triage_id='$pto'";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $resulta->Close();
                return $resulta->fields[0];
        }
//------------------------------------AUTOTIZACIONES---------------------------
    /**
    *
    */
    function AutorizarPaciente()
    {
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['ADMISIONES']['PACIENTE']['plan_id'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']=true;
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']=$_SESSION['ADMISIONES']['TIPO'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['ADMISIONES']['SERVICIO'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Admon';
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Admisiones';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

            $this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
            return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
    function RetornoAutorizacion()
    {
                $_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
                $_SESSION['ADMISIONES']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
                $_SESSION['ADMISIONES']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
                $_SESSION['ADMISIONES']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];

                if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])){  $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; }
                $_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
                $_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
                $_SESSION['ADMISIONES']['AUTORIZACIONES']['ARREGLO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'];

                //empleador
                if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
                {
                        $_SESSION['ADMISIONES']['PACIENTE']['EMPLEADOR']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
                        $_SESSION['ADMISIONES']['PACIENTE']['EMPLEADOR']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
                        //$_SESSION['TRIAGE']['PACIENTE']['EMPLEADOR']['empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador'];
                        //$_SESSION['TRIAGE']['PACIENTE']['EMPLEADOR']['telefono_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador'];
                        //$_SESSION['TRIAGE']['PACIENTE']['EMPLEADOR']['direccion_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador'];
                }

                unset($_SESSION['AUTORIZACIONES']);

                if(empty($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']))
                {
                            if(empty($Mensaje))
                            {   $Mensaje = 'No se pudo realizar la Autorizaci” para la Admisi” Urgencias.';   }
                            $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                            if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
                            return false;
                            }
                            return true;
                }

                $this->EstacionEnfermeria();
                return true;
    }


//--------------------------INGRESO----------------------------------------------

    /**
    *
    */
    function Admitir()
    {
                includeLib('funciones_admision');
                $var=$this->DatosPendientesAdmitir($_SESSION['ADMISIONES']['PACIENTE']['triage_id']);
                $CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');
        
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query="SELECT nextval('ingresos_ingreso_seq')";
                $result=$dbconn->Execute($query);
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error ingresos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }
                $IngresoId=$result->fields[0];

                if(empty($var[autorizacion_int]))
                {   $var[autorizacion_int] = 1;  }

                if(empty($var[autorizacion_ext]))
                {   $var[autorizacion_ext] = 0;  }

                $query = "INSERT INTO ingresos (ingreso,
                                                                                tipo_id_paciente,
                                                                                paciente_id,
                                                                                fecha_ingreso,
                                                                                causa_externa_id,
                                                                                via_ingreso_id,
                                                                                comentario,
                                                                                departamento,
                                                                                estado,
                                                                                fecha_registro,
                                                                                usuario_id,
                                                                                departamento_actual,
                                                                                autorizacion_int,
                                                                                autorizacion_ext)
                                    VALUES($IngresoId,'".$var[tipo_id_paciente]."','".$var[paciente_id]."','".$var[fecha_registro]."','$CausaExterna','".$var[via_ingreso_id]."','".$var[comentarios]."','".$var[departamento]."','1','now()',".$var[usuario_id].",'".$var[departamento_actual]."',".$var[autorizacion_int].",".$var[autorizacion_ext].")";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error ingresos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }

                if(!empty($var[empleador_id]) AND !empty($var[tipo_id_empleador]))
                {
                        $query = "INSERT INTO ingresos_empleadores(
                                                                empleador_id,
                                                                tipo_id_empleador,
                                                                ingreso)
                                            VALUES('".$var[empleador_id]."','".$var[tipo_id_empleador]."',$IngresoId)";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }
                }

                $sqls="INSERT into pacientes_urgencias(
                                                                                        ingreso,
                                                                                        estacion_id,
                                                                                        triage_id,
                                                                                        paciente_urgencia_consultorio_id)
                            VALUES($IngresoId,'".$var[estacion_id]."',".$var[triage_id].",".$var[paciente_urgencia_consultorio_id].")";
                $result = $dbconn->Execute($sqls);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                if(empty($var[autorizacion_ext]))
                {  $var[autorizacion_ext]='NULL';  }
                if(empty($var[autorizacion_int]))
                {  $var[autorizacion_int]='NULL';  }

                $query="SELECT nextval('cuentas_numerodecuenta_seq')";
                $result=$dbconn->Execute($query);
                $Cuenta=$result->fields[0];
                $query = "INSERT INTO cuentas ( numerodecuenta,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                ingreso,
                                                                                plan_id,
                                                                                estado,
                                                                                usuario_id,
                                                                                fecha_registro,
                                                                                tipo_afiliado_id,
                                                                                rango,
                                                                                autorizacion_int,
                                                                                autorizacion_ext,
                                                                                semanas_cotizadas)
                                    VALUES($Cuenta,'".$var[empresa_id]."','".$var[centro_utilidad]."',$IngresoId,".$var[plan_id].",1,".$var[usuario_id].",'now()','".$var[tipo_afiliado_id]."','".$var[rango]."',".$var[autorizacion_int].",".$var[autorizacion_ext].",".$var[semanas_cotizadas].")";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error cuentas";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }

                //si es de SOAT
                if(!empty($var[evento]))
                {
                        $query = "INSERT INTO ingresos_soat( ingreso, evento)
                                                                VALUES($IngresoId,$var[evento])";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en la Base de Datos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }

                        $query = "INSERT INTO soat_consumos_internos (evento,numerodecuenta)
                                            VALUES($var[evento],$Cuenta)";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en la Base de Datos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                }

                $query = "select paciente_remitido_id from pacientes_remitidos
                                            where triage_id=".$var[triage_id]."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en triages";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }
                if(!$result->EOF)
                {
                        $query = "UPDATE pacientes_remitidos SET ingreso=$IngresoId
                                            WHERE paciente_remitido_id=".$result->fields[0]."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en triages";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                }

                $query = "update triages set sw_estado='2',ingreso=$IngresoId
                                    where triage_id=".$var[triage_id]."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en triages";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }

                $query = "UPDATE autorizaciones SET ingreso=$IngresoId
                                    WHERE autorizacion=".$var[autorizacion_int]."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en la Tabal autorizaiones";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                }

                $query = "delete from triages_pendientes_admitir where triage_id=".$var[triage_id]."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "delete autorizaciones_solicitudes_cargos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                }

                $dbconn->CommitTrans();
                //------------nuevo,para ver si es un particular y si es lo crea en terceros
                PlanParticulares($var[plan_id],$var[tipo_id_paciente],$var[paciente_id]);
                //---------------               
                $this->frmError["MensajeError"]="EL PACIENTE FUE ADMITIDO.";
                $this->FormaBuscar();
                return true;

                /*$conte=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                $mod=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                $tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                $met=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                $arg=$_SESSION['ADMISIONES']['RETORNO']['argumentos'];

                $mensaje='EL PACIENTE FUE ADMITIDO.';
                $accion=ModuloGetURL($conte,$mod,$tipo,$met,$arg);
                if(!$this->FormaMensaje($mensaje,'ADMISIONES - DATOS INGRESO',$accion,$boton)){
                            return false;
                }
                return true;*/

                /*$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['contenedor']='app';
                $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['modulo']='Admisiones';
                $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['tipo']='user';
                $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['metodo']='BuscarPaciente';
                $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['argumentos']=array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
                $_SESSION['ADMISIONES']['GARANTE']['PACIENTE']['ingreso']=$IngresoId;

                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Admisiones';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']='GarantesAdmon';
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();

                $Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
                if(!is_object($Paciente))
                {
                                $this->error = "La clase Pacientes no se pudo instanciar";
                                $this->mensajeDeError = "";
                                return false;
                }
                if(!$Paciente->LlamarFormaDatosAcudiente($IngresoId))
                {
                                $this->error = $Paciente->error ;
                                $this->mensajeDeError = $Paciente->mensajeDeError;
                                unset($Paciente);
                                return false;
                }
                else
                {
                                if(!$Paciente->TipoRetorno)
                                {
                                                        $this->salida .= $Paciente->GetSalida();
                                                        unset($Paciente);
                                                        return true;
                                }
                }*/
    }

    /**
    *
    */
    function DatosPendientesAdmitir($triage)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.*, b.departamento as departamento_actual,
                                c.tipo_id_empleador, c.empleador_id
                                FROM triages_pendientes_admitir as a
                                LEFT JOIN triages_pendientes_admitir_empleadores as c
                                on(a.triage_pendiente_admitir_id=c.triage_pendiente_admitir_id),
                                estaciones_enfermeria as b
                                WHERE a.triage_id=$triage and a.estacion_id=b.estacion_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $vars=$result->GetRowAssoc($ToUpper = false);

            $result->Close();
            return $vars;
    }

    /**
    *
    */
    function EstacionEnfermeria()
    {
                IncludeLib("funciones_admision");
                unset($_SESSION['ADMISIONES']['PACIENTE']['estacion_id']);
                $Estaciones=BuscarEstacionesPuntosAdmisiones($_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id']);
                //solo hay una estacion
                if(sizeof($Estaciones)==1)
                {
                            $_SESSION['ADMISIONES']['PACIENTE']['estacion_id']=$Estaciones[0][estacion_id];
                            $_SESSION['ADMISIONES']['PACIENTE']['departamento_actual']=$Estaciones[0][departamento];

                            //busca si hay consultorios y si hay varios muestra el combo sino elige ese
                            $cons = BuscarConsultoriosEstacion($_SESSION['ADMISIONES']['PACIENTE']['estacion_id']);
                            if(sizeof($cons)==1 or empty($cons))
                            {
                                    $_SESSION['ADMISIONES']['PACIENTE']['consultorio']=$cons[0]['paciente_urgencia_consultorio_id'];
                            }
                            else
                            {       //muestra ventana para elegir
                                    $this->FormaConsultorios();
                                    return true;
                            }

                            $this->LlamarIngreso();
                            return true;
                }
                else
                {           //existen varias estaciones
                            $this->FormaElegirEstacion();
                            return true;
                }
    }

    /**
    *
    */
    function LlamarIngreso()
    {
            IncludeLib('funciones_admision');
            
            if($_REQUEST['Estacion']==-1)
            {
                        if($_REQUEST['Estacion']==-1){ $this->frmError["Estacion"]=1; }
                        $this->frmError["MensajeError"]="DEBE ELEGIR LA ESTACION.";
                        if(!$this->FormaElegirEstacion()){
                                        return false;
                        }
                        return true;
            }

            if(empty($_SESSION['ADMISIONES']['PACIENTE']['estacion_id']))
            {
                    $est=explode(',',$_REQUEST['Estacion']);
                    $_SESSION['ADMISIONES']['PACIENTE']['estacion_id']=$est[0];
                    $_SESSION['ADMISIONES']['PACIENTE']['departamento_actual']=$est[1];
            }

            //busca si hay consultorios y si hay varios muestra el combo sino elige ese
            /*$cons = BuscarConsultoriosEstacion($_SESSION['ADMISIONES']['PACIENTE']['estacion_id']);
            if(sizeof($cons)==1)
            {
                    $_SESSION['ADMISIONES']['PACIENTE']['consultorio']=$cons[0]['paciente_urgencia_consultorio_id'];
            }
            elseif(sizeof($cons) >1)
            {       //muestra ventana para elegir
                    $this->FormaConsultorios();
                    return true;
            }*/

            //aqui siempe va a hacer un ingreso
            $_SESSION['ADMISIONES']['INGRESO']['ingreso']=true;

            $this->FormaCapturaIngreso();
            return true;
    }

    function LlamarIngresoCons()
    {
            if($_REQUEST['consultorio']==-1)
            {
                        if($_REQUEST['consultorio']==-1){ $this->frmError["consultorio"]=1; }
                        $this->frmError["MensajeError"]="DEBE ELEGIR EL CONSULTORIO.";
                        if(!$this->FormaConsultorios()){
                                        return false;
                        }
                        return true;
            }

            if(empty($_SESSION['ADMISIONES']['PACIENTE']['consultorio']))
            {
                    $_SESSION['ADMISIONES']['PACIENTE']['consultorio']=$_REQUEST['consultorio'];
            }

            $this->FormaCapturaIngreso();
            return true;
    }

    /**
    * Llama al modulo soat
    * @access public
    * @return boolean
    */
    function LlamarModuloSoat()
    {
                    $_SESSION['SOAT']['PACIENTE']['paciente_id']=$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'];
                    $_SESSION['SOAT']['PACIENTE']['tipo_id_paciente']=$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'];
                    $_SESSION['SOAT']['PACIENTE']['empresa_id']=$_SESSION['ADMISIONES']['EMPRESA'];
                    $_SESSION['SOAT']['PACIENTE']['centro_utilidad']=$_SESSION['ADMISIONES']['CENTROUTILIDAD'];
                    $_SESSION['SOAT']['RETORNO']['argumentos']=array();
                    $_SESSION['SOAT']['RETORNO']['contenedor']='app';
                    $_SESSION['SOAT']['RETORNO']['modulo']='Admisiones';
                    $_SESSION['SOAT']['RETORNO']['tipo']='user';
                    $_SESSION['SOAT']['RETORNO']['metodo']='LlamaFormaIngresoEventos';

                    $this->ReturnMetodoExterno('app','Soat','user','SoatAdmision');
                    return true;
    }

    /**
  * Inserta los datos de ingreso de un paciente
    * @access public
    * @return boolean
    */
    /*function InsertarDatosIngreso()
    {
                    $sw=$this->BuscarSW($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);

                    list($dbconn) = GetDBconn();
                    //es un plan de soat
                    if($sw==1)
                    {
                            if($_REQUEST['ViaIngreso']==-1)
                            {
                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        if(!$this->FormaIngreso()){
                                                        return false;
                                        }
                                        return true;
                            }

                            $query="SELECT rango, tipo_afiliado_id
                                            FROM planes_rangos WHERE plan_id='".$_SESSION['ADMISIONES']['PACIENTE']['plan_id']."'";
                            $result=$dbconn->Execute($query);
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error ingresos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                            }
                            $_REQUEST['Nivel']=$result->fields[0];
                            $_REQUEST['TipoAfiliado']=$result->fields[1];
                            $_REQUEST['Semanas']=0;
                    }
                    else
                    {
                            if($_REQUEST['ViaIngreso']==-1 || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1)
                            {
                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                        if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                                        if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        if(!$this->FormaIngreso()){
                                                        return false;
                                        }
                                        return true;
                            }
                    }


                    if(empty($_REQUEST['Semanas'])) $_REQUEST['Semanas']=0;

                    $CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');

                    $auto=$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'];
                    if(empty($auto))
                    {  $autoInt=1;  }
                    else
                    {  $autoInt=$auto;  }
                    if($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']==true)
                    {  $autoExt=$auto; }
                    else
                    {  $autoExt=0; }

                    $query="SELECT nextval('ingresos_ingreso_seq')";
                    $result=$dbconn->Execute($query);
                    $IngresoId=$result->fields[0];
                    $query = "INSERT INTO ingresos (ingreso,
                                                                                    tipo_id_paciente,
                                                                                    paciente_id,
                                                                                    fecha_ingreso,
                                                                                    causa_externa_id,
                                                                                    via_ingreso_id,
                                                                                    comentario,
                                                                                    departamento,
                                                                                    estado,
                                                                                    fecha_registro,
                                                                                    usuario_id,
                                                                                    departamento_actual,
                                                                                    autorizacion_int,
                                                                                    autorizacion_ext)
                                            VALUES($IngresoId,'".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."',
                                            'now()','$CausaExterna','".$_REQUEST['ViaIngreso']."','".$_REQUEST['Comentarios']."','".$_SESSION['ADMISIONES']['DPTO']."','1','now()',".UserGetUID().",'".$_SESSION['ADMISIONES']['PACIENTE']['departamento']."',$autoInt,$autoExt)";
                    $dbconn->BeginTrans();
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error ingresos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                    }
                    else
                    {
                            $query="SELECT nextval('cuentas_numerodecuenta_seq')";
                            $result=$dbconn->Execute($query);
                            $Cuenta=$result->fields[0];
                            $query = "INSERT INTO cuentas ( numerodecuenta,
                                                                                            empresa_id,
                                                                                            centro_utilidad,
                                                                                            ingreso,
                                                                                            plan_id,
                                                                                            estado,
                                                                                            usuario_id,
                                                                                            fecha_registro,
                                                                                            tipo_afiliado_id,
                                                                                            rango,
                                                                                            autorizacion_int,
                                                                                            autorizacion_ext,
                                                                                            semanas_cotizadas)
                                                                    VALUES($Cuenta,'".$_SESSION['ADMISIONES']['EMPRESA']."','".$_SESSION['ADMISIONES']['CENTROUTILIDAD']."',$IngresoId,
                                                                    ".$_SESSION['ADMISIONES']['PACIENTE']['plan_id'].",'1',".UserGetUID().",'now()','".$_REQUEST['TipoAfiliado']."','". $_REQUEST['Nivel']."',
                                                                    ".$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'].",".$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT'].",".$_REQUEST['Semanas'].")";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error cuentas";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                            }
                            else
                            {
                                        //es un plan de soat
                                        if($sw==1)
                                        {
                                                $query = "INSERT INTO ingresos_soat( ingreso,
                                                                                                                    evento)
                                                                    VALUES($IngresoId,".$_REQUEST['Evento'].")";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al Guardar en la Base de Datos";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }

                                                $query = "INSERT INTO soat_consumos_internos (evento,
                                                                                                                numerodecuenta)
                                                                    VALUES(".$_REQUEST['Evento'].",$Cuenta)";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al Guardar en la Base de Datos";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }
                                        }

                                        if(!empty($_SESSION['ADMISIONES']['PACIENTE']['triage_id']))
                                        {
                                                $query = "select paciente_remitido_id
                                                                    from pacientes_remitidos
                                                                    where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                                                $result=$dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al Guardar en triages";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }
                                                if(!$result->EOF)
                                                {
                                                        $query = "UPDATE pacientes_remitidos SET ingreso=$IngresoId
                                                                            WHERE paciente_remitido_id=".$result->fields[0]."";
                                                        $dbconn->Execute($query);
                                                        if ($dbconn->ErrorNo() != 0) {
                                                                        $this->error = "Error al Guardar en triages";
                                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                        $dbconn->RollbackTrans();
                                                                        return false;
                                                        }
                                                }

                                                $query = "update triages set sw_estado='2'
                                                where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al Guardar en triages";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }

                                                $query = "SELECT tipo_id_paciente FROM pacientes_remitidos
                                                                    WHERE triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                                                $result=$dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al Guardar en triages";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }
                                                if(!$result->EOF)
                                                {
                                                                $query = "UPDATE pacientes_remitidos SET ingreso=$IngresoId
                                                                                    WHERE triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                                                                $dbconn->Execute($query);
                                                                if ($dbconn->ErrorNo() != 0) {
                                                                                $this->error = "Error al Guardar en triages";
                                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                $dbconn->RollbackTrans();
                                                                                return false;
                                                                }
                                                }
                                        }
                                        else
                                        {  $_SESSION['ADMISIONES']['PACIENTE']['triage_id']='NULL';  }

                                        $sqls="INSERT into pacientes_urgencias(
                                                                                                                ingreso,
                                                                                                                estacion_id,
                                                                                                                triage_id)
                                                    VALUES($IngresoId,'".$_SESSION['ADMISIONES']['PACIENTE']['estacion_id']."',".$_SESSION['ADMISIONES']['PACIENTE']['triage_id'].")";
                                        $result = $dbconn->Execute($sqls);
                                        if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al Cargar el Modulo";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                        }

                                        if(!empty($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']))
                                        {
                                                    $query = "UPDATE autorizaciones SET
                                                                        ingreso=$IngresoId
                                                                        WHERE autorizacion=".$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']."";
                                                            $dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0) {
                                                                    $this->error = "Error al Guardar en la Tabal autorizaiones";
                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    $dbconn->RollbackTrans();
                                                                    return false;
                                                    }
                                        }

                                        $dbconn->CommitTrans();

                                        $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['contenedor']='app';
                                        $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['modulo']='Admisiones';
                                        $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['tipo']='user';
                                        $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['metodo']='BuscarPaciente';
                                        $_SESSION['ADMISIONES']['GARANTE']['RETORNO']['argumentos']=array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
                                        $_SESSION['ADMISIONES']['GARANTE']['PACIENTE']['ingreso']=$IngresoId;

                                        $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                                        $_SESSION['PACIENTES']['RETORNO']['modulo']='Admisiones';
                                        $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                                        $_SESSION['PACIENTES']['RETORNO']['metodo']='GarantesAdmon';
                                        $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();

                                        $Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
                                        if(!is_object($Paciente))
                                        {
                                                        $this->error = "La clase Pacientes no se pudo instanciar";
                                                        $this->mensajeDeError = "";
                                                        return false;
                                        }
                                        if(!$Paciente->LlamarFormaDatosAcudiente($IngresoId))
                                        {
                                                        $this->error = $Paciente->error ;
                                                        $this->mensajeDeError = $Paciente->mensajeDeError;
                                                        unset($Paciente);
                                                        return false;
                                        }
                                        else
                                        {
                                                        if(!$Paciente->TipoRetorno)
                                                        {
                                                                                $this->salida .= $Paciente->GetSalida();
                                                                                unset($Paciente);
                                                                                return true;
                                                        }
                                        }
                            }
                    }
    }*/


        /**
        *
        */
        /*function GarantesAdmon()
        {
                unset($_SESSION['PACIENTES']['RETORNO']);

                if(empty($_SESSION['ADMISIONES']['GARANTE']['RETORNO']))
                {
                                $this->error = "ADMISIONES GARANTE";
                                $this->mensajeDeError = "El retorno esta vacio.";
                                return false;
                }

                $this->FormaGarantesAdmon();
                return true;
        }*/

        /**
        *
        */
        /*function InsertarGarantesAdmon()
        {
            $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
            $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
            $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
            $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);

                        if(!$_REQUEST['GaranteId'] AND $_REQUEST['TipoId']==-1 AND !$PrimerNombre AND !$PrimerApellido AND !$_REQUEST['Direccion'] AND !$_REQUEST['Telefono'])
                        {
                                    $conte=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['contenedor'];
                                    $mod=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['modulo'];
                                    $tipo=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['tipo'];
                                    $met=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['metodo'];
                                    $arg=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['argumentos'];

                                    $mensaje='EL PACIENTE FUE ADMITIDO.';
                                    $accion=ModuloGetURL($conte,$mod,$tipo,$met,$arg);
                                    if(!$this->FormaMensaje($mensaje,'ADMISIONES - DATOS GARANTES',$accion,$boton)){
                                                return false;
                                    }
                                    return true;
                        }

                        if(!$_REQUEST['GaranteId'] || $_REQUEST['TipoId']==-1 || !$PrimerNombre || !$PrimerApellido || !$_REQUEST['Direccion'] || !$_REQUEST['Telefono'])
                        {
                                if(!$_REQUEST['GaranteId']){ $this->frmError["GaranteId"]=1; }
                                if($_REQUEST['TipoId']==-1){ $this->frmError["TipoId"]=1; }
                                if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
                                if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
                                if(!$Direccion){ $this->frmError["Direccion"]=1; }
                                if(!$_REQUEST['Telefono']){ $this->frmError["Telefono"]=1; }
                                $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                if(!$this->FormaGarantesAdmon()){
                                                return false;
                                }
                                return true;
                        }
                        list($dbconn) = GetDBconn();
                        $query = "INSERT INTO garantes (
                                                                                ingreso,
                                                                                tipo_id_tercero,
                                                                                garante_id,
                                                                                primer_nombre_garante,
                                                                                segundo_nombre_garante,
                                                                                primer_apellido_garante,
                                                                                segundo_apellido_garante,
                                                                                direccion_garante,
                                                                                telefono_garante)
                                            VALUES(".$_SESSION['ADMISIONES']['PACIENTE']['ingreso'].",'".$_REQUEST['TipoId']."',".$_REQUEST['GaranteId'].",'$PrimerNombre','$SegundoNombre','$PrimerApellido','$SegundoApellido','".$_REQUEST['Direccion']."','".$_REQUEST['Telefono']."')";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en la Base de Datos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }

                        $conte=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['contenedor'];
                        $mod=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['modulo'];
                        $tipo=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['tipo'];
                        $met=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['metodo'];
                        $arg=$_SESSION['ADMISIONES']['GARANTE']['RETORNO']['argumentos'];

                        $mensaje='EL PACIENTE FUE ADMITIDO.';
                        $accion=ModuloGetURL($conte,$mod,$tipo,$met,$arg);
                        if(!$this->FormaMensaje($mensaje,'ADMISIONES - DATOS GARANTES',$accion,$boton)){
                                    return false;
                        }
                        return true;
        }*/

        /**
        *
        */
        function CambiarEstacionAtencion()
        {
                        if($_REQUEST['Estacion']==-1)
                        {
                                $this->frmError["Estacion"]=1;
                                if(!$this->FormaCambiarEstacion()){
                                                return false;
                                }
                                return true;
                        }

                        $x=explode('||',$_REQUEST['Estacion']);

                        list($dbconn) = GetDBconn();
                        $dbconn->BeginTrans();
                        $query = "UPDATE pacientes_urgencias SET estacion_id='".$x[0]."'
                                            WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error UPDATE pacientes_urgencias";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }

                        $query = "UPDATE ingresos SET departamento_actual='".$x[1]."'
                                            WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error UPDATE ingresos ";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }

                        $dbconn->CommitTrans();

                        $mensaje='EL PACIENTE PASO DE LA ESTACION: &nbsp;'.$_SESSION['ADMISIONES']['PACIENTE']['nombre_estacion'].'<br> A LA ESTACION: &nbsp; '.$x[2];
                        $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                        if(!$this->FormaMensaje($mensaje,'ADMISIONES - CAMBIO ESTACION',$accion,$boton)){
                                    return false;
                        }
                        return true;
        }

        /**
        *
        */
        function SacarPaciente()
        {
                    if(empty($_REQUEST['observacion']))
                    {
                            $this->frmError["observacion"]=1;
                            $this->frmError["MensajeError"]='DEBE ESCRIBIR EL MOTIVO.';
                            if(!$this->FormaSacarLista()){
                                return false;
                            }
                            return true;
                    }

                    list($dbconn) = GetDBconn();
                    $dbconn->BeginTrans();
                  $query = "INSERT INTO egresos_no_atencion (
                                                                                    tipo_id_paciente,
                                                                                    paciente_id,
                                                                                    ingreso,
                                                                                    triage_id,
                                                                                    observacion,
                                                                                    fecha_registro,
                                                                                    usuario_id)
                                            VALUES('".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."',
                                            ".$_SESSION['ADMISIONES']['PACIENTE']['ingreso'].",".$_SESSION['ADMISIONES']['PACIENTE']['triage_id'].",
                                            '".$_REQUEST['observacion']."','now()',".UserGetUID().")";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error INSERT INTO egresos_no_atencion";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                    }

                    if(!empty($_SESSION['ADMISIONES']['PACIENTE']['triage_id']))
                    {
                                    $query = "UPDATE triages SET sw_estado=9
                                                      WHERE triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                                    $results = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error update PACIENTES_URGENCIAS";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                    }

                    //sacar listado medico
                    if($_SESSION['ADMISIONES']['PACIENTE']['lista']==1)
                    {
                                    $query = "UPDATE pacientes_urgencias SET sw_estado=9
                                                      WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                                    $results = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error update PACIENTES_URGENCIAS";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }

                                    $query = "UPDATE ingresos SET estado=0
                                                      WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                                    $results = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error update ingresos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }

                                    $query = "UPDATE cuentas SET estado=0
                                                      WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                                    $results = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error update ingresos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                    }
                    elseif($_SESSION['ADMISIONES']['PACIENTE']['lista']==2)
                    {
                                $query = "delete from triages_pendientes_admitir where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "delete autorizaciones_solicitudes_cargos";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                }
                    }

                    $dbconn->CommitTrans();
                    $mensaje='EL PACIENTE FUE SACADO DE LA LISTA';
                    $accion=ModuloGetURL('app','Admisiones','user','FormaBuscar');
                    if(!$this->FormaMensaje($mensaje,'ADMISIONES - SACAR PACIENTE',$accion,$boton)){
                                return false;
                    }
                    return true;
        }

    /**
    * Busca el sw_tipo_plan que corresponde al plan
    * @access public
    * @return array
    * @param int nivel del plan
    */
    function BuscarSW($Responsable)
    {
                    list($dbconn) = GetDBconn();
                    $query = "SELECT  sw_tipo_plan
                                                            FROM planes
                                                            WHERE plan_id='$Responsable'";
                    $results = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Base de Datos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                    }
                    return $results->fields[0];
    }

/**
        * Busca los diferentes tipos de afiliados
        * @access public
        * @return array
        */
        function NombreTipoAfiliado($TipoAfiliado)
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT tipo_afiliado_nombre FROM tipos_afiliado WHERE tipo_afiliado_id='$TipoAfiliado'";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $resulta->Close();
                return $resulta->fields[0];
        }

    /**
    *
    */
    function NombreEmpresa($empresa)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT razon_social FROM empresas WHERE empresa_id='$empresa'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $vars=$result->fields[0];
            $result->Close();
            return $vars;
    }

    /**
    *
    */
    function NivelTriage($triage)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT nivel_triage_id FROM triages WHERE triage_id='$triage'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $vars=$result->fields[0];
            $result->Close();
            return $vars;
    }

    function BuscarAutorizador($int,$ext)
    {
                list($dbconn) = GetDBconn();
                $query = "select b.nombre as autorizador
                                    from autorizaciones as a, system_usuarios as b
                                    where (a.autorizacion=$int OR a.autorizacion=$ext)
                                    and a.usuario_id=b.usuario_id";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else
                {
                        $var=$result->fields[0];
                }
                $result->Close();
                return $var;
    }


//-------------------------REPORTES------------------------------------------
    /**
    *
    */
    function EncabezadoReporte()
    {
        unset($_SESSION['ADMISIONES']['DATOS']);
        list($dbconn) = GetDBconn();
        $query = "select  b.tipo_id_paciente, b.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
                  t.tipo_id_tercero, t.id, t.razon_social, t.direccion, t.telefonos, u.departamento,
                  v.municipio, p.plan_descripcion, p.nombre_cuota_moderadora, p.nombre_copago,
                  w.nombre_tercero, d.tipo_afiliado_nombre, c.rango,
                  f.nombre as usuario, f.usuario_id, c.plan_id,d.tipo_afiliado_id
                                    from pacientes as b, cuentas as c,
                  empresas as t,   tipo_dptos as u, tipo_mpios as v, planes as p, terceros as w,
                  tipos_afiliado as d, system_usuarios as f
                                    where c.ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."
                  and c.empresa_id=t.empresa_id
                  and c.tipo_afiliado_id=d.tipo_afiliado_id
                  and f.usuario_id=".UserGetUID()."
                  and b.tipo_id_paciente='".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."'
                  and c.plan_id=p.plan_id
                  and b.paciente_id='".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."'
                  and t.tipo_pais_id=u.tipo_pais_id
                  and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id
                  and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
              $var=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }

    /**
    *
    */
    function ReporteOrdenServicio()
    {
            if (!IncludeFile("classes/reports/reports.class.php")) {
                    $this->error = "No se pudo inicializar la Clase de Reportes";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
                    return false;
            }

            $var[0]=$_SESSION['ADMISIONES']['DATOS'];
            list($dbconn) = GetDBconn();
                $query = "select a.*,
                                e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                                e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                                f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                                h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
                                j.sw_estado, a.observacion,
                                z.tarifario_id, z.cargo, y.requisitos,
                                x.nombre_tercero as nompro, x.direccion  as dirpro, x.telefono as telpro,
                                s.descripcion as descar, q.evolucion_id, a.semanas_cotizadas, a.plan_id,
                                a.servicio, a.rango, n.observacion as obsapoyo, m.observacion as obsinter,
                                m.especialidad,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
                                from os_ordenes_servicios as a
                                join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                                join cups as f  on(e.cargo_cups=f.cargo)
                                left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                                left join departamentos as l on(g.departamento=l.departamento)
                                left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                                left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                                left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                left join hc_os_solicitudes_interconsultas as m on(m.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                left join especialidades as AB on(AB.especialidad=m.especialidad )
                                left join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
                                join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                                left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                                left join hc_apoyod_requisitos as y on(f.cargo=y.cargo),
                                autorizaciones as j
                                where a.orden_servicio_id=".$_REQUEST['orden']."
                                and a.tipo_afiliado_id='".$_REQUEST['afiliado']."'
                                and a.plan_id='".$_REQUEST['plan']."'
                                and a.tipo_id_paciente='".$_REQUEST['tipoid']."'
                                and a.paciente_id='".$_REQUEST['paciente']."'
                                and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                                and j.sw_estado=0
                                and q.evolucion_id is not null
                                order by e.numero_orden_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                        $var[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                    }
            }
            $result->Close();

            $classReport = new reports;

            if($_REQUEST['pos']==1)
            {
                    $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
                    $reporte=$classReport->PrintReport('pos','app','CentroAutorizacion','ordenservicio',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
                    if(!$reporte){
                            $this->error = $classReport->GetError();
                            $this->mensajeDeError = $classReport->MensajeDeError();
                            unset($classReport);
                            return false;
                    }

                    $resultado=$classReport->GetExecResultado();
                    unset($classReport);


                    if(!empty($resultado[codigo])){
                            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
                    }
                    $this->FormaImpresionSolicitudes();
                    return true;
            }
            else
            {
                    if ($_REQUEST['parametro_retorno'] == '1')
                    {
                            IncludeLib("reportes/ordenservicio");
                            GenerarOrden($var);
                            if(is_array($var))
                            {
                                    $RUTA = $_ROOT ."cache/ordenservicio".$var['orden'].".pdf";
                                    $mostrar ="\n<script language='javascript'>\n";
                                    $mostrar.="var rem=\"\";\n";
                                    $mostrar.="  function abreVentana(){\n";
                                    $mostrar.="    var nombre=\"\"\n";
                                    $mostrar.="    var url2=\"\"\n";
                                    $mostrar.="    var str=\"\"\n";
                                    $mostrar.="    var ALTO=screen.height\n";
                                    $mostrar.="    var ANCHO=screen.width\n";
                                    $mostrar.="    var nombre=\"REPORTE\";\n";
                                    $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                                    $mostrar.="    var url2 ='$RUTA';\n";
                                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                                    $mostrar.="</script>\n";
                                    $this->salida.="$mostrar";
                                    $this->salida.="<BODY onload=abreVentana();>";
                            }
                            $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
                    }
                    else
                    {
                            IncludeLib("reportes/ordenservicio");
                            $vector['orden']=$_REQUEST['orden'];
                            GenerarOrden($vector);
                            $this->FormaImpresionSolicitudes($vector,3);
                            return true;
                    }
            }
    }

    /**
    *
    */
    function Reportesolicitudes()
    {
            if (!IncludeFile("classes/reports/reports.class.php")) {
                    $this->error = "No se pudo inicializar la Clase de Reportes";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
                    return false;
            }

            $var[0]=$_SESSION['ADMISIONES']['DATOS'];
            for($i=0; $i<sizeof($_SESSION['ADMISIONES']['ARR_SOLICITUDES']);$i++)
            {

                    $var[$i+1]=$_SESSION['ADMISIONES']['ARR_SOLICITUDES'][$i];

            }
            $classReport = new reports;

            if($_REQUEST['pos']==1)
            {
                    $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
                    $reporte=$classReport->PrintReport('pos','app','Central_de_Autorizaciones','solicitudes',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
                    if(!$reporte){
                            $this->error = $classReport->GetError();
                            $this->mensajeDeError = $classReport->MensajeDeError();
                            unset($classReport);
                            return false;
                    }

                    $resultado=$classReport->GetExecResultado();
                    unset($classReport);


                    if(!empty($resultado[codigo])){
                            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
                    }
                    $this->FormaImpresionSolicitudes();
                    return true;
            }
            else
            {
                    if ($_REQUEST['parametro_retorno'] == '1')
                    {
                            IncludeLib("reportes/solicitudes");
                            GenerarSolicitud($var);
                            if(is_array($var))
                            {
                                    $RUTA = $_ROOT ."cache/solicitudes".UserGetUID().".pdf";
                                    $DIR="printer.php?ruta=$RUTA";
                                    $RUTA1= GetBaseURL() . $DIR;
                                    $mostrar ="\n<script language='javascript'>\n";
                                    $mostrar.="var rem=\"\";\n";
                                    $mostrar.="  function abreVentana(){\n";
                                    $mostrar.="    var url2=\"\"\n";
                                    $mostrar.="    var width=\"400\"\n";
                                    $mostrar.="    var height=\"300\"\n";
                                    $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                                    $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                                    $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                                    $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                                    $mostrar.="    var url2 ='$RUTA1';\n";
                                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                                    $mostrar.="</script>\n";
                                    $this->salida.="$mostrar";
                                    $this->salida.="<BODY onload=abreVentana();>";
                                }
                            $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
                    }
                    else
                    {
                            IncludeLib("reportes/solicitudes");
                            $vector['evolucion']=$_SESSION['ADMISIONES']['PACIENTE']['evolucion_id'];
                            $vector['ingreso']=$_SESSION['ADMISIONES']['PACIENTE']['ingreso'];
                            $vector['TipoDocumento']=$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'];
                            $vector['Documento']=$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'];
                            $vector['Nombres']=$_SESSION['ADMISIONESRESIONHC']['PACIENTE']['nombre'];
                            GenerarSolicitud($vector);
                            $this->FormaImpresionSolicitudes($vector,2);
                            return true;
                    }
            }

    }

//******************************REPORTES CLAUDIA
    function ReporteFormulaMedica()
    {
        if (!IncludeFile("classes/reports/reports.class.php"))
        {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $criterio=='';
        $uso_controlado = 0;
        if(($_REQUEST['sw_paciente_no_pos']==='0') OR ($_REQUEST['sw_paciente_no_pos']==1))
        {
          $criterio= "AND k.sw_pos = '".$_REQUEST['sw_pos']."' AND a.sw_paciente_no_pos = '".$_REQUEST['sw_paciente_no_pos']."'";
        }
        elseif($_REQUEST['sw_pos']=='1')
        {
          $criterio= "AND k.sw_pos = '".$_REQUEST['sw_pos']."'";
        }
        if ($criterio == '' AND $_REQUEST['sw_uso_controlado']=='1')
        {
          $criterio = "AND k.sw_uso_controlado = '".$_REQUEST['sw_uso_controlado']."'";
          $uso_controlado = 1;
        }
        if($_REQUEST['tipo_formulacion'] == '1')
        {
            $filtroAmb = "and a.sw_ambulatorio = '1'";
        }else
        {
            $filtroAmb = "and a.sw_ambulatorio = '0'";        
        }


                    list($dbconn) = GetDBconn();
                    $query="select n.ingreso, n.fecha_cierre, n.fecha, w.residencia_direccion, w.residencia_telefono,
                    v.tipo_afiliado_id, t.plan_id, sw_tipo_plan,
                    s.rango, v.tipo_afiliado_nombre, p.nombre_tercero,
                    u.nombre_tercero as cliente, r.descripcion as tipo_profesional,
                    p.tipo_id_tercero as tipo_id_medico, p.tercero_id as
                    medico_id, q.tarjeta_profesional,
                    t.plan_descripcion, a.evolucion_id, case when k.sw_pos = 1 then 'POS'
                    else 'NO POS' end as item,
                    a.sw_paciente_no_pos, a.codigo_producto,  h.descripcion as producto,
                    c.descripcion as principio_activo, m.nombre as via, a.dosis, a.unidad_dosificacion,
                    a.tipo_opcion_posologia_id, a.cantidad, l.descripcion, h.contenido_unidad_venta,
                    a.observacion
                    from hc_medicamentos_recetados_hosp as a left join hc_vias_administracion as m
                    on (a.via_administracion_id = m.via_administracion_id)
                    left join hc_evoluciones as n on (a.evolucion_id= n.evolucion_id) left join
                    profesionales_usuarios as o on (n.usuario_id = o.usuario_id) left join terceros as p
                    on (o.tipo_tercero_id = p.tipo_id_tercero AND o.tercero_id = p.tercero_id) left join
                    profesionales as q on (o.tipo_tercero_id = q.tipo_id_tercero AND o.tercero_id = q.tercero_id)
                    left join tipos_profesionales as r on (q.tipo_profesional = r.tipo_profesional)
                    left join cuentas as s on (n.numerodecuenta = s.numerodecuenta) left join planes as t
                    on (s.plan_id = t.plan_id)
                    left join terceros as u on (t.tipo_tercero_id = u.tipo_id_tercero AND t.tercero_id
                    = u.tercero_id) left join tipos_afiliado as v on (s.tipo_afiliado_id = v.tipo_afiliado_id)
                    left join pacientes as w on (w.paciente_id= '".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."'
                    and w.tipo_id_paciente = '".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."'),
                    inv_med_cod_principios_activos as c,
                    inventarios_productos as h, medicamentos as k, unidades as l
                    where       n.estado = '0' and
          a.ingreso = ".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']." and
                    a.sw_estado = '1' and
                    k.cod_principio_activo = c.cod_principio_activo
                    and h.codigo_producto = k.codigo_medicamento and a.codigo_producto = h.codigo_producto
                    and h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id $filtroAmb
                    ".$criterio." order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
            while (!$result->EOF)
            {
              $var[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
        }
        //$result->Close();
          $var[0][uso_controlado]=$uso_controlado;
          $var[0][razon_social]=$_SESSION['CENTRALHOSP']['NOM_EMPRESA'];
          $var[0][tipo_id_tercero]=$_SESSION['ADMISIONES']['TIPO'];
          $var[0][id]=$_SESSION['ADMISIONES']['ID'];
          $var[0][tipo_id]=$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'];
          $var[0][paciente_id]=$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'];
          $var[0][paciente]=$_SESSION['ADMISIONES']['PACIENTE']['nombre'] ;
          
          $select = "SELECT fecha_nacimiento FROM pacientes 
                     WHERE paciente_id = '".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."'
                     AND tipo_id_paciente =  '".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."';";
          
          $result = $dbconn->Execute($select);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $var[0][fecha_nacimiento] = $result->fields[0];
          
          if($_REQUEST['sw_pos']=='1' AND $var[0][sw_tipo_plan]==3)
          {
               if((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND (!empty($var[0][tipo_afiliado_id])))
               {
                    $query="select cuota_moderadora from planes_rangos where plan_id = ".$var[0][plan_id]."
                    AND tipo_afiliado_id = '".$var[0][tipo_afiliado_id]."' AND rango = '".$var[0][rango]."';";

                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    else
                    {
                         $cuotam=$result->GetRowAssoc($ToUpper = false);
                    }
                    $var[0][cuota_moderadora]=$cuotam;
               }
          }

          for($i=0;$i<sizeof($var);$i++)
          {
               $query == '';
               if ($var[$i][tipo_opcion_posologia_id] == 1)
               {
                         $query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 2)
               {
                         $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."' and a.duracion_id = b.duracion_id";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 3)
               {
                         $query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 4)
               {
                         $query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 5)
               {
                         $query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }

               if ($query!='')
               {
                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al buscar en la consulta de medicamentos recetados";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         else
                         {
                              if ($var[$i][tipo_opcion_posologia_id] != 4)
                              {
                                   while (!$result->EOF)
                                   {
                                        $vector[]=$result->GetRowAssoc($ToUpper = false);
                                        $result->MoveNext();
                                   }
                              }
                              else
                              {
                                   while (!$result->EOF)
                                   {
                                        $vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                                        $result->MoveNext();
                                   }
                              }
                         }
               }
               $var[$i][posologia]=$vector;
               unset($vector);
          }

          //hallando la evolucion maxima  caso especial de hospitalizacion.
          $query= "select a.evolucion_id, c.nombre_tercero, c.tipo_id_tercero as tipo_id_medico,
          c.tercero_id as medico_id, d.tarjeta_profesional,e. descripcion as tipo_profesional
          from hc_evoluciones a, profesionales_usuarios b, terceros c, profesionales d,
          tipos_profesionales e where (select max (evolucion_id) from hc_evoluciones
          where ingreso = ".$var[0][ingreso]." and estado ='1') =a.evolucion_id
          and a.usuario_id = b.usuario_id
          and b.tipo_tercero_id = c.tipo_id_tercero AND b.tercero_id = c.tercero_id
          and b.tipo_tercero_id = d.tipo_id_tercero AND b.tercero_id = d.tercero_id
          and d.tipo_profesional = e.tipo_profesional";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               $medico_evol_max=$result->GetRowAssoc($ToUpper = false);
          }
          $var[0][medico_evol_max]=$medico_evol_max;
          $classReport = new reports;
          $i=1;//condicion especial que se le agrega al reporte pos para poder imprimir por pdf.
          if($i==1)
          {
            $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
               $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='CentralImpresionHospitalizacion',$reporte_name='formulamedica',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
          }
          else
          {
            $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
               $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='CentralImpresionHospitalizacion',$reporte_name='formula_medica_hosp',$var,$impresora,$orientacion='P',$unidades='mm',$formato='letter',$html=1);
          }

          if(!$reporte)
          {
               $this->error = $classReport->GetError();
               $this->mensajeDeError = $classReport->MensajeDeError();
               unset($classReport);
               return false;
          }

          $resultado=$classReport->GetExecResultado();
          unset($classReport);

          if(!empty($resultado[codigo]))
          {
               "El PrintReport retorno : " . $resultado[codigo] . "<br>";
          }
          $this->FormaImpresionSolicitudes();
          return true;
    }

//******************FIN REPORTE CLAUDIA
//-----------------------------------------------------------------------------

    /**
    *
    */
    function DatosIngreso($Ingreso)
    {
                list($dbconn) = GetDBconn();
                $query = "select b.poliza, c.fecha_ingreso, c.causa_externa_id, c.via_ingreso_id,
                                    c.comentario, d.tipo_afiliado_id, d.rango, d.plan_id,
                                    d.semanas_cotizadas, e.plan_descripcion
                                    from (ingresos c left join ingresos_soat a on (c.ingreso=a.ingreso))
                                    left join soat_eventos as b on (a.evento=b.evento), cuentas as d,
                                    planes as e
                                    where c.ingreso=$Ingreso and c.ingreso=d.ingreso
                                    and d.plan_id=e.plan_id";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $vars=$result->GetRowAssoc($ToUpper = false);
                return $vars;
    }

    /***
    *
    */
    function ModificarDatosIngreso()
    {
                $Ingreso=$_REQUEST['Ingreso'];
                $PlanId=$_REQUEST['Responsable'];
                $Nivel=$_REQUEST['Nivel'];
                $Poliza=$_REQUEST['Poliza'];
                $PolizaAnt=$_REQUEST['PolizaAnt'];
                $FechaIngreso=$_REQUEST['FechaIngreso'];
                $CausaExterna=$_REQUEST['CausaExterna'];
                $ViaIngreso=$_REQUEST['ViaIngreso'];
                $TipoAfiliado=$_REQUEST['TipoAfiliado'];
                $Comentarios=$_REQUEST['Comentario'];
                $PacienteId=$_REQUEST['PacienteId'];
                $TipoId=$_REQUEST['TipoId'];

                if($_REQUEST['sw']!=1)
                {
                            if($_REQUEST['ViaIngreso']==-1 || $_REQUEST['TipoAfiliado']==-1|| $_REQUEST['Nivel']==-1)
                            {
                                            if($CausaExterna==-1){ $this->frmError["CausaExterna"]=1; }
                                            if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                            if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                                            if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                                            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                            if(!$this->FormaModificarDatosPaciente()){
                                                    return false;
                                            }
                                            return true;
                            }
                }
                if($_REQUEST['sw']==1){
                                        if($_REQUEST['ViaIngreso']==-1 || $Estado==-1 || $_REQUEST['Poliza']==''){
                                                        if($CausaExterna==-1){ $this->frmError["CausaExterna"]=1; }
                                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                                        if($Estado==-1){ $this->frmError["Estado"]=1; }
                                                        if($_REQUEST['Poliza']==''){ $this->frmError["poliza"]=1; }
                                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                                                if(!$this->FormaModificarDatosPaciente()){
                                                                                return false;
                                                                }
                                                                return true;
                                        }
                }

                if(empty($_REQUEST['Semanas'])){  $_REQUEST['Semanas']=0;  }


        list($dbconn) = GetDBconn();
                if($_REQUEST['Poliza'])
                {
                            $query = "UPDATE soat_polizas SET   poliza='".$_REQUEST['Poliza']."'
                                                WHERE poliza='".$_REQUEST['PolizaAnt']."'";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                            }
                    }

                    $f=explode('/',$_REQUEST['FechaIngreso']);
                    $_REQUEST['FechaIngreso']=$f[2].'-'.$f[1].'-'.$f[0];

                    $query = "UPDATE ingresos SET
                                                        fecha_ingreso='".$_REQUEST['FechaIngreso']."',
                                                        via_ingreso_id='".$_REQUEST['ViaIngreso']."',
                                                        comentario='".$_REQUEST['Comentario']."'
                                        WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error ingresos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }

                        $query = "UPDATE cuentas SET
                                                                    tipo_afiliado_id='".$_REQUEST['TipoAfiliado']."',
                                                                    rango='".$_REQUEST['Nivel']."',
                                                                    semanas_cotizadas=".$_REQUEST['Semanas']."
                                            WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['ingreso']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error cuentas";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }

                        $mensaje='SE ACTUALIZARON LOS DATOS DE INGRESO DEL PACIENTE';
                        if(empty($_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']))
                        {  $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));  }
                        else
                        {
                                $contenedor=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['contenedor'];
                                $modulo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['modulo'];
                                $tipo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['tipo'];
                                $metodo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['metodo'];
                                $argumentos=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['argumentos'];
                                $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                        }
                        if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
                                    return false;
                        }
                        return true;
    }

    /**
    * Busca los niveles de atencion
    * @access public
    * @return array
    * @param string plan_id
    */
    function Niveles()
    {
                list($dbconn) = GetDBconn();
                $query="SELECT distinct a.descripcion, a.nivel
                                FROM niveles_atencion as a, centros_remision as b
                                WHERE a.nivel=b.nivel ORDER BY a.nivel";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$result->EOF){
                        $niveles[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                }

                $result->Close();
                return $niveles;
    }

    /**
    *
    */
    function AccionesRemision()
    {
            if($_REQUEST['Buscar'])
            {
                    $this->Busqueda($_REQUEST['criterio'],$_REQUEST['codigo'],$_REQUEST['descripcion']);
                    return true;
            }
            elseif($_REQUEST['Guardar'])
            {
                    $this->GuardarCentro($_REQUEST);
                    return true;
            }
            elseif($_REQUEST['Aceptar'])
            {
                        list($dbconn) = GetDBconn();
                        $dbconn->BeginTrans();
                        $query = "UPDATE hc_conducta_remision SET observacion_remision='".$_REQUEST['observacion']."',
                                            sw_remision=1
                                            WHERE ingreso=".$_REQUEST['ingreso']." and evolucion_id=".$_REQUEST['evolucion']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error hc_conducta_remision ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }

                        $query = "DELETE FROM hc_conducta_remision_centros
                                            WHERE ingreso=".$_REQUEST['ingreso']." and evolucion_id=".$_REQUEST['evolucion']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error hc_conducta_remision_centros ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }

                        foreach($_SESSION['ADMISIONES']['CENTROS'] as $k => $v)
                        {
                                $query = "INSERT INTO hc_conducta_remision_centros (
                                                            ingreso,
                                                            evolucion_id,
                                                            centro_remision)
                                                    VALUES (".$_REQUEST['ingreso'].",".$_REQUEST['evolucion'].",'$k')";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO hc_conducta_remision_centros ";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                        }

                        $dbconn->CommitTrans();
                        $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                        $this->FormaRemisionMedica();
                        return true;
            }
            elseif($_REQUEST['Imprimir'])
            {
                    IncludeLib('funciones_admision');
                    $arr=DatosRemision($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);

            }
    }

    /**
    *
    */
    function GuardarCentro($vector)
    {
            $_REQUEST=$vector;
            foreach($vector as $k => $v)
            {
                    if(substr_count($k,'centro'))
                    {
                            //0 centro 1 des 2 nivel 3 telefono 4 direccion
                            $var=explode('||',$v);
                            $_SESSION['ADMISIONES']['CENTROS'][$var[0]][$var[1]][$var[2]][$var[3]][$var[4]]=$var[0];
                    }
            }
            $this->FormaRemisionMedica('');
            return true;
    }

    /**
    *
    */
    function EliminarCentro()
    {
            unset($_SESSION['ADMISIONES']['CENTROS'][$_REQUEST['codigoEC']]);
            $this->FormaRemisionMedica('');
            return true;
    }

        /**
        *
        */
        function Busqueda($opcion,$codigo,$descripcion)
        {
                    list($dbconn) = GetDBconn();
                    $descripcion =STRTOUPPER($descripcion);
                    if(empty($opcion) AND  empty($codigo))
                    {
                            $opcion=$_REQUEST['criterio'];
                            $codigo=$_REQUEST['codigo'];
                            $descripcion=STRTOUPPER($_REQUEST['descripcion']);
                    }

                    $filtroTipoCodigo = '';
                    $busqueda1 = '';
                    $busqueda2 = '';

                    if ($codigo != '')
                    {  $busqueda1 =" AND centro_remision LIKE '%$codigo%'";  }

                    if ($descripcion != '')
                    {  $busqueda2 ="AND descripcion LIKE '%$descripcion%'";  }

                    if ($opcion != 'Todas')
                    {  $filtroTipoCodigo ="AND nivel='$opcion'";  }

                    if(empty($_REQUEST['conteo']))
                    {
                            $query = "SELECT count(*) FROM centros_remision
                                                WHERE centro_remision is not null
                                                $busqueda1 $busqueda2 $filtroTipoCodigo";
                            $resulta = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }
                            list($this->conteo)=$resulta->fetchRow();
                    }
                    else
                    {  $this->conteo=$_REQUEST['conteo'];  }
                    if(!$_REQUEST['Of'])
                    {
                            $Of='0';
                    }
                    else
                    {
                            $Of=$_REQUEST['Of'];
                            if($Of > $this->conteo)
                            {
                                    $Of=0;
                                    $_REQUEST['Of']=0;
                                    $_REQUEST['paso']=1;
                            }
                    }

                    $query = "SELECT * FROM centros_remision
                                        WHERE centro_remision is not null
                                        $busqueda1 $busqueda2 $filtroTipoCodigo
                                        order by nivel LIMIT ".$this->limit." OFFSET $Of;";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }
                    if(!$resulta->EOF)
                    {
                            while(!$resulta->EOF)
                            {
                                    $var[]=$resulta->GetRowAssoc($ToUpper = false);
                                    $resulta->MoveNext();
                            }
                    }

                    if($this->conteo==='0')
                    {
                                    $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                    }

                    $this->FormaRemisionMedica($var);
                    return true;
        }

//-------------------------------INGRESO DINAMICO-------------------------------------------

    function LlamarFormaIngreso()
    {
            //quiere decir q es insertar un ingreso $_SESSION['ADMISIONES']['INGRESO']['ingreso']
            //quiere decir q es insertar un ingreso tmp $_SESSION['ADMISIONES']['INGRESO']['ingresotmp']

            unset($_SESSION['EMPLEADOR']);
            unset($_SESSION['GARANTE']);
            unset($_SESSION['ADMISIONES']['PACIENTE']['INGRESO']);
            if(empty($_SESSION['ADMISIONES']['RETORNO']))
            {
                            $this->error = "INGRESO ";
                            $this->mensajeDeError = "EL RETORNO DEL INGRESO ESTA VACIO.";
                            return false;
            }

            if(!empty($_SESSION['AUTORIZACIONES1']))
            {
                $this->FormaCapturaIngreso($_REQUEST['SW_APERTURA']);
            }
            else
            {
                $this->EstacionEnfermeria();
            }
            return true;
    }

    function LlamarAcudientes()
    {
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Admisiones';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']=$_REQUEST['metod'];
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array('Nivel'=>$_REQUEST['Nivel'],'Semanas'=>$_REQUEST['Semanas'],'TipoAfiliado'=>$_REQUEST['TipoAfiliado']);
                if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
                {   $_SESSION['PACIENTES']['ACUDIENTES']['TMP']=$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE'];   }
                else
                {  $_SESSION['PACIENTES']['ACUDIENTES']['INGRESO']=$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];  }

                $Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
                if(!is_object($Paciente))
                {
                                $this->error = "La clase Pacientes no se pudo instanciar";
                                $this->mensajeDeError = "";
                                return false;
                }
                if(!$Paciente->LlamarFormaDatosAcudiente($_SESSION['ADMISIONES']['PACIENTE']['INGRESO']))
                {
                                $this->error = $Paciente->error ;
                                $this->mensajeDeError = $Paciente->mensajeDeError;
                                unset($Paciente);
                                return false;
                }
                else
                {
                                if(!$Paciente->TipoRetorno)
                                {
                                                        $this->salida .= $Paciente->GetSalida();
                                                        unset($Paciente);
                                                        return true;
                                }
                }
    }

    function BuscarAcudientes()
    {
            list($dbconn) = GetDBconn();
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
            {
                    $query = "SELECT a.nombre_completo,a.telefono,a.direccion,
                                        b.descripcion, a.contacto_id as contacto
                                        FROM hc_contactos_paciente as a, tipos_parentescos as b
                                        WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                        AND a.tipo_parentesco_id=b.tipo_parentesco_id";
            }
            elseif(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {
                    $query = "SELECT a.nombre_completo,a.telefono,a.direccion,
                                        b.descripcion, a.tmp_contacto_id as contacto
                                        FROM tmp_hc_contactos_paciente as a, tipos_parentescos as b
                                        WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."
                                        AND a.tipo_parentesco_id=b.tipo_parentesco_id";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            while(!$result->EOF)
            {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            return $vars;
    }


    function LlamarGarantesIngreso()
    {
            if(!$this->LlamarFormaGarantes('app','Admisiones','user',$_REQUEST['metod'],array('Nivel'=>$_REQUEST['Nivel'],'Semanas'=>$_REQUEST['Semanas'],'TipoAfiliado'=>$_REQUEST['TipoAfiliado']))){
                            return false;
            }
            return true;
    }

    function LlamarFormaGarantes($contenedor,$modulo,$tipo,$metodo,$argumentos)
    {
            $_SESSION['GARANTE']['RETORNO']['contenedor']=$contenedor;
            $_SESSION['GARANTE']['RETORNO']['modulo']=$modulo;
            $_SESSION['GARANTE']['RETORNO']['tipo']=$tipo;
            $_SESSION['GARANTE']['RETORNO']['metodo']=$metodo;
            $_SESSION['GARANTE']['RETORNO']['argumentos']=$argumentos;
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {   $_SESSION['GARANTE']['TMP']=$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE'];   }
            else
            {   $_SESSION['GARANTE']['INGRESO']=$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];  }

            if(!$this->FormaCrearGarantes()){
                            return false;
            }
            return true;
    }

    function LlamarFormaGarantesExt()
    {
            if(empty($_SESSION['GARANTE']['RETORNO']))
            {
                            $this->error = "GARANTE ";
                            $this->mensajeDeError = "EL GARANTE ESTA VACIO.";
                            return false;
            }

            $this->FormaCrearGarantes();
            return true;
    }

    function RevisionDatosAdicionales($empleador,$acudiente,$garante)
    {
            if(!empty($acudiente))
            {
                    $this->LlamarAcudientes();
                    return true;
            }
            elseif(!empty($garante))
            {
                    $this->LlamarGarantesIngreso();
                    return true;
            }
            elseif(!empty($empleador))
            {
                    $this->LlamarEmpleador();
                    return true;
            }
    }

    function InsertarIngresoInicial($sw_apertura)
    {
                if($sw_apertura=='1')
                {
                    $sql1=", sw_apertura_admision";
                    $sql2=", '1'";
                }
                else
                {
                    $sql1="";
                    $sql2="";
                }
                $CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');
                $auto=$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'];
                if(empty($auto))
                {  $autoInt=1;  }
                else
                {  $autoInt=$auto;  }
                if($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']==true)
                {  $autoExt=$auto; }
                else
                {  $autoExt='NULL'; }
		//CONDICION DEL PACIENTE
		$sql  = "SELECT tipos_condicion_usuarios_planes_id  ";
		$sql .= "FROM pacientes_datos_adicionales ";
		$sql .= "WHERE paciente_id = '".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."' ";
		$sql .= "AND tipo_id_paciente = '".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."';";
                list($dbconn) = GetDBconn();
//echo $sql;
		$rst=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error ingresos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}

		$sql3="";
		$sql4="";
		if(!$rst->EOF AND !empty($rst->fields[0]))
		{
			$con = $rst->fields[0];
			$sql3=",tipos_condicion_usuarios_planes_id";
			$sql4=", $con";
		}
		//FIN CONDICION DEL PACIENTE
                //list($dbconn) = GetDBconn();
                $query="SELECT nextval('ingresos_ingreso_seq')";
                $result=$dbconn->Execute($query);
                $IngresoId=$result->fields[0];
                $query = "INSERT INTO ingresos (ingreso,
                                                                                tipo_id_paciente,
                                                                                paciente_id,
                                                                                fecha_ingreso,
                                                                                causa_externa_id,
                                                                                via_ingreso_id,
                                                                                comentario,
                                                                                departamento,
                                                                                estado,
                                                                                fecha_registro,
                                                                                usuario_id,
                                                                                departamento_actual,
                                                                                autorizacion_int,
                                                                                autorizacion_ext $sql1 $sql3)
                                    VALUES($IngresoId,'".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."','now()','$CausaExterna','1','','".$_SESSION['ADMISIONES']['PACIENTE']['departamento']."','0','now()',".UserGetUID().",'".$_SESSION['ADMISIONES']['PACIENTE']['departamento_actual']."',$autoInt,$autoExt $sql2 $sql4)";
                $_SESSION['ADMISIONES']['PACIENTE']['INGRESO']=$IngresoId;
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error ingresos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                }

                if(!empty($_SESSION['ADMISIONES']['PACIENTE']['id_empleador'])
                        AND !empty($_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']))
                {
                        $query = "INSERT INTO ingresos_empleadores(
                                                                empleador_id,
                                                                tipo_id_empleador,
                                                                ingreso)
                                            VALUES('".$_SESSION['ADMISIONES']['PACIENTE']['id_empleador']."','".$_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']."',$IngresoId)";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                }

                $_SESSION['ADMISIONES']['PACIENTE']['INGRESO']=$IngresoId;
                return true;
    }

    function InsertarPendientesAdmitir()
    {
            list($dbconn) = GetDBconn();
            $query="SELECT nextval('triages_pendientes_admitir_triage_pendiente_admitir_id_seq')";
            $result=$dbconn->Execute($query);

            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['id_empleador'])
                    AND !empty($_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']))
            {
                        $query = "INSERT INTO triages_pendientes_admitir_empleadores(
                                                                empleador_id,
                                                                tipo_id_empleador,
                                                                triage_pendiente_admitir_id)
                                            VALUES('".$_SESSION['ADMISIONES']['PACIENTE']['id_empleador']."','".$_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']."',".$result->fields[0].")";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
            }

            $_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']=$result->fields[0];
            return true;

    }

    function CancelarIngreso()
    {
                list($dbconn) = GetDBconn();
                if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
                {
                        $query = "DELETE FROM tmp_garantes WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error DELETE FROM tmp_garantes";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }

                        $query = "DELETE FROM tmp_hc_contactos_paciente WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error DELETE FROM tmp_hc_contactos_paciente";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }

                        $query = "DELETE FROM triages_pendientes_admitir_empleadores WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error DELETE FROM tmp_hc_contactos_paciente";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                }

                if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
                {
                        $query = "DELETE FROM ingresos WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error DELETE FROM ingresos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                }

                unset($_SESSION['ADMISIONES']['PACIENTE']['INGRESO']);
                unset($_SESSION['ADMISIONES']['PACIENTE']['INGRESO']);

                $_SESSION['ADMISIONES']['RETORNO']['CANCELAR']=true;
                $Contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                $Modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                $Tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                $Metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                $this->ReturnMetodoExterno($Contenedor,$Modulo,$Tipo,$Metodo,$_SESSION['ADMISIONES']['RETORNO']['argumentos']);
                return true;
    }

    function InsertarIngreso()
    {
                if(!empty($_REQUEST['Empleador']) OR !empty($_REQUEST['Acudiente']) OR !empty($_REQUEST['Garante']))
                {
                        $this->RevisionDatosAdicionales($_REQUEST['Empleador'],$_REQUEST['Acudiente'],$_REQUEST['Garante']);
                        return true;
                }

                unset($_SESSION['PACIENTES']);
                $_SESSION['ADMISIONES']['RETORNO']['CANCELAR']=false;
                //no es soat
                if(empty($_SESSION['ADMISIONES']['SOAT']))
                {
                        if($_REQUEST['ViaIngreso']==-1 || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1 || empty($_REQUEST['fechaIngreso']))
                        {
                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                        if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                                        if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        if(!$this->FormaCapturaIngreso()){
                                                        return false;
                                        }
                                        return true;
                        }
                }
                else
                {
                        if($_REQUEST['ViaIngreso']==-1|| empty($_REQUEST['fechaIngreso']))
                        {
                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                        if(empty($_REQUEST['fechaIngreso'])){ $this->frmError["fechaIngreso"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        if(!$this->FormaIngresoEventos()){
                                                        return false;
                                        }
                                        return true;
                        }
                        if(empty($_REQUEST['TipoAfiliado']) && empty($_REQUEST['Nivel']))
                        {
                                        IncludeLib("funciones_admision");
                                        $tipo_afiliado=TiposAfiliado($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                                        if(sizeof($tipo_afiliado)==1)
                                        {   $_REQUEST['TipoAfiliado']=$tipo_afiliado[0][tipo_afiliado_id];   }
                                        else
                                        { $_REQUEST['TipoAfiliado']=0;  }

                                        $niveles=Niveles($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                                        if(sizeof($niveles)==1)
                                        {   $_REQUEST['Nivel']=$niveles[0][rango];  }
                        }
                }

                if(empty($_REQUEST['Semanas'])) $_REQUEST['Semanas']=0;

                $CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');
                
                // Ejemplo de lo que no se debe hacer
                $auto=$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION'];
                
                if(empty($_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']))
                    $auto = $_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'];
                
                if(empty($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']))
                {  $autoInt=1;  }
                else
                {  $autoInt=$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'];  }
                if($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']==true)
                {  $autoExt=$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']; }
                else
                {  $autoExt='NULL'; }

                $f=explode('/',$_REQUEST['fechaIngreso']);
                $_REQUEST['fechaIngreso']=$f[2].'-'.$f[1].'-'.$f[0].' '.date('H:i:s');
                
                includeLib('funciones_admision');
                
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
				
                if($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'] > 100)
                {
                        $query = "UPDATE autorizaciones SET
                                         ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                 WHERE autorizacion=".$auto."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error ingresos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                }
                                
                $query = "UPDATE ingresos SET
                                                        fecha_ingreso='".$_REQUEST['fechaIngreso']."',
                                                        via_ingreso_id='".$_REQUEST['ViaIngreso']."',
                                                        comentario='".$_REQUEST['Comentarios']."',
                                                        estado='1'
                                    WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error ingresos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }

                $query="SELECT nextval('cuentas_numerodecuenta_seq')";
                $result=$dbconn->Execute($query);
                $Cuenta=$result->fields[0];
                $query = "INSERT INTO cuentas (numerodecuenta,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                ingreso,
                                                                                plan_id,
                                                                                estado,
                                                                                usuario_id,
                                                                                fecha_registro,
                                                                                tipo_afiliado_id,
                                                                                rango,
                                                                                autorizacion_int,
                                                                                autorizacion_ext,
                                                                                semanas_cotizadas)
                                    VALUES($Cuenta,'".$_SESSION['ADMISIONES']['EMPRESA']."','".$_SESSION['ADMISIONES']['CENTROUTILIDAD']."',".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'].",'".$_SESSION['ADMISIONES']['PACIENTE']['plan_id']."','1',".UserGetUID().",'now()','".$_REQUEST['TipoAfiliado']."','".$_REQUEST['Nivel']."',$autoInt,$autoExt,".$_REQUEST['Semanas'].")";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error cuentas";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }

                if($_SESSION['ADMISIONES']['TIPO']=='URGENCIAS')
                {
                        if(empty($_SESSION['ADMISIONES']['PACIENTE']['consultorio']))
                        {  $_SESSION['ADMISIONES']['PACIENTE']['consultorio']='NULL';  }
                        if(empty($_SESSION['ADMISIONES']['PACIENTE']['triage_id']))
                        {  $_SESSION['ADMISIONES']['PACIENTE']['triage_id']='NULL';  }
                        $sqls="INSERT into pacientes_urgencias(
                                                                                            ingreso,
                                                                                            estacion_id,
                                                                                            triage_id,
                                                                                            paciente_urgencia_consultorio_id)
                                    VALUES(".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'].",'".$_SESSION['ADMISIONES']['PACIENTE']['estacion_id']."',".$_SESSION['ADMISIONES']['PACIENTE']['triage_id'].",".$_SESSION['ADMISIONES']['PACIENTE']['consultorio'].")";
                        $result = $dbconn->Execute($sqls);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error pacientes_urgencias";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }
                }

                //es soat
                if(!empty($_SESSION['ADMISIONES']['SOAT']))
                {
                            $query = "INSERT INTO ingresos_soat( ingreso,
                                                                                                evento)
                                                VALUES(".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'].",".$_SESSION['ADMISIONES']['SOAT']['evento'].")";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                            }

                            $query = "INSERT INTO soat_consumos_internos (evento,
                                                                                            numerodecuenta)
                                                VALUES(".$_SESSION['ADMISIONES']['SOAT']['evento'].",$Cuenta)";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                            }
                }

                if(!empty($_SESSION['ADMISIONES']['PACIENTE']['triage_id']))
                {
                        $query = "select paciente_remitido_id
                                            from pacientes_remitidos
                                            where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en triages";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                        if(!$result->EOF)
                        {
                                $query = "UPDATE pacientes_remitidos SET ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                                    WHERE paciente_remitido_id=".$result->fields[0]."";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al Guardar en triages";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                }
                        }

                        $query = "update triages set sw_estado='2',
                                            ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                            where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en triages";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                }

                if(!empty($_SESSION['ADMISIONES']['PACIENTE']['REMISION']))
                {
                                $query = "UPDATE pacientes_remitidos SET ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                                    WHERE paciente_remitido_id=".$_SESSION['ADMISIONES']['PACIENTE']['REMISION']."";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al Guardar en triages";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                }
                }

                if(!empty($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']))
                {
                                        $query = "UPDATE autorizaciones SET
                                                                                ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                                                                WHERE autorizacion=".$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']."";
                                                $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                                        $this->error = "Error al Guardar en la Tabal autorizaiones";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                        }
                }
                $dbconn->CommitTrans();
                //------------nuevo,para ver si es un particular y si es lo crea en terceros                
                PlanParticulares($_SESSION['ADMISIONES']['PACIENTE']['plan_id'],$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
                //---------------       
                $contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                $modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                $tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                $metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$_SESSION['ADMISIONES']['RETORNO']['argumentos']);
                return true;
    }


    function InsertarIngresoTMP()
    {
                if(!empty($_REQUEST['Empleador']) OR !empty($_REQUEST['Acudiente']) OR !empty($_REQUEST['Garante']))
                {
                        $this->RevisionDatosAdicionales($_REQUEST['Empleador'],$_REQUEST['Acudiente'],$_REQUEST['Garante']);
                        return true;
                }

                unset($_SESSION['PACIENTES']);
                //no es soat
                if(empty($_SESSION['ADMISIONES']['SOAT']))
                {
                        if($_REQUEST['ViaIngreso']==-1 || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1 || empty($_REQUEST['fechaIngreso']))
                        {
                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                        if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                                        if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        if(!$this->FormaCapturaIngreso()){
                                                        return false;
                                        }
                                        return true;
                        }
                }
                else
                {
                        if($_REQUEST['ViaIngreso']==-1|| empty($_REQUEST['fechaIngreso']))
                        {
                                        if($_REQUEST['ViaIngreso']==-1){ $this->frmError["ViaIngreso"]=1; }
                                        if(empty($_REQUEST['fechaIngreso'])){ $this->frmError["fechaIngreso"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        if(!$this->FormaIngresoEventos()){
                                                        return false;
                                        }
                                        return true;
                        }
                        if(empty($_REQUEST['TipoAfiliado']) && empty($_REQUEST['Nivel']))
                        {
                                        IncludeLib("funciones_admision");
                                        $tipo_afiliado=TiposAfiliado($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                                        if(sizeof($tipo_afiliado)==1)
                                        {   $_REQUEST['TipoAfiliado']=$tipo_afiliado[0][tipo_afiliado_id];   }
                                        else
                                        { $_REQUEST['TipoAfiliado']=0;  }

                                        $niveles=Niveles($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                                        if(sizeof($niveles)==1)
                                        {   $_REQUEST['Nivel']=$niveles[0][rango];  }
                        }
                }

                if(empty($_REQUEST['Semanas'])) $_REQUEST['Semanas']=0;
                if(empty($_SESSION['ADMISIONES']['SOAT']['evento'])){  $_SESSION['ADMISIONES']['SOAT']['evento']='NULL'; }
                $CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');

                $auto=$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION'];
                if(empty($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']))
                {  $autoInt=1;  }
                else
                {  $autoInt=$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'];  }
                if($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']==true)
                {  $autoExt=$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']; }
                else
                {  $autoExt='NULL'; }

                if(empty($_SESSION['ADMISIONES']['PACIENTE']['consultorio']))
                {  $_SESSION['ADMISIONES']['PACIENTE']['consultorio']='NULL';  }

                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query = "INSERT INTO triages_pendientes_admitir(
                                                                            triage_pendiente_admitir_id,
                                                                            tipo_id_paciente,
                                                                            paciente_id,
                                                                            empresa_id,
                                                                            centro_utilidad,
                                                                            via_ingreso_id,
                                                                            comentario,
                                                                            departamento,
                                                                            plan_id,
                                                                            tipo_afiliado_id,
                                                                            rango,
                                                                            semanas_cotizadas,
                                                                            autorizacion_int,
                                                                            autorizacion_ext,
                                                                            fecha_registro,
                                                                            usuario_id,
                                                                            estacion_id,
                                                                            causa_externa_id,
                                                                            evento,
                                                                            triage_id,
                                                                            paciente_urgencia_consultorio_id)
                                    VALUES(".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE'].",'".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."','".$_SESSION['ADMISIONES']['EMPRESA']."','".$_SESSION['ADMISIONES']['CENTROUTILIDAD']."',
                                    '".$_REQUEST['ViaIngreso']."','".$_REQUEST['Comentarios']."','".$_SESSION['ADMISIONES']['PACIENTE']['departamento']."',".$_SESSION['ADMISIONES']['PACIENTE']['plan_id'].",
                                    '".$_REQUEST['TipoAfiliado']."','".$_REQUEST['Nivel']."',".$_REQUEST['Semanas'].",".$autoInt.",".$autoExt.",'now()',".UserGetUID().",'".$_SESSION['ADMISIONES']['PACIENTE']['estacion_id']."','$CausaExterna',".$_SESSION['ADMISIONES']['SOAT']['evento'].",
                                    ".$_SESSION['ADMISIONES']['PACIENTE']['triage_id'].",".$_SESSION['ADMISIONES']['PACIENTE']['consultorio'].")";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }

                $query = "update triages set sw_estado='5'
                                    where triage_id=".$_SESSION['ADMISIONES']['PACIENTE']['triage_id']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en triages";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }

                $dbconn->CommitTrans();

                $contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                $modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                $tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                $metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$_SESSION['ADMISIONES']['RETORNO']['argumentos']);
                return true;
    }

    function LlamaFormaIngresoEventos()
    {
            $_SESSION['ADMISIONES']['SOAT']['evento']=$_SESSION['SOAT']['RETORNO']['evento'];
            $_SESSION['ADMISIONES']['SOAT']['saldo']=$_SESSION['SOAT']['RETORNO']['saldo'];
            $_SESSION['ADMISIONES']['SOAT']['poliza']=$_SESSION['SOAT']['RETORNO']['poliza'];
            $_SESSION['ADMISIONES']['SOAT']['sw']=$_SESSION['SOAT']['RETORNO']['sw'];
            unset($_SESSION['SOAT']);

            list($dbconn) = GetDBconn();
            if(empty($_SESSION['ADMISIONES']['SOAT']['evento']))
            {        //cancelar en el modulo de soat
                            $query = "delete from autorizaciones
                                                where autorizacion=".$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']."";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "delete autorizaciones_solicitudes_cargos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                            }

                            $contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                            $modulo=$_SESSION['ADMISION']['RETORNO']['modulo'];
                            $tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                            $metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                            $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$_SESSION['ADMISIONES']['RETORNO']['argumentos']);
                            return true;
            }

            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']))
            {
                        $query = "INSERT INTO autorizaciones_admision_soat(
                                                                            autorizacion,
                                                                            evento,
                                                                            poliza,
                                                                            saldo)
                        VALUES(".$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'].",".$_SESSION['ADMISIONES']['SOAT']['evento'].",'".$_SESSION['ADMISIONES']['SOAT']['poliza']."',".$_SESSION['ADMISIONES']['SOAT']['saldo'].")";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en autorizaciones_admision_soat";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                        }
            }

            $query = "select a.plan_id, a.rango from planes_rangos as a
                                where a.plan_id=".$_SESSION['ADMISIONES']['PACIENTE']['plan_id']."";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']=$results->fields[0];
            $_SESSION['ADMISIONES']['PACIENTE']['rango']=$results->fields[1];

            if(!$this->FormaIngresoEventos()){
                    return false;
            }
            return true;
    }

    function InsertarDatosGarantes()
    {
                    if(!$_REQUEST['GaranteId'] || $_REQUEST['TipoId']==-1 || !$_REQUEST['PrimerNombre'] || !$_REQUEST['PrimerApellido'] || !$_REQUEST['Direccion'] || !$_REQUEST['Telefono'])
                    {
                            if(!$_REQUEST['GaranteId']){ $this->frmError["GaranteId"]=1; }
                            if($_REQUEST['TipoId']==-1){ $this->frmError["TipoId"]=1; }
                            if(!$_REQUEST['PrimerNombre']){ $this->frmError["PrimerNombre"]=1; }
                            if(!$_REQUEST['PrimerApellido']){ $this->frmError["PrimerApellido"]=1; }
                            if(!$_REQUEST['Direccion']){ $this->frmError["Direccion"]=1; }
                            if(!$_REQUEST['Telefono']){ $this->frmError["Telefono"]=1; }
                            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                            if(!$this->FormaCrearGarantes())
                            {   return false;   }
                            return true;
                    }

                    $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
                    $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
                    $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
                    $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);

                    list($dbconn) = GetDBconn();
                    if(!empty($_SESSION['GARANTE']['TMP']))
                    {
                            $query = "INSERT INTO tmp_garantes (
                                                                                    triage_pendiente_admitir_id,
                                                                                    tipo_id_tercero,
                                                                                    garante_id,
                                                                                    primer_nombre_garante    ,
                                                                                    segundo_nombre_garante    ,
                                                                                    primer_apellido_garante    ,
                                                                                    segundo_apellido_garante    ,
                                                                                    direccion_garante    ,
                                                                                    telefono_garante)
                                                VALUES (".$_SESSION['GARANTE']['TMP'].",'".$_REQUEST['TipoId']."','".$_REQUEST['GaranteId']."','$PrimerNombre','$SegundoNombre','$PrimerApellido','$SegundoApellido','".$_REQUEST['Direccion']."','".$_REQUEST['Telefono']."')";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                            }
                    }
                    else
                    {
                            $query = "INSERT INTO garantes (
                                                                                    ingreso,
                                                                                    tipo_id_tercero,
                                                                                    garante_id,
                                                                                    primer_nombre_garante    ,
                                                                                    segundo_nombre_garante    ,
                                                                                    primer_apellido_garante    ,
                                                                                    segundo_apellido_garante    ,
                                                                                    direccion_garante    ,
                                                                                    telefono_garante)
                                                VALUES (".$_SESSION['GARANTE']['INGRESO'].",'".$_REQUEST['TipoId']."','".$_REQUEST['GaranteId']."','$PrimerNombre','$SegundoNombre','$PrimerApellido','$SegundoApellido','".$_REQUEST['Direccion']."','".$_REQUEST['Telefono']."')";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                            }
                    }

                    $contenedor=$_SESSION['GARANTE']['RETORNO']['contenedor'];
                    $modulo=$_SESSION['GARANTE']['RETORNO']['modulo'];
                    $tipo=$_SESSION['GARANTE']['RETORNO']['tipo'];
                    $metodo=$_SESSION['GARANTE']['RETORNO']['metodo'];
                    $argumentos=$_SESSION['GARANTE']['RETORNO']['argumentos'];
                    $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                    return true;
    }

    function LlamarFormaActualizarGarantes()
    {
                $_REQUEST['GaranteId']=$_REQUEST['arr'][garante_id];
                $_REQUEST['TipoId']=$_REQUEST['arr'][tipo_id_tercero];
                $_REQUEST['GaranteIdAnt']=$_REQUEST['arr'][garante_id];
                $_REQUEST['TipoIdAnt']=$_REQUEST['arr'][tipo_id_tercero];
                $_REQUEST['PrimerNombre']=$_REQUEST['arr'][primer_nombre_garante];
                $_REQUEST['SegundoNombre']=$_REQUEST['arr'][segundo_nombre_garante];
                $_REQUEST['PrimerApellido']=$_REQUEST['arr'][primer_apellido_garante];
                $_REQUEST['SegundoApellido']=$_REQUEST['arr'][segundo_apellido_garante];
                $_REQUEST['Direccion']=$_REQUEST['arr'][direccion_garante];
                $_REQUEST['Telefono']=$_REQUEST['arr'][telefono_garante];
                $_REQUEST['triage_pendiente']=$_REQUEST['arr'][triage_pendiente_admitir_id];
                $_REQUEST['ingreso']=$_REQUEST['arr'][ingreso];

                $this->FormaActualizarGarantes();
                return true;
    }

    function ActualizarDatosGarantes()
    {
                    if(!$_REQUEST['GaranteId'] || $_REQUEST['TipoId']==-1 || !$_REQUEST['PrimerNombre'] || !$_REQUEST['PrimerApellido'] || !$_REQUEST['Direccion'] || !$_REQUEST['Telefono'])
                    {
                            if(!$_REQUEST['GaranteId']){ $this->frmError["GaranteId"]=1; }
                            if($_REQUEST['TipoId']==-1){ $this->frmError["TipoId"]=1; }
                            if(!$_REQUEST['PrimerNombre']){ $this->frmError["PrimerNombre"]=1; }
                            if(!$_REQUEST['PrimerApellido']){ $this->frmError["PrimerApellido"]=1; }
                            if(!$_REQUEST['Direccion']){ $this->frmError["Direccion"]=1; }
                            if(!$_REQUEST['Telefono']){ $this->frmError["Telefono"]=1; }
                            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                            if(!$this->FormaActualizarGarantes())
                            {   return false;   }
                            return true;
                    }

                    $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
                    $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
                    $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
                    $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);

                    list($dbconn) = GetDBconn();
                    if(!empty($_REQUEST['triage_pendiente']))
                    {
                            $query = "UPDATE  tmp_garantes  SET
                                                                                    tipo_id_tercero='".$_REQUEST['TipoId']."',
                                                                                    garante_id='".$_REQUEST['GaranteId']."',
                                                                                    primer_nombre_garante='$PrimerNombre',
                                                                                    segundo_nombre_garante='$SegundoNombre',
                                                                                    primer_apellido_garante='$PrimerApellido',
                                                                                    segundo_apellido_garante='$SegundoApellido',
                                                                                    direccion_garante='".$_REQUEST['Direccion']."',
                                                                                    telefono_garante='".$_REQUEST['Telefono']."'
                                                WHERE triage_pendiente_admitir_id=".$_REQUEST['triage_pendiente']."
                                                AND tipo_id_tercero='".$_REQUEST['TipoIdAnt']."' AND garante_id='".$_REQUEST['GaranteIdAnt']."'";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                            }
                    }
                    else
                    {
                            $query = "UPDATE  garantes SET
                                                                                    tipo_id_tercero='".$_REQUEST['TipoId']."',
                                                                                    garante_id='".$_REQUEST['GaranteId']."',
                                                                                    primer_nombre_garante='$PrimerNombre',
                                                                                    segundo_nombre_garante='$SegundoNombre',
                                                                                    primer_apellido_garante='$PrimerApellido',
                                                                                    segundo_apellido_garante='$SegundoApellido',
                                                                                    direccion_garante='".$_REQUEST['Direccion']."',
                                                                                    telefono_garante='".$_REQUEST['Telefono']."'
                                                WHERE ingreso=".$_REQUEST['ingreso']."
                                                AND tipo_id_tercero='".$_REQUEST['TipoIdAnt']."' AND garante_id='".$_REQUEST['GaranteIdAnt']."'";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar en la Base de Datos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                            }
                    }

                    $_REQUEST='';
                    $this->frmError["MensajeError"]="EL GARANTE SE ACTUALIZO.";
                    $this->FormaCrearGarantes();
                    return true;
    }


    function BuscarGarantes()
    {
            list($dbconn) = GetDBconn();
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
            {
                    $query = "SELECT * FROM garantes
                                        WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."";
            }
            elseif(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {
                    $query = "SELECT * FROM tmp_garantes
                                        WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            while(!$result->EOF)
            {
                            $vars[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
            }
            $result->Close();
            return $vars;
    }

    function EliminarGarante()
    {
            list($dbconn) = GetDBconn();
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
            {
                    $query = "DELETE FROM garantes
                                        WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
                                        AND tipo_id_tercero='".$_REQUEST['tipoGarante']."'
                                        AND garante_id='".$_REQUEST['idGarante']."'";
            }
            elseif(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {
                    $query = "DELETE FROM tmp_garantes
                                        WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."
                                        AND tipo_id_tercero='".$_REQUEST['tipoGarante']."'
                                        AND garante_id='".$_REQUEST['idGarante']."'";
            }

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            $this->frmError["MensajeError"]="EL GARANTE FUE ELIMINADO.";

            $this->FormaCrearGarantes();
            return true;
    }



//--------------------------------EMPLEADOR------------------------------

    function LlamarEmpleador()
    {
            if(!$this->LlamarFormaEmpleador('app','Admisiones','user',$_REQUEST['metod'],array('Nivel'=>$_REQUEST['Nivel'],'Semanas'=>$_REQUEST['Semanas'],'TipoAfiliado'=>$_REQUEST['TipoAfiliado']))){
                            return false;
            }
            return true;
    }

    function LlamarFormaEmpleador($contenedor,$modulo,$tipo,$metodo,$argumentos)
    {
            $_SESSION['EMPLEADOR']['RETORNO']['contenedor']=$contenedor;
            $_SESSION['EMPLEADOR']['RETORNO']['modulo']=$modulo;
            $_SESSION['EMPLEADOR']['RETORNO']['tipo']=$tipo;
            $_SESSION['EMPLEADOR']['RETORNO']['metodo']=$metodo;
            $_SESSION['EMPLEADOR']['RETORNO']['argumentos']=$argumentos;
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {   $_SESSION['EMPLEADOR']['TMP']=$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE'];   }
            else
            {   $_SESSION['EMPLEADOR']['INGRESO']=$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];  }

            if(!$this->FormaEmpleador()){
                            return false;
            }
            return true;
    }

    function Empleadores()
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT tipo_id_empleador,empleador_id,nombre FROM empleadores ORDER BY nombre";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                        $vars[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                }

                $result->Close();
                return $vars;
    }

    function GuardarEmpleador()
    {
                if($_REQUEST['empleador']==-1)
                {
                        if($_REQUEST['tipoID']==-1)
                        {
                                $this->frmError["tipoID"]=1;
                                $this->frmError["MensajeError"]="ERROR DATOS VACIOS: DEBE ELEGIR EL TIPO DE IDENTIFICACION DEL EMPLEADOR.";
                                $this->FormaEmpleador();
                                return true;
                        }
                        if(empty($_REQUEST['numero']))
                        {
                                $this->frmError["numero"]=1;
                                $this->frmError["MensajeError"]="ERROR DATOS VACIOS: DEBE DIGITAR EL NUMERO DE IDENTIFICACION DEL EMPLEADOR.";
                                $this->FormaEmpleador();
                                return true;
                        }
                        if(empty($_REQUEST['nombre']))
                        {
                                $this->frmError["nombre"]=1;
                                $this->frmError["MensajeError"]="ERROR DATOS VACIOS: DEBE DIGITAR EL NOMBRE DEL EMPLEADOR.";
                                $this->FormaEmpleador();
                                return true;
                        }

                        if(empty($_REQUEST['pais']) OR empty($_REQUEST['dpto']) OR empty($_REQUEST['mpio']))
                        {
                                    $_REQUEST['pais']='NULL';
                                    $_REQUEST['mpio']='NULL';
                                    $_REQUEST['dpto']='NULL';
                        }
                        else
                        {
                                    $_REQUEST['pais']="'".$_REQUEST['pais']."'";
                                    $_REQUEST['mpio']="'".$_REQUEST['mpio']."'";
                                    $_REQUEST['dpto']="'".$_REQUEST['dpto']."'";
                        }

                        $tipoEmp=$_REQUEST['tipoID'];
                        $idEmp=$_REQUEST['numero'];
                        $_REQUEST['nombre']=strtoupper($_REQUEST['nombre']);

                        list($dbconn) = GetDBconn();
                        $query = "INSERT INTO empleadores(
                                                                empleador_id,
                                                                tipo_id_empleador,
                                                                nombre,
                                                                direccion,
                                                                telefono,
                                                                usuario_id,
                                                                tipo_pais_id,
                                                                tipo_dpto_id,
                                                                tipo_mpio_id)
                                            VALUES('".$_REQUEST['numero']."','".$_REQUEST['tipoID']."','".$_REQUEST['nombre']."','".$_REQUEST['direccion']."','".$_REQUEST['telefono']."',".UserGetUID().",".$_REQUEST['pais'].",".$_REQUEST['dpto'].",".$_REQUEST['mpio'].")";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                }
                else
                {
                        $v=explode('||',$_REQUEST['empleador']);
                        $tipoEmp=$v[0];
                        $idEmp=$v[1];
                }

                list($dbconn) = GetDBconn();
                if(!empty($_SESSION['EMPLEADOR']['INGRESO']))
                {
                        $query = "INSERT INTO ingresos_empleadores(
                                                                empleador_id,
                                                                tipo_id_empleador,
                                                                ingreso)
                                            VALUES('".$idEmp."','".$tipoEmp."',".$_SESSION['EMPLEADOR']['INGRESO'].")";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                }
                elseif(!empty($_SESSION['EMPLEADOR']['TMP']))
                {
                        $query = "INSERT INTO triages_pendientes_admitir_empleadores(
                                                                empleador_id,
                                                                tipo_id_empleador,
                                                                triage_pendiente_admitir_id)
                                            VALUES('".$idEmp."','".$tipoEmp."',".$_SESSION['EMPLEADOR']['TMP'].")";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                }

                $contenedor=$_SESSION['EMPLEADOR']['RETORNO']['contenedor'];
                $modulo=$_SESSION['EMPLEADOR']['RETORNO']['modulo'];
                $tipo=$_SESSION['EMPLEADOR']['RETORNO']['tipo'];
                $metodo=$_SESSION['EMPLEADOR']['RETORNO']['metodo'];
                $argumentos=$_SESSION['EMPLEADOR']['RETORNO']['argumentos'];
                $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                return true;
    }

    function BuscarEmpleadores()
    {
                list($dbconn) = GetDBconn();
                if(empty($_SESSION['EMPLEADOR']['TMP']) AND (empty($_SESSION['EMPLEADOR']['INGRESO'])))
                {
                        if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
                        {   $_SESSION['EMPLEADOR']['TMP']=$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE'];   }
                        else
                        {   $_SESSION['EMPLEADOR']['INGRESO']=$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];  }
                }

                if(!empty($_SESSION['EMPLEADOR']['INGRESO']))
                {
                        $query = "SELECT a.tipo_id_empleador,a.empleador_id,a.nombre
                                            FROM empleadores as a, ingresos_empleadores as b
                                            WHERE b.ingreso=".$_SESSION['EMPLEADOR']['INGRESO']."
                                            AND b.empleador_id=a.empleador_id
                                            AND b.tipo_id_empleador=a.tipo_id_empleador";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }

                            while(!$result->EOF)
                            {
                                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                                    $result->MoveNext();
                            }
                }
                elseif(!empty($_SESSION['EMPLEADOR']['TMP']))
                {
                        $query = "SELECT a.tipo_id_empleador,a.empleador_id,a.nombre
                                            FROM empleadores as a, triages_pendientes_admitir_empleadores as b
                                            WHERE b.triage_pendiente_admitir_id=".$_SESSION['EMPLEADOR']['TMP']."
                                            AND b.empleador_id=a.empleador_id
                                            AND b.tipo_id_empleador=a.tipo_id_empleador";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }

                            while(!$result->EOF)
                            {
                                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                                    $result->MoveNext();
                            }
                }

                return $vars;
    }

    function EliminarEmpleador()
    {
            list($dbconn) = GetDBconn();
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
            {
                    $query = "DELETE FROM ingresos_empleadores
                                        WHERE ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."";
            }
            elseif(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {
                    $query = "DELETE FROM triages_pendientes_admitir_empleadores
                                        WHERE triage_pendiente_admitir_id=".$_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']."";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            $this->frmError["MensajeError"]="EL EMPLEADOR FUE ELIMINADO.";

            $this->FormaEmpleador();
            return true;
    }

    function VerificarDatosObligatoriosRips($tipo,$id)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT count(sexo_id) FROM pacientes
                                WHERE tipo_id_paciente='$tipo' AND paciente_id='$id'
                                AND (sexo_id is null OR primer_apellido is null
                                OR primer_nombre is null OR fecha_nacimiento is null
                                OR tipo_dpto_id is null OR tipo_mpio_id is null
                                OR zona_residencia is null)";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
            }
/*          while(!$result->EOF)
            {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }*/
            $vars=$result->fields[0];
            $result->Close();
            return $vars;
    }
	function BuscarColorTriage($NivelTriage)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM niveles_triages
					  WHERE nivel_triage_id = ".$NivelTriage."";
					  
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$Nivel =$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $Nivel;
		}

//------------------------------------------------------------------------------
}//fin clase user
?>

