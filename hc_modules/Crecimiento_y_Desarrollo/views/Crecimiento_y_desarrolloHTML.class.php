<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Crecimiento_y_desarrolloHTML.class.php,v 1.2 2010/02/05 21:40:49 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  /**
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  class Crecimiento_y_desarrolloHTML 
  {
    /**
    * Constructot de la clase
    */
    function Crecimiento_y_desarrolloHTML(){}
    
    /*
    *Funcion para inscribir un nuevo paciente al submodulo de historia clinica
    *@param array $action
    *@param array $datosPaciente:los datos del paciente
    *@param int $evolucion:trae la evolucion
    *@param array $datos:los datos del paciente
    *@param array $educacion:trae los niveles de educacion para los padres
    *@param array $ocupaciones:trae los tipos de ocupaciones de los padres
    *@param array $patologias:trae las patologias ingresadas en el modulo
    *@param String $mensaje: Mensaje para mostrar
    *
    * @return String
    */
    function FormaMostrarRegistro($action,$datosPaciente,$evolucion,$datos, $educacion,$ocupaciones,$patologias,$mensaje)
    {
      $sl = AutoCarga::factory("ClaseUtil");
      $html = ThemeAbrirTabla("CRECIMIENTO Y DESARROLLO");     
      $html.= $sl->AcceptNum();
      $html.= $sl->IsNumeric();
      $html.="<form name=\"inscripcion\" action=\"javascript:validarRegistro(document.inscripcion)\" id=\"inscripcion\"  method=\"post\">\n";
      $html.="<table class=\"modulo_table_list\" width=\"50%\" align=\"center\">\n";
      if($mensaje)
      {
        $html.="<center>\n";
        $html.="  <label class=\"label_error\">".$mensaje."</label>\n";
        $html.="</center>\n"; 
      }
      else
      {
      $html.="        <tr class=\"formulacion_table_list\">";
      $html.="            <td colspan=\"5\">INSCRIPCION PACIENTE</td>";
      $html.="        </tr>";     
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">ESTABLECIMIENTO DONDE NACIÓ</td>";
      $html.="      <td>\n";
      $html.="          <input type=\"text\" size=\"40%\" name=\"establecimiento_donde_nacio\" class=\"input-text\">\n";
      $html.="      </td>";    
      $html.="      <td class=\"formulacion_table_list\">EMBARAZO DESEADO</td>";    
      $html.="      <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"embarazo_deseado\" value=\"1\"></input>SI<input type=\"radio\" name=\"embarazo_deseado\" value=\"2\"></input>NO</td>";
      $html.="  </tr>"; 
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">EDAD GESTIONAL AL NACER EN SEMANAS</td>";
      $html.="      <td>\n";
      $html.="          <input type=\"text\" size=\"40%\" name=\"edad_gestional_semanas\" class=\"input-text\">\n";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">ATENCION PRENATAL</td>";    
      $html.="      <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"atencion_prenatal\" value=\"1\"></input>SI<input type=\"radio\" name=\"atencion_prenatal\" value=\"2\"></input>NO</td>";
      $html.="  </tr>"; 
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">TALLA</td>";
      $html.="      <td>\n";
      $html.="          <input type=\"text\" size=\"15%\" name=\"talla\" class=\"input-text\"> CM\n";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">PESO</td>";
      $html.="      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html.="          <input type=\"text\" size=\"15%\" name=\"peso\" class=\"input-text\"> KG\n";
      $html.="      </td>";
      $html.="  </tr>";
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">PER CEF</td>";
      $html.="      <td>\n";
      $html.="          <input type=\"text\" size=\"15%\" name=\"per_cef\" class=\"input-text\">\n";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">APGAR</td>";
      $html.="      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html.="          <input type=\"text\" size=\"15%\" name=\"apgar\" class=\"input-text\">\n";
      $html.="      </td>";
      $html.="  </tr>";
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">CANTIDAD DE HERMANOS</td>\n";
      $html.="      <td class=\"modulo_list_claro\">";
      $html.="          <select name=\"cantidad_hermanos\" class=\"select\">";
      $html.="              <option value='0'>--Seleccionar--</option>";
      for($i = 1; $i <= 5; $i++)
         $html.="           <option value=\"$i\">".$i."</option>";
      $html.="          </select>";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">LUGAR QUE OCUPA ENTRE LOS HERMANOS</td>\n";
      $html.="      <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"sw_ubicacion\" value=\"1\"></input>MAYOR<input type=\"radio\" name=\"sw_ubicacion\" value=\"2\"></input>MEDIO<input type=\"radio\" name=\"sw_ubicacion\" value=\"3\"></input>MENOR</td>";
      $html.="   </tr>";
      $html.="   <tr class=\"formulacion_table_list\">";
      $html.="      <td colspan=\"5\">INSCRIPCION PARIENTES</td>";
      $html.="   </tr>"; 
      $html.="   <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">NOMBRE DE LA MADRE</td>";
      $html.="      <td>\n";
      $html.="          <input type=\"text\" size=\"40%\" name=\"nombre_madre\" class=\"input-text\">\n";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">EDAD</td>\n";
      $html.="      <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="          <select name=\"edad_madre\" class=\"select\">";
      $html.="              <option value='0'>--Seleccionar--</option>";
      for($i = 1; $i <= 100; $i++)
         $html.="           <option value=\"$i\">".$i."</option>";
      $html.="          </select>";
      $html.="       </td>";
      $html.="   </tr>";
      $html.="   <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">EDUCACION</td>\n";
      $html.="      <td class=\"modulo_list_claro\">";
      $html.="          <select name=\"educacion_madre\" class=\"select\">";
      $html.="              <option value='-1'>--Seleccionar--</option>";
      foreach($educacion as $key => $detalle)
      {
        ($datos['educacion_madre'] == $detalle['tipo_educacion_id'])? $slt= "selected":$slt = "";
        $html.="            <option value='".$detalle["tipo_educacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="          </select>";
      $html.="       </td>";
      $html.="       <td class=\"formulacion_table_list\">OCUPACION</td>\n";
      $html.="       <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="          <select name=\"ocupacion_madre\" class=\"select\">";
      $html.="              <option value='-1'>--Seleccionar--</option>";
      foreach($ocupaciones as $key => $detalle)
      {
        ($datos['ocupacion_madre'] == $detalle['tipo_ocupacion_id'])? $slt= "selected":$slt = "";
        $html.="            <option value='".$detalle["tipo_ocupacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="          </select>";
      $html.="       </td>"; 
      $html.="    </tr>"; 
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">NOMBRE DEL PADRE</td>";
      $html.="      <td>\n";
      $html.="          <input type=\"text\" size=\"40%\" name=\"nombre_padre\" class=\"input-text\">\n";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">EDAD</td>\n";
      $html.="      <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="          <select name=\"edad_padre\" class=\"select\">";
      $html.="              <option value='0'>--Seleccionar--</option>";
      for($i = 1; $i <= 100; $i++)
         $html.="           <option value=\"$i\">".$i."</option>";
      $html.="          </select>";
      $html.="      </td>";
      $html.="  </tr>";
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">EDUCACION</td>\n";
      $html.="      <td class=\"modulo_list_claro\">";
      $html.="          <select name=\"educacion_padre\" class=\"select\">";
      $html.="              <option value='-1'>--Seleccionar--</option>";
      foreach($educacion as $key => $detalle)
      {
        ($datos['educacion_padre'] == $detalle['tipo_educacion_id'])? $slt= "selected":$slt = "";
        $html.="            <option value='".$detalle["tipo_educacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="          </select>";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">OCUPACION</td>\n";
      $html.="      <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="          <select name=\"ocupacion_padre\" class=\"select\">";
      $html.="              <option value='-1'>--Seleccionar--</option>";
      foreach($ocupaciones as $key => $detalle)
      {
        ($datos['ocupacion_padre'] == $detalle['tipo_ocupacion_id'])? $slt= "selected":$slt = "";
        $html.="            <option value='".$detalle["tipo_ocupacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="          </select>";
      $html.="      </td>";
      $html.="  </tr>"; 
      $html.="  <tr class=\"formulacion_table_list\">";
      $html.="       <td colspan=\"5\">ASPECTOS DE LA VIVIENDA</td>";
      $html.="  </tr>";
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">TIPO DE VIVIENDA</td>\n";
      $html.="      <td class=\"modulo_list_claro\">";
      $html.="          <select name=\"descripcion\" class=\"select\">";
      $html.="              <option value='0'>--Seleccionar--</option>";
      $html.="              <option value='1'>CASA</option>";
      $html.="              <option value='2'>APARTAMNETO</option>";
      $html.="              <option value='3'>PIEZA</option>";
      $html.="              <option value='4'>FINCA</option>";
      $html.="           </select>";
      $html.="      </td>";
      $html.="      <td class=\"formulacion_table_list\">POSEE ENERGIA</td>";    
      $html.="      <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"energia\" value=\"1\"></input>SI<input type=\"radio\" name=\"energia\" value=\"2\"></input>NO</td>";
      $html.="  </tr>";
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">POSEE ACUEDUCTO</td>";    
      $html.="      <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"acueducto\" value=\"1\"></input>SI<input type=\"radio\" name=\"acueducto\" value=\"2\"></input>NO</td>";
      $html.="      <td class=\"formulacion_table_list\">POSEE ALCANTARILLADO</td>";    
      $html.="      <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"alcantarillado\" value=\"1\">SI</input><input type=\"radio\" name=\"alcantarillado\" value=\"2\"></input>NO</td>";
      $html.="  </tr>"; 
      $html.="  <tr class=\"modulo_list_claro\">";
      $html.="      <td class=\"formulacion_table_list\">CONVIVE CON ANIMALES</td>";    
      $html.="      <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"animales\" value=\"1\"></input>SI<input type=\"radio\" name=\"animales\" value=\"2\"></input>NO</td>";
      $html.="      <td class=\"formulacion_table_list\">ESPECIFIQUE CUAL(ES)</td>";
      $html.="      <td class=\"modulo_list_claro\">\n";
      $html.="          <input type=\"text\" size=\"30%\" name=\"descripcion_animales\" class=\"input-text\">\n";
      $html.="      </td>";
      $html.="  </tr>";   
      $html.="      <tr class=\"formulacion_table_list\">";
      $html.="          <td colspan=\"4\">PATOLOGIAS</td>";
      $html.="      </tr>";
      $html.="      <tr class=\"modulo_list_claro\">";
      $html.="          <td>DESCRIPCION</td>";
      $html.="          <td>ESTADO</td>";
      //$html.="          <td style=\"width:15%\">NO</td>";
      $html.="          <td colspan=\"2\">OBSERVACIONES</td>";
      $html.="      </tr>";
      foreach($patologias as $key => $detalle)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
        $html.="    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html.="        <td>".$detalle['descripcion']."</td>";
        $html.="        <td><input type=\"radio\" name=\"estado[".$key."]\" value=\"1\"></input>SI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"estado[".$key."]\" value=\"2\"></input>NO</td>";
        //$html.="        <td><input type=\"radio\" name=\"estado[".$key."]\" value=\"2\"></td>";
        $html.="        <td colspan=\"2\"><input type=\"text\" size=\"40%\" name=\"observacion[".$key."]\" class=\"input-text\">\n";
        $html.="    </tr>";
      }
      $html.="  <tr class=\"formulacion_table_list\"><td colspan=\"5\">";
      $html.="      <input type=\"submit\" name=\"aceptar\" value=\"Aceptar\" class=\"input-submit\">";  
      $html.="  </tr>";
      }
			$html.="</table>\n";
			$html.="</form>\n";
            
      $html.="<center><div id =\"error\" class=\"label_error\"></div></center>";        
      $html.="<script>\n";
      $html.=$script;
      $html.="    function validarRegistro(objeto)\n";
      $html.="    {\n";
      $html.="        if(objeto.establecimiento_donde_nacio.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NOMBRE DEL ESTABLECIMIENTO DONDE NACIO EL NIÑO';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(!objeto.embarazo_deseado[0].checked && !objeto.embarazo_deseado[1].checked)\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL EMBARAZO DESEADO ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="        if(objeto.edad_gestional_semanas.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR LA EDAD GESTIONAL DEL NIÑO AL NACER';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(!objeto.atencion_prenatal[0].checked && !objeto.atencion_prenatal[1].checked)\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA ATENCION PRENATAL ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="        if(objeto.talla.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR LA TALLA DEL NIÑO';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.peso.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR EL PESO DEL NIÑO';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.per_cef.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR EL PER_CEF';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.apgar.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR EL APGAR';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.nombre_madre.value == \"\" && objeto.nombre_padre.value == \"\")";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NOMBRE DE LA MADRE O DEL PADRE';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.edad_madre.value == '0' && objeto.edad_padre.value == '0')";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'FALTAN VALORES DE LA EDAD POR INGRESAR';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.educacion_madre.value == '-1' && objeto.educacion_padre.value == '-1')";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'FALTAN VALORES DEL NIVEL DE EDUCACION POR INGRESAR';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.ocupacion_madre.value == '-1' && objeto.ocupacion_padre.value == '-1')";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'FALTAN VALORES DEL TIPO DE OCUPACION POR INGRESAR';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(objeto.descripcion.value == '0')";
      $html.="        {";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL TIPO DE VIVIENDA';\n";
      $html.="            return;";
      $html.="        }";
      $html.="        if(!objeto.energia[0].checked && !objeto.energia[1].checked)\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR SI POSEE ENERGIA ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="        if(!objeto.acueducto[0].checked && !objeto.acueducto[1].checked)\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR POSEE ACUEDUCTO ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="        if(!objeto.alcantarillado[0].checked && !objeto.alcantarillado[1].checked)\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR SI POSEE ALCANTARILLADO ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="        if(!objeto.animales[0].checked && !objeto.animales[1].checked)\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR SI CONVIVE CON ANIMALES ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="        if(objeto.animales[0].checked && objeto.descripcion_animales.value == \"\")\n";
      $html.="        {\n";
      $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR CON QUE TIPO DE ANIMALES CONVIVE ';\n";
      $html.="            return;";
      $html.="        }\n";
      $html.="           objeto.action=\"".$action["inscripcion"]."\";\n";
      $html.="           objeto.submit();";      
      $html.="     }";   
      $html.="</script>\n";
      
      $html.= ThemeCerrarTabla();
      return $html;
    }
    
    /*
    *Funcion que maneja el control del paciente del submodulo de historia clinica
    *@param array $action
    *@param array $datosPaciente:los datos del paciente
    *@param int $evolucion:trae la evolucion
    *@param array $datos:los datos del paciente
    *@param array $educacion:trae los niveles de educacion para los padres
    *@param array $ocupaciones:trae los tipos de ocupaciones de los padres
    *@param array $patologias:trae las patologias ingresadas en el modulo
    *@param array $ex_fisica:trae los datos de la exploracion fisica o las partes del cuerpo
    *@param array $cavidad_oral:trae las cavidades orales que existen
    *@param array $alimentos:trae los tipos de alimentos que existen
    *@param array $edad:trae el intervalo de edad al cual pertenece el paciente
    *
    * @return String
    */
    function FormaMostrarVentanas($action,$datosPaciente,$evolucion,$datos, $educacion,$ocupaciones,$patologias,$ex_fisica,$cavidad_oral,$alimentos,$edad,$puntaje)
    { 
      $mostrarOcultar='0';
      $html =ThemeAbrirTabla("CRECIMIENTO Y DESARROLLO");
      $html.="<table width=\"90%\" align=\"center\">\n";
			$html.="    <tr>\n";
			$html.="        <td>\n";     
      $html.="		        <div class=\"tab-pane\" id=\"Crecimiento\">\n";
      $html.="              <script>	tabPane = new WebFXTabPane( document.getElementById( \"Crecimiento\" ), false ); </script>\n";
      $html.="			        <div class=\"tab-page\" id=\"Control\">\n";
      $html.="				        <h2 class=\"tab\">CONTROL</h2>\n";
      $html.="					      <script>	tabPane.addTabPage( document.getElementById(\"Control\")); </script>\n";   
      $html.="                <form name=\"control_paciente\" action=\"javascript:validarControl(document.control_paciente)\" id=\"control_paciente\"  method=\"post\">\n";     
      $html.="                  <input type=\"hidden\" name=\"control_edad_id\"  value=\"".$edad['control_edad_id']."\">\n";
      $html.="                  <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"10\">EXPLORACION FISICA</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td style=\"width:30%\">DESCRIPCION</td>";
      $html.="                          <td style=\"width:15%\">NORMAL</td>";
      $html.="                          <td style=\"width:15%\">ANORMAL</td>";
      $html.="                          <td style=\"width:40%\">OBSERVACIONES</td>";
      $html.="                      </tr>";
      foreach($ex_fisica as $key => $detalle)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
        $html.="                    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html.="                        <td>".$detalle['division_cuerpo_descripcion']."</td>";
        $html.="                        <td><input type=\"radio\" name=\"estado_f[".$key."]\" value=\"1\"></td>";
        $html.="                        <td><input type=\"radio\" name=\"estado_f[".$key."]\" value=\"2\"></td>";
        $html.="                        <td><input type=\"text\" size=\"40%\" name=\"observacion[".$key."]\" class=\"input-text\">\n";
        $html.="                    </tr>";
      }
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"10\">DATOS DE CAVIDAD ORAL</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td>DESCRIPCION</td>";
      $html.="                          <td>NORMAL</td>";
      $html.="                          <td>ANORMAL</td>";
      $html.="                          <td>OBSERVACIONES</td>";
      $html.="                      </tr>";
      foreach($cavidad_oral as $key => $detalle)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
        $html.="                    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html.="                        <td>".$detalle['descripcion']."</td>";
        $html.="                        <td><input type=\"radio\" name=\"estado_c[".$key."]\" value=\"1\"></td>";
        $html.="                        <td><input type=\"radio\" name=\"estado_c[".$key."]\" value=\"2\"></td>";
        $html.="                        <td><input type=\"text\" size=\"40%\" name=\"observacion[".$key."]\" class=\"input-text\">\n";
        $html.="                    </tr>";
      }
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"4\">ASPECTOS ALIMENTICIOS</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td>DESCRIPCION</td>";
      $html.="                      </tr>";
      foreach($alimentos as $key => $detalle)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
        $html.="                    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html.="                        <td>".$detalle['descripcion']."</td>";
        if($detalle['tipo_campo'] == '1')
        {
          $html.="                      <td colspan=\"3\"><input type=\"text\" size=\"20%\" name=\"estado_a[".$key."]\" class=\"input-text\">\n";
        }    
        else
        {
          $html.="                      <td><input type=\"radio\" name=\"estado_a[".$key."]\" value=\"1\">SI</td>";
          $html.="                      <td colspan=\"3\"><input type=\"radio\" name=\"estado_a[".$key."]\" value=\"2\">NO</td>";
        }
        $html.="                    </tr>";
      }
      $html.="                      <tr class=\"formulacion_table_list\"><td colspan=\"5\">";
      $html.="                          <input type=\"submit\" name=\"aceptar\" value=\"Aceptar\" class=\"modulo_table_list\">";  
      $html.="                      </tr>";
      $html.="                  </table>\n";
      $html.="              </form>\n";   
      $html.="            </div>";  
      $html.="			      <div class=\"tab-page\" id=\"Inscripcion\">\n";
      $html.="				      <h2 class=\"tab\">DATOS DEL PACIENTE</h2>\n";  
      $html.="					    <script>	tabPane.addTabPage( document.getElementById(\"Inscripcion\")); </script>\n";
      $html.="                <form name=\"inscripcion\" action=\"javascript:validarRegistro(document.inscripcion)\" id=\"inscripcion\"  method=\"post\">\n";
      $html.="                  <table class=\"modulo_table_list\" width=\"50%\" align=\"center\">\n";
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"5\">INSCRIPCION PACIENTE</td>";
      $html.="                      </tr>";     
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">ESTABLECIMIENTO DONDE NACIÓ</td>";
      $html.="                          <td>\n";
      $html.="                              <input type=\"text\" size=\"40%\" name=\"establecimiento_donde_nacio\" class=\"input-text\" value=\"".$datos['establecimiento_donde_nacio']."\">\n";
      $html.="                          </td>"; 
      $html.="                          <td class=\"formulacion_table_list\">EMBARAZO DESEADO</td>"; 
      if($datos['embarazo_deseado'] == '1')
      {
        $chk1="checked";
        $chk2="";
      }
      else
      {
        $chk1="";
        $chk2="checked";
      }
      $html.="                          <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"embarazo_deseado\" value=\"1\" ".$chk1."></input>SI<input type=\"radio\" name=\"embarazo_deseado\" value=\"2\" ".$chk2."></input>NO</td>";
      $html.="                      </tr>"; 
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">EDAD GESTIONAL AL NACER EN SEMANAS</td>";
      $html.="                          <td>\n";
      $html.="                              <input type=\"text\" size=\"40%\" name=\"edad_gestional_semanas\" class=\"input-text\" value=\"".$datos['edad_gestional_semanas']."\">\n";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">ATENCION PRENATAL</td>";    
      if($datos['atencion_prenatal'] == '1')
      {
        $chk3="checked";
        $chk4="";
      }
      else
      {
        $chk3="";
        $chk4="checked";
      }
      $html.="                          <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"atencion_prenatal\" value=\"1\" ".$chk3."></input>SI<input type=\"radio\" name=\"atencion_prenatal\" value=\"2\" ".$chk4."></input>NO</td>";
      $html.="                      </tr>"; 
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">TALLA</td>";
      $html.="                          <td>\n";
      $html.="                              <input type=\"text\" size=\"15%\" name=\"talla\" class=\"input-text\" value=\"".$datos['talla']."\"> CM\n";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">PESO</td>";
      $html.="                          <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html.="                              <input type=\"text\" size=\"15%\" name=\"peso\" class=\"input-text\" value=\"".$datos['peso']."\"> KG\n";
      $html.="                          </td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">PER CEF</td>";
      $html.="                          <td>\n";
      $html.="                              <input type=\"text\" size=\"15%\" name=\"per_cef\" class=\"input-text\" value=\"".$datos['per_cef']."\">\n";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">APGAR</td>";
      $html.="                          <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html.="                              <input type=\"text\" size=\"15%\" name=\"apgar\" class=\"input-text\" value=\"".$datos['apgar']."\">\n";
      $html.="                          </td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">CANTIDAD DE HERMANOS</td>\n";
      $html.="                          <td class=\"modulo_list_claro\">";
      $html.="                              <select name=\"cantidad_hermanos\" class=\"select\">";
      $html.="                                  <option value='0'>--Seleccionar--</option>";
      for($i = 1; $i <= 5; $i++)
         $html.="                               <option value=\"$i\">".$i."</option>";
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">LUGAR QUE OCUPA ENTRE LOS HERMANOS</td>\n";
      $html.="                          <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"sw_ubicacion\" value=\"1\"></input>MAYOR<input type=\"radio\" name=\"sw_ubicacion\" value=\"2\"></input>MEDIO<input type=\"radio\" name=\"sw_ubicacion\" value=\"3\"></input>MENOR</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"5\">INSCRIPCION PARIENTES</td>";
      $html.="                      </tr>"; 
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">NOMBRE DE LA MADRE</td>";
      $html.="                          <td>\n";
      $html.="                              <input type=\"text\" size=\"40%\" name=\"nombre_madre\" class=\"input-text\" value=\"".$datos['nombre_madre']."\">\n";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">EDAD</td>\n";
      $html.="                          <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="                              <select name=\"edad_madre\" class=\"select\">";
      $html.="                                  <option value='0'>--Seleccionar--</option>";
      for($i = 1; $i <= 100; $i++)
      {
         ($datos['edad_madre'] == $i)? $slt= "selected":$slt = "";
         $html.="                               <option value=\"$i\" ".$slt.">".$i."</option>";
      }   
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">EDUCACION</td>\n";
      $html.="                          <td class=\"modulo_list_claro\">";
      $html.="                              <select name=\"educacion_madre\" class=\"select\">";
      $html.="                                  <option value='-1'>--Seleccionar--</option>";
      foreach($educacion as $key => $detalle)
      {
        ($datos['educacion_madre'] == $detalle['tipo_educacion_id'])? $slt= "selected":$slt = "";
        $html.="                                <option value='".$detalle["tipo_educacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">OCUPACION</td>\n";
      $html.="                          <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="                              <select name=\"ocupacion_madre\" class=\"select\">";
      $html.="                                  <option value='-1'>--Seleccionar--</option>";
      foreach($ocupaciones as $key => $detalle)
      {
        ($datos['ocupacion_madre'] == $detalle['tipo_ocupacion_id'])? $slt= "selected":$slt = "";
        $html.="                                <option value='".$detalle["tipo_ocupacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="                              </select>";
      $html.="                          </td>"; 
      $html.="                      </tr>"; 
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">NOMBRE DEL PADRE</td>";
      $html.="                          <td>\n";
      $html.="                              <input type=\"text\" size=\"40%\" name=\"nombre_padre\" class=\"input-text\" value=\"".$datos['nombre_padre']."\">\n";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">EDAD</td>\n";
      $html.="                          <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="                              <select name=\"edad_padre\" class=\"select\">";
      $html.="                                  <option value='0'>--Seleccionar--</option>";
      for($i = 1; $i <= 100; $i++)
         $html.="                               <option value=\"$i\">".$i."</option>";
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">EDUCACION</td>\n";
      $html.="                          <td class=\"modulo_list_claro\">";
      $html.="                              <select name=\"educacion_padre\" class=\"select\">";
      $html.="                                  <option value='-1'>--Seleccionar--</option>";
      foreach($educacion as $key => $detalle)
      {
        ($datos['educacion_padre'] == $detalle['tipo_educacion_id'])? $slt= "selected":$slt = "";
        $html.="                                <option value='".$detalle["tipo_educacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">OCUPACION</td>\n";
      $html.="                          <td class=\"modulo_list_claro\" colspan=\"2\">";
      $html.="                              <select name=\"ocupacion_padre\" class=\"select\">";
      $html.="                                  <option value='-1'>--Seleccionar--</option>";
      foreach($ocupaciones as $key => $detalle)
      {
        ($datos['ocupacion_padre'] == $detalle['tipo_ocupacion_id'])? $slt= "selected":$slt = "";
        $html.="                                <option value='".$detalle["tipo_ocupacion_id"]."' $slt>".$detalle["descripcion"]."</option>";
      }
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                      </tr>"; 
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"5\">ASPECTOS DE LA VIVIENDA</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">TIPO DE VIVIENDA</td>\n";
      $html.="                          <td class=\"modulo_list_claro\">";
      $html.="                              <select name=\"descripcion\" class=\"select\">";
      $html.="                                  <option value='0'>--Seleccionar--</option>";
      $html.="                                  <option value='1'>CASA</option>";
      $html.="                                  <option value='2'>APARTAMNETO</option>";
      $html.="                                  <option value='3'>PIEZA</option>";
      $html.="                                  <option value='4'>FINCA</option>";
      $html.="                              </select>";
      $html.="                          </td>";
      $html.="                          <td class=\"formulacion_table_list\">POSEE ENERGIA</td>"; 
      if($datos['energia'] == '1')
      {
        $chk5="checked";
        $chk6="";
      }
      else
      {
        $chk5="";
        $chk6="checked";
      }      
      $html.="                          <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"energia\" value=\"1\" ".$chk5."></input>SI<input type=\"radio\" name=\"energia\" value=\"2\" ".$chk6."></input>NO</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">POSEE ACUEDUCTO</td>"; 
      if($datos['acueducto'] == '1')
      {
        $chk7="checked";
        $chk8="";
      }
      else
      {
        $chk7="";
        $chk8="checked";
      }                  
      $html.="                          <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"acueducto\" value=\"1\" ".$chk7."></input>SI<input type=\"radio\" name=\"acueducto\" value=\"2\" ".$chk8."></input>NO</td>";
      $html.="                          <td class=\"formulacion_table_list\">POSEE ALCANTARILLADO</td>";  
      if($datos['alcantarillado'] == '1')
      {
        $chk9="checked";
        $chk10="";
      }
      else
      {
        $chk9="";
        $chk10="checked";
      }            
      $html.="                          <td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"radio\" name=\"alcantarillado\" value=\"1\" ".$chk9.">SI</input><input type=\"radio\" name=\"alcantarillado\" value=\"2\" ".$chk10."></input>NO</td>";
      $html.="                      </tr>"; 
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td class=\"formulacion_table_list\">CONVIVE CON ANIMALES</td>";  
      if($datos['animales'] == '1')
      {
        $chk11="checked";
        $chk12="";
      }
      else
      {
        $chk11="";
        $chk12="checked";
      }       
      $html.="                          <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"animales\" value=\"1\" ".$chk11."></input>SI<input type=\"radio\" name=\"animales\" value=\"2\" ".$chk12."></input>NO</td>";
      $html.="                          <td class=\"formulacion_table_list\">ESPECIFIQUE CUAL(ES)</td>";
      $html.="                          <td class=\"modulo_list_claro\">\n";
      $html.="                              <input type=\"text\" size=\"30%\" name=\"descripcion_animales\" class=\"input-text\" value=\"".$datos['descripcion_animales']."\">\n";
      $html.="                          </td>";
      $html.="                      </tr>";   
      $html.="                      <tr class=\"formulacion_table_list\">";
      $html.="                          <td colspan=\"4\">PATOLOGIAS</td>";
      $html.="                      </tr>";
      $html.="                      <tr class=\"modulo_list_claro\">";
      $html.="                          <td>DESCRIPCION</td>";
      $html.="                          <td>ESTADO</td>";
      //$html.="          <td style=\"width:15%\">NO</td>";
      $html.="                          <td colspan=\"2\">OBSERVACIONES</td>";
      $html.="                      </tr>";
      foreach($patologias as $key => $detalle)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
        $html.="                    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html.="                        <td>".$detalle['descripcion']."</td>";
        if($datos['estado'][".$key."] == '1')
        {
            $chk13="checked";
            $chk14="";
        }
        else
        {
            $chk13="";
            $chk14="checked";
        } 
        $html.="                        <td><input type=\"radio\" name=\"estado[".$key."]\" value=\"1\" ".$chk13."></input>SI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"estado[".$key."]\" value=\"2\" ".$chk14."></input>NO</td>";
        //$html.="        <td><input type=\"radio\" name=\"estado[".$key."]\" value=\"2\"></td>";
        $html.="                        <td colspan=\"2\"><input type=\"text\" size=\"40%\" name=\"observacion[".$key."]\" class=\"input-text\" value=\"".$datos['observacion'][".$key."]."\">\n";
        $html.="                    </tr>";
      }
      $html.="                      <tr class=\"formulacion_table_list\"><td colspan=\"5\">";
      $html.="                          <input type=\"submit\" name=\"aceptar\" value=\"Modificar Datos\" class=\"input-submit\">";  
      $html.="                      </tr>";
			$html.="                </table>\n";
			$html.="              </form>\n";
      $html.="            </div>";
      
      if($puntaje)
      {
        $html.="			      <div class=\"tab-page\" id=\"Control\">\n";
        $html.="				      <h2 class=\"tab\">ESCALAS ABREVIADAS DEL DESARROLLO</h2>\n";
        $html.="					    <script>	tabPane.addTabPage( document.getElementById(\"Control\")); </script>\n";   
        foreach($puntaje as $key => $detalle)
        {
          $html.="                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
          $html.="                    <tr class=\"formulacion_table_list\">";
          $html.="                       <td colspan=\"10\">ESCALAS</td>";
          $html.="                    </tr>";   
          $html.="      <tr class=\"formulacion_table_list\">";
          $html.="        <td colspan=\"6\">\n";
          $html.="          <input type=\"hidden\" name=\"nombre_escala[".$key."]\"  value=\"".$detalle[$key]['descripcion']."\">".$detalle[$key]['descripcion']."</td>";
          $html.="      </tr>";
          $html.="      <tr class=\"modulo_list_claro\">";
          $html.="          <td style=\"width:20%\">RANGO DE EDAD</td>";
          $html.="          <td style=\"width:10%\">ITEM</td>";
          $html.="          <td style=\"width:50%\">DESCRIPCION</td>";
          $html.="      </tr>";
          foreach($detalle as $key2 => $dtl)
          {
            ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
            ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
            $html.="    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n"; 
            $html.="        <td>".$dtl['edad_minima']." A ".$dtl['edad_maxima']."</td>";
            $html.="        <td>".$dtl['item']."</td>";
            $html.="        <td>".$dtl['descripcion_item']."</td>";
            $html.="        <td>".$dtl['puntaje_total']."</td>";
            $html.="    </tr>";
          }
        }
      }
      $html.="                </table>\n";
      $html.="            </div>";
      
      $html.="          </div>";
      $html.="      </td>";  
      $html.="      <tr class=\"modulo_list_claro\">";
      $html.="        <td colspan=\"8\">\n";
      $html.="          <div id=\"mensaje\"></div>\n";
      $html.="        </td>\n";
      $html.="      </tr>";
      $html.="   </tr>";
      $html.="</table>";
   
      $html.="<center><div id =\"error\" class=\"label_error\"></div></center>";        
      $html.="<script>\n";
      $html.= $script;
      $html.="    function validarControl(objeto)\n";
      $html.="    {\n";
      $html.="           objeto.action=\"".$action["guardarControl"]."\";\n";
      $html.="           objeto.submit();";      
      $html.="    }";   
      $html.="</script>\n"; 
      //$html.="<pre>".print_r($edad,true)."</pre>";
      $html.= ThemeCerrarTabla();
      return $html;
    }
    
    /*
    *Funcion que muestra las escalas abreviadas del desarrollo y me permite seleccionar para calcular el puntaje
    *@param array $action
    *@param array $datosPaciente:los datos del paciente
    *@param int $evolucion:trae la evolucion
    *@param array $traerPuntajes: trae las escalas de acuerdo a la edad del paciente
    *
    * @return String
    */
    function FormaMostrarPuntajes($action,$datosPaciente,$evolucion,$traerPuntajes)
    { 
      $html =ThemeAbrirTabla("CRECIMIENTO Y DESARROLLO");
      $html.="<form name=\"puntaje\" id=\"puntaje\" action=\"javascript:validarPuntaje(document.puntaje)\" id=\"puntaje\"  method=\"post\">\n";
      $html.="  <input type=\"hidden\" name=\"seccion_escala_id\"  value=\"".$traerPuntajes['seccion_escala_id']."\">\n";
      $html.="  <input type=\"hidden\" name=\"evolucion\"  value=\"".$evolucion['evolucion_id']."\">\n";
      if($traerPuntajes)
      {     
        foreach($traerPuntajes as $key => $detalle)
        {
          $html.="  <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
          $html.="      <tr class=\"formulacion_table_list\">";
          $html.="        <td colspan=\"6\">\n";
          $html.="          <input type=\"hidden\" name=\"nombre_escala[".$key."]\"  value=\"".$detalle[$key]['descripcion']."\">".$detalle[$key]['descripcion']."</td>";
          $html.="      </tr>";
          $html.="      <tr class=\"modulo_list_claro\">";
          $html.="          <td style=\"width:20%\">RANGO DE EDAD</td>";
          $html.="          <td style=\"width:10%\">ITEM</td>";
          $html.="          <td style=\"width:50%\">DESCRIPCION</td>";
          $html.="          <td style=\"width:20%\">SELECCIONAR</td>";
          $html.="      </tr>";
          foreach($detalle as $key2 => $dtl)
          {
            ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
            ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
            $html.="    <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n"; 
            $html.="        <td>".$dtl['edad_minima']." A ".$dtl['edad_maxima']."</td>";
            $html.="        <td>".$dtl['item']."</td>";
            $html.="        <td>".$dtl['descripcion_item']."</td>";
            $html.="        <td><input type=\"checkbox\" name=\"escalas[".$key."][".$dtl['item']."]\"  value=\"".$dtl['item']."\"></td>";
            $html.="    </tr>";
          }
          $html .= "  </table><br>\n";
        }
      }
      $html.="  <table align=\"center\">\n";
      $html.="    <tr class=\"formulacion_table_list\">";
      $html.="      <td>";
      $html.="        <input type=\"button\" name=\"calcular_puntaje\" value=\"Calcular Puntaje\" class=\"input-submit\" onclick=\"xajax_MostrarPuntaje(xajax.getFormValues('puntaje'))\">";  
      $html.="      </td>";
      $html.="    </tr>";
      $html.="    <tr class=\"modulo_list_claro\">";
      $html.="      <td>\n";
      $html.="         <div id=\"mensaje\"></div>\n";
      $html.="      </td>\n";
      $html.="    </tr>";
      $html.="  </table>\n";
      $html.="</form>\n";
      
      $html.="<script>";
      $html.="    function validarPuntaje(objeto)\n";
      $html.="    {\n";
      $html.="        xajax_MostrarPuntaje(xajax.getFormValues('puntaje'));\n";
      $html.="        objeto.action = \"".$action["calcular_puntaje"]."\";\n";
      $html.="        objeto.submit();";
      $html.="    }";
      $html.="</script>";
      $html.= $this->CrearVentana(300,150);
      $html.= ThemeCerrarTabla();
      return $html;
    }
    
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño en x que tendra la ventana
    * @param int $tmny Tamaño en y que tendra la ventana
    * @param int $contenido Contenido a mostrar en la ventana
    *
    * @return string
		*/
    function CrearVentana($tmn = 370, $tmny = "'auto'",$contenido)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
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
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";
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
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	  <form name=\"puntaje\" id=\"puntaje\" action=\"javascript:xajax_MostrarPuntaje()\" method=\"post\">\n";
			$html .= "	    <div id=\"calcular_puntaje\">".$contenido."</div>";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}  
    
    /**
    * Crea una forma, para mostrar mensajes informativos con un solo boton
    * @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *  en pantalla
    * @return string
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
  }
?>