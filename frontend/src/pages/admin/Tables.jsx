import { useEffect, useState } from 'react';
import apiClient from '../../api/client';
import './admin-shared.css';

export default function AdminTables() {
  const [tables, setTables] = useState([]);
  const [creneaux, setCreneaux] = useState([]);
  const [newTable, setNewTable] = useState({ numero: '', capacite: '' });
  const [newCreneau, setNewCreneau] = useState({ heureDebut: '', heureFin: '' });

  const load = async () => {
    const [tablesRes, creneauxRes] = await Promise.all([
      apiClient.get('/admin/tables'),
      apiClient.get('/admin/creneaux'),
    ]);
    setTables(tablesRes.data);
    setCreneaux(creneauxRes.data);
  };

  useEffect(() => {
    load();
  }, []);

  const toggleTableDisponible = async (table) => {
    await apiClient.put(`/admin/tables/${table.id}`, { disponible: !table.disponible });
    load();
  };

  const deleteTable = async (id) => {
    await apiClient.delete(`/admin/tables/${id}`);
    load();
  };

  const addTable = async (event) => {
    event.preventDefault();
    await apiClient.post('/admin/tables', {
      numero: Number(newTable.numero),
      capacite: Number(newTable.capacite),
    });
    setNewTable({ numero: '', capacite: '' });
    load();
  };

  const toggleCreneauActif = async (creneau) => {
    await apiClient.put(`/admin/creneaux/${creneau.id}`, { actif: !creneau.actif });
    load();
  };

  const deleteCreneau = async (id) => {
    await apiClient.delete(`/admin/creneaux/${id}`);
    load();
  };

  const addCreneau = async (event) => {
    event.preventDefault();
    await apiClient.post('/admin/creneaux', newCreneau);
    setNewCreneau({ heureDebut: '', heureFin: '' });
    load();
  };

  return (
    <div className="admin-page">
      <h1>Tables &amp; créneaux</h1>

      <h2>Tables</h2>
      <form className="admin-form-grid" onSubmit={addTable}>
        <div className="field">
          <label htmlFor="table-numero">Numéro</label>
          <input
            id="table-numero"
            type="number"
            required
            value={newTable.numero}
            onChange={(e) => setNewTable((f) => ({ ...f, numero: e.target.value }))}
          />
        </div>
        <div className="field">
          <label htmlFor="table-capacite">Capacité</label>
          <input
            id="table-capacite"
            type="number"
            required
            value={newTable.capacite}
            onChange={(e) => setNewTable((f) => ({ ...f, capacite: e.target.value }))}
          />
        </div>
        <button type="submit" className="btn btn-primary" style={{ gridColumn: '1 / -1', justifySelf: 'start' }}>
          Ajouter une table
        </button>
      </form>

      <div className="admin-table-wrap">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Numéro</th>
              <th>Capacité</th>
              <th>Disponible</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {tables.map((table) => (
              <tr key={table.id}>
                <td>Table {table.numero}</td>
                <td>{table.capacite} places</td>
                <td>
                  <span className={`badge ${table.disponible ? 'badge-success' : 'badge-danger'}`}>
                    {table.disponible ? 'Disponible' : 'Désactivée'}
                  </span>
                </td>
                <td className="admin-table-actions">
                  <button type="button" onClick={() => toggleTableDisponible(table)}>
                    {table.disponible ? 'Désactiver' : 'Activer'}
                  </button>
                  <button type="button" onClick={() => deleteTable(table.id)}>
                    Supprimer
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <h2 style={{ marginTop: 'var(--space-5)' }}>Créneaux</h2>
      <form className="admin-form-grid" onSubmit={addCreneau}>
        <div className="field">
          <label htmlFor="creneau-debut">Heure de début</label>
          <input
            id="creneau-debut"
            type="time"
            required
            value={newCreneau.heureDebut}
            onChange={(e) => setNewCreneau((f) => ({ ...f, heureDebut: e.target.value }))}
          />
        </div>
        <div className="field">
          <label htmlFor="creneau-fin">Heure de fin</label>
          <input
            id="creneau-fin"
            type="time"
            required
            value={newCreneau.heureFin}
            onChange={(e) => setNewCreneau((f) => ({ ...f, heureFin: e.target.value }))}
          />
        </div>
        <button type="submit" className="btn btn-primary" style={{ gridColumn: '1 / -1', justifySelf: 'start' }}>
          Ajouter un créneau
        </button>
      </form>

      <div className="admin-table-wrap">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Créneau</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {creneaux.map((creneau) => (
              <tr key={creneau.id}>
                <td>
                  {creneau.heureDebut} – {creneau.heureFin}
                </td>
                <td>
                  <span className={`badge ${creneau.actif ? 'badge-success' : 'badge-danger'}`}>
                    {creneau.actif ? 'Ouvert' : 'Fermé'}
                  </span>
                </td>
                <td className="admin-table-actions">
                  <button type="button" onClick={() => toggleCreneauActif(creneau)}>
                    {creneau.actif ? 'Fermer' : 'Ouvrir'}
                  </button>
                  <button type="button" onClick={() => deleteCreneau(creneau.id)}>
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
