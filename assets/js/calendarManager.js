import {Calendar} from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { parse,subDays } from 'date-fns';

class CalendarManager {

  static initCalendar() {
    const calendarEl = document.getElementById('calendar')

    if (calendarEl) {
      // Get public holidays from data attribute if it exists
      const publicHolidaysData = calendarEl.closest('[data-holidays]')?.dataset.holidays || '[]'
      const publicHolidays = JSON.parse(publicHolidaysData)
      const user = calendarEl.closest('[data-holidays]')?.dataset.user || '{}'

      const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        weekends: false,
        selectable: true,
        timeZone: 'local',
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
        select: function (info) {
          console.log('selected ' + info.startStr + ' to ' + info.endStr)
          const parsedStartDate = parse(info.startStr, 'yyyy-MM-dd', new Date());
          let endDate = parse(info.endStr, 'yyyy-MM-dd', new Date());
          const parsedEndDate = subDays(endDate, 1);

          const event = new CustomEvent('calendar:dateSelected', {
            bubbles: true, // Make sure this bubbles up
            detail: {dateStart: parsedStartDate, dateEnd: parsedEndDate, user: user},
          });

          calendarEl.dispatchEvent(event);
        }
      })

      calendar.render()
      return calendar
    }
  }
}
export default CalendarManager;
