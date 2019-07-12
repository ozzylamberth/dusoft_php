<?php

/**
* $Id: InterfaseCG1.class.php,v 1.13 2006/05/12 22:41:43 alex Exp $
*/

/**
* Clase de control para la generación de la interfase con CG-UNO
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.13 $
* @package SIIS
*/
class InterfaseCG1
{

   /**
    * Constructor
    *
    * @return boolean
    * @access public
    */
    function InterfaseCG1()
    {
        if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1_Financiero.class.php"))
        {
            die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
        }

        if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1_v_5_0_Financiero.class.php"))
        {
            die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1_v_5_0_Financiero.class.php' NO SE ENCUENTRA"));
        }
        if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1_v_8_5_Financiero.class.php"))
        {
            die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1_v_8_5_Financiero.class.php' NO SE ENCUENTRA"));
        }
        return true;
    }

    function GetNewInterfase()
    {
        if(!is_object($objInterfase))
        {
            unset($objInterfase);
        }

        $objInterfase= new InterfaseCG1_v_8_5_Financiero;
        return $objInterfase;
    }


}//fin de la class


?>
