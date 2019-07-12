<?php
		$_ROOT='../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		IncludeClass('MovimientosSQL',null,'app','Cg_Movimientos');
    //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
    $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	  IncludeClass("ContabilizarDocumento","ContabilizacionDeDocumentos");
    IncludeClass("ContabilizacionDeDocumentos","ContabilizacionDeDocumentos");
		IncludeFile($fileName);
   	$empresa_id      = $_REQUEST['Empresa_id'];
    $prefijo         = $_REQUEST['Prefijo'];
    $lapso           = $_REQUEST['Lapso'];
    if($_REQUEST['Actualizar']==='true')
    {
      $actualizar      = true;
    }
    else
    {
      $actualizar      = false;
    }
    
    $TITLE="DETALLE CONTABILIZACION LAPSO CONTABLE ".$lapso."";
    print(ReturnHeader($TITLE));
		print(ReturnBody());
		//echo "EMPRESA".$empresa_id."prefijo".$prefijo."lapso".$lapso."actualizar".$actualizar;
    $consulta=new MovimientosSQL();
    $cad="";
    $contabilizador= new ContabilizacionDeDocumentos();
    $resultado=$contabilizador->ContabilizarLapsoDocumento($empresa_id,$prefijo,$lapso,$actualizar);
    $salida =ThemeAbrirTabla('DETALLE CONTABILIZACION');
    $salida .= "                  <table  width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                      <td colspan=2 align=\"center\">\n";
    $salida .= "                      CONTABILIZACION DE DOCUMENTOS DEL SISTEMA SIIS LAPSO CONTABLE: &nbsp;&nbsp;".$lapso;
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
    $salida .= "                      <td  class=\"normal_10AN\" colspan=2 align=\"center\">\n";
    $descripcione=$consulta->TipoDocumento($tip_doc);
    $documentus=$consulta->Documentus($prefijo);
    $salida .= "                       TIPO DE DOCUMENTO:&nbsp;&nbsp;".$descripcione[0]['descripcion']."&nbsp;&nbsp;&nbsp;&nbsp;PREFIJO:&nbsp;&nbsp;".$prefijo."&nbsp;&nbsp;&nbsp;&nbsp;DOCUMENTO:&nbsp;&nbsp;".$documentus[0]['descripcion'];
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";       
    
   
    if($resultado === false)
     {
        $salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
        $salida .= "                      <td  class=\"label_error\" colspan=2 align=\"center\">\n";
        $salida.= "ERRORES : " . $contabilizador->Err() . "<br>" . $contabilizador->ErrMsg() . "<br>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";  
     }
     
    $RETORNOS = $contabilizador->GetRetornoLoteContabilizacion();
    if(is_array($RETORNOS))
    {   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                      <td  class=\"normal_10AN\" colspan=2 align=\"center\">\n";
        $salida .= "                         NUMERO DE DOCUMENTOS CONTABILIZADOS : ".count($RETORNOS);
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";

        foreach($RETORNOS as $numero => $detalle)
        {
            $salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $salida .= "                      <td  class=\"normal_10AN\" align=\"left\">\n";
            
            if($detalle['RESULTADO'])
            {
                $salida .= "".$prefijo."&nbsp;".$numero."";
                $salida .= "                      </td>\n";
                $salida .= "                      <td>\n";
                $salida .= "                      ".$detalle['DETALLE']."";
                $salida .= "                      </td>\n";
            }
            else
            {
                $salida .= "".$prefijo."&nbsp;".$numero."";
                $salida .= "                      </td>\n";
                $salida .= "                      <td>\n";
                $salida .= "                      ".$detalle['TITULO']."&nbsp;".$detalle['DETALLE'];
                $salida .= "                      <td>\n";
            }
            $salida .= "                    </tr>\n";       
        }
    }
    else
    {
         $salida .= "                    <tr>\n"; 
         $salida .= "                    <td>\n"; 
         $salida .= "NO SE CONTABILIZARON DOCUMENTOS";
         $salida .= "                    </td>\n"; 
         $salida .= "                    </tr>\n"; 
    }
     
    $salida .= "                  </table>"; 
    $salida .=ThemeCerrarTabla();  
    echo $salida; 
     
   
    
    
    
    


	
	print(ReturnFooter());
?>

