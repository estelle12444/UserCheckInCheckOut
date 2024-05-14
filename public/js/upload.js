/*
    ####
    Etape 1:
        Recupération des info du formulaire
        Envoi des info au niveau du server
        Recuperation de la reponse du server
            En cas d'erreur , il faut recuperer l'ensemble des info(erreur) et les affichés
            En cas de succès
    Etape 2:
        Recuperation de l'url signé
        Puis poste les info matricule et le chemin de l'image qui y est associé
        Pour Recuperer la reponse
            En cas d'erreur , il faut recuperer l'ensemble des info(erreur) et les affichés
            En cas de succès, Faire une redirection sur la liste avec un message à l'appui

*/

async function errorHandler(error){
    console.error("Une erreur est survenue :", error);
    // const div = document.getElementById('#error-output');

    // if(!div){
    //     const divEl = document.createElement('#error-output.alert.alert-danger');
    // }

    // console.log(divEl);
    // docu
    // document.insertBefore(,document.getElementById('submitBtn'))

    const errorDiv = document.getElementById('error-output');
    if (!errorDiv) {
        const errorDivEl = document.createElement('div');
        errorDivEl.id = 'error-output';
        errorDivEl.classList.add('alert', 'alert-danger');
        document.body.insertBefore(errorDivEl, document.getElementById('submitBtn'));
    }

    errorDiv.innerHTML = `<p><strong>Erreur :</strong> ${error.message}</p>`;
}

document.getElementById("submitBtn").addEventListener("click", function(e){
    //Etape 1
    e.preventDefault();

    const form = document.getElementsByTagName("form")[0];
    const formData = new FormData(form);
    const action = form.getAttribute("data-action");

    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }

    fetch(action, {
        body: formData,
        method: "post",
        headers
    }).then( async (response) => {
        //Etape 2
        const json = await response.json();
        try {
            const resp =  await fetch(json['url'] /* recuperer depuis response */,{
                method: "post",
                headers
            });

            const refreshJson =  await resp.json();
            window.location.href = refreshJson['url'];
        } catch (error) {
            //affichage des erreurs
            errorHandler(error);
        }
    }).catch(errorHandler);
});
