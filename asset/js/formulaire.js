// id : nom, email, sujet, message, envoyer (bouton)

function is_correct(elem) {
    if (elem.getAttribute("type") === "email") {
        let regexMail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
        return regexMail.test(email.value);
    }
    return elem.value != "";
}

function invalider(elem, label) {
    let fondRouge = "rgb(255, 210, 210)";
    let border = "2px solid red";
    let txt = elem.getAttribute("id");
    txt = txt.charAt(0).toUpperCase() + txt.slice(1);
    elem.style.border = border;
    elem.style.backgroundColor = fondRouge;
    label.textContent = txt + " incorrect !";
    label.style.color = "red";
}

function reinitialiser(elem, label) {
    elem.style.border = "";
    elem.style.backgroundColor = "white";
    label.style.color = "white";
    let txt = elem.getAttribute("tag");
    //txt = txt.charAt(0).toUpperCase() + txt.slice(1);
    label.innerHTML = txt;
}

function afficher_res(listeForm, listeLabel) {
    let txt = "Contenu du formulaire :\n";
    for (let i=0; i<listeForm.length; i++) {
        let elem = listeForm[i];
        let label = listeLabel[i];
        txt += label.innerHTML + " : " + elem.value + "\n";
    }
    alert(txt);
}

function effacer(listeForm) {
    for (let i=0; i<listeForm.length; i++) {
        let elem = listeForm[i];
        elem.value = "";
    }
}

function envoyer() {

    let listeForm = document.querySelectorAll("form input, form textarea");
    let listeLabel = document.querySelectorAll("form label");

    let pass = true;
    let res = document.querySelector("#resultat");

    for (let i=0; i<listeForm.length; i++) {
        let elem = listeForm[i];
        let label = listeLabel[i];
        if (!is_correct(elem)) {
            invalider(elem, label);
            pass = false;
        }
        else {
            reinitialiser(elem, label);
        }
    }

    // Signal à l'utilisateur si les entrées sont correctes.
    // Sinon, affiche les données entrées, un message de confirmation
    // et vide le formulaire.
    if (!pass) {
        res.style.color = "red";
        res.innerHTML = "<strong> Données incorrectes !</strong>";
    }
    else {
        res.style.color = "white";
        res.textContent = "Message envoyé !";
        afficher_res(listeForm, listeLabel);
        effacer(listeForm);
    }
}

// Event listener pour le bouton "envoyer".
document.querySelector("#envoyer").addEventListener("click", envoyer);

