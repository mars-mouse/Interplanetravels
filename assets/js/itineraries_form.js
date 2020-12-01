/// itineraries_form.js
// Gère l'ajout et le retrait d'entrées du formulaire ItineraryFormType
// Permet d'ajouter des destinations lors de la création d'un Travel

///
/// BALISES HTML
///

const addItemButtonHTML = document.querySelector("#addItem");
const itinerariesFormHTML = document.querySelector("#itineraries");
const itineraryTagsHTML = document.querySelectorAll(".itinerary");

///
/// EVENT LISTENERS
///

addItemButtonHTML.addEventListener('click', addItem);

///
/// INITIALISATION
///

// count the current itinerary forms we have (e.g. 2), use that as the new
// index when inserting a new item (e.g. 2)
itinerariesFormHTML.dataset.index = itineraryTagsHTML.length;

// add a delete link to all of the existing itinerary elements
itineraryTagsHTML.forEach((item) => {
    addItineraryDeleteLink(item);
});

///
/// FONCTIONS
///

/**
 * Ajoute un nouvel élément itinerary au formulaire.
 */
function addItem() {
    // Get the data-prototype
    let prototype = itinerariesFormHTML.dataset.prototype;

    // get the new index
    let index = itinerariesFormHTML.dataset.index;
    if (index === '') {
        index = '0';
    }

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    itinerariesFormHTML.dataset.index = parseInt(index) + 1;


    // Add the new form at the end of the list
    let formHTML = document.createElement('div');
    formHTML.innerHTML = `<div class="itinerary col-12 form-row">${newForm}</div>`;
    let newItinerary = itinerariesFormHTML.appendChild(formHTML.firstChild);

    // Ajouter le bouton pour retirer l'élément
    addItineraryDeleteLink(newItinerary);
}

/**
 * Ajoute un bouton pour retirer l'élément itinerary donné en paramètre
 * @param itineraryElement 
 */
function addItineraryDeleteLink(itineraryElement) {
    // on ajoute le bouton dans l'élément itinerary
    let removeFormButtonDiv = document.createElement('div');
    removeFormButtonDiv.innerHTML = '<button type="button" class="bouton col-2">Delete this destination</button>';
    let removeFormButtonHTML = itineraryElement.appendChild(removeFormButtonDiv.firstChild);

    // on ajoute la fonction du bouton pour retirer tout l'élément itinerary
    removeFormButtonHTML.addEventListener('click', removeButton);
}

/**
 * Retire tout l'élément itinerary quand on clique le bouton
 * @param eventHandler 
 */
function removeButton(eventHandler) {
    eventHandler.preventDefault();
    // eventHandler.currentTarget = bouton, et on veut retirer tout l'élément, donc le parent
    eventHandler.currentTarget.parentNode.remove();
}