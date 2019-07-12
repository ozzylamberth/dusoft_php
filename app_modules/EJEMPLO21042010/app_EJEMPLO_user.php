<?php

class app_EJEMPLO_user extends classModulo
{

    function main()
    {
        $this->forma1($var);
        return true;
    }
    

    

/*     function ResumenEpicrisis_HTML($ingreso)
    {
        $this->ResumenEpicrisis();
        $this->ingreso=$ingreso;
        return true;
    }
*/
    function Revisar()
    {
    /*    global $VISTA;
        //include_once $_ROOT.'includes/enviroment.inc.php';//se incluye la clase del buscador.....
        if(!IncludeFile("classes/ImpresionHistoria/ImpresionHistoria.class.php"))
        {
            $this->error = "No se pudo cargar el Resumen de Epicrisis";
            $this->mensajeDeError = "El archivo de vistas 'classes/ResumenEpicrisis/ResumenEpicrisis.class.php' no existe.";
            return false;
        }//se incluye la clase del buscador.....
        $fileName ="classes/ImpresionHistoria/$VISTA/ImpresionHistoria.$VISTA.php";
        if(!IncludeFile($fileName))
        {
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
            return false;
        }

        //echo $_REQUEST['ingreso'];
        //$this->ReturnMetodoExterno('app','ResumenEpicrisis','user','Iniciar',array('ingreso'=>$_REQUEST['ingreso']));


        $clase="ImpresionHistoria_$VISTA";
        $ResumenEpicrisis = new $clase('3956');//$_REQUEST['ingreso']
        $ResumenEpicrisis->IniciarImprimir();
        $ResumenEpicrisis->Iniciar();
        echo $ResumenEpicrisis->salida;*/
        /*$reporte= new GetReports();
        $mostrar=$reporte->GetJavaReportHC('4029',array('rpt_name'=>'diego','rpt_dir'=>'historias_clinicas','rpt_rewrite'=>FALSE));
        $funcion=$reporte->GetJavaFunction();*/
        return true;
    }


    //javascript:WindowPrinter0002()
}//end of class

?>
