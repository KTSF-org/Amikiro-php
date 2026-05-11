document.addEventListener('DOMContentLoaded', function () {
    const pwd      = document.getElementById('password');
    const confirm  = document.getElementById('passwordConfirm');
    const mismatch = document.getElementById('passwordMismatch');
    const submit   = document.getElementById('submitIdentity');

    if (!pwd || !confirm) return;

    function checkPasswords() {
        const differs = pwd.value.length > 0 && pwd.value !== confirm.value;
        mismatch.style.display = differs ? '' : 'none';
        submit.disabled = differs;
    }

    pwd.addEventListener('input', checkPasswords);
    confirm.addEventListener('input', checkPasswords);
});
