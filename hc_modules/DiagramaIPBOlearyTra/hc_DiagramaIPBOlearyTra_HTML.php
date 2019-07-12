
<?php

/**
* Submodulo de Diagrama de Indice de Placa Bacteriana Oleary.
*
* Submodulo para manejar el IPB Oleary del paciente.
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_DiagramaIPBOlearyTra_HTML.php,v 1.17 2006/08/10 20:22:09 carlos Exp $
*/

/**
* Diagrama de IPB Oleary
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de DiagrmaIPBOleary.
*/

class DiagramaIPBOlearyTra_HTML extends DiagramaIPBOlearyTra
{

	function DiagramaIPBOlearyTra_HTML()
	{
		$this->DiagramaIPBOlearyTra();//constructor del padre
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
		$odontograma=$this->BuscarIPBOleary();
		if($odontograma===false)
		{
			return false;
		}
		$randon=rand();
		$randon=$this->ingreso._.$randon;
		IncludeLib("jpgraph/IPBOleary_graphic");
		$valoracion=$this->BuscarEnviarPintarOleary();
		$nopresentes=$this->BuscarEnviarPintarNoBoca();
		$valoracio2=$this->BuscarEnviarPintarOlearyTra();
		$nopresente2=$this->BuscarEnviarPintarNoBocaTra($nopresentes);//$nopresentes
		$RutaImg=IPBOleary($valoracion,$randon,$nopresentes,1);
		$RutaImg2=IPBOleary($valoracio2,$randon,$nopresente2,2);
		$this->salida =ThemeAbrirTablaSubModulo('DIAGRAMA IPB OLEARY');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<label class=\"label\">DIAGRAMA DE IPB O'LEARY DE PRIMERA VEZ</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$indice=$this->CalcularIPBOleary();
		$this->salida.="<label class=\"label\">PLACA BACTERIANA ANTERIOR: ".number_format(($indice), 2, ',', '.')." %</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<label class=\"label\">DIAGRAMA DE IPB O'LEARY DE TRATAMIENTO</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<img src=\"".$RutaImg2."\" border=\"0\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$indice=$this->CalcularIPBOlearyTra();
		$this->salida.="<label class=\"label\">PLACA BACTERIANA ANTERIOR: ".number_format(($indice), 2, ',', '.')." %</label>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
    $this->salida.="<tr>";
    //FUNCIÖN NO SELECCIONAR CHECK 
    $this->salida .= "<script language='javascript'>";
    $this->salida .= "function chequeoTotal(frm,x){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
    $this->salida .= "        if(frm.elements[i].checked==true){";
    $this->salida .= "          frm.elements[i].checked=false;";
    $this->salida .= "    }";
    $this->salida .= "   }";
    $this->salida .= "  }";
    $this->salida .= " }";
    $this->salida .= "}";
    $this->salida .= "</script>";
    //fin FUNCIÓN NO SELECCIONAR CHECK 
    //CHECK DE LOS DIENTES A DESELECCIONAR
    $this->salida.="<td align=\"right\" width=\"3%\">";
    $this->salida.="  <label class=\"label\">LIMPIAR<input type = checkbox name='todas' onclick=chequeoTotal(this.form,this.checked)></label>";
    $this->salida.="</td>";
    $this->salida.="</tr>";    
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<table width=\"45%\" border=\"0\" align=\"center\">";
		$this->salida.="	<tr>";
		$this->salida.="	<td width=\"50%\" align=\"right\">";
/*		$busquedas=$this->BuscarTipoUbicacion();
		$this->salida.="	<select name=\"tipoubicpb".$pfj."\" class=\"select\">";
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1)
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."\" selected>".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."\">".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."</option>";
			}
		}
		$this->salida.="	</select>";*/
    $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
    //FIN CONTROL ETIQUETAS*/
    //control de etiquetas
    $n[0]=5;
    $n[1]=8;
    $n[2]=5;
    $n[3]=8;
    $n[4]=8;
    $n[5]=5;
    $n[6]=8;
    $n[7]=5;
    
    $i=0;
    $k=0;
    $label=55;
    $label2=61;
    $label3=18;
    $label4=21;
    $label5=48;
    $label6=31;
    $label7=85;
    $label8=71;
    $lim=sizeof($n);
    
    for ($l=0;$l<$lim;)
    {
      while ($i<$n[$l])
      {
        if ($n[$l]==5 AND $label==55 AND $k==0 AND $l==0)
        {
          $this->salida.= "<TR>";          
          $this->salida.= "<TD align=\"right\" class=\"hc_submodulo_list_oscuro\">";          
        }
        else
        if ($n[$l]==8 AND $label3==0 AND $k==0 AND $l==1)
        {
          $this->salida.= "</TD>";            
          //$this->salida.= "<BR><BR><BR>";  
          $label3=1;       
        }
        else
        if ($n[$l]==5 AND $label3==1 AND $label2==61 AND $k==0 AND $l==2)
        {
          $this->salida.= "<TD align=\"left\" class=\"hc_submodulo_list_claro\">";          
        }
        else
        if ($n[$l]==8 AND $label4==0)
        { 
          $this->salida.= "</TD>";          
          $this->salida.= "</TR>";  
          $label4=1;
        }        
        else
        if ($n[$l]==8 AND $label5==48 AND $label4==1 AND $k==1)
        {
          $this->salida.= "<TR>";          
          $this->salida.= "<TD align=\"right\" class=\"hc_submodulo_list_claro\">";          
          $k=0;
          $label4=2;
        }     
        else
        if ($n[$l]==5 AND $label7==0 AND $label4==2)// 
        { 
          $this->salida.= "</TD>"; 
          $label7=1;   
        }
        else
        if ($label7==1)
        { 
          $this->salida.= "<TD align=\"left\" class=\"hc_submodulo_list_oscuro\">";          
          $label7=2;
          $n[$l]=9;         
        }
        else
        if ($label8==0)
        {
          $this->salida.= "</TD>";          
          $this->salida.= "</TR>";          
          $label8=1;   
        }        
        //INICIO ETIQUETAS /CHECK
        if ($n[$l]==5 AND $label>=51 AND $label<=55 AND $k==0 AND $l==0)
        {  
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label."&nbsp;&nbsp;</label>";    
         $label=$label-1;
        }
        else
        if ($n[$l]==5 AND $label>=51 AND $label<=55 AND $k==1 AND $l==0)
        {
         $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label."\">&nbsp;";          
         $label=$label-1;
        } 
        else
        if ($n[$l]==8 AND $label3>=11 AND $label3<=18 AND $k==0 AND $l==1)
        {  
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label3."&nbsp;&nbsp;</label>";    
         $label3=$label3-1;
        }
        else
        if ($n[$l]==8 AND $label3>=11 AND $label3<=18 AND $k==1  AND $l==1)
        {  
         $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label3."\">&nbsp;";          
         $label3=$label3-1;
        }
        else
        if ($n[$l]==5 AND $label2>=61 AND $label2<=65 AND $k==0 AND $l==2)
        {
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label2."&nbsp;&nbsp;</label>";          
         $label2=$label2+1;
        } 
        else
        if ($n[$l]==5 AND $label2>=61 AND $label2<=65 AND $k==1 AND $l==2)
        {
         $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label2."\">&nbsp;";          
         $label2=$label2+1;
        } 
        else
        if ($n[$l]==8 AND $label4>=21 AND $label4<=28 AND $k==0 AND $l==3)
        {
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label4."&nbsp;&nbsp;</label>";          
         $label4=$label4+1;
        } 
        else
        if ($n[$l]==8 AND $label4>=21 AND $label4<=28 AND $k==1 AND $l==3)
        {
         $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label4."\">&nbsp;";          
         $label4=$label4+1;
        }       
        else
        if ($n[$l]==8 AND $label5>=41 and $label5<=48 AND $k==0 AND $l==4)
        {      
          $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label5."&nbsp;&nbsp;</label>";          
          $label5=$label5-1;      
        }
        else 
        if ($n[$l]==8 AND $label5>=41 and $label5<=48 AND $k==1 AND $l==4)
        {    
          $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label5."\">&nbsp;";           
          $label5=$label5-1;
        }
        else
        if ($n[$l]==5 AND $label7>=81 AND $label7<=85 AND $k==1 AND $l==5)
        {
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label7."&nbsp;&nbsp;</label>";          
         $label7=$label7-1;
        } 
        else
        if ($n[$l]==5 AND $label7>=81 AND $label7<=85 AND $k==0 AND $l==5)
        {
         $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label7."\">&nbsp;";          
         $label7=$label7-1;
        } 
        else
        if ($label6>=31 and $label6<=38 AND $k==1 AND $label7==2)
        {      
          $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label6."&nbsp;&nbsp;</label>";          
          $label6=$label6+1;      
        }
        else 
        if ($label6>=31 and $label6<=38 AND $k==0  AND $label7==2)
        {    
          $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label6."\">&nbsp;";           
          $label6=$label6+1;
        }
        else
        if ($label8>=71 and $label8<=75 AND $k==0 AND $label7==2)
        {     
          $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label8."&nbsp;&nbsp;</label>";          
          $label8=$label8+1;      
        }
        else 
        if ($label8>=71 and $label8<=75 AND $k==1  AND $label7==2)
        {    
          $this->salida.= "<input type=checkbox name=\"tipoubicpb".$label8."\">&nbsp;";           
          $label8=$label8+1;
        }
          $i++;          
          //SALTOS DE LINEA
          if ($n[$l]==5 AND $label==50 AND $k==0)
          {
            $this->salida.= "<BR>";     
            $i=0;      
            $k=1;
            $label=55;    
          }
          else
          if ($n[$l]==5 AND $label==50 AND $k==1)
          {
            $this->salida.= "<BR>";     
            $label=0;   
            $k=0;               
          }
          else
          if ($n[$l]==8 AND $label3==10 AND $k==0 AND $l==1)
          { 
            $this->salida.= "<BR>";     
            $i=0;      
            $k=1;
            $label3=18;  
                               
          }
          else
          if ($n[$l]==8 AND $label3==10 AND $k==1)
          { 
            $this->salida.= "<BR>";     
            $i=0;      
            $label3=0;        
            $k=0;  
          }
          else
          if ($n[$l]==5 AND $label2==66 AND $k==0)
          {
            $this->salida.= "<BR>";     
            $i=0;      
            $k=1;
            $label2=61;    
          }
          else
          if ($n[$l]==5 AND $label2==66 AND $k==1)
          {
            $this->salida.= "<BR>";     
            $label2=0;   
            $k=0;                        
          }                    
          else          
          if ($n[$l]==8 AND $label4==29 AND $k==0)          
          {
            $this->salida.= "<BR>";           
            $i=0;      
            $k=1;
            $label4=21;        
          }
          else
          if ($n[$l]==8 AND $label4==29 AND $k==1)
          { 
            $this->salida.= "<BR>";           
            $i=0;      
            $label4=0;
          }
          else
          if ($n[$l]==8 AND $label5==40 AND $k==0)
          { 
            $this->salida.= "<BR>";   
            $k=1;
            $i=0;      
            $label5=48;     
          }
          else
          if ($n[$l]==5 AND $label7==80 AND $k==1)
          {
            $this->salida.= "<BR>";     
            $i=0;      
            $k=0;
            $label7=85;    
          }
          else
          if ($n[$l]==5 AND $label7==80 AND $k==0)
          {
            $label7=0;   
          }                    
          else
          if ($label7==2 AND $label6==39 AND $k==1)
          {
            $this->salida.= "<BR>";   
            $i=0;      
            $k=0;
            $label6=31;        
          }
          else
          if ($label7==2  AND $label6==39 AND $k==0)
          {
            $this->salida.= "<BR>";   
            $i=0;      
            $label6=0;        
          }
          else
          if ($label8==76 AND $k==0)
          {
            $this->salida.= "<BR>";   
            $i=0;      
            $k=1;
            $label8=71;        
          }
          else
          if ($label8==76 AND $k==1)
          {  
            $this->salida.= "<BR>";   
            $i=0;      
            $label8=0;        
          }
       }
       $l++;
       $i=0;
     }   
    //FIN CONTROL ETIQUETAS    
    $this->salida.="</td>";
    $this->salida.="</tr>";
    $this->salida.="  <tr>";
    $this->salida.="  <td width=\"50%\" align=\"center\" colspan=\"2\">";
    $busquedas=$this->BuscarTipoCuadrantes();
    $this->salida.="  <select name=\"tipocuadpb".$pfj."\" class=\"select\">";
    for($i=0;$i<sizeof($busquedas);$i++)
    {
      if($busquedas[$i]['indice_orden']==1)
      {
        $this->salida.="  <option value=\"".$busquedas[$i]['hc_tipo_cuadrante_id']."".','."0".','."0\" selected>".$busquedas[$i]['descripcion']."</option>";
      }
      else
      {
        $this->salida.="  <option value=\"".$busquedas[$i]['hc_tipo_cuadrante_id']."".','."0".','."0\">".$busquedas[$i]['descripcion']."</option>";
      }
    }
    $this->salida.="  <option value=\"1".','."3".','."0\">VESTIBULAR - LINGUAL</option>";
    $this->salida.="  <option value=\"1".','."2".','."0\">VESTIBULAR - PALATINO</option>";
    $this->salida.="  <option value=\"1".','."4".','."0\">VESTIBULAR - MESIAL</option>";
    $this->salida.="  <option value=\"1".','."5".','."0\">VESTIBULAR - DISTAL</option>";
    $this->salida.="  <option value=\"1".','."3".','."4\">VESTIBULAR - LINGUAL - MESIAL</option>";
    $this->salida.="  <option value=\"1".','."3".','."5\">VESTIBULAR - LINGUAL - DISTAL</option>";
    $this->salida.="  <option value=\"1".','."2".','."4\">VESTIBULAR - PALATINO - MESIAL</option>";
    $this->salida.="  <option value=\"1".','."2".','."5\">VESTIBULAR - PALATINO - DISTAL</option>";
    $this->salida.="  <option value=\"1".','."4".','."5\">VESTIBULAR - MESIAL - DISTAL</option>";
    $this->salida.="  <option value=\"3".','."4".','."0\">LINGUAL - MESIAL</option>";
    $this->salida.="  <option value=\"3".','."5".','."0\">LINGUAL - DISTAL</option>";
    $this->salida.="  <option value=\"3".','."4".','."5\">LINGUAL - MESIAL - DISTAL</option>";
    $this->salida.="  <option value=\"2".','."4".','."0\">PALATINO - MESIAL</option>";
    $this->salida.="  <option value=\"2".','."5".','."0\">PALATINO - DISTAL</option>";
    $this->salida.="  <option value=\"2".','."4".','."5\">PALATINO - MESIAL - DISTAL</option>";
    $this->salida.="  <option value=\"4".','."5".','."0\">MESIAL - DISTAL</option>";
    $this->salida.="  </select>";
    $this->salida.="</table>";        
    $this->salida.="  </tr>";
    $this->salida.="  </td>";
    
/*		$this->salida.="	</td>";
		$this->salida.="	<td width=\"50%\" align=\"left\">";*/
/*		$busquedas=$this->BuscarTipoCuadrantes();
		$this->salida.="	<select name=\"tipocuadpb".$pfj."\" class=\"select\">";
		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1)
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_cuadrante_id']."".','."0".','."0\" selected>".$busquedas[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['hc_tipo_cuadrante_id']."".','."0".','."0\">".$busquedas[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="	<option value=\"1".','."3".','."0\">VESTIBULAR - LINGUAL</option>";
		$this->salida.="	<option value=\"1".','."2".','."0\">VESTIBULAR - PALATINO</option>";
		$this->salida.="	<option value=\"1".','."4".','."0\">VESTIBULAR - MESIAL</option>";
		$this->salida.="	<option value=\"1".','."5".','."0\">VESTIBULAR - DISTAL</option>";
		$this->salida.="	<option value=\"1".','."3".','."4\">VESTIBULAR - LINGUAL - MESIAL</option>";
		$this->salida.="	<option value=\"1".','."3".','."5\">VESTIBULAR - LINGUAL - DISTAL</option>";
		$this->salida.="	<option value=\"1".','."2".','."4\">VESTIBULAR - PALATINO - MESIAL</option>";
		$this->salida.="	<option value=\"1".','."2".','."5\">VESTIBULAR - PALATINO - DISTAL</option>";
		$this->salida.="	<option value=\"1".','."4".','."5\">VESTIBULAR - MESIAL - DISTAL</option>";
		$this->salida.="	<option value=\"3".','."4".','."0\">LINGUAL - MESIAL</option>";
		$this->salida.="	<option value=\"3".','."5".','."0\">LINGUAL - DISTAL</option>";
		$this->salida.="	<option value=\"3".','."4".','."5\">LINGUAL - MESIAL - DISTAL</option>";
		$this->salida.="	<option value=\"2".','."4".','."0\">PALATINO - MESIAL</option>";
		$this->salida.="	<option value=\"2".','."5".','."0\">PALATINO - DISTAL</option>";
		$this->salida.="	<option value=\"2".','."4".','."5\">PALATINO - MESIAL - DISTAL</option>";
		$this->salida.="	<option value=\"4".','."5".','."0\">MESIAL - DISTAL</option>";
		$this->salida.="	</select>";*/
		//$this->salida.="	</td>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table width=\"10%\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="</form>";
		if(!empty($odontograma))
		{
			$this->salida.="<table width=\"65%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"16%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"46%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"28%\" align=\"center\">";
			$this->salida.="ACCION";
			$this->salida.="</td>";
			$this->salida.="<td width=\"10%\" align=\"center\">";
			$this->salida.="FECHA REGISTRO";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($odontograma);
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
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar','odondetadi'.$pfj=>$odontograma[$i]['hc_indice_ipb_oleary_trata_detalle_id']));
				$this->salida.="<a href=\"$accion\">ELIMINAR</a>";
				$this->salida.="</td>";
				$fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$fecha_registro[0]."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
		}
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$datos=$this->BuscarIPBOlearyConsulta();
		if($datos===false)
		{
			return false;
		}
		if(sizeof($datos)!=0)
		{
			$randon=rand();
			$randon=$this->ingreso._.$randon;
			IncludeLib("jpgraph/IPBOleary_graphic");
			$valoracion=$this->BuscarEnviarPintarOlearyConsulta();
			$nopresentes=$this->BuscarEnviarPintarNoBocaConsulta();
			$valoracio2=$this->BuscarEnviarPintarOlearyTraConsulta();
			$nopresente2=$this->BuscarEnviarPintarNoBocaTraConsulta($nopresentes);
			$RutaImg=IPBOleary($valoracion,$randon,$nopresentes,1);
			$RutaImg2=IPBOleary($valoracio2,$randon,$nopresente2,2);
			$this->salida.="<br>";
			$this->salida.="<table border=\"1\" width=\"100%\" class=\"hc_table_submodulo_list\" align=\"center\">";
			$this->salida.="<tr>";
			$this->salida.="<td colspan=\"2\" align=\"center\" class='hc_table_submodulo_list_title'>";
			$this->salida.="<label class=\"label\">DIAGRAMA DE IPB O'LEARY DE PRIMERA VEZ</label>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td colspan=\"2\" align=\"center\">";
			$this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td colspan=\"2\" align=\"center\">";
			$indice=$this->CalcularIPBOlearyConsulta();
			$this->salida.="<label class=\"label\">PLACA BACTERIANA ANTERIOR: ".number_format(($indice), 2, ',', '.')." %</label>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td colspan=\"2\" align=\"center\" class='hc_table_submodulo_list_title'>";
			$this->salida.="<label class=\"label\">DIAGRAMA DE IPB O'LEARY DE TRATAMIENTO</label>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td colspan=\"2\" align=\"center\">";
			$this->salida.="<img src=\"".$RutaImg2."\" border=\"0\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td colspan=\"2\" align=\"center\">";
			$indice=$this->CalcularIPBOlearyTraConsulta();
			$this->salida.="<label class=\"label\">PLACA BACTERIANA ANTERIOR: ".number_format(($indice), 2, ',', '.')." %</label>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=hc_table_list_title>";
			$this->salida.="<td width=\"45%\" align=\"center\">";
			$this->salida.="DIENTE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"45%\" align=\"center\">";
			$this->salida.="SUPERFICIE";
			$this->salida.="</td>";
			$this->salida.="<td width=\"10%\" align=\"center\">";
			$this->salida.="FECHA REGISTRO";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($datos);
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
				$this->salida.="".$datos[$i]['hc_tipo_ubicacion_diente_id']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$datos[$i]['des1']."";
				$this->salida.="</td>";
				$fecha_registro=explode(' ',$datos[$i]['fecha_registro']);
				$this->salida.="<td align=\"center\">";
				$this->salida.="".$fecha_registro[0]."";
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
		$datos=$this->BuscarIPBOlearyConsulta();
		if($datos===false)
		{
			return false;
		}
		if(sizeof($datos)!=0)
		{
			$randon=rand();
			$randon=$this->ingreso._.$randon;
			IncludeLib("jpgraph/IPBOleary_graphic");
			$valoracion=$this->BuscarEnviarPintarOlearyConsulta();
			$nopresentes=$this->BuscarEnviarPintarNoBocaConsulta();
			$valoracio2=$this->BuscarEnviarPintarOlearyTraConsulta();
			$nopresente2=$this->BuscarEnviarPintarNoBocaTraConsulta($nopresentes);
			$RutaImg=IPBOleary($valoracion,$randon,$nopresentes,1);
			$RutaImg2=IPBOleary($valoracio2,$randon,$nopresente2,2);
			$salida.="<table border=\"1\" width=\"100%\" class=\"hc_table_submodulo_list\" align=\"center\">";
			$salida.="<tr>";
			$salida.="<td colspan=\"2\" align=\"center\" class='hc_table_submodulo_list_title'>";
			$salida.="<label class=\"label\">DIAGRAMA DE IPB O'LEARY DE PRIMERA VEZ</label>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td colspan=\"2\" align=\"center\">";
			$salida.="<img src=\"".$RutaImg."\" border=\"0\">";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td colspan=\"2\" align=\"center\">";
			$indice=$this->CalcularIPBOlearyConsulta();
			$salida.="<label class=\"label\">PLACA BACTERIANA ANTERIOR: ".number_format(($indice), 2, ',', '.')." %</label>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td colspan=\"2\" align=\"center\" class='hc_table_submodulo_list_title'>";
			$salida.="<label class=\"label\">DIAGRAMA DE IPB O'LEARY DE TRATAMIENTO</label>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td colspan=\"2\" align=\"center\">";
			$salida.="<img src=\"".$RutaImg2."\" border=\"0\">";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td colspan=\"2\" align=\"center\">";
			$indice=$this->CalcularIPBOlearyTraConsulta();
			$salida.="<label class=\"label\">PLACA BACTERIANA ANTERIOR: ".number_format(($indice), 2, ',', '.')." %</label>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=hc_table_list_title>";
			$salida.="<td width=\"45%\" align=\"center\">";
			$salida.="DIENTE";
			$salida.="</td>";
			$salida.="<td width=\"45%\" align=\"center\">";
			$salida.="SUPERFICIE";
			$salida.="</td>";
			$salida.="<td width=\"10%\" align=\"center\">";
			$salida.="FECHA REGISTRO";
			$salida.="</td>";
			$salida.="</tr>";
			$ciclo=sizeof($datos);
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
				$salida.="<td align=\"center\">";
				$salida.="".$datos[$i]['hc_tipo_ubicacion_diente_id']."";
				$salida.="</td>";
				$salida.="<td align=\"center\">";
				$salida.="".$datos[$i]['des1']."";
				$salida.="</td>";
				$fecha_registro=explode(' ',$datos[$i]['fecha_registro']);
				$salida.="<td align=\"center\">";
				$salida.="".$fecha_registro[0]."";
				$salida.="</td>";
				$salida.="</tr>";
			}
			$salida.="</table>";
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
