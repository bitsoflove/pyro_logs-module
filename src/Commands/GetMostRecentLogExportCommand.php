<?php namespace Bitsoflove\LogsModule\Commands;


use League\Csv\AbstractCsv;
use League\Csv\Reader;

class GetMostRecentLogExportCommand
{
    /**
     * @return Reader
     */
    public function handle() {
        $dir = storage_path('exports/logs');
        $mostRecentFile = $this->getMostRecentFileInDirectory($dir);
        $fullFilename = "$dir/$mostRecentFile";

        $csv = $this->getCsvReader($fullFilename);
        return $csv;
    }

    /**
     * This method assumes that the files have a timestamped name,
     * meaning that the newest file will always be on top (when using the descending sorting algo)
     *
     * https://stackoverflow.com/a/11597482/237739
     */
    private function getMostRecentFileInDirectory($directory)
    {
        $files = scandir($directory, SCANDIR_SORT_DESCENDING);
        $newest_file = $files[0];

        return $newest_file;
    }

    /**
     * @param $csvFileLocation
     * @return Reader
     */
    private function getCsvReader($csvFileLocation)
    {
        $csv = Reader::createFromPath($csvFileLocation);
        $csv->setOutputBOM(Reader::BOM_UTF8);
        return $csv;
    }
}