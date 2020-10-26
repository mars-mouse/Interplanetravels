/// savedPaymentLoading.js
// charge dans le formulaire les données d'un payment enregistré par l'utilisateur

//const { findIndex } = require("core-js/fn/array");
const { ajax } = require("jquery");

///
/// CONSTANTES
///

const selectionHTML = document.querySelector("#savedPayments");
const selectionErrorHTML = document.querySelector("#savedPaymentsError");
const loadPaymentButtonHTML = document.querySelector("#loadPayment");
const cardTypeHTML = document.querySelector("#payment_form_cardType");
const cardNumberHTML = document.querySelector("#payment_form_cardNumber");
const cryptoHTML = document.querySelector("#payment_form_crypto");
const addressBillingHTML = document.querySelector("#payment_form_addressBilling");
const addressDeliveryHTML = document.querySelector("#payment_form_addressDelivery");
const fullNameHTML = document.querySelector("#payment_form_fullName");
const dateExpirationMonthHTML = document.querySelector("#payment_form_dateExpiration_month");
const dateExpirationYearHTML = document.querySelector("#payment_form_dateExpiration_year");

///
/// VARIABLES
///

let isRequestSent = false;
let savedPayments;

///
/// EVENT LISTENERS
///

selectionHTML.addEventListener('change', onSelectionChange);

///
/// FONCTIONS
///

/**
 * Lance la requête API à la première utilisation du menu déroulant
 */
function onSelectionChange() {
    if (!isRequestSent) {
        ajax('/api/saved_payments')
            .then(onRequestSuccess)
            .catch(onRequestFailure);
        isRequestSent = true;
    }
}

/**
 * Transforme du JSON en array ou renvoie false si ce n'est pas du JSON
 * @param {string} text 
 */
function JSONparse(text) {
    try {
        return JSON.parse(text);
    }
    catch (e) {
        alert(e);
        console.log(text);
        return false;
    }
}

/**
 * Promesse en cas de succès de la requête
 */
function onRequestSuccess(receivedContent) {
    savedPayments = JSONparse(receivedContent);
    console.log(savedPayments);

    if (savedPayments === false) {
        // ce n'est pas un JSON, voir côté API
        console.log("Not a JSON");
        return;
    }

    if (savedPayments === []) {
        // il n'y a pas de paiement sauvegardé
        console.log("Empty");
        return;
    }

    // on a reçu l'array avec les objets contenant les informations de paiement pour remplir le formulaire
    // on peut donc afficher le bouton qui permet de pré-remplir le formulaire
    console.log(loadPaymentButtonHTML.classList);
    loadPaymentButtonHTML.classList.replace("d-none", "d-block");
    loadPaymentButtonHTML.addEventListener('click', loadPaymentDetails);
}

/**
 * Gère l'échec de la requête
 */
function onRequestFailure(error) {
    selectionErrorHTML.innerHTML = `<p>${error}</p>`;
    isRequestSent = false; // on autorise un nouvel essai de requête
}

function loadPaymentDetails(eventHandler) {
    eventHandler.preventDefault();

    // on doit savoir quel paiement sauvegardé utiliser pour remplir le formulaire
    let paymentId = selectionHTML.value;

    if (paymentId == -1) {
        // "New" -> réinitialisation du formulaire
        cardTypeHTML.value = 'Visa';
        cardNumberHTML.value = '';
        cryptoHTML.value = '';
        addressBillingHTML.value = '';
        addressDeliveryHTML.value = '';
        fullNameHTML.value = '';
        dateExpirationMonthHTML.value = '';
        dateExpirationYearHTML.value = '';

        return;
    }

    let index = savedPayments.findIndex((element) => {
        // paymentId = string, element.id = number
        return element.id == paymentId;
    });

    if (index === -1) {
        // détails du paiment non trouvé : incohérence entre API et BookingController
        return;
    }

    // remplissage du formulaire
    cardTypeHTML.value = savedPayments[index].cardType;
    cardNumberHTML.value = savedPayments[index].cardNumber;
    cryptoHTML.value = savedPayments[index].crypto;
    addressBillingHTML.value = savedPayments[index].addressBilling;
    addressDeliveryHTML.value = savedPayments[index].addressDelivery;
    fullNameHTML.value = savedPayments[index].fullName;

    // les dates nécessitent une traduction
    let expirationDate = new Date(savedPayments[index].dateExpiration.date);
    dateExpirationMonthHTML.value = expirationDate.getMonth() + 1; // getMonth() renvoie un mois de 0 à 11
    dateExpirationYearHTML.value = expirationDate.getFullYear();
}