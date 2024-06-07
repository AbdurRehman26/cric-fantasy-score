<?php

namespace App\Http\Controllers;

use App\Actions\NotificationChannels\CreateNotificationChannel;
use App\Helpers\HtmxResponse;
use App\Models\NotificationChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationChannelController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', [NotificationChannel::class, $this->getCurrentProject()]);

        return view('notification-channels.index', [
            'pageTitle' => __('Notification Channels'),
            'channels' => $this->getCurrentProject()->notificationChannels()->with('monitors')->get(),
        ]);
    }

    public function store(Request $request): HtmxResponse
    {
        $this->authorize('create', [NotificationChannel::class, $this->getCurrentProject()]);

        app(CreateNotificationChannel::class)->create(
            $this->getCurrentProject(),
            $request->input()
        );

        session()->flash('toast.type', 'success');
        session()->flash('toast.message', __('Notification channel created.'));

        return htmx()->redirect(route('notification-channels.index'));
    }

    public function verifyEmail(NotificationChannel $notificationChannel): RedirectResponse
    {
        $this->authorize('update', [$notificationChannel, $this->getCurrentProject()]);

        $notificationChannel->is_connected = true;
        $notificationChannel->save();

        session()->flash('toast.type', 'success');
        session()->flash('toast.message', __('Email verified.'));

        return redirect()->route('notification-channels.index');
    }

    public function resendEmail(NotificationChannel $notificationChannel): RedirectResponse
    {
        $this->authorize('update', [$notificationChannel, $this->getCurrentProject()]);

        if ($notificationChannel->is_connected || $notificationChannel->type != 'email') {
            abort(404);
        }

        $notificationChannel->type()->connect();

        session()->flash('toast.type', 'success');
        session()->flash('toast.message', __('Email sent.'));

        return back();
    }

    public function destroy(NotificationChannel $notificationChannel): RedirectResponse
    {
        $this->authorize('delete', [$notificationChannel, $this->getCurrentProject()]);

        DB::beginTransaction();
        try {
            $notificationChannel->delete();
            $notificationChannel->monitors()->detach();

            session()->flash('toast.type', 'success');
            session()->flash('toast.message', __('Notification channel deleted.'));

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            session()->flash('toast.type', 'danger');
            session()->flash('toast.message', __('Failed to delete notification channel.'));

            Log::error($e->getMessage(), $e->getTrace());
        }

        return back();
    }
}
