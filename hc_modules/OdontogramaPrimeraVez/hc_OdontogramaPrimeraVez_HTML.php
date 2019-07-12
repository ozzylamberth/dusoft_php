
<?php

/**
* Submodulo de Odontograma Primera Vez.
*
* Submodulo para manejar el odontograma del paciente, en su primera atencion medica
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_OdontogramaPrimeraVez_HTML.php,v 1.48 2008/06/10 14:41:40 cahenao Exp $
*/

/**
* Odontograma Primera Vez
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de odontograma primera vez.
*/

class OdontogramaPrimeraVez_HTML extends OdontogramaPrimeraVez
{
//USUARIO HIGIENISTA    : ipspasolcb
//PASSWORD                                              : 123456
        function OdontogramaPrimeraVez_HTML()
        {
                $this->OdontogramaPrimeraVez();//constructor del padre
                return true;
        }

        function SetStyle($campo)
        {
          if ($this->frmError[$campo]||$campo=="MensajeError")
                {
                  if ($campo=="MensajeError")
                        {
                          return ("<tr><td align=\"center\" class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
                        }
                        return ("hc_tderror");
                }
                return ("hc_tdlabel");
        }

        //
        function EsquemaCheck()
        {
                //MODIFICACIÓN PARA LAS SUPERFICIES
    //control de etiquetas
    $n[0]=5;
    $n[1]=8;
    $n[2]=5;
    $n[3]=8;
    $n[4]=8;
    $n[5]=5;
    $n[6]=8;
    $n[7]=5;
    
    $i=0;
    $k=0;
    $label=55;
    $label2=61;
    $label3=18;
    $label4=21;
    $label5=48;
    $label6=31;
    $label7=85;
    $label8=71;
    $lim=sizeof($n);
    
    for ($l=0;$l<$lim;)
    {
      while ($i<$n[$l])
      {
        if ($n[$l]==5 AND $label==55 AND $k==0 AND $l==0)
        {

                                        $this->salida.="<TR>";
          $this->salida.="<TD align=\"right\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
        }
        else
        if ($n[$l]==8 AND $label3==0 AND $k==0 AND $l==1)
        {
          $this->salida.= "</TD>";            
          $this->salida.= "<BR><BR><BR>";  
          $label3=1;       
        }
        else
        if ($n[$l]==5 AND $label3==1 AND $label2==61 AND $k==0 AND $l==2)
        {
          $this->salida.= "<TD align=\"left\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
        }
        else
        if ($n[$l]==8 AND $label4==0)
        { 
          $this->salida.= "</TD>";          
          $this->salida.= "</TR>";  
          $label4=1;
        }        
        else
        if ($n[$l]==8 AND $label5==48 AND $label4==1 AND $k==1)
        {
                                        $this->salida.="<TR>";
          $this->salida.="<TD align=\"right\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
          $k=0;
          $label4=2;
        }     
        else
        if ($n[$l]==5 AND $label7==0 AND $label4==2)// 
        { 
          $this->salida.= "</TD>"; 
          $label7=1;   
        }
        else
        if ($label7==1)
        { 
          $this->salida.= "<TD align=\"left\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
          $label7=2;
          $n[$l]=9;         
        }
        else
        if ($label8==0)
        {
          $this->salida.= "</TD>";          
          $this->salida.= "</TR>";          
          $label8=1;   
        }        
        //INICIO ETIQUETAS /CHECK
        if ($n[$l]==5 AND $label>=51 AND $label<=55 AND $k==0 AND $l==0)
        {  
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label."&nbsp;&nbsp;</label>";    
         $label=$label-1;
        }
        else
        if ($n[$l]==5 AND $label>=51 AND $label<=55 AND $k==1 AND $l==0)
        {
                                                if ($label>=51 AND $label<=53)
                                                        $this->salida.= "<input type=checkbox name=\"tipoubic".$label."\">&nbsp;";          
                                                else
                                                        $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label."\">&nbsp;";          
         $label=$label-1;
        } 
        else
        if ($n[$l]==8 AND $label3>=11 AND $label3<=18 AND $k==0 AND $l==1)
        {  
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label3."&nbsp;&nbsp;</label>";    
         $label3=$label3-1;
        }
        else
        if ($n[$l]==8 AND $label3>=11 AND $label3<=18 AND $k==1  AND $l==1)
        {  
                                                if ($label3>=11 AND $label3<=13)
                                                        $this->salida.= "<input type=checkbox name=\"tipoubic".$label3."\">&nbsp;";          
                                                else
                                                        $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label3."\">&nbsp;";          
         $label3=$label3-1;
        }
        else
        if ($n[$l]==5 AND $label2>=61 AND $label2<=65 AND $k==0 AND $l==2)
        {
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label2."&nbsp;&nbsp;</label>";          
         $label2=$label2+1;
        } 
        else
        if ($n[$l]==5 AND $label2>=61 AND $label2<=65 AND $k==1 AND $l==2)
        {
                                                if ($label2>=61 AND $label2<=63)
                                                        $this->salida.= "<input type=checkbox name=\"tipoubic".$label2."\">&nbsp;";          
                                                else
                                                        $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label2."\">&nbsp;";          
         $label2=$label2+1;
        } 
        else
        if ($n[$l]==8 AND $label4>=21 AND $label4<=28 AND $k==0 AND $l==3)
        {
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label4."&nbsp;&nbsp;</label>";          
         $label4=$label4+1;
        } 
        else
        if ($n[$l]==8 AND $label4>=21 AND $label4<=28 AND $k==1 AND $l==3)
        {
                                                if ($label4>=21 AND $label4<=23)
                                                        $this->salida.= "<input type=checkbox name=\"tipoubic".$label4."\">&nbsp;";          
                                                else
                                                        $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label4."\">&nbsp;";          
         $label4=$label4+1;
        }       
        else
        if ($n[$l]==8 AND $label5>=41 and $label5<=48 AND $k==0 AND $l==4)
        {      
          $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label5."&nbsp;&nbsp;</label>";          
          $label5=$label5-1;      
        }
        else 
        if ($n[$l]==8 AND $label5>=41 and $label5<=48 AND $k==1 AND $l==4)
        {    
          $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label5."\">&nbsp;";           
          $label5=$label5-1;
        }
        else
        if ($n[$l]==5 AND $label7>=81 AND $label7<=85 AND $k==1 AND $l==5)
        {
         $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label7."&nbsp;&nbsp;</label>";          
         $label7=$label7-1;
        } 
        else
        if ($n[$l]==5 AND $label7>=81 AND $label7<=85 AND $k==0 AND $l==5)
        {
         $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label7."\">&nbsp;";          
         $label7=$label7-1;
        } 
        else
        if ($label6>=31 and $label6<=38 AND $k==1 AND $label7==2)
        {      
          $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label6."&nbsp;&nbsp;</label>";          
          $label6=$label6+1;      
        }
        else 
        if ($label6>=31 and $label6<=38 AND $k==0  AND $label7==2)
        {    
          $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label6."\">&nbsp;";           
          $label6=$label6+1;
        }
        else
        if ($label8>=71 and $label8<=75 AND $k==0 AND $label7==2)
        {     
          $this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label8."&nbsp;&nbsp;</label>";          
          $label8=$label8+1;      
        }
        else 
        if ($label8>=71 and $label8<=75 AND $k==1  AND $label7==2)
        {    
          $this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label8."\">&nbsp;";           
          $label8=$label8+1;
        }
          $i++;          
          //SALTOS DE LINEA
          if ($n[$l]==5 AND $label==50 AND $k==0)
          {
            $this->salida.= "<BR>";     
            $i=0;      
            $k=1;
            $label=55;    
          }
          else
          if ($n[$l]==5 AND $label==50 AND $k==1)
          {
            $this->salida.= "<BR>";     
            $label=0;   
            $k=0;               
          }
          else
          if ($n[$l]==8 AND $label3==10 AND $k==0 AND $l==1)
          { 
            $this->salida.= "<BR>";     
            $i=0;      
            $k=1;
            $label3=18;  
                               
          }
          else
          if ($n[$l]==8 AND $label3==10 AND $k==1)
          { 
            $this->salida.= "<BR>";     
            $i=0;      
            $label3=0;        
            $k=0;  
          }
          else
          if ($n[$l]==5 AND $label2==66 AND $k==0)
          {
            $this->salida.= "<BR>";     
            $i=0;      
            $k=1;
            $label2=61;    
          }
          else
          if ($n[$l]==5 AND $label2==66 AND $k==1)
          {
            $this->salida.= "<BR>";     
            $label2=0;   
            $k=0;                        
          }                    
          else          
          if ($n[$l]==8 AND $label4==29 AND $k==0)          
          {
            $this->salida.= "<BR>";           
            $i=0;      
            $k=1;
            $label4=21;        
          }
          else
          if ($n[$l]==8 AND $label4==29 AND $k==1)
          { 
            $this->salida.= "<BR>";           
            $i=0;      
            $label4=0;
          }
          else
          if ($n[$l]==8 AND $label5==40 AND $k==0)
          { 
            $this->salida.= "<BR>";   
            $k=1;
            $i=0;      
            $label5=48;     
          }
          else
          if ($n[$l]==5 AND $label7==80 AND $k==1) 
          {
            $this->salida.= "<BR>";     
            $i=0;      
            $k=0;
            $label7=85;    
          }
          else
          if ($n[$l]==5 AND $label7==80 AND $k==0)
          {
            $label7=0;   
          }                    
          else
          if ($label7==2 AND $label6==39 AND $k==1)
          {
            $this->salida.= "<BR>";   
            $i=0;      
            $k=0;
            $label6=31;        
          }
          else
          if ($label7==2  AND $label6==39 AND $k==0)
          {
            $this->salida.= "<BR>";   
            $i=0;      
            $label6=0;        
          }
          else
          if ($label8==76 AND $k==0)
          {
            $this->salida.= "<BR>";   
            $i=0;      
            $k=1;
            $label8=71;        
          }
          else
          if ($label8==76 AND $k==1)
          {  
            $this->salida.= "<BR>";   
            $i=0;      
            $label8=0;        
          }
       }
       $l++;
       $i=0;
     }   
    //FIN CONTROL ETIQUETAS
                //FIN MODIFICACIÓN PARA LAS SUPERFICIES
                return true;
        }
        //
				
//INICIO CHECKS
				function ChecksOdontograma()
				{
                //MODIFICACIÓN PARA LAS SUPERFICIES
								//control de etiquetas
								$n[0]=5;
								$n[1]=8;
								$n[2]=5;
								$n[3]=8;
								$n[4]=8;
								$n[5]=5;
								$n[6]=8;
								$n[7]=5;
								
								$i=0;
								$k=0;
								$label=55;
								$label2=61;
								$label3=18;
								$label4=21;
								$label5=48;
								$label6=31;
								$label7=85;
								$label8=71;
								$lim=sizeof($n);
								
								for ($l=0;$l<$lim;)
								{
									while ($i<$n[$l])
									{
										if ($n[$l]==5 AND $label==55 AND $k==0 AND $l==0)
										{
						
											$this->salida.="<TR>";
											$this->salida.="<TD align=\"right\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
										}
										else
										if ($n[$l]==8 AND $label3==0 AND $k==0 AND $l==1)
										{
											$this->salida.= "</TD>";            
											$this->salida.= "<BR><BR><BR>";  
											$label3=1;       
										}
										else
										if ($n[$l]==5 AND $label3==1 AND $label2==61 AND $k==0 AND $l==2)
										{
											$this->salida.= "<TD align=\"left\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
										}
										else
										if ($n[$l]==8 AND $label4==0)
										{ 
											$this->salida.= "</TD>";          
											$this->salida.= "</TR>";  
											$label4=1;
										}        
										else
										if ($n[$l]==8 AND $label5==48 AND $label4==1 AND $k==1)
										{
																										$this->salida.="<TR>";
											$this->salida.="<TD align=\"right\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
											$k=0;
											$label4=2;
										}     
										else
										if ($n[$l]==5 AND $label7==0 AND $label4==2)// 
										{ 
											$this->salida.= "</TD>"; 
											$label7=1;   
										}
										else
										if ($label7==1)
										{ 
											$this->salida.= "<TD align=\"left\" class=\"hc_submodulo_list_oscuro\" width=\"30%\">";          
											$label7=2;
											$n[$l]=9;         
										}
										else
										if ($label8==0)
										{
											$this->salida.= "</TD>";          
											$this->salida.= "</TR>";          
											$label8=1;   
										}        
										//INICIO ETIQUETAS /CHECK
										if ($n[$l]==5 AND $label>=51 AND $label<=55 AND $k==0 AND $l==0)
										{  
										$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label."&nbsp;&nbsp;</label>";    
										$label=$label-1;
										}
										else
										if ($n[$l]==5 AND $label>=51 AND $label<=55 AND $k==1 AND $l==0)
										{
												$checked = '';
												if($_REQUEST["tipoubic".$label])
												{
													$checked = 'checked';
												}         
												if ($label>=51 AND $label<=53)
														$this->salida.= "<input type=checkbox name=\"tipoubic".$label."\" $checked>&nbsp;";          
												else
														//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label."\">&nbsp;"; 
														$this->salida.= "<input type=checkbox name=\"tipoubic".$label."\" $checked>&nbsp;";          
											$label=$label-1;
										} 
										else
										if ($n[$l]==8 AND $label3>=11 AND $label3<=18 AND $k==0 AND $l==1)
										{  
										$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label3."&nbsp;&nbsp;</label>";    
										$label3=$label3-1;
										}
										else
										if ($n[$l]==8 AND $label3>=11 AND $label3<=18 AND $k==1  AND $l==1)
										{  
											$checked = '';
											if($_REQUEST["tipoubic".$label3])
											{
												$checked = 'checked';
											}         
											if ($label3>=11 AND $label3<=13)
														$this->salida.= "<input type=checkbox name=\"tipoubic".$label3."\" $checked>&nbsp;";          
											else
													//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label3."\">&nbsp;";          
												$this->salida.= "<input type=checkbox name=\"tipoubic".$label3."\" $checked>&nbsp;";          	
											$label3=$label3-1;
										}
										else
										if ($n[$l]==5 AND $label2>=61 AND $label2<=65 AND $k==0 AND $l==2)
										{
										$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label2."&nbsp;&nbsp;</label>";          
										$label2=$label2+1;
										} 
										else
										if ($n[$l]==5 AND $label2>=61 AND $label2<=65 AND $k==1 AND $l==2)
										{
											$checked = '';
											if($_REQUEST["tipoubic".$label2])
											{
												$checked = 'checked';
											}         
											if ($label2>=61 AND $label2<=63)
													$this->salida.= "<input type=checkbox name=\"tipoubic".$label2."\" $checked>&nbsp;";          
											else
													//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label2."\">&nbsp;";          
													$this->salida.= "<input type=checkbox name=\"tipoubic".$label2."\" $checked>&nbsp;";          
											$label2=$label2+1;
										} 
										else
										if ($n[$l]==8 AND $label4>=21 AND $label4<=28 AND $k==0 AND $l==3)
										{
										$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label4."&nbsp;&nbsp;</label>";          
										$label4=$label4+1;
										} 
										else
										if ($n[$l]==8 AND $label4>=21 AND $label4<=28 AND $k==1 AND $l==3)
										{
											$checked = '';
											if($_REQUEST["tipoubic".$label4])
											{
												$checked = 'checked';
											}         
											if ($label4>=21 AND $label4<=23)
													$this->salida.= "<input type=checkbox name=\"tipoubic".$label4."\" $checked>&nbsp;";          
											else
													//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label4."\">&nbsp;";          
													$this->salida.= "<input type=checkbox name=\"tipoubic".$label4."\" $checked>&nbsp;";          
											$label4=$label4+1;
										}       
										else
										if ($n[$l]==8 AND $label5>=41 and $label5<=48 AND $k==0 AND $l==4)
										{      
											$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label5."&nbsp;&nbsp;</label>";          
											$label5=$label5-1;      
										}
										else 
										if ($n[$l]==8 AND $label5>=41 and $label5<=48 AND $k==1 AND $l==4)
										{    
											$checked = '';
											if($_REQUEST["tipoubic".$label5])
											{
												$checked = 'checked';
											}         
											//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label5."\">&nbsp;";           
											$this->salida.= "<input type=checkbox name=\"tipoubic".$label5."\" $checked>&nbsp;";           
											$label5=$label5-1;
										}
										else
										if ($n[$l]==5 AND $label7>=81 AND $label7<=85 AND $k==1 AND $l==5)
										{
											$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label7."&nbsp;&nbsp;</label>";          
											$label7=$label7-1;
										} 
										else
										if ($n[$l]==5 AND $label7>=81 AND $label7<=85 AND $k==0 AND $l==5)
										{
											$checked = '';
											if($_REQUEST["tipoubic".$label7])
											{
												$checked = 'checked';
											}         
											//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label7."\">&nbsp;";          
											$this->salida.= "<input type=checkbox name=\"tipoubic".$label7."\" $checked>&nbsp;";          
											$label7=$label7-1;
										} 
										else
										if ($label6>=31 and $label6<=38 AND $k==1 AND $label7==2)
										{      
											$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label6."&nbsp;&nbsp;</label>";          
											$label6=$label6+1;      
										}
										else 
										if ($label6>=31 and $label6<=38 AND $k==0  AND $label7==2)
										{    
											$checked = '';
											if($_REQUEST["tipoubic".$label6])
											{
												$checked = 'checked';
											}         
											//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label6."\">&nbsp;";           
											$this->salida.= "<input type=checkbox name=\"tipoubic".$label6."\" $checked>&nbsp;";           
											$label6=$label6+1;
										}
										else
										if ($label8>=71 and $label8<=75 AND $k==0 AND $label7==2)
										{     
											$this->salida.= "<label class=\"label\">&nbsp;&nbsp;".$label8."&nbsp;&nbsp;</label>";          
											$label8=$label8+1;      
										}
										else 
										if ($label8>=71 and $label8<=75 AND $k==1  AND $label7==2)
										{    
											$checked = '';
											if($_REQUEST["tipoubic".$label8])
											{
												$checked = 'checked';
											}         
											//$this->salida.= "<input disabled=true type=checkbox name=\"tipoubic".$label8."\">&nbsp;";           
											$this->salida.= "<input type=checkbox name=\"tipoubic".$label8."\" $checked>&nbsp;";           
											$label8=$label8+1;
										}
											$i++;          
											//SALTOS DE LINEA
											if ($n[$l]==5 AND $label==50 AND $k==0)
											{
												$this->salida.= "<BR>";     
												$i=0;      
												$k=1;
												$label=55;    
											}
											else
											if ($n[$l]==5 AND $label==50 AND $k==1)
											{
												$this->salida.= "<BR>";     
												$label=0;   
												$k=0;               
											}
											else
											if ($n[$l]==8 AND $label3==10 AND $k==0 AND $l==1)
											{ 
												$this->salida.= "<BR>";     
												$i=0;      
												$k=1;
												$label3=18;  
																					
											}
											else
											if ($n[$l]==8 AND $label3==10 AND $k==1)
											{ 
												$this->salida.= "<BR>";     
												$i=0;      
												$label3=0;        
												$k=0;  
											}
											else
											if ($n[$l]==5 AND $label2==66 AND $k==0)
											{
												$this->salida.= "<BR>";     
												$i=0;      
												$k=1;
												$label2=61;    
											}
											else
											if ($n[$l]==5 AND $label2==66 AND $k==1)
											{
												$this->salida.= "<BR>";     
												$label2=0;   
												$k=0;                        
											}                    
											else          
											if ($n[$l]==8 AND $label4==29 AND $k==0)          
											{
												$this->salida.= "<BR>";           
												$i=0;      
												$k=1;
												$label4=21;        
											}
											else
											if ($n[$l]==8 AND $label4==29 AND $k==1)
											{ 
												$this->salida.= "<BR>";           
												$i=0;      
												$label4=0;
											}
											else
											if ($n[$l]==8 AND $label5==40 AND $k==0)
											{ 
												$this->salida.= "<BR>";   
												$k=1;
												$i=0;      
												$label5=48;     
											}
											else
											if ($n[$l]==5 AND $label7==80 AND $k==1) 
											{
												$this->salida.= "<BR>";     
												$i=0;      
												$k=0;
												$label7=85;    
											}
											else
											if ($n[$l]==5 AND $label7==80 AND $k==0)
											{
												$label7=0;   
											}                    
											else
											if ($label7==2 AND $label6==39 AND $k==1)
											{
												$this->salida.= "<BR>";   
												$i=0;      
												$k=0;
												$label6=31;        
											}
											else
											if ($label7==2  AND $label6==39 AND $k==0)
											{
												$this->salida.= "<BR>";   
												$i=0;      
												$label6=0;        
											}
											else
											if ($label8==76 AND $k==0)
											{
												$this->salida.= "<BR>";   
												$i=0;      
												$k=1;
												$label8=71;        
											}
											else
											if ($label8==76 AND $k==1)
											{  
												$this->salida.= "<BR>";   
												$i=0;      
												$label8=0;        
											}
									}
									$l++;
									$i=0;
								}   
								//FIN CONTROL ETIQUETAS
								//FIN MODIFICACIÓN PARA LAS SUPERFICIES
								return true;
				}
//FIN CHECKS

		function TiposSuperficies($pfj)
		{		
				$this->salida.="<table  width=\"100%\" border=\"0\" align=\"center\" class=\"NORMAL_10\">";
				$this->salida.="<tr>";
				$this->salida.="<td>";
				$checked='';
				if($_REQUEST["1".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=1".$pfj." value=1 $checked>VESTIBULAR<br>\n";
				$checked='';
				if($_REQUEST["2".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=2".$pfj." value=2 $checked>PALATINO<br>\n";
				$checked='';
				if($_REQUEST["3".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=3".$pfj." value=3 $checked>LINGUAL<br>\n";
				$checked='';
				if($_REQUEST["4".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=4".$pfj." value=4 $checked>MESIAL<br>\n";
				$checked='';
				if($_REQUEST["5".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=5".$pfj." value=5 $checked>DISTAL<br>\n";
				$checked='';
				if($_REQUEST["6".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=6".$pfj." value=6 $checked>OCLUSAL<br>\n";
				$this->salida.="</td>";
				$this->salida.="<td>";
				$checked='';
				if($_REQUEST["7".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=7".$pfj." value=7 $checked>INCISAL<br>\n";
				$checked='';
				if($_REQUEST["8".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=8".$pfj." value=8 $checked>CERVICAL VESTIBULAR<br>\n";
				$checked='';
				if($_REQUEST["9".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=9".$pfj." value=9 $checked> CERVICAL PALATINO<br>\n";
				$checked='';
				if($_REQUEST["10".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=10".$pfj." value=10 $checked> CERVICAL LINGUAL<br>\n";
				$checked='';
				if($_REQUEST["0".$pfj])
				{
					$checked='checked';
				}
				$this->salida.="<input type=checkbox name=0".$pfj." value=11 $checked>SUPERFICIE TOTAL<br>\n";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				return true;
		}

//FRMFORMA MODIFICADA CON EL NUEVO ESQUEMA DE SELECCIÓN DE DIENTES
        function frmForma()//Desde esta funcion es de JORGE AVILA
        {
                                
                UNSET($_SESSION['PRIMERA_VEZ']['ODONTOGRAMA']);
                $this->salida =ThemeAbrirTablaSubModulo('ODONTOGRAMA DE PRIMERA VEZ');  
                $mostrar ="<script language='javascript'>\n";                   
                $mostrar.="     function abrirVentanaClass(url){\n";
                $mostrar.="     var str = 'width=930,height=420,resizable=no,status=no,scrollbars=no,top=100,left=50';\n";
                $mostrar.="     var rems = window.open(url,'',str);\n";
                $mostrar.="     if (rems != null) {\n";
                $mostrar.="             if (rems.opener == null) {\n";
                $mostrar.="                     rems.opener = self;\n";
                $mostrar.="             }\n";
                $mostrar.="     }\n";
                $mostrar.="     }\n";
                $mostrar.="</script>\n";
                $this->salida.=$mostrar;        
                                
                $pfj=$this->frmPrefijo;
                $odontograma=$this->BuscarOdontogramaForma();
                //echo '<pre>'; print_r($odontograma,false); echo '</pre>'; exit;
                $valoracion=$this->BuscarEnviarPintarMuelas();//presentes
                if($odontograma===false)
                {
                        return false;
                }
                
                $ipboleary=$this->BuscarIPBOlearyControl();
                if($ipboleary<>NULL)
                {
                        if($this->frmError["MensajeError"]==NULL)
                        {
                                $this->frmError["MensajeError"]="SE ENCONTRÓ UN DIAGRAMA DE IPBOLEARY RELACIONADO AL PACIENTE
                                <br>POR FAVOR, TENGA ENCUENTA ESTO SI VA A REALIZAR UN CAMBIO EN EL ODONTOGRAMA";
                        }
                        else
                        {
                                $this->frmError["MensajeError"].="<br>SE ENCONTRÓ UN DIAGRAMA DE IPBOLEARY RELACIONADO AL PACIENTE
                                <br>POR FAVOR, TENGA ENCUENTA ESTO SI VA A REALIZAR UN CAMBIO EN EL ODONTOGRAMA";
                        }
                }
                if($this->primeravez==1)
                {
                        if($this->frmError["MensajeError"]==NULL)
                        {
                                $this->frmError["MensajeError"]="NO SE PUEDE CERRAR LA HC<br>FALTAN DIENTES POR DIAGNÓSTICO";
                        }
                        else
                        {
                                $this->frmError["MensajeError"]="<br>NO SE PUEDE CERRAR LA HC<br>FALTAN DIENTES POR DIAGNÓSTICO";
                        }
                }
                if($odontograma==NULL)
                {
                    if($this->frmError["MensajeError"]==NULL)
                    {
                            $this->frmError["MensajeError"]="NO SE ENCONTRÓ UN ODONTOGRAMA ACTIVO";
                    }
                    else
                    {
                            $this->frmError["MensajeError"].="<br>NO SE ENCONTRÓ UN ODONTOGRAMA ACTIVO";
                    }
                    //BUSCAR E INSERTAR EL NUEVO ODONTOGRAMA DE SEIS
                    //MESES
                    $odonselcop=$this->BuscarOdontogramaFormaViejo();
                    if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
                    {
                            $valoracion=$this->BuscarEnviarPintarMuelasViejo($odonselcop[0]['hc_odontograma_primera_vez_id']);
                            $this->frmError["MensajeError"].="<br>PERO SI SE ENCONTRÓ UN ODONTOGRAMA EN EL HISTORIAL";
                    }
                    //cambio dar
                    $odontograma=$this->BuscarOdontogramaForma();
                    $valoracion=$this->BuscarEnviarPintarMuelasControl();
                }
								elseif($this->seismeses==1)
								{
//echo '<pre>';
                    $valoracion=$this->BuscarEnviarPintarMuelasControl();
//echo '</pre>';
								}
                //CASO ODONTOGRAMA DE CONTROL
                if(!empty($odontograma))
                {
                    //**********************************
                    //CONTROL POSTERIOR A LOS SEIS MESES
                    //**********************************
                    $despuesseismeses=$this->ConrtolPosteriorSeisMeses();
                    //**************************************
                    //FIN CONTROL POSTERIOR A LOS SEIS MESES
                    //**************************************
                    
                    //PRIMERA VEZ
                    $odonto = $this->UltimoOdnotogramaPrimeraVezInactivo();
                    if(!empty($odonto))
                    {
                                    $_SESSION['PRIMERA_VEZ']['ODONTOGRAMA']=$odonto;
                                    IncludeLib("jpgraph/Odontograma_graphic");
                                    $this->salida.="<table border=\"0\" width=\"80%\" align=\"center\" class=\"label_error\">";
                                    $this->salida.="<tr>";
                                    //PRIMERA VEZ 
                                    if ($despuesseismeses == 0)
                                    {
                                        $valoracion1 = $this->BuscarEnviarPintarMuelasViejo($odonto);
                                        $randon1 = $this->ingreso._.rand();                     
                                        $rutaOPH=Odontograma($valoracion1,$randon1,1);
                                        $this->salida.="<td align=\"center\"><a class=\"label\" href=\"javascript:abrirVentanaClass('$rutaOPH')\">ULTIMO ODONTOGRAMA DE PRIMERA VEZ</a></td>";
                                    }
                                    else
                                    {
                                       $this->salida.="<td align=\"center\"><label class=\"label_mark\" >CITA POSTERIOR A SEIS MESES</label></td>";
                                    }   
                                    //TRATAMIENTO
                                    $trata = $this->UltimoOdnotogramaTratamientoInactivo();
																		if(!empty($trata))
																		{			//SI LA CITA NO ES POSTERIOR A LOS SEIS MESES
																				if ($despuesseismeses == 0)
																				{
																						$valoracion2 = $this->BuscarEnviarPintarMuelasTratamiento($trata,$odonto);                              
																						$fecha_registro='';
																				}
																				else
																				//SI LA CITA ES POSTERIOR A LOS SEIS MESES
																				{
																						$valoracion2 = $this->BuscarEnviarPintarMuelas2();                              
																						$fecha_registro='-&nbsp;'.$valoracion2[fecha];
																				}
																						$randon2 = $this->ingreso._.rand();             
																						$rutaOPT=Odontograma($valoracion2,$randon2,1);
																						$this->salida.="<td align=\"center\"><a class=\"label\" href=\"javascript:abrirVentanaClass('$rutaOPT')\">ODONTOGRAMA TRATAMIENTO ÚLTIMO CONTROL  ".$fecha_registro."</a></td>";
																		}
                                    $this->salida.="</tr>";
                                    $this->salida.="</table>";      
                    }
                }
                
                $randon=$this->ingreso._.rand();//$randon=rand();
                IncludeLib("jpgraph/Odontograma_graphic");
                $RutaImg=Odontograma($valoracion,$randon,1);
//              $this->salida =ThemeAbrirTablaSubModulo('ODONTOGRAMA DE PRIMERA VEZ');
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar','trata'=>$trata));
/*                $mostrar1 ="<script language='javascript'>\n";
                $mostrar1.="    function BuscarSuperficies(valor,prefijo,frm){\n";
                $mostrar1.="    document.getElementById('div1').innerHTML ='<input type=checkbox name=0'+prefijo+' value=11>SUPERFICIE TOTAL<br>';\n";
                $mostrar1.="    if((valor>=11 && valor<=18 || valor>=51 && valor<=55) || (valor>=21 && valor<=28 || valor>=61 && valor<=65)){\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=2>PALATINO<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
                $mostrar1.="            if((valor>=11 && valor<=13 || valor>=51 && valor<=53) || (valor>=21 && valor<=23 || valor>=61 && valor<=63)){\n";
                $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
                $mostrar1.="            }\n";
                $mostrar1.="            else{\n";
                $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
                $mostrar1.="            }\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=9> CERVICAL PALATINO<br>';\n";
                $mostrar1.="    }\n";
                $mostrar1.="    else if((valor>=31 && valor<=38 || valor>=71 && valor<=75) || (valor>=41 && valor<=48 || valor>=81 && valor<=85)){\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=3>LINGUAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
                $mostrar1.="            if((valor>=31 && valor<=33 || valor>=71 && valor<=73) || (valor>=41 && valor<=43 || valor>=81 && valor<=83)){\n";
                $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
                $mostrar1.="            }\n";
                $mostrar1.="            else{\n";
                $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
                $mostrar1.="            }\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=10> CERVICAL LINGUAL<br>';\n";
                $mostrar1.="    }\n";
//
                $mostrar1.="    if(valor==11){alert('cc');\n";
                $mostrar1.="                for(i=0;i<frm.elements.length;i++){\n";
                $mostrar1.="                        if(frm.elements[i].type=='checkbox'){\n";
                $mostrar1.="                                if(frm.elements[i].name=='tipoubic14' || frm.elements[i].name=='tipoubic15' || frm.elements[i].name=='tipoubic16' || frm.elements[i].name=='tipoubic17' || frm.elements[i].name=='tipoubic18'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic24' || frm.elements[i].name=='tipoubic25' || frm.elements[i].name=='tipoubic26' || frm.elements[i].name=='tipoubic27' || frm.elements[i].name=='tipoubic28'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic54' || frm.elements[i].name=='tipoubic55'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic64' || frm.elements[i].name=='tipoubic65'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic31' || frm.elements[i].name=='tipoubic32' || frm.elements[i].name=='tipoubic33' || frm.elements[i].name=='tipoubic34' || frm.elements[i].name=='tipoubic35' || frm.elements[i].name=='tipoubic36' || frm.elements[i].name=='tipoubic37' || frm.elements[i].name=='tipoubic38'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic41' || frm.elements[i].name=='tipoubic42' || frm.elements[i].name=='tipoubic43' || frm.elements[i].name=='tipoubic44' || frm.elements[i].name=='tipoubic45' || frm.elements[i].name=='tipoubic46' || frm.elements[i].name=='tipoubic47' || frm.elements[i].name=='tipoubic48'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic71' || frm.elements[i].name=='tipoubic72' || frm.elements[i].name=='tipoubic73' || frm.elements[i].name=='tipoubic74' || frm.elements[i].name=='tipoubic75'\n";
                $mostrar1.="                                            || frm.elements[i].name=='tipoubic81' || frm.elements[i].name=='tipoubic82' || frm.elements[i].name=='tipoubic83' || frm.elements[i].name=='tipoubic84' || frm.elements[i].name=='tipoubic85'){\n";
                $mostrar1.="                                            if(frm.elements[i].checked==true){\n";
                $mostrar1.="                                                    frm.elements[i].checked=false;\n";
                $mostrar1.="                                                    }\n";
                $mostrar1.="           frm.elements[i].disabled=true\n";
                $mostrar1.="        }\n";
                $mostrar1.="                                else{\n";
                $mostrar1.="                                             frm.elements[i].disabled=false\n";
                $mostrar1.="                                                }\n";
                $mostrar1.="    }\n";
                $mostrar1.="   }\n";
                $mostrar1.="    }\n";
                $mostrar1.="    else{\n";
                $mostrar1.="         if(valor==31){\n";
                $mostrar1.="        for(i=0;i<frm.elements.length;i++){";
                $mostrar1.="          if(frm.elements[i].type=='checkbox'){";
                $mostrar1.="            if(frm.elements[i].name=='tipoubic34' || frm.elements[i].name=='tipoubic35' || frm.elements[i].name=='tipoubic36' || frm.elements[i].name=='tipoubic37' || frm.elements[i].name=='tipoubic38'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic44' || frm.elements[i].name=='tipoubic45' || frm.elements[i].name=='tipoubic46' || frm.elements[i].name=='tipoubic47' || frm.elements[i].name=='tipoubic48'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic74' || frm.elements[i].name=='tipoubic75'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic84' || frm.elements[i].name=='tipoubic85'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic11' || frm.elements[i].name=='tipoubic12' || frm.elements[i].name=='tipoubic13' || frm.elements[i].name=='tipoubic14' || frm.elements[i].name=='tipoubic15' || frm.elements[i].name=='tipoubic16' || frm.elements[i].name=='tipoubic17' || frm.elements[i].name=='tipoubic18'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic21' || frm.elements[i].name=='tipoubic22' || frm.elements[i].name=='tipoubic23' || frm.elements[i].name=='tipoubic24' || frm.elements[i].name=='tipoubic25' || frm.elements[i].name=='tipoubic26' || frm.elements[i].name=='tipoubic27' || frm.elements[i].name=='tipoubic28'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic51' || frm.elements[i].name=='tipoubic52' || frm.elements[i].name=='tipoubic53' || frm.elements[i].name=='tipoubic54' || frm.elements[i].name=='tipoubic55'\n";
                $mostrar1.="             || frm.elements[i].name=='tipoubic61' || frm.elements[i].name=='tipoubic62' || frm.elements[i].name=='tipoubic63' || frm.elements[i].name=='tipoubic64' || frm.elements[i].name=='tipoubic65'){\n";
                $mostrar1.="               if(frm.elements[i].checked==true){";
                $mostrar1.="                 frm.elements[i].checked=false;";
                $mostrar1.="               }\n";
                $mostrar1.="               frm.elements[i].disabled=true\n";
                $mostrar1.="             }\n";
                $mostrar1.="             else{\n";
                $mostrar1.="               frm.elements[i].disabled=false\n";
                $mostrar1.="              }\n";
                $mostrar1.="           }\n";
                $mostrar1.="         }\n";
                $mostrar1.="          }\n";
                $mostrar1.="          else{\n";
                $mostrar1.="              if(valor==14){\n";
                $mostrar1.="             for(i=0;i<frm.elements.length;i++){\n";
                $mostrar1.="               if(frm.elements[i].type=='checkbox'){\n";
                $mostrar1.="                 if(frm.elements[i].name=='tipoubic11' || frm.elements[i].name=='tipoubic12' || frm.elements[i].name=='tipoubic13'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic21' || frm.elements[i].name=='tipoubic22' || frm.elements[i].name=='tipoubic23'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic51' || frm.elements[i].name=='tipoubic52' || frm.elements[i].name=='tipoubic53'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic61' || frm.elements[i].name=='tipoubic62' || frm.elements[i].name=='tipoubic63'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic31' || frm.elements[i].name=='tipoubic32' || frm.elements[i].name=='tipoubic33' || frm.elements[i].name=='tipoubic34' || frm.elements[i].name=='tipoubic35' || frm.elements[i].name=='tipoubic36' || frm.elements[i].name=='tipoubic37' || frm.elements[i].name=='tipoubic38'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic41' || frm.elements[i].name=='tipoubic42' || frm.elements[i].name=='tipoubic43' || frm.elements[i].name=='tipoubic44' || frm.elements[i].name=='tipoubic45' || frm.elements[i].name=='tipoubic46' || frm.elements[i].name=='tipoubic47' || frm.elements[i].name=='tipoubic48'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic71' || frm.elements[i].name=='tipoubic72' || frm.elements[i].name=='tipoubic73' || frm.elements[i].name=='tipoubic74' || frm.elements[i].name=='tipoubic75'\n";
                $mostrar1.="                   || frm.elements[i].name=='tipoubic81' || frm.elements[i].name=='tipoubic82' || frm.elements[i].name=='tipoubic83' || frm.elements[i].name=='tipoubic84' || frm.elements[i].name=='tipoubic85'){\n";
                $mostrar1.="                      if(frm.elements[i].checked==true){\n";
                $mostrar1.="                          frm.elements[i].checked=false;\n";
                $mostrar1.="                      }\n";
                $mostrar1.="                    frm.elements[i].disabled=true\n";
                $mostrar1.="                  }\n";
                $mostrar1.="                  else{\n";
                $mostrar1.="                     frm.elements[i].disabled=false\n";
                $mostrar1.="                    }\n";
                $mostrar1.="                   }\n";
                $mostrar1.="                 }\n";
                $mostrar1.="                }\n";
                $mostrar1.="                 else{\n";
                $mostrar1.="                      if(valor==34){\n";
                $mostrar1.="                     for(i=0;i<frm.elements.length;i++){\n";
                $mostrar1.="                       if(frm.elements[i].type=='checkbox'){\n";
                $mostrar1.="                         if(frm.elements[i].name=='tipoubic31' || frm.elements[i].name=='tipoubic32' || frm.elements[i].name=='tipoubic33'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic41' || frm.elements[i].name=='tipoubic42' || frm.elements[i].name=='tipoubic43'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic71' || frm.elements[i].name=='tipoubic72' || frm.elements[i].name=='tipoubic73'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic81' || frm.elements[i].name=='tipoubic82' || frm.elements[i].name=='tipoubic83'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic11' || frm.elements[i].name=='tipoubic12' || frm.elements[i].name=='tipoubic13' || frm.elements[i].name=='tipoubic14' || frm.elements[i].name=='tipoubic15' || frm.elements[i].name=='tipoubic16' || frm.elements[i].name=='tipoubic17' || frm.elements[i].name=='tipoubic18'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic21' || frm.elements[i].name=='tipoubic22' || frm.elements[i].name=='tipoubic23' || frm.elements[i].name=='tipoubic24' || frm.elements[i].name=='tipoubic25' || frm.elements[i].name=='tipoubic26' || frm.elements[i].name=='tipoubic27' || frm.elements[i].name=='tipoubic28'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic51' || frm.elements[i].name=='tipoubic52' || frm.elements[i].name=='tipoubic53' || frm.elements[i].name=='tipoubic54' || frm.elements[i].name=='tipoubic55'\n";
                $mostrar1.="                          || frm.elements[i].name=='tipoubic61' || frm.elements[i].name=='tipoubic62' || frm.elements[i].name=='tipoubic63' || frm.elements[i].name=='tipoubic64' || frm.elements[i].name=='tipoubic65'){\n";
                $mostrar1.="                            if(frm.elements[i].checked==true){\n";
                $mostrar1.="                              frm.elements[i].checked=false;\n";
                $mostrar1.="                            }\n";
                $mostrar1.="                            frm.elements[i].disabled=true\n";
                $mostrar1.="                           }\n";
                $mostrar1.="                           else{\n";
                $mostrar1.="                             frm.elements[i].disabled=false\n";
                $mostrar1.="                          }\n";
                $mostrar1.="                           }\n";
                $mostrar1.="                         }\n";
                $mostrar1.="                       }\n";
                $mostrar1.="                 }\n";
                $mostrar1.="              }\n";
                $mostrar1.="     }\n";
//
                $mostrar1.="else{";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=2>PALATINO<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=3>LINGUAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=9> CERVICAL PALATINO<br>';\n";
                $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=10> CERVICAL LINGUAL<br>';\n";
                $mostrar1.=" }";
                $mostrar1.="}\n";//FIN DE LA FUNCIÓN PPAL*/
        /*      $mostrar1.="    function abrirVentanaClass(url){\n";
                $mostrar1.="    var str = 'width=930,height=350,resizable=no,status=no,scrollbars=no,top=100,left=50';\n";
                $mostrar1.="    var rems = window.open(url,'',str);\n";
                $mostrar1.="    if (rems != null) {\n";
                $mostrar1.="            if (rems.opener == null) {\n";
                $mostrar1.="                    rems.opener = self;\n";
                $mostrar1.="            }\n";
                $mostrar1.="    }\n";
                $mostrar1.="    }\n";
                $mostrar1.="</script>\n";
                $this->salida.="$mostrar1";*/
                $this->salida.='<form name="forma1'.$pfj.'" action="'.$accion.'" method="post">';
                $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
                $this->salida.=$this->SetStyle("MensajeError");
                $this->salida.="</table>";
                $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="        <table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                $this->salida.="        <tr class=\"hc_table_list_title\">";
/*              $this->salida.="        <td width=\"17%\" align=\"center\">";
                $this->salida.="DIENTE";
                $this->salida.="        </td>";*/
                $this->salida.="        <td width=\"65%\" align=\"center\">";
                $this->salida.="SELECCIÓN";
                $this->salida.="        </td>";
                $this->salida.="        <td width=\"35%\" align=\"center\">";
                $this->salida.="SUPERFICIE";
                $this->salida.="        </td>";
                $this->salida.="        </tr>";
/*                $this->salida.="        <tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.="        <td width=\"100%\" align=\"center\" colspan=\"2\">";
                //$busquedas=$this->BuscarTipoUbicacion();
                $this->salida.= "<label class=\"label\">SELECCIONE RANGO DE UBICACIÓN:</label>";    
                $this->salida.="        <select name=\"tipoubicac".$pfj."\" class=\"select\" onchange=\"BuscarSuperficies(this[this.selectedIndex].value,'$pfj',this.form);\">";
                //EN EL VALUE DEL SELECT SE COLOCA EL DIENTE (hc_tipo_ubicacion_diente_id)
                //REPRESENTATIVO DEL AREA DONDE LAS SUPERFICIES SON COMUNES.
                $this->salida.="        <option value=\"11\" selected>[&nbsp;11-13&nbsp;]&nbsp&nbsp;[&nbsp;21-23&nbsp;]&nbsp&nbsp;[&nbsp;51-53&nbsp;]&nbsp&nbsp;[&nbsp;61-63&nbsp;]</option>";
                $this->salida.="        <option value=\"14\">[&nbsp;14-18&nbsp;]&nbsp&nbsp;[&nbsp;24-28&nbsp;]&nbsp&nbsp;[&nbsp;54-55&nbsp;]&nbsp&nbsp;[&nbsp;64-65&nbsp;]</option>";
                $this->salida.="        <option value=\"31\">[&nbsp;31-33&nbsp;]&nbsp&nbsp;[&nbsp;41-43&nbsp;]&nbsp&nbsp;[&nbsp;71-73&nbsp;]&nbsp&nbsp;[&nbsp;81-83&nbsp;]</option>";
                $this->salida.="        <option value=\"34\">[&nbsp;34-38&nbsp;]&nbsp&nbsp;[&nbsp;44-48&nbsp;]&nbsp&nbsp;[&nbsp;74-75&nbsp;]&nbsp&nbsp;[&nbsp;84-85&nbsp;]</option>";
                $this->salida.="        </select>";
                $this->salida.="        </td>";
                $this->salida.="</tr>";*/
                //CHECKS DE UBICACIÓN
                $this->salida.="        <tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.="        <td width=\"45%\" align=\"right\">";
                $this->salida.="        <table width=\"40%\" border=\"1\" align=\"center\">";
                //$this->EsquemaCheck();
                $this->ChecksOdontograma();
                $this->salida.="        </table>";
                $this->salida.="        </td>";
                //FIN CHECKS DE UBICACIÓN

/*                $this->salida.="        <td width=\"35%\" align=\"left\">";
                $this->salida.="        <div id=\"div1\">";
                $this->salida.="        </div>";
                if($_REQUEST['tipoubicac'.$pfj]<>NULL)
                { 
                        $mostrar1 ="<script language='javascript'>\n";
                        $mostrar1.="    BuscarSuperficies('".$_REQUEST['tipoubicac'.$pfj]."','$pfj',this.form);";
                        $mostrar1.="</script>\n";
                }
                else
                {
                        $mostrar1 ="<script language='javascript'>\n";
                        $mostrar1.="    BuscarSuperficies(11,'$pfj',this.form);";
                        $mostrar1.="</script>\n";
                }
                $this->salida.="$mostrar1";
                $this->salida.="        </td>";*/
                $this->salida.="        <td width=\"55%\" align=\"center\">";
                $this->TiposSuperficies($pfj);
                $this->salida.="        </td>";
                $this->salida.="</tr>";

                //HALLAZGO -- SOLUCIÓN
/*              $this->salida.="        <tr class=\"hc_table_list_title\">";
                $this->salida.="        <td width=\"100%\" align=\"center\" colspan=\"3\">";
                $this->salida.="HALLAZGO // SOLUCIÓN";
                $this->salida.="        </td>";
                $this->salida.="        </tr>";*/
                $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.="        <td width=\"100%\" align=\"center\" colspan=\"3\">";
                $this->salida.="                <table width=\"100%\" border=\"0\" align=\"center\">";
                $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.="                <td align=\"left\" colspan=\"2\">";
                $busquedas=$this->BuscarTipoProblema();
                $this->salida.="HALLAZGO: <select name=\"tipoproble".$pfj."\" class=\"select\">";
                $a=explode(',',$_REQUEST['tipoproble'.$pfj]);
                for($i=0;$i<sizeof($busquedas);$i++)
                {
                        if($busquedas[$i]['indice_orden']==1 OR ($busquedas[$i]['hc_tipo_problema_diente_id']==$a[0] AND $_REQUEST['tipoproble'.$pfj]<>NULL))
                        {
                                $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\" selected>".$busquedas[$i]['descripcion']."</option>";
                        }
                        else
                        {
                                $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\">".$busquedas[$i]['descripcion']."</option>";
                        }
                }
                $this->salida.="        </select>";
                $this->salida.="                </td>";
                $this->salida.="                </tr>";
                $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.="                <td align=\"left\" colspan=\"2\">";
                $busquedas=$this->BuscarTipoProductos();
                $this->salida.="SOLUCIÓN: <select name=\"tipoproduc".$pfj."\" class=\"select\">";
                for($i=0;$i<sizeof($busquedas);$i++)
                {
                        if($busquedas[$i]['indice_orden']==1 OR $_REQUEST['tipoproduc'.$pfj]==$busquedas[$i]['hc_tipo_producto_diente_id'])
                        {
                                $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\" selected>".$busquedas[$i]['descripcion']."</option>";
                        }
                        else
                        {
                                $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\">".$busquedas[$i]['descripcion']."</option>";
                        }
                }
                $this->salida.="        </select>";
                $this->salida.="                </td>";
                $this->salida.="                </tr>";

                $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.="                <td width=\"50%\" align=\"center\">";
                $this->salida.="                <input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
                $this->salida.="                </td>";
                $this->salida.="                </form>";
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'apoyos'));
                $this->salida.='<form name="forma2'.$pfj.'" action="'.$accion.'" method="post">';
                $this->salida.="                <td width=\"50%\" align=\"center\">";
                if($odontograma<>NULL)
                {
                        $this->salida.="                <input type=\"submit\" name=\"solicitar\" value=\"SOLICITAR APOYOS DIAGNÓSTICOS\" class=\"input-submit\">";
                }
                else
                {
                        $this->salida.="                <input disabled=\"true\" type=\"submit\" name=\"solicitar\" value=\"SOLICITAR APOYOS DIAGNÓSTICOS\" class=\"input-submit\">";
                }
                $this->salida.="                </td>";
                $this->salida.="        </form>";
                $this->salida.="                </tr>";
                $this->salida.="                </table>";
                $this->salida.="        </td>";
                $this->salida.="        </tr>";
                $this->salida.="        </table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="<label class=\"label\">ODONTOGRAMA</label>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $ruta="hc_modules/OdontogramaPrimeraVez/hc_convenciones.php";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="<input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"CONVENCIONES\" onclick=\"abrirVentanaClass('$ruta')\">";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
                if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
                {
                        $this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=\"hc_table_list_title\">";
                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarcopiar'));
                        $this->salida.='<form name="forma3'.$pfj.'" action="'.$accion.'" method="post">';
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="SE ENCONTRÓ UN ODONTOGRAMA EN EL HISTORIAL";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR COPIA\" class=\"input-submit\">";
                        $this->salida.="</td>";
                        $this->salida.="</form>";
                        $this->salida.="</tr>";
                        $this->salida.="</table><br>";
                }
                if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
                {
                        $ciclo=sizeof($odonselcop);
                        $cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
                        $cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odonselcop[$i]['estado']<>0
                                AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']<=48)
                                {
                                        if($odonselcop[$i]['sw_cariado']==1)
                                        {
                                                $cariadosp++;
                                        }
                                        else if($odonselcop[$i]['sw_obturado']==1)
                                        {
                                                $obturadosp++;
                                        }
                                        else if($odonselcop[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosp++;
                                        }
                                        else if($odonselcop[$i]['sw_sanos']==1)
                                        {
                                                $sanosp++;
                                        }
                                        else
                                        {
                                                $nocontadosp++;
                                        }
                                }
                                else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odonselcop[$i]['estado']==0
                                AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']<=48)
                                {
                                        if($odonselcop[$i]['sw_cariado2']==1)
                                        {
                                                $cariadosp++;
                                        }
                                        else if($odonselcop[$i]['sw_obturado2']==1)
                                        {
                                                $obturadosp++;
                                        }
                                        else if($odonselcop[$i]['sw_perdidos2']==1)
                                        {
                                                $perdidosp++;
                                        }
                                        else if($odonselcop[$i]['sw_sanos2']==1)
                                        {
                                                $sanosp++;
                                        }
                                        else
                                        {
                                                $nocontadosp++;
                                        }
                                }
                                else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odonselcop[$i]['estado']<>0
                                AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']>=51)
                                {
                                        if($odonselcop[$i]['sw_cariado']==1)
                                        {
                                                $cariadosp++;
                                        }
                                        else if($odonselcop[$i]['sw_obturado']==1)
                                        {
                                                $obturadosp++;
                                        }
                                        else if($odonselcop[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosp++;
                                        }
                                        else if($odonselcop[$i]['sw_sanos']==1)
                                        {
                                                $sanosp++;
                                        }
                                        else
                                        {
                                                $nocontadosp++;
                                        }
                                }
                                else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odonselcop[$i]['estado']==0
                                AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']>=51)
                                {
                                        if($odonselcop[$i]['sw_cariado2']==1)
                                        {
                                                $cariadosd++;
                                        }
                                        else if($odonselcop[$i]['sw_obturado2']==1)
                                        {
                                                $obturadosd++;
                                        }
                                        else if($odonselcop[$i]['sw_perdidos2']==1)
                                        {
                                                $perdidosd++;
                                        }
                                        else if($odonselcop[$i]['sw_sanos2']==1)
                                        {
                                                $sanosd++;
                                        }
                                        else
                                        {
                                                $nocontadosd++;
                                        }
                                }
                        }
                        $totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
                        $totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
                }
                else
                {
                        $ciclo=sizeof($odontograma);
                        $cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
                        $cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
                                {
                                        if($odontograma[$i]['sw_cariado']==1)
                                        {
                                                $cariadosp++;
                                        }
                                        else if($odontograma[$i]['sw_obturado']==1)
                                        {
                                                $obturadosp++;
                                        }
                                        else if($odontograma[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosp++;
                                        }
                                        else if($odontograma[$i]['sw_sanos']==1)
                                        {
                                                $sanosp++;
                                        }
                                        else
                                        {
                                                $nocontadosp++;
                                        }
                                }
                                else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=48)
                                {
                                        if($odontograma[$i]['sw_cariado']==1)
                                        {
                                                $cariadosd++;
                                        }
                                        else if($odontograma[$i]['sw_obturado']==1)
                                        {
                                                $obturadosd++;
                                        }
                                        else if($odontograma[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosd++;
                                        }
                                        else if($odontograma[$i]['sw_sanos']==1)
                                        {
                                                $sanosd++;
                                        }
                                        else
                                        {
                                                $nocontadosd++;
                                        }
                                }
                        }
                        $totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
                        $totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
                }
                $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                $this->salida.="<tr class=hc_table_list_title>";
                $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
                $this->salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=hc_table_list_title>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="CARIADOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="OBTURADOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="PERDIDOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="SANOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"20%\" align=\"center\">";
                $this->salida.="TOTAL";
                $this->salida.="</td>";
                $this->salida.="<td width=\"20%\" align=\"center\">";
                $this->salida.="SIN INCLUIR";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=hc_submodulo_list_claro>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$cariadosp."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$obturadosp."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$perdidosp."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$sanosp."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$totalp."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$nocontadosp."";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
                $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                $this->salida.="<tr class=hc_table_list_title>";
                $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
                $this->salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=hc_table_list_title>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="CARIADOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="EXTRAIDOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="OBTURADOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"15%\" align=\"center\">";
                $this->salida.="SANOS";
                $this->salida.="</td>";
                $this->salida.="<td width=\"20%\" align=\"center\">";
                $this->salida.="TOTAL";
                $this->salida.="</td>";
                $this->salida.="<td width=\"20%\" align=\"center\">";
                $this->salida.="SIN INCLUIR";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=hc_submodulo_list_claro>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$cariadosd."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$perdidosd."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$obturadosd."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$sanosd."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$totald."";
                $this->salida.="</td>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="".$nocontadosd."";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
                if(!empty($odontograma))
                {
                        $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=hc_table_list_title>";
                        $this->salida.="<td width=\"4%\" align=\"center\">";
                        $this->salida.="DIENTE";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="SUPERFICIE";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"25%\" align=\"center\">";
                        $this->salida.="HALLAZGO";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"25%\" align=\"center\">";
                        $this->salida.="SOLUCIÓN";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"16%\" align=\"center\">";
                        $this->salida.="PROFESIONAL";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"9%\" align=\"center\">";
                        $this->salida.="ACCIÓN";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"6%\" align=\"center\">";
                        $this->salida.="FECHA REGISTRO";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $j=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($j==0)
                                {
                                        $color="class=\"hc_submodulo_list_claro\"";
                                        $j=1;
                                }
                                else
                                {
                                        $color="class=\"hc_submodulo_list_oscuro\"";
                                        $j=0;
                                }
                                $this->salida.="<tr $color>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['des1']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['des2']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['des3']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['nombre']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                if($odontograma[$i]['estado']==0 OR $odontograma[$i]['estado']>=4)
                                {
                                        $this->salida.="ELIMINAR";
                                }
                                else
                                {
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar','odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id']));
                                        $this->salida.="<a href=\"$accion\">ELIMINAR</a>";//<img src=\"".GetThemePath()."/images/elimina.png\"  border='0'>
                                }
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
                                $this->salida.="".$fecha_registro[0]."";
                                $this->salida.="</td>";
                                $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                }
                $apoyos=$this->BuscarApoyosOdontologiaGuardados();
                if($apoyos<>NULL)
                {
                        $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=\"hc_table_list_title\">";
                        $this->salida.="<td width=\"10%\">CARGO</td>";
                        $this->salida.="<td width=\"67%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
                        $this->salida.="<td width=\"5%\" >CANTIDAD</td>";
                        $this->salida.="<td width=\"12%\">UBICACIÓN</td>";
                        $this->salida.="<td width=\"12%\">FECHA REGISTRO</td>";
                        $this->salida.="</tr>";
                        for($i=0;$i<sizeof($apoyos);$i++)
                        {
                                if( $i % 2)
                                {
                                        $estilo='modulo_list_claro';
                                }
                                else
                                {
                                        $estilo='modulo_list_oscuro';
                                }
                                $this->salida.="<tr class=\"$estilo\">";
                                $this->salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
                                $this->salida.="</td>";
                                $fecha_registro=explode(' ',$apoyos[$i]['fecha_registro']);
                                $this->salida.="<td align=\"center\">".$fecha_registro[0]."";
                                $this->salida.="</td>";
                                $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                }
                if($odontograma<>NULL)
                {
                        $this->salida.="<table width=\"60%\" align=\"center\" border=\"0\">";
                        $this->salida.="<tr>";
                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
                        $this->salida.='<form name="forma12" action="'.$accion.'" method="post">';
                        $this->salida.="<td align=\"right\" width=\"80%\" >";
                        $this->salida.="        <select name=\"sel\" class=\"select\">";
                        $this->salida.="        <option value=\"-1\" selected>----SELECCIONE----</option>";
                        $this->salida.="        <option value=\"insertarblanco\">INSERTAR DIENTES PERMANENTES SANOS</option>";
                        $this->salida.="        <option value=\"insertarsinerupcionar\">INSERTAR DIENTES PERMANENTES SIN ERUPCIONAR</option>";
                        $this->salida.="        <option value=\"insertarpermanentesextraidos\">INSERTAR DIENTES PERMANENTES EXTRAIDOS</option>";
                        $this->salida.="        <option value=\"insertarblancode\">INSERTAR DIENTES DECIDUOS SANOS</option>";
                        $this->salida.="        <option value=\"insertarausentes\">INSERTAR DIENTES DECIDUOS EXTRAIDOS</option>";
                        $this->salida.="        <option value=\"eliminarblanco\">ELIMINAR DIENTES PERMANENTES SANOS</option>";
                        $this->salida.="        <option value=\"eliminarsinerupcionar\">ELIMINAR DIENTES PERMANENTES SIN ERUPCIONAR</option>";
                        $this->salida.="        <option value=\"eliminarpermanentesausentes\">ELIMINAR DIENTES PERMANENTES EXTRAIDOS</option>";
                        $this->salida.="        <option value=\"eliminarblancode\">ELIMINAR DIENTES DECIDUOS SANOS</option>";
                        $this->salida.="        <option value=\"eliminarausentes\">ELIMINAR DIENTES DECIDUOS EXTRAIDOS</option>";
                        $this->salida.="        </select>";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"left\" width=\"20%\">";
                        $this->salida.="<input type=\"submit\" name=\"hacer\" value=\"HACER\" class=\"input-submit\">";
                        $this->salida.="</td>";
                        $this->salida.="</form>";
                        $this->salida.="</tr>";
                        $this->salida.="</table><br>";
                }
                $this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                $this->salida.="<tr class=\"hc_table_list_title\">";
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarobser'));
                $this->salida.='<form name="forma12'.$pfj.'" action="'.$accion.'" method="post">';
                $this->salida.="<td align=\"center\">";
                $this->salida.="OBSERVACIÓN";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="<textarea class=\"input-text\" name=\"observacio".$pfj."\" cols=\"80\" rows=\"4\">".$_REQUEST['observacio'.$pfj]."</textarea>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR OBSERVACIÓN\" class=\"input-submit\">";
                $this->salida.="</td>";
                $this->salida.="</form>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
                $this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                $this->salida.="<tr class=\"hc_table_list_title\">";
                $this->salida.="<td align=\"center\">";
                if($_REQUEST['swactivado'.$pfj]==1)
                {
                        $this->salida.="EL ODONTOGRAMA QUEDARÁ ACTIVO AL TERMINAR LA EVOLUCIÓN";
                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarinacodon'));
                        $valor="DEJARLO INACTIVO";
                }
                else
                {
                        $this->salida.="EL ODONTOGRAMA QUEDARÁ INACTIVO AL TERMINAR LA EVOLUCIÓN";
                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertaractiodon'));
                        $valor="DEJARLO ACTIVO";
                }
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
                $this->salida.='<form name="forma13'.$pfj.'" action="'.$accion.'" method="post">';
                $this->salida.="<td align=\"center\">";
                if($this->tipo_profesional==10)
                {
                        $this->salida.="<input disabled=\"true\" type=\"submit\" name=\"insertar\" value=\"".$valor."\" class=\"input-submit\">";
                }
                else
                {
                        $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"".$valor."\" class=\"input-submit\">";
                }
                $this->salida.="</td>";
                $this->salida.="</form>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.=ThemeCerrarTablaSubModulo();
                return true;
        }
        //FIN MODIFICACIÓN DE FRMFORMA

        //FORMA ORIGINAL 10/08/05
//      function frmForma()//Desde esta funcion es de JORGE AVILA
//      {
//              $pfj=$this->frmPrefijo;
//              $odontograma=$this->BuscarOdontogramaForma();
//              $valoracion=$this->BuscarEnviarPintarMuelas();//presentes
//              if($odontograma===false)
//              {
//                      return false;
//              }
//              $ipboleary=$this->BuscarIPBOlearyControl();
//              if($ipboleary<>NULL)
//              {
//                      if($this->frmError["MensajeError"]==NULL)
//                      {
//                              $this->frmError["MensajeError"]="SE ENCONTRÓ UN DIAGRAMA DE IPBOLEARY RELACIONADO AL PACIENTE
//                              <br>POR FAVOR, TENGA ENCUENTA ESTO SI VA A REALIZAR UN CAMBIO EN EL ODONTOGRAMA";
//                      }
//                      else
//                      {
//                              $this->frmError["MensajeError"].="<br>SE ENCONTRÓ UN DIAGRAMA DE IPBOLEARY RELACIONADO AL PACIENTE
//                              <br>POR FAVOR, TENGA ENCUENTA ESTO SI VA A REALIZAR UN CAMBIO EN EL ODONTOGRAMA";
//                      }
//              }
//              if($this->primeravez==1)
//              {
//                      if($this->frmError["MensajeError"]==NULL)
//                      {
//                              $this->frmError["MensajeError"]="NO SE PUEDE CERRAR LA HC<br>FALTAN DIENTES POR DIAGNÓSTICO";
//                      }
//                      else
//                      {
//                              $this->frmError["MensajeError"]="<br>NO SE PUEDE CERRAR LA HC<br>FALTAN DIENTES POR DIAGNÓSTICO";
//                      }
//              }
//              if($odontograma==NULL)
//              {
//                      if($this->frmError["MensajeError"]==NULL)
//                      {
//                              $this->frmError["MensajeError"]="NO SE ENCONTRÓ UN ODONTOGRAMA ACTIVO";
//                      }
//                      else
//                      {
//                              $this->frmError["MensajeError"].="<br>NO SE ENCONTRÓ UN ODONTOGRAMA ACTIVO";
//                      }
//                      $odonselcop=$this->BuscarOdontogramaFormaViejo();
//                      if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
//                      {
//                              $valoracion=$this->BuscarEnviarPintarMuelasViejo($odonselcop[0]['hc_odontograma_primera_vez_id']);
//                              $this->frmError["MensajeError"].="<br>PERO SI SE ENCONTRÓ UN ODONTOGRAMA EN EL HISTORIAL";
//                      }
//              }
//              $randon=$this->ingreso._.rand();//$randon=rand();
//              IncludeLib("jpgraph/Odontograma_graphic");
//              $RutaImg=Odontograma($valoracion,$randon,1);
//              $this->salida =ThemeAbrirTablaSubModulo('ODONTOGRAMA DE PRIMERA VEZ');
//              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
//              $mostrar1 ="<script language='javascript'>\n";
//              $mostrar1.="    function BuscarSuperficies(valor,prefijo){\n";
//              $mostrar1.="    document.getElementById('div1').innerHTML ='<input type=checkbox name=0'+prefijo+' value=11>SUPERFICIE TOTAL<br>';\n";
//              $mostrar1.="    if((valor>=11 && valor<=18 || valor>=51 && valor<=55) || (valor>=21 && valor<=28 || valor>=61 && valor<=65)){\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=2>PALATINO<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
//              $mostrar1.="            if((valor>=11 && valor<=13 || valor>=51 && valor<=53) || (valor>=21 && valor<=23 || valor>=61 && valor<=63)){\n";
//              $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
//              $mostrar1.="            }\n";
//              $mostrar1.="            else{\n";
//              $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
//              $mostrar1.="            }\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=9> CERVICAL PALATINO<br>';\n";
//              $mostrar1.="    }\n";
//              $mostrar1.="    else if((valor>=31 && valor<=38 || valor>=71 && valor<=75) || (valor>=41 && valor<=48 || valor>=81 && valor<=85)){\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=1'+prefijo+' value=1>VESTIBULAR<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=2'+prefijo+' value=3>LINGUAL<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=3'+prefijo+' value=4>MESIAL<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=4'+prefijo+' value=5>DISTAL<br>';\n";
//              $mostrar1.="            if((valor>=31 && valor<=33 || valor>=71 && valor<=73) || (valor>=41 && valor<=43 || valor>=81 && valor<=83)){\n";
//              $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=7>INCISAL<br>';\n";
//              $mostrar1.="            }\n";
//              $mostrar1.="            else{\n";
//              $mostrar1.="                    document.getElementById('div1').innerHTML+='<input type=checkbox name=5'+prefijo+' value=6>OCLUSAL<br>';\n";
//              $mostrar1.="            }\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=6'+prefijo+' value=8>CERVICAL VESTIBULAR<br>';\n";
//              $mostrar1.="            document.getElementById('div1').innerHTML+='<input type=checkbox name=7'+prefijo+' value=10> CERVICAL LINGUAL<br>';\n";
//              $mostrar1.="    }\n";
//              $mostrar1.="    }\n";
//              $mostrar1.="    function abrirVentanaClass(url){\n";
//              $mostrar1.="    var str = 'width=930,height=350,resizable=no,status=no,scrollbars=no,top=100,left=50';\n";
//              $mostrar1.="    var rems = window.open(url,'',str);\n";
//              $mostrar1.="    if (rems != null) {\n";
//              $mostrar1.="            if (rems.opener == null) {\n";
//              $mostrar1.="                    rems.opener = self;\n";
//              $mostrar1.="            }\n";
//              $mostrar1.="    }\n";
//              $mostrar1.="    }\n";
//              $mostrar1.="</script>\n";
//              $this->salida.="$mostrar1";
//              $this->salida.='<form name="forma1'.$pfj.'" action="'.$accion.'" method="post">';
//              $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
//              $this->salida.=$this->SetStyle("MensajeError");
//              $this->salida.="</table>";
//              $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
//              $this->salida.="<tr>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="        <table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//              $this->salida.="        <tr class=\"hc_table_list_title\">";
//              $this->salida.="        <td width=\"10%\" align=\"center\">";
//              $this->salida.="DIENTE";
//              $this->salida.="        </td>";
//              $this->salida.="        <td width=\"20%\" align=\"center\">";
//              $this->salida.="SUPERFICIE";
//              $this->salida.="        </td>";
//              $this->salida.="        <td width=\"70%\" align=\"center\">";
//              $this->salida.="HALLAZGO // SOLUCIÓN";
//              $this->salida.="        </td>";
//              $this->salida.="        </tr>";
//              $this->salida.="        <tr class=\"hc_submodulo_list_oscuro\">";
//              $this->salida.="        <td width=\"10%\" align=\"center\">";
//              $busquedas=$this->BuscarTipoUbicacion();
//              $this->salida.="        <select name=\"tipoubicac".$pfj."\" class=\"select\" onchange=\"BuscarSuperficies(this[this.selectedIndex].value,'$pfj');\">";
//              for($i=0;$i<sizeof($busquedas);$i++)
//              {
//                      if($busquedas[$i]['indice_orden']==1 OR $_REQUEST['tipoubicac'.$pfj]==$busquedas[$i]['hc_tipo_ubicacion_diente_id'])
//                      {
//                              $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."\" selected>".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."</option>";
//                      }
//                      else
//                      {
//                              $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."\">".$busquedas[$i]['hc_tipo_ubicacion_diente_id']."</option>";
//                      }
//              }
//              $this->salida.="        </select>";
//              $this->salida.="        </td>";
//              $this->salida.="        <td width=\"20%\" align=\"left\">";
//              $this->salida.="        <div id=\"div1\">";
//              $this->salida.="        </div>";
//              if($_REQUEST['tipoubicac'.$pfj]<>NULL)
//              {
//                      $mostrar1 ="<script language='javascript'>\n";
//                      $mostrar1.="    BuscarSuperficies('".$_REQUEST['tipoubicac'.$pfj]."','$pfj');";
//                      $mostrar1.="</script>\n";
//              }
//              else
//              {
//                      $mostrar1 ="<script language='javascript'>\n";
//                      $mostrar1.="    BuscarSuperficies(11,'$pfj');";
//                      $mostrar1.="</script>\n";
//              }
//              $this->salida.="$mostrar1";
//              $this->salida.="        </td>";
// 
//              $this->salida.="        <td width=\"100%\" align=\"center\">";
//              $this->salida.="                <table width=\"100%\" border=\"0\" align=\"center\">";
//              $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
//              $this->salida.="                <td align=\"left\" colspan=\"2\">";
//              $busquedas=$this->BuscarTipoProblema();
//              $this->salida.="HALLAZGO: <select name=\"tipoproble".$pfj."\" class=\"select\">";
//              $a=explode(',',$_REQUEST['tipoproble'.$pfj]);
//              for($i=0;$i<sizeof($busquedas);$i++)
//              {
//                      if($busquedas[$i]['indice_orden']==1 OR ($busquedas[$i]['hc_tipo_problema_diente_id']==$a[0] AND $_REQUEST['tipoproble'.$pfj]<>NULL))
//                      {
//                              $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\" selected>".$busquedas[$i]['descripcion']."</option>";
//                      }
//                      else
//                      {
//                              $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_problema_diente_id']."".','."".$busquedas[$i]['sw_diente_completo']."\">".$busquedas[$i]['descripcion']."</option>";
//                      }
//              }
//              $this->salida.="        </select>";
//              $this->salida.="                </td>";
//              $this->salida.="                </tr>";
//              $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
//              $this->salida.="                <td align=\"left\" colspan=\"2\">";
//              $busquedas=$this->BuscarTipoProductos();
//              $this->salida.="SOLUCIÓN: <select name=\"tipoproduc".$pfj."\" class=\"select\">";
//              for($i=0;$i<sizeof($busquedas);$i++)
//              {
//                      if($busquedas[$i]['indice_orden']==1 OR $_REQUEST['tipoproduc'.$pfj]==$busquedas[$i]['hc_tipo_producto_diente_id'])
//                      {
//                              $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\" selected>".$busquedas[$i]['descripcion']."</option>";
//                      }
//                      else
//                      {
//                              $this->salida.="        <option value=\"".$busquedas[$i]['hc_tipo_producto_diente_id']."\">".$busquedas[$i]['descripcion']."</option>";
//                      }
//              }
//              $this->salida.="        </select>";
//              $this->salida.="                </td>";
//              $this->salida.="                </tr>";
// 
//              $this->salida.="                <tr class=\"hc_submodulo_list_oscuro\">";
//              $this->salida.="                <td width=\"50%\" align=\"center\">";
//              $this->salida.="                <input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
//              $this->salida.="                </td>";
//              $this->salida.="                </form>";
//              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'apoyos'));
//              $this->salida.='<form name="forma2'.$pfj.'" action="'.$accion.'" method="post">';
//              $this->salida.="                <td width=\"50%\" align=\"center\">";
//              if($odontograma<>NULL)
//              {
//                      $this->salida.="                <input type=\"submit\" name=\"solicitar\" value=\"SOLICITAR APOYOS DIAGNÓSTICOS\" class=\"input-submit\">";
//              }
//              else
//              {
//                      $this->salida.="                <input disabled=\"true\" type=\"submit\" name=\"solicitar\" value=\"SOLICITAR APOYOS DIAGNÓSTICOS\" class=\"input-submit\">";
//              }
//              $this->salida.="                </td>";
//              $this->salida.="        </form>";
//              $this->salida.="                </tr>";
//              $this->salida.="                </table>";
//              $this->salida.="        </td>";
//              $this->salida.="        </tr>";
//              $this->salida.="        </table>";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="<label class=\"label\">ODONTOGRAMA</label>";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $ruta="hc_modules/OdontogramaPrimeraVez/hc_convenciones.php";
//              $this->salida.="<tr>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="<input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"CONVENCIONES\" onclick=\"abrirVentanaClass('$ruta')\">";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="</table><br>";
//              if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
//              {
//                      $this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//                      $this->salida.="<tr class=\"hc_table_list_title\">";
//                      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarcopiar'));
//                      $this->salida.='<form name="forma3'.$pfj.'" action="'.$accion.'" method="post">';
//                      $this->salida.="<td align=\"center\">";
//                      $this->salida.="SE ENCONTRÓ UN ODONTOGRAMA EN EL HISTORIAL";
//                      $this->salida.="</td>";
//                      $this->salida.="</tr>";
//                      $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
//                      $this->salida.="<td align=\"center\">";
//                      $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR COPIA\" class=\"input-submit\">";
//                      $this->salida.="</td>";
//                      $this->salida.="</form>";
//                      $this->salida.="</tr>";
//                      $this->salida.="</table><br>";
//              }
//              if($odonselcop[0]['hc_odontograma_primera_vez_id']<>NULL)
//              {
//                      $ciclo=sizeof($odonselcop);
//                      $cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
//                      $cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
//                      for($i=0;$i<$ciclo;$i++)
//                      {
//                              if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
//                              AND $odonselcop[$i]['estado']<>0
//                              AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']<=48)
//                              {
//                                      if($odonselcop[$i]['sw_cariado']==1)
//                                      {
//                                              $cariadosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_obturado']==1)
//                                      {
//                                              $obturadosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_perdidos']==1)
//                                      {
//                                              $perdidosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_sanos']==1)
//                                      {
//                                              $sanosp++;
//                                      }
//                                      else
//                                      {
//                                              $nocontadosp++;
//                                      }
//                              }
//                              else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
//                              AND $odonselcop[$i]['estado']==0
//                              AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']<=48)
//                              {
//                                      if($odonselcop[$i]['sw_cariado2']==1)
//                                      {
//                                              $cariadosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_obturado2']==1)
//                                      {
//                                              $obturadosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_perdidos2']==1)
//                                      {
//                                              $perdidosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_sanos2']==1)
//                                      {
//                                              $sanosp++;
//                                      }
//                                      else
//                                      {
//                                              $nocontadosp++;
//                                      }
//                              }
//                              else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
//                              AND $odonselcop[$i]['estado']<>0
//                              AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']>=51)
//                              {
//                                      if($odonselcop[$i]['sw_cariado']==1)
//                                      {
//                                              $cariadosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_obturado']==1)
//                                      {
//                                              $obturadosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_perdidos']==1)
//                                      {
//                                              $perdidosp++;
//                                      }
//                                      else if($odonselcop[$i]['sw_sanos']==1)
//                                      {
//                                              $sanosp++;
//                                      }
//                                      else
//                                      {
//                                              $nocontadosp++;
//                                      }
//                              }
//                              else if($odonselcop[$i]['hc_tipo_ubicacion_diente_id']<>$odonselcop[$i-1]['hc_tipo_ubicacion_diente_id']
//                              AND $odonselcop[$i]['estado']==0
//                              AND $odonselcop[$i]['hc_tipo_ubicacion_diente_id']>=51)
//                              {
//                                      if($odonselcop[$i]['sw_cariado2']==1)
//                                      {
//                                              $cariadosd++;
//                                      }
//                                      else if($odonselcop[$i]['sw_obturado2']==1)
//                                      {
//                                              $obturadosd++;
//                                      }
//                                      else if($odonselcop[$i]['sw_perdidos2']==1)
//                                      {
//                                              $perdidosd++;
//                                      }
//                                      else if($odonselcop[$i]['sw_sanos2']==1)
//                                      {
//                                              $sanosd++;
//                                      }
//                                      else
//                                      {
//                                              $nocontadosd++;
//                                      }
//                              }
//                      }
//                      $totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
//                      $totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
//              }
//              else
//              {
//                      $ciclo=sizeof($odontograma);
//                      $cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
//                      $cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
//                      for($i=0;$i<$ciclo;$i++)
//                      {
//                              if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
//                              AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
//                              {
//                                      if($odontograma[$i]['sw_cariado']==1)
//                                      {
//                                              $cariadosp++;
//                                      }
//                                      else if($odontograma[$i]['sw_obturado']==1)
//                                      {
//                                              $obturadosp++;
//                                      }
//                                      else if($odontograma[$i]['sw_perdidos']==1)
//                                      {
//                                              $perdidosp++;
//                                      }
//                                      else if($odontograma[$i]['sw_sanos']==1)
//                                      {
//                                              $sanosp++;
//                                      }
//                                      else
//                                      {
//                                              $nocontadosp++;
//                                      }
//                              }
//                              else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
//                              AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=48)
//                              {
//                                      if($odontograma[$i]['sw_cariado']==1)
//                                      {
//                                              $cariadosd++;
//                                      }
//                                      else if($odontograma[$i]['sw_obturado']==1)
//                                      {
//                                              $obturadosd++;
//                                      }
//                                      else if($odontograma[$i]['sw_perdidos']==1)
//                                      {
//                                              $perdidosd++;
//                                      }
//                                      else if($odontograma[$i]['sw_sanos']==1)
//                                      {
//                                              $sanosd++;
//                                      }
//                                      else
//                                      {
//                                              $nocontadosd++;
//                                      }
//                              }
//                      }
//                      $totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
//                      $totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
//              }
//              $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//              $this->salida.="<tr class=hc_table_list_title>";
//              $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
//              $this->salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr class=hc_table_list_title>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="CARIADOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="OBTURADOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="PERDIDOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="SANOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"20%\" align=\"center\">";
//              $this->salida.="TOTAL";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"20%\" align=\"center\">";
//              $this->salida.="SIN INCLUIR";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr class=hc_submodulo_list_claro>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$cariadosp."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$obturadosp."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$perdidosp."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$sanosp."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$totalp."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$nocontadosp."";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="</table><br>";
//              $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//              $this->salida.="<tr class=hc_table_list_title>";
//              $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
//              $this->salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr class=hc_table_list_title>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="CARIADOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="EXTRAIDOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="OBTURADOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"15%\" align=\"center\">";
//              $this->salida.="SANOS";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"20%\" align=\"center\">";
//              $this->salida.="TOTAL";
//              $this->salida.="</td>";
//              $this->salida.="<td width=\"20%\" align=\"center\">";
//              $this->salida.="SIN INCLUIR";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr class=hc_submodulo_list_claro>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$cariadosd."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$perdidosd."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$obturadosd."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$sanosd."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$totald."";
//              $this->salida.="</td>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="".$nocontadosd."";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="</table><br>";
//              if(!empty($odontograma))
//              {
//                      $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//                      $this->salida.="<tr class=hc_table_list_title>";
//                      $this->salida.="<td width=\"6%\" align=\"center\">";
//                      $this->salida.="DIENTE";
//                      $this->salida.="</td>";
//                      $this->salida.="<td width=\"17%\" align=\"center\">";
//                      $this->salida.="SUPERFICIE";
//                      $this->salida.="</td>";
//                      $this->salida.="<td width=\"25%\" align=\"center\">";
//                      $this->salida.="HALLAZGO";
//                      $this->salida.="</td>";
//                      $this->salida.="<td width=\"27%\" align=\"center\">";
//                      $this->salida.="SOLUCIÓN";
//                      $this->salida.="</td>";
//                      $this->salida.="<td width=\"16%\" align=\"center\">";
//                      $this->salida.="PROFESIONAL";
//                      $this->salida.="</td>";
//                      $this->salida.="<td width=\"9%\" align=\"center\">";
//                      $this->salida.="ACCIÓN";
//                      $this->salida.="</td>";
//                      $this->salida.="</tr>";
//                      $j=0;
//                      for($i=0;$i<$ciclo;$i++)
//                      {
//                              if($j==0)
//                              {
//                                      $color="class=\"hc_submodulo_list_claro\"";
//                                      $j=1;
//                              }
//                              else
//                              {
//                                      $color="class=\"hc_submodulo_list_oscuro\"";
//                                      $j=0;
//                              }
//                              $this->salida.="<tr $color>";
//                              $this->salida.="<td align=\"center\">";
//                              $this->salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">";
//                              $this->salida.="".$odontograma[$i]['des1']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">";
//                              $this->salida.="".$odontograma[$i]['des2']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">";
//                              $this->salida.="".$odontograma[$i]['des3']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">";
//                              $this->salida.="".$odontograma[$i]['nombre']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">";
//                              if($odontograma[$i]['estado']==0 OR $odontograma[$i]['estado']>=4)
//                              {
//                                      $this->salida.="ELIMINAR";
//                              }
//                              else
//                              {
//                                      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar','odondetadi'.$pfj=>$odontograma[$i]['hc_odontograma_primera_vez_detalle_id']));
//                                      $this->salida.="<a href=\"$accion\">ELIMINAR</a>";//<img src=\"".GetThemePath()."/images/elimina.png\"  border='0'>
//                              }
//                              $this->salida.="</td>";
//                              $this->salida.="</tr>";
//                      }
//                      $this->salida.="</table><br>";
//              }
//              $apoyos=$this->BuscarApoyosOdontologiaGuardados();
//              if($apoyos<>NULL)
//              {
//                      $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//                      $this->salida.="<tr class=\"hc_table_list_title\">";
//                      $this->salida.="<td width=\"10%\">CARGO</td>";
//                      $this->salida.="<td width=\"70%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
//                      $this->salida.="<td width=\"8%\" >CANTIDAD</td>";
//                      $this->salida.="<td width=\"12%\">UBICACIÓN</td>";
//                      $this->salida.="</tr>";
//                      for($i=0;$i<sizeof($apoyos);$i++)
//                      {
//                              if( $i % 2)
//                              {
//                                      $estilo='modulo_list_claro';
//                              }
//                              else
//                              {
//                                      $estilo='modulo_list_oscuro';
//                              }
//                              $this->salida.="<tr class=\"$estilo\">";
//                              $this->salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
//                              $this->salida.="</td>";
//                              $this->salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
//                              $this->salida.="</td>";
//                              $this->salida.="</tr>";
//                      }
//                      $this->salida.="</table><br>";
//              }
//              if($odontograma<>NULL)
//              {
//                      $this->salida.="<table width=\"60%\" align=\"center\" border=\"0\">";
//                      $this->salida.="<tr>";
//                      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
//                      $this->salida.='<form name="forma12" action="'.$accion.'" method="post">';
//                      $this->salida.="<td align=\"right\" width=\"80%\" >";
//                      $this->salida.="        <select name=\"sel\" class=\"select\">";
//                      $this->salida.="        <option value=\"-1\" selected>----SELECCIONE----</option>";
//                      $this->salida.="        <option value=\"insertarblanco\">INSERTAR DIENTES PERMANENTES SANOS</option>";
//                      $this->salida.="        <option value=\"insertarsinerupcionar\">INSERTAR DIENTES PERMANENTES SIN ERUPCIONAR</option>";
//                      $this->salida.="        <option value=\"insertarblancode\">INSERTAR DIENTES DECIDUOS SANOS</option>";
//                      $this->salida.="        <option value=\"insertarausentes\">INSERTAR DIENTES DECIDUOS EXTRAIDOS</option>";
//                      $this->salida.="        <option value=\"eliminarblanco\">ELIMINAR DIENTES PERMANENTES SANOS</option>";
//                      $this->salida.="        <option value=\"eliminarsinerupcionar\">ELIMINAR DIENTES PERMANENTES SIN ERUPCIONAR</option>";
//                      $this->salida.="        <option value=\"eliminarblancode\">ELIMINAR DIENTES DECIDUOS SANOS</option>";
//                      $this->salida.="        <option value=\"eliminarausentes\">ELIMINAR DIENTES DECIDUOS EXTRAIDOS</option>";
//                      $this->salida.="        </select>";
//                      $this->salida.="</td>";
//                      $this->salida.="<td align=\"left\" width=\"20%\">";
//                      $this->salida.="<input type=\"submit\" name=\"hacer\" value=\"HACER\" class=\"input-submit\">";
//                      $this->salida.="</td>";
//                      $this->salida.="</form>";
//                      $this->salida.="</tr>";
//                      $this->salida.="</table><br>";
//              }
//              $this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//              $this->salida.="<tr class=\"hc_table_list_title\">";
//              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarobser'));
//              $this->salida.='<form name="forma12'.$pfj.'" action="'.$accion.'" method="post">';
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="OBSERVACIÓN";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="<textarea class=\"input-text\" name=\"observacio".$pfj."\" cols=\"80\" rows=\"4\">".$_REQUEST['observacio'.$pfj]."</textarea>";
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr>";
//              $this->salida.="<td align=\"center\">";
//              $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR OBSERVACIÓN\" class=\"input-submit\">";
//              $this->salida.="</td>";
//              $this->salida.="</form>";
//              $this->salida.="</tr>";
//              $this->salida.="</table><br>";
//              $this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
//              $this->salida.="<tr class=\"hc_table_list_title\">";
//              $this->salida.="<td align=\"center\">";
//              if($_REQUEST['swactivado'.$pfj]==1)
//              {
//                      $this->salida.="EL ODONTOGRAMA QUEDARÁ ACTIVO AL TERMINAR LA EVOLUCIÓN";
//                      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarinacodon'));
//                      $valor="DEJARLO INACTIVO";
//              }
//              else
//              {
//                      $this->salida.="EL ODONTOGRAMA QUEDARÁ INACTIVO AL TERMINAR LA EVOLUCIÓN";
//                      $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertaractiodon'));
//                      $valor="DEJARLO ACTIVO";
//              }
//              $this->salida.="</td>";
//              $this->salida.="</tr>";
//              $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
//              $this->salida.='<form name="forma13'.$pfj.'" action="'.$accion.'" method="post">';
//              $this->salida.="<td align=\"center\">";
//              if($this->tipo_profesional==10)
//              {
//                      $this->salida.="<input disabled=\"true\" type=\"submit\" name=\"insertar\" value=\"".$valor."\" class=\"input-submit\">";
//              }
//              else
//              {
//                      $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"".$valor."\" class=\"input-submit\">";
//              }
//              $this->salida.="</td>";
//              $this->salida.="</form>";
//              $this->salida.="</tr>";
//              $this->salida.="</table>";
//              $this->salida.=ThemeCerrarTablaSubModulo();
//              return true;
//      }

        function frmApoyos()
        {
                $pfj=$this->frmPrefijo;
                $this->salida= ThemeAbrirTablaSubModulo('ODONTOGRAMA DE PRIMERA VEZ - APOYOS DIAGNÓSTICOS');
                $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
                'accion'.$pfj=>'apoyos',
                'Of'.$pfj=>$_REQUEST['Of'.$pfj],
                'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
                'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
                'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
                $this->salida.="<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                $this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE APOYOS DIAGNÓSTICOS</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="<td width=\"5%\">CODIGO:";
                $this->salida.="</td>";
                $this->salida.="<td width=\"5%\" align='center'>";
                $this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj'>";
                $this->salida.="</td>" ;
                $this->salida.="<td width=\"9%\">DESCRIPCIÓN:";
                $this->salida.="</td>";
                $this->salida.="<td width=\"46%\" align='center'>";
                $this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value=\"".$_REQUEST['diagnostico'.$pfj]."\">";
                $this->salida.="</td>" ;
                $this->salida.="<td width=\"5%\" align='center'>";
                $this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
                $this->salida.="</td>";
                $this->salida.="</form>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
                $vectorD=$this->BuscarApoyosOdontologia();
                $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
                $this->salida.= $this->SetStyle("MensajeError");
                $this->salida.="</table>";
                $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
                'accion'.$pfj=>'insertarapoyos',
                'vector'.$pfj=>$vectorD));
                if($vectorD)
                {
                        $this->salida.="<form name=\"formades2$pfj\" action=\"$accionI\" method=\"post\">";
                        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                        $this->salida.="<tr class=\"modulo_table_title\">";
                        $this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="<td width=\"10%\">CARGO</td>";
                        $this->salida.="<td width=\"62%\">DESCRIPCIÓN</td>";
                        $this->salida.="<td width=\"8%\" >CANTIDAD</td>";
                        $this->salida.="<td width=\"12%\">UBICACIÓN</td>";
                        $this->salida.="<td width=\"8%\" >GUAR</td>";
                        $this->salida.="</tr>";
                        $busquedas=$this->BuscarTipoUbicacion();
                        for($i=0;$i<sizeof($vectorD);$i++)
                        {
                                if( $i % 2)
                                {
                                        $estilo='modulo_list_claro';
                                }
                                else
                                {
                                        $estilo='modulo_list_oscuro';
                                }
                                $this->salida.="<tr class=\"$estilo\">";
                                $this->salida.="<td align=\"center\">".$vectorD[$i]['cargo']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"left\">".$vectorD[$i]['descripcion']."";
                                $this->salida.="</td>";
                                if($vectorD[$i]['cantidad_pend']==0
                                AND $vectorD[$i]['estado']=='1'
                                AND $vectorD[$i]['guarda']<>NULL)
                                {
                                        $habilita="";
                                }
                                else
                                {
                                        $habilita="disabled=\"true\"";
                                }
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="<input type=\"text\" class=\"input-text\" size=\"4\" maxlength=\"4\" name=\"cantidad".$i.$pfj."\" value=\"".$vectorD[$i]['cantidad']."\">";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="<textarea class=\"input-text\" name=\"apoyo".$i.$pfj."\"  cols=\"45\" rows=\"3\">".$vectorD[$i]['descripcion_ubicacion']."</textarea>";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                if($vectorD[$i]['guarda']<>NULL)
                                {
                                        $this->salida.="<input $habilita type=\"checkbox\" name=\"ayudas".$i.$pfj."\" value=\"".$vectorD[$i]['cargo']."\" checked>";
                                }
                                else
                                {
                                        $this->salida.="<input type=\"checkbox\" name=\"ayudas".$i.$pfj."\" value=\"".$vectorD[$i]['cargo']."\">";
                                }
                                $this->salida.="</td>";
                                $this->salida.="</tr>";
                        }
                        $this->salida.="<tr class=\"modulo_list_claro\">";
                        $this->salida.="<td align=\"right\" colspan=\"5\">";
                        $this->salida.="<input class=\"input-submit\" name=\"guardar".$pfj."\" type=\"submit\" value=\"GUARDAR\">";
                        $this->salida.="</td>";
                        $this->salida.="</form>";
                        $this->salida.="</tr>";
                        $this->salida.="</table><br>";
                        $var=$this->RetornarBarraDiagnosticos_Avanzada();
                        if(!empty($var))
                        {
                                $this->salida.="<table border=\"0\" width=\"60%\" align=\"center\">";
                                $this->salida.="<tr>";
                                $this->salida.="<td width=\"100%\" align=\"center\">";
                                $this->salida.=$var;
                                $this->salida.="</td>";
                                $this->salida.="</tr>";
                                $this->salida.="</table><br>";
                        }
                }
                $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"40%\">";
                $this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
                $this->salida.="</td>";
                $this->salida.="</form>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.=ThemeCerrarTablaSubModulo();
                return true;
        }

        function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
        {
                $pfj=$this->frmPrefijo;
                if($this->limit>=$this->conteo)
                {
                        return '';
                }
                $paso=$_REQUEST['paso1'.$pfj];
                if(empty($paso))
                {
                        $paso=1;
                }
                $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
                'accion'.$pfj=>'apoyos',
                'conteo'.$pfj=>$this->conteo,
                'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
                'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
                'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
                $barra=$this->CalcularBarra($paso);
                $numpasos=$this->CalcularNumeroPasos($this->conteo);
                $colspan=1;
                $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
                if($paso > 1)
                {
                        $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
                        $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
                        $colspan+=2;
                }
                $barra++;
                if(($barra+10)<=$numpasos)
                {
                        for($i=($barra);$i<($barra+10);$i++)
                        {
                                if($paso==$i)
                                {
                                        $salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                                }
                                else
                                {
                                        $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
                                }
                                $colspan++;
                        }
                        $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
                        $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
                        $colspan+=2;
                }
                else
                {
                        $diferencia=$numpasos-9;
                        if($diferencia<=0)
                        {
                                $diferencia=1;
                        }
                        for($i=($diferencia);$i<=$numpasos;$i++)
                        {
                                if($paso==$i)
                                {
                                        $salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                                }
                                else
                                {
                                        $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
                                }
                                $colspan++;
                        }
                        if($paso!=$numpasos)
                        {
                                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
                                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
                                $colspan++;
                        }
                }
                if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
                {
                        if($numpasos>10)
                        {
                                $valor=10+3;
                        }
                        else
                        {
                                $valor=$numpasos+3;
                        }
                        $salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
                }
                else
                {
                        if($numpasos>10)
                        {
                                $valor=10+5;
                        }
                        else
                        {
                                $valor=$numpasos+5;
                        }
                        $salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
                }
                return $salida;
        }

        function frmConsulta()
        {
                $odontograma=$this->BuscarOdontogramaFormaConsulta();
                $valoracion=$this->BuscarEnviarPintarMuelasConsulta();
                if($odontograma===false)
                {
                        return false;
                }
                if(sizeof($odontograma)!=0)
                {
                        $randon=$this->ingreso._.rand();//$randon=rand();
                        IncludeLib("jpgraph/Odontograma_graphic");
                        $RutaImg=Odontograma($valoracion,$randon,1);
                        $this->salida.="<br>";
                        $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr>";
                        $this->salida.="<td align=\"center\" class='hc_table_submodulo_list_title'>";
                        $this->salida.="<label class=\"label\">ODONTOGRAMA DE PRIMERA VEZ_</label>";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="<img src=\"".$RutaImg."\" border=\"0\">";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $ruta=GetThemePath()."/images/simbolos1.png";
                        $this->salida.="<tr>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="<img src=\"".$ruta."\" border=\"0\">";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="</table><br>";
                        $ciclo=sizeof($odontograma);
                        $cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
                        $cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
                                {
                                        if($odontograma[$i]['sw_cariado']==1)
                                        {
                                                $cariadosp++;
                                        }
                                        else if($odontograma[$i]['sw_obturado']==1)
                                        {
                                                $obturadosp++;
                                        }
                                        else if($odontograma[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosp++;
                                        }
                                        else if($odontograma[$i]['sw_sanos']==1)
                                        {
                                                $sanosp++;
                                        }
                                        else
                                        {
                                                $nocontadosp++;
                                        }
                                }
                                else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
                                {
                                        if($odontograma[$i]['sw_cariado']==1)
                                        {
                                                $cariadosd++;
                                        }
                                        else if($odontograma[$i]['sw_obturado']==1)
                                        {
                                                $obturadosd++;
                                        }
                                        else if($odontograma[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosd++;
                                        }
                                        else if($odontograma[$i]['sw_sanos']==1)
                                        {
                                                $sanosd++;
                                        }
                                        else
                                        {
                                                $nocontadosd++;
                                        }
                                }
                        }
                        $totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
                        $totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
                        $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=hc_table_submodulo_list_title>";
                        $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
                        $this->salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=hc_table_submodulo_list_title>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="CARIADOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="OBTURADOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="PERDIDOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="SANOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"20%\" align=\"center\">";
                        $this->salida.="TOTAL";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"20%\" align=\"center\">";
                        $this->salida.="SIN INCLUIR";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=hc_submodulo_list_claro>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$cariadosp."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$obturadosp."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$perdidosp."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$sanosp."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$totalp."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$nocontadosp."";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        //$this->salida.="</table><br>";
                        //$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=hc_table_submodulo_list_title>";
                        $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
                        $this->salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=hc_table_submodulo_list_title>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="CARIADOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="EXTRAIDOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="OBTURADOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="SANOS";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"20%\" align=\"center\">";
                        $this->salida.="TOTAL";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"20%\" align=\"center\">";
                        $this->salida.="SIN INCLUIR";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=hc_submodulo_list_claro>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$cariadosd."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$perdidosd."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$obturadosd."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$sanosd."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$totald."";
                        $this->salida.="</td>";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="".$nocontadosd."";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="</table><br>";
                        $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=hc_table_submodulo_list_title>";
                        $this->salida.="<td width=\"4%\" align=\"center\">";
                        $this->salida.="DIENTE";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"15%\" align=\"center\">";
                        $this->salida.="SUPERFICIE";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"29%\" align=\"center\">";
                        $this->salida.="HALLAZGO";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"30%\" align=\"center\">";
                        $this->salida.="SOLUCIÓN";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"16%\" align=\"center\">";
                        $this->salida.="PROFESIONAL";
                        $this->salida.="</td>";
                        $this->salida.="<td width=\"6%\" align=\"center\">";
                        $this->salida.="FECHA REGISTRO";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $j=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($j==0)
                                {
                                        $color="class=\"hc_submodulo_list_claro\"";
                                        $j=1;
                                }
                                else
                                {
                                        $color="class=\"hc_submodulo_list_oscuro\"";
                                        $j=0;
                                }
                                $this->salida.="<tr $color>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['des1']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['des2']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['des3']."";
                                $this->salida.="</td>";
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$odontograma[$i]['nombre']."";
                                $this->salida.="</td>";
                                $fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="".$fecha_registro[0]."";
                                $this->salida.="</td>";
                                $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                        $apoyos=$this->BuscarApoyosOdontologiaGuardadosConsulta();
                        $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        if($apoyos<>NULL)
                        {
                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                $this->salida.="<td width=\"8%\">CARGO</td>";
                                $this->salida.="<td width=\"68%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
                                $this->salida.="<td width=\"6%\" >CANTIDAD</td>";
                                $this->salida.="<td width=\"12%\">UBICACIÓN</td>";
                                $this->salida.="<td width=\"6%\">FECHA REGISTRO</td>";
                                $this->salida.="</tr>";
                                for($i=0;$i<sizeof($apoyos);$i++)
                                {
                                        if( $i % 2)
                                        {
                                                $estilo='modulo_list_claro';
                                        }
                                        else
                                        {
                                                $estilo='modulo_list_oscuro';
                                        }
                                        $this->salida.="<tr class=\"$estilo\">";
                                        $this->salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
                                        $this->salida.="</td>";
                                        $this->salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
                                        $this->salida.="</td>";
                                        $this->salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
                                        $this->salida.="</td>";
                                        $this->salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
                                        $this->salida.="</td>";
                                        $fecha_registro=explode(' ',$apoyos[$i]['fecha_registro']);
                                        $this->salida.="<td align=\"center\">".$fecha_registro[0]."";
                                        $this->salida.="</td>";
                                        $this->salida.="</tr>";
                                }
                        }
                        else
                        {
                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                $this->salida.="<td width=\"100%\">NO SE ENCONTRARÓN APOYOS DIAGNÓSTICOS</td>";
                                $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                        $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="<td align=\"center\">";
                        $this->salida.="OBSERVACIÓN";
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
                        $this->salida.="<td align=\"center\">";
                        if($_REQUEST['observacio'.$this->frmPrefijo]<>NULL)
                        {
                                $this->salida.="".$_REQUEST['observacio'.$this->frmPrefijo]."";
                        }
                        else
                        {
                                $this->salida.="NO HAY OBSERVACIONES";
                        }
                        $this->salida.="</td>";
                        $this->salida.="</tr>";
                        $this->salida.="</table>";
                        $this->salida.="<br>";
                }
                else
                {
                        return false;
                }
                return true;
        }

        function frmHistoria()
        {
                $odontograma=$this->BuscarOdontogramaFormaConsulta();
                $valoracion=$this->BuscarEnviarPintarMuelasConsulta();
                if($odontograma===false)
                {
                        return false;
                }
                if(sizeof($odontograma)!=0)
                {
                        $randon=$this->ingreso._.rand();//$randon=rand();
                        IncludeLib("jpgraph/Odontograma_graphic");
                        $RutaImg=Odontograma($valoracion,$randon,1);
                        $salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $salida.="<tr>";
                        $salida.="<td align=\"center\" class='hc_table_submodulo_list_title'>";
                        $salida.="<label class=\"label\">ODONTOGRAMA DE PRIMERA VEZ..</label>";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr>";
                        $salida.="<td align=\"center\">";
                        $salida.="<img src=\"".$RutaImg."\" border=\"0\">";// width=\"70%\"
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr>";
                        $salida.="<td align=\"center\">";
                        $ruta=GetThemePath()."/images/simbolos1.png";
                        $salida.="<img src=\"".$ruta."\" border=\"0\">";// width=\"70%\"
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="</table><br>";
                        $ciclo=sizeof($odontograma);
                        $cariadosp=$obturadosp=$perdidosp=$sanosp=$nocontadosp=$totalp=0;
                        $cariadosd=$obturadosd=$perdidosd=$sanosd=$nocontadosd=$totald=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']<=48)
                                {
                                        if($odontograma[$i]['sw_cariado']==1)
                                        {
                                                $cariadosp++;
                                        }
                                        else if($odontograma[$i]['sw_obturado']==1)
                                        {
                                                $obturadosp++;
                                        }
                                        else if($odontograma[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosp++;
                                        }
                                        else if($odontograma[$i]['sw_sanos']==1)
                                        {
                                                $sanosp++;
                                        }
                                        else
                                        {
                                                $nocontadosp++;
                                        }
                                }
                                else if($odontograma[$i]['hc_tipo_ubicacion_diente_id']<>$odontograma[$i-1]['hc_tipo_ubicacion_diente_id']
                                AND $odontograma[$i]['hc_tipo_ubicacion_diente_id']>=51)
                                {
                                        if($odontograma[$i]['sw_cariado']==1)
                                        {
                                                $cariadosd++;
                                        }
                                        else if($odontograma[$i]['sw_obturado']==1)
                                        {
                                                $obturadosd++;
                                        }
                                        else if($odontograma[$i]['sw_perdidos']==1)
                                        {
                                                $perdidosd++;
                                        }
                                        else if($odontograma[$i]['sw_sanos']==1)
                                        {
                                                $sanosd++;
                                        }
                                        else
                                        {
                                                $nocontadosd++;
                                        }
                                }
                        }
                        $totalp=$cariadosp+$obturadosp+$perdidosp+$sanosp;
                        $totald=$cariadosd+$obturadosd+$perdidosd+$sanosd;
                        $salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $salida.="<tr class=hc_table_submodulo_list_title>";
                        $salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
                        $salida.="INDICE COP DEL ODONTOGRAMA DE PRIMERA VEZ";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr class=hc_table_submodulo_list_title>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="CARIADOS";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="OBTURADOS";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="PERDIDOS";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="SANOS";
                        $salida.="</td>";
                        $salida.="<td width=\"20%\" align=\"center\">";
                        $salida.="TOTAL";
                        $salida.="</td>";
                        $salida.="<td width=\"20%\" align=\"center\">";
                        $salida.="SIN INCLUIR";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr class=hc_submodulo_list_claro>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$cariadosp."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$obturadosp."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$perdidosp."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$sanosp."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$totalp."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$nocontadosp."";
                        $salida.="</td>";
                        $salida.="</tr>";
                        //$salida.="</table><br>";
                        //$salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $salida.="<tr class=hc_table_submodulo_list_title>";
                        $salida.="<td width=\"100%\" align=\"center\" colspan=\"6\">";
                        $salida.="INDICE CEO DEL ODONTOGRAMA DE PRIMERA VEZ";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr class=hc_table_submodulo_list_title>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="CARIADOS";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="EXTRAIDOS";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="OBTURADOS";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="SANOS";
                        $salida.="</td>";
                        $salida.="<td width=\"20%\" align=\"center\">";
                        $salida.="TOTAL";
                        $salida.="</td>";
                        $salida.="<td width=\"20%\" align=\"center\">";
                        $salida.="SIN INCLUIR";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr class=hc_submodulo_list_claro>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$cariadosd."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$perdidosd."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$obturadosd."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$sanosd."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$totald."";
                        $salida.="</td>";
                        $salida.="<td align=\"center\">";
                        $salida.="".$nocontadosd."";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="</table><br>";
                        $salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $salida.="<tr class=hc_table_submodulo_list_title>";
                        $salida.="<td width=\"4%\" align=\"center\">";
                        $salida.="DIENTE";
                        $salida.="</td>";
                        $salida.="<td width=\"15%\" align=\"center\">";
                        $salida.="SUPERFICIE";
                        $salida.="</td>";
                        $salida.="<td width=\"29%\" align=\"center\">";
                        $salida.="HALLAZGO";
                        $salida.="</td>";
                        $salida.="<td width=\"30%\" align=\"center\">";
                        $salida.="SOLUCIÓN";
                        $salida.="</td>";
                        $salida.="<td width=\"16%\" align=\"center\">";
                        $salida.="PROFESIONAL";
                        $salida.="</td>";
                        $salida.="<td width=\"6%\" align=\"center\">";
                        $salida.="FECHA REGISTRO";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $j=0;
                        for($i=0;$i<$ciclo;$i++)
                        {
                                if($j==0)
                                {
                                        $color="class=\"hc_submodulo_list_claro\"";
                                        $j=1;
                                }
                                else
                                {
                                        $color="class=\"hc_submodulo_list_oscuro\"";
                                        $j=0;
                                }
                                $salida.="<tr $color>";
                                $salida.="<td align=\"center\">";
                                $salida.="".$odontograma[$i]['hc_tipo_ubicacion_diente_id']."";
                                $salida.="</td>";
                                $salida.="<td align=\"center\">";
                                $salida.="".$odontograma[$i]['des1']."";
                                $salida.="</td>";
                                $salida.="<td align=\"center\">";
                                $salida.="".$odontograma[$i]['des2']."";
                                $salida.="</td>";
                                $salida.="<td align=\"center\">";
                                $salida.="".$odontograma[$i]['des3']."";
                                $salida.="</td>";
                                $salida.="<td align=\"center\">";
                                $salida.="".$odontograma[$i]['nombre']."";
                                $salida.="</td>";
                                $fecha_registro=explode(' ',$odontograma[$i]['fecha_registro']);
                                $salida.="<td align=\"center\">";
                                $salida.="".$fecha_registro[0]."";
                                $salida.="</td>";
                                $salida.="</tr>";
                        }
                        $salida.="</table>";
                        $apoyos=$this->BuscarApoyosOdontologiaGuardadosConsulta();
                        $salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        if($apoyos<>NULL)
                        {
                                $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                $salida.="<td width=\"8%\">CARGO</td>";
                                $salida.="<td width=\"68%\">DESCRIPCIÓN DE LOS APOYOS DIAGNÓSTICOS</td>";
                                $salida.="<td width=\"6%\" >CANTIDAD</td>";
                                $salida.="<td width=\"12%\">UBICACIÓN</td>";
                                $salida.="<td width=\"6%\">FECHA REGISTRO</td>";
                                $salida.="</tr>";
                                for($i=0;$i<sizeof($apoyos);$i++)
                                {
                                        if( $i % 2)
                                        {
                                                $estilo='modulo_list_claro';
                                        }
                                        else
                                        {
                                                $estilo='modulo_list_oscuro';
                                        }
                                        $salida.="<tr class=\"$estilo\">";
                                        $salida.="<td align=\"center\">".$apoyos[$i]['cargo']."";
                                        $salida.="</td>";
                                        $salida.="<td align=\"left\">".$apoyos[$i]['descripcion']."";
                                        $salida.="</td>";
                                        $salida.="<td align=\"center\">".$apoyos[$i]['cantidad']."";
                                        $salida.="</td>";
                                        $salida.="<td align=\"center\">".$apoyos[$i]['descripcion_ubicacion']."";
                                        $salida.="</td>";
                                        $fecha_registro=explode(' ',$apoyos[$i]['fecha_registro']);
                                        $salida.="<td align=\"center\">".$fecha_registro[0]."";
                                        $salida.="</td>";
                                        $salida.="</tr>";
                                }
                        }
                        else
                        {
                                $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                $salida.="<td width=\"100%\">NO SE ENCONTRARÓN APOYOS DIAGNÓSTICOS</td>";
                                $salida.="</tr>";
                        }
                        $salida.="</table>";
                        $salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
                        $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $salida.="<td align=\"center\">";
                        $salida.="OBSERVACIÓN";
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="<tr class=\"hc_submodulo_list_claro\">";
                        $salida.="<td align=\"center\">";
                        if($_REQUEST['observacio'.$this->frmPrefijo]<>NULL)
                        {
                                $salida.="".$_REQUEST['observacio'.$this->frmPrefijo]."";
                        }
                        else
                        {
                                $salida.="NO HAY OBSERVACIONES";
                        }
                        $salida.="</td>";
                        $salida.="</tr>";
                        $salida.="</table>";
                        $salida.="<br>";
                }
                else
                {
                        return false;
                }
                return $salida;
        }

}
?>
