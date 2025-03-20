import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

document.addEventListener('DOMContentLoaded', function () {
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

        calendarEl.dispatchEvent(event);
      }
    })

    calendar.render()
  }
})
