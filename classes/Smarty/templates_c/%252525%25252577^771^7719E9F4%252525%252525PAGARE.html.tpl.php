<?php /* Smarty version 2.6.10, created on 2006-10-02 14:19:57
         compiled from PAGARE.html.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.1.4  (Linux)">
	<META NAME="AUTHOR" CONTENT="Ivan">
	<META NAME="CREATED" CONTENT="20050911;23210000">
	<META NAME="CHANGED" CONTENT="20050913;13084100">
	<?php echo '
	<STYLE>
	<!--
		@page { size: 21.59cm 27.94cm; margin-right: 3cm; margin-top: 2.5cm; margin-bottom: 2.5cm }
		P { margin-bottom: 0.21cm; direction: ltr; color: #000000; widows: 2; orphans: 2 }
		P.western { font-family: "Times New Roman", serif; font-size: 12pt; so-language: es-CO }
		P.cjk { font-family: "Times New Roman", serif; font-size: 12pt }
		P.ctl { font-family: "Times New Roman", serif; font-size: 12pt; so-language: ar-SA }
		.contenido {
						font-family: "Verdana, Arial"; font-size: "8pt"; color:#000000;
		}
		.texto {
						font-family: "Verdana, Arial"; font-size: "8pt"; color:#000000; TEXT-ALIGN: justify;
		}
		.titulo {
						font-family: "Verdana, Arial"; font-size: "10pt"; color:#000000;
		}				
	-->
	</STYLE>
	'; ?>

</HEAD>
<BODY LANG="en-US" TEXT="#000000" DIR="LTR">
<table border="0" width="100%" align="center" class="contenido">
<tr>
	<td align="center" class='titulo'><B>PAGARE NEGOCIABLE No. <?php echo $this->_tpl_vars['NumeroPagare']; ?>
</B><br></br></td>
</tr>
</table>
<table border="0" width="100%" align="center" class="contenido">
<tr>
	<td>INFORMACION DEL PAGARE</td>
</tr>
<tr>
	<td>Lugar y fecha de firma: <?php echo $this->_tpl_vars['FechaFirmaPagare']; ?>
</td>
</tr>
<tr>
	<td>Valor: <?php echo $this->_tpl_vars['ValorPagare']; ?>
</td>
</tr>
<tr>
	<td>Intereses de mora:</td>
</tr>
<tr>
	<td>Forma de pago: <?php echo $this->_tpl_vars['FormaPagoPagare']; ?>
</td>
</tr>
<tr>
	<td>Fecha vencimiento de la obligacion: <?php echo $this->_tpl_vars['VencimientoPagare']; ?>
</td>
</tr>
<tr>
	<td>Iintereses durante el plazo: DOS PORCIENTO (2%)</td>
</tr>

<tr>
	<td>PERSONA A QUIEN HACERSE EL PAGO:</td>
</tr>
<tr>
	<td>LUGAR DONDE SE EFECTUARA EL PAGO:</td>
</tr>


<tr>
	<td>INFORMACION DEL(LOS) DEUDOR(ES): </td>
</tr>
<tr>
	<td><?php echo $this->_tpl_vars['DedudoresDatosPagare']; ?>
</td>
</tr>
<tr>
	<td></td>
</tr>
<tr>
	<td>Observación: <?php echo $this->_tpl_vars['ObservacionPagare']; ?>
</td>
</tr>
</table>

<table border="0" width="100%" align="center" class="texto">
<tr>
	<td class="texto">Declaremos<B>: PRIMERA</B> -<B><I>OBJETO</I></B>: que por virtud del presente titulo valor Pagare
(mos) incondicionalmente, a la orden de O a quien represente sus
derechos, en la ciudad y direcci&oacute;n indicados, en las fechas de
amortizaci&oacute;n por cuotas se&ntilde;aladas en la cl&aacute;usula
tercera de este pagare, la suma de <?php echo $this->_tpl_vars['ValorLetrasPagare']; ?>

&nbsp; 
( $ <?php echo $this->_tpl_vars['ValorPagare']; ?>
 ), mas los intereses se&ntilde;alados
en la cl&aacute;usula segunda de este documento. 
<B>SEGUNDA. -<I>INTERESES: </I></B>Que sobra la suma debida reconocer&eacute;
(mos) intereses,equivalentes
&nbsp;&nbsp;&nbsp;&nbsp;               
por ciento (&nbsp;&nbsp;&nbsp;&nbsp; %) mensual, sobre el capital o su saldo insoluto. En caso de mora
reconocer&eacute; (mos) intereses a la tasa m&aacute;xima legal
autorizada. 
<B>TERCERA- <I>PLAZO:</I></B> Que Pagare (mos) el capital
indicado en la cl&aacute;usula primera y sus intereses mediante
cuotas mensuales y sucesivas correspondientes cada una a la cantidad
de ($&nbsp;&nbsp;&nbsp;&nbsp; ).                                            
El primer pago lo efectuare(mos) el d&iacute;a        (    ).
Del mes de
&nbsp;&nbsp;&nbsp;&nbsp; 
del a&ntilde;o
&nbsp;&nbsp;&nbsp;&nbsp;  (&nbsp;&nbsp;&nbsp;&nbsp;)                          
y as&iacute; sucesivamente en
ese mismo d&iacute;a de cada mes. <B>CUARTA</B>.-<I><B>CLAUSULA
ALEATORIA</B>: </I>El tenedor podr&aacute;  declarar vencidos la
totalidad de los plazos de esta obligaci&oacute;n o de las cuotas que
constituyan el saldo de lo debido y exigir su pago inmediato ya sea
judicial o extrajudicialmente, cuando el (los) deudor (es) entre en
mora e incumpla una cualquiera de las obligaciones derivadas del
presente documento. <B>QUINTA</B>- <B><I>IMPUESTO DE TIMBRE:</I></B>
el impuesto de timbre de este documento si se causare ser&aacute;  de
cargo &uacute;nica  y exclusivamente de el (los) deudor (es).
</td>
</tr>
<tr>
	<td><br>En constancia de lo anterior, se firma este pagaré en la ciudad de
	<?php echo $this->_tpl_vars['CiudadPagare']; ?>
, el d&iacute;a 
<?php echo $this->_tpl_vars['DiaLetrasPagare']; ?>
 (<?php echo $this->_tpl_vars['DiaPagare']; ?>
)
del mes de <?php echo $this->_tpl_vars['MesPagare']; ?>
 del año <?php echo $this->_tpl_vars['AnoLetrasPagare']; ?>
 (&nbsp;&nbsp;&nbsp;&nbsp; )
.</td>
</tr>
<tr>
	<td><BR><B>OTORGANTES:</B></BR><BR></BR></td>
</tr>
</table>
<table border="0" width="100%" align="center" class="texto">
<tr>
	<?php echo $this->_tpl_vars['FirmaDedudoresPagare']; ?>

<tr>
	<td>DEUDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEUDOR</td>
</tr>
</table>

</BODY>
</HTML>