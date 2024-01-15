// Allow nameless form inputs to control their parameter name with a data attribute
document.body.addEventListener('htmx:configRequest', function(event) {
    if (event.target.dataset.name) {
        event.detail.parameters[event.target.dataset.name] = event.target.value
    }
});

// Highlight tabs immediately on click instead of waiting for Sprig update
document.getElementById('fathom-sidebar').addEventListener('click', function (event) {
    if (event.target.classList.contains('fathom-tab') && !event.target.hasAttribute('aria-selected')) {
        event.target.parentElement.querySelector('[aria-selected]').removeAttribute('aria-selected')
        event.target.setAttribute('aria-selected', 'true')
    }
})
