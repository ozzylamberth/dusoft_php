<?php
/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @ Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar la administracion de usuarios
*/


class system_AdminEmpresa_adminclasses_HTML extends system_AdminEmpresa_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_AdminEmpresa_admin_HTML()
	{
		$this->salida='';
		$this->system_AdminEmpresa_admin();
		return true;
	}


/**
* Funcion donde se visializa el estilo de error de un dato en la forma
* @return string
* @param string nombre del campo que genera error
*/

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



/*fucnion q crea el listado de las empresas que visualiza el administrador primario*/
	
	function MenuEmpresas($dats)
	{

	$this->salida.="<center>\n";
	if($dats)
				{
					$this->salida .= ThemeMenuAbrirTabla("ADMINISTRADOR DE EMPRESA","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{

						$empresaId=$dats[$i][empresa_id];
						$razon=$dats[$i][razon_social];
						$web=$dats[$i][website];
						$sw=$dats[$i][sw_activa];
						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','ListadoCentrosUtilidad',array("empid"=>$empresaId))."\">$razon</a>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;".$web."</div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}
					$this->salida.="<table align=\"center\" width='20%' border=\"0\">";
					$action2=ModuloGetURL('system','Menu','user','main');
					$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">No existen Empresas.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="<table align=\"center\" width='20%' border=\"0\">";
					$action2=ModuloGetURL('system','Menu','user','main');
					$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.="</table>\n";
				}
				$this->salida.="</center>\n";

  return true;
	}



/*funcion que visualiza los centros de utilidad  para adicionar*/
function ListadoCentrosUtilidad()
 {
	unset($_SESSION['ADMIN']['CENTROU']);
	if(empty($_SESSION['ADMIN']['EMPRESAID'])) //identificación de la empresa.
	{
		$_SESSION['ADMIN']['EMPRESAID']=$_REQUEST['empid'];
	}
	$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
	$dats=$this->TraerListadoCentroUtilidad();
	$this->salida .='<form name="formares" action="'.ModuloGetURL('system','AdminEmpresa','admin','main').'" method="post">';
	$this->salida .= ThemeAbrirTabla('CENTROS DE UTILIDAD');
	if($dats)
				{     $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$actionCu=ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad');
							$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
  						$this->salida .= "            <tr><td>";
							$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
              $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
							$this->salida .= "			         </table>";
      				$this->salida .= "            </td></tr>";
              $this->salida.="<tr><td><br>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
             	$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td width=\"20%\">Centro Utilidad</td>";
							$this->salida.="  <td width=\"40%\">Descripcion</td>";
							$this->salida.="  <td width=\"12%\">#Unidades F</td>";
							$this->salida.="  <td width=\"20%\" colspan='2'>Eventos</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
									$cu=$dats[$i][centro_utilidad];
         					$desc=$dats[$i][descripcion];
									$cuenta=$this->TraerConteoUnidadFuncional($cu);//funcion q tyrae el conteo de unidades
									if(!$cuenta)
									{
										$cuenta=0;
									}
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
                  $this->salida.="  <td  align=\"left\">$cu</td>";
									$this->salida.="  <td  align=\"left\"><a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional',array("cu"=>$cu))."\">$desc</a></td>";
									$this->salida.="  <td  align=\"left\"><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$cuenta &nbsp;(UF)</td>";
									$this->salida.="  <td  align=\"left\"><img src=\"".GetThemePath()."/images/editar.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad',array("cu"=>$cu,"spy"=>'true'))."\">EDITAR</a></td>";
									$this->salida.="  <td  align=\"left\"><img src=\"".GetThemePath()."/images/elimina.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','BorrarCentroUtilidad',array("cu"=>$cu))."\">BORRAR</a></td>";
									$this->salida.="</tr>";
							}
							    $this->salida .= "            </td></tr>";
									$this->salida.="</table><br>";
									$this->salida.="</td></tr>";

									$this->salida.="<table align=\"center\">";
									$this->salida.="<tr>";
									$this->salida.="  <td align=\"center\"><img src=\"".GetThemePath()."/images/cu.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad')."\">CREAR NUEVO CENTRO DE UTILIDAD</a></td>";
									$this->salida.="</tr>";
									$this->salida.="</table><br>";

        					$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','AdminEmpresa','admin','main',array("uid"=>$uid)).'" method="post">';
									$this->salida.="  <td align=\"center\">";
									$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";

				}
        else
        {

								$this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
              	$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
								$this->salida .= "			         </table><br>";

								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td align=\"center\"><img src=\"".GetThemePath()."/images/cu.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad')."\">CREAR NUEVO CENTRO DE UTILIDAD</a></td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";

								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td class='label_error' align=\"center\"><img src=\"".GetThemePath()."/images/informacion.png\"  border=\"0\">&nbsp;&nbsp;&nbsp;No hay centros de utilidad para esta empresa</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr>";
								$this->salida.="  <td align=\"center\">";
								$this->salida .="<br><br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
        				$this->salida.="</tr>";
								$this->salida.="</table>";

				 }
				 				$this->salida .= ThemeCerrarTabla();
  return true;
 }




/**
* Funcion donde se visualiza la forma
*	que pide datos para insertar o modicar un centro de utilidad
* @return boolean
*/

   // $spy diferencia si es para insertar o modificar ,si viene true es modificar.
	// $cu esta variable trae la identificación del centro de utilidad.
	function FormaCentroUtilidad($spy,$cu)
	{
    if(!empty($_REQUEST['cu']))
		{
			$cu=$_REQUEST['cu'];   //esta variable trae la identificacion del centro de utilidad.
		}

		if(!empty($_REQUEST['spy']))
		{
			$spy=$_REQUEST['spy'];
		}
		if($spy==true)
		{
		if(empty($_SESSION['ADMIN']['CENTROU']))
		{
			$cu=$_SESSION['ADMIN']['CENTROU']=$_REQUEST['cu'];
		}
		$var='MODIFICAR';
		$action=ModuloGetURL('system','AdminEmpresa','admin','ModificarCentroutilidad',array('cu'=>$cu));
    $centroU=$this->TraerListadoCentroUtilidad($_SESSION['ADMIN']['CENTROU']);//tare los datos de esa identificación del centro de utilidad
		//$centro=$centroU[0][centro_utilidad];  //como es modificacion trae datos de la bd
		$centro=$cu;
		$desc=$centroU[0][descripcion];    //como es modificacion trae datos de la bd
		}
		else
		{
		  $var='NUEVO';
			$action=ModuloGetURL('system','AdminEmpresa','admin','InsertarCentroutilidad',array('cu'=>$cu));
      $desc=$_REQUEST['descripcion']; //para que traiga los datos x si algo falla.
			$centro=$_REQUEST['cu'];  //trae los datos por si se reenvia la info
		}
		$this->salida  = ThemeAbrirTabla(''.$var.' CENTRO DE UTILIDAD');
		$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
		$this->salida .= "			      <br><br>";

		$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
		$this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
		$this->salida .= "			         </table><br>";

		$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";

// 		//$archivos=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
// 		$this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("empresa")."\">EMPRESA: </td><td><select name=\"empresa\" class=\"select\">";
// 		$this->salida .=" <option value=\"-1\">-----Seleccione-----</option>";
// 		for($i=0;$i<sizeof($archivos);$i++)
// 		{
// 			$this->salida .=" <option value=\"".$archivos[$i][empresa_id]."\">".$archivos[$i][razon_social]."</option>";
// 		}
//
// 		$this->salida .= "       </select></td></tr>";

		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("centro")."\">ID CENTRO UTILIDAD: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"cu\" maxlength=\"2\" value=\"$centro\"></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCION: </td><td><textarea class=\"textarea\" name=\"descripcion\" cols=\"25\" rows=\"2\">$desc</textarea></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "			         </table>";
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
		$this->salida.="<tr>";
		$this->salida.="  <td align=\"left\">";
		$this->salida .="<br><input type=\"submit\"  name=\"Buscar\" value=\"Guardar\" class=\"input-submit\"></form></td>";

		$this->salida .='<form name="formita" action="'.ModuloGetURL('system','AdminEmpresa','admin','ListadoCentrosUtilidad',array("uid"=>$uid)).'" method="post">';
		$this->salida.="  <td align=\"right\">";
		$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/*funcion que visualiza la unidad funcional para adicionar*/
function FormaUnidadFuncional()
 {unset($_SESSION['ADMIN']['UNIDAD']);
  if(!empty($_REQUEST['cu']))
	{
			$centroU=$_SESSION['ADMIN']['CENTROU']=$_REQUEST['cu'];
	}
	else
	{
			$centroU=$_SESSION['ADMIN']['CENTROU'];
	}
	$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
	$uti=$this->TraerListadoCentroUtilidad($centroU);
	$dats=$this->TraerListadoUnidadFuncional();

	$this->salida .='<form name="formares" action="'.ModuloGetURL('system','AdminEmpresa','admin','ListadoCentrosUtilidad',array("cu"=>$_REQUEST['cu'])).'" method="post">';
	$this->salida .= ThemeAbrirTabla('UNIDAD FUNCIONAL');
	if($dats)
				{     $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$actionCu=ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad');
							$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
  						$this->salida .= "            <tr><td>";
							$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
              $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\" align=\"left\">".$uti[0][centro_utilidad]." &nbsp;&nbsp;&nbsp;".$uti[0][descripcion]."</td></tr>";
							$this->salida .= "			         </table>";
      				$this->salida .= "            </td></tr>";
              $this->salida.="<tr><td><br>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
             	$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td width=\"20%\">Unidad Funcional</td>";
							$this->salida.="  <td width=\"40%\">Descripcion</td>";
							$this->salida.="  <td width=\"12%\">#Dpto</td>";
							$this->salida.="  <td width=\"20%\" colspan='2'>Eventos</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
									$uf=$dats[$i][unidad_funcional];
         					$desc=$dats[$i][descripcion];
									$cuenta=$this->TraerConteoDepartamentos($uf);
									if(!$cuenta)
									{
										$cuenta=0;
									}
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
                  $this->salida.="  <td  align=\"left\">$uf</td>";
									$this->salida.="  <td  align=\"left\"><a href=\"".ModuloGetURL('system','AdminEmpresa','admin','ListadoDepartamentos',array("uf"=>$uf,"spy"=>'true'))."\">$desc</a></td>";
									$this->salida.="  <td  align=\"left\"><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$cuenta &nbsp;(Dpto)</td>";
									$this->salida.="  <td  align=\"left\"><img src=\"".GetThemePath()."/images/editar.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaOPUnidadFuncional',array("uf"=>$uf,"spy"=>'true'))."\">EDITAR</a></td>";
									$this->salida.="  <td  align=\"left\"><img src=\"".GetThemePath()."/images/elimina.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','BorrarUnidadFuncional',array("uf"=>$uf))."\">BORRAR</a></td>";
									$this->salida.="</tr>";
							}
							    $this->salida .= "            </td></tr>";
									$this->salida.="</table><br>";
									$this->salida.="</td></tr>";

									$this->salida.="<table align=\"center\">";
									$this->salida.="<tr>";
									$this->salida.="  <td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaOPUnidadFuncional')."\">CREAR NUEVA UNIDAD FUNCIONAL</a></td>";
									$this->salida.="</tr>";
									$this->salida.="</table><br>";

        					$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional',array("uid"=>$uid)).'" method="post">';
									$this->salida.="  <td align=\"center\">";
									$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";

				}
        else
        {


                $this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
              	$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\" align=\"left\">".$uti[0][centro_utilidad]." &nbsp;&nbsp;&nbsp;".$uti[0][descripcion]."</td></tr>";
								$this->salida .= "			         </table><br>";

								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaOPUnidadFuncional')."\">CREAR NUEVA UNIDAD FUNCIONAL</a></td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";


								$this->salida.="<br><table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td class='label_error' align=\"center\"><img src=\"".GetThemePath()."/images/informacion.png\"  border=\"0\">&nbsp;&nbsp;&nbsp;No hay Unidades Funcionales para este centro de utilidad</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr>";
								$this->salida .='<form name="formas" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional',array("uid"=>$uid)).'" method="post">';
								$this->salida.="  <td align=\"center\">";
								$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";

				 }
				 				$this->salida .= ThemeCerrarTabla();
  return true;
 }




	/**
* Funcion donde se visualiza la forma
*	que pide datos para insertar o modicar una unidad funcional
* @return boolean
*/

   // $spy diferencia si es para insertar o modificar ,si viene true es modificar.
	// $uf esta variable trae la identificación del centro de utilidad.
	function FormaOPUnidadFuncional($spy,$uf)
	{
    if(!empty($_REQUEST['uf']))
		{
			$uf=$_REQUEST['uf'];   //esta variable trae la identificacion de la unidad funcional.
		}

		if(!empty($_REQUEST['spy']))
		{
			$spy=$_REQUEST['spy'];
		}
		if($spy==true)
		{
		if(empty($_SESSION['ADMIN']['UNIDAD']))
		{
			$uf=$_SESSION['ADMIN']['UNIDAD']=$_REQUEST['uf'];
		}
		$var='MODIFICAR';
		$action=ModuloGetURL('system','AdminEmpresa','admin','ModificarUnidadFuncional',array('uf'=>$uf));
    $unidadF=$this->TraerListadoUnidadFuncional($_SESSION['ADMIN']['UNIDAD']);//trae los datos de esa identificación del centro de utilidad
		//$centro=$centroU[0][centro_utilidad];  //como es modificacion trae datos de la bd
		$centro=$uf;
		$desc= $unidadF[0][descripcion];    //como es modificacion trae datos de la bd
		}
		else
		{
		  $var='NUEVA';
			$action=ModuloGetURL('system','AdminEmpresa','admin','InsertarUnidadFuncional',array('uf'=>$uf));
      $desc=$_REQUEST['descripcion']; //para que traiga los datos x si algo falla.
			$centro=$_REQUEST['uf'];  //trae los datos por si se reenvia la info
		}
		$this->salida  = ThemeAbrirTabla(''.$var.' UNIDAD FUNCIONAL');
		$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
		$uti=$this->TraerListadoCentroUtilidad($_SESSION['ADMIN']['CENTROU']);
		$this->salida .= "			      <br><br>";

		$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
		$this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\" align=\"left\">".$uti[0][centro_utilidad]." &nbsp;&nbsp;&nbsp;".$uti[0][descripcion]."</td></tr>";
		$this->salida .= "			         </table><br>";

		$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";

		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("centro")."\">ID UNIDAD FUNCIONAL: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"uf\" maxlength=\"4\" value=\"$centro\"></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCION: </td><td><textarea class=\"textarea\" name=\"descripcion\" cols=\"25\" rows=\"2\">$desc</textarea></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "			         </table>";
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
		$this->salida.="<tr>";
		$this->salida.="  <td align=\"left\">";
		$this->salida .="<br><input type=\"submit\"  name=\"Buscar\" value=\"Guardar\" class=\"input-submit\"></form></td>";

		$this->salida .='<form name="formita" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional').'" method="post">';
		$this->salida.="  <td align=\"right\">";
		$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/*funcion que visualiza los departamentos para editar,borrar y adicionar*/
function ListadoDepartamentos()
 {
 	unset($_SESSION['ADMIN']['DPTO']);
  if(!empty($_REQUEST['uf']))
	{
			$centroU=$_SESSION['ADMIN']['UNIDAD']=$_REQUEST['uf'];
	}
	else
	{
			$centroU=$_SESSION['ADMIN']['UNIDAD'];
	}
	$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
	$uti=$this->TraerListadoCentroUtilidad($_SESSION['ADMIN']['CENTROU']);
	$unid=$this->TraerListadoUnidadFuncional('unidad');
	$dats=$this->TraerListadoDpto('');
	$this->salida .='<form name="formares" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional',array("cu"=>$_REQUEST['cu'])).'" method="post">';
	$this->salida .= ThemeAbrirTabla('LISTADO DEPARTAMENTOS');
	if($dats)
				{     $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$actionCu=ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad');
							$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
  						$this->salida .= "            <tr><td>";
							$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
              $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\" align=\"left\">".$uti[0][centro_utilidad]." &nbsp;&nbsp;&nbsp;".$uti[0][descripcion]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$unid[0][unidad_funcional]." &nbsp;&nbsp;&nbsp;".$unid[0][descripcion]."</td></tr>";
							$this->salida .= "			         </table>";
      				$this->salida .= "            </td></tr>";
              $this->salida.="<tr><td><br>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
             	$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td width=\"20%\">Departamento</td>";
							$this->salida.="  <td width=\"40%\">Descripción</td>";
							$this->salida.="  <td width=\"20%\" colspan='2'>Eventos</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
									$dpto=$dats[$i][departamento];
         					$desc=$dats[$i][descripcion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
                  $this->salida.="  <td  align=\"left\">$dpto</td>";
									$this->salida.="  <td  align=\"left\">$desc</td>";
									$this->salida.="  <td  align=\"left\"><img src=\"".GetThemePath()."/images/editar.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaDepartamento',array("dpto"=>$dpto,"spy"=>'true'))."\">EDITAR</a></td>";
									$this->salida.="  <td  align=\"left\"><img src=\"".GetThemePath()."/images/elimina.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','BorrarDpto',array("dpto"=>$dpto))."\">BORRAR</a></td>";
									$this->salida.="</tr>";
							}
							    $this->salida .= "            </td></tr>";
									$this->salida.="</table><br>";
									$this->salida.="</td></tr>";

									$this->salida.="<table align=\"center\">";
									$this->salida.="<tr>";
									$this->salida.="  <td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaDepartamento')."\">CREAR NUEVO DEPARTAMENTO</a></td>";
									$this->salida.="</tr>";
									$this->salida.="</table><br>";

        					$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional',array("uid"=>$uid)).'" method="post">';
									$this->salida.="  <td align=\"center\">";
									$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";

				}
        else
        {


                $this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
              	$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\" align=\"left\">".$uti[0][centro_utilidad]." &nbsp;&nbsp;&nbsp;".$uti[0][descripcion]."</td></tr>";
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$unid[0][unidad_funcional]." &nbsp;&nbsp;&nbsp;".$unid[0][descripcion]."</td></tr>";
								$this->salida .= "			         </table><br>";

								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\">&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','FormaDepartamento')."\">CREAR NUEVO DEPARTAMENTO</a></td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";


								$this->salida.="<br><table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td class='label_error' align=\"center\"><img src=\"".GetThemePath()."/images/informacion.png\"  border=\"0\">&nbsp;&nbsp;&nbsp;No hay Unidades Funcionales para este centro de utilidad</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr>";
								$this->salida .='<form name="formas" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaUnidadFuncional',array("uid"=>$uid)).'" method="post">';
								$this->salida.="  <td align=\"center\">";
								$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";

				 }
				 				$this->salida .= ThemeCerrarTabla();
  return true;
 }



/**
* Funcion donde se visualiza la forma
*	que pide datos para insertar o modicar una unidad funcional
* @return boolean
*/

   // $spy diferencia si es para insertar o modificar ,si viene true es modificar.
	// $uf esta variable trae la identificación del centro de utilidad.
	function FormaDepartamento($spy,$dpto)
	{
    if(!empty($_REQUEST['dpto']))
		{
			$dpto=$_REQUEST['dpto'];   //esta variable trae la identificacion de la unidad funcional.
		}

		if(!empty($_REQUEST['spy']))
		{
			$spy=$_REQUEST['spy'];
		}


		if($spy==true)
		{
					if(empty($_SESSION['ADMIN']['DPTO']))
					{
						$dpto=$_SESSION['ADMIN']['DPTO']=$_REQUEST['dpto'];
					}
					$var='MODIFICAR';
					$action=ModuloGetURL('system','AdminEmpresa','admin','ModificarDpto',array('dpto'=>$dpto));
					$departamento=$this->TraerListadoDpto($_SESSION['ADMIN']['DPTO']);//trae los datos de esa identificación del centro de utilidad
					//$centro=$departamento[0][centro_utilidad];  //como es modificacion trae datos de la bd
					$centro=$dpto;
					$desc= $departamento[0][descripcion];    //como es modificacion trae datos de la bd
					$asistencia= $departamento[0][sw_asistencial1];    //como es modificacion trae datos de la bd
					$hospitalizacion= $departamento[0][sw_hospitalizacion1];
					$ser= $departamento[0][servicio];

		}
		else
		{
		  $var='NUEVO';
			$action=ModuloGetURL('system','AdminEmpresa','admin','InsertarDpto',array('dpto'=>$dpto));
      $desc=$_REQUEST['descripcion']; //para que traiga los datos x si algo falla.
			$centro=$_REQUEST['dpto'];  //trae los datos por si se reenvia la info
			$asistencia=$_REQUEST['asistencia'];
			$hospitalizacion=$_REQUEST['hospital'];
		}


		if(!empty($hospitalizacion))
		{$check1='checked';}else{$check1='';}

		if(!empty($asistencia))
		{$check='checked';}else{$check='';}


		$unid=$this->TraerListadoUnidadFuncional('unidad');
		$this->salida  = ThemeAbrirTabla(''.$var.' DEPARTAMENTO');
		$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
		$uti=$this->TraerListadoCentroUtilidad($_SESSION['ADMIN']['CENTROU']);
		$this->salida .= "			      <br><br>";

		$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
		$this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CODIGO SGSSS: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][codigo_sgsss]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\" align=\"left\">".$uti[0][centro_utilidad]." &nbsp;&nbsp;&nbsp;".$uti[0][descripcion]."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$unid[0][unidad_funcional]." &nbsp;&nbsp;&nbsp;".$unid[0][descripcion]."</td></tr>";
		$this->salida .= "			         </table><br>";

		$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";

		if($spy==true)
		{
			$archivos=$this->TraerComboServicio();
				$this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("servicio")."\">SERVICIO: </td><td><select name=\"servicio\" class=\"select\">";
				$this->salida .=" <option value=\"-1\">-----Seleccione-----</option>";
				for($i=0;$i<sizeof($archivos);$i++)
				{
				  if($archivos[$i][servicio]==$ser)
					{
						$this->salida .=" <option value=\"".$archivos[$i][servicio]."\" selected>".$archivos[$i][descripcion]."</option>";
					}
					else
					{
						$this->salida .=" <option value=\"".$archivos[$i][servicio]."\">".$archivos[$i][descripcion]."</option>";
					}
				}
		}
		else
		{
				$archivos=$this->TraerComboServicio();
				$this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("servicio")."\">SERVICIO: </td><td><select name=\"servicio\" class=\"select\">";
				$this->salida .=" <option value=\"-1\">-----Seleccione-----</option>";
				for($i=0;$i<sizeof($archivos);$i++)
				{
					$this->salida .=" <option value=\"".$archivos[$i][servicio]."\">".$archivos[$i][descripcion]."</option>";
				}
		}

		$this->salida .= "       </select></td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td  class='label' align=\"left\">SW_INTERNACION: &nbsp;&nbsp;&nbsp;<input type='checkbox' name='hospital' $check1></td><td class='label' align=\"left\">&nbsp;<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','AdminEmpresa','admin','ListadoTodos',array("dpto"=>$_REQUEST['dpto'],"spy"=>$spy))."\">REVISAR CODIGOS</a></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("centro")."\">ID DEL DEPARTAMENTO: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"dpto\" maxlength=\"4\" size=\"25\"  value=\"$centro\"></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCION: </td><td><textarea class=\"textarea\" name=\"descripcion\" cols=\"30\" rows=\"2\">$desc</textarea></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "			         </table>";
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
		$this->salida.="<tr>";
		$this->salida.="  <td align=\"left\">";
		$this->salida .="<br><input type=\"submit\"  name=\"Buscar\" value=\"Guardar\" class=\"input-submit\"></form></td>";

		$this->salida .='<form name="formita" action="'.ModuloGetURL('system','AdminEmpresa','admin','ListadoDepartamentos').'" method="post">';
		$this->salida.="  <td align=\"right\">";
		$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



function ListadoTodos()
 {
	$dats=$this->ListaTodos();
 	$this->salida .='<form name="forma" action="'.ModuloGetURL('system','AdminEmpresa','admin','FormaDepartamento',array("dpto"=>$_REQUEST['dpto'],"spy"=>$_REQUEST['spy'])).'" method="post">';
	$this->salida .= ThemeAbrirTabla('LISTADO DE TODAS LAS EMPRESAS');
	//if($dats)
				//{
				     	$this->salida.="<br><table border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida.=$this->SetStyle("MensajeError");
							//$actionCu=ModuloGetURL('system','AdminEmpresa','admin','FormaCentroUtilidad');
							$this->salida.="<form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
  						$this->salida.="<tr><td><br>";
							$this->salida.="<table  align=\"center\" border=\"0\" width=\"85%\">";
             	$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td width=\"28%\">Empresa</td>";
							$this->salida.="  <td width=\"27%\">Centro Utilidad</td>";
							$this->salida.="  <td width=\"23%\">Unidad Funcional</td>";
							$this->salida.="  <td width=\"25%\">Depto</td>";
							$this->salida.="</tr>";
							$j=0;
							for($i=0;$i<sizeof($dats);)
							{
									if($j==0)
									{
										$color="class=\"modulo_list_claro\"";
										$j=1;
									}
									else
									{
										$color="class=\"modulo_list_oscuro\"";
										$j=0;
									}
									$this->salida .= "  <tr $color>";
									$this->salida .= "  <td>";
									$this->salida .= "".$dats[$i]['razon_social']."";
									$this->salida .= "  </td>";
									$this->salida .= "  <td colspan=\"3\">";
									if($dats[$i]['cu']==NULL)
									{
										$this->salida .= "  	<table border=\"0\" width=\"100%\" align=\"center\" $color>";
									}
									else
									{
										$this->salida .= "  	<table border=\"1\" width=\"100%\" align=\"center\" $color>";
									}
									$k=$i;
									While($dats[$i]['razon_social']==$dats[$k]['razon_social'])
									{
										$this->salida .= "      <tr>";
										$this->salida .= "      <td width=\"36%\">";
										$this->salida .= "".$dats[$k]['cu']."";
										$this->salida .= "      </td>";
										$this->salida .= "      <td width=\"64%\">";
										if($dats[$k]['uf']==NULL)
										{
											$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\" $color>";
										}
										else
										{
											$this->salida .= "  		<table border=\"1\" width=\"100%\" align=\"center\" $color>";
										}
										$l=$k;
										While($dats[$k]['cu']==$dats[$l]['cu'] AND $dats[$k]['razon_social']==$dats[$l]['razon_social'])
										{
											$this->salida .= "      <tr>";
											$this->salida .= "      <td width=\"50%\">";
											$this->salida .= "".$dats[$l]['uf']."";
											$this->salida .= "      </td>";


												$this->salida .= "      <td width=\"10%\">";
												$this->salida .= "".$dats[$l]['departamento']."";
												$this->salida .= "      </td>";

											$this->salida .= "      <td width=\"25%\">";
											$this->salida .= "".$dats[$l]['dpto']."";
											$this->salida .= "      </td>";
											$this->salida .= "      </tr>";
											$l++;
										}
										$this->salida .= "      </table>";
										$this->salida .= "      </td>";
										$this->salida .= "      </tr>";
										$k=$l;
									}
									$this->salida .= "      </table>";
									$this->salida .= "  </td>";
									$this->salida .= "  </tr>";
									$i=$k;
							}
							$this->salida .= "  </table>";
							$this->salida .= "</td></tr></table>";

							$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
							$this->salida.="<tr>";
							$this->salida.="  <td align=\"center\">";
							$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
							$this->salida.="</tr>";
							$this->salida.="</table>";
			 				$this->salida .= ThemeCerrarTabla();
  						return true;
 }



}//fin clase user
?>

