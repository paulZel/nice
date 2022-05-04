<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use \Storage;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;



    const IS_ADMIN = 1;
    const IS_NORMAL = 0;
    const IS_BANNED = 1;
    const IS_ACTIVE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'credo'
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
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function generatePassword($password)
    {
        if ($password != null)
        {
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }

    public function removeAvatar()
    {
        if($this->avatar != null)
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function uploadAvatar($image)
    {
        if($image == null) {return;}

        $this->removeAvatar();
        $filename = Str::random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename); //указываем папку относительно папки public
        $this->avatar = $filename;
        $this->save();
    }



    public function getImage()
    {
        if($this->avatar == null)
        {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin()
    {
        //$this->is-Admin = 1;
        $this->is_admin = Post::IS_ADMIN;
        $this->save();

    }

    public function makeNormal()
    {
        $this->is_admin = Post::IS_NORMAL;
        $this->save();

    }

    public function toggleAdmin($value)
    {
        if($value == null)
        {
            return $this->makeNormal();
        }
        return $this->makeAdmin();
    }

    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }

    public function unban()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBan($value)
    {
        if($value == 0)
        {
            return $this->unban();
        }
        return $this->ban();
    }
}
