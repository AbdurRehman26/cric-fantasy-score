<?php

namespace Tests;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected User $user;

    protected Project $project;

    public function createUser(): User
    {
        $this->user = User::factory()->create();

        return $this->user;
    }

    protected function createProject(): Project
    {
        $this->project = Project::factory()->create([
            'user_id' => $this->user->id,
        ]);

        return $this->project;
    }

    protected function createUserWithProject(): User
    {
        $this->user = User::factory()->withDefaultProject()->create();

        $this->project = $this->user->currentProject;

        return $this->user;
    }
}
