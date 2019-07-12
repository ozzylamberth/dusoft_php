<?php

/*ARREGLOS SOLAMENTE PARA LA FUNCION DIASTOLICA*/
/*ARREGLO DE LOS CUALES LO MANDARA EL DOCTOR*/

//Se debe restar el valor que llega de X,en (1) entero,ya que por ejemplo
//x=25,x=25-1 para poder que nos muestre 25 y cuando el valor sea x=24, habra que restar 0.5
//$datayd2 = array(40,55,60,90);
//$dataxd2 = array((24-0.5),28,33,35);
/*FIN*/

function GraficarPresionArterial($todos='',$datayda2,$dataydb2,$dataxd2,$datadf1)
{

	IncludeFile("classes/jpgraph-1.14/src/jpgraph.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_line.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_bar.php");//libreria actualizada jpgarph 1.14
	IncludeFile("classes/jpgraph-1.14/src/jpgraph_error.php");//libreria actualizada jpgarph 1.14

	
	list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0)
	{
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
	$Dir="cache/Presion_arterial$seq.png";

	$ejex=sizeof($dataxd2)+1;

	//inicializamos la clase graph.
	$graphd = new Graph(230,230,"auto");
	$graphd->img->SetMargin(40,40,40,40);
	$graphd->SetScale("textlin",40,260,0,$ejex); 
	$graphd->xaxis->SetTickLabels($datadf1);
	$graphd->xaxis->SetLabelAngle(90);
	$graphd->SetMarginColor('white');
	$graphd->yaxis->title->Set('Presion Arterial');
	$graphd->xaxis->title->Set('Mes-Año');
	
	//Aqui se coloca el titulo de la grafica
	$graphd->title->Set("Presion Arterial");
	$graphd->title->SetFont(FF_FONT1,FS_BOLD);  //fuente del titulo.
	//terminamos la inicializacion del grafico 

	if($todos)
	{
		//Creamos el  area de la grafica (1)
		$arrp1y=array(70,70);
		$arrp1x=array(0,$ejex);
		$p1 = new LinePlot($arrp1y,$arrp1x);
		$p1->AddArea(0,$ejex,LP_AREA_FILLED,"beige");
		$p1->SetColor("darkblue@0.7");
		$p1->SetCenter();
		//final del primer area

		//Aqui se crea la union o linea de los puntos para realizar la grafica
		
		for($k=0;$k<sizeof($datayda2);$k++)
		{
			$errdatay[]=$datayda2[$k];
			$errdatay[]=$dataydb2[$k];
		}
		
		$p2=new ErrorPlot($errdatay);
		$p2->SetColor("blue");
		$p2->SetWeight(5);
		
		//final de la grafica
		
		//Aqui se crea el segundo area de grafica
		$arrp3=array(120,120);
		$arrp3x=array(0,$ejex);
		$p3 = new LinePlot($arrp3,$arrp3x);
		$p3->AddArea(0,$ejex,LP_AREA_FILLED,"cadetblue4");
		//Final de segundo area
		
		//Aqui se crea el tercer area
		$arrp3=array(260,260);
		$arrp3x=array(0,$ejex);
		$p4 = new LinePlot($arrp3,$arrp3x);
		$p4->AddArea(0,$ejex,LP_AREA_FILLED,"beige");
		//Final del terecer area
		
		$graphd->Add($p4);
		$graphd->Add($p3);
		$graphd->Add($p1);
		$graphd->Add($p2);
		
		//FIN DE LA GRAFICA 1

		$graphd->Stroke($Dir); //generamos la imagen.
	}
	else
	{
		$txt=new Text("No Existen\ndatos para\ngraficar.\nSIIS");
		$txt->Pos(0.5,0.5,"center","center");
		$txt->SetFont(FF_FONT1,FS_BOLD);
		$txt->ParagraphAlign('centered');
		$txt->SetBox('azure2','navy','gray');
		$txt->SetColor("darkblue",'1');
		$graphd->AddText($txt);
		
		$graphd->Stroke($Dir); //generamos la imagen.
	}
	 
	return $Dir;
}

?>