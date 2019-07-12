<?php
//Reporte de prueba formato HTML

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class prueba_report 
{ 
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();
	
    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function prueba_report($datos=array())
    {
		$this->datos=$datos;
        return true;
    }
		
	
// 	//METODO PRIVADO NO MODIFICAR
// 	function GetParametrosReport()
// 	{
// 		$parametros = array('title' => $this->title,'author' => $this->author,'sizepage' => $this->sizepage,'Orientation'=> $this->Orientation,'grayScale' => $this->grayScale,'headers' => $this->headers,'footers' =>$this->footers )
// 		return $parametros;
// 	}
// 	
// 	
	
	//FUNCION GetMembrete() - SI NO VA UTILIZAR MEMBRETE EXTERNO PUEDE BORRAR ESTE METODO
	//RETORNA EL MEMBRETE DEL DOCUMENTO
	//
	// SI RETORNA FALSO SIGNIFICA EL REPORTE NO UTILIZA MEMBRETE EXTERNO AL MISMO REPORTE.
	// SI RETORNA ARRAY HAY DOS OPCIONES:
	//
	// 1. SI $file='NombreMembrete' EL REPORTE UTILIZARA UN MEMBRETE UBICADO EN
	//    reports/HTML/MEMBRETES/NombreMembrete y el arraglo $datos_membrete
	//    seran los parametros especificos de este membrete.
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>'NombreMembrete','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE','subtitulo'=>'SUBTITULO'));
	// 				return $Membrete;
	// 			}	
	//
	// 2. SI $file=false  SIGNIFICA QUE UTILIZA UN MEMBRETE GENERICO QUE CONCISTE EN UN
	//    LOGO (SI LO HAY), UN TITULO, UN SUBTITULO Y UNA POSICION DEL LOGO (IZQUIERDA,DERECHA O CENTRO)
	//    LOS PARAMETROS DEL VECTOR datos_membrete DEBN SER:
	//    titulo    : TITULO DE REPORTE
	//    subtitulo : SUBTITULO DEL REPORTE
	//    logo      : LA RUTA DE UN LOGO DENTRO DEL DIRECTORIO images (EN EL RAIZ)
	//    align     : POSICION DEL LOGO (left,center,right)
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
	// 																		'subtitulo'=>'subtitulo'
	// 																		'logo'=>'logocliente.png'
	// 																		'align'=>'left'));
	// 				return $Membrete;
	// 			}
	
// 	function GetMembrete()
// 	{
// 		$Membrete = array('file'=>'MembreteDePrueba','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
// 																'subtitulo'=>'subtitulo',
// 																'logo'=>'logocliente.png',
// 																'align'=>'left'));
// 		return $Membrete;
// 	}
	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
																'subtitulo'=>'subtitulo',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}
    //FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    function CrearReporte()
    {
		$Salida .= "<table width='80%' border='1' align='center'>\n";
		$Salida .= "    <tr>\n";
		$Salida .= "      <td colspan='2' align='center'>TITULO DEL REPORTE</td>\n";
		$Salida .= "    </tr>\n";
		$Salida .= "    <tr>\n";
		$Salida .= "      <td width='20%'>NOMBRE</td>\n";
		$Salida .= "      <td width='80%'>". $this->datos['NOMBRE'] ."</td>\n";
		$Salida .= "    </tr>\n";
		$Salida .= "    <tr>\n";
		$Salida .= "      <td>APELLIDO</td>\n";
		$Salida .= "      <td>". $this->datos['APELLIDO'] ."</td>\n";
		$Salida .= "    </tr>\n";
		$Salida .= "    <tr>\n";
		$Salida .= "      <td>SEXO</td>\n";
		$Salida .= "      <td>". $this->datos['SEXO'] ."</td>\n";
		$Salida .= "    </tr>\n";
		$Salida .= "    <tr>\n";
		$Salida .= "      <td colspan='2' align='JUSTIFY'>Esto es una linea de texto un poco larga sin\n";
		$Salida .= "	   necesidad de ser copiada de otra parte por el contrario perdi tiempo\n";
		$Salida .= "	   digitandola para desentumir mis deditos, ya que estaba engarrotados del frio\n";
		$Salida .= "	   tan tenas que hace en el centro de control.</td>\n";
		$Salida .= "    </tr>	\n";
		$Salida .= "</table>\n";
		$Salida .= "\n";		
        return $Salida;
    }


    
    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}

?>
