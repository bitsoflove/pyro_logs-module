<?php namespace Bitsoflove\LogsModule\Log;

use Bitsoflove\LogsModule\Log\Contract\LogRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;
use Carbon\Carbon;

class LogRepository extends EntryRepository implements LogRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var LogModel
     */
    protected $model;

    /**
     * Create a new LogRepository instance.
     *
     * @param LogModel $model
     */
    public function __construct(LogModel $model)
    {
        $this->model = $model;
    }

    public function getLogs($dateStart, $dateEnd , $event = null, $toolversion = null){
       $data = $this->model->where('created_at', '>', (new Carbon($dateStart))->startOfDay())->where('created_at', '<', (new Carbon($dateEnd))->addDay());
       if($event){
           $data = $data->where('event', '=', $event);
       }
       if($toolversion){
           //get data for toolversion
       }
       return $data->get();
    }
}
