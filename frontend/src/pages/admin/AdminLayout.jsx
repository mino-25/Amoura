import { NavLink, Outlet } from 'react-router-dom';
import { useAuthStore } from '../../store/authStore';
import './AdminLayout.css';

const LINKS = [
  { to: '/admin', label: 'Tableau de bord', end: true },
  { to: '/admin/reservations', label: 'Réservations' },
  { to: '/admin/tables', label: 'Tables & créneaux' },
  { to: '/admin/clients', label: 'Clients' },
  { to: '/admin/fidelite', label: 'Fidélité' },
];

export default function AdminLayout() {
  const logout = useAuthStore((state) => state.logout);

  return (
    <div className="admin-layout">
      <aside className="admin-sidebar">
        <div>
          <p className="admin-logo">Amoura</p>
          <p className="eyebrow">Administration</p>
        </div>

        <nav className="admin-nav">
          {LINKS.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              end={link.end}
              className={({ isActive }) => `admin-nav-link${isActive ? ' active' : ''}`}
            >
              {link.label}
            </NavLink>
          ))}
        </nav>

        <button type="button" className="admin-logout" onClick={logout}>
          Déconnexion
        </button>
      </aside>

      <div className="admin-content">
        <Outlet />
      </div>
    </div>
  );
}
