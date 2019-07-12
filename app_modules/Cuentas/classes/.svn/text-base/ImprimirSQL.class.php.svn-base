 <?php
/******************************************************************************
* $Id: ImprimirSQL.class.php,v 1.1 2011/03/22 16:04:30 hugo Exp $
* @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* $Revision: 1.1 $ 
* 
* @autor
********************************************************************************/
  
IncludeClass('OpcionesCuentasHTML','','app','Cuentas');
IncludeClass('ImprimirHTML','','app','Cuentas');
IncludeClass('app_Cuentas_user','','app','Cuentas');
class ImprimirSQL
{
  function ImprimirSQL(){}
 /**
  * La funcion  ConsultaPagosCaja se encarga de obtener de la base de datos
  * los totales de los cheques, las tarjetas, los efectivos y el total de los
  * recibos de caja anteriores, segun la cuenta de una persona.
  * @access public
  * @return array
  */
  function ConsultaPagosCaja($Cuenta)
  {
     list($dbconn) = GetDBconn();
     //$dbconn->debug=true;
     $query = "SELECT   e.empresa_id,
                                  e.recibo_caja,
                                  e.centro_utilidad, e.prefijo,
                                  e.fecha_ingcaja, e.total_abono,
                                  e.total_bonos, e.total_efectivo,
                                  e.total_cheques, e.total_tarjetas,
                                  e.tipo_id_tercero, e.tercero_id,
                                 e.estado, e.fecha_registro, e.usuario_id, e.caja_id, e.cuenta_tipo_id
                     FROM    recibos_caja e ,
                               (
                                  SELECT  recibo_caja ,
                                               empresa_id ,
                                               centro_utilidad,
                                               prefijo
                                  FROM    rc_detalle_hosp as h
                                  WHERE   numerodecuenta=".$Cuenta."
                             UNION DISTINCT
                                      SELECT  recibo_caja ,
                                                   empresa_id ,
                                                   centro_utilidad,
                                                   prefijo
                                       FROM    rc_detalle_cargos_ambulatorios
                                      WHERE   numerodecuenta=".$Cuenta."
                              UNION DISTINCT
                                       SELECT  h.recibo_caja ,
                                                    h.empresa_id ,
                                                    h.centro_utilidad,
                                                    h.prefijo
                                       FROM    pagares c, 
                                                    rc_detalle_pagare h
                                      WHERE   h.numerodecuenta=".$Cuenta."
                                      AND       c.empresa_id = h.empresa_id
                                      AND       c.numero = h.numero
                                      AND       c.prefijo = h.prefijo_pagare
                                  ) D,
                                 cajas as a
                    WHERE  e.estado = '0'
                    AND      a.caja_id = e.caja_id
                    AND      a.cuenta_tipo_id='01'
                    AND      e.recibo_caja = D.recibo_caja 
                    AND      e.empresa_id = d.empresa_id 
                    AND      e.centro_utilidad = d.centro_utilidad
                    AND      e.prefijo = d.prefijo 

                     
            ";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          $this->fileError = __FILE__;
                          $this->lineError = __LINE__;
            return false;
          }
          $i=0;
          while (!$result->EOF) {
                $vars[$i]= $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
                $i++;
          }
          $result->Close();
         
      return $vars;
  }
  
  
  
  function BuscarDatos($Recibo,$Prefijo,$Empresa,$CenU,$TipoId,$PacienteId,$PlanId, $caja_id)
 {
     if (empty($_REQUEST['PlanId']))
        $_REQUEST['PlanId']=$_SESSION['planid'];
     else
        $_SESSION['planid']=$_REQUEST['PlanId'];
    list($dbconn) = GetDBconn();
    //$dbconn->debug=true;
  
       $sql= "  SELECT   a.fecha_ingcaja,
                                  a.recibo_caja,
                                  a.prefijo,
                                  a.caja_id,
								  a.cuenta_tipo_id,
                                  a.fecha_registro,
                                  a.total_abono,
                                  a.total_efectivo,
                                  a.total_tarjetas,
                                  a.total_cheques,
                                  a.total_bonos,
                                  b.razon_social,
                                  c.descripcion,
                                  d.plan_descripcion,
                                  e.usuario,
                                  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' || f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                                  f.tipo_id_paciente||' '||f.paciente_id as id,
								  g.tipo_id_tercero,
								  g.tercero_id,
								  g.nombre_tercero
                  FROM       recibos_caja a,
                                  empresas b,
                                  centros_utilidad c,
                                  planes d,
                                  system_usuarios e,pacientes f,
								  terceros g
                 WHERE      a.recibo_caja='".$Recibo."'
                 AND          a.prefijo='".$Prefijo."'
                 AND          a.empresa_id=b.empresa_id
                 AND          c.empresa_id='".$Empresa."'
                 AND          c.centro_utilidad='".$CenU."'
                 AND          d.plan_id='".$PlanId."'
                 AND          a.usuario_id=e.usuario_id
                 AND          tipo_id_paciente='".$TipoId."'
                 AND          paciente_id='".$PacienteId."'
                 AND          a.caja_id=".$caja_id."
                 AND          a.estado IN ('0')
				 AND          d.tipo_tercero_id = g.tipo_id_tercero
				 AND          d.tercero_id = g.tercero_id ";
   
        $resulta=$dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) 
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var=$resulta->GetRowAssoc($ToUpper = false);
        
			   
                            //GenerarReciboDevolucion($var);
         return $var;
   }
   
   
  function BuscarDatosCajaRapida($Cuenta)
 {
     if (empty($_REQUEST['PlanId']))
        $_REQUEST['PlanId']=$_SESSION['planid'];
     else
        $_SESSION['planid']=$_REQUEST['PlanId'];
    list($dbconn) = GetDBconn();
    //$dbconn->debug=true;
  
       $query = " SELECT 		c.prefijo,
                                c.factura_fiscal,
								c.total_abono,
                                c.total_efectivo,
								c.total_cheques,
								c.total_tarjetas,
                                c.sw_cuota_moderadora,
								c.fecha_registro,
								c.numerodecuenta
                      FROM    	fac_facturas_contado as c
                      WHERE  	c.numerodecuenta=".$Cuenta."
					  AND       c.estado = '0'
                       ";
   
        $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          $this->fileError = __FILE__;
                          $this->lineError = __LINE__;
            return false;
          }
          $i=0;
          while (!$result->EOF) {
                $vars[$i]= $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
                $i++;
          }
          $result->Close();
         
      return $vars;
   }

   /*
   $query = " SELECT c.prefijo,
                                   c.factura_fiscal, 
                                   a.valor_nocubierto, 
                                   a.precio, 
                                   a.cargo, 
                                   a.tarifario_id, 
                                   a.cantidad, 
                                   a.fecha_cargo, 
                                   a.transaccion, 
                                   b.descripcion as desccargo,
                                   f.empresa_id, 
                                   c.total_efectivo,
                                   f.valor_cuota_paciente,
                                   f.valor_nocubierto,
                                   f.valor_cubierto,
                                   f.valor_cuota_moderadora,
                                   c.sw_cuota_moderadora
                      FROM    cuentas_detalle as a, 
                                  tarifarios_detalle as b, 
                                  fac_facturas_contado as c, 
                                   cuentas as f 
                      WHERE  a.numerodecuenta=".$cuenta." 
                      AND       a.numerodecuenta=f.numerodecuenta
                      AND       a.cargo=b.cargo 
                      AND       a.tarifario_id=b.tarifario_id 
                      AND       a.cargo!='DESCUENTO' 
                      AND       c.numerodecuenta=a.numerodecuenta 
                       ";
   */
}
?>