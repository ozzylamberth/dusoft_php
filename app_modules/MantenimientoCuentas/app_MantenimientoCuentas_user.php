<?php

/**
 * $Id: $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_MantenimientoCuentas_user extends classModulo
{

    var $limit;
    var $conteo;

     function app_MantenimientoCuentas_user()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     /**
     *
     */
     function main()
     {
          unset($_SESSION['BIO']);
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     
          $query = "SELECT b.razon_social as descripcion1, b.empresa_id
                    FROM userpermisos_mantenimientocuentas as a, empresas as b
                    WHERE a.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id";
          
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al ejecutar el query de permisos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resulta->FetchRow()) {
               $bioestadistica[$data['descripcion1']]= $data;
          }
     
          $url[0]='app';
          $url[1]='MantenimientoCuentas';
          $url[2]='user';
          $url[3]='Menu';
          $url[4]='Bio';
     
          $arreglo[0]='EMPRESA';
     
          $this->salida.= gui_theme_menu_acceso('MANTENIMIENTO CUENTAS',$arreglo,$bioestadistica,$url,ModuloGetURL('system','Menu'));
          return true;
     }

     function Menu()
     {
          if(empty($_SESSION['MANTENIMIENTO_CUENTAS']['EMPRESA']))
          {
               $_SESSION['MANTENIMIENTO CUENTAS']['EMPRESA']=$_REQUEST['Bio']['empresa_id'];
               $_SESSION['MANTENIMIENTO CUENTAS']['NOM_EMP']=$_REQUEST['Bio']['descripcion1'];
          }
          if(!$this->FormaMenus()){
               return false;
          }
          return true;
     }

     function LlamarFormaBuscarPaciente()
     {
          if(!$this->FormaBuscarPaciente()){
               return false;
          }
          return true;
     }

     function TiposIdPacientes()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while (!$result->EOF) {
               $vars[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          $result->Close();
          return $vars;
     }

     function BuscarPaciente($modificar)
     {
          $filtroTipoDocumento = '';
          $filtroDocumento='';
          $filtroNombres='';

/*          if($_REQUEST[Nombres]=='' 
             AND empty($_REQUEST[Documento])
             AND empty($_REQUEST[Cuenta]))
          {
            $this->frmError["MensajeError"]="NO HAY DATOS PARA LA BUSQUEDA.";
           $this->FormaMenus();
           return true;
          }*/
          list($dbconn) = GetDBconn();
          if($_REQUEST[refrescar])
          {
               $query = "UPDATE fac_facturas_cuentas
                         SET numerodecuenta = numerodecuenta
                         WHERE numerodecuenta = '$_REQUEST[Cuenta]';";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar totales facturas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
              //
                      if($_REQUEST[Estado]== '1' OR $_REQUEST[Estado]== '2')      
                      {
                        $sql = " estado = '0' ";
                      }
                      else
                      if($_REQUEST[Estado]== '0')
                      {
                        $sql = " estado = '1' ";
                      }
                        list($dbconn) = GetDBconn();
                            $query = "UPDATE cuentas SET $sql
                                      WHERE numerodecuenta = '$_REQUEST[Cuenta]';";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                  $this->error = "Error al actualizar cuentas";
                                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                  return false;
                            }
              //
               $this->frmError["MensajeError"]="TOTALES DE LAS FACTURAS ACUALIZADOS.";
                      
          }
          
          if(empty($_REQUEST[Cuenta]))
          {
            $this->frmError["MensajeError"]="NO HAY DATOS PARA LA BUSQUEDA.";
           $this->FormaMenus();
           return true;
          }
         
/*          if($_REQUEST[TipoDocumento]!='')
          {   $filtroTipoDocumento=" AND P.tipo_id_paciente = '".$_REQUEST[TipoDocumento]."'";   }

          if (!empty($_REQUEST[Documento]))
          {   $filtroDocumento =" AND P.paciente_id ='".$_REQUEST[Documento]."'";   }

          if ($_REQUEST[Nombres] != '')
          {
               $a=explode(' ',$_REQUEST[Nombres]);
               foreach($a as $k=>$v)
               {
                    if(!empty($v))
                    {
                         $filtroNombres.=" and (upper(P.primer_nombre||' '||P.segundo_nombre||' '||
                                             P.primer_apellido||' '||P.segundo_apellido) like '%".strtoupper($_REQUEST[Nombres])."%')";
                    }
               }
          }*/
         
          if (!empty($_REQUEST[Cuenta]))
          {   $filtroCuenta =" AND C.numerodecuenta ='".$_REQUEST[Cuenta]."'";   }

          if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

          //list($dbconn) = GetDBconn();
//           if(empty($_REQUEST['paso']))
//           {
//                $query = "SELECT	C.*, P.tipo_id_paciente, P.paciente_id,
//                                    P.primer_nombre||' '||P.segundo_nombre||' '||P.primer_apellido||' '||P.segundo_apellido as nombre
//                          FROM pacientes P,
//                               ingresos I,
//                               cuentas C
//                           WHERE P.paciente_id IS NOT NULL
//                           AND P.tipo_id_paciente = I.tipo_id_paciente
//                           AND P.paciente_id = I.paciente_id
//                           AND I.ingreso = C.ingreso
//                           AND I.estado IN ('0','1','2')
//                           AND C.estado NOT IN ('4','5') --4  ANTICIPOS 5 ANULADA
//                           $filtroTipoDocumento $filtroDocumento $filtroNombres
//                           $filtroCuenta ";
//                $result=$dbconn->Execute($query);
//                if ($dbconn->ErrorNo() != 0) {
//                     $this->error = "Error al buscar";
//                     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                     return false;
//                }
//                if(!$result->EOF)
//                {
//                     $_SESSION['SPY']=$result->RecordCount();
//                }
//                $result->Close();
//           }

/*    echo      $query = "SELECT FF. total_factura, FFC.*, C.*
                    --, P.tipo_id_paciente, P.paciente_id,
                    --P.primer_nombre||' '||P.segundo_nombre||' '||P.primer_apellido||' '||P.segundo_apellido
                    -- as nombre
                    FROM 
                    --pacientes P,
                    --    ingresos I,
                        cuentas C JOIN fac_facturas_cuentas FFC
                        ON (C.numerodecuenta = FFC.numerodecuenta)
                                JOIN fac_facturas FF
                        ON (FFC.prefijo = FF.prefijo
                            AND FFC.factura_fiscal = FF.factura_fiscal
                             AND FF.estado NOT IN ('2','3'))
                        LEFT JOIN envios_detalle ED ON
                        (
                          FFC.empresa_id = ED.empresa_id
                          AND FFC.prefijo = ED.prefijo 
                          AND FFC.factura_fiscal = ED.factura_fiscal
                        )
                    WHERE
                    -- P.paciente_id IS NOT NULL
                    --AND P.tipo_id_paciente = I.tipo_id_paciente
                    --AND P.paciente_id = I.paciente_id
                    --AND I.ingreso = C.ingreso
                    --AND I.estado IN ('0','1','2')
                    --AND
                    C.estado NOT IN ('4','5') --4  ANTICIPOS 5 ANULADA
                    AND ED.envio_id IS NULL
                    $filtroTipoDocumento $filtroDocumento $filtroNombres $filtroCuenta
                    --order by nombre
                    --LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";*/
         $query = "SELECT FF. total_factura, FFC.*, C.*,ED.envio_id
                    FROM 
                        cuentas C JOIN fac_facturas_cuentas FFC
                        ON (C.numerodecuenta = FFC.numerodecuenta)
                                JOIN fac_facturas FF
                        ON (FFC.prefijo = FF.prefijo
                            AND FFC.factura_fiscal = FF.factura_fiscal
                             AND FF.estado NOT IN ('2','3'))
                        LEFT JOIN envios_detalle ED ON
                        (
                          FFC.empresa_id = ED.empresa_id
                          AND FFC.prefijo = ED.prefijo 
                          AND FFC.factura_fiscal = ED.factura_fiscal
                        )
                    WHERE
                    C.estado NOT IN ('4','5')
                    --AND ED.envio_id IS NULL
                    $filtroTipoDocumento $filtroDocumento $filtroNombres $filtroCuenta
                   ";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al buscar";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          if($var[0][estado] <> '0' AND $modificar <> '1'
              AND empty($var[0][prefijo])
              AND empty($var[0][factura_fiscal]))
          {
            $this->frmError["MensajeError"]="LA CUENTA NO ESTA FACTURADA O ESTA ENVIADA"; 
            $this->FormaMenus();
            return true;
          }
          
          if(empty($var))
          {  $this->frmError["MensajeError"]="NO SE OBTUVO RESULTADOS.";  }
          
          $this->FormaMenus($var);
          return true;
     }

      function LlamarModificarCuenta()
      {
        if($_REQUEST[Estado]== '1' OR $_REQUEST[Estado]== '2')      
        {
          $estado = "0";
        }
        else
        if($_REQUEST[Estado]== '0')
        {
          $estado = "1";
        }
          list($dbconn) = GetDBconn();
               $query = "UPDATE cuentas SET estado = '$estado'
                         WHERE numerodecuenta = '$_REQUEST[Cuenta]';";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar cuentas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else
               {
                if($estado == '0')
                {
                    $query = "UPDATE fac_facturas_cuentas
                              SET numerodecuenta = numerodecuenta
                              WHERE numerodecuenta = '$_REQUEST[Cuenta]';";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error al actualizar totales facturas";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          return false;
                    }
                }
                //if(empty($_REQUEST[nojustificar]))
                //{
                  if(empty($_REQUEST[motivo_id]))
                     $_REQUEST[motivo_id] = '0';
                 $query = "INSERT INTO auditoria_activacion_cuentas
                         (
                          numerodecuenta,
                          tipo_justificacion_activacion_cuenta_id,
                          estado_anterior,
                          estado_actual,
                          fecha_registro,
                          observacion,
                          usuario_id
                         )
                         VALUES
                         (
                          $_REQUEST[Cuenta],
                          $_REQUEST[motivo_id],
                          '$_REQUEST[Estado]',                          
                          $estado,
                          now(),
                          '$_REQUEST[observacion]',
                          ".UserGetUID()."
                         );";
                  $result=$dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al insertar en auditoria_activacion_cuentas";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                  }
                //}
               }
          $this->BuscarPaciente($_REQUEST[modificar]);
          return true;
        
      }
  
	/**
	* La funcion tipo_id_paciente se encarga de obtener de la base de datos
	* los diferentes tipos de identificacion de los paciente.
	* @access public
	* @return array
	*/
	function tipo_id_paciente()
  	{
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
                    return false;
               }
               while (!$result->EOF) {
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
               }
          }
          $result->Close();
          return $vars;
	}
		
  function GetMotivosActivacionCuenta()
  {
        list($dbconn) = GetDBconn();
        $query = "SELECT * 
                  FROM tipos_justificacion_activacion_cuenta
                  WHERE tipo_justificacion_activacion_cuenta_id <> 0
                  ORDER BY descripcion";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar tipos_justificacion_activacion_cuenta";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
        }
        else{
              if($result->EOF){
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "La tabla maestra 'tipos_justificacion_activacion_cuenta' esta vacia ";
                  return false;
              }
              while (!$result->EOF) {
                  $vars[$result->fields[0]]=$result->fields[1];
                  $result->MoveNext();
              }
        }
        $result->Close();
        return $vars;
  }

	/****cambiamos la ubicacion de las fechas********/
	function Change_Formatt_Date($fecha)
	{
		$f=explode("-",$fecha);
		return $f[2]."-".$f[1]."-".$f[0];
	}

//------------------------------------------------------------------------------
}//fin clase user
?>