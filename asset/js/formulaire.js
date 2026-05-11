function verifierChamps() {
    let estValide = true;
    const inputs = document.querySelectorAll('.mandatory');

    inputs.forEach(input => {
        if (input.value.trim() === "") {
            input.classList.add('border', 'border-danger');
            estValide = false;
        } else {
            input.classList.remove('border', 'border-danger');
        }
    });
    return estValide;
}


const form = document.getElementById('formulaire');

form.addEventListener('submit', function (event) {
    // On appelle ta fonction de vérification
    const formulaireValide = verifierChamps();

    if (!formulaireValide) {
        // Si les champs ne sont pas tous remplis, on bloque l'envoi
        event.preventDefault();
        alert("Veuillez remplir tous les champs obligatoires.");
    }
    // Si c'est valide, on ne fait rien : le navigateur continue l'envoi normalement
});

