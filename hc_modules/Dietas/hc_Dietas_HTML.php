<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: hc_Dietas_HTML.php,v 1.1 2009/02/02 16:32:31 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : Dietas_HTML
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Dietas_HTML extends Dietas
  {
  	/**
  	*	Constructor de la clase
  	*/
		function Dietas_HTML()
		{
      $this->Dietas();
		}
    /**
    *
    */
		function GetForma()
		{
      $file ='hc_modules/Dietas/RemoteXajax/Dietas.php';
      $this->SetXajax(array("SeleccionarTipoDieta"),$file);

      $request = $_REQUEST;
      $pfj = $this->frmPrefijo;
      $dts = AutoCarga::factory("DietasSql","classes","hc1","Dietas");
      $mdl = AutoCarga::factory("DietasHTML","views","hc1","Dietas");
      
      $dieta = $dts->ObtenerDietasPaciente($this->evolucion);
            
      if(!empty($dieta) && !$request['subModuloAction'])
        $request['subModuloAction'] = "Detalle";
      
			switch ($request['subModuloAction'])
			{
				case 'Editar':
          $caract  = $dts->ObtenerDietasPaciente($this->evolucion);
          $control = $dts->ObtenerControlDietas();
          $viaOral = $dts->ObtenerNadaViaOral();
          
          $action['guardar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>"Actualizar"));
          $action['cancelar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);

          $this->salida = $mdl->FormaRegistroDietas($action,$control,$viaOral,$caract);

				break;
				case 'Eliminar':
          $rst = $dts->EliminarDieta($this->ingreso,$this->evolucion);
          $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          
          $mensaje = "EL REGISTRO DE LA DIETA SE HA ELIMINADO CORRECTAMENTE";
          if(!$rst)
            $mensaje = "HA OCURRIDO UN ERROR ".$dts->ErrMsg();
          
          $this->salida .= $mdl->FormaMensajeModulo($action,$mensaje);
				break;
        case 'Ingreso':
          $rst = $dts->IngresarDieta($this->ingreso,$this->evolucion,$this->usuario_id,$request);
          $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          
          $mensaje = "EL REGISTRO DE LA DIETA SE HA REALIZADO CORRECTAMENTE";
          if(!$rst)
            $mensaje = "HA OCURRIDO UN ERROR ".$dts->ErrMsg();
          else
            $this->RegistrarSubmodulo($this->GetVersion());
          
          $this->salida .= $mdl->FormaMensajeModulo($action,$mensaje);
        break;        
        case 'Actualizar':
          $rst = $dts->ActualizarDieta($this->ingreso,$this->evolucion,$this->usuario_id,$request);
          $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          
          $mensaje = "EL REGISTRO DE LA DIETA SE HA REALIZADO CORRECTAMENTE";
          if(!$rst)
            $mensaje = "HA OCURRIDO UN ERROR ".$dts->ErrMsg();
          else
            $this->RegistrarSubmodulo($this->GetVersion());
          
          $this->salida .= $mdl->FormaMensajeModulo($action,$mensaje);
        break;
        case 'Detalle':
          $action['editar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>"Editar"));
          $action['eliminar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>"Eliminar"));

          $dieta_d = $dts->ObtenerDietaspacienteDetalle($this->evolucion);
          $this->salida = $mdl->FormaDetalleDietas($action,$dieta,$dieta_d,$this->estado,$this->tipo_profesional);
        break;
        default :
          $control = $dts->ObtenerControlDietas();
          $viaOral = $dts->ObtenerNadaViaOral();
          
          $action['guardar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>"Ingreso"));
          $this->salida = $mdl->FormaRegistroDietas($action,$control,$viaOral);
				break;
			}
      return true;
		}
    /**
    *
    */
		function GetConsulta()
		{
      $dts = AutoCarga::factory("DietasSql","classes","hc1","Dietas");
      $mdl = AutoCarga::factory("DietasHTML","views","hc1","Dietas");
      
      $dieta = $dts->ObtenerDietasPaciente($this->evolucion);      
      $dieta_d = $dts->ObtenerDietaspacienteDetalle($this->evolucion);

			$this->salida = $mdl->FormaConsulta($dieta,$dieta_d);
			return $this->salida;
		}
    /**
    *
    */
		function GetReporte_Html()
		{
      $dts = AutoCarga::factory("DietasSql","classes","hc1","Dietas");
      $mdl = AutoCarga::factory("DietasHTML","views","hc1","Dietas");
      
      $dieta = $dts->ObtenerDietasPaciente($this->evolucion);      
      $dieta_d = $dts->ObtenerDietaspacienteDetalle($this->evolucion);
      $html .= $mdl->FormaHistoria($dieta,$detalle);

			return $html;
		}
    /**
    *
    */
    function GetEstado()
    {
      return true;
    }
  }
?>