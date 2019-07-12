<?php

/**
 * $Id: Odontograma_graphic.inc28012005.php,v 1.2 2005/06/07 18:28:18 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

 function Muela($n,$val)
  {
  print_r($val);
  list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0) {
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
	$Dir="cache/Odontograma$seq.png";

  //$Dir="cache/muela.png";
  include_once ("classes/jpgraph-1.14/src/jpgraph.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_line.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvas.php");//
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvtools.php");//

  $g = new CanvasGraph(850,700,'auto');
  //$g->SetMargin(5,11,6,11);
  $g->SetShadow();
  $g->SetMarginColor("white");
  $g->InitFrame();
  $x1=0;
  $y1=0;
  $i=0;
  $y=0;
  $j=0;
  $x=0;
  $k=0;
  $w=0;
  $m=0;
  $p=0;
  $label=8;
  $label1=5;
$bool=0;
 $g=arcos($g);
  $t=Etiquetas($g,$n);
  //$t->Stroke($g->img);

    for ($l=0;$l<sizeof($n);)
    {
      while ($i<$n[$l])
      {
       //ETIQUETAS
       //FIN ETIQUETAS
        if ($label>0) //PRIMER CUADRANTE
        {
         for ($x1=0;$x1<1;$x1++)
           for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
               if ($val[$x2][$y2]=='18')
               {
                 $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                 $bool=1;
                 $label-='1';
               }//fin if
             }//fin for x2,y2

            }//fin for x1,y1
             if ($bool==1) $y+=50;
             if ($bool==0)
              {
                $g=DibujarSano($g,$y,$x);
                $y+=50;
                $label-=1;
              }

         for ($x1=0;$x1<1;$x1++)
           for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
               if ($val[$x2][$y2]=='17')
                  {
                   $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                   $bool=1;
                   $label-=1;
                  }//fin if
               }//fin for x2
             }//fin for x1
             if ($bool==1) $y+=50;
              if ($bool==0)
              {
                $g=DibujarSano($g,$y,$x);
                $y+=50;
                $label-=1;
              }

              for ($x1=0;$x1<1;$x1++)
                for ($y1=0;$y1<1;$y1++)
                  {
                  for ($x2=0;$x2<sizeof($val);$x2++)
                    for ($y2=0;$y2<sizeof($val);$y2++)
                    {
                    if ($val[$x2][$y2]=='16')
                        {
                        $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                        $bool=1;
                        $label-=1;
                        }//fin if
                    }//fin for x2
                  }//fin for x1
                    if ($bool==1) $y+=50;
                    if ($bool==0)
                    {
                      $g=DibujarSano($g,$y,$x);
                      $y+=50;
                      $label-=1;
                    }

                    for ($x1=0;$x1<1;$x1++)
                      for ($y1=0;$y1<1;$y1++)
                        {
                        for ($x2=0;$x2<sizeof($val);$x2++)
                          for ($y2=0;$y2<sizeof($val);$y2++)
                          {echo $bool;
                          if ($val[$x2][$y2]=='15')
                              {
                              $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                              $bool=1;
                              $label-=1;
                              }//fin if
                          }//fin for x2

                        }//fin for x1
                          if ($bool==1) $y+=50;
                          if ($bool==0)
                          {
                            $g=DibujarSano($g,$y,$x);
                            $y+=50;
                            $label-=1;
                          }

                          for ($x1=0;$x1<1;$x1++)
                            for ($y1=0;$y1<1;$y1++)
                              {
                              for ($x2=0;$x2<sizeof($val);$x2++)
                                for ($y2=0;$y2<sizeof($val);$y2++)
                                {
                                if ($val[$x2][$y2]=='14')
                                    { echo $bool;
                                    $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                                    $bool=1;
                                    $label-=1;
                                    }//fin if
                                }//fin for x2

                              }//fin for x1
                                if ($bool==1) $y+=50;
                                if ($bool==0)
                                {
                                  $g=DibujarSano($g,$y,$x);
                                  $y+=50;
                                  $label-=1;
                                }

                          for ($x1=0;$x1<1;$x1++)
                            for ($y1=0;$y1<1;$y1++)
                              {
                              for ($x2=0;$x2<sizeof($val);$x2++)
                                for ($y2=0;$y2<sizeof($val);$y2++)
                                {
                                if ($val[$x2][$y2]=='13')
                                    {
                                    $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                                    $bool=1;
                                    $label-=1;
                                    }//fin if
                                }//fin for x2

                              }//fin for x1
                                if ($bool==1) $y+=50;
                                if (!$bool==0)
                                {
                                  $g=DibujarSano($g,$y,$x);
                                  $y+=50;
                                  $label-=1;
                                  $bool=FALSE;
                                }

                              for ($x1=0;$x1<1;$x1++)
                                for ($y1=0;$y1<1;$y1++)
                                  {
                                  for ($x2=0;$x2<sizeof($val);$x2++)
                                    for ($y2=0;$y2<sizeof($val);$y2++)
                                    {
                                    if ($val[$x2][$y2]=='12')
                                        {
                                        $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                                        $bool=TRUE;
                                        $label-=1;
                                        }//fin if
                                    }//fin for x2
                                    if ($bool) $y+=50;

                                  }//fin for x1
                                    if (!$bool)
                                    {
                                      $g=DibujarSano($g,$y,$x);
                                      $y+=50;
                                      $label-=1;
                                      $bool=FALSE;
                                    }

                                  for ($x1=0;$x1<1;$x1++)
                                    for ($y1=0;$y1<1;$y1++)
                                      {
                                      for ($x2=0;$x2<sizeof($val);$x2++)
                                        for ($y2=0;$y2<sizeof($val);$y2++)
                                        {
                                        if ($val[$x2][$y2]=='14')
                                            {
                                            $g=DrawImage($g,$y,$x,$y2,$x2,$val,$val[$x2][$y2]);
                                            $bool=TRUE;
                                            $label-=1;
                                            }//fin if
                                        }//fin for x2
                                        if ($bool) $y+=50;

                                      }//fin for x1
                                        if (!$bool)
                                        {
                                          $g=DibujarSano($g,$y,$x);
                                          $y+=50;
                                          $label-=1;
                                        }
              //$p++;
              //}
              //$m++;

         }//fin de cuadrante 1
         //llama FUNCIÓN DIBUJARSANO
         //$g=DibujarSano($g,$y,$x);
         $i++;
         //$y+=50;
        }
      $l++;
      if ($n[$l]==10 && $k==0)
       {
         $y-=650;
         $k++;
       }
       else
         if ($n[$l]==10 && $k==1)
           $y-=500;
         else
           $y=0;
      $x+=95;
      $i=0;
    }

//SEGUNDO CUADRANTE
//  if ($label!=7)
//    {
//    }
  //LINEA SEPARADORA
  $g->img->Line(0,365,850,365);
  $g->img->Line(0,366,850,366);
  $g->img->Line(0,367,850,367);

  //LINEAS CUADRANTES
  $g->img->Line(410,0,410,365);
  $g->img->Line(0,177,900,177);

  $g=Simbolos($g);
  $g->Stroke($Dir);
  return $Dir;
 }

  //DIBUJAR DIENTE SANO
  function DibujarSano($g,$y,$x)
  {
    $g->img->SetColor('black');
    //linea superior izq
    $g->img->Line($y+20,28+$x,$y+33,45+$x);
    //linea superior der
    $g->img->Line($y+53,30+$x,$y+39,46+$x);
    //linea inferior izq
    $g->img->Line($y+34,54+$x,$y+20,73+$x);
    //linea inferior der
    $g->img->Line($y+38,53+$x,$y+53,72+$x);
    //ARCOS
    //arco superior
    $g->img->Arc($y+35,50+$x,55,55,-125,-50);
    //arco inferior
    $g->img->Arc($y+35,50+$x,55,55,50,125);
    // circulo (x,y,diameter)
    $g->img->Circle($y+35,50+$x,15);
    $g->img->Circle($y+35,50+$x,5);
    return $g;

  }

  //Figura caries obturación temporal lateral derecha
  function COT_LD($g,$y,$x)
  {
    $g->img->SetColor('black');
    //arco lateral derecho
    $g->img->filledArc($y+35,$x+50,35,35,312,-310);
    //contorno de los circulos
    $g->img->SetColor('white');
    $g->img->filledCircle($y+35,$x+50,6);
    return $g;
  }

  //obturación temporal arco superior
  function COT_S($g,$y,$x)
  {
  //PIEZA 2 SOMBREADO DE LA ZONA 1
  //arco siperior
  $g->img->SetColor('black');
  $g->img->FilledArc($y+35,50+$x,55,55,-135,-45);
  //arco inferior
/*  $g->img->Arc($y+35,50+$x,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc($y+35,50+$x,55,55,132,-140);
  $g->img->Arc($y+34,50+$x,55,55,132,-140);*/
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle($y+35,50+$x,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle($y+35,50+$x,15);
  $g->img->SetColor('black');
/*  $g->img->Circle($y+35,50+$x,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle($y+35,50+$x,5);

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line($y+20,28+$x,$y+33,45+$x);
  //linea superior der
  $g->img->Line($y+53,29+$x,$y+38,45+$x);
  //linea inferior izq
  $g->img->Line($y+33,53+$x,$y+20,73+$x);
  //linea inferior der
  $g->img->Line($y+38,50+$x,$y+53,72+$x);*/
  return $g;
  }

  //RESIBNA SUPERIOR
/************************************************/
 function Resina_S($g,$y,$x)
  {

  //PIEZA 7 - SOMBREADO DE LA ZONA 6
  //arco superior

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+30,24+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}
//FUNCIÓN AMALGAMA_SUPERIOR
/********************************************/
  function Amalgama_S($g,$y,$x)
  {
  //PIEZA 6 - SOMBREADO DE LA ZONA 5
  //arco superior
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
//
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//     //lineas de la figura
//     $g->img->SetColor('black');
//     //linea superior izq
//     $g->img->Line($y+20,28+$x,$y+33,45+$x);
//     //linea superior der
//     $g->img->Line($y+53,29+$x,$y+38,45+$x);
//     //linea inferior izq
//     $g->img->Line($y+33,53+$x,$y+20,73+$x);
//     //linea inferior der
//     $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+26);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
   return $g;
  }
  //****RESTAURACION DESADAPTADA SUPERIOR
function Restauracion_Desadaptada_S($g,$y,$x)
{
  //PIEZA 12 - SOMBREADO DE LA ZONA 11
  //simbolo X
  //Restauración desadaptada
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//     $g->img->SetColor('black');
//     //linea superior izq
//     $g->img->Line($y+20,28+$x,$y+33,45+$x);
//     //linea superior der
//     $g->img->Line($y+53,29+$x,$y+38,45+$x);
//     //linea inferior izq
//     $g->img->Line($y+33,53+$x,$y+20,73+$x);
//     //linea inferior der
//     $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="X";
  $l = new Text($txt,$y+30,$x+24);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

/************************************************/
//superficie sellada superior
function Sellada_S($g,$y,$x)
{
  //PIEZA 20 SOMBREADO DE LA ZONA 1
  //superficie sellada
  //arcos
  //arco siperior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="S";
  $t = new Text($txt,$y+31,$x+24);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
}

function COT_I($g,$y,$x)
{
  //PIEZA 3 SOMBREADO DE LA ZONA 2
  //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-47);
   //arco inferior
  $g->img->SetColor('black');
  $g->img->FilledArc($y+35,50+$x,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
/*  $g->img->SetColor('white');
  $g->img->filledArc($y+35,50+$x,55,55,132,-140);
  $g->img->Arc($y+35,50+$x,55,55,132,-140);
  // circulo (x,y,diameter)*/
/*  $g->img->SetColor('black');
  $g->img->Circle($y+35,50+$x,16);*/
  $g->img->SetColor('white');
  $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);

  //se desplaza 90 pos en el eje y
  //lineas de la figura
/*  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line($y+20,28+$x,$y+33,45+$x);
  //linea superior der
  $g->img->Line($y+53,29+$x,$y+38,45+$x);
  //linea inferior izq
  $g->img->Line($y+33,53+$x,$y+20,73+$x);
  //linea inferior der
  $g->img->Line($y+38,50+$x,$y+53,72+$x);*/
   return $g;
}

function Resina_I($g,$y,$x)
{
  //PIEZA 7 - SOMBREADO DE LA ZONA 6
  //arco superior

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
/*  $g->img->SetColor('white');
  $g->img->Arc($y+35,50+$x,55,55,132,-140);
  $g->img->filledArc($y+35,50+$x,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle($y+35,50+$x,16);
  $g->img->filledCircle($y+35,50+$x,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc($y+35,50+$x,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc($y+35,50+$x,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc($y+35,50+$x,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc($y+35,50+$x,55,55,48,130);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle($y+35,50+$x,16);
  $g->img->SetColor('black');
  $g->img->Circle($y+35,50+$x,6);

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line($y+20,28+$x,$y+33,45+$x);
  //linea superior der
  $g->img->Line($y+53,29+$x,$y+38,45+$x);
  //linea inferior izq
  $g->img->Line($y+33,53+$x,$y+20,73+$x);
  //linea inferior der
  $g->img->Line($y+38,50+$x,$y+53,72+$x);*/
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+31,66+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Amalgama_I($g,$y,$x)
{
  //PIEZA 6 - SOMBREADO DE LA ZONA 5
  //arco superior
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+68);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
   return $g;
}

function Restauracion_Desadaptada_I($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="x";
  $l = new Text($txt,$y+30,$x+66);
  $l->SetFont(FF_LUXIS,FS_NORMAL,12);
  $l->Stroke($g->img);
  return $g;

}
//superficie sellada interna
function Sellada_I($g,$y,$x)
{
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="S";
  $t = new Text($txt,$y+31,$x+66);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
}

function COT_SI($g,$y,$x)
{
  //arco superior interior
  $g->img->SetColor('black');
  $g->img->filledArc($y+35,$x+50,35,35,-132,-48);
  //contorno circulo pe
  $g->img->SetColor('white');
  $g->img->filledCircle($y+35,$x+50,6);
  return $g;
}

function Resina_SI($g,$y,$x)
{
  //PIEZA 7 - SOMBREADO DE LA ZONA 6
  //arco superior

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
//
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
//
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+31,36+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;

}

function Amalgama_SI($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+38);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}


function Restauracion_Desadaptada_SI($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//     $g->img->SetColor('black');
//     //linea superior izq
//     $g->img->Line($y+20,28+$x,$y+33,45+$x);
//     //linea superior der
//     $g->img->Line($y+53,29+$x,$y+38,45+$x);
//     //linea inferior izq
//     $g->img->Line($y+33,53+$x,$y+20,73+$x);
//     //linea inferior der
//     $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="x";
  $l = new Text($txt,$y+30,$x+35);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Sellada_SI($g,$y,$x)
{
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+32,$x+35);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
}

function Resina_LD($g,$y,$x)
{
/*  $g->img->SetColor('white');
  $g->img->Arc($y+35,50+$x,55,55,132,-140);
  $g->img->filledArc($y+35,50+$x,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle($y+35,50+$x,16);
  $g->img->filledCircle($y+35,50+$x,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc($y+35,50+$x,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc($y+35,50+$x,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc($y+35,50+$x,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc($y+35,50+$x,55,55,48,130);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle($y+35,50+$x,16);
  $g->img->SetColor('black');
  $g->img->Circle($y+35,50+$x,6);

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line($y+20,28+$x,$y+33,45+$x);
  //linea superior der
  $g->img->Line($y+53,29+$x,$y+38,45+$x);
  //linea inferior izq
  $g->img->Line($y+33,53+$x,$y+20,73+$x);
  //linea inferior der
  $g->img->Line($y+38,50+$x,$y+53,72+$x);*/
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+41,44+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Amalgama_LD($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+41,$x+46);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Restauracion_Desadaptada_LD($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//     $g->img->SetColor('black');
//     //linea superior izq
//     $g->img->Line($y+20,28+$x,$y+33,45+$x);
//     //linea superior der
//     $g->img->Line($y+53,29+$x,$y+38,45+$x);
//     //linea inferior izq
//     $g->img->Line($y+33,53+$x,$y+20,73+$x);
//     //linea inferior der
//     $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="x";
  $l = new Text($txt,$y+41,$x+44);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Sellada_LD($g,$y,$x)
{
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+42,$x+45);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
}

function  COT_II($g,$y,$x)
{
  //arco inferior inferior
  $g->img->SetColor('black');
  $g->img->filledArc($y+35,$x+50,35,35,48,130);
  //contorno de los circulos
  $g->img->SetColor('white');
  $g->img->filledCircle($y+35,$x+50,6);
  return $g;
}

function Resina_II($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
//
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+31,55+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}


function Amalgama_II($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+32,$x+57);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;

}

function Restauracion_Desadaptada_II($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//     $g->img->SetColor('black');
//     //linea superior izq
//     $g->img->Line($y+20,28+$x,$y+33,45+$x);
//     //linea superior der
//     $g->img->Line($y+53,29+$x,$y+38,45+$x);
//     //linea inferior izq
//     $g->img->Line($y+33,53+$x,$y+20,73+$x);
//     //linea inferior der
//     $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="x";
  $l = new Text($txt,$y+31,$x+56);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Sellada_II($g,$y,$x)
{
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+32,$x+67);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
}

//CARIES OBTTURACIÓN TEMPORAL LATERAL IZQUIERDA
/************************************************/
function COT_LI($g,$y,$x)
{
  $g->img->SetColor('black');
  //arco lateral izquierdo
  $g->img->filledArc($y+35,$x+50,35,35,120,-135);
  //contorno de los circulos
  $g->img->SetColor('white');
  $g->img->filledCircle($y+35,$x+50,6);
  return $g;
}
//resina lateral izquierda
function Resina_LI($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
//
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
//
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+21,47+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Amalgama_LI($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+21,$x+47);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Restauracion_Desadaptada_LI($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="x";
  $l = new Text($txt,$y+20,$x+45);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

//sellada lateral izquierda
function Sellada_LI($g,$y,$x)
{
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+21,$x+45);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
}

function COT_C($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,400,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,400,6);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-47);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  $g->img->SetColor('black');
  $g->img->filledCircle($y+35,50+$x,6);
  return $g;
}

function Resina_C($g,$y,$x)
{
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+30,46+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
  }

  function Amalgama_C($g,$y,$x)
  {
//     $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //contorno de los circulos
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);

  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+48);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
  }

  function Restauracion_Desadaptada_C($g,$y,$x)
  {
//   $g->img->SetColor('white');
//   $g->img->Arc($y+35,50+$x,55,55,132,-140);
//   $g->img->filledArc($y+35,50+$x,55,55,120,-135);
// 
//   // circulo (x,y,diameter)
//   $g->img->SetColor('white');
//   $g->img->filledCircle($y+35,50+$x,16);
//   $g->img->filledCircle($y+35,50+$x,6);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //se desplaza 90 pos en el eje y
//   //lineas negras
// 
//   //arco superior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
// 
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,312,-310);
//   //filledArc(Y,Y,A,B,C,-D);
//   //C si se +C diminuye uno de los lados
//   //D si se -D diminuye uno de los lados
//   //se desplaza 90 pos en el eje y
// 
//   //rellenar arcos
//   //arco superior
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->filledArc($y+35,400,55,55,48,130);
//   //lineas negras
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
// 
//   //arco superior - contornos
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-132,-48);
//   //arco inferior
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,48,130);
//   //contorno de los circulos
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->Circle($y+35,50+$x,6);
  //simbolo multiplicación(x)
  $txt="x";
  $l = new Text($txt,$y+30,$x+45);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
  }

  function Sellada_C($g,$y,$x)
  {
//   $g->img->SetColor('black');
//   $g->img->Arc($y+35,50+$x,55,55,-135,-45);
//   //arco inferior
//   $g->img->Arc($y+35,50+$x,55,55,48,135);
//   //arco lateral derecho
//   //Arc(x,y,a,b,c,d)
//   //x,y: punto sobre el que se circunscribe la curva
//   //a,b: curvatura de la curva.
//   //c,d: alargamiento o encogimiento de los extremos
//   $g->img->SetColor('white');
//   $g->img->filledArc($y+35,50+$x,55,55,132,-140);
//   $g->img->Arc($y+34,50+$x,55,55,132,-140);
//   // circulo (x,y,diameter)
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,16);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,15);
//   $g->img->SetColor('black');
//   $g->img->Circle($y+35,50+$x,6);
//   $g->img->SetColor('white');
//   $g->img->FilledCircle($y+35,50+$x,5);
// 
//   //lineas de la figura
//   $g->img->SetColor('black');
//   //linea superior izq
//   $g->img->Line($y+20,28+$x,$y+33,45+$x);
//   //linea superior der
//   $g->img->Line($y+53,29+$x,$y+38,45+$x);
//   //linea inferior izq
//   $g->img->Line($y+33,53+$x,$y+20,73+$x);
//   //linea inferior der
//   $g->img->Line($y+38,50+$x,$y+53,72+$x);
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+32,$x+46);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);
  return $g;
  }
  //incrustacion superior
  function Incrustacion_S($g,$y,$x)
  {
    $txt="I";
    $t = new Text($txt,$y+31,$x+24);
    $t->SetFont(FF_LUXIS,FS_BOLD,9);
    $t->Stroke($g->img);
    return $g;
  }

  //incrustacion INFERIOR
  function Incrustacion_I($g,$y,$x)
  { echo $y.'--'.$x;
    $txt="I";
    $l = new Text($txt,$y+31,66+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }

  //incrustacion SUPERIOR INTERIOR
  function Incrustacion_SI($g,$y,$x)
  {
    $txt="I";
    $l = new Text($txt,$y+31,36+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }

  //incrustacion LATERAL DERECHA
  function Incrustacion_LD($g,$y,$x)
  {
    $txt="I";
    $l = new Text($txt,$y+41,44+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }
  //incrustacion INFERIOR INTERIOR
  function Incrustacion_II($g,$y,$x)
  {
    $txt="I";
    $l = new Text($txt,$y+33,56+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,8);
    $l->Stroke($g->img);
    return $g;
  }

  //incrustacion LATERAL IZQUIERDA
  function Incrustacion_LI($g,$y,$x)
  {
    $txt="I";
    $l = new Text($txt,$y+21,47+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }

    //incrustacion CENTRAL
  function Incrustacion_C($g,$y,$x)
  {
    $txt="I";
    $l = new Text($txt,$y+30,46+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }

  //AUSENTE POR EXODONCIA
  function Ausente_Exodoncia($g,$y,$x)
  {
    $txt="|";
    $l = new Text($txt,$y+32,$x+23);
    $l->SetFont(FF_LUXIS,FS_NORMAL,45);
    $l->Stroke($g->img);
    return $g;
  }

  //DIENTE SIN ERUPCIONAR
  function Diente_Sin_Erupcionar($g,$y,$x)
  {
    $txt="-";
    $l = new Text($txt,$y+20,$x+46);
    $l->SetFont(FF_LUXIS,FS_NORMAL,48);
    $l->Stroke($g->img);
    return $g;
  }

  //EXODONCIA INDICADA
 function Exodoncia_Indicada($g,$y,$x)
 {
  $txt="\\";
  $l = new Text($txt,$y+18,$x+26);
  $l->SetFont(FF_LUXIS,FS_NORMAL,43);
  $l->Stroke($g->img);
  $txt="/";
  $l = new Text($txt,$y+18,26+$x);
  $l->SetFont(FF_LUXIS,FS_NORMAL,43);
  $l->Stroke($g->img);
  return $g;
 }

 //DIENTE SEMI INCLUIDO
 function Diente_Semi_Incluido($g,$y,$x)
 {
  $txt="\\";
  $l = new Text($txt,$y+18,$x+26);
  $l->SetFont(FF_LUXIS,FS_NORMAL,43);
  $l->Stroke($g->img);
  $txt="/";
  $l = new Text($txt,$y+18,26+$x);
  $l->SetFont(FF_LUXIS,FS_NORMAL,43);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,$y+20,$x+46);
  $l->SetFont(FF_LUXIS,FS_NORMAL,48);
  $l->Stroke($g->img);
  return $g;
 }

  //PROTESIS REMOVIBLE
  function Protesis_Removible($g,$y,$x)
  {
    $txt="-";
    $l = new Text($txt,$y+13,$x+44);
    $l->SetFont(FF_LUXIS,FS_NORMAL,70);
    $l->Stroke($g->img);
    return $g;
   }

  //PROTESIS FIJA
  function Protesis_Fija($g,$y,$x)
  {
   $txt="-";
   $l = new Text($txt,$y+19,$x+41);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,$y+19,$x+54);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
    $txt="-";
   $l = new Text($txt,$y+35,$x+41);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,$y+35,$x+54);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
   return $g;
  }
  //ENDODONCIA POR REALIZAR
  function Endodoncia_Por_Realizar($g,$y,$x)
  {
    //lados de la figura
    //lado izquierdo
    $g->img->Line($y+20,$x+28,$y+36,5+$x);
    //base del traigulo
    $g->img->Line($y+20,28+$x,$y+53,28+$x);
    //lado derecho
    $g->img->Line($y+36,5+$x,$y+53,28+$x);
    return $g;
  }

   //NUCLEO O POSTE
  function Nucleo_Poste($g,$y,$x)
  {
    //lados de la figura
    //lado izquierdo
    $g->img->Line($y+20,72+$x,$y+35,25+$x);
    //base del traigulo
    $g->img->Line($y+20,72+$x,$y+51,72+$x);
    //lado derecho
    $g->img->Line($y+35,25+$x,$y+51,72+$x);
    return $g;
  }

  //Endodoncia Realizada
function Endodoncia_Realizada($g,$y,$x)
{
  //fondo del triangulo
  $g->img->SetColor('black');
  $g->img->filledcircle($y+35,22+$x,17);
  $g->img->SetColor('white');
  //lado derecho
  $g->img->filledArc($y+20,28+$x,60,60,250,-60);
  //lado ziquierdo
  $g->img->filledArc($y+53,30+$x,60,60,235,-60);
  return $g;
}
    //superficie sellada
  function Superficie_Sellada($g,$y,$x)
 {
  $txt="S";
  $t = new Text($txt,$y+20,35+$x);
  $t->SetFont(FF_LUXIS,FS_BOLD,30);
  $t->Stroke($g->img);
  return $g;
 }

 //INCRUSTACIÖN
 function Incrustacion($g,$y,$x)
 {
  $txt="I";
  $t = new Text($txt,$y+22,30+$x);
  $t->SetFont(FF_LUXIS,FS_BOLD,40);
  $t->Stroke($g->img);
  return $g;
 }

 //DIENTE INCLUIDO
 function Diente_Incluido($g,$y,$x)
 {
  $txt="\\";
  $l = new Text($txt,$y+18,$x+26);
  $l->SetFont(FF_LUXIS,FS_NORMAL,43);
  $l->Stroke($g->img);
  $txt="/";
  $l = new Text($txt,$y+18,26+$x);
  $l->SetFont(FF_LUXIS,FS_NORMAL,43);
  $l->Stroke($g->img);

  $txt="-";
  $l = new Text($txt,$y+20,$x+41);
  $l->SetFont(FF_LUXIS,FS_NORMAL,48);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,$y+20,$x+54);
  $l->SetFont(FF_LUXIS,FS_NORMAL,48);
  $l->Stroke($g->img);
  return $g;
 }

 //CORONA
 function Corona($g,$y,$x,$problema)
 {
  //CORONA POR CAMBIAR
  if ($problema=='10')
  {
   $g->img->Circle($y+35,50+$x,28);
   $g->img->Circle($y+35,50+$x,29);
   $g->img->Circle($y+35,50+$x,30);
   $txt="|";
   $t = new Text($txt,$y+42,12+$x);
   $t->SetFont(FF_LUXIS,FS_BOLD,10);
  }else
  if ($problema=='9')//CORONA BUENA
  {echo hola;
   $g->img->Circle($y+35,50+$x,28);
   $g->img->Circle($y+35,50+$x,29);
   $g->img->Circle($y+35,50+$x,30);
   $txt="";
   $t = new Text($txt,$y+42,12+$x);
   $t->SetFont(FF_LUXIS,FS_BOLD,10);
  }
   $t->Stroke($g->img);
   return $g;
 }

 //MUESTRA CADA UNA DE LA COMBINACIONES POSIBLES PARA CADA DIENTE O PIEZA
 function DrawImage($g,$y,$x,$y1,$x1,$val,$tooth)
 {
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='1')
    {
      if ($val[$x1][$y1+2]=='14')
      {
        $g=COT_S($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_S($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_S($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_S($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_S($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_S($g,$y,$x);
        //$y+=50;
      }
    }

    //region dos
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='2')
    {
      if ($val[$x1][$y1+2]=='14')
      {
        $g=COT_I($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_I($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_I($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_I($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_I($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_I($g,$y,$x);
        //$y+=50;
      }
    }

    //region tres
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='3')
    {
      if ($val[$x1][$y1+2]=='14')
      {
      //caries superior interior
        $g=COT_SI($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_SI($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_SI($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_SI($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_SI($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_SI($g,$y,$x);
        //$y+=50;
      }
    }
    //SUPERFICIE cuatro
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='4')
    {
      //función que dibuja caries obturación temporal
      //lateral derecha
      if ($val[$x1][$y1+2]=='14')
      {
        $g=COT_LD($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_LD($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_LD($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_LD($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_LD($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_LD($g,$y,$x);
       // $y+=50;
      }
    }
      //region 5
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='5')
    {
      if ($val[$x1][$y1+2]=='14')
      {
        $g=COT_II($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      //resina inferior interna
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_II($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_II($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_II($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_II($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_II($g,$y,$x);
        //$y+=50;
      }
    }
      //region 6
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='6')
    {
      if ($val[$x1][$y1+2]=='14')
      {
        $g=COT_LI($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_LI($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      //amalgama lateral izquierda
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_LI($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_LI($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_LI($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_LI($g,$y,$x);
        //$y+=50;
      }
    }
      //region 7
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]=='7')
    {
      if ($val[$x1][$y1+2]=='14')//1
      {
        $g=COT_C($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='16')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_C($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='15')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_C($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='20')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_C($g,$y,$x);
       // $y+=50;
      }
      if ($val[$x1][$y1+2]=='18')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_C($g,$y,$x);
        //$y+=50;
      }
      if ($val[$x1][$y1+2]=='19')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_C($g,$y,$x);
       // $y+=50;
      }
    }
    //funciones que dibujan los simbolos que
    //involucran toda la pieza o diente

    if ($val[$x1][$y1+1]=='11')
    {
      if ($val[$x1][$y1+2]=='8')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Ausente_Exodoncia($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='2')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Diente_Sin_Erupcionar($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='3')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Exodoncia_Indicada($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='5')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Diente_Semi_Incluido($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='12')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Protesis_Removible($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='11')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Protesis_fija($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='6')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Endodoncia_Por_Realizar($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='13')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Nucleo_Poste($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='7')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Endodoncia_Realizada($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='17')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Superficie_Sellada($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='16')//falta aclarar el caso
        {
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='9' || $val[$x1][$y1+2]=='10')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Corona($g,$y,$x,$val[$x1][$y1+2]);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='4')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Diente_Incluido($g,$y,$x);
        $y+=50;
        }
    }//fin if de la superficie 11
  return $g;
 }
/************************************************/
/************************************************/
/************************************************/
/************************************************/
/************************************************/
/************************************************/
  //CONVENCIONES-SIMBOLOGIA//
/************************************************/
  function Simbolos($g)
  {
  //texto simbolos
  $txt="Caries\nobturación\ntemporal";
  $l = new Text($txt,8,435);
  $l->SetFont(FF_LUXIS,FS_NORMAL,7);
  $l->Stroke($g->img);
  //texto simbolos
  $txt="Corona";
  $l = new Text($txt,115,435);
  $l->SetFont(FF_LUXIS,FS_NORMAL,7);
  $l->Stroke($g->img);

  //PIEZA 2 SOMBREADO DE LA ZONA 1
  //arco siperior
  $g->img->SetColor('black');
  $g->img->FilledArc(35,400,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(35,400,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(35,400,55,55,132,-140);
  $g->img->Arc(34,400,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(35,400,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(35,400,15);
  $g->img->SetColor('black');
  $g->img->Circle(35,400,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(35,400,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(18,382,33,395);
   //linea superior der
   $g->img->Line(53,379,38,395);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(33,405,18,423);
   //linea inferior der
   $g->img->Line(38,405,53,422);

/********************************************/
  //PIEZA 3 SOMBREADO DE LA ZONA 2
  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(85,400,55,55,-132,-47);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->FilledArc(85,400,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(85,400,55,55,132,-140);
  $g->img->Arc(84,400,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(85,400,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(85,400,15);
  $g->img->SetColor('black');
  $g->img->Circle(85,400,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(85,400,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(68,380,83,395);
   //linea superior der
   $g->img->Line(103,379,88,395);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(83,405,68,423);
   //linea inferior der
   $g->img->Line(88,405,103,422);

/********************************************/
  //PIEZA 4 SOMBREADO DE LA ZONA 3
  //arco superior

  //corona
  $g->img->SetColor('black');
  $g->img->FilledCircle(135,400,30);
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(135,400,55,55,120,-135);
  $g->img->Arc(134,400,55,55,132,-140);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(135,400,16);
  $g->img->SetColor('white');
  $g->img->filledCircle(135,400,6);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(135,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->filledArc(135,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  $g->img->SetColor('white');
  //arco superior
  $g->img->Arc(135,400,55,55,-132,-47);
  $g->img->filledArc(135,400,55,55,-132,-47);
  //arco inferior
  $g->img->Arc(135,400,55,55,48,135);
  $g->img->filledArc(135,400,55,55,48,135);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(135,400,16);
  $g->img->SetColor('black');
  $g->img->Circle(135,400,6);
  $g->img->SetColor('black');

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(118,380,133,395);
  //linea superior der
  $g->img->Line(153,379,138,395);
  //linea inferior izq
  $g->img->Line(133,405,118,423);
  //linea inferior der
  $g->img->Line(138,405,153,422);

/********************************************/
  //PIEZA 5 SOMBREADO DE LA ZONA 4
  //arco superior

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(204,400,55,55,132,-140);
  $g->img->filledArc(205,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(205,400,16);
  $g->img->filledCircle(205,400,6);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(205,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(205,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(205,400,55,55,-132,-47);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(205,400,55,55,48,130);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(205,400,16);
  $g->img->SetColor('black');
  $g->img->Circle(205,400,6);

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(188,380,203,395);
  //linea superior der
  $g->img->Line(223,379,208,395);
  //linea inferior izq
  $g->img->Line(203,405,188,423);
  //linea inferior der
  $g->img->Line(208,405,223,422);

  $g->img->SetColor('black');
  $g->img->filledCircle(205,400,6);

/********************************************/

  //PIEZA 6 - SOMBREADO DE LA ZONA 5
  //arco superior
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(254,400,55,55,132,-140);
  $g->img->filledArc(255,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(255,400,16);
  $g->img->filledCircle(255,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(255,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(255,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(255,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(255,400,55,55,48,130);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(255,400,16);
  $g->img->SetColor('black');
  $g->img->Circle(255,400,6);

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(238,380,253,395);
  //linea superior der
  $g->img->Line(273,379,258,395);
  //linea inferior izq
  $g->img->Line(253,405,238,423);
  //linea inferior der
  $g->img->Line(258,405,273,422);
  //linea del simbolo menos(-)
  //$g->img->SetColor('red');
    //texto simbolos
  $txt="Superficie\nen amalgama";
  $l = new Text($txt,225,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,262,399);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  //$g->img->Line(263,400,269,400);
/***************************************/
  //PIEZA 7 - SOMBREADO DE LA ZONA 6
  //arco superior

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(304,400,55,55,132,-140);
  $g->img->filledArc(305,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(305,400,16);
  $g->img->filledCircle(305,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(305,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(305,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(305,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(305,400,55,55,48,130);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(305,400,16);
  $g->img->SetColor('black');
  $g->img->Circle(305,400,6);

  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(288,380,303,395);
  //linea superior der
  $g->img->Line(323,379,308,395);
  //linea inferior izq
  $g->img->Line(303,405,288,423);
  //linea inferior der
  $g->img->Line(308,405,323,422);
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="Superficie\nen resina\nionómero\ncerómero";
  $l = new Text($txt,285,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="+";
  $l = new Text($txt,311,397);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  //horizontal
//   $g->img->SetColor('red');
//   $g->img->Line(312,400,318,400);
//   //vertical
//   $g->img->Line(315,397,315,403);

/***************************************/
  //PIEZA 8 - SOMBREADO DE LA ZONA 7
  //arco superior

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(355,400,16);
  $g->img->filledCircle(355,400,6);

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(355,400,55,55,-132,-48);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->FilledCircle(355,400,16);
  $g->img->SetColor('white');
  $g->img->filledCircle(355,400,6);

  //arco inferior
  $g->img->SetColor('white');
  $g->img->filledArc(355,400,55,55,48,130);
  $g->img->SetColor('black');
  $g->img->Arc(355,400,55,55,48,130);

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(355,400,55,55,120,-135);
  $g->img->Arc(354,400,55,55,132,-140);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(355,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(355,400,55,55,312,-310);
  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(355,400,16);
  $g->img->Circle(355,400,6);

  //lineas negras
  //se desplaza 90 pos en el eje y
  //linea superior izq
  $g->img->SetColor('black');
  $g->img->Line(338,380,353,395);
  //linea superior der
  $g->img->Line(373,379,358,395);
  //linea inferior izq
  $g->img->Line(353,405,338,423);
  //linea inferior der
  $g->img->Line(358,405,373,422);

/***************************************/
  //PIEZA 9 - SOMBREADO DE LA ZONA 8
  //arco superior

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(405,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

    // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->filledCircle(405,400,16);
  $g->img->SetColor('white');
  $g->img->filledCircle(405,400,6);
  $g->img->SetColor('black');
  $g->img->Circle(405,400,16);
  $g->img->Circle(405,400,6);

  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(405,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('white');
  $g->img->filledArc(405,400,55,55,48,130);

    //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(404,400,55,55,132,-140);
  $g->img->filledArc(405,400,55,55,120,-135);

  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(388,380,403,395);
  //linea superior der
  $g->img->Line(423,379,408,395);
  //linea inferior izq
  $g->img->Line(403,405,388,423);
  //linea inferior der
  $g->img->Line(408,405,423,422);

    //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(405,400,16);
  $g->img->SetColor('black');
  $g->img->Circle(405,400,6);

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(405,400,55,55,-132,-48);
  //arco inferior
  $g->img->Arc(405,400,55,55,48,130);


/***************************************/

  //PIEZA 10 - SOMBREADO DE LA ZONA 9
  //arco inferior
  $g->img->SetColor('white');
  $g->img->Arc(455,400,55,55,48,130);

  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->filledCircle(455,400,16);
  $g->img->SetColor('white');
  $g->img->filledCircle(455,400,6);

  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(455,400,16);
  $g->img->SetColor('black');
  $g->img->Circle(455,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->Arc(455,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(455,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y

  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(455,400,55,55,-132,-48);
  $g->img->SetColor('black');
  $g->img->Arc(455,400,55,55,-132,-48);
  //arco inferior - contorno
  $g->img->SetColor('black');
  $g->img->Arc(455,400,55,55,48,130);

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(454,400,55,55,132,-140);
  $g->img->filledArc(455,400,55,55,120,-135);
  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(455,400,16);
  $g->img->Circle(455,400,6);
//lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(438,380,453,395);
  //linea superior der
  $g->img->Line(473,379,458,395);
  //linea inferior izq
  $g->img->Line(453,405,438,423);
  //linea inferior der
  $g->img->Line(458,405,473,422);

/***************************************/
  //PIEZA 11 - SOMBREADO DE LA ZONA 10

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(504,400,55,55,132,-140);
  $g->img->filledArc(505,400,55,55,120,-135);

   // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->filledCircle(505,400,16);
  $g->img->SetColor('white');
  $g->img->filledCircle(505,400,6);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->filledArc(505,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(505,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(505,400,55,55,48,130);
  //contorno de los arcos
  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(505,400,55,55,-132,-48);
  //arco inferior
  $g->img->Arc(505,400,55,55,48,130);
  //contorno de los circulos
  $g->img->SetColor('black');
  $g->img->Circle(505,400,16);
  $g->img->Circle(505,400,6);
  //linea negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(488,380,503,395);
  //linea superior der
  $g->img->Line(523,379,508,395);
  //linea inferior izq
  $g->img->Line(503,405,488,423);
  //linea inferior der
  $g->img->Line(508,405,523,422);
  //contorno de los circulos
  $g->img->Circle(505,400,16);
  $g->img->Circle(505,400,6);

/***************************************/
  //PIEZA 12 - SOMBREADO DE LA ZONA 11
  //simbolo X
  //Restauración desadaptada
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(554,400,55,55,132,-140);
  $g->img->filledArc(555,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(555,400,16);
  $g->img->filledCircle(555,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(555,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(555,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(555,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(555,400,55,55,48,130);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(555,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(555,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(555,400,55,55,48,130);
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(538,380,553,395);
  //linea superior der
  $g->img->Line(573,379,558,395);
  //linea inferior izq
  $g->img->Line(553,405,538,423);
  //linea inferior der
  $g->img->Line(558,405,573,422);

  //arco superior - contornos
  $g->img->SetColor('black');
  $g->img->Arc(555,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(555,400,55,55,48,130);
  //contorno de los circulos
  $g->img->Circle(555,400,16);
  $g->img->Circle(555,400,6);
  //lineas del simbolo multiplicación(x)
  //texto simbolos
  $txt="Restauración\nDesadaptada";
  $l = new Text($txt,520,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);

  $txt="X";
  $l = new Text($txt,560,393);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

//   $g->img->SetColor('red');
//   $g->img->Line(562,403,568,398);
//   $g->img->Line(563,397,567,403);


/***************************************/
  //PIEZA 13 - SOMBREADO DE LA ZONA 11
  //ausente por exodoncia
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(604,400,55,55,132,-140);
  $g->img->filledArc(605,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(605,400,16);
  $g->img->filledCircle(605,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(605,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(605,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(605,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(605,400,55,55,48,130);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(605,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(605,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(605,400,55,55,48,130);
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(588,380,603,395);
  //linea superior der
  $g->img->Line(623,379,608,395);
  //linea inferior izq
  $g->img->Line(603,405,588,423);
  //linea inferior der
  $g->img->Line(608,405,623,422);

  //arco superior - contornos
  $g->img->SetColor('black');
  $g->img->Arc(605,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(605,400,55,55,48,130);
  //contorno de los circulos
  $g->img->Circle(605,400,16);
  $g->img->Circle(605,400,6);
  //linea que simboliza la ausencia por exodoncia
//  $g->img->Line(605,372,605,428);
  //texto simbolos
  $txt="Ausente\npor\nexodoncia";
  $l = new Text($txt,583,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);

  $txt="|";
  $l = new Text($txt,602,372);
  $l->SetFont(FF_LUXIS,FS_NORMAL,45);
  $l->Stroke($g->img);

/***************************************/
  //PIEZA 14 - SOMBREADO DE LA ZONA 11

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(654,400,55,55,132,-140);
  $g->img->filledArc(655,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(655,400,16);
  $g->img->filledCircle(655,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(655,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(655,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(655,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(655,400,55,55,48,130);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(655,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(655,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(655,400,55,55,48,130);
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(638,380,653,395);
  //linea superior der
  $g->img->Line(673,379,658,395);
  //linea inferior izq
  $g->img->Line(653,405,638,423);
  //linea inferior der
  $g->img->Line(658,405,673,422);

  //arco superior - contornos
  $g->img->SetColor('black');
  $g->img->Arc(655,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(655,400,55,55,48,130);
  //contorno de los circulos
  $g->img->Circle(655,400,16);
  $g->img->Circle(655,400,6);
  //linea que simboliza diente sin erupcionar
  //$g->img->Line(640,400,670,400);
  $txt="Diente\nsin\nerupcionar";
  $l = new Text($txt,635,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,640,395);
  $l->SetFont(FF_LUXIS,FS_NORMAL,48);
  $l->Stroke($g->img);


/***************************************/
  //PIEZA 15 - SOMBREADO DE LA ZONA 11

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(704,400,55,55,132,-140);
  $g->img->filledArc(705,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(705,400,16);
  $g->img->filledCircle(705,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(705,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(705,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(705,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(705,400,55,55,48,130);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(705,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(705,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(705,400,55,55,48,130);
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(688,380,703,395);
  //linea superior der
  $g->img->Line(723,379,708,395);
  //linea inferior izq
  $g->img->Line(703,405,688,423);
  //linea inferior der
  $g->img->Line(708,405,723,422);

  //arco superior - contornos
  $g->img->SetColor('black');
  $g->img->Arc(705,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(705,400,55,55,48,130);
  //contorno de los circulos
  $g->img->Circle(705,400,16);
  $g->img->Circle(705,400,6);
  //linea que simboliza exodoncia indicada
//   $g->img->Line(688,380,723,422);
//   $g->img->Line(723,379,688,423);
  $txt="Exodoncia\nindicada";
  $l = new Text($txt,690,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="X";
  $l = new Text($txt,688,380);
  $l->SetFont(FF_LUXIS,FS_NORMAL,45);
  $l->Stroke($g->img);

/***************************************/
  //PIEZA 16 - SOMBREADO DE LA ZONA 11

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(754,400,55,55,132,-140);
  $g->img->filledArc(755,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(755,400,16);
  $g->img->filledCircle(755,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(755,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(755,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(755,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(755,400,55,55,48,130);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(755,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(755,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(755,400,55,55,48,130);
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(738,380,753,395);
  //linea superior der
  $g->img->Line(773,379,758,395);
  //linea inferior izq
  $g->img->Line(753,405,738,423);
  //linea inferior der
  $g->img->Line(758,405,773,422);

  //arco superior - contornos
  $g->img->SetColor('black');
  $g->img->Arc(755,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(755,400,55,55,48,130);
  //contorno de los circulos
  $g->img->Circle(755,400,16);
  $g->img->Circle(755,400,6);
  //linea que simboliza diente incluido o semi-incluido
/*  $g->img->Line(738,380,773,422);
  $g->img->Line(773,379,738,423);
  //linea horizontal
  $g->img->Line(733,400,820,400);*/
  $txt="Diente\nsemi\nincluido";
  $l = new Text($txt,742,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="X";
  $l = new Text($txt,738,380);
  $l->SetFont(FF_LUXIS,FS_NORMAL,45);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,740,397);
  $l->SetFont(FF_LUXIS,FS_NORMAL,48);
  $l->Stroke($g->img);

/***************************************/
  //PIEZA 17 - SOMBREADO DE LA ZONA 11

  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(804,400,55,55,132,-140);
  $g->img->filledArc(805,400,55,55,120,-135);

  // circulo (x,y,diameter)
  $g->img->SetColor('white');
  $g->img->filledCircle(805,400,16);
  $g->img->filledCircle(805,400,6);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('black');
  $g->img->Arc(805,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->SetColor('white');
  $g->img->filledArc(805,400,55,55,312,-310);
  //se desplaza 90 pos en el eje y
  //lineas negras

  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc(805,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(805,400,55,55,48,130);

  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(805,400,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  //se desplaza 90 pos en el eje y

  //rellenar arcos
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(805,400,55,55,-132,-48);
  //arco inferior
  $g->img->filledArc(805,400,55,55,48,130);
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(788,380,803,395);
  //linea superior der
  $g->img->Line(823,379,808,395);
  //linea inferior izq
  $g->img->Line(803,405,788,423);
  //linea inferior der
  $g->img->Line(808,405,823,422);

  //arco superior - contornos
  $g->img->SetColor('black');
  $g->img->Arc(805,400,55,55,-132,-48);
  //arco inferior
  $g->img->SetColor('black');
  $g->img->Arc(805,400,55,55,48,130);
  //contorno de los circulos
  $g->img->Circle(805,400,16);
  $g->img->Circle(805,400,6);
  //linea que simboliza protesis removible
  //$g->img->Line(785,400,825,400);
  $txt="Prótesis\nremovible";
  $l = new Text($txt,788,430);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,780,395);
  $l->SetFont(FF_LUXIS,FS_NORMAL,80);
  $l->Stroke($g->img);

/***************************************/
  //PIEZA 18 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(35,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(35,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(35,550,55,55,132,-140);
  $g->img->Arc(34,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(35,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(35,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(35,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(35,500,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(18,532,33,545);
   //linea superior der
   $g->img->Line(53,529,38,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(33,555,18,573);
   //linea inferior der
   $g->img->Line(38,555,53,572);
   //linea que simboliza protesis fija
//    $g->img->Line(15,547,54,547);
//    $g->img->Line(15,553,54,553);
   $txt="Prótesis\nfija";
   $l = new Text($txt,5,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,20,540);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,20,553);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);

/********************************************/
  //PIEZA 18 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(85,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(85,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(85,550,55,55,132,-140);
  $g->img->Arc(84,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(85,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(85,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(85,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(85,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(68,532,83,545);
   //linea superior der
   $g->img->Line(103,529,88,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(83,555,68,573);
   //linea inferior der
   $g->img->Line(88,555,103,572);
   //linea que simboliza exodoncia por realizar
   $txt="Endodoncia\npor\nrealizar";
   $l = new Text($txt,55,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $g->img->Line(65,532,100,532);
   $g->img->Line(65,532,82,495);
   $g->img->Line(82,495,100,532);

/********************************************/
   //PIEZA 18 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(135,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(135,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(135,550,55,55,132,-140);
  $g->img->Arc(134,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(135,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(135,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(135,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(135,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(118,532,133,545);
   //linea superior der
   $g->img->Line(153,529,138,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(133,555,118,573);
   //linea inferior der
   $g->img->Line(138,555,153,572);
   //linea que simboliza nucleo o poste
   $txt="Núcleo\no\nposte";
   $l = new Text($txt,115,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   //$g->img->Line(65,532,100,532);
   //$g->img->Line(65,532,82,495);
   //$g->img->Line(82,495,100,532);
   $g->img->Line(118,572,153,572);
   $g->img->Line(118,572,135,521);
   $g->img->Line(135,521,153,572);

/********************************************/
   //PIEZA 19 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(185,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(185,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(185,550,55,55,132,-140);
  $g->img->Arc(184,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(185,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(185,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(185,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(185,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(168,532,183,545);
   //linea superior der
   $g->img->Line(203,529,188,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(183,555,168,573);
   //linea inferior der
   $g->img->Line(188,555,203,572);
   //linea que simboliza endodoncia realizada
   $g->img->SetColor('white');
   $g->img->Line(165,532,200,532);
   $g->img->Line(165,532,182,480);
   $g->img->Line(182,480,200,532);
   $g->img->SetColor('black');
   $g->img->filledcircle(183,510,30);
   //lado#1 para el triangulo
   $g->img->SetColor('black');
   $g->img->Arc(165,532,112,112,200,-70);
   $g->img->SetColor('white');
   $g->img->filledArc(165,532,112,112,200,-60);
   //lado#2 para el triangulo
   $txt="Endodoncia\nrealizada";
   $l = new Text($txt,155,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $g->img->SetColor('black');
   $g->img->Arc(200,532,112,112,255,-60);
   $g->img->SetColor('white');
   $g->img->filledArc(200,532,112,112,239,-5);

/********************************************/
   //PIEZA 20 SOMBREADO DE LA ZONA 1
   //superficie sellada
  //arcos
  //arco siperior
  $g->img->SetColor('black');
  $g->img->Arc(235,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(235,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(235,550,55,55,132,-140);
  $g->img->Arc(234,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(235,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(235,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(235,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(235,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(218,532,233,545);
   //linea superior der
   $g->img->Line(253,529,238,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(233,555,218,573);
   //linea inferior der
   $g->img->Line(238,555,253,572);
   $txt="Superficie\npor\nsellar";
   $l = new Text($txt,210,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $txt="S";
   $t = new Text( $txt,232,524);
   $t->SetFont(FF_LUXIS,FS_BOLD,9);
   $t->Stroke($g->img);

/********************************************/
    //PIEZA 21 SOMBREADO DE LA ZONA 1
  //arcos SUPERFICIE SELLADA
  //arco siperior
  $g->img->Arc(285,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(285,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(285,550,55,55,132,-140);
  $g->img->Arc(284,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(285,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(285,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(285,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(285,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
  $g->img->SetColor('black');
  //linea superior izq
  $g->img->Line(268,532,283,545);
  //linea superior der
  $g->img->Line(303,529,288,545);
  $g->img->SetColor('black');
  //linea inferior izq
  $g->img->Line(283,555,268,573);
  //linea inferior der
  $g->img->Line(288,555,303,572);
  //seperficie sellada
  $txt="Superficie\nsellada";
  $l = new Text($txt,265,585);
  $l->SetFont(FF_LUXIS,FS_NORMAL,6);
  $l->Stroke($g->img);
  $txt="S";
  $t = new Text($txt,270,538);
  $t->SetFont(FF_LUXIS,FS_BOLD,26);
  $t->Stroke($g->img);

/********************************************/
  //PIEZA 22 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(335,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(335,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(335,550,55,55,132,-140);
  $g->img->Arc(334,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(335,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(335,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(335,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(335,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(318,532,333,545);
   //linea superior der
   $g->img->Line(353,529,338,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(333,555,318,573);
   //linea inferior der
   $g->img->Line(338,555,353,572);
   //SIMBOLO INCRUSTACIÓN
   $txt="Incrus-\ntación";
   $l = new Text($txt,320,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $txt="I";
   $t = new Text($txt,321,530);
   $t->SetFont(FF_LUXIS,FS_BOLD,40);
   $t->Stroke($g->img);

/********************************************/
    //PIEZA 23 SOMBREADO DE LA ZONA 1
  //corona
  $g->img->SetColor('black');
  $g->img->FilledCircle(410,550,30);

  //arcos
  //arco siperior
  $g->img->SetColor('white');
  $g->img->filledArc(410,550,55,55,-135,-45);
  $g->img->Arc(410,550,55,55,-135,-45);
  //arco inferior
  $g->img->filledArc(410,550,55,55,48,135);
  $g->img->Arc(410,550,55,55,48,135);
  //arco lateral izquierdo
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->filledArc(410,550,55,55,132,-138);
  $g->img->Arc(409,550,55,55,132,-140);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->Arc(410,550,55,55,312,-310);
  //filledArc(Y,Y,A,B,C,-D);
  //C si se +C diminuye uno de los lados
  //D si se -D diminuye uno de los lados
  $g->img->filledArc(410,550,55,55,312,-310);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(410,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(410,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(410,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(410,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(393,532,408,545);
   //linea superior der
   $g->img->Line(428,529,413,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(408,555,393,573);
   //linea inferior der
   $g->img->Line(413,555,428,572);
  //SOMBOLO CORONA POR CAMBIAR
   $txt="Corona\npor\ncambiar";
   $l = new Text($txt,390,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $txt="|";
   $t = new Text($txt,408,510);
   $t->SetFont(FF_LUXIS,FS_BOLD,10);
   $t->Stroke($g->img);
/********************************************/
    //PIEZA 24 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(485,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(485,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(485,550,55,55,132,-140);
  $g->img->Arc(484,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(485,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(485,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(485,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(485,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(468,532,483,545);
   //linea superior der
   $g->img->Line(503,529,488,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(483,555,468,573);
   //linea inferior der
   $g->img->Line(488,555,503,572);
   //
   $txt="Diente\nincluido";
   $l = new Text($txt,470,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,469,540);
   $l->SetFont(FF_LUXIS,FS_NORMAL,53);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,469,553);
   $l->SetFont(FF_LUXIS,FS_NORMAL,53);
   $l->Stroke($g->img);
   $txt="X";
   $l = new Text($txt,468,528);
   $l->SetFont(FF_LUXIS,FS_NORMAL,45);
   $l->Stroke($g->img);
/********************************************/
    //PIEZA 25 SOMBREADO DE LA ZONA 1
  //arcos
  //arco siperior
  $g->img->Arc(535,550,55,55,-135,-45);
  //arco inferior
  $g->img->Arc(535,550,55,55,48,135);
  //arco lateral derecho
  //Arc(x,y,a,b,c,d)
  //x,y: punto sobre el que se circunscribe la curva
  //a,b: curvatura de la curva.
  //c,d: alargamiento o encogimiento de los extremos
  $g->img->SetColor('white');
  $g->img->filledArc(535,550,55,55,132,-140);
  $g->img->Arc(534,550,55,55,132,-140);
  // circulo (x,y,diameter)
  $g->img->SetColor('black');
  $g->img->Circle(535,550,16);
  $g->img->SetColor('white');
  $g->img->FilledCircle(535,550,15);
  $g->img->SetColor('black');
  $g->img->Circle(535,550,6);
  $g->img->SetColor('white');
  $g->img->FilledCircle(535,550,5);

  //se desplaza 90 pos en el eje y
  //lineas negras
   $g->img->SetColor('black');
   //linea superior izq
   $g->img->Line(518,532,533,545);
   //linea superior der
   $g->img->Line(553,529,538,545);
   $g->img->SetColor('black');
   //linea inferior izq
   $g->img->Line(533,555,518,573);
   //linea inferior der
   $g->img->Line(538,555,553,572);
   $txt="Sano";
   $l = new Text($txt,525,585);
   $l->SetFont(FF_LUXIS,FS_NORMAL,6);
   $l->Stroke($g->img);
   return $g;
  }

  //ETIQUETAS
  function Etiquetas($g,$n)
  {
    $i=0;
    $y=0;
    $k=0;
    $label=9;
    $label1=5;
    for ($l=0;$l<sizeof($n);)
    {
      while ($i<$n[$l])
      {
        //ETIQUETAS
        if ($i<8 and $label<=9 and $n[$l]==16)
        {
         $label=$label-1;
         $txt=(string) $label;
         $t = new Text($txt,32+$y,$x+8);
        }
        else
        if ($label>=1 and $label<=8 and $i!=0)
        {
         $txt=(string) $label;
         $t = new Text($txt,32+$y,$x+8);
         $label=$label+1;
        }
        else
        if ($n[$l]==10 and $i<=4 and $label1!=0)
        {
          $txt=(string) $label1;
          $t = new Text($txt,32+$y,$x+8);
          $label1=$label1-1;
        }
        else
        if ($n[$l]==10 and $i>=5 and $label1>=0)
        {
         $label1=$label1+1;
         $txt=(string) $label1;
         $t = new Text($txt,32+$y,$x+8);
        }
        $t->SetFont(FF_LUXIS,FS_NORMAL,10);
        $t->Stroke($g->img);
        //fin etiquetas
        $i++;
        $y+=50;
       }
      $l++;
      if ($n[$l]==10 && $k==0)
       {
         $y-=650;
         $k++;
       }
       else
         if ($n[$l]==10 && $k==1)
           $y-=500;
         else
           $y=0;
      $x+=95;
      $i=0;
    }
  }

  function arcos($g)
  {
  //arco superior
  $g->img->SetColor('white');
  $g->img->filledArc(35,150,35,35,-132,-48);

  //arco inferior inferior
  $g->img->filledArc(35,150,35,35,48,130);
  $g->img->SetColor('black');
  //arco lateral izquierdo
  $g->img->filledArc(35,150,35,35,120,-135);
  //arco lateral derecho
  $g->img->filledArc(35,150,35,35,312,-310);
  //contorno de los circulos
  $g->img->SetColor('white');
  $g->img->filledCircle(35,150,6);
  return $g;
  }
?>
