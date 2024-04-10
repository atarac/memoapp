@extends('layouts.app')

@section('content')
<div class="p-0">
    <div class="card">
        <h5 class="card-header">新規メモ作成</h5>
        <form class="card-body my-card-body my-card-body-gray" action="{{ route('store') }}" method="POST">
            @csrf
            <div class="form-group mb-3 h-60">
                <textarea class="form-control h-100" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
            </div>
        @error('content')
            <div class="alert alert-danger">メモ内容を入力してください</div>
        @enderror
        <div id="tags-container">
            @foreach($tags as $t)
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}" />
                    <label class="form-check-label" for="{{ $t['id'] }}">{{ $t['name'] }}</label>
                </div>
            @endforeach
        </div>
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグ" />
            <button type="submit" class="btn btn-success">保存</button>
        </form>
    </div>
</div>
@endsection
