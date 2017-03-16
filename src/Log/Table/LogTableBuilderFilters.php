<?php namespace Bitsoflove\LogsModule\Log\Table;


use Anomaly\Streams\Platform\Entry\EntryQueryBuilder;
use Anomaly\Streams\Platform\Ui\Table\Component\Filter\Contract\FilterInterface;
use Illuminate\Support\Facades\Auth;
use Puratos\GeneralModule\Toolversion\ToolversionModel;



class LogTableBuilderFilters
{

    protected $supportedLocales = null;
    protected $isSuperAdmin = false;
    protected $user = null;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->supportedLocales = $this->getSupportedLocales();
        $this->isSuperAdmin = \Profile::isSuperAdmin();
    }

    public function handle(LogTableBuilder $builder) {

        $builder->setFilters([
            'search' => [
                'columns' => [
                    'slug',
                    'message',
                    'email',
                    'general_toolversions.locale',
                    'general_toolversions.label_language',
                    'general_toolversions.label_country',

                    'user',
                ],
            ],

            'locale' => [
                'filter' => 'select',
                'options' => $this->supportedLocales,
                'query' => function (EntryQueryBuilder $query, FilterInterface $filter)
                  {
                      if(!$this->isSuperAdmin){
                          $query->join('users_users', 'users_users.id', '=', "logs_logs.user_id")
                                ->join('users_users_roles', 'users_users.id', '=', "users_users_roles.entry_id")
                                ->join('users_roles', 'users_users_roles.related_id', '=', "users_roles.id")
                                ->where('users_roles.slug', '!=', 'puratos_admin')
                                ->where($filter->getSlug(), 'LIKE', "%{$filter->getValue()}%");
                          //
                      } else {
                          $query->where($filter->getSlug(), 'LIKE', "%{$filter->getValue()}%");
                      }
                  }
            ],
        ]);
    }

    private function getSupportedLocales()
    {
        $locales = config('streams::locales.enabled');

        $toolversions = ToolversionModel::whereIn('locale', $locales)->get();
        $prepared = [];
        foreach($toolversions as $version) {
            $prepared[$version->locale] = $version->label_language . " (" . $version->label_country . ')';
        }
        return $prepared;
    }
}
