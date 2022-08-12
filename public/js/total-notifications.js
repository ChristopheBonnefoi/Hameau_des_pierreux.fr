// Fonction permettant de récupérer le total de toutes les notifications en attente
function numberOf(){
    let trueComment = document.querySelectorAll('.notification-result');
    return trueComment.length;
}

// Affichage du total de toutes les notifications en attente
let selectContainer = document.querySelector(`.bell-notif`);

selectContainer.textContent = numberOf();
