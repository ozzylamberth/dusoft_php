<?php
/**
* Submodulo para la Solicitud de Apoyos Diagnosticos.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Apoyos_Diagnosticos_Solicitud_HTML.php,v 1.17 2007/06/21 15:30:49 tizziano Exp $
*/

class Apoyos_Diagnosticos_Solicitud_HTML extends Apoyos_Diagnosticos_Solicitud
{
  //cor - clzc - ads
  function Apoyos_Diagnosticos_Solicitud_HTML()
  {
   $this->Apoyos_Diagnosticos_Solicitud();//constructor del padre
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
    'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }
///////////////////////////////////
  
  
  //cor - clzc -ads
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

	//cor - jea - ads
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//cor - jea - ads
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//cor - jea - ads
	function RetornarBarraExamenes_Avanzada()//Barra paginadora de los planes clientes
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));

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

//cor - jea - ads
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'hc_os_solicitud_id'.$pfj=>$_REQUEST['hc_os_solicitud_id'.$pfj],
			 'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
			 'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
			 'observacion'.$pfj=>$_REQUEST['observacion'.$pfj]));

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

     //cor - clzc - ads
     function frmForma()
     {
          $pfj=$this->frmPrefijo;
          if(empty($this->titulo))
          {
               $this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE APOYOS DIAGNOSTICOS');
          }
          else
          {
               $this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
          }

          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $vector1=$this->Consulta_Solicitud_Apoyod();
          if($vector1)
          {
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";

               $this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"6\">APOYOS DIAGNOSTICOS SOLICITADOS</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"7%\">TIPO</td>";
               $this->salida.="  <td width=\"9%\">CARGO</td>";
               $this->salida.="  <td colspan= 2 width=\"51%\">DESCRIPCION</td>";
							$this->salida.="  <td colspan= 2 width=\"13%\">OPCION</td>";
							$this->salida.="</tr>";

               for($i=0;$i<sizeof($vector1);$i++)
               {
                    $hc_os_solicitud_id =$vector1[$i][hc_os_solicitud_id];
                    $tipo=$vector1[$i][tipo];
                    $cargo=$vector1[$i][cargo];
                    $descripcion= $vector1[$i][descripcion];
                    $observacion= $vector1[$i][observacion];
                    $usuario_solicitud = $vector1[$i][usuario_solicitud];

                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
										$row=4;
                    if(!empty($this->parametro) AND !empty($vector1[$i][usuario_solicitud]))
                    {  
                    	$row=5;
                         $profesionales = $this->ReconocerProfesional($usuario_solicitud);
                         $nombre = $profesionales[0][nombre];
                         $usuario_solicitud = $profesionales[0][usuario_id];
                    }
                    $this->salida.="  <td ROWSPAN = $row align=\"center\" width=\"7%\">$tipo</td>";
                    $this->salida.="  <td align=\"center\" width=\"9%\">$cargo</td>";
                    $this->salida.="  <td colspan= 2 align=\"left\" width=\"52%\">$descripcion</td>";
                    //condicion que controla que se eliminan o modiquen solo las solicitudes de esa evolucion
                    if($vector1[$i][evolucion_id] == $this->evolucion)
                    {
                              $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'observacion','hc_os_solicitud_id'.$pfj => $hc_os_solicitud_id, 'cargo'.$pfj =>$cargo, 'descripcion'.$pfj => $descripcion, 'observacion'.$pfj => $observacion,'ambulatorio'.$pfj=>$vector1[$i][sw_ambulatorio], 'ordeno'.$pfj=>$usuario_solicitud));
                              $this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion1'><img title=\"Modificar\" src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";

                              $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar', 'hc_os_solicitud_id'.$pfj => $hc_os_solicitud_id));
                              $this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                    }
                    else
                    {
                              $this->salida.="  <td colspan=\"2\" align=\"center\" width=\"12%\">&nbsp;</td>";
                    }
                    $this->salida.="</tr>";
               	$this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
                    $this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">$observacion</td>";
                    $this->salida.="</tr>";
                    
                    //cambio dar										
                    /*if($vector1[$i][sw_ambulatorio]==1)
                    {
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="  <td colspan =5 align=\"CENTER\"  class=\"label_mark\">SOLICITUD AMBULATORIA</td>";
                              $this->salida.="</tr>";										
                    }*/
                    //fin cambio dar
                	
				if(!empty($this->parametro) AND !empty($vector1[$i][usuario_solicitud]))                    
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Orden Profesional</td>";
                         $this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$nombre."</td>";
                         $this->salida.="</tr>";
                    }
                    
                    $diag =$this->Diagnosticos_Solicitados($vector1[$i][hc_os_solicitud_id]);
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos Presuntivos</td>";
                    $this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">";
                    $this->salida.="<table width=\"100%\">";
                    if(!empty($diag))
                    {
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="<td width=\"10%\">PRIMARIO</td>";
	                    $this->salida.="<td width=\"10%\">TIPO DX</td>";
                         $this->salida.="<td width=\"10%\">CODIGO</td>";
                         $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
                         $this->salida.="</tr>";
                    }

                    for($j=0;$j<sizeof($diag);$j++)
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         
                         if($diag[$j]['sw_principal']==1)
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
                         }
                         else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
                         }
                         
                         if($diag[$j][tipo_diagnostico] == '1')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                         }elseif($diag[$j][tipo_diagnostico] == '2')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                         }else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                         }
                         $this->salida.="<td align=\"center\" width=\"10%\">".$diag[$j][diagnostico_id]."</td>";
                         $this->salida.="<td align=\"justify\" width=\"60%\">".$diag[$j][diagnostico_nombre]."</td>";
                         $this->salida.="</tr>";
                    }
                    if(!empty($diag))
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"> (ID) - IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;(CN) - CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;(CR) - CONFIRMADO REPETIDO</td>";
                         $this->salida.="</tr>";
				}
                    
                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";                    
                    
                    $this->salida.="<tr>";
										$col=4;
										if($this->servicio!=3)
										{  $col=2; }
                    if(!empty($vector1[$i][informacion_cargo])){
										$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\"  class=\"modulo_table_title\">INFORMACION</td>";
										if($vector1[$i][informacion_cargo]=='SIN COBERTURA.')
										{
												$this->salida.="  <td colspan = $col align=\"left\" width=\"64%\" class=\"modulo_table_title\">".$vector1[$i][informacion_cargo]."</td>";
										}
										else
										{
												$this->salida.="  <td colspan = $col align=\"left\" width=\"64%\" class=\"modulo_table_title\">".$vector1[$i][informacion_cargo]."</td>";
										}
										
										//cambio dar										
										if($this->servicio!=3)
										{
												if($vector1[$i][sw_ambulatorio]==1)
												{		
														$accionN=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'noAmbulatorio','hc_os_solicitud_id'.$pfj => $hc_os_solicitud_id));
														$this->salida.="  <td colspan=\"2\" class=\"$estilo\" align=\"left\"><a href=\"$accionN\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0' title=\"Poner Solicitud No Ambulatoria\">&nbsp;&nbsp;Ambulatoria</a></td>"; 		
												}
												else
												{		
														$accionA=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ambulatorio','hc_os_solicitud_id'.$pfj => $hc_os_solicitud_id));
														$this->salida.="  <td colspan=\"2\" class=\"$estilo\" align=\"left\"><a href=\"$accionA\"><img src=\"".GetThemePath()."/images/checkno.png\" border='0' title=\"Poner Solicitud Ambulatoria\">&nbsp;&nbsp;Ambulatoria</a></td>"; 		
												}
										}
										//fin cambio dar										
									}	
									$this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
          }
          $this->salida .= "</form>";
        	//este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
          //if ($_SESSION['PROFESIONAL'.$pfj]==1)
          if ($this->PermitirNoProfesionales=='1')
          {
               if(!empty($this->plan_id))
               {
                    //lo que inserte
                    $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
                    'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
                    'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
                    'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
                    'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));

                    $this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE APOYOS DIAGNOSTICOS - BUSQUEDA AVANZADA </td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td width=\"5%\">TIPO</td>";

                    $this->salida.="<td width=\"10%\" align = left >";
                    $this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
                    $this->salida.="<option value = '001' selected>Todos</option>";
                    if (($_REQUEST['criterio1'.$pfj])  == '002')
                    {
                         $this->salida.="<option value = '002' selected>Frecuentes</option>";
                    }
                    else
                    {
                         $this->salida.="<option value = '002' >Frecuentes</option>";
                    }

//             //claudia entrega feb01/2005 - lo nuevo de apoyos
// 						if (($_REQUEST['criterio1'.$pfj])  == '003')
// 						{
// 							$this->salida.="<option value = '003' selected>Perfiles</option>";
// 						}
// 					else
// 						{
// 							$this->salida.="<option value = '003' >Perfiles</option>";
// 						}
// 						//fin de lo nuevo.

                    $categoria = $this->tipos();
                    for($i=0;$i<sizeof($categoria);$i++)
                    {
                         $apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
                         $opcion = $categoria[$i][descripcion];

                         if (($_REQUEST['criterio1'.$pfj])  != $apoyod_tipo_id)
                         {

                              $this->salida.="<option value = $apoyod_tipo_id>$opcion</option>";
                         }
                         else
                         {
                              $this->salida.="<option value = $apoyod_tipo_id selected >$opcion</option>";
                         }
                    }
                    $this->salida.="</select>";
                    $this->salida.="</td>";

                    $this->salida.="<td width=\"6%\">CARGO:</td>";
                    $this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargo$pfj'  value =\"".$_REQUEST['cargo'.$pfj]."\"    ></td>" ;

                    $this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
                    $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;

                    $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";


                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida.="</table>";
                    $this->salida.="</form>";
               	//hasta aqui lo que inserte
          	}
               else
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr><td align=\"center\" width=\"80%\" class=\"label_mark\" >SE ESTA ABRIENDO LA HISTORIA CLINICA SIN UN PLAN Y NO ES PERMITIDO REALIZAR SOLICITUDES</td></tr>";
                    $this->salida.="</table>";
               }
          }
          else
          {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr><td align=\"center\" width=\"80%\" class=\"label_mark\" >EL TIPO DE PROFESIONAL NO PERMITE GENERAR ESTE TIPO DE ORDEN MEDICA</td></tr>";
                    $this->salida.="</table>";
          }
          
          if($vector1)
          {
               $reporte = new GetReports();
               $mostrar=$reporte->GetJavaReport('hc','Apoyos_Diagnosticos_Solicitud','ReporteAPD_Solicitud',array('PACIENTE_ID'=>$this->paciente,'TIPOPACIENTE_ID'=>$this->tipoidpaciente,'INGRESO'=>$this->ingreso,'EVOLUCION'=>$this->evolucion,'PARAMETRO'=>$this->parametro),array('rpt_name'=>'ReporteAPDS'.$this->evolucion,'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
               $nombre_funcion=$reporte->GetJavaFunction();
               $this->salida.=$mostrar;
               
               $this->salida.="<br>";
               $this->salida.="<div align='center'>";
               $this->salida.="<a href=\"javascript:$nombre_funcion\">Imprimir Ordenes</a>";
               $this->salida.="</div>";
		}

		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


     //cor - clzc - ads
     function frmForma_Seleccion_Apoyos($vectorA)
     {
          $pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO');
          $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
          'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
          'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
          'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
          'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));

          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"5%\">TIPO</td>";

          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
          $this->salida.="<option value = '001' selected>Todos</option>";
          if (($_REQUEST['criterio1'.$pfj])  == '002')
          {
	          $this->salida.="<option value = '002' selected>Frecuentes</option>";
          }
          else
          {
               $this->salida.="<option value = '002' >Frecuentes</option>";
          }
          $categoria = $this->tipos();
          for($i=0;$i<sizeof($categoria);$i++)
          {
	          $apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
               if (($_REQUEST['criterio1'.$pfj])  != $apoyod_tipo_id)
               {
                    $this->salida.="<option value = $apoyod_tipo_id>".$categoria[$i][descripcion]."</option>";
               }
               else
			{
                    $this->salida.="<option value = $apoyod_tipo_id selected >".$categoria[$i][descripcion]."</option>";
               }
          }
          $this->salida.="</select>";
          $this->salida.="</td>";

          $this->salida.="<td width=\"6%\">CARGO:</td>";
          $this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargo$pfj'  value =\"".$_REQUEST['cargo'.$pfj]."\"    ></td>" ;

          $this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
          $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;

          $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          $this->salida.="</form>";

          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varias'));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";

	     if ($vectorA)
          {
     	     $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"15%\">TIPO</td>";
               $this->salida.="  <td width=\"10%\">CARGO</td>";
               $this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
               $this->salida.="  <td width=\"5%\">OPCION</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($vectorA);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'op".$pfj."[$i]' value = ".$vectorA[$i][cargo].",".$vectorA[$i][apoyod_tipo_id]."></td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";

               /*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
               if ($this->conteo == 1)
               {
                    $this->Insertar_Solicitud($vectorA[$i][cargo],$vectorA[$i][apoyod_tipo_id]);
               }*/

          	$var=$this->RetornarBarraExamenes_Avanzada();
               if(!empty($var))
               {
                    $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                    $this->salida .= "  <tr>";
                    $this->salida .= "  <td width=\"100%\" align=\"center\">";
                    $this->salida .=$var;
                    $this->salida .= "  </td>";
                    $this->salida .= "  </tr>";
                    $this->salida .= "  </table><br>";
               }
	     }
          $this->salida .= "</form>";

     	//BOTON DEVOLVER
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
     }


	//cor - clzc - ads
     function frmForma_Modificar_Observacion($hc_os_solicitud_id, $cargo, $descripcion, $observacion, $vectorD,$ambulatorio)
     {
		$pfj=$this->frmPrefijo;
          $this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO');
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar','hc_os_solicitud_id'.$pfj=>$hc_os_solicitud_id));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"2\">OBSERVACION</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td width=\"15%\">CARGO</td>";
          $this->salida.="  <td width=\"65%\">DESCRIPCION</td>";
          $this->salida.="</tr>";

          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"center\" width=\"15%\">$cargo</td>";
          $this->salida.="  <td align=\"left\" width=\"65%\">$descripcion</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
          $this->salida .="<td width=\"65%\" align='center'><textarea class='textarea' name = 'obs$pfj' cols = 100 rows = 3>$observacion</textarea></td>" ;
          $this->salida.="</tr>";
          
		//Responsable Tizziano Perea
          if(!empty($this->parametro))
          {
               $this->salida.="<tr class=\"$estilo\">";
               $profesionales = $this->ReconocerProfesional();
               $this->salida.="<td  width=\"80%\" colspan=\"2\">PROFESIONAL QUE ORDENO: &nbsp;&nbsp;&nbsp;&nbsp;";
               $this->salida.="<select name=\"ordeno$pfj\" class=\"select\">";
               $this->salida.="<option value=\"-1\" selected>-- SELECCIONE --</option>";
               foreach($profesionales as $k => $v)
               {
                    if($v[usuario_id] == $_REQUEST['ordeno'.$pfj] OR $v[usuario_id] == UserGetUID())
                         $this->salida.="<option value=\"".$v[usuario_id]."\" selected>".$v[nombre]."</option>";
                    else
                         $this->salida.="<option value=\"".$v[usuario_id]."\">".$v[nombre]."</option>";
               }
               $this->salida.="</select>";
               $this->salida.="</td>" ;
               $this->salida.="</tr>";
          }

          $diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
          if ($diag)
          {
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS PRESUNTIVOS</td>";
               $this->salida.="<td colspan = 2 width=\"65%\">";
               $this->salida.="<table width=\"100%\">";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">PRIMARIO</td>";
               $this->salida.="<td width=\"10%\">TIPO DX</td>";
			$this->salida.="<td width=\"8%\">CODIGO</td>";
			$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="<td width=\"7%\">ELIMINAR</td>";
			$this->salida.="</tr>";

               for($i=0;$i<sizeof($diag);$i++)
               {
                    $this->salida.="<tr class=\"$estilo\">";
          		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'cambiar_diagnostico', 'hc_os_solicitud_id'.$pfj=>$hc_os_solicitud_id,'obs'.$pfj=>$_REQUEST['obs'.$pfj],
                    'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
                    'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
                    'diagnostico'.$pfj=>$_REQUEST['diagnostico'],
                    'hc_os_solicitud_id'.$pfj=>$_REQUEST['hc_os_solicitud_id'.$pfj],
                    'codigo_esp'.$pfj=>$_REQUEST['codigo_esp'.$pfj],
                    'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
                    'observacion'.$pfj=>$_REQUEST['observacion'.$pfj],
                    'sw_cantidad'.$pfj=>$_REQUEST['sw_cantidad'.$pfj],
                    'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
                    'obs'.$pfj=>$_REQUEST['obs'.$pfj],
                    'cod_diag'.$pfj=>$diag[$i][diagnostico_id]));
                    
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
				}
				else
				{
					$this->salida.="<td align=\"center\" width=\"10%\"><a href='$accion'><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></a></td>";
				}

                    if($diag[$i][tipo_diagnostico] == '1')
				{
					$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
				}elseif($diag[$i][tipo_diagnostico] == '2')
				{
					$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
				}else
				{
					$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
				}
                   
                    $this->salida.="<td align=\"center\" width=\"8%\">".$diag[$i][diagnostico_id]."</td>";
                    $this->salida.="<td align=\"justify\" width=\"60%\">".$diag[$i][diagnostico_nombre]."</td>";
                    
                    $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico', 'hc_os_solicitud_id'.$pfj => $hc_os_solicitud_id, 'codigo'.$pfj => $diag[$i][diagnostico_id], 'principal'.$pfj =>$diag[$i][sw_principal],
                    'hc_os_solicitud_id'.$pfj=>$_REQUEST['hc_os_solicitud_id'.$pfj],
                    'codigo_esp'.$pfj=>$_REQUEST['codigo_esp'.$pfj],
                    'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
                    'observacion'.$pfj=>$_REQUEST['observacion'.$pfj],
                    'sw_cantidad'.$pfj=>$_REQUEST['sw_cantidad'.$pfj],
                    'cantidad'.$pfj=>$_REQUEST['cantidad'.$pfj],
                    'obs'.$pfj=>$_REQUEST['obs'.$pfj]));
                    $this->salida.="<td align=\"center\" width=\"7%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                    $this->salida.="<tr>";
               }
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"5\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
               $this->salida.="</tr>";

               $this->salida.="</table>";
               $this->salida .="</td>" ;
               $this->salida.="</tr>";
          }
          //cambio dar
          if($this->servicio!=3)
          {
               $this->salida.="<tr class=\"$estilo\">";
               if(!empty($ambulatorio))
               {  $this->salida .= "<td align=\"center\" colspan=\"2\" class=\"label\"><input type=\"checkbox\" name=\"ambulatorio$pfj\" value=\"1\" checked>SOLICITUD AMBULATORIA</td>";  }
               else
               {  $this->salida .= "<td align=\"center\" colspan=\"2\" class=\"label\"><input type=\"checkbox\" name=\"ambulatorio$pfj\" value=\"1\">SOLICITUD AMBULATORIA</td>";  }
               $this->salida.="</tr>";					
          }
          //fin cambio dar
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida .= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";
          $this->salida .= "</form>";


		//nueva forma
          $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
          'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
          'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
          'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
          'hc_os_solicitud_id'.$pfj=>$_REQUEST['hc_os_solicitud_id'.$pfj],
          'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
          'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
          'observacion'.$pfj=>$_REQUEST['observacion'.$pfj]));

          $this->salida.="<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"4%\">CODIGO:</td>";

          $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
          //la misma pero con el value $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'  value =\"".$_REQUEST['codigo'.$pfj]."\"    ></td>" ;

          $this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
          $this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;

          $this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";
          /*
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";
          */
          $this->salida.="</form>";

          $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos',
          'hc_os_solicitud_id'.$pfj=>$_REQUEST['hc_os_solicitud_id'.$pfj],
          'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
          'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
          'observacion'.$pfj=>$_REQUEST['observacion'.$pfj]));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
          if ($vectorD)
          {
	          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"8%\">CODIGO</td>";
               $this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="  <td width=\"17%\">TIPO DX</td>";
               $this->salida.="  <td width=\"5%\">OPCION</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($vectorD);$i++)
               {
                    $codigo          = $vectorD[$i][diagnostico_id];
                    $diagnostico    = $vectorD[$i][diagnostico_nombre];

                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";

                    $this->salida.="  <td align=\"center\" width=\"8%\">$codigo</td>";
                    $this->salida.="  <td align=\"left\" width=\"60%\">$diagnostico</td>";
   				$this->salida.="<td align=\"center\" width=\"17%\">";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
                    $this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = ".$hc_os_solicitud_id.",".$codigo."></td>";
                    $this->salida.="</tr>";
               }
               
               $this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
               
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";
               /*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
               if ($this->conteo == 1)
		     {
                    $this->Insertar_Diagnosticos($hc_os_solicitud_id, $codigo);
               }*/

               $var=$this->RetornarBarraDiagnosticos_Avanzada();
               if(!empty($var))
               {
                    $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                    $this->salida .= "  <tr>";
                    $this->salida .= "  <td width=\"100%\" align=\"center\">";
                    $this->salida .=$var;
                    $this->salida .= "  </td>";
                    $this->salida .= "  </tr>";
                    $this->salida .= "  </table><br>";
               }
     	}
          $this->salida .= "</form>";

		//fin de la nueva forma

		//BOTON DEVOLVER
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

     //COR - clzc - ads
     function frmConsulta()
     {
		$pfj=$this->frmPrefijo;

          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $vector1 = $this->Consulta_Solicitud_Apoyod();
          if($vector1)
          {
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";

               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"4\">APOYOS DIAGNOSTICOS SOLICITADOS</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"7%\">TIPO</td>";
               $this->salida.="  <td width=\"9%\">CARGO</td>";
               $this->salida.="  <td width=\"70%\">DESCRIPCION</td>";
	          $this->salida.="  <td width=\"14%\">FECHA/HORA EVOLUCION</td>";
               $this->salida.="</tr>";

               for($i=0;$i<sizeof($vector1);$i++)
               {
	               $hc_os_solicitud_id =$vector1[$i][hc_os_solicitud_id];
                    $a = $this->FechaStamp($vector1[$i][fecha]);
                    $b = $this->HoraStamp($vector1[$i][fecha]);
                    $usuario_solicitud = $vector1[$i][usuario_solicitud];

                    $fecha = $a.' - '.$b;
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
										$row=3;
                    //cambio dar										
                    if($vector1[$i][sw_ambulatorio]==1)
                    {  $row=4;  }
                    
                    //Responsable Tizziano Perea
                    if(!empty($this->parametro) AND !empty($vector1[$i][usuario_solicitud]))
                    {  
                    	$row=4;
                         if($vector1[$i][sw_ambulatorio]==1)
	                    {  $row=5;  }
                         $profesionales = $this->ReconocerProfesional($usuario_solicitud);
                         $nombre = $profesionales[0][nombre];
                         $usuario_solicitud = $profesionales[0][usuario_id];
                    }

                    $this->salida.="  <td ROWSPAN = $row align=\"center\" width=\"7%\">".$vector1[$i][tipo]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"9%\">".$vector1[$i][cargo]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"76%\">".$vector1[$i][descripcion]."</td>";
	               $this->salida.="  <td align=\"left\" width=\"14%\">".$fecha."</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                	$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Observacion</td>";
                	$this->salida.="  <td colspan = 2 align=\"left\" width=\"84%\">".$vector1[$i][observacion]."</td>";
                    $this->salida.="</tr>";
                    
                    //cambio dar										
                    if($vector1[$i][sw_ambulatorio]==1)
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td colspan =4 align=\"CENTER\"  class=\"label\">SOLICITUD AMBULATORIA</td>";
                         $this->salida.="</tr>";										
                    }
                    //fin cambio dar   
                    
                    //Responsable Tizziano Perea
                    if(!empty($this->parametro) AND !empty($vector1[$i][usuario_solicitud]))                    
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Orden Profesional</td>";
                         $this->salida.="  <td colspan = 2 align=\"left\" width=\"84%\">".$nombre."</td>";
                         $this->salida.="</tr>";
                    }
                    
                    $diag =$this->Diagnosticos_Solicitados($vector1[$i][hc_os_solicitud_id]);
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos Presuntivos</td>";
                    $this->salida.="  <td colspan = 2 align=\"left\" width=\"84%\">";
                    $this->salida.="<table width=\"100%\">";
                    if(!empty($diag))
                    {
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="<td width=\"10%\">PRIMARIO</td>";
                         $this->salida.="<td width=\"10%\">TIPO DX</td>";
                         $this->salida.="<td width=\"10%\">CODIGO</td>";
                         $this->salida.="<td width=\"80%\">DIAGNOSTICO</td>";
                         $this->salida.="</tr>";
                    }
                    for($j=0;$j<sizeof($diag);$j++)
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         
                         if($diag[$j]['sw_principal']==1)
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
                         }
                         else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
                         }
                         
                         if($diag[$j][tipo_diagnostico] == '1')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                         }elseif($diag[$j][tipo_diagnostico] == '2')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                         }else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                         }
                         $this->salida.="<td width=\"10%\" align=\"center\">".$diag[$j][diagnostico_id]."</td>";
                         $this->salida.="<td width=\"80%\" align=\"justify\">".$diag[$j][diagnostico_nombre]."</td>";
                         $this->salida.="</tr>";
                    }
                    if(!empty($diag))
                    {
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
                         $this->salida.="</tr>";
                    }               
						                 
                    
                    /*$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
                    //if ($diag)
                    //{
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
                         $this->salida.="  <td colspan = 2 align=\"left\" width=\"84%\">";
                         $this->salida.="<table>";
		               for($j=0;$j<sizeof($diag);$j++)
                         {
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]." - </td>";
                              $this->salida.="<td colspan = 2>".$diag[$j][diagnostico_nombre]."</td>";
                              $this->salida.="</tr>";
                         }*/
                         $this->salida.="</table>";
                         $this->salida.="</td>";
                         $this->salida.="</tr>";
                    //}
               }
               $this->salida.="</table><br>";
          }
     	$this->salida .= "</form>";
		return true;
	}



	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$vector1 = $this->Consulta_Solicitud_Apoyod();
		if($vector1)
		{
			$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"4\">APOYOS DIAGNOSTICOS SOLICITADOS</td>";
			$salida.="</tr>";

			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="  <td width=\"7%\">TIPO</td>";
			$salida.="  <td width=\"9%\">CARGO</td>";
			$salida.="  <td width=\"70%\">DESCRIPCION</td>";
			$salida.="  <td width=\"14%\">FECHA/HORA EVOLUCION</td>";
			$salida.="</tr>";

			for($i=0;$i<sizeof($vector1);$i++)
			{
				$hc_os_solicitud_id =$vector1[$i][hc_os_solicitud_id];
				$a = $this->FechaStamp($vector1[$i][fecha]);
				$b = $this->HoraStamp($vector1[$i][fecha]);
                    
                    $usuario_solicitud = $vector1[$i][usuario_solicitud];

				$fecha = $a.' - '.$b;
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
                    
                    //Responsable Tizziano Perea
                    if(!empty($this->parametro) AND !empty($vector1[$i][usuario_solicitud]))
                    {  
                    	$row=4;
                         $profesionales = $this->ReconocerProfesional($usuario_solicitud);
                         $nombre = $profesionales[0][nombre];
                         $usuario_solicitud = $profesionales[0][usuario_id];
                    }
                    else
                    {
                    	$row = '3';
                    }
                                        
				$salida.="<tr class=\"$estilo\">";
				$salida.="  <td ROWSPAN = $row align=\"center\" width=\"7%\">".$vector1[$i][tipo]."</td>";
				$salida.="  <td align=\"center\" width=\"9%\">".$vector1[$i][cargo]."</td>";
				$salida.="  <td align=\"left\" width=\"76%\">".$vector1[$i][descripcion]."</td>";
				$salida.="  <td align=\"left\" width=\"14%\">".$fecha."</td>";
				$salida.="</tr>";
				
                    $salida.="<tr class=\"$estilo\">";
				$salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Observacion</td>";
				$salida.="  <td colspan = 2 align=\"left\" width=\"84%\">".$vector1[$i][observacion]."</td>";
				$salida.="</tr>";
                    
                    //Responsable Tizziano Perea
                    if(!empty($this->parametro) AND !empty($vector1[$i][usuario_solicitud]))                    
                    {
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Orden Profesional</td>";
                         $salida.="  <td colspan = 2 align=\"left\" width=\"84%\">".$nombre."</td>";
                         $salida.="</tr>";
                    }

				$diag =$this->Diagnosticos_Solicitados($vector1[$i][hc_os_solicitud_id]);
				$salida.="<tr class=\"$estilo\">";
				$salida.="<td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos Presuntivos</td>";
				$salida.="<td colspan = 2 align=\"left\" width=\"84%\">";
				$salida.="<table width=\"100%\">";
				if(!empty($diag))
				{
							$salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$salida.="<td width=\"10%\">PRIMARIO</td>";
							$salida.="<td width=\"10%\">TIPO DX</td>";
							$salida.="<td width=\"10%\">CODIGO</td>";
							$salida.="<td width=\"70%\">DIAGNOSTICO</td>";
							$salida.="</tr>";
				}
				for($j=0;$j<sizeof($diag);$j++)
				{
					$salida.="<tr class=\"$estilo\">";
                       
					if($diag[$j]['sw_principal']==1)
					{
							$salida.="<td align=\"center\" width=\"10%\">DX 1</td>";
					}
					else
					{
							$salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";
					}
					
					if($diag[$j][tipo_diagnostico] == '1')
					{
							$salida.="<td align=\"center\" width=\"10%\"> ID </td>";
					}elseif($diag[$j][tipo_diagnostico] == '2')
					{
							$salida.="<td align=\"center\" width=\"10%\"> CN </td>";
					}else
					{
							$salida.="<td align=\"center\" width=\"10%\"> CR </td>";
					}

					$salida.="<td width=\"10%\" align=\"center\">".$diag[$j][diagnostico_id]."</td>";
					$salida.="<td width=\"70%\" align=\"justify\">".$diag[$j][diagnostico_nombre]."</td>";
					$salida.="</tr>";
				}
				if(!empty($diag))
				{
							$salida.="<tr>";
							$salida.="<td align=\"center\" colspan=\"4\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
							$salida.="</tr>";
				}
				$salida.="</table>";
				$salida.="</td>";
				$salida.="</tr>";
				//cambio dar										
				if($vector1[$i][sw_ambulatorio]==1)
				{
						$salida.="<tr class=\"$estilo\">";
						$salida.="  <td colspan =5 align=\"CENTER\"  class=\"label\">SOLICITUD AMBULATORIA</td>";
						$salida.="</tr>";										
				}
				//fin cambio dar
			}
			$salida.="</table><br>";
		}
		return $salida;
	}



//DARLING
	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	 function FechaStamp($fecha)
	 {
			if($fecha){
					$fech = strtok ($fecha,"-");
					for($l=0;$l<3;$l++)
					{
						$date[$l]=$fech;
						$fech = strtok ("-");
					}
					return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
	}


	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
			$hor = strtok ($hora," ");
			for($l=0;$l<4;$l++)
			{
				$time[$l]=$hor;
				$hor = strtok (":");
			}

			$x = explode (".",$time[3]);
			return  $time[1].":".$time[2].":".$x[0];
	}

}
?>
