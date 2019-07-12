<?php
  /**
  * $Id: Pagare.report.php,v 1.9 2005/09/30 16:12:39 darling Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  *
  */
  IncludeClass("ClaseUtil");
  class Pagare_report
  {
    var $datos;
  	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
  	var $title       = '';
  	var $author      = '';
  	var $sizepage    = 'leter';
  	var $Orientation = '';
  	var $grayScale   = false;
  	var $headers     = array();
  	var $footers     = array();
    /**
    *
    */
    function Pagare_report($datos=array())
    {
      $this->datos=$datos;
			return true;
    }
    /**
    *
    */
    function GetMembrete()
    {
      $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'',
																'align'=>'center'));
      return $Membrete;
    }
    /**
    *
    */
    function CrearReporte()
    {
			IncludeLib('funciones_pagares');
			IncludeLib('funciones_admision');
			$smarty=getSmarty(dirname (__FILE__));
			$smarty->compile_check = true;			
			
			$ciudad = $this->NombreCiudad(GetVarConfigAplication('DefaultPais'),GetVarConfigAplication('DefaultDpto'),GetVarConfigAplication('DefaultMpio'));
			$smarty->assign("ciudad",ucfirst(strtolower($ciudad)));	
			$smarty->assign("Empresa",GetVarConfigAplication('Cliente')	);				
		

      $ctl = new ClaseUtil();
      $pagare = BuscarDatosPagares($this->datos['empresa'],$this->datos['prefijo'],$this->datos['numero']);
      $cuotas = BuscarCuotasPagare($this->datos['empresa'],$this->datos['prefijo'],$this->datos['numero']);
      $respon = BuscarDeudoresPagare($this->datos['empresa'],$this->datos['prefijo'],$this->datos['numero']);
      $intereses = ModuloGetVar('app','Pagares','intereses');
      $intereses_mora = ModuloGetVar('app','Pagares','intereses_mora');
      $f = explode(' ',$pagare['fecha_registro']);
      $v = explode('-',$f[0]);
      
      $letras = $ctl->num2letras($pagare['valor'],false)." pesos "; 
      
      $forma_pago =  " en ".$cuotas['cantidad']." cuotas ".$pagare['periodicidad_representativa']." de ";
      $cuotas_desc = " cuotas ".$pagare['periodicidad_representativa'];
      
      $firma = "";
      for($i = 0; $i< 50; $i++)
        $firma .= "&nbsp;";
      
      $firmaI = "";
      for($i = 0; $i< 100; $i++)
        $firmaI .= "&nbsp;";
      
      $firmaII = "";
      for($i = 0; $i< 30; $i++)
        $firmaII .= "&nbsp;";
        
      $smarty->assign("prefijo",$this->datos['prefijo']);
      $smarty->assign("pagare",$this->datos['numero']);
      
      if($pagare['valor'] != 0)
      {
        $smarty->assign("valor_pagare",FormatoValor($pagare['valor']));
        $smarty->assign("valor_letras",$letras);
        $smarty->assign("forma_pago",FormatoValor($pagare['formapago']));
        $smarty->assign("valor_cuota",FormatoValor($cuotas['valor_cuota']));
        $smarty->assign("cuota_letras",$ctl->num2letras(round($cuotas['valor_cuota']),false)." pesos ");
        $smarty->assign("cuotas_desc",$cuotas_desc);
        $smarty->assign("cuotas",$cuotas['cantidad']);
        $smarty->assign("dia_pagare",$v[2]);
        $smarty->assign("mes_pagare",GetMes($v[1]));	
        $smarty->assign("anyo_pagare",$v[0]);	
      }
      else
      {
        $smarty->assign("valor_pagare",$firmaII);
        $smarty->assign("valor_letras",$firmaI);
        $smarty->assign("forma_pago",$firmaII);
        $smarty->assign("valor_cuota",$firmaII);
        $smarty->assign("cuota_letras",$firmaII);
        $smarty->assign("cuotas","&nbsp;&nbsp;&nbsp;");
        $smarty->assign("cuotas_desc"," cuotas mensuales ");
        $smarty->assign("dia_pagare","&nbsp;&nbsp;&nbsp;&nbsp;");
        $smarty->assign("mes_pagare","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");	
        $smarty->assign("anyo_pagare","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      }
     
      $smarty->assign("nombre_deudor",$respon[1][0]['nombre']);
      $smarty->assign("identificacion_deudor",$respon[1][0]['tipo_id_tercero']." ".$respon[1][0]['tercero_id']);
      $smarty->assign("direccion_deudor",$respon[1][0]['direccion_residencia']);
      $smarty->assign("telefono_deudor",$respon[1][0]['telefono_residencia']);
      							
      $smarty->assign("fecha_pagare",$v[2]."/".$v[1]."/".$v[0]);	
      if($v[2] == "01")
        $pronuncia = " un ";
      else
        $pronuncia = $ctl->num2letras($v[2],false);
        
      $smarty->assign("dias_letras",$pronuncia." (".intval($v[2]).") ");					
      $smarty->assign("mes",GetMes($v[1]));	
      $smarty->assign("anyo",$v[0]);			
      $smarty->assign("dia1",date('d'));			
      $smarty->assign("mes1",GetMes(date('m')));			
      $smarty->assign("anyo1",date('Y'));			
      		
      $smarty->assign("intereses",$intereses);			
      $smarty->assign("intereses_mora",$intereses_mora);	      
      
      $smarty->assign("intereses_letras",$ctl->num2letras($intereses,false,true));			
      $smarty->assign("intereses_mora_letras",$ctl->num2letras($intereses_mora,false,true));			
      $smarty->assign("FechaFirmaPagare",$ciudad.", ".$v[2]." de ".GetMes($v[1])." de ".$v[0]);							
      
      
      $smarty->assign("nombre_codeudor",$respon[0][0]['nombre']);
      $smarty->assign("identificacion_codeudor",$respon[0][0]['tipo_id_tercero']." ".$respon[0][0]['tercero_id']);
      //$smarty->assign("direccion_deudor",$respon[1][1]['direccion_residencia']);
      $smarty->assign("telefono_codeudor",$respon[0][0]['telefono_residencia']);
      
      $f = explode(' ',$pagare['vencimiento']);
      $v = explode('-',$f[0]);					
      $smarty->assign("fecha_vencimiento",$v[2]." de ".GetMes($v[1])." de ".$v[0]);			
      $smarty->assign("firma",$firma);
			
      if(!empty($respon[0]))
      {
        foreach($respon[0] as $k => $dtl)
        {
          if(!$respon[$k+1])
            $htmI .= " y ";
          
          $htmI .= $dtl['nombre'] ;
          
          $html .= "<td>\n";
          $html .= "<u>".$firma ."</u><br>";
          $html .= "Nombre y apellidos: ".$dtl['nombre']."<br> ";
          $html .= "Documento de identificación: ".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." ";
          $html .= "</td>\n";
        }
      
        $htm1  = "<tr class=\"normal_12\">\n";
        $htm1 .= "  <td colspan=\"".$k."\">LOS CODEUDORES<br><br></td>\n";
        $htm1 .= "</tr>\n";
        $htm1 .= "<tr class=\"normal_12\">\n";
        $htm1 .= $html;
        $htm1 .= "</tr>\n";
      
        $smarty->assign("spanl","colspan='$k'");
        $smarty->assign("codeudores",$htm1);
        $smarty->assign("codeudores_uno","<u>".$htmI."</u>");
      }
      
			$salida=$smarty->fetch('pagare_coc.html.tpl');			
			unset($smarty);				
			return $salida;
	}
	
	function NombreCiudad($pais,$dpto,$mpio)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT municipio FROM tipo_mpios
								WHERE tipo_pais_id='$pais' and tipo_dpto_id='$dpto' and tipo_mpio_id='$mpio'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			return $result->fields[0];		
	}

//---------------------FIN CLASE--------------------------	
}
?>

