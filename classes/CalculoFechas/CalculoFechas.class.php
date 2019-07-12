<?php

class CalculoFechas {

    function CalculoFechas() {
        
    }

    var $hoy;
    var $festivos;
    var $lista_festivos = array();
    var $ano;
    var $pascua_mes;
    var $pascua_dia;

    function getFestivos($ano = '') {


        $this->festivos($ano);
        $this->festivos;
        foreach ($this->festivos as $anio => $lista_meses) {
            foreach ($lista_meses as $mes => $dias_festivos) {
                foreach ($dias_festivos as $dia => $value) {

                    array_push($this->lista_festivos, date("Y-m-d", strtotime($this->ano . "-" . $mes . "-" . $dia)));
                }
            }
        }

        sort($this->lista_festivos);

        return $this->lista_festivos;
    }

    function festivos($ano = '') {
        $this->hoy = date('d/m/Y');

        if ($ano == '')
            $ano = date('Y');

        $this->ano = $ano;

        $this->pascua_mes = date("m", easter_date($this->ano));
        $this->pascua_dia = date("d", easter_date($this->ano));

        $this->festivos[$ano][1][1] = true;           // Primero de Enero
        $this->festivos[$ano][5][1] = true;           // Dia del Trabajo 1 de Mayo
        $this->festivos[$ano][7][20] = true;           // Independencia 20 de Julio
        $this->festivos[$ano][8][7] = true;           // Batalla de BoyacÃ?Â¡ 7 de Agosto
        $this->festivos[$ano][12][8] = true;           // Maria Inmaculada 8 diciembre (religiosa)
        $this->festivos[$ano][12][25] = true;           // Navidad 25 de diciembre

        $this->calcula_emiliani(1, 6);                          // Reyes Magos Enero 6
        $this->calcula_emiliani(3, 19);                         // San Jose Marzo 19
        $this->calcula_emiliani(6, 29);                         // San Pedro y San Pablo Junio 29
        $this->calcula_emiliani(8, 15);                         // AsunciÃ?Â³n Agosto 15
        $this->calcula_emiliani(10, 12);                        // Descubrimiento de AmÃ?Â©rica Oct 12
        $this->calcula_emiliani(11, 1);                         // Todos los santos Nov 1
        $this->calcula_emiliani(11, 11);                        // Independencia de Cartagena Nov 11
        //otras fechas calculadas a partir de la pascua.

        $this->otrasFechasCalculadas(-3);                       //jueves santo
        $this->otrasFechasCalculadas(-2);                       //viernes santo

        $this->otrasFechasCalculadas(43, true);          //AscenciÃ?Â³n el SeÃ?Â±or pascua
        $this->otrasFechasCalculadas(64, true);          //Corpus Cristi
        $this->otrasFechasCalculadas(71, true);          //Sagrado CorazÃ?Â³n
        // otras fechas importantes que no son festivos
        // $this->otrasFechasCalculadas(-46);           // MiÃ?Â©rcoles de Ceniza
        // $this->otrasFechasCalculadas(-46);           // MiÃ?Â©rcoles de Ceniza
        // $this->otrasFechasCalculadas(-48);           // Lunes de Carnaval Barranquilla
        // $this->otrasFechasCalculadas(-47);           // Martes de Carnaval Barranquilla
    }

    function calcula_emiliani($mes_festivo, $dia_festivo) {
        // funcion que mueve una fecha diferente a lunes al siguiente lunes en el
        // calendario y se aplica a fechas que estan bajo la ley emiliani
        //global  $y,$dia_festivo,$mes_festivo,$festivo;
        // Extrae el dia de la semana
        // 0 Domingo Ã?ï¿½ 6 SÃ?Â¡bado
        $dd = date("w", mktime(0, 0, 0, $mes_festivo, $dia_festivo, $this->ano));
        switch ($dd) {
            case 0:                                    // Domingo
                $dia_festivo = $dia_festivo + 1;
                break;
            case 2:                                    // Martes.
                $dia_festivo = $dia_festivo + 6;
                break;
            case 3:                                    // MiÃ?Â©rcoles
                $dia_festivo = $dia_festivo + 5;
                break;
            case 4:                                     // Jueves
                $dia_festivo = $dia_festivo + 4;
                break;
            case 5:                                     // Viernes
                $dia_festivo = $dia_festivo + 3;
                break;
            case 6:                                     // SÃ?Â¡bado
                $dia_festivo = $dia_festivo + 2;
                break;
        }
        $mes = date("n", mktime(0, 0, 0, $mes_festivo, $dia_festivo, $this->ano)) + 0;
        $dia = date("d", mktime(0, 0, 0, $mes_festivo, $dia_festivo, $this->ano)) + 0;
        $this->festivos[$this->ano][$mes][$dia] = true;
    }

    function otrasFechasCalculadas($cantidadDias = 0, $siguienteLunes = false) {
        $mes_festivo = date("n", mktime(0, 0, 0, $this->pascua_mes, $this->pascua_dia + $cantidadDias, $this->ano));
        $dia_festivo = date("d", mktime(0, 0, 0, $this->pascua_mes, $this->pascua_dia + $cantidadDias, $this->ano));

        if ($siguienteLunes) {
            $this->calcula_emiliani($mes_festivo, $dia_festivo);
        } else {
            $this->festivos[$this->ano][$mes_festivo + 0][$dia_festivo + 0] = true;
        }
    }

    function esFestivo($dia, $mes) {
        //echo (int)$mes;
        if ($dia == '' or $mes == '') {
            return false;
        }

        if (isset($this->festivos[$this->ano][(int) $mes][(int) $dia])) {
            return true;
        } else {
            return FALSE;
        }
    }

    function obtener_cantidad_meses($date1, $date2) {

        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        return $diff;
    }

    function obtener_dias_habiles($startDate, $endDate) {
        // do strtotime calculations just once

        $the_first_day_of_week = date("w", strtotime($startDate));
        $the_last_day_of_week = date("w", strtotime($endDate));

        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);
        $holidays = $this->getFestivos();

        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        //$the_first_day_of_week = date("N", $startDate);
        //$the_last_day_of_week = date("N", $endDate);


        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week)
                $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week)
                $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)
            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            } else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
        //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0) {
            $workingDays += $no_remaining_days;
        }

        //We subtract the holidays
        foreach ($holidays as $holiday) {
            $time_stamp = strtotime($holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N", $time_stamp) != 6 && date("N", $time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }

}

?>