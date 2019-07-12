
<?php

// $Id: hc_OdontogramaTratamiento_HTML.php,v 1.52 2006/09/07 16:45:44 carlos Exp $

class OdontogramaTratamiento_HTML extends OdontogramaTratamiento
{

	function OdontogramaTratamiento_HTML()
	{
		$this->OdontogramaTratamiento();//constructor del padre
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

	function frmForma()//Desde esta funcion es de JORGE AVILA
	{
		$pfj=$this->frmPrefijo;
		$odontograma=$this->BuscarOdontogramaForma();
		if($odontograma===false)
		{
			return false;
		}
		if($odontograma==NULL)
		{
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="NO SE ENCONTRÓ UN ODONTOGRAMA DE PRIMERA VEZ ACTIVO";
			}
			else
			{
				$this->frmError["MensajeError"].="<br>NO SE ENCONTRÓ UN ODONTOGRAMA DE PRIMERA VEZ ACTIVO";
			}
		}
		$randon=$this->ingreso._.rand();
		IncludeLib("jpgraph/Odontograma_graphic");
echo '<pre>';
		$valoracion=$this->BuscarEnviarPintarMuelas();//presentes
echo '</pre>';
		$valoracio2=$this->BuscarEnviarPintarMuelas2();//no presentes
		$RutaImg=Odontograma($valoracion,$randon,1);
		$RutaIm2=Odontograma($valoracio2,$randon,2);
		$this->salida =ThemeAbrirTablaSubModulo('ODONTOGRAMA DE TRATAMIENTO');
		$mostrar1 ="<script language='javascript'>\n";
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
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<label class=\"label\">ODONTOGRAMA DE PRIMERA VEZ</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<label class=\"label\">ODONTOGRAMA DE TRATAMIENTO</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<img src=\"".$RutaIm2."\" border=\"0\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$ruta="hc_modules/OdontogramaPrimeraVez/hc_convenciones.php";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"CONVENCIONES\" onclick=\"abrirVentanaClass('$ruta')\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
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
			AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
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
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
		$this->salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="CARIADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="OBTURADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="PERDIDOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="SANOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="TOTAL";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="SIN INCLUIR";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_submodulo_list_claro>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$cariadosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$obturadosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$perdidosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$sanosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$totalp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$nocontadosp."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
		$this->salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="CARIADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="EXTRAIDOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="OBTURADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="SANOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="TOTAL";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="SIN INCLUIR";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_submodulo_list_claro>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$cariadosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$perdidosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$obturadosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$sanosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$totald."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$nocontadosd."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"100%\" align=\"center\">";
		$this->salida.="OBSERVACIÓN DEL ODONTOGRAMA DE PRIMERA VEZ";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_submodulo_list_claro>";
		$this->salida.="<td align=\"center\">";
		if($_REQUEST['observacio'.$this->frmPrefijo]<>NULL)
		{
			$this->salida.="".$_REQUEST['observacio'.$this->frmPrefijo]."";
		}
		else
		{
			$this->salida.="NO HAY OBSERVACIONES";
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$ciclo=sizeof($odontograma);
		$cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
		$cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
		for($i=0;$i<$ciclo;$i++)
		{
			if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
			AND $odontograma[$i]['estado']<>0
			AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
			{
				if($odontograma[$i]['estadotrat']=='0')
				{
					$resultado=$this->BuscarIndicesTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
					if($resultado['sw_cariado']==1)
					{
						$cariadosp++;
					}
					else if($resultado['sw_obturado']==1)
					{
						$obturadosp++;
					}
					else if($resultado['sw_perdidos']==1)
					{
						$perdidosp++;
					}
					else if($resultado['sw_sanos']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else
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
			}
			else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
			AND $odontograma[$i]['estado']==0
			AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
			{
				if($odontograma[$i]['sw_cariado2']==1)
				{
					$cariadosp++;
				}
				else if($odontograma[$i]['sw_obturado2']==1)
				{
					$obturadosp++;
				}
				else if($odontograma[$i]['sw_perdidos2']==1)
				{
					$perdidosp++;
				}
				else if($odontograma[$i]['sw_sanos2']==1)
				{
					$sanosp++;
				}
				else
				{
					$nocontadosp++;
				}
			}
			else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
			AND $odontograma[$i]['estado']<>0
			AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
			{
				if($odontograma[$i]['estadotrat']=='0')
				{
					$resultado=$this->BuscarIndicesTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
					if($resultado['sw_cariado']==1)
					{
						$cariadosd++;
					}
					else if($resultado['sw_obturado']==1)
					{
						$obturadosd++;
					}
					else if($resultado['sw_perdidos']==1)
					{
						$perdidosd++;
					}
					else if($resultado['sw_sanos']==1)
					{
						$sanosd++;
					}
					else
					{
						$nocontadosd++;
					}
				}
				else
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
			else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
			AND $odontograma[$i]['estado']==0
			AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
			{
				if($odontograma[$i]['sw_cariado2']==1)
				{
					$cariadosd++;
				}
				else if($odontograma[$i]['sw_obturado2']==1)
				{
					$obturadosd++;
				}
				else if($odontograma[$i]['sw_perdidos2']==1)
				{
					$perdidosd++;
				}
				else if($odontograma[$i]['sw_sanos2']==1)
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
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
		$this->salida.="INDICE COP DEL ODONTOGRAMA DE TRATAMIENTO";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="CARIADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="OBTURADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="PERDIDOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="SANOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="TOTAL";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="SIN INCLUIR";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_submodulo_list_claro>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$cariadosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$obturadosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$perdidosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$sanosp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$totalp."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$nocontadosp."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
		$this->salida.="INDICE CEO DEL ODONTOGRAMA DE TRATAMIENTO";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_table_list_title>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="CARIADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="EXTRAIDOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="OBTURADOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"15%\" align=\"center\">";
		$this->salida.="SANOS";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="TOTAL";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="SIN INCLUIR";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=hc_submodulo_list_claro>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$cariadosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$perdidosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$obturadosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$sanosd."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$totald."";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="".$nocontadosd."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		if(!empty($odontograma))
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"5%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"30%\" align=\"center\">";
			$this->salida.="HALLAZGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"30%\" align=\"center\">";
			$this->salida.="SOLUCIÓN";
			$this->salida.="</td>";
			$this->salida.="<td width=\"14%\" align=\"center\">";
			$this->salida.="ACCIÓN";
			$this->salida.="</td>";
			$this->salida.="<td width=\"6%\" align=\"center\">";
			$this->salida.="FECHA REGISTRO";
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
				if($odontograma[$i]['estadotrat']<>NULL)
				{
					$cambios=$this->BuscarCambiosTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$cambios['des1']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$cambios['des2']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$cambios['des3']."";
					$this->salida.="</td>";
				}
				else
				{
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
				}
				$this->salida.="<td align=\"center\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'tratamientos',
				'odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id'],
				'cargotrata'.$pfj=>$odontograma[$i]['cargo'],
				'estado'.$pfj=>$odontograma[$i]['estado'],
				'ubicacion'.$pfj=>$odontograma[$i]['hc_tipo_ubicacion_diente_id'],
				'evoluciontra'.$pfj=>$odontograma[$i]['evolucion'],
				'idevolucitra'.$pfj=>$odontograma[$i]['evolucion_id']));
				$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'activar',
				'odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id']));
				$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'modificartratamiento',
				'odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id'],
				'cargotrata'.$pfj=>$odontograma[$i]['cargo'],
				'estado'.$pfj=>$odontograma[$i]['estado'],
				'ubicacion'.$pfj=>$odontograma[$i]['hc_tipo_ubicacion_diente_id']));
				$accion4=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'tratamientos2',
				'odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id'],
				'odontratdi'.$pfj=>$odontograma[$i]['hc_odontograma_tratamiento_detalle_id'],
				'ubicacion'.$pfj=>$odontograma[$i]['hc_tipo_ubicacion_diente_id']));
				$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'tratamientoshistorial',
				'odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id'],
				'cargotrata'.$pfj=>$odontograma[$i]['cargo'],
				'estado'.$pfj=>$odontograma[$i]['estado'],
				'ubicacion'.$pfj=>$odontograma[$i]['hc_tipo_ubicacion_diente_id'],
				'evoluciontra'.$pfj=>$odontograma[$i]['evolucion'],
				'idevolucitra'.$pfj=>$odontograma[$i]['evolucion_id']));
				if(($odontograma[$i]['evolucion_id']<>NULL AND $this->evolucion==$odontograma[$i]['evolucion_id'])
				OR ($odontograma[$i]['evolucion_id']==NULL))
				{
					if($odontograma[$i]['estado']==1)
					{
						$this->salida.="<a href=\"$accion\">POR REALIZAR.</a>";//listo
					}
					else if($odontograma[$i]['estado']==0)
					{
						$this->salida.="<a href=\"$accion\">SOLUCIONADO</a>";//listo
					}
					else if($odontograma[$i]['estado']==2)
					{
						$this->salida.="<a href=\"$accion2\">CANCELADO</a>";//listo
					}
					else if($odontograma[$i]['estado']==3)
					{
						$this->salida.="<a href=\"$accion3\">SIN PRESUP.</a>";//listo
					}
					else if($odontograma[$i]['estado']==4)
					{
						$this->salida.="<a href=\"$accion\">EN TRATAMIENTO</a>";//listo
					}
					else if($odontograma[$i]['estado']>=5)
					{
						if($odontograma[$i]['estadotrat']<>NULL)
						{
							$this->salida.="<a href=\"$accion4\">MODIFICADO</a>";//listo
						}
						else
						{
							if($odontograma[$i]['estado']==5){$this->salida.="<label class=\"label_mark\">SOLUCIONADO</label>";}
							else if($odontograma[$i]['estado']==6){$this->salida.="<label class=\"label_mark\">POR REALIZAR</label>";}
							else if($odontograma[$i]['estado']==7){$this->salida.="<label class=\"label_mark\">CANCELADO</label>";}
							else if($odontograma[$i]['estado']==8){$this->salida.="<label class=\"label_mark\">SIN PRESUP.</label>";}
							else if($odontograma[$i]['estado']==9){$this->salida.="<label class=\"label_mark\">EN TRATAMIENTO</label>";}
						}
					}
				}
				else
				{
					if($odontograma[$i]['estado']==1)
					{
						$this->salida.="<a href=\"$accion\">* POR REALIZAR</a>";//listo
					}
					else if($odontograma[$i]['estado']==0)
					{
						$this->salida.="<a href=\"$accion5\">* SOLUCIONADO</a>";//listo
					}
					else if($odontograma[$i]['estado']==2)
					{
						$this->salida.="<a href=\"$accion2\">* CANCELADO</a>";//listo
					}
					else if($odontograma[$i]['estado']==3)
					{
						$this->salida.="<a href=\"$accion3\">* SIN PRESUP.</a>";//listo
					}
					else if($odontograma[$i]['estado']==4)
					{
						$this->salida.="<a href=\"$accion\">* EN TRATAMIENTO</a>";//listo
					}
					else if($odontograma[$i]['estado']>=5)
					{
						if($odontograma[$i]['estadotrat']<>NULL)
						{
							$this->salida.="<a href=\"$accion4\">* MODIFICADO</a>";
						}
						else
						{
							if($odontograma[$i]['estado']==5){$this->salida.="<label class=\"label_mark\">SOLUCIONADO</label>";}
							else if($odontograma[$i]['estado']==6){$this->salida.="<label class=\"label_mark\">POR REALIZAR</label>";}
							else if($odontograma[$i]['estado']==7){$this->salida.="<label class=\"label_mark\">CANCELADO</label>";}
							else if($odontograma[$i]['estado']==8){$this->salida.="<label class=\"label_mark\">SIN PRESUP.</label>";}
							else if($odontograma[$i]['estado']==9){$this->salida.="<label class=\"label_mark\">EN TRATAMIENTO</label>";}
						}
					}
				}
				$this->salida.="</td>";
				$fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$fecha_registro[0]."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		$apoyos=$this->BuscarApoyosOdontograma();
		if($apoyos)
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"hc_table_list_title\">";
			$this->salida.="<td width=\"8%\">CARGO</td>";
			$this->salida.="<td width=\"60%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
			$this->salida.="<td width=\"6%\" >CANTIDAD</td>";
			$this->salida.="<td width=\"10%\">UBICACIÓN</td>";
			$this->salida.="<td width=\"10%\">ACCIÓN</td>";
			$this->salida.="<td width=\"6%\">FECHA REGISTRO</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($apoyos);$i++)
			{
				if( $i % 2)
				{
					$estilo='modulo_list_claro';
				}
				else
				{
					$estilo='modulo_list_oscuro';
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$accionapoyos=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'tratamientosapoyos',
				'odondetadi'.$pfj=>$apoyos[$i]['hc_odontograma_primera_vez_id'],
				'cargotrata'.$pfj=>$apoyos[$i]['cargo'],
				'cargodestr'.$pfj=>$apoyos[$i]['descripcion'],
				'estado'.$pfj=>$apoyos[$i]['estado'],
				'ubicacion'.$pfj=>$apoyos[$i]['descripcion_ubicacion'],
				'cantidad'.$pfj=>$apoyos[$i]['cantidad'],
				'cantida2'.$pfj=>$apoyos[$i]['cantidad_pend'],
				'cantida3'.$pfj=>$apoyos[$i]['cantidad_pend'],
				'evolucprtra'.$pfj=>$apoyos[$i]['evolucion']));
				if($apoyos[$i]['estado']==1)
				{
					$this->salida.="<a href=\"$accionapoyos\">POR REALIZAR</a>";//listo
				}
				else if($apoyos[$i]['estado']==0)
				{
					$this->salida.="<a href=\"$accionapoyos\">REALIZADO</a>";//listo
				}
				else if($apoyos[$i]['estado']==2)
				{
					$this->salida.="<a href=\"$accionapoyos\">CANCELADO</a>";//listo
				}
				$this->salida.="</td>";
				$fecha_registro=explode(' ',$apoyos[$i]['fecha_registro']);
				$this->salida.="<td align=\"center\">".$fecha_registro[0]."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		$presup=$this->BuscarPresupuestosOdontograma();
		if($presup)
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"hc_table_list_title\">";
			$this->salida.="<td width=\"12%\">CARGO</td>";
			$this->salida.="<td width=\"70%\">DESCRIPCIÓN DE LOS CARGOS PRESUPUESTADOS</td>";
			$this->salida.="<td width=\"6%\" >CANTIDAD</td>";
			$this->salida.="<td width=\"10%\">ACCIÓN</td>";
			$this->salida.="<td width=\"6%\" >FECHA REGISTRO</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($presup);$i++)
			{
				if( $i % 2)
				{
					$estilo='modulo_list_claro';
				}
				else
				{
					$estilo='modulo_list_oscuro';
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\">".$presup[$i]['cargo']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\">".$presup[$i]['descripcion']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">".$presup[$i]['cantidad']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$accionpresup=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'tratamientospresupuestos',
				'odondetadi'.$pfj=>$presup[$i]['hc_odontograma_primera_vez_id'],
				'cargotrata'.$pfj=>$presup[$i]['cargo'],
				'cargodestr'.$pfj=>$presup[$i]['descripcion'],
				'estado'.$pfj=>$presup[$i]['estado'],
				'cantidad'.$pfj=>$presup[$i]['cantidad'],
				'cantida2'.$pfj=>$presup[$i]['cantidad_pend'],
				'cantida3'.$pfj=>$presup[$i]['cantidad_pend'],
				'evolucprtra'.$pfj=>$presup[$i]['evolucion']));
				if($presup[$i]['estado']==1)
				{
					$this->salida.="<a href=\"$accionpresup\">POR REALIZAR</a>";//listo
				}
				else if($presup[$i]['estado']==0)
				{
					$this->salida.="<a href=\"$accionpresup\">REALIZADO</a>";//listo
				}
				else if($presup[$i]['estado']==2)
				{
					$this->salida.="<a href=\"$accionpresup\">CANCELADO</a>";//listo
				}
				$this->salida.="</td>";
				$fecha_registro=explode(' ',$presup[$i]['fecha_registro']);
				$this->salida.="<td align=\"center\">".$fecha_registro[0]."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		$_REQUEST['observacio2'.$pfj]=$this->BuscarDatosObser();
		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"hc_table_list_title\">";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarobser'));
		$this->salida.='<form name="forma1'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<td align=\"center\">";
		$this->salida.="OBSERVACIÓN DE TRATAMIENTO";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<textarea class=\"input-text\" name=\"observacio2".$pfj."\" cols=\"80\" rows=\"4\">".$_REQUEST['observacio2'.$pfj]."</textarea>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR OBSERVACIÓN\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'nuevoscargosnopres'));
		$this->salida.='<form name="forma2'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"hc_table_list_title\">";
		$this->salida.="<td align=\"center\">";
		$this->salida.="NUEVOS CARGOS PARA REALIZAR";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"NUEVOS CARGOS\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmNuevosCargosPresupuesto()
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('NUEVOS CARGOS DEL PLAN DE TRATAMIENTO Y PRESUPUESTO');
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'nuevoscargosnopres',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida.="<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA DESCRIPCIÓN DE ACTIVIDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">CODIGO:";
		$this->salida.="</td>";
		$this->salida.="<td width=\"5%\" align='center'>";
		$this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj'>";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"9%\">ACTIVIDAD:";
		$this->salida.="</td>";
		$this->salida.="<td width=\"46%\" align='center'>";
		$this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value=\"".$_REQUEST['diagnostico'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"5%\" align='center'>";
		$this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vector=$this->BuscarCargosPlan();
		$vectorD=$this->BuscarCargosPlan2();
		for($i=0;$i<sizeof($vector);$i++)
		{
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">CARGOS REALIZADOS";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CARGO</td>";
			$this->salida.="<td width=\"62%\">DESCRIPCIÓN</td>";
			$this->salida.="<td width=\"8%\" >CANTIDAD</td>";
			$this->salida.="</tr>";
			$bool=false;
			for($i=0;$i<sizeof($vector);$i++)
			{	
				if($vector[$i]['estado']==0 AND $vector[$i]['cantidad']<>0)
				{
					$bool=true;;
					if( $i % 2)
					{
						$estilo='modulo_list_claro';
					}
					else
					{
						$estilo='modulo_list_oscuro';
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">".$vector[$i]['cargo']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"left\">".$vector[$i]['descripcion']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$vector[$i]['cantidad']."";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
			}
			if (!$bool)
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" colspan=\"3\">";
					$this->salida.="NO SE ENCONTRARON CARGOS REALIZADOS";
					$this->salida.="</tr>";
					$this->salida.="</td>";
				}
		 }
		for($i=0;$i<sizeof($vector);$i++)
		{
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">CARGOS POR REALIZAR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CARGO</td>";
			$this->salida.="<td width=\"62%\">DESCRIPCIÓN</td>";
			$this->salida.="<td width=\"8%\" >CANTIDAD</td>";
			$this->salida.="</tr>";
			$bool=false;
			for($i=0;$i<sizeof($vector);$i++)
			{	
				if($vector[$i]['estado']==1)
				{
					$bool=true;;
					if( $i % 2)
					{
						$estilo='modulo_list_claro';
					}
					else
					{
						$estilo='modulo_list_oscuro';
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">".$vector[$i]['cargo']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"left\">".$vector[$i]['descripcion']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$vector[$i]['cantidad']."";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
			}
			if (!$bool)
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" colspan=\"3\">";
					$this->salida.="NO SE ENCONTRARON CARGOS POR REALIZAR";
					$this->salida.="</tr>";
					$this->salida.="</td>";
				}
		}
		$this->salida.="</tr>";
		$this->salida.="</table><br><br>";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table><br>";
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertarcargosnopres',
		'vector'.$pfj=>$vectorD));
		if($vectorD)
		{
			$this->salida.="<form name=\"formades2$pfj\" action=\"$accionI\" method=\"post\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CARGO</td>";
			$this->salida.="<td width=\"62%\">DESCRIPCIÓN</td>";
			$this->salida.="<td width=\"8%\" >CANTIDAD</td>";
			$this->salida.="<td width=\"8%\" >OPCIÓN</td>";
			$this->salida.="<td width=\"12%\">PLAN</td>";
			$this->salida.="</tr>";

			for($i=0;$i<sizeof($vectorD);$i++)
			{
				if( $i % 2)
				{
					$estilo='modulo_list_claro';
				}
				else
				{
					$estilo='modulo_list_oscuro';
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\">".$vectorD[$i]['cargo']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\">".$vectorD[$i]['descripcion']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				//$this->salida.="<input type=\"text\" class=\"input-text\" name=\"cantidad".$i.$pfj."\" maxlength=\"5\" value=\"".$vectorD[$i]['cantidad']."\" size=\"5\">";
 				$this->salida.="<input type=\"text\" class=\"input-text\" name=\"cantidad".$i.$pfj."\" maxlength=\"5\" size=\"5\">";			
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
/*				if($vectorD[$i]['cantidad']==NULL)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=\"".$vectorD[$i]['cargo']."\">";
				}
				else if($vectorD[$i]['estado']==1 //POR REALIZAR
				AND $vectorD[$i]['hc_odontograma_tratamiento_id']<>NULL
				AND $vectorD[$i]['cantidad_pend']==0)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=\"".$vectorD[$i]['cargo']."\" checked>";
				}
        elseif($vectorD[$i]['estado']==0 //REALIZADO
				AND $vectorD[$i]['hc_odontograma_tratamiento_id']<>NULL
				AND $vectorD[$i]['cantidad_pend']==0)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=\"".$vectorD[$i]['cargo']."\"checked>";
				}
				else
				$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\">";*/
				$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\">";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$vectorD[$i]['desplantra']."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"right\" colspan=\"5\">";
			$this->salida.="<input class=\"input-submit\" name=\"guardar".$pfj."\" type=\"submit\" value=\"GUARDAR\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada4();
			if(!empty($var))
			{
				$this->salida.="<table border=\"0\" width=\"60%\" align=\"center\">";
				$this->salida.="<tr>";
				$this->salida.="<td width=\"100%\" align=\"center\">";
				$this->salida.=$var;
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
			}
		}
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"40%\">";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmTratamientosApoyos()
	{
		$pfj=$this->frmPrefijo;
		$diag=$this->BuscarDiagnosticosApoyosTratamGuarda();
		if($diag)
		{
			$_REQUEST['validadiag'.$pfj]=1;
		}
		else
		{
			$_REQUEST['validadiag'.$pfj]=0;
		}
		$this->salida =ThemeAbrirTablaSubModulo('EVOLUCIÓN DEL ODONTOGRAMA DE TRATAMIENTO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertarapoyos',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
		'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
		'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
		'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj],
		'validadiag'.$pfj=>$_REQUEST['validadiag'.$pfj]));
		$this->salida.="<form name=\"forma1$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="APOYO DIAGNÓSTICO";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$_REQUEST['cargotrata'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DESCRIPCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['cargodestr'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="UBICACIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['ubicacion'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CANTIDAD ORDENADA";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="".$_REQUEST['cantidad'.$pfj]."";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CANTIDAD A REALIZAR";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<input type=\"text\" class=\"input-text\" name=\"cantida2".$pfj."\" value=\"".$_REQUEST['cantida2'.$pfj]."\" maxlength=\"5\" size=\"5\">";
		$this->salida.="<input type=\"hidden\" name=\"cantida3".$pfj."\" value=\"".$_REQUEST['cantida3'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="EVOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<textarea class=\"textarea\" name=\"evolucprtra".$pfj."\" cols=100 rows=3>".$_REQUEST['evolucprtra'.$pfj]."</textarea>";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		if($diag)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNÓSTICOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diag);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'eliminar_diagnosticos4',
				'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
				'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
				'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
				'estado'.$pfj=>$_REQUEST['estado'.$pfj],
				'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
				'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
				'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
				'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
				'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id'],
				'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
				$this->salida.="<td align=\"center\" width=\"6%\">";
				if($diag[$i]['evolucion_id']==$this->evolucion AND $_REQUEST['estado'.$pfj]==1)
				{
					$this->salida.="<a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\" border='0'></a>";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/pconsultar.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diag[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']==$this->evolucion)
				{
					$accionDiag=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
					'accion'.$pfj=>'cambiardiagnostico4',
					'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
					'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
					'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
					'estado'.$pfj=>$_REQUEST['estado'.$pfj],
					'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
					'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
					'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
					'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
					'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id'],
					'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
					$this->salida.="<a href='$accionDiag'><img src=\"".GetThemePath()."/images/checkno.png\" border='0'></a>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']<>$this->evolucion)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diag[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$this->salida.="</table><br>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"50%\">";
		if($_REQUEST['estado'.$pfj]==1)
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR A REALIZADO\">";
		}
		else
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR A REALIZADO\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		if($_REQUEST['estado'.$pfj]==1)
		{
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'cancelarapoyos',
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
			'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
			'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
			'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
			'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
			$this->salida.="<form name=\"forma2$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="<td align=\"center\" width=\"50%\">";
			if($diag)
			{
				$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"CANCELAR LA ORDEN\">";
			}
			else
			{
				$this->salida.="<input class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"CANCELAR LA ORDEN\">";
			}
			$this->salida.="</td>";
			$this->salida.="</form>";
		}
		else if($_REQUEST['estado'.$pfj]==2)
		{
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'activarapoyos',
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
			'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
			'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
			'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
			'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
			$this->salida.="<form name=\"forma2$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="<td align=\"center\" width=\"50%\">";
			$this->salida.="<input class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"ACTIVAR LA ORDEN\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
		}
		else
		{
			$this->salida.="<td align=\"center\" width=\"50%\">";
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"CANCELAR LA ORDEN\">";
			$this->salida.="</td>";
		}
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma3$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		if($_REQUEST['estado'.$pfj]==1)
		{
			$this->salida.="</table><br>";
			$vectorD=$this->BuscarDiagnosticosApoyosTratam();
			$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'tratamientosapoyos',
			'Of'.$pfj=>$_REQUEST['Of'.$pfj],
			'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
			'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
			'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
			'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
			'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
			'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
			$this->salida.="<form name=\"forma6$pfj\" action=\"$accionD\" method=\"post\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"4%\">CODIGO:</td>";
			$this->salida.="<td width=\"5%\" align='center'>";
			$this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj' value =\"".$_REQUEST['codigo'.$pfj]."\">";
			$this->salida.="</td>" ;
			$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
			$this->salida.="<td width=\"55%\" align='center'>";
			$this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value =\"".$_REQUEST['diagnostico'.$pfj]."\">";
			$this->salida.="</td>" ;
			$this->salida.="<td width=\"7%\" align=\"center\">";
			$this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			if($vectorD)
			{
				$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'insertar_diagnosticos4',
				'Of'.$pfj=>$_REQUEST['Of'.$pfj],
				'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
				'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
				'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
				'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
				'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
				'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
				'estado'.$pfj=>$_REQUEST['estado'.$pfj],
				'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
				'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
				'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
				'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
				'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
				$this->salida.="<form name=\"forma7$pfj\" action=\"$accionI\" method=\"post\">";
				$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"10%\">CÓDIGO</td>";
				$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
				$this->salida.="<td width=\"5%\" >OPCIÓN</td>";
				$this->salida.="<td width=\"5%\" >PRINC.</td>";
				$this->salida.="<td width=\"20%\">TIPO DX</td>";
				$this->salida.="</tr>";
				$ciclo=sizeof($vectorD);
				$sw=0;
				for($i=0;$i<$ciclo;$i++)
				{
					if($i%2)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$vectorD[$i]['diagnostico_id']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"left\">";
					$this->salida.="".$vectorD[$i]['diagnostico_nombre']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($vectorD[$i]['hc_odontograma_primera_vez_id']==NULL)
					{
						$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id'].">";
					}
					else
					{
						$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id']." checked>";
					}
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($vectorD[$i]['sw_principal']==1)
					{
						$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
						$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"1\">";
						$sw=1;
					}
					else
					{
						$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
						if($sw==0)
						{
							$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"0\">";
						}
					}
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($vectorD[$i]['tipo_diagnostico']==1)
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\" checked>&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
					}
					else if($vectorD[$i]['tipo_diagnostico']==2)
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\" checked>&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
					}
					else if($vectorD[$i]['tipo_diagnostico']==3)
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\" checked>&nbsp;CR&nbsp;&nbsp;";
					}
					else
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
					}
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"left\" colspan=\"5\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"right\" colspan=\"5\">";
				if($_REQUEST['estado'.$pfj]==1)
				{
					$this->salida.="<input class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
				}
				else
				{
					$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
				}
				$this->salida.="</td>";
				$this->salida.="</form>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$var=$this->RetornarBarraDiagnosticos_Avanzada5();
				if(!empty($var))
				{
					$this->salida.="<table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
					$this->salida.="<tr>";
					$this->salida.="<td width=\"100%\" align=\"center\">";
					$this->salida.=$var;
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
				}
			}
			$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
			$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
			$this->salida.="<form name=\"forma8$pfj\" action=\"$accionV\" method=\"post\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmTratamientosPresupuestos()
	{
		$pfj=$this->frmPrefijo;
		$diag=$this->BuscarDiagnosticosPresupuestoTratamGuarda();
		if($diag)
		{
			$_REQUEST['validadiag'.$pfj]=1;
		}
		else
		{
			$_REQUEST['validadiag'.$pfj]=0;
		}
		$this->salida =ThemeAbrirTablaSubModulo('EVOLUCIÓN DEL ODONTOGRAMA DE TRATAMIENTO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertarpresupuestos',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
		'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
		'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
		'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj],
		'validadiag'.$pfj=>$_REQUEST['validadiag'.$pfj]));
		$this->salida.="<form name=\"forma1$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="CARGO PRESUPUESTADO";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$_REQUEST['cargotrata'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DESCRIPCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['cargodestr'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DIENTE";
		$this->salida.="</td>";
		if($_REQUEST['ubicacion'.$pfj]==NULL)
		{
			$this->salida.="<td align=\"left\">NO SE ESPECIFICÓ UNA UBICACIÓN";
		}
		else
		{
			$this->salida.="<td align=\"left\">".$_REQUEST['ubicacion'.$pfj]."";
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CANTIDAD ORDENADA";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="".$_REQUEST['cantidad'.$pfj]."";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CANTIDAD A REALIZAR";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<input type=\"text\" class=\"input-text\" name=\"cantida2".$pfj."\" value=\"".$_REQUEST['cantida2'.$pfj]."\" maxlength=\"5\" size=\"5\">";
		$this->salida.="<input type=\"hidden\" name=\"cantida3".$pfj."\" value=\"".$_REQUEST['cantida3'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="ESTADO";
		$this->salida.="</td>";
		$this->salida.="<td>";
		if($_REQUEST['estado'.$pfj]==0)
		{
			$this->salida.="REALIZADO";
		}
		else if($_REQUEST['estado'.$pfj]==1)
		{
			$this->salida.="POR REALIZAR";
		}
		else if($_REQUEST['estado'.$pfj]==2)
		{
			$this->salida.="CANCELADO";
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="EVOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<textarea class=\"textarea\" name=\"evolucprtra".$pfj."\" cols=100 rows=3>".$_REQUEST['evolucprtra'.$pfj]."</textarea>";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		if($diag)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNÓSTICOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diag);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'eliminar_diagnosticos3',
				'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
				'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
				'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
				'estado'.$pfj=>$_REQUEST['estado'.$pfj],
				'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
				'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
				'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
				'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id'],
				'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
				$this->salida.="<td align=\"center\" width=\"6%\">";
				if($diag[$i]['evolucion_id']==$this->evolucion AND $_REQUEST['estado'.$pfj]==1)
				{
					$this->salida.="<a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\" border='0'></a>";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/pconsultar.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diag[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']==$this->evolucion)
				{
					$accionDiag=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
					'accion'.$pfj=>'cambiardiagnostico3',
					'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
					'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
					'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
					'estado'.$pfj=>$_REQUEST['estado'.$pfj],
					'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
					'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
					'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
					'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id'],
					'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
					$this->salida.="<a href='$accionDiag'><img src=\"".GetThemePath()."/images/checkno.png\" border='0'></a>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']<>$this->evolucion)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diag[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$this->salida.="</table><br>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"50%\">";
		if($_REQUEST['estado'.$pfj]==1)
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR A REALIZADO\">";
		}
		else
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR A REALIZADO\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		if($_REQUEST['estado'.$pfj]==1)
		{
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'cancelarpresupuestos',
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
			'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
			'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
			'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
			$this->salida.="<form name=\"forma2$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="<td align=\"center\" width=\"50%\">";
			$this->salida.="<input class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"CANCELAR LA ORDEN\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
		}
		else if($_REQUEST['estado'.$pfj]==2)
		{
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'activarpresupuestos',
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
			'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
			'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
			'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
			$this->salida.="<form name=\"forma2$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="<td align=\"center\" width=\"50%\">";
			$this->salida.="<input class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"ACTIVAR LA ORDEN\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
		}
		else
		{
			$this->salida.="<td align=\"center\" width=\"50%\">";
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"CANCELAR LA ORDEN\">";
			$this->salida.="</td>";
		}
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma3$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		if($_REQUEST['estado'.$pfj]==1)
		{
			$this->salida.="</table><br>";
			$vectorD=$this->BuscarDiagnosticosPresupuestoTratam();
			$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'tratamientospresupuestos',
			'Of'.$pfj=>$_REQUEST['Of'.$pfj],
			'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
			'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
			'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
			'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
			'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
			$this->salida.="<form name=\"forma6$pfj\" action=\"$accionD\" method=\"post\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"4%\">CODIGO:</td>";
			$this->salida.="<td width=\"5%\" align='center'>";
			$this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj' value =\"".$_REQUEST['codigo'.$pfj]."\">";
			$this->salida.="</td>" ;
			$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
			$this->salida.="<td width=\"55%\" align='center'>";
			$this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value =\"".$_REQUEST['diagnostico'.$pfj]."\">";
			$this->salida.="</td>" ;
			$this->salida.="<td width=\"7%\" align=\"center\">";
			$this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			if($vectorD)
			{
				$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'insertar_diagnosticos3',
				'Of'.$pfj=>$_REQUEST['Of'.$pfj],
				'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
				'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
				'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
				'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
				'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
				'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
				'estado'.$pfj=>$_REQUEST['estado'.$pfj],
				'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
				'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
				'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
				'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
				$this->salida.="<form name=\"forma7$pfj\" action=\"$accionI\" method=\"post\">";
				$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"10%\">CÓDIGO</td>";
				$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
				$this->salida.="<td width=\"5%\" >OPCIÓN</td>";
				$this->salida.="<td width=\"5%\" >PRINC.</td>";
				$this->salida.="<td width=\"20%\">TIPO DX</td>";
				$this->salida.="</tr>";
				$ciclo=sizeof($vectorD);
				$sw=0;
				for($i=0;$i<$ciclo;$i++)
				{
					if($i%2)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$vectorD[$i]['diagnostico_id']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"left\">";
					$this->salida.="".$vectorD[$i]['diagnostico_nombre']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($vectorD[$i]['hc_odontograma_primera_vez_id']==NULL)
					{
						$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id'].">";
					}
					else
					{
						$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id']." checked>";
					}
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($vectorD[$i]['sw_principal']==1)
					{
						$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
						$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"1\">";
						$sw=1;
					}
					else
					{
						$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
						if($sw==0)
						{
							$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"0\">";
						}
					}
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($vectorD[$i]['tipo_diagnostico']==1)
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\" checked>&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
					}
					else if($vectorD[$i]['tipo_diagnostico']==2)
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\" checked>&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
					}
					else if($vectorD[$i]['tipo_diagnostico']==3)
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\" checked>&nbsp;CR&nbsp;&nbsp;";
					}
					else
					{
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
						$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
					}
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"left\" colspan=\"5\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"right\" colspan=\"5\">";
				if($_REQUEST['estado'.$pfj]==1)
				{
					$this->salida.="<input class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
				}
				else
				{
					$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
				}
				$this->salida.="</td>";
				$this->salida.="</form>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$var=$this->RetornarBarraDiagnosticos_Avanzada3();
				if(!empty($var))
				{
					$this->salida.="<table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
					$this->salida.="<tr>";
					$this->salida.="<td width=\"100%\" align=\"center\">";
					$this->salida.=$var;
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
				}
			}
			$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
			$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
			$this->salida.="<form name=\"forma8$pfj\" action=\"$accionV\" method=\"post\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmTratamientos()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('EVOLUCIÓN DEL ODONTOGRAMA DE TRATAMIENTO');
		$diag=$this->BuscarDiagnosticosTratamGuarda();
		if($diag)
		{
			$_REQUEST['validadiag'.$pfj]=1;
		}
		else
		{
			$_REQUEST['validadiag'.$pfj]=0;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'corregir',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontra'.$pfj=>$_REQUEST['evoluciontra'.$pfj],
		'validadiag'.$pfj=>$_REQUEST['validadiag'.$pfj]));
		$this->salida.="<form name=\"forma1$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="EVOLUCIÓN MÉDICA DE ODONTOLOGÍA";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$_REQUEST['cargotrata'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DESCRIPCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$this->BuscarCargoDescripcion($_REQUEST['cargotrata'.$pfj])."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DIENTE";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['ubicacion'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="EVOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<textarea class=\"textarea\" name=\"evoluciontra".$pfj."\" cols=100 rows=3>".$_REQUEST['evoluciontra'.$pfj]."</textarea>";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		if($diag)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNÓSTICOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diag);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'eliminar_diagnosticos',
				'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
				'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
				'estado'.$pfj=>$_REQUEST['estado'.$pfj],
				'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
				'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
				'evoluciontra'.$pfj=>$_REQUEST['evoluciontra'.$pfj],
				'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id']));
				$this->salida.="<td align=\"center\" width=\"6%\">";
				$this->salida.="<a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\" border='0'></a>";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diag[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']==$this->evolucion)
				{
					$accionDiag=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
					'accion'.$pfj=>'cambiardiagnostico1',
					'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
					'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
					'estado'.$pfj=>$_REQUEST['estado'.$pfj],
					'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
					'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
					'evoluciontra'.$pfj=>$_REQUEST['evoluciontra'.$pfj],
					'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id']));
					$this->salida.="<a href='$accionDiag'><img src=\"".GetThemePath()."/images/checkno.png\" border='0'></a>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']<>$this->evolucion)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diag[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$diaghistorial=$this->BuscarDiagnosticosTratamGuardaHistorial();
		if($diaghistorial)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNÓSTICOS DE EVOLUCIONES ANTERIORES";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diaghistorial);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td align=\"center\" width=\"6%\">";
				$this->salida.="<img src=\"".GetThemePath()."/images/pconsultar.png\" border='0'>";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diaghistorial[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diaghistorial[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$dientes=$this->BuscarOdontogramaDiente();
		if($dientes AND $_REQUEST['estado'.$pfj]==1)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td colspan=\"2\" align=\"center\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td colspan=\"5\" align=\"center\">";
			$this->salida.="OTROS HALLAZGOS PARA EL MISMO DIENTE";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"7%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"32%\" align=\"center\">";
			$this->salida.="HALLAZGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"32%\" align=\"center\">";
			$this->salida.="SOLUCIÓN";
			$this->salida.="</td>";
			$this->salida.="<td width=\"14%\" align=\"center\">";
			$this->salida.="APLICAR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$j=0;
			for($i=0;$i<sizeof($dientes);$i++)
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
				$this->salida.="".$dientes[$i]['hc_tipo_ubicacion_diente_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$dientes[$i]['des1']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$dientes[$i]['des2']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$dientes[$i]['des3']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="<input type=\"checkbox\" name=\"copiar".$i."".$pfj."\" value=\"1\">";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="<label class=\"label_mark\">CONTINUAR EN TRATAMIENTO</label>";
		if($_REQUEST['estado'.$pfj]==4)
		{
			$this->salida.="<input type=\"checkbox\" name=\"continua".$pfj."\" value=1 checked>";
		}
		else
		{
			$this->salida.="<input type=\"checkbox\" name=\"continua".$pfj."\" value=1>";
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="<input class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'borrar',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj]));
		$this->salida.="<form name=\"forma2$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<td align=\"center\" width=\"33%\">";
		$this->salida.="<input class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"ELIMINAR LA EVOLUCIÓN\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'modificartratamiento',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj]));
		$this->salida.="<form name=\"forma3$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<td align=\"center\" width=\"34%\">";
		if($_REQUEST['estado'.$pfj]<>'4')
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar3$pfj\" type=\"submit\" value=\"MODIFICAR TRATAMIENTO\">";
		}
		else
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar3$pfj\" type=\"submit\" value=\"MODIFICAR TRATAMIENTO\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'cancelar',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj]));
		$this->salida.="<form name=\"forma4$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<td align=\"center\" width=\"33%\">";
		if($_REQUEST['estado'.$pfj]=='1')
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar4$pfj\" type=\"submit\" value=\"CANCELAR TRATAMIENTO\">";
		}
		else
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar4$pfj\" type=\"submit\" value=\"CANCELAR TRATAMIENTO\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma5$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vectorD=$this->BuscarDiagnosticosTratam();
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'tratamientos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontra'.$pfj=>$_REQUEST['evoluciontra'.$pfj]));
		$this->salida.="<form name=\"forma6$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'>";
		$this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj' value =\"".$_REQUEST['codigo'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'>";
		$this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value =\"".$_REQUEST['diagnostico'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"7%\" align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		if($vectorD)
		{
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'insertar_diagnosticos',
			'Of'.$pfj=>$_REQUEST['Of'.$pfj],
			'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
			'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
			'estado'.$pfj=>$_REQUEST['estado'.$pfj],
			'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
			'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
			'evoluciontra'.$pfj=>$_REQUEST['evoluciontra'.$pfj]));
			$this->salida.="<form name=\"forma7$pfj\" action=\"$accionI\" method=\"post\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CÓDIGO</td>";
			$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="<td width=\"5%\" >OPCIÓN</td>";
			$this->salida.="<td width=\"5%\" >PRINC.</td>";
			$this->salida.="<td width=\"20%\">TIPO DX</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($vectorD);
			for($i=0;$i<$ciclo;$i++)
			{
				if($i%2)
				{
					$estilo='hc_submodulo_list_claro';
				}
				else
				{
					$estilo='hc_submodulo_list_oscuro';
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$vectorD[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\">";
				$this->salida.="".$vectorD[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['hc_odontograma_primera_vez_detalle_id']==NULL)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id'].">";
				}
				else
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id']." checked>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
					$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"1\">";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
					$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"0\">";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['tipo_diagnostico']==1)
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\" checked>&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
				}
				else if($vectorD[$i]['tipo_diagnostico']==2)
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\" checked>&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
				}
				else if($vectorD[$i]['tipo_diagnostico']==3)
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\" checked>&nbsp;CR&nbsp;&nbsp;";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
				}
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" colspan=\"5\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"right\" colspan=\"5\">";
			$this->salida.="<input class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida.="<br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida.="<tr>";
				$this->salida.="<td width=\"100%\" align=\"center\">";
				$this->salida.=$var;
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
			}
		}
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma8$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmTratamientosHistorial()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('EVOLUCIÓN DEL ODONTOGRAMA DE TRATAMIENTO');
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="EVOLUCIÓN MÉDICA DE ODONTOLOGÍA";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$_REQUEST['cargotrata'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DESCRIPCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$this->BuscarCargoDescripcion($_REQUEST['cargotrata'.$pfj])."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DIENTE";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['ubicacion'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="EVOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['evoluciontra'.$pfj]."";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$diaghistorial=$this->BuscarDiagnosticosTratamGuardaHistorial();
		if($diaghistorial)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNÓSTICOS DE EVOLUCIONES ANTERIORES";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diaghistorial);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td align=\"center\" width=\"6%\">";
				$this->salida.="<img src=\"".GetThemePath()."/images/pconsultar.png\" border='0'>";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diaghistorial[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diaghistorial[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diaghistorial[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$this->salida.="</table><br>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'modificartratamiento',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj]));
		$this->salida.="<form name=\"forma3$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"100%\">";
		$this->salida.="<input class=\"input-submit\" name=\"guardar3$pfj\" type=\"submit\" value=\"MODIFICAR TRATAMIENTO\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma5$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmCancelar()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('CANCELAR UN PROCEDIMIENTO EN EL ODONTOGRAMA DE TRATAMIENTO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertarjustif',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj]));
		$this->salida.="<form name=\"forma1$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="EVOLUCIÓN MÉDICA DE ODONTOLOGÍA";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$_REQUEST['cargotrata'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DESCRIPCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$this->BuscarCargoDescripcion($_REQUEST['cargotrata'.$pfj])."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DIENTE";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$_REQUEST['ubicacion'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="JUSTIFICACIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<textarea class=\"textarea\" name=\"justificac".$pfj."\" cols=\"100\" rows=\"3\">".$_REQUEST['justificac'.$pfj]."</textarea>";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"10%\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR JUSTIFICACIÓN\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmModificarTratamientos()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('MODIFICAR EL HALLAZGO EN EL ODONTOGRAMA DE TRATAMIENTO');
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertar',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj]));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"hc_table_list_title\">";
		$this->salida.="<td width=\"10%\" align=\"center\">";
		$this->salida.="DIENTE";
		$this->salida.="</td>";
		$this->salida.="<td width=\"20%\" align=\"center\">";
		$this->salida.="SUPERFICIE";
		$this->salida.="</td>";
		$this->salida.="<td width=\"70%\" align=\"center\">";
		$this->salida.="HALLAZGO // SOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td width=\"10%\" align=\"center\">";
		$this->salida.="".$_REQUEST['ubicacion'.$pfj]."";
		$this->salida.="</td>";
		$this->salida.="<td width=\"25%\" align=\"left\">";
		$this->salida.="<div id=\"div1\">";
		$this->salida.="</div>";
		$mostrar1 ="<script language='javascript'>\n";
		$mostrar1.="	BuscarSuperficies('".$_REQUEST['ubicacion'.$pfj]."','$pfj');";
		$mostrar1.="</script>\n";
		$this->salida.="$mostrar1";
		$this->salida.="</td>";
		$this->salida.="<td width=\"70%\" align=\"left\">";
		$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida.="	<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="	<td align=\"left\">";
		$busquedas=$this->BuscarTipoProblemaTra();
		$this->salida.="HALLAZGO: <select name=\"tipoproble".$pfj."\" class=\"select\">";
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1)
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\" selected>".$busquedas[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\">".$busquedas[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="	</select>";
		$this->salida.="	</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="	<td align=\"left\">";
		$busquedas=$this->BuscarTipoProductos();
		$this->salida.="SOLUCIÓN: <select name=\"tipoproduc".$pfj."\" class=\"select\">";
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1)
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\" selected>".$busquedas[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\">".$busquedas[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="	</select>";
		$this->salida.="	</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="	<td align=\"center\">";
		$this->salida.="	<input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="	</td>";
		$this->salida.="	</form>";
		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$ruta="hc_modules/OdontogramaPrimeraVez/hc_convenciones.php";
		$this->salida.="<tr>";
		$this->salida.="<td width=\"50%\" align=\"center\">";
		$this->salida.="<input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"CONVENCIONES\" onclick=\"abrirVentanaClass('$ruta')\">";
		$this->salida.="</td>";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<td width=\"50%\" align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmTratamientosTra()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('EVOLUCIÓN DEL ODONTOGRAMA DE TRATAMIENTO');
		$diag=$this->BuscarDiagnosticosTratamGuarda2();
		if($diag)
		{
			$_REQUEST['validaditr'.$pfj]=1;
		}
		else
		{
			$_REQUEST['validaditr'.$pfj]=0;
		}
		$cambiotratam=$this->BuscarCambiosTratamientoEvolucion($_REQUEST['odontratdi'.$pfj]);
		$_REQUEST['evoluciontr2'.$pfj]=$cambiotratam['evolucion'];
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'corregirtrat',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj],
		'validaditr'.$pfj=>$_REQUEST['validaditr'.$pfj],
		'estado'.$pfj=>$cambiotratam['estado'],
		'idevolucitr2'.$pfj=>$cambiotratam['evolucion_id'],
		'cargotrata'.$pfj=>$cambiotratam['cargo']));
		$this->salida.="<form name=\"forma1$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="EVOLUCIÓN MÉDICA DE ODONTOLOGÍA";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$cambiotratam['cargo']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DESCRIPCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$cambiotratam['descripcion']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="DIENTE";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$cambiotratam['hc_tipo_ubicacion_diente_id']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="SUPERFICIE";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$cambiotratam['des1']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="HALLAZGO";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$cambiotratam['des2']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="SOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$cambiotratam['des3']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="EVOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<textarea class='textarea' name='evoluciontr2$pfj' cols=100 rows=3>".$_REQUEST['evoluciontr2'.$pfj]."</textarea>";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		if($diag)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNOSTICOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diag);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
				'accion'.$pfj=>'eliminar_diagnosticos2',
				'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
				'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
				'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
				'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj],
				'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id']));
				$this->salida.="<td align=\"center\" width=\"6%\">";
				if($cambiotratam['estado']==0 AND $cambiotratam['evolucion_id']<>$this->evolucion)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/pconsultar.png\" border='0'>";
				}
				else
				{
					$this->salida.="<a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\" border='0'></a>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diag[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']==$this->evolucion)
				{
					$accionDiag=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
					'accion'.$pfj=>'cambiardiagnostico2',
					'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
					'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
					'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
					'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj],
					'diagnostitra'.$pfj=>$diag[$i]['diagnostico_id']));
					$this->salida.="<a href='$accionDiag'><img src=\"".GetThemePath()."/images/checkno.png\" border='0'></a>";
				}
				else if($diag[$i]['sw_principal']==0 AND $diag[$i]['evolucion_id']<>$this->evolucion)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diag[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$diaghistorial=$this->BuscarDiagnosticosTratamGuardaHistorial2();
		if($diaghistorial)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
			$this->salida.="DIAGNÓSTICOS DE EVOLUCIONES ANTERIORES";
			$this->salida.="</td>";
			$this->salida.="<td width=\"80%\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			for($i=0;$i<sizeof($diaghistorial);$i++)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td align=\"center\" width=\"6%\">";
				$this->salida.="<img src=\"".GetThemePath()."/images/pconsultar.png\" border='0'>";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"10%\">";
				$this->salida.="".$diaghistorial[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"5%\">";
				if($diaghistorial[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\" width=\"79%\">";
				$this->salida.="".$diaghistorial[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$dientes=$this->BuscarOdontogramaDienteTra();
		if($dientes AND $cambiotratam['estado']==1)
		{
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td colspan=\"2\" align=\"center\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td colspan=\"5\" align=\"center\">";
			$this->salida.="OTROS HALLAZGOS PARA EL MISMO DIENTE";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"7%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"32%\" align=\"center\">";
			$this->salida.="HALLAZGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"32%\" align=\"center\">";
			$this->salida.="SOLUCIÓN";
			$this->salida.="</td>";
			$this->salida.="<td width=\"14%\" align=\"center\">";
			$this->salida.="APLICAR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$j=0;
			for($i=0;$i<sizeof($dientes);$i++)
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
				$this->salida.="".$dientes[$i]['hc_tipo_ubicacion_diente_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$dientes[$i]['des1']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$dientes[$i]['des2']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$dientes[$i]['des3']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="<input type=\"checkbox\" name=\"copiar".$i."".$pfj."\" value=\"1\">";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>" ;
			$this->salida.="</tr>";
		}
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="<label class=\"label_mark\">CONTINUAR EN TRATAMIENTO</label>";
		if($cambiotratam['estado']==4)
		{
			$this->salida.="<input type=\"checkbox\" name=\"continua".$pfj."\" value=1 checked>";
		}
		else
		{
			$this->salida.="<input type=\"checkbox\" name=\"continua".$pfj."\" value=1>";
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		if($cambiotratam['estado']==0 AND $cambiotratam['evolucion_id']<>$this->evolucion)
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR\">";
		}
		else
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar1$pfj\" type=\"submit\" value=\"GUARDAR\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'borrartrat',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj],
		'estado'.$pfj=>$cambiotratam['estado'],
		'idevolucitr2'.$pfj=>$cambiotratam['evolucion_id'],
		'cargotrata'.$pfj=>$cambiotratam['cargo']));
		$this->salida.="<form name=\"forma5$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<td align=\"center\" width=\"50%\">";
		if($cambiotratam['estado']==0 AND $cambiotratam['evolucion_id']<>$this->evolucion)
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"ELIMINAR LA EVOLUCIÓN\">";
		}
		else
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar2$pfj\" type=\"submit\" value=\"ELIMINAR LA EVOLUCIÓN\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'eliminartrat',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj]));
		$this->salida.="<form name=\"forma5$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<td align=\"center\" width=\"50%\">";
		if($cambiotratam['estado']=='1')
		{
			$this->salida.="<input class=\"input-submit\" name=\"guardar4$pfj\" type=\"submit\" value=\"ELIMINAR TRATAMIENTO\">";
		}
		else
		{
			$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar4$pfj\" type=\"submit\" value=\"ELIMINAR TRATAMIENTO\">";
		}
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vectorD=$this->BuscarDiagnosticosTratam2();
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'tratamientos2',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj]));
		$this->salida.="<form name=\"forma3$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'>";
		$this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj' value =\"".$_REQUEST['codigo'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'>";
		$this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value =\"".$_REQUEST['diagnostico'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"7%\" align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		if($vectorD)
		{
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
			'accion'.$pfj=>'insertar_diagnosticos2',
			'Of'.$pfj=>$_REQUEST['Of'.$pfj],
			'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
			'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
			'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
			'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
			'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
			'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj]));
			$this->salida.="<form name=\"forma4$pfj\" action=\"$accionI\" method=\"post\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CÓDIGO</td>";
			$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="<td width=\"5%\" >OPCIÓN</td>";
			$this->salida.="<td width=\"5%\" >PRINC.</td>";
			$this->salida.="<td width=\"20%\">TIPO DX</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($vectorD);
			for($i=0;$i<$ciclo;$i++)
			{
				if($i%2)
				{
					$estilo='hc_submodulo_list_claro';
				}
				else
				{
					$estilo='hc_submodulo_list_oscuro';
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$vectorD[$i]['diagnostico_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\">";
				$this->salida.="".$vectorD[$i]['diagnostico_nombre']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['hc_odontograma_tratamiento_detalle_id']==NULL)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id'].">";
				}
				else
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['diagnostico_id']." checked>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['sw_principal']==1)
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checksi.png\" border='0'>";
					$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"1\">";
				}
				else
				{
					$this->salida.="<img src=\"".GetThemePath()."/images/checkno.png\" border='0'>";
					$this->salida.="<input type=\"hidden\" name=\"swprincipal".$pfj."\" value=\"0\">";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['tipo_diagnostico']==1)
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\" checked>&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
				}
				else if($vectorD[$i]['tipo_diagnostico']==2)
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\" checked>&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
				}
				else if($vectorD[$i]['tipo_diagnostico']==3)
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\" checked>&nbsp;CR&nbsp;&nbsp;";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
					$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;";
				}
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" colspan=\"5\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"right\" colspan=\"5\">";
			if($cambiotratam['estado']==0 AND $cambiotratam['evolucion_id']<>$this->evolucion)
			{
				$this->salida.="<input disabled=\"true\" class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
			}
			else
			{
				$this->salida.="<input class=\"input-submit\" name=\"guardar5$pfj\" type=\"submit\" value=\"GUARDAR\">";
			}
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada2();
			if(!empty($var))
			{
				$this->salida.="<br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida.="<tr>";
				$this->salida.="<td width=\"100%\" align=\"center\">";
				$this->salida.=$var;
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
			}
		}
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'tratamientos',
		'conteo'.$pfj=>$this->conteo,
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'idevolucitra'.$pfj=>$_REQUEST['idevolucitra'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontra'.$pfj=>$_REQUEST['evoluciontra'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" class=\"label\" colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" class=\"label\" colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function RetornarBarraDiagnosticos_Avanzada2()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'tratamientos2',
		'conteo'.$pfj=>$this->conteo,
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'odontratdi'.$pfj=>$_REQUEST['odontratdi'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'evoluciontr2'.$pfj=>$_REQUEST['evoluciontr2'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" class=\"label\" colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" class=\"label\" colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function RetornarBarraDiagnosticos_Avanzada3()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'tratamientospresupuestos',
		'conteo'.$pfj=>$this->conteo,
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
		'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
		'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
		'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" class=\"label\" colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" class=\"label\" colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function RetornarBarraDiagnosticos_Avanzada4()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'nuevoscargosnopres',
		'conteo'.$pfj=>$this->conteo,
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function RetornarBarraDiagnosticos_Avanzada5()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'tratamientosapoyos',
		'conteo'.$pfj=>$this->conteo,
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj],
		'cargotrata'.$pfj=>$_REQUEST['cargotrata'.$pfj],
		'cargodestr'.$pfj=>$_REQUEST['cargodestr'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],
		'ubicacion'.$pfj=>$_REQUEST['ubicacion'.$pfj],
		'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
		'cantida2'.$pfj=>$_REQUEST['cantida2'.$pfj],
		'cantida3'.$pfj=>$_REQUEST['cantida3'.$pfj],
		'evolucprtra'.$pfj=>$_REQUEST['evolucprtra'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function frmConsulta()
	{
		$odontograma=$this->BuscarOdontogramaFormaConsulta();
		if($odontograma===false)
		{
			return false;
		}
		if(sizeof($odontograma)!=0)
		{
			$randon=$this->ingreso._.rand();
			IncludeLib("jpgraph/Odontograma_graphic");
			$valoracion=$this->BuscarEnviarPintarMuelasConsulta();
			$valoracio2=$this->BuscarEnviarPintarMuelas2Consulta();
			$RutaImg=Odontograma($valoracion,$randon,1);
			$RutaIm2=Odontograma($valoracio2,$randon,2);
			$this->salida.="<br>";
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" class='hc_table_submodulo_list_title'>";
			$this->salida.="<label class=\"label\">ODONTOGRAMA DE PRIMERA VEZ__</label>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" class='hc_table_submodulo_list_title'>";
			$this->salida.="<label class=\"label\">ODONTOGRAMA DE TRATAMIENTO</label>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="<img src=\"".$RutaIm2."\" border=\"0\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$ruta=GetThemePath()."/images/simbolos1.png";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="<img src=\"".$ruta."\" border=\"0\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
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
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
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
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$this->salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="CARIADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="OBTURADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="PERDIDOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SANOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="TOTAL";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="SIN INCLUIR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_submodulo_list_claro>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$cariadosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$obturadosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$perdidosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$sanosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$totalp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$nocontadosp."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			//$this->salida.="</table><br>";
			//$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$this->salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="CARIADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="EXTRAIDOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="OBTURADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SANOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="TOTAL";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="SIN INCLUIR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_submodulo_list_claro>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$cariadosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$perdidosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$obturadosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$sanosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$totald."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$nocontadosd."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"100%\" align=\"center\">";
			$this->salida.="OBSERVACIÓN DEL ODONTOGRAMA DE PRIMERA VEZ";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_submodulo_list_claro>";
			$this->salida.="<td align=\"center\">";
			if($_REQUEST['observacio'.$this->frmPrefijo]<>NULL)
			{
				$this->salida.="".$_REQUEST['observacio'.$this->frmPrefijo]."";
			}
			else
			{
				$this->salida.="NO HAY OBSERVACIONES";
			}
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$ciclo=sizeof($odontograma);
			$cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
			$cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']<>0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odontograma[$i]['estadotrat']=='0')
					{
						$resultado=$this->BuscarIndicesTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
						if($resultado['sw_cariado']==1)
						{
							$cariadosp++;
						}
						else if($resultado['sw_obturado']==1)
						{
							$obturadosp++;
						}
						else if($resultado['sw_perdidos']==1)
						{
							$perdidosp++;
						}
						else if($resultado['sw_sanos']==1)
						{
							$sanosp++;
						}
						else
						{
							$nocontadosp++;
						}
					}
					else
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
				}
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']==0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odontograma[$i]['sw_cariado2']==1)
					{
						$cariadosp++;
					}
					else if($odontograma[$i]['sw_obturado2']==1)
					{
						$obturadosp++;
					}
					else if($odontograma[$i]['sw_perdidos2']==1)
					{
						$perdidosp++;
					}
					else if($odontograma[$i]['sw_sanos2']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']<>0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
				{
					if($odontograma[$i]['estadotrat']=='0')
					{
						$resultado=$this->BuscarIndicesTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
						if($resultado['sw_cariado']==1)
						{
							$cariadosd++;
						}
						else if($resultado['sw_obturado']==1)
						{
							$obturadosd++;
						}
						else if($resultado['sw_perdidos']==1)
						{
							$perdidosd++;
						}
						else if($resultado['sw_sanos']==1)
						{
							$sanosd++;
						}
						else
						{
							$nocontadosd++;
						}
					}
					else
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
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']==0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
				{
					if($odontograma[$i]['sw_cariado2']==1)
					{
						$cariadosd++;
					}
					else if($odontograma[$i]['sw_obturado2']==1)
					{
						$obturadosd++;
					}
					else if($odontograma[$i]['sw_perdidos2']==1)
					{
						$perdidosd++;
					}
					else if($odontograma[$i]['sw_sanos2']==1)
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
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$this->salida.="INDICE COP DEL ODONTOGRAMA DE TRATAMIENTO";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="CARIADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="OBTURADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="PERDIDOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SANOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="TOTAL";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="SIN INCLUIR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_submodulo_list_claro>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$cariadosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$obturadosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$perdidosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$sanosp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$totalp."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$nocontadosp."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			//$this->salida.="</table><br>";
			//$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$this->salida.="INDICE CEO DEL ODONTOGRAMA DE TRATAMIENTO";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_submodulo_list_title>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="CARIADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="EXTRAIDOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="OBTURADOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SANOS";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="TOTAL";
			$this->salida.="</td>";
			$this->salida.="<td width=\"20%\" align=\"center\">";
			$this->salida.="SIN INCLUIR";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_submodulo_list_claro>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$cariadosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$perdidosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$obturadosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$sanosd."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$totald."";
			$this->salida.="</td>";
			$this->salida.="<td align=\"center\">";
			$this->salida.="".$nocontadosd."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"5%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"15%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"30%\" align=\"center\">";
			$this->salida.="HALLAZGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"30%\" align=\"center\">";
			$this->salida.="SOLUCIÓN";
			$this->salida.="</td>";
			$this->salida.="<td width=\"14%\" align=\"center\">";
			$this->salida.="ESTADO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"6%\" align=\"center\">";
			$this->salida.="FECHA REGISTRO";
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
				if($odontograma[$i]['estadotrat']<>NULL)
				{
					$cambios=$this->BuscarCambiosTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$cambios['des1']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$cambios['des2']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					$this->salida.="".$cambios['des3']."";
					$this->salida.="</td>";
				}
				else
				{
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
				}
				$this->salida.="<td align=\"center\">";
				if($odontograma[$i]['estado']==1)
				{
					$this->salida.="POR REALIZAR";
				}
				else if($odontograma[$i]['estado']==0)
				{
					$this->salida.="SOLUCIONADO";
				}
				else if($odontograma[$i]['estado']==2)
				{
					$this->salida.="CANCELADO";
				}
				else if($odontograma[$i]['estado']==3)
				{
					$this->salida.="SIN PRESUP.";
				}
				else if($odontograma[$i]['estado']==4)
				{
					$this->salida.="EN TRATAMIENTO";
				}
				else if($odontograma[$i]['estado']>=5)
				{
					$this->salida.="MODIFICADO";
				}
				$this->salida.="</td>";
				$fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$fecha_registro[0]."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
			$_REQUEST['observacio2'.$this->frmPrefijo]=$this->BuscarDatosObserConsulta();
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\">";
			$this->salida.="OBSERVACIÓN DE TRATAMIENTO";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="<td align=\"center\">";
			if($_REQUEST['observacio2'.$this->frmPrefijo]<>NULL)
			{
				$this->salida.="".$_REQUEST['observacio2'.$this->frmPrefijo]."";
			}
			else
			{
				$this->salida.="NO HAY OBSERVACIONES";
			}
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$apoyos=$this->BuscarApoyosOdontogramaConsulta();
			if($apoyos<>NULL)
			{
				$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"8%\">CARGO</td>";
				$this->salida.="<td width=\"60%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
				$this->salida.="<td width=\"6%\" >CANTIDAD</td>";
				$this->salida.="<td width=\"10%\">UBICACIÓN</td>";
				$this->salida.="<td width=\"10%\">ESTADO</td>";
				$this->salida.="<td width=\"6%\">FECHA REGISTRO</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($apoyos);$i++)
				{
					if( $i % 2)
					{
						$estilo='modulo_list_claro';
					}
					else
					{
						$estilo='modulo_list_oscuro';
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($apoyos[$i]['estado']==1)
					{
						$this->salida.="POR REALIZAR";//listo
					}
					else if($apoyos[$i]['estado']==0)
					{
						$this->salida.="REALIZADO";//listo
					}
					else if($apoyos[$i]['estado']==2)
					{
						$this->salida.="CANCELADO";//listo
					}
					$this->salida.="</td>";
					$fecha_registro=explode(' ',$apoyos[$i]['fecha_registro']);
					$this->salida.="<td align=\"center\">".$fecha_registro[0]."";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="</table>";
			}
			$presup=$this->BuscarPresupuestosOdontogramaConsulta();
			if($presup<>NULL)
			{
				$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"8%\">CARGO</td>";
				$this->salida.="<td width=\"70%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
				$this->salida.="<td width=\"6%\" >CANTIDAD</td>";
				$this->salida.="<td width=\"10%\">ESTADO</td>";
				$this->salida.="<td width=\"6%\">FECHA REGISTRO</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($presup);$i++)
				{
					if( $i % 2)
					{
						$estilo='modulo_list_claro';
					}
					else
					{
						$estilo='modulo_list_oscuro';
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">".$presup[$i]['cargo']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"left\">".$presup[$i]['descripcion']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">".$presup[$i]['cantidad']."";
					$this->salida.="</td>";
					$this->salida.="<td align=\"center\">";
					if($presup[$i]['estado']==1)
					{
						$this->salida.="POR REALIZAR";//listo
					}
					else if($presup[$i]['estado']==0)
					{
						$this->salida.="REALIZADO";//listo
					}
					else if($presup[$i]['estado']==2)
					{
						$this->salida.="CANCELADO";//listo
					}
					$this->salida.="</td>";
					$fecha_registro=explode(' ',$presup[$i]['fecha_registro']);
					$this->salida.="<td align=\"left\">".$fecha_registro[0]."";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="</table>";
			}
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
		$odontograma=$this->BuscarOdontogramaFormaConsulta();
		if($odontograma===false)
		{
			return false;
		}
		if(sizeof($odontograma)!=0)
		{
			$randon=$this->ingreso._.rand();
			IncludeLib("jpgraph/Odontograma_graphic");
			$valoracion=$this->BuscarEnviarPintarMuelasConsulta();
			$valoracio2=$this->BuscarEnviarPintarMuelas2Consulta();
			$RutaImg=Odontograma($valoracion,$randon,1);
			$RutaIm2=Odontograma($valoracio2,$randon,2);
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr>";
			$salida.="<td align=\"center\" class='hc_table_submodulo_list_title'>";
			$salida.="<label class=\"label\">ODONTOGRAMA DE PRIMERA VEZ__</label>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td align=\"center\">";
			$salida.="<img src=\"".$RutaImg."\" border=\"0\">";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td align=\"center\" class='hc_table_submodulo_list_title'>";
			$salida.="<label class=\"label\">ODONTOGRAMA DE TRATAMIENTO</label>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td align=\"center\">";
			$salida.="<img src=\"".$RutaIm2."\" border=\"0\">";
			$salida.="</td>";
			$salida.="</tr>";
			$ruta=GetThemePath()."/images/simbolos1.png";
			$salida.="<tr>";
			$salida.="<td align=\"center\">";
			$salida.="<img src=\"".$ruta."\" border=\"0\">";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table><br>";
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
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
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
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="CARIADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="OBTURADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="PERDIDOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="SANOS";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="TOTAL";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="SIN INCLUIR";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_submodulo_list_claro>";
			$salida.="<td align=\"center\">";
			$salida.="".$cariadosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$obturadosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$perdidosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$sanosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$totalp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$nocontadosp."";
			$salida.="</td>";
			$salida.="</tr>";
			//$salida.="</table><br>";
			//$salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="CARIADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="EXTRAIDOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="OBTURADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="SANOS";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="TOTAL";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="SIN INCLUIR";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_submodulo_list_claro>";
			$salida.="<td align=\"center\">";
			$salida.="".$cariadosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$perdidosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$obturadosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$sanosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$totald."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$nocontadosd."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table>";
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=hc_table_list_title>";
			$salida.="<td width=\"100%\" align=\"center\">";
			$salida.="OBSERVACIÓN DEL ODONTOGRAMA DE PRIMERA VEZ";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_submodulo_list_claro>";
			$salida.="<td align=\"center\">";
			if($_REQUEST['observacio'.$this->frmPrefijo]<>NULL)
			{
				$salida.="".$_REQUEST['observacio'.$this->frmPrefijo]."";
			}
			else
			{
				$salida.="NO HAY OBSERVACIONES";
			}
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table><br>";
			$ciclo=sizeof($odontograma);
			$cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
			$cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']<>0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odontograma[$i]['estadotrat']=='0')
					{
						$resultado=$this->BuscarIndicesTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
						if($resultado['sw_cariado']==1)
						{
							$cariadosp++;
						}
						else if($resultado['sw_obturado']==1)
						{
							$obturadosp++;
						}
						else if($resultado['sw_perdidos']==1)
						{
							$perdidosp++;
						}
						else if($resultado['sw_sanos']==1)
						{
							$sanosp++;
						}
						else
						{
							$nocontadosp++;
						}
					}
					else
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
				}
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']==0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
				{
					if($odontograma[$i]['sw_cariado2']==1)
					{
						$cariadosp++;
					}
					else if($odontograma[$i]['sw_obturado2']==1)
					{
						$obturadosp++;
					}
					else if($odontograma[$i]['sw_perdidos2']==1)
					{
						$perdidosp++;
					}
					else if($odontograma[$i]['sw_sanos2']==1)
					{
						$sanosp++;
					}
					else
					{
						$nocontadosp++;
					}
				}
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']<>0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
				{
					if($odontograma[$i]['estadotrat']=='0')
					{
						$resultado=$this->BuscarIndicesTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
						if($resultado['sw_cariado']==1)
						{
							$cariadosd++;
						}
						else if($resultado['sw_obturado']==1)
						{
							$obturadosd++;
						}
						else if($resultado['sw_perdidos']==1)
						{
							$perdidosd++;
						}
						else if($resultado['sw_sanos']==1)
						{
							$sanosd++;
						}
						else
						{
							$nocontadosd++;
						}
					}
					else
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
				else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
				AND $odontograma[$i]['estado']==0
				AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
				{
					if($odontograma[$i]['sw_cariado2']==1)
					{
						$cariadosd++;
					}
					else if($odontograma[$i]['sw_obturado2']==1)
					{
						$obturadosd++;
					}
					else if($odontograma[$i]['sw_perdidos2']==1)
					{
						$perdidosd++;
					}
					else if($odontograma[$i]['sw_sanos2']==1)
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
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$salida.="INDICE COP DEL ODONTOGRAMA DE TRATAMIENTO";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="CARIADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="OBTURADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="PERDIDOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="SANOS";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="TOTAL";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="SIN INCLUIR";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_submodulo_list_claro>";
			$salida.="<td align=\"center\">";
			$salida.="".$cariadosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$obturadosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$perdidosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$sanosp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$totalp."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$nocontadosp."";
			$salida.="</td>";
			$salida.="</tr>";
			//$salida.="</table><br>";
			//$salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
			$salida.="INDICE CEO DEL ODONTOGRAMA DE TRATAMIENTO";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_table_submodulo_list_title>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="CARIADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="EXTRAIDOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="OBTURADOS";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="SANOS";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="TOTAL";
			$salida.="</td>";
			$salida.="<td width=\"20%\" align=\"center\">";
			$salida.="SIN INCLUIR";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_submodulo_list_claro>";
			$salida.="<td align=\"center\">";
			$salida.="".$cariadosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$perdidosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$obturadosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$sanosd."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$totald."";
			$salida.="</td>";
			$salida.="<td align=\"center\">";
			$salida.="".$nocontadosd."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table><br>";
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=hc_table_list_title>";
			$salida.="<td width=\"5%\" align=\"center\">";
			$salida.="DIENTE";
			$salida.="</td>";
			$salida.="<td width=\"15%\" align=\"center\">";
			$salida.="SUPERFICIE";
			$salida.="</td>";
			$salida.="<td width=\"30%\" align=\"center\">";
			$salida.="HALLAZGO";
			$salida.="</td>";
			$salida.="<td width=\"30%\" align=\"center\">";
			$salida.="SOLUCIÓN";
			$salida.="</td>";
			$salida.="<td width=\"14%\" align=\"center\">";
			$salida.="ESTADO";
			$salida.="</td>";
			$salida.="<td width=\"6%\" align=\"center\">";
			$salida.="FECHA REGISTRO";
			$salida.="</td>";
			$salida.="</tr>";
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
				$salida.="<tr $color>";
				if($odontograma[$i]['estadotrat']<>NULL)
				{
					$cambios=$this->BuscarCambiosTratamiento($odontograma[$i]['hc_odontograma_tratamiento_detalle_id']);
					$salida.="<td align=\"center\">";
					$salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					$salida.="".$cambios['des1']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					$salida.="".$cambios['des2']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					$salida.="".$cambios['des3']."";
					$salida.="</td>";
				}
				else
				{
					$salida.="<td align=\"center\">";
					$salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					$salida.="".$odontograma[$i]['des1']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					$salida.="".$odontograma[$i]['des2']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					$salida.="".$odontograma[$i]['des3']."";
					$salida.="</td>";
				}
				$salida.="<td align=\"center\">";
				if($odontograma[$i]['estado']==1)
				{
					$salida.="POR REALIZAR";
				}
				else if($odontograma[$i]['estado']==0)
				{
					$salida.="SOLUCIONADO";
				}
				else if($odontograma[$i]['estado']==2)
				{
					$salida.="CANCELADO";
				}
				else if($odontograma[$i]['estado']==3)
				{
					$salida.="SIN PRESUP.";
				}
				else if($odontograma[$i]['estado']==4)
				{
					$salida.="EN TRATAMIENTO";
				}
				else if($odontograma[$i]['estado']>=5)
				{
					$salida.="MODIFICADO";
				}
				$salida.="</td>";
				$fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
				$salida.="<td align=\"center\">";
				$salida.="".$fecha_registro[0]."";
				$salida.="</td>";
				$salida.="</tr>";
			}
			$salida.="</table>";
			$_REQUEST['observacio2'.$this->frmPrefijo]=$this->BuscarDatosObserConsulta();
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td align=\"center\">";
			$salida.="OBSERVACIÓN DE TRATAMIENTO";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_submodulo_list_claro\">";
			$salida.="<td align=\"center\">";
			if($_REQUEST['observacio2'.$this->frmPrefijo]<>NULL)
			{
				$salida.="".$_REQUEST['observacio2'.$this->frmPrefijo]."";
			}
			else
			{
				$salida.="NO HAY OBSERVACIONES";
			}
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table>";
			$apoyos=$this->BuscarApoyosOdontogramaConsulta();
			if($apoyos<>NULL)
			{
				$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="<td width=\"8%\">CARGO</td>";
				$salida.="<td width=\"60%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
				$salida.="<td width=\"6%\" >CANTIDAD</td>";
				$salida.="<td width=\"10%\">UBICACIÓN</td>";
				$salida.="<td width=\"10%\">ESTADO</td>";
				$salida.="<td width=\"6%\">FECHA REGISTRO</td>";
				$salida.="</tr>";
				for($i=0;$i<sizeof($apoyos);$i++)
				{
					if( $i % 2)
					{
						$estilo='modulo_list_claro';
					}
					else
					{
						$estilo='modulo_list_oscuro';
					}
					$salida.="<tr class=\"$estilo\">";
					$salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
					$salida.="</td>";
					$salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					if($apoyos[$i]['estado']==1)
					{
						$salida.="POR REALIZAR";//listo
					}
					else if($apoyos[$i]['estado']==0)
					{
						$salida.="REALIZADO";//listo
					}
					else if($apoyos[$i]['estado']==2)
					{
						$salida.="CANCELADO";//listo
					}
					$salida.="</td>";
					$fecha_registro=explode(' ',$apoyos[$i]['fecha_registro']);
					$salida.="<td align=\"center\">".$fecha_registro[0]."";
					$salida.="</td>";
					$salida.="</tr>";
				}
				$salida.="</table>";
			}
			$presup=$this->BuscarPresupuestosOdontogramaConsulta();
			if($presup<>NULL)
			{
				$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="<td width=\"8%\">CARGO</td>";
				$salida.="<td width=\"70%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
				$salida.="<td width=\"6%\" >CANTIDAD</td>";
				$salida.="<td width=\"10%\">ESTADO</td>";
				$salida.="<td width=\"6%\">FECHA REGISTRO</td>";
				$salida.="</tr>";
				for($i=0;$i<sizeof($presup);$i++)
				{
					if( $i % 2)
					{
						$estilo='modulo_list_claro';
					}
					else
					{
						$estilo='modulo_list_oscuro';
					}
					$salida.="<tr class=\"$estilo\">";
					$salida.="<td align=\"center\">".$presup[$i]['cargo']."";
					$salida.="</td>";
					$salida.="<td align=\"left\">".$presup[$i]['descripcion']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">".$presup[$i]['cantidad']."";
					$salida.="</td>";
					$salida.="<td align=\"center\">";
					if($presup[$i]['estado']==1)
					{
						$salida.="POR REALIZAR";//listo
					}
					else if($presup[$i]['estado']==0)
					{
						$salida.="REALIZADO";//listo
					}
					else if($presup[$i]['estado']==2)
					{
						$salida.="CANCELADO";//listo
					}
					$salida.="</td>";
					$fecha_registro=explode(' ',$presup[$i]['fecha_registro']);
					$salida.="<td align=\"center\">".$fecha_registro[0]."";
					$salida.="</td>";
					$salida.="</tr>";
				}
				$salida.="</table>";
			}
			$salida.="<br>";
		}
		else
		{
			return false;
		}
		return $salida;
	}

}
?>
