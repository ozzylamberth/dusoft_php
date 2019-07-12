<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: CodificacionProductos_MenuHTML.class.php,v 1.4 2010/01/19 13:23:00 mauricio Exp $ 
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Clase Vista: CodificacionProductos_MenuHTML
 * Clase Contiene Metodos para el despliegue de Menús del Módulo
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.4 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
class CodificacionProductos_MenuHTML {

    /**
     * Constructor de la clase
     */
    function CodificacionProductos_MenuHTML()
    {
        
    }

    /**
     * @param array $action Vector de links de la aplicaion
     * @param array $partes_del_cuerpo_afectado 
     * @param array $tipos_lesion
     * @param array $Agentes_Accidentes
     * @param array $Formas_Accidente
     * @param array $tipos_Accidente.
     * @return String $html con la forma para diligenciar un accidente de trabajo.
     */
    function Menu($action, $sw, $EmpresaId)
    {
        //print_r($_REQUEST);
        $accion = $action['volver'];
        $html = ThemeAbrirTabla('CODIFICACION DE PRODUCTOS');
        $html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
        $html .= "  <tr><td>";
        $html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr class=\"modulo_table_list_title\">";
        $html .= "      <td align=\"center\">";
        $html .= "      MENÚ";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\">";
        $html .= "      <a href=\"" . ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'CrearMoleculas') . "&datos[sw_tipo_empresa]=" . $sw . "&datos[empresa_id]=" . $EmpresaId . "\">CREAR MOLECULA (SubClases)</a>";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\">";
        $html .= "      <a href=\"" . ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'CrearTiposInsumos') . "&datos[sw_tipo_empresa]=" . $sw . "&datos[empresa_id]=" . $EmpresaId . "\">CREAR TIPOS INSUMOS (SubClases)</a>";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\">";
        $html .= "      <a href=\"" . ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'CrearLaboratorios') . "&datos[sw_tipo_empresa]=" . $sw . "&datos[empresa_id]=" . $EmpresaId . "\">CREAR LABORATORIOS (Clases)</a>";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\">";
        $html .= "      <a href=\"" . ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'Clasificacion_Productos') . "&datos[sw_tipo_empresa]=" . $sw . "&datos[empresa_id]=" . $EmpresaId . "\">CLASIFICACION GENERAL DE LOS PRODUCTOS</a>";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\">";
        $html .= "      <a href=\"" . ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'AsignarEspecialidadesAMedicamentos') . "&datos[sw_tipo_empresa]=" . $sw . "&datos[empresa_id]=" . $EmpresaId . "\">ASIGNAR ESPECIALIDADES A MEDICAMENTOS POR EMPRESA Y DEPTO</a>";
        $html .= "      </td>";
        $html .= "      </tr>";
        
        
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\">";
        $html .= "      <a href=\"" . ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'ConsultarAuditoriaProductos') . "&datos[sw_tipo_empresa]=" . $sw . "&datos[empresa_id]=" . $EmpresaId . "\">CONSULTAR AUDITORIA PRODUCTOS</a>";
        $html .= "      </td>";
        $html .= "      </tr>";



        $html .= "      </table>";
        $html .= "  </td></tr>";
        $html .= ' 	<form name="forma" action="' . $action['volver'] . '" method="post">';
        $html .= "  <tr>";
        $html .= "  <td align=\"center\"><br>";
        $html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
        $html .= "  </td>";
        $html .= "  </form>";
        $html .= "  </tr>";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();

        return $html;
    }

}

?>