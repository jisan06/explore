<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];

    protected $appends = ['fullname', 'avatar', 'middleinitial'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name.
     */
    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . ' '. ($this->middlename ? $this->middlename . ' ' : '') . $this->lastname,
        );
    }

    /**
     * Get the photo.
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->photo && file_exists(public_path('/storage/'.$this->photo)) ? asset('/storage/'.$this->photo) : '',
        );
    }

    /**
     * Get middle initial.
     */
    protected function middleinitial(): Attribute
    {
        return Attribute::make(
            get: fn () => substr($this->middlename, 0, 1),
        );
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }
}
