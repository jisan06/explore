<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserServiceInterface
{
    public function list(int $per_page);

    public function store(array $attribute);

    public function find(int $id);

    public function update(int $id, array $attribute);

    public function destroy(int $id);

    public function listTrashed(int $per_page);

    public function restore(int $id);

    public function delete(int $id);

    public function upload(UploadedFile $file);

    public function saveDetails(User $user);

}
