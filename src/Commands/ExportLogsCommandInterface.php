<?php namespace Bitsoflove\LogsModule\Commands;

use Bitsoflove\LogsModule\Log\Contract\LogInterface;
use League\Csv\Writer;

/**
 * When you're writing your own export implementation, you'll have to implement these 4 steps:
 *
 * 1. Get all the necessary logs for the export                                      (default = all logs)
 * 2. Define the columns that will be used for the export                            (default = the default database columns)
 * 3. Provide an implementation to get the formatted value for each of those columns (default = raw value)
 * 4. Handle the actual export                                                       (default = csv export)
 *
 * Interface ExportLogsCommandInterface
 * @package Bitsoflove\LogsModule\Commands
 */
interface ExportLogsCommandInterface
{
    /**
     * Get all the logs necessary for the export.
     * By default this will return all logs in reverse order (newest first)
     *
     * @return \Anomaly\Streams\Platform\Entry\EntryCollection
     */
    function getLogs();

    /**
     * Lists the columns that will be used in the csv
     * default: ['event', 'slug', 'data', 'message', 'user', 'created_at']
     *
     * @return array
     */
    function getColumns();

    /**
     * Given a log entry, and one of the columns from getColumns(),
     * this function should return the formatted value, to be present in the export
     *
     * eg. For a given column 'user', one might return $log->user->first_name
     *
     * @return string
     */
    function getColumnValue(LogInterface $log, $column);

    /**
     * Handle your export. By default this package will export to CSV.
     * When rolling your own export logic, you'll have to call the other implemented methods yourself
     *
     * @return Writer
     */
    public function handle($path);
}
