<?php
/**
* Submodulo para la Generacion de Incapacidades Medicas.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Generacion_Incapacidades_HTML.php,v 1.11 2008/07/18 21:22:45 cahenao Exp $
*/

class Generacion_Incapacidades_HTML extends Generacion_Incapacidades
{
  //cor - clzc - ads
  function Generacion_Incapacidad_HTML()
  {
   $this->Generacion_Incapacidades();//constructor del padre
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
/////////////////////
  
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
     
     //cor - clzc - ads
     function frmForma($vectorD)
	{
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
			$this->salida= ThemeAbrirTablaSubModulo('GENERACION DE INCAPACIDAD MEDICA');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_incapacidad'));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$vector1=$this->Consulta_Incapacidades_Generadas();
		if($vector1)
		{
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"7\">INCAPACIDADES MEDICAS GENERADAS</td>";
               $this->salida.="</tr>";	
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"5%\">No. EVOLUCION</td>";
               $this->salida.="  <td width=\"35%\">OBSERVACION DE LA INCAPACIDAD</td>";
               $this->salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
               $this->salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
               $this->salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
               $this->salida.="  <td colspan = 2 width=\"10%\">OPCION</td>";
               $this->salida.="</tr>";

               for($i=0;$i<sizeof($vector1);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"center\" width=\"5%\">".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"35%\">".$vector1[$i][observacion_incapacidad]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][tipo_incapacidad_descripcion]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][dias_de_incapacidad]."</td>";
                    $fecha = $this->FechaStamp($vector1[$i][fecha_inicio]);
                    //$b = $this->HoraStamp($vector1[$i][fecha_inicio]);
                    //$fecha = $a.' - '.$b;
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$fecha."</td>";
                    //condicion que controla que se eliminan o modiquen solo las solicitudes de esa evolucion
                    if($vector1[$i][evolucion_id] == $this->evolucion)
                    {
                         $x = explode('-',$vector1[$i][fecha_inicio]);
                         $vector1[$i][fecha_inicio]=$x[2].'/'.$x[1].'/'.$x[0];
                         $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'consultar_incapacidad', 'tipo_incapacidad'.$pfj => $vector1[$i][tipo_incapacidad_id],'observacion_incapacidad'.$pfj => $vector1[$i][observacion_incapacidad], 'dias_incapacidad'.$pfj => $vector1[$i][dias_de_incapacidad],'clase'.$pfj => $vector1[$i][tipo_atencion_incapacidad_id],'prorroga'.$pfj => $vector1[$i][sw_prorroga],'fechainicio'.$pfj => $vector1[$i][fecha_inicio],'id_incapacidad'.$pfj => $vector1[$i][hc_incapacidad_id],'codigo'.$pfj => $vector1[$i][diagnostico_id]));
                         $this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0' title=\"Modificar\"></a></td>";
                         $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_incapacidad','id_incapacidad'.$pfj=>$vector1[$i][hc_incapacidad_id]));
                         $this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion2'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                    }
                    else
                    {
                              $this->salida.="  <td colspan=\"2\" align=\"center\" width=\"10%\">&nbsp;</td>";
                    }
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"100%\" colspan=\"7\"><b>ORDENÓ:  ".$vector1[$i][nombre]." - ".$vector1[$i][especialidad]."</b></td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
		}
		else
		{
			if( $i % 2){ $estilo='modulo_list_claro';}
			else {$estilo='modulo_list_oscuro';}

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">GENERAR INCAPACIDAD</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" width=\"20%\" class=".$this->SetStyle("tipo_incapacidad").">TIPO DE INCAPACIDAD</td>";
			$this->salida.="<td width=\"40%\" align='lef'>";
			$this->salida.="<select size = 1 name = 'tipo_incapacidad$pfj'  class =\"select\">";
			$this->salida.="<option value = -1 selected>Seleccione</option>";
			$tipos_incapacidad = $this->tipos_incapacidad();
               for($i=0;$i<sizeof($tipos_incapacidad);$i++)
               {
                    if (($_REQUEST['tipo_incapacidad'.$pfj])  != $tipos_incapacidad[$i][tipo_incapacidad_id])
                    {
                         $this->salida.="<option value = ".$tipos_incapacidad[$i][tipo_incapacidad_id].">".$tipos_incapacidad[$i][descripcion]."</option>";
                    }
                    else
                    {
                         $this->salida.="<option value = ".$tipos_incapacidad[$i][tipo_incapacidad_id]." selected >".$tipos_incapacidad[$i][descripcion]."</option>";
                    }
               }
			$this->salida.="</select>";
			$this->salida.="</td>";			
	
			//--------fecha inicio
			if(empty($_REQUEST['fechainicio'.$pfj]))
			{  $_REQUEST['fechainicio'.$pfj]=date('d/m/Y');  }
			$this->salida .= "      <td width=\"50%\" class=\"label\"> FECHA INICIO: <input size=\"10\" type=\"text\" name=\"fechainicio$pfj\" value=\"".$_REQUEST['fechainicio'.$pfj]."\" class=\"input-text\">";
			$this->salida .= "      ".ReturnOpenCalendario("formades$pfj","fechainicio$pfj",'/')."</td>";
			//-------- fin fecha inicio
			
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="  <td width=\"20%\" class=".$this->SetStyle("dias_incapacidad").">NUMERO DE DIAS DE INCAPACIDAD</td>";
			$this->salida .="<td width=\"20%\" align=\"left\"><input type='text' class='input-text'  size = 5 maxlength= 3 name = 'dias_incapacidad$pfj'  value =\"".$_REQUEST['dias_incapacidad'.$pfj]."\"    ></td>" ;
			//cambio dar
			$this->salida.="  <td class=".$this->SetStyle("prorroga").">&nbsp; PRORROGA :   ";
			if(empty($_REQUEST['prorroga'.$pfj]) OR $_REQUEST['prorroga'.$pfj]===0)
			{  $this->salida.="    NO <input type=\"radio\" name=\"prorroga$pfj\" value=\"0\" checked>";  }
			else
			{  $this->salida.="    NO <input type=\"radio\" name=\"prorroga$pfj\" value=\"0\">";  }
			if($_REQUEST['prorroga'.$pfj]==1)
			{  $this->salida.=" SI <input type=\"radio\" name=\"prorroga$pfj\" value=\"1\" checked>";  }
			else
			{  $this->salida.=" SI <input type=\"radio\" name=\"prorroga$pfj\" value=\"1\" >";  }
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$clase=$this->BuscarClaseAtencion();
			if(!empty($clase))
			{
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td align=\"left\" width=\"20%\" class=".$this->SetStyle("clase").">CLASE ATENCION: </td>";
                    $this->salida.="<td width=\"60%\" align='lef' colspan=\"2\">";
                    $this->salida.="<select size = 1 name = 'clase$pfj'  class =\"select\">";
                    $this->salida.="<option value = -1 selected>Seleccione</option>";
                    for($i=0;$i<sizeof($clase);$i++)
                    {
                         if (($_REQUEST['clase'.$pfj])  != $clase[$i][tipo_atencion_incapacidad_id])
                         {
                              $this->salida.="<option value = ".$clase[$i][tipo_atencion_incapacidad_id].">".$clase[$i][descripcion]."</option>";
                         }
                         else
                         {
                              $this->salida.="<option value = ".$clase[$i][tipo_atencion_incapacidad_id]." selected >".$clase[$i][descripcion]."</option>";
                         }
                    }
                    $this->salida.="</select>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
			}
			else
			{  $this->salida .= "<input type=\"hidden\" name=\"clase$pfj\" value=\"NULL\" class=\"input-text\" >";   }
			//fin cambio dar

			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" width=\"20%\">OBSERVACION DE LA INCAPACIDAD</td>";
			$this->salida.="<td width=\"60%\" align=\"left\" colspan=\"2\"><textarea style = \"width:100%\" class='textarea' name = 'observacion_incapacidad$pfj' cols = 45 rows = 5>".$_REQUEST['observacion_incapacidad'.$pfj]."</textarea></td>" ;
			$this->salida.="</tr>";
			/*$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";*/
			
			//si esta llena hay diagnosticos      
			if(!empty($_SESSION['DIAGNOSTICOS'.$pfj]))
			{
                    /*$this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
                    $this->salida.="<td colspan = 2 width=\"65%\">";
                    $this->salida.="<table width=\"100%\">";
                    foreach( $_SESSION['DIAGNOSTICOS'.$pfj]as $k => $vector)
                    {
                      foreach($vector as $v => $tipo)
                      {
                         $this->salida.="<tr class=\"$estilo\">";
                         $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico', 'codigo'.$pfj => $k,
                         'tipo_incapacidad'.$pfj=>$_REQUEST['tipo_incapacidad'.$pfj],'dias_incapacidad'.$pfj=>$_REQUEST['dias_incapacidad'.$pfj],
                         'clase'.$pfj=>$_REQUEST['clase'.$pfj],'observacion_incapacidad'.$pfj=>$_REQUEST['observacion_incapacidad'.$pfj],
                         'prorroga'.$pfj=>$_REQUEST['prorroga'.$pfj],'fechainicio'.$pfj=>$_REQUEST['fechainicio'.$pfj]));
                         $this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                         $this->salida.="<td align=\"left\">".$k."</td>";
                         $this->salida.="<td align=\"left\">".$v."</td>";
                         $this->salida.="<tr>";
                      }   
                    }
                    $this->salida.="</table>";                    
                    $this->salida .="</td>" ;
                    $this->salida.="</tr>";		
                    */
                    $this->salida.="<tr class=\"$estilo\">";                    
                    $this->salida.="<td colspan = 3 width=\"100%\">";
                    $this->salida.="<table  align=\"center\" border=\"0\" width=\"98%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="<td align=\"center\" colspan=\"6\">DIAGNOSTICOS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";                    
                    $this->salida.="<td width=\"10%\">TIPO DX</td>";
                    $this->salida.="<td width=\"8%\">CODIGO</td>";
                    $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
                    $this->salida.="<td width=\"7%\">ELIMINAR</td>";                    
                    $this->salida.="</tr>";		
                    $estilo='modulo_list_claro';                    
                    $this->salida.="<tr class=\"$estilo\">";                                     
                    foreach($_SESSION['DIAGNOSTICOS'.$pfj] as $k => $vector)
                    {
                      if(!empty($k)){
                      foreach($vector as $tipo => $v)
                      {
                        if($tipo == '1'){
                          $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                        }elseif($tipo == '2'){
                          $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                        }else{
                          $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                        }            
                        $this->salida.="<td width=\"8%\" align=\"center\" class=\"$estilo\">".$k."</td>";
                        $this->salida.="<td width=\"60%\" align=\"left\" class=\"$estilo\">".$v."</td>";
                        $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico', 'codigo'.$pfj => $k,
                        'tipo_incapacidad'.$pfj=>$_REQUEST['tipo_incapacidad'.$pfj],'dias_incapacidad'.$pfj=>$_REQUEST['dias_incapacidad'.$pfj],
                        'clase'.$pfj=>$_REQUEST['clase'.$pfj],'observacion_incapacidad'.$pfj=>$_REQUEST['observacion_incapacidad'.$pfj],
                        'prorroga'.$pfj=>$_REQUEST['prorroga'.$pfj],'fechainicio'.$pfj=>$_REQUEST['fechainicio'.$pfj]));
                        $this->salida.="  <td align=\"center\" width=\"7%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="<td align=\"left\" colspan=\"6\" valign=\"top\">&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
                        $this->salida.="</tr>";
                      }
                      }
                    }                        
                    $this->salida.="</table>";          
                    $this->salida .="</td>" ;
                    $this->salida.="</tr>";   			
			}
			$this->salida.="</table>";			
			//----------------nuevo dar diagnosticos
			if(empty($_SESSION['DIAGNOSTICOS'.$pfj]))
			{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"4%\">CODIGO:</td>";
				$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
				$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'></td>" ;
				$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				if ($vectorD)
				{
                         $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                         $this->salida.="<tr class=\"modulo_table_title\">";
                         $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
                         $this->salida.="</tr>";
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td width=\"10%\">CODIGO</td>";
                         $this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
                         $this->salida.="  <td width=\"25%\">TIPO DX</td>";
                         $this->salida.="  <td width=\"5%\">OPCION</td>";
                         $this->salida.="</tr>";
                         for($i=0;$i<sizeof($vectorD);$i++)
                         {
                              $codigo          = $vectorD[$i][diagnostico_id];
                              $diagnostico    = $vectorD[$i][diagnostico_nombre];
                              if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
                              $this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
                              $this->salida.="<td align=\"center\" width=\"17%\">";
                              $this->salida.="<input type=\"radio\" name=\"dx$codigo$pfj\" value=\"1\" checked>&nbsp;ID&nbsp;&nbsp;";
                              $this->salida.="<input type=\"radio\" name=\"dx$codigo$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
                              $this->salida.="<input type=\"radio\" name=\"dx$codigo$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
                              $this->salida.="  <td align=\"center\" width=\"5%\"><input type = 'radio' name= 'opD$pfj' value = \"".$codigo."||".$diagnostico."\"></td>";
                              $this->salida.="</tr>";
                         }
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"left\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
                         $this->salida.="</tr>";
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardardiag$pfj\" type=\"submit\" value=\"INSERTAR DIAGNOSTICO\"></td>";
                         $this->salida.="</tr>";
                         $this->salida.="</table><br>";
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
			}			
			//----------------fin nuevo dar diagnosticos		
			$this->salida.="<table align=\"center\" width=\"40%\">";					
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR INCAPACIDAD\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";									
			$this->salida.="</form>";								
		}
		//FIN DE LA INSERCCION
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


     //cor - clzc - ads
     function frmForma_Modificar_Observacion($vectorD)
     {
          $pfj=$this->frmPrefijo;
          $this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DE LA INCAPACIDAD GENERADA');
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar_incapacidad','id_incapacidad'.$pfj =>$_REQUEST['id_incapacidad'.$pfj]));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"3\">MODIFICACION DE LA INCAPACIDAD GENERADA</td>";
          $this->salida.="</tr>";
          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td  width=\"25%\" colspan=\"2\">EVOLUCION</td>";
          $this->salida.="  <td align=\"center\" width=\"55%\">".$this->evolucion."</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td align=\"left\" width=\"25%\" class=".$this->SetStyle("tipo_incapacidad").">TIPO DE INCAPACIDAD</td>";
          $this->salida.="<td width=\"30%\" align='lef'";
          $this->salida.="<select size = 1 name = 'tipo_incapacidad$pfj'  class =\"select\">";
          $this->salida.="<option value = -1 selected>Seleccione</option>";
          $tipos_incapacidad = $this->tipos_incapacidad();
          for($i=0;$i<sizeof($tipos_incapacidad);$i++)
          {
               if (($_REQUEST['tipo_incapacidad'.$pfj])  != $tipos_incapacidad[$i][tipo_incapacidad_id])
               {
                    $this->salida.="<option value = ".$tipos_incapacidad[$i][tipo_incapacidad_id].">".$tipos_incapacidad[$i][descripcion]."</option>";
               }
               else
               {
                    $this->salida.="<option value = ".$tipos_incapacidad[$i][tipo_incapacidad_id]." selected >".$tipos_incapacidad[$i][descripcion]."</option>";
               }
          }
          $this->salida.="</select>";
          $this->salida.="</td>";
          //--------fecha inicio
          $this->salida .= "      <td width=\"60%\" class=\"label\"> FECHA INICIO: <input size=\"10\" type=\"text\" name=\"fechainicio$pfj\" value=\"".$_REQUEST['fechainicio'.$pfj]."\" class=\"input-text\">";
          $this->salida .= "      ".ReturnOpenCalendario("formades$pfj","fechainicio$pfj",'/')."</td>";
          //-------- fin fecha inicio							
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td width=\"25%\" class=".$this->SetStyle("dias_incapacidad").">NUMERO DE DIAS DE INCAPACIDAD</td>";
          $this->salida .="<td width=\"25%\" align='lef'><input type='text' class='input-text'  size = 5 maxlength= 3 name = 'dias_incapacidad$pfj'  value =\"".$_REQUEST['dias_incapacidad'.$pfj]."\"    ></td>" ;
          //cambio dar
          $this->salida.="  <td width=\"60%\" class=".$this->SetStyle("prorroga").">&nbsp; PRORROGA :   ";
          if(empty($_REQUEST['prorroga'.$pfj]) OR $_REQUEST['prorroga'.$pfj]===0)
          {  $this->salida.="    NO <input type=\"radio\" name=\"prorroga$pfj\" value=\"0\" checked>";  }
          else
          {  $this->salida.="    NO <input type=\"radio\" name=\"prorroga$pfj\" value=\"0\">";  }
          if($_REQUEST['prorroga'.$pfj]==1)
          {  $this->salida.=" SI <input type=\"radio\" name=\"prorroga$pfj\" value=\"1\" checked>";  }
          else
          {  $this->salida.=" SI <input type=\"radio\" name=\"prorroga$pfj\" value=\"1\" >";  }
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $clase=$this->BuscarClaseAtencion();
          if(!empty($clase))
          {
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td align=\"left\" width=\"20%\" class=".$this->SetStyle("clase").">CLASE ATENCION: </td>";
               $this->salida.="<td  align='lef' colspan=\"2\">";
               $this->salida.="<select size = 1 name = 'clase$pfj'  class =\"select\">";
               $this->salida.="<option value = -1 selected>Seleccione</option>";
               for($i=0;$i<sizeof($clase);$i++)
               {
                    if (($_REQUEST['clase'.$pfj])  != $clase[$i][tipo_atencion_incapacidad_id])
                    {
                         $this->salida.="<option value = ".$clase[$i][tipo_atencion_incapacidad_id].">".$clase[$i][descripcion]."</option>";
                    }
                    else
                    {
                         $this->salida.="<option value = ".$clase[$i][tipo_atencion_incapacidad_id]." selected >".$clase[$i][descripcion]."</option>";
                    }
               }
               $this->salida.="</select>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }
          else
          {  $this->salida .= "<input type=\"hidden\" name=\"clase$pfj\" value=\"NULL\" class=\"input-text\" >";   }
          //fin cambio dar
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td width=\"25%\" align='left' >OBSERVACION DE LA INCAPACIDAD</td>";
          $this->salida.="<td width=\"55%\" align='left' colspan=\"2\"><textarea style = \"width:100%\" class='textarea' name = 'observacion_incapacidad$pfj' cols = 45 rows = 5>".$_REQUEST['observacion_incapacidad'.$pfj]."</textarea></td>" ;
          $this->salida.="</tr>";
                         
          //si esta llena hay diagnosticos
          $diag=$this->DiagnosticosIncapcidad();
          if(!empty($diag))
          {
               $this->salida.="<tr class=\"$estilo\">";               
               $this->salida.="<td colspan = 3 width=\"65%\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"98%\">";
                $this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="<td align=\"center\" colspan=\"6\">DIAGNOSTICOS</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";                    
                $this->salida.="<td width=\"10%\">TIPO DX</td>";
                $this->salida.="<td width=\"8%\">CODIGO</td>";
                $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
                $this->salida.="<td width=\"7%\">ELIMINAR</td>";                    
                $this->salida.="</tr>";   
                $estilo='modulo_list_claro';                    
                $this->salida.="<tr class=\"$estilo\">";                                    
                if($diag['tipo_diagnostico'] == '1'){
                  $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                }elseif($diag['tipo_diagnostico'] == '2'){
                  $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                }else{
                  $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                }            
                $this->salida.="<td width=\"8%\" align=\"center\">".$diag['diagnostico_id']."</td>";
                $this->salida.="<td width=\"60%\" align=\"left\">".$diag['diagnostico_nombre']."</td>";
                $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico_real', 'codigo'.$pfj => $diag[diagnostico_id],
                'tipo_incapacidad'.$pfj=>$_REQUEST['tipo_incapacidad'.$pfj],'dias_incapacidad'.$pfj=>$_REQUEST['dias_incapacidad'.$pfj],
                'clase'.$pfj=>$_REQUEST['clase'.$pfj],'observacion_incapacidad'.$pfj=>$_REQUEST['observacion_incapacidad'.$pfj],
                'prorroga'.$pfj=>$_REQUEST['prorroga'.$pfj],'fechainicio'.$pfj=>$_REQUEST['fechainicio'.$pfj]));
                $this->salida.="  <td align=\"center\" width=\"7%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="<td align=\"left\" colspan=\"6\" valign=\"top\">&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
                $this->salida.="</tr>";                    
                $this->salida.="</table>";   
                $this->salida.="</td></tr>";    
               /*$this->salida.="<table width=\"100%\">";
               $this->salida.="<tr class=\"$estilo\">";
               $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico_real', 'codigo'.$pfj => $diag[diagnostico_id],
               'tipo_incapacidad'.$pfj=>$_REQUEST['tipo_incapacidad'.$pfj],'dias_incapacidad'.$pfj=>$_REQUEST['dias_incapacidad'.$pfj],
               'clase'.$pfj=>$_REQUEST['clase'.$pfj],'observacion_incapacidad'.$pfj=>$_REQUEST['observacion_incapacidad'.$pfj],
               'prorroga'.$pfj=>$_REQUEST['prorroga'.$pfj],'fechainicio'.$pfj=>$_REQUEST['fechainicio'.$pfj]));
               $this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
               $this->salida.="<td align=\"left\">".$diag[diagnostico_id]."</td>";
               $this->salida.="<td align=\"left\">".$diag[diagnostico_nombre]."</td>";
               $this->salida.="<tr>";
               $this->salida.="</table>";*/
          }		
          $this->salida.="</table>";
          if(empty($diag))		
          {								
               //----------------nuevo dar diagnosticos
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"4%\">CODIGO:</td>";
               $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
               $this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
               $this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'></td>" ;
               $this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";
               if ($vectorD)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"10%\">CODIGO</td>";
                    $this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
                    $this->salida.="  <td width=\"25%\">TIPO DX</td>";
                    $this->salida.="  <td width=\"5%\">OPCION</td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($vectorD);$i++)
                    {
                         $codigo          = $vectorD[$i][diagnostico_id];
                         $diagnostico    = $vectorD[$i][diagnostico_nombre];
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
                         $this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
                         $this->salida.="<td align=\"center\" width=\"17%\">";
                         $this->salida.="<input type=\"radio\" name=\"dx$codigo$pfj\" value=\"1\" checked>&nbsp;ID&nbsp;&nbsp;";
                         $this->salida.="<input type=\"radio\" name=\"dx$codigo$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
                         $this->salida.="<input type=\"radio\" name=\"dx$codigo$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
                         $this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opD$pfj' value = \"".$codigo."\"></td>";
                         $this->salida.="</tr>";
                    }
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardardiag$pfj\" type=\"submit\" value=\"INSERTAR DIAGNOSTICO\"></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
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
          }	
          //----------------fin nuevo dar diagnosticos		
          $this->salida.="<table align=\"center\" width=\"40%\">";								
          $this->salida.="<tr>";
          $this->salida .= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"MODIFICAR INCAPACIDAD\"></td>";
          $this->salida.="</tr>";					
          $this->salida.="</table><br>";
          $this->salida .= "</form>";

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
		$vector1=$this->Consulta_Incapacidades_Generadas();
		if($vector1)
          {
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">INCAPACIDADES MEDICAS GENERADAS</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"5%\">No. EVOLUCION</td>";
               $this->salida.="  <td width=\"45%\">OBSERVACION DE LA INCAPACIDAD</td>";
			$this->salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
               $this->salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
               $this->salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
               $this->salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"center\" width=\"5%\">".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"45%\">".$vector1[$i][observacion_incapacidad]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][tipo_incapacidad_descripcion]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][dias_de_incapacidad]."</td>";
                    $fecha = $this->FechaStamp($vector1[$i][fecha_inicio]);
				//$a = $this->FechaStamp($vector1[$i][fecha]);
                    //$b = $this->HoraStamp($vector1[$i][fecha]);
                    //$fecha = $a.' - '.$b;
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$fecha."</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"100%\" colspan=\"5\"><b>ORDENÓ:  ".$vector1[$i][nombre]." - ".$vector1[$i][especialidad]."</b></td>";
                    $this->salida.="</tr>";
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
		$salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$vector1=$this->Consulta_Incapacidades_Generadas();
		if($vector1)
		{
			$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="  <td align=\"center\" colspan=\"5\">INCAPACIDADES MEDICAS GENERADAS</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="  <td width=\"5%\">No. EVOLUCION</td>";
			$salida.="  <td width=\"45%\">OBSERVACION DE LA INCAPACIDAD</td>";
			$salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
			$salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
			$salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
			$salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$salida.="<tr class=\"$estilo\">";
				$salida.="  <td align=\"center\" width=\"5%\">".$vector1[$i][evolucion_id]."</td>";
				$salida.="  <td align=\"left\" width=\"45%\">".$vector1[$i][observacion_incapacidad]."</td>";
				$salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][tipo_incapacidad_descripcion]."</td>";
				$salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][dias_de_incapacidad]."</td>";
				$fecha = $this->FechaStamp($vector1[$i][fecha_inicio]);
                    //$a = $this->FechaStamp($vector1[$i][fecha]);
				//$b = $this->HoraStamp($vector1[$i][fecha]);
				//$fecha = $a.' - '.$b;
				$salida.="  <td align=\"center\" width=\"10%\">".$fecha."</td>";
				$salida.="</tr>";
				$salida.="<tr class=\"$estilo\">";
				$salida.="  <td align=\"left\" width=\"100%\" colspan=\"5\"><b>ORDENÓ:  ".$vector1[$i][nombre]." - ".$vector1[$i][especialidad]."</b></td>";
				$salida.="</tr>";
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
          if($fecha)
          {
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
	
//---------------- nuevo dar diagnosticos			
	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{		return '';		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{			$paso=1;		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'hc_os_solicitud_id'.$pfj=>$_REQUEST['hc_os_solicitud_id'.$pfj],
		'codigo_esp'.$pfj=>$_REQUEST['codigo_esp'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
		'observacion'.$pfj=>$_REQUEST['observacion'.$pfj],
		'obs'.$pfj=>$_REQUEST['obs'.$pfj]));

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
	
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}
	
//----------------fin nuevo dar diagnosticos				
	
}
?>
