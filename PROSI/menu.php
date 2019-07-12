<?php

if (!session_id()){
  session_start();
}


require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

open_database();

$self = $_SERVER["PHP_SELF"]; 
$usuario_id = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];


function ConsultaNivelesMenuUsuario($usuario_id, $dbh)
		{
		$query = "	SELECT a.cod_nivel, a.des_nivel
					FROM niveles_prosi a, menus_prosi b, usuarios_menu_prosi c
					WHERE a.cod_nivel = b.cod_nivel 
					AND b.cod_menu = c.cod_menu 
					AND c.usuario_id = ".$usuario_id."
					GROUP BY a.cod_nivel, a.des_nivel
					ORDER BY 1 ASC";
								
			$result=execute_query($dbh, $query);
			return $result;
		}
		
function ConsultaMenusUsuario($usuario_id, $cod_nivel, $dbh)
		{
		$query1 = "	SELECT a.cod_menu, a.des_menu, a.enlace_menu, a.indice_orden
					FROM menus_prosi a, usuarios_menu_prosi b
					WHERE a.cod_nivel = ".$cod_nivel." 
					AND a.cod_menu = b.cod_menu 
					AND b.usuario_id = ".$usuario_id."
					GROUP BY a.cod_menu, a.des_menu, a.enlace_menu, a.indice_orden
					ORDER BY a.indice_orden ASC";
							
			$result1=execute_query($dbh, $query1);
			return $result1;
		}		
		
?>		

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"></head>

<body bgcolor="WHITE">
<?php
$result_nivel_menu=ConsultaNivelesMenuUsuario($usuario_id, $dbh);
while ($nivel = fetch_object($result_nivel_menu)) {
 ?>
	<p><font color="#666666"><?php echo $nivel->des_nivel;?></font><br>
	
	<?php
	$result_menu=ConsultaMenusUsuario($usuario_id, $nivel->cod_nivel, $dbh);
	while ($menu = fetch_object($result_menu)) {
	 ?>
	 <img src="imagenes/mas.gif" width="8" height="8"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="<?php echo $menu->enlace_menu;?>?&amp;cod_menu=<?php echo $menu->cod_menu;?>" target="mainFrame"> <?php echo $menu->des_menu;?><br></a>
	 
	 <?php
	 }
}
?> 

  
<br>
<p><img src="imagenes/mas.gif" width="8" height="8"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="salir.php" target="_parent"> Salir<br>
</a>  
  
  


  <br>
</font></p>
</body>
</html>
