<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Twigger\UnionCloud\API\Exception\BaseUnionCloudException;
use Twigger\UnionCloud\API\Exception\Request\IncorrectRequestParameterException;
use Twigger\UnionCloud\API\UnionCloud;

class SaveGroupInCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $groupId;

    protected $tries = 5;

    /**
     * SaveStudentInCache constructor.
     *
     * @param $groupId
     *
     */
    public function __construct($groupId)
    {
        $this->groupId = $groupId;
    }

    public function handle()
    {#
        /** @var UnionCloud $unioncloud */
        $unioncloud = app()->make(UnionCloud::class);

        $cacheKey = 'command:contactsheet:unioncloud:group:id.'.$this->groupId;

        if(!Cache::has($cacheKey)) {
            try {
                $group = $unioncloud->groups()->getByID($this->groupId)->get()->first();
                Cache::put('command:contactsheet:unioncloud:group:id.'.$this->groupId, $this->filterUser($group), 20000);
            } catch (BaseUnionCloudException $exception)
            {
                Cache::put('command:contactsheet:unioncloud:group:id.'.$this->groupId, json_encode([
                    'name' => 'N/A',
                ]), 200);
            }
        }
    }

    private function filterUser($group){
        return json_encode( [
            'name' => $group->name,
        ]);

    }
}
