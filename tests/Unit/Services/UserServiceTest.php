<?php

namespace Services;

use App\Models\User;
use App\Services\UserServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected $user_service;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user_service = $this->app->make(UserServiceInterface::class);
    }

    /** @test */
    public function it_can_return_a_paginated_list_of_users()
    {
        $per_page = 2;
        $users = $this->user_service->list($per_page);
        $this->assertCount($per_page, $users->items());
    }

    /** @test */
    public function it_can_store_a_user_to_database()
    {
        $attribute = [
            'prefixname' => fake()->randomElement(['Mr', 'Mrs', 'Ms']),
            'firstname' => fake()->name(),
            'middlename' => fake()->name(),
            'lastname' => fake()->name(),
            'suffixname' => fake()->name(),
            'username' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'type' => fake()->text(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        $this->user_service->store($attribute);

        $this->assertDatabaseHas('users', [
            'username' => $attribute['username'],
            'email' => $attribute['email'],
        ]);
    }

    /** @test */
    public function it_can_find_and_return_an_existing_user()
    {
        $user = User::latest()->first();
        $exist_user = $this->user_service->find($user->id);

        $this->assertEquals($user->id, $exist_user->id);
        $this->assertEquals($user->username, $exist_user->username);
    }

    /** @test */
    public function it_can_update_an_existing_user()
    {
        $user = User::latest()->first();
        $data = [
            'suffixname' => fake()->name(),
        ];
        $this->user_service->update($user->id, $data);
        $updated_user = $user->fresh();

        $this->assertEquals($data['suffixname'], $updated_user->suffixname);
    }

    /** @test */
    public function it_can_soft_delete_an_existing_user()
    {
        $user = User::latest()->first();
        $this->user_service->destroy($user->id);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        $per_page = 2;
        $users = $this->user_service->listTrashed($per_page);
        $this->assertCount($per_page, $users->items());
    }

    /** @test */
    public function it_can_restore_a_soft_deleted_user()
    {
        $user = User::withTrashed()->latest()->first();
        $this->user_service->restore($user->id);
        $restored_user = $user->fresh();

        $this->assertFalse($restored_user->trashed());
    }

    /** @test */
    public function it_can_permanent_delete_a_soft_deleted_user()
    {
        $user = User::withTrashed()->latest()->first();
        $this->user_service->delete($user->id);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_upload_photo()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->user_service->upload($file);
        Storage::disk('public')->assertExists($path);
    }
}
