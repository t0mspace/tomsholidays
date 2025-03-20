import { Controller } from '@hotwired/stimulus';
import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  connect() {
    const calendarEl = document.getElementById('calendar')

    if (calendarEl) {
      const publicHolidays = JSON.parse(calendarEl.dataset.holidays)

      const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        weekends: true,
        selectable: true,
        events: publicHolidays.map(event => ({
          title: event.name,
          start: new Date(event.date).toISOString(),
          end: new Date(event.date).toISOString(),
          allDay: true
        })),
        headerToolbar: {
          left: 'prev,next',
          center: 'title',
          right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        dateClick: function(info) {
          alert('clicked ' + info.dateStr)

          const event = new CustomEvent('calendar:dateClick', {
            detail: { date: info.dateStr },
            bubbles: true
          });

          calendarEl.dispatchEvent(event);
        },
        select: function(info) {
          console.log('selected ' + info.startStr + ' to ' + info.endStr)

          const event = new CustomEvent('calendar:dateClick', {
            detail: { dateStart: info.startStr, dateEnd: info.endStr},
            bubbles: true
          });

        }
      })

      calendar.render()
    }
    this.element.addEventListener('calendar:dateClick', this.handleDateClick.bind(this));
  }

  async handleDateClick(event) {
    const selectedDate = event.detail.date;
    console.log(`📅 Date cliquée : ${selectedDate}`);

    // 🔥 Envoi des données à Symfony via Fetch API
    try {
      const response = await fetch('/request/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' // Optionnel, utile pour reconnaître les requêtes AJAX
        },
        body: JSON.stringify({ date: selectedDate })
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
