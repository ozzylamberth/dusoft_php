<?php


class Validador
{

  var $salida="";
  var $funcionesAIncluir=array();
  var $tipoValidacion = "";
  var $prefijo = "";

  function Validador()
  {
    $this->ResetValidador();
    return true;
  }

  function ResetValidador()
  {
    $this->funcionesAIncluir = array('IsEmpty'=>0,
                                      'Numero'=>0,
                                      'AlfaNumerico'=>0,
                                      'IsEmail'=>0,
                                      'Alfabetico'=>0,
                                      'Entero'=>0
                                      );
    $this->salida="";
    $this->prefijo="";
    $this->TipoDeValidacionOnSubmit();
    return true;
  }

  function SetPrefijo($prefijo)
  {
    $this->prefijo=$prefijo;
  }

  function TipoDeValidacionOnSubmit()
  {
    $this->tipoValidacion = "OnSubmit";
  }

  function TipoDeValidacionOnBlur()
  {
    $this->tipoValidacion = "OnBlur";
  }

  function IncludeIsEmpty()
  {
    $this->salida .= "function isempty(frm,campo){\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "if((Campo == null) || (Campo.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    return true;
  }

  function IsEmpty()
  {
    $this->funcionesAIncluir['IsEmpty']=1;
    return true;
  }


  function IncludeNumero()
  {
    $this->salida .= "function Numero(frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "var f=false;\n";
    $this->salida .= "var j;\n";
    $this->salida .= "var x;\n";
    $this->salida .= "var digitos = \"0123456789\";\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
  //$this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if( i != 0 ){";
    $this->salida .= "if ( c == \".\" ){\n";
    $this->salida .= "j=i;\n";
    $this->salida .= "if( !f ) f = true;\n";
    $this->salida .= "else{\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "x=i-j;\n";
    $this->salida .= "if(x>2){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "if(digitos.indexOf(c)==-1){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "if ( c == \".\" ){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "if ((digitos.indexOf(c)==-1) && (c != \"-\") || (c == \"+\")){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function IsNumero()
  {
    $this->funcionesAIncluir['Numero']=1;
    return true;
  }

  function IncludeAlfaNumerico()
  {
    $this->salida .= "function AlfaNumerico(frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var letrasminusculas = \"abcdefghijklmnopqrstuvwxyzáéíóúñü\";\n";
    $this->salida .= "var letrasmayusculas = \"ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÑ\";\n";
    $this->salida .= "var digitos = \"0123456789\";\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
      //     $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if((letrasminusculas.indexOf(c)==-1) && (letrasmayusculas.indexOf(c)==-1) && digitos.indexOf(c)==-1){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un texto que contenga solo letras y/o numeros\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un texto que contenga solo letras y/o numeros\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function AlfaNumerico()
  {
    $this->funcionesAIncluir['AlfaNumerico']=1;
    return true;
  }

  function IncludeIsEmail ()
  {
    $this->salida .= "function Fmail (frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= " i=1;\n";
    $this->salida .= "var sLength = d.length;\n";
    $this->salida .= "while ((i < sLength) && (d.charAt(i) != \"@\") && (d.charAt(i) != \".\"))\n";
    $this->salida .= "i++; \n";
    $this->salida .= "if ((i >= sLength) || (d.charAt(i) != \"@\") || (i <= 1)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese una dirección de correo electrónico válida.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= " else i++;\n";
    $this->salida .= " var p=i;\n";
    $this->salida .= "while ((i < sLength) && (d.charAt(i) != \".\") && (d.charAt(i) != \"@\"))\n";
    $this->salida .= "i++; \n";
    $this->salida .= "if ((i >= sLength-2) || (d.charAt(i) != \".\") || (i-p<=1)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese una dirección de correo electrónico válida.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else i++;\n";
    $this->salida .= "var j = i;\n";
    $this->salida .= "while ((i <= sLength) && (d.charAt(i) != \".\") && (d.charAt(i) != \"@\"))\n";
    $this->salida .= " i++;\n";
    $this->salida .= "var f= i-j;\n";
    $this->salida .= "if ((d.charAt(i) == \".\") || (d.charAt(i) == \"@\") || (f>=5)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"222Ingrese una dirección de correo electrónico válida.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else return true; \n";
    $this->salida .= "}\n";
    return true;
  }

  function IsEmail()
  {
    $this->funcionesAIncluir['IsEmail']=1;
    return true;
  }

  function IncludeAlfabetico()
  {
    $this->salida .= "function Alfabetico(frm,campo){\n";
      //     $this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var letrasminusculas = \"abcdefghijklmnopqrstuvwxyzáéíóúñü\";\n";
    $this->salida .= "var letrasmayusculas = \"ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÑ\";\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if((letrasminusculas.indexOf(c)==-1) && (letrasmayusculas.indexOf(c)==-1)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "   return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function Alfabetico()
  {
    $this->funcionesAIncluir['Alfabetico']=1;
    return true;
  }

  function IncludeEntero ()
  {
    $this->salida .= "function Entero(frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var digitos = \"0123456789\";\n";
    $this->salida .= "var i=0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" +campo+ \" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if(digitos.indexOf(c)==-1){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero entero.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function Entero()
  {
    $this->funcionesAIncluir['Entero']=1;
    return true;
  }

  function ReturnScript()
  {
    $this->salida  = '';

    if($this->funcionesAIncluir['IsEmpty'])
    {
      $this->IncludeIsEmpty();
    }
    if($this->funcionesAIncluir['Numero'])
    {
      $this->IncludeNumero();
    }
    if($this->funcionesAIncluir['AlfaNumerico'])
    {
      $this->IncludeAlfaNumerico();
    }
    if($this->funcionesAIncluir['IsEmail'])
    {
      $this->IncludeIsEmail();
    }
    if($this->funcionesAIncluir['Alfabetico'])
    {
      $this->IncludeAlfabetico();
    }
    if($this->funcionesAIncluir['Entero'])
    {
      $this->IncludeEntero();
    }

    if(!empty($this->salida)){
      $this->salida  = "<script language=\"javascript\">\n var avisado=false;\n" . $this->salida ;
      $this->salida .= "</script>\n\n";
    }

    return $salida;
  }

  function ReturnValidaciones(){
    return $this->funcionesAIncluir;
  }

  function IncluirValidaciones($funcionesAIncluir=array())
  {
    foreach($funcionesAIncluir as $k=>$v)
    {
      if(!empty($v)){
        $this->funcionesAIncluir[$k]=1;
      }
    }
    return true;
  }

}//fin clase VALIDAR

















class ValidadorOnBlur
{

  var $salida="";
  var $funcionesAIncluir=array();

  function Validador()
  {
    $this->ResetValidador();
    return true;
  }

  function ResetValidador()
  {
    $this->funcionesAIncluir = array('IsEmpty'=>0,
                                      'Numero'=>0,
                                      'AlfaNumerico'=>0,
                                      'IsEmail'=>0,
                                      'Alfabetico'=>0,
                                      'Entero'=>0
                                      );
    $this->salida="";
    return true;
  }

  function IncludeIsEmpty()
  {
    $this->salida .= "function isempty(frm,campo){\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "if((Campo == null) || (Campo.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    return true;
  }

  function IsEmpty()
  {
    $this->funcionesAIncluir['IsEmpty']=1;
    return true;
  }


  function IncludeNumero()
  {
    $this->salida .= "function Numero(frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "var f=false;\n";
    $this->salida .= "var j;\n";
    $this->salida .= "var x;\n";
    $this->salida .= "var digitos = \"0123456789\";\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
  //$this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if( i != 0 ){";
    $this->salida .= "if ( c == \".\" ){\n";
    $this->salida .= "j=i;\n";
    $this->salida .= "if( !f ) f = true;\n";
    $this->salida .= "else{\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "x=i-j;\n";
    $this->salida .= "if(x>2){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "if(digitos.indexOf(c)==-1){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "if ( c == \".\" ){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else{\n";
    $this->salida .= "if ((digitos.indexOf(c)==-1) && (c != \"-\") || (c == \"+\")){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero.\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un numero.\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function IsNumero()
  {
    $this->funcionesAIncluir['Numero']=1;
    return true;
  }

  function IncludeAlfaNumerico()
  {
    $this->salida .= "function AlfaNumerico(frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var letrasminusculas = \"abcdefghijklmnopqrstuvwxyzáéíóúñü\";\n";
    $this->salida .= "var letrasmayusculas = \"ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÑ\";\n";
    $this->salida .= "var digitos = \"0123456789\";\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
      //     $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if((letrasminusculas.indexOf(c)==-1) && (letrasmayusculas.indexOf(c)==-1) && digitos.indexOf(c)==-1){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un texto que contenga solo letras y/o numeros\");\n";
    $this->salida .= "window.status=(\"Error:  Ingrese un texto que contenga solo letras y/o numeros\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function AlfaNumerico()
  {
    $this->funcionesAIncluir['AlfaNumerico']=1;
    return true;
  }

  function IncludeIsEmail ()
  {
    $this->salida .= "function Fmail (frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= " i=1;\n";
    $this->salida .= "var sLength = d.length;\n";
    $this->salida .= "while ((i < sLength) && (d.charAt(i) != \"@\") && (d.charAt(i) != \".\"))\n";
    $this->salida .= "i++; \n";
    $this->salida .= "if ((i >= sLength) || (d.charAt(i) != \"@\") || (i <= 1)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese una dirección de correo electrónico válida.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= " else i++;\n";
    $this->salida .= " var p=i;\n";
    $this->salida .= "while ((i < sLength) && (d.charAt(i) != \".\") && (d.charAt(i) != \"@\"))\n";
    $this->salida .= "i++; \n";
    $this->salida .= "if ((i >= sLength-2) || (d.charAt(i) != \".\") || (i-p<=1)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese una dirección de correo electrónico válida.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else i++;\n";
    $this->salida .= "var j = i;\n";
    $this->salida .= "while ((i <= sLength) && (d.charAt(i) != \".\") && (d.charAt(i) != \"@\"))\n";
    $this->salida .= " i++;\n";
    $this->salida .= "var f= i-j;\n";
    $this->salida .= "if ((d.charAt(i) == \".\") || (d.charAt(i) == \"@\") || (f>=5)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"222Ingrese una dirección de correo electrónico válida.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "else return true; \n";
    $this->salida .= "}\n";
    return true;
  }

  function IsEmail()
  {
    $this->funcionesAIncluir['IsEmail']=1;
    return true;
  }

  function IncludeAlfabetico()
  {
    $this->salida .= "function Alfabetico(frm,campo){\n";
      //     $this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var letrasminusculas = \"abcdefghijklmnopqrstuvwxyzáéíóúñü\";\n";
    $this->salida .= "var letrasmayusculas = \"ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÑ\";\n";
    $this->salida .= "var i = 0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" + campo +\" es requerido.\";\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if((letrasminusculas.indexOf(c)==-1) && (letrasmayusculas.indexOf(c)==-1)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "   return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function Alfabetico()
  {
    $this->funcionesAIncluir['Alfabetico']=1;
    return true;
  }

  function IncludeEntero ()
  {
    $this->salida .= "function Entero(frm,campo){\n";
    //$this->salida .=" var Campo = vari;\n";
    $this->salida .=" var Campo = frm.value;\n";
    $this->salida .= "var digitos = \"0123456789\";\n";
    $this->salida .= "var i=0;\n";
    $this->salida .= "while ((i < Campo.length) && (Campo.charAt(i) == \" \"))\n";
    $this->salida .= "i++;\n";
    $this->salida .= "var d = Campo.substring (i, Campo.length); \n";
    $this->salida .= "if((d == null) || (d.length == 0)){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"El \" + campo +\" es requerido.\");\n";
    $this->salida .= "window.status = \"El \" +campo+ \" es requerido.\";\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false; \n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "for (i = 0; i < d.length; i++){\n";
    $this->salida .= "var c = d.charAt(i);\n";
    $this->salida .= "if(digitos.indexOf(c)==-1){\n";
    $this->salida .= "if(!avisado){\n";
    $this->salida .= "alert(\"Ingrese un numero entero.\");\n";
    $this->salida .= "window.status=(\"Error: Ingrese un texto que contenga solo letras\");\n";
    $this->salida .= "frm.select();\n";
    $this->salida .= "frm.focus();\n";
    $this->salida .= "avisado=true;\n";
    $this->salida .= "setTimeout('avisado=false',50);\n";
    $this->salida .= "return false;\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "}\n";
    $this->salida .= "return true;\n";
    $this->salida .= "}\n";
    return true;
  }

  function Entero()
  {
    $this->funcionesAIncluir['Entero']=1;
    return true;
  }

  function ReturnScript()
  {
    $this->salida  = '';

    if($this->funcionesAIncluir['IsEmpty'])
    {
      $this->IncludeIsEmpty();
    }
    if($this->funcionesAIncluir['Numero'])
    {
      $this->IncludeNumero();
    }
    if($this->funcionesAIncluir['AlfaNumerico'])
    {
      $this->IncludeAlfaNumerico();
    }
    if($this->funcionesAIncluir['IsEmail'])
    {
      $this->IncludeIsEmail();
    }
    if($this->funcionesAIncluir['Alfabetico'])
    {
      $this->IncludeAlfabetico();
    }
    if($this->funcionesAIncluir['Entero'])
    {
      $this->IncludeEntero();
    }

    if(!empty($this->salida)){
      $this->salida  = "<script language=\"javascript\">\n var avisado=false;\n" . $this->salida ;
      $this->salida .= "</script>\n\n";
    }

    return $this->salida;
  }

  function ReturnValidaciones(){
    return $this->funcionesAIncluir;
  }

  function IncluirValidaciones($funcionesAIncluir=array())
  {
    foreach($funcionesAIncluir as $k=>$v)
    {
      if(!empty($v)){
        $this->funcionesAIncluir[$k]=1;
      }
    }
    return true;
  }

}//fin clase ValidarOnBlur



?>
