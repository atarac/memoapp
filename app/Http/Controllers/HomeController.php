<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $memos = Memo::getMemosForCurrentUser();
        $tags = Tag::getTagsForCurrentUser();

        return view('create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required']);
        Memo::createMemoWithTags($request->all());

        return redirect( route('home') );
    }

    public function edit($id)
    {        
        $edit_memo = Memo::getMemoWithTags($id);
        $include_tags = $edit_memo->pluck('tag_id')->all();
        $tags = Tag::getTagsForCurrentUser();
    
        return view('edit', compact('edit_memo', 'include_tags', 'tags'));
    }

    public function update(Request $request)
    {
        $request->validate(['content' => 'required']);
        $posts = $request->all();

        Memo::updateMemoAndTags(
            $posts['memo_id'],
            $posts['content'],
            $posts['tags'] ?? [],
            $posts['new_tag'] ?? null
        );

        return redirect(route('home'));
    }

    public function destroy(Request $request)
    {
        $memoId = $request->input('memo_id');
        Memo::softDelete($memoId);

        return redirect(route('home'));
    }

    public function tag_update(Request $request, $id)
    {
        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->save();

        return response()->json(['success' => true]);
    }

    public function tags_list($id)
    {        
        $edit_memo = Memo::getMemoWithTags($id);
        $include_tags = $edit_memo->pluck('tag_id')->all();
        $tags = Tag::getTagsForCurrentUser();

        return response()->json([
            'include_tags' => $include_tags,
            'tags' => $tags
        ]);
    }

}