<?php namespace Bitsoflove\LogsModule\Log;

use Anomaly\UsersModule\User\UserModel;
use Bitsoflove\LogsModule\Log\Exceptions\InvalidLogDataException;
use Bitsoflove\LogsModule\Log\Contract\LogInterface;
use Anomaly\Streams\Platform\Model\Logs\LogsLogsEntryModel;
use Illuminate\Support\Facades\Log;

class LogModel extends LogsLogsEntryModel implements LogInterface
{
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
    }

    protected $casts = [
        'data' => 'array',
    ];

    public function user() {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function getDataAttribute() {
        return json_decode($this->attributes['data'], true);
    }

    public function setDataAttribute($value) {

        $jsonValue = $this->createJsonData($value);
        $this->attributes['data'] = $jsonValue;
    }

    private function createJsonData($value) {

        try {
            // allow passing of an array. we will cast this to json here
            if(is_array($value)) {
                $value = json_encode($value, JSON_PRETTY_PRINT);
            }

            if(!$this->isJson($value)) {
                throw new InvalidLogDataException("Cannot create JSON data for log entry - invalid input", $value);
            }

        } catch(\Exception $e) {
            Log::error($e);
            return null;
        }

        // by now, we're sure $value is a valid json string
        return $value;
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
