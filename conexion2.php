<?php
	$dbconn = pg_connect("host=10.0.2.246 dbname=dusoft user=admin password=.123mauro*")
    or die ("Nao consegui conectar ao PostGres --> " . pg_last_error($conn));
?>
