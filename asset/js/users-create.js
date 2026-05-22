document.addEventListener('DOMContentLoaded', function () {
    const select             = document.getElementById('codeRole');
    const blockInvite        = document.getElementById('accessInvite');
    const blockNaturaliste   = document.getElementById('accessNaturaliste');
    const blockDates         = document.getElementById('accessDates');
    const label              = document.getElementById('accessDatesLabel');
    const INVITE             = select.dataset.roleInvite;
    const ADHERENT           = select.dataset.roleAdherent;
    const NATURALISTE        = select.dataset.roleNaturaliste;

    function toggle() {
        const role = select.value;
        blockInvite.style.display      = (role === INVITE)      ? '' : 'none';
        blockNaturaliste.style.display = (role === NATURALISTE) ? '' : 'none';
        blockDates.style.display       = (role === ADHERENT)    ? '' : 'none';
        if (role === ADHERENT) {
            label.innerHTML = 'Adhésion <span class="fw-normal text-white-50 ms-1">obligatoire</span>';
        }
    }

    select.addEventListener('change', toggle);
    toggle();
});
