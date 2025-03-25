import {Controller} from '@hotwired/stimulus';
import Modal from '../js/modal';
import CalendarManager from '../js/calendarManager';
import requestManager from '../js/requestManager';
import {format} from 'date-fns';
import fr from 'date-fns/locale/fr';

export default class extends Controller {
  data = {};
  static targets = ["modal"]

  connect() {
    // Initialize calendar after controller connects
    this.calendarInstance = CalendarManager.initCalendar();
    this.element.addEventListener('calendar:dateSelected', this.handleDateSelected.bind(this));
  }

  disconnect() {
    // Clean up when controller disconnects
    if (this.calendarInstance) {
      this.calendarInstance.destroy();
    }
  }

  handleDateSelected(event) {
    this.data = event.detail;
    this.openModal(event.detail);
  }

  openModal(detail) {
    if (!this.hasModalTarget) {
      console.error("Modal target is missing");
      return;
    }

    const {dateStart, dateEnd} = detail;

    try {
      this.modalInstance = new Modal(this.modalTarget);
      const dateStartFormatted = format(new Date(detail.dateStart), 'dd/MM/yyyy', {locale: fr});
      const dateEndFormatted = format(new Date(detail.dateEnd), 'dd/MM/yyyy', {locale: fr});

      this.modalInstance.setId(`calendarModal-${dateStart}`);
      const contentHTML = `
        <p><strong>Date de début :</strong> ${dateStartFormatted}</p>
        <p><strong>Date de fin :</strong> ${dateEndFormatted}</p>
      `;
      this.modalInstance.setContent(contentHTML);
      this.modalInstance.setTitle("Request details");
      this.modalInstance.setAction("calendar#confirmRequest");

      // Ouvrir la modale
      this.modalInstance.open();
    } catch (error) {
      console.error("Error opening modal:", error);
    }
  }

  async confirmRequest(data) {
    this.modalInstance.close();
    requestManager.confirmRequest(this.data)
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        console.error(error);
      })
  }
}
