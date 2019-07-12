<?php
  /******************************************************************************
  * $Id: LiquidacionHabitacionesCta.class.php,v 1.2 2006/12/11 13:15:22 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Lorena Arago Galindo
  * Proposito del Archivo:	Manejo de la logica del proceso de liquidacion de Habitaciones 
  ********************************************************************************/
	class LiquidacionHabitacionesCta
	{
		var $offset = 0;
		
		function LiquidacionHabitacionesCta(){}
		
    /**********************************************************************************
		* Busca los tipos de camas contratados en el plan.
    *
    * @access public 
		* @params int $plan plan del acuenta del usuario
		* @return array
		***********************************************************************************/
		function ObtenerTiposCamasPlan($plan){
    
		  $query = "SELECT b.descripcion, a.cargo, a.tarifario_id, a.cargo_cups, 
                       a.tipo_cama_id,a.porcentaje, a.valor_lista, 
                       a.valor_excedente, c.descripcion as descar, c.precio
                FROM  planes_tipos_camas as a, tipos_camas as b, tarifarios_detalle as c
                WHERE a.plan_id=$plan AND a.tipo_cama_id=b.tipo_cama_id AND
                      a.cargo=c.cargo and a.tarifario_id=c.tarifario_id";           
			
      if(!$resultado = $this->ConexionBaseDatos($query))
				return false;
        		
			while (!$resultado->EOF){
				$camas[] = $resultado->GetRowAssoc($ToUpper = false);				
				$resultado->MoveNext();
			}
			$resultado->Close();			
			return $camas;
		}
    
    /**********************************************************************************
    * Busca los departamentos asistenciales en la empresa.
    *
    * @access public 
    * @params varchar $EmpresaId donde el usuario se encuentra logueado
    * @return array
    ***********************************************************************************/
    
    function ObtenerDepartamentosHabitaciones($EmpresaId){
          
      $query = "SELECT a.departamento,a.descripcion, a.servicio
                FROM departamentos as a, servicios as b 
                WHERE a.empresa_id='$EmpresaId' 
                AND a.servicio=b.servicio 
                AND b.sw_asistencial='1'";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;          
      while (!$resultado->EOF){
        $dptos[] = $resultado->GetRowAssoc($ToUpper = false);       
        $resultado->MoveNext();
      }
      $resultado->Close();      
      return $dptos;
    }
    
    /**********************************************************************************
    * Elimina un cargo del vector de los cargos de habitaciones y realiza un ordenamiento de las pocisiones.
    *
    * @access public 
    * @params int $posicion pocision dentro del vector del cargo a eliminar 
    * @return boolean
    ***********************************************************************************/
    
    function EliminarCargoHabitacionVector($posicion){
      unset($_SESSION['LIQUIDACION_HABITACIONES'][$posicion]);        
      for($i=$posicion+1;$i<=sizeof($_SESSION['LIQUIDACION_HABITACIONES']);$i++){        
        $_SESSION['LIQUIDACION_HABITACIONES'][$i-1]=$_SESSION['LIQUIDACION_HABITACIONES'][$i];
        unset($_SESSION['LIQUIDACION_HABITACIONES'][$i]);   
      }        
      return true;    
    }
    
    /**********************************************************************************
    * Modificar un cargo del vector de los cargos de habitaciones.
    *
    * @access public 
    * @params array $precio_plan vector de los precios traidos del request
    * @params array $dias vector de los cantidades de dias traidos del request 
    * @params array $excedente vector de los excedentes traidos del request
    * @params array $cub vector de los valores cubiertos traidos del request
    * @params array $noCub vector de los valores no cubiertos traidos del request
    * @return boolean
    ***********************************************************************************/
        
    function ModificarCargoHabitacionVector($precio_plan,$dias,$excedente,$cub,$noCub){
      
      foreach($precio_plan as $posicion => $valor){
        
        $_SESSION['LIQUIDACION_HABITACIONES'][$posicion]['precio_plan']=$valor;        
        $_SESSION['LIQUIDACION_HABITACIONES'][$posicion]['cantidad']=$dias[$posicion];       
        $_SESSION['LIQUIDACION_HABITACIONES'][$posicion]['valor_cubierto']=$_SESSION['LIQUIDACION_HABITACIONES'][$posicion]['precio_plan'];              
        $_SESSION['LIQUIDACION_HABITACIONES'][$posicion]['excedente']=$excedente[$posicion];
        $_SESSION['LIQUIDACION_HABITACIONES'][$posicion]['valor_no_cubierto']=$excedente[$posicion];
      }         
      //----revisa q la suma de valor cubierto y no cubierto de el valor del cargo
      //hay q multiplicar val cubierto y no cubierto por la cantidad
      
      for($i=0; $i<sizeof($_SESSION['LIQUIDACION_HABITACIONES']); $i++){                        
        $_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_no_cubierto']=$_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_no_cubierto']*$_SESSION['LIQUIDACION_HABITACIONES'][$i]['cantidad'];
        $_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_cubierto']=$_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_cubierto']*$_SESSION['LIQUIDACION_HABITACIONES'][$i]['cantidad'];
        $_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_cargo']=$_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_no_cubierto']+$_SESSION['LIQUIDACION_HABITACIONES'][$i]['valor_cubierto'];        
      }      
      return true;
    }
    
    /**********************************************************************************
    * Insertar cargo de habitacion en el vector de los cargos de movimientos de habitaciones.
    *
    * @access public 
    * @params varchar $tipocama tipo cama que ocupa el paciente
    * @params varchar $dpto parametro que especifica el departamento de la cama
    * @params float $precioN valor de la cama por dia
    * @params int $diasN cantidad de dias que ocupo la cama
    * @params float $noCubN valor no cubierto en el valor del cargo
    * @params boolean $copago indica si existe copago en el cargo
    * @return boolean
    ***********************************************************************************/
     
    
    function InsertarCargoHabitacionVector($tipocama,$dpto,$precioN,$diasN,$noCubN,$copago){
          
      $cubN=$precioN*$diasN;
      $excedente=$noCubN;
      $noCubN=$noCubN*$diasN;
      $valcargo=$cubN+$noCubN;      
      $v = explode('||',$tipocama);
      $cups=$v[7];
      $cargo=$v[6];
      $tarifario=$v[5];
      $copago=0;
      if(!empty($copago)){
        $copago=1; 
      }      
      $d = explode('||',$dpto);
      $servicio=$d[1];
      $dpto=$d[0];
      $_SESSION['LIQUIDACION_HABITACIONES'][]=array('servicio'=>$servicio,'departamento'=>$dpto,'valor_no_cubierto'=>$noCubN,'valor_cubierto'=>$cubN,'precio_plan'=>$precioN,'valor_cargo'=>$valcargo,'cargo_cups'=>$cups,'cantidad'=>$diasN,'cargo'=>$cargo,'tarifario_id'=>$tarifario,'facturado'=>1,'sw_cuota_paciente'=>$copago,'sw_cuota_moderadora'=>0,'porcentaje_gravamen'=>0,'descripcion'=>$v[8],'valor_descuento_empresa'=>0,'valor_descuento_paciente'=>0,'excedente'=>$excedente);      
      return true;
    }
    
    /**********************************************************************************
    * Insertar cargos de movimoentos de habitacion en la cuenta.
    *
    * @access public 
    * @params varchar $EmpresaId Empresa a la que pertenece la cuenta
    * @params int $Cuenta numero de la cuenta donde se insertaran los cargos
    * @params varchar $TipoId tipo de identificacion del paciente
    * @params varchar $PacienteId identificacion del paciente
    * @params int $PlanId plan que tiene la cuenta
    * @params varchar $Nivel indical el nivel de afiliacion del paciente
    * @params date $Fecha fecha que indica cuando se creo el cargo 
    * @params int $Ingreso numero del Ingreso asignado al paciente
    * @return boolean
    ***********************************************************************************/
     
    
    function CargarHabitacionCuenta($EmpresaId,$Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso){
      
      IncludeLib('funciones_facturacion'); 
      
      list($dbconn)=GetDBConn();     
      $dbconn->BeginTrans();
      
      $query = "UPDATE cuentas 
                SET sw_liquidacion_manual_habitaciones='1' 
                WHERE numerodecuenta=".$Cuenta."";
      $dbconn->Execute($query);       
      if ($dbconn->ErrorNo() != 0){      
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
        echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
        $dbconn->RollbackTrans();
        return false;
      }else{
        $query = "INSERT INTO auditoria_liquidacion_manual_habitaciones(
                  numerodecuenta,
                  usuario_id,
                  fecha_registro)VALUES(
                  ".$Cuenta.",".UserGetUID().",'".date("Y-m-d H:i:s")."')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){      
          $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
          echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
          $dbconn->RollbackTrans();
          return false;
        }
        $val = CargarHabitacionCuenta('',$_SESSION['LIQUIDACION_HABITACIONES'],true,&$dbconn,$EmpresaId,$Cuenta,3);
        if(empty($val)){          
          return false;
        }  
      } 
      unset($_SESSION['LIQUIDACION_HABITACIONES']);    
      $dbconn->CommitTrans();      
      return true;
    }
    
    /**********************************************************************************
    * Funcion que borra los cargos de movimientos de habitacion del vector de session
    *
    * @access public     
    * @return boolean
    ***********************************************************************************/
     
    
    function CancelarCargueHabitacionCuenta(){
      unset($_SESSION['LIQUIDACION_HABITACIONES']); 
      return true;   
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