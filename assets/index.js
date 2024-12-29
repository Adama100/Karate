// Bootstrap
import Button from "bootstrap/js/src/button"
import Collapse from "bootstrap/js/src/collapse"
import Alert from 'bootstrap/js/src/alert'
import Carousel from 'bootstrap/js/src/carousel'
import Dropdown from 'bootstrap/js/src/dropdown'
import Modal from 'bootstrap/js/src/modal'
import Offcanvas from 'bootstrap/js/src/offcanvas'
import Tab from 'bootstrap/js/src/tab'


const closeAlert = document.querySelectorAll('.close-alert')
if(closeAlert !== null) {
    closeAlert.forEach(close => {
        close.addEventListener('click', function() {
            this.parentElement.style.display = 'none'
        })
    })
}