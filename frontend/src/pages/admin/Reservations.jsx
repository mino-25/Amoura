import { useEffect, useState } from 'react';
import apiClient from '../../api/client';
import './admin-shared.css';

const STATUT_LABELS = {
  en_attente: { label: 'En attente', className: 'badge-warning' },
  confirmee: { label: 'Confirmée', className: 'badge-success' },
  annulee: { label: 'Annulée', className: 'badge-danger' },
};

function todayISO() {
  return new Date().toLocaleDateString('sv-SE');
}

export default function AdminReservations() {
  const [date, setDate] = useState(todayISO());
  const [reservations, setReservations] = useState([]);
  const [loading, setLoading] = useState(true);

  const load = async () => {
    setLoading(true);
    const { data } = await apiClient.get('/admin/reservations', { params: { date } });
    setReservations(data);
    setLoading(false);
  };

  useEffect(() => {
    load();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [date]);

  const honorer = async (id) => {
    await apiClient.patch(`/admin/reservations/${id}/honorer`);
    load();
  };

  const annuler = async (id) => {
    await apiClient.patch(`/admin/reservations/${id}/statut`, { statut: 'annulee' });
    load();
  };

  return (
    <div className="admin-page">
      <div className="admin-page-header">
        <h1>Réservations</h1>
        <div className="field">
          <label htmlFor="admin-date">Date</label>
          <input id="admin-date" type="date" value={date} onChange={(e) => setDate(e.target.value)} />
        </div>
      </div>

      <div className="admin-table-wrap">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Client</th>
              <th>Heure</th>
              <th>Couverts</th>
              <th>Statut</th>
              <th>Points</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {!loading && reservations.length === 0 && (
              <tr>
                <td colSpan={6}>Aucune réservation pour cette date.</td>
              </tr>
            )}
            {reservations.map((resa) => (
              <tr key={resa.id}>
                <td>{resa.client}</td>
                <td>{resa.heure}</td>
                <td>{resa.nbCouverts}</td>
                <td>
                  <span className={`badge ${STATUT_LABELS[resa.statut]?.className}`}>
                    {STATUT_LABELS[resa.statut]?.label}
                  </span>
                </td>
                <td>{resa.honoree ? '+10' : '—'}</td>
                <td className="admin-table-actions">
                  {!resa.honoree && resa.statut !== 'annulee' && (
                    <button type="button" onClick={() => honorer(resa.id)}>
                      Valider honorée
                    </button>
                  )}
                  {resa.statut !== 'annulee' && (
                    <button type="button" onClick={() => annuler(resa.id)}>
                      Annuler
                    </button>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
