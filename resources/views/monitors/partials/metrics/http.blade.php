<div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
    <div class="order-2 col-span-1 space-y-5 xl:order-1 xl:col-span-2">
        <x-simple-card>
            <div class="flex justify-between">
                <div>
                    <x-title class="mb-2">{{ __("Response Time") }}</x-title>
                    <x-description>{{ __("Based on Location") }}</x-description>
                </div>
            </div>
            <div id="response-time-chart"></div>
            <script>
                window.addEventListener("load", function() {
                    let options = {
                        series: [
                            @foreach ($monitor->locations as $location)
                                {
                                    name: "{{ str($location)->upper() }}",
                                    data: @json($data['metrics']->where('location', $location)->pluck('response_time')->toArray()),
                                    color: "{{ config('core.metric_colors')[$location] }}",
                                },
                            @endforeach
                        ],
                        chart: {
                            height: "100%",
                            maxWidth: "100%",
                            type: "area",
                            fontFamily: "Inter, sans-serif",
                            dropShadow: {
                                enabled: false,
                            },
                            toolbar: {
                                show: false,
                            },
                        },
                        tooltip: {
                            enabled: true,
                            x: {
                                show: true,
                            },
                        },
                        legend: {
                            show: true
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                opacityFrom: 0.55,
                                opacityTo: 0,
                                shade: "#1C64F2",
                                gradientToColors: ["#1C64F2"],
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            width: 4,
                        },
                        grid: {
                            show: false,
                            strokeDashArray: 4,
                            padding: {
                                left: 2,
                                right: 2,
                                top: 0
                            },
                        },
                        xaxis: {
                            categories: @json($data['metrics']->where('location', $monitor->locations[0])->pluck('date')->toArray()),
                            labels: {
                                show: false,
                            },
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                        },
                        yaxis: {
                            show: false,
                            labels: {
                                formatter: function(value) {
                                    return parseInt(value) + 'ms';
                                }
                            }
                        },
                    }

                    if (document.getElementById("response-time-chart") && typeof ApexCharts !== 'undefined') {
                        const chart = new ApexCharts(document.getElementById("response-time-chart"), options);
                        chart.render();
                    }
                });
            </script>
        </x-simple-card>
        <x-simple-card>
            <div class="flex justify-between">
                <div>
                    <x-title class="mb-2">{{ __("Last Checks") }}</x-title>
                    <x-description>{{ __("Here you can see the last checks") }}</x-description>
                </div>
            </div>
            <x-table class="mt-5">
                <x-thead>
                    <tr>
                        <x-th>{{ __("Location") }}</x-th>
                        <x-th>{{ __("Response Time") }}</x-th>
                        <x-th>{{ __("Datetime") }}</x-th>
                    </tr>
                </x-thead>
                <x-tbody>
                    @php
                        $records = $monitor
                            ->records()
                            ->latest()
                            ->limit(10)
                            ->get();
                    @endphp

                    @foreach ($records as $record)
                        <x-tr>
                            <x-td class="uppercase">{{ $record->location }}</x-td>
                            <x-td>{{ $record->data["response_time"] }}ms</x-td>
                            <x-td>
                                <x-datetime :value="$record->created_at" />
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-tbody>
            </x-table>
        </x-simple-card>
    </div>
    <div class="order-1 col-span-1 xl:order-2">
        <div class="grid gap-5">
            <div class="order-2 xl:order-1">
                <x-simple-card class="flex items-center justify-between">
                    <x-title>{{ __("Uptime") }}</x-title>
                    <x-uptime-time class="text-2xl font-bold" :value="$data['uptime']" />
                </x-simple-card>
            </div>
            <div class="order-1 space-y-5 xl:order-2">
                @include("monitors.partials.metrics.general")
                @include("monitors.partials.metrics.events")
            </div>
        </div>
    </div>
</div>
