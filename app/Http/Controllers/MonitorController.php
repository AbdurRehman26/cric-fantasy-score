<?php

namespace App\Http\Controllers;

use App\Actions\Monitors\CreateMonitor;
use App\Actions\Monitors\GetMetrics;
use App\Actions\Monitors\UpdateMonitorConfigs;
use App\Actions\Monitors\UpdateMonitorInfo;
use App\Actions\Monitors\UpdateMonitorStatus;
use App\Actions\Monitors\UpdateNotificationChannels;
use App\Facades\Toast;
use App\Helpers\HtmxResponse;
use App\Models\Monitor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', [Monitor::class, $this->getCurrentProject()]);

        return view('monitors.index', [
            'monitors' => $this->getCurrentProject()->monitors,
        ]);
    }

    public function store(Request $request): HtmxResponse
    {
        $this->authorize('create', [Monitor::class, $this->getCurrentProject()]);

        app(CreateMonitor::class)->create(
            $this->getCurrentProject(),
            $request->input()
        );

        return htmx()->redirect(route('monitors.index'));
    }

    public function show(Monitor $monitor): View
    {
        $this->authorize('view', [$monitor, $this->getCurrentProject()]);

        return view('monitors.show', [
            'monitor' => $monitor,
        ]);
    }

    public function updateStatus(Monitor $monitor, string $status): RedirectResponse
    {
        $this->authorize('update', [$monitor, $this->getCurrentProject()]);

        app(UpdateMonitorStatus::class)->update($monitor, [
            'status' => $status,
        ]);

        return back();
    }

    public function updateInfo(Request $request, Monitor $monitor): RedirectResponse
    {
        $this->authorize('update', [$monitor, $this->getCurrentProject()]);

        app(UpdateMonitorInfo::class)->update($monitor, $request->input());

        Toast::success('Monitor updated');

        return back();
    }

    public function updateConfigs(Request $request, Monitor $monitor): RedirectResponse
    {
        $this->authorize('update', [$monitor, $this->getCurrentProject()]);

        app(UpdateMonitorConfigs::class)->update($monitor, $request->input());

        Toast::success('Monitor configurations updated');

        return back();
    }

    public function updateNotificationChannels(Request $request, Monitor $monitor): RedirectResponse
    {
        $this->authorize('update', [$monitor, $this->getCurrentProject()]);

        app(UpdateNotificationChannels::class)->update($monitor, $request->input());

        Toast::success('Notification channels updated');

        return back();
    }

    public function destroy(Monitor $monitor): HtmxResponse
    {
        $this->authorize('delete', [$monitor, $this->getCurrentProject()]);

        $monitor->delete();

        return htmx()->redirect(route('monitors.index'));
    }

    public function metrics(Request $request, Monitor $monitor): View
    {
        $this->authorize('view', [$monitor, $this->getCurrentProject()]);

        return view('monitors.metrics', [
            'monitor' => $monitor,
            'data' => app(GetMetrics::class)->filter($monitor, $request->input()),
        ]);
    }
}
