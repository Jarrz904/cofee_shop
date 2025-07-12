<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Relasi: Satu user bisa memiliki banyak pesanan.
     */
    public function orders() { 
        return $this->hasMany(Order::class); 
    }
    
    // ==========================================================
    // == PERBAIKAN UTAMA: Tambahkan Method ini ==
    // ==========================================================
    /**
     * Mengecek apakah pengguna memiliki peran sebagai admin.
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        // Ganti 'admin' dengan nilai yang Anda gunakan di database
        // jika berbeda (misalnya, $this->role === 1).
        return $this->role === 'admin';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- Pastikan 'role' bisa diisi jika Anda membuatnya saat registrasi
    ];

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
     * The attributes that should be cast.
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
}