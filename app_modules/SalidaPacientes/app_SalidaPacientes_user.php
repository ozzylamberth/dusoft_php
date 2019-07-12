<?php

/**
 * $Id: app_SalidaPacientes_user.php,v 1.12 2006/10/18 18:26:27 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_SalidaPacientes_user extends classModulo
{

    var $limit;
    var $conteo;

		function app_SalidaPacientes_user()
		{
    		$this->limit=GetLimitBrowser();
				return true;
		}

     /**
     *
     */
     function main()
     {
          unset($_SESSION['SALIDA']);
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $query = "select a.empresa_id,a.centro_utilidad, a.sw_todos_cu,
                                   a.descripcion as descripcion3,a.prefijo_fac_credito, a.prefijo_fac_contado,
                                   b.razon_social as descripcion1, c.descripcion as descripcion2
                                   from puntos_salidas_pacientes as a, empresas as b, centros_utilidad as c ,
                                   userpermisos_salidas_pacientes as d
                                   where a.punto_salida_paciente_id=d.punto_salida_paciente_id
                                   and d.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id
                                   and a.empresa_id=c.empresa_id
                                   and a.centro_utilidad=c.centro_utilidad  order by a.empresa_id, a.centro_utilidad";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }
          while ($data = $resulta->FetchRow()) {
               $cuenta[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]= $data;
               $seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['cuenta_filtro_id']]=1;
          }
          $resulta->Close();

          $url[0]='app';
          $url[1]='SalidaPacientes';
          $url[2]='user';
          $url[3]='Menus';
          $url[4]='Salida';
          $arreglo[0]='EMPRESA';
          $arreglo[1]='CENTRO UTILIDAD';
          $arreglo[2]='SALIDA';
          $_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['arreglo']=$arreglo;
          $_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['salida']=$cuenta;
          $_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['url']=$url;
          $_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['puntos']=$seguridad;

          $this->salida.= gui_theme_menu_acceso('SALIDA',$_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['arreglo'],$_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['salida'],$_SESSION['SEGURIDAD']['SALIDA']['FILTRO']['url'],ModuloGetURL('system','Menu'));
          return true;
     }


     /**
     * Llama la forma del menu de salida pacientes
     * @access public
     * @return boolean
     */
     function Menus()
     {
          if(empty($_SESSION['SALIDA']['EMPRESA']))
          {
               $_SESSION['SALIDA']['EMPRESA']=$_REQUEST['Salida']['empresa_id'];
               $_SESSION['SALIDA']['NOMEMPRESA']=$_REQUEST['Salida']['descripcion1'];
               $_SESSION['SALIDA']['CENTROUTILIDAD']=$_REQUEST['Salida']['centro_utilidad'];
               $_SESSION['SALIDA']['CU']=$_REQUEST['Salida']['sw_todos_cu'];
               $_SESSION['SALIDA']['CREDITO']=$_REQUEST['Salida']['prefijo_fac_credito'];
               $_SESSION['SALIDA']['CONTADO']=$_REQUEST['Salida']['prefijo_fac_contado'];
          }

          if(!$this->FormaMenus()){
               return false;
          }
          return true;
     }


	/**
	*
	*/
	function LlamarBuscarPaciente()
	{
          unset($_SESSION['SALIDA']['URG']);
          unset($_SESSION['SALIDA']['HOS']);
          unset($_SESSION['SALIDA']['BUSQUEDA']);

          if($_REQUEST['tipo_salida']=='URG')
          {
               $_SESSION['SALIDA']['URG']=true;
               $_SESSION['SALIDA']['TITULO']='URGENCIAS';
          }
          elseif($_REQUEST['tipo_salida']=='HOS')
          {
               $_SESSION['SALIDA']['HOS']=true;
               $_SESSION['SALIDA']['TITULO']='HOSPITALIZACION';
          }elseif($_REQUEST['tipo_salida']=='CIR')
          {
               $_SESSION['SALIDA']['CIR']=true;
               $_SESSION['SALIDA']['TITULO']='CIRUGIA';
          }

          $this->FormaBuscar();
          return true;
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

     function BuscarPacienteSalida()
	{
          unset($_SESSION['SALIDA']['BUSQUEDA']);
          $_SESSION['SALIDA']['BUSQUEDA']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
          $_SESSION['SALIDA']['BUSQUEDA']['paciente_id']=$_REQUEST['Documento'];
          $_SESSION['SALIDA']['BUSQUEDA']['Nombres']=$_REQUEST['Nombres'];
          $_SESSION['SALIDA']['BUSQUEDA']['prefijo']=strtoupper($_REQUEST['prefijo']);
          $_SESSION['SALIDA']['BUSQUEDA']['historia']=$_REQUEST['historia'];

          if($_REQUEST['TipoDocumento']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'] AND !$_REQUEST['nombre'])
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

          IncludeLib("funciones_admision");
          $tipo_id_paciente=$_REQUEST['Tipo'];
          $paciente_id=$_REQUEST['Documento'];
          $nombres = strtoupper($_REQUEST['nombre']);

          $NUM=$_REQUEST['Of'];
          if(!$NUM)
          {   $NUM='0';   }

          $var='';
          if(!empty($_SESSION['SALIDA']['URG']))
          {
               if(empty($_REQUEST['paso']))
               {
                    $vars=PacienteSalidaUrgencias($_SESSION['SALIDA']['EMPRESA'],$tipo_id_paciente,$paciente_id,$prefijo,$historia,$nombres,$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU'],'','');
                    $_SESSION['SPY']=sizeof($vars);
               }
               $var=PacienteSalidaUrgencias($_SESSION['SALIDA']['EMPRESA'],$tipo_id_paciente,$paciente_id,$prefijo,$historia,$nombres,$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU'],$this->limit,$NUM);
               $tipoPac=10;
               if(!empty($var))
               {
                    $this->FormaBuscar($var,$tipoPac);
                    return true;
               }
          }
          elseif(!empty($_SESSION['SALIDA']['HOS']))
          {		
          	//salida de hospitalizacion
               if(empty($_REQUEST['paso']))
               {
                    $var=PacienteSalidaEstacion($_SESSION['SALIDA']['EMPRESA'],$tipo_id_paciente,$paciente_id,$prefijo,$historia,$nombres,$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU'],'','');
                    $_SESSION['SPY']=sizeof($var);
               }
               $var=PacienteSalidaEstacion($_SESSION['SALIDA']['EMPRESA'],$tipo_id_paciente,$paciente_id,$prefijo,$historia,$nombres,$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU'],$this->limit,$NUM);
               $tipoPac=9;
               if(!empty($var))
               {
                    $this->FormaBuscar($var,$tipoPac);
                    return true;
               }
          }elseif(!empty($_SESSION['SALIDA']['CIR']))
          {
           	//salida de Cirugia
               if(empty($_REQUEST['paso']))
               {
                    $var=PacienteSalidaCirugia($_SESSION['SALIDA']['EMPRESA'],$tipo_id_paciente,$paciente_id,$prefijo,$historia,$nombres,$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU'],'','');
                    $_SESSION['SPY']=sizeof($var);
               }
               $var=PacienteSalidaCirugia($_SESSION['SALIDA']['EMPRESA'],$tipo_id_paciente,$paciente_id,$prefijo,$historia,$nombres,$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU'],$this->limit,$NUM);
               $tipoPac=11;
               if(!empty($var))
               {
                    $this->FormaBuscar($var,$tipoPac);
                    return true;
               }
          }

          $this->frmError["MensajeError"]="EL PACIENTE NO SE ENCUENTRA.";
          $this->FormaBuscar('','');
          return true;
	}

	function BuscarSolcitudesImpresion($ingreso)
	{
          IncludeLib("funciones_central_impresion");
          $vector1=GetMedicamentosIngreso($ingreso);
          if(!empty($vector1))
          {  return true;  }

          $arr=BuscarSolicitudesIngreso($ingreso);
          if(!empty($arr))
          {  return true;  }

          $var=BuscarOrdenesIngreso($ingreso);
          if(!empty($var))
          {  return true;  }

          $vec=Consulta_Incapacidades_GeneradasIngreso($ingreso);
          if(!empty($vec))
          {  return true;  }

          return false;
	}

	function UbicacionPacienteEstacion()
	{
          $this->FormaUbicacionEstacion($_REQUEST['paciente'],$_REQUEST['tipoid'],$_REQUEST['nombre'],$_REQUEST['nombre_estacion'],$_REQUEST['ingreso']);
          return true;
	}

	function LlamarModificarDatosPaciente()
	{
          unset($_SESSION['ADMISIONES']['PACIENTE']);
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['contenedor']='app';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['modulo']='SalidaPacientes';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['tipo']='user';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['metodo']='BuscarPacienteSalida';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['argumentos']=array('Tipo'=>$_REQUEST['tipoid'],'Documento'=>$_REQUEST['paciente']);

          $this->ReturnMetodoExterno('app','Admisiones','user','ModificarDatosPacienteExt');
          return true;
	}

	function LlamarImpresionSolicitudes()
	{
          unset($_SESSION['ADMISIONES']['PACIENTE']);
          unset($_SESSION['ADMISIONES']['DATOS']);

          // Cambio Realizado por Tizziano Perea.
          $_SESSION['SALIDAPACIENTES'] = true;
          
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
          $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']=$_REQUEST['evolucion'];
          $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['contenedor']='app';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['modulo']='SalidaPacientes';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['tipo']='user';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['metodo']='BuscarPacienteSalida';
          $_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['argumentos']=array('Tipo'=>$_REQUEST['tipoid'],'Documento'=>$_REQUEST['paciente']);

          $this->ReturnMetodoExterno('app','Admisiones','user','ImpresionSolicitudesExt');
          return true;
	}

	function LlamarConsultaTriage()
	{
          unset($_SESSION['ADMISIONES']['PACIENTE']);
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
          $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];

          $_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['contenedor']='app';
          $_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['modulo']='SalidaPacientes';
          $_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['tipo']='user';
          $_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['metodo']='BuscarPacienteSalida';
          $_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['argumentos']=array('Tipo'=>$_REQUEST['tipoid'],'Documento'=>$_REQUEST['paciente']);

          $this->ReturnMetodoExterno('app','Admisiones','user','ConsultaTriageExt');
          return true;
	}

	function VerCuenta()
	{
          //agregado por lorena pues no se estan generando las facturas correctamente
          
          unset($_SESSION['FACTURACION']['arreglo']);
          list($dbconn) = GetDBconn();         
          
          $query = "select a.*,(a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                           c.tipo_id_paciente,c.paciente_id,  c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                                    a.rango,case when a.estado=1 then 'A' when a.estado=2 then 'I' when a.estado=3 then 'C' end as estado
                    from cuentas as a, ingresos as b, pacientes as c
                    where a.numerodecuenta='".$_REQUEST['cuenta']."'                                     
                    and a.ingreso=b.ingreso and b.tipo_id_paciente=c.tipo_id_paciente and
                    b.paciente_id=c.paciente_id";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error al buscar";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          return false;
          }else{
            if($result->RecordCount()>0){
              $var=$result->GetRowAssoc($ToUpper = false);                          
              $_SESSION['FACTURACION']['arreglo']=$var;
            }
          }          
          $query = "SELECT B.prefijo, B.factura_fiscal, B.sw_tipo, B.empresa_id
                        FROM fac_facturas A, fac_facturas_cuentas B
                        WHERE B.numerodecuenta='".$_REQUEST['cuenta']."'
                        AND A.prefijo=B.prefijo
                        AND A.factura_fiscal=B.factura_fiscal
                        AND A.empresa_id=B.empresa_id 
                        AND A.estado='0';";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error al buscar en fac_facturas_cuentas";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          return false;
          }else{
            if($result->RecordCount()>0){
              $var=$result->GetRowAssoc($ToUpper = false);                          
              $tipo_factura=$var;
            }
          }          

          //fin agregado
  
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
          $_SESSION['FACTURACION']['EMPRESA']=$_SESSION['SALIDA']['EMPRESA'];
          $_SESSION['FACTURACION']['RETORNO']['contenedor']='app';
          $_SESSION['FACTURACION']['RETORNO']['modulo']='SalidaPacientes';
          $_SESSION['FACTURACION']['RETORNO']['tipo']='user';
          $_SESSION['FACTURACION']['RETORNO']['metodo']='BuscarPacienteSalida';
          $_SESSION['FACTURACION']['RETORNO']['argumentos']=array('Tipo'=>$_REQUEST['tipoid'],'Documento'=>$_REQUEST['paciente']);

          $this->ReturnMetodoExterno('app','Facturacion_Fiscal','user','LlamadoFacturacion',array('tipo_factura'=>$tipo_factura));
          return true;
	}

	function LlamarRemisionMedica()
	{
          unset($_SESSION['ADMISIONES']['PACIENTE']);
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];
          $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']=$_REQUEST['evolucion'];
          $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];
          $_SESSION['ADMISIONES']['NOMEMPRESA']=$_SESSION['SALIDA']['NOMEMPRESA'];

          $_SESSION['ADMISIONES']['REMISION']['RETORNO']['contenedor']='app';
          $_SESSION['ADMISIONES']['REMISION']['RETORNO']['modulo']='SalidaPacientes';
          $_SESSION['ADMISIONES']['REMISION']['RETORNO']['tipo']='user';
          $_SESSION['ADMISIONES']['REMISION']['RETORNO']['metodo']='BuscarPacienteSalida';
          $_SESSION['ADMISIONES']['REMISION']['RETORNO']['argumentos']=array('Tipo'=>$_REQUEST['tipoid'],'Documento'=>$_REQUEST['paciente']);

          $this->ReturnMetodoExterno('app','Admisiones','user','RemisionMedicaExt');
          return true;
	}

	function LlamarFormaSalidaPaciente()
	{
          $this->FormaSalidaPaciente($_REQUEST['tipoid'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['ingreso']);
          return true;
	}

	function DarSalidaPaciente()
	{
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          $query = "UPDATE ingresos SET estado='2',fecha_cierre='now()'
                                   WHERE ingreso=".$_REQUEST['ingreso']."";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $dbconn->RollbackTrans();
               $resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
               return $resultado;
          }

          $query = "INSERT INTO ingresos_salidas (ingreso,fecha_registro,usuario_id,observacion_salida)
                                   VALUES(".$_REQUEST['ingreso'].",'now()',".UserGetUID().",'".$_REQUEST['observacion']."')";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $dbconn->RollbackTrans();
               $resultado['mensaje']='ERROR EN INSERTAR'. $dbconn->ErrorMsg();
               return $resultado;
          }

          $dbconn->CommitTrans();

          unset($_REQUEST['Tipo']);
          unset($_REQUEST['Documento']);

          $this->frmError["MensajeError"]="TERMINADO EL PROCESO DE SALIDA DEL PACIENTE .";
          $this->FormaBuscar();
          return true;
	}
//------------------------------------------------------------------------------
}//fin clase user
?>

