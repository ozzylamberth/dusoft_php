<?php
/**
 * $Id: NumeroEnLetras.class.php,v 1.2 2005/09/21 13:03:12 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 *
 * Paquete para convertir los números en letras
 */


/**
 * Clase para convertir
 *
 * @author Ehudes García<efgarcia@ipsoft-sa.com>
 * @version $Revision: 1.2 $
 * @package IPSOFT-SIIS-API
 */
class NumeroEnLetras{

    /**
     * Arreglo que relaciona la centena con su equivalencia en letras
     *
     * @var array NumerosCentenas
     * @access private
     */
    var $NumerosCentenas;

    /**
     * Arreglo que relaciona la unidad con su equivalencia en letras
     *
     * @var array NumerosUnidades
     * @access private
     */
    var $NumerosUnidades;

    /**
     * Arreglo que relaciona la decena con equivalencia en letras
     *
     * @var array NumerosDecenas
     * @access private
     */
    var $NumerosDecenas;

    /**
     * Numero a convertir
     *
     * @acccess private
     */
    var $Numero;

    /**
     * Valor en letras del numero
     *
     * @access private
     */
    var $Letras;

    /**
     * Unidad de la parte entera del número, tiene por default "pesos"
     *
     * @access public
     */
    var $UnidadEntera;

    /**
     * Unidad de la parte decima del número, tiene por default "centavos"
     *
     * @access public
     */
    var $UnidadDecimal;

    /**
     * Constructor de la clase
     *
     * @param double Numero
     */
    function NumeroEnLetras($Numero)
    {
        $this->error=0;
        $this->mensajeError="";
        $this->SetValorNumerico($Numero);
        $this->CargarArreglosEquivalencias();

    }//fin del constructor


    /**
     * Fija la unidad entera a utilizar
     *
     * @param string $UnidadEntera
     * @acccess public
     */
    function SetUnidadEntera($UnidadEntera)
    {
        $this->UnidadEntera=$UnidadEntera;
    }

    /**
     * Fija la unidad decimal a utilizar
     *
     * @param string $UnidadDecimal
     * @acccess public
     */
    function SetUnidadDecimal($UnidadDecimal)
    {
        $this->UnidadDecimal=$UnidadDecimal;
    }


    /**
     * Fija el valor numerico
     *
     * @param double float
     * @acccess public
     */
    function SetValorNumerico($Numero)
    {
        if(!is_numeric($Numero))
        {
            $this->error=1;
            $this->mensajeError="El valor ingresado no  es numerico";
            $this->Numero=0;
            $this->Letras="";
            return false;
        }
        if ($Numero >= 1000000000)
        {
            $this->error=2;
            $this->mensajerError="El número es mayor o igual a 1.000.000.000";
            $this->Numero=0;
            $this->Letras="";
            return false;
        }
        $this->Numero=$Numero;
        $this->Letras="";
    }//Fin del metodo SetValorNumerico

    /**
     * Carga los arreglos de equivalencias
     *
     * @access private
     */
    function CargarArreglosEquivalencias()
    {
        $this->NumerosCentenas[0] = "cero";
        $this->NumerosCentenas[1] = "uno";
        $this->NumerosCentenas[2] = "dos";
        $this->NumerosCentenas[3] = "tres";
        $this->NumerosCentenas[4] = "cuatro";
        $this->NumerosCentenas[5] = "cinco";
        $this->NumerosCentenas[6] = "seis";
        $this->NumerosCentenas[7] = "siete";
        $this->NumerosCentenas[8] = "ocho";
        $this->NumerosCentenas[9] = "nueve";
        $this->NumerosCentenas[10] = "diez";
        $this->NumerosCentenas[11] = "once";
        $this->NumerosCentenas[12] = "doce";
        $this->NumerosCentenas[13] = "trece";
        $this->NumerosCentenas[14] = "catorce";
        $this->NumerosCentenas[15] = "quince";
        $this->NumerosCentenas[20] = "veinte";
        $this->NumerosCentenas[30] = "treinta";
        $this->NumerosCentenas[40] = "cuarenta";
        $this->NumerosCentenas[50] = "cincuenta";
        $this->NumerosCentenas[60] = "sesenta";
        $this->NumerosCentenas[70] = "setenta";
        $this->NumerosCentenas[80] = "ochenta";
        $this->NumerosCentenas[90] = "noventa";
        $this->NumerosCentenas[100] = "ciento";
        $this->NumerosCentenas[101] = "quinientos";
        $this->NumerosCentenas[102] = "setecientos";
        $this->NumerosCentenas[103] = "novecientos";

        $this->NumerosUnidades[0] = "cero";
        $this->NumerosUnidades[1] = "un";
        $this->NumerosUnidades[2] = "dos";
        $this->NumerosUnidades[3] = "tres";
        $this->NumerosUnidades[4] = "cuatro";
        $this->NumerosUnidades[5] = "cinco";
        $this->NumerosUnidades[6] = "seis";
        $this->NumerosUnidades[7] = "siete";
        $this->NumerosUnidades[8] = "ocho";
        $this->NumerosUnidades[9] = "nueve";
        $this->NumerosUnidades[10] = "diez";
        $this->NumerosUnidades[11] = "once";
        $this->NumerosUnidades[12] = "doce";
        $this->NumerosUnidades[13] = "trece";
        $this->NumerosUnidades[14] = "catorce";
        $this->NumerosUnidades[15] = "quince";
        $this->NumerosUnidades[20] = "veinte";
        $this->NumerosUnidades[30] = "treinta";
        $this->NumerosUnidades[40] = "cuarenta";
        $this->NumerosUnidades[50] = "cincuenta";
        $this->NumerosUnidades[60] = "sesenta";
        $this->NumerosUnidades[70] = "setenta";
        $this->NumerosUnidades[80] = "ochenta";
        $this->NumerosUnidades[90] = "noventa";
        $this->NumerosUnidades[100] = "ciento";
        $this->NumerosUnidades[101] = "quinientos";
        $this->NumerosUnidades[102] = "setecientos";
        $this->NumerosUnidades[103] = "novecientos";

        $this->NumerosDecenas[0] = "cero";
        $this->NumerosDecenas[1] = "uno";
        $this->NumerosDecenas[2] = "dos";
        $this->NumerosDecenas[3] = "tres";
        $this->NumerosDecenas[4] = "cuatro";
        $this->NumerosDecenas[5] = "cinco";
        $this->NumerosDecenas[6] = "seis";
        $this->NumerosDecenas[7] = "siete";
        $this->NumerosDecenas[8] = "ocho";
        $this->NumerosDecenas[9] = "nueve";
        $this->NumerosDecenas[10] = "diez";
        $this->NumerosDecenas[11] = "once";
        $this->NumerosDecenas[12] = "doce";
        $this->NumerosDecenas[13] = "trece";
        $this->NumerosDecenas[14] = "catorce";
        $this->NumerosDecenas[15] = "quince";
        $this->NumerosDecenas[20] = "veinte";
        $this->NumerosDecenas[30] = "treinta";
        $this->NumerosDecenas[40] = "cuarenta";
        $this->NumerosDecenas[50] = "cincuenta";
        $this->NumerosDecenas[60] = "sesenta";
        $this->NumerosDecenas[70] = "setenta";
        $this->NumerosDecenas[80] = "ochenta";
        $this->NumerosDecenas[90] = "noventa";
        $this->NumerosDecenas[100] = "ciento";
        $this->NumerosDecenas[101] = "quinientos";
        $this->NumerosDecenas[102] = "setecientos";
        $this->NumerosDecenas[103] = "novecientos";
    }//Fin del CargarArreglosEquivalencias

    /**
     * Función que retorna la equivalencia en letras de las centenas
     *
     * @param integer VCentena
     * @return string
     * @access public
     */
    function Centenas($VCentena)
    {
        if ($VCentena == 1)
        {
            return $this->NumerosCentenas[100];
        }
        else If ($VCentena == 5)
        {
            return $this->NumerosCentenas[101];
        }
        else If ($VCentena == 7 )
        {
            return ( $this->NumerosCentenas[102]);
        }
        else If ($VCentena == 9)
        {
            return ($this->NumerosCentenas[103]);
        }
        else
        {
            return $this->NumerosCentenas[$VCentena];
        }
    }//Fin del metodo Centenas

    /**
     * Función que retorna la equivalencia en letras de las unidades
     *
     * @param integer VCentena
     * @return string
     * @access public
     */
    function Unidades($VUnidad)
    {
        $tempo=$this->NumerosUnidades[$VUnidad];
        return $tempo;
    }//Fin del metodo Unidades

    /**
     * Función que retorna la equivalencia en letras de las decenas
     *
     * @param integer VCentena
     * @return string
     * @access public
     */
    function Decenas($VDecena)
    {
        $tempo = ($this->NumerosCentenas[$VDecena]);
        return $tempo;
    }//Fin del metodo Decenas

    /**
     * Función que retorna la equivalencia en letras de un número sin
     * tener en cuenta la parte decimal
     *
     * @return string
     * @access public
     */
    function NumerosALetras($Numero)
    {
        $Decimales = 0;
        //$Numero = intval($Numero);
        $letras = "";

        while ($Numero != 0)
        {
            // '*---> Validación si se pasa de 100 millones

            If ($Numero >= 1000000000)
            {
                $letras = "Error en Conversión a Letras";
                $Numero = 0;
                $Decimales = 0;
            }

            // '*---> Centenas de Millón
            If (($Numero < 1000000000) And ($Numero >= 100000000))
            {
                If ((Intval($Numero / 100000000) == 1) And (($Numero - (Intval($Numero / 100000000) * 100000000)) < 1000000))
                {
                    $letras .= (string) "cien millones ";
                }
                Else
                {
                    //$letras = $letras & $this->Centenas(Intval($Numero / 100000000));
                    $letras = $letras . $this->Centenas(Intval($Numero / 100000000));
                    If ((Intval($Numero / 100000000) <> 1) And (Intval($Numero / 100000000) <> 5) And (Intval($Numero / 100000000) <> 7) And (Intval($Numero / 100000000) <> 9))
                    {
                        $letras .= (string) "cientos ";
                    }
                    Else
                    {
                        $letras .= (string) " ";
                    }
                }
                $Numero = $Numero - (Intval($Numero / 100000000) * 100000000);
            }

            // '*---> Decenas de Millón
            If (($Numero < 100000000) And ($Numero >= 10000000))
            {
                If (Intval($Numero / 1000000) < 16)
                {
                    $tempo = $this->Decenas(Intval($Numero / 1000000));
                    $letras .= (string) $tempo;
                    $letras .= (string) " millones ";
                    $Numero = $Numero - (Intval($Numero / 1000000) * 1000000);
                }
                Else
                {
                    $letras = $letras . $this->Decenas(Intval($Numero / 10000000) * 10);
                    $Numero = $Numero - (Intval($Numero / 10000000) * 10000000);
                    If ($Numero > 1000000)
                    {
                        $letras .=  " y ";
                    }
                }
            }

            // '*---> Unidades de Millón
            If (($Numero < 10000000) And ($Numero >= 1000000))
            {
                $tempo=(Intval($Numero / 1000000));
                If ($tempo == 1)
                {
                    $letras .= (string) " un millon ";
                }
                Else
                {
                    $tempo= $this->Unidades(Intval($Numero / 1000000));
                    $letras .= (string) $tempo;
                    $letras .= (string) " millones ";
                }
                $Numero = $Numero - (Intval($Numero / 1000000) * 1000000);
            }

            // '*---> Centenas de Millar
            If (($Numero < 1000000) And ($Numero >= 100000))
            {
                $tempo=(Intval($Numero / 100000));
                $tempo2=($Numero - ($tempo * 100000));
                If (($tempo == 1) And ($tempo2 < 1000))
                {
                    $letras .= (string) "cien mil ";
                }
                Else
                {
                    $tempo = $this->Centenas(Intval($Numero / 100000));
                    $letras .= (string) $tempo;
                    $tempo=(Intval($Numero / 100000));
                    If (($tempo <> 1) And ($tempo <> 5) And ($tempo <> 7) And ($tempo <> 9))
                    {
                        $letras .= (string) "cientos ";
                    }
                    Else
                    {
                        $letras .= (string) " ";
                    }
                }
               $Numero = $Numero - (Intval($Numero / 100000) * 100000);
					if($Numero<999)
					{
						$letras .= "mil " ;
					}
            }

            // '*---> Decenas de Millar
            If (($Numero < 100000) And ($Numero >= 10000))
            {
                $tempo= (Intval($Numero / 1000));
                If ($tempo < 16)
                {
                    $tempo = $this->Decenas(Intval($Numero / 1000));
                    $letras .= (string) $tempo;
                    $letras .= (string) " mil ";
                    $Numero = $Numero - (Intval($Numero / 1000) * 1000);
                }
                Else
                {
                    $tempo = $this->Decenas(Intval($Numero / 10000) * 10);
                    $letras .= (string) $tempo;
                    $Numero = $Numero - (Intval(($Numero / 10000)) * 10000);
                    If ($Numero > 1000)
                    {
                        $letras .= (string) " y ";
                    }
                    Else
                    {
                        $letras .= (string) " mil ";
                    }
                }
            }

            // '*---> Unidades de Millar
            If (($Numero < 10000) And ($Numero >= 1000))
            {
                $tempo=(Intval($Numero / 1000));
                If ($tempo == 1)
                {
                    $letras .= (string) "un";
                }
                Else
                {
                    $tempo = $this->Unidades(Intval($Numero / 1000));
                    $letras .= (string)$tempo;
                }
                $letras .= (string) " mil ";
                $Numero = $Numero - (Intval($Numero / 1000) * 1000);
            }

            // '*---> Centenas
            If (($Numero < 1000) And ($Numero > 99))
            {
                If ((Intval($Numero / 100) == 1) And (($Numero - (Intval($Numero / 100) * 100)) < 1))
                {
                    $letras .=  "cien ";
                }
                Else
                {
                    $temp=(Intval($Numero / 100));
                    $l2 = $this->Centenas($temp);
                    $letras .= (string) $l2;
                    If ((Intval($Numero / 100) <> 1) And (Intval($Numero / 100) <> 5) And (Intval($Numero / 100) <> 7) And (Intval($Numero / 100) <> 9))
                    {
                        $letras .= "cientos ";
                    }
                    Else
                    {
                        $letras .= (string) " ";
                    }
                }
                $Numero = $Numero - (Intval($Numero / 100) * 100);
            }

            // '*---> Decenas
            If (($Numero < 100) And ($Numero > 9) )
            {
                If ($Numero < 16 )
                {
                    $tempo = $this->Decenas(Intval($Numero));
                    $letras .= $tempo;
                    $Numero = $Numero - Intval($Numero);
                }
                Else
                {
                    $tempo= $this->Decenas(Intval(($Numero / 10)) * 10);
                    $letras .= (string) $tempo;
                    $Numero = $Numero - (Intval(($Numero / 10)) * 10);
                    If ($Numero > 0.99)
                    {
                        $letras .=(string) " y ";
                    }
                }
            }

            // '*---> Unidades
            If (($Numero < 10) And ($Numero > 0.99))
            {
                $tempo = $this->Unidades(Intval($Numero));
                $letras .= (string) $tempo;
                $Numero = $Numero - Intval($Numero);
            }
            return $letras;
        }
    }//fin del metodo NumeroALetras


    /**
     * Función que retorna el valor numerico en letras incluyendo la parte decimal
     *
     * @param mixed tipo(1=mayusculas,2=minuscululas,3=tipo titulo)
     * @access public
     */
    function GetValorEnLetras($tipo=1)
    {
        if($this->error!=0)
        {
            return "Error en Conversión a Letras";
        }
        if($this->Numero==0)
        {
            $this->Letras="cero ".$this->UnidadEntera;
        }
        else
        {
            $tt=$this->Numero;
            $this->Letras="";
            $tt = $tt+0.009;
            $Numero = (int)$tt;
            $this->Letras = $this->NumerosALetras($Numero)." ".$this->UnidadEntera;
            //echo ($x);
            $Decimales = $tt - $Numero;
            $Decimales= $Decimales*100;
            $Decimales= Intval($Decimales);
            If ($Decimales > 0)
            {
                $this->Letras .= " con " .$this->NumerosALetras($Decimales) ." ".$this->UnidadDecimal;
            }
        }
        switch($tipo)
        {
            case 1:
                $this->Letras=strtoupper($this->Letras);
                break;
            case 2:
                $this->Letras=strtolower($this->Letras);
                break;
            case 3:
                $this->Letras=ucwords($this->Letras);
                break;
            default:
                $this->Letras=strtoupper($this->Letras);
        }
        return $this->Letras;
    }//Fin del metodo GetValorEnLetras
}//Fin de la clase
?>