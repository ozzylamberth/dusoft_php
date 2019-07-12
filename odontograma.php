
<?php
  include_once ("classes/jpgraph-1.14/src/jpgraph.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_line.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvas.php");//
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvtools.php");//

  $VISTA='HTML';
  $_ROOT = '';
  //Cargar el entorno (Variables,Definiciones,BD,API,Session etc..)
  include $_ROOT . 'includes/enviroment.inc.php';

  echo "<center><h1>ODONTOGRAMA</h1></center><BR>";

  IncludeLib("jpgraph/Odontograma_graphic");
  //IncludeLib("jpgraph/IPBOleary_graphic");

  $n[0]=16;
  $n[1]=10;
  $n[2]=10;
  $n[3]=16;
  //DATOS PRUEBA
/*  $vector[0][0]='11';
  $vector[0][1]='9';
  $vector[0][2]='14';
  $vector[1][0]='18';
  $vector[1][1]='4';
  $vector[1][2]='27';
  $vector[2][0]='17';
  $vector[2][1]='5';
  $vector[2][2]='27';
  $vector[3][0]='42';
  $vector[3][1]='3';
  $vector[3][2]='16';
  $vector[4][0]='17';
  $vector[4][1]='1';
  $vector[4][2]='14';
  $vector[5][0]='64';
  $vector[5][1]='6';
  $vector[5][2]='14';
  $vector[6][0]='75';
  $vector[6][1]='3';
  $vector[6][2]='15';
  $vector[7][0]='17';
  $vector[7][1]='4';
  $vector[7][2]='20';
  $vector[8][0]='51';
  $vector[8][1]='5';
  $vector[8][2]='19';
  $vector[9][0]='46';
  $vector[9][1]='1';
  $vector[9][2]='14';
  $vector[10][0]='65';
  $vector[10][1]='7';
  $vector[10][2]='14';
  $vector[11][0]='82';
  $vector[11][1]='1';
  $vector[11][2]='19';
  $vector[12][0]='16';
  $vector[12][1]='4';
  $vector[12][2]='16';
  $vector[13][0]='38';
  $vector[13][1]='6';
  $vector[13][2]='19';
  $vector[14][0]='84';
  $vector[14][1]='6';
  $vector[14][2]='14';
  $vector[15][0]='33';
  $vector[15][1]='11';
  $vector[15][2]='3';
  $vector[16][0]='41';
  $vector[16][1]='11';
  $vector[16][2]='7';
  $vector[17][0]='28';
  $vector[17][1]='11';
  $vector[17][2]='17';
  $vector[18][0]='45';
  $vector[18][1]='11';
  $vector[18][2]='16';
  $vector[19][0]='12';
  $vector[19][1]='11';
  $vector[19][2]='26';
  $vector[20][0]='26';
  $vector[20][1]='11';
  $vector[20][2]='9';
  $vector[21][0]='36';
  $vector[21][1]='11';
  $vector[21][2]='10';
  $vector[22][0]='18';
  $vector[22][1]='11';
  $vector[22][2]='6';
  $vector[23][0]='14';
  $vector[23][1]='11';
  $vector[23][2]='11';
  $vector[24][0]='15';
  $vector[24][1]='11';
  $vector[24][2]='11';
  $vector[25][0]='13';
  $vector[25][1]='11';
  $vector[25][2]='24';
  $vector[26][0]='72';
  $vector[26][1]='7';
  $vector[26][2]='18';
  $vector[27][0]='74';
  $vector[27][1]='4';
  $vector[27][2]='28';
  $vector[28][0]='48';
  $vector[28][1]='11';
  $vector[28][2]='13';
  $vector[29][0]='48';
  $vector[29][1]='8';
  $vector[29][2]='18';
  $vector[30][0]='48';
  $vector[30][1]='4';
  $vector[30][2]='14';
  $vector[31][0]='22';
  $vector[31][1]='7';
  $vector[31][2]='14';
  $vector[32][0]='22';
  $vector[32][1]='4';
  $vector[32][2]='18';
  $vector[33][0]='22';
  $vector[33][1]='5';
  $vector[33][2]='14';
  $vector[34][0]='22';
  $vector[34][1]='4';
  $vector[34][2]='14';
  $vector[35][0]='22';
  $vector[35][1]='11';
  $vector[35][2]='13';
  $vector[36][0]='53';
  $vector[36][1]='4';
  $vector[36][2]='19';
  $vector[37][0]='53';
  $vector[37][1]='5';
  $vector[37][2]='19';
  $vector[38][0]='53';
  $vector[38][1]='6';
  $vector[38][2]='19';
  $vector[39][0]='16';
  $vector[39][1]='1';
  $vector[39][2]='14';
  $vector[40][0]='16';
  $vector[40][1]='2';
  $vector[40][2]='14';
  $vector[41][0]='61';
  $vector[41][1]='11';
  $vector[41][2]='24';
  $vector[42][0]='24';
  $vector[42][1]='11';
  $vector[42][2]='10';
  $vector[43][0]='24';
  $vector[43][1]='7';
  $vector[43][2]='30';*/
  //FIN DATOS PRUEBA
  //parametros de la función
  list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0) {
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
  $tipodonto=1;
  //fin parametros
  //
  $vector[0][0]='81';
  $vector[0][1]='11';
  $vector[0][2]='7';
  $vector[1][0]='52';
  $vector[1][1]='11';
  $vector[1][2]='10';
  $vector[2][0]='13';
  $vector[2][1]='11';
  $vector[2][2]='23';
  $vector[3][0]='14';
  $vector[3][1]='11';
  $vector[3][2]='24';
  $vector[4][0]='15';
  $vector[4][1]='11';
  $vector[4][2]='9';
  $vector[5][0]='16';
  $vector[5][1]='8';
  $vector[5][2]='21';
  $vector[6][0]='17';
  $vector[6][1]='6';
  $vector[6][2]='25';
  $vector[7][0]='18';
  $vector[7][1]='6';
  $vector[7][2]='26';
  $vector[8][0]='21';
  $vector[8][1]='7';
  $vector[8][2]='17';
  $vector[9][0]='22';
  $vector[9][1]='7';
  $vector[9][2]='18';
  $vector[10][0]='23';
  $vector[10][1]='7';
  $vector[10][2]='27';
  $vector[11][0]='24';
  $vector[11][1]='6';
  $vector[11][2]='20';
  $vector[12][0]='25';
  $vector[12][1]='4';
  $vector[12][2]='21';
  //
  $RutaImg=Odontograma($vector,$seq,$tipodonto);
  $RutaImgSimbolos="cache/SimbolosOdontograma.png";
  $salida.="  <td>";
  $salida.="   <center> <img src=\"".$RutaImg."\" border='1'></center>"; //aqui se imprime para mostrar el grafico
  $salida.="  </td>";
/*  $salida.="  <td>";
  $salida.="   <center> <img src=\"".$RutaImgSimbolos."\" border='0'></center>"; //aqui se imprime para mostrar el grafico
  $salida.="  </td>";*/
  //WIDTH=140 HEIGHT=210
  echo $salida;
?>
