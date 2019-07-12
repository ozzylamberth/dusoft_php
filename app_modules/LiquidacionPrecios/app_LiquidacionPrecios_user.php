<?php

/**
* @package IPSOFT-SIIS
*
* @author    Mauricio Bejarano L. 
* @version   $Revision: 1.5 $
* @package   LiquidacionPrecios
* 
*/

IncludeClass("LiquidacionPrecios");
class app_LiquidacionPrecios_user extends classModulo
{
	function app_LiquidacionPrecios_user()
	{
			$this->limit=GetLimitBrowser();
			return true;
	}

	/**
	* La funcion main es la principal y donde se llama FormaPrincipal
	* @access public
	* @return boolean
	*/
	function main()
	{
		
			if(!$this->LiquidaPrecios())
			{
					return false;
			}
			return true;
	}
	
	/**
	* Muestra en rojo el campo donde se presento el error con su descripcion
	* @param  SetStyle($campo): $campo en el campo en donde se presento el error
	* @return lebel del error
	*/
   function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' print_r(align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}
/*******************************************************************/   

   /**
	* Cambia el formato de la fecha de dd/mm/YY a YY/mm/dd
	* @access private
	* @return string
	* @param date fecha
	* @var 	  cad	Cadena con el nuevo formato de la fecha
	*/
	function ConvFecha($fecha)
	{	
		if($fecha){
			$fech = strtok ($fecha,"-");
			for($i=0;$i<3;$i++)
			{
				$date[$i]=$fech;
				$fech = strtok ("-");
			}
			$cad = $date[2]."-".$date[1]."-".$date[0];
			return $cad;
		}
    }/**/
/*******************************************************************/   
   /**
	* Separa la Fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}

				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
    }/**/

/*******************************************************************/	
	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
    {
    	$hor = strtok ($hora," ");
    	for($l=0;$l<4;$l++)
    	{
    	  $time[$l]=$hor;
    	  $hor = strtok (":");
    	}
		$x=explode('.',$time[3]);
    	return  $time[1].":".$time[2].":".$x[0];
   }/**/
	 
	 


	/**
		* @access private
		* @return boolean
		*/
		function LiquidaPrecios()
		{
			unset($_SESSION['LIQ_PRECIOS']['RESULT']);
			$this->Consultar_Cumplimiento();
			return true;
		}
		/**
		*
		*/
		function BuscaDatos()
		{
			$plan_id= $_REQUEST['plan'];
			$tarifario_id= '';
			$cargo= '';
			$grupo_tarifario_id= $_REQUEST['grupo_tarifario'] ;
			$subgrupo_tarifario_id=  $_REQUEST['subgrupo_tarifario'];
			$grupo_tipo_cargo= $_REQUEST['grupo_tipo_cargo'] ;
			$tipo_cargo= $_REQUEST['tipo_cargo'] ;
			$_REQUEST['mostrar']=$_REQUEST['mostrar'];
			$Liq_precios = new LiquidacionPrecios();
			//$a='tarifario_id,cargo,descripcion,(ROUND (precio + (precio * porcentaje /100),get_digitos_redondeo())) as valor,descripcion_corta,gravamen,por_cobertura';
            $a='tarifario_id,cargo,descripcion,(ROUND (precio + (precio * porcentaje /100),get_digitos_redondeo())) as valor,gravamen,por_cobertura';
			//$a='cargo,descripcion';
			$Liq_precios-> PlanTarifario($plan_id, $tarifario_id, $cargo, $grupo_tarifario_id, $subgrupo_tarifario_id, $grupo_tipo_cargo, $tipo_cargo,'',$a);
			$this->Consultar_Cumplimiento();
			return true;
		}
		/**
		* Consulta Plan
		*/
		function Plan()
		{
			$query="SELECT plan_id,plan_descripcion
							FROM	planes
							WHERE estado = '1'
							ORDER BY plan_descripcion";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta plan";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			
			return $vector;
		}
		
		/**
		* Consulta Grupo Tarifario
		*/
		function Gtarifario()
		{
			$query="SELECT 	grupo_tarifario_id, grupo_tarifario_descripcion
							FROM		grupos_tarifarios";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta Gtarifario";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			return $vector;
		}
		/**
		* Consulta SubGrupo Tarifario
		*/
		function SubGtarifario($gtarifario)
		{
			$query="SELECT	subgrupo_tarifario_id, subgrupo_tarifario_descripcion
							FROM		subgrupos_tarifarios
							WHERE		grupo_tarifario_id = '$gtarifario'";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta SubGtarifario";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			return $vector;
		}


		
		/**
		* Consulta SubGrupo Tarifario
		*/
		function GrupoTipoCargo()
		{
			$query="SELECT	grupo_tipo_cargo, descripcion
							FROM		grupos_tipos_cargo";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta GrupoTipoCargo";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			return $vector;
		}
		
		
				/**
		* Consulta SubGrupo Tarifario
		*/
		function TipoCargo($gtipocargo)
		{
			$query="SELECT	tipo_cargo,descripcion
							FROM		tipos_cargos
							WHERE		grupo_tipo_cargo='$gtipocargo'";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consultar TipoCargo";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			return $vector;
		}
		
		/**
		 * Liquida insumos y medicamentos
		 *
		 * @param string producto
		 * @param string empresa_id
		 * @param integer cantidad
		 * @param array datosAdicionales
		 * @return array
		 */
		function LiquidarInsumosMedicamentos($producto, $empresa_id=NULL, $cantidad=1, $datosAdicionales)
		{
			if (!IncludeClass("LiquidacionCargosInventario"))
			{
				$this->error = "Liquidar insumos y medicamentos";
				$this->mensajeDeError = "NO SE PUDO INCLUIR LA CLASE LiquidacionCargosInventario"; 
				return false;
			}
			$LiquidacionCargos = new LiquidacionCargosInventario;
			$retorno = $LiquidacionCargos->GetLiquidacionProducto($producto,$empresa_id,$cantidad,$datosAdicionales);
			if($retorno === false)
			{
				$this->error = $LiquidacionCargos->Err();
				$this->mensajeDeError = $LiquidacionCargos->ErrMsg();
				return false;
			}
			return $retorno;
		}//Fin LiquidarInsumosMedicamentos
}//fin clase user

?>
