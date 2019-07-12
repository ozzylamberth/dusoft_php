<?php

/**
* Submodulo de InscripcionCPN.
* $Id: hc_DatosRecienNacidos.php,v 1.2 2007/02/01 20:44:46 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/
IncludeClass("Nacidos",null,"hc","DatosRecienNacidos");
IncludeClass("Nacidos_HTML","html","hc","DatosRecienNacidos");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");

class DatosRecienNacidos extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/
	
	var $num=0;
	
	function DatosRecienNacidos()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

		/**
		* Esta función retorna los datos de concernientes a la version del submodulo
		* @access private
		*/
	
		function GetVersion()
		{
			$informacion=array(
			'version'=>'1',
			'subversion'=>'0',
			'revision'=>'0',
			'fecha'=>'30/06/2006',
			'autor'=>'LUIS ALEJANDRO VARGAS',
			'descripcion_cambio' => '',
			'requiere_sql' => false,
			'requerimientos_adicionales' => '',
			'version_kernel' => '1.0'
			);
			return $informacion;
		}
	
	
		/**
		* Esta función retorna los datos de la impresión de la consulta del submodulo.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
		function GetConsulta()
		{
			$nacidos_html=new Nacidos_HTML($this);
			if($nacidos_html->frmConsulta()==false)
			{
				return true;
			}
			return $nacidos_html->salida;
		}
			
		/**
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
	
		function GetReporte_Html()
		{
			$nacidos_html=new Nacidos_HTML($this);
			$imprimir=$nacidos_html->frmHistoria();
			if($imprimir==false)
			{
				return true;
			}
			return $imprimir;
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
	
	function GetForma()
	{

		$nacidos=new Nacidos();
		$nacidos_html=new Nacidos_HTML();
		$riesgo=new RiesgoBS();
		
		$pfj=SessionGetVar("Prefijo");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		$evolucion=SessionGetVar("Evolucion");
		
		$fum=$riesgo->GetDatofum($inscripcion);
		$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum[0][fecha_ultimo_periodo]));

		if($_REQUEST['guardar'.$pfj])
		{
			$datos=null;
			$desc=null;
			//---------------------------------
			if(empty($_REQUEST['nom_madre'.$pfj]))
			{
				$desc[]='nom_madre';
			}
			
			if(empty($_REQUEST['nom_rn'.$pfj]))
			{
				$desc[]='nom_rn';
			}
			
			if(empty($_REQUEST['nom_padre'.$pfj]))
			{
				$desc[]='nom_padre';
			}
				
			if(empty($_REQUEST['sexo'.$pfj]))
			{
				$desc[]='sexo';
			}
			
			if($_REQUEST['horas_traslado'.$pfj]==-1)
			{
				$h1=$_REQUEST['horas_traslado'.$pfj]+1;
			}
			else
			{
				$h1=$_REQUEST['horas_traslado'.$pfj];
			}
			
			if($_REQUEST['horas_fallece'.$pfj]==-1)
			{
				$h2=$_REQUEST['horas_fallece'.$pfj]+1;
			}
			else
			{
				$h2=$_REQUEST['horas_fallece'.$pfj];
			}
			
			$d1=$_REQUEST['dias_traslado'.$pfj];
			$d2=$_REQUEST['dias_fallece'.$pfj];
						
			$time1=date("Y-m-d h:m",(time()-($d1*$h1*60*60)));
			$time2=date("Y-m-d h:m",(time()-($d2*$h2*60*60)));
			
			if(sizeof($desc)>0)
			{
				for($i=0;$i<sizeof($desc);$i++)
				{
					$nacidos_html->frmError[$desc[$i]]=1;
					$nacidos_html->frmError["MensajeError"]="LOS DATOS MARCADOS EN 'ROJO' SON OBLIGATORIOS";
					$nacidos_html->ban=1;	
				}
			}
			else
			{
				$datos[0]=$_REQUEST['num_hc'.$pfj];
				$datos[1]=strtoupper($_REQUEST['nom_madre'.$pfj]);
				$datos[2]=strtoupper($_REQUEST['nom_rn'.$pfj]);
				$datos[3]=strtoupper($_REQUEST['nom_padre'.$pfj]);
				$datos[4]=$_REQUEST['sexo'.$pfj];
				$datos[5]=$_REQUEST['peso_nacer'.$pfj];
				$datos[6]=$_REQUEST['talla'.$pfj];
				$datos[7]=$_REQUEST['percef'.$pfj];
				$datos[8]=$_REQUEST['grupo_san'.$pfj];
				$datos[9]=$_REQUEST['rh'.$pfj];
				$datos[10]=$_REQUEST['vdrl'.$pfj];
				$datos[11]=$_REQUEST['tsh'.$pfj];
				$datos[12]=$_REQUEST['sw_bcg'.$pfj];
				$datos[13]=$_REQUEST['sw_hepatitis'.$pfj];
				$datos[14]=$_REQUEST['sw_polio'.$pfj];
				$datos[15]=$_REQUEST['sw_vitk'.$pfj];
				$datos[16]=$_REQUEST['peso_eg'.$pfj];
				$datos[17]=$_REQUEST['edad'.$pfj];
				$datos[18]=$_REQUEST['tipo_egreso'.$pfj];
				$datos[19]=$_REQUEST['sw_rn_madre'.$pfj];
				$datos[20]=$time1;
				$datos[21]=$time2;
				$datos[22]=$_REQUEST['alimentacion'.$pfj];
				$datos[23]=$_REQUEST['sw_muerte_materno'.$pfj];
				$datos[24]=$_REQUEST['muerte_materno'.$pfj];
	
				for($i=0;$i<sizeof($datos);$i++)
				{
					if(empty($datos[$i]))
					{
						$datos[$i]='0';
					}
				}
				
				if($nacidos->GuardarDatosNacidos($inscripcion,$evolucion,$datos))
				{
					$nacidos_html->frmError["MensajeError"]="REGISTROS GUARDADOS EXITOSAMENTE";
					$nacidos_html->ban=1;
					$nacidos_html->req=1;
				}
				else
				{
					$nacidos_html->frmError["MensajeError"]=$nacidos->ErrorDB();
					$nacidos_html->ban=1;	
				}
			}
		}
		SessionDelVar("rn");
		$num_hijos=$nacidos->NumeroHijos($inscripcion,$evolucion);
	
		if($num_hijos[0]==$num_hijos[1]+1)
					SessionSetVar("rn",1);
					
		if(!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn") OR !SessionGetVar("rn"))
		{
			return $nacidos_html->frmForma($num_hijos[0]);
		}
		elseif(SessionGetVar("cierre_caso_$programa") OR !SessionGetVar("cpn"))
		{
			$datosR=$nacidos->ConsultaInformacion($inscripcion,$evolucion);
			return $nacidos_html->frmDatosConsultaNacidos($datosR);
		}
		return "";
		
	}
	
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			
			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
}
?>