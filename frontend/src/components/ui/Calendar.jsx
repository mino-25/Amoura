import { useState } from 'react';
import './Calendar.css';

const WEEKDAYS = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
const MONTHS = [
  'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
];

function toISODate(date) {
  return date.toLocaleDateString('sv-SE'); // format YYYY-MM-DD stable, sans décalage UTC
}

export default function Calendar({ value, onChange }) {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const [viewDate, setViewDate] = useState(new Date(today.getFullYear(), today.getMonth(), 1));

  const year = viewDate.getFullYear();
  const month = viewDate.getMonth();
  const firstDayIndex = (new Date(year, month, 1).getDay() + 6) % 7; // lundi = 0
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  const cells = [];
  for (let i = 0; i < firstDayIndex; i += 1) {
    cells.push(null);
  }
  for (let day = 1; day <= daysInMonth; day += 1) {
    cells.push(new Date(year, month, day));
  }

  return (
    <div className="calendar">
      <div className="calendar-header">
        <button type="button" onClick={() => setViewDate(new Date(year, month - 1, 1))} aria-label="Mois précédent">
          ‹
        </button>
        <span>
          {MONTHS[month]} {year}
        </span>
        <button type="button" onClick={() => setViewDate(new Date(year, month + 1, 1))} aria-label="Mois suivant">
          ›
        </button>
      </div>

      <div className="calendar-weekdays">
        {WEEKDAYS.map((day, index) => (
          // eslint-disable-next-line react/no-array-index-key
          <span key={`${day}-${index}`}>{day}</span>
        ))}
      </div>

      <div className="calendar-grid">
        {cells.map((date, index) => {
          if (!date) {
            // eslint-disable-next-line react/no-array-index-key
            return <span key={`empty-${index}`} />;
          }

          const iso = toISODate(date);
          const isPast = date < today;
          const isSelected = value === iso;

          return (
            <button
              key={iso}
              type="button"
              disabled={isPast}
              className={`calendar-day${isSelected ? ' selected' : ''}`}
              onClick={() => onChange(iso)}
            >
              {date.getDate()}
            </button>
          );
        })}
      </div>
    </div>
  );
}
