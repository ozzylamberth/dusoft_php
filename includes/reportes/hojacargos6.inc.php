<?php

/**
 * $Id: hojacargos6.inc.php,v 1.6 2007/03/13 16:03:04 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function DatosPrincipales5($cuenta)
  {
        list($dbconn) = GetDBconn();
				$query = "select count(*)
									from cuentas as a,
									fac_facturas_cuentas l
									where a.numerodecuenta=$cuenta 
									and a.numerodecuenta=l.numerodecuenta;";
				$result = $dbconn->Execute($query);
        if ($result->fields[0] > 0)
        {
        $query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
                  k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, 
									ingresos as d,
									fac_facturas_cuentas l
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and d.ingreso=a.ingreso 
									and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and l.numerodecuenta = a.numerodecuenta
									and l.empresa_id=i.empresa_id 
									and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";
				}
				else
				{
					$query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
										a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
										c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
										e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
										e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
										i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
										k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
										from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
										empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d
										where a.numerodecuenta=$cuenta 
										and a.plan_id=b.plan_id 
										and a.empresa_id=i.empresa_id
										and b.tercero_id=c.tercero_id
										and b.tipo_tercero_id=c.tipo_id_tercero
										and d.ingreso=a.ingreso 
										and d.tipo_id_paciente=e.tipo_id_paciente
										and d.paciente_id=e.paciente_id
										and i.tipo_pais_id=j.tipo_pais_id 
										and i.tipo_dpto_id=j.tipo_dpto_id
										and i.tipo_pais_id=k.tipo_pais_id 
										and i.tipo_dpto_id=k.tipo_dpto_id 
										and i.tipo_mpio_id=k.tipo_mpio_id
										and d.departamento_actual=h.departamento";
				}
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }

        $var=$result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
  }
  
  /*function InsumosCuentaC2($cuenta,$noFacturado)
  {
        if($noFacturado=='1'){
          $filFacturado=" and a.facturado='0'";
        }else{
          $filFacturado=" and a.facturado='1'";
        }
        list($dbconn) = GetDBconn();
        $querys = "SELECT f.descripcion as desagru,
                  a.*, e.descripcion, c.codigo_producto, g.descripcion as bodega,usu.usuario,
                  (CASE 
                    WHEN u.abreviatura IS NOT NULL THEN u.abreviatura 
                    ELSE u.unidad_id  
                  END) as unidad_venta
                  FROM cuentas_detalle as a
                  left join system_usuarios as usu on(usu.usuario_id=a.usuario_id),
                  bodegas_documentos_d as c, inventarios_productos as e
                  JOIN unidades u ON (e.unidad_id=u.unidad_id),                  
                  bodegas_doc_numeraciones as b, bodegas as g,
                  cuentas_codigos_agrupamiento as f
                  WHERE a.numerodecuenta=$cuenta
                  $filFacturado
                  and a.cargo <>'DIMD'
                  and a.consecutivo=c.consecutivo
                  and a.codigo_agrupamiento_id=f.codigo_agrupamiento_id
                  and c.codigo_producto=e.codigo_producto
                  and c.bodegas_doc_id=b.bodegas_doc_id
                  and g.bodega=b.bodega
                  order by f.codigo_agrupamiento_id,e.descripcion, c.codigo_producto";
                
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
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

  function DevolucionesInsumosCuentaC2($cuenta,$noFacturado)
  {
        if($noFacturado=='1'){
          $filFacturado=" and a.facturado='0'";
        }else{
          $filFacturado=" and a.facturado='1'";
        }
        list($dbconn) = GetDBconn();
        $querys = "SELECT f.descripcion as desagru,
                  a.*, e.descripcion, c.codigo_producto, g.descripcion as bodega,usu.usuario,
                  (CASE 
                    WHEN u.abreviatura IS NOT NULL THEN u.abreviatura 
                    ELSE u.unidad_id  
                  END) as unidad_venta
                  FROM cuentas_detalle as a
                  left join system_usuarios as usu on(usu.usuario_id=a.usuario_id),
                  bodegas_documentos_d as c, inventarios_productos as e
                  JOIN unidades u ON (e.unidad_id=u.unidad_id),                  
                  bodegas_doc_numeraciones as b, bodegas as g,
                  cuentas_codigos_agrupamiento as f
                  WHERE a.numerodecuenta=$cuenta
                  $filFacturado                 
                  and a.tarifario_id='SYS'
                  and a.cargo='DIMD'
                  and a.consecutivo=c.consecutivo
                  and a.codigo_agrupamiento_id=f.codigo_agrupamiento_id
                  and c.codigo_producto=e.codigo_producto
                  and c.bodegas_doc_id=b.bodegas_doc_id
                  and g.bodega=b.bodega
                  order by f.codigo_agrupamiento_id,e.descripcion, c.codigo_producto";
                
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
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
  
  


  function BuscarHa2()
  {
        list($dbconn) = GetDBconn();
        $query = "select a.fecha_ingreso, a.fecha_egreso, a.cama
                  from movimientos_habitacion as a
                  where a.numerodecuenta=$cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        while(!$result->EOF)
        {
                $var[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
        }
        $result->Close();
        return $var;
  }*/
  
  function Devoluciones5($cuenta)
  {
        list($dbconn) = GetDBconn();  
        $query = "SELECT a.prefijo, a.recibo_caja, a.fecha_registro, b.nombre, a.total_devolucion
                  FROM rc_devoluciones as a, system_usuarios as b
                  WHERE a.numerodecuenta=$cuenta and a.usuario_id=b.usuario_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {
            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $total+=$result->fields[4];
                    $result->MoveNext();
            }
            $var[0]['devoluciones']=$total;
        }
        return $var;                  
  }
  

  function CargosFacturaHoja5($cuenta)
  {
        
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $query="
        (SELECT a.*
        FROM (SELECT a.departamento_al_cargar,b.descripcion as departamento_nom,
        c.grupo_tipo_cargo,d.descripcion as grupo_cargo_nom,a.cargo_cups,e.descripcion as cargo_nom,
        (CASE WHEN a.facturado='1' THEN a.precio ELSE '0' END) as precio,
        sum(a.cantidad) as cantidad,'' as unidad,
        (CASE WHEN a.facturado='1' THEN sum(a.valor_cargo) ELSE '0' END) as valor_cargo, 
        (CASE WHEN a.facturado='1' THEN sum(a.valor_nocubierto) ELSE '0' END) as valor_paciente,
        (CASE WHEN a.facturado='1' THEN sum(a.valor_cubierto) ELSE '0' END) as valor_cliente
        
        FROM cuentas_detalle a
        LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id), 
        departamentos b,cups c, grupos_tipos_cargo d, 
        tarifarios_detalle e                                         
        
        WHERE a.numerodecuenta='".$cuenta."' 
        AND a.cargo!='DIMD' AND a.cargo!='IMD' 
        AND a.cargo!='DCTOREDON' 
        AND a.cargo!='APROVREDON'
        AND a.departamento_al_cargar=b.departamento
        AND a.cargo_cups=c.cargo        
        AND c.grupo_tipo_cargo=d.grupo_tipo_cargo
        AND a.cargo=e.cargo
        AND a.tarifario_id=e.tarifario_id
        AND f.cuenta_liquidacion_qx_id IS NULL
        
        GROUP BY a.departamento_al_cargar,b.descripcion,
        c.grupo_tipo_cargo,d.descripcion,a.cargo_cups,e.descripcion,
        a.facturado,a.precio,unidad) as a
        WHERE a.cantidad > 0
        )
        UNION
        (SELECT a.*
        
        FROM (SELECT a.departamento_al_cargar,a.departamento_nom,
        a.grupo_tipo_cargo,a.grupo_cargo_nom,a.codigo_producto as cargo_cups,a.cargo_nom,
        a.precio,sum(a.cantidad) as cantidad,a.unidad,sum(a.valor_cargo) as valor_cargo,
        sum(a.valor_paciente) as valor_paciente,sum(a.valor_cliente) as valor_cliente
        
        FROM (SELECT a.departamento_al_cargar,b.descripcion as departamento_nom,
        '0' as grupo_tipo_cargo,'INSUMOS Y MEDICAMENTOS' as grupo_cargo_nom,c.codigo_producto,        
        (CASE WHEN p_act.cod_principio_activo <> '000000' THEN
          (CASE WHEN p_act.descripcion <> '000000' THEN 
            (p_act.descripcion||' '||
            (CASE WHEN u.abreviatura <> '' THEN
            u.abreviatura 
            ELSE u.descripcion END)||' '||         
            d.contenido_unidad_venta)
             
            ELSE             
            d.descripcion
            END
          ) ||' '|| med.concentracion_forma_farmacologica
        ELSE d.descripcion 
        END) as cargo_nom,
        (CASE WHEN a.facturado='1' THEN a.precio ELSE '0' END) as precio,
        (CASE WHEN a.cargo='DIMD' THEN (sum(a.cantidad))*-1 ELSE sum(a.cantidad) END) as cantidad,
        (CASE WHEN u.abreviatura IS NOT NULL THEN
         u.abreviatura 
         ELSE u.unidad_id END) as unidad,
        (CASE WHEN a.facturado='1' THEN sum(a.valor_cargo) ELSE '0' END) as valor_cargo, 
        (CASE WHEN a.facturado='1' THEN sum(a.valor_nocubierto) ELSE '0' END) as valor_paciente,
        (CASE WHEN a.facturado='1' THEN sum(a.valor_cubierto) ELSE '0' END) as valor_cliente,
        a.cargo        
        FROM cuentas_detalle a, departamentos b,bodegas_documentos_d c,         
        inventarios_productos d
        JOIN unidades u ON (d.unidad_id=u.unidad_id)
        LEFT JOIN medicamentos med ON(d.codigo_producto=med.codigo_medicamento)        
        LEFT JOIN inv_med_cod_principios_activos p_act ON(p_act.cod_principio_activo=med.cod_principio_activo)
        WHERE a.numerodecuenta='".$cuenta."' 
        AND (a.cargo='DIMD' OR a.cargo='IMD')         
        AND a.departamento_al_cargar=b.departamento
        AND a.consecutivo=c.consecutivo
        AND c.codigo_producto=d.codigo_producto        

        GROUP BY a.departamento_al_cargar,b.descripcion,
        grupo_tipo_cargo,grupo_cargo_nom,c.codigo_producto,cargo_nom,
        a.facturado,a.precio,unidad,a.cargo) as a
        
        GROUP BY 
        a.departamento_al_cargar,a.departamento_nom,a.grupo_tipo_cargo,
        a.grupo_cargo_nom,a.codigo_producto,a.cargo_nom,a.precio,
        a.unidad order by a.cargo_nom ASC) as a
        WHERE a.cantidad>0
        )
        ORDER BY cargo_nom ASC
        
        ";
        
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }
        if($result->RecordCount()>0){
          while ($data = $result->FetchRow()){
            $datos[$data['departamento_al_cargar']][$data['departamento_nom']]
            [$data['grupo_tipo_cargo']][$data['grupo_cargo_nom']]
            [$data['cargo_cups']]=$data;
          }        
        }
        return $datos;
  }
  
  function CargosQXFacturaHoja5($numerodecuenta){
  
    list($dbconn) = GetDBconn();    
    GLOBAL $ADODB_FETCH_MODE;    
    $query="SELECT b.cuenta_liquidacion_qx_id,b.descripcion as cargo_agrupamiento,
    d.consecutivo_procedimiento,d.cargo as cargo_procedimiento,
    d.tarifario_id as tarifario_id_procedimiento,f.descripcion as nom_procedimiento,
    h.descripcion as via,
    a.cargo_cups,c.descripcion as cargo_nom,
    (CASE WHEN a.facturado='1' THEN a.precio ELSE '0' END) as precio,
    a.cantidad,'' as unidad,a.valor_cargo, 
    a.valor_nocubierto as valor_paciente,
    a.valor_cubierto as valor_cliente,
    ter.nombre_tercero
    
    FROM cuentas_detalle a
    LEFT JOIN cuentas_detalle_profesionales as prof on(a.transaccion=prof.transaccion)
    LEFT JOIN terceros as ter on(prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id),
    cuentas_codigos_agrupamiento b,
    tarifarios_detalle c, cuentas_cargos_qx_procedimientos d
    JOIN tarifarios_detalle f ON (f.tarifario_id=d.tarifario_id AND f.cargo=d.cargo),
    cuentas_liquidaciones_qx g
    JOIN qx_vias_acceso h ON(g.via_acceso=h.via_acceso)
    
    WHERE a.numerodecuenta=$numerodecuenta
    AND a.cargo!='DIMD' AND a.cargo!='IMD'
    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id    
    AND a.tarifario_id=c.tarifario_id AND a.cargo=c.cargo
    AND a.transaccion=d.transaccion     
    AND a.facturado='1'
    AND b.cuenta_liquidacion_qx_id=g.cuenta_liquidacion_qx_id";
    
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($result->EOF){
        $this->error = "Error al ejecutar la consulta.<br>";
        $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
        return false;
    }
    if($result->RecordCount()>0){
      while ($data = $result->FetchRow()){
        $datos[$data['cuenta_liquidacion_qx_id']][$data['cargo_agrupamiento']]
        [$data['consecutivo_procedimiento']]
        [$data['tarifario_id_procedimiento']][$data['cargo_procedimiento']]
        [$data['nom_procedimiento']][$data['via']][$data['cargo_cups']]=$data;
      }        
    }
    return $datos;
  
  }
  
  

  function GenerarHojaCargos6($datos)
  { 
      $total_cantidad=$total_valor_unitario=0;
      $_SESSION['REPORTES']['VARIABLE']='hoja_cargos_agrupada';
      $dat=DatosPrincipales5($datos[numerodecuenta]);
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$dat;
      //IncludeLib("tarifario");
      IncludeLib("funciones_admision");
      IncludeLib("funciones_facturacion");
      $Dir="cache/hojacargos6".$datos[numerodecuenta].".pdf";
      require_once("classes/fpdf/html_class.php");
      include_once("classes/fpdf/conversor.php");
      define('FPDF_FONTPATH','font/');
      $pdf2d=new PDF();
      $pdf2d->AddPage();
      $pdf2d->SetFont('Arial','',7);
      //$usu=NombreUsuario();
      $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
      $vector=CargosFacturaHoja5($datos[numerodecuenta]);           
      foreach($vector as $departamento=>$vector1){
        foreach($vector1 as $departamento_nom=>$vector2){
          $html.="<tr><td width=700><B>".$departamento_nom."</B></td></tr>";          
          foreach($vector2 as $grupoCargo=>$vector3){ 
            $SumValorPaciente=0;
            $SumValorCliente=0;  
            foreach($vector3 as $grupoCargo_nom=>$vector4){                
              $html.="<tr><td width=40>&nbsp;</td><td width=650><B>".$grupoCargo_nom."</B></td></tr>";                
              foreach($vector4 as $cargo=>$vector6){
                $html.="<tr>";                  
                $html.="<td width=70><B>".$cargo."</B></td>";
                $html.="<td width=390><B>".substr($vector6['cargo_nom'],0,60)."</B></td>";
                $var=explode('.',$vector6['cantidad']);                
                if($var[1]==0)$vector6['cantidad']=$var[0];
                $total_cantidad +=$vector6['cantidad'];
                $total_valor_unitario +=$vector6['precio'];
                $html.="<td width=50 align=\"RIGHT\"><B>".$vector6['cantidad']."</B></td>";
                $html.="<td width=30 align=\"RIGHT\"><B>".str_pad($vector6['unidad'],4," ",STR_PAD_LEFT)."</B></td>";                
                $html.="<td width=50 align=\"RIGHT\"><B>".FormatoValor($vector6['precio'])."</B></td>";
                $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($vector6['valor_paciente'])."</B></td>";
                $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($vector6['valor_cliente'])."</B></td>";                
                $html.="</tr>";
                $SumValorPaciente+=$vector6['valor_paciente'];
                $SumValorCliente+=$vector6['valor_cliente'];
              }
            }
            $html.="<tr>";                  
            $html.="<td width=590 align=\"RIGHT\"><B>SUBTOTAL</B></td>";            
            $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($SumValorPaciente)."</B></td>";
            $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($SumValorCliente)."</B></td>";
            $html.="</tr>";
          }          
        }
      }
      
      $vector=CargosQXFacturaHoja5($datos[numerodecuenta]);         
      foreach($vector as $liquidacionid => $vector0){
        $SumValorPaciente=0;
        $SumValorCliente=0;
        foreach($vector0 as $agrupamiento => $vector1){
          $html.="<tr><td width=60></td><td width=700><B>".$agrupamiento."</B></td></tr>";      
          foreach($vector1 as $consecutivo_proc => $vector2){
            foreach($vector2 as $tarifario_proc => $vector3){
              foreach($vector3 as $cargo_proc => $vector4){
                foreach($vector4 as $nombre_proc => $vector5){
                  foreach($vector5 as $via_proc => $vector6){                    
                    $html.="<tr><td width=80></b>PROCEDIMIENTO:</td><td width=400></b>".$cargo_proc." - ".$nombre_proc."</td></tr>";
                    $html.="<tr><td width=80></b>VIA ACCESO:</td><td width=400></b>".$via_proc."</td></tr>";                    
                    foreach($vector6 as $cargo => $vector7){
                      $html.="<tr>";                  
                      $html.="<td width=70><B>".$cargo."</B></td>";
                      $html.="<td width=390><B>".substr($vector7['cargo_nom'],0,60)."</B></td>";
                      $var=explode('.',$vector7['cantidad']);                
                      if($var[1]==0)$vector7['cantidad']=$var[0];
                      $total_cantidad +=$vector7['cantidad'];
                      $total_valor_unitario +=$vector7['precio'];
                      $html.="<td width=50 align=\"RIGHT\"><B>".$vector7['cantidad']."</B></td>";
                      $html.="<td width=30 align=\"RIGHT\"><B></B></td>";                
                      $html.="<td width=50 align=\"RIGHT\"><B>".FormatoValor($vector7['precio'])."</B></td>";
                      $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($vector7['valor_paciente'])."</B></td>";
                      $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($vector7['valor_cliente'])."</B></td>";                
                      $html.="</tr>";
                      if(!empty($vector7['nombre_tercero'])){
                        $html.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$vector7['nombre_tercero']."</td></tr>";
                      }
                      $SumValorPaciente+=$vector7['valor_paciente'];
                      $SumValorCliente+=$vector7['valor_cliente'];
                    }
                    $html.="<tr><td width=480></td></tr>";                    
                  }
                }
              }
            }
          }  
        }
        $html.="<tr>";                  
        $html.="<td width=590 align=\"RIGHT\"><B>SUBTOTAL</B></td>";            
        $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($SumValorPaciente)."</B></td>";
        $html.="<td width=80 align=\"RIGHT\"><B>".FormatoValor($SumValorCliente)."</B></td>";
        $html.="</tr>";  
      }
        
        
        
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      
      
      //--------------------HABITACIONES-----------------------------
      //Arranque cali movimientos de habitaciones
      
      if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
      {
          die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
      }
      
      $liqHab = new LiquidacionHabitaciones;
      $hab = $liqHab->LiquidarCargosInternacion($datos[numerodecuenta],false);
            
      if(is_array($hab))
      {
        $html.="<tr><td width=700><b>HABITACIONES</b></td></tr>";
        $html.="<tr>";
        $html.="<td width=100><b>TARIF.</b></td>";
        $html.="<td width=100><b>CARGO</b></td>";
        $html.="<td width=300><b>DESCRIPCION</b></td>";
        $html.="<td width=100><b>PRECIO</b></td>";
        $html.="<td width=100><b>CANTIDAD</b></td>";
        $html.="<td width=100><b>TOTAL</b></td>";
        $html.="</tr>"; 
        $total=0;
        for($i=0; $i<sizeof($hab); $i++)
        {   
            $total_cantidad += $hab[$i][cantidad];
            $total_valor_unitario += $hab[$i][valor_cargo];
            $html.="<tr>";
            $html.="<td width=100><b>".$hab[$i][tarifario_id]."</b></td>";
            $html.="<td width=100><b>".$hab[$i][cargo]."</b></td>";
            $html.="<td width=300><b>".substr($hab[$i][descripcion],0,55)."</b></td>";
            $html.="<td width=100><b>".FormatoValor($hab[$i][precio_plan])."</b></td>";
            $html.="<td width=100><b>".$hab[$i][cantidad]."</b></td>";
            $html.="<td width=100><b>".FormatoValor($hab[$i][valor_cargo])."</b></td>";
            $html.="</tr>";             
            $totalEstancia +=$hab[$i][valor_cargo];     
        }       
        $html.="<tr>";
        $html.="<td width=700><b>TOTAL ESTANCIA:</b></td>";
        $html.="<td width=100><b>".FormatoValor($totalEstancia)."</b></td>";        
        $html.="</tr>";               
        
        $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
        
      }
      //fin
      
      $caja=PagosCuenta($datos[numerodecuenta]);
      $abono=0;
      if(!empty($caja))
      {
          $html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=40 align=\"CENTER\">RET FTE</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][recibo_caja]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_ingcaja]."</td><td width=140 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
              $abono+=$caja[$i][total_abono];
          }
      }
      else
      {
          $caja='';
          $caja=PagosCajaRapida($datos[numerodecuenta]);
          $abono=0;
          $html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=40 align=\"CENTER\">RET FTE</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][factura_fiscal]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_registro]."</td><td width=140 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
              $abono+=$caja[$i][total_abono];
          }
      }
      $dev = Devoluciones5($datos[numerodecuenta]);  
      if(!empty($dev))
      {           
          $html.="<tr><td width=80 align=\"CENTER\">DEVCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=320 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL DEVOLUCION</td></tr>";
          for($i=0; $i<sizeof($dev); $i++)
          {
              $fec=explode('.',$dev[$i][fecha_registro]);
              $html.="<tr><td width=80 align=\"CENTER\">".$dev[$i][prefijo]."".$dev[$i][recibo_caja]."</td><td width=100 align=\"CENTER\">".$fec[0]."</td><td width=140 align=\"CENTER\">".$dev[$i][nombre]."</td><td width=320 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($dev[$i][total_devolucion])."</td></tr>";
          }
      }     
      $html.="<tr><td><br></td></tr>";
      $html.="<tr><td width=520>TOTAL DE ABONOS: </td><td width=240 align=\"RIGHT\">".FormatoValor($abono)."</td></tr>";
  
      //hay devoluciones
      if(!empty($dev))
      {
        $html.="<tr><td width=520>TOTAL DEVOLUCIONES: </td><td width=240 align=\"RIGHT\">".FormatoValor($dev[0]['devoluciones'])."</td></tr>";
      
      } 
      //APROVECHAMIENTO
      $apro=BuscarCargoAjusteApro($datos[numerodecuenta]);
      if(!empty($apro))
      {
          $html.="<tr><td width=520>DEVOLUCION P: </td><td width=240 align=\"RIGHT\">".FormatoValor($apro['precio'])."</td></tr>";
      }
      //DESCUENTO
      $des=BuscarCargoAjusteDes($datos[numerodecuenta]);
      if(!empty($des))
      {
          $html.="<tr><td width=520>DESCUENTO POR REDONDEO: </td><td width=240 align=\"RIGHT\">".FormatoValor($des)."</td></tr>";
      }
      $html.="<tr><td width=520>TOTAL CUENTA: </td><td width=240 align=\"RIGHT\">".FormatoValor($dat[total_cuenta]+$totalEstancia)."</td></tr>";
      $html.="<tr><td width=520>TOTAL CANTIDAD CARGOS: </td><td width=240 align=\"RIGHT\">".FormatoValor($total_cantidad)."</td></tr>";
      $html.="<tr><td width=520>TOTAL VALOR UNITARIO: </td><td width=240 align=\"RIGHT\">".FormatoValor($total_valor_unitario)."</td></tr>";
      $html.="<tr><td width=150>CARGO A CUENTA DE: </td><td width=370>".$dat[nombre_tercero]."</td><td width=240 align=\"RIGHT\">".FormatoValor($dat[valor_total_empresa])."</td></tr>";
      $saldo=SaldoCuentaPaciente($datos[numerodecuenta]);
      $html.="<tr><td width=520>SALDO PACIENTE: </td><td width=240 align=\"RIGHT\">".FormatoValor($saldo)."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";        
      $html.="</table>";      
      //--------------------------------------------
      if(!empty($vector)){
        /*for($i=0; $i<sizeof($NoFacturados);$i++)
        {
          //$html.="<tr><td width=60 align='LEFT'>".$NoFacturados[$i][cargo]."</td><td width=200 align=\"LEFT\">".substr($NoFacturados[$i][descripcion],0,37)."</td><td width=40>".FormatoValor($NoFacturados[$i][cantidad])."</td><td width=70 align=CENTER>".FormatoValor($NoFacturados[$i][precio])."</td><td width=70 align=\"CENTER\">".FormatoValor($NoFacturados[$i][valor_nocubierto])."</td><td width=75 align=\"CENTER\">".FormatoValor($NoFacturados[$i][valor_cubierto])."</td><td width=80 align=\"CENTER\">".FormatoValor($NoFacturados[$i][valor_cargo])."</td></tr>";
          $html.="<tr><td width=60 align='LEFT'>".$NoFacturados[$i][cargo]."</td><td width=200 align=\"LEFT\">".substr($NoFacturados[$i][descripcion],0,37)."</td><td width=40>".FormatoValor($NoFacturados[$i][cantidad])."</td><td width=70 align=CENTER>".FormatoValor(0)."</td><td width=70 align=\"CENTER\">".FormatoValor(0)."</td><td width=75 align=\"CENTER\">".FormatoValor(0)."</td><td width=80 align=\"CENTER\">".FormatoValor(0)."</td></tr>";
        }*/
        $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
        $html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
        $html.="</table>";
        $pdf2d->WriteHTML($html);
        $pdf2d->Output($Dir,'F');
      }
      else
      {
        $html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
        $html.="</table>";
        $pdf2d->WriteHTML($html);
        $pdf2d->Output($Dir,'F');
      }
      
      return true;      
   }
?>
