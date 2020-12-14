initPopover();

/*
* FUNCTIONS
*/

function wobble(el) {
    el.classList.add('wobble');
    setTimeout(_ => el.classList.remove('wobble'), 800);
}

function initPopover() {
    let popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-toggle="popover"]')
    );

    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
}
