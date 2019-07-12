<?php
/**
* Submodulo de Antecedentes Perinatales (HTML).
*
* Submodulo para manejar la informacion de una madre mediante datos de parto y datos del recien nacido
* verificando su estado de salud en la madres en pre y post parto, al igual que la salud del recien nacido.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesPerinatales_HTML.php,v 1.3 2006/12/19 21:00:12 jgomez Exp $
*/

/**
* AntecedentesPerinatales_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Antecedentes Perinatales, se extiende la clase AntecedentesPerinatales y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
class AntecedentesPerinatales_HTML extends AntecedentesPerinatales
{

 /**
* Contiene una cadena que determina el color de los riesgos en pantalla, puede cambiarse el DEFAULT
* del color.
* @var string
* @access privado
*/
 var $color;

	function AntecedentesPerinatales_HTML()
	{
	    $this->color='blue';
	    $this->AntecedentesPerinatales();//constructor del padre
       	return true;
	}

/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'01/27/2005',
    'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }




/**
* Funcion que consulta los datos del parto  y de recien nacido y los trae en tablas
* @return boolean
*/

	function frmConsulta()
	{
				$pfj=$this->frmPrefijo;
			  $result=$this->ConsultaPerinatalParto();

				if(!$result->EOF)
							{
                      while (!$result->EOF)
			                {
                       /*PRIMERA TABLA DE CONSULTA*/
												$dato1=$result->fields[0];
												$dato2=$result->fields[1];
												$dato3=$result->fields[2];
												$dato4=$result->fields[3];
												$dato5=$result->fields[4];
												$dato6=$result->fields[5];
												$dato7=$result->fields[6];
												$dato8=$result->fields[7];
                        $dato9=$result->fields[8];
                        /*SEGUNDA(A) TABLA DE CONSULTA*/
												$dato10=$result->fields[18];
                        $dato11=$result->fields[19];
                        /*SEGUNDA Y TERCERA TABLA DE PARTO Y PATOLOGIA*/
                        $segunda1=$result->fields[9];
                        $segunda2=$result->fields[10];
												$segunda3=$result->fields[11];
												$segunda4=$result->fields[12];
                        /*FINAl SEGUNDA TABLA DE PARTO Y PATOLOGIA*/
												/* TABLA DE TESTEO DE SILVERMAN */
                        $tercera1 =$result->fields[13];
                        $tercera2=$result->fields[14];
												$tercera3=$result->fields[15];
												$tercera4=$result->fields[16];
												$tercera5=$result->fields[17];
												/*FINAl  TABLA SILVERMAN*/
                        $dato12=$result->fields[20];
												$result->MoveNext();
					              //$i++;
			                }
											//$this->salida.= '';
											$this->salida.=ThemeAbrirTablaSubModulo('Datos del Parto');
											$this->salida.= "<table border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
										  $this->salida.= "<tr>";
											$this->salida.= "<td align=\"center\">";
										//	$this->salida.=ThemeAbrirTablaSubModulo('Datos del Parto');
											$this->salida.= "<table border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
											$this->salida.= "<tr>";
											$this->salida.= "  <td align=\"center\">";
											$this->salida.= "    <table border=\"1\"  class=\"hc_table_submodulo_list\">";
											$this->salida.= "      <tr class=\"hc_table_submodulo_list_title\">";
											$this->salida.= "           <td align=\"center\">";
											$this->salida.= "           <label>Establecimiento</label>";
											$this->salida.= "           </td>";
											$this->salida.= "           <td>";
											$this->salida.= "            <label>Embarazo Deseado</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Adaptacion</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Placenta</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Medicamento</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>liquido Amniotico</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Pinzamiento Cordon</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Atencion Prenatal</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Muerte del Feto</label>";
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.= "            <label>Presentacion</label>";
											$this->salida.= "            </td>";
											$this->salida.= "     </tr>";
											$this->salida.= "     <tr  class=\"hc_submodulo_list_oscuro\">";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato1;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato2;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato3;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato4;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato5;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato6;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato7;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato8;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato9;
											$this->salida.= "            </td>";
											$this->salida.= "            <td align=\"center\">";
											$this->salida.=             $dato12;
											$this->salida.=             "</td>";
											//$this->salida.= "</tr>";
											$this->salida.= "     </tr>";
											$this->salida.= "    </table>";
											$this->salida.= "   </td>";
											$this->salida.= " </tr>";

											$this->salida.= "<tr>";
											$this->salida.= "<td>";

											$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
											$this->salida.= "  <tr><td colspan=\"2\">&nbsp;</td></tr>";
											$this->salida.= "  <tr>";
											$this->salida.= "  <td align=\"center\">";

											$this->salida.= "    <table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
											$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
											$this->salida.= "      <td align=\"center\">";
											$this->salida.= "      <label>Atencion de la Madre</label>";
											$this->salida.= "      </td>";
											$this->salida.= "      <td align=\"center\">";
											$this->salida.= "      <label>Atencion del Recien nacido</label>";
											$this->salida.= "      </td>";
											$this->salida.= "  </tr>";
											$this->salida.= " <tr class=\"hc_submodulo_list_oscuro\">";
											$this->salida.= "      <td align=\"center\">";
											$this->salida.= "      <label>$dato10</label>";
											$this->salida.= "      </td>";
											$this->salida.= "      <td align=\"center\">";
											$this->salida.= "      <label>$dato11</label>";
											$this->salida.= "      </td>";
											$this->salida.= "  </tr>";
											$this->salida.= "</table>";
											$this->salida.= "</td>";
											$this->salida.= "</tr>";
											$this->salida.= "</table>";
											$this->salida.= "</td>";
											$this->salida.= "</tr>";

											$this->salida.= "<tr>";
											$this->salida.= "<td>";
											if(($segunda1=="Normal") or ($segunda1=="normal"))
											{
													$this->salida.= "<table border=\"0\"  width=\"25%\" align=\"center\" class=\"hc_table_submodulo\">";
													$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
													$this->salida.= "<tr>";
													$this->salida.= "<td align=\"center\">";
													$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
													$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
													$this->salida.= "<td align=\"center\"width=\"20%\" >";
													$this->salida.= "<label>Parto</label>";
													$this->salida.= "</td>";
													$this->salida.= "</tr>";
													$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
													$this->salida.= "<td align=\"center\">";
													$this->salida.= "<label>$segunda1</label>";
													$this->salida.= "</td>";
													$this->salida.= "</tr>";
													$this->salida.= "</table>";
													$this->salida.= "</td>";
													$this->salida.= "</tr>";
													$this->salida.= "</table>";
//      $this->salida.=  "</td>";
										}
										else
										{
												$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
												$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
												$this->salida.= "<tr>";
												$this->salida.= "<td align=\"center\">";
												$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
												$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
												$this->salida.= "<td align=\"center\" width=\"13%\">";
												$this->salida.= "<label>Parto</label>";
												$this->salida.= "</td>";
												$this->salida.= "<td align=\"center\" >";
												$this->salida.= "<label>Causas</label>";
												$this->salida.= "</td>";
												$this->salida.= "</tr>";
												$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
												$this->salida.= "<td align=\"center\">";
												$this->salida.= "<label>$segunda1</label>";
												$this->salida.= "</td>";
												$this->salida.= "<td align=\"center\">";
												$this->salida.= "<label>$segunda2</label>";
												$this->salida.= "</td>";
												$this->salida.= "</tr>";
												$this->salida.= "</table>";
												$this->salida.=  "</td>";
												$this->salida.= "</tr>";
												$this->salida.= "</table>";
										}
										$this->salida.= "</td>";
										$this->salida.= "</tr>";

										$this->salida.= "<tr>";
										$this->salida.= "<td>";
										if(($segunda3=="No") or ($segunda1=="no"))
										{
												$this->salida.= "<table border=\"0\"  width=\"25%\" align=\"center\" class=\"hc_table_submodulo\">";
												$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
												$this->salida.= "<tr>";
												$this->salida.= "<td align=\"center\">";
												$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
												$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
												$this->salida.= "<td align=\"center\" width=\"10%\">";
												$this->salida.= "<label>Patologias</label>";
												$this->salida.= "</td>";
												$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
												$this->salida.= "<td align=\"center\">";
												$this->salida.= "<label>$segunda3</label>";
												$this->salida.= "</td>";
												$this->salida.= "</tr>";
												$this->salida.= "</table>";
												$this->salida.=  "</td>";
												$this->salida.= "</tr>";
												$this->salida.= "</table>";
									}
									else
									{
										$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
										$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
										$this->salida.= "<tr>";
										$this->salida.= "<td align=\"center\">";
										$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
										$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
										$this->salida.= "<td align=\"center\" width=\"10%\">";
										$this->salida.= "<label>Patologias</label>";
										$this->salida.= "</td>";
										$this->salida.= "<td align=\"center\">";
										$this->salida.= "<label>Causas Patologias</label>";
										$this->salida.= "</td>";
										$this->salida.= "</tr>";
										$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
										$this->salida.= "<td align=\"center\">";
										$this->salida.= "<label>$segunda3</label>";
										$this->salida.= "</td>";
										$this->salida.= "<td align=\"center\">";
										$this->salida.= "<label>$segunda4</label>";
										$this->salida.= "</td>";
										$this->salida.= "</tr>";
										$this->salida.= "</table>";
										$this->salida.=  "</td>";
										$this->salida.= "</tr>";
										$this->salida.= "</table>";
									}
										$this->salida.= "</td>";
										$this->salida.= "</tr>";

										$this->salida.= "<tr>";
										$this->salida.= "<td>";
										$this->salida.= "<p>&nbsp;</p>";
										$this->salida.= "<table width=\"100%\" border=\"1\" align=\"center\"  class=\"hc_table_submodulo_list\">";
										$this->salida.=   "<tr class=\"label\" > ";
										$this->salida.=  " <td class=\"hc_table_submodulo_list_title\" width=\"35%\" rowspan=\"6\"  align=\"center\">Silverman</td>";
										$this->salida.=   "</tr>";
										$this->salida.=   "<tr class=\"hc_submodulo_list_oscuro\"> ";
										$this->salida.=  " <td  class=\"label\" height=\"22\">Aleteo </td>";
										$this->salida.=  " <td width=\"12%\" height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"text\" name=\"aleteo\" value=\"$tercera1\" READONLY></td>";
										$this->salida.=   "</tr>";
										$this->salida.=   "<tr class=\"hc_submodulo_list_claro\" > ";
										$this->salida.=  " <td  class=\"label\" height=\"22\">Tiajes </td>";
										$this->salida.=  " <td  width=\"2%\" height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"text\" name=\"tiaje\" value=\"$tercera2\" READONLY></td>";
										$this->salida.=   "</tr>";
										$this->salida.=   "<tr class=\"hc_submodulo_list_oscuro\" > ";
										$this->salida.=  " <td  class=\"label\" height=\"22\">Disbalance toracoabdominal </td>";
										$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\"class=\"input-text\" type=\"text\" name=\"disbalance\" value=\"$tercera3\" READONLY></td>";
										$this->salida.=   "</tr>";
										$this->salida.=   "<tr class=\"hc_submodulo_list_claro\" > ";
										$this->salida.=  " <td  class=\"label\" height=\"22\">Quejido </td>";
										$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"text\" name=\"quejido\" value=\"$tercera4\"  READONLY></td>";
										$this->salida.=   "</tr>";
										$this->salida.=   "<tr class=\"hc_submodulo_list_oscuro\" > ";
										$this->salida.=  " <td  class=\"label\" height=\"22\">Retracciones </td>";
										$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"text\" name=\"retracciones\" value=\"$tercera5\" READONLY></td>";
										$this->salida.=   "</tr>";
										$this->salida.="</table>";
										$this->salida.= "</td>";
										$this->salida.= "</tr>";


										$this->salida.= "</table>";


										$this->salida.= "</td>";
										$this->salida.= "</tr>";

										$this->salida.= "<tr>";
										$this->salida.= "<td colspan=\"7\">&nbsp;<br></td>";
										$this->salida.= "</tr>";

										$this->salida.= "<tr>";
										$this->salida.= "<td colspan=\"7\" class=\"hc_table_title\">Datos Recien Nacido</td>";
										$this->salida.= "</tr>";

					/*AQUI EMPIEZA LOS DATOS DEL RECIEN NACIDOS*/
          /*AQUI COMIENZA LA TABLA DE RECIEN NACIDO */
										$resulta=$this->ConsultaPerinatalnacido();
									  while (!$resulta->EOF)
											{
												/*PRIMERA TABLA DE CONSULTA*/
													$dato1=$resulta->fields[0];
													$dato2=$resulta->fields[1];
													$dato3=$resulta->fields[2];
													$dato4=$resulta->fields[3];
													$dato5=$resulta->fields[4];
													$dato6=$resulta->fields[5];
													$dato7=$resulta->fields[6];
													//$dato8=$resulta->fields[7];
												/*SEGUNDA TABLA DE CONSULTA*/
													$seg1=$resulta->fields[7];
													$seg2=$resulta->fields[8];
													$Identificador=$resulta->fields[9];
												/*CUARTA TABLA DE CONSULTA*/
													$seg3=$resulta->fields[10];
													$seg4=$resulta->fields[11];
												/*QUINTA TABLA DE CONSULTA*/
													$seg5=$resulta->fields[12];
													$seg6=$resulta->fields[13];
												/*QUINTA TABLA DE CONSULTA*/
													$seg7=$resulta->fields[14];
													$seg8=$resulta->fields[15];
													$resulta->MoveNext();
													//$i++;
										}

										$this->salida.= "<tr>";
										$this->salida.= "<td align=\"center\">";
								//	$this->salida .= "<p>&nbsp;</p>";
							//		$this->salida .= ThemeAbrirTablaSubModulo('Datos Recien Nacido');
									$this->salida.="<br><table width=\"95%\" border=\"1\" align=\"center\"  class=\"hc_table_submodulo_list\" >";
									$this->salida.= "<br><tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.= "       <td align=\"center\" >";
									$this->salida.= "       <label>Sufrimiento Fetal</label>";
									$this->salida.= "       </td>";
									$this->salida.= "       <td>";
									$this->salida.= "       <label>Edad Gestacional</label>";
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.= "       <label>Peso</label>";
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.= "       <label>Talla</label>";
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.= "       <label>Perimetro Cefalico</label>";
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.= "       <label>Diuresis</label>";
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.= "       <label>Deposición</label>";
									$this->salida.= "       </td>";
									$this->salida.= "  </tr>";
									$this->salida.= "  <tr  class=\"hc_submodulo_list_oscuro\">";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato1;
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato2;
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato3;
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato4;
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato5." cms";
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato6;
									$this->salida.= "       </td>";
									$this->salida.= "       <td align=\"center\">";
									$this->salida.=        $dato7;
									$this->salida.= "       </td>";
									$this->salida.= "  </tr>";
									//$this->salida.= "</tr>";
									//$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";


									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
									$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
									$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>Apgar al minuto</label>";
									$this->salida.= "</td>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>Apgar a los 5 minuto</label>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>$seg1</label>";
									$this->salida.= "</td>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>$seg2</label>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";

									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";
									//	$this->salida.=  "</td>";
												//    $this->salida.= "</tr>";

                  $res=$this->ConsultaReanimacion($Identificador);
           /* AQUI VA LO DE REANIMACION *******************************************/
									$vars="";
									$nom1="";
									$i=0;
									while (!$res->EOF)
									{
												$vars[$i]=$res->fields[0];
												$i++;
												$res->MoveNext();
									}
									$res->Close();
									$d=sizeof($vars)+1;
									$this->salida.= "<br><table width=\"45%\" border=\"1\" align=\"center\"  class=\"hc_table_submodulo_list\">";
									$this->salida.=    "<tr> ";
									$this->salida.=   " <td class=\"hc_table_submodulo_list_title\" width=\"35%\" rowspan=\"$d\"  align=\"center\"  class=\"label\">Reanimacion</td>";
									$this->salida.=    "</tr>";

									for($i=0;$i<sizeof($vars);$i++)
									{
											$this->salida.=   "<tr> ";
											if($i % 2){ $estilo='hc_submodulo_list_claro';}
											else { $estilo='hc_submodulo_list_oscuro'; }
											$this->salida.=   " <td width=\"1%\" class=\"$estilo\" class=\"label\">&nbsp;&nbsp;$vars[$i]</td>";
											$this->salida.=    "</tr>";
									}
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";

									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";

									$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
									$this->salida.= "   <tr><td colspan=\"2\">&nbsp;</td></tr>";
									$this->salida.= "   <tr>";
									$this->salida.= "    <td align=\"center\">";
									$this->salida.= "      <table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
									$this->salida.= "        <tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.= "         <td align=\"center\">";
									$this->salida.= "         <label>Permeabilidad Ano</label>";
									$this->salida.= "         </td>";
									$this->salida.= "         <td align=\"center\">";
									$this->salida.= "         <label>Permeabilidad Esofago</label>";
									$this->salida.= "         </td>";
									$this->salida.= "       </tr>";
									$this->salida.= "       <tr class=\"hc_submodulo_list_oscuro\">";
									$this->salida.= "         <td align=\"center\">";
									$this->salida.= "         <label>$seg3</label>";
									$this->salida.= "         </td>";
									$this->salida.= "         <td align=\"center\">";
									$this->salida.= "         <label>$seg4</label>";
									$this->salida.= "         </td>";
									$this->salida.= "       </tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";

									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";

									$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
									$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
									$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>Alimentación Recien nacido</label>";
									$this->salida.= "</td>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>Peso - Talla/Egreso</label>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>$seg5</label>";
									$this->salida.= "</td>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>$seg6</label>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";

									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<table border=\"0\"  width=\"100%\" class=\"hc_table_submodulo\">";
									$this->salida.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
									$this->salida.= "<tr>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<table border=\"1\"  width=\"80%\" class=\"hc_table_submodulo_list\">";
									$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>Egreso de la Madre</label>";
									$this->salida.= "</td>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>Muerte Materna</label>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "<tr class=\"hc_submodulo_list_oscuro\">";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>$seg7</label>";
									$this->salida.= "</td>";
									$this->salida.= "<td align=\"center\">";
									$this->salida.= "<label>$seg8</label>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida.= "</td>";
									$this->salida.= "</tr>";

								//	$this->salida.= "</table>";
								//	$this->salida .= ThemeCerrarTablaSubModulo();
								//	return true;
								//	$this->salida.= "</td>";
								//	$this->salida.= "</tr>";
									$this->salida.= "</table>";
									$this->salida .= ThemeCerrarTablaSubModulo();
									return true;

		//-----------------------------------
					/*AQUI EMPIEZA LOS DATOS DEL RECIEN NACIDOS*/
          /*AQUI COMIENZA LA TABLA DE RECIEN NACIDO */


									$resulta =ConsultaPerinatalnacido();
									if ($conn->ErrorNo() != 0)
									{
                      return false;
									}
									else
									{
                      while (!$resulta->EOF)
											{
													/*PRIMERA TABLA DE CONSULTA*/
														$dato1=$resulta->fields[0];
														$dato2=$resulta->fields[1];
														$dato3=$resulta->fields[2];
														$dato4=$resulta->fields[3];
														$dato5=$resulta->fields[4];
														$dato6=$resulta->fields[5];
														$dato7=$resulta->fields[6];
														//$dato8=$resulta->fields[7];
													/*SEGUNDA TABLA DE CONSULTA*/
														$seg1=$resulta->fields[7];
														$seg2=$resulta->fields[8];
														$Identificador=$resulta->fields[9];
													/*CUARTA TABLA DE CONSULTA*/
														$seg3=$resulta->fields[10];
														$seg4=$resulta->fields[11];
													/*QUINTA TABLA DE CONSULTA*/
														$seg5=$resulta->fields[12];
														$seg6=$resulta->fields[13];
													/*QUINTA TABLA DE CONSULTA*/
														$seg7=$resulta->fields[14];
														$seg8=$resulta->fields[15];
													/*	$busqueda="select b.tipo
																from hc_aux_rnacidos_reanimacion as a,hc_tipo_rnacidos_reanimacion as b where
																hc_antecedente_perinatal_id='$Identificador' and
																a.tipo_nacido_reanimacion_id=b.tipo_nacido_reanimacion_id;";
												*/		$resulta->MoveNext();
														//$i++;
										}
							}
       //   $this->salida .= "<p>&nbsp;</p>";
		//-----------------------------

	}//fin if
		else
		{

		    $this->salida .= ThemeAbrirTablaSubModulo('CONSULTAS');
			 // $this->salida .= ThemeCerrarTablaSubModulo();
				//$this->salida .= "</table";
				$this->salida .= "<table align='center'>";
				$this->salida .= "<tr>";
				$this->salida .= "<td class=\"label\">NO HAY CONSULTA DISPONIBLE</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				$this->salida .= ThemeCerrarTablaSubModulo();
				return true;
		}
}


  function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}





	/**
* Funcion que muestra en pantalla del formulario en donde se escribiran los datos del parto  y de recien nacido.
* @return boolean
*/
	function frmForma($apgar1)
	{
		$pfj=$this->frmPrefijo;
		$establecimiento =$this->ComboPartosEstablecimientos();
		$parto=$this->ComboPartosParto();
		$medicamentos=$this->ComboPartosMedicamentos();
		$liquido=$this->ComboLiquidoAmniotico();
		$presentacion=$this->ComboPartosPresentacion();
		$pinzamiento=$this->ComboPartosPinzamiento();
		$reanimacion=$this->ComboRnacidoReanimacion();

		$alimentacion=$this->ComboRnacidosAlimentacion();
		$egreso=$this->ComboRnacidosEgreso();
		$muertem=$this->ComboRnacidosMuerte();
		$fetal=$this->ComboRnacidosSufrimientoFetal();
		$pesotallaeg =$this->ComboRnacidosPesoTallaE();

		if(empty($this->titulo))
        {
			$this->salida  = ThemeAbrirTablaSubModulo('ANTECEDENTES PERINATALES');
        }
        else
        {
            $this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
        }

	    if($this->datosPaciente['sexo_id']=='F')
		{
					$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'insertar'));
          $this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
					$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
					$this->salida.=$this->SetStyle("MensajeError");
					$this->salida.="<tr><td class=\"hc_table_title\" colspan=\"3\">Datos del parto</td></tr>";
					$this->salida.="<tr>";
					$this->salida.="<td>";

					$this->salida.="<table  width=\"100%\" border=\"2\" align=\"center\">";
					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"4\">";


					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\" >";
					$this->salida.="<tr class=\"label\"  colspan=\"2\">";
					$this->salida.="<td  width=\"80%\">&nbsp;&nbsp;Atención Prenatal: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td  width=\"2%\"><input type=\"radio\" name=\"prenatal".$pfj."\" value=\"1\" checked></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"prenatal".$pfj."\" value=\"0\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\">";
					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td  width=\"78%\">&nbsp;&nbsp;Embarazo Deseado: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td   width=\"2%\"><input type=\"radio\" name=\"deseado".$pfj."\" value=\"1\" checked></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"deseado".$pfj."\" value=\"0\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.= "</td>";
					$this->salida.= "</tr>";

					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td width=\"35%\" colspan=\"2\">";
					$this->salida.="<table width=\"86%\" border=\"0\" align=\"left\">";
					$this->salida.=" <tr>";
					$this->salida.="   <td  class=\"".$this->SetStyle("causapato")."\" width=\"90%\">&nbsp;&nbsp;Patologias de la madre durante el embarazo: </td>";
					$this->salida.="   <td class=\"label\">Si</td>";
					$this->salida.="   <td bgcolor=\".$this->color\"  width=\"2%\"><input type=\"radio\" name=\"patologia".$pfj."\" value=\"1\" ></td>";
					$this->salida.="   <td class=\"label\">No</td>";
					$this->salida.="   <td  width=\"2%\" ><input type=\"radio\" name=\"patologia".$pfj."\" value=\"0\" checked ></td>";
					$this->salida.=" </tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\"> <textarea name=\"causapato".$pfj."\"class=\"textarea\"></textarea>";
					$this->salida.="</tr>";


					$this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones

					$this->salida.="<tr>";
					$this->salida.="<td width=\"35%\" class=\"label\">&nbsp;&nbsp;Establecimiento donde nacio: </td>";
					$this->salida.=" <td  align=\"left\" colspan=\"2\">";
					$this->salida.="<select name=\"establecimiento".$pfj."\"  class=\"select\">";
  				$vars="";
				  while (!$establecimiento->EOF) {
							$vars[$establecimiento->fields[0]]=$establecimiento->fields[1];
							$establecimiento->MoveNext();
					}
         $establecimiento->Close();
				  foreach($vars as $id=>$tipo){
               $this->salida.=" <option value=\"$id\">$tipo</option>";
          }
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"".$this->SetStyle("Parto")."\">&nbsp;&nbsp;&nbsp;Parto: </td>";
					$this->salida.=" <td width=\"20%\" align=\"left\">";
					$this->salida.=" <select name=\"parto".$pfj."\" align=\"left\"  class=\"select\">";
         $vars="";
					while (!$parto->EOF) {
							$vars[$parto->fields[0]]=$parto->fields[1];
							$parto->MoveNext();
					}
         $parto->Close();
				  foreach($vars as $id1=>$tparto){
             $this->salida.=" <option value=\"$id1\">$tparto</option>";
         }
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="<td width=\"30%\" align=\"center\">";
					$this->salida.="<textarea name=\"causaparto".$pfj."\"  class=\"textarea\"></textarea>";
					$this->salida.="</td>";
					$this->salida.="</tr>";


          $this->salida.= "<tr>";
					$this->salida.="<td width=\"35%\" class=\"label\">&nbsp;&nbsp;&nbsp;Placenta: </td>";
					$this->salida.="<td align=\"left\" colspan=\"2\">";
					$this->salida.="<select name=\"placenta".$pfj."\" class=\"select\">";
					$this->salida.="<option value=\"Normal\">Normal</option>";
          $this->salida.="<option value=\"Anormal\">Anormal</option>";
					$this->salida.=" </select>";
					$this->salida.="</td>";
          $this->salida.= "</tr>";
          $this->salida.= "<tr>";
					$this->salida.="<td width=\"35%\" class=\"label\">&nbsp;&nbsp;&nbsp;Adaptacion: </td>";
					$this->salida.="<td align=\"left\" colspan=\"2\">";
					$this->salida.="<select name=\"adaptacion".$pfj."\" class=\"select\">";
					$this->salida.="<option value=\"Espontanea\">Espontanea</option>";
          $this->salida.="<option value=\"Inducida\">Inducida</option>";
					$this->salida.=" </select>";
					$this->salida.="</td>";
          $this->salida.= "</tr>";
				  $this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Medicamento: </td>";
					$this->salida.="<td width=\"20%\" align=\"left\">";
					$this->salida.=" <select name=\"medicamento".$pfj."\" align=\"left\"  class=\"select\">";

         $vars="";
					while (!$medicamentos->EOF) {
							$vars[$medicamentos->fields[0]]=$medicamentos->fields[1];
					$medicamentos->MoveNext();
						}
         $medicamentos->Close();
				  foreach($vars as $id2=>$medi){
               $this->salida.=" <option value=\"$id2\">$medi</option>";
                }
					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Liquido Amniótico: </td>";
					$this->salida.="<td width=\"20%\" align=\"left\">";
					$this->salida.=" <select name=\"amniotico".$pfj."\" align=\"left\"  class=\"select\">";

					$vars="";
					while (!$liquido->EOF)
					{
							$vars[$liquido->fields[0]]=$liquido->fields[1];
							$liquido->MoveNext();
					}
					$liquido->Close();
				  foreach($vars as $id3=>$liq)
					{  $this->salida.=" <option value=\"$id3\">$liq</option>";  }

					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
          $this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Presentacion: </td>";
					$this->salida.="<td width=\"20%\" align=\"left\">";
					$this->salida.=" <select name=\"presentacion".$pfj."\" align=\"left\"  class=\"select\">";

					$vars="";
					while (!$presentacion->EOF)
					{
							$vars[$presentacion->fields[0]]=$presentacion->fields[1];
							$presentacion->MoveNext();
					}
					$presentacion->Close();
				  foreach($vars as $id7=>$pre)
					{  $this->salida.=" <option value=\"$id7\">$pre</option>"; }
					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
          /* AQUI VA EL INICIO DE PINZAMIENTO DEL CORDON  *******************************************/
          $vars="";
					$this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Pinzamiento del Cordon: </td>";
					$this->salida.="<td width=\"35%\" align=\"left\">";
					$this->salida.=" <select name=\"pinzamiento".$pfj."\" align=\"left\"  class=\"select\">";

					$vars="";
					while (!$pinzamiento->EOF)
					{
							$vars[$pinzamiento->fields[0]]=$pinzamiento->fields[1];
							$pinzamiento->MoveNext();
					}
          $pinzamiento->Close();

				  foreach($vars as $pin=>$za)
					{  $this->salida.=" <option value=\"$pin\">$za</option>";  }

					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
          /* AQUI VA EL FINAL DE PINZAMIENTO DEL CORDON*******************************************/
          $this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\">";
					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td  width=\"78%\">&nbsp;&nbsp;Muerte Fetal en el Parto: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td   width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"feto".$pfj."\" value=\"1\"></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" ><input type=\"radio\" name=\"feto".$pfj."\" value=\"0\" checked></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.= "</td>";
					$this->salida.= "</tr>";
          $this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones

          /*TESTEO DE SILVERMAN -----------------------------------------------------------------*/
					$this->salida.=   "<tr  valing=\"center\" align=\"center\"> ";
					$this->salida.=  " <td colspan=\"3\">";

					$this->salida .= "<table width=\"80%\" border=\"0\" align=\"center\">";
					$this->salida.=   "<tr class=\"label\" > ";
					$this->salida.=  " <td width=\"85%\" align=\"right\">0&nbsp;&nbsp;&nbsp;</td>";
					$this->salida.=  " <td width=\"8%\" align=\"center\">1</td>";
					$this->salida.=  " <td width=\"8%\" align=\"center\">2</td>";
					$this->salida.=   "</tr>";
					$this->salida.="</table>";


					$this->salida .= "<table width=\"80%\" border=\"1\" align=\"center\">";
					$this->salida.=   "<tr class=\"label\" > ";
					$this->salida.=  " <td class=\"hc_table_list_title\" width=\"35%\" rowspan=\"6\"  align=\"center\">Silverman</td>";
					$this->salida.=   "</tr>";
					$this->salida.=   "<tr class=\"hc_submodulo_list_oscuro\"> ";
					$this->salida.=  " <td  class=\"".$this->SetStyle("aleteo")."\" height=\"22\">Aleteo </td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"aleteo".$pfj."\" value=\"0\" checked></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"aleteo".$pfj."\" value=\"1\"></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"aleteo".$pfj."\" value=\"2\" ></td>";
					$this->salida.=   "</tr>";
					$this->salida.=   "<tr class=\"hc_submodulo_list_claro\"> ";
					$this->salida.=  " <td class=\"".$this->SetStyle("tiaje")."\" height=\"22\">Tiajes </td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"tiaje".$pfj."\" value=\"0\" checked></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"tiaje".$pfj."\" value=\"1\"></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"tiaje".$pfj."\" value=\"2\" ></td>";
					$this->salida.=   "</tr>";
					$this->salida.=   "<tr class=\"hc_submodulo_list_oscuro\"> ";
					$this->salida.=  " <td class=\"".$this->SetStyle("disbalance")."\" height=\"22\">Disbalance toracoabdominal </td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\"class=\"input-text\" type=\"radio\" name=\"disbalance".$pfj."\" value=\"0\" checked></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"disbalance".$pfj."\" value=\"1\"></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"disbalance".$pfj."\" value=\"2\" ></td>";
					$this->salida.=   "</tr>";
					$this->salida.=   "<tr class=\"hc_submodulo_list_claro\"> ";
					$this->salida.=  " <td  class=\"".$this->SetStyle("quejido")."\" height=\"22\">Quejido </td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"quejido".$pfj."\" value=\"0\" checked></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"quejido".$pfj."\" value=\"1\"></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"quejido".$pfj."\" value=\"2\" ></td>";
					$this->salida.=   "</tr>";
					$this->salida.=   "<tr class=\"hc_submodulo_list_oscuro\"> ";
					$this->salida.=  " <td class=\"".$this->SetStyle("retracciones")."\" height=\"22\">Retracciones </td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"retracciones".$pfj."\" value=\"0\" checked></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"retracciones".$pfj."\" value=\"1\"></td>";
					$this->salida.=  " <td height=\"22\"><input maxlength=\"1\" size=\"2\" class=\"input-text\" type=\"radio\" name=\"retracciones".$pfj."\" value=\"2\" ></td>";
					$this->salida.=   "</tr>";
					$this->salida.="</table>";
					$this->salida.=   "</td>";
					$this->salida.=   "</tr>";
          /* FIN DEL TESTEO DE SILVERMAN -----------------------------------------------------------------*/
					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\"><br>";
					$this->salida.="<br>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td>";
					$this->salida .= "<table width=\"60%\" border=\"1\" align=\"right\">";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td  class=\"hc_table_list_title\" rowspan=\"7\"  width=\"5%\" class=\"label\">ATENCION: </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"left\">";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\" class=\"label\">Medico </td>";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$this->salida.="<input type=\"radio\" name=\"atencion".$pfj."\" value=\"medico\" checked>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"left\">";
					$this->salida.="<td  class=\"hc_submodulo_list_claro\" class=\"label\">Enfermera </td>";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$this->salida.="<input type=\"radio\" name=\"atencion".$pfj."\" value=\"enfermera\">";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"left\">";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\" class=\"label\">Auxiliar </td>";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$this->salida.="<input type=\"radio\" name=\"atencion".$pfj."\" value=\"auxiliar\">";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"left\">";
					$this->salida.="<td class=\"hc_submodulo_list_claro\" class=\"label\">Partera </td>";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$this->salida.="<input type=\"radio\" name=\"atencion".$pfj."\" value=\"partera\">";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"left\">";
					$this->salida.= "<td class=\"hc_submodulo_list_oscuro\" class=\"label\">Promotor </td>";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$this->salida.= "<input type=\"radio\" name=\"atencion".$pfj."\" value=\"promotor\">";
					$this->salida.="</td>";
					$this->salida.=" </tr>";
					$this->salida.="<tr align=\"left\">";
					$this->salida.= "<td class=\"hc_submodulo_list_claro\" class=\"label\">Otro </td>";
					$this->salida.="<td  class=\"hc_submodulo_list_oscuro\" bgcolor=\".$this->color\">";
					$this->salida.= "<input type=\"radio\" name=\"atencion".$pfj."\" value=\"otro\">";
					$this->salida.="</td>";
					$this->salida.=" </tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="<td colspan=\"2\">";
					$this->salida .= "<table width=\"40%\" border=\"1\" align=\"center\">";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td  class=\"hc_table_list_title\" rowspan=\"7\"  width=\"5%\" class=\"label\">ATENCION <br> RECIEN NACIDO: </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td  class=\"hc_submodulo_list_oscuro\" class=\"label\">Medico </td>";
					$this->salida.="<td  class=\"hc_submodulo_list_oscuro\">";
					$this->salida.="<input type=\"radio\" name=\"atencionr".$pfj."\" value=\"medico\"checked>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td  class=\"hc_submodulo_list_claro\" class=\"label\">Enfermera </td>";
					$this->salida.="<td class=\"hc_submodulo_list_claro\">";
					$this->salida.="<input type=\"radio\" name=\"atencionr".$pfj."\" value=\"enfermera\">";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td  class=\"hc_submodulo_list_oscuro\" class=\"label\">Auxiliar </td>";
					$this->salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$this->salida.="<input type=\"radio\" name=\"atencionr".$pfj."\" value=\"auxiliar\">";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.="<td  class=\"hc_submodulo_list_claro\"  class=\"label\">Partera </td>";
					$this->salida.="<td  class=\"hc_submodulo_list_claro\">";
					$this->salida.="<input type=\"radio\" name=\"atencionr".$pfj."\" value=\"partera\">";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"  class=\"label\">Promotor </td>";
					$this->salida.="<td  class=\"hc_submodulo_list_oscuro\">";
					$this->salida.= "<input type=\"radio\" name=\"atencionr".$pfj."\" value=\"promotor\">";
					$this->salida.="</td>";
					$this->salida.=" </tr>";
					$this->salida.="<tr align=\"center\">";
					$this->salida.= "<td  class=\"hc_submodulo_list_claro\"  class=\"label\">Otro </td>";
					$this->salida.="<td  class=\"hc_submodulo_list_claro\" bgcolor=\".$this->color\" >";
					$this->salida.= "<input type=\"radio\" name=\"atencionr".$pfj."\" value=\"otro\">";
					$this->salida.="</td>";
					$this->salida.=" </tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				  $this->salida.="</td>";
					$this->salida.="</tr>";
          $this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
          /*Aqui empieza el formato de recien nacido*/
          $this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\">";
					$this->salida.="<tr><td class=\"hc_table_title\">Datos Recien Nacido</td></tr>";
					$this->salida.="<tr>";
					$this->salida.="<td>";
					$this->salida.="<table width=\"550\" border=\"0\" align=\"center\"class=\"label\" >";
					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"4\">";
				  $this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
					$this->salida.=" <table width=\"100%\" border=\"0\">";
				 /* AQUI VA  INICIO DE SUFRIMIENTO FETAL *******************************************/
          $this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;Sufrimiento Fetal: </td>";
					$this->salida.="<td width=\"35%\" align=\"left\">";
					$this->salida.=" <select name=\"sufrimiento".$pfj."\" align=\"left\"  class=\"select\">";
					$vars="";
					while (!$fetal->EOF)
					{
							$vars[$fetal->fields[0]]=$fetal->fields[1];
							$fetal->MoveNext();
					}
          $fetal->Close();

				  foreach($vars as $fe=>$to)
					{  $this->salida.=" <option value=\"$fe\">$to</option>";  }

					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
          /* AQUI VA EL FINAL DE SUFRIMIENTO FETAL*******************************************/

  				$this->salida.="<tr> ";
    			$this->salida.="<td width=\"35%\" class=\"label\">&nbsp;&nbsp;Edad gestacional al nacer</td>";
          $this->salida.=" <td width=\"20%\" align=\"lef\">";
					$this->salida.="<select name=\"edadgest".$pfj."\" align=\"left\" class=\"select\">";
					$this->salida.="<option value=\"Menos de 37 semanas\">Menos de 37 semanas</option>";
					$this->salida.="<option value=\"38-40 semanas\" selected>38-40 semanas</option>";
					$this->salida.="<option value=\"M&aacute;s de 41 semanas\">M&aacute;s de 41 ";
					$this->salida.="semanas</option>";
					$this->salida.="<option value=\"No sabe\">No sabe</option>";
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="<td></td>";
					$this->salida.="</tr>";
					$this->salida.="<tr> ";
					$this->salida.="<td width=\"35%\" class=\"label\">&nbsp;&nbsp;Peso al nacer: </td>";
					$this->salida.="<td align=\"left\" colspan=\"2\">";
					$this->salida.="<select name=\"peso".$pfj."\" class=\"select\">";
					$this->salida.="<option value=\"Menos de 1500 GRS\">Menos de 1500 GRS</option>";
					$this->salida.=" <option value=\"Entre 1501-2500 GRS\">Entre 1501-2500 GRS </option>";
					$this->salida.="<option value=\" Entre 2501-3500 GRS\" selected>Entre 2501-3500 GRS</option>";
					$this->salida.="<option value=\" Entre 3501-4000 GRS \">Entre 3501-4000 GRS</option>";
					$this->salida.="   <option value=\"Mas de 4000 GRS \">Mas de 4000 GRS </option>";
					$this->salida.=" </select>";
					$this->salida.="</td>";
					$this->salida.= "</tr>";
					$this->salida.="<tr> ";
					$this->salida.="<td width=\"35%\" class=\"label\">&nbsp;&nbsp;Talla:</td>";
					$this->salida.="<td width=\"20%\" align=\"left\"> ";
					$this->salida.="<select name=\"talla".$pfj."\" align=\"left\" class=\"select\">";
					$this->salida.="<option value=\"Menos de 48 CMS\">Menos de 48 CMS</option>";
					$this->salida.="<option value=\"48-52\" selected>48-52</option>";
					$this->salida.="<option value=\"Mas de 52\">Mas de 52</option>";
					$this->salida.="</select>";
					$this->salida.= "</td>";
					$this->salida.="<td width=\"30%\" align=\"center\">&nbsp;</td>";
					$this->salida.="</tr>";
					$this->salida.= "<tr>";
          $this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
					/*AQUI COMIENZA LO DEL APGAR DEL NIÑO*/
					$this->salida.= "<td width=\"35%\" height=\"40\" class=\"".$this->SetStyle("apgar1")."\">&nbsp;&nbsp;Apgar al nacer al minuto</td>";
					$this->salida.="<td width=\"20%\" align=\"center\" height=\"40\"> ";
					$this->salida.="<div align=\"left\"> ";
					$this->salida.= "<input type=\"text\" size=\"2\" maxlength=\"2\" name=\"apgar1".$pfj."\" class=\"input-text\">";
					$this->salida.= "   </div>";
					$this->salida.=  " </td>";
					$this->salida.=   " <td width=\"30%\" align=\"center\" height=\"40\">&nbsp;</td>";
					$this->salida.=   "</tr>";
					$this->salida.= "<tr>";
					$this->salida.= "<td width=\"35%\" height=\"40\" class=\"".$this->SetStyle("apgar5")."\">&nbsp;&nbsp;Apgar al nacer a los 5 minutos</td>";
					$this->salida.="<td width=\"20%\" align=\"center\" height=\"40\"> ";
					$this->salida.="<div align=\"left\"> ";
					$this->salida.= "<input type=\"text\" size=\"2\" maxlength=\"2\" name=\"apgar5".$pfj."\" class=\"input-text\">";
					$this->salida.= "   </div>";
					$this->salida.=  " </td>";
					$this->salida.=   " <td width=\"30%\" align=\"center\" height=\"40\">&nbsp;</td>";
					$this->salida.=   "</tr>";
          /*AQUI TERMINA LO DEL APGAR DEL NIÑO*/
					$this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
          /* AQUI VA LO DE REANIMACION *******************************************/
					$vars="";
			    while (!$reanimacion->EOF)
					{
					  		$vars[$reanimacion->fields[0]]=$reanimacion->fields[1];
				      	$reanimacion->MoveNext();
					}
					$reanimacion->Close();
					$d=sizeof($vars)+1;

					$this->salida.=   "<tr valing=\"center\" align=\"center\"> ";
					$this->salida.=  " <td colspan=\"3\">";
					$this->salida .= "<table width=\"45%\" border=\"1\" align=\"center\">";
					$this->salida.=   "<tr> ";
					$this->salida.=  " <td class=\"hc_table_list_title\" width=\"35%\" height=\"22\" rowspan=\"$d\"  align=\"center\"  class=\"label\">Reanimacion</td>";
					$this->salida.=   "</tr>";

					$spy=0;
					foreach($vars as $id5=>$nom1)
					{
              if($spy==0)
							{
                $estilo="hc_submodulo_list_oscuro";
                $spy=1;
							}
							else
							{
									$estilo="hc_submodulo_list_claro";
									$spy=0;
							}
							$this->salida.=   "<tr> ";
							$this->salida.=  " <td  class=$estilo width=\"1%\" height=\"22\"  class=\"label\">&nbsp;&nbsp;$nom1</td>";
							$this->salida.=  " <td class=\"hc_submodulo_list_oscuro\"  width=\"1%\" height=\"22\"><input type=\"checkbox\" name=\"reanimacion".$id5.$pfj."\" value=$id5></td>";
							$this->salida.=   "</tr>";
					}
					$this->salida.="</table>";
					$this->salida.=   "</td>";
					$this->salida.=   "</tr>";
         /* AQUI VA FIN DE  LO DE REANIMACION *******************************************/
          $this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
          /* INICIO DE DIURESIS Y DEPOSICION*/
          $this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\">";
					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td  width=\"78%\">&nbsp;&nbsp;Diuresis: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td   width=\"2%\"><input type=\"radio\" name=\"diuresis".$pfj."\" value=\"1\" checked></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"diuresis".$pfj."\" value=\"0\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
          $this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\">";
					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td  width=\"78%\">&nbsp;&nbsp;Deposicion: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td   width=\"2%\"><input type=\"radio\" name=\"deposicion".$pfj."\" value=\"1\" checked></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"deposicion".$pfj."\" value=\"0\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					/* FINAL  DE DIURESIS Y DEPOSICION*/
          /* INICIO DE PERMEABILIDAD*/
          $this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\">";
					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td  width=\"78%\">&nbsp;&nbsp;Permeabilidad Ano: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td   width=\"2%\"><input type=\"radio\" name=\"permeabilidadano".$pfj."\" value=\"1\" checked></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"permeabilidadano".$pfj."\" value=\"0\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
          $this->salida.="<tr>";
					$this->salida.="<td colspan=\"3\">";
					$this->salida.="<table width=\"60%\" border=\"0\" align=\"left\">";
					$this->salida.="<tr class=\"label\">";
					$this->salida.="<td  width=\"78%\">&nbsp;&nbsp;Permeabilidad Esofago: </td>";
					$this->salida.="<td>Si</td>";
					$this->salida.="<td   width=\"2%\"><input type=\"radio\" name=\"permeabilidadesofago".$pfj."\" value=\"1\" checked></td>";
					$this->salida.="<td>No</td>";
					$this->salida.="<td  width=\"2%\" bgcolor=\".$this->color\"><input type=\"radio\" name=\"permeabilidadesofago".$pfj."\" value=\"0\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					/* FINAL  DE PERMEABILIDAD*/
          $this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
					/*AQUI VA PERIMETROCEFALICO*/
					$this->salida.=   "<tr> ";
					$this->salida.=  " <td  class=\"".$this->SetStyle("perimetro")."\" width=\"35%\" height=\"22\">&nbsp;&nbsp;&nbsp;Perimetro Cefalico</td>";
					$this->salida.=  "  <td width=\"15%\" align=\"left\" height=\"22\"  class=\"label\"> ";
					$this->salida.=   "   <input type=\"text\" name=\"perimetro".$pfj."\" class=\"input-text\" maxlength=\"5\" size=\"5\">&nbsp;cms";
					$this->salida.=   " </td>";
					$this->salida.=  "  <td height=\"22\"></td>";
					$this->salida.=   "</tr>";
					$this->salida.=  "<tr align=\"center\" class=\"label\">";
					$this->salida.=   "  <td></td>";
					$this->salida.=   "  <td></td>";
					$this->salida.=    "</tr>";
					$this->salida.=    "<tr align=\"center\"> ";
					$this->salida.=     "  <td></td>";
					$this->salida.=   "  <td></td>";
					$this->salida.= "</tr>";
					$this->salida.= "<tr align=\"center\" class=\"label\"> ";
					$this->salida.=  "  <td></td>";
					$this->salida.=  "  <td></td>";
					$this->salida.=  "</tr>";
					$this->salida.=  " </td>";
					$this->salida.=  "</tr>";
          /*AQUI VA FINAL PERIMETRO CEFALICO*/
          /* AQUI VA  INICIO DE ALIMENTACION *******************************************/
          $this->salida.="<tr>";
					$this->salida.=" <td width=\"2%\" class=\"label\">&nbsp;&nbsp;&nbsp;Alimentacion: </td>";
					$this->salida.="<td width=\"20%\" align=\"left\">";
					$this->salida.=" <select name=\"alimentacion".$pfj."\" align=\"left\" class=\"select\">";
					$vars="";
					while (!$alimentacion->EOF)
					{
							$vars[$alimentacion->fields[0]]=$alimentacion->fields[1];
							$alimentacion->MoveNext();
					}
					$alimentacion->Close();

				  foreach($vars as $id9=>$ali)
					{   $this->salida.=" <option value=\"$id9\">$ali</option>";   }

					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
          /* AQUI VA  FINAL DE ALIMENTACION *******************************************/
           /* AQUI VA EL INICIO DE PESO TALLA/EG  *******************************************/
          $this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Peso Talla/EG : </td>";
					$this->salida.="<td width=\"35%\" align=\"left\">";
					$this->salida.=" <select name=\"pesotallaeg".$pfj."\" align=\"left\"  class=\"select\">";

					$vars="";
					while (!$pesotallaeg->EOF)
					{
							$vars[$pesotallaeg->fields[0]]=$pesotallaeg->fields[1];
							$pesotallaeg->MoveNext();
					}
          $pesotallaeg->Close();

				  foreach($vars as $pes=>$ta)
					{   $this->salida.=" <option value=\"$pes\">$ta</option>";   }

					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
          /* AQUI VA EL FINAL DE PESO TALLA/EG*******************************************/
					/* AQUI VA  INICIO DE EGRESO MATERNO *******************************************/
          $this->salida.="<tr>";
					$this->salida.=" <td width=\"40%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Egreso de la Madre: </td>";
					$this->salida.="<td width=\"35%\" align=\"left\">";
					$this->salida.=" <select name=\"egreso".$pfj."\" align=\"left\"  class=\"select\">";

					$vars="";
					while (!$egreso->EOF)
					{
							$vars[$egreso->fields[0]]=$egreso->fields[1];
							$egreso->MoveNext();
					}
					$egreso->Close();

				  foreach($vars as $ids=>$egr)
					{  $this->salida.=" <option value=\"$ids\">$egr</option>";  }

					$this->salida.="</select>";
					$this->salida.="</td>";
      	  $this->salida.="</tr>";
         /* AQUI VA  FINAL DE EGRESO MATERNO *******************************************/
          /* AQUI VA  INICIO DE MUERTE MATERNO *******************************************/
          $this->salida.="<tr>";
					$this->salida.=" <td width=\"35%\"  class=\"label\">&nbsp;&nbsp;&nbsp;Muerte de la Madre: </td>";
					$this->salida.="<td width=\"35%\" align=\"left\">";
					$this->salida.=" <select name=\"muertem".$pfj."\" align=\"left\"  class=\"select\">";
          $vars="";
					while (!$muertem->EOF)
					{
							$vars[$muertem->fields[0]]=$muertem->fields[1];
							$muertem->MoveNext();
					}
          $muertem->Close();

				  foreach($vars as $ds=>$muerm)
					{  $this->salida.=" <option value=\"$ds\">$muerm</option>";  }

					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					/* AQUI VA  FINAL DE MUERTE MATERNO *******************************************/
					$this->salida.=   "<tr><td><br></td></tr>";//espacios en cada secciones
					$this->salida.= "</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.= "</table>";
					$this->salida.= "<p align=\"center\"><input type=\"submit\" name=\"enviar\" value=\"Insertar\" class=\"input-submit\"></p>";
          $this->salida.="</form>";
					 $this->salida.="</tr>";
          $this->salida.="</table>";
     }
		 else
		 {
			$this->salida.= "<table align=\"center\">";
			$this->salida.="<tr>";
			$this->salida.= "<td align=\"center\" class=\"label_error\">El paciente no puede ser de tipo masculino</td>";
      $this->salida.="</tr>";
      $this->salida.="</table>";
		 }

          $this->salida .= ThemeCerrarTablaSubModulo();
					return true;


	}

}

?>
