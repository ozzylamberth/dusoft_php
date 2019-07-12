<html>
<head>
<?php
/*if(file_exists("validador.class.php")){
  include "validador.class.php";
  if(class_exists("Validador")){
    $classvalidar = new Validador;

  }else{
    echo "No existe la clase";
    exit;
  }
 }else{
  echo "No existe el include";
  exit;
 }
  $classvalidar->ResetValidador();
  $classvalidar->TipoDeValidacionOnSubmit();
  $classvalidar->Entero();
  echo $classvalidar->ReturnScript();*/
  ?>
<script language="javascript">
  function Fechas(frm,campo,sep)
  {
    var fecha=frm.value;
    if(fecha=='')
    {
      alert('La cadena esta vacia.');
      return false;
    }
    if(fecha.length!=10)
    {
      alert('La fecha esta mal escrita');
      return false;
    }
    fech=fecha.split(sep);
    if(fech[1]==undefined || fech[2]==undefined)
    {
      alert('El separador que se utilizo es diferente a '+sep+' .');
      return false;
    }
    if(isNaN(fech[0]) || isNaN(fech[1]) || isNaN(fech[2]))
    {
      alert('Existen letras en la fecha.');
      return false;
    }
    if(fech[0]>31 || fech[1]>12)
    {
      alert('La fecha esta mal escrita.');
      return false;
    }
  }
 </script>
</head>
<body>

 <TABLE cellSpacing=0 width=60% align=center>
   <form onSubmit="return empty(this)" name="forma" action="funciones.php" method="post" enctype="multipart/form-data">
  <TR>
    <TD>Nombre:<BR></TD>
    <TD vAlign=top><INPUT name="nombre" onBlur="Alfabetico(this.form.nombre,this.name);"></TD></TR>
  <TR>
    <TD>numeros:<BR></TD>
    <TD vAlign=top><INPUT name="CC" onBlur="Entero(this.form.CC,this.name)"></TD></TR>
  <TR>
    <TD>alfanumericos:</TD>
    <TD vAlign=top><INPUT name="alfanum" onBlur="AlfaNumerico(this.form.alfanum,this.name)"></TD></TR>
  <TR>
    <TD> numero con o sin 2 decimales:</TD>
    <TD vAlign=top><INPUT name="decimales" onBlur="Numero(this.form.decimales,this.name)"></TD></TR>
  <TR>
    <TD> e-mail:</TD>
    <TD vAlign=top><INPUT name="Email" onBlur="Fmail(this.form.Email,this.name)"></TD></TR>
  <TR>
    <TD>telefono-numero:</TD>
    <TD vAlign=top><INPUT name="campoF" onBlur="Entero(this.form.campoF,this.name)"></TD></TR>
  <TR>
    <TD>fecha:</TD>
    <TD vAlign=top><INPUT name="Fecha" onBlur="Fechas(this.form.Fecha,this.name,'/')"></TD></TR>
  <TR>
    <TD align=middle colSpan=2 vAlign=center>
    <INPUT type="submit" value="Enviar" name="Enviar"></TD>
    </TR>
  </form>
 </TABLE>
</body>
</html>
