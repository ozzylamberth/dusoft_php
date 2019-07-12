<?php

// $Id: hc_Tamizaje_HTML.php,v 1.3 2006/12/19 21:00:15 jgomez Exp $

class Tamizaje_HTML extends Tamizaje
{

	function Tamizaje_HTML()
	{
	    $this->Tamizaje();//constructor del padre
       	return true;
	}


  /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'',
    'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }

////////////////
  
  /**
* Funcion que calcula y  retorna las semanas de gestacion de una paciente, sacando la fecha
* inicial desde la tabla gestacion, mediante el campo (FUM -->fecha ultima mestruacion), la cual llega
* a la variable $FechaIni y con la fecha actual, la cual es $FechaFin, se sacan las semanas de la paciente.
* @return boolean
* @param date fecha sacada de la tabla gestacion(campo --> fum)
*/
	function CalcularSemanasGestante($FechaIni)
	{
			$FechaFin=date("Y-m-d");
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
			$meses1=(($total%378)/30);
			$meses1=$meses1*4.5;
  		return $meses1;
	}



	function frmConsulta()
	{
	list($dbconn) = GetDBconn();
	$query = "select * from hc_motivo_consulta where evolucion_id=".$this->evolucion.";";
                    $result = $dbconn->Execute($query);
                    $i=0;
			if ($dbconn->ErrorNo() != 0)
			{
			echo "hola";
                      	return false;
                    	}
			else
			{
   				while (!$result->EOF)
			        {
				$motiv[0][$i]=$result->fields[1];
				$motiv[1][$i]=$result->fields[2];
	  		        $result->MoveNext();
				$i++;
			        }
			 }
	  $this->consulta  = ThemeAbrirTablaSubModulo('Consulta Tamizaje');
	  $this->consulta.="<table border=\"1\" class=\"hc_table_list\">";
		$this->consulta.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->consulta.="<td>Motivo Consulta</td>";
		$this->consulta.="<td>Enfermedad Actual</td>";
		$this->consulta.="</tr>";
		$i=0;
		$p=0;
		while($i<sizeof($motiv[0]))
		{
		if ($p==0)
		{
		  $p=1;
		  $this->consulta.="<tr class=\"hc_submodulo_list_claro\">";
		  $this->consulta.="<td>";
		  $this->consulta.=$motiv[0][$i];
		  $this->consulta.="</td>";
		  $this->consulta.="<td>";
		  $this->consulta.=$motiv[1][$i];
		  $this->consulta.="</td>";
		  $this->consulta.="</tr>";
		}
		else
		{
		  $p=0;
			$this->consulta.="<tr class=\"hc_submodulo_list_oscuro\">";
		  $this->consulta.="<td>";
		  $this->consulta.=$motiv[0][$i];
		  $this->consulta.="</td>";
		  $this->consulta.="<td>";
		  $this->consulta.=$motiv[1][$i];
		  $this->consulta.="</td>";
		  $this->consulta.="</tr>";
		}
		$i++;
		}
		$this->consulta.="</table>";
	  $this->consulta .= ThemeCerrarTablaSubModulo();
    	return true;
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


	function frmForma()
	{

    list($onn) = GetDBconn();
    //tipo_tamizaje_id
		$buscon = "select tipo,objeto from  hc_tipo_tamizajes order by objeto";
		$source=$onn->Execute($buscon);
		$conteo=$source->RecordCount();

    $bus = "select fum,gestacion_id from gestacion as a where a.paciente_id='".
		$this->paciente."' and a.tipo_id_paciente='".$this->tipo."' and a.estado=1;";
    $source=$onn->Execute($bus);
		$fechaData=$source->fields[0]; //$fechaData trae la fecha de inicio de la gestacion....
    $gestacion=$source->fields[1];
		$semana=0;
		//mandamos la fecha de inicio para sacar las semanas de gestacion
    $semana=$this->CalcularSemanasGestante($fechaData);
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('TAMIZAJE');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

	  $this->forma.="<table width=\"632\" border=\"0\" align=\"center\" class=\"hc_table_list\">";
		$this->forma.=$this->SetStyle("MensajeError");
    $this->forma.="<br>";
		$this->forma.="<table border=\"1\" align=\"center\"  width=\"53%\" class=\"hc_table\">";
		$this->forma.="<tr class=\"hc_table_title\" >";
		$this->forma.="<td colspan=\"2\">Grupo y RH</td>";
		$this->forma.="<td>Glicemia en Ayunas</td>";
		$this->forma.="<td>O Sullivan</td>";
		$this->forma.="<td>CTG</td>";
		$this->forma.="</tr>";
		$this->forma.="<tr align=\"center\" class=\"hc_list_oscuro\">";
		$this->forma.="<td height=\"27\">";
		$this->forma.="<input name=\"data\" type=\"checkbox\" id=\"data\" value=\"checkbox\"></td>";
		$this->forma.="<td><input name=\"rh\" type=\"text\" id=\"rh2\" size=\"10\" maxlength=\"5\" class=\"input-text\"></td>";
		$this->forma.="<td><input name=\"glicemia\" type=\"text\" id=\"glicemia\" size=\"10\" class=\"input-text\"></td>";
		$this->forma.="<td><input name=\"sullivan\" type=\"text\" id=\"sullivan\" size=\"10\" class=\"input-text\"></td>";
		$this->forma.="<td><input name=\"ctg\" type=\"text\" id=\"ctg\" size=\"10\" class=\"input-text\"></td>";
		$this->forma.="</tr>";
		$this->forma.="</table>";
		$this->forma.="<br>";
		$this->forma.="<br>";
		$this->forma.="<table border=\"1\" align=\"center\" class=\"hc_table_list\" width=\"53%\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->forma.="<tr  class=\"hc_table_list_title\">";
		$this->forma.="<td></td>";
		$this->forma.="<td>1 Trimestre</td>";
		$this->forma.="<td>2 Trimestre</td>";
		$this->forma.="<td>3 Trimestre</td>";
		$this->forma.="</tr>";

    $i=0;
    while(!$source->EOF)
       {
			    if($i % 2) $estilo='hc_list_oscuro';
					else $estilo='hc_list_claro';
			    $dato=$source->fields[0];
					$objeto=$source->fields[1];
          $figura='';
					switch ($objeto)
					     {
								case "texto": {
												$figura="<input name=\"ctg\" type=\"text\" id=\"ctg\" size=\"10\" class=\"input-text\">";
												break;
								}
								case "combos": {
												$figura="<select name=\"parto\" align=\"left\"  class=\"select\"><option value=1>Si</option><option value=0>No</option>";
												break;
								}
								case "combo+": {
												$figura="<select name=\"parto\" align=\"left\"  class=\"select\"><option value='+'>+</option><option value='-'>-</option>";
												break;
								}

            }
					$this->forma.="<tr class=\"$estilo\">";
					$this->forma.="<td class=\"label\">&nbsp;$dato</td>";
					$this->forma.="<td align=\"center\">$figura</td>";
					$this->forma.="<td>&nbsp;</td>";
					$this->forma.="<td>&nbsp;</td>";
					$this->forma.="</tr>";
					$i++;
          $source->MoveNext();
				}
		$this->forma.="</table>";
		$this->forma .= ThemeCerrarTablaSubModulo();
    return true;
	}

}

?>
