<?php namespace Bitsoflove\LogsModule\Log\Table;

use Anomaly\Streams\Platform\Ui\Table\Event\TableIsQuerying;
use Anomaly\Streams\Platform\Ui\Table\Table;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

class LogTableBuilder extends TableBuilder
{

    public function __construct(Table $table)
    {
        $this->columns = config('bitsoflove.module.logs::logs.table.columns');
        $this->model = config('bitsoflove.module.logs::logs.model');

        parent::__construct($table);

        $this->manipulateQuery();
    }
    
    protected $filters = LogTableBuilderFilters::class;

    protected $criteria = [
        'order_by' => [
            'column' => 'created_at',
            'direction' => 'DESC',
        ]
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        //pagination and limit
        'enable_pagination' => true,
        'limit' => 75,

        'eager' => [
            'user',
        ],
    ];


    private function manipulateQuery()
    {
        $events = app('events');

        $events->listen(TableIsQuerying::class, function(TableIsQuerying $event) {
            $isCurrentBuilder = ($event->getBuilder() instanceof LogTableBuilder);
            if($isCurrentBuilder) {
                $query = $event->getQuery();

                if(!$query->hasJoin('users_users')) {
                    $query->join('users_users', 'logs_logs.user_id', '=', 'users_users.id');
                }

                $query->select([
                    // fetch all the columns from our logs tables
                    'logs_logs.*',

                    // plus whatever we're interested in to filter on
                    // @warn ensure no duplicate column names !
                    "users_users.first_name + ' ' + users_users.last_name as user",
                    'users_users.email as email',
                ]);

                $query->orderBy('logs_logs.id', 'desc');
            }
        });
    }
}
