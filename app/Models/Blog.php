<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use HasFactory;

    const OPEN = 1;
    const CLOSED = 0;

    protected $guarded = [];

    protected $hidden = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected static function booted()
    {
        static::deleting(function ($blog) {
            $blog->deletePictFile();
            
            $blog->comments->each(function ($comment) {
                $comment->delete();
            });
        });
    }

    public function deletePictFile()
    {
        if ($this->pict) {
            Storage::disk('public')->delete($this->pict);
        }
    }
}
