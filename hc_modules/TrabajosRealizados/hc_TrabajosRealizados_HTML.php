<?php

// $Id: hc_TrabajosRealizados_HTML.php,v 1.1 2007/11/30 20:57:09 tizziano Exp $

class TrabajosRealizados_HTML extends TrabajosRealizados
{

	function TrabajosRealizados_HTML()
	{
          $this->TrabajosRealizados();//constructor del padre
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
          'autor'=>'TIZZIANO PEREA OCORO',
          'descripcion_cambio' => '',
          'requiere_sql' => false,
          'requerimientos_adicionales' => '',
          'version_kernel' => '1.0'
          );
          return $informacion;
     }

       
     function frmConsulta()
     {
          return true;
     }


	function frmHistoria()
	{
          $salida = "";
          return $salida;
     }


	function SetStyle($campo)
	{
		$pfj=$this->frmPrefijo;
		if ($this->frmError[$campo]||$campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
			  return ("<tr align=\"center\"><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}


	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		
          include_once 'hc_modules/TrabajosRealizados/RemoteXajax/TrabajosRealizados_Xajax.php';
		$objClassModules=new hc_Classmodules();
		$objClassModules->SetXajax(array("SelectTipos", "InsertarDetalle"));
     
          SessionSetVar("Ingreso",$this->ingreso);
		SessionSetVar("Evolucion",$this->evolucion);
          SessionSetVar("Usuario", UserGetUID());
		
          if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('TRABAJOS REALIZADOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$Detalles = $this->GetDatosDetalleMotivo();
          
          $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";

		$this->salida.=$this->SetStyle("MensajeError",11);
		
          if(!is_array($Detalles))
          {
               $this->salida.="<tr>";
               $this->salida.="<td width=\"100%\">";
               /**********************************/
               $this->salida.="<table width=\"85%\" border=\"0\"  class=\"modulo_table_list\" align=\"center\">";
               $this->salida.="<tr class='modulo_table_title'>";
               $this->salida.="<td align='center' colspan=\"2\">TRABAJOS REALIZADOS";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr>";
               $this->salida.="<td align='center' class='hc_submodulo_list_claro' width=\"40%\">";
               $this->salida.="<select name=\"crecimiento\" id=\"crecimiento\" class=\"select\" OnChange=\"SelectTiposCre();\">";
               $this->salida.="<option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";
               $VectorCre = $this->Get_TiposCrecimientos();
               $this->GetHtmlTiposCrecimiento($VectorCre, $_REQUEST['crecimiento']);
               $this->salida.="</select>";
               $this->salida.="</td>";
               $this->salida.="<td align='center' class='hc_submodulo_list_claro' width=\"60%\">";
               $this->salida.="<div id=\"tipos_cre\" style=\"display:none\"></div>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               /**********************************/
          }
          else
          {
               $this->salida.="<tr>";
               $this->salida.="<td width=\"100%\">";
               $this->salida.="<br><table width=\"85%\" align=\"center\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"2\">TRABAJOS REALIZADOS";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               
               foreach($Detalles AS $k => $V)
               {
                    $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
                    $this->salida.="<td align=\"center\" width=\"30%\">$k";
                    $this->salida.="</td>";
                    $this->salida.="<td align=\"left\" width=\"70%\">";
                    for($i=0; $i<sizeof($V); $i++)
                    {
                    	$this->salida.="".$V[$i][descripcion]." <br>";
                    }
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }
          
          $this->salida.="</table>";
          
          $javaC = "<script>\n";
          
          $javaC.="	    Datos = new Array();\n";
          
          $javaC.="		function SelectTiposCre()\n";
          $javaC.="		{\n";
          $javaC.="		     Tipo1 = document.getElementById('crecimiento').value;\n";
          $javaC.="		     if(Tipo1 != -1)\n";
          $javaC.="		     {\n";
          $javaC.="		     	xajax_SelectTipos(Tipo1);\n";
          $javaC.="		     }\n";
          $javaC.="		     else\n";
          $javaC.="		     {\n";
          $javaC.="		     	alert('Opción no valida!!');\n";
          $javaC.="		     }\n";
          $javaC.="		}\n";
          
          $javaC.="		function LlenarVectorDX(Code, Sw, Cat)\n";
          $javaC.="		{\n";
          $javaC.="			if(Code != '')\n";
          $javaC.="			{\n";          
          $javaC.="				if(Datos.length == 0)\n";
          $javaC.="				{\n";
          $javaC.="					Datos[0] = Code;\n";
          $javaC.="				}\n";
          $javaC.="				else\n";
          $javaC.="				{\n";
          $javaC.="					a = Datos.length ++;\n";
          $javaC.="					Datos[a] = Code;\n";
          $javaC.="				}\n";
          $javaC.="			}\n";
          $javaC.= "     	if(Sw == 1)\n";
          $javaC.= "     	{\n";
     	$javaC.= "     		xajax_InsertarDetalle(Datos, Cat);\n";
          $javaC.= "     	}\n";
          $javaC.="		}\n";
         
          $javaC.="		function RecargarPage()\n";
          $javaC.="		{\n";
		$javaC.="			location.reload();\n";          
          $javaC.="		}\n";
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
     
     
     function GetHtmlTiposCrecimiento($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[trabajo_id]==$TipoId){
                    $this->salida .=" <option align=\"center\" value=\"$titulo[trabajo_id]\" selected>$titulo[descripcion]</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"$titulo[trabajo_id]\">$titulo[descripcion]</option>";
               }
          }
     }

}
?>