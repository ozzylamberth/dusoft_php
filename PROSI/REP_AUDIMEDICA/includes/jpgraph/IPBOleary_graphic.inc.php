<?php

/**
 * $Id: IPBOleary_graphic.inc.php,v 1.11 2005/06/07 18:28:17 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

/**
* Clase que muestra un diagrama de o`leary para la historia clinica odontologica (PHP).
*

/**
* IPBOleary_graphic.inc.php
*
//*
**/
 function IPBOleary($val,$seq,$valnoboca,$tipodont)//,$valnoboca
 //function IPBOleary($val,$valnoboca)
  {
//   list($dbconn) = GetDBconn();
// 	$sql="select nextval('asignanombrevirtualgraph_seq')";
// 	$result = $dbconn->Execute($sql);
// 
// 	if($dbconn->ErrorNo() != 0)
//    {
// 		die ($dbconn->ErrorMsg());
// 		return false;
// 	 }
// 	$seq=$result->fields[0];

	//$Dir="cache/oleary$seq.png";
  if($tipodont==1)
   {
     $Dir="cache/Olearyprime$seq.png";
   }
   else if($tipodont==2)
   {
     $Dir="cache/Olearytrata$seq.png";
   }

  //coordenadas
  $x=0;
  $y=0;

  //control de etiquetas
  $n[0]=10;
  $n[1]=16;
  $n[2]=16;
  $n[3]=10;

  include_once ("classes/jpgraph-1.14/src/jpgraph.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_line.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvas.php");//
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvtools.php");//

  $g = new CanvasGraph(850,270,'auto');
  $g->SetMargin(5,11,6,11);
  $g->SetShadow();
  $g->SetMarginColor("blue");
  $g->InitFrame();

  $g=Etiquetas1($g,$n);

    //CUADRANTE UNO - CINCO
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($val);$x2++)
           for ($y2=0;$y2<sizeof($val);$y2++)
           {
             if ($val[$x2][$y2]=='18')
             {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='entroval';
               $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
             }
             else
              if ($valnoboca[$x2][$y2]=='18')
              {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='noboca';
               $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
              }
              else
               $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='17')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                if ($valnoboca[$x2][$y2]=='17')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='16')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='16')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                  }
                  else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='15' || $val[$x2][$y2]=='55')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='15' || $valnoboca[$x2][$y2]=='55')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                  }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='14' || $val[$x2][$y2]=='54')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='14' || $valnoboca[$x2][$y2]=='54')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                  }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='13' || $val[$x2][$y2]=='53')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='13' || $valnoboca[$x2][$y2]=='53')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                  }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='12' || $val[$x2][$y2]=='52')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='12' || $valnoboca[$x2][$y2]=='52')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='11' || $val[$x2][$y2]=='51')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='11' || $valnoboca[$x2][$y2]=='51')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                  }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

    //CUADRANTE DOS - SEIS
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($val);$x2++)
           for ($y2=0;$y2<sizeof($val);$y2++)
           {
             if ($val[$x2][$y2]=='21' || $val[$x2][$y2]=='61')
             {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='entroval';
               $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
             }
             else
              if ($valnoboca[$x2][$y2]=='21' || $valnoboca[$x2][$y2]=='61')
              {
                $g=DibujarSano1($g,$y,$x);
                $_SESSION['label']['image']='noboca';
                $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
              }
              else
                $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='22' || $val[$x2][$y2]=='62')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='22' || $valnoboca[$x2][$y2]=='62')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='23' || $val[$x2][$y2]=='63')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='23' || $valnoboca[$x2][$y2]=='63')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='24' || $val[$x2][$y2]=='64')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='24' || $valnoboca[$x2][$y2]=='64')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='25' || $val[$x2][$y2]=='65')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';                  
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='25' || $valnoboca[$x2][$y2]=='65')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='26')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='26')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='27')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='27')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='28')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='28')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                  }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

       $y-=800;
     $x+=85;

    //CUADRANTE CUATRO - OCHO
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($val);$x2++)
           for ($y2=0;$y2<sizeof($val);$y2++)
           {
             if ($val[$x2][$y2]=='48')
             {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='entroval';
               $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
             }
             else
              if ($valnoboca[$x2][$y2]=='48')
              {
                $g=DibujarSano1($g,$y,$x);
                $_SESSION['label']['image']='noboca';
                $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
              }
              else
                $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='47')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='47')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='46')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='46')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                 else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='45' || $val[$x2][$y2]=='85')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='45' || $valnoboca[$x2][$y2]=='85')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                 else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='44' || $val[$x2][$y2]=='84')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='44' || $valnoboca[$x2][$y2]=='84')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                 else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='43' || $val[$x2][$y2]=='83')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='43' || $valnoboca[$x2][$y2]=='83')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                 else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='42' || $val[$x2][$y2]=='82')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='42' || $valnoboca[$x2][$y2]=='82')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                 else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='41' || $val[$x2][$y2]=='81')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,4,$_SESSION['label']['image']);
                }
                else
                 if ($valnoboca[$x2][$y2]=='41' || $valnoboca[$x2][$y2]=='81')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                 else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

    //CUADRANTE TRES - SIETE
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($val);$x2++)
           for ($y2=0;$y2<sizeof($val);$y2++)
           {
             if ($val[$x2][$y2]=='31' || $val[$x2][$y2]=='71')
             {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='entroval';
               $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
             }
             else
              if ($valnoboca[$x2][$y2]=='31' || $valnoboca[$x2][$y2]=='71')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='32' || $val[$x2][$y2]=='72')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='32' || $valnoboca[$x2][$y2]=='72')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='33' || $val[$x2][$y2]=='73')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='33' || $valnoboca[$x2][$y2]=='73')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='34' || $val[$x2][$y2]=='74')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='34' || $valnoboca[$x2][$y2]=='74')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                   $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='35' || $val[$x2][$y2]=='75')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='35' || $valnoboca[$x2][$y2]=='75')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='36')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='36')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='37')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='37')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
                if ($val[$x2][$y2]=='38')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='entroval';
                  $g=Arcos1($g,$x2,$y2,$val,$x,$y,3,$_SESSION['label']['image']);
                }
                else
                  if ($valnoboca[$x2][$y2]=='38')
                    {
                      $g=DibujarSano1($g,$y,$x);
                      $_SESSION['label']['image']='noboca';
                      $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                    }
                  else
                    $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }
  $g=SimbolosDientesCompletos($g,$valnoboca);
  //LINEAS CUADRANTES
  //vertical
  $g->img->Line(410,0,410,265);
  //horizontal
  $g->img->Line(0,137,840,137);

  $g->Stroke($Dir);
  return $Dir;
 }

  //DIBUJAR PIEZA SANA
  function DibujarSano1($g,$y,$x)
  {
  //arco superior
  $g->img->SetColor('black');
  $g->img->Arc($y+35,90+$x,35,35,-132,-48);
  //arco inferior inferior
  $g->img->Arc($y+35,90+$x,35,35,48,130);
  //arco lateral izquierdo
  $g->img->Arc($y+35,90+$x,35,35,120,-135);
  //arco lateral derecho
  $g->img->Arc($y+35,90+$x,35,35,312,-310);
  //lineas separadoras
  $g->img->line($y+25,$x+76,46+$y,$x+104);
  $g->img->line($y+48,$x+78,24+$y,$x+103);
  return $g;
  }

  //ARCOS DE LA PIEZA
 function Arcos1($g,$x1,$y1,$val,$x,$y,$cuadrante)
  {
    //CAMBIO DE SOMBRAS SEGUN EL CUADRANTE
    if ($cuadrante==1 || $cuadrante==5)
    {
      $c1='1';
      $c2='4';
      $c3='2';
      $c4='5';
    }
    else
    if ($cuadrante==2 || $cuadrante==6)
    {
      $c1='1';
      $c2='5';
      $c3='2';
      $c4='4';
    }
    else
    if ($cuadrante==4 || $cuadrante==8)
    {
      $c1='3';
      $c2='4';
      $c3='1';
      $c4='5';
    }
    else
    if ($cuadrante==3 || $cuadrante==7)
    {
      $c1='3';
      $c2='5';
      $c3='1';
      $c4='4';
    }
  if ($_SESSION['label']['image']=='entroval')
   {
    $g->img->SetColor('black');
    if ($val[$x1][$y1+1]==$c1)
    {
    //arco superior
      $g->img->filledArc($y+35,$x+90,35,35,-132,-46);
    }
    if ($val[$x1][$y1+1]==$c2)
    {
      //arco lateral derecho
      $g->img->filledArc($y+35,$x+90,35,35,300,-300);
    }
    if ($val[$x1][$y1+1]==$c3)
    {
      //arco inferior inferior
      $g->img->filledArc($y+35,$x+90,35,35,48,130);
    }
    if ($val[$x1][$y1+1]==$c4)
    {
      //arco lateral izquierdo
      $g->img->filledArc($y+35,$x+90,35,35,120,-120);
    }
    //DIENTE TOTALMENTE AFECTADO
    if ($val[$x1][$y1+1]=='11')
    {
      //arco superior
      $g->img->filledArc($y+35,$x+90,35,35,-132,-46);
      //arco lateral derecho
      $g->img->filledArc($y+35,$x+90,35,35,300,-300);
      //arco inferior inferior
      $g->img->filledArc($y+35,$x+90,35,35,48,130);
      //arco lateral izquierdo
      $g->img->filledArc($y+35,$x+90,35,35,120,-120);
    }
   }
   else
  if ($_SESSION['label']['image']=='noboca')
   {
    //diente sin erupcionar
    if ($val[$x1][$y1+1]=='2' OR $val[$x1][$y1+1]=='31')
    {
      $g=Diente_Sin_Erupcionar1($g,$y,$x);
    }
    //diente incluido
    if ($val[$x1][$y1+1]=='4')
    {
      $g=Diente_Incluido1($g,$y,$x);
    }
    //diente semi-incluido
    if ($val[$x1][$y1+1]=='5')
    {
     $g= Diente_Semi_Incluido1($g,$y,$x);
    }
    //ausente por exodoncia
    if ($val[$x1][$y1+1]=='8')
    {
      $g=Ausente_Exodoncia1($g,$y,$x);
    }
    //Protesis_Removible1($g,$y,$x)
    if ($val[$x1][$y1+1]=='12')
    {
      $g=Protesis_Removible1($g,$y,$x);
    }

   }
    return $g;
 }

  //SIMBOLOS
  //DIENTE SIN ERUPCIONAR
  function Diente_Sin_Erupcionar1($g,$y,$x)
  {
    $txt="-";
    $l = new Text($txt,$y+20,$x+86);
    $l->SetFont(FF_LUXIS,FS_NORMAL,48);
    $l->Stroke($g->img);
    return $g;
  }

 //DIENTE SEMI INCLUIDO
 function Diente_Semi_Incluido1($g,$y,$x)
 {
  $txt="\\";
  $l = new Text($txt,$y+20,$x+70);
  $l->SetFont(FF_LUXIS,FS_NORMAL,33);
  $l->Stroke($g->img);
  $txt="/";
  $l = new Text($txt,$y+20,70+$x);
  $l->SetFont(FF_LUXIS,FS_NORMAL,33);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,$y+20,$x+86);
  $l->SetFont(FF_LUXIS,FS_NORMAL,40);
  $l->Stroke($g->img);
  return $g;
 }

  //AUSENTE POR EXODONCIA
  function Ausente_Exodoncia1($g,$y,$x)
  {
    $txt="|";
    $l = new Text($txt,$y+33,$x+75);
    $l->SetFont(FF_LUXIS,FS_NORMAL,25);
    $l->Stroke($g->img);
    return $g;
  }

 //DIENTE INCLUIDO
 function Diente_Incluido1($g,$y,$x)
 {
  $txt="\\";
  $l = new Text($txt,$y+22,$x+70);
  $l->SetFont(FF_LUXIS,FS_NORMAL,33);
  $l->Stroke($g->img);
  $txt="/";
  $l = new Text($txt,$y+22,70+$x);
  $l->SetFont(FF_LUXIS,FS_NORMAL,33);
  $l->Stroke($g->img);

  $txt="-";
  $l = new Text($txt,$y+22,$x+83);
  $l->SetFont(FF_LUXIS,FS_NORMAL,40);
  $l->Stroke($g->img);
  $txt="-";
  $l = new Text($txt,$y+22,$x+90);
  $l->SetFont(FF_LUXIS,FS_NORMAL,40);
  $l->Stroke($g->img);
  return $g;
 }

  //PROTESIS REMOVIBLE
  function Protesis_Removible1($g,$y,$x)
  {
    $txt="-";
    $l = new Text($txt,$y+15,$x+85);
    $l->SetFont(FF_LUXIS,FS_NORMAL,65);
    $l->Stroke($g->img);
    return $g;
  }
  //FIN SIMBOLOS

  //ETIQUETAS
  function Etiquetas1($g,$n)
  {
    $i=0;
    $y=150;
    $x=0;
    $j=0;
    $k=0;
    $label=55;
    $label2=61;
    $label3=18;
    $label4=21;
    $label5=48;
    $label6=31;
    $label7=85;
    $label8=71;

    for ($l=0;$l<sizeof($n);)
    {
      while ($i<$n[$l])
      {
        if ($label>=51 and $label<=55 and $n[$l]==10)
        {
         $txt=(string) $label;
         $t = new Text($txt,25+$y,$x+28);
         $label=$label-1;
        }
        else
        if ($label2>=61 and $label2<=65 and $n[$l]==10)
        {
         $txt=(string) $label2;
         $t = new Text($txt,30+$y,$x+28);
         $label2=$label2+1;
        }
        else
        if ($n[$l]==16 and $label3>=11 and $label3<=18)
        {
          $txt=(string) $label3;
          $t = new Text($txt,25+$y,$x+28);
          $label3=$label3-1;
        }
        else
        if ($n[$l]==16 and $label4>=21 and $label4<=28)
        {
         $txt=(string) $label4;
         $t = new Text($txt,30+$y,$x+28);
         $label4=$label4+1;
        }
        else
        if ($n[$l]==16 and $label5>=41 and $label5<=48)
        {
         $txt=(string) $label5;
         $t = new Text($txt,30+$y,$x+28);
         $label5=$label5-1;
         $j++;
        }
        else
        if ($n[$l]==16 and $label6>=31 and $label6<=38)
        {
         $txt=(string) $label6;
         $t = new Text($txt,30+$y,$x+28);
         $label6=$label6+1;
        }
        else
        if ($n[$l]==10 and $label7>=81 and $label7<=85)
        {
         $txt=(string) $label7;
         $t = new Text($txt,30+$y,$x+28);
         $label7=$label7-1;
        }
        else
        if ($n[$l]==10 and $label8>=71 and $label8<=75)
        {
         $txt=(string) $label8;
         $t = new Text($txt,30+$y,$x+28);
         $label8=$label8+1;
        }

          $i++;
          $y+=50;
          $t->SetFont(FF_LUXIS,FS_NORMAL,8);
          $t->Stroke($g->img);
       }
       $l++;

      if ($n[$l]==16 and $label4<29)
       {
         $y=0;
         $x=-15;
       }
       else
       if ($n[$l]==10 || $label4==29 and $k<1)
        {
          $y=0;
          $x=145;
          $k++;
        }
       else
       if ($n[$l]==10 || $label4==29 and $k==1)
        {
          $y=150;
          $x=165;
        }

        $x+=35;
        $i=0;
     }
      return $g;
  }

 //simbolos de diente completo para ipbo'leary
 function SimbolosDientesCompletos($g,$valnoboca)
 {
  $x=0;
  $y=0;
    //CUADRANTE UNO - CINCO
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($valnoboca);$x2++)
           for ($y2=0;$y2<sizeof($valnoboca);$y2++)
           {
              if ($valnoboca[$x2][$y2]=='18')
              {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='noboca';
               $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
              }
              else
               $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='17')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='16')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='15' || $valnoboca[$x2][$y2]=='55')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                }
                else
                 $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='14' || $valnoboca[$x2][$y2]=='54')
                 {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                 }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
               if ($valnoboca[$x2][$y2]=='13' || $valnoboca[$x2][$y2]=='53')
                 {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                 }
                else
                 $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
               if ($valnoboca[$x2][$y2]=='12' || $valnoboca[$x2][$y2]=='52')
                 {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                 }
               else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
               if ($valnoboca[$x2][$y2]=='11' || $valnoboca[$x2][$y2]=='51')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,1,$_SESSION['label']['image']);
                }
               else
                 $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

    //CUADRANTE DOS - SEIS
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($valnoboca);$x2++)
           for ($y2=0;$y2<sizeof($valnoboca);$y2++)
           {
            if ($valnoboca[$x2][$y2]=='21' || $valnoboca[$x2][$y2]=='61')
             {
                $g=DibujarSano1($g,$y,$x);
                $_SESSION['label']['image']='noboca';
                $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
             }
            else
             $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='22' || $valnoboca[$x2][$y2]=='62')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
               else
                 $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='23' || $valnoboca[$x2][$y2]=='63')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='24' || $valnoboca[$x2][$y2]=='64')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
               if ($valnoboca[$x2][$y2]=='25' || $valnoboca[$x2][$y2]=='65')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='26')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='27')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='28')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,2,$_SESSION['label']['image']);
                }
                else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

       $y-=800;
     $x+=85;

    //CUADRANTE CUATRO - OCHO
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($valnoboca);$x2++)
           for ($y2=0;$y2<sizeof($valnoboca);$y2++)
           {
             if ($valnoboca[$x2][$y2]=='48')
             {
               $g=DibujarSano1($g,$y,$x);
               $_SESSION['label']['image']='noboca';
               $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
             }
            else
              $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='47')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='46')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='45' || $valnoboca[$x2][$y2]=='85')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='44' || $valnoboca[$x2][$y2]=='84')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='43' || $valnoboca[$x2][$y2]=='83')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='42' || $valnoboca[$x2][$y2]=='82')
                {
                  $g=DibujarSano1($g,$y,$x);
                  $_SESSION['label']['image']='noboca';
                  $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                }
              else
                $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='41' || $valnoboca[$x2][$y2]=='81')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,4,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

    //CUADRANTE TRES - SIETE
    for ($x1=0;$x1<1;$x1++)
      for ($y1=0;$y1<1;$y1++)
        {
         for ($x2=0;$x2<sizeof($valnoboca);$x2++)
           for ($y2=0;$y2<sizeof($valnoboca);$y2++)
           {
            if ($valnoboca[$x2][$y2]=='31' || $valnoboca[$x2][$y2]=='71')
              {
                $g=DibujarSano1($g,$y,$x);
                $_SESSION['label']['image']='noboca';
                $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
              }
            else
              $_SESSION['label']['image']='';//fin if
           }//fin for x2,y2

         }//fin for x1,y1
        if (!empty($_SESSION['label']['image']))
         {
           $y+=50;
           $_SESSION['label']['image']='';
         }
         else
         if ($_SESSION['label']['image']=='')
          {
            $g=DibujarSano1($g,$y,$x);
            $y+=50;
          }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='32' || $valnoboca[$x2][$y2]=='72')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='33' || $valnoboca[$x2][$y2]=='73')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                 $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='34' || $valnoboca[$x2][$y2]=='74')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                 $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='35' || $valnoboca[$x2][$y2]=='75')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='36')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='37')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }

        for ($x1=0;$x1<1;$x1++)
          for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($valnoboca);$x2++)
              for ($y2=0;$y2<sizeof($valnoboca);$y2++)
              {
                if ($valnoboca[$x2][$y2]=='38')
                  {
                    $g=DibujarSano1($g,$y,$x);
                    $_SESSION['label']['image']='noboca';
                    $g=Arcos1($g,$x2,$y2,$valnoboca,$x,$y,3,$_SESSION['label']['image']);
                  }
                else
                  $_SESSION['label']['image']='';//fin if
              }//fin for x2,y2

            }//fin for x1,y1
            if (!empty($_SESSION['label']['image']))
            {
              $y+=50;
              $_SESSION['label']['image']='';
            }
            else
            if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano1($g,$y,$x);
                $y+=50;
              }
   return $g;
 }


?>
