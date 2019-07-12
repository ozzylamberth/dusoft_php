<?php

/**
 * $Id: hc_HistoriaOdontologicaUrgencias.php,v 1.10 2007/11/22 14:37:57 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Submodulo para controlar las acciones preventivas de odontologia a realizar en el paciente
 */

/**
* Accion Preventiva
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de accion preventiva.
*/

class HistoriaOdontologicaUrgencias extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function HistoriaOdontologicaUrgencias()
	{
		return true;
	}

/**
* Esta función retorna los datos de concernientes a la version del submodulo
*
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'CARLOS A. HENAO',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}

/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
 	function GetConsulta()//Corregida para el submodulo
 	{
 		if($this->frmConsulta()==false)
 		{
 			return true;
 		}
 		return $this->salida;
 	}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetEstado()
	{
		return true;
	}

/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma()//
	{
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj]))
		{
			$this->frmForma();
		}
		elseif($_REQUEST['accion'.$pfj]=='insertaractiodon')
		{
			if($this->InsertActivacionOdontograma()==true)
			{
				$this->frmForma();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='insertarinacodon')
		{
			if($this->InsertInactivacionOdontograma()==true)
			{
				$this->frmForma();
			}
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
		elseif($_REQUEST['accion'.$pfj]=='insertarobser')
		{
			if($this->InsertDatosObser()==true)
			{
				$this->frmForma();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='insertarcopiar')
		{
			if($this->InsertDatosCopiar()==true)
			{
				$this->frmForma();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='insertarapoyos')
		{
			if($this->InsertDatosApoyos()==true)
			{
				$this->frmApoyos();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='apoyos')
		{
			$this->frmApoyos();
		}
		return $this->salida;
	}

//INICIO NUEVA MODIFICACIÓN ****JUNIO 16/2005****
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

	function BuscarTipoProblema()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT hc_tipo_problema_diente_id,
		descripcion,
		indice_orden,
		sw_diente_completo
		FROM hc_tipos_problemas_dientes
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

	function BuscarTipoProductos()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT hc_tipo_producto_diente_id,
		descripcion,
		indice_orden
		FROM hc_tipos_productos_dientes
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
//FIN NUEVA MODIFICACIÓN

	function BuscarAccionPreventiva()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.tipo_accion_id,
		A.nombre,
		B.hc_accion_preventiva_id,
		B.sw_accion_preventiva,
		B.descripcion
		FROM hc_tipos_accion_preventiva AS A
		LEFT JOIN hc_accion_preventiva AS B ON
		(A.tipo_accion_id=B.tipo_accion_id
		AND B.evolucion_id=".$this->evolucion.")
		ORDER BY A.tipo_accion_id;";
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

	function InsertDatos()
	{
          //INICIO VALIDACIÓN
          $pfj=$this->frmPrefijo;
          $this->frmError["MensajeError"]="";
          $salir=0;
          $a=explode(',',$_REQUEST['tipoproble'.$pfj]);
          if($a[1]==1)
          {
               $_REQUEST['0'.$pfj]=11;
          }
          else if($a[1]==0 AND $_REQUEST['0'.$pfj]==11)
          {
               $this->frmError["MensajeError"]="PROBLEMA QUE REQUIERE ESPECIFICAR UNA SUPERFICIE";
               return true;
          }
          for($i=0;$i<8;$i++)
          {
               if($_REQUEST[$i.$pfj]<>NULL)
               {
                    $salir=1;
                    $i=8;
               }
          }
          if($_REQUEST['tipoproble'.$pfj]==NULL OR $_REQUEST['tipoubicac'.$pfj]==NULL
          OR $salir==0 OR $_REQUEST['tipoproduc'.$pfj]==NULL)
          {
               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
               return true;
          }
          else
          {
               list($dbconn) = GetDBconn();
               $dbconn->BeginTrans();
               $query="SELECT hc_odontologia_evolucion_urgencias_detalle_id
               FROM hc_odontologia_evolucion_urgencias_detalle
               WHERE evolucion_id=".$this->evolucion.";";
               $resulta = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               $odonto=$resulta->fields[0];
               $query="SELECT NEXTVAL ('hc_odontologia_evolucion_urge_hc_odontologia_evolucion_urge_seq');";
               $resulta = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               $odonto=$resulta->fields[0];
     
          if($_REQUEST['tipoubicac'.$pfj]>=51)
          {
               $query="SELECT hc_tipo_problema_diente_des_id
               FROM hc_tipos_problemas_dientes_desiduos
               WHERE hc_tipo_problema_diente_des_id=".$a[0].";";
               $resulta = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               if($resulta->fields[0]==NULL)
               {
                    $this->frmError["MensajeError"]="PROBLEMA NO VÁLIDO PARA EL DIENTE ".$_REQUEST['tipoubicac'.$pfj]."";
                    return true;
               }
               $query="SELECT hc_tipo_problema_diente_id
               FROM hc_odontologia_evolucion_urgencias_detalle
               WHERE hc_odontologia_evolucion_urgencias_detalle_id=".$odonto."
               AND hc_tipo_ubicacion_diente_id='".($_REQUEST['tipoubicac'.$pfj]-40)."';";
               $resulta = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               if($resulta->fields[0]==NULL)
               {
                    $this->frmError["MensajeError"]="EL DIENTE '".($_REQUEST['tipoubicac'.$pfj]-40)."' NO TIENE UN DIAGNÓSTICO";
                    return true;
               }
          }
          $query="SELECT A.hc_tipo_problema_diente_id
          FROM hc_tipos_problemas_soluciones_dientes_urgencias AS A,
          hc_tipos_problemas_dientes AS B
          WHERE A.hc_tipo_problema_diente_id=".$a[0]."
          AND A.hc_tipo_producto_diente_id=".$_REQUEST['tipoproduc'.$pfj]."
          AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id;";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if($resulta->fields[0]==NULL)
          {
               $this->frmError["MensajeError"]="SOLUCIÓN NO VÁLIDA PARA EL PROBLEMA EN EL DIENTE ".$_REQUEST['tipoubicac'.$pfj]."";
               return true;
          }
          $query="SELECT B.sw_diente_completo,
                         A.hc_tipo_cuadrante_id,
                         A.hc_tipo_problema_diente_id,
                         A.hc_tipo_producto_diente_id,
                         A.hc_tipo_ubicacion_diente_id
	             FROM hc_odontologia_evolucion_urgencias_detalle AS A,
                         hc_tipos_problemas_dientes AS B
                  WHERE A.evolucion_id=".$this->evolucion."
                         AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id
                         AND A.hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicac'.$pfj]."'
                         ORDER BY B.sw_diente_completo ASC;";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          if(($_REQUEST['tipoubicac'.$pfj]==$resulta->fields[4]) AND ($_REQUEST['0'.$pfj]==$resulta->fields[1]))
          {
               $dbconn->RollbackTrans();
               $this->frmError["MensajeError"]="DATOS PARA UNA SUPERFICIE REPETIDA";
               return true;
          }
          
          $sw=0;
          if($resulta->EOF AND $_REQUEST['0'.$pfj]<>NULL)
          {
               $fecharegistro=date("Y-m-d");
               $usuario=UserGetUID();
               $query="INSERT INTO hc_odontologia_evolucion_urgencias_detalle
               (
                    hc_odontologia_evolucion_urgencias_detalle_id,
                    hc_tipo_cuadrante_id,
                    hc_tipo_ubicacion_diente_id,
                    hc_tipo_problema_diente_id,
                    hc_tipo_producto_diente_id,
                    evolucion_id,
                    ingreso,
                    fecha_registro,
                    usuario_id
               )
               VALUES
               (
                    ".$odonto.",
                    ".$_REQUEST['0'.$pfj].",
                    '".$_REQUEST['tipoubicac'.$pfj]."',
                    ".$a[0].",
                    ".$_REQUEST['tipoproduc'.$pfj].",
                    ".$this->evolucion.",
                    ".$this->ingreso.",
                    now(),
                    $usuario
               );";
               $resulta = $dbconn->Execute($query); 
               if($dbconn->ErrorNo() != 0)
               {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return true;
               }
               $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
               $_REQUEST['tipoubicac'.$pfj]='';
               $_REQUEST['tipoproble'.$pfj]='';
               $_REQUEST['tipoproduc'.$pfj]='';
               $dbconn->CommitTrans();
               return true;
          }
          else if($resulta->EOF AND $_REQUEST['0'.$pfj]==NULL)
          {
               for($i=1;$i<8;$i++)
               {
                    $query="SELECT NEXTVAL ('hc_odontologia_evolucion_urge_hc_odontologia_evolucion_urge_seq');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                    }
                    $odonto=$resulta->fields[0];
          
                    if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                    OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8)
                    AND $sw==0 AND $_REQUEST[$i.$pfj]<>NULL)
                    {
                    $sw=1;
                    }
                    else if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                    OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8)
                    AND $sw==1 AND $_REQUEST[$i.$pfj]<>NULL)
                    {
                    $_REQUEST['tipoproduc'.$pfj]++;
                    }
                    if($_REQUEST[$i.$pfj]<>NULL)
                    {
                    $fecharegistro=date("Y-m-d");
                    $usuario=UserGetUID();
                    $query="INSERT INTO hc_odontologia_evolucion_urgencias_detalle
                    (
                    hc_odontologia_evolucion_urgencias_detalle_id,
                    hc_tipo_cuadrante_id,
                    hc_tipo_ubicacion_diente_id,
                    hc_tipo_problema_diente_id,
                    hc_tipo_producto_diente_id,
                    evolucion_id,
                    ingreso,
                    fecha_registro,
                    usuario_id
                    )
                    VALUES
                    (".$odonto.",
                    ".$_REQUEST[$i.$pfj].",
                    '".$_REQUEST['tipoubicac'.$pfj]."',
                    ".$a[0].",
                    ".$_REQUEST['tipoproduc'.$pfj].",
                    ".$this->evolucion.",
                    ".$this->ingreso.",
                    now(),
                    $usuario
                    );";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                         $dbconn->RollbackTrans();
                         $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                         $this->fileError = __FILE__;
                         $this->lineError = __LINE__;
                         return true;
                    }
               }
          }
          $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          $_REQUEST['tipoubicac'.$pfj]='';
          $_REQUEST['tipoproble'.$pfj]='';
          $_REQUEST['tipoproduc'.$pfj]='';
          $dbconn->CommitTrans();
          $this->RegistrarSubmodulo($this->GetVersion());
          return true;
          }
          else
          {
          for($i=1;$i<8;$i++)
          {
          //print_r($resulta);
               $resulta->MoveFirst();
               $inserte=0;
               while(!$resulta->EOF)
               {
               if($resulta->fields[0]==1 AND
               !($resulta->fields[2]==6 OR $resulta->fields[2]==24))
               {
               $dbconn->RollbackTrans();
               $this->frmError["MensajeError"]="EL DIENTE '".$_REQUEST['tipoubicac'.$pfj]."' TIENE UN PROBLEMA DE DIENTE COMPLETO";
               return true;
               }
               else if($resulta->fields[0]==1
               AND ($resulta->fields[2]==6 OR $resulta->fields[2]==24)
               AND $_REQUEST[$i.$pfj]<>NULL
               AND ($a[0]==10 OR $a[0]==14 OR $a[0]==20
               OR $a[0]==25 OR $a[0]==26 OR $a[0]==30))
               {
               $inserte=1;
               }
               else if($resulta->fields[1]<>$_REQUEST[$i.$pfj]
               AND $_REQUEST[$i.$pfj]<>NULL
               AND $resulta->fields[0]==0)
               {
               $inserte=1;
               }
               else if($resulta->fields[1]==$_REQUEST[$i.$pfj]
               AND $_REQUEST[$i.$pfj]<>NULL)
               {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="DATOS PARA UNA SUPERFICIE REPETIDA";
                    return true;
               }
               if($_REQUEST['tipoproduc'.$pfj]==$resulta->fields[3]
               AND ($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
               OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8))
               {
               $_REQUEST['tipoproduc'.$pfj]++;
               }
               $resulta->MoveNext();
               }
      //FIN VALIDACIÓN

               $ubicacion=$_REQUEST['tipoubicac'.$pfj];
               $problema=$a[0];
               $producto=$_REQUEST['tipoproduc'.$pfj];
               $fecharegistro=date("Y-m-d");
               $usuario=UserGetUID();
          
               if($inserte==1)
               {
               $query="INSERT INTO hc_odontologia_evolucion_urgencias_detalle
                    (
                    hc_odontologia_evolucion_urgencias_detalle_id,
                    hc_tipo_cuadrante_id,
                    hc_tipo_ubicacion_diente_id,
                    hc_tipo_problema_diente_id,
                    hc_tipo_producto_diente_id,
                    evolucion_id,
                    ingreso,
                    fecha_registro,
                    usuario_id
                    )
                    VALUES
                    (
                    $odonto,
                    ".$_REQUEST[$i.$pfj].",
                    ".$ubicacion.",
                    $problema,
                    $producto,
                    ".$this->evolucion.",
                    ".$this->ingreso.",
                    now(),
                    $usuario
                    );";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                    }
                    $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
               }
               }
               if($this->frmError["MensajeError"]==NULL)
               {
                    $this->frmError["MensajeError"]="EL DIENTE '".$_REQUEST['tipoubicac'.$pfj]."' TIENE UN PROBLEMA DE DIENTE POR SUPERFICIE";
               }
          
               $_REQUEST['tipoubicac'.$pfj]='';
               $_REQUEST['tipoproble'.$pfj]='';
               $_REQUEST['tipoproduc'.$pfj]='';
               $dbconn->CommitTrans();
               $this->RegistrarSubmodulo($this->GetVersion());
               return true;
               }
          }//FIN ELSE DEL MENSAJE FALTAN DATOS OBLIGATORIOS
	}

	function BuscarDatos()
	{
		list($dbconn) = GetDBconn();
  		$query="SELECT A.hc_odontologia_evolucion_urgencias_detalle_id,
				A.hc_tipo_cuadrante_id,
				A.hc_tipo_ubicacion_diente_id,
				A.hc_tipo_problema_diente_id,
				A.hc_tipo_producto_diente_id,
		          B.descripcion AS des1,
                    C.descripcion AS des2,
                    D.descripcion AS des3,
                    C.sw_cariado,
                    C.sw_obturado,
                    C.sw_perdidos,
                    C.sw_sanos
			FROM hc_odontologia_evolucion_urgencias_detalle AS A,
     			hc_tipos_cuadrantes_dientes AS B,
                    hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D
			WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
			AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
			AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
               AND A.evolucion_id=".$this->evolucion."
			ORDER BY A.hc_tipo_ubicacion_diente_id ASC, C.sw_cariado DESC,
			C.sw_obturado DESC, C.sw_perdidos DESC, C.sw_sanos DESC;";
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

     function BuscarDatosObservacion()
     {
		list($dbconn) = GetDBconn();
          $query="SELECT A.descripcion, A.fecha_registro, B.nombre
          	   FROM hc_odontologia_evolucion_urgencias AS A, 
                       system_usuarios AS B
                  WHERE A.usuario_id=B.usuario_id AND evolucion_id=".$this->evolucion."";
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
 
  
	function InsertDatosObser()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
          if(empty($_REQUEST['observacio'.$this->frmPrefijo]))
          {
               $this->frmError["MensajeError"]="SIN OBSERVACIÓN";
               return true;
          }    
          $query="SELECT NEXTVAL ('hc_odontologia_evolucion_urge_hc_odontologia_evolucion_urge_seq');";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $dbconn->RollbackTrans();
               $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
               return true;
          }
          $odonto=$resulta->fields[0];
          $fecharegistro=date("Y-m-d");
          $usuario=UserGetUID();
          $query="INSERT INTO hc_odontologia_evolucion_urgencias
          (
               evolucion_id,
               descripcion,
               ingreso,
               fecha_registro,
               usuario_id
          )
          VALUES
          (
      		".$this->evolucion.",
			'".$_REQUEST['observacio'.$this->frmPrefijo]."',
      		".$this->ingreso.",
      		now(),
			$usuario
		);";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $dbconn->RollbackTrans();
               $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
               return true;
          }

		$dbconn->CommitTrans();
          $this->RegistrarSubmodulo($this->GetVersion());
          $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
		return true;
	}

	function EliminDatos()
	{
		$pfj=$this->frmPrefijo;
		$this->frmError["MensajeError"]="";
		list($dbconn) = GetDBconn();
		$query="DELETE FROM hc_odontologia_evolucion_urgencias_detalle
		WHERE hc_odontologia_evolucion_urgencias_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
			return true;
		}
	}

	function BuscarAccionPreventiva2()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.tipo_accion_id,
		A.nombre,
		B.hc_accion_preventiva_id,
		B.sw_accion_preventiva,
		B.descripcion
		FROM hc_tipos_accion_preventiva AS A,
		hc_accion_preventiva AS B
		WHERE A.tipo_accion_id=B.tipo_accion_id
		AND B.evolucion_id=".$this->evolucion."
		ORDER BY A.tipo_accion_id;";
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

	function BuscarHistoriaUrgenciasOdontologia()
	{
          list($dbconn) = GetDBconn();
          $query="SELECT A.hc_odontologia_evolucion_urgencias_detalle_id,
                         A.hc_tipo_cuadrante_id,
                         A.hc_tipo_ubicacion_diente_id,
                         A.hc_tipo_problema_diente_id,
                         A.hc_tipo_producto_diente_id,
                         B.descripcion AS des1,
                         C.descripcion AS des2,
                         D.descripcion AS des3,
                         C.sw_cariado,
                         C.sw_obturado,
                         C.sw_perdidos,
                         C.sw_sanos
                 FROM hc_odontologia_evolucion_urgencias_detalle AS A,
                      hc_tipos_cuadrantes_dientes AS B,
                      hc_tipos_problemas_dientes AS C,
                      hc_tipos_productos_dientes AS D
                 WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
                 AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                 AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
                 AND evolucion_id=".$this->evolucion."
                 ORDER BY A.hc_tipo_ubicacion_diente_id ASC, C.sw_cariado DESC,
                 C.sw_obturado DESC, C.sw_perdidos DESC, C.sw_sanos DESC;";
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

	function BuscarAccionPreventivaAnterior()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.tipo_accion_id,A.nombre, B.hc_accion_preventiva_id,
                         B.sw_accion_preventiva,B.descripcion, B.evolucion_id
                         FROM hc_tipos_accion_preventiva AS A,
                         hc_accion_preventiva AS B
                         WHERE B.tipo_id_paciente='".$this->tipoidpaciente."'
                         AND B.paciente_id='".$this->paciente."'
                         AND A.tipo_accion_id=B.tipo_accion_id
                         ORDER BY A.tipo_accion_id;";
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

}
?>
