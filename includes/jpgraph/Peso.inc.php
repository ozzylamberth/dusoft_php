<?php

function GraficarControlPeso($todos='',$datayp2,$dataxp2,$datafd2)
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
	$Dir="cache/peso$seq.png";
	
	$ejex=sizeof($dataxp2)+1;
	
	//Inicializamos el grafico.
	$graphp = new Graph(230,230,'auto');
	$graphp->img->SetMargin(40,40,40,40);
	$graphp->SetScale("textlin",30,230,0,$ejex); //OJO CON ESTAS COORDENADAS
	$graphp->xgrid->Show();
	$graphp->xgrid->SetColor('gray@0.5');
	$graphp->ygrid->SetColor('gray@0.5');
	$graphp->SetMarginColor('white');
	$graphp->xaxis->SetTickLabels($datafd2);
	$graphp->xaxis->SetLabelAngle(90);
	$graphp->xaxis->SetTextLabelInterval(1);
	$graphp->yaxis->title->Set('Peso');
	$graphp->xaxis->title->Set('Mes-Año');
	// title
	$graphp->title->Set("Peso");
	$graphp->title->SetFont(FF_FONT1,FS_BOLD);


	// make sure that the X-axis is always at the
	// bottom at the plot and not just at Y=0 which is
	// the default position
	$graphp->xaxis->SetPos('min');
	
	/*FIN inicializacion del grafico*/
  if($todos)
	{
		//Aqui se crea la union o linea de los puntos para realizar la grafica
		$pp2=new LinePlot($datayp2,$dataxp2);
		$pp2->SetColor("black");
		$pp2->SetWeight(1);
		$pp2->mark->SetType(MARK_IMG_MBALL,'blue','0.5');
		$pp2->SetCenter();
		//final de la grafica
		
		$graphp->Add($pp2);
		
		$graphp->Stroke($Dir);  //Generamos la imagen.
		
		/*finalizamos la generacion de la grafica. */
  }
	else
	{
		$txt=new Text("No Existen\ndatos para\ngraficar.\nSIIS");
		$txt->Pos(0.5,0.5,"center","center");
		$txt->SetFont(FF_FONT1,FS_BOLD);
		$txt->ParagraphAlign('cenetered');
		$txt->SetBox('azure2','navy','gray');
		$txt->SetColor("darkblue",'1');
		$graphp->AddText($txt);

		$graphp->Stroke($Dir);  //Generamos la imagen.
	}
	return $Dir;
}

?>
