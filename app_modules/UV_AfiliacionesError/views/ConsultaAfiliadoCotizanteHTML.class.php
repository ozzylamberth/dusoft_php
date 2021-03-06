<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultaAfiliadoCotizanteHTML.class.php,v 1.4 2009/12/04 20:36:51 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: ConsultaAfiliadoCotizanteHTML 
  * Clase contiene la forma para de los datos del usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

	class ConsultaAfiliadoCotizanteHTML
	{
		/**
		* Constructor de la clase
		*/
		function ConsultaAfiliadoCotizanteHTML(){}
		/**
	  * @param array $action Vector de links de la aplicaion
		* @param array $datos_cotizante Vector de tipos de identificacion
		* @param array $tipos_afiliados Vector de tipos de afiliados
    * @param array $salida Vector de estados de afiliados
    * @param string $cuantos Vector de dependencias de la U.V.
    *
    * @return String
		*/
		function FormaDatosAfiliado($action,$datos_cotizante,$salida,$cuantos)
		{
      $vector_permiso=SessionGetVar("permisosAfiliaciones");
      $usuario=UserGetUID();
      $style = " style=\"text-align:left;text-indent:8pt\" ";
      $html  = ThemeAbrirTabla('CONSULTA DE AFILIADOS');
      if($vector_permiso[$usuario]['perfil_id']=='C')//|| $vector_permiso[$usuario]['perfil_id']=='I'
      {    
        $html .= "<form name=\"info_usuario\" id=\"info_usuario\" action=\"\" method=\"post\">";
        $html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
        $html .= "	<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "	  <tr class=\"modulo_table_list_title\">\n";
        $html .= "	    <td colspan=\"8\">INFORMACION USUARIO</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td style=\"text-align:left;text-indent:8pt\">N? AFILIACION</td>\n";
        $html .= "      <td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['eps_afiliacion_id']."";
        $html .= "      </td>\n";
        $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:8pt\">";
        $html .= "        ESTADO";
        $html .= "      </td >\n";
        $html .= "      <td colspan='3' width=\"12%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['descripcion_estado']."-".$datos_cotizante['descripcion_subestado'];
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:8pt\">";
        $html .= "        TIPO AFILIADO";
        $html .= "      </td>\n";
        $html .= "      <td width=\"15%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['descripcion_eps_tipo_afiliado'];
        $html .= "      </td>\n";
        $html .= "      <td width=\"13%\" style=\"text-align:left;text-indent:8pt\">";
        $html .= "        FECHA AFILIACION";
        $html .= "      </td>\n";
        $html .= "      <td width=\"8%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['fecha_afiliacion'];
        $html .= "      </td>\n";
        
        if($datos_cotizante['eps_tipo_afiliado_id'] == "C")
        {
          $f = explode("-",$datos_cotizante['fecha_afiliacion']);
          
          $timestamp1 = mktime(0,0,0,$f[1],$f[2],$f[0]);
          $timestamp2 = mktime(4,12,0,date("m"),date("d"),date("Y"));
          
          $segundos_diferencia = abs($timestamp1 - $timestamp2);
          $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
          $semanas = intval($dias_diferencia/7) + $datos_cotizante['semanas_cotizadas_eps_anterior'];
          
          $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:8pt\">";
          $html .= "        SEMANAS COTIZADAS";
          $html .= "      </td>\n";
          $html .= "      <td width=\"7%\" align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$semanas;
          $html .= "      </td>\n";
        }
        else
        {
          $html .= "      <td colspan=\"2\" class=\"modulo_list_claro\">&nbsp;</td>\n";
        }
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td ".$style.">PLAN</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['plan_descripcion'];
        $html .= "      </td>\n";
        $html .= "      <td ".$style.">TIPO AFILIADO PLAN</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['tipo_afiliado_nombre'];
        $html .= "      </td>\n";
        $html .= "      <td ".$style.">RANGO</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['rango'];
        $html .= "      </td>\n";
        $html .= "     </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "        PUNTO DE ATENCION";
        $html .= "      </td>\n";
        $html .= "      <td COLSPAN='5' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['eps_punto_atencion_nombre'];
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        
        if($datos_cotizante['fecha_afiliacion_eps_anterior'])
        {
          $html .= "     <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        EPS ANTERIOR";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['razon_social_eps_anterior'];
          $html .= "      </td>\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        FECHA DE AFILIACION EPS ANTERIOR";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['fecha_afiliacion_eps_anterior'];
          $html .= "      </td>\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        SEMANAS COTIZADA EPS ANTERIOR";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['semanas_cotizadas_eps_anterior'];
          $html .= "      </td>\n";
          $html .= "     </tr>\n";
        }
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td colspan='3' ".$style." >\n";
        $html .= "         FECHA DE AFILIACION SISTEMA GENERAL SEGIRIDAD SOCIAL ";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" colspan='3' class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['fecha_afiliacion_sgss'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"formulacion_table_list\"> \n";
        $html .= "       <td colspan='6' height=\"4\"></td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td ".$style." >IDENTIFICACION</td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['afiliado_tipo_id']." ".$datos_cotizante['afiliado_id'];
        $html .= "       </td>\n";
        $html .= "       <td ".$style." >NOMBRE</td>\n";
        $html .= "       <td COLSPAN ='3' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['nombre_afiliado'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td ".$style.">SEXO</td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['descripcion_eps_tipo_sexo_id'];
        $html .= "       </td>\n";
        $html .= "       <td ".$style.">FECHA NACIMIENTO</td>\n";
        $html .= "       <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['fecha_nacimiento'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         ESTRATO SOCIAL";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_estrato_socioeconomico'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         ESTADO CIVIL";
        $html .= "       </td>\n";
        $html .= "       <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_estado_civil'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         ZONA RESIDENCIA";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['descripcion_zona_residencia'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         DIR RESIDENCIA";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN='3' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['direccion_residencia']." (".$datos_cotizante['municipio']."-".$datos_cotizante['departamento']."-".$datos_cotizante['pais'].")";
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         CELULAR";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN='1' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['telefono_movil'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         TEL RESIDENCIA";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN='3' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['telefono_residencia'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";

        if(!empty($datos_cotizante["DATOS_COTIZANTE"]))
        {
          $html .= "     <tr class=\"formulacion_table_list\"> \n";
          $html .= "       <td colspan='6' height=\"4\"></td>\n";
          $html .= "     </tr>\n";
          $html .= "    <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        ESTAMENTO";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_estamento'];
          $html .= "      </td>\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        ACTIVIDAD ECONOMICA";
          $html .= "      </td>\n";
          $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_ciiu_r3_grupo'];
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          if($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='S' && $datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='J')
          {
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
            $html .= "        OCUPACION";
            $html .= "      </td>\n";
            $html .= "      <td COLSPAN='5' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_ciuo_88_grupo_primario'];
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
            $html .= "        DEPENDENCIA";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_dependencia'];
            $html .= "      </td>\n";
            $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
            $html .= "        TEL DEPENDENCIA";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" colspan='3' class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['telefono_dependencia'];
            $html .= "      </td>\n";
            $html .= "    </tr>\n"; 
          }
          $html .= "    <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        T/APORTANTE";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_tipo_aportante'];
          $html .= "      </td>\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        FECHA INGRESO LABORAL";
          $html .= "      </td>\n";
          $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['fecha_ingreso_laboral'];
          $html .= "      </td>\n";
          $html .= "    </tr>";
          if($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='V')
          {
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td style=\"text-align:left;text-indent:8pt\">\n";
                
            if($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='S' && $datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='J')
            {
              $html .= "        SALARIO BASE";
              $html .= "      </td>\n";
              $html .= "      <td COLSPAN='5' align=\"left\" class=\"modulo_list_claro\">\n";
              $html .= "       ".FormatoValor($datos_cotizante["DATOS_COTIZANTE"]['ingreso_mensual']);
              $html .= "      </td>\n";
            }
            elseif($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']=='S' || $datos_cotizante["DATOS_COTIZANTE"]['estamento_id']=='J')
            {
              $html .= "        INGRESO MENSUAL";
              $html .= "      </td>\n";
              $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
              $html .= "       ".FormatoValor($datos_cotizante["DATOS_COTIZANTE"]['ingreso_mensual']);
              $html .= "      </td>\n";
              $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
              $html .= "        FONDO DE PENSIONES AFP";
              $html .= "      </td>\n";
              $html .= "      <td COLSPAN='3' align=\"left\" class=\"modulo_list_claro\">\n";
              $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['razon_social_afp'];
              $html .= "      </td>\n";
            }
            $html .= "    </tr>";
          }
          elseif($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']=='V')
          {
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        IDENTIFICACION ENTIDAD CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['convenio_tipo_id_tercero']."-".$datos_cotizante["DATOS_CONVENIO"]['convenio_tercero_id']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        ENTIDAD CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['nombre_tercero']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "    </tr>";
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        FECHA INICIO CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['fecha_inicio_convenio']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        FECHA VENCIMIENTO CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['fecha_vencimiento_convenio']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "    </tr>";
          }
        }
        if(!empty($datos_cotizante["DATOS_BENEFICIARIO"]))
        {
          $html .= "    <tr class=\"formulacion_table_list\"> \n";
          $html .= "      <td colspan='6' height=\"4\"></td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td ".$style.">NOMBRE DEL COTIZANTE</td>\n";
          $html .= "      <td colspan='2' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['nombre_cotizante'];
          $html .= "      </td>\n";
          $html .= "      <td ".$style.">IDENTIFICACION</td>\n";
          $html .= "      <td align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['cotizante_tipo_id']."-".$datos_cotizante["DATOS_BENEFICIARIO"]['cotizante_id'];
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td ".$style.">PARENTESCO</td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\" colspan=\"5\">\n";
          $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['descripcion_parentesco']."&nbsp;";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          
          if($datos_cotizante["DATOS_BENEFICIARIO"]['estamento_id'] == 'V')
          {
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        IDENTIFICACION ENTIDAD CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['convenio_tipo_id_tercero']." ".$datos_cotizante["DATOS_CONVENIO"]['convenio_tercero_id']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        ENTIDAD CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['nombre_tercero']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "    </tr>";
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        FECHA INICIO CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['fecha_inicio_convenio']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "      <td COLSPAN='2' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        FECHA VENCIMIENTO CONVENIO";
            $html .= "      </td>\n";
            $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_CONVENIO"]['fecha_vencimiento_convenio']."&nbsp;";
            $html .= "      </td>\n";
            $html .= "    </tr>";
          }
          if(!empty($datos_cotizante["DATOS_BENEFICIARIO"]['observaciones']))
          {
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td colspan='1' style=\"text-align:left;text-indent:8pt\">";
            $html .= "        OBSERVACIONES";
            $html .= "      </td>\n";
            $html .= "      <td colspan='5' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['observaciones'];
            $html .= "      </td>\n";
            $html .= "    </tr>";
          }        
        }
        $html .= "  </table>\n";
        $html .= "</form>\n";
        
        $reporte = new GetReports();
        

        $mostrar1 = $reporte->GetJavaReport('app','UV_Afiliaciones','Certificado_Afiliacion',array("datos"=>$datos_cotizante),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $funcion1 = $reporte->GetJavaFunction();
        $mostrar2 = $reporte->GetJavaReport('app','UV_Afiliaciones','ReportePorUsuario',array("datos"=>$datos_cotizante),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $funcion2 = $reporte->GetJavaFunction();
        $html .= $mostrar1;
        $html .= $mostrar2;
        
        $html .= "  <table border=\"0\" width=\"95%\" align=\"center\" >\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\">";
        $html .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE USUARIO\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">GENERAR CERTIFICADO DE AFILIACION</a>\n";
        $html .= "      </td>";
        $html .= "      <td align=\"center\">";
        $html .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE USUARIO\">&nbsp;<a href=\"javascript:$funcion2\" class=\"label_error\">REPORTE USUARIO</a>\n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "  </table>";
        $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
        $html .= "              <td align=\"center\"><br>\n";
        $html .= "                  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";//VolverTablaDatos
        $html .= "              </td>";
        $html .= "          </form>";
        $html .= "      </tr>";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
      }
      elseif($vector_permiso[$usuario]['perfil_id']=='R' || $vector_permiso[$usuario]['perfil_id']=='I')//
      {
        $html .= "<form name=\"info_usuario\" id=\"info_usuario\" action=\"\" method=\"post\">";
        $html .= "  <center><div class=\"label_error\" id=\"error\"></div></center>\n";
        $html .= "  <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td colspan=\"8\">INFORMACION USUARIO</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "        NUMERO AFILIACION";
        $html .= "      </td>\n";
        $html .= "      <td colspan='3' width=\"10%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['eps_afiliacion_id']."";
        $html .= "      </td>\n";
        $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "        ESTAMENTO";
        $html .= "      </td>\n";
        $html .= "      <td colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante["DATOS_COTIZANTE"]['descripcion_estamento'];
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:8pt\">";
        $html .= "        TIPO AFILIADO";
        $html .= "      </td>\n";
        $html .= "      <td width=\"15%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['descripcion_eps_tipo_afiliado'];
        $html .= "      </td>\n";
        $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:8pt\">";
        $html .= "        ESTADO";
        $html .= "      </td>\n";
        $html .= "      <td width=\"12%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['estado_afiliado_id']."-".$datos_cotizante['subestado_afiliado_id'];
        $html .= "      </td>\n";
        $html .= "      <td width=\"13%\" style=\"text-align:left;text-indent:8pt\">";
        $html .= "        FECHA AFILIACION";
        $html .= "      </td>\n";
        $html .= "      <td width=\"8%\" align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "       ".$datos_cotizante['fecha_afiliacion'];
        $html .= "      </td>\n";
        
        if($datos_cotizante['eps_tipo_afiliado_id'] == "C")
        {
          $f = explode("-",$datos_cotizante['fecha_afiliacion']);
          
          $timestamp1 = mktime(0,0,0,$f[1],$f[2],$f[0]);
          $timestamp2 = mktime(4,12,0,date("m"),date("d"),date("Y"));
          
          $segundos_diferencia = abs($timestamp1 - $timestamp2);
          $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
          $semanas = intval($dias_diferencia/7) + $datos_cotizante['semanas_cotizadas_eps_anterior'];
          
          $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:8pt\">";
          $html .= "        SEMANAS COTIZADAS";
          $html .= "      </td>\n";
          $html .= "      <td width=\"7%\" align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$semanas;
          $html .= "      </td>\n";
        }
        else
        {
          $html .= "      <td colspan=\"2\" class=\"modulo_list_claro\">&nbsp;</td>\n";
        }
        $html .= "     </tr>\n";
        
        if($datos_cotizante['fecha_afiliacion_eps_anterior'])
        {
          $html .= "     <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
          $html .= "        EPS ANTERIOR";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['razon_social_eps_anterior'];
          $html .= "      </td>\n";
          $html .= "      <td colspan='2' style=\"text-align:left;text-indent:8pt\">";
          $html .= "        FECHA DE AFILIACION EPS ANTERIOR";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['fecha_afiliacion_eps_anterior'];
          $html .= "      </td>\n";
          $html .= "      <td colspan='2' style=\"text-align:left;text-indent:8pt\">";
          $html .= "        SEMANAS COTIZADA EPS ANTERIOR";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['semanas_cotizadas_eps_anterior'];
          $html .= "      </td>\n";
          $html .= "     </tr>\n";
        }
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td colspan='3' style=\"text-align:left;text-indent:8pt\">";
        $html .= "         FECHA DE AFILIACION SISTEMA GENERAL SEGIRIDAD SOCIAL ";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" colspan='5' class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['fecha_afiliacion_sgss'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";

        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         TIPO IDENTIFICACION";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['afiliado_tipo_id'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         IDENTIFICACION";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['afiliado_id'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         NOMBRE";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN ='3' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['nombre_afiliado'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         ZONA RESIDENCIA";
        $html .= "       </td>\n";
        $html .= "       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['descripcion_zona_residencia'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         DIR REISIDENCIA";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN='5' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['direccion_residencia']." (".$datos_cotizante['municipio']."-".$datos_cotizante['departamento']."-".$datos_cotizante['pais'].")";
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         CELULAR";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN='1' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['telefono_movil'];
        $html .= "       </td>\n";
        $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
        $html .= "         TEL RESIDENCIA";
        $html .= "       </td>\n";
        $html .= "       <td COLSPAN='5' align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "         ".$datos_cotizante['telefono_residencia'];
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        if(!empty($datos_cotizante["DATOS_BENEFICIARIO"]))
        {
          $html .= "    <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td colspan='8'>\n";
          $html .= "        &nbsp;";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr  class=\"modulo_table_list_title\">\n";
          $html .= "      <td colspan='2'>";
          $html .= "        NOMBRE DEL COTIZANTE";
          $html .= "      </td>\n";
          $html .= "      <td colspan='2' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['nombre_cotizante'];
          $html .= "      </td>\n";
          $html .= "      <td>";
          $html .= "        IDENTIFICACION";
          $html .= "      </td>\n";
          $html .= "      <td colspan='1' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['cotizante_tipo_id']."-".$datos_cotizante["DATOS_BENEFICIARIO"]['cotizante_id'];
          $html .= "      </td>\n";
          $html .= "      <td>";
          $html .= "        PARENTESCO";
          $html .= "      </td>\n";
          $html .= "      <td COLSPAN='1' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante["DATOS_BENEFICIARIO"]['descripcion_parentesco']."&nbsp;";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
        }
        
        if(!empty($datos_cotizante['observaciones']))
        {
          $html .= "    <tr class=\"modulo_table_list_title\">\n";
          $html .= "      <td colspan='1' style=\"text-align:left;text-indent:8pt\">";
          $html .= "        OBSERVACIONES";
          $html .= "      </td>\n";
          $html .= "      <td colspan='7' align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "       ".$datos_cotizante['observaciones'];
          $html .= "      </td>\n";
          $html .= "    </tr>";
        }
        $html .= "  </table>\n";
        $html .= "</form>";
        
        $html .= "  <table border=\"0\" width=\"95%\" align=\"center\" >\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\">";
        
        $reporte = new GetReports();
        $mostrar = $reporte->GetJavaReport('app','UV_Afiliaciones','ReportePorUsuario',array("datos"=>$datos_cotizante),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $funcion1 = $reporte->GetJavaFunction();
        $html .= $mostrar;
        //$reporte = "app_modules/UV-Afiliaciones/reports/html/ReportePorUsuario.report.php?".URLRequest($fecha_ini);
        $html .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE USUARIO\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">REPORTE USUARIO</a>\n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "  </table>";
        
        $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
        $html .= "              <td align=\"center\"><br>\n";
        $html .= "                  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";//VolverTablaDatos
        $html .= "              </td>";
        $html .= "          </form>";
        $html .= "      </tr>";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
      }      
		}
	}
?>