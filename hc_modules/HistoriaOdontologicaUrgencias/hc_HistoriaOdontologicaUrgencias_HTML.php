<?php

/**
 * $Id: hc_HistoriaOdontologicaUrgencias_HTML.php,v 1.5 2005/07/18 20:53:23 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Submodulo para controlar las acciones preventivas de odontologia a realizar en el paciente
 */

/**
* Accion Preventiva
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de accion preventiva.
*/

class HistoriaOdontologicaUrgencias_HTML extends HistoriaOdontologicaUrgencias
{
//USUARIO HIGIENISTA	: ipspasolcb
//PASSWORD						: 123456
	function HistoriaOdontologicaUrgencias_HTML()
	{
		$this->HistoriaOdontologicaUrgencias();//constructor del padre
		return true;
	}

	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align=\"center\" class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}

// 	function frmForma()//Desde esta funcion es de JORGE AVILA
// 	{
// 		$pfj=$this->frmPrefijo;
// 		$this->salida =ThemeAbrirTablaSubModulo('HISTORIA CLINICA URGENCIAS ODONTOLOGIA');
// 		echo ENTRO;
// 		$datos=$this->BuscarAccionPreventivaAnterior();
// 		if(!empty($datos) AND $datos[0][evolucion_id]!=$this->evolucion)
// 		{
// 					$this->frmAccionPrimeraVez($datos);
// 		}
// 		else
// 		{
// 				$datos='';
// 				$datos=$this->BuscarAccionPreventiva();
// 				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar','datos'.$pfj=>$datos));
// 				$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
// 				$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
// 				$this->salida.=$this->SetStyle("MensajeError");
// 				$this->salida.="</table>";
// 				$this->salida.="<table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
// 				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\"><td></td><td></td><td align=center class=label>FRECUENCIA</td></tr>";
// 				$ciclo=sizeof($datos);
// 				$j=0;
// 				for($i=0;$i<$ciclo;$i++)
// 				{
// 					if($j==0)
// 					{
// 						$color="class=\"hc_submodulo_list_claro\"";
// 						$j=1;
// 					}
// 					else
// 					{
// 						$color="class=\"hc_submodulo_list_oscuro\"";
// 						$j=0;
// 					}
// 					$this->salida.="<tr $color>";
// 					$this->salida.="<td width=\"35%\" align=\"center\">";
// 					$this->salida.="<label class=\"label\">".$datos[$i]['nombre']."</label>";
// 					$this->salida.="</td>";
// 					$this->salida.="<td width=\"10%\" align=\"center\">";
// 					if($datos[$i]['tipo_accion_id']<>7)
// 					{
// 						$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\">";
// 						$this->salida.="	<tr>";
// 						$this->salida.="	<td class=\"label\" align=\"right\">";
// 						$this->salida.="SI    ";
// 						if($datos[$i]['sw_accion_preventiva']==1 AND $datos[$i]['hc_accion_preventiva_id']<>NULL)
// 						{
// 							$this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=1 checked>";
// 						}
// 						else
// 						{
// 							$this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=1>";
// 						}
// 						$this->salida.="	</td>";
// 						$this->salida.="	</tr>";
// 						$this->salida.="	<tr>";
// 						$this->salida.="	<td class=\"label\" align=\"right\">";
// 						$this->salida.="NO    ";
// 						if($datos[$i]['sw_accion_preventiva']==0 AND $datos[$i]['hc_accion_preventiva_id']<>NULL)
// 						{
// 							$this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=0 checked>";
// 						}
// 						else
// 						{
// 							$this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=0>";
// 						}
// 						$this->salida.="	</td>";
// 						$this->salida.="	</tr>";
// 						$this->salida.="	</table>";
// 					}
// 					$this->salida.="</td>";
// 					$this->salida.="<td width=\"55%\" align=\"center\">";
// 					$this->salida.="<textarea class=\"input-text\" name=\"observacio".$pfj."".$i."\" cols=\"50\" rows=\"5\">".$datos[$i]['descripcion']."</textarea>";
// 					$this->salida.="</td>";
// 					$this->salida.="</tr>";
// 				}
// 				$this->salida.="</table><br>";
// 				$this->salida.="<table width=\"10%\" align=\"center\">";
// 				$this->salida.="<tr>";
// 				$this->salida.="<td align=\"center\">";
// 				$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
// 				$this->salida.="</td>";
// 				$this->salida.="</tr>";
// 				$this->salida.="</table>";
// 				$this->salida.="</form>";
// 		}
// 		$this->salida.=ThemeCerrarTablaSubModulo();
// 		return true;
// 	}

//INICIO ADICIÓN CARLOS
	function frmForma()//Desde esta funcion es de JORGE AVILA
	{
  	//echo $this->ingreso;
 		$pfj=$this->frmPrefijo;
 		$odontograma=$this->BuscarDatos();
    $observacion=$this->BuscarDatosObservacion();
		$this->salida =ThemeAbrirTablaSubModulo('EVOLUCIÓN ODONTOLOGIA URGENCIAS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$mostrar1 ="<script language='javascript'>\n";
		$mostrar1.=" 	function BuscarSuperficies(valor,prefijo){\n";
		$mostrar1.="	document.getElementById('div1').innerHTML ='<input type=checkbox name=0'+prefijo+' value=11>SUPERFICIE TOTAL<br>';\n";
		$mostrar1.="	if((valor>=11 && valor<=18 || valor>=51 && valor<=55) || (valor>=21 && valor<=28 || valor>=61 && valor<=65)){\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=2>PALATINO<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
		$mostrar1.="		if((valor>=11 && valor<=13 || valor>=51 && valor<=53) || (valor>=21 && valor<=23 || valor>=61 && valor<=63)){\n";
		$mostrar1.="			document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
		$mostrar1.="		}\n";
		$mostrar1.="		else{\n";
		$mostrar1.="			document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
		$mostrar1.="		}\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=9> CERVICAL PALATINO<br>';\n";
		$mostrar1.="	}\n";
		$mostrar1.="	else if((valor>=31 && valor<=38 || valor>=71 && valor<=75) || (valor>=41 && valor<=48 || valor>=81 && valor<=85)){\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=3>LINGUAL<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
		$mostrar1.="		if((valor>=31 && valor<=33 || valor>=71 && valor<=73) || (valor>=41 && valor<=43 || valor>=81 && valor<=83)){\n";
		$mostrar1.="			document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
		$mostrar1.="		}\n";
		$mostrar1.="		else{\n";
		$mostrar1.="			document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
		$mostrar1.="		}\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
		$mostrar1.="		document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=10> CERVICAL LINGUAL<br>';\n";
		$mostrar1.="	}\n";
		$mostrar1.="	}\n";
		$mostrar1.="	function abrirVentanaClass(url){\n";
		$mostrar1.="	var str = 'width=930,height=350,resizable=no,status=no,scrollbars=no,top=100,left=50';\n";
		$mostrar1.="	var rems = window.open(url,'',str);\n";
		$mostrar1.="	if (rems != null) {\n";
		$mostrar1.="		if (rems.opener == null) {\n";
		$mostrar1.="			rems.opener = self;\n";
		$mostrar1.="		}\n";
		$mostrar1.="	}\n";
		$mostrar1.="	}\n";
		$mostrar1.="</script>\n";
		$this->salida.="$mostrar1";
		$this->salida.='<form name="forma1'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="	<tr class=\"hc_table_list_title\">";
		$this->salida.="	<td width=\"10%\" align=\"center\">";
		$this->salida.="DIENTE";
		$this->salida.="	</td>";
		$this->salida.="	<td width=\"20%\" align=\"center\">";
		$this->salida.="SUPERFICIE";
		$this->salida.="	</td>";
		$this->salida.="	<td width=\"70%\" align=\"center\">";
		$this->salida.="HALLAZGO // SOLUCIÓN";
		$this->salida.="	</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="	<td width=\"10%\" align=\"center\">";
		$busquedas=$this->BuscarTipoUbicacion();
		$this->salida.="	<select name=\"tipoubicac".$pfj."\" class=\"select\" onchange=\"BuscarSuperficies(this[this.selectedIndex].value,'$pfj');\">";
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1 OR $_REQUEST['tipoubicac'.$pfj]==$busquedas[$i]['hc_tipo_ubicacion_diente_id'])
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."\" selected>".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."\">".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."</option>";
			}
		}
		$this->salida.="	</select>";
		$this->salida.="	</td>";
		$this->salida.="	<td width=\"20%\" align=\"left\">";
		$this->salida.="	<div id=\"div1\">";
		$this->salida.="	</div>";
		if($_REQUEST['tipoubicac'.$pfj]<>NULL)
		{
			$mostrar1 ="<script language='javascript'>\n";
			$mostrar1.="	BuscarSuperficies('".$_REQUEST['tipoubicac'.$pfj]."','$pfj');";
			$mostrar1.="</script>\n";
		}
		else
		{
			$mostrar1 ="<script language='javascript'>\n";
			$mostrar1.="	BuscarSuperficies(11,'$pfj');";
			$mostrar1.="</script>\n";
		}
		$this->salida.="$mostrar1";
		$this->salida.="	</td>";
		$this->salida.="	<td width=\"70%\" align=\"left\">";
		$this->salida.="		<table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="		<td align=\"left\" colspan=\"2\">";
		$busquedas=$this->BuscarTipoProblema();
		$this->salida.="HALLAZGO: <select name=\"tipoproble".$pfj."\" class=\"select\">";
		$a=explode(',',$_REQUEST['tipoproble'.$pfj]);
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1 OR ($busquedas[$i]['hc_tipo_problema_diente_id']==$a[0] AND $_REQUEST['tipoproble'.$pfj]<>NULL))
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\" selected>".$busquedas[$i]['descripcion']."</option>";
				//$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."\" selected>".$busquedas[$i]['descripcion']."</option>";
      }
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\">".$busquedas[$i]['descripcion']."</option>";
				//$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."\" >".$busquedas[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="	</select>";
		$this->salida.="		</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="		<td align=\"left\" colspan=\"2\">";
		$busquedas=$this->BuscarTipoProductos();
		$this->salida.="SOLUCIÓN: <select name=\"tipoproduc".$pfj."\" class=\"select\">";
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1 OR $_REQUEST['tipoproduc'.$pfj]==$busquedas[$i]['hc_tipo_producto_diente_id'])
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\" selected>".$busquedas[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\">".$busquedas[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="	</select>";
		$this->salida.="		</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="		<td width=\"50%\" align=\"center\">";
		$this->salida.="		<input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="		</td>";
		$this->salida.="		</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'apoyos'));
		$this->salida.='<form name="forma2'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="		<td width=\"50%\" align=\"center\">";
/*		if($odontograma<>NULL)
		{
			$this->salida.="		<input type=\"submit\" name=\"solicitar\" value=\"SOLICITAR APOYOS DIAGNÓSTICOS\" class=\"input-submit\">";
		}
		else
		{
			$this->salida.="		<input disabled=\"true\" type=\"submit\" name=\"solicitar\" value=\"SOLICITAR APOYOS DIAGNÓSTICOS\" class=\"input-submit\">";
		}*/
		$this->salida.="		</td>";
		$this->salida.="</form>";
		$this->salida.="		</tr>";
		$this->salida.="		</table>";
		$this->salida.="	</td>";
		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
/*		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<label class=\"label\">ODONTOGRAMA</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$ruta="hc_modules/OdontogramaPrimeraVez/hc_convenciones.php";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"CONVENCIONES\" onclick=\"abrirVentanaClass('$ruta')\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";*/
		$this->salida.="</table><br>";
		if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
		{
			$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"hc_table_list_title\">";
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarcopiar'));
			$this->salida.='<form name="forma3'.$pfj.'" action="'.$accion.'" method="post">';
			$this->salida.="<td align=\"center\">";
			$this->salida.="SE ENCONTRÓ UN ODONTOGRAMA EN EL HISTORIAL";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td align=\"center\">";
			$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR COPIA\" class=\"input-submit\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
		}
		if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
		{
			$ciclo=sizeof($odonselcop);
			$cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
			$cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odonselcop[$i]['estado']<>0
				AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odonselcop[$i]['sw_cariado']==1)
					{
						$cariadosp++;
					}
					else if($odonselcop[$i]['sw_obturado']==1)
					{
						$obturadosp++;
					}
					else if($odonselcop[$i]['sw_perdidos']==1)
					{
						$perdidosp++;
					}
					else if($odonselcop[$i]['sw_sanos']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odonselcop[$i]['estado']==0
				AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odonselcop[$i]['sw_cariado2']==1)
					{
						$cariadosp++;
					}
					else if($odonselcop[$i]['sw_obturado2']==1)
					{
						$obturadosp++;
					}
					else if($odonselcop[$i]['sw_perdidos2']==1)
					{
						$perdidosp++;
					}
					else if($odonselcop[$i]['sw_sanos2']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odonselcop[$i]['estado']<>0
				AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']>=51)
				{
					if($odonselcop[$i]['sw_cariado']==1)
					{
						$cariadosp++;
					}
					else if($odonselcop[$i]['sw_obturado']==1)
					{
						$obturadosp++;
					}
					else if($odonselcop[$i]['sw_perdidos']==1)
					{
						$perdidosp++;
					}
					else if($odonselcop[$i]['sw_sanos']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odonselcop[$i]['estado']==0
				AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']>=51)
				{
					if($odonselcop[$i]['sw_cariado2']==1)
					{
						$cariadosd++;
					}
					else if($odonselcop[$i]['sw_obturado2']==1)
					{
						$obturadosd++;
					}
					else if($odonselcop[$i]['sw_perdidos2']==1)
					{
						$perdidosd++;
					}
					else if($odonselcop[$i]['sw_sanos2']==1)
					{
						$sanosd++;
					}
					else
					{
						$nocontadosd++;
					}
				}
			}
			$totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
			$totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
		}
		else
		{
			$ciclo=sizeof($odontograma);
			$cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
			$cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odontograma[$i]['sw_cariado']==1)
					{
						$cariadosp++;
					}
					else if($odontograma[$i]['sw_obturado']==1)
					{
						$obturadosp++;
					}
					else if($odontograma[$i]['sw_perdidos']==1)
					{
						$perdidosp++;
					}
					else if($odontograma[$i]['sw_sanos']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=48)
				{
					if($odontograma[$i]['sw_cariado']==1)
					{
						$cariadosd++;
					}
					else if($odontograma[$i]['sw_obturado']==1)
					{
						$obturadosd++;
					}
					else if($odontograma[$i]['sw_perdidos']==1)
					{
						$perdidosd++;
					}
					else if($odontograma[$i]['sw_sanos']==1)
					{
						$sanosd++;
					}
					else
					{
						$nocontadosd++;
					}
				}
			}
			$totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
			$totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
		}
		if(!empty($odontograma))
		{
			$this->salida.="<table width=\"90%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"6%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"17%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"25%\" align=\"center\">";
			$this->salida.="HALLAZGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"27%\" align=\"center\">";
			$this->salida.="SOLUCIÓN";
			$this->salida.="</td>";
/*			$this->salida.="<td width=\"16%\" align=\"center\">";
			$this->salida.="PROFESIONAL";
			$this->salida.="</td>";*/
			$this->salida.="<td width=\"9%\" align=\"center\">";
			$this->salida.="ACCIÓN";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($j==0)
				{
					$color="class=\"hc_submodulo_list_claro\"";
					$j=1;
				}
				else
				{
					$color="class=\"hc_submodulo_list_oscuro\"";
					$j=0;
				}
				$this->salida.="<tr $color>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$odontograma[$i]['des1']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$odontograma[$i]['des2']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$odontograma[$i]['des3']."";
				$this->salida.="</td>";
/*				$this->salida.="<td align=\"center\">";
				$this->salida.="".$odontograma[$i]['nombre']."";
				$this->salida.="</td>";*/
				$this->salida.="<td align=\"center\">";
        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar','odondetadi'.$pfj=>$odontograma[$i]['hc_odontologia_evolucion_urgencias_detalle_id']));
        $this->salida.="<a href=\"$accion\">ELIMINAR</a>";//<img src=\"".GetThemePath()."/images/elimina.png\"  border='0'>
				$this->salida.="</td>";
        $this->salida.="</tr>";
			}
        $this->salida.="<tr class=hc_table_list_title>";
        $this->salida.="<td align=\"center\" colspan=\"5\">";
        $this->salida.="LISTADO DE EVULUCIONES ODONTOLOGICAS";        
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr class=hc_table_list_title>";
        $this->salida.="<td width=\"30%\" align=\"center\">";
        $this->salida.="FECHA/HORA";        
        $this->salida.="</td>";
        $this->salida.="<td width=\"25%\" align=\"center\">";
        $this->salida.="USUARIO";        
        $this->salida.="</td>";
        $this->salida.="<td width=\"45%\" align=\"center\" colspan=\"4\">";//
        $this->salida.="DESCRIPCIÓN";        
        $this->salida.="</td>";
        $this->salida.="</tr>";
        for($i=0;$i<sizeof($observacion);$i++)
        {
          if($j==0)
          {
            $color="class=\"hc_submodulo_list_claro\"";
            $j=1;
          }
          else
          {
            $color="class=\"hc_submodulo_list_oscuro\"";
            $j=0;
          }
          $this->salida.="<tr $color>";
          $this->salida.="<td align=\"center\">";
          $this->salida.="".$observacion[$i]['fecha_registro']."";
          $this->salida.="</td>";
          $this->salida.="<td align=\"center\">";
          $this->salida.="".$observacion[$i]['nombre']."";
          $this->salida.="</td>";
          $this->salida.="<td width=\"55%\" align=\"LEFT\" colspan=\"4\">";
          $this->salida.="".$observacion[$i]['descripcion']."";
          $this->salida.="</td>";
          $this->salida.="</tr>";
        }
			$this->salida.="</table><br>";
		}
		//$apoyos=$this->BuscarApoyosOdontologiaGuardados();
		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"hc_table_list_title\">";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarobser'));
		$this->salida.='<form name="forma12'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<td align=\"center\">";
		$this->salida.="EVOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<textarea class=\"input-text\" name=\"observacio".$pfj."\" cols=\"120\" rows=\"7\">".$_REQUEST['observacio'.$pfj]."</textarea>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR EVOLUCIÓN\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}
//FIN ADICIÓN CARLOS

  function frmAccionPrimeraVez($datos)
	{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class='hc_table_submodulo_list_title'>";
			$this->salida.="<td colspan=\"3\">";
			$this->salida.="ACCIÓN PREVENTIVA PRIMERA VEZ";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($datos);
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($j==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$j=1;
				}
				else if($j==1)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$j=0;
				}
				$this->salida.="<td width=\"35%\" align=\"center\">";
				$this->salida.="<label class=\"label\">".$datos[$i]['nombre']."</label>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"8%\" align=\"center\">";
				if($datos[$i]['nombre']<>'OBSERVACIONES')
				{
					if($datos[$i]['sw_accion_preventiva']==1)
					{
						$this->salida.="SI";
					}
					else
					{
						$this->salida.="NO";
					}
				}
				$this->salida.="</td>";
				$this->salida.="<td width=\"58%\" align=\"center\">";
				$this->salida.="".$datos[$i]['descripcion']."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
	}

 	function frmConsulta()
 	{
 		$datos=$this->BuscarHistoriaUrgenciasOdontologia();
		if($datos===false)
		{
			return false;
		}
		if (sizeof($datos)!=0)
		{
			$this->salida.="<br>";
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class='hc_table_submodulo_list_title'>";
			$this->salida.="<td colspan=\"4\" align=\"center\">";
			$this->salida.="EVOLUCIÓN URGENCIAS";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"6%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"17%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"25%\" align=\"center\">";
			$this->salida.="HALLAZGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"27%\" align=\"center\">";
			$this->salida.="SOLUCIÓN";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$j=0;
			for($i=0;$i<sizeof($datos);$i++)
			{
				if($j==0)
				{
					$color="class=\"hc_submodulo_list_claro\"";
					$j=1;
				}
				else
				{
					$color="class=\"hc_submodulo_list_oscuro\"";
					$j=0;
				}
				$this->salida.="<tr $color>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$datos[$i]['hc_tipo_ubicacion_diente_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$datos[$i]['des1']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$datos[$i]['des2']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$datos[$i]['des3']."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
			$this->salida.="<br>";
		}
		else
		{
			return false;
		}
		return true;
 	}

	function frmHistoria()
	{
		$datos=$this->BuscarHistoriaUrgenciasOdontologia();
		if($datos===false)
		{
			return false;
		}
		if (sizeof($datos)!=0)
		{

			$salida.="<br>";
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class='hc_table_submodulo_list_title'>";
			$salida.="<td colspan=\"4\" align=\"center\">";
			$salida.="EVOLUCIÓN URGENCIAS";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_table_list_title>";
			$salida.="<td width=\"6%\" align=\"center\">";
			$salida.="DIENTE";
			$salida.="</td>";
			$salida.="<td width=\"17%\" align=\"center\">";
			$salida.="SUPERFICIE";
			$salida.="</td>";
			$salida.="<td width=\"25%\" align=\"center\">";
			$salida.="HALLAZGO";
			$salida.="</td>";
			$salida.="<td width=\"27%\" align=\"center\">";
			$salida.="SOLUCIÓN";
			$salida.="</td>";
			$salida.="</tr>";
			$j=0;
			for($i=0;$i<sizeof($datos);$i++)
			{
				if($j==0)
				{
					$color="class=\"hc_submodulo_list_claro\"";
					$j=1;
				}
				else
				{
					$color="class=\"hc_submodulo_list_oscuro\"";
					$j=0;
				}
				$salida.="<tr $color>";
				$salida.="<td align=\"center\">";
				$salida.="".$datos[$i]['hc_tipo_ubicacion_diente_id']."";
				$salida.="</td>";
				$salida.="<td align=\"center\">";
				$salida.="".$datos[$i]['des1']."";
				$salida.="</td>";
				$salida.="<td align=\"center\">";
				$salida.="".$datos[$i]['des2']."";
				$salida.="</td>";
				$salida.="<td align=\"center\">";
				$salida.="".$datos[$i]['des3']."";
				$salida.="</td>";
				$salida.="</tr>";
			}
			$salida.="</table><br><br>";
		}
		else
		{
			return false;
		}
		return $salida;
	}

}
?>
