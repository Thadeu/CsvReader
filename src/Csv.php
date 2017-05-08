<?php

namespace ThadeuEsteves;

class Csv
{

    /**
     * Lê um arquivo CSV.
     * @param $file
     * @return Array | boolean
     */
    public static function read($file)
    {
        if (self::validate($file)) {
            $values = [];
            $out = [];

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

        return false;
    }

    /**
     * Retorna somente o cabeçalho do CSV.
     * @param $array
     * @return Array
     */
    public static function header(array $array)
    {
        $header = array_shift($array);
        return $header;
    }

    /**
     * Verifica se arquivo é um CSV válido.
     * @param $file
     * @return boolean
     */
    private static function validate($file)
    {
        if (!file_exists($file) || pathinfo($file)["extension"] != "csv") {
            return false;
        }
        return true;
    }

    /**
     * Remove o cabeçalho e retorna somente as linhas.
     * @param $array
     * @return Array
     */
    private static function removeHeader(array $array)
    {
        $out = array_slice($array, 1, -1);
        return $out;
    }

    /**
     * Combina um header + rows.
     * @param $keys
     * @param $values
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
