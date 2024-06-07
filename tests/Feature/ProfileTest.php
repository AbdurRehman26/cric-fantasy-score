<?php

use App\Models\User;

test('profile page is displayed', function () {
    $response = $this
        ->actingAs(User::factory()->withDefaultProject()->create())
        ->get('/profile');

    $response
        ->assertOk()
        ->assertSee('Profile Information')
        ->assertSee('Update Password')
        ->assertSee('Delete Account');
});

test('profile information can be updated', function () {
    $user = User::factory()->withDefaultProject()->create();

    $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
            'timezone' => 'UTC',
        ]);

    $user->refresh();

    expect('Test User')->toEqual($user->name);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->withDefaultProject()->create();

    $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    expect($user->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->withDefaultProject()->create();

    $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->withDefaultProject()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect(route('profile'));

    expect($user->fresh())->not->toBeNull();
});
