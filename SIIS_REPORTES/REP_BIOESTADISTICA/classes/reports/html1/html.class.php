<?php
// pos.class.php  07/22/2004
// -------------------------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 IPSOFT SA.
// Email: mail@ipsoft-sa.com
// -------------------------------------------------------------------------------------
// Autor: Alexander Giraldo  -- alexgiraldo@ipsoft-sa.com
// Proposito del Archivo: Clase para la generacion de reportes POS
// -------------------------------------------------------------------------------------

class html_reports_class
{
    var $opciones;
    var $rpt_name;
    var $rpt_HTML;
    var $error;
    var $mensajeDeError;
    var $dir_spool;
    var $dir_cache;
    var $domain;
    var $reporte_html;
    var $reporte_pdf;

    function html_reports_class()
    {
        $this->opciones=array();
        $this->rpt_name='';
        $this->rpt_HTML='';
        $this->error='';
        $this->mensajeDeError='';
        $this->dir_spool=GetVarConfigAplication('DirSpool');
        $this->dir_cache=GetVarConfigAplication('DirCache');
        $this->domain=GetVarConfigAplication('DOMINIO_SIIS');
        $this->reporte_html='';
        $this->reporte_pdf='';
        return true;
    }

    function GetError()
    {
        return $this->error;
    }

    function MensajeDeError()
    {
        return $this->mensajeDeError;
    }

    //ESTO ES NUEVO/*-----------------------------------------*/
    /*INCLUSION DE IMPRESION PARA HC  */
    //ESTO ES NUEVO/*-----------------------------------------*/

    function GetReportHTML_HC($ingreso,$opciones=array())
    {
        if(!empty($opciones))
        {
            $this->opciones=$opciones;
        }

        global $_ROOT;

        $archivo_existente =  $_ROOT . $opciones['rpt_dir'] . "/" . $opciones['rpt_name'] . ".html";

        if(empty($ingreso))
        {
            $this->error = "El Existe el Ingreso";
            $this->mensajeDeError = "No puede ser creado el reporte.";
            return false;
        }

        if(!empty($opciones['rpt_name']) && !empty($opciones['rpt_dir']) && !$opciones['rpt_rewrite'] && file_exists($archivo_existente) && is_readable($archivo_existente))
        {
            $this->reporte_html=$archivo_existente;
            $archivo_existente =  $_ROOT . $opciones['rpt_dir'] . "/" . $opciones['rpt_name'] . ".pdf";
            if(file_exists($archivo_existente) && is_readable($archivo_existente))
            {
                $this->reporte_pdf=$archivo_existente;
            }
        }
        else
        {

            global $VISTA;
            //include_once $_ROOT.'includes/enviroment.inc.php';//se incluye la clase del buscador.....
            if(!IncludeFile("classes/ImpresionHistoria/ImpresionHistoria.class.php"))
            {
                $this->error = "No se pudo cargar el Resumen de Epicrisis";
                $this->mensajeDeError = "El archivo de vistas 'classes/ImpresionHistoria/ImpresionHistoria.class.php' no existe.";
                return false;
            }//se incluye la clase del buscador.....
            $rpt_file ="classes/ImpresionHistoria/$VISTA/ImpresionHistoria.$VISTA.php";
            if(!IncludeFile($rpt_file))
            {
                $this->error = "No se pudo cargar el Modulo";
                $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
                return false;
            }

            $rpt_class="ImpresionHistoria_$VISTA";

            if(!class_exists($rpt_class)){
                $this->error = "No existe la clase";
                $this->mensajeDeError = "La clase $rpt_class no existe.";
                return false;
            }

            $rpt = new $rpt_class($ingreso);
            if(!is_object($rpt)){
                $this->error = "Error con el reporte";
                $this->mensajeDeError = "No es object.";
                return false;
            }

            $rpt->IniciarImprimir();
            $this->rpt_HTML = $rpt->GetImpresion();


            if($this->rpt_HTML === false)
            {
                $this->error = "Reporte Vacio";
                $this->mensajeDeError = "No retorno datos.";
                return false;
            }

            if(method_exists($rpt,'GetMembrete')){
                $rpt_membrete = $rpt->GetMembrete();
                if(is_array($rpt_membrete)){
                    if($rpt_membrete['file']){
                        $file='reports/HTML/MEMBRETES/' . $rpt_membrete['file'] . ".php";
                        if(IncludeFile($file)){
                            $clase= $rpt_membrete['file'] . "_Membrete";
                            if(!class_exists($clase)){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "La clase $clase del membrete no existe.";
                                return false;
                            }
                            $membrete_obj = new $clase($rpt_membrete['datos_membrete']);
                            if(!is_object($membrete_obj)){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "No es object.";
                                return false;
                            }
                            if(!method_exists($membrete_obj,'GetMembrete')){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "El metodo GetMembrete no existe.";
                                return false;
                            }
                            $membrete = $membrete_obj->GetMembrete();
                            unset($membrete_obj);
                            $this->rpt_HTML = $membrete . $this->rpt_HTML;
                        }
                    }else{
                        $this->rpt_HTML = $this->GetMembreteGenerico($rpt_membrete['datos_membrete']) . $this->rpt_HTML;
                    }
                }
            }
            unset($rpt);

            $this->rpt_HTML = $this->Open_Tags_Html($title) . $this->rpt_HTML . $this->Close_Tags_Html();
            $this->rpt_HTML = preg_replace(':<img (.*?)src=["\']((?!http\://).*?)["\']:i', '<img \\1 src="'.$this->domain.'\\2"', $this->rpt_HTML);
            $this->reporte_html = $this->CrearReporteHTML();
            if(!$this->reporte_html)
            {
                return false;
            }
        }//fin del else de archivo no existente
        return $this->reporte_html;
    }


    //ESTO ES NUEVO/*-----------------------------------------*/
    /*INCLUSION DE IMPRESION PARA Historia Clinica Jaime  */
    //ESTO ES NUEVO/*-----------------------------------------*/

    function GetReportHTML_HistoriaClinica($evolucion,$opciones=array())
    {
        if(!empty($opciones))
        {
            $this->opciones=$opciones;
        }

        global $_ROOT;

        $archivo_existente =  $_ROOT . $opciones['rpt_dir'] . "/" . $opciones['rpt_name'] . ".html";

        if(empty($evolucion))
        {
            $this->error = "El Existe la Evolucion";
            $this->mensajeDeError = "No puede ser creado el reporte.";
            return false;
        }

        if(!empty($opciones['rpt_name']) && !empty($opciones['rpt_dir']) && !$opciones['rpt_rewrite'] && file_exists($archivo_existente) && is_readable($archivo_existente))
        {
            $this->reporte_html=$archivo_existente;
            $archivo_existente =  $_ROOT . $opciones['rpt_dir'] . "/" . $opciones['rpt_name'] . ".pdf";
            if(file_exists($archivo_existente) && is_readable($archivo_existente))
            {
                $this->reporte_pdf=$archivo_existente;
            }
        }
        else
        {

            global $VISTA;
            //include_once $_ROOT.'includes/enviroment.inc.php';//se incluye la clase del buscador.....
            if(!IncludeFile("classes/ResumenHC/ResumenHC.class.php"))
            {
                $this->error = "No se pudo cargar el Resumen de la Historia Clinica";
                $this->mensajeDeError = "El archivo de vistas 'classes/ResumenHC/ResumenHC.class.php' no existe.";
                return false;
            }//se incluye la clase del buscador.....
            $rpt_file ="classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php";
            if(!IncludeFile($rpt_file))
            {
                $this->error = "No se pudo cargar el Modulo";
                $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
                return false;
            }

            $rpt_class="ResumenHC_$VISTA";

            if(!class_exists($rpt_class)){
                $this->error = "No existe la clase";
                $this->mensajeDeError = "La clase $rpt_class no existe.";
                return false;
            }

            $rpt = new $rpt_class($evolucion);
            if(!is_object($rpt)){
                $this->error = "Error con el reporte";
                $this->mensajeDeError = "No es object.";
                return false;
            }

            $rpt->IniciarImprimir();
            $this->rpt_HTML = $rpt->GetImpresion();


            if($this->rpt_HTML === false)
            {
                $this->error = "Reporte Vacio";
                $this->mensajeDeError = "No retorno datos.";
                return false;
            }

            if(method_exists($rpt,'GetMembrete')){
                $rpt_membrete = $rpt->GetMembrete();
                if(is_array($rpt_membrete)){
                    if($rpt_membrete['file']){
                        $file='reports/HTML/MEMBRETES/' . $rpt_membrete['file'] . ".php";
                        if(IncludeFile($file)){
                            $clase= $rpt_membrete['file'] . "_Membrete";
                            if(!class_exists($clase)){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "La clase $clase del membrete no existe.";
                                return false;
                            }
                            $membrete_obj = new $clase($rpt_membrete['datos_membrete']);
                            if(!is_object($membrete_obj)){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "No es object.";
                                return false;
                            }
                            if(!method_exists($membrete_obj,'GetMembrete')){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "El metodo GetMembrete no existe.";
                                return false;
                            }
                            $membrete = $membrete_obj->GetMembrete();
                            unset($membrete_obj);
                            $this->rpt_HTML = $membrete . $this->rpt_HTML;
                        }
                    }else{
                        $this->rpt_HTML = $this->GetMembreteGenerico($rpt_membrete['datos_membrete']) . $this->rpt_HTML;
                    }
                }
            }
            unset($rpt);

            $this->rpt_HTML = $this->Open_Tags_Html($title) . $this->rpt_HTML . $this->Close_Tags_Html();
            $this->rpt_HTML = preg_replace(':<img (.*?)src=["\']((?!http\://).*?)["\']:i', '<img \\1 src="'.$this->domain.'\\2"', $this->rpt_HTML);
            $this->reporte_html = $this->CrearReporteHTML();
            if(!$this->reporte_html)
            {
                return false;
            }
        }//fin del else de archivo no existente
        return $this->reporte_html;
    }


    //metodo que me retorna el nombre del archivo - reporte.
    function  GetReportHTML($tipo,$modulo,$reporte,$datos=array(),$opciones=array())
    {
        if(!empty($opciones))
        {
            $this->opciones=$opciones;
        }

        global $_ROOT;

        $archivo_existente =  $_ROOT . $opciones['rpt_dir'] . "/" . $opciones['rpt_name'] . ".html";

        if(!empty($opciones['rpt_name']) && !empty($opciones['rpt_dir']) && !$opciones['rpt_rewrite'] && file_exists($archivo_existente) && is_readable($archivo_existente))
        {
            $this->reporte_html=$archivo_existente;
            $archivo_existente =  $_ROOT . $opciones['rpt_dir'] . "/" . $opciones['rpt_name'] . ".pdf";
            if(file_exists($archivo_existente) && is_readable($archivo_existente))
            {
                $this->reporte_pdf=$archivo_existente;
            }
        }
        else
        {

            if(empty($tipo) || empty($modulo)){
                $rpt_file = "reports/HTML/" . $reporte . ".php";
            }else{
                $rpt_file = "$tipo"."_modules/$modulo/reports/html/$reporte".".report.php";
            }

            if (!IncludeFile($rpt_file)) {
                $this->error = "No existe el reporte";
                $this->mensajeDeError = "El archivo $rpt_file no existe.";
                return false;
            }

            $rpt_class = $reporte."_report";

            if(!class_exists($rpt_class)){
                $this->error = "No existe la clase";
                $this->mensajeDeError = "La clase $rpt_class no existe.";
                return false;
            }

            $rpt = new $rpt_class($datos);
            if(!is_object($rpt)){
                $this->error = "Error con el reporte";
                $this->mensajeDeError = "No es object.";
                return false;
            }

            $this->rpt_HTML = $rpt->CrearReporte();
            if(empty($this->rpt_HTML)){
                $this->error = "Reporte Vacio";
                $this->mensajeDeError = "No retorno datos.";
                return false;
            }

            if(method_exists($rpt,'GetMembrete')){
                $rpt_membrete = $rpt->GetMembrete();
                if(is_array($rpt_membrete)){
                    if($rpt_membrete['file']){
                        $file='reports/HTML/MEMBRETES/' . $rpt_membrete['file'] . ".php";
                        if(IncludeFile($file)){
                            $clase= $rpt_membrete['file'] . "_Membrete";
                            if(!class_exists($clase)){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "La clase $clase del membrete no existe.";
                                return false;
                            }
                            $membrete_obj = new $clase($rpt_membrete['datos_membrete']);
                            if(!is_object($membrete_obj)){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "No es object.";
                                return false;
                            }
                            if(!method_exists($membrete_obj,'GetMembrete')){
                                $this->error = "Error al incluir Membrete";
                                $this->mensajeDeError = "El metodo GetMembrete no existe.";
                                return false;
                            }
                            $membrete = $membrete_obj->GetMembrete();
                            unset($membrete_obj);
                            $this->rpt_HTML = $membrete . $this->rpt_HTML;
                        }
                    }else{
                        $this->rpt_HTML = $this->GetMembreteGenerico($rpt_membrete['datos_membrete']) . $this->rpt_HTML;
                    }
                }
            }

            unset($rpt);

            $this->rpt_HTML = $this->Open_Tags_Html($title) . $this->rpt_HTML . $this->Close_Tags_Html();
            $this->rpt_HTML = preg_replace(':<img (.*?)src=["\']((?!http\://).*?)["\']:i', '<img \\1 src="'.$this->domain.'\\2"', $this->rpt_HTML);

            $this->reporte_html = $this->CrearReporteHTML();
            if(!$this->reporte_html)
            {
                return false;
            }
        }//fin del else de archivo no existente

        return $this->reporte_html;

    }//fin GetReportHTML

    function GetReportPDF($report_html='',$opciones=array())
    {
        if(!empty($opciones))
        {
            $this->opciones=$opciones;
        }
        
        if(!empty($report_html))
        {
            if(!file_exists($report_html))
            {
                $this->error = "Error en el archivo HTML pasado por parametro";
                $this->mensajeDeError = "No existe ".$report_html;
                return false;    
            }    
            if(!is_readable($report_html))
            {
                $this->error = "Error en el archivo HTML pasado por parametro";
                $this->mensajeDeError = "No tiene permiso de lectura ".$report_html;
                return false;        
            }
            $this->reporte_html = $report_html;    
                            
        }else{
        
            if(!file_exists($this->reporte_pdf) && is_readable($this->reporte_pdf))
            {
                return $this->reporte_pdf;
            }
            
            if(!file_exists($this->reporte_html))
            {
                $this->error = "Error en el archivo HTML pasado por parametro";
                $this->mensajeDeError = "No existe ".$report_html;
                return false;    
            }    
            
            if(!is_readable($this->reporte_html))
            {
                $this->error = "Error en el archivo HTML pasado por parametro";
                $this->mensajeDeError = "No tiene permiso de lectura ".$report_html;
                return false;        
            }
        }
        $this->reporte_pdf = $this->CrearReportePDF();
        if(!$this->reporte_pdf)
        {
            return false;
        }
        
        return $this->reporte_pdf;    
        
    }//fin de GetReportPDF
    
    
    //metodo que genera el nombre del archivo
    function CrearArchivoReport($prefijo,$extencion)
    {
        if(!empty($this->opciones['rpt_name']) && !empty($this->opciones['rpt_dir']))
        {
            global $_ROOT;    
            if(!empty($extencion))
            {
                $extencion = ".".$extencion;
            }
            $file_new =  $_ROOT . $this->opciones['rpt_dir'] . "/" . $prefijo . $this->opciones['rpt_name'] . $extencion;
            if(!touch($file_new))
            {
                $this->error = "No se pudo crear el archivo";
                $this->mensajeDeError = $file_new;
                return false;
            }
        }
        else
        {

            $file_new = tempnam($this->dir_cache, "$prefijo");
            //ECHO $this->dir_cache.'<br>'.$file_new; EXIT;
            if(!$file_new)
            {
                $this->error = "No se pudo crear el archivo";
                $this->mensajeDeError = $file_new;
                return false;
            }
            if(!empty($extencion))
            {
                $tmp = $file_new;
                $file_new .= ".".$extencion;
                if(!rename($tmp,$file_new))
                {
                    $this->error = "No se pudo renombrar";
                    $this->mensajeDeError = $file_new;
                    return false;
                }
            }
        }

        if(!file_exists($file_new))
        {
            $this->error = "No se pudo crear el archivo";
            $this->mensajeDeError = "No existe ".$file_new;
            return false;
        }

        if(!is_readable($file_new))
        {
            $this->error = "Error con el archivo temporal";
            $this->mensajeDeError = "No tiene permiso de lectura ".$file_new;
            return false;
        }

        if(!is_writeable($file_new))
        {
            $this->error = "Error con el archivo temporal";
            $this->mensajeDeError = "No tiene permiso de escritura".$file_new;
            return false;
        }

        return($file_new);
    }

    function CrearReportePDF()
    {
        $file = "classes/HTML_ToPDF-3.2/HTML_ToPDF.php";
        if(!IncludeFile($file))
        {
            $this->error = "Error al incluir la libreria HTML_ToPDF";
            $this->mensajeDeError = "Archivo ".$file;
            return false;        
        }
        $archivo_report=$this->CrearArchivoReport('','pdf');
        if(!$archivo_report)
        {
            return false;
        }    
        
        $pdf =& new HTML_ToPDF($this->reporte_html, $this->domain, $archivo_report);
        $result = $pdf->convert();
        if (PEAR::isError($result)) {
            $this->error = "Error al generar el archivo PDF";
            $this->mensajeDeError = $result->getMessage();
            return false;            
        }

        return     $archivo_report;
    }
        
    function CrearReporteHTML()
    {
        $archivo_report=$this->CrearArchivoReport('','html');
        if(!$archivo_report)
        {
            return false;
        }

        $fp = fopen($archivo_report, "w");

        if(!$fp){
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "No se pudo abrir el archivo  : $archivo_report";
            return false;
        }
        if (fwrite($fp,$this->rpt_HTML) === FALSE)
        {
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "No se pudo escribir en el archivo.";
            return false;
        }

        if(!fclose($fp)){
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "No se pudo cerrar el archivo.";
            return false;
        }

        return $archivo_report;

    }//fin CrearArchivoSpool



    /*
    * funcion que crea un membrete generico para el reporte
    */
    function GetMembreteGenerico($datos_membrete=array())
    {
        if(empty($datos_membrete)){
            return '';
        }
        
        if(empty($datos_membrete['titulo']) && empty($datos_membrete['subtitulo']) && empty($datos_membrete['logo'])){
            return '';
        }
        
        $HEADER ="<TABLE width='100%' border=0 cellpadding='4' cellspacing='4'>\n";
        
        global $_ROOT;
        $file_logo = $_ROOT . 'images/' . $datos_membrete['logo'];

        if($datos_membrete['logo'] && file_exists($file_logo))
        {
            if(strtolower($datos_membrete['align'])=='left')
            {
                if(empty($datos_membrete['titulo']))
                {
                    $datos_membrete['titulo']='&nbsp;';
                }            
                if(empty($datos_membrete['subtitulo']))
                {
                    $datos_membrete['subtitulo']='&nbsp;';
                }
                $HEADER.="<TR>";
                $HEADER.="<TD rowspan='2'><img src='$file_logo' align='left' border=0></TD>\n";
                $HEADER.="<TD width='100%' align='center' valign='center'>$datos_membrete[titulo]</TD>\n";
                $HEADER.="</TR>\n";
                $HEADER.="<TR>\n";
                $HEADER.="<TD align='center' valign='top'>$datos_membrete[subtitulo]</TD>\n";
                $HEADER.="</TR>\n";
            }
            elseif(strtolower($datos_membrete['align'])=='right')
            {
                if(empty($datos_membrete['titulo']))
                {
                    $datos_membrete['titulo']='&nbsp;';
                }            
                if(empty($datos_membrete['subtitulo']))
                {
                    $datos_membrete['subtitulo']='&nbsp;';
                }
                $HEADER.="<TR>\n";
                $HEADER.="<TD width='100%' align='center' valign='center'>$datos_membrete[titulo]</TD>\n";
                $HEADER.="<TD rowspan='2'><img src='$file_logo' align='left' border=0></TD>\n";                
                $HEADER.="</TR>\n";
                $HEADER.="<TR>\n";
                $HEADER.="<TD align='center' valign='top'>$datos_membrete[subtitulo]</TD>\n";
                $HEADER.="</TR>\n";
            }
            else
            {
                $HEADER.="<TR>\n";
                $HEADER.="<TD align='center'><img src='$file_logo' border=0></TD>\n";
                $HEADER.="</TR>\n";

                if(!empty($datos_membrete['titulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='center'>" . $datos_membrete['titulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }
                if(!empty($datos_membrete['subtitulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='center'>" . $datos_membrete['subtitulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }                    
            }

        
        }else{
            if(strtolower($datos_membrete['align'])=='left')
            {
                if(!empty($datos_membrete['titulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='left'>" . $datos_membrete['titulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }
                if(!empty($datos_membrete['subtitulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='left'>" . $datos_membrete['subtitulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }            
            }
            elseif(strtolower($datos_membrete['align'])=='right')
            {
                if(!empty($datos_membrete['titulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='right'>" . $datos_membrete['titulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }
                if(!empty($datos_membrete['subtitulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='right'>" . $datos_membrete['subtitulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }            
            }
            else
            {
                if(!empty($datos_membrete['titulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='center'>" . $datos_membrete['titulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }
                if(!empty($datos_membrete['subtitulo']))
                {
                    $HEADER.="<TR>\n";
                    $HEADER.="<TD align='center'>" . $datos_membrete['subtitulo'] . "</TD>\n";
                    $HEADER.="</TR>\n";
                }            
            }
        }
    
        $HEADER.="</TABLE><BR>\n";
        
        return $HEADER;
    }


    /*
    * funcion que inicia los tags de html
    * para hacer la pagina web
    */
    function Open_Tags_Html($title)
    {
        if(empty($title))
        {
            $title="&nbsp;";
        }
        
        $HTML  = "<HTML>\n";
        $HTML .= "<HEAD>\n";
        $HTML .= "    <TITLE>$title</TITLE>\n";
        //$d="".$this->domain."/classes/reports/style/style.css";
        $HTML .= "<link href=".$this->domain."classes/reports/style/style.css  rel=stylesheet type=text/css>\n";
        $HTML .= "</HEAD>\n";
        $HTML .= "<BODY>\n";
        return $HTML;
    }


    /*
    * funcion que cierra los tags de html
    * para terminar la pagina web
    */
    function Close_Tags_Html()
    {
        $HTML  = "</BODY>\n";
        $HTML .= "</HTML>\n\n";
        
        return $HTML;
    }        
    
}//fin de la class

?>
