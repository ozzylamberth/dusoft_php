<?php

/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class system_Menu_user extends classModulo
{

	function system_Menu_user()
	{
		return true;
	}

/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

	function main(){

  	if(!$this->Menus()){
        return false;
    }
		return true;
  }




 /**
* Funcion que trae el usuario,nombre,password de la tabla system_usuarios
* @return array
*/
function BuscarMenuUsuario()
{

		list($dbconn) = GetDBconn();
		$query="select a.menu_id,a.menu_nombre,a.descripcion,b.usuario_id,
					c.nombre,c.descripcion as desc,c.usuario
					from  system_menus a,system_usuarios_menus b,system_usuarios c
					where b.usuario_id=".UserGetUID()."
					and a.menu_id=b.menu_id and c.usuario_id=b.usuario_id
					ORDER BY a.menu_nombre";
		//echo "<br><br>Q->".$query;
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los menus de usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
			return $var;
}

/**
* Funcion que trae el usuario,nombre,password de la tabla system_usuarios
* @return array
*/
function MenuPerfiles()
{

		list($dbconn) = GetDBconn();
		$query="
      select 
      d.menu_id,
      d.menu_nombre,
      d.descripcion,
      b.usuario_id,
      e.nombre,
      e.descripcion as desc,
      e.usuario
      from  
      system_usuarios_perfiles as b
      JOIN system_perfiles_menus as c ON (b.usuario_id = ".UserGetUID().")
       and (b.perfil_id = c.perfil_id)
      JOIN system_menus as d ON (c.menu_id = d.menu_id) 
      JOIN system_usuarios as e ON (b.usuario_id = e.usuario_id)
      group by d.menu_id,d.menu_nombre,d.descripcion,b.usuario_id,
      e.nombre,
      e.descripcion,
      e.usuario
      order by d.menu_nombre
";
		//echo "<br><br>Q->".$query;
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los menus de usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
			return $var;
}

function BuscarSubMenuUsuario($menu)
{

		list($dbconn) = GetDBconn();
		$query="select titulo from system_menus_items where menu_id=".$menu."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los submenus de usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
		return $var;
}


function AsignarUrl($dato,$title)
		{
     if(empty($dato))
		 {
				$var1[]=array('','');
				return $var1;
		 }

		GLOBAL $ADODB_FETCH_MODE;
		 list($dbconn) = GetDBconn();
		 $sqls="select menu_id ,titulo,modulo_tipo,modulo,tipo,metodo,
							descripcion,indice_de_orden from system_menus_items where menu_id=$dato AND
							titulo='$title'
							order by indice_de_orden asc";
			//echo "<br><br>OQ->".$sqls;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resulta = $dbconn->Execute($sqls);

			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

    while($data=$resulta->FetchRow()){
			$var1=ModuloGetURL($data['modulo_tipo'],$data['modulo'],$data['tipo'],$data['metodo']);
		}
     return $var1;
		}
   function ConsultarMensajes(){
        $usuario_id=UserGetUID();
        $sql.="select count(fecha_fin) as todas,count (cl.sw) as leidas
                from system_usuarios_perfiles as s
                inner join controlar_x_perfil as c on (c.perfil_id = s.perfil_id  or c.perfil_id=-1)
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id
                inner join system_usuarios as su on (s.usuario_id=su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id'
                where a.fecha_fin >=now() and s.usuario_id='$usuario_id'";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

     function control_lectura_Mensajes($usuario_id){
        $sql="select a.actualizacion_id,a.asunto,a.descripcion,a.fecha_fin,cl.sw,su.nombre,cl.fecha_lectura,c.obligatorio
                from system_usuarios_perfiles as s
                inner join controlar_x_perfil as c on c.perfil_id = s.perfil_id or c.perfil_id =-1 and c.obligatorio=1
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id
                inner join system_usuarios as su on (s.usuario_id = su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id'
                where a.fecha_fin >=now() and s.usuario_id='$usuario_id' order by cl.sw desc";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }
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
}//fin clase user
?>