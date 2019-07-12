
<?php

/*
* ServiciosDepartamentos.php  21/08/2004
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los departamentos según el Centro de Uitilidad elegidocio elegido.
*/

?>

<head>
<title>ESTACIONES ENFERMERIA</title>
<script languaje="javascript">
	function cerrarVentana()
     {
     	close();
     }
</script>
<?php
			$VISTA='HTML';
			$_ROOT='../../';
			include_once $_ROOT.'includes/enviroment.inc.php';
			include_once $_ROOT.'includes/modules.inc.php';
			include_once $_ROOT.'includes/api.inc.php';
			$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
			IncludeFile($filename);
			
               print(ReturnHeader(''));
			print(ReturnBody());
               
               $datosEstacion = explode(",",$_REQUEST['sign']);
               list($dbconn) = GetDBconn();
			$query = "SELECT 	COUNT(CA.cama) AS total, 
					COUNT(MH.cama) AS ocupadas,
					TC.descripcion 
					FROM		estaciones_enfermeria EE,
                                   piezas PZ,
                                   tipos_camas TC,
                                   camas CA LEFT JOIN
                                   movimientos_habitacion MH
                                   ON(	CA.cama = MH.cama AND
							MH.fecha_egreso IS NULL) 
                         WHERE	CA.pieza = PZ.pieza 
                         AND		CA.tipo_cama_id = TC.tipo_cama_id 
                         AND		CA.SW_virtual = '1' 
                         AND		PZ.estacion_id = EE.estacion_id 
                         AND		EE.estacion_id = '".$datosEstacion[0]."'
                         GROUP BY 3;";
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
               while($fila = $resultado->FetchRow())
               {
               	$datos_cama[] = $fila;
               }
?>
			<form name=forma method=GET action="buscador.php"><br>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
			<tr>
			<td>
				<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
				<tr class="modulo_list_claro">
				<td width=25% nowrap class="label" colspan=3 align=center>TIPOS DE CAMAS EN <?php echo $datosEstacion[1]; ?>
				</td>
				</tr>
				<tr class="modulo_list_claro">
				<td width=80% nowrap align=center class="label">TIPO DE CAMA
				</td>
				<td colspan=2 width=20% align=center class="label"> DISPONIBLES
				</td>
				</tr>
                    <?php
                         if(!empty($datos_cama))
                         {
                              foreach($datos_cama as $k => $camas)
                              {
                                   $salida ="<tr class='modulo_list_claro'>";
                                   $salida.="<td width='80%' nowrap class='label' align='left'>".$camas[descripcion]."";
                                   $salida.="</td>";
                                   $disponibles = $camas['total'] - $camas['ocupadas'];
                                   $salida.="<td colspan='2' width='20%' align='right' class='label'>".$disponibles."";
                                   $salida.="</td>";
                                   $salida.="</tr>";
                                   echo $salida;
                              }
                         }
                         else
                         {
                                   $salida ="<tr class='modulo_list_claro'>";
                                   $salida.="<td width='100%' nowrap class='label_mark' align='center' colspan='3'>NO SE ENCONTRARON CAMAS EN <br>".$datosEstacion[1]."";
                                   $salida.="</td>";
                                   $salida.="</tr>";
                                   echo $salida;
                         }
                    ?>
				</table>
			</td>
			</tr>
			</table><br>
			<br><table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95%>
			<tr>
			<td align=center colspan=3>
			<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="cerrarVentana()">
			</td>
			</tr>
			</table>
			</form>
