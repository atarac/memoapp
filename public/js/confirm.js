function deleteHandle(event){
  event.preventDefault();

  if(window.confirm('本当に削除しても良いですか？')){
    document.getElementById('delete-form').submit();
  } else {
    alert('キャンセルしました');
  }
}