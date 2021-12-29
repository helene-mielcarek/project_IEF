import {Flipper, spring} from 'flip-toolkit'

/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLElement} sorting
 * @property {HTMLFormElement} form
 * @property {number} page
 * @property {boolean} moreNav
 */
export default class Filter{

    /**
     * @param {HTMLElement|null} element 
     */
    constructor(element){
        if (element === null){
            return
        }
        this.pagination = element.querySelector('.js-filter-pagination')
        this.content = element.querySelector('.js-filter-content')
        this.sorting = element.querySelector('.js-filter-sorting')
        this.form = element.querySelector('.js-filter-form')
        this.page = parseInt(new URLSearchParams(window.location.search).get('page') || 1)
        this.moreNav = this.page === 1
        this.elementsCard = document.getElementsByTagName('article')
        this.elementBrother = document.querySelector('.js-browse-event')
        this.bindEvents()
    }

    /**
     * Ajoute les comportement aux différents éléments
     */
    bindEvents(){
        const aClickListener = e => {
            if(e.target.tagName === 'A'){
                e.preventDefault()
                this.loadUrl(e.target.getAttribute('href'))
            } 
        }

        this.sorting.addEventListener('click', e => {
            aClickListener(e)
            this.page = 1
        })
        if (this.moreNav) {
            this.pagination.innerHTML = '<div class="d-grid gap-2 col-4 mt-2 mx-auto"><button class="btn btn-primary btn-plus">Voir plus</button></div>'
            // const divParent = this.pagination
            // console.log(divParent)

            this.pagination.classList.add('d-grip')
            this.pagination.querySelector('button').addEventListener('click', this.loadMore.bind(this))
        } else {

        }
        this.pagination.addEventListener('click', aClickListener)
        this.form.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })
    }

    async loadMore() {
        const button = this.pagination.querySelector('button')
        button.setAttribute('disabled', 'disabled')
        this.page++
        const url = new URL(window.location.href)
        const params = new URLSearchParams(url.search)
        params.set('page', this.page)
        await this.loadUrl(url.pathname + '?' + params.toString(), true)
        button.removeAttribute('disabled')
    }

    async loadForm(){
        this.page = 1
        const data = new FormData(this.form)
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams()
        data.forEach((value, key) => {
            params.append(key, value)
        })
        return this.loadUrl(url.pathname + '?' + params.toString())
    }
    
    async loadUrl(url, append = false){
        this.showLoader()
        const params = new URLSearchParams(url.split('?')[1] || '')
        params.set('ajax', 1)
        const response = await fetch(url.split('?')[0] + '?' + params.toString(), {
            headers: {
                'X-Requested-With' : 'XMLHttpRequest'
            }
        })
        if (response.status >= 200 && response.status < 300){
            // console.log('ici')
            const data = await response.json()
            // console.log(data.content)
            this.flipContent(data.content, append)
            this.sorting.innerHTML = data.sorting
            if (!this.moreNav){
                params.delete('ajax')
                this.pagination.innerHTML = data.pagination
            } else if (this.page === data.pages) {
                params.delete('ajax')
                this.pagination.style.display = 'none';
            } else {
                params.delete('ajax')
                this.pagination.style.display = null;
            }
            params.delete('ajax')
            history.replaceState({}, '', url.split('?')[0] + '?' + params.toString())
        } else {
            params.delete('ajax')
            // console.error(response)
            // console.log('la')
        } 
        this.hideLoader()
    }

    /**
     * Remplace les éléments de la grille avec un effet d'animation flip
     * @param {string} content 
     */
    flipContent (content, append){
        const springConfig = 'gentle'
        const exitSpring = function(element, index, onComplete) {
            spring({
                config: 'stiff',
                values: {
                  translateY: [0, -20],
                  opacity: [1, 0]
                },
                onUpdate: ({ translateY, opacity }) => {
                  element.style.opacity = opacity;
                  element.style.transform = `translateY(${translateY}px)`;
                },
                onComplete
              });
        }
        const appearSpring = function(element, index) {
            spring({
                config: 'stiff',
                values: {
                  translateY: [20, 0],
                  opacity: [0, 1]
                },
                onUpdate: ({ translateY, opacity }) => {
                  element.style.opacity = opacity;
                  element.style.transform = `translateY(${translateY}px)`;
                },
                delay: index * 20
              });
        }

        const flipper = new Flipper({
            element : this.content
        })
        Array.from(this.content.children).forEach(element => {
            flipper.addFlipped({
                element,
                spring :springConfig,
                flipId: element.id,
                shouldflip: false,
                onExit: exitSpring
            })
        })
        flipper.recordBeforeUpdate()
        if (append) {
            this.content.innerHTML += content
        } else {
            this.content.innerHTML = content
        }
        Array.from(this.content.children).forEach(element => {
            flipper.addFlipped({
                element,
                spring :springConfig,
                flipId: element.id,
                onAppear: appearSpring
            })
        })
        if(this.content.children.length === 0){
            this.noResult()
        } else {
            this.result()
        }
        flipper.update()
    }

    showLoader() {
        this.form.classList.add('is-loading')
        const loader = this.form.querySelector('.js-loading')
        if(loader === null) {
            return
        }
        loader.setAttribute('aria-hidden', false)
        loader.style.display = null
    }

    hideLoader() {
        this.form.classList.remove('is-loading')
        const loader = this.form.querySelector('.js-loading')
        if(loader === null) {
            return
        }
        loader.setAttribute('aria-hidden', true)
        loader.style.display = 'none'
    }
    noResult(){
        let divNoResult = document.querySelector('.no-result')
        if(divNoResult != null) {
            divNoResult.remove()
            console.log('supp')
        }

        console.log(this.elementsCard.length)
        if(this.elementsCard.length === 0){
            console.log('ok')
            let newCol =  document.createElement("div")
            newCol.classList.add('col', 'text-center', 'no-result')
            let newH = document.createElement("h3")
            let content = document.createTextNode("Aucun résultat trouvé.")
            newH.appendChild(content)
            newCol.appendChild(newH)
            this.elementBrother.insertAdjacentElement('afterend', newCol)
            
            let btnPagi = document.querySelector('.btn-plus')
            btnPagi.classList.add('d-none')
        }
    }
    result(){
        let btnPagi = document.querySelector('.btn-plus')
        if(btnPagi != null){
            btnPagi.classList.remove('d-none')
        }
        let divNoResult = document.querySelector('.no-result')
        if(divNoResult != null) {
            divNoResult.remove()
            console.log('supp')
        }
    }
}