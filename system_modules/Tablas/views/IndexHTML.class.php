<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: IndexHTML.class.php,v 1.9 2008/04/07 13:27:47 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: IndexHTML
	* Clase que permite crear las vistas por defecto del modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.9 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class IndexHTML
  {
    /**
    * Guarda el html de los javascript
    *
    * @var string
    * @access public
    */
    var $script;
    /**
    * Guarda las funciones ajaxa que se usaran
    *
    * @var array
    * @access public
    */
    var $ajax = array();
    /**
    * Variable de conteo
    *
    * @var int
    * @access public
    */
    var $i = 0;
    /**
    * Constructor de la clase
    */
    function IndexHTML(){}
    /**
    * Funcion donde se crean el html de los campos que se veran en la forma
    * 
    * @param String $forma  nombre de la forma
    * @param String $tipo  Tipo de dato del campo
    * @param String $name  Nombre del campo
    * @param int $null Define si el campo es obligatorio o no
    * @param String $valor  Valor que contiene el campo
    * @param int $longitud  Longitud del campo
    *
    * @return String
    */
    function CrearCampos($forma,$tipo,$name,$null,$valor=null,$longitud = null)
    {
      switch($tipo)
      {
        case 'character':
        case 'character varying':
          $html .= "<input class=\"input-text\" type=\"text\" name=\"".$name."\" value=\"".$valor."\" ";
          
          if(!($longitud === null)) $html .= "maxlength =\"$longitud\" ";
          
          $html .= " style=\"width:90%\">\n";
          
          $this->script .= "	obligatorios[".($this->i++)."] = new Array(objeto.".$name.".value,".$null.",'".ucfirst(str_replace("_"," ",$name))."','text');\n";

        break;
        case 'smallint':
        case 'integer':
          $html .= "<input class=\"input-text\" type=\"text\" name=\"".$name."\" value=\"".$valor."\" ";
          
          if(!($longitud === null)) $html .= "maxlength =\"$longitud\" ";
          
          $html .= " style=\"width:90%\" onKeypress=\"return acceptInteger(event)\">\n";
          
          $this->script .= "	obligatorios[".($this->i++)."] = new Array(objeto.".$name.".value,".$null.",'".ucfirst(str_replace("_"," ",$name))."','numeric');\n";
        break;
        case 'numeric':
          $html .= "<input class=\"input-text\" type=\"text\" name=\"".$name."\" value=\"".$valor."\" ";
          
          if(!($longitud === null)) $html .= "maxlength =\"$longitud\" ";
          
          $html .= " style=\"width:90%\" onKeypress=\"return acceptInteger(event)\">\n";
          
          $this->script .= "	obligatorios[".($this->i++)."] = new Array(objeto.".$name.".value,".$null.",'".ucfirst(str_replace("_"," ",$name))."','numeric');\n";
        break;
        case 'date':
        case 'timestamp without time zone':
          if($valor)
          {
            $l = explode(" ",$valor);
            if(sizeof($l) > 1)
            {
              $f = explode("-",$l[0]);
              $valor = $f[2]."/".$f[1]."/".$f[0];
            }
          }
          $html .= "<input class=\"input-text\" type=\"text\" name=\"".$name."\" value=\"".$valor."\" size=\"12\" maxlength =\"10\" onKeypress=\"return acceptDate(event)\">\n";
					$html .= " ".ReturnOpenCalendario($forma,$name,'/')."\n";			

          $this->script .= "	obligatorios[".($this->i++)."] = new Array(objeto.".$name.".value,".$null.",'".ucfirst(str_replace("_"," ",$name))."','date');\n";
        break;
        case 'text':
        	$html .= "<textarea name=\"".$name."\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$valor."</textarea>\n";		

          $this->script .= "	obligatorios[".($this->i++)."] = new Array(objeto.".$name.".value,".$null.",'".ucfirst(str_replace("_"," ",$name))."','text');\n";
        break;
      }
      return $html;
    }
    /**
    * Funcion donde se crean la forma del index
    * 
    * @param array $action  Vector con los datos de los links
    * @param array $request Vector con los datos que llegan por request
    * @param array $campos Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos de los registros de la tabla
    * @param array $primarykey Vector con los datos de la llave primaria
    * @param int $pagina Pagina actual en la qiue se esta navegando
    * @param int $conteo Cantidad de registros totales
    * @param String $comentario Comentario de la tabla
    *
    * @return String
    */
    function FormaIndex($action,$request,$campos,$datos,$primarykey,$pagina,$conteo,$comentario)
    {
      $html  = ThemeAbrirTabla("TABLA: ".strtoupper($request['nombre_tabla']));
      if($comentario != "")
      {
        $html .= "<center>\n";
        $html .= "  <div class=\"fieldset\" style=\"width:48%\">\n";
        $html .= "    ESTA TABLA CONTIENE EL SIGUIENTE COMENTARIO: <br><label class=\"normal_10AN\">".strtoupper($comentario)."</label>\n";
        $html .= "  </div>\n";
        $html .= "</center>\n";
      }
      $html .= $this->CrearBuscador($campos,$request,$action);
      $html .= "<center>\n";
      $html .= "  <a class=\"label_error\" href=\"".$action['adicionar']."\">\n";
      $html .= "    ADICIONAR UN NUEVO REGISTRO DE: ".strtoupper($request['nombre_tabla'])." \n";
      $html .= "  </a>\n";
      $html .= "</center><br>\n";
      if (is_array($datos)) 
      {      
        if(!empty($datos))
        {
          $clase = "modulo_list_claro";
          $html .= "<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
          $html .= "  <tr class=\"modulo_table_list_title\">\n";
      
          foreach ($campos[$request['nombre_tabla']] as $fieldName) 
          {
            $html .= "    <td>".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
          }
        
          $html .= "    <td colspan=\"3\" width=\"9%\">Opciones</td>";
          $html .= "  </tr>\n";

          foreach ($datos as $key => $row) 
          {
            ($clase == "modulo_list_claro")? $clase = "modulo_list_oscuro": $clase = "modulo_list_claro";
            
            $html .= "  <tr class=\"$clase\">\n";
            foreach ($campos[$request['nombre_tabla']] as $fieldName) 
            {
              $html .= "    <td>".$row[$fieldName['name']]."</td>\n";
            }
            
            $url = array();
            $id = "";
            foreach($primarykey as $pkey => $desc)
            {
              $url[$desc] = $row[$desc];
              ($id == "")? $id = " ".$row[$desc]: $id .= " - ".$row[$desc];
            }
            $req = URLRequest(array("pkey"=>$url));
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a class=\"label_error\" title=\"VER REGISTRO\" href=\"".$action['ver'].$req."\">\n";
            $html .= "        <img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";	          
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a class=\"label_error\" title=\"EDITAR REGISTRO\" href=\"".$action['editar'].$req."\">\n";
            $html .= "        <img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a class=\"label_error\" title=\"ELIMINAR REGISTRO\" href=\"".$action['eliminar'].$req."\" onclick=\"return confirm('Esta seguro que desea eliminar el registro con id ".$id." ?');\">\n";
            $html .= "        <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "   </tr>\n";
          }
          $html .= "</table><br>\n";
        
          $chtml = AutoCarga::factory('ClaseHTML');
          $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    			$html .= "		<br>\n";
        }
        else
        {
          $html .= "<center>\n";
          $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
          $html .= "</center>\n";
        }
      }
      
      if($action['volver'])
      {
        $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
        $html .= "  <table align=\"center\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "        <input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
      }
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se consultan los datos
    * 
    * @param array $action Vector con los datos de los links
    * @param array $request Vector con los datos que llegan por request
    * @param array $campos Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos de los registros de la tabla
    * @param array $primarykey Vector con los datos de la llave primaria
    *
    * @return String
    */
    function FormaVerRegistro($action,$request,$campos,$datos,$primarykey)
    {
      $html  = ThemeAbrirTabla("TABLA: ".strtoupper($request['nombre_tabla'])); 
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= " <tr class=\"formulacion_table_list\">\n";
      $html .= "		<td width=\"48%\">CAMPO</td>\n";
      $html .= "		<td>VALOR</td>\n";
      $html .= "	</tr>\n";
      foreach ($campos[$request['nombre_tabla']] as $fieldName) 
      {
  	    if($fieldName['type'] == "text")
        {
          $html .= "	<tr class=\"formulacion_table_list\">\n";
          $html .= "		<td colspan=\"2\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
          $html .= "	</tr>\n";
          $html .= "	<tr class=\"modulo_list_claro\">\n";
          $html .= "		<td colspan=\"2\" align=\"justify\" height=\"14\">\n";
          $html .= "      ".$datos[$fieldName['name']]."\n";
          $html .= "		</td>\n";
          $html .= "	</tr>\n";           
        }
        else
        {
          $html .= "	<tr class=\"formulacion_table_list\">\n";
          $html .= "		<td style=\"text-indent:8pt;text-align:left\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
          $html .= "		<td class=\"modulo_list_claro\" style=\"text-indent:8pt;text-align:left\">\n";
          $html .= "      ".$datos[$fieldName['name']]."\n";
          $html .= "		</td>\n";
          $html .= "	</tr>\n";  
        }
  		}
      $html .= "</table>\n";
      $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla(); 
      
      return $html;
    }
    /**
    * Funcion donde se crean la forma para editar un registro
    * 
    * @param array $action Vector con los datos de los links
    * @param array $request Vector con los datos que llegan por request
    * @param array $campos Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos de los registros de la tabla
    * @param array $primarykey Vector con los datos de la llave primaria
    * @param array $foreignkey Vector con los datos de las tablas con las que tiene 
    *         relacion la tabla que se esta mostrando
    * @param String $mensaje Mensaje que se mostrara en pantalla
    *
    * @return String
    */
    function FormaEditarRegistro($action,$request,$campos,$datos,$primarykey,$foreignkey,$mensaje = "")
    {
      $html  = ThemeAbrirTabla("TABLA: ".strtoupper($request['nombre_tabla'])); 
      $html .= "<form name=\"editar\" id=\"editar\" action=\"javascript:evaluarDatosObligatorios(document.editar)\" method=\"post\">\n";
      $html .= "<center>\n";
      $html .= "  <div class=\"label_error\" id=\"error_e\">".$mensaje."</div>\n";
      $html .= "</center>\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= " <tr class=\"formulacion_table_list\">\n";
      $html .= "		<td width=\"1%\">NULL</td>\n";
      $html .= "		<td width=\"48%\">CAMPO</td>\n";
      $html .= "		<td>VALOR</td>\n";
      $html .= "		<td width=\"1%\">?</td>\n";
      $html .= "	</tr>\n";
      foreach ($campos[$request['nombre_tabla']] as $fieldName) 
      { 
        $chk = "";
        if($fieldName['type'] == "text")
        {
          $html .= "	<tr class=\"formulacion_table_list\">\n";
          $html .= "		<td>\n";
          if($fieldName['null'] == 0)
          {
            ($datos[$fieldName['name']] === null)? $chk = "checked": $chk="";
            $html .= "		  <input type=\"checkbox\" name=\"chk_".$fieldName['name']."\" $chk>\n";
          } 
          $html .= "		</td>\n";
          $html .= "		<td colspan=\"2\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
          $html .= "	  <td class=\"modulo_list_claro\" style=\"cursor:pointer\">\n";
          if($fieldName['comment'])
            $html .= "        <img src=\"".GetThemePath()."/images/interrogacion.png\" title=\"".$fieldName['comment']."\" border=\"0\" width=\"14\" height=\"14\">\n";
          $html .= "    </td>\n";
          $html .= "	</tr>\n";
          $html .= "	<tr class=\"formulacion_table_list\">\n";
          $html .= "		<td>&nbsp;</td>\n";
          $html .= "		<td colspan=\"2\">\n";
          $html .= "      ".$this->CrearCampos("editar",$fieldName['type'],$fieldName['name'],$fieldName['null'],$datos[$fieldName['name']],$fieldName['char_length'])."\n";
          $html .= "		</td>\n";
          $html .= "    <td></td>\n";
          $html .= "	</tr>\n";           
        }
        else
        {
          $html .= "	<tr class=\"formulacion_table_list\">\n";
          $html .= "		<td>\n";
          if($fieldName['null'] == 0)
          {
            ($datos[$fieldName['name']] === null)? $chk = "checked": $chk="";
            $html .= "		  <input type=\"checkbox\" name=\"chk_".$fieldName['name']."\" $chk>\n";
          }
          $html .= "		</td>\n";
          $html .= "		<td style=\"text-indent:8pt;text-align:left\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
          $html .= "		<td class=\"modulo_list_claro\" style=\"text-indent:8pt;text-align:left\">\n";
          $html .= "      ".$this->CrearCampos("editar",$fieldName['type'],$fieldName['name'],$fieldName['null'],$datos[$fieldName['name']],$fieldName['char_length'])."\n";
          $html .= "		</td>\n";
          $html .= "	  <td class=\"modulo_list_claro\" style=\"cursor:pointer\">\n";
          if($fieldName['comment'])
            $html .= "        <img src=\"".GetThemePath()."/images/interrogacion.png\" title=\"".$fieldName['comment']."\" border=\"0\" width=\"14\" height=\"14\">\n";
          $html .= "    </td>\n";
          $html .= "	</tr>\n";
        }
  		}
      $html .= "</table><br>\n";
      if(!empty($foreignkey))
      {
        $html .= "<table width=\"60%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td class =\"normal_10AN\">\n";
        $html .= "      LISTA DE TABLAS RELACIONADAS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "       <ul>\n";
        foreach($foreignkey as $key => $llaves)
        {
          $html .= "        <li>\n";
          $html .= "          <a class=\"label_error\" href=\"javascript:AbrirVentana('".$key."','".URLRequest(array("eqivalencias"=>$llaves))."')\">Tabla: ".$key."</a>\n";
        } 
        $html .= "       </ul>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<script>\n";
        $html .= "  function AbrirVentana(nombre_tabla,url)\n"; 
        $html .= "  {\n"; 
        $html .= "    window.open('".$action['ventana']."&nombre_tabla='+nombre_tabla+url+'&forma=editar','localidad','toolbar=no,width=800,height=600,resizable=no,scrollbars=yes').focus(); \n";
        $html .= "  }\n"; 
        $html .= "</script>\n";
      }
      $html .= "<center>\n";
      $html .= "  <div class=\"label_error\" id=\"error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<table align=\"center\" width=\"60%\">\n";
      $html .= "  <td align=\"center\">\n";
      $html .= "    <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "  </td>\n";
      $html .= "</form>\n";
      $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <td align=\"center\">\n";
      $html .= "    <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\">\n";
      $html .= "  </td>\n";
      $html .= "</form>\n";
      $html .= "</table>\n";
      $html .= $this->Scripts($action);
      $html .= ThemeCerrarTabla(); 
      
      return $html;
    }
    /**
    * Funcion donde se crean la forma para editar un registro
    * 
    * @param array $action Vector con los datos de los links
    * @param array $request Vector con los datos que llegan por request
    * @param array $campos Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos de los registros de la tabla
    * @param array $primarykey Vector con los datos de la llave primaria
    * @param array $foreignkey Vector con los datos de las tablas con las que tiene 
    *         relacion la tabla que se esta mostrando
    * @param String $mensaje Mensaje que se mostrara en pantalla
    *
    * @return String
    */
    function FormaAdicionarRegistro($action,$request,$campos,$datos,$primarykey,$foreignkey,$mensaje = "")
    {
      $html  = ThemeAbrirTabla("TABLA: ".strtoupper($request['nombre_tabla'])); 
      $html .= "<form name=\"editar\" id=\"editar\" action=\"javascript:evaluarDatosObligatorios(document.editar)\" method=\"post\">\n";
      $html .= "<center>\n";
      $html .= "  <div class=\"label_error\" id=\"error_e\">".$mensaje."</div>\n";
      $html .= "</center>\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= " <tr class=\"formulacion_table_list\">\n";
      $html .= "		<td width=\"1%\">NULL</td>\n";
      $html .= "		<td width=\"48%\">CAMPO</td>\n";
      $html .= "		<td>VALOR</td>\n";
      $html .= "		<td width=\"1%\">?</td>\n";
      $html .= "	</tr>\n";
      foreach ($campos[$request['nombre_tabla']] as $fieldName) 
      { 
        $flag = true;
        foreach($primarykey as $k => $valor)
        {
          if($valor == $fieldName['name'])
          {
            if($fieldName['default'] != "NULL" && $fieldName['default'])
              $flag = false;
          
            break;
          }
        }
        if($flag)
        {
          if($fieldName['type'] == "text")
          {
            $html .= "	<tr class=\"formulacion_table_list\">\n";
            $html .= "		<td>\n";
            if($fieldName['null'] == 0)
              $html .= "		  <input type=\"checkbox\" name=\"chk_".$fieldName['name']."\">\n";
            $html .= "		</td>\n";
            $html .= "		<td colspan=\"2\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
            $html .= "	  <td class=\"modulo_list_claro\" style=\"cursor:pointer\">\n";
            if($fieldName['comment'])
              $html .= "        <img src=\"".GetThemePath()."/images/interrogacion.png\" title=\"".$fieldName['comment']."\" border=\"0\" width=\"14\" height=\"14\">\n";
            $html .= "    </td>\n";
            $html .= "	</tr>\n";
            $html .= "	<tr class=\"formulacion_table_list\">\n";
            $html .= "		<td></td>\n";
            $html .= "		<td colspan=\"2\">\n";
            $html .= "      ".$this->CrearCampos("editar",$fieldName['type'],$fieldName['name'],$fieldName['null'],$datos[$fieldName['name']],$fieldName['char_length'])."\n";
            $html .= "		</td>\n";
            $html .= "	  <td></td>\n";
            $html .= "	</tr>\n";           
          }
          else
          {
            $html .= "	<tr class=\"formulacion_table_list\">\n";
            $html .= "		<td>\n";
            if($fieldName['null'] == 0)
              $html .= "		  <input type=\"checkbox\" name=\"chk_".$fieldName['name']."\">\n";
            $html .= "		</td>\n";
            $html .= "		<td style=\"text-indent:8pt;text-align:left\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
            $html .= "		<td class=\"modulo_list_claro\" style=\"text-indent:8pt;text-align:left\">\n";
            $html .= "      ".$this->CrearCampos("editar",$fieldName['type'],$fieldName['name'],$fieldName['null'],$datos[$fieldName['name']],$fieldName['char_length'])."\n";
            $html .= "		</td>\n";
            $html .= "	  <td class=\"modulo_list_claro\" style=\"cursor:pointer\">\n";
            if($fieldName['comment'])
              $html .= "        <img src=\"".GetThemePath()."/images/interrogacion.png\" title=\"".$fieldName['comment']."\" border=\"0\" width=\"14\" height=\"14\">\n";
            $html .= "    </td>\n";
            $html .= "	</tr>\n";  
          }
        }
        else
        {
          $html .= "  <input type=\"hidden\" name=\"".$fieldName['name']."\" value=\"DEFAULT\">\n";
        }
  		}
      $html .= "</table><br>\n";
      
      if(!empty($foreignkey))
      {
        $html .= "<table width=\"60%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td class =\"normal_10AN\">\n";
        $html .= "      LISTA DE TABLAS RELACIONADAS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "       <ul>\n";
        foreach($foreignkey as $key => $llaves)
        {
          $html .= "        <li>\n";
          $html .= "          <a class=\"label_error\" href=\"javascript:AbrirVentana('".$key."','".URLRequest(array("eqivalencias"=>$llaves))."')\">Tabla: ".$key."</a>\n";
        } 
        $html .= "       </ul>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<script>\n";
        $html .= "  function AbrirVentana(nombre_tabla,url)\n"; 
        $html .= "  {\n"; 
        $html .= "    window.open('".$action['ventana']."&nombre_tabla='+nombre_tabla+url+'&forma=editar','localidad','toolbar=no,width=900,height=600,resizable=no,scrollbars=yes').focus(); \n";
        $html .= "  }\n"; 
        $html .= "</script>\n";
      }
      $html .= "<center>\n";
      $html .= "  <div class=\"label_error\" id=\"error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<table align=\"center\" width=\"60%\">\n";
      $html .= "  <td align=\"center\">\n";
      $html .= "    <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "  </td>\n";
      $html .= "</form>\n";
      $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <td align=\"center\">\n";
      $html .= "    <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\">\n";
      $html .= "  </td>\n";
      $html .= "</form>\n";
      $html .= "</table>\n";
      $html .= $this->Scripts($action);

      $html .= ThemeCerrarTabla(); 
      
      return $html;
    }
    /**
    * Funcion donde se crean scripts de validacion de datos
    * 
    * @param array $action Vector con los datos de los links
    *
    * @return String 
    */
    function Scripts($action)
    {
      $html  = "<script>\n";
      $html .= "	function evaluarDatosObligatorios(objeto)\n";
			$html .= "	{\n";
			$html .= "		div_msj = document.getElementById('error');\n";
			$html .= "		obligatorios = new Array();\n";
			$html .= $this->script."\n";
			$html .= "		for(i=0; i< ".$this->i."; i++)\n";
			$html .= "		{\n";
			$html .= "			if(obligatorios[i][1] == '1')\n";
			$html .= "			{\n";
      $html .= "				switch(obligatorios[i][3])\n";
			$html .= "				{\n";
			$html .= "				  case 'select':\n";
			$html .= "				    if(obligatorios[i][0] == '-1')\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = 'Se debe seleccionar '+obligatorios[i][2]+'';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'date':\n";
			$html .= "				    if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', es obligatoria o el formato no corresponde';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'numeric':\n";
			$html .= "				    if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', es obligatorio o el formato no corresponde';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'text':\n";
			$html .= "				    if(obligatorios[i][0] == '' || obligatorios[i][0] == undefined)\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', es obligatorio';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		objeto.action = \"".$action['actualizar']."\";\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
      $html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
      $html .= "	function acceptInteger(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor numérico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	}\n";
      $html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length > 3)\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";
      $html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "</script> \n";
      
      return $html;
    }
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action Vector que continen los link de la aplicacion
    * @param String $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return String
		*/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
    * Funcion donde se crea una forma para mostrar la lista de registros de 
    * la tabla 
    * 
    * @param array $action Vector con los datos de los links
    * @param array $request Vector con los datos que llegan por request
    * @param array $campos Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos de los registros de la tabla
    * @param int $pagina Pagina actual en la qiue se esta navegando
    * @param int $conteo Cantidad de registros totales
    *
    * @return String
    */
    function FormaListaTabla($action,$request,$campos,$datos,$pagina,$conteo)
    {
      $html  = ThemeAbrirTabla("TABLA: ".strtoupper($request['nombre_tabla'])); 
      $html .= $this->CrearBuscador($campos,$request,$action);
      
      if(!empty($datos))
      {
        $html .= "<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
      
        foreach ($campos[$request['nombre_tabla']] as $fieldName) 
        {
          $html .= "    <td>".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
        }
        
        $html .= "    <td colspan=\"3\" width=\"1%\">Op</td>";
        $html .= "  </tr>\n";

        if (is_array($datos)) 
        {
          $clase = "modulo_list_claro";
          foreach ($datos as $key => $row) 
          {
            ($clase == "modulo_list_claro")? $clase = "modulo_list_oscuro": $clase = "modulo_list_claro";
            $req = "";
            $html .= "  <tr class=\"$clase\">\n";
            foreach ($campos[$request['nombre_tabla']] as $fieldName) 
            {
              if(array_key_exists($fieldName['name'],$request['eqivalencias']))
              {
                ($req == "")? $req .= "'".$row[$fieldName['name']]."'":$req .= ",'".$row[$fieldName['name']]."'";
              }
              $html .= "    <td>".$row[$fieldName['name']]."</td>\n";
            }
            
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a class=\"label_error\" title=\"SELECCXIONAR\" href=\"javascript:SeleccionarDatos($req)\">\n";
            $html .= "        <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "   </tr>\n";
          }
        }
        $html .= "</table><br>\n";
        
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
  			$html .= "		<br>\n";
      }
      else
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>\n";
      }
      if($action['volver'])
      {
        $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
        $html .= "  <table align=\"center\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "        <input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
      }
      
      $params = "";
      $valores = "";
      foreach($request['eqivalencias'] as $key => $valor)
      {
        ($params == "")? $params .= $key: $params .= ",".$key; 
        $valores .= "window.opener.document.".$request['forma'].".".$valor.".value =".$key.";\n"; 
      }
      
      $html .= "<script>\n";
      $html .= "  function SeleccionarDatos($params)\n";
      $html .= "  {\n";
      $html .= "    ".$valores."\n";
      $html .= "    window.close();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion que permite crear la forma de un buscador generico
    * @param array $campos Vector con los datos generales de la tabla
    * @param array $request Vector con los datos que llegan por request
    * @param array $action Vector con los datos de los links
    *
    * @return String
    */
    function CrearBuscador($campos,$request,$action)
    {
      $i = 0;
      $html  = "<form name=\"form\" action=\"".$action['paginador']."\" method=\"post\">";
      $html .= "  <table align=\"center\" width=\"50%\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "        <fieldset class=\"fieldset\"><legend>BUSCADOR AVANZADO\n";
      $html .= "          <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
      foreach ($campos[$request['nombre_tabla']] as $fieldName) 
      { 
        $chk = "";
        if($fieldName['type'] != "text" && $fieldName['null'] == "1" &&
            $fieldName['type'] != 'date' && $fieldName['type'] != 'timestamp without time zone')
        {
          if($i %2 == 0)   $html .= "            <tr class=\"formulacion_table_list\">\n";
            
          $html .= "              <td width=\"25%\" style=\"text-indent:8pt;text-align:left\">".ucfirst(str_replace("_"," ",$fieldName['name']))."</td>\n";
          $html .= "		          <td width=\"25%\" class=\"modulo_list_claro\" style=\"text-indent:8pt;text-align:left\">\n";
          $html .= "                ".$this->CrearCampos("editar",$fieldName['type'],"buscador[".$fieldName['name']."]",$fieldName['null'],$request['buscador'][$fieldName['name']],$fieldName['char_length'])."\n";
          $html .= "		          </td>\n";
            
          if($i%2 == 1) $html .= "            </tr>\n";
            
          $i++;
        }
  		}
      if($i%2 == 1) 
      {
        $html .= "              <td colspan=\"2\"></td>\n";
        $html .= "            </tr>\n";
      }
      $html .= "          </table>\n";
      $html .= "        </fieldset>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <table align=\"center\" width=\"60%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" width=\"50%\">\n";
      $html .= "        <input type=\"submit\" name=\"buscar\" value=\"Buscar\" class=\"input-submit\">\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" width=\"50%\">\n";
      $html .= "        <input type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" class=\"input-submit\" onclick=\"LimpiarCampos(document.form)\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
      $html .= "	function acceptInteger(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function LimpiarCampos(frm)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "</script>\n";
      return $html;
    }
  }
?>