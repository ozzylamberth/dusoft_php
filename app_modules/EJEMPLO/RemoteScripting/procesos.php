<?php
    /*
    incluimos rs_server.class.php que contiene la class rs_server que será la que "extenderemos"
    */
		$VISTA = "HTML";
		$_ROOT="../../../";
    include  "../../../classes/rs_server/rs_server.class.php";
		include	 "../../../includes/enviroment.inc.php";
    class procesos_admin extends rs_server {
    /*
    * Definimos tantos métodos como funciones queremos que nuestro servidor "sirva"
    */
        function get_valores ( $parameters )  {
            // los parametros siempre son un array ... en este caso de solo 1 elemento
            $mamiferos  = array ( 'gato', 'perro', 'conejo', 'raton' );
            $aves       = array ( 'gallina', 'gorrión', 'cuervo', 'canario' );
            $moluscos   = array ( 'id-berberecho', 'coquina', 'almeja');

            $valor = $parameters[0];
            //comprobamos que existan el array con los valores:
            if ( isset( $$valor)) {
                // devolvemos el array como una cadena delimitada por ~
                return implode( '~',  $$valor );
            } else {
                // sinó ... enviamos una cadena vacia ...
                return  '';
            }
						
        }

				/**
				*
				*/
			function get_valores_depto ( $parameters )  {
					$valor = $this-> ConsultaDepto($parameters[0]);
					$cad='';
					foreach($valor as $val){
						$cad[] = $val[tipo_dpto_id]."-".$val[departamento];
					}
					return implode('~',$cad);
					
			}
		
			/**
			*
			*/
			function ConsultaDepto($pais){
				$query="SELECT tipo_dpto_id,departamento
									FROM	tipo_dptos
									WHERE tipo_pais_id ='".$pais."'
									ORDER BY departamento";
					list($dbconn) = GetDBconn();
					$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Consulta depto";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								return false;
						}
						while(!$result->EOF)
						{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
					return $vector;
			}
	
			/**
			*
			*/
		function get_valores_mpio ( $parameters )  {
			$valor = $this-> ConsultaMpio($parameters);
			$cad='';
			foreach($valor as $val){
				$cad[] = $val[tipo_mpio_id]."-".$val[municipio];
			}
			return implode('~',$cad);
			
		}
		/**
		*
		*/
		function ConsultaMpio($parameters){
		 $query="SELECT tipo_mpio_id,municipio
							FROM	 tipo_mpios
							WHERE tipo_pais_id ='".$parameters[0]."' AND 
										tipo_dpto_id = '".$parameters[1]."'
							ORDER BY municipio";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta depto";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			return $vector;
		}
		
		
   }//end of class


    /*
        cuando creamos el objeto que tiene los procesos debemos indicar como único parámetro un
        array con todas las funciones posibles ... esto se hace para evitar que se pueda llamar
        a cualquier método del objeto.

    */

    $oRS = new procesos_admin( array( 'get_valores','get_valores_depto', "get_valores_mpio"));

    // el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
    $oRS->action();


?>