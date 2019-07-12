<?php
  include_once ("classes/jpgraph-1.14/src/jpgraph.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_line.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvas.php");//
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvtools.php");//

  $g = new CanvasGraph(900,500,'auto');
  //$g->SetMargin(5,11,6,11);
  $g->SetShadow();
  $g->SetMarginColor("white");

  $g->InitFrame();

  $VISTA='HTML';
  $_ROOT = '';
  //Cargar el entorno (Variables,Definiciones,BD,API,Session etc..)
  include $_ROOT . 'includes/enviroment.inc.php';

  echo "<center><h1>INDICE DE O'LEARY</h1></center><BR>";

  IncludeLib("jpgraph/IPBOleary_graphic");

//DATOS PRUEBA
//VECTOR DE SOMBRAS O CARIES
// $vector[0][0]='16';
// $vector[0][1]='1';
// $vector[1][0]='17';
// $vector[1][1]='4';
// $vector[2][0]='16';
// $vector[2][1]='2';
// $vector[3][0]='12';
// $vector[3][1]='2';
// $vector[4][0]='53';
// $vector[4][1]='2';
// $vector[5][0]='52';
// $vector[5][1]='1';
$vector[0][0]='16';
$vector[0][1]='1';
$vector[1][0]='27';
$vector[1][1]='1';
$vector[2][0]='33';
$vector[2][1]='1';
$vector[3][0]='34';
$vector[3][1]='3';
$vector[4][0]='34';
$vector[4][1]='4';
$vector[5][0]='34';
$vector[5][1]='5';
$vector[6][0]='35';
$vector[6][1]='3';
$vector[7][0]='43';
$vector[7][1]='1';
$vector[8][0]='43';
$vector[8][1]='3';

//VECTOR DE PIEZAS AUSENTES O SIMBOLOS PARA TODO EL DIENTE
/*$vector1[0][0]='18';
$vector1[0][1]='4';
$vector1[1][0]='28';
$vector1[1][1]='5';
$vector1[2][0]='48';
$vector1[2][1]='4';
$vector1[3][0]='38';
$vector1[3][1]='5';
$vector1[4][0]='63';
$vector1[4][1]='12';
$vector1[5][0]='13';
$vector1[5][1]='12';
$vector1[6][0]='52';
$vector1[6][1]='12';
$vector1[7][0]='34';
$vector1[7][1]='12';*/
//FIN DATOS PRUEBA

  $RutaImg=IPBOleary($vector,1,$vector1,1);
  $salida.="  <td>";
  $salida.="   <center> <img src=\"".$RutaImg."\" border='1'></center>"; //aqui se imprime para mostrar el grafico
  $salida.="  </td>";
  //WIDTH=140 HEIGHT=210
  echo $salida;
?>
