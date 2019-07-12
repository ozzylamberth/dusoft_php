 <?php
	/********************************************************************************* 
 	* $Id: TranscripcionExamenes.class.php,v 1.2 2007/02/01 19:56:53 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   TranscripcionExamenes
	* 
 	**********************************************************************************/

	class TranscripcionExamenes
	{
		var $salida = "";
		
		function TranscripcionExamenes()
		{
			$this->cargo=$_REQUEST['cargo'];
			$this->descripcion=$_REQUEST['descripcion'];
			$this->periodo=$_REQUEST['periodo'];
			$this->accion=$_REQUEST['op'];
			$this->trans=$_REQUEST['trans'];
			$this->evolucion_id=$_REQUEST['evolucion_id'];
			return true;
		}
		
		function llamarTranscribir()
		{
			$apdC=new Apoyos_Diagnosticos_Control_1();
			$pfj=$apdC->frmPrefijo;

			$cargo=$this->cargo;
			$descripcion=$this->descripcion;
			$op=$this->accion;
			$periodo=$this->periodo;
			$evolucion=$this->evolucion_id;
			$trans=$this->trans;
			
			if($_REQUEST['tecnica_id'])
			{
				$tecnica=$_REQUEST['tecnica_id'];
				$evolucion=$_REQUEST['evolucion_id'];
			}
			else
				$tecnica=0;
			
			$datos="cargo=".$cargo."&descripcion=".$descripcion."&op=".$op."&periodo=".$periodo."&evolucion=".$evolucion."&trans=".$trans."&accionM=1&pfj=".$apdC->frmPrefijo;
			$url = "TranscripcionExamenes.class.php?$datos";
			$scripts = "<script language=\"javascript\" src=\"../../javascripts/jsrsClient.js\"></script>\n";
			$scripts .= "<script language=\"javascript\">\n";
			$scripts .= "	function EnviarTecnica(forma,evolucion)\n";
			$scripts .= "	{\n";
			$scripts .= "		var tecnica=forma.selector_multitecnica$pfj.value;\n";
			$scripts .= "		forma.action = '$url' + '&tecnica_id='+tecnica+ '&evolucion_id='+evolucion;\n";
			$scripts .= "		forma.submit();\n";
			$scripts .= "	}\n";
			$scripts .= "	function EnviarDatosT(formas,info)\n";
			$scripts .= "	{\n";
			$scripts .= "		formas.action = '$url'+'&datos[0]='+info[0]+'&datos[1]='+info[1]+'&datos[2]='+info[2]+'&datos[3]='+info[3]+'&datos[4]='+info[4]+'&datos[5]='+info[5];\n";
			$scripts .= "		formas.submit();\n";
			$scripts .= "	}\n";
			$scripts .= "</script>\n";
			$this->salida .=ReturnHeader("Transcripcion de Examenes",$scripts);
			$this->salida .=ReturnBody();
			$this->salida .= $apdC->GetForma($cargo,$descripcion,$op,$periodo,$trans,$tecnica,$evolucion);
			$this->salida .= "</body></html>";
		}
		
		function IngresoTranscribir()
		{
			$k=0;
			$e=0;
			$apdC=new APDControl();
			$pfj=$_REQUEST['pfj'];
			$fecha = $_REQUEST['fecha_realizado'.$pfj.''];
			$subindice = $_REQUEST['items'.$k.$pfj];
			$mensaje="";
			
			if(empty($fecha))
			{
					$mensaje="<center><label class=\"label_error\">FALTAN DATOS OBLIGATORIOS, SELECCIONE UNA FECHA</label></center>";
					echo $mensaje;
					$this->llamarTranscribir();
			}
			elseif(!$_REQUEST['items'.$k.$pfj])
			{
					$mensaje= "<center><label class=\"label_error\"> CAMPOS DE RESULTADO INEXISTENTES </label></center>";
					echo $mensaje;
					$this->llamarTranscribir();
			}
			else
			{
				for ($i=0; $i< $subindice; $i++)
				{
					if (empty($_REQUEST['resultado'.$k.$i.$pfj]) && ($_REQUEST['resultado'.$k.$i.$pfj] === '' or $_REQUEST['resultado'.$k.$i.$pfj] == -1))
					{
						$mensaje= "<center><label class=\"label_error\">FALTAN DATOS OBLIGATORIOS, DEBE ESCRIBIR UN RESULTADO</label></center>";
						echo $mensaje;
						$this->llamarTranscribir();
					}
				}
			}
			
			if(!$mensaje)
			{
				$resultado_id=$apdC->Insertar($_REQUEST);
				if($resultado_id)
				{
					echo "<center>DATOS INSERTADOS</center>";
					$datos="resultado_id=".$resultado_id."&sw_modo=3";
					$url="classes/Visualizar/Visualizar.class.php?".$datos;
					
					$this->salida .= "<html>\n";
					$this->salida .= "	<head>\n";
					$this->salida .= "	</head>\n";
					$this->salida .= "	<body>\n";
					$this->salida .= "		<script>";
					if($_REQUEST['sw_patologico'.$k.$e.$pfj])
						$this->salida .= "			window.opener.document.getElementById('".$this->trans."').innerHTML=\"<a href=javascript:AbrirVentanaVer('$url')><b>Ver</b></a>\";\n";
					else
						$this->salida .= "			window.opener.document.getElementById('".$this->trans."').innerHTML=\"<a href=javascript:AbrirVentanaVer('$url')>Ver</a>\";\n";
					$this->salida .= "			window.close();\n";
					$this->salida .= "		</script>\n";
					$this->salida .= "	</body>\n";
					$this->salida .= "</html>\n";
				}
				else
				{
					echo "<center><label class=\"label_error\">".$apdC->error." <br>".$apdC->mensajeDeError."</label></center>";
					$this->llamarTranscribir();
				}
			}
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
	
	$trans=new TranscripcionExamenes();
	
	if(!$_REQUEST['accionM'])
		$trans->llamarTranscribir();
	else 
		if($_REQUEST['tecnica_id'])
				$trans->llamarTranscribir();
		else
				$trans->IngresoTranscribir();
					
	echo $trans->salida;
	
?>