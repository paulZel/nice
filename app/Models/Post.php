<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Auth;


class Post extends Model
{
    use HasFactory;
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBBLIC = 1;
    const IS_FEATURED = 1;
    const IS_STANDARD = 0;

    protected $fillable = ['title', 'content', 'date', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class, //модель с каторой мы связываемся
            'post_tags',
            'post_id', //первым указывается id этой модели
            'tag_id'
        );
    }

    public static function add($fields)
    {
        $post = new static; //можно new self;
        $post->fill($fields);
        $post->user_id = Auth::user()->id; //чтобы не приписывать в fields (user_id)кто угодно может поменять
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if($this->image != null) {
            Storage::delete('uploads/' . $this->image);
        }
    }

    public function uploadImage($image)
    {
        if ($image == null) {return;}

        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename); //указываем папку относительно папки public
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if($this->image == null)
        {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function setCategory($id)
    {
        if ($id == null) {return;}

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids == null) {return;}

        $this->tags()->sync($ids);
    }

    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = Post::IS_PUBBLIC;
        $this->save();
    }

    public function toggleStatus($value) //если кликнули чекбокс значит не null
    {
        if ($value == null) {
            return $this->setDraft();
        } else {
            return $this->setPublic();
        }
    }

    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURED;
        $this->save();
    }

    public function setStandard()
    {
        $this->is_featured = Post::IS_STANDARD;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if ($value == null) {
            return $this->setStandard();
        } else {
            return $this->setFeatured();
        }
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function setDateAtribute($value)
    {
        //return Carbon::parse($this->created_at)->diffForHumans();
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d H:i:s');

        $this->attributes['date'] = $date;
    }

    public function getCategoryTitle()
    {
        /*
        if($this->category != null)
        {
            return $this->category->title;
        }
        'No category';
        */

        return ($this->category != null) ? $this->category->title : 'No category';
    }

    public function getTagsTitles()
    {
        /*
        if(!$this->tags->isEmpty())
        {
            return implode(', ', $this->tags->pluck('title')->all());
        }
        return 'No tags';
        */

        return (!$this->tags->isEmpty()) ? implode(', ', $this->tags->pluck('title')->all())
            : 'No tags';

    }

    public function getCategoryID()
    {
        return $this->category != null? $this->category->id : null;
    }


    public function getDate()
    {
        //$newDate = $this->created_at->toDayDateTimeString();
        return $newDate = $this->created_at->format('M d, Y');
        //return Carbon::parse($this->created_at)->diffForHumans();

    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious(); //ID
        return self::find($postID);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext();
        return self::find($postID);
    }

    public function related()
    {
        return self::all()->except($this->id);
    }

    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }

    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }

    public static function getPopularPosts()
    {
        return self::orderBy('views', 'desc')->take(3)->get();
    }

    public static function getFeaturedPosts()
    {
        return self::where('is_featured', 1)->take(3)->get();
    }

    public static function getRecentPosts()
    {
        return self::orderBy('created_at', 'desc')->take(4)->get();
    }

    public function getUserName()
    {
        return $this->user != null ? $this->user->name : 'Alias';
    }
}
