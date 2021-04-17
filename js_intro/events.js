const boutonAffichage = document.getElementById('boutonAffichage');

boutonAffichage.addEventListener('click', (event) => {
  const listVoitures = document.getElementById('voitures');
  if (boutonAffichage.textContent == 'Afficher voitures') {
    boutonAffichage.textContent = 'Masquer voitures';
  } else {
    boutonAffichage.textContent = 'Afficher voitures';
  }
  // listVoitures.classList.add('affiche'); // ajout d'une classe
  // listVoitures.classList.remove('affiche'); // suppresion d'une classe
   // classList.toggle() sert a ajouter ou retirer une classe soit sa condition
  listVoitures.classList.toggle('affiche');
});


const voituresFormulaire = document.getElementById('voituresFormulaire'); // form
voituresFormulaire.addEventListener('submit', (event) => {
  event.preventDefault();
  const voitureInsert = {
    marque: voituresFormulaire.voiture.value,
    prix: voituresFormulaire.voiture_prix.value,
    couleur: voituresFormulaire.voiture_couleur.value,
    annee: voituresFormulaire.voiture_annee.value,
  }
  listVoitures.push(voitureInsert);
  actualiserVoitures();
});
