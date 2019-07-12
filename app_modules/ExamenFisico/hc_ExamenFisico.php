<?php

session_start();

IncludeClass("signos",NULL,"hc","ExamenFisico"); 
IncludeClass("signos_HTML","HTML","hc","ExamenFisico"); 

class ExamenFisico extends hc_classModules
{
/**
* Esta funciï¿½ Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function ExamenFisico()
	{
		return true;
	}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		return true;
	}

	
     function GetConsulta()
     { 
          $signo_HH = new signos_HTML($this);
          $signo1 = new signos($this);
          $b_solo = $signo1->ConsultaExamenes(2);
          $hallazgo_solo = $signo1->ConsultarHallazgo(2);
          $ConsultaHTML  = $signo_HH->frmConsulta($b_solo,$hallazgo_solo);
          if($ConsultaHTML == false)
          {
               return true;
          }
          return $ConsultaHTML;
     }


/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

     function GetReporte_Html()
     { 
          $signo_HH=new signos_HTML($this);
          $signo1=new signos($this);
          $b_solo=$signo1->ConsultaExamenes(3);
          $hallazgo_solo=$signo1->ConsultarHallazgo(3);
          $imprimir=$signo_HH->frmHistoria($b_solo,$hallazgo_solo);
          if($imprimir==false)
          {
               return true;
          }
          return $imprimir;
     }

/**
* Esta funciï¿½ retorna la presentaciï¿½ del submodulo (consulta o inserciï¿½).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acciï¿½ a realizar.
*/
	
	function GetForma()
	{
		//echo "aqui comienza todo";
    $pfj=$this->frmPrefijo;
		echo "<br><br>";
		//var_dump($this);
    $signo_H=new signos_HTML($this);
		$signo1=new signos($this);
		$asistemas=$signo1->ConsultaSignos();
		 $b_todos_no_actual=$signo1->ConsultaExamenes(1);
		 $b_solo_actual=$signo1->ConsultaExamenes(2);
    //var_dump($b_solo_actual);      
     $profesional=$signo1->ConsultarNombre();
		 $hallazgo_todos_no_actual=$signo1->ConsultarHallazgo(1);
     //var_dump($hallazgo_todos_no_actual);      
		$hallazgo_solo_actual=$signo1->ConsultarHallazgo(2);
    
          if(empty($asistemas))
          {
            //$this->salida.="no se pudo realizar la consulta de sistemas";
          }
          if(empty($b_todos_no_actual))
          {
                   
            //$this->salida.="no saliron registros no actuales";
             //$b_todos_no_actual=0;
          }
		      if(empty($b_solo_actual))
          {
            //$this->salida.="no se pudo realizar la consulta de registro actual ";
            $b_solo_actual=0; 
          }
          if(empty($profesional))
          {
          //  $this->salida.="no se pudo realizar la consulta de profesional";
          }
		      if(empty($hallazgo_todos_no_actual))
          {
            //$this->salida.="no se pudo realizar la consulta hallazgos no actual";
            $hallazgo_todos_no_actual=0;
          } 
		      if(empty($hallazgo_solo_actual))
          {
            //$this->salida.="no se pudo realizar la consulta hallazo solo actual";
            $hallazgo_solo=0;
          }
		$this->salida.=$signo_H->Forma($this,$asistemas,$b_todos_no_actual,$b_solo_actual,$hallazgo_todos_no_actual,$hallazgo_solo_actual);
		//$this->salida.=$signo_H->frmConsulta($b_solo,$hallazgo_solo);
		$this->RegistrarSubmodulo($this->GetVersion());
		return true;
	}
			
// fin de la clase
}
?>