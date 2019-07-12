<?php

$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';


if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1.class.php"))
{
    die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
}

if(!class_exists('InterfaseCG1'))
{
    die(MsgOut("NO SE CARGO LA CLASE","InterfaseCG1 - NO EXISTE"));
}

$a= new InterfaseCG1;

//OBTENER EL DETALLE DE UN CODIGO (cg_contabilizacion_estado_id)
echo $a->GetDetalleError('01');
echo "<br><br>";

//lISTADO CON LOS TIPOS DE DOCUMENTOS CONFIGURADOS PARA LA INTERFASE
$tiposDeDoc = $a->getTiposDeDocumentos('01');

//SELECCION DEL LAPSO CONTABLE (AÑO, MES, DIAINICIAL, DIAFINAL)
$a->SetLapsoContable(2005,1,5,5);

//CONFIGURACION DEL TIPO DE DOCUMENTO SELECCIONADO (EMPRESA_ID,DOCUMENTO_ID)
$a->setTipoDeDocumento('01',70);

//RETORNA LA INFORMACION DEL DOCUMENTO QUE SELECCIONO CON $a->setTipoDeDocumento();
$tipoDeDocSeleccionado = $a->getTipoDeDocumento();

//RETORNA UN ARREGLO CON TODOS LOS DOCUMENTOS QUE DEBEN PASAR POR LA INTERFASE Y SU ESTADO
$retorno = $a->GetDetalleDocumento(30);


if(!$a->ContabilizarDocumento(31,$reprocesar=false))
{
    // si retorno falso mostrar el error en el mismo modulo.. Noooo como sigue
    die(MsgOut($a->Err(),$a->ErrMsg()));
}


echo "RANGO DE FECHAS SELECCIONADO<br><br>";
echo "Fecha Final : " .$a->getFechaInicial() ."<br>Fecha Final : " . $a->getFechaFinal() . "<br>";

if($retorno===false)
{
    // si retorno falso mostrar el error en el mismo modulo.. Noooo como sigue
    die(MsgOut($a->Err(),$a->ErrMsg()));
}
else
{
    if(empty($retorno))
    {
        echo "SIN DATOS PARA EL VECTOR";
    }
    else
    {

        echo ImprimirNivel(&$retorno);

    }


}

function ImprimirNivel($v)
{

$SALIDA .='<TABLE width="100%" border="1">';
$SALIDA .='  <tr colspan="2">';
$SALIDA .='  <td>'.$v['TITULO'].'</td>';
$SALIDA .='  </tr>';

if(is_array($vector['REGISTROS']))
{
$SALIDA .='  <tr>';
$SALIDA .='  <td>'.$v['TITULO'].'</td>';
$SALIDA .='  </tr>';
}


    echo $vector['TITULO'];
    foreach($vector as $k=>$v)
    {

    }

$SALIDA .='</TABLE>    ';
return $SALIDA;
}

EXIT;

$a->GenerarInterfase();

echo "TIPOS DE DOCUMENTOS<br><br>";
print_r($tiposDeDoc);
echo "<br><br>";
echo "RANGO DE FECHAS SELECCIONADO<br><br>";
echo "Fecha Final : " .$a->getFechaInicial() ."<br>Fecha Final : " . $a->getFechaFinal() . "<br>";
echo "<br><br>";
echo "TIPO DE DOCUMENTO SELECCIONADO<br><br>";
print_r($tipoDeDocSeleccionado);
echo "<br><br>";
echo "DATOS DE LOS DOCUMENTOS SELECCIONADOS<br><br>";


if($retorno===false)
{
    // si retorno falso mostrar el error en el mismo modulo.. Noooo como sigue
    die(MsgOut($a->Err(),$a->ErrMsg()));
}
else
{
    PRINT_R($retorno);
}

$retorno= $a->GenerarInterfase();

echo "<br><br>";
echo "GENERACION DE LA INTERFASE<br><br>";
if($retorno===false)
{
    // si retorno falso mostrar el error en el mismo modulo.. Noooo como sigue
    die(MsgOut($a->Err(),$a->ErrMsg()));
}
else
{
    echo "OK";
}

?>
