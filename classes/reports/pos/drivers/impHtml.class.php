<?php



class report_pos_driver_impHtml
{

    var $salida;
    var $columns;
    var $redColor;
    var $serie;
    var $filtro = array ("ñ" => "n",
                         "Ñ" => "N",
                         "á" => "a",
                         "é" => "e",
                         "í" => "i",
                         "ó" => "o",
                         "ú" => "u",
                         "à" => "a",
                         "è" => "e",
                         "ì" => "i",
                         "ò" => "o",
                         "ù" => "u",
                         "ü" => "u",
                         "Á" => "A",
                         "É" => "E",
                         "Í" => "I",
                         "Ó" => "O",
                         "Ú" => "U",
                         "Ü" => "U",
                         "¿" => "¿",
                         "@" => "@",
                         "#" => "#",
                         " "=>" ");
    //Constructor
    function report_pos_driver_impHtml($serie='A')
    {
        $this->serie = $serie;
        $this->salida = "";
        $this->SetFontSizeNormal();

        return true;
    }

    //Metodo Privado OK
    //
    function SetFontSizeNormal()
    {
        $this->salida .= " ";
        $this->$columns = 39;
        return true;
    }

    //Metodo Privado OK
    //
    function SetFontSizeGrande()
    {
        $this->salida .= " ";
        $this->$columns = 31;
        return true;
    }

    //Metodo Privado
    //
    function SetCharacterMode($normal=false)
    {
        if($normal){
            //$this->salida .= "\x0E";
        }else{
           // $this->salida .= "\x0F";
        }
        return true;
    }

    //Metodo Privado
    //
    function setCharacterSet($codigo)
    {
       // $this->salida .= "\x1B\x52".chr($codigo);
        return true;
    }

    //Metodo Privado OK
    //
    function FormatearTexto($text)
    {
        $text=trim($text);
        $text=strtr($text, $this->filtro);
        return $text;
    }

    //Metodo Privado OK
    //Retornar contenido para imprimir
    function GetSalida()
    {
        if($this->salida == " "){
            $this->salida = "";
        }
        $cadena = "
            <script>          
                  var ventimp = window.open(' ', 'imprimir.html');
                  ventimp.document.write( '".$this->salida." ' );
                  ventimp.document.close();
                  ventimp.print( );
                     
             </script> 

";
        
        
        echo $cadena;
        return $this->salida;
    }

    //---------------------------------------------
    //METODOS PUBLICOS
    //---------------------------------------------


    //Limpiar el Contenido OK
    function BorrarContenido()
    {
        $this->salida = "";
        return true;
    }

    //NEGRILLA OK
    function setFontResaltar($normal=false)
    {
                if($this->$columns == 31)
                {
                        if($normal){
                                $this->salida .= "<b>";
                        }else{
                                $this->salida .= "</b> ";
                        }

                }else{
                        if($normal){
                                $this->salida .= "<b>";
                        }else{
                                $this->salida .= "</b> ";
                        }
                }

        return true;
    }

    //FUENTE ROJA  OK
    function setFontRedColor($RedColor=false)
    {
        if($RedColor){
                $this->salida .= " ";
        }else{
                $this->salida .= " ";
        }
        return true;
    }

    //SALTO DE LINEA(S) OK
    function SaltoDeLinea($n='')
    {
        if(is_numeric($n))
        {
            if(($n>1)&&($n<=100))
            {
                $this->salida .= "<br>";
            }else{
                $this->salida .= "<br>";
            }
        }else{
            $this->salida .= "<br>";
        }
        return true;
    }


    //IMPRIMIR TEXTO DE CORRIDO OK
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
    function PrintFTexto($text,$bold=false,$align='left',$redColor=false,$size=false,$char_relleno=" ")
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
                    $LINEAS[] = str_pad($text_salida, $sizeFont, $char_relleno,STR_PAD_LEFT);
                break;
                case 'CENTER':
                    $LINEAS[] = str_pad($text_salida, $sizeFont, $char_relleno,STR_PAD_BOTH);
                break;
                default:
                    $LINEAS[] = str_pad($text_salida, $sizeFont, $char_relleno,STR_PAD_RIGHT);
            }


        }while(strlen($text));

        foreach($LINEAS as $k=>$v)
        {
            $this->salida .= str_replace(" "," ",$v);
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
            $signoMoneda=" ";
        }else{
            $signoMoneda=' ';
        }

        if($posiciones<1 || $posiciones>31){
            $posiciones=11;
        }

        if(is_numeric($valor)){
            $valor = number_format($valor,$decimales,',','.');
        }

        if(strlen($valor) < $posiciones){
            $valor = str_pad($valor, $posiciones, " ", STR_PAD_LEFT);
            $valor = $signoMoneda . str_replace(" "," ",$valor);
        }else{
            $valor = $signoMoneda . str_pad('', $posiciones, "-", STR_PAD_LEFT);
        }
        $sizevalor=strlen($valor);
        $sizetext=39-$sizevalor;
        $relleno=str_pad('', $sizevalor, " ", STR_PAD_LEFT);
        $relleno=str_replace(" "," ",$relleno);

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
                    $LINEAS[] = str_pad($text_salida, $sizetext, ' ',STR_PAD_LEFT);
                break;
                case 'CENTER':
                    $LINEAS[] = str_pad($text_salida, $sizetext, ' ',STR_PAD_BOTH);
                break;
                default:
                    $LINEAS[] = str_pad($text_salida, $sizetext, ' ',STR_PAD_RIGHT);
            }
        }while(strlen($text));

        foreach($LINEAS as $k=>$v)
        {
            if($text_bold){
                $this->setFontResaltar(true);
            }

            $this->salida .= str_replace(" "," ",$v);
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

    //ABRIR EL CAJON MONEDERO OK
    function OpenCajaMonedera()
    {
        $this->salida .= " ";
        return true;
    }

    //FIN DEL TIQUETE OK
    function PrintEnd()
    {
        $this->salida .= " ";
        return true;
    }

    //CORTAR PAPEL OK
    function PrintCutPaper($full=true)
    {
        if($full){
            if($this->serie=='A'||$this->serie=='B'){
                $this->salida .= " ";
            }
        }else{
            if($this->serie=='A'||$this->serie=='B'){
                $this->salida .= " ";
            }
        }
        return true;
    }

    function PrintLinea()
    {
        $this->salida .=str_pad('', $this->$columns, "-");
        $this->SaltoDeLinea();
        return true;
    }


}//Fin de la Class starPOS

?>
