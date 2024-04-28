<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoTag extends Model
{
    use HasFactory;

    protected $fillable = ['memo_id', 'tag_id'];

    public static function detachTagsFromMemo($memoId)
    {
        self::where('memo_id', $memoId)->delete();
    }

    public static function attachTagOrTagsToMemo($memoId, $tagIds)
    {
        if (!is_array($tagIds)) {
            $tagIds = [$tagIds];
        }

        $tagsData = array_map(function($tagId) use ($memoId) {
            return ['memo_id' => $memoId, 'tag_id' => $tagId];
        }, $tagIds);

        self::insert($tagsData);
    }

    public static function detachTagsFromTag($tagId)
    {
        $memoIds = self::where('tag_id', $tagId)->pluck('memo_id');

        if (!empty($memoIds)) {
            foreach ($memoIds as $memoId) {
                self::where('memo_id', $memoId)->delete();
            }
        }
    }
}
