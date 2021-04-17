const baliseList = document.getElementById('voitures');

function actualiserVoitures() {
  baliseList.innerHTML = '';

  function afficherVoitures(voiture) {
    let listItem = document.createElement('li');
    listItem.textContent = voiture.marque+' '+voiture.couleur+' '+voiture.prix+' '+voiture.annee;
    baliseList.appendChild(listItem);
  }

  listVoitures.forEach(afficherVoitures);
};

actualiserVoitures();
