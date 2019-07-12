<?php
	/**
	* $Id: app_Cafeteria_userclasses_HTML.php,v 1.5 2009/06/04 20:31:24 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.5 $ 	
	* @author 
	*
	*/
  class app_Cafeteria_userclasses_HTML extends app_Cafeteria_user
{

	function app_Cafeteria_user_HTML()
	{
		$this->app_Cafeteria_user(); 
		$this->salida='';
		return true;
	}
	
	function SetStyle($campo)
	{
			if ($this->frmError[$campo] || $campo=="MensajeError")
			{
					if ($campo=="MensajeError")
					{
							$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
			}
			return ("label");
	}
  	/**
  	*
  	*/
  	function FrmConsulta($empresa='')
  	{
  		$this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE DIETAS');
  		
      $empresas = $this->ConsultaEmpresa();
      
  		if($empresa!='')
      {
  			$empresa_id=$empresa;
  		}
      else
      {
  			$empresa_id=$_REQUEST['empresa'];
  		}
      
      if(sizeof($empresas) == 1 && !$empresa_id)
          $empresa_id = $empresas[0]['empresa_id'];

  		if(($empresa_id=='-1')||(empty($empresa_id))){
  			$estadoInf = 'disabled';
  		}else{
  			$estadoInf = 'enable';
  		}
  		
  		if(!empty($_REQUEST['permisocafeteria']))
      {
        SessionSetVar("permisocaf",$_REQUEST['permisocafeteria']);
      }
  		$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
  		$this->salida .= $this->SetStyle("MensajeError");
  		$this->salida.="</table>";

  		$this->salida .= "<script>\n";
  		$this->salida .= "  function filtroempresa(valor)"."\n";
  		$this->salida .= "  {\n";
  		$accion2=ModuloGetUrl('app','Cafeteria','user','FrmConsulta');
  		$this->salida .= "    window.location.href=\"".$accion2."&empresa=\"+valor;\n";
  		$this->salida .= "  }\n";
  		$this->salida .= "</script>\n";
  		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\" class=\"modulo_table_list\">";
  		$this->salida.="	<tr class=\"modulo_table_title\">";
  		$this->salida.="		<td colspan = 3 align=\"center\" width=\"100%\">DATOS BASICOS PARA LA CONSULTA</td>";
  		$this->salida.="	</tr>";
  		//Empresas      
  		$this->salida .= "	<tr colspan = 3>";
  		$this->salida .= "		<td width=\"30%\" align=\"center\" class=\"modulo_table_title\">EMPRESAS</td>";
  		$this->salida .= "		<td width=\"70%\" ><select name=\"empresa\" onchange=\"filtroempresa(this.value)\" class=\"select\">";
  		$this->salida .= "  	<option value= \"-1\" >SELECCIONE</option>";
  		for($j=0;$j< sizeof($empresas);$j++)
      {
  			if($empresas[$j][empresa_id]==$empresa_id)
        {
  				$this->salida .=" <option value= '".$empresas[$j][empresa_id]."' selected>".$empresas[$j][razon_social]."</option>";
  			}else{
  				$this->salida .=" <option value= '".$empresas[$j][empresa_id]."'>".$empresas[$j][razon_social]."</option>";
  			}
  		}
  		$this->salida.="			</select>";
      
  		$this->salida.="		</td>";
  		$this->salida.="	</tr>";	
  		
  		$this->salida.="	<tr colspan = 3 class=\"hc_table_submodulo_list_title\"> ";
  		$this->salida.="		<td colspan = 3 align=\"center\" width=\"100%\">TIPOS DE CONSULTA</td>";
  		$this->salida.="	</tr>";
  		
  		$this->salida.="	<tr>";	
  		$accionTxD=ModuloGetURL('app','Cafeteria','user','BuscaDatos',array('empresa'=>$empresa_id,'Consulta'=>'TxD'));//Total por dieta
  		$accionTxE=ModuloGetURL('app','Cafeteria','user','BuscaDatos',array('empresa'=>$empresa_id,'Consulta'=>'TxE'));//Total por estacion
  		$accionDET=ModuloGetURL('app','Cafeteria','user','BuscaDatos',array('empresa'=>$empresa_id,'Consulta'=>'DET'));//total detallado
  		$this->salida.="	<td>";	
  		$this->salida.="		<table  align=\"center\" border=\"0\"  width=\"80%\">";
  		$this->salida.="			<tr>";
  		$this->salida.="				<form name=\"forma\" action=\"$accionTxD\" method=\"post\">";
  		$this->salida.="				<td  align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"TOTAL x DIETA\" $estadoInf></form></td>";
  		$this->salida.="			</tr>";
  		$this->salida.="		</table>";
  		$this->salida.="	</td>";	
  		$this->salida.="	<td>";	
  		$this->salida.="		<table  align=\"center\" border=\"0\"  width=\"80%\">";
  		$this->salida.="			<tr>";
  		$this->salida.="				<form name=\"forma\" action=\"$accionTxE\" method=\"post\">";
  		$this->salida.="				<td  align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"TOTAL x ESTACION\" $estadoInf></form></td>";
  		$this->salida.="			</tr>";
  		$this->salida.="		</table>";
  		$this->salida.="	</td>";	
  		$this->salida.="	<td>";	
  		$this->salida.="		<table  align=\"center\" border=\"0\"  width=\"80%\">";
  		$this->salida.="			<tr>";
  		$this->salida.="				<form name=\"forma\" action=\"$accionDET\" method=\"post\">";
  		$this->salida.="				<td  align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"TOTAL DETALLADO\" $estadoInf></form></td>";
  		$this->salida.="			</tr>";
  		$this->salida.="		</table>";
  		$this->salida.="	</td>";
  		$this->salida.="</table><br>\n";
      
   		$accionVolver =ModuloGetURL('app','Cafeteria','user','main');
      $this->salida .= "<center>\n";
      $this->salida .= "  <a href=\"".$accionVolver."\" class=\"label_error\">\n";
      $this->salida .= "    VOLVER\n";
      $this->salida .= "  </a>\n";
      $this->salida .= "</center>";

  		$this->salida.=ThemeCerrarTabla();
  		return true;
  	}


	/**
	*
	*/
	function InfTotalxDieta($empresa_id)
	{
		$ingresos=$this->ConsultaDietasEstaciones('2');
		
		foreach($ingresos as $k=>$v)
		{
			foreach($v as $solicitud_id => $sol)
			{
				foreach($sol as $dietas_id => $d)
				{
					$valor = 0;
					foreach($d['CARACTERISTICAS'] as $c=>$vc)
					{
						$x=$this->GetValorCaracteristica($vc);
						$valor = $valor + $x['VALOR'];
					}
					$dietas[$solicitud_id][$dietas_id][$valor] = $dietas[$solicitud_id][$dietas_id][$valor]  + 1;
				}
			}
		}
		
		$this->salida= ThemeAbrirTablaSubModulo('CONSULTA TOTALES X DIETA');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\" class=\"modulo_table_list\">";
		
		foreach($dietas As $solicitud_id => $d){
			$solicitud_descripcion = $this->GetDescripcionSolicitud($solicitud_id);
			$this->salida.="	<tr class=\"modulo_table_title\">";
			$this->salida.="		<td colspan = 2 align=\"center\" width=\"100%\">DIETAS PARA : ".$solicitud_descripcion."</td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"modulo_table_title\">";
			$this->salida.="		<td width=\"70%\" align=\"center\">DESCRIPCION</td>";
			$this->salida.="		<td width=\"30%\" align=\"center\">CANTIDAD</td>";
			$this->salida.="	</tr>";
			$control = 0;
			foreach($d AS $dieta_id => $c){
				$dieta_descripcion=$this->GetDescripcionDieta($dieta_id);
				foreach($c AS $caract_id => $card){
					$des_dieta=$dieta_descripcion." ".$this->GetCadenaCaracteristicas($caract_id);
					$control = 1;
						if( $j % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
						$this->salida.="	<tr class=\"$estilo\">";
						$this->salida.="		<td width=\"70%\" align=\"left\">".$des_dieta."</td>";
						$this->salida.="		<td width=\"30%\" align=\"center\">".$card."</td>";
						$this->salida.="	</tr>";
						$this->salida.="<tr>&nbsp;</tr>";
				}
			}
			if($control== 0){
				$this->salida.="	<tr class=\"modulo_list_claro\">";
				$this->salida.="		<td colspan = 2 align=\"center\" width=\"100%\">NO SE REGISTRO NINGUNA DIETA PARA ESTE TIPO DE SOLICITUD</td>";
				$this->salida.="	</tr>";
			}
			$control = 0;
		}
		$this->salida.="</table>";
		$this->salida.="<br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= "<tr>";
		$accion2=ModuloGetURL('app','Cafeteria','user','FrmConsulta',array('empresa'=>$empresa_id));
		$this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="	<tr align=\"center\" class=\"normal_10\">";	
 		//lo de alex
 		$rep= new GetReports();
 		$mostrar=$rep->GetJavaReport('app','Cafeteria','examenes_html_InfTotalxDieta',array('empresa_id'=>$empresa_id));
 		$nombre_funcion=$rep->GetJavaFunction();
 		$this->salida .=$mostrar;
 		$this->salida.="		<td width=\"100%\" valign=\"center\" colspan=\"3\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> <br>IMPRIMIR</a></td>";
 		//fin de alex
 		$this->salida.="	</tr>";	
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTabla();
		return true;
	}


	/**
	*
	*/
	function InfTotalxEstacion($empresa_id)
	{
		$estaciones=$this->ConsultaEstaciones($empresa_id);
		$this->salida= ThemeAbrirTablaSubModulo('CONSULTA TOTALES X ESTACION');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\" class=\"modulo_table_list\">";
		
		foreach($estaciones AS $estacion_id => $estacion)
		{
			$ingresos=$this->ConsultaDietasEstaciones('2',$estacion['estacion_id']);
			
			if(!empty($ingresos))
			{
				foreach($ingresos as $k=>$v)
				{
					foreach($v as $solicitud_id => $sol)
					{
						foreach($sol as $dietas_id => $d)
						{
							$valor = 0;
							foreach($d['CARACTERISTICAS'] as $c=>$vc)
							{
								$x=$this->GetValorCaracteristica($vc);
								$valor = $valor + $x['VALOR'];
							}
							$dietas[$solicitud_id][$dietas_id][$valor] = $dietas[$solicitud_id][$dietas_id][$valor]  + 1;
						}
					}
				}
				
				foreach($dietas As $solicitud_id => $d)
				{
					$solicitud_descripcion = $this->GetDescripcionSolicitud($solicitud_id);
					$this->salida.="	<tr class=\"modulo_table_title\">";
					$this->salida.="		<td colspan = 2 align=\"center\" width=\"100%\">DIETAS PARA : ".$solicitud_descripcion."</td>";
					$this->salida.="	</tr>";
					$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="		<td colspan = 2 align=\"center\" width=\"100%\">ESTACION : ".$estacion['descripcion']."</td>";
					$this->salida.="	</tr>";
					$this->salida.="	<tr class=\"modulo_table_title\">";
					$this->salida.="		<td width=\"70%\" align=\"center\">DESCRIPCION</td>";
					$this->salida.="		<td width=\"30%\" align=\"center\">CANTIDAD</td>";
					$this->salida.="	</tr>";
					$control = 0;
					foreach($d AS $dieta_id => $c)
					{
						$dieta_descripcion=$this->GetDescripcionDieta($dieta_id);
						foreach($c AS $caract_id => $card)
						{
							$des_dieta=$dieta_descripcion." ".$this->GetCadenaCaracteristicas($caract_id);
							$control = 1;
								if( $j % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
								$this->salida.="	<tr class=\"$estilo\">";
								$this->salida.="		<td width=\"70%\" align=\"left\">".$des_dieta."</td>";
								$this->salida.="		<td width=\"30%\" align=\"center\">".$card."</td>";
								$this->salida.="	</tr>";
								$this->salida.="<tr>&nbsp;</tr>";
						}
					}
					if($control== 0)
					{
						$this->salida.="	<tr class=\"modulo_list_claro\">";
						$this->salida.="		<td colspan = 2 align=\"center\" width=\"100%\">NO SE REGISTRO NINGUNA DIETA PARA ESTE TIPO DE SOLICITUD</td>";
						$this->salida.="	</tr>";
					}
					$control = 0;
				}
			}
		}
		$this->salida.="</table>";
		$this->salida.="<br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= "<tr>";
		$accion2=ModuloGetURL('app','Cafeteria','user','FrmConsulta',array('empresa'=>$empresa_id));
		$this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</tr>";
		$this->salida.="	<tr align=\"center\" class=\"normal_10\">";	
 		//lo de alex
 		$rep= new GetReports();
 		$mostrar=$rep->GetJavaReport('app','Cafeteria','examenes_html_InfTotalxEstacion',array('empresa_id'=>$empresa_id));
 		$nombre_funcion=$rep->GetJavaFunction();
 		$this->salida .=$mostrar;
 		$this->salida.="		<td width=\"100%\" valign=\"center\" colspan=\"3\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> <br>IMPRIMIR</a></td>";
 		//fin de alex
 		$this->salida.="	</tr>";	
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTabla();
		return true;
	}


	/**
	*
	*/
	function InfTotalDetallado($empresa_id)
    {
        //print_r($_SESSION["permisocaf"]);
        $path = SessionGetVar("rutaImagenes");
        $accionDET=ModuloGetURL('app','Cafeteria','user','BuscaDatos',array('empresa'=>$empresa_id,'Consulta'=>'DET'));//total detallado
        $desayunos_n=$this->ConsultaDietasEstaciones('1','','','','',1,'','','','0','','0');
        $desayunos_a=$this->ConsultaDietasEstaciones('1','','','','',1,'','','','1','','0');
        $almuerzos_n=$this->ConsultaDietasEstaciones('1','','','','',2,'','','','0','','0');
        $almuerzos_a=$this->ConsultaDietasEstaciones('1','','','','',2,'','','','1','','0');
        $cenas_n=$this->ConsultaDietasEstaciones('1','','','','',3,'','','','0','','0');
        $cenas_a=$this->ConsultaDietasEstaciones('1','','','','',3,'','','','1','','0');
        
        if(!empty($desayunos_n))
        {
            foreach($desayunos_n AS $estaciones => $dietas)
            {$desayunos_normal=count($dietas);}
        }
        else
        {
            $desayunos_normal='0';
        }


        if(!empty($desayunos_a))
        {
            foreach($desayunos_a AS $estaciones => $dietas)
            {$desayunos_adicional=count($dietas);}
        }
        else
        {
            $desayunos_adicional='0';
        }
        


        if(!empty($almuerzos_n))
        {
            foreach($almuerzos_n AS $estaciones => $dietas)
            {$almuerzos_normal=count($dietas);}
        }
        else
        {
            $almuerzos_normal='0';
        }
        

        if(!empty($almuerzos_a))
        {
            foreach($almuerzos_a AS $estaciones => $dietas)
            {$almuerzos_adicional=count($dietas);}
        }
        else
        {
            $almuerzos_adicional='0';
        }
        


        if(!empty($cenas_n))
        {
            foreach($cenas_n AS $estaciones => $dietas)
            {$cenas_normal=count($dietas);}
        }
        else
        {
            $cenas_normal='0';
        }


        if(!empty($cenas_a))
        {
            foreach($cenas_a AS $estaciones => $dietas)
            {$cenas_adicional=count($dietas);}
        }
        else
        {
            $cenas_adicional='0';
        }

        
            $CONSULTADN=ModuloGetURL('app','Cafeteria','user','ListaDeCenas',array('titulo'=>"LISTA DE DESAYUNOS EN HORARIO NORMAL",'empresa'=>$empresa_id,'tipo_solicitud'=>'1','sw_adicional'=>'0'));
            $CONSULTADA=ModuloGetURL('app','Cafeteria','user','ListaDeCenas',array('titulo'=>"LISTA DE DESAYUNOS EN HORARIO ADICIONAL",'empresa'=>$empresa_id,'tipo_solicitud'=>'1','sw_adicional'=>'1'));
            $CONSULTAAN=ModuloGetURL('app','Cafeteria','user','ListaDeCenas',array('titulo'=>"LISTA DE ALMUERZOS EN HORARIO NORMAL",'empresa'=>$empresa_id,'tipo_solicitud'=>'2','sw_adicional'=>'0'));
            $CONSULTAAD=ModuloGetURL('app','Cafeteria','user','ListaDeCenas',array('titulo'=>"LISTA DE ALMUERZOS EN HORARIO ADICIONAL",'empresa'=>$empresa_id,'tipo_solicitud'=>'2','sw_adicional'=>'1'));
            $CONSULTACN=ModuloGetURL('app','Cafeteria','user','ListaDeCenas',array('titulo'=>"LISTA DE CENAS EN HORARIO NORMAL",'empresa'=>$empresa_id,'tipo_solicitud'=>'3','sw_adicional'=>'0'));
            $CONSULTACA=ModuloGetURL('app','Cafeteria','user','ListaDeCenas',array('titulo'=>"LISTA DE CENAS EN HORARIO ADICIONAL",'empresa'=>$empresa_id,'tipo_solicitud'=>'3','sw_adicional'=>'1'));

        $this->salida .= "  <script>\n";
        $this->salida .= "      function Timer()\n";
        $this->salida .= "      {\n";
        $this->salida .= "          window.setTimeout('BuscarLista()', 150000);\n";
        $this->salida .= "      };\n";
        $this->salida .= "      function BuscarLista()\n";
        $this->salida .= "      {\n";
        $this->salida .= "          document.location.href=\"".$accionDET."\";\n";
        $this->salida .= "      }
                                    Timer();\n";
        $this->salida .= "  </script>\n";
        $this->salida .= ThemeAbrirTablaSubModulo('CONSULTA DETALLE DIETA');
        //$this->IncludeJS('RemoteXajax/Cafeteria.js', $contenedor='app', $modulo='Cafeteria');
        //$file ='app_modules/Cafeteria/RemoteXajax/Cafeteria.php';
        //$this->SetXajax(array("Averigua"),$file);
       //echo "aaaaaaaaaaaaa".var_dump($_REQUEST);
       
        $this->salida .= "            <form name=\"menu_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                      <td colspan='2' align=\"center\">\n";
        $this->salida .= "                         MENU DE OPCIONES";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td width='95%'  align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <a  title=\"CONSULTAR DIETAS\" class=\"label_error\" href=\"".$CONSULTADN."\">LISTA DE DESAYUNOS EN HORARIO NORMAL (".$desayunos_normal.")</a>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td  width='5%' align=\"center\" class=\"normal_10AN\">\n";
        if($desayunos_normal==0)
        {
            $this->salida .= "                          &nbsp;\n";
        }
        else
        $this->salida .= "                          <sub><img src=\"".$path."/images/alarm008.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <a  title=\"CONSULTAR DIETAS\" class=\"label_error\" href=\"".$CONSULTADA."\">LISTA DE DESAYUNOS EN HORARIO ADICIONAL (".$desayunos_adicional.")</a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        if($desayunos_adicional==0)
        {
            $this->salida .= "                          &nbsp;\n";
        }
        else
        $this->salida .= "                          <sub><img src=\"".$path."/images/alarm008.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
       
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <a  title=\"CONSULTAR DIETAS\" class=\"label_error\" href=\"".$CONSULTAAN."\">LISTA DE ALMUERZOS EN HORARIO NORMAL (".$almuerzos_normal.")</a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        if($almuerzos_normal==0)
        {
            $this->salida .= "                          &nbsp;\n";
        }
        else
        $this->salida .= "                          <sub><img src=\"".$path."/images/alarm008.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <a  title=\"CONSULTAR DIETAS\" class=\"label_error\" href=\"".$CONSULTAAD."\">LISTA DE ALMUERZOS EN HORARIO ADICIONAL (".$almuerzos_adicional.")</a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        if($almuerzos_adicional==0)
        {
            $this->salida .= "                          &nbsp;\n";
        }
        else
        $this->salida .= "                          <sub><img src=\"".$path."/images/alarm008.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <a  title=\"CONSULTAR DIETAS\" class=\"label_error\" href=\"".$CONSULTACN."\">LISTA DE CENAS EN HORARIO NORMAL (".$cenas_normal.")</a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        if($cenas_normal==0)
        {
            $this->salida .= "                          &nbsp;\n";
        }
        else
        $this->salida .= "                          <sub><img src=\"".$path."/images/alarm008.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <a  title=\"CONSULTAR DIETAS\" class=\"label_error\" href=\"".$CONSULTACA."\">LISTA DE CENAS EN HORARIO ADICIONAL (".$cenas_adicional.")</a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
        if($cenas_adicional==0)
        {
            $this->salida .= "                          &nbsp;\n";
        }
        else
        $this->salida .= "                          <sub><img src=\"".$path."/images/alarm008.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   </table>";
        $this->salida .= "             </form>";
        $accion2=ModuloGetURL('app','Cafeteria','user','FrmConsulta',array('empresa'=>$empresa_id));
        $this->salida .= " <form name=\"volver\" action=\"".$accion2."\" method=\"post\">\n";//".$this->action[0]."
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
//////////////////////////////////////////////////////////////////


		$this->salida.=ThemeCerrarTabla();
		return true;
	}
    /**
    *
    */
    function ListaDeCenas()
    {
        $percaf =SessionGetVar("permisocaf");
        $path = SessionGetVar("rutaImagenes");
        $estaciones=$this->ConsultaEstaciones($_REQUEST['empresa']);
        $this->salida= ThemeAbrirTablaSubModulo($_REQUEST['titulo']);
        $file ='app_modules/Cafeteria/RemoteXajax/Cafeteria.php';
        $this->SetXajax(array("CambiarEstado"),$file);
        $this->IncludeJS('RemoteXajax/Cafeteria.js', $contenedor='app', $modulo='Cafeteria');
        
        $datos=$this->ConsultaDietasEstaciones('1','','','','',$_REQUEST['tipo_solicitud'],'','','',$_REQUEST['sw_adicional']);

        if(!empty($datos))
        {
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
            $this->salida.="    <tr>";
            $this->salida.="        <td colspan ='11' align=\"LEFT\" class='label_error'>".$_REQUEST['titulo']."   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  FECHA ".date("Y-m-d")." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HORA DE IMPRESION ".date("G:i:s")."</td>";
            $this->salida.="    </tr>";
            $this->salida.="</table>";
            $this->salida .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"modulo_table_list\">";
            $this->salida.=" <tr class=\"modulo_table_list\">";
            $this->salida.="     <td colspan = 11 align=\"center\" class='modulo_table_list_title' width=\"100%\">DATOS BASICOS PARA LA CONSULTA</td>";
            $this->salida.=" </tr>";
            $this->salida.=" <tr class=\"modulo_table_list\">";
            $this->salida.="     <td class='modulo_table_list_title' width=\"15%\" align=\"center\">ESTACION</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"5%\" align=\"center\">INGRESO</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"5%\" align=\"center\">CAMA</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"10%\" align=\"center\">SOLICITUD</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"27%\" align=\"center\">PACIENTE</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"4%\" align=\"center\">AYUNO</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"12%\" align=\"center\">DIETA</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"10%\" align=\"center\">MOTIVO AYUNO</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"5%\" align=\"center\">FECHA SOLICITADA</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"7%\" align=\"center\">ESTADO</td>";
            $this->salida.="     <td class='modulo_table_list_title' width=\"5%\" align=\"center\">CONFIRMADA</td>";
            $this->salida.=" </tr>";


            // ($datos);

            foreach($datos AS $estacion => $ingreso)
            {   $estilo='modulo_list_claro';
                $this->salida.="    <tr class=\"".$estilo."\">";
                $this->salida.="        <td rowspan='".count($ingreso)."' align=\"left\">";
                $this->salida.="           ".$estacion."";
                $this->salida.="        </td>";
                foreach($ingreso AS $n_ingreso => $habitacion)
                {
                    $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                    $this->salida.="           ".$n_ingreso."";
                    $this->salida.="        </td>";

                    foreach($habitacion AS $n_habitacion => $tipo_solicitud)
                    {
                        $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                        $this->salida.="           ".$n_habitacion."";
                        $this->salida.="        </td>";
                        foreach($tipo_solicitud AS $des_habitacion => $nom_paciente)
                        {
                            $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                            $this->salida.="           ".$des_habitacion."";
                            $this->salida.="        </td>";

                            foreach($nom_paciente AS $des_nom_paciente => $sw_ayuno)
                            {
                                $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                $this->salida.="           ".$des_nom_paciente."";
                                $this->salida.="        </td>";

                                    foreach($sw_ayuno AS $des_sw_ayuno => $tipo_dieta)
                                    {
                                            //var_dump($tipo_dieta);
                                            $this->salida.="        <td class=\"".$estilo."\" align=\"center\">";
                                            if($des_sw_ayuno=='1')
                                            {

                                                $this->salida.="            <a title='".$observaciones[0]['motivo_ayuno']."' href='#'><sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                            }
                                            elseif($des_sw_ayuno==' ')
                                            {
                                                $this->salida.="            <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                                            }
                                            $this->salida.="        </td>";

                                            foreach($tipo_dieta AS $observacion => $CARACTERISTICAS)
                                            {
                                                $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                                $solosalida="           ".$observacion."";
                                            }
                                             
                                            foreach($CARACTERISTICAS AS $des_caracteristicas => $DATOX)
                                            {
                                                $this->salida.="           ".$des_caracteristicas."";
                                                for($i=0;$i<count($DATOX);$i++)
                                                {
                                                    $this->salida.="           ,".$DATOX[$i]['descripcion_caracteristica']."";
                                                }
                                                $this->salida.= ". &nbsp".$solosalida;
                                                $this->salida.="        </td>";
                                                $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                                $this->salida.="           ".$DATOX[0]['motivo_ayuno']."";
                                                $this->salida.="        </td>";
                                                $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                                $this->salida.="           ".substr($DATOX[0]['fecha_confirmacion'],0,19)."";
                                                $this->salida.="        </td>";
                                                if($DATOX[0]['estado_dieta']==1)
                                                {
                                                   
                                                    $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                                    $this->salida.="           ACTIVA";
                                                    $this->salida.="        </td>";
                                                }
                                                elseif($DATOX[0]['estado_dieta']==0)
                                                {
                                                    $this->salida.="        <td bgcolor=\"#FF5555\" align=\"left\">";
                                                    $this->salida.="          CANCELADA:  ".$DATOX[0]['motivo_cancelacion_dieta']." USUARIO: ".$DATOX[0]['usuario_id_cancelacion'];
                                                    $this->salida.="        </td>";

                                                }

                                                elseif($DATOX[0]['estado_dieta']==2)
                                                {
                                                    $this->salida.="        <td bgcolor=\"#55FF55\" align=\"left\">";
                                                    $this->salida.="          REACTIVADA:  ".$DATOX[0]['motivo_cancelacion_dieta']." USUARIO: ".$DATOX[0]['usuario_id_cancelacion'];
                                                    $this->salida.="        </td>";
                                                }
                                                
                                                if($percaf['sw_confirmada']=='1')
                                                {
                                                  if($DATOX[0]['sw_recibida']=='0')
                                                  {
                                                      $this->salida.="        <td id='total".$DATOX[0]['ingreso_id']."' class=\"".$estilo."\" align=\"left\">";
                                                      $this->salida.="            <a title='DIETA SIN CONFIRMAR POR EL USUARIO DE CAFETERIA' href=\"javascript:CambiarEstado('".$DATOX[0]['ingreso_id']."','".$DATOX[0]['fecha_solicitud']."','".$DATOX[0]['tipo_solicitud_dieta_id']."','".$DATOX[0]['estacion_id']."','1','".UserGetUID()."','total".$DATOX[0]['ingreso_id']."');\">";//
                                                      $this->salida.="              <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                                      $this->salida.="        </td>";
                                                  }
                                                  elseif($DATOX[0]['sw_recibida']=='1')
                                                  {
                                                      $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                                      $this->salida.="            <a title='DIETA CONFIRMADA USUARIO ".$DATOX[0]['usuario_recibe']." FECHA DE CONFIR ".$DATOX[0]['fecha_recibida']."' >";// href=\"javascript:CambiarEstado('".$DATOX[0]['ingreso_id']."','".$DATOX[0]['fecha_solicitud']."','".$DATOX[0]['tipo_solicitud_dieta_id']."','".$DATOX[0]['estacion_id']."','0','".UserGetUID()."','total".$DATOX[0]['ingreso_id']."');\"
                                                      $this->salida.="              <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                                      $this->salida.="        </td>";
                                                  }
                                                }
                                                else
                                                {
                                                    if($DATOX[0]['sw_recibida']=='0')
                                                    {
                                                        $this->salida.="        <td id='total".$DATOX[0]['ingreso_id']."' class=\"".$estilo."\" align=\"left\">";
                                                        $this->salida.="              <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                                        $this->salida.="        </td>";
                                                    }
                                                    elseif($DATOX[0]['sw_recibida']=='1')
                                                    {
                                                        $this->salida.="        <td class=\"".$estilo."\" align=\"left\">";
                                                        $this->salida.="            <a title='DIETA CONFIRMADA USUARIO ".$DATOX[0]['usuario_recibe']." FECHA DE CONFIR ".$DATOX[0]['fecha_recibida']."' >";// href=\"javascript:CambiarEstado('".$DATOX[0]['ingreso_id']."','".$DATOX[0]['fecha_solicitud']."','".$DATOX[0]['tipo_solicitud_dieta_id']."','".$DATOX[0]['estacion_id']."','0','".UserGetUID()."','total".$DATOX[0]['ingreso_id']."');\"
                                                        $this->salida.="              <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                                        $this->salida.="        </td>";
                                                    }
                                                }
                                            }
                                    }

                            }
                        }
                    }
                $this->salida.="    </tr>";
                }


            }
        $rep= new GetReports();
        $this->salida.="</table>";
        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\">";
        $this->salida.="    <tr align=\"center\" class=\"normal_10\">";
        $mostrar=$rep->GetJavaReport('app','Cafeteria','examenes_html_InfTotalxDetallado',array('empresa_id'=>$_REQUEST['empresa_id'],'titulo'=>$_REQUEST['titulo'],'tipo_solicitud'=>$_REQUEST['tipo_solicitud'],'sw_adicional'=>$_REQUEST['sw_adicional']));
        $nombre_funcion=$rep->GetJavaFunction();        
        
        $mostrar.=$rep->GetJavaReport('app','Cafeteria','Etiquetas_Totaldetallado',array('empresa_id'=>$_REQUEST['empresa_id'],'titulo'=>$_REQUEST['titulo'],'tipo_solicitud'=>$_REQUEST['tipo_solicitud'],'sw_adicional'=>$_REQUEST['sw_adicional']));
        $nombre_funcion1=$rep->GetJavaFunction();
        $this->salida .=$mostrar;
        $this->salida .= "        <td align=\"center\" class=\"label_error\" ><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR</a></td>";
        $this->salida .= "        <td align=\"center\" >\n";
        $this->salida .= "          <a href=\"javascript:$nombre_funcion1\" class=\"label_error\">\n";
        $this->salida .= "            <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' class=\"label_error\">ETIQUETAS\n";
        $this->salida .= "          </a>\n";
        $this->salida .= "        </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "</table>\n";
      }
      else
      {
        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td  align=\"center\"><label class='label_error'>NO SE ENCONTRARON DIETAS ASIGNADAS  PARA LA FECHA ".DATE("Y-m-d")." Y HORA ".date("G:i:s")."</label></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
      }
      $this->salida.="<table ALIGN='center'>";
      $this->salida .= "<tr>";
      $accion2=ModuloGetURL('app','Cafeteria','user','InfTotalDetallado',array('empresa'=>$_REQUEST['empresa']));
      $this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
      $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
      $this->salida.="</tr>";
      $this->salida.="</table>";   
      $this->salida.=ThemeCerrarTabla(); 
      return true;
    }
  }//fin de la clase
?>