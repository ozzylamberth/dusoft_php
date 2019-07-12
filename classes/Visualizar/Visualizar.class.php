 <?php
	/********************************************************************************* 
 	* $Id: Visualizar.class.php,v 1.1 2006/12/07 21:25:52 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   TranscripcionExamenes
	* 
 	**********************************************************************************/

	class Visualizar
	{
		var $salida = "";
		
		function Visualizar()
		{
			$this->resultado_id=$_REQUEST['resultado_id'];
			$this->sw_modo=$_REQUEST['sw_modo'];
			return true;
		}
		
		function llamarVer()
		{
						
			$apdC=new APDControl();
			$apdC_html=new APDControl_HTML();
			
			$resultado_id=$this->resultado_id;
			$sw_modo_resultado=$this->sw_modo;

			$examenes=$apdC->ConsultaExamenesPaciente($resultado_id, $sw_modo_resultado);

			$registro=$apdC->RegistroLecturas($resultado_id);
			$vector = $apdC->ConsultaDetalle($resultado_id);
			$observaciones=$apdC->ConsultaObservaciones($resultado_id);

			$this->salida .=ReturnHeader("Ver Examenes");
			$this->salida .=ReturnBody();
			$this->salida .=$apdC_html->Consulta_Resultados($resultado_id, $sw_modo_resultado,$examenes,$registro,$vector,$observaciones,$evolucion_id);	

			$this->salida .= "</body></html>";
		}
		
	}		
	$VISTA = "HTML";
	$_ROOT = "../../";
	include  "../../classes/rs_server/rs_server.class.php";
	include	 "../../includes/enviroment.inc.php";
	include	 "../../classes/modules/hc_classmodules.class.php";
	$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
	IncludeFile($filename);
	include	 "../../hc_modules/Apoyos_Diagnosticos_Control/hc_Apoyos_Diagnosticos_Control_1.php";
	
	$ver=new Visualizar();
	$ver->llamarVer();
	echo $ver->salida;
?>