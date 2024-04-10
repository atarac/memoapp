@extends('layouts.app')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection

@section('content')
<div class="p-0">
    <div class="card">
        <h5 class="card-header d-flex justify-content-between">
            メモ編集
            <form id="delete-form" action="{{ route('destroy') }}" method="POST">
                @csrf
                <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
                <i class="fa-solid fa-trash-can pointer me-3" onclick="deleteHandle(event);"></i>
            </form>
        </h5>
        <form class="card-body my-card-body my-card-body-gray" action="{{ route('update') }}" method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <div class="form-group mb-3 h-60">
                <textarea class="form-control h-100" name="content" rows="3" placeholder="ここにメモを入力">{{
                    $edit_memo[0]['content'] }}</textarea>
            </div>
        @error('content')
            <div class="alert alert-danger">メモ内容を入力してください</div>
        @enderror
        @foreach($tags as $t)
            <div class="form-check form-check-inline mb-3">
                <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}" {{ in_array($t['id'], $include_tags) ? 'checked' : '' }} />
                <label class="form-check-label" for="{{ $t['id'] }}">{{ $t['name'] }}</label>
            </div>
        @endforeach
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグ" />
            <button type="submit" class="btn btn-success">更新</button>
        </form>
    </div>
</div>
@endsection
