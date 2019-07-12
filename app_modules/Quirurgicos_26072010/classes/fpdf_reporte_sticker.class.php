<?php

/**
 * $Id: fpdf_reporte_sticker.class.php,v 1.1 2007/04/18 22:05:07 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

    class fpdf_reporte_sticker extends FPDF
    {
            var $correcion_x;
            var $correcion_y;
    
            function fpdf_reporte_sticker($orientation,$unit,$format)
            {
                    $this->correcion_x = 1;
                    $this->correcion_y = 1;
                    $this->FPDF($orientation,$unit,$format);
                    return true;
            }
    
            function set_correcion_x($valor)
            {
                    if(is_numeric($valor))
                    {
                            $this->correcion_x = $valor;
                    }
            }
    
            function set_correcion_y($valor)
            {
                    if(is_numeric($valor))
                    {
                            $this->correcion_y = $valor;
                    }
            }
    
            function Text_corregida($x,$y,$txt)
            {
                    $this->Text($x * $this->correcion_x, $y * $this->correcion_y, $txt);
            }

            //**********************************************
            // REPORTE STICKER
            function TraerDatos($programacionId)
            {
                list($dbconn) = GetDBconn();
								$query = "SELECT 
								a.tipo_id_paciente,a.paciente_id,b.primer_nombre,b.segundo_nombre,b.primer_apellido,b.segundo_apellido 
								FROM qx_programaciones a
								LEFT JOIN terceros ter ON (a.cirujano_id=ter.tercero_id AND a.tipo_id_cirujano=ter.tipo_id_tercero)
								LEFT JOIN qx_anestesiologo_programacion e ON(a.programacion_id=e.programacion_id)
								LEFT JOIN terceros f ON(e.tipo_id_tercero=f.tipo_id_tercero AND e.tercero_id=f.tercero_id)
								LEFT JOIN terceros g ON(e.tipo_id_instrumentista=g.tipo_id_tercero AND e.instrumentista_id=g.tercero_id)
								LEFT JOIN terceros h ON(e.tipo_id_circulante=h.tipo_id_tercero AND e.circulante_id=h.tercero_id)
								LEFT JOIN terceros i ON(e.tipo_id_ayudante=i.tipo_id_tercero AND e.ayudante_id=i.tercero_id)
								LEFT JOIN planes pl ON(a.plan_id=pl.plan_id)
								LEFT JOIN terceros terpl ON(pl.tipo_tercero_id=terpl.tipo_id_tercero AND pl.tercero_id=terpl.tercero_id)
								LEFT JOIN diagnosticos diag ON(a.diagnostico_id=diag.diagnostico_id)
								LEFT JOIN qx_quirofanos_programacion c ON(a.programacion_id=c.programacion_id AND c.qx_tipo_reserva_quirofano_id='3')
								LEFT JOIN qx_quirofanos d ON(c.quirofano_id=d.quirofano),
								pacientes b
								WHERE a.programacion_id='".$programacionId."' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                while(!$resulta->EOF)
                {
                    $var=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }

								$vect[0][0]=11;//COLUMNA
								$vect[0][1]=12;//FILA
								$vect[0][2]=$var[tipo_id_paciente].' '.$var[paciente_id];//61,55,
	
								$vect[1][0]=11;
								$vect[1][1]=17;
								$vect[1][2]=$var[primer_apellido].' '.$var[segundo_apellido];//150,55,
	
								$vect[2][0]=11;
								$vect[2][1]=22;
								$vect[2][2]=$var[primer_nombre].' '.$var[segundo_nombre];//36,59

                return $vect;
            }
//**********************************************

    }//end of class

?>
