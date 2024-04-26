function enableEdit(element) {
    let tagNameElement = element.previousElementSibling.getElementsByTagName('span')[0];
    editTagName(tagNameElement);
}

function editTagName(tagNameElement) {
    let currentText = tagNameElement.innerText;
    let parentLink = tagNameElement.parentNode;
    let originalHref = parentLink.getAttribute('href');

    parentLink.removeAttribute('href');

    tagNameElement.innerHTML = `<input type="text" class="form-control" value="${currentText}">`;
    let inputField = tagNameElement.firstChild;
    inputField.focus();

    inputField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            saveTagName(this, tagNameElement.dataset.id, parentLink, originalHref);
            inputField.blur();
        }
    });

    inputField.addEventListener('blur', function() {
        if (inputField.value !== currentText) {
            return;  // Enterが押された場合、更新処理が行われるので何もしない
        }
        tagNameElement.innerHTML = currentText;
        parentLink.setAttribute('href', originalHref);
    });
}
  
function saveTagName(inputElement, tagId, parentLink, originalHref) {
    let newValue = inputElement.value;
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
            inputElement.value = newValue;
            let parentSpan = inputElement.closest('.tag-container').querySelector('.tag-name');
            parentSpan.innerText = newValue;
            parentLink.setAttribute('href', originalHref);

            let id = getMemoId();
            updateTagsList(id);
        } else {
            console.error('Update failed:', data.error);
            alert('更新に失敗しました。');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        parentLink.setAttribute('href', originalHref);
        alert('通信エラーが発生しました。');
    });
}

function getMemoId() {
    const editingTextArea = document.querySelector('.editing-text');
    if (editingTextArea) {
        const formElement = editingTextArea.closest('form');
        if (formElement) {
            const idInput = formElement.querySelector('input[name="memo_id"]');
            if (idInput) {
                return idInput.value;
            }
        }
    }
    return null;
}

function updateTagsList(id) {
    fetch(`/tags/list/${id}`)
    .then(response => response.json())
    .then(data => {
        const { tags, include_tags } = data;
        const container = document.getElementById('tags-container');
        container.innerHTML = '';
        tags.forEach(tag => {
            const checkedAttribute = id && include_tags.includes(tag.id) ? 'checked' : '';
            container.innerHTML += `
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="tag-${tag.id}" value="${tag.id}" ${checkedAttribute}>
                    <label class="form-check-label" for="tag-${tag.id}">${tag.name}</label>
                </div>
            `;
        });
    })
    .catch(error => console.error('Error fetching tags:', error));
}
