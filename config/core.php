<?php

use App\Enums\MonitorType;
use App\Enums\NotificationChannelType;

return [
    'check_frequencies' => ['1m', '5m', '15m', '30m', '1h'],
    'default_check_frequency' => '5m',
    'metric_intervals' => [
        '1h',
        '12h',
        '1d',
        '7d',
        '30d',
        'custom',
    ],

    'monitor_types' => [
        MonitorType::HTTP->value => [
            'class' => \App\MonitorTypes\HttpMonitor::class,
            'frequencies' => ['1m', '5m', '15m', '30m', '1h'],
            'thresholds' => ['0s', '30s', '1m', '5m', '10m', '30m', '1h'],
            'default_threshold' => '0s',
        ],
    ],

    'notification_channels' => [
        NotificationChannelType::EMAIL->value => [
            'class' => \App\NotificationChannels\Email::class,
        ],
        NotificationChannelType::SLACK->value => [
            'class' => \App\NotificationChannels\Slack::class,
        ],
        NotificationChannelType::DISCORD->value => [
            'class' => \App\NotificationChannels\Discord::class,
        ],
        // NotificationChannelType::TELEGRAM->value => [
        //     'class' => \App\NotificationChannels\Telegram::class,
        // ],
    ],

    'monitor_nodes' => [
        [
            'id' => 'de-1',
            'location' => 'de',
            'name' => 'Germany',
            'capacity' => 1000,
            'used' => 0,
            'active' => true,
        ],
        [
            'id' => 'es-1',
            'location' => 'es',
            'name' => 'Spain',
            'capacity' => 1000,
            'used' => 0,
            'active' => false,
        ],
    ],

    'metric_colors' => [
        'de' => '#eab308',
        'es' => '#f43f5e',
    ],
];
