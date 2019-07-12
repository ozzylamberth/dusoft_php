<?php

/**
 * $Id: Cuello_uterino.inc.php,v 1.2 2005/06/07 18:28:17 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

function GraficarControlCuelloUterino($todos='',$datay2,$datax2)
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
	$Dir="cache/Incremento_cuello_uterino$seq.png";


		// Inicializamos el grafico.
		$graphu = new Graph(220,220,'auto');
		$graphu->SetScale("linlin",7,35,13,40); //ESTAS COORDENADAS no se cambian.


		// esto pinta las grillas
		$graphu->xgrid->Show();
		$graphu->xgrid->SetColor('gray@0.5');
		$graphu->ygrid->SetColor('gray@0.5');
		$graphu->SetMarginColor('white');


		// title
		$graphu->title->Set("Incremento Cuello Uterino");
		$graphu->title->SetFont(FF_FONT1,FS_BOLD);
		//$graphu->xaxis->SetFont(FF_LUXIS,FS_NORMAL,20);  //prueba


		// make sure that the X-axis is always at the
		// bottom at the plot and not just at Y=0 which is
		// the default position
		$graphu->xaxis->SetPos('min');


	if($todos)
	{

						/*Pintamos las coordenadas iniciales del grafico uterino*/

						// $datay2 = array(15,23,27);
							//$datax2 = array((15-0.5),(23-1.0),(27-1.0));

							// Creamos la ultima linea
							$datayu = array();
							$dataxu = array();

							// Creamos la primera linea
							$datay1l = array();
							$datax1l = array();

							// Creamos la segunda linea
							$datay2l = array();
							$datax2l = array();

							//for de la ultima linea
							for($y=7; $y<36; $y+=2)
							{  $datayu[] = $y;  }

							for($x=13; $x<43; $x+=2)
							{  $dataxu[] = $x;  }//print_r($datax);

							//for de la segunda linea
							for($y=13; $y<36; $y+=2)
							{  $datay2l[] = $y;  }

							for($x=13; $x<37; $x+=2)
							{  $datax2l[] = $x;  }//print_r($datax);


							//for de la primer linea
							for($y=10; $y<36; $y+=2)
							{  $datay1l[] = $y;  }

							for($x=13; $x<39; $x+=2)
							{  $datax1l[] = $x;  }//print_r($datax);

						/*coordenadas alineadas para la grafica de cuello uterino*/



						/* pintamos las lineas */

							// Create the regression line
							$lplot = new LinePlot($datayu,$dataxu);
							$lplot->SetWeight(2);
							$lplot->SetColor('darkgreen');

							$lplot2 = new LinePlot($datay2l,$datax2l);
							$lplot2->SetWeight(2);
							$lplot2->SetColor('azure3');

							$lplot1= new LinePlot($datay1l,$datax1l);
							$lplot1->SetWeight(2);
							$lplot1->SetColor('darkblue');

							//Aqui se crea la union o linea de los puntos para realizar la grafica
							$p=new LinePlot($datay2,$datax2);
							$p->SetColor("black");
							$p->SetWeight(1);
							$p->mark->SetType(MARK_IMG_MBALL,'red','0.5');
							$p->SetCenter();
							//final de la grafica

							// Add the pltos to the line
							$graphu->Add($lplot);
							$graphu->Add($lplot2);
							$graphu->Add($lplot1);
							$graphu->Add($p);

							$graphu->Stroke($Dir);  // Se genera la grafica.
							/* generamos el grafico */
	}
	else
	{
			$txt=new Text("No Existen\ndatos para\ngraficar.\nSIIS");
			$txt->Pos(0.5,0.5,"center","center");
			$txt->SetFont(FF_FONT1,FS_BOLD);
			$txt->ParagraphAlign('cenetered');
			$txt->SetBox('azure2','navy','gray');
			$txt->SetColor("darkblue",'1');
			//$graphd->AddText($txt);
			$graphu->AddText($txt);
			//$graphp->AddText($txt);


			$graphu->Stroke($Dir);  // Se genera un grafico diciendo no hay puntos que mostrar.
	}

	return $Dir;
}


?>
