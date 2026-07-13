import { useEffect, useState } from 'react';
import apiClient from '../../api/client';
import './admin-shared.css';

const TYPES = [
  { value: 'reduction', label: 'Réduction' },
  { value: 'boisson', label: 'Boisson offerte' },
  { value: 'dessert', label: 'Dessert offert' },
  { value: 'autre', label: 'Autre' },
];

export default function AdminFidelite() {
  const [programme, setProgramme] = useState(null);
  const [recompenses, setRecompenses] = useState([]);
  const [newRecompense, setNewRecompense] = useState({ libelle: '', type: 'dessert', seuilPoints: '' });

  const load = async () => {
    const [programmeRes, fideliteRes] = await Promise.all([
      apiClient.get('/admin/fidelite/programme'),
      apiClient.get('/fidelite'),
    ]);
    setProgramme(programmeRes.data);
    setRecompenses(fideliteRes.data.recompenses);
  };

  useEffect(() => {
    load();
  }, []);

  const saveProgramme = async (event) => {
    event.preventDefault();
    await apiClient.put('/admin/fidelite/programme', programme);
    load();
  };

  const addRecompense = async (event) => {
    event.preventDefault();
    await apiClient.post('/admin/fidelite/recompenses', {
      ...newRecompense,
      seuilPoints: Number(newRecompense.seuilPoints),
    });
    setNewRecompense({ libelle: '', type: 'dessert', seuilPoints: '' });
    load();
  };

  const deleteRecompense = async (id) => {
    await apiClient.delete(`/admin/fidelite/recompenses/${id}`);
    load();
  };

  if (!programme) {
    return <div className="admin-page">Chargement…</div>;
  }

  return (
    <div className="admin-page">
      <h1>Programme de fidélité</h1>

      <form className="admin-form-grid" onSubmit={saveProgramme}>
        <div className="field">
          <label htmlFor="points-par-resa">Points par réservation honorée</label>
          <input
            id="points-par-resa"
            type="number"
            value={programme.pointsParResa}
            onChange={(e) => setProgramme((p) => ({ ...p, pointsParResa: Number(e.target.value) }))}
          />
        </div>
        <div className="field">
          <label htmlFor="seuil-recompense">Seuil de récompense par défaut</label>
          <input
            id="seuil-recompense"
            type="number"
            value={programme.seuilRecompense}
            onChange={(e) => setProgramme((p) => ({ ...p, seuilRecompense: Number(e.target.value) }))}
          />
        </div>
        <button type="submit" className="btn btn-primary" style={{ gridColumn: '1 / -1', justifySelf: 'start' }}>
          Enregistrer
        </button>
      </form>

      <h2 style={{ marginTop: 'var(--space-5)' }}>Récompenses</h2>
      <form className="admin-form-grid" onSubmit={addRecompense}>
        <div className="field">
          <label htmlFor="recompense-libelle">Libellé</label>
          <input
            id="recompense-libelle"
            required
            value={newRecompense.libelle}
            onChange={(e) => setNewRecompense((f) => ({ ...f, libelle: e.target.value }))}
          />
        </div>
        <div className="field">
          <label htmlFor="recompense-type">Type</label>
          <select
            id="recompense-type"
            value={newRecompense.type}
            onChange={(e) => setNewRecompense((f) => ({ ...f, type: e.target.value }))}
          >
            {TYPES.map((t) => (
              <option key={t.value} value={t.value}>
                {t.label}
              </option>
            ))}
          </select>
        </div>
        <div className="field">
          <label htmlFor="recompense-seuil">Seuil de points</label>
          <input
            id="recompense-seuil"
            type="number"
            required
            value={newRecompense.seuilPoints}
            onChange={(e) => setNewRecompense((f) => ({ ...f, seuilPoints: e.target.value }))}
          />
        </div>
        <button type="submit" className="btn btn-primary" style={{ gridColumn: '1 / -1', justifySelf: 'start' }}>
          Ajouter une récompense
        </button>
      </form>

      <div className="admin-table-wrap">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Libellé</th>
              <th>Type</th>
              <th>Seuil</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {recompenses.map((r) => (
              <tr key={r.id}>
                <td>{r.libelle}</td>
                <td>{TYPES.find((t) => t.value === r.type)?.label}</td>
                <td>{r.seuilPoints} pts</td>
                <td className="admin-table-actions">
                  <button type="button" onClick={() => deleteRecompense(r.id)}>
                    Supprimer
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
