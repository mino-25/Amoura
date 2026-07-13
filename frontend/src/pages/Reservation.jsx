import { useCallback, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import apiClient from '../api/client';
import Calendar from '../components/ui/Calendar';
import './Reservation.css';

const COUVERTS_OPTIONS = [1, 2, 3, 4, 5, 6];

function formatDateLong(iso) {
  if (!iso) return '';
  return new Date(`${iso}T00:00:00`).toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
}

export default function Reservation() {
  const [date, setDate] = useState('');
  const [nbCouverts, setNbCouverts] = useState(2);
  const [creneauId, setCreneauId] = useState(null);
  const [disponibilites, setDisponibilites] = useState([]);
  const [loadingSlots, setLoadingSlots] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const navigate = useNavigate();

  const loadDisponibilites = useCallback(async () => {
    if (!date) return;
    setLoadingSlots(true);
    setCreneauId(null);
    try {
      const { data } = await apiClient.get('/reservations/disponibilites', {
        params: { date, nbCouverts },
      });
      setDisponibilites(data);
    } catch {
      setDisponibilites([]);
    } finally {
      setLoadingSlots(false);
    }
  }, [date, nbCouverts]);

  useEffect(() => {
    loadDisponibilites();
  }, [loadDisponibilites]);

  const handleSubmit = async () => {
    setSubmitting(true);
    setError('');
    try {
      await apiClient.post('/reservations', { date, creneauId, nbCouverts });
      setSuccess(true);
    } catch (err) {
      if (err.response?.status === 401) {
        navigate('/connexion', { state: { from: { pathname: '/reservation' } } });
        return;
      }
      setError(err.response?.data?.message || 'Impossible de finaliser la réservation.');
    } finally {
      setSubmitting(false);
    }
  };

  const selectedCreneau = disponibilites.find((c) => c.creneauId === creneauId);

  if (success) {
    return (
      <div className="reservation-page">
        <header className="page-hero">
          <h1>Réservation confirmée</h1>
        </header>
        <div className="container reservation-success">
          <p>
            Merci ! Votre table est réservée pour le <strong>{formatDateLong(date)}</strong> à{' '}
            <strong>{selectedCreneau?.heureDebut}</strong> pour <strong>{nbCouverts}</strong> personne(s).
          </p>
          <p>Un email de confirmation vous a été envoyé.</p>
        </div>
      </div>
    );
  }

  return (
    <div className="reservation-page">
      <header className="page-hero">
        <h1>Réserver une table</h1>
        <p className="eyebrow">Disponibilités en temps réel</p>
      </header>

      <div className="container reservation-grid">
        <div className="reservation-steps">
          <section className="reservation-step">
            <h2>
              <span className="step-number">1</span> Choisir une date
            </h2>
            <Calendar value={date} onChange={setDate} />
          </section>

          <section className="reservation-step">
            <h2>
              <span className="step-number">2</span> Choisir un créneau
            </h2>
            {!date && <p className="hint">Sélectionnez d'abord une date.</p>}
            {date && loadingSlots && <p className="hint">Chargement des créneaux…</p>}
            {date && !loadingSlots && disponibilites.length === 0 && (
              <p className="hint">Aucun créneau disponible pour cette date et ce nombre de couverts.</p>
            )}
            {date && !loadingSlots && disponibilites.length > 0 && (
              <div className="slot-grid">
                {disponibilites.map((slot) => (
                  <button
                    key={slot.creneauId}
                    type="button"
                    className={`slot-btn${creneauId === slot.creneauId ? ' selected' : ''}`}
                    onClick={() => setCreneauId(slot.creneauId)}
                  >
                    {slot.heureDebut}
                  </button>
                ))}
              </div>
            )}
          </section>

          <section className="reservation-step">
            <h2>
              <span className="step-number">3</span> Nombre de personnes
            </h2>
            <div className="slot-grid">
              {COUVERTS_OPTIONS.map((n) => (
                <button
                  key={n}
                  type="button"
                  className={`slot-btn${nbCouverts === n ? ' selected' : ''}`}
                  onClick={() => setNbCouverts(n)}
                >
                  {n === 6 ? '6+' : n}
                </button>
              ))}
            </div>
          </section>
        </div>

        <aside className="reservation-summary">
          <h2>Votre sélection</h2>
          <div className="summary-card">
            <p className="eyebrow">Récapitulatif</p>
            <dl>
              <div>
                <dt>Date</dt>
                <dd>{date ? formatDateLong(date) : '—'}</dd>
              </div>
              <div>
                <dt>Heure</dt>
                <dd>{selectedCreneau?.heureDebut || '—'}</dd>
              </div>
              <div>
                <dt>Couverts</dt>
                <dd>{nbCouverts} personne(s)</dd>
              </div>
            </dl>
          </div>

          {error && <p className="error">{error}</p>}

          <button
            type="button"
            className="btn btn-primary reservation-submit"
            disabled={!date || !creneauId || submitting}
            onClick={handleSubmit}
          >
            {submitting ? 'Confirmation…' : 'Confirmer la réservation'}
          </button>

          <p className="reservation-note">
            Cette réservation honorée vous rapportera <strong>+10 points</strong> de fidélité, cumulables vers des
            avantages exclusifs.
          </p>
        </aside>
      </div>
    </div>
  );
}
