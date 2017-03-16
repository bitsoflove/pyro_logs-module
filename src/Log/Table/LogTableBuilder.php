<?php namespace Bitsoflove\LogsModule\Log\Table;

use Anomaly\Streams\Platform\Ui\Table\Event\TableIsQuerying;
use Anomaly\Streams\Platform\Ui\Table\Table;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use App\Overrides\Log\Table\LogTableColumns;

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


    // @todo: this module was meant to be reusable, so all this stuff should happen elsewhere
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

                if(!$query->hasJoin('profiles_profiles')) {
                    $query->join('profiles_profiles', 'profiles_profiles.user_id', '=', 'users_users.id');
                }

                if(!$query->hasJoin('profiles_profiles_toolversions')) {
                    $query->join('profiles_profiles_toolversions', 'profiles_profiles.id', '=', 'profiles_profiles_toolversions.entry_id');
                }

                if(!$query->hasJoin('general_toolversions')) {
                    $query->join('general_toolversions', 'general_toolversions.id', '=', 'profiles_profiles_toolversions.related_id');
                }

                $query->select([
                    // fetch all the columns from our logs tables
                    'logs_logs.*',

                    // plus whatever we're interested in to filter on
                    // @warn ensure no duplicate column names !
                    "users_users.first_name + ' ' + users_users.last_name as user",
                    'users_users.email as email',

                    'general_toolversions.locale as locale',
                    'general_toolversions.label_language as label_language',
                    'general_toolversions.label_country as label_country',
                ]);

                $query->orderBy('logs_logs.id', 'desc');
            }
        });
    }
}
