<?php



if (!session_id()){
  session_start();
}


require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

		

?>
<html>
<head>
<script>
function abrirpopup(nombre,ancho,alto) {
 Xpos=(screen.width/2)-300;
 Ypos=(screen.height/2)-200; 

dat = 'width=' + ancho + ',height=' + alto + ',left='+Xpos+',top = '+Ypos+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,titlebar=no';
window.open(nombre,'',dat)

function Verificar()
{
if(document.buscarcuenta.numerodecuenta.value==""){alert("Por favor indica el Nombre de la Empresa");
document.buscarcuenta.numerodecuenta.focus();return false;}
}

}
</script>
<title>RELACION DE CUENTAS SIIS</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
</head>

<body background="imagenes/fondo_bloque.gif">


<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_id = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];


procesar_entrada("GET", "id", "action", "action1", "relacion_id", "departamento_entrega", "departamento_recibe", "numerodecuenta","prefijo",
"factura_fiscal","proceso", "cod_menu");


$departamento_entrega = get_value($_GET["departamento_entrega"], "C");
$departamento_recibe = get_value($_GET["departamento_recibe"], "C");
$numerodecuenta = get_value($_GET["numerodecuenta"], "N");
$prefijo = get_value($_GET["prefijo"], "C");
$factura_fiscal = get_value($_GET["factura_fiscal"], "N");
$relacion_id = get_value($_GET["relacion_id"], "C");
$cod_menu = get_value($_GET["cod_menu"], "C");
	


$action = get_value($_GET["action"], "C");
$action1 = get_value($_GET["action1"], "C");
$proceso = get_value($_GET["proceso"], "C");
$mensaje_insert = "";

$fecha_registro = date("Y-m-d H:i:s");
$color = "black";

$sess = session_id();

			function fecha_inicial_responsabilidad_cuenta ($numerodecuenta, $dbh){
						
						$row_evoluciones = 0;
						$qry_evoluciones = "SELECT count(c.evolucion_id), b.estado, a.fecha_ingreso, b.sw_corte, b.fecha_registro
								FROM ingresos a LEFT JOIN hc_evoluciones c ON (a.ingreso = c.ingreso), cuentas b 
								WHERE b.numerodecuenta = $numerodecuenta
								AND a.ingreso = b.ingreso
								GROUP BY b.estado, a.fecha_ingreso, b.sw_corte, b.fecha_registro";
	 											
    							$resultado_evoluciones = execute_query($dbh, $qry_evoluciones);
       							$evolucion = pg_fetch_row($resultado_evoluciones);
								free_result($resultado_evoluciones);
			    		
			    			if($evolucion[0] == 0){//Identifica al paciente ambulatorio
			    			 	if($evolucion[1] == "0"){//Comprueba si hay factura
			    			 	 	$qry_fecha_factura = "SELECT a.fecha_registro
										FROM fac_facturas a, fac_facturas_cuentas b
										WHERE b.numerodecuenta = $numerodecuenta
										AND (a.prefijo = b.prefijo AND a.factura_fiscal = b.factura_fiscal)
										AND a.estado IN ('0','1')";
			 											
		    							$resultado_fecha_factura = execute_query($dbh, $qry_fecha_factura);
		       							$fecha = pg_fetch_row($resultado_fecha_factura);
										free_result($resultado_fecha_factura);
									return (substr($fecha[0],0,19)."AFO"."ELAB FACT");
								}
								else{
									return (substr($evolucion[4],0,19)."AFO"."ELAB CTA");
								}
			    			}
							else{//Identifica paciente Hospitalizado

							 	if($evolucion[3] == "0"){//Cuenta sin corte
							 	$qry_fecha_egreso = "SELECT COUNT(c.*), c.fecha_registro
										FROM cuentas a, ingresos b, ingresos_salidas c
										WHERE a.numerodecuenta = $numerodecuenta
										AND b.ingreso = a.ingreso
										AND c.ingreso = b.ingreso
										GROUP BY c.fecha_registro";
			 											
		    							$resultado_fecha_egreso = execute_query($dbh, $qry_fecha_egreso);
		       							$fecha_egreso = pg_fetch_row($resultado_fecha_egreso);
		       								if($fecha_egreso[0]==0){//Paciente Hospitalizado sin salida
												return (null);
											}
											else{//Paciente Hospitalizado con salida
												if($evolucion[1] == "0"){//Comprueba si hay factura
														$qry_fecha_factura = "SELECT a.fecha_registro
														FROM fac_facturas a, fac_facturas_cuentas b
														WHERE b.numerodecuenta = $numerodecuenta
														AND (a.prefijo = b.prefijo AND a.factura_fiscal = b.factura_fiscal)
														AND a.estado IN ('0','1')";
																		
														$resultado_fecha_factura = execute_query($dbh, $qry_fecha_factura);
														$fecha = pg_fetch_row($resultado_fecha_factura);
														free_result($resultado_fecha_factura);
														return (substr($fecha[0],0,19)."AFO"."ELAB FACT");
												}
												else{//Retorna la fecha de egreso del paciente
													return (substr($fecha_egreso[1],0,19)."AFO"."EGRE PAC");
												}
											}
										free_result($resultado_fecha_egreso);
								}
								else{//Cuenta con corte
									if($evolucion[1] == "0"){//Cuenta corte facturada
										$qry_fecha_factura = "SELECT a.fecha_registro
										FROM fac_facturas a, fac_facturas_cuentas b
										WHERE b.numerodecuenta = $numerodecuenta
										AND (a.prefijo = b.prefijo AND a.factura_fiscal = b.factura_fiscal)
										AND (a.prefijo ILIKE '%C' AND a.prefijo NOT ILIKE '%K')
										AND a.estado IN ('0','1')";
			 											
		    							$resultado_fecha_factura = execute_query($dbh, $qry_fecha_factura);
		       							$fecha = pg_fetch_row($resultado_fecha_factura);
										free_result($resultado_fecha_factura);
										return (substr($fecha[0],0,19)."AFO"."ELAB FACT");
									}
									else{//Cuenta corte sin facturar
										return (substr($evolucion[4],0,19)."AFO"."ELAB CTA CORTE");
									}
								}
							}
					}

switch ($action1) {

  case "add":
  
  if($numerodecuenta <> "" OR ($prefijo <> "" AND $factura_fiscal <> "")){
   
   	
					
$query = "SELECT cuentas.numerodecuenta, cuentas.estado as estado_cuentas, cuentas.total_cuenta as t_cuenta, fac_facturas.empresa_id, fac_facturas.prefijo, fac_facturas.factura_fiscal, fac_facturas.estado  as estado_factura, fac_facturas.fecha_registro, fac_facturas.total_factura, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion
 FROM   ((((public.fac_facturas_cuentas fac_facturas_cuentas RIGHT OUTER JOIN public.cuentas cuentas ON fac_facturas_cuentas.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((fac_facturas_cuentas.empresa_id=fac_facturas.empresa_id) AND (fac_facturas_cuentas.prefijo=fac_facturas.prefijo)) AND (fac_facturas_cuentas.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 AND (cuentas.estado NOT IN ('4','5'))";

$where = build_where(	"cuentas.numerodecuenta", $numerodecuenta, "N",
    					"fac_facturas.prefijo", $prefijo, "C",
    					"fac_facturas.factura_fiscal", $factura_fiscal, "N");
    



/*if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;*/

$order = "1";

if ($where) {
    $query .= " WHERE " . $where;
    /*$query_records .= " WHERE " . $where;*/
} 


/*if ($grupo){
	$query .= " GROUP BY " .$grupo;
	/*$query_records .= " GROUP BY " .$grupo;
}*/

/*if ($_GET["orientation"])
    $orientation = $_GET["orientation"];
else
    $orientation = 1;

$query .= " ORDER BY " . $order;

if ($orientation == 1)
    $query = $query . " ASC";
else
    $query = $query . " DESC";*/


/*$result = execute_query($dbh, $query_records);
$row = fetch_object($result);
$num_records = $row->numreg;
free_result($result);*/

$result = execute_query($dbh, $query);

while ($row1 = fetch_object($result)) {

$result_cantidad = pg_query($dbh, "SELECT rel_det_id FROM tmp_relacion_cuentas_detalle 
	 					WHERE numerodecuenta = $row1->numerodecuenta AND usuario_id = $usuario_id");

						$rows = pg_num_rows($result_cantidad);
					

						if($rows > 0){
		
						}

 		else{
 		if($row1->factura_fiscal==""){
			$sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
		 		 . ",numerodecuenta"
				 . ",total_cuenta"
				 . ",usuario_id"
				 . ") VALUES ("
				 . "'" . $sess . "'"
				 . "," . $row1->numerodecuenta . ""
				 . "," . $row1->t_cuenta . ""
				 . "," . $usuario_id
				 . ")";
			execute_query($dbh, $sql);
			
			 
		}
		else{
		 
		 $result_cantidad1 = pg_query($dbh, "SELECT MAX(a.oid), a.prefijo, a.factura_fiscal 
FROM fac_facturas a, fac_facturas_cuentas b
WHERE (a.empresa_id = b.empresa_id AND a.prefijo = b.prefijo AND a.factura_fiscal = b.factura_fiscal) AND b.numerodecuenta = '$row1->numerodecuenta' AND a.prefijo ILIKE '%C' AND a.prefijo NOT ILIKE '%K' AND a.estado IN ('0', '1')
GROUP BY  a.prefijo, a.factura_fiscal");

			$rows1 = pg_num_rows($result_cantidad1);
			
			
			
			
			if($rows1 == 0){
			 
			 if(eregi('K', $row1->prefijo)) {
			  
			  }
			  else{
			 
			 $sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
					 		 		. ",numerodecuenta"
									. ",total_cuenta"
									. ",usuario_id"
							 		. ") VALUES ("
							 		. "'" . $sess . "'"
							 		. "," . $row1->numerodecuenta . ""
							 		. "," . $row1->total_cuenta . ""
							 		. "," . $usuario_id
							 		. ")";
										execute_query($dbh, $sql);
					}					
				
			}
			else{
		 
				if ($row1->estado_factura <>'2' AND $row1->estado_factura <>'3'){
				 
				 		if(eregi('C', $row1->prefijo)) {
				 		 $conteo_agrupado = "SELECT count(*) as nro_agrupado
								FROM fac_facturas_cuentas
								WHERE prefijo = '$row1->prefijo' AND factura_fiscal = $row1->factura_fiscal";
	 											
    							$resultado_agrupado = execute_query($dbh, $conteo_agrupado);
       							$agrupado = pg_fetch_row($resultado_agrupado);
								$nro_agrupado = $agrupado[0];
								free_result($resultado_agrupado);
								if($nro_agrupado > 1){
									
									$total_cuenta = $row1->t_cuenta;
								}
								else{
		 							
		 							$total_cuenta = $row1->total_factura;
		 						}
								$sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
				 		 		. ",numerodecuenta"
				 		 		. ",empresa_id"
				 		 		. ",prefijo"
				 		 		. ",factura_fiscal"
						 		. ",total_cuenta"
						 		. ",usuario_id"
						 		. ") VALUES ("
						 		. "'" . $sess . "'"
						 		. "," . $row1->numerodecuenta . ""
						 		. ",'" . $row1->empresa_id . "'"
						 		. ",'" . $row1->prefijo . "'"
						 		. "," . $row1->factura_fiscal . ""
						 		. "," . $total_cuenta . ""
							 	. "," . $usuario_id
						 		. ")";
						 		
								execute_query($dbh, $sql);
					
									
						}
								
				}
			}
				
		}	
	}
   }
  }

   
  /*foreach($_POST["insertarlinea"] as $idtmp) {
  		
	$linea = explode(":", $idtmp);
	
	$result = pg_query($dbh, "SELECT rel_det_id FROM tmp_relacion_cuentas_detalle 
	 WHERE numerodecuenta = $linea[0] AND session_id = '$sess'");

	$rows = pg_num_rows($result);

	if($rows > 0){
		
	}
	else{
		
	
 		if($linea[3]==""){
			$sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
		 		 . ",numerodecuenta"
				 . ",total_cuenta"
				 . ") VALUES ("
				 . "'" . $sess . "'"
				 . "," . $linea[0] . ""
				 . "," . $linea[4]
				 . ")";
			if (execute_query($dbh, $sql)){
			
			} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			
			}
		}
		else{
				$sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
		 		 . ",numerodecuenta"
		 		 . ",empresa_id"
		 		 . ",prefijo"
		 		 . ",factura_fiscal"
				 . ",total_cuenta"
				 . ") VALUES ("
				 . "'" . $sess . "'"
				 . "," . $linea[0] . ""
				 . ",'" . $linea[1] . "'"
				 . ",'" . $linea[2] . "'"
				 . "," . $linea[3] . ""
				 . "," . $linea[4]
				 . ")";
			if (execute_query($dbh, $sql)){
			
			} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			
			}
			
		}
  
	}	
  }*/
  break;
  
case "delete":

 foreach($_POST["borrarlinea"] as $idtemp) {
 
  	$sql = "DELETE FROM tmp_relacion_cuentas_detalle WHERE rel_det_id = $idtemp";
	execute_query($dbh, $sql);
  }
  break;
  
case "grabar":

	$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "insert", $dbh);
		if($permiso == ""){
			$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
			break;
		}

$numero_control = 0;

$message = "
<html>
<body>
<center><table align=CENTER% width=80% border=0 cellspacing=0>
<tr> 
      <td  background=imagenes/cellpic1.gif  bgcolor=#EEEEEE><div align=center><strong><font color=#003366 size=2>Cuentas que no pueden ser relacionadas:</font></strong></div></td>
</tr><br><br>";



$pre_insert = execute_query($dbh, "SELECT a.*
 FROM   tmp_relacion_cuentas_detalle a
 WHERE  a.usuario_id=$usuario_id
 ORDER BY a.rel_det_id");
 $rows = pg_num_rows($pre_insert);
 if($rows > 0){
  
while ($p_insert = fetch_object($pre_insert)) {
 
/*echo $dbh;*/
 	$fecha_inicial = fecha_inicial_responsabilidad_cuenta($p_insert->numerodecuenta,$dbh);

 /*echo 'Fecha Inicial         ',$fecha_inicial,'  ';	*/

 	$query = "Select a.rel_det_id, a.relacion_id, a.estado, b.fecha_recibe FROM relacion_cuentas_detalle a, relacion_cuentas b WHERE a.rel_det_id = (Select max(rel_det_id) from relacion_cuentas_detalle WHERE numerodecuenta = $p_insert->numerodecuenta
	 AND estado NOT IN ('N')) AND a.relacion_id = b.relacion_id";
    $result = execute_query($dbh, $query);
    
    $comprobar = pg_fetch_row($result);

    $maximo = $comprobar[0];
    
    $relacion_id_vieja = $comprobar[1];
    $estado = $comprobar[2];
	free_result($result);
	
	$query1 = "SELECT departamento_entrega, departamento_recibe FROM relacion_cuentas 
	 WHERE relacion_id = $relacion_id_vieja";
    $result1 = execute_query($dbh, $query1);
    
    $comprobar1 = pg_fetch_row($result1);
    
    $departamento_o = $comprobar1[0];
    $departamento_d = $comprobar1[1];
    
    free_result($result1);
    
/*echo $estado,' - ';
echo $departamento_d,' - ';
echo $departamento_entrega,' - ';
echo $departamento_o;*/

    	if($estado == 'E' AND $departamento_d <> $departamento_entrega){
    	 
    	 	$consulta1 = "SELECT descripcion FROM departamentos 
	 		WHERE departamento = '$departamento_d'";
    		$resultado1 = execute_query($dbh, $consulta1);
       		$dpto1 = pg_fetch_row($resultado1);
       		$departamento = $dpto1[0];
      		free_result($resultado1);
    	 
    	 	$numero_control = $numero_control + 1;
    	 	
    	 	$message .= 
			 "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." ya fue recibida por el departamento de ".$departamento."</strong></font></td></tr>";
      
    	 	
    	 	}
    	 	
    	 	/*else if($estado == 'N' AND $departamento_o <> $departamento_entrega){
    	 	 	$consulta2 = "SELECT descripcion FROM departamentos 
	 			WHERE departamento = '$departamento_o'";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$dpto2 = pg_fetch_row($resultado2);
       			$departamento = $dpto2[0];
      			free_result($resultado2);
					
				$numero_control = $numero_control + 1;
				$message .= 
				"<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." solo puede ser relacionada 
				por el departamento de ".$departamento."</strong></font></td></tr>";
				
				
					}*/

					else if($estado == 'R'){
					$numero_control = $numero_control + 1;
					
					
					
					$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." ya se encuentra relacionada"."</strong></font></td></tr>";
					}
				
		
		else if ($fecha_inicial == null){
			$numero_control = $numero_control + 1;
			
//echo 'line 509';
			$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." no tiene orden de salida"."</strong></font></td></tr>";
		}
		else{
			
		}		
		
 
 }
 
		if($numero_control > 0){
		echo $message;	
		}
		else{
			
$val = execute_query($dbh, "SELECT nextval('relacion_cuenta_relacion_id_seq')");
$row = fetch_object($val);

$estado_relacion = 'R';
$sql_insertar_relacion = "INSERT INTO relacion_cuentas(relacion_id"
		 . ",fecha_registro"	
		 . ",usuario_entrega"
		 . ",estado"
		 . ",departamento_entrega"
		 . ",departamento_recibe"
		 . ") VALUES ("
		 . "" . $row->nextval . ""
		 . ",'" . $fecha_registro . "'"
		 . "," . $usuario_id . ""
		 . ",'" . $estado_relacion . "'"
		 . ",'" . $departamento_entrega . "'"
		 . ",'" . $departamento_recibe
		 . "')";
		 
		if (execute_query($dbh, $sql_insertar_relacion)){
			
				$estado = 'R';
				$result3 = execute_query($dbh, "SELECT cuentas.numerodecuenta, fac_facturas.empresa_id, fac_facturas.prefijo, fac_facturas.factura_fiscal, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.session_id, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.total_cuenta, tmp_relacion_cuentas_detalle.observacion
 FROM   ((((public.tmp_relacion_cuentas_detalle tmp_relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON tmp_relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((tmp_relacion_cuentas_detalle.empresa_id=fac_facturas.empresa_id) AND (tmp_relacion_cuentas_detalle.prefijo=fac_facturas.prefijo)) AND (tmp_relacion_cuentas_detalle.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  tmp_relacion_cuentas_detalle.usuario_id=$usuario_id
 ORDER BY tmp_relacion_cuentas_detalle.rel_det_id DESC");
 	$fecha_recibe = null;											
 	while ($row3 = fetch_object($result3)) {
 	$fecha_inicial = fecha_inicial_responsabilidad_cuenta($row3->numerodecuenta,$dbh);
 	
 	$query = "Select a.rel_det_id, a.relacion_id, a.estado, b.fecha_recibe FROM relacion_cuentas_detalle a, relacion_cuentas b WHERE a.rel_det_id = (Select max(rel_det_id) from relacion_cuentas_detalle WHERE numerodecuenta = $row3->numerodecuenta) AND a.relacion_id = b.relacion_id";
    $result = execute_query($dbh, $query);
    
    $comprobar = pg_fetch_row($result);
    $fecha_recibe = $comprobar[3];
	free_result($result);
	
	if($fecha_recibe == null){
				$inf_fecha_inicial = explode("AFO", $fecha_inicial);
				$fecha_inicial = $inf_fecha_inicial[0];
				$obs_fecha_inicial = $inf_fecha_inicial[1];
			}
			else{
				$fecha_inicial = $fecha_recibe;
				$obs_fecha_inicial = "RECIBE";
			
			}
 												 
 												 
 												 	if(isset ($row3->factura_fiscal) ==""){
														$sql1 = "INSERT INTO relacion_cuentas_detalle(relacion_id"
		 															. ",numerodecuenta"
		 															. ",estado"
		 															. ",total_cuenta"
		 															. ",observacion"
		 															. ",fecha_inicial"
																	. ",obs_fecha_inicial"
		 															. ") VALUES ("
		 															. "" . $row->nextval . ""
		 															. "," . $row3->numerodecuenta . ""
																	. ",'" . $estado . "'"
																	. "," . $row3->total_cuenta . ""
																	. ",'" . $row3->observacion . "'"
																	. ",'" . $fecha_inicial . "'"
																	. ",'" . $obs_fecha_inicial
																	. "')";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_cuentas_detalle 	WHERE rel_det_id = $row3->rel_det_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													else{
														$sql1 = "INSERT INTO relacion_cuentas_detalle(relacion_id"
		 															. ",numerodecuenta"
		 															. ",estado"
		 															. ",total_cuenta"
		 															. ",empresa_id"
		 															. ",prefijo"
		 															. ",factura_fiscal"
		 															. ",observacion"
		 															. ",fecha_inicial"
																	. ",obs_fecha_inicial"
		 															. ") VALUES ("
		 															. "" . $row->nextval . ""
		 															. "," . $row3->numerodecuenta . ""
																	. ",'" . $estado . "'"
																	. "," . $row3->total_cuenta . ""
																	. ",'" . $row3->empresa_id . "'"
																	. ",'" . $row3->prefijo . "'"
																	. "," . $row3->factura_fiscal . ""
																	. ",'" . $row3->observacion . "'"
																	. ",'" . $fecha_inicial . "'"
																	. ",'" . $obs_fecha_inicial
																	. "')";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_cuentas_detalle 	WHERE rel_det_id = $row3->rel_det_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													  
													  
 												 
 												 }
		} else {
            $mensaje = "ERROR: No se pudo ejecutar el evento.";
			
		}
		$departamento_recibe = '';
		$departamento_entrega = '';
		$proceso = 'grabar';
		
				$consulta2 = "SELECT a.relacion_id, a.fecha_registro, b.nombre
 				FROM   relacion_cuentas a, system_usuarios b
 				WHERE  a.relacion_id = $row->nextval
 				AND a.usuario_entrega = b.usuario_id
 				ORDER BY a.relacion_id";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			
      			free_result($resultado2);
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr> 
      <td  background=imagenes/cellpic1.gif colspan=5 bgcolor=#EEEEEE><div align=left><strong><font color=#003366 size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>";
    
    	$mensaje_insert .= "<tr> 
    <td width=8% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Cuenta</font></strong></a> 
   </td>
    <td width=8% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Factura Fiscal</font></strong></a> 
   </td>
    <td width=12% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Valor</font></strong></a> 
   </td>
    <td width=33% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=33% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Plan</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.rel_det_id
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$row_c[0]
ORDER BY relacion_cuentas_detalle.rel_det_id");
			$total_relacion = 0;
		while ($row_d = fetch_object($relacion_detalle)) {
		 $total_relacion = $total_relacion +  $row_d->total_cuenta;
		 $mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->numerodecuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->prefijo"." "."$row_d->factura_fiscal</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$". number_format ($row_d->total_cuenta)."</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->tipo_id_paciente"." "."$row_d->paciente_id"." "."$row_d->primer_nombre"." "."$row_d->segundo_nombre"." "."$row_d->primer_apellido"." "."$row_d->segundo_apellido</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->plan_descripcion</font></td>
	</tr>";
		 
		 }
		 
	$mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td colspan=2><div align=center class=Estilo1><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color><u>Total Relacion:</u></font></div></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color><strong>$". number_format ($total_relacion)."</strong></font></td>
	<td colspan=3><strong><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color></font></strong></td>
	</tr>
	<tr bgcolor=#EEEEEE>
	<td colspan=3><a href=imprimir_relacion_tercero.php?relacion_id=$row->nextval target=_blank>Imprimir Relacion</a>
	</td>
	<td colspan=2><a href=xls_relacion.php?relacion_id=$row->nextval>Exportar a Excel</a>
	</td>
	
	</tr></table><br>";
	
	/*$mensaje_insert .= $imprimir;*/
	
			$departamento_recibe = '';
			$departamento_entrega = '';
			$proceso = 'grabar';
			$relacion_id = "";
  
		}
	}
	else {
		$mensaje_insert = "ERROR: No se pudo ejecutar el evento.";
	}
	break;
	
	case "actualizar":
	$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "update", $dbh);
		if($permiso == ""){
			$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
			break;
		}
		$numero_control = 0;

$message = "
<html>
<body>
<center><table align=CENTER% width=80% border=0 cellspacing=0>
<tr> 
      <td  background=imagenes/cellpic1.gif  bgcolor=#EEEEEE><div align=center><strong><font color=#003366 size=2>Cuentas que no pueden ser relacionadas:</font></strong></div></td>
</tr><br><br>";



$pre_insert = execute_query($dbh, "SELECT a.*
 FROM   tmp_relacion_cuentas_detalle a
 WHERE  a.usuario_id=$usuario_id
 ORDER BY a.rel_det_id");
 $rows = pg_num_rows($pre_insert);
 if($rows > 0){
	 
while ($p_insert = fetch_object($pre_insert)) {
 
 	
 	$fecha_inicial = fecha_inicial_responsabilidad_cuenta($p_insert->numerodecuenta,$dbh);
 	
 	$query = "Select rel_det_id, relacion_id, estado FROM relacion_cuentas_detalle WHERE rel_det_id = (Select max(rel_det_id) from relacion_cuentas_detalle WHERE numerodecuenta = $p_insert->numerodecuenta AND relacion_id <> $relacion_id)";
    $result = execute_query($dbh, $query);
    
    $comprobar = pg_fetch_row($result);

    $maximo = $comprobar[0];
    
    $relacion_id_vieja = $comprobar[1];
    $estado = $comprobar[2];
    
    	
	
	free_result($result);
	
	$query1 = "SELECT departamento_entrega, departamento_recibe FROM relacion_cuentas 
	 WHERE relacion_id = $relacion_id_vieja";
    $result1 = execute_query($dbh, $query1);
    
    $comprobar1 = pg_fetch_row($result1);
    
    $departamento_o = $comprobar1[0];
    $departamento_d = $comprobar1[1];
    
    free_result($result1);
    
    	if($estado == 'E' AND $departamento_d <> $departamento_entrega){
    	 
    	 	$consulta1 = "SELECT descripcion FROM departamentos 
	 		WHERE departamento = '$departamento_d'";
    		$resultado1 = execute_query($dbh, $consulta1);
       		$dpto1 = pg_fetch_row($resultado1);
       		$departamento = $dpto1[0];
      		free_result($resultado1);
    	 
    	 	$numero_control = $numero_control + 1;
    	 	
    	 	$message .= 
			 "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." ya fue recibida por el departamento de ".$departamento."</strong></font></td></tr>";
      
    	 	
    	 	}
    	 		else if($estado == 'R'){
					$numero_control = $numero_control + 1;
					
					
					
					$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." ya se encuentra relacionada"."</strong></font></td></tr>";
					}
				
		
		else if ($fecha_inicial == null){
			$numero_control = $numero_control + 1;
			
//echo 'linea 809';
			$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La cuenta ".$p_insert->numerodecuenta." no tiene orden de salida"."</strong></font></td></tr>";
		}
		else{
			
		}		

 	}

	if($numero_control > 0){
		echo $message;	
		}
	else{
	 		$sql = "UPDATE relacion_cuentas SET "
		 . "departamento_entrega = '" . $departamento_entrega . "'"
		 . ",departamento_recibe = '" . $departamento_recibe
		 . "' WHERE relacion_id = " . $relacion_id . "";
		 
		if (execute_query($dbh, $sql)){
		 
		 				$sql = "DELETE FROM relacion_cuentas_detalle WHERE relacion_id = " . $relacion_id;
						execute_query($dbh, $sql);
		 			
					 	$estado = 'R';
						$result3 = execute_query($dbh, "SELECT cuentas.numerodecuenta, fac_facturas.empresa_id, fac_facturas.prefijo, fac_facturas.factura_fiscal, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.session_id, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.total_cuenta, tmp_relacion_cuentas_detalle.observacion
 FROM   ((((public.tmp_relacion_cuentas_detalle tmp_relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON tmp_relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((tmp_relacion_cuentas_detalle.empresa_id=fac_facturas.empresa_id) AND (tmp_relacion_cuentas_detalle.prefijo=fac_facturas.prefijo)) AND (tmp_relacion_cuentas_detalle.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  tmp_relacion_cuentas_detalle.usuario_id=$usuario_id
 ORDER BY tmp_relacion_cuentas_detalle.rel_det_id DESC");
 $fecha_recibe = null;
 												
while ($row3 = fetch_object($result3)) {
 $query = "Select a.rel_det_id, a.relacion_id, a.estado, b.fecha_recibe FROM relacion_cuentas_detalle a, relacion_cuentas b WHERE a.rel_det_id = (Select max(rel_det_id) from relacion_cuentas_detalle WHERE numerodecuenta = $row3->numerodecuenta AND relacion_id <> $relacion_id) AND a.relacion_id = b.relacion_id";
    $result = execute_query($dbh, $query);
    
    $comprobar = pg_fetch_row($result);
	$fecha_recibe = $comprobar[3];
	free_result($result);
	
	if($fecha_recibe == null){
				$inf_fecha_inicial = explode("AFO", $fecha_inicial);
				$fecha_inicial = $inf_fecha_inicial[0];
				$obs_fecha_inicial = $inf_fecha_inicial[1];
			}
			else{
				$fecha_inicial = $fecha_recibe;
				$obs_fecha_inicial = "RECIBE";
			
			}
 												 
 												 
 												 	if(isset ($row3->factura_fiscal) ==""){
														$sql1 = "INSERT INTO relacion_cuentas_detalle(relacion_id"
		 															. ",numerodecuenta"
		 															. ",estado"
		 															. ",total_cuenta"
		 															. ",observacion"
		 															. ",fecha_inicial"
																	. ",obs_fecha_inicial"
		 															. ") VALUES ("
		 															. "" . $relacion_id . ""
		 															. "," . $row3->numerodecuenta . ""
																	. ",'" . $estado . "'"
																	. "," . $row3->total_cuenta . ""
																	. ",'" . $row3->observacion . "'"
																	. ",'" . $fecha_inicial . "'"
																	. ",'" . $obs_fecha_inicial
																	. "')";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_cuentas_detalle 	WHERE rel_det_id = $row3->rel_det_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													else{
														$sql1 = "INSERT INTO relacion_cuentas_detalle(relacion_id"
		 															. ",numerodecuenta"
		 															. ",estado"
		 															. ",total_cuenta"
		 															. ",empresa_id"
		 															. ",prefijo"
		 															. ",factura_fiscal"
		 															. ",observacion"
		 															. ",fecha_inicial"
																	. ",obs_fecha_inicial"
		 															. ") VALUES ("
		 															. "" . $relacion_id . ""
		 															. "," . $row3->numerodecuenta . ""
																	. ",'" . $estado . "'"
																	. "," . $row3->total_cuenta . ""
																	. ",'" . $row3->empresa_id . "'"
																	. ",'" . $row3->prefijo . "'"
																	. "," . $row3->factura_fiscal . ""
																	. ",'" . $row3->observacion . "'"
																	. ",'" . $fecha_inicial . "'"
																	. ",'" . $obs_fecha_inicial
																	. "')";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_cuentas_detalle 	WHERE rel_det_id = $row3->rel_det_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													  
													  
 												 
 												 }
		 
			
		
				$consulta2 = "SELECT a.relacion_id, a.fecha_registro, b.nombre
 				FROM   relacion_cuentas a, system_usuarios b
 				WHERE  a.relacion_id = $relacion_id
 				AND a.usuario_entrega = b.usuario_id
 				ORDER BY a.relacion_id";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			
      			free_result($resultado2);
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr> 
      <td  background=imagenes/cellpic1.gif colspan=5 bgcolor=#EEEEEE><div align=left><strong><font color=#003366 size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>";
    
    $mensaje_insert .= "<tr> 
    <td width=8% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Cuenta</font></strong></a> 
   </td>
    <td width=8% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Factura Fiscal</font></strong></a> 
   </td>
    <td width=12% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Valor</font></strong></a> 
   </td>
    <td width=33% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=33% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Plan</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.rel_det_id
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$row_c[0]
ORDER BY relacion_cuentas_detalle.rel_det_id");
			$total_relacion = 0;
		while ($row_d = fetch_object($relacion_detalle)) {
		 $total_relacion = $total_relacion +  $row_d->total_cuenta;
		 $mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->numerodecuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->prefijo"." "."$row_d->factura_fiscal</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$". number_format ($row_d->total_cuenta)."</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->tipo_id_paciente"." "."$row_d->paciente_id"." "."$row_d->primer_nombre"." "."$row_d->segundo_nombre"." "."$row_d->primer_apellido"." "."$row_d->segundo_apellido</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->plan_descripcion</font></td>
	</tr>";
		 
		 }
		 
	$mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td colspan=2><div align=center class=Estilo1><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color><u>Total Relacion:</u></font></div></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color><strong>$". number_format ($total_relacion)."</strong></font></td>
	<td colspan=3><strong><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color></font></strong></td>
	</tr>
	<tr bgcolor=#EEEEEE>
	<td colspan=3><a href=imprimir_relacion_tercero.php?relacion_id=$relacion_id target=_blank>Imprimir Relacion</a>
	</td>
	<td colspan=2><a href=xls_relacion.php?relacion_id=$relacion_id>Exportar a Excel</a>
	</td>
	
	</tr></table><br>";
	
	$mensaje_insert .= $imprimir;
		} 
		
		else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			}
			
			
			$departamento_recibe = '';
			$departamento_entrega = '';
			$proceso = 'grabar';
			$relacion_id = "";
	}
}
		 break;
	
	default:
	
	
	
	
	
	if ($relacion_id != ""){
	 
	 
	 if ($proceso != "")
	 	{
			
		}
	else
		{
			execute_query($dbh, "DELETE FROM tmp_relacion_cuentas_detalle WHERE usuario_id = " . $usuario_id);
	 
	 		$result = execute_query($dbh, "SELECT a.* FROM relacion_cuentas a WHERE a.relacion_id = " . $relacion_id);
	 		$row = fetch_object($result);
	 		$relacion_id = $row->relacion_id;
			$departamento_entrega = $row->departamento_entrega;
	 		$departamento_recibe = $row->departamento_recibe;
	 		free_result($result);
	 
	 		$result = execute_query($dbh, "SELECT * FROM relacion_cuentas_detalle WHERE relacion_id = " . $relacion_id);
	 		while (($row = fetch_object($result))) {
	  
	  			if($row->factura_fiscal==""){
			  
			  		$sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
		 		 	. ",numerodecuenta"
				 	. ",total_cuenta"
				 	. ",observacion"
				 	. ",usuario_id"
				 	. ") VALUES ("
				 	. "'" . $sess . "'"
				 	. "," . $row->numerodecuenta . ""
				 	. "," . $row->total_cuenta . ""
				 	. ",'" . $row->observacion . "'"
				 	. "," . $usuario_id
				 	. ")";
					execute_query($dbh, $sql);
				}
				else{
					$sql = "INSERT INTO tmp_relacion_cuentas_detalle(session_id"
		 		 	. ",numerodecuenta"
		 		 	. ",empresa_id"
		 		 	. ",prefijo"
		 		 	. ",factura_fiscal"
				 	. ",total_cuenta"
				 	. ",observacion"
				 	. ",usuario_id"
				 	. ") VALUES ("
				 	. "'" . $sess . "'"
				 	. "," . $row->numerodecuenta . ""
				 	. ",'" . $row->empresa_id . "'"
				 	. ",'" . $row->prefijo . "'"
				 	. "," . $row->factura_fiscal . ""
				 	. "," . $row->total_cuenta . ""
				 	. ",'" . $row->observacion . "'"
				 	. "," . $usuario_id
				 	. ")";
					execute_query($dbh, $sql);
				}
			}
	 
		}
		$proceso="actualizar";
	}
	else{
		if ($proceso != "")
	 	{
			
		}
		
		else
		{
			execute_query($dbh, "DELETE FROM tmp_relacion_cuentas_detalle WHERE usuario_id = " . $usuario_id ." ");
		}
				
		$proceso="grabar";
	}
}



?>

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="buscarcuenta" action="<?=$_SERVER['PHP_SELF']?>" method="POST" onSubmit="return Verificar()">
<input name="relacion_id" type="hidden" id="relacion_id" value="<?php echo $relacion_id;?>">
<input name="cod_menu" type="hidden" id="cod_menu" value="<?php echo $cod_menu;?>">
<input name="proceso" type="hidden" id="proceso" value="<?php echo $proceso;?>">
<table align="CENTER%" width="100%" border="1" bordercolor="blue">
<tr>
<td><center><img src="imagenes/logo_clinica.bmp" align="CENTER%" width="250" height="150">  
	
	</td>
  </tr>
<tr>
<td>
  <table align="CENTER%" width="100%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">RELACION DE ENTREGAS CUENTAS</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Origen</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_entrega\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_entrega = execute_query($dbh, "SELECT departamentos.departamento, departamentos.descripcion, system_usuarios.usuario_id
 FROM   (public.system_usuarios_departamentos system_usuarios_departamentos INNER JOIN public.departamentos departamentos ON system_usuarios_departamentos.departamento=departamentos.departamento) INNER JOIN public.system_usuarios system_usuarios ON system_usuarios_departamentos.usuario_id=system_usuarios.usuario_id
 WHERE  system_usuarios.usuario_id=$usuario_id AND departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_cuenta = '1') ORDER BY 2");

while (($row = fetch_object($result_dpto_entrega))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_entrega) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_entrega);
print("</select>"); 
?>

</td>
    
     
<td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Destino</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_recibe\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_recibe = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_cuenta = '1') ORDER BY 5");

while (($row = fetch_object($result_dpto_recibe))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_recibe) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_recibe);
print("</select>"); 
?>

</td>
          
    </tr>
</form>

<?php   echo $mensaje_insert; ?>

<?php echo "<form method=\"GET\" action=\"relaciones.php\">";?>    
    <?php if($departamento_entrega <> '' AND $departamento_recibe <> ''){
	 
	 ?>
    <tr> 
    
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></td>
      <td bgcolor="#EEEEEE"><input name="numerodecuenta" type="text" id="numerodecuenta" maxlength="10" class="textbox"></td>
      <td bgcolor="#EEEEEE"></td>
      <td bgcolor="#EEEEEE"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Prefijo</font></td>
      <td bgcolor="#EEEEEE"><select name="prefijo" id="prefijo">
          <option value=''>Todas</option>
<?php
$result_prefijo = execute_query($dbh, "SELECT * FROM documentos WHERE tipo_doc_general_id = 'FV01' ORDER BY prefijo");
while (($row = fetch_object($result_prefijo))) {
    echo "<option value='" . $row->prefijo . "'";
    if ($row->prefijo == $prefijo) echo " selected ";
    echo ">" . $row->prefijo . "</option>";
} 
free_result($result_prefijo);

?>
        </select></td>
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></td>
      <td bgcolor="#EEEEEE"><input name="factura_fiscal" type="text" id="factura_fiscal" maxlength="10" class="textbox"></td>
    </tr>
    
    <?php echo 	"<input type=\"hidden\" name=\"departamento_entrega\" value=\"$departamento_entrega\">";
			  echo	"<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
			  echo	"<input type=\"hidden\" name=\"relacion_id\" value=\"$relacion_id\">";
			  echo	"<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
			  echo	"<input type=\"hidden\" name=\"proceso\" value=\"$proceso\">";
			  echo	"<input type=\"hidden\" name=\"action1\" value=\"add\">";
 		echo "<tr bgcolor=#EEEEEE>
 		 <td>
		 <input type=\"submit\" name=\"Submit\" value=\"Adicionar\" >
        </td>
        <td></td>
        <td></td>
        <td></td>
    	</tr>";?>
	</form> 
    <?php
    }
?>
    
    
    
   <?php
   	

if($departamento_entrega <> '' AND $departamento_recibe <> ''){
 
 		$resultado_b = pg_query($dbh, "SELECT cuentas.numerodecuenta, fac_facturas.prefijo, fac_facturas.factura_fiscal, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.session_id, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.total_cuenta
 FROM   ((((public.tmp_relacion_cuentas_detalle tmp_relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON tmp_relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((tmp_relacion_cuentas_detalle.empresa_id=fac_facturas.empresa_id) AND (tmp_relacion_cuentas_detalle.prefijo=fac_facturas.prefijo)) AND (tmp_relacion_cuentas_detalle.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  tmp_relacion_cuentas_detalle.usuario_id=$usuario_id
 ORDER BY tmp_relacion_cuentas_detalle.rel_det_id");

		$rows = pg_num_rows($resultado_b);
if($rows > 0){		
		
$result2 = execute_query($dbh, "SELECT cuentas.numerodecuenta, fac_facturas.prefijo, fac_facturas.factura_fiscal, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.session_id, tmp_relacion_cuentas_detalle.rel_det_id, tmp_relacion_cuentas_detalle.total_cuenta
 FROM   ((((public.tmp_relacion_cuentas_detalle tmp_relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON tmp_relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((tmp_relacion_cuentas_detalle.empresa_id=fac_facturas.empresa_id) AND (tmp_relacion_cuentas_detalle.prefijo=fac_facturas.prefijo)) AND (tmp_relacion_cuentas_detalle.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  tmp_relacion_cuentas_detalle.usuario_id=$usuario_id
 ORDER BY tmp_relacion_cuentas_detalle.rel_det_id DESC");



?>
  
<br>
<table width="100%" border="0" cellspacing="0">
  <tr> 
    <td width="8%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a> 
   </td>
    <td width="8%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Factura Fiscal</font></strong></a> 
   </td>
    <td width="12%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a> 
   </td>
    <td width="33%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a> 
   </td>
    <td width="33%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a> 
   </td>
    <td width="6%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Opcion</font></strong></a> 
   </td>
 </tr>
<?php
$i = 1;

$total_relacion = 0;
while ($row2 = fetch_object($result2)) {
$total_relacion = $total_relacion +  $row2->total_cuenta;
 
 
?>
  <tr bgcolor="#EEEEEE"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row2->numerodecuenta?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row2->prefijo." ".$row2->factura_fiscal?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">$<?php echo number_format($row2->total_cuenta)?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row2->tipo_id_paciente." ".$row2->paciente_id." ".$row2->primer_nombre." ".$row2->segundo_nombre." ".$row2->primer_apellido. " ".$row2->segundo_apellido?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row2->plan_descripcion?></font></td>
    <td>
    <?php echo "<form method=\"POST\" action=\"relaciones.php?action1=delete&amp;departamento_entrega=$departamento_entrega&amp;departamento_recibe=$departamento_recibe&amp;relacion_id=$relacion_id&amp;proceso=$proceso&amp;cod_menu=$cod_menu\">";?>
	<input type="checkbox" name="borrarlinea[]" value="<?php echo $row2->rel_det_id;?>">
	<a href="#" onClick="abrirpopup('obs_cuenta_relacion1.php?rel_det_id=<?php echo $row2->rel_det_id?>',500,150);">Obs </a>
	</td>
	
  </tr>
  <?php
    
}
echo 	"<input type=\"hidden\" name=\"departamento_entrega\" value=\"$departamento_entrega\">";
		"<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
		"<input type=\"hidden\" name=\"relacion_id\" value=\"$relacion_id\">";
		"<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
		"<input type=\"hidden\" name=\"proceso\" value=\"$proceso\">";
 		echo "<tr bgcolor=#EEEEEE>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
		 <td>
		 <input type=\"submit\" name=\"Submit\" value=\"Borrar\" >
        </form>
		</td>
		</tr>";
		?>
	<tr bgcolor="#EEEEEE"> 
    <td colspan="2"><div align="center" class="Estilo1"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><u>Total Relacion:</u></font></div></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><strong>$<?php echo number_format ($total_relacion)?></strong></font></td>
	<td colspan="3"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"></font></strong></td>
	</tr>	
</table>

<table width="100%" border="0" cellspacing="0">
  <tr> <td width="8%" background="imagenes/cellpic1.gif" align="center"><?php
    echo "<form method=\"POST\" action=\"relaciones.php?action1=$proceso&amp;departamento_entrega=$departamento_entrega&amp;departamento_recibe=$departamento_recibe&amp;relacion_id=$relacion_id&amp;proceso=$proceso&amp;cod_menu=$cod_menu\">
		<input type=\"submit\" name=\"Submit\" value=\"Confirmar\" >
        </form>";
        ?>
  </tr>
</table>   
<?php
}
}
?>


</td>
</tr>

</body>
</html>
