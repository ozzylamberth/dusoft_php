<?php
/**
 * $Id: app_CompararBD_userclasses_HTML.php,v 1.4 2005/12/22 22:25:51 ehudes Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CompararBD_userclasses_HTML extends app_CompararBD_user
{
	/**
	 * Sirve de bandera para cuando hay un error de formulario
	 * (campos obligatorios, logica de interfaz de usuario)
	 * 
	 * @var int
	 */
	var $ErrorDatos;
	
	/**
	 * Constructor
	 */
	function app_CompararBD_userclasses_HTML()
	{
		$this->app_CompararBD_user(); //Constructor del padre 'modulo'
		$this->salida = '';
		$this->ErrorDatos='';
		return true;
	}
	 
	/**
	 * Funcion principal del modulo
	 */
	function main()
	{
		$this->salida = ThemeAbrirTabla('COMAPARAR BASES DE DATOS','90%');
		$this->FormaPrincipal();
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	 
	/**
	 * Forma principal para ingresar las bases de datos que seran comparadas
	 */
	function FormaPrincipal()
	{
		$action=ModuloGetURL('app','CompararBD','user','FormaCompararBasesDeDatos');
		$DatosConexionActual=$this->GetDatosConexionActual();
		$this->salida .= "<script>\n
									function LlenarDatosBD()\n
									{\n
										document.FrmConn.HOST1.value='".$DatosConexionActual['dbhost']."';
										document.FrmConn.BD1.value='".$DatosConexionActual['dbname']."';
										document.FrmConn.UserBD1.value='".$DatosConexionActual['dbuser']."';
										document.FrmConn.Paswd1.value='".$DatosConexionActual['dbpass']."';
									}\n
									</script>\n";
		$this->salida .= "<table width=\"60%\" align=\"center\">\n";
		$this->salida .= "<tr>\n";
		$this->salida .= "<td align=\"center\">\n";
		$this->salida .= "\n";
		$this->salida .= "<form action=\"$action\" method=\"POST\" name=\"FrmConn\">\n";
		$this->salida .= "	<fieldset><legend class=\"field\">BASE DE DATOS 1</legend>\n";
		$this->salida .= "		<table width=\"90%\" align=\"center\">\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">HOST:</td>\n";
		$this->salida .= "				<td><input type=\"text\" class=\"input-text\" name=\"HOST1\"  value=\"$_REQUEST[HOST1]\" size=20 maxlength=20></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">BASE DE DATOS:</td>\n";
		$this->salida .= "				<td><input type=\"text\" class=\"input-text\" name=\"BD1\"  value=\"$_REQUEST[BD1]\" size=60 maxlength=60></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">USUARIO:</td>\n";
		$this->salida .= "				<td><input type=\"text\" class=\"input-text\" name=\"UserBD1\"  value=\"$_REQUEST[UserBD1]\" size=20 maxlength=20></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">CONTRASEÑA:</td>\n";
		$this->salida .= "				<td><input type=\"password\"  class=\"input-text\" name=\"Paswd1\"  value=\"$_REQUEST[Paswd1]\" size=20 maxlength=20>\n";
		$this->salida .= "				<input type=\"button\"  class=\"input-submit\" name=\"Paswd1\"  value=\"BD Actual\" onclick=\"LlenarDatosBD();\"></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "		</table>\n";
		$this->salida .= "	</fieldset>\n";
		$this->salida .= "	<fieldset><legend class=\"field\">BASE DE DATOS 2</legend>\n";
		$this->salida .= "		<table width=\"90%\" cellspacing=1 border=0 cellpadding=2 align=\"center\">\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">HOST:</td>\n";
		$this->salida .= "				<td><input type=\"text\" class=\"input-text\" name=\"HOST2\" value=\"$_REQUEST[HOST2]\" size=20 maxlength=20></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">BASE DE DATOS:</td>\n";
		$this->salida .= "				<td><input type=\"text\" class=\"input-text\" name=\"BD2\" value=\"$_REQUEST[BD2]\" size=60 maxlength=60></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">USUARIO:</td>\n";
		$this->salida .= "				<td><input type=\"text\" class=\"input-text\" name=\"UserBD2\" value=\"$_REQUEST[UserBD2]\" size=20 maxlength=20></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td class=\"label\">CONTRASEÑA:</td>\n";
		$this->salida .= "				<td><input type=\"password\" class=\"input-text\" name=\"Paswd2\" value=\"$_REQUEST[Paswd2]\" size=20 maxlength=20></td>\n";
		$this->salida .= "			</tr>\n";
		$this->salida .= "    </table>\n";
		$this->salida .= "	</fieldset>\n";
		$checked[1]="checked";
		if(isset($_REQUEST['comparar']) || isset($_REQUEST['BD2']))
		{
			$OpcionesComparar=$_REQUEST['comparar'];
			$disabled = "disabled";
		}
		else
		{
			$OpcionesComparar = $this->GetOpcionesDefaultComparar();
		}
		$disabled="";
		$this->salida .= "	<fieldset><legend class=\"field\">COMPARAR</legend>\n";
		$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td class=\"label\">\n";
		$this->salida .= "			<fieldset><legend class=\"field\"><input type=\"checkbox\" class=\"input-text\" name=\"comparar[tablas]\"  value=\"1\" {$checked[$OpcionesComparar['tablas']]} >TABLAS</legend>\n";
		$this->salida .= "				<table width=\"90%\" align=\"center\">\n";
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td class=\"label\">\n";
		$this->salida .= "							<input type=\"checkbox\" class=\"input-text\" id=\"campos\"  name=\"comparar[campos]\"  value=\"1\" {$checked[$OpcionesComparar['campos']]} \"$disabled\">CAMPOS\n";
		$this->salida .= "						</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td class=\"label\">\n";
		$this->salida .= "							<input type=\"checkbox\" class=\"input-text\" id=\"restricciones\" name=\"comparar[restricciones]\"  value=\"1\" {$checked[$OpcionesComparar['restricciones']]} >RESTRICCIONES\n";
		$this->salida .= "						</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td class=\"label\">\n";
		$this->salida .= "							<input type=\"checkbox\" class=\"input-text\" id=\"indices\" name=\"comparar[indices]\"  value=\"1\" {$checked[$OpcionesComparar['indices']]} >INDICES\n";
		$this->salida .= "						</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td class=\"label\">\n";
		$this->salida .= "							<input type=\"checkbox\" class=\"input-text\" id=\"triggers\" name=\"comparar[triggers]\"  value=\"1\" {$checked[$OpcionesComparar['triggers']]} >TRIGGERS\n";
		$this->salida .= "						</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "				</table>\n";
		$this->salida .= "			</fieldset>\n";		
		$this->salida .= "			</td>\n";
		$this->salida .= "			<td class=\"label\"><input type=\"checkbox\" class=\"input-text\" name=\"comparar[funciones]\"  value=\"1\" {$checked[$OpcionesComparar['funciones']]}>FUNCIONES</td>\n";
		$this->salida .= "			<td class=\"label\"><input type=\"checkbox\" class=\"input-text\" name=\"comparar[vistas]\"  value=\"1\" {$checked[$OpcionesComparar['vistas']]}>VISTAS</td>\n";
		$this->salida .= "			<td class=\"label\"><input type=\"checkbox\" class=\"input-text\" name=\"comparar[secuencias]\"  value=\"1\" {$checked[$OpcionesComparar['secuencias']]}>SECUENCIAS</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table>\n";
		$this->salida .= "	</fieldset>\n";
		$this->salida .= "<br>";
		$this->salida .= "	<input type=\"submit\" class=\"input-submit\" value=\"Comparar\">";
		$this->salida .= "</form>\n";
		$accion=ModuloGetUrl('system','Menu');
		$this->salida .= "	<form name=\"frmVolver\" action=\"$accion\" method=\"POST\">";
		$this->salida .= "		<input type=\"submit\" class=\"input-submit\" value=\"Volver\">";
		$this->salida .= "	</form>";
		$this->salida .= "</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida .= "</table>\n";
		return true;
	}
	
	/**
	 * Retorna un arreglo con la opciones que por default deben estat checkeadas de las opcionces
	 * de comparar
	 *
	 * @access private
	 */
	function GetOpcionesDefaultComparar()
	{
		return array('tablas'=>1,'campos'=>1,'restricciones'=>1,'funciones'=>1);
	}//Fin GetOpcionesDefaultComparar
	
	/**
	 * Conduce el caso de Comparar Bases de Datos
	 */
	function FormaCompararBasesDeDatos()
	{
		$this->salida .= ThemeAbrirTabla('COMAPARAR BASES DE DATOS','90%');
		//Se capturan las variables que vienen por request y se valida las obligatorias
		if(empty($_REQUEST['HOST1']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO HOST DE LA BASE DE DATOS 1");
		if(empty($_REQUEST['BD1']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO BASE DE DATOS DE LA BASE DE DATOS 1");
		if(empty($_REQUEST['UserBD1']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO USUARIO DE DATOS DE LA BASE DE DATOS 1");
		if(empty($_REQUEST['Paswd1']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO CONTRASEÑA DE LA BASE DE DATOS 1");
		if(empty($_REQUEST['HOST2']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO HOST DE LA BASE DE DATOS 2");
		if(empty($_REQUEST['BD2']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO BASE DE DATOS DE LA BASE DE DATOS 2");
		if(empty($_REQUEST['UserBD2']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO USUARIO DE DATOS DE LA BASE DE DATOS 2");
		if(empty($_REQUEST['Paswd2']))
			$this->FormaError("POR FAVOR INGRESE EL CAMPO CONTRASEÑA DE LA BASE DE DATOS 1");
		if(empty($_REQUEST['comparar']))
			$this->FormaError("POR FAVOR SELECCIONE ALGUNA OPCIÓN DE COMPARAR");
		//Se imprime la forma principal
		if(empty($this->ErrorDatos))
		{
			if($this->CompararBasesDeDatos($_REQUEST['comparar']))
			{
				$this->FormaPrincipal();
				$this->salida .= "<br>";
				if($_REQUEST['comparar']['tablas'])
				{
					$this->FormaTabla($this->TablasNoDB2,"TABLAS QUE ESTÁN EN {$_REQUEST['BD1']} Y NO ESTÁN EN {$_REQUEST['BD2']}");
					$this->salida .= "<br>";
					$this->FormaTabla($this->TablasNoDB1,"TABLAS QUE ESTÁN EN {$_REQUEST['BD2']} Y NO ESTÁN EN {$_REQUEST['BD1']}");
				}
				if(!empty($this->CompararTablas))
				{
					$this->salida .= "<br>";
					$this->FormaTablasCampos1();
				}
				$this->salida .="<br>";
				if($_REQUEST['comparar']['funciones'])
				{
					$this->FormaTabla($this->Funciones1,"FUNCIONES QUE ESTÁN EN {$_REQUEST['BD1']} Y NO ESTÁN EN {$_REQUEST['BD2']}");
					$this->salida .= "<br>";
					$this->FormaTabla($this->Funciones2,"FUNCIONES QUE ESTÁN EN {$_REQUEST['BD2']} Y NO ESTÁN EN {$_REQUEST['BD1']}");
					$this->salida .= "<br>";
					$this->FormaTabla($this->Funciones3,"FUNCIONES QUE ESTÁN EN {$_REQUEST['BD1']} Y ESTÁN EN {$_REQUEST['BD2']} CON DIFERENCIAS");
					$this->salida .= "<br>";
				}
				if($_REQUEST['comparar']['vistas'])
				{
					$this->FormaTabla($this->Vistas1,"VISTAS QUE ESTÁN EN {$_REQUEST['BD1']} Y NO ESTÁN EN {$_REQUEST['BD2']}");
					$this->salida .= "<br>";
					$this->FormaTabla($this->Vistas2,"VISTAS QUE ESTÁN EN {$_REQUEST['BD2']} Y NO ESTÁN EN {$_REQUEST['BD1']}");
					$this->salida .= "<br>";
				}
				if($_REQUEST['comparar']['secuencias'])
				{
					$this->FormaTabla($this->Secuencias1,"SECUENCIAS QUE ESTÁN EN {$_REQUEST['BD1']} Y NO ESTÁN EN {$_REQUEST['BD2']}");
					$this->salida .= "<br>";
					$this->FormaTabla($this->Secuencias2,"SECUENCIAS QUE ESTÁN EN {$_REQUEST['BD2']} Y NO ESTÁN EN {$_REQUEST['BD1']}");
					$this->salida .= "<br>";
				}
			}
			else
			{
				$this->FormaError($this->error.":".$this->mensajeDeError);
				$this->FormaPrincipal();
			}
		}
		else
			$this->FormaPrincipal();
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	/**
	 * Imprime un mensaje de error de entrada de datos
	 */
	function FormaError($mensaje)
	{
		$this->salida .= "<div align='center' class='label_error'>$mensaje</div>\n";
		$this->ErrorDatos=1;//Bandera que indica que hubo un error en los datos del formulario
		return true;
	}//Fin Error;

	/**
	 * Imprime una tabla con dos columnas
	 *
	 * @param array datos
	 * @param string titulo
	 */
	function FormaTabla($datos,$titulo="")
	{
		if(!empty($datos))
		{
			$this->salida .= "<table width=\"80%\" class=\"modulo_table_list\" cellspacing=\"1\" cellpadding=\"2\" align=\"center\">\n";
			$this->salida .= "   <tr class=\"modulo_table_title\">\n";
			$this->salida .= "     <td colspan=\"2\" align=\"center\"><b>$titulo</b></td>\n";
			$this->salida .= "   </tr>\n";
			$this->salida .= "   <tr class=\"modulo_table_title\">\n";
			$this->salida .= "     <td><b>No.</b></td>\n";
			$this->salida .= "     <td><b>DESCRIPCIÓN</b></td>\n";
			$this->salida .= "   </tr>\n";
			$k=0;
			foreach($datos as $v)
			{
				$numerotabla=str_pad($k+1, 3, "0", STR_PAD_LEFT);
				if($k%2){$estilo="modulos_list_claro";}
				else{$estilo="modulo_list_oscuro";}
				$k++;
				$this->salida .= "   <tr class=\"$estilo\">\n";
				$this->salida .= "     <td>$numerotabla</td>\n";
				$this->salida .= "     <td>$v</td>\n";
				$this->salida .= "   </tr>\n";
			}
			$this->salida .= "</table>\n";
		}
		return true;
	}

	/**
	 * Imprime una tabla con las diferencias de las tablas en los
	 * campos, restricciones, indices, triggers
	 * 
	 * @param array comparar
	 */
	function FormaTablasCampos($comparar)
	{
		if(empty($comparar))
		{
			if(empty($this->CompararTablas))
				return array();
			else
				$comparar=$this->CompararTablas;
		}
		else
			$this->CompararTablas=$comparar;
		$datos=$this->TablasDiferencias;
		if(!empty($datos))
		{
			$this->salida .= "<table width=\"80%\" class=\"modulo_table_list\" cellspacing=\"1\" cellpadding=\"2\" align=\"center\">\n";
			$this->salida .= "	<tr class=\"modulo_table_title\">\n";
			$colspan=sizeof($comparar)*2+2;
			$this->salida .= "		<td witdh=\"5%\" colspan=\"$colspan\" align=\"center\"><b>DIFERENCIAS DE LAS TABLAS</b></td>\n";
			$this->salida .= "	</tr>";
			$this->salida .= "	<tr class=\"modulo_table_title\">\n";
			$this->salida .= "		<td witdh=\"5%\"><b>No.</b></td>\n";
			$this->salida .= "		<td witdh=\"15%\"><b>NOMBRE DE LA TABLA</b></td>\n";
			if(isset($comparar['campos']))
			{
				$this->salida .= "		<td witdh=\"10%\"><b>CAMPOS+ DB1</b></td>\n";
				$this->salida .= "		<td witdh=\"10%\"><b>CAMPOS+ DB2</b></td>\n";
			}
			if(isset($comparar['restricciones']))
			{
				$this->salida .= "		<td witdh=\"10%\"><b>RESTRICCIONES+ DB1</b></td>\n";
				$this->salida .= "		<td witdh=\"10%\"><b>RESTRICCIONES+ DB2</b></td>\n";
			}
			if(isset($comparar['indices']))
			{
				$this->salida .= "		<td witdh=\"10%\"><b>INDICES+ DB1</b></td>\n";
				$this->salida .= "		<td witdh=\"10%\"><b>INDICES+ DB2</b></td>\n";
			}
			if(isset($comparar['triggers']))
			{
				$this->salida .= "		<td witdh=\"10%\"><b>TRIGGERS+ DB1</b></td>\n";
				$this->salida .= "		<td witdh=\"10%\"><b>TRIGGERS+ DB2</b></td>\n";
			}
			$this->salida .= "	</tr>\n";
			$cont=0;
			foreach($datos as $NombreTabla=>$Tabla)
			{
				
				$numerotabla=str_pad($cont+1, 3, "0", STR_PAD_LEFT);
				if($cont%2){$estilo="modulos_list_claro";}
				else{$estilo="modulo_list_oscuro";}	
				$cont++;
				$this->salida .= "	<tr class=\"$estilo\">\n";
				$this->salida .= "		<td>$numerotabla</td>\n";
				$this->salida .= "		<td>$NombreTabla</td>\n";
				foreach($comparar as $key=>$valor)
				{
					
					if(!empty($Tabla[$key]))
					{
						$this->salida .= "<td>\n";
						if(!empty($Tabla[$key]['db1']))
						{
							foreach($Tabla[$key]['db1'] as $Texto)
							{
								$this->salida .= "<div>$Texto</div>";
							}
						}
						else
						{
							$this->salida .= "&nbsp;";
						}
						$this->salida .= "</td>\n";
						$this->salida .= "<td>\n";
						if(!empty($Tabla[$key]['db2']))
						{
							foreach($Tabla[$key]['db2'] as $Texto)
							{
								$this->salida .= "<div>$Texto</div>";
							}
						}
						else
						{
							$this->salida .= "&nbsp;";
						}
						$this->salida .= "</td>\n";
					}
					else
					{
						$this->salida .= "<td>&nbsp;</td>\n";
						$this->salida .= "<td>&nbsp;</td>\n";
					}
				}
				$this->salida .= "</tr>\n";
			}
		$this->salida .= "</table>\n";
		}
		return true;
	}//Fin FormaTablasCampos
	
	
	/**
	 * Imprime las tablas con las diferencias de las tablas en los
	 * campos, restricciones, indices, triggers en comparar viene
	 * que se va a comparar
	 * 
	 * @param array comparar
	 */
	function FormaTablasCampos1($comparar)
	{
		if(empty($comparar))
		{
			if(empty($this->CompararTablas))
				return array();
			else
				$comparar=$this->CompararTablas;
		}
		else
			$this->CompararTablas=$comparar;
		$datos=$this->TablasDiferencias;
		if(!empty($datos))
		{
			foreach($comparar as $key=>$valor)
			{
				$TablasHTML[$key] .= "<table width=\"80%\" class=\"modulo_table_list\" cellspacing=\"1\" cellpadding=\"2\" align=\"center\">\n";
				$TablasHTML[$key] .= "	<tr class=\"modulo_table_title\">\n";
				//$colspan=sizeof($comparar)*2+2;
				$TablasHTML[$key] .= "		<td colspan=\"4\" align=\"center\"><b>DIFERENCIAS DE LAS TABLAS EN ".strtoupper($key)."</b></td>\n";
				$TablasHTML[$key] .= "	</tr>";
				$TablasHTML[$key] .= "	<tr class=\"modulo_table_title\">\n";
				$TablasHTML[$key] .= "		<td witdh=\"5%\"><b>No.</b></td>\n";
				$TablasHTML[$key] .= "		<td witdh=\"15%\"><b>NOMBRE DE LA TABLA</b></td>\n";
				$TablasHTML[$key] .= "		<td witdh=\"40%\"><b>".strtoupper($key)."+ {$_REQUEST['BD1']}</b></td>\n";
				$TablasHTML[$key] .= "		<td witdh=\"40%\"><b>".strtoupper($key)."+ {$_REQUEST['BD2']}</b></td>\n";
				$TablasHTML[$key] .= "	</tr>\n";
			}

			foreach($datos as $NombreTabla=>$Tabla)
			{
				foreach($comparar as $key=>$valor)
				{
					if(!empty($Tabla[$key]))
					{
						if(empty($cont[$key]))
							$cont[$key]=0;
						$numerotabla=str_pad($cont[$key]+1, 3, "0", STR_PAD_LEFT);
						if($cont[$key]%2){$estilo="modulos_list_claro";}
						else{$estilo="modulo_list_oscuro";}
						$cont[$key]++;
						$TablasHTML[$key] .= "	<tr class=\"$estilo\">\n";
						$TablasHTML[$key] .= "		<td>$numerotabla</td>\n";
						$TablasHTML[$key] .= "		<td>$NombreTabla</td>\n";
						$TablasHTML[$key] .= "<td>\n";
						if(!empty($Tabla[$key]['db1']))
						{
							foreach($Tabla[$key]['db1'] as $Texto)
							{
								$TablasHTML[$key] .= "<div>$Texto</div>";
							}
						}
						else
						{
							$TablasHTML[$key] .= "&nbsp;";
						}
						$TablasHTML[$key] .= "</td>\n";
						$TablasHTML[$key] .= "<td>\n";
						if(!empty($Tabla[$key]['db2']))
						{
							foreach($Tabla[$key]['db2'] as $Texto)
							{
								$TablasHTML[$key] .= "<div>$Texto</div>";
							}
						}
						else
						{
							$TablasHTML[$key] .= "&nbsp;";
						}
						$TablasHTML[$key] .= "</td>\n";
					}
					$TablasHTML[$key] .= "</tr>\n";
				}
			}
			foreach($comparar as $key=>$valor)
			{
				$this->salida .= $TablasHTML[$key];
				$this->salida .= "</table>\n";
				$this->salida .= "</br>\n";
			}
		}
		return true;
	}//Fin FormaTablasCampos
}//fin de la clase
?>

