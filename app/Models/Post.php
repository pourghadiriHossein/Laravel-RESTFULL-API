<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $appends = [
        'short_description',
    ];

    protected $fillable = [
        'title',
        'description',
        'up_vote_count',
        'location'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function votes() {
        return $this->hasMany(UpVote::class);
    }

    public function media() {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtolower($value),
        );
    }
    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::words($this->description, 5),
        );
    }
}
