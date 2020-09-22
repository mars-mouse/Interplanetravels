window.addEventListener('resize', resizeBackground);

window.addEventListener('load', resizeBackground);

function resizeBackground() {
    let contentHTML = document.querySelector("#content");
    let contentRect = contentHTML.getBoundingClientRect();
    document.querySelector("#bg-moon").style.height = contentRect.height + "px";
}