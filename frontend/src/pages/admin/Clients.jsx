import { useEffect, useState } from 'react';
import apiClient from '../../api/client';
import './admin-shared.css';

export default function AdminClients() {
  const [clients, setClients] = useState([]);

  const load = async () => {
    const { data } = await apiClient.get('/admin/clients');
    setClients(data);
  };

  useEffect(() => {
    load();
  }, []);

  const removeClient = async (id) => {
    if (!window.confirm('Supprimer ce client et toutes ses données personnelles (RGPD) ?')) {
      return;
    }
    await apiClient.delete(`/admin/clients/${id}`);
    load();
  };

  return (
    <div className="admin-page">
      <h1>Clients</h1>

      <div className="admin-table-wrap">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Client</th>
              <th>Email</th>
              <th>Membre depuis</th>
              <th>Réservations</th>
              <th>Points</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {clients.map((client) => (
              <tr key={client.id}>
                <td>
                  <span className="avatar-chip">
                    {client.prenom[0]}
                    {client.nom[0]}
                  </span>
                  {client.prenom} {client.nom}
                </td>
                <td>{client.email}</td>
                <td>{new Date(client.membreDepuis).toLocaleDateString('fr-FR')}</td>
                <td>{client.nbReservations}</td>
                <td>{client.points}</td>
                <td className="admin-table-actions">
                  <button type="button" onClick={() => removeClient(client.id)}>
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
