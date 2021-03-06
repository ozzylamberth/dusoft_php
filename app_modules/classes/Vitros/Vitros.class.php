<?
/**
 * $Id: Vitros.class.php,v 1.20 2005/10/27 12:50:52 mauricio Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Clases que maneja la informacion enviada por la vitros al WebService de 
 * la Vitros
 * @author    Mauricio Bejarano L. <maurobej@hotmail.com>
 * @version   $Revision: 1.20 $
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
					if(unlink($nombre_archivo)){
						$file2="S".substr($file,1);
						$nombre_archivo=$path.$file2;
						if(!unlink($nombre_archivo)){
							return false;
						}
					}else{
						return false;
					}
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
	
			$client=new soapclient($wsdl, true);
			
			if ($sError = $client->getError()) {
				//echo "Error En la Coneccion  con el WebService [" . $sError . "]";
				return 'error';break;
			} 
			$param=array('validacion'=>'Guardar','nombre'=>$file,'cuerpo'=>$cadena);
	
			//$respuesta= $client->call("webS", $param);
			$respuesta= $client->call($metodo, $param);
			//echo $client->getDebug(); 
			if ($client->fault){ // Si
						//echo 'Error en la Comunicacion con el WebService';
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
		//echo"<br>->";print_r($cadena);
			for($i=0;$i<sizeof($cadena);$i++){
				$sw=0;
				//echo "<br>".substr($cadena[$i],0,1)."-".substr($cadena[$i],1);
				$j=$i;
				if(substr($cadena[$i],0,1)=='X'){
					$id_muestra=substr($cadena[$j],1);
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
			//$respuesta_muestra=$this->ModificaResultadoCasoEspecial($id_muestra,$cod_vitros,$respuesta_muestra);
			$res=$this->VerificaResultado($id_muestra,$cod_vitros);
					$this->RegistroErrorVitros("actualizaDatos","Repuesta de VerificaResultado",$res);
			$res=explode("-",$res);
					$this->RegistroErrorVitros("actualizaDatos","Repuesta de VerificaResultado",$res[0]);
			$tmp=explode(",",$res[0]);
			$accion=$tmp[0];
			$tipo=$tmp[1];
			$c_especial=$tmp[2];
			$tmp=explode(",",$res[1]);
			$resultado_id=$tmp[0];
			$lab_examen_id=$tmp[1];
			if(!$accion)
			{
				return false;
			}
			elseif($accion=='inserta')
				{
					if($c_especial=='detalle'){
						//si solo inserta hc_apoyod_resultados_detalle
						$idv=$this->InsertaDatosVitros($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																			$error_derivado_id,$advertencia_id,$error_id);
						if($idv)
						{
							$this->InsertaCompuesto($resultado_id,$lab_examen_id,$cod_vitros,$id_muestra);
							return true;
						}
						else
						{
							return false;
						}
					}else
					{//inserta en todo
							$this->RegistroErrorVitros("actualizaDatos","Inserta",$resultado_id);
						$idv=$this->InsertaDatosVitros($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																			$error_derivado_id,$advertencia_id,$error_id);
						if($idv)
						{
							$this->InsertaHcResultados($cod_vitros,$id_muestra,$tipo,$lab_examen_id);
							return true;
						}
						else
						{
							return false;
						}
					}
				}
				elseif($accion=='modifica')
				{
						$this->RegistroErrorVitros("actualizaDatos","Modifica",$res[1]);
					$firma=$this->VerificaFirma($id_muestra,$cod_vitros,$res[1]);
					$respuesta_muestra=trim($respuesta_muestra);
					//se puede modificar si no esta firmado
					if(($firma==NULL)||(empty($firma))) 
					{
							$this->ActualizaHcResultados($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$resultado_id,$lab_examen_id,$c_especial);
 							return true;
					}
					else
					{//esta firmado
						return false;
					}
				}
				else{
					$this->RegistroErrorVitros("actualizaDatos","error",$res[1]);
					return false;//%%verificar
				}
			//%%verificar si se deja
			//debe ser al momento de la firma
		//$this->EliminaRegistroVitros($id_muestra,$id_control);
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
			$muestra=explode('-',$id_muestra);
			//print_r($muestra);
			list($dbconn) = GetDBconn();
			$query="SELECT	resultado_id,
											sw_estado_examen
							FROM		interface_vitros_control_examen_detalle
							WHERE		codigo_vitros= '$codigo_vitros'  AND
											muestra_id = '$id_muestra'
						";
			//$this->RegistroErrorVitros("VerificaResultado","query",$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al resultado_id1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("VerificaResultado1",$query,"ERROOOOORRRRR");
					return false;
			}else{
				$resultado_id=$result->fields[0];
				$sw_examen=$result->fields[1];
				$this->RegistroErrorVitros("VerificaResultado_id_y_sw_examen",$resultado_id,$sw_examen);
				if($resultado_id==NULL){
					//se inserta resultado
					//verifica si es un resultado compuesto
					//para este caso muestra debe venir como :
					//fecha cumplimiento - numero cumplimiento - consecutivo de la muestra
					if(($muestra[2]!=NULL)||(!empty($muestra[2]))) 
					{
							$id_muestra=$muestra[0]."-".$muestra[1];
							$query="SELECT	DISTINCT resultado_id
											FROM		interface_vitros_control_examen_detalle
											WHERE		codigo_vitros= '$codigo_vitros'  AND
															muestra_id like '$id_muestra%'
										";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al consultar resultado_id2";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->RegistroErrorVitros("VerificaResultado2",$query,"ERROOOOORRRRR");
							return false;
						}else{
							$res='';
							//capturo el resultado_id cuando ya a sido asignado
							foreach($result as $datos=>$d){
								if($d>$res){
									$res=$d;
								}
							}
							$examen_id=(int)$muestra[2]+1;
							if($res[0]==NULL){//inserta uno nuevo
								return "inserta,compuesto-,".$examen_id;
							}else{//si solo hay que insertarlo en hc_apoyod_resultados_detalle
								return "inserta,compuesto,detalle-".$res[0].",".$examen_id;
							}
						}
					}//fin if compuesto
					else{//si es normal
						return 'inserta-';
					}
				}else{
					//se modifica resultado
					
					if(($muestra[2]!=NULL)||(!empty($muestra[2]))) 
					{//si es compuesto
						$examen_id=(int)$muestra[2]+1;
						return "modifica,compuesto,detalle-".$resultado_id.",".$examen_id;
					}else{
						return "modifica-".$resultado_id;
					}
				}
			}
			$dbconn->Close();
		}//fin VerificaResultado

		
		/**
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
		* Actualiza los la BD_Vitros con la informacion generada por la vitros en su archivo de respuesta
		* @param ($id_control,$id_muestra,$respuesta_muestra,$unidad_id,$error_medidas_id,$error_derivado_id,$advertencia_id,$error_id
		* parametros en la base de datos vitros
		*/
		function InsertaDatosVitros($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$error_medidas_id,
																	$error_derivado_id,$advertencia_id,$error_id){
			list($dbconn) = GetDBconn();
			//$error_id_mod=substr($error_id,1,2);//leer los dos primeros caracteres los otros 6 quedan pendientes
			$id_muestra=trim($id_muestra);
			$cod_vitros=trim($cod_vitros);
			$respuesta_muestra=trim($respuesta_muestra);
			$query="UPDATE 	interface_vitros_control_examen_detalle
							SET			respuesta_muestra=$respuesta_muestra,
											unidad_id='$unidad_id',
											error_medidas_id='$error_medidas_id',
											error_derivado_id='$error_derivado_id',
											advertencia_id='$advertencia_id',
											error_id='$error_id',
											sw_estado_examen='3'
							WHERE		codigo_vitros='$cod_vitros' AND
											muestra_id = '$id_muestra' AND
											sw_estado_examen = '2'
			";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD" . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("InsertaDatosVitros","Erro en query",$query);
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
		}//fin function InsertaDatosVitros
		
		
		/**
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
		* %%verificar si se necesita
		* Se encarga de consultar los resultados de los examenes en la Base de datos Vitros
		*/
		function ConsultaResultadosVitrosBD($codigo_vitros,$id_muestra){
			list($dbconn) = GetDBconn();
			
			$id_muestra=trim($id_muestra);
			$id_control=trim($id_control);
			$query="SELECT	a.fecha,
											a.usuario_id,
											a.tipo_id_paciente,
											a.paciente_id,
											c.codigo_cups,
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
											interface_vitros_cargo c,
											lab_plantilla1 d
							WHERE		a.interface_vitros_control_examen_id = b.interface_vitros_control_examen_id AND
											b.muestra_id = '$id_muestra' AND
											b.codigo_vitros = '$codigo_vitros' AND
											b.codigo_vitros = c.codigo_vitros AND
											c.lab_examen_id = d.lab_examen_id AND
											c.tecnica_id = d.tecnica_id AND
											c.codigo_cups = d.cargo
							";
		
							$query2=$query;
		$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
		$nombre_archivo=$path.'recibido.txt';
		if(!$fichero = fopen($nombre_archivo,'a')){
			return 'errorF';
		}
		fputs($fichero,"query_var-> ".$query2."\n");
		fclose($fichero);
							
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD" . $dbconn->ErrorMsg();
					return false;
			}else{
				$var=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
			$result->Close();
			return $var;
		}// fin ConsultaResultadosVitrosBD
		
		/**
		* %% verificar si se necesita
		* Se encarga de consultar el resultado de un examen determinado
		*/
		function ConsultaResultado_id($fecha,$usuario_id,$tipo_id_paciente,$paciente_id,$codigo_cups){
			list($dbconn) = GetDBconn();
			$query="SELECT 	resultado_id
							FROM 		hc_resultados
							WHERE		fecha_registro='$fecha' AND
											usuario_id='$usuario_id' AND
											tipo_id_paciente='$tipo_id_paciente' AND
											paciente_id='$paciente_id' AND
											cargo='$codigo_cups'";
											
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error al consultar resultado_id" . $dbconn->ErrorMsg();
					return false;
			}else{
				$respuesta=$result->fields[0];
			}
			$result->Close();
			return $respuesta;
		}//fin ConsultaResultado_id
		

		/**
		* Actualiza los resultados en hc_resultados dependiendo del examen
		*/
		function ActualizaHcResultados($id_muestra,$cod_vitros,$respuesta_muestra,$unidad_id,$resultado_id,$lab_examen_id,$c_especial){
			$var=$this->ConsultaResultadosVitrosBD($cod_vitros,$id_muestra);//cod_vitros, id_muestra
			list($dbconn) = GetDBconn();

			if($c_especial=='detalle'){
				$lab_examen=$lab_examen_id;
			}else{
				$lab_examen=$var[lab_examen_id];
			}
			$query="UPDATE	hc_apoyod_resultados_detalles
							SET			resultado = '$respuesta_muestra'
							WHERE		resultado_id = $resultado_id AND
											cargo = '$var[codigo_cups]' AND
											tecnica_id = '$var[tecnica_id]' AND
											lab_examen_id = '$lab_examen'
							";

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
					$dbconn->RollbackTrans();
					return false;
			}
			
			
			$query="UPDATE interface_vitros_control_examen_detalle
							SET			respuesta_muestra = $respuesta_muestra
							WHERE		muestra_id = '$id_muestra' AND
											codigo_vitros = '$cod_vitros'";
											//$id_muestra,$id_control==>cod_vitros,id_muestra
			$query5=$query;
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'recibido.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,"query5-> ".$query5."\n");
			fclose($fichero);
												
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						//echo "<br>Error BD " . $dbconn->ErrorMsg();
						$this->RegistroErrorVitros("ActualizaHcResultados","query5","");
						$dbconn->RollbackTrans();
						return false;
				}
			

				$dbconn->CommitTrans();
			$result->Close();

			return true;
	} // fin ActualizaHcResultados
		
		/**
		* Inserta resultados en hc_resultados
		* @param	$cod_vitros
		* @param	$id_muestra
		* @param	$tipo
		* @param	$lab_examen_id_compuesto
		*/
		function InsertaHcResultados($cod_vitros,$id_muestra,$tipo,$lab_examen_id_compuesto){
			//captura datos de la BD de Vitros
			$var=$this->ConsultaResultadosVitrosBD($cod_vitros,$id_muestra);//cod_vitros,id_muestra
			$alerta='0';
			if(($var[rango_max]!='0')&&($var[respuesta_muestra]<$var[rango_min])&&($var[respuesta_muestra]>$var[rango_max])){
				$alerta='1';}
			
				$paciente_id=trim($var[paciente_id]);
				$tipo_id_paciente=trim($var[tipo_id_paciente]);
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
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
							VALUES(NEXTVAL ('hc_resultados_resultado_id_seq'),
										'$var[fecha]',
										$var[usuario_id],
										'$tipo_id_paciente',
										'$paciente_id',
										'$var[codigo_cups]',
										'now()',
										 'APD',
										'',
										'1',
										$var[tecnica_id]
										)";

		$query1=$query;
		$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
		$nombre_archivo=$path.'recibido.txt';
		if(!$fichero = fopen($nombre_archivo,'a')){
			return 'errorF';
		}
		fputs($fichero,"query1-> ".$query1."\n");
		fclose($fichero);
										
										
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>1111Error BD hc_resultados" . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("InsertaHcResultados","query1","");
					$dbconn->RollbackTrans();
					return false;
			}
			//$result->Close();
				$resul_id=$this->ConsultaResultado_id($var[fecha],$var[usuario_id],$tipo_id_paciente,$paciente_id,$var[codigo_cups]);

				//si es compuesto el lab_examen_id es especial
				if($tipo=='compuesto'){
					$examen_id=$lab_examen_id_compuesto;
				}else{
					$examen_id=$var[lab_examen_id];
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
										VALUES('$examen_id',
													'$resul_id',
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
					$this->RegistroErrorVitros("InsertaHcResultados","query2","");
					$dbconn->RollbackTrans();
					return false;
			}
			//$result->Close();
			
			//list($dbconn) = GetDBconn();
			$query= "INSERT INTO hc_resultados_sistema(
														resultado_id,
														numero_orden_id,
														usuario_id_profesional,
														apoyod_entrega_id,
														usuario_id_profesional_autoriza)
										VALUES	($resul_id,
														$var[numero_orden_id],
														NULL,
														NULL,
														NULL)
						";

						$query3=$query;
		$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
		$nombre_archivo=$path.'recibido.txt';
		if(!$fichero = fopen($nombre_archivo,'a')){
			return 'errorF';
		}
		fputs($fichero,"query3-> ".$query3."\n");
		fclose($fichero);
						
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD " . $dbconn->ErrorMsg();
					$this->RegistroErrorVitros("InsertaHcResultados","query3","");
					$dbconn->RollbackTrans();
					return false;
			}
			
			$query="UPDATE interface_vitros_control_examen_detalle
							SET			resultado_id = $resul_id
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
					$this->RegistroErrorVitros("InsertaHcResultados","query4","");
					$dbconn->RollbackTrans();
					return false;
			}

			$dbconn->CommitTrans();
			$result->Close();

			return true;
		}//fin function ActualizaResultadoVitros
		
		/**
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
			$numero_cump=$res[1];
			$query="SELECT	a.volumen,b.codigo_cups
							FROM		interface_vitros_control_examen as a,
											interface_vitros_control_examen_detalle as b
							WHERE		a.fecha_cumplimiento = '$fecha_cump' AND 
											a.numero_cumplimiento = $numero_cump AND
											b.interface_vitros_control_examen_id = a.interface_vitros_control_examen_id AND
											b.codigo_vitros = '$cod_vitros' 
			";
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
			$valor=ConsultaVolumenCups($id_muestra,$cod_vitros);
			$constante_depuracion=1440;
			$nuevo_resultado=0;
			switch($valor['codigo_cups']){
				//tp, proteinas totales
				case "":{
									$volumen=$valor['volumen'];
									$nuevo_resultado=(($volumen*$respuesta_muestra)/100);
									break;
								}
				//bun orina 24h 903857
				case "903857":{
									$volumen=$valor['volumen'];
									$nuevo_resultado=((($volumen*$respuesta_muestra)/100)/1000);
									break;
								}
				//depuracion creatina en orina 903823
				case "903823":{
									$res_dc_suero=$this->ConsultaDepuracionCreatina($id_muestra,'903825');
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
				case "903825":{
									$res_dc_orina=$this->ConsultaDepuracionCreatina($id_muestra,'903823');
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
			return true;
		}
	}//fin class Vitros
?>