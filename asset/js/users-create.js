document.addEventListener('DOMContentLoaded', function () {
    const select      = document.getElementById('codeRole');
    const blockInvite = document.getElementById('accessInvite');
    const blockDates  = document.getElementById('accessDates');
    const label       = document.getElementById('accessDatesLabel');
    const INVITE      = select.dataset.roleInvite;
    const ADHERENT    = select.dataset.roleAdherent;

    function toggle() {
        const role = select.value;
        blockInvite.style.display = (role === INVITE) ? '' : 'none';
        blockDates.style.display  = (role !== INVITE) ? '' : 'none';
        if (role === ADHERENT) {
            label.innerHTML = 'Adhésion <span class="fw-normal text-white-50 ms-1">obligatoire</span>';
        } else {
            label.innerHTML = 'Adhésion <span class="fw-normal text-white-50 ms-1">informatif</span>';
        }
    }

    select.addEventListener('change', toggle);
    toggle();
});
