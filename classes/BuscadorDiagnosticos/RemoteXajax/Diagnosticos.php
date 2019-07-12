<?php

    function obtenListDiagnos($nombDiag){
    
        //$bds = new BuscadorDiagnosticosSQL();
        $bdh = new BuscadorDiagnosticos();
            
        //$bds->ObtenDiagnosticos("AES");
    
        $objResponse = new xajaxResponse();
        
        //$objResponse->alert("Esto es un xajax!!!");
        
        $html .= $bdh->frmListDiagnost($nombDiag);
        
        //$html = "hey";
        
        $objResponse->assign("listDiagnost","innerHTML",$html);
        return $objResponse;
    }
    
    
    function enviarDiagnos($diagnosId, $claseDiagnostId, $pacientId, $tipoPacientId, $referenId){
        
        ///$bds = new BuscadorDiagnosticosSQL();        
            
        $objResponse = new xajaxResponse();
                
        //$html = $idDiagnost;
        
        $html = "diagnosId: ".$diagnosId.", pacientId: ".$pacientId.", tipoPacientId: ".$tipoPacientId;
        
        ///$bds->IngrDiagnostico($diagnosId, $claseDiagnostId, $pacientId, $tipoPacientId, $referenId);
        
        //$objResponse->assign("listDiagnost","innerHTML",$html); 
        $objResponse->assign("diagnostPacie","innerHTML",$html);
        
        
            
        return $objResponse;
    } 
?>