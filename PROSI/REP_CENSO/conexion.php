<?php
  $dbconn = pg_connect("host=127.0.0.1 port=5432 dbname=SIIS user=admin password=admincdoqwe")
      or die ("Nao consegui conectar ao PostGres --> " . pg_last_error($conn));
?> 