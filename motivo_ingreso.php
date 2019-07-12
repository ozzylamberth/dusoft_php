<?
        $VISTA='HTML';
        include 'includes/enviroment.inc.php';

        do
        {

            $query = "select A.ingreso, B.hc_motivo_consulta_id from
                  hc_evoluciones as A, hc_motivo_consulta as B
                  where A.evolucion_id=B.evolucion_id and B.ingreso is null LIMIT 10 OFFSET 0;";


            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                die( " ERROR : " . $dbconn->ErrorMsg());
            }

            $filas = $result->GetRows();
            $result->Close();
            $query="BEGIN WORK; \n";

				foreach ($filas as $k => $v)
        {
            $query .= "update hc_motivo_consulta set ingreso='".$v[0]."'
                        where hc_motivo_consulta_id=".$v[1].";\n ";
        }

					$query .="COMMIT; \n";

					echo  "query ==>$query<BR><BR>";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							die(" ERROR : " . $dbconn->ErrorMsg());
					}

					$result->Close();

			} while(sizeof($filas)>0);

			echo "ACTUALIZACION FINALIZADA";

?>
