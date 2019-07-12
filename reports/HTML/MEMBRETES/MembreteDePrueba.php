<?php
//Membrete de prueba formato HTML


class MembreteDePrueba_Membrete 
{  
	//PARAMETROS PROPIOS DEL MEMBRETE
	var $titulo;
	var $subtitulo;
	//constructor
	function MembreteDePrueba_Membrete($datos_membrete=array())
	{
		$this->titulo=$datos_membrete['titulo'];
		$this->subtitulo=$datos_membrete['subtitulo'];
		return true;
	}
	
	//metodo que retorna el html del membrete
	function GetMembrete()
	{
		global $_ROOT;
		$logo = $_ROOT .'images/logos_tunal.png';
		$HEADER ="<TABLE width='100%' border=0 >\n";
		$HEADER.="  <TR>\n";
		$HEADER.="     <TD colspan='1'  align='left'><img src='$logo' border=0></TD>\n";
		$HEADER.="  </TR>\n";
		if(!empty($this->titulo))
		{
			$HEADER.="  <TR>\n";
			$HEADER.="     <TD align='center'>" . $this->titulo . "</TD>\n";
			$HEADER.="  </TR>\n";
		}
		if(!empty($this->subtitulo))
		{
			$HEADER.="  <TR>\n";
			$HEADER.="     <TD align='center'>" . $this->subtitulo . "</TD>\n";
			$HEADER.="  </TR>\n";
		}		
		$HEADER.="</TABLE><BR>\n";
		
		return $HEADER;			
	}
	
}//fin de la clase