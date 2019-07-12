<?php
/**
* Submodulo para la Solicitud de Procedimientos Quirurgicos.
*
* Submodulo para manejar la solicitud de procedimientos quirurgicos.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Solicitud_De_Procedimientos_Qx_HTML.php,v 1.22 2006/12/19 21:00:15 jgomez Exp $
*/
IncludeClass("ClaseHTML");
class Solicitud_De_Procedimientos_Qx_HTML extends Solicitud_De_Procedimientos_Qx
{
  //clzc -spqx
	function Solicitud_De_Procedimientos_Qx_HTML(){
		$this->Solicitud_De_Procedimientos_Qx();//constructor del padre
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


  
  //clzc -spqx
	function SetStyle($campo){
		if($this->frmError[$campo] || $campo=="MensajeError"){
				if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
		}
		return ("label");
	}


    //jea - spqx
	function CalcularNumeroPasos($conteo){
			$numpaso=ceil($conteo/$this->limit);
			return $numpaso;
	}

    //jea - spqx
	function CalcularBarra($paso){

		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
				$barra=$barra-10;
		}
		return $barra;
	}

    //jea - spqx
	function CalcularOffset($paso){
			$offset=($paso*$this->limit)-$this->limit;
			return $offset;
	}

//el titulo RetornarBarraExamnes avanzada lo cambie por RetornarBarraProcedimientos_Avanzada poor que
//necesite pegar el retornar barra de examnes avanzada de apoyos y me creaba conflicto
    //jea - spqx
	function RetornarBarraProcedimientos_Avanzada()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso)){
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargos'.$pfj=>$_REQUEST['cargos'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
				$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
			$diferencia=$numpasos-9;
			if($diferencia<=0){
					$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
						$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))	{
			if($numpasos>10){
					$valor=10+3;
			}else{
					$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}else{
			if($numpasos>10){
				$valor=10+5;
			}else{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

//clzc - spqx
  function frmForma(){

		$pfj=$this->frmPrefijo;
		unset($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset($_SESSION['APOYOS'.$pfj]);
		unset($_SESSION['PROCEDIMIENTO'.$pfj]);
		unset($_SESSION['MODIFICANDO'.$pfj]);
		unset($_SESSION['PASO']);
		unset($_SESSION['PASO1']);
		if(empty($this->titulo)){
			$this->salida = ThemeAbrirTablaSubModulo('SOLICITUD DE PROCEDIMIENTOS QUIRURGICOS');
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		if(($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2')){
			$_SESSION['PROFESIONAL'.$pfj]=1;//usuario medico
		}

		//FIN DE LA FORMA QUE LISTA LOS PROCEDIMIENTOS SOLICITADOS
		//DESDE AQUI INSERTO EL BUSCADOR AVANZADO
		if($_SESSION['PROFESIONAL'.$pfj]==1){
			if(!empty($this->plan_id)){
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
				'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
				'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
				'cargos'.$pfj=>$_REQUEST['cargos'.$pfj],
				'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
				$this->salida .= "<form name=\"forma$pfj\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO</td>";

				$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>Todos</option>";
				$categoria = $this->tipos();
				for($i=0;$i<sizeof($categoria);$i++){
					$id = $categoria[$i][tipo_cargo];
					$opcion = $categoria[$i][descripcion];
					if (($_REQUEST['criterio1'.$pfj])  != $id){
							$this->salida.="<option value = '$id'>$opcion</option>";
					}else{
						$this->salida.="<option value = '$id' selected >$opcion</option>";
					}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"10%\">CARGO:</td>";
				$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10    name = 'cargos$pfj'  value =\"".$_REQUEST['cargos'.$pfj]."\"    ></td>" ;
				$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
				$this->salida .="<td width=\"34%\" align='center'><input type='text' class='input-text'     name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar$pfj' type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$this->salida.="</form>";
				//hasta aqui lo que inserte
			}else{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr><td align=\"center\" width=\"80%\" class=\"label_mark\" >SE ESTA ABRIENDO LA HISTORIA CLINICA SIN UN PLAN Y NO ES PERMITIDO REALIZAR SOLICITUDES</td></tr>";
					$this->salida.="</table>";
			}
		}else{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr><td align=\"center\" width=\"80%\" class=\"label_mark\" >EL TIPO DE PROFESIONAL NO PERMITE GENERAR ESTE TIPO DE ORDEN MEDICA</td></tr>";
				$this->salida.="</table>";
		}
//HASTA A QUI LO QUE INSERTE DEL BUSCADOR
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }

	function frmConsultaSolicitudes($vector){
		
		$pfj=$this->frmPrefijo;
		unset($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset($_SESSION['APOYOS'.$pfj]);
		unset($_SESSION['PROCEDIMIENTO'.$pfj]);
		unset($_SESSION['MODIFICANDO'.$pfj]);
		unset($_SESSION['PASO']);
		unset($_SESSION['PASO1']);
		if(empty($this->titulo)){
			$this->salida = ThemeAbrirTablaSubModulo('SOLICITUD DE PROCEDIMIENTOS QUIRURGICOS');
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		if(($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2')){
			$_SESSION['PROFESIONAL'.$pfj]=1;//usuario medico
		}
		if($_SESSION['PROFESIONAL'.$pfj]==1){
			if(!empty($this->plan_id)){
				if($vector){					
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"2\">SOLICITUDES PROCEDIMIENTOS QX</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td width=\"15%\" align=\"center\">ACTO QX</td>";
					$this->salida.="  <td width=\"85%\">&nbsp;</td>";					
					$this->salida.="</tr>";
					$this->salida.="<tr>";          					
					$this->salida.="<td class=\"hc_table_submodulo_list_title\" width=\"15%\" valign=\"center\">- ".$vector[0]['acto_qx_id']." - <BR><BR>".$vector[0]['nombre_tercero']."<BR><BR>";
          if($vector[$i]['sw_estado']=='1'){
            $action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'','SolicitudId'.$pfj=>$vector[0]['acto_qx_id']));            
            $this->salida.="<a href=\"$action\" title=\"Modificar\"><img border = 0 src=\"".GetThemePath()."/images/pmodificar.png\"></a>";
          }
          $this->salida.="</td>";
					$this->salida.="<td width=\"85%\">";
					$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"100%\">";
					$this->salida.="		<tr class=\"modulo_table_title\">";					
					$this->salida.="  	<td width=\"15%\" align=\"center\">SOLICITUD</td>";
					$this->salida.="  	<td width=\"25%\" align=\"center\">PROFESIONAL</td>";
					$this->salida.="  	<td width=\"45%\" align=\"center\">PROCEDIMIETOS</td>";
					$this->salida.="  	<td width=\"5%\" align=\"center\">ESTADO</td>";
					$this->salida.="		</tr>";					
					for($i=0;$i<sizeof($vector);$i++){						
						$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";						
						(list($ano,$mes,$dia)=explode('-',$vector[$i]['fecha']));
						$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);						
						if($vector[$i]['sw_ambulatorio']==1){
							$this->salida.=" <td valign=\"top\" rowspan=\"".((sizeof($vector1)+1)+3)."\" align=\"center\">";					
						}else{
							$this->salida.=" <td valign=\"top\" rowspan=\"".((sizeof($vector1)+1)+2)."\" align=\"center\">";
						}						
						$this->salida.="  - ".$vector[$i]['hc_os_solicitud_id']." - <BR>".ucwords(strftime("%b %d de %Y ",$FechaConver1))."<BR>";            
						$this->salida.="   </td>";
						$this->salida.="   <td rowspan=\"".(sizeof($vector1)+1)."\" align=\"center\">".$vector[$i]['tipo']."</td>";
						$this->salida.="   <td class=\"modulo_list_claro\" align=\"left\">".$vector[$i]['cargo']." - ".$vector[$i]['descripcion']."</td>";
						if($vector[$i]['sw_estado']=='0' OR $vector[$i]['sw_estado']=='3'){
              $ref=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ConsultaAutorizacionesSolicitud','SolicitudId'.$pfj=>$vector[$i]['hc_os_solicitud_id'],
						  'CargoPrincipal'.$pfj=>$vector[$i]['cargo'],'NombreCargo'.$pfj=>$vector[$i]['descripcion'],'Tipo'.$pfj=>$vector[$i]['tipo'],"ingresoId".$pfj=>$vector[$i]['ingreso'],"EvolucionId".$pfj=>$vector[$i]['evolucion_id']));
						  //$this->salida.=" <td class=\"modulo_list_claro\" align=\"left\"><a href=\"$ref\">AUTORIZADO</a></td>";
              $this->salida.=" <td class=\"modulo_list_claro\" align=\"left\">AUTORIZADO</td>";
						}elseif($vector[$i]['sw_estado']=='2'){
              $this->salida.=" <td class=\"modulo_list_claro\" align=\"left\">ANULADA</td>";
						}elseif($vector[$i]['sw_estado']=='1'){
              $this->salida.=" <td class=\"modulo_list_claro\" align=\"left\">ACTIVA</td>";
            }
						$this->salida.="	 </tr>";						
            $this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td>OBSERVACIONES</td>";
            $this->salida.="  <td class=\"modulo_list_claro\" colspan=\"2\" align=\"left\">".$vector[$i]['observacion']."</td>";
						$this->salida.="	</tr>";
            $this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td>DIAGNOSTICOS PRESUNTIVOS</td>";
            $this->salida.="  <td class=\"modulo_list_claro\" colspan=\"2\">";
            if($dat=$this->DiagnosticosSolicitudQX($vector[$i]['hc_os_solicitud_id'])){
            $this->salida.="    <table  align=\"center\" border=\"0\"  width=\"100%\">";
            $this->salida.="    <tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="    <td width=\"10%\">PRIMARIO</td>";
            $this->salida.="    <td width=\"10%\">TIPO DX</td>";
            $this->salida.="    <td width=\"10%\">CODIGO</td>";
            $this->salida.="    <td>DIAGNOSTICO</td>";
            $this->salida.="    </tr>";
            for($l=0;$l<sizeof($dat);$l++){
              if($h % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
              $this->salida.="<tr class=\"$estilo\">";
              if($dat[$l]['sw_principal']==1){
              $this->salida.="  <td width=\"5%\" align=\"center\"><img title=\"Diagnostico Principal\" border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></td>";
              }else{
              $this->salida.="  <td width=\"5%\" align=\"center\"><img title=\"Diagnostico Principal\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></td>";
              }
              if($dat[$l]['tipo_diagnostico'] == '1'){
                $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
              }elseif($dat[$l]['tipo_diagnostico'] == '2'){
                $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
              }else{
                $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
              }
              $this->salida.="<td align=\"center\" width=\"10%\">".$dat[$l]['diagnostico_id']."</td>";
              $this->salida.="<td align=\"left\" width=\"80%\">".$dat[$l]['diagnostico_nombre']."</td>";
              $this->salida.="</tr>";
              $h++;
            }
            $this->salida.="    </table>";
            }else{
            $this->salida.="    &nbsp;";
            }
            $this->salida.="  </td>";
						$this->salida.="</tr>";						
						if($vector[$i]['sw_ambulatorio']==1){
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";					
							$this->salida.="  <td align=\"center\" class=\"modulo_list_claro\" colspan=\"3\" align=\"left\">AMBULATORIO</td>";
							$this->salida.="</tr>";
						} 						
					}					
					$this->salida.="</table>";
					
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$actionObser=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarObservacionesSolicitud','SolicitudId'.$pfj=>$vector[0]['acto_qx_id']));
					$this->salida.="  <td colspan=\"2\"><a href=\"$actionObser\" class=\"link\">OBSERVACIONES</a></td>";
					$this->salida.="</tr>";
					
					$actionNew=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'FormaPrincipal','centinela'.$pfj=>1));
					$this->salida.="<tr><td colspan=\"2\" align=\"right\"><a class=\"hcLinkClaro\" href=\"$actionNew\"><b>SOLICITAR NUEVO ACTO QX</b></a></td></tr>";
					$this->salida.="</table>";
				}
			}else{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr><td align=\"center\" width=\"80%\" class=\"label_mark\" >SE ESTA ABRIENDO LA HISTORIA CLINICA SIN UN PLAN Y NO ES PERMITIDO REALIZAR SOLICITUDES</td></tr>";
					$this->salida.="</table>";
			}
		}else{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr><td align=\"center\" width=\"80%\" class=\"label_mark\" >EL TIPO DE PROFESIONAL NO PERMITE GENERAR ESTE TIPO DE ORDEN MEDICA</td></tr>";
				$this->salida.="</table>";
		}
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}



//clzc - ads
 function frmForma_Seleccion_Avanzada($vectorA)
 {
			//PRINT_R($_REQUEST);
      $pfj=$this->frmPrefijo;
			$this->salida= ThemeAbrirTablaSubModulo('ADICION DE PROCEDIMIENTOS QUIRURGICOS');
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
			'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
			'cargos'.$pfj=>$_REQUEST['cargos'.$pfj],
			'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));

			$this->salida .= "<form name=\"forma$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO</td>";
			$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
			$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
			$this->salida.="<option value = '-1' selected>Todos</option>";
			$categoria = $this->tipos();
			for($i=0;$i<sizeof($categoria);$i++)
			{
				$id = $categoria[$i][tipo_cargo];
				$opcion = $categoria[$i][descripcion];
				if (($_REQUEST['criterio1'.$pfj])  != $id)
				{
					$this->salida.="<option value = '$id'>$opcion</option>";
				}
				else
				{
					$this->salida.="<option value = '$id' selected >$opcion</option>";
				}
			}
			$this->salida.="</select>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
     // $this->salida.="<pre>".print_r($_REQUEST['cargos'.$pfj],true)."</pre>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CARGO:</td>";
			$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10    name = 'cargos$pfj'  value =\"".$_REQUEST['cargos'.$pfj]."\"    ></td>" ;
			$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
			$this->salida .="<td width=\"34%\" align='center'><input type='text' class='input-text'     name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;
			$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar$pfj' type=\"submit\" value=\"BUSCAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		  $this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			$this->salida.="</form>";

      $this->salida .= "<form name=\"forma$pfj\" action=\"$accion\" method=\"post\">";
      if ($vectorA)
      {
      //$this->salida.="<pre>".print_r($_REQUEST['cargos'.$pfj],true)."</pre>";
				  $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"25%\">TIPO</td>";
					$this->salida.="  <td width=\"10%\">CARGO</td>";
					$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
					$this->salida.="  <td width=\"5%\">OPCION</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vectorA);$i++)
					{
						$grupo_tipo_cargo = $vectorA[$i][grupo_tipo_cargo];
						$tipo             = $vectorA[$i][tipo];
						$cargos            = $vectorA[$i][cargo];
						$descripcion      = $vectorA[$i][descripcion];

						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"center\" width=\"25%\">$tipo</td>";
						$this->salida.="  <td align=\"center\" width=\"10%\">$cargos</td>";
						$this->salida.="  <td align=\"left\" width=\"40%\">$descripcion</td>";
            $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenarprocedimiento','tipo'.$pfj=>"$tipo", 'cargos'.$pfj=>"$cargos", 'procedimiento'.$pfj=>"$descripcion"));
						$this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion1'>SOLICITAR</a></td>";
            $this->salida.="</tr>";
          }
          $this->salida.="</table><br>";
          $var=$this->RetornarBarraProcedimientos_Avanzada();
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
      $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenarprocedimiento'));
      $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
      $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name = 'volver$pfj' type=\"submit\" value=\"VOLVER\"></form></td></tr>";
      $this->salida .= ThemeCerrarTablaSubModulo();
      return true;
 }


//clzc - spqx
	function Modificar_Procedimiento_Solicitado($hc_os_solicitud_id, $vectorD){

		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('MODIFICAR PROCEDIMIENTO QUIRURGICO');
		//lo pegue yo en el proceso de la insercion del buscador
		$this->salida .= "<script>\n";
		$this->salida .= "function enviar(Of,paso){\n";
		$this->salida .= "document.formamod$pfj.Of$pfj.value=Of\n";
		$this->salida .= "document.formamod$pfj.paso1$pfj.value=paso\n";
		$this->salida .= "document.formamod$pfj.opc$pfj.value='opc'\n";
		$this->salida .= "document.formamod$pfj.submit();}\n";
		$this->salida .= "function elimdiagbd(t){\n";
		$this->salida .= "document.formamod$pfj.t$pfj.value=t;\n";
		$this->salida .= "document.formamod$pfj.eliminardiagnosticobd$pfj.value='1';\n";
		$this->salida .= "document.formamod$pfj.submit();}\n";
		$this->salida .= "</script>\n";

		$accionM=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'OpcionesModificacionProcedimiento',
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj], 'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida .= "<form name=\"formamod$pfj\" action=\"$accionM\" method=\"post\">";
		$this->salida.="  <input type='hidden' name = 'hc_os_solicitud_id$pfj'  value = '$hc_os_solicitud_id'>";
		$this->salida.="  <input type='hidden' name = 'Of$pfj'  value = ''>";
		$this->salida.="  <input type='hidden' name = 'paso1$pfj'  value = ''>";
		$this->salida.="  <input type='hidden' name = 'opc$pfj'  value = ''>";
		//fin de lo que pegue yo en el proceso de la insercion del buscador

		/*    if($_SESSION['CARGAR_DATOS_PROCEDIMIENTOS'.$pfj]!=1)
											{*/
		$vector1=$this->Consulta_Modificar_Procedimiento($hc_os_solicitud_id);
		/*$_SESSION['CARGAR_DATOS_PROCEDIMIENTOS'.$pfj] = 1;
		$_SESSION['VECTOR1'.$pfj] = $vector1;
		}
		$vector1 = $_SESSION['VECTOR1'.$pfj];*/

		if($vector1){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">DATOS DEL PROCEDIMIENTO</td>";
			$this->salida.="</tr>";
			foreach($vector1[0] as $k=>$v){
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" width=\"20%\">TIPO</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">CARGO</td>";
				$this->salida.="  <td align=\"center\" width=\"50%\">DESCRIPCION</td>";
				$this->salida.="</tr>";

				$hc_os_solicitud_id =$v[hc_os_solicitud_id];

				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}

	      //tipo, cargo, descripcion
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\" width=\"20%\">".$v[tipo]."</td>";
				$this->salida.="<td align=\"center\" width=\"10%\">".$v[cargo]."</td>";
				$this->salida.="<td align=\"left\" width=\"50%\" >".$v[descripcion]."</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
//observacion
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"30%\" align=\"left\" >OBSERVACION</td>";

				if ($_REQUEST['observacion'.$pfj]=== '' OR !empty($_REQUEST['observacion'.$pfj])){
					$this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:100%\" class='textarea' name = 'observacion$pfj' cols = 100 rows = 3>".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
				}else{
					$this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:100%\" class='textarea' name = 'observacion$pfj' cols = 100 rows = 3>".$v[observacion]."</textarea></td>" ;
				}
				$this->salida.="</tr>";

	      //tipo de cirugia
				/*$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"30%\"align=\"left\" >TIPO DE CIRUGIA</td>";
				$this->salida.="<td width=\"50%\" align = left >";
				$this->salida.="<select size = 1 name = 'cirugia$pfj'  class =\"select\">";
				$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
				$categoria = $this->tipocirugia();

				if(empty($_REQUEST['cirugia'.$pfj])){
						$_REQUEST['cirugia'.$pfj]=$v[tipo_cirugia_id];
				}

				for($i=0;$i<sizeof($categoria);$i++){
					if ($_REQUEST['cirugia'.$pfj] != $categoria[$i][tipo_cirugia_id]){
						$this->salida.="<option value = \"".$categoria[$i][tipo_cirugia_id]."\">".$categoria[$i][descripcion]."</option>";
					}else{
						$this->salida.="<option  value = \"".$categoria[$i][tipo_cirugia_id]."\" selected >".$categoria[$i][descripcion]."</option>";
					}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
	//tipo de ambito
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"30%\" align=\"left\" >AMBITO</td>";
				$this->salida.="<td width=\"50%\" align = left >";
				$this->salida.="<select size = 1 name = 'ambito$pfj'  class =\"select\">";
				$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
				$categoria = $this->tipoambito();
				if(empty($_REQUEST['ambito'.$pfj])){
						$_REQUEST['ambito'.$pfj]=$v[ambito_cirugia_id];
				}
				for($i=0;$i<sizeof($categoria);$i++){
					if ($_REQUEST['ambito'.$pfj] != $categoria[$i][ambito_cirugia_id]){
						$this->salida.="<option value = \"".$categoria[$i][ambito_cirugia_id]."\">".$categoria[$i][descripcion]."</option>";
					}else{
						$this->salida.="<option value = \"".$categoria[$i][ambito_cirugia_id]."\" selected >".$categoria[$i][descripcion]."</option>";
					}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
	//tipo de finalidad
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"30%\" align=\"left\" >FINALIDAD</td>";
				$this->salida.="<td width=\"50%\" align = left >";
				$this->salida.="<select size = 1 name = 'finalidad$pfj'  class =\"select\">";
				$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
				$categoria = $this->tipofinalidad();
				if(empty($_REQUEST['finalidad'.$pfj])){
						$_REQUEST['finalidad'.$pfj]=$v[finalidad_procedimiento_id];
				}
				for($i=0;$i<sizeof($categoria);$i++){
					if($_REQUEST['finalidad'.$pfj]  != $categoria[$i][finalidad_procedimiento_id]){
						$this->salida.="<option value = \"".$categoria[$i][finalidad_procedimiento_id]."\">".$categoria[$i][descripcion]."</option>";
					}else{
						$this->salida.="<option value = \"".$categoria[$i][finalidad_procedimiento_id]."\" selected >".$categoria[$i][descripcion]."</option>";
					}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				*/
	//equipos especiales
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  align=\"left\" width=\"30%\">EQUIPOS ESPECIALES REQUERIDOS</td>";
				$this->salida.="<td width=\"30%\" align=\"left\" >";
				$this->salida.="<table align=\"center\" border=\"0\"  width=\"35%\">";
				$categoria = $this->tipoequipofijo();
				for($i=0;$i<sizeof($categoria);$i++){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"left\" width=\"5%\">".$categoria[$i][descripcion]."</td>";
					$f = $_REQUEST['fijo'.$pfj];
					if(empty($_SESSION['PASO'])){
						if(empty($vector1[2][$k][$categoria[$i][tipo_equipo_fijo_id]])){
								$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'fijo".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
						}else{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name= 'fijo".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
						}
					}else{
						if(($f[$i]) != $categoria[$i][tipo_equipo_fijo_id]){
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'fijo".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
						}else{
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name= 'fijo".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
						}
					}
				}
				if(empty($_SESSION['PASO'])){
						$_SESSION['PASO']=true;
				}
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

	      //equipos fijos
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td  align=\"left\" width=\"30%\">OTROS EQUIPOS REQUERIDOS</td>";
				$this->salida.="<td width=\"30%\" align=\"left\" >";
				$this->salida.="<table align=\"center\" border=\"0\"  width=\"35%\">";
				$categoria = $this->tipoequipomovil();
				for($i=0;$i<sizeof($categoria);$i++){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"left\" width=\"5%\">".$categoria[$i][descripcion]."</td>";
					$m = $_REQUEST['movil'.$pfj];
					if(empty($_SESSION['PASO1'])){
						if(empty($vector1[3][$k][$categoria[$i][tipo_equipo_id]])){
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name = 'movil".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
						}else{
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name = 'movil".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
						}
					}else{
						if(($m[$i]) != $categoria[$i][tipo_equipo_id]){
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name = 'movil".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
						}else{
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name = 'movil".$pfj."[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
						}
				  }
				}
				if(empty($_SESSION['PASO1'])){
						$_SESSION['PASO1']=true;
				}
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				//lo nuevo de solicitud de apoyos diagnosticos
				$this->salida.="<script>";
				$this->salida.="function apoyos1(url){\n";
				$this->salida.="document.formamod$pfj.action=url;\n";
				$this->salida.="document.formamod$pfj.submit();}";
				$this->salida.="</script>";

				if ($_SESSION['APOYOS'.$pfj]){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"20%\">APOYOS PRE Y POS QUIRURGICOS REQUERIDOS</td>";
					$this->salida.="<td align=\"left\" width=\"60%\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" width=\"10%\">CARGO</td>";
					$this->salida.="<td align=\"center\" width=\"65%\">APOYO DIAGNOSTICO</td>";
					$this->salida.="<td align=\"center\" width=\"5%\">OPCION</td>";
					$this->salida.="</tr>";
					$h=0;
					foreach ($_SESSION['APOYOS'.$pfj] as $l=>$v){
							if( $h % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminarapoyo', 'apoyo'.$pfj=>$l));
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td align=\"center\" width=\"10%\">".$l."</td>";
							$this->salida.="<td align=\"left\" width=\"65%\">".$v."</td>";
							$this->salida.="<input type='hidden' name = id$l$pfj' value = ".$l.">";
							$this->salida.="<td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:apoyos1(\"$accion5\")'><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
							$this->salida.="</tr>";
							$h++;
					}
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\">&nbsp;</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos'));
					$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\"><a href='javascript:apoyos1(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITAR APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}else{
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos'));
					$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos1(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITAR APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
					$this->salida.="</tr>";
				}
        //fin de apoyos
        if (!empty($vector1[1][$k])){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td rowspan=\"".((sizeof($vector1[1][$k]))+1)."\" align=\"left\" width=\"18%\">DIAGNOSTICOS</td>";
					$this->salida.="<td colspan = 1 align=\"left\" width=\"60%\">";
					$this->salida.="<table align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <input type='hidden' name = 'eliminardiagnosticobd$pfj' value = ''>";
					$this->salida.="  <input type='hidden' name = 't$pfj'  value = ''>";
					foreach($vector1[1][$k] as $t=>$s)
					{
							$this->salida.="  <td class=\"$estilo\" align=\"left\" width=\"62%\">".$t." - ".$s."</td>";
							$this->salida.="  <td class=\"$estilo\" align=\"center\" width=\"5%\"><a href=\"javascript:elimdiagbd('$t')\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
							$this->salida.="</tr>";
					}
					//$this->salida.="</td>";
					//$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
      }
			$this->salida.="</table><br>";
		}
		//LO NUEVO CLAU
		//inicio del buscador de diagnosticos
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS MEDICOS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6    name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text'     name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;
		$this->salida.="<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"BuscarDiag$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
//inicio del listado del resultado de la busqueda de diagnosticos-esta seccion permite
//insertar un diagnostico
		if($vectorD){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"10%\">CODIGO</td>";
			$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++){
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorD[$i][diagnostico_id]."</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">".$vectorD[$i][diagnostico_nombre]."</td>";
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = '".$hc_os_solicitud_id.",".$vectorD[$i][diagnostico_id]."'></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"minsertardiagnostico$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";

			$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";

//PEGO LA BARRA
			if($this->limit>=$this->conteo){
				return '';
			}
			$paso=$_REQUEST['paso1'.$pfj];
			if(empty($paso)){
				$paso=1;
			}
			$accion = 'javascript:enviar(';
			$barra=$this->CalcularBarra($paso);
			$numpasos=$this->CalcularNumeroPasos($this->conteo);
			$colspan=1;
			$this->salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
			if($paso > 1){
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset(1)."','')\">&lt;</a></td>";
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso-1)."','".($paso-1)."')\">&lt;&lt;</a></td>";
					$colspan+=2;
			}
			$barra++;
			if(($barra+10)<=$numpasos){
				for($i=($barra);$i<($barra+10);$i++){
					if($paso==$i){
							$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
					}else{
							$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($i)."','".$i."');\">$i</a></td>";//&Of$pfj=".."&paso1$pfj=
					}
					$colspan++;
				}
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso+1)."','".($paso+1)."');\">&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($numpasos)."','".$numpasos."');\">&gt;</a></td>";
				$colspan+=2;
			}else{
				$diferencia=$numpasos-9;
				if($diferencia<=0){
						$diferencia=1;
				}
				for($i=($diferencia);$i<=$numpasos;$i++){
					if($paso==$i){
							$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
					}else{
							$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($i)."','".$i."');\">$i</a></td>";//&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'
					}
					$colspan++;
				}
				if($paso!=$numpasos){
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso+1)."','".($paso+1)."');\" >&gt;&gt;</a></td>";
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($numpasos)."','".$numpasos."');\">&gt;</a></td>";
						$colspan++;
				}
			}
			if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos)){
				if($numpasos>10){
						$valor=10+3;
				}else{
						$valor=$numpasos+3;
				}
				$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
			}else{
				if($numpasos>10){
					$valor=10+5;
				}else{
					$valor=$numpasos+5;
				}
				$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
			}
		  //FIN DE LA BARRA
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		//LO MIO HASTA AQUI
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
		$this->salida.="<td><input type=\"submit\" name = 'guardarmodificacionprocedimiento$pfj' value=\"GUARDAR PROCEDIMIENTO\" class=\"input-submit\"</td>";
		$this->salida .= "</form>";

		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'FormaPrincipal'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name= 'volver$pfj' type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

//ojo esta funcion recibia algo llamado $descripcion y lo cambie por $procedimiento
//clzc - spqx
  function Llenar_Procedimiento($tipo, $cargos, $procedimiento, $vectorD){
		
    $pfj=$this->frmPrefijo;
		//print_R($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS']);
    if(!empty($tipo) && !empty($cargos) && !empty($procedimiento)){
		  $_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj][$cargos]['tipo']=$tipo;
      $_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj][$cargos]['descripcion']=$procedimiento;
			$this->CargoXDefecto($cargos);
			$this->EstanciaXDefecto($cargos);
			if(empty($_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL'])){
        $_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']=$cargos;
			}
		}
		$this->salida .= "<script>\n";
		$this->salida .= "function enviar(Of,paso){\n";
		$this->salida .= "document.formades$pfj.Of$pfj.value=Of\n";
		$this->salida .= "document.formades$pfj.paso1$pfj.value=paso\n";
		$this->salida .= "document.formades$pfj.opc$pfj.value='opc'\n";
		$this->salida .= "document.formades$pfj.submit();}\n";
		$this->salida .= "function elimdiag(k){\n";
		$this->salida .= "document.formades$pfj.k$pfj.value=k;\n";
		$this->salida .= "document.formades$pfj.eliminardiagnostico$pfj.value='1';\n";
		$this->salida .= "document.formades$pfj.submit();}\n";
		$this->salida .= "</script>\n";
    $this->salida.="<script>";
		$this->salida.="function apoyos(url){\n";
		$this->salida.="document.formades$pfj.action=url;\n";
		$this->salida.="document.formades$pfj.submit();}";
		$this->salida.="function ModificarCargo(url){\n";
		$this->salida.="document.formades$pfj.action=url;\n";
		$this->salida.="document.formades$pfj.submit();}";		
		$this->salida.="</script>";
		$accionG=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'OpcionesProcedimiento',
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj], 'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accionG\" method=\"post\">";
		$this->salida.="  <input type='hidden' name = 'tipo$pfj'  value = '$tipo'>";
		$this->salida.="  <input type='hidden' name = 'cargos$pfj'  value = '$cargos'>";
		$this->salida.="  <input type='hidden' name = 'procedimiento$pfj'  value = '$procedimiento'>";
		$this->salida.="  <input type='hidden' name = 'Of$pfj'  value = ''>";
		$this->salida.="  <input type='hidden' name = 'paso1$pfj'  value = ''>";
		$this->salida.="  <input type='hidden' name = 'opc$pfj'  value = ''>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"4\">PROCEDIMIENTOS</td>";
		$accionAdi=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada'));
		$this->salida.="  <td width=\"5%\"><a href='javascript:apoyos(\"$accionAdi\")'><img title=\"Agregar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/editar.png\"></a></td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" width=\"20%\">TIPO</td>";
		$this->salida.="  <td align=\"center\" width=\"10%\">CARGO</td>";
		$this->salida.="  <td align=\"center\" width=\"50%\">DESCRIPCION</td>";
		$this->salida.="  <td align=\"center\" width=\"10%\" colspan=\"2\">OPCION</td>";
		$this->salida.="</tr>";
    foreach($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj] as $cargos=>$datos){
			if( $i % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"20%\" rowspan=\"3\">".$datos['tipo']."</td>";
			$this->salida.="<td align=\"center\" width=\"10%\">$cargos</td>";
			$this->salida.="<td align=\"center\" width=\"50%\" >".$datos['descripcion']."</td>";
			/*if($_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']==$cargos){
		  $this->salida.="  <td width=\"5%\"><img title=\"Procedimiento Principal\" border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></td>";
			}else{
      $accionPrincipal=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eleccion_Procedimientos_Principal','cargoPrincipal'.$pfj=>$cargos));
		  $this->salida.="  <td width=\"5%\"><a href=\"$accionPrincipal\"><img title=\"Seleccion Procedimiento Principal\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
			}*/
			$accionModify=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Modificar_Procedimientos','cargoModificar'.$pfj=>$cargos,'descripcion'.$pfj=>$datos['descripcion']));
			$this->salida.="  <td width=\"5%\"><a href='javascript:ModificarCargo(\"$accionModify\")'><img title=\"Modificar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/pmodificar.png\"></a></td>";
			$accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Procedimientos','cargoEliminar'.$pfj=>$cargos));
		  $this->salida.="  <td width=\"5%\"><a href='javascript:apoyos(\"$accionElim\")'><img title=\"Eliminar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"10%\">Observacion</td>";
			$this->salida.="<td colspan=\"3\" align=\"left\">".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$cargos]."</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"10%\">Diagnosticos<BR>Presuntivos</td>";
			$this->salida.="<td colspan=\"3\">";
			if ($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$cargos]){				
				$this->salida.="<table width=\"100%\" border=\"0\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"10%\">PRIMARIO</td>";
				$this->salida.="<td width=\"10%\">TIPO DX</td>";
				$this->salida.="<td width=\"8%\">CODIGO</td>";
				$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";				
				$this->salida.="</tr>";
	
				foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargos] as $codigoDiagnostico=>$vectorDiag){
					if(empty($codiag_uno)){$codiag_uno=$codigoDiagnostico;}
					
					foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
						$this->salida.="<tr class=\"$estilo\">";
						if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargos]==$codigoDiagnostico){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
						}else{							
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
						}
						if($tipoDiagnostico == '1'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
						}elseif($tipoDiagnostico == '2'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
						}else{
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
						}
						$this->salida.="<td align=\"center\" width=\"8%\">".$codigoDiagnostico."</td>";
						$this->salida.="<td align=\"justify\" width=\"60%\">".$nombreDiag."</td>";																	
						$this->salida.="<tr>";
					}				
				}
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";				
			}	
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		/*$this->salida.="<td width=\"20%\" class=\"label\" align=\"center\" >OBSERVACIONES Y REQUERIMIENTOS ESPECIALES</td>";

		if(!empty($_REQUEST['observacion'.$pfj])){
			$this->salida.="<td width=\"60%\"align='center'><textarea style = \"width:100%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 3>".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
		}else{
			$this->salida.="<td width=\"60%\"align='center'><textarea style = \"width:100%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 3>".$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']."</textarea></td>" ;
		}*/
		$this->salida.="</tr>";
		/*$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td class=".$this->SetStyle("cirugia")." width=\"20%\"align=\"left\" >TIPO DE CIRUGIA</td>";
		$this->salida.="<td width=\"60%\" align = left >";
		$this->salida.="<select size = 1 name = 'cirugia$pfj'  class =\"select\">";
		$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
		$categoria = $this->tipocirugia();
		for($i=0;$i<sizeof($categoria);$i++){
			$tipo_cirugia_id = $categoria[$i][tipo_cirugia_id];
			$opcion=$categoria[$i][descripcion];
			if(($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA'])  != $tipo_cirugia_id){
				$this->salida.="<option value = $tipo_cirugia_id>$opcion</option>";
			}else{
				$this->salida.="<option value = $tipo_cirugia_id selected >$opcion</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td width=\"20%\" class=".$this->SetStyle("ambito")." align=\"left\" >AMBITO</td>";
		$this->salida.="<td width=\"60%\" align = left >";
		$this->salida.="<select size = 1 name = 'ambito$pfj'  class =\"select\">";
		$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
		$categoria = $this->tipoambito();
		for($i=0;$i<sizeof($categoria);$i++){
			$ambito_cirugia_id = $categoria[$i][ambito_cirugia_id];
			$opcion = $categoria[$i][descripcion];
			if(($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO'])  != $ambito_cirugia_id){
				$this->salida.="<option value = $ambito_cirugia_id>$opcion</option>";
			}else{
				$this->salida.="<option value = $ambito_cirugia_id selected >$opcion</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td width=\"20%\" class=".$this->SetStyle("finalidad")." align=\"left\" >FINALIDAD</td>";
		$this->salida.="<td width=\"60%\" align = left >";
		$this->salida.="<select size = 1 name = 'finalidad$pfj'  class =\"select\">";
		$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
		$categoria = $this->tipofinalidad();
		for($i=0;$i<sizeof($categoria);$i++){
			$finalidad_procedimiento_id  = $categoria[$i][finalidad_procedimiento_id];
			$opcion = $categoria[$i][descripcion];
			if(($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD'])  != $finalidad_procedimiento_id){
				$this->salida.="<option value = $finalidad_procedimiento_id>$opcion</option>";
			}else{
				$this->salida.="<option value = $finalidad_procedimiento_id selected >$opcion</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";*/
    if($this->servicio!=3){
    if($_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']==1){
      $chekc='checked';
    }
    $this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td width=\"20%\" class=".$this->SetStyle("solicitudAmbulatoria")." align=\"left\">SOLICITUDES AMBULATORIAS</td>";
		$this->salida.="<td width=\"60%\" align = left ><input type=\"checkbox\" value=\"1\" name=\"solicitudAmbulatoria".$pfj."\" $chekc></td>";
		$this->salida.="</tr>";
    }

  /*$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td width=\"20%\" align=\"left\" >REQUERIMIENTO DE EQUIPOS ESPECIALES</td>";
		$this->salida.="<td width=\"60%\" align=\"left\" >";
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"50%\">";
		$categoria = $this->tipoequipofijo();
		for($i=0;$i<sizeof($categoria);$i++){
			$tipo_equipo_fijo_id  = $categoria[$i][tipo_equipo_fijo_id];
			$opcion = $categoria[$i][descripcion];
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" width=\"5%\">$opcion</td>";
			$f = $_REQUEST['fijo'.$pfj];
			if(($f[$i])  != $tipo_equipo_fijo_id){
							$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'fijo".$pfj."[$i]' value = $tipo_equipo_fijo_id></td></tr>";
			}else{
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name= 'fijo".$pfj."[$i]' value = $tipo_equipo_fijo_id></td></tr>";
			}
		}
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td width=\"20%\" align=\"left\" >OTROS EQUIPOS REQUERIDOS</td>";
		$this->salida.="<td width=\"60%\" align=\"left\" >";
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"50%\">";
		$categoria = $this->tipoequipomovil();
		for($i=0;$i<sizeof($categoria);$i++){
			$tipo_equipo_id  = $categoria[$i][tipo_equipo_id];
			$opcion = $categoria[$i][descripcion];
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" width=\"5%\">$opcion</td>";
			$m = $_REQUEST['movil'.$pfj];
			if(($m[$i])  != $tipo_equipo_id){
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'movil".$pfj."[$i]' value = $tipo_equipo_id></td></tr>";
			}else{
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name = 'movil".$pfj."[$i]' value = $tipo_equipo_id></td></tr>";
			}
		}

		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
    */
		//lo nuevo de solicitud de apoyos diagnosticos

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td colspan=\"2\" align=\"center\" width=\"100%\">";
		$this->salida.="  <table  align=\"center\" border=\"0\" width=\"98%\">";
		$this->salida.="    <tr class=\"modulo_list_claro\">";
		$this->salida.="    <td align=\"left\" class=\"label\" width=\"10%\">PRIORIDAD DE</BR>AUTORIZACION</td>";
		$this->salida.="    <td align=\"left\" width=\"25%\">";
		$this->salida.="    <select name=\"nivel$pfj\" class=\"select\">";
		$niveles=$this->niveles_Autorizacion();
		for($i=0;$i<sizeof($niveles);$i++){
			if(($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL'])!= $niveles[$i]['nivel']){
				$this->salida.="<option value = ".$niveles[$i]['nivel'].">".$niveles[$i]['descripcion']."</option>";
			}else{
				$this->salida.="<option value = ".$niveles[$i]['nivel']." selected >".$niveles[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select></td>";
		$this->salida.="    <td align=\"left\" class=\"label\" width=\"10%\">FECHA<BR>SUGERIDA</td>";
		$this->salida .= "	 <td align=\"left\" width=\"35%\"><input size=\"10\" type=\"text\" name=\"FechaCirugiaTentativa$pfj\" value=\"".$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	 &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formades'.$pfj,'FechaCirugiaTentativa'.$pfj,'/')."</td>";
		$this->salida.="     <td align=\"center\" width=\"15%\"><select size=\"1\" name=\"hora$pfj\" class=\"select\">";
		$this->salida.="     <option value = -1>Hora</option>";
	  for($j=0;$j<=23; $j++){
      if(($j >= 0) AND ($j<= 9)){
        $hora = '0'.$j;
				if($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']==$hora){
				  $this->salida.="      <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="      <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']==$j){
					$this->salida.="      <option selected value = $j>$j</option>";
				}else{
					$this->salida.="      <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="     </select>&nbsp;";
		$this->salida.="     <td align=\"center\" width=\"15%\"> <select size=\"1\"  name=\"minutos$pfj\" class=\"select\">";
	  $this->salida.="     <option value = -1>Minutos</option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
        $min = '0'.$j;
				if($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']==$min){
					$this->salida.="<option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="<option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']==$j){
					$this->salida.="<option selected value=$j>$j</option>";
				}else{
					$this->salida.="<option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="      </select></td>";
		$this->salida.="      </tr>";
		$this->salida.="      </table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";

		/*if($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj]){
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td class=\"label\" align=\"center\" width=\"20%\">DIAGNOSTICOS MEDICOS</td>";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">PRIMARIO</td>";
      $this->salida.="<td width=\"10%\">TIPO DX</td>";
      $this->salida.="<td width=\"10%\">CODIGO</td>";
      $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
      $this->salida.="<td width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$h=1;
			foreach($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj] as $codigo=>$vector){
        foreach($vector as $descripcion=>$tipoDiag){
          if($h % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
          $this->salida.="<tr class=\"$estilo\">";
          if($_SESSION['SOLICITUD_DIAGNOSTICOS_QX_PRINCIPAL'.$pfj]==$codigo){
          $this->salida.="  <td width=\"5%\" align=\"center\"><img title=\"Seleccion Diagnostico Principal\" border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></td>";
          }else{
          $accionDiadPrimario=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eleccion_Diagnostico_Principal','diagnostico'.$pfj=>$codigo));
		      $this->salida.="  <td width=\"5%\" align=\"center\"><a href=\"$accionDiadPrimario\"><img title=\"Seleccion Diagnostico Principal\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
          }
          if($tipoDiag == '1'){
            $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
          }elseif($tipoDiag == '2'){
            $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
          }else{
            $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
          }
          $this->salida.="<td align=\"center\" width=\"10%\">".$codigo."</td>";
          $this->salida.="<td align=\"left\" width=\"80%\">".$descripcion."</td>";
          $accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Diagnostico','diagnosticoEliminar'.$pfj=>$codigo));
          $this->salida.="<td width=\"5%\"><a href=\"$accionElim\"><img title=\"Eliminar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
          $this->salida.="</tr>";
          $h++;
        }
			}
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="  <td colspan = 5 align=\"center\" width=\"80%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos_Medicos'));
			$this->salida.="  <td colspan = 5 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accion1\")'><font color='#190CA2'><b><u>SELECCION DE DIAGNOSTICOS MEDICOS</u></b></font></a></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}else{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos_Medicos'));
			$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accion1\")'><font color='#190CA2'><b><u>SELECCION DE DIAGNOSTICOS MEDICOS</u></b></font></a></td>";
			$this->salida.="</tr>";
		}*/
		if ($_SESSION['SOLICITUD_APOYOS_QX'.$pfj]){
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td class=\"label\" align=\"center\" width=\"20%\">APOYOS PRE Y POS QX, PROCEDIMIENTOS QX Y NOQX </td>";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"10%\">CARGO</td>";
			$this->salida.="<td align=\"center\" width=\"70%\">DESCRIPCION</td>";
			$this->salida.="<td align=\"center\" width=\"10%\">CANTIDAD</td>";
			$this->salida.="<td align=\"center\" width=\"10%\" colspan=\"2\">OPCION</td>";
			$this->salida.="</tr>";
			$h=1;
			foreach ($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vect){
			  foreach ($vect as $descripcion=>$cantidad){
          (list($descript,$tipo)=explode('||//',$descripcion));
					$estilo='modulo_list_claro';					
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"10%\">".$codigo."</td>";
					$this->salida.="<td align=\"left\" width=\"80%\">".$descript."</td>";
          $this->salida.="<td align=\"left\" width=\"5%\">".$cantidad."</td>";
					$accionModify=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Modificar_Procedimientos','cargoModificar'.$pfj=>$codigo,'descripcion'.$pfj=>$descript));
					$this->salida.="  <td width=\"5%\"><a href='javascript:ModificarCargo(\"$accionModify\")'><img title=\"Modificar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/pmodificar.png\"></a></td>";
          $accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminarapoyo','cargoEliminar'.$pfj=>$codigo));
					$this->salida.="<td width=\"5%\"><a href=\"$accionElim\"><img title=\"Eliminar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida.="</tr>";
					$h++;					
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"10%\">Observacion</td>";
					$this->salida.="<td colspan=\"4\" align=\"left\">".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"10%\">Diagnosticos<BR>Presuntivos</td>";
					$this->salida.="<td colspan=\"4\">";
					if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$codigo]){				
						$this->salida.="<table width=\"100%\" border=\"0\">";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td width=\"10%\">PRIMARIO</td>";
						$this->salida.="<td width=\"10%\">TIPO DX</td>";
						$this->salida.="<td width=\"8%\">CODIGO</td>";
						$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";				
						$this->salida.="</tr>";
			
						foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){
							if(empty($codiag_uno)){$codiag_uno=$codigoDiagnostico;}
							
							foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
								$this->salida.="<tr class=\"$estilo\">";
								if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
									$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
								}else{							
									$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
								}
								if($tipoDiagnostico == '1'){
									$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
								}elseif($tipoDiagnostico == '2'){
									$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
								}else{
									$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
								}
								$this->salida.="<td align=\"center\" width=\"8%\">".$codigoDiagnostico."</td>";
								$this->salida.="<td align=\"justify\" width=\"60%\">".$nombreDiag."</td>";																	
								$this->salida.="<tr>";
							}				
						}
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
						$this->salida.="</tr>";
						$this->salida.="</table>";				
					}	
					$this->salida.="</td>";
					$this->salida.="</tr>";				
				}
			}
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos'));
			$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITUD DE APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}else{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos'));
			$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITUD DE APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
			$this->salida.="</tr>";
		}

		if ($_SESSION['SOLICITUD_MATERALES_QX'.$pfj]){
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td class=\"label\" align=\"center\" width=\"20%\">MATERIALES DE OSTEOSINTESIS, TEJIDOS Y ORGANOS</td>";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"10%\">CODIGO</td>";
			$this->salida.="<td align=\"center\" width=\"80%\">DESCRIPCION</td>";
			$this->salida.="<td align=\"center\" width=\"5%\">CANTIDAD</td>";
			$this->salida.="<td align=\"left\" width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$h=1;
			foreach($_SESSION['SOLICITUD_MATERALES_QX'.$pfj] as $codigo=>$vector){
			  foreach($vector as $descripcion=>$cantidad){
					if($h % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
					$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminarapoyo', 'apoyo'.$pfj=>$k));
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"10%\">$codigo</td>";
					$this->salida.="<td align=\"left\" width=\"80%\">$descripcion</td>";
					$this->salida.="<td align=\"left\" width=\"5%\">$cantidad</td>";
					$accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Materiales','productoEliminar'.$pfj=>$codigo));
					$this->salida.="<td width=\"5%\"><a href=\"$accionElim\"><img title=\"Eliminar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida.="</tr>";
					$h++;
				}
			}

			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionMat=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Materiales'));
			$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accionMat\")'><font color='#190CA2'><b><u>SOLICITUD DE MATERIAL DE OSTEOSINTESIS, TEJIDOS Y ORGANOS</u></b></font></a></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}else{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionMat=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Materiales'));
			$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accionMat\")'><font color='#190CA2'><b><u>SOLICITUD DE MATERIAL DE OSTEOSINTESIS, TEJIDOS Y ORGANOS</u></b></font></a></td>";
			$this->salida.="</tr>";
		}

		if ($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj]){
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td class=\"label\" align=\"center\" width=\"20%\">EQUIPOS QUIRURGICOS</td>";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"10%\">TIPO</td>";
			$this->salida.="<td align=\"center\" width=\"80%\">DESCRIPCION</td>";
			$this->salida.="<td align=\"center\" width=\"5%\">CANTIDAD</td>";
			$this->salida.="<td align=\"left\" width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$h=1;
			foreach($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj] as $codigo=>$vector){
			  foreach($vector as $descripcion=>$cantidad){
					if($h % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
					$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminarapoyo', 'apoyo'.$pfj=>$k));
					$this->salida.="<tr class=\"$estilo\">";
					(list($Equipo,$Tipo)=explode('||//',$codigo));
					$this->salida.="<td align=\"center\" width=\"10%\">$Tipo</td>";
					$this->salida.="<td align=\"left\" width=\"80%\">$descripcion</td>";
					$this->salida.="<td align=\"left\" width=\"5%\">$cantidad</td>";
					$accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Tipo_Equipo','equipoEliminar'.$pfj=>$codigo));
					$this->salida.="<td width=\"5%\"><a href=\"$accionElim\"><img title=\"Eliminar Tipo Equipo\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida.="</tr>";
					$h++;
				}
			}
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionEqui=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EquiposQX'));
			$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accionEqui\")'><font color='#190CA2'><b><u>SOLICITUD DE EQUIPOS QUIRURGICOS</u></b></font></a></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}else{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionEqui=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EquiposQX'));
			$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accionEqui\")'><font color='#190CA2'><b><u>SOLICITUD DE EQUIPOS QUIRURGICOS</u></b></font></a></td>";
			$this->salida.="</tr>";
		}
		
		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td class=\"label\" align=\"center\" width=\"20%\">ESTANCIA</td>";
		$this->salida.="<td align=\"left\" width=\"100%\">";
		$vector= $this->Busqueda_Avanzada_EstanciaQX();
		if($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";			
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="  <td>TIPO ESTANCIA</td>";			
			$this->salida.="  <td width=\"10%\">PRE QX</td>";
			$this->salida.="  <td width=\"10%\">POS QX</td>";			
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
				if($i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="  <td align=\"left\">".$vector[$i]['descripcion']."</td>";		
				$che1='';$che2='';		
				if($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['PRE']=='1' || ($_REQUEST[$vector[$i]['tipo_clase_cama_id'].'PRE'.$pfj]=='1')){$che1='checked';}
				if($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['POS']=='1' || ($_REQUEST[$vector[$i]['tipo_clase_cama_id'].'POS'.$pfj]=='1')){$che2='checked';}
				$this->salida.="  <td align=\"center\" width=\"10%\"><input $che1 type = checkbox name= \"".$vector[$i]['tipo_clase_cama_id']."PRE".$pfj."\" value=\"1\"></td>";
				$this->salida.="  <td align=\"center\" width=\"10%\"><input $che2 type = checkbox name= \"".$vector[$i]['tipo_clase_cama_id']."POS".$pfj."\" value=\"1\"></td>";									
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";	
		/*if ($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj]){
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td class=\"label\" align=\"center\" width=\"20%\">ESTANCIA</td>";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"50%\">TIPO CLASE CAMA</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">DIAS</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">PRE QX</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">POS QX</td>";
			$this->salida.="<td align=\"center\" width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$h=1;
			foreach($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj] as $codigo=>$vector){
			  if($h % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
				$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminarapoyo', 'apoyo'.$pfj=>$k));
				$this->salida.="<tr class=\"$estilo\">";
        foreach($vector as $indice=>$valor){
				  if($indice!='POS' && $indice!='PRE'){
						$this->salida.="<td align=\"center\">$indice</td>";
						$this->salida.="<td align=\"center\">$valor</td>";
          }
				}
        if($vector['PRE']=='1'){
          $this->salida.="<td align=\"center\" width=\"15%\">Si</td>";
				}else{
          $this->salida.="<td align=\"center\" width=\"15%\">No</td>";
				}
        if($vector['POS']=='1'){
          $this->salida.="<td align=\"center\" width=\"15%\">Si</td>";
				}else{
          $this->salida.="<td align=\"center\" width=\"15%\">No</td>";
				}
				$accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Tipo_Estancia','estanciaEliminar'.$pfj=>$codigo));
				$this->salida.="<td align=\"center\"><a href=\"$accionElim\"><img title=\"Eliminar Tipo Equipo\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				$this->salida.="</tr>";
				$h++;
			}
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="  <td colspan = 3 align=\"center\">&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionEqui=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EstanciaQX'));
			$this->salida.="  <td colspan = 5 align=\"center\"><a href='javascript:apoyos(\"$accionEqui\")'><font color='#190CA2'><b><u>SOLICITUD DE ESTANCIA</u></b></font></a></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}else{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionEqui=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EstanciaQX'));
			$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accionEqui\")'><font color='#190CA2'><b><u>SOLICITUD DE ESTANCIA</u></b></font></a></td>";
			$this->salida.="</tr>";
		}*/

		if ($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj]){
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td rowspan=\"2\" class=\"label\" align=\"center\" width=\"20%\">COMPONENTES SANGUINEOS</td>";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"80%\">COMPONENTE</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">CANTIDAD</td>";
			$this->salida.="<td align=\"center\" width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$h=1;
			foreach($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj] as $componente=>$cantidad){
			  if($cantidad){
			  (list($componente_id,$nomcomponente)=explode('||//',$componente));
			  if($h % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
        $this->salida.="<td align=\"center\" width=\"80%\">$nomcomponente</td>";
        $this->salida.="<td align=\"center\" width=\"15%\">$cantidad</td>";
				$accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Componente_Sangre','componenteEliminar'.$pfj=>$componente));
				$this->salida.="<td align=\"center\" width=\"5%\"><a href=\"$accionElim\"><img title=\"Eliminar Tipo Equipo\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				$this->salida.="</tr>";
				$h++;
				}
			}
			$this->salida.="</table>";
      $this->salida.="</td>";
			$this->salida.="</tr>";
      $this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td align=\"left\" width=\"60%\">";
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"15%\">CARGO</td>";
      $this->salida.="<td align=\"center\" width=\"80%\">DESCRIPCION</td>";
			$this->salida.="<td align=\"center\" width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			$h=1;
      foreach($_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj] as $cargo=>$descripcion){
			  if($h % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
        $this->salida.="<td align=\"center\" width=\"15%\">$cargo</td>";
        $this->salida.="<td align=\"center\" width=\"80%\">$descripcion</td>";
				$accionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Apoyo_Sangre','ApoyoEliminar'.$pfj=>$cargo));
				$this->salida.="<td align=\"center\" width=\"5%\"><a href=\"$accionElim\"><img title=\"Eliminar Tipo Equipo\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				$this->salida.="</tr>";
				$h++;
			}
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionBS=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_BSangreQX'));
			$this->salida.="  <td colspan = 5 align=\"center\"><a href='javascript:apoyos(\"$accionBS\")'><font color='#190CA2'><b><u>SOLICITUD DE COMPONENTES SANGUINEOS</u></b></font></a></td>";
			$this->salida.="</tr>";
      $this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}else{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionBS=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_BSangreQX'));
			$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accionBS\")'><font color='#190CA2'><b><u>SOLICITUD DE COMPONENTES SANGUINEOS</u></b></font></a></td>";
			$this->salida.="</tr>";
		}
    //LO MIO HASTA AQUI
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
		if(empty($_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA'])){
		$this->salida .= "<td><input type=\"submit\"  name = 'guardarprocedimiento$pfj' value=\"GUARDAR PROCEDIMIENTO\"  class=\"input-submit\"></td>";
		}else{
    $this->salida .= "<td><input type=\"submit\"  name = 'guardarprocedimiento$pfj' value=\"MODIFICAR PROCEDIMIENTO\"  class=\"input-submit\"></td>";
		}
		$this->salida .= "</form>";
		$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'FormaPrincipal'));
		$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
		//$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"SALIR\"></form></td>";
		$this->salida.="</tr></table>";
    return true;
  }

	function InsertarObservacionesSolicitud($SolicitudId,$observacionId,$observacion){

		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('OBSERVACIONES SOLICITUDES QX');
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Guardar_Observaciones_Solicitud','SolicituId'.$pfj=>$SolicitudId,'observacionId'.$pfj=>$observacionId,'observacion'.$pfj=>$observacion));
		$observaciones=$this->ObservacionesSolicitud($SolicitudId);
		$this->salida.="<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="  <table  align=\"center\" border=\"0\"  width=\"60%\">";
		$this->salida.="  <tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\">OBSERVACIONES</td>";
		$this->salida.="  </tr>";
		$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td><textarea class=\"input-text\" cols=\"80\" rows=\"10\" name=\"observaciones$pfj\">$observacion</textarea></td>";
    $this->salida.="  </tr>";
    $this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
		if(empty($observacionId)){
		$this->salida.="  <td>";
		$this->salida.="  <input type=\"submit\" value=\"GUARDAR\" name=\"Guardar$pfj\" class=\"input-submit\">";
		$this->salida.="  <input type=\"submit\" value=\"SALIR\" name=\"Salir$pfj\" class=\"input-submit\">";
		$this->salida.="  </td>";
		}else{
    $this->salida.="  <td>";
		$this->salida.="  <input type=\"submit\" value=\"MODIFICAR\" name=\"Modificar$pfj\" class=\"input-submit\">";
		$this->salida.="  <input type=\"submit\" value=\"SALIR\" name=\"Salir$pfj\" class=\"input-submit\">";
		$this->salida.="  </td>";
		}
		$this->salida.="  </tr>";
    $this->salida.="  </table><BR>";
		if($observaciones){
      $this->salida.="  <table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="  <tr class=\"modulo_table_title\">";
			$this->salida.="  <td colspan=\"2\" align=\"center\">REGISTRO DE OBSERVACIONES</td>";
			$this->salida.="  </tr>";
			for($i=0;$i<sizeof($observaciones);$i++){
        $this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
				(list($fecha,$hora)=explode(' ',$observaciones[$i]['fecha']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
				(list($hora,$minutos)=explode(':',$hora));
				$FechaConver1=mktime($hora,$minutos,0,$mes,$dia,$ano);
				(list($fecha,$hora)=explode(' ',$observaciones[$i]['fecha_ultima_modificacion']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
				(list($hora,$minutos)=explode(':',$hora));
				$FechaConver2=mktime($hora,$minutos,0,$mes,$dia,$ano);
				$this->salida.="  <td align=\"left\" width=\"30%\">".$observaciones[$i]['nombre_tercero']."";
				$this->salida.="  <BR>".ucwords(strftime("%b %d de %Y %H:%M",$FechaConver1))."";
				$this->salida.="  <BR>Editada:  ".ucwords(strftime("%b %d de %Y %H:%M",$FechaConver2))."";
				if($observaciones[$i]['propio']==1){
					$EliminaObservacion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'EliminarObservacionSolicitud','SolicitudId'.$pfj=>$SolicitudId,'observacionId'.$pfj=>$observaciones[$i]['observacion_id']));
					$EditaObservacion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'EditaObservacionSolicitud','SolicitudId'.$pfj=>$SolicitudId,'observacionId'.$pfj=>$observaciones[$i]['observacion_id'],"observacion".$pfj=>$observaciones[$i]['observacion']));
					$this->salida.="  <BR><BR><a class=\"link\" href=\"$EditaObservacion\">EDITAR</a>&nbsp&nbsp;<a class=\"link\" href=\"$EliminaObservacion\">ELIMINAR</a>";
				}
				$this->salida.="  </td>";
				$this->salida.="  <td valign=\"top\" align=\"left\">".$observaciones[$i]['observacion']."</td>";
				$this->salida.="  </tr>";
			}
      $this->salida.="  </table>";
		}
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
	
	function Frm_Modificar_Procedimiento($cargo,$descripcion){

		
		$pfj=$this->frmPrefijo;
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$this->salida= ThemeAbrirTablaSubModulo('MODIFICAR PROCEDIMIENTO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Buscador_Modificar_Procedimientos','cargoModificar'.$pfj=>$cargo,'descripcion'.$pfj=>$descripcion));
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
		$this->salida .="<td width=\"65%\" align='center'><textarea class='textarea' name = 'obs$pfj' cols = 100 rows = 3>".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$cargo]."</textarea></td>" ;
		$this->salida.="</tr>";

		
		if ($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$cargo]){
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

			foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargo] as $codigoDiagnostico=>$vectorDiag){
				if(empty($codiag_uno)){$codiag_uno=$codigoDiagnostico;}
				
				foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
					$this->salida.="<tr class=\"$estilo\">";
					if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargo]==$codigoDiagnostico){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
					}else{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'Buscador_Modificar_Procedimientos',"cargoModificar".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion,"CambioDiagPrincipal".$this->frmPrefijo=>'Cambio','codiag'.$this->frmPrefijo=>$codigoDiagnostico));
						$this->salida.="<td align=\"center\" width=\"10%\"><a href='$accion'><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></a></td>";
					}
					if($tipoDiagnostico == '1'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
					}elseif($tipoDiagnostico == '2'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}else{
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.="<td align=\"center\" width=\"8%\">".$codigoDiagnostico."</td>";
					$this->salida.="<td align=\"justify\" width=\"60%\">".$nombreDiag."</td>";					
					$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'Buscador_Modificar_Procedimientos',"cargoModificar".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion,"EliminacionDiagnostico".$this->frmPrefijo=>'Cambio','codiag'.$this->frmPrefijo=>$codigoDiagnostico,'codiag_uno'.$this->frmPrefijo=>$codiag_uno));					
					$this->salida.="<td align=\"center\" width=\"7%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
					$this->salida.="<tr>";
				}				
			}
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";

			$this->salida.="</table>";
			$this->salida .="</td>" ;
			$this->salida.="</tr>";
    }		
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida .= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardarVolver$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";		
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj' value=\"".$_REQUEST['codigo'.$pfj]."\"></td>" ;
		//la misma pero con el value $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'  value =\"".$_REQUEST['codigo'.$pfj]."\"    ></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"></td>" ;
		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		
		
		$vectorD=$this->Busqueda_Avanzada_Diagnosticos($_REQUEST['codigo'.$pfj],$_REQUEST['diagnostico'.$pfj]);
		if ($vectorD){
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
      for($i=0;$i<sizeof($vectorD);$i++){
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];
	
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";

				$this->salida.="  <td align=\"center\" width=\"8%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"60%\">$diagnostico</td>";
   			$this->salida.="<td align=\"center\" width=\"17%\">";
				$this->salida.="<input type=\"radio\" name=\"dx".$pfj."[$cargo][$codigo]\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$pfj."[$cargo][$codigo]\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$pfj."[$cargo][$codigo]\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$pfj."[$cargo][$codigo]\" value = \"$diagnostico\"></td>";
				$this->salida.="</tr>";
      }
               
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
							
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$Paginador = new ClaseHTML();
			$this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'Buscador_Modificar_Procedimientos',"buscar".$this->frmPrefijo=>'BUSCAR','cargoModificar'.$pfj=>$cargo,'descripcion'.$pfj=>$descripcion,"codigo".$pfj=>$_REQUEST['codigo'.$pfj],'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr><td align=\"center\"><input type=\"submit\" name=\"Volver".$this->frmPrefijo."\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
		$this->salida.="</table>";		
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}	
		



	function frmFormaConsultaDiagnostico($tipo, $cargo, $descripcion){

		$pfj=$this->frmPrefijo;
		if($_SESSION['DIAGNOSTICOS'.$pfj]){
			$consulta.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			/*$consulta .="<tr class=\"modulo_table_title\">";
			$consulta .="<td colspan=\"3\" align=\"center\">DIAGNOSTICOS ASIGNADOS</td>";
			$consulta .="</tr>";*/
			$consulta .="<tr class=\"hc_table_submodulo_list_title\">";
			$consulta .="<td align=\"center\" width=\"10%\">CODIGO</td>";
			$consulta .="<td align=\"center\" width=\"65%\">DIAGNOSTICO</td>";
			$consulta .="<td align=\"center\" width=\"5%\">OPCION</td>";
			$consulta .="</tr>";
			$s=0;
			$this->salida.="  <input type='hidden' name = 'eliminardiagnostico$pfj' value = ''>";
			$this->salida.="  <input type='hidden' name = 'k$pfj'  value = ''>";
			foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v){
				if ($s==0){
					$consulta .="<tr class=\"hc_submodulo_list_oscuro\">";
					$s=1;
				}else{
					$consulta .="<tr class=\"hc_submodulo_list_claro\">";
					$s=0;
				}
				$consulta .="<td align=\"center\">$k</td>";
				$consulta .="<td align=\"left\">$v</td>";
			  $consulta .="<td align=\"center\" width=\"5%\"><a href=\"javascript:elimdiag('$k')\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\" ></a></td>";
				$consulta .="</tr>";
			}
			$consulta .="</table><br>";
	  }
		return $consulta;
	}

//COR - clzc - SPQX
	function frmConsulta(){

    $pfj=$this->frmPrefijo;    
    $vector=$this->SolicitudesQXPacientefrmConsulta();
		if($vector){					
			$this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"2\">SOLICITUDES PROCEDIMIENTOS QX</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td width=\"15%\" align=\"center\">ACTO QX</td>";
			$this->salida.="  <td width=\"85%\">&nbsp;</td>";					
			$this->salida.="</tr>";
			$this->salida.="<tr>";			
			$this->salida.="<td class=\"hc_table_submodulo_list_title\" width=\"15%\" valign=\"center\">- ".$vector[0]['acto_qx_id']." - <BR><BR>".$vector[0]['nombre_tercero']."<BR><BR><img title=\"Modificar\" border = 0 src=\"".GetThemePath()."/images/pmodificar.png\"></td>";
			$this->salida.="<td width=\"85%\">";
			$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_title\">";					
			$this->salida.="  	<td width=\"15%\" align=\"center\">SOLICITUD</td>";
			$this->salida.="  	<td width=\"25%\" align=\"center\">PROFESIONAL</td>";
			$this->salida.="  	<td width=\"45%\" align=\"center\">PROCEDIMIETOS</td>";
			$this->salida.="  	<td width=\"5%\" align=\"center\">ESTADO</td>";
			$this->salida.="		</tr>";					
			for($i=0;$i<sizeof($vector);$i++){
				$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";						
				(list($ano,$mes,$dia)=explode('-',$vector[$i]['fecha']));
				$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);						
				if($vector[$i]['sw_ambulatorio']==1){
					$this->salida.=" <td valign=\"top\" rowspan=\"".((sizeof($vector1)+1)+3)."\" align=\"center\">";					
				}else{
					$this->salida.=" <td valign=\"top\" rowspan=\"".((sizeof($vector1)+1)+2)."\" align=\"center\">";
				}						
				$this->salida.="  - ".$vector[$i]['hc_os_solicitud_id']." - <BR>".ucwords(strftime("%b %d de %Y ",$FechaConver1))."<BR>";            
				$this->salida.="   </td>";
				$this->salida.="   <td rowspan=\"".(sizeof($vector1)+1)."\" align=\"center\">".$vector[$i]['tipo']."</td>";
				$this->salida.="   <td class=\"modulo_list_claro\" align=\"left\">".$vector[$i]['cargo']." - ".$vector[$i]['descripcion']."</td>";
				if($vector[$i]['sw_estado']=='0' OR $vector[$i]['sw_estado']=='3'){					
					$this->salida.=" <td class=\"modulo_list_claro\" align=\"left\">AUTORIZADO</td>";
				}else{
					$this->salida.=" <td class=\"modulo_list_claro\" align=\"left\">ACTIVA</td>";
				}
				$this->salida.="	 </tr>";						
				$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td>OBSERVACIONES</td>";
				$this->salida.="  <td class=\"modulo_list_claro\" colspan=\"2\" align=\"left\">".$vector[$i]['observacion']."</td>";
				$this->salida.="	</tr>";
				$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td>DIAGNOSTICOS PRESUNTIVOS</td>";
				$this->salida.="  <td class=\"modulo_list_claro\" colspan=\"2\">";
				if($dat=$this->DiagnosticosSolicitudQX($vector[$i]['hc_os_solicitud_id'])){
				$this->salida.="    <table  align=\"center\" border=\"0\"  width=\"100%\">";
				$this->salida.="    <tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="    <td width=\"10%\">PRIMARIO</td>";
				$this->salida.="    <td width=\"10%\">TIPO DX</td>";
				$this->salida.="    <td width=\"10%\">CODIGO</td>";
				$this->salida.="    <td>DIAGNOSTICO</td>";
				$this->salida.="    </tr>";
				for($l=0;$l<sizeof($dat);$l++){
					if($h % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					if($dat[$l]['sw_principal']==1){
					$this->salida.="  <td width=\"5%\" align=\"center\"><img title=\"Diagnostico Principal\" border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></td>";
					}else{
					$this->salida.="  <td width=\"5%\" align=\"center\"><img title=\"Diagnostico Principal\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></td>";
					}
					if($dat[$l]['tipo_diagnostico'] == '1'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
					}elseif($dat[$l]['tipo_diagnostico'] == '2'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}else{
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.="<td align=\"center\" width=\"10%\">".$dat[$l]['diagnostico_id']."</td>";
					$this->salida.="<td align=\"left\" width=\"80%\">".$dat[$l]['diagnostico_nombre']."</td>";
					$this->salida.="</tr>";
					$h++;
				}
				$this->salida.="    </table>";
				}else{
				$this->salida.="    &nbsp;";
				}
				$this->salida.="  </td>";
				$this->salida.="</tr>";						
				if($vector[$i]['sw_ambulatorio']==1){
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";					
					$this->salida.="  <td align=\"center\" class=\"modulo_list_claro\" colspan=\"3\" align=\"left\">AMBULATORIO</td>";
					$this->salida.="</tr>";
				} 						
			}					
			$this->salida.="</table>";
					
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";			
			$this->salida.="  <td colspan=\"2\">OBSERVACIONES</td>";
			$this->salida.="</tr>";			
			$this->salida.="</table>";
		}else{		
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTES DE HALLAZGOS QUIRURGICOS<br><br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";		
		}    
    return true;
	}


	//COR - clzc - SPQX
	function frmHistoria(){
		$pfj=$this->frmPrefijo;

		$vector=$this->SolicitudesQXPaciente();
		if($vector){	
		//print_r($vector);				
			$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="  <td align=\"center\" colspan=\"2\">SOLICITUDES PROCEDIMIENTOS QX</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="  <td width=\"15%\" align=\"center\">ACTO QX</td>";
			$salida.="  <td width=\"85%\">&nbsp;</td>";					
			$salida.="</tr>";
			$salida.="<tr>";			
			$salida.="<td class=\"hc_table_submodulo_list_title\" width=\"15%\" align=\"center\">".$vector[0]['nombre_tercero']."</td>";
			$salida.="<td width=\"85%\">";
			$salida.="	<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="		<tr class=\"modulo_table_title\">";					
			$salida.="  	<td width=\"15%\" align=\"center\">SOLICITUD</td>";
			$salida.="  	<td width=\"25%\" align=\"center\">PROFESIONAL</td>";
			$salida.="  	<td width=\"45%\" align=\"center\">PROCEDIMIETOS</td>";
			$salida.="  	<td width=\"5%\" align=\"center\">ESTADO</td>";
			$salida.="		</tr>";					
			for($i=0;$i<sizeof($vector);$i++){
				$salida.="	<tr class=\"hc_table_submodulo_list_title\">";						
				(list($ano,$mes,$dia)=explode('-',$vector[$i]['fecha']));
				$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);						
				if($vector[$i]['sw_ambulatorio']==1){
					$salida.=" <td valign=\"top\" rowspan=\"".((sizeof($vector1)+1)+3)."\" align=\"center\">";					
				}else{
					$salida.=" <td valign=\"top\" rowspan=\"".((sizeof($vector1)+1)+2)."\" align=\"center\">";
				}						
				$salida.="  - ".$vector[$i]['hc_os_solicitud_id']." - <BR>".ucwords(strftime("%b %d de %Y ",$FechaConver1))."<BR>";            
				$salida.="   </td>";
				$salida.="   <td rowspan=\"".(sizeof($vector1)+1)."\" align=\"center\">".$vector[$i]['tipo']."</td>";
				$salida.="   <td class=\"modulo_list_claro\" align=\"left\">".$vector[$i]['cargo']." - ".$vector[$i]['descripcion']."</td>";
				if($vector[$i]['sw_estado']=='0' OR $vector[$i]['sw_estado']=='3'){					
					$salida.=" <td class=\"modulo_list_claro\" align=\"left\">AUTORIZADO</td>";
				}else{
					$salida.=" <td class=\"modulo_list_claro\" align=\"left\">ACTIVA</td>";
				}
				$salida.="	 </tr>";						
				$salida.="	<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td>OBSERVACIONES</td>";
				$salida.="  <td class=\"modulo_list_claro\" colspan=\"2\" align=\"left\">".$vector[$i]['observacion']."</td>";
				$salida.="	</tr>";
				$salida.="	<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td>DIAGNOSTICOS PRESUNTIVOS</td>";
				$salida.="  <td class=\"modulo_list_claro\" colspan=\"2\">";
				if($dat=$this->DiagnosticosSolicitudQX($vector[$i]['hc_os_solicitud_id'])){
				$salida.="    <table  align=\"center\" border=\"0\"  width=\"100%\">";
				$salida.="    <tr class=\"hc_table_submodulo_list_title\">";
				$salida.="    <td width=\"10%\">PRIMARIO</td>";
				$salida.="    <td width=\"10%\">TIPO DX</td>";
				$salida.="    <td width=\"10%\">CODIGO</td>";
				$salida.="    <td>DIAGNOSTICO</td>";
				$salida.="    </tr>";
				for($l=0;$l<sizeof($dat);$l++){
					if($h % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$salida.="<tr class=\"$estilo\">";
					if($dat[$l]['sw_principal']==1){
					$salida.="  <td width=\"5%\" align=\"center\">X</td>";
					}else{
					$salida.="  <td width=\"5%\" align=\"center\">&nbsp;</td>";
					}
					if($dat[$l]['tipo_diagnostico'] == '1'){
						$salida.="<td align=\"center\" width=\"10%\">ID</td>";
					}elseif($dat[$l]['tipo_diagnostico'] == '2'){
						$salida.="<td align=\"center\" width=\"10%\">CN</td>";
					}else{
						$salida.="<td align=\"center\" width=\"10%\">CR</td>";
					}
					$salida.="<td align=\"center\" width=\"10%\">".$dat[$l]['diagnostico_id']."</td>";
					$salida.="<td align=\"left\" width=\"80%\">".$dat[$l]['diagnostico_nombre']."</td>";
					$salida.="</tr>";
					$h++;
				}
				$salida.="    </table>";
				}else{
				$salida.="    &nbsp;";
				}
				$salida.="  </td>";
				$salida.="</tr>";						
				if($vector[$i]['sw_ambulatorio']==1){
					$salida.="<tr class=\"hc_table_submodulo_list_title\">";					
					$salida.="  <td align=\"center\" class=\"modulo_list_claro\" colspan=\"3\" align=\"left\">AMBULATORIO</td>";
					$salida.="</tr>";
				} 						
			}					
			$salida.="</table>";
					
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";			
			//$salida.="  <td colspan=\"2\">OBSERVACIONES</td>";
			$salida.="</tr>";			
			$salida.="</table><br>";
		}else{		
			return false;	
		}   
    return $salida;
	}

//nuevas funciones para lo de materiales
  function frmForma_Seleccion_Materiales($vector){

		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('MATERIAL DE OSTEOSINTESIS, TEJIDOS Y ORGANOS');
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Materiales',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">GRUPOS</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = 'grupo$pfj'  class =\"select\">";
		$this->salida.="<option value = '-1' >Todos</option>";
		$grupos = $this->GrupoInventarios();
		for($i=0;$i<sizeof($grupos);$i++){
			if(($_REQUEST['grupo'.$pfj])!= $grupos[$i]['grupo_id']){
				$this->salida.="<option value = ".$grupos[$i]['grupo_id'].">".$grupos[$i]['descripcion']."</option>";
			}else{
				$this->salida.="<option value = ".$grupos[$i]['grupo_id']." selected >".$grupos[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="<td width=\"6%\">CODIGO:</td>";
		$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10    name = 'codigoProducto$pfj'  value =\"".$_REQUEST['codigoProducto'.$pfj]."\"    ></td>" ;
		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Materiales','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'grupo'.$pfj=>$_REQUEST['grupo'.$pfj],'codigoProducto'.$pfj=>$_REQUEST['codigoProducto'.$pfj],'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"15%\">GRUPO</td>";
			$this->salida.="  <td width=\"10%\">CODIGO</td>";
			$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
			$this->salida.="  <td width=\"5%\">CANTIDAD</td>";
			$this->salida.="  <td width=\"5%\">SELECCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
				if($i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">".$vector[$i]['desgrupo']."</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vector[$i]['codigo_producto']."</td>";
				$this->salida.="  <td align=\"left\" width=\"50%\">".$vector[$i]['descripcion']."</td>";
				if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
				if($_SESSION['Insumos'.$pfj][$paso][$vector[$i]['codigo_producto']]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"text\" size=\"4\" name=\"".$vector[$i]['codigo_producto']."".$pfj."\" value=\"".$_SESSION['Insumos'.$pfj][$paso][$vector[$i]['codigo_producto']][$vector[$i]['descripcion']]."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"seleccion".$pfj."[".$vector[$i]['codigo_producto']."]\" value=\"".$vector[$i]['descripcion']."\" checked></td>";
				}elseif($_SESSION['SOLICITUD_MATERALES_QX'.$pfj][$vector[$i]['codigo_producto']][$vector[$i]['descripcion']]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"text\" class=\"input-text\" size=\"4\" name=\"".$vector[$i]['codigo_producto']."".$pfj."\" value=\"".$_SESSION['SOLICITUD_MATERALES_QX'.$pfj][$vector[$i]['codigo_producto']][$vector[$i]['descripcion']]."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"seleccion".$pfj."[".$vector[$i]['codigo_producto']."]\" value=\"".$vector[$i]['descripcion']."\" checked></td>";
				}else{
					$this->salida.="  <td align=\"center\" width=\"5%\"><input class=\"input-text\" type=\"text\" size=\"4\" name=\"".$vector[$i]['codigo_producto']."".$pfj."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"seleccion".$pfj."[".$vector[$i]['codigo_producto']."]\" value=\"".$vector[$i]['descripcion']."\"></td>";
				}
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"5\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			/*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
			if ($this->conteo == 1)
			{
					$this->Insertar_Solicitud($cargo,$apoyod_tipo_id);
			}*/

			$var=$this->RetornarBarraExamenes_Avanzada(1);
			if(!empty($var)){
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
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_de_solicitud_materiales'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }

	function frmForma_Seleccion_ComponentesBanco($vector){
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('RESERVA DE COMPONENTES SANGUINEOS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_BSangreQX'));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">COMPONENTES SANGUINEOS</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
			  $this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td width=\"25%\" class=\"label\">".$vector[$i]['componente']."</td>";
        if($_SESSION['SELECCION_COMPONENTES_SANGRE_QX'.$pfj][$vector[$i]['hc_tipo_componente'].'||//'.$vector[$i]['componente']]){
          $this->salida.="<td width=\"75%\"colspan=\"2\"><input size=\"2\" type=\" class=\"input-text\" text\" name=\"cantidades".$pfj."[".$vector[$i]['hc_tipo_componente'].'||//'.$vector[$i]['componente']."]\" value=\"".$_SESSION['SELECCION_COMPONENTES_SANGRE_QX'.$pfj][$vector[$i]['hc_tipo_componente'].'||//'.$vector[$i]['componente']]."\"><b>Und.</b></td>";
				}else{
          $this->salida.="<td width=\"75%\"colspan=\"2\"><input size=\"2\" type=\" class=\"input-text\" text\" name=\"cantidades".$pfj."[".$vector[$i]['hc_tipo_componente'].'||//'.$vector[$i]['componente']."]\" value=\"".$_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj][$vector[$i]['hc_tipo_componente'].'||//'.$vector[$i]['componente']]."\"><b>Und.</b></td>";
				}
				$this->salida.="</tr>";
			}
			if(empty($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj])){
			  $_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj]=$_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj];
				unset($_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj]);
			}
			if(sizeof($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj])>0){
        $this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"3\">APOYOS ADICIONALES</td>";
				$this->salida.="</tr>";
				foreach($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj] as $codigo=>$descripcion){
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida.="<td class=\"label\" width=\"25%\">".$codigo."</td>";
					$this->salida.="<td class=\"label\" width=\"70%\">".$descripcion."</td>";
					$accionEligido=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Eliminar_Apoyo_Banco_Sangre','cargoEliminar'.$pfj=>$codigo));
					$this->salida.="<td width=\"5%\"><a href=\"$accionEligido\"><img title=\"Eliminar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida.="</tr>";
			  }
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
		}
    $this->salida .= "</form>";
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_BSangreQX',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida.="<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"6%\">CODIGO:</td>";
		$this->salida.="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10    name = 'codigo$pfj'  value =\"".$_REQUEST['codigo'.$pfj]."\"></td>" ;
		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida.="<td width=\"25%\" align='center'><input type='text' size=\"35\" class='input-text' name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"></td>" ;
		$this->salida.="<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
    $apoyos=$this->ApoyosDiagnosticosBanco($_REQUEST['codigo'.$pfj],$_REQUEST['descripcion'.$pfj]);
		if($apoyos){
      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"15%\">CODIGO</td>";
			$this->salida.="  <td width=\"80%\">DESCRIPCION</td>";
			$this->salida.="  <td width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($apoyos);$i++){
				if($i % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\" width=\"15%\">".$apoyos[$i]['cargo']."</td>";
				$this->salida.="<td align=\"center\" width=\"80%\">".$apoyos[$i]['descripcion']."</td>";
				$accionSelect=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_BSangreQX','cargoSeleccionado'.$pfj=>$apoyos[$i]['cargo'],'descripcionSelect'.$pfj=>$apoyos[$i]['descripcion']));
				$this->salida.="<td width=\"5%\"><a href=\"$accionSelect\"><img title=\"Seleccion Procedimiento Principal\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
			/*$var=$this->RetornarBarraExamenes_Avanzada(1);
			if(!empty($var)){
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$var;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}*/
		}
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		//BOTON DEVOLVER
    $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_de_solicitud_componentes_sangre'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }

	function frmForma_Seleccion_Diagnosticos_Medicos($vector){
    
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS MEDICOS');
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos_Medicos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS MEDICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6 name = \"codigo$pfj\" value=\"".$_REQUEST['codigo'.$pfj]."\"></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text'     name = \"diagnostico$pfj\"   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"BuscarDiag$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		//inicio del listado del resultado de la busqueda de diagnosticos-esta seccion permite
		//insertar un diagnostico
		if ($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"8%\">CODIGO</td>";
               $this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="  <td width=\"17%\">TIPO DX</td>";
               $this->salida.="  <td width=\"5%\">OPCION</td>";
               $this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
				$codigo          = $vector[$i][diagnostico_id];
				$diagnostico    = $vector[$i][diagnostico_nombre];
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
        $this->salida.="<td align=\"center\" width=\"17%\">";
        $che1=$che2=$che3='';
        if($_SESSION['Diagnosticos_QX'.$pfj][$paso][$codigo][$diagnostico]==1){
          $che1='checked';
        }elseif($_SESSION['Diagnosticos_QX'.$pfj][$paso][$codigo][$diagnostico]==2){
          $che2='checked';
        }elseif($_SESSION['Diagnosticos_QX'.$pfj][$paso][$codigo][$diagnostico]==3){
          $che3='checked';
        }elseif($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj][$codigo][$diagnostico]==1){
          $che1='checked';
        }elseif($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj][$codigo][$diagnostico]==2){
          $che2='checked';
        }elseif($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj][$codigo][$diagnostico]==3){
          $che3='checked';
        }else{
          $che1='checked';
        }
				$this->salida.="<input $che1 type=\"radio\" name=\"dx".$pfj."[$codigo]\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input $che2 type=\"radio\" name=\"dx".$pfj."[$codigo]\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input $che3 type=\"radio\" name=\"dx".$pfj."[$codigo]\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
				if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
				if($_SESSION['Diagnosticos_QX'.$pfj][$paso][$codigo]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$pfj."[$codigo]\" value =\"".$diagnostico."\" checked></td>";
				}elseif($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj][$codigo]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$pfj."[$codigo]\" value =\"".$diagnostico."\" checked></td>";
				}else{
				  $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$pfj."[$codigo]\" value =\"".$diagnostico."\"></td>";
				}
				$this->salida.="</tr>";
			}
               $this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardarDiag$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraExamenes_Avanzada(4);
			if(!empty($var)){
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
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_de_solicitud_diagnosticos'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }

	//nuevas funciones para lo de materiales
  function frmForma_Seleccion_EquiposQX($vector){

		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('EQUIPOS QUIRURGICOS');
		$this->salida .= "<script>";
    $this->salida .= "function SeleccionQuiro(frm,valor){";
    $this->salida .= "  if(valor=='F'){";
    $this->salida .= "    frm.Quirofano$pfj.disabled=false;";
		$this->salida .= "  }else{";
    $this->salida .= "    frm.Quirofano$pfj.disabled=true;";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .= "</script>";
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EquiposQX',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">TIPO EQUIPO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = 'tipoEquipo$pfj'  class =\"select\" onchange=\"SeleccionQuiro(this.form,this.value)\">";
		if($_REQUEST['tipoEquipo'.$pfj]==-1){
      $selected='selected';
		}elseif(($_REQUEST['tipoEquipo'.$pfj]=='M')){
      $selected1='selected';
		}elseif(($_REQUEST['tipoEquipo'.$pfj]=='F')){
      $selected2='selected';
		}
    $this->salida.="<option value = '-1' $selected>Todos</option>";
		$this->salida.="<option value = 'M' $selected1>Movil</option>";
		$this->salida.="<option value = 'F' $selected2>Fijo</option>";
		$this->salida.="</select>";
		$this->salida.="</td>";
    if($_REQUEST['tipoEquipo'.$pfj]!='F'){
      $disable='disabled';
		}
		$this->salida.="<td width=\"5%\">QUIROFANO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = 'Quirofano$pfj'  class =\"select\" $disable>";
		$this->salida.="<option value = '-1' >Todos</option>";
		$quiros = $this->QuirofanosTotal();
		for($i=0;$i<sizeof($quiros);$i++){
			if(($_REQUEST['Quirofano'.$pfj])!= $quiros[$i]['quirofano']){
				$this->salida.="<option value = ".$quiros[$i]['quirofano'].">".$quiros[$i]['descripcion']."</option>";
			}else{
				$this->salida.="<option value = ".$quiros[$i]['quirofano']." selected >".$quiros[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' name = 'descripcionEquipo$pfj'   value =\"".$_REQUEST['descripcionEquipo'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EquiposQX','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'tipoEquipo'.$pfj=>$_REQUEST['tipoEquipo'.$pfj],'Quirofano'.$pfj=>$_REQUEST['Quirofano'.$pfj],'descripcionEquipo'.$pfj=>$_REQUEST['descripcionEquipo'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="  <td width=\"10%\">TIPO</td>";
			$this->salida.="  <td width=\"15%\">DESCRIPCION</td>";
			$this->salida.="  <td width=\"5%\">CANTIDAD</td>";
			$this->salida.="  <td width=\"5%\">SELECCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
				if($i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">".$vector[$i]['tipo']."</td>";
				$this->salida.="  <td align=\"left\" width=\"50%\">".$vector[$i]['descripcion']."</td>";
				if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
				if($_SESSION['Equipos'.$pfj][$paso][$vector[$i]['tipoid']."||//".$vector[$i]['tipo']]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"text\" size=\"4\" name=\"".$vector[$i]['tipoid']."||//".$vector[$i]['tipo']."".$pfj."\" value=\"".$_SESSION['Equipos'.$pfj][$paso][$vector[$i]['tipoid']."||//".$vector[$i]['tipo']][$vector[$i]['descripcion']]."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"seleccion".$pfj."[".$vector[$i]['tipoId']."||//".$vector[$i]['tipo']."]\" value=\"".$vector[$i]['descripcion']."\" checked></td>";
				}elseif($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj][$vector[$i]['tipoid']."||//".$vector[$i]['tipo']][$vector[$i]['descripcion']]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input class=\"input-text\" type=\"text\" size=\"4\" name=\"".$vector[$i]['tipoid']."||//".$vector[$i]['tipo']."".$pfj."\" value=\"".$_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj][$vector[$i]['tipoid']."||//".$vector[$i]['tipo']][$vector[$i]['descripcion']]."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"seleccionEquipos".$pfj."[".$vector[$i]['tipoid']."||//".$vector[$i]['tipo']."]\" value=\"".$vector[$i]['descripcion']."\" checked></td>";
				}else{
					$this->salida.="  <td align=\"center\" width=\"5%\"><input class=\"input-text\" type=\"text\" size=\"4\" name=\"".$vector[$i]['tipoid']."||//".$vector[$i]['tipo']."".$pfj."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"seleccionEquipos".$pfj."[".$vector[$i]['tipoid']."||//".$vector[$i]['tipo']."]\" value=\"".$vector[$i]['descripcion']."\"></td>";
				}
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			/*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
			if ($this->conteo == 1)
			{
					$this->Insertar_Solicitud($cargo,$apoyod_tipo_id);
			}*/
			$var=$this->RetornarBarraExamenes_Avanzada(2);
			if(!empty($var)){
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
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_de_solicitud_equipos'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
 }



  //nuevas funciones para lo de materiales
  function frmForma_Seleccion_EstanciaQX($vector){

		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('ESTANCIA');
		$this->salida .= "<script>";
    $this->salida .= "function SeleccionQuiro(frm,valor){";
    $this->salida .= "  if(valor=='F'){";
    $this->salida .= "    frm.Quirofano$pfj.disabled=false;";
		$this->salida .= "  }else{";
    $this->salida .= "    frm.Quirofano$pfj.disabled=true;";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .= "</script>";
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EstanciaQX',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"3\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"20%\">DESCRIPCION TIPO CLASE CAMA:</td>";
		$this->salida .="<td width=\"30%\" align='left'><input type='text' size=\"40\" class='input-text' name = 'descripcionEstancia$pfj'   value =\"".$_REQUEST['descripcionEstancia'.$pfj]."\"></td>" ;
		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EstanciaQX','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'descripcionEstancia'.$pfj=>$_REQUEST['descripcionEstancia'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		
		if($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="  <td>TIPO CLASE CAMA</td>";
			$this->salida.="  <td width=\"10%\">No. DIAS</td>";
			$this->salida.="  <td width=\"10%\">PRE QX</td>";
			$this->salida.="  <td width=\"10%\">POS QX</td>";
			$this->salida.="  <td width=\"10%\">SELECCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
				if($i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"left\">".$vector[$i]['descripcion']."</td>";
				if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
				if($_SESSION['Estacion'.$pfj][$paso][$vector[$i]['tipo_clase_cama_id']]){
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type=\"text\" size=\"4\" name=\"".$vector[$i]['tipo_clase_cama_id']."$pfj\" value=\"".$_SESSION['Estacion'.$pfj][$paso][$vector[$i]['tipo_clase_cama_id']][$vector[$i]['descripcion']]."\"></td>";
					if($_SESSION['Estacion'.$pfj][$paso][$vector[$i]['tipo_clase_cama_id']]['PRE']=='1'){
					  $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPREEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\" checked></td>";
					}else{
            $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPREEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\"></td>";
					}
					if($_SESSION['Estacion'.$pfj][$paso][$vector[$i]['tipo_clase_cama_id']]['POS']=='1'){
					  $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPOSEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\" checked></td>";
					}else{
            $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPOSEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\"></td>";
					}
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" checked value=\"".$vector[$i]['descripcion']."\"></td>";
				}elseif($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]){
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type=\"text\" size=\"4\" name=\"".$vector[$i]['tipo_clase_cama_id']."$pfj\" value=\"".$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']][$vector[$i]['descripcion']]."\"></td>";
					if($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['PRE']=='1'){
            $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPREEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\" checked></td>";
					}else{
            $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPREEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\"></td>";
					}
					if($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['POS']=='1'){
            $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPOSEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\" checked></td>";
					}else{
            $this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPOSEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\"></td>";
					}
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"".$vector[$i]['descripcion']."\" checked></td>";
				}else{
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type=\"text\" size=\"4\" name=\"".$vector[$i]['tipo_clase_cama_id']."$pfj\"></td>";
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPREEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\"></td>";
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionPOSEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"1\"></td>";
					$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= \"seleccionEstacion".$pfj."[".$vector[$i]['tipo_clase_cama_id']."]\" value=\"".$vector[$i]['descripcion']."\"></td>";
				}
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"5\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			/*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
			if ($this->conteo == 1)
			{
					$this->Insertar_Solicitud($cargo,$apoyod_tipo_id);
			}*/
			$var=$this->RetornarBarraExamenes_Avanzada(3);
			if(!empty($var)){
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
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_de_solicitud_estacion'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }
//nuevas funciones para lo de apoyos
  function frmForma_Seleccion_Apoyos($vectorAPD){

		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO');
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">TIPO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
		$this->salida.="<option value = '001' selected>Todos</option>";
		if (($_REQUEST['criterio1'.$pfj])  == '002'){
			$this->salida.="<option value = '002' selected>Frecuentes</option>";
		}else{
			$this->salida.="<option value = '002' >Frecuentes</option>";
		}
		$categoria = $this->GruposTiposCargos();
		for($i=0;$i<sizeof($categoria);$i++){
			$apoyod_tipo_id = $categoria[$i][grupo_tipo_cargo];
			$opcion = $categoria[$i][descripcion];
			if(($_REQUEST['criterio1'.$pfj])  != $categoria[$i][grupo_tipo_cargo]){
				$this->salida.="<option value = ".$categoria[$i][grupo_tipo_cargo].">$opcion</option>";
			}else{
				$this->salida.="<option value = ".$categoria[$i][grupo_tipo_cargo]." selected >$opcion</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="<td width=\"6%\">CARGO:</td>";
		$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10    name = 'cargo$pfj'  value =\"".$_REQUEST['cargo'.$pfj]."\"    ></td>" ;
		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text'     name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if($vectorAPD){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"20%\">TIPO</td>";
			$this->salida.="  <td width=\"10%\">CARGO</td>";
			$this->salida.="  <td width=\"60%\">DESCRIPCION</td>";
			$this->salida.="  <td width=\"5%\">CANTIDAD</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			//unset($_SESSION['Apoyos_Procedimientos'.$pfj]);
			for($i=0;$i<sizeof($vectorAPD);$i++){
				if($i % 2){$estilo='modulo_list_claro';}
				else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorAPD[$i][tipo]."</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorAPD[$i][cargo]."</td>";
				$this->salida.="  <td align=\"left\" width=\"50%\">".$vectorAPD[$i][descripcion]."</td>";
      if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
				if($_SESSION['Apoyos_Procedimientos'.$pfj][$paso][$vectorAPD[$i][cargo]]){
          $this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"text\" size=\"4\" name=\"".$vectorAPD[$i][cargo]."".$pfj."\" value=\"".$_SESSION['Apoyos_Procedimientos'.$pfj][$paso][$vectorAPD[$i][cargo]][$vectorAPD[$i][descripcion].'||//'.$vectorAPD[$i][apoyod_tipo_id]]."\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"op".$pfj."[".$vectorAPD[$i][cargo]."]\" value=\"".$vectorAPD[$i][descripcion]."||//".$vectorAPD[$i][apoyod_tipo_id]."\" checked></td>";
				}elseif($_SESSION['SOLICITUD_APOYOS_QX'.$pfj][$vectorAPD[$i][cargo]]){
					$this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"text\" class=\"input-text\" size=\"4\" name=\"".$vectorAPD[$i][cargo]."".$pfj."\" value=\"".$_SESSION['SOLICITUD_APOYOS_QX'.$pfj][$vectorAPD[$i][cargo]][$vectorAPD[$i][descripcion].'||//'.$vectorAPD[$i][apoyod_tipo_id]]."\"></td>";
				  $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"op".$pfj."[".$vectorAPD[$i][cargo]."]\" value = '".$vectorAPD[$i][descripcion]."||//".$vectorAPD[$i][apoyod_tipo_id]."' checked></td>";
				}else{
				  $this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"text\" class=\"input-text\" size=\"4\" name=\"".$vectorAPD[$i][cargo]."".$pfj."\"></td>";
				  $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"op".$pfj."[".$vectorAPD[$i][cargo]."]\" value = '".$vectorAPD[$i][descripcion]."||//".$vectorAPD[$i][apoyod_tipo_id]."'></td>";
				}
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"5\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			/*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
			if ($this->conteo == 1)
			{
					$this->Insertar_Solicitud($cargo,$apoyod_tipo_id);
			}*/
			$var=$this->RetornarBarraExamenes_Avanzada();
			if(!empty($var)){
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
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_de_solicitud_de_apoyos'));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }


 //cor - jea - ads
	function RetornarBarraExamenes_Avanzada($param)//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso)){
				$paso=1;
		}
		if($param==1){
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Materiales',
			'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'grupo'.$pfj=>$_REQUEST['grupo'.$pfj],
			'codigoProducto'.$pfj=>$_REQUEST['codigoProducto'.$pfj],
			'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
		}elseif($param==2){
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EquiposQX',
			'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'tipoEquipo'.$pfj=>$_REQUEST['tipoEquipo'.$pfj],
			'Quirofano'.$pfj=>$_REQUEST['Quirofano'.$pfj],
			'descripcionEquipo'.$pfj=>$_REQUEST['descripcionEquipo'.$pfj]));
		}elseif($param==3){
      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_EstanciaQX',
			'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'descripcionEstancia'.$pfj=>$_REQUEST['descripcionEstancia'.$pfj]));
		}elseif($param==4){
      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos_Medicos',
			'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
			'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
			'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		}else{
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Apoyos',
			'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
			'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
			'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
			'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
			$diferencia=$numpasos-9;
			if($diferencia<=0){
					$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
					$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos)){
			if($numpasos>10){
					$valor=10+3;
			}else{
					$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}else{
			if($numpasos>10){
				$valor=10+5;
			}else{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function formaConsultaAutorizaciones($SolicitudId,$CargoPrincipal,$NombreCargo,$ingresoId,$EvolucionId){
    $pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('AUTORIZACIONES REALIZADAS');
		//$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos_Medicos',
		//'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="  <table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="  <tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan=\"2\" align=\"center\">AUTORIZACIONES DE LA SOLICITUD QX No. ".$SolicitudId."</td>";
		$this->salida.="  </tr>";
		$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
    $this->salida.="  <td width=\"15%\">PROCEDIMIENTO PRINCIPAL</td>";
		$this->salida.="  <td  align=\"left\">$CargoPrincipal - $NombreCargo</td>";
		$this->salida.="  </tr>";
		$datos=$this->DatosSolicitudQX($SolicitudId);
		if($datos){
		$this->salida.="  <tr>";
    $this->salida.="  <td colspan=\"2\" width=\"100%\">";
    $this->salida.="    <table  align=\"center\" cellspacing=\"1\" cellpadding=\"0\" border=\"0\"  width=\"100%\">";
    $this->salida.="    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="    <td width=\"19%\" align=\"left\" class=\"label\">TIPO CIRUGIA</td>";
		$this->salida.="    <td width=\"30%\" align=\"left\" class=\"label\">".$datos['tipocirugia']."</td>";
		$this->salida.="    <td width=\"19%\" align=\"left\" class=\"label\">AMBITO CIRUGIA</td>";
		$this->salida.="    <td align=\"left\" class=\"label\">".$datos['ambito']."</td>";
		$this->salida.="    </tr>";
		$this->salida.="    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="    <td width=\"19%\" align=\"left\" class=\"label\">FINALIDAD CIRUGIA</td>";
		$this->salida.="    <td align=\"left\" class=\"label\" colspan=\"3\">".$datos['finalidad']."</td>";
		$this->salida.="    </tr>";
		$this->salida.="    </table>";
    $this->salida.="  </td>";
    $this->salida.="  </tr>";
		if($datos['observacion']){
    $this->salida.="  <tr>";
    $this->salida.="  <td colspan=\"2\" width=\"100%\">";
    $this->salida.="    <table  align=\"center\" cellspacing=\"1\" cellpadding=\"0\" border=\"0\"  width=\"100%\">";
    $this->salida.="    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="    <td width=\"15%\" align=\"left\">OBSERVACIONES</td>";
		$this->salida.="    <td align=\"left\">".$datos['observacion']."</td>";
		$this->salida.="    </tr>";
		$this->salida.="    </table>";
    $this->salida.="  </td>";
    $this->salida.="  </tr>";
		}
		}
		//diagnostivos
		$diagnosticos=$this->DiagnosticosSolicitudQX($SolicitudId);
		if($diagnosticos){
		$this->salida.="  <tr>";
    $this->salida.="  <td colspan=\"2\" width=\"100%\">";
    $this->salida.="    <table  align=\"center\" cellspacing=\"1\" cellpadding=\"0\" border=\"0\"  width=\"100%\">";
		for($i=0;$i<sizeof($diagnosticos);$i++){
		$this->salida.="    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="    <td align=\"left\">DIAGNOSTICO ".($i+1)."</td>";
		$this->salida.="    <td align=\"left\">".$diagnosticos[$i]['diagnostico_nombre']."</td>";
		$this->salida.="    </tr>";
		}
		$this->salida.="    </table>";
		$this->salida.="  </td>";
    $this->salida.="  </tr>";
    }
		//OTROS PROCEDIMIENTOS
		$procedimientos=$this->ProcedimientosAutorizados($SolicitudId);
		if($procedimientos){
			$this->salida.="  <tr class=\"modulo_table_title\">";
			$this->salida.="  <td colspan=\"2\" align=\"center\">OTROS PROCEDIMIENTOS QX</td>";
			$this->salida.="  </tr>";
			$this->salida.="  <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
			$this->salida.="      <table  align=\"center\" border=\"0\"  width=\"98%\">";
			for($i=0;$i<sizeof($procedimientos);$i++){
			  $this->salida.="      <tr><td>&nbsp;</td></tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">PROCEDIMIENTO</td>";
				$this->salida.="      <td>".$procedimientos[$i]['procedimiento_id']." - ".$procedimientos[$i]['descripcion']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">ESTADO</td>";
				if($procedimientos[$i]['sw_estado']=='1'){
				  $this->salida.="      <td class=\"label\">AUTORIZADO - No. AUTORIZACION: ".$procedimientos[$i]['autorizacion']."</td>";
				}else{
          $this->salida.="      <td class=\"label\">NO AUTORIZADO - No. AUTORIZACION: ".$procedimientos[$i]['autorizacion']."</td>";
				}
				$this->salida.="      </tr>";
				(list($fecha,$hora)=explode(' ',$procedimientos[$i]['fecha_registro']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">FECHA / USUARIO</td>";
				$this->salida.="      <td>".strftime("%b %d de %Y",$FechaConver1)." / ".$procedimientos[$i]['usuario']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">OBSERVACIONES</td>";
				$this->salida.="      <td>".$procedimientos[$i]['observaciones']."</td>";
				$this->salida.="      </tr>";
			}
      $this->salida.="      </table><BR>";
			$this->salida.="  </td></tr>";
		}
		//OTROS PROCEDIMIENTOS QX NO QX Y APOYOS
    $pmtosApoyos=$this->ProcedimientosApoyosAutorizados($SolicitudId);
		if($pmtosApoyos){
			$this->salida.="  <tr class=\"modulo_table_title\">";
			$this->salida.="  <td colspan=\"2\" align=\"center\">OTROS CARGOS QX, NO QX Y APOYOS</td>";
			$this->salida.="  </tr>";
			$this->salida.="  <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
			$this->salida.="      <table  align=\"center\" border=\"0\"  width=\"98%\">";
			for($i=0;$i<sizeof($pmtosApoyos);$i++){
			  $this->salida.="      <tr><td>&nbsp;</td></tr>";
			  $this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">TIPO CARGO</td>";
				$this->salida.="      <td colspan=\"3\">".$pmtosApoyos[$i]['tipocargo']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">CARGO</td>";
				$this->salida.="      <td>".$pmtosApoyos[$i]['cargo']." - ".$pmtosApoyos[$i]['descripcion']."</td>";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">CANTIDAD</td>";
				$this->salida.="      <td width=\"10%\" nowrap>".$pmtosApoyos[$i]['cantidad']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">ESTADO</td>";
				if($pmtosApoyos[$i]['sw_estado']=='1'){
				  $this->salida.="      <td colspan=\"3\" class=\"label\">AUTORIZADO - No. AUTORIZACION: ".$pmtosApoyos[$i]['autorizacion']."</td>";
				}else{
          $this->salida.="      <td colspan=\"3\" class=\"label\">NO AUTORIZADO - No. AUTORIZACION: ".$pmtosApoyos[$i]['autorizacion']."</td>";
				}
				$this->salida.="      </tr>";
				(list($fecha,$hora)=explode(' ',$pmtosApoyos[$i]['fecha_registro']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">FECHA / USUARIO</td>";
				$this->salida.="      <td colspan=\"3\">".strftime("%b %d de %Y",$FechaConver1)." / ".$pmtosApoyos[$i]['usuario']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">OBSERVACIONES</td>";
				$this->salida.="      <td colspan=\"3\">".$pmtosApoyos[$i]['observaciones']."</td>";
				$this->salida.="      </tr>";
			}
			$this->salida.="  </table>";
			$this->salida.="  </td></tr>";
		}
		//MATERIAL DE OSTEOSINTESIS Y OTROS
    $materiales=$this->MaterialesAutorizados($SolicitudId);
		if($materiales){
			$this->salida.="  <tr class=\"modulo_table_title\">";
			$this->salida.="  <td colspan=\"2\" align=\"center\">MATERIALES REQUERIDOS</td>";
			$this->salida.="  </tr>";
			$this->salida.="  <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
			$this->salida.="      <table  align=\"center\" border=\"0\"  width=\"98%\">";
			for($i=0;$i<sizeof($materiales);$i++){
			  $this->salida.="      <tr><td>&nbsp;</td></tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">PRODUCTO</td>";
				$this->salida.="      <td>".$materiales[$i]['codigo_producto']." - ".$materiales[$i]['descripcion']."</td>";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">CANTIDAD</td>";
				$this->salida.="      <td width=\"10%\" nowrap>".$materiales[$i]['cantidad']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">ESTADO</td>";
				if($materiales[$i]['sw_estado']=='1'){
				  $this->salida.="      <td colspan=\"3\" class=\"label\">AUTORIZADO - No. AUTORIZACION: ".$materiales[$i]['autorizacion']."</td>";
				}else{
          $this->salida.="      <td colspan=\"3\" class=\"label\">NO AUTORIZADO - No. AUTORIZACION: ".$materiales[$i]['autorizacion']."</td>";
				}
				$this->salida.="      </tr>";
				(list($fecha,$hora)=explode(' ',$materiales[$i]['fecha_registro']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">FECHA / USUARIO</td>";
				$this->salida.="      <td colspan=\"3\">".strftime("%b %d de %Y",$FechaConver1)." / ".$materiales[$i]['usuario']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">OBSERVACIONES</td>";
				$this->salida.="      <td colspan=\"3\">".$materiales[$i]['observaciones']."</td>";
				$this->salida.="      </tr>";
			}
			$this->salida.="      </table>";
			$this->salida.="  </td></tr>";
		}

		//ESTANCIA
    $estancia=$this->EstanciaAutorizada($SolicitudId);
		if($estancia){
			$this->salida.="  <tr class=\"modulo_table_title\">";
			$this->salida.="  <td colspan=\"2\" align=\"center\">ESTANCIA SOLICITADA</td>";
			$this->salida.="  </tr>";
			$this->salida.="  <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
			$this->salida.="      <table  align=\"center\" border=\"0\"  width=\"98%\">";
			for($i=0;$i<sizeof($estancia);$i++){
			  $this->salida.="      <tr><td>&nbsp;</td></tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">TIPO CLASE CAMA</td>";
				$this->salida.="      <td width=\"20%\" nowrap>".$estancia[$i]['clasecama']."</td>";
				$this->salida.="      <td width=\"10%\" nowrap class=\"label\">No. DIAS</td>";
				$this->salida.="      <td width=\"10%\" nowrap>".$estancia[$i]['cantidad_dias']."</td>";
				$this->salida.="      <td width=\"10%\" nowrap class=\"label\">PRE QX</td>";
				if($estancia[$i]['sw_pre_qx']=='1'){
				$this->salida.="      <td width=\"10%\" nowrap>Si</td>";
				}else{
        $this->salida.="      <td width=\"10%\" nowrap>No</td>";
				}
				$this->salida.="      <td width=\"10%\" nowrap class=\"label\">POS QX</td>";
				if($estancia[$i]['sw_pos_qx']=='1'){
				$this->salida.="      <td width=\"10%\" nowrap>Si</td>";
				}else{
        $this->salida.="      <td width=\"10%\" nowrap>No</td>";
				}
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">ESTADO</td>";
				if($estancia[$i]['sw_estado']=='1'){
				  $this->salida.="      <td colspan=\"3\" class=\"label\">AUTORIZADO - No. AUTORIZACION: ".$estancia[$i]['autorizacion']."</td>";
				}else{
          $this->salida.="      <td colspan=\"3\" class=\"label\">NO AUTORIZADO - No. AUTORIZACION: ".$estancia[$i]['autorizacion']."</td>";
				}
        $this->salida.="      <td colspan=\"2\" class=\"label\">TIPO CAMA</td>";
				$this->salida.="      <td colspan=\"2\">".$estancia[$i]['tipocama']."</td>";

				$this->salida.="      </tr>";
				(list($fecha,$hora)=explode(' ',$estancia[$i]['fecha_registro']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				$FechaConver1=mktime(0,0,0,$mes,$dia,$ano);
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">FECHA / USUARIO</td>";
				$this->salida.="      <td colspan=\"7\">".strftime("%b %d de %Y",$FechaConver1)." / ".$estancia[$i]['usuario']."</td>";
				$this->salida.="      </tr>";
				$this->salida.="      <tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="      <td width=\"20%\" nowrap class=\"label\">OBSERVACIONES</td>";
				$this->salida.="      <td colspan=\"7\">".$estancia[$i]['observaciones']."</td>";
				$this->salida.="      </tr>";
			}
			$this->salida.="      </table>";
			$this->salida.="  </td></tr>";
		}

    $this->salida.="      </table>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


}
?>
