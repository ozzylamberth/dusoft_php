<?php

/**
 * $Id: buscador.php,v 1.2 2005/06/02 16:01:53 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la busqueda de diagnosticos,finalidad
 */


?>
<html>
<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="seleccion.js">
</script>
</head>
<?php
  $VISTA='HTML';
	$_ROOT='../../';
	include_once $_ROOT.'includes/enviroment.inc.php';
	include_once $_ROOT.'includes/api.inc.php';
	$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
  IncludeFile($filename);
	print (ReturnHeader('BUSCADOR'));
	print(ReturnBody());
	$limit=10;

	function CalcularNumeroPasos($conteo,$limit){
 		$numpaso=ceil($conteo/$limit);
		return $numpaso;
	}
	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}
	function CalcularOffset($paso,$limit){
		$offset=($paso*$limit)-$limit;
		return $offset;
	}

	function RetornarBarra($limit,$conteo){
	if($limit>=$conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		$accion='buscador.php?conteo='.$conteo."&sign=".$_REQUEST['sign']."";
		$barra=CalcularBarra($paso);
		$numpasos=CalcularNumeroPasos($conteo,$limit);
		$colspan=1;

		echo "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset(1,$limit)."&paso=1'>&lt;</a></td>";
			echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($paso-1,$limit)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						echo "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($i,$limit)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($paso+1,$limit)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($numpasos,$limit)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					echo "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($i,$limit)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($paso+1,$limit)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				echo "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($numpasos,$limit)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		echo "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
	}
	list($dbconn) = GetDBconn();
	
	if($_REQUEST['sign']==1)
		{
			$table="diagnosticos";
			$campo1="diagnostico_id";
			$campo2="diagnostico_nombre";
			
		}
		elseif($_REQUEST['sign']==2)
		{
			$table="diagnosticos";
			$campo1="diagnostico_id";
			$campo2="diagnostico_nombre";
		
		}
		elseif($_REQUEST['sign']==3)
		{
			$table="hc_tipos_finalidad";
			$campo1="tipo_finalidad_id";
			$campo2="detalle";
		
		}
	
	if(empty($_REQUEST['conteo'])){
		$query="SELECT count(*) FROM $table
		WHERE  UPPER($campo2) LIKE('%".strtoupper($_REQUEST['descripcion'])."%')";
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		$conteo=$result->fields[0];
	}else{
    $conteo=$_REQUEST['conteo'];
	}
  if(!$_REQUEST['Of']){
    $Of='0';
	}else{
    $Of=$_REQUEST['Of'];
	}
	 $query = "SELECT $campo1 as codigo,$campo2 as nombre FROM $table 
						WHERE  UPPER($campo2) LIKE('%".strtoupper($_REQUEST['descripcion'])."%')
						LIMIT " . $limit . " OFFSET $Of";
	$result = $dbconn->Execute($query);
	if($result->EOF){
		$this->error = "Error al ejecutar la consulta.<br>";
		$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
		//return false;
	}else{
		while (!$result->EOF)
		{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
		}
	}
  echo '<BR><BR>';
?>
  <form name=forma method=POST action="buscador.php?<?php echo "sign=".$_REQUEST['sign']; ?>">
  <table cellspacing=3 border=0 align=center cellpadding=3 valign=bottom width=60%  class=modulo_table>
    <tr>
      <td width=30% nowrap>
			 <?php 
			 	if($_REQUEST['sign']==1)
				{	$a="DIAGNOSTICO INGRESO";}elseif($_REQUEST['sign']==2){	$a="DIAGNOSTICO ENGRESO";}
					elseif($_REQUEST['sign']==3){	$a="FINALIDAD";}
				echo "BUSCAR $a";
			 ?>
		  </td>
			<td>
			  <input type=text maxlength=40 size=40 name=descripcion value="<?php echo $descripcion ?>" class=input-text>
			</td>
		</tr>
		<tr>
		  <td align=center colspan=2>
			  <input type=submit class=input-submit name=Aceptar value="Buscar">
			  <input type=button class=input-submit name=Cancelar value="Cerrar" onclick="window.close()">
			</td>
    </tr>
	</table><BR><BR>
  <table cellspacing=3 border=0 align=center cellpadding=3 valign=bottom width=90% >
		<?php
		$y=0;
		for($y=0;$y<sizeof($var);$y++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			?><tr class='<?echo $estilo?>'>
			    <td width=20%><?echo $var[$y][codigo] ?></td>
			   <td width=80%><?echo $var[$y][nombre]  ?></td>
				<td width=5%><input type='checkbox' name='Seleccion'  onclick="PasarInformacion('<?echo $var[$y][codigo]?>',this.checked)">
				</tr>
			</tr><?
		}
		print (ReturnFooter());
		?>
		
		<?php
		if($var)
		{
			echo	"<tr align='right'><td class=$estilo colspan='3'>
				<input type=button class=input-submit   onclick=MandarInformacion_A_VentanaHija('".$_REQUEST['sign']."')
				value=ADICIONAR></td></tr>";
		}
		?>
		
		</tr>
  </table><BR><BR>
	<input type='hidden' name='code'><br>
		
  <?
	if($conteo==0)
	{
		echo "<TABLE width='50%' align='center'><TR><TD><LABEL class='label_mark'>NO HUBO NINGUN RESULTADO DE LA BUSQUEDA ".$_REQUEST['descripcion']."</label></TD></TR></TABLE>";
	}
	RetornarBarra($limit,$conteo);
	?>
	</form>
	</html>

