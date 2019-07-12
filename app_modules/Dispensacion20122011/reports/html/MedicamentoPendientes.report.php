<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MedicamentoPendiente_ESM.report.php,v 1.5 2010/07/08
  * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */
  /**
  * Clase Reporte: MedicamentoPendientes_report
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.0
  * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */

   class MedicamentoPendientes_repor

      var $datos;

      var $title       = '';
      var $author      = '';
      var $sizepage    = 'leter';
      var $Orientation = '';
      var $grayScale   = false;
      var $headers     = array();
      var $footers     = array();

      /*Constructor de la clase- Metodo Privado No Modificar*/
      function MedicamentoPendientes_report($datos=array())
      {
         $this->datos=$datos;

         return true;
      }

      function GetMembrete()
      {

         $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:5px\"";
         //$titulo .= " <b $estilo>MEDICAMENTOS ENTREGADOS Y/O PENDIENTES</b>";

         $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
                       'logo'=>'',
                       'align'=>'left'));
         return $Membrete;
      }

      function CrearReporte()
      {
         IncludeClass('ConexionBD');
         IncludeClass('DispensacionSQL','','app','Dispensacion');
         $style  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";
         $ods = new DispensacionSQL();

         $Cabecera_Formulacion=$ods->ObtenerFormulasCabecera_por_evolucion($this->datos['evolucion']);



          //$Dx=$ods->Diagnostico_Real($this->datos['formula_id']);
         /*$medicamentos=$ods->Medicamentos_Dispensados_Esm_x_lote($this->datos['formula_id']);
         */
         $pendientes=$ods->Medicamentos_Pendientes_($this->datos['evolucion']);

         $profesional=$ods->Profesional_formula($this->datos['evolucion']);

         $Usuarios_=$ods->GetNombreUsuarioImprime();

         $html .    "     <BR>  <fieldset class=\"fieldset\">\n";
          $html .=              <legend class=\"normal_10AN\">ENTREGA DE MEDICAMENTOS</legend>\n";
         $html .               <table width=\"100%\" cellspacing=\"2\">\n";
         $html .                  <tr>\n";
         $html .                     <td align=\"center\">\n";
         $html .                        <table width=\"100%\" class=\"label\" $style>\n";

         $html .                           <tr >\n";

         $html .                              <td  align=\"left\" ><U>FECHA DE REGISTRO:</U></td>\n";
         $html .                              <td  align=\"left\">".$Cabecera_Formulacion['fecha_registro']."\n";
         $html .                              </td>\n";

         $html .                              <td  align=\"left\"><U>FECHA DE FORMULA:</U></td>\n";
         $html .                              <td align=\"left\">".$Cabecera_Formulacion['fecha_formulacion']."\n";
         $html .                              </td>\n";


         $html .                              <td align=\"left\" ><U>EVOLUCION No:<U></td>\n";
         $html .                              <td  align=\"left\">".$this->datos['evolucion']."\n";
         $html .                              </td>\n";




         $html .                           </tr>\n";


         $html .=                          <tr>\n";
         $html .                              <td  align=\"left\"><U>IDENTIFICACION:</U></td>\n";
         $html .                              <td  align=\"left\" >\n";
         $html .                                 ".$Cabecera_Formulacion['tipo_id_paciente']."  ".$Cabecera_Formulacion['paciente_id']."\n";
         $html .                              </td>\n";
         $html .                              <td  colspan=\"4\" align=\"left\"   ><U>NOMBRE COMPLETO:</U>\n";
         $html .                               ".$Cabecera_Formulacion['nombres']."".$Cabecera_Formulacion['apellidos']."\n";
         $html .                              </td>\n";
         $html .                           </tr>\n";

         if($Cabecera_Formulacion['sexo_id']=='M')
         {
          $sexo='MASCULINO';

         }else
         {
         $sexo='FEMENINO';

         }
         list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);

         if($anio!=0)
         {

          $edad_t='AÑOS';
          $edad=$anio;
         }
         if($anio==0 and $mes!=0)
         {
           $edad_t='MES';
            $edad=$mes;
         }
         else
         {
              if($anio==0 and $mes==0)
            {
            $edad_t='DIAS';
                $edad=$dias;
            }

          }

         $html .                           <tr>\n";
         $html .                              <td  align=\"left\"><U>EDAD:</U></td>\n";
         $html .                              <td  align=\"left\" >".$edad." &nbsp; $edad_t \n";
         $html .                              </td>\n";
         $html .                              <td  colspan=\"2\" align=\"left\"><U>SEXO:</U>\n";
         $html .                              ".$sexo."\n";
         $html .                              </td>\n";
         $html .                            </tr>\n";

         $html .                           <tr>\n";
         $html .                              <td  align=\"left\"><U>TELEFONO:</U></td>\n";
         $html .                              <td  align=\"left\" > ".$Cabecera_Formulacion['residencia_telefono']." \n";
         $html .                              </td>\n";
         $html .                              <td  colspan=\"2\" align=\"left\"><U>DIRECCION:</U>\n";
         $html .                               ".$Cabecera_Formulacion['residencia_direccion']."\n";
         $html .                              </td>\n";
         $html .                            </tr>\n";

         $html .                           <tr>\n";
         $html .                              <td  align=\"left\"><U>PROFESIONAL:</U></td>\n";
         $html .                              <td  align=\"left\"  colspan=\"3\" >".$profesional['tipo_id_tercero']." ".$profesional['tercero_id']." &nbsp; ".$profesional['nombre']." - ".$profesional['descripcion']." \n";

         $html .                            </tr>\n";



         $html .                        </table>\n";

         $html .                  <tr>\n";
         $html .                     <td align=\"center\">\n";

         $html .                     </td>\n";
         $html .                  </tr>\n";

         $html .               </table>\n";
         $html .            </fieldset>\n";


         $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
         $html .   "  <tr>\n";
         $html .    "    <td>\n";
         $html .            <fieldset class=\"fieldset\">\n";
         $html .               <legend class=\"normal_10AN\">MEDICAMENTOS PENDIENTES</legend>\n";
         $html .               <table width=\"100%\" cellspacing=\"2\">\n";
         $html .                  <tr>\n";
         $html .                     <td align=\"center\">\n";
         $html .                        <table width=\"100%\" class=\"label\" $style>\n";
         $html .                           <tr >\n";

         $html .                              <td  ><U>CODIGO</U></td>\n";
         $html .                              <td colspan=\"6\"  ><U>MEDICAMENTO</U></td>\n";
         $html .                              <td ><U>CANTIDAD</U></td>\n";
         $html .                              </td>\n";
         $html .                           </tr>\n";


         foreach($pendientes as $item=>$fila)
         {
            $html                             <tr >\n";
            $html                                <td   >".$fila['codigo_medicamento']."</td>\n";
            $html                                <td colspan=\"6\">".$fila['descripcion_prod']."</td>\n";
            $html                                <td  align=\"center\" >".round($fila['total'])."</td>\n";
            $html                                </td>\n";
            $html                             </tr>\n";
         }


         $html .=                       </table>\n";

         $html .                     <td>\n";
         $html .                  </tr>\n";
         $html .                  <tr>\n";
         $html .                     <td align=\"center\">\n";

         $html .                     </td>\n";
         $html .                  </tr>\n";

         $html .               </table>\n";
         $html .            </fieldset>\n";
         $html .    "    </td>\n";
         $html .   "  </tr>\n";
         $html .= "</table>\n";


              $html .= "            <table width=\"100%\" class=\"label\" $style>\n";
         $html .= "             <tr class=\"label\"  valign=\"bottom\" >\n";
         $html .= "                <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
         $html .= "              </tr        >\ ";
         $html .= "               <tr class=\"label\" >\n";
         $html .= "                <td align=\"LEFT\">FIRMA PACIENTE</td>\n";
         $html .= "               </tr>\n";
         $html .= "       <tr align='right'>\n";
         $html .= "         <td align=\"right\" $style>";
         $html .= "           USUARIO  IMPRIME:";
         $html .= "       ".$Usuarios_['0']['nombre']."&nbsp;";
         $html .= "      - ".$Usuarios_['0']['descripcion']."&nbsp;";
         $html .= "      </td>\n";


         $html .= "         <td width='50%' align=\"right\" $style>";
         $html .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
         $html .= "     </td>\n";
         $html .= "     </tr>\n";
         $html .= "    </table>\n";





         return $html;
      }

   }
?>