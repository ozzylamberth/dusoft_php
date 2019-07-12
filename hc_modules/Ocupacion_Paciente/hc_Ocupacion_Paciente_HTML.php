<?php

/**
* Submodulo de Ocupacion del Paciente (HTML).
*
* Submodulo que permite reportar y editar la ocupacion de un paciente ingresado.
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Ocupacion_Paciente_HTML.php,v 1.4 2005/09/01 18:47:58 tizziano Exp $
*/

/**
* Ocupacion_Paciente_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Promocion_y_Prevencion, se extiende la clase Promocion_y_Prevencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Ocupacion_Paciente_HTML extends Ocupacion_Paciente
{

	function Ocupacion_Paciente_HTML()
	{
	    $this->Ocupacion_Paciente();//constructor del padre
       	return true;
	}

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
	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Ocupaciones',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'ocupacion'.$pfj=>$_REQUEST['ocupacion'.$pfj],'ingresos'.$pfj=>$_REQUEST['ocupacion'.$pfj]));

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

	//
     //cor - jea - ads
	function RetornarBarra_IngresosAvanzada()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
          $this->limit=1;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Ingresos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));

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

     //
     
	function frmConsulta()
	{
    		return true;
	}


	function frmHistoria()
	{
		return true;
	}


	function frmForma($ingresos)
	{
		$pfj=$this->frmPrefijo;
         
	     $this->Datos_Adicionales_Pacientes();
		
          if(empty($this->titulo))
		{
			$this->salida  = ThemeAbrirTablaSubModulo('OCUPACION DEL PACIENTE');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$ocupacion_paciente = $this->ConsultaOcupacion();

          $java = "\n<script language='javascript'>\n";
          $java.= "function Recargar(frm){\n";
          $java.= "frm.submit();\n";
          $java.= "}\n";
          $java.= "</script>\n";
          $this->salida.="$java";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'cambiar_descripcion', 'ocupacion_id'.$pfj=>$ocupacion_paciente[1], 'descripcion'.$pfj=>$ocupacion_paciente[0], 'ingresos'.$pfj=>$ingresos));
          $this->salida.= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">OCUPACION PACIENTE</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"60%\">OCUPACION</td>";
		$this->salida.="<td width=\"10%\">NOTA</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align=\"justify\" width=\"40%\"><b>$ocupacion_paciente[0]</b></td>";

		$this->salida.="<td align=\"center\" width=\"40%\"><a href=javascript:Recargar(document.formades$pfj)><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
		$this->salida.="</tr>";
		
          if (!empty($this->datos_adicionales[telefono_trabajo]) OR !empty($this->datos_adicionales[direccion_trabajo]))
		{
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"40%\">DIRECCION TRABAJO</td>";
               $this->salida.="<td width=\"40%\">TELEFONO TRABAJO</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td class=\"modulo_list_claro\" align=\"justify\" width=\"40%\">".$this->datos_adicionales[direccion_trabajo]."</td>";
               $this->salida.="<td class=\"modulo_list_claro\" align=\"justify\" width=\"40%\">".$this->datos_adicionales[telefono_trabajo]."</td>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table><br>";

          $descripcion = $this->Consulta_DescripcionOcupacion();
		if(!empty($descripcion))
          {
               $this->salida .="<table width=\"80%\" border=\"0\" align=\"center\">";
               $this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .="<td width=\"10%\" align=\"center\" nowrap>FECHA</td>";
               $this->salida .="<td width=\"90%\" align=\"center\" nowrap>DESCRIPCION DE LAS OCUPACIONES ACTUALES Y/O ANTERIORES DEL PACIENTE</td>";
               $this->salida .="</tr>";
     
               $spy=0;
               foreach($descripcion as $k=>$v)
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
     
                    $this->salida .="<td width=\"10%\" nowrap align=\"center\">$k</td>";
     
     
                    $this->salida .="<td width=\"90%\" nowrap><table border=\"1\" class=\"hc_table_submodulo_list\" width=\"100%\">";
                    foreach($v as $k2=>$vector){
     
                         $this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
                         $this->salida .="<td width=\"10%\" nowrap><b>&nbsp;$vector[hora]</b></td>";
                         $this->salida .="<td width=\"87%\" nowrap><b>&nbsp;";
                         $this->salida .=$vector[usuario].' - '.$vector[nombre];
                         $this->salida .="</b></td>";
                         if($vector[sw_estado]=='1')
                         {
                              $this->salida .="<td width=\"3%\" nowrap><img title=\"ESTA NOTA ES LA ACTUAL\" src=\"".GetThemePath()."/images/checkS.gif\" border='0'></td>";
                              $this->salida.="<input type='hidden' name='retaila$pfj' value='".$vector[descripcion]."'>\n";    
                         }
                         else
                         {
                              $this->salida .="<td width=\"3%\" nowrap>&nbsp;</td>";                    
                         }
                         $this->salida .="</tr>";
     
                         if (!empty($vector[descripcion]))
                         {
                              $motivo = chunk_split($vector[descripcion],150,'<br>');
                              $this->salida .="<tr class=\"hc_submodulo_list_claro\">";
                              $this->salida .="<td width=\"10%\" nowrap class=\"hc_submodulo_list_claro\">&nbsp;</td>";
                              $this->salida .="<td width=\"90%\" colspan=\"2\" nowrap align=\"justify\"><b>&nbsp;DESCRIPCION  :<br></b>  ".$motivo."</td>";
                              $this->salida .="</tr>";
                         }
                         $this->salida .="<tr>";
                    }
                    $this->salida .="</table>";
                    $this->salida .="</td>";
                    $this->salida .="</tr>";
               }
               $this->salida.="</table><br>";
          }
          $this->salida.="</form>";

          
          //
          $accionJJ=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Ingresos', 'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida.= "<form name=\"formaIngresos$pfj\" action=\"$accionJJ\" method=\"post\">";
          //

          foreach($ingresos as $j => $ingreso)
          {
          	$causas = $this->Causas_Externas($ingreso[ingreso]);
               $diag = $this->Diagnosticos($ingreso[ingreso]);
               $descripcion = $this->Motivos($ingreso[ingreso]);
               $via = $this->Via_Ingreso($ingreso[ingreso]);

               $this->salida.="<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_list_claro\">";
               
               $this->salida.="<tr class=\"modulo_table_title\">";
               $fecha_ingreso = $this->FechaStamp($ingreso[fecha_registro]);
               $this->salida.="<td align=\"left\" width=\"100%\">INGRESO: ".$ingreso[ingreso]."</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"left\" width=\"100%\">FECHA Y VIA DE INGRESO: ( $fecha_ingreso ) - $via[via_ingreso_nombre]</td>";
               $this->salida.="</tr>";
               
               if(!empty($causas))
               {
                    $this->salida.="<tr class=\"modulo_list_oscuro\">";
	               $this->salida.="<td align=\"center\" width=\"100%\">";

                    $this->salida.="<br><table align=\"center\" border=\"1\" width=\"100%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="<td align=\"center\" width=\"100%\" colspan=\"2\">CAUSALES DE ATENCION</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"20%\">EVOLUCION</td>";
                    $this->salida.="<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"80%\">CAUSAS</td>";                    
                    for($i=0; $i<sizeof($causas); $i++)
                    {
                         $this->salida.="</tr>";
                         $this->salida.="<td class=\"modulo_list_claro\" align=\"center\" width=\"20%\">".$causas[$i][evolucion_id]."</td>";
                         $this->salida.="<td class=\"modulo_list_claro\" align=\"justify\" width=\"80%\">".strtoupper($causas[$i][detalle])."</td>";
                         $this->salida.="</tr>";
                    }
                    $this->salida.="</table><br>";
                    
                    $this->salida.="</td>";
	               $this->salida.="</tr>";
               }
                    

               if(!empty($descripcion))
               {
                    $this->salida.="<tr class=\"modulo_list_claro\">";
	               $this->salida.="<td align=\"center\" width=\"100%\">";
                    
                    $this->salida.="<br><table align=\"center\" border=\"1\" width=\"100%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="<td align=\"center\" width=\"100%\">MOTIVOS DE CONSULTAS</td>";
                    $this->salida.="</tr>";
                    
                    $this->salida.="<tr>";
                    $this->salida.="<td align=\"center\" width=\"100%\">";
                    
                    $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
                    for($x=0; $x<sizeof($descripcion); $x++)
                    {
                    	if(!empty($descripcion[$x][motivo_consulta]))
                         {
                              $this->salida.="<tr>";
                              $this->salida.="<td class=\"hc_table_submodulo_list_title\" align=\"left\" width=\"25%\">Motivo Evolución: ".$descripcion[$x][evolucion_id]."</td>";
                              $this->salida.="<td align=\"justify\" width=\"75%\" class=\"modulo_list_oscuro\">".$descripcion[$x][motivo_consulta]."</td>";
                              $this->salida.="</tr>";
                         }
                    	
                         if(!empty($descripcion[$x][enfermedad_actual]))
                         {
                              $this->salida.="<tr>";
                              $this->salida.="<td class=\"hc_table_submodulo_list_title\" align=\"left\" width=\"25%\">Enfermedad Evolución: ".$descripcion[$x][evolucion_id]."</td>";
                              $this->salida.="<td align=\"justify\" width=\"75%\" class=\"modulo_list_oscuro\">".$descripcion[$x][enfermedad_actual]."</td>";
                              $this->salida.="</tr>";
                         }
                    }
                    $this->salida.="</table>";
     
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
                    
                    $this->salida.="</td>";
	               $this->salida.="</tr>";
               }
  
               if(!empty($diag))
               {
                    $this->salida.="<tr class=\"modulo_list_oscuro\">";
                    $this->salida.="<td align=\"center\" width=\"100%\">";
     
                    $this->salida.="<br><table width=\"100%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="<td align=\"center\" width=\"100%\" colspan=\"4\">IMPRESION DIAGNOSTICA</td>";
                    $this->salida.="</tr>";

                    if(!empty($diag))
                    {
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="<td width=\"10%\">PRIMARIO</td>";
                         $this->salida.="<td width=\"10%\">TIPO DX</td>";
                         $this->salida.="<td width=\"10%\">CODIGO</td>";
                         $this->salida.="<td width=\"70%\">DIAGNOSTICO</td>";
                         $this->salida.="</tr>";
                    }
          
                    for($w=0;$w<sizeof($diag);$w++)
                    {
                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         
                         if($diag[$w]['sw_principal']==1)
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
                         }
                         else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
                         }
                         
                         if($diag[$w][tipo_diagnostico] == '1')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                         }elseif($diag[$w][tipo_diagnostico] == '2')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                         }else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                         }
                         $this->salida.="<td align=\"center\" width=\"10%\">".$diag[$w][diagnostico_id]."</td>";
                         $this->salida.="<td align=\"justify\" width=\"60%\">".$diag[$w][diagnostico_nombre]."</td>";
                         $this->salida.="</tr>";
                    }
                    if(!empty($diag))
                    {
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"> (ID) - IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;(CN) - CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;(CR) - CONFIRMADO REPETIDO</td>";
                         $this->salida.="</tr>";
                    }
                    
                    $this->salida.="</table><br>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
               }

               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.="</table>";
          }

          //          
          $var=$this->RetornarBarra_IngresosAvanzada();
          if(!empty($var))
          {
               $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
               $this->salida .= "  <tr>";
               $this->salida .= "  <td width=\"100%\" align=\"center\">";
               $this->salida .=$var;
               $this->salida .= "  </td>";
               $this->salida .= "  </tr>";
               $this->salida .= "  </table><br>";
          }
          //
          $this->salida.="</form>";
          $this->salida.= ThemeCerrarTablaSubModulo();
		return true;
	}
     

	function CambiarDescripcion($vectorD)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DESCRIPCION OCUPACION DEL PACIENTE');
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";

          $ocupacion_paciente = $this->ConsultaOcupacion();
          
          $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Ocupaciones', 'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj], 'codigo'.$pfj=>$_REQUEST['codigo'.$pfj], 'ocupacion'.$pfj=>$_REQUEST['ocupacion'.$pfj], 'ingresos'.$pfj=>$_REQUEST['ocupacion'.$pfj]));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE OCUPACIONES</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">OCUPACION:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'ocupacion$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"></td>" ;
		$this->salida.= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";

		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_ocupacion', 'vector'.$pfj=>$vectorD[$i][ocupacion_id],'ingresos'.$pfj=>$_REQUEST['ocupacion'.$pfj]));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"10%\">CODIGO</td>";
			$this->salida.="  <td width=\"60%\">OCUPACION</td>";
			$this->salida.="  <td width=\"10%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				$codigo = $vectorD[$i][ocupacion_id];
				$ocupacion = $vectorD[$i][ocupacion_descripcion];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\" width=\"10%\">$codigo</td>";
				$this->salida.="<td align=\"left\" width=\"60%\">$ocupacion</td>";
				$this->salida.="<td align=\"center\" width=\"10%\"><input type = radio name= 'opD".$pfj."[$i]' value = ".$codigo."></td>";
				$this->salida.="</tr>";
			}

			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";

			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$var;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}
		}

		$this->salida .= "</form>";
		$accionA=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insertar_Descripcion','ocupacion_id'.$pfj=>$ocupacion_paciente[1], 'ingresos'.$pfj=>$_REQUEST['ocupacion'.$pfj]));
		$this->salida.= "<form name=\"descripcion$pfj\" action=\"$accionA\" method=\"post\">";
		
          $this->salida.="<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" width=\"80%\">DESCRIPCION OCUPACION</td>";
		$this->salida.="</tr>";
          $this->salida.="<tr>";
		
          if (!empty($_REQUEST['descripcion'.$pfj]))
          {
               $this->salida.="<td align=\"center\" width=\"80%\" class=\"modulo_list_claro\">".$_REQUEST['descripcion'.$pfj]."</td>";
          }
          else
          {
               $this->salida.="<td align=\"center\" width=\"80%\" class=\"modulo_list_claro\">".$ocupacion_paciente[0]."</td>";
          }
          $this->salida.="</tr>";

		$this->salida.="</table>";
		
          $this->salida.="<br>";
		
          $this->salida.="<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"20%\">DESCRIPCION</td>";
		$this->salida.="<td align =\"left\" width=\"80%\"><textarea name='descripcion_ocupacion$pfj' style=\"width:100%\" cols=40 rows=7>".$_REQUEST['retaila'.$pfj]."</textarea>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		
          $this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr>";
		$this->salida.="<td colspan=\"2\" width=\"50%\" align=\"right\"><input class=\"input-submit\" name=\"insertar$pfj\" type=\"submit\" value=\"INSERTAR\"></td>";
		$this->salida.="</form>";
		$accionB=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Volver_Original','ingresos'.$pfj=>$_REQUEST['ocupacion'.$pfj]));
		$this->salida.="<form name=\"descripcion2$pfj\" action=\"$accionB\" method=\"post\">";
		$this->salida.="<td colspan=\"2\" width=\"50%\" align=\"left\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</td>";
		
          $this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
