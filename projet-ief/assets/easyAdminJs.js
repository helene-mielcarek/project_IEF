// call functions once page is loaded
document.addEventListener("DOMContentLoaded", function() {
    makeTableRowClickable();
});

function makeTableRowClickable() {
    let elements = document.getElementsByClassName('js-row-action');

    for (let i = 0; i < elements.length; i++) {
        let td  = elements[i];
        let tr  = td.parentNode;
        let url = tr.getAttribute('href');
        let elmtsClick = tr.querySelectorAll('.js-row-click')
        console.log(elmtsClick)
        for (let elm of elmtsClick) {
            elm.addEventListener("click", function (e) {
                location.href = url;
            });
        }
    }

    //click sur les event dans user details
    // let eventElements = document.getElementsByClassName('js-event-action');

    // for (let i = 0; i < eventElements.length; i++) {
    //     let div  = eventElements[i];
    //     // let dd  = div.parentNode;
    //     let url = div.getAttribute('href');
    //     console.log(div)

    //     let elmtsClick = div.querySelectorAll('li')
    //     // let elmtsClick = li.querySelectorAll('.js-row-click')
    //     console.log(elmtsClick)
    //     for (let elm of elmtsClick) {
    //         elm.addEventListener("click", function (e) {
    //             location.href = url;
    //         });
    //     }
    // }
}


