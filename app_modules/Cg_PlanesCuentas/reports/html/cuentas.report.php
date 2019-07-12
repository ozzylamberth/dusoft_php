
<?php

	/**************************************************************************************
	* $Id: cuentas.report.php,v 1.3 2007/02/01 18:34:05 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
include_once "./app_modules/Cg_PlanesCuentas/classes/CuentasSQL.class.php";
	class cuentas_report 
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
	  function cuentas_report($datos=array())
	  {
			$this->datos=$datos;
	    //var_dump($this->datos);
      return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b> CATALOGO DE CUENTAS</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  { $consulta= new CuentasSQL();
      //echo "jejeje".var_dump($this->datos['empresa']);
      $vector=$consulta->BuscarCuentass($this->datos['tipo'],$this->datos['cuenta'],$this->datos['empresa']);
      //$vector=SessionGetVar("vector");
      
      
      //$vector=$consulta->BuscarCuentasStip($this->datos['offset'],$this->datos['tip_bus'],$this->datos['buscar'],$this->datos['empresaid']);
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-align:center\""; 
			$salida .= "                 <table   border='0' width=\"100%\" align=\"center\">\n";         
      $salida .= "                    <tr class=\"label_mark\" >\n";
      //border-left: 1px solid #000000; border-right: 1px solid #000000; border-top: 1px solid #000000;
      $salida .= "                      <td align=\"left\" width=\"10%\"style=\"border-left: 2px dotted #000000;border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        CUENTA N�";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\"width=\"39%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        DESCRIPCION";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\"width=\"10%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        PADRE";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\"width=\"5%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        NIVEL";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        TP";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        NAT";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        CC";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        TER";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\" style=\"border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        ACT";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\" style=\"border-right: 2px dotted #000000;border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                        DC";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";         
   
   
   $vectornivs=$consulta->ConsultarNivelesSegunEmpresa($this->datos['empresa']);
   $tableniveles=array();
   for($i=1;$i<(count($vectornivs)+1);$i++)
   {
     $tableniveles[$i]=$vectornivs[$i-1]['digitos'];
    
   }
   
   
   for($i=0;$i<sizeof($vector);$i++)
   {   
       
      $salida .= "                    <tr>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\" border-left: 2px dotted #000000;border-right: 2px dotted #000000;border-bottom: 2px dotted #000000;border-top: 2px dotted #000000;\">\n";
      $salida .= "                     ".$vector[$i]['cuenta']."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                        ".$vector[$i]['descripcion'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $padre=$vector[$i]['nivel']-1;
      if($padre==0)
      {
      $salida .= "                        &nbsp;";
      }
      else
      {
       $digitos=$tableniveles[$padre];
       $cuentapadre = substr ($vector[$i]['cuenta'],0,$digitos); 
       $salida .="                     ".$cuentapadre."";
      }
      
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
      $salida .= "                     ".$vector[$i]['nivel']."";
      $salida .= "                      </td>\n";
      if($vector[$i]['sw_cuenta_movimiento']==1)
        { 
            $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            $salida .= "                         <sub>M</sub>";
        } 
       else
        {
            $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            $salida .= "                         <sub>T</sub>";
        }
      $salida .= "                      </td>\n";
      if($vector[$i]['sw_naturaleza']=='C')
       { 
            $salida .= "                      <td align=\"center\" class=\"label\">\n";
            $salida .= "                         <sub>C</sub>";
       } 
      else
       {
         if($vector[$i]['sw_naturaleza']=='D')
           { 
             $salida .= "                      <td align=\"center\" class=\"label\">\n";
             $salida .= "                         <sub>D</sub>";
           }
         else
           {
             $salida .= "                      <td align=\"center\" class=\"label\">\n";
             $salida .= "                        &nbsp;";
           }
       }
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" class=\"label\">\n";
        if($vector[$i]['sw_centro_costo']>=1)
        {
          
          $salida .= "                          <sub>1</sub>\n";       
          
        }
        else
        {
          
          $salida .= "                          <sub>0</sub>\n";       
          
        }
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" class=\"label\">\n";
       if($vector[$i]['sw_tercero']>=1)
        {
          
          $salida .= "                          <sub>1</sub>\n";       
          
        }
       else
       {
         
         $salida .= "                          <sub>1</sub>\n";       
         
       }
       $salida .= "                      </td>\n";
       $salida .= "                      <td align=\"center\" class=\"label\">\n";
       if($vector[$i]['sw_estado']==0)
        { 
         
          $salida .= "                          <sub>0</sub>\n";       
         
        } 
       elseif($vector[$i]['sw_estado']==1)
        {
         
         $salida .= "                          <sub>1</sub>\n";       
         
        }
       $salida .= "                      </td>\n";
       $salida .= "                      <td align=\"center\" class=\"label\">\n";
       if($vector[$i]['sw_documento_cruce']>=1)
        {
         
            $salida .= "                          <sub>1</sub>\n";       
         
        
        }
       else
       {
         
         $salida .= "                          <sub>0</sub>\n";       
         
       }
       $salida .= "                    </td>\n";
       $salida .= "                    </tr>\n";
     }   
      $salida .= "</table>\n";
      ////////////////////////////////////////////////////////////
			
	    ECHO $salida;
      //return $salida;
      EXIT;
		}
	
		
	  //AQUI TODOS LOS METODOS QUE USTED QUIERA
	  //---------------------------------------
	}

?>
