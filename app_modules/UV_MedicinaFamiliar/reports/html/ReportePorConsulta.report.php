<?php
    /**
    * @package IPSOFT-SIIS
    * @version $Id: ReportePorConsulta.report.php,v 1.4 2007/11/08 23:24:54 jgomez Exp $ 
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author JAIME GOMEZ
    */
    
    /**
    * Clase Reporte: ReportePorConsulta_report 
    * reporte con los datos de los afiliados que consulta el buscador de afiliados.
    * @package IPSOFT-SIIS
    * @version $Revision: 1.4 $
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author Jaime Gomez
    */


    IncludeClass('Autocarga');
    class ReportePorConsulta_report 
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
        function ReportePorConsulta_report($datos=array())
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
        * Funcion que crea el cuerpo del reporte
        * @return string $salida
        *
        **/
        function CrearReporte()
        {
            
            $datos=SessionGetVar("BUSQUEDA");
            $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
            $afiliados = $afi->GetAfiliados($datos, $count=false, $limit=false, $offset=false);
            $contador_registros=SessionGetVar("CONTADOR");
            $ESTILO3="style=\"font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
            $ESTILO2="style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\"";
            //$usuario=UserGetUID();
            if(!empty($afiliados))
            {
                $salida .= "                 <table width=\"90%\" align=\"center\" >\n";
                $salida .= "                    <tr class=\"normal_10AN\">\n";
                $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                $salida .= "                       TOTAL DE REGISTRO(S) (".$contador_registros.")";
                $salida .= "                      </td>\n";
                $salida .= "                   </tr>\n";
                $salida .= "                 </table>\n";
                $salida .= "                 <table width=\"90%\" align=\"center\" BORDER='1' cellspacing='0'>\n";
                $salida .= "                    <tr>\n";
                $salida .= "                       <td $ESTILO2 width=\"2%\" align=\"center\">\n";
                $salida .= "                       <a title='EPS TIPO AFILIADO'>";
                $salida .= "                        T";
                $salida .= "                       </a>";
                $salida .= "                       </td>\n";
                $salida .= "                       <td $ESTILO2 width=\"8%\" align=\"center\">\n";
                $salida .= "                       <a title='ESTADO DEL AFILIADO'>";
                $salida .= "                        ESTADO";
                $salida .= "                       </a>";
                $salida .= "                       </td>\n";
                $salida .= "                       <td $ESTILO2 width=\"14%\" align=\"center\">\n";
                $salida .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
                $salida .= "                         IDENTIFICACION";
                $salida .= "                       </a>";
                $salida .= "                       </td>\n";
                $salida .= "                       <td $ESTILO2 width=\"43%\" align=\"center\">\n";
                $salida .= "                       <a title='NOMBRE DEL AFILIADO'>";
                $salida .= "                          NOMBRE";
                $salida .= "                       </a>";
                $salida .= "                       </td>\n";
                $salida .= "                       <td $ESTILO2 width=\"18%\" align=\"center\">\n";
                $salida .= "                       <a title='ESTAMENTO'>";
                $salida .= "                        ESTAMENTO";
                $salida .= "                       </a>";
                $salida .= "                       </td>\n";
                $salida .= "                       <td $ESTILO2 width=\"15%\" align=\"center\">\n";
                $salida .= "                       <a title='FECHA DE AFILIACION'>";
                $salida .= "                          FECHA DE AFILIACION";
                $salida .= "                       </a>";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                for($i=0;$i<count($afiliados);$i++)
                {   
                    $salida .= "                    <tr>\n";
                    $salida .= "                       <td align=\"center\" $ESTILO3>\n";
                    $salida .= "                       <a title='".$afiliados[$i]['descripcion_eps_tipo_afiliado']."'>";
                    $salida .= "                       ".$afiliados[$i]['eps_tipo_afiliado_id'];
                    $salida .= "                       </a>\n";
                    $salida .= "                       </td>\n";
                    $salida .= "                      <td align=\"center\" $ESTILO3>\n";
                    $salida .= "                       <a title='".$afiliados[$i]['descripcion_estado']."'>";
                    $salida .= "                       ".$afiliados[$i]['estado_afiliado_id'];
                    $salida .= "                      </a>\n";
                    $salida .= "                        - ";
                    $salida .= "                       <a title='".$afiliados[$i]['descripcion_subestado']."'>";
                    $salida .= "                       ".$afiliados[$i]['subestado_afiliado_id'];
                    $salida .= "                       </a>\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\" $ESTILO3>\n";
                    $salida .= "                       ".$afiliados[$i]['afiliado_tipo_id']."-".$afiliados[$i]['afiliado_id'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       ".$afiliados[$i]['nombre_afiliado'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       <a title='".$afiliados[$i]['descripcion_subestado']."'>";
                    $salida .= "                       ".$afiliados[$i]['descripcion_estamento']."&nbsp;";
                    $salida .= "                       </a>\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"center\">\n";
                    $salida .= "                       ".$afiliados[$i]['fecha_afiliacion'];
                    $salida .= "                      </td>\n";       
                    $salida .= "                    </tr>\n";
                } 
                $salida .= "                    </table>\n";
                $salida .= "                    <br>\n";
            }
                

                    $salida .= "     <table align='center' border='0' width='50%'>";
                    $salida .= "       <tr>\n";
                    $salida .= "         <td width='50%' align=\"left\" $ESTILO20>";
                    $salida .= "           USUARIO :";
                    IncludeClass('Autocarga');
                    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
                    $nombre=$afi->GetNombreUsuario(UserGetUID());
                    //var_dump($nombre);
                    $salida .= "       ".$nombre[0]['nombre']."&nbsp;";
                    $salida .= "      </td>\n";
                    $salida .= "         <td width='50%' align=\"left\" $ESTILO20>";
                    $salida .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
                    $salida .= "      </td>\n";
                    $salida .= "     </tr>\n";
                    $salida .= "    </table>\n";
            return $salida;
        }

	}

?>
