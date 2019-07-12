<?php
	/**************************************************************************************
	* $Id: hc_AntecedentesPersonales_HTML.php,v 1.14 2006/12/19 21:00:12 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	*
	* coduigo Tomado del Anterior Submodulo de Antecedentes autor:Jaime Andres Valencia Salazar
	*
	* Clase para retornar la presentacion en pantalla en html de los formularios de 
	* insercion y de consulta de los datos del submodulo antecedentes personales, se 
	* extiende la clase Antecedentespersonales y asi pueden ser utilizados los metodos 
	* de esta clase en la anterior.
	**************************************************************************************/
	class AntecedentesPersonales_HTML extends AntecedentesPersonales
	{
		/**
		* Color de fondo especial para el manejo de antecedentes
		*
		* @var text
		* @access private
		*/
		var $backcolor;
		/**
		* Color especial de la letra para el manejo de antecedentes
		*
		* @var text
		* @access private
		*/
    var $backcolorf;

    function AntecedentesPersonales_HTML()
    {
			$this->AntecedentesPersonales();//constructor del padre
			$this->backcolor="pink";
			$this->backcolorf="#990000";
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
      'autor'=>'JAIME ANDRES VALENCIA',
      'descripcion_cambio' => '',
      'requiere_sql' => false,
      'requerimientos_adicionales' => '',
      'version_kernel' => '1.0'
      );
      return $informacion;
    }
    //////////////////////
    function frmConsulta()
    {
	   $pfj=$this->frmPrefijo;
        $tipo_ant=$this->BusquedaAntecedentes();
        if(sizeof($tipo_ant[0])!=0)
        {
            $this->salida.="<br>";
            $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
            $this->salida.="     <tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="         <td align=\"center\" colspan=\"2\">";
            $this->salida.="ANTECEDENTES PERSONALES";
            $this->salida.="         </td>";
            $this->salida.="         <td align=\"center\" width=\"10\">";
            $this->salida.="Si";
            $this->salida.="         </td>";
            $this->salida.="         <td align=\"center\" width=\"10\">";
            $this->salida.="No";
            $this->salida.="         </td>";
            $this->salida.="         <td align=\"center\" width=\"50%\">";
            $this->salida.="Detalle";
            $this->salida.="         </td>";
            $this->salida.="    </tr>";
            $i=0;
            $r=0;
            $s=2;
            while($i<sizeof($tipo_ant[0]))
            {
                if($mira<>$tipo_ant[0][$i])
                {
                    $mira=$tipo_ant[0][$i];
                }
                if($mira2<>$tipo_ant[7][$i])
                {
                    $mira2=$tipo_ant[7][$i];
                }
                $j=$i;
                $t1=0;
                while($tipo_ant[0][$j]<>"")
                {
                    if(!strcasecmp($mira2,$tipo_ant[7][$j]))
                    {
                        $t1++;
                    }
                    $j++;
                }
                $j=$i;
                $t=0;
                while($tipo_ant[0][$j]<>"")
                {
                    if(!strcasecmp($mira,$tipo_ant[0][$j]))
                    {
                        $t++;
                    }
                    $j++;
                }
                if($tipo_ant[2][$i]<>"")
                {
                    $this->salida.="    <tr>\n";
                    if($tipo_ant[7][$i]<>$tipo_ant[7][$i-1])
                    {
                        if($s==0)
                        {
                            $this->salida.="<td rowspan=\"".($t1)."\" class=\"hc_submodulo_list_oscuro\">";
                        }
                        else
                        {
                            $this->salida.="<td rowspan=\"".($t1)."\" class=\"hc_submodulo_list_claro\">";
                        }
                        $this->salida.="<label class=\"label\">".$tipo_ant[7][$i]."</label>";
                        $this->salida.="</td>";
                    }
                    if($tipo_ant[0][$i]<>$tipo_ant[0][$i-1])
                    {
                        $p=$t;
                        if ($r==0)
                        {
                            $r=1;
                            $this->salida.="        <td rowspan=\"$t\" class=\"hc_submodulo_list_claro\">\n";
                        }
                        else
                        {
                            $r=0;
                            $this->salida.="        <td rowspan=\"$t\" class=\"hc_submodulo_list_oscuro\">\n";
                        }
                        $this->salida.="<label class=\"label\">".$tipo_ant[0][$i]."</label>";
                        $this->salida.="        </td>\n";
                    }
                    if ($tipo_ant[10][$i]=="1")
                    {
                        $this->salida.="        <td align=\"center\" width=\"10\" bgcolor=\"".$this->backcolor."\">\n";
                        $this->salida.="            si\n";
                    }
                    else
                    {
                        $this->salida.="        <td align=\"center\" width=\"10\">\n";
                        //$this->salida.="            si\n";
                    }
                    $this->salida.="        </td>\n";
                    if ($tipo_ant[10][$i]=="0")
                    {
                        $this->salida.="        <td align=\"center\" width=\"10\" bgcolor=\"".$this->backcolor."\">\n";
                        $this->salida.="no\n";
                    }
                    else
                    {
                        $this->salida.="        <td align=\"center\" width=\"10\">\n";
                        //$this->salida.="no\n";
                    }
                    $this->salida.="        </td>\n";
                    if($r==0)
                    {
                        $this->salida.="        <td align=\"left\" class=\"hc_submodulo_list_oscuro\">";
                    }
                    else
                    {
                        $this->salida.="        <td align=\"left\" class=\"hc_submodulo_list_claro\">";
                    }
                    if ($tipo_ant[3][$i]=="1")
                    {
                        $this->salida.="<font color=\"".$this->backcolorf."\">\n";
                        $this->salida.=$tipo_ant[2][$i];
                        $this->salida.="</font>";
                    }
                    else
                    {
                        $this->salida.=$tipo_ant[2][$i];
                    }
                    $this->salida.="</td>\n";
                    $this->salida.="    </tr>\n";
                }
                $i++;
            }
            $this->salida.="</table>";
            $datosbusqueda=$this->BusquedaTotalToxicos();
		  $instituciones=$this->BusquedaInstituciones();
            if(!empty($datosbusqueda))
            {
                $sustancias=$this->BusquedaSustanciasAdictivas();
                $ultimo=$this->BusquedaUltimoConsumo();
                $problemas=$this->BusquedaProblemasxConsumo();
                $this->salida.="<br>";
                $this->salida.="<table width=\"100%\" align=\"center\" class=\"hc_table_submodulo_list\">";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="<td>";
                $this->salida.="Sustancias Adictivas";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="Patron de Consumo";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="Ultimo Consumo";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="Problemas Por Consumo";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="Edad de Inicio";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="Tiempo de Consumo";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                foreach($sustancias as $t=>$v)
                {
                	$sustancia_id = $v[hc_tipos_sustancias_adictivas_id];
                    if(!empty($datosbusqueda[$sustancia_id]))
                    {
                        if($spy==0)
                        {
                            $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
                            $spy=1;
                        }
                        else
                        {
                            $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
                            $spy=0;
                        }
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="$v[descripcion]";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $patron=$this->BusquedaPatronConsumo($v[tipo_patronconsumo]);
                        foreach($patron as $k=>$v)
                        {
                            if($datosbusqueda[$sustancia_id]['hc_tipos_patron_consumos_id']==$k)
                            {
                                $this->salida.="$v";
                            }
                        }
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        foreach($ultimo as $k=>$v)
                        {
                            if($datosbusqueda[$sustancia_id]['hc_tipos_ultimo_consumo_id']==$k)
                            {
                                $this->salida.="$v";
                            }
                        }
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        foreach($problemas as $k=>$v)
                        {
                            if($datosbusqueda[$sustancia_id]['hc_tipos_problemasxconsumo_id']==$k)
                            {
                                $this->salida.="$v";
                            }
                        }
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        if(!empty($datosbusqueda[$sustancia_id]['edad_inicio']))
                        {
                            $this->salida.=$datosbusqueda[$sustancia_id]['edad_inicio']." Años";
                        }
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.=$datosbusqueda[$sustancia_id]['tiempo_consumo'];
                        if($datosbusqueda[$sustancia_id]['tiempo_consumo_tipo']=='A')
                        {
                            $this->salida.=" Años";
                        }
                        if($datosbusqueda[$sustancia_id]['tiempo_consumo_tipo']=='M')
                        {
                            $this->salida.=" Meses";
                        }
                        if($datosbusqueda[$sustancia_id]['tiempo_consumo_tipo']=='D')
                        {
                            $this->salida.=" Dias";
                        }
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                    }
                }
                $this->salida.="</table>";
            }
            if(!empty($instituciones))
            {
                $this->salida.="<br>";
                $this->salida.="<table width=\"100%\" align=\"center\">";//class=\"hc_table_submodulo_list\"
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="<td align=\"center\" colspan=\"4\">";
                $this->salida.="PROGRAMAS DE REHABILITACION";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                foreach($instituciones as $k=>$v)
                {
                    if($spy==0)
                    {
                        $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
                        $spy=1;
                    }
                    else
                    {
                        $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
                        $spy=0;
                    }
                    $this->salida.="<td align=\"center\">";
                    $this->salida.="Institución:";
                    $this->salida.="</td>";
                    $this->salida.="<td align=\"center\">";
                    $this->salida.=$v['nombre_institucion'];
                    $this->salida.="</td>";
                    $this->salida.="<td align=\"center\">";
                    $this->salida.="Estancia:";
                    $this->salida.="</td>";
                    $this->salida.="<td align=\"center\">";
                    $this->salida.=$v['estancia_institucion'];
                    if($v['tipo_estancia_institucion']=='A')
                    {
                        $this->salida.=" Años";
                    }
                    if($v['tipo_estancia_institucion']=='M')
                    {
                        $this->salida.=" Meses";
                    }
                    if($v['tipo_estancia_institucion']=='D')
                    {
                        $this->salida.=" Dias";
                    }
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                }
                $this->salida.="</table>";
            }
        }
        else
        {
            return false;
        }
	    return true;
    }

    function SetStyle($campo)
    {
      if ($this->frmError[$campo]||$campo=="MensajeError")
        {
          if ($campo=="MensajeError")
            {
              return ("<tr><td class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
            }
            return ("hc_tderror");
        }
        return ("hc_tdlabel");
    }
	//claudia

		function Frmforma_infancia_niez()
		{
                $pfj=$this->frmPrefijo;
                //$this->salida= ThemeAbrirTablaSubModulo('ANTECEDENTES PERSONALES DE INFANCIA Y NIï¿½Z');
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_infancia_niez'));
                $this->salida .= "<form name=\"formajus$pfj\" action=\"$accion\" method=\"post\">";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida.="</table>";

                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align=\"center\" colspan=\"5\">ANTECEDENTES PERSONALES DE INFANCIA Y NIï¿½Z</td>";
                $this->salida.="</tr>";

                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}

                $this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="<td colspan=\"5\" width=\"100%\"align=\"left\" >PRENACIMIENTO</td>";
                $this->salida.="</tr>";

//EMBARAZO
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" width=\"20%\">EMBARAZO</td>";
                if ($_REQUEST['sw_tipo_embarazo'.$pfj] == '1')
                {
                    $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'sw_tipo_embarazo$pfj' checked value = '1'>NORMAL</td>";
                }
                else
                {
            $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'sw_tipo_embarazo$pfj' value = '1'>NORMAL</td>";
								}

                if ($_REQUEST['tipo_embarazo'.$pfj] == '2')
                {
                    $this->salida.="<td  width=\"20%\" align = left ><input type = radio name= 'sw_tipo_embarazo$pfj' checked value = '2'>DESEADO</td>";
                }
                else
                {
            $this->salida.="<td  width=\"20%\" align = left ><input type = radio name= 'sw_tipo_embarazo$pfj' value = '2'>DESEADO</td>";
								}

                $this->salida.="<td colspan=\"2\" width=\"45%\"align=\"left\" >";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['sw_tipo_embarazo'.$pfj] == '3')
                {
                    $this->salida.="<td  width=\"40%\" align = left ><input type = radio name= 'sw_tipo_embarazo$pfj' checked value = '3'>COMPLICADO</td>";
                }
                else
                {
									$this->salida.="<td  width=\"40%\" align = left ><input type = radio name= 'sw_tipo_embarazo$pfj' value = '3'>COMPLICADO</td>";
                }
								//pendiente cargar esta lista
								$complicaciones = $this->Tipos_Complicaciones_Embarazo();
                $this->salida.="<td width=\"60%\" align = left >";
                $this->salida.="<select size = 1 name = 'complicaciones_embarazo$pfj' class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($complicaciones);$i++)
                {
                        if ($_REQUEST['complicaciones_embarazo'.$pfj]  != $complicaciones[$i][tipo_embarazo_complicado_id])
                            {
                                $this->salida.="<option value = ".$complicaciones[$i][tipo_embarazo_complicado_id].">".$complicaciones[$i][descripcion]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$complicaciones[$i][tipo_embarazo_complicado_id]." selected >".$complicaciones[$i][descripcion]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
								//PARTO
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" width=\"20%\">PARTO</td>";

                if ($_REQUEST['sw_sitio_parto'.$pfj] == '1')
                {
                    $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'sw_sitio_parto$pfj' checked value = '1'>HOSPITAL</td>";
                }
                else
                {
									$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'sw_sitio_parto$pfj' value = '1'>HOSPITAL</td>";
                }

                if ($_REQUEST['sw_sitio_parto'.$pfj] == '2')
                {
                    $this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'sw_sitio_parto$pfj' checked value = '2'>CASA</td>";
                }
                else
                {
									$this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'sw_sitio_parto$pfj' value = '2'>CASA</td>";
                }

                $this->salida.="<td colspan=\"2\" width=\"45%\"align=\"left\" >";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['sw_sitio_parto'.$pfj] == '3')
                {
                    $this->salida.="<td width=\"40%\" align = left ><input type = radio name= 'sw_sitio_parto$pfj' checked value = '3'>OTRO</td>";
                }
                else
                {
										$this->salida.="<td width=\"40%\" align = left ><input type = radio name= 'sw_sitio_parto$pfj' value = '3'>OTRO</td>";
                }

                if (($_REQUEST['sitio_parto'.$pfj])  == '')
                {
                    $this->salida.="<td width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'sitio_parto$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
								else
                {
                    $this->salida.="<td width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'sitio_parto$pfj' cols = 60 rows = 5>".$_REQUEST['sitio_parto'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
								//COMPLICACION
								$this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td width=\"20%\"align=\"left\" >COMPLICACION</td>";
                if ($_REQUEST['sw_complicacion'.$pfj] != '1')
                {
                  $this->salida.="<td width=\"15%\"align=\"left\" ><input type = radio name= 'sw_complicacion$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td width=\"15%\"align=\"left\" ><input type = radio checked name= 'sw_complicacion$pfj' value = '0'>&nbsp; NO</td>";
                }
                else
                {
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_complicacion$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio name= 'sw_complicacion$pfj' value = '0'>&nbsp; NO</td>";
                }
                $this->salida.="<td width=\"25%\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'complicacion$pfj'   value =\"".$_REQUEST['complicacion'.$pfj]."\"></td>" ;

//pendiente cargar datos
                $tipos_complicacion = $this->Tipos_Complicacion();
                $this->salida.="<td width=\"20%\" align = left >";
                $this->salida.="<select size = 1 name = 'tipo_complicacion$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($tipos_complicacion);$i++)
                {
                        if ((($_REQUEST['tipo_complicacion'.$pfj])  != $tipos_complicacion[$i][tipo_complicacion_id]) )
                            {
                                $this->salida.="<option value = ".$tipos_complicacion[$i][tipo_complicacion_id].">".$tipos_complicacion[$i][descripcion]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$tipos_complicacion[$i][tipo_complicacion_id]." selected >".$tipos_complicacion[$i][descripcion]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

//ALIMENTACION MATERNA
        $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td width=\"20%\"align=\"left\" >ALIMENTACION MATERNA</td>";
                if ($_REQUEST['sw_alimentacion_materna'.$pfj] != '1')
                {
                  $this->salida.="<td width=\"15%\"align=\"left\" ><input type = radio name= 'sw_alimentacion_materna$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td width=\"15%\"align=\"left\" ><input type = radio checked name= 'sw_alimentacion_materna$pfj' value = '0'>&nbsp; NO</td>";
                }
                else
                {
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_alimentacion_materna$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio name= 'sw_alimentacion_materna$pfj' value = '0'>&nbsp; NO</td>";
                }

                //pendiente cargar datos
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td colspan=\"2\" width=\"45%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="<td colspan=\"5\" width=\"100%\" align=\"left\" >DESARROLLO PSICOMOTOR</td>";
                $this->salida.="</tr>";


//GATEO
//pendiente cargar datos
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td width=\"20%\"align=\"left\" >GATEO</td>";
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td width=\"15%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="<td width=\"20%\"align=\"left\" >CAMINAR</td>";
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td colspan=\"2\" width=\"45%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td width=\"20%\"align=\"left\" >PALABRAS</td>";
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td width=\"15%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="<td width=\"20%\"align=\"left\" >FRASES</td>";
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td colspan=\"2\" width=\"45%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";


//actividad lucida compartida
        $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td  colspan=\"2\" width=\"35%\"align=\"left\" >ACTIVIDAD LUCIDA COMPARTIDA</td>";
                if ($_REQUEST['sw_actividad_lucida_compartida'.$pfj] != '1')
                {
                  $this->salida.="<td  width=\"20%\"align=\"left\" ><input type = radio name= 'sw_actividad_lucida_compartida$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td  width=\"25%\"align=\"left\" ><input type = radio checked name= 'sw_actividad_lucida_compartida$pfj' value = '0'>&nbsp; NO</td>";
                }
                else
                {
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_actividad_lucida_compartida$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td width=\"25%\"align=\"left\" ><input type = radio name= 'sw_actividad_lucida_compartida$pfj' value = '0'>&nbsp; NO</td>";
                }
                $this->salida.="<td  width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'actividad_lucida_compartida$pfj'   value =\"".$_REQUEST['actividad_lucida_compartida'.$pfj]."\"></td>" ;
                $this->salida.="</tr>";



//CONTROL ESFINTER
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"2\" width=\"35%\"align=\"left\" >CONTROL SFINTER</td>";

//pendiente este select
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td colspan=\"3\" width=\"65%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";


//angustia de separacion
        $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"2\" width=\"35%\"align=\"left\" >ANGUSTIA DE SEPARACION</td>";
                if ($_REQUEST['sw_angustia_separacion'.$pfj] != '1')
                {
                  $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_angustia_separacion$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"2\" width=\"45%\"align=\"left\" ><input type = radio checked name= 'sw_angustia_separacion$pfj' value = '0'>&nbsp; NO</td>";
                }
                else
                {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_angustia_separacion$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"2\" width=\"45%\"align=\"left\" ><input type = radio name= 'sw_angustia_separacion$pfj' value = '0'>&nbsp; NO</td>";
                }
                $this->salida.="</tr>";


//EDAD INICIO ESCOLAR
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"2\" width=\"35%\"align=\"left\" >EDAD INICIO ESCOLAR</td>";
                $cada_periocidad = $this->Cargar_Periocidad();
                $this->salida.="<td colspan=\"3\" width=\"65%\" align = left >";
                $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
                $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                for($i=0;$i<sizeof($cada_periocidad);$i++)
                {
                        if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                        else
                            {
                                $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                            }
                }
                $this->salida.="</select>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

                //COMPORTAMIENTO
                //pendiente select
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"2\" align=\"left\" width=\"35%\">COMPORTAMIENTO</td>";
                $this->salida.="<td colspan=\"3\" width=\"65%\"align=\"left\" >";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['tipo_embarazo'.$pfj] == '3')
                {
                    $this->salida.="<td  colspan=\"2\" width=\"100%\" align = left ><input type = radio name= 'tipo_embarazo$pfj' checked value = '3'>ADAPTADO</td>";
                }
                else
                {
            $this->salida.="<td  colspan=\"2\" width=\"100%\" align = left ><input type = radio name= 'tipo_embarazo$pfj' value = '0'>ADAPTADO</td>";
                }
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['tipo_embarazo'.$pfj] == '3')
                {
                    $this->salida.="<td  colspan=\"2\" width=\"100%\" align = left ><input type = radio name= 'tipo_embarazo$pfj' checked value = '3'>HIPERACTIVO</td>";
                }
                else
                {
            $this->salida.="<td  colspan=\"2\" width=\"100%\" align = left ><input type = radio name= 'tipo_embarazo$pfj' value = '0'>HIPERACTIVO</td>";
                }
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['tipo_embarazo'.$pfj] == '3')
                {
                    $this->salida.="<td  colspan=\"1\" width=\"20%\" align = left ><input type = radio name= 'tipo_embarazo$pfj' checked value = '3'>OTRO</td>";
                }
                else
                {
            $this->salida.="<td  colspan=\"1\" width=\"20%\" align = left ><input type = radio name= 'tipo_embarazo$pfj' value = '0'>OTRO</td>";
                }
                $this->salida.="<td colspan=\"1\" width=\"80\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$pfj'   value =\"".$_REQUEST['medicamento_pos'.$pfj]."\"></td>" ;
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";


                //ACEPTACION DE LA AUTORIDAD
        $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"2\" width=\"35%\"align=\"left\" >ACEPTACION DE LA AUTORIDAD</td>";
                if ($_REQUEST['sw_aceptacion_autoridad'.$pfj] != '1')
                {
                  $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio name= 'sw_aceptacion_autoridad$pfj' value = '1'>&nbsp; ADECUADA</td>";
                    $this->salida.="<td width=\"25%\"align=\"left\" ><input type = radio checked name= 'sw_aceptacion_autoridad$pfj' value = '0'>&nbsp; PROBLEMATICA</td>";
                }
                else
                {
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_aceptacion_autoridad$pfj' value = '1'>&nbsp; ADECUADA</td>";
                    $this->salida.="<td width=\"25%\"align=\"left\" ><input type = radio name= 'sw_aceptacion_autoridad$pfj' value = '0'>&nbsp; PROBLEMATICA</td>";
                }
                $this->salida.="<td width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'aceptacion_autoridad$pfj'   value =\"".$_REQUEST['aceptacion_autoridad'.$pfj]."\"></td>" ;
                $this->salida.="</tr>";


                //RENDIMIENTO ACADEMICO

       $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"2\" width=\"35%\"align=\"left\" >RENDIMIENTO ACADEMICO</td>";
                if ($_REQUEST['sw_rendimiento_academico'.$pfj] != '1')
                {
                  $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio name= 'sw_rendimiento_academico$pfj' value = '1'>&nbsp; BUENO</td>";
                    $this->salida.="<td width=\"25%\"align=\"left\" ><input type = radio checked name= 'sw_rendimiento_academico$pfj' value = '0'>&nbsp; MALO</td>";
                }
                else
                {
                    $this->salida.="<td width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_rendimiento_academico$pfj' value = '1'>&nbsp; BUENO</td>";
                    $this->salida.="<td width=\"25%\"align=\"left\" ><input type = radio name= 'sw_rendimiento_academico$pfj' value = '0'>&nbsp; MALO</td>";
                }
                $this->salida.="<td width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'rendimiento_academico$pfj'   value =\"".$_REQUEST['rendimiento_academico'.$pfj]."\"></td>" ;
                $this->salida.="</tr>";


                $this->salida.="</table><br>";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
                $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR ANTECEDENTES DE INFANCIA Y NIï¿½Z\"></td>";
                $this->salida .= "</form>";
                $this->salida.="</tr></table>";
                //$this->salida .= ThemeCerrarTablaSubModulo();
		}

		function Frmforma_adultez()
		{
                $pfj=$this->frmPrefijo;
                //$this->salida= ThemeAbrirTablaSubModulo('ANTECEDENTES PERSONALES DE INFANCIA Y NIï¿½Z');
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_infancia_niez'));
                $this->salida .= "<form name=\"formajus$pfj\" action=\"$accion\" method=\"post\">";

                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida.="</table>";

                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align=\"center\" colspan=\"5\">ADULTEZ</td>";
                $this->salida.="</tr>";

                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}

                //HISTORIA OCUPACIONAL
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"1\"  width=\"20%\"align=\"left\" >HISTORIA OCUPACIONAL</td>";
                if (($_REQUEST['historia_ocupacional'.$pfj])  == '')
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'historia_ocupacional$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
                  else
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'historia_ocupacional$pfj' cols = 60 rows = 5>".$_REQUEST['historia_ocupacional'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";

                //HABITOS FRENTE AL DINERO
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >HABITOS FRENTE AL DINERO</td>";
                if (($_REQUEST['habitos_dinero'.$pfj])  == '')
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'habitos_dinero$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
                  else
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'habitos_dinero$pfj' cols = 60 rows = 5>".$_REQUEST['habitos_dinero'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";

                //RELACIONES SOCIALES
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td width=\"20%\" colspan=\"1\" align=\"left\" >RELACIONES SOCIALES</td>";
                if (($_REQUEST['relaciones_sociales'.$pfj])  == '')
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'relaciones_sociales$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
                  else
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'relaciones_sociales$pfj' cols = 60 rows = 5>".$_REQUEST['relaciones_sociales'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";


                //NUCLEO SECUNDARIO
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" colspan=\"1\" width=\"20%\">NUCLEO SECUNDARIO</td>";
                if ($_REQUEST['sw_nucleo_secundario'.$pfj] == '1')
                {
                    $this->salida.="<td colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_nucleo_secundario$pfj' checked value = '1'>ESPOSA</td>";
                }
                else
                {
                    $this->salida.="<td colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_nucleo_secundario$pfj' value = '1'>ESPOSA</td>";
                }

                if ($_REQUEST['sw_nucleo_secundario'.$pfj] == '2')
                {
                    $this->salida.="<td  colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_nucleo_secundario$pfj' checked value = '2'>HIJOS</td>";
                }
                else
                {
                    $this->salida.="<td  colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_nucleo_secundario$pfj' value = '2'>HIJOS</td>";
                }
                $this->salida.="<td colspan=\"1\" align=\"left\" width=\"15%\">RELACIONES</td>";
                $this->salida.="<td colspan=\"1\" width=\"25%\"align=\"left\" >";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['sw_relaciones'.$pfj] == '1')
                {
                    $this->salida.="<td width=\"100%\" align = left ><input type = radio name= 'sw_relaciones$pfj' checked value = '1'>ARMONICAS</td>";
                }
                else
                {
                    $this->salida.="<td width=\"100%\" align = left ><input type = radio name= 'sw_relaciones$pfj' value = '1'>ARMONICAS</td>";
                }
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['sw_relaciones'.$pfj] == '2')
                {
                    $this->salida.="<td width=\"100%\" align = left ><input type = radio name= 'sw_relaciones$pfj' checked value = '2'>MALTRATO INFANTIL</td>";
                }
                else
                {
                    $this->salida.="<td width=\"100%\" align = left ><input type = radio name= 'sw_relaciones$pfj' value = '2'>MALTRATO INFANTIL</td>";
                }
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"$estilo\">";
                if ($_REQUEST['sw_relaciones'.$pfj] == '3')
                {
                    $this->salida.="<td width=\"100%\" align = left ><input type = radio name= 'sw_relaciones$pfj' checked value = '3'>VIOLENCIA INTRAFAMILIAR</td>";
                }
                else
                {
                    $this->salida.="<td width=\"100%\" align = left ><input type = radio name= 'sw_relaciones$pfj' value = '3'>VIOLENCIA INTRAFAMILIAR</td>";
                }
                $this->salida.="</tr>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

                $this->salida.="</table><br>";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
                $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR ANTECEDENTES DE INFANCIA Y NIï¿½Z\"></td>";
                $this->salida .= "</form>";
                $this->salida.="</tr></table>";
                //$this->salida .= ThemeCerrarTablaSubModulo();
		}

		function Frmforma_climaterio_senilidad()
		{
                $pfj=$this->frmPrefijo;
                //$this->salida= ThemeAbrirTablaSubModulo('ANTECEDENTES PERSONALES DE INFANCIA Y NIï¿½Z');
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_infancia_niez'));
                $this->salida .= "<form name=\"formajus$pfj\" action=\"$accion\" method=\"post\">";
		
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida.="</table>";

                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align=\"center\" colspan=\"5\">CLIMATERIO Y SENILIDAD</td>";
                $this->salida.="</tr>";

                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}

                //HISTORIA OCUPACIONAL
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"1\"  width=\"20%\"align=\"left\" >OCUPACIONES Y DISTRACCIONES</td>";
                if (($_REQUEST['ocupaciones_distracciones'.$pfj])  == '')
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'ocupaciones_distracciones$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
                  else
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'ocupaciones_distracciones$pfj' cols = 60 rows = 5>".$_REQUEST['ocupaciones_distracciones'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";

                //HABITOS FRENTE AL DINERO
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >MANIFESTACIONES AFECTIVAS</td>";
                if (($_REQUEST['manifestaciones_afectivas'.$pfj])  == '')
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'manifestaciones_afectivas$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
                  else
                {
                    $this->salida.="<td colspan=\"4\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'manifestaciones_afectivas$pfj' cols = 60 rows = 5>".$_REQUEST['manifestaciones_afectivas'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";

                //MENOPAUSIA
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td colspan=\"1\" align=\"left\" width=\"20%\">MENOPAUSIA</td>";

                if ($_REQUEST['sw_menopausia'.$pfj] == '1')
                {
                    $this->salida.="<td colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_menopausia$pfj' checked value = '1'>NORMAL</td>";
                }
                else
                {
                    $this->salida.="<td colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_menopausia$pfj' value = '1'>NORMAL</td>";
                }

                if ($_REQUEST['sw_menopausia'.$pfj] == '2')
                {
                    $this->salida.="<td colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_menopausia$pfj' checked value = '2'>ANORMAL</td>";
                }
                else
                {
                    $this->salida.="<td colspan=\"1\" width=\"10%\" align = left ><input type = radio name= 'sw_menopausia$pfj' value = '2'>ANORMAL</td>";
                }

                if (($_REQUEST['menopausia'.$pfj])  == '')
                {
                    $this->salida.="<td colspan=\"2\" width=\"40%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'menopausia$pfj' cols = 60 rows = 5></textarea></td>" ;
                }
                else
                {
                    $this->salida.="<td colspan=\"2\" width=\"40%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'menopausia$pfj' cols = 60 rows = 5>".$_REQUEST['menopausia'.$pfj]."</textarea></td>" ;
                }
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

                $this->salida.="</table><br>";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
                $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR ANTECEDENTES DE INFANCIA Y NIï¿½Z\"></td>";
                $this->salida .= "</form>";
                $this->salida.="</tr></table>";
                //$this->salida .= ThemeCerrarTablaSubModulo();
		}
		/*************************************************************************************************
		*
		**************************************************************************************************/
    function frmForma()
    {
			//$this->datosAdicionales['sw_siquiatria'] = 1;
			SessionSetVar("SwSiquiatria",$this->datosAdicionales['sw_siquiatria']);
			SessionSetVar("EvolucionHc",$this->evolucion);
			SessionSetVar("IngresoHc",$this->ingreso);
			SessionSetVar("RutaImg",GetThemePath());
			SessionSetVar("IdPaciente",$this->paciente);
			SessionSetVar("TipoPaciente",$this->tipoidpaciente);
			
			$pfj = $this->frmPrefijo;
			$titulo1 = $this->titulo;
			//if(empty($this->titulo)) 
      $titulo1 = "ANTECEDENTES PERSONALES";
			
			$Antecedentes = $this->BusquedaAntecedentesTotal();
			$accion = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
			
			$estilos = "style=\"border-bottom-width:0px;border-left-width:2px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			
			$this->salida = ThemeAbrirTablaSubModulo($titulo1);
			$this->salida .= "	<table width=\"100%\">\n";
			$this->salida .= "		<tr><td>\n";
			$this->salida .= "			<table width=\"30%\" align=\"right\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"label_mark\" >MOSTRAR: </td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" >\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('T');MostrarVisibles();MostrarOcultos();\">TODOS</a>\n";			
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" $estilos>\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('V');MostrarVisibles();EsconderOcultos();\">VISIBLES</a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" $estilos>\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('O');MostrarOcultos();EsconderVisibles();\">OCULTOS</a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "		<tr><td>\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td align=\"center\" width=\"44%\" colspan=\"2\">ANTECEDENTES</td>\n";
			$this->salida .= "					<td align=\"center\" ></td>\n";
			$this->salida .= "				</tr>\n";
			
			$i = 0;
			$b1 = true;
			$b2 = true;
			$ocultos = "var Aocultos = new Array(";
			$visibles = "var Avisibles = new Array(";
			
			foreach($Antecedentes as $key => $nivel1)
			{
				$j=0;
				$columna = "";					
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro'; $background = "#DDDDDD";
				}
				
				foreach($nivel1 as $key1 => $nivel2)
				{
					if($j % 2 == 0)	
					{	
						$est = 'hc_submodulo_list_oscuro'; $estX = 'hc_submodulo_list_claro'; 
					}
					else 
					{
						$est = 'hc_submodulo_list_claro'; $estX = 'hc_submodulo_list_oscuro';
					}
					
					$k = 0;
					$x = 0;
					$tabla = "";
					$tablaO = "";
					foreach($nivel2 as $key2 => $nivel3)
					{
						$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
						
						if($nivel3['sw_riesgo'] == '0') $op = "NO";
						else if($nivel3['sw_riesgo'] == '1') $op = "SI";
						
						$arregloJs = "new Array('".$nivel3['hctap']."','".$nivel3['hctad']."','$est','$estX','".$i.$j."','".$nivel3['hcid']."'";
						
						$check  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'));OcultarAntecedente(".$arregloJs.",'1'))\" title=\"Ocultar Antecedente\">";
						$check .= "	<img src=\"".GetThemePath()."/images/checkno.png\" height=\"14\" border=\"0\"></a>";
						
						$check1  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'));OcultarAntecedente(".$arregloJs.",'0'))\" title=\"Mostrar Antecedente\">";
						$check1 .= "	<img src=\"".GetThemePath()."/images/checkS.gif\" height=\"14\" width=\"14\" border=\"0\"></a>";
						
						if(!$nivel3['detalle'])
						{
							$k = 0;
							$op = "&nbsp;";
							$check = "&nbsp;";
							$check1 = "&nbsp;";
						}
						if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold;text-transform:capitalize;\" ";
						
						$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
						if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
												
						if($nivel3['ocultar'] == '0')
						{
							if($nivel3['detalle'] != "")
								$k = 1;
							
							$tabla .= "						<tr class=\"$est\">\n";
							$tabla .= "							<td align=\"center\" $styl1 width=\"15%\" >$op</td>\n";
							$tabla .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
							$tabla .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
							$tabla .= "							<td align=\"center\" $styl width=\"10%\">$check</td>\n";
							$tabla .= "						</tr>\n";
						}
						else if($nivel3['ocultar'] == '1')
						{
							$x = 1;
							$tablaO .= "						<tr class=\"$estX\">\n";
							$tablaO .= "							<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
							$tablaO .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
							$tablaO .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
							$tablaO .= "							<td align=\"center\" $styl width=\"10%\">$check1</td>\n";
							$tablaO .= "						</tr>\n";
						}
					}
					
					$arregloJs = "new Array('".$nivel2[$key2]['hctap']."','".$nivel2[$key2]['hctad']."','$est','$estX','".$i.$j."')";
					
					$columna .= "					<tr>\n";
					$columna .= "						<td class=\"$est\">\n";
					$columna .= "							<a href=\"javascript:MostrarSpan('d2Container');Iniciar('".$nivel2[$key2]['nombre_tipo']."',new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'),$arregloJs)\" class=\"label\">".$nivel2[$key2]['nombre_tipo']."</a>\n";
					$columna .= "						</td>\n";
					$columna .= "						<td height=\"17\" class=\"$est\">\n";
					$clase = "";
					
					$columna1 = "";
					if($k == 1 || $x == 1)
					{
						$columna1 .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
						$columna1 .= "									<tr class=\"formulacion_table_list\" >\n";
						$columna1 .= "										<td align=\"center\" width=\"15%\" >OP.</td>\n";
						$columna1 .= "										<td align=\"center\" width=\"55%\" >DETALLE</td>\n";
						$columna1 .= "										<td align=\"center\" width=\"20%\" >F. REGIS</td>\n";				
						$columna1 .= "										<td align=\"center\" width=\"10%\" >OCUL</td>\n";
						$columna1 .= "									</tr>\n";
						$columna1 .= "								</table>\n";
						
						$clase = " class=\"modulo_table_list\" bgcolor=\"#FFFFFF\"";
					}
					
					$display = "style=\"display:block\"";
					if($k==0 && $x==1) $display = "style=\"display:none\"";
					
					$columna .= "							<div id=\"XAntecedente".$i.$j."\" $display >$columna1</div>\n";
					$columna .= "								<div id=\"Antecedente".$i.$j."\" style=\"display:block\">\n";
					if($tabla != "")
					{
						$columna .= "									<table width=\"100%\" $clase>\n";
						$columna .= "										$tabla\n";
						$columna .= "									</table>\n";
					}
					$columna .= "								</div>\n";
					$columna .= "								<div id=\"Ocultos".$i.$j."\" style=\"display:none\">\n";	
					if($tablaO != "")
					{
						$columna .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
						$columna .= "									$tablaO\n";
						$columna .= "								</table>\n";
					}
					$columna .= "							</div>\n";
					$columna .= "						</td>\n";
					$columna .= "					</tr>\n";
					
					$b1? $visibles .= "'Antecedente".$i.$j."'": $visibles .= ",'Antecedente".$i.$j."'";
					$b1? $ocultos .= "'Ocultos".$i.$j."'": $ocultos .= ",'Ocultos".$i.$j."'";
					$b1 = false;
					
					$j++;
				}
				$this->salida .= "		<tr >\n";
				$this->salida .= "			<td rowspan=\"".($j+1)."\" class=\"$estilo\" ><label class=\"label\" width=\"15%\">".$key."</label></td>\n";					
				$this->salida .= "		</tr>\n";
				$this->salida .= "		".$columna;
				$i++;
			}
						
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			
			$this->salida .= "		<tr><td><br>\n";
			$this->DatosConsumo();
			$this->salida .= "		</td></tr>\n";
			
			$this->salida .= "		<tr><td><br>\n";
			$this->ObtenerInstituciones();
			$this->salida .= "		</td></tr>\n";
			
			$this->salida .= "	</table>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	".$ocultos.");\n";
			$this->salida .= "	".$visibles.");\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	var mensaje = '';\n";
			$this->salida .= "	var opcion = 'V';\n";
			$this->salida .= "	var contenedor = 'd2Container';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var datosE = new Array();\n";
			$this->salida .= "	var capaActual = new Array();\n";
			$this->salida .= "	function Iniciar(tit,capita,envios)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datosE = envios;\n";
			$this->salida .= "		capaActual = capita;\n";
			$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
			$this->salida .= "		document.oculta.resaltar.checked = false;\n";
			$this->salida .= "		document.oculta.observacion.value = '';\n";
			$this->salida .= "		document.oculta.decision[0].checked = false;\n";
			$this->salida .= "		document.oculta.decision[1].checked = false;\n";
			$this->salida .= "	 	contenedor = 'd2Container';\n";
			$this->salida .= "		titulo = 'titulo';\n";
			$this->salida .= "		ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
			$this->salida .= "		ele = xGetElementById('titulo');\n";
			$this->salida .= "	  xResizeTo(ele,280, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 280, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IniciarSustancias(encabeza)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'ContenedorS';\n";
			$this->salida .= "		titulo = 'tituloS';\n";
			$this->salida .= "		document.getElementById('tituloS').innerHTML = '<center>'+encabeza+'</center>';\n";
			$this->salida .= "		ele = xGetElementById('ContenedorS');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+xHeight(ele));\n";
			$this->salida .= "		ele = xGetElementById('tituloS');\n";
			$this->salida .= "	  xResizeTo(ele,280, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarS');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 280, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  window.status = '';\n";
			$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
			$this->salida .= "	  ele.myTotalMX = 0;\n";
			$this->salida .= "	  ele.myTotalMY = 0;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  if (ele.id == titulo) {\n";
			$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	  else {\n";
			$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$this->salida .= "	  }  \n";
			$this->salida .= "	  ele.myTotalMX += mdx;\n";
			$this->salida .= "	  ele.myTotalMY += mdy;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Cerrar(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"none\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CrearArregloCapas(capita)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		capaActual = capita;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function EvaluarDatos(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		var vsino = ''; \n";
			$this->salida .= "		var vresaltar = '0';\n";
			$this->salida .= "		var vobservacion = objeto.observacion.value;\n";
			$this->salida .= "		if(objeto.decision[0].checked) \n";
			$this->salida .= "			vsino = objeto.decision[0].value;\n";
			$this->salida .= "			else if(objeto.decision[1].checked)\n";
			$this->salida .= "				vsino = objeto.decision[1].value;\n";
			$this->salida .= "		if(objeto.resaltar.checked) vresaltar = objeto.resaltar.value;\n";
			$this->salida .= "		if(vsino == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE ESCOGER SI, EL PACIENTE PRESENTA O NO EL ANTECENTE';\n";
			$this->salida .= "		else if(vobservacion == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR EL DETALLE DE LA PATOLOGIA';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje == '')\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			datosE[5] = vsino;\n";
			$this->salida .= "			datosE[6] = vobservacion;\n";
			$this->salida .= "			datosE[7] = vresaltar;\n";
			$this->salida .= "			CrearAntecedentes(datosE);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ActualizarAntecedentes(html)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		Cerrar('d2Container');\n";
			$this->salida .= "		resultado  = jsrsArrayFromString( html, 'ç' );";
			$this->salida .= "		document.getElementById(capaActual[0]).innerHTML = resultado[0];\n";
			$this->salida .= "		document.getElementById(capaActual[1]).innerHTML = resultado[1];\n";
			$this->salida .= "		document.getElementById('X'+capaActual[0]).innerHTML = resultado[2];\n";
			$this->salida .= "		if(opcion == 'V' && resultado[0] == \"\") Cerrar('X'+capaActual[0]);\n";
			$this->salida .= "		if(opcion == 'O' && resultado[1] == \"\") Cerrar('X'+capaActual[0]);\n";
			$this->salida .= "	}\n";
			$this->salida .= "		function MostrarOcultos()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Aocultos.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Aocultos[i]);\n";
			$this->salida .= "				e.style.display = \"block\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EsconderOcultos()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Aocultos.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Aocultos[i]);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarVisibles()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Avisibles.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Avisibles[i]);\n";
			$this->salida .= "				e.style.display = \"block\";\n";
			$this->salida .= "				try\n";
			$this->salida .= "				{\n";
			$this->salida .= "					html = document.getElementById(Avisibles[i]).innerHTML;\n";
			$this->salida .= "					if( html.substring(10,11) != \"\" && opcion == 'V')\n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"block\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else if(opcion == 'T')\n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"block\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else \n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"none\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				catch(error){}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EsconderVisibles()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Avisibles.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Avisibles[i]);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "				html = document.getElementById(Aocultos[i]).innerHTML;";
			$this->salida .= "				if( html.substring(9,10) == \"\")\n";
			$this->salida .= "				{\n";
			$this->salida .= "					f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "					f.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "					f.style.display = \"block\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ActualizarOpcion(op)\n";
			$this->salida .= "		{opcion = op;}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";	
			$this->salida .= "</script>";
			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='d2Contents'>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td  >PRESENCIA DEL ANTECEDENTE</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"1\" >SI\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"0\" >NO\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"3\">DETALLE</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\">\n";
			$this->salida .= "						<textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\">\n";
			$this->salida .= "						<input type=\"checkbox\" name=\"resaltar\" class=\"input-text\" value=\"1\"><b>RESALTAR</b>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.oculta)\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= "<div id='ContenedorS' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloS' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrarS' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorS')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='errorS' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='ContentS'></div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function DatosConsumo()
		{
			$datosbusqueda = $this->BusquedaTotalToxicos();
			
			if($this->datosAdicionales['sw_siquiatria'] == 1)
			{
				$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>SUSTANCIAS ADICTIVAS</td>\n";
				$this->salida .= "			<td>PATRON DE CONSUMO</td>\n";
				$this->salida .= "			<td>ULTIMO CONSUMO</td>\n";
				$this->salida .= "			<td>PROBLEMAS POR CONSUMO</td>\n";
				$this->salida .= "			<td>EDAD DE INICIO</td>\n";
				$this->salida .= "			<td>TIEMPO DE CONSUMO</td>\n";
				$this->salida .= "		</tr>\n";
					
				$j=0;
				foreach($datosbusqueda as $key => $nivel1)
				{
	        if($j % 2 == 0)	
						$est = 'hc_submodulo_list_oscuro'; 
					else 
						$est = 'hc_submodulo_list_claro';
						
					$j++;             
					
					$this->salida .= "		<tr class=\"$est\" id=\"Adictiva".$nivel1['hc_tipos_sustancias_adictivas_id']."\">\n";
					$this->salida .= "			<td >\n";
					$this->salida .= "				<a href=\"javascript:CrearIngresoDatos(new Array('".$nivel1['hc_tipos_sustancias_adictivas_id']."','".$nivel1['tipo_patronconsumo']."'),'$key');\" class=\"label\">".$key."</a>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['patron']."</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['ultimo_consumo']."</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['problemas']."</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['edad_inicio']."</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['tiempo_consumo']." ".$nivel1['tiempo_consumo_tipo']."</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			else if(sizeof($datosbusqueda) > 0)
			{
				$j=0;
				$html = "";
				foreach($datosbusqueda as $key => $nivel1)
				{
	        if($j % 2 == 0)	
						$est = 'hc_submodulo_list_oscuro'; 
					else 
						$est = 'hc_submodulo_list_claro';
						
					if($nivel1['patron'] && $nivel1['ultimo_consumo'] && $nivel1['problemas'] && $nivel1['edad_inicio'] && $nivel1['tiempo_consumo'])
					{
						$j++;             
						$html .= "		<tr class=\"$est\" id=\"Adictiva".$nivel1['hc_tipos_sustancias_adictivas_id']."\">\n";
						$html .= "			<td >\n";
						$html .= "				<b class=\"label_mark\">".$key."</b>\n";
						$html .= "			</td>\n";
						$html .= "			<td class=\"label\">".$nivel1['patron']."</td>\n";
						$html .= "			<td class=\"label\">".$nivel1['ultimo_consumo']."</td>\n";
						$html .= "			<td class=\"label\">".$nivel1['problemas']."</td>\n";
						$html .= "			<td class=\"label\">".$nivel1['edad_inicio']."</td>\n";
						$html .= "			<td class=\"label\">".$nivel1['tiempo_consumo']." ".$nivel1['tiempo_consumo_tipo']."</td>\n";
						$html .= "		</tr>\n";
					}
				}
				if($html != "")
				{
					$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td>SUSTANCIAS ADICTIVAS</td>\n";
					$this->salida .= "			<td>PATRON DE CONSUMO</td>\n";
					$this->salida .= "			<td>ULTIMO CONSUMO</td>\n";
					$this->salida .= "			<td>PROBLEMAS POR CONSUMO</td>\n";
					$this->salida .= "			<td>EDAD DE INICIO</td>\n";
					$this->salida .= "			<td>TIEMPO DE CONSUMO</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		".$html;
					$this->salida .= "	</table>\n";
				}
			}
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function ObtenerInstituciones()
		{
			$instituciones = $this->BusquedaInstituciones();
			
			$this->salida .= "<div id=\"Instituciones\">\n";
			if($this->datosAdicionales['sw_siquiatria']==1)
			{
				$this->salida .= "<center>\n";
				$this->salida .= "	<a href=\"javascript:IngresarInstitucion()\" class=\"label\">ADICIONAR PROGRAMAS DE REHABILITACION</a>\n";
				$this->salida .= "</center>\n";
			}
			if(!empty($instituciones))
			{

				$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"formulacion_table_list\">\n";
				$this->salida .= "			<td align=\"center\" colspan=\"4\">PROGRAMAS DE REHABILITACION</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"1%\"></td>\n";
				$this->salida .= "			<td align=\"center\">INSTITUCIÓN</td>\n";
				$this->salida .= "			<td align=\"center\">ESTANCIA</td>\n";
				
				if($this->datosAdicionales['sw_siquiatria']==1)
					$this->salida .= "			<td align=\"center\" width=\"10%\">OPCIÓN</td>\n";
				
				$this->salida .= "		</tr>\n";

				$j=0;
				foreach($instituciones as $k=>$nivel1)
				{
	        if($j % 2 == 0)	
						$est = 'hc_submodulo_list_oscuro'; 
					else 
						$est = 'hc_submodulo_list_claro';
						
					$j++; 
					$estancia = "Años";
					if($nivel1['tipo_estancia_institucion']=='M')
						$estancia = " Meses";
					else if($nivel1['tipo_estancia_institucion']=='D')
						$estancia = " Dias";
					
					$this->salida .= "		<tr class=\"$est\">\n";
					$this->salida .= "			<td class=\"label\">$j</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['nombre_institucion']."</td>\n";
					$this->salida .= "			<td class=\"label\">".$nivel1['estancia_institucion']."$estancia</td>\n";
					
					if($this->datosAdicionales['sw_siquiatria']==1)
					{
						$id = $nivel1['hc_antecedente_personal_institucion_id'];
					
						$this->salida .= "			<td align=\"center\" >\n";
						$this->salida .= "				<a href=\"javascript:EliminarInstitucion('$id','".strtoupper($nivel1['nombre_institucion'])."','$j')\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/elimina.png\"  border='0'>\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
					}
					
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			$this->salida .= "</div>\n";
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function frmHistoria()
		{
			$Antecedentes = $this->BusquedaAntecedentesTotal2();
			$flag = true; 
			$html .= "	<table width=\"100%\" border=\"1\" rules=\"none\">\n";
			if(!empty($Antecedentes))
			{
				$flag = false;
				$html .= "		<tr><td class=\"normal_11_menu\" align=\"center\"><b>ANTECEDENTES PERSONALES</b></td></tr>\n";
				$html .= "		<tr><td>\n";
				$html .= "			<table width=\"100%\" border=\"1\" align=\"center\" class=\"normal_10\">\n";
				$html .= "				<tr class=\"normal_11_menu\">\n";
				$html .= "					<td align=\"center\" width=\"44%\" colspan=\"2\">ANTECEDENTES</td>\n";
				$html .= "					<td align=\"center\" width=\"%\">\n";
				$html .= "						<table width=\"100%\" border=\"0\" class=\"normal_11_menu\">\n";
				$html .= "							<tr class=\"normal_11_menu\">\n";
				$html .= "								<td align=\"center\" width=\"14%\" >OP</td>\n";
				$html .= "								<td align=\"center\"  width=\"%\" >DETALLE</td>\n";
				$html .= "							</tr>\n";
				$html .= "						</table>\n";
				$html .= "					</td>\n";
				$html .= "				</tr>\n";
				
				$i = 0;
				
				foreach($Antecedentes as $key => $nivel1)
				{
					$j=0;
					$columna = "";					
					
					foreach($nivel1 as $key1 => $nivel2)
					{			
						$k = 0;
						$x = 0;
						$tablaX = "";
						$tablaY = "";
						$tablaO = "";
						foreach($nivel2 as $key2 => $nivel3)
						{
							$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
							
							if($nivel3['sw_riesgo'] == '0') $op = "NO";
							else if($nivel3['sw_riesgo'] == '1') $op = "SI";
													
							if(!$nivel3['detalle'])
							{
								$k = 0;
								$op = "&nbsp;";
								$check = "&nbsp;";
								$check1 = "&nbsp;";
							}
							if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold;text-transform:capitalize;\" ";
							
							$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
							if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
													
							if($nivel3['detalle'] != "")
								$k = 1;
								
							$tablaX .= "						<tr class=\"label\">\n";
							$tablaX .= "							<td align=\"center\" $styl1 width=\"14%\" >$op</td>\n";
							$tablaX .= "							<td align=\"justify\" $styl width=\"%\" >".$nivel3['detalle']."</td>\n";
							$tablaX .= "						</tr>\n";
						}
							
						$columna .= "					<tr>\n";
						$columna .= "						<td class=\"label\">\n";
						$columna .= "							".$nivel2[$key2]['nombre_tipo']."\n";
						$columna .= "						</td>\n";
						$columna .= "						<td>\n";
						$columna .= "							<table width=\"100%\" border=\"1\">\n";
						$columna .= "								$tablaX\n";
						$columna .= "							</table>\n";
						$columna .= "						</td>\n";
						$columna .= "					</tr>\n";
						
						$j++;
					}
					$html .= "		<tr >\n";
					$html .= "			<td rowspan=\"".($j+1)."\"><label class=\"label\" width=\"15%\">".$key."</label></td>\n";					
					$html .= "		</tr>\n";
					$html .= "		".$columna;
					$i++;
				}
							
				$html .= "			</table>\n";
				$html .= "		</td></tr>\n";
			}
			$html1 = "";
			$html2 = "";
			$datosbusqueda = $this->BusquedaTotalToxicos();
			if(sizeof($datosbusqueda) > 0)
			{
				foreach($datosbusqueda as $key => $nivel1)
				{				
					if($nivel1['patron'] && $nivel1['ultimo_consumo'] && $nivel1['problemas'] && $nivel1['edad_inicio'] && $nivel1['tiempo_consumo'])
					{          
						$html1 .= "		<tr>\n";
						$html1 .= "			<td >\n";
						$html1 .= "				<b class=\"label\">".$key."</b>\n";
						$html1 .= "			</td>\n";
						$html1 .= "			<td class=\"label\">".$nivel1['patron']."</td>\n";
						$html1 .= "			<td class=\"label\">".$nivel1['ultimo_consumo']."</td>\n";
						$html1 .= "			<td class=\"label\">".$nivel1['problemas']."</td>\n";
						$html1 .= "			<td class=\"label\">".$nivel1['edad_inicio']."</td>\n";
						$html1 .= "			<td class=\"label\">".$nivel1['tiempo_consumo']." ".$nivel1['tiempo_consumo_tipo']."</td>\n";
						$html1 .= "		</tr>\n";
					}
				}
				if($html1 != "")
				{
					$html2 .= "	<table width=\"100%\" align=\"center\" border=\"1\">\n";
					$html2 .= "		<tr class=\"normal_11_menu\">\n";
					$html2 .= "			<td>SUSTANCIAS ADICTIVAS</td>\n";
					$html2 .= "			<td>PATRON DE CONSUMO</td>\n";
					$html2 .= "			<td>ULTIMO CONSUMO</td>\n";
					$html2 .= "			<td>PROBLEMAS POR CONSUMO</td>\n";
					$html2 .= "			<td>EDAD DE INICIO</td>\n";
					$html2 .= "			<td>TIEMPO DE CONSUMO</td>\n";
					$html2 .= "		</tr>\n";
					$html2 .= "		".$html1;
					$html2 .= "	</table>\n";
				}
			}
			if($html2 != "")
			{
				$flag = false;
				$html .= "		<tr><td><br>\n";
				$html .= "		".$html2;
				$html .= "		</td></tr>\n";
			}
			
			$instituciones = $this->BusquedaInstituciones();
			if(!empty($instituciones))
			{
				$flag = false;
				$html .= "<tr><td><br>\n";
				$html .= "	<table width=\"100%\" border=\"1\" align=\"center\" class=\"normal_10N\">\n";
				$html .= "		<tr class=\"normal_11_menu\">\n";
				$html .= "			<td align=\"center\" colspan=\"4\">PROGRAMAS DE REHABILITACION</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"normal_11_menu\">\n";
				$html .= "			<td align=\"center\">INSTITUCIÓN</td>\n";
				$html .= "			<td align=\"center\">ESTANCIA</td>\n";
				$html .= "		</tr>\n";

				$j=0;
				foreach($instituciones as $k=>$nivel1)
				{
					$estancia = "Años";
					if($nivel1['tipo_estancia_institucion']=='M')
						$estancia = " Meses";
					else if($nivel1['tipo_estancia_institucion']=='D')
						$estancia = " Dias";
					
					$html .= "		<tr>\n";
					$html .= "			<td class=\"label\">".$nivel1['nombre_institucion']."</td>\n";
					$html .= "			<td class=\"label\">".$nivel1['estancia_institucion']."$estancia</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
				$html .= "</td></tr>\n";
			}
			
			$html .= "	</table>\n";
			
			if($flag) return "";
			
			return $html;
		}
	}
?>
