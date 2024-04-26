<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use DB;
use App\Models\MemoTag;
use App\Models\Tag;

class Memo extends Model
{
    use HasFactory;

    public function getMymemo(){
        $query_tag = \Request::query('tag');
        $query = self::query()->select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC');

        if ( !empty($query_tag) ) {
            $query->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                ->where('memo_tags.tag_id', '=', $query_tag);
        }

        $memos = $query->get();

        return $memos;
    }

    public static function getMemosForCurrentUser() {
        return self::where('user_id', '=', \Auth::id())
                   ->whereNull('deleted_at')
                   ->orderBy('updated_at', 'DESC')
                   ->get();
    }

    public static function createMemoWithTags($posts)
    {
        DB::transaction(function() use ($posts) {
            $memo_id = self::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);

            if(!empty($posts['new_tag'])) {
                $tag = Tag::createIfNeeded($posts['new_tag'], \Auth::id());
                MemoTag::attachTagToMemo($memo_id->id, $tag->id);
            }
            
            if(!empty($posts['tags'])) {
                foreach($posts['tags'] as $tagId) {
                    MemoTag::attachTagToMemo($memo_id->id, $tagId);
                }
            }
        });
    }

    public static function getMemoWithTags($id) {
        return self::select('memos.*', 'tags.id as tag_id')
            ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('memos.user_id', '=', \Auth::id())
            ->where('memos.id', '=', $id)
            ->whereNull('memos.deleted_at')
            ->get();
    }

    public static function updateMemoAndTags($memoId, $content, $tagIds, $newTag = null)
    {
        DB::transaction(function() use ($memoId, $content, $tagIds, $newTag) {
            $memo = self::find($memoId);
            $memo->content = $content;
            $memo->save();

            MemoTag::detachTagsFromMemo($memoId);
            MemoTag::attachTagsToMemo($memoId, $tagIds);

            if (!empty($newTag)) {
                $tag = Tag::createIfNeeded($newTag, $memo->user_id);
                MemoTag::attachTagsToMemo($memoId, [$tag->id]);
            }
        });
    }

    use SoftDeletes;

    protected $fillable = ['content', 'user_id'];

    public static function softDelete($memoId)
    {
        $memo = self::find($memoId);
        if ($memo) {
            $memo->delete();
        }
    }
}