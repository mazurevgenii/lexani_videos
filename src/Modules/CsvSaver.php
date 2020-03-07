<?php


namespace App\Modules;


class CsvSaver
{
    public function saveToCsv($array) {

        $fp = fopen('result.csv', 'w');

        fputcsv($fp, ['Link','Title','Description','Thumbnail']);

        foreach ($array as $fields) {
            $newArray =  (array) $fields;
            fputcsv($fp, $newArray);
        }
    }
}