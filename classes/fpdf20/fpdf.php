<?php
/*******************************************************************************
* $Id: fpdf.php,v 1.80 2008/10/22 21:32:51 cahenao Exp $
* Software: FPDF                                                               *
* Version:  1.52                                                               *
* Date:     2003-12-30                                                         *
* Author:   Olivier PLATHEY ipsoft                                                   *
* License:  Freeware                                                           *
*                                                                              *
* You may use and modify this software as you wish.                            *
*******************************************************************************/

if(!class_exists('FPDF'))
{
define('FPDF_VERSION','1.52');

class FPDF
{
//Private properties
var $page;               //current page number
var $n;                  //current object number
var $offsets;            //array of object offsets
var $buffer;             //buffer holding in-memory PDF
var $pages;              //array containing pages
var $state;              //current document state
var $compress;           //compression flag
var $DefOrientation;     //default orientation
var $CurOrientation;     //current orientation
var $OrientationChanges; //array indicating orientation changes
var $k;                  //scale factor (number of points in user unit)
var $fwPt,$fhPt;         //dimensions of page format in points
var $fw,$fh;             //dimensions of page format in user unit
var $wPt,$hPt;           //current dimensions of page in points
var $w,$h;               //current dimensions of page in user unit
var $lMargin;            //left margin
var $tMargin;            //top margin
var $rMargin;            //right margin
var $bMargin;            //page break margin
var $cMargin;            //cell margin
var $x,$y;               //current position in user unit for cell positioning
var $lasth;              //height of last cell printed
var $LineWidth;          //line width in user unit
var $CoreFonts;          //array of standard font names
var $fonts;              //array of used fonts
var $FontFiles;          //array of font files
var $diffs;              //array of encoding differences
var $images;             //array of used images
var $PageLinks;          //array of links in pages
var $links;              //array of internal links
var $FontFamily;         //current font family
var $FontStyle;          //current font style
var $underline;          //underlining flag
var $CurrentFont;        //current font info
var $FontSizePt;         //current font size in points
var $FontSize;           //current font size in user unit
var $DrawColor;          //commands for drawing color
var $FillColor;          //commands for filling color
var $TextColor;          //commands for text color
var $ColorFlag;          //indicates whether fill and text colors are different
var $ws;                 //word spacing
var $AutoPageBreak;      //automatic page breaking
var $PageBreakTrigger;   //threshold used to trigger page breaks
var $InFooter;           //flag set when processing footer
var $ZoomMode;           //zoom display mode
var $LayoutMode;         //layout display mode
var $title;              //title
var $subject;            //subject
var $author;             //author
var $keywords;           //keywords
var $creator;            //creator
var $AliasNbPages;       //alias for total number of pages

/*******************************************************************************
*                                                                              *
*                               Public methods                                 *
*                                                                              *
*******************************************************************************/
function FPDF($orientation='P',$unit='mm',$format='A4')
{
    //Some checks
    $this->_dochecks();
    //Initialization of properties
    $this->page=0;
    $this->n=2;
    $this->buffer='';
    $this->pages=array();
    $this->OrientationChanges=array();
    $this->state=0;
    $this->fonts=array();
    $this->FontFiles=array();
    $this->diffs=array();
    $this->images=array();
    $this->links=array();
    $this->InFooter=false;
    $this->lasth=0;
    $this->FontFamily='';
    $this->FontStyle='';
    $this->FontSizePt=12;
    $this->underline=false;
    $this->DrawColor='0 G';
    $this->FillColor='0 g';
    $this->TextColor='0 g';
    $this->ColorFlag=false;
    $this->ws=0;
    //Standard fonts
    $this->CoreFonts=array('courier'=>'Courier','courierB'=>'Courier-Bold','courierI'=>'Courier-Oblique','courierBI'=>'Courier-BoldOblique',
        'helvetica'=>'Helvetica','helveticaB'=>'Helvetica-Bold','helveticaI'=>'Helvetica-Oblique','helveticaBI'=>'Helvetica-BoldOblique',
        'times'=>'Times-Roman','timesB'=>'Times-Bold','timesI'=>'Times-Italic','timesBI'=>'Times-BoldItalic',
        'symbol'=>'Symbol','zapfdingbats'=>'ZapfDingbats');
    //Scale factor
    if($unit=='pt')
        $this->k=1;
    elseif($unit=='mm')
        $this->k=72/25.4;
    elseif($unit=='cm')
        $this->k=72/2.54;
    elseif($unit=='in')
        $this->k=72;
    else
        $this->Error('Incorrect unit: '.$unit);
    //Page format
    if(is_string($format))
    {
        $format=strtolower($format);
        if($format=='a3')
            $format=array(841.89,1190.55);
        elseif($format=='a4')
            $format=array(595.28,841.89);
        elseif($format=='a5')
            $format=array(420.94,595.28);
        elseif($format=='letter')
            $format=array(612,792);
        elseif($format=='legal')
            $format=array(612,1008);
/*ESTOS SON LOS PROPIOS DE LA APLICACI�*/
        elseif($format=='soat')
            $format=array(612,410);
        elseif($format=='letter2')
            $format=array(612,820);//792
        elseif($format=='recibo')
            $format=array(612,274);
        elseif($format=='facturamediacarta')
            $format=array(612,380);
        elseif($format=='vouchercargos')
            $format=array(612,350);
            elseif($format=='prueba')
            $format=array(200,265);//para stickers..
        else
            $this->Error('Unknown page format: '.$format);
        $this->fwPt=$format[0];
        $this->fhPt=$format[1];
    }
    else
    {
        $this->fwPt=$format[0]*$this->k;
        $this->fhPt=$format[1]*$this->k;
    }
    $this->fw=$this->fwPt/$this->k;
    $this->fh=$this->fhPt/$this->k;
    //Page orientation
    $orientation=strtolower($orientation);
    if($orientation=='p' or $orientation=='portrait')
    {
        $this->DefOrientation='P';
        $this->wPt=$this->fwPt;
        $this->hPt=$this->fhPt;
    }
    elseif($orientation=='l' or $orientation=='landscape')
    {
        $this->DefOrientation='L';
        $this->wPt=$this->fhPt;
        $this->hPt=$this->fwPt;
    }
    else
        $this->Error('Incorrect orientation: '.$orientation);
    $this->CurOrientation=$this->DefOrientation;
    $this->w=$this->wPt/$this->k;
    $this->h=$this->hPt/$this->k;
    //Page margins (1 cm)
    $margin=28.35/$this->k;
    $this->SetMargins($margin,$margin);
    //Interior cell margin (1 mm)
    $this->cMargin=$margin/10;
    //Line width (0.2 mm)
    $this->LineWidth=.567/$this->k;
    //Automatic page break
    $this->SetAutoPageBreak(true,2*$margin);
    //Full width display mode
    $this->SetDisplayMode('fullwidth');
    //Compression
    $this->SetCompression(true);
}

function SetMargins($left,$top,$right=-1)
{
    //Set left, top and right margins
    $this->lMargin=$left;
    $this->tMargin=$top;
    if($right==-1)
        $right=$left;
    $this->rMargin=$right;
}

function SetLeftMargin($margin)
{
    //Set left margin
    $this->lMargin=$margin;
    if($this->page>0 and $this->x<$margin)
        $this->x=$margin;
}

function SetTopMargin($margin)
{
    //Set top margin
    $this->tMargin=$margin;
}

function SetRightMargin($margin)
{
    //Set right margin
    $this->rMargin=$margin;
}

function SetAutoPageBreak($auto,$margin=0)
{
    //Set auto page break mode and triggering margin
    $this->AutoPageBreak=$auto;
    $this->bMargin=$margin;
    $this->PageBreakTrigger=$this->h-$margin;
}

function SetDisplayMode($zoom,$layout='continuous')
{
    //Set display mode in viewer
    if($zoom=='fullpage' or $zoom=='fullwidth' or $zoom=='real' or $zoom=='default' or !is_string($zoom))
        $this->ZoomMode=$zoom;
    else
        $this->Error('Incorrect zoom display mode: '.$zoom);
    if($layout=='single' or $layout=='continuous' or $layout=='two' or $layout=='default')
        $this->LayoutMode=$layout;
    else
        $this->Error('Incorrect layout display mode: '.$layout);
}

function SetCompression($compress)
{
    //Set page compression
    if(function_exists('gzcompress'))
        $this->compress=$compress;
    else
        $this->compress=false;
}

function SetTitle($title)
{
    //Title of document
    $this->title=$title;
}

function SetSubject($subject)
{
    //Subject of document
    $this->subject=$subject;
}

function SetAuthor($author)
{
    //Author of document
    $this->author=$author;
}

function SetKeywords($keywords)
{
    //Keywords of document
    $this->keywords=$keywords;
}

function SetCreator($creator)
{
    //Creator of document
    $this->creator=$creator;
}

function AliasNbPages($alias='{nb}')
{
    //Define an alias for total number of pages
    $this->AliasNbPages=$alias;
}

function Error($msg)
{
    //Fatal error
    die('<B>FPDF error: </B>'.$msg);
}

function Open()
{
    //Begin document
    if($this->state==0)
        $this->_begindoc();
}

function Close()
{
    //Terminate document
    if($this->state==3)
        return;
    if($this->page==0)
        $this->AddPage();
    //Page footer
    $this->InFooter=true;
    $this->Footer();
    $this->InFooter=false;
    //Close page
    $this->_endpage();
    //Close document
    $this->_enddoc();
}

function AddPage($orientation='')
{
    //Start a new page
    if($this->state==0)
        $this->Open();
    $family=$this->FontFamily;
    $style=$this->FontStyle.($this->underline ? 'U' : '');
    $size=$this->FontSizePt;
    $lw=$this->LineWidth;
    $dc=$this->DrawColor;
    $fc=$this->FillColor;
    $tc=$this->TextColor;
    $cf=$this->ColorFlag;
    if($this->page>0)
    {
        //Page footer
        $this->InFooter=true;
        $this->Footer();
        $this->InFooter=false;
        //Close page
        $this->_endpage();
    }
    //Start new page
    $this->_beginpage($orientation);
    //Set line cap style to square
    $this->_out('2 J');
    //Set line width
    $this->LineWidth=$lw;
    $this->_out(sprintf('%.2f w',$lw*$this->k));
    //Set font
    if($family)
        $this->SetFont($family,$style,$size);
    //Set colors
    $this->DrawColor=$dc;
    if($dc!='0 G')
        $this->_out($dc);
    $this->FillColor=$fc;
    if($fc!='0 g')
        $this->_out($fc);
    $this->TextColor=$tc;
    $this->ColorFlag=$cf;
    //Page header
    $this->Header();
    //Restore line width
    if($this->LineWidth!=$lw)
    {
        $this->LineWidth=$lw;
        $this->_out(sprintf('%.2f w',$lw*$this->k));
    }
    //Restore font
    if($family)
        $this->SetFont($family,$style,$size);
    //Restore colors
    if($this->DrawColor!=$dc)
    {
        $this->DrawColor=$dc;
        $this->_out($dc);
    }
    if($this->FillColor!=$fc)
    {
        $this->FillColor=$fc;
        $this->_out($fc);
    }
    $this->TextColor=$tc;
    $this->ColorFlag=$cf;
}

function Header()
{
        //darling envios
        if($_SESSION['REPORTES']['VARIABLE']=='envios')
        {
                $arr=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
                $this->SetFont('Arial','',7);
                $this->Cell(4,12,date('Y-m-d h:m'));
                $this->Cell(2,2,'Pagina No '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',170,9,18)."";
                $html.="<table border=0 width=100 align='center'>";
                $html.="<tr><td width=760><BR><BR></td></tr>";
                $html .= "          <tr>";
                $html .= "              <td align=\"CENTER\" width=760>ENVIO No. ".$arr[0][envio_id]."</td>";
                $html .= "          </tr>";
                $html .= "          <tr>";
                $html .= "              <td align=\"CENTER\" width=760>".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>";
                $html .= "          </tr>";
                $html .= "          <tr>";
                $html .= "              <td align=\"CENTER\" width=760>DEBE A:</td>";
                $html .= "          </tr>";
                $html .= "          <tr width=760>";
                $html .= "              <td align=\"CENTER\" width=760>".NombreEmpresa($arr[0][empresa_id])."</td>";
                $html .= "          </tr>";
                $html .= "          <tr width=760>";
                $html .= "              <td align=\"CENTER\" width=760>POR SERVICIOS PRESTADOS EN: </td>";
                $html .= "          </tr>";
                $html .= "          <tr>";
                IF(empty($arr[0][departamento]))
                {  $dpto='TODOS'; }
                else
                {
                        list($dbconn) = GetDBconn();
                        $query = "select descripcion from departamentos
                                            where departamento='$dpto'";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en la Tabal autorizaiones";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        $dpto=$result->fields[0];
                }
                $html .= "              <td align=\"CENTER\" width=760> - ".$dpto."</td>";
                $html .= "          </tr>";
                $html .= "          <tr align=\"CENTER\">";
                $html .= "              <td width=\"110\" align=\"CENTER\">FACTURA</td>";
                $html .= "              <td width=\"100\" align=\"CENTER\">VALOR</td>";
                $html .= "              <td width=\"130\" align=\"CENTER\">IDENTIFICACION</td>";
                $html .= "              <td width=\"215\" align=\"CENTER\">PACIENTE</td>";
                $html .= "              <td width=\"205\" align=\"CENTER\">PLAN</td>";
                $html .= "          </tr>";
                $html .="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html .= "  </table>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        //darling facturas
        if($_SESSION['REPORTES']['VARIABLE']=='factura')
        {
                $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
								//print_r($datos);
                $this->SetFont('Arial','',8);
                $this->Cell(0,2,'Pagina No '.$this->PageNo());
                if($this->PageNo() <> 1)
                {
                  $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
                  $html.="<tr><td width=760>&nbsp;</td></tr>";
                  if(!empty($datos[prefijo]) AND !empty($datos[factura_fiscal]))
                  {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT count(numerodecuenta) FROM fac_facturas_cuentas
                                            WHERE factura_fiscal=".$datos[factura_fiscal]."
                                            and prefijo='".$datos[prefijo]."'
                                            LIMIT 2 OFFSET 0";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        if($result->fields[0] > 1)
                        {
                                $datos[prefijo]='';
                                $datos[factura_fiscal]='';
                        }
                  }
                  $factura_fiscal=str_pad($datos[factura_fiscal], $datos[numero_digitos], "0", STR_PAD_LEFT);
                  $html.="<tr><td width=300>PACIENTE: ".$datos[nombre]."</td><td width=280>HIS/CLI: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td><td width=180>FACT No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
                  $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="</table>";
                }
                else
                {
                  $html="".$this->image('images/logocliente.png',180,2,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                  //$html.="<br><br><br>"; 
                  $html.="<table border=0 width=100 align='center' border=0>";
                  $html.="<tr><td width=760><BR><BR></td></tr>";

                  if(!empty($datos[prefijo]) AND !empty($datos[factura_fiscal]))
                  {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT count(numerodecuenta) FROM fac_facturas_cuentas
                                            WHERE factura_fiscal=".$datos[factura_fiscal]."
                                            and prefijo='".$datos[prefijo]."'
                                            LIMIT 2 OFFSET 0";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        if($result->fields[0] > 1)
                        {
                                $datos[prefijo]='';
                                $datos[factura_fiscal]='';
                        }
                  }
                  $factura_fiscal=str_pad($datos[factura_fiscal], $datos[numero_digitos], "0", STR_PAD_LEFT);
                  $razon_social = substr($datos[razon_social],0,36);
                  $html.="<tr><td width=260><B>".$razon_social."</B></td><td width=120 align=\"left\">".$datos[tipoid].": ".$datos[id]."</td><td width=280 align='center'>FACTURA DE VENTA</td><td width=100>No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
                  $razon_social = substr($datos[razon_social],36,72);
                  if(!empty($razon_social))
                  {
                    $html.="<tr><td width=260><B>".$razon_social."</B></td><td width=120 align=\"left\">&nbsp;</td><td width=280 align='center'>&nbsp;</td><td width=100>&nbsp;</td></tr>";
                  }
                  //$html.="<tr><td width=460>".$datos[tipoid].": ".$datos[id]."</td><td width=300>No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
									$datostelefonos = substr($datos[telefonos],0,8);
                  $html.="<tr><td width=380>DIRECCION: ".$datos[direccion]."</td><td width=210> TELEFONOS: ".$datostelefonos."</td><td width=170>".$datos[municipio]."-".$datos[departamento]."</td></tr>";
									$datostelefonos = substr($datos[telefonos],8,17);
									if(!empty($datostelefonos))
									{
                   $html.="<tr><td width=380>&nbsp;</td><td width=210> ".$datostelefonos."</td><td width=170>&nbsp;</td></tr>";
									}
                  $html.="<tr><td width=760 colspan=\"2\">".substr("$datos[texto1]",0,120)."</td></tr>";
                  $html.="<tr><td width=760 colspan=\"2\" align='center'>".substr("$datos[texto1]",120,256)."</td></tr>";
                  $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
									$nombre_tercero = substr($datos[nombre_tercero],0,32);
									$direccion_tercero = substr($datos[direccion_tercero],0,40);
                  $html.="<tr><td width=300><B>CLIENTE: ".$nombre_tercero."</B></td><td width=150>".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td><td width=310>DIRECCION: ".$direccion_tercero."</td></tr>";
									$nombre_tercero = substr($datos[nombre_tercero],32,70);
									$direccion_tercero = substr($datos[direccion_tercero],40,80);
         					if(!empty($nombre_tercero) OR !empty($direccion_tercero))
									{
										$html.="<tr><td width=300><B>".$nombre_tercero."</B></td><td width=150>&nbsp;</td><td width=310>$direccion_tercero</td></tr>";
									}
                  //$html.="<tr><td width=300><B>CLIENTE: ".$datos[nombre_tercero]."</B></td><td width=150>".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td><td width=310>DIRECCION: ".$datos[direccion_tercero]."</td></tr>";
									//$tmp_dir_tercero = substr("$datos[direccion_tercero]",17,45);
                  $html.="<tr><td width=300>TELEFONOS: ".$datos[telefono_tercero]."</td><td width=280>PLAN: ".substr("$datos[plan_descripcion]",0,34)."</td><td width=180>DPTO: ".substr("$datos[descripcion]",0,17)."</td></tr>";
                  //$html.="<tr><td width=460>".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td></tr>";
                  //$html.="<tr><td width=460>DIRECCION: ".$datos[direccion_tercero]."</td><td width=300>TELEFONOS: ".$datos[telefono_tercero]."</td></tr>";
                  //$html.="<tr><td width=300>TELEFONOS: ".$datos[telefono_tercero]."</td><td width=280>PLAN: ".$datos[plan_descripcion]."</td><td width=180>DPTO: ".substr("$datos[descripcion]",0,17)."</td></tr>";
									$tmp_plandescripcion = substr("$datos[plan_descripcion]",34,75);
									$tmp_dpto = substr("$datos[descripcion]",17,45);
									if(!empty($tmp_plandescripcion) OR !empty($tmp_dpto))
									{
												$html.="<tr><td width=300>&nbsp;</td><td width=280>".$tmp_plandescripcion."</td><td width=180>".$tmp_dpto."</td></tr>";
									}
                  $html.="<tr><td width=580>PACIENTE: ".$datos[nombre]."</td><td width=180>HIS/CLI: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td></tr>";
                  $fecha=explode("-",$datos[fechafac]);
                  $nueva = mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]) + 30 * 24 * 60 * 60;
                  $nuevafecha=date("d/m/Y",$nueva);
                  $fechaElab=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
                  if($datos[fecha_cierre]){
                    (list($date,$hour)=explode(' ',$datos[fecha_cierre]));
                    (list($anoFC,$mesFC,$diaFC)=explode('-',$date));                
                    $fechaEgreso=date("d/m/Y",mktime(0,0,0, $mesFC,$diaFC,$anoFC));
                  }
                  elseif($datos[fecha_cierre_movimientos_habitacion]){
                    (list($date,$hour)=explode(' ',$datos[fecha_cierre_movimientos_habitacion]));
                    (list($anoFC,$mesFC,$diaFC)=explode('-',$date));                
                    $fechaEgreso=date("d/m/Y",mktime(0,0,0, $mesFC,$diaFC,$anoFC));
                  }
                  else
                  {
                    $fechaEgreso=FechaStamp($datos[fecha_registro]);
                  }
                  $html.="<tr><td width=190>FECHA INGR.: ".FechaStamp($datos[fecha_registro])."</td><td width=190>FECHA EGRE.: ".$fechaEgreso."</td><td width=190>FECHA ELAB.: ".$fechaElab."</td><td width=190>FECHA VENC.: ".$nuevafecha."</td></tr>";
                  //$html.="<tr><td width=230>DIRECCION: ".$datos[residencia_direccion]."</td><td width=230>TELEFONOS: ".$datos[residencia_telefono]."</td><td width=150>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                  $html.="</table>";
                }
                $this->Ln(1);
                $this->WriteHTML($html);
        }
	//facturamediacarta
        if($_SESSION['REPORTES']['VARIABLE']=='facturamediacarta')
        {
                $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
		//print_r($datos);
                $this->SetFont('Arial','B',6);
                //$this->Cell(0,2,'Pagina No '.$this->PageNo());
/*                if($this->PageNo() <> 1)
                {
                  $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
                  $html.="<tr><td width=760>&nbsp;</td></tr>";
                  if(!empty($datos[prefijo]) AND !empty($datos[factura_fiscal]))
                  {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT count(numerodecuenta) FROM fac_facturas_cuentas
                                            WHERE factura_fiscal=".$datos[factura_fiscal]."
                                            and prefijo='".$datos[prefijo]."'
                                            LIMIT 2 OFFSET 0";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        if($result->fields[0] > 1)
                        {
                                $datos[prefijo]='';
                                $datos[factura_fiscal]='';
                        }
                  }
                  $factura_fiscal=str_pad($datos[factura_fiscal], $datos[numero_digitos], "0", STR_PAD_LEFT);
                  $html.="<tr><td width=300>PACIENTE: ".$datos[nombre]."</td><td width=280>HIS/CLI: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td><td width=180>FACT No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
                  $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="</table>";
                }
                else
                {*/
                  $html="".$this->image('images/logocliente.png',180,2,15)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                  //$html.="<br><br><br>"; 
                  $html.="<table border=\"0\" width=100 align='center'>";
                  $html.="<tr><td width=760 height=\"25\"><BR></td></tr>";

                  if(!empty($datos[prefijo]) AND !empty($datos[factura_fiscal]))
                  {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT count(numerodecuenta) FROM fac_facturas_cuentas
                                            WHERE factura_fiscal=".$datos[factura_fiscal]."
                                            and prefijo='".$datos[prefijo]."'
                                            LIMIT 2 OFFSET 0";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        if($result->fields[0] > 1)
                        {
                                $datos[prefijo]='';
                                $datos[factura_fiscal]='';
                        }
                  }
                  $factura_fiscal=str_pad($datos[factura_fiscal], $datos[numero_digitos], "0", STR_PAD_LEFT);
                  $razon_social = substr($datos[razon_social],0,36);
                  $html.="<tr><td width=260 height=\"25\"><B>".$razon_social."</B></td><td width=120 align=\"left\" height=\"25\">".$datos[tipoid].": ".$datos[id]."</td><td width=280 align='center' height=\"25\">FACTURA DE VENTA</td><td width=100 height=\"25\">No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
                  $razon_social = substr($datos[razon_social],36,72);
                  if(!empty($razon_social))
                  {
                    $html.="<tr><td width=260 height=\"25\"><B>".$razon_social."</B></td><td width=120 align=\"left\" height=\"25\">&nbsp;</td><td width=280 align='center' height=\"25\">&nbsp;</td><td width=100 height=\"25\">&nbsp;</td></tr>";
                  }
                  //$html.="<tr><td width=460>".$datos[tipoid].": ".$datos[id]."</td><td width=300>No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
									$datostelefonos = substr($datos[telefonos],0,8);
                  $html.="<tr><td width=380 height=\"25\">DIRECCION: ".$datos[direccion]."</td><td width=210 height=\"25\"> TELEFONOS: ".$datostelefonos."</td><td width=170 height=\"25\">".$datos[municipio]."-".$datos[departamento]."</td></tr>";
									$datostelefonos = substr($datos[telefonos],8,17);
									if(!empty($datostelefonos))
									{
                   $html.="<tr><td width=380 height=\"25\">&nbsp;</td><td width=210 height=\"25\"> ".$datostelefonos."</td><td width=170 height=\"25\">&nbsp;</td></tr>";
									}
                  $html.="<tr><td width=760 colspan=\"2\" height=\"25\">".$datos[texto1]."</td></tr>";
                  //$html.="<tr><td width=760 colspan=\"2\">".substr("$datos[texto1]",0,120)."</td></tr>";
                  //$html.="<tr><td width=760 colspan=\"2\" align='center'>".substr("$datos[texto1]",120,256)."</td></tr>";
                  //$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
									$nombre_tercero = substr($datos[nombre_tercero],0,32);
									$direccion_tercero = substr($datos[direccion_tercero],0,40);
                  $html.="<tr><td width=300 height=\"25\"><B>CLIENTE: ".$nombre_tercero."</B></td><td width=150 height=\"25\">".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td><td width=310 height=\"25\">DIRECCION: ".$direccion_tercero."</td></tr>";
									$nombre_tercero = substr($datos[nombre_tercero],32,70);
									$direccion_tercero = substr($datos[direccion_tercero],40,80);
         					if(!empty($nombre_tercero) OR !empty($direccion_tercero))
									{
										$html.="<tr><td width=300 height=\"25\"><B>".$nombre_tercero."</B></td><td width=150 height=\"25\">&nbsp;</td><td width=310 height=\"25\">$direccion_tercero</td></tr>";
									}
                  //$html.="<tr><td width=300><B>CLIENTE: ".$datos[nombre_tercero]."</B></td><td width=150>".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td><td width=310>DIRECCION: ".$datos[direccion_tercero]."</td></tr>";
									//$tmp_dir_tercero = substr("$datos[direccion_tercero]",17,45);
                  $html.="<tr><td width=300 height=\"25\">TELEFONOS: ".$datos[telefono_tercero]."</td><td width=280 height=\"25\">PLAN: ".substr("$datos[plan_descripcion]",0,34)."</td><td width=180 height=\"25\">DPTO: ".substr("$datos[descripcion]",0,17)."</td></tr>";
                  //$html.="<tr><td width=460>".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td></tr>";
                  //$html.="<tr><td width=460>DIRECCION: ".$datos[direccion_tercero]."</td><td width=300>TELEFONOS: ".$datos[telefono_tercero]."</td></tr>";
                  //$html.="<tr><td width=300>TELEFONOS: ".$datos[telefono_tercero]."</td><td width=280>PLAN: ".$datos[plan_descripcion]."</td><td width=180>DPTO: ".substr("$datos[descripcion]",0,17)."</td></tr>";
									$tmp_plandescripcion = substr("$datos[plan_descripcion]",34,75);
									$tmp_dpto = substr("$datos[descripcion]",17,45);
									if(!empty($tmp_plandescripcion) OR !empty($tmp_dpto))
									{
												$html.="<tr><td width=300>&nbsp;</td><td width=280>".$tmp_plandescripcion."</td><td width=180>".$tmp_dpto."</td></tr>";
									}
                  $html.="<tr><td width=580 height=\"25\">PACIENTE: ".$datos[nombre]."</td><td width=180 height=\"25\">HIS/CLI: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td></tr>";
                  $fecha=explode("-",$datos[fechafac]);
                  $nueva = mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]) + 30 * 24 * 60 * 60;
                  $nuevafecha=date("d/m/Y",$nueva);
                  $fechaElab=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
                  if($datos[fecha_cierre]){
                    (list($date,$hour)=explode(' ',$datos[fecha_cierre]));
                    (list($anoFC,$mesFC,$diaFC)=explode('-',$date));                
                    $fechaEgreso=date("d/m/Y",mktime(0,0,0, $mesFC,$diaFC,$anoFC));
                  }
                  elseif($datos[fecha_cierre_movimientos_habitacion]){
                    (list($date,$hour)=explode(' ',$datos[fecha_cierre_movimientos_habitacion]));
                    (list($anoFC,$mesFC,$diaFC)=explode('-',$date));                
                    $fechaEgreso=date("d/m/Y",mktime(0,0,0, $mesFC,$diaFC,$anoFC));
                  }
                  else
                  {
                    $fechaEgreso=FechaStamp($datos[fecha_registro]);
                  }
                  $html.="<tr><td width=190 height=\"25\">FECHA INGR.: ".FechaStamp($datos[fecha_registro])."</td><td width=190 height=\"25\">FECHA EGRE.: ".$fechaEgreso."</td><td width=190 height=\"25\">FECHA ELAB.: ".$fechaElab."</td><td width=190 height=\"25\">FECHA VENC.: ".$nuevafecha."</td></tr>";
                  //$html.="<tr><td width=230>DIRECCION: ".$datos[residencia_direccion]."</td><td width=230>TELEFONOS: ".$datos[residencia_telefono]."</td><td width=150>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                  $html.="</table>";
                //}
                $this->Ln(1);
                $this->WriteHTML($html);
        }
	//
        if($_SESSION['REPORTES']['VARIABLE']=='facturapaciente')
        {
                $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
                $this->SetFont('Arial','',8);
                //$this->Cell(0,2,'Pagina No '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',180,10,20)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                //$html.="<br><br><br>"; 
                $html.="<table border=0 width=100 align='center' border=0>";
                $html.="<tr><td width=760><BR><BR></td></tr>";
                //$html.="<tr><td width=460><B>".$datos[razon_social]."</B></td><td width=300 align='center'>FACTURA CAMBIARIA DE COMPRAVENTA</td></tr>";

                if(!empty($datos[prefijo]) AND !empty($datos[factura_fiscal]))
                {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT count(numerodecuenta) FROM fac_facturas_cuentas
                                            WHERE factura_fiscal=".$datos[factura_fiscal]."
                                            and prefijo='".$datos[prefijo]."'
                                            LIMIT 2 OFFSET 0";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        if($result->fields[0] > 1)
                        {
                                $datos[prefijo]='';
                                $datos[factura_fiscal]='';
                        }
                }
                $factura_fiscal=str_pad($datos[factura_fiscal], $datos[numero_digitos], "0", STR_PAD_LEFT);
                $html.="<tr><td width=200><B>".$datos[razon_social]."</B></td><td width=180 align=\"left\">".$datos[tipoid].": ".$datos[id]."</td><td width=280 align='center'>FACTURA DE VENTA</td><td width=100>No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
                //$html.="<tr><td width=460>".$datos[tipoid].": ".$datos[id]."</td><td width=300>No. ".$datos[prefijo]."".$factura_fiscal."</td></tr>";
								$datostelefonos = substr($datos[telefonos],0,8);
                $html.="<tr><td width=230>DIRECCION: ".$datos[direccion]."</td><td width=230> TELEFONOS: ".$datostelefonos."</td><td width=300>".$datos[municipio]."-".$datos[departamento]."</td></tr>";
								$datostelefonos = substr($datos[telefonos],8,17);
								if(!empty($datostelefonos))
								{
									$html.="<tr><td width=230>&nbsp;</td><td width=230> ".$datostelefonos."</td><td width=300>&nbsp;</td></tr>";
								}
                //$html.="<tr><td width=760 colspan=\"2\">".$datos[texto1]."</td></tr>";
                $html.="<tr><td width=760 colspan=\"2\">".substr("$datos[texto1]",0,120)."</td></tr>";
                $html.="<tr><td width=760 colspan=\"2\" align='center'>".substr("$datos[texto1]",120,256)."</td></tr>";
                $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="<tr><td width=270>PACIENTE: ".$datos[nombre]."</td></tr>";
                if($datos[fecha_cierre]){
                    (list($date,$hour)=explode(' ',$datos[fecha_cierre]));
                    (list($anoFC,$mesFC,$diaFC)=explode('-',$date));                
                    $fechaEgreso=date("d/m/Y",mktime(0,0,0, $mesFC,$diaFC,$anoFC));
                }
                $html.="<tr><td width=190>HIS/CLI: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td><td width=150>FECHA INGR.: ".FechaStamp($datos[fecha_registro])."</td><td width=150>FECHA EGRE.: ".$fechaEgreso."</td><td width=150>CUENTA: ".$datos[numerodecuenta]."</td></tr>";
                $html.="<tr><td width=450><B>PLAN: ".$datos[plan_descripcion]."</B></td><td width=30>&nbsp;</td><td width=150>INGRESO: ".$datos[ingreso]."</td></tr>";
                $html.="<tr><td width=450>DPTO: ".$datos[descripcion]."</td></tr>";
                $fecha=explode("-",$datos[fechafac]);
                $nueva = mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]) + 30 * 24 * 60 * 60;
                $nuevafecha=date("d/m/Y",$nueva);
                $fechaElab=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
                //$html.="<tr><td width=450>DIRECCION: ".$datos[direccion_tercero]."</td></tr>";
                $html.="<tr><td width=250>TELEFONOS: ".$datos[telefono_tercero]."</td><td width=150>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                $html.="</table>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        if($_SESSION['REPORTES']['VARIABLE']=='facturaconcepto')
        {
                $datos=$_SESSION['REPORTES']['FACTURACONCEPTO']['ARREGLO'];

                $this->SetFont('Arial','',7);
                $this->Cell(0,2,'Pagina No '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',170,9,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html.="<table border=0 width=100 align='center' border=0>";
                $html.="<tr><td width=760><BR><BR></td></tr>";
                $html.="<tr><td width=460><B>".$datos[0][razon_social]."</B></td><td width=300 align='center'>FACTURA DE VENTA</td></tr>";

                $html.="<tr><td width=460>".$datos[0][tipoid].": ".$datos[0][id]."</td><td width=300>No. ".$datos[0][prefijo]."".$datos[0][factura_fiscal]."</td></tr>";
                $html.="<tr><td width=230>DIRECCION: ".$datos[0][direccion]."</td><td width=230> TELEFONOS: ".$datos[0][telefonos]."</td><td width=300>".$datos[0][municipio]."-".$datos[0][departamento]."</td></tr>";
                $html.="<tr><td width=760 colspan=\"2\">".$datos[0][texto1]."</td></tr>";
                $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="<tr><td width=500>CLIENTE: ".$datos[0][nombre_tercero]."</td></tr>";
                $html.="<tr><td width=460>".$datos[0][tipo_id_tercero].": ".$datos[0][tercero_id]."</td></tr>";
                $html.="<tr><td width=450>DPTO: ".$datos[0][centro_atencion]."</td></tr>";
                $fecha=explode("-",$datos[0][fecha_registro]);
                $nueva = mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]) + 30 * 24 * 60 * 60;
                $nuevafecha=date("d/m/Y",$nueva);
                $fechaElab=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
                if($datos[fecha_cierre]){
                    (list($date,$hour)=explode(' ',$datos[fecha_cierre]));
                    (list($anoFC,$mesFC,$diaFC)=explode('-',$date));                
                    $fechaEgreso=date("d/m/Y",mktime(0,0,0, $mesFC,$diaFC,$anoFC));
                }
                //$html.="<tr><td width=230>DIRECCION: ".$datos[residencia_direccion]."</td><td width=230>TELEFONOS: ".$datos[residencia_telefono]."</td><td width=150>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                $html.="<tr><td width=450>DIRECCION: ".$datos[0][direccion_tercero]."</td></tr>";
                $html.="<tr><td width=250>TELEFONOS: ".$datos[0][telefono_tercero]."</td><td width=150>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                //$html.="<tr><td width=270>PACIENTE: ".$datos[nombre]."</td></tr>";
                //$html.="<tr><td width=190>HIS/CLI: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td><td width=150>FECHA INGR.: ".FechaStamp($datos[fecha_registro])."</td><td width=150>FECHA EGRE.: ".$fechaEgreso."</td></tr>";
                $html.="</table>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        //darling hoja de cargos
        if($_SESSION['REPORTES']['VARIABLE']=='hoja_cargos')
        {
                list($dbconn) = GetDBconn();
                $querys = "select usuario
                                        from system_usuarios
                                        where usuario_id=".UserGetUID()."";
                $result = $dbconn->Execute($querys);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                }

                $usu=$result->GetRowAssoc($ToUpper = false);
                $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
                $this->SetFont('Arial','',7);
                $this->Cell(100,10,'Pagina No '.$this->PageNo());
                if($this->PageNo() <> 1)
                {
                  $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
                  $html.="<tr><td width=760>&nbsp;</td></tr>";
                  $html.="<tr><td width=250>CUENTA No.: ".$datos[numerodecuenta]."</td><td width=300>PACIENTE: ".$datos[nombre]."</td><td width=210>DOCUMENTOS: ".$datos[tipo_id_paciente].": ".$datos[paciente_id]."</td></tr>";
                  $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="<tr><td width=60 align=\"CENTER\">F CARGO</td><td width=60 align=\"CENTER\">CARGO</td><td width=50 align=\"CENTER\">HAB</td><td width=50 align=\"CENTER\">DPTO</td><td width=200 align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width=40 align=\"CENTER\">CANT</td><td width=60 align=\"CENTER\">VALOR</td><td width=60 align=\"CENTER\">VALOR TOT</td><td width=60 align=\"CENTER\">VLR RECO</td><td width=60 align=\"CENTER\">VLR NO CUB</td><td width=40 align=\"CENTER\">TRAN</td><td width=40 align=\"CENTER\">USUARIO</td></tr>";
                  $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="</table>";
                }
                else
                {
                  $html="".$this->image('images/logocliente.png',170,9,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                  $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
                  $html.="<tr><td width=760>&nbsp;</td></tr>";
                  $html.="<tr><td width=760><B>".$datos[razon_social]."</B></td></tr>";
                  $html.="<tr><td width=610>".$datos[tipoid].": ".$datos[id]."</td><td width=150>FECHA: ".date('d/m/Y')."</td></tr>";
                  $html.="<tr><td width=610>&nbsp;</td><td width=150>HORA: ".date('H:m')."</td></tr>";
                  $html.="<tr><td width=160>&nbsp;</td><td width=450 align='CENTER'>HOJA DE CARGOS</td><td width=150>USUARIO: ".$usu[usuario]."</td></tr>";
                  $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="<tr><td width=250>CUENTA No.: ".$datos[numerodecuenta]."</td><td width=300>PACIENTE: ".$datos[nombre]."</td><td width=210>DOCUMENTOS: ".$datos[tipo_id_paciente].": ".$datos[paciente_id]."</td></tr>";
                  $html.="<tr><td width=250>DIRECCION: ".$datos[direccion]."</td></td><td width=300>CIUDAD: ".$datos[municipio]."</td><td width=210>TELEFONOS: ".$datos[telefonos]."</td></tr>";
                  //$html.="<tr><td width=300>TELEFONOS: ".$datos[telefonos]."</td><td width=460>DOCUMENTOS: ".$datos[tipo_id_paciente].": ".$datos[paciente_id]."</td></tr>";
                  $fecha=explode("-",$datos[fecha_registro]);
                  $fechaIng=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
                  $html.="<tr><td width=250>MEDICO:</td><td width=300>HISTORIA: ".$datos[X]."</td><td width=210>FECHA INGRESO: ".$fechaIng."</td></tr>";
                  $html.="<tr><td width=400>EMPRESA: ".$datos[nombre_tercero]."  ".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td></tr><tr><td width=430>PLAN: (".$datos[plan_id].")  ".  $datos[plan_descripcion]."</td></tr>";
                  $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="<tr><td width=60 align=\"CENTER\">F CARGO</td><td width=60 align=\"CENTER\">CARGO</td><td width=50 align=\"CENTER\">HAB</td><td width=50 align=\"CENTER\">DPTO</td><td width=200 align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width=40 align=\"CENTER\">CANT</td><td width=60 align=\"CENTER\">VALOR</td><td width=60 align=\"CENTER\">VALOR TOT</td><td width=60 align=\"CENTER\">VLR RECO</td><td width=60 align=\"CENTER\">VLR NO CUB</td><td width=40 align=\"CENTER\">TRAN</td><td width=40 align=\"CENTER\">USUARIO</td></tr>";
                  $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                  $html.="</table>";
                }
                $this->Ln(1);
                $this->WriteHTML($html);
        }

        //VOUCHER CARGOS
	if($_SESSION['REPORTES']['VARIABLE']=='vouchercargos')
	{
		list($dbconn) = GetDBconn();
		$querys = "select usuario
			from system_usuarios
			where usuario_id=".UserGetUID()."";
		$result = $dbconn->Execute($querys);
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$usu=$result->GetRowAssoc($ToUpper = false);
		$datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
		$this->SetFont('Arial','',7);
		$this->Cell(100,10,'Pagina No '.$this->PageNo());
		$html="".$this->image('images/logocliente.png',180,9,17)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
		$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
		$html.="<tr><td width=760>&nbsp;</td></tr>";
		$html.="<tr><td width=280><B>".$datos[razon_social]."</B></td><td width=100>".$datos[tipoid].":  ".$datos[id]."</td><td width=100>FECHA:  ".date('d/m/Y')."</td><td width=100>HORA:  ".date('H:m')."</td><td width=100>USUARIO:  ".$usu[usuario]."</td></tr>";
		//$html.="<tr><td width=310>".$datos[tipoid].": ".$datos[id]."</td><td width=150>FECHA: ".date('d/m/Y')."</td><td width=150>HORA: ".date('H:m')."</td><td width=150>USUARIO: ".$usu[usuario]."</td></tr>";
		//$html.="<tr><td width=610>&nbsp;</td><td width=150>HORA: ".date('H:m')."</td></tr>";
		$html.="<tr><td width=50>&nbsp;</td><td width=700 align='CENTER'><B>VOUCHER CARGOS</B></td></tr>";
		$html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
		$html.="<tr><td width=250>CUENTA No.:  ".$datos[numerodecuenta]."</td><td width=300>PACIENTE:  ".$datos[nombre]."</td><td width=210>DOCUMENTO:  ".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td></tr>";
		$html.="<tr><td width=350>DIRECCION:  ".$datos[direccion]."</td></td><td width=200>CIUDAD:  ".$datos[municipio]."</td><td width=210>TELEFONOS:  ".$datos[telefonos]."</td></tr>";
		$fecha=explode("-",$datos[fecha_registro]);
		$fechaIng=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
		//$html.="<tr><td width=250>MEDICO:</td><td width=300>HISTORIA: ".$datos[X]."</td><td width=210>FECHA INGRESO: ".$fechaIng."</td></tr>";
		$html.="<tr><td width=350>EMPRESA:  ".$datos[nombre_tercero]."</td><td width=200>".$datos[tipo_id_tercero].":  ".$datos[tercero_id]."</td><td width=210>FECHA INGRESO:  ".$fechaIng."</td></tr>"; //jab
		//$html.="<tr><td width=400>EMPRESA: ".$datos[nombre_tercero]."  ".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td></tr>";
		//$html.="<tr><td width=550>PLAN:  (".$datos[plan_id].")  ".  $datos[plan_descripcion]."</td><td>MEDICO:  ".$_SESSION['REPORTES']['MEDICO']."</td></tr>";
		$html.="<tr><td width=550>PLAN:  (".$datos[plan_id].")  ".  $datos[plan_descripcion]."</td></tr>";//<td>MEDICO:  ".$_SESSION['NOMBRE']['MEDICO']."</td></tr>"; //jab
		$html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
		$html.="<tr><td width=60 align=\"CENTER\">F CARGO</td><td width=60 align=\"CENTER\">CARGO</td><td width=50 align=\"CENTER\">HAB</td><td width=50 align=\"CENTER\">DPTO</td><td width=200 align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width=40 align=\"CENTER\">CANT</td><td width=60 align=\"CENTER\">VALOR</td><td width=60 align=\"CENTER\">VALOR TOT</td><td width=60 align=\"CENTER\">VLR RECO</td><td width=60 align=\"CENTER\">VLR NO CUB</td><td width=40 align=\"CENTER\">TRAN</td><td width=40 align=\"CENTER\">USUARIO</td></tr>";
		$html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
		$html.="</table>";
		$this->Ln(1);
		$this->WriteHTML($html);
	}
        //FIN VOUCHER CARGOS 

        if($_SESSION['REPORTES']['VARIABLE']=='facturacion_recepcion')
        {
							$datos=$_SESSION['REPORTES']['RECEPCION']['ARREGLO'];
							if($datos[request][request][Estado] == 1)
							{
								$Estado = 'RECIBIDAS';
							}
							else
							if($datos[request][request][Estado] == 0)
							{
								$Estado = 'NO RECIBIDAS';
							}
							else
							if($datos[request][request][Estado] == 2)
							{
								$Estado = 'TODAS';
							}

							if(!empty($datos[request][request][fechaInicial])
									AND !empty($datos[request][request][fechaFinal]))
							{
								$periodo = $datos[request][request][fechaInicial].' - '.$datos[request][request][fechaFinal];
							}
							else
							{
								$periodo = ' TODOS ';
							}

							if($datos[request][request][departamentos] <> -1)
							{
									$dep = explode(',',$datos[request][request][departamentos]);
									list($dbconn) = GetDBconn();
									$querys = "select *
														from departamentos
														where departamento = '".$dep[1]."'";
									$result = $dbconn->Execute($querys);
									if ($dbconn->ErrorNo() != 0) {
																	$this->error = "Error al Cargar el Modulo";
																	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	return false;
									}
								$departamento = $result->GetRowAssoc($ToUpper = false);
							}
							else
							{
								$departamento[descripcion] = 'DEPARTAMENTO TODOS';
							}
							$this->SetFont('Arial','',7);
							$html="".$this->image('images/logocliente.png',170,9,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
							$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
							$html.="<tr><td width=760>&nbsp;</td></tr>";
							$html.="<tr><td width=760  align=\"CENTER\"><B>REPORTE DE FACTURAS CON ESTADO ".$Estado."</B></td></tr>";
							$html.="<tr><td width=760  align=\"CENTER\"><B>PERIODO ".$periodo."</B></td></tr>";
							$html.="<tr><td width=760  align=\"CENTER\"><B>".$departamento[descripcion]." - ".$departamento[departamento]."</B></td></tr>";
							//$html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
							$html.="</table>";
							$this->Ln(1);
							$this->WriteHTML($html);
        }
    
    if($_SESSION['REPORTES']['VARIABLE']=='hoja_cargos_agrupada')
    {
        list($dbconn) = GetDBconn();
        $querys = "select usuario
                    from system_usuarios
                    where usuario_id=".UserGetUID()."";
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }

        $usu=$result->GetRowAssoc($ToUpper = false);
        $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
        $this->SetFont('Arial','',9);
//        $this->Cell(100,9,'Pagina No '.$this->PageNo());
        $html="".$this->image('images/logocliente.png',130,9,30)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
        $html .="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
        //$html.="<tr><td width=760 align='LEFT'>".$this->image('images/logocliente.jpg',170,9,18)."</td></tr>";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
        //$html.="<tr><td width=760>".$this->Cell(9,100,'Pagina No '.$this->PageNo())."</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760><B>".$datos[razon_social]."</B></td></tr>";
        //$html.="<tr><td width=610>".$datos[tipoid].": ".$datos[id]."</td><td width=150>FECHA: ".date('d/m/Y')."</td></tr>";
        $html.="<tr><td width=760>".$datos[tipoid].": ".$datos[id]."</td></tr>";
                $html.="<td width=760>FECHA: ".date('d/m/Y')."</td></tr>";
        //$html.="<tr><td width=610>&nbsp;</td><td width=150>HORA: ".date('H:m')."</td></tr>";
        $html.="<tr><td width=760>HORA: ".date('H:m')."</td></tr>";
        $html.="<tr><td width=760>USUARIO: ".$usu[usuario]."</td></tr>";
        //$html.="<tr><td width=160>&nbsp;</td><td width=450 align='CENTER'>HOJA DE CARGOS</td><td width=150>USUARIO: ".$usu[usuario]."</td></tr>";
        $html.="<tr><td width=760 align='CENTER'><B>ORDEN DE SERVICIO Nro: ".$datos[numerodecuenta]."</B></td></tr>";
        $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
        //$html.="<tr><td width=250>CUENTA No.: ".$datos[numerodecuenta]."</td><td width=300>PACIENTE: ".$datos[nombre]."</td><td width=210>DOCUMENTOS: ".$datos[tipo_id_paciente].": ".$datos[paciente_id]."</td></tr>";
        $html.="<tr><td width=550>PACIENTE: ".$datos[nombre]."</td><td width=210>DOCUMENTOS: ".$datos[tipo_id_paciente].": ".$datos[paciente_id]."</td></tr>";
        $html.="<tr><td width=250>DIRECCION: ".$datos[direccion]."</td></td><td width=300>CIUDAD: ".$datos[municipio]."</td><td width=210>TELEFONOS: ".$datos[telefonos]."</td></tr>";
        //$html.="<tr><td width=300>TELEFONOS: ".$datos[telefonos]."</td><td width=460>DOCUMENTOS: ".$datos[tipo_id_paciente].": ".$datos[paciente_id]."</td></tr>";
        $fecha=explode("-",$datos[fecha_registro]);
        $fechaIng=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
        $html.="<tr><td width=250>MEDICO:</td><td width=300>HISTORIA: ".$datos[X]."</td><td width=210>FECHA INGRESO: ".$fechaIng."</td></tr>";
        $html.="<tr><td width=400>EMPRESA: ".$datos[nombre_tercero]."  ".$datos[tipo_id_tercero].": ".$datos[tercero_id]."</td></tr><tr><td width=430>PLAN: (".$datos[plan_id].")  ".  $datos[plan_descripcion]."</td></tr>";
        $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
        $html.="<tr><td width=70 align=\"CENTER\">CARGO</td><td width=400 align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width=50 align=\"CENTER\">CANT</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">V.UNT</td><td width=80 align=\"CENTER\">V.PACIENTE</td><td width=80 align=\"CENTER\">V.CLIENTE</td></tr>";
        $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
        $html.="</table>";
        $this->Ln(1);
        $this->WriteHTML($html);
    }
    
        //darling hoja de cargos por transaccion
        if($_SESSION['REPORTES']['VARIABLE']=='hoja_transaccion')
        {
                list($dbconn) = GetDBconn();
                $querys = "select usuario
                                        from system_usuarios
                                        where usuario_id=".UserGetUID()."";
                $result = $dbconn->Execute($querys);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                }

                $usu=$result->GetRowAssoc($ToUpper = false);

                $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
                $this->SetFont('Arial','',7);
                $this->Cell(100,10,'Pagina No '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',170,9,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
                $html.="<tr><td width=760>&nbsp;</td></tr>";
                $html.="<tr><td width=760><B>".$datos[razon_social]."</B></td></tr>";
                $html.="<tr><td width=610>".$datos[tipoid].": ".$datos[id]."</td><td width=150>FECHA: ".date('d/m/Y')."</td></tr>";
                $html.="<tr><td width=610>&nbsp;</td><td width=150>HORA: ".date('h:m:s')."</td></tr>";
                $html.="<tr><td width=160>&nbsp;</td><td width=450 align='CENTER'>HOJA DE CARGOS</td><td width=150>USUARIO: ".$usu[usuario]."</td></tr>";
                $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="<tr><td width=200>HOJA DE CARGOS No.: 123456</td><td width=150 align='center'>FACTURA: ".$datos[prefijo]."".$datos[factura_fiscal]."</td><td width=410>PACIENTE: ".$datos[nombre]."</td></tr>";
                $html.="<tr><td width=300>DIRECCION: ".$datos[direccion]."</td></td><td width=460>CIUDAD: ".$datos[municipio]."</td></tr>";
                $html.="<tr><td width=300>TELEFONOS: ".$datos[telefonos]."</td><td width=460>DOCUMENTOS: ".$datos[tipoid].": ".$datos[id]."</td></tr>";
                $html.="<tr><td width=300>MEDICO:</td><td width=460>HISTORIA: ".$datos[X]."</td></tr>";
                $html.="<tr><td width=300>FECHA INGRESO: ".FechaStamp($datos[fecha_registro])."</td><td width=460>EMPRESA: ".$datos[nombre_tercero]."</td></tr>";
                $html.="<tr><td width=300>&nbsp;</td><td width=460>PLAN: (".$datos[plan_id].")  ".  $datos[plan_descripcion]."</td></tr>";
                $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="<tr><td width=70 align=\"CENTER\">F CARGO</td><td width=70 align=\"CENTER\">CARGO</td><td width=50 align=\"CENTER\">HAB</td><td width=50 align=\"CENTER\">DPTO</td><td width=160 align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width=20 align=\"CENTER\">N</td><td width=40 align=\"CENTER\">CANT</td><td width=60 align=\"CENTER\">VALOR</td><td width=60 align=\"CENTER\">VALOR TOT</td><td width=60 align=\"CENTER\">VLR RECO</td><td width=60 align=\"CENTER\">VLR PACI</td><td width=60 align=\"CENTER\">TRAN2</td></tr>";
                $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="</table>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        //darling facturas de un usuario
        if($_SESSION['REPORTES']['VARIABLE']=='facturas_usuario')
        {
                $datos=$_SESSION['REPORTES']['FACUSUARIOS']['ARREGLO'];
                $this->SetFont('Arial','',7);
                $this->Cell(100,10,'Pagina No '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',170,9,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
                $html.="<tr><td width=760>&nbsp;</td></tr>";
                $html.="<tr><td width=760><B>".$datos[0][razon_social]."</B></td></tr>";
                $html.="<tr><td width=610>".$datos[0][tipoid].": ".$datos[0][id]."</td><td width=150>FECHA: ".date('d/m/Y')."</td></tr>";
                $html.="<tr><td width=610>&nbsp;</td><td width=150>HORA: ".date('h:m:s')."</td></tr>";
                $html.="<tr><td width=160>&nbsp;</td><td width=450 align='CENTER'>FACTURAS</td><td width=150>USUARIO: ".$datos[0][usuario]."</td></tr>";
                $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="</table>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        //caso de duvan de la estacion de enfermeria.......
        if($_SESSION['REPORTES']['VARIABLE']=='estacion_enf')
        {
            $this->SetFont('Arial','',7);
            $this->Cell(10,2,'Pagina '.$this->PageNo());
            $html="".$this->image('images/logocliente.png',10,4,15)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
            $html .= "      <br><TABLE WIDTH=\"100\" border=\"1\" align=\"center\" >";
            $html .= "          <TR>";
            $html.= "               <TD align='center' WIDTH=763 ><font color='black'><b>".$_SESSION['ESTACION_ENFERMERIA']['EMP']."</b></font></TD>";
            $html.= "           </TR>";
            $html .= "          <TR>";
            $TITULO='REPORTE DE PACIENTES INTERNOS  DE LA ESTACION :'." ".$_SESSION['ESTACION_ENFERMERIA']['NOM'];
            $html.= "               <TD WIDTH=763 ><font color='black' size=10><b>$TITULO</b></font></TD>";
            $html.= "           </TR>";
            $html.="<TABLE>";
            $html .= "      <TABLE WIDTH=100% border=\"1\" align=\"center\" >";


            $html.= "               <TR><TD  WIDTH='80' ><font color='white'><b>PIEZA</b></font></TD>";
            $html.= "               <TD WIDTH='80' ><font color='white'><b>HAB</b></font></TD>";
            $html.= "               <TD WIDTH='260'   ><font color='white'><b>PACIENTE</b></font></TD>";
            $html.= "               <TD WIDTH='110' ><font color='white'><b>INGRESO</b></font></TD>";
            $html.= "               <TD WIDTH='155' ><font color='white'><b>PLAN</b></font></TD>";
            $html.= "               <TD WIDTH='78'><font color='white'><b>CUENTA</b></font></TD></TR>";
            $html.="</TR>";
            $this->Ln(1);
            $this->WriteHTML($html);
        }

        //caso de duvan del censo de la estacion...
        if($_SESSION['REPORTES']['VARIABLE']=='censo')
        {
                $this->SetFont('Arial','',7);
                $this->Cell(0,2,'Pagina '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',10,4,15)."";
                $html = "       <br><TABLE WIDTH=\"100\" border=\"1\" align=\"center\" >";
                $html .= "          <TR>";
                $html.= "               <TD align='center' WIDTH=765 ><font color='black'><b>".$_SESSION['ESTACION_ENFERMERIA']['EMP']."</b></font></TD>";
                $html.= "           </TR>";
                $html .= "          <TR>";
                $TITULO='CENSO DIARIO DE PACIENTES INTERNOS - HOSPITALIZADOS DE LA ESTACION :'." ".$_SESSION['ESTACION_ENFERMERIA']['NOM'];
                $html.= "               <TD WIDTH=765 ><font color='black' size=10><b>$TITULO</b></font></TD>";
                $html.= "           </TR>";
                $html.="<TABLE>";

                $html .= "      <TABLE WIDTH=100% border=\"1\" align=\"center\" >";
                $html.= "               <TR><TD  WIDTH='70' ><font color='white'><b>PIEZA</b></font></TD>";
                $html.= "               <TD WIDTH='65' ><font color='white'><b>HAB</b></font></TD>";
                $html.= "               <TD  WIDTH='250'   ><font color='white'><b>PACIENTE</b></font></TD>";
                $html.= "               <TD WIDTH='100' ><font color='white'><b>INGRESO</b></font></TD>";
                $html.= "               <TD WIDTH='169' ><font color='white'><b>PLAN</b></font></TD>";
                $html.= "               <TD WIDTH='60'><font color='white'><b>CUENTA</b></font></TD>";
                $html.= "               <TD WIDTH='51'><font color='white'><b>DIAS</b></font></TD></TR>";
                $html.="</TR>";
                $html.="<TR>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }


    //caso de claudia liliana val
        if($_SESSION['REPORTES']['VARIABLE']=='formula_hosp')
        {
                $html="<TABLE BORDER='0' WIDTH='1520'>";
                $html.="<TR><TD ALIGN='CENTER'>rtret";
                $html="".$this->image('images/logocliente.png',4,3,25)."";
                $html.="</TD></TR></TABLE>";
                $this->Ln(1);
                $this->Ln(1);
                $this->WriteHTML($html);
        }


        //caso de duvan del cierre de caja...
        if($_SESSION['REPORTES']['VARIABLE']=='cierre_caja')
        {
                $this->SetFont('Arial','',7);
                //$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
                //$html="".$this->image('images/logocliente.jpg',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html = "<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
                $html .= "<tr>";
                $space=" ";
                if(empty($_SESSION['TMP']['CONTROL_CIERRE']['DPTO'])
                    AND $_SESSION['TMP']['CONTROL_CIERRE']['CUENTA']!='08'
                    AND $_SESSION['TMP']['CONTROL_CIERRE']['CUENTA']!='04'
                    AND $_SESSION['TMP']['CONTROL_CIERRE']['CUENTA']!='03')
                {
                    $datos=DatosEncabezadoEmpresaRecibo();
                }
                else
                if($_SESSION['TMP']['CONTROL_CIERRE']['CUENTA']=='08')
                {       $datos=DatosEncabezadoEmpresa();}
                else
                {       $datos=DatosEncabezadoEmpresa();}
                $info=TraerDatoUsuario();
                $datos_cierre=TraerDatoCierre($feha_confirmacion_cierre=true);
                $_SESSION['observa']=$datos_cierre['observaciones'];
                $_SESSION['login']=$info['usuario'];
                $impresion="IMPRESION :";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $fech=explode(".",$datos_cierre[fecha_confirmacion]);
                $TITULO='REPORTE DE CIERRE DE CAJA :'." ".strtoupper($datos['descuenta'])."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";
                //esta parte es nueva de sos


                $html.= "<tr>";
                $TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$datos_cierre[cierre_de_caja_id]."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";


                $html.= "<tr>";
                $TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";    
                
                $html.="<table>";
                $html.= "<table width=100% border=\"1\" align=\"center\" >";
                $html.= "<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
                $html.= "<td width='80' ><font color='white'><b>FECHA</b></font></td>";
                $html.= "<td  width='310'><font color='white'><b>PACIENTE</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>BONOS</b></font></td>";

                /*if(!empty($_SESSION['REF_DPTO']))
                {
                    $html.= "<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
                }*/
                $html.="<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
                $html.="</tr>";
                $html.="<tr>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }

        //CUADRE DE CAJA
        if($_SESSION['REPORTES']['VARIABLE']=='cuadre_caja')
        {
                $this->SetFont('Arial','',7);
                //$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
                //$html="".$this->image('images/logocliente.jpg',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html = "<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
                $html.= "<tr>";
                $space=" ";
                if(empty($_SESSION['TMP']['CONTROL_CIERRE']['DPTO']) AND $_SESSION['CAJA']['TIPOCUENTA']!='08' AND $_SESSION['CAJA']['TIPOCUENTA']!='03')
                {
                    $datos=DatosEncabezadoEmpresaRecibo();
                }
                else
                if($_SESSION['CAJA']['TIPOCUENTA']=='08' OR $_SESSION['CAJA']['TIPOCUENTA']=='03')
                {       $datos=DatosEncabezadoEmpresaInventarios();}
                else
                {       $datos=DatosEncabezadoEmpresa();}
                $info=TraerDatoUsuario();
                $datos_cierre=TraerDatoCierre();
                $_SESSION['observa']=$datos_cierre['observaciones'];
                $_SESSION['login']=$info['usuario'];
                $impresion="IMPRESION :";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $fech=explode(".",$datos_cierre[fecha_registro]);
                $TITULO='REPORTE DE CUADRE DE CAJA :'." ".strtoupper($datos['descuenta'])."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";
                //esta parte es nueva de sos


                $html .= "<tr>";
                $TITULO='REPORTE DE CUADRE DE CAJA No :'." ".$datos_cierre[cierre_caja_id]."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";


                $html .= "<tr>";
                $TITULO='FECHA DE CUADRE DE CAJA :'."$fech[0]";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";    
                
                $html.="<table>";
                $html.= "<table width=100% border=\"1\" align=\"center\" >";
                $html.= "<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
                $html.= "<td width='80' ><font color='white'><b>FECHA</b></font></td>";
                $html.= "<td  width='310'><font color='white'><b>PACIENTE</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>BONOS</b></font></td>";

                /*if(!empty($_SESSION['REF_DPTO']))
                {
                    $html.= "<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
                }*/
                $html.= "<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
                $html.="</tr>";
                $html.="<tr>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        //FIN CUADRE CAJA
        
        // cierre de caja TOTAL...
        if($_SESSION['REPORTES']['VARIABLE']=='cierre_de_caja')
        {
                $this->SetFont('Arial','',7);
                //$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
                //$html="".$this->image('images/logocliente.jpg',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html = "<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
                $html.= "<tr>";
                $space=" ";
                if(empty($_SESSION['TMP']['CONTROL_CIERRE']['DPTO'])
                    AND $_SESSION['CAJA']['TIPOCUENTA']!='08'
                    AND $_SESSION['CAJA']['TIPOCUENTA']!='03')
                { 
                    $datos=DatosEncabezadoEmpresaRecibo();
                }
                else
                {$datos=DatosEncabezadoEmpresa();}
                $info=TraerDatoUsuario();
                $datos_cierre=TraerDatoCierreDeCaja();
                $_SESSION['observa']=$datos_cierre['observaciones'];
                $_SESSION['login']=$info['usuario'];
                $impresion="IMPRESION :";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $fech=explode(".",$datos_cierre[fecha_registro]);
                $TITULO='REPORTE DE CIERRE DE CAJA :'." ".$datos['caja_id']."-".strtoupper($datos['descuenta'])."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";
                //esta parte es nueva de sos


                $html.= "<tr>";
                $TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$datos_cierre[cierre_de_caja_id]."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";


                $html.= "<tr>";
                $TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";    
                
                $html.="<table>";
                $html.= "<table width=100% border=\"1\" align=\"center\" >";
                $html.= "<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
                $html.= "<td width='80' ><font color='white'><b>FECHA</b></font></td>";
                if($_SESSION['CIERRE']['CIERRE_TOTAL']['cuenta']=='03' OR $_SESSION['CAJA']['TIPOCUENTA']=='03')
                    $html.= "<td  width='310'   ><font color='white'><b>CLIENTE</b></font></td>";
                else
                    $html.= "<td  width='310'   ><font color='white'><b>PACIENTE</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>BONOS</b></font></td>";

                /*if(!empty($_SESSION['REF_DPTO']))
                {
                    $html.= "<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
                }*/
                $html.= "<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
                $html.="</tr>";
                $html.="<tr>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }

        // cierre de caja TOTAL CONTROL...
        if($_SESSION['REPORTES']['VARIABLE']=='cierre_de_caja_control')
        {
                $this->SetFont('Arial','',7);
                //$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
                //$html="".$this->image('images/logocliente.jpg',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html = "<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
                $html.= "<tr>";
                $space=" ";
/*              if(empty($_SESSION['TMP']['CONTROL_CIERRE']['DPTO']))
                {
                    $datos=DatosEncabezadoEmpresaRecibo();
                }
                else
                {       $datos=DatosEncabezadoEmpresaControl();}*/
                    $datos=DatosEncabezadoEmpresaControl();
                $info=TraerDatoUsuario();
                $datos_cierre=TraerDatoCierreDeCajaControl();
                $_SESSION['observa']=$datos_cierre['observaciones'];
                $_SESSION['login']=$info['usuario'];
                $impresion="IMPRESION :";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $html.= "<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
                $html.= "</tr>";
                $html.= "<tr>";
                $fech=explode(".",$datos_cierre[fecha_registro]);
                $TITULO='REPORTE DE CIERRE DE CAJA :'." ".strtoupper($datos['descuenta'])."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";
                //esta parte es nueva de sos


                $html.= "<tr>";
                $TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$datos_cierre[cierre_de_caja_id]."";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";


                $html.= "<tr>";
                $TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
                $html.= "<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
                $html.= "</tr>";    
                
                $html.="<table>";
                $html.= "<table width=100% border=\"1\" align=\"center\" >";
                $html.= "<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
                $html.= "<td width='80' ><font color='white'><b>FECHA</b></font></td>";
                $html.= "<td  width='310'   ><font color='white'><b>PACIENTE</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
                $html.= "<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
                $html.= "<td width='65'><font color='white'><b>BONOS</b></font></td>";

                /*if(!empty($_SESSION['REF_DPTO']))
                {
                    $html.= "<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
                }*/
                $html.="<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
                $html.="</tr>";
                $html.="<tr>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }

        //caso de solicitudes

        if($_SESSION['REPORTES']['VARIABLE']=='solicitudes')
        {
            $html="<TABLE BORDER='0' WIDTH='1520'>";
            $html.="<TR>";
            $html.="<TD ALIGN='CENTER'>";
            $this->SetFont('Arial','',8);
            if(is_file('images/logocliente.png'))
            {
                $html.="".$this->image('images/logocliente.png',10,6,18)."";
            }
            $datos[0]=$_SESSION['SOLICITUD']['DATOS'];
            $dat=$_SESSION['SOLICITUD']['DAT'];
            $fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
            $edad=CalcularEdad($fechaI,$fechaF);

            $html.="</TD>";
            $html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
            $html.="<b>".strtoupper($datos[0][razon_social])."</b>";
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
            $html.="<b>".$datos[0][tipo_id_tercero].' : '.$datos[0][id]."</b>";
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25><br><br>FECHA: </TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".date('d/m/Y h:m')."</TD>";
            //$html.="<TD WIDTH='400' HEIGHT=25>ATENDIDO : ".$datos[0][usuario_id].' - '.$datos[0][usuario]."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>DOCUMENTO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>HC:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>";
            if($datos[0]['historia_numero']!="")
            {
                if($datos[0]['historia_prefijo']!="")
                {
                    $html.= $datos[0]['historia_numero']." - ". $datos[0]['historia_prefijo'];
                }
                else
                {
                    $html.= $datos[0]['paciente_id']." - ".$datos[0]['historia_prefijo'];
                }
            }
            else
            {
                $html.= $datos[0]['paciente_id']." - ".$datos[0]['tipo_id_paciente'];
            }
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>NOMBRE:</TD>";
            $nombre = $datos[0]['nombre'];
            $nombre = substr("$nombre", 0, 38);
            $html.="<TD WIDTH='270' HEIGHT=25><font size=5><b>".strtoupper($nombre).""."</b></font></TD>";
            $html.="<TD WIDTH='40' HEIGHT=25>EDAD:</TD>";
            $html.="<TD WIDTH='70' HEIGHT=25>".$edad['anos'].' A�S'."</TD>";
            $html.="<TD WIDTH='40' HEIGHT=25>SEXO:</TD>";
            $html.="<TD WIDTH='70' HEIGHT=25>".$datos[0]['sexo_id']."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>CLIENTE:</TD>";
    //      $html.="<TD WIDTH='110' HEIGHT=25></TD>";
            $cliente = $datos[0]['nombre_tercero'];
            $cliente = substr("$cliente", 0, 30);
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($cliente)."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>PLAN:</TD>";
            $plan = $datos[0]['plan_descripcion'];
            $plan = substr("$plan", 0, 30);
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($plan)."."."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>TIPO DE AFILIADO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['tipo_afiliado_nombre'])."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>RANGO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['rango'])."</TD>";
            $html.="</TR>";
            $pro=Profesional($dat[0][evolucion_id]);
            for($j=0; $j<sizeof($pro); $j++)
            {
                if($j==0)
                {
                    $espe.=$pro[$j][descripcion];
                }
                else
                {
                    $espe.="  -  ".$pro[$j][descripcion];
                }
            }
            $html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>PROFESIONAL :</TD>";
            $profesional = $pro[0][nombre_tercero];
            $profesional = substr("$profesional", 0, 40);
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($profesional)."."."</TD>";
            //$html.="<TD WIDTH='2' HEIGHT=25>-</TD>";
            $espe = substr("$espe", 0, 40);
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($espe)."."."</TD>";
            $html.="</TR>";
            $html.="</TABLE><br>";
            $this->Ln(1);
            $this->WriteHTML($html);
        }

        //Para casos de incapacidad
        if($_SESSION['REPORTES']['VARIABLE']=='incapacidad')
        {
            $html="<TABLE BORDER='0' WIDTH='1520'>";
            $this->SetFont('Arial','',10);
            if(is_file('images/logocliente.png'))
            {
                $html.="".$this->image('images/logocliente.png',10,6,18)."";
            }
            $datos[0]=$_SESSION['INCAPACIDAD']['DATOS'];
            $fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
            $fechaF=FechaStampJT($datos[0][fecha_cierre]);
            $fechaIngreso=FechaStampJ($datos[0][fecha_ingreso]);
            $fechaEvolucion=FechaStampJ($datos[0][fecha_cierre]);
            $edad=CalcularEdad($fechaI,$fechaF);

            $html.="<TR>";
            $html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><b>SOLICITUD DE INCAPACIDADES Y/O LICENCIAS DE MATERNIDAD</b></TD>";
            $html.="</TR>";
            $this->WriteHTML($html);
            $html='';
            $this->SetFont('Arial','',8);
            $html.="<TR>";
            $html.="<TD ALIGN='LEFT' WIDTH='760' HEIGHT=25><BR><b>INFORMACION DEL COTIZANTE</b></BR></TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>Documento:</TD>";
            $html.="<TD WIDTH='260' HEIGHT=25>".$datos[0]['tipo_id_paciente']." ".$datos[0]['paciente_id']."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>HC:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>";
            if($datos[0]['historia_numero']!="")
            {
                if($datos[0]['historia_prefijo']!="")
                {
                    $html.= $datos[0]['historia_numero']." - ". $datos[0]['historia_prefijo'];
                }
                else
                {
                    $html.= $datos[0]['paciente_id']." - ".$datos[0]['historia_prefijo'];
                }
            }
            else
            {
                $html.= $datos[0]['paciente_id']." - ".$datos[0]['tipo_id_paciente'];
            }
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>Primer Nombre: </TD>";
            $nombre = $datos[0]['primer_nombre'];
            $html.="<TD WIDTH='260' HEIGHT=25><b>".strtoupper($nombre)."</b></TD>";
            $html.="<TD WIDTH='130' HEIGHT=25>Segundo Nombre: </TD>";
            if(empty($datos[0]['segundo_nombre']))
            {  $html.="<TD WIDTH='270' HEIGHT=25>&nbsp;</TD>";  }
            else
            {  $html.="<TD WIDTH='270' HEIGHT=25><b>".$datos[0]['segundo_nombre']."</b></TD>";  }
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>Primer Apellido: </TD>";
            $apellido = $datos[0]['primer_apellido'];
            $html.="<TD WIDTH='260' HEIGHT=25><b>".strtoupper($apellido)."</b></TD>";
            $html.="<TD WIDTH='130' HEIGHT=25>Segundo Apellido: </TD>";
            if(empty($datos[0]['segundo_apellido']))
            {  $html.="<TD WIDTH='260' HEIGHT=25>&nbsp;</TD>";  }
            else
            {  $html.="<TD WIDTH='260' HEIGHT=25><b>".$datos[0]['segundo_apellido']."</b></TD>";  }
            $html.="</TR>";
            /*$html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>NOMBRE:</TD>";
            $nombre = $datos[0]['paciente'];
            $nombre = substr("$nombre", 0, 38);
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($nombre)."."."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>No. DE INGRESO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['ingreso']."</TD>";
            $html.="</TR>";*/
            /*$html.="<TR>";
            $html.="<TD WIDTH='110' HEIGHT=25>EDAD:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".$edad['anos'].' A�S'."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>SEXO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['sexo_id']."</TD>";
            $html.="</TR>";*/
            $html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>No. Ingreso:</TD>";
            $html.="<TD WIDTH='260' HEIGHT=25>".$datos[0]['ingreso']."</TD>";
            /*$html.="<TD WIDTH='110' HEIGHT=25>FECHA INGRESO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".$fechaIngreso."</TD>";*/
            $html.="<TD WIDTH='110' HEIGHT=25>Fecha Solicitud:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".$fechaEvolucion."</TD>";
            $html.="</TR>";
            /*$html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>CLIENTE:</TD>";
            $cliente = $datos[0]['cliente'];
            $cliente = substr("$cliente", 0, 38);
            $html.="<TD WIDTH='260' HEIGHT=25>".strtoupper($cliente)."."."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>PLAN:</TD>";
            $plan = $datos[0]['plan_descripcion'];
            $plan = substr("$plan", 0, 38);
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($plan)."."."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>TIPO DE AFILIADO:</TD>";
            $html.="<TD WIDTH='260' HEIGHT=25>".strtoupper($datos[0]['tipo_afiliado_nombre'])."</TD>";
            $html.="<TD WIDTH='110' HEIGHT=25>RANGO:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['rango'])."</TD>";
            $html.="</TR>";*/
            $html.="<TR>";
            $html.="<TD WIDTH='120' HEIGHT=25>Ciudad:</TD>";
            $html.="<TD WIDTH='260' HEIGHT=25>".$datos[0]['municipio']."</TD>";
            $html.="</TR>";
            $html.="</TABLE><BR>";
            $this->Ln(1);
            $this->WriteHTML($html);
        }

        //Para casos de impresion de ordenes de servicio
        if ($_SESSION['REPORTES']['VARIABLE']=='orden_servicio')
        {
            IncludeLib("tarifario_cargos");
            IncludeLib("funciones_facturacion");
            IncludeLib("funciones_central_impresion");
            IncludeLib("ordenservicio");

            $datos[0]=$_SESSION['ORDENSERVICIO']['DATOS'];
            $dat=$_SESSION['ORDENSERVICIO']['DAT'];
            $vector=$_SESSION['ORDENSERVICIO']['VECTOR'];
            $fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
            $fechaIngreso=FechaStampJ($datos[0][fechaingreso]);
            $fechaSolicitud=FechaStampJ($datos[0][fechasolicitud]);
            $edad=CalcularEdad($fechaI,$fechaF);
            if(!empty($datos[0][ingreso]))
            {
                $cama=BuscarCamaActiva($datos[0][ingreso]);
            }
            else
            {
                $res=RevisarCama($vector['orden']);
                $cama=$res[cama];
            }

            $html="<TABLE BORDER='0' WIDTH='1520'>";
            $html.="<TR>";
            $html.="<TD ALIGN='CENTER'>";
            if(is_file('images/logocliente.png'))
            {
                $html.="".$this->image('images/logocliente.png',10,6,18)."";
            }
            $this->SetFont('Arial','',8);
            $html.="</TD>";
            $html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
            $html.="<b>".strtoupper($datos[0][razon_social])."</b>";
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
            $html.="<b>".$datos[0][tipo_id_tercero].' : '.$datos[0][id]."</b>";
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
            $html.="<b>ORDEN DE SERVICIO No. ".$dat[0][orden_servicio_id]."</b>";
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='115' HEIGHT=25><br><br>DOCUMENTO:</TD>";
            $html.="<TD WIDTH='275' HEIGHT=25>".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."</TD>";
            $html.="<TD WIDTH='115' HEIGHT=25>HC:</TD>";
            $html.="<TD WIDTH='275' HEIGHT=25>";
            if($datos[0]['historia_numero']!="")
            {
                if($datos[0]['historia_prefijo']!="")
                {
                    $html.= $datos[0]['historia_numero']." - ". $datos[0]['historia_prefijo'];
                }
                else
                {
                    $html.= $datos[0]['paciente_id']." - ".$datos[0]['historia_prefijo'];
                }
            }
            else
            {
                $html.= $datos[0]['paciente_id']." - ".$datos[0]['tipo_id_paciente'];
            }
            $html.="</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='115' HEIGHT=25>NOMBRE:</TD>";
            $nombre = $datos[0]['nombre'];
            $nombre = substr("$nombre", 0, 38);
            $html.="<TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".strtoupper($nombre)."."."</TD>";
            $html.="<TD WIDTH='115' HEIGHT=25>FECHA INGRESO: </TD>";
            $html.="<TD WIDTH='275' HEIGHT=25>".$fechaIngreso."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='40' HEIGHT=25>EDAD:</TD>";
            $html.="<TD WIDTH='75' HEIGHT=25>".$edad['anos'].' A�S'."</TD>";
            $html.="<TD WIDTH='40' HEIGHT=25>SEXO:</TD>";
            $html.="<TD WIDTH='235' HEIGHT=25>".$datos[0]['sexo_id']."</TD>";
            $html.="<TD WIDTH='265' HEIGHT=25>FECHA SOLICITUD:   ".$fechaSolicitud."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='115' HEIGHT=25>TIPO DE AFILIADO:</TD>";
            $html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($datos[0]['tipo_afiliado_nombre'])."</TD>";
            $html.="<TD WIDTH='115' HEIGHT=25>RANGO:</TD>";
            $html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($datos[0]['rango'])."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='115' HEIGHT=25>CLIENTE:</TD>";
            $cliente = $datos[0]['nombre_tercero'];
            $cliente = substr("$cliente", 0, 42);
            $html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($cliente)."."."</TD>";
            $html.="<TD WIDTH='115' HEIGHT=25>PLAN:</TD>";
            $plan = $datos[0]['plan_descripcion'];
            $plan = substr("$plan", 0, 42);
            $html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($plan)."."."</TD>";
            $html.="</TR>";

            $html.="<TR>";
            $html.="<TD WIDTH='200' HEIGHT=25>CAMA: ".$cama."</TD>";
            $html.="<TD WIDTH='300' HEIGHT=25>ATENDIDO : ".$datos[0][usuario_id].' - '.$datos[0][usuario]."</TD>";
            $html.="</TR>";
            $html.="</TABLE>";
            $this->Ln(1);
            $this->WriteHTML($html);
        }

        //Para casos de formulacion ambulatoria
        if($_SESSION['REPORTES']['VARIABLE']=='formulacio_amb')
        {
            IncludeLib("tarifario");
            $datos[0]=$_SESSION['FORMULA_AMB']['DATOS'];
            $fechaI=FechaStampT($datos[0][fecha_nacimiento]);
            $fechaF=FechaStampT($datos[0][fecha_cierre]);
            $fechaEvolucion=FechaStampC($datos[0][fecha_cierre]);
            $edad = CalcularEdad($fechaI,$fechaF);

            $html="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
            $this->SetFont('Arial','',12);

            $historia= '';
            $titulo = 'FORMULA MEDICA';

            if($datos[0][historia_numero]!="")
            {
                if($datos[0][historia_prefijo]!="")
                {
                    $historia=$datos[0][historia_numero]."-". $datos[0][historia_prefijo];
                }
                else
                {
                    $historia=$datos[0][paciente_id]."-".$datos[0][historia_prefijo];
                }
            }
            else
            {
                $historia=$datos[0][paciente_id]."-".$datos[0][tipo_id_paciente];
            }
		//jab--Impresion Razon Social, Direccion y Telefono
		
            $html.="<TR>";
            $html.="<TD ALIGN='LEFT' WIDTH='760' HEIGHT=25><b>".$datos[0]['razon_social']."</b></TD>";
            $html.="</TR>";
	    $html.="<TR>";
	    $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='90'>Direcci�:</TD>";
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22>".$datos[0]['direccion']."</TD>";
            $this->SetFont('Arial','',8);
	    $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='90'>Tel�ono:</TD>";
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22>".$datos[0]['telefonos']."</TD>";
	    $html.="</TR>";
	    $html.="<TR>";
            $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='90'>Ciudad:</TD>";
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22>".$datos[0]['municipio']."</TD>";
	    $html.="</TR>";
	    
	    $html.="<TR>";
	    $html.="<TD> </TD>";
            $html.="</TR>";
	    
	    $html.="<TR>";
            $html.="<TD ALIGN='LEFT' WIDTH='760' HEIGHT=25><b>FORMULA MEDICA</b></TD>";
            $html.="</TR>";
            $this->WriteHTML($html);
            $html='';
            $this->SetFont('Arial','',8);
            $html.="<TR>";
            //$html.="<TD WIDTH='90' HEIGHT=22>Ciudad:</TD>";
            //$html.="<TD WIDTH='270' HEIGHT=22>".$datos[0]['municipio']."</TD>";
            //jab--fin
	    $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Fecha Solicitud:</TD>";
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22>".$fechaEvolucion."</TD>";
            $html.="</TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='90'>Documento:</TD>";
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22>".$datos[0][tipo_id_paciente]." : ".$datos[0][paciente_id]."</TD>";
            $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Apellidos y Nombres: </TD>";
            $nombre = $datos[0][paciente];
            $nombre = substr("$nombre", 0, 38);
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22><B>".strtoupper($nombre)."</B></TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='90'>Diagn�tico(s) : </TD>";
            $diagnostico_ingreso = '';
            foreach ($datos[0][diagnostico_ingreso] as $k => $v)
            {
                if($diagnostico_ingreso == '')
                {  $diagnostico_ingreso.= $v[diagnostico_id];   }
                else
                {  $diagnostico_ingreso.= ' - '.$v[diagnostico_id];     }
            }

            $diagnostico_egreso = '';
            foreach ($datos[0][diagnostico_egreso] as $k => $v)
            {
                if($diagnostico_egreso == '')
                {  $diagnostico_egreso.= $v[diagnostico_id];  }
                else
                {  $diagnostico_egreso.= ' - '.$v[diagnostico_id];  }
            }
            $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='270'>".$diagnostico_ingreso." ".$diagnostico_egreso."</TD>";
            $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Tipo Contingencia:</TD>";
            $html.="<TD ALIGN='LEFT' WIDTH='270' HEIGHT=22>".strtoupper($datos[0][atencion])."</TD>";
            $html.="</TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='90'>Plan:</TD>";
            $plan = $datos[0][plan_descripcion];
            $plan = substr("$plan", 0, 38);
            $html.="<TD WIDTH='270' HEIGHT=22>".strtoupper($plan)."</TD>";
            $html.="<TD HEIGHT=22 WIDTH='110'>Estrato:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=22>".$datos[0][rango]."</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="</TD><TD HEIGHT=22 WIDTH='90'>Empresa:</TD>";
            $empleador = $datos[0][empleador];
            $empleador = substr("$empleador", 0, 38);
            $html.="<TD WIDTH='270' HEIGHT=22>".strtoupper($empleador)."</TD>";
            $html.="<TD HEIGHT=22 WIDTH='110'>Edad:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=22>".$edad['anos']." A�s</TD>";
            $html.="</TR>";
            $html.="<TR>";
            $html.="<TD HEIGHT=22 WIDTH='90'>Sexo:</TD>";
            $html.="<TD WIDTH='270' HEIGHT=22>".$datos[0][sexo_id]."</TD>";
            $html.="</TR>";


/*antiguo lo anterior fue para sos
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>IDENTIFICACION:</TD><TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".$datos[0][tipo_id_paciente]." : ".$datos[0][paciente_id]."</TD><TD HEIGHT=22 WIDTH='115'>HC:</TD><TD WIDTH='275' HEIGHT=22>".$historia."</TD></TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>PACIENTE: </TD>";
            $nombre = $datos[0][paciente];
            $nombre = substr("$nombre", 0, 38);
            $html.="<TD ALIGN='LEFT' WIDTH='275' HEIGHT=22><B>".strtoupper($nombre)."</B></TD>";
            $html.="<TD HEIGHT=22 WIDTH='115'>No. EVOL.:</TD>";
            $html.="<TD WIDTH='275' HEIGHT=22>".$datos[0][evolucion_id]."</TD>";
            $html.="</TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>EDAD:</TD><TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".$edad[edad_aprox]."</TD><TD HEIGHT=22 WIDTH='115'>SEXO:</TD><TD WIDTH='275' HEIGHT=22>".$datos[0][sexo_id]."</TD></TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>FECHA SOLICITUD:</TD><TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".$fechaEvolucion."</TD><TD HEIGHT=22 WIDTH='115'>TIPO AFI.:</TD><TD WIDTH='275' HEIGHT=22>".strtoupper($datos[0][tipo_afiliado_nombre])."</TD></TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>CLIENTE:</TD>";
            $cliente = $datos[0][cliente];
            $cliente = substr("$cliente", 0, 38);
            $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='275'>".strtoupper($cliente)."</TD>";
            $html.="<TD HEIGHT=22 WIDTH='115'>RANGO:</TD><TD WIDTH='275' HEIGHT=22></TD>".$datos[0][rango]."</TR>";
            $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>PLAN:</TD>";
            $plan = $datos[0][plan_descripcion];
            $plan = substr("$plan", 0, 38);
            $html.="<TD WIDTH='275' HEIGHT=22>".strtoupper($plan)."</TD>";
            $html.="</TD><TD HEIGHT=22 WIDTH='115'>EMPRESA:</TD>";
            $empleador = $datos[0][empleador];
            $empleador = substr("$empleador", 0, 38);
            $html.="<TD WIDTH='275' HEIGHT=22>".strtoupper($empleador)."</TD></TR>";
            $html.="<TR>";
            $html.="<TD WIDTH='115' HEIGHT=22>CIUDAD:</TD>";
            $html.="<TD WIDTH='275' HEIGHT=22>".$datos[0]['municipio']."</TD>";

            if ($datos[0][atencion]!= '')
            {
                $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='120'>T. CONTINGENCIA:</TD><TD ALIGN='LEFT' WIDTH='280' HEIGHT=22>".strtoupper($datos[0][atencion])."</TD>";
            }
            $html.="</TR>";
*/
            if ($datos[0][uso_controlado]==1)
            {
                $html.="<TR>";
                $html.="<TD WIDTH='110' HEIGHT=25>Direcci�.:</TD>";
                $dir = $datos[0][residencia_direccion];
                $dir = substr("$dir", 0, 38);
                $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($dir)."."."</TD>";
                $html.="<TD WIDTH='110' HEIGHT=25>Tel�ono:</TD>";
                $html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['residencia_telefono'])."</TD>";
                $html.="</TR>";
            }
            $html.="</TABLE><br>";
            $this->Ln(1);
            $this->WriteHTML($html);
        }

        //CASO CUENTA DE COBRO
        if($_SESSION['REPORTES']['VARIABLE']=='cuenta_cobro')
        {
            $empresa=EncabezadoCuentaCobro($_SESSION['CUENTACOBRO']['PALAN_ID'],$_SESSION['CUENTACOBRO']['INGRESO']);
            $empresaCC=EmpresaCuentaCobro($_SESSION['CUENTACOBRO']['EMPRESA']);
            $pacienteCC=PacienteCuentaCobro($_SESSION['CUENTACOBRO']['EMPRESA'],$_SESSION['CUENTACOBRO']['PREFIJO'],$_SESSION['CUENTACOBRO']['NUMERO']);
            $dat=DatosPrincipales($_SESSION['CUENTACOBRO']['CUENTA']);
            $this->SetFont('Arial','',7);
            $this->Cell(100,10,'Pagina No '.$this->PageNo());
            $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
            $html.="<tr><td width=760>&nbsp;</td></tr>";
            $html.="<tr><td width=760>&nbsp;</td></tr>";
            $html.="<tr><td width=760 align='CENTER'>FECHA: ".date('d/m/Y')."</td></tr>";
            $html.="<tr><td width=760 align='CENTER'>CUENTA DE COBRO No.</td></tr>";
            $html.="<tr><td width=760 align='CENTER'>".$_SESSION['CUENTACOBRO']['PREFIJO'].$_SESSION['CUENTACOBRO']['NUMERO']."</td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>EMPRESA ASEGURADORA:  </td><td width=380 align='LEFT'>".$empresa[nombre_tercero]." </td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>DEBE A:  </td><td width=380 align='LEFT'>".$empresaCC[razon_social]."</td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>&nbsp;</td><td width=380 align='LEFT'>".$empresaCC[tipo_id_tercero]."-".$empresaCC[id]."</td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>DIRECCI�:  </td><td width=380 align='LEFT'>".$empresa[direccion]."</td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>TELEFONOS:  </td><td width=380 align='LEFT'>".$empresa[telefono]."</td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>CENTRO DE COSTO:  </td><td width=380 align='LEFT'>".$dat[deservicio]."</td></tr>";
            $html.="<tr><td width=380 align='RIGHT'>LA SUMA DE:  </td><td width=380 align='LEFT'>$".FormatoValor($dat[total_cuenta])."</td></tr>";
            $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
            $html.="</table>";
            $this->Ln(1);
            $this->WriteHTML($html);
        }
        //

        if($_SESSION['REPORTES']['VARIABLE']=='factura_agrupada')
        {
                $datos=$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO'];
                $this->SetFont('Arial','',7);
                //$this->Cell(0,2,'Pagina No '.$this->PageNo());
                $html="".$this->image('images/logocliente.png',170,9,20)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
                $html.="<br><br><br>"; 
                $html.="<table border=0 width=100 align='center' border=0>";
                $html.="<tr><td width=760><BR><BR></td></tr>";
                $html.="<tr><td width=460><B>".$datos[0][razon_social]."</B></td><td width=300 align='center'>FACTURA DE VENTA</td></tr>";

                if(!empty($datos[prefijo]) AND !empty($datos[factura_fiscal]))
                {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT count(numerodecuenta) FROM fac_facturas_cuentas
                                            WHERE factura_fiscal=".$datos[factura_fiscal]."
                                            and prefijo='".$datos[prefijo]."'
                                            LIMIT 2 OFFSET 0";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        $result->Close();
                        if($result->fields[0] > 1)
                        {
                                $datos[prefijo]='';
                                $datos[factura_fiscal]='';
                        }
                }
                $factura_fiscal=str_pad($datos[0][factura_fiscal], $datos[0][numero_digitos], "0", STR_PAD_LEFT);
                $html.="<tr><td width=\"460\">".$datos[0][tipoid].": ".$datos[0][id]."</td><td width=\"300\">No. ".$datos[0][prefijo]."".$factura_fiscal."</td></tr>";
                $html.="<tr><td width=\"230\">DIRECCION: ".$datos[0][direccion]."</td><td width=\"230\"> TELEFONOS: ".$datos[0][telefonos]."</td><td width=\"300\">".$datos[0][municipio]."-".$datos[0][departamento]."</td></tr>";
		//$this->SetFont('Arial','',6);
                $html.="<tr><td width=\"760\" colspan=\"3\">".$datos[0][texto1]."</td></tr>";
		//$this->SetFont('Arial','',8);
		$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
                $html.="<tr><td width=\"500\"><B>CLIENTE: ".$datos[0][nombre_tercero]."</B></td></tr>";
                $html.="<tr><td width=460>".$datos[0][tipo_id_tercero].": ".$datos[0][tercero_id]."</td></tr>";
                $html.="<tr><td width=450><B>PLAN: ".$datos[0][plan_descripcion]."</B></td></tr>";
                $html.="<tr><td width=450> DPTO: ".$datos[0][descripcion]."</td></tr>";
                $fecha=explode("-",$datos[0][fecha_registro]);
                $nueva = mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]) + 30 * 24 * 60 * 60;
                $nuevafecha=date("d/m/Y",$nueva);
                $fechaElab=date("d/m/Y",mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]));
                //$html.="<tr><td width=230>DIRECCION: ".$datos[residencia_direccion]."</td><td width=230>TELEFONOS: ".$datos[residencia_telefono]."</td><td width=150>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                $html.="<tr><td width=450>DIRECCION: ".$datos[0][dirter]."</td></tr>";
                $html.="<tr><td width=400>TELEFONOS: ".$datos[0][telter]."</td><td width=200>FECHA ELAB.: ".$fechaElab."</td><td width=150>FECHA VENC.: ".$nuevafecha."</td></tr>";
                $html.="<tr><td width=400>&nbsp;</td><td width=200>FECHA INGR.: ".$fechaElab."</td><td width=150>FECHA EGRE.: ".$nuevafecha."</td></tr>";
                $html.="</table>";
                $this->Ln(1);
                $this->WriteHTML($html);
        }
        //To be implemented in your own inherited class
    }

    function Footer()
    {
        //darling envios
        if($_SESSION['REPORTES']['VARIABLE']=='envios')
        {
                $this->SetY(-15);
                $html .= "<table WIDTH=\"40\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">";
                $html.="<tr><td WIDTH=760 align=\"CENTER\">NOTA: Al cancelar hacer referencia al No. de la factura por paciente , o al No. del envio.</td></tr>";
                $html .= "  </table><BR>";
                $this->WriteHTML($html);
        }
    //To be implemented in your own inherited class


//darling envios
        if($_SESSION['REPORTES']['VARIABLE']=='cierre_caja')
        {
                $this->SetY(-25);
                 //Select Arial italic 8
            $this->SetFont('Arial','B',8);
                    //Print current and total page numbers
                $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');

        }

        if ($_SESSION['REPORTES']['VARIABLE']=='incapacidad')
        {
            $this->SetY(-25);
        $this->SetFont('Arial','',6);           
            //$html.="<TABLE BORDER='0' WIDTH='1520'>";
            //$html.="<tr><td WIDTH=760 align=\"CENTER\" HEIGHT=25>NOTA: 1:Documento no v�ido para descuentos en planillas de autoliquidacion de aportes,favor acerquese a S.O.S para su liquidaci�.</td></tr>";
            //$html.="<tr><td WIDTH=760 align=\"CENTER\" HEIGHT=25>      2:La solicitud de licencia de maternidad requiere el certificado de nacido vivo, favor solicitarlo a su m�ico y adjuntarlo para su respectivo tr�ite.</td></tr>";
            //$html.="</TABLE>";
            $this->WriteHTML($html);
        }



        /*if($_SESSION['REPORTES']['VARIABLE']=='incapacidad')
        {
                $this->SetY(-25);
                 //Select Arial italic 8
            $this->SetFont('Arial','B',8);
                    //Print current and total page numbers
                $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');

        }*/


        if($_SESSION['REPORTES']['VARIABLE']=='solicitudes')
        {
                $this->SetY(-25);
                 //Select Arial italic 8
            $this->SetFont('Arial','B',8);
                    //Print current and total page numbers
                $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');

        }

        if($_SESSION['REPORTES']['VARIABLE']=='orden_servicio')
        {
                $this->SetY(-25);
                 //Select Arial italic 8
            $this->SetFont('Arial','B',8);
                    //Print current and total page numbers
                $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');

        }

        if($_SESSION['REPORTES']['VARIABLE']=='formula_hosp')
        {

                $this->SetY(-25);
                 //Select Arial italic 8
            $this->SetFont('Arial','B',8);
                    //Print current and total page numbers
                $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');

        }

        if($_SESSION['REPORTES']['VARIABLE']=='formulacio_amb')
        {

                $this->SetY(-24);
                 //Select Arial italic 8
            $this->SetFont('Arial','',7);
                    //Print current and total page numbers
                $this->Cell(0,10,'Si los sintomas persisten favor consultar en las siguientes 72 horas',0,0,"C");

                $this->SetY(-20);
                 //Select Arial italic 8
            $this->SetFont('Arial','',7);
                    //Print current and total page numbers

                $this->Cell(0,10,'Caducidad Tres (3) d�s calendario',0,0,"C");


                $this->SetY(-16);
                 //Select Arial italic 8
            $this->SetFont('Arial','B',8);
                    //Print current and total page numbers
                $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');

        }

//      if($_SESSION['REPORTES']['VARIABLE']=='cuenta_cobro')
//      {
//              $this->SetY(-25);
//               //Select Arial italic 8
//              $this->SetFont('Arial','B',8);
//                  //Print current and total page numbers
//              $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
// 
//      }

     if($_SESSION['REPORTES']['VARIABLE']=='facturacion_recepcion')
     {
             $this->SetY(-25);
              //Select Arial italic 8
             $this->SetFont('Arial','B',8);
                 //Print current and total page numbers
             $this->Cell(0,10,'P�ina '.$this->PageNo().' / {nb}',0,0,'C');

     }

    //To be implemented in your own inherited class
    }

function PageNo()
{
    //Get current page number
    return $this->page;
}

function SetDrawColor($r,$g=-1,$b=-1)
{
    //Set color for all stroking operations
    if(($r==0 and $g==0 and $b==0) or $g==-1)
        $this->DrawColor=sprintf('%.3f G',$r/255);
    else
        $this->DrawColor=sprintf('%.3f %.3f %.3f RG',$r/255,$g/255,$b/255);
    if($this->page>0)
        $this->_out($this->DrawColor);
}

function SetFillColor($r,$g=-1,$b=-1)
{
    //Set color for all filling operations
    if(($r==0 and $g==0 and $b==0) or $g==-1)
        $this->FillColor=sprintf('%.3f g',$r/255);
    else
        $this->FillColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
    $this->ColorFlag=($this->FillColor!=$this->TextColor);
    if($this->page>0)
        $this->_out($this->FillColor);
}

function SetTextColor($r,$g=-1,$b=-1)
{
    //Set color for text
    if(($r==0 and $g==0 and $b==0) or $g==-1)
        $this->TextColor=sprintf('%.3f g',$r/255);
    else
        $this->TextColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
    $this->ColorFlag=($this->FillColor!=$this->TextColor);
}

function GetStringWidth($s)
{
    //Get width of a string in the current font
    $s=(string)$s;
    $cw=&$this->CurrentFont['cw'];
    $w=0;
    $l=strlen($s);
    for($i=0;$i<$l;$i++)
        $w+=$cw[$s{$i}];
    return $w*$this->FontSize/1000;
}

function SetLineWidth($width)
{
    //Set line width
    $this->LineWidth=$width;
    if($this->page>0)
        $this->_out(sprintf('%.2f w',$width*$this->k));
}

function Line($x1,$y1,$x2,$y2)
{
    //Draw a line
    $this->_out(sprintf('%.2f %.2f m %.2f %.2f l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
}

function Rect($x,$y,$w,$h,$style='')
{
    //Draw a rectangle
    if($style=='F')
        $op='f';
    elseif($style=='FD' or $style=='DF')
        $op='B';
    else
        $op='S';
    $this->_out(sprintf('%.2f %.2f %.2f %.2f re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
}

function AddFont($family,$style='',$file='')
{
    //Add a TrueType or Type1 font
    $family=strtolower($family);
    if($family=='arial')
        $family='helvetica';
    $style=strtoupper($style);
    if($style=='IB')
        $style='BI';
    if(isset($this->fonts[$family.$style]))
        $this->Error('Font already added: '.$family.' '.$style);
    if($file=='')
        $file=str_replace(' ','',$family).strtolower($style).'.php';
    if(defined('FPDF_FONTPATH'))
        $file=FPDF_FONTPATH.$file;
    include($file);
    if(!isset($name))
        $this->Error('Could not include font definition file');
    $i=count($this->fonts)+1;
    $this->fonts[$family.$style]=array('i'=>$i,'type'=>$type,'name'=>$name,'desc'=>$desc,'up'=>$up,'ut'=>$ut,'cw'=>$cw,'enc'=>$enc,'file'=>$file);
    if($diff)
    {
        //Search existing encodings
        $d=0;
        $nb=count($this->diffs);
        for($i=1;$i<=$nb;$i++)
            if($this->diffs[$i]==$diff)
            {
                $d=$i;
                break;
            }
        if($d==0)
        {
            $d=$nb+1;
            $this->diffs[$d]=$diff;
        }
        $this->fonts[$family.$style]['diff']=$d;
    }
    if($file)
    {
        if($type=='TrueType')
            $this->FontFiles[$file]=array('length1'=>$originalsize);
        else
            $this->FontFiles[$file]=array('length1'=>$size1,'length2'=>$size2);
    }
}

function SetFont($family,$style='',$size=0)
{
    //Select a font; size given in points
    global $fpdf_charwidths;

    $family=strtolower($family);
    if($family=='')
        $family=$this->FontFamily;
    if($family=='arial')
        $family='helvetica';
    elseif($family=='symbol' or $family=='zapfdingbats')
        $style='';
    $style=strtoupper($style);
    if(is_int(strpos($style,'U')))
    {
        $this->underline=true;
        $style=str_replace('U','',$style);
    }
    else
        $this->underline=false;
    if($style=='IB')
        $style='BI';
    if($size==0)
        $size=$this->FontSizePt;
    //Test if font is already selected
    if($this->FontFamily==$family and $this->FontStyle==$style and $this->FontSizePt==$size)
        return;
    //Test if used for the first time
    $fontkey=$family.$style;
    if(!isset($this->fonts[$fontkey]))
    {
        //Check if one of the standard fonts
        if(isset($this->CoreFonts[$fontkey]))
        {
            if(!isset($fpdf_charwidths[$fontkey]))
            {
                //Load metric file
                $file=$family;
                if($family=='times' or $family=='helvetica')
                    $file.=strtolower($style);
                $file.='.php';
                if(defined('FPDF_FONTPATH'))
                    $file=FPDF_FONTPATH.$file;
                include($file);
                if(!isset($fpdf_charwidths[$fontkey]))
                    $this->Error('Could not include font metric file');
            }
            $i=count($this->fonts)+1;
            $this->fonts[$fontkey]=array('i'=>$i,'type'=>'core','name'=>$this->CoreFonts[$fontkey],'up'=>-100,'ut'=>50,'cw'=>$fpdf_charwidths[$fontkey]);
        }
        else
            $this->Error('Undefined font: '.$family.' '.$style);
    }
    //Select it
    $this->FontFamily=$family;
    $this->FontStyle=$style;
    $this->FontSizePt=$size;
    $this->FontSize=$size/$this->k;
    $this->CurrentFont=&$this->fonts[$fontkey];
    if($this->page>0)
        $this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
}

function SetFontSize($size)
{
    //Set font size in points
    if($this->FontSizePt==$size)
        return;
    $this->FontSizePt=$size;
    $this->FontSize=$size/$this->k;
    if($this->page>0)
        $this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
}

function AddLink()
{
    //Create a new internal link
    $n=count($this->links)+1;
    $this->links[$n]=array(0,0);
    return $n;
}

function SetLink($link,$y=0,$page=-1)
{
    //Set destination of internal link
    if($y==-1)
        $y=$this->y;
    if($page==-1)
        $page=$this->page;
    $this->links[$link]=array($page,$y);
}

function Link($x,$y,$w,$h,$link)
{
    //Put a link on the page
    $this->PageLinks[$this->page][]=array($x*$this->k,$this->hPt-$y*$this->k,$w*$this->k,$h*$this->k,$link);
}

function Text($x,$y,$txt)
{
    //Output a string
    $s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    if($this->underline and $txt!='')
        $s.=' '.$this->_dounderline($x,$y,$txt);
    if($this->ColorFlag)
        $s='q '.$this->TextColor.' '.$s.' Q';
    $this->_out($s);
}

function AcceptPageBreak()
{
    //Accept automatic page break or not
    return $this->AutoPageBreak;
}

function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
{
    //Output a cell
    $k=$this->k;
    if($this->y+$h>$this->PageBreakTrigger and !$this->InFooter and $this->AcceptPageBreak())
    {
        //Automatic page break
        $x=$this->x;
        $ws=$this->ws;
        if($ws>0)
        {
            $this->ws=0;
            $this->_out('0 Tw');
        }
        $this->AddPage($this->CurOrientation);
        $this->x=$x;
        if($ws>0)
        {
            $this->ws=$ws;
            $this->_out(sprintf('%.3f Tw',$ws*$k));
        }
    }
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $s='';
    if($fill==1 or $border==1)
    {
        if($fill==1)
            $op=($border==1) ? 'B' : 'f';
        else
            $op='S';
        $s=sprintf('%.2f %.2f %.2f %.2f re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
    }
    if(is_string($border))
    {
        $x=$this->x;
        $y=$this->y;
        if(is_int(strpos($border,'L')))
            $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
        if(is_int(strpos($border,'T')))
            $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
        if(is_int(strpos($border,'R')))
            $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
        if(is_int(strpos($border,'B')))
            $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
    }
    if($txt!='')
    {
        if($align=='R')
            $dx=$w-$this->cMargin-$this->GetStringWidth($txt);
        elseif($align=='C')
            $dx=($w-$this->GetStringWidth($txt))/2;
        else
            $dx=$this->cMargin;
        if($this->ColorFlag)
            $s.='q '.$this->TextColor.' ';
        $txt2=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
        $s.=sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt2);
        if($this->underline)
            $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
        if($this->ColorFlag)
            $s.=' Q';
        if($link)
            $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
    }
    if($s)
        $this->_out($s);
    $this->lasth=$h;
    if($ln>0)
    {
        //Go to next line
        $this->y+=$h;
        if($ln==1)
            $this->x=$this->lMargin;
    }
    else
        $this->x+=$w;
}

function MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0)
{
    //Output text with automatic or explicit line breaks
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $b=0;
    if($border)
    {
        if($border==1)
        {
            $border='LTRB';
            $b='LRT';
            $b2='LR';
        }
        else
        {
            $b2='';
            if(is_int(strpos($border,'L')))
                $b2.='L';
            if(is_int(strpos($border,'R')))
                $b2.='R';
            $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
        }
    }
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $ns=0;
    $nl=1;
    while($i<$nb)
    {
        //Get next character
        $c=$s{$i};
        if($c=="\n")
        {
            //Explicit line break
            if($this->ws>0)
            {
                $this->ws=0;
                $this->_out('0 Tw');
            }
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border and $nl==2)
                $b=$b2;
            continue;
        }
        if($c==' ')
        {
            $sep=$i;
            $ls=$l;
            $ns++;
        }
        $l+=$cw[$c];
        if($l>$wmax)
        {
            //Automatic line break
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            }
            else
            {
                if($align=='J')
                {
                    $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                    $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
                }
                $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                $i=$sep+1;
            }
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border and $nl==2)
                $b=$b2;
        }
        else
            $i++;
    }
    //Last chunk
    if($this->ws>0)
    {
        $this->ws=0;
        $this->_out('0 Tw');
    }
    if($border and is_int(strpos($border,'B')))
        $b.='B';
    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
    $this->x=$this->lMargin;
}

function Write($h,$txt,$link='')
{
    //Output text in flowing mode
    $cw=&$this->CurrentFont['cw'];
    $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        //Get next character
        $c=$s{$i};
        if($c=="\n")
        {
            //Explicit line break
            $this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            if($nl==1)
            {
                $this->x=$this->lMargin;
                $w=$this->w-$this->rMargin-$this->x;
                $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
            }
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            //Automatic line break
            if($sep==-1)
            {
                if($this->x>$this->lMargin)
                {
                    //Move to next line
                    $this->x=$this->lMargin;
                    $this->y+=$h;
                    $w=$this->w-$this->rMargin-$this->x;
                    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
                    $i++;
                    $nl++;
                    continue;
                }
                if($i==$j)
                    $i++;
                $this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
            }
            else
            {
                $this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
                $i=$sep+1;
            }
            $sep=-1;
            $j=$i;
            $l=0;
            if($nl==1)
            {
                $this->x=$this->lMargin;
                $w=$this->w-$this->rMargin-$this->x;
                $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
            }
            $nl++;
        }
        else
            $i++;
    }
    //Last chunk
    if($i!=$j)
        $this->Cell($l/1000*$this->FontSize,$h,substr($s,$j),0,0,'',0,$link);
}

function Image($file,$x,$y,$w=0,$h=0,$type='',$link='')
{
    //Put an image on the page
    if(!isset($this->images[$file]))
    {
        //First use of image, get info
        if($type=='')
        {
            $pos=strrpos($file,'.');
            if(!$pos)
                $this->Error('Image file has no extension and no type was specified: '.$file);
            $type=substr($file,$pos+1);
        }
        $type=strtolower($type);
        $mqr=get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        if($type=='jpg' or $type=='jpeg')
            $info=$this->_parsejpg($file);
        elseif($type=='png')
            $info=$this->_parsepng($file);
        else
        {
            //Allow for additional formats
            $mtd='_parse'.$type;
            if(!method_exists($this,$mtd))
                $this->Error('Unsupported image type: '.$type);
            $info=$this->$mtd($file);
        }
        set_magic_quotes_runtime($mqr);
        $info['i']=count($this->images)+1;
        $this->images[$file]=$info;
    }
    else
        $info=$this->images[$file];
    //Automatic width and height calculation if needed
    if($w==0 and $h==0)
    {
        //Put image at 72 dpi
        $w=$info['w']/$this->k;
        $h=$info['h']/$this->k;
    }
    if($w==0)
        $w=$h*$info['w']/$info['h'];
    if($h==0)
        $h=$w*$info['h']/$info['w'];
    $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
    if($link)
        $this->Link($x,$y,$w,$h,$link);
}

function Ln($h='')
{
    //Line feed; default value is last cell height
    $this->x=$this->lMargin;
    if(is_string($h))
        $this->y+=$this->lasth;
    else
        $this->y+=$h;
}

function GetX()
{
    //Get x position
    return $this->x;
}

function SetX($x)
{
    //Set x position
    if($x>=0)
        $this->x=$x;
    else
        $this->x=$this->w+$x;
}

function GetY()
{
    //Get y position
    return $this->y;
}

function SetY($y)
{
    //Set y position and reset x
    $this->x=$this->lMargin;
    if($y>=0)
        $this->y=$y;
    else
        $this->y=$this->h+$y;
}

function SetXY($x,$y)
{
    //Set x and y positions
    $this->SetY($y);
    $this->SetX($x);
}

function Output($name='',$dest='')
{
    //Output PDF to some destination
    global $HTTP_SERVER_VARS;

    //Finish document if necessary
    if($this->state<3)
        $this->Close();
    //Normalize parameters
    if(is_bool($dest))
        $dest=$dest ? 'D' : 'F';
    $dest=strtoupper($dest);
    if($dest=='')
    {
        if($name=='')
        {
            $name='doc.pdf';
            $dest='I';
        }
        else
            $dest='F';
    }
    switch($dest)
    {
        case 'I':
            //Send to standard output
            if(isset($HTTP_SERVER_VARS['SERVER_NAME']))
            {
                //We send to a browser
                Header('Content-Type: application/pdf');
                if(headers_sent())
                $this->Error('Some data has already been output to browser, can\'t send PDF file');
                Header('Content-Length: '.strlen($this->buffer));
                Header('Content-disposition: inline; filename='.$name);
            }
            echo $this->buffer;
            break;
        case 'D':
            //Download file
            if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE'))
                Header('Content-Type: application/force-download');
            else
                Header('Content-Type: application/octet-stream');
            if(headers_sent())
                $this->Error('Some data has already been output to browser, can\'t send PDF file');
            Header('Content-Length: '.strlen($this->buffer));
            Header('Content-disposition: attachment; filename='.$name);
            echo $this->buffer;
            break;
        case 'F':
            //Save to local file
            $f=fopen($name,'wb');
            if(!$f)
                $this->Error('Unable to create output file: '.$name);
            fwrite($f,$this->buffer,strlen($this->buffer));
            fclose($f);
            break;
        case 'S':
            //Return as a string
            return $this->buffer;
        default:
            $this->Error('Incorrect output destination: '.$dest);
    }
    return '';
}

/*******************************************************************************
*                                                                              *
*                              Protected methods                               *
*                                                                              *
*******************************************************************************/
function _dochecks()
{
    //Check for locale-related bug
    if(1.1==1)
        $this->Error('Don\'t alter the locale before including class file');
    //Check for decimal separator
    if(sprintf('%.1f',1.0)!='1.0')
        setlocale(LC_NUMERIC,'C');
}

function _begindoc()
{
    //Start document
    $this->state=1;
    $this->_out('%PDF-1.3');
}

function _putpages()
{
    $nb=$this->page;
    if(!empty($this->AliasNbPages))
    {
        //Replace number of pages
        for($n=1;$n<=$nb;$n++)
            $this->pages[$n]=str_replace($this->AliasNbPages,$nb,$this->pages[$n]);
    }
    if($this->DefOrientation=='P')
    {
        $wPt=$this->fwPt;
        $hPt=$this->fhPt;
    }
    else
    {
        $wPt=$this->fhPt;
        $hPt=$this->fwPt;
    }
    $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
    for($n=1;$n<=$nb;$n++)
    {
        //Page
        $this->_newobj();
        $this->_out('<</Type /Page');
        $this->_out('/Parent 1 0 R');
        if(isset($this->OrientationChanges[$n]))
            $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$hPt,$wPt));
        $this->_out('/Resources 2 0 R');
        if(isset($this->PageLinks[$n]))
        {
            //Links
            $annots='/Annots [';
            foreach($this->PageLinks[$n] as $pl)
            {
                $rect=sprintf('%.2f %.2f %.2f %.2f',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
                $annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
                if(is_string($pl[4]))
                    $annots.='/A <</S /URI /URI '.$this->_textstring($pl[4]).'>>>>';
                else
                {
                    $l=$this->links[$pl[4]];
                    $h=isset($this->OrientationChanges[$l[0]]) ? $wPt : $hPt;
                    $annots.=sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]>>',1+2*$l[0],$h-$l[1]*$this->k);
                }
            }
            $this->_out($annots.']');
        }
        $this->_out('/Contents '.($this->n+1).' 0 R>>');
        $this->_out('endobj');
        //Page content
        $p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
        $this->_newobj();
        $this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
        $this->_putstream($p);
        $this->_out('endobj');
    }
    //Pages root
    $this->offsets[1]=strlen($this->buffer);
    $this->_out('1 0 obj');
    $this->_out('<</Type /Pages');
    $kids='/Kids [';
    for($i=0;$i<$nb;$i++)
        $kids.=(3+2*$i).' 0 R ';
    $this->_out($kids.']');
    $this->_out('/Count '.$nb);
    $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
    $this->_out('>>');
    $this->_out('endobj');
}

function _putfonts()
{
    $nf=$this->n;
    foreach($this->diffs as $diff)
    {
        //Encodings
        $this->_newobj();
        $this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
        $this->_out('endobj');
    }
    $mqr=get_magic_quotes_runtime();
    set_magic_quotes_runtime(0);
    foreach($this->FontFiles as $file=>$info)
    {
        //Font file embedding
        $this->_newobj();
        $this->FontFiles[$file]['n']=$this->n;
        if(defined('FPDF_FONTPATH'))
            $file=FPDF_FONTPATH.$file;
        $size=filesize($file);
        if(!$size)
            $this->Error('Font file not found');
        $this->_out('<</Length '.$size);
        if(substr($file,-2)=='.z')
            $this->_out('/Filter /FlateDecode');
        $this->_out('/Length1 '.$info['length1']);
        if(isset($info['length2']))
            $this->_out('/Length2 '.$info['length2'].' /Length3 0');
        $this->_out('>>');
        $f=fopen($file,'rb');
        $this->_putstream(fread($f,$size));
        fclose($f);
        $this->_out('endobj');
    }
    set_magic_quotes_runtime($mqr);
    foreach($this->fonts as $k=>$font)
    {
        //Font objects
        $this->fonts[$k]['n']=$this->n+1;
        $type=$font['type'];
        $name=$font['name'];
        if($type=='core')
        {
            //Standard font
            $this->_newobj();
            $this->_out('<</Type /Font');
            $this->_out('/BaseFont /'.$name);
            $this->_out('/Subtype /Type1');
            if($name!='Symbol' and $name!='ZapfDingbats')
                $this->_out('/Encoding /WinAnsiEncoding');
            $this->_out('>>');
            $this->_out('endobj');
        }
        elseif($type=='Type1' or $type=='TrueType')
        {
            //Additional Type1 or TrueType font
            $this->_newobj();
            $this->_out('<</Type /Font');
            $this->_out('/BaseFont /'.$name);
            $this->_out('/Subtype /'.$type);
            $this->_out('/FirstChar 32 /LastChar 255');
            $this->_out('/Widths '.($this->n+1).' 0 R');
            $this->_out('/FontDescriptor '.($this->n+2).' 0 R');
            if($font['enc'])
            {
                if(isset($font['diff']))
                    $this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
                else
                    $this->_out('/Encoding /WinAnsiEncoding');
            }
            $this->_out('>>');
            $this->_out('endobj');
            //Widths
            $this->_newobj();
            $cw=&$font['cw'];
            $s='[';
            for($i=32;$i<=255;$i++)
                $s.=$cw[chr($i)].' ';
            $this->_out($s.']');
            $this->_out('endobj');
            //Descriptor
            $this->_newobj();
            $s='<</Type /FontDescriptor /FontName /'.$name;
            foreach($font['desc'] as $k=>$v)
                $s.=' /'.$k.' '.$v;
            $file=$font['file'];
            if($file)
                $s.=' /FontFile'.($type=='Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
            $this->_out($s.'>>');
            $this->_out('endobj');
        }
        else
        {
            //Allow for additional types
            $mtd='_put'.strtolower($type);
            if(!method_exists($this,$mtd))
                $this->Error('Unsupported font type: '.$type);
            $this->$mtd($font);
        }
    }
}

function _putimages()
{
    $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
    reset($this->images);
    while(list($file,$info)=each($this->images))
    {
        $this->_newobj();
        $this->images[$file]['n']=$this->n;
        $this->_out('<</Type /XObject');
        $this->_out('/Subtype /Image');
        $this->_out('/Width '.$info['w']);
        $this->_out('/Height '.$info['h']);
        if($info['cs']=='Indexed')
            $this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
        else
        {
            $this->_out('/ColorSpace /'.$info['cs']);
            if($info['cs']=='DeviceCMYK')
                $this->_out('/Decode [1 0 1 0 1 0 1 0]');
        }
        $this->_out('/BitsPerComponent '.$info['bpc']);
        $this->_out('/Filter /'.$info['f']);
        if(isset($info['parms']))
            $this->_out($info['parms']);
        if(isset($info['trns']) and is_array($info['trns']))
        {
            $trns='';
            for($i=0;$i<count($info['trns']);$i++)
                $trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
            $this->_out('/Mask ['.$trns.']');
        }
        $this->_out('/Length '.strlen($info['data']).'>>');
        $this->_putstream($info['data']);
        unset($this->images[$file]['data']);
        $this->_out('endobj');
        //Palette
        if($info['cs']=='Indexed')
        {
            $this->_newobj();
            $pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
            $this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
            $this->_putstream($pal);
            $this->_out('endobj');
        }
    }
}

function _putresources()
{
    $this->_putfonts();
    $this->_putimages();
    //Resource dictionary
    $this->offsets[2]=strlen($this->buffer);
    $this->_out('2 0 obj');
    $this->_out('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
    $this->_out('/Font <<');
    foreach($this->fonts as $font)
        $this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
    $this->_out('>>');
    if(count($this->images))
    {
        $this->_out('/XObject <<');
        foreach($this->images as $image)
            $this->_out('/I'.$image['i'].' '.$image['n'].' 0 R');
        $this->_out('>>');
    }
    $this->_out('>>');
    $this->_out('endobj');
}

function _putinfo()
{
    $this->_out('/Producer '.$this->_textstring('FPDF '.FPDF_VERSION));
    if(!empty($this->title))
        $this->_out('/Title '.$this->_textstring($this->title));
    if(!empty($this->subject))
        $this->_out('/Subject '.$this->_textstring($this->subject));
    if(!empty($this->author))
        $this->_out('/Author '.$this->_textstring($this->author));
    if(!empty($this->keywords))
        $this->_out('/Keywords '.$this->_textstring($this->keywords));
    if(!empty($this->creator))
        $this->_out('/Creator '.$this->_textstring($this->creator));
    $this->_out('/CreationDate '.$this->_textstring('D:'.date('YmdHis')));
}

function _putcatalog()
{
    $this->_out('/Type /Catalog');
    $this->_out('/Pages 1 0 R');
    if($this->ZoomMode=='fullpage')
        $this->_out('/OpenAction [3 0 R /Fit]');
    elseif($this->ZoomMode=='fullwidth')
        $this->_out('/OpenAction [3 0 R /FitH null]');
    elseif($this->ZoomMode=='real')
        $this->_out('/OpenAction [3 0 R /XYZ null null 1]');
    elseif(!is_string($this->ZoomMode))
        $this->_out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode/100).']');
    if($this->LayoutMode=='single')
        $this->_out('/PageLayout /SinglePage');
    elseif($this->LayoutMode=='continuous')
        $this->_out('/PageLayout /OneColumn');
    elseif($this->LayoutMode=='two')
        $this->_out('/PageLayout /TwoColumnLeft');
}

function _puttrailer()
{
    $this->_out('/Size '.($this->n+1));
    $this->_out('/Root '.$this->n.' 0 R');
    $this->_out('/Info '.($this->n-1).' 0 R');
}

function _enddoc()
{
    $this->_putpages();
    $this->_putresources();
    //Info
    $this->_newobj();
    $this->_out('<<');
    $this->_putinfo();
    $this->_out('>>');
    $this->_out('endobj');
    //Catalog
    $this->_newobj();
    $this->_out('<<');
    $this->_putcatalog();
    $this->_out('>>');
    $this->_out('endobj');
    //Cross-ref
    $o=strlen($this->buffer);
    $this->_out('xref');
    $this->_out('0 '.($this->n+1));
    $this->_out('0000000000 65535 f ');
    for($i=1;$i<=$this->n;$i++)
        $this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
    //Trailer
    $this->_out('trailer');
    $this->_out('<<');
    $this->_puttrailer();
    $this->_out('>>');
    $this->_out('startxref');
    $this->_out($o);
    $this->_out('%%EOF');
    $this->state=3;
}

function _beginpage($orientation)
{
    $this->page++;
    $this->pages[$this->page]='';
    $this->state=2;
    $this->x=$this->lMargin;
    $this->y=$this->tMargin;
    $this->FontFamily='';
    //Page orientation
    if(!$orientation)
        $orientation=$this->DefOrientation;
    else
    {
        $orientation=strtoupper($orientation{0});
        if($orientation!=$this->DefOrientation)
            $this->OrientationChanges[$this->page]=true;
    }
    if($orientation!=$this->CurOrientation)
    {
        //Change orientation
        if($orientation=='P')
        {
            $this->wPt=$this->fwPt;
            $this->hPt=$this->fhPt;
            $this->w=$this->fw;
            $this->h=$this->fh;
        }
        else
        {
            $this->wPt=$this->fhPt;
            $this->hPt=$this->fwPt;
            $this->w=$this->fh;
            $this->h=$this->fw;
        }
        $this->PageBreakTrigger=$this->h-$this->bMargin;
        $this->CurOrientation=$orientation;
    }
}

function _endpage()
{
    //End of page contents
    $this->state=1;
}

function _newobj()
{
    //Begin a new object
    $this->n++;
    $this->offsets[$this->n]=strlen($this->buffer);
    $this->_out($this->n.' 0 obj');
}

function _dounderline($x,$y,$txt)
{
    //Underline text
    $up=$this->CurrentFont['up'];
    $ut=$this->CurrentFont['ut'];
    $w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');
    return sprintf('%.2f %.2f %.2f %.2f re f',$x*$this->k,($this->h-($y-$up/1000*$this->FontSize))*$this->k,$w*$this->k,-$ut/1000*$this->FontSizePt);
}

/*funcion que genera los rectangulos redondeados funcion a�dida*/
function RoundedRect($x, $y, $w, $h,$r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

        /*funcion que genera los rectangulos redondeados funcion a�dida*/
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }


            /*funcion a�dida q deja una marca de agua en el formato*/
            function RotatedText($x,$y,$txt,$angle)
            {
                    //Text rotated around its origin
                    $this->Rotate($angle,$x,$y);
                    $this->Text($x,$y,$txt);
                    $this->Rotate(0);
            }

            /*funcion a�dida q deja una marca de agua en el formato*/
            function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}
        

function _parsejpg($file)
{
    //Extract info from a JPEG file
    $a=GetImageSize($file);
    if(!$a)
        $this->Error('Missing or incorrect image file: '.$file);
    if($a[2]!=2)
        $this->Error('Not a JPEG file: '.$file);
    if(!isset($a['channels']) or $a['channels']==3)
        $colspace='DeviceRGB';
    elseif($a['channels']==4)
        $colspace='DeviceCMYK';
    else
        $colspace='DeviceGray';
    $bpc=isset($a['bits']) ? $a['bits'] : 8;
    //Read whole file
    $f=fopen($file,'rb');
    $data='';
    while(!feof($f))
        $data.=fread($f,4096);
    fclose($f);
    return array('w'=>$a[0],'h'=>$a[1],'cs'=>$colspace,'bpc'=>$bpc,'f'=>'DCTDecode','data'=>$data);
}

function _parsepng($file)
{
    //Extract info from a PNG file
    $f=fopen($file,'rb');
    if(!$f)
        $this->Error('Can\'t open image file: '.$file);
    //Check signature
    if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
        $this->Error('Not a PNG file: '.$file);
    //Read header chunk
    fread($f,4);
    if(fread($f,4)!='IHDR')
        $this->Error('Incorrect PNG file: '.$file);
    $w=$this->_freadint($f);
    $h=$this->_freadint($f);
    $bpc=ord(fread($f,1));
    if($bpc>8)
        $this->Error('16-bit depth not supported: '.$file);
    $ct=ord(fread($f,1));
    if($ct==0)
        $colspace='DeviceGray';
    elseif($ct==2)
        $colspace='DeviceRGB';
    elseif($ct==3)
        $colspace='Indexed';
    else
        $this->Error('Alpha channel not supported: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Unknown compression method: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Unknown filter method: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Interlacing not supported: '.$file);
    fread($f,4);
    $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
    //Scan chunks looking for palette, transparency and image data
    $pal='';
    $trns='';
    $data='';
    do
    {
        $n=$this->_freadint($f);
        $type=fread($f,4);
        if($type=='PLTE')
        {
            //Read palette
            $pal=fread($f,$n);
            fread($f,4);
        }
        elseif($type=='tRNS')
        {
            //Read transparency info
            $t=fread($f,$n);
            if($ct==0)
                $trns=array(ord(substr($t,1,1)));
            elseif($ct==2)
                $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
            else
            {
                $pos=strpos($t,chr(0));
                if(is_int($pos))
                    $trns=array($pos);
            }
            fread($f,4);
        }
        elseif($type=='IDAT')
        {
            //Read image data block
            $data.=fread($f,$n);
            fread($f,4);
        }
        elseif($type=='IEND')
            break;
        else
            fread($f,$n+4);
    }
    while($n);
    if($colspace=='Indexed' and empty($pal))
        $this->Error('Missing palette in '.$file);
    fclose($f);
    return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
}

function _freadint($f)
{
    //Read a 4-byte integer from file
    $i=ord(fread($f,1))<<24;
    $i+=ord(fread($f,1))<<16;
    $i+=ord(fread($f,1))<<8;
    $i+=ord(fread($f,1));
    return $i;
}

function _textstring($s)
{
    //Format a text string
    return '('.$this->_escape($s).')';
}

function _escape($s)
{
    //Add \ before \, ( and )
    return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));
}

function _putstream($s)
{
    $this->_out('stream');
    $this->_out($s);
    $this->_out('endstream');
}

function _out($s)
{
    //Add a line to the document
    if($this->state==2)
        $this->pages[$this->page].=$s."\n";
    else
        $this->buffer.=$s."\n";
}
//End of class
}

//Handle special IE contype request
if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and $HTTP_SERVER_VARS['HTTP_USER_AGENT']=='contype')
{
    Header('Content-Type: application/pdf');
    exit;
}

}
?>
