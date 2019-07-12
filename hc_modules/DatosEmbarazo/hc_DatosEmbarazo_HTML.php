<?php

/**
* Submodulo de Datos de la Mujer Embarazada (HTML).
*
* Submodulo para manejar la mujer embarazada y sus diferentes controles según los trimestres y las semanas de
* embarazo
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_DatosEmbarazo_HTML.php,v 1.2 2005/03/08 23:31:09 tizziano Exp $
*/

/**
* DatosEmbarazo_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo datos embarazo, se extiende la clase DatosEmbarazo y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class DatosEmbarazo_HTML extends DatosEmbarazo
{

	function DatosEmbarazo_HTML()
	{
	    $this->DatosEmbarazo();//constructor del padre
       	return true;
	}

	function frmConsulta()
	{
		$this->GestacionPaciente(&$fecha,&$gestacion);
		$semana=$this->CalcularSemanasGestante($fecha,date("Y-m-d"));
		$cron=$this->HistoriaReproductiva();
		$dato=$this->CondicionesAsociadasConsulta($gestacion);
		if(!empty($gestacion))
		{
			$this->salida.='<table border="0" align="center" width="635">';
			$this->salida.='<tr>';
			$this->salida.='<td>';
			if($semana<42)
			{
				if(empty($cron[0]))
				{
					$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list">';
					$this->salida.='<tr>';
					$this->salida.='	<td colspan="2">';
					$this->salida.='No existe datos de la Historia Reproductiva';
					$this->salida.='	</td>';
					$this->salida.='</tr>';
					$this->salida.='</table>';
				}
				else
				{
					$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list" cellpadding="2">';
					$this->salida.='<tr class="hc_table_list_title">';
					$this->salida.='	<td colspan="2">';
					$this->salida.='		HISTORIA REPRODUCTIVA';
					$this->salida.='	</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='	<td colspan="2">';
					if($cron[1]==1)
					{
						$this->salida.='Edad  < 16';
					}
					elseif($cron[1]==0)
					{
						$this->salida.='Edad  16 - 35';
					}
					elseif($cron[1]==2)
					{
						$this->salida.='Edad  > 35';
					}
					$this->salida.='	</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td colspan="2">';
					if($cron[2]==1)
					{
						$this->salida.='Paridad  0';
					}
					elseif($cron[2]==0)
					{
						$this->salida.='Paridad   1-4';
					}
					elseif($cron[2]==2)
					{
						$this->salida.='Paridad  >=5';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Aborto Habitual / Infertilidad';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[3]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Retención Placentaria';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[4]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Recien Nacido > 4000 GR.';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[5]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Recien Nacido < 2500 GR.';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[6]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='HTA Inducida por el embarazo';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[7]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Embarazo Gemelar / Cesaria Previa';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[8]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Mortinato / Muerte Neonatal';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[9]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Parto Dificil';
					$this->salida.='</td>';
					$this->salida.='<td width="5%" align="center">';
					if($cron[10]==1)
					{
						$this->salida.='SI';
					}
					else
					{
						$this->salida.='NO';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='</table>';
					$this->salida.='</td>';
				}
				if(sizeof($dato[0])!=0)
				{
					$this->salida.='<td valign="baseline">';
					$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list"  cellpadding="2">';
					$this->salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$this->salida.='<td colspan="'.$i.'">';
					$this->salida.='CONDICIONES ASOCIADAS';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Qx. Ginecologia Previa / Ectopico';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][3]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Enfermedad Renal Cronica';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][4]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Diabetes Gestacional';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][5]==2)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Diabetes Mellitus';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][6]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Enfermedad Cardiaca';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][7]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Enfermedad Infecciosa Aguda (Bacteriana)';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][8]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Enfermedad Autoinmune';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][9]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Anemia (Hb < 10 g/L)';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][10]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='</table>';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td colspan="2"><br>';
					$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list"  cellpadding="2">';
					$this->salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$this->salida.='<td colspan="'.$i.'">';
					$this->salida.='EMBARAZO ACTUAL';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Hemorragia &lt;= 20 Semanas';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][11]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Vaginal &gt; 20 Semanas';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][12]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$i++;
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='HTA';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][14]==2)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$i++;
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='RPM';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][15]==2)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$i++;
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='POLIHIDRAMINIOS';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][16]==2)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$i++;
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='RCIU';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][17]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Embarazo Multiple';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][18]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$i++;
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Mala Presentación';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][19]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='ISOINMUNIZACION RH';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$this->salida.='<td align="center">';
						if($dato[$i][20]==3)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='</table>';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td colspan="2"><br>';
					$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list"  cellpadding="2">';
					$this->salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$this->salida.='<td colspan="'.$i.'">';
					$this->salida.='SOPORTE EMOCIONAL';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Tension Emocional (Llanto fácil, lesión muscular, sobresalto, temblor, no poder quedarse quieta.)';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if($dato[$i][21]>=2)
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Intenso';
							$this->salida.='</td>';
						}
						else
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente';
							$this->salida.='</td>';
						}
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Humor Depresivo (Insomnio, falta de interés, no disfruta pasatiempo, depresión malgenio.)';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][21]-1)>=1)
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Intenso';
							$this->salida.='</td>';
						}
						else
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente';
							$this->salida.='</td>';
						}
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Sintomas Neurovegetativos (Transpiración manos, boca seca, accesos de rubor, palidez, cefaléa de tensión.)';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][21]-2)==1)
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Intenso';
							$this->salida.='</td>';
						}
						else
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente';
							$this->salida.='</td>';
						}
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='</table>';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td colspan="2"><br>';
					$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
					$this->salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$this->salida.='<td colspan="'.$i.'">';
					$this->salida.='SOPORTE FAMILIAR (Satisfecha con la forma como usted comparte con su familia o compañero)';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td width="60%">';
					$this->salida.='El tiempo';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][22])>=2)
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Nunca';
							$this->salida.='</td>';
						}
						else
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Casi Siempre';
							$this->salida.='</td>';
						}
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td width="60%">';
					$this->salida.='El espacio';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][22]-1)>=1)
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Nunca';
							$this->salida.='</td>';
						}
						else
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Casi Siempre';
							$this->salida.='</td>';
						}
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$this->salida.='<td width="60%">';
					$this->salida.='El dinero';
					$this->salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][22]-2)==1)
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Nunca';
							$this->salida.='</td>';
						}
						else
						{
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Casi Siempre';
							$this->salida.='</td>';
						}
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='</table>';
					$this->salida.='<br>';
					$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
					$this->salida.='<tr class="hc_table_list_title">';
					$i=sizeof($dato);
					$this->salida.='<td colspan="'.$i.'">';
					$this->salida.='RESULTADOS';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					$this->salida.='<tr>';
					$i=0;
					while($i<sizeof($dato))
					{
						$j=1;
						$t=0;
						while($j<sizeof($cron))
						{
							$t=$t+$cron[$j];
							$j++;
						}
						$j=3;
						while($j<sizeof($dato[$i]))
						{
							$t=$t+$dato[$i][$j];
							$j++;
						}
						$this->salida.='<td align="center">';
						if ($i==0)
						{
							if($t>3)
							{
								$this->salida.='Semana de 12 - 27 puntaje: <label class="label_error">'.$t.'</label>';
							}
							else
							{
								$this->salida.='Semana de 12 - 27 puntaje: <label>'.$t.'</label>';
							}
						}
						elseif($i==1)
						{
							if($t>3)
							{
								$this->salida.='Semana de 28 - 32 puntaje: <label class="label_error">'.$t.'</label>';
							}
							else
							{
								$this->salida.='Semana de 28 - 32 puntaje: <label>'.$t.'</label>';
							}
						}
						elseif
						($i==2)
						{
							if($t>3)
							{
								$this->salida.='Semana de 33 - 42 puntaje: <label class="label_error">'.$t.'</label>';
							}
							else
							{
								$this->salida.='Semana de 33 - 42 puntaje: <label>'.$t.'</label>';
							}
						}
						$this->salida.='</td>';
						$i++;
					}
					$this->salida.='</tr>';
					$this->salida.='</table>';
				}
				else
				{
					$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list">';
					$this->salida.='<tr>';
					$this->salida.='	<td>';
					$this->salida.='No existe datos de las Condiciones Asosciadas';
					$this->salida.='	</td>';
					$this->salida.='</tr>';
					$this->salida.='</table>';
				}
			}
			else
			{
				$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list">';
				$this->salida.='<tr class="hc_table_list_title">';
				$this->salida.='<td colspan="2">';
				$this->salida.='EMBARAZO ACTUAL';
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$this->salida.='<tr>';
				$this->salida.='<td>';
				$this->salida.='Embarazo prolongado';
				$this->salida.='</td>';
				$this->salida.='<td>';
				if($dato[13]==1)
				{
					$this->salida.='<input type="checkbox" name="prolongado'.$pfj.'" value="1" class="input-text" checked="true">';
				}
				else
				{
					$this->salida.='<input type="checkbox" name="prolongado'.$pfj.'" value="1" class="input-text">';
				}
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$this->salida.='</table>';
			}
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$this->salida.='</table>';
			$this->salida.='</table>';
			}
			else
			{
				return false;
			}
    	return true;
	}



	function frmHistoria()
	{
		$this->GestacionPaciente(&$fecha,&$gestacion);
		$semana=$this->CalcularSemanasGestante($fecha,date("Y-m-d"));
		$cron=$this->HistoriaReproductiva();
		$dato=$this->CondicionesAsociadasConsulta($gestacion);
		if(!empty($gestacion))
		{
			$salida.='<table border="0" align="center" width="635">';
			$salida.='<tr>';
			$salida.='<td>';
			if($semana<42)
			{
				if(empty($cron[0]))
				{
					$salida.='<table border="1" align="center" width="90%" class="hc_table_list">';
					$salida.='<tr>';
					$salida.='	<td colspan="2">';
					$salida.='No existe datos de la Historia Reproductiva';
					$salida.='	</td>';
					$salida.='</tr>';
					$salida.='</table>';
				}
				else
				{
					$salida.='<table border="1" align="center" width="90%" class="hc_table_list" cellpadding="2">';
					$salida.='<tr class="hc_table_list_title">';
					$salida.='	<td colspan="2">';
					$salida.='		HISTORIA REPRODUCTIVA';
					$salida.='	</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='	<td colspan="2">';
					if($cron[1]==1)
					{
						$salida.='Edad  < 16';
					}
					elseif($cron[1]==0)
					{
						$salida.='Edad  16 - 35';
					}
					elseif($cron[1]==2)
					{
						$salida.='Edad  > 35';
					}
					$salida.='	</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td colspan="2">';
					if($cron[2]==1)
					{
						$salida.='Paridad  0';
					}
					elseif($cron[2]==0)
					{
						$salida.='Paridad   1-4';
					}
					elseif($cron[2]==2)
					{
						$salida.='Paridad  >=5';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Aborto Habitual / Infertilidad';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[3]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Retención Placentaria';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[4]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Recien Nacido > 4000 GR.';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[5]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Recien Nacido < 2500 GR.';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[6]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='HTA Inducida por el embarazo';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[7]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Embarazo Gemelar / Cesaria Previa';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[8]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Mortinato / Muerte Neonatal';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[9]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Parto Dificil';
					$salida.='</td>';
					$salida.='<td width="5%" align="center">';
					if($cron[10]==1)
					{
						$salida.='SI';
					}
					else
					{
						$salida.='NO';
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='</table>';
					$salida.='</td>';
				}
				if(sizeof($dato[0])!=0)
				{
					$salida.='<td valign="baseline">';
					$salida.='<table border="1" align="center" width="90%" class="hc_table_list"  cellpadding="2">';
					$salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$salida.='<td colspan="'.$i.'">';
					$salida.='CONDICIONES ASOCIADAS';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Qx. Ginecologia Previa / Ectopico';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][3]==1)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Enfermedad Renal Cronica';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][4]==1)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Diabetes Gestacional';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][5]==2)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Diabetes Mellitus';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][6]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Enfermedad Cardiaca';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][7]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Enfermedad Infecciosa Aguda (Bacteriana)';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][8]==1)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Enfermedad Autoinmune';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][9]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Anemia (Hb < 10 g/L)';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][10]==1)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='</table>';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td colspan="2"><br>';
					$salida.='<table border="1" align="center" width="95%" class="hc_table_list"  cellpadding="2">';
					$salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$salida.='<td colspan="'.$i.'">';
					$salida.='EMBARAZO ACTUAL';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Hemorragia &lt;= 20 Semanas';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][11]==1)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Vaginal &gt; 20 Semanas';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][12]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$i++;
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='HTA';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][14]==2)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$i++;
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='RPM';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][15]==2)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$i++;
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='POLIHIDRAMINIOS';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][16]==2)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$i++;
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='RCIU';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][17]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Embarazo Multiple';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][18]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$i++;
					}
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Mala Presentación';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][19]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='ISOINMUNIZACION RH';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						$salida.='<td align="center">';
						if($dato[$i][20]==3)
						{
							$salida.='SI';
						}
						else
						{
							$salida.='NO';
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='</table>';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td colspan="2"><br>';
					$salida.='<table border="1" align="center" width="95%" class="hc_table_list"  cellpadding="2">';
					$salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$salida.='<td colspan="'.$i.'">';
					$salida.='SOPORTE EMOCIONAL';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Tension Emocional (Llanto fácil, lesión muscular, sobresalto, temblor, no poder quedarse quieta.)';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if($dato[$i][21]>=2)
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Intenso';
							$salida.='</td>';
						}
						else
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Ausente';
							$salida.='</td>';
						}
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Humor Depresivo (Insomnio, falta de interés, no disfruta pasatiempo, depresión malgenio.)';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][21]-1)>=1)
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Intenso';
							$salida.='</td>';
						}
						else
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Ausente';
							$salida.='</td>';
						}
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td>';
					$salida.='Sintomas Neurovegetativos (Transpiración manos, boca seca, accesos de rubor, palidez, cefaléa de tensión.)';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][21]-2)==1)
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Intenso';
							$salida.='</td>';
						}
						else
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Ausente';
							$salida.='</td>';
						}
						$i++;
					}
					$salida.='</tr>';
					$salida.='</table>';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td colspan="2"><br>';
					$salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
					$salida.='<tr class="hc_table_list_title">';
					$i=1+sizeof($dato);
					$salida.='<td colspan="'.$i.'">';
					$salida.='SOPORTE FAMILIAR (Satisfecha con la forma como usted comparte con su familia o compañero)';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td width="60%">';
					$salida.='El tiempo';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][22])>=2)
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Nunca';
							$salida.='</td>';
						}
						else
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Casi Siempre';
							$salida.='</td>';
						}
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td width="60%">';
					$salida.='El espacio';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][22]-1)>=1)
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Nunca';
							$salida.='</td>';
						}
						else
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Casi Siempre';
							$salida.='</td>';
						}
						$i++;
					}
					$salida.='</tr>';
					$salida.='<tr>';
					$salida.='<td width="60%">';
					$salida.='El dinero';
					$salida.='</td>';
					$i=0;
					while($i<sizeof($dato))
					{
						if(($dato[$i][22]-2)==1)
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Nunca';
							$salida.='</td>';
						}
						else
						{
							$salida.='<td width="10%" align="center">';
							$salida.='Casi Siempre';
							$salida.='</td>';
						}
						$i++;
					}
					$salida.='</tr>';
					$salida.='</table>';
					$salida.='<br>';
					$salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
					$salida.='<tr class="hc_table_list_title">';
					$i=sizeof($dato);
					$salida.='<td colspan="'.$i.'">';
					$salida.='RESULTADOS';
					$salida.='</td>';
					$salida.='</tr>';
					$salida.='<tr>';
					$i=0;
					while($i<sizeof($dato))
					{
						$j=1;
						$t=0;
						while($j<sizeof($cron))
						{
							$t=$t+$cron[$j];
							$j++;
						}
						$j=3;
						while($j<sizeof($dato[$i]))
						{
							$t=$t+$dato[$i][$j];
							$j++;
						}
						$salida.='<td align="center">';
						if ($i==0)
						{
							if($t>3)
							{
								$salida.='Semana de 12 - 27 puntaje: <label class="label_error">'.$t.'</label>';
							}
							else
							{
								$salida.='Semana de 12 - 27 puntaje: <label>'.$t.'</label>';
							}
						}
						elseif($i==1)
						{
							if($t>3)
							{
								$salida.='Semana de 28 - 32 puntaje: <label class="label_error">'.$t.'</label>';
							}
							else
							{
								$salida.='Semana de 28 - 32 puntaje: <label>'.$t.'</label>';
							}
						}
						elseif
						($i==2)
						{
							if($t>3)
							{
								$salida.='Semana de 33 - 42 puntaje: <label class="label_error">'.$t.'</label>';
							}
							else
							{
								$salida.='Semana de 33 - 42 puntaje: <label>'.$t.'</label>';
							}
						}
						$salida.='</td>';
						$i++;
					}
					$salida.='</tr>';
					$salida.='</table>';
				}
				else
				{
					$salida.='<table border="1" align="center" width="90%" class="hc_table_list">';
					$salida.='<tr>';
					$salida.='	<td>';
					$salida.='No existe datos de las Condiciones Asosciadas';
					$salida.='	</td>';
					$salida.='</tr>';
					$salida.='</table>';
				}
			}
			else
			{
				$salida.='<table border="1" align="center" width="95%" class="hc_table_list">';
				$salida.='<tr class="hc_table_list_title">';
				$salida.='<td colspan="2">';
				$salida.='EMBARAZO ACTUAL';
				$salida.='</td>';
				$salida.='</tr>';
				$salida.='<tr>';
				$salida.='<td>';
				$salida.='Embarazo prolongado';
				$salida.='</td>';
				$salida.='<td>';
				if($dato[13]==1)
				{
					$salida.='<input type="checkbox" name="prolongado'.$pfj.'" value="1" class="input-text" checked="true">';
				}
				else
				{
					$salida.='<input type="checkbox" name="prolongado'.$pfj.'" value="1" class="input-text">';
				}
				$salida.='</td>';
				$salida.='</tr>';
				$salida.='</table>';
			}
			$salida.='</td>';
			$salida.='</tr>';
			$salida.='</table>';
			$salida.='</table>';
			}
			else
			{
				return false;
			}
    	return $salida;
	}



	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}

	function CalcularSemanasGestante($FechaIni,$FechaFin)
	{
		$fech=strtok($FechaIni,"-");
		for($i=0;$i<3;$i++)
		{
			$date[$i]=$fech;
			$fech=strtok("-");
		}
		$fech=strtok($FechaFin,"-");
		for($i=0;$i<3;$i++)
		{
			$date1[$i]=$fech;
			$fech=strtok("-");
		}
		$edad=(ceil($date1[0])-$date[0]);
		$meses=$date1[1]-$date[1];
		$dias=$date1[2]-$date[2];
		$total=($edad*378)+($meses*31.5)+$dias;
		$meses1=($total%378)/30;
		$meses1=$meses1*4.5;
		return $meses1;
	}



	function CalcularFechaFinEmbarazo($FechaIni)
	{
   $spy=0;
	 $fecha="";
   $FechaIni=str_replace("/","-",$FechaIni);
   $fecha=explode("-",$FechaIni);
   $dia=$fecha[2] + 7;
   $mes=$fecha[1];
   $año=$fecha[0];

	 if($dia>30)
	   {
      $dia=$dia - 30;
		 }

    if($mes<3)
		 {
      $mes=$mes-3;
			$mes=12+$mes;
			}
      else
			{
      $año++;
			$mes=$mes-3;
			}
  }


	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$sexo=$this->Sexo();
		if($sexo=='F')
		{
			$fecha=$this->FechaNacimiento();
			$edad=CalcularEdad($fecha,date("Y-m-d"));
			$this->GestacionPaciente(&$fecha,&$gestacion);
			$semana=$this->CalcularSemanasGestante($fecha,date("Y-m-d"));
			$cron=$this->HistoriaReproductiva();
			$dato=$this->CondicionesAsociadas($gestacion,$semana);
			if(empty($this->titulo))
			{
				$this->salida  = ThemeAbrirTablaSubModulo('DISTINCIONES CRONICAS DEL PACIENTES');
			}
			else
			{
				$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
			}
			if(!empty($gestacion))
			{
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
				$this->salida.='<form name="prueba'.$pfj.'" action="'.$accion.'" method="post">';
				$this->salida.='<table border="0" align="center" width="635">';
				$this->salida.='<tr>';
				$this->salida.='<td>';
				if($semana<42)
				{
					if(empty($cron[0]))
					{
						$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list"  cellpadding="2">';
						$this->salida.='<tr class="hc_table_list_title">';
						$this->salida.='	<td colspan="2">';
						$this->salida.='		HISTORIA REPRODUCTIVA';
						$this->salida.='	</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='	<td colspan="2">';
						if($edad['años']<16)
						{
							$edades=1;
							$this->salida.='Edad  < 16';
						}
						elseif($edad['años']>=16 and $edad['años']<35)
						{
							$edades=0;
							$this->salida.='Edad  16 - 35';
						}
						elseif($edad['años']>=35)
						{
							$edades=2;
							$this->salida.='Edad  > 35';
						}
						$this->salida.='		<input type="hidden" name="edad'.$pfj.'" value="'.$edades.'" class="input-text">';
						$this->salida.='	</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td rowspan="3">';
						$this->salida.='&nbsp;    &nbsp;&nbsp; &nbsp;   &nbsp;       &nbsp;         &nbsp;                 &nbsp;                        &nbsp; 0 <br> Paridad &nbsp; &nbsp;1 - 4 <br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; >=5';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="radio" name="paridad'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="radio" name="paridad'.$pfj.'" value="0" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="radio" name="paridad'.$pfj.'" value="2" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Aborto Habitual / Infertilidad';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="infertilidad'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Retención Placentaria';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="retencion'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Recien Nacido > 4000 GR.';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="rngrande'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Recien Nacido < 2500 GR.';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="rnpequeno'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='HTA Inducida por el embarazo';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="htainducida'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Embarazo Gemelar / Cesaria Previa';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="gemelar'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Mortinato / Muerte Neonatal';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="mneonato'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Parto Dificil';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						$this->salida.='<input type="checkbox" name="dificil'.$pfj.'" value="1" class="input-text">';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='</table>';
					}
					else
					{
						$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list" cellpadding="2">';
						$this->salida.='<tr class="hc_table_list_title">';
						$this->salida.='	<td colspan="2">';
						$this->salida.='		HISTORIA REPRODUCTIVA';
						$this->salida.='	</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='	<td colspan="2">';
						if($cron[1]==1)
						{
							$this->salida.='Edad  < 16';
						}
						elseif($cron[1]==0)
						{
							$this->salida.='Edad  16 - 35';
						}
						elseif($cron[1]==2)
						{
							$this->salida.='Edad  > 35';
						}
						$this->salida.='	</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td colspan="2">';
						if($cron[2]==1)
						{
							$this->salida.='Paridad  0';
						}
						elseif($cron[2]==0)
						{
							$this->salida.='Paridad   1-4';
						}
						elseif($cron[2]==2)
						{
							$this->salida.='Paridad  >=5';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Aborto Habitual / Infertilidad';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[3]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Retención Placentaria';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[4]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Recien Nacido > 4000 GR.';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[5]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Recien Nacido < 2500 GR.';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[6]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='HTA Inducida por el embarazo';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[7]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Embarazo Gemelar / Cesaria Previa';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[8]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Mortinato / Muerte Neonatal';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[9]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Parto Dificil';
						$this->salida.='</td>';
						$this->salida.='<td width="5%" align="center">';
						if($cron[10]==1)
						{
							$this->salida.='SI';
						}
						else
						{
							$this->salida.='NO';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='</table>';
					}
					$this->salida.='</td>';
					$this->salida.='<td valign="baseline">';
					if($semana>14)
					{
						$this->salida.='<table border="1" align="center" width="90%" class="hc_table_list" cellpadding="2">';
						$this->salida.='<tr class="hc_table_list_title">';
						$this->salida.='<td colspan="2">';
						$this->salida.='CONDICIONES ASOCIADAS';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Qx. Ginecologia Previa / Ectopico';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[3]==1)
						{
							$this->salida.='<input type="checkbox" name="ectopico'.$pfj.'" value="1" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="ectopico'.$pfj.'" value="1" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Enfermedad Renal Cronica';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[4]==1)
						{
							$this->salida.='<input type="checkbox" name="renal'.$pfj.'" value="1" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="renal'.$pfj.'" value="1" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Diabetes Gestacional';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[5]==2)
						{
							$this->salida.='<input type="checkbox" name="gestacional'.$pfj.'" value="2" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="gestacional'.$pfj.'" value="2" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Diabetes Mellitus';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[6]==3)
						{
							$this->salida.='<input type="checkbox" name="mellitus'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="mellitus'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Enfermedad Cardiaca';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[7]==3)
						{
							$this->salida.='<input type="checkbox" name="cardiaca'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="cardiaca'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Enfermedad Infecciosa Aguda (Bacteriana)';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[8]==1)
						{
							$this->salida.='<input type="checkbox" name="infecciosa'.$pfj.'" value="1" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="infecciosa'.$pfj.'" value="1" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Enfermedad Autoinmune';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[9]==3)
						{
							$this->salida.='<input type="checkbox" name="autoinmune'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="autoinmune'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Anemia (Hb < 10 g/L)';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[10]==1)
						{
							$this->salida.='<input type="checkbox" name="anemia'.$pfj.'" value="1" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="anemia'.$pfj.'" value="1" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='</table>';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td colspan="2"><br>';
						$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
						$this->salida.='<tr class="hc_table_list_title">';
						$this->salida.='<td colspan="2">';
						$this->salida.='EMBARAZO ACTUAL';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Hemorragia &lt;= 20 Semanas';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[11]==1)
						{
							$this->salida.='<input type="checkbox" name="hemorragia'.$pfj.'" value="1" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="hemorragia'.$pfj.'" value="1" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Vaginal &gt; 20 Semanas';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[12]==3)
						{
							$this->salida.='<input type="checkbox" name="vaginal'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="vaginal'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='HTA';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[14]==2)
						{
							$this->salida.='<input type="checkbox" name="hta'.$pfj.'" value="2" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="hta'.$pfj.'" value="2" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='RPM';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[15]==2)
						{
							$this->salida.='<input type="checkbox" name="rpm'.$pfj.'" value="2" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="rpm'.$pfj.'" value="2" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='POLIHIDRAMINIOS';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[16]==2)
						{
							$this->salida.='<input type="checkbox" name="polihidraminios'.$pfj.'" value="2" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="polihidraminios'.$pfj.'" value="2" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='RCIU';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[17]==3)
						{
							$this->salida.='<input type="checkbox" name="rciu'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="rciu'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Embarazo Multiple';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[18]==3)
						{
							$this->salida.='<input type="checkbox" name="multiple'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="multiple'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='Mala Presentación';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[19]==3)
						{
							$this->salida.='<input type="checkbox" name="presentacion'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="presentacion'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td>';
						$this->salida.='ISOINMUNIZACION RH';
						$this->salida.='</td>';
						$this->salida.='<td>';
						if($dato[20]==3)
						{
							$this->salida.='<input type="checkbox" name="isoinmunizacion'.$pfj.'" value="3" class="input-text" checked="true">';
						}
						else
						{
							$this->salida.='<input type="checkbox" name="isoinmunizacion'.$pfj.'" value="3" class="input-text">';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='</table>';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td colspan="2"><br>';
						$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
						$this->salida.='<tr class="hc_table_list_title">';
						$this->salida.='<td colspan="3">';
						$this->salida.='SOPORTE EMOCIONAL';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						if($dato[21]>=2)
						{
							$dat=$dato[21];
							$this->salida.='<tr>';
							$this->salida.='<td>';
							$this->salida.='Tension Emocional (Llanto fácil, lesión muscular, sobresalto, temblor, no poder quedarse quieta.)';
							$this->salida.='</td>';
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente<input type="radio" name="emocional1'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Intenso<input type="radio" name="emocional1'.$pfj.'" value="1" class="input-text" checked="true">';
							$dat--;
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td>';
							$this->salida.='Humor Depresivo (Insomnio, falta de interés, no disfruta pasatiempo, depresión malgenio.)';
							$this->salida.='</td>';
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente<input type="radio" name="emocional2'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Intenso<input type="radio" name="emocional2'.$pfj.'" value="1" class="input-text" checked="true">';
							$dat--;
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td>';
							$this->salida.='Sintomas Neurovegetativos (Transpiración manos, boca seca, accesos de rubor, palidez, cefaléa de tensión.)';
							$this->salida.='</td>';
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente<input type="radio" name="emocional3'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							if($dat==1)
							{
								$this->salida.='Intenso<input type="radio" name="emocional3'.$pfj.'" value="1" class="input-text" checked="true">';
							}
							else
							{
								$this->salida.='Intenso<input type="radio" name="emocional3'.$pfj.'" value="1" class="input-text">';
							}
							$this->salida.='</td>';
							$this->salida.='</tr>';
						}
						else
						{
							$this->salida.='<tr>';
							$this->salida.='<td>';
							$this->salida.='Tension Emocional (Llanto fácil, lesión muscular, sobresalto, temblor, no poder quedarse quieta.)';
							$this->salida.='</td>';
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente<input type="radio" name="emocional1'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Intenso<input type="radio" name="emocional1'.$pfj.'" value="1" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td>';
							$this->salida.='Humor Depresivo (Insomnio, falta de interés, no disfruta pasatiempo, depresión malgenio.)';
							$this->salida.='</td>';
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente<input type="radio" name="emocional2'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Intenso<input type="radio" name="emocional2'.$pfj.'" value="1" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td>';
							$this->salida.='Sintomas Neurovegetativos (Transpiración manos, boca seca, accesos de rubor, palidez, cefaléa de tensión.)';
							$this->salida.='</td>';
							$this->salida.='<td width="10%" align="center">';
							$this->salida.='Ausente<input type="radio" name="emocional3'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Intenso<input type="radio" name="emocional3'.$pfj.'" value="1" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='</tr>';
						}
						$this->salida.='</table>';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$this->salida.='<tr>';
						$this->salida.='<td colspan="2"><br>';
						$this->salida.='<table border="1" align="center" width="95%" class="hc_table_list" cellpadding="2">';
						$this->salida.='<tr class="hc_table_list_title">';
						$this->salida.='<td colspan="4">';
						$this->salida.='SOPORTE FAMILIAR (Satisfecha con la forma como usted comparte con su familia o compañero)';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						if($dato[22]>=2)
						{
							$dat=$dato[22];
							$this->salida.='<tr>';
							$this->salida.='<td width="60%">';
							$this->salida.='El tiempo';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Casi Siempre<input type="radio" name="familiar1'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='A veces<input type="radio" name="familiar1'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Nunca<input type="radio" name="familiar1'.$pfj.'" value="1" class="input-text" checked="true">';
							$dat--;
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td width="60%">';
							$this->salida.='El espacio';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Casi Siempre<input type="radio" name="familiar2'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='A veces<input type="radio" name="familiar2'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Nunca<input type="radio" name="familiar2'.$pfj.'" value="1" class="input-text" checked="true">';
							$dat--;
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td width="60%">';
							$this->salida.='El dinero';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Casi Siempre<input type="radio" name="familiar3'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='A veces<input type="radio" name="familiar3'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							if($dat==1)
							{
								$this->salida.='Nunca<input type="radio" name="familiar3'.$pfj.'" value="1" class="input-text" checked="true">';
							}
							else
							{
								$this->salida.='Nunca<input type="radio" name="familiar3'.$pfj.'" value="1" class="input-text">';
							}
							$this->salida.='</td>';
							$this->salida.='</tr>';
						}
						else
						{
							$this->salida.='<tr>';
							$this->salida.='<td width="60%">';
							$this->salida.='El tiempo';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Casi Siempre<input type="radio" name="familiar1'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='A veces<input type="radio" name="familiar1'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Nunca<input type="radio" name="familiar1'.$pfj.'" value="1" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td width="60%">';
							$this->salida.='El espacio';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Casi Siempre<input type="radio" name="familiar2'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='A veces<input type="radio" name="familiar2'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Nunca<input type="radio" name="familiar2'.$pfj.'" value="1" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='<tr>';
							$this->salida.='<td width="60%">';
							$this->salida.='El dinero';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Casi Siempre<input type="radio" name="familiar3'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='A veces<input type="radio" name="familiar3'.$pfj.'" value="0" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='<td align="center">';
							$this->salida.='Nunca<input type="radio" name="familiar3'.$pfj.'" value="1" class="input-text">';
							$this->salida.='</td>';
							$this->salida.='</tr>';
							$this->salida.='</td>';
						}
					}
				}
				else
				{
					$this->salida.='<tr>';
					$this->salida.='<td>';
					$this->salida.='Embarazo prolongado';
					$this->salida.='</td>';
					$this->salida.='<td>';
					if($dato[13]==1)
					{
						$this->salida.='<input type="checkbox" name="prolongado'.$pfj.'" value="1" class="input-text" checked="true">';
					}
					else
					{
						$this->salida.='<input type="checkbox" name="prolongado'.$pfj.'" value="1" class="input-text">';
					}
					$this->salida.='</td>';
					$this->salida.='</tr>';
				}
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$this->salida.='</table>';
				$this->salida.="<table width=\"0\" align=\"center\">";
				$this->salida.='<tr><td align="center" colspan="2"><br><input type="submit" name="Guardar" value="Guardar" class="input-submit"></td></tr>';
				$this->salida.='</table>';
				$this->salida.='</table>';
				$this->salida.="</form>";
				$this->salida.= ThemeCerrarTablaSubModulo();
			}
			else
			{
				$this->salida.="<br>";
				$this->salida.="<table width=\"80%\" align=\"center\">";
				$this->salida.="<tr>";
				$this->salida.="<td>";
				$this->salida.="No existen datos";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="<br>";
			}
		}
		else
		{
			$this->salida.="<br>";
			$this->salida.="<table width=\"80%\" align=\"center\">";
			$this->salida.="<tr>";
			$this->salida.="<td>";
		  $this->salida.="El paciente es masculino por lo tanto este modulo no aplica.";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="<br>";
		}
    return true;
	}

}

?>
