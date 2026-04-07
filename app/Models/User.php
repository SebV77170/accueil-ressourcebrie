<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'task_color',
        'sub_task_color',
        'task_background_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function getConnectionName(): ?string
    {
        $configuredConnection = config('database.auth_connection', parent::getConnectionName());
        $defaultConnection = config('database.default');
        $sharedAuthDatabase = config('database.connections.mysql_auth.database');
        $defaultDatabase = config("database.connections.{$defaultConnection}.database");

        if (
            $configuredConnection === $defaultConnection
            && $defaultConnection === 'mysql'
            && $sharedAuthDatabase
            && $sharedAuthDatabase !== $defaultDatabase
        ) {
            return 'mysql_auth';
        }

        return $configuredConnection;
    }

    public function loginIdentifierColumn(): string
    {
        if (Schema::connection($this->getConnectionName())->hasColumn($this->getTable(), 'pseudo_normalise')) {
            return 'pseudo_normalise';
        }

        return 'email';
    }
}
