<?php
// -------------------------------------------------------------------------------------
// Autor: Alexander Giraldo  -- alexgiraldo@ipsoft-sa.com
// Proposito del Archivo: Clase para la generacion de reportes en impresoras POS Start
// 2004-07-21
// -------------------------------------------------------------------------------------


class report_pos_driver_epsonTMU220
{

    var $salida;
    var $columns;
    var $serie;
    var $filtro = array ("ñ" => "\xA4",
                         "Ñ" => "\xA5",
                         "á" => "\xA0",
                         "é" => "\x82",
                         "í" => "\xA1",
                         "ó" => "\xA2",
                         "ú" => "\xA3",
                         "à" => "\x85",
                         "è" => "\x8A",
                         "ì" => "\x8D",
                         "ò" => "\x95",
                         "ù" => "\x97",
                         "ü" => "\x81",
                         "Á" => "A",
                         "É" => "E",
                         "Í" => "I",
                         "Ó" => "O",
                         "Ú" => "U",
                         "Ü" => "\x9A",
                         '$' => "\x24",
                         "¿" => "\xA8",
                         "@" => "\x40",
                         "#" => "\x23",
                         "\x7F"=>" ");                     
    //Constructor
    function report_pos_driver_epsonTMU220($serie='D')
    {
        $this->serie = $serie;
        $this->salida = "";
        $this->SetFontSizeNormal();
        
        return true;
    }
    
    //Metodo Privado
    //
    function SetFontSizeNormal()
    {
        $this->salida .= "\x1B\x21\x0";
        $this->$columns = 42;
        return true;
    }
    
    //Metodo Privado
    //    
    function SetFontSizeGrande()
    {
        $this->salida .= "\x1B\x21\x1";
        $this->$columns = 35;
        return true;
    }
    
    //Metodo Privado
    //    
    function SetCharacterMode($normal=false)
    {
        if($normal){
            $this->salida .= "\x0E";
        }else{
            $this->salida .= "\x0F";
        }
        return true;
    }
    
    //Metodo Privado
    //      
    function setCharacterSet($codigo)
    {
        $this->salida .= "\x1B\x52".chr($codigo);
        return true;
    }
    
    //Metodo Privado
    //      
    function FormatearTexto($text)
    {
        $text=trim($text);
        $text=strtr($text, $this->filtro);
        return $text;
    }
    
    //Metodo Privado
    //Retornar contenido para imprimir
    function GetSalida()
    {
        if($this->salida == "\x1B\x4D"){
            $this->salida = "";
        }
        return $this->salida;
    }    
    
    //---------------------------------------------
    //METODOS PUBLICOS
    //---------------------------------------------
    
    
    //Limpiar el Contenido
    function BorrarContenido()
    {
        $this->salida = "";
        return true;
    }        
    
    //NEGRILLA
    function setFontResaltar($normal=false)
    {
				if($this->$columns == 35)
				{
						if($normal){
								$this->salida .= "\x1B\x21\x1";
						}else{
								$this->salida .= "\x1B\x21\x9";
						}   				
					
				}else{
						if($normal){
								$this->salida .= "\x1B\x21\x0";
						}else{
								$this->salida .= "\x1B\x21\x8";
						}   				
				}
 
        return true;
    }
    
    //FUENTE ROJA
    function setFontRedColor($RedColor=false)
    {
        if($RedColor){
                $this->salida .= "\x1B\x72\x1";
        }else{
                $this->salida .= "\x1B\x72\x0";
        }    
        return true;
    }
    
    //SALTO DE LINEA(S)
    function SaltoDeLinea($n='')
    {
        if(is_numeric($n))
        {
            if(($n>1)&&($n<=100))
            {
                $this->salida .= "\x1B\x64".chr($n);
            }else{
                $this->salida .= "\x0A";
            }
        }else{
            $this->salida .= "\x0A";
        }
        return true;
    }    
    
   
    //IMPRIMIR TEXTO DE CORRIDO
    function PrintTexto($text,$SaltoLinea=0)
    {
        $text = $this->FormatearTexto($text);
        $this->salida .= $text;
        if($SaltoLinea){
            $this->SaltoDeLinea($SaltoLinea);
        }
        return true;
    }
    
    //IMPRIR TEXTO TAMAÑO NORMAL CON OPCIONES DE FORMATO
    function PrintFTexto($text,$bold=false,$align='left',$redColor=false,$size=false)
    {

        $text = $this->FormatearTexto($text);
        
        if(empty($text)){
            return true;
        }
        if(!$size){
            $sizeFont=42;
            $this->SetFontSizeNormal();
        }else{
            $sizeFont=35;
            $this->SetFontSizeGrande();            
        }
        
        if($bold){
            $this->setFontResaltar(true);
        }else{
            $this->setFontResaltar(false);
        }
        
        if($redColor){
            $this->setFontRedColor(true);
        }else{
            $this->setFontRedColor(false);
        }        

               
        $LINEAS = array();
        do{
            if(strlen($text) <= $sizeFont){
                $text_salida = $text;
                $text='';
            }else{
                $text_salida = substr($text, 0, $sizeFont);
                $text = substr($text, $sizeFont);
            }
            
            $align = strtoupper($align);
            
            switch($align){
                case 'RIGHT':
                    $LINEAS[] = str_pad($text_salida, $sizeFont, "\x7F",STR_PAD_LEFT);
                break;
                case 'CENTER':
                    $LINEAS[] = str_pad($text_salida, $sizeFont, "\x7F",STR_PAD_BOTH);
                break;
                default:
                    $LINEAS[] = str_pad($text_salida, $sizeFont, "\x7F",STR_PAD_RIGHT);         
            }    
            
           
        }while(strlen($text));

        foreach($LINEAS as $k=>$v)
        {
            $this->salida .= str_replace("\x7F"," ",$v);
            $this->SaltoDeLinea();
        }
        
        
        if($bold){
            $this->setFontResaltar(false);
        }
        
        if($redColor){
            $this->setFontRedColor(false);
        }  
             
        return true;
    }
    

   
     //IMPRIR TEXTO-VALOR TAMAÑO NORMAL A 2 COLUMNAS  
    function PrintFTextoValor($text,$valor=0,$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=false,$align_text='left')
    {
        $this->SetFontSizeNormal();
        $this->setFontResaltar(false);
        $this->setFontRedColor(false);
        
        if($signoMoneda){
            $signoMoneda=" \x24";
        }else{
            $signoMoneda=' ';
        }
        
        if($posiciones<1 || $posiciones>35){
            $posiciones=11;
        }
        
        if(is_numeric($valor)){
            $valor = number_format($valor,$decimales,',','.');
        }

        if(strlen($valor) < $posiciones){
            $valor = str_pad($valor, $posiciones, "\x7F", STR_PAD_LEFT);
            $valor = $signoMoneda . str_replace("\x7F"," ",$valor);
        }else{
            $valor = $signoMoneda . str_pad('', $posiciones, "-", STR_PAD_LEFT);
        }
        $sizevalor=strlen($valor);
        $sizetext=42-$sizevalor;
        $relleno=str_pad('', $sizevalor, "\x7F", STR_PAD_LEFT);
        $relleno=str_replace("\x7F"," ",$relleno);
        
        $LINEAS = array();
        do{
            if(strlen($text) <= $sizetext){
                $text_salida = $text;
                $text='';
            }else{
                $text_salida = substr($text, 0, $sizetext);
                $text = substr($text, $sizetext);
            }
            
            $align = strtoupper($align);
            
            switch($align){
                case 'RIGHT':
                    $LINEAS[] = str_pad($text_salida, $sizetext, "\x7F",STR_PAD_LEFT);
                break;
                case 'CENTER':
                    $LINEAS[] = str_pad($text_salida, $sizetext, "\x7F",STR_PAD_BOTH);
                break;
                default:
                    $LINEAS[] = str_pad($text_salida, $sizetext, "\x7F",STR_PAD_RIGHT);         
            }    
        }while(strlen($text));    
        
        foreach($LINEAS as $k=>$v)
        {
            if($text_bold){
                $this->setFontResaltar(true);
            }            
                        
            $this->salida .= str_replace("\x7F"," ",$v);
            if($k==0){
                $this->salida .= $valor;
            }else{
                $this->salida .= $relleno;
            }
            $this->SaltoDeLinea();
            
            if($text_bold){
                $this->setFontResaltar(false);
            }            
            
        }        
                
        return true;
    }    
     
    //ABRIR EL CAJON MONEDERO
    function OpenCajaMonedera()
    {
        $this->salida .= "\x1B\x70\x00\x00\x00";
        return true;
    }
    
    //FIN DEL TIQUETE
    function PrintEnd()
    {
        $this->salida .= "\x1B\x64\x04";
        return true;
    }
    
    //CORTAR PAPEL
    function PrintCutPaper($full=true)
    {
        if($full){
            if($serie=='SP240'){
                $this->salida .= "\x1B\x64\x30";
            }        
        }else{
            if($serie=='SP240'){
                $this->salida .= "\x1B\x64\x31";
            }else{
                $this->salida .=str_pad('', $this->$columns, "-");
                $this->SaltoDeLinea();
            }        
        }
        return true;
    }
    
    
}//Fin de la Class starPOS

?>
