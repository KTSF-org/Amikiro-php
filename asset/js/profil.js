document.addEventListener('DOMContentLoaded', function () {
    const pwd      = document.getElementById('new_password');
    const confirm  = document.getElementById('confirm_password');
    const mismatch = document.getElementById('pwdMismatch');
    const submit   = document.getElementById('submitPwd');

    function checkPwd() {
        const differs = pwd.value.length > 0 && pwd.value !== confirm.value;
        mismatch.style.display = differs ? '' : 'none';
        submit.disabled = differs;
    }

    pwd.addEventListener('input', checkPwd);
    confirm.addEventListener('input', checkPwd);
});
