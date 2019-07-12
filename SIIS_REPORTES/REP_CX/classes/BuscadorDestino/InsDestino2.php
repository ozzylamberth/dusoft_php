<?php
// InsDestino2.php  09/12/2003
// --------------------------------------------------------------------------------------7//
// eHospital v 0.1                                                                       //
// Copyright (C) 2003 InterSoftware Ltda.                                               //
// Emai: intersof@telesat.com.co                                                       //
// -----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez,Darling Dorado,Lorena Aragón                     //                       //
// Proposito del Archivo: realizar la busqueda de los paises de origen de los       //
// pacientes,y permite adicionar departamentos y municipos.                        //
//                                                                                //
// ------------------------------------------------------------------------------//

$VISTA='HTML';
$_ROOT='../../';
include_once $_ROOT.'includes/enviroment.inc.php';
 //echo "hola";
		//		exit;
// header("Cache-Control: no-cache, must-revalidate");
						$coPais=$_REQUEST['codpais'];
					 	$coddpto=$_REQUEST['codepto'];
					 	$codmpio=$_REQUEST['codmpio'];
					 	$nompio=strtoupper($_REQUEST['nompio']);

						if(!empty($coPais) or ($coPais!="") or !empty($coddpto) or ($coddpto!=""))
						{
										if((($coddpto!="")  and ($codmpio!="") and ($nompio!="")))
											{
                        list($conn) = GetDBconn();
												$sql1="SELECT COUNT(tipo_mpio_id) FROM tipo_mpios WHERE tipo_mpio_id ='$codmpio'
												AND 	tipo_pais_id='$coPais'";
												$result=$conn->Execute($sql1);
												$cont=$result->fields[0];
												if($cont>'0')
													 {
                             header("location:selector.php?pais=$coPais&dpto=$coddpto&resp=true&EditarM=Editar");
													   exit;
													 }
													 else
													 {

													  //aqui se insertan los datos..
														$sql="insert into tipo_mpios
																		( tipo_dpto_id,
																			tipo_pais_id,
																			tipo_mpio_id,
																			municipio
																		)
																		values(

																			'$coddpto',
																			'$coPais',
																			'$codmpio',
																			'$nompio')";
														 $result=$conn->Execute($sql);
														 header("location:selector.php?pais=$coPais&dpto=$coddpto&ciudad=$codmpio&resp=true");
                             exit;
													 }
											}
											else //sin insertaron espacios en blanco....va aca
											{
												header("location:selector.php?pais=$coPais&dpto=$coddpto&resp=falso&EditarM=Editar");
												exit;
											}
         		}
		       else
		        {
              header('location:selector.php?resp=false');
							exit;
		        }

?>