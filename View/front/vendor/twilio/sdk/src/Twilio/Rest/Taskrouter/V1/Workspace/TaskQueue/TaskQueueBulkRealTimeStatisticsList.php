<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Taskrouter
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace\TaskQueue;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Values;
use Twilio\Version;


class TaskQueueBulkRealTimeStatisticsList extends ListResource
    {
    /**
     * Construct the TaskQueueBulkRealTimeStatisticsList
     *
     * @param Version $version Version that contains the resource
     * @param string $workspaceSid The unique SID identifier of the Workspace.
     */
    public function __construct(
        Version $version,
        string $workspaceSid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'workspaceSid' =>
            $workspaceSid,
        
        ];

        $this->uri = '/Workspaces/' . \rawurlencode($workspaceSid)
        .'/TaskQueues/RealTimeStatistics';
    }

    /**
     * Create the TaskQueueBulkRealTimeStatisticsInstance
     *
     * @return TaskQueueBulkRealTimeStatisticsInstance Created TaskQueueBulkRealTimeStatisticsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(): TaskQueueBulkRealTimeStatisticsInstance
    {

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $data = $body->toArray();
        $headers['Content-Type'] = 'application/json';
        $payload = $this->version->create('POST', $this->uri, [], $data, $headers);

        return new TaskQueueBulkRealTimeStatisticsInstance(
            $this->version,
            $payload,
            $this->solution['workspaceSid']
        );
    }


    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Twilio.Taskrouter.V1.TaskQueueBulkRealTimeStatisticsList]';
    }
}