<?php  namespace Bitsoflove\LogsModule\Commands;

use Anomaly\Streams\Platform\Entry\EntryCollection;
use Bitsoflove\LogsModule\Log\Contract\LogInterface;
use Bitsoflove\LogsModule\Log\LogCollection;
use Bitsoflove\LogsModule\Log\LogModel;


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
        switch($column) {
            case 'user': {
                return isset($log->user->email) ? $log->user->email : '';
            }
        }

        return isset($log->$column) ? $log->$column : '';
    }

    public function handle()
    {
        $logs = $this->getLogs();
        $columns = $this->getColumns();
        $transformed = $this->transformData($logs, $columns);
        $csv = $this->buildLogsExportCsv($transformed, $columns);
        return $csv;
    }

    protected function buildLogsExportCsv($transformed, $fields) {
        $csv = $this->getCsv();

        $csv->insertOne($fields);
        foreach ($transformed as $mapped) {
            $csv->insertOne($mapped);
        }

        return $csv;
    }

    protected function getCsv() {
        $file = new \SplTempFileObject();
        $file->setCsvControl(';');
        $csv = \League\Csv\Writer::createFromFileObject($file);
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
