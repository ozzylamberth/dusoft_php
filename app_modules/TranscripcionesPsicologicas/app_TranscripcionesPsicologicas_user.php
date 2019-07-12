<?php

/**
 * $Id: app_BioEstadistica_user.php,v 1.18 2007/10/03 23:11:26 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_TranscripcionesPsicologicas_user extends classModulo
{

    var $limit;
    var $conteo;

     function app_TranscripcionesPsicologicas_user()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     /**
     *
     */
     function main()
     {
          unset($_SESSION['TRANS']);
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     
          $query = "SELECT b.razon_social as descripcion1, b.empresa_id
                    FROM userpermisos_transcripciones_psicologia as a, empresas as b
                    WHERE a.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id";
          
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al ejecutar el query de permisos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resulta->FetchRow()) {
               $Transcripcion[$data['descripcion1']]= $data;
          }
     
          $url[0]='app';
          $url[1]='TranscripcionesPsicologicas';
          $url[2]='user';
          $url[3]='Menu';
          $url[4]='Trans';
     
          $arreglo[0]='EMPRESA';
     
          $this->salida.= gui_theme_menu_acceso('TRANSCRIPCION PRUEBAS APLICADAS',$arreglo,$Transcripcion,$url,ModuloGetURL('system','Menu'));
          return true;
     }

     function Menu()
     {
          if(empty($_SESSION['TRANS']['EMPRESA']))
          {
               $_SESSION['TRANS']['EMPRESA']=$_REQUEST['Trans']['empresa_id'];
               $_SESSION['TRANS']['NOM_EMP']=$_REQUEST['Trans']['descripcion1'];
          }
          if(!$this->FormaMenus()){
               return false;
          }
          return true;
     }

     function LlamarFormaBuscarPaciente()
     {
          if(!$this->FormaBuscarPaciente()){
               return false;
          }
          return true;
     }

     function TiposIdPacientes()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while (!$result->EOF) {
               $vars[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          $result->Close();
          return $vars;
     }

     function BuscarPaciente()
     {
          $filtroTipoDocumento = '';
          $filtroDocumento='';
          $filtroNombres='';

          if($_REQUEST[TipoDocumento]!='')
          {   $filtroTipoDocumento=" AND c.tipo_id_paciente = '".$_REQUEST[TipoDocumento]."'";   }

          if (!empty($_REQUEST[Documento]))
          {   $filtroDocumento =" AND c.paciente_id ='".$_REQUEST[Documento]."'";   }

          if ($_REQUEST[Nombres] != '')
          {
               $a=explode(' ',$_REQUEST[Nombres]);
               foreach($a as $k=>$v)
               {
                    if(!empty($v))
                    {
                         $filtroNombres.=" and (upper(c.primer_nombre||' '||c.segundo_nombre||' '||
                                             c.primer_apellido||' '||c.segundo_apellido) like '%".strtoupper($_REQUEST[Nombres])."%')";
                    }
               }
          }
          if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

          list($dbconn) = GetDBconn();
          if(empty($_REQUEST['paso']))
          {
               $query = "SELECT	c.tipo_id_paciente, c.paciente_id,
                                   c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                                   d.trabajo_id, d.ingreso, d.evolucion_id, d.fecha_registro,
                    			e.tipo_motivo_d, f.descripcion as nombre_prueba
                                   FROM pacientes as c,
                                        hc_psicologia_trabajos_realizados as d,
                                        hc_psicologia_trabajos_realizados_detalle as e,
                                        hc_psicologia_tipo_trabajos_realizados_detalle as f
                                   WHERE c.paciente_id is not null
                                   $filtroTipoDocumento $filtroDocumento $filtroNombres
                                   AND c.tipo_id_paciente = d.tipo_id_paciente
                                   AND c.paciente_id = d.paciente_id
                                   AND d.ingreso = e.ingreso
                                   AND d.evolucion_id = e.evolucion_id
                                   AND e.tipo_motivo = '4'
                                   AND e.tipo_motivo_d = f.trabajo_detalle_id
                                   AND e.estado = '0'";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al buscar";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               if(!$result->EOF)
               {
                    $_SESSION['SPY']=$result->RecordCount();
               }
               $result->Close();
          }

          $query = "SELECT	c.tipo_id_paciente, c.paciente_id,
                    c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                    d.trabajo_id, d.ingreso, d.evolucion_id, d.fecha_registro,
                    e.tipo_motivo_d, f.descripcion as nombre_prueba
                    FROM pacientes as c,
                         hc_psicologia_trabajos_realizados as d,
                         hc_psicologia_trabajos_realizados_detalle as e,
                         hc_psicologia_tipo_trabajos_realizados_detalle as f
                    WHERE c.paciente_id is not null
                    $filtroTipoDocumento $filtroDocumento $filtroNombres
                    AND c.tipo_id_paciente = d.tipo_id_paciente
                    AND c.paciente_id = d.paciente_id
                    AND d.ingreso = e.ingreso
                    AND d.evolucion_id = e.evolucion_id
                    AND e.tipo_motivo = '4'
                    AND e.tipo_motivo_d = f.trabajo_detalle_id
                    AND e.estado = '0'
                    order by nombre
                    LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al buscar";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          if(empty($var))
          {  $this->frmError["MensajeError"]="NO SE OBTUVO RESULTADOS.";  }

          $this->FormaBuscarPaciente($var);
          return true;
     }

     function InsertTranscripcion()
     {
     	$tipopaciente = $_REQUEST['tipopaciente'];
          $paciente = $_REQUEST['paciente'];
          $ingreso = $_REQUEST['ingreso'];
          $evolucion = $_REQUEST['evolucion'];
          $prueba = $_REQUEST['prueba'];
          $tipo_motivo = $_REQUEST['tipo_motivo'];
          $nombre = $_REQUEST['nombre'];
          $trabajo = $_REQUEST['trabajo'];
          
          $this->FormaInsertarTranscripcionPruebas($tipopaciente, $paciente, $ingreso, $evolucion, $prueba, $tipo_motivo, $nombre, $trabajo);
          return true;
     }
     
     function InsertarTranscripcion()
     {
     	$tipopaciente = $_REQUEST['tipopaciente'];
          $paciente = $_REQUEST['paciente'];
          $ingreso = $_REQUEST['ingreso'];
          $evolucion = $_REQUEST['evolucion'];
          $tipo_motivo = $_REQUEST['tipo_motivo'];
          $prueba = $_REQUEST['prueba'];
          $nombre = $_REQUEST['nombre'];
		$trabajo = $_REQUEST['trabajo'];
          
          list($dbconn) = GetDBconn();
          $query = "INSERT INTO hc_psicologia_pruebas_aplicadas_transcripcion
          									( trabajo_id,
                                                         paciente_id,
                                                         tipo_id_paciente,
                                                         ingreso,
                                                         evolucion_id,
                                                         descripcion,
                                                         tipo_motivo,
                                                         tipo_motivo_d,
                                                         usuario_id,
                                                         fecha_registro)
          							VALUES	( ".$trabajo.",
                                             		  '".$paciente."',
                                                         '".$tipopaciente."',
                                                         ".$ingreso.",
                                                         ".$evolucion.",
                                                         '".$_REQUEST['descripcion']."',
                                                         '4',
                                                         ".$tipo_motivo.",
                                                         ".UserGetUID().",
                                                         'now()')";
          $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $query_u = "UPDATE hc_psicologia_trabajos_realizados_detalle
          		  SET estado= '1'
                      WHERE ingreso = ".$ingreso."
                      AND evolucion_id = ".$evolucion."
                      AND tipo_motivo = '4'
                      AND tipo_motivo_d = ".$tipo_motivo."";
          
          $dbconn->Execute($query_u);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
 
		$this->frmError["MensajeError"]="TRANSCRIPCION DILIGENCIADA SATISFACTORIAMENTE.";
          $this->FormaInsertarTranscripcionPruebas($tipopaciente, $paciente, $ingreso, $evolucion, $prueba, $tipo_motivo, $nombre, $trabajo, $_REQUEST['descripcion']);                   
          return true;     
     }
		
	function FormateoFechaLocal($fecha)
	{
          if(!empty($fecha))
          {
               $f=explode(".",$fecha);
               $fecha_arreglo=explode(" ",$f[0]);
               $fecha_real=explode("-",$fecha_arreglo[0]);
               return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));
          }
          else
          {
               return "-----";
          }
          return true;
	}
	

//------------------------------------------------------------------------------
}//fin clase user
?>