<?
global $_ROOT;
$_ROOT = '../../../';
$VISTA='HTML';

include "../../../includes/enviroment.inc.php";
//IncludeClass("rs_server");
include  "../../../classes/rs_server/rs_server.class.php";



    class procesos_admin extends rs_server {
    /*
    * Definimos tantos métodos como funciones queremos que nuestro servidor "sirva"
    */
        function get_valores ( $parameters )  
				{
        		//unset($_SESSION['AUDITORIA']['VECTOR']);
            // los parametros siempre son un array ... en este caso de solo 1 elemento
            //error_log("Sesion".ModuloGetUrl("app","AuditoriaMedica","metodo").print_r($_SESSION,true),3,"/tmp/sesion.log");
            $tipo_auditoria=$parameters[0];
            $a = explode('||',$tipo_auditoria);
            if(!isset($_SESSION['AUDITORIA']['VECTOR'][$a[0]]))
            {
                $_SESSION['AUDITORIA']['VECTOR'][$a[0]]=array('id'=>$a[0],'descripcion'=>$a[1]);
								$html='';
								$html .= "<table width=100%  cellspacing=\"3\" cellpadding=\"3\">";
								foreach($_SESSION['AUDITORIA']['VECTOR'] as $k => $v)
								{
											$html .= "<tr class=modulo_list_claro>";
											$html .= "<td width=90%><li class=modulo_list_claro>".$v['descripcion']."</li></td>";
											$html .= "<td width=10% align=\"CENTER\" class=\"label\"><a href=\"javascript:Eliminar('".$v['id']."')\">ELIMINAR</a></td>";
											$html .= "</tr>";
								}
								$html .= "</table>";	
                return  $html;
            }							
            return '';
        }
				
				function EliminarVector($valor)
				{
						$tipo_auditoria=(int)$valor[0];		
						unset($_SESSION['AUDITORIA']['VECTOR'][$tipo_auditoria]);
						
            if(!empty($_SESSION['AUDITORIA']['VECTOR']))
            {
								$html='';
								$html .= "<table width=100%  cellspacing=\"3\" cellpadding=\"3\">";
								foreach($_SESSION['AUDITORIA']['VECTOR'] as $k => $v)
								{
											$html .= "<tr class=modulo_list_claro>";
											$html .= "<td width=90%><li class=modulo_list_claro>";
											$html .= $v['descripcion'];
											$html .= "</li></td>";
											$html .= "<td width=10% class=\"label\" align=\"center\"><a href=\"javascript:Eliminar('".$v['id']."')\">ELIMINAR</a></td>";
											$html .= "</tr>";
								}
								$html .= "</table>";	
                return  $html;
            }							
            return '';				
				}

    }//end of class


    /*
        cuando creamos el objeto que tiene los procesos debemos indicar como único parámetro un
        array con todas las funciones posibles ... esto se hace para evitar que se pueda llamar
        a cualquier método del objeto.

    */

    $oRS = new procesos_admin( array( 'get_valores' ,'EliminarVector'));

    // el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
    $oRS->action();

?>
