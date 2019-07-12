<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Estudiantes.report.php,v 1.2 2009/09/24 16:41:39 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  /**
  * Clase Reporte: Estudiantes 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  IncludeClass("AutoCarga");
  IncludeClass("ConexionBD");
  class Estudiantes_report 
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
    function Estudiantes_report($datos=array())
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
        $titulo .= "<b $estilo>REPORTE DE ESTUDIANTES</b>";
    
        $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
                          'subtitulo'=>"<b $estilo>UNIVERSIDAD DEL VALLE </b>",'logo'=>'logocliente.png','align'=>'left'));//logocliente.png
        return $Membrete;
    }
    /**
    *
    **/
    function CrearReporte()
    {
      $afi = AutoCarga::factory("Listados", "", "app","UV_AfiliadosEstudiantes");
      $afiliados = $afi->ObtenerListaBeneficiarios($this->datos ,null,1);
      
      $html .= "  <table width=\"100%\" border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"center\" rules=\"all\">\n";
      $html .= "	  <tr class=\"label\" align=\"center\">\n";
      $html .= "		  <td width=\"%\"   rowspan=\"2\" colspan=\"2\">AFILIADO</td>\n";
      $html .= "			<td width=\"10%\"  rowspan=\"2\">EDAD</td>\n";
      $html .= "			<td width=\"14%\" rowspan=\"2\">DIRECCION</td>\n";
      $html .= "			<td width=\"14%\" colspan=\"2\">TELEFONO</td>\n";		      
     // $html .= "			<td width=\"18%\" colspan=\"6\">ESTUDIANTE</td>\n";
      $html .= "			<td width=\"10%\" rowspan=\"2\" colspan=\"2\">PERIODO COBERTURA</td>\n";
      $html .= "			<td width=\"10%\" rowspan=\"2\">ESTAMENTO</td>\n";
		
      $html .= "		</tr>\n";
      $html .= "	  <tr class=\"label\" align=\"center\">\n";
      $html .= "			<td width=\"7%\">RES.</td>\n";
      $html .= "			<td width=\"7%\">MOVIL</td>\n";      
      /*$html .= "			<td width=\"2%\" >U</td>\n";
      $html .= "			<td width=\"2%\" >P</td>\n";
      $html .= "			<td width=\"2%\" >F</td>\n";
      $html .= "			<td width=\"2%\" >N</td>\n";
      $html .= "			<td width=\"2%\" >T</td>\n";
      $html .= "			<td width=\"%\">CODIGO</td>\n";*/

      $html .= "		</tr>\n";
      
      $estuv = ""; 
      foreach($afiliados as $key => $afiliado)
      {
        //($afiliado['codigo_estudiante'])? $estuv = "X": $estuv = "&nbsp;"; 
        ($afiliado['periodo'])? $c = 2:$c=1;
        
        $html .= "		<tr class=\"normal10\">\n";
        $html .= "		  <td rowspan=\"".$c."\" width=\"10%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
        $html .= "		  <td rowspan=\"".$c."\" >".$afiliado['primer_apellido']." ".$afiliado['segundo_apellido']." ".$afiliado['primer_nombre']." ".$afiliado['segundo_nombre']."</td>\n";
        $html .= "		  <td rowspan=\"".$c."\" align=\"center\">".$afiliado['edad_afiliado']." Años</td>\n";
        $html .= "		  <td rowspan=\"".$c."\" >".$afiliado['direccion_residencia']."&nbsp;</td>\n";
        $html .= "		  <td rowspan=\"".$c."\" >".$afiliado['telefono_residencia']."&nbsp;</td>\n";
        $html .= "		  <td rowspan=\"".$c."\" >".$afiliado['telefono_movil']."&nbsp;</td>\n";
        /*$html .= "		  <td rowspan=\"".$c."\" align=\"center\" >".$estuv."</td>\n";
        $html .= "		  <td rowspan=\"".$c."\" align=\"center\" >";
        if($estuv == "X")
        {
          if($afiliado['sw_estudiante_postgrado'] == '1')
            $html .= "X";
          else
            $html .= "&nbsp;";
        }
        else
          $html .= "  &nbsp;";
        $html .= "		  </td>\n";
        $html .= "		  <td rowspan=\"".$c."\" align=\"center\" >";
        if($estuv == "X")
        {
          if($afiliado['sw_matricula_financiera'] == '1')
            $html .= "X";
          else
            $html .= "&nbsp;";
        }
        else
          $html .= "  &nbsp;";
        $html .= "      </td>\n";          
        $html .= "		  <td rowspan=\"".$c."\" align=\"center\" >";
        if($estuv == "X")
        {
          if($afiliado['sw_estudiante_nocturno'] == '1')
            $html .= "X";
          else
            $html .= "&nbsp;";
        }
        else
          $html .= "  &nbsp;";
        $html .= "      </td>\n";					

        $html .= "		  <td rowspan=\"".$c."\" align=\"center\">";
        if($estuv == "X")
        {
          if($afiliado['sw_estudiante_trabaja'] == '1')
            $html .= "X";
          else
            $html .= "&nbsp;";
        }
        else
          $html .= "  &nbsp;";
        $html .= "      </td>\n";		
        $html .= "		  <td rowspan=\"".$c."\" >".$afiliado['codigo_estudiante']."&nbsp;</td>\n";
        */
        if($afiliado['periodo'])
        {
          $f = explode("-",$afiliado['cobertura_fecha_fin']);
          $t1 = mktime(0,0,0,$f[1],$f[2],$f[0]);
          $t2 = mktime(0,0,0,date("m"),date("d"),date("Y"));
          $dd = abs(($t1 - $t2)/(60 * 60 * 24));

          $html .= "		  <td align=\"center\" colspan=\"2\">";

          if($afiliado['periodo'] == '1')
            $html .= "      ACTIVO - ".$dd." DIAS\n";
          elseif($afiliado['periodo'] == '2')
            $html .= "      VENCIDO HACE ".$dd." DIAS\n";
        
          $html .= "      </td>\n";
        }
        else
        {
          $html .= "		  <td rowspan=\"".$c."\" align=\"center\" colspan=\"2\">SIN PERIODO</td>\n";
        }
        
        $html .= "		  <td rowspan=\"".$c."\" >".$afiliado['descripcion_estamento']."</td>\n";
        $html .= "    </tr>\n";
        if($afiliado['periodo'])
        { 
          $html .= "		<tr class=\"normal10\">\n";
          $html .= "		  <td align=\"center\" >".$afiliado['inicio']."</td>\n";
          $html .= "		  <td align=\"center\" >".$afiliado['fin']."</td>\n";
          $html .= "    </tr>";
        }      
      }
      $html .= "    </table>";
      
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:8.5px\"";
     /*
      $html .= "  <table width=\"65%\">\n";
      $html .= "    <tr ".$est.">\n";
      $html .= "      <td><b>U:</b></td>\n";
      $html .= "      <td>Estudiante Universidad&nbsp;</td>\n";      
      $html .= "      <td><b>P:</b></td>\n";
      $html .= "      <td>Estudiante De Postgrado&nbsp;</td>\n";      
      $html .= "      <td><b>F:</b></td>\n";
      $html .= "      <td>Pago Financiero&nbsp;</td>\n";      
      $html .= "      <td><b>N:</b></td>\n";
      $html .= "      <td>Estudiante Universidad Nocturno&nbsp;</td>\n";
      $html .= "      <td><b>T:</b></td>\n";
      $html .= "      <td>Estudiante Trabaja&nbsp;</td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table><br>\n";*/
      
      $usuario = $afi->ObtenerUsuario($this->datos['usuario_id']);
      
      $html .= "	<table ".$est." width=\"100%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td width=\"5%\"><b>Imprimio:</b></td>\n";
			$html .= "			<td width=\"70%\">".$usuario['nombre']."</td>\n";
			$html .= "			<td width=\"16%\"><b>Fecha Impresion:</b></td>\n";
			$html .= "			<td width=\"%\">".date("d/m/Y")."</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
      
      return $html;
    }
	}
?>