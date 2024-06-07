<x-response-time
    :response-time="$monitor->lastRecord()->data['response_time']"
    :is-up="$monitor->lastRecord()->data['is_up']"
/>
