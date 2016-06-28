<?php

namespace ThadeuEsteves;

class Csv
{

    /**
    * Lê um arquivo CSV
    * @param $path
    * @return Array
    */
    public static function read($file)
    {
        $values = array();
        $out = array();
    
        if (($handle = fopen($file, "r")) !== false) {
            while (($cols = fgetcsv($handle, 4096, ";")) !== false) {
                $values[] = $cols;
            }
        }
    
        foreach ($values as $k => $v) {
            $out[$k] = $v;
        }
    
        $header = self::header($out);
        $out = self::removeHeader($out);
        $combine = self::combine($header, $out);
              
        return $combine;
    }
  
    /**
    * Retorna somente o cabeçalho do CSV
    * @return Array
    */
    public static function header(array $array)
    {
        $header = array_shift($array);
        return $header;
    }
  
    /**
    * Remove o cabeçalho e retorna somente as linhas
    * @return Array
    */
    private static function removeHeader(array $array)
    {
        $out = array_slice($array, 1, -1);
        return $out;
    }
  
    /**
    * Combina um header + rows
    * @return Array
    */
    private static function combine(array $keys, array $values)
    {
        $out = [];
        foreach ($values as $k => $v) {
            $out[] = array_combine(self::slugify($keys), $values[$k]);
        }
        return $out;
    }
  
    public static function slugify(array $text)
    {
        foreach ($text as $k => $v) {
            $slug[$k] = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', utf8_encode($v))));
        }
    
        return $slug;
    }
}
