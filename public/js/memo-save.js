document.addEventListener('DOMContentLoaded', function() {
    let inactivityTimer;
    const timer = 90 * 60 * 1000;

    function autoSubmitForm() {
        const creatingText = document.querySelector('.creating-text');
        if (creatingText && creatingText.value !== "") {
            const form = document.querySelector('.create-form');
            if (form) {
                form.submit();
            }
        }

        const editingText = document.querySelector('.editing-text');
        if (editingText) {
            const form = document.querySelector('.edit-form');
            if (form) {
                form.submit();
            }
        }
    }

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(autoSubmitForm, timer);
    }

    document.addEventListener('mousemove', resetInactivityTimer);
    document.addEventListener('keydown', resetInactivityTimer);
    document.addEventListener('scroll', resetInactivityTimer);
    document.addEventListener('click', resetInactivityTimer);

    resetInactivityTimer();
});
