<?php
/**
* Submodulo de Registro de Atencion de Soat
*
* @author Tizziano Perea Ocoro
* @version 1.0
* @package SIIS
* $Id: hc_RegistroAtencion_Soat.php
*/

class Certificado
{

     function Certificado()
     {
          return true;
     }

	
     function GetFechaAccidenteSoat()
     {
          $sql = "SELECT A.fecha_accidente
                  FROM soat_accidente AS A,
               	   ingresos_soat AS B,
               	   soat_eventos AS C
                  WHERE B.ingreso = ".SessionGetVar("Ingreso")."
                  AND C.evento = B.evento
                  AND A.accidente_id = C.accidente_id;";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               $data = $resultado->FetchRow();
          }
          return $data;
	}

     
	function GetFechaAtencionSoat()
     {
          $sql = "SELECT fecha_registro AS fecha_ingreso
			   FROM soat_eventos
			   WHERE evento = (SELECT MIN(evento)
                                  FROM ingresos_soat
                                  WHERE ingreso = ".SessionGetVar("Ingreso").");";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               $data = $resultado->FetchRow();
          }
          return $data;
     }


     function GetRegistroIngreso()
     {
          $sql = "SELECT * 
          	   FROM hc_sub_registroatencionsoat_ingreso
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               $data = $resultado->FetchRow();
          }
          return $data;
     }
     
     
     function GetSignosVitales()
     {
          $sql = "SELECT * 
          	   FROM hc_signos_vitales
                  WHERE ingreso = ".SessionGetVar("Ingreso")."
                  ORDER BY fecha_registro ASC
                  LIMIT 1;";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
			$data = $resultado->FetchRow();
          }
          return $data;
     }
     
     
	function GetSignosVitalesLocales()
     {
          $sql = "SELECT * 
          	   FROM hc_sub_registroatencionsoat_signosvitales
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
			$data = $resultado->FetchRow();
          }
          return $data;
     }

     
     function GetNivelesConciencia()
     {
          $sql = "SELECT * 
          	   FROM hc_sub_registroatencionsoat_nivelconciencia
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
			$data = $resultado->FetchRow();
          }
          return $data;
     }
          
     
     function LugarExpedicionDocumento()
     {
          $sql = "SELECT lugar_expedicion_documento 
          	   FROM pacientes
                  WHERE paciente_id = '".SessionGetVar("paciente")."'
                  AND tipo_id_paciente = '".SessionGetVar("tipoidpaciente")."';";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               $data = $resultado->fields['lugar_expedicion_documento'];
          }
          return $data;
     }

 
     function GetRegistroExamenesFisicos()
     {
          $sql = "SELECT * 
          	   FROM hc_sub_registroatencionsoat_examenfisico
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               $data = $resultado->FetchRow();
          }
          return $data;
     }

     
     function GetRegistroDiagnosticosI()
     {
          $sql = "SELECT A.diagnostico_id, B.diagnostico_nombre
          	   FROM hc_sub_registroatencionsoat_diagnosticosingreso AS A,
          		   diagnosticos AS B
                  WHERE A.ingreso = ".SessionGetVar("Ingreso")."
                  AND A.diagnostico_id = B.diagnostico_id;";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               while($data = $resultado->FetchRow())
               {
               	$VectorDX[] = $data;
               }
          }
          return $VectorDX;
     }
     
     
     function GetRegistroDiagnosticosE()
     {
          $sql = "SELECT A.diagnostico_id, B.diagnostico_nombre
          	   FROM hc_sub_registroatencionsoat_diagnosticosegreso AS A,
          		   diagnosticos AS B
                  WHERE A.ingreso = ".SessionGetVar("Ingreso")."
                  AND A.diagnostico_id = B.diagnostico_id;";
          $resultado = $this->ConexionDB($sql);
          if($resultado != false)
          {
               while($data = $resultado->FetchRow())
               {
               	$VectorDX[] = $data;
               }
          }
          return $VectorDX;
     }
     
     
     function InsetarDatosIngresoSoat($DatosReg, $DatosSignosV, $DatosNivel, $DatosExamen)
     {
     	$pfj = SessionGetVar("prefijo");
          
          $sql = "SELECT COUNT(*) FROM hc_sub_registroatencionsoat_ingreso
          	   WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          $sql = "";
		
          $RegIn = $resultado->fields['count'];
          
          if($RegIn == 0)
          {
               $sql = "INSERT INTO hc_sub_registroatencionsoat_ingreso    (ingreso,
                                                                           estado,
                                                                           usuario_id,
                                                                           fecha_registro,
                                                                           estado_embriaguez,
                                                                           nombre_acudiente,
                                                                           id_acudiente,
                                                                           expedicion_identificacion,
                                                                           fecha_accidente,
                                                                           fecha_urgencias,
                                                                           tipo_id_acudiente)
                                                              VALUES (".SessionGetVar("Ingreso").",
                                                                      '1',
                                                                      ".SessionGetVar("Usuario").",
                                                                      now(),
                                                                      '".$DatosReg['estado_embriaguez']."',
                                                                      '".$DatosReg['acudiente']."',
                                                                      '".$DatosReg['id_acudiente']."',
                                                                      '".$DatosReg['expedicion_doc']."',
                                                                      '".$DatosReg['fecha_accidente']."',
                                                                      '".$DatosReg['fecha_atencion']."',
                                                                      '".$DatosReg['tipo_id_acudiente']."');";
               $resultado = $this->ConexionDB($sql);
               $sql = "";
          }
          

          $sql = "SELECT COUNT(*) FROM hc_sub_registroatencionsoat_signosvitales
          	   WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          $sql = "";
		
          $RegSig = $resultado->fields['count'];
          
          if($RegSig == 0)
          {
               $sql = "INSERT INTO hc_sub_registroatencionsoat_signosvitales (ingreso,
                                                                           fr,
                                                                           fc,
                                                                           temperatura,
                                                                           t_alta,
                                                                           t_baja)
                                                                 VALUES (".SessionGetVar("Ingreso").",
                                                                           '".$DatosSignosV['fr']."',
                                                                           '".$DatosSignosV['fc']."',
                                                                           '".$DatosSignosV['temperatura']."',
                                                                           '".$DatosSignosV['t_alta']."',
                                                                           '".$DatosSignosV['t_baja']."'
                                                                           );";
               
               $resultado = $this->ConexionDB($sql);
               $sql = "";
          }
 
          $sql = "SELECT COUNT(*) FROM hc_sub_registroatencionsoat_nivelconciencia
          	   WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          $sql = "";
		
          $RegNiv = $resultado->fields['count'];
          
          if($RegNiv == 0)
          {
               $sql = "INSERT INTO hc_sub_registroatencionsoat_nivelconciencia (ingreso,
                                                                                alerta,
                                                                                obnubilado,
                                                                                estuporoso,
                                                                                comatoso,
                                                                                glasgow)
                                                                 VALUES (".SessionGetVar("Ingreso").",
                                                                           '".$DatosNivel['alerta']."',
                                                                           '".$DatosNivel['obnubilado']."',
                                                                           '".$DatosNivel['estuporoso']."',
                                                                           '".$DatosNivel['comatoso']."',
                                                                           '".$DatosNivel['glasgow']."'
                                                                           );";
               
               $resultado = $this->ConexionDB($sql);
               $sql = "";
          }
                   
          $sql = "SELECT COUNT(*) FROM hc_sub_registroatencionsoat_examenfisico
          	   WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $resultado = $this->ConexionDB($sql);
          $sql = "";
		
          $RegEx = $resultado->fields['count'];
          
          if($RegEx == 0)
          {
               $sql = "INSERT INTO hc_sub_registroatencionsoat_examenfisico (ingreso,
                                                                           cabeza,
                                                                           cuello,
                                                                           torax,
                                                                           abdomen,
                                                                           genitourinario,
                                                                           pelvis,
                                                                           dorso,
                                                                           neurologico)
                                                                 VALUES (".SessionGetVar("Ingreso").",
                                                                           '".$DatosExamen['cabeza']."',
                                                                           '".$DatosExamen['cuello']."',
                                                                           '".$DatosExamen['torax']."',
                                                                           '".$DatosExamen['abdomen']."',
                                                                           '".$DatosExamen['genitourinario']."',
                                                                           '".$DatosExamen['pelvis']."',
                                                                           '".$DatosExamen['dorso']."',
                                                                           '".$DatosExamen['neurologico']."'
                                                                           );";
               
               $resultado = $this->ConexionDB($sql);
               $sql = "";
          }
          
          $Vector1 = array();
          $Vector1 = $_SESSION['diag_REGI'];
          
          $select = "SELECT COUNT(diagnostico_id) 
          		 FROM hc_sub_registroatencionsoat_diagnosticosingreso
                     WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $resultado = $this->ConexionDB($select);
          if($resultado != false)
          {
          	list($contador) = $resultado->FetchRow();
          }
          
          if(!empty($Vector1) AND $contador < 1)
          {
               for($i=0; $i<sizeof($Vector1); $i++)
               {
                    $query = "DELETE FROM hc_sub_registroatencionsoat_diagnosticosingreso WHERE 
                              ingreso = ".SessionGetVar("Ingreso")."
                              AND diagnostico_id = '".$Vector1[$i]['diagnostico_id']."';";
                    $query.= "INSERT INTO hc_sub_registroatencionsoat_diagnosticosingreso (ingreso, diagnostico_id)
                              VALUES (".SessionGetVar("Ingreso").", 
                                      '".$Vector1[$i]['diagnostico_id']."');";
                    
                    $resultado = $this->ConexionDB($query);
                    $query = "";
               }
               unset($_SESSION['diag_REGI']);
          }

          $Vector2 = array();
          $Vector2 = $_SESSION['diag_REGE'];
          
          $select = "SELECT COUNT(diagnostico_id) 
          		 FROM hc_sub_registroatencionsoat_diagnosticosegreso
                     WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $resultado = $this->ConexionDB($select);
          if($resultado != false)
          {
          	list($contador1) = $resultado->FetchRow();
          }
          
          if(!empty($Vector2) AND $contador1 < 1)
          {
               for($i=0; $i<sizeof($Vector2); $i++)
               {
                    $query = "DELETE FROM hc_sub_registroatencionsoat_diagnosticosegreso WHERE 
                              ingreso = ".SessionGetVar("Ingreso")."
                              AND diagnostico_id = '".$Vector2[$i]['diagnostico_id']."';";
                    $query.= "INSERT INTO hc_sub_registroatencionsoat_diagnosticosegreso (ingreso, diagnostico_id)
                              VALUES (".SessionGetVar("Ingreso").", 
                                      '".$Vector2[$i]['diagnostico_id']."');";
                    
                    $resultado = $this->ConexionDB($query);
                    $query = "";
               }
               unset($_SESSION['diag_REGE']);
          }
          
          return true;
     }

     
     //cor - clzc -ads
     function ConsultaDiagnosticoI()
     {
          $query = "select b.diagnostico_id, b.diagnostico_nombre, a.evolucion_id, a.sw_principal,
                    a.descripcion, a.tipo_diagnostico from hc_diagnosticos_ingreso as a, diagnosticos as b,
                    hc_evoluciones c
                    where a.tipo_diagnostico_id=b.diagnostico_id
                    and a.evolucion_id = c.evolucion_id
                    and c.ingreso = ".SessionGetVar("Ingreso")."
                    order by b.diagnostico_id;";
     
          $resultado = $this->ConexionDB($query);
          if($resultado != false)
          {
               $i = 0;
               while($data = $resultado->FetchRow())
               {
               	$VectorDX[$i] = $data;
                    $i++;
               }
          }
          return $VectorDX;
     }


     //cor - clzc -ads
     function ConsultaDiagnosticoE()
     {
         $query = "select b.diagnostico_id, b.diagnostico_nombre, a.evolucion_id, a.sw_principal,
                    a.tipo_diagnostico from hc_diagnosticos_egreso as a, diagnosticos as b,
                    hc_evoluciones c
                    where a.tipo_diagnostico_id=b.diagnostico_id
                    and a.evolucion_id = c.evolucion_id
                    and c.ingreso = ".SessionGetVar("Ingreso")."
                    order by b.diagnostico_id;";
     
          $resultado = $this->ConexionDB($query);
          if($resultado != false)
          {
               $i = 0;
               while($data = $resultado->FetchRow())
               {
               	$VectorDX[$i] = $data;
                    $i++;
               }
          }
          return $VectorDX;
     }
     
     
     //cor - clzc -ads
     function GetActivacionImpresion()
     {
          $query = "SELECT estado
          		FROM hc_sub_registroatencionsoat_ingreso
                    WHERE ingreso = ".SessionGetVar("Ingreso").";";
     
          $resultado = $this->ConexionDB($query);
          if($resultado != false)
          {
               $data = $resultado->fields['estado'];
          }
          return $data;
     }
     
     
     function Get_Tipo_ID($tipo)
     {
		if($tipo)
          {
               $tipo_id = "WHERE tipo_id_paciente = '".$tipo."'";
          }
          
          $query = "SELECT tipo_id_paciente FROM tipos_id_pacientes $tipo_id;";
          
          $resultado = $this->ConexionDB($query);
          if($resultado != false)
          {
               $i = 0;
               while($data = $resultado->FetchRow())
               {
               	$Vector[$i] = $data;
                    $i++;
               }
          }
          return $Vector;
     }
    
    
	function ConexionDB($query)
     {
     	list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          $dbconn->BeginTrans();
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;          
          $resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosMotivosConsulta - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          	$dbconn->RollbackTrans();
			return false;
		}
          
          $dbconn->CommitTrans();
          return $resultado;
     }

}
?>