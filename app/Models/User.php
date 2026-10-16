<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $guarded = ['id', 'role'];           // role لا يُعبّأ أبدًا من الطلب (Mass-assignment safe)
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['password' => 'hashed', 'email_verified_at' => 'datetime']; }
    public function orders() { return $this->hasMany(Order::class); }
}
