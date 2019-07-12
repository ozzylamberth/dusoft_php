<?php
  /******************************************************************************
  * $Id: ListadoPacientesconSalida.class.php,v 1.8 2011/06/24 16:36:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.8 $ 
	* 
	* @autor Lorena Arago Galindo
  * Proposito del Archivo:	Manejo de la logica del proceso de liquidacion de Habitaciones 
  ********************************************************************************/
	class ListadoPacientesconSalida
	{
		var $offset = 0;
		
		function ListadoPacientesconSalida(){}
		
    /**********************************************************************************
		* Busca los pacientes con salida de hospitalizacion en la estacion
    *
    * @access public 		
		* @return array
		***********************************************************************************/
		function ObtenerPacientesconSalidaHopitalizacion(){
      GLOBAL $ADODB_FETCH_MODE;
		  $query = "SELECT 
                    A.*,
                          MH.cama, MH.fecha_egreso,CA.pieza,
                          P.primer_nombre ||' '|| P.segundo_nombre ||' '|| P.primer_apellido ||' '|| P.segundo_apellido AS nombre,
                          EE.estacion_id,EE.descripcion,EE.departamento,DPTO.descripcion as nombre_departamento
                FROM 
                (
                    SELECT A.ingreso, A.evolucion_id, A.sw_estado,
                              C.numerodecuenta,
                              D.estado,D.plan_id,D.rango,
                              TO_CHAR(D.fecha_registro, 'DD/MM/YYYY') AS fecha,
                              PL.plan_descripcion,
                              I.tipo_id_paciente, I.paciente_id, I.fecha_ingreso
                    
                    FROM   
                              hc_ordenes_medicas AS A
                              RIGHT JOIN hc_vistosok_salida_detalle AS B ON (A.ingreso = B.ingreso AND A.evolucion_id = B.evolucion_id),
                              hc_evoluciones AS C,
                              cuentas AS D,
                              planes AS PL,
                              ingresos AS I
                              
                    WHERE A.sw_estado IN ('1')
                    AND   A.hc_tipo_orden_medica_id IN ('99','06','07')
                    AND   A.ingreso = C.ingreso
                    AND   A.evolucion_id = C.evolucion_id
                    AND   D.numerodecuenta = C.numerodecuenta
                    AND   D.estado IN ('1','2')
                    AND   D.plan_id=PL.plan_id
                    AND   A.ingreso = I.ingreso
                      
                ) AS A,
                movimientos_habitacion AS MH,
                camas CA,
                pacientes AS P,
                estaciones_enfermeria AS EE,
                departamentos DPTO                 
                
                WHERE
                  A.ingreso = MH.ingreso
                AND  A.numerodecuenta = MH.numerodecuenta
                AND  MH.fecha_egreso IS NULL
                AND  MH.cama=CA.cama
                AND  P.paciente_id = A.paciente_id
                AND  P.tipo_id_paciente = A.tipo_id_paciente
                AND  EE.estacion_id = MH.estacion_id
                AND EE.departamento=DPTO.departamento
                AND  EE.departamento IN (SELECT departamento 
                                        FROM userpermisos_cuentas 
                                        WHERE usuario_id='".UserGetUID()."')

                "; 
				
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      if(!$resultado = $this->ConexionBaseDatos($query))
				return false;
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;  
      while($datos=$resultado->FetchRow()){
        $vector[$datos[departamento]][$datos[nombre_departamento]][$datos[estacion_id]][$datos[descripcion]][$datos[ingreso]]=$datos;	        			
			}
			$resultado->Close();			
			return $vector;
		}
    
    
    /**********************************************************************************
    * Busca los pacientes con salida de urgencias en la estacion
    *
    * @access public     
    * @return array
    ***********************************************************************************/
    function ObtenerPacientesconSalidaUrgencias(){
      GLOBAL $ADODB_FETCH_MODE;
      $query = "SELECT 
                    A.*,
                          PU.estacion_id,
                          P.primer_nombre ||' '|| P.segundo_nombre ||' '|| P.primer_apellido ||' '|| P.segundo_apellido AS nombre,
                          EE.estacion_id,EE.descripcion,EE.departamento,DPTO.descripcion as nombre_departamento
                FROM 
                (
                    SELECT    A.ingreso, A.evolucion_id, A.sw_estado,
                              C.numerodecuenta,
                              D.estado,D.plan_id,D.rango,
                              TO_CHAR(D.fecha_registro, 'DD/MM/YYYY') AS fecha,
                              PL.plan_descripcion,
                              I.tipo_id_paciente, I.paciente_id, I.fecha_ingreso
                    
                    FROM   
                              hc_ordenes_medicas AS A
                              RIGHT JOIN hc_vistosok_salida_detalle AS B ON (A.ingreso = B.ingreso AND A.evolucion_id = B.evolucion_id),
                              hc_evoluciones AS C,
                              cuentas AS D,
                              planes AS PL,
                              ingresos AS I
                              
                    WHERE A.sw_estado = '1'
                    AND   A.hc_tipo_orden_medica_id IN ('99','06','07')
                    AND   A.ingreso = C.ingreso
                    AND   A.evolucion_id = C.evolucion_id
                    AND   D.numerodecuenta = C.numerodecuenta
                    AND   D.estado = '1'
                    AND   D.plan_id=PL.plan_id
                    AND   A.ingreso = I.ingreso
                      
                ) AS A,
                pacientes_urgencias AS PU,
                pacientes AS P,
                estaciones_enfermeria AS EE,
                departamentos DPTO          
                WHERE
                  A.ingreso = PU.ingreso
                AND  P.paciente_id = A.paciente_id
                AND  P.tipo_id_paciente = A.tipo_id_paciente
                AND  EE.estacion_id = PU.estacion_id
                AND  EE.departamento=DPTO.departamento
                AND  PU.sw_estado IN ('0','1')
                AND  EE.departamento IN (SELECT departamento 
                                        FROM userpermisos_cuentas 
                                        WHERE usuario_id='".UserGetUID()."')

                ";           
      
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      if(!$resultado = $this->ConexionBaseDatos($query))
        return false;
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;  
      while($datos=$resultado->FetchRow()){
        $vector[$datos[departamento]][$datos[nombre_departamento]][$datos[estacion_id]][$datos[descripcion]][$datos[ingreso]]=$datos;               
      }
      $resultado->Close();      
      return $vector;
    }
    
     /**********************************************************************************
    * Busca los pacientes con salida de cirugia en la estacion
    *
    * @access public     
    * @return array
    ***********************************************************************************/
    function ObtenerPacientesconSalidaCirugia(){
      GLOBAL $ADODB_FETCH_MODE;
      $query = "SELECT 
                    A.*,
                          QX.programacion_id, QX.fecha_egreso,
                          P.primer_nombre ||' '|| P.segundo_nombre ||' '|| P.primer_apellido ||' '|| P.segundo_apellido AS nombre,
                          EE.estacion_id,EE.descripcion,EE.departamento,DPTO.descripcion as nombre_departamento
                          
                FROM 
                (
                    SELECT A.ingreso, A.evolucion_id, A.sw_estado,
                              C.numerodecuenta,
                              D.plan_id,D.rango,
                              TO_CHAR(D.fecha_registro, 'DD/MM/YYYY') AS fecha,
                              PL.plan_descripcion,
                              I.tipo_id_paciente, I.paciente_id, I.fecha_ingreso, D.estado
                    
                    FROM   
                              hc_ordenes_medicas AS A
                              RIGHT JOIN hc_vistosok_salida_detalle AS B ON (A.ingreso = B.ingreso AND A.evolucion_id = B.evolucion_id),
                              hc_evoluciones AS C,
                              cuentas AS D,
                              planes AS PL,
                              ingresos AS I
                              
                    WHERE A.sw_estado = '1'
                    AND   A.hc_tipo_orden_medica_id IN ('99','06','07')
                    AND   A.ingreso = C.ingreso
                    AND   A.evolucion_id = C.evolucion_id
                    AND   D.numerodecuenta = C.numerodecuenta
                    AND   D.estado = '1'
                    AND   D.plan_id=PL.plan_id
                    AND   A.ingreso = I.ingreso
                      
                ) AS A,
                estacion_enfermeria_qx_pacientes_ingresados AS QX,
                pacientes AS P,
                estaciones_enfermeria AS EE,
                departamentos DPTO                  
                
                WHERE
                A.numerodecuenta = QX.numerodecuenta
                AND  QX.fecha_egreso IS NULL
                AND  P.paciente_id = A.paciente_id
                AND  P.tipo_id_paciente = A.tipo_id_paciente
                AND  EE.departamento = QX.departamento
                AND  EE.departamento = DPTO.departamento
                AND  EE.departamento IN (SELECT departamento 
                                        FROM userpermisos_cuentas 
                                        WHERE usuario_id='".UserGetUID()."')
                ";           
      
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      if(!$resultado = $this->ConexionBaseDatos($query))
        return false;
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;  
      while($datos=$resultado->FetchRow()){
        $vector[$datos[departamento]][$datos[nombre_departamento]][$datos[estacion_id]][$datos[descripcion]][$datos[ingreso]]=$datos;               
      }
      $resultado->Close();      
      return $vector;
    }      
		
		
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		*
    * @access public  
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}    
   
	}
?>