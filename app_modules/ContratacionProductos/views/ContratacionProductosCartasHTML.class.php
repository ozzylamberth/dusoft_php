 <?php
   /**
  * @package IPSOFT-SIIS
  * @version $Id: ContratacionProductosCartasHTML.class.php,v 1.1 2009/11/13 18:22:49 sandra Exp $Revision: 1.1 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres
  */

 IncludeClass("InsertatImagenSQL");
 
  
  class ContratacionProductosCartasHTML
  {
     /**
     * Constructor de la clase
     */
     
    function  ContratacionProductosCartasHTML(){}
	
	
		function FormaSubir($action,$tipood,$Noid,$nome)
		{
    		$html  = ThemeAbrirTabla(" SUBIR IMAGENES"); 
			$html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$action['imagensu']."\"  >";
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_30AN\" align=\"center\">S E L E C C I N A R  -  I M A G E N  </legend>\n";
			$html .= "<table  width=\"70%\" align=\"center\" border=\"1\" >\n";
			$html .= "  <tr  class=\"modulo_list_claro\" align=\"center\" >\n";
			$html .= "      <td  colspan=\"10\" > <b>IDENTIFICAÒN :   ".$tipood." ".$Noid." </b> </td>\n";
			$html .= " <br>";
			$html .= "      <td colspan=\"10\" > <b>PROVEEDOR :   ".$nome." </b> </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n"; 
			$html .= " <br>";
			$html .= " <table width=\"100\" border=\"1\" align=\"center\"  >";
			$html .= " <tr class=\"modulo_list_claro\" > ";
			$html .= " <td class=\"modulo_list_claro\"  ><B>IMAGEN </B> ";
			$html .= "    </td>\n";
			$html .= "    <td>\n";
			$html .= "  <input type=\"file\" name=\"archivo\" size=\"30\" style=\"border: 1px solid #7F9DB7;\" >";
			$html .= "    </td>\n";
			$html .= " </tr>  ";
			$html .= "</table>\n"; 
			$html .= " <table width=\"50\" border=\"0\" align=\"center\" class=\"modulo_list_title\" >";
			$html .= "		<tr>\n";
			$html .= "			<td align=\center\" >\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"Guardar Imagen\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";  
			$html .= "</fieldset><br>\n";
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['continuar']." \" class=\"label_error\">\n";
			$html .= "        CONTINUAR\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/**
		* Funcion permite  Mostar un mensaje de acuerdo al proceso de insertar una imagen a la base de datos
		* @param array $action vector que contiene los link de la aplicacion
		* @return string $html retorna la cadena con el codigo html de la pagina 
	*/
		function FormaMensajeIngresocartas($action, $msg1=null,$msg1=null,$datos)
		{
			$html  = ThemeAbrirTabla("INFORMACIÒN DEL PROCESO");
			$html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$action['parametros']."\"  >";
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\">M E N S A J E </legend>\n";
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$msg1."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= " <br>";
			$html .= " <table width=\"50\" border=\"0\" align=\"center\" class=\"modulo_list_title\" >";
			$html .= "		<tr>\n";
			$html .= "			<td align=\center\" >\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"PARAMETRIZAR\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";  
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= "</fieldset><br>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    }
?>