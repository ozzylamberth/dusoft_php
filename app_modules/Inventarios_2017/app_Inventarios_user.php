<?php

/**
 * $Id: app_Inventarios_user.php,v 1.9 2008/06/26 19:22:07 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase que maneja los metodos que llaman a las vistas relacionadas al manejo de los
 * inventarios
 */
class app_Inventarios_user extends classModulo {

    var $limit;
    var $conteo;

    function app_Inventarios_user()
    {
        $this->limit = GetLimitBrowser();
        return true;
    }

    /**
     * Function que llama al menu
     * @return boolean;
     */
//=================================================================================
    function main()
    {
        if (!$this->FrmLogueoBodega())
        {
            return false;
        }
        return true;
    }

    //=================================================================================
    /**
     * Function que llama al menu la forma que visualiza las diferentes formas de busqueda en el inventario
     * @return boolean;
     */
    function LlamaFormaTiposBusqueda()
    {
        if (!$this->FormaListadoInventario($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']))
        {
            return false;
        }

        return true;
    }

    //=================================================================================
    /**
     * Function que llama al menu que muestra al usuario las empresas en las que puede trabajar
     * @return boolean;
     */
    function LlamaSeleccionCentroUtilidad()
    {
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaCrearBodegas');
        if (!$this->SeleccionCentroUtilidad($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $action))
        {
            return false;
        }
        return true;
    }

    //=================================================================================
    /**
     * Funcion que consulta en la base de datos los permisos de los usuarios para trabajar en las empresas creadas en el sistema
     * @return array;
     */
    function LogueoBodega()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT  DISTINCT ugbm.empresa_id,x.razon_social, x.id, x.direccion, x.telefonos FROM usuarios_maestro_inventarios ugbm,empresas as x WHERE ugbm.usuario_id = " . UserGetUID() . " AND ugbm.empresa_id=x.empresa_id ";
        $result = $dbconn->Execute($query);
        if ($result->EOF)
        {
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg() . "<br>" . $query;
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($toUpper = false);
                $result->MoveNext();
            }
            return $vars;
        }
    }

    //=================================================================================
    function ProductosNoExisEmpresas()
    {
        if (!$this->FormaMostrarPrInvnoEmp($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']))
        {
            return false;
        }
        return true;
    }

    //=================================================================================
    function BuscarDatosClasifyProd($Empresa, $codigoProducto)
    {

        list($dbconn) = GetDBconn();
        $query = "SELECT x.grupo_id,y.descripcion as desgr,x.clase_id,z.descripcion as desclas,
		x.subclase_id,l.descripcion as dessubclas
		FROM inventarios_productos x,inv_grupos_inventarios y,inv_clases_inventarios z,inv_subclases_inventarios l
		WHERE x.codigo_producto='$codigoProducto' AND
		x.grupo_id=y.grupo_id AND x.grupo_id=z.grupo_id AND x.clase_id=z.clase_id AND
		x.grupo_id=l.grupo_id AND x.clase_id=l.clase_id AND x.subclase_id=l.subclase_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
                return false;
            }
            else
            {
                $vars = $result->GetRowAssoc($toUpper = false);
            }
            $result->close();
            return $vars;
        }
    }

    //=================================================================================
    function InsertarFormaCrearBodegas()
    {

        if ($_REQUEST['Cancelar'])
        {
            $action = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaCrearBodegas');
            if (!$this->SeleccionCentroUtilidad($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $action))
            {
                return false;
            }
            return true;
        }
        if ($_REQUEST['restitucion'])
        {
            $restitucion = 1;
        }
        else
        {
            $restitucion = 0;
        }
        if ($_REQUEST['ingresocompras'])
        {
            $ingresocompras = 1;
        }
        else
        {
            $ingresocompras = 0;
        }
        $ComprobarCodigoBodega = $this->VerificacionExistenciaCodigoBodega($_REQUEST['Bodega'], $_REQUEST['empresa'], $_REQUEST['centroutilidad']);
        if ($ComprobarCodigoBodega == 1)
        {
            $this->frmError["Bodega"] = 1;
            $this->frmError["MensajeError"] = "Ya Existe una Bodega con este Codigo Verifique por Favor.";
            if (!$this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['Departamento'], $_REQUEST['Ubicacion'], $_REQUEST['descripcion'], $_REQUEST['Responsable'], '', $_REQUEST['tipoNumeracion'], $restitucion, $ingresocompras, $_REQUEST['TipoDisposicion']))
            {
                return false;
            }
            return true;
        }

        if ($_REQUEST['centroutilidad'] == -1 || !$_REQUEST['Bodega'] || !$_REQUEST['descripcion'] || $_REQUEST['Departamento'] == -1 || $_REQUEST['tipoNumeracion'] == -1)
        {
            if ($_REQUEST['centroutilidad'] == -1)
            {
                $this->frmError["centroutilidad"] = 1;
            }
            if (!$_REQUEST['Bodega'])
            {
                $this->frmError["Bodega"] = 1;
            }
            if (!$_REQUEST['descripcion'])
            {
                $this->frmError["descripcion"] = 1;
            }
            if ($_REQUEST['Departamento'] == -1)
            {
                $this->frmError["Departamento"] = 1;
            }
            if ($_REQUEST['tipoNumeracion'] == -1)
            {
                $this->frmError["tipoNumeracion"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
            if (!$this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['Departamento'], $_REQUEST['Ubicacion'], $_REQUEST['descripcion'], $_REQUEST['Responsable'], '', $_REQUEST['tipoNumeracion'], $restitucion, $ingresocompras, $_REQUEST['TipoDisposicion']))
            {
                return false;
            }
            return true;
        }
        list($dbconn) = GetDBconn();
        $query = "INSERT INTO bodegas(empresa_id,
		                              centro_utilidad,
																	bodega,
																	descripcion,
																	departamento,
																	ubicacion,
																	responsable,
																	estado,
																	sw_restitucion,
																	autorizacion_recibir_compras,
																	sw_consumo_directo,
																	sw_restriccion_stock
																	)
																VALUES('" . $_REQUEST['Empresa'] . "','" . $_REQUEST['centroutilidad'] . "',
																'" . $_REQUEST['Bodega'] . "','" . $_REQUEST['descripcion'] . "',
																'" . $_REQUEST['Departamento'] . "','" . $_REQUEST['Ubicacion'] . "',
																'" . $_REQUEST['Responsable'] . "','1','$restitucion',
																'$ingresocompras',0,0)";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $mensaje = "Bodega Creada";
            $titulo = "CREACION BODEGAS";
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaCrearBodegas', array("Empresa" => $_REQUEST['Empresa'], "NombreEmp" => $_REQUEST['NombreEmp'], "centroutilidad" => $_REQUEST['centroutilidad']));
            $this->FormaMensaje($mensaje, $titulo, $accion, $boton);
            return true;
        }
    }

    //=====================================================================================
    function VerificacionExistenciaCodigoBodega($Bodega, $empresa, $centroutilidad)
    {

        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM bodegas WHERE bodega='$Bodega' AND empresa_id='$empresa' AND centro_utilidad='$centroutilidad'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                $retorno = 1;
            }
            else
            {
                $retorno = 0;
            }
        }
        return $retorno;
    }

    //=====================================================================================
    function RealizarInseryPrEmpresa()
    {
        if ($_REQUEST['Regresar'])
        {
            $this->MenuInventariosPrincipal();
            return true;
        }
        if ($_REQUEST['buscar'])
        {
            $descripcionPro = strtoupper($_REQUEST['descripcionPro']);
            $this->FormaMostrarPrInvnoEmp($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $descripcionPro, $_REQUEST['codigoProAlterno']);
            return true;
        }
    }

    //=====================================================================================
    /**
     * Funcion que llama a la forma que muestra el listado de los productos existentes en el inventario
     * @return boolean;
     * @param string empresa en la que el usuario esta trabajando;
     */
    function BusquedaInventarios()
    {
        if ($_REQUEST['Salir'])
        {
            $this->MenuInventariosPrincipal();
            return true;
        }
        if (!$this->FormaListadoInventario($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase']))
        {
            return false;
        }
        return true;
    }

    //=====================================================================================
    /**
     * Funcion que Llama a la forma para adicionar un producto en el inventario o eliminarlo segun la seleccion del usuario
     * @return boolean;
     */
    function LlamaAccionDelProducto()
    {
        $paso = $_REQUEST['paso'];
        $Of = $_REQUEST['Of'];
        if ($_REQUEST['Regresar'])
        {
            $this->MenuInventariosPrincipal();
            return true;
        }
        if ($_REQUEST['buscar'])
        {
            $descripcionPro = strtoupper($_REQUEST['descripcionPro']);
            $this->FormaListadoInventario($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoPro'], $descripcionPro, $_REQUEST['codigoProAlterno']);
            return true;
        }
        if ($_REQUEST['Eliminar'])
        {
            $descripcionPro = strtoupper($_REQUEST['descripcionPro']);
            $this->EliminarProductos($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Seleccion'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $descripcionPro);
            return true;
        }
    }

    //=====================================================================================
    /**
     * Funcion que Elimina de la base de datos productos en el inventario
     * @return boolean;
     * @param string indica la empresa del sisema en la que esta trabajando el usuario
     * @param string indica el nombre de la empresa del sistema en la que esta trabajando el usuario
     * @param array codigos de los productos a eliminar en el inventario
     */
    function EliminarProductos($Empresa, $NombreEmp, $Seleccion, $grupo, $clasePr, $subclase, $NomGrupo, $NomClase, $NomSubClase, $codigoPro, $descripcionPro)
    {

        list($dbconn) = GetDBconn();
//		$dbconn->debug=true;
        foreach ($Seleccion as $Pr => $n)
        {
            $query = "SELECT estado FROM inventarios WHERE codigo_producto='$Pr' AND empresa_id='$Empresa'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                $datos = $result->RecordCount();
                if ($datos)
                {
                    $var = $result->GetRowAssoc($toUpper = false);
                }
            }
            if ($var['estado'] == 1)
            {
                $Indicador = $this->VerificarEliminacionProductoInv($Pr);
                //$PrMedi=$this->VerificacionProductoMedicamento($Pr,$Empresa);
                if ($Indicador != 1)
                {
                    $query = "DELETE FROM inventarios WHERE codigo_producto='$Pr' AND empresa_id='$Empresa';";
                    $periodo = $fecha_actual = date("Y/m");
                    list($anio, $mes) = explode("/", $periodo);
                    $periodo_ = $anio . "" . $mes;
                    $query .= " DELETE FROM inventarios_lapsos WHERE codigo_producto='$Pr' AND empresa_id='$Empresa' AND lapso='" . $periodo_ . "';";
                }
                else
                {
                    $query = "UPDATE inventarios SET estado='0' WHERE codigo_producto='$Pr' AND empresa_id='$Empresa'";
                }
            }
            else
            {
                $query = "UPDATE inventarios SET estado='1' WHERE codigo_producto='$Pr' AND empresa_id='$Empresa'";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        $this->FormaListadoInventario($Empresa, $NombreEmp, $grupo, $clasePr, $subclase, $NomGrupo, $NomClase, $NomSubClase, '', $codigoPro, $descripcionPro);
        return true;
    }

    //=====================================================================================
    function VerificarEliminacionProductoInv($Pr)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM existencias_bodegas WHERE codigo_producto='$Pr'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                $retorno = 1;
            }
            else
            {
                $retorno = 0;
            }
        }
        return $retorno;
    }

    //=====================================================================================
    /* function VerificacionProductoMedicamento($Pr,$Empresa){

      list($dbconn) = GetDBconn();
      $query = "SELECT * FROM inventario_medicamentos WHERE codigo_producto='$Pr' AND empresa_id='$Empresa'";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }else{
      $datos=$result->RecordCount();
      if($datos){
      $retorno=1;
      }else{
      $retorno=0;
      }
      }
      return $retorno;
      } */

    //=====================================================================================
    /**
     * Funcion que inserta en la base de datos un nuevo producto al inventario
     * @return boolean;
     */
    function InsertarProductoInventarios()
    {
        $DescripcionCompleta = $_REQUEST['DescripcionCompleta'];
        $DescripcionCompleta = strtoupper($DescripcionCompleta);
        $DescripcionAbreviada = $_REQUEST['DescripcionAbreviada'];
        $DescripcionAbreviada = strtoupper($DescripcionAbreviada);
        if ($_REQUEST['Cancelar'])
        {
            if ($_REQUEST['OrigenFuct'] != 1)
            {
                $this->FormaMostrarPrInv($_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase']);
                return true;
            }
            else
            {
                $action = ModuloGetURL('app', 'Inventarios', 'user', 'BusquedaBDProductosInventarios');
                $this->FormaTiposBusqueda('', '', $action);
                return true;
            }
        }
        $existePr = $this->existenciaProductosInv($_REQUEST['codProducto'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase']);
        if ($existePr == 1)
        {
            $this->frmError["MensajeError"] = "El Codigo de Producto ya Existe en la Base de Datos.";
            $this->FormaAdicionarInventario($_REQUEST['codProducto'], $DescripcionCompleta, $DescripcionAbreviada, $_REQUEST['unidad'], $_REQUEST['fabricante'], $_REQUEST['valorFab'], $_REQUEST['PorcentajeIva'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase']);
            return true;
        }
        if (!$_REQUEST['codProducto'] || !$_REQUEST['DescripcionCompleta'] || !$_REQUEST['DescripcionAbreviada'] ||
                $_REQUEST['unidad'] == -1 || !$_REQUEST['PorcentajeIva'] || !$_REQUEST['grupo'] ||
                !$_REQUEST['clasePr'] || !$_REQUEST['subclase'])
        {
            if (!$_REQUEST['codProducto'])
            {
                $this->frmError["codProducto"] = 1;
            }
            if (!$_REQUEST['DescripcionCompleta'])
            {
                $this->frmError["DescripcionCompleta"] = 1;
            }
            if (!$_REQUEST['DescripcionAbreviada'])
            {
                $this->frmError["DescripcionAbreviada"] = 1;
            }
            if ($_REQUEST['unidad'] == -1)
            {
                $this->frmError["unidad"] = 1;
            }
            if (!$_REQUEST['PorcentajeIva'])
            {
                $this->frmError["PorcentajeIva"] = 1;
            }
            if (!$_REQUEST['grupo'])
            {
                $this->frmError["grupo"] = 1;
            }
            if (!$_REQUEST['clasePr'])
            {
                $this->frmError["clasePr"] = 1;
            }
            if (!$_REQUEST['subclase'])
            {
                $this->frmError["subclase"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
            $this->FormaAdicionarInventario($_REQUEST['codProducto'], $DescripcionCompleta, $DescripcionAbreviada, $_REQUEST['unidad'], $_REQUEST['fabricante'], $_REQUEST['valorFab'], $_REQUEST['PorcentajeIva'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase']);
            return true;
        }
        if ($_REQUEST['valorFab'])
        {
            $valorFab1 = "'$valorFab'";
        }
        else
        {
            $valorFab1 = 'NULL';
        }
        list($dbconn) = GetDBconn();
        $query = "INSERT INTO inventarios_productos(
																					codigo_producto,
																					grupo_id,
																					clase_id,
																					subclase_id,
																					producto_id,
																					descripcion,
																					descripcion_abreviada,
																					fabricante_id,
																					unidad_id,
																					porc_iva,
																					estado,
																					grupo_contratacion_id
																					)VALUES('','" . $_REQUEST['grupo'] . "','" . $_REQUEST['clasePr'] . "','" . $_REQUEST['subclase'] . "',
																					'" . $_REQUEST['codProducto'] . "','$DescripcionCompleta',
																					'$DescripcionAbreviada','$valorFab1','" . $_REQUEST['unidad'] . "',
																			    '" . $_REQUEST['PorcentajeIva'] . "','1','" . $_REQUEST['grupoContratacion'] . "')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $grupoMedicamento = $this->IdentificacionGrupoMedico($grupo);
        if ($grupoMedicamento['sw_medicamento'] == 1)
        {
            $var = $this->HallarCodigoProducto($_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['codProducto']);
            $codProducto = $var['codigo_producto'];
            $this->FormaDatosMedicamentos($_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomSubGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['codProducto'], '', '', '', '', '', '', $_REQUEST['codProducto'], '', $_REQUEST['DescripcionCompleta']);
            return true;
        }
        else
        {
            $mensaje = "Producto Creado en el inventario";
            $titulo = "PRODUCTOS INVENTARIOS";
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'BusquedaInventarios', array('Empresa' => $_REQUEST['Empresa'], 'NombreEmp' => $_REQUEST['NombreEmp'], 'grupo' => $_REQUEST['grupo'], 'NomGrupo' => $_REQUEST['NomGrupo'], 'subgrupo' => $_REQUEST['subgrupo'], 'NomSubGrupo' => $_REQUEST['NomSubGrupo'], 'clasePr' => $_REQUEST['clasePr'], 'NomClase' => $_REQUEST['NomClase'], 'subclase' => $_REQUEST['subclase'], 'NomSubClase' => $_REQUEST['NomSubClase']));
            $this->FormaMensaje($mensaje, $titulo, $accion, $boton);
            return true;
        }
    }

    //=====================================================================================
    function existenciaProductosInv($codProducto, $grupo, $clasePr, $subclase)
    {

        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM inventarios_productos WHERE producto_id='$codProducto' AND grupo_id='$grupo' AND clase_id='$clasePr' AND subclase_id='$subclase'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                $retorno = 1;
            }
            else
            {
                $retorno = 0;
            }
            $result->Close();
            return $retorno;
        }
    }

    //=====================================================================================
    function LLamaAdicionCancelacionClas()
    {
        $this->MenuInventariosPrincipal();
        return true;
    }

    //=====================================================================================
    function ConfirmarExisteGrupo($CodGrupo)
    {

        list($dbconn) = GetDBconn();
        $query = "SELECT count(*) as contador FROM inv_grupos_inventarios WHERE grupo_id='$CodGrupo'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $vars = $result->GetRowAssoc($toUpper = false);
        }
        $retorno = $vars['contador'];
        $result->Close();
        return $retorno;
    }

    //=====================================================================================
    /**
     * Funcion donde se llama la forma crear bodegas o seleccion del centro de utilidad segun la seleccion de usuario
     * @return boolean;
     */
    function LlamaFormaCrearBodegas()
    {
        if ($_REQUEST['cancelar'])
        {
            $this->MenuInventariosPrincipal();
            return true;
        }
        if ($_REQUEST['centroutilidad'] == -1)
        {
            $this->frmError["MensajeError"] = "Debe Elegir un Centro de Utilidad.";
            $action = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaCrearBodegas');
            if (!$this->SeleccionCentroUtilidad($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $action))
            {
                return false;
            }
            return true;
        }
        else
        {
            if ($_REQUEST['VerBodegas'])
            {
                $this->FormaConsultaBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad']);
                return true;
            }
            else
            {
                if (!$this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad']))
                {
                    return false;
                }
                return true;
            }
        }
    }

    //=====================================================================================
    /**
     * Funcion donde se llama la forma para realizar la modificacion de la bodega
     * @return boolean;
     */
    function LlamaFormaModificarBodega()
    {

        $this->FormaModificarBodega($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['bandera'], $_REQUEST['Bodega'], $_REQUEST['NomBodega'], $_REQUEST['Departamento'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['centinela'], $_REQUEST['restitucion'], $_REQUEST['ingresocompras']);
        return true;
    }

    //=====================================================================================
    /**
     * Funcion donde se inserta la modificacion de una bodega
     * @return boolean;
     */
    function InsertarModificacionBodegas()
    {
        if ($_REQUEST['Cancelar'])
        {
            if ($_REQUEST['centinela'])
            {
                $this->FormaConsultaBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad']);
                return true;
            }
            if (!$this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], '', '', '', '', '', '', $_REQUEST['bandera']))
            {
                return false;
            }
            return true;
        }
        if ($_REQUEST['restitucion'])
        {
            $restitucion = 1;
        }
        else
        {
            $restitucion = 0;
        }
        if ($_REQUEST['ingresocompras'])
        {
            $ingresocompras = 1;
        }
        else
        {
            $ingresocompras = 0;
        }
        if (!$_REQUEST['descripcion'] || $_REQUEST['Departamento'] == -1)
        {
            if (!$_REQUEST['descripcion'])
            {
                $this->frmError["descripcion"] = 1;
            }
            if ($_REQUEST['Departamento'] == -1)
            {
                $this->frmError["Departamento"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
            if (!$this->FormaModificarBodega($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['bandera'], $_REQUEST['Bodega'], $_REQUEST['NomBodega'], $_REQUEST['Departamento'], $_REQUEST['descripcion'], $_REQUEST['Ubicacion'], $_REQUEST['Responsable'], $_REQUEST['centinela'], $restitucion, $ingresocompras))
            {
                return false;
            }
            return true;
        }
        list($dbconn) = GetDBconn();
        $query = "UPDATE bodegas SET descripcion='" . $_REQUEST['descripcion'] . "',departamento='" . $_REQUEST['Departamento'] . "',ubicacion='" . $_REQUEST['Ubicacion'] . "',responsable='" . $_REQUEST['Responsable'] . "',sw_restitucion='$restitucion',autorizacion_recibir_compras='$ingresocompras' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($_REQUEST['centinela'])
        {
            $this->FormaConsultaBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad']);
            return true;
        }
        if (!$this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], '', '', '', '', '', '', $_REQUEST['bandera']))
        {
            return false;
        }
        return true;
    }

    //=====================================================================================
    /**
     * Funcion donde modifica el estado de la bodega
     * @return boolean;
     */
    function ModificarEstadoBodega()
    {

        list($dbconn) = GetDBconn();
        if ($_REQUEST['Estado'] == 1)
        {
            $query = "UPDATE bodegas SET estado='0' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "'";
        }
        else
        {
            $query = "UPDATE bodegas SET estado='1' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "'";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        if ($_REQUEST['centinela'])
        {
            $this->FormaConsultaBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad']);
            return true;
        }
        $this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], '', '', '', '', '', '', $_REQUEST['bandera']);
        return true;
    }

    //=====================================================================================
    /**
     * Funcion donde listan las bodegas existentes en la base de datos
     * @return boolean;
     */
    function LLamaListadoBodegas()
    {
        if ($_REQUEST['centinela'])
        {
            $this->FormaConsultaBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad']);
            return true;
        }
        $this->FormaCrearBodegas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], '', '', '', '', '', '', $_REQUEST['bandera']);
        return true;
    }

    //=====================================================================================
    /**
     * Funcion consulta en la base de datos las bodegas y sos atributos
     * @return array;
     */
    function ConsultaTotalBodegas($Empresa, $centroutilidad, $bandera)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT x.bodega,x.descripcion,x.departamento,x.ubicacion,x.responsable,x.estado,y.descripcion as desdpto,x.sw_restitucion,x.autorizacion_recibir_compras FROM bodegas as x,departamentos as y WHERE x.empresa_id='$Empresa' AND x.centro_utilidad='$centroutilidad' AND x.empresa_id=y.empresa_id AND x.centro_utilidad=y.centro_utilidad AND x.departamento=y.departamento ORDER BY x.bodega";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                while (!$result->EOF)
                {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
            }
            $result->Close();
            return $vars;
        }
    }

    //=====================================================================================
    /* function TiposDeNumeracion(){

      list($dbconn) = GetDBconn();
      $query = "SELECT tipo_numeracion,descripcion FROM numeraciones";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }else{
      if($result->EOF){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "La tabla 'planes' esta vacia ";
      return false;
      }else{
      while (!$result->EOF) {
      $vars[$result->fields[0]]=$result->fields[1];
      $result->MoveNext();
      }
      }
      $result->Close();
      return $vars;
      }
      } */

    //=====================================================================================
    /**
     * Funcion donde se selecciona los departamentos de la base de datos a partir de la empresa y el centro de utilidad
     * @return array;
     * @param string codigo de la empresa en la que el usuario esta trabajando;
     * @param string codigo del centro de utilidad en la que el usuario esta trabajando;
     */
    function TiposDepartamentos($Empresa, $centroutilidad)
    {

        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT departamento,descripcion FROM departamentos WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                while (!$result->EOF)
                {
                    $vars[$result->fields[0]] = $result->fields[1];
                    $result->MoveNext();
                }
            }
            $result->Close();
            return $vars;
        }
    }

    //=====================================================================================
    /**
     * Funcion donde se retorne el nombre del centro de utilidad a partir de su codigo
     * @return array;
     * @param string empresa en la que el usuario esta trabajando
     * @param string centro de utilidad en el que el usuario esta trabajando
     */
    function NombreCentroUtilidad($Empresa, $centroutilidad)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT descripcion FROM centros_utilidad WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                $vars = $result->GetRowAssoc($toUpper = false);
            }
            $result->Close();
            return $vars;
        }
    }

    //=====================================================================================
    /**
     * Funcion donde se selecciona los centro de uitilidad de la base de datos a partir de la empresa
     * @return array;
     * @param string codigo de la empresa en la que el usuario esta trabajando;
     */
    function CentrosUtilidad($Empresa)
    {

        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT centro_utilidad,descripcion FROM centros_utilidad WHERE empresa_id='$Empresa'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                while (!$result->EOF)
                {
                    $vars[$result->fields[0]] = $result->fields[1];
                    $result->MoveNext();
                }
            }
            $result->Close();
            return $vars;
        }
    }

    //=====================================================================================
    function EditarProductoInventarioCodifi()
    {
        $paso = $_REQUEST['paso'];
        $Of = $_REQUEST['Of'];
        $datosProd = $this->DatosProductoInventarioCodifi($_REQUEST['codigoProducto']);
        $NomGrupo = $datosProd['grupo_id'] . ' ' . $datosProd['nomgrupo'];
        $NomClase = $datosProd['clase_id'] . ' ' . $datosProd['nomclase'];
        $NomSubClase = $datosProd['subclase_id'] . ' ' . $datosProd['nomsubclase'];
        if (!$datosProd['sw_medicamento'])
        {
            $this->FormaEditarProductoInventarioCodifi($NomGrupo, $datosProd['grupo_id'], $NomClase, $datosProd['clase_id'], $NomSubClase, $datosProd['subclase_id'], $datosProd['producto_id'], $datosProd['descripcion'], $datosProd['descripcion_abreviada'], $datosProd['fabricante_id'], $datosProd['nomfabricante'], $datosProd['unidad_id'], $datosProd['porc_iva'], $datosProd['grupo_contratacion_id'], $_REQUEST['codigoProducto'], '', '', '', '', '', '', '', '', $_REQUEST['codigoBusqueda'], $_REQUEST['descripcionBusqueda'], $datosProd['producto_id'], $datosProd['grupo_id'], $datosProd['clase_id'], $datosProd['subclase_id'], $paso, $Of, $_REQUEST['consultaForma']);
            return true;
        }
        else
        {
            $DatosMedicamento = $this->DatosDelMedicamento($_REQUEST['codigoProducto']);
            $this->FormaEditarProductoInventarioCodifi($NomGrupo, $datosProd['grupo_id'], $NomClase, $datosProd['clase_id'], $NomSubClase, $datosProd['subclase_id'], $datosProd['producto_id'], $datosProd['descripcion'], $datosProd['descripcion_abreviada'], $datosProd['fabricante_id'], $datosProd['nomfabricante'], $datosProd['unidad_id'], $datosProd['porc_iva'], $datosProd['grupo_contratacion_id'], $_REQUEST['codigoProducto'], $datosProd['sw_medicamento'], $DatosMedicamento['cod_anatomofarmacologico'], $DatosMedicamento['cod_principio_activo'], $DatosMedicamento['cod_forma_farmacologica'], $DatosMedicamento['cod_concentracion'], $DatosMedicamento['presentacion'], $DatosMedicamento['pos'], $DatosMedicamento['sw_solucion'], $_REQUEST['codigoBusqueda'], $_REQUEST['descripcionBusqueda'], $datosProd['producto_id'], $datosProd['grupo_id'], $datosProd['clase_id'], $datosProd['subclase_id'], $paso, $Of, $_REQUEST['consultaForma']);
            return true;
        }
    }

    //=====================================================================================
    function DatosProductoInventarioCodifi($codigoProducto)
    {

        $query = "SELECT a.grupo_id,b.descripcion as nomgrupo,a.clase_id,c.descripcion as nomclase,a.subclase_id,d.descripcion as nomsubclase,a.producto_id,a.descripcion,a.descripcion_abreviada,a.fabricante_id,e.descripcion as nomfabricante,a.unidad_id,a.porc_iva,b.sw_medicamento,a.grupo_contratacion_id  FROM inventarios_productos a,inv_grupos_inventarios b,inv_clases_inventarios c,inv_subclases_inventarios d,inv_fabricantes e WHERE a.codigo_producto='$codigoProducto' AND a.grupo_id=b.grupo_id AND a.grupo_id=c.grupo_id AND a.clase_id=c.clase_id AND a.grupo_id=d.grupo_id AND a.clase_id=d.clase_id AND a.subclase_id=d.subclase_id AND a.fabricante_id=e.fabricante_id";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                $vars = $result->GetRowAssoc($toUpper = false);
            }
        }
        return $vars;
    }

    //=====================================================================================
    function DatosDelMedicamento($codigoPrincipal)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT cod_anatomofarmacologico,cod_principio_activo,cod_forma_farmacologica,cod_concentracion,pos,presentacion,sw_solucion
		FROM medicamentos WHERE codigo_medicamento='$codigoPrincipal'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                $vars = $result->GetRowAssoc($toUpper = false);
            }
        }
        return $vars;
    }

    //=====================================================================================
    /**
     * Funcion que consulta los productos y los atributos de los productos existentes en el inventario
     * @return array;
     * @param string empresa en la que el usuario esta trabajando;
     */
    function TotalInventarioProductosInvnoEmp($Empresa, $grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno)
    {

        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $queryBuqueda = $this->HallarQueryBusqueda($grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno);
        if (empty($_REQUEST['conteo']))
        {
            $query = "SELECT count(*) FROM inventarios_productos z,
				(SELECT codigo_producto FROM inventarios_productos EXCEPT SELECT codigo_producto
				FROM inventarios WHERE empresa_id='$Empresa')  h WHERE z.codigo_producto=h.codigo_producto $queryBuqueda";
            $result = $dbconn->Execute($query);
            if ($result->EOF)
            {
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg() . "<br>" . $query;
                return false;
            }
            list($this->conteo) = $result->fetchRow();
        }
        else
        {
            $this->conteo = $_REQUEST['conteo'];
        }
        if (!$_REQUEST['Of'])
        {
            $Of = '0';
        }
        else
        {
            $Of = $_REQUEST['Of'];
        }
        $query = "SELECT z.codigo_producto,z.descripcion,fc_descripcion_producto(z.codigo_producto) as nombre FROM inventarios_productos z,
		(SELECT codigo_producto FROM inventarios_productos EXCEPT SELECT codigo_producto
		FROM inventarios WHERE empresa_id='$Empresa')  h WHERE z.codigo_producto=h.codigo_producto
		$queryBuqueda LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);

        if ($result->EOF)
        {
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg() . "<br>" . $query;
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($toUpper = false);
                $result->MoveNext();
            }
            return $vars;
        }
    }

    //=====================================================================================
    function HallarQueryBusqueda($grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno)
    {

        if ($grupo)
        {
            $query.=" AND z.grupo_id='$grupo'";
        }
        if ($clasePr)
        {
            $query.=" AND z.clase_id='$clasePr'";
        }
        if ($subclase)
        {
            $query.=" AND z.subclase_id='$subclase'";
        }
        if ($codigoPro)
        {
            $query.=" AND z.codigo_producto LIKE '%$codigoPro'";
        }
        if ($descripcionPro)
        {
            $query.=" AND z.descripcion LIKE '%$descripcionPro%'";
        }
        if ($codigoProAlterno)
        {
            $query.=" AND z.cod_ihosp LIKE '%$codigoProAlterno%'";
        }
        return $query;
    }

    //=====================================================================================
    function InsertarProductoEnInventarios()
    {
        $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', $venta, $servicio, $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], '', '', $_REQUEST['descripcion']);
        return true;
    }

    //=====================================================================================
    function InsertarDatosPrInventarios()
    {
        if ($_REQUEST['Cancelar'])
        {
            $this->FormaMostrarPrInvnoEmp($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
            return true;
        }
        if ($_REQUEST['venta'])
        {
            $venta = 1;
        }
        else
        {
            $venta = 0;
        }
        if ($_REQUEST['servicio'])
        {
            $servicio = 1;
        }
        else
        {
            $servicio = 0;
        }
        if (!$_REQUEST['existMinima'] || !$_REQUEST['existMaxima'] ||
                !$_REQUEST['precioVentaAnt'] || !$_REQUEST['precioVenta'] ||
                !$_REQUEST['precioMinimo'] || !$_REQUEST['precioMaximo'])
        {
            if (!$_REQUEST['existMinima'])
            {
                $this->frmError["existMinima"] = 1;
            }
            if (!$_REQUEST['existMaxima'])
            {
                $this->frmError["existMaxima"] = 1;
            }
            if (!$_REQUEST['precioVentaAnt'])
            {
                $this->frmError["precioVentaAnt"] = 1;
            }
            if (!$_REQUEST['precioVenta'])
            {
                $this->frmError["precioVenta"] = 1;
            }
            if (!$_REQUEST['precioMinimo'])
            {
                $this->frmError["precioMinimo"] = 1;
            }
            if (!$_REQUEST['precioMaximo'])
            {
                $this->frmError["precioMaximo"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
            $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], $_REQUEST['existMinima'], $_REQUEST['existMaxima'], $_REQUEST['precioVentaAnt'], $_REQUEST['precioVenta'], $_REQUEST['precioMinimo'], $_REQUEST['precioMaximo'], $_REQUEST['venta'], $_REQUEST['servicio'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], $_REQUEST['grupoContratacion'], $_REQUEST['autorizadorCompra']);
            return true;
        }
        if ($_REQUEST['existMinima'] > $_REQUEST['existMaxima'])
        {
            $this->frmError["MensajeError"] = "La Existencias Menores no pueden ser mayores a las existencias Maximas.";
            $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], $_REQUEST['existMinima'], $_REQUEST['existMaxima'], $_REQUEST['precioVentaAnt'], $_REQUEST['precioVenta'], $_REQUEST['precioMinimo'], $_REQUEST['precioMaximo'], $_REQUEST['venta'], $_REQUEST['servicio'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], $_REQUEST['grupoContratacion'], $_REQUEST['autorizadorCompra']);
            return true;
        }
        if ($_REQUEST['precioMinimo'] < 0)
        {
            $this->frmError["MensajeError"] = "El Precio Minimo No puede ser Menor a Cero.";
            $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], $_REQUEST['existMinima'], $_REQUEST['existMaxima'], $_REQUEST['precioVentaAnt'], $_REQUEST['precioVenta'], $_REQUEST['precioMinimo'], $_REQUEST['precioMaximo'], $_REQUEST['venta'], $_REQUEST['servicio'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], $_REQUEST['grupoContratacion'], $_REQUEST['autorizadorCompra']);
            return true;
        }
        if ($_REQUEST['precioVentaAnt'] < 0)
        {
            $this->frmError["MensajeError"] = "El Precio Venta Anterior No puede ser Menor a Cero.";
            $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], $_REQUEST['existMinima'], $_REQUEST['existMaxima'], $_REQUEST['precioVentaAnt'], $_REQUEST['precioVenta'], $_REQUEST['precioMinimo'], $_REQUEST['precioMaximo'], $_REQUEST['venta'], $_REQUEST['servicio'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], $_REQUEST['grupoContratacion'], $_REQUEST['autorizadorCompra']);
            return true;
        }

        if ($_REQUEST['precioMaximo'] < 0)
        {
            $this->frmError["MensajeError"] = "El Precio Maximo no Puede Ser Menor a Cero.";
            $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], $_REQUEST['existMinima'], $_REQUEST['existMaxima'], $_REQUEST['precioVentaAnt'], $_REQUEST['precioVenta'], $_REQUEST['precioMinimo'], $_REQUEST['precioMaximo'], $_REQUEST['venta'], $_REQUEST['servicio'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], $_REQUEST['grupoContratacion'], $_REQUEST['autorizadorCompra']);
            return true;
        }

        if ($_REQUEST[precioVenta] < 0)
        {
            $this->frmError["MensajeError"] = "El Precio Venta, No Puede Ser Menor a Cero.";
            $this->FormaDatosProductoEnInventarios($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['Producto'], $_REQUEST['existMinima'], $_REQUEST['existMaxima'], $_REQUEST['precioVentaAnt'], $_REQUEST['precioVenta'], $_REQUEST['precioMinimo'], $_REQUEST['precioMaximo'], $_REQUEST['venta'], $_REQUEST['servicio'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro'], $_REQUEST['grupoContratacion'], $_REQUEST['autorizadorCompra']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query = "INSERT INTO inventarios(empresa_id,codigo_producto,existencia_minima,
		existencia_maxima,precio_venta_anterior,precio_venta,precio_minimo,precio_maximo,
		sw_vende,usuario_id,estado,fecha_registro,sw_servicio,grupo_contratacion_id,nivel_autorizacion_id)VALUES(
		'" . $_REQUEST['Empresa'] . "','" . $_REQUEST['Producto'] . "','" . $_REQUEST['existMinima'] . "','" . $_REQUEST['existMaxima'] . "',
		'" . $_REQUEST['precioVentaAnt'] . "','" . $_REQUEST['precioVenta'] . "',
		'" . $_REQUEST['precioMinimo'] . "','" . $_REQUEST['precioMaximo'] . "','" . $venta . "','" . UserGetUID() . "','1','" . date("Y-m-d H:i:s") . "','" . $servicio . "','" . $_REQUEST['grupoContratacion'] . "','" . $_REQUEST['autorizadorCompra'] . "')";
        $dbconn->Execute($query);

        //WS
        $resultado_ws = $this->Ejecutar_WS($query);
        //echo "ws1: ".$resultado_ws;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $mensaje = "Producto Creado en el inventario de la Empresa";
            $titulo = "PRODUCTOS INVENTARIOS EMPRESA";
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'ProductosNoExisEmpresas', array('Empresa' => $_REQUEST['Empresa'], 'NombreEmp' => $_REQUEST['NombreEmp'], 'grupo' => $_REQUEST['grupo'], 'NomGrupo' => $_REQUEST['NomGrupo'], 'subgrupo' => $_REQUEST['subgrupo'], 'NomSubGrupo' => $_REQUEST['NomSubGrupo'], 'clasePr' => $_REQUEST['clasePr'], 'NomClase' => $_REQUEST['NomClase'], 'subclase' => $_REQUEST['subclase'], 'NomSubClase' => $_REQUEST['NomSubClase'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']));
            $this->FormaMensaje($mensaje, $titulo, $accion, $boton);
            return true;
        }
    }

    function Ejecutar_WS($query)
    {
        //Consumo del WS
        require_once ('nusoap/lib/nusoap.php');
        //$url_wsdl = "http://10.0.1.80/SIIS/ws/ws_ejecutar_sql.php?wsdl";
        $url_wsdl = "http://dusoft.cosmitet.net/dusoft/ws/ws_ejecutar_sql.php?wsdl";
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "ejecutar_query";
        $inputs = array('sql' => $query);
        $resultado = $soapclient->call($function, $inputs);

        return $resultado;
    }

    //=====================================================================================
    /**
     * Funcion que consulta los productos y los atributos de los productos existentes en el inventario
     * @return array;
     * @param string empresa en la que el usuario esta trabajando;
     */
    function TotalInventario($Empresa, $grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno)
    {

        list($dbconn) = GetDBconn();
        $queryBuqueda = $this->HallarQueryBusqueda($grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno);
        if (empty($_REQUEST['conteo']))
        {
            $query = "SELECT count(*) FROM inventarios x,inventarios_productos z,inv_grupos_inventarios y,
			inv_clases_inventarios l,inv_subclases_inventarios as c WHERE x.empresa_id='$Empresa' AND
			x.codigo_producto=z.codigo_producto AND z.grupo_id=y.grupo_id AND z.grupo_id=l.grupo_id AND
			z.clase_id=l.clase_id AND z.grupo_id=c.grupo_id AND z.clase_id=c.clase_id AND
			z.subclase_id=c.subclase_id $queryBuqueda";
            $result = $dbconn->Execute($query);
            if ($result->EOF)
            {
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg() . "<br>" . $query;
                return false;
            }
            list($this->conteo) = $result->fetchRow();
        }
        else
        {
            $this->conteo = $_REQUEST['conteo'];
        }
        if (!$_REQUEST['Of'])
        {
            $Of = '0';
        }
        else
        {
            $Of = $_REQUEST['Of'];
        }
        $query = "SELECT fc_descripcion_producto(x.codigo_producto) as nombre,y.grupo_id,y.descripcion as desgrupo,l.clase_id,l.descripcion as desclase,c.subclase_id,
		c.descripcion as dessubclase,z.descripcion,x.estado,x.codigo_producto FROM inventarios x,
		inventarios_productos z,inv_grupos_inventarios y,inv_clases_inventarios l,inv_subclases_inventarios as c
		WHERE x.empresa_id='$Empresa' AND x.codigo_producto=z.codigo_producto AND z.grupo_id=y.grupo_id AND
		z.grupo_id=l.grupo_id AND z.clase_id=l.clase_id AND z.grupo_id=c.grupo_id AND z.clase_id=c.clase_id AND
		z.subclase_id=c.subclase_id $queryBuqueda LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if ($result->EOF)
        {
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg() . "<br>" . $query;
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($toUpper = false);
                $result->MoveNext();
            }
            return $vars;
        }
    }

    //=====================================================================================
    function LlamaVerDetalleProducto()
    {
        $this->VerDetalleProductoInv($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['claseIn'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['conteo'], $_REQUEST['Of'], $_REQUEST['paso'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
        return true;
    }

    //=====================================================================================
    function BuscarDatosProductoInv($Empresa, $codigoProducto)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT l.descripcion,l.descripcion_abreviada,y.descripcion as fabricante,z.descripcion as unidad,
		l.porc_iva,x.existencia_minima,x.existencia_maxima,x.existencia,x.costo_anterior,
		x.costo,x.costo_penultima_compra,x.costo_ultima_compra,x.precio_venta_anterior,
		x.precio_venta,x.precio_minimo,x.precio_maximo,x.sw_vende,x.sw_servicio,c.descripcion as grupocontratacion,a.descripcion as autorizador,
    x.grupo_contratacion_id,x.nivel_autorizacion_id
		FROM inventarios x,inventarios_productos l,
		inv_fabricantes y,unidades z,inv_grupos_contrataciones c,inv_niveles_autorizacion_compras a
		WHERE x.codigo_producto='$codigoProducto' AND x.empresa_id='$Empresa' AND
		x.codigo_producto=l.codigo_producto AND
		l.fabricante_id=y.fabricante_id AND l.unidad_id=z.unidad_id AND
		x.grupo_contratacion_id=c.grupo_contratacion_id AND x.nivel_autorizacion_id=a.nivel_autorizacion_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
                return false;
            }
            else
            {
                $vars = $result->GetRowAssoc($toUpper = false);
            }
            $result->close();
            return $vars;
        }
    }

    //=====================================================================================
    function CrearUbicacionesBodega()
    {
        $this->CrearUbicacionesEmpresas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['desDpto'], $_REQUEST['centinela']);
        return true;
    }

    /* function DescripcionNumeracion($tipoNumeracion){

      list($dbconn) = GetDBconn();
      $query = "SELECT descripcion FROM numeraciones WHERE tipo_numeracion='$tipoNumeracion'";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }else{
      if($result->EOF){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "La tabla 'planes' esta vacia ";
      return false;
      }else{
      $vars=$result->fields[0];
      }
      $result->Close();
      return $vars;
      }
      } */

    //=====================================================================================
    function ClasificacionUbicacionUno($Empresa, $centroutilidad, $Bodega)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT x.n1,y.descripcion as destipoal,x.tipo_almacenaje_id FROM bodegas_ubicaciones_n1 x,tipo_almacenaje y WHERE x.empresa_id='$Empresa' AND x.centro_utilidad='$centroutilidad' AND x.bodega='$Bodega' AND x.tipo_almacenaje_id=y.tipo_almacenaje_id ORDER BY x.n1";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                while (!$result->EOF)
                {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
                $result->Close();
            }
        }
        return $vars;
    }

    //=====================================================================================
    function ComprobarExisteNivelSiguiente($Empresa, $centroutilidad, $Bodega, $OrigenFuc, $n1, $n2, $n3)
    {
        list($dbconn) = GetDBconn();
        if ($OrigenFuc == 1)
        {
            $query = "SELECT * FROM bodegas_ubicaciones_n2 WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND n1='$n1' AND n2<>''";
        }
        elseif ($OrigenFuc == 2)
        {
            $query = "SELECT * FROM bodegas_ubicaciones_n3 WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND n1='$n1' AND n2='$n2' AND n3<>''";
        }
        elseif ($OrigenFuc == 3)
        {
            $query = "SELECT * FROM bodegas_ubicaciones_n4 WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND n1='$n1' AND n2='$n2' AND n3='$n3' AND n4<>''";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    //=====================================================================================
    function ClasificacionUbicacionDos($Empresa, $centroutilidad, $Bodega, $n1)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT n2 FROM bodegas_ubicaciones_n2 WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND n1='$n1' ORDER BY n1,n2";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                while (!$result->EOF)
                {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
                $result->Close();
            }
        }
        return $vars;
    }

    //=====================================================================================
    function ClasificacionUbicacionTres($Empresa, $centroutilidad, $Bodega, $n1, $n2)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT n3 FROM bodegas_ubicaciones_n3 WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND n1='$n1' AND n2='$n2' ORDER BY n1,n2,n3";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                while (!$result->EOF)
                {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
                $result->Close();
            }
        }
        return $vars;
    }

    //=====================================================================================
    function ClasificacionUbicacionCuatro($Empresa, $centroutilidad, $Bodega, $n1, $n2, $n3)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT n4 FROM bodegas_ubicaciones_n4 WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND n1='$n1' AND n2='$n2' AND n3='$n3' AND n4<>'' ORDER BY n1,n2,n3,n4";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $datos = $result->RecordCount();
            if ($datos)
            {
                while (!$result->EOF)
                {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
                $result->Close();
            }
        }
        return $vars;
    }

    //=====================================================================================
    function AdicionarClasificacionUbicacion()
    {

        $this->FormaAdicionarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
        return true;
    }

    //=====================================================================================
    function TiposAlmacenajesBodega()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_almacenaje_id,descripcion FROM tipo_almacenaje";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                while (!$result->EOF)
                {
                    $vars[$result->fields[0]] = $result->fields[1];
                    $result->MoveNext();
                }
            }
            $result->Close();
            return $vars;
        }
    }

    //=====================================================================================
    function InsertarNuevaClasify()
    {
        list($dbconn) = GetDBconn();
        if ($_REQUEST['Modificar'])
        {
            if ($_REQUEST['bandera'] == 4)
            {
                if (!$_REQUEST['descripcionNuv'] || $_REQUEST['tipoAlmacenaje'] == -1)
                {
                    $this->frmError["MensajeError"] = "Debe Digitar Todos los Datos.";
                    $this->FormaAdicionarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
                    return true;
                }
            }
            else
            {
                if (!$_REQUEST['descripcionNuv'])
                {
                    $this->frmError["MensajeError"] = "Digite todos los Datos.";
                    $this->FormaAdicionarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
                    return true;
                }
            }
            if ($_REQUEST['bandera'] == 1)
            {
                $query = "SELECT * FROM bodegas_ubicaciones_n2 WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n1='" . $_REQUEST['n1'] . "' AND n2='" . $_REQUEST['descripcionNuv'] . "'";
            }
            elseif ($_REQUEST['bandera'] == 2)
            {
                $query = "SELECT * FROM bodegas_ubicaciones_n3 WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n1='" . $_REQUEST['n1'] . "' AND n2='" . $_REQUEST['n2'] . "' AND n3='" . $_REQUEST['descripcionNuv'] . "'";
            }
            elseif ($_REQUEST['bandera'] == 3)
            {
                $query = "SELECT * FROM bodegas_ubicaciones_n4 WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n1='" . $_REQUEST['n1'] . "' AND n2='" . $_REQUEST['n2'] . "' AND n3='" . $_REQUEST['n3'] . "' AND n4='" . $_REQUEST['descripcionNuv'] . "'";
            }
            elseif ($_REQUEST['bandera'] == 4)
            {
                $query = "SELECT * FROM bodegas_ubicaciones_n1 WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n1='" . $_REQUEST['descripcionNuv'] . "' AND tipo_almacenaje_id='" . $_REQUEST['tipoAlmacenaje'] . "'";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                $datos = $result->RecordCount();
                if ($datos)
                {
                    $this->frmError["MensajeError"] = "Ya Existe La Ubicacion Anterior Dentro de esta Bodega.";
                    $this->FormaAdicionarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
                    return true;
                }
                else
                {
                    if ($_REQUEST['bandera'] == 1)
                    {
                        $query = "INSERT INTO bodegas_ubicaciones_n2(empresa_id,centro_utilidad,bodega,n1,n2)VALUES('" . $_REQUEST['Empresa'] . "','" . $_REQUEST['centroutilidad'] . "','" . $_REQUEST['Bodega'] . "','" . $_REQUEST['n1'] . "','" . $_REQUEST['descripcionNuv'] . "')";
                    }
                    elseif ($_REQUEST['bandera'] == 2)
                    {
                        $query = "INSERT INTO bodegas_ubicaciones_n3(empresa_id,centro_utilidad,bodega,n1,n2,n3)VALUES('" . $_REQUEST['Empresa'] . "','" . $_REQUEST['centroutilidad'] . "','" . $_REQUEST['Bodega'] . "','" . $_REQUEST['n1'] . "','" . $_REQUEST['n2'] . "','" . $_REQUEST['descripcionNuv'] . "')";
                    }
                    elseif ($_REQUEST['bandera'] == 3)
                    {
                        $query = "INSERT INTO bodegas_ubicaciones_n4(empresa_id,centro_utilidad,bodega,n1,n2,n3,n4)VALUES('" . $_REQUEST['Empresa'] . "','" . $_REQUEST['centroutilidad'] . "','" . $_REQUEST['Bodega'] . "','" . $_REQUEST['n1'] . "','" . $_REQUEST['n2'] . "','" . $_REQUEST['n3'] . "','" . $_REQUEST['descripcionNuv'] . "')";
                    }
                    elseif ($_REQUEST['bandera'] == 4)
                    {
                        $query = "INSERT INTO bodegas_ubicaciones_n1(empresa_id,centro_utilidad,bodega,n1,tipo_almacenaje_id)VALUES('" . $_REQUEST['Empresa'] . "','" . $_REQUEST['centroutilidad'] . "','" . $_REQUEST['Bodega'] . "','" . $_REQUEST['descripcionNuv'] . "','" . $_REQUEST['tipoAlmacenaje'] . "')";
                    }
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $this->CrearUbicacionesEmpresas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['centinela']);
                    return true;
                }
            }
        }
        $this->CrearUbicacionesEmpresas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['centinela']);
        return true;
    }

    //=====================================================================================
    function EditarClasificacionUbicacion()
    {
        $this->FormaEditarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['tipoAlmacenaje'], $_REQUEST['NombreUbicaAct'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
        return true;
    }

    //=====================================================================================
    function InsertarEditarClasify()
    {

        list($dbconn) = GetDBconn();
        if ($_REQUEST['Modificar'])
        {
            if ($_REQUEST['bandera'] == 1)
            {
                if (!$_REQUEST['NombreUbica'] || $_REQUEST['tipoAlmacenaje'] == -1)
                {
                    $this->frmError["MensajeError"] = "Debe Digitar la Descripcion.";
                    $this->FormaEditarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['tipoAlmacenaje'], $_REQUEST['NombreUbicaAct'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
                    return true;
                }
            }
            else
            {
                if (!$_REQUEST['NombreUbica'])
                {
                    $this->frmError["MensajeError"] = "Seleccione o Digite todos los Datos.";
                    $this->FormaEditarClasificacionUbicacion($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['bandera'], $_REQUEST['tipoAlmacenaje'], $_REQUEST['NombreUbicaAct'], $_REQUEST['n1'], $_REQUEST['n2'], $_REQUEST['n3'], $_REQUEST['centinela']);
                    return true;
                }
            }
            if ($_REQUEST['bandera'] == 1)
            {
                $query = "UPDATE bodegas_ubicaciones_n1 SET tipo_almacenaje_id='" . $_REQUEST['tipoAlmacenaje'] . "',n1='" . $_REQUEST['NombreUbica'] . "' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n1='" . $_REQUEST['NombreUbicaAct'] . "'";
            }
            elseif ($_REQUEST['bandera'] == 2)
            {
                $query = "UPDATE bodegas_ubicaciones_n2 SET n2='" . $_REQUEST['NombreUbica'] . "' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n2='" . $_REQUEST['NombreUbicaAct'] . "' AND n1='" . $_REQUEST['n1'] . "'";
            }
            elseif ($_REQUEST['bandera'] == 3)
            {
                $query = "UPDATE bodegas_ubicaciones_n3 SET n3='" . $_REQUEST['NombreUbica'] . "' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n3='" . $_REQUEST['NombreUbicaAct'] . "' AND n1='" . $_REQUEST['n1'] . "' AND  n2='" . $_REQUEST['n2'] . "'";
            }
            elseif ($_REQUEST['bandera'] == 4)
            {
                $query = "UPDATE bodegas_ubicaciones_n4 SET n4='" . $_REQUEST['NombreUbica'] . "' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND centro_utilidad='" . $_REQUEST['centroutilidad'] . "' AND bodega='" . $_REQUEST['Bodega'] . "' AND n4='" . $_REQUEST['NombreUbicaAct'] . "' AND n1='" . $_REQUEST['n1'] . "' AND  n2='" . $_REQUEST['n2'] . "' AND n3='" . $_REQUEST['n3'] . "'";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        $this->CrearUbicacionesEmpresas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['centinela']);
        return true;
    }

    //=====================================================================================
    function EliminarUbicacionClasify()
    {
        list($dbconn) = GetDBconn();
        if ($_REQUEST['bandera'] == 1)
        {
            $query = "DELETE FROM bodegas_ubicaciones WHERE n1='" . $_REQUEST['n1'] . "'";
        }
        elseif ($_REQUEST['bandera'] == 2)
        {
            $query = "DELETE FROM bodegas_ubicaciones WHERE n1='" . $_REQUEST['n1'] . "' AND n2='" . $_REQUEST['n2'] . "'";
        }
        elseif ($_REQUEST['bandera'] == 3)
        {
            $query = "DELETE FROM bodegas_ubicaciones WHERE n1='" . $_REQUEST['n1'] . "' AND n2='" . $_REQUEST['n2'] . "' AND n3='" . $_REQUEST['n3'] . "'";
        }
        elseif ($_REQUEST['bandera'] == 4)
        {
            $query = "DELETE FROM bodegas_ubicaciones WHERE n1='" . $_REQUEST['n1'] . "' AND n2='" . $_REQUEST['n2'] . "' AND n3='" . $_REQUEST['n3'] . "' AND n4='" . $_REQUEST['n4'] . "'";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->CrearUbicacionesEmpresas($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['centroutilidad'], $_REQUEST['Bodega'], $_REQUEST['descripcion'], $_REQUEST['ubicacion'], $_REQUEST['responsable'], $_REQUEST['TipoNumeracion'], $_REQUEST['NomDpto'], $_REQUEST['centinela']);
        return true;
    }

    //=====================================================================================
    /**
     * Funcion que consulta las diferentes unidades de medida existentes en la base de datos
     * @return array;
     */
    function TiposDeGruposContratacion()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT grupo_contratacion_id,descripcion FROM inv_grupos_contrataciones";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                while (!$result->EOF)
                {
                    $vars[$result->fields[0]] = $result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    //=====================================================================================
    /**
     * Funcion que consulta las diferentes unidades de medida existentes en la base de datos
     * @return array;
     */
    function TiposAutorizacionesCompra()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT nivel_autorizacion_id,descripcion FROM inv_niveles_autorizacion_compras";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if ($result->EOF)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            else
            {
                while (!$result->EOF)
                {
                    $vars[$result->fields[0]] = $result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    //=====================================================================================
    function LlamaEditarProductoEmpresa()
    {

        $this->EditarProductoEmpresa($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
        return true;
    }

    //=====================================================================================
    function ActualizarProsuctoEmpresa()
    {

        if ($_REQUEST['Actualizar'])
        {
            if ($_REQUEST['existencia_minima'] > $_REQUEST['existencia_maxima'])
            {
                $this->frmError["MensajeError"] = "La Existencias Menores no pueden ser mayores a las existencias Maximas.";
                $this->EditarProductoEmpresa($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
                return true;
            }
            if ($_REQUEST['precioMinimo'] < 0)
            {
                $this->frmError["MensajeError"] = "El Precio Minimo, No Puede Ser Menor a Cero	";
                $this->EditarProductoEmpresa($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
                return true;
            }
            if ($_REQUEST['precioMaximo'] < 0)
            {
                $this->frmError["MensajeError"] = "El Precio Maximo, No Puede Ser Menor a Cero	";
                $this->EditarProductoEmpresa($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
                return true;
            }

            if ($_REQUEST[precioVenta] < 0)
            {
                $this->frmError["MensajeError"] = "El Precio Venta, No Puede Ser Menor a Cero	";
                $this->EditarProductoEmpresa($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
                return true;
            }

            list($dbconn) = GetDBconn();
            if ($_REQUEST['PtoVende'])
            {
                $PtoVende = 1;
            }
            else
            {
                $PtoVende = 0;
            }
            if ($_REQUEST['PtoService'])
            {
                $PtoService = 1;
            }
            else
            {
                $PtoService = 0;
            }
            $query = "UPDATE inventarios SET sw_vende='$PtoVende',sw_servicio='$PtoService',precio_minimo='" . $_REQUEST['precioMinimo'] . "',precio_maximo='" . $_REQUEST['precioMaximo'] . "',grupo_contratacion_id='" . $_REQUEST['grupoContratacion'] . "',nivel_autorizacion_id='" . $_REQUEST['autorizadorCompra'] . "',existencia_minima='" . $_REQUEST['existencia_minima'] . "',existencia_maxima='" . $_REQUEST['existencia_maxima'] . "' WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND codigo_producto='" . $_REQUEST['codigoProducto'] . "'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if ($_REQUEST['precioVenta'] != $_REQUEST['PrecioVentaActual'])
            {
			
				
                $query = "UPDATE inventarios SET precio_venta='" . $_REQUEST['precioVenta'] . "',precio_venta_anterior='" . $_REQUEST['PrecioVentaActual'] . "' , precio_regulado= '". $_REQUEST['precioVenta']."'  WHERE empresa_id='" . $_REQUEST['Empresa'] . "' AND codigo_producto='" . $_REQUEST['codigoProducto'] . "'";
                $result = $dbconn->Execute($query);
				
				$queryLog = "INSERT INTO inventarios_logs_precio_venta_producto VALUES ('" . $_REQUEST['Empresa'] . "', '" . $_REQUEST['codigoProducto'] . "', '" . $_REQUEST['PrecioVentaActual'] . "', '" . $_REQUEST['precioVenta'] . "', " . UserGetUID() . ", 'now()');";
				$resultLog = $dbconn->Execute($queryLog);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
				
				
				
                
                //echo $query;
               // exit();
            }
            $this->EditarProductoEmpresa($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoProducto'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
            return true;
        }
        $this->FormaListadoInventario($_REQUEST['Empresa'], $_REQUEST['NombreEmp'], $_REQUEST['grupo'], $_REQUEST['clasePr'], $_REQUEST['subclase'], $_REQUEST['NomGrupo'], $_REQUEST['NomClase'], $_REQUEST['NomSubClase'], $_REQUEST['Seleccion'], $_REQUEST['codigoPro'], $_REQUEST['descripcionPro']);
        return true;
    }

    //====================================================================================
    function llamarFormaReportes()
    {
        $this->formaReporteBusqueda();

        return true;
    }

    //====================================================================================
    function consultaProductos($tipo, $valor = "")//$tipo='c', $tipo='d', $tipo='t'->general, 
    {
        switch ($tipo)
        { //por codigo
            case "c": $query = "select ip.codigo_producto, descripcion from inventarios_productos ip, inventarios i where ip.estado='1' and ip.codigo_producto like '%" . $valor . "%' and i.codigo_producto=ip.codigo_producto order by codigo_producto";
                break;
            //CODIGO ALTERNO
            case "ca": $query = "select ip.codigo_producto, descripcion from inventarios_productos ip, inventarios i where ip.estado='1' and ip.cod_ihosp like '%" . $valor . "%' and i.codigo_producto=ip.codigo_producto order by codigo_producto";
                break;
            //por descripcion
            case "d": $cad = strtoupper($valor);
                $query = "select ip.codigo_producto, descripcion from inventarios_productos ip, inventarios i where i.codigo_producto=ip.codigo_producto and ip.estado='1' and (ip.descripcion like '%" . $valor . "%' or ip.descripcion like '%" . $cad . "%')  order by descripcion";
                break;
            //todos los registros
            case "t": $query = "select ip.codigo_producto as codigo, descripcion from inventarios_productos ip, inventarios i where ip.estado='1' and i.codigo_producto=ip.codigo_producto order by descripcion";
                break;
        }

        $matriz = $this->consultaBD($query, 2);
        return $matriz;
    }

    //====================================================================================
    function consultaBodegas()
    {
        $query = "select empresa_id, centro_utilidad, bodega, descripcion from bodegas where empresa_id='" . $_REQUEST['Empresa'] . "' order by descripcion";
        $matriz = $this->consultaBD($query, 2);
        return $matriz;
    }

    /* =======================================================================================================
      Consulta las fechas de vencimientos de los productos de inventario
      ======================================================================================================= */

    function consultaProductosVencimientos() //$emp_id="", $prod_cod="", $bod_cod="", $fecha="", $periodo=""
    {
        /*
          echo "EM: ".$_REQUEST['Empresa'];
          echo "<br>NOM: ".$_REQUEST['NombreEmp'];
          echo "<br>BOD: ".$_REQUEST['s_bodegas'];
          echo "<br>FEC: ".$_REQUEST['t_fecha'];
          echo "<br>PROD: ".$_REQUEST['prod_hide'];
          echo "<br>PERI: ".$_REQUEST['s_periodo'];
         */

        $emp_id = $_REQUEST['Empresa'];
        $prod_cod = $_REQUEST['prod_hide'];
        $bod_cod = $_REQUEST['s_bodegas'];
        $fecha = $_REQUEST['t_fecha'];
        $periodo = $_REQUEST['s_periodo'];

        $filtro_producto = "";
        $filtro_bodega = "";
        $filtro_fecha = "";
        $filtro_periodo = "";
        //------------------Realiza los filtros de la busqueda---------------------
        if ($prod_cod == '-1' && $bod_cod == '-1' && strlen($fecha) == 0) //Busca todos
        {
            $filtro_producto = "";
            $filtro_bodega = "";
            $filtro_fecha = "";
            $filtro_periodo = "";
        }
        else
        {
            if ($prod_cod != '-1')
                $filtro_producto = "and q.codigo_producto='" . $prod_cod . "'";
            if ($bod_cod != '-1')
                $filtro_bodega = "and q.bodega='" . $bod_cod . "'";

            if (strlen($fecha) > 0)
            {
                $fechaResult = $this->validarFecha($fecha);

                if ($fechaResult) //Si la fecha es valida
                {
                    switch ($periodo)
                    {
                        case "antes";
                            $filtro_periodo = "<";
                            break;
                        case "igual";
                            $filtro_periodo = "=";
                            break;
                        case "despues";
                            $filtro_periodo = ">";
                            break;
                    }

                    $filtro_fecha = "and f.fecha_vencimiento" . $filtro_periodo . "'" . $fechaResult . "' ";
                }
                else
                    $filtro_fecha = "and f.fecha_vencimiento='1111-11-11' "; //Para que la consulta no arroje un error
            }
            else
                $filtro_fecha = "";
        }

        $query = "SELECT q.b_desc, 
                   q.descripcion,
                   q.contenido_unidad_venta,
                   q.unidad_id, 
                   q.existencia, 
                   q.codigo_producto,  
                   f.lote,  
                   f.existencia_actual as cantidad, 
                   f.fecha_vencimiento,
                   q.subclase,
                   q.clase
                   
				FROM
				(
					SELECT        e.codigo_producto, 
                        e.existencia, 
                        b.bodega, 
                        b.descripcion as b_desc, 
                        e.centro_utilidad,
                        ip.descripcion, 
                        ip.contenido_unidad_venta, 
                        ip.unidad_id, 
                        i.empresa_id,
                        sub.descripcion as subclase,
                        cla.descripcion as clase
        			FROM 
                    existencias_bodegas e, 
                    bodegas b, 
                    inventarios_productos ip, 
                    inventarios i,
                    inv_subclases_inventarios as sub,
                    inv_clases_inventarios as cla
					WHERE 
					b.bodega=e.bodega 
          and e.centro_utilidad=b.centro_utilidad 
          and ip.codigo_producto=e.codigo_producto 
        	and i.codigo_producto=e.codigo_producto 
          and i.empresa_id=e.empresa_id
          and ip.clase_id = sub.clase_id
          and ip.grupo_id = sub.grupo_id
          and ip.subclase_id = sub.subclase_id
          and sub.grupo_id = cla.grupo_id
          and sub.clase_id = cla.clase_id
				) as q
				LEFT JOIN existencias_bodegas_lote_fv f ON 
                      f.codigo_producto=q.codigo_producto 
                  and f.centro_utilidad=q.centro_utilidad 
                  and q.bodega=f.bodega 
				
        WHERE 
        q.empresa_id='" . $emp_id . "' " . $filtro_producto . " " . $filtro_bodega . " " . $filtro_fecha . " 
				ORDER BY q.b_desc";

        $rst = null;
        //----------------------------Paginador-----------------------------
        if (!$rst = $this->consultaBD($query, 1))
            return false;

        $this->cont = 0;

        if (!$rst->EOF)
            $this->cont = $rst->RecordCount();

        $this->ProcesarSqlConteo($limite = null, $_REQUEST['offset']); //$limite=null

        $query.= " LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        if (!$result = $this->consultaBD($query, 1))
            return false;

        $vec = array();
        while (!$result->EOF)
        {
            $vec[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $result->close();

        return $vec;
    }

    //====================================================================================
    function ProcesarSqlConteo($limite = null, $offset = null)
    {
        $this->offset = 0;
        $this->paginaActual = 1;

        if ($limite == null)
        {
            $this->limit = GetLimitBrowser();
            if (!$this->limit)
                $this->limit = 25;
        }
        else
        {
            $this->limit = $limite;
        }

        if ($offset)
        {
            $this->paginaActual = intval($offset);
            if ($this->paginaActual > 1)
            {
                $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
        }

        return true;
    }

    /* ====================================================================================
      Mètodo para realizar consultas a la BD.
      $tipoRetorno=1 -> Retorna el RecordSet, $tipoRetorno=2 -> Retorna una Matriz
      ==================================================================================== */

    function consultaBD($query, $tipoRetorno)
    {
        list($dbconn) = GetDBConn();
        //$dbconn->debug = true;
        //$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0 || !$result)
        {
            $this->error = "Error en la Consulta";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            echo "<br>ERROR EN LA CONEXION O CONSULTA";
            $result->close();

            return false;
        }
        else
        {
            if ($tipoRetorno == 2)
            {
                while (!$result->EOF)
                {
                    $matriz[] = $result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }

                $result->close();
                return $matriz;
            }
            else
            {
                return $result;
            }
        }
    }

    //========================================================================
    function validarFecha($fecha)
    {
        $vec = explode("/", $fecha);

        if (count($vec) != 3)
        {
            $vec = explode("-", $fecha);

            if (count($vec) != 3)
                return false;
        }

        if ((strlen($vec[0]) == 4 && strlen($vec[1]) == 2 && strlen($vec[2]) == 2) || (strlen($vec[0]) == 2 && strlen($vec[1]) == 2 && strlen($vec[2]) == 4))
        {
            if (strlen($vec[2]) == 4)
                $fecha2 = $vec[2] . "-" . $vec[1] . "-" . $vec[0];
            else
                $fecha2 = $vec[0] . "-" . $vec[1] . "-" . $vec[2];

            return $fecha2;
        }
        else
            return false;
    }

}

//FIN CLASE USER