<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTableMigrationTest extends TestCase
{
    /** @test */
    public function users_table_has_columns()
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
            'photo',
            'type',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
            'deleted_at'
        ]));
    }
}
