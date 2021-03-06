<?
/**
 * $Id: Vitros.class.php,v 1.31 2006/01/26 22:26:07 mauricio Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Clases que maneja la informacion enviada por la vitros al WebService de 
 * la Vitros
 * @author    Mauricio Bejarano L. <maurobej@hotmail.com>
 * @version   $Revision: 1.31 $
 * @package   Vitros
 */


	class Vitros{
		function Vitros(){
		}
		/**
		* Graba fisicamente el archivo retornado por la vitros, para ser analizado 
		* y actualizado en la base de datos
		* @param string file 		Nombre del archivo generado por la Vitros
		* @param string cadena	cadena de resultados generado por la vitros para el archivo file
		* @access private
		*/
		function ProcesaResultadoVitros($file,$cadena){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.$file;
			if(!$fichero = fopen($nombre_archivo,'w')){
				return false;
			}
			fclose($fichero);
			$fichero = fopen($nombre_archivo,'a');
			$res=fputs($fichero,"$cadena");
			fclose($fichero);
			if(!$res){
				return false;
			}else{
				$res_cad=$this->ManipulaCadena($file);
				$this->RegistroErrorVitros("ProcesaResultadoVitros",$file,$res_cad);
				if(!$res_cad){
					return false;
				}else{
					//elimino archivo
// 					if(unlink($nombre_archivo)){
// 						$file2="S".substr($file,1);
// 						$nombre_archivo=$path.$file2;
// 						if(!unlink($nombre_archivo)){
// 							return false;
// 						}
// 					}else{
// 						return false;
// 					}
				}
			}
			return true;
		}//fin function ResultadoVitros
		
		/**
		*	metodo encargado de verificar si es un error Vitros y de acuerdo a eso tomer 
		* una desicion.
		*
		* Codigos de error manejados en la aplicacion
		* 0000 al 0015 son errores generados por el analizador Vitros
		* 0050 al 0059 son erroes generados por la aplicacion o el driver de comunicacion asi:
		* 0050	Estado ok del examen se genero y envio bien
		* 0051	WebService no puede crear el archivo
		* 0052	Archivo sin cuerpo, vacio
		* 0053	Vitros desconectado
		* 0054	Error al crear archivo en SIIs, Revizar permisos
		* 0055	Kermit recibe el archivo pero no lo puede enviar a la Vitros. Error de lectura
		* 0056	Kermit no puede enviar archivo a la Vitros. Esta relacionado con el error 0053
		* 0057	Error de comunicacion con el Webservice
		* 0058	Archivo malo. No puede ser interpretado
		* 0059	Archivo de respuesta no existe en ela bse de datos
		* @param string error		Error generado por la vitros enviado a travez del WebService
		* @param string file 		Nombre del archivo generado por la Vitros
		* @access private
		*/
		function ProcesaErrorVitros($error,$file){
				$res='';
				
				$min_E_vitros=ModuloGetVar('app','Os_ListaTrabajoVitros','min_E_vitros');
				$max_E_vitros=ModuloGetVar('app','Os_ListaTrabajoVitros','max_E_vitros');
				$min_E_trans=ModuloGetVar('app','Os_ListaTrabajoVitros','min_E_trans');
				$max_E_trans=ModuloGetVar('app','Os_ListaTrabajoVitros','max_E_trans');
				
		
			//if($res>=0){
			if( (((int)$error>=(int)$min_E_vitros)&&((int)$error<=(int)$max_E_vitros)) || 
					(((int)$error>=(int)$min_E_trans)&&((int)$error<=(int)$max_E_trans)) ) {
				$res=$this-> ActualizaErrorVitros($error,$file);
			}else{
				$res='no_entro';
			}
			
			$this->RegistroErrorVitros($error,$file,$res);
			
			return $res;
			
		}//fin function ProcesaErrorVitros
		
		/**
		* Metodo de pruebas, registra los errores generados
		*/
		function RegistroErrorVitros($error,$file,$res){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'error.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				$this->frmError["MensajeError"]='NO SE PUDO CREAR EL ARCHIVO.VERIFIQUE PERMISOS';
				return 'errorF';
			}
			$fecha=Date('Y-m-d');
			$cadena="fecha-> ".$fecha." error-> ".$error." file-> ".$file ." res-> ".$res."\n";
			fputs($fichero,$cadena);
			fclose($fichero);
		}
		/**
		*	Procesa los errores generados por el WebService o por el kermit y decide que hacer.
		* @param string error		Error generado enviado a travez del WebService
		* @param string file 		Nombre del archivo que esta generando el error
		* @access private
		*/
		function ProcesaErrorGenerado($error,$file){
			switch($error){
				case '1000':{//reenviar archivo
											$this->ReenviaArchivo($file);
							break;}
				case '':{break;}
			}//fin switch
			return true;
		}//fin function ProcesaError
		
		/**
		*	Reenvia el archivo a la Vitros, cuando se presenta un error en la transmicion del mismo
		* y se pide su reenvio
		* @param string file 	Nombre del archivo que debe ser reenviado	
		* @access private
		*/
		function ReenviaArchivo($file){
			$res='ok';
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.$file;
			if(!$fichero = fopen($nombre_archivo,'r')){
				//$this->error = "Error Vitros";
				//$this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO.';
				//echo "error";
				return false;
			}
			//fclose($fichero);
			//$fichero = fopen($nombre_archivo,'a');
			$linea=0;
			$cadena=null;
			while(!feof($fichero)){
				$linea++;
				$buffer=fgets($fichero,4096);
				$cadena.=$buffer;
			}//fin while
			
			fclose($fichero);
			$res=$this-> ConectaWs($file,$cadena);
			//$res=$this-> ConectaWs('','');
			//echo "<br>res->".$res;
			if($res=='ok'){
				$this->ActualizaEstadoOs($file, 2);
			}elseif($res=='error'){
				$this->ActualizaEstadoOs($file, 5);
			}
			return true;
		}//fin function ReenviaArchivo
		

		/**
	* Actualiza el estado de los examenes en interface_vitros_control_examen_detalle
	* @param $file		archivo que debe ser modificado
	* @param $estado	estado que debe terner la muestra. Se buscar por el nombre del archivo
	*/
	function ActualizaEstadoOs($file, $estado){
		$file=substr($file,1);//quito la S
		$file=(int)$file;
		$error='0050';
		if($estado=='5'){$error='0057';}
		list($dbconn) = GetDBconn();
			$query="UPDATE interface_vitros_control_examen_detalle
							SET sw_estado_examen = $estado,
									error_vitros = '$error'
							WHERE interface_vitros_control_examen.nombre_archivo = $file AND
							interface_vitros_control_examen.muestra_id = interface_vitros_control_examen_detalle.muestra_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al actualizar en ActualizaEstadoOs";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
		$result->Close();
		return true;
	}
		
		/**
		*	Reenvia el archivo a traves del WebService de SIIS Vitros
		* @param string file 		Nombre del archivo que debe ser enviado por el webservice
		* @param string cadena	cadena con examenes por realizar
		* @access private
		*/
		function ConectaWs($file,$cadena){

			$path=GetVarConfigAplication('DIR_SIIS')."classes/nusoap/lib/";
			require_once($path."nusoap.php"); 

			$ip=ModuloGetVar('app','Os_ListaTrabajoVitros','IP');
			$puerto=ModuloGetVar('app','Os_ListaTrabajoVitros','PUERTO');
			$proy_name=ModuloGetVar('app','Os_ListaTrabajoVitros','PROYECTO_NAME');
			$wsdl=ModuloGetVar('app','Os_ListaTrabajoVitros','JWS');
			$metodo=ModuloGetVar('app','Os_ListaTrabajoVitros','WEB_SERVICE_NAME_VITROS');
			
			$wsdl="http://".$ip.":".$puerto."/".$proy_name."/".$wsdl."?wsdl";
		$this->ErrorSistema("wsdl-->".$wsdl);
			$client=new soapclient($wsdl, true);
			
			if ($sError = $client->getError()) {
				$this->ErrorSistema("error 1--> Error En la Coneccion  con el WebService [" . $sError . "]");
				return 'error';break;
			} 
			
			/**Cali*/
			$param=array('validacion'=>'Guardar','nombre'=>$file,'cuerpo'=>$cadena);
	
			/**Tulua*/
			//$param=array('in0'=>'Guardar','in1'=>$file,'in2'=>$cadena);
			
			//$respuesta= $client->call("webS", $param);
			$respuesta= $client->call($metodo, $param);
			//echo $client->getDebug(); 
				//$this->ErrorSistema("debug-->".$client->getDebug());
			if ($client->fault){ // Si
						$this->ErrorSistema("error 2--> Error en la Comunicacion con el WebService");
					return 'error';break;
			}
			else{ // No
						$sError = $client->getError();
						// Hay algun error ?
						if ($sError) { // Si
							//echo 'Error :' . $sError;
							return 'error';break;
						}
			}
			return 'ok';
		}//fin function ConectaWs
		
		/**
		* Recive el archivo generado por la vitros y lo separa para ser aptualizado en la Base de Datos
		*
		* Se crea un vector en el que se guarda la informacion enviada por la  vitros, en la que en cada
		* posicion contiene informacion con datos basicos de la muestra y con datos de los resultados
		* del analisis. Para lo cual se debe tener en cuenta:
		* 
		* Cada linea corresponde al examen ubicado en una copa de cada bandeja del equipo, y de cada
		* linea se sacan datos basicos y datos de resultados organizados asi
		* Letra de identificacion de dato + informacion del dato:
		*
		* 'X' + id de la muestra en la tabla interface_vitros_control_examen
		* 'Y' + tipo de fluido que contiene la copa
		* 'Z' + factor de dilucion de la muestra en la copa
		* 'id de la prueba' + resultado generado por la vitros a la muestra. (Sobre una misma muestra se pueden realizar 
		*     hasta un maximo de 30 pruebas)
		* @param string file 		Nombre del archivo generado por la Vitros
		* @access private
		*/
		function ManipulaCadena($file){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.$file;
			if(!$fichero = fopen($nombre_archivo,'r')){
				$this->error = "Error Vitros";
				$this->mensajeDeError = 'NO SE PUDO ABRIR EL ARCHIVO.';
				//echo ERROR;
				return false;
			}
			$cadena='';
			while(!feof($fichero)){
				$buffer=fgets($fichero,4096);//lee una linea
				$id='X'.substr($buffer,25,15).'}';//id
				$fluido='Y'.substr($buffer,40,1).'}';//+ fluido
				$dilucion='Z'.substr($buffer,44,5).'}';//+ dilucion
				$buffer=substr($buffer,49);//quito comienzo
				$buffer=substr($buffer,0,-13);//quito final
				$cadena.=$id.$fluido.$dilucion.$buffer;
			}//fin while
			fclose($fichero);
			//echo "<br>".$cadena."<br>";
			//%%ojo con esta instruccion 
			//examinar el momento en que aparecen varios enter al final de l acadena de repuesta
			//cuando esto pasa se necesita la siguiente instruccion
			//$cadena=substr($cadena,0,-13);//quito ultima parte de la cadena  'x}y}x}x}y}z}}'
			$this->RegistroErrorVitros("ManipulaCadena","cadena de trabajo",$cadena);
			$cadena=explode('}',$cadena);
			$res=$this->OrganizaDatosEnBD($cadena);
			//%% se quita
			$this->RegistroErrorVitros("ManipulaCadena",$file,$res);
			if(!$res){
				return false;
			}else{
				return true; 
			}
		}//fin function ManipulaCadena
		
		/**
		* Actualiza los datos en la base de datos de Vitros
		* @param array cadena Vector con la informacion de resultados generados por la Vitros
		* @return null
		*/
		function OrganizaDatosEnBD($cadena){
		//print_r($cadena);
		$id='';
		$fluido='';
		$dilucion='';
// 		echo "<br>->";
// 		print_r($cadena);
			for($i=0;$i<sizeof($cadena);$i++){
				$sw=0;
				//echo "<br>".substr($cadena[$i],0,1)."-".substr($cadena[$i],1);
				$j=$i;
				if(substr($cadena[$i],0,1)=='X'){
					$id_muestra=substr($cadena[$j],1);
					$id_muestra=trim($id_muestra);
					if(substr($id_muestra,-1)=='Z'){
						$id_muestra=substr($id_muestra,0,-1);
					}
					$fluido=substr($cadena[$j+1],1);
					$dilucion=substr($cadena[$j+2],1);
					$j=$j+2;
					//echo "<br>id:".$id_control." fl:".$fluido." duil:".$dilucion;
					$sw=1;
				}//fin if
				if($sw==0){
					$cod_vitros=substr($cadena[$i],0,1);//caract 0
					$respuesta_muestra=substr($cadena[$i],1,9);//caract 1-9
					$unidad_id=substr($cadena[$i],10,1);//caract 10
					if((ord($cod_vitros)>=96)&&(ord($cod_vitros)<=111)){
						$error_derivado_id=substr($cadena[$i],11,1);//caract 11
					}else{
						$error_medidas_id=substr($cadena[$i],11,1);//caract 11
					}
					$advertencia_id=substr($cadena[$i],12,1);//caract 12
					$error_id=substr($cadena[$i],13,8);//caract 13- 22
					//echo "<br>OrganizaDatosEnBD<br>";
					$id_muestra=trim($id_muestra);
					$cod_vitros=trim($cod_vitros);
					 $prueba_datos="id_muestra>".$id_muestra." cod_vitros:".$cod_vitros."->".ord($cod_vitros)." res:".$respuesta_muestra." uni:".$unidad_id." eMed:".$error_medidas_id." eDer".$error_derivado_id." adv:".$advertencia_id." err:".$error_id;
						$this->RegistroErrorVitros("OrganizaDatosBD",$file,$prueba_datos);
					$res=$this->ActualizaDatos($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																	$error_derivado_id,$advertencia_id,$error_id);
					//se reinician las variables
					//echo"<br>->";print_r($prueba_datos);
					$cod_vitros=$respuesta_muestra=$unidad_id=$error_derivado_id=$error_medidas_id=$advertencia_id=$error_id='';
				}
				$i=$j;
			}//fin for
			return $res;
		}//fin function OrganizaDatosEnBD
		
		/**
		* Se encarga de verificar que el examen ya este firmado para poder ser borrado de la 
		* base datos
		*/
		function VerificaFirma($resultado_id){
			list($dbconn) = GetDBconn();
			$query="SELECT usuario_id_profesional_autoriza
							FROM		hc_resultados_sistema
							WHERE resultado_id = $resultado_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error consulta firma en hc_resultados_sistema";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("firma_hc_resultados_sistema",$query,"ERROOOOORRRRR");
					return false;
			}else{
				$firma=$result->fields[0];
				return $firma;
			}
		}
		
		/**
		*	%%verificar si se necesita
		* consulta el resulatado de un determinado examen
		*/
		function ConsultaResultadoHc($resultado_id){
			list($dbconn) = GetDBconn();
			$query="SELECT 	resultado
							FROM		hc_apoyod_resultados_detalles
							WHERE 	resultado_id = $resultado_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error consulta resultado en hc_apoyod_resultados_detalles";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("firma_hc_resultados_sistema",$query,"ERROOOOORRRRR");
					return false;
			}else{
				$res=$result->fields[0];
				return $res;
			}
		
		}
		/**
		* Actualiza en la base de datos un resultado generado por la vitros. hace un analisis del
		* resultado enviado y determina que medoto debe ser llamado para actualizar o insertar
		* los resultados
		*/
		function ActualizaDatos($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																	$error_derivado_id,$advertencia_id,$error_id){
			$this->RegistroErrorVitros("ActualizaDatos",'-->','Entro');
			$nueva_respuesta='vacio';
			$nueva_respuesta=$this->ModificaResultadoCasoEspecial($id_muestra,$cod_vitros,$respuesta_muestra);
			$no_result = '';
			$error_medidas= '';
			$error_derivado= '';
			$error_advertencia= '';
			$error_muestra = '';
			//Errores y advertencias enviados de la vitros
			if(($error_medidas_id != '0')&&(!empty($error_medidas_id)))
			{
				$error_medidas = "\n Error en Medidas: ";
				$error_medidas .= $this->ConsultaErrorMedida($error_medidas_id);
			}
			if(($error_derivado_id != '0')&&(!empty($error_derivado_id)))
			{
				$error_derivado = "\n Error Derivado: ";
				$error_derivado .= $this->ConsultaErrorDerivado($error_derivado_id);
			}
			if(($advertencia_id != '0')&&(!empty($advertencia_id)))
			{
				$error_advertencia = "\n Error Advertencia: ";
				$error_advertencia .= $this->ConsultaErrorAdvertencia($advertencia_id);
			}
			if(!empty($error_id))
			{
				$error_muestra = "\n Error Generado: ";
				$error_muestra .= $this->ConsultaErrorMuestra($error_id);
			}
			if($nueva_respuesta!='vacio')
			{
				$respuesta_muestra=$nueva_respuesta;
			}
			//cuando no llega resultado de la vitros por error
			if ($respuesta_muestra == 'NO RESULT')
			{
				$respuesta_muestra = 0;
				$no_result = "Respuesta: NO RESULT";
			}
			//comcatenamos todos los errores y advertencias sacados por la vitros
			$no_result .= $error_medidas . $error_derivado . $error_advertencia . $error_muestra;
			$this->RegistroErrorVitros("ActualizaDatos",'nueva res-->',$respuesta_muestra);
			$respuesta_muestra=trim($respuesta_muestra);
			$res=$this->VerificaResultado($id_muestra,$cod_vitros);
			
			if($cod_vitros == "'")
			{
				$codigo_vitros=addslashes($codigo_vitros);
			}
			$this->ControlResultados("VerificaResultado->".$res[0]);
			$this->RegistroErrorVitros("ActualizaDatos",$id_muestra,$res[0]);
			if($res[0]=='inserta')
				{
					$idv=$this->InsertaDatosVitros($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																			$error_derivado_id,$advertencia_id,$error_id,$res[1]);
						if($idv)
						{
							if($this->InsertaHcResultados($cod_vitros,$id_muestra,$res[1],$no_result)){
							return true;
							}else{return false;}
						}
						else
						{
							return false;
						}
				}elseif($res[0]=='modifica')
				{
					$firma=$this->VerificaFirma($res[1]);
					//se puede modificar si no esta firmado
					if(($firma==NULL)||(empty($firma))) 
					{
						$idv=$this->InsertaDatosVitros($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																			$error_derivado_id,$advertencia_id,$error_id,$res[1]);
						if($idv)
						{
							$this->ActualizaHcResultados($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$res[1],$no_result);
 							return true;
						}
						else
						{
							return false;
						}
					}
					else
					{//esta firmado
						return false;
					}
				}
				else{
					$this->RegistroErrorVitros("actualizaDatos","error","No esta en la BD");
					return false;//%%verificar
				}
				
		}//fin ActualizaDatos
		
		/**
		* Dada la muestra y el codigo vitros de la misma, determina si el resultados
		* debe ser actualizado o insertado en la base de datos. Lo hace tambien
		* para los datos normales o los compuestos.
		* Un examen compuesto, es aqui que con un mismo cargo se pueden generar 
		* varios subexamenes, ejemplo glucosa 1a, 2a, 3a y 4a hora.
		* @param $id_muestra
		* @param $codigo_vitros
		*/
			function VerificaResultado($id_muestra,$codigo_vitros){
			$id_muestra=trim($id_muestra);
			$id_control=trim($id_control);
			$muestra=explode("-",$id_muestra);
			$m=$muestra[0]."-".$muestra[1];//quite los tres ultimos caracteres
			if(empty($muestra[2]))
			{
				$consul_id_muestra = " muestra_id = '$id_muestra' ";
			}
			else
			{//caso espesial
				$consul_id_muestra = " muestra_id like '$m-G%' ";
			}
			//print_r($muestra);
			list($dbconn) = GetDBconn();
			//$res_id=$this->ConsultaResultado_id($id_muestra,$codigo_vitros);
			if($codigo_vitros == "'")
			{
				$codigo_vitros=addslashes($codigo_vitros);
			}
			$query=
						"	SELECT	DISTINCT codigo_cups
							FROM		interface_vitros_control_examen_detalle
							WHERE		muestra_id = '$id_muestra'
											AND codigo_vitros= '$codigo_vitros'
						";
			$this->ControlQuery("VerificaResultado-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al contar numero de orden en VerificaResultado";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->ErrorQuery("VerificaResultado".$query);
					return false;
			}
			$cod_cups=$result->fields[0];
			$this->ControlQuery("VerificaResultado--> cups-> ".$cod_cups);
			$query=
						"	SELECT	COUNT(numero_orden_id)
							FROM		interface_vitros_control_examen_detalle
							WHERE		$consul_id_muestra
											AND codigo_cups = '".$cod_cups."'
						";
			$this->ControlQuery("VerificaResultado-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al contar numero de orden en VerificaResultado";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->ErrorQuery("VerificaResultado".$query);
					return false;
			}
			$cant=$result->fields[0];
			if($cant == 1)
			{
				$query=
								"SELECT	resultado_id
								FROM		interface_vitros_control_examen_detalle
								WHERE		$consul_id_muestra
												AND codigo_vitros= '$codigo_vitros'  
												
							";
				$this->ControlQuery("VerificaResultado-> ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al resultado_id1";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->ErrorQuery("VerificaResultado".$query);
						return false;
				}
			}
			elseif($cant > 1)
			{
				$query=
				"SELECT	DISTINCT resultado_id
								FROM		interface_vitros_control_examen_detalle
								WHERE		$consul_id_muestra
												AND codigo_cups = '".$cod_cups."'
												AND resultado_id IS NOT NULL
							";
				$this->ControlQuery("VerificaResultado-> ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al resultado_id2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->ErrorQuery("VerificaResultado".$query);
						return false;
				}
			
			}
			else
			{
				return false;
			}
			$resultado_id=$result->fields[0];
			
			if($resultado_id==NULL){
			//if($resultado_id!=$res_id){
				$query="SELECT NEXTVAL ('hc_resultados_resultado_id_seq')";
				$this->ControlQuery("VerificaResultado-> ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->ErrorQuery("ConsultaResultado_id-nextval(resultado_id) ".$query);
						return false;
				}else{
					$res_id=$result->fields[0];
					$_SESSION['LTATRABAJO']['nuevo_resultado_id']='resultado_id_nuevo';
				}
				$res[0]='inserta';
				$res[1]=$res_id;
			}else{
				$res[0]='modifica';
				$res[1]=$resultado_id;
				//esto es para el caso especial de la glucosa
				//
				if(!empty($muestra[0]))
				{
					$query=
									"SELECT	resultado_id
									FROM		interface_vitros_control_examen_detalle
									WHERE		muestra_id = '$id_muestra'
													AND codigo_vitros= '$codigo_vitros'  
								";
					$this->ControlQuery("VerificaResultado-> ".$query);
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al resultado_id1";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->ErrorQuery("VerificaResultado".$query);
							return false;
					}
					else
					{
						$res_id_espe=$result->fields[0];
						if($res_id_espe==NULL)
						{
							$res[0]='inserta';
							$res[1]=$resultado_id;
						}
					}
				}
			}
				
			
			$result->Close();
			return $res;
		}//fin VerificaResultado

		
		/**
		*%%borrar%
		* Se encarga de insertar un resultado compuesto por primera vez
		* @param $resultado_id
		* @param $lab_examen_id
		* @param $cod_vitros
		* @param $id_muestra
		*/
		function InsertaCompuesto($resultado_id,$lab_examen_id,$cod_vitros,$id_muestra){
			$var=$this->ConsultaResultadosVitrosBD($cod_vitros,$id_muestra);//cod_vitros,id_muestra
			$alerta='0';
			if(($var[rango_max]!='0')&&($var[respuesta_muestra]<$var[rango_min])&&($var[respuesta_muestra]>$var[rango_max])){
				$alerta='1';}
			if($cod_vitros == "'")
			{
				$cod_vitros=addslashes($cod_vitros);
			}
			$paciente_id=trim($var[paciente_id]);
			$tipo_id_paciente=trim($var[tipo_id_paciente]);
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="INSERT INTO hc_apoyod_resultados_detalles(
													lab_examen_id,
													resultado_id,
													sw_alerta,
													resultado,
													rango_min,
													rango_max,
													unidades,
													cargo,
													tecnica_id)
										VALUES('$lab_examen_id',
													'$resultado_id',
													'$alerta',
													'$var[respuesta_muestra]',
													'$var[rango_min]',
													'$var[rango_max]',
													'$var[unidades]',
													'$var[codigo_cups]',
													'$var[tecnica_id]')";

$query2=$query;
		$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
		$nombre_archivo=$path.'recibido.txt';
		if(!$fichero = fopen($nombre_archivo,'a')){
			return 'errorF';
		}
		fputs($fichero,"query2-> ".$query2."\n");
		fclose($fichero);
		
		
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD hc_apoyod_resultados_detalles" . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("Insertacompuesto","query2","");
					$dbconn->RollbackTrans();
					return false;
			}

						$query="UPDATE interface_vitros_control_examen_detalle
							SET			resultado_id = $resultado_id
							WHERE		numero_orden_id = $var[numero_orden_id] AND
											orden_servicio_id = '$var[orden_servicio_id]' AND
											muestra_id = '$id_muestra' AND
											codigo_vitros = '$cod_vitros'";
											//$id_muestra,$id_control==>cod_vitros,id_muestra
			$query4=$query;
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'recibido.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,"query4-> ".$query4."\n");
			fclose($fichero);
												
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						//echo "<br>Error BD " . $dbconn->ErrorMsg();
						$this->RegistroErrorVitros("insertacompuesto","query4","");
						$dbconn->RollbackTrans();
						return false;
				}

			
			
			
						
			$dbconn->CommitTrans();
			$result->Close();
		}//fin InsertaCompuesto
				

		
		
		/**
		* Se encarga de eliminar un registro de la base de datos cuando el usuario la elimina 
		* Solo los puede eliminar si no los a enviado a la vitros
		* @param $id_muestra
		* @param $id_control
		*/
		function EliminaRegistroVitros($id_muestra,$id_control){
			$id_muestra=trim($id_muestra);
			$id_control=trim($id_control);
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="DELETE FROM  interface_vitros_control_examen
						WHERE interface_vitros_control_examen_id = '$control_id' AND
									muestra_id = '$id_muestra'
						";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al eliminar en interface_vitros_control";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
			$dbconn->CommitTrans();
			$dbconn-->Close();
		}//EliminaRegistroVitros
		
		/**
		*%%borrar%
		* Actualiza los la BD_Vitros con la informacion generada por la vitros en su archivo de respuesta
		* @param ($id_control,$id_muestra,$respuesta_muestra,$unidad_id,$error_medidas_id,$error_derivado_id,$advertencia_id,$error_id
		* parametros en la base de datos vitros
		*/
		function InsertaDatosVitros($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																	$error_derivado_id,$advertencia_id,$error_id,$res_id){
			list($dbconn) = GetDBconn();
			$id_muestra=trim($id_muestra);
			$cod_vitros=trim($cod_vitros);
			if($cod_vitros == "'")
			{
				$cod_vitros=addslashes($cod_vitros);
			}
			$respuesta_muestra=trim($respuesta_muestra);
			if(empty($respuesta_muestra)){
				$respuesta_muestra='0';
			}
			$query="UPDATE 	interface_vitros_control_examen_detalle
							SET			respuesta_muestra=$respuesta_muestra,
											unidad_id='$unidad_id',
											error_medidas_id='$error_medidas_id',
											error_derivado_id='$error_derivado_id',
											advertencia_id='$advertencia_id',
											error_id='$error_id',
											sw_estado_examen='3',
											resultado_id = $res_id
							WHERE		codigo_vitros='$cod_vitros' AND
											muestra_id = '$id_muestra' 
			";
			/*
			
											AND sw_estado_examen = '2'
			*/
			$this->ControlQuery("InsertaDatosVitros-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD" . $dbconn->ErrorMsg();
					$this->ErrorQuery("InsertaDatosVitros".$query);
					return false;
			}
			$result->Close();
			return true;
		}//fin function InsertaDatosVitros
		
		
		/**
		*%%borrar%
		* Este metodo ya no se esta utilizando se dejo en control de actualizaciones a otro metodo
		*
		* Actualiza los la BD_Vitros con la informacion generada por la vitros en su archivo de respuesta
		* @param ($id_control,$id_muestra,$respuesta_muestra,$unidad_id,$error_medidas_id,$error_derivado_id,$advertencia_id,$error_id
		* parametros en la base de datos vitros
		*/
		function ActualizaDatosVitros($id_control,$id_muestra,$respuesta_muestra,$unidad_id,$error_medidas_id,
																	$error_derivado_id,$advertencia_id,$error_id,$resultado_id){
			list($dbconn) = GetDBconn();
			//$error_id_mod=substr($error_id,1,2);//leer los dos primeros caracteres los otros 6 quedan pendientes
			$id_muestra=trim($id_muestra);
			$id_control=trim($id_control);
			$respuesta_muestra=trim($respuesta_muestra);
			$query="UPDATE 	interface_vitros_control_examen_detalle
							SET			respuesta_muestra=$respuesta_muestra,
											unidad_id='$unidad_id',
											error_medidas_id='$error_medidas_id',
											error_derivado_id='$error_derivado_id',
											advertencia_id='$advertencia_id',
											error_id='$error_id',
											sw_estado_examen='3'
							WHERE		muestra_id ='$id_control' AND
											codigo_vitros = '$id_muestra' 
			";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD" . $dbconn->ErrorMsg();
					return false;
			}
			$result->Close();
			
			//%%esto es de prubas se quita
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'recibido.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,"query-> ".$query."\n");
			fclose($fichero);
			
			return true;
		}//fin function ActualizaDatosVitros
		
		/**
		* Se encarga de consultar los resultados de los examenes en la Base de datos Vitros
		*/
		function ConsultaResultadosVitrosBD($codigo_vitros,$id_muestra){
			list($dbconn) = GetDBconn();
			if($codigo_vitros == "'")
			{
				$codigo_vitros=addslashes($codigo_vitros);
			}
			$id_muestra=trim($id_muestra);
			$muestra=explode("-",$id_muestra);
			//print_r($muestra);
			if(!empty($muestra[2])){
				$vitros_cargo="interface_vitros_cargo_especial c,";
				$plantilla_cargo="c.codigo_cups_real = d.cargo AND b.codigo_cups = c.codigo_cups_real";
			}else{
				$vitros_cargo="interface_vitros_cargo c,";
				$plantilla_cargo="c.codigo_cups = d.cargo";
			}
			$query="SELECT	a.fecha,
											a.usuario_id,
											a.tipo_id_paciente,
											a.paciente_id,
											b.codigo_cups,
											c.tecnica_id,
											c.lab_examen_id,
											b.numero_orden_id,
											b.respuesta_muestra,
											b.unidad_id,
											d.rango_min,
											d.rango_max,
											d.unidades,
											a.nombre_archivo,
											a.numero_cumplimiento,
											a.fecha_cumplimiento,
											b.orden_servicio_id
							FROM		interface_vitros_control_examen a,
											interface_vitros_control_examen_detalle b,
											$vitros_cargo
											lab_plantilla1 d
							WHERE		a.interface_vitros_control_examen_id = b.interface_vitros_control_examen_id AND
											b.muestra_id = '$id_muestra' AND
											b.codigo_vitros = '$codigo_vitros' AND
											b.codigo_vitros = c.codigo_vitros AND
											b.lab_examen_id = d.lab_examen_id AND
											c.lab_examen_id = d.lab_examen_id AND
											c.tecnica_id = d.tecnica_id AND
											$plantilla_cargo
							";
				$result = $dbconn->Execute($query);
			
			$this->ControlQuery("ConsultaResultadosVitrosBD-> ".$query);
			
			if ($dbconn->ErrorNo() != 0){
					$this->ErrorQuery("ConsultaResultadosVitrosBD ".$query);
					//echo "errrrrrrrrroooooooooooorrrrrrr";
					return false;
			}
			
			while(!$result->EOF){
				$var=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
			
			$result->Close();
			return $var;
		}// fin ConsultaResultadosVitrosBD
		
		/**
		* Se encarga de consultar el resultado_id de un examen determinado
		*/
		function ConsultaResultado_id($id_muestra,$cod_vitros){
			list($dbconn) = GetDBconn();
			unset($_SESSION['LTATRABAJO']['nuevo_resultado_id']);
			$muestra=explode("-",$id_muestra);
			$m=$muestra[0]."-".$muestra[1];//quite los tres ultimos caracteres
			
			$query=
			
			
							"SELECT DISTINCT resultado_id
							FROM		interface_vitros_control_examen_detalle
							WHERE		muestra_id LIKE '$m%' AND
											codigo_vitros = '$cod_vitros'";
			
			/*
							"SELECT DISTINCT resultado_id
							FROM		interface_vitros_control_examen_detalle
							WHERE		muestra_id LIKE '$m%' ";
			*/
							
			$this->ControlQuery("ConsultaResultado_id-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->ErrorQuery("ConsultaResultado_id ".$query);
					return false;
			}else{
				$respuesta=$result->fields[0];
				if($respuesta==NULL){
					$query=
							"SELECT DISTINCT resultado_id
								FROM		interface_vitros_control_examen_detalle
								WHERE		muestra_id LIKE '$m%' ";
					$result = $dbconn->Execute($query);
					$respuesta2=$result->fields[0];
					if($respuesta2==NULL){
							$query="SELECT NEXTVAL ('hc_resultados_resultado_id_seq')";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->ErrorQuery("ConsultaResultado_id-nextval(resultado_id) ".$query);
									return false;
							}else{
								$respuesta=$result->fields[0];
								$_SESSION['LTATRABAJO']['nuevo_resultado_id']='resultado_id_nuevo';
							}
					}else{
						$respuesta=$respuesta2;
					}
				}
			}
			$result->Close();
			return $respuesta;
		}//fin ConsultaResultado_id
		

		/**
		* Actualiza los resultados en hc_resultados dependiendo del examen
		*/
		function ActualizaHcResultados($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$resultado_id,$observacion){
			$this->ControlResultados("respuesta_muestra->".$respuesta_muestra);
			$var=$this->ConsultaResultadosVitrosBD($cod_vitros,$id_muestra);//cod_vitros, id_muestra
			//$lab_examen_id=$this->ConsultaLabExamen($cod_vitros,$var[cargo],$var[tecnica_id]);
			$lab_examen_id=$var[lab_examen_id];
			$alerta='0';
				if(($var[rango_max]!='0')&&($var[respuesta_muestra]<$var[rango_min])&&($var[respuesta_muestra]>$var[rango_max])){
					$alerta='1';}
			//$resultado_id=$this->ConsultaResultado_id($id_muestra,$cod_vitros);
			list($dbconn) = GetDBconn();
			if($cod_vitros == "'")
			{
				$cod_vitros=addslashes($cod_vitros);
			}
			$query="SELECT count(*)
							FROM hc_apoyod_resultados_detalles
							WHERE resultado_id = $resultado_id AND
											cargo = '$var[codigo_cups]' AND
											tecnica_id = '$var[tecnica_id]' AND
											lab_examen_id = '$lab_examen_id'";
			$result = $dbconn->Execute($query);
			$respuesta=$result->fields[0];
			
			if($respuesta==0){
				$query="INSERT INTO hc_apoyod_resultados_detalles(
															lab_examen_id,
															resultado_id,
															sw_alerta,
															resultado,
															rango_min,
															rango_max,
															unidades,
															cargo,
															tecnica_id)
												VALUES('".$lab_examen_id."',
															'".$resultado_id."',
															'".$alerta."',
															'".$var[respuesta_muestra]."',
															'".$var[rango_min]."',
															'".$var[rango_max]."',
															'".$var[unidades]."',
															'".$var[codigo_cups]."',
															'".$var[tecnica_id]."')";
				$this->ControlQuery("InsertaHcResultados-hc_apoyod_resultados_detalles-> ".$query);
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
							$this->ErrorQuery("InsertaHcResultados-hc_apoyod_resultados_detalles ".$query);
							$dbconn->RollbackTrans();
							return false;
					}
			}else{
				$query="UPDATE	hc_apoyod_resultados_detalles
								SET			resultado = '$respuesta_muestra'
								WHERE		resultado_id = $resultado_id AND
												cargo = '$var[codigo_cups]' AND
												tecnica_id = '$var[tecnica_id]' AND
												lab_examen_id = '$lab_examen_id'
								";
				$this->ControlQuery("ActualizaHcResultados- ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->ErrorQuery("ActualizaHcResultados- ".$query);
						$dbconn->RollbackTrans();
						return false;
				}
			}
			
			$query="UPDATE interface_vitros_control_examen_detalle
							SET			respuesta_muestra = $respuesta_muestra,
											resultado_id = $resultado_id
							WHERE		muestra_id = '$id_muestra' AND
											codigo_vitros = '$cod_vitros'";
											//$id_muestra,$id_control==>cod_vitros,id_muestra
			$this->ControlQuery("ActualizaHcResultados- ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->ErrorQuery("ActualizaHcResultados-interface_vitros_control_examen_detalle ".$query);
						$dbconn->RollbackTrans();
						return false;
				}
				$query = "
									SELECT observacion_prestacion_servicio
									FROM		hc_resultados
									WHERE		resultado_id  = '".$resultado_id."'
				";
				$this->ControlQuery("ActualizaHcResultados- ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->ErrorQuery("ActualizaHcResultados- Consulta hc_resultados ".$query);
						$dbconn->RollbackTrans();
						return false;
				}
				$res_observacion=$result->fields[0];
				$observacion .= "\n" . $res_observacion;
				$query = "UPDATE 	hc_resultados
									SET			observacion_prestacion_servicio = '".$observacion."'
									WHERE		resultado_id  = '".$resultado_id."'
									";
				$this->ControlQuery("ActualizaHcResultados- ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->ErrorQuery("ActualizaHcResultados- Actualizacion hc_resultados ".$query);
						$dbconn->RollbackTrans();
						return false;
				}
				$dbconn->CommitTrans();
			$result->Close();

			return true;
	} // fin ActualizaHcResultados
		
	/**
	*Consulta el lab_examen_id de un examen
	*/
	function ConsultaLabExamen1($cod_vitros,$cargo_cups,$tecnica_id){
		$query="SELECT	lab_examen_id
						FROM		interface_vitros_cargo
						WHERE		tecnica_id = '$tecnica_id' AND
										codigo_vitros = '$cod_vitros'  AND
										codigo_cups = '$cargo_cups'";
		$this->ControlQuery("ConsultaLabExamen--> ".$query);
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->ErrorQuery("ConsultaLabExamen ".$query);
				return false;
		}else{
			$res=$result->fields[0];
		}
		$result->Close();
		return $res;
	}
		/**
		* Inserta resultados en hc_resultados
		* @param	$cod_vitros
		* @param	$id_muestra
		* @param	$tipo
		* @param	$lab_examen_id_compuesto
		*/
		function InsertaHcResultados($cod_vitros,$id_muestra,$resul_id,$observacion){
			//captura datos de la BD de Vitros
			$var=$this->ConsultaResultadosVitrosBD($cod_vitros,$id_muestra);//cod_vitros,id_muestra
			//print_r($var);
			if($cod_vitros == "'")
			{
				$cod_vitros=addslashes($cod_vitros);
			}
			if(!empty($var))
			{
				$alerta='0';
				if(($var[rango_max]!='0')&&($var[respuesta_muestra]<$var[rango_min])&&($var[respuesta_muestra]>$var[rango_max])){
					$alerta='1';}
				$paciente_id=trim($var[paciente_id]);
				$tipo_id_paciente=trim($var[tipo_id_paciente]);
				//$resul_id=$this->ConsultaResultado_id($id_muestra,$cod_vitros);
				
				//$lab_examen_id=$this->ConsultaLabExamen($cod_vitros,$var[codigo_cups],$var[tecnica_id]);
				$lab_examen_id=$var[lab_examen_id];
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$cargo=explode("-",$var[codigo_cups]);
				if($_SESSION['LTATRABAJO']['nuevo_resultado_id']=='resultado_id_nuevo'){
				
					$query="INSERT INTO hc_resultados(
														resultado_id,
														fecha_registro,
														usuario_id,
														tipo_id_paciente,
														paciente_id,
														cargo,
														fecha_realizado,
														os_tipo_resultado,
														observacion_prestacion_servicio,
														sw_modo_resultado,
														tecnica_id)
									VALUES('".$resul_id."',
												'".$var[fecha]."',
												".$var[usuario_id].",
												'".$tipo_id_paciente."',
												'".$paciente_id."',
												'".$cargo[0]."',
												'now()',
												'APD',
												'".$observacion."',
												'1',
												".$var[tecnica_id]."
												)";
					$this->ControlQuery("InsertaHcResultados-hc_resultados-> ".$query);
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
							$this->ErrorQuery("InsertaHcResultados-hc_resultados ".$query);
							$dbconn->RollbackTrans();
							return false;
					}
				
					$query= "INSERT INTO hc_resultados_sistema(
																resultado_id,
																numero_orden_id,
																usuario_id_profesional,
																apoyod_entrega_id,
																usuario_id_profesional_autoriza)
												VALUES	(".$resul_id.",
																".$var[numero_orden_id].",
																".$var[usuario_id].",
																NULL,
																NULL)
								";
					$this->ControlQuery("InsertaHcResultados-hc_resultados_sistema ".$query);
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
							$this->ErrorQuery("InsertaHcResultados-hc_resultados_sistema ".$query);
							$dbconn->RollbackTrans();
							return false;
					}
				}
				//list($dbconn) = GetDBconn();
				$query="INSERT INTO hc_apoyod_resultados_detalles(
														lab_examen_id,
														resultado_id,
														sw_alerta,
														resultado,
														rango_min,
														rango_max,
														unidades,
														cargo,
														tecnica_id)
											VALUES('".$lab_examen_id."',
														'".$resul_id."',
														'".$alerta."',
														'".$var[respuesta_muestra]."',
														'".$var[rango_min]."',
														'".$var[rango_max]."',
														'".$var[unidades]."',
														'".$cargo[0]."',
														'".$var[tecnica_id]."')";
			$this->ControlQuery("InsertaHcResultados-hc_apoyod_resultados_detalles-> ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->ErrorQuery("InsertaHcResultados-hc_apoyod_resultados_detalles ".$query);
						$dbconn->RollbackTrans();
						return false;
				}
				
				
				$query="UPDATE interface_vitros_control_examen_detalle
								SET			resultado_id = ".$resul_id.",
												sw_estado_examen = '3'
								WHERE		numero_orden_id = ".$var[numero_orden_id]." AND
												orden_servicio_id = '".$var[orden_servicio_id]."' AND
												muestra_id = '".$id_muestra."' AND
												codigo_vitros = '".$cod_vitros."'";
				$this->ControlQuery("InsertaHcResultados-interface_vitros_control_examen_detalle ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->ErrorQuery("InsertaHcResultados-interface_vitros_control_examen_detalle ".$query);
						$dbconn->RollbackTrans();
						return false;
				}
	
				$dbconn->CommitTrans();
				$result->Close();
				unset($_SESSION['LTATRABAJO']['nuevo_resultado_id']);
			}else{return false;}
			return true;
		}//fin function ActualizaResultadoVitros
		
		/**
		*%%borrar%
		* %% borrar cuando ya no se necesite
		* MEtodo de prueba para contar los caracteres generados en la cadena de la muestra
		*/
		function ManipulaCadenaParaConteo($file){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.$file;
			if(!$fichero = fopen($nombre_archivo,'r')){
				$this->error = "Error Vitros";
				$this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO.';
				//echo ERROR;
				return false;
			}
			$cadena='';
			while(!feof($fichero)){
				$buffer=fgets($fichero,4096);//lee una linea
				/*$id='X'.substr($buffer,25,15).'}';//id
				$fluido='Y'.substr($buffer,40,1).'}';//+ fluido
				$dilucion='Z'.substr($buffer,44,5).'}';//+ dilucion
				$buffer=substr($buffer,49);//quito comienzo
				$buffer=substr($buffer,0,-13);//quito final
				$cadena.=$id.$fluido.$dilucion.$buffer;
				*/
				$cadena.=$buffer;
			}//fin while
			fclose($fichero);
			//echo $cadena."<br>";
			
			$cadena=str_replace(" ",",",$cadena);
			
			return true; 
		}//fin function ManipulaCadena
		
		

		/**
		*	Actualiza en la base de datos de vitros los errores generados
		*/
		function ActualizaErrorVitros($respuesta,$file){
	
			$file=substr($file,1);//quito la S o R
			$file=(int)$file;
	
			list($dbconn) = GetDBconn();
			$query="UPDATE  interface_vitros_control_examen_detalle
							SET			error_vitros='$respuesta',
											sw_estado_examen = '5'
							WHERE interface_vitros_control_examen.nombre_archivo = $file AND
										interface_vitros_control_examen.interface_vitros_control_examen_id = interface_vitros_control_examen_detalle.interface_vitros_control_examen_id
			";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al actualizar error vitros";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return '0059';
			}
			
			$cont=$dbconn->Affected_Rows();
			if($cont==0){
				$res='error';}
			else{
				$res='ok';}
				
			//$result->Close();
			
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'query.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,$query."\n".$cont."\n"."--------\n");
			fclose($fichero);
		
			return $res;
		}//fin ActualizaErrorVitros
		/**
		* Consulta el valor de una metrica del paciente
		*/
		function ConsultaMetrica($id_muestra,$tipo_metrica){
			$res=explode("-",$id_muestra);
			$fecha_cump=$fecha=date( "Y-m-d",strtotime ("$res[0]"));
			$numero_cump=$res[1];
			$query="
							SELECT	b.valor_metrica
							FROM		interface_vitros_control_examen as a,
											pacientes_metricas as b
							WHERE		a.numero_cumplimiento = $numero_cump AND
											a.fecha_cumplimiento = '$fecha_cump' AND
											a.tipo_id_paciente = b.tipo_id_paciente AND
											a.paciente_id = b.paciente_id AND
											b.tipo_metrica_id = '$tipo_metrica'
			";
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar metrica";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$respuesta=$result->fields[0];
			return $respuesta;
		}
		/**
		* Calcula la superficie Corporal dado el peso y la talla del paciente
		*/
		function CalculaSuperficieCorporal($id_muestra){
			$peso=$this->ConsultaMetrica($id_muestra,'peso');
			$talla=$this->ConsultaMetrica($id_muestra,'talla');
			$sp=1+(($peso+$talla-160)/100);
			return $sp;
		}
		
		/**
		* Consulta el volumen y el cargo cuops dada la muestra_id y el cogigo_vitros
		*/
		function ConsultarVolumenCups($id_muestra,$cod_vitros){
			$res=explode("-",$id_muestra);
			$fecha_cump=$fecha=date( "Y-m-d",strtotime ("$res[0]"));
			if(empty($res[1]))
			{
				$res[1]='0';
			}
			$numero_cump=$res[1];
			$this->RegistroErrorVitros("ConsultarVolumenCups","id_muestra--> ",$id_muestra);
			$this->RegistroErrorVitros("ConsultarVolumenCups","id_muestra--> ",$res[0]);
			$this->RegistroErrorVitros("ConsultarVolumenCups","id_muestra--> ",$res[1]);
			$query="SELECT	a.volumen,b.codigo_cups
							FROM		interface_vitros_control_examen as a,
											interface_vitros_control_examen_detalle as b
							WHERE		a.fecha_cumplimiento = '".$fecha_cump."' AND 
											a.numero_cumplimiento = '".$numero_cump."' AND
											b.interface_vitros_control_examen_id = a.interface_vitros_control_examen_id AND
											b.codigo_vitros = '".$cod_vitros."' 
			";
			$this->ControlQuery("VerificaResultado - ConsultarVolumenCups --> ".$query);
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar fluido";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while (!$result->EOF){
				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
			return $vars;
		}
		/**
		*
		*/
		function ConsultaDepuracionCreatina($id_muestra,$codigo_cups){
			$res=explode("-",$id_muestra);
			$fecha_cump=$fecha=date( "Y-m-d",strtotime ("$res[0]"));
			$numero_cump=$res[1];
			$query="SELECT	b.respuesta_muestra
							FROM		interface_vitros_control_examen as a,
											interface_vitros_control_examen_detalle as b
							WHERE		a.fecha_cumplimiento = '$fecha_cump' AND 
											a.numero_cumplimiento = $numero_cump AND
											a.muestra_id  = '$id_muestra' AND
											b.interface_vitros_control_examen_id = a.interface_vitros_control_examen_id AND
											b.codigo_cups = '$codigo_cups' 
			";
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar fluido";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$respuesta=$result->fields[0];
			return $vars;
		}
		/**
		* Para ciertos casos especiales se debe recalcular el resultado generado por la vitros,
		* pues se este se analiza en diferentes unidades a las dadas.
		* OJO
		* Para la busqueda de las depuraciones en creatina 24horas en Suero y Orina, se asume que fueron mandadas
		* en la misma orden de cumplimiento, por que es la forma de saber a que grupo de examenes se va a 
		* evaluar si no se pone por hay, podria tomar una depuracion normal como si fuera de 24 horas. De todos
		* modos si se desea hacerlo de esa manera la busqueda seria por tipo_documento y paciente_id.
		*
		* @param	String $id_muestra
		* @param	String $cod_vitros
		* @param	String $respuesta_muestra
		* @return	String $respuesta_muestra
		*/
		function ModificaResultadoCasoEspecial($id_muestra,$cod_vitros,$respuesta_muestra){
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'respuesta_muestra-->',$respuesta_muestra);
			$valor=$this->ConsultarVolumenCups($id_muestra,$cod_vitros);
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'volumen-->',$valor['volumen']);
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'cups-->',$valor['codigo_cups']);
			$constante_depuracion=1440;
			$nuevo_resultado=$respuesta_muestra;
			$proteina_total	=	'0';
			$bun_orina			=	'0';
			$dep_crea_orina	=	'0';
			$dep_crea_suero	=	'0';
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'switch-->',"antes");
			//$proteina_orina	=	ModuloGetVar('app','Os_ListaTrabajoVitros','Proteina_Orina');
			$proteina_orina	=	'903862';
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'switch-->',$proteina_orina);
			//$bun_orina			=	ModuloGetVar('app','Os_ListaTrabajoVitros','bun_orina_24h');
			$bun_orina			= '903857';
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'switch-->',$bun_orina);
			//$dep_crea_orina	=	ModuloGetVar('app','Os_ListaTrabajoVitros','depuracion_creatinina_orina');
			$dep_crea_orina	= '111111';
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'switch-->',$dep_crea_orina);
			//$dep_crea_suero	=	ModuloGetVar('app','Os_ListaTrabajoVitros','depuracion_creatinina_suero');
			$dep_crea_suero	= '222222';
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'switch-->',$dep_crea_suero);
			if(!empty($valor))
			{$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'switch-->',"entro");
				switch($valor['codigo_cups']){
					//tp, proteinas en Orina 24 h 903862
					case "$proteina_orina":{
										$nuevo_resultado=0;
										$volumen=$valor['volumen'];
										$nuevo_resultado=(($volumen*$respuesta_muestra)/100);
										$this->ControlResultados("nuevo_res->".$nuevo_resultado."= vol -> ".$volumen." *res->".$respuesta_muestra);
										break;
									}
					//bun orina 24h 903857
					case "$bun_orina":{
										$nuevo_resultado=0;
										$volumen=$valor['volumen'];
										//$nuevo_resultado=((($volumen*$respuesta_muestra)/100)/1000);
										$nuevo_resultado=(($volumen*$respuesta_muestra)/100);
										break;
									}
					//depuracion creatinina en orina 903823
					case "$dep_crea_orina":{
										$nuevo_resultado=0;
										$res_dc_suero=$this->ConsultaDepuracionCreatina($id_muestra,$dep_crea_suero);
										//si hay resultado en suero genere nuevo resultado
										if((!empty($res_dc_suero)) || ($res_dc_suero != NULL)){
											$sup_corporal=$this->CalculaSuperficieCorporal($id_muestra);
											$volumen=$valor['volumen'];
											$nuevo_resultado=(((($respuesta_muestra*$volumen)/$constante_depuracion)/$res_dc_suero)*$sup_corporal);
										}else{
											$nuevo_resultado=$respuesta_muestra;
										}
										break;
									}
					//depuracion creatina en suero 903825
					case "$dep_crea_suero":{
										$nuevo_resultado=0;
										$res_dc_orina=$this->ConsultaDepuracionCreatina($id_muestra,$dep_crea_orina);
										if((!empty($res_dc_orina)) || ($res_dc_orina != NULL)){
											$sup_corporal=$this->CalculaSuperficieCorporal($id_muestra);
											$volumen=$valor['volumen'];
											$nuevo_resultado=(((($respuesta_muestra*$volumen)/$constante_depuracion)/$res_dc_suero)*$sup_corporal);
										}else{
											$nuevo_resultado=$respuesta_muestra;
										}
										break;
									}
				}
			}
			$this->RegistroErrorVitros("ModificaResultadoCasoEspecial",'res-->',$nuevo_resultado);
			return $nuevo_resultado;
		}
		
		/**
		*
		*/
		function ConsultaErrorMedida($error_medidas_id)
		{
			list($dbconn) = GetDBconn();
			$query=
						"	SELECT	error_medidas_descripcion
							FROM		interface_vitros_error_medidas
							WHERE		error_medidas_id = '".$error_medidas_id."'
						";
			$this->ControlQuery("VerificaResultado-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->RegistroErrorVitros("ConsultaErrorMedida",'-->','yy');
					$this->error = "Error al consultar error_medidas_descripcion";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->ErrorQuery("ConsultaErrorMedida".$query);
					return false;
			}
			$this->RegistroErrorVitros("ConsultaErrorMedida",'-->','xx');
			$descripcion=$result->fields[0];
			$this->RegistroErrorVitros("ConsultaErrorMedida",'-->',$descripcion);
			return $descripcion;
		}
		/**
		*
		*/
		function ConsultaErrorDerivado($error_derivado_id)
		{
			list($dbconn) = GetDBconn();
			$query=
						"	SELECT	error_derivado_descripcion
							FROM		interface_vitros_error_derivado
							WHERE		error_derivado_id = '".$error_derivado_id."'
						";
			$this->ControlQuery("VerificaResultado-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar interface_vitros_error_derivado";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->ErrorQuery("ConsultaErrorMedida".$query);
					return false;
			}
			$descripcion=$result->fields[0];
			return $descripcion;
		}
/**
		*
		*/
		function ConsultaErrorAdvertencia($advertencia_id)
		{
			list($dbconn) = GetDBconn();
			$query=
						"	SELECT	descripcion_advertencia
							FROM		interface_vitros_advertencia
							WHERE		advertencia_id = '".$advertencia_id."'
						";
			$this->ControlQuery("VerificaResultado-> ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar interface_vitros_advertencia";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->ErrorQuery("ConsultaErrorMedida".$query);
					return false;
			}
			$descripcion=$result->fields[0];
			return $descripcion;
		}

		/**
		*
		*/
		function ConsultaErrorMuestra($error_id)
		{
			$i=0;
			$descripcion ="";
			while(!empty($error_id))
			{
					$error[$i]=substr($error_id, 0, 2);
					$error_id=substr($error_id, 2);
					$i++;
			}
			
			foreach($error As $datos => $valor)
			{
				list($dbconn) = GetDBconn();
				$query=
							"	SELECT	descripcion_error
								FROM		 	interface_vitros_error
								WHERE		error_id = '".$valor."'
							";
				$this->ControlQuery("VerificaResultado-> ".$query);
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al consultar interface_vitros_error";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->ErrorQuery("ConsultaErrorMedida".$query);
						return false;
				}
				$descripcion .= trim($result->fields[0]);
				$descripcion .= ", ";
			}
			return $descripcion;
		}
		/**
		*
		*/
		function ErrorSistema($mensaje){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'errorsistema.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,$mensaje."\n");
			fclose($fichero);
			return true;
		}
		/**
		*
		*/
		function ErrorQuery($mensaje){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'errorquery.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,$mensaje."\n");
			fclose($fichero);
			return true;
		}
		/**
		*
		*/
		function ControlQuery($mensaje){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'controlquery.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,$mensaje."\n");
			fclose($fichero);
			return true;
		}
		/**
		*
		*/
		function ControlResultados($mensaje){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'controlres.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,$mensaje."\n");
			fclose($fichero);
			return true;
		}
	}//fin class Vitros
?>