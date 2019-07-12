<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultaAfiliadoHTML.class.php,v 1.7 2007/11/08 22:53:48 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: ConsultaAfiliadoHTML 
  * Clase contiene metodos para la consulta de afiliados del sistema
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

	class AccidentesdeTrabajoHTML
	{
		/**
		* Constructor de la clase
		*/
		function AccidentesdeTrabajoHTML(){}
		/**
	    * @param array $action Vector de links de la aplicaion
		* @param array $partes_del_cuerpo_afectado 
		* @param array $tipos_lesion
        * @param array $Agentes_Accidentes
        * @param array $Formas_Accidente
        * @param array $tipos_Accidente.
        * @return String $html con la forma para diligenciar un accidente de trabajo.
		*/
		function FormaRegistroAccidenteTrabajo($action,$partes_del_cuerpo_afectado,$tipos_lesion,$Agentes_Accidentes,$Formas_Accidente,$tipos_Accidente,$departamentos,$sitios_accidente,$espacios,$Tipo_id_terceros)
		{

            $path=SessionGetVar("rutaImagenes");
            $html  = ThemeAbrirTabla('REGISTRO DE ACCIDENTES DE TRABAJO');
            $html .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= "               <div align='center' id=\"tipos_de_riesgox\">";
            $html .= "                 <form name=\"accidente_trabajo\" id=\"accidente_trabajo\" action=\"#\" method=\"post\">\n";
            $html .= "                   <table class=\"modulo_table_list\" width=\"100%\" align=\"center\"  >\n";
            $html .= "                     <tr>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" width='10%' align=\"center\">\n";
            $html .= "                        FECHA DEL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                       <td  width=\"17%\" align=\"left\" class=\"modulo_list_claro\"> \n";
            $html .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha_acc\" id=\"fecha_acc\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
            $html .= "                           <sub>".ReturnOpenCalendario("accidente_trabajo","fecha_acc","-")."</sub>";
            $html .= "                       </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" width='10%' align=\"center\">\n";
            $html .= "                        HORA DEL ACCIDENTE (0-23 HRS)";
            $html .= "                     </td>\n";
            $html .= "                     <td width='26%' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                     HH <select class=\"select\" name=\"hora\" id=\"hora\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">-</option> \n";
            for($i=0;$i<25;$i++)
            {
                $html .= "                       <option value=\"".$i."\">".$i."</option> \n";
            }
            $html .= "                         </select>\n";

            $html .= "                      MM <select class=\"select\" name=\"min\" id=\"min\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">-</option> \n";
            for($i=0;$i<60;$i++)
            {
                $html .= "                       <option value=\"".$i."\">".$i."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                      SS <select class=\"select\" name=\"seg\" id=\"seg\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">-</option> \n";
            for($i=0;$i<60;$i++)
            {
                $html .= "                       <option value=\"".$i."\">".$i."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                      </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" width='17%' align=\"center\">\n";
            $html .= "                        JORNADA EN LA QUE SUCEDI&#211; EL ACCIDENTE ?";
            $html .= "                     </td>\n";
            $html .= "                     <td  width=\"10%\" class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                       (1) NORMAL  <input type=\"radio\" name=\"jornada\" id=\"jor\" value=\"1\"><br>";
            $html .= "                       (2) EXTRA  <input type=\"radio\" name=\"jornada\" id=\"jor\"  value=\"0\">";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        ESTABA REALIZANDO SU TRABAJO HABITUAL ?";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                       SI  <input type=\"radio\" name=\"trabajo_habitual\" id=\"th\" onclick='Evaluar(this.value)' value=\"0\">";
            $html .= "                       NO  <input type=\"radio\" name=\"trabajo_habitual\" id=\"th\" onclick='Evaluar(this.value)' value=\"1\">";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        CUAL?   DILIGENCIAR SOLO EN CASO NEGATIVO";
            $html .= "                     </td>\n";
            $html .= "                     <td colspan='2' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                       <input type=\"text\" class=\"input-text\" name=\"descripcion_trabajo_no_habitual\" id=\"descripcion_trabajo_no_habitual\"  size=\"60\"  maxlength='59' onkeypress=\"return acceptNum(event)\" disabled >\n";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td colspan='3' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        TOTAL TIEMPO LABORANDO PREVIO AL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                       HORAS<select class=\"select\" name=\"hpa\" id=\"hpa\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">-</option> \n";
            for($i=0;$i<25;$i++)
            {
                $html .= "                       <option value=\"".$i."\">".$i."</option> \n";
            }
            $html .= "                         </select>\n";

            $html .= "                      MINUTOS <select class=\"select\" name=\"mpa\" id=\"mpa\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">-</option> \n";
            for($i=0;$i<60;$i++)
            {
                $html .= "                       <option value=\"".$i."\">".$i."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                      </td>\n";
            $html .= "                     <td COLSPAN='2' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                       TIPO DE ACCIDENTE";
            
            
            $html .= "                      <select class=\"select\" name=\"tipo_accidente\" id=\"tipo_accidente\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">SELECCIONAR</option> \n";
            for($i=0;$i<count($tipos_Accidente);$i++)
            {                                                          
                $html .= "                       <option value=\"".$tipos_Accidente[$i]['tipo_accidente_id']."\">".$tipos_Accidente[$i]['descripcion']."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        DEPARTAMENTO DEL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                       <select class=\"select\" name=\"depto\" id=\"depto\" onchange=\"Obtener_Municipios(this.value);\">";
            $html .= "                         <option value=\"-1\">SELECCIONAR</option> \n";
            for($i=0;$i<count($departamentos);$i++)
            {                          // tipo_dpto_id    tipo_pais_id    departamento
                $html .= "                       <option value=\"".$departamentos[$i]['tipo_dpto_id']."\">".$departamentos[$i]['departamento']."</option> \n";
            }
            $html .= "                         </select>\n";

            $html .= "                      </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        MUNICIPIO DEL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                       <select class=\"select\" name=\"ciudades\" id=\"ciudades\" onchange=\"\" disabled>";
            $html .= "                         <option value=\"-1\" selected>SELECCIONAR</option> \n";
            $html .= "                       </select>\n";

            $html .= "                      </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        ZONA DONDE OCURRIO EL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                      <label class=\"normal_10AN\"> URBANA </label> <input type=\"radio\" name=\"zona_acc\" id=\"za\" value=\"1\"><BR>";
            $html .= "                      <label class=\"normal_10AN\"> RURAL </label>  <input type=\"radio\" name=\"zona_acc\" id=\"za\" value=\"0\">";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       CODIGO DEL DEPARTAMENTO ";
            $html .= "                     </td>\n";
            $html .= "                     <td id='codigo_dep' class=\"modulo_list_claro\" align=\"left\">\n";
            
            $html .= "                      </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        CODIGO DEL MUNICIPIO";
            $html .= "                     </td>\n";
            $html .= "                     <td id='codigo_municipio' class=\"modulo_list_claro\" align=\"left\">\n";
            
            $html .= "                     </td>\n";
            $html .= "                     <td COLSPAn='2' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                      &nbsp;";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       LUGAR DONDE OCURRIO EL ACCIDENTE ";
            $html .= "                     </td>\n";
            $html .= "                     <td colspan='2'  class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <label class=\"normal_10AN\"> DENTRO DE LA EMPRESA </label> <input type=\"radio\" name=\"lugar_acc\" id=\"lugar\"  value=\"1\">";
            $html .= "                      <label class=\"normal_10AN\"> FUERA DE LA EMPRESA </label>  <input type=\"radio\" name=\"lugar_acc\" id=\"lugar\"  value=\"0\">";
            $html .= "                     </td>\n";
//             $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
//             $html .= "                        INDIQUE EL SITIO DEL ACCIDENTE";
//             $html .= "                     </td>\n";
            $html .= "                     <td colspan='2' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <label class=\"normal_10AN\">INDIQUE EL SITIO</label> <select class=\"select\" name=\"sitio_accidente\" id=\"sitio_accidente\" onchange=\"\">";
            $html .= "                         <option value=\"-1\">SELECCIONAR</option> \n";
            for($i=0;$i<count($sitios_accidente);$i++)
            {                                                       
                $html .= "                       <option title='".$sitios_accidente[$i]['descripcion']."' value=\"".$sitios_accidente[$i]['sitio_accidente_id']."\">".substr($sitios_accidente[$i]['descripcion'],0,30)."</option> \n";
            }
            $html .= "                         </select>\n";

            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                    <tr>\n";
            $html .= "                     <td colspan='8' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        &nbsp;";
            $html .= "                     </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td colspan='6'>\n";
            $html .= "                     <table width='100%' celspan>\n";
            if(count($tipos_lesion) > count($partes_del_cuerpo_afectado))
            {
                $rows=count($tipos_lesion)+2;
                $registros=count($tipos_lesion);
            }
            else
            {   
                $rows=count($partes_del_cuerpo_afectado)+2;
                $registros=count($partes_del_cuerpo_afectado);
            }
            for($i=0;$i<$registros;$i++)
            {
                    
                if($i%2==0)
                {
                    $estilo="modulo_list_claro";
                }
                else
                {
                    $estilo="modulo_list_oscuro";
                }
                if($i==0)
                {
                    $html .= "                   <tr class='".$estilo."'>\n";
                    $html .= "                     <td width='15%' ROWSPAN='".$registros."' class=\"modulo_table_list_title\" align=\"center\">\n";
                    $html .= "                       TIPOS DE LESION <BR> (MARQUE CUAL O CUALES)";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <input type=\"checkbox\" name=\"lesiones[".$tipos_lesion[$i]['tipo_lesion_id']."]\" value=\"1\">";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">(".$tipos_lesion[$i]['tipo_lesion_id'].")</label>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='29%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">".$tipos_lesion[$i]['descripcion']." </label>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='15%' ROWSPAN='".$registros."' class=\"modulo_table_list_title\" align=\"center\">\n";
                    $html .= "                       PARTE DEL CUERPO <BR> APARENTEMENTE AFECTADA";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <input type=\"checkbox\" name=\"partes_cuerpo[".$partes_del_cuerpo_afectado[$i]['parte_cuerpo_id']."]\" value=\"\">";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">(".$partes_del_cuerpo_afectado[$i]['parte_cuerpo_id'].")</label>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='29%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">".$partes_del_cuerpo_afectado[$i]['descripcion']." </label>";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                }
                else
                {
                    $html .= "                     <tr class='".$estilo."'>\n";
                    
                    if(!empty($tipos_lesion[$i]['tipo_lesion_id']))
                    {
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n"; 
                        $html .= "                      <input type=\"checkbox\" name=\"lesiones[".$tipos_lesion[$i]['tipo_lesion_id']."]\" value=\"1\">";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n"; 
                        $html .= "                      <label class=\"normal_10AN\">(".$tipos_lesion[$i]['tipo_lesion_id'].")</label>";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n"; 
                        $html .= "                       <label class=\"normal_10AN\">".$tipos_lesion[$i]['descripcion']." </label>";
                        $html .= "                     </td>\n";
                    }
                    else
                    {

                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n"; 
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n"; 
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n"; 
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";

                        

                    }
                    
                    
                    if(!empty($partes_del_cuerpo_afectado[$i]['parte_cuerpo_id']))
                    {
                      $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                      $html .= "                      <input type=\"checkbox\" name=\"partes_cuerpo[".$partes_del_cuerpo_afectado[$i]['parte_cuerpo_id']."]\" value=\"1\">";
                      $html .= "                     </td>\n";
                      $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                      $html .= "                      <label class=\"normal_10AN\">(".$partes_del_cuerpo_afectado[$i]['parte_cuerpo_id'].")</label>";
                      $html .= "                     </td>\n";
                      $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                      $html .= "                      <label class=\"normal_10AN\">".$partes_del_cuerpo_afectado[$i]['descripcion']." </label>";
                      $html .= "                     </td>\n";  
                    }
                    else
                    {

                      $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                      $html .= "                      &nbsp;";
                      $html .= "                     </td>\n";
                      $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                      $html .= "                      &nbsp;";
                      $html .= "                     </td>\n";
                      $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                      $html .= "                      &nbsp;";
                      $html .= "                     </td>\n";    
                      
                    }
                    
                    $html .= "                     </tr>\n";
                }

            }


            $html .= "                    <tr>\n";
            $html .= "                     <td colspan='8' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        &nbsp;";
            $html .= "                     </td>\n";
            $html .= "                    </tr>\n";


            if(count($Agentes_Accidentes) > count($Formas_Accidente))
            {
               $rows=count($Agentes_Accidentes)+2;
               $registros=count($Agentes_Accidentes);
            }
            else
            {   
               $rows=count($Formas_Accidente)+2;
               $registros=count($Formas_Accidente);
            }


            for($i=0;$i<$registros;$i++)
            {
                    
                if($i%2==0)
                {
                    $estilo="modulo_list_claro";
                }
                else
                {
                    $estilo="modulo_list_oscuro";
                }

                 if($i==0)
                 {
                    $html .= "                     <tr class='".$estilo."'>\n";
                    $html .= "                     <td width='15%' ROWSPAN='".$rows."' class=\"modulo_table_list_title\" align=\"center\">\n";
                    $html .= "                       AGENTE DEL ACCIDENTE <BR> (CON QUE SE LESIONO EL TRABAJADOR)";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <input type=\"checkbox\" name=\"Agentes_accidente[".$Agentes_Accidentes[$i]['tipo_lesion_id']."]\" value=\"1\">";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                       <label class=\"normal_10AN\">(".$Agentes_Accidentes[$i]['agente_accidente_id'].")</label>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='29%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">".$Agentes_Accidentes[$i]['descripcion']." </label>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='15%' ROWSPAN='".$rows."' class=\"modulo_table_list_title\" align=\"center\">\n";
                    $html .= "                       MECANISMOS O FORMAS DE ACCIDENTE";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <input type=\"checkbox\" name=\"formas_accidente[".$Formas_Accidente[$i]['tipo_lesion_id']."]\" value=\"1\">";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">(".$Formas_Accidente[$i]['forma_accidente_id'].") </label>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='29%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "                      <label class=\"normal_10AN\">".$Formas_Accidente[$i]['descripcion']." </label>";
                    $html .= "                     </td>\n";
                    $html .= "                     </tr>\n";
                 }
                 else
                 {
                    $html .= "                     <tr class='".$estilo."'>\n";
                    
                    if(!empty($Agentes_Accidentes[$i]['agente_accidente_id']))
                    {
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      <input type=\"checkbox\" name=\"Agentes_accidente[".$Agentes_Accidentes[$i]['agente_accidente_id']."]\" value=\"1\">";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      <label class=\"normal_10AN\">(".$Agentes_Accidentes[$i]['agente_accidente_id'].")</label>";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      <label class=\"normal_10AN\">".$Agentes_Accidentes[$i]['descripcion']." </label>";
                        $html .= "                     </td>\n";
                    }
                    else
                    {
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                    
                    }
                    
                    
                    if(!empty($Formas_Accidente[$i]['forma_accidente_id']))
                    {
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      <input type=\"checkbox\" name=\"formas_accidente[".$Formas_Accidente[$i]['forma_accidente_id']."]\" value=\"".$Formas_Accidente[$i]['forma_accidente_id']."\">";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                       <label class=\"normal_10AN\">(".$Formas_Accidente[$i]['forma_accidente_id'].")</label>";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      <label class=\"normal_10AN\">".$Formas_Accidente[$i]['descripcion']." </label>";
                        $html .= "                     </td>\n";
                    }
                    else
                    {
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                        $html .= "                     <td class=\"modulo_table_list_claro\" align=\"left\">\n";
                        $html .= "                      &nbsp;";
                        $html .= "                     </td>\n";
                    }
                    $html .= "                     </td>\n";
                    $html .= "                     </tr>\n";
                 }

             }    

            $html .= "                 </table>\n";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                <td colspan='6' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                  DESCRIBA DETALLADAMENTE EN EL RECUADRO LA INFORMACION DEL ACCIDENTE, QUE LO ORIGINO O CAUSO Y DEMAS ASPECTOS RELACIONADOS AL ACCIDENTE";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                <td COLSPAN='6' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                  <TEXTAREA name='detalle_accidente' ROWS='4' style=\"width:80%\">DESCRIBA DETALLADAMENTE EN EL RECUADRO LA INFORMACION DEL ACCIDENTE QUE LO ORIGINO O CAUSO Y DEMAS ASPECTOS RELACIONADOS</TEXTAREA>";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                  TIPO DE ESPACIO EN EL QUE OCURRIO EL ACCIDENTE";
            $html .= "                </td>\n";
            $html .= "                <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                  <select class=\"select\" name=\"espacios\" id=\"espacios\" onchange=\"PintarTablaAgentes(this.value);\">";
            $html .= "                    <option value=\"-1\">SELECCIONAR</option> \n";
            for($i=0;$i<count($espacios);$i++)
            {                                                    
                $html .= "                       <option value=\"".$espacios[$i]['tipo_espacio_id']."\">".$espacios[$i]['descripcion']."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                      </td>\n";
            $html .= "                <td colspan='1' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                  FACTORES DE RIESGO";
            $html .= "                </td>\n";
            $html .= "                <td id='agentesx_riesgo' colspan='2' class =\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                <label CLASS='label_error'>PARA VISUALIZAR LOS FACTORES DE RIESGO SELECCIONE UN TIPO DE ESPACIO</label>";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                <td ROWSPAN='4' colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                  HUBO PERSONAS QUE PRESENCIARON EL ACCIDENTE";
            $html .= "                </td>\n";
            $html .= "                <td ROWSPAN='4'  class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                   SI  <input type=\"radio\" name=\"personas_presentes\" id=\"personas_presentes\" onclick=\"Evaluar2(this.value);\" value=\"1\">";
            $html .= "                   NO  <input type=\"radio\" name=\"personas_presentes\" id=\"personas_presentes\" onclick=\"Evaluar2(this.value);\" value=\"0\" >";
            $html .= "                </td>\n";
            $html .= "                <td colspan='3' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                  (EN CASO AFIRMATIVO INGRESE LOS DATOS DE LA(S) PERSONA)";
            $html .= "                 </td>\n";
            $html .= "               </tr>\n";
            $html .= "               <tr>\n";
            $html .= "                 <td COLSPAN='3' class=\"modulo_list_claro\" align=\"LEFT\">\n";
            $html .= "                 1).  TIPO ID<select class=\"select\" name=\"tipo_id_ter[1]\" id=\"tipo_id_ter1\" onchange=\"habilitar(this,'1')\" disabled>";
            $html .= "                    <option value=\"-1\">--</option> \n";
            
            for($i=0;$i<count($Tipo_id_terceros);$i++)
            {
                $html .= "                       <option title='".$Tipo_id_terceros[$i]['descripcion']."' value=\"".$Tipo_id_terceros[$i]['tipo_id_tercero']."\">".$Tipo_id_terceros[$i]['tipo_id_tercero']."</option> \n";
            }
            $html .= "                         </select> \n";
            
            $html .= "                   &nbsp;&nbsp;IDENTIFICACION <input type=\"text\" class=\"input-text\" name=\"id_ter[1]\" id=\"id_ter1\"  size=\"15\"  maxlength='15' onkeypress=\"return acceptNum(event)\" disabled >\n";
            $html .= "                   &nbsp;&nbsp;NOMBRE <input type=\"text\" class=\"input-text\" name=\"nom_ter[1]\" id=\"nom_ter1\"  size=\"39\"  maxlength='50' disabled >\n";
            $html .= "                 </td>\n";
            $html .= "               </tr>\n";
             $html .= "               <tr>\n";
            $html .= "                 <td COLSPAN='3' class=\"modulo_list_claro\" align=\"LEFT\">\n";
            $html .= "                2).  TIPO ID<select class=\"select\" name=\"tipo_id_ter[2]\" id=\"tipo_id_ter2\" onchange=\"habilitar(this,'2')\" disabled>";
            $html .= "                    <option value=\"-1\">--</option> \n";
            
            for($i=0;$i<count($Tipo_id_terceros);$i++)
            {
                $html .= "                       <option title='".$Tipo_id_terceros[$i]['descripcion']."' value=\"".$Tipo_id_terceros[$i]['tipo_id_tercero']."\">".$Tipo_id_terceros[$i]['tipo_id_tercero']."</option> \n";
            }
            $html .= "                         </select> \n";
            
            $html .= "                   &nbsp;&nbsp;IDENTIFICACION <input type=\"text\" class=\"input-text\" name=\"id_ter[2]\" id=\"id_ter2\"  size=\"15\"  maxlength='15' onkeypress=\"return acceptNum(event)\" disabled >\n";
            $html .= "                   &nbsp;&nbsp;NOMBRE <input type=\"text\" class=\"input-text\" name=\"nom_ter[2]\" id=\"nom_ter2\"  size=\"39\"  maxlength='50'  disabled >\n";
            $html .= "                 </td>\n";
            $html .= "               </tr>\n";
             $html .= "               <tr>\n";
            $html .= "                 <td COLSPAN='3' class=\"modulo_list_claro\" align=\"LEFT\">\n";
            $html .= "                 3).  TIPO ID<select class=\"select\" name=\"tipo_id_ter[3]\" id=\"tipo_id_ter3\" onchange=\"habilitar(this,'3')\" disabled>";
            $html .= "                    <option value=\"-1\">--</option> \n";
            
            for($i=0;$i<count($Tipo_id_terceros);$i++)
            {
                $html .= "                       <option title='".$Tipo_id_terceros[$i]['descripcion']."' value=\"".$Tipo_id_terceros[$i]['tipo_id_tercero']."\">".$Tipo_id_terceros[$i]['tipo_id_tercero']."</option> \n";
            }
            $html .= "                         </select> \n";
            
            $html .= "                   &nbsp;&nbsp;IDENTIFICACION <input type=\"text\" class=\"input-text\" name=\"id_ter[3]\" id=\"id_ter3\"  size=\"15\"  maxlength='15' onkeypress=\"return acceptNum(event)\" disabled >\n";
            $html .= "                   &nbsp;&nbsp;NOMBRE <input type=\"text\" class=\"input-text\" name=\"nom_ter[3]\" id=\"nom_ter3\"  size=\"39\"  maxlength='50' disabled >\n";
            $html .= "                 </td>\n";
            $html .= "               </tr>\n";
            $html .= "               <tr>\n";
            $html .= "                 <td colspan='8' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                   <input class=\"input-submit\" type=\"button\" name=\"Guardar\" value=\"Guardar\" onclick=\"xajax_Registro_Accidente((xajax.getFormValues('accidente_trabajo')),'".$_REQUEST['afiliado_tipo_id']."','".$_REQUEST['afiliado_id']."');\">";
            $html .= "                 </td>\n";
            $html .= "              </tr>\n";
            $html .= "            </table>\n";
            $html .= "          </form>";
            $html .= "  <br>\n";
            $html .= "      <center><div id='resultado' class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= "  <br>\n";
            $html .= "   <table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "     <tr>\n";
            $html .= "       <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
            $html .= "        <td align=\"center\"><br>\n";
            $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
            $html .= "        </td>";
            $html .= "      </form>";
            $html .= "    </tr>";
            $html .= "  </table>";
            $html .= ThemeCerrarTabla();
            return $html;
		}

	}
?>