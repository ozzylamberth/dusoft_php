<?php

/**
 * $Id: Temperatura_Y_Humedad.inc.php,v 1.2 2005/06/07 18:28:18 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

function GraficarTemperatura($xdata,$ydata,$Grafica,$seq)
{
  list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0) {
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
 	if($Grafica==1)
	{
	  $palabra="Temperatura";
		$Dir="cache/Temperatura$seq.png";
	}
	else
	{
	  $palabra="Humedad";
		$Dir="cache/Humedad$seq.png";
	}
	IncludeFile("classes/jpgraph-1.14/src/jpgraph.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_line.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_bar.php");//libreria actualizada jpgarph 1.14

		$graph = new Graph(750,380,'auto');
		$graph->SetScale("textlin");
		$graph->yaxis->HideZeroLabel();
		$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#FFFFFF@0.5');
		$graph->xgrid->Show();
		$graph->SetMarginColor('white');
		$graph->img->SetMargin(90,40,80,100); // (-(izq),)
		$graph->title->Set($palabra);//TITULO
		$graph->xaxis->title->Set($fechaE);

		$text=new Text();
		$text->Set($palabra);//TITULO
		$text->Pos(20,100,center);
		$text->SetAngle(90);
		$graph->Add($text);

		$lineplot1=new LinePlot($ydata);
		$lineplot1->SetWeight(2);
		$lineplot1->SetColor('darkblue');
		$lineplot1->mark->SetType(MARK_IMG_MBALL,'red','0.35');
		$lineplot1->value->SetColor('black');
		$lineplot1->SetLegend($palabra.'/fecha');//titulo
		$lineplot1->SetCenter();
    $graph->Add($lineplot1);
		$graph->xaxis->SetTickLabels($xdata);
		$graph->xaxis->SetFont(FF_LUXIS,FS_BOLD,7);
		$graph->xaxis->SetLabelAngle(45);
		$graph->SetShadow();
		$graph->legend->SetLayout(LEGEND_VER);
		$graph->legend->Pos(0.85,0.95,"center","bottom");
		$graph->Stroke($Dir);
	return $Dir;
}
?>
