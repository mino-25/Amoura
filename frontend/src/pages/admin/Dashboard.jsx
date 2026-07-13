import { useEffect, useState } from 'react';
import apiClient from '../../api/client';
import './admin-shared.css';

export default function Dashboard() {
  const [stats, setStats] = useState(null);

  useEffect(() => {
    apiClient.get('/admin/dashboard').then((res) => setStats(res.data));
  }, []);

  if (!stats) {
    return <div className="admin-page">Chargement…</div>;
  }

  const delta = stats.reservationsCeSoir - stats.reservationsHier;
  const deltaAnnulations = stats.annulationsAujourdhui - stats.annulationsHier;

  return (
    <div className="admin-page">
      <div className="admin-page-header">
        <h1>Tableau de bord</h1>
        <p className="eyebrow">{new Date(stats.date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</p>
      </div>

      <div className="stat-grid">
        <div className="stat-card">
          <p className="stat-value">{stats.reservationsCeSoir}</p>
          <p className="stat-label">Réservations ce soir</p>
          <p className={`stat-delta ${delta >= 0 ? 'positive' : 'negative'}`}>
            {delta >= 0 ? '+' : ''}
            {delta} vs hier
          </p>
        </div>
        <div className="stat-card">
          <p className="stat-value">{stats.clientsCeMois}</p>
          <p className="stat-label">Clients ce mois</p>
        </div>
        <div className="stat-card">
          <p className="stat-value">{stats.clientsFideles}</p>
          <p className="stat-label">Clients fidèles</p>
        </div>
        <div className="stat-card">
          <p className="stat-value">{stats.annulationsAujourdhui}</p>
          <p className="stat-label">Annulations</p>
          <p className={`stat-delta ${deltaAnnulations <= 0 ? 'positive' : 'negative'}`}>
            {deltaAnnulations >= 0 ? '+' : ''}
            {deltaAnnulations} vs hier
          </p>
        </div>
      </div>

      <h2>Clients les plus fidèles</h2>
      <div className="admin-table-wrap" style={{ marginTop: 'var(--space-3)' }}>
        <table className="admin-table">
          <thead>
            <tr>
              <th>Client</th>
              <th>Points</th>
            </tr>
          </thead>
          <tbody>
            {stats.topFideles.map((client) => (
              <tr key={client.nom}>
                <td>{client.nom}</td>
                <td>{client.points}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
