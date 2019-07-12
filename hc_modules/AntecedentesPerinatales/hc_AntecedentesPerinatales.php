<?php
/**
* Submodulo de Antecedentes Perinatales (PHP).
*
* Submodulo para manejar la informacion de una madre mediante datos de parto y datos del recien nacido
* verificando su estado de salud en la madres en pre y post parto, al igual que la salud del recien nacido.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesPerinatales.php,v 1.3 2006/12/19 21:00:12 jgomez Exp $
*/

/**
* AntecedentesPerinatales_PHP
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del submodulo AntecedentesPerinatales, se extiende la clase AntecedentesPerinatales y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/




class AntecedentesPerinatales extends hc_classModules
{

	function AntecedentesPerinatales($evolucion)
	{
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
// 		'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}



	function GetConsulta()
	{
        $this->frmConsulta();
		    return $this->salida;
	}

	function GetForma()
	{
	if(empty($_REQUEST['accion']))
		{
	    $this->frmForma();
		}
		else
		{
			if($this->InsertDatos()==true)
			{
				$this->frmForma();
			}
		}
		return $this->salida;
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

  function ConsultaPerinatalParto()
	{
	 list($dbconn) = GetDBconn();
   $queryt = "select b.tipo,case embarazodes  when 1 then 'Si' else 'No' end,
									adaptacion,placenta,d.tipo,e.tipo,f.tipo,
									case atencionpren when 1 then 'Si' else 'No' end,
									case muertefetoc when 1 then 'Si' else 'No' end,
									c.tipo,causaparto,case patologiamadre when 1 then 'Si' else 'No' end, causapatologia,
									aleteo,tiaje,disbalance,quejido,retracciones,atencion,atencionr,g.tipo
									from hc_antecedentes_perinatales as a,hc_tipo_partos_establecimiento as b, hc_tipo_partos_parto as c,hc_tipo_partos_medicamentos as d,
									hc_tipo_partos_liquidoAmniotico as e,
									hc_tipo_partos_pinzamiento as f,
									hc_tipo_partos_presentacion as g
									where evolucion_id ='".$this->evolucion."' and a.partos_establecimiento_id=b.partos_establecimiento_id
									and a.partos_parto_id=c.partos_parto_id and
									a.partos_medicamentos_id=d.partos_medicamentos_id and a.partos_liquidoamniotico_id=e.partos_liquidoamniotico_id
									and a.partos_pinzamiento_id=f.partos_pinzamiento_id
									and a.partos_presentacion_id=g.partos_presentacion_id;";

				$result = $dbconn->Execute($queryt);
				if ($dbconn->ErrorNo() != 0)
				{
               return false;
				}
				return $result;

	}



 function ConsultaPerinatalnacido()
 {
  list($conn) = GetDBconn();
  $busca="select b.tipo,edadgest,peso,talla,perimetrocef,
														case diuresis when 1 then 'Si' else 'No' end,
														case deposicion when 1 then 'Si' else 'No' end,
														apgar1,apgar5,hc_antecedente_perinatal_id,
														case permeabilidadano when 1 then 'Si' else 'No' end,
														case permeabilidadesofago when 1 then 'Si' else 'No' end,
														d.tipo,c.tipo,e.tipo,f.tipo
														from hc_antecedentes_perinatales as a,hc_tipo_rnacidos_sufrimientofetal as b,
														hc_tipo_rnacidos_pesotallaegreso  as c ,hc_tipo_rnacidos_alimentacion as d,
														hc_tipo_rnacidos_egresomaterno as e,
														hc_tipo_rnacidos_muertematerna as f
														where evolucion_id='$this->evolucion' and a.nacido_sufrimientofetal_id=b.nacido_sufrimientofetal_id
														and
														a.nacido_pesotallaegreso_id=c.nacido_pesotallaegreso_id
														and
														a.nacido_alimentacion_id=d.nacido_alimentacion_id
														and
														a.nacido_egresomaterno_id=e.nacido_egresomaterno_id
														and
														a.nacido_muertematerna_id=f.nacido_muertematerna_id;";

										$resulta = $conn->Execute($busca);
                    if ($conn->ErrorNo() != 0)
			              {
                      return false;
                    }
		return $resulta;
 }


function ConsultaReanimacion($Identificador)
{
      list($link) = GetDBconn();
			$busqueda="select b.tipo
			from hc_aux_rnacidos_reanimacion as a,hc_tipo_rnacidos_reanimacion as b where
			hc_antecedente_perinatal_id='$Identificador' and
			a.tipo_nacido_reanimacion_id=b.tipo_nacido_reanimacion_id;";
			$res = $link->Execute($busqueda);
			if ($link->ErrorNo() != 0)
			              {
                      return false;
                    }
			return $res;
}



function ComboPartosEstablecimientos()
{
 list($dbconn) = GetDBconn();
 $query = "SELECT partos_establecimiento_id,tipo FROM hc_tipo_partos_establecimiento";
 $establecimiento = $dbconn->Execute($query);
 if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $establecimiento;
}

function ComboPartosParto()
{
  list($dbconn) = GetDBconn();
 	$query1 = "SELECT partos_parto_id,tipo FROM hc_tipo_partos_parto";
	$parto=$dbconn->Execute($query1);
	if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $parto;
}


function ComboPartosMedicamentos()
{
 list($dbconn) = GetDBconn();
 $query2 = "SELECT partos_medicamentos_id,tipo FROM hc_tipo_partos_medicamentos";
 $medicamentos=$dbconn->Execute($query2);
 if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $medicamentos;

}

function ComboLiquidoAmniotico()
{
  list($dbconn) = GetDBconn();
 	$query3 = "SELECT partos_liquidoAmniotico_id,tipo FROM hc_tipo_partos_liquidoAmniotico";
	$liquido=$dbconn->Execute($query3);
	if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $liquido;


}


function ComboPartosPresentacion()
{
  list($dbconn) = GetDBconn();
 	$query4 = "SELECT partos_presentacion_id,tipo FROM hc_tipo_partos_presentacion";
	$presentacion=$dbconn->Execute($query4);
	if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $presentacion;

}


function ComboPartosPinzamiento()
{
  list($dbconn) = GetDBconn();
  $queryx = "SELECT partos_pinzamiento_id,tipo FROM hc_tipo_partos_pinzamiento";
  $pinzamiento = $dbconn->Execute($queryx);
	if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $pinzamiento;

}



function ComboRnacidoReanimacion()
{
  list($dbconn) = GetDBconn();
	$query5 = "SELECT tipo_nacido_reanimacion_id,tipo FROM hc_tipo_Rnacidos_reanimacion";
	$reanimacion = $dbconn->Execute($query5);
  if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $reanimacion;
}


function ComboRnacidosAlimentacion()
{
  list($dbconn) = GetDBconn();
	$query6 = "SELECT nacido_alimentacion_id,tipo FROM hc_tipo_Rnacidos_alimentacion";
	$alimentacion = $dbconn->Execute($query6);
	if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $alimentacion;

}


function ComboRnacidosEgreso()
{
 list($dbconn) = GetDBconn();
 $query7 = "SELECT nacido_egresomaterno_id,tipo FROM hc_tipo_Rnacidos_egresomaterno";
 $egreso = $dbconn->Execute($query7);
 if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $egreso;

}


function ComboRnacidosMuerte()
{
 list($dbconn) = GetDBconn();
 $query8 = "SELECT nacido_muertematerna_id,tipo FROM hc_tipo_Rnacidos_muertematerna";
 $muertem = $dbconn->Execute($query8);
 if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $muertem;

}

function ComboRnacidosSufrimientoFetal()
{
 list($dbconn) = GetDBconn();
 $query9 = "SELECT nacido_sufrimientofetal_id,tipo FROM hc_tipo_Rnacidos_sufrimientofetal";
 $fetal = $dbconn->Execute($query9);
 if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $fetal;
}

function ComboRnacidosPesoTallaE()
{
  list($dbconn) = GetDBconn();
	$queryy = "SELECT nacido_pesotallaegreso_id,tipo FROM hc_tipo_Rnacidos_pesotallaegreso";
  $pesotallaeg = $dbconn->Execute($queryy);
	if ($dbconn->ErrorNo() != 0)
			              {
                      return false;
                    }
 return $pesotallaeg;

}


  /**
* Funcion que inserta los datos de la madre en el parto y del recien nacido, mediante la clase de AntecedentesPerinatales
* @return boolean
*/

	function InsertDatos()
{   $pfj=$this->frmPrefijo;
	    list($dbconn) = GetDBconn();
     $busquin="SELECT evolucion_id from hc_antecedentes_perinatales where evolucion_id=".$this->evolucion."";
      $recordS=$dbconn->Execute($busquin);
      $cuenta=$recordS->RecordCount();

      if((!empty($cuenta)) or $cuenta!=0)
       {
         	//$this->error="La evolución de este paciente ya existe.";
					$this->frmError["MensajeError"]='LA EVOLUCION YA EXISTE.';
						if(!$this->frmForma($_REQUEST['apgar1'.$pfj])){
						return true;
					}

       }else{


      $query="select nextval('hc_antecedente_perinatal_id_seq');";
      $r=$dbconn->Execute($query);
			$identifier=$r->fields[0];


			$sql1="SELECT tipo_nacido_reanimacion_id  from  hc_tipo_Rnacidos_reanimacion";
			list($conn) = GetDBconn();
			$rs=$conn->Execute($sql1);
			$conteo=$rs->RecordCount();
  		$sql1="";
			$sql="";

      $patologia="patologia". $pfj;
			$parto="parto". $pfj;
			$causapato="causapato". $pfj;
      $causaparto="causaparto". $pfj;

			if ($_REQUEST[$parto]==1){
			$normal=1;
			$causaparto="";
			}
			else
			{
			$normal=$_REQUEST[$parto];
      $causaparto="causaparto".$pfj;
      $causaparto=$_REQUEST[$causaparto];
			}


			if ($_REQUEST[$patologia]==0){
			$patologia=0;
      $causapato="";
			}
			else
			{
      $patologia=$_REQUEST[$patologia];
			$causapato=$_REQUEST[$causapato];
			}

		if ($_REQUEST[$patologia]==1)
		{
			if(!$_REQUEST['apgar1'.$pfj] || !$_REQUEST['apgar5'.$pfj] || !$_REQUEST['perimetro'.$pfj] || !$causapato)
			{
					if(!$causapato) { $this->frmError["causaparto"]=1; }
					if(!$_REQUEST['apgar1'.$pfj]) { $this->frmError["apgar1"]=1; }
					if(!$_REQUEST['apgar5'.$pfj]) { $this->frmError["apgar5"]=1; }
					if(!$_REQUEST['perimetro'.$pfj]) { $this->frmError["perimetro"]=1; }
					if(!$_REQUEST['causapato'.$pfj]) { $this->frmError["causapato"]=1; }
					$this->frmError["MensajeError"]='Faltan datos obligatorios.';
					if(!$this->frmForma($_REQUEST['apgar1'.$pfj])){
						return true;
					}
					//return true;
			}
		}
		else
		{
			if(!$_REQUEST['apgar1'.$pfj] || !$_REQUEST['apgar5'.$pfj] || !$_REQUEST['perimetro'.$pfj])
			{
			  	if(!$_REQUEST['apgar1'.$pfj]) { $this->frmError["apgar1"]=1; }
					if(!$_REQUEST['apgar5'.$pfj]) { $this->frmError["apgar5"]=1; }
					if(!$_REQUEST['perimetro'.$pfj]) { $this->frmError["perimetro"]=1; }
					//if(!$_REQUEST['causapato'.$pfj]) { $this->frmError["causapato"]=1; }
					$this->frmError["MensajeError"]='Faltan datos obligatorios.';
					if(!$this->frmForma($_REQUEST['apgar1'.$pfj])){
						return true;
					}
					//return true;
			}

	}
//echo "AQUI HAY UN ERRORRRR";

         	$sql="insert into hc_antecedentes_perinatales
         (hc_antecedente_perinatal_id,
         muertefetoc,
				 diuresis,
         deposicion,
				 patologiamadre,
         causapatologia,
				 partos_parto_id,
				 causaparto,
				 partos_establecimiento_id,
				 atencionpren,
				 embarazodes,
				 adaptacion,
				 placenta,
				 edadgest,
				 peso,
				 talla,
				 perimetrocef,
				 apgar1,
				 apgar5,
				 partos_presentacion_id,
				 partos_medicamentos_id,
				 partos_liquidoAmniotico_id,
				 nacido_sufrimientofetal_id,
				 nacido_alimentacion_id,
				 nacido_egresomaterno_id,
				 nacido_muertematerna_id,
				 atencion,
				 atencionr,
				 aleteo,
				 tiaje,
				 disbalance,
				 quejido,
				 retracciones,
				 nacido_pesotallaegreso_id,
				 partos_pinzamiento_id,
				 permeabilidadano,
				 permeabilidadesofago,
				 evolucion_id
				 )
             values($identifier,
              '".$_REQUEST['feto'.$pfj]."',
							'".$_REQUEST['diuresis'.$pfj]."',
              '".$_REQUEST['deposicion'.$pfj]."',
							'$patologia',
              '$causapato',
							'$normal',
              '$causaparto',
							'".$_REQUEST['establecimiento'.$pfj]."',
						  '".$_REQUEST['prenatal'.$pfj]."',
						  '".$_REQUEST['deseado'.$pfj]."',
              '".$_REQUEST['adaptacion'.$pfj]."',
			 				'".$_REQUEST['placenta'.$pfj]."',
              '".$_REQUEST['edadgest'.$pfj]."',
							'".$_REQUEST['peso'.$pfj]."',
						 '".$_REQUEST['talla'.$pfj]."',
						 ".$_REQUEST['perimetro'.$pfj].",
						 '".$_REQUEST['apgar1'.$pfj]."',
             '".$_REQUEST['apgar5'.$pfj]."',
						 '".$_REQUEST['presentacion'.$pfj]."',
						 '".$_REQUEST['medicamento'.$pfj]."',
						 '".$_REQUEST['amniotico'.$pfj]."',
						 '".$_REQUEST['sufrimiento'.$pfj]."',
             '".$_REQUEST['alimentacion'.$pfj]."',
						 '".$_REQUEST['egreso'.$pfj]."',
             '".$_REQUEST['muertem'.$pfj]."',
             '".$_REQUEST['atencion'.$pfj]."',
             '".$_REQUEST['atencionr'.$pfj]."',
						 '".$_REQUEST['aleteo'.$pfj]."',
             '".$_REQUEST['tiaje'.$pfj]."',
             '".$_REQUEST['disbalance'.$pfj]."',
             '".$_REQUEST['quejido'.$pfj]."',
						 '".$_REQUEST['retracciones'.$pfj]."',
      			 '".$_REQUEST['pesotallaeg'.$pfj]."',
						 '".$_REQUEST['pinzamiento'.$pfj]."',
						 '".$_REQUEST['permeabilidadano'.$pfj]."',
             '".$_REQUEST['permeabilidadesofago'.$pfj]."',
						 '$this->evolucion');";
     // echo "$sql <br>";
		 //exit();
//exit();
						/*	for($i=1;$i<$conteo+1;$i++)
							{
							$reanimacion="reanimacion";
							$reanimacion.=$i;
							if()
							$sql3="insert into hc_aux_Rnacidos_reanimacion
											(hc_antecedente_perinatal_id,tipo_nacido_reanimacion_id)
													values($identifier,$i);";*/
  //   echo "<br>$sql3---><br>";

//}


		  // Reportar errores para depuracion.
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
list($dbconn) = GetDBconn();
$dbconn->StartTrans();


if(!$dbconn->Execute($sql))
{
  $dbconn->FailTrans();
	$error=$dbconn->ErrorMsg();
	echo "$error";
	return false;
}
	for($i=1;$i<$conteo+1;$i++)
					{
							$reanimacion="reanimacion";
							$reanimacion.=$i.$pfj;
					//echo "xxxxxxxx=>".$_REQUEST[$reanimacion];

            if(empty($_REQUEST[$reanimacion])){

							}
						 else
						  {
							 $sql3="insert into hc_aux_Rnacidos_reanimacion
											(hc_antecedente_perinatal_id,tipo_nacido_reanimacion_id)
													values($identifier,$_REQUEST[$reanimacion]);";
  //   echo "<br>$sql3---><br>";

               $dbconn->Execute($sql3);
               $dbconn->CompleteTrans();
               $this->RegistrarSubmodulo($this->GetVersion());
					     }
            }
return true;
}
}
}
?>
