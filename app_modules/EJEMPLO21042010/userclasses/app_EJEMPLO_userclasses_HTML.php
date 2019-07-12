<?php

class app_EJEMPLO_userclasses_HTML extends app_EJEMPLO_user
{

    function app_EJEMPLO_user_HTML()
    {
      $this->app_EJEMPLO_user(); //Constructor del padre 'modulo'
        $this->salida='';
        return true;
    }


    function forma1()
     {
        $ThemeImages = GetThemePath() . "/images";
        $this->salida  .= ThemeAbrirTabla('Sistema Integral de Información en Salud','500');
        $this->salida  .="<br>";
        $this->salida  .="<br>";
        $this->salida  .="<table width='300' height='200' border='0' align='center'>";
        $this->salida  .="    <tr>";
        $this->salida  .= "        <td width='150' height='200' background=\"$ThemeImages/logo_grande/logo_grande.png\">";
        $this->salida  .="        <div align='center'><font color='#999999'  size='+7'>";
        $this->salida  .="        <strong></strong></font></div></td>";
        $this->salida  .="    </tr>";
        $this->salida  .="    <tr>";
        $this->salida  .= "        <td width='150'>";
        $this->salida  .="        </td>";
        $this->salida  .="    </tr>";
        $this->salida  .="</table><br><br>";
				$accion = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','main',array("estacion"=>'1'));

			//	$this->salida  .="<div><a href='$accion'>GO TO ESTATION</a></div>";
        $this->salida .= ThemeCerrarTabla();
        //$this->Grafica();
				//$arr=$this->GetDatosDias_X_Cargos(3700);
			//	print_r($arr);

				//IncludeLib("jpgraph/carlos_graphic");
				//$RutaPresionDiastolica=Grafica();
				//file:/mnt/pc1/SIIS/classes/jpgraph-1.14/docs/html/exframes/frame_scatterex2.html
				$this->salida.="  <td>";
				//$this->salida.="    <img src=$RutaPresionDiastolica border='1'>"; //aqui se imprime para mostrar el grafico
				$this->salida.="  </td>";
        return true;
    }
    
    function Grafica()
    {
        //$this->salida.="PRUEBA HOLA";
       return true;
    }



			/**
	*
	*/

		/*
		* funcion demo.
		*/
		function GetDatosDias_X_Cargos($ingreso)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.cargo,a.fecha_ingreso,a.fecha_egreso,a.precio,a.cama,b.pieza,b.ubicacion
									FROM movimientos_habitacion a,camas b WHERE
									a.ingreso='$ingreso'
									AND a.cama=b.cama
									ORDER BY fecha_ingreso;";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al traer los cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}
				while(!$result->EOF)
				{
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				$k=0;
				for($i=0;$i<sizeof($var);)
				{
					 	$fecha_ingreso=explode(" ",$var[$i][fecha_ingreso]);
  	    		$fecha_ingreso_anterior=explode(" ",$var[$i-1][fecha_ingreso]);

				   if($i==0)
					 {
							$arr_dias_hosp[$fecha_ingreso[0]][$k][cargo]=$var[$i][cargo];
							$arr_dias_hosp[$fecha_ingreso[0]][$k][fecha_ingreso]=$var[$i][fecha_ingreso];
							$arr_dias_hosp[$fecha_ingreso[0]][$k][fecha_egreso]=$var[$i][fecha_egreso];
							$arr_dias_hosp[$fecha_ingreso[0]][$k][precio]=$var[$i][precio];
							$arr_dias_hosp[$fecha_ingreso[0]][$k][cama]=$var[$i][cama];
							$arr_dias_hosp[$fecha_ingreso[0]][$k][pieza]=$var[$i][pieza];
							$arr_dias_hosp[$fecha_ingreso[0]][$k][ubicacion]=$var[$i][ubicacion];
					 }
					 else
					 {
									if(strtotime($fecha_ingreso[0])==strtotime($fecha_ingreso_anterior[0]))
									{
										$k++;
									}
									else
									{
										$k=0;
									}
								$arr_dias_hosp[$fecha_ingreso[0]][$k][cargo]=$var[$i][cargo];
								$arr_dias_hosp[$fecha_ingreso[0]][$k][fecha_ingreso]=$var[$i][fecha_ingreso];
								$arr_dias_hosp[$fecha_ingreso[0]][$k][fecha_egreso]=$var[$i][fecha_egreso];
								$arr_dias_hosp[$fecha_ingreso[0]][$k][precio]=$var[$i][precio];
								$arr_dias_hosp[$fecha_ingreso[0]][$k][cama]=$var[$i][cama];
								$arr_dias_hosp[$fecha_ingreso[0]][$k][pieza]=$var[$i][pieza];
								$arr_dias_hosp[$fecha_ingreso[0]][$k][ubicacion]=$var[$i][ubicacion];
					 }
					 unset($fecha_ingreso);
					 unset($fecha_ingreso_anterior);
					$i++;
				}
				return $arr_dias_hosp;
	}




}//fin de la clase
?>

