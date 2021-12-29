/**
 * @property {HTMLElement} btnSubmit
 * @property {HTMLElement} btnAddPart
 */

 export default class ValidationFormEventParticipant{
    constructor(element){
        if(element == null) {
            return
        }
        this.eventNbParticipant = document.getElementById('event_nbParticipants')
        this.btnAddPart = document.querySelector('.addParticipant')
        // console.log(this.eventNbParticipant)
        this.bindEvent()
    }

    /**
     * Ajouter les différents comportements aux différents éléments
     */
    bindEvent() {
        this.eventNbParticipant.addEventListener("focusout", this.verifParticipant.bind(this))
        this.btnAddPart.addEventListener("click", this.verifParticipant.bind(this))
        // console.log(this.btnAddPart)
    }

    verifParticipant() {
        this.eventNbParticipant.classList.remove('is-invalid')
        console.log(this.eventNbParticipant.value)

        if (this.eventNbParticipant.value == 0 || this.eventNbParticipant.value == null){
            
            this.invalidOptions("On a besoin de savoir combien vous serez", this.eventNbParticipant)
        } else {
            let msg = this.eventNbParticipant.parentElement.querySelector('.msg-error')
            if (msg != null){
                msg.remove()
            }
            this.eventNbParticipant.classList.add('is-valid')
        }
    }

    invalidOptions(message, element){
        let newDiv = document.createElement("div")
        let newContent = document.createTextNode(message)
        newDiv.appendChild(newContent);
        newDiv.classList.add('msg-error', 'fs-6', 'text-danger')
        element.parentElement.appendChild(newDiv)
        element.classList.add('is-invalid')
    }

}
