<?php

function GraficarControlPesoMaterno($todos='',$datayp2,$dataxp2)
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
	$Dir="cache/Incremento_peso_materno$seq.png";



	//  Inicializamos el grafico.

			$graphp = new Graph(220,220,'auto');
			$graphp->SetScale("linlin",0,16,16,42); //OJO CON ESTAS COORDENADAS

			$graphp->xgrid->Show();
			$graphp->xgrid->SetColor('gray@0.5');
			$graphp->ygrid->SetColor('gray@0.5');
			$graphp->SetMarginColor('white');

			// title
			$graphp->title->Set("Incremento Peso Materno");
			$graphp->title->SetFont(FF_FONT1,FS_BOLD);


			// make sure that the X-axis is always at the
			// bottom at the plot and not just at Y=0 which is
			// the default position
			$graphp->xaxis->SetPos('min');

		/*FIN inicializacion del grafico*/


  if($todos)
	{

		/*ARREGLOS SOLAMENTE PARA LA FUNCION DE INCREMENTO DE PESO PARA EMBARAZADAS*/

			// Create some "fake" regression data
			$datayp = array();
			$dataxp = array();
			$datayp1 = array();
			$dataxp1 = array();
			$datayp2a = array();
			$dataxp2a = array();

			for($y=1; $y<15; $y+=2)
			{  $datayp[] = $y;  }

			for($x=16; $x<44; $x+=4)
			{  $dataxp[] = $x; }//print_r($datax);

			for($y=3; $y<19; $y+=2)
			{  $datayp1[] = $y;  }

			for($x=16; $x<46; $x+=4)
			{  $dataxp1[] = $x; }//print_r($datax);

			for($y=0; $y<15; $y+=2)
			{  $datayp2a[] = $y; }

			for($x=16; $x<46; $x+=4)
			{  $dataxp2a[] = $x;  }//print_r($datax);
			
      	/*pintamos el grafico materno*/

					// Create the regression line
					$plot = new LinePlot($datayp,$dataxp);
					$plot->SetWeight(2);
					$plot->SetColor('darkgreen');



					//Aqui se crea la union o linea de los puntos para realizar la grafica
					$pp2=new LinePlot($datayp2,$dataxp2);
					$pp2->SetColor("black");
					$pp2->SetWeight(1);
					$pp2->mark->SetType(MARK_IMG_MBALL,'blue','0.5');
					$pp2->SetCenter();
					//final de la grafica

					$plot3 = new LinePlot($datayp1,$dataxp1);
					$plot3->SetWeight(2);
					$plot3->SetColor('indianred');

          $plot2 = new LinePlot($datayp2a,$dataxp2a);
					$plot2->SetWeight(2);
					$plot2->SetColor('darkgray');

					// Add the pltos to the line
					$graphp->Add($plot);
					$graphp->Add($plot2);
					$graphp->Add($plot3);
					$graphp->Add($pp2);

					$graphp->Stroke($Dir);  //Generamos la imagen.

         /*finalizamos la generacion de la grafica. */
  }
	else
	{

	//echo "entro al else de la grafica";
	//exit();
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
