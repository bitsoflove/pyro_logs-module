<?php namespace Bitsoflove\LogsModule\Http\Controller\Admin;

use Bitsoflove\LogsModule\Commands\ExportLogsCommand;
use Bitsoflove\LogsModule\Commands\ExportLogsCommandInterface;
use Bitsoflove\LogsModule\Log\Table\LogTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Illuminate\Support\Facades\Log;

class LogsController extends AdminController
{

    /**
     * Display an index of existing entries.
     *
     * @param LogTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(LogTableBuilder $table)
    {
        return $table->render();
    }


    /**
     * Todo this should be moved to an extendable /overridable class
     * @param LogTableBuilder $table
     */
    public function export(LogTableBuilder $table) {
        try {
            $this->disableDebugbar();

            $cmd = app(ExportLogsCommandInterface::class);
            $csv = $cmd->handle();

            $path = storage_path('temp/' . 'logs-export-' . date('Y-m-d H:i:s') . '.csv');
            $csv->output($path);
        } catch(\Exception $e) {
            Log::error($e);

            // on debug environments, throw exception
            if(config('app.debug')) {
                throw $e;
            }

            // return json error otherwise.
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }

    private function disableDebugbar()
    {
        $isEnabled = config('debugbar.enabled');

        if($isEnabled) {
            \Debugbar::disable();
        }
    }
}
