<?php


namespace App\Services;


use App\Models\Detail;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    public function __construct(User $user)
    {

    }

    public function list(int $per_page): LengthAwarePaginator
    {
        return User::paginate($per_page ?? 10);
    }

    public function store(array $attribute)
    {
        if( !empty($attribute['photo']) ) {
            $attribute['photo'] = $this->upload($attribute['photo']);
        }
        return User::create($attribute);
    }

    public function find(int $id)
    {
       return User::findOrFail($id);
    }

    public function update(int $id, array $attribute)
    {
        $user = User::findOrFail($id);
        if( !empty($attribute['photo']) ) {
            $attribute['photo'] = $this->upload($attribute['photo']);
            if( $user->photo && file_exists(public_path('/storage/'.$user->photo)) ) {
                Storage::disk('public')->delete($user->photo);
            }
        }else {
            unset($attribute['photo']);
        }
        if( empty($attribute['password']) ) {
            unset($attribute['password']);
        }
        return $user->update($attribute);
    }

    public function destroy(int $id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function listTrashed($per_page): LengthAwarePaginator
    {
        return User::onlyTrashed()->paginate($per_page ?? 10);
    }

    public function restore(int $id)
    {
        $user = User::withTrashed()->find($id);
        if ($user && $user->trashed()) {
            $user->restore();
        }
    }

    public function delete(int $id)
    {
        $user = User::withTrashed()->find($id);
        if ($user && $user->trashed()) {
            $user->forceDelete();
        }
    }

    public function upload(UploadedFile $file)
    {
        $path = $file->store('images', 'public');
        return $path;
    }

    public function saveDetails(User $user)
    {
        $fullName = trim("{$user->firstname} {$user->middlename} {$user->lastname}");
        $middleInitial = substr($user->middlename, 0, 1);

        $userDetails = [
            ['key' => 'full_name', 'value' => $fullName],
            ['key' => 'middle_initial', 'value' => $middleInitial],
            ['key' => 'avatar', 'value' => $user->photo],
            ['key' => 'gender', 'value' => $this->getGender($user->prefixname)],
        ];

        foreach ($userDetails as $detail) {
            Detail::updateOrCreate(
                ['key' => $detail['key'], 'user_id' => $user->id],
                ['value' => $detail['value'], 'type' => 'details']
            );
        }
    }

    protected function getGender($prefixName)
    {
        switch ($prefixName) {
            case 'Mr':
                return 'Male';
            case 'Mrs':
            case 'Ms':
                return 'Female';
            default:
                return 'Unknown'; // Handle unknown prefixes
        }
    }
}
