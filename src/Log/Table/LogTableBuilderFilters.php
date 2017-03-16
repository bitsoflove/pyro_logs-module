<?php namespace Bitsoflove\LogsModule\Log\Table;

use Anomaly\Streams\Platform\Entry\EntryQueryBuilder;
use Anomaly\Streams\Platform\Ui\Table\Component\Filter\Contract\FilterInterface;
use Illuminate\Support\Facades\Auth;
use Puratos\GeneralModule\Toolversion\ToolversionModel;

class LogTableBuilderFilters
{

    public function handle(LogTableBuilder $builder) {

        // default: no filters
        $builder->setFilters([

        ]);

    }
}
