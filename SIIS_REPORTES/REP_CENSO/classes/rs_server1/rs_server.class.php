<?php 
/* 
    Clase rs_server, autor Antonio Cortés, basado en un desarrollo original
        de Brent Ashley [jsrs@megahuge.com]
    
    Se ha optado por utilizar una clase para poder extenderla segun nuestras
    necesidades ...

*/

// Original Concept:
// jsrsServer.php - javascript remote scripting server include
//
// Orginal Author:  Brent Ashley [jsrs@megahuge.com]
// PHP version   :  Sébastien Cramatte [sebastien@webeclaireur.com] 
//		    Pierre Cailleux [cailleux@noos.fr] 
// Date		 :  May 2001 
// 
// see jsrsClient.js for version info
//
//  see license.txt for copyright and license info
//  Modified by Dr Zippie 2003 ... An object system ... optimized


class  rs_server {
    var $allowed_functions = array();
    var $method ;
    var $parameters ;
    function rs_server( $allowed_functions = array())  {
        $this->allowed_functions = $allowed_functions;
        $this->method = (isset($_POST['RS_Function']) ? $_POST['RS_Function'] : "-1");
        $this->parameters = (isset($_POST['RS_Parameters']) ? $_POST['RS_Parameters'] : array() );
    }
    function action() {
        
        if( method_exists( $this, $this->method )) {
            $result = $this->{$this->method}( $this->parameters );
            $error = false ;
        } else {
            $result = "The selected function doesn't exists";
            $error = true;
        }
        if ($error) {
            $this->return_error( $result ) ;
        } else {
            $this->return_ok( $result );
        }
    }
    function return_ok( $string ) {
        $C = (isset($_POST['RS_Container']) ) ? $_POST['RS_Container'] : "";
        echo "<html><head></head><body onload=\"p=document.layers?parentLayer:window.parent;p.jsrsLoaded('" 
            . $C . "');\">jsrsPayload:<br>" 
            . "<form name=\"jsrs_Form\"><textarea name=\"jsrs_Payload\">" 
            . $this->escape_string($string) . "</textarea></form></body></html>" ;
        exit();
    }
    function return_error( $string )  {
        $C = (isset($_REQUEST['C']) ? $_REQUEST['C'] : "");
        $string = "jsrsError: " . ereg_replace("\"", "\\\"", ereg_replace("\'","\\'",$string)); 
        echo "<html><head></head><body " 
             . "onload=\"p=document.layers?parentLayer:window.parent;p.jsrsError('" . $C . "','" 
             . urlencode($string) . "');\">"
             . $string . "</body></html>" ;
        exit();
    }
    function escape_string( $string ) {
        return ereg_replace( "\/" , "\\/",ereg_replace( "&", "&amp;", $string ));     
    }
}

?>