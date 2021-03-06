<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function generateToken()
    {
        $token = Str::random(50);
        $activo = True;
        $archivoCSV = fopen('C:\xampp\htdocs\prueba\public\tokens.csv', "a"); 
        fwrite($archivoCSV, "{$token},{$activo}" . PHP_EOL);
        fclose($archivoCSV);
        
        return [
            'Token' => $token,
            'Estado' => $activo
        ];
    }

    public function usarToken(Request $request){
        $tokenAValidar = $request->input('token');
        $file = fopen('C:\xampp\htdocs\prueba\public\tokens.csv', "r");
        $tokenValido = False;
        //Lee línea a línea y escribela hasta el fin de fichero
        $lineas = array();
        while($linea = fgets($file)) {
            if (feof($file)) break;
            $tokenInfo = explode(',', $linea);
            if(($tokenAValidar == $tokenInfo[0]) and ($tokenInfo[1] == '1'))
            {
                $tokenValido = True;
                $tokenInfo[1] = '0';
                array_push($lineas, $tokenInfo[0] . ',' . $tokenInfo[1]);
            }
            else
            {
                array_push($lineas, $linea);
            }
        }
        fclose($file);
        $archivoCSV = fopen('C:\xampp\htdocs\prueba\public\tokens.csv', "w");
        foreach($lineas as $l)
        {
            fwrite($archivoCSV, $l);
        }
        fclose($archivoCSV);
        return ['TokenValido' => $tokenValido];
    }
}
