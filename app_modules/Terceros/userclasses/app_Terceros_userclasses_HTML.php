
<?php

/**
* Modulo de Terceros (PHP).
*
* Modulo que permite el mantenimiento de los terceros relacionados a
* la empresa, así como el tipo de relación que mantienen
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Terceros_userclasses_HTML.php
*
* Clase que establece los métodos y las opciones en las que un tercero puede
* relacionarse con la empresa, es decir desde cliente, proveedor, entre otros
**/

class app_Terceros_userclasses_HTML extends app_Terceros_user
{
	function app_Terceros_user_HTML()
	{
		$this->app_Terceros_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de TERCEROS
	function PrincipalTercer()//Informa que el modulo no tiene acceso directo
	{
		$this->salida  = ThemeAbrirTabla('TERCEROS');
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td width=\"100%\">";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"10%\" class=\"label\" align=\"center\">";
		$this->salida .= "      <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"90%\" class=\"label\" align=\"left\">";
		$this->salida .= "ESTE MODULO NO TIENE ACCESO PERMITIDO DE MANERA DIRECTA";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece una busqueda para saber si el tercero esta creado o no
	function BusquedaTercer()//Permite hacer una busqueda del tercero para modificar
	{
		if($_SESSION['tercer']['empresa']==NULL)
		{
			$_SESSION['INFORM']['RETORNO']['sw']=3;//Empresa sin sesion
			$this->ReturnMetodoExterno($_SESSION['INFORM']['RETORNO']['contenedor'],
			$_SESSION['INFORM']['RETORNO']['modulo'],
			$_SESSION['INFORM']['RETORNO']['tipo'],
			$_SESSION['INFORM']['RETORNO']['metodo']);
			return true;
		}
		if($_SESSION['tercer']['tipo_id_tercero']==NULL AND $_SESSION['tercer']['tercero_id']==NULL)
		{
			$this->salida  = ThemeAbrirTabla('TERCEROS - BUSQUEDA');
			$accion=ModuloGetURL('app','Terceros','user','ValidarBusquedaTercer');
			$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <table border=\"0\" width=\"45%\" align=\"center\">";
			$this->salida .= "  <tr><td>";
			$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL TERCERO</legend>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"label\" width=\"40%\">TIPO DOCUMENTO:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"60%\">";
			$this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
			$var=explode(',',$_POST['TipoDocum']);
			$_POST['TipoDocum']=$var[0];
			$tipo_id=$this->TercerosTercer();
			$this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['TipoDocum']);
			$this->salida .= "      </select>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"label\">DOCUMENTO:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td>";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"DocumentoB\" value=\"".$_POST['DocumentoB']."\" maxlength=\"32\" size=\"26\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
			$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </form>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "  </fieldset>";
			$this->salida .= "  </td></tr>";
			$accion=ModuloGetURL('app','Terceros','user','BorrarTercer');
			$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td align=\"center\" colspan=\"2\"><br>";
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
			$this->salida .= "  </td>";
			$this->salida .= "  </form>";
			$this->salida .= "  </tr>";
			if($this->uno == 1)
			{
				if($_POST['DocumentoB']<>NULL)
				{
					$this->salida .= "  <tr>";
					$this->salida .= "  <td align=\"center\" colspan=\"2\"><br>";
					$accion=ModuloGetURL('app','Terceros','user','IngresaTercer',array('TipoDocum'=>$_POST['TipoDocum'],'DocumentoB'=>$_POST['DocumentoB']));
					$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
					$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"NUEVO\">";
					$this->salida .= "  </td>";
					$this->salida .= "  </form>";
					$this->salida .= "  </tr>";
				}
				$this->salida .= "  <tr>";
				$this->salida .= "  <td align=\"center\" colspan=\"2\"><br>";
				$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  </table>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->uno=0;
			}
			$this->salida .= "  </table>";
			$this->salida .= ThemeCerrarTabla();
		}
		else
		{
			$_SESSION['INFORM']['DATOS']=$this->BuscaDatosTercer($_SESSION['tercer']['tipo_id_tercero'],$_SESSION['tercer']['tercero_id']);
			$this->IngresaTercer();
		}
		return true;
	}

	//Función que captura la información básica de los terceros
	function IngresaTercer()//Válida desde donde fue llamado y que debe hacer (guardar o modificar)
	{
		if($_SESSION['tercer']['empresa']==NULL)
		{
			$_SESSION['INFORM']['RETORNO']['sw']=3;//Empresa sin sesion
			$this->ReturnMetodoExterno($_SESSION['INFORM']['RETORNO']['contenedor'],
			$_SESSION['INFORM']['RETORNO']['modulo'],
			$_SESSION['INFORM']['RETORNO']['tipo'],
			$_SESSION['INFORM']['RETORNO']['metodo']);
			return true;
		}
		if(!($this->uno == 1))
		{
			if(!empty($_SESSION['INFORM']['DATOS']))
			{
				$_POST['TipoDocum']=$_SESSION['INFORM']['DATOS']['tipo_id_tercero'];
				$_POST['Documento']=$_SESSION['INFORM']['DATOS']['tercero_id'];
				$_POST['nombre']=$_SESSION['INFORM']['DATOS']['nombre_tercero'];
				$_POST['direccion']=$_SESSION['INFORM']['DATOS']['direccion'];
				$_POST['pais']=$_SESSION['INFORM']['DATOS']['tipo_pais_id'];
				$_POST['dpto']=$_SESSION['INFORM']['DATOS']['tipo_dpto_id'];
				$_POST['mpio']=$_SESSION['INFORM']['DATOS']['tipo_mpio_id'];
				$_POST['telefono']=$_SESSION['INFORM']['DATOS']['telefono'];
				$_POST['celular']=$_SESSION['INFORM']['DATOS']['celular'];
				$_POST['fax']=$_SESSION['INFORM']['DATOS']['fax'];
				$_POST['email']=$_SESSION['INFORM']['DATOS']['email'];
				$var=explode('-',$_SESSION['INFORM']['DATOS']['busca_persona']);
				$_POST['beeper1']=$var[0];
				$_POST['beeper2']=$var[1];
				$_SESSION['INFORM']['DATOS']['existe']=1;
				if($_SESSION['INFORM']['DATOS']['sw_persona_juridica']==1)
				{
					$_POST['persona']=1;
				}
				else
				{
					$_POST['persona']=2;
				}
			}
			else if($_REQUEST['TipoDocum']<>NULL AND $_REQUEST['DocumentoB']<>NULL)
			{
				$_POST['Documento']=$_REQUEST['DocumentoB'];
				$_POST['TipoDocum']=$_REQUEST['TipoDocum'];
			}
			else
			{
				$_POST['Documento']=$_SESSION['tercer']['tercero_id'];
				$_POST['TipoDocum']=$_SESSION['tercer']['tipo_id_tercero'];
			}
		}
		$this->salida  = ThemeAbrirTabla('TERCEROS');
		$accion=ModuloGetURL('app','Terceros','user','ValidarIngresaTercer');
		$ru='classes/BuscadorDestino/selectorCiudad.js';
		$rus='classes/BuscadorDestino/selector.php';
		$this->salida .= "  <script languaje='javascript' src=\"$ru\"></script>";
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL TERCERO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['tercer']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("TipoDocum")."\" width=\"40%\">TIPO DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		$var=explode(',',$_POST['TipoDocum']);
		$_POST['TipoDocum']=$var[0];
		$tipo_id=$this->TercerosTercer();
		if($_POST['TipoDocum']==NULL AND $_SESSION['INFORM']['DATOS']==NULL)
		{
			$this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
			$this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['TipoDocum']);
			$this->salida .= "      </select>";
		}
		else
		{
			foreach($tipo_id as $value=>$titulo)
			{
				if($value==$_POST['TipoDocum'])
				{
					$_POST['TipoDocum']=$value.','.$titulo;
					$this->salida .= "<input type=\"hidden\" name=\"TipoDocum\" value=\"".$_POST['TipoDocum']."\">";
					$this->salida .="".$titulo."";
				}
			}
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"".$_POST['Documento']."\" maxlength=\"32\" size=\"32\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("nombre")."\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"".$_POST['nombre']."\" maxlength=\"60\" size=\"60\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("direccion")."\">DIRECCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"direccion\" value=\"".$_POST['direccion']."\" maxlength=\"100\" size=\"60\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
		{
			$_POST['pais']=GetVarConfigAplication('DefaultPais');
			$_POST['dpto']=GetVarConfigAplication('DefaultDpto');
			$_POST['mpio']=GetVarConfigAplication('DefaultMpio');
		}
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
		$_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
		$_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
		$_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"label\">TELÉFONO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefono\" value=\"".$_POST['telefono']."\" maxlength=\"30\" size=\"25\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">CELULAR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"celular\" value=\"".$_POST['celular']."\" maxlength=\"15\" size=\"25\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"label\">FAX:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fax\" value=\"".$_POST['fax']."\" maxlength=\"15\" size=\"25\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">E - MAIL";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"email\" value=\"".$_POST['email']."\" maxlength=\"60\" size=\"25\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"label\">BEEPER:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"beeper1\" value=\"".$_POST['beeper1']."\" maxlength=\"15\" size=\"25\">";
		$this->salida .= "      <label class=\"label\">"."CÓD:"."</label>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"beeper2\" value=\"".$_POST['beeper2']."\" maxlength=\"9\" size=\"9\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("persona")."\">PERSONA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">JURIDICA";
		if($_POST['persona']==1)
		{
			$this->salida .= "      <input type='radio' name=\"persona\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name=\"persona\" value=1>";
		}
		$this->salida .= "  NATURAL";
		if($_POST['persona']==2)
		{
			$this->salida .= "      <input type='radio' name=\"persona\" value=2 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name=\"persona\" value=2>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Terceros','user','BorrarTercer');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
