<?php

/**
* Submodulo de NotasAnestesiologia.
*
* Submodulo para manejar los suministros de las anestesias a los pacientes.
* @author Tizziano Perea <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_NotasAnestesiologia.php,v 1.5 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* NotasAnestesiologia
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de NotasAnestesiologia.
*/

class NotasAnestesiologia extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $limit;
	var $conteo;


	function NotasAnestesiologia()
	{
		$this->limit=5;
		$this->salida = '';
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'TIZZIANO PEREA OCORO',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT count(*)
			   FROM hc_descripcion_cirugia
                  WHERE evolucion_id=".$this->evolucion.";";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		
		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
		 	return true;
		}
	}


/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma()
	{
	    if($this->tipo_profesional == 1 OR $this->tipo_profesional == 2)
		{
			$pfj=$this->frmPrefijo;
			if(empty($_REQUEST['accion'.$pfj]))
			{
				$this->frmForma();
			}
			else
			{
				if($this->InsertDatos()==true)
				{
					$this->frmForma();
				}

				if($_REQUEST['accion'.$pfj]=='ListadoNotasE')
				{
					$this-> frmForma();
				}
			}
		}
		else
		{
			$this->GetConsulta();
		}
		return $this->salida;
	}

/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetConsulta()
	{
        if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
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


	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}

     /**
     * Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
     * @return array
     */
     function TiposGasesAnestesicos($tipoGas)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT  tipo_gas_id,descripcion
          FROM tipos_gases WHERE clasificacion_gas='$tipoGas' ";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
	     if($result->RecordCount()){
                    while(!$result->EOF){
                         $vars[$result->fields[0]]=$result->fields[1];
                         $result->MoveNext();
                    }
               }
          }
          $result->Close();
          return $vars;
     }
     
     
     /**
     * Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
     * @return array
     */
	function TiposDeAnestesias()
     {
		list($dbconn) = GetDBconn();
		$query = "SELECT qx_tipo_anestesia_id,descripcion,sw_uso_gases
		FROM qx_tipos_anestesia";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()){
        while (!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
 		return $vars;
	}

     
     function ReconocerProfesional($user)
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;           
     	$sql="SELECT usuario, nombre
                FROM system_usuarios
                WHERE usuario_id = ".$user.";";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;		
          if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$profesional = $result->FetchRow();
          return $profesional;
	}


     /**
     * Esta función inserta los datos del submodulo.
     *
     * @access private
     * @return boolean Informa si lo logro o no.
     */
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		
          if($_REQUEST['TipoAnestesia']!=-1 && !empty($_REQUEST['TipoAnestesia']))
          {
          	$TIPO_ANESTESIA = $_REQUEST['TipoAnestesia'];
               
               $anestesia = explode('/',$TIPO_ANESTESIA);
               $TIPO_ANESTESIA = $anestesia[0];
			
               if($_REQUEST['nogas']=='1')
               { $NO_GAS = '1'; }else
               { $NO_GAS = '0'; }
		}
          
          if($_REQUEST['gasAnestesico']!=-1 && !empty($_REQUEST['gasAnestesico']))
          {
          	$GAS_ANESTESICO = $_REQUEST['gasAnestesico'];
			$GAS_ANESTESICO =  "'".$GAS_ANESTESICO."'";        
           }else
           { $GAS_ANESTESICO = "NULL"; }
                    
          if($_REQUEST['gasAnestesicoMe']!=-1 && !empty($_REQUEST['gasAnestesicoMe']))
          { 
          	$GAS_ANESTESICO_ME = $_REQUEST['gasAnestesicoMe'];
               $GAS_ANESTESICO_ME =  "'".$GAS_ANESTESICO_ME."'";
          }else
          { $GAS_ANESTESICO_ME = "NULL"; }
          
		if(!empty($_REQUEST['DuracionGas']))
          { $DURACION_GAS = $_REQUEST['DuracionGas']; }
          
          if($NO_GAS == '1')
          {
          	if((empty($GAS_ANESTESICO) AND empty($GAS_ANESTESICO_ME)) OR empty($DURACION_GAS))
               {
				$this->frmError["MensajeError"] = "DEBE SELECCIONAR EL TIPO DE GAS A SUMINISTRAR Y LOS MINUTOS DE DURACION.";
				$this->frmForma();
				return true;
               }
          }
          
          list($dbconn) = GetDBconn();

          $resultado = $dbconn->Execute("SELECT nextval('public.hc_notasanestesiologia_nota_anestesia_id_seq'::text)");
          list($anestesia_id) = $resultado->FetchRow();
                  
          $insert ="INSERT INTO hc_notasanestesiologia (nota_anestesia_id,
          									 ingreso,
          									 evolucion_id,
                                                        usuario_id,
                                                        qx_tipo_anestesia_id,
                                                        sw_gas,
                                                        fecha_registro)
										VALUES (".$anestesia_id.",
                                                  	   ".$this->ingreso.",
                                                  	   ".$this->evolucion.",
                                                          ".UserGetUID().",
                                                          '$TIPO_ANESTESIA',
                                                          '$NO_GAS',
                                                          now());";
          
          $result=$dbconn->Execute($insert);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar las notas de Anestesia.";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          if ($NO_GAS == '1')
          {
               $insert_det ="INSERT INTO hc_notasanestesiologia_detalle 
               						(nota_anestesia_id,
                                              ingreso,
                                              evolucion_id,
                                              gas_anestesico,
                                              gas_medicinal,
                                              minutos_suministro)
                                     VALUES (".$anestesia_id.",
                                     		".$this->ingreso.",
                                             ".$this->evolucion.",
                                             $GAS_ANESTESICO,
                                             $GAS_ANESTESICO_ME,
                                             '$DURACION_GAS');";
               $result=$dbconn->Execute($insert_det);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar las notas de Cirugia";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          }
		 $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
	}


	function Detalle_anestesiologia()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $query = "SELECT A.nota_anestesia_id, A.evolucion_id, A.usuario_id, A.sw_gas, A.fecha_registro, 
          			  B.descripcion AS tipo_anestesia, 
                           (SELECT SUB.descripcion 
                            FROM tipos_gases AS SUB, hc_notasanestesiologia_detalle AS B 
                            WHERE SUB.tipo_gas_id = B.gas_anestesico 
                            AND B.nota_anestesia_id = A.nota_anestesia_id) AS gas_anestecia, 
                           (SELECT SUB.descripcion FROM tipos_gases AS SUB, hc_notasanestesiologia_detalle AS B 
                            WHERE SUB.tipo_gas_id = B.gas_medicinal
                            AND B.nota_anestesia_id = A.nota_anestesia_id) AS gas_medico, 
                           (SELECT minutos_suministro FROM hc_notasanestesiologia_detalle 
                            WHERE nota_anestesia_id = A.nota_anestesia_id) AS minutos_suministro
                    FROM hc_notasanestesiologia AS A, qx_tipos_anestesia AS B
                    WHERE A.ingreso = ".$this->ingreso."
                    AND A.qx_tipo_anestesia_id = B.qx_tipo_anestesia_id
                    ORDER BY A.fecha_registro DESC;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;		
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     	while($data = $result->FetchRow())
          {
          	$datos[] = $data;
          }
          return $datos;
	}

}
?>
