<?php

/* * ************************************************************************************
 * $Id: app_Cg_Documentos_user.php,v 1.1 2006/11/02 20:56:33 jgomez Exp $
 *
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS-FI
 *
 * $Revision: 1.1 $
 *
 * @autor Jaime Gomez

 * ************************************************************************************* */
IncludeClass('DocumentosSQL', '', 'app', 'Cg_Documentos');

class app_Cg_Documentos_user extends classModulo {

    /**
     * @var $action Variable donde se guardan los action de las formsa
     * */
    var $action = array();

    /**
     */
    function app_Cg_Documentos_user() {
        
    }

    /*     * ********************************************************************************
     * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
     * se averiguan los tipos de documentos
     * ********************************************************************* */

    function CrearElementos() {
        //$empresa_id="01";
        if (!SessionIsSetVar("EMPRESA")) {
            //print_r($_REQUEST);
            SessionSetVar("rutaImagenes", GetThemePath());
            SessionSetVar("EMPRESA", $_REQUEST['datos']['empresa_id']);
            SessionSetVar("NOMBRE_EMPRESA", $_REQUEST['datos']['razon_social']);
            //print_r($_REQUEST);
        } else {
            SessionDelVar("Creardoc");
        }
    }

    function MostrarDocus() {
        $consulta = new DocumentosSQL();
        $this->TipsDocumentos = $consulta->ListarTiposDocumentos();
    }

    function SubMenu() {
        if (!SessionIsSetVar("Creardoc"))
            SessionSetVar("Creardoc", $_REQUEST["Docus"]);
        //$this->actionOption21=ModuloGetURL('app','Cg_Documentos','user','ListaDocumentos'); 
        $this->Datos = SessionGetVar("Creardoc");
    }

}

?>