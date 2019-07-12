<?php

/**
 * $Id: app_Contratacion_userclasses_HTML.php,v 1.2 2009/10/05 19:04:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de la contratación (determinar las características de los planes)
 */

/**
* app_Contratación_userclasses_HTML.php
*
* Clase que establece los métodos de acceso y búsqueda de información con las opciones
* de los detalles de los planes, ajustados a las características de los servicios y de
* los clientes con los cuales se va a contratar, relacionando los cargos y medicamentos
* con sus tarifarios, copagos, autorizaciones, semanas de carencia y paragrafados
**/

class app_Contratacion_userclasses_HTML extends app_Contratacion_user
{
    function app_Contratacion_user_HTML()
    {
        $this->app_Contratacion_user(); //Constructor del padre 'modulo'
        $this->salida='';
        return true;
    }

    //Determina las empresas, en las cuales el usuario tiene permisos
    function PrincipalContra2()//Selecciona las empresas disponibles
    {
        UNSET($_SESSION['contra']);
        UNSET($_SESSION['ctrpla']);
        UNSET($_SESSION['ctrpl1']);
        if($this->UsuariosContra()==false)
        {
            return false;
        }
        return true;
    }
    /**
    * Funcion donde se muestra la informacion de los planes y las respectivas
    * opciones sobre cada uno de ellos
    *
    * @return boolean
    */
    function EmpresasContra($traerestado)//LLama a todas las opciones posibles
    {		
 			if($_REQUEST['planestado']<>NULL AND $_REQUEST['planestado']<>$_SESSION['tmp']['estado'])//!empty($_REQUEST['planestado']) AND 
			{ 
				UNSET($_SESSION['tmp']['estado']);
				$_SESSION['tmp']['estado']=$_REQUEST['planestado'];
			}
			else
			$_REQUEST['planestado']=$_SESSION['tmp']['estado'];

			if($_REQUEST['contrato']=='contrato')
				$traerestado=$_REQUEST['estadogrupo'];
      else if($_REQUEST['tarifa']=='tarifa')
				$traerestado=$_REQUEST['estadogrupo'];

      if($_SESSION['contra']['empresa']==NULL)
      {
        $_SESSION['contra']['empresa']=$_REQUEST['permisoscontra']['empresa_id'];
        $_SESSION['contra']['razonso']=$_REQUEST['permisoscontra']['descripcion1'];
      }
      UNSET($_SESSION['ctrpla']);
      UNSET($_SESSION['ctrpl1']);
        
      $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLANES DE LOS CLIENTES RELACIONADOS CON LA EMPRESA');
      if($this->uno == 1)
      {
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "      </table><br>";
        $this->uno="";
      }
      $ctr = AutoCarga::factory("Contratacion","classes","app","Contratacion");
      $facturado = $ctr->ObtenerResumenFacturacionPlan($_SESSION['contra']['empresa']);
      
      $accion=ModuloGetURL('app','Contratacion','user','PreguntaIngresaPlan');
      $this->salida .= "<form name=\"contratacion\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"1180\" align=\"center\">";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td>\n";
      $this->salida .= "        <fieldset class=\"fieldset\">\n";
      $this->salida .= "          <legend class=\"label\">CONTRATACIÓN - CLIENTES</legend>\n";
      $this->salida .= "          <table border=\"0\" width=\"600\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "            <tr class=modulo_list_claro>";
      $this->salida .= "              <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "            </td>";
      $this->salida .= "            <td align=\"center\" width=\"70%\" class=\"normal_10AN\">".$_SESSION['contra']['razonso']."</td>";
      $this->salida .= "          </tr>";
      $this->salida .= "        </table><br>";
      $this->salida .= "        <table border=\"0\"  align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "          <tr class=\"modulo_table_list_title\">";
      $this->salida .= "            <td width=\"10\" >No.</td>";
      $this->salida .= "            <td width=\"100\">No. - CONTRATO</td>";
      $this->salida .= "            <td width=\"200\">DESCRIPCIÓN DEL CONTRATO</td>";
      $this->salida .= "            <td width=\"80\">TIPO PLAN</td>\n";
      $this->salida .= "            <td width=\"80\">FECHA FINAL</td>\n";
      $this->salida .= "            <td width=\"80\">DIAS</td>\n";
      $this->salida .= "            <td width=\"200\">CLIENTE</td>";
      $this->salida .= "            <td >FACT.</td>";
      $this->salida .= "            <td width=\"100\" >V. CONTRATO</td>";
      $this->salida .= "            <td width=\"100\" >V. FACTURADO</td>";
      $this->salida .= "            <td width=\"100\" >DIFERENCIA</td>";
      $this->salida .= "            <td width=\"40\">ES.</td>\n";
      $this->salida .= "            <td width=\"90\" colspan=\"2\">MENÚ</td>\n";
      $this->salida .= "          </tr>\n";
      $tipoplan=$this->BuscarEstadoPlanContra();
      $planempr=$this->BuscarEmpresasPlanes($_SESSION['contra']['empresa'],$tipoplan,$traerestado,$_REQUEST['planestado']);//$estadobarra
      $i=0;
      $j=0;
      $ciclo=sizeof($planempr);
      
      $ctl = AutoCarga::factory("ClaseUtil");
      while($i<$ciclo)
      {
        $class = "modulo_list_claro";
        if($i%2 == 1) $class = "modulo_list_oscuro";
        $dias = $ctl->CompararFechas($planempr[$i]['fecha_fin_contrato'],date("d/m/Y")); 
        $this->salida .= "        <tr class=\"".$class."\">\n";
        $this->salida .= "            <td align=\"center\">".($i+1)."</td>";
        $this->salida .= "            <td align=\"center\">".$planempr[$i]['num_contrato']."</td>";
        $this->salida .= "            <td>".$planempr[$i]['plan_descripcion']."</td>";
        $this->salida .= "            <td>".$planempr[$i]['descripcion']."</td>";
        $this->salida .= "            <td align=\"center\">".$planempr[$i]['fecha_fin_contrato']."</td>\n";
        $this->salida .= "            <td class=\"label\" align=\"right\">".($dias/86400)." días</td>\n";
        $this->salida .= "            <td>".$planempr[$i]['nombre_tercero']."</td>";
        $this->salida .= "            <td align=\"center\">".(($planempr[$i]['sw_facturacion_agrupada']==1)? "SI":"NO")."</td>\n";
        $this->salida .= "            <td align=\"right\">".formatoValor($planempr[$i]['monto_contrato'])."</td>\n";
        $this->salida .= "            <td align=\"right\">".formatoValor($facturado[$planempr[$i]['plan_id']]['total'])."</td>\n";
        $this->salida .= "            <td align=\"right\">".formatoValor($planempr[$i]['monto_contrato'] - $facturado[$planempr[$i]['plan_id']]['total'])."</td>\n";
        $this->salida .= "            <td align=\"center\">";
        
        if($planempr[$i]['estado'] == 1)//ACTVO
        {
          $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','JustificarCambiarEstadoPlanContra',
        
          array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
              'codigoctra'=>$_REQUEST['codigoctra'],'planelegc'=>$planempr[$i]['plan_id'],'estado'=>$planempr[$i]['estado'])) ."\">
              <img title=\"PLAN ACTIVO\" src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\"></a>";
        }
        else if($planempr[$i]['estado'] == 0)//INACTIVO
          {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','JustificarCambiarEstadoPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
                'codigoctra'=>$_REQUEST['codigoctra'],'planelegc'=>$planempr[$i]['plan_id'],'estado'=>$planempr[$i]['estado'])) ."\">
                <img title=\"PLAN INACTIVO\" src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\"></a>";
          }
          else if($planempr[$i]['estado'] == 2)//ANULADO
            {
              $this->salida .= "<img title=\"PLAN ANULADO\" src=\"".GetThemePath()."/images/panulado.png\" border=\"0\">";
            }
            else
            {
              $this->salida .= "<img title=\"PLAN VENCIDO\" src=\"".GetThemePath()."/images/pvencido.png\" border=\"0\">";
            }
        $this->salida .= "            </td>";
        $this->salida .= "<td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClientePlanContra',array(
          'planelegc'=>$planempr[$i]['plan_id'],'descelegc'=>$planempr[$i]['plan_descripcion'],'numeelegc'=>$planempr[$i]['num_contrato'],
          'nombelegc'=>$planempr[$i]['nombre_tercero'],'estado'=>$planempr[$i]['estado'],'paragracd'=>$planempr[$i]['sw_paragrafados_cd'],
          'tipoidter'=>$planempr[$i]['tipo_id_tercero'],'terceroid'=>$planempr[$i]['tercero_id'],'paragraimd'=>$planempr[$i]['sw_paragrafados_imd'],
          'tipparimd'=>$planempr[$i]['tipo_para_imd'],'estado'=>$planempr[$i]['estado'])) ."\"><img title=\"CONTRATO\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\"></a>";
          $this->salida .= "</td>";
          $this->salida .= "<td align=\"center\">";
          $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra',array(
          'planelegc'=>$planempr[$i]['plan_id'],'descelegc'=>$planempr[$i]['plan_descripcion'],'numeelegc'=>$planempr[$i]['num_contrato'],
          'nombelegc'=>$planempr[$i]['nombre_tercero'],'estado'=>$planempr[$i]['estado'],'paragracd'=>$planempr[$i]['sw_paragrafados_cd'],
          'tipoidter'=>$planempr[$i]['tipo_id_tercero'],'terceroid'=>$planempr[$i]['tercero_id'],'paragraimd'=>$planempr[$i]['sw_paragrafados_imd'],
          'tipparimd'=>$planempr[$i]['tipo_para_imd'],'estado'=>$planempr[$i]['estado'],'manejahab'=>$planempr[$i]['sw_contrata_hospitalizacion'])) ."\"><img title=\"TARIFAS\" src=\"".GetThemePath()."/images/pcargos.png\" border=\"0\"></a>";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          $i++;
      }
      $this->salida .= "<tr>";
      $this->salida .= "<td colspan=\"14\" align=\"right\">";
      $this->salida .= "<label class=\"label\">Tipos estado:&nbsp;</label>";
      $this->salida .= "      <select name=\"planestado\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--  SELECCIONE  --</option>";
      $this->salida .= "      <option value=\"-2\">--  TODOS  --</option>";
      if ($_REQUEST['planestado']==NULL)
      {
        $_REQUEST['planestado']=$estadobarra;
      }
      if($_REQUEST['planestado']<>NULL AND $_REQUEST['planestado']<>-2 AND $_REQUEST['planestado']<>-1)
      {
        for($i=0;$i<sizeof($tipoplan);$i++)
        {
          if($tipoplan[$i]['estado_id']==$_REQUEST['planestado'])
          {
            $this->salida .="<option value=\"".$tipoplan[$i]['estado_id']."\" selected>".$tipoplan[$i]['descripcion']."</option>";
          }
          else
            $this->salida .="<option value=\"".$tipoplan[$i]['estado_id']."\">".$tipoplan[$i]['descripcion']."</option>";
        }
      }
      else
      for($i=0;$i<sizeof($tipoplan);$i++)
      {
        if($tipoplan[$i]['sw_default']==1)
        {
            $this->salida .="<option value=\"".$tipoplan[$i]['estado_id']."\" selected>".$tipoplan[$i]['descripcion']."</option>";
        }
        else
        {
            $this->salida .="<option value=\"".$tipoplan[$i]['estado_id']."\">".$tipoplan[$i]['descripcion']."</option>";
        }
      }
      $this->salida .= " 			 </select>";
      $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"BuscarEstado\" value=\"TRAER\">";//'Buscador_Plan','$rus',this.form
      $this->salida .= "</td>";
      $this->salida .= "</tr>";
      if(empty($planempr))
      {
          $this->salida .= "<tr class=\"modulo_list_claro\">";
          $this->salida .= "<td colspan=\"9\" align=\"center\">";
          $this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN RELACIONADO A LA EMPRESA'";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
      }
      $this->salida .= "      </table>";
      $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
      $this->salida .= "      <tr>";
      $this->salida .= "      <td align=\"center\"><br>";
      $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO PLAN\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </form>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td align=\"center\"><br>";
      $accion=ModuloGetURL('app','Contratacion','user','PrincipalContra2');
      $this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table><br>";
      if ($_REQUEST['planestado']==NULL)
      $_REQUEST['planestado']=$_SESSION['tmp']['estado'];
      $var1=$this->RetornarBarraClientes($_REQUEST['planestado']);
      if(!empty($var1))
      {
          $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
          $this->salida .= "  <tr>";
          $this->salida .= "  <td width=\"100%\" align=\"center\">";
          $this->salida .=$var1;
          $this->salida .= "  </td>";
          $this->salida .= "  </tr>";
          $this->salida .= "  </table><br>";
      }
      $accion=ModuloGetURL('app','Contratacion','user','EmpresasContra',
      array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri'],'estadobarra'=>$estadobarra));
      $this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "  <tr class=modulo_list_claro>";
      $this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
      $this->salida .= "  </td>";
      $this->salida .= "  <td width=\"73%\">";
      $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr class=modulo_list_claro>";
      $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
      $this->salida .= "  </td>";
      $this->salida .= "  <td>";
      $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr class=modulo_list_claro>";
      $this->salida .= "  <td colspan=\"2\" align=\"center\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr class=modulo_list_claro>";
      $this->salida .= "  <td colspan=\"2\" align=\"center\">";
      $accion=ModuloGetURL('app','Contratacion','user','EmpresasContra');
      $this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }

		//JustificarCambiarEstadoPlanContra
		function JustificarCambiarEstadoPlanContra()
		{
				if($_REQUEST['estado']==1)
				$this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLAN CLIENTE - ACTIVO');
				else
				if($_REQUEST['estado']==0)
				$this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLAN CLIENTE - INACTIVO');
				$accion=ModuloGetURL('app','Contratacion','user','CambiarEstadoPlanContra',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
                'codigoctra'=>$_REQUEST['codigoctra'],'planelegc'=>$_REQUEST['planelegc'],'estado'=>$_REQUEST['estado']));
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
						$this->uno="";
        }
				$this->salida.="		<table border=\"0\" width=\"80%\" align=\"center\">";
				$this->salida.="		<form name=\"contrajustifi\" action=\"$accion\" method=\"post\">";
				$this->salida.="		<tr class=\"modulo_list_oscuro\">";
				$this->salida.="		<td align=\"center\" width=\"100%\">";
				$this->salida.="			<label class=\"".$this->SetStyle("justificacion")."\">JUSFICACIÓN:</label>";
				$this->salida.="			<textarea name=\"justificacion\" cols=\"80\" rows=\"5\" style = \"width:100%\" class=\"textarea\"></textarea>";
				$this->salida.="		</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr class=\"modulo_list_claro\">";
				$this->salida.="		<td align=\"center\" width=\"100%\">";
				$this->salida.="		<label class=\"label\">PASAR A:</label>";
				if($_REQUEST['estado']==2)
				{
				$this->salida.="		<input type=\"radio\" name=\"restado\" value=\"0\" checked>";
				$this->salida.="		<label classs=\"label\">INACTIVO</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;";    
				$this->salida.="		<input type=\"radio\" name=\"restado\" value=\"1\">";
				$this->salida.="		<label classs=\"label\">ACIVO</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;";   
				}
				else
				if($_REQUEST['estado']==1)
				{
				$this->salida.="		<input type=\"radio\" name=\"restado\" value=\"0\" checked>";
				$this->salida.="		<label classs=\"label\">INACTIVO</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;";   
				$this->salida.="		<input type=\"radio\" name=\"restado\" value=\"2\">";
				$this->salida.="		<label classs=\"label\">ANULADO</label>";
				} 
				else
				if($_REQUEST['estado']==0)
				{
				$this->salida.="		<input type=\"radio\" name=\"restado\" value=\"1\" checked>";
				$this->salida.="		<label classs=\"label\">ACTIVO</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;";    
				$this->salida.="		<input type=\"radio\" name=\"restado\" value=\"2\">";
				$this->salida.="		<label classs=\"label\">ANULADO</label>";
				}   
				$this->salida.="		</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr>";
				$this->salida.="		<td align=\"center\" width=\"20%\"><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
				$this->salida.="		</tr>";
				$this->salida.="		</form>";
				$this->salida.="		<br>";
				$this->salida.="		<tr>";
				$acc=ModuloGetURL('app','Contratacion','user','EmpresasContra');
				$this->salida.="		<form name=\"justifi\" action=\"$acc\" method=\"post\">";
				$this->salida.="		<td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td>";
				$this->salida.="		</form>";
				$this->salida.="		</tr>";
				$this->salida.="		</table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
		}

    //Función que permite realizar mantenimiento sobre el plan elegido
    function ClientePlanContra()//Opciones del plan
    {
        if(empty($_SESSION['ctrpla']['planeleg']))
        {
            $_SESSION['ctrpla']['planeleg']=$_REQUEST['planelegc'];
            $_SESSION['ctrpla']['desceleg']=$_REQUEST['descelegc'];
            $_SESSION['ctrpla']['numeeleg']=$_REQUEST['numeelegc'];
            $_SESSION['ctrpla']['nombeleg']=$_REQUEST['nombelegc'];//nombre del cliente - tercero
            $_SESSION['ctrpla']['tidteleg']=$_REQUEST['tipoidter'];
            $_SESSION['ctrpla']['terceleg']=$_REQUEST['terceroid'];
            $_SESSION['ctrpla']['estaeleg']=$_REQUEST['estado'];
            $_SESSION['ctrpla']['pimdeleg']=$_REQUEST['paragraimd'];
            $_SESSION['ctrpla']['pcadeleg']=$_REQUEST['paragracd'];
            $_SESSION['ctrpla']['tpmdeleg']=$_REQUEST['tipparimd'];
            $_SESSION['ctrpla']['estado']=$_REQUEST['estado'];
        }
        UNSET($_SESSION['ctrpla']['afiliaM']);
        UNSET($_SESSION['ctrpla']['rangosM']);
        UNSET($_SESSION['ctrpla']['afiliado2']);
        UNSET($_SESSION['ctrpla']['rangospl2']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLAN CLIENTE');
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','EmpresasContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">OPCIONES DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" class=\"modulo_table_list_title\">";
        $this->salida .= "MENÚ 1";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" class=\"label\">";
        $this->salida .= "CONSULTAR INFORMACIÓN DEL CONTRATO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','MostrarDatosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" title=\"CONSULTAR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" class=\"label\">";
        $this->salida .= "MODIFICAR INFORMACIÓN DEL CONTRATO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ModificaDatosPlan') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\" title=\"MODIFICAR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" class=\"label\">";
        $this->salida .= "RANGOS PARA EL CONTRATO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','VerRangosPlan') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/prangos.png\" border=\"0\" title=\"RANGOS PARA EL CONTRATO\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" class=\"label\">";
        $this->salida .= "UNIDADES UVR DEL PLAN (VALORES POR DEFECTO)";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','FrmIngresarRangosUVR') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/asignacion_citas.png\" border=\"0\" title=\"RANGOS UVR(VALORES POR DEFECTO)\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" class=\"label\">";
        $this->salida .= "IR AL MENÚ 2";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img title=\"TARIFAS\" src=\"".GetThemePath()."/images/pcargos.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"100%\">";
        $accion=ModuloGetURL('app','Contratacion','user','EmpresasContra',array('estadogrupo'=>$_SESSION['ctrpla']['estado'],'contrato'=>'contrato'));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PLANES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

		//FORMA PARA INGRESAR LO UVR POR DEFECTO
    function FrmIngresarRangosUVR()//Llama a validar plan y vuelve al menu principal
    {
        if(!($this->uno == 1))
        {
            $planeleg=$this->MostrarEmpresasPlanes($_SESSION['ctrpla']['planeleg']);
            $_POST['tipoplctraM']=$planeleg['sw_tipo_plan'];
            $_POST['descrictraM']=$planeleg['plan_descripcion'];
            $_POST['tipoTerceroId']=$planeleg['tipo_tercero_id'];
            $_POST['nombre']=$_SESSION['ctrpla']['nombeleg'];
            $_POST['codigo']=$planeleg['tercero_id'];
            $_POST['numeroctraM']=$planeleg['num_contrato'];
/*            $fecha=explode('-',$planeleg['fecha_inicio']);
            $_POST['feinictraM']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $fecha=explode('-',$planeleg['fecha_final']);
            $_POST['fefinctraM']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];*/
            $_POST['clientectraM']=$planeleg['tipo_cliente'];
            $_POST['liquihactraM']=$planeleg['tipo_liq_habitacion'];
            $_POST['capitactraM']=$planeleg['sw_autoriza_sin_bd'];
        }
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - UNIDADES UVR / INGRESO');
        $mostrar=ReturnClassBuscador('proveedores','','','contratacion','');

				$this->salida .=$mostrar;
				$this->salida .="</script>\n";
				$rus=ModuloGetURL('app','Contratacion','user','FrmIngresarRangosUVR');
				$mostrar1 ="<script language='javascript'>\n";
				$mostrar1.="  function load_page(obj){\n";
				$mostrar1.="    var url ='$rus';\n";
				$mostrar1.="    var es = obj.options[obj.selectedIndex].value;\n";
				$mostrar1.='    var url2 = url+"&tarifario1="+es;';
				$mostrar1.="    window.location.href=url2;};\n";
				$mostrar1.="</script>\n";
				$this->salida.="$mostrar1";

				$accion=ModuloGetURL('app','Contratacion','user','IngresarDatosPlanContraUVR');
				$datos=$this->BuscarDatosUVRplanes();
				$tarifariosUVRS=$this->ConsultarTarifarios_UvrsTarifarios();
				$datosuvrp=$this->BuscarDatosUVRplanesp();
				if(sizeof($datos)>0)
				{
/*					$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list_titulo\">";
					$this->salida .= "  <tr class=\"modulo_table_list_title\" width=\"100%\">";
					$this->salida .= "  <td align=\"center\" colspan=\"5\">";
					$this->salida .= "PARAMETRIZACIÓN DE UNIDADES UVRS";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  <tr class=\"modulo_table_list_title\" >";
					$this->salida .= "  <td align=\"center\" width=\"45%\">";
					$this->salida .= "	<label>TARIFARIO</label>";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\" width=\"10%\">";
					$this->salida .= "	<label>DERECHO ESPEC./CIRUGIA</label>";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\" width=\"10%\">";
					$this->salida .= "	<label>DERECHO ANESTESIOLOGIA</label>";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\" width=\"10%\">";
					$this->salida .= "	<label>DERECHO AYUDANTE</label>";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\" width=\"10%\">";
					$this->salida .= "	<label>DERECHO MED./ODONT. GENERAL</label>";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					for($i=0;$i<sizeof($datos);$i++)
					{
					$this->salida .= "  <tr class=modulo_list_claro>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "<center><label class=\"label\">".$datos[$i][tarifario_id].'--'.$datos[$i][descripcion]."</label></center>";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "$&nbsp;".FormatoValor($datos[$i][dc_valor])."&nbsp;=";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "$&nbsp;".FormatoValor($datos[$i][da_valor])."&nbsp;=";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "$&nbsp;".FormatoValor($datos[$i][dy_valor])."&nbsp;=";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "$&nbsp;".FormatoValor($datos[$i][dg_valor])."&nbsp;=";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";						
					}
					$this->salida .= "  </table><BR><BR>";*/
					if (!empty($_REQUEST['tarifario1']))
					{
						$_POST['tarifariouvr']=$_REQUEST['tarifario1'];
						$tarifariouvr=0;
						for($i=0;$i<sizeof($datos);$i++)
						{ 
							if ($_REQUEST['tarifario1']==$datos[$i][tarifario_id])
							{$tarifariouvr=1;
								$_POST['tarifariouvr']=$datos[$i][tarifario_id];
								$_POST['valorespecialista']=$datos[$i][dc_valor];
								$_POST['valoranestesiologo']=$datos[$i][da_valor];
								$_POST['valorayudante']=$datos[$i][dy_valor];
								$_POST['valorgeneral']=$datos[$i][dg_valor];
							}
						}
							
/*							else
							{*/
								if($tarifariouvr==0)
								{
								for($j=0;$j<sizeof($tarifariosUVRS);$j++)
								{
									if ($_POST['tarifariouvr']==$tarifariosUVRS[$j][tarifario_id])
									{ 
										$_POST['tarifariouvr']=$tarifariosUVRS[$j][tarifario_id];
										$_POST['valorespecialista']=$tarifariosUVRS[$j][dc_valor];
										$_POST['valoranestesiologo']=$tarifariosUVRS[$j][da_valor];
										$_POST['valorayudante']=$tarifariosUVRS[$j][dy_valor];
										$_POST['valorgeneral']=$tarifariosUVRS[$j][dg_valor];
									}
								}
								}
							//}
					}
					else
					{
						for($i=0;$i<sizeof($datos);$i++)
						{ //tarifariouvr
								$_POST['tarifariouvr']=$datos[$i][tarifario_id];
								$_POST['valorespecialista']=$datos[$i][dc_valor];
								$_POST['valoranestesiologo']=$datos[$i][da_valor];
								$_POST['valorayudante']=$datos[$i][dy_valor];
								$_POST['valorgeneral']=$datos[$i][dg_valor];
						}
					}
				}
			else
			{
				if (!empty( $_REQUEST['tarifario1']))
						$_POST['tarifariouvr']=$_REQUEST['tarifario1'];

				for($i=0;$i<sizeof($tarifariosUVRS);$i++)
				{
					if ($_POST['tarifariouvr']==$tarifariosUVRS[$i][tarifario_id])
					{
						$_POST['tarifariouvr']=$tarifariosUVRS[$i][tarifario_id];
						$_POST['valorespecialista']=$tarifariosUVRS[$i][dc_valor];
						$_POST['valoranestesiologo']=$tarifariosUVRS[$i][da_valor];
						$_POST['valorayudante']=$tarifariosUVRS[$i][dy_valor];
						$_POST['valorgeneral']=$tarifariosUVRS[$i][dg_valor];
						$i=sizeof($tarifariosUVRS);
					}
/*					else
					{
						$_POST['tarifariouvr']=$tarifariosUVRS[$i][tarifario_id];
						$_POST['valorespecialista']=$tarifariosUVRS[$i][dc_valor];
						$_POST['valoranestesiologo']=$tarifariosUVRS[$i][da_valor];
						$_POST['valorayudante']=$tarifariosUVRS[$i][dy_valor];
						$_POST['valorgeneral']=$tarifariosUVRS[$i][dg_valor];
					}*/
				}
			}
			
			if(sizeof($datosuvrp)>0)
			{
				$_POST['tarifariouvrp']=$datosuvrp[0][tarifario_id];
				$_POST['valoruvrp']=$datosuvrp[0][uvr_valor];
			}
			

        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">PARAMETRIZACIÓN UVRS - DATOS DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tipoplctraM")."\">TIPO PLAN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $tipoplan=$this->BuscarTipoPlanContra();
        for($i=0;$i<sizeof($tipoplan);$i++)
        {
            if($tipoplan[$i]['sw_tipo_plan']==$_POST['tipoplctraM'])
            {
       				 $this->salida .= "      <input type=\"text\" name=\"tipoplctraM\" size=\"33\" class=\"input-text\" value=\"".$tipoplan[$i]['descripcion']."\" READONLY>";
            }
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"10%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tipoTerceroId")."\">TIPO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"15%\">";
        $this->salida .= "      <input type=\"text\" name=\"tipoTerceroId\" size=\"4\" class=\"input-text\" value=\"".$_POST['tipoTerceroId']."\" READONLY>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"13%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("nombre")."\">CLIENTE:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      <input type=\"button\" name=\"proveedor\" value=\"CLIENTE\" onclick=abrirVentana() class=\"input-submit\" disabled>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td><label class=\"".$this->SetStyle("codigo")."\">DOCUMENTO:</label>";//&nbsp&nbsp&nbsp;
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("numeroctraM")."\">NÚMERO DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroctraM\" value=\"".$_POST['numeroctraM']."\" maxlength=\"20\" size=\"33\" readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("descrictraM")."\">DESCRIPCIÓN DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrictraM\" value=\"".$_POST['descrictraM']."\" maxlength=\"60\" size=\"48\" readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";

        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
/*        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"20%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("feinictraM")."\">FECHA INICIAL:</label>";
        $this->salida .= "      </td>";
				if(empty($_POST['feinictraM']) AND empty($_POST['fefinctraM']))
				{
					$_POST['feinictraM']=date('01/01/Y');
					$_POST['fefinctraM']=date('12/31/Y');
				}
        $this->salida .= "      <td width=\"80%\" colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictraM\" value=\"".$_POST['feinictraM']."\" maxlength=\"10\" size=\"12\">";
        $this->salida .= "      ".ReturnOpenCalendario('contratacion','feinictraM','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";*/
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"20%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tarifario")."\">TARIFARIO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"80%\">";
				//$acc=ModuloGetURL('app','Contratacion','user','FrmIngresarRangosUVR');
        $this->salida .= "      <select name=\"tarifariouvr\" class=\"select\" OnChange=\"load_page(this);\">";//\"load_page();\"
				$this->salida .= "      <option value=\"-1\" selected>----SELECCIONE----</option>";
				$des="";
				$tarifarios=$this->TraerTarifariosUVR();
				for($j=0;$j<sizeof($tarifarios);$j++)
				{
					if($tarifarios[$j]['tarifario_id']==$_POST['tarifariouvr'])
					{
						$this->salida .="<option value=\"".$tarifarios[$j]['tarifario_id']."\" selected>".$tarifarios[$j]['tarifario_id'].'-'.substr($tarifarios[$j]['descripcion'],0,10)."</option>";
						$des=$tarifarios[$j]['descripcion'];
					}
					else
					{
						$this->salida .="<option value=\"".$tarifarios[$j]['tarifario_id']."\" title=\"".$tarifarios[$j]['descripcion']."\">".$tarifarios[$j]['tarifario_id'].'-'.substr($tarifarios[$j]['descripcion'],0,10)."</option>";
					}
				}
				$this->salida .= "      </select>";
				if (!empty($des))
					$this->salida .=" <img title=\"$des\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"30%\">";
				$this->salida .= "      <label class=\"".$this->SetStyle("especialista")."\">UVRS ESPECIALISTAS:</label>";
				$this->salida .= "      </td>";
				$this->salida .= "      <td width=\"40%\">";
				$this->salida .= "      <b>Valor:</b>&nbsp;<input type=\"text\" class=\"input-text\" name=\"valorespecialista\" value=\"".$_POST['valorespecialista']."\" maxlength=\"15\" size=\"15\" align=\"right\">";
				$this->salida .= "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label\"><font size=\"5\" color=\"red\">*</font>(Valor obligatorio)</label></td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"30%\">";
				$this->salida .= "      <label class=\"".$this->SetStyle("anestesiologo")."\">UVRS ANESTESIOLOGO:</label>";
				$this->salida .= "      </td>";
				$this->salida .= "      <td width=\"40%\">";
				$this->salida .= "      <b>Valor:</b>&nbsp;<input type=\"text\" class=\"input-text\" name=\"valoranestesiologo\" value=\"".$_POST['valoranestesiologo']."\" maxlength=\"15\" size=\"15\" align=\"right\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"30%\">";
				$this->salida .= "      <label class=\"".$this->SetStyle("ayudante")."\">UVRS AYUDANTES:</label>";
				$this->salida .= "      </td>";
				$this->salida .= "      <td width=\"40%\">";
				$this->salida .= "      <b>Valor:</b>&nbsp;<input type=\"text\" class=\"input-text\" name=\"valorayudante\" value=\"".$_POST['valorayudante']."\" maxlength=\"15\" size=\"15\" align=\"right\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"30%\">";
				$this->salida .= "      <label class=\"".$this->SetStyle("general")."\">UVRS MEDICO GENERAL:</label>";
				$this->salida .= "      </td>";
				$this->salida .= "      <td width=\"40%\">";
				$this->salida .= "      <b>Valor:</b>&nbsp;<input type=\"text\" class=\"input-text\" name=\"valorgeneral\" value=\"".$_POST['valorgeneral']."\" maxlength=\"15\" size=\"15\" align=\"right\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      </table><BR>";
//VALORES DE UVRS POR PAQUETE
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"20%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tarifario")."\">TARIFARIO UVR PAQUETE:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"80%\">";
        $this->salida .= "      <select name=\"tarifariouvrp\" class=\"select\" >";//\"load_page();\"
				$this->salida .= "      <option value=\"-1\" selected>----SELECCIONE----</option>";
				$des="";
				$tarifarios=$this->TraerTarifariosUVRPaquete();
				for($j=0;$j<sizeof($tarifarios);$j++)
				{
					if($tarifarios[$j]['tarifario_id']==$_POST['tarifariouvrp'])
					{
						$this->salida .="<option value=\"".$tarifarios[$j]['tarifario_id']."\" selected>".$tarifarios[$j]['tarifario_id'].'-'.substr($tarifarios[$j]['descripcion'],0,10)."</option>";
						$des=$tarifarios[$j]['descripcion'];
					}
					else
					{
						$this->salida .="<option value=\"".$tarifarios[$j]['tarifario_id']."\" title=\"".$tarifarios[$j]['descripcion']."\">".$tarifarios[$j]['tarifario_id'].'-'.substr($tarifarios[$j]['descripcion'],0,10)."</option>";
					}
				}
				$this->salida .= "      </select>";
				if (!empty($des))
					$this->salida .=" <img title=\"$des\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"30%\">";
				$this->salida .= "      <label class=\"".$this->SetStyle("uvr")."\">UVR:</label>";
				$this->salida .= "      </td>";
				$this->salida .= "      <td width=\"40%\">";
				$this->salida .= "      <b>Valor uvr paquete:</b>&nbsp;<input type=\"text\" class=\"input-text\" name=\"valoruvrp\" value=\"".$_POST['valoruvrp']."\" maxlength=\"15\" size=\"15\" align=\"right\">";
				$this->salida .= "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      </table><BR>";
//FIN VALORES DE UVRS POR PAQUETE
				$this->salida .= "  </fieldset>";
				$this->salida .= "  </td></tr>";
				$this->salida .= "  </table><br>";
				$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
				$this->salida .= "  </td>";
				$this->salida .= "  </form>";
				$accion=ModuloGetURL('app','Contratacion','user','ClientePlanContra');
				$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
				$this->salida .= "  </td>";
				$this->salida .= "  </form>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
    }
//FORMA PARA INGRESAR LO UVR POR DEFECTO
    //
    function PreguntaIngresaPlan()//
    { 
			if($_REQUEST['BuscarEstado']=='TRAER')
			{ 
				if ($_REQUEST['planestado']<>-2)
					UNSET($_SESSION['contra']['estadotodos']);
				$this->EmpresasContra($_REQUEST['planestado']);
				return true;		
			}
			$this->salida  = ThemeAbrirTabla('CONTRATACIÓN - NUEVO PLAN');
			$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "  <tr><td>";
			$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES PARA UN NUEVO PLAN</legend>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['contra']['razonso']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td colspan=\"2\" class=\"modulo_table_list_title\">";
			$this->salida .= "MENÚ";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
			$this->salida .= "GUARDAR UN CONTRATO EN LIMPIO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"30%\" align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','IngresaDatosPlan') ."\">";
			$this->salida .= "<img src=\"".GetThemePath()."/images/pguardar.png\" border=\"0\" title=\"CONTRATO EN LIMPIO\"></a>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
			$this->salida .= "GUARDAR UN CONTRATO A PARTIR DE OTRO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"30%\" align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','IngresaDatosPlan3') ."\">";
			$this->salida .= "<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\" title=\"CONTRATO A PARTIR DE OTRO\"></a>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "  </fieldset>";
			$this->salida .= "  </td></tr>";
			$this->salida .= "  </table><br>";
			$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td align=\"center\" width=\"100%\">";
			$accion=ModuloGetURL('app','Contratacion','user','EmpresasContra');
			$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PLANES\">";
			$this->salida .= "  </td>";
			$this->salida .= "  </form>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
    }

    //
		function IngresaDatosPlan3()//
		{
			$this->salida  = ThemeAbrirTabla('CONTRATACIÓN - DATOS DEL PLAN CLIENTE');
			$mostrar=ReturnClassBuscador('proveedores','','','contratacion','');//contratacion
			$this->salida .=$mostrar;
			$this->salida .="</script>\n";
			$rus='app_modules/Contratacion/selectorplan2.php';
			$mostrar1 ="<script language='javascript'>\n";
			$mostrar1.="  function abrirVentana10(frm){\n";
			$mostrar1.="    var url ='$rus';\n";
			$mostrar1.="    var ALTO=screen.height\n";
			$mostrar1.="    var ANCHO=screen.width\n";
			$mostrar1.="    var nombre=\"PLANES\";\n";
			$mostrar1.="    var str =\"ANCHO,ALTO,resizable=no,location=yes,status=no,scrollbars=yes\";\n";
			$mostrar1.="    var t1 = frm.tarifario1.value;\n";
			$mostrar1.="    var em = frm.empresacon.value;\n";
			$mostrar1.="    var tp = frm.tipoplacon.value;\n";
			$mostrar1.="    var es = frm.estadocont.value;\n";
			$mostrar1.='    var url2 = url+"?tarifario1="+t1+"&empresacon="+em+"&tipoplacon="+tp+"&estadocont="+es;';
			$mostrar1.="    rem = window.open(url2, nombre, str)};\n";
      $mostrar1.= "function Todos(frm,x){";
      $mostrar1.= "  if(x==true){";
      $mostrar1.= "    for(i=0;i<frm.elements.length;i++){";
      $mostrar1.= "      if(frm.elements[i].type=='checkbox'){";
      $mostrar1.= "        frm.elements[i].checked=true";
      $mostrar1.= "      }";
      $mostrar1.= "    }";
      $mostrar1.= "  }else{";
      $mostrar1.= "    for(i=0;i<frm.elements.length;i++){";
      $mostrar1.= "      if(frm.elements[i].type=='checkbox'){";
      $mostrar1.= "        frm.elements[i].checked=false";
      $mostrar1.= "      }";
      $mostrar1.= "    }";
      $mostrar1.= "  }";
      $mostrar1.= "}";
			$mostrar1.="</script>\n";
			$this->salida.="$mostrar1";
			$accion=ModuloGetURL('app','Contratacion','user','ValidarDatosPlanContra3');
			$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <table border=\"0\" width=\"65%\" align=\"center\">";
			$this->salida .= "  <tr><td>";
			$this->salida .= "  <fieldset><legend class=\"field\">BUSCAR UN PLAN</legend>";
			$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['contra']['razonso']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			if($this->uno == 1)
			{
					$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "      </table><br>";
			}
			$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">";
			$this->salida .= "      OPCIONES DE BÚSQUEDA";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"normal_10AN\" width=\"30%\">NÚMERO DE CONTRATO</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tarifario1\" value=\"".$_POST['tarifario1']."\" maxlength=\"20\" size=\"20\" readonly>";
			$this->salida .= "      <input type=\"hidden\" name=\"tarifario2\" value=\"".$_POST['tarifario2']."\">";
			$this->salida .= "      <input type=\"hidden\" name=\"paragracar\" value=\"".$_POST['paragracar']."\">";
			$this->salida .= "      <input type=\"hidden\" name=\"paragramed\" value=\"".$_POST['paragramed']."\">";
			$this->salida .= "      <input type=\"hidden\" name=\"tipoparimd\" value=\"".$_POST['tipoparimd']."\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"normal_10AN\" width=\"30%\">EMPRESAS</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$empresas=$this->BuscarEmpresasContra();
			$this->salida .= "      <select name=\"empresacon\" class=\"select\">";
			$this->salida .= "      <option value=\"-1\">TODAS</option>";
			for($i=0;$i<sizeof($empresas);$i++)
			{
					if($empresas[$i]['empresa_id']==$_POST['empresacon'])
					{
							$this->salida .="<option value=\"".$empresas[$i]['empresa_id']."\" selected>".$empresas[$i]['razon_social']."</option>";
					}
					else
					{
							$this->salida .="<option value=\"".$empresas[$i]['empresa_id']."\">".$empresas[$i]['razon_social']."</option>";
					}
			}
			$this->salida .= "      </select>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"normal_10AN\" width=\"30%\">TIPO PLAN</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$tipoplan=$this->BuscarTipoPlanContra();
			$this->salida .= "      <select name=\"tipoplacon\" class=\"select\">";
			$this->salida .= "      <option value=\"-1\">TODOS</option>";
			for($i=0;$i<sizeof($tipoplan);$i++)
			{
					if($tipoplan[$i]['sw_tipo_plan']==$_POST['tipoplacon'])
					{
							$this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\" selected>".$tipoplan[$i]['descripcion']."</option>";
					}
					else
					{
							$this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\">".$tipoplan[$i]['descripcion']."</option>";
					}
			}
			$this->salida .= "      </select>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"normal_10AN\" width=\"30%\">ESTADO DEL PLAN</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= "      <select name=\"estadocont\" class=\"select\">";
			$this->salida .= "      <option value=\"1\">TODOS</option>";
			if($_POST['estadocont']==2)
			{
					$this->salida .= "<option value=\"2\" selected>ACTIVOS</option>";
			}
			else
			{
					$this->salida .= "<option value=\"2\">ACTIVOS</option>";
			}
			if($_POST['estadocont']==3)
			{
					$this->salida .= "<option value=\"3\" selected>INACTIVOS</option>";
			}
			else
			{
					$this->salida .= "<option value=\"3\">INACTIVOS</option>";
			}
			$this->salida .= "      </select>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\" colspan=\"3\">";
			$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR PLAN\" onclick=\"abrirVentana10(this.form)\">";//'Buscador_Plan','$rus',this.form
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"4\">";
			$this->salida .= "      INFORMACIÓN OBLIGATORIA";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"10%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("tipoTerceroId")."\">TIPO:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"15%\">";
			$this->salida .= "      <input type=\"text\" name=\"tipoTerceroId\" size=\"4\" class=\"input-text\" value=\"".$_POST['tipoTerceroId']."\" READONLY>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"13%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("nombre")."\">CLIENTE:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"62%\">";
			$this->salida .= "      <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_oscuro>";
			$this->salida .= "      <td align=\"center\" colspan=\"2\">";
			$this->salida .= "      <input type=\"button\" name=\"proveedor\" value=\"CLIENTE\" onclick=abrirVentana() class=\"input-submit\">";
			$this->salida .= "      </td>";
			$this->salida .= "      <td><label class=\"".$this->SetStyle("codigo")."\">CÓDIGO:</label>";//&nbsp&nbsp&nbsp;
			$this->salida .= "      </td>";
			$this->salida .= "      <td>";
			$this->salida .= "      <input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("descr2ctra")."\">DESCRIPCIÓN DEL CONTRATO:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descr2ctra\" value=\"".$_POST['descr2ctra']."\" maxlength=\"60\" size=\"40\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_oscuro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("numeroctra")."\">NÚMERO DEL CONTRATO:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroctra\" value=\"".$_POST['numeroctra']."\" maxlength=\"20\" size=\"40\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("contactoctra")."\">CONTACTO(S)<br>(NOMBRE COMPLETO Y TELEFÓNOS):</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <textarea class=\"input-text\" name=\"contactoctra\" cols=\"45\" rows=\"4\">".$_POST['contactoctra']."</textarea>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_oscuro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("valorctra")."\">VALOR DEL CONTRATO:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valorctra\" value=\"".$_POST['valorctra']."\" maxlength=\"17\" size=\"22\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"normal_10AN\">VALOR MENSUAL DEL CONTRATO:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valmectra\" value=\"".$_POST['valmectra']."\" maxlength=\"17\" size=\"22\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_oscuro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"normal_10AN\">VALOR MÁXIMO POR FACTURA:</label>";//".$this->SetStyle("facturactra")."
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"facturactra\" value=\"".$_POST['facturactra']."\" maxlength=\"13\" size=\"22\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("diasCredito")."\">DÍAS CRÉDITO:</label>";//".$this->SetStyle("facturactra")."
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"diasCredito\" value=\"".$_POST['diasCredito']."\" maxlength=\"13\" size=\"22\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"normal_10AN\">EXCEDER EL VALOR MENSUAL DEL CONTRATO:</label>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "SI  ";
			if($_POST['excmonctra']==1)
			{
					$this->salida .= "      <input type=\"radio\" name=\"excmonctra\" value=1 checked>";
			}
			else
			{
					$this->salida .= "      <input type=\"radio\" name=\"excmonctra\" value=1>";
			}
			$this->salida .= "    NO  ";
			if($_POST['excmonctra']==0)
			{
					$this->salida .= "      <input type=\"radio\" name=\"excmonctra\" value=0 checked>";
			}
			else
			{
					$this->salida .= "      <input type=\"radio\" name=\"excmonctra\" value=0>";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_oscuro>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("feinictra")."\">FECHA INICIAL:</label>";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_POST['feinictra']."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "      ".ReturnOpenCalendario('contratacion','feinictra','/')."";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"50%\">";
			$this->salida .= "      <label class=\"".$this->SetStyle("fefinctra")."\">FECHA FINAL:</label>";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_POST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "      ".ReturnOpenCalendario('contratacion','fefinctra','/')."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "  </fieldset>";
			$this->salida .= "  </td></tr>";
			$this->salida .= "  </table><br>";
//NUEVOS CAMPOS PARA COPIA DE CONTRATOS
			$this->salida .= " <table border=\"0\" width=\"65%\" align=\"center\">";
			$this->salida .= "  <tr><td>";
			$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES ADICIONALES PARA COPIAR</legend>";
			$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"1\">";
			$this->salida .= "      OPCIONES PARA GUARDAR POR CARGOS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
			$this->salida .= "      TODO<input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"80%\">OPCIONES</td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPOS</td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">EXCEPCIONES</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">TARIFARIO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiartari\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiartariex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">COPAGOS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarcopa\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarcopaex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">SEMANAS PARA DÍAS DE CARENCIA";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarsema\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarsemaex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"1\">";
			$this->salida .= "      OPCIONES PARA GUARDAR POR SERVICIOS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
			$this->salida .= "      &nbsp;";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">AUTORIZACIONES INTERNAS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarauin\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarauinex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">AUTORIZACIONES EXTERNAS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarauex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarauexex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">INSUMOS Y MEDICAMENTOS - AUTORIZACIONES";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarinm2\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarinmee2\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">INSUMOS Y MEDICAMENTOS - COPAGOS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarinme\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarinmeex\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" colspan=\"2\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarpaim\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">PARAGRAFADOS CARGOS DIRECTOS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" colspan=\"2\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarpacd\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">INCUMPLIMIENTO DE CITAS";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" colspan=\"2\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarincu\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td align=\"center\">HABITACIONES";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" colspan=\"2\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"copiarplanescamas\" value=1>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "      </table>";
			$this->salida .= "  </fieldset>";
			$this->salida .= " <td><tr>";
			$this->salida .= " </table>";
			$this->salida .= "  <br>";
//FIN NUEVOS CAMPOS PARA COPIA DE CONTRATO
			$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
			$this->salida .= "  </td>";
			$this->salida .= "  </form>";
			$this->salida .= "  <td align=\"center\">";
			$accion=ModuloGetURL('app','Contratacion','user','EmpresasContra');
			$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
			$this->salida .= "  </td>";
			$this->salida .= "  </form>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
    /**
    * Funcion para la captura de datos del plan
    *
    * @return boolean
    */
		function IngresaDatosPlan()
		{
			$this->salida  = ThemeAbrirTabla('CONTRATACIÓN ..- DATOS DEL PLAN CLIENTE');
			$mostrar=ReturnClassBuscador('proveedores','','','contratacion','');
			$mostrar .="</script>\n";
			$this->salida .=$mostrar;
			$mostrar1 .= "<SCRIPT>";
      $mostrar1 .= "function listamanual(forma,valor){ ";
      $mostrar1 .= "  if(valor=='3'){";
      $mostrar1 .= "    forma.listaprecios.disabled=false; ";
      $mostrar1 .= "  }";
      $mostrar1 .= "  else";
      $mostrar1 .= "  {";
      $mostrar1 .= "    forma.listaprecios.disabled=true;";
      $mostrar1 .= "    forma.listaprecios.value='000'; ";
      $mostrar1 .= "  }";
      $mostrar1 .= "}";
      $mostrar1 .= "</SCRIPT>";
      $this->salida .=$mostrar1;
			$accion=ModuloGetURL('app','Contratacion','user','ValidarDatosPlanContra');
            
      $this->salida .= "<table border=\"0\" width=\"68%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "  <tr class=\"formulacion_table_list\">\n";
			$this->salida .= "    <td>EMPRESA</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "  <tr class=\"normal_10AN\">\n";
			$this->salida .= "    <td align=\"center\">".$_SESSION['contra']['razonso']."</td>\n";
			$this->salida .= "  </tr>\n";
			$this->salida .= "</table><br>\n";
			$this->salida .= "<form name=\"contratacion\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">\n";
			$this->salida .= "    <tr>\n";
      $this->salida .= "      <td>\n";
			$this->salida .= "        <fieldset class=\"fieldset\">\n";
      $this->salida .= "          <legend class=\"normal_10AN\">INFORMACIÓN DEL PLAN</legend>";

			if($this->uno == 1)
			{
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "      </table><br>";
			}
      
			$this->salida .= "          <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\" >\n";
			$this->salida .= "            <tr>\n";
			$this->salida .= "              <td width=\"40%\" class=\"modulo_list_oscuro\">\n";
			$this->salida .= "                <label class=\"".$this->SetStyle("tipoplctra")."\">* TIPO PLAN:</label>\n";
			$this->salida .= "              </td>\n";
			$tipoplan=$this->BuscarTipoPlanContra();
			$this->salida .= "              <td>\n";
			$this->salida .= "                <select name=\"tipoplctra\" class=\"select\">\n";
			$this->salida .= "                  <option value=\"\">--  SELECCIONE  --</option>\n";
			for($i=0;$i<sizeof($tipoplan);$i++)
			{
				$s ="";
        if($tipoplan[$i]['sw_tipo_plan']==$_POST['tipoplctra'])  $s = "selected";

				$this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\" ".$s." >".$tipoplan[$i]['descripcion']."</option>\n";
			}
			$this->salida .= "                </select>\n";
			$this->salida .= "              </td>\n";
			$this->salida .= "            </tr>\n";
			$this->salida .= "            <tr >\n";
			$this->salida .= "              <td class=\"modulo_list_oscuro\" rowspan=\"2\">\n";
			$this->salida .= "                <label class=\"".$this->SetStyle("nombre")."\">* CLIENTE:</label>";
			$this->salida .= "                <input type=\"button\" name=\"proveedor\" value=\"Buscar Cliente\" onclick=abrirVentana() class=\"input-submit\">\n";
			$this->salida .= "              </td>\n";
			$this->salida .= "              <td>\n";
      $this->salida .= "                <input type=\"text\" name=\"tipoTerceroId\" size=\"4\" class=\"input-text\" value=\"".$_POST['tipoTerceroId']."\" READONLY>&nbsp;";
			$this->salida .= "                <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>\n";
			$this->salida .= "              </td>\n";
			$this->salida .= "            </tr>\n";
			$this->salida .= "            <tr>\n";
			$this->salida .= "              <td>\n";
      $this->salida .= "                <label class=\"".$this->SetStyle("codigo")."\">* CÓDIGO:</label>&nbsp;&nbsp;&nbsp;";
			$this->salida .= "                <input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
      $this->salida .= "              </td>";
      $this->salida .= "            </tr>\n";
			$this->salida .= "            <tr>\n";
			$this->salida .= "              <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "                <label class=\"".$this->SetStyle("clientectra")."\">* TIPO CLIENTE:</label>";
			$this->salida .= "              </td>\n";
			$semana=$this->BuscarClientesContra();
			$this->salida .= "            <td>\n";
			$this->salida .= "              <select name=\"clientectra\" class=\"select\">\n";
			$this->salida .= "                <option value=\"\" >--------  SELECCIONE  --------</option>\n";
			for($i=0;$i<sizeof($semana);$i++)
			{
				$s = "";	
        if($semana[$i]['tipo_cliente'] == $_POST['clientectra']) $s = "selected";
				
        $this->salida .= "                <option value=\"".$semana[$i]['tipo_cliente']."\" ".$s.">".$semana[$i]['descripcion']."</option>\n";
			}
			$this->salida .= "              </select>\n";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("numeroctra")."\">* NÚMERO DEL CONTRATO:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"numeroctra\" value=\"".$_POST['numeroctra']."\" maxlength=\"20\" size=\"33\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("descrictra")."\">* DESCRIPCIÓN DEL CONTRATO:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_POST['descrictra']."\" maxlength=\"60\" size=\"48\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\" colspan=\"2\">\n";
			$this->salida .= "              <label class=\"normal_10AN\">&nbsp;&nbsp;&nbsp;SERVICIOS CONTRATADOS:</label>\n";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td colspan=\"2\">\n";
			$this->salida .= "              <textarea class=\"input-text\" name=\"servicioctra\" style=\"width:100%\" rows=\"3\">".$_POST['servicioctra']."</textarea>";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr >\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\" colspan=\"2\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("contactoctra")."\">* CONTACTO(S) (NOMBRE COMPLETO Y TELEFÓNOS):</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td colspan=\"2\">\n";
			$this->salida .= "              <textarea class=\"input-text\" name=\"contactoctra\" style=\"width:100%\" rows=\"3\">".$_POST['contactoctra']."</textarea>";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("usuariosctra")."\">* ENCARGADO:</label>";
			$this->salida .= "            </td>\n";
			$usuarios=$this->BuscarEncargadosContra($_SESSION['contra']['empresa']);
			$this->salida .= "            <td>\n";
			$this->salida .= "              <select name=\"usuariosctra\" class=\"select\">\n";
			$this->salida .= "                <option value=\"\">--  SELECCIONE  --</option>\n";
			$ciclo=sizeof($usuarios);
			for($i=0;$i<$ciclo;$i++)
			{
        $s = "";
				if($usuarios[$i]['usuario_id']==$_POST['usuariosctra']) $s = "selected";
          
				$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\" ".$s.">".$usuarios[$i]['nombre']."</option>";
			}
			$this->salida .= "              </select>\n";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"normal_10AN\">&nbsp;&nbsp;&nbsp;URL PROTÓCOLOS:</label>";//".$this->SetStyle("protocoloctra")."
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"protocoloctra\" value=\"".$_POST['protocoloctra']."\" maxlength=\"255\" size=\"48\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "        </table><br>\n";
			$this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td width=\"40%\" class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("valorctra")."\">* VALOR DEL CONTRATO:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"valorctra\" value=\"".$_POST['valorctra']."\" maxlength=\"17\" size=\"22\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr >\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"normal_10AN\">&nbsp;&nbsp;&nbsp;VALOR MENSUAL DEL CONTRATO:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"valmectra\" value=\"".$_POST['valmectra']."\" maxlength=\"17\" size=\"22\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"normal_10AN\">&nbsp;&nbsp;&nbsp;VALOR MÁXIMO POR FACTURA:</label>";//".$this->SetStyle("facturactra")."
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"facturactra\" value=\"".$_POST['facturactra']."\" maxlength=\"13\" size=\"22\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("diasCredito")."\">* DÍAS CRÉDITO:</label>";//".$this->SetStyle("facturactra")."
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"diasCredito\" value=\"".$_POST['diasCredito']."\" maxlength=\"13\" size=\"22\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";        
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("excmonctra")."\">* EXCEDER EL VALOR MENSUAL DEL CONTRATO:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "            <td>\n";
			$chk[$_POST['excmonctra']] = "checked";
      $this->salida .= "              SI <input type=\"radio\" name=\"excmonctra\" value=1 ".$chk[1].">\n";
			$this->salida .= "              NO <input type=\"radio\" name=\"excmonctra\" value=2 ".$chk[2].">\n";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
      $this->salida .= "              <label class=\"".$this->SetStyle("feinictra")."\">* FECHA INICIAL:</label>\n";
      $this->salida .= "            </td>\n";
      $this->salida .= "            <td class=\"normal_10AN\">\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_POST['feinictra']."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "              ".ReturnOpenCalendario('contratacion','feinictra','/')."";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\">\n";
      $this->salida .= "              <label class=\"".$this->SetStyle("fefinctra")."\">* FECHA FINAL:</label>\n";
      $this->salida .= "            </td>\n";
      $this->salida .= "            <td class=\"normal_10AN\">\n";
			$this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_POST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "              ".ReturnOpenCalendario('contratacion','fefinctra','/')."";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";      
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\" colspan=\"2\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("telefono1")."\">* LÍNEAS DE ATENCIÓN -- AUTORIZACIONES:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td colspan=\"2\">\n";
			$this->salida .= "              <textarea class=\"input-text\" name=\"telefono1\" style=\"width:100%\" rows=\"3\">".$_POST['telefono1']."</textarea>";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$servicios=$this->BuscarServiciosContra();
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\" colspan=\"2\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("servicios")."\">* SERVICIOS ASISTENCIALES CONTRATADOS:</label>";
			$this->salida .= "              <input type=\"hidden\" name=\"servicios\" value=\"".sizeof($servicios)."\" class=\"input-text\">";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td colspan=\"2\">\n";
			$this->salida .= "              <table width=\"100%\" border=\"1\" rules=\"1\" align=\"center\" class=\"modulo_table_list\">\n";
			for($i=0;$i<sizeof($servicios);$i++)
			{
        $s = "";
				if($_POST['servicios'.$i]==$servicios[$i]['servicio']) $s = "checked";
				$this->salida .= "                <tr class=\"label\">\n";
				$this->salida .= "                  <td class=\"modulo_list_oscuro\" width=\"90%\" >\n";
				$this->salida .= "                    ".$servicios[$i]['descripcion']."";
				$this->salida .= "                  </td>\n";
				$this->salida .= "                  <td align=\"center\">\n";
        $this->salida .= "                    <input type=\"checkbox\" name=\"servicios".$i."\" value=".$servicios[$i]['servicio']." ".$s.">\n";
				$this->salida .= "                  </td>\n";
				$this->salida .= "                </tr>\n";
			}
			$this->salida .= "              </table>\n";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr >\n";
			$this->salida .= "            <td class=\"modulo_list_oscuro\" colspan=\"2\">\n";
			$this->salida .= "              <label class=\"".$this->SetStyle("bventactra")."\">* BASE PARA LA LIQUIDACIÓN DE INSUMOS Y MEDICAMENTOS:</label>";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "            <td colspan=\"2\">\n";
			$this->salida .= "              <table border=\"1\" rules=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "                <tr class=\"label\">\n";
			$this->salida .= "                  <td width=\"90%\" class=\"modulo_list_oscuro\">COSTO PROMEDIO</td>";
			$this->salida .= "                  <td align=\"center\">\n";
			$sel[$_POST['bventactra']] = "checked";
			$this->salida .= "                    <input type=\"radio\" name=\"bventactra\" value=1 ".$sel[1].">\n";
			$this->salida .= "                  </td>\n";
			$this->salida .= "                </tr>\n";
			$this->salida .= "                <tr class=\"label\">";
			$this->salida .= "                  <td class=\"modulo_list_oscuro\">COSTO ÚLTIMA COMPRA</td>\n";
			$this->salida .= "                  <td align=\"center\">\n";
			$this->salida .= "                    <input type=\"radio\" name=\"bventactra\" value=2 ".$sel[1].">\n";
			$this->salida .= "                  </td>\n";
			$this->salida .= "                </tr>\n";
			$this->salida .= "                <tr class=\"label\">\n";
			$this->salida .= "                  <td class=\"modulo_list_oscuro\">LISTA DE VENTA</td>\n";
			$this->salida .= "                  <td align=\"center\">\n";
			$this->salida .= "                    <input type=\"radio\" name=\"bventactra\" value=3 ".$sel[1].">\n";
			$this->salida .= "                  </td>\n";
			$this->salida .= "                </tr>\n";
			$listaprecios=$this->TraerListaPrecios();
      if($listaprecios)
      {
  			$this->salida .= "                <tr>\n";
  			$this->salida .= "                  <td colspan=\"2\" class=\"modulo_list_oscuro\">\n";
  			$this->salida .= "                    <select name=\"listaprecios\" class=\"select\">";
  			
  			for($i=0;$i<sizeof($listaprecios);$i++)
  			{
  				$s = "";
          if($listaprecios[$i]['codigo_lista']==$_POST['listaprecios']) $s="selected";
  				$this->salida .="<option value=\"".$listaprecios[$i]['codigo_lista']."\" ".$s.">".$listaprecios[$i]['descripcion']."</option>";
  			}
  			$this->salida .= "                    </select>\n";
  			$this->salida .= "                  </td>\n";
  			$this->salida .= "                </tr>\n";
      }
			$this->salida .= "                <tr class= \"label\">\n";
			$this->salida .= "                  <td class=\"modulo_list_oscuro\" >PORCENTAJE POR DEFECTO</td>\n";
			$this->salida .= "                  <td >\n";
			$this->salida .= "                    <input type=\"text\" class=\"input-text\" name=\"porcentaje\" value=\"".$_POST['porcentaje']."\" maxlength=\"10\" style=\"width:70%\">%";
			$this->salida .= "                  </td>\n";
			$this->salida .= "                </tr>\n";
			$this->salida .= "              </table>\n";
			$this->salida .= "            </td>\n";
			$this->salida .= "          </tr>\n";
			$this->salida .= "        </table>\n";
			$this->salida .= "      </fieldset>\n";
			$this->salida .= "    </td>\n";
      $this->salida .= "  </tr>";
			$this->salida .= "</table>\n";
			$this->salida .= "<table border=\"0\" width=\"40%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
			$this->salida .= "  </form>";
			$this->salida .= "  </td>";
			$accion=ModuloGetURL('app','Contratacion','user','EmpresasContra');
			$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
			$this->salida .= "  </td>";
			$this->salida .= "  </form>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
    /**
    * Funcion donde se comtinua con el ingreso de los datos de la contratacion
    * !Llama a validar datos y despues vulveve al menu principal
    *
    * @return boolean
    */
    function IngresaDatosPlan2()
    {
        UNSET($_SESSION['ctrpla']['afilcrea']);
        UNSET($_SESSION['ctrpla']['afiliado']);
        UNSET($_SESSION['ctrpla']['rangospl']);
        if($_SESSION['ctrpla']['busccrea']==1)
        {
            UNSET($_SESSION['ctrpla']['busccrea']);
            $datosconsu=$this->MostrarIngresaDatosPlan2($_SESSION['ctrpla']['plancrea']);
            //$_POST['liquihactra']=$datosconsu['tipo_liq_habitacion'];
            $_POST['liquihactra']=$datosconsu['sw_contrata_hospitalizacion'];
            $_POST['ponderarctra']=$datosconsu['tipo_liquidacion_id'];
            $_POST['liquidacarctra']=$datosconsu['tipo_liquidacion_cargo'];
            $_POST['capitactra']=$datosconsu['sw_autoriza_sin_bd'];
            $_POST['afiliactra']=$datosconsu['sw_afiliacion'];
            $_POST['facagrctra']=$datosconsu['sw_facturacion_agrupada'];
            $_POST['paracadctra']=$datosconsu['sw_paragrafados_cd'];
            $_POST['paraimdctra']=$datosconsu['sw_paragrafados_imd'];
            $_POST['copagoctra']=$datosconsu['nombre_copago'];
            $_POST['cuotactra']=$datosconsu['nombre_cuota_moderadora'];
            $_POST['incumpctra']=$datosconsu['actividad_incumplimientos'];
            $_POST['mesconbd']=$datosconsu['meses_consulta_base_datos'];
            $_POST['horaprecan']=$datosconsu['horas_cancelacion'];
            $_POST['linecancit']=$datosconsu['telefono_cancelacion_cita'];
            $_POST['tipaimdctra']=$datosconsu['tipo_para_imd'];
        }
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - DATOS DEL PLAN CLIENTE');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarDatosPlanContra2');
        
        $this->salida .= "<table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
  			$this->salida .= "  <tr class=\"formulacion_table_list\">\n";
  			$this->salida .= "    <td>EMPRESA</td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "  <tr class=\"normal_10AN\">\n";
  			$this->salida .= "    <td align=\"center\">".$_SESSION['contra']['razonso']."</td>\n";
  			$this->salida .= "  </tr>\n";
  			$this->salida .= "</table><br>\n";
        $this->salida .= "<table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
  			$this->salida .= "  <tr >\n";
        $this->salida .= "    <td class=\"formulacion_table_list\" width=\"30%\">PLAN</td>\n";
        $this->salida .= "    <td class=\"label\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numecrea']."".' --- '."".$_SESSION['ctrpla']['desccrea']."";
        $this->salida .= "    </td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "  <tr>\n";
        $this->salida .= "    <td class=\"formulacion_table_list\">CLIENTE:</td>\n";
        $this->salida .= "    <td class=\"label\"> ".$_SESSION['ctrpla']['nombcrea']."</td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "</table><br>";

        $this->salida .= "<form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td>\n";
        $this->salida .= "        <fieldset class=\"fieldset\">\n";
        $this->salida .= "          <legend class=\"normal_10AN\">INFORMACIÓN ADICIONAL DEL PLAN</legend>\n";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("liquihactra")."\">* LIQUIDACIÓN DE LA HABITACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "SI&nbsp;<input type='radio' name='liquihactra' value=1 checked>&nbsp;&nbsp;&nbsp;";
        $this->salida .= "NO&nbsp;<input type='radio' name='liquihactra' value=1>";
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("ponderarctra")."\">* LIQUIDACIÓN DE SEMANAS<br>PARA DÍAS DE CARENCIA:</label>";
        $this->salida .= "      </td>";
        $semana=$this->BuscarLiqSemContra();
        $this->salida .= "      <td>";
        $this->salida .= "      <select name=\"ponderarctra\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_liquidacion_id']==$_POST['ponderarctra'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_id']."\" selected>".$semana[$i]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_id']."\">".$semana[$i]['descripcion']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("liquidacarctra")."\">* LIQUIDACIÓN DE CARGOS:</label>";
        $this->salida .= "      </td>";
        $semana=$this->BuscarLiqCarContra();
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <select name=\"liquidacarctra\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_liquidacion_cargo']==$_POST['liquidacarctra'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_cargo']."\" selected>".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_cargo']."\">".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tipaimdctra")."\">* TIPO PARAGRAFADOS DE INSUMOS Y MEDICAMENTOS:</label>";
        $this->salida .= "      </td>";
        $semana=$this->BuscarTipoParaImdContra();
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <select name=\"tipaimdctra\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_para_imd']==$_POST['tipaimdctra'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_para_imd']."\" selected>".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_para_imd']."\">".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("capitactra")."\">* PERMITIR AUTORIZACIÓN SIN BASE DE DATOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>SI";
        if($_POST['capitactra']==1)
        {
            $this->salida .= "      <input type='radio' name='capitactra' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='capitactra' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['capitactra']==0)
        {
            $this->salida .= "      <input type='radio' name='capitactra' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='capitactra' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        
        $this->salida .= "          <tr class=\"modulo_list_oscuro\">\n";
  			$this->salida .= "            <td>\n";
  			$this->salida .= "              <label class=\"".$this->SetStyle("auto_solicitud")."\">* SOLICITUD AUTORIZACION:</label>";
  			$this->salida .= "            </td>\n";
  			$this->salida .= "            <td>\n";
  			$chk1[$_POST['auto_solicitud']] = "checked";
        $this->salida .= "              SI <input type=\"radio\" name=\"auto_solicitud\" value=1 ".$chk1[1].">\n";
  			$this->salida .= "              NO <input type=\"radio\" name=\"auto_solicitud\" value=0 ".$chk1[0].">\n";
  			$this->salida .= "            </td>\n";
  			$this->salida .= "          </tr>\n";
        
        $afiliados[0]['detalle'] = "NO MANEJA AFILIADOS";
        $afiliados[1]['detalle'] = "MANEJA AFILIADOS";
        $afiliados[2]['detalle'] = "ATENCION PARTICULAR AFILIADOS";
        
        $this->salida .= "          <tr class=\"modulo_list_claro\">\n";
  			$this->salida .= "            <td >\n";
        $this->salida .= "              <label class=\"".$this->SetStyle("sw_afiliaciones")."\">* AFILIADOS:</label>\n";
        $this->salida .= "            </td>\n";
        $this->salida .= "            <td>\n";
  			$this->salida .= "              <select name=\"sw_afiliaciones\" class=\"select\">\n";
  			$this->salida .= "                <option value=\"-1\">--  SELECCIONE  --</option>\n";

        $s="";
        foreach($afiliados as $key=> $dtl)
        {
          ($_POST['sw_afiliaciones'] == $key)? $s = "selected": $s ="";
          $this->salida .= "              <option value=\"".$key."\" ".$s.">".$dtl['detalle']."</option>\n";
        }
        
  			$this->salida .= "              </select>\n";
  			$this->salida .= "            </td>\n";
  			$this->salida .= "          </tr>\n";
        
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("afiliactra")."\">* MANEJO DE BASE DE DATOS DE AFILIADOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>SI";
        if($_POST['afiliactra']==1)
        {
            $this->salida .= "      <input type='radio' name='afiliactra' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='afiliactra' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['afiliactra']==0)
        {
            $this->salida .= "      <input type='radio' name='afiliactra' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='afiliactra' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("facagrctra")."\">* FACTURACIÓN AGRUPADA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>SI";
        if($_POST['facagrctra']==1)
        {
            $this->salida .= "      <input type='radio' name='facagrctra' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='facagrctra' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['facagrctra']==0)
        {
            $this->salida .= "      <input type='radio' name='facagrctra' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='facagrctra' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("paraimdctra")."\">* PARAGRAFADOS INSUMOS Y MEDICAMENTOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>SI";
        if($_POST['paraimdctra']==1)
        {
            $this->salida .= "      <input type='radio' name='paraimdctra' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paraimdctra' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['paraimdctra']==0)
        {
            $this->salida .= "      <input type='radio' name='paraimdctra' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paraimdctra' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("paracadctra")."\">* PARAGRAFADOS CARGOS DIRECTOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>SI";
        if($_POST['paracadctra']==1)
        {
            $this->salida .= "      <input type='radio' name='paracadctra' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paracadctra' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['paracadctra']==0)
        {
            $this->salida .= "      <input type='radio' name='paracadctra' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paracadctra' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"normal_10AN\">NOMBRE DEL COPAGO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagoctra\" value=\"".$_POST['copagoctra']."\" maxlength=\"20\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"normal_10AN\">NOMBRE DE LA CUOTA MODERADORA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cuotactra\" value=\"".$_POST['cuotactra']."\" maxlength=\"20\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("mesconbd")."\">* MESES PARA CONSULTAR EN LA BD:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"mesconbd\" value=\"".$_POST['mesconbd']."\" maxlength=\"5\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"".$this->SetStyle("incumpctra")."\">* MOSTRAR LOS ÚLTIMOS DÍAS DE INCUMPLIMIENTO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"incumpctra\" value=\"".$_POST['incumpctra']."\" maxlength=\"5\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">HORAS PREVIAS PARA CANCELAR CITA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"horaprecan\" value=\"".$_POST['horaprecan']."\" maxlength=\"2\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">LÍNEAS PARA CANCELACIÓN DE CITA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"linecancit\" value=\"".$_POST['linecancit']."\" maxlength=\"40\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td>";
        $this->salida .= "      <label class=\"normal_10AN\">OBSERVACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <textarea class=\"input-text\" name=\"observacion\" cols=\"45\" rows=\"4\">".$_POST['observacion']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" class=\"modulo_table_list_title\">";
        $this->salida .= "SELECCIONE LOS TIPOS DE AFILIACIÓN PARA INGRESAR LOS VALORES";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\" align=\"center\" class=\"".$this->SetStyle("rangctra")."\">";
        $this->salida .= "NÚMERO DE RANGOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" align=\"center\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"rangctra\" value=\"".$_POST['rangctra']."\" maxlength=\"2\" size=\"4\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\" align=\"center\" class=\"".$this->SetStyle("afilia")."\">";
        $this->salida .= "      <input type=\"hidden\" name=\"afilia\" class=\"input-text\">";
        $this->salida .= "TIPO DE AFILIACIÓN";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" align=\"center\">";
        $_SESSION['ctrpla']['afilcrea']=$this->BuscarTipoAfiliadoContra();
        $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
        $ciclo=sizeof($_SESSION['ctrpla']['afilcrea']);
        if($_SESSION['ctrpla']['tipocrea']<>1)
        {
            for($i=0;$i<$ciclo;$i++)
            {
                $this->salida .= "          <tr>";
                $this->salida .= "          <td align=\"center\" width=\"70%\">";
                $this->salida .= "          ".strtoupper($_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_nombre'])."";
                $this->salida .= "          </td>";
                $this->salida .= "          <td align=\"center\" width=\"30%\">";
                if($_POST['afiliados'.$i]==NULL)
                {
                    $this->salida .= "          <input type=\"checkbox\" name=\"afiliados".$i."\" value=\"".$_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_id']."\">";
                }
                else
                {
                    $this->salida .= "          <input type=\"checkbox\" name=\"afiliados".$i."\" value=\"".$_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_id']."\" checked>";
                }
                $this->salida .= "          </td>";
                $this->salida .= "          </tr>";
            }
        }
        else
        {
            for($i=0;$i<$ciclo;$i++)
            {
                if($_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_id']==0)
                {
                    $this->salida .= "          <tr>";
                    $this->salida .= "          <td align=\"center\" width=\"70%\">";
                    $this->salida .= "          ".strtoupper($_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_nombre'])."";
                    $this->salida .= "          </td>";
                    $this->salida .= "          <td align=\"center\" width=\"30%\">";
                    if($_POST['afiliados'.$i]==NULL)
                    {
                        $this->salida .= "          <input type=\"checkbox\" name=\"afiliados".$i."\" value=\"".$_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_id']."\">";
                    }
                    else
                    {
                        $this->salida .= "          <input type=\"checkbox\" name=\"afiliados".$i."\" value=\"".$_SESSION['ctrpla']['afilcrea'][$i]['tipo_afiliado_id']."\" checked>";
                    }
                    $this->salida .= "          </td>";
                    $this->salida .= "          </tr>";
                }
            }
        }
        $this->salida .= "         </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','EmpresasContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que crea las matrices de los rangos con sus respectivos valores
    function ValoresRangosContra()//Valida los datos de los rangos
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - RANGOS DEL PLAN CLIENTE');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarDatosRangosContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">RANGOS Y AFILIADOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numecrea']."".' --- '."".$_SESSION['ctrpla']['desccrea']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombcrea']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $j=0;
        $ciclo=$_SESSION['ctrpla']['rangospl'];
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=modulo_list_claro";
                $j=1;
            }
            else
            {
                $color="class=modulo_list_oscuro";
                $j=0;
            }
            $this->salida .= "      <tr $color>";
            $this->salida .= "      <td width=\"20%\" align=\"center\">";
            $this->salida .= "      <label class=\"".$this->SetStyle("nomranctra".$i)."\">RANGO"." -> </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nomranctra".$i."\" value=\"".$_POST['nomranctra'.$i]."\" maxlength=\"40\" size=\"23\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"80%\" align=\"center\">";
            $this->salida .= "          <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
            $this->salida .= "          <td align=\"center\" width=\"20%\">";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">CUOTA MODERADORA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO (%)";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÁXIMO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÍNIMO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÁXIMO AÑO";
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $ciclo1=sizeof($_SESSION['ctrpla']['afilcrea']);
            for($k=0;$k<$ciclo1;$k++)
            {
                if(!($_SESSION['ctrpla']['afiliado'][$k]==NULL))//Llaves
                {
                    if($_POST['cuotamod'.$i.$k]==NULL)
                    {
                        $_POST['cuotamod'.$i.$k]='0.00';
                    }
                    if($_POST['copagopor'.$i.$k]==NULL)
                    {
                        $_POST['copagopor'.$i.$k]='0.00';
                    }
                    if($_POST['copagomax'.$i.$k]==NULL)
                    {
                        $_POST['copagomax'.$i.$k]='0.00';
                    }
                    if($_POST['copagomin'.$i.$k]==NULL)
                    {
                        $_POST['copagomin'.$i.$k]='0.00';
                    }
                    if($_POST['copagoano'.$i.$k]==NULL)
                    {
                        $_POST['copagoano'.$i.$k]='0.00';
                    }
                    $this->salida .= "      <tr class=modulo_list_claro>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      ".strtoupper($_SESSION['ctrpla']['afilcrea'][$k]['tipo_afiliado_nombre'])."";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cuotamod".$i.$k."\" value=\"".$_POST['cuotamod'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagopor".$i.$k."\" value=\"".$_POST['copagopor'.$i.$k]."\" maxlength=\"8\" size=\"8\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagomax".$i.$k."\" value=\"".$_POST['copagomax'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagomin".$i.$k."\" value=\"".$_POST['copagomin'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagoano".$i.$k."\" value=\"".$_POST['copagoano'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      </tr>";
                }
            }
            $this->salida .= "         </table><br>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR LOS VALORES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','IngresaDatosPlan2');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"    value=\"VOLVER  A  LOS RANGOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que modifica los datos del plan
    function ModificaDatosPlan()//Llama a validar plan y vuelve al menu principal
    {
        if(!($this->uno == 1))
        {
            $planeleg=$this->MostrarEmpresasPlanes($_SESSION['ctrpla']['planeleg']);
            $_POST['tipoplctraM']=$planeleg['sw_tipo_plan'];
            $_POST['descrictraM']=$planeleg['plan_descripcion'];
            $_POST['tipoTerceroId']=$planeleg['tipo_tercero_id'];
            $_POST['nombre']=$_SESSION['ctrpla']['nombeleg'];
            $_POST['codigo']=$planeleg['tercero_id'];
            $_POST['numeroctraM']=$planeleg['num_contrato'];
            //echo $_POST['numeroctraM'].'AAA';
            $_POST['valorctraM']=FormatoValor($planeleg['monto_contrato']);
            $_POST['valmectraM']=FormatoValor($planeleg['monto_contrato_mensual']);
            $_POST['saldoctraM']=FormatoValor($planeleg['saldo_contrato']);
            $_POST['facturactraM']=FormatoValor($planeleg['tope_maximo_factura']);
            
            $_POST['diasCredito']=$planeleg['dias_credito_cartera'];
            
            $_POST['excmonctraM']=$planeleg['sw_exceder_monto_mensual'];
            $fecha=explode('-',$planeleg['fecha_inicio']);
            $_POST['feinictraM']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $fecha=explode('-',$planeleg['fecha_final']);
            $_POST['fefinctraM']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $_POST['clientectraM']=$planeleg['tipo_cliente'];
            //$_POST['liquihactraM']=$planeleg['tipo_liq_habitacion'];
            $_POST['capitactraM']=$planeleg['sw_autoriza_sin_bd'];
            $_POST['afiliactraM']=$planeleg['sw_afiliacion'];
            $_POST['ponderarctraM']=$planeleg['tipo_liquidacion_id'];
						$_POST['LiquidaHab']=$planeleg['sw_contrata_hospitalizacion'];
            $_POST['facagrctraM']=$planeleg['sw_facturacion_agrupada'];
            $_POST['paraimdctraM']=$planeleg['sw_paragrafados_imd'];
            $_POST['paracadctraM']=$planeleg['sw_paragrafados_cd'];
            $_POST['observacionM']=$planeleg['observacion'];
            $_POST['servicioctraM']=$planeleg['servicios_contratados'];
            $_POST['protocoloctraM']=$planeleg['protocolos'];
            $_POST['contactoctraM']=$planeleg['contacto'];
            $_POST['usuariosctraM']=$planeleg['usuario_id'];
            $_POST['telefono1M']=$planeleg['lineas_atencion'];
            $_POST['bventactraM']=$planeleg['sw_base_liquidacion_imd'];
            $_POST['copagoctraM']=$planeleg['nombre_copago'];
            $_POST['cuotactraM']=$planeleg['nombre_cuota_moderadora'];
            $_POST['incumpctraM']=$planeleg['actividad_incumplimientos'];
            $_POST['liquidacarctraM']=$planeleg['tipo_liquidacion_cargo'];
            $_POST['mesconbdM']=$planeleg['meses_consulta_base_datos'];
            $_POST['horaprecanM']=$planeleg['horas_cancelacion'];
            $_POST['linecancitM']=$planeleg['telefono_cancelacion_cita'];
            $_POST['tipaimdctraM']=$planeleg['tipo_para_imd'];
						$_POST['listaprecios']=$planeleg['lista_precios'];
						$_POST['porcentaje']=$planeleg['porcentaje_utilidad'];
						$_POST['sw_afiliaciones']=$planeleg['sw_afiliados'];
            $_POST['auto_solicitud'] = $planeleg['sw_solicita_autorizacion_admision'];

        }
       
				SessionDelVar("CondicionUsuario");
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - DATOS DEL PLAN CLIENTE - MODIFICAR');
        $mostrar=ReturnClassBuscador('proveedores','','','contratacion','');
        $this->salida .=$mostrar;
        $this->salida .="</script>\n";
				$mostrar1 .= "<SCRIPT>";
				$mostrar1 .= "function listamanual(forma,valor){ ";
				$mostrar1 .= "  if(valor=='3'){";
				$mostrar1 .= "    forma.listaprecios.disabled=false; ";
				$mostrar1 .= "  }";
				$mostrar1 .= "  else";
				$mostrar1 .= "  {";
				$mostrar1 .= "    forma.listaprecios.disabled=true;";
				$mostrar1 .= "    forma.listaprecios.value='000'; ";
				$mostrar1 .= "  }";
				$mostrar1 .= "}";
				$mostrar1 .= "function VerTiposUsuarios(){ ";
				$mostrar1 .= "  objeto = document.getElementById('tiposusuarios');";
				$mostrar1 .= "  if(objeto.style.display == \"none\"){ ";
				$mostrar1 .= "   objeto.style.display = \"block\"; ";
				$mostrar1 .= "  }";
				$mostrar1 .= "  else";
				$mostrar1 .= "  {";
				$mostrar1 .= "    objeto.style.display = \"none\";";
				$mostrar1 .= "  }";
				$mostrar1 .= "}";
				$mostrar1 .= "</SCRIPT>";
				$this->salida .=$mostrar1;
        $accion=ModuloGetURL('app','Contratacion','user','ModificarDatosPlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tipoplctraM")."\">TIPO PLAN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $tipoplan=$this->BuscarTipoPlanContra();
        $this->salida .= "      <select name=\"tipoplctraM\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
        for($i=0;$i<sizeof($tipoplan);$i++)
        {
            if($tipoplan[$i]['sw_tipo_plan']==$_POST['tipoplctraM'])
            {
                $this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\" selected>".$tipoplan[$i]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\">".$tipoplan[$i]['descripcion']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"10%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tipoTerceroId")."\">TIPO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"15%\">";
        $this->salida .= "      <input type=\"text\" name=\"tipoTerceroId\" size=\"4\" class=\"input-text\" value=\"".$_POST['tipoTerceroId']."\" READONLY>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"13%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("nombre")."\">CLIENTE:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      <input type=\"button\" name=\"proveedor\" value=\"CLIENTE\" onclick=abrirVentana() class=\"input-submit\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <td><label class=\"".$this->SetStyle("codigo")."\">DOCUMENTO:</label>";//&nbsp&nbsp&nbsp;
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("numeroctraM")."\">NÚMERO DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroctraM\" value=\"".$_POST['numeroctraM']."\" maxlength=\"20\" size=\"33\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("descrictraM")."\">DESCRIPCIÓN DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrictraM\" value=\"".$_POST['descrictraM']."\" maxlength=\"60\" size=\"48\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("servicioctraM")."\">SERVICIOS CONTRATADOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <textarea class=\"input-text\" name=\"servicioctraM\" cols=\"45\" rows=\"4\">".$_POST['servicioctraM']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("contactoctraM")."\">CONTACTO<br>(NOMBRE COMPLETO Y TELEFÓNOS):</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <textarea class=\"input-text\" name=\"contactoctraM\" cols=\"45\" rows=\"4\">".$_POST['contactoctraM']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("usuariosctraM")."\">ENCARGADO:</label>";
        $this->salida .= "      </td>";
        $usuarios=$this->BuscarEncargadosContra($_SESSION['contra']['empresa']);
        $this->salida .= "      <td>";
        $this->salida .= "      <select name=\"usuariosctraM\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
        $ciclo=sizeof($usuarios);
        for($i=0;$i<$ciclo;$i++)
        {
            if($usuarios[$i]['usuario_id']==$_POST['usuariosctraM'])
            {
                $this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\" selected>".$usuarios[$i]['nombre']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\">".$usuarios[$i]['nombre']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"3\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("protocoloctraM")."\">URL PROTÓCOLOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"protocoloctraM\" value=\"".$_POST['protocoloctraM']."\" maxlength=\"255\" size=\"48\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </table><br>";
        $var=$this->CambiarRangosPlan($_SESSION['ctrpla']['planeleg']);
        $readonly = '';
        if($var > 0)
        {
          $readonly = 'READONLY';
        }
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
				//TIPOS DE USUARIOS
				$this->salida .= "      <tr class=modulo_list_oscuro>";
				$this->salida .= "      <td width=\"40%\">"; 
				$this->salida .= "       <a href=\"javascript:VerTiposUsuarios();\">Tipos de usuarios</a>";
				$this->salida .= "      </td>";
				$dat = $this->GetDatosCondicionUsuario();
				SessionSetVar("CondicionUsuario",$dat);
				$Obtener = $this->GetPlanCondicionUsuario();
				$this->salida .= "      <td width=\"60%\">";
				$this->salida .= "       <div id='tiposusuarios' style=\"display:none\">";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
				foreach($dat AS $i => $v)
				{
					$checked = "";
					foreach($Obtener AS $i1 => $v1)
					{
						if($v[tipos_condicion_usuarios_planes_id] == $v1[tipos_condicion_usuarios_planes_id])
						{
							$checked = "checked";
						}
					}
					$this->salida .= "      <tr class=modulo_list_claro>";
					$this->salida .= "      <td width=\"100%\">";
					$this->salida .= "      <input type=\"checkbox\" name=\"Condicion".$v[tipos_condicion_usuarios_planes_id]."\" value=\"1\" $checked>&nbsp;&nbsp;&nbsp;$v[descripcion]";
					$this->salida .= "      </td>";
					$this->salida .= "      </tr>";
				}
				$this->salida .= "      </table>";
				//$this->salida .= "       TIPOS DE USUARIOS";
				$this->salida .= "       </div>";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				//FIN TIPOS DE USUARIOS
        
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("valorctraM")."\">VALOR DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valorctraM\" value=\"".$_POST['valorctraM']."\" maxlength=\"19\" size=\"22\" $readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">VALOR MENSUAL DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valmectraM\" value=\"".$_POST['valmectraM']."\" maxlength=\"17\" size=\"22\" $readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">VALOR MÁXIMO POR FACTURA:</label>";//".$this->SetStyle("facturactraM")."
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"facturactraM\" value=\"".$_POST['facturactraM']."\" maxlength=\"15\" size=\"22\" $readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("diasCredito")."\">DÍAS CRÉDITO: </label>";//".$this->SetStyle("facturactraM")."
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"diasCredito\" value=\"".$_POST['diasCredito']."\" maxlength=\"15\" size=\"22\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";        
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">EXCEDER EL VALOR MENSUAL DEL CONTRATO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"label\">";
        $this->salida .= "SI  ";
        if($_POST['excmonctraM']==1)
        {
            $this->salida .= "      <input type=\"radio\" name=\"excmonctraM\" value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type=\"radio\" name=\"excmonctraM\" value=1>";
        }
        $this->salida .= "    NO  ";
        if($_POST['excmonctraM']==0)
        {
            $this->salida .= "      <input type=\"radio\" name=\"excmonctraM\" value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type=\"radio\" name=\"excmonctraM\" value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("feinictraM")."\">FECHA INICIAL:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictraM\" value=\"".$_POST['feinictraM']."\" maxlength=\"10\" size=\"12\">";
        $this->salida .= "      ".ReturnOpenCalendario('contratacion','feinictraM','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fefinctraM")."\">FECHA FINAL:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctraM\" value=\"".$_POST['fefinctraM']."\" maxlength=\"10\" size=\"12\">";
        $this->salida .= "      ".ReturnOpenCalendario('contratacion','fefinctraM','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("clientectraM")."\">TIPO CLIENTE:</label>";//class=\"".$this->SetStyle("clientectraM")."\"
        $this->salida .= "      </td>";
        $semana=$this->BuscarClientesContra();
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <select name=\"clientectraM\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_cliente']==$_POST['clientectraM'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_cliente']."\" selected>".$semana[$i]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_cliente']."\">".$semana[$i]['descripcion']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("LiquidaHab")."\">MANEJA LIQUIDACIÓN DE LA HABITACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"normal_10AN\">SI";
        if($_POST['LiquidaHab']==1)
        {
            $this->salida .= "      <input type='radio' name='LiquidaHab' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='LiquidaHab' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['LiquidaHab']==0)
        {
            $this->salida .= "      <input type='radio' name='LiquidaHab' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='LiquidaHab' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("ponderarctraM")."\">LIQUIDACIÓN DE SEMANAS<br>PARA DÍAS DE CARENCIA:</label>";
        $this->salida .= "      </td>";
        $semana=$this->BuscarLiqSemContra();
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <select name=\"ponderarctraM\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_liquidacion_id']==$_POST['ponderarctraM'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_id']."\" selected>".$semana[$i]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_id']."\">".$semana[$i]['descripcion']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("liquidacarctraM")."\">LIQUIDACIÓN DE CARGOS:</label>";
        $this->salida .= "      </td>";
        $semana=$this->BuscarLiqCarContra();
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <select name=\"liquidacarctraM\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_liquidacion_cargo']==$_POST['liquidacarctraM'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_cargo']."\" selected>".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_liquidacion_cargo']."\">".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tipaimdctraM")."\">TIPO PARAGRAFADOS DE<br>INSUMOS Y MEDICAMENTOS:</label>";
        $this->salida .= "      </td>";
        $semana=$this->BuscarTipoParaImdContra();
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <select name=\"tipaimdctraM\" class=\"select\">";
        $this->salida .= "      <option value=\"\">--------  SELECCIONE  --------</option>";
        for($i=0;$i<sizeof($semana);$i++)
        {
            if($semana[$i]['tipo_para_imd']==$_POST['tipaimdctraM'])
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_para_imd']."\" selected>".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$semana[$i]['tipo_para_imd']."\">".substr($semana[$i]['descripcion'],0,90)."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("capitactraM")."\">PERMITIR AUTORIZACIÓN SIN BASE DE DATOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"normal_10AN\">SI";
        if($_POST['capitactraM']==1)
        {
            $this->salida .= "      <input type='radio' name='capitactraM' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='capitactraM' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['capitactraM']==0)
        {
            $this->salida .= "      <input type='radio' name='capitactraM' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='capitactraM' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        
        $this->salida .= "          <tr class=\"modulo_list_oscuro\">\n";
  			$this->salida .= "            <td>\n";
  			$this->salida .= "              <label class=\"".$this->SetStyle("auto_solicitud")."\">SOLICITUD AUTORIZACION:</label>";
  			$this->salida .= "            </td>\n";
  			$this->salida .= "            <td>\n";
  			$chk1[$_POST['auto_solicitud']] = "checked";
        $this->salida .= "              SI <input type=\"radio\" name=\"auto_solicitud\" value=1 ".$chk1[1].">\n";
  			$this->salida .= "              NO <input type=\"radio\" name=\"auto_solicitud\" value=0 ".$chk1[0].">\n";
  			$this->salida .= "            </td>\n";
  			$this->salida .= "          </tr>\n";
        
        $afiliados[0]['detalle'] = "NO MANEJA AFILIADOS";
        $afiliados[1]['detalle'] = "MANEJA AFILIADOS";
        $afiliados[2]['detalle'] = "ATENCION PARTICULAR AFILIADOS";
        
        $this->salida .= "          <tr class=\"modulo_list_claro\">\n";
  			$this->salida .= "            <td >\n";
        $this->salida .= "              <label class=\"".$this->SetStyle("sw_afiliaciones")."\">AFILIADOS:</label>\n";
        $this->salida .= "            </td>\n";
        $this->salida .= "            <td>\n";
  			$this->salida .= "              <select name=\"sw_afiliaciones\" class=\"select\">\n";
  			$this->salida .= "                <option value=\"-1\">--  SELECCIONE  --</option>\n";

        $s="";
        foreach($afiliados as $key=> $dtl)
        {
          ($_POST['sw_afiliaciones'] == $key)? $s = "selected": $s ="";
          $this->salida .= "              <option value=\"".$key."\" ".$s.">".$dtl['detalle']."</option>\n";
        }
        
  			$this->salida .= "              </select>\n";
  			$this->salida .= "            </td>\n";
  			$this->salida .= "          </tr>\n";
        
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("afiliactraM")."\">MANEJO DE BASE DE DATOS DE AFILIADOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"label\">SI";
        if($_POST['afiliactraM']==1)
        {
            $this->salida .= "      <input type='radio' name='afiliactraM' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='afiliactraM' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['afiliactraM']==0)
        {
            $this->salida .= "      <input type='radio' name='afiliactraM' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='afiliactraM' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("facagrctraM")."\">FACTURACIÓN AGRUPADA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"label\">SI";
        if($_POST['facagrctraM']==1)
        {
            $this->salida .= "      <input type='radio' name='facagrctraM' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='facagrctraM' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['facagrctraM']==0)
        {
            $this->salida .= "      <input type='radio' name='facagrctraM' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='facagrctraM' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("paraimdctraM")."\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"label\">SI";
        if($_POST['paraimdctraM']==1)
        {
            $this->salida .= "      <input type='radio' name='paraimdctraM' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paraimdctraM' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['paraimdctraM']==0)
        {
            $this->salida .= "      <input type='radio' name='paraimdctraM' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paraimdctraM' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("paracadctraM")."\">PARAGRAFADOS CARGOS DIRECTOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\" class=\"label\">SI";
        if($_POST['paracadctraM']==1)
        {
            $this->salida .= "      <input type='radio' name='paracadctraM' value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paracadctraM' value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['paracadctraM']==0)
        {
            $this->salida .= "      <input type='radio' name='paracadctraM' value=0 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name='paracadctraM' value=0>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">NOMBRE DEL COPAGO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagoctraM\" value=\"".$_POST['copagoctraM']."\" maxlength=\"20\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">NOMBRE DE LA CUOTA MODERADORA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cuotactraM\" value=\"".$_POST['cuotactraM']."\" maxlength=\"20\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("mesconbdM")."\">MESES PARA CONSULTAR EN LA BD:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"mesconbdM\" value=\"".$_POST['mesconbdM']."\" maxlength=\"5\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("incumpctraM")."\">MOSTRAR LOS ÚLTIMOS DÍAS DE INCUMPLIMIENTO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"incumpctraM\" value=\"".$_POST['incumpctraM']."\" maxlength=\"5\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">HORAS PREVIAS PARA CANCELAR CITA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"horaprecanM\" value=\"".$_POST['horaprecanM']."\" maxlength=\"2\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">LÍNEAS PARA CANCELACIÓN DE CITA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"linecancitM\" value=\"".$_POST['linecancitM']."\" maxlength=\"40\" size=\"30\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("telefono1M")."\">LÍNEAS DE ATENCIÓN -- AUTORIZACIONES:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <textarea class=\"input-text\" name=\"telefono1M\" cols=\"45\" rows=\"4\">".$_POST['telefono1M']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"normal_10AN\">OBSERVACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "      <textarea class=\"input-text\" name=\"observacionM\" cols=\"45\" rows=\"4\">".$_POST['observacionM']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $servicios=$this->BuscarServiciosContra();
        $serveleg=$this->MostrarServiciosPlanes($_SESSION['ctrpla']['planeleg']);
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("serviciosM")."\">SERVICIOS ASISTENCIALES CONTRATADOS:</label>";
        $this->salida .= "      <input type=\"hidden\" name=\"serviciosM\" value=\"".sizeof($servicios)."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
        for($i=0;$i<sizeof($servicios);$i++)
        {
            $this->salida .= "      <tr align=\"center\">";
            $this->salida .= "      <td width=\"80%\" class=\"label\">";
            $this->salida .= "".$servicios[$i]['descripcion']."";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"20%\">";
            if($_POST['serviciosM'.$i]==$servicios[$i]['servicio'] OR $serveleg[$servicios[$i]['servicio']]==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"serviciosM".$i."\" value=".$servicios[$i]['servicio']." checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"serviciosM".$i."\" value=".$servicios[$i]['servicio'].">";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "          </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"40%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("bventactraM")."\">BASE PARA LA LIQUIDACIÓN DE<br>INSUMOS Y MEDICAMENTOS:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"60%\">";
        $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
        $this->salida .= "          <tr align=\"center\">";
        $this->salida .= "          <td width=\"80%\" class=\"label\">";
        $this->salida .= "COSTO PROMEDIO";
        $this->salida .= "          </td>";
        $this->salida .= "          <td width=\"20%\">";
        if($_POST['bventactraM']==1)
        {
            $this->salida .= "      <input type=\"radio\" name=\"bventactraM\" value=1 checked onclick=\"listamanual(this.form,this.value)\">";
        }
        else
        {
            $this->salida .= "      <input type=\"radio\" name=\"bventactraM\" value=1 onclick=\"listamanual(this.form,this.value)\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr align=\"center\">";
        $this->salida .= "          <td width=\"80%\" class=\"label\">";
        $this->salida .= "COSTO ÚLTIMA COMPRA";
        $this->salida .= "          </td>";
        $this->salida .= "          <td width=\"20%\">";
        if($_POST['bventactraM']==2)
        {
            $this->salida .= "      <input type=\"radio\" name=\"bventactraM\" value=2 checked onclick=\"listamanual(this.form,this.value)\">";
        }
        else
        {
            $this->salida .= "      <input type=\"radio\" name=\"bventactraM\" value=2 onclick=\"listamanual(this.form,this.value)\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr align=\"center\">";
        $this->salida .= "          <td width=\"80%\" class=\"label\">";
        $this->salida .= "LISTA DE VENTA";
        $this->salida .= "          </td>";
        $this->salida .= "          <td width=\"20%\">";
        if($_POST['bventactraM']==3)
        {
            $this->salida .= "      <input type=\"radio\" name=\"bventactraM\" value=3 checked onclick=\"listamanual(this.form,this.value)\">";
        }
        else
        {
            $this->salida .= "      <input type=\"radio\" name=\"bventactraM\" value=3 onclick=\"listamanual(this.form,this.value)\">";
        }
        $this->salida .= "          </td>";
				$this->salida .= "          </tr>";
				$this->salida .= "          <tr align=\"center\">";
				$this->salida .= "          <td width=\"20%\">";
				$this->salida .= "      <select name=\"listaprecios\" class=\"select\">";
				$this->salida .= "      <option value=\"0000\">LISTA DE PRECIOS POR DEFECTO</option>";
				$listaprecios=$this->TraerListaPrecios();
				
/*				for($i=0;$i<sizeof($listaprecios);$i++)
				{
					if($listaprecios[$i]['codigo_lista']==$_POST['listaprecios'] AND $listaprecios[$i]['codigo_lista']!='000')
					{ 
							$this->salida .="<option value=\"".$listaprecios[$i]['codigo_lista']."\" selected>".$listaprecios[$i]['descripcion']."</option>";
					}
					else
					if($listaprecios[$i]['codigo_lista']=='000')
					{ 
							$this->salida .="<option value=\"".$listaprecios[$i]['codigo_lista']."\" selected>".$listaprecios[$i]['descripcion']."</option>";
					}
					else
					if($listaprecios[$i]['codigo_lista']!='000')
					{
							$this->salida .="<option value=\"".$listaprecios[$i]['codigo_lista']."\">".$listaprecios[$i]['descripcion']."</option>";
					}
				}*/
				for($i=0;$i<sizeof($listaprecios);$i++)
				{
					if($listaprecios[$i]['codigo_lista']==$_POST['listaprecios'])
					{ 
							$this->salida .="<option value=\"".$listaprecios[$i]['codigo_lista']."\" selected>".$listaprecios[$i]['descripcion']."</option>";
					}
					else
					{
							$this->salida .="<option value=\"".$listaprecios[$i]['codigo_lista']."\">".$listaprecios[$i]['descripcion']."</option>";
					}
				}
				$this->salida .= "      </select>";
				$this->salida .= "          </td>";
				$this->salida .= "          <td width=\"20%\">&nbsp;";
				$this->salida .= "          </td>";
				$this->salida .= "          </tr>";
				$this->salida .= "          <tr align=\"center\">";
				$this->salida .= "          <td width=\"100%\" class=\"label\">";
				$this->salida .= "PORCENTAJE POR DEFECTO";
				$this->salida .= "          </td>";
				$this->salida .= "          <td width=\"100%\" class=\"label\">&nbsp;";
				$this->salida .= "          </td>";
				$this->salida .= "          </tr>";
				$this->salida .= "          <tr align=\"center\" colspan=\"2\">";
				$this->salida .= "      <td width=\"50%\">";
				$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"porcentaje\" value=\"".$_POST['porcentaje']."\" maxlength=\"10\" size=\"10\">%";
				$this->salida .= "      </td>";
				$this->salida .= "          <td width=\"20%\">&nbsp;";
				$this->salida .= "          </td>";
				$this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $accion=ModuloGetURL('app','Contratacion','user','ClientePlanContra');
        $this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    /**
    * Funcion donde se muestra la informacion del contrato
    *
    * @return boolean
    */
    function MostrarDatosContra()//Vuelve a la función de donde fue llamada
    {
      $accion=ModuloGetURL('app','Contratacion','user','ClientePlanContra');
      $planeleg=$this->MostrarEmpresasPlanes($_SESSION['ctrpla']['planeleg']);
      $stl = "style=\"padding:4px;text-align:left\" class=\"formulacion_table_list\" ";
      $this->salida  = "<script>\n";
      $this->salida .= "  function Protocolo(valor)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    window.open('protocolos/'+valor,'PROTOCOLO','');";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeAbrirTabla('CONTRATACIÓN - DATOS DEL PLAN CLIENTE');
      $this->salida .= "<form name=\"contratacion\" action=\"$accion\" method=\"post\">\n";
      $this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td>\n";
      $this->salida .= "        <fieldset class=\"fieldset\">\n";
      $this->salida .= "          <legend class=\"field\">INFORMACIÓN DEL PLAN</legend>\n";
      $this->salida .= "            <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "              <tr class=modulo_list_claro>\n";
      $this->salida .= "                <td ".$stl." width=\"45%\">NOMBRE DE LA EMPRESA</td>\n";
      $this->salida .= "                <td width=\"55%\">".$_SESSION['contra']['razonso']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td  ".$stl.">NÚMERO DEL CONTRATO</td>\n";
      $this->salida .= "                <td>".$planeleg['num_contrato']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">DESCRIPCIÓN DEL CONTRATO</td>\n";
      $this->salida .= "                <td>".$planeleg['plan_descripcion']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">TIPO PLAN</td>\n";
      $this->salida .= "                <td>".$planeleg['descripcion']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">IDENTIFICADOR PLAN (SISTEMA)</td>\n";
      $this->salida .= "                <td>".$_SESSION['ctrpla']['planeleg']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">CLIENTE</td>";
      $this->salida .= "                <td>".$_SESSION['ctrpla']['nombeleg']."</td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">IDENTIFICACIÓN</td>\n";
      $this->salida .= "                <td>".$planeleg['tipo_tercero_id']." ".$planeleg['tercero_id']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">SERVICIOS CONTRATADOS</td>\n";
      $this->salida .= "                <td>\n";
      $this->salida .= "                  ".((!empty($planeleg['servicios_contratados']))? $planeleg['servicios_contratados']:"'NO SE ENCONTRÓ INFORMACIÓN'" )."\n";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">CONTACTO(S)</td>\n";
      $this->salida .= "                <td>".$planeleg['contacto']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">ENCARGADO(S)</td>\n";
      $this->salida .= "                <td>".$planeleg['nombre']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>\n";
      $this->salida .= "                <td ".$stl.">URL PROTÓCOLOS</td>\n";
      $this->salida .= "                <td>\n";
      if(!empty($planeleg['protocolos']))
      {
        if(file_exists("protocolos/".$planeleg['protocolos'].""))
        {
          $Protocolo = $planeleg['protocolos'];
          $this->salida .= "<a href=\"javascript:Protocolo('".$Protocolo."')\">".$Protocolo."</a>\n";
        }
        else
          $this->salida .= "NO SE ENCONTRÓ EL ARCHIVO";
      }
      else
        $this->salida .= "'NO SE ENCONTRÓ INFORMACIÓN'";
      
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">FECHA INICIAL (dd/mm/aaaa)</td>";
      $this->salida .= "                <td>\n";
      $fecini=explode('-',$planeleg['fecha_inicio']);
      $this->salida .= "                  ".$fecini[2]."".'/'."".$fecini[1]."".'/'."".$fecini[0]."";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">FECHA FINAL (dd/mm/aaaa)</td>";
      $this->salida .= "                <td>\n";
      $fecini=explode('-',$planeleg['fecha_final']);
      $this->salida .= "                  ".$fecini[2]."".'/'."".$fecini[1]."".'/'."".$fecini[0]."";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>\n";
      $this->salida .= "                <td ".$stl.">VALOR DEL CONTRATO</td>";
      $this->salida .= "                <td>";
      $this->salida .= "                  ".FormatoValor($planeleg['monto_contrato'])."";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">SALDO DEL CONTRATO</td>\n";
      $this->salida .= "                <td>";
      $this->salida .= "                  ".FormatoValor($planeleg['saldo_contrato'])."";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">VALOR MENSUAL DEL CONTRATO</td>\n";
      $this->salida .= "                <td>";
      $this->salida .= "                  ".FormatoValor($planeleg['monto_contrato_mensual'])."";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">VALOR MÁXIMO POR FACTURA</td>\n";
      $this->salida .= "                <td>";
      $this->salida .= "                  ".FormatoValor($planeleg['tope_maximo_factura'])."";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">DIAS CRÉDITO CARTERA</td>\n";
      $this->salida .= "                <td>".$planeleg['dias_credito_cartera']."</td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">EXCEDER VALOR MENSUAL DEL CONTRATO</td>\n";
      $this->salida .= "                <td>\n";
      $this->salida .= "                  ".(($planeleg['sw_exceder_monto_mensual']==1)? "SI":"NO")."\n";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">TIPO CLIENTE</td>\n";
      $this->salida .= "                <td>\n";
      $this->salida .= "                  ".((!empty($planeleg['descripcion2']))? $planeleg['descripcion2'] :"'NO SE ENCONTRÓ INFORMACIÓN'")."\n";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">MANEJA LIQUIDACIÓN DE LA HABITACIÓN</td>\n";
      $this->salida .= "                <td>\n";
      if ($planeleg['sw_contrata_hospitalizacion']==1)
        $this->salida .= "SI";
      else if ($planeleg['sw_contrata_hospitalizacion']==0)
        $this->salida .= "NO";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">LIQUIDACIÓN DE SEMANAS<br>PARA DÍAS DE CARENCIA</td>\n";
      $this->salida .= "                <td>".$planeleg['descripcion3']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">LIQUIDACIÓN DE CARGOS</td>";
      $this->salida .= "                <td>".$planeleg['descripcion4']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">TIPO PARAGRAFADOS DE<br>INSUMOS Y MEDICAMENTOS</td>\n";
      $this->salida .= "                <td>".$planeleg['descripcion5']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">PERMITIR AUTORIZACIÓN SIN BASE DE DATOS</td>\n";
      $this->salida .= "                <td>".(($planeleg['sw_autoriza_sin_bd']==1)? "SI":"NO")."</td>\n";
      $chk1[0] = "NO";
      $chk1[1] = "SI";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                <td ".$stl.">SOLICITUD AUTORIZACION</td>\n";
      $this->salida .= "                <td>".$chk1[$planeleg['sw_solicita_autorizacion_admision']]."</td>\n";
      $this->salida .= "              </tr>\n";
      $afiliados[0]['detalle'] = "NO MANEJA AFILIADOS";
      $afiliados[1]['detalle'] = "MANEJA AFILIADOS";
      $afiliados[2]['detalle'] = "ATENCION PARTICULAR AFILIADOS";
      
      $this->salida .= "              <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                <td ".$stl.">AFILIADOS</td>\n";
      $this->salida .= "                <td>".$afiliados[$planeleg['sw_afiliados']]['detalle']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">MANEJO DE BASE DE DATOS DE AFILIADOS</td>\n";
      $this->salida .= "                <td>".(($planeleg['sw_afiliacion']==1)? "SI":"NO")."</td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>";
      $this->salida .= "                <td ".$stl.">FACTURACIÓN AGRUPADA</td>\n";
      $this->salida .= "                <td>".(($planeleg['sw_facturacion_agrupada']==1)? "SI":"NO")."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>\n";
      $this->salida .= "                <td ".$stl.">PARAGRAFADOS INSUMOS Y MEDICAMENTOS</td>\n";
      $this->salida .= "                <td>".(($planeleg['sw_paragrafados_imd']==1)? "SI":"NO")."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>\n";
      $this->salida .= "                <td ".$stl.">PARAGRAFADOS CARGOS DIRECTOS</td>\n";
      $this->salida .= "                <td>".(($planeleg['sw_paragrafados_cd']==1)? "SI":"NO")."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_oscuro>";
      $this->salida .= "                <td ".$stl.">NOMBRE DEL COPAGO</td>\n";
      $this->salida .= "                <td>".$planeleg['nombre_copago']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=modulo_list_claro>\n";
      $this->salida .= "                <td ".$stl.">NOMBRE DE LA CUOTA MODERADORA</td>\n";
      $this->salida .= "                <td>".$planeleg['nombre_cuota_moderadora']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                <td ".$stl.">MESES PARA CONSULTAR EN LA BD</td>\n";
      $this->salida .= "                <td>".$planeleg['meses_consulta_base_datos']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                <td ".$stl.">MOSTRAR LOS ÚLTIMOS DÍAS DE INCUMPLIMIENTO</td>\n";
      $this->salida .= "                <td>".$planeleg['actividad_incumplimientos']."</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                <td ".$stl.">HORAS PREVIAS PARA CANCELAR CITA</td>";
      $this->salida .= "                <td>".$planeleg['horas_cancelacion']."</td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                <td ".$stl.">LÍNEAS PARA CANCELACIÓN DE CITA</td>";
      $this->salida .= "                <td>".$planeleg['telefono_cancelacion_cita']."</td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                <td ".$stl.">LÍNEAS DE ATENCIÓN - AUTORIZACIONES</td>";
      $this->salida .= "                <td>".$planeleg['lineas_atencion']."</td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                <td ".$stl.">OBSERVACIÓN</td>";
      $this->salida .= "                <td>";
      $this->salida .= "                  ".((!empty($planeleg['observacion']))? $planeleg['observacion']: "'NO SE ENCONTRÓ INFORMACIÓN'")."\n";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $servicios=$this->BuscarServiciosContra();
      $serveleg=$this->MostrarServiciosPlanes($_SESSION['ctrpla']['planeleg']);
      $this->salida .= "              <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                <td ".$stl.">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
      $this->salida .= "                <td align=\"left\">\n";
      $this->salida .= "                  <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">\n";
      for($i=0;$i<sizeof($servicios);$i++)
      {
        if($serveleg[$servicios[$i]['servicio']]==1)
        {
          $this->salida .= "                    <tr>\n";
          $this->salida .= "                      <td>".$servicios[$i]['descripcion']."</td>\n";
          $this->salida .= "                    </tr>\n";
        }
      }
      $this->salida .= "                  </table>\n";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                <td ".$stl.">BASE PARA LA LIQUIDACIÓN DE<br>INSUMOS Y MEDICAMENTOS</td>";
      $this->salida .= "                <td>";
      if($planeleg['sw_base_liquidacion_imd']==1)
      {
        $this->salida .= "COSTO PROMEDIO";
      }
      else if($planeleg['sw_base_liquidacion_imd']==2)
      {
          $this->salida .= "COSTO ÚLTIMA COMPRA";
      }
      else if($planeleg['sw_base_liquidacion_imd']==3)
      {
        $descripcionlista=$this->TraerDescripcionLista($planeleg['lista_precios']);
        $this->salida .= "LISTA DE VENTA - ".$descripcionlista[descripcion];
      }
      else if($planeleg['sw_base_liquidacion_imd']==1 OR $planeleg['sw_base_liquidacion_imd']==2)
      {
        $this->salida .= "LISTA DE VENTA";
      }
      $this->salida .= "                </td>";
      $this->salida .= "              </tr>\n";
      $this->salida .= "              <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                <td ".$stl.">PORCENTAJE UTILIDAD</td>";
      $this->salida .= "                <td>".FormatoValor($planeleg['porcentaje_utilidad'])."%</td>\n";
      $this->salida .= "              </tr>\n";
      $this->salida .= "            </table>\n";
      $this->salida .= "        </fieldset>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table><br>";
      $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"><br>";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= "</form>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }

    //Función que crea las matrices de los rangos con sus respectivos valores
    function VerRangosPlan()//Valida los datos de los rangos
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - RANGOS DEL PLAN CLIENTE');
        $accion=ModuloGetURL('app','Contratacion','user','ModificarRangosContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">RANGOS Y AFILIADOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $_SESSION['ctrpla']['rangosM']=$this->BuscarRangosPlan($_SESSION['ctrpla']['planeleg']);
        $_SESSION['ctrpla']['afiliaM']=$this->BuscarTipoAfiliadoContra();
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpla']['rangosM']);
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                $color="class=modulo_list_claro";
                $j=1;
            }
            else
            {
                $color="class=modulo_list_oscuro";
                $j=0;
            }
            $this->salida .= "      <tr $color>";
            $this->salida .= "      <td width=\"20%\" align=\"center\">";
            $this->salida .= "      <label class=\"label\">RANGO"." -> </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nomranctraM".$i."\" value=\"".$_SESSION['ctrpla']['rangosM'][$i]['rango']."\" maxlength=\"40\" size=\"23\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"80%\" align=\"center\">";
            $this->salida .= "          <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
            $this->salida .= "          <td align=\"center\" width=\"20%\">";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">CUOTA MODERADORA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO (%)";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÁXIMO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÍNIMO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÁXIMO AÑO";
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $l=$i;
            $ciclo1=sizeof($_SESSION['ctrpla']['afiliaM']);
            for($k=0;$k<$ciclo1;$k++)
            {
                if($_SESSION['ctrpla']['rangosM'][$l]['tipo_afiliado_id']==$_SESSION['ctrpla']['afiliaM'][$k]['tipo_afiliado_id'])//Llaves
                {
                    $_POST['cuotamodM'.$l.$k]=$_SESSION['ctrpla']['rangosM'][$l]['cuota_moderadora'];
                    $_POST['copagoporM'.$l.$k]=$_SESSION['ctrpla']['rangosM'][$l]['copago'];
                    $_POST['copagomaxM'.$l.$k]=$_SESSION['ctrpla']['rangosM'][$l]['copago_maximo'];
                    $_POST['copagominM'.$l.$k]=$_SESSION['ctrpla']['rangosM'][$l]['copago_minimo'];
                    $_POST['copagoanoM'.$l.$k]=$_SESSION['ctrpla']['rangosM'][$l]['copago_maximo_ano'];
                    $this->salida .= "      <tr class=modulo_list_claro>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      ".strtoupper($_SESSION['ctrpla']['afiliaM'][$k]['tipo_afiliado_nombre'])."";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cuotamodM".$l.$k."\" value=\"".$_POST['cuotamodM'.$l.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagoporM".$l.$k."\" value=\"".$_POST['copagoporM'.$l.$k]."\" maxlength=\"8\" size=\"8\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagomaxM".$l.$k."\" value=\"".$_POST['copagomaxM'.$l.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagominM".$l.$k."\" value=\"".$_POST['copagominM'.$l.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagoanoM".$l.$k."\" value=\"".$_POST['copagoanoM'.$l.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      </tr>";
                    $l++;
                }
            }
            $this->salida .= "         </table><br>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $i=$l;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"33%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"34%\">";
        $accion=ModuloGetURL('app','Contratacion','user','MatrizRangosPlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $var=$this->CambiarRangosPlan($_SESSION['ctrpla']['planeleg']);
        if($var==0)
        {
            $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"modificar\" value=\"MODIFICAR\">";
        }
        else
        {
            $this->salida .= "  <input disabled=\"true\" class=\"input-submit\" type=\"submit\" name=\"modificar\" value=\"MODIFICAR\">";
        }
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"33%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClientePlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"MENÚ 1\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que permite rediseñar la matriz de los rangos de un plan
    function MatrizRangosPlanContra()//Válida los parámetros de los rangos de un plan que se pueden cambiar
    {
        UNSET($_SESSION['ctrpla']['afiliado2']);
        UNSET($_SESSION['ctrpla']['rangospl2']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - RANGOS DEL PLAN CLIENTE');
        $accion=ModuloGetURL('app','Contratacion','user','MatrizRangoContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">RANGOS Y AFILIADOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" class=\"modulo_table_list_title\">";
        $this->salida .= "SELECCIONE LOS TIPOS DE AFILIACIÓN PARA INGRESAR LOS VALORES";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"50%\" align=\"center\" class=\"".$this->SetStyle("rangctra2")."\">";
        $this->salida .= "NÚMERO DE RANGOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\" align=\"center\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"rangctra2\" value=\"".$_POST['rangctra2']."\" maxlength=\"2\" size=\"4\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"50%\" align=\"center\" class=\"".$this->SetStyle("afilia2")."\">";
        $this->salida .= "      <input type=\"hidden\" name=\"afilia2\" class=\"input-text\">";
        $this->salida .= "TIPO DE AFILIACIÓN";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\" align=\"center\">";
        $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
        $ciclo=sizeof($_SESSION['ctrpla']['afiliaM']);
        for($i=0;$i<$ciclo;$i++)
        {
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"center\" width=\"70%\">";
            $this->salida .= "          ".strtoupper($_SESSION['ctrpla']['afiliaM'][$i]['tipo_afiliado_nombre'])."";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"30%\">";
            if($_POST['afiliados2'.$i]==NULL)
            {
                $this->salida .= "          <input type=\"checkbox\" name=\"afiliados2".$i."\" value=\"".$_SESSION['ctrpla']['afiliaM'][$i]['tipo_afiliado_id']."\">";
            }
            else
            {
                $this->salida .= "          <input type=\"checkbox\" name=\"afiliados2".$i."\" value=\"".$_SESSION['ctrpla']['afiliaM'][$i]['tipo_afiliado_id']."\" checked>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
        }
        $this->salida .= "         </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"INGRESAR LOS VALORES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClientePlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR LOS CAMBIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que crea las matrices de los rangos con sus respectivos valores
    function ValoresRangosContra2()//Valida los datos de los rangos
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - RANGOS DEL PLAN CLIENTE');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarDatosRangosContra2');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">RANGOS Y AFILIADOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $j=0;
        $ciclo=$_SESSION['ctrpla']['rangospl2'];
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=modulo_list_claro";
                $j=1;
            }
            else
            {
                $color="class=modulo_list_oscuro";
                $j=0;
            }
            $this->salida .= "      <tr $color>";
            $this->salida .= "      <td width=\"20%\" align=\"center\">";
            $this->salida .= "      <label class=\"".$this->SetStyle("nomranctra2".$i)."\">RANGO"." -> </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nomranctra2".$i."\" value=\"".$_POST['nomranctra2'.$i]."\" maxlength=\"40\" size=\"23\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"80%\" align=\"center\">";
            $this->salida .= "          <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
            $this->salida .= "          <td align=\"center\" width=\"20%\">";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">CUOTA MODERADORA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO (%)";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÁXIMO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÍNIMO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"16%\">COPAGO MÁXIMO AÑO";
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $ciclo1=sizeof($_SESSION['ctrpla']['afiliaM']);
            for($k=0;$k<$ciclo1;$k++)
            {
                if(!($_SESSION['ctrpla']['afiliado2'][$k]==NULL))//Llaves
                {
                    if($_POST['cuotamod2'.$i.$k]==NULL)
                    {
                        $_POST['cuotamod2'.$i.$k]='0.00';
                    }
                    if($_POST['copagopor2'.$i.$k]==NULL)
                    {
                        $_POST['copagopor2'.$i.$k]='0.00';
                    }
                    if($_POST['copagomax2'.$i.$k]==NULL)
                    {
                        $_POST['copagomax2'.$i.$k]='0.00';
                    }
                    if($_POST['copagomin2'.$i.$k]==NULL)
                    {
                        $_POST['copagomin2'.$i.$k]='0.00';
                    }
                    if($_POST['copagoano2'.$i.$k]==NULL)
                    {
                        $_POST['copagoano2'.$i.$k]='0.00';
                    }
                    $this->salida .= "      <tr class=modulo_list_claro>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      ".strtoupper($_SESSION['ctrpla']['afiliaM'][$k]['tipo_afiliado_nombre'])."";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cuotamod2".$i.$k."\" value=\"".$_POST['cuotamod2'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagopor2".$i.$k."\" value=\"".$_POST['copagopor2'.$i.$k]."\" maxlength=\"8\" size=\"8\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagomax2".$i.$k."\" value=\"".$_POST['copagomax2'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagomin2".$i.$k."\" value=\"".$_POST['copagomin2'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"copagoano2".$i.$k."\" value=\"".$_POST['copagoano2'.$i.$k]."\" maxlength=\"10\" size=\"10\">";
                    $this->salida .= "      </td>";
                    $this->salida .= "      </tr>";
                }
            }
            $this->salida .= "         </table><br>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR LOS VALORES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','MatrizRangosPlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER  A  LOS RANGOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function RetornarBarraClientes($estadobarra)//Barra paginadora de los planes clientes
    { 
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','EmpresasContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri'],'estadobarra'=>$estadobarra));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraTaCaCo()//Barra paginadora de los cargos del grupo y subgrupo
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','ConsulCargosTarifarioContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra'],'tarifactra'=>$_REQUEST['tarifactra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraTaCli()//Barra paginadora del plan Tarifario Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','TariExcePlanContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraCoCli()//Barra paginadora de Copagos Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','CopagosExcePlanContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraSeCli()//Barra paginadora de Semanas cotizadas Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','SemanasExcePlanContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraAiCli()//Barra paginadora de Autorización interna Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','AutoInteExPlanContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraAeCli()//Barra paginadora de Autorización externa Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','AutoExteExPlanContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraTiaCli()//Barra paginadora de Tarifario inventario Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','TariExceAutoInveContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraTicCli()//Barra paginadora de Tarifario inventario Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','TariExceCopaInveContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraPmCli()//Barra paginadora de Paragrafados insumos y medicamentos Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','ModificarImdInveContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraPcCli()//Barra paginadora de Paragrafados cargos directos Cliente
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','ModificarCadInveContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    function RetornarBarraImcarg()//Barra paginadora de los Incumplimientos de los cargos
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Contratacion','user','IncumplimientoContra',array('conteo'=>$this->conteo,
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
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
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
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

    //Función que permite realizar mantenimiento sobre el plan elegido
    function ClienteCargosContra($cargos)//Opciones del plan
    { 
        if(empty($_SESSION['ctrpla']['planeleg']))
        {
            $_SESSION['ctrpla']['planeleg']=$_REQUEST['planelegc'];
            $_SESSION['ctrpla']['desceleg']=$_REQUEST['descelegc'];
            $_SESSION['ctrpla']['numeeleg']=$_REQUEST['numeelegc'];
            $_SESSION['ctrpla']['nombeleg']=$_REQUEST['nombelegc'];//nombre del cliente - tercero
            $_SESSION['ctrpla']['tidteleg']=$_REQUEST['tipoidter'];
            $_SESSION['ctrpla']['terceleg']=$_REQUEST['terceroid'];
            $_SESSION['ctrpla']['estaeleg']=$_REQUEST['estado'];
            $_SESSION['ctrpla']['pimdeleg']=$_REQUEST['paragraimd'];
            $_SESSION['ctrpla']['pcadeleg']=$_REQUEST['paragracd'];
            $_SESSION['ctrpla']['tpmdeleg']=$_REQUEST['tipparimd'];
            $_SESSION['ctrpla']['estado']=$_REQUEST['estado'];
            $_SESSION['habitaciones']=$_REQUEST['manejahab'];
        }
        UNSET($_SESSION['ctrpl1']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - CARGOS');
        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','EmpresasContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">OPCIONES DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" class=\"modulo_table_list_title\">";
        $this->salida .= "MENÚ 2";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" align=\"left\" class=\"label\">";
        $this->salida .= "TARIFARIOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/ptarifario.png\" border=\"0\" title=\"TARIFARIOS\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" align=\"left\" class=\"label\">";
        $this->salida .= "TARIFARIOS (OPCIÓN RÁPIDA)";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioGrupoContraRapida') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/ptarifario.png\" border=\"0\" title=\"TARIFARIOS (OPCIÓN RAPIDA)\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "COPAGOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','CopagosPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/pcopagos.png\" border=\"0\" title=\"COPAGOS\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "SEMANAS PARA DÍAS DE CARENCIA";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','SemanasPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/psemanas.png\" border=\"0\" title=\"SEMANAS PARA DÍAS DE CARENCIA\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "AUTORIZACIONES POR SERVICIOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutorizaPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/pautorizacion.png\" border=\"0\" title=\"AUTORIZACIONES POR SERVICIOS\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "INSUMOS Y MEDICAMENTOS - AUTORIZACIONES";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioSerAutoInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/pinveauto.png\" border=\"0\" title=\"INSUMOS Y MEDICAMENTOS - AUTORIZACIONES\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "INSUMOS Y MEDICAMENTOS - COPAGOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioSerCopaInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/pinvecopa.png\" border=\"0\" title=\"INSUMOS Y MEDICAMENTOS - COPAGOS\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "PARAGRAFADOS - INSUMOS Y MEDICAMENTOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        if($_SESSION['ctrpla']['pimdeleg']==1 AND $_SESSION['ctrpla']['tpmdeleg']==0)
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParaTipoImdInveContra') ."\">";
            $this->salida .= "<img src=\"".GetThemePath()."/images/pparamed.png\" border=\"0\" title=\"PARAGRAFADOS - INSUMOS Y MEDICAMENTOS\"></a>";
        }
        else if($_SESSION['ctrpla']['pimdeleg']==1 AND $_SESSION['ctrpla']['tpmdeleg']>0)
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParaGeneImdInveContra') ."\">";
            $this->salida .= "<img src=\"".GetThemePath()."/images/pparamed.png\" border=\"0\" title=\"PARAGRAFADOS - INSUMOS Y MEDICAMENTOS\"></a>";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/pparamedin.png\" border=\"0\" title=\"PARAGRAFADOS - INSUMOS Y MEDICAMENTOS\">";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "PARAGRAFADOS - CARGOS DIRECTOS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        if($_SESSION['ctrpla']['pcadeleg']==1)
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParagraCadInveContra') ."\">";
            $this->salida .= "<img src=\"".GetThemePath()."/images/pparacar.png\" border=\"0\" title=\"PARAGRAFADOS - CARGOS DIRECTOS\"></a>";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/pparacarin.png\" border=\"0\" title=\"PARAGRAFADOS - CARGOS DIRECTOS\">";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"left\" class=\"label\">";
        $this->salida .= "INCUMPLIMIENTO DE CITAS";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','IncumplimientoContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/cumplimientoin_citas.png\" border=\"0\" title=\"INCUMPLIMIENTO DE CITAS\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
				//SI EL CONTRATO MANEJA PARAMETROS DE HABITACIONES
				if($_SESSION['habitaciones']==1)
				{
					$this->salida .= "      <tr class=modulo_list_claro>";
					$this->salida .= "      <td align=\"left\" class=\"label\">";
					$this->salida .= "PARAMETRIZACIÓN DE HABITACIONES";
					$this->salida .= "      </td>";
					$this->salida .= "      <td align=\"center\">";
					$this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParametrizacionHabitaciones') ."\">";
					$this->salida .= "<img src=\"".GetThemePath()."/images/cama.png\" border=\"0\" title=\"PARAMETRIZACIÓN DE HABITACIONES\"></a>";
					$this->salida .= "      </td>";
					$this->salida .= "      </tr>";
				}
				//FIN PARA CONTRATO QUE MANEJA PARAMETROS DE HABITACIONES
				//PROTOCOLOS HABITACIONES
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td align=\"left\" class=\"label\">";
				$this->salida .= "PROTOCOLOS HABITACIONES";
				$this->salida .= "      </td>";
				$this->salida .= "      <td align=\"center\">";
				$this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','MostrarFormaProtocoloInternacion') ."\">";
				$this->salida .= "<img src=\"".GetThemePath()."/images/pcargos.png\" border=\"0\" title=\"PROTOCOLOS DE LA PARAMETRIZACIÓN DE HABITACIONES\"></a>";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				//FIN PROTOCOLOS HABITACIONES
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"70%\" class=\"label\">";
        $this->salida .= "IR AL MENÚ 1";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\" align=\"center\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClientePlanContra') ."\">";
        $this->salida .= "<img title=\"CONTRATO\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"100%\">";
        $accion=ModuloGetURL('app','Contratacion','user','EmpresasContra',array('estadogrupo'=>$_SESSION['ctrpla']['estado'],'tarifa'=>'tarifa'));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TarifarioGrupoContraRapida($cargos)//
    {
        UNSET($_SESSION['ctrpl1']['grutaplanc']);
        UNSET($_SESSION['ctrpl1']['dattarctra']);
        UNSET($_SESSION['ctrpl1']['cargotaric']);
        UNSET($_SESSION['ctrpl1']['dacacoctra']);
        UNSET($_SESSION['ctrpl1']['carconctra']);
        $_SESSION['ctrpl1']['marcapltra']=1;
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - GRUPOS Y SUBGRUPOS DEL PLAN TARIFARIO CLIENTE (OPCIÓN RÁPIDA)');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">TARIFARIOS POR GRUPOS Y SUBGRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table  border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        //********************************************************
				//Buscador de cargos
				$js = "<SCRIPT>";
				$js .= "function ver(){";
				$js .= " if (document.getElementById('cargos').style.display == \"none\"){";
				$js .= "  document.getElementById('cargos').style.display = \"block\";";
				$js .= " }else{";
				$js .= "  document.getElementById('cargos').style.display = \"none\";";
				$js .= " }";
				$js .= "}";
				$action = ModuloGetUrl("app","Contratacion","user","BuscarCargoGrupoSubgrupo",array('plan_id'=>$_SESSION['ctrpla']['planeleg']));
				$js .= "function accion(frm){";
				//$js .= "alert (document.getElementById('codigo').value);";
				$js .= "if(document.getElementById('codigo').value != ''){";
				$js .= " codigo = document.getElementById('codigo').value;";
				$js .= " url = '".$action."&codigo='+codigo+'';";
				$js .= " }";
				$js .= "if(document.getElementById('descripcion').value != ''){";
				$js .= " descripcion = document.getElementById('descripcion').value;";
				$js .= " url = '".$action."&descripcion='+descripcion+'';";
				$js .= " }";
				//$js .= " frm.action = url;";
				//$js .= " frm.submit();";
				//$js .= "alert (url);";
				$js .= "  document.getElementById('cargos').style.display = \"block\";";
				$js .= "  document.getElementById('frmbuscar').action = url;";
				$js .= "  document.getElementById('frmbuscar').submit();";
				$js .= "}";
				$js .= "</SCRIPT>";
				$this->salida .= $js;
				$style="none";
				if(is_array($cargos))
				{
					$style = "block";
				}
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"100%\" align=\"center\" colspan=\"2\">";
				$this->salida .= "<a href=\"javascript:ver();\" title=\"OBTENER GRUPO Y SUBGRUPO DE CARGOS\">";
				$this->salida .= "OBTENER GRUPO Y SUBGRUPO DE CARGOS</a>";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"70%\" align=\"left\" class=\"label\" colspan=\"2\">";
				$this->salida .= "<div id='cargos' style=\"display:$style\">";
				$this->salida .= "<form name=\"frmbuscar\" id=\"frmbuscar\" method=\"post\">";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CODIGO:";
				$this->salida .= "      </td>";
				$this->salida .= "      <td align=\"center\" width=\"70%\">";
				$this->salida .= "      <input type=\"text\" name=\"codigo\" id=\"codigo\" size =\"10\" maxlength=\"10\" value=\"$_REQUEST[codigo]\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
				$this->salida .= "      </td>";
				$this->salida .= "      <td align=\"center\" width=\"70%\">";
				$this->salida .= "      <input type=\"text\" name=\"descripcion\" id=\"descripcion\" size =\"30\" maxlength=\"30\" value=\"$_REQUEST[descripcion]\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=modulo_list_claro>";
				$this->salida .= "      <td width=\"100%\" colspan=\"2\" align=\"center\"";
				$this->salida .= "	 <a href=\"javascript:accion(this.form);\">Buscar</a>";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				if(is_array($cargos))
				{
					$this->salida .= "      <tr class=modulo_list_claro>";
					$this->salida .= "      <td width=\"100%\" colspan=\"2\" align=\"center\">";
					$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "      <tr class=modulo_table_title>";
					$this->salida .= "      <td width=\"8%\" align=\"center\">";
					$this->salida .= "grupo_descrip";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"8%\" align=\"center\">";
					$this->salida .= "subgrupo_descrip";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"8%\" align=\"center\">";
					$this->salida .= "cargo";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"38%\" align=\"center\">";
					$this->salida .= "descripcion";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"8%\" align=\"center\">";
					$this->salida .= "precio_tari";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"5%\" align=\"center\">";
					$this->salida .= "%";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"8%\" align=\"center\">";
					$this->salida .= "precio_pactado";
					$this->salida .= "      </td>";
/*					$this->salida .= "      <td width=\"20%\" align=\"center\">";
					$this->salida .= "	 descripcion";
					$this->salida .= "      </td>";*/
					$this->salida .= "      <td width=\"7%\" align=\"center\">";
					$this->salida .= "tarifario_descrip";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"5%\" align=\"center\">";
					$this->salida .= "% desc";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"5%\" align=\"center\">";
					$this->salida .= "no_cont";
					$this->salida .= "      </td>";
					$this->salida .= "      </tr>";
					foreach($cargos AS $i => $v)
					{//grupo_tarifario_descripcion	cargo	descripcion	tarifario_id	destarifario
						if($i%2)
						{$estilo='modulo_list_claro';}
						else
						{$estilo='modulo_list_oscuro';}
						$_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id']=$v[grupo_tarifario_id];
						$_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_descripcion']=$v[grupo_tarifario_descripcion];
						//$accion = ModuloGetURL("app","Contratacion","user","TarifarioPlanContraRapida");
						//$accion2 = ModuloGetURL("app","Contratacion","user","TarifarioPlanContraRapida",array('subgrupo_tarifario_id'=>$v[subgrupo_tarifario_id]));
						$this->salida .= "      <tr class=$estilo>";
						$this->salida .= "      <td width=\"8%\" align=\"center\">";
						//$this->salida .= " <a href=\"$accion\">$v[grupo_tarifario_descripcion]</a>";
						$this->salida .= " <a >$v[grupo_tarifario_descripcion]</a>";
						$this->salida .= "      </td>";
						$this->salida .= "      <td width=\"8%\" align=\"center\">";
						//$this->salida .= " <a href=\"$accion2\">$v[subgrupo_tarifario_descripcion]</a>";
						$this->salida .= " <a title=\"$v[descripcion]\">$v[subgrupo_tarifario_descripcion]</a>";
						$this->salida .= "      </td>";
						$this->salida .= "      <td width=\"8%\" align=\"center\">";
						$this->salida .= " $v[cargo]";
						$this->salida .= "      </td>";
						$this->salida .= "      <td width=\"34%\" align=\"center\">";
						$this->salida .= " $v[descripcion]";
						$this->salida .= "      </td>";
						$this->salida .= "      <td width=\"8%\" align=\"center\">";
						$this->salida .= " $v[precio]";
						$this->salida .= "      </td>";
						$this->salida .= "      <td width=\"5%\" align=\"center\">";
						$this->salida .= " $v[porcentaje]";
						//$this->salida .= " $v[por_cobertura]";
						$this->salida .= "      </td>";
						//PRECIO PACTADO
						$this->salida .= "      <td width=\"8%\" align=\"center\">";
						if(!empty($v['porcentaje']))
						{ 
								if ($v['tipo_unidad_id']=="01")//PESOS
								{
									$val=$v['precio']+($v['porcentaje']*$v['precio']/100);
									$this->salida .=  "$&nbsp;".FormatoValor(round($val,1))."";
								}
								else
								if ($v['tipo_unidad_id']=="02")//UVR
								{
										$val2=$v['precio'];
										$this->salida .=  "<font color=\"#f87a17\"><B>".FormatoValor(round($val2,1))."</B></font>";
								}
								else
								if ($v['tipo_unidad_id']=="03")//SMMLV
								{
									$val3=($v['precio']*GetSalarioMinimo(date("Y")))*(1+$v['porcentaje']/100);
									$this->salida .=  "<Label class=label><font color=\"#151b7e\"><B>$&nbsp;".FormatoValor(round($val3,-2))."</B></font></label>";
								}
								else
								if ($v['tipo_unidad_id']=="04")//GQ - GRUPOS QUIRURJICOS
								{
									$val4=$v[$i]['precio'];
									$this->salida .=  "<Label class=label><font color=\"#151b7e\"><B>".FormatoValor(round($val4,1))."</B></font></label>";
								}
								else
								if($v['tipo_unidad_id']=="05")//05 UVRS	UNIDADES DE VALOR REAL PARA PAQUETES 
								{
									$val4=($v['precio']*(1+$v['porcentaje']/100))*100;
									$this->salida .=  "<Label class=label><font color=\"#151b7e\"><B>".FormatoValor($val4)."</B></font></label>";
								}
										
						}
						else
						{
							$this->salida .= "".FormatoValor(round($v['precio'],-2));
						}
						$this->salida .= "      </td>";
						//FIN PRECIO PACTADO
/*						$this->salida .= "      <td width=\"20%\" align=\"center\">";
						$this->salida .= " $v[descripcion]";
						$this->salida .= "      </td>";*/
						$this->salida .= "      <td width=\"7%\" align=\"center\">";
						$this->salida .= " $v[destarifario]";
						$this->salida .= "      </td>";
						$checked = "";
						if($v[sw_descuento])
						{
							$checked = "checked";
						}
						$this->salida .= "      <td width=\"5%\" align=\"center\">";
						$this->salida .= "        <input type=\"checkbox\" name=\"descuento$i\" disabled $checked>";
						$this->salida .= "      </td>";
						$checked = "";
						if($v[sw_no_contratado])
						{
							$checked = "checked";
						}						
						$this->salida .= "      <td width=\"5%\" align=\"center\">";
						$this->salida .= "        <input type=\"checkbox\" name=\"nocontratado$i\" disabled $checked>";
						$this->salida .= "      </td>";
						$this->salida .= "      </tr>";
					}
					$this->salida .= "      </table><br>";
					$this->salida .= "      </td>";
					$this->salida .= "      </tr>";
				}
				$this->salida .= "      </table><br>";
				$this->salida .= "</form>";
				$this->salida .= "</div>";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      </table><br>";
				//Fin Buscador de cargos				
				//********************************************************
				$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"10%\">IR</td>";
        $this->salida .= "      <td width=\"40%\">GRUPOS TARIFARIOS</td>";
        $this->salida .= "      <td width=\"50%\">SUBGRUPOS TARIFARIOS</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['grutaplanc']=$this->BuscarGruposPlanContra($_SESSION['ctrpla']['planeleg']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['grutaplanc']);
        for($i=0;$i<$ciclo;)//$ciclo
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida',
            array('indiragrsupl'=>$i)) ."\"><img title=\"TARIFARIOS\" src=\"".GetThemePath()."/images/ptarifario.png\" border=\"0\"></a>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id'])
            {
                //$tarifarios=$this->BuscarTarifarioPlanContra($_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id'],
                //$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']);
                $this->salida .= "      <tr>";
                $this->salida .= "      <td height=\"30\" width=\"70%\">";
                $this->salida .= "".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_descripcion']."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $k++;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $i=$k;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"100%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TarifarioPlanContraRapida()//
    {
        if($_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id']==NULL)
        {
            $_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiragrsupl']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiragrsupl']]['grupo_tarifario_descripcion'];
            UNSET($_SESSION['ctrpl1']['grutaplanc']);
        }
        UNSET($_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_id']);
        UNSET($_SESSION['ctrpl1']['cargotaric']);
        UNSET($_SESSION['ctrpl1']['dacacoctra']);
        UNSET($_SESSION['ctrpl1']['carconctra']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLAN TARIFARIO CLIENTE (OPCIÓN RÁPIDA)');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarTarifarioPlanContraRapida');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioGrupoContraRapida') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">TARIFARIOS POR GRUPOS Y SUBGRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table  border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">SUBGRUPOS TARIFARIOS</td>";
        $this->salida .= "      <td width=\"18%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"10%\">PORCE.</td>";
        $this->salida .= "      <td width=\"10%\">COBER.</td>";
        $this->salida .= "      <td width=\"4%\" >DES.</td>";
        $this->salida .= "      <td colspan=\"2\">DETALLES</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['grutaplanc']=$this->BuscarTarifarioPlanRapidaContra($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id'],$_REQUEST[subgrupo_tarifario_id]);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['grutaplanc']);
        for($k=0;$k<$ciclo;)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $grabar=$c=0;
            $l=$k;
            $a=$l;
            while($_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']==$_SESSION['ctrpl1']['grutaplanc'][$l]['subgrupo_tarifario_id'])
            {
                if($_SESSION['ctrpl1']['grutaplanc'][$l]['porcentaje']<>NULL)
                {
                    //
/*                    $grabar=$this->ModificarTariPlanContra($_SESSION['ctrpla']['planeleg'],
                    $_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id'],
                    $_SESSION['ctrpl1']['grutaplanc'][$l]['subgrupo_tarifario_id']);*/
                    //
                    $grabar = 0;
                    $a=$l;
                }
                $c++;
                $l++;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td height=\"30\">";
            $this->salida .= "".$_SESSION['ctrpl1']['grutaplanc'][$a]['subgrupo_tarifario_descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td height=\"30\" align=\"left\">";
            $l=$k;
            if($grabar==0)
            {
                $this->salida .= "  <select name=\"tarifplanc".$a."\" class=\"select\">";
                $this->salida .= "  <option value=\"\">----</option>";
                while($_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']==$_SESSION['ctrpl1']['grutaplanc'][$l]['subgrupo_tarifario_id'])
                {
                    if($_SESSION['ctrpl1']['grutaplanc'][$l]['porcentaje']<>NULL OR $_POST['taritodoct']==$_SESSION['ctrpl1']['grutaplanc'][$l]['tarifario_id'])
                    {
                        $this->salida .="<option value=\"".$_SESSION['ctrpl1']['grutaplanc'][$l]['tarifario_id']."\" selected>".$_SESSION['ctrpl1']['grutaplanc'][$l]['descripcion']."</option>";
                    }
                    else if($_SESSION['ctrpl1']['grutaplanc'][$l]['porcentaje']==NULL OR $_POST['taritodoct']==$_SESSION['ctrpl1']['grutaplanc'][$l]['tarifario_id'])
                    {
                        $this->salida .="<option value=\"".$_SESSION['ctrpl1']['grutaplanc'][$l]['tarifario_id']."\">".$_SESSION['ctrpl1']['grutaplanc'][$l]['descripcion']."</option>";
                    }
                    $l++;
                }
                $this->salida .= "  </select>";
            }
            else
            {
                if($_SESSION['ctrpl1']['grutaplanc'][$a]['porcentaje']<>NULL)
                {
                    $this->salida .="<input type=\"hidden\" name=\"tarifplanc".$a."\" value=\"".$_SESSION['ctrpl1']['grutaplanc'][$a]['tarifario_id']."\" class=\"input-text\" >";
                    $this->salida .="".$_SESSION['ctrpl1']['grutaplanc'][$a]['descripcion']."";
                }
            }
            $this->salida .= "  </td>";
            $this->salida .= "  <td height=\"30\" align=\"center\">";
            $_POST['porceplanc'.$a]=$_SESSION['ctrpl1']['grutaplanc'][$a]['porcentaje'];
            if(!empty($_POST['porctodoct']))
            {
                $_POST['porceplanc'.$a]=$_POST['porctodoct'];
            }
            if($_POST['porceplanc'.$a]==NULL)
            {
                $_POST['porceplanc'.$a]='0.0000';
            }
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porceplanc".$a."\" value=\"".$_POST['porceplanc'.$a]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "  </td>";
            $this->salida .= "  <td height=\"30\" align=\"center\">";
            $_POST['coberplanc'.$a]=$_SESSION['ctrpl1']['grutaplanc'][$a]['por_cobertura'];
            if(!empty($_POST['cobetodoct']))
            {
                $_POST['coberplanc'.$a]=$_POST['cobetodoct'];
            }
            if($_POST['coberplanc'.$a]==NULL)
            {
                $_POST['coberplanc'.$a]='0.0000';
            }
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"coberplanc".$a."\" value=\"".$_POST['coberplanc'.$a]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "  </td>";
            $this->salida .= "  <td height=\"30\" align=\"center\">";
            $_POST['descuplanc'.$a]=$_SESSION['ctrpl1']['grutaplanc'][$a]['sw_descuento'];
            if($_POST['descuplanc'.$a]==1 OR $_POST['desctodoct']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"descuplanc".$a."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"descuplanc".$a."\" value=1>";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  <td height=\"30\" align=\"center\" width=\"4%\">";
            if($_SESSION['ctrpl1']['grutaplanc'][$a]['porcentaje']<>NULL)
            {
                $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TariExcePlanContra',
                array('indicetaripl'=>$a)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
            }
            else
            {
                $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  <td height=\"30\" align=\"center\" width=\"5%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ConsulCargosTarifarioContra',
            array('indiconcar'=>$a)) ."\"><img title=\"CONSULTAR CARGOS\" src=\"".GetThemePath()."/images/pcargoscon.png\" border=\"0\"></a>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $k=$k+$c;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR TARIFARIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','TarifarioGrupoContraRapida');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  <br><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"100%\" align=\"center\">";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $tarifa=$this->BuscarTarifariosContra();//combos
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA EL TARIFARIO</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida');
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"40%\">TARIFARIO</td>";
            $this->salida .= "      <td width=\"18%\">PORCE.</td>";
            $this->salida .= "      <td width=\"18%\">COBER.</td>";
            $this->salida .= "      <td width=\"6%\" >DES.</td>";
            $this->salida .= "      <td width=\"18%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td width=\"30%\" align=\"center\">";
            $this->salida .= "      <select name=\"taritodoct\" class=\"select\">";
            $this->salida .= "      <option value=\"\">----</option>";
            $ciclo=sizeof($tarifa);
            for($l=0;$l<$ciclo;$l++)
            {
                if($_POST['taritodoct'] == $tarifa[$l]['tarifario_id'])
                {
                    $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\" selected>".$tarifa[$l]['descripcion']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\">".$tarifa[$l]['descripcion']."</option>";
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['porctodoct']))
            {
                $_POST['porctodoct']='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"porctodoct\" value=\"".$_POST['porctodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['cobetodoct']))
            {
                $_POST['cobetodoct']='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cobetodoct\" value=\"".$_POST['cobetodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"6%\" align=\"center\">";
            if($_POST['desctodoct']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"desctodoct\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"desctodoct\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"16%\" align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
        }
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que muestra la tarifa de un plan
    function TarifarioPlanContra()//Valida los cambios y determina si se debe insertar o modificar
    {
        UNSET($_SESSION['ctrpl1']['grutaplanc']);
        UNSET($_SESSION['ctrpl1']['plataplanc']);
        UNSET($_SESSION['ctrpl1']['dattarctra']);
        UNSET($_SESSION['ctrpl1']['cargotaric']);
        UNSET($_SESSION['ctrpl1']['dacacoctra']);
        UNSET($_SESSION['ctrpl1']['carconctra']);
        $_SESSION['ctrpl1']['marcapltra']=2;
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLAN TARIFARIO CLIENTE..');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarTarifarioPlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">TARIFARIOS POR GRUPOS Y SUBGRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table  border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"20%\">GRUPOS TARIFARIOS</td>";
        $this->salida .= "      <td width=\"30%\">SUBGRUPOS TARIFARIOS</td>";
        $this->salida .= "      <td width=\"18%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"10%\">PORCE.</td>";
        $this->salida .= "      <td width=\"10%\">COBER.</td>";
        $this->salida .= "      <td width=\"4%\" >DES.</td>";
        $this->salida .= "      <td colspan=\"2\">DETALLES</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['grutaplanc']=$this->BuscarGruposPlanContra($_SESSION['ctrpla']['planeleg']);
        $_SESSION['ctrpl1']['plataplanc']=$this->BuscarPlanTarifarioPlanContra($_SESSION['ctrpla']['planeleg']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['grutaplanc']);
        for($i=0;$i<$ciclo;)//$ciclo
        {
            $tarifarios=$this->BuscarTarifarioPlanContra($_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id'],
            $_SESSION['ctrpl1']['grutaplanc'][$i]['subgrupo_tarifario_id']);
            if(!empty($tarifarios))
            {
                if($j==0)
                {
                    $color="class=\"modulo_list_claro\"";
                    $j=1;
                }
                else
                {
                    $color="class=\"modulo_list_oscuro\"";
                    $j=0;
                }
                $this->salida .= "  <tr $color>";
                $this->salida .= "  <td>";
                $this->salida .= "".$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_descripcion']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td colspan=\"7\">";
                $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
                $k=$i;
                while($_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id'])
                {
                    $tarifarios=$this->BuscarTarifarioPlanContra($_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id'],
                    $_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']);
                    if(!empty($tarifarios))
                    {
                        //
/*                        $modificar=$this->ModificarTariPlanContra($_SESSION['ctrpla']['planeleg'],
                        $_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id'],
                        $_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']);*/
                        //
                        $modificar=0;
                        $ciclo1=sizeof($tarifarios);
                        $this->salida .= "      <tr>";
                        $this->salida .= "      <td height=\"30\" width=\"37%\">";
                        $this->salida .= "".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_descripcion']."";
                        $this->salida .= "      </td>";
                        $this->salida .= "      <td height=\"30\" align=\"right\" width=\"22%\">";
                        if($modificar==0)
                        {
                            $this->salida .= "      <select name=\"tarifplanc".$k."\" class=\"select\">";
                            $this->salida .= "      <option value=\"\">----</option>";
                            for($l=0;$l<$ciclo1;$l++)
                            {
                                if($_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']==$tarifarios[$l]['tarifario_id']
                                OR $tarifarios[$l]['tarifario_id']==$_POST['taritodoct'])
                                {
                                    $this->salida .="<option value=\"".$tarifarios[$l]['tarifario_id']."\" selected>".$tarifarios[$l]['descripcion']."</option>";
                                    $_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['descripcion']=$tarifarios[$l]['descripcion'];
                                }
                                else
                                {
                                    $this->salida .="<option value=\"".$tarifarios[$l]['tarifario_id']."\">".$tarifarios[$l]['descripcion']."</option>";
                                }
                            }
                            $this->salida .= "      </select>";
                        }
                        else
                        {
                            for($l=0;$l<$ciclo1;$l++)
                            {
                                if($_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']==$tarifarios[$l]['tarifario_id'])
                                {
                                    $this->salida .="<input type=\"hidden\" name=\"tarifplanc".$k."\" value=\"".$tarifarios[$l]['tarifario_id']."\" class=\"input-text\" >";
                                    $this->salida .="".$tarifarios[$l]['descripcion']."";
                                    $_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['descripcion']=$tarifarios[$l]['descripcion'];
                                }
                            }
                        }
                        $this->salida .= "      </td>";
                        $this->salida .= "      <td height=\"30\" align=\"center\" width=\"13%\">";
                        $_POST['porceplanc'.$k]=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['porcentaje'];
                        if(!empty($_POST['porctodoct']))
                        {
                            $_POST['porceplanc'.$k]=$_POST['porctodoct'];
                        }
                        if($_POST['porceplanc'.$k]==NULL)
                        {
                            $_POST['porceplanc'.$k]='0.0000';
                        }
                        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porceplanc".$k."\" value=\"".$_POST['porceplanc'.$k]."\" maxlength=\"8\" size=\"8\">";
                        $this->salida .= "%";
                        $this->salida .= "      </td>";
                        $this->salida .= "      <td height=\"30\" align=\"center\" width=\"13%\">";
                        $_POST['coberplanc'.$k]=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['por_cobertura'];
                        if(!empty($_POST['cobetodoct']))
                        {
                            $_POST['coberplanc'.$k]=$_POST['cobetodoct'];
                        }
                        if($_POST['coberplanc'.$k]==NULL)
                        {
                            $_POST['coberplanc'.$k]='0.0000';
                        }
                        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"coberplanc".$k."\" value=\"".$_POST['coberplanc'.$k]."\" maxlength=\"8\" size=\"8\">";
                        $this->salida .= "%";
                        $this->salida .= "      </td>";
                        $this->salida .= "      <td height=\"30\" align=\"center\" width=\"5%\">";
                        $_POST['descuplanc'.$k]=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['sw_descuento'];
                        if($_POST['descuplanc'.$k]==1 OR $_POST['desctodoct']==1)
                        {
                            $this->salida .= "<input type=\"checkbox\" name=\"descuplanc".$k."\" value=1 checked>";
                        }
                        else
                        {
                            $this->salida .= "<input type=\"checkbox\" name=\"descuplanc".$k."\" value=1>";
                        }
                        $this->salida .= "      </td>";
                        $this->salida .= "      <td height=\"30\" align=\"center\" width=\"5%\">";
                        if($_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$i]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['porcentaje'])
                        {
                            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TariExcePlanContra',
                            array('indicetaripl'=>$k)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                        }
                        else
                        {
                            $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
                        }
                        $this->salida .= "      </td>";
                        $this->salida .= "        <td height=\"30\" align=\"center\" width=\"5%\">";
                        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ConsulCargosTarifarioContra',
                        array('indiconcar'=>$k)) ."\"><img title=\"CONSULTAR CARGOS\" src=\"".GetThemePath()."/images/pcargoscon.png\" border=\"0\"></a>";
                        $this->salida .= "      </td>";
                        $this->salida .= "      </tr>";
                    }
                    $k++;
                }
                $this->salida .= "      </table>";
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
            }
            else
            {
                $k++;
            }
            $i=$k;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  <br><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"100%\" align=\"center\">";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $tarifa=$this->BuscarTarifariosContra();//combos
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA EL TARIFARIO</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContra');
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"40%\">TARIFARIO</td>";
            $this->salida .= "      <td width=\"18%\">PORCE.</td>";
            $this->salida .= "      <td width=\"18%\">COBER.</td>";
            $this->salida .= "      <td width=\"6%\" >DES.</td>";
            $this->salida .= "      <td width=\"18%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td width=\"30%\" align=\"center\">";
            $this->salida .= "      <select name=\"taritodoct\" class=\"select\">";
            $this->salida .= "      <option value=\"\">----</option>";
            $ciclo=sizeof($tarifa);
            for($l=0;$l<$ciclo;$l++)
            {
                if($_POST['taritodoct'] == $tarifa[$l]['tarifario_id'])
                {
                    $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\" selected>".$tarifa[$l]['descripcion']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\">".$tarifa[$l]['descripcion']."</option>";
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['porctodoct']))
            {
                $_POST['porctodoct']='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"porctodoct\" value=\"".$_POST['porctodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['cobetodoct']))
            {
                $_POST['cobetodoct']='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cobetodoct\" value=\"".$_POST['cobetodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"6%\" align=\"center\">";
            if($_POST['desctodoct']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"desctodoct\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"desctodoct\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"16%\" align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  <br>";
            $accion=ModuloGetURL('app','Contratacion','user','ValidarCopiarTarifarioPlanContra');//cambiar
            $this->salida .= "  <form name=\"contratari\" action=\"$accion\" method=\"post\">";
            $ru='app_modules/Contratacion/selectortarifario.js';
            $rus='app_modules/Contratacion/selectorplan.php';
            $this->salida .= "  <script languaje='javascript' src=\"$ru\">";
            $this->salida .= "  </script>";
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA CONTRATOS ANTERIORES (COPIAR)</legend>";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">";
            $this->salida .= "      OPCIONES DE BÚSQUEDA";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">NÚMERO DE CONTRATO</td>";
            $this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tarifario1\" value=\"".$_POST['tarifario1']."\" maxlength=\"20\" size=\"20\" readonly>";
            $this->salida .= "      <input type=\"hidden\" name=\"tarifario2\" value=\"".$_POST['tarifario2']."\" maxlength=\"20\" size=\"20\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">EMPRESAS</td>";
            $this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
            $empresas=$this->BuscarEmpresasContra();
            $this->salida .= "      <select name=\"empresacon\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\">TODAS</option>";
            $ciclo=sizeof($empresas);
            for($i=0;$i<$ciclo;$i++)
            {
                if($empresas[$i]['empresa_id']==$_POST['empresacon'])
                {
                    $this->salida .="<option value=\"".$empresas[$i]['empresa_id']."\" selected>".$empresas[$i]['razon_social']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$empresas[$i]['empresa_id']."\">".$empresas[$i]['razon_social']."</option>";
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">TIPO PLAN</td>";
            $this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
            $tipoplan=$this->BuscarTipoPlanContra();
            $this->salida .= "      <select name=\"tipoplacon\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\">TODOS</option>";
            $ciclo=sizeof($tipoplan);
            for($i=0;$i<$ciclo;$i++)
            {
                if($tipoplan[$i]['sw_tipo_plan']==$_POST['tipoplacon'])
                {
                    $this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\" selected>".$tipoplan[$i]['descripcion']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$tipoplan[$i]['sw_tipo_plan']."\">".$tipoplan[$i]['descripcion']."</option>";
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">ESTADO DEL PLAN</td>";
            $this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
            $this->salida .= "      <select name=\"estadocont\" class=\"select\">";
            $this->salida .= "      <option value=\"1\">TODOS</option>";
            if($_POST['estadocont']==2)
            {
                $this->salida .= "<option value=\"2\" selected>ACTIVOS</option>";
            }
            else
            {
                $this->salida .= "<option value=\"2\">ACTIVOS</option>";
            }
            if($_POST['estadocont']==3)
            {
                $this->salida .= "<option value=\"3\" selected>INACTIVOS</option>";
            }
            else
            {
                $this->salida .= "<option value=\"3\">INACTIVOS</option>";
            }
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\" colspan=\"3\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR TARIFARIO\" onclick=\"abrirVentana('Buscador_Tarifario','$rus',this.form)\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">";
            $this->salida .= "      OPCIONES PARA GUARDAR POR CARGOS";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"60%\">OPCIONES</td>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">GRUPOS</td>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">EXCEPCIONES</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">TARIFARIO";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiartari']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiartari\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiartari\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiartariex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiartariex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiartariex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">COPAGOS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarcopa']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarcopa\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarcopa\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarcopaex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarcopaex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarcopaex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">SEMANAS PARA DÍAS DE CARENCIA";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarsema']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarsema\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarsema\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarsemaex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarsemaex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarsemaex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">";
            $this->salida .= "      OPCIONES PARA GUARDAR POR SERVICIOS";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">AUTORIZACIONES INTERNAS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarauin']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauin\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauin\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarauinex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauinex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauinex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">AUTORIZACIONES EXTERNAS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarauex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarauexex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauexex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarauexex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">INSUMOS Y MEDICAMENTOS - AUTORIZACIONES";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarinm2']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinm2\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinm2\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarinmee2']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinmee2\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinmee2\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">INSUMOS Y MEDICAMENTOS - COPAGOS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarinme']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinme\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinme\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['copiarinmeex']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinmeex\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarinmeex\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" colspan=\"2\">";
            if($_POST['copiarpaim']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarpaim\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarpaim\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">PARAGRAFADOS CARGOS DIRECTOS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" colspan=\"2\">";
            if($_POST['copiarpacd']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarpacd\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarpacd\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">INCUMPLIMIENTO DE CITAS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" colspan=\"2\">";
            if($_POST['copiarincu']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarincu\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"copiarincu\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td colspan=\"3\" align=\"center\" class=\"label_error\">ADVERTENCIA: ESTA OPCIÓN MODIFICA TODO EL TARIFARIO Y LOS SERVICIOS DEL CONTRATO</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"center\" width=\"100%\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR OPCIONES\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  <br>";
        }
        $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA CONTRATACIÓN POR NIVELES</legend>";
        $niveles=$this->BuscarNivelesAteContra();
        $accion=ModuloGetURL('app','Contratacion','user','ContarDatosNivelContra');
        $this->salida .= "      <form name=\"contrata4\" action=\"$accion\" method=\"post\">";
        if($this->dos == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <input type=\"hidden\" class=\"input-text\" name=\"niveles\" value=\"".sizeof($niveles)."\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">CARGOS POR NIVELES</td>";
        $this->salida .= "      <td width=\"20%\">PORCE.</td>";
        $this->salida .= "      <td width=\"20%\">COBER.</td>";
        $this->salida .= "      <td width=\"10%\">DES.</td>";
        $this->salida .= "      </tr>";
        $ciclo=sizeof($niveles);
        for($i=1;$i<=$ciclo;$i++)
        {
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td width=\"50%\" align=\"center\">";
            $this->salida .= "".$niveles[($i-1)]['descripcion']."";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['porcnivect'.$i]))
            {
                $_POST['porcnivect'.$i]='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"porcnivect".$i."\" value=\"".$_POST['porcnivect'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['cobenivect'.$i]))
            {
                $_POST['cobenivect'.$i]='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cobenivect".$i."\" value=\"".$_POST['cobenivect'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"6%\" align=\"center\">";
            if($_POST['descnivect'.$i]==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"descnivect".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"descnivect".$i."\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td colspan=\"4\" align=\"center\" class=\"label_error\">ADVERTENCIA: ESTA OPCIÓN MODIFICA TODO EL TARIFARIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardanivel\"  value=\"GUARDAR NIVELES\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ConsulCargosTarifarioContra()//
    {
        if($_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_id']==NULL AND $_SESSION['ctrpl1']['marcapltra']==1)
        {
            $_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['grupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dacacoctra']['subgrupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['subgrupo_tarifario_id'];
            $_SESSION['ctrpl1']['dacacoctra']['subgrupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['subgrupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dacacoctra']['tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['tarifario_id'];
            $_SESSION['ctrpl1']['dacacoctra']['descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['descripcion'];
            $_SESSION['ctrpl1']['dacacoctra']['porcentaje']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['porcentaje'];
            UNSET($_SESSION['ctrpl1']['grutaplanc']);
        }
        if($_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_id']==NULL AND $_SESSION['ctrpl1']['marcapltra']<>1)
        {
            $_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['grupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dacacoctra']['subgrupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['subgrupo_tarifario_id'];
            $_SESSION['ctrpl1']['dacacoctra']['subgrupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indiconcar']]['subgrupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dacacoctra']['tarifario_id']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['tarifario_id'];
            $_SESSION['ctrpl1']['dacacoctra']['descripcion']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['descripcion'];
            $_SESSION['ctrpl1']['dacacoctra']['porcentaje']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['porcentaje'];
            UNSET($_SESSION['ctrpl1']['grutaplanc']);
            UNSET($_SESSION['ctrpl1']['plataplanc']);
        }
        UNSET($_SESSION['ctrpl1']['carconctra']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - CONSULTAR CARGOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        if($_SESSION['ctrpl1']['marcapltra']==1)
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida') ."\">";
        }
        else
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioPlanContra') ."\">";
        }
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">CARGOS POR GRUPOS Y SUBGRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">SUBGRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dacacoctra']['subgrupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">TARIFARIO CONTRATADO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['dacacoctra']['porcentaje']<>NULL)
        {
            $this->salida .= "".$_SESSION['ctrpl1']['dacacoctra']['descripcion']."";
        }
        else
        {
            $this->salida .= "NO TIENE UN TARIFARIO CONTRATADO";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"8%\" >CARGO</td>";
        $this->salida .= "      <td width=\"70%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"16%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"6%\" >CONTRA.</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $_SESSION['ctrpl1']['carconctra']=$this->BuscarConsulCargosTarifarioContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['dacacoctra']['grupo_tarifario_id'],$_SESSION['ctrpl1']['dacacoctra']['subgrupo_tarifario_id']);
        $ciclo=sizeof($_SESSION['ctrpl1']['carconctra']);
        for($i=0;($i<$ciclo);$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['carconctra'][$i]['cargo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['carconctra'][$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['carconctra'][$i]['destarifario']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if($_SESSION['ctrpl1']['carconctra'][$i]['tarifario_id']<>NULL)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
            }
            else
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($_SESSION['ctrpl1']['carconctra']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"4\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO EN EL TARIFARIO PARA ESTE GRUPO Y SUBGRUPO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"100%\" align=\"center\">";
        if($_SESSION['ctrpl1']['marcapltra']==1)
        {
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida');
        }
        else
        {
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContra');
        }
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraTaCaCo();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','ConsulCargosTarifarioContra',array(
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra'],'tarifactra'=>$_REQUEST['tarifactra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $tarifa=$this->BuscarTarifariosContra();//combos
        $this->salida .= "  <tr class=\"modulo_list_claro\">";
        $this->salida .= "  <td width=\"30%\" class=\"label\">TARIFARIO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <select name=\"tarifactra\" class=\"select\">";
        $this->salida .= "  <option value=\"\">----</option>";
        $ciclo=sizeof($tarifa);
        for($l=0;$l<$ciclo;$l++)
        {
            if($_REQUEST['tarifactra'] == $tarifa[$l]['tarifario_id'])
            {
                $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\" selected>".$tarifa[$l]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\">".$tarifa[$l]['descripcion']."</option>";
            }
        }
        $this->salida .= "  </select>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ConsulCargosTarifarioContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que muestra los cargos y excepciones del plan tarifario
    function TariExcePlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        if($_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_id']==NULL AND $_SESSION['ctrpl1']['marcapltra']==1)
        {
            $_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dattarctra']['tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['descripcion'];
            $_SESSION['ctrpl1']['dattarctra']['porcentaje']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['porcentaje'];
            $_SESSION['ctrpl1']['dattarctra']['por_cobertura']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['por_cobertura'];
            $_SESSION['ctrpl1']['dattarctra']['sw_descuento']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['sw_descuento'];
            UNSET($_SESSION['ctrpl1']['grutaplanc']);
        }
        if($_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id']==NULL AND $_SESSION['ctrpl1']['marcapltra']<>1)
        {
            $_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_id']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_descripcion']=$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['dattarctra']['tarifario_id']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['tarifario_id'];
            $_SESSION['ctrpl1']['dattarctra']['descripcion']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['descripcion'];
            $_SESSION['ctrpl1']['dattarctra']['porcentaje']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['porcentaje'];
            $_SESSION['ctrpl1']['dattarctra']['por_cobertura']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['por_cobertura'];
            $_SESSION['ctrpl1']['dattarctra']['sw_descuento']=$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$_REQUEST['indicetaripl']]['subgrupo_tarifario_id']]['sw_descuento'];
            UNSET($_SESSION['ctrpl1']['grutaplanc']);
            UNSET($_SESSION['ctrpl1']['plataplanc']);
        }
        UNSET($_SESSION['ctrpl1']['cargotaric']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PLAN TARIFARIO CLIENTE - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarExceTariPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        if($_SESSION['ctrpl1']['marcapltra']==1)
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida') ."\">";
        }
        else
        {
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioPlanContra') ."\">";
        }
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">SUBGRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"20%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dattarctra']['descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">PORCENTAJE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dattarctra']['porcentaje'].' '.'%'."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">PORCENTAJE COBERTURA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dattarctra']['por_cobertura'].' '.'%'."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">DESCUENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['dattarctra']['sw_descuento']==1)
        {
            $this->salida .= "SI";
        }
        else
        {
            $this->salida .= "NO";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"44%\">DESCRIPCIÓN</td>";//56
        //$this->salida .= "      <td width=\"2%\" >N.</td>";//56
        $this->salida .= "      <td width=\"6%\">VALOR</td>";
        $this->salida .= "      <td width=\"2%\">UNIDAD</td>";
        $this->salida .= "      <td width=\"10%\">DES. UN.</td>";
/*        $this->salida .= "      <td width=\"8%\">PRECIO LIQ. $</td>";*/
/*				$this->salida .= "      <td width=\"6%\">PRECIO LIQ. $</td>";
				$this->salida .= "      <td width=\"6%\">PORCE. // VALOR</td>";*/
        //$this->salida .= "      <td width=\"48%\">DESCRIPCIÓN</td>";//56
        //$this->salida .= "      <td width=\"2%\" >N.</td>";//56
        //$this->salida .= "      <td width=\"8%\">PRECIO</td>";
/*        $this->salida .= "      <td width=\"2%\">UNIDAD</td>";
        $this->salida .= "      <td width=\"6%\">DESCRIP.</td>";*/
        //$this->salida .= "      <td width=\"8%\">PRECIO LIQ. $</td>";
        $this->salida .= "      <td width=\"8%\">PRECIO PACTADO</td>";
				$this->salida .= "      <td width=\"8%\">PORCE. // PESOS</td>";
				$this->salida .= "      <td width=\"2%\">%</td>";
				$this->salida .= "      <td width=\"2%\">$</td>";
				$this->salida .= "      <td width=\"10%\">COBER.</td>";
				$this->salida .= "      <td width=\"3%\" >DES.</td>";
				$this->salida .= "      <td width=\"3%\" >NO CONT.</td>";
				$this->salida .= "      </tr>";
				$j=0;
				$_SESSION['ctrpl1']['cargotaric']=$this->BuscarCarTarPlanContra($_SESSION['ctrpla']['planeleg'],
				$_SESSION['ctrpl1']['dattarctra']['grupo_tarifario_id'],$_SESSION['ctrpl1']['dattarctra']['subgrupo_tarifario_id']);
				$ciclo=sizeof($_SESSION['ctrpl1']['cargotaric']);
				for($i=0;($i<$ciclo);$i++)
				{
						if($j==0)
						{
								$color="class=\"modulo_list_claro\"";
								$j=1;
						}
						else
						{
								$color="class=\"modulo_list_oscuro\"";
								$j=0;
						}
						$this->salida .= "<tr $color>";
						$this->salida .= "<td align=\"center\">";
						$this->salida .= "".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."";
						$this->salida .= "</td>";
						$this->salida .= "<td>";
						$this->salida .= "".$_SESSION['ctrpl1']['cargotaric'][$i]['descripcion']."";
						$this->salida .= "</td>";
						$this->salida .= "<td  align=\"right\">";
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="01")//PESOS
								$this->salida .= "".FormatoValor($_SESSION['ctrpl1']['cargotaric'][$i]['precio'])."";
						else
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="02")//UVR
								$this->salida .= "<Label class=label><font color=\"#f87a17\">".FormatoValor($_SESSION['ctrpl1']['cargotaric'][$i]['precio'])."</font></label>";
						else
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="03")//SMMLV
								$this->salida .= "<Label class=label><font color=\"#151b7e\">".FormatoValor($_SESSION['ctrpl1']['cargotaric'][$i]['precio'])."</font></label>";
						else
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="04" OR $_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="05")//04 - GQ - GRUPOS QUIRURJICOS//05 UVRS	UNIDADES DE VALOR REAL PARA PAQUETES 
								$this->salida .= "<Label class=label><font color=\"#151b7e\">".FormatoValor($_SESSION['ctrpl1']['cargotaric'][$i]['precio'])."</font></label>";
						$this->salida .= "      <input type=\"hidden\" name=\"preciocargo".$i."\" value=\"".$_SESSION['ctrpl1']['cargotaric'][$i]['precio']."\">";
						$this->salida .= "      <input type=\"hidden\" name=\"tipounidad".$i."\" value=\"".$_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']."\">";
						$this->salida .= "</td>";
						$descripcion_corta=$this->BuscarDesUnidad($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']);
						$this->salida .= "<td>";
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="04")//GQ - GRUPOS QUIRURJICOS
							$this->salida .= "<b>".$descripcion_corta[0][descripcion_corta]."<b>";
						else
							$this->salida .= "".$descripcion_corta[0][descripcion_corta]."";
// 						if(!empty($descripcion_corta[0][descripcion]))
// 							$this->salida .=" <img title=\"".$descripcion_corta[0][descripcion]."\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
						$this->salida .= "</td>";
						$this->salida .= "<td align=\"center\">";
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="01")//PESOS
							$this->salida .= "<font size=\"1\" face=\"arial\">".strtolower($descripcion_corta[0][descripcion])."</font>";
						else
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="02")//UVR
							$this->salida .= "<font size=\"1\" face=\"arial\">".strtolower($descripcion_corta[0][descripcion])."</font>";
						else
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="03")//SMMLV
							$this->salida .= "(".$_SESSION['ctrpl1']['cargotaric'][$i]['precio']."*".GetSalarioMinimo(date("Y")).")*\n(1+".($_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']/100).")";
						else
						if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="04" OR $_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="05")//GQ - GRUPOS QUIRURJICOS//05 UVRS	UNIDADES DE VALOR REAL PARA PAQUETES 
							$this->salida .= "<font size=\"1\" face=\"arial\"><b>".strtolower($descripcion_corta[0][descripcion])."</b></font>";
						$this->salida .= "</td>";
/*            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargotaric'][$i]['nivel']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            if($_SESSION['ctrpl1']['cargotaric'][$i]['sw_uvrs']=='1')
            {
                $this->salida .= "".$_SESSION['ctrpl1']['cargotaric'][$i]['precio']."".' UVR'."";
            }
            else
            {
								$this->salida .= "".'$ '."".FormatoValor($_SESSION['ctrpl1']['cargotaric'][$i]['precio'])."";
								$this->salida .= "      <input type=\"hidden\" name=\"preciocargo".$i."\" value=\"".$_SESSION['ctrpl1']['cargotaric'][$i]['precio']."\">";
            }
            $this->salida .= "</td>";*/
						//VISUALIZACIÓN DEL PRECIO SOBRE EL CUAL SE VA A LIQUIDAR
						$this->salida .= "<td align=\"right\">";
						if(!empty($_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']))
						{ 
								if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="01")//PESOS
								{
									$val=$_SESSION['ctrpl1']['cargotaric'][$i]['precio']+($_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']*$_SESSION['ctrpl1']['cargotaric'][$i]['precio']/100);
									$this->salida .=  "$&nbsp;".FormatoValor(round($val,1))."";
								}
								else
								if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="02")//UVR
								{
/*									if(!$valido)
										$this->salida .=  "<font color=\"#f87a17\"><B>No hay rangos uvr válidas</B></font>";
									else
									{*/
										$val2=$_SESSION['ctrpl1']['cargotaric'][$i]['precio'];
										$this->salida .=  "<font color=\"#f87a17\"><B>".FormatoValor(round($val2,1))."</B></font>";
//									}
								}
								else
								if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="03")//SMMLV
								{
									$val3=($_SESSION['ctrpl1']['cargotaric'][$i]['precio']*GetSalarioMinimo(date("Y")))*(1+$_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']/100);
									$this->salida .=  "<Label class=label><font color=\"#151b7e\"><B>$&nbsp;".FormatoValor(round($val3,-2))."</B></font></label>";
								}
								else
								if ($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="04")//GQ - GRUPOS QUIRURJICOS
								{
									$val4=$_SESSION['ctrpl1']['cargotaric'][$i]['precio'];
									$this->salida .=  "<Label class=label><font color=\"#151b7e\"><B>".FormatoValor(round($val4,1))."</B></font></label>";
								}
								else
								if($_SESSION['ctrpl1']['cargotaric'][$i]['tipo_unidad_id']=="05")//05 UVRS	UNIDADES DE VALOR REAL PARA PAQUETES 
								{
									$val4=($_SESSION['ctrpl1']['cargotaric'][$i]['precio']*(1+$_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']/100))*100;
									$this->salida .=  "<Label class=label><font color=\"#151b7e\"><B>".FormatoValor($val4)."</B></font></label>";
								}
										
            }
						else
						{
							$this->salida .= "".FormatoValor(round($_SESSION['ctrpl1']['cargotaric'][$i]['precio'],-2));
						}
            $this->salida .= "</td>";
						//FIN VISUALIZACIÓN DEL PRECIO SOBRE EL CUAL SE VA A LIQUIDAR
/*            $this->salida .= "<td align=\"center\">";
            if($_SESSION['ctrpl1']['cargotaric'][$i]['excepcion']==1 AND
            $_SESSION['ctrpl1']['cargotaric'][$i]['sw_no_contratado']==0)
            {
                $_POST['porexctra'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje'];
                $_POST['cobexctra'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['por_cobertura'];
                $_POST['desexctra'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['sw_descuento'];
            }
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porexctra".$i."\" value=\"".$_POST['porexctra'.$i]."\" maxlength=\"9\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "</td>";*/
            $this->salida .= "<td align=\"center\">";
            if($_SESSION['ctrpl1']['cargotaric'][$i]['excepcion']==1 AND
            $_SESSION['ctrpl1']['cargotaric'][$i]['sw_no_contratado']==0)
            { 
                $_POST['porexctra'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje'];
                $_POST['cobexctra'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['por_cobertura'];
                $_POST['desexctra'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['sw_descuento'];
            }
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porexctra".$i."\" value=\"".$_POST['porexctra'.$i]."\" maxlength=\"9\" size=\"9\">";
            $this->salida .= "</td>";
						$this->salida .= "<td align=\"center\">";
						$this->salida .= "<input type=\"radio\" name=\"radioporexctra".$i."\" value=1 checked>";
						$this->salida .= "</td>";
						$this->salida .= "<td align=\"center\">";
						$this->salida .= "<input type=\"radio\" name=\"radioporexctra".$i."\" value=0>";
						$this->salida .= "</td>";
						$this->salida .= "<td align=\"center\">";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cobexctra".$i."\" value=\"".$_POST['cobexctra'.$i]."\" maxlength=\"9\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if($_POST['desexctra'.$i]==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"desexctra".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"desexctra".$i."\" value=1>";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if($_SESSION['ctrpl1']['cargotaric'][$i]['sw_no_contratado']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"contratado".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"contratado".$i."\" value=1>";
            }
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($_SESSION['ctrpl1']['cargotaric']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"8\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO EN EL TARIFARIO PARA ESTE GRUPO Y SUBGRUPO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        if($_SESSION['ctrpl1']['marcapltra']==1)
        {
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida');
        }
        else
        {
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContra');
        }
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraTaCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','TariExcePlanContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TariExcePlanContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que establece los copagos para los grupos y subgrupos tarifarios
    function CopagosPlanContra()//Válida los switches de los copagos
    {
				//echo $_REQUEST['ayudacopagos']; 
        UNSET($_SESSION['ctrpl1']['copagoctra']);
        UNSET($_SESSION['ctrpl1']['copserctra']);
        UNSET($_SESSION['ctrpl1']['datcopctra']);//indice
        UNSET($_SESSION['ctrpl1']['cargocopac']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - COPAGOS');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarCopagosPlanContra');
        $this->salida .= "  <form name=\"copagos\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">COPAGOS POR GRUPOS Y SUBGRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"30%\">GRUPO TARIFARIO</td>";
        $this->salida .= "      <td width=\"35%\">SUBGRUPOS TARIFARIOS // TARIFARIO CONTRATADO</td>";
        $this->salida .= "      <td width=\"28%\">COPAGOS - CUOTA MODERADORA</td>";
        $this->salida .= "      <td width=\"7%\" >DETALLES</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $_SESSION['ctrpl1']['copserctra']=$this->MostrarServiciosPlanes2($_SESSION['ctrpla']['planeleg']);
        $_SESSION['ctrpl1']['copagoctra']=$this->BuscarCopagosPlanContra($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($_SESSION['ctrpl1']['copagoctra']);
        $ciclo1=sizeof($_SESSION['ctrpl1']['copserctra']);
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                $color="class=modulo_list_claro";
                $j=1;
            }
            else
            {
                $color="class=modulo_list_oscuro";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['copagoctra'][$i]['grupo_tarifario_descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" colspan=\"3\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['copagoctra'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id'])
            {
                if($_SESSION['ctrpl1']['copagoctra'][$k]['sw_copagos']==0)
                {
                    $this->salida .= "      <tr>";
                    $this->salida .= "      <td width=\"50%\">";
                    $this->salida .= "".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_descripcion']."<br><br>";
                    $this->salida .= "<label class=label_mark>".$_SESSION['ctrpl1']['copagoctra'][$k]['descripcion']."</label>";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\" width=\"40%\">";
                    $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" $color>";
                    $this->salida .= "          <tr>";
                    $this->salida .= "          <td align=\"left\" width=\"70%\">";
                    $this->salida .= "COPAGO";
                    $this->salida .= "          </td>";
                    $this->salida .= "          <td align=\"center\" width=\"30%\">";
                    if(($_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']==1 && $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==1)
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=1 checked>";
                    }
                    else
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=1>";
                    }
                    $this->salida .= "          </td>";
                    $this->salida .= "          </tr>";
                    $this->salida .= "          <tr>";
                    $this->salida .= "          <td align=\"left\" width=\"70%\">";
                    $this->salida .= "CUOTA MODERADORA";
                    $this->salida .= "          </td>";
                    $this->salida .= "          <td align=\"center\" width=\"30%\">";
                    if(($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']==1 && $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==2)
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=2 checked>";
                    }
                    else
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=2>";
                    }
                    $this->salida .= "          </td>";
                    $this->salida .= "          <tr>";
                    $this->salida .= "          <td align=\"left\" width=\"70%\">";
                    $this->salida .= "NINGUNA";
                    $this->salida .= "          </td>";
                    $this->salida .= "          <td align=\"center\" width=\"30%\">";
                    if(($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']<>NULL
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']<>NULL
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']<>1
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']<>1 && $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==3)
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=3 checked>";
                    }
                    else
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=3>";
                    }
                    $this->salida .= "          </td>";
                    $this->salida .= "          </tr>";
                    $this->salida .= "          <tr>";
                    $this->salida .= "          <td align=\"left\" width=\"70%\">";
                    $this->salida .= "NO GRABAR";
                    $this->salida .= "          </td>";
                    $this->salida .= "          <td align=\"center\" width=\"30%\">";
                    if(($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']==NULL
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']==NULL && $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==4)
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=4 checked>";
                    }
                    else
                    {
                        $this->salida .= "<input type=\"radio\" name=\"cuotas".$i.$k."\" value=4>";
                    }
                    $this->salida .= "          </td>";
                    $this->salida .= "          </tr>";
                    $this->salida .= "          </table>";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\" width=\"10%\">";
                    if($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']<>NULL
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']<>NULL)
                    {
                        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','CopagosExcePlanContra',
                        array('indicecopago'=>$k)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                    }
                    else
                    {
                        $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
                    }
                    $this->salida .= "      </td>";
                    $this->salida .= "      </tr>";
                    $k++;
                }
                else if($_SESSION['ctrpl1']['copagoctra'][$k]['sw_copagos']==1)
                {
                    $p=$k;
                    $t=0;
                    while($_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']==$_SESSION['ctrpl1']['copagoctra'][$p]['subgrupo_tarifario_id']
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']==$_SESSION['ctrpl1']['copagoctra'][$p]['grupo_tarifario_id'])
                    {
                        $t++;
                        $p++;
                    }
                    $p=$k;
                    $this->salida .= "      <tr>";
                    $this->salida .= "      <td align=\"center\" width=\"30%\">";
                    $this->salida .= "".$_SESSION['ctrpl1']['copagoctra'][$p]['subgrupo_tarifario_descripcion']."<br><br>";
                    $this->salida .= "<label class=label_mark>".$_SESSION['ctrpl1']['copagoctra'][$p]['descripcion']."</label>";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\" width=\"35%\">";
                    $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" $color>";
                    for($s=0;$s<$ciclo1;$s++)
                    {
                        $this->salida .= "      <tr>";
                        $this->salida .= "      <td height=\"30\" align=\"center\">";
                        $this->salida .= "".$_SESSION['ctrpl1']['copserctra'][$s]['descripcion']."";
                        $this->salida .= "      </td>";
                        $this->salida .= "      </tr>";
                    }
                    $this->salida .= "          </table>";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\" width=\"25%\">";
                    $this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" $color>";
                    for($s=0;$s<$ciclo1;$s++)
                    {
                        $this->salida .= "      <tr>";
                        $this->salida .= "      <td height=\"30\">";
                        $this->salida .= "      <select name=\"cuotas".$i.$k.$s."\" class=\"select\">";
                        $this->salida .= "      <option value=\"4\">NO GRABAR</option>";
                        if(($_SESSION['ctrpl1']['copagoctra'][$p]['sw_copago']==1
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['servicio']==$_SESSION['ctrpl1']['copserctra'][$s]['servicio']
												&& $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==1)
                        {
													$this->salida .="<option value=\"1\" selected>COPAGO</option>";
													$p++;
                        }
                        else
                        {
                            $this->salida .="<option value=\"1\">COPAGO</option>";
                        }
                        if(($_SESSION['ctrpl1']['copagoctra'][$p]['sw_cuota_moderadora']==1
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['servicio']==$_SESSION['ctrpl1']['copserctra'][$s]['servicio']
												&& $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==2)
                        {
													$this->salida .="<option value=\"2\" selected>CUOTA MODERADORA</option>";
													$p++;
                        }
                        else
                        {
                            $this->salida .="<option value=\"2\">CUOTA MODERADORA</option>";
                        }
                        if(($_SESSION['ctrpl1']['copagoctra'][$p]['sw_cuota_moderadora']<>NULL
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['sw_copago']<>NULL
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['sw_cuota_moderadora']<>1
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['sw_copago']<>1
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['servicio']==$_SESSION['ctrpl1']['copserctra'][$s]['servicio']
												&& $_REQUEST['ayudacopagos']==NULL) || $_REQUEST['ayudacopagos']==3)
                        {
                            $this->salida .="<option value=\"3\" selected>NINGUNA</option>";
                            $p++;
                        }
                        else
                        {
                            $this->salida .="<option value=\"3\">NINGUNA</option>";
                        }
                        $this->salida .= "      </select>";
                        $this->salida .= "      </td>";
                        $this->salida .= "      </tr>";
                    }
                    $this->salida .= "          </table>";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\" width=\"10%\">";
                    $this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" $color>";
                    $p=$k;
                    for($s=0;$s<$ciclo1;$s++)
                    {
                        $this->salida .= "      <tr>";
                        $this->salida .= "      <td height=\"30\" align=\"center\">";
                        if($_SESSION['ctrpl1']['copagoctra'][$p]['sw_cuota_moderadora']<>NULL
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['sw_copago']<>NULL
                        AND $_SESSION['ctrpl1']['copagoctra'][$p]['servicio']==$_SESSION['ctrpl1']['copserctra'][$s]['servicio'])
                        {
                            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','CopagosExcePlanContra',
                            array('indicecopago'=>$p)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                            $p++;
                        }
                        else
                        {
                            $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
                        }
                        $this->salida .= "      </td>";
                        $this->salida .= "      </tr>";
                    }
                    $this->salida .= "          </table>";
                    $this->salida .= "      </td>";
                    $this->salida .= "      </tr>";
                    $k=$k+$t;
                }
            }
            $i=$k;
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        if(empty($_SESSION['ctrpl1']['copagoctra']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"4\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO O SUBGRUPO TARIFARIOS'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
				//AYUDA PARA COPAGOS
        $accion=ModuloGetURL('app','Contratacion','user','CopagosPlanContra');
        $this->salida .= "  <form name=\"copagos\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"100%\" align=\"center\">";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            //$tarifa=$this->BuscarTarifariosContra();//combos
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA LOS COPAGOS</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioPlanContraRapida');
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"80%\">COPAGOS-CUOTA MODERADORA</td>";
/*            $this->salida .= "      <td width=\"18%\">PORCE.</td>";
            $this->salida .= "      <td width=\"18%\">COBER.</td>";
            $this->salida .= "      <td width=\"6%\" >DES.</td>";*/
            $this->salida .= "      <td width=\"28%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td width=\"80%\" align=\"center\">";
            $this->salida .= "      <select name=\"ayudacopagos\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\" selected>--SELECCIONE--</option>";
						$this->salida .= "			<option value=\"1\">COPAGO</option>";
						$this->salida .= "			<option value=\"2\">CUOTA MODERADORA</option>";
						$this->salida .= "			<option value=\"3\">NINGUNA</option>";
						$this->salida .= "      <option value=\"4\">NO GRABAR</option>";
/*            $ciclo=sizeof($tarifa);
            for($l=0;$l<$ciclo;$l++)
            {
                if($_POST['taritodoct'] == $tarifa[$l]['tarifario_id'])
                {
                    $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\" selected>".$tarifa[$l]['descripcion']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\">".$tarifa[$l]['descripcion']."</option>";
                }
            }*/
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
/*            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['porctodoct']))
            {
                $_POST['porctodoct']='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"porctodoct\" value=\"".$_POST['porctodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"14%\" align=\"center\">";
            if(empty($_POST['cobetodoct']))
            {
                $_POST['cobetodoct']='0';
            }
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cobetodoct\" value=\"".$_POST['cobetodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"6%\" align=\"center\">";
            if($_POST['desctodoct']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"desctodoct\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"desctodoct\" value=1>";
            }
            $this->salida .= "      </td>";*/
            $this->salida .= "      <td width=\"20%\" align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
					}
        $this->salida .= "  </>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </form>";
				//FIN AYUDA PARA COPAGOS
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que permite crear, modificar o eliminar las excepciones según el cargo de los copagos
    function CopagosExcePlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        if($_SESSION['ctrpl1']['datcopctra']['grupo_tarifario_id']==NULL)
        {
            $_SESSION['ctrpl1']['datcopctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['datcopctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['grupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['datcopctra']['subgrupo_tarifario_id']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['subgrupo_tarifario_id'];
            $_SESSION['ctrpl1']['datcopctra']['subgrupo_tarifario_descripcion']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['subgrupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['datcopctra']['sw_copagos']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['sw_copagos'];
            $_SESSION['ctrpl1']['datcopctra']['sw_copago']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['sw_copago'];
            $_SESSION['ctrpl1']['datcopctra']['sw_cuota_moderadora']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['sw_cuota_moderadora'];
            $_SESSION['ctrpl1']['datcopctra']['servicio']=$_SESSION['ctrpl1']['copagoctra'][$_REQUEST['indicecopago']]['servicio'];
            UNSET($_SESSION['ctrpl1']['copagoctra']);
        }
        UNSET($_SESSION['ctrpl1']['cargocopac']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - COPAGOS - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarExceCopaPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','CopagosPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if($_SESSION['ctrpl1']['datcopctra']['sw_copagos']==1)
        {
            for($i=0;$i<sizeof($_SESSION['ctrpl1']['copserctra']);$i++)
            {
                if($_SESSION['ctrpl1']['datcopctra']['servicio']==$_SESSION['ctrpl1']['copserctra'][$i]['servicio'])
                {
                    $this->salida .= "      <tr class=modulo_list_claro>";
                    $this->salida .= "      <td class=\"modulo_table_list_title\">SERVICIO:";
                    $this->salida .= "      </td>";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      ".$_SESSION['ctrpl1']['copserctra'][$i]['descripcion']."";
                    $this->salida .= "      </td>";
                    $this->salida .= "      </tr>";
                }
            }
        }
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"30%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datcopctra']['grupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">SUBGRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datcopctra']['subgrupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"4\" align=\"center\">";
        $this->salida .= "          <table border=\"1\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $this->salida .= "          <td align=\"left\" width=\"80%\">";
        $this->salida .= "COPAGO";
        $this->salida .= "          </td>";
        $this->salida .= "          <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['datcopctra']['sw_copago']==1)
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\">";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $this->salida .= "          <td align=\"left\" width=\"70%\">";
        $this->salida .= "CUOTA MODERADORA";
        $this->salida .= "          </td>";
        $this->salida .= "          <td align=\"center\" width=\"30%\">";
        if($_SESSION['ctrpl1']['datcopctra']['sw_cuota_moderadora']==1)
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\">";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $this->salida .= "          <td align=\"left\" width=\"70%\">";
        $this->salida .= "NINGUNA";
        $this->salida .= "          </td>";
        $this->salida .= "          <td align=\"center\" width=\"30%\">";
        if($_SESSION['ctrpl1']['datcopctra']['sw_cuota_moderadora']==0
        AND $_SESSION['ctrpl1']['datcopctra']['sw_copago']==0)
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\">";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"70%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"24%\">EXCEPCIONES</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $_SESSION['ctrpl1']['cargocopac']=$this->BuscarCarCopPlanContra(
        $_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['datcopctra']['grupo_tarifario_id'],
        $_SESSION['ctrpl1']['datcopctra']['subgrupo_tarifario_id'],$_SESSION['ctrpl1']['datcopctra']['servicio']);
        $ciclo=sizeof($_SESSION['ctrpl1']['cargocopac']);
        for($i=0;($i<$ciclo);$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargocopac'][$i]['cargo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['cargocopac'][$i]['descripcion']."";
            $this->salida .= "</td>";
            $_POST['cuotas'.$i]=0;
            if($_SESSION['ctrpl1']['cargocopac'][$i]['excepcion']==1)
            {
                if($_SESSION['ctrpl1']['cargocopac'][$i]['sw_copago']==1)
                {
                    $_POST['cuotas'.$i]=1;
                }
                else if($_SESSION['ctrpl1']['cargocopac'][$i]['sw_cuota_moderadora']==1)
                {
                    $_POST['cuotas'.$i]=2;
                }
                else if($_SESSION['ctrpl1']['cargocopac'][$i]['sw_copago']==0
                AND $_SESSION['ctrpl1']['cargocopac'][$i]['sw_cuota_moderadora']==0)
                {
                    $_POST['cuotas'.$i]=3;
                }
            }
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" $color>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"70%\">";
            $this->salida .= "COPAGO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"30%\">";
            if($_POST['cuotas'.$i]==1)
            {
                $this->salida .= "      <input type=\"radio\" name=\"cuotas".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "      <input type=\"radio\" name=\"cuotas".$i."\" value=1>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"70%\">";
            $this->salida .= "CUOTA MODERADORA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"30%\">";
            if($_POST['cuotas'.$i]==2)
            {
                $this->salida .= "      <input type=\"radio\" name=\"cuotas".$i."\" value=2 checked>";
            }
            else
            {
                $this->salida .= "      <input type=\"radio\" name=\"cuotas".$i."\" value=2>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"70%\">";
            $this->salida .= "NINGUNA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"30%\">";
            if($_POST['cuotas'.$i]==3)
            {
                $this->salida .= "      <input type=\"radio\" name=\"cuotas".$i."\" value=3 checked>";
            }
            else
            {
                $this->salida .= "      <input type=\"radio\" name=\"cuotas".$i."\" value=3>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          </table>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','CopagosPlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS COPAGOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraCoCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','CopagosExcePlanContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','CopagosExcePlanContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que establece las semanas para dias de carencia para los grupos y subgrupos tarifarios
    function SemanasPlanContra()//Válida las semanas de cotización
    {
        UNSET($_SESSION['ctrpl1']['semanactra']);
        UNSET($_SESSION['ctrpl1']['datsemctra']);
        UNSET($_SESSION['ctrpl1']['cargosemac']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - SEMANAS PARA DÍAS DE CARENCIA');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarSemanasPlanContra');
        $this->salida .= "  <form name=\"semana1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">SEMANAS POR GRUPOS Y SUBGRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"30%\">GRUPO TARIFARIO</td>";
        $this->salida .= "      <td width=\"35%\">SUBGRUPOS TARIFARIOS</td>";
        $this->salida .= "      <td width=\"13%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"15%\">NÚMERO DE SEMANAS</td>";
        $this->salida .= "      <td width=\"7%\" >DETALLES</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $_SESSION['ctrpl1']['semanactra']=$this->BuscarSemanasPlanContra($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($_SESSION['ctrpl1']['semanactra']);
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                $color="class=modulo_list_claro";
                $j=1;
            }
            else
            {
                $color="class=modulo_list_oscuro";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['semanactra'][$k]['grupo_tarifario_id'])
            {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td height=\"30\">";
                $this->salida .= "".$_SESSION['ctrpl1']['semanactra'][$k]['subgrupo_tarifario_descripcion']."";
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k++;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['semanactra'][$k]['grupo_tarifario_id'])
            {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td height=\"30\">";
                $this->salida .= "".$_SESSION['ctrpl1']['semanactra'][$k]['descripcion']."";
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k++;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['semanactra'][$k]['grupo_tarifario_id'])
            {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td height=\"30\" align=\"center\">";
                $_POST['semana'.$k]=$_SESSION['ctrpl1']['semanactra'][$k]['semanas_cotizadas'];
                if($_POST['sematodoct']<>NULL)
                {
                    $_POST['semana'.$k]=$_POST['sematodoct'];
                }
                else if($_REQUEST['borrarsema']==2)
                {
                    $_POST['semana'.$k]='';
                }
                $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"semana".$k."\" value=\"".$_POST['semana'.$k]."\" maxlength=\"5\" size=\"10\">";
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k++;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['semanactra'][$k]['grupo_tarifario_id'])
            {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td height=\"30\" align=\"center\">";
                if($_SESSION['ctrpl1']['semanactra'][$k]['semanas_cotizadas']<>NULL)
                {
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','SemanasExcePlanContra',
                    array('indicesemana'=>$k)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                }
                else
                {
                    $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
                }
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k++;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $i=$k;
        }
        if(empty($_SESSION['ctrpl1']['semanactra']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"5\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO O SUBGRUPO TARIFARIOS'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"semana2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $this->salida .= "  <br><br><table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA SEMANAS PARA DÍAS DE CARENCIA</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','SemanasPlanContra');
            $this->salida .= "      <form name=\"semana3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"30%\">SEMANAS</td>";
            $this->salida .= "      <td width=\"30%\"></td>";
            $this->salida .= "      <td width=\"40%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"sematodoct\" value=\"".$_POST['sematodoct']."\" maxlength=\"5\" size=\"10\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $accion=ModuloGetURL('app','Contratacion','user','SemanasPlanContra',array('borrarsema'=>2));
            $this->salida .= "      <form name=\"semana4\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"borrar\" value=\"BORRAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que permite crear, modificar o eliminar las excepciones según el cargo de los copagos
    function SemanasExcePlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        if($_SESSION['ctrpl1']['datsemctra']['grupo_tarifario_id']==NULL)
        {
            $_SESSION['ctrpl1']['datsemctra']['grupo_tarifario_id']=$_SESSION['ctrpl1']['semanactra'][$_REQUEST['indicesemana']]['grupo_tarifario_id'];
            $_SESSION['ctrpl1']['datsemctra']['grupo_tarifario_descripcion']=$_SESSION['ctrpl1']['semanactra'][$_REQUEST['indicesemana']]['grupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['datsemctra']['subgrupo_tarifario_id']=$_SESSION['ctrpl1']['semanactra'][$_REQUEST['indicesemana']]['subgrupo_tarifario_id'];
            $_SESSION['ctrpl1']['datsemctra']['subgrupo_tarifario_descripcion']=$_SESSION['ctrpl1']['semanactra'][$_REQUEST['indicesemana']]['subgrupo_tarifario_descripcion'];
            $_SESSION['ctrpl1']['datsemctra']['semanas_cotizadas']=$_SESSION['ctrpl1']['semanactra'][$_REQUEST['indicesemana']]['semanas_cotizadas'];
            UNSET($_SESSION['ctrpl1']['semanactra']);
        }
        UNSET($_SESSION['ctrpl1']['cargosemac']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - SEMANAS PARA DÍAS DE CARENCIA - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarExceSemaPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','SemanasPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"30%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datsemctra']['grupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">SUBGRUPO TARIFARIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datsemctra']['subgrupo_tarifario_descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">NÚMERO DE SEMANAS:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datsemctra']['semanas_cotizadas']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"70%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"24%\">EXCEPCIONES</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $_SESSION['ctrpl1']['cargosemac']=$this->BuscarCarSemPlanContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['datsemctra']['grupo_tarifario_id'],$_SESSION['ctrpl1']['datsemctra']['subgrupo_tarifario_id']);
        $ciclo=sizeof($_SESSION['ctrpl1']['cargosemac']);
        for($i=0;($i<$ciclo);$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargosemac'][$i]['cargo']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['cargosemac'][$i]['descripcion']."";
            $this->salida .= "  </td>";
            if($_SESSION['ctrpl1']['cargosemac'][$i]['excepcion']==1)
            {
                $_POST['semanaex'.$i]=$_SESSION['ctrpl1']['cargosemac'][$i]['semanas_cotizadas'];
            }
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"semanaex".$i."\" value=\"".$_POST['semanaex'.$i]."\" maxlength=\"5\" size=\"10\">";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','SemanasPlanContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LAS SEMANAS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraSeCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','SemanasExcePlanContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','SemanasExcePlanContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function AutorizaPlanContra()//
    {
        /*Variables que vienen de AutoIntePlanContra*/
        UNSET($_SESSION['ctrpl1']['serautintc']);
        UNSET($_SESSION['ctrpl1']['dseautintc']);
        UNSET($_SESSION['ctrpl1']['nivautoinc']);
        UNSET($_SESSION['ctrpl1']['gruautoinc']);
        UNSET($_SESSION['ctrpl1']['tipocauinc']);//indice de excepciones
        UNSET($_SESSION['ctrpl1']['cargoauinc']);
        UNSET($_SESSION['ctrpl1']['incaexainc']);//indice de excepciones cargo
        /*Variables que vienen de AutoExtePlanContra*/
        UNSET($_SESSION['ctrpl1']['serautextc']);
        UNSET($_SESSION['ctrpl1']['dseautextc']);
        UNSET($_SESSION['ctrpl1']['nivautoexc']);
        UNSET($_SESSION['ctrpl1']['gruautoexc']);
        UNSET($_SESSION['ctrpl1']['tipocauexc']);//indice de excepciones
        UNSET($_SESSION['ctrpl1']['cargoauexc']);
        UNSET($_SESSION['ctrpl1']['incaexaexc']);//indice de excepciones cargo
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIONES POR SERVICIOS');
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">AUTORIZACIONES PARA EL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">MENÚ - AUTORIZACIONES</td>";
        $this->salida .= "      </tr>";
        $servicios=$this->MostrarServiciosPlanes2($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($servicios);
        for($i=0;$i<$ciclo;$i++)
        {
            $this->salida .= "  <tr class=\"modulo_list_claro\">";
            $this->salida .= "  <td align=\"center\" width=\"60%\">";
            $this->salida .= "".$servicios[$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"20%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutoIntePlanContra',
            array('serviceleg'=>$servicios[$i]['servicio'],'descrieleg'=>$servicios[$i]['descripcion'])) ."\">
            <img src=\"".GetThemePath()."/images/pautoint.png\" border=\"0\" title=\"AUTORIZACIÓN INTERNA POR SERVICIOS\"></a>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"20%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutoExtePlanContra',
            array('serviceleg'=>$servicios[$i]['servicio'],'descrieleg'=>$servicios[$i]['descripcion'])) ."\">
            <img src=\"".GetThemePath()."/images/pautoext.png\" border=\"0\" title=\"AUTORIZACIÓN EXTERNA POR SERVICIOS\"></a>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"40%\">AUDITORES INTERNOS DEL PLAN</td>";
        $this->salida .= "      <td td width=\"10%\" align=\"center\" class=\"modulo_list_claro\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AuditoresInPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/autorizadores.png\" border=\"0\" title=\"AUDITORES INTERNOS DEL PLAN\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"40%\">AUDITORES EXTERNOS DEL PLAN</td>";
        $this->salida .= "      <td td width=\"10%\" align=\"center\" class=\"modulo_list_claro\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AuditoresExPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/autorizadores.png\" border=\"0\" title=\"AUDITORES EXTERNOS DEL PLAN\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\"><br>";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function AutoIntePlanContra()//
    {
        if($_SESSION['ctrpl1']['serautintc']==NULL)
        {
            $_SESSION['ctrpl1']['serautintc']=$_REQUEST['serviceleg'];
            $_SESSION['ctrpl1']['dseautintc']=$_REQUEST['descrieleg'];
        }
        UNSET($_SESSION['ctrpl1']['nivautoinc']);
        UNSET($_SESSION['ctrpl1']['gruautoinc']);
        UNSET($_SESSION['ctrpl1']['tipocauinc']);
        UNSET($_SESSION['ctrpl1']['cargoauinc']);
        UNSET($_SESSION['ctrpl1']['incaexainc']);//indice de excepciones
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN INTERNA POR SERVICIOS');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarAutoIntePlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutorizaPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">GRUPOS DE CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseautintc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"25%\">GRUPOS CARGOS</td>";
        $this->salida .= "      <td width=\"25%\">TIPOS CARGOS</td>";
        $this->salida .= "      <td width=\"35%\">NIVELES</td>";
        $this->salida .= "      <td width=\"15%\">DETALLES</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['gruautoinc']=$this->BuscarGruposAuInPlanContra($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serautintc']);
        $_SESSION['ctrpl1']['nivautoinc']=$this->BuscarNivelesAteContra();
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['gruautoinc']);
        $ciclo1=sizeof($_SESSION['ctrpl1']['nivautoinc']);
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['gruautoinc'][$i]['des1']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td colspan=\"3\">";
            $this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['gruautoinc'][$i]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$k]['grupo_tipo_cargo'])
            {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td width=\"33%\">";
                $this->salida .= "".$_SESSION['ctrpl1']['gruautoinc'][$k]['des2']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td width=\"47%\">";
                $this->salida .= "      <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                for($m=0;$m<$ciclo1;$m++)
                {
                    $this->salida .= "<td>";
                    $this->salida .= "".$_SESSION['ctrpl1']['nivautoinc'][$m]['descripcion_corta']."";
                    $this->salida .= "</td>";
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $l=$k;
                while($_SESSION['ctrpl1']['gruautoinc'][$k]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$k]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['grupo_tipo_cargo'])
                {
                    if($_SESSION['ctrpl1']['gruautoinc'][$l]['nivel']==NULL)
                    {
                        for($m=0;$m<$ciclo1;$m++)
                        {
                            $this->salida .= "<td>";
                            if($_POST['niveltodo'.$m]<>NULL AND $_POST['niveltodo'.$m]==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'])
                            {
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauinc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel']." checked>";
                            }
                            else
                            {
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauinc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'].">";
                            }
                            $this->salida .= "</td>";
                        }
                        $l++;
                    }
                    else
                    {
                        $n=$l;
                        for($m=0;$m<$ciclo1;$m++)
                        {
                            if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                            $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['tipo_cargo'] AND
                            $_SESSION['ctrpl1']['gruautoinc'][$n]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['grupo_tipo_cargo'])
                            {
                                $this->salida .= "<td>";
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauinc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel']." checked>";
                                $this->salida .= "</td>";
                                $n++;
                            }
                            else if($_POST['niveltodo'.$m]<>NULL AND $_POST['niveltodo'.$m]==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'])
                            {
                                $this->salida .= "<td>";
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauinc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel']." checked>";
                                $this->salida .= "</td>";
                            }
                            else
                            {
                                $this->salida .= "<td>";
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauinc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'].">";
                                $this->salida .= "</td>";
                            }
                        }
                        $l=$n;
                    }
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      </table>";
                $this->salida .= "  </td>";
                $this->salida .= "  <td align=\"center\" width=\"20%\">";
                if($_SESSION['ctrpl1']['gruautoinc'][$k]['servicio']==NULL)
                {
                    $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
                }
                else
                {
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutoInteExPlanContra',
                    array('indcauinc'=>$k)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                }
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k=$l;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $i=$k;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutorizaPlanContra');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"100%\" align=\"center\">";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA LAS AUTORIZACIONES INTERNAS</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','AutoIntePlanContra');
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            for($m=0;$m<$ciclo1;$m++)
            {
                $this->salida .= "      <td width=\"20%\">";
                $this->salida .= "".$_SESSION['ctrpl1']['nivautoinc'][$m]['descripcion_corta']."";
                $this->salida .= "      </td>";
            }
            $this->salida .= "      <td rowspan=\"2\" width=\"20%\" align=\"center\" class=\"modulo_list_claro\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            for($m=0;$m<$ciclo1;$m++)
            {
                $this->salida .= "<td width=\"20%\" align=\"center\">";
                $this->salida .= "<input type=\"checkbox\" name=\"niveltodo".$m."\" value=\"".$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel']."\">";
                $this->salida .= "</td>";
            }
            $this->salida .= "      </tr>";
            $this->salida .= "      </form>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
        }
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function AutoInteExPlanContra()//
    {
        if($_SESSION['ctrpl1']['tipocauinc']==NULL)
        {
            $_SESSION['ctrpl1']['tipocauinc']=$_REQUEST['indcauinc'];//indice del grupo
        }
        UNSET($_SESSION['ctrpl1']['cargoauinc']);
        UNSET($_SESSION['ctrpl1']['incaexainc']);//indice de excepciones
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN INTERNA POR SERVICIOS - EXCEPCIONES');
        $this->salida .= "<script>\n";
        $this->salida .= "function FUNCION(valida){\n";
        $this->salida .= "valida.submit();\n";
        $this->salida .= "}\n";
        $this->salida .= "</script>\n";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutoIntePlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" colspan=\"3\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseautintc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">GRUPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['des1']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">TIPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['des2']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      AUTORIZACIONES";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $ciclo=sizeof($_SESSION['ctrpl1']['nivautoinc']);
        for($m=0;$m<$ciclo;$m++)
        {
            $this->salida .= "      <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['nivautoinc'][$m]['descripcion_corta']."";
            $this->salida .= "      </td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $l=$_SESSION['ctrpl1']['tipocauinc'];
        while($_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['tipo_cargo']
        AND $_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['grupo_tipo_cargo'])
        {
            $n=$_SESSION['ctrpl1']['tipocauinc'];
            for($m=0;$m<$ciclo;$m++)
            {
                if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['grupo_tipo_cargo'])
                {
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                    $this->salida .= "      </td>";
                    $n++;
                }
                else
                {
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                    $this->salida .= "      </td>";
                }
            }
            $l=$n;
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"4%\" >NIVEL</td>";
        $this->salida .= "      <td width=\"30%\">EXCEPCIONES</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['cargoauinc']=$this->BuscarAuInPlanContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['grupo_tipo_cargo'],
        $_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo'],
        $_SESSION['ctrpl1']['serautintc']);
        $excepain=$this->BuscarAuInExPlanContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['grupo_tipo_cargo'],
        $_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo'],
        $_SESSION['ctrpl1']['serautintc']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['cargoauinc']);
        for($i=0;$i<$ciclo;$i++)//$this->limit
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['cargoauinc'][$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargoauinc'][$i]['nivel']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            if($_SESSION['ctrpl1']['cargoauinc'][$i]['excepciones']==0)
            {
                $this->salida .= "<a href=\"".ModuloGetURL('app','Contratacion','user','CrearAuInCargExPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],
                'descrictra'=>$_REQUEST['descrictra'],'indiceainc'=>$i))."\">
                <img src=\"".GetThemePath()."/images/pautoe.png\" border=\"0\"></a>";
            }
            else
            {
                $accion=ModuloGetURL('app','Contratacion','user','ValidarAuInCargExPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],
                'descrictra'=>$_REQUEST['descrictra'],'ictra'=>$i,'cremod'=>2));
                $this->salida .= "          <form name=\"contratacion$i\" action=\"$accion\" method=\"post\">";
                $this->salida .= "          <table width=\"100%\" align=\"center\" border=\"0\">";
                $this->salida .= "          <tr>";
                $this->salida .= "          <td width=\"85%\" align=\"center\">";
                $this->salida .= "              <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
                $_POST['valmaxainc'.$i]=$excepain[$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']]['valor_maximo'];
                $_POST['periocainc'.$i]=$excepain[$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']]['periocidad_dias'];
                $_POST['cantidainc'.$i]=$excepain[$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']]['cantidad'];
                $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                $this->salida .= "              <td width=\"45%\" align=\"center\">";
                $this->salida .= "AUTORIZADO";
                $this->salida .= "              </td>";
                $this->salida .= "              <td width=\"55%\" align=\"center\">";
                $this->salida .= "VALOR MÁXIMO";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr $color>";
                $this->salida .= "              <td align=\"center\">";
                if($excepain[$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']]['sw_autorizado']==1)
                {
                    $this->salida .= "<input type=\"checkbox\" name=\"swautinexc".$i."\" value=1 checked>";
                }
                else
                {
                    $this->salida .= "<input type=\"checkbox\" name=\"swautinexc".$i."\" value=1>";
                }
                $this->salida .= "              </td>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"valmaxainc".$i."\" value=\"".$_POST['valmaxainc'.$i]."\" maxlength=\"13\" size=\"13\">";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                $this->salida .= "              <td width=\"45%\" align=\"center\">";
                $this->salida .= "PERIOCIDAD";
                $this->salida .= "              </td>";
                $this->salida .= "              <td width=\"55%\" align=\"center\">";
                $this->salida .= "CANTIDAD";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr $color>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"periocainc".$i."\" value=\"".$_POST['periocainc'.$i]."\" maxlength=\"5\" size=\"13\">";
                $this->salida .= "              </td>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cantidainc".$i."\" value=\"".$_POST['cantidainc'.$i]."\" maxlength=\"10\" size=\"13\">";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              </table>";
                $this->salida .= "          </td>";
                $this->salida .= "          <td width=\"15%\" align=\"center\">";
                $this->salida .= "              <table width=\"100%\" align=\"center\" border=\"0\" $color>";
                $this->salida .= "              <tr>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<a href=\"JAVASCRIPT:FUNCION(document.contratacion$i);\">";
                $this->salida .= "<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
                $this->salida .= "              </td>";
                $this->salida .= "              </form>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr>";
                $this->salida .= "              <td align=\"center\"><br><br>";
                $this->salida .= "<a href=\"".ModuloGetURL('app','Contratacion','user','EliminarAuInCargExPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra'],
                'idcarainexc'=>$i))."\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              </table>";
                $this->salida .= "          </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          </table>";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutoIntePlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraAiCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','AutoInteExPlanContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutoInteExPlanContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que permite cerar las excepciones a un caso específico
    function CrearAuInCargExPlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        if($_SESSION['ctrpl1']['incaexainc']==NULL)
        {
            $_SESSION['ctrpl1']['incaexainc']=$_REQUEST['indiceainc'];//identificador del cargo elegido
        }
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN INTERNA - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarAuInCargExPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],
        'descrictra'=>$_REQUEST['descrictra'],'ictra'=>1,'cremod'=>1));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" colspan=\"3\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseautintc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">GRUPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['des1']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">TIPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['des2']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      AUTORIZACIONES";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "          <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
        $this->salida .= "          <tr class=\"modulo_table_list_title\">";
        $ciclo=sizeof($_SESSION['ctrpl1']['nivautoinc']);
        for($m=0;$m<$ciclo;$m++)
        {
            $this->salida .= "      <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['nivautoinc'][$m]['descripcion_corta']."";
            $this->salida .= "      </td>";
        }
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $l=$_SESSION['ctrpl1']['tipocauinc'];
        while($_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['tipo_cargo']
        AND $_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['grupo_tipo_cargo'])
        {
            $n=$_SESSION['ctrpl1']['tipocauinc'];
            for($m=0;$m<$ciclo;$m++)
            {
                if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$l]['grupo_tipo_cargo'])
                {
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                    $this->salida .= "</td>";
                    $n++;
                }
                else
                {
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                    $this->salida .= "</td>";
                }
            }
            $l=$n;
        }
        $this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"4%\" >NIVEL</td>";
        $this->salida .= "      <td width=\"30%\">EXCEPCIONES</td>";
        $this->salida .= "      </tr>";
        $i=1;
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "".$_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['cargo']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "".$_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "".$_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['nivel']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "              <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
        $this->salida .= "              <td width=\"45%\" align=\"center\">";
        $this->salida .= "AUTORIZADO";
        $this->salida .= "              </td>";
        $this->salida .= "              <td width=\"55%\" align=\"center\">";
        $this->salida .= "VALOR MÁXIMO";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_list_claro\">";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"checkbox\" name=\"swautinexc".$i."\" value=1>";
        $this->salida .= "              </td>";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"valmaxainc".$i."\" value=\"".$_POST['valmaxainc'.$i]."\" maxlength=\"13\" size=\"13\">";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
        $this->salida .= "              <td width=\"45%\" align=\"center\">";
        $this->salida .= "PERIOCIDAD";
        $this->salida .= "              </td>";
        $this->salida .= "              <td width=\"55%\" align=\"center\">";
        $this->salida .= "CANTIDAD";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_list_claro\">";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"periocainc".$i."\" value=\"".$_POST['periocainc'.$i]."\" maxlength=\"5\" size=\"13\">";
        $this->salida .= "              </td>";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cantidainc".$i."\" value=\"".$_POST['cantidainc'.$i]."\" maxlength=\"10\" size=\"13\">";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutoInteExPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function AutoExtePlanContra()//
    {
        if($_SESSION['ctrpl1']['serautextc']==NULL)
        {
            $_SESSION['ctrpl1']['serautextc']=$_REQUEST['serviceleg'];
            $_SESSION['ctrpl1']['dseautextc']=$_REQUEST['descrieleg'];
        }
        UNSET($_SESSION['ctrpl1']['nivautoexc']);
        UNSET($_SESSION['ctrpl1']['gruautoexc']);
        UNSET($_SESSION['ctrpl1']['tipocauexc']);
        UNSET($_SESSION['ctrpl1']['cargoauexc']);
        UNSET($_SESSION['ctrpl1']['incaexaexc']);//indice de excepciones
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN EXTERNA POR SERVICIOS');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarAutoExtePlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutorizaPlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">GRUPOS DE CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseautextc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"25%\">GRUPOS CARGOS</td>";
        $this->salida .= "      <td width=\"25%\">TIPOS CARGOS</td>";
        $this->salida .= "      <td width=\"35%\">NIVELES</td>";
        $this->salida .= "      <td width=\"15%\">DETALLES</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['gruautoexc']=$this->BuscarGruposAuExPlanContra($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serautextc']);
        $_SESSION['ctrpl1']['nivautoexc']=$this->BuscarNivelesAteContra();
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['gruautoexc']);
        $ciclo1=sizeof($_SESSION['ctrpl1']['nivautoexc']);
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['gruautoexc'][$i]['des1']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td colspan=\"3\">";
            $this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
            $k=$i;
            while($_SESSION['ctrpl1']['gruautoexc'][$i]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$k]['grupo_tipo_cargo'])
            {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td width=\"33%\">";
                $this->salida .= "".$_SESSION['ctrpl1']['gruautoexc'][$k]['des2']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td width=\"47%\">";
                $this->salida .= "      <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                for($m=0;$m<$ciclo1;$m++)
                {
                    $this->salida .= "<td>";
                    $this->salida .= "".$_SESSION['ctrpl1']['nivautoexc'][$m]['descripcion_corta']."";
                    $this->salida .= "</td>";
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $l=$k;
                while($_SESSION['ctrpl1']['gruautoexc'][$k]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$k]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['grupo_tipo_cargo'])
                {
                    if($_SESSION['ctrpl1']['gruautoexc'][$l]['nivel']==NULL)
                    {
                        for($m=0;$m<$ciclo1;$m++)
                        {
                            $this->salida .= "<td>";
                            if($_POST['niveltodo'.$m]<>NULL AND $_POST['niveltodo'.$m]==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'])
                            {
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauexc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel']." checked>";
                            }
                            else
                            {
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauexc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'].">";
                            }
                            $this->salida .= "</td>";
                        }
                        $l++;
                    }
                    else
                    {
                        $n=$l;
                        for($m=0;$m<$ciclo1;$m++)
                        {
                            if(($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                            $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['tipo_cargo'] AND
                            $_SESSION['ctrpl1']['gruautoexc'][$n]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['grupo_tipo_cargo']))
                            {
                                $this->salida .= "<td>";
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauexc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel']." checked>";
                                $this->salida .= "</td>";
                                $n++;
                            }
                            else if($_POST['niveltodo'.$m]<>NULL AND $_POST['niveltodo'.$m]==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'])
                            {
                                $this->salida .= "<td>";
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauexc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel']." checked>";
                                $this->salida .= "</td>";
                            }
                            else
                            {
                                $this->salida .= "<td>";
                                $this->salida .= "<input type=\"checkbox\" name=\"nivauexc".$k.$m."\" value=".$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'].">";
                                $this->salida .= "</td>";
                            }
                        }
                        $l=$n;
                    }
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      </table>";
                $this->salida .= "  </td>";
                $this->salida .= "  <td align=\"center\" width=\"20%\">";
                if($_SESSION['ctrpl1']['gruautoexc'][$k]['servicio']==NULL)
                {
                    $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
                }
                else
                {
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutoExteExPlanContra',
                    array('indcauexc'=>$k)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                }
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k=$l;
            }
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $i=$k;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR AUTORIZACIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutorizaPlanContra');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $this->salida .= "  </table><br><br>";
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA LAS AUTORIZACIONES EXTERNAS</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','AutoExtePlanContra');
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            for($m=0;$m<$ciclo1;$m++)
            {
                $this->salida .= "      <td width=\"20%\">";
                $this->salida .= "".$_SESSION['ctrpl1']['nivautoexc'][$m]['descripcion_corta']."";
                $this->salida .= "      </td>";
            }
            $this->salida .= "      <td rowspan=\"2\" width=\"20%\" align=\"center\" class=\"modulo_list_claro\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            for($m=0;$m<$ciclo1;$m++)
            {
                $this->salida .= "<td width=\"20%\" align=\"center\">";
                $this->salida .= "<input type=\"checkbox\" name=\"niveltodo".$m."\" value=\"".$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel']."\">";
                $this->salida .= "</td>";
            }
            $this->salida .= "      </tr>";
            $this->salida .= "      </form>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function AutoExteExPlanContra()//
    {
        if($_SESSION['ctrpl1']['tipocauexc']==NULL)
        {
            $_SESSION['ctrpl1']['tipocauexc']=$_REQUEST['indcauexc'];//indice del grupo
        }
        UNSET($_SESSION['ctrpl1']['cargoauexc']);
        UNSET($_SESSION['ctrpl1']['incaexaexc']);//indice de excepciones
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN EXTERNA POR SERVICIOS - EXCEPCIONES');
        $this->salida .= "<script>\n";
        $this->salida .= "function FUNCION(valida){\n";
        $this->salida .= "valida.submit();\n";
        $this->salida .= "}\n";
        $this->salida .= "</script>\n";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','AutoExtePlanContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" colspan=\"3\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseautextc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">GRUPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['des1']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">TIPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['des2']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      AUTORIZACIONES";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $ciclo=sizeof($_SESSION['ctrpl1']['nivautoexc']);
        for($m=0;$m<$ciclo;$m++)
        {
            $this->salida .= "      <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['nivautoexc'][$m]['descripcion_corta']."";
            $this->salida .= "      </td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $l=$_SESSION['ctrpl1']['tipocauexc'];
        while($_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['tipo_cargo']
        AND $_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['grupo_tipo_cargo'])
        {
            $n=$_SESSION['ctrpl1']['tipocauexc'];
            for($m=0;$m<$ciclo;$m++)
            {
                if($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['grupo_tipo_cargo'])
                {
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                    $this->salida .= "      </td>";
                    $n++;
                }
                else
                {
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                    $this->salida .= "      </td>";
                }
            }
            $l=$n;
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"4%\" >NIVEL</td>";
        $this->salida .= "      <td width=\"30%\">EXCEPCIONES</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['cargoauexc']=$this->BuscarAuExPlanContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['grupo_tipo_cargo'],
        $_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo'],
        $_SESSION['ctrpl1']['serautextc']);
        $excepaex=$this->BuscarAuExExPlanContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['grupo_tipo_cargo'],
        $_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo'],
        $_SESSION['ctrpl1']['serautextc']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['cargoauexc']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['cargoauexc'][$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['cargoauexc'][$i]['nivel']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            if($_SESSION['ctrpl1']['cargoauexc'][$i]['excepciones']==0)
            {
                $this->salida .= "<a href=\"".ModuloGetURL('app','Contratacion','user','CrearAuExCargExPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],
                'descrictra'=>$_REQUEST['descrictra'],'indiceaexc'=>$i))."\">
                <img src=\"".GetThemePath()."/images/pautoe.png\" border=\"0\"></a>";
            }
            else
            {
                $accion=ModuloGetURL('app','Contratacion','user','ValidarAuExCargExPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],
                'descrictra'=>$_REQUEST['descrictra'],'ictra'=>$i,'cremod'=>2));
                $this->salida .= "          <form name=\"contratacion$i\" action=\"$accion\" method=\"post\">";
                $this->salida .= "          <table width=\"100%\" align=\"center\" border=\"0\">";
                $this->salida .= "          <tr>";
                $this->salida .= "          <td width=\"85%\" align=\"center\">";
                $this->salida .= "              <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
                $_POST['valmaxaexc'.$i]=$excepaex[$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']]['valor_maximo'];
                $_POST['periocaexc'.$i]=$excepaex[$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']]['periocidad_dias'];
                $_POST['cantidaexc'.$i]=$excepaex[$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']]['cantidad'];
                $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                $this->salida .= "              <td width=\"45%\" align=\"center\">";
                $this->salida .= "AUTORIZADO";
                $this->salida .= "              </td>";
                $this->salida .= "              <td width=\"55%\" align=\"center\">";
                $this->salida .= "VALOR MÁXIMO";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr $color>";
                $this->salida .= "              <td align=\"center\">";
                if($excepaex[$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']]['sw_autorizado']==1)
                {
                    $this->salida .= "<input type=\"checkbox\" name=\"swautexexc".$i."\" value=1 checked>";
                }
                else
                {
                    $this->salida .= "<input type=\"checkbox\" name=\"swautexexc".$i."\" value=1>";
                }
                $this->salida .= "              </td>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"valmaxaexc".$i."\" value=\"".$_POST['valmaxaexc'.$i]."\" maxlength=\"13\" size=\"13\">";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                $this->salida .= "              <td width=\"45%\" align=\"center\">";
                $this->salida .= "PERIOCIDAD";
                $this->salida .= "              </td>";
                $this->salida .= "              <td width=\"55%\" align=\"center\">";
                $this->salida .= "CANTIDAD";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr $color>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"periocaexc".$i."\" value=\"".$_POST['periocaexc'.$i]."\" maxlength=\"5\" size=\"13\">";
                $this->salida .= "              </td>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cantidaexc".$i."\" value=\"".$_POST['cantidaexc'.$i]."\" maxlength=\"10\" size=\"13\">";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              </table>";
                $this->salida .= "          </td>";
                $this->salida .= "          <td width=\"15%\" align=\"center\">";
                $this->salida .= "              <table width=\"100%\" align=\"center\" border=\"0\" $color>";
                $this->salida .= "              <tr>";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "<a href=\"JAVASCRIPT:FUNCION(document.contratacion$i);\">";
                $this->salida .= "<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
                $this->salida .= "              </td>";
                $this->salida .= "              </form>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr>";
                $this->salida .= "              <td align=\"center\"><br><br>";
                $this->salida .= "<a href=\"".ModuloGetURL('app','Contratacion','user','EliminarAuExCargExPlanContra',
                array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra'],
                'idcaraexexc'=>$i))."\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              </table>";
                $this->salida .= "          </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          </table>";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutoExtePlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A AUTORIZACIÓN EXTERNA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraAeCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','AutoExteExPlanContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutoExteExPlanContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que permite cerar las excepciones a un caso específico
    function CrearAuExCargExPlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        if($_SESSION['ctrpl1']['incaexaexc']==NULL)
        {
            $_SESSION['ctrpl1']['incaexaexc']=$_REQUEST['indiceaexc'];//identificador del cargo elegido
        }
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN EXTERNA - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarAuExCargExPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],
        'descrictra'=>$_REQUEST['descrictra'],'ictra'=>1,'cremod'=>1));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" colspan=\"3\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseautextc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">GRUPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['des1']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"25%\">TIPO CARGO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['des2']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      AUTORIZACIONES";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "          <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
        $this->salida .= "          <tr class=\"modulo_table_list_title\">";
        $ciclo=sizeof($_SESSION['ctrpl1']['nivautoexc']);
        for($m=0;$m<$ciclo;$m++)
        {
            $this->salida .= "      <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['nivautoexc'][$m]['descripcion_corta']."";
            $this->salida .= "      </td>";
        }
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $l=$_SESSION['ctrpl1']['tipocauexc'];
        while($_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['tipo_cargo']
        AND $_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['grupo_tipo_cargo'])
        {
            $n=$_SESSION['ctrpl1']['tipocauexc'];
            for($m=0;$m<$ciclo;$m++)
            {
                if($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$l]['grupo_tipo_cargo'])
                {
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                    $this->salida .= "</td>";
                    $n++;
                }
                else
                {
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                    $this->salida .= "</td>";
                }
            }
            $l=$n;
        }
        $this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >CARGO</td>";
        $this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"4%\" >NIVEL</td>";
        $this->salida .= "      <td width=\"30%\">EXCEPCIONES</td>";
        $this->salida .= "      </tr>";
        $i=1;
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "".$_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['cargo']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "".$_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "".$_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['nivel']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "              <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
        $this->salida .= "              <td width=\"45%\" align=\"center\">";
        $this->salida .= "AUTORIZADO";
        $this->salida .= "              </td>";
        $this->salida .= "              <td width=\"55%\" align=\"center\">";
        $this->salida .= "VALOR MÁXIMO";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_list_claro\">";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"checkbox\" name=\"swautexexc".$i."\" value=1>";
        $this->salida .= "              </td>";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"valmaxaexc".$i."\" value=\"".$_POST['valmaxaexc'.$i]."\" maxlength=\"13\" size=\"13\">";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
        $this->salida .= "              <td width=\"45%\" align=\"center\">";
        $this->salida .= "PERIOCIDAD";
        $this->salida .= "              </td>";
        $this->salida .= "              <td width=\"55%\" align=\"center\">";
        $this->salida .= "CANTIDAD";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_list_claro\">";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"periocaexc".$i."\" value=\"".$_POST['periocaexc'.$i]."\" maxlength=\"5\" size=\"13\">";
        $this->salida .= "              </td>";
        $this->salida .= "              <td align=\"center\">";
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cantidaexc".$i."\" value=\"".$_POST['cantidaexc'.$i]."\" maxlength=\"10\" size=\"13\">";
        $this->salida .= "              </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutoExteExPlanContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función de mantenimiento de los auditores internos de un plan
    function AuditoresInPlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUDITORES INTERNOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">AUDITORIA DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"40%\">USUARIO</td>";
        $this->salida .= "      <td width=\"15%\">EXTENSIÓN</td>";
        $this->salida .= "      <td width=\"20%\">CELULAR</td>";
        $this->salida .= "      <td width=\"15%\" >TIPO AUDITORIA</td>";
        $this->salida .= "      <td width=\"5%\" >ESTA.</td>";
        $this->salida .= "      <td width=\"5%\" >ELIM.</td>";
        $this->salida .= "      </tr>";
        $auditores=$this->BuscarAuditoresInPlan($_SESSION['ctrpla']['planeleg']);
        $j=0;
        $ciclo=sizeof($auditores);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $j=1;
            }
            else
            {
                $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                $j=0;
            }
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['nombre']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['extension']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['celular']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if($auditores[$i]['estado']==1)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
            }
            else if($auditores[$i]['estado']==0)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','EliminarAsignarInContra',array(
            'usuario'=>$auditores[$i]['usuario_id'])) ."\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($auditores))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"5\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN AUDITOR PARA ESTE PLAN'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "      <table border=\"0\" width=\"10%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td colspan=\"5\" align=\"center\"><br>";
        $accion=ModuloGetURL('app','Contratacion','user','AsignarAuditorInContra');
        $this->salida .= "      <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"ASIGNAR\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutorizaPlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que asigna los auditores internos a un plan específico
    function AsignarAuditorInContra()//Válida la asignación de los auditores seleccionados al plan
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUDITORES INTERNOS');
        $auditores=$this->BuscarAuditoresInternos($_SESSION['contra']['empresa']);
        $accion=ModuloGetURL('app','Contratacion','user','ValidarAsignarInContra',array('total'=>sizeof($auditores)));
        $this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">AUDITORES INTERNOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"40%\">USUARIO</td>";
        $this->salida .= "      <td width=\"15%\">EXTENSIÓN</td>";
        $this->salida .= "      <td width=\"20%\">CELULAR</td>";
				$this->salida .= "      <td width=\"15%\">TIPO AUDITORIA</td>";
        $this->salida .= "      <td width=\"5%\">ESTA.</td>";
        $this->salida .= "      <td width=\"5%\">ASIG.</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $ciclo=sizeof($auditores);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $j=1;
            }
            else
            {
                $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                $j=0;
            }
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['nombre']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['extension']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['celular']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['descripcion']."";
            $this->salida .= "</td>";
						$this->salida .= "      <input type=\"hidden\" name=\"tipo_auditoria".$i."\" value=\"".$auditores[$i]['tipo_auditoria_id']."\">";
            $this->salida .= "<td align=\"center\">";
            if($auditores[$i]['estado']==1)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
            }
            else if($auditores[$i]['estado']==0)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if(!empty($_POST['asignar'.$i]))
            {
                $this->salida .= "<input type=\"checkbox\" name=\"asignar".$i."\" value=\"".$auditores[$i]['usuario_id']."\" checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"asignar".$i."\" value=\"".$auditores[$i]['usuario_id']."\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($auditores))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"5\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN AUDITOR'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','AuditoresInPlanContra');
        $this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función de mantenimiento de los auditores externos de un plan
    function AuditoresExPlanContra()//Válida los cambios, elimina, guarda o modifica
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUDITORES EXTERNOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">AUDITORIA DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"30%\">USUARIO</td>";
        $this->salida .= "      <td width=\"12%\">TELEFÓNOS</td>";
        $this->salida .= "      <td width=\"12%\">CELULAR</td>";
        $this->salida .= "      <td width=\"36%\">CLIENTE</td>";
        $this->salida .= "      <td width=\"5%\" >ESTA.</td>";
        $this->salida .= "      <td width=\"5%\" >ELIM.</td>";
        $this->salida .= "      </tr>";
        $auditores=$this->BuscarAuditoresExPlan($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpla']['tidteleg'],$_SESSION['ctrpla']['terceleg']);
        $j=0;
        $ciclo=sizeof($auditores);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $j=1;
            }
            else
            {
                $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                $j=0;
            }
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['nombre']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['telefonos']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['celular']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['tipo_id_tercero']."".' - '."".$auditores[$i]['tercero_id']."".' - '."".$auditores[$i]['nombre_tercero']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if($auditores[$i]['estado']==1)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
            }
            else if($auditores[$i]['estado']==0)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','EliminarAsignarExContra',array(
            'usuario'=>$auditores[$i]['usuario_id'])) ."\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($auditores))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"6\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN AUDITOR PARA ESTE PLAN'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "      <table border=\"0\" width=\"10%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td align=\"center\"><br>";
        $accion=ModuloGetURL('app','Contratacion','user','AsignarAuditorExContra');
        $this->salida .= "      <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"ASIGNAR AUDITORES\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','AutorizaPlanContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A AUTORIZACIÓN\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Función que asigna los auditores internos a un plan específico
    function AsignarAuditorExContra()//Válida la asignación de los auditores seleccionados al plan
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUDITORES EXTERNOS');
        $auditores=$this->BuscarAuditoresExternos2($_SESSION['contra']['empresa'],$_SESSION['ctrpla']['planeleg']);
        $accion=ModuloGetURL('app','Contratacion','user','ValidarAsignarExContra',array('total'=>sizeof($auditores)));
        $this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">AUDITORES EXTERNOS DEL PLAN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"25%\">USUARIO</td>";
        $this->salida .= "      <td width=\"20%\">TELEFÓNOS</td>";
        $this->salida .= "      <td width=\"20%\">CELULAR</td>";
        $this->salida .= "      <td width=\"25%\">CLIENTE</td>";
        $this->salida .= "      <td width=\"5%\" >ESTA.</td>";
        $this->salida .= "      <td width=\"5%\" >ASIG.</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $ciclo=sizeof($auditores);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $j=1;
            }
            else
            {
                $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                $j=0;
            }
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['nombre']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['telefonos']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['celular']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$auditores[$i]['tipo_id_tercero']."".' - '."".$auditores[$i]['tercero_id']."".' - '."".$auditores[$i]['nombre_tercero']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if($auditores[$i]['estado']==1)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
            }
            else if($auditores[$i]['estado']==0)
            {
                $this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            if(!empty($_POST['asignar'.$i]))
            {
                $this->salida .= "<input type=\"checkbox\" name=\"asignar".$i."\" value=\"".$auditores[$i]['usuario_id']."\" checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"asignar".$i."\" value=\"".$auditores[$i]['usuario_id']."\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($auditores))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"6\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN AUDITOR'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','AuditoresExPlanContra');
        $this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TarifarioSerAutoInveContra()//
    {
        UNSET($_SESSION['ctrpl1']['serinvautc']);
        UNSET($_SESSION['ctrpl1']['deseinautc']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INSUMOS Y MEDICAMENTOS - AUTORIZACIONES');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">MENÚ - INSUMOS Y MEDICAMENTOS</td>";
        $this->salida .= "      </tr>";
        $servicios=$this->MostrarServiciosPlanes2($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($servicios);
        for($i=0;$i<$ciclo;$i++)
        {
            $this->salida .= "  <tr class=\"modulo_list_claro\">";
            $this->salida .= "  <td align=\"center\" width=\"60%\">";
            $this->salida .= "".$servicios[$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"40%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioAutoInveContra',
            array('serviceleg'=>$servicios[$i]['servicio'],'descrieleg'=>$servicios[$i]['descripcion'])) ."\">
            <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\" title=\"AUTORIZACIONES - INSUMOS Y MEDICAMENTOS\"></a>";//ADICIONAR Y/O ELIMINAR
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TarifarioAutoInveContra()//
    {
        if($_SESSION['ctrpl1']['serinvautc']==NULL)
        {
            $_SESSION['ctrpl1']['serinvautc']=$_REQUEST['serviceleg'];
            $_SESSION['ctrpl1']['deseinautc']=$_REQUEST['descrieleg'];
        }
        UNSET($_SESSION['ctrpl1']['grupoinauc']);//grupos
        UNSET($_SESSION['ctrpl1']['datautinvc']);//
        UNSET($_SESSION['ctrpl1']['codigoiauc']);//borra los codigos de los medicamentos
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INSUMOS Y MEDICAMENTOS - AUTORIZACIONES');//TARIFARIO INVENTARIO
        $accion=ModuloGetURL('app','Contratacion','user','ValidarTarifarioAutoInveContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioSerAutoInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">TARIFARIO POR GRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">SERVICIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['deseinautc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"38%\">GRUPOS DE CONTRATACIÓN</td>";
        $this->salida .= "      <td width=\"10%\">SEMANAS</td>";
        $this->salida .= "      <td width=\"10%\">CANT. MAX</td>";
        $this->salida .= "      <td width=\"14%\">VAL. MAX. UNIDAD</td>";
        $this->salida .= "      <td width=\"14%\">VAL. MAX. CUENTA</td>";
        $this->salida .= "      <td width=\"4%\" >IN</td>";
        $this->salida .= "      <td width=\"4%\" >EX</td>";
        $this->salida .= "      <td width=\"6%\" >DETAL.</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['grupoinauc']=$this->BuscarGruposAutoInveContra(
        $_SESSION['contra']['empresa'],$_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serinvautc']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['grupoinauc']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $_POST['seminvctra'.$i]=$_SESSION['ctrpl1']['grupoinauc'][$i]['semanas_cotizadas'];
            if($_POST['seintodoct']<>NULL)//if(!empty($_POST['seintodoct']))
            {
                $_POST['seminvctra'.$i]=$_POST['seintodoct'];
            }
            else if($_REQUEST['borrarauin']==2)
            {
                $_POST['seminvctra'.$i]='';
            }
            $_POST['caninvctra'.$i]=$_SESSION['ctrpl1']['grupoinauc'][$i]['cantidad_max'];
            if($_POST['caintodoct']<>NULL)
            {
                $_POST['caninvctra'.$i]=$_POST['caintodoct'];
            }
            else if($_REQUEST['borrarauin']==2)
            {
                $_POST['caninvctra'.$i]='';
            }
            $_POST['vmuinvctra'.$i]=$_SESSION['ctrpl1']['grupoinauc'][$i]['valor_max_unidad'];
            if($_POST['vunitodoct']<>NULL)
            {
                $_POST['vmuinvctra'.$i]=$_POST['vunitodoct'];
            }
            else if($_REQUEST['borrarauin']==2)
            {
                $_POST['vmuinvctra'.$i]='';
            }
            $_POST['vmcinvctra'.$i]=$_SESSION['ctrpl1']['grupoinauc'][$i]['valor_max_cuenta'];
            if($_POST['vcuitodoct']<>NULL)
            {
                $_POST['vmcinvctra'.$i]=$_POST['vcuitodoct'];
            }
            else if($_REQUEST['borrarauin']==2)
            {
                $_POST['vmcinvctra'.$i]='';
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['grupoinauc'][$i]['des1']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"seminvctra".$i."\" value=\"".$_POST['seminvctra'.$i]."\" maxlength=\"5\" size=\"9\">";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"caninvctra".$i."\" value=\"".$_POST['caninvctra'.$i]."\" maxlength=\"5\" size=\"9\">";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"vmuinvctra".$i."\" value=\"".$_POST['vmuinvctra'.$i]."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"vmcinvctra".$i."\" value=\"".$_POST['vmcinvctra'.$i]."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $_POST['autoint'.$i]=$_SESSION['ctrpl1']['grupoinauc'][$i]['requiere_autorizacion_int'];
            if($_POST['autoint'.$i]==1 OR $_POST['auintodoct']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoint".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoint".$i."\" value=1>";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $_POST['autoext'.$i]=$_SESSION['ctrpl1']['grupoinauc'][$i]['requiere_autorizacion_ext'];
            if($_POST['autoext'.$i]==1 OR $_POST['auextodoct']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoext".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoext".$i."\" value=1>";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  <td width=\"9%\" align=\"center\">";
            if($_SESSION['ctrpl1']['grupoinauc'][$i]['semanas_cotizadas']<>NULL)
            {
                $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TariExceAutoInveContra',
                array('indiceautinc'=>$i)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
            }
            else
            {
                $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR TARIFARIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TarifarioSerAutoInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $this->salida .= "  <br><br><table border=\"0\" width=\"70%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA INSUMOS Y MEDICAMENTOS - AUTORIZACIONES</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioAutoInveContra');
            $this->salida .= "      <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"14%\">SEMANAS</td>";
            $this->salida .= "      <td width=\"14%\">CANT. MAX</td>";
            $this->salida .= "      <td width=\"17%\">VAL. MAX. UNIDAD</td>";
            $this->salida .= "      <td width=\"17%\">VAL. MAX. CUENTA</td>";
            $this->salida .= "      <td width=\"5%\" >IN</td>";
            $this->salida .= "      <td width=\"5%\" >EX</td>";
            $this->salida .= "      <td width=\"14%\"></td>";
            $this->salida .= "      <td width=\"14%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"seintodoct\" value=\"".$_POST['seintodoct']."\" maxlength=\"5\" size=\"9\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"caintodoct\" value=\"".$_POST['caintodoct']."\" maxlength=\"5\" size=\"9\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"vunitodoct\" value=\"".$_POST['vunitodoct']."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"vcuitodoct\" value=\"".$_POST['vcuitodoct']."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['auintodoct']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"auintodoct\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"auintodoct\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['auextodoct']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"auextodoct\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"auextodoct\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"ACEPTAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioAutoInveContra',array('borrarauin'=>2));
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"borrar\" value=\"ELIMINAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TariExceAutoInveContra()//
    {
        if($_SESSION['ctrpl1']['datautinvc']['grupo_contratacion_id']==NULL)
        {
            $_SESSION['ctrpl1']['datautinvc']['grupo_contratacion_id']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['grupo_contratacion_id'];
            $_SESSION['ctrpl1']['datautinvc']['des1']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['des1'];
            $_SESSION['ctrpl1']['datautinvc']['semanas_cotizadas']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['semanas_cotizadas'];
            $_SESSION['ctrpl1']['datautinvc']['cantidad_max']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['cantidad_max'];
            $_SESSION['ctrpl1']['datautinvc']['valor_max_unidad']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['valor_max_unidad'];
            $_SESSION['ctrpl1']['datautinvc']['valor_max_cuenta']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['valor_max_cuenta'];
            $_SESSION['ctrpl1']['datautinvc']['requiere_autorizacion_int']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['requiere_autorizacion_int'];
            $_SESSION['ctrpl1']['datautinvc']['requiere_autorizacion_ext']=$_SESSION['ctrpl1']['grupoinauc'][$_REQUEST['indiceautinc']]['requiere_autorizacion_ext'];
            UNSET($_SESSION['ctrpl1']['grupoinauc']);
        }
        UNSET($_SESSION['ctrpl1']['codigoiauc']);//borra los codigos de los medicamentos
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INSUMOS Y MEDICAMENTOS - AUTORIZACIONES - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarTariExceAutoInveContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioAutoInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">SERVICIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['deseinautc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" colspan=\"5\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datautinvc']['des1']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">SEMANAS:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $semver=$_SESSION['ctrpl1']['datautinvc']['semanas_cotizadas'];
        $this->salida .= "      ".number_format(($semver), 2, '.', '.')."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">CANTIDAD MÁXIMA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datautinvc']['cantidad_max']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">AUTORIZACIÓN INTERNA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['datautinvc']['requiere_autorizacion_int']==1)
        {
            $this->salida .= "SI";
        }
        else
        {
            $this->salida .= "NO";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">VALOR MÁX. UNIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datautinvc']['valor_max_unidad']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">VALOR MÁX CUENTA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datautinvc']['valor_max_cuenta']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">AUTORIZACIÓN EXTERNA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['datautinvc']['requiere_autorizacion_ext']==1)
        {
            $this->salida .= "SI";
        }
        else
        {
            $this->salida .= "NO";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
        $this->salida .= "      <td width=\"27%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"11%\">COSTO</td>";
        $this->salida .= "      <td width=\"11%\">COS. ÚLT. COMPRA</td>";
        $this->salida .= "      <td width=\"11%\">PRECIO VENTA</td>";
        $this->salida .= "      <td width=\"32%\">COBER.</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['codigoiauc']=$this->BuscarTariAutoInveContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['contra']['empresa'],$_SESSION['ctrpl1']['serinvautc'],$_SESSION['ctrpl1']['datautinvc']['grupo_contratacion_id']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['codigoiauc']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoiauc'][$i]['codigo_producto']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoiauc'][$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoiauc'][$i]['costo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoiauc'][$i]['costo_ultima_compra']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoiauc'][$i]['precio_venta']."";
            $this->salida .= "</td>";
            if($_SESSION['ctrpl1']['codigoiauc'][$i]['excepcion']==1)
            {
                $_POST['seminvexc'.$i]=$_SESSION['ctrpl1']['codigoiauc'][$i]['semanas_cotizadas'];
                $_POST['caninvexc'.$i]=$_SESSION['ctrpl1']['codigoiauc'][$i]['cantidad_max'];
                $_POST['autoexintc'.$i]=$_SESSION['ctrpl1']['codigoiauc'][$i]['requiere_autorizacion_int'];
                $_POST['vmuinvexc'.$i]=$_SESSION['ctrpl1']['codigoiauc'][$i]['valor_max_unidad'];
                $_POST['vmcinvexc'.$i]=$_SESSION['ctrpl1']['codigoiauc'][$i]['valor_max_cuenta'];
                $_POST['autoexextc'.$i]=$_SESSION['ctrpl1']['codigoiauc'][$i]['requiere_autorizacion_ext'];
            }
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td width=\"40%\">SEMANAS";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"40%\">CANTIDAD MÁX.";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"20%\">AUT. INT.";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td>";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"seminvexc".$i."\" value=\"".$_POST['seminvexc'.$i]."\" maxlength=\"5\" size=\"8\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td>";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"caninvexc".$i."\" value=\"".$_POST['caninvexc'.$i]."\" maxlength=\"5\" size=\"8\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['autoexintc'.$i]==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoexintc".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoexintc".$i."\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td width=\"40%\">VALOR MÁX CUENTA";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"40%\">VALOR MÁX. UNIDAD";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"20%\">AUT. EXT.";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td>";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"vmuinvexc".$i."\" value=\"".$_POST['vmuinvexc'.$i]."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td>";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"vmcinvexc".$i."\" value=\"".$_POST['vmcinvexc'.$i]."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['autoexextc'.$i]==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoexextc".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"autoexextc".$i."\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($_SESSION['ctrpl1']['codigoiauc']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"6\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRARÓN PRODUCTOS'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TarifarioAutoInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER   AL   TARIFARIO\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraTiaCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','TariExceAutoInveContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TariExceAutoInveContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TarifarioSerCopaInveContra()//
    {
        UNSET($_SESSION['ctrpl1']['serinvcopc']);
        UNSET($_SESSION['ctrpl1']['deseincopc']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INSUMOS Y MEDICAMENTOS - COPAGOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">MENÚ - INSUMOS Y MEDICAMENTOS</td>";
        $this->salida .= "      </tr>";
        $servicios=$this->MostrarServiciosPlanes2($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($servicios);
        for($i=0;$i<$ciclo;$i++)
        {
            $this->salida .= "  <tr class=\"modulo_list_claro\">";
            $this->salida .= "  <td align=\"center\" width=\"60%\">";
            $this->salida .= "".$servicios[$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"40%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioCopaInveContra',
            array('serviceleg'=>$servicios[$i]['servicio'],'descrieleg'=>$servicios[$i]['descripcion'])) ."\">
            <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\" title=\"INSUMOS Y MEDICAMENTOS - COPAGOS\"></a>";//ADICIONAR Y/O ELIMINAR
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

		//PARAMETRIZACIÓN HABITACIONES
    function ParametrizacionHabitaciones()
    { 
        if($_SESSION['ctrpl1']['serinvcopc']==NULL)
        {
            $_SESSION['ctrpl1']['serinvcopc']=$_REQUEST['serviceleg'];
            $_SESSION['ctrpl1']['deseincopc']=$_REQUEST['descrieleg'];
        }
        UNSET($_SESSION['ctrpl1']['grupoincoc']);//grupos
        UNSET($_SESSION['ctrpl1']['datcopinvc']);
        UNSET($_SESSION['ctrpl1']['codigoicoc']);//borra los codigos de los medicamentos
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAMETRIZACIÓN DE HABITACIONES');//TARIFARIO INVENTARIO
        $accion=ModuloGetURL('app','Contratacion','user','ValidarParametrosHabitaciones');
				$this->salida .= "<SCRIPT language=\"javascript\" >";
				$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, tar, car, tarcar, frm){\n";
				$this->salida .= "\n";
				$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
				//$this->salida .= "alert('hola');\n";
				$this->salida .= "var nameTarifarioId=tar+'_id';\n";
				$this->salida .= "var nameCargoId=car+'_id';\n";
				$this->salida .= "var servicio=document.getElementById(nameTarifarioId).value;\n";
				$this->salida .= "var departam=document.getElementById(nameCargoId).value;\n";
				//$this->salida .= "alert(servicio + ' '+departam);\n";
				//$this->salida .= "alert(servicio) ;\n";
				$this->salida .= "var url2 = url+'?tarifario='+tar+'&cargo='+car+'&taricargo='+tarcar+'&servicio='+servicio+'&departam='+departam;\n";
				$this->salida .= "var rems = window.open(url2, nombre, str);\n";
				$this->salida .= "if (rems != null) {\n";
				$this->salida .= "   if (rems.opener == null) {\n";
				$this->salida .= "       rems.opener = self;\n";
				$this->salida .= "   }\n";
				$this->salida .= "}\n";
				$this->salida .= "}\n";
				$this->salida .= "function abrirVentanaClass2(nombre, url, ancho, altura, x, frm){\n";
				$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
				$this->salida .= "var url2 = url+'?servicio='+frm.tarifariosplan_id.value+'&departam='+frm.cargosplan_id.value;";
				$this->salida .= "var rems = window.open(url2, nombre, str);\n";
				$this->salida .= "if (rems != null) {\n";
				$this->salida .= "   if (rems.opener == null) {\n";
				$this->salida .= "       rems.opener = self;\n";
				$this->salida .= "   }\n";
				$this->salida .= "}\n";
				$this->salida .= "}\n";
				//******************************
/*				$this->salida .= "function deshabilitar(valor, frm, pj){\n";
				$this->salida .= "	if (valor==-1){\n";
				$this->salida .= "		pj.value='XXX';}\n";
				$this->salida .= "\n";
				$this->salida .= "\n";
				$this->salida .= "}\n";*/
				$this->salida .= "</SCRIPT>";
        $this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">TARIFARIO PARA HABITACIONES</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
				if($this->uno == 1)
				{
					$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "      </table><br>";
				}
				//$_SESSION['ctrpl1']['grupoincoc']=$this->BuscarTiposCamas(
				//$_SESSION['contra']['empresa'],$_SESSION['ctrpla']['planeleg']);
				$_SESSION['ctrpl1']['grupoincoc']=$this->BuscarTiposCamas($_SESSION['contra']['empresa']);
				$_SESSION['ctrpl1']['paramhab']=$this->BuscarParametrosHab($_SESSION['contra']['empresa'],$_SESSION['ctrpla']['planeleg']);
				$ciclo=sizeof($_SESSION['ctrpl1']['grupoincoc']);
				$ciclo2=sizeof($_SESSION['ctrpl1']['paramhab']);
				//$ayuda=$this->TraerTarifas($_POST['ayuda']);
				//$ciclo3=sizeof($ayuda);
				$h=0;
				for($i=0;$i<$ciclo;)
				{
/*						if($j==0)
						{
								$color="class=\"modulo_list_claro\"";
								$j=1;
						}
						else
						{
								$color="class=\"modulo_list_oscuro\"";
								$j=0;
						}*/
//NUEVA FORMA PARAMETROS HABITACIONES

				$l=$i;
while($_SESSION['ctrpl1']['grupoincoc'][$i]['destipo']==$_SESSION['ctrpl1']['grupoincoc'][$l]['destipo'])
{        
				if($h==0)
				{
						$color="class=\"modulo_list_claro\"";
						$h=1;
				}
				else
				{
						$color="class=\"modulo_list_oscuro\"";
						$h=0;
				}
//*********************
//OBJETOS DE LA FORMA
//********************
						//$this->salida .= "  <tr $color >";
	/*				$this->salida .= "  <td>";
						$this->salida .= "".$_SESSION['ctrpl1']['grupoincoc'][$i]['destipo']."";
						$this->salida .= "  </td>";*/
				$this->salida .= "  <tr>";
				$this->salida .= "   <td $color width=\"8%\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
        $this->salida .= "   <td width=\"30%\" align=\"left\">CLASE HOPITALIZACIÓN:</td>";
				$this->salida .= "  <td align=\"left\" $color colspan=\"11\">";
				$this->salida .= "		<label>".$_SESSION['ctrpl1']['grupoincoc'][$i]['destipo']."</label>";
				$this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
        $this->salida .= "   <td width=\"30%\" align=\"left\">INTERNACIÓN :</td>";
				$this->salida .= "  <td align=\"left\" $color colspan=\"11\">";
				$this->salida .= "<label>".$_SESSION['ctrpl1']['grupoincoc'][$l]['desclase']."</label>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  <tr class=\"modulo_table_list_title\">";
				$this->salida .= "   <td width=\"30%\" align=\"left\">CARGO CUPS:</td>";
				//$this->salida .= "  </tr>";
						$cargos=$this->ConsultarCargosCups();
						/*cargo_cups,
						tarifario_id,
						cargo,
						porcentaje,
						valor_lista
						valor_excedente*/
						$k=$i;
				//$this->salida .= "  <tr $color>";
						$this->salida .= "  <td $color align=\"left\" colspan=\"7\">";
						$this->salida .= "      <select name=\"cargocups".$l."\" class=\"select\">";
						$this->salida .= "      <option value=\"-1\" selected>----SELECCIONE----</option>";
						$des="";
						//VERIFICAR SI EXISTE AYUDA
						if(!empty($_REQUEST['ayuda']) AND $_REQUEST['ayuda']<>-1)
						{//ayuda[0]--tarifario, ayuda[1]--tipo_cama_id, ayuda[2]--cargo, ayuda[3]--cargo_cups
							$ayuda=explode(',',$_REQUEST['ayuda']);
							
							for($j=0;$j<sizeof($cargos);$j++)
							{
								if($ayuda[3]==$cargos[$j]['cargo'])
								{
									$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" selected>".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,100)."</option>";
									$des=$cargos[$j]['descripcion'];
								}
								else
									$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" title=\"".$cargos[$j]['descripcion']."\">".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,100)."</option>";
							}
						}
						else
						{
							for($j=0;$j<sizeof($cargos);$j++)
							{
								if($ciclo2<>0)
								{
									$tmp1=false;
									for($k=0;$k<$ciclo2;$k++)
									{ 
										if($cargos[$j]['cargo']==$_SESSION['ctrpl1']['paramhab'][$k]['cargo_cups']
											AND $_SESSION['ctrpl1']['grupoincoc'][$l]['tipo_cama_id']==$_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id']
											//AND $_SESSION['ctrpl1']['paramhab'][$k]['cargo_cups']==$_SESSION['ctrpl1']['grupoincoc'][$i]['cargo']
											AND $_SESSION['ctrpl1']['paramhab'][$k]['plan_id']==$_SESSION['ctrpla']['planeleg'])
										{
												//$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" selected>".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,10)."</option>";
												$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" selected>".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,100)."</option>";
												$des=$cargos[$j]['descripcion'];
												$tmp1=true;;
												//$k=$ciclo2;
										}
/*										else
										{
											$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" title=\"".$cargos[$j]['descripcion']."\">".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,10)."</option>";
										}*/
									}
									if(!$tmp1)
									{
										$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" title=\"".$cargos[$j]['descripcion']."\">".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,100)."</option>";
										$tmp1=false;
									}
								}
								else
								if($cargos[$j]['cargo']==$_POST['cargocups'.$l])
								{
										$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" selected>".$cargos[$j]['cargo'].'-'.substr($cargos[$j]['descripcion'],0,100)."</option>";
										$des=$cargos[$j]['descripcion'];
								}
								else
								{
									$this->salida .="<option value=\"".$cargos[$j]['cargo']."\" title=\"".$cargos[$j]['descripcion']."\">".substr($cargos[$j]['cargo'].'-'.$cargos[$j]['descripcion'],0,100)."</option>";
								}
							}
						}
						$this->salida .= "      </select>";
						//$des=$this->BuscarDesCargo('',$_REQUEST['cargocups'.$i]);
						//if(!empty($des))
						//$this->salida .=" <img title=\"$des\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
						$this->salida .= "  </td>";
						$this->salida .= "  </tr>";
						$this->salida .= "  <tr class=\"modulo_table_list_title\" >";
						$this->salida .= "   <td width=\"30%\" align=\"left\">TARIRAFIO/CARGO FACTURAR:</td>";
						$ruta='app_modules/Contratacion/tarifarioscargos.php';
						$this->salida .= "  <td width=\"3%\" align=\"left\" $color>";
						//$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcober".$i."\" value=\"".$_POST['porcober'.$i]."\" maxlength=\"8\" size=\"8\" >";
						$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiar".$l."\" value=\"sel\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,'tarifario$l','cargo$l','taricargo$l',this.form)\" align=\"right\">";
						$this->salida .= "  </td>";
						$preciolidq=0;
						$this->salida .= "  <td $color>";
						unset($tmp);
						//VERIFICAR SI SE SELECCIONÓ AYUDA
						//***********************
						if(!empty($_REQUEST['ayuda']) AND $_REQUEST['ayuda']<>-1)
						{
							//ayuda[0]--tarifario, ayuda[1]--tipo_cama_id, ayuda[2]--cargo, 
							//ayuda[3]--cargo_cups, ayuda[4]--descripción tarifario
							$ayuda=explode(',',$_REQUEST['ayuda']);
							// 0002,8,213615,S31102,ISS 2000
							$_POST['tarifario'.$l]=$ayuda[4];
							$_POST['tarifario'.$l.'_id']=$ayuda[0];
							$des=$this->TraerTarifas($ayuda[0],$ayuda[2]);
							//$_POST['cargo'.$i]=$des[0][descar];
							$_POST['cargo'.$l]=$ayuda[2];
							$_POST['cargo'.$l.'_id']=$ayuda[2];
						}
						else
						{
							for($k=0;$k<$ciclo2;$k++)
							{ 				//CARGO DE TIPOS CAMAS(BASE)											CARGO BASE DE PLANES_TIPOS_CAMAS
								if($_SESSION['ctrpl1']['grupoincoc'][$l]['tipo_cama_id']==$_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id'])
									{ 
/*										if ($_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']>0)
											$preciolidq=$_SESSION['ctrpl1']['paramhab'][$k]['precio']*(1+$_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']/100);
										else
											$preciolidq=$_SESSION['ctrpl1']['paramhab'][$k]['valor_lista'];*/
										if (!empty($_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']) AND $_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']!=0)
											$preciolidq=$_SESSION['ctrpl1']['paramhab'][$k]['precio']*(1+$_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']/100);
										else
											$preciolidq=$_SESSION['ctrpl1']['paramhab'][$k]['valor_lista'];
										$valorlista=$_SESSION['ctrpl1']['paramhab'][$k]['valor_lista'];
										$unidades=$_SESSION['ctrpl1']['paramhab'][$k]['precio'];
										$tipounidad=$_SESSION['ctrpl1']['paramhab'][$k]['descripcion_corta'];
										$textotitle=$_SESSION['ctrpl1']['paramhab'][$k]['desunidad'];
										//$_POST['porexctra'.$i]=$_SESSION['ctrpl1']['paramhab'][$k]['valor_lista'];
										$_POST['tarifario'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['descripcion'];
										$_POST['tarifario'.$l.'_id']=$_SESSION['ctrpl1']['paramhab'][$k]['tarifario_id'];
										$_POST['cargo'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['cargo'];
										$_POST['cargo'.$l.'_id']=$_SESSION['ctrpl1']['paramhab'][$k]['cargo'];
										$_POST['excedente'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['valor_excedente'];
										//BUSCAR DESCRIPCION DEL TARIFARIO EXCEDENTE
										$_POST['tarifarioexcedente'.$l]=$this->BuscarDesTari($_SESSION['ctrpl1']['paramhab'][$k]['tarifario_excedente']);
										//FIN BUSCAR DESCRIPCION DEL TARIFARIO EXCEDENTE 
										$_POST['tarifarioexcedente'.$l.'_id']=$_SESSION['ctrpl1']['paramhab'][$k]['tarifario_excedente'];
										$_POST['cargoexcedente'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['cargo_excedente'];
										$_POST['cargoexcedente'.$l.'_id']=$_SESSION['ctrpl1']['paramhab'][$k]['cargo_excedente'];
										$_POST['porcentajexcedente'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['porcentaje_excedente'];
										//$_POST['porexctra'.$i]=$_SESSION['ctrpl1']['paramhab'][$k]['porcentaje'];
										//if (!empty($_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']) AND $_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']>0)
										if(!empty($_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']) AND $_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']!=0)
										{ 
											$tmp=1;
											$_POST['porexctra'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['porcentaje'];
										}
										else
										if (!empty($_SESSION['ctrpl1']['paramhab'][$k]['valor_lista']) AND $_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']==0)
										{
											$_POST['porexctra'.$l]=$_SESSION['ctrpl1']['paramhab'][$k]['valor_lista'];
											$tmp=0;
										}
										//CONTROL DE RADIOBUTTON PARA LOS CARGOS EXCEDENTES
										if (!empty($_SESSION['ctrpl1']['paramhab'][$k]['valor_excedente']) AND $_SESSION['ctrpl1']['paramhab'][$k]['valor_excedente']>0)
										{ 
											$tmp2=1;
										}
										else
										if (!empty($_SESSION['ctrpl1']['paramhab'][$k]['tarifario_excedente']) AND !empty($_SESSION['ctrpl1']['paramhab'][$k]['cargo_excedente']))
										{
											$tmp2=0;
										}
										//FIN CONTROL DE RADIOBUTTON PARA LOS CARGOS EXCEDENTES
									}
							}
						}
            $this->salida .= "  <input type=\"hidden\" class=\"input-text\" name=\"tarifario".$l."\" value=\"".$_POST['tarifario'.$l]."\" maxlength=\"30\" size=\"30\" readonly>";
						$this->salida .= "  <input type=\"hidden\" id=\"tarifario".$l."_id\" name=\"tarifario".$l."_id\" value=\"".$_POST['tarifario'.$l.'_id']."\" class=\"input-text\">";
            //$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"posinvctra".$i."\" value=\"".$_POST['posinvctra'.$i]."\" maxlength=\"8\" size=\"8\">";
            //$this->salida .= "%";
            //$this->salida .= "  </td>";
						$des=$this->BuscarDesCargo($_POST['tarifario'.$l.'_id'],$_POST['cargo'.$l.'_id']);
            //$this->salida .= "  <td $color align=\"left\">";
            $this->salida .= "  <input type=\"hidden\" class=\"input-text\" name=\"cargo".$l."\" value=\"".$_POST['cargo'.$l]."-".$des."\" maxlength=\"50\" size=\"50\" readonly>";
						$this->salida.="			<textarea name=\"taricargo".$l."\" cols=\"60\" rows=\"1\" style = \"width:100%\" class=\"textarea\" readonly>".$_POST['tarifario'.$l]."-".$_POST['cargo'.$l]."-".$des."</textarea>";
						$this->salida .= "      <input type=\"hidden\" id=\"cargo".$l."_id\" name=\"cargo".$l."_id\" value=\"".$_POST['cargo'.$l.'_id']."\" class=\"input-text\">";
						//if($_POST['tarifario'.$l.'_id']<>NULL)
							//$this->salida .=" <img title=\"$des\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\" align=\"right\">";
						$this->salida .= "  </td>";
        $this->salida .= "  </tr>";

        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
/*        $this->salida .= "   <td width=\"11%\">CARGO</td>";
        $this->salida .= "   <td width=\"4%\"></td>";*/
        $this->salida .= "   <td width=\"30%\" align=\"left\">UNIDADES:</td>";
//
        $this->salida .= "  <td  $color align=\"center\" width=\"100%\" colspan=\"3\">";
        $this->salida .= "  <table border=\"1\" width=\"100%\" align=\"right\">";
        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
				$this->salida .= "  <td $color align=\"right\" width=\"10%\">";
				$this->salida .= "".FormatoValor($unidades)."";
				$this->salida .= "  </td>";
				$this->salida .= "      <input type=\"hidden\" name=\"valorlista".$l."\" value=\"".$unidades."\" class=\"input-text\">";
        $this->salida .= "   <td width=\"10%\">T.UNIDAD</td>";
				$this->salida .= "  <td $color align=\"center\" width=\"15%\">";
				$this->salida .= "".$tipounidad."";
				if (!empty($tipounidad))
					$this->salida .=" <img title=\"$textotitle\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\" align=\"right\">";
				$this->salida .= "  </td>";
				$this->salida .= "      <input type=\"hidden\" name=\"valorlista".$l."\" value=\"".$valorlista."\" class=\"input-text\">";
	/*        $this->salida .= "  <td align=\"right\">";
				$this->salida .= "".FormatoValor($valorlista)."";
				$this->salida .= "  </td>";*/
	/*			if ($preciolidq==0 AND )
					$preciolidq=$_SESSION['ctrpl1']['paramhab'][$i]['valor_lista'];*/
				$this->salida .= "   <td width=\"10%\">PRECIO LIQ. $</td>";
				$this->salida .= "  <td $color align=\"right\" width=\"10%\">";
				$this->salida .= "".FormatoValor($preciolidq)."";
				$this->salida .= "  </td>";
				$this->salida .= "   <td width=\"15%\">PORCE. // VALOR</td>";
				$this->salida .= "<td $color align=\"center\" width=\"15%\">";
				if (!empty($_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']))
					$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porexctra".$l."\" value=\"".$_SESSION['ctrpl1']['paramhab'][$k]['porcentaje']."\" maxlength=\"9\" size=\"9\">";
				else
					$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porexctra".$l."\" value=\"".$_POST['porexctra'.$l]."\" maxlength=\"9\" size=\"9\">";
				$this->salida .= "</td>";
				$this->salida .= "   <td width=\"1%\">%</td>";
				$this->salida .= "<td $color align=\"center\" width=\"5%\">";
				if ($tmp==1)//PORCENTAJE
					$this->salida .= "<input type=\"radio\" name=\"radioporexctra".$l."\" value=1 checked>";
				else
					$this->salida .= "<input type=\"radio\" name=\"radioporexctra".$l."\" value=1>";

				$this->salida .= "</td>";
				$this->salida .= "   <td width=\"1%\">$</td>";
				$this->salida .= "<td $color align=\"center\"width=\"5%\">";
				if ($tmp==0)//VALOR LISTA
						$this->salida .= "<input type=\"radio\" name=\"radioporexctra".$l."\" value=0 checked>";
				else
						$this->salida .= "<input type=\"radio\" name=\"radioporexctra".$l."\" value=0>";
				$this->salida .= "</td>";
        $this->salida .= " </tr>";
        $this->salida .= "</table>";
				$this->salida .= "</td>";
        $this->salida .= " </tr>";
			//FIN PRIMER TR DEL ROWS
//         $this->salida .= "  <tr class=\"modulo_table_list_title\">";
//         $this->salida .= "   <td width=\"6%\">EXCEDENTE</td>";
//        //$this->salida .= "   <td width=\"6%\">PRECIO LISTA</td>";
//         $this->salida .= "   <td width=\"6%\">Tar. Exced.</td>";
//         $this->salida .= "   <td width=\"6%\">Cargo Exced.</td>";
//         $this->salida .= "   <td width=\"6%\">% Exced.</td>";
//         $this->salida .= "   <td width=\"4%\"></td>";
//         $this->salida .= "  </tr>";

        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
				$this->salida .= "  <td width=\"30%\" align=\"left\">";
				$this->salida .= "  COBRO DE EXCEDENTES";
				$this->salida .= "  </td>";

        $_POST['preciolista'.$l]=FormatoValor($_SESSION['ctrpl1']['paramhab'][$l]['valor_lista']);
        //$this->salida .= "  <tr $color>";
//
				$this->salida .= "  <td $color colspan=\"3\" align=\"left\" width=\"100%\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr $color>";
				$this->salida .= "  <td width=\"2%\" align=\"center\">";
				if ($tmp2==1)//VALOR EXCEDENTE
					$this->salida .= "<input type=\"radio\" name=\"rcobroexce".$l."\" value=1 checked>";
				else
					$this->salida .= "<input type=\"radio\" name=\"rcobroexce".$l."\" value=1>";
				$this->salida .= "  </td>";
				$this->salida .= "  <td  align=\"left\">";
				$this->salida .= "  <b>$</b><input type=\"text\" class=\"input-text\" name=\"excedente".$l."\" value=\"".$_POST['excedente'.$l]."\" maxlength=\"8\" size=\"6\">";
				$this->salida .= "  </td>";
        $this->salida .= "  </tr>";
				//NUEVO PARA EXCEDENTE
        $this->salida .= "  <tr $color>";
				$ruta1='app_modules/Contratacion/tarifarioscargos.php';
				$this->salida .= "  <td  align=\"center\">";
				if ($tmp2==0)//VALOR EXCEDENTE
					$this->salida .= "<input type=\"radio\" name=\"rcobroexce".$l."\" value=0 checked>";
				else
					$this->salida .= "<input type=\"radio\" name=\"rcobroexce".$l."\" value=0>";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiarexcedente".$l."\" value=\"sel\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta1',450,200,0,'tarifarioexcedente$l','cargoexcedente$l','taricargoexce$l',this.form)\" align=\"right\">";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"left\" width=\"45%\">";
				//$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiarexcedente".$l."\" value=\"sel\" onclick=\"abrirVentanaClassExcedente('PARAMETROS','$ruta1',450,200,0,'tarifarioexcedente$l','cargoexcedente$l',this.form)\" align=\"right\">";
				$this->salida .= "  <input type=\"hidden\" class=\"input-text\" name=\"tarifarioexcedente".$l."\" value=\"".$_POST['tarifarioexcedente'.$l]."\" maxlength=\"25\" size=\"20\" readonly>";
				//$this->salida .= "  </td>";

				$this->salida .= "      <input type=\"hidden\" id=\"tarifarioexcedente".$l."_id\" name=\"tarifarioexcedente".$l."_id\" value=\"".$_POST['tarifarioexcedente'.$l.'_id']."\" class=\"input-text\">";

				$desexce=$this->BuscarDesCargo($_POST['tarifarioexcedente'.$l.'_id'],$_POST['cargoexcedente'.$l.'_id']);
				//$this->salida .= "  <td align=\"right\" width=\"45%\">";
				$this->salida.="			<textarea name=\"taricargoexce".$l."\" cols=\"60\" rows=\"1\" style = \"width:100%\" class=\"textarea\" readonly>".$_POST['tarifarioexcedente'.$l]."-".$_POST['cargoexcedente'.$l]."-".$desexce."</textarea>";
				$this->salida .= "  <input type=\"hidden\" class=\"input-text\" name=\"cargoexcedente".$l."\" value=\"".$_POST['cargoexcedente'.$l]."--".$desexce."\" maxlength=\"40\" size=\"40\" readonly>";
				$this->salida .= "  </td>";
				$this->salida .= "      <input type=\"hidden\" id=\"cargoexcedente".$l."_id\" name=\"cargoexcedente".$l."_id\" value=\"".$_POST['cargoexcedente'.$l.'_id']."\" class=\"input-text\">";

				$this->salida .= "  <td align=\"right\" width=\"5%\">";
				$this->salida .= " <input type=\"text\" class=\"input-text\" name=\"porcentajexcedente".$l."\" value=\"".$_POST['porcentajexcedente'.$l]."\" maxlength=\"8\" size=\"8\" align=\"right\"><b>%</b>";
				$this->salida .= "  </td>";

				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
				$this->salida .= "  </td>";
//
        $this->salida .= "  </tr>";
				//FIN NUEVO PARA ECEDENTE	
				//BOTON GUARDAR
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"right\" colspan=\"5\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				//FIN BOTON GUARDAR
				//$this->salida .= "  </tr>";
				$tipounidad='';
				$unidades='';

//**********************
//FIN OBJETOS DE LA FORMA
//************************
        $this->salida .= "      </table>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "&nbsp;";
        $this->salida .= "  </td>";
				$this->salida .= "  </tr>";
			$l++;
		}
		$i=$l;

//FIN NUEVA FORMA PARAMETROS HABITACIONES
   }
        //$this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
/*        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR PARAMETROS\">";
        $this->salida .= "  </td>";*/
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
	//if($_SESSION['ctrpla']['estaeleg']==0)
	//AYUDA PARA HABITACIONES
       // $accion=ModuloGetURL('app','Contratacion','user','ParametrizacionHabitaciones');
       // $this->salida .= "  <form name=\"copagos\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"35%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"40%\" align=\"center\">";
				$this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA HABITACIONES</legend>";
				$accion=ModuloGetURL('app','Contratacion','user','ParametrizacionHabitaciones',array('tarifa'=>$tarifa));
				$this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
				$this->salida .= "      <table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "      <tr class=\"modulo_table_list_title\">";
				$this->salida .= "      <td width=\"80%\">OPCIÓN HABITACIONES</td>";
				$this->salida .= "      <td width=\"20%\"></td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=\"modulo_list_claro\">";
				$this->salida .= "      <td width=\"80%\" align=\"center\">";
				$this->salida .= "      <select name=\"ayuda\" class=\"select\">";
				$this->salida .= "      <option value=\"-1\" selected>--SELECCIONE--</option>";
				$tarifa=$this->TraerTarifas();
		    $ciclo=sizeof($tarifa);
				for($l=0;$l<$ciclo;$l++)
				{
						if($_POST['ayuda'] == $tarifa[$l]['tarifario_id'])//A.tipo_cama_id, A.cargo, A.cargo_cups
						{
								$this->salida .="<option value=\"".$tarifa[$l]['tarifario_id'].','.$tarifa[$l]['tipo_cama_id'].','.$tarifa[$l]['cargo'].','.$tarifa[$l]['cargo_cups'].','.$tarifa[$l]['destari']."\" selected>".$tarifa[$l]['destari']."</option>";
						}
						else
						{
								$this->salida .="<option value=\"".$tarifa[$l]['tarifario_id'].','.$tarifa[$l]['tipo_cama_id'].','.$tarifa[$l]['cargo'].','.$tarifa[$l]['cargo_cups'].','.$tarifa[$l]['destari']."\">".$tarifa[$l]['destari']."</option>";
						}
				}
				$this->salida .= "      </select>";
				$this->salida .= "      </td>";
				$this->salida .= "      <td width=\"20%\" align=\"center\">";
				$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </form>";
				$this->salida .= "      </tr>";
				$this->salida .= "      </table>";
				$this->salida .= "  </fieldset>";
				$this->salida .= "  </td>";
				//ESPACIO PARA LA PARAMETRIZACIÓN DE LAS HAB.
				$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		    $this->salida .= "  <tr>";
    		$this->salida .= "  <td>";
				$this->salida .= "  <fieldset><legend class=\"field\">PARAMETROS LIQUIDACIÓN HABITACIONES</legend>";
				$accion=ModuloGetURL('app','Contratacion','user','GuardarLiquidacionHabitaciones',array('PlanesCargos'=>$datos));
				$this->salida .= "      <form name=\"Formliquidar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "      <tr class=\"modulo_table_list_title\">";
				$this->salida .= "      <td width=\"10%\">OPCIONES</td>";
				$this->salida .= "      <td width=\"90%\">SELECCIÓN</td>";
				$this->salida .= "      </tr>";
				$tarifa2=$this->TipoCleseCama();
				$liqguardadas=$this->TraerLiqGuardadas();
				$ciclo3=sizeof($liqguardadas);
				//tipo_liq_habitacion, tipo_clase_cama_id, plan_id
				$_SESSION['contra']['tarifa']=$tarifa2;
				$_SESSION['contra']['liquidacion']=$liqguardadas;
				$ciclo=sizeof($tarifa2);
				$j=0;
				for($i=0;$i<$ciclo;$i++)
				{
						if($j==0)
						{
								$color="class=\"modulo_list_claro\"";
								$j=1;
						}
						else
						{
								$color="class=\"modulo_list_oscuro\"";
								$j=0;
						}
					$this->salida .= "      <tr $color>";
					$this->salida .= "      <td width=\"20%\" align=\"center\">";
					$this->salida .= "".$tarifa2[$i][tipo_clase_cama_id].'-'.$tarifa2[$i][descripcion]."";
					$this->salida .= "      </td>";
					$this->salida .= "      <td width=\"80%\" align=\"left\">";
					$this->salida .= "      <select name=\"tipocama".$i."\" class=\"select\">";
					$this->salida .= "      <option value=\"-1\" selected>--SELECCIONE--</option>";
					$liquidacion=$this->TiposLiqHab();
					$ciclo2=sizeof($liquidacion);
					unset($desc);
					for($l=0;$l<$ciclo2;$l++)
					{
							if ($ciclo3>0)
							{
								$tmp=false;
								for($x=0;$x<$ciclo3;$x++)
								{
									//$liqguardadas[$x][plan_id]==$_SESSION['ctrpla']['planeleg']
									if ($liqguardadas[$x][plan_id]==$_SESSION['ctrpla']['planeleg']
											AND $tarifa2[$i]['tipo_clase_cama_id']==$liqguardadas[$x]['tipo_clase_cama_id']
											AND $tarifa2[$i]['tipo_clase_cama_id']==$liquidacion[$l]['tipo_clase_cama_id']
											AND $liquidacion[$l]['tipo_clase_cama_id']==$liqguardadas[$x]['tipo_clase_cama_id']
											AND $liquidacion[$l]['tipo_liq_habitacion']==$liqguardadas[$x]['tipo_liq_habitacion']
											)
									{ 
										$this->salida .="<option value=\"".$liqguardadas[$x]['tipo_clase_cama_id'].','.$liqguardadas[$x]['tipo_liq_habitacion']."\" selected>".$liqguardadas[$x]['tipo_clase_cama_id'].'--'.$liqguardadas[$x]['descripcion']."</option>";
										$desc=$liqguardadas[$x]['detalle'];
										$tmp=true;
									}
// 									else
// 									if ($liquidacion[$l]['tipo_clase_cama_id']==$tarifa2[$i][tipo_clase_cama_id]
// 											AND $liqguardadas[$x]['tipo_clase_cama_id']<>$tarifa2[$i][tipo_clase_cama_id]
// 											AND $liquidacion[$l]['tipo_liq_habitacion']<>$liqguardadas[$x]['tipo_liq_habitacion']
// 											)
// 									{
// 											$this->salida .="<option value=\"".$liquidacion[$l]['tipo_clase_cama_id'].','.$liquidacion[$l]['tipo_liq_habitacion']."\" title=\"".$liquidacion[$l]['detalle']."\">".$liquidacion[$l]['descripcion']."</option>";
// 									}

								}
								if (!$tmp AND $liquidacion[$l]['tipo_clase_cama_id']==$tarifa2[$i][tipo_clase_cama_id])
								{
										$this->salida .="<option value=\"".$liquidacion[$l]['tipo_clase_cama_id'].','.$liquidacion[$l]['tipo_liq_habitacion']."\" title=\"".$liquidacion[$l]['detalle']."\">".$liquidacion[$l]['tipo_clase_cama_id'].'--'.$liquidacion[$l]['descripcion']."</option>";
										$tmp=false;
								}
							}
							else
							{
								$tmp=explode(',',$_POST['tipocama'.$i]);
								if($tmp[0] == $liquidacion[$l]['tipo_clase_cama_id'])
								{
										$this->salida .="<option value=\"".$liquidacion[$l]['tipo_clase_cama_id'].','.$liquidacion[$l]['tipo_liq_habitacion']."\" selected>".$liquidacion[$l]['tipo_clase_cama_id'].'--'.$liquidacion[$l]['descripcion']."</option>";
										$desc=$liquidacion[$l]['detalle'];
								}
								else
									if ($liquidacion[$l]['tipo_clase_cama_id']==$tarifa2[$i][tipo_clase_cama_id])
								{ 
									$this->salida .="<option value=\"".$liquidacion[$l]['tipo_clase_cama_id'].','.$liquidacion[$l]['tipo_liq_habitacion']."\" title=\"".$liquidacion[$l]['detalle']."\">".$liquidacion[$l]['tipo_clase_cama_id'].'--'.$liquidacion[$l]['descripcion']."</option>";
								}
							}
					}
					$this->salida .= "      </select>";
					if (!empty($desc))
						$this->salida .=" <img title=\"$desc\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
					$this->salida .= "      </td>";
					$this->salida .= "      </tr>";
				}//FIN FOR
				//$this->salida .= "      </form>";
/////planes_cargos_excedente_habitaciones
				$this->salida .= "      <tr>";
				$this->salida .= "      <td colspan=\"2\">";
				$this->salida .= "&nbsp&nbsp;";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr>";
				$this->salida .= "      <td colspan=\"2\">";
				//$this->salida .= "      <form name=\"Formliquidar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "      <tr class=\"modulo_table_list_title\" colspan=\"3\">";
				$this->salida .= "      <td width=\"39%\" align=\"center\">CARGO EXCEDENTE CUPS</td>";
				$this->salida .= "      <td width=\"27%\" align=\"center\">TARIFARIO EXCEDENTE</td>";
				$this->salida .= "      <td width=\"27%\" align=\"center\">CARGO EXCEDENTE HAB.</td>";
				$this->salida .= "      <td width=\"7%\" align=\"center\"></td>";
				$this->salida .= "      </tr>";
				$datos=$this->ConsultarPlanesCargosExcedente();
				$_SESSION['DATOSLIQUIDACION']=$datos;
				$this->salida .= "      <tr class=\"modulo_list_claro\">";
				$this->salida .= "      <td align=\"center\">";
				$this->salida .= "      <select name=\"cargocupsplan\" class=\"select\">";
				$this->salida .= "      <option value=\"-1\" selected>--SELECCIONE--</option>";
				$cupsinternacion=$this->ConsultarCargosCups();
				$ciclo=sizeof($cupsinternacion);
				for($l=0;$l<$ciclo;$l++)
				{
					if($_POST['cargocupsplan'] == $cupsinternacion[$l]['cargo'])
					{
							$this->salida .="<option value=\"".$cupsinternacion[$l]['cargo']."\" selected>".$cupsinternacion[$l]['cargo'].'--'.substr($cupsinternacion[$l]['descripcion'],0,20)."</option>";
					}
					if ($datos[cargo_cups]==$cupsinternacion[$l]['cargo'])
					{
							$this->salida .="<option value=\"".$cupsinternacion[$l]['cargo']."\" selected>".$cupsinternacion[$l]['cargo'].'--'.substr($cupsinternacion[$l]['descripcion'],0,20)."</option>";
							$desc=$datos[descarcups];
					}
					else
					{
							$this->salida .="<option value=\"".$cupsinternacion[$l]['cargo']."\" title=\"".$cupsinternacion[$l]['descripcion']."\">".$cupsinternacion[$l]['cargo'].'--'.substr($cupsinternacion[$l]['descripcion'],0,20)."</option>";
					}
				}
				$this->salida .= "      </select>";
				if (!empty($desc))
					$this->salida .=" <img title=\"$desc\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
				$this->salida .= "      </td>";
				if (!empty($datos[destari]) AND !empty($datos[descar]))
				{ 
					$_POST['tarifariosplan']=$datos[tarifario_id].'-'.$datos[destari];
					$_POST['tarifariosplan_id']=$datos[tarifario_id];
					$_POST['cargosplan']=$datos[cargo];
					$_POST['cargosplan_id']=$datos[cargo];
					$desctari=$datos[destari];
					$descar=$datos[descar];
				}
				$this->salida .= "      <td align=\"center\">";
				$this->salida .= "			<input type=\"text\" class=\"input-text\" name=\"tarifariosplan\" value=\"".$_POST['tarifariosplan']."\" maxlength=\"12\" size=\"12\" readonly>";
				if (!empty($desctari))
					$this->salida .=" <img title=\"$desctari\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
				$this->salida .= "      <input type=\"hidden\" name=\"tarifariosplan_id\" value=\"".$_POST['tarifariosplan_id']."\">";
				$this->salida .= "      </td>";
				$this->salida .= "      <td align=\"center\">";
				$this->salida .= "			<input type=\"text\" class=\"input-text\" name=\"cargosplan\" value=\"".$_POST['cargosplan']."\" maxlength=\"12\" size=\"12\" readonly>";
				if (!empty($descar))
					$this->salida .=" <img title=\"$descar\" src=\"".GetThemePath()."/images/infor.png\" border=\"0\">";
				$this->salida .= "      <input type=\"hidden\" name=\"cargosplan_id\" value=\"".$_POST['cargosplan_id']."\">";
				$this->salida .= "      </td>";
				$this->salida .= "      <td>";
				$ruta2='app_modules/Contratacion/cargosinternacion.php';																										//nombre, url, ancho, altura, x, tar, car, frm
				$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"sel\" onclick=\"abrirVentanaClass2('PARAMETROS','$ruta2',450,200,0,this.form)\" align=\"right\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      </table>";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
/////planes_cargos_excedente_habitaciones

				$this->salida .= "      <tr>";
				$this->salida .= "      <td width=\"100%\" align=\"center\" colspan=\"2\">";
				$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"GUARDAR\">";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
				$this->salida .= "      </form>";
				$this->salida .= "    </table>";
				$this->salida .= "      </form>";
				$this->salida .= "  </fieldset>";
				$this->salida .= "  </td>";
				//FIN ESPACIO PARA LA PARAMETRIZACIÓN DE LAS HAB.
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
//FIN AYUDA PARA LAS HABITACIONES
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    //FIN PARAMETRIZACION HABITACIONES

    function TarifarioCopaInveContra()//
    {
        if($_SESSION['ctrpl1']['serinvcopc']==NULL)
        {
            $_SESSION['ctrpl1']['serinvcopc']=$_REQUEST['serviceleg'];
            $_SESSION['ctrpl1']['deseincopc']=$_REQUEST['descrieleg'];
        }
        UNSET($_SESSION['ctrpl1']['grupoincoc']);//grupos
        UNSET($_SESSION['ctrpl1']['datcopinvc']);
        UNSET($_SESSION['ctrpl1']['codigoicoc']);//borra los codigos de los medicamentos
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INSUMOS Y MEDICAMENTOS - COPAGOS');//TARIFARIO INVENTARIO
        $accion=ModuloGetURL('app','Contratacion','user','ValidarTarifarioCopaInveContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioSerCopaInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">TARIFARIO POR GRUPOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">SERVICIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['deseincopc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"28%\">GRUPOS DE CONTRATACIÓN</td>";
        $this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
        $this->salida .= "      <td width=\"10%\">COBERTURA</td>";
        $this->salida .= "      <td width=\"10%\">PORCENTAJE NO POS AUTORIZADO</td>";
        $this->salida .= "      <td width=\"6%\" >DES.</td>";
        $this->salida .= "      <td width=\"30%\">COPAGOS</td>";
        $this->salida .= "      <td width=\"6%\" >DETAL.</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['grupoincoc']=$this->BuscarGruposCopaInveContra(
        $_SESSION['contra']['empresa'],$_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serinvcopc']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['grupoincoc']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $_POST['porinvctra'.$i]=$_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje'];
            if($_POST['pointodoct']<>NULL)
            {
                $_POST['porinvctra'.$i]=$_POST['pointodoct'];
            }
            else if($_REQUEST['borrarcoin']==2)
            {
                $_POST['porinvctra'.$i]='';
            }
            $_POST['cobinvctra'.$i]=$_SESSION['ctrpl1']['grupoincoc'][$i]['por_cobertura'];
            if($_POST['cointodoct']<>NULL)
            {
                $_POST['cobinvctra'.$i]=$_POST['cointodoct'];
            }
            else if($_REQUEST['borrarcoin']==2)
            {
                $_POST['cobinvctra'.$i]='';
            }
            $_POST['posinvctra'.$i]=$_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje_nopos_autorizado'];
            if($_POST['psintodoct']<>NULL)
            {
                $_POST['posinvctra'.$i]=$_POST['psintodoct'];
            }
            else if($_REQUEST['borrarcoin']==2)
            {
                $_POST['posinvctra'.$i]='';
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td>";
            $this->salida .= "".$_SESSION['ctrpl1']['grupoincoc'][$i]['des1']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porinvctra".$i."\" value=\"".$_POST['porinvctra'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"cobinvctra".$i."\" value=\"".$_POST['cobinvctra'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"posinvctra".$i."\" value=\"".$_POST['posinvctra'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $_POST['desinvctra'.$i]=$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_descuento'];
            if($_POST['desinvctra'.$i]==1 OR $_POST['deintodoct']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"desinvctra".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"desinvctra".$i."\" value=1>";
            }
            $this->salida .= "  </td>";
            if($_SESSION['ctrpl1']['grupoincoc'][$i]['sw_copago']==1)
            {
                $_POST['cuotas'.$i]=1;
            }
            else if($_SESSION['ctrpl1']['grupoincoc'][$i]['sw_cuota_moderadora']==1)
            {
                $_POST['cuotas'.$i]=2;
            }
            else if($_SESSION['ctrpl1']['grupoincoc'][$i]['sw_copago']<>NULL
            AND $_SESSION['ctrpl1']['grupoincoc'][$i]['sw_cuota_moderadora']<>NULL
            AND $_SESSION['ctrpl1']['grupoincoc'][$i]['sw_copago']<>1
            AND $_SESSION['ctrpl1']['grupoincoc'][$i]['sw_cuota_moderadora']<>1)
            {
                $_POST['cuotas'.$i]=3;
            }
            if($_POST['cuotastodo']<>NULL)
            {
                $_POST['cuotas'.$i]=$_POST['cuotastodo'];
            }
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"left\" width=\"75%\">";
            $this->salida .= "COPAGO";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"25%\">";
            if($_POST['cuotas'.$i]==1)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotas".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotas".$i."\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"left\" width=\"75%\">";
            $this->salida .= "CUOTA MODERADORA";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"25%\">";
            if($_POST['cuotas'.$i]==2)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotas".$i."\" value=2 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotas".$i."\" value=2>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"left\" width=\"75%\">";
            $this->salida .= "NINGUNA";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"25%\">";
            if($_POST['cuotas'.$i]==3)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotas".$i."\" value=3 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotas".$i."\" value=3>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            if($_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje']<>NULL)
            {
                $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TariExceCopaInveContra',
                array('indicecopinc'=>$i)) ."\"><img title=\"EXCEPCIONES\" src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
            }
            else
            {
                $this->salida .= "<img title=\"SIN CONTRATACIÓN\" src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
            }
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR TARIFARIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TarifarioSerCopaInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        if($_SESSION['ctrpla']['estaeleg']==0)
        {
            $this->salida .= "  <br><br><table border=\"0\" width=\"80%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA INSUMOS Y MEDICAMENTOS - AUTORIZACIONES</legend>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioCopaInveContra');
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"12%\">PORCENTAJE</td>";
            $this->salida .= "      <td width=\"12%\">COBERTURA</td>";
            $this->salida .= "      <td width=\"12%\">PORCENTAJE NO POS AUTORIZADO</td>";
            $this->salida .= "      <td width=\"6%\" >DES.</td>";
            $this->salida .= "      <td width=\"34%\">COPAGOS</td>";
            $this->salida .= "      <td width=\"12%\"></td>";
            $this->salida .= "      <td width=\"12%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"pointodoct\" value=\"".$_POST['pointodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cointodoct\" value=\"".$_POST['cointodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"psintodoct\" value=\"".$_POST['psintodoct']."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            if($_POST['deintodoct']==1)
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"deintodoct\" value=1 checked>";
            }
            else
            {
                $this->salida .= "  <input type=\"checkbox\" name=\"deintodoct\" value=1>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"75%\">";
            $this->salida .= "COPAGO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"25%\">";
            if($_POST['cuotastodo']==1)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotastodo\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotastodo\" value=1>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"75%\">";
            $this->salida .= "CUOTA MODERADORA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"25%\">";
            if($_POST['cuotastodo']==2)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotastodo\" value=2 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotastodo\" value=2>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"75%\">";
            $this->salida .= "NINGUNA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"25%\">";
            if($_POST['cuotastodo']==3)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotastodo\" value=3 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuotastodo\" value=3>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          </table>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"ACEPTAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $accion=ModuloGetURL('app','Contratacion','user','TarifarioCopaInveContra',array('borrarcoin'=>2));
            $this->salida .= "      <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <td align=\"center\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"borrar\" value=\"ELIMINAR\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function TariExceCopaInveContra()//
    {
        if($_SESSION['ctrpl1']['datcopinvc']['grupo_contratacion_id']==NULL)
        {
            $_SESSION['ctrpl1']['datcopinvc']['grupo_contratacion_id']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['grupo_contratacion_id'];
            $_SESSION['ctrpl1']['datcopinvc']['des1']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['des1'];
            $_SESSION['ctrpl1']['datcopinvc']['porcentaje']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['porcentaje'];
            $_SESSION['ctrpl1']['datcopinvc']['por_cobertura']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['por_cobertura'];
            $_SESSION['ctrpl1']['datcopinvc']['porcentaje_nopos_autorizado']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['porcentaje_nopos_autorizado'];
            $_SESSION['ctrpl1']['datcopinvc']['sw_descuento']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['sw_descuento'];
            $_SESSION['ctrpl1']['datcopinvc']['sw_copago']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['sw_copago'];
            $_SESSION['ctrpl1']['datcopinvc']['sw_cuota_moderadora']=$_SESSION['ctrpl1']['grupoincoc'][$_REQUEST['indicecopinc']]['sw_cuota_moderadora'];
            UNSET($_SESSION['ctrpl1']['grupoincoc']);
        }
        UNSET($_SESSION['ctrpl1']['codigoicoc']);//borra los codigos de los medicamentos
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INSUMOS Y MEDICAMENTOS - COPAGOS - EXCEPCIONES');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarTariExceCopaInveContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','TarifarioCopaInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">SERVICIO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['deseincopc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['datcopinvc']['des1']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">DESCUENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        if($_SESSION['ctrpl1']['datcopinvc']['sw_descuento']==1)
        {
            $this->salida .= "SI";
        }
        else
        {
            $this->salida .= "NO";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">PORCENTAJE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $porver=$_SESSION['ctrpl1']['datcopinvc']['porcentaje'];
        $this->salida .= "      ".number_format(($porver), 2, '.', '.').' '.'%'."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">COBERTURA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $porver=$_SESSION['ctrpl1']['datcopinvc']['por_cobertura'];
        $this->salida .= "      ".number_format(($porver), 2, '.', '.').' '.'%'."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">PORCENTAJE NO POS AUTORIZADO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $porver=$_SESSION['ctrpl1']['datcopinvc']['porcentaje_nopos_autorizado'];
        $this->salida .= "      ".number_format(($porver), 2, '.', '.').' '.'%'."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\" colspan=\"4\">";
        $this->salida .= "          <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $this->salida .= "          <td align=\"left\" width=\"80%\">";
        $this->salida .= "COPAGO";
        $this->salida .= "          </td>";
        $this->salida .= "          <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['datcopinvc']['sw_copago']==1)
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\">";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $this->salida .= "          <td align=\"left\" width=\"80%\">";
        $this->salida .= "CUOTA MODERADORA";
        $this->salida .= "          </td>";
        $this->salida .= "          <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['datcopinvc']['sw_cuota_moderadora']==1)
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\">";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          <tr class=modulo_list_claro>";
        $this->salida .= "          <td align=\"left\" width=\"80%\">";
        $this->salida .= "NINGUNA";
        $this->salida .= "          </td>";
        $this->salida .= "          <td align=\"center\" width=\"20%\">";
        if($_SESSION['ctrpl1']['datcopinvc']['sw_copago']==0 AND $_SESSION['ctrpl1']['datcopinvc']['sw_cuota_moderadora']==0)
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\">";
        }
        else
        {
            $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\">";
        }
        $this->salida .= "          </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
        $this->salida .= "      <td width=\"27%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"11%\">COSTO</td>";
        $this->salida .= "      <td width=\"11%\">COS. ÚLT. COMPRA</td>";
        $this->salida .= "      <td width=\"11%\">PRECIO VENTA</td>";
        $this->salida .= "      <td width=\"32%\">COBER.</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['codigoicoc']=$this->BuscarTariCopaInveContra($_SESSION['ctrpla']['planeleg'],
        $_SESSION['contra']['empresa'],$_SESSION['ctrpl1']['serinvcopc'],$_SESSION['ctrpl1']['datcopinvc']['grupo_contratacion_id']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['codigoicoc']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoicoc'][$i]['codigo_producto']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoicoc'][$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoicoc'][$i]['costo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoicoc'][$i]['costo_ultima_compra']."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"right\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoicoc'][$i]['precio_venta']."";
            $this->salida .= "</td>";
            if($_SESSION['ctrpl1']['codigoicoc'][$i]['excepcion']==1)
            {
                $_POST['porinvexc'.$i]=$_SESSION['ctrpl1']['codigoicoc'][$i]['porcentaje'];
                $_POST['cobinvexc'.$i]=$_SESSION['ctrpl1']['codigoicoc'][$i]['por_cobertura'];
                $_POST['posinvexc'.$i]=$_SESSION['ctrpl1']['codigoicoc'][$i]['porcentaje_nopos_autorizado'];
                $_POST['desinvexc'.$i]=$_SESSION['ctrpl1']['codigoicoc'][$i]['sw_descuento'];
                $copagos=$_SESSION['ctrpl1']['codigoicoc'][$i]['sw_copago'];
                $cuotasm=$_SESSION['ctrpl1']['codigoicoc'][$i]['sw_cuota_moderadora'];
            }
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td width=\"50%\" align=\"center\">";
            $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" $color>";//$color
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"75%\">";
            $this->salida .= "COPAGO";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"25%\">";
            if($copagos==1 AND $_SESSION['ctrpl1']['codigoicoc'][$i]['excepcion']==1)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuoinvexc".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuoinvexc".$i."\" value=1>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"75%\">";
            $this->salida .= "CUOTA MODERADORA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"25%\">";
            if($cuotasm==1 AND $_SESSION['ctrpl1']['codigoicoc'][$i]['excepcion']==1)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuoinvexc".$i."\" value=2 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuoinvexc".$i."\" value=2>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td align=\"left\" width=\"75%\">";
            $this->salida .= "NINGUNA";
            $this->salida .= "          </td>";
            $this->salida .= "          <td align=\"center\" width=\"25%\">";
            if($copagos<>NULL AND $cuotasm<>NULL AND $copagos<>1 AND $cuotasm<>1 AND $_SESSION['ctrpl1']['codigoicoc'][$i]['excepcion']==1)
            {
                $this->salida .= "<input type=\"radio\" name=\"cuoinvexc".$i."\" value=3 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"radio\" name=\"cuoinvexc".$i."\" value=3>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          </table>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"50%\" align=\"center\">";
            $this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" $color>";//$color
            $this->salida .= "          <tr>";
            $this->salida .= "          <td width=\"40%\"> PORCE.";
            $this->salida .= "          </td>";
            $this->salida .= "          <td width=\"60%\">";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porinvexc".$i."\" value=\"".$_POST['porinvexc'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td width=\"40%\"> COBER.";
            $this->salida .= "          </td>";
            $this->salida .= "          <td width=\"60%\">";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cobinvexc".$i."\" value=\"".$_POST['cobinvexc'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td width=\"40%\"> NO POS";
            $this->salida .= "          </td>";
            $this->salida .= "          <td width=\"60%\">";
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"posinvexc".$i."\" value=\"".$_POST['posinvexc'.$i]."\" maxlength=\"8\" size=\"8\">";
            $this->salida .= "%";
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td width=\"40%\">DESCU.";
            $this->salida .= "          </td>";
            $this->salida .= "          <td width=\"60%\" align=\"center\">";
            if($_POST['desinvexc'.$i]==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"desinvexc".$i."\" value=1 checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"desinvexc".$i."\" value=1>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          </table>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($_SESSION['ctrpl1']['codigoicoc']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"6\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRARÓN PRODUCTOS'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TarifarioCopaInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER   AL   TARIFARIO\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraTicCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','TariExceCopaInveContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','TariExceCopaInveContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ParaTipoImdInveContra()//
    {
        UNSET($_SESSION['ctrpl1']['servdepimd']);
        UNSET($_SESSION['ctrpl1']['servicimdc']);
        UNSET($_SESSION['ctrpl1']['dserviimdc']);
        UNSET($_SESSION['ctrpl1']['departimdc']);
        UNSET($_SESSION['ctrpl1']['ddeparimdc']);
        UNSET($_SESSION['ctrpl1']['codigosimd']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS - INSUMOS Y MEDICAMENTOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">SERVICIOS ASISTENCIALES CONTRATADOS - DEPARTAMENTOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">MENÚ - INSUMOS Y MEDICAMENTOS</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['servdepimd']=$this->MostrarServiciosPlanes3($_SESSION['ctrpla']['planeleg'],$_SESSION['contra']['empresa']);
        $ciclo=sizeof($_SESSION['ctrpl1']['servdepimd']);
        $j=0;
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                 $color="class=\"modulo_list_claro\"";
                 $j=1;
            }
            else
            {
                 $color="class=\"modulo_list_oscuro\"";
                 $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\" width=\"40%\">";
            $this->salida .= "".$_SESSION['ctrpl1']['servdepimd'][$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"60%\">";
            $k=$i;
            while($_SESSION['ctrpl1']['servdepimd'][$i]['servicio']==$_SESSION['ctrpl1']['servdepimd'][$k]['servicio'])
            {
                $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" $color>";
                $this->salida .= "  <tr>";
                $this->salida .= "  <td align=\"center\" width=\"50%\">";
                $this->salida .= "  ".$_SESSION['ctrpl1']['servdepimd'][$k]['descdept']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td align=\"center\" width=\"25%\">";
                $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','MostrarImdInveContra',
                array('serviceleg'=>$k)) ."\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
                $this->salida .= "  </td>";
                $this->salida .= "  <td align=\"center\" width=\"25%\">";
                $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ModificarImdInveContra',
                array('serviceleg'=>$k)) ."\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";//ADICIONAR Y/O ELIMINAR
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $this->salida .= "  </table>";
                $k++;
            }
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $i=$k;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ModificarImdInveContra()//
    {
        if($_SESSION['ctrpl1']['servicimdc']==NULL)
        {
            $_SESSION['ctrpl1']['servicimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['servicio'];
            $_SESSION['ctrpl1']['dserviimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['descripcion'];
            $_SESSION['ctrpl1']['departimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['departamento'];
            $_SESSION['ctrpl1']['ddeparimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['descdept'];
            UNSET($_SESSION['ctrpl1']['servdepimd']);
        }
        UNSET($_SESSION['ctrpl1']['codigosimd']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS INSUMOS Y MEDICAMENTOS - ADICIONAR Y/O ELIMINAR');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarImdInveContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParaTipoImdInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dserviimdc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['ddeparimdc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td colspan=\"2\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
        $this->salida .= "      <td width=\"90%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $paragraimd=$this->BuscarParaTipoImdInveContra2($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['servicimdc'],$_SESSION['ctrpl1']['departimdc']);
        $j=0;
        $ciclo=sizeof($paragraimd);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$paragraimd[$i]['codigo_producto']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragraimd[$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($paragraimd))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"2\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN INSUMO Y/O MEDICAMENTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"10%\">ADIC/ELIM</td>";
        $this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
        $this->salida .= "      <td width=\"80%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['codigosimd']=$this->BuscarParaTipoImdInveContra($_SESSION['contra']['empresa'],
        $_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['servicimdc'],$_SESSION['ctrpl1']['departimdc']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['codigosimd']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            if($_SESSION['ctrpl1']['codigosimd'][$i]['paragrafado']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"grabarimd".$i."\" value=\"1\" checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"grabarimd".$i."\" value=\"1\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigosimd'][$i]['codigo_producto']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigosimd'][$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($_SESSION['ctrpl1']['codigosimd']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"3\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN INSUMO Y/O MEDICAMENTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR PARAGRAFADOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ParaTipoImdInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraPmCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','ModificarImdInveContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ModificarImdInveContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function MostrarImdInveContra()//
    {
        if($_SESSION['ctrpl1']['servicimdc']==NULL)
        {
            $_SESSION['ctrpl1']['servicimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['servicio'];
            $_SESSION['ctrpl1']['dserviimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['descripcion'];
            $_SESSION['ctrpl1']['departimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['departamento'];
            $_SESSION['ctrpl1']['ddeparimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['descdept'];
            UNSET($_SESSION['ctrpl1']['servdepimd']);
        }
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS INSUMOS Y MEDICAMENTOS - CONSULTAR');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParaTipoImdInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dserviimdc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['ddeparimdc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td colspan=\"2\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
        $this->salida .= "      <td width=\"90%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $paragraimd=$this->BuscarParaTipoImdInveContra2($_SESSION['ctrpla']['planeleg'],
        $_SESSION['ctrpl1']['servicimdc'],$_SESSION['ctrpl1']['departimdc']);
        $j=0;
        $ciclo=sizeof($paragraimd);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$paragraimd[$i]['codigo_producto']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragraimd[$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($paragraimd))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"2\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN INSUMO Y/O MEDICAMENTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ParaTipoImdInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ParaGeneImdInveContra()//
    {
        UNSET($_SESSION['ctrpl1']['servdepimd']);
        UNSET($_SESSION['ctrpl1']['servicimdc']);
        UNSET($_SESSION['ctrpl1']['dserviimdc']);
        UNSET($_SESSION['ctrpl1']['departimdc']);
        UNSET($_SESSION['ctrpl1']['ddeparimdc']);
        UNSET($_SESSION['ctrpl1']['codigosimd']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS - INSUMOS Y MEDICAMENTOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">SERVICIOS ASISTENCIALES CONTRATADOS - DEPARTAMENTOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">MENÚ - INSUMOS Y MEDICAMENTOS</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['servdepimd']=$this->MostrarServiciosPlanes3($_SESSION['ctrpla']['planeleg'],$_SESSION['contra']['empresa']);
        $ciclo=sizeof($_SESSION['ctrpl1']['servdepimd']);
        $j=0;
        for($i=0;$i<$ciclo;)
        {
            if($j==0)
            {
                 $color="class=\"modulo_list_claro\"";
                 $j=1;
            }
            else
            {
                 $color="class=\"modulo_list_oscuro\"";
                 $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\" width=\"40%\">";
            $this->salida .= "".$_SESSION['ctrpl1']['servdepimd'][$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"60%\">";
            $k=$i;
            while($_SESSION['ctrpl1']['servdepimd'][$i]['servicio']==$_SESSION['ctrpl1']['servdepimd'][$k]['servicio'])
            {
                $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" $color>";
                $this->salida .= "  <tr>";
                $this->salida .= "  <td align=\"center\" width=\"75%\">";
                $this->salida .= "  ".$_SESSION['ctrpl1']['servdepimd'][$k]['descdept']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td align=\"center\" width=\"25%\">";
                $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClasificacionTipoContra',
                array('serviceleg'=>$k)) ."\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $this->salida .= "  </table>";
                $k++;
            }
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $i=$k;
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ClasificacionTipoContra()//
    {
        if($_SESSION['ctrpl1']['servicimdc']==NULL)
        {
            $_SESSION['ctrpl1']['servicimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['servicio'];
            $_SESSION['ctrpl1']['dserviimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['descripcion'];
            $_SESSION['ctrpl1']['departimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['departamento'];
            $_SESSION['ctrpl1']['ddeparimdc']=$_SESSION['ctrpl1']['servdepimd'][$_REQUEST['serviceleg']]['descdept'];
            UNSET($_SESSION['ctrpl1']['servdepimd']);
        }
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS INSUMOS Y MEDICAMENTOS - CONSULTAR');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParaGeneImdInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dserviimdc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['ddeparimdc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td colspan=\"2\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      EL PLAN TIENE UN CLASIFICACIÓN PREDEFINIDA";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
        $this->salida .= "      <td width=\"90%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $paragraimd=$this->BuscarClasificacionTipoContra($_SESSION['ctrpla']['tpmdeleg'],
        $_SESSION['ctrpl1']['servicimdc'],$_SESSION['ctrpl1']['departimdc']);
        $j=0;
        $ciclo=sizeof($paragraimd);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$paragraimd[$i]['codigo_producto']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragraimd[$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($paragraimd))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"2\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN INSUMO Y/O MEDICAMENTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ParaGeneImdInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ParagraCadInveContra()//
    {
        UNSET($_SESSION['ctrpl1']['serparcadc']);
        UNSET($_SESSION['ctrpl1']['dseparcadc']);
        UNSET($_SESSION['ctrpl1']['codigoscad']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS - CARGOS DIRECTOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">MENÚ - CARGOS DIRECTOS</td>";
        $this->salida .= "      </tr>";
        $servicios=$this->MostrarServiciosPlanes2($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($servicios);
        for($i=0;$i<$ciclo;$i++)
        {
            $this->salida .= "  <tr class=\"modulo_list_claro\">";
            $this->salida .= "  <td align=\"center\" width=\"60%\">";
            $this->salida .= "".$servicios[$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"20%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','MostrarCadInveContra',
            array('serviceleg'=>$servicios[$i]['servicio'],'descrieleg'=>$servicios[$i]['descripcion'])) ."\">
            <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\" width=\"20%\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ModificarCadInveContra',
            array('serviceleg'=>$servicios[$i]['servicio'],'descrieleg'=>$servicios[$i]['descripcion'])) ."\">
            <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";//ADICIONAR Y/O ELIMINAR
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ModificarCadInveContra()//
    {
        if($_SESSION['ctrpl1']['serparcadc']==NULL)
        {
            $_SESSION['ctrpl1']['serparcadc']=$_REQUEST['serviceleg'];
            $_SESSION['ctrpl1']['dseparcadc']=$_REQUEST['descrieleg'];
        }
        UNSET($_SESSION['ctrpl1']['codigoscad']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS CARGOS DIRECTOS - ADICIONAR Y/O ELIMINAR');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarCadInveContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParagraCadInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS CARGOS DIRECTOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpl1']['dseparcadc']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td colspan=\"5\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"5%\" >CARGO</td>";
        $this->salida .= "      <td width=\"13%\">GRUPO</td>";
        $this->salida .= "      <td width=\"13%\">SUBGRUPO</td>";
        $this->salida .= "      <td width=\"13%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $paragracad=$this->BuscarParagraCadInveContra2($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serparcadc']);
        $j=0;
        $ciclo=sizeof($paragracad);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$paragracad[$i]['cargo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['des1']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['des2']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['des3']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($paragracad))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"5\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO DIRECTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"6%\" >ADIC/ELIM</td>";
        $this->salida .= "      <td width=\"5%\" >CARGO</td>";
        $this->salida .= "      <td width=\"13%\">GRUPO</td>";
        $this->salida .= "      <td width=\"13%\">SUBGRUPO</td>";
        $this->salida .= "      <td width=\"13%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"50%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $_SESSION['ctrpl1']['codigoscad']=$this->BuscarParagraCadInveContra($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serparcadc']);
        $j=0;
        $ciclo=sizeof($_SESSION['ctrpl1']['codigoscad']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            if($_SESSION['ctrpl1']['codigoscad'][$i]['paragrafado']==1)
            {
                $this->salida .= "<input type=\"checkbox\" name=\"grabarcad".$i."\" value=\"1\" checked>";
            }
            else
            {
                $this->salida .= "<input type=\"checkbox\" name=\"grabarcad".$i."\" value=\"1\">";
            }
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoscad'][$i]['cargo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoscad'][$i]['des1']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoscad'][$i]['des2']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoscad'][$i]['des3']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$_SESSION['ctrpl1']['codigoscad'][$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($_SESSION['ctrpl1']['codigoscad']))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"6\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO DIRECTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR PARAGRAFADOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ParagraCadInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraPcCli();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','ModificarCadInveContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ModificarCadInveContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function MostrarCadInveContra()//
    {
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - PARAGRAFADOS CARGOS DIRECTOS - CONSULTAR');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ParagraCadInveContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS CARGOS DIRECTOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO ASISTENCIAL CONTRATADO:</td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_REQUEST['descrieleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td colspan=\"5\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"5%\" >CARGO</td>";
        $this->salida .= "      <td width=\"13%\">GRUPO</td>";
        $this->salida .= "      <td width=\"13%\">SUBGRUPO</td>";
        $this->salida .= "      <td width=\"13%\">TARIFARIO</td>";
        $this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
        $this->salida .= "      </tr>";
        $paragracad=$this->BuscarParagraCadInveContra2($_SESSION['ctrpla']['planeleg'],$_REQUEST['serviceleg']);
        $j=0;
        $ciclo=sizeof($paragracad);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=\"modulo_list_claro\"";
                $j=1;
            }
            else
            {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
            }
            $this->salida .= "<tr $color>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".$paragracad[$i]['cargo']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['des1']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['des2']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['des3']."";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "".$paragracad[$i]['descripcion']."";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        if(empty($paragracad))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td align=\"center\" colspan=\"5\">";
            $this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO DIRECTO'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td width=\"50%\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','ParagraCadInveContra');
        $this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function IncumplimientoContra()//
    {
        UNSET($_SESSION['ctrpl1']['incumpctra']);
        $this->salida  = ThemeAbrirTabla('CONTRATACIÓN - INCUMPLIMIENTO DE CITAS');
        $accion=ModuloGetURL('app','Contratacion','user','ValidarIncumplimientoContra',
        array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
        'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"copagos\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td width=\"100%\" align=\"right\">";
        $this->salida .= "<a href=\"". ModuloGetURL('app','Contratacion','user','ClienteCargosContra') ."\">";
        $this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" title=\"ANTERIOR\"></a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">CARGOS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['contra']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['numeeleg']."".' --- '."".$_SESSION['ctrpla']['desceleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "      ".$_SESSION['ctrpla']['nombeleg']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
        }
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"10%\">CARGO</td>";
        $this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
        $this->salida .= "      <td width=\"15%\">VALOR</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $_SESSION['ctrpl1']['incumpctra']=$this->BuscarIncumplimientoContra($_SESSION['ctrpla']['planeleg']);
        $ciclo=sizeof($_SESSION['ctrpl1']['incumpctra']);
        for($i=0;$i<$ciclo;$i++)
        {
            if($j==0)
            {
                $color="class=modulo_list_claro";
                $j=1;
            }
            else
            {
                $color="class=modulo_list_oscuro";
                $j=0;
            }
            $this->salida .= "  <tr $color>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  ".$_SESSION['ctrpl1']['incumpctra'][$i]['cargo_cita']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td>";
            $this->salida .= "  ".$_SESSION['ctrpl1']['incumpctra'][$i]['descripcion']."";
            $this->salida .= "  </td>";
            $this->salida .= "  <td align=\"center\">";
            $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"valorictra".$i."\" value=\"".$_SESSION['ctrpl1']['incumpctra'][$i]['valor']."\" maxlength=\"13\" size=\"13\">";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
        $this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"MENÚ 2\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $var=$this->RetornarBarraImcarg();
        if(!empty($var))
        {
            $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"100%\" align=\"center\">";
            $this->salida .=$var;
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion=ModuloGetURL('app','Contratacion','user','IncumplimientoContra',
        array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
        $this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"70%\">";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
        $this->salida .= "  </td>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=modulo_list_claro>";
        $this->salida .= "  <td colspan=\"2\" align=\"center\">";
        $accion=ModuloGetURL('app','Contratacion','user','IncumplimientoContra');
        $this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
	/**
	 */
	function MostrarFormaProtocoloInternacion()
	{
		$this->FormaProtocoloInternacion();
		return true;
	}
	
	/**
	 */
	function FormaProtocoloInternacion()
	{
		$protocolo=$this->GetProtocoloInternacion();
		$acc=ModuloGetURL('app','Contratacion','user','GuardarProtocoloInternacion');
		$this->salida .= ThemeAbrirTabla('CONTRATACIÓN - PROTOCOLO INTERNACIÓN');
		$this->salida .= "<form name=\"contrata2\" action=\"$acc\" method=\"post\">";
		$this->salida .= "	<table border=\"1\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr class=modulo_list_claro>";
		$this->salida .= "			<td>PROTOCOLO";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=modulo_list_claro>";
		$this->salida .= "			<td>";
		$this->salida .= getFckeditor('protocoloInternacion',"200","100%",$protocolo);
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "	<br>";
		$this->salida .= "	<table align=\"center\">";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
		$this->salida .= "</form>";
		$this->salida .= "			<td>";
		$accion=ModuloGetURL('app','Contratacion','user','ClienteCargosContra');
		$this->salida .= "				<form name=\"frmVolver\" action=\"$accion\" method=\"post\">";
		$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"VOLVER\">";
		$this->salida .= "				</form>";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
}//fin de la clase
?>