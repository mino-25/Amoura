import { useEffect, useState } from 'react';
import apiClient from '../api/client';
import { useAuthStore } from '../store/authStore';
import { getBadges, getNiveau, getNiveauSuivant } from '../utils/fidelite';
import './Profile.css';

const STATUT_LABELS = {
  en_attente: { label: 'En attente', className: 'badge-warning' },
  confirmee: { label: 'À venir', className: 'badge-success' },
  annulee: { label: 'Annulée', className: 'badge-danger' },
};

export default function Profile() {
  const user = useAuthStore((state) => state.user);
  const [fidelite, setFidelite] = useState(null);
  const [reservations, setReservations] = useState([]);
  const [nbVisites, setNbVisites] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function load() {
      const [fideliteRes, reservationsRes, visitesRes] = await Promise.all([
        apiClient.get('/fidelite'),
        apiClient.get('/reservations'),
        apiClient.get('/profil/visites'),
      ]);
      setFidelite(fideliteRes.data);
      setReservations(reservationsRes.data);
      setNbVisites(visitesRes.data.nbVisites);
      setLoading(false);
    }
    load();
  }, []);

  if (loading || !fidelite) {
    return <div className="container profile-loading">Chargement de votre profil…</div>;
  }

  const points = fidelite.solde;
  const niveau = getNiveau(points);
  const niveauSuivant = getNiveauSuivant(points);
  const badges = getBadges(points, nbVisites);
  const progression = niveauSuivant
    ? Math.min(100, ((points - niveau.seuil) / (niveauSuivant.seuil - niveau.seuil)) * 100)
    : 100;

  const initials = `${user?.prenom?.[0] || ''}${user?.nom?.[0] || ''}`.toUpperCase();

  return (
    <div className="profile-page">
      <header className="profile-header">
        <div className="container profile-header-inner">
          <div className="profile-avatar">{initials}</div>
          <div>
            <h1>
              {user?.prenom} {user?.nom}
            </h1>
            <p className="eyebrow">Niveau {niveau.label}</p>
          </div>
        </div>
      </header>

      <section className="profile-points">
        <div className="container profile-points-inner">
          <div>
            <p className="points-value">{points}</p>
            <p className="eyebrow">Points fidélité</p>
          </div>
          <div className="profile-progress">
            <div className="progress-labels">
              <span>Niveau {niveau.label}</span>
              {niveauSuivant && <span>Niveau {niveauSuivant.label} · {niveauSuivant.seuil} pts</span>}
            </div>
            <div className="progress-track">
              <div className="progress-fill" style={{ width: `${progression}%` }} />
            </div>
            {niveauSuivant && (
              <p className="progress-hint">
                Il vous reste {niveauSuivant.seuil - points} points pour atteindre le niveau {niveauSuivant.label}
              </p>
            )}
          </div>
        </div>
      </section>

      <section className="container profile-content">
        <div className="card profile-reservations">
          <h2>Mes réservations</h2>
          {reservations.length === 0 && <p className="hint">Aucune réservation pour le moment.</p>}
          <ul>
            {reservations.map((resa) => (
              <li key={resa.id}>
                <div>
                  <p className="resa-date">
                    {new Date(`${resa.date}T00:00:00`).toLocaleDateString('fr-FR', {
                      weekday: 'long',
                      day: 'numeric',
                      month: 'long',
                    })}
                  </p>
                  <p className="resa-meta">
                    {resa.heure} · {resa.nbCouverts} couverts
                  </p>
                </div>
                <span className={`badge ${resa.honoree ? 'badge-success' : STATUT_LABELS[resa.statut]?.className}`}>
                  {resa.honoree ? 'Honorée' : STATUT_LABELS[resa.statut]?.label}
                </span>
              </li>
            ))}
          </ul>
        </div>

        <div className="card profile-badges">
          <h2>Mes badges</h2>
          <div className="badges-grid">
            {badges.map((badge) => (
              <div key={badge.key} className={`badge-item${badge.unlocked ? ' unlocked' : ''}`}>
                <span className="badge-symbol" aria-hidden="true">
                  {badge.symbol}
                </span>
                <span>{badge.label}</span>
              </div>
            ))}
          </div>

          <div className="profile-stats">
            <div>
              <p className="stat-value">{nbVisites}</p>
              <p className="eyebrow">Visites</p>
            </div>
            <div>
              <p className="stat-value">{points}</p>
              <p className="eyebrow">Points</p>
            </div>
            <div>
              <p className="stat-value">{niveau.label}</p>
              <p className="eyebrow">Niveau</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
