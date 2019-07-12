<?php
// InsDestino1.php  09/12/2003
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



				$coPais=$_REQUEST['codpais1'];
				$coddpto=$_REQUEST['coddpto'];
				$nomDpto=$_REQUEST['nompto'];

				//print_r($_REQUEST) ;
        //echo "--$coPais--";
				//echo $coddpto ;
				//echo $nomDpto ;
			//exit();
        if(($coddpto!="")  and ($nomDpto!=""))
				{
                        list($dbconn) = GetDBconn();
												$nomDpto= strtoupper($nomDpto);
												$sql="SELECT COUNT(tipo_dpto_id) FROM tipo_dptos WHERE tipo_dpto_id='$coddpto'
												AND 	tipo_pais_id='$coPais'";
												$res=$dbconn->Execute($sql);
												$conteo=$res->fields[0];
												//echo $sql;
												//exit;
											if($conteo>'0')
													{
                            header("location:selector.php?pais=$coPais&resp=true&EditarD=Editar");
														exit;
												  }
													else
													{
													$sql1="insert into tipo_dptos
																		( tipo_dpto_id,
																			tipo_pais_id,
																			departamento
																		)
																		values(

																			'$coddpto',
																			'$coPais',
																			'$nomDpto')";
																			 $res1=$dbconn->Execute($sql1);
                                       //echo $sql1;
																			 //exit;
																			 header("location:selector.php?pais=$coPais&dpto=$coddpto&ciudad=$codmpio&resp=true");
                                       exit;
													}

					}
					else
					{
					 // echo "se salio por que habia espacios";
						//exit;
						header("location:selector.php?pais=$coPais&resp=falso&EditarD=Editar");
				    exit;
					}

?>
