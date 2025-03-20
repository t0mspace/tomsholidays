import {Controller} from '@hotwired/stimulus';
import {Modal} from 'bootstrap';
import initCalendar from '../js/calendar';

export default class extends Controller {
  data = {};
  static targets = ["modal"]

  connect() {
    // Initialize calendar after controller connects
    this.calendarInstance = initCalendar();

    // Listen for custom event
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

    console.log(data);
  }

  openModal(detail) {
    if (!this.hasModalTarget) {
      console.error("Modal target is missing");
      return;
    }

    const {dateStart, dateEnd} = detail;

    try {
      // Initialize the Bootstrap modal
      const modal = new Modal(this.modalTarget);

      // Update modal content
      this.modalTarget.querySelector(".modal-body").innerHTML = `
        <p><strong>Date de début:</strong> ${dateStart}</p>
        <p><strong>Date de fin:</strong> ${dateEnd}</p>
      `;

      // Show the modal
      modal.show();
    } catch (error) {
      console.error("Error opening modal:", error);
    }
  }

  async saveRequest(event) {
    event.preventDefault();
    console.log(this.data);
    try {
      const response = await fetch('/request/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' // Optionnel, utile pour reconnaître les requêtes AJAX
        },
        body: JSON.stringify({ data: this.data })
      });

      if (!response.ok) {
        throw new Error(`Erreur serveur: ${response.status}`);
      }

      const result = await response.json();
      alert(result.message); // Affiche le message de Symfony
    } catch (error) {
      console.error("❌ Erreur lors de l'envoi des données :", error);
      alert("Erreur lors de l'enregistrement de la date.");
    }
  }
}
