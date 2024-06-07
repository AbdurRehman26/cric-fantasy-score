<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getUser(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->refresh();
    }

    protected function getCurrentProject(): Project
    {
        $user = $this->getUser();

        return $user->currentProject ?? $user->createDefaultProject();
    }
}
