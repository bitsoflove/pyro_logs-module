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

    public function getLogs( $dateStart,  $dateEnd , $event = null, $toolversion = null){

        $data = $this->model->where('created_at', '>', $dateStart)->where('created_at', '<', $dateEnd);
        if($event){
            $data = $data->where('event', '=', $event);
        }

        return $data->get();
    }
}
