<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BarberoPhoto extends Model
{
    protected $fillable = ['barbero_id', 'path', 'thumb_path', 'caption', 'is_featured'];

    protected $appends = ['url', 'thumb_url'];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }

    public function getUrlAttribute()
    {
        return $this->path ? Storage::url($this->path) : null;
    }
    public function getThumbUrlAttribute()
    {
        return $this->thumb_path ? Storage::url($this->thumb_path) : $this->url;
    }
}
