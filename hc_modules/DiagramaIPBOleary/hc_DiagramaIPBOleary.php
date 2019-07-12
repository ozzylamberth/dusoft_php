
<?php

/**
* Submodulo de Diagrama de Indice de Placa Bacteriana Oleary.
*
* Submodulo para manejar el IPB Oleary del paciente.
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_DiagramaIPBOleary.php,v 1.31 2007/07/09 19:20:53 tizziano Exp $
*/

/**
* Diagrama de IPB Oleary
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar en la base
* de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del submodulo de DiagrmaIPBOleary.
*/

class DiagramaIPBOleary extends hc_classModules
{

    function DiagramaIPBOleary()
    {
        return true;
    }

    function GetVersion()
    {
        $informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JORGE ELIECER AVILA',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'requerimientos_adicionales' => '',
        'version_kernel' => '1.0'
        );
        return $informacion;
    }

    function GetReporte_Html()
    {
        $imprimir=$this->frmHistoria();
        if($imprimir==false)
        {
            return true;
        }
        return $imprimir;
    }

    function GetConsulta()
    {
        if($this->frmConsulta()==false)
        {
            return true;
        }
        return $this->salida;
    }

    function GetEstado()
    {
        return true;
    }

    function GetForma()//Desde esta funcion es de JORGE AVILA
    {
        $pfj=$this->frmPrefijo;
        if(empty($_REQUEST['accion'.$pfj]))
        {
            $this->frmForma();
        }
        elseif($_REQUEST['accion'.$pfj]=='insertar')
        {
            if($this->InsertDatos()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='eliminar')
        {
            if($this->EliminDatos()==true)
            {
                $this->frmForma();
            }
        }
        return $this->salida;
    }

    function BuscarTipoUbicacion()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT hc_tipo_ubicacion_diente_id,
        indice_orden
        FROM hc_tipos_ubicaciones_dientes
        ORDER BY indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarTipoCuadrantes()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.hc_tipo_cuadrante_id,
        A.descripcion,
        A.indice_orden
        FROM hc_tipos_cuadrantes_dientes AS A,
        hc_tipos_cuadrantes_dientes_oleary AS B
        WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_diente_oleary_id
        ORDER BY A.indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarOdontogramaControl()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[0];
    }

    function BuscarIPBOleary()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_indice_ipb_oleary_detalle_id,
        A.hc_tipo_ubicacion_diente_id,
        A.fecha_registro,
        B.descripcion AS des1
        FROM hc_indice_ipb_oleary_detalle AS A,
        hc_tipos_cuadrantes_dientes AS B,
        hc_indice_ipb_oleary AS C
        WHERE A.hc_indice_ipb_oleary_id=C.hc_indice_ipb_oleary_id
        AND A.hc_tipo_cuadrante_diente_oleary_id=B.hc_tipo_cuadrante_id
        AND C.tipo_id_paciente='".$this->tipoidpaciente."'
        AND C.paciente_id='".$this->paciente."'
        AND C.sw_activo='1'
        ORDER BY A.hc_tipo_ubicacion_diente_id,B.descripcion;";//AND C.evolucion_id=".$this->evolucion."
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

		function BuscarIPBOlearyConsulta()
		{
				list($dbconn) = GetDBconn();
				//VERIFICAR SI HAY CONTROL 
				$query="SELECT count(*)
				FROM hc_indice_ipb_oleary_detalle AS A,
				hc_tipos_cuadrantes_dientes AS B,
				hc_indice_ipb_oleary AS C
				WHERE C.hc_indice_ipb_oleary_id=
				(
					SELECT MAX(D.hc_indice_ipb_oleary_id)
					FROM hc_indice_ipb_oleary AS D,
								hc_indice_ipb_oleary_detalle AS E 
					WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
					AND D.paciente_id='".$this->paciente."'
					AND D.hc_indice_ipb_oleary_id=E.hc_indice_ipb_oleary_id 
					AND E.sw_control='1'
				)
				AND A.hc_indice_ipb_oleary_id=C.hc_indice_ipb_oleary_id
				AND A.hc_tipo_cuadrante_diente_oleary_id=B.hc_tipo_cuadrante_id;";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if($resulta->fields[0]>0)
				{
					$sql=" AND E.sw_control='1'";
				}
				else
				{
					$sql=" AND E.sw_control='0'";
				}
				//FIN VERIFICAR SI HAY CONTROL 
				$query="SELECT A.hc_indice_ipb_oleary_detalle_id,
				A.hc_tipo_ubicacion_diente_id,
				A.fecha_registro,
				B.descripcion AS des1
				FROM hc_indice_ipb_oleary_detalle AS A,
				hc_tipos_cuadrantes_dientes AS B,
				hc_indice_ipb_oleary AS C
				WHERE C.hc_indice_ipb_oleary_id=
				(
					SELECT MAX(D.hc_indice_ipb_oleary_id)
					FROM hc_indice_ipb_oleary AS D,
								hc_indice_ipb_oleary_detalle AS E 
					WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
					AND D.paciente_id='".$this->paciente."'
					AND D.hc_indice_ipb_oleary_id=E.hc_indice_ipb_oleary_id 
					$sql
				)
				AND A.hc_indice_ipb_oleary_id=C.hc_indice_ipb_oleary_id
				AND A.hc_tipo_cuadrante_diente_oleary_id=B.hc_tipo_cuadrante_id
				ORDER BY A.hc_tipo_ubicacion_diente_id,B.descripcion;";
				//AND C.evolucion_id=".$this->evolucion."
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$resulta->EOF)
				{
						$var[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				return $var;
		}

//  function InsertDatos()
//  {
//      $pfj=$this->frmPrefijo;
//      $this->frmError["MensajeError"]="";
//      if($_REQUEST['tipoubicpb'.$pfj]==NULL OR $_REQUEST['tipocuadpb'.$pfj]==NULL)
//      {
//          $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
//          return true;
//      }
//      else
//      {
//          list($dbconn) = GetDBconn();
//          $dbconn->BeginTrans();
//          $query="SELECT hc_indice_ipb_oleary_id
//          FROM hc_indice_ipb_oleary
//          WHERE tipo_id_paciente='".$this->tipoidpaciente."'
//          AND paciente_id='".$this->paciente."'
//          AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
//          $resulta = $dbconn->Execute($query);
//          if($dbconn->ErrorNo() != 0)
//          {
//              $this->error = "Error al Cargar el Modulo";
//              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//              return false;
//          }
//          $odonto=$resulta->fields[0];
//          if(empty($odonto))
//          {
//              $query="SELECT NEXTVAL ('hc_indice_ipb_oleary_hc_indice_ipb_oleary_id_seq');";
//              $resulta = $dbconn->Execute($query);
//              if($dbconn->ErrorNo() != 0)
//              {
//                  $this->error = "Error al Cargar el Modulo";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  return false;
//              }
//              $odonto=$resulta->fields[0];
//              $query="INSERT INTO hc_indice_ipb_oleary
//              (hc_indice_ipb_oleary_id,
//              tipo_id_paciente,
//              paciente_id,
//              evolucion_id,
//              sw_activo)
//              VALUES
//              (".$odonto.",
//              '".$this->tipoidpaciente."',
//              '".$this->paciente."',
//              ".$this->evolucion.",
//              '1');";
//              $resulta = $dbconn->Execute($query);
//              if($dbconn->ErrorNo() != 0)
//              {
//                  $this->error = "Error al Cargar el Modulo";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  $dbconn->RollbackTrans();
//                  return false;
//              }
//          }
//          $query="SELECT B.hc_tipo_ubicacion_diente_id
//          FROM hc_odontogramas_primera_vez AS A,
//          hc_odontogramas_primera_vez_detalle AS B
//          WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
//          AND A.paciente_id='".$this->paciente."'
//          AND A.sw_activo='1'
//          AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
//          AND B.hc_tipo_ubicacion_diente_id=".$_REQUEST['tipoubicpb'.$pfj].";";//A.evolucion_id=".$this->evolucion." AND
//          $resulta = $dbconn->Execute($query);
//          if($dbconn->ErrorNo() != 0)
//          {
//              $this->error = "Error al Cargar el Modulo";
//              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//              return false;
//          }
//          //MODIFICACION PARA QUE EL TIPO PROFESIONAL 10 (HIGIENISTA)
//          //PUEDA LLENAR EL IPBOLEARY SIN NECESIDAD DE QUE HALLAN DIAGNOSTICOS
//          //EN EL ODONTOGRAMA//if($resulta->fields[0]==NULL)
//          if($resulta->fields[0]==NULL AND $this->tipo_profesional != 10)
//          {
//              $this->frmError["MensajeError"]="EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]." NO TIENE UN DIAGNÓSTICO EN EL ODONTOGRAMA DE PRIMERA VEZ";
//              return true;
//          }
//          $query="SELECT B.hc_tipo_ubicacion_diente_id,
//          B.hc_tipo_problema_diente_id
//          FROM hc_odontogramas_primera_vez AS A,
//          hc_odontogramas_primera_vez_detalle AS B
//          WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
//          AND A.paciente_id='".$this->paciente."'
//          AND A.sw_activo='1'
//          AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
//          AND B.hc_tipo_ubicacion_diente_id=".$_REQUEST['tipoubicpb'.$pfj]."
//          AND (B.hc_tipo_problema_diente_id=2
//          OR B.hc_tipo_problema_diente_id=4
//          OR B.hc_tipo_problema_diente_id=5
//          OR B.hc_tipo_problema_diente_id=8
//          OR B.hc_tipo_problema_diente_id=12
//          OR B.hc_tipo_problema_diente_id=31);";//A.evolucion_id=".$this->evolucion." AND
//          $resulta = $dbconn->Execute($query);
//          if($dbconn->ErrorNo() != 0)
//          {
//              $this->error = "Error al Cargar el Modulo";
//              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//              return false;
//          }
//          if($resulta->fields[0]<>NULL)
//          {
//              $this->frmError["MensajeError"]="EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]." NO ESTÁ EN BOCA";
//              return true;
//          }
//          $a=explode(',',$_REQUEST['tipocuadpb'.$pfj]);
//          if((($_REQUEST['tipoubicpb'.$pfj]>=11 AND $_REQUEST['tipoubicpb'.$pfj]<=28)
//          OR ($_REQUEST['tipoubicpb'.$pfj]>=51 AND $_REQUEST['tipoubicpb'.$pfj]<=65))
//          AND ($a[0]==3 OR $a[1]==3 OR $a[2]==3))//($_REQUEST['tipocuadpb'.$pfj]==3)
//          {
//              $this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]."";
//              return true;
//          }
//          else if((($_REQUEST['tipoubicpb'.$pfj]>=31 AND $_REQUEST['tipoubicpb'.$pfj]<=48)
//          OR ($_REQUEST['tipoubicpb'.$pfj]>=71 AND $_REQUEST['tipoubicpb'.$pfj]<=85))
//          AND ($a[0]==2 OR $a[1]==2 OR $a[2]==2))//($_REQUEST['tipocuadpb'.$pfj]==2)
//          {
//              $this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]."";
//              return true;
//          }
//          if($a[0]<>0)
//          {
//              $query="SELECT hc_indice_ipb_oleary_id
//              FROM hc_indice_ipb_oleary_detalle
//              WHERE hc_indice_ipb_oleary_id=".$odonto."
//              AND hc_tipo_cuadrante_diente_oleary_id=".$a[0]."
//              AND hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicpb'.$pfj]."';";
//              $resulta = $dbconn->Execute($query);
//              if($dbconn->ErrorNo() != 0)
//              {
//                  $this->error = "Error al Cargar el Modulo";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  return false;
//              }
//              if(empty($resulta->fields[0]))
//              {
//                  $query="INSERT INTO hc_indice_ipb_oleary_detalle
//                  (hc_indice_ipb_oleary_id,
//                  hc_tipo_cuadrante_diente_oleary_id,
//                  hc_tipo_ubicacion_diente_id)
//                  VALUES
//                  (".$odonto.",
//                  ".$a[0].",
//                  '".$_REQUEST['tipoubicpb'.$pfj]."');";
//                  $resulta = $dbconn->Execute($query);
//                  if($dbconn->ErrorNo() != 0)
//                  {
//                      $this->error = "Error al Cargar el Modulo";
//                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                      $dbconn->RollbackTrans();
//                      return false;
//                  }
//                  $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
//              }
//              else
//              {
//                  $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
//              }
//          }
//          if($a[1]<>0)
//          {
//              $query="SELECT hc_indice_ipb_oleary_id
//              FROM hc_indice_ipb_oleary_detalle
//              WHERE hc_indice_ipb_oleary_id=".$odonto."
//              AND hc_tipo_cuadrante_diente_oleary_id=".$a[1]."
//              AND hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicpb'.$pfj]."';";
//              $resulta = $dbconn->Execute($query);
//              if($dbconn->ErrorNo() != 0)
//              {
//                  $this->error = "Error al Cargar el Modulo";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  return false;
//              }
//              if(empty($resulta->fields[0]))
//              {
//                  $query="INSERT INTO hc_indice_ipb_oleary_detalle
//                  (hc_indice_ipb_oleary_id,
//                  hc_tipo_cuadrante_diente_oleary_id,
//                  hc_tipo_ubicacion_diente_id)
//                  VALUES
//                  (".$odonto.",
//                  ".$a[1].",
//                  '".$_REQUEST['tipoubicpb'.$pfj]."');";
//                  $resulta = $dbconn->Execute($query);
//                  if($dbconn->ErrorNo() != 0)
//                  {
//                      $this->error = "Error al Cargar el Modulo";
//                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                      $dbconn->RollbackTrans();
//                      return false;
//                  }
//                  $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
//              }
//              else
//              {
//                  $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
//              }
//          }
//          if($a[2]<>0)
//          {
//              $query="SELECT hc_indice_ipb_oleary_id
//              FROM hc_indice_ipb_oleary_detalle
//              WHERE hc_indice_ipb_oleary_id=".$odonto."
//              AND hc_tipo_cuadrante_diente_oleary_id=".$a[2]."
//              AND hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicpb'.$pfj]."';";
//              $resulta = $dbconn->Execute($query);
//              if($dbconn->ErrorNo() != 0)
//              {
//                  $this->error = "Error al Cargar el Modulo";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  return false;
//              }
//              if(empty($resulta->fields[0]))
//              {
//                  $query="INSERT INTO hc_indice_ipb_oleary_detalle
//                  (hc_indice_ipb_oleary_id,
//                  hc_tipo_cuadrante_diente_oleary_id,
//                  hc_tipo_ubicacion_diente_id)
//                  VALUES
//                  (".$odonto.",
//                  ".$a[2].",
//                  '".$_REQUEST['tipoubicpb'.$pfj]."');";
//                  $resulta = $dbconn->Execute($query);
//                  if($dbconn->ErrorNo() != 0)
//                  {
//                      $this->error = "Error al Cargar el Modulo";
//                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                      $dbconn->RollbackTrans();
//                      return false;
//                  }
//                  $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
//              }
//              else
//              {
//                  $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
//              }
//          }
//          $dbconn->CommitTrans();
//          return true;
//      }
//  }


  //MODIFICACIÓN DE LA FUNCIÓN INSERTAR DATOS PARA PODER PROCESAR VARIAS
  //SUPERFICIES A LA VEZ CON EL MISMO DIAGNOSTICO.
  function InsertDatos()
  {
    $val=false;//VALIDAR QUE POR LO MENOS HALLA UN TIPO DE UBICACION A INSERTAR
    $pfj=$this->frmPrefijo;
    $this->frmError["MensajeError"]="";
        $fecha_registro=date("Y-m-d");
    //FOR PARA LOS $_REQUEST['tipoubicpb'.$i] 
    for($i=11; $i<86; $i++)
    { 
      if($_REQUEST['tipoubicpb'.$i]==on AND $_REQUEST['tipocuadpb'.$pfj]<>NULL)
      { 
        $val=true;//VALIDAR QUE POR LO MENOS HALLA UN TIPO DE UBICACION A INSERTAR
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_indice_ipb_oleary_id
        FROM hc_indice_ipb_oleary
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        $odonto=$resulta->fields[0];
        if(empty($odonto))
        {
          $query="SELECT NEXTVAL ('hc_indice_ipb_oleary_hc_indice_ipb_oleary_id_seq');";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $odonto=$resulta->fields[0];
          $query="INSERT INTO hc_indice_ipb_oleary
          (hc_indice_ipb_oleary_id,
          tipo_id_paciente,
          paciente_id,
          evolucion_id,
          sw_activo,
                    fecha_registro)
          VALUES
          (".$odonto.",
          '".$this->tipoidpaciente."',
          '".$this->paciente."',
          ".$this->evolucion.",
          '1',
                    now());";//'".$fecha_registro."'
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
        }
        $query="SELECT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id=".$i.";";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        //MODIFICACION PARA QUE EL TIPO PROFESIONAL 10 (HIGIENISTA)
        //PUEDA LLENAR EL IPBOLEARY SIN NECESIDAD DE QUE HALLAN DIAGNOSTICOS
        //EN EL ODONTOGRAMA//if($resulta->fields[0]==NULL)
        if($resulta->fields[0]==NULL AND $this->tipo_profesional != 10)
        {
          $this->frmError["MensajeError"]="EL DIENTE ".$i." NO TIENE UN DIAGNÓSTICO EN EL ODONTOGRAMA DE PRIMERA VEZ";
          return true;
        }
                
                //cambio dar para revisar, denticion mixta*******************************
                /*if($i>49 AND $this->tipo_profesional == 10 AND $resulta->fields[0]==NULL)
                {
                    $this->frmError["MensajeError"]="EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]." NO TIENE UN DIAGNÓSTICO EN EL ODONTOGRAMA DE PRIMERA VEZ";
                    return true;
                }*/
                //fin cambio dar----------**************    
                            
        $query="SELECT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_problema_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id=".$i."
        AND (B.hc_tipo_problema_diente_id=2
        OR B.hc_tipo_problema_diente_id=4
        OR B.hc_tipo_problema_diente_id=5
        OR B.hc_tipo_problema_diente_id=8
        OR B.hc_tipo_problema_diente_id=12
        OR B.hc_tipo_problema_diente_id=31);";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        if($resulta->fields[0]<>NULL)
        {
          $this->frmError["MensajeError"]="EL DIENTE ".$i." NO ESTÁ EN BOCA";
          return true;
        }
        $a=explode(',',$_REQUEST['tipocuadpb'.$pfj]);
        if((($i>=11 AND $i<=28)
        OR ($i>=51 AND $i<=65))
        AND ($a[0]==3 OR $a[1]==3 OR $a[2]==3))//($_REQUEST['tipocuadpb'.$pfj]==3)
        {
          $this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$i."";
          return true;
        }
        else if((($i>=31 AND $i<=48)
        OR ($i>=71 AND $i<=85))
        AND ($a[0]==2 OR $a[1]==2 OR $a[2]==2))//($_REQUEST['tipocuadpb'.$pfj]==2)
        {
          $this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$i."";
          return true;
        }
        
        //PARA INSERTAR LOS DATOSA DE CONTROL DE SEIS MESES
        if($_REQUEST['control']=='1')
        {
        $control=$_REQUEST['control'];
        }
        else
        {
        $control='0';
        }
        //
        
        if($a[0]<>0)
        {
          $query="SELECT hc_indice_ipb_oleary_id
          FROM hc_indice_ipb_oleary_detalle
          WHERE hc_indice_ipb_oleary_id=".$odonto."
          AND hc_tipo_cuadrante_diente_oleary_id=".$a[0]."
          AND hc_tipo_ubicacion_diente_id='".$i."';";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if(empty($resulta->fields[0]))
          {
            $query="INSERT INTO hc_indice_ipb_oleary_detalle
            (hc_indice_ipb_oleary_id,
            hc_tipo_cuadrante_diente_oleary_id,
            hc_tipo_ubicacion_diente_id,
            fecha_registro,
            sw_control)
            VALUES
            (".$odonto.",
            ".$a[0].",
            '".$i."',
            now(),
            '".$control."');"; 
            //'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          }
          else
          {
            $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
          }
        }
        if($a[1]<>0)
        {
          $query="SELECT hc_indice_ipb_oleary_id
          FROM hc_indice_ipb_oleary_detalle
          WHERE hc_indice_ipb_oleary_id=".$odonto."
          AND hc_tipo_cuadrante_diente_oleary_id=".$a[1]."
          AND hc_tipo_ubicacion_diente_id='".$i."';";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if(empty($resulta->fields[0]))
          {
            $query="INSERT INTO hc_indice_ipb_oleary_detalle
            (hc_indice_ipb_oleary_id,
            hc_tipo_cuadrante_diente_oleary_id,
            hc_tipo_ubicacion_diente_id,
            fecha_registro,
            sw_control)
            VALUES
            (".$odonto.",
            ".$a[1].",
            '".$i."',
             now(),
             '".$control."');";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          }
          else
          {
            $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
          }
        }
        if($a[2]<>0)
        {
          $query="SELECT hc_indice_ipb_oleary_id
          FROM hc_indice_ipb_oleary_detalle
          WHERE hc_indice_ipb_oleary_id=".$odonto."
          AND hc_tipo_cuadrante_diente_oleary_id=".$a[2]."
          AND hc_tipo_ubicacion_diente_id='".$i."';";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if(empty($resulta->fields[0]))
          {
            $query="INSERT INTO hc_indice_ipb_oleary_detalle
            (hc_indice_ipb_oleary_id,
            hc_tipo_cuadrante_diente_oleary_id,
            hc_tipo_ubicacion_diente_id,
            fecha_registro,
            sw_control)
            VALUES
            (".$odonto.",
            ".$a[2].",
            '".$i."',
            now(),
            '".$control."');";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          }
          else
          {
            $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
          }
        }
        $dbconn->CommitTrans();
      }//FIN IF DE LOS on 
      else
        if($_REQUEST['tipocuadpb'.$pfj]==NULL)
        { 
          $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS(tipo cuadrante.)";
          return true;
        }
    }//FIN FOR DE LOS $_REQUEST['op'.$i]  
    if(!$val)
    { 
      $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS(tipo ubicación.)";
      return true;
    }
    else
    {
    	$this->RegistrarSubmodulo($this->GetVersion());
     return true;	
    }
  } 
  //FIN MODIFICACIÓN INSERTAR DATOS.
    
  function EliminDatos()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="DELETE FROM hc_indice_ipb_oleary_detalle
        WHERE hc_indice_ipb_oleary_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $query="DELETE FROM hc_indice_ipb_oleary
            WHERE tipo_id_paciente='".$this->tipoidpaciente."'
            AND paciente_id='".$this->paciente."'
            AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
            $resulta = $dbconn->Execute($query);
            $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
            return true;
        }
    }

    function BuscarOdontogramaPaciente()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez_detalle AS A,
        hc_odontogramas_primera_vez AS B,
        hc_tipos_problemas_dientes AS C
        WHERE A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.tipo_id_paciente='".$this->tipoidpaciente."'
        AND B.paciente_id='".$this->paciente."'
        AND B.sw_activo='1'
        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND (C.sw_cariado='1'
        OR C.sw_obturado='1'
        OR (C.sw_perdidos='1'
        AND C.hc_tipo_problema_diente_id='3')
        OR C.sw_sanos='1');";//AND B.evolucion_id=".$this->evolucion."
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var=$resulta->RecordCount();
        return $var;
    }

    function CalcularIPBOleary()
    {
//(numero de superficies con placa/numero de superficies totales)*100
//numero de superficies totales=numero de dientes * 4, tengo que buscar el numero de dientes del paciente
        $numerodien=52-$this->BuscarDienteCantidadNoBoca();//$numerodien=$this->BuscarOdontogramaPaciente();
        $placab=$this->BuscarSuperficiesplaca();
        $numerosupe=($numerodien*4);
        $numerosupe;
        $ipboleary=($placab/$numerosupe)*100;
        return $ipboleary;
    }

    function CalcularIPBOlearyConsulta()
    {
//(numero de superficies con placa/numero de superficies totales)*100
//numero de superficies totales=numero de dientes * 4, tengo que buscar el numero de dientes del paciente
        $numerodien=52-$this->BuscarDienteCantidadNoBocaConsulta();//$numerodien=$this->BuscarOdontogramaPaciente();
        $placab=$this->BuscarSuperficiesplacaConsulta();
        $numerosupe=($numerodien*4);
        $numerosupe;
        $ipboleary=($placab/$numerosupe)*100;
        return $ipboleary;
    }

    function BuscarDienteCantidadNoBoca()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND (B.hc_tipo_problema_diente_id=2
        OR B.hc_tipo_problema_diente_id=4
        OR B.hc_tipo_problema_diente_id=5
        OR B.hc_tipo_problema_diente_id=8
        OR B.hc_tipo_problema_diente_id=12
        OR B.hc_tipo_problema_diente_id=31)
        ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var=$resulta->RecordCount();
        return $var;
    }

    function BuscarDienteCantidadNoBocaConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.hc_odontograma_primera_vez_id=
        (SELECT MAX(D.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."')
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND (B.hc_tipo_problema_diente_id=2
        OR B.hc_tipo_problema_diente_id=4
        OR B.hc_tipo_problema_diente_id=5
        OR B.hc_tipo_problema_diente_id=8
        OR B.hc_tipo_problema_diente_id=12
        OR B.hc_tipo_problema_diente_id=31)
        ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var=$resulta->RecordCount();
        return $var;
    }

    function ConsultaHigieneOral()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez_detalle AS A,
        hc_odontogramas_primera_vez AS B,
        hc_tipos_problemas_dientes AS C
        WHERE A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.tipo_id_paciente='".$this->tipoidpaciente."'
        AND B.paciente_id='".$this->paciente."'
        AND B.sw_activo='1'
        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND (A.hc_tipo_problema_diente_id=2
        OR A.hc_tipo_problema_diente_id=8
        OR A.hc_tipo_problema_diente_id=31);";//AND B.evolucion_id=".$this->evolucion."
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var=$resulta->RecordCount();
        return $var;
    }

    function BuscarSuperficiesplaca()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.hc_tipo_cuadrante_diente_oleary_id
        FROM hc_indice_ipb_oleary AS A,
        hc_indice_ipb_oleary_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
        ORDER BY B.hc_tipo_cuadrante_diente_oleary_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $total=0;
        while(!$resulta->EOF)
        {
            if($resulta->fields[0]<>11)
            {
                $total++;
            }
            else
            {
                $total=$total+4;
            }
            $resulta->MoveNext();
        }
        return $total;
    }

    function BuscarSuperficiesplacaConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.hc_tipo_cuadrante_diente_oleary_id
        FROM hc_indice_ipb_oleary AS A,
        hc_indice_ipb_oleary_detalle AS B
        WHERE A.hc_indice_ipb_oleary_id=
        (SELECT MAX(D.hc_indice_ipb_oleary_id)
        FROM hc_indice_ipb_oleary AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."')
        AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
        ORDER BY B.hc_tipo_cuadrante_diente_oleary_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $total=0;
        while(!$resulta->EOF)
        {
            if($resulta->fields[0]<>11)
            {
                $total++;
            }
            else
            {
                $total=$total+4;
            }
            $resulta->MoveNext();
        }
        return $total;
    }

    function BuscarEnviarPintarOleary()
    {
        list($dbconn) = GetDBconn();
/*				$query="SELECT B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id
				FROM hc_indice_ipb_oleary AS A,
				hc_indice_ipb_oleary_detalle AS B
				WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
				AND A.paciente_id='".$this->paciente."'
				AND A.sw_activo='1'
				AND B.sw_control='1'
				AND date(B.fecha_registro)='".date('Y-m-d')."'
				AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
				ORDER BY B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        if($resulta->RecordCount() > 0)
        {
					while(!$resulta->EOF)
					{
							$var[$i][0]=$resulta->fields[0];
							$var[$i][1]=$resulta->fields[1];
							$i++;
							$resulta->MoveNext();
					}
					return $var;
        }
        else
				{*/
					$query="SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_diente_oleary_id
					FROM hc_indice_ipb_oleary AS A,
					hc_indice_ipb_oleary_detalle AS B
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
					ORDER BY B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_diente_oleary_id;";
					//A.evolucion_id=".$this->evolucion." AND
				//}
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i][0]=$resulta->fields[0];
            $var[$i][1]=$resulta->fields[1];
            $i++;
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarEnviarPintarOlearyConsulta()
    {
        list($dbconn) = GetDBconn();
				//VERIFICAR SI HAY DATOS  DE CONTROL
        $query="SELECT count(*)
        FROM hc_indice_ipb_oleary AS A,
        hc_indice_ipb_oleary_detalle AS B
        WHERE A.hc_indice_ipb_oleary_id=
        (
            SELECT MAX(D.hc_indice_ipb_oleary_id)
            FROM hc_indice_ipb_oleary AS D,
                 hc_indice_ipb_oleary_detalle AS E 
            WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
            AND D.paciente_id='".$this->paciente."'
            AND D.hc_indice_ipb_oleary_id=E.hc_indice_ipb_oleary_id 
            AND E.sw_control='1'
        )
        AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
        ;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
				if($resulta->fields[0]>0)
				{
					$sql=" AND E.sw_control='1'";
				}
				else
				{
					$sql=" AND E.sw_control='0'";
				}
				//FIN VERIFICAR SI HAY DATOS  DE CONTROL
				$query="SELECT B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id
				FROM hc_indice_ipb_oleary AS A,
				hc_indice_ipb_oleary_detalle AS B
				WHERE A.hc_indice_ipb_oleary_id=
				(
						SELECT MAX(D.hc_indice_ipb_oleary_id)
						FROM hc_indice_ipb_oleary AS D,
									hc_indice_ipb_oleary_detalle AS E 
						WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
						AND D.paciente_id='".$this->paciente."'
						AND D.hc_indice_ipb_oleary_id=E.hc_indice_ipb_oleary_id 
						$sql
				)
				AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
				ORDER BY B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id;";
				//A.evolucion_id=".$this->evolucion." AND
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i][0]=$resulta->fields[0];
						$var[$i][1]=$resulta->fields[1];
						$i++;
						$resulta->MoveNext();
				}
				return $var;
		}

		function BuscarEnviarPintarOlearyConsultaControl()
		{
				list($dbconn) = GetDBconn();
				//VERIFICAR SI HAY DATOS  DE CONTROL
				//FIN VERIFICAR SI HAY DATOS  DE CONTROL
				$query="SELECT B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id
				FROM hc_indice_ipb_oleary AS A,
				hc_indice_ipb_oleary_detalle AS B
				WHERE A.hc_indice_ipb_oleary_id=
				(
						SELECT MAX(D.hc_indice_ipb_oleary_id)
						FROM hc_indice_ipb_oleary AS D,
									hc_indice_ipb_oleary_detalle AS E 
						WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
						AND D.paciente_id='".$this->paciente."'
						AND D.hc_indice_ipb_oleary_id=E.hc_indice_ipb_oleary_id 
						AND D.sw_activo='0'
				)
				AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
				ORDER BY B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id;";
				//A.evolucion_id=".$this->evolucion." AND
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i][0]=$resulta->fields[0];
						$var[$i][1]=$resulta->fields[1];
						$i++;
						$resulta->MoveNext();
				}
				return $var;
		}

    function BuscarEnviarPintarOlearyConsulta2()
    {
        list($dbconn) = GetDBconn();
				$query="SELECT B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id
				FROM hc_indice_ipb_oleary AS A,
				hc_indice_ipb_oleary_detalle AS B
				WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
				AND A.paciente_id='".$this->paciente."' 
				AND A.evolucion_id=".$this->evolucion."
				AND A.sw_activo='0'
				AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
				ORDER BY B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_diente_oleary_id;";
				//A.evolucion_id=".$this->evolucion." AND
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i][0]=$resulta->fields[0];
						$var[$i][1]=$resulta->fields[1];
						$i++;
						$resulta->MoveNext();
				}
				return $var;
		}

    function BuscarEnviarPintarNoBoca()
    {
        list($dbconn) = GetDBconn();
        //cambio dar
        //si es higienista este caso es para la denticion mixta, para q no pinte las dos cosas en el ipb
        if($this->tipo_profesional == 10)
        {
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
                B.hc_tipo_problema_diente_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.paciente_id='".$this->paciente."'
                AND A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_ubicacion_diente_id<49
                AND (B.hc_tipo_problema_diente_id=2
                OR B.hc_tipo_problema_diente_id=4
                OR B.hc_tipo_problema_diente_id=5
                OR B.hc_tipo_problema_diente_id=8
                OR B.hc_tipo_problema_diente_id=12
                OR B.hc_tipo_problema_diente_id=31)
                AND B.hc_tipo_ubicacion_diente_id not in (SELECT case when b.hc_tipo_ubicacion_diente_id > 49 then (to_number(b.hc_tipo_ubicacion_diente_id,'99') - 40) else 0 end as hc_tipo_ubicacion_diente_id 
                                                                                            FROM hc_indice_ipb_oleary as a, hc_indice_ipb_oleary_detalle as b 
                                                                                            WHERE a.paciente_id='".$this->paciente."' 
                                                                                            and a.tipo_id_paciente='".$this->tipoidpaciente."' 
                                                                                            and a.sw_activo='1' 
                                                                                            and a.hc_indice_ipb_oleary_id=b.hc_indice_ipb_oleary_id 
                                                                                            and b.hc_tipo_ubicacion_diente_id > 49)
                ORDER BY B.hc_tipo_ubicacion_diente_id;";
        
        }
        else
        {   
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
                B.hc_tipo_problema_diente_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.paciente_id='".$this->paciente."'
                AND A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_ubicacion_diente_id<49
                AND (B.hc_tipo_problema_diente_id=2
                OR B.hc_tipo_problema_diente_id=4
                OR B.hc_tipo_problema_diente_id=5
                OR B.hc_tipo_problema_diente_id=8
                OR B.hc_tipo_problema_diente_id=12
                OR B.hc_tipo_problema_diente_id=31)
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
        }
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        $deciduos=$this->BuscarValidarNoBoca();
        while(!$resulta->EOF)
        {
            if(!($deciduos[$resulta->fields[0]+40]==1))
            {
                $var[$i][0]=$resulta->fields[0];
                $var[$i][1]=$resulta->fields[1];
                $i++;
            }
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarEnviarPintarNoBocaConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_problema_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.hc_odontograma_primera_vez_id=
        (SELECT MAX(D.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."')
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id<49
        AND (B.hc_tipo_problema_diente_id=2
        OR B.hc_tipo_problema_diente_id=4
        OR B.hc_tipo_problema_diente_id=5
        OR B.hc_tipo_problema_diente_id=8
        OR B.hc_tipo_problema_diente_id=12
        OR B.hc_tipo_problema_diente_id=31)
        ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        $deciduos=$this->BuscarValidarNoBocaConsulta();
        while(!$resulta->EOF)
        {
            if(!($deciduos[$resulta->fields[0]+40]==1))
            {
                $var[$i][0]=$resulta->fields[0];
                $var[$i][1]=$resulta->fields[1];
                $i++;
            }
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarEnviarPintarNoBocaConsultaControl()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_problema_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.hc_odontograma_primera_vez_id=
        (SELECT MAX(D.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."'
        AND D.sw_activo='0')
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id<49
        AND (B.hc_tipo_problema_diente_id=2
        OR B.hc_tipo_problema_diente_id=4
        OR B.hc_tipo_problema_diente_id=5
        OR B.hc_tipo_problema_diente_id=8
        OR B.hc_tipo_problema_diente_id=12
        OR B.hc_tipo_problema_diente_id=31)
        ORDER BY B.hc_tipo_ubicacion_diente_id;"; 
        //A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        $deciduos=$this->BuscarValidarNoBocaConsulta();
        while(!$resulta->EOF)
        {
            if(!($deciduos[$resulta->fields[0]+40]==1))
            {
                $var[$i][0]=$resulta->fields[0];
                $var[$i][1]=$resulta->fields[1];
                $i++;
            }
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarEnviarPintarNoBocaConsulta2()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_problema_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.evolucion_id=".$this->evolucion."
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id<49
        AND (B.hc_tipo_problema_diente_id=2
        OR B.hc_tipo_problema_diente_id=4
        OR B.hc_tipo_problema_diente_id=5
        OR B.hc_tipo_problema_diente_id=8
        OR B.hc_tipo_problema_diente_id=12
        OR B.hc_tipo_problema_diente_id=31)
        ORDER BY B.hc_tipo_ubicacion_diente_id;";
        //A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        $deciduos=$this->BuscarValidarNoBocaConsulta();
        while(!$resulta->EOF)
        {
            if(!($deciduos[$resulta->fields[0]+40]==1))
            {
                $var[$i][0]=$resulta->fields[0];
                $var[$i][1]=$resulta->fields[1];
                $i++;
            }
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarValidarNoBoca()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id>49
        AND B.hc_tipo_problema_diente_id<>2
        AND B.hc_tipo_problema_diente_id<>4
        AND B.hc_tipo_problema_diente_id<>5
        AND B.hc_tipo_problema_diente_id<>8
        AND B.hc_tipo_problema_diente_id<>12
        AND B.hc_tipo_problema_diente_id<>31
        ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[$resulta->fields[0]]=1;
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarValidarNoBocaConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.hc_odontograma_primera_vez_id=
        (SELECT MAX(D.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."')
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id>49
        AND B.hc_tipo_problema_diente_id<>2
        AND B.hc_tipo_problema_diente_id<>4
        AND B.hc_tipo_problema_diente_id<>5
        AND B.hc_tipo_problema_diente_id<>8
        AND B.hc_tipo_problema_diente_id<>12
        AND B.hc_tipo_problema_diente_id<>31
        ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[$resulta->fields[0]]=1;
            $resulta->MoveNext();
        }
        return $var;
    }

//---------------nuevo dar
    function UltimoOdnotogramaIPoInactivo() 
    {
            list($dbconn) = GetDBconn();
            $query="(SELECT MAX(D.hc_indice_ipb_oleary_id)
            FROM hc_indice_ipb_oleary AS D
            WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
            AND D.paciente_id='".$this->paciente."'
            AND D.sw_activo='0');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }           
            if(!$resulta->EOF)
            {
                    $odonto=$resulta->fields[0];
                    $resulta->Close();
                    return $odonto;
            }
            return $odonto;
    }
    
    function BuscarEnviarPintarOlearyTraConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_cuadrante_diente_oleary_id
        FROM hc_indice_ipb_oleary_trata_detalle AS B
        WHERE B.hc_indice_ipb_oleary_trata_id=
        (SELECT MAX(A.hc_indice_ipb_oleary_trata_id)
        FROM hc_indice_ipb_oleary_trata AS A
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='0')
        ORDER BY B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_cuadrante_diente_oleary_id;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i][0]=$resulta->fields[0];
            $var[$i][1]=$resulta->fields[1];
            $i++;
            $resulta->MoveNext();
        }
        return $var;
    }       
    
    function BuscarEnviarPintarNoBocaTraConsulta($var2)//falta la copia del de tratamiento
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_problema_diente_id
        FROM hc_odontogramas_primera_vez_detalle AS B
        WHERE B.hc_odontograma_primera_vez_id=
        (SELECT MAX(A.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS A
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='0')
        AND B.hc_tipo_ubicacion_diente_id<49
        AND (B.hc_tipo_problema_diente_id=3
        OR B.hc_tipo_problema_diente_id=23)
        AND B.estado='0'
        ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var4[$i][0]=$resulta->fields[0];
            if($resulta->fields[1]==3)
            {
                $var4[$i][1]=8;
            }
            else
            {
                $var4[$i][1]=12;
            }
            $i++;
            $resulta->MoveNext();
        }
        $query="SELECT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_problema_diente_id
        FROM hc_odontogramas_tratamientos_detalle AS B
        WHERE B.hc_odontograma_tratamiento_id=
        (SELECT MAX(A.hc_odontograma_tratamiento_id)
        FROM hc_odontogramas_tratamientos AS A
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='0')
        AND B.hc_tipo_ubicacion_diente_id<49
        AND ((B.hc_tipo_problema_diente_id=3
        OR B.hc_tipo_problema_diente_id=23)
        AND B.estado=0)
        ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=$k=0;
        while(!$resulta->EOF)
        {
            if($var4[$k][0]<$resulta->fields[0]
            AND $var4[$k][0]<>NULL)
            {
                $var[$i][0]=$var4[$k][0];
                $var[$i][1]=$var4[$k][1];
                $k++;
            }
            else
            {
                $var[$i][0]=$resulta->fields[0];
                if($resulta->fields[1]==3)
                {
                    $var[$i][1]=8;
                }
                else
                {
                    $var[$i][1]=12;
                }
            }
            $i++;
            $resulta->MoveNext();
        }
        for(;$var4[$k][0]<>NULL;$k++)
        {
            $var[$i][0]=$var4[$k][0];
            $var[$i][1]=$var4[$k][1];
            $i++;
        }
        $k=$l=0;
        $ciclo1=sizeof($var);
        $ciclo2=sizeof($var2);
        if($ciclo1>=$ciclo2)
        {
            for($i=0;$i<$ciclo1;$i++)
            {
                if($var[$i][0]==$var2[$l][0] AND $var2[$l][0]<>NULL)
                {
                    $var3[$k][0]=$var[$i][0];
                    $var3[$k][1]=$var[$i][1];
                    $l++;
                }
                else if($var2[$l][0]<$var[$i][0] AND $var2[$l][0]<>NULL)
                {
                    $var3[$k][0]=$var2[$l][0];
                    $var3[$k][1]=$var2[$l][1];
                    $l++;
                    $i--;
                }
                else if($var2[$l][0]>$var[$i][0] AND $var2[$l][0]<>NULL)
                {
                    $var3[$k][0]=$var[$i][0];
                    $var3[$k][1]=$var[$i][1];
                }
                else if($var2[$l][0]==NULL)
                {
                    $var3[$k][0]=$var[$i][0];
                    $var3[$k][1]=$var[$i][1];
                }
                $k++;
            }
        }
        else if($ciclo1<$ciclo2)
        {
            for($i=0;$i<$ciclo2;$i++)
            {
                if($var[$i][0]==$var2[$l][0] AND $var[$i][0]<>NULL)
                {
                    $var3[$k][0]=$var[$i][0];
                    $var3[$k][1]=$var[$i][1];
                    $l++;
                }
                else if($var2[$l][0]<$var[$i][0] AND $var[$i][0]<>NULL)
                {
                    $var3[$k][0]=$var2[$l][0];
                    $var3[$k][1]=$var2[$l][1];
                    $l++;
                    $i--;
                }
                else if($var2[$l][0]>$var[$i][0] AND $var[$i][0]<>NULL)
                {
                    $var3[$k][0]=$var[$i][0];
                    $var3[$k][1]=$var[$i][1];
                }
                else if($var[$i][0]==NULL)
                {
                    $var3[$k][0]=$var2[$l][0];
                    $var3[$k][1]=$var2[$l][1];
                    $l++;
                }
                $k++;
            }
        }
        return $var3;
    }
    
    
//----------------------------------    
}
?>
