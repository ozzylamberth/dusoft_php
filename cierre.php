<?php
//****************************************************************
//PROGRAMA PARA REALIZAR LE CIERRE DE CAJA RAPIDA -- CONSULTA EXTERNA
//****************************************************************
    $VISTA='HTML';
    include 'includes/enviroment.inc.php';

    $PermisosDBconn = ADONewConnection($ConfigDB['dbtype']);


   if (!($PermisosDBconn->Connect($ConfigDB['dbhost'], base64_decode($ConfigDB['dbuserAdmin']), base64_decode($ConfigDB['dbpassAdmin']),$ConfigDB['dbname']))) {
        die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$PermisosDBconn->ErrorMsg()));
    }
 
        echo "<center><h1>CONECTADO A LA BASE DE DATOS </h1></center><BR>";
        echo "<center><h1><b>$ConfigDB[dbhost]/$ConfigDB[dbname]</b></h1></center><BR><BR>";

//*******************************************************************
		$PermisosDBconn->BeginTrans();
		$busca="select nextval('cierre_caja_seq')";
		$resulta=$PermisosDBconn->Execute($busca);
		if ($PermisosDBconn->ErrorNo() != 0) {
		echo "Error DB : " . $PermisosDBconn->ErrorMsg();
		return false;
		}
		
		$serial=$resulta->fields[0];
		 $query="SELECT DISTINCT a.cierre_caja_id 
						FROM fac_facturas_contado a, cajas_rapidas b,
									userpermisos_cajas_rapidas c
						WHERE a.caja_id=1
									AND a.empresa_id='01'
									AND a.centro_utilidad='01'
									AND a.caja_id=b.caja_id
									AND c.caja_id=b.caja_id
									AND c.usuario_id=a.usuario_id
									AND a.cierre_caja_id IS NOT NULL
									AND a.cierre_caja_id NOT IN (
											SELECT e.cierre_caja_id
											FROM cierre_de_caja d, cierre_de_caja_detalle e
											WHERE d.cierre_de_caja_id=e.cierre_de_caja_id);"; 
			$result1=$PermisosDBconn->Execute($query);
			while(!$result1->EOF)
			{
							$var[]=$result1->GetRowAssoc($ToUpper = false);
							$result1->MoveNext();
			}
		 $query="SELECT SUM(a.total_efectivo) AS total_efectivo,SUM(a.total_cheques) AS total_cheques,SUM(a.total_tarjetas) AS total_tarjetas,
									SUM(a.total_bonos) AS total_bonos
						FROM fac_facturas_contado a, cajas_rapidas b,
									userpermisos_cajas_rapidas c
						WHERE a.caja_id=1
									AND a.empresa_id='01'
									AND a.centro_utilidad='01'
									AND a.caja_id=b.caja_id
									AND c.caja_id=b.caja_id
									AND c.usuario_id=a.usuario_id
									AND a.cierre_caja_id IS NOT NULL
									AND a.cierre_caja_id NOT IN (
											SELECT e.cierre_caja_id
											FROM cierre_de_caja d, cierre_de_caja_detalle e
											WHERE d.cierre_de_caja_id=e.cierre_de_caja_id);";
			$result=$PermisosDBconn->Execute($query);
$entrega= $result->fields[0]+$result->fields[1]+$result->fields[2]+$result->fields[3];

		$query="INSERT INTO cierre_de_caja
						(caja_id,
							cierre_de_caja_id,
							centro_utilidad,
							empresa_id,
							usuario_id,
							usuario_recibio,
							total_efectivo,
							total_cheques,
							total_tarjetas,
							total_bonos,
							total_devolucion,
							entrega_efectivo,
							fecha_registro,
							observaciones
						)
						VALUES
						(
						1 ,
						$serial,
						'01',
						'01',
						493,
						493,
						".round($result->fields[0]).",
						".round($result->fields[1]).",
						".round($result->fields[2]).",
						".round($result->fields[3]).",
						0,
						".round($entrega).",
							now(),
						'CIERRE CAJA RAPIDA CONSULTA EXTERNA 1'
						)";     
				$PermisosDBconn->Execute($query); 
				if ($PermisosDBconn->ErrorNo() != 0) {
				echo "Error DB : " . $PermisosDBconn->ErrorMsg();
					$PermisosDBconn->RollbackTrans();
				}
				
			$PermisosDBconn->CommitTrans();
  //print_r($var); exit;
	//echo  sizeof($var); exit;
			$PermisosDBconn->BeginTrans();
					if (sizeof($var)>0)
					{
						for($i=0;$i<sizeof($var);$i++)
						{
								echo	$query="INSERT INTO cierre_de_caja_detalle
													(
														cierre_de_caja_id,
														cierre_caja_id
													)
													VALUES
													(
														$serial,
														".$var[$i][cierre_caja_id]."
													)"; 
									$PermisosDBconn->Execute($query);
									if ($PermisosDBconn->ErrorNo() != 0) {
										echo "Error DB : " . $PermisosDBconn->ErrorMsg();
										$PermisosDBconn->RollbackTrans();
									}
						}
					}
			$PermisosDBconn->CommitTrans();
//******************************************************************** 
      echo "<br>EL CIERRE DE LA CAJA RAPIDA CONSULTA EXTERNA 1 SE HA REALIZADO!!"; 
   
?>    