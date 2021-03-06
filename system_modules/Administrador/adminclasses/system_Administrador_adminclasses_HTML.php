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


class system_Administrador_adminclasses_HTML extends system_Administrador_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_Administrador_admin_HTML()
	{
		$this->salida='';
		$this->system_Administrador_admin();
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
					$this->salida .= ThemeMenuAbrirTabla("ADMINISTRADOR DEL SISTEMA","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{

						$empresaId=$dats[$i][empresa_id];
						$razon=$dats[$i][razon_social];
						$web=$dats[$i][website];
						$sw=$dats[$i][sw_activa];
						$swM=$dats[$i][sw_usuarios_multiempresa];

						if($sw=='1')
						{
							$bloqueo='Inactivar';
							$img='activoemp.png';
						}
						else
						{
        				$bloqueo='Activar..';
								$img='inactivoemp.png';
						}
						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','ListadoUsuarioEmpresa',array("empid"=>$empresaId,'swm'=>$swM))."\">$razon</a>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;".$web."</div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/$img\" width='13' height='13'>&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','ModificarEstadoEmpresa',array("empid"=>$empresaId))."\">$bloqueo</a>&nbsp;|&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','FormaIngresarEmpresa',array("empid"=>$empresaId,"decision"=>true))."\">Modificar</a>|&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','BorrarEmp',array("empid"=>$empresaId))."\">Borrar</a></div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}
					$this->salida.="<table border='0' width='100%'>";
					$this->salida.="	<tr>";
					$this->salida.="		<td align='center' class='normal_10_menu'>";
					$this->salida.="<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','FormaIngresarEmpresa',array("empid"=>$empresaId))."\">CREAR NUEVA EMPRESA</a>	";
					$this->salida.="		</td>";
					$this->salida.="	</tr>";

					$this->salida.="	<tr>";
					$this->salida.="		<td align='center' class='normal_10_menu'>";
					$this->salida.="<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','ListadoModulos')."\">ADMINISTRACION MODULOS</a>	";
					$this->salida.="		</td>";
					$this->salida.="	</tr>";

					$this->salida.="</table><br>";
					$this->salida.="<table border='0' align='center'>";
					$this->salida.="	<tr><td>";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="	</td></tr>";
					$this->salida.="</table>";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">No existen Empresas.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="</table>\n";
				}
				$this->salida.="</center>\n";
  return true;
	}



/*funcion q visualiza la forma para insertar o modificar
 * una nueva empresa por medio del administrador
 *		primario
 */




	function FormaIngresarEmpresa($decision='',$empresaid)
	{
      if(!empty($_REQUEST['decision']))
			{
       	$decision=$_REQUEST['decision'];
			}

      if($decision==true)
			{
			  if(!empty($_REQUEST['empid']))
				{
         $empresaid=$_REQUEST['empid'];
				}
				$action=ModuloGetURL('system','Administrador','admin','ModificarEmp',array('empid'=>$empresaid));
        $datos=$this->TraerDatosEmpresa($empresaid);
				$ide= $datos[0][empresa_id];
				$nombreemp= $datos[0][razon_social];
				$id= $datos[0][id];
				$reprelegal= $datos[0][representante_legal];
				$pais= $datos[0][tipo_pais_id];
    		$email= $datos[0][email];
				$fax= $datos[0][fax];
				$tel= $datos[0][telefonos];
				$dir= $datos[0][direccion];
				$codigossg= $datos[0][codigo_sgsss];
				$mpio= $datos[0][tipo_mpio_id];
				$dpto= $datos[0][tipo_dpto_id];
				$web= $datos[0][website];
				$cpostal=$datos[0][codigo_postal];
				$swactivo=$datos[0][sw_activa];
				$swmemp=$datos[0][sw_usuarios_multiempresa];
				$tipodoc=$datos[0][tipo_id_tercero];
				$titulo='Modificar Empresa';
				$boton='Modificar';
			}
			else
			{
				$ide=$_REQUEST['ide'];
				$nombreemp=$_REQUEST['nombreemp'];
				$id=$_REQUEST['id'];
				$reprelegal=$_REQUEST['reprelegal'];
				$npais=$_REQUEST['npais'];
    		$email=$_REQUEST['email'];
				$fax=$_REQUEST['fax'];
				$tel=$_REQUEST['tel'];
				$dir=$_REQUEST['dir'];
				$codigossg=$_REQUEST['codigossg'];
				$mpio=$_REQUEST['mpio'];
				$nmpio=$_REQUEST['nmpio'];
				$dpto=$_REQUEST['dpto'];
				$pais=$_REQUEST['pais'];
				$ndpto=$_REQUEST['ndpto'];
				$web=$_REQUEST['web'];
				$cpostal=$_REQUEST['cpostal'];
				$titulo='Crear Nueva Empresa';
				$boton='Insertar';
				$action=ModuloGetURL('system','Administrador','admin','InsertarEmp');
			}
			$this->salida  = ThemeAbrirTabla('INSERTAR EMPRESA');
			$this->salida .= "			      <br><br>";
			$this->salida .= "           <form name=\"forma\" action=\"$action\" method=\"post\">";
			$ru='classes/BuscadorDestino/selectorCiudad.js';
			$rus='classes/BuscadorDestino/selector.php';

			$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
			$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
			$this->salida .= "            <tr><td>";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "            </td></tr>";

			$this->salida .= "            <tr><td>";
			$this->salida .= "              <fieldset><legend class=\"field\">$titulo</legend>";
			$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\" border=\"0\" width=\"80%\" align=\"center\">";
      if($decision!=true)
      {
				$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("ide")."\">IDENTIFICACION EMPRESA: </td><td><input type=\"text\" class=\"input-text\" name=\"ide\" maxlength=\"60\" size=\"30\" value=\"".$ide."\"></td></tr>";
			}
			$this->salida.= "               <input type=\"hidden\" name=\"action\" value=\"$action\">";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("empresa")."\">RAZON SOCIAL: </td><td><input type=\"text\" class=\"input-text\" name=\"nombreemp\" maxlength=\"60\" size=\"30\" value=\"".$nombreemp."\"></td></tr>";
			$this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("tipodoc")."\">TIPO DOCUMENTO: </td><td><select name=\"tipodoc\" class=\"select\">";


			if($decision!=true)
			{
						$archivos=$this->TraerBusqTercero(false,'');
						$this->salida .=" <option value=\"-1\">--Seleccione---</option>";
						for($i=0;$i<sizeof($archivos);$i++)
						{
							$this->salida .=" <option value=\"".$archivos[$i][tipo_id_tercero]."\">".$archivos[$i][descripcion]."</option>";
						}
			}
			else
			{
             $ubicacion=$this->TraerNombreUbicacion($pais,$dpto,$mpio);
             $npais=$ubicacion[0][pais];
						 $ndpto=$ubicacion[0][departamento];
						 $nmpio=$ubicacion[0][municipio];


						$tipo_tercero=$this->TraerBusqTercero(true,$tipodoc); //para tarer 1 tercero
						$archivos=$this->TraerBusqTercero(false,'');//para tarer todos los tercero

						for($i=0;$i<sizeof($archivos);$i++)
						{
						  if($archivos[$i][tipo_id_tercero]==$tipo_tercero)
							{
								$this->salida .=" <option value=\"".$archivos[$i][tipo_id_tercero]."\" selected>".$archivos[$i][descripcion]."</option>";
							}
							else
							{
								$this->salida .=" <option value=\"".$archivos[$i][tipo_id_tercero]."\">".$archivos[$i][descripcion]."</option>";
							}
						}
			}


			$this->salida .= "       </select></td></tr>";
      if($decision==true)
			{
			  if($swactivo=='1')
				{
          $checked='checked';
				}
				else
				{
					$checked='';
				}
				if($swmemp=='1')
				{
					$checked1='checked';
				}
				else
				{
					$checked1='';
				}

				$this->salida .= "              <tr class=\"modulo_list_claro\"><td class=\"label\" align=\"left\">ACTIVO   <input type=\"checkbox\" name=\"activo\" $checked></td>";
				$this->salida .= "              <td align=\"left\" class=\"label\">M.EMPRESA  <input type=\"checkbox\" name=\"mempresa\" $checked1></td></tr>";
			}
			else
			{
				$this->salida .= "              <tr class=\"modulo_list_claro\"><td class=\"label\" align=\"left\">ACTIVO   <input type=\"checkbox\" name=\"activo\" checked></td>";
				$this->salida .= "              <td align=\"left\" class=\"label\">M.EMPRESA  <input type=\"checkbox\" name=\"mempresa\"></td></tr>";
			}

			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("id")."\">IDENTIFICACION: </td><td><input type='text'  value=\"".$id."\" name='id' class=\"input-text\" maxlength=\"20\" size=\"30\"></td></tr>";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("reprelegal")."\">REPRESENTANTE LEGAL: </td><td><input type='text' class=\"input-text\" name='reprelegal' value=\"".$reprelegal."\"  size=\"30\" maxlength=\"60\"></td></tr>";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("codigossg")."\">CODIGO SGSS: </td><td><input type='text' name='codigossg' class=\"input-text\" value=\"".$codigossg."\"  maxlength=\"20\" size=\"30\"></td></tr>";
			$this->salida .= "				       <tr>";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\">DIRECCION: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"dir\" maxlength=\"30\" size=\"30\"  value=\"".$dir."\"></td></tr>";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\">TELEFONO: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"tel\" maxlength=\"30\" size=\"30\" value=\"".$tel."\"></td></tr>";
      $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\">FAX: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fax\" maxlength=\"30\" size=\"30\" value=\"".$fax."\"></td></tr>";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\">CODIGO POSTAL: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"cpostal\" maxlength=\"30\" size=\"30\" value=\"".$cpostal."\"></td></tr>";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\">WEB SITE: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"web\" maxlength=\"30\" size=\"30\" value=\"".$web."\"></td></tr>";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\">E-MAIL: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"email\" maxlength=\"30\" size=\"30\" value=\"".$email."\"></td></tr>";

			$this->salida .= "<tr class=\"modulo_list_claro\"><td width='30%' class='label'>UBICACION</td>";
			$this->salida .= "<td><table  width='60%' border=\"0\" >";
			$this->salida .= "<tr  width='30%'  class=\"modulo_list_claro\"> ";
			$this->salida .= "<td  class=\"".$this->SetStyle("pais")."\">Pais</td><td class=\"modulo_list_oscuro\"><input type='text' class=\"input-text\" name='npais' value=\"".$npais."\" READONLY> </td> ";
			$this->salida .= "<input type='hidden' name='pais' value=\"".$pais."\">";
			$this->salida .= "</tr>";
			$this->salida .= "<tr> ";
			$this->salida .= "<td class=\"".$this->SetStyle("dpto")."\">Dpto</td><td class=\"modulo_list_claro\"><input type='text' class=\"input-text\" name='ndpto' value=\"".$ndpto."\" READONLY> </td> ";
			$this->salida .= "<input type='hidden' name='dpto' value=\"".$dpto."\">";
			$this->salida .= "<td><input type='button' name='buscar' class=\"input-submit\"  value='Buscar' onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\"></td> ";
			$this->salida .= "</tr>";
			$this->salida .= "<tr> ";
			$this->salida .= "<td  class=\"".$this->SetStyle("mpio")."\">Municipio</td><td class=\"modulo_list_oscuro\"><input type='text' class=\"input-text\" name='nmpio' value=\"".$nmpio."\" READONLY></td> ";
			$this->salida .= "<input type='hidden' name='mpio' value=\"".$mpio."\" >";
			$this->salida .= "</tr>";
			$this->salida .= "</table></td></tr>";
			$this->salida .= "			         </table>";
			$this->salida .= "		           </fieldset></td></tr>";

			$this->salida .= "  <table width=\"40%\" align=\"center\">";
			$this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"$boton\"><br></td>";
			$this->salida .= "			      </form>";
			$action3=ModuloGetURL('system','Administrador','admin','main');
			$this->salida .= "           <form name=\"formas\" action=\"$action3\" method=\"post\">";
			$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Cancelar\"></td></tr>";
			$this->salida .= "			      </form>";
			$this->salida .= "            </table><BR><BR>";
			$this->salida .= ThemeCerrarTabla();
   return true;
	}



/*funcion que visualiza los usuarios del sistema para adicionar*/
function ListadoUsuarioSistema()
 {
	$this->salida .= ThemeAbrirTabla('LISTADO DE USUARIOS');
	$this->salida .= "<br>";
	$this->salida .= "<br>";
	$this->salida .= "<SCRIPT>";
	$this->salida .= "function chequeoTotal(frm,x){";
	$this->salida .= "  if(x==true){";
	$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
	$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
	$this->salida .= "        frm.elements[i].checked=true";
	$this->salida .= "      }";
	$this->salida .= "    }";
	$this->salida .= "  }else{";
	$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
	$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
	$this->salida .= "        frm.elements[i].checked=false";
	$this->salida .= "      }";
	$this->salida .= "    }";
	$this->salida .= "  }";
	$this->salida .= "}";
	$this->salida .= "</SCRIPT>";
 	$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);  //si sale esto error revisar
 $dats=$this->TraerListadoUsuariosSistema();
	if($dats)
				{     $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\">";
							$actionInsertar=ModuloGetURL('system','Administrador','admin','InsertarUsuarioSistema');
							$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
  						$this->salida .= "            <tr><td>";
							$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
            	$this->salida .= "			         </table>";
						 $this->salida .= "            </td></tr>";
              $this->salida.="<tr><td><br>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
              if(empty($_REQUEST['op']))
							{
								$this->salida.="<tr><td  colspan='5' align=\"center\" class='label_error'>Debe seleccionar como minimo un usuario para adherir a la empresa </td></tr>";
							}
							$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td width=\"10%\">><input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
							$this->salida.="  <td>Id</td>";
							$this->salida.="  <td>Usuario</td>";
       $this->salida.="  <td>Nombre</td>";
							$this->salida.="  <td>descripcion</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
                	$user=$dats[$i][usuario_id];
	   							$login=$dats[$i][usuario];
         $nombre=$dats[$i][nombre];
									$desc=$dats[$i][descripcion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
                  $this->salida.="  <td width=\"10%\"><input type=checkbox name=op[$i] value=$user></td>";
         $this->salida.="  <td  align=\"left\">$user</td>";
									$this->salida.="  <td  align=\"left\">$login</td>";
									$this->salida.="  <td  align=\"left\">$nombre</td>";
									$this->salida.="  <td  align=\"left\">$desc</td>";
                  $this->salida.="</tr>";
							}
							    $this->salida .= "            </td></tr>";
									$this->salida.="</table>";
									$this->salida.="</td></tr>";
									$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida.="  <td align=\"left\">";
									$this->salida .="<br><input type=\"submit\"  name=\"Buscar\" value=\"Guardar\" class=\"input-submit\"></form></td>";

									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','Administrador','admin','ListadoUsuarioEmpresa',array("uid"=>$uid)).'" method="post">';
									$this->salida.="  <td align=\"right\">";
									$this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";

				}
        else
        {
								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td class='label_error' align=\"center\"><img src=\"".GetThemePath()."/images/informacion.png\"  border=\"0\">&nbsp;&nbsp;&nbsp;No posee usuarios administradores esta empresa</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr>";
								$this->salida.="  <td align=\"center\">";
								$this->salida .='<form name="formares" action="'.ModuloGetURL('system','Administrador','admin','ListadoUsuarioEmpresa').'" method="post">';
								$this->salida .="<br><br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
        				$this->salida.="</tr>";
								$this->salida.="</table>";

				 }
								$this->salida .= ThemeCerrarTabla();
  return true;
 }




/*funcion que visualiza los usuarios segun la identificaci?n de una empresa*/
function ListadoUsuarioEmpresa()
 {
	if(empty($_SESSION['ADMIN']['EMPRESAID'])) //identificaci?n de la empresa.
	{
		$_SESSION['ADMIN']['EMPRESAID']=$_REQUEST['empid'];
	}
	if(empty($_SESSION['ADMIN']['SWM'])) //variable de seccion q indica si es multiempresa o no.
	{
		$_SESSION['ADMIN']['SWM']=$_REQUEST['swm'];
	}
	$emp=$this->TraerDatosEmpresa($_SESSION['ADMIN']['EMPRESAID']);
	$dats=$this->TraerListadoUsuarios();
	$this->salida = ThemeAbrirTabla('LISTADO DE USUARIOS');
	if($dats)
				{
  						$this->salida .= "              <br><br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"75%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][id]." &nbsp;&nbsp;&nbsp;".$emp[0][razon_social]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">REPRESENTANTE LEGAL: </td><td class=\"modulo_list_claro\" align=\"left\">".$emp[0][representante_legal]."</td></tr>";
            	$this->salida .= "			         </table>";

							$this->salida.="<br><br><table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td>Id</td>";
							$this->salida.="  <td>Usuario</td>";
							$this->salida.="  <td>Nombre</td>";
							$this->salida.="  <td>descripcion</td>";
							$this->salida.="  <td></td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
                	$user=$dats[$i][usuario_id];
	   							$login=$dats[$i][usuario];
									$nombre=$dats[$i][nombre];
									$desc=$dats[$i][descripcion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
									$this->salida.="  <td  align=\"left\">$user</td>";
									$this->salida.="  <td  align=\"left\">$login</td>";
									$this->salida.="  <td  align=\"left\">$nombre</td>";
									$this->salida.="  <td  align=\"left\">$desc</td>";
                  $this->salida.="  <td><a href=\"".ModuloGetURL('system','Administrador','admin','BorrarUsuarios',array("uid"=>$user))."\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\" >&nbsp;&nbsp;Borrar</a></td>";
									$this->salida.="</tr>";
							}
							   	$this->salida.="</table><br>";
									$this->salida.="<table align='center'>";
									 //quiere decir que si esta variable esta en 1 es por q es multi_empresa.
									if($_SESSION['ADMIN']['SWM']=='1')
									{
										$accion=ModuloGetURL('system','Administrador','admin','ListadoUsuarioSistema');
									}
									$acc=ModuloGetURL('system','Administrador','admin','ListadoUsuarioEmpresa');
									$ax=ModuloGetURL('system','Administrador','admin','Usuario');


									$this->salida.="<tr>";
									$this->salida .= "<td  colspan=\"2\"  class='normal_10' align=\"center\"><a href=\"$ax\">CREAR  USUARIOS ADMINISTRADORES DE EMPRESAS</a>&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/nuevo_usuario.png\">";
									$this->salida.="</td>";
									$this->salida.="</tr>";
									if($_SESSION['ADMIN']['SWM']=='1')
									{
										$this->salida.="<tr>";
										$this->salida .= "<td  colspan=\"2\"   class='normal_10' align=\"center\"><a href=\"$accion\">ADICIONAR USUARIOS DEL SISTEMA</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/usuarios.png\">";
										$this->salida.="</td>";
										$this->salida.="</tr>";
									}
									$this->salida.="</table>";
									$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida.="  <td align=\"center\">";
									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','Administrador','admin','main',array("uid"=>$uid)).'" method="post">';
									$this->salida .="<br><br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";
				}
        else
        {
								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td class='label_error' align=\"center\"><img src=\"".GetThemePath()."/images/informacion.png\"  border=\"0\">&nbsp;&nbsp;&nbsp;No posee usuarios administradores esta empresa</td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";
								$this->salida.="<table align='center'>";
									 //quiere decir que si esta variable esta en 1 es por q es multi_empresa.
									if($_SESSION['ADMIN']['SWM']=='1')
									{
										$accion=ModuloGetURL('system','Administrador','admin','ListadoUsuarioSistema');
									}
									$acc=ModuloGetURL('system','Administrador','admin','ListadoUsuarioEmpresa');
									$ax=ModuloGetURL('system','Administrador','admin','Usuario');


									$this->salida.="<tr>";
									$this->salida .= "<td  colspan=\"2\"  class='normal_10' align=\"center\"><a href=\"$ax\">CREAR  USUARIOS ADMINISTRADORES DE EMPRESAS</a>&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/nuevo_usuario.png\">";
									$this->salida.="</td>";
									$this->salida.="</tr>";
									if($_SESSION['ADMIN']['SWM']=='1')
									{
										$this->salida.="<tr>";
										$this->salida .= "<td  colspan=\"2\"   class='normal_10' align=\"center\"><a href=\"$accion\">ADICIONAR USUARIOS DEL SISTEMA</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/usuarios.png\">";
										$this->salida.="</td>";
										$this->salida.="</tr>";
									}
									$this->salida.="</table>";
									$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida.="  <td align=\"center\">";
									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','Administrador','admin','main',array("uid"=>$uid)).'" method="post">';
									$this->salida .="<br><br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";
				 }
								$this->salida .= ThemeCerrarTabla();
  return true;
 }



/*funcion que inserta un usuario administrador de empresas solo lo pude crear el
administrador primario*/
function FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,$consulta,$descripcion,$spia,$uid){
		$this->salida  = ThemeAbrirTabla('INSERTAR USUARIO ADMINISTRADOR');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
    $this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "            </td></tr>";

		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DATOS USUARIO SISTEMA</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"80%\" align=\"center\">";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("nombreUsuario")."\">NOMBRE USUARIO: </td><td><input type=\"text\" class=\"input-text\" name=\"nombreUsuario\" maxlength=\"60\" value=\"$nombreUsuario\"></td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("tema")."\">TEMA: </td><td><select name=\"tema\" class=\"select\">";
    $this->salida.= "               <input type=\"hidden\" name=\"action\" value=\"$action\">";
    $archivos=$this->listarDirectorios();
    $tm=$this->RevisarTema($uid);
		if($spia==true)
		{
       if(empty($tm))
			 {
			  		$this->salida .=" <option value=\"-1\">Default</option>";
						for($i=0;$i<sizeof($archivos);$i++){
								if($archivos[$i]==$tema){
									$this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
								}else{
									$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
								}
						}
				}
				else
				{
            $this->salida .=" <option value=\"-1\">Default</option>";
						for($i=0;$i<sizeof($archivos);$i++){
								if($archivos[$i]==$tm){
								  $this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
								}else{
									$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
								}
						}
					}
		}
    elseif(empty($spia))
		{

				$this->salida .=" <option value=\"-1\">Default</option>";
				for($i=0;$i<sizeof($archivos);$i++){
						if($archivos[$i]==$tema){
							$this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
						}else{
							$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
						}
				}
		}
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "              <tr class=\"modulo_list_claro\"><td class=\"label\" align=\"left\">ACTIVO   <input type=\"checkbox\" name=\"activo\" checked></td>";
    $this->salida .= "              <td align=\"left\" class=\"label\">USUARIO EMPRESA</td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCION: </td><td><textarea class=\"textarea\" name=\"descripcion\" cols=\"20\" rows=\"2\">$descripcion</textarea></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("loginUsuario")."\">LOGIN: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"loginUsuario\" maxlength=\"25\" value=\"$loginUsuario\"></td></tr>";
    $this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";

		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Insertar\"><br></td>";
		$this->salida .= "			      </form>";
    $action3=ModuloGetURL('system','Administrador','admin','ListadoUsuarioEmpresa',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Cancelar\"></td></tr>";
    $this->salida .= "			      </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "            </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




 /*	Listado de los modulos del sistema los cuales el Administrador podr? desactivarlos
	*	? activarlos.
 */
 function ListadoModulos()
 {
				$vector=$this->TraerModulo();	//trae los modulos del sistema...
				$this->salida.= ThemeAbrirTabla('ADMINISTRACION MODULOS SIIS');
        if($vector)
				{
          $this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"4\"> MODULOS DEL SISTEMA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td width='10%'>Tipo</td>";
					$this->salida.="  <td width='10%'>Modulo</td>";
					$this->salida.="  <td width='25%'>Descripcion</td>";
					$this->salida.="  <td width='15%'>Estado</td>";
					//$this->salida.="  <td width='15%'>Switch User</td>";
					//$this->salida.="  <td width='15%'>Switch Admin</td>";
          $this->salida.="</tr>";
					for($i=0;$i<sizeof($vector);$i++)
					{
							$modulo=$vector[$i][modulo];
							$tipo_mod=$vector[$i][modulo_tipo];
							$desc=$vector[$i][descripcion];
							$activo=$vector[$i][activo];
							//$sw_user=$vector[$i][sw_user];  Ojo despues puede servir,preguntar.
						//	$sw_admin=$vector[$i][sw_admin];
						/*	if($sw_admin=='1')
							{
               $infoA='DESACTIVAR';
							 $figura1='planactivo.png';
							}
							else
							{
               $infoA='ACTIVAR';
							 $figura1='planinactivo.png';
							}*/
						/*	if($sw_user=='1')
							{
								$infoU='DESACTIVAR';
								$figura2='planactivo.png';
							}
							else
							{
								$infoU='ACTIVAR';
								$figura2='planinactivo.png';
							}*/
							if($activo=='1')
							{
								$info='DESACTIVAR';
								$figura='pactivo.png';
							}
							else
							{
								$info='ACTIVAR';
								$figura='pinactivo.png';
							}

							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" align=\"left\">";
							$this->salida.="  <td> $tipo_mod</td>";
							$this->salida.="  <td>$modulo</td>";
							$this->salida.="  <td>$desc</td>";
							$this->salida.="  <td><img src=\"".GetThemePath()."/images/$figura\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','ModificarEstadoActivo',array("mod"=>$modulo,"tipos1"=>$tipo_mod,"decision"=>'activo'))."\">$info</a></td>";
							//$this->salida.="  <td><img src=\"".GetThemePath()."/images/$figura2\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','ModificarEstadoActivo',array("mod"=>$modulo,"tipos1"=>$tipo_mod,"decision"=>'sw_user'))."\">$infoU</a></td>";
							//$this->salida.="  <td><img src=\"".GetThemePath()."/images/$figura1\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','Administrador','admin','ModificarEstadoActivo',array("mod"=>$modulo,"tipos1"=>$tipo_mod,"decision"=>'sw_admin'))."\">$infoA</a></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$action2=ModuloGetURL('system','Administrador','admin','main');
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";

				}
$this->salida .= ThemeCerrarTabla();
return true;

 }


}//fin clase user
?>

