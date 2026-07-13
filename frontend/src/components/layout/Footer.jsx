import { Link } from 'react-router-dom';
import './Footer.css';

export default function Footer() {
  return (
    <footer className="footer">
      <div className="container footer-grid">
        <div>
          <p className="eyebrow">Navigation</p>
          <nav className="footer-links">
            <Link to="/">Accueil</Link>
            <Link to="/menu">Menu</Link>
            <Link to="/notre-histoire">Notre histoire</Link>
            <Link to="/contact">Contact</Link>
          </nav>
        </div>

        <div>
          <p className="eyebrow">Horaires</p>
          <p>Mar–Sam : 12h–14h30 · 19h–22h30</p>
          <p>Dimanche : 12h–15h</p>
          <p>Lundi : fermé</p>
        </div>

        <div>
          <p className="eyebrow">Contact</p>
          <p>12 rue de la Gastronomie</p>
          <p>75006 Paris</p>
          <p>+33 1 23 45 67 89</p>
        </div>

        <div className="footer-brand">
          <span>Amoura</span>
        </div>
      </div>

      <div className="footer-bottom container">
        <p>© {new Date().getFullYear()} Amoura. Tous droits réservés.</p>
      </div>
    </footer>
  );
}
