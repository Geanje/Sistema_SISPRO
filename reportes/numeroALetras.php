<?php 
class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];
    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.',$number);
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda);
            // $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }
        return $valor_convertido;
    }
    private static function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }

    function num2letras($num, $fem = false, $dec = true) {

        //if (strlen($num) > 14) die("El n?mero introducido es demasiado grande");
  
           $matuni[2]  = "dos";
  
           $matuni[3]  = "tres";
  
           $matuni[4]  = "cuatro";
  
           $matuni[5]  = "cinco";
  
           $matuni[6]  = "seis";
  
           $matuni[7]  = "siete";
  
           $matuni[8]  = "ocho";
  
           $matuni[9]  = "nueve";
  
           $matuni[10] = "diez";
  
           $matuni[11] = "once";
  
           $matuni[12] = "doce";
  
           $matuni[13] = "trece";
  
           $matuni[14] = "catorce";
  
           $matuni[15] = "quince";
  
           $matuni[16] = "dieciseis";
  
           $matuni[17] = "diecisiete";
  
           $matuni[18] = "dieciocho";
  
           $matuni[19] = "diecinueve";
  
           $matuni[20] = "veinte";
  
           $matunisub[2] = "dos";
  
           $matunisub[3] = "tres";
  
           $matunisub[4] = "cuatro";
  
           $matunisub[5] = "quin";
  
           $matunisub[6] = "seis";
  
           $matunisub[7] = "sete";
  
           $matunisub[8] = "ocho";
  
           $matunisub[9] = "nove";
  
       
  
           $matdec[2] = "veint";
  
           $matdec[3] = "treinta";
  
           $matdec[4] = "cuarenta";
  
           $matdec[5] = "cincuenta";
  
           $matdec[6] = "sesenta";
  
           $matdec[7] = "setenta";
  
           $matdec[8] = "ochenta";
  
           $matdec[9] = "noventa";
  
           $matsub[3]  = 'mill';
  
           $matsub[5]  = 'bill';
  
           $matsub[7]  = 'mill';
  
           $matsub[9]  = 'trill';
  
           $matsub[11] = 'mill';
  
           $matsub[13] = 'bill';
  
           $matsub[15] = 'mill';
  
           $matmil[4]  = 'millones';
  
           $matmil[6]  = 'billones';
  
           $matmil[7]  = 'de billones';
  
           $matmil[8]  = 'millones de billones';
  
           $matmil[10] = 'trillones';
  
           $matmil[11] = 'de trillones';
  
           $matmil[12] = 'millones de trillones';
  
           $matmil[13] = 'de trillones';
  
           $matmil[14] = 'billones de trillones';
  
           $matmil[15] = 'de billones de trillones';
  
           $matmil[16] = 'millones de billones de trillones';
  
       
  
           $num = trim((string)@$num);
  
           if ($num[0] == '-') {
  
              $neg = 'menos ';
  
              $num = substr($num, 1);
  
           }else
  
              $neg = '';
  
           while ($num[0] == '0') $num = substr($num, 1);
  
           if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
  
           $zeros = true;
  
           $punt = false;
  
           $ent = '';
  
           $fra = '';
  
           for ($c = 0; $c < strlen($num); $c++) {
  
              $n = $num[$c];
  
              if (! (strpos(".,'''", $n) === false)) {
  
                 if ($punt) break;
  
                 else{
  
                    $punt = true;
  
                    continue;
  
                 }
  
       
  
              }elseif (! (strpos('0123456789', $n) === false)) {
  
                 if ($punt) {
  
                    if ($n != '0') $zeros = false;
  
                    $fra .= $n;
  
                 }else
  
       
  
                    $ent .= $n;
  
              }else
  
       
  
                 break;
  
       
  
           }
  
           $ent = '     ' . $ent;
  
           if ($dec and $fra and ! $zeros) {
  
              $fin = ' con';
  
              for ($n = 0; $n < strlen($fra); $n++) {
  
                 if (($s = $fra[$n]) == '0')
  
                    $fin .= ' cero';
  
                 elseif ($s == '1')
  
                    $fin .= $fem ? ' una' : ' un';
  
                 else
  
                    $fin .= ' ' . $matuni[$s];
  
              }
  
           }else
  
              $fin = '';
  
           if ((int)$ent === 0) return 'Cero ' . $fin;
  
           $tex = '';
  
           $sub = 0;
  
           $mils = 0;
  
           $neutro = false;
  
           while ( ($num = substr($ent, -3)) != '   ') {
  
              $ent = substr($ent, 0, -3);
  
              if (++$sub < 3 and $fem) {
  
                 $matuni[1] = 'una';
  
                 $subcent = 'as';
  
              }else{
  
                 $matuni[1] = $neutro ? 'un' : 'uno';
  
                 $subcent = 'os';
  
              }
  
              $t = '';
  
              $n2 = substr($num, 1);
  
              if ($n2 == '00') {
  
              }elseif ($n2 < 21)
  
                 $t = ' ' . $matuni[(int)$n2];
  
              elseif ($n2 < 30) {
  
                 $n3 = $num[2];
  
                 if ($n3 != 0) $t = 'i' . $matuni[$n3];
  
                 $n2 = $num[1];
  
                 $t = ' ' . $matdec[$n2] . $t;
  
              }else{
  
                 $n3 = $num[2];
  
                 if ($n3 != 0) $t = ' y ' . $matuni[$n3];
  
                 $n2 = $num[1];
  
                 $t = ' ' . $matdec[$n2] . $t;
  
              }
  
              $n = $num[0];
  
              // if ($n == 1) {
  
              //    $t = ' ciento' . $t;
  
              // }elseif ($n == 5){
  
              //    $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
  
              // }elseif ($n != 0){
              //    // echo "esto es el matu: ".$matsub[$n];
              //    // echo "esto es sub: " .$subcent;
              //    // echo "esto es t: " .$t;
  
  
              //    $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
  
              // }
  
              if ($sub == 1) {
  
              }elseif (! isset($matsub[$sub])) {
  
                 if ($num == 1) {
  
                    $t = ' mil';
  
                 }elseif ($num > 1){
  
                    $t .= ' mil';
  
                 }
  
              }elseif ($num == 1) {
  
                 $t .= ' ' . $matsub[$sub] . '&oacute;n';
  
              }elseif ($num > 1){
  
                 $t .= ' ' . $matsub[$sub] . 'ones';
  
              }  
  
              if ($num == '000') $mils ++;
  
              elseif ($mils != 0) {
  
                 if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
  
                 $mils = 0;
  
              }
  
              $neutro = true;
  
              $tex = $t . $tex;
  
           }
  
           $tex = $neg . substr($tex, 1) . $fin;
  
           return ucfirst($tex);
  
        }
  
}

 ?>