<?php
// Security.inc.php  17/08/2004
// ----------------------------------------------------------------------
// SIIS v 1.0
// Copyright (C) 2004 IPSOFT S.A.
// Email: mail@ipsoft-sa.com
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Crear los permisos de los objetos de la BD de SIIS.
// ----------------------------------------------------------------------


    $VISTA='HTML';
    include 'includes/enviroment.inc.php';

    $PermisosDBconn = ADONewConnection($ConfigDB['dbtype']);


   if (!($PermisosDBconn->Connect($ConfigDB['dbhost'], base64_decode($ConfigDB['dbuserAdmin']), base64_decode($ConfigDB['dbpassAdmin']),$ConfigDB['dbname']))) {
        die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$PermisosDBconn->ErrorMsg()));
    }
 
        echo "<center><h1>CONECTADO A LA BASE DE DATOS </h1></center><BR>";
        echo "<center><h1><b>$ConfigDB[dbhost]/$ConfigDB[dbname]</b></h1></center><BR><BR>";

        $sql  = "REVOKE ALL ON SCHEMA public FROM PUBLIC;";
        $sql .= "GRANT USAGE ON SCHEMA public TO siis;";
        $sql .= "GRANT USAGE ON SCHEMA public TO siis_consulta;";
        $sql .= "GRANT USAGE ON SCHEMA public TO datalab;";
        $sql .= "GRANT SELECT ON TABLE interface_datalab_solicitudes TO datalab;";
        $sql .= "GRANT SELECT,INSERT,UPDATE,TRIGGER ON TABLE interface_datalab_resultados TO datalab;";
        $sql .= "GRANT EXECUTE ON FUNCTION interface_datalab_trigger_resultado() TO datalab;";

        $sql .= "GRANT SELECT, UPDATE ON TABLE	os_maestro TO datalab;";
				$sql .= "GRANT SELECT, UPDATE ON TABLE	interface_datalab_control_detalle TO datalab;";
				$sql .= "GRANT SELECT ON TABLE interface_datalab_control TO datalab;";
				$sql .= "GRANT SELECT ON TABLE interface_datalab_bacteriologo  TO datalab;";
				$sql .= "GRANT INSERT ON TABLE	hc_resultados TO datalab;";
				$sql .= "GRANT INSERT ON TABLE	hc_resultados_sistema TO datalab;";
				$sql .= "GRANT INSERT ON TABLE	hc_apoyod_resultados_detalles  TO datalab;";

				$sql .= "GRANT SELECT, UPDATE ON TABLE	hc_resultados_resultado_id_seq  TO datalab;";


    $PermisosDBconn->Execute($sql);
    
    if ($PermisosDBconn->ErrorNo() != 0) {
            echo " ERROR : " . $PermisosDBconn->ErrorMsg();
    }

     $sql = "SELECT tablename FROM pg_catalog.pg_tables
            WHERE schemaname='public'
            ORDER BY tablename";
   
    $result=$PermisosDBconn->Execute($sql);        
    $TABLAS=$result->GetRows($result);
    $result->close();

    echo "<h1>Tablas actualizadas : ".sizeof($TABLAS)."</h1><br><br>";
        
    foreach($TABLAS as $k=>$v){
        echo str_pad($k+1, 3, "0", STR_PAD_LEFT); 
        echo " : $v[0]";
        $sql  = "REVOKE ALL ON TABLE \"$v[0]\" FROM PUBLIC;";
        $sql .= "GRANT SELECT ON TABLE \"$v[0]\" TO siis_consulta;\n";
        $sql .= "GRANT SELECT,INSERT,UPDATE,DELETE ON TABLE \"$v[0]\" TO siis;\n";
        $PermisosDBconn->Execute($sql);
        if ($PermisosDBconn->ErrorNo() != 0) {
            echo " ERROR : " . $PermisosDBconn->ErrorMsg();
        }
        echo "<br>";
    }
    
    $sql = "SELECT viewname FROM pg_catalog.pg_views
            WHERE schemaname='public' ORDER BY viewname";
    $result=$PermisosDBconn->Execute($sql);        
    $VISTAS=$result->GetRows($result);
    $result->close();

    echo "<br><br><center><h1>Vistas actualizadas : ".sizeof($VISTAS)."</h1></center><br><br>";
    
    foreach($VISTAS as $k=>$v){
        echo str_pad($k+1, 3, "0", STR_PAD_LEFT); 
        echo " : $v[0]";
        $sql  = "REVOKE ALL ON TABLE \"$v[0]\" FROM PUBLIC;\n";
        $sql .= "GRANT SELECT ON TABLE \"$v[0]\" TO siis_consulta;\n";
        $sql .= "GRANT SELECT ON TABLE \"$v[0]\" TO siis;\n";
        $PermisosDBconn->Execute($sql);
        if ($PermisosDBconn->ErrorNo() != 0) {
            echo " ERROR : " . $PermisosDBconn->ErrorMsg();
        }
        echo "<br>";
    }
    
        
    $sql = "SELECT c.relname FROM pg_catalog.pg_class c, pg_catalog.pg_user u, pg_catalog.pg_namespace n
    WHERE c.relowner=u.usesysid AND c.relnamespace=n.oid
    AND c.relkind = 'S' AND n.nspname='public' ORDER BY relname";

    $result=$PermisosDBconn->Execute($sql);        
    $SECUENCIAS=$result->GetRows($result);
    $result->close();

    echo "<br><br<h1>Secuencias actualizadas : ".sizeof($SECUENCIAS)."</h1><br><br>";
    
    foreach($SECUENCIAS as $k=>$v){
        echo str_pad($k+1, 3, "0", STR_PAD_LEFT); 
        echo " : $v[0]";
        $sql  = "REVOKE ALL ON TABLE \"$v[0]\" FROM PUBLIC;\n";
        $sql .= "GRANT SELECT ON TABLE \"$v[0]\" TO siis_consulta;\n";
        $sql .= "GRANT SELECT,UPDATE ON TABLE \"$v[0]\" TO siis;\n";
        $PermisosDBconn->Execute($sql);
        if ($PermisosDBconn->ErrorNo() != 0) {
            echo " ERROR : '$sql'" . $PermisosDBconn->ErrorMsg();
        }
        echo "<br>";
    }        
    

    $sql = "SELECT
            DISTINCT p.proname,
            pg_catalog.oidvectortypes(p.proargtypes) AS arguments
            FROM pg_catalog.pg_proc p
            LEFT JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace
            WHERE p.prorettype <> 'pg_catalog.cstring'::pg_catalog.regtype
            AND p.proargtypes[0] <> 'pg_catalog.cstring'::pg_catalog.regtype
            AND NOT p.proisagg
            AND n.nspname = 'public'
            ORDER BY p.proname
            ";    
    
    $result=$PermisosDBconn->Execute($sql);        
    $FUNCIONES=$result->GetRows($result);
    $result->close();
    
    echo "<br><br><h1>Funciones actualizadas : ".sizeof($FUNCIONES)."</h1><br><br>";
    
    foreach($FUNCIONES as $k=>$v){
        echo str_pad($k+1, 3, "0", STR_PAD_LEFT); 
        echo " : $v[0]($v[1])";
        $sql  = "REVOKE ALL ON FUNCTION \"$v[0]\"($v[1]) FROM PUBLIC;\n";
        $sql .= "GRANT EXECUTE ON FUNCTION \"$v[0]\"($v[1]) TO siis_consulta;\n";
        $sql .= "GRANT EXECUTE ON FUNCTION \"$v[0]\"($v[1]) TO siis;\n";
        $PermisosDBconn->Execute($sql);
        if ($PermisosDBconn->ErrorNo() != 0) {
            echo " ERROR : '$sql'" . $PermisosDBconn->ErrorMsg();
        }
        echo "<br>";
    }    
        
?>            
                            
