<?php
  /******************************************************************************
  * $Id: BuscarCargoIYM.class.php,v 1.8 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.8 $ 
	* 
	* @autor
  ********************************************************************************/
  IncludeClass('BuscarCargoIYM','','app','Cuentas');
  IncludeClass('app_Cuentas_user','','app','Cuentas');
  
	class BuscarCargoIYM
	{
		function BuscarCargoIYM(){}
		/**********************************************************************************
		* 
		* 
		* @return array 
		***********************************************************************************/
			/**
			*Departamentos
			*/
		function Departamentos($EmpresaId,$CentroU)
		{
				if($CentroU)
				{ $CU="and centro_utilidad='$CentroU'"; }

				list($dbconn) = GetDBconn();
				$query = "SELECT a.departamento,a.descripcion
										FROM departamentos as a, servicios as b 
										WHERE a.empresa_id='$EmpresaId'
										$CU
										and a.servicio=b.servicio and b.sw_asistencial='1'";
				$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else{
						if($result->EOF){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "La tabla maestra 'departamentos' esta vacia ";
							return false;
						}
							while (!$result->EOF) {
								$vars[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
							}
					}
			$result->Close();
			return $vars;
		}
			/**
				* Busca las bodegas asociadas a un usuario
				*
				* @param integer UsuarioId
				*/
			function BuscarBodegasPorUsuarioId($EmpresaId,$CU,$UsuarioId)
			{
					if($sql)
					{
							$CU="AND b.centro_utilidad='".$CU."'";
					}
					list($dbconn) = GetDBconn();
					$query="
							SELECT
									b.*
							FROM
									bodegas b,
									bodegas_usuarios bu
							WHERE
									b.empresa_id=bu.empresa_id AND
									b.centro_utilidad=bu.centro_utilidad AND
									b.bodega=bu.bodega AND
									bu.usuario_id=$UsuarioId AND
									b.empresa_id='$EmpresaId'
									$sql
							ORDER BY
									b.descripcion";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$result->EOF)
					{
							$var[]= $result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
			}//Fin BuscarBodegasPorUsuarioId
    
//----------------------INSUMOS Y MEDICAMENTOS-------------------------------------------
    /**
    *
    */
    function InsertarInsumos()
    {
      foreach($_SESSION['CUENTAS']['ADD_IYM'] AS $i => $v)
      {
        $Departamento = $v[departamento];
        $precio = $v[precio];
        $Codigo = $v[codigo];
        $Cantidad = $v[cantidad];
        $Cuenta = $v[Cuenta];
        $PlanId = $v[PlanId];
        $Ingreso = $v[Ingreso];
        $TipoId = $v[TipoId];
        $PacienteId = $v[PacienteId];
        $empresa = $v[EmpresaId];
        $cu = $v[CU];
        $bodega = $v[Bodega];
        $f = explode('/',$v[fecha_cargo]);
        $fecha_cargo = $f[2].'-'.$f[1].'-'.$f[0];

        $SystemId = UserGetUID();

        if(!$Cantidad || !$Codigo || $fecha_cargo === '--' || !$fecha_cargo)
        {
          if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
          if(!$Codigo){ $this->frmError["Codigo"]=1; }
          if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
          $this->frmError["MensajeError"]="Faltan datos obligatorios.";
          $mensaje = "Faltan datos obligatorios.";
          $titulo = "Agregar IyM";
          $accion = SessionGetVar("AccionVolverCargosIYM");
          $boton = "";
          $html = $this->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);
          return $html;
        }

        $f = (int) $Cantidad;
        $y = $Cantidad - $f;
        if($y != 0)
        {
          if($y != 0){ $this->frmError["Cantidad"]=1; }
          $mensaje='La Cantidad debe ser entera.';
          $titulo = "Agregar IyM";
          $accion = SessionGetVar("AccionVolverCargosIYM");
          $boton = "";
          $html = $this->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);
          return $html;
        }
        //FIN CASO CLINICA OCCIDENTE CALI

        list($dbconn) = GetDBconn();
        
        $query = "SELECT  b.servicio
                  FROM    departamentos as b
                  WHERE   b.departamento = '".$Departamento."'";
        
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) 
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."".__LINE__."";
          return false;
        }
        
        $Servicio = $results->fields[0];

        $query = " INSERT INTO tmp_cuenta_insumos
                      (
                        numerodecuenta,
                        departamento,
                        bodega,
                        codigo_producto,
                        cantidad,
                        empresa_id,
                        centro_utilidad,
                        precio,
                        fecha_cargo,
                        plan_id,
                        servicio_cargo,
                        lote,
                        fecha_vencimiento
                      )
                      VALUES
                      (
                        ".$Cuenta.",
                       '".$Departamento."',
                       '".$bodega."',
                       '".$Codigo."',
                        ".$Cantidad.",
                       '".$empresa."',
                       '".$cu."',
                        ".$precio.",
                       '".$fecha_cargo."',
                        ".$PlanId.",
                       '".$Servicio."',
                       '".$v['lote']."',
                       '".$this->DividirFecha($v['fecha_vencimiento'])."'
                      )";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) 
        {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
          return false;
        }
      }
      //************************************************************
      //CREAR DOCUEMNTOS DE BODEGA
      //************************************************************
      $query = "SELECT  count(a.numerodecuenta)
                FROM    tmp_cuenta_insumos as a 
                WHERE   a.numerodecuenta = ".$Cuenta." ";
      $result=$dbconn->Execute($query);
      
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."".__LINE__."";
        return false;
      }

      if($result->fields[0]==0)
      {
        $mensaje = "NO HA AGREGADO NINGUN INSUMO.";
        $titulo = "Agregar IyM";
        $accion = SessionGetVar("AccionVolverCargosIYM");
        $boton = "";
        $html = $this->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);
        return $html;
      }
      
      $argu=array('Cuenta'=>$Cuenta);

      $_SESSION['INVENTARIOS']['RETORNO']['contenedor'] = 'app';
      $_SESSION['INVENTARIOS']['RETORNO']['modulo']     = 'Cuentas';
      $_SESSION['INVENTARIOS']['RETORNO']['tipo']       = 'user';
      $_SESSION['INVENTARIOS']['RETORNO']['metodo']     = 'FormaMostrarCuenta';
      $_SESSION['INVENTARIOS']['RETORNO']['argumentos'] = $argu;
      $_SESSION['INVENTARIOS']['CUENTA']                = $Cuenta;

      $this->LlamaReturnMetodoExterno('app','InvBodegas','user','LiquidacionMedicamentos');
      if(!$_SESSION['INVENTARIOS']['RETORNO']['Bodega'])
      {
        $mensaje = $_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error'];
        //PENDIENTE POR ERROR EN LOS IMD OSTEROSINTESOS O GASES
        $query = "DELETE
                  FROM tmp_cuenta_insumos
                  WHERE numerodecuenta=$Cuenta";

        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al delete tmp_cuenta_insumos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."".__LINE__."";
            echo $this->mensajeDeError;
            return false;
        } 
        $tranasaccion = explode('||//',$_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']);
        $mensaje = $tranasaccion[0];
        $query = "DELETE
                  FROM cuentas_detalle
                  WHERE transaccion = $tranasaccion[1]";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al delete cuentas_detalle";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."".__LINE__."";
            echo $this->mensajeDeError;
            return false;
        }
      }

      else
      {
        $mensaje = 'Datos Guardados Correctamente.';
        SessionDelVar("ValidacionExistencias");
      }
      $titulo = "Agregar IyM";
      $accion = SessionGetVar("AccionVolverCargosIYM");
      $boton = "";
      $html = $this->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);

      return $html;
    }//FIN INSUMOS

		function LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton)
		{
			$frm = new app_Cuentas_user();
			$html = $frm->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);
			return $html;
		}

		function LlamaReturnMetodoExterno($app,$modu,$user,$met)
		{
			$frm = new app_Cuentas_user();
			$frm->ReturnMetodoExterno($app,$modu,$user,$met);
			return true;
		}
    /**
    * Funcion donde se parte la fecha y se devuelve la fecha en formato yyyy-MM-DD
    *
    * @param strinmg $fecha Fecha pasada por parametro 
    *
    * @return string
    */
    function DividirFecha($fecha)
    {
      $f = explode("/",$fecha);
      if(sizeof($f) == 3 )
        $fecha = $f[2]."-".$f[1]."-".$f[0];
      
      return $fecha;
    }
	}
?>