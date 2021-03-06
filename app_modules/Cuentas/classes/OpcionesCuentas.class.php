<?php
  /******************************************************************************
  * $Id: OpcionesCuentas.class.php,v 1.12 2011/07/25 20:37:18 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.12 $ 
	* 
	* @autor
  ********************************************************************************/
  
	IncludeClass('OpcionesCuentasHTML','','app','Cuentas');
	IncludeClass('app_Cuentas_userclasses_HTML','','app','Cuentas');
	IncludeClass('app_Cuentas_user','','app','Cuentas');
	class OpcionesCuentas
	{
		function OpcionesCuentas(){}
		/**********************************************************************************
		* 
		* 
		* @return array 
		***********************************************************************************/
      /**
      *LlamaFormaMensaje
      */
			function LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton)
			{
				$frm = new app_Cuentas_user();
				$html = $frm->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);
				return $html;
			}
      /**
      *GuardarNuevoPlan
      */
      function GuardarNuevoPlan($PlanId,$Cuenta,$Ingreso,$TipoId,$PacienteId,$Nivel,$Fecha,$Nuevo_Responsable)
      {
          list($dbconn) = GetDBconn();
          //si no es cambio de plan a soat
         
          
          
          if(!empty($_SESSION['SOAT']['RETORNO']))
          {
       
//                 $Cuenta=$_REQUEST['Cuenta'];
//                 $TipoId=$_REQUEST['TipoId'];
//                 $PacienteId=$_REQUEST['PacienteId'];
//                 $Nivel=$_REQUEST['Nivel'];
//                 $PlanId=$_REQUEST['PlanId'];
//                 $Ingreso=$_REQUEST['Ingreso'];
//                 $Fecha=$_REQUEST['Fecha'];
//                 $TipoAfiliado=$_REQUEST['TipoAfiliado'];
//                 $Nivel=$_REQUEST['Nivel'];

                if(!empty($_SESSION['SOAT']['NOEVENTO']))
                { 


                    unset($_SESSION['SOAT']);
                    $mensaje='El Paciente no tiene eventos creados, Debe tener Eventos para el Cambio de Plan.';
										$titulo = "Agregar IyM";
										$accion = SessionGetVar("AccionVolverCargosIYM");
										$boton = "";
										$html = $this->LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton);
                    //if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
                    //return false;
                    //}
                    //return true;
                  return $html;
                }
                else
                {
                 
                     $query = "select * from ingresos_soat
                               where ingreso=$Ingreso and evento=".$_SESSION['SOAT']['RETORNO']['evento']."";
                     $result=$dbconn->Execute($query);
                     if ($dbconn->ErrorNo() != 0) {
                           $this->error = "Error al Guardar en cambio_responsable";
                           $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                           return false;
                     }
                     //si el evento no estaba guardo en ingresos_soat
                     if($result->EOF)
                     {
                     
                         $query = "INSERT INTO ingresos_soat (ingreso,evento)
                                   VALUES($Ingreso,".$_SESSION['SOAT']['RETORNO']['evento'].")";
                         $result=$dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0) {
                               $this->error = "Error al Guardar en cambio_responsable2";
                               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                               echo $this->mensajeDeError;
                               return false;
                         }
                     }
					 else
					 {
                        //ACTUALIZAR EVENTO
                        $query = "UPDATE ingresos_soat SET
                                  evento=".$_SESSION['SOAT']['RETORNO']['evento']."
                                  WHERE ingreso = $Ingreso;";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Actualizar en ingresos_soat";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              echo $this->mensajeDeError;
                              return false;
                        }
					 }
						 

                    //unset($_SESSION['SOAT']);
                    $query = "SELECT rango, 
                                     tipo_afiliado_id
                              FROM   planes_rangos
                              WHERE  plan_id='".$Nuevo_Responsable."'";
                    $result=$dbconn->Execute($query);
                    $Nivel=$result->fields[0];
                    $TipoAfiliado=$result->fields[1];
                }
          }

                
     
          if(empty($_SESSION['SOAT']['RETORNO']))
          {
         
              if(empty($PlanId) AND empty($Cuenta))
              { 
                  if($_REQUEST['TipoAfiliado']==-1 OR $_REQUEST['Nivel']==-1)
                  {
                  
                      if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=-1; }
                      if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=-1; }
                      $this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
                      echo $this->frmError["MensajeError"];
                      $fact = new OpcionesCuentasHTML();
                      $html = $fact->FormaDatosAfiliado($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
                      return $html;
/*                      $this->FormaDatosAfiliado($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
                      return true;*/
                  }

/*                  $Cuenta=$_REQUEST['Cuenta'];
                  $TipoId=$_REQUEST['TipoId'];
                  $PacienteId=$_REQUEST['PacienteId'];
                  $Nivel=$_REQUEST['Nivel'];
                  $PlanId=$_REQUEST['PlanId'];
                  $Ingreso=$_REQUEST['Ingreso'];
                  $Fecha=$_REQUEST['Fecha'];
                  $TipoAfiliado=$_REQUEST['TipoAfiliado'];
                  $Nivel=$_REQUEST['Nivel'];*/
              }
              else
              {
            
                if($_REQUEST['TipoAfiliado']==-1 OR $_REQUEST['Nivel']==-1)
                {
                
                  $query = "SELECT rango, 
                                   tipo_afiliado_id
                            FROM   planes_rangos 
                            WHERE  plan_id=".$Nuevo_Responsable."";
                  $result=$dbconn->Execute($query);
                  $Nivel=$result->fields[0];
                  $TipoAfiliado=$result->fields[1];
                }
                else
                {
                
                  $Nivel=$_REQUEST['Nivel'];
                  $TipoAfiliado=$_REQUEST['TipoAfiliado'];
                  
                
                
       
                 
                }
              }
          }

          $sem=$_REQUEST['Semanas'];
          if(empty($sem))
          { $sem=0; }

          list($dbconn) = GetDBconn();
        /*  $query="select a.cambio_responsable_id, b.cambio_responsable_detalle_actual_id
                  from cambio_Responsable as a, cambio_responsable_detalle_actual as b, cambio_responsable_detalle_nuevo as c
                  where a.numerodecuenta=$Cuenta and
                  b.cambio_responsable_id=a.cambio_responsable_id and b.cambio_responsable_detalle_actual_id=c.cambio_responsable_detalle_actual_id";
        */  $query = " SELECT a.cambio_responsable_id 
                       FROM   cambio_Responsable as a
                       WHERE  a.numerodecuenta=$Cuenta 
                       AND    a.usuario_id_inicio=".UserGetUID()."";
                    
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en cambio_responsable3";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }

          if(!$result->EOF)
          {
         
         $query = "DELETE 
                        FROM   cambio_Responsable
                        WHERE  numerodecuenta=$Cuenta 
                        AND    usuario_id_inicio=".UserGetUID()."";
             
               $result=$dbconn->Execute($query);
            
              if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en cambio_responsable";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
              }
          }

          $query = "SELECT nextval('cambio_responsable_cambio_responsable_id_seq')";
          $result=$dbconn->Execute($query);
          $cambio=$result->fields[0];

          $query = "INSERT INTO cambio_responsable(
                                          cambio_responsable_id,
                                          numerodecuenta,
                                          ingreso,
                                          plan_id_actual,
                                          plan_id_nuevo,
                                          usuario_id_inicio,
                                          fecha_registro_inicio,
                                          usuario_id_final,
                                          fecha_registro_final,
                                          tipo_afiliado_id,
                                          rango,
                                          semanas_cotizadas)
                    VALUES(
                                          $cambio,
                                          $Cuenta,
                                          $Ingreso,
                                          $PlanId,
                                          ".$Nuevo_Responsable.",".UserGetUID().",
                                          now(),
                                          0,
                                          NULL,
                                          '$TipoAfiliado',
                                          '$Nivel',
                                          $sem);";

          $dbconn->BeginTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en 1cambio_responsable";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              echo $this->mensajeDeError;
              $dbconn->RollbackTrans();
              return false;
          }

                //no es desde division de cuenta
                if(empty($_SESSION['CUENTA']['DIVISION']))
                {
         
                        $query = "SELECT a.*, c.codigo_agrupamiento_id, b.descripcion,
                                            d.descripcion as descripcion_agru,
                                            d.bodegas_doc_id as bodegas_doc_id_agru,
                                            d.numeracion as numeracion_agru,
                                            d.cuenta_liquidacion_qx_id
                                            from cuentas_detalle as a
                                  LEFT JOIN cuentas_codigos_agrupamiento d ON(a.codigo_agrupamiento_id=d.codigo_agrupamiento_id),
                                            tarifarios_detalle as b,
                                            grupos_tipos_cargo as c 
                                     WHERE ((    a.numerodecuenta=$Cuenta 
                                            AND a.cargo=b.cargo
                                            AND a.tarifario_id=b.tarifario_id 
                                            AND b.grupo_tipo_cargo=c.grupo_tipo_cargo and c.grupo_tipo_cargo!='SYS'
                                            AND d.cuenta_liquidacion_qx_id IS NULL))
                                   ORDER BY a.codigo_agrupamiento_id         ";
                }
                else
                {

 
                        $query = "SELECT a.*,
                                         d.descripcion as descripcion_agru,
                                         d.bodegas_doc_id as bodegas_doc_id_agru,
                                         d.numeracion as numeracion_agru,
                                         d.cuenta_liquidacion_qx_id
                                  FROM   tmp_division_cuenta as a
                               LEFT JOIN cuentas_codigos_agrupamiento d ON(a.codigo_agrupamiento_id=d.codigo_agrupamiento_id)
                                  WHERE  a.numerodecuenta=$Cuenta 
                                    AND  a.cuenta='".$_SESSION['CUENTA']['CAMBIO']['indice']."' 
                                    AND  a.plan_id=".$Nuevo_Responsable."
                                ORDER BY a.plan_id,a.codigo_agrupamiento_id";
                                
                }
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en cambio_responsable4";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
          }
          while(!$resulta->EOF)
          {
              $Datos[]=$resulta->GetRowAssoc($ToUpper = false);
              $resulta->MoveNext();
          }
          $resulta->Close();
          if(!empty($Datos))
          {
        
                for($i=0; $i<sizeof($Datos); $i++)
                {
                        if(!empty($Datos[$i][autorizacion_int]) AND $Datos[$i][autorizacion_int]==='0')
                        {   $AutoInt=$Datos[$i][autorizacion_int];   }
                        else
                        {   $AutoInt='NULL';   }
                        if(!empty($Datos[$i][autorizacion_ext]) AND $Datos[$i][autorizacion_ext]==='0')
                        {   $AutoExt=$Datos[$i][autorizacion_ext];  }
                        else
                        {   $AutoExt='NULL';   }

                        if(empty($Datos[$i][codigo_agrupamiento_id]))
                        {   $Datos[$i][codigo_agrupamiento_id]='NULL';   }
                        if(empty($Datos[$i][consecutivo]))
                        {   $Datos[$i][consecutivo]='NULL';   }

                        if(empty($Datos[$i][cuenta_liquidacion_qx_id]))
                        {   $Datos[$i][cuenta_liquidacion_qx_id]='NULL';   }


                         //esta validaciones solamente para los medicamentos


                        if($Datos[$i][consecutivo]==='NULL' && $Datos[$i][cuenta_liquidacion_qx_id]=='NULL'){
                          if(empty($Datos[$i][codigo_agrupamiento_id])){
                            $agrupamiento='NULL';
                          }else{
                            $agrupamiento=$Datos[$i][codigo_agrupamiento_id];
                          }
                        }else{
                          if(in_array($Datos[$i][codigo_agrupamiento_id],$CodigosAgrupamiento['anterior'])){
                            for($cont=0;$cont<sizeof($CodigosAgrupamiento['anterior']);$cont++){
                              if($Datos[$i][codigo_agrupamiento_id]==$CodigosAgrupamiento['anterior'][$cont]){
                                $agrupamiento=$CodigosAgrupamiento['nuevo'][$cont];
                                break;
                              }
                            }
                          }else{
                            if(empty($Datos[$i][bodegas_doc_id_agru]))
                            {   $Datos[$i][bodegas_doc_id_agru]='NULL';   }
                            if(empty($Datos[$i][numeracion_agru]))
                            {   $Datos[$i][numeracion_agru]='NULL';   }
                            $query="SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq')";
                            $result=$dbconn->Execute($query);
                            $Nuevoagrupamiento=$result->fields[0];
                            $query = "INSERT INTO cuentas_codigos_agrupamiento(
                                                  codigo_agrupamiento_id,
                                                  descripcion,
                                                  bodegas_doc_id,
                                                  numeracion,
                                                  cuenta_liquidacion_qx_id)
                                           VALUES($Nuevoagrupamiento,
                                                  '".$Datos[$i][descripcion_agru]."',
                                                  ".$Datos[$i][bodegas_doc_id_agru].",
                                                  ".$Datos[$i][numeracion_agru].",
                                                  ".$Datos[$i][cuenta_liquidacion_qx_id].")";

                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Guardar en la Base de Datos";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              echo $this->mensajeDeError;
                              $dbconn->RollbackTrans();
                              return false;
                            }
                            $CodigosAgrupamiento['anterior'][]=$Datos[$i][codigo_agrupamiento_id];
                            $CodigosAgrupamiento['nuevo'][]=$Nuevoagrupamiento;
                            $agrupamiento=$Nuevoagrupamiento;

                          }

                        }
                        //fin validacion
										$query = "INSERT INTO cambio_responsable_detalle_actual(
																cambio_responsable_id,
																transaccion,
																empresa_id,
																centro_utilidad,
																numerodecuenta,
																departamento,
																tarifario_id,
																cargo,
																cantidad,
																precio,
																valor_cargo,
																valor_nocubierto,
																valor_cubierto,
																usuario_id,
																facturado,
																fecha_cargo,
																fecha_registro,
																valor_descuento_empresa,
																valor_descuento_paciente,
																porcentaje_gravamen,
																sw_liq_manual,
																servicio_cargo,
																autorizacion_int,
																autorizacion_ext,
																sw_cuota_paciente,
																sw_cuota_moderadora,
																codigo_agrupamiento_id,
																consecutivo,
																cargo_cups,
																sw_cargue)
												     VALUES (
                                $cambio,
                                ".$Datos[$i][transaccion].",
                                '".$Datos[$i][empresa_id]."',
                                '".$Datos[$i][centro_utilidad]."',
                                $Cuenta,
                                '".$Datos[$i][departamento]."',
                                '".$Datos[$i][tarifario_id]."',
                                '".$Datos[$i][cargo]."',
                                ".$Datos[$i][cantidad].",
                                ".$Datos[$i][precio].",
                                ".$Datos[$i][valor_cargo].",
                                ".$Datos[$i][valor_nocubierto].",
                                ".$Datos[$i][valor_cubierto].",
                                ".$Datos[$i][usuario_id].",
                                ".$Datos[$i][facturado].",
                                '".$Datos[$i][fecha_cargo]."',
                                '".$Datos[$i][fecha_registro]."',
                                ".$Datos[$i][valor_descuento_empresa].",
                                ".$Datos[$i][valor_descuento_paciente].",
                                ".$Datos[$i][porcentaje_gravamen].",
                                ".$Datos[$i][sw_liq_manual].",
                                ".$Datos[$i][servicio_cargo].",
                                $AutoInt,
                                $AutoExt,
                                ".$Datos[$i][sw_cuota_paciente].",
                                ".$Datos[$i][sw_cuota_moderadora].",
                                $agrupamiento,
                                ".$Datos[$i][consecutivo].",
                                '".$Datos[$i][cargo_cups]."',
                                '".trim($Datos[$i][sw_cargue])."')";

                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al cuentas_detalle";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              echo $this->mensajeDeError;
                            $dbconn->RollbackTrans();
                            return false;
                        }
                }

          }

          $dbconn->CommitTrans();
                //cuando es division
                if(!empty($_SESSION['CUENTA']['DIVISION']))
                { 
                        //cuando tiene cargos la cuenta de division
                        if(!empty($Datos))
                        {

                                $query = "SELECT tarifario_id,
                                                 cargo,    
                                                 cambio_responsable_detalle_actual_id
                                            FROM cambio_responsable_detalle_actual
                                           WHERE cambio_responsable_id=$cambio 
                                             AND cargo in('IMD','DIMD')";
                                $resulta = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al cuentas_detalle";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                                if(!$resulta->EOF)
                                {
                                        while(!$resulta->EOF)
                                        {
                                                $imd[]=$resulta->GetRowAssoc($ToUpper = false);
                                                $resulta->MoveNext();
                                        }
                                        $resulta->Close();
                                }
                                //no solo son medicamentos y va a equivalencias
                                if(sizeof($Datos) != sizeof($imd))
                                {
                               
                                        $fact = new OpcionesCuentasHTML();
                                        $html = $fact->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
                                        return $html;
                                }
                                else
                                {       //solo son medicamentos y no va a equivalencias y se debe llenar el vector
                                         //$_REQUEST['Nuevo_Responsable'] = $Nuevo_Responsable;
                                         $_REQUEST['Cambio'] = $cambio;
                                         $_SESSION['CUENTA']['REQUEST'] = $_REQUEST;
                                        foreach($_SESSION['CUENTA']['REQUEST'] as $k => $v)
                                        {
                                                if(substr_count($k,'New'))
                                                {   unset($_SESSION['CUENTA']['REQUEST'][$k]);  }
                                        }

                                        for($i=0; $i<sizeof($imd); $i++)
                                        {
                                                $_SESSION['CUENTA']['REQUEST']['New'.$imd[$i]['tarifario_id'].$imd[$i]['cargo'].$imd[$i]['cambio_responsable_detalle_actual_id']]=$imd[$i]['cambio_responsable_detalle_actual_id'].",".$imd[$i]['tarifario_id'].",".$imd[$i]['cargo'];
                                        }

                                        $html = $this->GuardarEquivalenciasDivision();
                                        return $html;
                                }

                        }
                        else
                        
                        {   
                       //es solo de abonos
                                //$_REQUEST['Nuevo_Responsable'] = $Nuevo_Responsable;
                                $_REQUEST['Cambio']=$cambio;
                                $_SESSION['CUENTA']['REQUEST']=$_REQUEST;
                                foreach($_SESSION['CUENTA']['REQUEST'] as $k => $v)
                                {
                                        if(substr_count($k,'New'))
                                        {
                                            unset($_SESSION['CUENTA']['REQUEST'][$k]);
                                        }
                                }
                                //unset($_SESSION['CUENTA']['REQUEST']);
                                $html = $this->GuardarEquivalenciasDivision();
                                return $html;
                        }
                }
          //unset($_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
          //$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'] = $Nuevo_Responsable;
          //cuando tiene cargos la cuenta
          if(!empty($x) || !empty($Datos))
          {
              //$this->DetalleCambioACtual($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
              $fact = new OpcionesCuentasHTML();
              $html = $fact->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$Nuevo_Responsable);
              return $html;
          }
          else
          {
          
              $html = $this->InsertarSinCargos($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$cambio,$Nuevo_Responsable);
              return $html;
          }
      }

      /**
      *
      */
      function InsertarSinCargos($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$cambio,$Nuevo_Responsable)
      {
          IncludeLib("tarifario_cargos");
          //$PlanId=$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'];
          $PlanId=$Nuevo_Responsable;
          //ACTUALIZA CAMBIO RESPONSABLE
          list($dbconn) = GetDBconn();
          $query = " UPDATE cambio_responsable SET
                            usuario_id_final=".UserGetUID().",
                            fecha_registro_final=now()
                     WHERE cambio_responsable_id=$cambio";
              $dbconn->BeginTrans();
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
          //ACTUALIZAR LA CUENTA
          $query = "SELECT *
                    FROM   cambio_responsable
                    WHERE  cambio_responsable_id=$cambio";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $vars=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();

          $query = " UPDATE cuentas SET
                            tipo_afiliado_id='".$vars[tipo_afiliado_id]."',
                            rango='".$vars[rango]."',
                            semanas_cotizadas=".$vars[semanas_cotizadas].",
                            plan_id=".$PlanId."
                      WHERE numerodecuenta=$Cuenta";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }

          $dbconn->CommitTrans();
          $mensaje='Se Cambio el Responsable de la Cuenta No. '.$Cuenta;
          //$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
          //if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
          //return false;
          //}
          //return true;
          $accion=SessionGetVar("AccionVolverCargosIYM");
          $fact = new OpcionesCuentasHTML();
          $html = $fact->FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'');
          return $html;
      }
    /**
    * Metodo donde se obtiene informacion del plan
    *
    * @param integer $plan Identificador del plab
    *
    * @return mixed
    */
    function NombrePlan($plan)
    {
      list($dbconn) = GetDBconn();
      $query = "SELECT  plan_descripcion,
                        tipo_tercero_id,
                        tercero_id,
                        sw_tipo_plan,
                        empresa_id
                FROM    planes  
                WHERE   plan_id = ".$plan." ";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $datos=$result->GetRowAssoc($ToUpper = false);
      
      return $datos;
    }

      /**
      *
      */
      function Equivalencias($PlanId,$Cuenta,$cargo,$tarifario,$Nuevo_Responsable)
      {
            //$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']
            list($dbconn) = GetDBconn();
            $query = "SELECT distinct a.cargo as cargoact, 
                                      a.tarifario_id as tarifarioact,
                                      b.descripcion as desact, 
                                      b.grupo_tarifario_id as grupoact, 
                                      b.subgrupo_tarifario_id as subact, 
                                      d.grupo_tarifario_id as gruponew,
                                      d.subgrupo_tarifario_id as subnew, 
                                      e.cargo as cargocups, 
                                      e.tarifario_id as tarifariocups, 
                                      q.cargo as cargonew, 
                                      q.tarifario_id as tarifarionew,
                                      g.descripcion as desnew
                                 FROM tarifarios_detalle as b LEFT JOIN tarifarios_equivalencias AS e ON (b.cargo=e.cargo and b.tarifario_id=e.tarifario_id)
                            LEFT JOIN cups AS p ON(e.cargo_base=p.cargo)
                            LEFT JOIN tarifarios_equivalencias AS q ON (q.cargo_base=p.cargo)
                            LEFT JOIN tarifarios_detalle AS g ON(q.cargo=g.cargo and q.tarifario_id=g.tarifario_id),
                                      cambio_responsable_detalle_actual AS a,
                                      plan_tarifario AS c
                            LEFT JOIN plan_tarifario AS d ON (d.plan_id=".$Nuevo_Responsable."
                                  AND d.grupo_tarifario_id=c.grupo_tarifario_id 
                                  AND d.subgrupo_tarifario_id=c.subgrupo_tarifario_id)
                                WHERE a.numerodecuenta=$Cuenta 
                                  AND a.cargo='$cargo'
                                  AND a.tarifario_id='$tarifario' 
                                  AND c.plan_id=$PlanId
                                  AND b.grupo_tarifario_id=c.grupo_tarifario_id
                                  AND b.subgrupo_tarifario_id=c.subgrupo_tarifario_id 
                                  AND a.cargo=b.cargo 
                                  AND a.tarifario_id=b.tarifario_id";
          $resulta = $dbconn->Execute($query);
            if(!$resulta->EOF)
            {
                while(!$resulta->EOF)
                {
                    $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }
            }
            $resulta->Close();
            return $vars;
      }

        /**
        *
        */
        function ValidarContratoEqui($tarifario,$cargo,$plan)
        {
              list($dbconn) = GetDBconn();

              $query = "(   SELECT r.plan_id
                                                    FROM  tarifarios_detalle as h, plan_tarifario as r
                                                    WHERE h.cargo='$cargo' 
                                                    AND   h.tarifario_id='$tarifario'
                                                    AND   r.plan_id=$plan 
                                                    AND   h.grupo_tarifario_id=r.grupo_tarifario_id
                                                    AND   h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
                                                    AND   h.tarifario_id=r.tarifario_id
                                                    AND   excepciones(r.plan_id,h.tarifario_id,h.cargo)=0
                                            )
                                            UNION
                                            (
                                                    SELECT b.plan_id
                                                    FROM   tarifarios_detalle a, 
                                                           excepciones b, 
                                                           subgrupos_tarifarios e
                                                    WHERE  a.cargo='$cargo' 
                                                    and    a.tarifario_id='$tarifario'
                                                    and    b.plan_id = $plan 
                                                    AND    b.tarifario_id = a.tarifario_id 
                                                    AND    b.sw_no_contratado = 0 
                                                    AND    b.cargo = a.cargo 
                                                    AND    e.grupo_tarifario_id = a.grupo_tarifario_id 
                                                    AND    e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                        )";
              $result=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar en la Tabal autorizaiones";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }

              if(!$result->EOF)
              {   $var=$result->RecordCount();  }

              return $var;
        }

      /**
      *
      */
      function InsertarNuevoPlan($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$Nuevo_Responsable)
      {
/*            $Cuenta=$_REQUEST['Cuenta'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Fecha=$_REQUEST['Fecha'];*/
            unset($_SESSION['CUENTA']['REQUEST']);
            $f=$d=0;

            foreach($_REQUEST as $k => $v)
            {
              if(substr_count($k,'New'))
              {
                if(!empty($v))
                { $f=1; $d++;}
              }
            }
            if($f==0)
            {
                $this->frmError["MensajeError"]="Debe Elegir los Cargos Equivalentes.";
                echo $this->frmError["MensajeError"];
                $fact = new OpcionesCuentasHTML();
                $html = $fact->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$Nuevo_Responsable);
                return $html;
            }

            $_SESSION['CUENTA']['REQUEST']=$_REQUEST;

            if($_REQUEST['Cant'] > $d)
            {
                $mensaje='Existe '.($_REQUEST['Cant']-$d).' Cargos de Equivalencia sin elegir, Esta seguro de Continuar.';
                $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado,'Nuevo_Responsable'=>$Nuevo_Responsable);
                $c='app';
                $m='Cuentas';
                $me='LlamaGuardarEquivalencias';
                $me2='LlamarFormaEquivalencias';
                $Titulo='CAMBIO DE RESPONSBALE CUENTA No. '.$Cuenta;
                $boton1='ACEPTAR';
                $boton2='CANCELAR';

                //$fact = new app_Cuentas_user();
                //$html = $fact->LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                $html = $this->LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                return $html;
            }
            else
            {
                //$fact = new app_Cuentas_user();
                $html = $this->GuardarEquivalencias();
                return $html;
            }
      }
    /**
    * Metodo donde se realiza la reliquidacion de los insumos de una cuenta
    *
    * @return mixed
    */
    function ReliquidarMedicamentos()
    {
      $Cuenta=$_REQUEST['Cuenta'];
      $Pieza=$_REQUEST['Pieza'];
      $Cama=$_REQUEST['Cama'];
      
      $rst = $this->ReliquidarDetalleCuenta($Cuenta,SessionGetVar("EmpresaId"),2);
      if(!$rst)
      {
        $msj  = "HA OCURRIDO UN ERROR DURANTE LA REALIQUIDACION DE LA CUENTA ".$Cuenta;
        $msj .= "<br>".$this->mensajeDeError;
        $accion = SessionGetVar("AccionVolverCargosIYM");
        
        $fact = new OpcionesCuentasHTML();
        $html = $fact->FormaMensaje($msj,$titulo,$accion,$boton);
        return $html;
      }

      $msj = "SE RELIQUIDARON ".$this->cnt." INSUMOS Y/O MEDICAMENTOS, DE LA CUENTA No. ".$Cuenta;
      $ttl = "RELIQUIDAR MEDICAMENTOS DE LA CUENTA No. ".$Cuenta;
      $accion = SessionGetVar("AccionVolverCargosIYM");
      
      $fact = new OpcionesCuentasHTML();
      $html = $fact->FormaMensaje($msj,$ttl,$accion,$boton);
      return $html;
    }
    /**
    * Metodo donde se realiza la reliquidacion de los cargos de una cuenta
    *
    * @return mixed
    */
		function ReliquidarCargos()
		{
      $rst = $this->ReliquidarDetalleCuenta($_REQUEST['Cuenta'],SessionGetVar("EmpresaId"),1);
      if(!$rst)
      {
        $msj  = "HA OCURRIDO UN ERROR DURANTE LA REALIQUIDACION DE LA CUENTA ".$_REQUEST['Cuenta'];
        $msj .= "<br>".$this->mensajeDeError;
        $accion = SessionGetVar("AccionVolverCargosIYM");
        
        $fact = new OpcionesCuentasHTML();
        $html = $fact->FormaMensaje($msj,$titulo,$accion,$boton);
        return $html;
      }

      $msj = "SE RELIQUIDARON ".$this->cnt." CARGOS, DE LA CUENTA No. ".$_REQUEST['Cuenta'];
      $ttl = "RELIQUIDACION CARGOS CUENTA No. ".$Cuenta;
      $accion = SessionGetVar("AccionVolverCargosIYM");
      
      $fact = new OpcionesCuentasHTML();
      $html = $fact->FormaMensaje($msj,$ttl,$accion,$boton);
      return $html;
		}
		/**
		* Metodo para la reliquidacion de una cuenta
		* 
		* @param integer $Cuenta numero de cuenta
		* @param string $TipoId tipo de documento
		* @param string $PacienteId numero de documento
		* @param string $Nivel rango
		* @param integer $PlanId Identificador del plan
		* @param integer $Ingreso Identificador del ingreso
		* @param date $Fecha fecha
    * @param string $paso Idica desde donde se realizo el llamado de la funcion
    *
		* @return boolean
		*/
    function Reliquidar($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$paso)
    {
      if(empty($Cuenta) && empty($TipoId) && empty($PacienteId))
      {
        $TipoId=$_REQUEST['TipoId'];
        $PacienteId=$_REQUEST['PacienteId'];
        $Nivel=$_REQUEST['Nivel'];
        $PlanId=$_REQUEST['PlanId'];
        $Pieza=$_REQUEST['Pieza'];
        $Cama=$_REQUEST['Cama'];
        $Fecha=$_REQUEST['Fecha'];
        $Ingreso=$_REQUEST['Ingreso'];
        $Cuenta=$_REQUEST['Cuenta'];
      }
      
      $rst = $this->ReliquidarDetalleCuenta($Cuenta,SessionGetVar("EmpresaId"));
      if(!$rst)
      {
        $ttl = "MENSAJE DE ERROR";
        $msj  = "HA OCURRIDO UN ERROR DURANTE LA REALIQUIDACION DE LA CUENTA ".$Cuenta;
        $msj .= "<br>".$this->mensajeDeError;
        $accion = SessionGetVar("AccionVolverCargosIYM");
        
        $fact = new OpcionesCuentasHTML();
        $html = $fact->FormaMensaje($msj,$ttl,$accion,$boton);
        return $html;
      }

      if(empty($paso))
      {       
        //no es desde division
        $msj = "SE RELIQUIDARON ".$this->cnt." ITEMS ENTRE CARGOS, INSUMOS Y MEDICAMENTOS DE LA CUENTA No. ".$Cuenta;
        $ttl = "RELIQUIDACION DE LA CUENTA No. ".$Cuenta;
        $accion = SessionGetVar("AccionVolverCargosIYM");
        
        $fact = new OpcionesCuentasHTML();
        $html = $fact->FormaMensaje($msj,$titulo,$accion,$boton);
        return $html;
      }
      
      return true;
    }

			/**
			* Busca el detalle de una cuenta para reliquidarla
			* @access public
			* @return boolean
			* @param int numero de cuenta
			*/
			function CuentasDetalleR($Cuenta)
			{
					list($dbconn) = GetDBconn();
	
					$query = "SELECT
												a.*,
												td.descripcion as nombre_cargo,
												d.servicio as servicio_al_cargar
										FROM
												cuentas_detalle as a
												LEFT JOIN cuentas_codigos_agrupamiento b ON (a.codigo_agrupamiento_id=b.codigo_agrupamiento_id),
												tarifarios_detalle td,
												departamentos d
										WHERE a.sw_liq_manual=0
										AND (a.codigo_agrupamiento_id <> 1 OR a.codigo_agrupamiento_id IS NULL)
										AND a.numerodecuenta=$Cuenta
										AND a.tarifario_id!='SYS'
										AND b.cuenta_liquidacion_qx_id IS NULL
										AND td.tarifario_id=a.tarifario_id
										AND td.cargo=a.cargo
										AND d.departamento = a.departamento_al_cargar
										ORDER BY a.fecha_cargo";
	
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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

			function BuscarInsumosReliquidar($cuenta)
			{
				list($dbconn) = GetDBconn();
					$query = "
											SELECT A.*,e.solicitud_id, f.evolucion_id
											FROM
											(
													SELECT
													a.departamento,
													a.cantidad,
													a.consecutivo,
													b.bodegas_doc_id,
													b.numeracion,
													c.codigo_producto,
													a.fecha_cargo,a.cargo as tipo_mov,
													x.precio_venta,
													a.departamento_al_cargar
													FROM
													cuentas_detalle as a, 
                          cuentas_codigos_agrupamiento as b,
													bodegas_documentos_d as c,inventarios x
													WHERE
													a.sw_liq_manual=0
													AND a.numerodecuenta=$cuenta
													AND a.consecutivo is not null
													AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
													AND b.bodegas_doc_id=c.bodegas_doc_id
													AND b.numeracion=c.numeracion
													AND a.consecutivo=c.consecutivo
													AND c.codigo_producto=x.codigo_producto
													AND x.empresa_id=a.empresa_id
													AND a.sw_liq_manual='0'
													AND b.cuenta_liquidacion_qx_id IS NULL
											) as A LEFT JOIN hc_solicitudes_medicamentos as e ON(A.numeracion=e.numeracion and A.bodegas_doc_id=e.bodegas_doc_id)
													LEFT JOIN hc_solicitudes_medicamentos_d as f ON (e.solicitud_id=f.solicitud_id)
											order by A.fecha_cargo
									";
	
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
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
			}

//-------------------------------DIVISION CUENTAS----------------------
      /**
      * Metodo donde se eilimina la division temporal de la cuenta que pueda existir
      * y se muestran las opciones del tipo de division de cuenta
      *
      * @return mixed
      */
      function TiposDivision()
      {
        if($_REQUEST['arreglo'])
          $_REQUEST = $_REQUEST['arreglo'];
        
        list($dbconn) = GetDBconn();

        $query =" DELETE FROM   tmp_division_cuenta 
                  WHERE  numerodecuenta=".$_REQUEST['Cuenta']."";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) 
        {
          $this->error = "Error DELETE FROM tmp_division_cuenta ";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }

        $query =" DELETE FROM   tmp_division_cuenta_abonos 
                  WHERE  numerodecuenta=".$_REQUEST['Cuenta']."";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error DELETE FROM tmp_division_cuenta_abonos ";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        
        $infoPlan = $this->NombrePlan($_REQUEST['PlanId']);
        
        $fact = new OpcionesCuentasHTML();
        $html = $fact->FormaTiposDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'',$infoPlan);
        return $html;
      }
	
      function TiposCortes()
      {
                list($dbconn) = GetDBconn();

                $query =" DELETE 
                          FROM   tmp_corte_cuenta 
                          WHERE  numerodecuenta=".$_REQUEST['Cuenta']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_corte_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												echo $this->mensajeDeError;
                        $dbconn->RollbackTrans();
                        return false;
                }

                $query =" DELETE 
                          FROM   tmp_corte_cuenta_abonos 
                          WHERE  numerodecuenta=".$_REQUEST['Cuenta']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_corte_cuenta_abonos ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $fact = new OpcionesCuentasHTML();
                $html = $fact->FormaTiposCortes($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'');
                return $html;
      }

      /**
      *
      */
      function LlamarFormaListadoCorte($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars)
      {
          unset($_SESSION['CUENTA']['ABONOS']);
          unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
          unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
         // $_SESSION['CUENTA']['ABONOS']['abono_efectivo']=$_SESSION['CUENTA']['ABONOS']['abono_chequespf']=$_SESSION['CUENTA']['ABONOS']['abono_cheque']=$_SESSION['CUENTA']['ABONOS']['abono_letras']=$_SESSION['CUENTA']['ABONOS']['abono_tarjetas']=$_SESSION['CUENTA']['ABONOS']['abono_bonos']=0;

          list($dbconn) = GetDBconn();
          $query = " DELETE 
                     FROM   tmp_corte_cuenta 
                     WHERE  numerodecuenta=$Cuenta";
            $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          $query = " UPDATE cuentas 
                     SET    estado='2' 
                     WHERE  numerodecuenta=$Cuenta";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

             $query = " SELECT a.*, 
                               b.descripcion,
                               c.plan_id
                        FROM   cuentas_detalle as a, 
                               tarifarios_detalle as b, 
                               cuentas c
                        WHERE  a.numerodecuenta=$Cuenta 
                        AND    a.cargo=b.cargo
                        AND    a.tarifario_id=b.tarifario_id 
                        AND    a.numerodecuenta=c.numerodecuenta 
                      ORDER BY a.codigo_agrupamiento_id";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $this->InsertarTmpCorte($results,0);
          
          $fact = new OpcionesCuentasHTML();
          $html = $fact->FormaListadoCorte($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
          return $html;
      }
      /**
      *
      */
      function LlamarFormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars)
      {
        unset($_SESSION['CUENTA']['ABONOS']);
        unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
        unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
        SessionDelVar("CargoAdicionalesValor");

          list($dbconn) = GetDBconn();
         
          $query = " DELETE 
                     FROM   tmp_division_cuenta 
                     WHERE  numerodecuenta=$Cuenta";
            $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          $query = " UPDATE cuentas 
                     SET    estado='2' 
                     WHERE  numerodecuenta=$Cuenta";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          
          $where="";
		  
          if(!empty($vars)){
              if($vars['FechaI'] AND $vars['FechaF']){
                $where = " AND a.fecha_cargo >= TO_DATE('".$vars['FechaI']."','DD-MM-YYYY')
                           AND a.fecha_cargo <= TO_DATE('".$vars['FechaF']."','DD-MM-YYYY') ";
              }
              else if($vars['Servicio']){
                $where = " AND a.servicio_cargo = '".$vars['Servicio']."'";
              }
			  else if($vars['Departamento']){
                $where = " AND a.departamento = '".$vars['Departamento']."'";
              }

          }
          

             $query = "SELECT a.*, 
                              b.descripcion,
                              c.plan_id
                       FROM   cuentas_detalle as a, 
                              tarifarios_detalle as b, 
                              cuentas c
                       WHERE  a.numerodecuenta=$Cuenta 
                       AND    a.cargo=b.cargo
                       AND    a.tarifario_id=b.tarifario_id 
                       AND    a.numerodecuenta=c.numerodecuenta
                       $where
                       ORDER BY a.transaccion ";
                    
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) 
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $this->InsertarTmpDivision($results,0,$vars);
          
          $fact = new OpcionesCuentasHTML();
          SessionSetVar("CargoAdicionalesValor",$this->InformacionAdicional);
          $html = $fact->FormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
          return $html;
      }

      /**
      *InsertarDivisionCuenta
      */
      function InsertarDivisionCuenta()
      {
        if($_REQUEST['SeleccionarNuevoPlan'])
        {
          if($_REQUEST[planNuevo] == $_REQUEST[PlanId])
          {
            $msg = 'Opci?n Corte de Cuenta.';
          }
          if($_REQUEST['planNuevo']!=-1)
          {
            $datPlan=$this->NombrePlan($_REQUEST['planNuevo']);                            
            $indice=sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);              
            $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][$indice][$_REQUEST['planNuevo']]=$datPlan['plan_descripcion'];
          }
          ksort($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
          $fact = new OpcionesCuentasHTML();
          $html = $fact->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars'],$msg);
          return $html;    
        }
                
                unset($_SESSION['CUENTA']['ABONOS']);
                IncludeLib('funciones_facturacion');

          //si eligio un cargo para bajar
          if(!empty($_REQUEST['abajo']))
          {
								if(empty($_REQUEST['plan']))
										{
											$this->frmError["MensajeError"]="Debe Elegir el Plan.";
											$fact = new OpcionesCuentasHTML();
											$html = $fact->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
											return $html;    
											//$this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
											//return true;
										}
              $f=0;
              //si elegio un abono actual
              foreach($_REQUEST as $k => $v)
              {
									if(substr_count($k,'actual'))
									{
											if(!empty($v))
											{
												$f=1;
												$var=explode(',',$v);
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['prefijo']=$var[0];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['recibo']=$var[1];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['fecha']=$var[2];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['efectivo']=$var[3];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['cheque']=$var[4];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['tarjeta']=$var[5];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['bonos']=$var[6];
												$_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['total']=$var[7];
											}
									}
              }
              foreach($_REQUEST as $k => $v)
              {
                  if($f==0)
                  {
                      if(substr_count($k,'New'))
                      {
                        if(!empty($v))
                        { $f=1; }
                      }
                  }
              }
            if($f==0)
            {
                $this->frmError["MensajeError"]="Debe Elegir algun Cargo o Abono para la Divisi???.";
								$fact = new OpcionesCuentasHTML();
								$html = $fact->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
								return $html;    
                //$this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                //return true;
            }
          }
          else
          {
              //si elegio un abono nuevo
              foreach($_REQUEST as $k => $v)
              {
								if(substr_count($k,'nuevo'))
								{
										if(!empty($v))
										{
														$f=1;
														list($dbconn) = GetDBconn();
														$var=explode(',',$v);
														//va ha borrar los abonos
														$query = "DELETE 
                                      FROM  tmp_division_cuenta_abonos 
                                      WHERE recibo_caja=".$var[1]."
																		  AND   prefijo='".$var[0]."' 
                                      AND   plan_id=".$var[2]."";
														$dbconn->Execute($query);
														if ($dbconn->ErrorNo() != 0) {
																		$this->error = "DELETE 
                                                    FROM tmp_division_cuenta_abonos";
																		$this->fileError = __FILE__;
																		$this->lineError = __LINE__;
																		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		return false;
														}
										}
								}
              }
              foreach($_REQUEST as $k => $v)
              {
                  if($f==0)
                  {
                      if(substr_count($k,'Go'))
                      {
                        if(!empty($v))
                        { $f=1; }
                      }
                  }
              }
              if($f==0)
              {
                  $this->frmError["MensajeError"]="Debe Elegir algun Cargo o Abono de la Cuenta Nueva.";
									$fact = new OpcionesCuentasHTML();
									$html = $fact->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
									return $html;    
                  //$this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                  //return true;
              }
          }

         list($dbconn) = GetDBconn();
                //va ha insertar los abonos
                foreach($_SESSION['CUENTA']['ABONOS'] as $k => $v)
                {
									$query = "INSERT INTO tmp_division_cuenta_abonos(plan_id,
														numerodecuenta,
														recibo_caja,
														prefijo,
														fecha_ingcaja,
														total_abono,
														total_efectivo,
														total_cheques,
														total_tarjetas,
														total_bonos)
														VALUES(".$_REQUEST['plan'].",".$_REQUEST['Cuenta'].",".$v[recibo].",'".$v[prefijo]."','".$v[fecha]."',".$v[total].",
														".$v[efectivo].",".$v[cheque].",".$v[tarjeta].",".$v[bonos].")";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error update tmp_division_cuenta";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }
                }

                /*
                        OJO los pagos son por recibo no por tipo
                */

          $f=0;
          $j=0;

          foreach($_REQUEST as $k => $v)
          {
                        //cuando los baja a la cuenta nueva
              if(substr_count($k,'New'))
              {         // 0 transaccion 1codigo_agrupamiento 2 consecutivo 3 cups
                    $d = explode(',',$v);
                    //este codigo se comento para poder pasar los medicamentos de una cuenta a otra
                    /*if(!empty($d[1]) AND !empty($d[2]))
                                    { //es un medicamento
                            $query = "update tmp_division_cuenta set cuenta=1, plan_id=".$_REQUEST['plan']."
                                    where codigo_agrupamiento_id=$d[1]";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error update tmp_division_cuenta";
                                                    $this->fileError = __FILE__;
                                                    $this->lineError = __LINE__;
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                            }
                                    }
                                    else
                                    {*/
                                    //fin codigo comentado
                                    if(empty($d[1]) AND empty($d[2])){
                                                $equi='';
                                                $equi=ValdiarEquivalencias($_REQUEST['plan'],$d[3]);
                                                if(empty($equi))
                                                {
                                                        $this->frmError["MensajeError"]='ALGUNO(S) DE LOS CARGOS NO TIENE EQUIVALENCIAS O LAS EQUIVALENCIAS NO ESTAN CONTRATADAS';
                                                        $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                                                        return true;
                                                }
                                    }

                                                //valida is tiene quivalencias y esta contratado para q el cambio de responsable salga nien
                            $query = "UPDATE tmp_division_cuenta 
                                      SET    cuenta=1, 
                                             plan_id=".$_REQUEST['plan']."
                                      WHERE  transaccion=$d[0]";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error update tmp_division_cuenta";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                            }
                    //}
                    $f++;
              }
              if(substr_count($k,'Go'))
              {         // 0 transaccion 1codigo_agrupamiento 2 consecutivo
                    $d = explode(',',$v);
                    //este codigo se comento para poder pasar los medicamentos de una cuenta a otra
                    /*if(!empty($d[1]) AND !empty($d[2]))
                                    { //es un medicamento
                            $query = "update tmp_division_cuenta set cuenta=0, plan_id=NULL
                                    where codigo_agrupamiento_id=$d[1]";
                                    }
                                    else
                                    {*/
                     //fin codigo comentado
                            $query = "UPDATE tmp_division_cuenta 
                                      SET    cuenta=0, plan_id=NULL
                                      WHERE  transaccion=$d[0]";
                                    //}
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error update tmp_division_cuenta";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $j++;
              }
          }

          if(!empty($f))
          {  $msg.="Los $f Cargos fueron asignados a la nueva Cuenta.  ";  }
          if(!empty($j))
          {  $msg.="Los $j Cargos fueron reasigandos a la Cuenta Actual.";  }
          $this->frmError["MensajeError"]=$msg;
					$fact = new OpcionesCuentasHTML();
					$html = $fact->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
					return $html;
          //$this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
          //return true;
      }

      /**
      *DivisionSoloAbonosCuenta
      */
			function DivisionSoloAbonosCuenta($Cuenta,$vector)
			{       //$vector traer los planes q ya estan
							unset($plan);
							for($i=0; $i<sizeof($vector); $i++)
							{
											if($i+1==sizeof($vector))
											{  $plan.=$vector[$i];  }
											else
											{  $plan.=$vector[$i].',';  }
							}

							if(!empty($plan))
							{   $x= " and a.plan_id not in($plan)";  }

				list($dbconn) = GetDBconn();
				$query = "SELECT distinct a.plan_id, 
                         b.plan_descripcion,
                         a.cuenta
					        FROM   tmp_division_cuenta_abonos as a, 
                         planes as b
									WHERE  a.numerodecuenta=$Cuenta $x
									AND    a.plan_id=b.plan_id 
                ORDER BY plan_id";
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
				return $var;
			}

		/**
		*FinalizarDivision
		*/
		function FinalizarDivision()
		{
									unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']); 
									unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
									$_SESSION['CUENTA']['DIVISION']=1;
									unset($_SESSION['DIVISION']['CUENTA']);
									$det = $this->DetalleNuevo($_REQUEST['Cuenta']);
									$_SESSION['DIVISION']['CUENTA'][]=array('cuenta'=>$_REQUEST['Cuenta']);                    
									for($i=0; $i<sizeof($det); $i++){                      
										if($det[$i]['cuenta']!='0'){                        
											$_REQUEST['Responsable']=$det[$i]['plan_id'];
											$_REQUEST['indice']=$det[$i]['cuenta'];
											$_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
											$html = $this->NuevoResponsable();
											return $html;
										}
									}                  
									//revisa si solo son abonos
									$det=$this->DivisionSoloAbonosCuenta($_REQUEST['Cuenta'],'');
									$_SESSION['CUENTA']['DIVISION']['ABONOS']=1;                    
									for($i=0; $i<sizeof($det); $i++){
										if($det[$i]['cuenta']!='0'){
											$_REQUEST['Responsable']=$det[$i]['plan_id'];
											$_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
											$_REQUEST['indice']=$det[$i]['cuenta'];
											$html = $this->NuevoResponsable();
											return $html;
										}
									}
									//---------hay q ir a revisar las equivalencias y pedir los nuevos datos



					$Cuenta1=$_REQUEST['Cuenta'];
					$TipoId=$_REQUEST['TipoId'];
					$PacienteId=$_REQUEST['PacienteId'];
					$Nivel=$_REQUEST['Nivel'];
					$PlanId=$_REQUEST['PlanId'];
					$Ingreso=$_REQUEST['Ingreso'];
					$Fecha=$_REQUEST['Fecha'];
					$Nivel=$_REQUEST['Nivel'];
					#$empresa=$_SESSION['CUENTAS']['EMPRESA'];
					$empresa=SessionGetVar("EmpresaId");
					//BUSCA LOS DATOS DE LA CUENTA ACTUAL
					list($dbconn) = GetDBconn();
					$query="SELECT * FROM cuentas
									WHERE numerodecuenta='$Cuenta1' and empresa_id='$empresa'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError;
							return false;
					}
					$var=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
					//CREA LA NUEVA CUENTA
					if(empty($var[autorizacion_int]))
					{   $AutoInt='NULL';   }
					else
					{   $AutoInt=$var[autorizacion_int];   }
					if(empty($var[autorizacion_ext]))
					{   $AutoExt='NULL';   }
					else
					{   $AutoExt=$var[autorizacion_ext];   }

					$query=" SELECT nextval('cuentas_numerodecuenta_seq')";
					$result=$dbconn->Execute($query);
					$CN=$result->fields[0];
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
																					semanas_cotizadas,
																					abono_efectivo,
																					abono_cheque,
																					abono_tarjetas,
																					abono_chequespf,
																					abono_letras,
																					abono_bonos)
										VALUES($CN,'".$var[empresa_id]."','".$var[centro_utilidad]."',".$var[ingreso].",'".$var[plan_id]."',".$var[estado].",'".UserGetUID()."','now()','".$var[tipo_afiliado_id]."','".$var[rango]."',$AutoInt,$AutoExt,".$var[semanas_cotizadas].",0,0,0,0,0,0)";
					//$dbconn->BeginTrans();
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error cuentas";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError;
							$dbconn->RollbackTrans();
							return false;
					}
					//ELIMINA DE LAS TABLAS REALES LOS CARGOS QUE VAN HA PASAR A LA NUEVA CUENTA


					$query =" DELETE FROM tmp_division_cuenta WHERE numerodecuenta=".$Cuenta1."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error DELETE FROM tmp_division_cuenta ";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError;
							$dbconn->RollbackTrans();
							return false;
					}
					//abonos a la nueva cuenta
					foreach($_SESSION['CUENTA']['ABONOS'] as $k => $v)
					{
									$query ="UPDATE rc_detalle_hosp SET numerodecuenta=$CN
																		WHERE prefijo='".$v[prefijo]."' AND recibo_caja=".$v[recibo]."
																		AND numerodecuenta=$Cuenta1";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error UPDATE rc_detalle_hosp ";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													echo $this->mensajeDeError;
													$dbconn->RollbackTrans();
													$this->fileError = __FILE__;
													$this->lineError = __LINE__;
													return false;
									}
					}

					//reliquidacion de la cuenta vieja para recalcular valores del paciente
					$var=$this->CuentasDetalleR($Cuenta1);
          //IncludeLib("tarifario_cargos");
					IncludeClass('LiquidacionCargos');
					$dat = new LiquidacionCargos();
					for($i=0; $i<sizeof($var); $i++)
					{
							$Cargo=$var[$i][cargo];
							$des=$this->BuscarDescuentosCuenta($Cuenta,$var[$i][grupo_tipo_cargo]);
							$TarifarioId=$var[$i][tarifario_id];
							$Cantidad=$var[$i][cantidad];
							$transaccion=$var[$i][transaccion];
							//$Liq=LiquidarCargoCuenta($Cuenta1,$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar'],'','');
							//LiquidarCargoCuenta($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=null, $planId='', $Servicio='', $semanas_cotizacion='', $tipo_empleador_id = '', $empleador = '',$tipo_id_paciente = '', $paciente_id = '', $tipoUninadTiempo = NULL, $porcentajeDelcargo = NULL)
							$Liq = $dat->LiquidarCargoCuenta($Cuenta1,$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar']);

							$query ="   UPDATE cuentas_detalle
													SET
															precio=".$Liq[precio_plan].",
															valor_cargo=".$Liq[valor_cargo].",
															valor_nocubierto=".$Liq[valor_no_cubierto].",
															valor_cubierto=".$Liq[valor_cubierto].",
															valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
															valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
															porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
															sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
															sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
													WHERE numerodecuenta=$Cuenta1
															AND cargo='$Cargo'
															AND tarifario_id='$TarifarioId'
															AND transaccion='$transaccion'";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error UPDATE cuentas_detalle";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											echo $this->mensajeDeError;
											$dbconn->RollbackTrans();
											return false;
							}
							$result->Close();
							$x++;
					}//fin for

					$var=$this->BuscarInsumosReliquidar($Cuenta1);
					//IncludeLib("tarifario_cargos");
					$datosAdicionales = array();
					IncludeClass('LiquidacionCargosInventario');
					$dat = new LiquidacionCargosInventario();
					for($i=0; $i<sizeof($var); $i++)
					{
						$datosAdicionales['cuenta'] = $Cuenta1;
						$datosAdicionales['plan_id'] = $_REQUEST['PlanId'];
						$datosAdicionales['departamento'] = $var[$i]['departamento_al_cargar'];
						$datosAdicionales['evolucion_id'] = $var[$i]['evolucion_id'];
						//$Liq=LiquidarIyM($Cuenta1 ,$var[$i]['codigo_producto'] ,$var[$i]['cantidad'] ,0 ,0 ,true ,true ,NULL ,$_REQUEST['PlanId'],false,$var[$i]['departamento_al_cargar'],$_SESSION['CUENTAS']['EMPRESA'],$var[$i]['evolucion_id']);
						$Liq = $dat->GetLiquidacionProducto($var[$i]['codigo_producto'],$empresa,$var[$i]['cantidad'],$datosAdicionales);
							if($var[$i]['tipo_mov']=='DIMD'){
									$valor_cargo=($Liq['valor_cargo']*-1);
									$valor_nocubierto=($Liq['valor_nocubierto']*-1);
									$valor_cubierto=($Liq['valor_cubierto']*-1);
							}else{
									$valor_cargo=$Liq['valor_cargo'];
									$valor_nocubierto=$Liq['valor_nocubierto'];
									$valor_cubierto=$Liq['valor_cubierto'];
							}
						$query =" UPDATE cuentas_detalle SET
															precio=".$Liq[precio_plan].",
															valor_cargo='".$valor_cargo."',
															valor_nocubierto='".$valor_nocubierto."',
															valor_cubierto='".$valor_cubierto."',
															valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
															valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
															porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
															sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
															sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
											WHERE numerodecuenta=$Cuenta1 and consecutivo=".$var[$i][consecutivo]."";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error UPDATE cuentas_detalle";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										echo $this->mensajeDeError;
										$dbconn->RollbackTrans();
										return false;
						}
						$result->Close();
						$x++;
					}
						//fin reliquidacion

					unset($_SESSION['CUENTA']['ABONOS']);
					//$dbconn->CommitTrans();
					$mensaje='La Cuenta No. '.$Cuenta1.' quedo Dividida en la Cuenta No.'.$Cuenta.'.<br>Recuerde que las Cuentas estan Inactivas, Debe Activar una de las Dos Cuentas.<br>Desea Activar una de las Dos Cuentas.';
					$arreglo=array('Cuenta1'=>$Cuenta1,'Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
					$c='app';
					$m='Cuentas';
					$me='LlamarFormaActivarCuentaDivision';
					$me2='FormaMostrarCuenta';
					$Titulo='DIVISION DE LA CUENTA No. '.$Cuenta1;
					$boton1='ACTIVAR UNA CUENTA';
					$boton2='CANCELAR';
					$fact = new app_Cuentas_user();
					$fact->LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
					//$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
					return true;
			}

		/**
		***InsertarCorteCuenta
		**/
		function InsertarCorteCuenta()
		{
				$det = $this->DetalleNuevo($_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['Cuenta'],1,$corte=true,$_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo][FechaI],$_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo][FechaF],$_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo][hora_inicial],$_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo][hora_final]);
       
					//---------hay q ir a revisar las equivalencias y pedir los nuevos datos

					$Cuenta1    = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['Cuenta'];
					$TipoId     = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['TipoId'];
					$PacienteId = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['PacienteId'];
					$Nivel      = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['Nivel'];
					$PlanId     = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['PlanId'];
					$Ingreso    = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['Ingreso'];
					$Fecha      = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['Fecha'];
					$Nivel      = $_SESSION['CUENTAS']['CORTE']['REQUEST'][arreglo]['Nivel'];
         
          #$empresa=$_SESSION['CUENTAS']['EMPRESA'];
					$empresa=SessionGetVar("EmpresaId");
					//BUSCA LOS DATOS DE LA CUENTA ACTUAL
					list($dbconn) = GetDBconn();
					//$dbconn->BeginTrans();
					
					$query = " SELECT * 
                     FROM   cuentas
					           WHERE  numerodecuenta='$Cuenta1' 
                     AND    empresa_id='$empresa'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
							return false;
					}
					$var=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
					
					$query = "SELECT * 
							      FROM   ingresos
						        WHERE  ingreso = $Ingreso;";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
							return false;
					}
					$varIngreso=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
					
					//CREA LA NUEVA CUENTA
					if(empty($var[autorizacion_int]))
					{   $AutoInt='NULL';   }
					else
					{   $AutoInt=$var[autorizacion_int];   }
					if(empty($var[autorizacion_ext]))
					{   $AutoExt='NULL';   }
					else
					{   $AutoExt=$var[autorizacion_ext];   }

					$query =" SELECT nextval('cuentas_numerodecuenta_seq')";
					$result=$dbconn->Execute($query);
					$CN=$result->fields[0];
										
					//el corte de cuenta se crea inactiva
					$sw_corte = '1';
					$query = "INSERT INTO cuentas 
                   (
                      numerodecuenta,
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
      								semanas_cotizadas,
      								abono_efectivo,
      								abono_cheque,
      								abono_tarjetas,
      								abono_chequespf,
      								abono_letras,
      								abono_bonos,
      								sw_liquidacion_manual_habitaciones,
      								sw_corte
								)
						    VALUES
                (
                     $CN,
                     '".$var[empresa_id]."',
                     '".$var[centro_utilidad]."',
                     '".$Ingreso."','".$var[plan_id]."',
                     '2',
                     '".UserGetUID()."',
                     'now()',
                     '".$var[tipo_afiliado_id]."',
                     '".$var[rango]."',
                     $AutoInt,
                     $AutoExt,
                     ".$var[semanas_cotizadas].",
                     0,
                     0,
                     0,
                     0,
                     0,
                     0,
                     0,
                     $sw_corte)";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error cuentas";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
							$dbconn->RollbackTrans();
							return false;
					}
					//CARGOS PENDIENTES POR CARGAR
					if($_SESSION['CUENTAS']['CORTE']['DET_HAB'] > 0)
					{
						$datosCorte = "";
						for($i=0; $i<$_SESSION['CUENTAS']['CORTE']['DET_HAB']; $i++)
						{
							if($_SESSION['CUENTAS']['CORTE']['REQUEST']['HAB'.$i])
							{
								//unset($_SESSION['LIQUIDACION_HABITACIONES']);
								IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
								$objeto = new LiquidacionHabitacionesCta(); 
								if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
								{
									die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
								}
								
								if(!is_array($_SESSION['LIQUIDACION_HABITACIONES'])){        
									$datosCorte = explode('*',$_SESSION['CUENTAS']['CORTE']['REQUEST']['HAB'.$i]);
									$set = "";
									if($datosCorte[1] == 'CAMACTUAL')
									{
										$date = explode("/",$_SESSION['CUENTAS']['CORTE'][REQUEST][FechaLiquidar]);
										$fechaE = $date[2]."-".$date[1]."-".$date[0];
										$set = ",fecha_egreso = '".$fechaE."'";
									}
									$_SESSION['CUENTAS']['CORTE']['REQUEST']['HAB'.$i] = $datosCorte[0];
									$liquidacionHab = new LiquidacionHabitacionesCorte;
									$_SESSION['LIQUIDACION_HABITACIONES'] = $liquidacionHab->LiquidarCargosInternacion($CN,false,NULL,NULL,NULL,NULL,$_SESSION['CUENTAS']['CORTE']['REQUEST']['HAB'.$i],$Cuenta1,$fechaE);
									
								}    
								    
								if($objeto->CargarHabitacionCuenta($empresa,$CN,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$mensaje,$corte='CORTE')==false){
									echo $mensaje="ERROR AL INSERTAR HABITACIONES EN CUENTAS DETALLE origen[$Cuenta1]-destino[$CN]."; exit;
								}else{        
									$mensaje="REGISTROS DE HABITACIONES CARGADOS A LA CUENTA SATISFACTORIAMENTE.";          
								}

								$query = " UPDATE movimientos_habitacion
									         SET    numerodecuenta = $CN, 
                                  numerodecuenta_corte = $CN $set
									         WHERE  movimiento_id = ".$_SESSION['CUENTAS']['CORTE']['REQUEST']['HAB'.$i]."";

								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error UPDATE movimientos_habitacion";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.$this.']['.__LINE__.']';
										echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
										$dbconn->RollbackTrans();
										return false;
								}
// 								//INSERTAR MOVIMIENTO ACTUAL SI ES DE CORTE
								if($datosCorte[1] == 'CAMACTUAL')
								{
								$query ="SELECT nextval('movimientos_habitacion_movimiento_id_seq');";
										$result=$dbconn->Execute($query);
										$mvto_id = $result->fields[0];
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error SELECT nextval('movimientos_habitacion_movimiento_id_seq')";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.$this.']['.__LINE__.']';
												echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
												$dbconn->RollbackTrans();
												return false;
										}

											$query =" INSERT INTO movimientos_habitacion
												(
													movimiento_id,
													ingreso_dpto_id,
													numerodecuenta,
													fecha_ingreso,
													fecha_egreso,
													cama,
													ingreso,
													precio,
													cargo,
													sw_excedente,
													tipo_cama_id,
													transaccion,
													departamento,
													estacion_id,
													autorizacion_int,
													autorizacion_ext,
													observacion
												)
											SELECT
														$mvto_id,
														ingreso_dpto_id,
														".$Cuenta1.",
														'$fechaE',
														--fecha_ingreso,
														NULL,
														cama,
														ingreso,
														precio,
														cargo,
														sw_excedente,
														tipo_cama_id,
														transaccion,
														departamento,
														estacion_id,
														autorizacion_int,
														autorizacion_ext,
														observacion
											FROM movimientos_habitacion
											WHERE movimiento_id = ".$_SESSION['CUENTAS']['CORTE']['REQUEST']['HAB'.$i].";";
									$result=$dbconn->Execute($query);
											
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error insertar en movimientos_habitacion";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.$this.']['.__LINE__.']';
											echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
									}
								}
								$datosCorte = "";
								//FIN INSERTAR MOVIMIENTO ACTUAL SI ES DE CORTE
							}
						}
					}
					//FIN CARGOS PENDINETES POR CARGAR

					//TRANSACCIONES DE LA CUENTA
					if($_SESSION['CUENTAS']['CORTE']['DET'] > 0)
					{
						for($i=0; $i<$_SESSION['CUENTAS']['CORTE']['DET']; $i++)
						{
							if($_SESSION['CUENTAS']['CORTE']['REQUEST']['Transaccion'.$i])
							{
	 							$query ="	UPDATE cuentas_detalle
                          SET    numerodecuenta = $CN
                          WHERE  transaccion='".$_SESSION['CUENTAS']['CORTE']['REQUEST']['Transaccion'.$i]."'";
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error UPDATE cuentas_detalle";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.$this.']['.__LINE__.']';
												echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
												$dbconn->RollbackTrans();
												return false;
								}
							}
						}
            foreach($_SESSION['CUENTAS']['CORTE']['REQUEST']['SeleccionActoQx'] as $k11 => $cuenta_liquidacion_qx)
            {
            $query = " UPDATE  cuentas_liquidaciones_qx 
                       SET     numerodecuenta = $CN
                       WHERE   cuenta_liquidacion_qx_id = ".$cuenta_liquidacion_qx." "; 
                $result=$dbconn->Execute($query);
						    if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error UPDATE cuentas_liquidaciones_qx ";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
										$dbconn->RollbackTrans();
										return false;
								   } 
            }
          }

					//FIN TRANSACCIONES DE LA CUENTA
					
					//ELIMINA DE LAS TABLAS REALES LOS CARGOS QUE VAN HA PASAR A LA NUEVA CUENTA
					$query =" DELETE 
                    FROM   tmp_corte_cuenta 
                    WHERE  numerodecuenta=".$Cuenta1."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error DELETE FROM tmp_division_cuenta ";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
							$dbconn->RollbackTrans();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;
							return false;
					}

					//reliquidacion de la cuenta vieja para recalcular valores del paciente
					$var=$this->CuentasDetalleR($Cuenta1);
					IncludeLib("tarifario_cargos");
					//IncludeClass('LiquidacionCargos');
					//$dat = new LiquidacionCargos();
					for($i=0; $i<sizeof($var); $i++)
					{
							$Cargo=$var[$i][cargo];
							$des=$this->BuscarDescuentosCuenta($Cuenta,$var[$i][grupo_tipo_cargo]);
							$TarifarioId=$var[$i][tarifario_id];
							$Cantidad=$var[$i][cantidad];
							$transaccion=$var[$i][transaccion];
							$Liq=LiquidarCargoCuenta($Cuenta1,$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar'],'','');
							//LiquidarCargoCuenta($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=null, $planId='', $Servicio='', $semanas_cotizacion='', $tipo_empleador_id = '', $empleador = '',$tipo_id_paciente = '', $paciente_id = '', $tipoUninadTiempo = NULL, $porcentajeDelcargo = NULL)
							//$Liq = $dat->LiquidarCargoCuenta($Cuenta1,$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar']);
							$query ="   UPDATE cuentas_detalle
													SET
															precio=".$Liq[precio_plan].",
															valor_cargo=".$Liq[valor_cargo].",
															valor_nocubierto=".$Liq[valor_no_cubierto].",
															valor_cubierto=".$Liq[valor_cubierto].",
															valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
															valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
															porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
															sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
															sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
													WHERE numerodecuenta=$Cuenta1
															AND cargo='$Cargo'
															AND tarifario_id='$TarifarioId'
															AND transaccion='$transaccion'";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error UPDATE cuentas_detalle";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
							}
							$result->Close();
							$x++;
					}//fin for

					$var=$this->BuscarInsumosReliquidar($Cuenta1);
					//IncludeLib("tarifario_cargos");
					$datosAdicionales = array();
					IncludeClass('LiquidacionCargosInventario');
					$dat = new LiquidacionCargosInventario();
					for($i=0; $i<sizeof($var); $i++)
					{
						$datosAdicionales['cuenta'] = $Cuenta1;
						$datosAdicionales['plan_id'] = $_REQUEST['PlanId'];
						$datosAdicionales['departamento'] = $var[$i]['departamento_al_cargar'];
						$datosAdicionales['evolucion_id'] = $var[$i]['evolucion_id'];
						//$Liq=LiquidarIyM($Cuenta1 ,$var[$i]['codigo_producto'] ,$var[$i]['cantidad'] ,0 ,0 ,true ,true ,NULL ,$_REQUEST['PlanId'],false,$var[$i]['departamento_al_cargar'],$_SESSION['CUENTAS']['EMPRESA'],$var[$i]['evolucion_id']);
						$Liq = $dat->GetLiquidacionProducto($var[$i]['codigo_producto'],$empresa,$var[$i]['cantidad'],$datosAdicionales);
							if($var[$i]['tipo_mov']=='DIMD'){
									$valor_cargo=($Liq['valor_cargo']*-1);
									$valor_nocubierto=($Liq['valor_nocubierto']*-1);
									$valor_cubierto=($Liq['valor_cubierto']*-1);
							}else{
									$valor_cargo=$Liq['valor_cargo'];
									$valor_nocubierto=$Liq['valor_nocubierto'];
									$valor_cubierto=$Liq['valor_cubierto'];
							}
						$query =" UPDATE cuentas_detalle SET
															precio=".$Liq[precio_plan].",
															valor_cargo='".$valor_cargo."',
															valor_nocubierto='".$valor_nocubierto."',
															valor_cubierto='".$valor_cubierto."',
															valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
															valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
															porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
															sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
															sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
											WHERE numerodecuenta=$Cuenta1 and consecutivo=".$var[$i][consecutivo]."";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error UPDATE cuentas_detalle";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
										$dbconn->RollbackTrans();
										return false;
						}
						$result->Close();
						$x++;
					}
					//fin reliquidacion
//
				$query="SELECT count(*) 
                FROM   cuentas_detalle
				        WHERE  numerodecuenta='$Cuenta1' 
				        AND    empresa_id='$empresa'";
				$rst=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error UPDATE cuentas_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
					$dbconn->RollbackTrans();
					return false;
				}
				if($rst->fields[0] == 0)
				{
					$query="UPDATE cuentas SET 
							valor_total_paciente = 0,
							valor_total_empresa = 0,
							total_cuenta = 0,
							--valor_cuota_paciente = 0,
							valor_nocubierto = 0,
							valor_cubierto = 0,
							valor_total_cargos = 0
					WHERE numerodecuenta='$Cuenta1' 
					AND empresa_id='$empresa'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error UPDATE cuentas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError.'['.get_class($this).']['.__LINE__.']';
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$result->Close();
//
					//unset($_SESSION['CUENTA']['ABONOS']);
					//$dbconn->CommitTrans();
					//$mensaje='La Cuenta No. '.$Cuenta1.' quedo Dividida en la Cuenta No.'.$Cuenta.'.<br>Recuerde que las Cuentas estan Inactivas, Debe Activar una de las Dos Cuentas.<br>Desea Activar una de las Dos Cuentas.';
					$mensaje .='Se gener? un corte de la cuenta '.$Cuenta1.' el numero de cuenta nuevo es '.$CN.'.<br>Recuerde que las Cuentas estan Inactivas, Debe Activar una de las Dos Cuentas.<br>Desea Activar una de las Dos Cuentas.';

					$arreglo=array('Cuenta1'=>$Cuenta1,'Transaccion'=>$Transaccion,'Cuenta'=>$CN,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado,'Corte'=>'Corte');
					$c='app';
					$m='Cuentas';
					$me='LlamarFormaActivarCuentaDivision';
					$me2='FormaMostrarCuenta';
					$Titulo='CORTE DE LA CUENTA No. '.$Cuenta1;
					$boton1='ACTIVAR UNA CUENTA';
					$boton2='CANCELAR';
					$html = $this->LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
					return $html;
			}
	
	/**
	*DetalleTotal
	*/
	function DetalleTotal($Cuenta)
	{
			list($dbconn) = GetDBconn();
			$query = "select a.*,d.codigo_producto,
								(CASE WHEN d.consecutivo IS NOT NULL THEN e.descripcion ELSE b.descripcion END) as descripcion,
								case a.facturado when 1 then a.valor_cargo else 0 end as fac
																from tmp_division_cuenta as a
																LEFT JOIN cuentas_codigos_agrupamiento c ON (a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
																LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND c.bodegas_doc_id=d.bodegas_doc_id AND c.numeracion=d.numeracion)
																LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
																,tarifarios_detalle as b
								where a.numerodecuenta=$Cuenta and a.cuenta=0 and
									a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
																order by a.codigo_agrupamiento_id";
			$results = $dbconn->Execute($query);
			while (!$results->EOF) {
					$var[]=$results->GetRowAssoc($ToUpper = false);
					$results->MoveNext();
			}
			$results->Close();
			return $var;
	}

	/**
	* Activa la cuenta
	* @access public
	* @return boolean
	*/
	function ActivarCuenta($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha)
	{
			if(empty($PlanId) AND empty($Cuenta))
			{
				$Cuenta=$_REQUEST['Cuenta'];
				$Transaccion=$_REQUEST['Transaccion'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Pieza=$_REQUEST['Pieza'];
				$Cama=$_REQUEST['Cama'];
				$Fecha=$_REQUEST['Fecha'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Estado=$_REQUEST['Estado'];
				$Ingreso=$_REQUEST['Ingreso'];
			}

				list($dbconn) = GetDBconn();
				$query = "select a.numerodecuenta
									from cuentas as a, ingresos as b
									where b.tipo_id_paciente='$TipoId' and  paciente_id='$PacienteId'
									and a.ingreso=b.ingreso and a.estado=1";
				$result=$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$result->Close();
				if(!$result->EOF)
				{
						$mensaje = 'No se puede Activar la Cuenta No. '.$Cuenta.' el paciente ya tiene una Cuenta Abierta.';
						$titulo = 'ACTIVAR CUENTA No. '.$Cuenta;
						$accion = SessionGetVar("AccionVolverCargosIYM");
						$fact = new OpcionesCuentasHTML();
						$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
						return $html;
/*						if(!$this-> FormaMensaje($mensaje,'ACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
						return false;
						}
						return true;*/
				}

				$query = " UPDATE cuentas 
                   SET    estado='1' 
                   WHERE  numerodecuenta=$Cuenta";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$query = "SELECT  a.numerodecuenta, 
                            a.ingreso, 
                            a.plan_id, 
                            b.tipo_id_paciente,
														b.paciente_id, 
                            a.fecha_registro
										FROM    cuentas as a, ingresos as b
										WHERE   a.numerodecuenta=$Cuenta 
                    AND     a.ingreso=b.ingreso";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$var= $result->GetRowAssoc($ToUpper = false);
					$result->Close();

					$mensaje = 'La Cuenta No. '.$Cuenta.' ha sido Activada.';
					$titulo = 'ACTIVAR CUENTA No. '.$Cuenta;
					//$_SESSION['ESTADO']='A';
					$accion = SessionGetVar("AccionVolverCargosIYM");
					$fact = new OpcionesCuentasHTML();
					$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return $html;
				}
	}

  /**
  **NombreTercero
  **/
  function NombreTercero($tipo_id_tercero,$tercero_id)
      {
        list($dbconn) = GetDBconn();
        $query =" SELECT nombre_tercero
                  FROM   terceros
                  WHERE  tipo_id_tercero='".$tipo_id_tercero."' 
                  AND    tercero_id='".$tercero_id."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          if($result->RecordCount()>0){
            $vars=$result->GetRowAssoc($toUpper=false);
          }
        }
        $result->Close();
        return $vars;
      }
    /**
    * Metodo donde se obtienen todos los planes
    *
    * @param integer $Plan identificador del plan
    * @param string $empresa_id Identificador de la empresa
    *
    * @return mixed
    */
    function Planes($Plan,$empresa_id)
    {
      list($dbconn) = GetDBconn();
      $query = " SELECT  plan_id,
                      plan_descripcion,
                      tercero_id,
                      tipo_tercero_id
               FROM   planes
               WHERE  fecha_final >= now()
               AND    estado='1'
               AND    fecha_inicio <= now()
               AND    plan_id NOT IN(".$Plan.") ";
      if($empresa_id)
        $query .= "AND    empresa_id = '".$empresa_id."' ";
        
      $query .= " ORDER BY plan_descripcion";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }

      while (!$result->EOF) 
      {
        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      return $var;
    }

      /**
      *
      */
      function DetalleNuevo($Cuenta,$paginador,$corte,$FechaI,$FechaF,$hora_inicial,$hora_final)
      {
          $condicion = "";
          if($corte AND $FechaI AND $FechaF)
          {
            $tabla_tmp = "tmp_corte_cuenta";
            $condicion = " AND a.fecha_registro >= '$FechaI $hora_inicial:00' AND a.fecha_registro <= '$FechaF $hora_final:59' ";
          }
          elseif($corte)
					{
						$tabla_tmp = "tmp_corte_cuenta";
						$condicion = "";
					}
					else
          {$tabla_tmp = "tmp_division_cuenta";}
          //paginador
          $this->paginaActual = 1;
          $this->offset = 0;
          if($_REQUEST['offset']){
            $this->paginaActual = intval($_REQUEST['offset']);
            if($this->paginaActual > 1){
              $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
          }
          //fin paginador
          
          list($dbconn) = GetDBconn();
          $query = "SELECT a.*,d.codigo_producto,
                          (CASE WHEN d.consecutivo IS NOT NULL 
                          THEN e.descripcion 
                          ELSE b.descripcion 
                          END) as descripcion,
                          c.plan_descripcion,
                          (CASE a.facturado WHEN 1 
                          THEN a.valor_cargo 
                          ELSE 0 
                          END) as fac,
                          dpto.descripcion as departamento,
                          cqx.cuenta_liquidacion_qx_id,
                          f.descripcion AS descripcion_grupo,
                          f.bodegas_doc_id
                   FROM $tabla_tmp as a
                   LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                   LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                   LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                   LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                   LEFT JOIN cuentas_liquidaciones_qx cqx ON (f.cuenta_liquidacion_qx_id=cqx.cuenta_liquidacion_qx_id), 
                        tarifarios_detalle as b, 
                        planes as c
                   WHERE a.numerodecuenta=$Cuenta 
                   AND a.cargo=b.cargo 
                   AND a.tarifario_id=b.tarifario_id
                   AND a.plan_id=c.plan_id
                   $condicion
                   ORDER BY cqx.cuenta_liquidacion_qx_id DESC,f.bodegas_doc_id DESC,
                            a.fecha_cargo,
                            a.codigo_agrupamiento_id,
                            a.transaccion";         
          if(empty($_REQUEST['conteo']))
          {
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
          }
          else
          {
            $this->conteo=$_REQUEST['conteo'];
          }
/*          if($paginador==1){
            $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
          }*/
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
          else
          {
            while(!$result->EOF)
            {
              $vars[]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
          return $vars;
      }
    /**
    * Metodo donde se hace el ingreso del resultado de la consulta de 
    * los cargos en la tabla temporal
    *
    * @param object $results Objeto del resultado de la consulta
    * @param string $grupo
    * @param array $vars Arreglo con informacion adicional que se necesite
    *
    */
    function InsertarTmpDivision($results,$grupo,$vars)
    {
      list($dbconn) = GetDBconn();
      while (!$results->EOF) 
      {
        $Datos[]=$results->GetRowAssoc($ToUpper = false);
        $results->MoveNext();
      }
      $results->Close();

      $valor_compara = 0;
      if($vars['Valor'])
        $valor_compara = $vars['Valor'];
      
      $suma_total = 0;
      
      if(!empty($Datos))
      {
        for($i=0; $i<sizeof($Datos); $i++)
        {
          $AutoInt = (empty($Datos[$i]['autorizacion_int']))? "NULL" :$Datos[$i]['autorizacion_int'];
          $AutoExt = (empty($Datos[$i]['autorizacion_ext']))? "NULL" :$Datos[$i]['autorizacion_ext'];
          
          if(empty($Datos[$i]['codigo_agrupamiento_id']))
            $Datos[$i]['codigo_agrupamiento_id'] = 'NULL';
            
          if(empty($Datos[$i]['consecutivo']))
            $Datos[$i]['consecutivo'] = 'NULL';

          if(empty($Datos[$i]['cargo_cups']))
            $Datos[$i]['cargo_cups'] = 'NULL';
          else
            $Datos[$i]['cargo_cups'] = "'".$Datos[$i]['cargo_cups']."'";
          
          if($valor_compara > 0)
          {
            if($suma_total + $Datos[$i]['valor_cargo'] > $valor_compara)
            {
              $Datos[$i]['valor_total'] = $suma_total;
              $this->InformacionAdicional = $Datos[$i];
              return true;
            }
          }
          
          $suma_total += $Datos[$i]['valor_cargo'];
          
          $query = "INSERT INTO tmp_division_cuenta
                      (
                        transaccion,
                        empresa_id,
                        centro_utilidad,
                        numerodecuenta,
                        departamento,
                        tarifario_id,
                        cargo,
                        cantidad,
                        precio,
                        valor_cargo,
                        valor_nocubierto,
                        valor_cubierto,
                        usuario_id,
                        facturado,
                        fecha_cargo,
                        fecha_registro,
                        valor_descuento_empresa,
                        valor_descuento_paciente,
                        porcentaje_gravamen,
                        sw_liq_manual,
                        servicio_cargo,
                        autorizacion_int,
                        autorizacion_ext,
                        sw_cuota_paciente,
                        sw_cuota_moderadora,
                        codigo_agrupamiento_id,
                        consecutivo,
                        cuenta,
                        cargo_cups,
                        sw_cargue,
                        plan_id
                      )
                    VALUES 
                      (
                         ".$Datos[$i][transaccion].",
                        '".$Datos[$i][empresa_id]."',
                        '".$Datos[$i][centro_utilidad]."',
                         ".$Datos[$i][numerodecuenta].",
                        '".$Datos[$i][departamento]."',
                        '".$Datos[$i][tarifario_id]."',
                        '".$Datos[$i][cargo]."',
                         ".$Datos[$i][cantidad].",
                         ".$Datos[$i][precio].",
                         ".$Datos[$i][valor_cargo].",
                         ".$Datos[$i][valor_nocubierto].",
                         ".$Datos[$i][valor_cubierto].",
                         ".$Datos[$i][usuario_id].",
                         ".$Datos[$i][facturado].",
                        '".$Datos[$i][fecha_cargo]."',
                        '".$Datos[$i][fecha_registro]."',
                         ".$Datos[$i][valor_descuento_empresa].",
                         ".$Datos[$i][valor_descuento_paciente].",
                         ".$Datos[$i][porcentaje_gravamen].",
                         ".$Datos[$i][sw_liq_manual].",
                         ".$Datos[$i][servicio_cargo].",
                         ".$AutoInt.",
                         ".$AutoExt.",
                         ".$Datos[$i][sw_cuota_paciente].",
                         ".$Datos[$i][sw_cuota_moderadora].",
                         ".$Datos[$i][codigo_agrupamiento_id].",
                         ".$Datos[$i][consecutivo].",
                         ".$grupo.",
                         ".$Datos[$i][cargo_cups].",
                        '".trim($Datos[$i][sw_cargue])."',
                        '".$Datos[$i][plan_id]."'
                      ) ";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) 
          {
            $this->error = "Error al cuentas_detalle2";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if($valor_compara > 0)
            if($valor_compara <= $suma_total)
              return true;
        }
      }
    }
			/**
			*InsertarTmpCorte
			*/
			function InsertarTmpCorte($results,$grupo)
			{
					list($dbconn) = GetDBconn();
					while (!$results->EOF) {
							$Datos[]=$results->GetRowAssoc($ToUpper = false);
							$results->MoveNext();
					}
					$results->Close();
	
					if(!empty($Datos))
					{
								for($i=0; $i<sizeof($Datos); $i++)
								{
										if(empty($Datos[$i][autorizacion_int]))
										{   $AutoInt='NULL';   }
										else
										{   $AutoInt=$Datos[$i][autorizacion_int];   }
										if(empty($Datos[$i][autorizacion_ext]))
										{   $AutoExt='NULL';   }
										else
										{   $AutoExt=$Datos[$i][autorizacion_ext];   }
										if(empty($Datos[$i][codigo_agrupamiento_id]))
										{   $Datos[$i][codigo_agrupamiento_id]='NULL';   }
										if(empty($Datos[$i][consecutivo]))
										{   $Datos[$i][consecutivo]='NULL';   }
	
										if(empty($Datos[$i][cargo_cups]))
										{   $Datos[$i][cargo_cups]='NULL';   }
										else
										{  $Datos[$i][cargo_cups]="'".$Datos[$i][cargo_cups]."'";  }
	
										$query = "INSERT INTO tmp_corte_cuenta(
																		transaccion,
																		empresa_id,
																		centro_utilidad,
																		numerodecuenta,
																		departamento,
																		tarifario_id,
																		cargo,
																		cantidad,
																		precio,
																		valor_cargo,
																		valor_nocubierto,
																		valor_cubierto,
																		usuario_id,
																		facturado,
																		fecha_cargo,
																		fecha_registro,
																		valor_descuento_empresa,
																		valor_descuento_paciente,
																		porcentaje_gravamen,
																		sw_liq_manual,
																		servicio_cargo,
																		autorizacion_int,
																		autorizacion_ext,
																		sw_cuota_paciente,
																		sw_cuota_moderadora,
																		codigo_agrupamiento_id,
																		consecutivo,
																		cuenta,
																		cargo_cups,
																		sw_cargue,
																		plan_id)
														VALUES (".$Datos[$i][transaccion].",
														'".$Datos[$i][empresa_id]."',
														'".$Datos[$i][centro_utilidad]."',
														".$Datos[$i][numerodecuenta].",
														'".$Datos[$i][departamento]."',
														'".$Datos[$i][tarifario_id]."',
														'".$Datos[$i][cargo]."',
														".$Datos[$i][cantidad].",
														".$Datos[$i][precio].",
														".$Datos[$i][valor_cargo].",
														".$Datos[$i][valor_nocubierto].",
														".$Datos[$i][valor_cubierto].",
														".$Datos[$i][usuario_id].",
														".$Datos[$i][facturado].",
														'".$Datos[$i][fecha_cargo]."',
														'".$Datos[$i][fecha_registro]."',
														".$Datos[$i][valor_descuento_empresa].",
														".$Datos[$i][valor_descuento_paciente].",
														".$Datos[$i][porcentaje_gravamen].",
														".$Datos[$i][sw_liq_manual].",
														".$Datos[$i][servicio_cargo].",
														$AutoInt,
														$AutoExt,
														".$Datos[$i][sw_cuota_paciente].",
														".$Datos[$i][sw_cuota_moderadora].",
														".$Datos[$i][codigo_agrupamiento_id].",
														".$Datos[$i][consecutivo].",
														$grupo,".$Datos[$i][cargo_cups].",
														'".trim($Datos[$i][sw_cargue])."',
														'".$Datos[$i][plan_id]."')";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error al cuentas_detalle2";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														return false;
										}
								}
					}
			}
        
				/**
        *TiposServicios
        */
        function TiposServicios()
        {
                list($dbconn) = GetDBconn();
                $query = "select servicio, descripcion from servicios where sw_asistencial=1";
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

       /**
       * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
       * @ access public
       * @ return boolean
       */
      function LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
      {
//           if(empty($Titulo))
//           {
//             $arreglo=$_REQUEST['arreglo'];
//             $Cuenta=$_REQUEST['Cuenta'];
//             $c=$_REQUEST['c'];
//             $m=$_REQUEST['m'];
//             $me=$_REQUEST['me'];
//             $me2=$_REQUEST['me2'];
//             $mensaje=$_REQUEST['mensaje'];
//             $Titulo=$_REQUEST['titulo'];
//             $boton1=$_REQUEST['boton1'];
//             $boton2=$_REQUEST['boton2'];
//           }

            $html = ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
            return $html;
      }

      /**
      *
      */
      function GuardarEquivalencias()
      {
								//--------revisa si es division para ir a otro metodo----------
								if(!empty($_SESSION['CUENTA']['DIVISION']))
								{
												$_REQUEST=$_SESSION['CUENTA']['REQUEST'];
												$html = $this->GuardarEquivalenciasDivision();
												return $html;
								}
								//-----------fin desde division--------------------------------
	
					IncludeLib("tarifario_cargos");
					IncludeLib("funciones_facturacion");
					$_REQUEST=$_SESSION['CUENTA']['REQUEST'];
					$Cuenta=$_REQUEST['Cuenta'];          
					$TipoId=$_REQUEST['TipoId'];
					$PacienteId=$_REQUEST['PacienteId'];
					$Nivel=$_REQUEST['Nivel'];
					$Plan=$_REQUEST['PlanId'];
					$Ingreso=$_REQUEST['Ingreso'];
					$Fecha=$_REQUEST['Fecha'];
					$cambio=$_REQUEST['Cambio'];
					$Nuevo_Responsable=$_REQUEST['Nuevo_Responsable'];
	
					$PlanId=$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'];
					//ACTUALIZA CAMBIO RESPONSABLE
					list($dbconn) = GetDBconn();
					$query = "update cambio_responsable set
																			usuario_id_final=".UserGetUID().",
																			fecha_registro_final=now()
										where cambio_responsable_id=$cambio";
					$dbconn->BeginTrans();
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						$dbconn->RollbackTrans();
						return false;
					}
					//ACTUALIZAR LA CUENTA
					$query = "select *
										from cambio_responsable
										where cambio_responsable_id=$cambio";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						return false;
					}
					$vars=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->Close();
					//$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']
					$query = "update cuentas set
													tipo_afiliado_id='".$vars[tipo_afiliado_id]."',
													rango='".$vars[rango]."',
													semanas_cotizadas=".$vars[semanas_cotizadas].",
													plan_id=".$Nuevo_Responsable."
										where numerodecuenta=$Cuenta";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error update cuentas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						$dbconn->RollbackTrans();
						return false;
          }

          //guardar en la de lo nuevo
          $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
					//IncludeClass('LiquidacionCargos');
					//$dat = new LiquidacionCargos();
          foreach($_REQUEST as $k => $v)
          {
              if(substr_count($k,'New'))
              {
										$vars='';
										$n=explode(',',$v);

										$query = "select b.*
															from cambio_responsable_detalle_actual as b
															where b.cambio_responsable_detalle_actual_id=$n[0]";
										$result=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											echo $this->mensajeDeError;
											return false;
										}
																		$vars=$result->GetRowAssoc($ToUpper = false);
										$result->Close();

										if(empty($vars[autorizacion_int]))
										{   $vars[autorizacion_int]='NULL';   }
										if(empty($vars[autorizacion_ext]))
										{   $vars[autorizacion_ext]='NULL';   }

																		if(empty($vars[consecutivo]))
										{   $vars[consecutivo]='NULL';   }
																		if(empty($vars[codigo_agrupamiento_id]))
																		{  $vars[codigo_agrupamiento_id]='NULL';  }
										$Cargo=$n[2];
										$TarifarioId=$n[1];
//----------------------------esto es para los calculos-------------------------
										$Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$vars[cantidad],0,0,false,false,'',$PlanId,$Servicio,'','','',true,'','',true);
										//$Liq = $dat->LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$vars[cantidad],0,0,false,false,0,$PlanId,$var[$i]['servicio_al_cargar']);
										$query = "INSERT INTO cambio_responsable_detalle_nuevo(
																		cambio_responsable_detalle_actual_id,
																		transaccion,
																		empresa_id,
																		centro_utilidad,
																		numerodecuenta,
																		departamento,
																		tarifario_id,
																		cargo,
																		cantidad,
																		precio,
																		valor_cargo,
																		valor_nocubierto,
																		valor_cubierto,
																		usuario_id,
																		facturado,
																		fecha_cargo,
																		fecha_registro,
																		valor_descuento_empresa,
																		valor_descuento_paciente,
																		porcentaje_gravamen,
																		sw_liq_manual,
																		servicio_cargo,
																		autorizacion_int,
																		autorizacion_ext,
																		sw_cuota_paciente,
																		sw_cuota_moderadora,
																		codigo_agrupamiento_id,
																		consecutivo,
																		cargo_cups,
																		sw_cargue)
										VALUES (".$vars[cambio_responsable_detalle_actual_id].",".$vars[transaccion].",'".$vars[empresa_id]."','".$vars[centro_utilidad]."',$Cuenta,'".$vars[departamento]."','$TarifarioId','$Cargo',".$vars[cantidad].",".$Liq[precio_plan].",".$Liq[valor_cargo].",".$Liq[valor_no_cubierto].",".$Liq[valor_cubierto].",".$vars[usuario_id].",".$Liq[facturado].",'".$vars[fecha_cargo]."','".$vars[fecha_registro]."',".$Liq[valor_descuento_empresa].",".$Liq[valor_descuento_paciente].",".$Liq[porcentaje_gravamen].",".$vars[sw_liq_manual].",".$vars[servicio_cargo].",".$vars[autorizacion_int].",".$vars[autorizacion_ext].",".$Liq[sw_cuota_paciente].",".$Liq[sw_cuota_moderadora].",  ".$vars[codigo_agrupamiento_id].",".$vars[consecutivo].",'".$vars[cargo_cups]."','".trim($vars[sw_cargue])."')";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al cambio_responsable_detalle_nuevo";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												echo $this->mensajeDeError;
												$dbconn->RollbackTrans();
												return false;
										}

										$query = "UPDATE cuentas_detalle SET
																		empresa_id='".$vars[empresa_id]."',
																		centro_utilidad='".$vars[centro_utilidad]."',
																		numerodecuenta=$Cuenta,
																		departamento='".$vars[departamento]."',
																		tarifario_id='$TarifarioId',
																		cargo='$Cargo',
																		cantidad=".$vars[cantidad].",
																		precio=".$Liq[precio_plan].",
																		valor_cargo=".$Liq[valor_cargo].",
																		valor_nocubierto=".$Liq[valor_no_cubierto].",
																		valor_cubierto=".$Liq[valor_cubierto].",
																		usuario_id=".$vars[usuario_id].",
																		facturado=".$Liq[facturado].",
																		fecha_cargo='".$vars[fecha_cargo]."',
																		valor_descuento_empresa=".$Liq[valor_descuento_paciente].",
																		valor_descuento_paciente=".$Liq[valor_descuento_empresa].",
																		servicio_cargo=".$vars[servicio_cargo].",
																		autorizacion_int=".$vars[autorizacion_int].",
																		autorizacion_ext=".$vars[autorizacion_ext].",
																		porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
																		sw_cuota_paciente='".$Liq[sw_cuota_paciente]."',
																		sw_cuota_moderadora='".$Liq[sw_cuota_moderadora]."',
																		codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
																		consecutivo=".$vars[consecutivo].",
																		fecha_registro='".$vars[fecha_registro]."',
																		cargo_cups='".$vars[cargo_cups]."',
																		sw_cargue='3'
										WHERE transaccion=".$vars[transaccion]."";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Guardar";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												echo $this->mensajeDeError;
												$dbconn->RollbackTrans();
												return false;
										}
              }
          }
					$dbconn->CommitTrans();
					$mensaje='Se Cambio el Responsable de la Cuenta No. '.$Cuenta;
					//$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
					//if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
					//      return false;
					//}
					//return true;
					$titulo = "RELIQUIDAR CUENTA No. ".$Cuenta;
					$accion = SessionGetVar("AccionVolverCargosIYM");
					$boton = "";
					$fact = new OpcionesCuentasHTML();
					$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return $html;
       }

      /**
      * Inactiva la cuenta
      * @access public
      * @return boolean
      */
      function InactivarCuenta()
      {
            $Cuenta=$_REQUEST['Cuenta'];

            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            $query = "UPDATE cuentas SET estado='2' WHERE numerodecuenta=$Cuenta";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
              return false;
            }
            else
            {
                            $query = "INSERT INTO auditoria_inactivar_cuentas (
                                                                                                            numerodecuenta,
                                                                                                            fecha_registro,
                                                                                                            usuario_id)
                                                VALUES($Cuenta,'now()',".UserGetUID().")";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                            else
                            {
                                    $dbconn->CommitTrans();
                                    $mensaje='La Cuenta No. '.$Cuenta.' ha sido Inactivada.';
                                    $_SESSION['ESTADO']='I';
																		$titulo="INACTIVAR CUENTA No. ".$Cuenta;
                                    $accion=SessionGetVar("AccionVolverCargosIYM");
																		$fact = new OpcionesCuentasHTML();
																		$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
																		return $html;
/*                                    if(!$this-> FormaMensaje($mensaje,'INACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
                                    return false;
                                    }
                                    return true;*/
                            }
            }
      }

      /**
      * Activa la cuenta
      * @access public
      * @return boolean
      */
      function OpcionActivarCuenta($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha)
      {
            $Cuenta=$_REQUEST['Cuenta'];
  
            list($dbconn) = GetDBconn();
            $query = "UPDATE cuentas SET estado='1' WHERE numerodecuenta=$Cuenta";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al actualizar cuentas";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            else
            {
							$result->Close();
							$mensaje='La Cuenta No. '.$Cuenta.' ha sido Activada.';
							$titulo = 'ACTIVAR CUENTA  No. '.$Cuenta;
							//$_SESSION['ESTADO']='A';
							$accion=SessionGetVar("AccionVolverCargosIYM");
							$fact = new OpcionesCuentasHTML();
							$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
							return $html;
            }
      }
      /**
      * Activa la cuenta
      * @access public
      * @return boolean
      */
      function OpcionFechaIngreso()
      {
         $fecha_ingreso=explode("-",$_REQUEST['fecha_ingreso']);
         $fecha_ingreso_c=$fecha_ingreso[2]."-".$fecha_ingreso[1]."-".$fecha_ingreso[0];
         //print_r($fecha_ingreso_c);
         $fecha_ahora=$fecha_ingreso_c." ".$_REQUEST['text_hora_com'].":".$_REQUEST['text_hora_com_min'];
         //print_r($$_REQUEST['fecha_ingreso']);
        
         
         list($dbconn) = GetDBconn();
         //$dbconn->debug=true;
         $query = "UPDATE ingresos_salidas SET fecha_registro='".$fecha_ahora."', usuario_cambiofech='".UserGetUID()."'   WHERE ingreso=".$_REQUEST['Ingreso']." ";
         $result=$dbconn->Execute($query);
         if ($dbconn->ErrorNo() != 0)
         {
            $this->error = "Error al actualizar cuentas";
            $mensaje= "Error DB : " . $dbconn->ErrorMsg();
            //return false;
          }
         else
         {
						$result->Close();
						$mensaje='Se realizo la actualizacion de la fecha de salida';
						$titulo = 'ACTUALIZACION DE LA FECHA';
            $boton = "";
					//$_SESSION['ESTADO']='A';
          }
						$accion=SessionGetVar("AccionVolverCargosIYM");
						$fact = new OpcionesCuentasHTML();
						$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
						return $html;
         //$html = $fact->FormaMensaje($mensaje,$titulo,$accion,$boton);
          
      }

        /**
      * Busca que el paciente no tenga otra cuenta activa para poder activar esta inactiva
      * @access public
      * @return boolean
      */
      function BuscarCuentaParaActivar($Cuenta,$TipoId,$PacienteId)
      {
//             $Transaccion=$_REQUEST['Transaccion'];
//             $TipoId=$_REQUEST['TipoId'];
//             $PacienteId=$_REQUEST['PacienteId'];
//             $Nivel=$_REQUEST['Nivel'];
//             $PlanId=$_REQUEST['PlanId'];
//             $Pieza=$_REQUEST['Pieza'];
//             $Cama=$_REQUEST['Cama'];
//             $Fecha=$_REQUEST['Fecha'];
//             $Ingreso=$_REQUEST['Ingreso'];
//             $Cuenta=$_REQUEST['Cuenta'];
//             $Estado=$_REQUEST['Estado'];
//             $Ingreso=$_REQUEST['Ingreso'];

            list($dbconn) = GetDBconn();
            $query = "select a.numerodecuenta
                      from cuentas as a, ingresos as b
                      where b.tipo_id_paciente='$TipoId' and  paciente_id='$PacienteId'
                      and a.ingreso=b.ingreso and a.estado=1";
            $result=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $result->Close();

            //se puede activar la cuenta
            if($result->EOF)
            {
                $mensaje='Esta seguro que desea Activar la Cuenta No. '.$Cuenta.'/**/1';
//                 $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
//                 $c='app';
//                 $m='Facturacion';
//                 $me='ActivarCuenta';
//                 $me2='Cuenta';
//                 $Titulo='ACTIVAR CUENTA No. '.$Cuenta;
//                 $boton1='ACEPTAR';
//                 $boton2='CANCELAR';

                //$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                //return true;
            }
            else
            {
                $mensaje='No se puede Activar la Cuenta No. '.$Cuenta.' el paciente ya tiene una Cuenta Abierta./**/0';
                //$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
/*                if(!$this-> FormaMensaje($mensaje,'ACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
                return false;
                }
                return true;*/
            }
           return $mensaje;
      }
    /**
      * Busca que el paciente no tenga otra cuenta activa para poder activar esta inactiva
      * @access public
      * @return boolean
      */
      function BuscarFechIngreso($Cuenta)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT  b.fecha_registro
                            FROM    cuentas as a, ingresos_salidas as b
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.ingreso=b.ingreso ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       // print_r($query);
				return $var;
      }
     /**
      * Busca 
      * @access public
      * @return boolean
      */
      function BuscarVistoSalida($Cuenta)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT  count(b.*) as vistosali
                            FROM    cuentas as a, hc_vistosok_salida_detalle as b
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.ingreso=b.ingreso ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       // print_r($query);
				return $var;
      }
      /**
      * Busca 
      * @access public
      * @return boolean
      */
      function BuscarSolicMedic($Cuenta)
      {
          list($dbconn) = GetDBconn();
          //$dbconn->debug=true;
          $query = "SELECT     count(b.*) as solicimed
                            FROM    cuentas as a, hc_solicitudes_medicamentos as b
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.ingreso=b.ingreso 
                            AND      b.sw_estado IN ('0','1') ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       // print_r($query);
				return $var;
      }
      /**
      * Busca 
      * @access public
      * @return boolean
      */
      function BuscarSolicDevol($Cuenta)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT     count(b.*) as solidevo
                            FROM    cuentas as a, inv_solicitudes_devolucion as b
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.ingreso=b.ingreso 
                            AND      b.estado IN ('0') ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       // print_r($query);
				return $var;
      }
    /**
     * Busca 
     * @access public
     * @return boolean
     */
     function BuscarPacienUrgen($Cuenta)
     {
         list($dbconn) = GetDBconn();
         $query = "SELECT     count(b.*) as pacienurge
                            FROM    cuentas as a, pacientes_urgencias as b
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.ingreso=b.ingreso 
                            AND      b.sw_estado IN ('1') ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       // print_r($query);
				return $var;
     }
    /**
     * Busca 
     * @access public
     * @return boolean
     */
    function BuscarMovimHabit($Cuenta)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT     count(b.*) as movimhabit
                            FROM    cuentas as a, movimientos_habitacion as b
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.numerodecuenta=b.numerodecuenta 
                            ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       // print_r($query);
				return $var;
    }
      
      /**
      * Busca que el paciente no tenga otra cuenta activa para poder activar esta inactiva
      * @access public
      * @return boolean
      */
      function BuscarParametFechCam($Cuenta,$centro_utilidad,$empresa_id)
      {
        list($dbconn) = GetDBconn();
         
        $query = "SELECT    count(c.sw_cambiofecha) as parame
                            FROM    cuentas as a, ingresos_salidas as b, userpermisos_cuentas as c, departamentos as d
                            WHERE  a.numerodecuenta=".$Cuenta."
                            AND      a.ingreso =b.ingreso
                            AND      d.centro_utilidad ='".$centro_utilidad."' 
                            AND      d.empresa_id ='".$empresa_id."' 
                            AND      d.departamento = c.departamento 	
                            AND      d.empresa_id = c.empresa_id 	
                            AND      c.usuario_id ='".UserGetUID()."' 
                            AND      c.sw_cambiofecha='1' ";
       
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
       
				return $var;
      }
      /**
        **
        **/
        function GuardarEquivalenciasDivision()
        {
                //IncludeLib("tarifario_cargos");
                IncludeLib("funciones_facturacion");
                $cambio=$_REQUEST['Cambio'];
                $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
                $CuentaAnt=$_REQUEST['Cuenta'];
                $TipoId=$_REQUEST['TipoId'];
                $PacienteId=$_REQUEST['PacienteId'];
                $Nivel=$_REQUEST['Nivel'];
                $Plan=$_REQUEST['PlanId'];
                $Ingreso=$_REQUEST['Ingreso'];
                $Fecha=$_REQUEST['Fecha'];
                if(empty($cambio))
                {  $cambio=$_REQUEST['Cambio'];  }
                //$PlanId=$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'];
                if($_REQUEST['Responsable'])
                {$NuevoResponsable=$_REQUEST['Responsable'];}
                elseif($_REQUEST['Nuevo_Responsable'])
                {$NuevoResponsable=$_REQUEST['Nuevo_Responsable'];}

          list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
          //ACTUALIZAR LA CUENTA
          $query = "select * from cambio_responsable where cambio_responsable_id=$cambio";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $vars=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();


          //consulta para saber si en la cuenta anterior liquido manualmente habitaciones
         $query = "SELECT sw_liquidacion_manual_habitaciones FROM cuentas WHERE numerodecuenta=$CuentaAnt";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $Habita=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();
          //fin consulta


                $query="SELECT nextval('cuentas_numerodecuenta_seq')";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $Cuenta=$result->fields[0];
                if(empty($vars[semanas_cotizadas]))
                {  $vars[semanas_cotizadas]=0;  }

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
																							semanas_cotizadas,
																							sw_estado_paciente,
																							fecha_cierre,
																							usuario_cierre,
																							sw_liquidacion_manual_habitaciones)
													VALUES($Cuenta,'".SessionGetVar('EmpresaId')."','".SessionGetVar('CentroUtilidad')."',
													$Ingreso,".$NuevoResponsable.",'2','".UserGetUID()."','now()',
													'".$vars[tipo_afiliado_id]."','".$vars[rango]."',NULL,NULL,".$vars[semanas_cotizadas].",0,NULL,NULL,
													'".$Habita['sw_liquidacion_manual_habitaciones']."')";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error cuentas";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                echo $this->mensajeDeError;
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                }
                            //exit;
                $afiliado = $vars[tipo_afiliado_id];
                $rango = $vars[rango];
                $sem = $vars[semanas_cotizadas];

                //ACTUALIZA CAMBIO RESPONSABLE
               $query = "update cambio_responsable set
																usuario_id_final=".UserGetUID().",
																fecha_registro_final=now()
													where cambio_responsable_id=$cambio";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }

                //guardar en la de lo nuevo
                $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
                foreach($_REQUEST as $k => $v)
                {
                        if(substr_count($k,'New'))
                        {
                                    $vars='';
                                    $n=explode(',',$v);

                                   $query = "select b.*
                                              from cambio_responsable_detalle_actual as b
                                              where b.cambio_responsable_detalle_actual_id=$n[0]

                                              ";

                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    $vars=$result->GetRowAssoc($ToUpper = false);
                                    $result->Close();

                                    if(empty($vars[autorizacion_int]))
                                    {   $vars[autorizacion_int]='NULL';   }
                                    if(empty($vars[autorizacion_ext]))
                                    {   $vars[autorizacion_ext]='NULL';   }

                                    if(empty($vars[consecutivo]))
                                    {   $vars[consecutivo]='NULL';   }
                                    if(empty($vars[codigo_agrupamiento_id]))
                                    {  $vars[codigo_agrupamiento_id]='NULL';  }
                                    $Cargo=$n[2];
                                    $TarifarioId=$n[1];
//----------------------------esto es para los calculos-------------------------
                                    //esto es para eliminar de la tablas cuentas codigo agrupamiento
                                    //los agrupamientos q ya no tengan o no agrupen registros en la
                                    //anterior cuenta
                                    $query = "select codigo_agrupamiento_id
                                          from cuentas_detalle
                                          where transaccion=".$vars[transaccion]."
                                          and numerodecuenta=".$CuentaAnt."";

                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    if($result->fields[0]){
                                      $agrupamientoAnt=$result->fields[0];
                                    }


                                    //fin
                                    //nuevo cambio por lo de paquetes pues a la nueva cuenta
                                    //llegan sin paquete  
                                    $query = "UPDATE cuentas_detalle SET
																											numerodecuenta=$Cuenta,
																											tarifario_id='$TarifarioId',
																											cargo='$Cargo',
																											codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
																											paquete_codigo_id=NULL,
																											sw_paquete_facturado=NULL
																							WHERE transaccion=".$vars[transaccion]."
																							and numerodecuenta=".$CuentaAnt."";

                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }

                                    $query = "UPDATE audit_cuentas_detalle
                                              SET numerodecuenta=$Cuenta,
                                              tarifario_id='$TarifarioId',
                                              cargo='$Cargo',
                                              codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
                                              paquete_codigo_id=NULL,
                                              sw_paquete_facturado=NULL
                                              WHERE transaccion=".$vars[transaccion]."
                                              AND numerodecuenta=".$CuentaAnt."";

                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }

                                    //fin cambio

                                    if($agrupamientoAnt){
                                      $query = "SELECT *
                                            FROM  cuentas_detalle b
                                            WHERE b.codigo_agrupamiento_id=".$agrupamientoAnt."
                                            AND numerodecuenta=".$CuentaAnt."
                                            ";

                                      $resultadoAgrupa=$dbconn->Execute($query);
                                      if ($dbconn->ErrorNo() != 0) {
                                          $this->error = "Error al Guardar";
                                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                          return false;
                                      }
                                      if($resultadoAgrupa->RecordCount()<1){
                                        $EliminaCuantasAgrupa[]=$agrupamientoAnt;
                                      }
                                    }

                        }
                }
                //hice esta actualizacion para q se dispare el trigger q actualiza los valores totales de
                //la cuenta vieja pues no se estaban actualizando
                $query = "UPDATE cuentas_detalle
                               SET numerodecuenta=$CuentaAnt
                               WHERE numerodecuenta=".$CuentaAnt."";

                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                //-------------GUARDAR LOS ABONOS-----------------
                $abono=$this->DivisionAbonosCuenta('',$NuevoResponsable);
                for($d=0; $d<sizeof($abono); $d++)
                {
                        $query ="UPDATE rc_detalle_hosp SET numerodecuenta=$Cuenta
                                            WHERE prefijo='".$abono[$d][prefijo]."' AND recibo_caja=".$abono[$d][recibo_caja]."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error UPDATE rc_detalle_hosp ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                        }
                }
                //--------------FIN ABONOS-----------------------

                $query =" DELETE FROM tmp_division_cuenta
                                    WHERE numerodecuenta=".$_REQUEST['Cuenta']." and plan_id=".$NuevoResponsable."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                $query =" DELETE FROM tmp_division_cuenta_abonos
                                    WHERE numerodecuenta=".$_REQUEST['Cuenta']." and plan_id=".$NuevoResponsable."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                for($c=0;$c<sizeof($EliminaCuantasAgrupa);$c++){
                  $query = "SELECT *
                            FROM cuentas_codigos_agrupamiento
                            WHERE codigo_agrupamiento_id =".$EliminaCuantasAgrupa[$c]."";
                  $result=$dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
                  }else{
                    if($result->RecordCount()<1){ 
                      $query = "DELETE FROM cuentas_codigos_agrupamiento WHERE codigo_agrupamiento_id =".$EliminaCuantasAgrupa[$c]."";
    
                      $dbconn->Execute($query);
                      if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error al Guardar";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          $dbconn->RollbackTrans();
                          return false;
                      }
                    }
                  }    
                }
                //va a reliquidar

                $this->Reliquidar($Cuenta,$TipoId,$PacienteId,$Nivel,$NuevoResponsable,$Ingreso,$Fecha,1);
                $dbconn->CommitTrans();
                //--------------GUARDA LA CUENTA-----------------
                    $_SESSION['DIVISION']['CUENTA'][]=array('cuenta'=>$Cuenta,'plan'=>$NuevoResponsable);
                    unset($_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
                //------valida si hay mas planes para dividir la cuenta
                
                $det = $this->DetalleNuevo($_REQUEST['Cuenta']); 
                for($i=0; $i<sizeof($det); $i++){
                  if($det[$i]['cuenta']!='0'){
                    $_REQUEST['Responsable']=$det[$i]['plan_id'];
                    $_REQUEST['indice']=$det[$i]['cuenta'];
                    $_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
                    $fact = new OpcionesCuentasHTML();
                    $html = $fact->FormaCuentaGenerada($det[$i]['plan_id'],$det[$i]['plan_descripcion'],$_REQUEST['Cuenta']);
                    return $html;
                    //$this->FormaCuentaGenerada($det[$i]['plan_id'],$det[$i]['plan_descripcion'],$_REQUEST['Cuenta']);
                    //return true;
                  }                   
                }                        
                $det = $this->DivisionSoloAbonosCuenta($_REQUEST['Cuenta'],'');
                for($i=0; $i<sizeof($det); $i++){
                  if($det[$i]['cuenta']!='0'){                
                    $_REQUEST['Responsable']=$det[$i]['plan_id'];
                    $_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
                    $_REQUEST['indice']=$det[$i]['cuenta'];
                    $fact = new OpcionesCuentasHTML();
                    $html = $fact->FormaCuentaGenerada($det[$i]['plan_id'],$det[$i]['plan_descripcion']);
                    return $html;
                    //$this->FormaCuentaGenerada($det[$i]['plan_id'],$det[$i]['plan_descripcion']);
                    //return true;
                  }  
                }
                //ya no hay mas divisiones
                $fact = new OpcionesCuentasHTML();
                $html = $fact->FormaCuentasDivision();
                return $html;
                //$this->FormaCuentasDivision();
                //return true;
        }

      /**
      *** DivisionAbonosCuenta
      **/
			function DivisionAbonosCuenta($Cuenta,$Plan)
			{
							if(!empty($Cuenta))
							{   $x = "numerodecuenta=$Cuenta and "; }

				list($dbconn) = GetDBconn();
					$query = "select * from tmp_division_cuenta_abonos where $x plan_id=$Plan";
				$results = $dbconn->Execute($query);
				while (!$results->EOF) {
						$var[]=$results->GetRowAssoc($ToUpper = false);
						$results->MoveNext();
				}
				$results->Close();
				return $var;
			}
      /**
      * La funcion BuscarNombresPaciente se encarga de buscar en la base de datos los nombres de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      */
     function BuscarNombresPaciente($tipo,$documento)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            else{
              if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                return false;
              }
            }
          $Nombres=$result->fields[0]." ".$result->fields[1];
          $result->Close();
        return $Nombres;
     }

      /**
      * Se encarga de buscar en la base de datos los apellidos de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      */
      function BuscarApellidosPaciente($tipo,$documento)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          else{
            if($result->EOF){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "La tabla 'paciente' esta vacia ";
              return false;
            }
          }
          $result->Close();
          $Apellidos=$result->fields[0]." ".$result->fields[1];
        return $Apellidos;
      }

      /**
      *
      */
      function CuentaParticular($Cuenta,$PlanId)
      {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.tipo_id_tercero,a.tercero_id, b.nombre_tercero, c.plan_descripcion, c.protocolos
                      FROM cuentas_responsable_particular as a, terceros as b, planes as c
                      WHERE a.numerodecuenta='$Cuenta' AND a.tipo_id_tercero=b.tipo_id_tercero
                      AND a.tercero_id=b.tercero_id AND c.plan_id='$PlanId' ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            if(!$result->EOF)
            {
              $var=$result->GetRowAssoc($ToUpper = false);
            }
            $result->Close();
            return $var;
      }

      /**
      *
      */
       function BuscarPlanes($PlanId,$Ingreso)
       {
            list($dbconn) = GetDBconn();
            $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $sw=$results->fields[0];
            //soat
            if($sw==1)
            {
               $query = "SELECT  b.nombre_tercero, c.plan_descripcion, e.tipo_id_tercero, e.tercero_id, c.protocolos, d.saldo, c.sw_tipo_plan
                            FROM ingresos_soat as a, terceros as b, planes as c,
                            soat_eventos as d, soat_polizas as e
                            WHERE a.ingreso=$Ingreso AND a.evento=d.evento AND e.tipo_id_tercero=b.tipo_id_tercero
                            AND e.tercero_id =b.tercero_id AND c.plan_id='$PlanId' AND d.poliza=e.poliza";
            }
            //cliente o capitacion
            if($sw==0 OR $sw==3)
            {
               $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                            FROM planes as a, terceros as b
                            WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                    }
            //particular
            if($sw==2)
            {
               $query = "select b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_tercero,
                          c.plan_descripcion, b.tipo_id_paciente as tipo_id_tercero, b.paciente_id as tercero_id, c.protocolos
                          from ingresos as a, pacientes as b, planes as c
                          where a.ingreso='$Ingreso' and a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente
                          and c.plan_id='$PlanId'";
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

			/**
			*
			*/
			function BuscarTipoAfiliado($cuenta)
			{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.tipo_afiliado_nombre, b.rango
								FROM tipos_afiliado as a, cuentas as b
								WHERE b.numerodecuenta=$cuenta and b.tipo_afiliado_id=a.tipo_afiliado_id";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

							$vars=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->Close();
			return $vars;
			}

      function DetalleCambioACtual($PlanId,$Cuenta)
      {
            list($dbconn) = GetDBconn();
            $query = "select a.cambio_responsable_id, b.cambio_responsable_detalle_actual_id, b.tarifario_id, b.cargo,b.cantidad,
                      d.codigo_producto, (CASE WHEN b.consecutivo IS NOT NULL THEN e.descripcion ELSE c.descripcion END) as descripcion
                      from cambio_Responsable as a, cambio_responsable_detalle_actual as b
                      LEFT JOIN cuentas_codigos_agrupamiento f ON (b.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                      LEFT JOIN bodegas_documentos_d d ON (b.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                      LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                      join tarifarios_detalle as c on (b.cargo=c.cargo and b.tarifario_id=c.tarifario_id)
                      where a.numerodecuenta=$Cuenta and a.plan_id_actual=$PlanId and
                      b.cambio_responsable_id=a.cambio_responsable_id  and a.usuario_id_inicio=".UserGetUID()."";
            $result=$dbconn->Execute($query);
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

      /**
      *
      */
      function BuscarNombreCompletoPaciente($tipo,$documento)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                    FROM pacientes
                                    WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            else{
              if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                return false;
              }
            }
          $Nombres=$result->fields[0];
          $result->Close();
             return $Nombres;
        }
      /**
      *
      */
      function NuevoResponsable($Cuenta,$Ingreso,$PlanId,$TipoId,$PacienteId,$Responsable)
      {
     
				if($_REQUEST[PlanId] AND $_REQUEST[Cuenta])
				{
					$PlanId = $_REQUEST[PlanId];
					$Cuenta = $_REQUEST[Cuenta];
					$TipoId = $_REQUEST[TipoId];
					$PacienteId = $_REQUEST[PacienteId];
					$Ingreso = $_REQUEST[Ingreso];
					$Nivel = $_REQUEST[Nivel]; 
					$Fecha = $_REQUEST[Fecha];
					$vars = $_REQUEST[vars];
					$Responsable = $_REQUEST[Responsable];
					$descripcion_plan = $_REQUEST[descripcion_plan];
				}

				if($Responsable==-1)
				{
						if($Responsable==-1){ $this->frmError["Responsable"]=-1; }
						$this->frmError["MensajeError"]="Debe Elegir el Nuevo Plan.";
						echo $this->frmError["MensajeError"];
						$fact = new OpcionesCuentasHTML();
						$html = $fact->FormaCambioResponsable($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
						return $html;
						//$this->FormaCambioResponsable($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
						//return true;
				}

				if($Responsable==$PlanId)
				{
						if($_REQUEST['Responsable']==-1){ $this->frmError["Responsable"]=-1; }
						$this->frmError["MensajeError"]="Debe Elegir el un Plan Diferente al que ya Tiene la Cuenta.";
						echo $this->frmError["MensajeError"];
						$fact = new OpcionesCuentasHTML();
						$html = $fact->FormaCambioResponsable($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
						return $html;
						//$this->FormaCambioResponsable($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
						//return true;
				}

				UNSET($_SESSION['CUENTA']['CAMBIO']);
				//$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']=$_REQUEST['Responsable'];
				$_SESSION['CUENTA']['CAMBIO']['indice']=$_REQUEST['indice'];
       if(empty($_REQUEST['indice']))
        {
          $_SESSION['CUENTA']['CAMBIO']['indice']='1';
        
        
        }
					
				list($dbconn) = GetDBconn();
				$query = "SELECT  sw_tipo_plan
									FROM planes
									WHERE plan_id='".$Responsable."'";
      
                  
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->frmError["MensajeError"];
						$dbconn->RollbackTrans();
						return false;
				}
				//si no es soat
				if($results->fields[0]!=1)
				{  
							//unset($_SESSION['SOAT']);
							$fact = new OpcionesCuentasHTML();
							$html = $fact->FormaDatosAfiliado($PlanId,$Cuenta,$Ingreso,$TipoId,$PacienteId,$_REQUEST['Responsable']);
							return $html;
				}
				else
				{ //si el plan es soat
							$fact = new app_Cuentas_user();
							$html = $fact->LlamarModuloSoat($PlanId,$Cuenta,$TipoId,$PacienteId,$_REQUEST['Responsable']);
							return $html;
				}
      }

      /**
      * Busca los diferentes tipos de afiliados
      * @access public
      * @return array
      */
        function Tipo_Afiliado($nuevo_plan_id)
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                      FROM tipos_afiliado as a, planes_rangos as b
                      WHERE b.plan_id='".$nuevo_plan_id."'
                      and b.tipo_afiliado_id=a.tipo_afiliado_id";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }

            while(!$resulta->EOF)
            {
                $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }
            $resulta->Close();
            return $vars;
        }

      /**
      * Busca los niveles del plan del responsable del paciente
      * @access public
      * @return array
      * @param string plan_id
      */
       function Niveles($nuevo_plan_id)
       {
            list($dbconn) = GetDBconn();
             $query="SELECT DISTINCT rango
                    FROM planes_rangos
                    WHERE plan_id='".$nuevo_plan_id."'";
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
          return $niveles;
       }

		/**
		*Departamentos
		*/
		function Departamentos($EmpresaId,$CentroU)
		{
				if(empty($EmpresaId)){
          $EmpresaId = SessionGetVar("DatosEmpresaId");
        }
        
        
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
		* Busca los diferentes tipos de responsable (planes)
		* @access public
		* @return array
		*/
			function responsables()
			{
					list($dbconn) = GetDBconn();
          $empresa_id = SessionGetVar("DatosEmpresaId");
					$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
									WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now()
                  AND empresa_id = '".$empresa_id."'
																	order by plan_descripcion";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else{
						if($result->EOF){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "La tabla 'planes' esta vacia ";
							return false;
						}
							$i=0;
							while (!$result->EOF) {
							$planes[$i]=$result->fields[0].'|/'.$result->fields[1].'|/'.$result->fields[2].'|/'.$result->fields[3];
							$result->MoveNext();
							$i++;
							}
				}
				$result->Close();
				return $planes;
			}

		/**
		* Busca los grupos de cargos a los que se les puede aplicar descuentos
		* @access public
		* @return array
		*/
			function BuscarSolicitudesDescuentos()
			{
					list($dbconn) = GetDBconn();
					$query="select * from grupos_tipos_cargo where grupo_tipo_cargo!='SYS'";
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
			}

		/**
		* Busca los descuentos que tiene un grupo de cargo especifico en la cuenta
		* @access public
		* @return boolean
		* @param int numero de cuenta
		* @param string tipo del grupo cargo
		*/
			function BuscarDescuentosCuenta($Cuenta,$Tipo)
			{
					list($dbconn) = GetDBconn();
					$query="select * from cuentas_descuentos
									where numerodecuenta=$Cuenta and grupo_tipo_cargo='$Tipo'";
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
			}
      
      /**
			* Actualiza e inserta descuentos que aplican a la cuenta para pacientes y empresas
			* @access public
			* @return boolean
			*/
			function GuardarDescuentos()
			{
					$Cuenta=$_REQUEST['Cuenta'];
					$TipoId=$_REQUEST['TipoId'];
					$PacienteId=$_REQUEST['PacienteId'];
					$Nivel=$_REQUEST['Nivel'];
					$PlanId=$_REQUEST['PlanId'];
					$Ingreso=$_REQUEST['Ingreso'];
					$Fecha=$_REQUEST['Fecha'];
	
					$f=0;
					foreach($_REQUEST as $k => $v)
					{
							if($f==0)
							{
								if(substr_count($k,'DesEmp'))
								{
									if(!empty($v))
									{ $f=1; }
								}
								if(substr_count($k,'DesPac'))
								{
									if(!empty($v))
									{ $f=1; }
								}
							}
					}
	
					if($f==0)
					{
							$this->frmError["MensajeError"]="Debe asignar algun tipo de Descuento.";
							$frm = new OpcionesCuentasHTML();
							$html = $frm->FormaDescuentos($Cuenta);
							return $html;
							//$this->FormaDescuentos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
							//return true;
					}
					else
					{
								list($dbconn) = GetDBconn();
								foreach($_REQUEST as $k => $v)
								{
											if(substr_count($k,'DesEmp'))
											{
													$f=explode(',',$k);
													$x=$_REQUEST['DesPac,'.$f[1].','.$f[2]];
													if(!$v) {  $v=0;  }
													if(!$x) {  $x=0;  }
	
													$query = "select descuento_empresa,descuento_paciente
																		from cuentas_descuentos
																		where numerodecuenta=$Cuenta and grupo_tipo_cargo='$f[2]'";
													$result=$dbconn->Execute($query);
													if(!$result->EOF)
													{
																$query = "UPDATE cuentas_descuentos SET descuento_empresa=$v,
																																				descuento_paciente=$x
																					WHERE numerodecuenta=$Cuenta and grupo_tipo_cargo='$f[2]'";
																$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																		$this->error = "Error UPDATE cuentas_descuentos";
																		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		return false;
																}
													}
													else
													{
														if($x!=0 || $v!=0)
														{
																$query = "INSERT INTO  cuentas_descuentos(numerodecuenta,
																																					grupo_tipo_cargo,
																																					descuento_empresa,
																																					descuento_paciente)
																								VALUES($Cuenta,'$f[2]',$v,$x)";
																$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																		$this->error = "Error INTO  cuentas_descuentos";
																		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		return false;
																}
														}
													}
											}
								}
								$this->Reliquidar($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
								return true;
					}
			}
    /**
    *
    */
    function ObtenerCargosCuenta()
    {
      list($dbconn) = GetDBconn();
      
      $sql  = "SELECT tmp_division_cuenta_id,";
      $sql .= " 	    transaccion 	,";
      $sql .= " 	    empresa_id 	,";
      $sql .= " 	    centro_utilidad 	,";
      $sql .= " 	    numerodecuenta 	,";
      $sql .= " 	    departamento 	,";
      $sql .= " 	    tarifario_id 	,";
      $sql .= " 	    cargo 	,";
      $sql .= " 	    cantidad 	,";
      $sql .= " 	    precio 	,";
      $sql .= " 	    porcentaje_descuento_empresa 	,";
      $sql .= " 	    valor_cargo 	,";
      $sql .= " 	    valor_nocubierto 	,";
      $sql .= " 	    valor_cubierto 	,";
      $sql .= " 	    facturado 	,";
      $sql .= " 	    fecha_cargo 	,";
      $sql .= " 	    usuario_id 	,";
      $sql .= " 	    fecha_registro 	,";
      $sql .= " 	    sw_liq_manual 	,";
      $sql .= " 	    valor_descuento_empresa 	,";
      $sql .= " 	    valor_descuento_paciente 	,";
      $sql .= " 	    porcentaje_descuento_paciente 	,";
      $sql .= " 	    servicio_cargo 	,";
      $sql .= " 	    autorizacion_int 	,";
      $sql .= " 	    autorizacion_ext 	,";
      $sql .= " 	    porcentaje_gravamen 	,";
      $sql .= " 	    sw_cuota_paciente 	,";
      $sql .= " 	    sw_cuota_moderadora 	,";
      $sql .= " 	    codigo_agrupamiento_id 	,";
      $sql .= " 	    consecutivo 	,";
      $sql .= " 	    cargo_cups 	,";
      $sql .= " 	    cuenta 	,";
      $sql .= " 	    sw_cargue 	,";
      $sql .= " 	    plan_id ";
      $sql .= "FROM   tmp_division_cuenta ";
      $sql .= "WHERE  numerodecuenta = ".$Cuenta." ";
      $sql .= "ORDER BY transaccion ";
      
      $rst = $dbconn->Execute($sql);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $datos = array();
      
      while(!$rst->EOF)
      {
        $datos[$rst->fields[34]][] = $rst->GetRowAssoc($toUpper=false);
        $rst->MoveNext();
      }
      return $datos;
    }
    /**
    * Metodo donde se realiza la division de cuentas que pertenecen a un plan tipo soat
    * 
    * @param integer $Cuenta Identificador de la cuenta
    * @param string $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function DivisionCuentasSoat($Cuenta,$empresa)
    {
      unset($_SESSION['CUENTA']['ABONOS']);
      unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
      unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
      SessionDelVar("CargoAdicionalesValor");

      $cxn = new ConexionBD();
      
      $cxn->ConexionTransaccion();
      
      $sql  = "DELETE FROM tmp_division_cuenta ";
      $sql .= "WHERE  numerodecuenta = ".$Cuenta." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ELIMINAR EL TEMPORAL";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }

      $sql  = "UPDATE cuentas ";
      $sql .= "SET    estado = '2' ";
      $sql .= "WHERE  numerodecuenta = ".$Cuenta." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ACTUALIZAR CUENTAS";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $cxn->Commit();
      
      $tidFisalud = ModuloGetVar('','',"tipo_FIDUFOSYGA");
      $idFisalud = ModuloGetVar('','',"id_FIDUFOSYGA");
      $uniFisalud = ModuloGetvar("app","Soat","CoberturaSoatFidusalud");
      $uniSoat = ModuloGetvar("app","Soat","CoberturaSoatAseguradora");

      $i =1;
      
      $sql  = "SELECT plan_id, ";
      $sql .= "       plan_descripcion ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  tipo_tercero_id = '".$tidFisalud."' ";
      $sql .= "AND    tercero_id = '".$idFisalud."' ";
      $sql .= "AND    empresa_id = '".$empresa."' ";
      $sql .= "AND    estado = '1' ";
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL CONSULTAR DATOS FISALUD";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      $planFisalud = array();
      if(!$rst->EOF)
      {
				$planFisalud = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->Close();
      $planFisalud['indice'] = $i;
      $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][$i][$planFisalud['plan_id']] = $planFisalud['plan_descripcion'];
      
      $sql  = "SELECT salario_dia ";
      $sql .= "FROM   salario_minimo_ano ";
      $sql .= "WHERE  ano = '".date("Y")."' ";
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL CONSULTAR DATOS SALARIO MINIMO";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      $unidades = array();
      if(!$rst->EOF)
      {
				$unidades = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->Close();
      
      $valorFisalud = $unidades['salario_dia']*$uniFisalud;
      $valorSoat = $unidades['salario_dia']*$uniSoat;
            
      $sql  = "SELECT EV.plan_id, ";
      $sql .= "       PL.plan_descripcion ";
      $sql .= "FROM   cuentas CU, ";
      $sql .= "       ingresos_soat IG, ";
      $sql .= "       soat_eventos EV, ";
      $sql .= "       planes PL ";
      $sql .= "WHERE  CU.numerodecuenta = ".$Cuenta." ";
      $sql .= "AND    CU.ingreso = IG.ingreso ";
      $sql .= "AND    IG.evento = EV.evento ";
      $sql .= "AND    EV.plan_id = PL.plan_id ";
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL CONSULTAR PLAN PACIENTE ";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      $planPaciente = array();
      if(!$rst->EOF)
      {
				$planPaciente = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->Close();
      $i++;
      if(!empty($planPaciente))
      {
        $planPaciente['indice'] = $i;
        $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][$i][$planPaciente['plan_id']] = $planPaciente['plan_descripcion'];
      }
      else
      {
        $this->mensaje = "PARA EL EVENTO DEL PACIENTE AUN NO SE HA DEFINIDO EL PLAN QUE CUBRE LOS GASTOS ,UNA VEZ SE HA SUPERADO LOS TOPES DE FOSYGA Y LA ASEGURADORA";
      }
            
      $sql  = "SELECT a.*,";
      $sql .= "       b.descripcion,";
      $sql .= "       c.plan_id ";
      $sql .= "FROM   cuentas_detalle a ";
      $sql .= "       LEFT JOIN cuentas_codigos_agrupamiento f ";
      $sql .= "       ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id), ";
      $sql .= "       tarifarios_detalle b,";
      $sql .= "       cuentas c ";
      $sql .= "WHERE  a.numerodecuenta = ".$Cuenta." ";
      $sql .= "AND    a.cargo=b.cargo ";
      $sql .= "AND    a.tarifario_id=b.tarifario_id  ";
      $sql .= "AND    a.numerodecuenta=c.numerodecuenta ";
      //$sql .= "ORDER BY a.transaccion ";
      $sql .= "ORDER BY f.cuenta_liquidacion_qx_id DESC,f.bodegas_doc_id DESC, ";
      $sql .= "     a.fecha_cargo, a.codigo_agrupamiento_id, a.transaccion ";  
                  
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL CONSULTAR CUENTA";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $detalleCuenta = array();
      while(!$rst->EOF)
      {
				$detalleCuenta[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->Close();
      
      if(!empty($detalleCuenta))
      {
        $sumaSoat = $sumaFisalud = 0;
        $cxn->ConexionTransaccion();
        foreach($detalleCuenta as $key => $dtl)
        {
          $AutoInt = (empty($dtl['autorizacion_int']))? "NULL" :$dtl['autorizacion_int'];
          $AutoExt = (empty($dtl['autorizacion_ext']))? "NULL" :$dtl['autorizacion_ext'];
          
          if(empty($dtl['codigo_agrupamiento_id']))
            $dtl['codigo_agrupamiento_id'] = 'NULL';
            
          if(empty($dtl['consecutivo']))
            $dtl['consecutivo'] = 'NULL';

          if(empty($dtl['cargo_cups']))
            $dtl['cargo_cups'] = 'NULL';
          else
            $dtl['cargo_cups'] = "'".$dtl['cargo_cups']."'";
          
          $plan = $dtl['plan_id'];
          $grupo = 0;
          
          if($valorSoat > $sumaSoat + $dtl['valor_cargo'])
          {
            $sumaSoat += $dtl['valor_cargo'];
          }
          else if($valorFisalud > $sumaFisalud + $dtl['valor_cargo'])
          {
            $plan = $planFisalud['plan_id'];
            $sumaFisalud += $dtl['valor_cargo'];
            $grupo = $planFisalud['indice'];
          }
          else
          {
            if($planPaciente['plan_id'])
            {
              $plan = $planPaciente['plan_id'];
              $grupo = $planPaciente['indice'];
            }
          }
          
          $sql = "INSERT INTO tmp_division_cuenta
                      (
                        transaccion,
                        empresa_id,
                        centro_utilidad,
                        numerodecuenta,
                        departamento,
                        tarifario_id,
                        cargo,
                        cantidad,
                        precio,
                        valor_cargo,
                        valor_nocubierto,
                        valor_cubierto,
                        usuario_id,
                        facturado,
                        fecha_cargo,
                        fecha_registro,
                        valor_descuento_empresa,
                        valor_descuento_paciente,
                        porcentaje_gravamen,
                        sw_liq_manual,
                        servicio_cargo,
                        autorizacion_int,
                        autorizacion_ext,
                        sw_cuota_paciente,
                        sw_cuota_moderadora,
                        codigo_agrupamiento_id,
                        consecutivo,
                        cuenta,
                        cargo_cups,
                        sw_cargue,
                        plan_id
                      )
                    VALUES 
                      (
                         ".$dtl['transaccion'].",
                        '".$dtl['empresa_id']."',
                        '".$dtl['centro_utilidad']."',
                         ".$dtl['numerodecuenta'].",
                        '".$dtl['departamento']."',
                        '".$dtl['tarifario_id']."',
                        '".$dtl['cargo']."',
                         ".$dtl['cantidad'].",
                         ".$dtl['precio'].",
                         ".$dtl['valor_cargo'].",
                         ".$dtl['valor_nocubierto'].",
                         ".$dtl['valor_cubierto'].",
                         ".$dtl['usuario_id'].",
                         ".$dtl['facturado'].",
                        '".$dtl['fecha_cargo']."',
                        '".$dtl['fecha_registro']."',
                         ".$dtl['valor_descuento_empresa'].",
                         ".$dtl['valor_descuento_paciente'].",
                         ".$dtl['porcentaje_gravamen'].",
                         ".$dtl['sw_liq_manual'].",
                         ".$dtl['servicio_cargo'].",
                         ".$AutoInt.",
                         ".$AutoExt.",
                         ".$dtl['sw_cuota_paciente'].",
                         ".$dtl['sw_cuota_moderadora'].",
                         ".$dtl['codigo_agrupamiento_id'].",
                         ".$dtl['consecutivo'].",
                         ".$grupo.",
                         ".$dtl['cargo_cups'].",
                        '".trim($dtl['sw_cargue'])."',
                         ".$plan."
                      ) ";
          if(!$rst = $cxn->ConexionTransaccion($sql))
          {
            $this->error = "ERROR AL INGRESAR EL DETALLE DE LA CUENTA AL TEMPORAL";
            $this->mensajeDeError = $cxn->mensajeDeError;
            return false;
          }
        }
        $cxn->Commit();
      }
      return true;
    }
    /**
    * Metodo donde se obtienen las cuentas que pertenecen a un mismo ingreso
    *
    * @param integer $ingreso Identificador del ingreso
    * @param integer $cuenta Identificador de la cuenta
    *
    * @return boolean
    */
    function ObtenerCuentasxIngreso($ingreso,$cuenta)
    {
      $sql  = "SELECT numerodecuenta "; 
      $sql .= "FROM   cuentas ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    numerodecuenta != ".$cuenta." ";
      $sql .= "AND    estado IN ('1','2') ";
      
      $cxn = new ConexionBD();
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL OBTENER CUENTAS DEL INGRESO";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $datos = array();
      
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($toUpper=false);
        $rst->MoveNext();
      }
      return $datos; 
    }
    /**
    * Metodo para realizar la unificacion de una cuenta
    *
    * @param integer $cuentaI Identificador de la cuenta que quedara
    * @param integer $cuentaA Identificador de la cuenta a unir con la cuenta inicial
    *
    * @return boolean
    */
    function UnificarCuentas($cuentaI, $cuentaA)
    {
      $cxn = new ConexionBD();
      
      $cxn->ConexionTransaccion();
      
      $sql  = "UPDATE rc_detalle_hosp ";
      $sql .= "SET    numerodecuenta = ".$cuentaI." ";
      $sql .= "WHERE  numerodecuenta = ".$cuentaA." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ACTUALIZAR RECIBOS DE CAJA HOSPITALARIOS";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $sql  = "UPDATE rc_detalle_pagare ";
      $sql .= "SET    numerodecuenta = ".$cuentaI." ";
      $sql .= "WHERE  numerodecuenta = ".$cuentaA." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ACTUALIZAR PAGARES";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $sql  = "UPDATE rc_devoluciones ";
      $sql .= "SET    numerodecuenta = ".$cuentaI." ";
      $sql .= "WHERE  numerodecuenta = ".$cuentaA." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ACTUALIZAR DEVOLUCIONES";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }      
      
      $sql  = "UPDATE cuentas_detalle ";
      $sql .= "SET    numerodecuenta = ".$cuentaI." ";
      $sql .= "WHERE  numerodecuenta = ".$cuentaA." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ACTUALIZAR EL DETALLE DE LA CUENTA";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }      
      
      $sql  = "UPDATE cuentas ";
      $sql .= "SET    estado = '5' ";
	  $sql .= ", total_cuenta = 0, abono_efectivo = 0, abono_cheque = 0, abono_tarjetas = 0,  abono_chequespf = 0, abono_letras = 0, valor_cuota_paciente = 0 ";
	  $sql .= ", valor_nocubierto = 0, valor_cubierto = 0, valor_cuota_moderadora = 0, valor_total_paciente = 0,  valor_total_empresa = 0, valor_total_cargos = 0, anulado_unificado='1' ";
      $sql .= "WHERE  numerodecuenta = ".$cuentaA." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL ANULAR LA CUENTA";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
	    
      $cxn->Commit();
      return true;
    }
    /**
    * Metodo donde se obtiene el detalle agrupado de cargos de la cuenat
    *
    * @param integer $cuenta Identificador de la cuenta
    *
    * @return boolean
    */
    function ObtenerDetalleAgrupado($cuenta)
    {
      $sql  = "SELECT valor_nocubierto, ";
      $sql .= "       valor_cubierto,";
      $sql .= "       valor_cargo,";
      $sql .= "       codigo_agrupamiento_id,";
      $sql .= "       descripcion,";
      $sql .= "       por_descuento_empresa,";
      $sql .= "       por_descuento_paciente ";
      $sql .= "FROM   (";
      $sql .= "         SELECT  SUM(CD.valor_nocubierto) AS valor_nocubierto, ";
      $sql .= "                 SUM(CD.valor_cubierto) AS valor_cubierto, ";
      $sql .= "                 SUM(CD.valor_cargo) AS valor_cargo, ";
      $sql .= "                 CA.codigo_agrupamiento_id, ";
      $sql .= "                 CA.descripcion,";
      $sql .= "                 DE.por_descuento_empresa, ";
      $sql .= "                 DE.por_descuento_paciente ";
      $sql .= "         FROM    cuentas_detalle CD LEFT JOIN ";
      $sql .= "                 cuentas_descuentos_grupos DE ";
      $sql .= "                 ON( CD.numerodecuenta = DE.numerodecuenta AND ";
      $sql .= "                     CD.codigo_agrupamiento_id = DE.codigo_agrupamiento_id ), ";
      $sql .= "                 cuentas_codigos_agrupamiento CA ";
      $sql .= "         WHERE   CD.numerodecuenta = ".$cuenta." ";
      $sql .= "         AND     CD.codigo_agrupamiento_id = CA.codigo_agrupamiento_id ";
      $sql .= "         AND     CD.facturado = '1' ";
      $sql .= "         AND     CD.consecutivo IS NULL ";
      $sql .= "         GROUP BY CA.codigo_agrupamiento_id, CA.descripcion, DE.por_descuento_empresa, DE.por_descuento_paciente ";
      $sql .= "         UNION ALL ";
      $sql .= "         SELECT  SUM(CD.valor_nocubierto) AS valor_nocubierto, ";
      $sql .= "                 SUM(CD.valor_cubierto) AS valor_cubierto, ";
      $sql .= "                 SUM(CD.valor_cargo) AS valor_cargo, ";
      $sql .= "                 0 AS codigo_agrupamiento_id, ";
      $sql .= "                 'DESCARGO DE MEDICAMENTOS' AS descripcion,";
      $sql .= "                 DE.por_descuento_empresa, ";
      $sql .= "                 DE.por_descuento_paciente ";
      $sql .= "         FROM    cuentas_detalle CD LEFT JOIN ";
      $sql .= "                 cuentas_descuentos_grupos DE ";
      $sql .= "                 ON( CD.numerodecuenta = DE.numerodecuenta AND ";
      $sql .= "                     CD.codigo_agrupamiento_id = DE.codigo_agrupamiento_id ), ";
      $sql .= "                 cuentas_codigos_agrupamiento CA ";
      $sql .= "         WHERE   CD.numerodecuenta = ".$cuenta." ";
      $sql .= "         AND     CD.codigo_agrupamiento_id = CA.codigo_agrupamiento_id ";
      $sql .= "         AND     CD.facturado = '1' ";
      $sql .= "         AND     CD.consecutivo IS NOT NULL ";
      $sql .= "         GROUP BY 4, 5, DE.por_descuento_empresa, DE.por_descuento_paciente ";
      $sql .= "       ) A ";
      //$sql .= "ORDER BY codigo_agrupamiento_id ";
      
      $cxn = new ConexionBD();
      //$cxn->debug = true;
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL OBTENER DETALLE DE LA CUENTA";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $datos = array();
      
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($toUpper=false);
        $rst->MoveNext();
      }
      return $datos; 
    }
    /**
    * Metodo donde se obtiene el detalle de los cargos a los cuales se les 
    * ha aplicado un descuento
    *
    * @param integer $cuenta Identificador de la cuenta
    * @param string $sw_tipo Indique que grupo se va a reliquidar 0->Todos(por defecto) 1-> cargos, 2->insumos y medicamentos
    *
    * @return boolean
    */
    function ObtenerCuentaDetalle($cuenta,$sw_tipo)
    {   
      $sql  = "SELECT DISTINCT CU.plan_id, ";
      $sql .= "       CA.codigo_agrupamiento_id, ";
      $sql .= "       CD.transaccion,";
      $sql .= "       CD.tarifario_id,";
      $sql .= "       CD.cargo,";
      $sql .= "       CD.cantidad,";
      $sql .= "       CD.departamento_al_cargar,";
      $sql .= "       CD.consecutivo,";
      $sql .= "       CD.sw_liq_manual,";
      $sql .= "       CD.facturado,";
      $sql .= "       DE.por_descuento_empresa, ";
      $sql .= "       DE.por_descuento_paciente, ";
      $sql .= "       DP.servicio, ";
      $sql .= "       BD.codigo_producto, ";
      $sql .= "       HD.evolucion_id ";
      $sql .= "FROM   cuentas CU, ";
      $sql .= "       cuentas_detalle CD ";
      $sql .= "       LEFT JOIN bodegas_documentos_d BD ";
      $sql .= "       ON(CD.consecutivo = BD.consecutivo ) ";
      $sql .= "       LEFT JOIN cuentas_descuentos_grupos DE ";
      $sql .= "       ON( CD.numerodecuenta = DE.numerodecuenta AND ";
      $sql .= "           CD.codigo_agrupamiento_id = DE.codigo_agrupamiento_id), ";
      $sql .= "       cuentas_codigos_agrupamiento CA ";
      $sql .= "       LEFT JOIN hc_solicitudes_medicamentos HS ";
      $sql .= "       ON( CA.numeracion = HS.numeracion AND ";
      $sql .= "           CA.bodegas_doc_id = HS.bodegas_doc_id) ";
      $sql .= "       LEFT JOIN hc_solicitudes_medicamentos_d HD ";
      $sql .= "       ON( HS.solicitud_id = HD.solicitud_id), ";
      $sql .= "       departamentos DP ";
      $sql .= "WHERE  CU.numerodecuenta = ".$cuenta." ";
      $sql .= "AND    CU.numerodecuenta = CD.numerodecuenta ";
      $sql .= "AND    CD.numerodecuenta = ".$cuenta." ";
      $sql .= "AND    CD.codigo_agrupamiento_id = CA.codigo_agrupamiento_id ";
      $sql .= "AND    CD.facturado = '1' ";
      $sql .= "AND    CA.cuenta_liquidacion_qx_id IS NULL ";
      $sql .= "AND    CD.departamento = DP.departamento ";
      if($sw_tipo == 1)
        $sql .= "AND    CD.consecutivo IS NULL ";
      else if($sw_tipo == 2)
        $sql .= "AND    CD.consecutivo IS NOT NULL ";
      

      $cxn = new ConexionBD();
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "ERROR AL OBTENER CUENTAS DEL INGRESO";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      $datos = array();
      
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($toUpper=false);
        $rst->MoveNext();
      }
      return $datos; 
    }
    /**
    * Metodo donde se realiza el ingreso de los descuentos de la cuenta
    *
    * @param array $form arreglo de datos de la forma
    *
    * @return boolean
    */
    function IngresarDescuentos($form)
    {
      $cxn = new ConexionBD();
      $cxn->ConexionTransaccion();
      
      $sql  = "DELETE FROM cuentas_descuentos_grupos ";
      $sql .= "WHERE  numerodecuenta = ".$form['numerodecuenta']." ";
      
      if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        $this->error = "ERROR AL INGRESAR DESCUENTOS";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      
      foreach($form['grupo'] as $key => $dtl)
      {
        if($form['descuento_paciente'][$key] == "")
          $form['descuento_paciente'][$key] = 0;
        
        if($form['descuento_empresa'][$key] == "")
          $form['descuento_empresa'][$key] = 0;
        
        $sql  = "INSERT INTO cuentas_descuentos_grupos";
        $sql .= "   ( ";
        $sql .= "     numerodecuenta, ";
        $sql .= "     codigo_agrupamiento_id, ";
        $sql .= "     por_descuento_empresa , ";
        $sql .= "     por_descuento_paciente ";
        $sql .= "   ) ";        
        
        if($key == 0)
        {
          $sql .= "SELECT DISTINCT CD.numerodecuenta, ";
          $sql .= "       CD.codigo_agrupamiento_id, ";
          $sql .= "       ".$form['descuento_empresa'][$key]." AS por_descuento_empresa, ";
          $sql .= "       ".$form['descuento_paciente'][$key]." AS por_descuento_paciente ";
          $sql .= "FROM   cuentas_detalle CD, ";
          $sql .= "       cuentas_codigos_agrupamiento CA ";
          $sql .= "WHERE  CD.numerodecuenta = ".$form['numerodecuenta']." ";
          $sql .= "AND    CD.codigo_agrupamiento_id = CA.codigo_agrupamiento_id ";
          $sql .= "AND    CD.facturado = '1' ";
          $sql .= "AND    CD.consecutivo IS NOT NULL ";
        }
        else
        {
          $sql .= "VALUES";
          $sql .= "   (";
          $sql .= "     ".$form['numerodecuenta'].",";
          $sql .= "     ".$key.",";
          $sql .= "     ".$form['descuento_empresa'][$key].",";
          $sql .= "     ".$form['descuento_paciente'][$key]." ";
          $sql .= "   )";
        }
        
        if(!$rst = $cxn->ConexionTransaccion($sql))
        {
          $this->error = "ERROR AL INGRESAR DESCUENTOS";
          $this->mensajeDeError = $cxn->mensajeDeError;
          return false;
        }
      }
      
      $cxn->Commit();
      $rst = $this->ReliquidarDetalleCuenta($form['numerodecuenta'],$form['empresa_id']);
      if(!$rst) return false;
      
      return true;
    }
    /**
    * Metodo donde se realiza la reliquidacion de una cuenta
    * una vez se han aplicado los descuentos
    *
    * @param integer $cuenta identificador de la cuenta
    * @param string $empresa Identificador de la empresa
    * @param string $sw_tipo Indique que grupo se va a reliquidar 0->Todos(por defecto) 1-> cargos, 2->insumos y medicamentos
    *
    * @return boolean
    */
    function ReliquidarDetalleCuenta($cuenta,$empresa,$sw_tipo = 0)
    {
			IncludeLib("tarifario_cargos");
			$detalle = $this->ObtenerCuentaDetalle($cuenta,$sw_tipo);
      
      $this->cnt = 0;
      $cxn = new ConexionBD();
      $cxn->ConexionTransaccion();
      
      $x = 0;
			foreach($detalle as $key => $dtl)
			{
        if($x%100 == 0) echo ".";
				$sql = "";
        if(!$dtl['consecutivo'])
        {          
          if($dtl['sw_liq_manual'] == 0)
          {
            $Liq = LiquidarCargoCuenta($cuenta,$dtl['tarifario_id'],$dtl['cargo'],$dtl['cantidad'],$dtl['por_descuento_empresa'],$dtl['por_descuento_paciente'],true,true,0,$dtl['plan_id'],$dtl['servicio'],'','','','','','',$dtl['facturado']);

            if(!empty($Liq))
            {
              $sql  = "UPDATE cuentas_detalle ";
              $sql .= "SET		precio = ".$Liq['precio_plan'].",";
              $sql .= "				valor_cargo = ".$Liq['valor_cargo'].",";
              $sql .= "				valor_nocubierto = ".$Liq['valor_no_cubierto'].",";
              $sql .= "				valor_cubierto = ".$Liq['valor_cubierto'].",";
              $sql .= "				valor_descuento_empresa = ".$Liq['valor_descuento_empresa'].",";
              $sql .= "				valor_descuento_paciente = ".$Liq['valor_descuento_paciente'].",";
              $sql .= "				porcentaje_gravamen = ".$Liq['porcentaje_gravamen'].",";
              $sql .= "				sw_cuota_paciente = ".$Liq['sw_cuota_paciente'].",";
              $sql .= "				sw_cuota_moderadora = ".$Liq['sw_cuota_moderadora'].",";
              $sql .= "				facturado = '".$Liq['facturado']."' ";
              $sql .= "WHERE  numerodecuenta = ".$cuenta." ";
              $sql .= "AND    cargo = '".$dtl['cargo']."' ";
              $sql .= "AND    tarifario_id = '".$dtl['tarifario_id']."' ";
              $sql .= "AND    transaccion = ".$dtl['transaccion']." ";
            }
				  }
			  }
        else
        {
          if($dtl['sw_liq_manual'] == 0)
          {
            $Liq = LiquidarIyM($cuenta,$dtl['codigo_producto'] ,$dtl['cantidad'] ,$dtl['por_descuento_empresa'],$dtl['por_descuento_paciente'] ,true ,true ,NULL ,$dtl['plan_id'],false,$dtl['departamento_al_cargar'],$empresa,$dtl['evolucion_id']);

            $Liq['valor_cargo'] = $Liq['valor_cargo']*(($dtl['cargo'] == 'DIMD')? -1:1);
            $Liq['valor_nocubierto'] = $Liq['valor_nocubierto']*(($dtl['cargo'] == 'DIMD')? -1:1);
            $Liq['valor_cubierto'] = $Liq['valor_cubierto']*(($dtl['cargo'] == 'DIMD')? -1:1);

            $sql  = "UPDATE cuentas_detalle ";
            $sql .= "SET		precio = ".$Liq['precio_plan'].",";
            $sql .= "				valor_cargo = ".$Liq['valor_cargo'].",";
            $sql .= "				valor_nocubierto = ".$Liq['valor_nocubierto'].",";
            $sql .= "				valor_cubierto = ".$Liq['valor_cubierto'].",";
            $sql .= "				valor_descuento_empresa = ".$Liq['valor_descuento_empresa'].",";
            $sql .= "				valor_descuento_paciente = ".$Liq['valor_descuento_paciente'].",";
            $sql .= "				porcentaje_gravamen = ".$Liq['porcentaje_gravamen'].",";
            $sql .= "				sw_cuota_paciente = ".$Liq['sw_cuota_paciente'].",";
            $sql .= "				sw_cuota_moderadora = ".$Liq['sw_cuota_moderadora'].",";
            $sql .= "				facturado = '".$Liq['facturado']."' ";
            $sql .= "WHERE  numerodecuenta = ".$cuenta." ";
            $sql .= "AND    consecutivo = ".$dtl['consecutivo']." ";
            $sql .= "AND    transaccion = ".$dtl['transaccion']." ";
          }
			  }
        if($sql != "")
        {
          if(!$rst = $cxn->ConexionTransaccion($sql))
          {
            $this->error = "ERROR AL OBTENER CUENTAS DEL INGRESO";
            $this->mensajeDeError = $cxn->mensajeDeError;
            return false;
          }
          $this->cnt++;
        }
        $x++;
			}
      
      $cxn->Commit();
      return true;
		}
	}
?>