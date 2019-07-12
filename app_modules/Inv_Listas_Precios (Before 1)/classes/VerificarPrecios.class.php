<?php
  	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	include '../../../conexion2.php';
	$response = '';

	if(isset($_POST)){
		extract($_POST, EXTR_PREFIX_SAME, "var_");

		if(isset($accion)){			
			if($accion === 'refreshInitialValue'){
				if(isset($empresa) && isset($producto)){

					$sqlVerificarPrecios = '
						SELECT 			
							costo_ultima_compra
						FROM
							inventarios 
						WHERE 
							empresa_id = \''.$empresa.'\'
						AND 
							codigo_producto = \''.$producto.'\'
					';
					$query = pg_query($dbconn, $sqlVerificarPrecios);		
					$inventario = pg_fetch_object($query);		
					$response = $inventario->costo_ultima_compra;
				}
			}else if($accion === 'tipos_listas_precios'){
                if(isset($empresa)){
                    if(isset($centro) && $centro !== ''){
                        $whereCentro = "AND centro_utilidad = '".$centro."'";
                    }else{
                        $whereCentro = "";
                    }
					$sqlTiposListasPrecios = '
						SELECT 			
							codigo_lista,
							descripcion
						FROM
							listas_precios
						WHERE
							empresa_id = \''.$empresa.'\'							
						AND
						    NOT status = 0
				        '.$whereCentro.'
					';
					$query = pg_query($dbconn, $sqlTiposListasPrecios);
					$countOption = 0;
					$response = '<option id="lista_'.$countOption.'" value="0" disabled selected>-Seleccione una Lista-</option>';
					while($inventario = pg_fetch_object($query)){
					    $countOption++;
						$response .= '<option id="lista_'.$countOption.'" value="'.$inventario->codigo_lista.'">'.$inventario->descripcion.'</option>';
					}
				}
			}else if($accion === 'AllDataEmpresa'){
			    if(isset($empresa)){
                    $sqlAllDataEmpresa = "
                        SELECT
                           tipo_id_tercero AS empresa_tipo_documento,
                           id AS empresa_documento,
                           razon_social AS empresa_razon_social,
                           direccion AS empresa_direccion,
                           telefonos AS empresa_telefono               
                        FROM
                          empresas AS e
                        WHERE
                          e.empresa_id = '". $empresa ."'
                    ";
                    $query = pg_query($dbconn, $sqlAllDataEmpresa);
                    $response = pg_fetch_all($query);
                }
            }else if($accion === 'verifyQuantityCentroBodegas'){
			    if(isset($_SESSION['empresa'])){
                    $response = 'All Fine!!';
                }else{
			        $response = 'No existe $_SESSION[\'empresa\']!!!';
                }
            }else{
                $response = 'Format Invalid in Data!';
            }
		}
	}else{ $response = 'Format Invalid in Data!'; }
	echo $response;
?>