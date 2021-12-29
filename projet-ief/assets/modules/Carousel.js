export default class Filer{

    
    constructor(element){
        if(element === null){
            return
        }
        let items1 = carouselFiveLastEvent.querySelectorAll('.carousel .carousel-item')
        console.log(items1)
        items1.forEach((el) => {
            const minPerSlide = 3
            let next = el.nextElementSibling
            for (var i=1; i<minPerSlide; i++) {
                if (!next) {
                    // wrap carousel by using first child
                    next = items1[0]
                }
                let cloneChild = next.cloneNode(true)
                el.appendChild(cloneChild.children[0])
                next = next.nextElementSibling
            }
        })
    }
    
}

