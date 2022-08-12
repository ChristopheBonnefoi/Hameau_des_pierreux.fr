window.onload = () => {

    // Gestion des liens "supprimer"
    let links = document.querySelectorAll("[data-delete]")

    // Boucle sur links
    for (link of links) {

        // On ecoute le clic
        link.addEventListener("click", function (e) {

            //On empeche la navigation
            e.preventDefault()

            //Demande de confirmation
            if (confirm("Voulez-vous supprimer cette image ?")) {
                // On envoie une requête Ajax vers le href du lien avec la méthode DELETE avec une promesse
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        'X-Requested-With': "XMHLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    //On recupere la réponse en JSON
                    response => response.json()
                ).then(data => {
                    if (data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e))
            }
        })

    }
}