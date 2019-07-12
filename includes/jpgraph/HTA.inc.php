<?php

function GraficarHTA($todos='',$datay,$datax,$datafd1=null)
{
	IncludeFile("classes/jpgraph-1.14/src/jpgraph.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_line.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_bar.php");//libreria actualizada jpgarph 1.14

	list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq');";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0)
	{
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
	$Dir="cache/Presion_arterial$seq.png";

	//inicializamos la clase graph de la GRAFICA
	
	if(sizeof($datax)<12)
		$ejex=12;
	else
		$ejex=sizeof($datax)+1;
		
	$graphd = new Graph(250,250,"auto");
	$graphd->img->SetMargin(40,40,40,40);
	$graphd->SetScale("textint",0,260,0,$ejex); 
	$graphd->xaxis->SetTickLabels($datafd1);
	$graphd->SetMarginColor('white');
	$graphd->xaxis->SetLabelAngle(90);
	$graphd->yaxis->title->Set('Presion Arterial');
	$graphd->xaxis->title->Set('Mes-Año');
	
	//Aqui se coloca el titulo de la grafica
	$graphd->title->Set("Presion Arterial HTA");
	$graphd->title->SetFont(FF_FONT1,FS_BOLD);  //fuente del titulo.
	/*terminamos la inicializacion del grafico */
	
	if($todos)
	{
		//Creamos el  area de la grafica (1)
		$datayd = array(90,90);
		$dataxd = array(0,$ejex);
		$p1 = new LinePlot($datayd,$dataxd);
		$p1->AddArea(0,$ejex,LP_AREA_FILLED,"cadetblue4");
		$p1->SetColor("darkblue@0.7");
		$p1->SetCenter();
		//final del primer area

		//Aqui se crea la union o linea de los puntos para realizar la grafica
		$p2=new LinePlot($datay,$datax);
		$p2->SetColor("black");
		$p2->SetWeight(1);
		$p2->mark->SetType(MARK_IMG_MBALL,'red','0.5');
		$p2->SetCenter();
		//final de la grafica
		
		//Aqui se crea el segundo area de grafica (2)
		$arrp3=array(140,140);
		$arrp3x=array(0,$ejex);
		$p3 = new LinePlot($arrp3,$arrp3x);
		$p3->AddArea(0,$ejex,LP_AREA_FILLED,"azure3");
		//Final de segundo area
		
		
		//Aqui se crea el tercer area de grafica (3)
		$arrp3=array(260,260);
		$arrp3x=array(0,$ejex);
		$p4 = new LinePlot($arrp3,$arrp3x);
		$p4->AddArea(0,$ejex,LP_AREA_FILLED,"beige");
		//Final del terecer area
		
		$graphd->Add($p4);
		$graphd->Add($p3);
		$graphd->Add($p1);
		$graphd->Add($p2);
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