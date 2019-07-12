<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReportePorConsulta.report.php,v 1.2 2009/09/23 21:42:42 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
  
  /**
  * Clase Reporte: ReportePorConsulta_report 
  * reporte con los datos de los afiliados que consulta el buscador de afiliados.
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */
  //
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
            IncludeClass('AutoCarga');
            $datos=SessionGetVar("BUSQUEDA");
            $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
            $afiliados = $afi->GetAfiliados($datos, $count=false, $limit=false, $offset=false,true);
            $contador_registros=SessionGetVar("CONTADOR");
            $ESTILO3 = " class=\"normal_10\" ";
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
                $salida .= "<table width=\"90%\" align=\"center\" BORDER='1' cellspacing='0'>\n";
                $salida .= "  <tr align=\"center\" class=\"label\">\n";
                $salida .= "    <td width=\"10%\" >TIPO</td>\n";
                $salida .= "    <td width=\"22%\">ESTADO</td>\n";
                $salida .= "    <td width=\"35%\" colspan=\"2\">AFILIADO</td>\n";
                $salida .= "    <td width=\"18%\">ESTAMENTO</td>\n";
                $salida .= "    <td width=\"15%\">FECHA DE AFILIACION</td>\n";
                $salida .= "    </tr>\n";
                for($i=0;$i<count($afiliados);$i++)
                {   
                  $salida .= "  <tr class=\"normal_10\">\n";
                  $salida .= "    <td>".$afiliados[$i]['descripcion_eps_tipo_afiliado']."</td>\n";
                  $salida .= "    <td>\n";
                  $salida .= "      ".$afiliados[$i]['descripcion_estado']." - ".$afiliados[$i]['descripcion_subestado'];
                  $salida .= "    </td>\n";
                  $salida .= "    <td width=\"12%\">\n";
                  $salida .= "      ".$afiliados[$i]['afiliado_tipo_id']."-".$afiliados[$i]['afiliado_id']."";
                  $salida .= "    </td>\n";
                  $salida .= "    <td>".$afiliados[$i]['nombre_afiliado']."</td>\n";
                  $salida .= "    <td>\n";
                  $salida .= "      ".$afiliados[$i]['descripcion_estamento']."&nbsp;";
                  $salida .= "    </td>\n";
                  $salida .= "    <td align=\"center\">\n";
                  $salida .= "      ".$afiliados[$i]['fecha_afiliacion'];
                  $salida .= "    </td>\n";       
                  $salida .= "  </tr>\n";
                } 
                $salida .= "                    </table>\n";
                $salida .= "                    <br>\n";
            }
                

                    $salida .= "     <table align='center' border='0' width='50%'>";
                    $salida .= "       <tr>\n";
                    $salida .= "         <td width='50%' align=\"left\" $ESTILO20>";
                    $salida .= "           USUARIO :";
                    
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