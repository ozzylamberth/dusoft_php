<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionDocumentosBodeHTML.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase Vista: ParametrizacionDocumentosBodeHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
  
  class ParametrizacionDocumentosBodeHTML
  {
    /**
           * Constructor de la clase
          */
    function ParametrizacionDocumentosBodeHTML(){}
    
    /**
            * Funcion donde se crea la forma para el menu de Parametrizacion tiempo de cita
           *
           * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
          */
    function formaMenu($action)
    {
      $html  = ThemeAbrirTabla('PARAMETRIZAR BUSQUEDA DE DOCUMENTOS');
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\">MENU\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['parametrizar_busqueda_documentos']."\" class=\"label_error\">PARAMETRIZAR BUSQUEDA DE DOCUMENTOS</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
          * Funcion donde se crea la forma para mostrar todos los usuarios que tienen permiso para documentos
          * 
          * @param array $datos vector que contiene la informacion de la consulta
          * @param array $action vector que contiene los link de la aplicacion
          * @param array $request vector que contiene la informacion de la busqueda
          * @param string $pagina cadena con el numero de la pagina que se esta visualizando
          * @param string $conteo cadena con la cantidad de los datos que se muestran
          * @return string $html retorna la cadena con el codigo html de la pagina
          */
    function formaBuscarUsuariosDocume($datos,$action,$request,$pagina, $conteo)
    {
      $html = ThemeAbrirTabla('LISTADO DE USUARIOS');
      $clas = AutoCarga ::factory("ClaseUtil");
      $html .= $clas->RollOverFilas();
      $html .= $clas->AcceptNum();
      $html .= $clas->IsNumeric();
      $html .= "<form name=\"formBuscarUsuariosDocume\" id=\"formBuscarUsuariosDocume\" method=\"post\" action=\"".$action['buscar_usuarios']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"40%\">\n";
      $html .= "    <tr class=\"modulo_table_title\" >\n";
      $html .= "      <td align=\"center\"colspan=\"2\">BUSCADOR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\"align=\"left\" width=\"40%\">USUARIO\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"30%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar_usuarios[id]\" maxlength=\"20\" value=".$request['id']."></td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\"align=\"left\" width=\"40%\">NOMBRE\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"30%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar_usuarios[usuario]\" maxlength=\"20\" value=".$request['usuario']."></td>\n";
      $html .= "    </tr>\n";
      $html .= "	  <td align='center'>\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "	  </td>\n"; 
      $html .= "</form>\n";
      $html .= "  <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		<td align='center' >\n";
      $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$html .= "		</td>\n";
			$html .= "  </form>\n";
      $html .= " </table>\n";
      if(!empty($datos))
      {
        $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td class=\"formulacion_table_list\" align=\"left\" width=\"25%\" colspan=\"3\">USUARIOS - DOCUMENTOS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"center\" width=\"10%\">USUARIO\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\" width=\"40%\">NOMBRE\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\" width=\"40%\">ASIGNAR BUSQUEDA\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        foreach ($datos as $indice=>$valor)
        {
          ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
          
          $html .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "  <td>".$valor['id']."</td>\n";
          $html .= "  <td>".$valor['usuario']."</td>\n";
          $html .= "  <td>\n";
          $html .= "    <a href=\"".$action['asignarBusqueda'].URLRequest(array("usuario_id"=>$valor['id'],"usuario"=>$valor['usuario']))."\" class=\"label_error\">\n";
          $html .= "       <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">ASIGNAR BUSQUEDA\n";
          $html .= "    </a>\n";
          $html .= "  </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "  </table>\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "  ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'],5);
      }
      else
      {
        if($request)
        $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
      }
   
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
          * Funcion donde se crea la forma para la asignacion de los tipos de busqueda
          *
          * @param array $datos vector que contiene la informacion del request
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
          */
    function formaAsignacionBusqueda($action,$datos)
    {
      $html  = ThemeAbrirTabla('BUSQUEDA DE DOCUMENTOS');
      $clas = AutoCarga ::factory("ClaseUtil");
      $html .= $clas->RollOverFilas();
      $html .= $clas->AcceptNum();
      $html .= $clas->IsNumeric();
      $html .= "<form name=\"formAsignacionBusqueda\" id=\"formAsignacionBusqueda\" method=\"post\" action=\"".$action['guardarAsignacionBusqueda']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"left\" width=\"25%\" colspan=\"2\">REALIZA BUSQUEDA POR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if(empty($datos))
      {
        $html .= "    <tr class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DEL PRODUCTO</b>\n";
        $html .= "       <input type=\"hidden\" name=\"parametro_id\" value=\"".$valor['parametro_id']."\"size=\"5\">\n"; 
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"codigo_producto\" value=\"1\"".$cp."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>NOMBRE DEL PRODUCTO</b>\n";
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"nombre_producto\" value=\"1\"".$np."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DE BARRAS</b>\n";
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"codigo_barras\" value=\"1\"".$cb."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>NOMBRE DE MOLECULA</b>\n";
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"nombre_molecula\" value=\"1\"".$nm."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DE MOLECULA</b>\n";
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"codigo_molecula\" value=\"1\"".$cm."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>NOMBRE DEL LABORATORIO</b>";
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"nombre_laboratorio\" value=\"1\"".$nl."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DEL LABORATORIO</b>";
        $html .= "      </td>";
        $html .= "      <td align=\"center\" width=\"10%\"> \n";
        $html .= "       <input type=\"checkbox\" name=\"codigo_laboratorio\" value=\"1\"".$cl."> \n";
        $html .= "      </td>";
        $html .= "    </tr>";
      }
      else
      {
        foreach ($datos as $indice=>$valor)
        {
          $html .= "    <tr class=\"modulo_list_oscuro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DEL PRODUCTO</b>\n";
          $html .= "       <input type=\"hidden\" name=\"parametro_id\" value=\"".$valor['parametro_id']."\"size=\"5\">\n"; 
          $html .= "      </td>";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          if($valor['sw_codigoproducto']==1)
           $cp="checked";
          $html .= "       <input type=\"checkbox\" name=\"codigo_producto\" value=\"1\"".$cp."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>NOMBRE DEL PRODUCTO</b>\n";
          $html .= "      </td>";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          if($valor['sw_nombreproducto']==1)
           $np="checked";
          $html .= "       <input type=\"checkbox\" name=\"nombre_producto\" value=\"1\"".$np."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_oscuro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DE BARRAS</b>\n";
          $html .= "      </td>";
          if($valor['sw_codigobarras']==1)
           $cb="checked";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          $html .= "       <input type=\"checkbox\" name=\"codigo_barras\" value=\"1\"".$cb."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>NOMBRE DE MOLECULA</b>\n";
          $html .= "      </td>";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          if($valor['sw_nombremolecula']==1)
           $nm="checked";
          $html .= "       <input type=\"checkbox\" name=\"nombre_molecula\" value=\"1\"".$nm."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_oscuro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DE MOLECULA</b>\n";
          $html .= "      </td>";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          if($valor['sw_codigomolecula']==1)
           $cm="checked";
          $html .= "       <input type=\"checkbox\" name=\"codigo_molecula\" value=\"1\"".$cm."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>NOMBRE DEL LABORATORIO</b>";
          $html .= "      </td>";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          if($valor['sw_nombrelaboratorio']==1)
           $nl="checked";
          $html .= "       <input type=\"checkbox\" name=\"nombre_laboratorio\" value=\"1\"".$nl."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_oscuro\">\n";
          $html .= "      <td align=\"center\" width=\"70%\"><b>CODIGO DEL LABORATORIO</b>";
          $html .= "      </td>";
          $html .= "      <td align=\"center\" width=\"10%\"> \n";
          if($valor['sw_codigolaboratorio']==1)
           $cl="checked";
          $html .= "       <input type=\"checkbox\" name=\"codigo_laboratorio\" value=\"1\"".$cl."> \n";
          $html .= "      </td>";
          $html .= "    </tr>";
        }
      }
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td align=\"center\" width=\"100%\" colspan=\"2\"> \n";
      $html .= "          <a href=\"javascript:Seleccionar_Todo()\"class=\"label_error\">TODOS\n";
      $html .= "      </td>\n";
      $html .= "      </td>";
      $html .= "    </tr>";
     
      $html .= "  </table>\n";
      $html .= " </form>\n";
      $html .= " <table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "   <td>\n";
      $html .= "    <input class=\"input-submit\" type=\"button\" value=\"Guardar\" onclick=\"ValidarSeleccionBusqueda()\">\n";
      $html .= "   </td>\n";
      $html .= " <form name=\"formVolver\" id=\"formVolver\" method=\"post\" action=\"".$action['volver']."\">\n";
      $html .= "   <td>\n";
      $html .= "     <input class=\"input-submit\" type=\"submit\" value=\"Volver\">\n";
      $html .= "   </td>\n";
      $html .= "  </tr>\n";
      $html .= " </table>\n";
      $html .= " </form>\n";            
      $html .= "<script>";
      $html .= "  function ValidarSeleccionBusqueda()\n";
      $html .= "  {\n";
      $html .= "    bandera = false;\n";
      $html .= "    frm=document.formAsignacionBusqueda;\n";
      $html .= "    codigop=frm.codigo_producto.checked;\n";
      $html .= "    nombrep=frm.nombre_producto.checked;\n";
      $html .= "    codigob=frm.codigo_barras.checked;\n";
      $html .= "    nombrem=frm.nombre_molecula.checked;\n";
      $html .= "    codigom=frm.codigo_molecula.checked;\n";
      $html .= "    nombrel=frm.nombre_laboratorio.checked;\n";
      $html .= "    codigol=frm.codigo_laboratorio.checked;\n";
      $html .= "    if (!codigop && !nombrep && !codigob && !nombrem && !codigom && !nombrel && !codigol)\n";
      $html .= "    {\n";
      $html .= "      alert ('DEBE ELEGIR UNA OPCION');\n";
		  $html .= "      return false;\n";
      $html .= "    }\n";
      $html .= "    else\n";
      $html .= "    {\n";
      $html .= "     frm.submit();\n ";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function Seleccionar_Todo()\n";
      $html .= "  {\n";
      $html .= "    frm=document.formAsignacionBusqueda;\n";
      $html .= "    for (i=0;i<frm.elements.length;i++)\n";
      $html .= "      if(frm.elements[i].type == 'checkbox')\n";
      $html .= "        frm.elements[i].checked=1;\n"; 
      $html .= "  }\n";
      $html .= "</script>";  
       
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
          * Funcion donde se crea la forma de mensaje
          *
          * @param array $action vector que contiene los link de la aplicacion
          * @param var $mensaje variable que contiene el mensaje
          * @return string $html retorna la cadena con el codigo html de la pagina
          */
    function formaMensajeInTc($action,$mensaje)
    {
      $html  = ThemeAbrirTabla('MENSAJE',500);
      $html .= "<table class=\"modulo_table_list\"align=\"center\">\n ";
      $html .= "  <tr>\n";
      $html .= "    <td> ".$mensaje." </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<table align=\"center\">";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>";
      $html .= "  </tr>";
      $html .= "</table>";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
  }  
?>