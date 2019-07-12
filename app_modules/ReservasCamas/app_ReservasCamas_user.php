<?php
/**
* Modulo de app_ReservasCamas_user (PHP).
*
* Modulo para el manejo de reservas de camas.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
*/

/**
* app_ReservasCamas_user.php
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del modulo app_ReservasCamas_user
* utilizados los metodos de esta clase en la anterior.
*/


class app_ReservasCamas_user extends classModulo
{

  /**
  * Es el contructor de la clase
  * @return boolean
  */
  function app_ReservasCamas_user()
  {
      $this->limit=GetLimitBrowser();
      $this->prefijo=$_REQUEST['prefijo'];
      $this->numero=$_REQUEST['numero'];
      $this->cajaid=$_REQUEST['cajaid'];
      return true;
  }



  /**
  * La funcion main es la principal y donde se llama FormaPrincipal
  * que muestra los diferentes tipos de busqueda de una cuenta para hospitalización.
  * @access public
  * @return boolean
  */
  function main()
  {
        if(!$this->BuscarPermisosUser()){
          return false;
        }
        return true;
  }


	/**
  * Nota: las variables pueden llegar por REQUEST o por Parametros.
  * @access private
  * @return boolean
  */
  function BuscarPermisosUser()
  {
      list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
      $query = "SELECT a.caja_id, b.sw_todos_cu, b.empresa_id, b.centro_utilidad,b.ip_address,
                b.descripcion as descripcion3, c.tipo_numeracion, d.razon_social as descripcion1,
                e.descripcion as descripcion2, b.cuenta_tipo_id, a.caja_id
                from cajas_usuarios as a, cajas as b, numeraciones as c, empresas as d, centros_utilidad as e
                where a.usuario_id=".UserGetUID()." and a.caja_id=b.caja_id
                and b.empresa_id=d.empresa_id and d.empresa_id=e.empresa_id and b.centro_utilidad=e.centro_utilidad
                and b.tipo_numeracion=c.tipo_numeracion order by d.empresa_id, b.centro_utilidad, a.caja_id";
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $resulta = $dbconn->Execute($query);

      while($data = $resulta->FetchRow())
      {
        $caja[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
      }

      $url[0]='app';
      $url[1]='ReservasCamas';
      $url[2]='user';
      $url[3]='MenudeCaja';
      $url[4]='Caja';
      $arreglo[0]='EMPRESA';
      $arreglo[1]='CENTRO UTILIDAD';
      $arreglo[2]='CAJA';

      $this->salida.= gui_theme_menu_acceso('CAJAS',$arreglo,$caja,$url,ModuloGetURL('system','Menu'));
      return true;
  }


}//fin clase user

?>

