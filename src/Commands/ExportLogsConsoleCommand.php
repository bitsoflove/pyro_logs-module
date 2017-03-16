<?php  namespace Bitsoflove\LogsModule\Commands;

use Illuminate\Console\Command;


class ExportLogsConsoleCommand extends Command
{

    protected $signature = 'export:logs';
    protected $description = 'export logs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $cmd = app(ExportLogsCommand::class);
        $csv = $cmd->handle();

        // save the csv temporarily
        $path = $this->getExportPath();

        $csv->output($path);

        $this->info($path);
        return $csv;
    }

    private function getExportPath() {
        $path = storage_path('temp/' . 'logs-export-' . date('Y-m-d H:i:s') . '.csv');
        return $path;
    }
}
