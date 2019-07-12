<?php

/**
* @package IPSOFT-SIIS
*
* @author    Mauricio Bejarano L. 
* @version   $Revision: 1.6 $
* $Id: app_Os_Mantenimiento_Apoyod_user.php,v 1.6 2008/05/07 19:19:18 cahenao Exp $
* @package   Mantenimiento_Apoyod
* 
*/


class app_Os_Mantenimiento_Apoyod_user extends classModulo
{
	function app_Os_Mantenimiento_Apoyod_user()
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
		if(!$this->Menu())
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
		* Consulta Cargos en Cups
		*/
		function BuscaCargosCups()
		{	
			if( (empty($_REQUEST['cargo'])) && (empty($_REQUEST['descripcion'])) ){
				$this->frmError['cargo']=1;
				$this->frmError["MensajeError"]="FALTAN DATOS PARA LA BUSQUEDA.";
				$this->Consultar_Cargos();
				return true;
			}else{
				$cargo=$_REQUEST['cargo'];
				$descripcion=strtoupper($_REQUEST['descripcion']);
				$buscar_cargo='';
				$busca_descripcion='';
				if($cargo){
					$buscar_cargo="AND a.cargo = '".$cargo."'";
				}
				if($descripcion){
	 				$busca_descripcion.="AND a.descripcion LIKE '%".$descripcion."%'";
				}

				$query="SELECT 	a.cargo,
												a.descripcion
								FROM		cups a LEFT JOIN apoyod_cargos b 
																	ON a.cargo = b.cargo
									WHERE		b.cargo  IS NULL
													$buscar_cargo
													$busca_descripcion
									ORDER BY a.cargo";

				list($dbconn) = GetDBconn();
				$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Consulta cargo cups";
							$this->mensajeDeError = $dbconn->ErrorMsg();
							return false;
					}else
					{
						while(!$result->EOF)
						{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
						$this->Consultar_Cargos($vector,$_REQUEST['accion']);
						return true;
					}
			}
		}

		
		/**
		* Consulta cargos en apoyod
		*/
		function BuscaCargosApoyod()
		{	
			if( (empty($_REQUEST['cargo'])) && (empty($_REQUEST['descripcion'])) ){
				$this->frmError['cargo']=1;
				$this->frmError["MensajeError"]="FALTAN DATOS PARA LA BUSQUEDA.";
				$this->Consultar_Cargos();
				return true;
			}else{
				$cargo=$_REQUEST['cargo'];
				$descripcion=strtoupper($_REQUEST['descripcion']);
				$buscar_cargo='';
				$busca_descripcion='';
				if($cargo){
					$buscar_cargo="AND a.cargo = '".$cargo."'";
				}
				if($descripcion){
					$busca_descripcion=" AND b.descripcion LIKE '%".$descripcion."%'";
				}
				
				$query="SELECT 	a.cargo,
												b.descripcion
								FROM		apoyod_cargos AS a,
												cups AS b
								WHERE		a.cargo = b.cargo 
												$buscar_cargo
												$busca_descripcion
								ORDER BY a.cargo";
				list($dbconn) = GetDBconn();
				$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Consulta cargo apoyod";
							$this->mensajeDeError = $dbconn->ErrorMsg();
							return false;
					}else
					{
						while(!$result->EOF)
						{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
						$this->Consultar_Cargos($vector,$_REQUEST['accion']);
						return true;
					}
			}
		}
		
		/**
		*/
		function SeleccionarCargos(){
			//print_r($_REQUEST);exit;
			if(empty($_REQUEST['seleccion']) ){
				$this->frmError['seleccion']=1;
				$this->frmError["MensajeError"]="FALTO SELECCIONAR UN CARGO.";
				$this->Consultar_Cargos();
				return false;
			}else{
				if($_REQUEST['accion']=='adiciona'){
					$this->ControlAdicion($_REQUEST['seleccion'],'cargo_tecnica');
				}else{
					$this->FmEdicion($_REQUEST['seleccion']);
				}
				return true;
			}
			return false;
		}
		/**
		* paso =cargo_tecnica
		* paso =sub_examen
		* paso =plantilla
		* paso =
		*/
		function ControlAdicion($cargo='',$paso=''){
			$datos=$_REQUEST['datos'];
			$accion = $_REQUEST['accion'];
			//print_r($_REQUEST);
			if(empty($paso)){
				$paso=$_REQUEST['paso'];
			}
			if(empty($cargo)){
				$cargo=$datos['cargo'];
			}
			if($accion=='adiciona_cargo_tecnica'){
				if(!$res=$this->InsertCargoTecnica($datos)){
					$paso ='cargo_tecnica';
				}
			}elseif($accion=='adiciona_lab_examen'){
				if(!$this->InsertLabExamenes($datos)){
					$paso ='sub_examen';
				}
			}
			//
			
			//
			if($paso =='cargo_tecnica'){
				$this->FmSeleccionaTecnica($cargo);
			}elseif($paso=='sub_examen'){
				$datos[apoyod_cargos_tecnicas_id] = $this->GetMaxCargoTecnica();
				$this->FmSeleccionaSubExamen($datos);
			}elseif($paso=='plantilla'){
				$this->FmAdicionaPlantilla($datos);
			}
			return true;
		}
		
		/**
		*
		*/
		function InsertLabExamenes($datos){
		//***

		$dat = explode("||//",$datos[tecnica]);
		$datos[tecnica] = $dat[0];
		$datos[apoyod_cargos_tecnicas_id] = $dat[1];
		//***
			$query="INSERT INTO	lab_examenes (tecnica_id,cargo,lab_examen_id,indice_de_orden,lab_plantilla_id,nombre_examen,apoyod_cargos_tecnicas_id)
							VALUES ('".$datos[tecnica]."','".$datos[cargo]."','".$datos[lab_examen]."','".$datos[orden]."',
											'".$datos[plantilla]."','".$datos[nom_examen]."',".$datos[apoyod_cargos_tecnicas_id].")";
			list($dbconn) = GetDBconn();
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->frmError["MensajeError"]="Error al insertar datos en lab_examenes.";
				return false;
			}
			return true;
		}
		
		/**
		*
		*/
		function InsertCargoTecnica($datos){
			$query="INSERT INTO	apoyod_cargos_tecnicas (tecnica_id,cargo,nombre_tecnica,sw_predeterminado)
							VALUES ('".$datos[tecnica]."','".$datos[cargo]."','".$datos[nombre_tecnica]."','".$datos[sw_predeterminado]."')";
			list($dbconn) = GetDBconn();
      //$dbconn->debug =true;
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->frmError["MensajeError"]="Error al insertar datos en apoyod_cargos_tecnicas.".$dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
				return false;
			}
			return true;
		}
		/**
		*
		*/
		function GetMaxCargoTecnica(){
			$query="SELECT MAX(apoyod_cargos_tecnicas_id)
									FROM apoyod_cargos_tecnicas;";
			list($dbconn) = GetDBconn();
			$dbconn->Execute($query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->frmError["MensajeError"]="Error al seleccionar datos en apoyod_cargos_tecnicas.".$dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
				return false;
			}
			return $result->fields[0];
		}
		/**
		*
		*/
		function ConsultaDescripcion($cargo){
			$query="SELECT 	descripcion
							FROM		cups
							WHERE		cargo = '".$cargo."'";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta plan";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					$descrip=$result->fields[0];
      		$result->Close();
				}
				return $descrip;
		}
		/**
		*
		*/
		function BuscaTecnicaCargo($cargo){
			$query="SELECT 	*
							FROM		apoyod_cargos_tecnicas
							WHERE		cargo = '".$cargo."'
							--order by tecnica_id
							";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta apoyod_cargos_tecnicas";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						echo "error"."[".get_class($this)."][".__LINE__."]";
						return false;
				}
				else
				{
					while(!$result->EOF)
					{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
				}

				//Seleccionar el equivalente entre cargo y tencnica , cuando la tecnica tiene varias plantillas
				//$j=0;
				foreach($vector AS $i => $v)
				{
					$query = "SELECT lab_plantilla_id, lab_examen_id
					FROM  lab_examenes  
					WHERE apoyod_cargos_tecnicas_id = ".$v[apoyod_cargos_tecnicas_id]."
					--AND cargo = '".$cargo."'
					 ";
	
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error en la consulta de tecnicas";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else
					{
							//$i=0;
							if (!$result->EOF)
							{
								//if($vector[$i][tecnica_id] <> '1')
								//{
									$vector[$i][lab_plantilla_id]=$result->fields[0];
									$vector[$i][lab_examen_id]=$result->fields[1];
									//$result->MoveNext();
								//}
								//$j++;
							}
					}
				}

				//FIN Seleccionar el equivalente entre cargo y tencnica , cuando la tecnica tiene varias plantillas
				return $vector;
		}
		
		/**
		*
		*/
		function BuscaPlantillaCargo($cargo_apd,$tecnica_apd){
		//***
		$tecnica = explode("||//",$tecnica_apd);
		$tecnica_apd = $tecnica[0];
		$apoyod_cargos_tecnicas_id = $tecnica[1];
		$cond="";
		if($apoyod_cargos_tecnicas_id)
		{
			$cond = " AND 	apoyod_cargos_tecnicas_id = ".$apoyod_cargos_tecnicas_id." ";
		}
		//***
			$query="SELECT 	lab_plantilla_id, lab_examen_id, tecnica_id
							FROM		lab_examenes
							WHERE		cargo = '".$cargo_apd."' AND
											tecnica_id = '".$tecnica_apd."' $cond ";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta lab_examenes";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					while(!$result->EOF)
					{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
				}
				return $vector;
		}
		
		/**
		*
		*/
		function ConsultaPlantilla($cargo_apd,$tecnica_apd,$plantilla_apd,$modificar=false)
    {
      if(!$modificar)
      {
        $tecnica = explode("||//",$plantilla_apd);
				$cond = " AND apoyod_cargos_tecnicas_id = ".$tecnica[0]." ";
				$tecnica_apd = $tecnica[2];
        $plantilla_apd = $tecnica[3];
      }
      else
      {
        $tecnica = explode("||//",$tecnica_apd);
        $tecnica_apd = $tecnica[0];
        $cond = "AND apoyod_cargos_tecnicas_id = ".$tecnica[1]." ";
      }
      if(!$plantilla_apd)
      {
        $plantilla="lab_examenes";
      }
      else
      {
        $plantilla="lab_plantilla".$plantilla_apd;
      }
			$query="SELECT 	*
							FROM		".$plantilla."
							WHERE		cargo = '".$cargo_apd."' AND
											tecnica_id = '".$tecnica_apd."'
											 $cond
											 ";
                       
			list($dbconn) = GetDBconn();
      
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta lab_examenes";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					while(!$result->EOF)
					{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
          if ($plantilla_apd == 2)
          {
            $sql  = "SELECT B.opcion_id,";
            $sql .= " 	    B.descripcion ,";
            $sql .= " 	    B.cargo 	,";
            $sql .= " 	    B.lab_examen_id,";
            $sql .= " 	    B.lab_examen_opcion_id,";
            $sql .= " 	    B.tecnica_id ";
            $sql .= "FROM   lab_plantilla2 A, opciones_lab_plantilla2 B ";
            $sql .= "WHERE  A.cargo = B.cargo " ;
            $sql .= "AND    A.cargo = '".$cargo_apd."' ";
            $sql .= "AND    A.lab_examen_id = B.lab_examen_id ";
            $sql .= "AND    A.lab_examen_opcion_id = B.lab_examen_opcion_id ";
            $sql .= "AND    A.tecnica_id = B.tecnica_id ";
            $sql .= "AND    A.tecnica_id =  ".$tecnica_apd." ";
            $sql .= $cond;
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) 
            {
              $this->error = "Consulta opciones plantilla";
              $this->mensajeDeError = $dbconn->ErrorMsg();
              return false;
            }
            
            $opciones = array();
            while(!$result->EOF)
  					{
  						$opciones[$result->fields[2]][$result->fields[3]][$result->fields[4]][$result->fields[5]][]=$result->GetRowAssoc($ToUpper = false);
  						$result->MoveNext();
  					}
          }
				}
				return array("vector"=>$vector,"opciones"=>$opciones);
		}
		
		/**
		*
		*/
		function ActualizarDatos(){
			$datos=$_REQUEST['datos'];
			$caso=$_REQUEST['caso'];
			$cargo=$_REQUEST['cargo'];
			$normalidades=$_REQUEST['normalidades'];

			switch($caso){
				case '1':{
									$res=$this->UpdateLabPlantilla1($datos,$normalidades);
									break;
				}
				case '2':{
									$res=$this->UpdateLabPlantilla2($datos,$normalidades);
									break;
				}
				case '3':{
									$res=$this->UpdateLabPlantilla3($datos,$normalidades);
									break;
				}
			}
			if($res){
				$this->Consultar_Cargos();
			}else{
				$this->FmEdicion($cargo);
			}
			return true;
		}
		/**
		*
		*/
		function UpdateLabPlantilla1($datos,$normalidades){
			for($i=0;$i<sizeof($datos);$i++){
				$query="UPDATE	lab_plantilla1
								SET		rango_min = '".$datos[$i]['rango_min']."',
											rango_max = '".$datos[$i]['rango_max']."',
											unidades = '".$datos[$i]['unidades']."',
												normalidades = '".$normalidades."'
								WHERE 	lab_examen_id  = '".$datos[$i]['lab_examen_id']."' AND
												cargo  = '".$datos[$i]['cargo']."' AND
												tecnica_id  = '".$datos[$i]['tecnica_id']."'AND
												edad_min  = '".$datos[$i]['edad_min']."' AND
												edad_max  = '".$datos[$i]['edad_max']."' AND
												sexo_id  = '".$datos[$i]['sexo_id']."'";
				list($dbconn) = GetDBconn();
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Actualizando lab_plantilla1";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						echo "<br> errrrorrr"."[".get_class($this)."][".__LINE__."]";
						return false;
				}
			}
			
			return true;
		}
		/**
		*
		*/
		function UpdateLabPlantilla2($datos,$normalidades){
			for($i=0;$i<sizeof($datos);$i++){
				$query="UPDATE	lab_plantilla2
								SET		opcion = '".$datos[$i]['opcion']."',
											unidades = '".$datos[$i]['unidades']."',
												normalidades = '".$normalidades."'
								WHERE 	lab_examen_opcion_id  = '".$datos[$i]['lab_examen_opcion_id']."' AND
												lab_examen_id  = '".$datos[$i]['lab_examen_id']."' AND
												cargo  = '".$datos[$i]['cargo']."' AND
												tecnica_id  = '".$datos[$i]['tecnica_id']."'";
				list($dbconn) = GetDBconn();
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Actualizando lab_plantilla2";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						echo "<br> errrrorrr"."[".get_class($this)."][".__LINE__."]";
						return false;
				}
			}
			
			return true;
		}
		
		/**
		*/
		function UpdateLabPlantilla3($datos,$normalidades){
			$query="UPDATE	lab_plantilla3
							SET		detalle = '".$datos[0]['detalle']."',
												normalidades = '".$normalidades."'
							WHERE 	lab_examen_id  = '".$datos[0]['lab_examen_id']."' AND
											cargo  = '".$datos[0]['cargo']."' AND
											tecnica_id  = '".$datos[0]['tecnica_id']."'";
			list($dbconn) = GetDBconn();
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Actualizando lab_plantilla3";
					$this->mensajeDeError = $dbconn->ErrorMsg();
					echo "<br> errrrorrr"."[".get_class($this)."][".__LINE__."]";
					return false;
			}
			return true;
		}
		
		/**
		*
		*/
		function CreaLabExamen($cargo_apd,$tecnica_apd){
		//**
		$dat = explode("||//",$tecnica_apd);
		$tecnica_apd = $dat[0];
		//**
			$query="SELECT 	MAX(lab_examen_id)
							FROM		lab_examenes
							WHERE		cargo = '".$cargo_apd."' AND
											tecnica_id = '".$tecnica_apd."'";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta plan";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					$max=$result->fields[0];
      		$result->Close();
				}
				$max++;
				return $max;
		}
		/**
		*
		*/
		function AdicionarDatos(){
			$datos=$_REQUEST['datos'];
			$normalidades=$_REQUEST['normalidades'];
			switch($datos[plantilla]){
				case '1':{
									$res=$this->InsertLabPlantilla1($datos,$normalidades);
									break;
				}
				case '2':{
									$res=$this->InsertLabPlantilla2($datos,$normalidades);
									break;
				}
				case '3':{
									$res=$this->InsertLabPlantilla3($datos,$normalidades);
									break;
				}
			}
			$this->Menu();
			return true;
		}
		
		/**
		*
		*/
		function InsertLabPlantilla1($datos,$normalidades)
    {
  		//***
  		$dat = explode("||//",$datos[tecnica]);
  		$datos[tecnica] = $dat[0];
  		$datos[apoyod_cargos_tecnicas_id] = $dat[1];
  		//***
      if(!$datos[edad_min]) $datos[edad_min] = "0";
      if(!$datos[edad_max]) $datos[edad_max] = "0";
      
			$query="INSERT INTO	lab_plantilla1(sexo_id,rango_min,rango_max,lab_examen_id,edad_min,edad_max,unidades,cargo,tecnica_id,normalidades,apoyod_cargos_tecnicas_id)
							VALUES 
              ( 
                '".$datos[sexo]."',
                '".$datos[rango_min]."',
                '".$datos[rango_max]."',
                 ".$datos[lab_examen].",
                 ".$datos[edad_min].",
                 ".$datos[edad_max].",
                '".$datos[unidades]."',
                '".$datos[cargo]."',
								 ".$datos[tecnica].",
                '".$normalidades."',
								 ".$datos[apoyod_cargos_tecnicas_id]."
              )";
											
			list($dbconn) = GetDBconn();
      
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) 
      {
        $this->frmError["MensajeError"]="Revise los datos. No se pudo ingresar informacion";
        $this->error = "Insertando lab_plantilla1";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        echo $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
        return false;
			}
			return true;
		}
		
		/**
		*
		*/
		function InsertLabPlantilla2($datos,$normalidades){
		//***
		$dat = explode("||//",$datos[tecnica]);
		$datos[tecnica] = $dat[0];
		$datos[apoyod_cargos_tecnicas_id] = $dat[1];
		//***
			$query="INSERT INTO	lab_plantilla2(lab_examen_opcion_id,lab_examen_id,cargo,tecnica_id,opcion,unidades,normalidades,apoyod_cargos_tecnicas_id)
							VALUES ('".$datos[opcion_id]."','".$datos[lab_examen]."','".$datos[cargo]."','".$datos[tecnica]."',
											'".$datos[opcion_des]."','".$datos[unidades]."','".$normalidades."'
											,".$datos[apoyod_cargos_tecnicas_id].")";
											
			list($dbconn) = GetDBconn();
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->frmError["MensajeError"]="Revise los datos. No se pudo ingresar informacion en lab_plantilla2";
					$this->error = "Insertando lab_plantilla2";
					$this->mensajeDeError = $dbconn->ErrorMsg();
					echo $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
					return false;
			}
			return true;
		}
		
		/**
		*
		*/
		function InsertLabPlantilla3($datos,$normalidades){
		//***
		$dat = explode("||//",$datos[tecnica]);
		$datos[tecnica] = $dat[0];
		$datos[apoyod_cargos_tecnicas_id] = $dat[1];
		//***
			$query="INSERT INTO	lab_plantilla3(lab_examen_id,cargo,tecnica_id,detalle,normalidades,apoyod_cargos_tecnicas_id)
							VALUES ('".$datos[lab_examen]."','".$datos[cargo]."','".$datos[tecnica]."','".$datos[detalle]."','".$normalidades."'
							,".$datos[apoyod_cargos_tecnicas_id].")";
							
			list($dbconn) = GetDBconn();
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->frmError["MensajeError"]="Revise los datos. No se pudo ingresar informacion en plantilla 3";
					$this->error = "Insertando lab_plantilla1";
					$this->mensajeDeError = $dbconn->ErrorMsg();
					echo $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
					return false;
			}
			return true;
		}
		
		/**
		*
		*/
		function ConsultaCargosTegnica($cargo){
			$query="SELECT 	*
							FROM		apoyod_cargos_tecnicas
							WHERE		cargo = '".$cargo."'";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta plan";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					while(!$result->EOF)
					{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
				}
				return $vector;
		}
		
		/**
		*
		*/
		function ConsultaLabExamenes($datos){
			$dat = explode("||//",$datos[tecnica]);
	
			$datos[tecnica] = $dat[0];
			$datos[apoyod_cargos_tecnicas_id] = $dat[1];
			$query="SELECT 	*
							FROM		lab_examenes
							WHERE		cargo = '".$datos[cargo]."' AND
											tecnica_id = '".$datos[tecnica]."'
											AND 	apoyod_cargos_tecnicas_id = ".$datos[apoyod_cargos_tecnicas_id]."";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta lab_examenes";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					while(!$result->EOF)
					{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
				}
				return $vector;
		}
		
		/**
		*
		*/
		function ConsultaMaxOpcion($cargo_apd,$tecnica_apd){
		//***
		$dat = explode("||//",$tecnica_apd);
		$tecnica_apd = $dat[0];
		//***
			$query="SELECT 	MAX(lab_examen_opcion_id)
							FROM		lab_plantilla2
							WHERE		cargo = '".$cargo_apd."' AND
											tecnica_id = '".$tecnica_apd."'";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Consulta plan";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						return false;
				}else
				{
					$max=$result->fields[0];
      		$result->Close();
				}
				$max++;
				return $max;
		}
}//fin clase user

?>