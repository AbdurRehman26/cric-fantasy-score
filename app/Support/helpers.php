<?php

use App\Helpers\HtmxResponse;

/**
 * @throws Exception
 */
function date_with_timezone($date, $timezone): string
{
    $dt = new DateTime('now', new DateTimeZone($timezone));
    $dt->setTimestamp(strtotime($date));

    return $dt->format('Y-m-d H:i:s');
}

function htmx(): HtmxResponse
{
    return new HtmxResponse();
}

function convert_time_format($string): string
{
    $string = preg_replace('/(\d+)m/', '$1 minutes', $string);
    $string = preg_replace('/(\d+)s/', '$1 seconds', $string);
    $string = preg_replace('/(\d+)d/', '$1 days', $string);

    return preg_replace('/(\d+)h/', '$1 hours', $string);
}
