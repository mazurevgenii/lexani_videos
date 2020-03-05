<?php


namespace App\Modules;


class CsvSaver
{
    public function saveToCsv($array) {

        $fp = fopen('result.csv', 'w');

        // Loop through file pointer and a line
        foreach ($this->$array as $fields) {
            fputcsv($fp, $fields, "|");
        }
    }

}