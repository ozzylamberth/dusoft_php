<?php

/**
 * $Id: app_Soat_userclasses_HTML.php,v 1.21 2007/01/29 22:30:23 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de los eventos del Soat (determinar los acontecimientos de un evento soat)
 */

/**
* app_Soat_userclasses_HTML.php
*
* Clase que establece las bosquedas y motodos de acceso a la informacion de las
* caracterosticas del accidente, del vehoculo, de la poliza y la E.P.S.,
* que se relacionan con un evento SOAT
**/

class app_Soat_userclasses_HTML extends app_Soat_user
{
    function app_Soat_user_HTML()
    {
        $this->app_Soat_user(); //Constructor del padre 'modulo'
        $this->salida='';
        return true;
    }

    //Funcion principal que da las opciones para tener acceso a los datos de SOAT
    function PrincipalSoat2()//Llama a todas las opciones posibles
    {
        UNSET($_SESSION['soa1']);
        UNSET($_SESSION['soat']);
        UNSET($_SESSION['SELECTO']);
        if($this->UsuariosSoat()==false)
        {
            return false;
        }
        return true;
    }

    //Funcion principal que da las opciones para tener acceso a los datos de SOAT
    function PrincipalSoat()//Llama a todas las opciones posibles
    {
        UNSET($_SESSION['SELECTO']);
        if(empty($_REQUEST['permisosoat']['empresa_id']) AND empty($_SESSION['soa1']['empresa']))
        {
            $this->frmError["MensajeError"]="SELECCIONE UNA EMPRESA";
            $this->uno=1;
            $this->PrincipalSoat2();
            return true;
        }
        if(empty($_SESSION['soa1']['empresa']))
        {
            $_SESSION['soa1']['empresa']=$_REQUEST['permisosoat']['empresa_id'];
            $_SESSION['soa1']['razonso']=$_REQUEST['permisosoat']['descripcion1'];
            $_SESSION['soa1']['centroutil']=$_REQUEST['permisosoat']['centro_utilidad'];
            $_SESSION['soa1']['descentro']=$_REQUEST['permisosoat']['descripcion2'];
        }//Variables de evento
        UNSET($_SESSION['soat']);
        UNSET($_SESSION['SELECTO']);
        $this->salida  = ThemeAbrirTabla('SOAT - OPCIONES');
        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td align=\"center\">MENÚ</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\" align=\"center\">";
        $this->salida .= "      <a href=\"". ModuloGetURL('app','Soat','user','DatosAccidentado') ."\">INGRESAR INFORMACIÓN DEL ACCIDENTE</a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\" align=\"center\">";
        $this->salida .= "      <a href=\"". ModuloGetURL('app','Soat','user','ConsumoSoat') ."\">CONSUMOS DEL SOAT</a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\"  align=\"center\">";
        $this->salida .= "      <a href=\"". ModuloGetURL('app','Soat','user','ConsultaPolizaSoat') ."\">CONSULTAR POLIZA</a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
/*      $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\"  align=\"center\">";
        $this->salida .= "      <a href=\"". ModuloGetURL('app','Soat','user','DatosInformeSoat') ."\">GENERAR INFORMES FOSYGA</a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";*/
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\"  align=\"center\">";
        $this->salida .= "      <a href=\"". ModuloGetURL('app','Soat','user','ListaPacientes') ."\">LISTADO DE PACIENTES POR SOAT</a>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat2');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EMPRESAS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Datos del Accidentado - SOAT - Toma los datos bosico de la base de datos
    function DatosAccidentado()//Llama a Buscar Datos del Accidentado
    {
        UNSET($_SESSION['soat']);
        UNSET($_SESSION['SELECTO']);
        $this->salida = ThemeAbrirTabla('EVENTO SOAT - DATOS DEL ACCIDENTADO');
        $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"45%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTADO</legend>";
        $this->salida .= "    <table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
        $this->salida .= "      <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "        <td align=\"left\" style=\"text-indent:8pt\" width=\"50%\">TIPO DOCUMENTO:</td>\n";
        $this->salida .= "        <td class=\"modulo_list_claro\" width=\"50%\">\n";
        $this->salida .= "          <select name=\"TipoDocum\" class=\"select\">\n";
        $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
        $this->BuscarIdPaciente($tipo_id,$TipoId=$_REQUEST['TipoDocum']);
        $this->salida .= "          </select>\n";
        $this->salida .= "        </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "        <td align=\"left\" style=\"text-indent:8pt\" >DOCUMENTO:</td>\n";
        $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "          <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" size=\"26\">";
        $this->salida .= "        </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "        <td colspan=\"2\" align=\"center\">";
        $this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
        $this->salida .= "        </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "    </table>\n";
        $this->salida .= "  </fieldset>\n";
        $this->salida .= "</form>\n";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 370)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";

			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
      $html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";
      
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
    /**
    * Busca los datos de la persona accidentada, captura los datos 
    * del accidente, los valida  y los guarda
    */
    function DatosAccidente()//Llama a Validar Datos del Accidente
    {
        UNSET($_SESSION['soat']['eventonews']);
        UNSET($_SESSION['soat']['pacisoat']);
        UNSET($_SESSION['soat']['paciedad']);
        UNSET($_SESSION['soat']['accidentes']);
        UNSET($_SESSION['soat']['pantalla']);
        UNSET($_SESSION['soat']['eventoelegMA']);
        UNSET($_SESSION['soat']['acciverM']);
        UNSET($_SESSION['soat']['eventoelegMVP']);
        UNSET($_SESSION['soat']['eventoelegMVC']);
        UNSET($_SESSION['soat']['asegverM']);
        UNSET($_SESSION['soat']['poliverM']);
        UNSET($_SESSION['soat']['eventoelegMM']);
        UNSET($_SESSION['soat']['eventoelegRE']);
        UNSET($_SESSION['soat']['ambuverM']);
        UNSET($_SESSION['soat']['eventoelegCM']);
        UNSET($_SESSION['soat']['polizaenco']);
        UNSET($_SESSION['soat']['polizacons']);
        UNSET($_SESSION['soat']['guarmodico']);
        UNSET($_SESSION['SELECTO']);
        if(empty($_REQUEST['Documento']) AND empty($_SESSION['soat']['evento']['Documento']))
        {
            $this->DatosAccidentado();
            return true;
        }
        if(empty($_SESSION['soat']['evento']['Documento']))
        {
            $_SESSION['soat']['evento']['TipoDocum']=$_REQUEST['TipoDocum'];
            $_SESSION['soat']['evento']['Documento']=$_REQUEST['Documento'];
        }

        $_SESSION['soat']['evento']['nombresoat']=$this->BuscarNombrePaci($_SESSION['soat']['evento']['TipoDocum'],$_SESSION['soat']['evento']['Documento']);
        if(empty($_SESSION['soat']['evento']['nombresoat']))
        {
            $this->uno=1;
            //----------cambio dar
                //------------el paciente no esta en la tabla pacientes, pedimos los datos
                $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
                $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoDocum'];
                $_SESSION['PACIENTES']['PACIENTE']['plan_id']='';
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Soat';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPacientes'; 
                $_SESSION['PACIENTES']['PACIENTE']['ARREGLO']=array();
                $this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
                return true;                
        }
        else
        {
         	$this->IncludeJS("CrossBrowser");
    			$this->IncludeJS("CrossBrowserEvent");
    			$this->IncludeJS("CrossBrowserDrag");
          $evensoat=$this->BuscarEventoSoat($_SESSION['soat']['evento']['TipoDocum'],$_SESSION['soat']['evento']['Documento']);
          if(!empty($evensoat))
          {
        		SessionDelVar('codigoi');
        		SessionDelVar('diagnosticoi');
        		SessionDelVar('codigoi1');
        		SessionDelVar('diagnosticoi1');
        		SessionDelVar('codigoi2');
        		SessionDelVar('diagnosticoi2');
        		SessionDelVar('codigoe');
        		SessionDelVar('diagnosticoe');
        		SessionDelVar('codigoe1');
        		SessionDelVar('diagnosticoe1');
        		SessionDelVar('codigoe2');
        		SessionDelVar('diagnosticoe2');

            $this->SetXajax(array("SeleccionarIngreso"),"app_modules/Soat/RemoteXajax/EventosSoat.php");
                $this->salida  = ThemeAbrirTabla('EVENTOS SOAT RELACIONADOS CON EL PACIENTE.');
                $accion=ModuloGetURL('app','Soat','user','IngresaDatosAccidente');
                $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
                $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
                $this->salida .= "  <tr><td>";
                $this->salida .= "      <table border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"70%\">";
                $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"70%\">";
                $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </table><br>";
                if($_REQUEST['mensaje']) $this->frmError["MensajeError"] = $_REQUEST['mensaje'];
                if($this->frmError["MensajeError"]<>NULL)
                {
                    $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .= "</table><br>";
                }
                $this->salida .= "  </td></tr>";
                $this->salida .= "  <tr>\n";
                $this->salida .= "    <td>\n";
                $this->salida .= "      <fieldset class=\"fieldset\">\n";
                $this->salida .= "        <legend class=\"normal_11N\">EVENTOS SOAT DEL PACIENTE</legend>";
                $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "          <tr class=modulo_list_claro>";
                $this->salida .= "            <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:</td>\n";
                $this->salida .= "      <td align=\"center\" width=\"40%\">";
                $this->salida .= "      ".$_SESSION['soat']['evento']['TipoDocum']."".' - '."".$_SESSION['soat']['evento']['Documento']."";
                $this->salida .= "      </td>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"40%\">";
                $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </table><br>";
                $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "      <td width=\"3%\" >No.</td>";
                $this->salida .= "      <td width=\"3%\" >EVENTO</td>";
                $this->salida .= "      <td width=\"14%\">POLIZA</td>";
                $this->salida .= "      <td width=\"30%\">ASEGURADORA</td>";
                $this->salida .= "      <td width=\"12%\">SALDO</td>";
                $this->salida .= "      <td width=\"10%\">CONDICIÓN</td>";
                $this->salida .= "      <td width=\"10%\">ASEGURADO</td>";
                $this->salida .= "      <td width=\"3%\" >ACC.</td>";
                $this->salida .= "      <td width=\"3%\" >REM.</td>";
                $this->salida .= "      <td width=\"3%\" >CER.</td>";
                //$this->salida .= "      <td width=\"3%\" >CONS.</td>";
                $this->salida .= "      <td colspan=\"5\">MODIFICAR</td>";//width=\"16%\"
                $this->salida .= "      </tr>";
                $i=0;
                $j=0;
                $k=1;
                $condic=$this->BuscarCondicion();
                $ciclo=sizeof($evensoat);
            
            $stl = "modulo_list_oscuro";
            while($i<$ciclo)
            {
              ($stl == "modulo_list_oscuro")? $stl="modulo_list_claro":$stl="modulo_list_oscuro";
              
              $this->salida .= "  <tr class=\"".$stl."\">\n";
              $this->salida .= "    <td align=\"center\">".$k."</td>\n";
              if($evensoat[$i]['poliza']==$evensoat[$i+1]['poliza'])
                $k++;
              else
                $k=1;
              
              $this->salida .= "    <td align=\"center\">".$evensoat[$i]['evento']."</td>\n";              
              $this->salida .= "    <td align=\"center\">".$evensoat[$i]['poliza']."</td>\n";
              $this->salida .= "    <td align=\"center\">".$evensoat[$i]['nombre_tercero']."</td>";
              $this->salida .= "<td align=\"right\">";
              $saldover=$evensoat[$i]['saldo'];
              $this->salida .= number_format(($saldover), 2, ',', '.');
              $this->salida .= "</td>";
              $this->salida .= "<td align=\"center\">";
              for($l=0;$l<sizeof($condic);$l++)
              {
                if($evensoat[$i]['condicion_accidentado']==$condic[$l]['condicion_accidentado'])
                {
                  $this->salida .= "      ".strtoupper($condic[$l]['descripcion'])."";
                }
              }
              $this->salida .= "</td>";
              $this->salida .= "<td align=\"center\">";
              if($evensoat[$i]['asegurado']==1)
              {
                $this->salida .= "SI";
              }
              else if($evensoat[$i]['asegurado']==2)
              {
                $this->salida .= "NO";
              }
              else if($evensoat[$i]['asegurado']==3)
              {
                $this->salida .= "FANT.";
              }
              else if($evensoat[$i]['asegurado']==4)
              {
                $this->salida .= "P. FALSA";
              }
              else if($evensoat[$i]['asegurado']==5)
              {
                $this->salida .= "P. VENCIDA";
              }
              $this->salida .= "</td>";
              $this->salida .= "<td align=\"center\">";
              
              $link1 = ModuloGetURL('app','Soat','user','MostrarDatosAdicional',array('switch'=>1,
              'acciver'=>$evensoat[$i]['accidente_id'],'razover'=>$evensoat[$i]['razon_social'],
              'poliver'=>$evensoat[$i]['poliza'],'saldver'=>$evensoat[$i]['saldo'],'condver'=>$evensoat[$i]['condicion_accidentado'],
              'asegver'=>$evensoat[$i]['asegurado'],'epsver'=>$evensoat[$i]['codigo_eps'],'ambuver'=>$evensoat[$i]['ambulancia_id'],
              'eventoeleg'=>$evensoat[$i]['evento']));
              
              $this->salida .= "      <a href=\"#\" onclick=\"xajax_SeleccionarIngreso('".$evensoat[$i]['evento']."','".$_SESSION['soat']['evento']['TipoDocum']."','".$_SESSION['soat']['evento']['Documento']."','".$link1."')\">\n";
              $this->salida .= "        <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">\n";
              $this->salida .= "      </a>\n";
              $this->salida .= "    </td>\n";
              $this->salida .= "<td align=\"center\">";
              $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','RemitirEventoAcc',array('eventoeleg'=>$evensoat[$i]['evento'])) ."\">
              <img src=\"".GetThemePath()."/images/uf.png\" border=\"0\"></a>";
              $this->salida .= "</td>";
              
              $link2 = ModuloGetURL('app','Soat','user','AtencionMedica',array('eventoeleg'=>$evensoat[$i]['evento']));
              $this->salida .= "    <td align=\"center\">";
              $this->salida .= "      <a href=\"#\"  onclick=\"xajax_SeleccionarIngreso('".$evensoat[$i]['evento']."','".$_SESSION['soat']['evento']['TipoDocum']."','".$_SESSION['soat']['evento']['Documento']."','".$link2."')\">\n";
              $this->salida .= "        <img src=\"".GetThemePath()."/images/historial.png\" border=\"0\">\n";
              $this->salida .= "      </a>\n";
              $this->salida .= "    </td>\n";
              $this->salida .= "<td align=\"center\" width=\"3%\">";
              $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','ModificarDatosEventoAcc',array('eventoeleg'=>$evensoat[$i]['evento'])) ."\">
              <img src=\"".GetThemePath()."/images/accidente.png\" border=\"0\" title=\"DATOS DEL ACCIDENTE\"></a>";
              $this->salida .= "</td>";
              $this->salida .= "<td align=\"center\" width=\"3%\">";
              $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','ModificarEventoPropiVeh',array('eventoeleg'=>$evensoat[$i]['evento'])) ."\">
              <img src=\"".GetThemePath()."/images/vehiculos_propietario.png\" border=\"0\" title=\"DATOS DEL VEHÍCULO\"></a>";
              $this->salida .= "</td>";
              $this->salida .= "<td align=\"center\" width=\"3%\">";
              $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','ModificarEventoConduVeh',array('eventoeleg'=>$evensoat[$i]['evento'])) ."\">
              <img src=\"".GetThemePath()."/images/vehiculos_conductor.png\" border=\"0\" title=\"DATOS DEL CONDUCTOR\"></a>";
              $this->salida .= "</td>";
              $this->salida .= "<td align=\"center\" width=\"3%\">";
              $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','ModificarDatosEventoAmb',array('eventoeleg'=>$evensoat[$i]['evento'],'ambueleg'=>$evensoat[$i]['ambulancia_id'])) ."\">
              <img src=\"".GetThemePath()."/images/ambulancia.png\" border=\"0\" title=\"DATOS DE LA AMBULANCIA\"></a>";
              $this->salida .= "</td>";

              $this->salida .= "</tr>";
              $i++;
            }
            $this->salida .= "      </table><br>";
            $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"center\" width=\"50%\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO EVENTO\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </fieldset>";
            $this->salida .= "  </td></tr>";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td align=\"center\"><br>";
            $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
            $this->salida .= "      <tr>";
            $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
            $this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <td align=\"center\" width=\"33%\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"SOAT - OPCIONES\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $accion=ModuloGetURL('app','Soat','user','DatosAccidentado');
            $this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <td align=\"center\" width=\"34%\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $accion=ModuloGetURL('app','Soat','user','IraConsumoSoat');
            $this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <td align=\"center\" width=\"33%\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"consu\" value=\"IR A CONSUMOS\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table>";
            $this->salida .= $this->CrearVentana();
            $this->salida .= ThemeCerrarTabla();
            $_SESSION['soat']['pantalla']=2;
            return true;
          }
            else
            {
                $_SESSION['soat']['pantalla']=1;
                $this->IngresaDatosAccidente();
            }
        }
        return true;
    }
    //
    function ConsultaPolizaSoat()//
    {
        $this->salida  = ThemeAbrirTabla('SOAT - CONSULTAR POLIZA');
        $accion=ModuloGetURL('app','Soat','user','BuscarConsultaPolizaSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA POLIZA</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"26%\"><label class=\"label\">POLIZA SOAT NO.: AT</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza1\" value=\"".$_POST['poliza1']."\" maxlength=\"4\" size=\"4\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza2\" value=\"".$_POST['poliza2']."\" maxlength=\"20\" size=\"10\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza3\" value=\"".$_POST['poliza3']."\" maxlength=\"1\" size=\"1\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if(!empty($_SESSION['soat']['polizacons']))
        {
            $_POST['aseguradora']=$_SESSION['soat']['polizacons']['tipo_id_tercero'].','.$_SESSION['soat']['polizacons']['tercero_id'];
            $_POST['sucursal']=$_SESSION['soat']['polizacons']['sucursal'];
            $fecha=explode('-',$_SESSION['soat']['polizacons']['vigencia_desde']);
            $_POST['fechadesde']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $fecha=explode('-',$_SESSION['soat']['polizacons']['vigencia_hasta']);
            $_POST['fechahasta']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $_POST['placa']=$_SESSION['soat']['polizacons']['placa_vehiculo'];
            $_POST['marca']=$_SESSION['soat']['polizacons']['marca_vehiculo'];
            $_POST['tipove']=$_SESSION['soat']['polizacons']['tipo_vehiculo'];
            UNSET($_SESSION['soat']['polizacons']);
            $this->salida .= "      <tr class=modulo_list_oscuro>";
            $this->salida .= "      <td width=\"26%\"><label class=\"label\">NOMBRE ASEGURADORA: </label>";
            $this->salida .= "      </td>";
            $AsegSoat=$this->BuscarAseguradoraSoat();
            $this->salida .= "      <td colspan=\"2\">";
            $A=explode(',',$_POST['aseguradora']);
            for($i=0;$i<sizeof($AsegSoat);$i++)
            {
                if($A[1]==$AsegSoat[$i]['tercero_id'] AND $A[0]==$AsegSoat[$i]['tipo_id_tercero'])
                {
                    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"aseguradora\" value=\"".$AsegSoat[$i]['nombre_tercero']."\" maxlength=\"40\" size=\"40\" readonly>";
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td width=\"26%\"><label class=\"label\">* SUCURSAL O AGENCIA: </label>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td colspan=\"2\">";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"sucursal\" value=\"".$_POST['sucursal']."\" maxlength=\"40\" size=\"40\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_list_oscuro>";
            $this->salida .= "      <td class=\"label\" width=\"26%\">VIGENCIA DE LA POLIZA</td>";
            $this->salida .= "      <td width=\"37%\">";
            $this->salida .= "      <label class=\"label\">DESDE: </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadesde\" value=\"".$_POST['fechadesde']."\" maxlength=\"10\" size=\"15\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"37%\">";
            $this->salida .= "      <label class=\"label\">HASTA: </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechahasta\" value=\"".$_POST['fechahasta']."\" maxlength=\"10\" size=\"15\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td width=\"26%\"><label class=\"label\">PLACA: </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"placa\" value=\"".$_POST['placa']."\" maxlength=\"8\" size=\"12\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"37%\"><label class=\"label\">MARCA: </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"marca\" value=\"".$_POST['marca']."\" maxlength=\"30\" size=\"25\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      <td width=\"37%\"><label class=\"label\">TIPO: </label>";
            $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tipove\" value=\"".$_POST['tipove']."\" maxlength=\"20\" size=\"27\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    /**
    *
    */
    function AtencionMedica()
    {
      if($_REQUEST['ingreso_soat']) SessionSetVar("ingreso_soat", $_REQUEST['ingreso_soat']);
      
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");

      //EXTAER EL TIPO REPORTE
      $tipo_reporte=ModuloGetVar('app','Soat','TipoReporte');
      $_SESSION['soat']['pacisoat']=$this->BuscarPacienteSoat($_SESSION['soat']['evento']['TipoDocum'],$_SESSION['soat']['evento']['Documento']);
        
      if(empty($_SESSION['soat']['eventoelegCM']))
      {
        $_SESSION['soat']['eventoelegCM']=$_REQUEST['eventoeleg'];
        if($tipo_reporte==='0')
        {
          $info=$this->BuscarAtencionMedica($_SESSION['soat']['eventoelegCM'],SessionGetVar("ingreso_soat"));
        }
        else
        {
          if(!IncludeFile("app_modules/Soat/reports/fpdf/reporte_soat2.inc.php"))
          {
            $this->error = "No se pudo inicializar el archivo reporte_soat2.inc.php";
            $this->mensajeDeError = "No se pudo Incluir el archivo : app_modules/Soat/reports/fpdf/reporte_soat2.inc.php";
            return false;
          }
          $info=BuscarAtencionMedica($_SESSION['soat']['eventoelegCM'],SessionGetVar("ingreso_soat"));
        }
        
        if($info['apellidos_declara']==NULL)
        {
          $_REQUEST['atenmedi']=1;
          if($_SESSION['soat']['pacisoat']['segundo_apellido']==NULL)
          {
            $_POST['apelliprop']=$_SESSION['soat']['pacisoat']['primer_apellido'];
          }
          else
          {
            $_POST['apelliprop']=$_SESSION['soat']['pacisoat']['primer_apellido'].' '.$_SESSION['soat']['pacisoat']['segundo_apellido'];
          }
        }
        else
        {
          $_REQUEST['atenmedi']=2;
          $_POST['apelliprop']=$info['apellidos_declara'];
        }
        if($info['nombres_declara']==NULL)
        {
          if($_SESSION['soat']['pacisoat']['segundo_nombre']==NULL)
          {
              $_POST['nombreprop']=$_SESSION['soat']['pacisoat']['primer_nombre'];
          }
          else
          {
              $_POST['nombreprop']=$_SESSION['soat']['pacisoat']['primer_nombre'].' '.$_SESSION['soat']['pacisoat']['segundo_nombre'];
          }
        }
        else
        {
          $_POST['nombreprop']=$info['nombres_declara'];
        }
        if($info['tipo_id_paciente']==NULL)
        {
          $_POST['tidocuprop']=$_SESSION['soat']['evento']['TipoDocum'];
        }
        else
        {
          $_POST['tidocuprop']=$info['tipo_id_paciente'];
        }
        if($info['declara_id']==NULL)
        {
          $_POST['documeprop']=$_SESSION['soat']['evento']['Documento'];
        }
        else
        {
          $_POST['documeprop']=$info['declara_id'];
        }
        $_POST['nmpioE']=$_SESSION['soat']['pacisoat']['lugar_expedicion_documento'];

        $_POST['paisE']=$info['extipo_pais_id'];
        $_POST['dptoE']=$info['extipo_dpto_id'];
        $_POST['mpioE']=$info['extipo_mpio_id'];
        if($info['fecha_accidente']<>NULL)
        {
          $fecha=explode(' ',$info['fecha_accidente']);
          $fecha1=explode('-',$fecha[0]);
          $fecha2=explode(':',$fecha[1]);
          $_POST['fechaaccid']=$fecha1[2].'/'.$fecha1[1].'/'.$fecha1[0];
          $_POST['horario1']=$fecha2[0];
          $_POST['minutero1']=$fecha2[1];
        }
        if($info['fecha_ingreso']<>NULL)
        {
          $fecha=explode(' ',$info['fecha_ingreso']);
          $fecha1=explode('-',$fecha[0]);
          $fecha2=explode(':',$fecha[1]);
          $_POST['fechaingre']=$fecha1[2].'/'.$fecha1[1].'/'.$fecha1[0];
          $_POST['horario2']=$fecha2[0];
          $_POST['minutero2']=$fecha2[1];
        }
            
        $_POST['datos1']=$info['datos1_ta'];
        $_POST['datos2']=$info['datos2_fc'];
        $_POST['datos3']=$info['datos3_fr'];
        $_POST['datos4']=$info['datos4_te'];
        $_POST['datos5']=$info['datos5_conciencia'];
        $_POST['datos6']=$info['datos6_glasgow'];
        $_POST['embriaguez']=$info['estado_embriaguez'];
        $_POST['diagnos1']=$info['diagnostico1'];
        $_POST['diagnos2']=$info['diagnostico2'];
        $_POST['diagnos3']=$info['diagnostico3'];
        $_POST['diagnos4']=$info['diagnostico4'];
        $_POST['diagnos5']=$info['diagnostico5'];
        $_POST['diagnos6']=$info['diagnostico6'];
        $_POST['diagnos7']=$info['diagnostico7'];
        $_POST['diagnos8']=$info['diagnostico8'];
        $_POST['diagnos9']=$info['diagnostico9'];
        $_POST['diagnosd']=$info['diagnostico_def'];
        $_POST['noapmedico']=$info['tipo_id_tercero'].','.$info['tercero_id'];
        $_POST['registrome']=$info['tarjeta_profesional'];
      }
     
      $datos_evento = $this->BuscarDatosSoat($_SESSION['soat']['eventoelegCM'],SessionGetVar("ingreso_soat"));
      $this->salida  = ThemeAbrirTabla('SOAT - CERTIFICADO DE ATENCIÓN MEDICA');
      $ru='classes/BuscadorDestino/selectorCiudad.js';
      $rus='classes/BuscadorDestino/selector.php';
      $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
      //CASO REPORTE CALI
      if($tipo_reporte==='0')
      {
        $RUTA = $_ROOT ."cache/certificado_atencion_medica.pdf";
      }
      else
      {
        $RUTA = $_ROOT ."cache/certificado_atencion_medica_1.pdf";
      }
      $mostrar ="\n<script language='javascript'>\n";
      $mostrar.="var rem=\"\";\n";
      $mostrar.="function abreVentana(){\n";
      $mostrar.="    var nombre=\"\"\n";
      $mostrar.="    var url2=\"\"\n";
      $mostrar.="    var str=\"\"\n";
      $mostrar.="    var ALTO=screen.height\n";
      $mostrar.="    var ANCHO=screen.width\n";
      $mostrar.="    var nombre=\"REPORTE\";\n";
      $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
      $mostrar.="    var url2 ='$RUTA';\n";
      $mostrar.="    rem = window.open(url2, nombre, str)};\n";
      $mostrar.="    function SetAtencion(valor){\n";
      $mostrar.="     if(valor==0){\n";
      $mostrar.="     if(document.getElementById('atencion1').style.display=='none'){\n";
      $mostrar.="      document.getElementById('atencion1').style.display='block';\n";
      $mostrar.="      document.getElementById('atencion2').style.display='none';\n";
      $mostrar.="      }else{\n";
      $mostrar.="       document.getElementById('atencion1').style.display='none';}\n";
      $mostrar.="     }else{\n";
      $mostrar.="     if(valor==1){\n";
      $mostrar.="      if(document.getElementById('atencion2').style.display=='none'){\n";
      $mostrar.="       document.getElementById('atencion2').style.display='block';\n";
      $mostrar.="       document.getElementById('atencion1').style.display='none';\n";
      $mostrar.="       }else{\n";
      $mostrar.="       document.getElementById('atencion2').style.display='none';}\n";
      $mostrar.="      }\n";
      $mostrar.="     }\n";
      $mostrar.="    };\n";
      $mostrar.="</script>\n";
      $this->salida .= "$mostrar";
      $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "</table>";
      $this->salida .= "<table width=\"98%\" align=\"center\">\n";
      $this->salida .= "	<tr>\n";
      $this->salida .= "		<td>\n";
      $this->salida .= "			<div class=\"tab-pane\" id=\"medicamentos_paciente\">\n";
      $this->salida .= "				<script>	tabPane = new WebFXTabPane( document.getElementById( \"medicamentos_paciente\" )); </script>\n";
      $this->salida .= "				<div class=\"tab-page\" id=\"medica_solu_formulados\">\n";
      $this->salida .= "					<h2 class=\"tab\">DATOS ATENCIÓN ANTERIOR</h2>\n";
      $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"medica_solu_formulados\")); </script>\n";

      $accion=ModuloGetURL('app','Soat','user','ValidarAtencionMedica',array('atenmedi'=>$_REQUEST['atenmedi']));
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table><br>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">TIPO DOCUMENTO: </td>";
      $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['descripcion']."</td>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">DOCUMENTO: </td>";
      $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['evento']['Documento']."</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">APELLIDOS: </td>";
      $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['primer_apellido'].' '.$_SESSION['soat']['pacisoat']['segundo_apellido']."</td>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">NOMBRE: </td>";
      $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['primer_nombre'].' '.$_SESSION['soat']['pacisoat']['segundo_nombre']."</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">DIRECCIÓN: </td>";
      $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['residencia_direccion']."</td>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">TELÓFONO: </td>";
      $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['residencia_telefono']."</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">DEPARTAMENTO: </td>";
      $this->salida .= "      <td colspan=\"3\">".$_SESSION['soat']['pacisoat']['departamento']."</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"label\" width=\"17%\">CIUDAD: </td>";
      $this->salida .= "      <td colspan=\"3\">".$_SESSION['soat']['pacisoat']['municipio']."</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table><br>";
      if($this->uno == 1)
      {
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table><br>";
      }
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTE..</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"25%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("apelliprop")."\">QUIEN SEGÚN DECLARACIÓN DE:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"75%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"apelliprop\" value=\"".$_POST['apelliprop']."\" maxlength=\"40\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\"></label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombreprop\" value=\"".$_POST['nombreprop']."\" maxlength=\"40\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"".$this->SetStyle("tidocuprop")."\">TIPO DOCUMENTO:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <select name=\"tidocuprop\" value=\"".$_POST['tidocuprop']."\" class=\"select\">";
      $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
      $this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['tidocuprop']);
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"".$this->SetStyle("documeprop")."\">DOCUMENTO:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"documeprop\" value=\"".$_POST['documeprop']."\" maxlength=\"32\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['paisE'] && !$_POST['dptoE'] && !$_POST['mpioE'])
      {
        $_POST['paisE']=GetVarConfigAplication('DefaultPais');
        $_POST['dptoE']=GetVarConfigAplication('DefaultDpto');
        $_POST['mpioE']=GetVarConfigAplication('DefaultMpio');
      }
      $_POST['npaisE']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['paisE']));
      $this->salida .= "      <input type=\"hidden\" name=\"npaisE\" value=\"".$_POST['npaisE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"paisE\" value=\"".$_POST['paisE']."\" class=\"input-text\">";
      $_POST['ndptoE']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE']));
      $this->salida .= "      <input type=\"hidden\" name=\"ndptoE\" value=\"".$_POST['ndptoE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"dptoE\" value=\"".$_POST['dptoE']."\" class=\"input-text\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">EXPEDIDA EN:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $_POST['nmpioE']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE'],'Mpio'=>$_POST['mpioE']));
      $this->salida .= "      <input type=\"text\" name=\"nmpioE\" value=\"".$_POST['nmpioE']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpioE\" value=\"".$_POST['mpioE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"ncomunaE\" value=\"".$_POST['ncomunaE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"comunaE\" value=\"".$_POST['comunaE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"nbarrioE\" value=\"".$_POST['nbarrioE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"barrioE\" value=\"".$_POST['barrioE']."\" class=\"input-text\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar1\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,2)\">";
      $this->salida .= "      <label class=\"label\">FUE VÍCTIMA DEL ACCIDENTE DE TRANSITO</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <label class=\"label\">OCURRIDO EL DÍA: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechaaccid\" value=\"".$_POST['fechaaccid']."\" maxlength=\"10\" size=\"15\" readonly>
      <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\"> [dd/mm/aaaa]";
      $this->salida .= "      <label class=\"label\"> A LAS: </label>";
      for($i=0;$i<24;$i++)
      {
        if($i<10)
        {
          if($_POST['horario1']=="0$i")
          {
            $this->salida .="<input type=\"text\" class=\"input-text\" name=\"horario1\" value=\"".'0'."".$i."\" maxlength=\"2\" size=\"2\" readonly>";
          }
        }
        else
        {
          if($_POST['horario1']=="$i")
          {
            $this->salida .="<input type=\"text\" class=\"input-text\" name=\"horario1\" value=\"".$i."\" maxlength=\"2\" size=\"2\" readonly>";
          }
        }
      }
      $this->salida .= " : ";
      for($i=0;$i<60;$i++)
      {
        if($i<10)
        {
          if($_POST['minutero1']=="0$i")
          {
            $this->salida .="<input type=\"text\" class=\"input-text\" name=\"minutero1\" value=\"".'0'."".$i."\" maxlength=\"2\" size=\"2\" readonly>";
          }
        }
        else
        {
          if($_POST['minutero1']=="$i")
          {
            $this->salida .="<input type=\"text\" class=\"input-text\" name=\"minutero1\" value=\"".$i."\" maxlength=\"2\" size=\"2\" readonly>";
          }
        }
      }
      $this->salida .= "      <label class=\"label\">HORAS INGRESANDO AL SERVICIO DE URGENCIAS DE ESTA</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("fechaingre")."\">INSTITUCIÓN EL DÍA: </label>";
      if(empty($_POST['fechaingre']))
      {
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fechaingre\" value=\"".date ("d/m/Y")."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "".ReturnOpenCalendario('forma','fechaingre','/')."";
      }
      else
      {
        $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fechaingre\" value=\"".$_POST['fechaingre']."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "".ReturnOpenCalendario('forma','fechaingre','/')."";
      }
      $this->salida .= "      <label class=\"label\"> A LAS: </label>";
      $this->salida .= "      <select name=\"horario2\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<24;$i++)
      {
        if($i<10)
        {
          if($_POST['horario2']=="0$i")
          {
            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"0$i\">0$i</option>";
          }
        }
        else
        {
          if($_POST['horario2']=="$i")
          {
            $this->salida .="<option value=\"$i\" selected>$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"$i\">$i</option>";
          }
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= " : ";
      $this->salida .= "      <select name=\"minutero2\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<60;$i++)
      {
        if($i<10)
        {
          if($_POST['minutero2']=="0$i")
          {
            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"0$i\">0$i</option>";
          }
        }
        else
        {
          if($_POST['minutero2']=="$i")
          {
            $this->salida .="<option value=\"$i\" selected>$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"$i\">$i</option>";
          }
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= "      <label class=\"label\">HORAS CON LOS SIGUIENTES HALLAZGOS:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <label class=\"label\">SIGNOS VITALES: TA</label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"datos1\" value=\"".$_POST['datos1']."\" maxlength=\"7\" size=\"7\">";
      $this->salida .= "      <label class=\"label\">mmHG    FC</label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"datos2\" value=\"".$_POST['datos2']."\" maxlength=\"7\" size=\"7\">";
      $this->salida .= "      <label class=\"label\">x min.  FR</label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"datos3\" value=\"".$_POST['datos3']."\" maxlength=\"7\" size=\"7\">";
      $this->salida .= "      <label class=\"label\">x min.  T</label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"datos4\" value=\"".$_POST['datos4']."\" maxlength=\"7\" size=\"7\">";
      $this->salida .= "      <label class=\"label\">C</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\" height=\"28\">";
      $this->salida .= "      <label class=\"label\">ESTADO DE CONCIENCIA: ALERTA</label>";
      if($_POST['datos5']==1)
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"1\" checked>";
      }
      else
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"1\">";
      }
      $this->salida .= "      <label class=\"label\">OBNUBILADO</label>";
      if($_POST['datos5']==2)
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"2\" checked>";
      }
      else
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"2\">";
      }
      $this->salida .= "      <label class=\"label\">ESTUPOROSO</label>";
      if($_POST['datos5']==3)
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"3\" checked>";
      }
      else
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"3\">";
      }
      $this->salida .= "      <label class=\"label\">COMA</label>";
      if($_POST['datos5']==4)
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"4\" checked>";
      }
      else
      {
        $this->salida .= "  <input type=\"radio\" name=\"datos5\" value=\"4\">";
      }
      $this->salida .= "      <label class=\"label\">GLASGOW(7)</label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"datos6\" value=\"".$_POST['datos6']."\" maxlength=\"7\" size=\"7\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td colspan=\"2\" height=\"28\">";
      $this->salida .= "      <label class=\"label\">ESATDO DE EMBRIAGUEZ: SI</label>";
      if($_POST['embriaguez']==1)
      {
        $this->salida .= "  <input type=\"radio\" name=\"embriaguez\" value=\"1\" checked>";
      }
      else
      {
        $this->salida .= "  <input type=\"radio\" name=\"embriaguez\" value=\"1\">";
      }
      $this->salida .= "      <label class=\"label\">NO</label>";
      if($_POST['embriaguez']==2)
      {
        $this->salida .= "  <input type=\"radio\" name=\"embriaguez\" value=\"2\" checked>";
      }
      else
      {
        $this->salida .= "  <input type=\"radio\" name=\"embriaguez\" value=\"2\">";
      }
      $this->salida .= "      <label>(En caso positivo tomar muestra para alcoholemia u otras drogas)</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\" height=\"28\">";
      $this->salida .= "      <label class=\"label\">DATOS POSITIVOS</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">CABEZA Y ORGANOS DE LOS SENTIDOS:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos1']==NULL)
      {
        $_POST['diagnos1']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos1\" cols=\"80\" rows=\"4\">".$_POST['diagnos1']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">CUELLO:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos2']==NULL)
      {
          $_POST['diagnos2']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos2\" cols=\"80\" rows=\"4\">".$_POST['diagnos2']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">TORAX Y CARDIOPULMONAR:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos3']==NULL)
      {
          $_POST['diagnos3']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos3\" cols=\"80\" rows=\"4\">".$_POST['diagnos3']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">ABDOMEN:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos4']==NULL)
      {
          $_POST['diagnos4']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos4\" cols=\"80\" rows=\"4\">".$_POST['diagnos4']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">GENITOURINARIO:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos5']==NULL)
      {
          $_POST['diagnos5']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos5\" cols=\"80\" rows=\"4\">".$_POST['diagnos5']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">PELVIS:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos6']==NULL)
      {
          $_POST['diagnos6']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos6\" cols=\"80\" rows=\"4\">".$_POST['diagnos6']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">DORSO Y EXTREMIDADES:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos7']==NULL)
      {
          $_POST['diagnos7']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos7\" cols=\"80\" rows=\"4\">".$_POST['diagnos7']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">NEUROLÓGICO:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos8']==NULL)
      {
          $_POST['diagnos8']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos8\" cols=\"80\" rows=\"4\">".$_POST['diagnos8']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">IMPRESIÓN DIAGNÓSTICA:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnos9']==NULL)
      {
          $_POST['diagnos9']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnos9\" cols=\"80\" rows=\"4\">".$_POST['diagnos9']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">DIAGNÓSTICO DEFINITIVO:</label>";
      $this->salida .= "      </td>";
      if($_POST['diagnosd']==NULL)
      {
          $_POST['diagnosd']='NORMAL';
      }
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"diagnosd\" cols=\"80\" rows=\"8\">".$_POST['diagnosd']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"".$this->SetStyle("noapmedico")."\">NOMBRES Y APELLIDOS DEL MÓDICO:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $profes=$this->BuscarProfesional();
      $this->salida .= "      <select name=\"noapmedico\" class=\"select\">";
      $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
      $ciclo=sizeof($profes);
      $prof=explode(',',$_POST['noapmedico']);
      for($i=0;$i<$ciclo;$i++)
      {
          if($prof[0]==$profes[$i]['tipo_id_tercero'] AND $prof[1]==$profes[$i]['tercero_id'])
          {
              $this->salida .="<option value=\"".$profes[$i]['tipo_id_tercero']."".','."".$profes[$i]['tercero_id']."\" selected>".$profes[$i]['nombre_tercero']."</option>";
          }
          else
          {
              $this->salida .="<option value=\"".$profes[$i]['tipo_id_tercero']."".','."".$profes[$i]['tercero_id']."\">".$profes[$i]['nombre_tercero']."</option>";
          }
      }
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td>";
      $this->salida .= "      <label class=\"label\">REGISTRO MÉDICO No.:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"registrome\" value=\"".$_POST['registrome']."\" maxlength=\"20\" size=\"20\" readonly>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if($_REQUEST['atenmedi']==2)
      {
          $this->salida .= "      <tr class=modulo_list_claro>";
          $this->salida .= "      <td align=\"center\" colspan=\"2\">";
          $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR\" onclick=\"javascript:abreVentana()\">";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
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
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= "				</div>\n";
      $this->salida .= "				<div class=\"tab-page\" id=\"solicitudes\">\n";
      $this->salida .= "					<h2 class=\"tab\">DATOS ATENCIÓN NUEVA</h2>\n";
      $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"solicitudes\")); </script>\n";

      SessionDelVar('tipod'); 
      $accion=ModuloGetURL('app','Soat','user','InsertarNuevaAtencionMedica',array("evento"=>$_SESSION['soat']['eventoelegCM']));
      $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
      //MODIFICACION
         
    	if(!$_REQUEST['codigoi'])
    	{
    		$_REQUEST['codigoi'] = SessionGetVar('codigoi');
    		$_REQUEST['diagnosticoi'] = SessionGetVar('diagnosticoi');
        if(!$_REQUEST['codigoi'])
        {
          $_REQUEST['codigoi'] = $datos_evento['diagnostico_principal_ingreso_id'];
          $_REQUEST['diagnosticoi'] = $datos_evento['principal_ingreso'];
        }
    	}
    	if(!$_REQUEST['codigoi1'])
    	{
    		$_REQUEST['codigoi1'] = SessionGetVar('codigoi1');
    		$_REQUEST['diagnosticoi1'] = SessionGetVar('diagnosticoi1');
        if(!$_REQUEST['codigoi1'])
        {
          $_REQUEST['codigoi1'] = $datos_evento['diagnostico1_ingreso_id'];
          $_REQUEST['diagnosticoi1'] = $datos_evento['diagnostico1_ingreso'];
        }
    	}
    	if(!$_REQUEST['codigoi2'])
    	{
    		$_REQUEST['codigoi2'] = SessionGetVar('codigoi2');
    		$_REQUEST['diagnosticoi2'] = SessionGetVar('diagnosticoi2');
        if(!$_REQUEST['codigoi2'])
        {
          $_REQUEST['codigoi2'] = $datos_evento['diagnostico2_ingreso_id'];
          $_REQUEST['diagnosticoi2'] = $datos_evento['diagnostico2_ingreso'];
        }
        
    	}
    	if(!$_REQUEST['codigoe'])
    	{
    		$_REQUEST['codigoe'] = SessionGetVar('codigoe');
    		$_REQUEST['diagnosticoe'] = SessionGetVar('diagnosticoe');
        if(!$_REQUEST['codigoe'])
        {
          $_REQUEST['codigoe'] = $datos_evento['diagnostico_principal_egreso_id'];
          $_REQUEST['diagnosticoe'] = $datos_evento['principal_egreso'];
        }
    	}
    	if(!$_REQUEST['codigoe1'])
    	{
    		$_REQUEST['codigoe1'] = SessionGetVar('codigoe1');
    		$_REQUEST['diagnosticoe1'] = SessionGetVar('diagnosticoe1');
        if(!$_REQUEST['codigoe1'])
        {
          $_REQUEST['codigoe1'] = $datos_evento['diagnostico1_egreso_id'];
          $_REQUEST['diagnosticoe1'] = $datos_evento['diagnostico1_egreso'];
        }
    	}
     	if(!$_REQUEST['codigoe2'])
    	{
    		$_REQUEST['codigoe2'] = SessionGetVar('codigoe2');
    		$_REQUEST['diagnosticoe2'] = SessionGetVar('diagnosticoe2');
        if(!$_REQUEST['codigoe2'])
        {
          $_REQUEST['codigoe2'] = $datos_evento['diagnostico2_egreso_id'];
          $_REQUEST['diagnosticoe2'] = $datos_evento['diagnostico2_egreso'];
        }
    	}
   
      $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
    	$action = ModuloGetURL("app","Soat","user","BusquedaDiagnosticos");
    	$this->salida.="<tr class=\"modulo_table_title\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> DIAGNOSTICOS </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> DIAGNOSTICO PRINCIPAL DE INGRESO </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
    	$this->salida.="<td width=\"4%\">CODIGO:</td>";
    	$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoi' value =\"".$_REQUEST['codigoi']."\" readonly></td>" ;
    	$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
    	$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoi'   value =\"".$_REQUEST['diagnosticoi']."\" readonly></td>" ;
    	$action = ModuloGetURL("app","Soat","user","FrmBusquedaDiagnosticos",array('tipod'=>'i'));
    	$this->salida.= "<td  width=\"7%\" align=\"center\"><a href=\"$action\">Busqueda</a></td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> OTRO DIAGNOSTICO DE INGRESO </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
    	$this->salida.="<td width=\"4%\">CODIGO:</td>";
    	$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoi1' value =\"".$_REQUEST['codigoi1']."\" readonly></td>" ;
    	$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
    	$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoi1'   value =\"".$_REQUEST['diagnosticoi1']."\" readonly></td>" ;
    	$action = ModuloGetURL("app","Soat","user","FrmBusquedaDiagnosticos",array('tipod'=>'i1'));
    	$this->salida.= "<td  width=\"7%\" align=\"center\"><a href=\"$action\">Busqueda</a></td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> OTRO DIAGNOSTICO DE INGRESO </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
    	$this->salida.="<td width=\"4%\">CODIGO:</td>";
    	$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoi2' value =\"".$_REQUEST['codigoi2']."\" readonly></td>" ;
    	$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
    	$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoi2'   value =\"".$_REQUEST['diagnosticoi2']."\" readonly></td>" ;
    	$action = ModuloGetURL("app","Soat","user","FrmBusquedaDiagnosticos",array('tipod'=>'i2'));
    	$this->salida.= "<td  width=\"7%\" align=\"center\"><a href=\"$action\">Busqueda</a></td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> DIAGNOSTICO PRINCIPAL DE EGRESO </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
    	$this->salida.="<td width=\"4%\">CODIGO:</td>";
    	$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoe' value =\"".$_REQUEST['codigoe']."\" readonly></td>" ;
    	$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
    	$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoe'   value =\"".$_REQUEST['diagnosticoe']."\" readonly></td>" ;
    	$action = ModuloGetURL("app","Soat","user","FrmBusquedaDiagnosticos",array('tipod'=>'e'));
    	$this->salida.= "<td  width=\"7%\" align=\"center\"><a href=\"$action\">Busqueda</a></td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> OTRO DIAGNOSTICO DE EGRESO </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
    	$this->salida.="<td width=\"4%\">CODIGO:</td>";
    	$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoe1' value =\"".$_REQUEST['codigoe1']."\" readonly></td>" ;
    	$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
    	$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoe1'   value =\"".$_REQUEST['diagnosticoe1']."\" readonly></td>" ;
    	$action = ModuloGetURL("app","Soat","user","FrmBusquedaDiagnosticos",array('tipod'=>'e1'));
    	$this->salida.= "<td  width=\"7%\" align=\"center\"><a href=\"$action\">Busqueda</a></td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td align=\"center\" colspan=\"5\"> OTRO DIAGNOSTICO DE EGRESO </td>";
    	$this->salida.="</tr>";
    	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
    	$this->salida.="<td width=\"4%\">CODIGO:</td>";
    	$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoe2' value =\"".$_REQUEST['codigoe2']."\" readonly></td>" ;
    	$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
    	$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoe2'   value =\"".$_REQUEST['diagnosticoe2']."\" readonly></td>" ;
    	$action = ModuloGetURL("app","Soat","user","FrmBusquedaDiagnosticos",array('tipod'=>'e2'));
    	$this->salida.= "<td  width=\"7%\" align=\"center\"><a href=\"$action\">Busqueda</a></td>";
    	$this->salida.="</tr>";
      $this->salida .= "  </table>";

      $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "  <tr class=modulo_list_claro><td>";
      $this->salida .= "*Fecha Ingreso &nbsp;&nbsp;&nbsp;<input type=\"text\" class=\"input-text\" name=\"fechaingreso\" value=\"".$datos_evento['fecha_ingreso']."\" maxlength=\"10\" size=\"15\">";
      $this->salida .= "".ReturnOpenCalendario('forma1','fechaingreso','/')."";
      $this->salida .= "      <label class=\"label\"> A LAS: </label>";
      $this->salida .= "      <select name=\"horario1\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<24;$i++)
      {
        if($i<10)
        {
          if($_POST['horario1']=="0$i")
          {
            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"0$i\">0$i</option>";
          }
        }
        else
        {
          if($_POST['horario1']=="$i")
          {
            $this->salida .="<option value=\"$i\" selected>$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"$i\">$i</option>";
          }
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= " : ";
      $this->salida .= "      <select name=\"minutero1\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<60;$i++)
      {
        if($i<10)
        {
          if($_POST['minutero1']=="0$i")
          {
            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"0$i\">0$i</option>";
          }
        }
        else
        {
          if($_POST['minutero1']=="$i")
          {
            $this->salida .="<option value=\"$i\" selected>$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"$i\">$i</option>";
          }
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr class=modulo_list_claro><td>";
      $this->salida .= "*Fecha Egreso &nbsp;&nbsp;&nbsp;<input type=\"text\" class=\"input-text\" name=\"fechaegreso\" value=\"".$datos_evento['fecha_egreso']."\" maxlength=\"10\" size=\"15\">";
      $this->salida .= "".ReturnOpenCalendario('forma1','fechaegreso','/')."";
      $this->salida .= "      <label class=\"label\"> A LAS: </label>";
      $this->salida .= "      <select name=\"horario2\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<24;$i++)
      {
          if($i<10)
          {
              if($_POST['horario2']=="0$i")
              {
                  $this->salida .="<option value=\"0$i\" selected>0$i</option>";
              }
              else
              {
                  $this->salida .="<option value=\"0$i\">0$i</option>";
              }
          }
          else
          {
              if($_POST['horario2']=="$i")
              {
                  $this->salida .="<option value=\"$i\" selected>$i</option>";
              }
              else
              {
                  $this->salida .="<option value=\"$i\">$i</option>";
              }
          }
      }
      $this->salida .= "      </select>";
      $this->salida .= " : ";
      $this->salida .= "      <select name=\"minutero2\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<60;$i++)
      {
          if($i<10)
          {
              if($_POST['minutero2']=="0$i")
              {
                  $this->salida .="<option value=\"0$i\" selected>0$i</option>";
              }
              else
              {
                  $this->salida .="<option value=\"0$i\">0$i</option>";
              }
          }
          else
          {
              if($_POST['minutero2']=="$i")
              {
                  $this->salida .="<option value=\"$i\" selected>$i</option>";
              }
              else
              {
                  $this->salida .="<option value=\"$i\">$i</option>";
              }
          }
      }
      $this->salida .= "      </select>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table>";
      $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "  <tr class=modulo_table_title>";
      $this->salida .= "  <td colspan=\"2\" align=\"center\">";
      $this->salida .= "   DATOS DEL PROFESIONAL";
    	$this->salida .= "  </td>";
    	$this->salida  .="  </tr>";
            
    	$this->salida .= "  <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"62%\">*TIPO DE DOCUMENTO";
    	$tipos_id = $this->GetTipoTerceros();
      $this->salida .= "      <select name=\"tipodocumento\" class=\"select\">";
    	
      if($_REQUEST['tipodocumento']) $datos_evento['tipo_id_tercero'] = $_REQUEST['tipodocumento'];
      if($_REQUEST['documento']) $datos_evento['tercero_id'] = $_REQUEST['documento'];
      
      $sel = "";
      foreach($tipos_id AS $i => $v)
    	{
        ($datos_evento['tipo_id_tercero'] == $v['tipo_id_tercero'])? $sel = "selected": $sel = "";
        $this->salida .=" <option value=\"".$v[tipo_id_tercero]."\" ".$sel.">".$v[descripcion]."</option>\n";
    	}
      $this->salida .= "      </select>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      DOCUMENTO: <input type=\"text\" class=\"input-text\" name=\"documento\" value=\"".$datos_evento['tercero_id']."\" maxlength=\"32\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida  .="  </tr>";
    	$this->salida .= "  <tr class=modulo_list_claro>";
    	$this->salida .= "  <td>";
      $this->salida .= "   *Primer Apellido <input type=\"text\" class=\"input-text\" name=\"primerapellido\" value=\"".$datos_evento['primer_apellido']."\" maxlength=\"30\" size=\"60\">";
    	$this->salida .= "  </td>";
    	$this->salida .= "  <td>";
      $this->salida .= "   Segundo Apellido <input type=\"text\" class=\"input-text\" name=\"segundoapellido\" value=\"".$datos_evento['segundo_apellido']."\" maxlength=\"30\" size=\"60\">";
    	$this->salida .= "  </td>";
    	$this->salida  .="  </tr>";
      $this->salida .= "  <tr class=modulo_list_claro>";
      $this->salida .= "  <td>";
      $this->salida .= "   *Primer Nombre <input type=\"text\" class=\"input-text\" name=\"primernombre\" value=\"".$datos_evento['primer_nombre']."\" maxlength=\"30\" size=\"60\">";
    	$this->salida .= "  </td>";
    	$this->salida .= "  <td>";
      $this->salida .= "   Segundo Nombre <input type=\"text\" class=\"input-text\" name=\"segundonombre\" value=\"".$datos_evento['segundo_nombre']."\" maxlength=\"30\" size=\"60\">";
    	$this->salida .= "  </td>";
    	$this->salida  .="  </tr>";
      $this->salida .= "  <tr class=modulo_list_claro>";
      $this->salida .= "  <td colspan=\"2\">";
      $this->salida .= "   *Numero de Registro <input type=\"text\" class=\"input-text\" name=\"registro\" value=\"".$datos_evento['registro_medico']."\" maxlength=\"20\" size=\"20\">";
    	$this->salida .= "  </td>";
    	$this->salida  .="  </tr>";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td align=\"right\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
        
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"left\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= "      </div>";
      $this->salida .= "		</td>\n";
      $this->salida .= "	</tr>\n";
      $this->salida .= "</table>\n";

      $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= "<script type=\"text/javascript\">\n";
      $this->salida .= "	setupAllTabs();\n";
      $this->salida .= "	tabPane.setSelectedIndex(1);";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }

    //Captura los datos del accidente
    function IngresaDatosAccidente()//Llama a Validar Datos del Accidente
    {
        UNSET($_SESSION['soat']['accidentes']);
	//tipo-accidente transito
	$js = "<script>\n";
	$js .= "	function SetValor(valor)\n";
	$js .= "	{\n";
	$js .= "	 e = document.getElementById('condicionaccidentado');\n";
	$js .= "	 if(valor == '01')\n";
	$js .= "	 {\n";
	$js .= "	  e.style.display = \"block\";\n";
	$js .= "	 }\n";
	$js .= "	 else\n";
	$js .= "	 {\n";
	$js .= "	  e.style.display = \"none\";\n";
	$js .= "	 }\n";
	$js .= "	}\n";
  $js .= " function ValidarDatos(frm){ ";
  $js .= "    if(frm.epssoat.selectedIndex==0)\n";
	$js .= "    { \n";
 	$js .= "       document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA COBERTURA DESPUES DEL TOPE';\n";
	$js .= "       return;\n";
 	$js .="     }\n";
  $js .= "    if(frm.lugaracci.value==\"\")\n";
	$js .= "    { \n";
 	$js .= "       document.getElementById('error').innerHTML = 'DEBE INGRESAR EL LUGAR DEL ACCIDENTE';\n";
	$js .= "       return;\n";
 	$js .="     }\n";
  $js .= "    if(frm.informeacci.value==\"\")\n";
	$js .= "    { \n";
 	$js .= "       document.getElementById('error').innerHTML = 'DEBE INGRESAR  UNA BREVE DESCRIPCION SOBRE EL ACCIDENTE';\n";
	$js .= "       return;\n";
 	$js .="     }\n";
  $js .="     frm.submit();\n";
  $js .="      }\n";  
  //fin tipo-accidente transito
	$js .= "</script>\n";
	$this->salida  = "$js";
        $this->salida  .= ThemeAbrirTabla('SOAT - DATOS DEL ACCIDENTE');
        
        $_SESSION['soat']['pacisoat']=$this->BuscarPacienteSoat($_SESSION['soat']['evento']['TipoDocum'],$_SESSION['soat']['evento']['Documento']);
        $_SESSION['soat']['paciedad']=CalcularEdad($_SESSION['soat']['pacisoat']['fecha_nacimiento'],'');
        $Edad=$_SESSION['soat']['paciedad']['edad_aprox'];//Array ( [anos] => 0 [meses] => 11 [dias] => 9 [edad_aprox] => 11 Meses [edad_rips] => 11 [unidad_rips] => 2 )
        $ru='classes/BuscadorDestino/selectorCiudad.js';
        $rus='classes/BuscadorDestino/selector.php';
        if($this->frmError["MensajeError"]<>NULL)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br><br>";
        }
        $this->salida .= "  <script languaje='javascript' src=\"$ru\"></script>";
        $accion=ModuloGetURL('app','Soat','user','ValidarDatosAccidente');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
       
        $this->salida .= "  <fieldset><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTADO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">TIPO DOCUMENTO: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['descripcion']."</td>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">DOCUMENTO: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['evento']['Documento']."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">APELLIDOS: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['primer_apellido'].' '.$_SESSION['soat']['pacisoat']['segundo_apellido']."</td>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">NOMBRE: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['primer_nombre'].' '.$_SESSION['soat']['pacisoat']['segundo_nombre']."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">DIRECCIÓN: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['residencia_direccion']."</td>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">TELÉFONO: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['residencia_telefono']."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">SEXO: </td>";
        $this->salida .= "      <td width=\"33%\">".$_SESSION['soat']['pacisoat']['descripcionsexo']."</td>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">EDAD: </td>";
        $this->salida .= "      <td width=\"33%\">".$Edad."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"17%\">CIUDAD: </td>";
        $mpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array(
        'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
        'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id'],
        'Mpio'=>$_SESSION['soat']['pacisoat']['tipo_mpio_id']));
        $this->salida .= "      <td colspan=\"3\">".$mpio."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
         $this->salida .= "<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $this->salida .= "  <fieldset><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("fecha")."\">FECHA: </label>";
        if(empty($_POST['fecha']))
        {
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".date ("d/m/Y")."\" maxlength=\"10\" size=\"15\">";
            $this->salida .= "".ReturnOpenCalendario('forma','fecha','/')."";
        }
        else
        {
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".$_POST['fecha']."\" maxlength=\"10\" size=\"15\">";
            $this->salida .= "".ReturnOpenCalendario('forma','fecha','/')."";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      <td><label class=\"".$this->SetStyle("horario")."\">HORA: </label>";
        $this->salida .= "      <select name=\"horario\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['horario']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['horario']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"minutero\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['minutero']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['minutero']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        
	//CAMPOS FURIPS
	$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
	$this->salida .= "		<td> <label class=\"".$this->SetStyle("traslado")."\">TRASLADO EN AMBULANCIA PROPIA:</label></td>\n";
	$this->salida .= "		<td>\n";
	$this->salida .= "			SI&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"1\">";
	$this->salida .= "			NO&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"0\" checked>";
	$this->salida .= "		</td>\n";
	$this->salida .= "	</tr>\n";
	$this->salida .= "	<tr class=\"modulo_list_oscuro\" height=\"20\">\n";
	$this->salida .= "		<td><label class=\"".$this->SetStyle("traslado")."\">TIPO DE AMBULANCIA:</label></td>\n";
	$this->salida .= "		<td>\n";
	$this->salida .= "			BASICA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"0\" checked>";
	$this->salida .= "			MEDICALIZADA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"1\">";
	$this->salida .= "		</td>\n";
	$this->salida .= "	</tr>\n";
	$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
	$this->salida .= "		<td><label class=\"".$this->SetStyle("intervencion")."\">INTERVINO LA AUTORIDAD:</label></td>\n";
	$this->salida .= "		<td>\n";
	$this->salida .= "			SI&nbsp;&nbsp;<input type=\"radio\" name=\"intervencion\" value=\"1\" checked>";
	$this->salida .= "			NO&nbsp;&nbsp;<input type=\"radio\" name=\"intervencion\" value=\"0\">";
	$this->salida .= "		</td>\n";
	$this->salida .= "	</tr>\n";
	$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
	$this->salida .= "		<td><label class=\"".$this->SetStyle("naturaleza")."\">NATURALEZA DEL EVENTO:</label></td>\n";
	$this->salida .= "		<td>\n";
	$this->salida .= "		<select name=\"tiponaturaleza\" class=\"select\" onChange=\"SetValor(this.value)\">\n";
	$this->salida .=" 		<option value=\"\" selected>-------NINGUNO-------</option>\n";
	$eventos = $this->ObtenerTiposEventos();
	for($i=0; $i<sizeof($eventos); $i++)
	{
		if($_POST[tiponaturaleza] == $eventos[$i][soat_naturaleza_evento_id])
		{
			$this->salida .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\" selected>".$eventos[$i]['descripcion']."</option>\n";
		}
		else
		{
			$this->salida .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\">".$eventos[$i]['descripcion']."</option>\n";
		}
	}			
	$this->salida .= "              </select>\n";
	$this->salida .= "		</td>\n";
	$this->salida .= "	</tr>\n";
	$this->salida .= " <tr class=\"modulo_list_oscuro\">\n";
	$this->salida .= "  <td width=\"100%\" colspan=\"2\">\n";
	//TIPO ACCIDENTE DE TRANSITO
	$this->salida .= "    <div name='condicionaccidentado' id='condicionaccidentado' style=\"display:none\">";
	$this->salida .= "	<table width=\"100%\" align=\"center\">\n";
	$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
	$this->salida .= "      <td  width=\"40%\"><label class=\"".$this->SetStyle("condicionAccidentado")."\">COND. DEL ACCIDENTADO:</label>";
	$this->salida .= "      </td>";
	$this->salida .= "      <td  width=\"60%\">";
	$condic = $this->BuscarCondicion();
	for($i=0;$i<sizeof($condic);$i++)
	{
		$this->salida.= "      ".strtoupper($condic[$i]['descripcion'])."";
		if($_POST['condicion']==$condic[$i]['condicion_accidentado'])
		{
			$this->salida .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\" checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\">";
		}
	}
	$this->salida .= "      </td>";
	$this->salida .= "      </tr>";
	$this->salida .= "     </table>\n";
	$this->salida .= "    </div>";
	//FIN TIPO ACCIDENTE DE TANSITO
	$this->salida .= "  </td>\n";
	$this->salida .= " </tr>\n";
	//FIN CAMPOS FURIPS
		
/*	$this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"".$this->SetStyle("condicion")."\" width=\"50%\">CONDICIoN DEL ACCIDENTADO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"label\">";
        $condic=$this->BuscarCondicion();
        for($i=0;$i<sizeof($condic);$i++)
        {
            $this->salida .= "      ".strtoupper($condic[$i]['descripcion'])."";
            if($_POST['condicion']==$condic[$i]['condicion_accidentado'])
            {
                $this->salida .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\" checked>";
            }
            else
            {
                $this->salida .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\">";
            }
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";*/
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td><label class=\"".$this->SetStyle("zona")."\">ZONA DEL ACCIDENTE: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"label\">";
        $zonas=$this->BuscarZonaResidencia();
        for($i=0;$i<sizeof($zonas);$i++)
        {
            $this->salida .= "      ".strtoupper($zonas[$i]['descripcion'])."";
            if($_POST['zona']==$zonas[$i]['zona_residencia'])
            {
                $this->salida .= "      <input type='radio' name='zona' value=\"".$zonas[$i]['zona_residencia']."\" checked>";
            }
            else
            {
                $this->salida .= "      <input type='radio' name='zona' value=\"".$zonas[$i]['zona_residencia']."\">";
            }
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
        {
            $_POST['pais']=GetVarConfigAplication('DefaultPais');
            $_POST['dpto']=GetVarConfigAplication('DefaultDpto');
            $_POST['mpio']=GetVarConfigAplication('DefaultMpio');
        }
        $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
        $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\"label\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
        $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
        $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"50%\">* COBERTURA DESPUES DEL TOPE (incluido FISALUD):";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $epsoat=$this->BuscarEpsSoat();
        $this->salida .= "      <select name=\"epssoat\" class=\"select\">";
        $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
        $ciclo=sizeof($epsoat);
        for($i=0;$i<$ciclo;$i++)
        {
            if($_POST['epssoat']==$epsoat[$i]['codigo_eps'])
            {
                $this->salida .="<option value=\"".$epsoat[$i]['codigo_eps']."\" selected>".$epsoat[$i]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$epsoat[$i]['codigo_eps']."\">".$epsoat[$i]['descripcion']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"label\" width=\"50%\">* SITIO DONDE OCURRIO EL ACCIDENTE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <textarea class=\"input-text\" name=\"lugaracci\" cols=\"30\" rows=\"3\">".$_POST['lugaracci']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"50%\">* INFORME DEL ACCIDENTE <br> (Relato breve de los hechos):";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <textarea class=\"input-text\" name=\"informeacci\" cols=\"30\" rows=\"3\">".$_POST['informeacci']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("poliza1")."\">POLIZA SOAT NO.: AT</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza1\" value=\"".$_POST['poliza1']."\" maxlength=\"4\" size=\"4\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza2\" value=\"".$_POST['poliza2']."\" maxlength=\"20\" size=\"10\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza3\" value=\"".$_POST['poliza3']."\" maxlength=\"1\" size=\"1\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\"align=\"center\"class=\"label\" width=\"50%\">* DATOS  OBLIGATORIOS";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"guardar\" value=\"GUARDAR\"  onclick=\"ValidarDatos(document.forma)\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        if($_SESSION['soat']['pantalla']==1)
        {
            $accion=ModuloGetURL('app','Soat','user','DatosAccidentado');
            $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        }
        else if($_SESSION['soat']['pantalla']==2)
        {
            $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
            $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        }
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"Volver\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Captura los datos del vehoculo y los guarda; y recibe el identificador del accidente
    function IngresaDatosVehiculo()//Llama a Guardar Datos del Vehoculo
    {
        if($_SESSION['soat']['accidentes']['condicion']==1)
        {
            if($_SESSION['soat']['pacisoat']['segundo_apellido']==NULL)
            {
                $_POST['apelliprop']=$_SESSION['soat']['pacisoat']['primer_apellido'];
            }
            else
            {
                $_POST['apelliprop']=$_SESSION['soat']['pacisoat']['primer_apellido'].' '.$_SESSION['soat']['pacisoat']['segundo_apellido'];
            }
            if($_SESSION['soat']['pacisoat']['segundo_nombre']==NULL)
            {
                $_POST['nombreprop']=$_SESSION['soat']['pacisoat']['primer_nombre'];
            }
            else
            {
                $_POST['nombreprop']=$_SESSION['soat']['pacisoat']['primer_nombre'].' '.$_SESSION['soat']['pacisoat']['segundo_nombre'];
            }
            $_POST['tidocuprop']=$_SESSION['soat']['evento']['TipoDocum'];
            $_POST['documeprop']=$_SESSION['soat']['evento']['Documento'];
            $_POST['direccprop']=$_SESSION['soat']['pacisoat']['residencia_direccion'];
            $_POST['telefoprop']=$_SESSION['soat']['pacisoat']['residencia_telefono'];
            $_POST['pais']=$_SESSION['soat']['pacisoat']['tipo_pais_id'];
            $_POST['dpto']=$_SESSION['soat']['pacisoat']['tipo_dpto_id'];
            $_POST['mpio']=$_SESSION['soat']['pacisoat']['tipo_mpio_id'];
        }
        if(!empty($_SESSION['soat']['polizaenco']))
        {
            $_POST['aseguradora']=$_SESSION['soat']['polizaenco']['tipo_id_tercero'].','.$_SESSION['soat']['polizaenco']['tercero_id'];
            $_POST['sucursal']=$_SESSION['soat']['polizaenco']['sucursal'];
            $fecha=explode('-',$_SESSION['soat']['polizaenco']['vigencia_desde']);
            $_POST['fechadesde']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $fecha=explode('-',$_SESSION['soat']['polizaenco']['vigencia_hasta']);
            $_POST['fechahasta']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $_POST['placa']=$_SESSION['soat']['polizaenco']['placa_vehiculo'];
            $_POST['marca']=$_SESSION['soat']['polizaenco']['marca_vehiculo'];
            $_POST['tipove']=$_SESSION['soat']['polizaenco']['tipo_vehiculo'];
            UNSET($_SESSION['soat']['polizaenco']);
        }
        $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL VEHICULO');
        $accion=ModuloGetURL('app','Soat','user','ValidarDatosVehiculo');
        $ru='classes/BuscadorDestino/selectorCiudad.js';
        $rus='classes/BuscadorDestino/selector.php';
        $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"normal_11N\">INFORMACIÓN DEL VEHICULO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"".$this->SetStyle("asegurado")."\" width=\"26%\">ASEGURADO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"label\" colspan=\"2\">SI";
        if($_POST['asegurado']==1)
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['asegurado']==2)
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=2 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=2>";
        }
        $this->salida .= "  FANT.";
        if($_POST['asegurado']==3)
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=3 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=3>";
        }
        $this->salida .= "  POLIZA FALSA";
        if($_POST['asegurado']==4)
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=4 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=4>";
        }
        $this->salida .= "  POLIZA VENCIDA";
        if($_POST['asegurado']==5)
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=5 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"asegurado\" value=5>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        //TIPO SERVICIO DEL VEHICULO
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("tiposerviciovehiculo")."\">TIPO SERVICIO VEHICULO: </label>";
        $this->salida .= "      </td>";
        $tiposerviciosvehiculos=$this->ObtenerTiposServiciosVehiculos();
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <select name=\"tiposerviciovehiculo\" class=\"select\">";
        $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
        for($i=0;$i<sizeof($tiposerviciosvehiculos);$i++)
        {
                if($_POST[tiposerviciovehiculo]==$tiposerviciosvehiculos[$i]['tipo_servicio_vehiculo_id'])
                {
                    $this->salida .="<option value=\"".$tiposerviciosvehiculos[$i]['tipo_servicio_vehiculo_id']."\" selected>".$tiposerviciosvehiculos[$i]['descripcion']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$tiposerviciosvehiculos[$i]['tipo_servicio_vehiculo_id']."\">".$tiposerviciosvehiculos[$i]['descripcion']."</option>";
                }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
	//FIN TIPO SERVICIO VEHICULO
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("poliza1")."\">POLIZA SOAT NO.: AT</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza1\" value=\"".$_POST['poliza1']."\" maxlength=\"4\" size=\"4\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza2\" value=\"".$_POST['poliza2']."\" maxlength=\"20\" size=\"10\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza3\" value=\"".$_POST['poliza3']."\" maxlength=\"1\" size=\"1\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";	
	$this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("aseguradora")."\">NOMBRE ASEGURADORA: </label>";
        $this->salida .= "      </td>";
        $AsegSoat=$this->BuscarAseguradoraSoat();
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <select name=\"aseguradora\" class=\"select\">";
        $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
        $A=explode(',',$_POST['aseguradora']);
        for($i=0;$i<sizeof($AsegSoat);$i++)
        {
        //NIT de FISALUD - para que no lo muestre
            //if(('830031511-6')<>$AsegSoat[$i]['tercero_id'])
            //{
                if($A[1]==$AsegSoat[$i]['tercero_id'] AND $A[0]==$AsegSoat[$i]['tipo_id_tercero'])
                {
                    $this->salida .="<option value=\"".$AsegSoat[$i]['tipo_id_tercero'].','.$AsegSoat[$i]['tercero_id'].','.$AsegSoat[$i]['identificador_at']."\" selected>".$AsegSoat[$i]['nombre_tercero']."</option>";
                }
                else
                {
                    $this->salida .="<option value=\"".$AsegSoat[$i]['tipo_id_tercero'].','.$AsegSoat[$i]['tercero_id'].','.$AsegSoat[$i]['identificador_at']."\">".$AsegSoat[$i]['nombre_tercero']."</option>";
                }
            //}
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"26%\"><label class=\"label\">* SUCURSAL O AGENCIA: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"sucursal\" value=\"".$_POST['sucursal']."\" maxlength=\"30\" size=\"20\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"26%\">VIGENCIA DE LA POLIZA</td>";
        $this->salida .= "      <td width=\"37%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechadesde")."\">DESDE: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadesde\" value=\"".$_POST['fechadesde']."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('forma','fechadesde','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"37%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechahasta")."\">HASTA: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechahasta\" value=\"".$_POST['fechahasta']."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('forma','fechahasta','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("placa")."\">PLACA: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"placa\" value=\"".$_POST['placa']."\" maxlength=\"8\" size=\"12\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"37%\"><label class=\"".$this->SetStyle("marca")."\">MARCA: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"marca\" value=\"".$_POST['marca']."\" maxlength=\"30\" size=\"25\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"37%\"><label class=\"".$this->SetStyle("tipove")."\">TIPO: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tipove\" value=\"".$_POST['tipove']."\" maxlength=\"20\" size=\"27\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
	
	if($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')//ENVENTO DE TRANSITO
	{
        $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"normal_11N\">INFORMACIÓN DEL PROPIETARIO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\" class=\"label_mark\">VERIFIQUE LA INFORMACIÓN DEL PROPIETARIO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"label\" width=\"38%\">LOS DATOS DEL PROPIETARIO SON<br>LOS MISMOS DEL CONDUCTOR:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"label\" width=\"62%\">SI";
        if($_POST['propicondu']==1)
        {
            $this->salida .= "      <input type='radio' name=\"propicondu\" value=1 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"propicondu\" value=1>";
        }
        $this->salida .= "  NO";
        if($_POST['propicondu']==2)
        {
            $this->salida .= "      <input type='radio' name=\"propicondu\" value=2 checked>";
        }
        else
        {
            $this->salida .= "      <input type='radio' name=\"propicondu\" value=2>";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("apelliprop")."\">APELLIDO(S) DEL PROPIETARIO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"apelliprop\" value=\"".$_POST['apelliprop']."\" maxlength=\"60\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("nombreprop")."\">NOMBRE(S) DEL PROPIETARIO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombreprop\" value=\"".$_POST['nombreprop']."\" maxlength=\"40\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"label\">TIPO DOCUMENTO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <select name=\"tidocuprop\" value=\"".$_POST['tidocuprop']."\" class=\"select\">";
        $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
        $this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['tidocuprop']);
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("documeprop")."\">DOCUMENTO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"documeprop\" value=\"".$_POST['documeprop']."\" maxlength=\"32\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if(!$_POST['paisE'] && !$_POST['dptoE'] && !$_POST['mpioE'])
        {
            $_POST['paisE']=GetVarConfigAplication('DefaultPais');
            $_POST['dptoE']=GetVarConfigAplication('DefaultDpto');
            $_POST['mpioE']=GetVarConfigAplication('DefaultMpio');
        }
        $_POST['npaisE']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['paisE']));
        $this->salida .= "      <input type=\"hidden\" name=\"npaisE\" value=\"".$_POST['npaisE']."\" class=\"input-text\">";
        $this->salida .= "      <input type=\"hidden\" name=\"paisE\" value=\"".$_POST['paisE']."\" class=\"input-text\">";
        $_POST['ndptoE']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE']));
        $this->salida .= "      <input type=\"hidden\" name=\"ndptoE\" value=\"".$_POST['ndptoE']."\" class=\"input-text\">";
        $this->salida .= "      <input type=\"hidden\" name=\"dptoE\" value=\"".$_POST['dptoE']."\" class=\"input-text\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"label\">DE:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $_POST['nmpioE']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE'],'Mpio'=>$_POST['mpioE']));
        $this->salida .= "      <input type=\"text\" name=\"nmpioE\" value=\"".$_POST['nmpioE']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"mpioE\" value=\"".$_POST['mpioE']."\" class=\"input-text\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar1\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,2)\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"label\">* DIRECCIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"direccprop\" value=\"".$_POST['direccprop']."\" maxlength=\"40\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"label\">* TELÓFONO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefoprop\" value=\"".$_POST['telefoprop']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
        {
            $_POST['pais']=GetVarConfigAplication('DefaultPais');
            $_POST['dpto']=GetVarConfigAplication('DefaultDpto');
            $_POST['mpio']=GetVarConfigAplication('DefaultMpio');
        }
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("pais")."\">PAIS:</label>";
        $this->salida .= "      </td>";
        $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO:</label>";
        $this->salida .= "      </td>";
        $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("mpio")."\">CIUDAD:</label>";
        $this->salida .= "      </td>";
        $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\" class=\"label\">* DATOS NO OBLIGATORIOS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
	}
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        if($_SESSION['soat']['pantalla']==1)
        {
            $accion=ModuloGetURL('app','Soat','user','DatosAccidentado');
            $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        }
        else if($_SESSION['soat']['pantalla']==2)
        {
            $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
            $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        }
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        if($this->polizamala == 1)
        {
            $this->salida .="<script language='javascript'>";
            $this->salida .="alert('POLIZA ERRONEA');\n";
            $this->salida .="</script>";
            $this->polizamala = 0;
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Captura los datos del conductor y los guarda; y recibe el identificador del accidente
    function IngresaDatosConductor()//Llama a Guardar Datos del Conductor
    {
        $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL CONDUCTOR');
        $accion=ModuloGetURL('app','Soat','user','ValidarDatosConductor');
        $ru='classes/BuscadorDestino/selectorCiudad.js';
        $rus='classes/BuscadorDestino/selector.php';
        $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"normal_11N\">INFORMACIÓN DEL CONDUCTOR</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("apellicond")."\">APELLIDO(S) DEL CONDUCTOR: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"apellicond\" value=\"".$_POST['apellicond']."\" maxlength=\"60\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("nombrecond")."\">NOMBRE(S) DEL CONDUCTOR: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecond\" value=\"".$_POST['nombrecond']."\" maxlength=\"40\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("tidocucond")."\">TIPO DOCUMENTO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <select name=\"tidocucond\" value=\"".$_POST['tidocucond']."\" class=\"select\">";
        $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
        $this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['tidocucond']);
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("documecond")."\">DOCUMENTO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"documecond\" value=\"".$_POST['documecond']."\" maxlength=\"32\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if(!$_POST['paisE'] && !$_POST['dptoE'] && !$_POST['mpioE'])
        {
            $_POST['paisE']=GetVarConfigAplication('DefaultPais');
            $_POST['dptoE']=GetVarConfigAplication('DefaultDpto');
            $_POST['mpioE']=GetVarConfigAplication('DefaultMpio');
        }
        $_POST['npaisE']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['paisE']));
        $this->salida .= "      <input type=\"hidden\" name=\"npaisE\" value=\"".$_POST['npaisE']."\" class=\"input-text\">";
        $this->salida .= "      <input type=\"hidden\" name=\"paisE\" value=\"".$_POST['paisE']."\" class=\"input-text\">";
        $_POST['ndptoE']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE']));
        $this->salida .= "      <input type=\"hidden\" name=\"ndptoE\" value=\"".$_POST['ndptoE']."\" class=\"input-text\">";
        $this->salida .= "      <input type=\"hidden\" name=\"dptoE\" value=\"".$_POST['dptoE']."\" class=\"input-text\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("mpioE")."\">DE:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $_POST['nmpioE']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE'],'Mpio'=>$_POST['mpioE']));
        $this->salida .= "      <input type=\"text\" name=\"nmpioE\" value=\"".$_POST['nmpioE']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"mpioE\" value=\"".$_POST['mpioE']."\" class=\"input-text\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar1\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,2)\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("direcicond")."\">DIRECCIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"direcicond\" value=\"".$_POST['direcicond']."\" maxlength=\"40\" size=\"40\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("telefocond")."\">TELÓFONO:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefocond\" value=\"".$_POST['telefocond']."\" maxlength=\"10\" size=\"10\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
        {
            $_POST['pais']=GetVarConfigAplication('DefaultPais');
            $_POST['dpto']=GetVarConfigAplication('DefaultDpto');
            $_POST['mpio']=GetVarConfigAplication('DefaultMpio');
        }
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("pais")."\">PAIS:</label>";
        $this->salida .= "      </td>";
        $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO:</label>";
        $this->salida .= "      </td>";
        $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("mpio")."\">CIUDAD:</label>";
        $this->salida .= "      </td>";
        $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
        $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\" class=\"label\">* DATOS NO OBLIGATORIOS</td>";
        $this->salida .= "      </tr>";
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
        if($_SESSION['soat']['pantalla']==1)
        {
            $accion=ModuloGetURL('app','Soat','user','DatosAccidentado');
            $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        }
        else if($_SESSION['soat']['pantalla']==2)
        {
            $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
            $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        }
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    /**
    * Modifica la informacion del accidente
    * Vuelve a la pantalla de eventos relacionados al paciente
    *
    * @return boolean
    */
    function ModificarDatosEventoAcc()
    {
      if(empty($_SESSION['soat']['eventoelegMA']))//$this->uno == 0
      {
        $_SESSION['soat']['eventoelegMA']=$_REQUEST['eventoeleg'];
        $evensoat=$this->BuscarEventoSoatMod($_SESSION['soat']['eventoelegMA']);
        $_POST['condicionM']=$evensoat['condicion_accidentado'];
        $_SESSION['soat']['acciverM']=$evensoat['accidente_id'];
        $_POST['epssoatM']=$evensoat['codigo_eps'];
        if($evensoat['fecha_accidente'])
        {
          $fechamod=explode(' ',$evensoat['fecha_accidente']);
          $fechapar=$fechamod[0];
          $fechadiv=explode('-',$fechapar);
          $_POST['fechaM']=$fechadiv[2].'/'.$fechadiv[1].'/'.$fechadiv[0];
          $fechahor=$fechamod[1];
          $fechamin=explode(':',$fechahor);
          $_POST['horarioM']=$fechamin[0];
          $_POST['minuteroM']=$fechamin[1];
        }
        $_POST['lugaracciM']=$evensoat['sitio_accidente'];
        $_POST['zonaM']=$evensoat['zona'];
        $_POST['informeacciM']=$evensoat['informe_accidente'];
        $_POST['pais']=$evensoat['tipo_pais_id'];
        $_POST['dpto']=$evensoat['tipo_dpto_id'];
        $_POST['mpio']=$evensoat['tipo_mpio_id'];
        $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$accisoat['tipo_pais_id']));
        $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$accisoat['tipo_pais_id'],'Dpto'=>$accisoat['tipo_dpto_id']));
        $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$accisoat['tipo_pais_id'],'Dpto'=>$accisoat['tipo_dpto_id'],'Mpio'=>$accisoat['tipo_mpio_id']));
        $_POST['tratamiento']=$evensoat['tipo_tratamiento'];
  	    $_POST['traslado'] = $evensoat['ambulancia_propia_ips'];
  	    $_POST['tipoambulancia'] = $evensoat['tipo_ambulancia_id'];
  	    $_POST['tiponaturaleza'] = $evensoat['soat_naturaleza_evento_id'];
  	    $_POST['intervension'] = $evensoat['intervension_autoridad'];
  	    $_POST['condicionM'] = $evensoat['condicion_accidentado'];
	    }
      $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL ACCIDENTE - MODIFICAR');
      $ru='classes/BuscadorDestino/selectorCiudad.js';
      $rus='classes/BuscadorDestino/selector.php';
      $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
      $accion=ModuloGetURL('app','Soat','user','ValidarGuardarAcci');
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table><br>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=\"modulo_table_list_title\">";
      $this->salida .= "      <td width=\"50%\">DOCUMENTO";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table><br>";
      if($this->uno == 1)
      {
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table><br>";
      }
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("fechaM")."\">FECHA: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechaM\" value=\"".$_POST['fechaM']."\" maxlength=\"10\" size=\"15\">";
      $this->salida .= "      ".ReturnOpenCalendario('forma','fechaM','/')."";
      $this->salida .= "      </td>";
      $this->salida .= "      <td><label class=\"".$this->SetStyle("horarioM")."\">HORA: </label><select name=\"horarioM\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<24;$i++)
      {
        if($i<10)
        {
          if($_POST['horarioM']=="0$i")
          {
            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"0$i\">0$i</option>";
          }
        }
        else
        {
          if($_POST['horarioM']=="$i")
          {
            $this->salida .="<option value=\"$i\" selected>$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"$i\">$i</option>";
          }
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= " : ";
      $this->salida .= "      <select name=\"minuteroM\" class=\"select\">";
      $this->salida .= "      <option value=\"-1\">--</option>";
      for($i=0;$i<60;$i++)
      {
        if($i<10)
        {
          if($_POST['minuteroM']=="0$i")
          {
            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"0$i\">0$i</option>";
          }
        }
        else
        {
          if($_POST['minuteroM']=="$i")
          {
            $this->salida .="<option value=\"$i\" selected>$i</option>";
          }
          else
          {
            $this->salida .="<option value=\"$i\">$i</option>";
          }
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
	
    	//CAMPOS FURIPS
    	if($_POST[traslado] == '1')
    	{
    		$chktrasladoS = "checked";
    	}
    	elseif($_POST[traslado] == '0')
    	{
    		$chktrasladoN = "checked";
    	}
    	if($_POST[tipoambulancia] == '1')
    	{
    		$chkmedicalizadaS = "checked";
    	}
    	elseif($_POST[tipoambulancia] == '0')
    	{
    		$chkbasicaN = "checked";
    	}
    	if($_POST[intervension] == '1')
    	{
    		$chkintervensionS = "checked";
    	}
    	elseif($_POST[intervension] == '0')
    	{
    		$chkintervensionN = "checked";
    	}
    	$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
    	$this->salida .= "		<td> <label class=\"".$this->SetStyle("traslado")."\">TRASLADO EN AMBULANCIA PROPIA:</label></td>\n";
    	$this->salida .= "		<td>\n";
    	$this->salida .= "			SI&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"1\" $chktrasladoS>";
    	$this->salida .= "			NO&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"0\" $chktrasladoN>";
    	$this->salida .= "		</td>\n";
    	$this->salida .= "	</tr>\n";
    	$this->salida .= "	<tr class=\"modulo_list_oscuro\" height=\"20\">\n";
    	$this->salida .= "		<td><label class=\"".$this->SetStyle("traslado")."\">TIPO DE AMBULANCIA:</label></td>\n";
    	$this->salida .= "		<td>\n";
    	$this->salida .= "			BASICA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"0\" $chkbasicaN>";
    	$this->salida .= "			MEDICALIZADA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"1\" $chkmedicalizadaS>";
    	$this->salida .= "		</td>\n";
    	$this->salida .= "	</tr>\n";
    	$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
    	$this->salida .= "		<td><label class=\"".$this->SetStyle("intervencion")."\">INTERVINO LA AUTORIDAD:</label></td>\n";
    	$this->salida .= "		<td>\n";
    	$this->salida .= "			SI&nbsp;&nbsp;<input type=\"radio\" name=\"intervencion\" value=\"1\" $chkintervensionS>";
    	$this->salida .= "			NO&nbsp;&nbsp;<input type=\"radio\" name=\"intervencion\" value=\"0\" $chkintervensionN>";
    	$this->salida .= "		</td>\n";
    	$this->salida .= "	</tr>\n";
    	$this->salida .= "	<tr class=\"modulo_list_oscuro\" height=\"20\">\n";
    	$this->salida .= "		<td><label class=\"".$this->SetStyle("naturaleza")."\">NATURALEZA DEL EVENTO:</label></td>\n";
    	$this->salida .= "		<td>\n";
    	$this->salida .= "		<select name=\"tiponaturaleza\" class=\"select\" onChange=\"SetValor(this.value)\">\n";
    	$this->salida .=" 		<option value=\"\" selected>-------NINGUNO-------</option>\n";
    	$eventos = $this->ObtenerTiposEventos();
    	for($i=0; $i<sizeof($eventos); $i++)
    	{
    		if($_POST[tiponaturaleza] == $eventos[$i][soat_naturaleza_evento_id])
    		{
    			$this->salida .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\" selected>".$eventos[$i]['descripcion']."</option>\n";
    		}
    		else
    		{
    			$this->salida .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\">".$eventos[$i]['descripcion']."</option>\n";
    		}
    	}			
    	$this->salida .= "              </select>\n";
    	$this->salida .= "		</td>\n";
    	$this->salida .= "	</tr>\n";
      //FIN CAMPOS FURIPS
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"".$this->SetStyle("condicionM")."\" width=\"50%\">CONDICIÓN DEL ACCIDENTADO:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td class=\"label\">";
      $condic=$this->BuscarCondicion();
      if($_POST[tiponaturaleza] <> '01') $disabled = "disabled";
      for($i=0;$i<sizeof($condic);$i++)
      {
        $this->salida .= "      ".strtoupper($condic[$i]['descripcion'])."";
        if($_POST['condicionM']==$condic[$i]['condicion_accidentado'])
        {
          $this->salida .= "      <input type='radio' name='condicionM' value=\"".$condic[$i]['condicion_accidentado']."\" checked $disabled>";
        }
        else
        {
          $this->salida .= "      <input type='radio' name='condicionM' value=\"".$condic[$i]['condicion_accidentado']."\" $disabled>";
        }
      }
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("zonaM")."\">ZONA: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td class=\"label\">";
      $zonas=$this->BuscarZonaResidencia();
      for($i=0;$i<sizeof($zonas);$i++)
      {
        $this->salida .= "      ".strtoupper($zonas[$i]['descripcion'])."";
        if($_POST['zonaM']==$zonas[$i]['zona_residencia'])
        {
          $this->salida .= "      <input type='radio' name='zonaM' value=\"".$zonas[$i]['zona_residencia']."\" checked>";
        }
        else
        {
          $this->salida .= "      <input type='radio' name='zonaM' value=\"".$zonas[$i]['zona_residencia']."\">";
        }
      }
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\"label\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
      $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
      $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\"label\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
      $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td  width=\"50%\" class=\"".$this->SetStyle("epssoatM")."\">* COBERTURA DESPUES DEL TOPE (incluido FISALUD):";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>\n";
      $epsoat=$this->BuscarEpsSoat();
      $this->salida .= "        <select name=\"epssoatM\" class=\"select\">\n";
      $this->salida .= "          <option value=\"\">----SELECCIONE----</option>\n";
      $ciclo=sizeof($epsoat);
      for($i=0;$i<$ciclo;$i++)
      {
        if($_POST['epssoatM']==$epsoat[$i]['codigo_eps'])
        {
          $this->salida .="          <option value=\"".$epsoat[$i]['codigo_eps']."\" selected>".$epsoat[$i]['descripcion']."</option>\n";
        }
        else
        {
          $this->salida .="          <option value=\"".$epsoat[$i]['codigo_eps']."\">".$epsoat[$i]['descripcion']."</option>\n";
        }
      }
      $this->salida .= "        </select>\n";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"label\" width=\"50%\">* SITIO DONDE OCURRIO EL ACCIDENTE:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <textarea class=\"input-text\" name=\"lugaracciM\" style=\"width:100%\" rows=\"5\">".$_POST['lugaracciM']."</textarea>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "            <td class=\"label\" width=\"50%\">\n";
      $this->salida .= "              * INFORME DEL ACCIDENTE <br> (Relato breve de los hechos):";
      $this->salida .= "            </td>\n";
      $this->salida .= "            <td>\n";
      $this->salida .= "              <textarea class=\"input-text\" name=\"informeacciM\" style=\"width:100%\" rows=\"5\">".$_POST['informeacciM']."</textarea>\n";
      $this->salida .= "            </td>\n";
      $this->salida .= "          </tr>\n";
      //TIPO DE TRATAMIENTO PARA SER VISUALIZADO EN ELREPORTE
      $this->salida .= "          <tr class=modulo_list_oscuro>\n";
      $this->salida .= "            <td class=\"label\" width=\"50%\">\n";
      $this->salida .= "              TRATAMIENTO\n";
      $this->salida .= "            </td>\n";
      $this->salida .= "            <td class=\"label\">\n";
      if ($_POST['tratamiento']=='0')
      {
        $this->salida .= "      Observación&nbsp;<input type='radio' name='tratamiento' value=\"Observacion\" checked>&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      else
      {
        $this->salida .= "      Observación&nbsp;<input type='radio' name='tratamiento' value=\"Observacion\">&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      if ($_POST['tratamiento']=='1')
      {
        $this->salida .= "      Hospitalario&nbsp;<input type='radio' name='tratamiento' value=\"Hospitalario\" checked>&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      else
      {
        $this->salida .= "      Hospitalario&nbsp;<input type='radio' name='tratamiento' value=\"Hospitalario\">&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      if ($_POST['tratamiento']=='2')
      {
        $this->salida .= "      Ambulatorio&nbsp;<input type='radio' name='tratamiento' value=\"Ambulatorio\" checked>&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      else
      {
        $this->salida .= "      Ambulatorio&nbsp;<input type='radio' name='tratamiento' value=\"Ambulatorio\">&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      $this->salida .= "            </td>\n";
      $this->salida .= "          </tr>\n";
      $this->salida .= "            <tr class=modulo_list_claro>\n";
      $this->salida .= "              <td colspan=\"2\"align=\"center\"class=\"label\" width=\"100%\">\n";
      $this->salida .= "                * DATOS NO OBLIGATORIOS\n";
      $this->salida .= "              </td>\n";
      $this->salida .= "            </tr>\n";
      $this->salida .= "          </table>\n";
      $this->salida .= "        </fieldset>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= "  <br>\n";
      $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td align=\"center\" width=\"50%\">\n";
      $this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "      </form>\n";
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "      <form name=\"form\" action=\"$accion\" method=\"post\">\n";
      $this->salida .= "      <td align=\"center\" width=\"50%\">\n";
      $this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "      </form>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }
    /**
    * Modifica la informacion del propietario del vehoculo
    * Vuelve a la pantalla de eventos relacionados al paciente
    *
    * @return boolean
    */
    function ModificarEventoPropiVeh()
    {
      if(empty($_SESSION['soat']['eventoelegMVP']))//$this->uno == 0
      {
        $_SESSION['soat']['eventoelegMVP']=$_REQUEST['eventoeleg'];
        $evensoat=$this->BuscarEventoSoatMod($_SESSION['soat']['eventoelegMVP']);
        $_POST['asegurado']=$evensoat['asegurado'];
        $_SESSION['soat']['asegverM']=$evensoat['asegurado'];
        $_SESSION['soat']['poliverM']=$evensoat['poliza'];
        $_POST['placa']=$evensoat['placa_vehiculo'];
        $_POST['marca']=$evensoat['marca_vehiculo'];
        $_POST['tipove']=$evensoat['tipo_vehiculo'];
        $poli=explode('-',$evensoat['poliza']);
        $_POST['poliza1']=$poli[0];
        $_POST['poliza2']=$poli[1];
        $_POST['poliza3']=$poli[2];
        $_POST['sucursal']=$evensoat['sucursal'];
        if(!empty($evensoat['vigencia_desde']))
        {
          $fechades=explode('-',$evensoat['vigencia_desde']);
          $_POST['fechadesde']=$fechades[2].'/'.$fechades[1].'/'.$fechades[0];
        }
        if(!empty($evensoat['vigencia_hasta']))
        {
          $fechades=explode('-',$evensoat['vigencia_hasta']);
          $_POST['fechahasta']=$fechades[2].'/'.$fechades[1].'/'.$fechades[0];
        }
        $_POST['aseguradora']=$evensoat['tipo_id_tercero'].','.$evensoat['tercero_id'];
        $_POST[tiposerviciovehiculo] = $evensoat['tipo_servicio_vehiculo_id'];
        $vehisoat=$this->BuscarModificarEventoPropiVeh($_SESSION['soat']['eventoelegMVP']);
        $_SESSION['soat']['guarmodico']=0;//guarda
        if($vehisoat<>NULL)
        {
          $_SESSION['soat']['guarmodico']=1;//modifica
        }
        $_POST['nombreprop']=$vehisoat['nombres_propietario'];
        $_POST['apelliprop']=$vehisoat['apellidos_propietario'];
        $_POST['tidocuprop']=$vehisoat['tipo_id_propietario'];
        $_POST['documeprop']=$vehisoat['propietario_id'];
        $_POST['direccprop']=$vehisoat['direccion_propietario'];
        $_POST['telefoprop']=$vehisoat['telefono_propietario'];
        $_POST['pais']=$vehisoat['tipo_pais_id'];
        $_POST['dpto']=$vehisoat['tipo_dpto_id'];
        $_POST['mpio']=$vehisoat['tipo_mpio_id'];
        $_POST['paisE']=$vehisoat['extipo_pais_id'];
        $_POST['dptoE']=$vehisoat['extipo_dpto_id'];
        $_POST['mpioE']=$vehisoat['extipo_mpio_id'];
      }
      $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL VEHICULO - MODIFICAR');
      $ru='classes/BuscadorDestino/selectorCiudad.js';
      $rus='classes/BuscadorDestino/selector.php';
      $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
      $accion=ModuloGetURL('app','Soat','user','ValidarModificarEventoPropiVeh');
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table><br>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=\"modulo_table_list_title\">";
      $this->salida .= "      <td width=\"50%\">DOCUMENTO";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table><br>";
      if($this->uno == 1)
      {
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table><br>";
      }
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL VEHÓCULO</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"".$this->SetStyle("asegurado")."\" width=\"26%\">ASEGURADO:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td class=\"label\" colspan=\"2\">SI";
      if($_POST['asegurado']==1)
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=1 checked>";
      }
      else
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=1>";
      }
      $this->salida .= "  NO";
      if($_POST['asegurado']==2)
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=2 checked>";
      }
      else
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=2>";
      }
      if($_SESSION['soat']['asegverM'] == 3)
      {
        $this->salida .= "  FANT.";
        if($_POST['asegurado']==3)
        {
          $this->salida .= "      <input type='radio' name=\"asegurado\" value=3 checked>";
        }
        else
        {
          $this->salida .= "      <input type='radio' name=\"asegurado\" value=3>";
        }
      }
      $this->salida .= "  POLIZA FALSA";
      if($_POST['asegurado']==4)
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=4 checked>";
      }
      else
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=4>";
      }
      $this->salida .= "  POLIZA VENCIDA";
      if($_POST['asegurado']==5)
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=5 checked>";
      }
      else
      {
        $this->salida .= "      <input type='radio' name=\"asegurado\" value=5>";
      }
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      //TIPO SERVICIO DEL VEHICULO
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("tiposerviciovehiculo")."\">TIPO SERVICIO VEHICULO: </label>";
      $this->salida .= "      </td>";
      $tiposerviciosvehiculos=$this->ObtenerTiposServiciosVehiculos();
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <select name=\"tiposerviciovehiculo\" class=\"select\">";
      $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
      for($i=0;$i<sizeof($tiposerviciosvehiculos);$i++)
      {
        if($_POST[tiposerviciovehiculo]==$tiposerviciosvehiculos[$i]['tipo_servicio_vehiculo_id'])
        {
          $this->salida .="<option value=\"".$tiposerviciosvehiculos[$i]['tipo_servicio_vehiculo_id']."\" selected>".$tiposerviciosvehiculos[$i]['descripcion']."</option>";
        }
        else
        {
          $this->salida .="<option value=\"".$tiposerviciosvehiculos[$i]['tipo_servicio_vehiculo_id']."\">".$tiposerviciosvehiculos[$i]['descripcion']."</option>";
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      //FIN TIPO SERVICIO VEHICULO
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("poliza1")."\">POLIZA SOAT NO.: AT</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza1\" value=\"".$_POST['poliza1']."\" maxlength=\"4\" size=\"4\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza2\" value=\"".$_POST['poliza2']."\" maxlength=\"20\" size=\"10\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza3\" value=\"".$_POST['poliza3']."\" maxlength=\"1\" size=\"1\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("aseguradora")."\">NOMBRE ASEGURADORA: </label>";
      $this->salida .= "      </td>";
      $AsegSoat=$this->BuscarAseguradoraSoat();
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <select name=\"aseguradora\" class=\"select\">";
      $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
      $A=explode(',',$_POST['aseguradora']);
      for($i=0;$i<sizeof($AsegSoat);$i++)
      {
        if($A[1]==$AsegSoat[$i]['tercero_id'] AND $A[0]==$AsegSoat[$i]['tipo_id_tercero'])
        {
          $this->salida .="<option value=\"".$AsegSoat[$i]['tipo_id_tercero'].','.$AsegSoat[$i]['tercero_id'].','.$AsegSoat[$i]['identificador_at']."\" selected>".$AsegSoat[$i]['nombre_tercero']."</option>";
        }
        else
        {
          $this->salida .="<option value=\"".$AsegSoat[$i]['tipo_id_tercero'].','.$AsegSoat[$i]['tercero_id'].','.$AsegSoat[$i]['identificador_at']."\">".$AsegSoat[$i]['nombre_tercero']."</option>";
        }
      }
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"26%\"><label class=\"label\">* SUCURSAL O AGENCIA: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td colspan=\"2\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"sucursal\" value=\"".$_POST['sucursal']."\" maxlength=\"30\" size=\"20\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"label\" width=\"26%\">VIGENCIA DE LA POLIZA</td>";
      $this->salida .= "      <td width=\"37%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("fechadesde")."\">DESDE: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadesde\" value=\"".$_POST['fechadesde']."\" maxlength=\"10\" size=\"15\">";
      $this->salida .= "      ".ReturnOpenCalendario('forma','fechadesde','/')."";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"37%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("fechahasta")."\">HASTA: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechahasta\" value=\"".$_POST['fechahasta']."\" maxlength=\"10\" size=\"15\">";
      $this->salida .= "      ".ReturnOpenCalendario('forma','fechahasta','/')."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("placa")."\">PLACA: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"placa\" value=\"".$_POST['placa']."\" maxlength=\"8\" size=\"12\">";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"37%\"><label class=\"".$this->SetStyle("marca")."\">MARCA: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"marca\" value=\"".$_POST['marca']."\" maxlength=\"30\" size=\"25\">";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"37%\"><label class=\"".$this->SetStyle("tipove")."\">TIPO: </label>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tipove\" value=\"".$_POST['tipove']."\" maxlength=\"20\" size=\"27\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table><br>";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PROPIETARIO</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">APELLIDO(S) DEL PROPIETARIO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"apelliprop\" value=\"".$_POST['apelliprop']."\" maxlength=\"60\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">NOMBRE(S) DEL PROPIETARIO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombreprop\" value=\"".$_POST['nombreprop']."\" maxlength=\"40\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">TIPO DOCUMENTO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <select name=\"tidocuprop\" value=\"".$_POST['tidocuprop']."\" class=\"select\">";
      $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
      $this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['tidocuprop']);
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">DOCUMENTO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"documeprop\" value=\"".$_POST['documeprop']."\" maxlength=\"32\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['paisE'] && !$_POST['dptoE'] && !$_POST['mpioE'])
      {
        $_POST['paisE']=GetVarConfigAplication('DefaultPais');
        $_POST['dptoE']=GetVarConfigAplication('DefaultDpto');
        $_POST['mpioE']=GetVarConfigAplication('DefaultMpio');
      }
      $_POST['npaisE']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['paisE']));
      $this->salida .= "      <input type=\"hidden\" name=\"npaisE\" value=\"".$_POST['npaisE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"paisE\" value=\"".$_POST['paisE']."\" class=\"input-text\">";
      $_POST['ndptoE']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE']));
      $this->salida .= "      <input type=\"hidden\" name=\"ndptoE\" value=\"".$_POST['ndptoE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"dptoE\" value=\"".$_POST['dptoE']."\" class=\"input-text\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">DE:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $_POST['nmpioE']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE'],'Mpio'=>$_POST['mpioE']));
      $this->salida .= "      <input type=\"text\" name=\"nmpioE\" value=\"".$_POST['nmpioE']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpioE\" value=\"".$_POST['mpioE']."\" class=\"input-text\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar1\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,2)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">* DIRECCIÓN:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"direccprop\" value=\"".$_POST['direccprop']."\" maxlength=\"40\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"label\">* TELÓFONO:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefoprop\" value=\"".$_POST['telefoprop']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
      {
        $_POST['pais']=GetVarConfigAplication('DefaultPais');
        $_POST['dpto']=GetVarConfigAplication('DefaultDpto');
        $_POST['mpio']=GetVarConfigAplication('DefaultMpio');
      }
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("pais")."\">PAIS:</label>";
      $this->salida .= "      </td>";
      $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO:</label>";
      $this->salida .= "      </td>";
      $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("mpio")."\">CIUDAD:</label>";
      $this->salida .= "      </td>";
      $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\" class=\"label\">* DATOS NO OBLIGATORIOS</td>";
      $this->salida .= "      </tr>";
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
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      if($this->polizamala == 1)
      {
        $this->salida .="<script language='javascript'>";
        $this->salida .="alert('POLIZA ERRONEA');\n";
        $this->salida .="</script>";
        $this->polizamala = 0;
      }
      $this->salida .= ThemeCerrarTabla();
      return true;
    }
    /**
    * Captura los datos del conductor y los guarda; 
    * y recibe el identificador del accidente
    * Llama a Guardar Datos del Conductor
    *
    * @return boolean 
    */
    function ModificarEventoConduVeh()//
    {
      if(empty($_SESSION['soat']['eventoelegMVC']))//$this->uno == 0
      {
        $_SESSION['soat']['eventoelegMVC']=$_REQUEST['eventoeleg'];
        $vehisoat=$this->BuscarModificarEventoConduVeh($_SESSION['soat']['eventoelegMVC']);
        if($vehisoat==NULL)
        {
          $_POST['insertarco']=1;
        }
        else
        {
          $_POST['insertarco']=2;
          $_POST['apellicond']=$vehisoat['apellidos_conductor'];
          $_POST['nombrecond']=$vehisoat['nombres_conductor'];
          $_POST['tidocucond']=$vehisoat['tipo_id_conductor'];
          $_POST['documecond']=$vehisoat['conductor_id'];
          $_POST['direcicond']=$vehisoat['direccion_conductor'];
          $_POST['telefocond']=$vehisoat['telefono_conductor'];
          $_POST['pais']=$vehisoat['tipo_pais_id'];
          $_POST['dpto']=$vehisoat['tipo_dpto_id'];
          $_POST['mpio']=$vehisoat['tipo_mpio_id'];
          $_POST['paisE']=$vehisoat['extipo_pais_id'];
          $_POST['dptoE']=$vehisoat['extipo_dpto_id'];
          $_POST['mpioE']=$vehisoat['extipo_mpio_id'];
        }
      }
      $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL CONDUCTOR - MODIFICAR');
      $accion=ModuloGetURL('app','Soat','user','ValidarModificarEventoConduVeh');
      $ru='classes/BuscadorDestino/selectorCiudad.js';
      $rus='classes/BuscadorDestino/selector.php';
      $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table><br>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=\"modulo_table_list_title\">";
      $this->salida .= "      <td width=\"50%\">DOCUMENTO";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table><br>";
      if($this->uno == 1)
      {
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table><br>";
      }
      $this->salida .= "  <input type=\"hidden\" name=\"insertarco\" value=\"".$_POST['insertarco']."\" class=\"input-text\">";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL CONDUCTOR</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("apellicond")."\">APELLIDO(S) DEL CONDUCTOR: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"apellicond\" value=\"".$_POST['apellicond']."\" maxlength=\"60\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("nombrecond")."\">NOMBRE(S) DEL CONDUCTOR: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecond\" value=\"".$_POST['nombrecond']."\" maxlength=\"40\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("tidocucond")."\">TIPO DOCUMENTO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <select name=\"tidocucond\" value=\"".$_POST['tidocucond']."\" class=\"select\">";
      $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
      $this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['tidocucond']);
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("documecond")."\">DOCUMENTO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"documecond\" value=\"".$_POST['documecond']."\" maxlength=\"32\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['paisE'] && !$_POST['dptoE'] && !$_POST['mpioE'])
      {
        $_POST['paisE']=GetVarConfigAplication('DefaultPais');
        $_POST['dptoE']=GetVarConfigAplication('DefaultDpto');
        $_POST['mpioE']=GetVarConfigAplication('DefaultMpio');
      }
      $_POST['npaisE']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['paisE']));
      $this->salida .= "      <input type=\"hidden\" name=\"npaisE\" value=\"".$_POST['npaisE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"paisE\" value=\"".$_POST['paisE']."\" class=\"input-text\">";
      $_POST['ndptoE']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE']));
      $this->salida .= "      <input type=\"hidden\" name=\"ndptoE\" value=\"".$_POST['ndptoE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"dptoE\" value=\"".$_POST['dptoE']."\" class=\"input-text\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("mpioE")."\">DE:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $_POST['nmpioE']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE'],'Mpio'=>$_POST['mpioE']));
      $this->salida .= "      <input type=\"text\" name=\"nmpioE\" value=\"".$_POST['nmpioE']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpioE\" value=\"".$_POST['mpioE']."\" class=\"input-text\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar1\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,2)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("direcicond")."\">DIRECCIÓN:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"direcicond\" value=\"".$_POST['direcicond']."\" maxlength=\"40\" size=\"40\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("telefocond")."\">TELÓFONO:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefocond\" value=\"".$_POST['telefocond']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
      {
        $_POST['pais']=GetVarConfigAplication('DefaultPais');
        $_POST['dpto']=GetVarConfigAplication('DefaultDpto');
        $_POST['mpio']=GetVarConfigAplication('DefaultMpio');
      }
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("pais")."\">PAIS:</label>";
      $this->salida .= "      </td>";
      $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO:</label>";
      $this->salida .= "      </td>";
      $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td width=\"38%\">";
      $this->salida .= "      <label class=\"".$this->SetStyle("mpio")."\">CIUDAD:</label>";
      $this->salida .= "      </td>";
      $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\" class=\"label\">* DATOS NO OBLIGATORIOS</td>";
      $this->salida .= "      </tr>";
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
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }

    //Funcion que captura los datos de la ambulancia que atendio al accidentado
    function ModificarDatosEventoAmb()//Volida los datos de la ambulancia, guarda o modifica
    {
      IncludeLib('funciones_admision');
      if(empty($_SESSION['soat']['eventoelegMM']))//
      {
        $_SESSION['soat']['eventoelegMM']=$_REQUEST['eventoeleg'];
        $_SESSION['soat']['ambuverM']=$_REQUEST['ambueleg'];
      }
      if(empty($_SESSION['soat']['ambuverM']))
      {
        $_REQUEST['ambugumo']=1;
      }
      else
      {
        $_REQUEST['ambugumo']=2;
        $ambusoat=$this->BuscarInforAmbulancia($_SESSION['soat']['ambuverM']);
        $_POST['tidocucondA']=$ambusoat['tipo_id_paciente'];
        $_POST['docucondA']=$ambusoat['conductor_id'];
        $_POST['nombrecondA']=$ambusoat['nombre_conductor'];
        $_POST['direcondA']=$ambusoat['direccion'];
        $_POST['telecondA']=$ambusoat['telefono'];
        $_POST['paisE']=$ambusoat['extipo_pais_id'];
        $_POST['dptoE']=$ambusoat['extipo_dpto_id'];
        $_POST['mpioE']=$ambusoat['extipo_mpio_id'];
        $_POST['pais']=$ambusoat['tipo_pais_id'];
        $_POST['dpto']=$ambusoat['tipo_dpto_id'];
        $_POST['mpio']=$ambusoat['tipo_mpio_id'];
        $_POST['placaA']=$ambusoat['placa_ambulancia'];
        $_POST['lugardesdeA']=$ambusoat['lugar_desde'];
        $_POST['lugarhastaA']=$ambusoat['lugar_hasta'];
        $_POST['traslado']=$ambusoat['tipo_traslado'];
        if(!empty($ambusoat['fecha_traslado']))
        {
          $_POST['FechaTraslado']=FechaStamp($ambusoat['fecha_traslado']);
        }               
        //EXTAER EL TIPO REPORTE
        $tipo_reporte=ModuloGetVar('app','Soat','TipoReporte');
        if($tipo_reporte==='0')
        {
          $datos=$this->BuscarReporteAmbulanciaSoat($_SESSION['soat']['ambuverM']);
        }
        else
        {
          if(!IncludeFile("app_modules/Soat/reports/fpdf/reporte_soat3.inc.php"))
          {
            $this->error = "No se pudo inicializar el archivo reporte_soat3.inc.php";
            $this->mensajeDeError = "No se pudo Incluir el archivo : app_modules/Soat/reports/fpdf/reporte_soat3inc.php";
            return false;
          }
          $datos=BuscarReporteAmbulanciaSoat($_SESSION['soat']['ambuverM']);
        }
      }
      $this->salida  = ThemeAbrirTabla('SOAT - DATOS DE LA AMBULANCIA - (INSERTAR O MODIFICAR)');
      $ru='classes/BuscadorDestino/selectorCiudad.js';
      $rus='classes/BuscadorDestino/selector.php';
      $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
      if($tipo_reporte==='0')
      {
        $RUTA = $_ROOT ."cache/ambulancia_anexo.pdf";
      }
      else
      {
        $RUTA = $_ROOT ."cache/ambulancia_anexo_1.pdf";
      }
      $mostrar ="\n<script language='javascript'>\n";
      $mostrar.="var rem=\"\";\n";
      $mostrar.="function abreVentana(){\n";
      $mostrar.="    var nombre=\"\"\n";
      $mostrar.="    var url2=\"\"\n";
      $mostrar.="    var str=\"\"\n";
      $mostrar.="    var ALTO=screen.height\n";
      $mostrar.="    var ANCHO=screen.width\n";
      $mostrar.="    var nombre=\"REPORTE\";\n";
      $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
      $mostrar.="    var url2 ='$RUTA';\n";
      $mostrar.="    rem = window.open(url2, nombre, str)};\n";
      $mostrar.="</script>\n";
      $this->salida .= "$mostrar";    
      $accion=ModuloGetURL('app','Soat','user','ValidarGuardarAmbu',array('ambugumo'=>$_REQUEST['ambugumo']));
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table><br>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=\"modulo_table_list_title\">";
      $this->salida .= "      <td width=\"50%\">DOCUMENTO";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table><br>";
      if($_REQUEST['ambugumo']!=2)
      {
        $var=$this->BuscarAmbulanciasEvento($_SESSION['soat']['eventoelegMM']);
        if(!empty($var))
        {
          $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list_title\">";
          $this->salida .= "  <tr>";
          $this->salida .= "  <td>CONDUCTOR</td>";    
          $this->salida .= "  <td>TIPO TRASLADO</td>";    
          $this->salida .= "  <td>FECHA</td>";
          $this->salida .= "  <td>DESDE</td>";
          $this->salida .= "  <td>HASTA</td>";
          $this->salida .= "  <td></td>";
          $this->salida .= "  <td></td>";
          $this->salida .= "  </tr>";
          for($i=0; $i<sizeof($var); $i++)
          {               
            if( $i % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}
            $this->salida .= "      <tr class=\"$estilo\">";
            $this->salida .= "  <td>".$var[$i]['tipo_id_paciente']." ".$var[$i]['conductor_id']." - ".$var[$i]['nombre_conductor']."</td>";
            $this->salida .= "  <td>".$var[$i]['tipo_traslado']."</td>";
            $this->salida .= "  <td>".$var[$i]['fecha_traslado']."</td>";
            $this->salida .= "  <td>".$var[$i]['lugar_desde']."</td>";
            $this->salida .= "  <td>".$var[$i]['lugar_hasta']."</td>";
            $this->salida .= "      <td align=\"center\"><a href=\"javascript:abreVentana()\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"Imprimir\"></a></td>";
            $accionM=ModuloGetURL('app','Soat','user','LlamarModificarAmbulancia',array('ambulancia'=>$var[$i]['ambulancia_id']));
            $this->salida .= "      <td align=\"center\"><a href=\"$accionM\"><img src=\"".GetThemePath()."/images/editar.png\" border='0' title=\"Modificar\"></a></td>";
            $this->salida .= "  </tr>";
          }
          $this->salida .= "  </table><br>";
        }
      }
      if($this->uno == 1)
      {
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table><br>";
      }
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL CONDUCTOR DE LA AMBULANCIA</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"".$this->SetStyle("nombrecondA")."\" width=\"38%\">APELLIDOS Y NOMBRES:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td width=\"62%\">";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecondA\" value=\"".$_POST['nombrecondA']."\" maxlength=\"60\" size=\"50\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td><label class=\"".$this->SetStyle("tidocucondA")."\">TIPO DOCUMENTO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td><select name=\"tidocucondA\" value=\"".$_POST['tidocucondA']."\" class=\"select\">";
      $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
      $this->BuscarIdPaciente($tipo_id,$TipoId=$_POST['tidocucondA']);
      $this->salida .= "      </select>";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td><label class=\"".$this->SetStyle("docucondA")."\">DOCUMENTO: </label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"docucondA\" value=\"".$_POST['docucondA']."\" maxlength=\"32\" size=\"50\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['paisE'] && !$_POST['dptoE'] && !$_POST['mpioE'])
      {
          $_POST['paisE']=GetVarConfigAplication('DefaultPais');
          $_POST['dptoE']=GetVarConfigAplication('DefaultDpto');
          $_POST['mpioE']=GetVarConfigAplication('DefaultMpio');
      }
      $_POST['npaisE']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['paisE']));
      $this->salida .= "      <input type=\"hidden\" name=\"npaisE\" value=\"".$_POST['npaisE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"paisE\" value=\"".$_POST['paisE']."\" class=\"input-text\">";
      $_POST['ndptoE']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE']));
      $this->salida .= "      <input type=\"hidden\" name=\"ndptoE\" value=\"".$_POST['ndptoE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"dptoE\" value=\"".$_POST['dptoE']."\" class=\"input-text\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"".$this->SetStyle("mpioE")."\">DE:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $_POST['nmpioE']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['paisE'],'Dpto'=>$_POST['dptoE'],'Mpio'=>$_POST['mpioE']));
      $this->salida .= "      <input type=\"text\" name=\"nmpioE\" value=\"".$_POST['nmpioE']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpioE\" value=\"".$_POST['mpioE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"ncomunaE\" value=\"".$_POST['ncomunaE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"comunaE\" value=\"".$_POST['comunaE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"nbarrioE\" value=\"".$_POST['nbarrioE']."\" class=\"input-text\">";
      $this->salida .= "      <input type=\"hidden\" name=\"barrioE\" value=\"".$_POST['barrioE']."\" class=\"input-text\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar1\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,2)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td><label class=\"label\">TELÓFONO:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telecondA\" value=\"".$_POST['telecondA']."\" size=\"25\">";//maxlength=\"10\"
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td><label class=\"label\">DIRECCIÓN:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"direcondA\" value=\"".$_POST['direcondA']."\" size=\"50\">";//maxlength=\"40\"
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
      {
        $_POST['pais']=$_POST['paisE'];
        $_POST['dpto']=$_POST['dptoE'];
        $_POST['mpio']=$_POST['mpioE'];
      }
      $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
      $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\"label\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
      $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
      $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
      $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\">";
      $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td class=\"".$this->SetStyle("traslado")."\">TIPO TRASLADO: </td>";
      $this->salida .= "      <td class=\"label\">";
      if($_POST['traslado']==1)
      {  $this->salida .= "      TRASLADO INICIAL <input type=\"radio\"  name=\"traslado\" value=\"1\" checked>";        }
      else
      {  $this->salida .= "      TRASLADO INICIAL <input type=\"radio\"  name=\"traslado\" value=\"1\">";        }
      if($_POST['traslado']==2)       
      {  $this->salida .= "      &nbsp;&nbsp;&nbsp;REMISION <input type=\"radio\"  name=\"traslado\" value=\"2\" checked>";  }
      else
      {  $this->salida .= "      &nbsp;&nbsp;&nbsp;REMISION <input type=\"radio\"  name=\"traslado\" value=\"2\">";  }
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>"; 
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"label\">FECHA TRASLADO: </td>";
      $this->salida .= "                <td><input type=\"text\" name=\"FechaTraslado\" value=\"".$_POST['FechaTraslado']."\" size=\"15\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
      $this->salida .=   ReturnOpenCalendario('forma','FechaTraslado','/')."</td>";
      $this->salida .= "              </tr>";     
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td colspan=\"2\" align=\"left\" class=\"label\">";
      $this->salida .= "      TRANSPORTÓ LA VÓCTIMA:";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td><label class=\"label\">DESDE:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"lugardesdeA\" value=\"".$_POST['lugardesdeA']."\" size=\"50\">";//maxlength=\"40\"
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_oscuro>";
      $this->salida .= "      <td><label class=\"label\">HASTA:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"lugarhastaA\" value=\"".$_POST['lugarhastaA']."\" size=\"50\">";//maxlength=\"40\"
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td><label class=\"".$this->SetStyle("placaA")."\">PLACA No.:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td>";
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"placaA\" value=\"".$_POST['placaA']."\" maxlength=\"8\" size=\"25\">";//maxlength=\"40\"
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      if($datos)
      {
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td align=\"center\" colspan=\"2\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR\" onclick=\"javascript:abreVentana()\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
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
      $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
      $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\" width=\"50%\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }

    //Muestra la informacion del accidente y vehiculo relacionada a un evento
    function MostrarDatosAdicional()//Vuelve al meno de ver los eventos
    {
      if($_REQUEST['ingreso_soat']) SessionSetVar("ingreso_soat", $_REQUEST['ingreso_soat']);

      $tipo_reporte=ModuloGetVar('app','Soat','TipoReporte');
      if($_REQUEST['switch'] == 1)
      {
        $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
        $nombsoat=$_SESSION['soat']['evento']['nombresoat'];

        $datos = $this->BuscarReporteEventoSoat($nombsoat['tipo_id_paciente'],$nombsoat['paciente_id'],$_REQUEST['eventoeleg'],$tipo_reporte,SessionGetVar("ingreso_soat"));
        $volver="VOLVER A EVENTOS";
      }
      if($_REQUEST['switch'] == 2)
      {
        $accion=ModuloGetURL('app','Soat','user','DatosConsumo');
        $nombsoat=$_SESSION['soat']['consumo']['nombresoat'];
        $datos=$this->BuscarReporteEventoSoat($nombsoat['tipo_id_paciente'],$nombsoat['paciente_id'],$_REQUEST['eventoeleg'],"",SessionGetVar("ingreso_soat"));
        $volver="VOLVER A CONSUMOS";
      }
      $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL EVENTO');
      if($tipo_reporte==='0')
      {
        $RUTA1 = $_ROOT ."cache/reclamacion_entidades_forecat.pdf";//REPORTE DE CALI FUSOAT
        $RUTA = $_ROOT ."cache/reclamacion_entidades.pdf";//REPORTE DE CALI FURIP
      }
      else
      {
        $RUTA1 = $_ROOT ."cache/reclamacion_entidades_1.pdf";//REPORTE PARA TULUA FUSOAT
        $RUTA = $_ROOT ."cache/reclamacion_entidades.pdf";//REPORTE PARA TULUA FURIP
      }
      $mostrar ="\n<script language='javascript'>\n";
      $mostrar.="var rem=\"\";\n";
      $mostrar.="  function abreVentana1(){\n";
      $mostrar.="    var nombre=\"\"\n";
      $mostrar.="    var url2=\"\"\n";
      $mostrar.="    var str=\"\"\n";
      $mostrar.="    var ALTO=screen.height\n";
      $mostrar.="    var ANCHO=screen.width\n";
      $mostrar.="    var nombre=\"REPORTE\";\n";
      $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
      $mostrar.="    var url2 ='$RUTA1';\n";
      $mostrar.="    rem = window.open(url2, nombre, str)};\n";
      $mostrar.="  function abreVentana(){\n";
      $mostrar.="    var nombre=\"\"\n";
      $mostrar.="    var url2=\"\"\n";
      $mostrar.="    var str=\"\"\n";
      $mostrar.="    var ALTO=screen.height\n";
      $mostrar.="    var ANCHO=screen.width\n";
      $mostrar.="    var nombre=\"REPORTE\";\n";
      $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
      $mostrar.="    var url2 ='$RUTA';\n";
      $mostrar.="    rem = window.open(url2, nombre, str)};\n";
      $mostrar.="</script>\n";
      $this->salida .= "$mostrar";
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA EMPRESA ACTUAL</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"70%\">";
      $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr><td><br>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"40%\">EMPRESA DONDE FUE CREADO EL EVENTO";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"60%\">";
      $this->salida .= "      ".$_REQUEST['razover']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"40%\">DOCUMENTO DEL PACIENTE";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"60%\">";
      $this->salida .= "      ".$nombsoat['tipo_id_paciente']."".' - '."".$nombsoat['paciente_id']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=modulo_list_claro>";
      $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"40%\">NOMBRE DEL PACIENTE";
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\" width=\"60%\">";
      $this->salida .= "      ".$nombsoat['primer_apellido']."".' '."".$nombsoat['segundo_apellido']."".' '."".$nombsoat['primer_nombre']."".' '."".$nombsoat['segundo_nombre']."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table><br>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr class=\"modulo_table_list_title\">";
      $this->salida .= "      <td width=\"25%\">POLIZA</td>";
      $this->salida .= "      <td width=\"25%\">SALDO</td>";
      $this->salida .= "      <td width=\"25%\">CONDICIÓN</td>";
      $this->salida .= "      <td width=\"25%\">ASEGURADO</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td align=\"center\">";
      $this->salida .= $_REQUEST['poliver'];
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\">";
      $saldover=$_REQUEST['saldver'];
      $this->salida .= number_format(($saldover), 2, ',', '.');
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\">";
      $condic=$this->BuscarCondicion();
      for($i=0;$i<sizeof($condic);$i++)
      {
          if($_REQUEST['condver']==$condic[$i]['condicion_accidentado'])
          {
              $this->salida .= "      ".strtoupper($condic[$i]['descripcion'])."";
          }
      }
      $this->salida .= "      </td>";
      $this->salida .= "      <td align=\"center\">";
      if($_REQUEST['asegver']==1)
      {
          $this->salida .= "SI";
      }
      else if($_REQUEST['asegver']==2)
      {
          $this->salida .= "NO";
      }
      else if($_REQUEST['asegver']==3)
      {
          $this->salida .= "FANT.";
      }
      else if($_REQUEST['asegver']==4)
      {
          $this->salida .= "P. FALSA";
      }
      else if($_REQUEST['asegver']==5)
      {
          $this->salida .= "P. VENCIDA";
      }
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr><td><br>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTE</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      if(!empty($_REQUEST['acciver']))
      {
        $accisoat=$this->BuscarInforAccidente($_REQUEST['acciver']);
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">FECHA DEL ACCIDENTE";
        $this->salida .= "      </td>";
        $fecha=explode(' ',$accisoat['fecha_accidente']);
        $fechamos=explode('-',$fecha[0]);
        $this->salida .= "      <td width=\"70%\">".$fechamos[2].'/'.$fechamos[1].'/'.$fechamos[0]."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">HORA DEL ACCIDENTE";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>".$fecha[1]."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">ZONA</td>";
        $this->salida .= "      <td>";
        $zonas=$this->BuscarZonaResidencia();
        for($i=0;$i<sizeof($zonas);$i++)
        {
          if($accisoat['zona']==$zonas[$i]['zona_residencia'])
          {
            $this->salida .= "      ".strtoupper($zonas[$i]['descripcion'])."";
          }
        }
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $Pais=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$accisoat['tipo_pais_id']));
        $Dpto=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$accisoat['tipo_pais_id'],'Dpto'=>$accisoat['tipo_dpto_id']));
        $Mpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$accisoat['tipo_pais_id'],'Dpto'=>$accisoat['tipo_dpto_id'],'Mpio'=>$accisoat['tipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">PAIS</td>";
        $this->salida .= "      <td>".$Pais."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">DEPARTAMENTO</td>";
        $this->salida .= "      <td>".$Dpto."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">MUNICIPIO</td>";
        $this->salida .= "      <td>".$Mpio."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\">ENTIDAD PROMOTORA DE SALUD";
        $this->salida .= "      </td>";
        if(!empty($_REQUEST['epsver']))
        {
          $epsoat=$this->BuscarEpsSoat();
          $ciclo=sizeof($epsoat);
          for($i=0;$i<$ciclo;$i++)
          {
            if($_REQUEST['epsver']==$epsoat[$i]['codigo_eps'])
            {
              $this->salida .="<td>".$epsoat[$i]['descripcion']."</td>";
            }
          }
        }
        else
        {
          $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">SITIO DEL ACCIDENTE</td>";
        if(!empty($accisoat['sitio_accidente']))
        {
          $this->salida .= "      <td>".$accisoat['sitio_accidente']."</td>";
        }
        else
        {
          $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">INFORME DEL ACCIDENTE</td>";
        if(!empty($accisoat['informe_accidente']))
        {
          $this->salida .= "      <td>".$accisoat['informe_accidente']."</td>";
        }
        else
        {
          $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
      }
      else
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
        $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN SOBRE EL ACCIDENTE'";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
      }
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr><td><br>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL VEHÍCULO</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $vehisoat=$this->BuscarEventoSoatMod($_REQUEST['eventoeleg']);
      if(!empty($vehisoat))
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">PLACA";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">".$vehisoat['placa_vehiculo']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">MARCA";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>".$vehisoat['marca_vehiculo']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">TIPO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>".$vehisoat['tipo_vehiculo']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
      }
      else
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
        $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN SOBRE EL VEHÍCULO'";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
      }
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr><td><br>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PROPIETARIO</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $vehisoat=$this->BuscarModificarEventoPropiVeh($_REQUEST['eventoeleg']);
      if(!empty($vehisoat))
      {
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">APELLIDOS DEL PROPIETARIO</td>";
        if(!empty($vehisoat['apellidos_propietario']))
        {
            $this->salida .= "      <td width=\"70%\">".$vehisoat['apellidos_propietario']."</td>";
        }
        else
        {
            $this->salida .= "      <td width=\"70%\">'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">NOMBRES DEL PROPIETARIO</td>";
        if(!empty($vehisoat['nombres_propietario']))
        {
            $this->salida .= "      <td>".$vehisoat['nombres_propietario']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">DOCUMENTO DEL PROPIETARIO</td>";
        if(!empty($vehisoat['tipo_id_propietario'])&&!empty($vehisoat['propietario_id']))
        {
            $this->salida .= "      <td>".$vehisoat['tipo_id_propietario'].' - '.$vehisoat['propietario_id']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $VMpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$vehisoat['extipo_pais_id'],'Dpto'=>$vehisoat['extipo_dpto_id'],'Mpio'=>$vehisoat['extipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">EXPEDIDO EN</td>";
        $this->salida .= "      <td>".$VMpio."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">TELÓFONO DEL PROPIETARIO</td>";
        if(!empty($vehisoat['telefono_propietario']))
        {
            $this->salida .= "      <td>".$vehisoat['telefono_propietario']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">DIRECCIÓN DEL PROPIETARIO</td>";
        if(!empty($vehisoat['direccion_propietario']))
        {
            $this->salida .= "      <td>".$vehisoat['direccion_propietario']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $VPais=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$vehisoat['tipo_pais_id']));
        $VDpto=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$vehisoat['tipo_pais_id'],'Dpto'=>$vehisoat['tipo_dpto_id']));
        $VMpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$vehisoat['tipo_pais_id'],'Dpto'=>$vehisoat['tipo_dpto_id'],'Mpio'=>$vehisoat['tipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">PAIS</td>";
        $this->salida .= "      <td>".$VPais."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">DEPARTAMENTO</td>";
        $this->salida .= "      <td>".$VDpto."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">MUNICIPIO</td>";
        $this->salida .= "      <td>".$VMpio."</td>";
        $this->salida .= "      </tr>";
      }
      else
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
        $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN SOBRE EL PROPIETARIO'";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
      }
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr><td><br>";
      $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL CONDUCTOR</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      $vehisoat=$this->BuscarModificarEventoConduVeh($_REQUEST['eventoeleg']);
      if(!empty($vehisoat))
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">APELLIDOS DEL CONDUCTOR</td>";
        $this->salida .= "      <td width=\"70%\">";
        $this->salida .= "      ".$vehisoat['apellidos_conductor']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">NOMBRES DEL CONDUCTOR</td>";
        $this->salida .= "      <td>";
        $this->salida .= "      ".$vehisoat['nombres_conductor']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">DOCUMENTO DEL CONDUCTOR</td>";
        $this->salida .= "      <td>";
        $this->salida .= "      ".$vehisoat['tipo_id_conductor'].' - '.$vehisoat['conductor_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $VMpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$vehisoat['extipo_pais_id'],'Dpto'=>$vehisoat['extipo_dpto_id'],'Mpio'=>$vehisoat['extipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">EXPEDIDO EN</td>";
        $this->salida .= "      <td>".$VMpio."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">TELÉFONO DEL CONDUCTOR</td>";
        if(!empty($vehisoat['telefono_conductor']))
        {
            $this->salida .= "      <td>".$vehisoat['telefono_conductor']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">DIRECCIÓN DEL CONDUCTOR</td>";
        if(!empty($vehisoat['direccion_conductor']))
        {
            $this->salida .= "      <td>".$vehisoat['direccion_conductor']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $VPais=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$vehisoat['tipo_pais_id']));
        $VDpto=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$vehisoat['tipo_pais_id'],'Dpto'=>$vehisoat['tipo_dpto_id']));
        $VMpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$vehisoat['tipo_pais_id'],'Dpto'=>$vehisoat['tipo_dpto_id'],'Mpio'=>$vehisoat['tipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">PAIS</td>";
        $this->salida .= "      <td>".$VPais."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">DEPARTAMENTO</td>";
        $this->salida .= "      <td>".$VDpto."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">MUNICIPIO</td>";
        $this->salida .= "      <td>".$VMpio."</td>";
        $this->salida .= "      </tr>";
      }
      else
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
        $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN SOBRE EL CONDUCTOR'";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
      }
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr><td><br>";
      $this->salida .= "  <fieldset  class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA AMBULANCIA</legend>";
      $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
      if(!empty($_REQUEST['ambuver']))
      {
        $ambusoat=$this->BuscarInforAmbulancia($_REQUEST['ambuver']);
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">APELLIDOS Y NOMBRES DEL CONDUCTOR</td>";
        $this->salida .= "      <td width=\"70%\">".$ambusoat['nombre_conductor']."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">DOCUMENTO DEL CONDUCTOR</td>";
        $this->salida .= "      <td>".$ambusoat['tipo_id_paciente'].' - '.$ambusoat['conductor_id']."</td>";
        $this->salida .= "      </tr>";
        $VMpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$ambusoat['extipo_pais_id'],'Dpto'=>$ambusoat['extipo_dpto_id'],'Mpio'=>$ambusoat['extipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">EXPEDIDO EN</td>";
        $this->salida .= "      <td>".$VMpio."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">TELÉFONO DEL CONDUCTOR</td>";
        if(!empty($ambusoat['telefono']))
        {
            $this->salida .= "      <td>".$ambusoat['telefono']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">DIRECCIÓN DEL CONDUCTOR</td>";
        if(!empty($ambusoat['direccion']))
        {
            $this->salida .= "      <td>".$ambusoat['direccion']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $VPais=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$ambusoat['tipo_pais_id']));
        $VDpto=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$ambusoat['tipo_pais_id'],'Dpto'=>$ambusoat['tipo_dpto_id']));
        $VMpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$ambusoat['tipo_pais_id'],'Dpto'=>$ambusoat['tipo_dpto_id'],'Mpio'=>$ambusoat['tipo_mpio_id']));
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">PAIS</td>";
        $this->salida .= "      <td>".$VPais."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">DEPARTAMENTO</td>";
        $this->salida .= "      <td>".$VDpto."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">MUNICIPIO</td>";
        $this->salida .= "      <td>".$VMpio."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"2\" align=\"left\" class=\"label\">";
        $this->salida .= "      TRANSPORTÓ LA VÍCTIMA";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">DESDE</td>";
        if(!empty($ambusoat['lugar_desde']))
        {
            $this->salida .= "      <td>".$ambusoat['lugar_desde']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"label\">HASTA</td>";
        if(!empty($ambusoat['lugar_hasta']))
        {
            $this->salida .= "      <td>".$ambusoat['lugar_hasta']."</td>";
        }
        else
        {
            $this->salida .= "      <td>'NO SE ENCONTRÓ INFORMACIÓN'</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\">PLACA</td>";
        $this->salida .= "      <td>".$ambusoat['placa_ambulancia']."</td>";
        $this->salida .= "      </tr>";
      }
      else
      {
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
        $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN SOBRE LA AMBULANCIA'";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
      }
      $this->salida .= "      </table>";
      $this->salida .= "  </fieldset>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  </table>";
      $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
      if($datos)
      {	    
        $this->salida .= "<tr>";
        $this->salida .= "<td align=\"left\" width=\"50%\"><br>";
        $this->salida .= "<input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR ANTERIOR\" onclick=\"javascript:abreVentana1()\">";
        $this->salida .= "</td>";
        $this->salida .= "<td align=\"right\" width=\"50%\"><br>";
        $this->salida .= "<input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:abreVentana()\">";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
      }
      $this->salida .= "  <tr>";
      $this->salida .= "  <td align=\"center\" colspan=\"2\"><br>";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"$volver\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $accion=ModuloGetURL('app','Soat','user','ImprimirSoatEven',array('switch'=>$_REQUEST['switch'],
        'acciver'=>$_REQUEST['acciver'],'razover'=>$_REQUEST['razover'],'ambuver'=>$_REQUEST['ambuver'],
        'poliver'=>$_REQUEST['poliver'],'saldver'=>$_REQUEST['saldver'],'condver'=>$_REQUEST['condver'],
        'asegver'=>$_REQUEST['asegver'],'epsver'=>$_REQUEST['epsver'],'eventoeleg'=>$_REQUEST['eventoeleg']));
      $this->salida .= "  <form name=\"imprimir\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }

    //
    function RemitirEventoAcc()//
    {
        if(empty($_SESSION['soat']['eventoelegRE']))//$this->uno == 0
        {
            $_SESSION['soat']['eventoelegRE']=$_REQUEST['eventoeleg'];
        }
        $this->salida  = ThemeAbrirTabla('SOAT - REMISIONES DEL PACIENTE');
        $accion=ModuloGetURL('app','Soat','user','IngresaRemisionSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">DOCUMENTO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"8%\" >REMISIÓN</td>";
        $this->salida .= "      <td width=\"64%\">REMITIDO A</td>";
        $this->salida .= "      <td width=\"12%\">FECHA REMISIÓN</td>";
        $this->salida .= "      <td width=\"12%\">FECHA REGISTRO</td>";
        $this->salida .= "      <td width=\"4%\" >INFO</td>";
        $this->salida .= "      </tr>";
        $j=0;
        $k=1;
        $evensoat=$this->BuscarRemitirEventoAcc($_SESSION['soat']['eventoelegRE']);
        $ciclo=sizeof($evensoat);
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
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "".($i+1)."";
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= $evensoat[$i]['descripcion'];
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $fecha=explode(' ',$evensoat[$i]['fecha_remision']);
            $fecha=explode('-',$fecha[0]);
            $this->salida .= $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $fecha=explode(' ',$evensoat[$i]['fecha_registro']);
            $fecha=explode('-',$fecha[0]);
            $this->salida .= $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $this->salida .= "</td>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','ConsultarRemitirEventoAcc',array('remisieleg'=>$evensoat[$i]['remision_id'])) ."\">";
            $this->salida .= "<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\"></a>";
            $this->salida .= "</td>";
        }
        if(empty($evensoat))
        {
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\" class=\"label\" colspan=\"5\">";
            $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN DEL PACIENTE EN LAS REMISIONES'";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"remision\" value=\"REMISIÓN\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $accion=ModuloGetURL('app','Soat','user','CrearCertificado');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"remision\" value=\"CONSTANCIA\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" colspan=\"2\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ConsultarRemitirEventoAcc()//
    {
        $this->salida  = ThemeAbrirTabla('SOAT - CONSULTAR REMISIÓN DEL PACIENTE');
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">DOCUMENTO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $datos=$this->BuscarConsultarRemitirEventoAcc($_SESSION['soat']['eventoelegRE'],$_REQUEST['remisieleg']);
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA REMISIÓN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        
	$this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">TIPO REFERENCIA:</td>";
        $this->salida .= "      <td  width=\"70%\">";
        $this->salida .= "      ".$datos['referencia']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
	$this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">CODIGO INSCRIPCIÓN:</td>";
        $this->salida .= "      <td  width=\"70%\">";
        $this->salida .= "      ".$datos['codigo_inscripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
	if($datos['nombre_profesional_recibe'])
	{
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">PROFESIONAL QUE RECIBE:</td>";
		$this->salida .= "      <td  width=\"70%\">";
		$this->salida .= "      ".$datos['nombre_profesional_recibe']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">CARGO PROFESIONAL QUE RECIBE:</td>";
		$this->salida .= "      <td  width=\"70%\">";
		$this->salida .= "      ".$datos['profesional_recibe_cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
	}
		
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"label\" width=\"30%\">CENTROS DE REMISIÓN:</td>";
        $this->salida .= "      <td  width=\"70%\">";
        $this->salida .= "      ".$datos['descripcion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"label\">FECHA: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $fecha=explode(' ',$datos['fecha_remision']);
        $fecha=explode('-',$fecha[0]);
        $this->salida .= "      ".$fecha[2]."".'/'."".$fecha[1]."".'/'."".$fecha[0]." <B>HORA:</B> ".$datos['hora']."" ;
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"label\">OBSERVACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $this->salida .= "      ".$datos['observacion']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','RemitirEventoAcc');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"100%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function CrearCertificado()//
    {
        $this->salida  = ThemeAbrirTabla('SOAT - CONSTANCIA DE ATENCIÓN');
        $accion=ModuloGetURL('app','Soat','user','ValidarCrearCertificado');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">DOCUMENTO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA CONSTANCIA</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("compania")."\">COMPAÑIA ASEGURADORA $:</label>";
        $this->salida .= "      <td  width=\"70%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"compania\" value=\"".$_POST['compania']."\" maxlength=\"13\" size=\"20\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("consorcio")."\">CONSORCIO $:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"consorcio\" value=\"".$_POST['consorcio']."\" maxlength=\"13\" size=\"20\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("observacion")."\">OBSERVACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $this->salida .= "      <textarea class=\"input-text\" name=\"observacion\" cols=\"60\" rows=\"8\">".$_POST['observacion']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"IMPRIMIR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $accion=ModuloGetURL('app','Soat','user','RemitirEventoAcc');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function ImprimirCrearCertificado()//
    {
        $dat=$this->BuscarReporteCertificadoSoat($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente'],
        $_SESSION['soat']['evento']['nombresoat']['paciente_id'],
        $_SESSION['soat']['eventoelegRE'],$_POST['compania'],$_POST['consorcio'],$_POST['observacion']);
        $this->salida  = ThemeAbrirTabla('SOAT - CONSTANCIA DE ATENCIÓN');
        $RUTA = $_ROOT ."cache/constancia_atencion.pdf";
        $mostrar ="\n<script language='javascript'>\n";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $mostrar.="</script>\n";
        $this->salida .= "$mostrar";
        $accion=ModuloGetURL('app','Soat','user','CrearCertificado');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">DOCUMENTO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA CONSTANCIA</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("compania")."\">COMPAÑIA ASEGURADORA $:</label>";
        $this->salida .= "      <td  width=\"70%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"compania\" value=\"".$_POST['compania']."\" maxlength=\"13\" size=\"20\" readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("consorcio")."\">CONSORCIO $:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"consorcio\" value=\"".$_POST['consorcio']."\" maxlength=\"13\" size=\"20\" readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("suma")."\">TOTAL:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $_POST['suma']=$_POST['compania']+$_POST['consorcio'];
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"suma\" value=\"".$_POST['suma']."\" maxlength=\"13\" size=\"20\" readonly>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("observacion")."\">OBSERVACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\">";
        $this->salida .= "      <textarea readonly class=\"input-text\" name=\"observacion\" cols=\"60\" rows=\"8\">".$_POST['observacion']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        if($dat)
        {
            $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:abreVentana()\">";
        }
        else
        {
            $this->salida .= "  <input disabled=\"true\" class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:abreVentana()\">";
        }
        $this->salida .= "  </form>";
        $this->salida .= "  </td>";
        $accion=ModuloGetURL('app','Soat','user','CrearCertificado');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" colspan=\"2\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL EVENTO\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function IngresaRemisionSoat()//
    {
        if($_POST['referencia'] == 'R')
	{
		$referenciaR = "checked";
	}
	else
	$referenciaOS = "checked";
	
	$js = "<script>\n";
	$js .= "function verfecharecepcion(valor){\n";
	$js .= "  if(document.getElementById('fecharecepcion').style.display == 'none' && valor == 'R'){\n";
	$js .= "   document.getElementById('fecharecepcion').style.display = 'block';\n";
	$js .= "  }else{\n";
	$js .= "  if(valor == 'OS'){\n";
	$js .= "   document.getElementById('fecharecepcion').style.display = 'none';\n";
	$js .= "   }\n";
	$js .= "  }\n";
	$js .= "}\n";
	$js .= "function SetInscripcion(valor){\n";
	$js .= "   document.getElementById('inscripcion').value = valor;\n";
	$js .= "}\n";
	$js .= "</script>\n";
	$this->salida .= "$js";
        $this->salida .= ThemeAbrirTabla('SOAT - REMITIR AL PACIENTE');
        $accion=ModuloGetURL('app','Soat','user','ValidaIngresaRemisionSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"50%\">DOCUMENTO";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">NOMBRE DEL PACIENTE";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      ".$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE LA REMISIÓN</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
	
//CAMPOS FURIPS
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("referencia")."\">TIPO REFERENCIA:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\" colspan=\"2\">";
	//$this->salida .= "      <input type=\"radio\" name=\"referencia\" value=\"R\" $referenciaR onclick=\"verfecharecepcion(this.value);\"> REMISIÓN <input type=\"radio\" name=\"referencia\" value=\"OS\" $referenciaOS onclick=\"verfecharecepcion(this.value);\"> ORDEN SERVICIO";
	$this->salida .= "      <input type=\"checkbox\" name=\"referencia\" value=\"R\" $referenciaR> REMISIÓN <input type=\"checkbox\" name=\"referencia\" value=\"OS\" $referenciaOS> ORDEN SERVICIO";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";	
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("centrosrem")."\" width=\"30%\">CENTROS DE REMISIÓN:</td>";
        $this->salida .= "      <td  width=\"70%\" colspan=\"2\">";
        $remision=$this->BuscarCentroRemision();
        $this->salida .= "      <select name=\"centrosrem\" class=\"select\" onchange=\"SetInscripcion(this.value);\">";
        $this->salida .= "      <option value=\"\">-- SELECCIONE --</option>";
        for($i=0;$i<sizeof($remision);$i++)
        {
            if($remision[$i]['centro_remision']==$_POST['centrosrem'])
            {
                $this->salida .="<option value=\"".$remision[$i]['centro_remision']."\" selected>".$remision[$i]['descripcion']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$remision[$i]['centro_remision']."\">".$remision[$i]['descripcion']."</option>";
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fecha")."\">FECHA: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"40%\">";
        if(empty($_POST['fecha']))
        {
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".date ("d/m/Y")."\" maxlength=\"10\" size=\"15\">";
            $this->salida .= "".ReturnOpenCalendario('forma','fecha','/')."";
        }
        else
        {
            $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".$_POST['fecha']."\" maxlength=\"10\" size=\"15\">";
            $this->salida .= "".ReturnOpenCalendario('forma','fecha','/')."";
        }
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"30%\"><label class=\"".$this->SetStyle("hora")."\"> HORA </label>";
        $this->salida .= "      <select name=\"horario2\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['horario2']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['horario2']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"minutero2\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['minutero2']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['minutero2']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
	
	//
	
	//PROFESIONAL Q REMITE
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("prefesionalrem")."\" width=\"30%\">PROFESIONAL QUE REMITE:</td>";
        $this->salida .= "      <td  width=\"70%\" colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" name=\"prefesionalr\" maxlength=\"64\" size=\"64\" value=\"".$_POST['prefesionalr']."\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("cargoprefesionalr")."\" width=\"30%\">CARGO:</td>";
        $this->salida .= "      <td  width=\"70%\" colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" name=\"cargoprefesionalr\" maxlength=\"32\" size=\"32\" value=\"".$_POST['cargoprefesionalr']."\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
	//FIN PROFESIONAL Q REMITE
	
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">";
	//$this->salida .= "     <div name=\"fecharecepcion\" id='fecharecepcion' style=\"display:none\">";
	$this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "        <tr class=modulo_list_claro>";
        $this->salida .= "        <td width=\"30%\">";
        $this->salida .= "        <label class=\"".$this->SetStyle("fecharecepcion")."\">RECEP. REMISION:</label>";
        $this->salida .= "        </td>";
        $this->salida .= "        <td width=\"30%\">";
	$this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"fecharecepcion\" value=\"".date ("d/m/Y")."\" maxlength=\"10\" size=\"15\">";
	$this->salida .= "        ".ReturnOpenCalendario('forma','fecharecepcion','/')."";
        $this->salida .= "        </td>";
        $this->salida .= "      <td width=\"30%\"><label class=\"".$this->SetStyle("hora")."\">HORA RECEP. REM.</label>";
        $this->salida .= "      <select name=\"horario2recepcion\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['horario2recepcion']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['horario2recepcion']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"minutero2recepcion\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['minutero2recepcion']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['minutero2recepcion']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
	$this->salida .= "       </table>";
	//$this->salida .= "     </div>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
	//
	
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("inscripcion")."\">CÓDIGO INSCRIPCIÓN</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\" colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" name=\"inscripcion\" id=\"inscripcion\" maxlength=\"32\" size=\"32\" value=\"".$_POST['inscripcion']."\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";	
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("prefesionalrem")."\" width=\"30%\">PROFESIONAL QUE RECIBE:</td>";
        $this->salida .= "      <td  width=\"70%\" colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" name=\"prefesionalrem\" maxlength=\"64\" size=\"64\" value=\"".$_POST['prefesionalrem']."\">";
/*        $remision=$this->BuscarProfesionalRemision();
        $this->salida .= "      <select name=\"prefesionalrem\" class=\"select\">";
        $this->salida .= "      <option value=\"\">-- SELECCIONE --</option>";
        for($i=0;$i<sizeof($remision);$i++)
        {
            if($remision[$i]['tercero_id']==$_POST['prefesionalrem'])
            {
                $this->salida .="<option value=\"".$remision[$i]['tercero_id']."\" selected>".$remision[$i]['nombre']."</option>";
            }
            else
            {
                $this->salida .="<option value=\"".$remision[$i]['tercero_id']."\">".$remision[$i]['nombre']."</option>";
            }
        }
        $this->salida .= "      </select>";*/
        $this->salida .= "      </td>";
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("cargoprefesionalrem")."\" width=\"30%\">CARGO PROFESIONAL QUE RECIBE:</td>";
        $this->salida .= "      <td  width=\"70%\" colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" name=\"cargoprefesionalrem\" maxlength=\"32\" size=\"32\" value=\"".$_POST['cargoprefesionalrem']."\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
//FIN CAMPOS FURIPS

        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"30%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("observacion")."\">OBSERVACIÓN:</label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"70%\" colspan=\"2\">";
        $this->salida .= "      <textarea class=\"input-text\" name=\"observacion\" cols=\"45\" rows=\"4\">".$_POST['observacion']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
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
        $accion=ModuloGetURL('app','Soat','user','RemitirEventoAcc');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Captura la información del paciente para buscar el evento y agregar el consumo
    function ConsumoSoat()//Llama a Datos Consumo
    {
        UNSET($_SESSION['soat']);
        $this->salida = ThemeAbrirTabla('CONSUMOS SOAT - DATOS DEL ACCIDENTADO');
        $accion=ModuloGetURL('app','Soat','user','DatosConsumo');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"45%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ACCIDENTADO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td class=\"label\" width=\"50%\">TIPO DOCUMENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"50%\">";
        $this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
        $tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
        $this->BuscarIdPaciente($tipo_id,$TipoId=$_REQUEST['TipoDocum']);
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td class=\"label\">DOCUMENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" size=\"26\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Muestra los resultados de los eventos asociados con el paciente, se escoge uno y se ingresa el consumo
    function DatosConsumo()//Llama a Ingresar Datos del consumo una vez elegido un evento
    {
        if(empty($_REQUEST['Documento']) AND empty($_SESSION['soat']['consumo']['Documento']))
        {
            $this->ConsumoSoat();
            return true;
        }
        if(empty($_SESSION['soat']['consumo']['Documento']))
        {
            $_SESSION['soat']['consumo']['TipoDocum']=$_REQUEST['TipoDocum'];
            $_SESSION['soat']['consumo']['Documento']=$_REQUEST['Documento'];
        }
        $_SESSION['soat']['consumo']['nombresoat']=$this->BuscarNombrePaci($_SESSION['soat']['consumo']['TipoDocum'],$_SESSION['soat']['consumo']['Documento']);
        if(empty($_SESSION['soat']['consumo']['nombresoat']))
        {
            $this->frmError["MensajeError"]="EL TIPO DOCUMENTO '".$var['0']."' CON No. '".$_POST['Documento']."' NO SE ENCONTRÓ";
            $this->uno=1;
            $this->ConsumoSoat();
            return true;
        }
        else
        {
        //1 guarda
        //2 modifica
            UNSET($_SESSION['soat']['evenconsumo']);
            UNSET($_SESSION['soat']['saldoconsu']);
            UNSET($_SESSION['soat']['policonsu']);
            UNSET($_SESSION['soat']['consumoele']);
            UNSET($_SESSION['soat']['valorviejo']);
            $evensoat=$this->BuscarEventoSoat($_SESSION['soat']['consumo']['TipoDocum'],$_SESSION['soat']['consumo']['Documento']);
            $this->salida  = ThemeAbrirTabla('EVENTOS SOAT RELACIONADOS CON EL PACIENTE - CONSUMOS');
            $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "  <tr><td>";
            $this->salida .= "      <table border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"70%\">";
            $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"70%\">";
            $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
            $this->salida .= "  </td></tr>";
            $this->salida .= "  <tr><td>";
//
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"40%\">";
            $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['consumo']['nombresoat']['paciente_id']."";
            $this->salida .= "      </td>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"40%\">";
            $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_nombre']."";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
//
            $this->salida .= "  </td></tr>";
            $this->salida .= "  <tr><td>";
//
            $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr><td width=\"60%\">";
//
            $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">CONSUMOS EVENTOS</legend>";
            if(!empty($evensoat))
            {
/*              $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"40%\">";
                $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['consumo']['nombresoat']['paciente_id']."";
                $this->salida .= "      </td>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"40%\">";
                $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_nombre']."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </table><br>";*/
                $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "      <td width=\"3%\" >No.</td>";
                $this->salida .= "      <td width=\"14%\">POLIZA</td>";
                $this->salida .= "      <td width=\"30%\">ASEGURADORA</td>";
                //$this->salida .= "      <td width=\"16%\">SALDO</td>";
                $this->salida .= "      <td width=\"10%\">CONDICIÓN</td>";
                $this->salida .= "      <td width=\"10%\">ASEGURADO</td>";
                $this->salida .= "      <td width=\"5%\" >ACCID.</td>";
                $this->salida .= "      <td width=\"12%\">CONSUMOS</td>";
                $this->salida .= "      </tr>";
                $i=0;
                $j=0;
                $k=1;
                $condic=$this->BuscarCondicion();
                $ciclo=sizeof($evensoat);
                while($i<$ciclo)
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
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "".$k."";
                    $this->salida .= "</td>";
                    if($evensoat[$i]['poliza']==$evensoat[$i+1]['poliza'])
                    {
                        $k++;
                    }
                    else
                    {
                        $k=1;
                    }
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $evensoat[$i]['poliza'];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $evensoat[$i]['nombre_tercero'];
                    $this->salida .= "</td>";
//
                    $saldoevent=$evensoat[$i]['saldo'];
//
/*                  $this->salida .= "<td align=\"right\">";
                    $saldoevent=$evensoat[$i]['saldo'];
                    $this->salida .= number_format(($saldoevent), 2, ',', '.');
                    $this->salida .= "</td>";*/
                    $this->salida .= "<td align=\"center\">";
                    for($l=0;$l<sizeof($condic);$l++)
                    {
                        if($evensoat[$i]['condicion_accidentado']==$condic[$l]['condicion_accidentado'])
                        {
                            $this->salida .= "      ".strtoupper($condic[$l]['descripcion'])."";
                        }
                    }
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    if($evensoat[$i]['asegurado']==1)
                    {
                        $this->salida .= "SI";
                    }
                    else if($evensoat[$i]['asegurado']==2)
                    {
                        $this->salida .= "NO";
                    }
                    else if($evensoat[$i]['asegurado']==3)
                    {
                        $this->salida .= "FANT.";
                    }
                    else if($evensoat[$i]['asegurado']==4)
                    {
                        $this->salida .= "P. FALSA";
                    }
                    else if($evensoat[$i]['asegurado']==5)
                    {
                        $this->salida .= "P. VENCIDA";
                    }
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','MostrarDatosAdicional',array('switch'=>2,
                    'acciver'=>$evensoat[$i]['accidente_id'],'razover'=>$evensoat[$i]['razon_social'],
                    'poliver'=>$evensoat[$i]['poliza'],'saldver'=>$evensoat[$i]['saldo'],'condver'=>$evensoat[$i]['condicion_accidentado'],
                    'asegver'=>$evensoat[$i]['asegurado'],'epsver'=>$evensoat[$i]['codigo_eps'],'ambuver'=>$evensoat[$i]['ambulancia_id'],
                    'eventoeleg'=>$evensoat[$i]['evento'])) ."\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\" title=\"INFORMACIÓN DEL ACCIDENTE\"></a>";
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','MostrarDatosConsumo',array(
                    'evenconsumo'=>$evensoat[$i]['evento'],'saldoconsu'=>$evensoat[$i]['saldo'],
                    'policonsu'=>$evensoat[$i]['poliza'])) ."\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"  title=\"NUEVO CONSUMO\"></a>";
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                    $i++;
                }
                $total=0;
                $datosconsumos=$this->BuscarConsumosSoat($evensoat[0]['evento']);
                foreach($datosconsumos AS $i => $v)
                {
                    $total+=$v[valor_consumo];
                }
                $this->salida .= "<tr class=\"modulo_table_list_title\">";
                $this->salida .= "<td colspan=\"6\" align=\"left\">";
                $this->salida .= "Total";
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"right\">$&nbsp;".FormatoValor($total)."";
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
            }
            else
            {
                $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
                $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN DEL PACIENTE EN LOS EVENTOS'";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
            }
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
//
        $this->salida .= "  </td>";
        $this->salida .= "  <td width=\"40%\">";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">CONSUMOS INTERNOS DEL PACIENTE - AMBULATORIO</legend>";
        $vect=$this->ConsultarConsumosInternos($evensoat[0][evento]);

        if(sizeof($vect)>0)
        {
            $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"center\" width=\"20%\" >Cuenta</td>";
            $this->salida .= "      <td align=\"center\" width=\"10%\" >Fecha</td>";
            $this->salida .= "      <td align=\"center\" width=\"28%\" >No. FACTURA</td>";
            $this->salida .= "      <td align=\"right\" width=\"42%\" >VALOR</td>";
            $this->salida .= "      </tr>";
            $totalconsumosinternos=0;
            foreach($vect AS $i => $v)
            {
                
                $totalconsumosinternos+=$v[total_factura];
                $f=$this->FormatoFecha($v[fecha_registro]);
                $this->salida .= "      <tr>";
                $this->salida .= "      <td align=\"center\" width=\"20%\" class=\"label\">".$v[numerodecuenta]."</td>";
                $this->salida .= "      <td align=\"center\" width=\"10%\" class=\"label\">".$f."</td>";
                $this->salida .= "      <td align=\"center\" width=\"28%\" class=\"label\">".$v[prefijo].$v[factura_fiscal]."</td>";
                $this->salida .= "      <td align=\"right\" width=\"42%\" class=\"label\">".FormatoValor($v[total_factura])."</td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"left\" width=\"38%\" colspan=\"3\">Total:</td>";
            $this->salida .= "      <td align=\"right\" width=\"62%\">$&nbsp;".FormatoValor($totalconsumosinternos)."</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
        }
        else
        {
            $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
            $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN DE CONSUMOS INTERNOS DEL PACIENTE'";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
        }
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td><tr>";
        $this->salida .= "  </table>";
//
        $this->salida .= "  </td></tr>";
//
        $this->salida .= "  <tr><td width=\"40%\">";
        $this->salida .= "  &nbsp;";
        $this->salida .= "  <td><tr>";
        //CONSUMOS REALIZADOS EN HOSPITALIZACION
        $vect1=$this->ConsultarConsumosHospitalizacion($evensoat[0][evento]);

        if(sizeof($vect1)>0)
        {
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"40%\" colspan=\"2\">";
            $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">CONSUMOS INTERNOS DEL PACIENTE - HOSPITALIZACIÓN</legend>";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
  /*          $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"left\" width=\"8%\" >No. Cuenta</td>";
            $this->salida .= "      <td align=\"center\" width=\"15%\" >Departamento</td>";
            $this->salida .= "      <td align=\"center\" width=\"10%\" >Tarifario</td>";
            $this->salida .= "      <td align=\"center\" width=\"34%\" >Cargo</td>";
            $this->salida .= "      <td align=\"right\" width=\"10%\" >Precio</td>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" >Cant.</td>";
            $this->salida .= "      <td align=\"right\" width=\"15%\" >Valor Cargo</td>";
            $this->salida .= "      </tr>";
            $totalconsumoshosp=0;
            foreach($vect1 AS $i => $v)
            {
                
                $totalconsumoshosp+=$v[valor_cargo];
                $this->salida .= "      <tr>";
                $this->salida .= "      <td align=\"center\">".$v[numerodecuenta]."</td>";
                $this->salida .= "      <td align=\"center\" width=\"20%\" >".$v[desdepartamento]."</td>";
                $this->salida .= "      <td align=\"center\" width=\"20%\" >".$v[destarifario]."</td>";
                $this->salida .= "      <td align=\"center\" width=\"12%\" >".$v[cargo]."  ".$v[descargo]."</td>";
                $this->salida .= "      <td align=\"right\" width=\"10%\" >".FormatoValor($v[precio])."</td>";
                $this->salida .= "      <td align=\"center\" width=\"8%\" >".$v[cantidad]."</td>";
                $this->salida .= "      <td align=\"right\" width=\"18%\" >".FormatoValor($v[valor_cargo])."</td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"right\" width=\"92%\" colspan=\"6\">Total:</td>";
            $this->salida .= "      <td align=\"right\" width=\"8%\">$".FormatoValor($totalconsumoshosp)."</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
            $this->salida .= "  </td></tr>";*/
//
//CONSUMOS O CUENTAS FACTURADAS
            $total_vcargos = $nro = $j = $total_valor_facturados = 0;
            $txt='';
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"40%\" colspan=\"2\">";
            $this->salida .= "      <table border=\"0\" width=\"55%\" align=\"center\">";
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" colspan=\"6\">CUENTAS FACTURADAS</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"center\" width=\"3%\" >Prefijo</td>";
            $this->salida .= "      <td align=\"center\" width=\"5%\" >No. Factura</td>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" >No. Cuenta</td>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" >Fecha</td>";
            $this->salida .= "      <td align=\"center\" width=\"2%\" >No. Cargos</td>";
            $this->salida .= "      <td align=\"right\" width=\"15%\" >Valor Total Cargos</td>";
            $this->salida .= "      </tr>";
            for($i=0; $i<sizeof($vect1[detalle]);)
            {
                $j=$i;
                while($vect1[detalle][$i][numerodecuenta] == $vect1[detalle][$j][numerodecuenta])
                {
                    if($vect1[detalle][$j][prefijo] AND $vect1[detalle][$j][factura_fiscal])
                    {
                        $prefijo = $vect1[detalle][$j][prefijo];
                        $nro_factura = $vect1[detalle][$j][factura_fiscal];
                        $cuenta = $vect1[detalle][$j][numerodecuenta];
                        $fecha = $vect1[detalle][$j][fecha_registro];
                        $nro += $vect1[detalle][$j][cantidad];
                        $total_vcargos = $vect1[detalle][$j][total_factura];
                        $txt.= $j.'=>'.$vect1[detalle][$j][destarifario]."&nbsp;&nbsp;";
                        $txt.= $vect1[detalle][$j][cargo]."  ".$vect1[detalle][$j][descargo]."&nbsp;&nbsp;";
                        $txt.= $vect1[detalle][$j][fcargo]."&nbsp;&nbsp;";
                        $txt.= FormatoValor($vect1[detalle][$j][precio])."&nbsp;&nbsp;";
                        $txt.= $vect1[detalle][$j][cantidad]."&nbsp;&nbsp;";
                        $txt.= FormatoValor($vect1[detalle][$j][valor_cargo])."&nbsp;&nbsp;&nbsp;";
                    }
                 $j++;
                }
                if($prefijo AND $nro_factura AND $cuenta AND $fecha)
                {
                    $this->salida .= "      <tr>";
                    $this->salida .= "      <td align=\"center\" width=\"3%\" >".$prefijo."</td>";
                    $this->salida .= "      <td align=\"center\" width=\"5%\" >".$nro_factura."</td>";
                    $this->salida .= "      <td align=\"center\" class=\"label\">".$cuenta."</td>";
                    $this->salida .= "      <td align=\"center\" class=\"label\">".$fecha."</td>";
                    $this->salida .= "      <td align=\"center\" width=\"2%\" class=\"label\"><a title=\"$txt\">".$nro."</a></td>";
                    $this->salida .= "      <td align=\"right\" width=\"18%\" class=\"label\">$&nbsp;".FormatoValor($total_vcargos)."</td>";
                    $this->salida .= "      </tr>";
                    $total_valor_facturados += $total_vcargos;
                }
                UNSET($prefijo);
                UNSET($nro_factura);
                UNSET($cuenta);
                UNSET($fecha);
                UNSET($nro);
                UNSET($total_vcargos);
               $i = $j;
            }
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"right\" width=\"100%\" colspan=\"6\">$&nbsp;".FormatoValor($total_valor_facturados)."</td>";
            $this->salida .= "      </tr>";
//
            $this->salida .= "      </table>";
            $this->salida .= "  </td></tr>";
            //FIN CONSUMOS FACTURADOS
            $total_vcargos = $nro = 0;
            $txt='';
            unset($fecha);
            foreach($vect1[detalle] AS $i => $v)
            {
                $j=$i;
                $j++;
                //$txt.=$v[numerodecuenta]."&nbsp;";
                //$txt.=$v[desdepartamento]."&nbsp;";
                if(!$v[prefijo] AND !$v[factura_fiscal])
                { 
                    $cuenta = $v[numerodecuenta];
                    $fecha = $v[fecha_registro];
                    $nro += $v[cantidad];
                    $total_vcargos+=$v[valor_cargo];
                    $txt.=$j.'=>'.$v[destarifario]."&nbsp;&nbsp;";
                    $txt.=$v[cargo]."  ".$v[descargo]."&nbsp;&nbsp;";
                    $txt.=$v[fcargo]."&nbsp;&nbsp;";
                    $txt.=FormatoValor($v[precio])."&nbsp;&nbsp;";
                    $txt.=$v[cantidad]."&nbsp;&nbsp;";
                    $txt.=FormatoValor($v[valor_cargo])."&nbsp;&nbsp;&nbsp;";
                }
            }
            //CUENTAS SIN FACTURAR
            if(!empty($txt))
            {
            $this->salida .= "  <tr>";
            $this->salida .= "  <td width=\"40%\" colspan=\"2\">";
            $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" colspan=\"4\">CUENTAS SIN FACTURAR</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" >No. Cuenta</td>";
            $this->salida .= "      <td align=\"center\" width=\"8%\" >Fecha</td>";
            
            $this->salida .= "      <td align=\"center\" width=\"2%\" >No. Cargos</td>";
            $this->salida .= "      <td align=\"right\" width=\"15%\" >Valor Total Cargos</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"center\" class=\"label\">".$cuenta."</td>";
            $this->salida .= "      <td align=\"center\" class=\"label\">".$fecha."</td>";
            $this->salida .= "      <td align=\"center\" width=\"2%\" class=\"label\"><a title=\"$txt\">".$nro."</a></td>";
            $this->salida .= "      <td align=\"right\" width=\"18%\" class=\"label\">$&nbsp;".FormatoValor($total_vcargos)."</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td align=\"right\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
            $this->salida .= "      </tr>";
//
            $this->salida .= "      </table>";
            $this->salida .= "  </td></tr>";
            }
            $this->salida .= "  </table>";
            //FIN CUENTAS SIN FACTURAR
            $this->salida .= "  </fieldset>";
            $this->salida .= "  </td></tr>";
        }
        else
        {
            $this->salida .= "  <tr><td width=\"100%\">";
            $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
            $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN DE CONSUMOS INTERNOS DE HOSPITALIZACIÓN'";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table>";
        }
        //FIN - CONSUMOS REALIZADOS EN HOSPITALIZACION


    if($evensoat[0][saldo_inicial] > 0)
    {
        $this->salida .= "  <tr><td width=\"100%\">";
        $this->salida .= "  &nbsp;";
        $this->salida .= "  <td><tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"24%\">SALDO INICIAL</td>";
        $this->salida .= "      <td align=\"right\" class=\"label\">$&nbsp;";
        $this->salida .=         number_format(($evensoat[0][saldo_inicial]), 2, ',', '.');
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"24%\">SUB TOTAL</td>";
        $this->salida .= "      <td align=\"right\" class=\"label\">$&nbsp;";
        $this->salida .=         number_format(($evensoat[0][saldo_inicial]-$saldoevent), 2, ',', '.');
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"16%\">SALDO</td>";
        $this->salida .= "      <td align=\"right\" class=\"label\">$&nbsp;";
        $this->salida .=         number_format(($saldoevent), 2, ',', '.');
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td></tr>";
    }
//
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "      <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"33%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"SOAT - OPCIONES\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Soat','user','ConsumoSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"34%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Soat','user','IraEventoSoat');
        $this->salida .= "      <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"33%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"consu\" value=\"IR A EVENTOS\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //FORMA CONSUMOS DEL EVENTO 15-08-06
    //Muestra los resultados de los eventos asociados con el paciente, se escoge uno y se ingresa el consumo
/*  function DatosConsumo()//Llama a Ingresar Datos del consumo una vez elegido un evento
    {
        if(empty($_REQUEST['Documento']) AND empty($_SESSION['soat']['consumo']['Documento']))
        {
            $this->ConsumoSoat();
            return true;
        }
        if(empty($_SESSION['soat']['consumo']['Documento']))
        {
            $_SESSION['soat']['consumo']['TipoDocum']=$_REQUEST['TipoDocum'];
            $_SESSION['soat']['consumo']['Documento']=$_REQUEST['Documento'];
        }
        $_SESSION['soat']['consumo']['nombresoat']=$this->BuscarNombrePaci($_SESSION['soat']['consumo']['TipoDocum'],$_SESSION['soat']['consumo']['Documento']);
        if(empty($_SESSION['soat']['consumo']['nombresoat']))
        {
            $this->frmError["MensajeError"]="EL TIPO DOCUMENTO '".$var['0']."' CON No. '".$_POST['Documento']."' NO SE ENCONTRo";
            $this->uno=1;
            $this->ConsumoSoat();
            return true;
        }
        else
        {
        //1 guarda
        //2 modifica
            UNSET($_SESSION['soat']['evenconsumo']);
            UNSET($_SESSION['soat']['saldoconsu']);
            UNSET($_SESSION['soat']['policonsu']);
            UNSET($_SESSION['soat']['consumoele']);
            UNSET($_SESSION['soat']['valorviejo']);
            $evensoat=$this->BuscarEventoSoat($_SESSION['soat']['consumo']['TipoDocum'],$_SESSION['soat']['consumo']['Documento']);
            $this->salida  = ThemeAbrirTabla('_EVENTOS SOAT RELACIONADOS CON EL PACIENTE - CONSUMOS');
            $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "  <tr><td>";
            $this->salida .= "      <table border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"70%\">";
            $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"70%\">";
            $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
            $this->salida .= "  </td></tr>";
            $this->salida .= "  <tr><td>";
            $this->salida .= "  <fieldset><legend class=\"normal_11N\">EVENTOS SOAT DEL PACIENTE</legend>";
            if(!empty($evensoat))
            {
                $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"40%\">";
                $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['consumo']['nombresoat']['paciente_id']."";
                $this->salida .= "      </td>";
                $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td align=\"center\" width=\"40%\">";
                $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_nombre']."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </table><br>";
                $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "      <td width=\"3%\" >No.</td>";
                $this->salida .= "      <td width=\"14%\">POLIZA</td>";
                $this->salida .= "      <td width=\"30%\">ASEGURADORA</td>";
                $this->salida .= "      <td width=\"16%\">SALDO</td>";
                $this->salida .= "      <td width=\"10%\">CONDICIoN</td>";
                $this->salida .= "      <td width=\"10%\">ASEGURADO</td>";
                $this->salida .= "      <td width=\"5%\" >ACCID.</td>";
                $this->salida .= "      <td width=\"12%\">CONSUMOS</td>";
                $this->salida .= "      </tr>";
                $i=0;
                $j=0;
                $k=1;
                $condic=$this->BuscarCondicion();
                $ciclo=sizeof($evensoat);
                while($i<$ciclo)
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
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "".$k."";
                    $this->salida .= "</td>";
                    if($evensoat[$i]['poliza']==$evensoat[$i+1]['poliza'])
                    {
                        $k++;
                    }
                    else
                    {
                        $k=1;
                    }
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $evensoat[$i]['poliza'];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $evensoat[$i]['nombre_tercero'];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"right\">";
                    $saldoevent=$evensoat[$i]['saldo'];
                    $this->salida .= number_format(($saldoevent), 2, ',', '.');
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    for($l=0;$l<sizeof($condic);$l++)
                    {
                        if($evensoat[$i]['condicion_accidentado']==$condic[$l]['condicion_accidentado'])
                        {
                            $this->salida .= "      ".strtoupper($condic[$l]['descripcion'])."";
                        }
                    }
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    if($evensoat[$i]['asegurado']==1)
                    {
                        $this->salida .= "SI";
                    }
                    else if($evensoat[$i]['asegurado']==2)
                    {
                        $this->salida .= "NO";
                    }
                    else if($evensoat[$i]['asegurado']==3)
                    {
                        $this->salida .= "FANT.";
                    }
                    else if($evensoat[$i]['asegurado']==4)
                    {
                        $this->salida .= "P. FALSA";
                    }
                    else if($evensoat[$i]['asegurado']==5)
                    {
                        $this->salida .= "P. VENCIDA";
                    }
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','MostrarDatosAdicional',array('switch'=>2,
                    'acciver'=>$evensoat[$i]['accidente_id'],'razover'=>$evensoat[$i]['razon_social'],
                    'poliver'=>$evensoat[$i]['poliza'],'saldver'=>$evensoat[$i]['saldo'],'condver'=>$evensoat[$i]['condicion_accidentado'],
                    'asegver'=>$evensoat[$i]['asegurado'],'epsver'=>$evensoat[$i]['codigo_eps'],'ambuver'=>$evensoat[$i]['ambulancia_id'],
                    'eventoeleg'=>$evensoat[$i]['evento'])) ."\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','MostrarDatosConsumo',array(
                    'evenconsumo'=>$evensoat[$i]['evento'],'saldoconsu'=>$evensoat[$i]['saldo'],
                    'policonsu'=>$evensoat[$i]['poliza'])) ."\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                    $i++;
                }
            }
            else
            {
                $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
                $this->salida .= "      'NO SE ENCONTRo INFORMACIoN DEL PACIENTE EN LOS EVENTOS'";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
            }
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "      <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"33%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"SOAT - OPCIONES\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Soat','user','ConsumoSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"34%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Soat','user','IraEventoSoat');
        $this->salida .= "      <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"33%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"consu\" value=\"IR A EVENTOS\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }*/
    //FIN FORMA SIN MODIFICAR de los consumos del evento 12-08-06

    //Llama la funcion de ingresar en modo nuevo o modificacion
    function MostrarDatosConsumo()//Muestra los consumos realizados por un usuario, segon el evento
    {
        $this->uno=0;
        if(empty($_SESSION['soat']['evenconsumo']))
        {
            $_SESSION['soat']['evenconsumo']=$_REQUEST['evenconsumo'];
            $_SESSION['soat']['saldoconsu']=$_REQUEST['saldoconsu'];
            $_SESSION['soat']['policonsu']=$_REQUEST['policonsu'];
        }
        UNSET($_SESSION['soat']['consumoele']);
        UNSET($_SESSION['soat']['valorviejo']);
        $this->salida  = ThemeAbrirTabla('SOAT - CONSUMOS DEL PACIENTE');
        $accion=ModuloGetURL('app','Soat','user','IngresaDatosConsumo',array('guarmodi'=>1));
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN SOBRE EL CONSUMO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['consumo']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\" width=\"9%\">POLIZA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['soat']['policonsu']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\" width=\"11%\">SALDO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $saldoconsu=$_SESSION['soat']['saldoconsu'];
        $this->salida .= "      ".number_format(($saldoconsu), 2, ',', '.')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $consumos=$this->BuscarConsumosSoat($_SESSION['soat']['evenconsumo']);
        if(!empty($consumos))
        {
            $this->salida .= "      <tr class=modulo_table_list_title>";
            $this->salida .= "      <td width=\"28%\">ENTIDAD QUE REPORTA</td>";
            $this->salida .= "      <td width=\"28%\">FUNCIONARIO QUE REPORTA</td>";
            $this->salida .= "      <td width=\"18%\">FECHA REPORTE</td>";
            $this->salida .= "      <td width=\"16%\">VALOR CONSUMO</td>";
            $this->salida .= "      <td width=\"10%\">CONSUMOS</td>";
            $this->salida .= "      </tr>";
            $i=0;
            $j=0;
            $ciclo=sizeof($consumos);
            while($i<$ciclo)
            {
                if($j==0)
                {
                    $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                    $j=1;
                }
                else
                {
                    $this->salida .= "<tr class=\"modulo_list_claro\">";
                    $j=0;
                }
                $this->salida .= "<td align=\"left\">";
                $this->salida .= $consumos[$i]['entidad_reporta'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"left\">";
                $this->salida .= $consumos[$i]['funcionario_reporta'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"center\">";
                $this->salida .= $consumos[$i]['fecha_reporte'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"right\">";
                $this->salida .= $consumos[$i]['valor_consumo'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"center\">";
                $this->uno=0;
                $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','IngresaDatosConsumo',array('guarmodi'=>2,
                'consumoele'=>$consumos[$i]['consumo'],'valorviejo'=>$consumos[$i]['valor_consumo'])) ."\">
                <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $i++;
            }
        }
        else
        {
            $this->salida .= "      <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td align=\"center\" class=\"label\" width=\"100%\">";
            $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN DEL PACIENTE EN LOS COMSUMOS'";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td colspan=\"5\" align=\"center\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO CONSUMO\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','DatosConsumo');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A CONSUMOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Captura los datos del nuevo consumo
    function IngresaDatosConsumo()//LLama a validar los datos y posteriormente los ingresa
    {
        if($_REQUEST['guarmodi']==1)
        {
            $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL CONSUMO');
            $accion=ModuloGetURL('app','Soat','user','ValidarDatosConsumo',array('guarmodi'=>1));
        }
        else
        {
            $this->salida  = ThemeAbrirTabla('SOAT - DATOS DEL CONSUMO - MODIFICAR');
            $accion=ModuloGetURL('app','Soat','user','ValidarDatosConsumo',array('guarmodi'=>2));
            if($this->uno==0)
            {
                $_SESSION['soat']['consumoele']=$_REQUEST['consumoele'];
                $_SESSION['soat']['valorviejo']=$_REQUEST['valorviejo'];
                $consumod=$this->BuscarConsumosSoatMod($_SESSION['soat']['consumoele']);
                $fechamod=explode(' ',$consumod['fecha_reporte']);
                $fechapar=$fechamod[0];
                $fechadiv=explode('-',$fechapar);
                $_POST['fechadelrep']=$fechadiv[2].'/'.$fechadiv[1].'/'.$fechadiv[0];
                $fechahor=$fechamod[1];
                $fechamin=explode(':',$fechahor);
                $_POST['horariorep']=$fechamin[0];
                $_POST['minuterrep']=$fechamin[1];
                $_POST['entidadrep']=$consumod['entidad_reporta'];
                $_POST['funcionrep']=$consumod['funcionario_reporta'];
                $_POST['valorconsu']=$consumod['valor_consumo'];
            }
        }
        $this->salida .= "  <form name=\"formacon\" action=\"$accion\" method=\"post\">";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIoN SOBRE EL CONSUMO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['consumo']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"label\" width=\"9%\">NOMBRE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\" width=\"9%\">POLIZA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$_SESSION['soat']['policonsu']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\" width=\"11%\">SALDO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $saldoconsu=$_SESSION['soat']['saldoconsu'];
        $this->salida .= "      ".number_format(($saldoconsu), 2, ',', '.')."";//".$_POST['saldoconsu']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"60%\"><label class=\"".$this->SetStyle("fechadelrep")."\">FECHA DEL REPORTE:</label>";
        if(empty($_POST['fechadelrep']))
        {
            $_POST['fechadelrep']=date ("d/m/Y");
        }
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadelrep\" value=\"".$_POST['fechadelrep']."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('formacon','fechadelrep','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td><label class=\"".$this->SetStyle("horariorep")."\">HORA: </label>";
        $this->salida .= "      <select name=\"horariorep\" class=\"select\">";
        $this->salida .= "      <option value=\"00\">00</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['horariorep']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['horariorep']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"minuterrep\" class=\"select\">";
        $this->salida .= "      <option value=\"00\">00</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['minuterrep']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['minuterrep']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"label\" width=\"38%\">ENTIDAD QUE REPORTA:</td>";
        $this->salida .= "      <td width=\"62%\"><input type=\"text\" class=\"input-text\" name=\"entidadrep\" value=\"".$_POST['entidadrep']."\" maxlength=\"50\" size=\"50\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"38%\">FUNCIONARIO QUE REPORTA:</td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"funcionrep\" value=\"".$_POST['funcionrep']."\" maxlength=\"40\" size=\"50\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td width=\"38%\"><label class=\"".$this->SetStyle("valorconsu")."\">VALOR TOTAL DEL CONSUMO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valorconsu\" value=\"".$_POST['valorconsu']."\" maxlength=\"20\" size=\"20\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if($_REQUEST['guarmodi']==2)
        {
            $this->salida .= "      <tr class=modulo_list_oscuro>";
            $this->salida .= "      <td width=\"38%\"><label class=\"label\">VALOR DEL CONSUMO REGISTRADO:</td>";
            $viejito=$_SESSION['soat']['valorviejo'];
            $this->salida .= "      <td width=\"62%\">";
            $this->salida .= "      <input type=\"text\" name=\"valorviejito\" value=\"".number_format(($viejito), 2, ',', '.')."\" class=\"input-text\" size=\"20\" readonly>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
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
        $accion=ModuloGetURL('app','Soat','user','MostrarDatosConsumo');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //Funcion que muestra el listado de los pacientes ingresados por SOAT
    function ListaPacientes()//Volida si continua con el listado
    {
        $this->salida  = ThemeAbrirTabla('SOAT - PACIENTES DEL SOAT');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIoN DE CUENTAS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_table_list_title>";
        $this->salida .= "      <td align=\"center\" width=\"3%\" >No.</td>";
        $this->salida .= "      <td align=\"center\" width=\"7%\" >No. CUENTA</td>";
        $this->salida .= "      <td align=\"center\" width=\"10%\">IDENT.</td>";
        $this->salida .= "      <td align=\"center\" width=\"17%\">NOMBRE</td>";
        $this->salida .= "      <td align=\"center\" width=\"3%\" >EST.</td>";
        $this->salida .= "      <td align=\"center\" width=\"16%\">ASEGURADORA</td>";
        $this->salida .= "      <td align=\"center\" width=\"11%\">POLIZA</td>";
        $this->salida .= "      <td align=\"center\" width=\"8%\" >INTE FAC.</td>";
        $this->salida .= "      <td align=\"center\" width=\"8%\" >INTE ACT.</td>";
        $this->salida .= "      <td align=\"center\" width=\"8%\" >CON. EXTE</td>";
        $this->salida .= "      <td align=\"center\" width=\"9%\" >SALDO</td>";
        $this->salida .= "      </tr>";
        $cuentasoat=$this->BuscarCuentasSoat($_SESSION['soa1']['empresa'],$_SESSION['soa1']['centroutil']);
        $i=0;
        $j=0;
        if(empty($cuentasoat))
        {
            $this->salida .= "<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td colspan=\"11\" align=\"center\">";
            $this->salida .= "'NO SE ENCONTRÓ INFORMACIÓN'";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        else
        {
            $ciclo=sizeof($cuentasoat);
            while($i<$ciclo)
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
                $this->salida .= "<td align=\"center\">";
                $this->salida .= ($i+1);
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"right\">";
                $this->salida .= $cuentasoat[$i]['numerodecuenta'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"center\">";
                $this->salida .= $cuentasoat[$i]['tipo_id_paciente'].' - '.$cuentasoat[$i]['paciente_id'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"left\">";
                $this->salida .= $cuentasoat[$i]['primer_nombre'].' '.$cuentasoat[$i]['primer_apellido'];
                $this->salida .= "</td>";//descripcion
                $this->salida .= "<td align=\"center\">";
                $this->salida .= $cuentasoat[$i]['descripcion'];
/*              if($cuentasoat[$i]['estado']==1)
                {
                    $this->salida .= "ACT.";
                }
                else if($cuentasoat[$i]['estado']==2)
                {
                    $this->salida .= "INACT.";
                }
                else if($cuentasoat[$i]['estado']==3)
                {
                    $this->salida .= "CUAD.";
                }*/
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"left\">";
                $this->salida .= $cuentasoat[$i]['nombre_tercero'];
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"center\">";
                $this->salida .= $cuentasoat[$i]['poliza'];//paciente_id
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"right\" class=\"label\">";
                $saldover=$cuentasoat[$i]['consumoint'];
                $this->salida .= number_format(($saldover), 2, ',', '.');
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"right\" class=\"label\">";
                $saldover=$cuentasoat[$i]['consumointactual'];
                $this->salida .= number_format(($saldover), 2, ',', '.');
                $this->salida .= "</td>";
                $this->salida .= "<td align=\"right\" class=\"label\">";
                $saldover=$cuentasoat[$i]['consumoext'];
                $this->salida .= number_format(($saldover), 2, ',', '.');
                $this->salida .= "</td>";
                $this->frmError["saldorojo"]=0;
                $saldover=($cuentasoat[$i]['saldo']-($cuentasoat[$i]['consumoint']+$cuentasoat[$i]['consumointactual']));
                if($saldover<=0)
                {
                    $this->frmError["saldorojo"]=1;
                }
                $this->salida .= "<td align=\"right\"><label class=\"".$this->SetStyle("saldorojo")."\">";
                $this->salida .= number_format(($saldover), 2, ',', '.');
                $this->salida .= "</label></td>";
                $this->salida .= "</tr>";
                $i++;
            }
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"SOAT - OPCIONES\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function SoatAdmision()//Llama a Validar Datos del Accidente Rapidos
    {
        if(empty($_SESSION['SOAT']['PACIENTE']['tipo_id_paciente']) OR empty($_SESSION['SOAT']['PACIENTE']['paciente_id']))
        {
            $this->error = "Error en SOAT";
            $this->mensajeDeError = "DATOS DEL PACIENTE INCOMPLETOS";
            return false;
        }
        else
        {
            $evensoat=$this->BuscarEventoSoat2($_SESSION['SOAT']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOAT']['PACIENTE']['paciente_id']);
            $nombsoat=$this->BuscarNombrePaci($_SESSION['SOAT']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOAT']['PACIENTE']['paciente_id']);
            if($_SESSION['SOAT']['CUENTA']==TRUE AND empty($evensoat))
            {
                $_SESSION['SOAT']['NOEVENTO']=TRUE;
                $contenedor=$_SESSION['SOAT']['RETORNO']['contenedor'];//app
                $modulo=$_SESSION['SOAT']['RETORNO']['modulo'];//Triage
                $tipo=$_SESSION['SOAT']['RETORNO']['tipo'];//user
                $metodo=$_SESSION['SOAT']['RETORNO']['metodo'];//LlamaFormaIngresoEventos
                $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                return true;
            }
	//tipo-accidente transito
	$js = "<script>\n";
	$js .= "	function SetValor(valor)\n";
	$js .= "	{\n";
	$js .= "	 e = document.getElementById('condicionaccidentado');\n";
	$js .= "	 if(valor == '01')\n";
	$js .= "	 {\n";
	$js .= "	  e.style.display = \"block\";\n";
	$js .= "	 }\n";
	$js .= "	 else\n";
	$js .= "	 {\n";
	$js .= "	  e.style.display = \"none\";\n";
	$js .= "	 }\n";
	$js .= "	}\n";
	//fin tipo-accidente transito
	$js .= "</script>\n";
	    $this->salida  = "$js";
            $this->salida .= ThemeAbrirTabla('EVENTOS SOAT RELACIONADOS CON EL PACIENTE');
            $accion=ModuloGetURL('app','Soat','user','ValidarSoatAdmiVie');
            $this->salida .= "  <form name=\"formadmin\" action=\"$accion\" method=\"post\">";
            $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "  <tr><td>";
            $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">EVENTOS SOAT DEL PACIENTE</legend>";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"40%\">";
            $this->salida .= "      ".$nombsoat['tipo_id_paciente']."".' - '."".$nombsoat['paciente_id']."";
            $this->salida .= "      </td>";
            $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
            $this->salida .= "      </td>";
            $this->salida .= "      <td align=\"center\" width=\"40%\">";
            $this->salida .= "      ".$nombsoat['primer_apellido']."".' '."".$nombsoat['segundo_apellido']."".' '."".$nombsoat['primer_nombre']."".' '."".$nombsoat['segundo_nombre']."";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      </table><br>";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"3%\">No.</td>";
            $this->salida .= "      <td width=\"9%\" >FECHA</td>";
            $this->salida .= "      <td width=\"9%\" >HORA</td>";
            $this->salida .= "      <td width=\"20%\">POLIZA</td>";
            $this->salida .= "      <td width=\"30%\">ASEGURADORA</td>";
            $this->salida .= "      <td width=\"11%\">SALDO</td>";
            //$this->salida .= "      <td width=\"9%\" >EPICRISIS</td>";
            $this->salida .= "      <td width=\"9%\" >ELEGIR</td>";
            $this->salida .= "      </tr>";
            $i=0;
            $j=0;
            $k=1;
            if(!empty($evensoat))//BuscarPolizaAsegur
            {
                $ciclo=sizeof($evensoat);
                while($i<$ciclo)
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
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= "".$k."";
                    $this->salida .= "</td>";
                    if($evensoat[$i]['poliza']==$evensoat[$i+1]['poliza'])
                    {
                        $k++;
                    }
                    else
                    {
                        $k=1;
                    }
                    $this->salida .= "<td align=\"center\">";
                    $vector=explode(' ',$evensoat[$i]['fecha_accidente']);
                    $accfecha=explode('-',$vector[0]);
                    $this->salida .= $accfecha[2].'/'.$accfecha[1].'/'.$accfecha[0];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $vector[1];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $evensoat[$i]['poliza'];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $this->salida .= $evensoat[$i]['nombre_tercero'];
                    $this->salida .= "</td>";
                    $this->salida .= "<td align=\"center\">";
                    $saldover=$evensoat[$i]['saldo'];
                    $this->salida .= number_format(($saldover), 2, ',', '.');
                    $this->salida .= "</td>";
                    /*$this->salida .= "<td align=\"center\">";
                    if($evensoat[$i]['ingreso']<>NULL)
                    {
                        $this->salida .= "<a href=\"". ModuloGetURL('app','Soat','user','EpicrisisSoat',array('ingreso'=>$evensoat[$i]['ingreso'])) ."\">EPICRISIS</a>";
                    }
                    else
                    {
                        $this->salida .= "EPICRISIS";
                    }
                    $this->salida .= "</td>";
          */
                    $this->salida .= "<td align=\"center\">";
                    if(!empty($_POST['eligevento']))
                    {
                        $this->salida .= "<input type='radio' name='eligevento' value=".$evensoat[$i]['evento']."".','."".$evensoat[$i]['poliza']."".','."".$evensoat[$i]['saldo']." checked>";
                    }
                    else
                    {
                        $this->salida .= "<input type='radio' name='eligevento' value=".$evensoat[$i]['evento']."".','."".$evensoat[$i]['poliza']."".','."".$evensoat[$i]['saldo'].">";
                    }
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                    $i++;
                }
                $this->salida .= "      </table>";
                $this->salida .= "  </fieldset>";
                $this->salida .= "  </td></tr>";
                $this->salida .= "  </table><br>";
                $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
                $this->salida .= "  <tr>";
                $this->salida .= "  <td colspan=\"8\" align=\"center\">";
                $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"SELECCIONAR EVENTO\">";
                $this->salida .= "  </td>";
                $this->salida .= "  </form>";
                $this->salida .= "  </tr>";
                $this->salida .= "  </table><br>";
            }
            else
            {
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $this->salida .= "      <td colspan=\"8\" align=\"center\">";
                $this->salida .= "      'NO SE ENCONTRÓ INFORMACIÓN DEL PACIENTE EN LOS EVENTOS'";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </form>";
                $this->salida .= "      </table>";
                $this->salida .= "  </fieldset>";
                $this->salida .= "  </td></tr>";
                $this->salida .= "  </table><br>";
            }
            if($this->admin1 == 1)
            {
                $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  </table><br>";
            }
            /*AQUI se divide la pantalla*/
            if($_SESSION['SOAT']['CUENTA']==FALSE)
            {
                $accion=ModuloGetURL('app','Soat','user','ValidarSoatAdmiNue');
                $ru='classes/BuscadorDestino/selectorCiudad.js';
                $rus='classes/BuscadorDestino/selector.php';
                $this->salida .= "  <script languaje='javascript' src=\"$ru\"></script>";
                $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
                $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
                $this->salida .= "  <tr><td>";
                $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">EVENTO NUEVO</legend>";
                $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=modulo_list_oscuro>";
                $this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("fechadmis")."\">FECHA: </label>";
                if(empty($_POST['fechadmis']))
                {
                    $_POST['fechadmis']=date("d/m/Y");
                }
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadmis\" value=\"".$_POST['fechadmis']."\" maxlength=\"10\" size=\"15\">";
                $this->salida .= "      ".ReturnOpenCalendario('forma','fechadmis','/')."";
                $this->salida .= "      </td>";
                $this->salida .= "      <td><label class=\"".$this->SetStyle("horadmin")."\">HORA: </label>";
                $this->salida .= "      <select name=\"horadmin\" class=\"select\">";
                $this->salida .= "      <option value=\"00\">00</option>";
                for($i=1;$i<24;$i++)
                {
                    if($i<10)
                    {
                        if($_POST['horadmin']=="0$i")
                        {
                            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                        }
                        else
                        {
                            $this->salida .="<option value=\"0$i\">0$i</option>";
                        }
                    }
                    else
                    {
                        if($_POST['horadmin']=="$i")
                        {
                            $this->salida .="<option value=\"$i\" selected>$i</option>";
                        }
                        else
                        {
                            $this->salida .="<option value=\"$i\">$i</option>";
                        }
                    }
                }
                $this->salida .= "      </select>";
                $this->salida .= " : ";
                $this->salida .= "      <select name=\"minudmin\" class=\"select\">";
                $this->salida .= "      <option value=\"00\">00</option>";
                for($i=1;$i<60;$i++)
                {
                    if($i<10)
                    {
                        if($_POST['minudmin']=="0$i")
                        {
                            $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                        }
                        else
                        {
                            $this->salida .="<option value=\"0$i\">0$i</option>";
                        }
                    }
                    else
                    {
                        if($_POST['minudmin']=="$i")
                        {
                            $this->salida .="<option value=\"$i\" selected>$i</option>";
                        }
                        else
                        {
                            $this->salida .="<option value=\"$i\">$i</option>";
                        }
                    }
                }
                $this->salida .= "      </select>";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
		//CAMPOS FURIPS
		$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
		$this->salida .= "		<td> <label class=\"".$this->SetStyle("traslado")."\">TRASLADO EN AMBULANCIA PROPIA:</label></td>\n";
		$this->salida .= "		<td>\n";
		$this->salida .= "			SI&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"1\">";
		$this->salida .= "			NO&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"0\" checked>";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\" height=\"20\">\n";
		$this->salida .= "		<td><label class=\"".$this->SetStyle("traslado")."\">TIPO DE AMBULANCIA:</label></td>\n";
		$this->salida .= "		<td>\n";
		$this->salida .= "			BASICA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"0\" checked>";
		$this->salida .= "			MEDICALIZADA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"1\">";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_claro\" height=\"20\">\n";
		$this->salida .= "		<td><label class=\"".$this->SetStyle("naturaleza")."\">NATURALEZA DEL EVENTO:</label></td>\n";
		$this->salida .= "		<td>\n";
		$this->salida .= "		<select name=\"tiponaturaleza\" class=\"select\" onChange=\"SetValor(this.value)\">\n";
		$this->salida .=" 		<option value=\"\" selected>-------NINGUNO-------</option>\n";
		$eventos = $this->ObtenerTiposEventos();
		for($i=0; $i<sizeof($eventos); $i++)
		{
			if($_POST[tiponaturaleza] == $eventos[$i][soat_naturaleza_evento_id])
			{
				$this->salida .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\" selected>".$eventos[$i]['descripcion']."</option>\n";
			}
			else
			{
				$this->salida .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\">".$eventos[$i]['descripcion']."</option>\n";
			}
		}			
		$this->salida .= "              </select>\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= " <tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "  <td width=\"100%\" colspan=\"2\">\n";
		//TIPO ACCIDENTE DE TRANSITO
		$this->salida .= "    <div name='condicionaccidentado' id='condicionaccidentado' style=\"display:none\">";
		$this->salida .= "	<table width=\"100%\" align=\"center\">\n";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td  width=\"40%\"><label class=\"".$this->SetStyle("condicionAccidentado")."\">COND. DEL ACCIDENTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td  width=\"60%\">";
		$condic = $this->BuscarCondicion();
		for($i=0;$i<sizeof($condic);$i++)
		{
			$this->salida.= "      ".strtoupper($condic[$i]['descripcion'])."";
			if($_POST['condicion']==$condic[$i]['condicion_accidentado'])
			{
				$this->salida .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\" checked>";
			}
			else
			{
				$this->salida .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\">";
			}
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "     </table>\n";
		$this->salida .= "    </div>";
		//FIN TIPO ACCIDENTE DE TANSITO
		$this->salida .= "  </td>\n";
		$this->salida .= " </tr>\n";
		//FIN CAMPOS FURIPS
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("zonadmin")."\">ZONA DEL ACCIDENTE: </label>";
                $this->salida .= "      </td>";
                $this->salida .= "      <td width=\"50%\" class=\"label\">";
                if($_POST['zonadmin']==NULL)
                {
                    $_POST['zonadmin']='U';
                }
                $zonas=$this->BuscarZonaResidencia();
                for($i=0;$i<sizeof($zonas);$i++)
                {
                    $this->salida .= "      ".strtoupper($zonas[$i]['descripcion'])."";
                    if($_POST['zonadmin']==$zonas[$i]['zona_residencia'])
                    {
                        $this->salida .= "      <input type='radio' name='zonadmin' value=\"".$zonas[$i]['zona_residencia']."\" checked>";
                    }
                    else
                    {
                        $this->salida .= "      <input type='radio' name='zonadmin' value=\"".$zonas[$i]['zona_residencia']."\">";
                    }
                }
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                if(!$_POST['pais'] && !$_POST['dpto'] && !$_POST['mpio'])
                {
                    $_POST['pais']=GetVarConfigAplication('DefaultPais');
                    $_POST['dpto']=GetVarConfigAplication('DefaultDpto');
                    $_POST['mpio']=GetVarConfigAplication('DefaultMpio');
                }
                $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
                $_POST['npais']=$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_POST['pais']));
                $this->salida .= "      <td>";
                $this->salida .= "      <input type=\"text\" name=\"npais\" value=\"".$_POST['npais']."\" class=\"input-text\" size=\"25\" readonly>";
                $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_POST['pais']."\" class=\"input-text\">";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_list_claro\"label\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
                $_POST['ndpto']=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto']));
                $this->salida .= "      <td>";
                $this->salida .= "      <input type=\"text\" name=\"ndpto\" value=\"".$_POST['ndpto']."\" class=\"input-text\" size=\"25\" readonly>";
                $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_POST['dpto']."\" class=\"input-text\">";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_list_oscuro\"label\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
                $_POST['nmpio']=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_POST['pais'],'Dpto'=>$_POST['dpto'],'Mpio'=>$_POST['mpio']));
                $this->salida .= "      <td>";
                $this->salida .= "      <input type=\"text\" name=\"nmpio\" value=\"".$_POST['nmpio']."\" class=\"input-text\" size=\"25\" readonly>";
                $this->salida .= "      <input type=\"hidden\" name=\"mpio\" value=\"".$_POST['mpio']."\" class=\"input-text\" >";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td colspan=\"2\" align=\"center\"width=\"50%\">";
                $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR UBICACIÓN\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </table><br>";
                $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=modulo_list_oscuro>";
                $this->salida .= "      <td class=\"".$this->SetStyle("asegurado")."\" width=\"26%\">ASEGURADO:";
                $this->salida .= "      </td>";
                $this->salida .= "      <td class=\"label\" colspan=\"2\">SI";
                if($_POST['asegurado']==1)
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=1 checked>";
                }
                else
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=1>";
                }
                $this->salida .= "  NO";
                if($_POST['asegurado']==2)
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=2 checked>";
                }
                else
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=2>";
                }
                $this->salida .= "  FANT.";
                if($_POST['asegurado']==3 OR $_POST['asegurado']==NULL)
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=3 checked>";
                }
                else
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=3>";
                }
                $this->salida .= "  POLIZA FALSA";
                if($_POST['asegurado']==4)
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=4 checked>";
                }
                else
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=4>";
                }
                $this->salida .= "  POLIZA VENCIDA";
                if($_POST['asegurado']==5)
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=5 checked>";
                }
                else
                {
                    $this->salida .= "      <input type='radio' name=\"asegurado\" value=5>";
                }
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("poliza1")."\">POLIZA SOAT NO.: AT</label>";
                $this->salida .= "      </td>";
                $this->salida .= "      <td colspan=\"2\">";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza1\" value=\"".$_POST['poliza1']."\" maxlength=\"4\" size=\"4\">";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza2\" value=\"".$_POST['poliza2']."\" maxlength=\"20\" size=\"10\">";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"poliza3\" value=\"".$_POST['poliza3']."\" maxlength=\"1\" size=\"1\">";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_oscuro>";
                $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("aseguradora")."\">NOMBRE ASEGURADORA: </label>";
                $this->salida .= "      </td>";
                $AsegSoat=$this->BuscarAseguradoraSoat();
                $this->salida .= "      <td colspan=\"2\">";
                $this->salida .= "      <select name=\"aseguradora\" class=\"select\">";
                $this->salida .= "      <option value=\"\">----SELECCIONE----</option>";
                $A=explode(',',$_POST['aseguradora']);
                for($i=0;$i<sizeof($AsegSoat);$i++)
                {
                //NIT de FISALUD - para que no lo muestre
                    //if(('830031511-6')<>$AsegSoat[$i]['tercero_id'])
                    //{
                        if($A[1]==$AsegSoat[$i]['tercero_id'] AND $A[0]==$AsegSoat[$i]['tipo_id_tercero'])
                        {
                            $this->salida .="<option value=\"".$AsegSoat[$i]['tipo_id_tercero'].','.$AsegSoat[$i]['tercero_id'].','.$AsegSoat[$i]['identificador_at']."\" selected>".$AsegSoat[$i]['nombre_tercero']."</option>";
                        }
                        else
                        {
                            $this->salida .="<option value=\"".$AsegSoat[$i]['tipo_id_tercero'].','.$AsegSoat[$i]['tercero_id'].','.$AsegSoat[$i]['identificador_at']."\">".$AsegSoat[$i]['nombre_tercero']."</option>";
                        }
                    //}
                }
                $this->salida .= "      </select>";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td width=\"26%\"><label class=\"label\">* SUCURSAL O AGENCIA: </label>";
                $this->salida .= "      </td>";
                $this->salida .= "      <td colspan=\"2\">";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"sucursal\" value=\"".$_POST['sucursal']."\" maxlength=\"30\" size=\"20\">";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_oscuro>";
                $this->salida .= "      <td class=\"label\" width=\"26%\">VIGENCIA DE LA POLIZA</td>";
                $this->salida .= "      <td width=\"37%\">";
                $this->salida .= "      <label class=\"".$this->SetStyle("fechadesde")."\">DESDE: </label>";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadesde\" value=\"".$_POST['fechadesde']."\" maxlength=\"10\" size=\"15\">";
                $this->salida .= "      ".ReturnOpenCalendario('forma','fechadesde','/')."";
                $this->salida .= "      </td>";
                $this->salida .= "      <td width=\"37%\">";
                $this->salida .= "      <label class=\"".$this->SetStyle("fechahasta")."\">HASTA: </label>";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechahasta\" value=\"".$_POST['fechahasta']."\" maxlength=\"10\" size=\"15\">";
                $this->salida .= "      ".ReturnOpenCalendario('forma','fechahasta','/')."";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=modulo_list_claro>";
                $this->salida .= "      <td width=\"26%\"><label class=\"".$this->SetStyle("placa")."\">PLACA: </label>";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"placa\" value=\"".$_POST['placa']."\" maxlength=\"8\" size=\"12\">";
                $this->salida .= "      </td>";
                $this->salida .= "      <td width=\"37%\"><label class=\"".$this->SetStyle("marca")."\">MARCA: </label>";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"marca\" value=\"".$_POST['marca']."\" maxlength=\"30\" size=\"25\">";
                $this->salida .= "      </td>";
                $this->salida .= "      <td width=\"37%\"><label class=\"".$this->SetStyle("tipove")."\">TIPO: </label>";
                $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tipove\" value=\"".$_POST['tipove']."\" maxlength=\"20\" size=\"27\">";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      </table>";
                $this->salida .= "  </fieldset>";
                $this->salida .= "  </td></tr>";
                $this->salida .= "  </table><br>";
                $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
                $this->salida .= "  <tr>";
                $this->salida .= "  <td colspan=\"3\" align=\"center\">";
                $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EVENTO\">";
                $this->salida .= "  </td>";
                $this->salida .= "  </form>";
                $this->salida .= "  </tr>";
                $this->salida .= "  </table>";
                if($this->admin2 == 1)
                {
                    $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .= "  </table><br>";
                }
                if($this->polizamala == 1)
                {
                    $this->salida .="<script language='javascript'>";
                    $this->salida .="alert('POLIZA ERRONEA');\n";
                    $this->salida .="</script>";
                    $this->polizamala = 0;
                }
            }
            $this->salida .= ThemeCerrarTabla();
            return true;
        }
    }

    function SoatAdmisionMenu()//Evento elegido, modulo de admision
    {
        $evensoat=$this->BuscarEventoSoatMod($_REQUEST['Evento']);//58$_REQUEST['Evento']
        $nombsoat=$this->BuscarNombrePaci($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
        $this->salida .= "<table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td>";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">EVENTO SOAT SELECCIONADO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"11%\">DOCUMENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$nombsoat['tipo_id_paciente']."".' - '."".$nombsoat['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"40%\">";
        $this->salida .= "      ".$nombsoat['primer_apellido']."".' '."".$nombsoat['segundo_apellido']."".' '."".$nombsoat['primer_nombre']."".' '."".$nombsoat['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"3%\">No.</td>";
        $this->salida .= "      <td width=\"10%\">FECHA</td>";
        $this->salida .= "      <td width=\"10%\">HORA</td>";
        $this->salida .= "      <td width=\"20%\">POLIZA</td>";
        $this->salida .= "      <td width=\"35%\">ASEGURADORA</td>";
        $this->salida .= "      <td width=\"13%\">SALDO</td>";
        $this->salida .= "      <td width=\"9%\" >EPICRISIS</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "".(1)."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $vector=explode(' ',$evensoat['fecha_accidente']);
        $accfecha=explode('-',$vector[0]);
        $this->salida .= $accfecha[2].'/'.$accfecha[1].'/'.$accfecha[0];
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= $vector[1];
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= $evensoat['poliza'];
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= $evensoat['nombre_tercero'];
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $saldover=$evensoat['saldo'];
        $this->salida .= number_format(($saldover), 2, ',', '.');
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\">";
        $this->salida .= "ENLACE";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        return true;
    }

    //
    function DatosInformeSoat()//
    {
        UNSET($_SESSION['soat']['reportes']);
        $this->salida = ThemeAbrirTabla('SOAT - PERIODO RECLAMADO');
        $accion=ModuloGetURL('app','Soat','user','ValidarDatosInformeSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL ANEXO 1 - FORECAT - CONSOLIDADO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechadradi")."\">FECHA DE RADICACIÓN: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        if(empty($_POST['fechadradi']))
        {
            $_POST['fechadradi']=date("d/m/Y");
        }
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadradi\" value=\"".$_POST['fechadradi']."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('forma','fechadradi','/')."";
        $this->salida .= "      </td>";
        /*$this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("numeroradi")."\">NoMERO DE RADICADO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroradi\" value=\"".$_POST['numeroradi']."\" maxlength=\"20\" size=\"20\">";
        $this->salida .= "      </td>";*/
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechainici")."\">FECHA INICIAL: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechainici\" value=\"".$_POST['fechainici']."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('forma','fechainici','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("periodorec")."\">PERIODO RECLAMADO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        if(empty($_POST['periodorec']))
        {
            $_POST['periodorec']=date("m/Y");
        }
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"periodorec\" value=\"".$_POST['periodorec']."\" maxlength=\"7\" size=\"7\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechafinal")."\">FECHA FINAL: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechafinal\" value=\"".$_POST['fechafinal']."\" maxlength=\"10\" size=\"15\">";        $this->salida .= "      ".ReturnOpenCalendario('forma','fechafinal','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        /*$this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";*/
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"generar\" value=\"GENERAR REPORTE\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Soat','user','PrincipalSoat');
        $this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"SOAT - OPCIONES\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //
    function GenerarInformeSoat()//
    {
        $this->salida  = ThemeAbrirTabla('SOAT - DATOS DE LA AMBULANCIA - (INSERTAR O MODIFICAR)');
        $var=$this->BuscarDatosInformeSoat();
        $reporte= new GetReports();//FALSE
        $mostrar=$reporte->GetJavaReport('app','Soat','Soat_Anexo1',array('var'=>$var),array('rpt_name'=>$var['numeroradi'],'rpt_dir'=>'cache/','rpt_rewrite'=>TRUE));
        $funcion=$reporte->GetJavaFunction();
        $this->salida .= "$mostrar";
        $accion=ModuloGetURL('app','Soat','user','DatosInformeSoat');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL PACIENTE</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        if($this->uno == 1)
        {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DEL CONDUCTOR DE LA AMBULANCIA</legend>";
        $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"".$this->SetStyle("nombrecondA")."\" width=\"38%\">APELLIDOS Y NOMBRES:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"62%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecondA\" value=\"".$_POST['nombrecondA']."\" maxlength=\"60\" size=\"50\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        if($var)//$datos
        {
            $this->salida .= "      <tr class=modulo_list_claro>";
            $this->salida .= "      <td align=\"center\" colspan=\"2\">";
            $this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\">";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        //$this->salida .= "  <td align=\"center\" width=\"50%\">";
        //$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"IMPRIMIR\">";
        //$this->salida .= "  </td>";
        //$this->salida .= "  </form>";
        //$accion=ModuloGetURL('app','Soat','user','DatosInformeSoat');
        //$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"100%\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    
    function FormaCargosInternos($tipodocumento,$documento,$saldo,$vect)
    {
        if(empty($_SESSION['soat']['consumo']['Documento']))
        {
            $_SESSION['soat']['consumo']['TipoDocum']=$tipodocumento;
            $_SESSION['soat']['consumo']['Documento']=$documento;
        }
        $_SESSION['soat']['consumo']['nombresoat']=$this->BuscarNombrePaci($_SESSION['soat']['consumo']['TipoDocum'],$_SESSION['soat']['consumo']['Documento']);
        $this->salida  = ThemeAbrirTabla('SOAT - CONSUMOS INTERNOS');
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset class=\"fieldset\"><legend class=\"normal_11N\">INFORMACIÓN DE CUENTAS</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\" colspan=\"3\">";
        $this->salida .= "      ".$_SESSION['soa1']['razonso']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"70%\" colspan=\"3\">";
        $this->salida .= "      ".$_SESSION['soa1']['descentro']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";

        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">DOCUMENTO:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"25%\">";
        $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['consumo']['nombresoat']['paciente_id']."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"9%\">NOMBRE:";
        $this->salida .= "      </td>";
        $this->salida .= "      <td align=\"center\" width=\"65%\">";
        $this->salida .= "      ".$_SESSION['soat']['consumo']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['consumo']['nombresoat']['segundo_nombre']."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";

        $this->salida .= "      </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "      <tr class=modulo_table_list_title>";
        $this->salida .= "      <td align=\"center\" width=\"30%\" >No. FACTURA</td>";
        $this->salida .= "      <td align=\"right\" width=\"70%\" >VALOR</td>";
        $this->salida .= "      </tr>";
        foreach($vect AS $i => $v)
        {
            $this->salida .= "      <tr>";
            $this->salida .= "      <td align=\"center\" width=\"30%\" >".$v[prefijo].$v[factura_fiscal]."</td>";
            $this->salida .= "      <td align=\"right\" width=\"70%\" >".FormatoValor($v[total_factura])."</td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "      <tr class=modulo_table_list_title>";
        $this->salida .= "      <td align=\"right\" width=\"70%\" >Saldo Inicial$ </td>";
        $this->salida .= "      <td align=\"right\" width=\"30%\" >".FormatoValor($vect[0][saldo_inicial])."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_table_list_title>";
        $this->salida .= "      <td align=\"right\" width=\"70%\" >Saldo $ </td>";
        $this->salida .= "      <td align=\"right\" width=\"30%\" >".FormatoValor($saldo)."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EVENTOS\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    
    function FrmEditaDatosIngreso($var)
    {
        $this->salida  = ThemeAbrirTabla('SOAT - MODIFICACIÓN DATOS INGRESO');
        $accion=ModuloGetURL('app','Soat','user','GuardarDatosIngreso',array('ingreso'=>$var['ingreso']));
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_table_title>";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">DATOS INGRESO";
        $this->salida .= "      </td>";
/*        $this->salida .= "      <td colspan=\"2\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"sucursal\" value=\"".$_POST['sucursal']."\" maxlength=\"30\" size=\"20\">";
        $this->salida .= "      </td>";*/
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro>";
        $this->salida .= "      <td class=\"label\" width=\"26%\">FECHA</td>";
        $this->salida .= "      <td width=\"37%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechadesde")."\">INGRESO: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechaingreso\" value=\"".$var[fecha_ingreso]."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('forma','fechaingreso','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"37%\">";
        $this->salida .= "      <label class=\"".$this->SetStyle("fechahasta")."\">EGRESO: </label>";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechaegreso\" value=\"".$var[fecha_egreso]."\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      ".ReturnOpenCalendario('forma','fechaegreso','/')."";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td class=\"label\" width=\"100%\" colspan=\"3\">DIAGNOSTICO</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_claro width=\"100%\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"3\">";
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "         <tr class=modulo_list_oscuro>";
	$this->salida .= "           <td width=\"50%\">"; 
        $this->salida .= "           <label class=\"".$this->SetStyle("diagingreso")."\">INGRESO: ".$var[diagnostico_id_ingreso]."</label>";
        $this->salida .= "           <input type=\"text\" class=\"input-text\" name=\"diagingreso\" value=\"".$var[desc_diagnostico_in]."\" maxlength=\"100\" size=\"50\" readonly>";
        $this->salida .= "           </td>";
        $this->salida .= "           <td width=\"50%\">";
        $this->salida .= "           <label class=\"".$this->SetStyle("diagegreso")."\">EGRESO: ".$var[diagnostico_id_egreso]."</label>";
        $this->salida .= "           <input type=\"text\" class=\"input-text\" name=\"diagegreso\" value=\"".$var[desc_diagnostico_de]."\" maxlength=\"100\" size=\"50\" readonly>";
        $this->salida .= "           </td>";
        $this->salida .= "         </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "     </td>";
        $this->salida .= "     </tr>"; 


	$this->salida .= "	<tr>\n";
	$mostrar = ReturnClassBuscador('diagnostico','','','forma');
	$this->salida .=$mostrar;
	$this->salida .= "	</script>\n";
	$this->salida .= "		<td class=\"modulo_table_list_title\" colspan=\"3\">DIAGNOSTICO: </td>\n";
	$this->salida .= "		<input type=\"hidden\" name=\"codigo\" size=\"6\" class=\"input-text\" value=\"".$this->Codigo."\">\n";
	$this->salida .= "	</tr>\n";
	$this->salida .= "	<tr>\n";
	$this->salida .= "		<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
	$this->salida .= "			<textarea cols=\"100\" rows=\"3\" class=\"textarea\" name=\"cargo\" READONLY>".$this->Codigo." - ".$this->Cargo."</textarea>\n";
	$this->salida .= "		</td>\n";
	$this->salida .= "	</tr>\n";
	$this->salida .= "	<tr>\n";
	$this->salida .= "		<td  class=\"modulo_list_claro\" align=\"center\" colspan=\"3\">\n";
	$this->salida .= "			<input type=\"button\" name=\"buscar\" value=\"Buscar Diagnostico\" onclick=abrirVentana() class=\"input-submit\">\n";
	$this->salida .= "		</td>\n";
	$this->salida .= "	</tr>\n";
        $this->salida .= "  <tr>";
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" colspan=\"3\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"GUARDAR\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>"; 
        $this->salida .= "  </form>";


        $accion=ModuloGetURL('app','Soat','user','DatosAccidente');
        $this->salida .= "  <tr>";
        $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" colspan=\"3\"><br>";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>"; 
        $this->salida .= "  </form>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    
    /***
    ****
    ****
    ***/
    
    function FrmBusquedaDiagnosticos($vectorD)
    {
		if($_REQUEST[tipod]=='i')
		{
			$titulo = "DIAGNOSTICO DE INGRESO";
		}
		elseif($_REQUEST[tipod]=='i1' OR $_REQUEST[tipod]=='i2')
		{
			$titulo = "OTRO DIAGNOSTICO DE INGRESO";
		}
		elseif($_REQUEST[tipod]=='e')
		{
			$titulo = "DIAGNOSTICO DE EGRESO";
		}
		elseif($_REQUEST[tipod]=='e1' OR $_REQUEST[tipod]=='e2')
		{
			$titulo = "OTRO DIAGNOSTICO DE EGRESO";
		}
		if($_REQUEST[tipod])
		{
			SessionSetVar('tipod',$_REQUEST[tipod]);
		}
		else
			$_REQUEST[tipod] = SessionGetVar('tipod');
    //echo $_REQUEST[tipod];
		$this->salida= ThemeAbrirTabla($titulo);
		$accionD = ModuloGetURL("app","Soat","user","Busqueda_Diagnosticos");
		$this->salida.= "<form name=\"formades\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA DE DIAGNOSTICOS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_list\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' name = 'diagnostico'   value =\"".$_REQUEST['diagnostico']."\"></td>" ;
		$this->salida.="<td width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accionI=ModuloGetURL("app","Soat","user","AsignarValores",array('vectorD'=>sizeof($vectorD),'tipod'=>$_REQUEST[tipod]));
		$this->salida.= "<form name=\"formades\" action=\"$accionI\" method=\"post\">";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"8%\">CODIGO</td>";
			$this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
			//$this->salida.="  <td width=\"17%\">TIPO DX</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				$codigo= $vectorD[$i][diagnostico_id];
				$diagnostico= $vectorD[$i][diagnostico_nombre];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\" width=\"8%\">$codigo</td>";
				$this->salida.="<td align=\"left\" width=\"60%\">$diagnostico</td>";
				//$this->salida.="<td align=\"center\" width=\"17%\">";
				//$this->salida.="<input type=\"radio\" name=\"dx$i\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				//$this->salida.="<input type=\"radio\" name=\"dx$i\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				//$this->salida.="<input type=\"radio\" name=\"dx$i\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
				$this->salida.="<input type=\"hidden\" name=\"descripcion$i\" value=\"".$codigo."||//".$diagnostico."\">";
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = radio name= 'radioD' value = ".$codigo."></td>";
				$this->salida.="</tr>";
			}

			//$this->salida.="<tr class=\"$estilo\">";
			//$this->salida.="<td align=\"left\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			//$this->salida.="</tr>";

			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"elegir\" type=\"submit\" value=\"Seleccionar\"></td>";
			$this->salida.="</tr>";

			$this->salida.="</table><br>";
/*
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
*/
		}
		$this->salida .= "</form>";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$accion=ModuloGetURL('app','Soat','user','AtencionMedica');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTabla();
		return true;
    }
}//fin de la clase
?>