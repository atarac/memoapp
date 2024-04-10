function enableEdit(element) {
  let tagNameElement = element.previousElementSibling.getElementsByTagName('span')[0];
  editTagName(tagNameElement);
}

function editTagName(tagNameElement) {
  let currentText = tagNameElement.innerText;
  let parentLink = tagNameElement.parentNode; // <span>の親要素である<a>を取得
  let originalHref = parentLink.getAttribute('href'); // 元のhrefを保存

  // href属性を一時的に無効化
  parentLink.removeAttribute('href');

  tagNameElement.innerHTML = `<input type="text" class="form-control" value="${currentText}">`;
  let inputField = tagNameElement.firstChild;
  inputField.focus();

  let enterPressed = false;

  // Enterキーが押された時のみ保存を実行するイベントリスナーを追加
  inputField.addEventListener('keydown', function(event) {
      if (event.key === 'Enter') {
          event.preventDefault(); // フォームの送信を防ぐ
          saveTagName(this, tagNameElement.dataset.id, parentLink, originalHref);
          enterPressed = true;
          this.blur(); // エンターキーで保存後、フォーカスを外す
      }
  });

  // オプショナル: フォーカスを失うイベントでの挙動を制御
  inputField.addEventListener('blur', function(event) {
      if (enterPressed) {
          enterPressed = false;
          return;
      }
      tagNameElement.innerHTML = currentText; // フォーカスを失ったら元のテキストに戻す
      parentLink.setAttribute('href', originalHref); // href属性を復元
  });
}

function saveTagName(inputElement, tagId, parentLink, originalHref) {
  let newValue = inputElement.value;
  // AJAXを使用してサーバーに新しい値をPOST
  fetch(`/update-tag/${tagId}`, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({name: newValue})
  }).then(response => response.json())
  .then(data => {
      if (data.success) {
          inputElement.value = newValue;  // 入力フィールドに新しい値をセット
          let parentSpan = inputElement.closest('.tag-container').querySelector('.tag-name');
          parentSpan.innerText = newValue; // SPAN要素に新しい値を表示
          parentLink.setAttribute('href', originalHref); // href属性を復元
          updateTagList();  // タグリストを更新する関数を呼び出す
      }
  })
  .catch((error) => {
      console.error('Error:', error);
      parentLink.setAttribute('href', originalHref); // エラー発生時にもhrefを復元
  });
}

function updateTagList() {
    fetch('/tags/list')  // タグリストの更新を行うエンドポイント
    .then(response => response.json())
    .then(tags => {
        const container = document.getElementById('tags-container');
        container.innerHTML = ''; // コンテナをクリア
        tags.forEach(tag => {
            container.innerHTML += `<div class="form-check form-check-inline mb-3">
                <input class="form-check-input" type="checkbox" name="tags[]" id="tag-${tag.id}" value="${tag.id}" />
                <label class="form-check-label" for="tag-${tag.id}">${tag.name}</label>
            </div>`;
        });
    })
    .catch(error => console.error('Error fetching tags:', error));
}