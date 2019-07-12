<?php

/*ARREGLOS SOLAMENTE PARA LA FUNCION DIASTOLICA*/
/*ARREGLO DE LOS CUALES LO MANDARA EL DOCTOR*/

//Se debe restar el valor que llega de X,en (1) entero,ya que por ejemplo
//x=25,x=25-1 para poder que nos muestre 25 y cuando el valor sea x=24, habra que restar 0.5
//$datayd2 = array(40,55,60,90);
//$dataxd2 = array((24-0.5),28,33,35);
/*FIN*/


function GraficarPresionArterialDiastolica($todos='',$datayd2,$dataxd2)
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
	$Dir="cache/Presion_arterial_diastolica$seq.png";


  /*Inicializamos el grafico*/

	//aqui se colocan los datos de los arreglos para crear el area de 40 a 70,ESTO NO SE MODIFICA!
		$datayd = array(70,70,75,77,79,80,85,85,85);
		$dataxd = array(14.5,25,26,29,30,32,35,36,40.5);
		//estos arreglos son para la funcion diastolica


		//inicializamos la clase graph de la GRAFICA DIASTOLICA.
		$graphd = new Graph(220,220);
		$graphd->img->SetMargin(40,30,40,30);
		$graphd->SetScale("textint",40,140,15,41); //scala yInicial,yfinal,Xini,Xfinal
		$graphd->SetMarginColor('white');

		//Aqui se coloca el titulo de la grafica
		$graphd->title->Set("Presion Arterial Diastolica");
		$graphd->title->SetFont(FF_FONT1,FS_BOLD);  //fuente del titulo.

	/*terminamos la inicializacion del grafico */

	if($todos)
	{
									//Creamos el  area de la grafica (1)
										$p1 = new LinePlot($datayd,$dataxd);
										$p1->AddArea(0,8,LP_AREA_FILLED,"cadetblue4");
										$p1->SetColor("darkblue@0.7");
										$p1->SetCenter();
										//final del primer area


										//Aqui se crea la union o linea de los puntos para realizar la grafica
										$p2=new LinePlot($datayd2,$dataxd2);
										$p2->SetColor("black");
										$p2->SetWeight(1);
										$p2->mark->SetType(MARK_IMG_MBALL,'blue','0.5');
										$p2->SetCenter();
										//final de la grafica


										//Aqui se crea el segundo area de grafica
										$arrp3=array(90,90);
										$arrp3x=array(14.5,40.5);
										$p3 = new LinePlot($arrp3,$arrp3x);
										$p3->AddArea(0,8,LP_AREA_FILLED,"azure3");

										//Final de segundo area


										//Aqui se crea el tercer area
										$arrp3=array(140,140);
										$arrp3x=array(14.5,40.5);
										$p4 = new LinePlot($arrp3,$arrp3x);
										$p4->AddArea(0,8,LP_AREA_FILLED,"beige");
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
