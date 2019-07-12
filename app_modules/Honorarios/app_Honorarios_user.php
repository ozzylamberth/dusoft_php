
<?php

/**
* Modulo de Honorarios (PHP).
*
* Modulo para la liquidación de los honorarios profesionales, por grupos o cargos
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Honorarios_user.php
*
* Establece los terminos de liquidación de los honorarios a los profesionales medicos,
* sea a modo individual o como un pool, que puede tener o no un tercero para su contabilidad,
* así mismo estos honorarios pueden ser en el ámbito del cargo o clasificación del
* grupo tipo cargo y tipo cargo, ofreciendo la capacidad de ligarse a un servicio como
* segundo nivel y a un plan de contratación como tercer nivel, con horarios adicionales
**/

class app_Honorarios_user extends classModulo
{
	var $uno;//para los errores
	var $dos;//para los errores
	var $limit;
	var $conteo;

	function app_Honorarios_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalHonora2();
		return true;
	}

	function UsuariosHonora()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query ="SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_honorarios AS A,
				empresas AS B
				WHERE A.usuario_id=".$usuario."
				AND A.empresa_id=B.empresa_id
				ORDER BY descripcion1;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var1[$resulta->fields[1]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$mtz[0]='EMPRESAS';
		$url[0]='app';
		$url[1]='Honorarios';
		$url[2]='user';
		$url[3]='PrincipalHonora';
		$url[4]='permisohonorario';
		$this->salida .=gui_theme_menu_acceso('HONORARIOS', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
		return true;
	}

	function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			else
			{
				return ("label_error");
			}
		}
		return ("label");
	}

	function CalcularNumeroPasos($conteo)//Función de las barras
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso)//Función de las barras
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso)//Función de las barras
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	function RetornarBarraProGruHon()//Barra paginadora de los profesionales por grupo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraProGruSerHon()//Barra paginadora de los profesionales por grupo y servicio
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoSerHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraProGruPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoPlaHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPoPlProGruPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposProfPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraProCarHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraProCarSerHon()//Barra paginadora de los profesionales por cargo y servicio
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoSerHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraCarHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','CargosHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraCarSerHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','CargosSerHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraProCarPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoPlaHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraCarPolPlaHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPoPlProCarPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosProfPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function TercerosHonora()//Trae los datos para el combo, del tipo de identificación de los terceros
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_tercero,
				descripcion
				FROM tipo_id_terceros
				ORDER BY indice_de_orden;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function PlanesHonora($empresa)//Trae los datos para el combo, del tipo de identificación de los terceros
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT plan_id,
				plan_descripcion,
				num_contrato
				FROM planes
				WHERE empresa_id='".$empresa."'
				AND estado='1'
				ORDER BY num_contrato;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	/*PROFESIONAL GRUPOS*/

	function BuscarProfesionalGrupoHonora($empresa)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['tipodohono'])
		{
			$codigo=$_REQUEST['tipodohono'];
			$busqueda1="AND A.tipo_id_tercero='$codigo'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda2="AND A.tercero_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda3="AND UPPER(B.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.tipo_id_tercero,
					A.tercero_id,
					A.estado,
					B.nombre_tercero,
					(SELECT COUNT(C.honorario_grupo_id)
					FROM prof_honorarios_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND B.tipo_id_tercero=C.tipo_id_tercero
					AND B.tercero_id=C.tercero_id) AS honorarios
					FROM profesionales AS A,
					terceros AS B
					WHERE A.tipo_id_tercero=B.tipo_id_tercero
					AND A.tercero_id=B.tercero_id
					$busqueda1
					$busqueda2
					$busqueda3
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.tipo_id_tercero,
				A.tercero_id,
				A.estado,
				B.nombre_tercero,
				(SELECT COUNT(C.honorario_grupo_id)
				FROM prof_honorarios_grupos AS C
				WHERE C.empresa_id='".$empresa."'
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id) AS honorarios
				FROM profesionales AS A,
				terceros AS B
				WHERE A.tipo_id_tercero=B.tipo_id_tercero
				AND A.tercero_id=B.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY B.nombre_tercero
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarProfesionalGrupoHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->ProfesionalGrupoHonora();
			return true;
		}
		else
		{
			$_SESSION['honor1']['gruposproh']['tipodoprof']=$_SESSION['honor1']['prgruposho'][$_POST['selprofeho']]['tipo_id_tercero'];
			$_SESSION['honor1']['gruposproh']['documeprof']=$_SESSION['honor1']['prgruposho'][$_POST['selprofeho']]['tercero_id'];
			$_SESSION['honor1']['gruposproh']['nombreprof']=$_SESSION['honor1']['prgruposho'][$_POST['selprofeho']]['nombre_tercero'];
			$this->GruposHonora();//UNSET($_SESSION['honor1']['prgruposho']);
			return true;
		}
	}

	function BuscarGruposHonora($empresa,$tipoid,$tercer)//Busca todos los grupos y tipos cargos, y los que tenga guardados el profesional
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT DISTINCT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
				D.porcentaje,
				D.honorario_grupo_id,
					(SELECT COUNT (C.honorario_grupo_id)
					FROM prof_honorarios_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo
					AND C.tipo_id_tercero='".$tipoid."'
					AND C.tercero_id='".$tercer."'
					AND (C.servicio IS NOT NULL
					OR C.plan_id IS NOT NULL)) AS honorarios
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B
				LEFT JOIN prof_honorarios_grupos AS D ON
				(D.empresa_id='".$empresa."'
				AND D.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND D.tipo_cargo=B.tipo_cargo
				AND D.tipo_id_tercero='".$tipoid."'
				AND D.tercero_id='".$tercer."'
				AND D.servicio IS NULL
				AND D.plan_id IS NULL),
				cups AS E
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=E.grupo_tipo_cargo
				AND B.tipo_cargo=E.tipo_cargo
				AND E.sw_honorarios='1'
				ORDER BY A.grupo_tipo_cargo, B.tipo_cargo;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposHonora()//Válida los porcentajes de los honorarios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor1']['pgrupocaho']);
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			while($_SESSION['honor1']['pgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$g1=0;
				if(is_numeric($_POST['porcentaje'.$k])==1)
				{
					$por1=doubleval($_POST['porcentaje'.$k]);
					if($por1 <= 100 AND $por1 >= 0)//999.9999
					{
						$g1=1;
					}
				}
				if($_POST['porcentaje'.$k]<>NULL AND $g1==1 AND
				$_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id']==NULL)
				{
					$contador1++;
					$query ="INSERT INTO prof_honorarios_grupos
							(empresa_id,
							tipo_id_tercero,
							tercero_id,
							grupo_tipo_cargo,
							tipo_cargo,
							porcentaje)
							VALUES
							('".$_SESSION['honora']['empresa']."',
							'".$_SESSION['honor1']['gruposproh']['tipodoprof']."',
							'".$_SESSION['honor1']['gruposproh']['documeprof']."',
							'".$_SESSION['honor1']['pgrupocaho'][$k]['grupo_tipo_cargo']."',
							'".$_SESSION['honor1']['pgrupocaho'][$k]['tipo_cargo']."',
							".$por1.");";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
						$dbconn->RollBackTrans();
						$k=$ciclo;
					}
				}
				else if($_POST['porcentaje'.$k]<>NULL AND $g1==1
				AND $_SESSION['honor1']['pgrupocaho'][$k]['porcentaje']<>$por1
				AND $_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id']<>NULL)
				{
					$contador2++;
					$query ="UPDATE prof_honorarios_grupos SET
							porcentaje=".$por1."
							WHERE honorario_grupo_id=".$_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id'].";";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
						$dbconn->RollBackTrans();
						$k=$ciclo;
					}
				}
				else if($_POST['porcentaje'.$k]==NULL
				AND $_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id']<>NULL)
				{
					$contador3++;
					$query ="DELETE FROM prof_honorarios_grupos
							WHERE honorario_grupo_id=".$_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id'].";";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
						$dbconn->RollBackTrans();
						$k=$ciclo;
					}
				}
				$k++;
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposHonora();
		return true;
	}

	function BuscarGruposAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_grupos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_grupo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor1']['pgrupohadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor1']['pgrupohadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor1']['pgrupoadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_grupos_excep
						(honorario_grupo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor1']['pgrupoadho']['honorario_grupo_id'].",
						".$_SESSION['honor1']['pgrupohadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor1']['pgrupohadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor1']['pgrupohadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor1']['pgrupoadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_grupos_excep SET
						porcentaje=".$por1."
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgrupoadho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor1']['pgrupohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor1']['pgrupohadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_grupos_excep
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgrupoadho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor1']['pgrupohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposAdicioHonora();
		return true;
	}

	function ValidarProfesionalGrupoSerHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->ProfesionalGrupoSerHonora();
			return true;
		}
		else
		{
			$_SESSION['honor1']['gruserproh']['tipodoprof']=$_SESSION['honor1']['prgruserho'][$_POST['selprofeho']]['tipo_id_tercero'];
			$_SESSION['honor1']['gruserproh']['documeprof']=$_SESSION['honor1']['prgruserho'][$_POST['selprofeho']]['tercero_id'];
			$_SESSION['honor1']['gruserproh']['nombreprof']=$_SESSION['honor1']['prgruserho'][$_POST['selprofeho']]['nombre_tercero'];
			$this->GruposSerHonora();
			return true;
		}
	}

	function BuscarServiciosHonora()//Función que busca los servicios disponibles
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT servicio,
				descripcion
				FROM servicios
				WHERE sw_asistencial='1'
				AND servicio<>'0'
				ORDER BY servicio;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarGruposSerHonora($empresa,$tipoid,$tercer)//Busca los grupos tipos cargos, con porcentajes y servicios, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT DISTINCT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
				D.porcentaje,
				D.honorario_grupo_id,
				D.servicio,
					(SELECT COUNT (C.honorario_grupo_id)
					FROM prof_honorarios_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo
					AND C.tipo_id_tercero='".$tipoid."'
					AND C.tercero_id='".$tercer."'
					AND (C.servicio IS NULL
					OR (C.servicio IS NOT NULL
					AND C.plan_id IS NOT NULL))) AS honorarios
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B
				LEFT JOIN prof_honorarios_grupos AS D ON
				(D.empresa_id='".$empresa."'
				AND D.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND D.tipo_cargo=B.tipo_cargo
				AND D.tipo_id_tercero='".$tipoid."'
				AND D.tercero_id='".$tercer."'
				AND D.servicio IS NOT NULL
				AND D.plan_id IS NULL),
				cups AS E
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=E.grupo_tipo_cargo
				AND B.tipo_cargo=E.tipo_cargo
				AND E.sw_honorarios='1'
				ORDER BY A.grupo_tipo_cargo, B.tipo_cargo, D.servicio;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposSerHonora()//Válida los porcentajes de los honorarios, por servicios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor1']['pgruservho']);
		$ciclo1=sizeof($_SESSION['honor1']['servprgrh1']);
		for($i=0;$i<$ciclo;)//$i++
		{
			$k=$i;
			while($_SESSION['honor1']['pgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo'])
			{
				$l=$k;
				while($_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$g1=0;
						if(is_numeric($_POST['porcentaje'.$k.$s])==1)
						{
							$por1=doubleval($_POST['porcentaje'.$k.$s]);
							if($por1 <= 100 AND $por1 >= 0)//999.9999
							{
								$g1=1;
							}
						}
						if(($_SESSION['honor1']['pgruservho'][$k]['servicio']==NULL
						OR $_SESSION['honor1']['pgruservho'][$l]['servicio']<>$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						AND $_POST['porcentaje'.$k.$s]<>NULL AND $g1==1)
						{
							$contador1++;
							$query ="INSERT INTO prof_honorarios_grupos
									(empresa_id,
									tipo_id_tercero,
									tercero_id,
									grupo_tipo_cargo,
									tipo_cargo,
									servicio,
									porcentaje)
									VALUES
									('".$_SESSION['honora']['empresa']."',
									'".$_SESSION['honor1']['gruserproh']['tipodoprof']."',
									'".$_SESSION['honor1']['gruserproh']['documeprof']."',
									'".$_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']."',
									'".$_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']."',
									'".$_SESSION['honor1']['servprgrh1'][$s]['servicio']."',
									".$por1.");";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
								$dbconn->RollBackTrans();
								$l=$ciclo;
							}
						}
						else if($_SESSION['honor1']['pgruservho'][$l]['servicio']<>NULL
						AND $_SESSION['honor1']['pgruservho'][$l]['porcentaje']<>$por1
						AND $_POST['porcentaje'.$k.$s]<>NULL AND $g1==1
						AND $_SESSION['honor1']['pgruservho'][$l]['servicio']==$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						{
							$contador2++;
							$query ="UPDATE prof_honorarios_grupos SET
									porcentaje=".$por1."
									WHERE honorario_grupo_id=".$_SESSION['honor1']['pgruservho'][$l]['honorario_grupo_id'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
								$dbconn->RollBackTrans();
								$l=$ciclo;
							}
							$l++;
						}
						else if($_SESSION['honor1']['pgruservho'][$l]['servicio']<>NULL
						AND $_POST['porcentaje'.$k.$s]==NULL
						AND $_SESSION['honor1']['pgruservho'][$l]['servicio']==$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						{
							$contador3++;
							$query ="DELETE FROM prof_honorarios_grupos
									WHERE honorario_grupo_id=".$_SESSION['honor1']['pgruservho'][$l]['honorario_grupo_id'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
								$dbconn->RollBackTrans();
								$l=$ciclo;
							}
							$l++;
						}
						else if($_SESSION['honor1']['pgruservho'][$l]['servicio']==$_SESSION['honor1']['servprgrh1'][$s]['servicio']
						AND $_SESSION['honor1']['pgruservho'][$l]['servicio']<>NULL)
						{
							$l++;
						}
						if($_SESSION['honor1']['pgruservho'][$k]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
					}
				}
				$k=$l;
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposSerHonora();
		return true;
	}

	function BuscarGruposSerAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados, por servicio
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_grupos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_grupo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposSerAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor1']['pgrusehadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor1']['pgrusehadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor1']['pgruseadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_grupos_excep
						(honorario_grupo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor1']['pgruseadho']['honorario_grupo_id'].",
						".$_SESSION['honor1']['pgrusehadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor1']['pgrusehadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor1']['pgrusehadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor1']['pgruseadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_grupos_excep SET
						porcentaje=".$por1."
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgruseadho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor1']['pgrusehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor1']['pgrusehadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_grupos_excep
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgruseadho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor1']['pgrusehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposSerAdicioHonora();
		return true;
	}

	function ValidarProfesionalGrupoPlaHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->ProfesionalGrupoPlaHonora();
			return true;
		}
		else
		{
			$_SESSION['honor1']['gruplaproh']['tipodoprof']=$_SESSION['honor1']['prgruplaho'][$_POST['selprofeho']]['tipo_id_tercero'];
			$_SESSION['honor1']['gruplaproh']['documeprof']=$_SESSION['honor1']['prgruplaho'][$_POST['selprofeho']]['tercero_id'];
			$_SESSION['honor1']['gruplaproh']['nombreprof']=$_SESSION['honor1']['prgruplaho'][$_POST['selprofeho']]['nombre_tercero'];
			$this->GruposPlaHonora();
			return true;
		}
	}

	function BuscarGruposPlaHonora($empresa,$tipoid,$tercer)//Busca los grupos tipos cargos, con porcentajes y planes, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT DISTINCT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
					(SELECT COUNT (C.honorario_grupo_id)
					FROM prof_honorarios_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo
					AND C.tipo_id_tercero='".$tipoid."'
					AND C.tercero_id='".$tercer."'
					AND (C.plan_id IS NULL
					OR (C.plan_id IS NOT NULL
					AND C.servicio IS NOT NULL))) AS honorarios,
					(SELECT COUNT (D.honorario_grupo_id)
					FROM prof_honorarios_grupos AS D 
					WHERE D.empresa_id='".$empresa."'
					AND D.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND D.tipo_cargo=B.tipo_cargo
					AND D.tipo_id_tercero='".$tipoid."'
					AND D.tercero_id='".$tercer."'
					AND D.servicio IS NULL
					AND D.plan_id IS NOT NULL) AS honoraplan
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B,
				cups AS E
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=E.grupo_tipo_cargo
				AND B.tipo_cargo=E.tipo_cargo
				AND E.sw_honorarios='1'
				ORDER BY A.grupo_tipo_cargo, B.tipo_cargo;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPorcPlanGruposProfPlaHonora($empresa,$grupot,$tipoca)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda="AND A.num_contrato LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.plan_descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.plan_id,
					A.plan_descripcion,
					A.num_contrato,
					A.estado,
					B.honorario_grupo_id,
					B.porcentaje
					FROM planes AS A
					LEFT JOIN prof_honorarios_grupos AS B ON
					(B.empresa_id='".$empresa."'
					AND B.grupo_tipo_cargo='".$grupot."'
					AND B.tipo_cargo='".$tipoca."'
					AND B.plan_id=A.plan_id
					AND B.servicio IS NULL)
					WHERE A.empresa_id='".$empresa."'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.plan_id,
				A.plan_descripcion,
				A.num_contrato,
				A.estado,
				B.honorario_grupo_id,
				B.porcentaje
				FROM planes AS A
				LEFT JOIN prof_honorarios_grupos AS B ON
				(B.empresa_id='".$empresa."'
				AND B.grupo_tipo_cargo='".$grupot."'
				AND B.tipo_cargo='".$tipoca."'
				AND B.plan_id=A.plan_id
				AND B.servicio IS NULL)
				WHERE A.empresa_id='".$empresa."'
				$busqueda1
				$busqueda2
				ORDER BY A.num_contrato
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPorcPlanGruposProfPlaHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor1']['pgruplpoho']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id']==NULL
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_grupos
						(empresa_id,
						tipo_id_tercero,
						tercero_id,
						grupo_tipo_cargo,
						tipo_cargo,
						plan_id,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor1']['gruplaproh']['tipodoprof']."',
						'".$_SESSION['honor1']['gruplaproh']['documeprof']."',
						'".$_SESSION['honor1']['pgrplporho']['grupo_tipo_cargo']."',
						'".$_SESSION['honor1']['pgrplporho']['tipo_cargo']."',
						'".$_SESSION['honor1']['pgruplpoho'][$i]['plan_id']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id']<>NULL
			AND $_SESSION['honor1']['pgruplpoho'][$i]['porcentaje']<>$por1
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_grupos SET
						porcentaje=".$por1."
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id']<>NULL
			AND $_POST['porcentaje'.$i]==NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_grupos
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PorcPlanGruposProfPlaHonora();
		return true;
	}

	function BuscarGruposProPlaAdicioHonora($grupoid)//
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_grupos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_grupo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposProPlaAdicioHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor1']['pgruplhadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor1']['pgruplhadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor1']['pgrupladho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_grupos_excep
						(honorario_grupo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor1']['pgrupladho']['honorario_grupo_id'].",
						".$_SESSION['honor1']['pgruplhadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor1']['pgruplhadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor1']['pgruplhadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor1']['pgrupladho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_grupos_excep SET
						porcentaje=".$por1."
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgrupladho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor1']['pgruplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor1']['pgruplhadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_grupos_excep
						WHERE honorario_grupo_id=".$_SESSION['honor1']['pgrupladho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor1']['pgruplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposProPlaAdicioHonora();
		return true;
	}

	/*PROFESIONAL CARGOS*/

	function BuscarProfesionalCargoHonora($empresa)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['tipodohono'])
		{
			$codigo=$_REQUEST['tipodohono'];
			$busqueda1="AND A.tipo_id_tercero='$codigo'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda2="AND A.tercero_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda3="AND UPPER(B.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.tipo_id_tercero,
					A.tercero_id,
					A.estado,
					B.nombre_tercero,
					(SELECT COUNT(C.honorario_grupo_id)
					FROM prof_honorarios_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND B.tipo_id_tercero=C.tipo_id_tercero
					AND B.tercero_id=C.tercero_id) AS honorarios,
					(SELECT COUNT(D.honorario_cargo_id)
					FROM prof_honorarios_cargos AS D
					WHERE D.empresa_id='".$empresa."'
					AND B.tipo_id_tercero=D.tipo_id_tercero
					AND B.tercero_id=D.tercero_id) AS honorarios1
					FROM profesionales AS A,
					terceros AS B
					WHERE A.tipo_id_tercero=B.tipo_id_tercero
					AND A.tercero_id=B.tercero_id
					$busqueda1
					$busqueda2
					$busqueda3
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.tipo_id_tercero,
				A.tercero_id,
				A.estado,
				B.nombre_tercero,
				(SELECT COUNT(C.honorario_grupo_id)
				FROM prof_honorarios_grupos AS C
				WHERE C.empresa_id='".$empresa."'
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id) AS honorarios,
				(SELECT COUNT(D.honorario_cargo_id)
				FROM prof_honorarios_cargos AS D
				WHERE D.empresa_id='".$empresa."'
				AND B.tipo_id_tercero=D.tipo_id_tercero
				AND B.tercero_id=D.tercero_id) AS honorarios1
				FROM profesionales AS A,
				terceros AS B
				WHERE A.tipo_id_tercero=B.tipo_id_tercero
				AND A.tercero_id=B.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY B.nombre_tercero
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarProfesionalCargoHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->ProfesionalCargoHonora();
			return true;
		}
		else
		{
			$_SESSION['honor2']['cargosproh']['tipodoprof']=$_SESSION['honor2']['prcargosho'][$_POST['selprofeho']]['tipo_id_tercero'];
			$_SESSION['honor2']['cargosproh']['documeprof']=$_SESSION['honor2']['prcargosho'][$_POST['selprofeho']]['tercero_id'];
			$_SESSION['honor2']['cargosproh']['nombreprof']=$_SESSION['honor2']['prcargosho'][$_POST['selprofeho']]['nombre_tercero'];
			$this->CargosHonora();
			return true;
		}
	}

	function BuscarCargosHonora($empresa,$tipoid,$tercer)//Busca todos los cargos y los que tenga guardados el profesional
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
					D.porcentaje,
					D.honorario_cargo_id,
					E.porcentaje AS porcengrup,
						(SELECT COUNT (F.honorario_cargo_id)
						FROM prof_honorarios_cargos AS F
						WHERE F.empresa_id='".$empresa."'
						AND F.cargo=A.cargo
						AND F.tipo_id_tercero='".$tipoid."'
						AND F.tercero_id='".$tercer."'
						AND (F.servicio IS NOT NULL
						OR F.plan_id IS NOT NULL)) AS honorarios
					FROM cups AS A
					LEFT JOIN prof_honorarios_cargos AS D ON
					(D.empresa_id='".$empresa."'
					AND A.cargo=D.cargo
					AND D.tipo_id_tercero='".$tipoid."'
					AND D.tercero_id='".$tercer."'
					AND D.servicio IS NULL
					AND D.plan_id IS NULL)
					LEFT JOIN prof_honorarios_grupos AS E ON
					(E.empresa_id='".$empresa."'
					AND E.grupo_tipo_cargo=A.grupo_tipo_cargo
					AND E.tipo_cargo=A.tipo_cargo
					AND E.tipo_id_tercero='".$tipoid."'
					AND E.tercero_id='".$tercer."'
					AND E.servicio IS NULL
					AND E.plan_id IS NULL)
					WHERE A.sw_honorarios='1'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
				D.porcentaje,
				D.honorario_cargo_id,
				E.porcentaje AS porcengrup,
					(SELECT COUNT (F.honorario_cargo_id)
					FROM prof_honorarios_cargos AS F
					WHERE F.empresa_id='".$empresa."'
					AND F.cargo=A.cargo
					AND F.tipo_id_tercero='".$tipoid."'
					AND F.tercero_id='".$tercer."'
					AND (F.servicio IS NOT NULL
					OR F.plan_id IS NOT NULL)) AS honorarios
				FROM cups AS A
				LEFT JOIN prof_honorarios_cargos AS D ON
				(D.empresa_id='".$empresa."'
				AND A.cargo=D.cargo
				AND D.tipo_id_tercero='".$tipoid."'
				AND D.tercero_id='".$tercer."'
				AND D.servicio IS NULL
				AND D.plan_id IS NULL)
				LEFT JOIN prof_honorarios_grupos AS E ON
				(E.empresa_id='".$empresa."'
				AND E.grupo_tipo_cargo=A.grupo_tipo_cargo
				AND E.tipo_cargo=A.tipo_cargo
				AND E.tipo_id_tercero='".$tipoid."'
				AND E.tercero_id='".$tercer."'
				AND E.servicio IS NULL
				AND E.plan_id IS NULL)
				WHERE A.sw_honorarios='1'
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosHonora()//Válida los porcentajes de los honorarios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor2']['pcargocaho']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcentaje'.$i]<>NULL AND $g1==1 AND
			$_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id']==NULL AND
			$_SESSION['honor2']['pcargocaho'][$i]['porcengrup']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_cargos
						(empresa_id,
						tipo_id_tercero,
						tercero_id,
						cargo,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor2']['cargosproh']['tipodoprof']."',
						'".$_SESSION['honor2']['cargosproh']['documeprof']."',
						'".$_SESSION['honor2']['pcargocaho'][$i]['cargo']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcentaje'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcargocaho'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id']<>NULL
			AND $_SESSION['honor2']['pcargocaho'][$i]['porcengrup']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_cargos SET
						porcentaje=".$por1."
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcentaje'.$i]==NULL
			AND $_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_cargos
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosHonora();
		return true;
	}

	function BuscarCargosAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados, por servicio
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_cargos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_cargo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor2']['pcargohadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcargohadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor2']['pcargoadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_cargos_excep
						(honorario_cargo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor2']['pcargoadho']['honorario_cargo_id'].",
						".$_SESSION['honor2']['pcargohadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcargohadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor2']['pcargohadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor2']['pcargoadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_cargos_excep SET
						porcentaje=".$por1."
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcargoadho']['honorario_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor2']['pcargohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor2']['pcargohadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_cargos_excep
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcargoadho']['honorario_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor2']['pcargohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosAdicioHonora();
		return true;
	}

	function ValidarProfesionalCargoSerHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->ProfesionalCargoSerHonora();
			return true;
		}
		else
		{
			$_SESSION['honor2']['carserproh']['tipodoprof']=$_SESSION['honor2']['prcarserho'][$_POST['selprofeho']]['tipo_id_tercero'];
			$_SESSION['honor2']['carserproh']['documeprof']=$_SESSION['honor2']['prcarserho'][$_POST['selprofeho']]['tercero_id'];
			$_SESSION['honor2']['carserproh']['nombreprof']=$_SESSION['honor2']['prcarserho'][$_POST['selprofeho']]['nombre_tercero'];
			$this->CargosSerHonora();
			return true;
		}
	}

	function BuscarCargosSerHonora($empresa,$tipoid,$tercer)//Busca los grupos tipos cargos, con porcentajes y servicios, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
						(SELECT COUNT (B.honorario_cargo_id)
						FROM prof_honorarios_cargos AS B
						WHERE B.empresa_id='".$empresa."'
						AND B.cargo=A.cargo
						AND B.tipo_id_tercero='".$tipoid."'
						AND B.tercero_id='".$tercer."'
						AND (B.servicio IS NULL
						OR (B.servicio IS NOT NULL
						AND B.plan_id IS NOT NULL))) AS honorarios,
						(SELECT COUNT (C.honorario_cargo_id)
						FROM prof_honorarios_cargos AS C
						WHERE C.empresa_id='".$empresa."'
						AND C.cargo=A.cargo
						AND C.tipo_id_tercero='".$tipoid."'
						AND C.tercero_id='".$tercer."'
						AND (C.servicio IS NOT NULL
						AND C.plan_id IS NULL)) AS honoservic
					FROM cups AS A
					WHERE A.sw_honorarios='1'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
					(SELECT COUNT (B.honorario_cargo_id)
					FROM prof_honorarios_cargos AS B
					WHERE B.empresa_id='".$empresa."'
					AND B.cargo=A.cargo
					AND B.tipo_id_tercero='".$tipoid."'
					AND B.tercero_id='".$tercer."'
					AND (B.servicio IS NULL
					OR (B.servicio IS NOT NULL
					AND B.plan_id IS NOT NULL))) AS honorarios,
					(SELECT COUNT (C.honorario_cargo_id)
					FROM prof_honorarios_cargos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.cargo=A.cargo
					AND C.tipo_id_tercero='".$tipoid."'
					AND C.tercero_id='".$tercer."'
					AND (C.servicio IS NOT NULL
					AND C.plan_id IS NULL)) AS honoservic
				FROM cups AS A
				WHERE A.sw_honorarios='1'
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPorcServCargosSerHonora($empresa,$tipoid,$tercer,$cargo)//Los servicios y porcentajes del cargo seleccionado
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.honorario_cargo_id,
				A.servicio,
				A.porcentaje
				FROM prof_honorarios_cargos AS A
				WHERE A.empresa_id='".$empresa."'
				AND A.cargo='".$cargo."'
				AND A.tipo_id_tercero='".$tipoid."'
				AND A.tercero_id='".$tercer."'
				AND (A.servicio IS NOT NULL
				AND A.plan_id IS NULL)
				ORDER BY A.servicio;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPorcServCargosSerHonora()//Válida los porcentajes de un cargo con los servicios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor2']['servprcah1']);
		$i=0;
		for($s=0;$s<$ciclo;$s++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i.$s])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i.$s]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if(($_SESSION['honor2']['pcarsepoho'][$i]['servicio']<>$_SESSION['honor2']['servprcah1'][$s]['servicio']
			OR empty($_SESSION['honor2']['pcarsepoho']))
			AND $g1==1 AND $_POST['porcentaje'.$i.$s]<>NULL)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_cargos
						(empresa_id,
						tipo_id_tercero,
						tercero_id,
						cargo,
						servicio,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor2']['carserproh']['tipodoprof']."',
						'".$_SESSION['honor2']['carserproh']['documeprof']."',
						'".$_SESSION['honor2']['pcaseporho']['cargo']."',
						'".$_SESSION['honor2']['servprcah1'][$s]['servicio']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$s=$ciclo;
				}
			}
			else if($_SESSION['honor2']['pcarsepoho'][$i]['servicio']==$_SESSION['honor2']['servprcah1'][$s]['servicio']
			AND $g1==1 AND $_POST['porcentaje'.$i.$s]<>NULL AND $_SESSION['honor2']['pcarsepoho'][$i]['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_cargos SET
						porcentaje=".$por1."
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarsepoho'][$i]['honorario_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$s=$ciclo;
				}
				$i++;
			}
			else if($_SESSION['honor2']['pcarsepoho'][$i]['servicio']==$_SESSION['honor2']['servprcah1'][$s]['servicio']
			AND $_POST['porcentaje'.$i.$s]==NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_cargos
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarsepoho'][$i]['honorario_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$s=$ciclo;
				}
				$i++;
			}
			else if($_SESSION['honor2']['pcarsepoho'][$i]['servicio']==$_SESSION['honor2']['servprcah1'][$s]['servicio'])
			{
				$i++;
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PorcServCargosSerHonora();
		return true;
	}

	function BuscarCargosSerAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados, por servicio
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_cargos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_cargo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosSerAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor2']['pcarsehadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcarsehadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor2']['pcarseadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_cargos_excep
						(honorario_cargo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor2']['pcarseadho']['honorario_cargo_id'].",
						".$_SESSION['honor2']['pcarsehadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcarsehadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor2']['pcarsehadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor2']['pcarseadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_cargos_excep SET
						porcentaje=".$por1."
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarseadho']['honorario_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor2']['pcarsehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor2']['pcarsehadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_cargos_excep
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarseadho']['honorario_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor2']['pcarsehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosSerAdicioHonora();
		return true;
	}

	function ValidarProfesionalCargoPlaHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->ProfesionalCargoPlaHonora();
			return true;
		}
		else
		{
			$_SESSION['honor2']['carplaproh']['tipodoprof']=$_SESSION['honor2']['prcarplaho'][$_POST['selprofeho']]['tipo_id_tercero'];
			$_SESSION['honor2']['carplaproh']['documeprof']=$_SESSION['honor2']['prcarplaho'][$_POST['selprofeho']]['tercero_id'];
			$_SESSION['honor2']['carplaproh']['nombreprof']=$_SESSION['honor2']['prcarplaho'][$_POST['selprofeho']]['nombre_tercero'];
			$this->CargosPlaHonora();
			return true;
		}
	}

	function BuscarCargosPlaHonora($empresa,$tipoid,$tercer)//Busca los grupos tipos cargos, con porcentajes y planes, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
						(SELECT COUNT (B.honorario_cargo_id)
						FROM prof_honorarios_cargos AS B
						WHERE B.empresa_id='".$empresa."'
						AND B.cargo=A.cargo
						AND B.tipo_id_tercero='".$tipoid."'
						AND B.tercero_id='".$tercer."'
						AND (B.plan_id IS NULL
						OR (B.plan_id IS NOT NULL
						AND B.servicio IS NOT NULL))) AS honorarios,
						(SELECT COUNT (C.honorario_cargo_id)
						FROM prof_honorarios_cargos AS C
						WHERE C.empresa_id='".$empresa."'
						AND C.cargo=A.cargo
						AND C.tipo_id_tercero='".$tipoid."'
						AND C.tercero_id='".$tercer."'
						AND (C.plan_id IS NOT NULL
						AND C.servicio IS NULL)) AS honoraplan
					FROM cups AS A
					WHERE A.sw_honorarios='1'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
					(SELECT COUNT (B.honorario_cargo_id)
					FROM prof_honorarios_cargos AS B
					WHERE B.empresa_id='".$empresa."'
					AND B.cargo=A.cargo
					AND B.tipo_id_tercero='".$tipoid."'
					AND B.tercero_id='".$tercer."'
					AND (B.plan_id IS NULL
					OR (B.plan_id IS NOT NULL
					AND B.servicio IS NOT NULL))) AS honorarios,
					(SELECT COUNT (C.honorario_cargo_id)
					FROM prof_honorarios_cargos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.cargo=A.cargo
					AND C.tipo_id_tercero='".$tipoid."'
					AND C.tercero_id='".$tercer."'
					AND (C.plan_id IS NOT NULL
					AND C.servicio IS NULL)) AS honoraplan
				FROM cups AS A
				WHERE A.sw_honorarios='1'
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPorcPlanCargosProfPlaHonora($empresa,$grupot)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda="AND A.num_contrato LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.plan_descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.plan_id,
					A.plan_descripcion,
					A.num_contrato,
					A.estado,
					B.honorario_cargo_id,
					B.porcentaje
					FROM planes AS A
					LEFT JOIN prof_honorarios_cargos AS B ON
					(B.empresa_id='".$empresa."'
					AND B.cargo='".$grupot."'
					AND B.plan_id=A.plan_id
					AND B.servicio IS NULL
					AND B.tipo_id_tercero='".$_SESSION['honor2']['carplaproh']['tipodoprof']."'
					AND B.tercero_id='".$_SESSION['honor2']['carplaproh']['documeprof']."')
					WHERE A.empresa_id='".$empresa."'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			//cambio dar esto lo agregue al query
			//AND B.tipo_id_tercero='".$_SESSION['honor2']['carplaproh']['tipodoprof']."'
			//			AND B.tercero_id='".$_SESSION['honor2']['carplaproh']['documeprof']."'
		$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.plan_id,
				A.plan_descripcion,
				A.num_contrato,
				A.estado,
				B.honorario_cargo_id,
				B.porcentaje
				FROM planes AS A
				LEFT JOIN prof_honorarios_cargos AS B ON
				(B.empresa_id='".$empresa."'
				AND B.cargo='".$grupot."'
				AND B.plan_id=A.plan_id
				AND B.servicio IS NULL
				AND B.tipo_id_tercero='".$_SESSION['honor2']['carplaproh']['tipodoprof']."'
				AND B.tercero_id='".$_SESSION['honor2']['carplaproh']['documeprof']."')
				WHERE A.empresa_id='".$empresa."'
				$busqueda1
				$busqueda2
				ORDER BY A.num_contrato
				)
				LIMIT ".$this->limit." OFFSET $Of;";

		//cambio dar esto lo agregue al query
		//AND B.tipo_id_tercero='".$_SESSION['honor2']['carplaproh']['tipodoprof']."'
	//			AND B.tercero_id='".$_SESSION['honor2']['carplaproh']['documeprof']."'

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPorcPlanCargosProfPlaHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor2']['pcarplpoho']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id']==NULL
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_cargos
						(empresa_id,
						tipo_id_tercero,
						tercero_id,
						cargo,
						plan_id,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor2']['carplaproh']['tipodoprof']."',
						'".$_SESSION['honor2']['carplaproh']['documeprof']."',
						'".$_SESSION['honor2']['pcaplporho']['cargo']."',
						'".$_SESSION['honor2']['pcarplpoho'][$i]['plan_id']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id']<>NULL
			AND $_SESSION['honor2']['pcarplpoho'][$i]['porcentaje']<>$por1
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_cargos SET
						porcentaje=".$por1."
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id']<>NULL
			AND $_POST['porcentaje'.$i]==NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_cargos
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PorcPlanCargosProfPlaHonora();
		return true;
	}

	function BuscarCargosProPlaAdicioHonora($grupoid)//
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_cargos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_cargo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosProPlaAdicioHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor2']['pcarplhadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcarplhadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor2']['pcarpladho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_cargos_excep
						(honorario_cargo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor2']['pcarpladho']['honorario_cargo_id'].",
						".$_SESSION['honor2']['pcarplhadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor2']['pcarplhadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor2']['pcarplhadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor2']['pcarpladho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_cargos_excep SET
						porcentaje=".$por1."
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarpladho']['honorario_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor2']['pcarplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor2']['pcarplhadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_cargos_excep
						WHERE honorario_cargo_id=".$_SESSION['honor2']['pcarpladho']['honorario_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor2']['pcarplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosProPlaAdicioHonora();
		return true;
	}

	/*POOL*/

	function RetornarBarraPolGruHon()//Barra paginadora de los profesionales por grupo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoHonora',
		array('conteo'=>$this->conteo,'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPolGruSerHon()//Barra paginadora de los profesionales por grupo y servicio
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoSerHonora',
		array('conteo'=>$this->conteo,'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPolCarHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoHonora',array('conteo'=>$this->conteo,
		'tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPolCarSerHon()//Barra paginadora de los profesionales por cargo y servicio
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoSerHonora',
		array('conteo'=>$this->conteo,'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraCarPolHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraCarPolSerHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolSerHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPolGruPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoPlaHonora',
		array('conteo'=>$this->conteo,'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPoPlPolGruPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposPoolPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPolCarPlaHon()//Barra paginadora de los profesionales por cargo
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargosPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraPoPlPolCarPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosPoolPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	function RetornarBarraCarPlaHon()//Barra paginadora de los profesionales por grupo y plan
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Honorarios','user','CargosPlaHonora',array('conteo'=>$this->conteo,
		'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
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
		return $salida;
	}

	/*POOL GRUPOS*/

	function BuscarPoolGrupoHonora($empresa)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda1="WHERE UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.prof_pool_id,
					A.descripcion,
					A.estado,
					(SELECT COUNT(B.honorario_pool_grupo_id)
					FROM prof_honorarios_pool_grupos AS B
					WHERE B.empresa_id='".$empresa."'
					AND A.prof_pool_id=B.prof_pool_id) AS honorarios
					FROM prof_pool AS A
					$busqueda1
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.prof_pool_id,
				A.descripcion,
				A.estado,
				(SELECT COUNT(B.honorario_pool_grupo_id)
				FROM prof_honorarios_pool_grupos AS B
				WHERE B.empresa_id='".$empresa."'
				AND A.prof_pool_id=B.prof_pool_id) AS honorarios
				FROM prof_pool AS A
				$busqueda1
				ORDER BY A.descripcion
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPoolGrupoHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->PoolGrupoHonora();
			return true;
		}
		else
		{
			$_SESSION['honor3']['grupospolh']['poolidprof']=$_SESSION['honor3']['logruposho'][$_POST['selprofeho']]['prof_pool_id'];
			$_SESSION['honor3']['grupospolh']['nombreprof']=$_SESSION['honor3']['logruposho'][$_POST['selprofeho']]['descripcion'];
			$this->GruposPoolHonora();
			return true;
		}
	}

	function BuscarGruposPoolHonora($empresa,$tipoid)//Busca todos los grupos y tipos cargos, y los que tenga guardados el profesional
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT DISTINCT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
				D.porcentaje,
				D.honorario_pool_grupo_id,
					(SELECT COUNT (C.honorario_pool_grupo_id)
					FROM prof_honorarios_pool_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo
					AND C.prof_pool_id=".$tipoid."
					AND (C.servicio IS NOT NULL
					OR C.plan_id IS NOT NULL)) AS honorarios
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B
				LEFT JOIN prof_honorarios_pool_grupos AS D ON
				(D.empresa_id='".$empresa."'
				AND D.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND D.tipo_cargo=B.tipo_cargo
				AND D.prof_pool_id=".$tipoid."
				AND D.servicio IS NULL
				AND D.plan_id IS NULL),
				cups AS E
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=E.grupo_tipo_cargo
				AND B.tipo_cargo=E.tipo_cargo
				AND E.sw_honorarios='1'
				ORDER BY A.grupo_tipo_cargo, B.tipo_cargo;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposPoolHonora()//Válida los porcentajes de los honorarios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor3']['lgrupocaho']);
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			while($_SESSION['honor3']['lgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$g1=0;
				if(is_numeric($_POST['porcentaje'.$k])==1)
				{
					$por1=doubleval($_POST['porcentaje'.$k]);
					if($por1 <= 100 AND $por1 >= 0)//999.9999
					{
						$g1=1;
					}
				}
				if($_POST['porcentaje'.$k]<>NULL AND $g1==1 AND
				$_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id']==NULL)
				{
					$contador1++;
					$query ="INSERT INTO prof_honorarios_pool_grupos
							(empresa_id,
							prof_pool_id,
							grupo_tipo_cargo,
							tipo_cargo,
							porcentaje)
							VALUES
							('".$_SESSION['honora']['empresa']."',
							'".$_SESSION['honor3']['grupospolh']['poolidprof']."',
							'".$_SESSION['honor3']['lgrupocaho'][$k]['grupo_tipo_cargo']."',
							'".$_SESSION['honor3']['lgrupocaho'][$k]['tipo_cargo']."',
							".$por1.");";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
						$dbconn->RollBackTrans();
						$k=$ciclo;
					}
				}
				else if($_POST['porcentaje'.$k]<>NULL AND $g1==1
				AND $_SESSION['honor3']['lgrupocaho'][$k]['porcentaje']<>$por1
				AND $_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id']<>NULL)
				{
					$contador2++;
					$query ="UPDATE prof_honorarios_pool_grupos SET
							porcentaje=".$por1."
							WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id'].";";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
						$dbconn->RollBackTrans();
						$k=$ciclo;
					}
				}
				else if($_POST['porcentaje'.$k]==NULL
				AND $_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id']<>NULL)
				{
					$contador3++;
					$query ="DELETE FROM prof_honorarios_pool_grupos
							WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id'].";";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
						$dbconn->RollBackTrans();
						$k=$ciclo;
					}
				}
				$k++;
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposPoolHonora();
		return true;
	}

	function BuscarPoolGruposAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_pool_grupos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_pool_grupo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPoolGruposAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor3']['lgrupohadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor3']['lgrupohadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor3']['lgrupoadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_grupos_excep
						(honorario_pool_grupo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor3']['lgrupoadho']['honorario_pool_grupo_id'].",
						".$_SESSION['honor3']['lgrupohadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor3']['lgrupohadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor3']['lgrupohadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor3']['lgrupoadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_grupos_excep SET
						porcentaje=".$por1."
						WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgrupoadho']['honorario_pool_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor3']['lgrupohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor3']['lgrupohadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_grupos_excep
						WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgrupoadho']['honorario_pool_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor3']['lgrupohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PoolGruposAdicioHonora();
		return true;
	}

	function ValidarPoolGrupoSerHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->PoolGrupoSerHonora();
			return true;
		}
		else
		{
			$_SESSION['honor3']['gruserpolh']['poolidprof']=$_SESSION['honor3']['logruserho'][$_POST['selprofeho']]['prof_pool_id'];
			$_SESSION['honor3']['gruserpolh']['nombreprof']=$_SESSION['honor3']['logruserho'][$_POST['selprofeho']]['descripcion'];
			$this->GruposPoolSerHonora();
			return true;
		}
	}

	function BuscarGruposPoolSerHonora($empresa,$tipoid)//Busca los grupos tipos cargos, con porcentajes y servicios, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT DISTINCT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
				D.porcentaje,
				D.honorario_pool_grupo_id,
				D.servicio,
					(SELECT COUNT (C.honorario_pool_grupo_id)
					FROM prof_honorarios_pool_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo
					AND C.prof_pool_id='".$tipoid."'
					AND (C.servicio IS NULL
					OR (C.servicio IS NOT NULL
					AND C.plan_id IS NOT NULL))) AS honorarios
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B
				LEFT JOIN prof_honorarios_pool_grupos AS D ON
				(D.empresa_id='".$empresa."'
				AND D.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND D.tipo_cargo=B.tipo_cargo
				AND D.prof_pool_id='".$tipoid."'
				AND D.servicio IS NOT NULL
				AND D.plan_id IS NULL),
				cups AS E
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=E.grupo_tipo_cargo
				AND B.tipo_cargo=E.tipo_cargo
				AND E.sw_honorarios='1'
				ORDER BY A.grupo_tipo_cargo, B.tipo_cargo, D.servicio;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposPoolSerHonora()//Válida los porcentajes de los honorarios, por servicios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor3']['lgruservho']);
		$ciclo1=sizeof($_SESSION['honor3']['servpogrh1']);
		for($i=0;$i<$ciclo;)//$i++
		{
			$k=$i;
			while($_SESSION['honor3']['lgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo'])
			{
				$l=$k;
				while($_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$g1=0;
						if(is_numeric($_POST['porcentaje'.$k.$s])==1)
						{
							$por1=doubleval($_POST['porcentaje'.$k.$s]);
							if($por1 <= 100 AND $por1 >= 0)//999.9999
							{
								$g1=1;
							}
						}
						if(($_SESSION['honor3']['lgruservho'][$k]['servicio']==NULL
						OR $_SESSION['honor3']['lgruservho'][$l]['servicio']<>$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						AND $_POST['porcentaje'.$k.$s]<>NULL AND $g1==1)
						{
							$contador1++;
							$query ="INSERT INTO prof_honorarios_pool_grupos
									(empresa_id,
									prof_pool_id,
									grupo_tipo_cargo,
									tipo_cargo,
									servicio,
									porcentaje)
									VALUES
									('".$_SESSION['honora']['empresa']."',
									'".$_SESSION['honor3']['gruserpolh']['poolidprof']."',
									'".$_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']."',
									'".$_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']."',
									'".$_SESSION['honor3']['servpogrh1'][$s]['servicio']."',
									".$por1.");";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
								$dbconn->RollBackTrans();
								$l=$ciclo;
							}
						}
						else if($_SESSION['honor3']['lgruservho'][$l]['servicio']<>NULL
						AND $_SESSION['honor3']['lgruservho'][$l]['porcentaje']<>$por1
						AND $_POST['porcentaje'.$k.$s]<>NULL AND $g1==1
						AND $_SESSION['honor3']['lgruservho'][$l]['servicio']==$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						{
							$contador2++;
							$query ="UPDATE prof_honorarios_pool_grupos SET
									porcentaje=".$por1."
									WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgruservho'][$l]['honorario_pool_grupo_id'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
								$dbconn->RollBackTrans();
								$l=$ciclo;
							}
							$l++;
						}
						else if($_SESSION['honor3']['lgruservho'][$l]['servicio']<>NULL
						AND $_POST['porcentaje'.$k.$s]==NULL
						AND $_SESSION['honor3']['lgruservho'][$l]['servicio']==$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						{
							$contador3++;
							$query ="DELETE FROM prof_honorarios_pool_grupos
									WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgruservho'][$l]['honorario_pool_grupo_id'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
								$dbconn->RollBackTrans();
								$l=$ciclo;
							}
							$l++;
						}
						else if($_SESSION['honor3']['lgruservho'][$l]['servicio']==$_SESSION['honor3']['servpogrh1'][$s]['servicio']
						AND $_SESSION['honor3']['lgruservho'][$l]['servicio']<>NULL)
						{
							$l++;
						}
						if($_SESSION['honor3']['lgruservho'][$k]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
					}
				}
				$k=$l;
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposPoolSerHonora();
		return true;
	}

	function BuscarGruposPoolSerAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados, por servicio
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_grupos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_grupo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposPoolSerAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor3']['lgrusehadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor3']['lgrusehadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor3']['lgruseadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_grupos_excep
						(honorario_grupo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor3']['lgruseadho']['honorario_grupo_id'].",
						".$_SESSION['honor3']['lgrusehadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor3']['lgrusehadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor3']['lgrusehadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor3']['lgruseadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_grupos_excep SET
						porcentaje=".$por1."
						WHERE honorario_grupo_id=".$_SESSION['honor3']['lgruseadho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor3']['lgrusehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor3']['lgrusehadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_grupos_excep
						WHERE honorario_grupo_id=".$_SESSION['honor3']['lgruseadho']['honorario_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor3']['lgrusehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposPoolSerAdicioHonora();
		return true;
	}

	/*POOL CARGOS*/

	function BuscarPoolCargoHonora($empresa)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda1="WHERE UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.prof_pool_id,
					A.descripcion,
					A.estado,
					(SELECT COUNT(B.honorario_pool_grupo_id)
					FROM prof_honorarios_pool_grupos AS B
					WHERE B.empresa_id='".$empresa."'
					AND A.prof_pool_id=B.prof_pool_id) AS honorarios,
					(SELECT COUNT(C.honorario_pool_cargo_id)
					FROM prof_honorarios_pool_cargos AS C
					WHERE C.empresa_id='".$empresa."'
					AND A.prof_pool_id=C.prof_pool_id) AS honorarios1
					FROM prof_pool AS A
					$busqueda1
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.prof_pool_id,
				A.descripcion,
				A.estado,
				(SELECT COUNT(B.honorario_pool_grupo_id)
				FROM prof_honorarios_pool_grupos AS B
				WHERE B.empresa_id='".$empresa."'
				AND A.prof_pool_id=B.prof_pool_id) AS honorarios,
				(SELECT COUNT(C.honorario_pool_cargo_id)
				FROM prof_honorarios_pool_cargos AS C
				WHERE C.empresa_id='".$empresa."'
				AND A.prof_pool_id=C.prof_pool_id) AS honorarios1
				FROM prof_pool AS A
				$busqueda1
				ORDER BY A.descripcion
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPoolCargoHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->PoolCargoHonora();
			return true;
		}
		else
		{
			$_SESSION['honor4']['cargospolh']['poolidprof']=$_SESSION['honor4']['locargosho'][$_POST['selprofeho']]['prof_pool_id'];
			$_SESSION['honor4']['cargospolh']['nombreprof']=$_SESSION['honor4']['locargosho'][$_POST['selprofeho']]['descripcion'];
			$this->CargosPoolHonora();
			return true;
		}
	}

	function BuscarCargosPoolHonora($empresa,$tipoid)//Busca todos los cargos y los que tenga guardados el profesional
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
					D.porcentaje,
					D.honorario_pool_cargo_id,
					E.porcentaje AS porcengrup,
						(SELECT COUNT (F.honorario_pool_cargo_id)
						FROM prof_honorarios_pool_cargos AS F
						WHERE F.empresa_id='".$empresa."'
						AND F.cargo=A.cargo
						AND F.prof_pool_id=".$tipoid."
						AND (F.servicio IS NOT NULL
						OR F.plan_id IS NOT NULL)) AS honorarios
					FROM cups AS A
					LEFT JOIN prof_honorarios_pool_cargos AS D ON
					(D.empresa_id='".$empresa."'
					AND A.cargo=D.cargo
					AND D.prof_pool_id=".$tipoid."
					AND D.servicio IS NULL
					AND D.plan_id IS NULL)
					LEFT JOIN prof_honorarios_pool_grupos AS E ON
					(E.empresa_id='".$empresa."'
					AND E.grupo_tipo_cargo=A.grupo_tipo_cargo
					AND E.tipo_cargo=A.tipo_cargo
					AND E.prof_pool_id=".$tipoid."
					AND E.servicio IS NULL
					AND E.plan_id IS NULL)
					WHERE A.sw_honorarios='1'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
				D.porcentaje,
				D.honorario_pool_cargo_id,
				E.porcentaje AS porcengrup,
					(SELECT COUNT (F.honorario_pool_cargo_id)
					FROM prof_honorarios_pool_cargos AS F
					WHERE F.empresa_id='".$empresa."'
					AND F.cargo=A.cargo
					AND F.prof_pool_id=".$tipoid."
					AND (F.servicio IS NOT NULL
					OR F.plan_id IS NOT NULL)) AS honorarios
				FROM cups AS A
				LEFT JOIN prof_honorarios_pool_cargos AS D ON
				(D.empresa_id='".$empresa."'
				AND A.cargo=D.cargo
				AND D.prof_pool_id=".$tipoid."
				AND D.servicio IS NULL
				AND D.plan_id IS NULL)
				LEFT JOIN prof_honorarios_pool_grupos AS E ON
				(E.empresa_id='".$empresa."'
				AND E.grupo_tipo_cargo=A.grupo_tipo_cargo
				AND E.tipo_cargo=A.tipo_cargo
				AND E.prof_pool_id=".$tipoid."
				AND E.servicio IS NULL
				AND E.plan_id IS NULL)
				WHERE A.sw_honorarios='1'
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosPoolHonora()//Válida los porcentajes de los honorarios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor4']['lcargocaho']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcentaje'.$i]<>NULL AND $g1==1 AND
			$_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id']==NULL AND
			$_SESSION['honor4']['lcargocaho'][$i]['porcengrup']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_cargos
						(empresa_id,
						prof_pool_id,
						cargo,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor4']['cargospolh']['poolidprof']."',
						'".$_SESSION['honor4']['lcargocaho'][$i]['cargo']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcentaje'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcargocaho'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id']<>NULL
			AND $_SESSION['honor4']['lcargocaho'][$i]['porcengrup']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_cargos SET
						porcentaje=".$por1."
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcentaje'.$i]==NULL
			AND $_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_cargos
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosPoolHonora();
		return true;
	}

	function BuscarCargosPoolAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados, por servicio
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_pool_cargos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_pool_cargo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosPoolAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor4']['lcargohadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcargohadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor4']['lcargoadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_cargos_excep
						(honorario_pool_cargo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor4']['lcargoadho']['honorario_pool_cargo_id'].",
						".$_SESSION['honor4']['lcargohadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcargohadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor4']['lcargohadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor4']['lcargoadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_cargos_excep SET
						porcentaje=".$por1."
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcargoadho']['honorario_pool_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor4']['lcargohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor4']['lcargohadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_cargos_excep
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcargoadho']['honorario_pool_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor4']['lcargohadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosPoolAdicioHonora();
		return true;
	}

	function ValidarPoolCargoSerHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->PoolCargoSerHonora();
			return true;
		}
		else
		{
			$_SESSION['honor4']['carserpolh']['poolidprof']=$_SESSION['honor4']['locarserho'][$_POST['selprofeho']]['prof_pool_id'];
			$_SESSION['honor4']['carserpolh']['nombreprof']=$_SESSION['honor4']['locarserho'][$_POST['selprofeho']]['descripcion'];
			$this->CargosPoolSerHonora();
			return true;
		}
	}

	function BuscarCargosPoolSerHonora($empresa,$tipoid)//Busca los grupos tipos cargos, con porcentajes y servicios, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
						(SELECT COUNT (B.honorario_pool_cargo_id)
						FROM prof_honorarios_pool_cargos AS B
						WHERE B.empresa_id='".$empresa."'
						AND B.cargo=A.cargo
						AND B.prof_pool_id=".$tipoid."
						AND (B.servicio IS NULL
						OR (B.servicio IS NOT NULL
						AND B.plan_id IS NOT NULL))) AS honorarios,
						(SELECT COUNT (C.honorario_pool_cargo_id)
						FROM prof_honorarios_pool_cargos AS C
						WHERE C.empresa_id='".$empresa."'
						AND C.cargo=A.cargo
						AND C.prof_pool_id=".$tipoid."
						AND (C.servicio IS NOT NULL
						AND C.plan_id IS NULL)) AS honoservic
					FROM cups AS A
					WHERE A.sw_honorarios='1'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
					(SELECT COUNT (B.honorario_pool_cargo_id)
					FROM prof_honorarios_pool_cargos AS B
					WHERE B.empresa_id='".$empresa."'
					AND B.cargo=A.cargo
					AND B.prof_pool_id=".$tipoid."
					AND (B.servicio IS NULL
					OR (B.servicio IS NOT NULL
					AND B.plan_id IS NOT NULL))) AS honorarios,
					(SELECT COUNT (C.honorario_pool_cargo_id)
					FROM prof_honorarios_pool_cargos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.cargo=A.cargo
					AND C.prof_pool_id=".$tipoid."
					AND (C.servicio IS NOT NULL
					AND C.plan_id IS NULL)) AS honoservic
				FROM cups AS A
				WHERE A.sw_honorarios='1'
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPorcServCargosPoolSerHonora($empresa,$tipoid,$cargo)//Los servicios y porcentajes del cargo seleccionado
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.honorario_pool_cargo_id,
				A.servicio,
				A.porcentaje
				FROM prof_honorarios_pool_cargos AS A
				WHERE A.empresa_id='".$empresa."'
				AND A.cargo='".$cargo."'
				AND A.prof_pool_id=".$tipoid."
				AND (A.servicio IS NOT NULL
				AND A.plan_id IS NULL)
				ORDER BY A.servicio;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPorcServCargosPoolSerHonora()//Válida los porcentajes de un cargo con los servicios
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor4']['servpocah1']);
		$i=0;
		for($s=0;$s<$ciclo;$s++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i.$s])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i.$s]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if(($_SESSION['honor4']['lcarsepoho'][$i]['servicio']<>$_SESSION['honor4']['servpocah1'][$s]['servicio']
			OR empty($_SESSION['honor4']['lcarsepoho']))
			AND $g1==1 AND $_POST['porcentaje'.$i.$s]<>NULL)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_cargos
						(empresa_id,
						prof_pool_id,
						cargo,
						servicio,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor4']['carserpolh']['poolidprof']."',
						'".$_SESSION['honor4']['lcaseporho']['cargo']."',
						'".$_SESSION['honor4']['servpocah1'][$s]['servicio']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$s=$ciclo;
				}
			}
			else if($_SESSION['honor4']['lcarsepoho'][$i]['servicio']==$_SESSION['honor4']['servpocah1'][$s]['servicio']
			AND $g1==1 AND $_POST['porcentaje'.$i.$s]<>NULL AND $_SESSION['honor4']['lcarsepoho'][$i]['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_cargos SET
						porcentaje=".$por1."
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarsepoho'][$i]['honorario_pool_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$s=$ciclo;
				}
				$i++;
			}
			else if($_SESSION['honor4']['lcarsepoho'][$i]['servicio']==$_SESSION['honor4']['servpocah1'][$s]['servicio']
			AND $_POST['porcentaje'.$i.$s]==NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_cargos
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarsepoho'][$i]['honorario_pool_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$s=$ciclo;
				}
				$i++;
			}
			else if($_SESSION['honor4']['lcarsepoho'][$i]['servicio']==$_SESSION['honor4']['servpocah1'][$s]['servicio'])
			{
				$i++;
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PorcServCargosPoolSerHonora();
		return true;
	}

	function BuscarCargosPoolSerAdicioHonora($grupoid)//Busca los horarios adicionales que tenga creados, por servicio
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_pool_cargos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_pool_cargo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosPoolSerAdicioHonora()//Válida los porcentajes de los horarios adicionales
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor4']['lcarsehadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcarsehadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor4']['lcarseadho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_cargos_excep
						(honorario_pool_cargo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor4']['lcarseadho']['honorario_pool_cargo_id'].",
						".$_SESSION['honor4']['lcarsehadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcarsehadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor4']['lcarsehadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor4']['lcarseadho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_cargos_excep SET
						porcentaje=".$por1."
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarseadho']['honorario_pool_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor4']['lcarsehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor4']['lcarsehadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_cargos_excep
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarseadho']['honorario_pool_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor4']['lcarsehadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosPoolSerAdicioHonora();
		return true;
	}

	function ValidarPoolCargoPlaHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->PoolCargoPlaHonora();
			return true;
		}
		else
		{
			$_SESSION['honor4']['carplapolh']['poolidprof']=$_SESSION['honor4']['locarplaho'][$_POST['selprofeho']]['prof_pool_id'];
			$_SESSION['honor4']['carplapolh']['nombreprof']=$_SESSION['honor4']['locarplaho'][$_POST['selprofeho']]['descripcion'];
			$this->CargosPoolPlaHonora();
			return true;
		}
	}

	function BuscarCargosPoolPlaHonora($empresa,$tipoid)//Busca los grupos tipos cargos, con porcentajes y planes, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
						(SELECT COUNT (B.honorario_pool_cargo_id)
						FROM prof_honorarios_pool_cargos AS B
						WHERE B.empresa_id='".$empresa."'
						AND B.cargo=A.cargo
						AND B.prof_pool_id='".$tipoid."'
						AND (B.plan_id IS NULL
						OR (B.plan_id IS NOT NULL
						AND B.servicio IS NOT NULL))) AS honorarios,
						(SELECT COUNT (C.honorario_pool_cargo_id)
						FROM prof_honorarios_pool_cargos AS C
						WHERE C.empresa_id='".$empresa."'
						AND C.cargo=A.cargo
						AND C.prof_pool_id='".$tipoid."'
						AND (C.plan_id IS NOT NULL
						AND C.servicio IS NULL)) AS honoraplan
					FROM cups AS A
					WHERE A.sw_honorarios='1'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
					(SELECT COUNT (B.honorario_pool_cargo_id)
					FROM prof_honorarios_pool_cargos AS B
					WHERE B.empresa_id='".$empresa."'
					AND B.cargo=A.cargo
					AND B.prof_pool_id='".$tipoid."'
					AND (B.plan_id IS NULL
					OR (B.plan_id IS NOT NULL
					AND B.servicio IS NOT NULL))) AS honorarios,
					(SELECT COUNT (C.honorario_pool_cargo_id)
					FROM prof_honorarios_pool_cargos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.cargo=A.cargo
					AND C.prof_pool_id='".$tipoid."'
					AND (C.plan_id IS NOT NULL
					AND C.servicio IS NULL)) AS honoraplan
				FROM cups AS A
				WHERE A.sw_honorarios='1'
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPorcPlanCargosPoolPlaHonora($empresa,$grupot)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda="AND A.num_contrato LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.plan_descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.plan_id,
					A.plan_descripcion,
					A.num_contrato,
					A.estado,
					B.honorario_pool_cargo_id,
					B.porcentaje
					FROM planes AS A
					LEFT JOIN prof_honorarios_pool_cargos AS B ON
					(B.empresa_id='".$empresa."'
					AND B.cargo='".$grupot."'
					AND B.plan_id=A.plan_id
					AND B.servicio IS NULL)
					WHERE A.empresa_id='".$empresa."'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.plan_id,
				A.plan_descripcion,
				A.num_contrato,
				A.estado,
				B.honorario_pool_cargo_id,
				B.porcentaje
				FROM planes AS A
				LEFT JOIN prof_honorarios_pool_cargos AS B ON
				(B.empresa_id='".$empresa."'
				AND B.cargo='".$grupot."'
				AND B.plan_id=A.plan_id
				AND B.servicio IS NULL)
				WHERE A.empresa_id='".$empresa."'
				$busqueda1
				$busqueda2
				ORDER BY A.num_contrato
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPorcPlanCargosPoolPlaHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor4']['lcarplpoho']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id']==NULL
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_cargos
						(empresa_id,
						prof_pool_id,
						cargo,
						plan_id,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor4']['carplapolh']['poolidprof']."',
						'".$_SESSION['honor4']['lcaplporho']['cargo']."',
						'".$_SESSION['honor4']['lcarplpoho'][$i]['plan_id']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id']<>NULL
			AND $_SESSION['honor4']['lcarplpoho'][$i]['porcentaje']<>$por1
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_cargos SET
						porcentaje=".$por1."
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id']<>NULL
			AND $_POST['porcentaje'.$i]==NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_cargos
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PorcPlanCargosPoolPlaHonora();
		return true;
	}

	function BuscarCargosPolPlaAdicioHonora($grupoid)//
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_pool_cargos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_pool_cargo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosPolPlaAdicioHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor4']['lcarplhadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcarplhadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor4']['lcarpladho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_cargos_excep
						(honorario_pool_cargo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor4']['lcarpladho']['honorario_pool_cargo_id'].",
						".$_SESSION['honor4']['lcarplhadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor4']['lcarplhadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor4']['lcarplhadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor4']['lcarpladho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_cargos_excep SET
						porcentaje=".$por1."
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarpladho']['honorario_pool_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor4']['lcarplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor4']['lcarplhadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_cargos_excep
						WHERE honorario_pool_cargo_id=".$_SESSION['honor4']['lcarpladho']['honorario_pool_cargo_id']."
						AND horario_especial_id=".$_SESSION['honor4']['lcarplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->CargosPolPlaAdicioHonora();
		return true;
	}

	function ValidarPoolGrupoPlaHonora()//Válida que se encuentre seleccionado un profesional
	{
		if($_POST['selprofeho']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN PROFESIONAL ACTIVO";
			$this->uno=1;
			$this->PoolGrupoPlaHonora();
			return true;
		}
		else
		{
			$_SESSION['honor3']['gruplapolh']['poolidprof']=$_SESSION['honor3']['logruplaho'][$_POST['selprofeho']]['prof_pool_id'];
			$_SESSION['honor3']['gruplapolh']['nombreprof']=$_SESSION['honor3']['logruplaho'][$_POST['selprofeho']]['descripcion'];
			$this->GruposPoolPlaHonora();
			return true;
		}
	}

	function BuscarGruposPoolPlaHonora($empresa,$tipoid)//Busca los grupos tipos cargos, con porcentajes y planes, descarta los otros casos
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT DISTINCT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
					(SELECT COUNT (C.honorario_pool_grupo_id)
					FROM prof_honorarios_pool_grupos AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo
					AND C.prof_pool_id='".$tipoid."'
					AND (C.plan_id IS NULL
					OR (C.plan_id IS NOT NULL
					AND C.servicio IS NOT NULL))) AS honorarios,
					(SELECT COUNT (D.honorario_pool_grupo_id)
					FROM prof_honorarios_pool_grupos AS D 
					WHERE D.empresa_id='".$empresa."'
					AND D.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND D.tipo_cargo=B.tipo_cargo
					AND D.prof_pool_id='".$tipoid."'
					AND D.servicio IS NULL
					AND D.plan_id IS NOT NULL) AS honoraplan
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B,
				cups AS E
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=E.grupo_tipo_cargo
				AND B.tipo_cargo=E.tipo_cargo
				AND E.sw_honorarios='1'
				ORDER BY A.grupo_tipo_cargo, B.tipo_cargo;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPorcPlanGruposPoolPlaHonora($empresa,$grupot,$tipoca)//Busca los profesionales y si tienen algún honorario ya creado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigohono'])
		{
			$codigo=$_REQUEST['codigohono'];
			$busqueda="AND A.num_contrato LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrihono'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrihono']);
			$busqueda2="AND UPPER(A.plan_descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.plan_id,
					A.plan_descripcion,
					A.num_contrato,
					A.estado,
					B.honorario_pool_grupo_id,
					B.porcentaje
					FROM planes AS A
					LEFT JOIN prof_honorarios_pool_grupos AS B ON
					(B.empresa_id='".$empresa."'
					AND B.grupo_tipo_cargo='".$grupot."'
					AND B.tipo_cargo='".$tipoca."'
					AND B.plan_id=A.plan_id
					AND B.servicio IS NULL)
					WHERE A.empresa_id='".$empresa."'
					$busqueda1
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "
				(
				SELECT A.plan_id,
				A.plan_descripcion,
				A.num_contrato,
				A.estado,
				B.honorario_pool_grupo_id,
				B.porcentaje
				FROM planes AS A
				LEFT JOIN prof_honorarios_pool_grupos AS B ON
				(B.empresa_id='".$empresa."'
				AND B.grupo_tipo_cargo='".$grupot."'
				AND B.tipo_cargo='".$tipoca."'
				AND B.plan_id=A.plan_id
				AND B.servicio IS NULL)
				WHERE A.empresa_id='".$empresa."'
				$busqueda1
				$busqueda2
				ORDER BY A.num_contrato
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarPorcPlanGruposPoolPlaHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor3']['lgruplpoho']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcentaje'.$i])==1)
			{
				$por1=doubleval($_POST['porcentaje'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id']==NULL
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_grupos
						(empresa_id,
						prof_pool_id,
						grupo_tipo_cargo,
						tipo_cargo,
						plan_id,
						porcentaje)
						VALUES
						('".$_SESSION['honora']['empresa']."',
						'".$_SESSION['honor3']['gruplapolh']['poolidprof']."',
						'".$_SESSION['honor3']['lgrplporho']['grupo_tipo_cargo']."',
						'".$_SESSION['honor3']['lgrplporho']['tipo_cargo']."',
						'".$_SESSION['honor3']['lgruplpoho'][$i]['plan_id']."',
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id']<>NULL
			AND $_SESSION['honor3']['lgruplpoho'][$i]['porcentaje']<>$por1
			AND $_POST['porcentaje'.$i]<>NULL AND $g1==1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_grupos SET
						porcentaje=".$por1."
						WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id']<>NULL
			AND $_POST['porcentaje'.$i]==NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_grupos
						WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->PorcPlanGruposPoolPlaHonora();
		return true;
	}

	function BuscarGruposPolPlaAdicioHonora($grupoid)//
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.horario_especial_id,
				A.descripcion,
				B.porcentaje
				FROM prof_horarios_especiales AS A
				LEFT JOIN prof_honorarios_pool_grupos_excep AS B ON
				(A.horario_especial_id=B.horario_especial_id
				AND honorario_pool_grupo_id=".$grupoid.")
				ORDER BY A.horario_especial_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarGruposPolPlaAdicioHonora()//
	{
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['honor3']['lgruplhadh']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if(is_numeric($_POST['porcenadic'.$i])==1)
			{
				$por1=doubleval($_POST['porcenadic'.$i]);
				if($por1 <= 100 AND $por1 >= 0)//999.9999
				{
					$g1=1;
				}
			}
			if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor3']['lgruplhadh'][$i]['porcentaje']==NULL
			AND $_SESSION['honor3']['lgrupladho']['porcentaje']<>$por1)
			{
				$contador1++;
				$query ="INSERT INTO prof_honorarios_pool_grupos_excep
						(honorario_pool_grupo_id,
						horario_especial_id,
						porcentaje)
						VALUES
						(".$_SESSION['honor3']['lgrupladho']['honorario_pool_grupo_id'].",
						".$_SESSION['honor3']['lgruplhadh'][$i]['horario_especial_id'].",
						".$por1.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]<>NULL AND $g1==1
			AND $_SESSION['honor3']['lgruplhadh'][$i]['porcentaje']<>$por1
			AND $_SESSION['honor3']['lgruplhadh'][$i]['porcentaje']<>NULL
			AND $_SESSION['honor3']['lgrupladho']['porcentaje']<>$por1)
			{
				$contador2++;
				$query ="UPDATE prof_honorarios_pool_grupos_excep SET
						porcentaje=".$por1."
						WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgrupladho']['honorario_pool_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor3']['lgruplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
			else if($_POST['porcenadic'.$i]==NULL
			AND $_SESSION['honor3']['lgruplhadh'][$i]['porcentaje']<>NULL)
			{
				$contador3++;
				$query ="DELETE FROM prof_honorarios_pool_grupos_excep
						WHERE honorario_pool_grupo_id=".$_SESSION['honor3']['lgrupladho']['honorario_pool_grupo_id']."
						AND horario_especial_id=".$_SESSION['honor3']['lgruplhadh'][$i]['horario_especial_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$dbconn->RollBackTrans();
					$i=$ciclo;
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->GruposPolPlaAdicioHonora();
		return true;
	}

	function ValidarPruebasLiquidaHonora()//
	{
		if($_POST['cargoprueh']==NULL)
		{
			$this->frmError["cargoprueh"]=1;
		}
		if($_POST['tipodprueh']==NULL)
		{
			$this->frmError["tipodprueh"]=1;
		}
		if($_POST['identprueh']==NULL)
		{
			$this->frmError["identprueh"]=1;
		}
		if(empty($_REQUEST['planeprueh']))
		{
			$this->frmError["planeprueh"]=1;
		}
		/*if($_POST['planeprueh']==NULL)
		{
			$this->frmError["planeprueh"]=1;
		}
		if($_POST['serviprueh']==NULL)
		{
			$this->frmError["serviprueh"]=1;
		}*/
		if($_POST['cargoprueh']==NULL||$_POST['tipodprueh']==NULL||
		$_POST['identprueh']==NULL || empty($_REQUEST['planeprueh'])/*||$_POST['planeprueh']==NULL||
		$_POST['serviprueh']==NULL*/)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->PruebasLiquidaHonora();
			return true;
		}
		else
		{
			($_SESSION['honorp']);
			list($dbconn) = GetDBconn();
			$query ="SELECT A.cargo,
					A.descripcion,
					A.grupo_tipo_cargo,
					A.tipo_cargo,
					B.descripcion AS des1,
					C.descripcion AS des2
					FROM cups AS A,
					grupos_tipos_cargo AS B,
					tipos_cargos AS C
					WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND A.grupo_tipo_cargo=C.grupo_tipo_cargo
					AND A.tipo_cargo=C.tipo_cargo
					AND A.cargo='".$_POST['cargoprueh']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
				$var=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			if(empty($var))
			{
				$this->frmError["MensajeError"]="EL CARGO CUPS NO ES VÁLIDO O NO SE ENCUENTRA";
				$this->uno=1;
				$this->PruebasLiquidaHonora();
				return true;
			}
			$query ="SELECT A.tipo_id_tercero,
					A.tercero_id,
					A.estado,
					B.nombre_tercero
					FROM profesionales AS A,
					terceros AS B
					WHERE A.tipo_id_tercero='".$_POST['tipodprueh']."'
					AND A.tercero_id='".$_POST['identprueh']."'
					AND A.tipo_id_tercero=B.tipo_id_tercero
					AND A.tercero_id=B.tercero_id;";
			$resulta2 = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta2->EOF)
			{
				$var2=$resulta2->GetRowAssoc($ToUpper = false);
				$resulta2->MoveNext();
			}
			$query ="SELECT A.tipo_id_tercero,
					A.tercero_id,
					A.estado,
					B.nombre_tercero,
					C.prof_pool_id,
					D.descripcion,
					D.estado AS estadopool,
					D.tipo_id_tercero AS tipopool,
					D.tercero_id AS tercpool,
					E.nombre_tercero AS nomtpool
					FROM profesionales AS A,
					terceros AS B,
					prof_pool_miembros AS C,
					prof_pool AS D
					LEFT JOIN terceros AS E ON
					(D.tipo_id_tercero=E.tipo_id_tercero
					AND D.tercero_id=E.tercero_id)
					WHERE C.tipo_id_tercero='".$_POST['tipodprueh']."'
					AND C.tercero_id='".$_POST['identprueh']."'
					AND C.tipo_id_tercero=A.tipo_id_tercero
					AND C.tercero_id=A.tercero_id
					AND A.tipo_id_tercero=B.tipo_id_tercero
					AND A.tercero_id=B.tercero_id
					AND C.prof_pool_id=D.prof_pool_id;";
			$resulta3 = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta3->EOF)
			{
				$var3=$resulta3->GetRowAssoc($ToUpper = false);
				$resulta3->MoveNext();
			}
			if(empty($var2) AND empty($var3))
			{
				$this->frmError["MensajeError"]="NO SE ENCONTRÓ EL PROFESIONAL CON ESA IDENTIFICACIÓN";
				$this->uno=1;
				$this->PruebasLiquidaHonora();
				return true;
			}
			else
			{
				$var['tipo_id_tercero']=$var2['tipo_id_tercero'];
				$var['tercero_id']=$var2['tercero_id'];
				$var['estado']=$var2['estado'];
				$var['nombre_tercero']=$var2['nombre_tercero'];

				$var['tipo_id_tercero_pool']=$var3['tipo_id_tercero'];
				$var['tercero_id_pool']=$var3['tercero_id'];
				$var['estado_pool']=$var3['estado'];
				$var['nombre_tercero_pool']=$var3['nombre_tercero'];
				$var['prof_pool_id']=$var3['prof_pool_id'];
				$var['descripcion_pool']=$var3['descripcion'];
				$var['estadopool_pool']=$var3['estadopool'];
				$var['tipopool_pool']=$var3['tipopool'];
				$var['tercpool_pool']=$var3['tercpool'];
				$var['nomtpool_pool']=$var3['nomtpool'];
				$_SESSION['honorp']['datos']=$var;
			}
			if($var['tipo_id_tercero']<>NULL AND $var['tercero_id']<>NULL AND
			$var['cargo']<>NULL AND $_POST['planeprueh']<>NULL AND
			$_POST['serviprueh']<>NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.cargo='".$var['cargo']."'
						AND A.servicio='".$_POST['serviprueh']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datoscargos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_cargo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_cargos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
							AND A.tercero_id='".$var['tercero_id']."'
							AND A.cargo='".$var['cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
				$query ="SELECT A.porcentaje,
						A.honorario_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."'
						AND A.servicio='".$_POST['serviprueh']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datosgrupos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_grupo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_grupos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
							AND A.tercero_id='".$var['tercero_id']."'
							AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
							AND A.tipo_cargo='".$var['tipo_cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
			}
			else if($var['tipo_id_tercero']<>NULL AND $var['tercero_id']<>NULL AND
			$var['cargo']<>NULL AND $_POST['planeprueh']<>NULL AND
			$_POST['serviprueh']==NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.cargo='".$var['cargo']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datoscargos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_cargo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_cargos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
							AND A.tercero_id='".$var['tercero_id']."'
							AND A.cargo='".$var['cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
				$query ="SELECT A.porcentaje,
						A.honorario_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datosgrupos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_grupo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_grupos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
							AND A.tercero_id='".$var['tercero_id']."'
							AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
							AND A.tipo_cargo='".$var['tipo_cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
			}
			else if($var['tipo_id_tercero']<>NULL AND $var['tercero_id']<>NULL AND
			$var['cargo']<>NULL AND $_POST['planeprueh']==NULL AND
			$_POST['serviprueh']<>NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.cargo='".$var['cargo']."'
						AND A.servicio='".$_POST['serviprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datoscargos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_cargo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_cargos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
							AND A.tercero_id='".$var['tercero_id']."'
							AND A.cargo='".$var['cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
				$query ="SELECT A.porcentaje,
						A.honorario_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."'
						AND A.servicio='".$_POST['serviprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datosgrupos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_grupo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_grupos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
							AND A.tercero_id='".$var['tercero_id']."'
							AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
							AND A.tipo_cargo='".$var['tipo_cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
			}
			else if($var['tipo_id_tercero']<>NULL AND $var['tercero_id']<>NULL AND
			$var['cargo']<>NULL AND $_POST['planeprueh']==NULL AND
			$_POST['serviprueh']==NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.cargo='".$var['cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				$query ="SELECT A.porcentaje,
						A.honorario_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.tipo_id_tercero='".$var['tipo_id_tercero']."'
						AND A.tercero_id='".$var['tercero_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
			if(!empty($datoscargos))
			{
				$_SESSION['honorp']['datoscargo']=$datoscargos;
			}
			else
			{
				UNSET($_SESSION['honorp']['datoscargo']);
			}
			if(!empty($datosgrupos))
			{
				$_SESSION['honorp']['datosgrupo']=$datosgrupos;
			}
			else
			{
				UNSET($_SESSION['honorp']['datosgrupo']);
			}
			$datoscargos='';
			$datosgrupos='';
			if($var['prof_pool_id']<>NULL AND $var['cargo']<>NULL AND
			$_POST['planeprueh']<>NULL AND $_POST['serviprueh']<>NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_pool_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.cargo='".$var['cargo']."'
						AND A.servicio='".$_POST['serviprueh']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datoscargos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_pool_cargo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_pool_cargos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.prof_pool_id='".$var['prof_pool_id']."'
							AND A.cargo='".$var['cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
				$query ="SELECT A.porcentaje,
						A.honorario_pool_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."'
						AND A.servicio='".$_POST['serviprueh']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datosgrupos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_pool_grupo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_pool_grupos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.prof_pool_id='".$var['prof_pool_id']."'
							AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
							AND A.tipo_cargo='".$var['tipo_cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
			}
			else if($var['prof_pool_id']<>NULL AND $var['cargo']<>NULL AND
			$_POST['planeprueh']<>NULL AND $_POST['serviprueh']==NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_pool_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.cargo='".$var['cargo']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datoscargos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_pool_cargo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_pool_cargos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.prof_pool_id='".$var['prof_pool_id']."'
							AND A.cargo='".$var['cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
				$query ="SELECT A.porcentaje,
						A.honorario_pool_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."'
						AND A.plan_id='".$_POST['planeprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datosgrupos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_pool_grupo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_pool_grupos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.prof_pool_id='".$var['prof_pool_id']."'
							AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
							AND A.tipo_cargo='".$var['tipo_cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
			}
			else if($var['prof_pool_id']<>NULL AND $var['cargo']<>NULL AND
			$_POST['planeprueh']==NULL AND $_POST['serviprueh']<>NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_pool_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.cargo='".$var['cargo']."'
						AND A.servicio='".$_POST['serviprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datoscargos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_pool_cargo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_pool_cargos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.prof_pool_id='".$var['prof_pool_id']."'
							AND A.cargo='".$var['cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
				$query ="SELECT A.porcentaje,
						A.honorario_pool_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."'
						AND A.servicio='".$_POST['serviprueh']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				if(empty($datosgrupos))
				{
					$query ="SELECT A.porcentaje,
							A.honorario_pool_grupo_id,
							A.servicio,
							A.plan_id
							FROM prof_honorarios_pool_grupos AS A
							WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
							AND A.prof_pool_id='".$var['prof_pool_id']."'
							AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
							AND A.tipo_cargo='".$var['tipo_cargo']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					while(!$resulta->EOF)
					{
						$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
				}
			}
			else if($var['prof_pool_id']<>NULL AND $var['cargo']<>NULL AND
			$_POST['planeprueh']==NULL AND $_POST['serviprueh']==NULL)
			{
				$query ="SELECT A.porcentaje,
						A.honorario_pool_cargo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_cargos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.cargo='".$var['cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datoscargos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				$query ="SELECT A.porcentaje,
						A.honorario_pool_grupo_id,
						A.servicio,
						A.plan_id
						FROM prof_honorarios_pool_grupos AS A
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$var['prof_pool_id']."'
						AND A.grupo_tipo_cargo='".$var['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$var['tipo_cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$resulta->EOF)
				{
					$datosgrupos[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
			//10010523
			//873444S3
			if(!empty($datoscargos))
			{
				$_SESSION['honorp']['dapolcargo']=$datoscargos;
			}
			else
			{
				UNSET($_SESSION['honorp']['dapolcargo']);
			}
			if(!empty($datosgrupos))
			{
				$_SESSION['honorp']['dapolgrupo']=$datosgrupos;
			}
			else
			{
				UNSET($_SESSION['honorp']['dapolgrupo']);
			}
			$this->frmError["MensajeError"]="INFORMACIÓN HALLADA";
			$this->dos=1;
			IncludeLib("honorarios");
			if($_POST['planeprueh']==NULL)
			{
				$_SESSION['honorp']['cargoshono']=BuscarCargoEquivalenteHonorario($var['cargo']);
			}
			else
			{
				$_SESSION['honorp']['cargoshono']=BuscarCargoEquivalente($var['cargo'],$_POST['planeprueh']);
			}
			$this->PruebasLiquidaHonora();
			return true;
		}
	}

	function CambiarProfesionalHonora()//
	{
		if($_REQUEST['de']==1 AND $_REQUEST['al']==2)
		{
			$_SESSION['honor1']['gruserproh']['tipodoprof']=$_SESSION['honor1']['gruposproh']['tipodoprof'];
			$_SESSION['honor1']['gruserproh']['documeprof']=$_SESSION['honor1']['gruposproh']['documeprof'];
			$_SESSION['honor1']['gruserproh']['nombreprof']=$_SESSION['honor1']['gruposproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruposproh']);
			$this->GruposSerHonora();
			return true;
		}
		else if($_REQUEST['de']==1 AND $_REQUEST['al']==3)
		{
			$_SESSION['honor1']['gruplaproh']['tipodoprof']=$_SESSION['honor1']['gruposproh']['tipodoprof'];
			$_SESSION['honor1']['gruplaproh']['documeprof']=$_SESSION['honor1']['gruposproh']['documeprof'];
			$_SESSION['honor1']['gruplaproh']['nombreprof']=$_SESSION['honor1']['gruposproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruposproh']);
			$this->GruposPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==1 AND $_REQUEST['al']==4)
		{
			$_SESSION['honor2']['cargosproh']['tipodoprof']=$_SESSION['honor1']['gruposproh']['tipodoprof'];
			$_SESSION['honor2']['cargosproh']['documeprof']=$_SESSION['honor1']['gruposproh']['documeprof'];
			$_SESSION['honor2']['cargosproh']['nombreprof']=$_SESSION['honor1']['gruposproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruposproh']);
			$this->CargosHonora();
			return true;
		}
		else if($_REQUEST['de']==1 AND $_REQUEST['al']==5)
		{
			$_SESSION['honor2']['carserproh']['tipodoprof']=$_SESSION['honor1']['gruposproh']['tipodoprof'];
			$_SESSION['honor2']['carserproh']['documeprof']=$_SESSION['honor1']['gruposproh']['documeprof'];
			$_SESSION['honor2']['carserproh']['nombreprof']=$_SESSION['honor1']['gruposproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruposproh']);
			$this->CargosSerHonora();
			return true;
		}
		else if($_REQUEST['de']==1 AND $_REQUEST['al']==6)
		{
			$_SESSION['honor2']['carplaproh']['tipodoprof']=$_SESSION['honor1']['gruposproh']['tipodoprof'];
			$_SESSION['honor2']['carplaproh']['documeprof']=$_SESSION['honor1']['gruposproh']['documeprof'];
			$_SESSION['honor2']['carplaproh']['nombreprof']=$_SESSION['honor1']['gruposproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruposproh']);
			$this->CargosPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==2 AND $_REQUEST['al']==1)
		{
			$_SESSION['honor1']['gruposproh']['tipodoprof']=$_SESSION['honor1']['gruserproh']['tipodoprof'];
			$_SESSION['honor1']['gruposproh']['documeprof']=$_SESSION['honor1']['gruserproh']['documeprof'];
			$_SESSION['honor1']['gruposproh']['nombreprof']=$_SESSION['honor1']['gruserproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruserproh']);
			$this->GruposHonora();
			return true;
		}
		else if($_REQUEST['de']==2 AND $_REQUEST['al']==3)
		{
			$_SESSION['honor1']['gruplaproh']['tipodoprof']=$_SESSION['honor1']['gruserproh']['tipodoprof'];
			$_SESSION['honor1']['gruplaproh']['documeprof']=$_SESSION['honor1']['gruserproh']['documeprof'];
			$_SESSION['honor1']['gruplaproh']['nombreprof']=$_SESSION['honor1']['gruserproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruserproh']);
			$this->GruposPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==2 AND $_REQUEST['al']==4)
		{
			$_SESSION['honor2']['cargosproh']['tipodoprof']=$_SESSION['honor1']['gruserproh']['tipodoprof'];
			$_SESSION['honor2']['cargosproh']['documeprof']=$_SESSION['honor1']['gruserproh']['documeprof'];
			$_SESSION['honor2']['cargosproh']['nombreprof']=$_SESSION['honor1']['gruserproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruserproh']);
			$this->CargosHonora();
			return true;
		}
		else if($_REQUEST['de']==2 AND $_REQUEST['al']==5)
		{
			$_SESSION['honor2']['carserproh']['tipodoprof']=$_SESSION['honor1']['gruserproh']['tipodoprof'];
			$_SESSION['honor2']['carserproh']['documeprof']=$_SESSION['honor1']['gruserproh']['documeprof'];
			$_SESSION['honor2']['carserproh']['nombreprof']=$_SESSION['honor1']['gruserproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruserproh']);
			$this->CargosSerHonora();
			return true;
		}
		else if($_REQUEST['de']==2 AND $_REQUEST['al']==6)
		{
			$_SESSION['honor2']['carplaproh']['tipodoprof']=$_SESSION['honor1']['gruserproh']['tipodoprof'];
			$_SESSION['honor2']['carplaproh']['documeprof']=$_SESSION['honor1']['gruserproh']['documeprof'];
			$_SESSION['honor2']['carplaproh']['nombreprof']=$_SESSION['honor1']['gruserproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruserproh']);
			$this->CargosPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==3 AND $_REQUEST['al']==1)
		{
			$_SESSION['honor1']['gruposproh']['tipodoprof']=$_SESSION['honor1']['gruplaproh']['tipodoprof'];
			$_SESSION['honor1']['gruposproh']['documeprof']=$_SESSION['honor1']['gruplaproh']['documeprof'];
			$_SESSION['honor1']['gruposproh']['nombreprof']=$_SESSION['honor1']['gruplaproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruplaproh']);
			$this->GruposHonora();
			return true;
		}
		else if($_REQUEST['de']==3 AND $_REQUEST['al']==2)
		{
			$_SESSION['honor1']['gruserproh']['tipodoprof']=$_SESSION['honor1']['gruplaproh']['tipodoprof'];
			$_SESSION['honor1']['gruserproh']['documeprof']=$_SESSION['honor1']['gruplaproh']['documeprof'];
			$_SESSION['honor1']['gruserproh']['nombreprof']=$_SESSION['honor1']['gruplaproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruplaproh']);
			$this->GruposSerHonora();
			return true;
		}
		else if($_REQUEST['de']==3 AND $_REQUEST['al']==4)
		{
			$_SESSION['honor2']['cargosproh']['tipodoprof']=$_SESSION['honor1']['gruplaproh']['tipodoprof'];
			$_SESSION['honor2']['cargosproh']['documeprof']=$_SESSION['honor1']['gruplaproh']['documeprof'];
			$_SESSION['honor2']['cargosproh']['nombreprof']=$_SESSION['honor1']['gruplaproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruplaproh']);
			$this->CargosHonora();
			return true;
		}
		else if($_REQUEST['de']==3 AND $_REQUEST['al']==5)
		{
			$_SESSION['honor2']['carserproh']['tipodoprof']=$_SESSION['honor1']['gruplaproh']['tipodoprof'];
			$_SESSION['honor2']['carserproh']['documeprof']=$_SESSION['honor1']['gruplaproh']['documeprof'];
			$_SESSION['honor2']['carserproh']['nombreprof']=$_SESSION['honor1']['gruplaproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruplaproh']);
			$this->CargosSerHonora();
			return true;
		}
		else if($_REQUEST['de']==3 AND $_REQUEST['al']==6)
		{
			$_SESSION['honor2']['carplaproh']['tipodoprof']=$_SESSION['honor1']['gruplaproh']['tipodoprof'];
			$_SESSION['honor2']['carplaproh']['documeprof']=$_SESSION['honor1']['gruplaproh']['documeprof'];
			$_SESSION['honor2']['carplaproh']['nombreprof']=$_SESSION['honor1']['gruplaproh']['nombreprof'];
			UNSET($_SESSION['honor1']['gruplaproh']);
			$this->CargosPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==4 AND $_REQUEST['al']==1)
		{
			$_SESSION['honor1']['gruposproh']['tipodoprof']=$_SESSION['honor2']['cargosproh']['tipodoprof'];
			$_SESSION['honor1']['gruposproh']['documeprof']=$_SESSION['honor2']['cargosproh']['documeprof'];
			$_SESSION['honor1']['gruposproh']['nombreprof']=$_SESSION['honor2']['cargosproh']['nombreprof'];
			UNSET($_SESSION['honor2']['cargosproh']);
			$this->GruposHonora();
			return true;
		}
		else if($_REQUEST['de']==4 AND $_REQUEST['al']==2)
		{
			$_SESSION['honor1']['gruserproh']['tipodoprof']=$_SESSION['honor2']['cargosproh']['tipodoprof'];
			$_SESSION['honor1']['gruserproh']['documeprof']=$_SESSION['honor2']['cargosproh']['documeprof'];
			$_SESSION['honor1']['gruserproh']['nombreprof']=$_SESSION['honor2']['cargosproh']['nombreprof'];
			UNSET($_SESSION['honor2']['cargosproh']);
			$this->GruposSerHonora();
			return true;
		}
		else if($_REQUEST['de']==4 AND $_REQUEST['al']==3)
		{
			$_SESSION['honor1']['gruplaproh']['tipodoprof']=$_SESSION['honor2']['cargosproh']['tipodoprof'];
			$_SESSION['honor1']['gruplaproh']['documeprof']=$_SESSION['honor2']['cargosproh']['documeprof'];
			$_SESSION['honor1']['gruplaproh']['nombreprof']=$_SESSION['honor2']['cargosproh']['nombreprof'];
			UNSET($_SESSION['honor2']['cargosproh']);
			$this->GruposPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==4 AND $_REQUEST['al']==5)
		{
			$_SESSION['honor2']['carserproh']['tipodoprof']=$_SESSION['honor2']['cargosproh']['tipodoprof'];
			$_SESSION['honor2']['carserproh']['documeprof']=$_SESSION['honor2']['cargosproh']['documeprof'];
			$_SESSION['honor2']['carserproh']['nombreprof']=$_SESSION['honor2']['cargosproh']['nombreprof'];
			UNSET($_SESSION['honor2']['cargosproh']);
			$this->CargosSerHonora();
			return true;
		}
		else if($_REQUEST['de']==4 AND $_REQUEST['al']==6)
		{
			$_SESSION['honor2']['carplaproh']['tipodoprof']=$_SESSION['honor2']['cargosproh']['tipodoprof'];
			$_SESSION['honor2']['carplaproh']['documeprof']=$_SESSION['honor2']['cargosproh']['documeprof'];
			$_SESSION['honor2']['carplaproh']['nombreprof']=$_SESSION['honor2']['cargosproh']['nombreprof'];
			UNSET($_SESSION['honor2']['cargosproh']);
			$this->CargosPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==5 AND $_REQUEST['al']==1)
		{
			$_SESSION['honor1']['gruposproh']['tipodoprof']=$_SESSION['honor2']['carserproh']['tipodoprof'];
			$_SESSION['honor1']['gruposproh']['documeprof']=$_SESSION['honor2']['carserproh']['documeprof'];
			$_SESSION['honor1']['gruposproh']['nombreprof']=$_SESSION['honor2']['carserproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carserproh']);
			$this->GruposHonora();
			return true;
		}
		else if($_REQUEST['de']==5 AND $_REQUEST['al']==2)
		{
			$_SESSION['honor1']['gruserproh']['tipodoprof']=$_SESSION['honor2']['carserproh']['tipodoprof'];
			$_SESSION['honor1']['gruserproh']['documeprof']=$_SESSION['honor2']['carserproh']['documeprof'];
			$_SESSION['honor1']['gruserproh']['nombreprof']=$_SESSION['honor2']['carserproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carserproh']);
			$this->GruposSerHonora();
			return true;
		}
		else if($_REQUEST['de']==5 AND $_REQUEST['al']==3)
		{
			$_SESSION['honor1']['gruplaproh']['tipodoprof']=$_SESSION['honor2']['carserproh']['tipodoprof'];
			$_SESSION['honor1']['gruplaproh']['documeprof']=$_SESSION['honor2']['carserproh']['documeprof'];
			$_SESSION['honor1']['gruplaproh']['nombreprof']=$_SESSION['honor2']['carserproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carserproh']);
			$this->GruposPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==5 AND $_REQUEST['al']==4)
		{
			$_SESSION['honor2']['cargosproh']['tipodoprof']=$_SESSION['honor2']['carserproh']['tipodoprof'];
			$_SESSION['honor2']['cargosproh']['documeprof']=$_SESSION['honor2']['carserproh']['documeprof'];
			$_SESSION['honor2']['cargosproh']['nombreprof']=$_SESSION['honor2']['carserproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carserproh']);
			$this->CargosHonora();
			return true;
		}
		else if($_REQUEST['de']==5 AND $_REQUEST['al']==6)
		{
			$_SESSION['honor2']['carplaproh']['tipodoprof']=$_SESSION['honor2']['carserproh']['tipodoprof'];
			$_SESSION['honor2']['carplaproh']['documeprof']=$_SESSION['honor2']['carserproh']['documeprof'];
			$_SESSION['honor2']['carplaproh']['nombreprof']=$_SESSION['honor2']['carserproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carserproh']);
			$this->CargosPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==6 AND $_REQUEST['al']==1)
		{
			$_SESSION['honor1']['gruposproh']['tipodoprof']=$_SESSION['honor2']['carplaproh']['tipodoprof'];
			$_SESSION['honor1']['gruposproh']['documeprof']=$_SESSION['honor2']['carplaproh']['documeprof'];
			$_SESSION['honor1']['gruposproh']['nombreprof']=$_SESSION['honor2']['carplaproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carplaproh']);
			$this->GruposHonora();
			return true;
		}
		else if($_REQUEST['de']==6 AND $_REQUEST['al']==2)
		{
			$_SESSION['honor1']['gruserproh']['tipodoprof']=$_SESSION['honor2']['carplaproh']['tipodoprof'];
			$_SESSION['honor1']['gruserproh']['documeprof']=$_SESSION['honor2']['carplaproh']['documeprof'];
			$_SESSION['honor1']['gruserproh']['nombreprof']=$_SESSION['honor2']['carplaproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carplaproh']);
			$this->GruposSerHonora();
			return true;
		}
		else if($_REQUEST['de']==6 AND $_REQUEST['al']==3)
		{
			$_SESSION['honor1']['gruplaproh']['tipodoprof']=$_SESSION['honor2']['carplaproh']['tipodoprof'];
			$_SESSION['honor1']['gruplaproh']['documeprof']=$_SESSION['honor2']['carplaproh']['documeprof'];
			$_SESSION['honor1']['gruplaproh']['nombreprof']=$_SESSION['honor2']['carplaproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carplaproh']);
			$this->GruposPlaHonora();
			return true;
		}
		else if($_REQUEST['de']==6 AND $_REQUEST['al']==4)
		{
			$_SESSION['honor2']['cargosproh']['tipodoprof']=$_SESSION['honor2']['carplaproh']['tipodoprof'];
			$_SESSION['honor2']['cargosproh']['documeprof']=$_SESSION['honor2']['carplaproh']['documeprof'];
			$_SESSION['honor2']['cargosproh']['nombreprof']=$_SESSION['honor2']['carplaproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carplaproh']);
			$this->CargosHonora();
			return true;
		}
		else if($_REQUEST['de']==6 AND $_REQUEST['al']==5)
		{
			$_SESSION['honor2']['carserproh']['tipodoprof']=$_SESSION['honor2']['carplaproh']['tipodoprof'];
			$_SESSION['honor2']['carserproh']['documeprof']=$_SESSION['honor2']['carplaproh']['documeprof'];
			$_SESSION['honor2']['carserproh']['nombreprof']=$_SESSION['honor2']['carplaproh']['nombreprof'];
			UNSET($_SESSION['honor2']['carplaproh']);
			$this->CargosSerHonora();
			return true;
		}
	}

}//fin de la clase
?>
