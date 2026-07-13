import { NavLink } from 'react-router-dom';
import { useAuthStore, useIsAuthenticated } from '../../store/authStore';
import './BottomNav.css';

export default function BottomNav() {
  const isAuthenticated = useIsAuthenticated();
  const user = useAuthStore((state) => state.user);
  const profileTarget = isAuthenticated ? (user?.role === 'admin' ? '/admin' : '/profil') : '/connexion';

  return (
    <nav className="bottom-nav" aria-label="Navigation mobile principale">
      <NavLink to="/" end className={({ isActive }) => (isActive ? 'active' : '')}>
        <span aria-hidden="true">⌂</span>
        Accueil
      </NavLink>
      <NavLink to="/menu" className={({ isActive }) => (isActive ? 'active' : '')}>
        <span aria-hidden="true">☰</span>
        Menu
      </NavLink>
      <NavLink to="/reservation" className={({ isActive }) => (isActive ? 'active' : '')}>
        <span aria-hidden="true">▤</span>
        Réserver
      </NavLink>
      <NavLink to={profileTarget} className={({ isActive }) => (isActive ? 'active' : '')}>
        <span aria-hidden="true">☺</span>
        {user?.role === 'admin' ? 'Admin' : 'Profil'}
      </NavLink>
    </nav>
  );
}
