<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
    {literal}
    <style>
    <!--
  		.contenido{font-family: "sans_serif, Verdana, helvetica, Arial";
        font-size: 11pt;}
  		.texto  { font-family: "sans_serif, Verdana, helvetica, Arial";
        font-size: 11pt;
        text-align: justify;}
      -->
    </style>
    {/literal}
  </head>
  <body style="margin: 0pt; padding: 0pt;">
    <table class="normal_12" width="100%">
      <tr>
        <td>
          <img src="/images/logocliente.png" name="objeto1">
        </td>
        <td align="right" valign="center"><b>No. {$prefijo} {$pagare}</b></td>
      </tr>
      <tr>
        <td align="left" colspan="2">{$tipo_id_empresa} {$empresa_id}</td>
      </tr>
    </table>
    <table class="contenido" width="100%">
      <tr>
        <td align="center" style="font-size: 13pt;"><b>PAGARE A LA ORDEN DE</b></td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1" style="border-color:#000000" rules="rows">
            <tr>
              <td width="40%">LUGAR Y FECHA DE FIRMA</td>
              <td width="60%">{$FechaFirmaPagare}</td>
            </tr>
            <tr>
              <td >VALOR</td>
              <td align="right">($ {$valor_pagare})</td>
            </tr>
            <tr>
              <td >INTERESES DURANTE EL PLAZO</td>
              <td align="right">({$intereses}%)</td>
            </tr>
            <tr>
              <td >INTERESES DE MORA</td>
              <td align="right">({$interes_mora}%)</td>
            </tr>
            <tr>
              <td >FECHA DE VENCIMIENTO DE LA OBLIGACION</td>
              <td >{$fecha_vencimiento}</td>
            </tr>
            <tr>
              <td ><b>DEUDORES:</b></td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td >NOMBRES: {$nombre_deudor}</td>
              <td >IDENTIFICACION: {$identificacion_deudor}</td>
            </tr>
            <tr>
              <td colspan="2">DIRECCION Y TELEFONO: {$direccion_deudor} {$telefono_deudor}</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>      
        <td>&nbsp;</td>      
      </tr>
      <tr class="normal_12">
        <td align="justify">
          Declaro(amos) que por virtud del presente titulo valor pagare(mos) incondicionalmente, 
          a la orden de la {$Empresa} o a quien represente sus derechos, 
          en la ciudad y fecha de vencimiento arriba indicadas, 
          o en las fechas de amortizacin por cuotas señaladas en las cláusulas 
          adicionales de este mismo pagare, la suma de: <u>{$valor_letras}</u>($ {$valor_pagare}), 
          mas los intereses antes señalados, pagaderos <u>{$forma_pago}</u> <u>{$cuota_letras}</u> ($ {$valor_cuota}). 
          En el evento de que deje(mos) de pagar a tiempo una o mas cuotas de capital y los intereses, 
          el tenedor podrá declarar insubsistentes los plazos de esta obligación y pedir su inmediato 
          pago total, o el pago del saldo o saldos insolutos tanto de capital como 
          de intereses, como también de las obligaciones accesorias a que haya lugar, 
          sin necesidad de requerimiento judicial o constitución en mora o 
          requerimiento previo, a los cuales desde ya renuncio(amos). 
          Expresamente declaro(amos) excusada la presentación para el pago, 
          el aviso de rechazo y protesto. Autorizo(amos) al tenedor para dar por 
          terminado el plazo de la obligación y cobrará judicial o extrajudicialmente, 
          en el evento de que el deudor o cualquiera de los deudores fuere 
          embargado de bienes o fuere sometido o solicitare concordato, 
          o solicitare o fuere llamado a concurso de acreedores o declarado en quiebra.
          En caso de cobro judicial o extrajudicial será de mí (nuestra) cuenta 
          los costos y gastos de cobranza. Los derechos fiscales que cause 
          este pagare serán de mi (nuestro) cargo. Para constancia se firma 
          en la ciudad de <u>{$ciudad}</u> a los <u>{$dias_letras}</u> días, 
          del mes de <u>{$mes}</u> del año <u>{$anyo}</u>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" class="normal_12" >
            <tr align="center">
              <td><b>DEUDOR</b></td>
              <td><b>CODEUDOR</b></td>
            </tr>
            <tr>
              <td>FIRMA <u>{$firma}</u></td>
              <td>FIRMA <u>{$firma}</u></td>
            </tr>
            <tr>
              <td>NOMBRE <u>{$nombre_deudor}</u></td>
              <td>NOMBRE <u>{$nombre_codeudor}</u></td>
            </tr>
            <tr>
              <td>C.C. <u>{$identificacion_deudor}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>	
              <td>C.C. <u>{$identificacion_codeudor}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
            </tr>
            <tr>
              <td>TELEFONO <u>{$telefono_deudor}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>	
              <td>TELEFONO <u>{$telefono_codeudor}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <div style="page-break-before: always"></div>
    <table class="normal_12" width="100%">
      <tr>
        <td align="center">
          <img src="/images/logocliente.png" name="objeto1">
        </td>
      </tr>
      <tr class="normal_12">
        <td>
        Santiago de Cali, {$dia1} de {$mes1} de {$anyo1}
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr class="normal_12">
        <td>Señores:</td>
      </tr>
      <tr class="normal_12">
        <td>{$Empresa}</td>
      </tr>
      <tr class="normal_12">
        <td>{$Direccion}</td>
      </tr>
      <tr class="normal_12">
        <td>Ciudad</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr class="normal_12">
        <td align="justify">
        <br>
          YO (nosotros) <u>{$nombre_deudor}</u>{$codeudores_uno}
          Vecinos de Cali, identificados como aparece bajo nuestras firmas, obrando en nuestro nombre, con la presente dejamos 
          en su poder un formato de titulo valor firmado, con espacios en blanco, contenido en el 
          pagare No<u> {$prefijo} {$pagare} </u>el cual autorizamos, expresa e irrevocablemente para que sea llenado 
          según las disposiciones del art.622 del código de comercio y de acuerdo con las siguientes instrucciones:
          <br>
          <ol>
          <li>
          CAUSA: La Sociedad {$Empresa} Nos ha otorgado un crédito a la firma de esta comunicación así: 
          la suma de $ <u>{$valor_pagare}</u> (números) <u>{$valor_letras}</u>
          (Letras) pagadera en ( {$cuotas} ) {$cuotas_desc} a partir 
          del día <u>{$dia_pagare}</u> del mes de <u>{$mes_pagare}</u>  del año <u>{$anyo_pagare}</u> 
          De acuerdo al plan de amortización que forma parte de la presente comunicación.
          </li>
          <li>
          FECHA DEL TITULO: La correspondiente, al establecer por {$Empresa} cualquier saldo exigible por 
          obligaciones a mi cargo.
          </li>
          <li>
          CUANTIA: El valor de las obligaciones exigibles a mi cargo en la fecha en que se llene 
          este instrumento hasta la cantidad de______________________________________________________
          ($______________) moneda legal.
          </li>
          <li>
          VENCIMIENTO: La {$Empresa} podrá optar por considerar el titulo a la vista o 
          con vencimiento al día siguiente de que fuere llenado. Como fecha de emisión del pagare, 
          la {$Empresa}, podrá anotar la que corresponda al día en que sea llenado.
          </li>
          <li>
          INTERESES: Para los ordinarios entre el {$intereses_letras} por ciento ({$intereses}%) y 
          {$intereses_mora_letras} por ciento ({$intereses_mora}%) y para los de 
          mora los máximos que autorice la ley.
          </li>
          <li>
          Igualmente autorizamos a la {$Empresa} a consultar y 
          reportar mi información comercial en cualquier base de datos.
          </li>
          </ul>
          <br>
          INSTRUCCIONES SOBRE PAGARE
          Expresamente manifiesto que estoy de acuerdo con lo que conste en los libros y papeles de la {$Empresa} 
          respecto a las obligaciones a mi cargo.
          <br><br><br><br>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" class="normal_12">
            <tr class="normal_12">
              <td {$spanl} >
                <u>{$firma}</u><br>
                DEUDOR(a)<br>
                Nombre y apellidos: {$nombre_deudor}<br>
                Documento de identificación: {$identificacion_deudor}
                <br><br><br>
              </td>
            </tr>
            {$codeudores}
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>