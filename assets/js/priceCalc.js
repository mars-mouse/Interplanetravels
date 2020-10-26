/// priceCalc.js
// Calcul du prix total des billets en fonction du nombre de places en temps réel

///
/// BALISES HTML
///

const numberPlacesHTML = document.querySelector("#numberPlaces input");
const unitPriceHTML = document.querySelector("#unitPrice");
const promotionHTML = document.querySelector("#promotionValue");
const totalPriceHTML = document.querySelector("#totalPrice");

///
/// EVENT LISTENERS
///

numberPlacesHTML.addEventListener('change', priceCalc);

///
/// FONCTIONS
///

function priceCalc() {
    // Récupération des valeurs des champs : nombre de place, prix unitaire, promotion (s'il y en a une)
    let totalPrice = numberPlacesHTML.value * unitPriceHTML.dataset.value;
    if (promotionHTML !== null) {
        totalPrice *= (100 - promotionHTML.dataset.value) / 100;
    }

    // Mise à jour du champ du prix total avec mise en forme en dollars
    totalPriceHTML.value = totalPrice.toLocaleString("en-US", {
        style: "currency",
        currency: "USD"
    });
}