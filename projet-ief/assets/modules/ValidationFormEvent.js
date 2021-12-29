/**
 * @property {HTMLElement} eventTitle
 * @property {HTMLElement} eventDateDate
 * @property {HTMLElement} eventDateTime
 * @property {HTMLElement} eventLieu
 * @property {HTMLElement} eventDescription
 * @property {HTMLElement} eventCategory
//  * @property {HTMLElement} eventParticipants
 * @property {HTMLElement} eventFamParticipants
 * @property {HTMLElement} eventLimite
 * @property {HTMLElement} eventComplet
 */

export default class ValidationFormEvent{
    constructor(element){
        if(element == null) {
            return
        }
        this.eventTitle = document.getElementById('event_title')
        this.eventDateDate = document.getElementById('event_date_date')
        this.eventDateTime = document.getElementById('event_date_time')
        this.eventLieu = document.getElementById('event_lieu')
        this.eventDescription = document.getElementById('event_description')
        this.eventCategory = document.getElementById('event_category')
        //si utilisation nb enfants this.eventParticipants = document.getElementById('event_participants')
        //sinon:
        this.eventFamParticipants = document.getElementById('event_FamParticipants')
        this.eventLimite = document.getElementById('event_limite')
        this.eventComplet = document.getElementById('event_complet')
        this.btnSubmit = document.getElementById('submit-add-event')
        this.bindEvent()
    }

    /**
     * Ajouter les différents comportements aux différents éléments
     */
    bindEvent() {
        this.btnSubmit.addEventListener("click", this.everyVerif.bind(this))
        this.eventDescription.addEventListener("focusout", this.verfiDescription.bind(this))
        this.eventLieu.addEventListener("focusout", this.verfiLieu.bind(this))
        this.eventTitle.addEventListener("focusout", this.verifTitle.bind(this))
    }

    everyVerif(){
        let msgs = document.querySelectorAll('.msg-error')
        console.log(msgs)
        for (let msg of msgs) {

            msg.remove()
        }
        this.verifCategory()
        this.verfiDescription()
        this.verfiLieu()
        this.verifTitle()
    }

    invalidOptions(message, element){
        let newDiv = document.createElement("div")
        let newContent = document.createTextNode(message)
        newDiv.appendChild(newContent);
        newDiv.classList.add('msg-error', 'fs-6', 'text-danger')
        element.parentElement.appendChild(newDiv)
        element.classList.add('is-invalid')
    }
    verifCategory() {
        let selects = this.eventCategory.querySelectorAll(".form-check-input")

    }

    verfiDescription(){
        this.eventDescription.classList.remove('is-invalid')

        if (this.eventDescription.value.length < 5){
            this.invalidOptions("Essaies de nous le décrire en quelques mots", this.eventDescription)
        }
        else {
            let msg = this.eventDescription.parentElement.querySelector('.msg-error')
            if (msg != null){
                msg.remove()
            }
            this.eventDescription.classList.add('is-valid')
        }
    }

    verfiLieu() {
        this.eventLieu.classList.remove('is-invalid')
        if (this.eventLieu.value.length < 1){
            this.invalidOptions("On a besoin d'un lieu", this.eventLieu)
        }
        else {
            let msg = this.eventLieu.parentElement.querySelector('.msg-error')
            if (msg != null){
                msg.remove()
            }
            this.eventLieu.classList.add('is-valid')
        }

    }
    verifTitle() {
        this.eventTitle.classList.remove('is-invalid')

        if (this.eventTitle.value.length < 5){
            this.invalidOptions("On a besoin d'un titre d'au moins 5 caractères", this.eventTitle)
        }
        else {
            let msg = this.eventTitle.parentElement.querySelector('.msg-error')
            if (msg != null){
                msg.remove()
            }
            this.eventTitle.classList.add('is-valid')
        }
    }
}