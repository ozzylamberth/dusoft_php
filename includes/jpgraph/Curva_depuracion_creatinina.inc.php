<?php

function GraficarCurvaCreatinina($todos='',$datayd2,$dataxd2,$datafd2=null)
{
	IncludeFile("classes/jpgraph-1.14/src/jpgraph.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_line.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_bar.php");//libreria actualizada jpgarph 1.14

	list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0) 
	{
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
	$Dir="cache/CurvaCreatinina$seq.png";

	$ejex=sizeof($dataxd2)+1;
	
	//inicializamos la clase graph
	$graphd = new Graph(230,230,"auto");
	$graphd->img->SetMargin(40,40,40,40);
	$graphd->SetScale("textint",0,100,0,$ejex); //scala yInicial,yfinal,Xini,Xfinal
	$graphd->SetMarginColor('white');
	$graphd->xaxis->SetTickLabels($datafd2);
	$graphd->xaxis->SetLabelAngle(90);
	$graphd->xaxis->SetTextLabelInterval(1);
	$graphd->yaxis->title->Set('Creatinina');
	$graphd->xaxis->title->Set('Mes-Año');
	//Aqui se coloca el titulo de la grafica
	$graphd->title->Set("Depuración de Creatinina");
	$graphd->title->SetFont(FF_FONT1,FS_BOLD);  //fuente del titulo.

	/*terminamos la inicializacion del grafico */
	if($todos)
	{
		//Aqui se crea la union o linea de los puntos para realizar la grafica
		$p1=new LinePlot($datayd2,$dataxd2);
		$p1->SetColor("black");
		$p1->SetWeight(1);
		$p1->mark->SetType(MARK_IMG_MBALL,'blue','0.5');
		$p1->SetCenter();
		//final de la grafica

		//Aqui se crea el primer area de grafica
		$arrp2=array(60,60);
		$arrp2x=array(0,$ejex);
		$p2 = new LinePlot($arrp2,$arrp2x);
		$p2->AddArea(0,$ejex,LP_AREA_FILLED,"azure3");
		//Final de segundo area
		
		//Aqui se crea el segundo area
		$arrp3=array(100,100);
		$arrp3x=array(0,$ejex);
		$p3 = new LinePlot($arrp3,$arrp3x);
		$p3->AddArea(0,$ejex,LP_AREA_FILLED,"beige");
		//Final del terecer area

		$graphd->Add($p3);
		$graphd->Add($p2);
		$graphd->Add($p1);
		/*FIN DE LA GRAFICA 1*/

		$graphd->Stroke($Dir); //generamos la imagen.
	}
	else
	{
		$txt=new Text("No Existen\ndatos para\ngraficar.\nSIIS");
		$txt->Pos(0.5,0.5,"center","center");
		$txt->SetFont(FF_FONT1,FS_BOLD);
		$txt->ParagraphAlign('cenetered');
		$txt->SetBox('azure2','navy','gray');
		$txt->SetColor("darkblue",'1');
		$graphd->AddText($txt);
		
		$graphd->Stroke($Dir); //generamos la imagen.
	}
	
	return $Dir;
}

?>
