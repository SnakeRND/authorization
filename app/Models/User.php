<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    private string $login;
    private string $email;
    private string $password;
    private string $anonymous_user;
    private mixed $verification_code;
    private int $monolith_id;
    private int $role_id;
    private string $token;
    private string $clientid_1C;

    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * @var int|mixed
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'anonymous_user',
        'verification_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'verification_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Переопределяет поле логина email для Passport на phone
     *
     * @param $identifier
     * @return mixed
     */
    public function findForPassport($identifier): mixed
    {
        return $this->where('login', $identifier)->first();
    }
}
