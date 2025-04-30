import {Calendar} from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { parse,subDays, formatISO } from 'date-fns';

class CalendarManager {

  static initCalendar() {
    const calendarEl = document.getElementById('calendar')

    if (calendarEl) {
      // Get public holidays from data attribute if it exists
      const publicHolidaysData = calendarEl.closest('[data-holidays]')?.dataset.holidays || '[]'
      const MyHolidaysData = calendarEl.closest('[data-myholidays]')?.dataset.myholidays || '[]'
      const publicHolidays = JSON.parse(publicHolidaysData)
      const myHolidays = JSON.parse(MyHolidaysData)
      console.log(myHolidays);
      const user = calendarEl.closest('[data-holidays]')?.dataset.user || '{}'

      const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        weekends: false,
        selectable: true,
        timeZone: 'local',
        eventSources: [
          {
            events: publicHolidays.map(event => ({
              title: event.name,
              start: new Date(event.date).toISOString(),
              end: new Date(event.date).toISOString(),
              allDay: true
            })),
            color: '#ff6666'
          },
          {
            events: myHolidays.map(holiday => ({
              start: new Date(holiday.dateStart).toISOString(),
              end: new Date(holiday.dateEnd).toISOString(),
              allDay: true
            })),
            color: '#3399ff' // Blue for personal holidays
          }
        ],
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

          const formattedStartDate= formatISO(parsedStartDate);
          const formattedEndDate= formatISO(parsedEndDate);

          const event = new CustomEvent('calendar:dateSelected', {
            bubbles: true,
            detail: {dateStart: formattedStartDate, dateEnd: formattedEndDate, employeeEmail: user},
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
