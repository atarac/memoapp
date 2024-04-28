<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public static function getTagsForCurrentUser() {
        return self::where('user_id', '=', \Auth::id())
                   ->whereNull('deleted_at')
                   ->orderBy('id', 'DESC')
                   ->get();
    }

    public static function createIfNeeded($name, $userId)
    {
        return self::firstOrCreate([
            'name' => $name,
            'user_id' => $userId
        ]);
    }

    public function memos() {
        return $this->belongsToMany(Memo::class);
    }

}
