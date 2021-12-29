/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
require('@fortawesome/fontawesome-free/scss/fontawesome.scss');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

// start the Stimulus application
// import './bootstrap';
import 'bootstrap';
import 'bootbox';
// import 'bootstrap-icons';


import Carousel from './modules/Carousel'
new Carousel(document.getElementById('carouselFiveLastEvent'))

//import Filter pour dynamisation filter/tri
import Filter from './modules/Filter'
new Filter(document.querySelector('.js-filter'))

import FormAddEvent from './modules/FormAddEvent'
new FormAddEvent(document.getElementById('form-add-event'))

import ValidateFormEvent from'./modules/ValidationFormEvent'
new ValidateFormEvent(document.getElementById('form-add-event'))

import ValidationFormEventParticipant from'./modules/ValidationFormEventParticipant'
new ValidationFormEventParticipant(document.getElementById('form-add-event-participant'))

let carouselsFive = document.querySelectorAll("#carousel5NextOrLast")
// console.log(carouselsFive)
for (let carouselFiveEvent of carouselsFive)
{
    let items2 = carouselFiveEvent.querySelectorAll('.col .carousel .carousel-item')
    console.log(items2)
    console.log(items2.length)
    if (items2.length >= 3){
        console.log("sup ou = 3")
        items2.forEach((el) => {
            const minPerSlide = 3
            let next = el.nextElementSibling
            for (var i=1; i<minPerSlide; i++) {
                    if (!next) {
                            // wrap carousel by using first child
                            next = items2[0]
                        }
                        let cloneChild = next.cloneNode(true)
                        el.appendChild(cloneChild.children[0])
                        next = next.nextElementSibling
                    }
                })
            } else {
                console.log("inf 3")
            }
}