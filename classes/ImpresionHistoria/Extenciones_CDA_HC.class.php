<?php

/**
* $Id: Extenciones_CDA_HC.class.php,v 1.1.1.1 2009/09/11 20:37:04 hugo Exp $
*/

/**
* Clase con metodos comunes para la generacion  CDA-HL7 de un submodulo
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/ 
class Extenciones_CDA_HC
{
    /**
    * Cadena XML-CDA a retornar 
    *
    * @var string $salida
    * @access private
    */
    var $salida;
    
    /**
    * Tipo del retorno (Por Fechas, o Completo) 
    *
    * @var boolean $retorno_por_fechas
    * @access private
    */
    var $retorno_por_fechas = false;    
    
    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public    
    */  
    function Extenciones_CDA_HC()
    {
        return true;
    }
    
    /**
    * Retorna el valor de la propiedad $retorno_por_fechas
    *
    * @return boolean True si es fechado, False si es completo.
    * @access public    
    */  
    function GetTipoReorno()
    {
        return $this->retorno_por_fechas;
    }    
    
    /**
    * Funcion para solicitar una estructura xml-CDA
    *
    * Este metodo es invocado con los argumentos que defin que tipo de informacion
    * se requiere del submodulo ejemplo los datos de este submodulo para una evolucion, un ingreso o un paciente
    * USO DEL METODO
    *    $tipo='evolucion_id' $datos=array('evolucion_id'=>NumeroDeLaEvolucionRequerida) -- Historia Completa de una evolucion
    *    $tipo='epicrisis' $datos=array('ingreso'=>NumeroDelIngresoDelPaciente) -- Epicrisis de un ingreso
    *    $tipo='full_ingreso' $datos=array('ingreso'=>NumeroDelIngresoDelPaciente)  -- Historia Completa de un Ingreso
    *    $tipo='full_historia' $datos=array('paciente_id'=>NoIdDelPaciente, 'tipoidpaciente'=>TipoIdDelPaciente)  -- Historia Completa de un Paciente
    *    $tipo='resumen_historia' $datos=array('ingreso'=>NumeroDelIngresoDelPaciente)  -- Resumen Historia de un Paciente    
    *
    * @param string $tipo El tipo de solicitud opciones('evolucion_id' | 'epicrisis'  | 'full_ingreso' | 'full_historia' | 'resumen_historia' )
    * @param array $datos Los argumentos necesarios para el tipo de solicitud seleccionado
    * @return string La estructura XML-CDA de la consulta
    * @access public    
    */     
    function GetXML($tipo,$datos=array())
    {
        switch($tipo)
        {
            case 'evolucion_id':
                if(empty($datos['evolucion_id'])) return false;
                return $this->GetCDA_Evolucion($datos['evolucion_id']);            
            break;

            case 'epicrisis':
                if(empty($datos['ingreso'])) return false;
                return $this->GetCDA_Epicrisis($datos['ingreso']);
            break;
            
            case 'full_ingreso':
                if(empty($datos['ingreso'])) return false;
                return $this->GetCDA_Full_Ingreso($datos['ingreso']);
            break;
            
            case 'full_historia':
                if(empty($datos['paciente_id']) || empty($datos['tipoidpaciente'])) return false;
                return $this->GetCDA_Full_Historia($datos['paciente_id'],$datos['tipoidpaciente']);
            break;
            
            case 'resumen_historia':
                if(empty($datos['paciente_id']) || empty($datos['tipoidpaciente'])) return false;
                return $this->GetCDA_Resumen_Historia($datos['paciente_id'],$datos['tipoidpaciente']);
            break;
            
            default:
                return false;                        
        }
    }

}//fin de la clase

?>
