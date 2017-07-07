<?php  namespace Bitsoflove\LogsModule\Commands;

use Anomaly\Streams\Platform\Entry\EntryCollection;
use Bitsoflove\LogsModule\Log\Contract\LogInterface;
use Bitsoflove\LogsModule\Log\LogCollection;
use Bitsoflove\LogsModule\Log\LogModel;
use Illuminate\Support\Facades\Log;
use League\Csv\AbstractCsv;
use League\Csv\Writer;


class ExportLogsCommand implements ExportLogsCommandInterface
{
    public function getLogs()
    {
        return LogModel::all()->reverse();
    }

    public function getColumns()
    {
        $columns = [
            'event',
            'slug',
            'data',
            'message',
            'user',
            'created_at',
        ];
        return $columns;
    }

    public function getColumnValue(LogInterface $log, $column)
    {
        try {
            switch($column) {
                case 'user': {
                    return (string) isset($log->user->email) ? $log->user->email : '';
                }
            }

            return (string) isset($log->$column) ? $log->$column : '';
        } catch(\Exception $e) {
            Log::error($e);
        }
    }

    public function handle($path)
    {
        $logs = $this->getLogs();
        $columns = $this->getColumns();
        $transformed = $this->transformData($logs, $columns);
        $csv = $this->buildLogsExportCsv($path, $transformed, $columns);
        return $csv;
    }

    protected function buildLogsExportCsv($path, $transformed, $fields) {
        $csv = $this->getCsv($path);

        $csv->insertOne($fields);
        foreach ($transformed as $mapped) {
            $csv->insertOne($mapped);
        }

        return $csv;
    }

    /**
     * @return Writer
     */
    protected function getCsv($path) {
        $csv = Writer::createFromPath($path, 'w');
        $csv->setDelimiter(';');

        return $csv;
    }


    protected function transformData(EntryCollection $logModels, array $fields)
    {
        $transformed = [];
        foreach($logModels as $logModel) {
            $transformed[] = $this->transformLog($logModel, $fields);
        }
        return $transformed;
    }

    protected function transformLog(LogModel $logModel, array $fields)
    {
        $transformed = [];
        foreach($fields as $field) {
            $value = $this->getColumnValue($logModel, $field);
            $transformed[$field] = $value;
        }
        return $transformed;
    }
}
