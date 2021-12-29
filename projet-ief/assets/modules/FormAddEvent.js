/**
 * @property {HTMLElement} formDateDate
 * @property {HTMLElement} selectsFormDateDate
 * @property {HTMLElement} formDateTime
 * @property {HTMLElement} inputFormDate
 * @property {HTMLElement} btnAddParticipants
 * @property {HTMLElement} myModal
 * @property {HTMLElement} myInput
//  * @property {HTMLElement} eventParticipants
 * @property {HTMLElement} eventFamParticipants
 */
export default class FormAddEvent {
     
    constructor(element){
        if(element === null){
            return
        }
        this.formDateDate = document.querySelector('#event_date_date')
        this.selectsFormDateDate = this.formDateDate.querySelectorAll('.form-select')
        this.formDateTime = document.querySelector('#event_date_time')
        this.inputFormDate = this.formDateTime.querySelector('.input-group')
        this.btnAddParticipants = document.getElementById('btn-add-participants')
        this.myModal = document.getElementById('select-participant')
        this.myInput = document.getElementById('div-focus')
        //si utilisation nb enfants this.eventparticipants = document.querySelector('#event_participants')
        //sinon:
        this.eventFamParticipants = document.getElementById('event_famParticipants')

        this.cssForm()

        this.bindEvents()
    }

    bindEvents(){
        // this.myModal.addEventListener('shown.bs.modal', function () {
        //     this.myInput.focus()
        // })
        this.myModal.addEventListener('shown.bs.modal', this.cssSelectParticipants.bind(this))
        
    }

    cssForm(){
        for (let select of this.selectsFormDateDate)
        {
            select.classList.add("form-select-sm")
        }
        this.inputFormDate.classList.add("input-group-sm")
    }

    cssSelectParticipants(){
        this.addRow()
        //si utilisation nb enfants let divsParticipants = this.eventparticipants.querySelectorAll('.form-check')
        //let raw = document.querySelector('#event_participants .raw-container-select-participants')

        //sinon:
        let divsParticipants = this.eventFamParticipants.querySelectorAll('.form-check')
        let raw = document.querySelector('#event_famParticipants .raw-container-select-participants')


        // nbColumns = Math.ceil(divsParticipants.length)
        let i = 0
        for (let div of divsParticipants){
            if (i == 0 || (i%15 == 0)){
                this.addColumn('column-'+i)
            }
            let lastcolumn = raw.lastElementChild
            lastcolumn.appendChild(div)
            i++
        }
    }

    addRow() {
        let newRaw = document.createElement("div");
        newRaw.classList.add('raw', 'raw-container-select-participants', 'd-sm-flex')
        //si utilisation nb enfants this.eventparticipants.insertAdjacentElement('afterbegin', newRaw)
        //sinon:
        this.eventFamParticipants.insertAdjacentElement('afterbegin', newRaw)
    }

    addColumn (className) {
        //si utilisation nb enfants let raw = document.querySelector('#event_participants .raw-container-select-participants')
        //sinon:
        let raw = document.querySelector('#event_famParticipants .raw-container-select-participants')

        let newDiv = document.createElement("div");
        newDiv.classList.add('col-6', className)

        raw.appendChild(newDiv)
    }
}