<?php  namespace Bitsoflove\LogsModule\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


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
        set_time_limit(0);

        $cmd = $this->getExportLogsCommand();

        $path = $this->getExportPath();

        $csv = $cmd->handle($path);

        // if all was successful, we can remove the previous log(s)
        $this->cleanupPreviousExports();

        $this->info($path);
        return $csv;
    }

    private function getExportPath() {
        $path = storage_path('exports/logs/' . 'logs-export-' . date('Y-m-d H:i:s') . '.csv');
        return $path;
    }

    /**
     * @return ExportLogsCommandInterface
     */
    private function getExportLogsCommand()
    {
        return app(ExportLogsCommandInterface::class);
    }

    private function cleanupPreviousExports()
    {
        $folder = storage_path('exports/logs');
        $twoDays = (60 * 24 * 2);
        return $this->clearFilesOlderThan($twoDays, $folder);
    }


    private function clearFilesOlderThan($minutes, $tmpFolder)
    {
        if (file_exists($tmpFolder)) {
            foreach (new \DirectoryIterator($tmpFolder) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
                if ($fileInfo->isFile() && time() - $fileInfo->getCTime() >= 60 * $minutes) {

                    $fileName = $fileInfo->getFilename();
                    if(starts_with($fileName, '.')) {
                        // skip .gitignore
                        continue;
                    }

                    $success = unlink($fileInfo->getRealPath());

                    if(!$success) {
                        Log::error("Failed to delete " . $fileInfo->getRealPath());
                    }
                }
            }
        }
    }
}
