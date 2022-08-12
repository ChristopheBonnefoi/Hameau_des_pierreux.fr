
// Écouteur d'évènement bouton switch

// chargement de la fenêtre, évènement load se déclenche à la fin du processus de chargement du document.
window.onload = () => {
    // sélection du boolean à activer
    let activer = document.querySelectorAll("[type=checkbox]")
    // boucle pour chaque switch
    for(let bouton of activer){
        //écouteur d'évènement à chaque click
        bouton.addEventListener("click", function(){
            // Interaction avec le serveur. Récupère les données à partir d'une URL sans avoir à rafraichir la page
            let xmlhttp = new XMLHttpRequest;

            // vérification du status de la requête
            xmlhttp.onreadystatechange = () => {
                const xhr = new XMLHttpRequest(),
                    method = "GET",
                    url = `/comment/activer/${this.dataset.id}`;

                xhr.open(method, url, true);
                xhr.onreadystatechange = function () {
                    if(xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText);
                    }
                };
                xhr.send();
            }

            // ouverture de l'URL demandée
            xmlhttp.open('get', `/comment/activer/${this.dataset.id}`);
            // envoie la requête au serveur
            xmlhttp.send();
        })
    }

    // Écouteur d'évènement bouton de suppression des notifications
    // sélection du boolean à supprimer
    let supprimer = document.querySelectorAll('[type=radio]')
    // boucle pour chaque bouton supprimer
    for(let bouton of supprimer){
        //écouteur d'évènement à chaque click
        bouton.addEventListener("click", function(){
            // Interaction avec le serveur. Récupère les données à partir d'une URL sans avoir à rafraichir la page
            let xmlhttp = new XMLHttpRequest;

            // // vérification du status de la requête
            xmlhttp.onreadystatechange = () => {
                const xhr = new XMLHttpRequest(),
                    method = "GET",
                    url = `/comment/supprimer/${this.dataset.id}`;

                xhr.open(method, url, true);
                xhr.onreadystatechange = function () {
                    if(xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText);
                    }
                };
                xhr.send();
            }

            // ouverture de l'URL demandée
            xmlhttp.open('get', `/comment/supprimer/${this.dataset.id}`)
            // envoie la requête au serveur.
            xmlhttp.send()
        })
    }
}

// Récupération du bouton "Supprimer es notifications"
function notify(){
    let existence = document.querySelectorAll('#child-notification');
    return existence.length;
}

//Condition qui permet de faire disparaitre le bouton lorsqu'il n'y a pas de notification
if( notify() === 0){
    let buttonNotify = document.querySelector('#btn-notify');
    buttonNotify.classList.add('d-none');
}

