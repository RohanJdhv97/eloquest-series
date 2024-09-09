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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopeSearch($query, $search = null)
    {
        // $query->join('posts', 'posts.user_id', '=', 'users.id');

        collect(explode(' ', $search))->filter()->each(function ($term) use ($query) {
            $term = "{$term}%";
            $query->whereIn('id',function($query) use ($term){
               $query->select('id')->from(function($query) use ($term){
                  $query->select('id')->from('users')->union(
                      $query->newQuery()
                        ->select('users.id')
                        ->from('users')
                        ->join('posts', 'posts.user_id', '=', 'users.id')
                        ->where('posts.title', 'like', $term)
                  );
               }, 'matches');
            });
        });
    }
}
