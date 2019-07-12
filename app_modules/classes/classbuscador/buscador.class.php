
<?php
// Buscador.class.php  21/10/2003
// --------------------------------------------------------------------------------------//
// eHospital v 0.1                                                                      //
// Copyright (C) 2003 InterSoftware Ltda.                                              //
// Emai: intersof@telesat.com.co                                                      //
// ----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez (jaja)                                          //
// Proposito del Archivo: Clase para realizar busqueda en la base de datos         //
// de 'CONSULTA EXTERNA' en principio y despues a  nivel general.                 //
// necesita modulo para trabajar..                                               //
// -----------------------------------------------------------------------------//



class buscador{

  var $buscador="";
	var $limit='';


  function buscador(){
    $this->limit=10;
    return true;
  }

  //Trae la Configuración de tabla,colores,numero de campos, segun el caso.
	function rec_datos($Configb)
	{


	 // [$ConsultaConteo] variable que cuando esta en true
	 //realiza la consulta (1)vez para sacar el conteo general de la busqueda
	 //cuando esta en false se sale..
  	$ConsultaConteo=true;

	 //$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
	 if(!$Configb['spi'])
   {
				if (empty($_REQUEST['buscar'])){
				  $this->RetornarCursor(true);
					$busca=$_REQUEST['buscar2'];
					$key1=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
					//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
					}
				else{
          $this->RetornarCursor(false);
					$busca=$_REQUEST['buscar'];
					$key1=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
					//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
					}
    }
		else
		{
				// [$ConsultaConteo] variable que cuando esta en true
	 			//realiza la consulta (1)vez para sacar el conteo general de la busqueda
				//cuando esta en false se sale..
				$ConsultaConteo=false;
				$Of=$Configb['Of'];
        $busca=$Configb['buscar'];
				$key1=$Configb['key1'];
				$conteo=$Configb['conteo'];
		}

  list($linker) = GetDBconn(); //cadena de  conexion a la base de datos llamada linker.
  $col1=$Configb['Bcol1']; //configuracion de color inicial, intercalado.
  $col2=$Configb['Bcol2'];//configuracion de color final, intercalado.
  $cab=$Configb['Bcab']; //cabezotes de la tabla de resultados..
	$cantCampos=$Configb['Bcampos'];//numero de campos.
  $campos=$Configb['Bncampo'];// trae los campos de una tabla determinada..
  $alias=$Configb['Bnombres'];
	$values=$Configb['BNhidden'];
  $busca=ltrim($busca);//quita espacios al inicio de la cadena
  $busca=rtrim($busca);  // quita espacios al final de la cadena  que se esta buscando.....
	if(empty($busca))
	{
      $this->error($nom="");
    }
		else
		{   $limit=$this->limit;
			  $cade = str_replace("*","",$busca);
        $val=$cade;
        $sign = "%";
        $val2 = $sign;
        $val3 = $sign; //se une la estructura de la cadena a buscar  "%nom" o "%nom%" o "nom%"
        $val2 .= $val;
        $val3 .= $val;
        $val3 .= $sign;
        $val .= $sign;
				IncludeLib("tarifario_cargos");
				$vari=" WHERE ( lower ($key1) like '".strtolower($val2)."' or lower ($key1) like '".strtolower($val3)."' or lower ($key1) like '".strtolower($val)."')";
      if($ConsultaConteo==true) //aqui se pregunta si hay que sacar un conteo general.
			{
           if($_SESSION['TIPO_BUSCADOR']=='BuscarCargo'){ $car=$_SESSION['CARGO'];}else{$car='';}
						$result=GetPlanTarifario($_SESSION['plan'],'','','',$_SESSION['grupo_tipo_cargo'],$_SESSION['tipo_cargo'],'','',false,false,$vari,$campos);
						if (!is_object($result)){
							echo "<div class='label'><br><b>Error al ejecutar la consulta.</b><br></div>".$result;
						}
						else
						{  $conteo=$result->RecordCount();}
      }

			if(is_null($Of))
					{
							$limit=$this->limit;
							$Of='0';
					}

       if($_SESSION['TIPO_BUSCADOR']=='BuscarCargo'){ $car=$_SESSION['CARGO'];}else{$car='';}
				$result=GetPlanTarifario($_SESSION['plan'],'','','',$_SESSION['grupo_tipo_cargo'],$_SESSION['tipo_cargo'],'','',false,false,$vari,$campos,$Of,$limit);
				$this->Mostrar($busca,$result,$alias,$cantCampos,$campos,$col1,$col2,$cab,$values);
				$this->RetornarBarra($paso,$conteo,$busca,$key1);
				return $conteo;
    }
}



  //$Configb ->Trae la Configuración de tabla,colores,numero de campos etc.. segun el caso.
function rec_datos2($Configb)
	{
		// [$ConsultaConteo] variable que cuando esta en true
	 	//realiza la consulta (1)vez para sacar el conteo general de la busqueda
		//cuando esta en false se sale..
	 	$ConsultaConteo=true;

		//$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
	 if(!$Configb['spi'])
   {
				if (empty($_REQUEST['buscar'])){
					$busca=$_REQUEST['buscar2'];
					$key1=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
					//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
					}
				else{
					$busca=$_REQUEST['buscar'];
					$key1=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
				//	$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
					}
    }
		else
		{

				$ConsultaConteo=false;
				$Of=$Configb['Of']; //offset de la consulta.
        $busca=$Configb['buscar']; //variable de la cadena de busqueda ej: codigo
				$key1=$Configb['key1'];  //variable de la cadena de busqueda ej:nombre
				$conteo=$Configb['conteo'];  //variable que guarda cuantos registros saco la consulta
		}

	//If bodega=vacio => es una consulta de plan terapeutico u otro de lo contrario
	//es una busqueda de medicamento y se agrega la bodega
	if (empty($_REQUEST['BBodega']))
		$consulta=urldecode(stripslashes($_SESSION['SQL']));
	else
		$consulta=urldecode(stripslashes($_SESSION['SQL']))."AND bodega ='".$_REQUEST['BBodega']."' ";

  list($linker) = GetDBconn(); //cadena de conexion a la BD llamada linker.
  $col1=$Configb['Bcol1']; //configuracion de color inicial, intercalado.
  $col2=$Configb['Bcol2'];//configuracion de color final, intercalado.
  $cab=$Configb['Bcab']; //cabezotes de la tabla de resultados..
	$cantCampos=$Configb['Bcampos'];
  $alias=$Configb['Bnombres'];
	$values=$Configb['BNhidden'];
  $busca=ltrim($busca);//quita espacios al inicio de la cadena
  $busca=rtrim($busca);  // quita espacios al final de la cadena  que se esta buscando.....
	if(empty($busca))
	{
      $this->error($nom="");
    }
		else
		{
		    $limit=$this->limit;
			  $cade = str_replace("*","",$busca);
        $val=$cade;
        $sign = "%";
        $val2 = $sign;
        $val3 = $sign; //se une la estructura de la cadena a buscar  "%nom" o "%nom%" o "nom%"
        $val2 .= $val;
        $val3 .= $val;
        $val3 .= $sign;
        $val .= $sign;
        if($ConsultaConteo==true)
			  {
				echo $q="$consulta"." and ( lower ($key1) like '".strtolower($val2)."' or lower ($key1) like '".strtolower($val3)."' or lower ($key1) like '".strtolower($val)."'";
				$result=$linker->Execute("$consulta"." and ( lower ($key1) like '".strtolower($val2)."' or lower ($key1) like '".strtolower($val3)."' or lower ($key1) like '".strtolower($val)."')");
        $conteo=$result->RecordCount();
				}

				if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}

        $R.="$consulta"." and ( lower ($key1) like '".strtolower($val2)."' or lower ($key1) like '".strtolower($val3)."' or lower ($key1) like '".strtolower($val)."')";
				//echo "<br>Q->".$R;
        $R.=" LIMIT ".$this->limit." OFFSET $Of ";
				$result=$linker->Execute($R);
				$this->Mostrar($busca,$result,$alias,$cantCampos,$campos,$col1,$col2,$cab,$values);
				$this->RetornarBarra($paso,$conteo,$busca,$key1); //funcion q llama la barra.
				return $conteo;
    }
}


function RetornarCursor($centinela)
{
 echo "<script language=\"javascript\">\n";
 if($centinela==true)
 {
    echo "document.datos.buscar2.focus();\n";
 }
 else
 {
    echo "document.datos1.buscar.focus();\n";
 }
 echo "</script>\n";
 return true;
}



 /* funcion de darling */
 //$Configb ->Trae la Configuración de tabla,colores,numero de campos etc.. segun el caso.
  function RecibeDatos($Configb)
	{

		// [$ConsultaConteo] variable que cuando esta en true
		//realiza la consulta (1)vez para sacar el conteo general de la busqueda
		//cuando esta en false se sale..
		$ConsultaConteo=true;

		//$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
	 if(!$Configb['spi'])
   {

				if (empty($_REQUEST['buscar']))
				{
     				    $this->RetornarCursor(true);
						//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
						$busca=$_REQUEST['buscar2'];
						$key1=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
				}
				else
		 	 	{
                        $this->RetornarCursor(false);
				  	//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
						$busca=$_REQUEST['buscar'];
						$key1=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
				}
    }
		else
		{

				$ConsultaConteo=false;
				$Of=$Configb['Of'];
                $busca=$Configb['buscar'];
				$key1=$Configb['key1'];
				$conteo=$Configb['conteo'];
		}
		$plan=$Configb['plan'];
		$num=$Configb['Bcampos'];
		$alias=$Configb['Bnombres']; //cargando configuración según el caso.
		$col1=$Configb['Bcol1'];
		$col2=$Configb['Bcol2'];
		$cab=$Configb['Bcab'];
		$values=$Configb['BNhidden'];

		list($conex) = GetDBconn();
		$busca=ltrim($busca);//quita espacios al inicio de la cadena
		$busca=strtolower(rtrim($busca));  // quita espacios al final de la cadena  que se esta buscando.....

				if(empty($busca))
				{
					$this->error($nom="");
				}
				else
				{
					$limit=$this->limit;
					if(!empty($Configb['tiposolicitud']))
					{  $f="AND grupo_tipo_cargo='".$Configb['tiposolicitud']."'";  }
					IncludeLib("tarifario_cargos");
					$filtro = "( lower ($key1) like '%$busca' or lower ($key1) like '%$busca%' or lower ($key1) like '$busca%')
											  $f";
					//$campos_select = " descripcion, tarifario_id, grupo_tarifario_id, subgrupo_tarifario_id, cargo, precio,
														//por_cobertura, gravamen, porcentaje, sw_cantidad ";
					//$campos_select = "descripcion, grupo_tarifario_id, subgrupo_tarifario_id, precio, cargo_base,sw_cantidad ";
					$campos_select = "descripcion, cargo";

					if(is_null($Of))
					{
              $limit=$this->limit;
								$Of='0';
					}
					if($ConsultaConteo==true)
					{
							$resultin = BuscardoCargosCups($filtro, $campos_select,'','');
							//$resultin = BuscardoCargosCups($plan, $Configb['tiposolicitud'], $filtro, $campos_select, $fetch_mode_assoc=false,'','');
							$conteo=$resultin->RecordCount();
         }
					if(is_null($Of))
					{
              $limit=$this->limit;
							$Of='0';
					}
					//$result= PlanTarifario($plan, '', '', '', '', $Configb['tiposolicitud'], '', $filtro, $campos_select, $fetch_mode_assoc=false,$Of,$limit,'');
					//$result = BuscardoCargosCups($plan, $Configb['tiposolicitud'], $filtro, $campos_select, $fetch_mode_assoc=false,$Of,$limit);
					$result = BuscardoCargosCups($filtro, $campos_select,$Of,$limit);
					$this->Mostrar($busca,$result,$alias,$num,'',$col1,$col2,$cab,$values,'');
					$this->RetornarBarra($paso,$conteo,$busca,$key1);
					return $conteo;
      }
	}

 /* funcion de darling */
 //$Configb ->Trae la Configuración de tabla,colores,numero de campos etc.. segun el caso.
  function RecibeDatosInsumos($Configb)
	{
		// [$ConsultaConteo] variable que cuando esta en true
		//realiza la consulta (1)vez para sacar el conteo general de la busqueda
		//cuando esta en false se sale..
		$ConsultaConteo=true;

		//$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
	 if(!$Configb['spi'])
   {
				if (empty($_REQUEST['buscar']))
				{
     				 $this->RetornarCursor(true);
						//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
						$busca=$_REQUEST['buscar2'];
						$key1=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
				}
				else
		 	 	{
           $this->RetornarCursor(false);
				  	//$ofsset_inicial='';  //se coloca para evitar que se haga un offset en plan tarifario
						$busca=$_REQUEST['buscar'];
						$key1=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
				}
    }
		else
		{

				$ConsultaConteo=false;
				$Of=$Configb['Of'];
                $busca=$Configb['buscar'];
				$key1=$Configb['key1'];
				$conteo=$Configb['conteo'];
		}
		$plan=$Configb['plan'];
		$num=$Configb['Bcampos'];
		$alias=$Configb['Bnombres']; //cargando configuración según el caso.
		$col1=$Configb['Bcol1'];
		$col2=$Configb['Bcol2'];
		$cab=$Configb['Bcab'];
		$values=$Configb['BNhidden'];
		$bodega=$Configb['bodega'];
		$empresa=$Configb['empresa'];
		$cu=$Configb['cu'];

		list($dbconn) = GetDBconn();
		$busca=ltrim($busca);//quita espacios al inicio de la cadena
		$busca=strtolower(rtrim($busca));  // quita espacios al final de la cadena  que se esta buscando.....

				if(empty($busca))
				{
					$this->error($nom="");
				}
				else
				{
						$limit=$this->limit;
						$filtro = " lower (a.$key1) like '%$busca%'";									
	
						//IncludeLib("tarifario_cargos");
						//$filtro = " WHERE ( lower ($key1) like '%$busca' or lower ($key1) like '%$busca%' or lower ($key1) like '$busca%')";

						if($ConsultaConteo==true)
						{		//CONTEO TOTAL PARA LA BARRA
								$query = "SELECT X.*, CASE WHEN Y.sw_pos='1' THEN 'POS' WHEN Y.sw_pos='0' THEN 'NO POS' END as sw_pos
													FROM
													(SELECT d.codigo_producto, a.descripcion, e.precio_venta, a.porc_iva,
														b.descripcion||' POR '|| a.contenido_unidad_venta AS presentacion
													FROM
													inventarios_productos a, unidades b, existencias_bodegas d, inventarios e
													WHERE $filtro
													AND b.unidad_id=a.unidad_id
													AND d.empresa_id='$empresa'
													AND d.centro_utilidad='$cu'
													AND d.bodega='$bodega'
													AND d.codigo_producto=a.codigo_producto
													AND e.empresa_id=d.empresa_id
													AND e.codigo_producto=d.codigo_producto) as X 
													LEFT JOIN medicamentos as Y ON (X.codigo_producto = Y.codigo_medicamento)";						
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Guardar en la Tabal autorizaiones";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
								}
								if(!$result->EOF)
								{
										while(!$result->EOF)
										{
														$var[]=$result->GetRowAssoc($ToUpper = false);
														$result->MoveNext();
										}	
										$conteo=$result->RecordCount();
								}										
						
								//$campos_select='codigo_producto, descripcion, precio_venta, porc_iva, presentacion, sw_pos';
								//$resultin = GetPlantarifarioIyM($plan,$bodega,$empresa,$cu,'','','', $filtro, $campos_select, $fetch_mode_assoc=false,'','');
								//$conteo=$resultin->RecordCount();
						}
						if(is_null($Of))
						{
								$limit=$this->limit;
								$Of='0';
						}
						$query = "SELECT X.*, CASE WHEN Y.sw_pos='1' THEN 'POS' WHEN Y.sw_pos='0' THEN 'NO POS' END as sw_pos
											FROM
											(SELECT d.codigo_producto, a.descripcion, e.precio_venta, a.porc_iva,
												b.descripcion||' POR '|| a.contenido_unidad_venta AS presentacion
											FROM
											inventarios_productos a, unidades b, existencias_bodegas d, inventarios e
											WHERE $filtro
											AND b.unidad_id=a.unidad_id
											AND d.empresa_id='$empresa'
											AND d.centro_utilidad='$cu'
											AND d.bodega='$bodega'
											AND d.codigo_producto=a.codigo_producto
											AND e.empresa_id=d.empresa_id
											AND e.codigo_producto=d.codigo_producto) as X 
											LEFT JOIN medicamentos as Y ON (X.codigo_producto = Y.codigo_medicamento) LIMIT $limit OFFSET $Of";						
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Tabal autorizaiones";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}				
						//$campos_select='codigo_producto, descripcion, precio_venta, porc_iva, presentacion, sw_pos';
						//$result = GetPlantarifarioIyM($plan,$bodega,$empresa,$cu,'','','', $filtro, $campos_select, $fetch_mode_assoc=false,$Of,$limit);
						$this->Mostrar($busca,$result,$alias,$num,'',$col1,$col2,$cab,$values,'');
						$this->RetornarBarra($paso,$conteo,$busca,$key1);
						return $conteo;
      }
	}



	function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}




function RetornarBarra($paso,$conteo,$busca,$key1){
    if($this->limit>=$conteo){
      if(!empty($conteo))
			{
				$this->registro($conteo,$busca);
      }
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
      $paso=1;
		}
		$accion="buscador.php?conteo=$conteo&buscar=$busca&key1=$key1&spi=true";
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($conteo);
		$colspan=1;

		$salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
    //  $salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0)
			{$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
       // $salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		//$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
    	if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		$this->registro($conteo,$busca);
		echo $salida;
	}

  /*funcion de lorena 2*/
	/*function Rec_Data($Configb)
	{
		if (empty($_REQUEST['buscar']))
		{
				$busca=$_REQUEST['buscar2'];
       	$key1=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		}
		else
		{
				$busca=$_REQUEST['buscar'];
				$key1=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}
				$plan=$Configb['plan'];
				$num=$Configb['Bcampos'];
				$alias=$Configb['Bnombres'];
				$col1=$Configb['Bcol1'];
				$col2=$Configb['Bcol2'];
				$cab=$Configb['Bcab'];
				$values=$Configb['BNhidden'];

       list($conex) = GetDBconn();
       $busca=ltrim($busca);//quita espacios al inicio de la cadena
       $busca=strtolower(rtrim($busca));  // quita espacios al final de la cadena  que se esta buscando.....

				if(empty($busca))
				{
					$this->error($nom="");
				}
				else
				{
          IncludeLib("tarifario");
				  $filtro = "( lower ($key1) like '%$busca' or lower ($key1) like '%$busca%' or lower ($key1) like '$busca%')";
          $campos_select = " descripcion, tarifario_id, grupo_tarifario_id, subgrupo_tarifario_id, cargo, precio,
                             por_cobertura, gravamen, porcentaje, sw_cantidad ";
          $resultar = PlanTarifario($plan, '', '', '', '', '', '', $filtro, $campos_select, $fetch_mode_assoc=false);
					$this->Mostrar($busca,$resultar,$alias,$num,'',$col1,$col2,$cab,$values,'');
      }
	}*/



	//$Configb ->Trae la Configuración de tabla,colores,numero de campos etc.. segun el caso.
	function recibir_dato($Configb){

	// [$ConsultaConteo] variable que cuando esta en true
	//realiza la consulta (1)vez para sacar el conteo general de la busqueda
	//cuando esta en false se sale..
 $ConsultaConteo=true;
 //$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
 if(!$Configb['spi'])
 {
		if (empty($_REQUEST['buscar']))
		{
     $busca=$_REQUEST['buscar2'];
     $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}else{
         $busca=$_REQUEST['buscar'];
         $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		     }

		}
		else
		{
			if (empty($_REQUEST['buscar']))
		{
     $busca=$_REQUEST['buscar2'];
     $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}else{
         $busca=$_REQUEST['buscar'];
         $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		     }

   			$ConsultaConteo=false;
				$Of=$Configb['Of'];
        $busca=$Configb['buscar'];
				$conteo=$Configb['conteo'];
        $clave=$Configb['key1'];
		}
		$tabla=$Configb['Btabla'];  //Recibe el nombre de la tabla en donde se hara la busqueda
		$alias=$Configb['Bnombres'];//Trae el arrglo de los nombres que se mostrara en la tabla
    $num=$Configb['Bcampos']; // trae el numero de campos de una tabla determinada..
    $campos=$Configb['Bncampo'];// trae los campos de una tabla determinada..
    $col1=$Configb['Bcol1']; //configuracion de color inicial, intercalado.
    $col2=$Configb['Bcol2'];//configuracion de color final, intercalado.
    $cab=$Configb['Bcab']; //cabezotes de la tabla de resultados..
    $values=$Configb['BNhidden'];

    $busca=ltrim($busca);//quita espacios al inicio de la cadena
    $busca=rtrim($busca);  // quita espacios al final de la cadena  que se esta buscando.....
    if($busca == ""){
      $this->error($nom="");
    }else{
      $aux = str_replace("*","",$busca); ///aqui esta la cadena limpia..ojo.....
      $ast="*";
      $caso1=$ast . $aux;
      $caso2=$ast . $aux . $ast;
      $caso3=$aux . $ast;

      if($busca == $caso1)
      {
        $signo="%";
        $vari.=$signo;
        $vari.=$busca;
        $this->revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo);
      }elseif($busca == $caso2){
        $signo="%";
        $vari=$signo;
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo);
      }elseif($busca == $caso3){
        $signo="%";
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo);
      }else{
        $this->revisar_todos($busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo);//pasa valores de conexion y la cadena
      }
    }
  }

    function revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo){
    list($linker) = GetDBconn();
    $cade = str_replace("*","",$vari);
    $vari=$cade;
		if($ConsultaConteo==true)
					{
					$result=$linker->Execute("SELECT $campos FROM $tabla  where lower($clave) like '".strtolower($vari)."' ".$_SESSION['CLIENTES_TERCEROS']."");
          $conteo=$result->RecordCount();
          }
					if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}

		$result=$linker->Execute("SELECT $campos FROM $tabla  where (lower($clave) like '".strtolower($vari)."' ".$_SESSION['CLIENTES_TERCEROS'].") LIMIT ".$this->limit." OFFSET $Of ");
    $this->Mostrar($busca,$result,$alias,$num,$campos,$col1,$col2,$cab,$values);
		$this->RetornarBarra($paso,$conteo,$busca,$clave);
		return $conteo;
  }

	function revisar_individualEmpresa($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtili,$Bodega){

		list($linker) = GetDBconn();
    $cade = str_replace("*","",$vari);
    $vari=$cade;
		if($ConsultaConteo==true)
					{
					$result=$linker->Execute("$campos and  x.empresa_id='$Empresa' and z.empresa_id=x.empresa_id and x.codigo_producto=z.codigo_producto and l.codigo_producto=x.codigo_producto and z.centro_utilidad='$CentroUtili' and z.bodega='$Bodega' AND (lower(l.$clave) like '".strtolower($vari)."')");
          $conteo=$result->RecordCount();
          }
					if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}
		$result=$linker->Execute("$campos  and x.empresa_id='$Empresa' and z.empresa_id=x.empresa_id and x.codigo_producto=z.codigo_producto  and l.codigo_producto=x.codigo_producto and z.centro_utilidad='$CentroUtili' and z.bodega='$Bodega'  AND  (lower(l.$clave) like '".strtolower($vari)."') LIMIT ".$this->limit." OFFSET $Of ");
    $this->Mostrar($busca,$result,$alias,$num,$campos,$col1,$col2,$cab,$values);
		$this->RetornarBarra($paso,$conteo,$busca,$clave);
		return $conteo;
  }

  function  revisar_todosEmpresa($val,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtili,$Bodega){//recibe valores de conexion //, la //cadena,la tabla, el campo de busqueda...


    list($linker) = GetDBconn();
    $busca=$val;
    $cade = str_replace("*","",$val);
    $val=$cade;
    $sign = "%";
    $val2 = $sign;
    $val3 = $sign; //se une la estructura de la cadena a buscar  "%nom" o "%nom%" o "nom%"
    $val2 .= $val;
    $val3 .= $val;
    $val3 .= $sign;
    $val .= $sign;
		if($ConsultaConteo==true)
					{
					$result=$linker->Execute("$campos AND x.empresa_id='$Empresa' and z.empresa_id=x.empresa_id and x.codigo_producto=z.codigo_producto and x.codigo_producto=l.codigo_producto and z.centro_utilidad='$CentroUtili' and z.bodega='$Bodega' AND  (lower(l.$clave)   like '".strtolower($val2)."' or lower(l.$clave) like '".strtolower($val3)."' or lower(l.$clave) like '".strtolower($val)."')");
          $conteo=$result->RecordCount();
          }
					if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}
    $result=$linker->Execute( "$campos AND x.empresa_id='$Empresa' and z.empresa_id=x.empresa_id and x.codigo_producto=z.codigo_producto and x.codigo_producto=l.codigo_producto and z.centro_utilidad='$CentroUtili' and z.bodega='$Bodega' AND (lower(l.$clave) like '".strtolower($val2)."' or lower(l.$clave) like '".strtolower($val3)."' or lower(l.$clave) like '".strtolower($val)."') LIMIT ".$this->limit." OFFSET $Of");
    $this->Mostrar($busca,$result,$alias,$num,$campos,$col1,$col2,$cab,$values);
		$this->RetornarBarra($paso,$conteo,$busca,$clave);
   	return $conteo;
  }


  function  revisar_todos($val,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo){//recibe valores de conexion //, la //cadena,la tabla, el campo de busqueda...
    list($linker) = GetDBconn();
    $busca=$val;
    $cade = str_replace("*","",$val);
    $val=$cade;
    $sign = "%";
    $val2 = $sign;
    $val3 = $sign; //se une la estructura de la cadena a buscar  "%nom" o "%nom%" o "nom%"
    $val2 .= $val;
    $val3 .= $val;
    $val3 .= $sign;
    $val .= $sign;
		if($ConsultaConteo==true)
					{
     			$result=$linker->Execute("SELECT $campos FROM $tabla where (lower($clave) like '".strtolower($val2)."' or lower($clave) like '".strtolower($val3)."' or lower($clave) like '".strtolower($val)."') ".$_SESSION['CLIENTES_TERCEROS']."");
          $conteo=$result->RecordCount();
          }
					if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}
    $result=$linker->Execute("SELECT $campos FROM $tabla where (lower($clave) like '".strtolower($val2)."' or lower($clave) like '".strtolower($val3)."' or lower($clave) like '".strtolower($val)."') ".$_SESSION['CLIENTES_TERCEROS']."  LIMIT ".$this->limit." OFFSET $Of");
    $this->Mostrar($busca,$result,$alias,$num,$campos,$col1,$col2,$cab,$values);
		$this->RetornarBarra($paso,$conteo,$busca,$clave);
   	return $conteo;
  }




  function error($nom){
    echo ("<p align=\"center\"><b><font color=\"#006699\">NINGUN RESULTADO DE BUSQUEDA <br>
                <br>
                <font color=\"#999999\"><i>la informacion que usted busca no se pudo
                concretar ya que la cadena no existe o son datos erroneos</i></font></font></b></p>
                </font></p>
            ");
    $this->registro($global=0,$nom);
  }


  function registro($global,$nom)//funcion de mostrar cuantos registros se obtuvo en busqueda.....
  {
    echo ("<table align=\"center\">
            <td></td>
            <td></td>
            <td></td>
            <td >la anterior busqueda arrojo <font color=\"#006699\">$global  <font color=\"#000000\">registros de la   cadena <font color=\"#006699\">\"$nom\"</td>
            </table> </form> ");
  }


  function Mostrar($val,$result,$alias,$num,$campos,$col1,$col2,$cab,$values,$nom_campos)
  {
    $nom=$val; //variable de nombre de busqueda con espacios.....
    $val=ltrim($val);//quita espacios al inicio de la cadena
    $val=rtrim($val);  // quita espacios al final de la cadena  que se esta buscando.....
    // $global=pg_numrows($result);///calcula numero de registros.............OJO CON ESTO.....
    $global=$result->RecordCount();///prueba con ADODB...-........-....

    if($global == "0")
		{
      $this->error($nom);  //si no hubo nada de registros en la consulta....
    }
		else
		{
      echo("<table border=0 align=\"CENTER\" cellspacing=1 cellpadding=1 width=\"60%\"
        bordercolorlight=\"#666666\" height=\"40\">
        <tr bgcolor=\"$cab\">
        <td height=\"10\" width=\"2%\"><font size =\"2\" color=\"#000000\">Sel</font></b></td>");
      $tok = strtok($alias," ");
      while($tok)
			{
        echo ("<td height=\"15\"><b><font size =\"2\" color=\"#000000\">$tok</font></b></td>");
        $tok =strtok(" ");
      }
      echo ("</tr>");
      $spy="1";
      while (!$result->EOF)
			{
      	if ($spy=="1")
				{
          $color=$col1;
          $spy="0";
      	}
				else
				{
        	$color=$col2; //#E6E6CC //colores de configuración del navegador....
        	$spy="1";  //para determinar el color de intercalado...
      	}
    		$Separador="";  //variable de separacion de cadenas
    		echo("<td bgcolor=\"$color\"><input type=\"radio\" name=\"chequeo\"");
    		$m=1;
				for($q=0;$q<$values;$q++)
				{
    			$Separador.=$result->fields[$q];
    			if($q<>$values-1) //values son los numeros de campos q muestra la consulta
					{
    				$Separador.="^"; //ESTO SEPARA LOS VALUES DE LA CONSULTA...OJO
						//este caracter separara la cadena en varios arreglos,hay que determinar otro
						//caracter en caso de que este no funcione bien en la consulta.
					}

    		}

				//$variables_separadas--> esta variable tiene todo el value que la persona necesita
				//aqui estan los datos que se van a enviar al modulo que se este llamando
				//ejemplo-->juan,3372005,cali,calle45#36/78,kl@hotmail.com etc...
				//esta variable se pasa por el evento onclick a la funcion pasar().
    		$variables_separadas=ltrim($Separador);//quita espacios al inicio de la cadena
    		$variables_separadas=rtrim($Separador);  // quita espacios al final
    		echo "value=\"$variables_separadas\" onclick=pasar(this.value)></td>";
				for($i=0;$i<$num;$i++)
				{
					$fila=$result->fields[$i];//$fila es cada registro que salga en consulta
					$val=strtoupper($val);
					if(strlen($fila)>60)
							{
               $fila=substr($fila,0,60); //$fila es cada registro que salga en consulta
							 $fila.='...';
							}
					$fila=str_replace(strtoupper($val),"<b>".strtoupper($val)."</b>",$fila);
					$fila=str_replace(strtolower($val),"<b>".strtolower($val)."</b>",$fila);
					echo("<td class=\"label\" bgcolor=\"$color\"><font color=\"#666666\">$fila</font></td>");
				}
				echo("</tr>");
				$result->MoveNext();
			}//fin while
   		echo "<td  height=\"15\" bgcolor=\"$cab\" colspan=\"".($num+1)."\"></td>";
    //  $this->registro($global,$nom);
    }
    $result->Close(); # optional
		echo "</tr>";
		echo "</table>";
  }


function imprime_java($val,$form){
echo "<script language=\"javascript\">\n";
echo "function pasar(text){\n";
echo "var re=text\n";
echo "var res=re.split(\"^\")\n";
$e=0;
$b = strtok($val," ");
while($b){
 echo "document.datos.$b.value=res[$e]\n";
 $recibe.="<input type=\"hidden\" name=\"$b\">\n";
 $data.="window.opener.document.$form.$b.value=document.datos.$b.value\n";
 $e++;
 $b =strtok(" ");
  }

//$data.="buscadorcuenta()\n";
$data.="self.close()\n";
echo $data;
echo "}\n";
echo "</script>\n";
echo $recibe;
}




/*caso de jorge eliecer avila*/


//$Configb ->Trae la Configuración de tabla,colores,numero de campos etc.. segun el caso.
function recibir_dato_proveedor($Configb){

 // [$ConsultaConteo] variable que cuando esta en true
 //realiza la consulta (1)vez para sacar el conteo general de la busqueda
	//cuando esta en false se sale..
 $ConsultaConteo=true;
 //$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
 if(!$Configb['spi'])
 {
		if (empty($_REQUEST['buscar']))
		{
		 $this->RetornarCursor(true);
     $busca=$_REQUEST['buscar2'];
     $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}else{
		     $this->RetornarCursor(false);
         $busca=$_REQUEST['buscar'];
         $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		     }

		}
		else
		{
			if (empty($_REQUEST['buscar']))
		{
     $busca=$_REQUEST['buscar2'];
     $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}else{
         $busca=$_REQUEST['buscar'];
         $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		     }

   			$ConsultaConteo=false;
				$Of=$Configb['Of'];
        $busca=$Configb['buscar'];
				$conteo=$Configb['conteo'];
        $clave=$Configb['key1'];
		}
		$tabla=$Configb['Btabla'];  //Recibe el nombre de la tabla en donde se hara la busqueda
		$alias=$Configb['Bnombres'];//Trae el arrglo de los nombres que se mostrara en la tabla
    $num=$Configb['Bcampos']; // trae el numero de campos de una tabla determinada..
    $campos=$Configb['Bncampo'];// trae los campos de una tabla determinada..
    $col1=$Configb['Bcol1']; //configuracion de color inicial, intercalado.
    $col2=$Configb['Bcol2'];//configuracion de color final, intercalado.
    $cab=$Configb['Bcab']; //cabezotes de la tabla de resultados..
    $values=$Configb['BNhidden'];

    $busca=ltrim($busca);//quita espacios al inicio de la cadena
    $busca=rtrim($busca);  // quita espacios al final de la cadena  que se esta buscando.....
    if($busca == ""){
      $this->error($nom="");
    }else{
      $aux = str_replace("*","",$busca); ///aqui esta la cadena limpia..ojo.....
      $ast="*";
      $caso1=$ast . $aux;
      $caso2=$ast . $aux . $ast;
      $caso3=$aux . $ast;

      if($busca == $caso1)
      {
        $signo="%";
        $vari.=$signo;
        $vari.=$busca;
        $this->revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo);
      }elseif($busca == $caso2){
        $signo="%";
        $vari=$signo;
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo);
      }elseif($busca == $caso3){
        $signo="%";
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individual($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo);
      }else{
        $this->revisar_todos($busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo);//pasa valores de conexion y la cadena
      }
    }
  }

//$Configb ->Trae la Configuración de tabla,colores,numero de campos etc.. segun el caso.
	function recibir_dato_Inventario($Configb){
    $Empresa=str_pad($_SESSION['SQL'],2,0,STR_PAD_LEFT);
    $CentroUtilidad=$_SESSION['SQLA'];
	  $BodegaId=$_SESSION['SQLB'];

	// [$ConsultaConteo] variable que cuando esta en true
	//realiza la consulta (1)vez para sacar el conteo general de la busqueda
	//cuando esta en false se sale..
 $ConsultaConteo=true;

 //$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
	 //simplemente es un link que toco desde la barra de la funcion RetornarBarra()
	 //si esta con datos quiere decir que viene de la barra y por el contrario,
	 //es que viene del boton buscar.
 if(!$Configb['spi'])
 {
		if (empty($_REQUEST['buscar']))
		{
		 $this->RetornarCursor(true);
     $busca=$_REQUEST['buscar2'];
     $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}else{
		     $this->RetornarCursor(false);
         $busca=$_REQUEST['buscar'];
         $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		     }

		}
		else
		{
			if (empty($_REQUEST['buscar']))
		{
     $busca=$_REQUEST['buscar2'];
     $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		}else{
         $busca=$_REQUEST['buscar'];
         $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		     }

   			$ConsultaConteo=false;
				$Of=$Configb['Of'];
        $busca=$Configb['buscar'];
				$conteo=$Configb['conteo'];
        $clave=$Configb['key1'];
		}
		$tabla=$Configb['Btabla'];  //Recibe el nombre de la tabla en donde se hara la busqueda
		$alias=$Configb['Bnombres'];//Trae el arrglo de los nombres que se mostrara en la tabla
    $num=$Configb['Bcampos']; // trae el numero de campos de una tabla determinada..
    $campos=$Configb['Bncampo'];// trae los campos de una tabla determinada..
    $col1=$Configb['Bcol1']; //configuracion de color inicial, intercalado.
    $col2=$Configb['Bcol2'];//configuracion de color final, intercalado.
    $cab=$Configb['Bcab']; //cabezotes de la tabla de resultados..
    $values=$Configb['BNhidden'];

    $busca=ltrim($busca);//quita espacios al inicio de la cadena
    $busca=rtrim($busca);  // quita espacios al final de la cadena  que se esta buscando.....
    if($busca == ""){
      $this->error($nom="");
    }else{
      $aux = str_replace("*","",$busca); ///aqui esta la cadena limpia..ojo.....
      $ast="*";
      $caso1=$ast . $aux;
      $caso2=$ast . $aux . $ast;
      $caso3=$aux . $ast;

      if($busca == $caso1)
      {
        $signo="%";
        $vari.=$signo;
        $vari.=$busca;
        $this->revisar_individualEmpresa($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);
      }elseif($busca == $caso2){
        $signo="%";
        $vari=$signo;
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individualEmpresa($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);
      }elseif($busca == $caso3){
        $signo="%";
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individualEmpresa($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);
      }else{
        $this->revisar_todosEmpresa($busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);//pasa valores de conexion y la cadena
      }
    }
  }

  function recibir_dato_ProcedimientosQX($Configb){
		// [$ConsultaConteo] variable que cuando esta en true
		//realiza la consulta (1)vez para sacar el conteo general de la busqueda
		//cuando esta en false se sale..
		$ConsultaConteo=true;
		//$Configb['spi'] variable que determina si toco el boton buscar o por el contrario
		//simplemente es un link que toco desde la barra de la funcion RetornarBarra()
		//si esta con datos quiere decir que viene de la barra y por el contrario,
		//es que viene del boton buscar.
		if(!$Configb['spi']){
			if (empty($_REQUEST['buscar'])){
			$this->RetornarCursor(true);
			$busca=$_REQUEST['buscar2'];
			$clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
			}else{
				$this->RetornarCursor(false);
				$busca=$_REQUEST['buscar'];
				$clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
			}
		}else{
		  if (empty($_REQUEST['buscar'])){
        $busca=$_REQUEST['buscar2'];
        $clave=$Configb['Bclave']; //Recibe la palabra clave de la busqueda
		  }else{
        $busca=$_REQUEST['buscar'];
        $clave=$Configb['Bclave1']; //Recibe la palabra clave de la busqueda
		  }
   		$ConsultaConteo=false;
			$Of=$Configb['Of'];
      $busca=$Configb['buscar'];
			$conteo=$Configb['conteo'];
      $clave=$Configb['key1'];
		}
		$tabla=$Configb['Btabla'];  //Recibe el nombre de la tabla en donde se hara la busqueda
		$alias=$Configb['Bnombres'];//Trae el arrglo de los nombres que se mostrara en la tabla
    $num=$Configb['Bcampos']; // trae el numero de campos de una tabla determinada..
    $campos=$Configb['Bncampo'];// trae los campos de una tabla determinada..
    $col1=$Configb['Bcol1']; //configuracion de color inicial, intercalado.
    $col2=$Configb['Bcol2'];//configuracion de color final, intercalado.
    $cab=$Configb['Bcab']; //cabezotes de la tabla de resultados..
    $values=$Configb['BNhidden'];

    $busca=ltrim($busca);//quita espacios al inicio de la cadena
    $busca=rtrim($busca);  // quita espacios al final de la cadena  que se esta buscando.....
    if($busca == ""){
      $this->error($nom="");
    }else{
      $aux = str_replace("*","",$busca); ///aqui esta la cadena limpia..ojo.....
      $ast="*";
      $caso1=$ast . $aux;
      $caso2=$ast . $aux . $ast;
      $caso3=$aux . $ast;
      if($busca == $caso1){
        $signo="%";
        $vari.=$signo;
        $vari.=$busca;
        $this->revisar_individual_procedimiento($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);
      }elseif($busca == $caso2){
        $signo="%";
        $vari=$signo;
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individual_procedimiento($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);
      }elseif($busca == $caso3){
        $signo="%";
        $vari.=$busca;
        $vari.=$signo;
        $this->revisar_individual_procedimiento($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,
        $col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);
      }else{
        $this->revisar_todos_procedimiento($busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo,$Empresa,$CentroUtilidad,$BodegaId);//pasa valores de conexion y la cadena
      }
    }
  }

	function revisar_individual_procedimiento($vari,$busca,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo){
    list($linker) = GetDBconn();
    $cade = str_replace("*","",$vari);
    $vari=$cade;
		if($ConsultaConteo==true)
					{
					$result=$linker->Execute("$campos AND lower(d.$clave) like '".strtolower($vari)."' ".$_SESSION['CLIENTES_TERCEROS']."");
          $conteo=$result->RecordCount();
          }
					if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}
		$result=$linker->Execute("$campos AND (lower(d.$clave) like '".strtolower($vari)."' ".$_SESSION['CLIENTES_TERCEROS'].") LIMIT ".$this->limit." OFFSET $Of ");
    $this->Mostrar($busca,$result,$alias,$num,$campos,$col1,$col2,$cab,$values);
		$this->RetornarBarra($paso,$conteo,$busca,$clave);
		return $conteo;
  }

	function  revisar_todos_procedimiento($val,$tabla,$clave,$alias,$num,$campos,$col1,$col2,$cab,$values,$Of,$ConsultaConteo,$conteo){//recibe valores de conexion //, la //cadena,la tabla, el campo de busqueda...
    list($linker) = GetDBconn();
    $busca=$val;
    $cade = str_replace("*","",$val);
    $val=$cade;
    $sign = "%";
    $val2 = $sign;
    $val3 = $sign; //se une la estructura de la cadena a buscar  "%nom" o "%nom%" o "nom%"
    $val2 .= $val;
    $val3 .= $val;
    $val3 .= $sign;
    $val .= $sign;
		if($ConsultaConteo==true)
					{
     			$result=$linker->Execute("$campos AND (lower(d.$clave) like '".strtolower($val2)."' or lower(d.$clave) like '".strtolower($val3)."' or lower(d.$clave) like '".strtolower($val)."') ".$_SESSION['CLIENTES_TERCEROS']."");
          $conteo=$result->RecordCount();
          }
					if($Of=='')
					{
							$limit=$this->limit;
							$Of='0';
					}
    $result=$linker->Execute("$campos AND (lower(d.$clave) like '".strtolower($val2)."' or lower(d.$clave) like '".strtolower($val3)."' or lower(d.$clave) like '".strtolower($val)."') ".$_SESSION['CLIENTES_TERCEROS']."  LIMIT ".$this->limit." OFFSET $Of");
    $this->Mostrar($busca,$result,$alias,$num,$campos,$col1,$col2,$cab,$values);
		$this->RetornarBarra($paso,$conteo,$busca,$clave);
   	return $conteo;
  }

}//fin class----
?>



