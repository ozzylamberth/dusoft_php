<?php
function rotacion(){
        $medicamentos_d=$_REQUEST['medicamentos'];
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=rotacion_excel.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $html_rt="<table>";
        $html_rt.="<tr  style='border: medium' >";
        $html_rt.="<td>";
        $html_rt.="<b>CODIGO</b>";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="<b>MEDICAMENTO</b>";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="<b>MOLECULA</b>";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="<b>PROMEDIO MES</b>";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="<b>STOCK FARMACIA</b>";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="<b>PEDIDO 60 DIAS</b>";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="";
        $html_rt.="</td>";
        $html_rt.="<td>";
        $html_rt.="<b>STOCK BODEGA</b>";
        $html_rt.="</td>";
        $html_rt.="</tr>";
        foreach ($medicamentos_d as $producto => $detalle) {
        $existencia = $detalle['stock_farmacia'];
        $total_egresos = 0;
        $totales=0;
        $meses=count($detalle['cantidad_total_despachada']);
        for ($i = 1; $i <= $meses; $i++) {
            $total_egresos = $total_egresos + $detalle['cantidad_total_despachada'][$i];                    
            $totales+=$detalle['cantidad_total_despachada'][$i];
        }
        $promedio_mes=rount($totales/$meses);
        $promedio_dia=ceil($promedio_mes/30); 
        $pedido_60=(($promedio_dia*60)-$existencia);
        $pedido_60=rount (($pedido_60<0)?0:$pedido_60);
         $html_rt.="<tr>";  
         $html_rt.="<td>";
         $html_rt.=$detalle['codigo_producto'];
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.=$detalle['descripcion_producto'];
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.=$detalle['molecula'];
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.=$promedio_mes;
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.=$existencia;
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.=$pedido_60;
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.="";
         $html_rt.="</td>";
         $html_rt.="<td>";
         $html_rt.=$detalle['stock_bodega'];
         $html_rt.="</td>";
         $html_rt.="</tr>";    
        }
        $html_rt.="</table>";
        echo $html_rt;
    }
?>
