<?
//-- NO ENVIADO 
	$VISTA='HTML';
  include 'includes/enviroment.inc.php';
	//$_ROOT = 'SIIS/';
	//include $_ROOT . 'includes/enviroment.inc.php';
echo '<pre>';
	list($dbconn) = GetDBconn();

  //$dbconn = ADONewConnection($ConfigDB['dbtype']);

  //if (!($dbconn->Connect($ConfigDB['dbhost'], base64_decode($ConfigDB['dbuser']), base64_decode($ConfigDB['dbpass']),$ConfigDB['dbname']))) {
   //    die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
   // }
		//ALTERACIONES DE LA TABLA hc_odontogramas_primera_vez_presupuesto
//***************************************************************
//***************************************************************
//***************************************************************
// PARA LOS RIPS
		$query = "SELECT CD.transaccion, 
								CD.cargo_cups,
								I.tipo_id_paciente,
								I.paciente_id,
								HCA.autorizacion_int,
								HCA.autorizacion_ext

							FROM	cuentas C,
										ingresos I,
										cuentas_detalle CD,
										fac_facturas_cuentas FFC,
										envios_detalle ED,
										hc_os_solicitudes_manuales HCSM,
										hc_os_autorizaciones HCA
							WHERE ED.envio_id=5330
							AND FFC.prefijo=ED.prefijo
							AND FFC.factura_fiscal=ED.factura_fiscal
							AND CD.numerodecuenta=FFC.numerodecuenta
							AND CD.numerodecuenta=C.numerodecuenta
							AND C.ingreso=I.ingreso
							AND I.tipo_id_paciente = HCSM.tipo_id_paciente
							AND I.paciente_id = HCSM.paciente_id
							AND CD.cargo_cups IS NOT NULL
							AND HCSM.hc_os_solicitud_id=HCA.hc_os_solicitud_id;";
		$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				echo " ERROR AL SELECCIONAR AUTORIZACION: " . $dbconn->ErrorMsg()."<BR>";
			}
			while(!$resulta->EOF)
			{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		$query = "SELECT CD.transaccion

							FROM cuentas_detalle CD,
										fac_facturas_cuentas FFC,
										envios_detalle ED
							WHERE ED.envio_id=5330
							AND FFC.prefijo=ED.prefijo
							AND FFC.factura_fiscal=ED.factura_fiscal
							AND CD.numerodecuenta=FFC.numerodecuenta
							AND CD.cargo_cups IS NOT NULL;";
		$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				echo " ERROR AL SELECCIONAR transaccion: " . $dbconn->ErrorMsg()."<BR>";
			}
			while(!$resulta->EOF)
			{
				$var2[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
print_r($var);
echo '<br>';
print_r($var2);
		//*****************************
				for($i=0;$i<sizeof($var);$i++)
				{
					for($j=0;$j<sizeof($var2);$j++)
					{
						if($var[$i][transaccion]==$var2[$j][transaccion])
							{
								$query1= "UPDATE cuentas_detalle
													SET autorizacion_int=".$var[$i][autorizacion_int].", autorizacion_ext=".$var[$i][autorizacion_ext]."
													WHERE transaccion=".$var[$i][transaccion].";";
								$resulta=$dbconn->Execute($query1);
								if ($dbconn->ErrorNo() != 0) {
									echo " ERROR UPDATE autorizacion int- ext en cuentas_detalle: " . $dbconn->ErrorMsg()."<BR>";
								}
							}
					}
echo '<br>';

				}
//HASTA AQUI PARA LOS RIPS
//***********************************************************************
//SI ES PARA LAS IMPRESIONES DE ENVIOS
		$query = "SELECT
								HCA.autorizacion_int,
								HCA.autorizacion_ext,
								C.ingreso
							FROM	cuentas C,
										ingresos I,
										cuentas_detalle CD,
										fac_facturas_cuentas FFC,
										envios_detalle ED,
										hc_os_solicitudes_manuales HCSM,
										hc_os_autorizaciones HCA
							WHERE ED.envio_id=5330
							AND FFC.prefijo=ED.prefijo
							AND FFC.factura_fiscal=ED.factura_fiscal
							AND CD.numerodecuenta=FFC.numerodecuenta
							AND CD.numerodecuenta=C.numerodecuenta
							AND C.ingreso=I.ingreso
							AND I.tipo_id_paciente = HCSM.tipo_id_paciente
							AND I.paciente_id = HCSM.paciente_id
							AND CD.cargo_cups IS NOT NULL
							AND HCSM.hc_os_solicitud_id=HCA.hc_os_solicitud_id;";
		$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				echo " ERROR AL SELECCIONAR AUTORIZACION_escritas: " . $dbconn->ErrorMsg()."<BR>";
			}
			while(!$resulta->EOF)
			{
				$var1[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}

			for($i=0;$i<sizeof($var1);$i++)
			{
						$query1= "UPDATE autorizaciones
											SET ingreso = ".$var1[$i][ingreso]."
											WHERE autorizacion = ".$var1[$i][autorizacion_int]." AND ingreso IS NULL;";
						$resulta=$dbconn->Execute($query1);
						if ($dbconn->ErrorNo() != 0) {
							echo " ERROR UPDATE autorizacion: " . $dbconn->ErrorMsg()."<BR>";
						}
			}
//HASTA AQUI PARA LOS ENVIOS
echo "<center>TERMINADO</center>";
	
?>



		




