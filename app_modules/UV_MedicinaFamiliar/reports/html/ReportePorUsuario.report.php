<?php
    /**
    * @package IPSOFT-SIIS
    * @version $Id: ReportePorUsuario.report.php,v 1.4 2007/11/09 13:54:55 jgomez Exp $ 
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author JAIME GOMEZ
    */
    
    /**
    * Clase Reporte: ReportePorUsuario_report 
    * reporte que contiene los datos del afiliado dependiendo de su estamento.
    * @package IPSOFT-SIIS
    * @version $Revision: 1.4 $
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author Jaime Gomez
    */
	


    class ReportePorUsuario_report 
	{ 
        //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
        var $datos;
        
        //PARAMETROS PARA LA CONFIGURACION DEL REPORTE
        //NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
        var $title       = '';
        var $author      = '';
        var $sizepage    = 'leter';
        var $Orientation = '';
        var $grayScale   = false;
        var $headers     = array();
        var $footers     = array();
        
        
        /**
        * CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
        * @param array $datos
        * @return boolean
        */
        function ReportePorUsuario_report($datos=array())
        {
            $this->datos=$datos;
            return true;
        }

        /**
        * Funcion que coloca el menbrete del reporte
        * @return array $Membrete
        *
        **/
        function GetMembrete()
        {
            $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
            $titulo .= "<b $estilo>INFORMACION DE AFILIADOS AL SERVICIO EN SALUD </b>";
        
            $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
                                                'subtitulo'=>"<b $estilo>UNIVERSIDAD DEL VALLE </b>",'logo'=>'logocliente.png','align'=>'left'));//logocliente.png
            return $Membrete;
        }

        /**
        *FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
        *
        *@return string $html con la forma del reporte
        **/
        function CrearReporte()
        {
            //var_dumP(SessionGetVar("permisosAfiliaciones"));
            $vector_permiso=SessionGetVar("permisosAfiliaciones");
            $usuario=UserGetUID();    
            if($vector_permiso[$usuario]['perfil_id']=='C')
            {    
                $ESTILO3="style=\"font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
                $datos_cotizante = $_REQUEST['datos'];
                $ESTILO2="style=\"font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\"";
//                 VAR_DUMP($datos_cotizante);
                $html .= "<form name=\"info_usuario\" id=\"info_usuario\" action=\"\" method=\"post\">";
                $html .= "  <table border=\"1\" width=\"80%\" align=\"center\" cellspacing='0'>\n";
                $html .= "    <tr>\n";
                if(empty($datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_estamento']))
                {
                    $html .= "  <td $ESTILO2  colspan=\"7\">DATOS DEL ".$datos_cotizante['datos']['descripcion_eps_tipo_afiliado']."</td>\n";
                }
                else
                {
                    $html .= "  <td $ESTILO2 colspan=\"4\">DATOS DEL ".$datos_cotizante['datos']['descripcion_eps_tipo_afiliado']."</td>\n";
                    $html .= "  <td $ESTILO2>";
                    $html .= "    ESTAMENTO";
                    $html .= "  </td>\n";
                    $html .= "  <td colspan=\"2\" align=\"left\" $ESTILO3>\n";
                    $html .= "    ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_estamento'];
                    $html .= "  </td>\n";
                }
                $html .= "    </tr>\n";
                $html .= "    <tr class=\"modulo_table_list_title\" >\n";
                $html .= "      <td $ESTILO2 >";
                $html .= "        TIPO AFILIADO";
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO3 align=\"left\">\n";
                $html .= "       ".$datos_cotizante['datos']['descripcion_eps_tipo_afiliado'];
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        ESTADO";
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO3 align=\"left\">\n";
                $html .= "       ".$datos_cotizante['datos']['descripcion_estado']."-".$datos_cotizante['datos']['descripcion_subestado'];
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        FECHA AFILIACION";
                $html .= "      </td>\n";
                $html .= "      <td colspaN='2' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['fecha_afiliacion'];
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td colspan='2' $ESTILO2>";
                $html .= "        SEMANAS COTIZADAS";
                $html .= "      </td>\n";
                $html .= "      <td colspan='1' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['semanas_cotizadas'];
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        EPS ANTERIOR";
                $html .= "      </td>\n";
                $html .= "      <td colspan='3' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['razon_social_eps_anterior'];
                $html .= "      </td>\n";

                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td colspan='2' $ESTILO2>";
                $html .= "        SEMANAS COTIZADA EPS ANTERIOR";
                $html .= "      </td>\n";
                $html .= "      <td co??span='2' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['semanas_cotizadas_eps_anterior'];
                $html .= "      </td>\n";
                $html .= "      <td colspan='2' $ESTILO2>";
                $html .= "        FECHA DE AFILIACION EPS ANTERIOR";
                $html .= "      </td>\n";
                $html .= "      <td colspan='2' $ESTILO3 align=\"left\">\n";
                $html .= "       ".$datos_cotizante['datos']['fecha_afiliacion_eps_anterior'];
                $html .= "      </td>\n";    
                $html .= "     </tr>\n";
                $html .= "     <tr> \n";
                $html .= "       <td colspan='3' $ESTILO2>";
                $html .= "         FECHA DE AFILIACION SISTEMA GENERAL SEGIRIDAD SOCIAL ";
                $html .= "       </td>\n";
                $html .= "       <td align=\"left\" colspan='4' $ESTILO3>\n";
                $html .= "         ".$datos_cotizante['datos']['fecha_afiliacion_sgss'];
                $html .= "       </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr> \n";
                $html .= "       <td colspan='7' $ESTILO3>\n";
                $html .= "         &nbsp;";
                $html .= "       </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "       <td $ESTILO2 width='14%'>";
                $html .= "         NOMBRE";
                $html .= "       </td>\n";
                $html .= "       <td width='33%'  COLSPAN ='2' align=\"left\" $ESTILO3>\n";
                $html .= "         ".$datos_cotizante['datos']['nombre_afiliado'];
                $html .= "       </td>\n";    
                $html .= "       <td $ESTILO2 WIDTH='16%'>";
                $html .= "         IDENTIFICACION";
                $html .= "       </td>\n";
                $html .= "       <td width='13%' align=\"left\" $ESTILO3>\n";
                $html .= "        ".$datos_cotizante['datos']['afiliado_tipo_id']."-".$datos_cotizante['datos']['afiliado_id'];
                $html .= "      </td>\n";
                $html .= "      <td width='14%' $ESTILO2>";
                $html .= "        FECHA NACIMIENTO";
                $html .= "      </td>\n";
                $html .= "      <td width='10%' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['fecha_nacimiento'];
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "    <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td  $ESTILO2>";
                $html .= "        SEXO";
                $html .= "      </td>\n";
                $html .= "      <td  width='19%' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['descripcion_eps_tipo_sexo_id'];
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2 width='14%'>";
                $html .= "        ESTRATO SOCIAL";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_estrato_socioeconomico'];
                $html .= "      </td>\n";
                $html .= "      <td  $ESTILO2>";
                $html .= "        ESTADO CIVIL";
                $html .= "      </td>\n";
                $html .= "      <td colspan='2' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_estado_civil'];
                $html .= "      </td>\n";
                $html .= "    </tr>\n";
                $html .= "    <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td colspan='1' $ESTILO2>";
                $html .= "        ZONA RESIDENCIA";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['descripcion_zona_residencia'];
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        DIR REISIDENCIA";
                $html .= "      </td>\n";
                $html .= "      <td COLSPAN='4' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['direccion_residencia']." (".$datos_cotizante['datos']['municipio']."-".$datos_cotizante['datos']['departamento']."-".$datos_cotizante['datos']['pais'].")";
                $html .= "      </td>\n";
                $html .= "    </tr>\n";
                $html .= "    <tr>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        CELULAR";
                $html .= "      </td>\n";
                $html .= "      <td COLSPAN='1' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['telefono_movil']."&nbsp;";
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        TEL RESIDENCIA";
                $html .= "      </td>\n";
                $html .= "      <td COLSPAN='4' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['telefono_residencia']."&nbsp;";
                $html .= "      </td>\n";
                $html .= "    </tr>\n";
                
                if(!empty($datos_cotizante['datos']["DATOS_COTIZANTE"]))
                {
    
                    $html .= "    <tr >\n";
                    $html .= "      <td colspan='7' $ESTILO3>\n";
                    $html .= "        &nbsp;";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";    
                    if($datos_cotizante['datos']["DATOS_COTIZANTE"]['estamento_id']=='J' || $datos_cotizante['datos']["DATOS_COTIZANTE"]['estamento_id']=='S')
                    {
                        $html .= "    <tr class=\"modulo_table_list_title\">\n";
                        $html .= "      <td COLSPAN='2' $ESTILO2>";
                        $html .= "        FONDO DE PENSIONES AFP";
                        $html .= "      </td>\n";
                        $html .= "      <td COLSPAN='3' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['razon_social_afp']."&nbsp;";
                        $html .= "      </td>\n";
    
                   
                   
                        $html .= "      <td $ESTILO2>";
                        $html .= "        INGRESO MENSUAL";
                        $html .= "      </td>\n";
                        $html .= "      <td colspan='1' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".FormatoValor($datos_cotizante['datos']["DATOS_COTIZANTE"]['ingreso_mensual']);
                        $html .= "      </td>\n";
                        $html .= "    </tr>\n";
    
                    }
    
                    elseif($datos_cotizante['datos']["DATOS_COTIZANTE"]['estamento_id']!='J' && $datos_cotizante['datos']["DATOS_COTIZANTE"]['estamento_id']!='S')
                    {
    
                        $html .= "    <tr class=\"modulo_table_list_title\">\n";
                        $html .= "      <td colspan='1' $ESTILO2>";
                        $html .= "        ACT ECONOMICA";
                        $html .= "      </td>\n";
                        $html .= "      <td colspan='6' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_ciiu_r3_grupo']."&nbsp;";
                        $html .= "      </td>\n";
                        $html .= "    </tr>\n";
                        $html .= "    <tr class=\"modulo_table_list_title\">\n";
                        $html .= "      <td $ESTILO2>";
                        $html .= "        OCUPACION";
                        $html .= "      </td>\n";
                        $html .= "      <td COLSPAN='2' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_ciuo_88_grupo_primario']."&nbsp;";
                        $html .= "      </td>\n";
                        $html .= "      <td $ESTILO2>";
                        $html .= "        DEPENDENCIA";
                        $html .= "      </td>\n";
                        $html .= "      <td COLSPAN='3' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_dependencia']."&nbsp;";
                        $html .= "      </td>\n";
                        $html .= "    </tr>\n";
                        $html .= "    <tr class=\"modulo_table_list_title\">\n";
                        $html .= "      <td  $ESTILO2>";
                        $html .= "        TEL DEPCIA";
                        $html .= "      </td>\n";
                        $html .= "      <td align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['telefono_dependencia']."&nbsp;";
                        $html .= "      </td>\n";
                        $html .= "      <td $ESTILO2>";
                        $html .= "        T/APORTANTE";
                        $html .= "      </td>\n";
                        $html .= "      <td COLSPAN='1' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_tipo_aportante']."&nbsp;";
                        $html .= "      </td>\n";
                        if($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='V')
                        {

                            if($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='S' && $datos_cotizante["DATOS_COTIZANTE"]['estamento_id']!='J')
                            {
                                $html .= "      <td $ESTILO2>";
                                $html .= "        SALARIO BASE";
                                $html .= "      </td>\n";
                                $html .= "      <td colspan='2' align=\"left\" $ESTILO3>\n";
                                $html .= "       ".FormatoValor($datos_cotizante["DATOS_COTIZANTE"]['ingreso_mensual']);
                                $html .= "      </td>\n";
                            }
                            elseif($datos_cotizante["DATOS_COTIZANTE"]['estamento_id']=='S' || $datos_cotizante["DATOS_COTIZANTE"]['estamento_id']=='J')
                            {
                                $html .= "      <td $ESTILO2>";
                                $html .= "        INGRESO MENSUAL";
                                $html .= "      </td>\n";
                                $html .= "      <td colspan='3' align=\"left\" $ESTILO3>\n";
                                $html .= "       ".FormatoValor($datos_cotizante['datos']["DATOS_COTIZANTE"]['ingreso_mensual']);
                                $html .= "      </td>\n";

                            }


                        }
                        else
                        {
                            $html .= "      <td COLSPAN='3' align=\"left\" $ESTILO3>\n";
                            $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_tipo_aportante']."&nbsp;";
                            $html .= "      </td>\n";
                        }
                        $html .= "    </tr>";
                        $html .= "    <tr class=\"modulo_table_list_title\">\n";
                        $html .= "      <td COLSPAN='2' $ESTILO2>";
                        $html .= "        FECHA DE INGRESO A LABORAR";
                        $html .= "      </td>\n";
                        $html .= "      <td colspan='5' align=\"left\" $ESTILO3>\n";
                        $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['fecha_ingreso_laboral']."&nbsp;";
                        $html .= "      </td>\n";
                        $html .= "    </tr>";
                        if($datos_cotizante['datos']["DATOS_COTIZANTE"]['estamento_id']=='V')
                        {
                            
                            $html .= "    <tr class=\"modulo_table_list_title\">\n";
                            $html .= "      <td COLSPAN='2' $ESTILO2>";
                            $html .= "        IDENTIFICACION ENTIDAD CONVENIO";
                            $html .= "      </td>\n";
                            $html .= "      <td align=\"left\" $ESTILO3>\n";
                            $html .= "       ".$datos_cotizante['datos']["DATOS_CONVENIO"]['convenio_tipo_id_tercero']."-".$datos_cotizante['datos']["DATOS_CONVENIO"]['convenio_tercero_id']."&nbsp;";
                            $html .= "      </td>\n";
                            $html .= "      <td COLSPAN='1' $ESTILO2>";
                            $html .= "        ENTIDAD CONVENIO";
                            $html .= "      </td>\n";
                            $html .= "      <td colspan='3' align=\"left\" $ESTILO3>\n";
                            $html .= "       ".$datos_cotizante['datos']["DATOS_CONVENIO"]['nombre_tercero']."&nbsp;";
                            $html .= "      </td>\n";
                            $html .= "    </tr>";
                            $html .= "    <tr class=\"modulo_table_list_title\">\n";
                            $html .= "      <td COLSPAN='2' $ESTILO2>";
                            $html .= "        FECHA INICIO CONVENIO";
                            $html .= "      </td>\n";
                            $html .= "      <td align=\"left\" $ESTILO3>\n";
                            $html .= "       ".$datos_cotizante['datos']["DATOS_CONVENIO"]['fecha_inicio_convenio']."&nbsp;";
                            $html .= "      </td>\n";
                            $html .= "      <td COLSPAN='2' $ESTILO2>";
                            $html .= "        FECHA VENCIMIENTO CONVENIO";
                            $html .= "      </td>\n";
                            $html .= "      <td colspan='2' align=\"left\" $ESTILO3>\n";
                            $html .= "       ".$datos_cotizante['datos']["DATOS_CONVENIO"]['fecha_vencimiento_convenio']."&nbsp;";
                            $html .= "      </td>\n";
                            $html .= "    </tr>";
                            
                        }
    
                    }
    
                }
    
                if(!empty($datos_cotizante['datos']["DATOS_BENEFICIARIO"]))
                {
    
                    $html .= "    <tr class=\"modulo_list_claro\">\n";
                    $html .= "      <td colspan='8'>\n";
                    $html .= "        &nbsp;";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";
                    $html .= "    <tr>\n";
                    $html .= "      <td colspan='1' $ESTILO2>";
                    $html .= "        NOMBRE DEL COTIZANTE";
                    $html .= "      </td>\n";
                    $html .= "      <td  $ESTILO3 colspan='2' align=\"left\" class=\"modulo_list_claro\">\n";
                    $html .= "       ".$datos_cotizante['datos']["DATOS_BENEFICIARIO"]['nombre_cotizante'];
                    $html .= "      </td>\n";
                    $html .= "      <td $ESTILO2>";
                    $html .= "        IDENTIFICACION";
                    $html .= "      </td>\n";
                    $html .= "      <td $ESTILO3 colspan='1' align=\"left\" class=\"modulo_list_claro\">\n";
                    $html .= "       ".$datos_cotizante['datos']["DATOS_BENEFICIARIO"]['cotizante_tipo_id']."-".$datos_cotizante['datos']["DATOS_BENEFICIARIO"]['cotizante_id'];
                    $html .= "      </td>\n";
                    $html .= "      <td $ESTILO2>";
                    $html .= "        PARENTESCO";
                    $html .= "      </td>\n";
                    $html .= "      <td $ESTILO3 COLSPAN='1' align=\"left\" class=\"modulo_list_claro\">\n";
                    $html .= "       ".$datos_cotizante['datos']["DATOS_BENEFICIARIO"]['descripcion_parentesco']."&nbsp;";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";
    
                }
    
                
                if(!empty($datos_cotizante['datos']['observaciones']))
                {
    
                    $html .= "    <tr class=\"modulo_table_list_title\">\n";
                    $html .= "      <td colspan='1' $ESTILO2>";
                    $html .= "        OBSERVACIONES";
                    $html .= "      </td>\n";
                    $html .= "      <td colspan='6' align=\"left\" $ESTILO3>\n";
                    $html .= "       ".$datos_cotizante['datos']['observaciones'];
                    $html .= "      </td>\n";
                    $html .= "    </tr>";
                }
                $html .= "  </table>\n";
                $html .= "</form>";
           }
           elseif($vector_permiso[$usuario]['perfil_id']=='R' || $vector_permiso[$usuario]['perfil_id']=='I')
           {
                $ESTILO3="style=\"border-color:#000000;border-style:solid; border-width: thin;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
                $ESTILO2="style=\"border-color:#000000;border-style:solid; border-width: thin; font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\"";
                $ESTILO20="style=\"font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\"";
                $datos_cotizante = $_REQUEST['datos'];
                $html .= "<form name=\"info_usuario\" id=\"info_usuario\" action=\"\" method=\"post\">";
                $html .= "  <table border=\"0\" width=\"90%\" align=\"center\" style=\"border-color:#000000;border-style:solid; border-width: thin;\">\n";
                $html .= "    <tr>\n";
                //var_dump($datos_cotizante);
                
                if(empty($datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_estamento']))
                {
                    $html .= "      <td style=\"border-color:#000000;border-style:solid; border-width: thin; font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\" colspan=\"7\">DATOS DEL ".$datos_cotizante['datos']['descripcion_eps_tipo_afiliado']."</td>\n";
                }
                else
                {
                    $html .= "      <td style=\"border-color:#000000;border-style:solid; border-width: thin; font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\" colspan=\"4\">DATOS DEL ".$datos_cotizante['datos']['descripcion_eps_tipo_afiliado']."</td>\n";
                    $html .= "      <td $ESTILO2>";
                    $html .= "        ESTAMENTO";
                    $html .= "      </td>\n";
                    $html .= "      <td colspan=\"2\" align=\"left\" $ESTILO3>\n";
                    $html .= "       ".$datos_cotizante['datos']["DATOS_COTIZANTE"]['descripcion_estamento'];
                    $html .= "      </td>\n";
                }
                $html .= "    </tr>\n";
                $html .= "      <tr> \n";
                $html .= "      <td colspan='7' $ESTILO3>\n";
                $html .= "        &nbsp;";
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "       <td $ESTILO2 width='12%'>";
                $html .= "         NOMBRE";
                $html .= "       </td>\n";
                $html .= "       <td width='33%'  COLSPAN ='2' align=\"left\" $ESTILO3>\n";
                $html .= "        ".$datos_cotizante['datos']['nombre_afiliado'];
                $html .= "       </td>\n";    
                $html .= "       <td $ESTILO2 WIDTH='16%'>";
                $html .= "         IDENTIFICACION";
                $html .= "       </td>\n";
                $html .= "       <td width='13%' align=\"left\" $ESTILO3>\n";
                $html .= "         ".$datos_cotizante['datos']['afiliado_tipo_id']."-".$datos_cotizante['datos']['afiliado_id'];
                $html .= "       </td>\n";
                $html .= "       <td width='16%' $ESTILO2>";
                $html .= "         FECHA NACIMIENTO";
                $html .= "       </td>\n";
                $html .= "       <td width='10%' align=\"left\" $ESTILO3>\n";
                $html .= "        ".$datos_cotizante['datos']['fecha_nacimiento'];
                $html .= "       </td>\n";
                $html .= "     </tr>\n";
                $html .= "    <tr class=\"modulo_table_list_title\" >\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        ESTADO";
                $html .= "      </td>\n";
                $html .= "      <td colspan='2' $ESTILO3 align=\"left\">\n";
                $html .= "       ".$datos_cotizante['datos']['descripcion_estado']."-".$datos_cotizante['datos']['descripcion_subestado'];
                $html .= "      </td>\n";
                $html .= "      <td colspan='1' $ESTILO2>";
                $html .= "        SEMANAS COTIZADAS";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['semanas_cotizadas'];
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        FECHA AFILIACION";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['fecha_afiliacion'];
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td $ESTILO2>";
                $html .= "        EPS ANTERIOR";
                $html .= "      </td>\n";
                $html .= "      <td colspan='3' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['razon_social_eps_anterior'];
                $html .= "      </td>\n";
                $html .= "      <td colspan='2' $ESTILO2>";
                $html .= "        FECHA DE AFILIACION EPS ANTERIOR";
                $html .= "      </td>\n";
                $html .= "      <td $ESTILO3 align=\"left\">\n";
                $html .= "       ".$datos_cotizante['datos']['fecha_afiliacion_eps_anterior'];
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td colspan='2' $ESTILO2>";
                $html .= "        SEMANAS COTIZADA EPS ANTERIOR";
                $html .= "      </td>\n";
                $html .= "      <td width='7%' align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['semanas_cotizadas_eps_anterior'];
                $html .= "      </td>\n";
                $html .= "      <td colspan='3' $ESTILO2>";
                $html .= "        FECHA DE AFILIACION SISTEMA GENERAL SEGIRIDAD SOCIAL ";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" colspan='5' $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['fecha_afiliacion_sgss'];
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "     <tr class=\"modulo_table_list_title\">\n";
                $html .= "      <td colspan='2' $ESTILO2>";
                $html .= "        ULTIMO PERIODO COTIZADO";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['ultimo_periodo_cotizado']."&nbsp;";
                $html .= "      </td>\n";
                $html .= "      <td colspan='3' $ESTILO2>";
                $html .= "        USUARIO SUJETO A COPAGO";
                $html .= "      </td>\n";
                $html .= "      <td align=\"left\" colspan='1' $ESTILO3>\n";
                $html .= "       ".$datos_cotizante['datos']['copago']."&nbsp;";
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "    </table>\n";

           }
           
                $html .= "     <table align='center' border='0' width='60%'>";
                $html .= "       <tr>\n";
                $html .= "         <td width='50%' align=\"left\" $ESTILO20>";
                $html .= "           USUARIO :";
                IncludeClass('Autocarga');
                $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
                $nombre=$afi->GetNombreUsuario($usuario);
                //var_dump($nombre);
                $html .= "       ".$nombre[0]['nombre']."&nbsp;";
                $html .= "      </td>\n";
                $html .= "         <td width='50%' align=\"left\" $ESTILO20>";
                $html .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
                $html .= "      </td>\n";
                $html .= "     </tr>\n";
                $html .= "    </table>\n"; 
           return $html;
           
        }

	}

?>
