<?php

/**
 * $Id: Odontograma_graphic.inc.php,v 1.14 2008/01/22 14:40:36 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

/*
* Clase que muestra un Odontograma para la historia clinica odontologica (PHP).
*
/**
* Odontograma_graphic.inc.php
*
//*
**/

 function Odontograma($val,$seq,$tipodont)
//function Odontograma($val,$seq)
 {
/*  list($dbconn) = GetDBconn();
	$sql="select nextval('asignanombrevirtualgraph_seq')";
	$result = $dbconn->Execute($sql);
	if($dbconn->ErrorNo() != 0) {
		die ($dbconn->ErrorMsg());
		return false;
	}
	$seq=$result->fields[0];
	$Dir="cache/Odontograma$seq.png";*/
  include_once ("classes/jpgraph-1.14/src/jpgraph.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_line.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvas.php");//
  include_once ("classes/jpgraph-1.14/src/jpgraph_scatter.php");
  include_once ("classes/jpgraph-1.14/src/jpgraph_canvtools.php");//

  $n[0]=16;
  $n[1]=10;
  $n[2]=10;
  $n[3]=16;

  if($tipodont==1)
   {
     $Dir="cache/Odontoprime$seq.jpeg";
   }
   else if($tipodont==2)
   {
     $Dir="cache/Odontotrata$seq.jpeg";
   }

  $g = new CanvasGraph(820,392,'auto');
  $g->SetMargin(5,11,6,11);
  $g->SetShadow();
  $g->SetMarginColor("darkgray");
  $g->InitFrame();

  $i=0;
  $y=0;
  $j=0;
  $x=0;
  $k=0;
  $w=0;

	//CONTROL CUADRANTES
  $label=8;
  $label2=0;
  $label5=5;
  $label6=0;
  $label7=0;
  $label8=5;
  $label3=0;
  $label4=8;
	//FIN CONTROL CUADRANTES	
	
  //$g=arcos($g); //FUNCION DE PRUEBA PARA GENERAR LES SOMBRAS INTERIORES
  $t=Etiquetas($g,$n);

    for ($l=0;$l<sizeof($n);)
    {
      while ($i<$n[$l])
      {
       //ETIQUETAS
       //FIN ETIQUETAS
       //llama FUNCIÓN DIBUJARSANO
       //$g=DibujarSano($g,$y,$x);			 
        if ($label>0) //PRIMER CUADRANTE ()
        {
         for ($x1=0;$x1<1;$x1++)
           for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
               if ($val[$x2][0]=='18')
               {
                 $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                 $_SESSION['label']['image']='entro';
                 $label-='1';
               }
							 else
							 $_SESSION['label']['image']='';//fin if
             }//fin for x2,y2

            }//fin for x1,y1
             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
             if ($_SESSION['label']['image']=='')
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
               if ($val[$x2][0]=='17')
                  { 
                   $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                   $_SESSION['label']['image']='entro';
                   $label-=1;
                  }
									else
							    $_SESSION['label']['image']='';//fin if
               }//fin for x2
             }//fin for x1
             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
             if ($_SESSION['label']['image']=='')
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
                    if ($val[$x2][0]=='16')
                        {
                        $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                        $_SESSION['label']['image']='entro';
                        $label-=1;
                        }//fin if
												else
										    $_SESSION['label']['image']='';//fin if
                    }//fin for x2
                  }//fin for x1
			             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      			       if ($_SESSION['label']['image']=='')
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
                          if ($val[$x2][0]=='15')
                              {
                              $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                              $_SESSION['label']['image']='entro';
                              $label-=1;
                              }//fin if
                 							else
							   							$_SESSION['label']['image']='';//fin if
                          }//fin for x2

                        }//fin for x1
					              if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
          							if ($_SESSION['label']['image']=='')
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
                                if ($val[$x2][0]=='14')
                                    {
                                    $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                                    $_SESSION['label']['image']='entro';
                                    $label-=1;
                                    }                 							
																		else
							   										$_SESSION['label']['image']='';//fin if
                                }//fin for x2

                              }//fin for x1
							    	         if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      								       if ($_SESSION['label']['image']=='')
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
                                if ($val[$x2][0]=='13')
                                    {
                                      $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                                      $_SESSION['label']['image']='entro';
                                      $label-=1;
                                    }//fin if
																    else
							   								    $_SESSION['label']['image']='';//fin for x2
                                }																		
                              }//fin for x1
									             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      									       if ($_SESSION['label']['image']=='')
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
                                    if ($val[$x2][0]=='12')
                                        {
                                        $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                                        $_SESSION['label']['image']='entro';
                                        $label-=1;
                                        }//fin if
																			else
							   								      $_SESSION['label']['image']='';//fin for x2//fin for x2
                                    }

                                  }//fin for x1
											             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      			    									 if ($_SESSION['label']['image']=='')
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
                                        if ($val[$x2][0]=='11')
                                            {
                                            $g=DrawImage($g,$y,$x,$y2,$x2,$val,1);
                                    				$_SESSION['label']['image']='entro';
                                            $label-=1;
                                            }//fin if
																				   else
							   								           $_SESSION['label']['image']='';//fin for x2//fin for x2
                                        }//fin for x2
                                      }//fin for x1
													             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      			       										 if ($_SESSION['label']['image']=='')
                                        {
                                          $g=DibujarSano($g,$y,$x);
                                          $y+=50;
                                          $label-=1;
                                        }
         }//fin de cuadrante 1
         //llama FUNCIÓN DIBUJARSANO
         //$g=DibujarSano($g,$y,$x);
				//SEGUNDO CUADRANTE
					if ($label2<8)
						{
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='21')
										{
											$g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
											$_SESSION['label']['image']='entro';
											$label2+='1';
										}
										else
										$_SESSION['label']['image']='';//fin if
									}//fin for x2,y2

									}//fin for x1,y1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label2+=1;
										}

							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='22')
												{
                          $g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
                          $_SESSION['label']['image']='entro';
                          $label2+=1;
												}
												else
												$_SESSION['label']['image']='';//fin if
										}//fin for x2
									}//fin for x1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label2+=1;
										}

										for ($x1=0;$x1<1;$x1++)
											for ($y1=0;$y1<1;$y1++)
												{
												for ($x2=0;$x2<sizeof($val);$x2++)
													for ($y2=0;$y2<sizeof($val);$y2++)
													{
													if ($val[$x2][$y2]=='23')
															{
                                $g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
                                $_SESSION['label']['image']='entro';
                                $label2+=1;
															}//fin if
															else
															$_SESSION['label']['image']='';//fin if
													}//fin for x2
												}//fin for x1
												if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
												if ($_SESSION['label']['image']=='')
													{
														$g=DibujarSano($g,$y,$x);
														$y+=50;
														$label2+=1;
													}

													for ($x1=0;$x1<1;$x1++)
														for ($y1=0;$y1<1;$y1++)
															{
															for ($x2=0;$x2<sizeof($val);$x2++)
																for ($y2=0;$y2<sizeof($val);$y2++)
																{
																if ($val[$x2][$y2]=='24')
                                {
                                  $g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
                                  $_SESSION['label']['image']='entro';
                                  $label2+=1;
                                }//fin if
                                else
                                 $_SESSION['label']['image']='';//fin if
																}//fin for x2

															}//fin for x1
															if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
															if ($_SESSION['label']['image']=='')
															{
																	$g=DibujarSano($g,$y,$x);
																	$y+=50;
																	$label2+=1;
																}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='25')
																					{ 
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
																					$_SESSION['label']['image']='entro';
																					$label2+=1;
																					}                 							
																					else
																					$_SESSION['label']['image']='';//fin if
																			}//fin for x2
			
																		}//fin for x1
																	if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																	if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label2+=1;
																			}

																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='26')
																					{
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
																					$_SESSION['label']['image']='entro';
																					$label2+=1;
																					}//fin if
																				else
																				$_SESSION['label']['image']='';//fin for x2																		
																			}																		
																		}//fin for x1
																		if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																		if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label2+=1;
																			}
			
																		for ($x1=0;$x1<1;$x1++)
																			for ($y1=0;$y1<1;$y1++)
																				{
																				for ($x2=0;$x2<sizeof($val);$x2++)
																					for ($y2=0;$y2<sizeof($val);$y2++)
																					{
																					if ($val[$x2][0]=='27')
																							{
																							$g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
																							$_SESSION['label']['image']='entro';
																							$label2+=1;
																							}//fin if
																						else
																						$_SESSION['label']['image']='';//fin for x2//fin for x2
																					}

																				}//fin for x1
																				if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																				if ($_SESSION['label']['image']=='')
																					{ 
																						$g=DibujarSano($g,$y,$x);
																						$y+=50;
																						$label2+=1;
																					}
			
																				for ($x1=0;$x1<1;$x1++)
																					for ($y1=0;$y1<1;$y1++)
																						{
																						for ($x2=0;$x2<sizeof($val);$x2++)
																							for ($y2=0;$y2<sizeof($val);$y2++)
																							{
																							if ($val[$x2][$y2]=='28')
																									{
																									$g=DrawImage($g,$y,$x,$y2,$x2,$val,2);
																									$_SESSION['label']['image']='entro';
																									$label2+=1;
																									}//fin if
																								else
																								$_SESSION['label']['image']='';//fin for x2//fin for x2																							
																							}//fin for x2																			
																						}//fin for x1
																						if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																						if ($_SESSION['label']['image']=='')
																							{
																								$g=DibujarSano($g,$y,$x);
																								$y+=50;
																								$label2+=1;
																							}

						}//FIN SEGUNDO CUADRANTE
         $i++;
//         $y+=50;
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
			//INICIO QUINTO CUADRANTE
					if ($label5>0)
						{
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='55')
										{
											$g=DrawImage($g,$y,$x,$y2,$x2,$val,5);
											$_SESSION['label']['image']='entro';
											$label5-=1;
										}
										else
										$_SESSION['label']['image']='';//fin if
									}//fin for x2,y2

									}//fin for x1,y1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label5-=1;				
										}
			
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='54')
												{ 
												$g=DrawImage($g,$y,$x,$y2,$x2,$val,5);
												$_SESSION['label']['image']='entro';
												$label5-=1;
												}
												else
												$_SESSION['label']['image']='';//fin if
										}//fin for x2
									}//fin for x1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{ 
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label5-=1;
										}
			
										for ($x1=0;$x1<1;$x1++)
											for ($y1=0;$y1<1;$y1++)
												{
												for ($x2=0;$x2<sizeof($val);$x2++)
													for ($y2=0;$y2<sizeof($val);$y2++)
													{
													if ($val[$x2][$y2]=='53')
															{
															$g=DrawImage($g,$y,$x,$y2,$x2,$val,5);
															$_SESSION['label']['image']='entro';
															$label5-=1;
															}//fin if
															else
															$_SESSION['label']['image']='';//fin if
													}//fin for x2
												}//fin for x1
												if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
												if ($_SESSION['label']['image']=='')
													{
														$g=DibujarSano($g,$y,$x);
														$y+=50;
														$label5-=1;
													}
			
													for ($x1=0;$x1<1;$x1++)
														for ($y1=0;$y1<1;$y1++)
															{
															for ($x2=0;$x2<sizeof($val);$x2++)
																for ($y2=0;$y2<sizeof($val);$y2++)
																{
																if ($val[$x2][$y2]=='52')
																		{
																		$g=DrawImage($g,$y,$x,$y2,$x2,$val,5);
																		$_SESSION['label']['image']='entro';
																		$label5-=1;
																		}//fin if
																		else
																		$_SESSION['label']['image']='';//fin if
																}//fin for x2
			
															}//fin for x1
															if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
															if ($_SESSION['label']['image']=='')
															{
																	$g=DibujarSano($g,$y,$x);
																	$y+=50;
																	$label5-=1;
																}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='51')
																					{ 
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,5);
																					$_SESSION['label']['image']='entro';
																					$label5-=1;
																					}                 							
																					else
																					$_SESSION['label']['image']='';//fin if
																			}//fin for x2
			
																		}//fin for x1
																	if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																	if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label5-=1;
																			}			
						}//FIN QUINTO CUADRANTE			
											
			    //INICIO SEXTO CUADRANTE
					if ($label6<5)
						{
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='61')
										{
											$g=DrawImage($g,$y,$x,$y2,$x2,$val,6);
											$_SESSION['label']['image']='entro';
											$label6+=1;
										}
										else
										$_SESSION['label']['image']='';//fin if
									}//fin for x2,y2
			
									}//fin for x1,y1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label6+=1;				
										}
			
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='62')
												{ 
												$g=DrawImage($g,$y,$x,$y2,$x2,$val,6);
												$_SESSION['label']['image']='entro';
												$label6+=1;
												}
												else
												$_SESSION['label']['image']='';//fin if
										}//fin for x2
									}//fin for x1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{ 
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label6+=1;
										}
			
										for ($x1=0;$x1<1;$x1++)
											for ($y1=0;$y1<1;$y1++)
												{
												for ($x2=0;$x2<sizeof($val);$x2++)
													for ($y2=0;$y2<sizeof($val);$y2++)
													{
													if ($val[$x2][$y2]=='63')
															{
															$g=DrawImage($g,$y,$x,$y2,$x2,$val,6);
															$_SESSION['label']['image']='entro';
															$label6+=1;
															}//fin if
															else
															$_SESSION['label']['image']='';//fin if
													}//fin for x2
												}//fin for x1
												if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
												if ($_SESSION['label']['image']=='')
													{
														$g=DibujarSano($g,$y,$x);
														$y+=50;
														$label6+=1;
													}
			
													for ($x1=0;$x1<1;$x1++)
														for ($y1=0;$y1<1;$y1++)
															{
															for ($x2=0;$x2<sizeof($val);$x2++)
																for ($y2=0;$y2<sizeof($val);$y2++)
																{
																if ($val[$x2][$y2]=='64')
																		{
																		$g=DrawImage($g,$y,$x,$y2,$x2,$val,6);
																		$_SESSION['label']['image']='entro';
																		$label6+=1;
																		}//fin if
																		else
																		$_SESSION['label']['image']='';//fin if
																}//fin for x2
			
															}//fin for x1
															if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
															if ($_SESSION['label']['image']=='')
															{
																	$g=DibujarSano($g,$y,$x);
																	$y+=50;
																	$label6+=1;
																}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='65')
																					{
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,6);
																					$_SESSION['label']['image']='entro';
																					$label6+=1;
																					}                 							
																					else
																					$_SESSION['label']['image']='';//fin if
																			}//fin for x2
			
																		}//fin for x1
																	if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																	if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label6+=1;
																			}			
						}//FIN SEXTO CUADRANTE
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
			
			//INICIO OCTAVO CUADRANTE
					if ($label8>0)
						{
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='85')
										{
											$g=DrawImage($g,$y,$x,$y2,$x2,$val,8);
											$_SESSION['label']['image']='entro';
											$label8-=1;
										}
										else
										$_SESSION['label']['image']='';//fin if
									}//fin for x2,y2
			
									}//fin for x1,y1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label8-=1;				
										}
			
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='84')
												{ 
												$g=DrawImage($g,$y,$x,$y2,$x2,$val,8);
												$_SESSION['label']['image']='entro';
												$label8-=1;
												}
												else
												$_SESSION['label']['image']='';//fin if
										}//fin for x2
									}//fin for x1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{ 
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label8-=1;
										}
			
										for ($x1=0;$x1<1;$x1++)
											for ($y1=0;$y1<1;$y1++)
												{
												for ($x2=0;$x2<sizeof($val);$x2++)
													for ($y2=0;$y2<sizeof($val);$y2++)
													{
													if ($val[$x2][$y2]=='83')
															{
															$g=DrawImage($g,$y,$x,$y2,$x2,$val,8);
															$_SESSION['label']['image']='entro';
															$label8-=1;
															}//fin if
															else
															$_SESSION['label']['image']='';//fin if
													}//fin for x2
												}//fin for x1
												if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
												if ($_SESSION['label']['image']=='')
													{
														$g=DibujarSano($g,$y,$x);
														$y+=50;
														$label8-=1;
													}
			
													for ($x1=0;$x1<1;$x1++)
														for ($y1=0;$y1<1;$y1++)
															{
															for ($x2=0;$x2<sizeof($val);$x2++)
																for ($y2=0;$y2<sizeof($val);$y2++)
																{
																if ($val[$x2][$y2]=='82')
																		{
																		$g=DrawImage($g,$y,$x,$y2,$x2,$val,8);
																		$_SESSION['label']['image']='entro';
																		$label8-=1;
																		}//fin if
																		else
																		$_SESSION['label']['image']='';//fin if
																}//fin for x2
			
															}//fin for x1
															if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
															if ($_SESSION['label']['image']=='')
															{
																	$g=DibujarSano($g,$y,$x);
																	$y+=50;
																	$label8-=1;
																}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='81')
																					{ 
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,8);
																					$_SESSION['label']['image']='entro';
																					$label8-=1;
																					}                 							
																					else
																					$_SESSION['label']['image']='';//fin if
																			}//fin for x2
			
																		}//fin for x1
																	if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																	if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label8-=1;
																			}			
						}//FIN OCTAVO CUADRANTE			

			//INICIO SEPTIMO CUADRANTE
					if ($label7<5)
						{
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='71')
										{
											$g=DrawImage($g,$y,$x,$y2,$x2,$val,7);
											$_SESSION['label']['image']='entro';
											$label7+=1;
										}
										else
										$_SESSION['label']['image']='';//fin if
									}//fin for x2,y2
			
									}//fin for x1,y1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label7+=1;				
										}
			
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='72')
												{ 
												$g=DrawImage($g,$y,$x,$y2,$x2,$val,7);
												$_SESSION['label']['image']='entro';
												$label7+=1;
												}
												else
												$_SESSION['label']['image']='';//fin if
										}//fin for x2
									}//fin for x1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{ 
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label7+=1;
										}
			
										for ($x1=0;$x1<1;$x1++)
											for ($y1=0;$y1<1;$y1++)
												{
												for ($x2=0;$x2<sizeof($val);$x2++)
													for ($y2=0;$y2<sizeof($val);$y2++)
													{
													if ($val[$x2][$y2]=='73')
															{
															$g=DrawImage($g,$y,$x,$y2,$x2,$val,7);
															$_SESSION['label']['image']='entro';
															$label7+=1;
															}//fin if
															else
															$_SESSION['label']['image']='';//fin if
													}//fin for x2
												}//fin for x1
												if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
												if ($_SESSION['label']['image']=='')
													{
														$g=DibujarSano($g,$y,$x);
														$y+=50;
														$label7+=1;
													}
			
													for ($x1=0;$x1<1;$x1++)
														for ($y1=0;$y1<1;$y1++)
															{
															for ($x2=0;$x2<sizeof($val);$x2++)
																for ($y2=0;$y2<sizeof($val);$y2++)
																{
																if ($val[$x2][$y2]=='74')
																		{
																		$g=DrawImage($g,$y,$x,$y2,$x2,$val,7);
																		$_SESSION['label']['image']='entro';
																		$label7+=1;
																		}//fin if
																		else
																		$_SESSION['label']['image']='';//fin if
																}//fin for x2
			
															}//fin for x1
															if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
															if ($_SESSION['label']['image']=='')
															{
																	$g=DibujarSano($g,$y,$x);
																	$y+=50;
																	$label7+=1;
																}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='75')
																					{ 
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,7);
																					$_SESSION['label']['image']='entro';
																					$label7+=1;
																					}                 							
																					else
																					$_SESSION['label']['image']='';//fin if
																			}//fin for x2
			
																		}//fin for x1
																	if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																	if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label7+=1;
																			}			
						}//FIN SEPTIMO CUADRANTE			
												
		      if ($n[$l]==10 && $k==0)
       {
         $y-=650;
         $k++;
       }
       else
         if ($n[$l]==10 && $k==1)
           $y-=650;
         else
           $y=0;
      $x+=95;
      $i=0;	
			
        if ($label4>0) //CUARTO CUADRANTE ()
        {
         for ($x1=0;$x1<1;$x1++)
           for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
               if ($val[$x2][$y2]=='48')
               {
                 $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                 $_SESSION['label']['image']='entro';
                 $label4-='1';
               }
							 else
							 $_SESSION['label']['image']='';//fin if
             }//fin for x2,y2

            }//fin for x1,y1
             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
             if ($_SESSION['label']['image']=='')
              {
                $g=DibujarSano($g,$y,$x);
                $y+=50;
                $label4-=1;				
              }

         for ($x1=0;$x1<1;$x1++)
           for ($y1=0;$y1<1;$y1++)
            {
            for ($x2=0;$x2<sizeof($val);$x2++)
              for ($y2=0;$y2<sizeof($val);$y2++)
              {
               if ($val[$x2][$y2]=='47')
                  { 
                   $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                   $_SESSION['label']['image']='entro';
                   $label4-=1;
                  }
									else
							    $_SESSION['label']['image']='';//fin if
               }//fin for x2
             }//fin for x1
             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
             if ($_SESSION['label']['image']=='')
						  { 
                $g=DibujarSano($g,$y,$x);
                $y+=50;
                $label4-=1;
              }

              for ($x1=0;$x1<1;$x1++)
                for ($y1=0;$y1<1;$y1++)
                  {
                  for ($x2=0;$x2<sizeof($val);$x2++)
                    for ($y2=0;$y2<sizeof($val);$y2++)
                    {
                    if ($val[$x2][$y2]=='46')
                        {
                        $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                        $_SESSION['label']['image']='entro';
                        $label4-=1;
                        }//fin if
												else
										    $_SESSION['label']['image']='';//fin if
                    }//fin for x2
                  }//fin for x1
			             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      			       if ($_SESSION['label']['image']=='')
						        {
                      $g=DibujarSano($g,$y,$x);
                      $y+=50;
                      $label4-=1;
                    }

                    for ($x1=0;$x1<1;$x1++)
                      for ($y1=0;$y1<1;$y1++)
                        {
                        for ($x2=0;$x2<sizeof($val);$x2++)
                          for ($y2=0;$y2<sizeof($val);$y2++)
                          {
                          if ($val[$x2][$y2]=='45')
                              {
                              $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                              $_SESSION['label']['image']='entro';
                              $label4-=1;
                              }//fin if
                 							else
							   							$_SESSION['label']['image']='';//fin if
                          }//fin for x2

                        }//fin for x1
					              if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
          							if ($_SESSION['label']['image']=='')
						             {
                            $g=DibujarSano($g,$y,$x);
                            $y+=50;
                            $label4-=1;
                          }

                          for ($x1=0;$x1<1;$x1++)
                            for ($y1=0;$y1<1;$y1++)
                              {
                              for ($x2=0;$x2<sizeof($val);$x2++)
                                for ($y2=0;$y2<sizeof($val);$y2++)
                                {
                                if ($val[$x2][$y2]=='44')
                                    { 
                                    $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                                    $_SESSION['label']['image']='entro';
                                    $label4-=1;
                                    }                 							
																		else
							   										$_SESSION['label']['image']='';//fin if
                                }//fin for x2

                              }//fin for x1
							    	         if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      								       if ($_SESSION['label']['image']=='')
																{
                                  $g=DibujarSano($g,$y,$x);
                                  $y+=50;
                                  $label4-=1;
                                }

                          for ($x1=0;$x1<1;$x1++)
                            for ($y1=0;$y1<1;$y1++)
                              {
                              for ($x2=0;$x2<sizeof($val);$x2++)
                                for ($y2=0;$y2<sizeof($val);$y2++)
                                {
                                if ($val[$x2][$y2]=='43')
                                    {
                                    $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                                    $_SESSION['label']['image']='entro';
                                    $label4-=1;
                                    }//fin if
																   else
							   								   $_SESSION['label']['image']='';//fin for x2																		
                                }																		
                              }//fin for x1
									             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      									       if ($_SESSION['label']['image']=='')
																{
                                  $g=DibujarSano($g,$y,$x);
                                  $y+=50;
                                  $label4-=1;
                                }

                              for ($x1=0;$x1<1;$x1++)
                                for ($y1=0;$y1<1;$y1++)
                                  {
                                  for ($x2=0;$x2<sizeof($val);$x2++)
                                    for ($y2=0;$y2<sizeof($val);$y2++)
                                    {
                                    if ($val[$x2][$y2]=='42')
                                        {
                                        $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                                        $_SESSION['label']['image']='entro';
                                        $label4-=1;
                                        }//fin if
																			else
							   								      $_SESSION['label']['image']='';//fin for x2//fin for x2																					
                                    }																

                                  }//fin for x1
											             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      			    									 if ($_SESSION['label']['image']=='')
																		{
                                      $g=DibujarSano($g,$y,$x);
                                      $y+=50;
                                      $label4-=1;
                                    }

                                  for ($x1=0;$x1<1;$x1++)
                                    for ($y1=0;$y1<1;$y1++)
                                      {
                                      for ($x2=0;$x2<sizeof($val);$x2++)
                                        for ($y2=0;$y2<sizeof($val);$y2++)
                                        {
                                        if ($val[$x2][$y2]=='41')
                                            {
                                            $g=DrawImage($g,$y,$x,$y2,$x2,$val,4);
                                    				$_SESSION['label']['image']='entro';
                                            $label4-=1;
                                            }//fin if
																				   else
							   								           $_SESSION['label']['image']='';//fin for x2//fin for x2																							
                                        }//fin for x2																			
                                      }//fin for x1
													             if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
      			       										 if ($_SESSION['label']['image']=='')
                                        {
                                          $g=DibujarSano($g,$y,$x);
                                          $y+=50;
                                          $label4-=1;
                                        }
         }//fin del CUARTO cuadrante 

				//TERCER CUADRANTE
					if ($label3<8)
						{
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='31')
										{
											$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
											$_SESSION['label']['image']='entro';
											$label3+='1';
										}
										else
										$_SESSION['label']['image']='';//fin if
									}//fin for x2,y2
			
									}//fin for x1,y1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label3+=1;				
										}
			
							for ($x1=0;$x1<1;$x1++)
								for ($y1=0;$y1<1;$y1++)
									{
									for ($x2=0;$x2<sizeof($val);$x2++)
										for ($y2=0;$y2<sizeof($val);$y2++)
										{
										if ($val[$x2][$y2]=='32')
												{ 
												$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
												$_SESSION['label']['image']='entro';
												$label3+=1;
												}
												else
												$_SESSION['label']['image']='';//fin if
										}//fin for x2
									}//fin for x1
									if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
									if ($_SESSION['label']['image']=='')
										{ 
											$g=DibujarSano($g,$y,$x);
											$y+=50;
											$label3+=1;
										}
			
										for ($x1=0;$x1<1;$x1++)
											for ($y1=0;$y1<1;$y1++)
												{
												for ($x2=0;$x2<sizeof($val);$x2++)
													for ($y2=0;$y2<sizeof($val);$y2++)
													{
													if ($val[$x2][$y2]=='33')
															{
															$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
															$_SESSION['label']['image']='entro';
															$label3+=1;
															}//fin if
															else
															$_SESSION['label']['image']='';//fin if
													}//fin for x2
												}//fin for x1
												if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
												if ($_SESSION['label']['image']=='')
													{
														$g=DibujarSano($g,$y,$x);
														$y+=50;
														$label3+=1;
													}
			
													for ($x1=0;$x1<1;$x1++)
														for ($y1=0;$y1<1;$y1++)
															{
															for ($x2=0;$x2<sizeof($val);$x2++)
																for ($y2=0;$y2<sizeof($val);$y2++)
																{
																if ($val[$x2][$y2]=='34')
																		{
																		$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
																		$_SESSION['label']['image']='entro';
																		$label3+=1;
																		}//fin if
																		else
																		$_SESSION['label']['image']='';//fin if
																}//fin for x2
			
															}//fin for x1
															if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
															if ($_SESSION['label']['image']=='')
															{
																	$g=DibujarSano($g,$y,$x);
																	$y+=50;
																	$label3+=1;
																}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='35')
																					{ 
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
																					$_SESSION['label']['image']='entro';
																					$label3+=1;
																					}                 							
																					else
																					$_SESSION['label']['image']='';//fin if
																			}//fin for x2
			
																		}//fin for x1
																	if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																	if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label3+=1;
																			}
			
																for ($x1=0;$x1<1;$x1++)
																	for ($y1=0;$y1<1;$y1++)
																		{
																		for ($x2=0;$x2<sizeof($val);$x2++)
																			for ($y2=0;$y2<sizeof($val);$y2++)
																			{
																			if ($val[$x2][$y2]=='36')
																					{
																					$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
																					$_SESSION['label']['image']='entro';
																					$label2+=1;
																					}//fin if
																				else
																				$_SESSION['label']['image']='';//fin for x2																		
																			}																		
																		}//fin for x1
																		if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																		if ($_SESSION['label']['image']=='')
																			{
																				$g=DibujarSano($g,$y,$x);
																				$y+=50;
																				$label3+=1;
																			}
			
																		for ($x1=0;$x1<1;$x1++)
																			for ($y1=0;$y1<1;$y1++)
																				{
																				for ($x2=0;$x2<sizeof($val);$x2++)
																					for ($y2=0;$y2<sizeof($val);$y2++)
																					{
																					if ($val[$x2][$y2]=='37')
																							{
																							$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
																							$_SESSION['label']['image']='entro';
																							$label3+=1;
																							}//fin if
																						else
																						$_SESSION['label']['image']='';//fin for x2//fin for x2																					
																					}																
			
																				}//fin for x1
																				if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																				if ($_SESSION['label']['image']=='')
																					{
																						$g=DibujarSano($g,$y,$x);
																						$y+=50;
																						$label3+=1;
																					}
			
																				for ($x1=0;$x1<1;$x1++)
																					for ($y1=0;$y1<1;$y1++)
																						{
																						for ($x2=0;$x2<sizeof($val);$x2++)
																							for ($y2=0;$y2<sizeof($val);$y2++)
																							{
																							if ($val[$x2][$y2]=='38')
																									{
																									$g=DrawImage($g,$y,$x,$y2,$x2,$val,3);
																									$_SESSION['label']['image']='entro';
																									$label3+=1;
																									}//fin if
																								else
																								$_SESSION['label']['image']='';//fin for x2//fin for x2																							
																							}//fin for x2																			
																						}//fin for x1
																						if (!empty($_SESSION['label']['image'])) {$y+=50; $_SESSION['label']['image']='';}else
																						if ($_SESSION['label']['image']=='')
																							{
																								$g=DibujarSano($g,$y,$x);
																								$y+=50;
																								$label3+=1;
																							}
						}//FIN TERCER CUADRANTE																				
				 					
    }
  //LINEAS CUADRANTES
  //vertical
  $g->img->Line(410,0,410,382);
  //horizontal
  $g->img->Line(0,190,815,190);
  //$g=Simbolos($g);
  $g->Stroke($Dir);

	UNSET($_SESSION['label']);
  return $Dir;
 }

  //DIBUJAR DIENTE SANO
  function DibujarSano($g,$y,$x)
  {
    $g->img->SetColor('black');
    //linea superior izq
    $g->img->Line($y+21,29+$x,$y+33,45+$x);
    //linea superior der
    $g->img->Line($y+52,31+$x,$y+39,46+$x);
    //linea inferior izq
    $g->img->Line($y+33,55+$x,$y+20,73+$x);
    //linea inferior der
    $g->img->Line($y+37,54+$x,$y+52,71+$x);
    //ARCOS
    //arco superior
    $g->img->Arc($y+35,50+$x,54,54,-125,-50);
    //arco inferior
    $g->img->Arc($y+35,50+$x,54,54,50,125);
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
    $g->img->filledArc($y+39,$x+50,25,25,305,-295);
    //RELENAR ARC
    $g->img->filledArc($y+39,$x+46,20,20,305,-291);
    $g->img->filledArc($y+37,$x+54,20,20,291,-308);
    return $g;
  }

  //obturación temporal arco superior
  function COT_S($g,$y,$x)
  {
  //PIEZA 2 SOMBREADO DE LA ZONA 1
  //arco siperior
  $g->img->SetColor('black');
  $g->img->filledArc($y+35,$x+36,28,28,-165,0);
  //RELLENO SUPERIOR
  $g->img->filledArc($y+20,$x+28,25,25,335,-305);
  $g->img->filledArc($y+53,$x+30,25,25,-230,210);
  return $g;
  }

  //RESIBNA SUPERIOR
/************************************************/
 function Resina_S($g,$y,$x,$cambiar)
  {
  //PIEZA 7 - SOMBREADO DE LA ZONA 6
  //arco superior
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+30,24+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

//FUNCIÓN AMALGAMA_SUPERIOR
/********************************************/
  function Amalgama_S($g,$y,$x,$cambiar)
  {
    //PIEZA 6 - SOMBREADO DE LA ZONA 5
    //texto simbolos
    $txt="-";
    $l = new Text($txt,$y+31,$x+26);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);

    if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  //****RESTAURACION DESADAPTADA SUPERIOR CUELLO_DESCUBIERTO
  function Restauracion_Desadaptada_S($g,$y,$x)
  {
    //PIEZA 12 - SOMBREADO DE LA ZONA 11
    //simbolo *
    //Restauración desadaptada
    //simbolo multiplicación(x)
    $txt="*";
    $l = new Text($txt,$y+30,$x+24);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }

/************************************************/
//superficie sellada superior
function Sellada_S($g,$y,$x,$cambiar)
{
  //PIEZA 20 SOMBREADO DE LA ZONA 1
  //superficie sellada
  //SIMBOLO S
  $txt="S";
  $t = new Text($txt,$y+31,$x+24);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);

  if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }

//SUPERFICIE POR SELLAR
  if ($cambiar=='18')
  {
    $txt="*";
    $l = new Text($txt,$y+38,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

function COT_I($g,$y,$x)
{
  //PIEZA 3 SOMBREADO DE LA ZONA 2
  $g->img->SetColor('black');
  //arco INFERIOR
  $g->img->filledArc($y+36,$x+64,28,28,20,163);
  //RELLENO INFERIOR
  $g->img->filledArc($y+20,$x+73,28,28,310,-345);
  $g->img->filledArc($y+53,$x+72,28,28,-200,230);
  return $g;
}

function Resina_I($g,$y,$x,$cambiar)
{
  //PIEZA 7 - SOMBREADO DE LA ZONA 6
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+31,66+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

function Amalgama_I($g,$y,$x,$cambiar)
{
  //PIEZA 6 - SOMBREADO DE LA ZONA 5
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+68);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
   return $g;
}
//CUELLO_DESCUBIERTO
function Restauracion_Desadaptada_I($g,$y,$x)
{
  //simbolo multiplicación(x)
  $txt="*";
  $l = new Text($txt,$y+30,$x+66);
  $l->SetFont(FF_LUXIS,FS_NORMAL,12);
  $l->Stroke($g->img);
  return $g;

}
//superficie sellada interna
function Sellada_I($g,$y,$x,$cambiar)
{
  //SIMBOLO S
  $txt="S";
  $t = new Text($txt,$y+31,$x+66);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);

  if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }

//SUPERFICIE POR SELLAR
  if ($cambiar=='18')
  {
    $txt="*";
    $l = new Text($txt,$y+38,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

function COT_SI($g,$y,$x)
{
  //arco superior interior
  $g->img->SetColor('black');
  //arco superior INTERIOR
  $g->img->filledArc($y+35,$x+47,28,28,-137,-30);
  return $g;
}

function Resina_SI($g,$y,$x,$cambiar)
{
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+31,36+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;

}

function Amalgama_SI($g,$y,$x,$cambiar)
{
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+38);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

//CUELLO_DESCUBIERTO
function Restauracion_Desadaptada_SI($g,$y,$x)
{
  //simbolo multiplicación(x)
  $txt="*";
  $l = new Text($txt,$y+30,$x+35);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Sellada_SI($g,$y,$x,$cambiar)
{
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+32,$x+35);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);

  if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }

//SUPERFICIE POR SELLAR
  if ($cambiar=='18')
  {
    $txt="*";
    $l = new Text($txt,$y+38,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

function Resina_LD($g,$y,$x,$cambiar)
{
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+41,45+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

function Amalgama_LD($g,$y,$x,$cambiar)
{
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+41,$x+46);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

//CUELLO_DESCUBIERTO
function Restauracion_Desadaptada_LD($g,$y,$x)
{
  //simbolo multiplicación(x)
  $txt="*";
  $l = new Text($txt,$y+41,$x+44);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Sellada_LD($g,$y,$x,$cambiar)
{
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+42,$x+45);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);

  if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }

//SUPERFICIE POR SELLAR
  if ($cambiar=='18')
  {
    $txt="*";
    $l = new Text($txt,$y+38,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

function  COT_II($g,$y,$x)
{
  //arco inferior inferior
   $g->img->SetColor('black');
  //arco inferior inferior
  $g->img->filledArc($y+37,$x+54,27,23,42,142);
  return $g;
}

function Resina_II($g,$y,$x,$cambiar)
{
  //lineas del simbolo más(+)
  //texto simbolos
  $txt="+";
  $l = new Text($txt,$y+31,55+$x);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}


function Amalgama_II($g,$y,$x,$cambiar)
{
  $txt="-";
  $l = new Text($txt,$y+32,$x+57);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;

}

//CUELLO_DESCUBIERTO
function Restauracion_Desadaptada_II($g,$y,$x)
{
  //simbolo multiplicación(x)
  $txt="*";
  $l = new Text($txt,$y+31,$x+56);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);
  return $g;
}

function Sellada_II($g,$y,$x,$cambiar)
{
  //SIMBOLO S
  $txt="s";
  $t = new Text($txt,$y+32,$x+56);
  $t->SetFont(FF_LUXIS,FS_BOLD,9);
  $t->Stroke($g->img);

  if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }

 //SUPERFICIE POR SELLAR
  if ($cambiar=='18')
  {
    $txt="*";
    $l = new Text($txt,$y+38,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
}

  //CARIES OBTTURACIÓN TEMPORAL LATERAL IZQUIERDA
  /************************************************/
  function COT_LI($g,$y,$x)
  {
    $g->img->SetColor('black');
    //arco lateral izquierdo
    $g->img->filledArc($y+31,$x+50,28,28,-260,258);
    //RELLENO lateral izquierdo
    $g->img->filledArc($y+34,$x+54,20,20,-240,200);
    $g->img->filledArc($y+33,$x+45,20,20,-240,230);
    return $g;
  }

  //resina lateral izquierda
  function Resina_LI($g,$y,$x,$cambiar)
  {
    //lineas del simbolo más(+)
    //texto simbolos
    $txt="+";
    $l = new Text($txt,$y+21,45+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  function Amalgama_LI($g,$y,$x,$cambiar)
  {
    //texto simbolos
    $txt="-";
    $l = new Text($txt,$y+21,$x+47);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);

    if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  //CUELLO_DESCUBIERTO
  function Restauracion_Desadaptada_LI($g,$y,$x)
  {
    //simbolo multiplicación(x)
    $txt="*";
    $l = new Text($txt,$y+20,$x+45);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    return $g;
  }

  //sellada lateral izquierda
  function Sellada_LI($g,$y,$x,$cambiar)
  {
    //SIMBOLO S
    $txt="s";
    $t = new Text($txt,$y+21,$x+45);
    $t->SetFont(FF_LUXIS,FS_BOLD,9);
    $t->Stroke($g->img);

    if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }

  //SUPERFICIE POR SELLAR
    if ($cambiar=='18')
    {
      $txt="*";
      $l = new Text($txt,$y+38,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  function COT_C($g,$y,$x)
  {

    $g->img->SetColor('black');
    $g->img->filledCircle($y+35,50+$x,6);
    return $g;
  }

 function Resina_C($g,$y,$x,$cambiar)
 {
    //lineas del simbolo más(+)
    //texto simbolos
    $txt="+";
    $l = new Text($txt,$y+30,45+$x);
    $l->SetFont(FF_LUXIS,FS_BOLD,12);
    $l->Stroke($g->img);
    if ($cambiar=='26')//SUPERFICIE EN RESINA POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  function Amalgama_C($g,$y,$x,$cambiar)
  {
  //texto simbolos
  $txt="-";
  $l = new Text($txt,$y+31,$x+48);
  $l->SetFont(FF_LUXIS,FS_BOLD,12);
  $l->Stroke($g->img);

  if ($cambiar=='25')//SUPERFICIE EN AMALGAMA POR CAMBIAR
  {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
  }
  return $g;
  }

  //CUELLO_DESCUBIERTO
  function Restauracion_Desadaptada_C($g,$y,$x)
  {
  //simbolo multiplicación(x)
  $txt="*";
  $l = new Text($txt,$y+30,$x+45);
  $l->SetFont(FF_LUXIS,FS_BOLD,16);
  $l->Stroke($g->img);
  return $g;
  }

  function Sellada_C($g,$y,$x,$cambiar)
  {
    $txt="s";
    $t = new Text($txt,$y+32,$x+46);
    $t->SetFont(FF_LUXIS,FS_BOLD,9);
    $t->Stroke($g->img);

    if ($cambiar=='27')//SUPERFICIE SELLADA POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }

  //SUPERFICIE POR SELLAR
    if ($cambiar=='18')
    {
      $txt="*";
      $l = new Text($txt,$y+38,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }
  //incrustacion superior
  function Incrustacion_S($g,$y,$x,$cambiar)
  {
    if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $t = new Text($txt,$y+31,$x+24);
      $t->SetFont(FF_LUXIS,FS_BOLD,9);
      $t->Stroke($g->img);
    }

    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $t = new Text($txt,$y+31,$x+24);
      $t->SetFont(FF_LUXIS,FS_BOLD,9);
      $t->Stroke($g->img);
    }

    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="///";
      $t = new Text($txt,$y+29,$x+26);
      $t->SetFont(FF_LUXIS,FS_BOLD,5);
      $t->Stroke($g->img);
    }
    return $g;
  }

  //incrustacion INFERIOR
  function Incrustacion_I($g,$y,$x,$cambiar)
  {
    if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $l = new Text($txt,$y+31,65+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,11);
      $l->Stroke($g->img);
    }
    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $l = new Text($txt,$y+31,65+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,11);
      $l->Stroke($g->img);
    }

    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="///";
      $t = new Text($txt,$y+28,$x+65);
      $t->SetFont(FF_LUXIS,FS_BOLD,5);
      $t->Stroke($g->img);
    }
    return $g;
  }

  //incrustacion SUPERIOR INTERIOR
  function Incrustacion_SI($g,$y,$x,$cambiar)
  {
    if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $l = new Text($txt,$y+31,36+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $l = new Text($txt,$y+31,36+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }

    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="///";
      $t = new Text($txt,$y+28,$x+36);
      $t->SetFont(FF_LUXIS,FS_BOLD,5);
      $t->Stroke($g->img);
    }
    return $g;
  }

  //incrustacion LATERAL DERECHA
  function Incrustacion_LD($g,$y,$x,$cambiar)
  {
    if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $l = new Text($txt,$y+41,44+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $l = new Text($txt,$y+41,44+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="//";
      $t = new Text($txt,$y+40,$x+45);
      $t->SetFont(FF_LUXIS,FS_BOLD,5);
      $t->Stroke($g->img);
    }

    return $g;
  }

  //incrustacion INFERIOR INTERIOR
  function Incrustacion_II($g,$y,$x,$cambiar)
  {
    if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $l = new Text($txt,$y+33,56+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,8);
      $l->Stroke($g->img);
    }
    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $l = new Text($txt,$y+33,56+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,8);
      $l->Stroke($g->img);
    }
    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="///";
      $t = new Text($txt,$y+29,$x+56);
      $t->SetFont(FF_LUXIS,FS_BOLD,5);
      $t->Stroke($g->img);
    }
    return $g;
  }

  //incrustacion LATERAL IZQUIERDA
  function Incrustacion_LI($g,$y,$x,$cambiar)
  {
    if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $l = new Text($txt,$y+21,44+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $l = new Text($txt,$y+21,44+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="///";
      $t = new Text($txt,$y+18,$x+46);
      $t->SetFont(FF_LUXIS,FS_BOLD,5);
      $t->Stroke($g->img);
    }
    return $g;
  }

    //incrustacion CENTRAL
  function Incrustacion_C($g,$y,$x,$cambiar)
  {
   if ($cambiar=='19')//INCRUSTACION
    {
      $txt="I";
      $l = new Text($txt,$y+31,44+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='20')//INCRUSTACION POR CAMBIAR
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
      $txt="I";
      $l = new Text($txt,$y+31,44+$x);
      $l->SetFont(FF_LUXIS,FS_BOLD,12);
      $l->Stroke($g->img);
    }
    if ($cambiar=='28')//CARIES_INCIPIENTE
    {
      $txt="///";
      $t = new Text($txt,$y+30,$x+47);
      $t->SetFont(FF_LUXIS,FS_BOLD,4);
      $t->Stroke($g->img);
    }
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
  function Protesis_Removible($g,$y,$x,$cambiar)
  {
    $txt="-";
    $l = new Text($txt,$y+13,$x+44);
    $l->SetFont(FF_LUXIS,FS_NORMAL,70);
    $l->Stroke($g->img);

    if ($cambiar=='23')
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  //PROTESIS FIJA
  function Protesis_Fija($g,$y,$x,$cambiar)
  {
   $txt="-";
   $l = new Text($txt,$y+3,$x+41);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
   $txt="-";
   $l = new Text($txt,$y+3,$x+54);
   $l->SetFont(FF_LUXIS,FS_NORMAL,48);
   $l->Stroke($g->img);
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

   if ($cambiar == '22' || $cambiar == '30')//PROTESIS FIJA POR CAMBIAR
   {
    $txt="|";
    $l = new Text($txt,$y+35,$x+78);
    $l->SetFont(FF_LUXIS,FS_NORMAL,13);
    $l->Stroke($g->img);
   }
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
  function Nucleo_Poste($g,$y,$x,$cambiar)
  {
    //lados de la figura
    //lado izquierdo
    $g->img->Line($y+20,72+$x,$y+35,25+$x);
    //base del traigulo
    $g->img->Line($y+20,72+$x,$y+51,72+$x);
    //lado derecho
    $g->img->Line($y+35,25+$x,$y+51,72+$x);

    if ($cambiar=='24')//Nucleo o Poste Por Cambiar
    {
      $txt="|";
      $l = new Text($txt,$y+35,$x+78);
      $l->SetFont(FF_LUXIS,FS_NORMAL,13);
      $l->Stroke($g->img);
    }
    return $g;
  }

  //Endodoncia Realizada
  function Endodoncia_Realizada($g,$y,$x,$Ndiente)
  {
    //TRIANGULO FILLED NEGRO(ARCO)
    $g->img->SetColor('black');
    $g->img->filledArc($y+36,2+$x,65,65,422,-240);
    //ETIQUETA
    $txt=$Ndiente;
    $l = new Text($txt,$y+29,$x+10);
    $l->SetColor('white');
    $l->SetFont(FF_LUXIS,FS_NORMAL,8);
    $l->Stroke($g->img);
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
   $g->img->Circle($y+35,50+$x,25.7);
   $g->img->Circle($y+35,50+$x,25);
   $g->img->Circle($y+35,50+$x,24);
   $txt="|";
   $t = new Text($txt,$y+35,$x+78);
   $t->SetFont(FF_LUXIS,FS_BOLD,13);
  }else
  //CORONA BUENA
  if ($problema=='9')
  {
   $g->img->Circle($y+35,50+$x,25.7);
   $g->img->Circle($y+35,50+$x,25);
   $g->img->Circle($y+35,50+$x,24);
   $txt="";
   $t = new Text($txt,$y+42,12+$x);
   $t->SetFont(FF_LUXIS,FS_BOLD,10);
  }
   $t->Stroke($g->img);
   return $g;
 }

 //MUESTRA CADA UNA DE LA COMBINACIONES POSIBLES PARA CADA DIENTE O PIEZA
 function DrawImage($g,$y,$x,$y1,$x1,$val,$cuadrante)
 {
	if ($cuadrante==1 || $cuadrante==5)
	{
		$c1='8'; //8 CERVICAL VESTIBULAR
		$c2='9'; //9 CERVICAL PALATINO
		$c3='1'; //1 VESTIBULAR
		$c4='4'; //4 MESIAL
		$c5='2'; //2 PALATINO
		$c6='5'; //5 DISTAL
		$c7='6'; //6 OCLUSAL
	}
	else
	if ($cuadrante==2 || $cuadrante==6)
	{
		$c1='8'; //8 CERVICAL VESTIBULAR
		$c2='9'; //9 CERVICAL PALATINO
		$c3='1'; //1 VESTIBULAR
		$c4='5'; //5 DISTAL
		$c5='2'; //2 PALATINO
		$c6='4'; //4 MESIAL
		$c7='6'; //6 OCLUSAL
	}
	else
	if ($cuadrante==4 || $cuadrante==8)
	{
		$c1='10';//9 CERVICAL PALATINO
		$c2='8';//8 CERVICAL VESTIBULAR
		$c3='3';//2 PALATINO
		$c4='4';//4 MESIAL
		$c5='1';//1 VESTIBULAR
		$c6='5';//5 DISTAL
		$c7='6';//6 OCLUSAL
	}
  else
 if ($cuadrante==3 || $cuadrante==7)
	{
		$c1='10';//9 CERVICAL PALATINO
		$c2='8';//8 CERVICAL VESTIBULAR
		$c3='3';//2 PALATINO
		$c4='5';//5 DISTAL
		$c5='1';//1 VESTIBULAR
		$c6='4';//4 MESIAL
		$c7='6';//6 OCLUSAL
	}

    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c1)
    {			//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
      if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
        $g=COT_S($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar))
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_S($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_S($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_S($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar,
      {                                                             //por cambiar      //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_S($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES_INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_S($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30

    //region dos
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c2)
    {			//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
      if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
        $g=COT_I($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_I($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25') //25 amalgama por cambiar
       {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_I($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_I($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar,
      {                                                         // por cambiar        //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_I($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES_INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_I($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30


    //region tres
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c3)
    {			//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
      if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
      //caries superior interior
        $g=COT_SI($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar)
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_SI($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_SI($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_SI($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar, por cambiar
      {                                                           //por cambiar        //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_SI($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES_INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_SI($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30

    //SUPERFICIE cuatro
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c4)
    {
      //función que dibuja caries obturación temporal
      //lateral derecha
    	//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
      if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
        $g=COT_LD($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar))
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_LD($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_LD($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_LD($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar,
      {                                                         // por cambiar        //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_LD($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES_INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_LD($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30

      //region 5
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c5)
    {			//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
      if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
        $g=COT_II($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar))
      //resina inferior interna
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_II($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_II($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_II($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar,
      {                                                              // por cambiar   //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_II($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_II($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30

      //region 6
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c6)
 	  {			//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
     if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
        $g=COT_LI($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar))
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_LI($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25')
      //amalgama lateral izquierda
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_LI($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_LI($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar, por cambiar
      {                                                          // por cambiar        //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_LI($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES_INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_LI($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30

      //region 7
    //simbolos que se dibujan por superficie
    if ($val[$x1][$y1+1]==$c7 || $val[$x1][$y1+1]=='7')
    {			//CARIES Y/O OBTURACION TEMPORAL O SUPERFICIE FRACTURADA
     if ($val[$x1][$y1+2]=='14' || $val[$x1][$y1+2]=='32')
      {
        $g=COT_C($g,$y,$x);
        $g=DibujarSano($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='16' || $val[$x1][$y1+2]=='26')//26 RESINA, IONOÓMERO, CERÓMERO por cambiar))
      {
        $g=DibujarSano($g,$y,$x);
        $g=Resina_C($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='15' || $val[$x1][$y1+2]=='25')
      {
        $g=DibujarSano($g,$y,$x);
        $g=Amalgama_C($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='21')// ANTES Restauración desadaptada if ($val[$x1][$y1+2]=='20')
      {                          //ahora CUELLO DESCUBIERTO =='21'
        $g=DibujarSano($g,$y,$x);
        $g=Restauracion_Desadaptada_C($g,$y,$x);
      }
      if ($val[$x1][$y1+2]=='17' || $val[$x1][$y1+2]=='18' || $val[$x1][$y1+2]=='27')//superficie por sellar, por cambiar
      {                                                           //por cambiar       //superficie sellada
        $g=DibujarSano($g,$y,$x);
        $g=Sellada_C($g,$y,$x,$val[$x1][$y1+2]);
      }
      if ($val[$x1][$y1+2]=='19' || $val[$x1][$y1+2]=='20' || $val[$x1][$y1+2]=='28') //INCRUSTACION, INCRUSTACION POR CAMBIAR
      {                                                         //CARIES_INCIPIENTE
        $g=DibujarSano($g,$y,$x);
        $g=Incrustacion_C($g,$y,$x,$val[$x1][$y1+2]); //EN LA FUNCION DE INCRUSTACIÓN
      }                                               //SE INSERTARÁ CARIES INCIPIENTE
    }                                                 //PROBLEMA 30


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
      if ($val[$x1][$y1+2]=='2' OR $val[$x1][$y1+2]=='31')//DIENTE SIN ERUPCIONAR
        {												// O DIENTE SIN ERUPCIONAR POR AGENESIA
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
      if ($val[$x1][$y1+2]=='12' || $val[$x1][$y1+2]=='23') //Protesis_Removible_Por_Cambiar
        {                             //Protesis_Removible
        $g=DibujarSano($g,$y,$x);
        $g=Protesis_Removible($g,$y,$x,$val[$x1][$y1+2]);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='11' || $val[$x1][$y1+2]=='22' || $val[$x1][$y1+2]=='29' || $val[$x1][$y1+2]=='30')//Protesis fija o protesisis fija por cambiar
        {																										// o Protesis fija PILAR  o PROTESIS FIJA PILAR POR CAMBIAR
        $g=DibujarSano($g,$y,$x);
        $g=Protesis_fija($g,$y,$x,$val[$x1][$y1+2]);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='6')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Endodoncia_Por_Realizar($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='13' || $val[$x1][$y1+2]=='24')//Nucleo_Poste
        {                             //Nucleo_Poste_Por_Cambiar
        $g=DibujarSano($g,$y,$x);
        $g=Nucleo_Poste($g,$y,$x,$val[$x1][$y1+2]);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='7')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Endodoncia_Realizada($g,$y,$x,$val[$x1][$y1]);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='17')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Superficie_Sellada($g,$y,$x);
        $y+=50;
        }
      if ($val[$x1][$y1+2]=='16')//falta aclarar el caso-NO SE USA
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
      if ($val[$x1][$y1+2]=='14')
        {
        $g=DibujarSano($g,$y,$x);
        $g=Diente_Caries($g,$y,$x);
        $y+=50;
        }
    }//fin if de la superficie 11
  return $g;
 }

//CARIES TOTAL
 function Diente_Caries($g,$y,$x)
 {
   //arco superior interior
  $g->img->SetColor('black');
  $g->img->filledArc($y+35,$x+50,35,35,-132,-48);
  //contorno circulo pe
    //arco inferior inferior
  $g->img->filledArc($y+35,$x+50,35,35,48,130);
  //contorno de los circulos

  //arco lateral derecho
  $g->img->filledArc($y+35,$x+50,35,35,312,-310);
  //contorno de los circulos

  //arco lateral izquierdo
  $g->img->filledArc($y+35,$x+50,35,35,120,-135);
  //contorno de los circulos

  $g->img->FilledArc($y+35,50+$x,55,55,-135,-45);
  $g->img->Circle($y+35,50+$x,16);

  $g->img->FilledArc($y+35,50+$x,55,55,48,135);

  //CIRCULO CENTRAL
  $g->img->filledCircle($y+35,50+$x,6);
  return $g;
 }

  //ETIQUETAS
  function Etiquetas($g,$n)
  {
    $i=0;
    $y=0;
    $x=0;
    $k=0;
    $j=0;
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
        if ($i<=4 and $label>=51 and $n[$l]==10 )
        {
         $txt=(string) $label;
         $t = new Text($txt,28+$y,$x+10);
         $label=$label-1;
        }
        else
        if ($label2>=61 and $label2<=65 and $n[$l]==10 )
        {
         $txt=(string) $label2;
         $t = new Text($txt,28+$y,$x+10);
         $label2=$label2+1;
        }
        else
        if ($n[$l]==16 and $label3>=11 and $label3<=18 and $i<8 )
        {
          $txt=(string) $label3;
          $t = new Text($txt,28+$y,$x+10);
          $label3=$label3-1;
        }
        else
        if ($n[$l]==16 and $label4>=21 and $label4<=28 and $i>=8 and $i<=15)
        {
         $txt=(string) $label4;
         $t = new Text($txt,28+$y,$x+10);
         $label4=$label4+1;
        }
        else
        if ($n[$l]==16 and $i<10 and $label5>=41 and $label5<=48)
        {
         $txt=(string) $label5;
         $t = new Text($txt,28+$y,$x+10);
         $label5=$label5-1;
        }
        else
        if ($n[$l]==16 and $label6>=31 and $label6<=38)
        {
         $txt=(string) $label6;
         $t = new Text($txt,28+$y,$x+10);
         $label6=$label6+1;
        }
        else
        if ($n[$l]==10 and $label7>=81 and $label7<=85)
        {
         $txt=(string) $label7;
         $t = new Text($txt,30+$y,$x+10);
         $label7=$label7-1;
        }
        else
        if ($n[$l]==10 and $label8>=71 and $label8<=75)
        {
         $txt=(string) $label8;
         $t = new Text($txt,30+$y,$x+10);
         $label8=$label8+1;
        }

        $i++;
        $y+=50;
        $t->SetFont(FF_LUXIS,FS_NORMAL,8);
        $t->Stroke($g->img);
     }
       $l++;

      if ($n[$l]==16)
       {
          $y=0;
          $x=200;
       }
       else
       if ($n[$l]==10)
         {
            $y=150;
            $x+=10;
         }
        $x+=85;
        $i=0;
     }
    return $g;
  }

  function arcos($g)
  {
  //arco superior
//   $g->img->SetColor('white');
  $g->img->filledArc(35,150,35,35,-132,-48);
  //arco inferior inferior
  $g->img->filledArc(35,150,35,35,48,130);
  $g->img->SetColor('black');
  //arco lateral izquierdo
  $g->img->filledArc(35,150,0,0,120,-135);
  //arco lateral derecho
  $g->img->filledArc(35,150,5,5,312,-310);
  //contorno de los circulos
  $g->img->SetColor('white');
  $g->img->filledCircle(35,150,6);
  return $g;
  }
?>
