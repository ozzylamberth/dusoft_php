<?php
session_start();

if ($_SESSION["usuario_id"] == ""){
	header("Location: login.php");
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title><< PROYECTO SIIS >></title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<frameset rows="100,*,25" cols="*" frameborder="NO" border="0" framespacing="0">
  <frame src="cabecera.php" name="topFrame" scrolling="NO" noresize >
  <frameset rows="*" cols="180,*" framespacing="0" frameborder="NO" border="0">
    <frame src="menu.php" name="leftFrame" scrolling="YES" noresize>
    <frame src="principal.php" name="mainFrame">
  </frameset>
  <frame src="pie.html">
</frameset>
<noframes><body>

</body></noframes>
</html>
