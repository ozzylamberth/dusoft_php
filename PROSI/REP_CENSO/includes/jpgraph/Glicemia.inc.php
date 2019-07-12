<?php

/**
 * $Id: Glicemia.inc.php,v 1.2 2005/06/07 18:28:17 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

function GraficarGlucometria($Vmax,$Vmin,$DatosF,$todos,$fechaE,$DatosH)
{
	IncludeFile("classes/jpgraph-1.14/src/jpgraph.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_line.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_bar.php");//libreria actualizada jpgarph 1.14

	list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0) {
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
	$Dir="cache/Glucometria$seq.png";

	if(!$todos){
		foreach($DatosH as $key =>$value){
			$ydata[count($ydata)]=$value['glucometria'];
			$xdata[count($xdata)]=$value['horas'];
		}

	if (sizeof($xdata)==1){
		array_push($xdata,"-");//puede ser "" ó "-" pero "." no solo funciona en jpgraph 1.13
		array_push($ydata,"-");//puede ser "" ó "-" pero "." no solo funciona en jpgraph 1.13 
	}

// 		echo "<br>H->";
// 		print_r($ydata);
// 		print_r($xdata);

		asort($ydata);
		$v_max=end($ydata);
		$v_min=reset($ydata);
		ksort($ydata);
/*
		if ($v_min<$Vmin){
			$VminY=$v_min;
		}
		else{
			$VminY=$Vmin;
		}
*/
		if ($v_max>$Vmax){
//			$VmaxY=$v_max;
			$VarLine=ceil(($v_max/150));
			if (!$VarLine){
				$VarLine=0.5;
			}
		}
		else{
			$VarLine=($Vmax/(($v_max*750)/$Vmax));
//			$VmaxY=$Vmax;
		}
/*
		echo "<br>Vmax->".$Vmax."<br>Vmin->".$Vmin."<br>";
		echo "<br>VmaxY->".$VmaxY."<br>VminY->".$VminY."<br>";
		echo "<br>Xmax->".$v_max."<br>Xmin".$v_min."<br>";
		echo "<br>VarLine->".$VarLine;
		echo "<br>Xdata->".reset($xdata)."--".end($xdata);
*/
		$i=0;
		$graph = new Graph(450,280,'manual');
		$graph->SetScale("textlin");
		$graph->yaxis->HideZeroLabel();
		$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#FFFFFF@0.5');
		$graph->xgrid->Show();
		$graph->SetMarginColor('white');
		$graph->img->SetMargin(70,50,30,55);
		$graph->title->Set("Glucometria");
		$graph->xaxis->title->Set($fechaE);

		$text=new Text();
		$text->Set("Glucometria");
		$text->Pos(20,100,center);
		$text->SetAngle(90);
		$graph->Add($text);

		$lineplot=new LinePlot($ydata);
		$lineplot->SetWeight(2);
		$lineplot->SetColor('blue');
		$lineplot->mark->SetType(MARK_IMG_MBALL,'red','0.5');
		$lineplot->value->SetColor('black');

		//Aqui se sacan los valores minimos de la grafica..
		$band = new PlotBand(HORIZONTAL,BAND_SOLID,$Vmin,$Vmin+$VarLine,'green');
		$band->ShowFrame(false);
		$graph->AddBand($band);

		//Aqui se sacan los valores maximos de la grafica..
		$band1 = new PlotBand(HORIZONTAL,BAND_SOLID,$Vmax-$VarLine,$Vmax,'red');
		$band1->ShowFrame(false);
		$graph->AddBand($band1);
		$graph->Add($lineplot);
		$graph->xaxis->SetTickLabels($xdata);
		$graph->xaxis->SetFont(FF_LUXIS,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		$graph->SetShadow();
		$graph->Stroke($Dir);
	}
	else{
		foreach($DatosF as $key =>$value){
			$ydata[count($ydata)]=number_format($value['media'],0,",",".");
			$xdata[count($xdata)]=$key;
			$ydata1[count($ydata1)]=$value['vmax'];
			$ydata2[count($ydata2)]=$value['vmin'];
		}

		asort($ydata2);
		$v_min=reset($ydata2);
		asort($ydata1);
		$v_max=end($ydata1);
		ksort($ydata1);
		ksort($ydata2);

		if ($v_min<$Vmin){
			$VminY=$v_min;
		}
		else{
			$VminY=$Vmin;
		}

		if ($v_max>$Vmax){
			$VmaxY=$v_max;
			$VarLine=ceil(($v_max/100));
			if (!$VarLine){
				$VarLine=0.5;
			}
		}
		else{
			$VmaxY=$Vmax;
			$VarLine=0.5;
		}

		$i=0;
		$graph = new Graph(480,280,'auto');
		$graph->SetScale("textlin");
		$graph->yaxis->HideZeroLabel();
		$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#FFFFFF@0.5');
		$graph->xgrid->Show();
		$graph->SetMarginColor('white');
		$graph->img->SetMargin(70,110,20,70);
		$graph->title->Set("Glucometria");
		$graph->xaxis->title->Set($fechaE);

		$text=new Text();
		$text->Set("Glucometria");
		$text->Pos(15,100,center);
		$text->SetAngle(90);
		$graph->Add($text);

		$lineplot=new LinePlot($ydata);
		$lineplot->SetWeight(3);
		$lineplot->SetColor('blue');
		$lineplot->mark->SetType(MARK_IMG_MBALL,'yellow','0.6');
		$lineplot->value->SetColor('black');
		$lineplot->SetLegend('Media');
		$lineplot->SetCenter();

    //$color=array('#FFFFFF','#EFEFEF','#EFEFEF','#EFEFEF','#EFEFEF');
		$lineplot1=new LinePlot($ydata1);
		$lineplot1->SetWeight(2);
		$lineplot1->SetColor('darkblue');
		$lineplot1->mark->SetType(MARK_IMG_MBALL,'red','0.35');
		$lineplot1->value->SetColor('black');
		$lineplot1->SetLegend('Maximo');
		$lineplot1->SetCenter();

		$lineplot2=new LinePlot($ydata2);
		$lineplot2->SetWeight(2);
		$lineplot2->SetColor('cornflowerblue');
		$lineplot2->mark->SetType(MARK_IMG_MBALL,'green','0.35');
		$lineplot2->value->SetColor('black');
		$lineplot2->SetLegend('Minimo');
		$lineplot2->SetCenter();

				//Aqui se sacan los valores minimos de la grafica..
		$band = new PlotBand(HORIZONTAL,BAND_SOLID,$Vmin,$Vmin+$VarLine,'green');
		$band->ShowFrame(false);
		$graph->AddBand($band);

		//Aqui se sacan los valores maximos de la grafica..
		$band1 = new PlotBand(HORIZONTAL,BAND_SOLID,$Vmax-$VarLine,$Vmax,'red');
		$band1->ShowFrame(false);
		$graph->AddBand($band1);
		$graph->Add($lineplot);
		$graph->Add($lineplot2);
		$graph->Add($lineplot1);
		$graph->xaxis->SetTickLabels($xdata);
		$graph->xaxis->SetFont(FF_LUXIS,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		$graph->SetShadow();
		$graph->legend->SetLayout(LEGEND_VER);
		$graph->legend->Pos(0.77,0.9,"left","bottom");
		$graph->Stroke($Dir);
	}
	return $Dir;
}
?>
