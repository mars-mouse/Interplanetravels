/// savedPaymentLoading.js
// charge dans le formulaire les données d'un payment enregistré par l'utilisateur

//const { findIndex } = require("core-js/fn/array");
const { ajax } = require("jquery");

///
/// CONSTANTES
///

// Sélection du SavedPayment
const selectionHTML = document.querySelector("#savedPayments");
const selectionErrorHTML = document.querySelector("#savedPaymentsError");

// Formulaire
// const paymentFormHTML = document.querySelector("#payment_form");
const loadPaymentButtonHTML = document.querySelector("#loadPayment");
const cardTypeHTML = document.querySelector("#payment_form_cardType");
const cardNumberHTML = document.querySelector("#payment_form_cardNumber");
const cryptoHTML = document.querySelector("#payment_form_crypto");
const addressBillingHTML = document.querySelector("#payment_form_addressBilling");
const addressDeliveryHTML = document.querySelector("#payment_form_addressDelivery");
const fullNameHTML = document.querySelector("#payment_form_fullName");
const dateExpirationMonthHTML = document.querySelector("#payment_form_dateExpiration_month");
const dateExpirationYearHTML = document.querySelector("#payment_form_dateExpiration_year");

// Profile page (profile.html.twig)
const savedPaymentNameHTML = document.querySelector("#savedPaymentName");

let options = [];
// null si on est ailleurs
if (savedPaymentNameHTML !== null) {
    // création de la liste des noms des options du select    
    for (let i = 0; i < selectionHTML.options.length; i++) {
        if (selectionHTML.options[i].value != -1) {
            options[selectionHTML.options[i].value] = selectionHTML.options[i].innerHTML;
        }
    }
}
const optionsNames = options;

// Profile page buttons
const savePaymentButtonHTML = document.querySelector("#savePayment");
const resetFormButtonHTML = document.querySelector("#resetForm");
const editPaymentButtonHTML = document.querySelector("#editPayment");
const deletePaymentButtonHTML = document.querySelector("#deletePayment");


///
/// VARIABLES
///

let isRequestSent = false;
let savedPayments;

///
/// EVENT LISTENERS
///

selectionHTML.addEventListener('change', onSelectionChange);

if (resetFormButtonHTML !== null) {
    resetFormButtonHTML.addEventListener('click', resetForm);
}

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

    if (savedPayments === false) {
        // ce n'est pas un JSON, voir côté API
        console.log("Not a JSON");
        return;
    }

    if (savedPayments === []) {
        // il n'y a pas de paiement sauvegardé
        // console.log("Empty");
        return;
    }

    // on a reçu l'array avec les objets contenant les informations de paiement pour remplir le formulaire
    // on peut donc afficher le bouton qui permet de pré-remplir le formulaire
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

/**
 * Remplis le formulaire avec les données de paiement récupérées par la requête
 */
function loadPaymentDetails(eventHandler) {
    eventHandler.preventDefault();

    // on doit savoir quel paiement sauvegardé utiliser pour remplir le formulaire
    let paymentId = selectionHTML.value;

    if (paymentId == -1) {
        // "New" -> réinitialisation du formulaire
        resetForm();
        editForm();

        // Cacher le bouton Delete
        if (deletePaymentButtonHTML.classList.contains('d-block')) {
            deletePaymentButtonHTML.classList.replace("d-block", "d-none");
        }
        return;
    }

    let index = savedPayments.findIndex((element) => {
        // paymentId = string, element.id = number
        return element.id == paymentId;
    });

    if (index === -1) {
        // détails du paiment non trouvé : incohérence entre API et BookingController/ProfileController
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

    // Si on est dans profile.html.twig
    if (savedPaymentNameHTML !== null) {
        // Nom du SavedPayment récupéré dans les options
        // (testé plus haut : paymentId != -1)
        savedPaymentNameHTML.value = optionsNames[paymentId];

        // On affiche le formulaire, disabled, et les boutons Edit et Delete
        disableForm();
        editPaymentButtonHTML.classList.replace('d-none', 'd-block');
        editPaymentButtonHTML.addEventListener('click', editForm);
        deletePaymentButtonHTML.classList.replace('d-none', 'd-block');
    }
}

/**
 * Réinitialise le formulaire de paiement
 */
function resetForm() {
    cardTypeHTML.value = 'Visa';
    cardNumberHTML.value = '';
    cryptoHTML.value = '';
    addressBillingHTML.value = '';
    addressDeliveryHTML.value = '';
    fullNameHTML.value = '';
    dateExpirationMonthHTML.value = '';
    dateExpirationYearHTML.value = '';

    if (savedPaymentNameHTML !== null) {
        savedPaymentNameHTML.value = '';
    }

    return;
}


/**
 * Permet l'édition du formulaire en enlevant la propriété disabled des inputs
 */
function editForm() {
    cardTypeHTML.disabled = false;
    cardNumberHTML.disabled = false;
    cryptoHTML.disabled = false;
    addressBillingHTML.disabled = false;
    addressDeliveryHTML.disabled = false;
    fullNameHTML.disabled = false;
    dateExpirationMonthHTML.disabled = false;
    dateExpirationYearHTML.disabled = false;

    if (savedPaymentNameHTML !== null) {
        savedPaymentNameHTML.readOnly = false;

        // Afficher le bouton Save
        if (savePaymentButtonHTML.classList.contains('d-none')) {
            savePaymentButtonHTML.classList.replace("d-none", "d-block");
        }

        // Retirer le bouton Edit
        if (editPaymentButtonHTML.classList.contains('d-block')) {
            editPaymentButtonHTML.removeEventListener('click', editForm);
            editPaymentButtonHTML.classList.replace('d-block', 'd-none');
        }

        // Retirer le bouton Delete (pour éviter des confusions si on change le nom)
        if (deletePaymentButtonHTML.classList.contains('d-block')) {
            deletePaymentButtonHTML.classList.replace('d-block', 'd-none');
        }
    }

    return;
}

/**
 * Ajoute la propriété "disabled" à tout le formulaire pour en empêcher l'édition
 */
function disableForm() {
    cardTypeHTML.disabled = "disabled";
    cardNumberHTML.disabled = "disabled";
    cryptoHTML.disabled = "disabled";
    addressBillingHTML.disabled = "disabled";
    addressDeliveryHTML.disabled = "disabled";
    fullNameHTML.disabled = "disabled";
    dateExpirationMonthHTML.disabled = "disabled";
    dateExpirationYearHTML.disabled = "disabled";

    if (savedPaymentNameHTML !== null) {
        savedPaymentNameHTML.readOnly = "readonly";
    }

    // Cacher le bouton Save
    if (savePaymentButtonHTML.classList.contains('d-block')) {
        savePaymentButtonHTML.classList.replace("d-block", "d-none");
    }
}