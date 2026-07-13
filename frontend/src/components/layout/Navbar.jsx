import { useState } from 'react';
import { Link, NavLink } from 'react-router-dom';
import { useAuthStore, useIsAuthenticated } from '../../store/authStore';
import './Navbar.css';

const NAV_LINKS = [
  { to: '/', label: 'Accueil' },
  { to: '/menu', label: 'Menu' },
  { to: '/notre-histoire', label: 'Notre histoire' },
  { to: '/contact', label: 'Contact' },
];

export default function Navbar() {
  const [open, setOpen] = useState(false);
  const isAuthenticated = useIsAuthenticated();
  const user = useAuthStore((state) => state.user);

  return (
    <header className="navbar">
      <div className="navbar-inner container">
        <Link to="/" className="navbar-logo" onClick={() => setOpen(false)}>
          Amoura
        </Link>

        <nav className="navbar-links" aria-label="Navigation principale">
          {NAV_LINKS.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) => `navbar-link${isActive ? ' active' : ''}`}
              end={link.to === '/'}
            >
              {link.label}
            </NavLink>
          ))}
        </nav>

        <div className="navbar-actions">
          <Link to="/reservation" className="btn btn-gold navbar-cta">
            Réserver
          </Link>
          {isAuthenticated ? (
            <Link to={user?.role === 'admin' ? '/admin' : '/profil'} className="btn btn-outline navbar-account">
              {user?.role === 'admin' ? 'Administration' : 'Mon profil'}
            </Link>
          ) : (
            <Link to="/connexion" className="btn btn-outline navbar-account">
              Connexion
            </Link>
          )}
        </div>

        <button
          type="button"
          className="navbar-burger"
          aria-label="Ouvrir le menu"
          aria-expanded={open}
          onClick={() => setOpen((v) => !v)}
        >
          <span />
          <span />
          <span />
        </button>
      </div>

      {open && (
        <nav className="navbar-mobile-menu" aria-label="Navigation mobile">
          {NAV_LINKS.map((link) => (
            <NavLink key={link.to} to={link.to} onClick={() => setOpen(false)} end={link.to === '/'}>
              {link.label}
            </NavLink>
          ))}
          {isAuthenticated ? (
            <Link to={user?.role === 'admin' ? '/admin' : '/profil'} onClick={() => setOpen(false)}>
              {user?.role === 'admin' ? 'Administration' : 'Mon profil'}
            </Link>
          ) : (
            <Link to="/connexion" onClick={() => setOpen(false)}>
              Connexion
            </Link>
          )}
        </nav>
      )}
    </header>
  );
}
