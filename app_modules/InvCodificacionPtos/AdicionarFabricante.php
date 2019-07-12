<?php

/**
 * $Id: AdicionarFabricante.php,v 1.3 2005/12/29 16:51:33 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la busqueda de los paises de origen de los pacientes
 * y permite adicionar departamentos y municipios.
 */

?>
<html>
<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="selectorGrupo.js">
</script>
</head>
<?php	
  $VISTA='HTML';
	$_ROOT='../../';
	include_once $_ROOT.'includes/enviroment.inc.php';
	include_once $_ROOT.'includes/api.inc.php';
	$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
  IncludeFile($filename);
	print (ReturnHeader('LISTADO FABRICANTES'));
	print(ReturnBody());
	$GLOBALS["limit"]=app_Inventarios_admin();

	function app_Inventarios_admin()
	{
	 $limit=GetLimitBrowser();
   return $limit;
	}
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
		$accion='AdicionarFabricante.php?conteo='.$conteo;
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
	if($_REQUEST['descripcionBus']){
		$filt=" WHERE descripcion LIKE '%".strtoupper($_REQUEST['descripcionBus'])."%'";
	}
	if(empty($_REQUEST['conteo'])){
		$query="SELECT count(*) FROM inv_fabricantes $filt";
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
	$query = "SELECT descripcion,fabricante_id FROM inv_fabricantes $filt ORDER BY descripcion LIMIT " . $limit . " OFFSET $Of";
	$result = $dbconn->Execute($query);
	if($result->EOF){
		$this->error = "Error al ejecutar la consulta.<br>";
		$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
		return false;
	}else{
		while(!$result->EOF){
		  $vars[$result->fields[1]]=$result->fields[0];
			$result->MoveNext();
		}
	}
  echo '<BR><BR>';
?>
  <form name=forma method=POST action="InsFabricante.php?<?php echo "Empresa=".$_REQUEST['Empresa']; ?>">
  <table cellspacing=3 border=0 align=center cellpadding=3 valign=bottom width=80%  class=modulo_table>
    <tr>
      <td width=30% nowrap>
			  Adicionar Nombre Fabricante:
		  </td>
			<td>
			  <input type=text maxlength=40 size=40 name=descripcion value="<?php echo $descripcion ?>" class=input-text>
			</td>
		</tr>
		<tr>
		  <td align=center colspan=2>
			  <input type=submit class=input-submit name=Aceptar value="Insertar">
			  <?php/*<input type=submit class=input-submit name=Cancelar value="Cancelar">*/?>
			</td>
    </tr>
	</table><BR><BR>
	</form>
	<form name=forma method=POST action="AdicionarFabricante.php?<?php echo "Empresa=".$_REQUEST['Empresa']; ?>">	
  <table cellspacing=3 border=0 align=center cellpadding=3 valign=bottom width=70%  class=modulo_table_list>
		<tr class="modulo_list_claro">
		<td align="center" colspan="2">
		<input type=text maxlength=30 size=30 name=descripcionBus value=<?php echo $_REQUEST['descripcionBus']?>>&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=submit class=input-submit name=Buscar value="Buscar">
		</td>
		</tr>
		<?php
		$y=0;
		foreach($vars as $codFab=>$nomFab){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			?><tr class='<?echo $estilo?>'>
			  <td width=5%><input type='radio' name='Seleccion' value='1' onclick="ValoresFabricante('<?echo $codFab?>','<?echo $nomFab?>')">
				<input type='hidden' name='valorFab' value='<? echo $codFab ?>'>
			  <td><?echo $nomFab ?></td>
				</tr>
			</tr><?
			$y++;
		}
		print (ReturnFooter());
		?>
		</tr>
  </table><BR><BR>
  <?
	RetornarBarra($limit,$conteo);
	?>
	</form>
	</html>

