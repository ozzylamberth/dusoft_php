<?php

// $Id: hc_ConfirmarCierreHC_HTML.php,v 1.2 2005/03/08 23:22:10 tizziano Exp $

class ConfirmarCierreHC_HTML extends ConfirmarCierreHC
{

    function ConfirmarCierreHC_HTML()
    {
        $this->ConfirmarCierreHC();//constructor del padre
           return true;
    }


    function frmConsulta()
    {
        //$this->frmForma();
        return true;
    }




    function frmForma($actionSI,$actionNO)
    {

        $this->salida .= "<FORM action='$actionSI' method='POST' name='FrmConfirmarCierre'>\n";
        $this->salida .= "    <TABLE width='80%' cellspacing='2' border='0' cellpadding='8' align='center'>\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "        <td align='center' colspan='2' class=titulo3>\n";
        $this->salida .= "        ¿Cerrar la evolución actual?\n";
        $this->salida .= "        </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "        <td align='right' width='50%'><INPUT type='submit' value='  SI  '></td>\n";
        $this->salida .= "        <td align='left' width='50%'><INPUT type='button' value='  NO  ' onclick=\"document.location='$actionNO'\"></td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    </TABLE>\n";
        $this->salida .= "</FORM>\n";

        return true;
    }

}

?>
