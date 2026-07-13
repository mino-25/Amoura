import { Link } from 'react-router-dom';
import './Home.css';

export default function Home() {
  return (
    <div className="home">
      <section className="hero">
        <div className="hero-content">
          <p className="eyebrow">Restaurant méditerranéen · Paris 6e</p>
          <h1>Amoura</h1>
          <p className="hero-subtitle">Une expérience d'excellence</p>
          <Link to="/reservation" className="btn btn-gold">
            Réserver une table
          </Link>
        </div>
        <div className="hero-scroll">↓ Découvrir</div>
      </section>

      <section className="section container intro">
        <p className="eyebrow" style={{ textAlign: 'center' }}>
          Notre cuisine
        </p>
        <h2>Bienvenue chez Amoura</h2>
        <p className="intro-text">
          Niché au cœur de la ville, notre restaurant vous accueille dans un cadre raffiné où se
          rencontrent élégance, convivialité et gastronomie méditerranéenne. Notre chef sublime des
          produits frais et de saison pour vous offrir une cuisine créative, délicate et généreuse.
        </p>

        <div className="intro-gallery">
          <div className="intro-photo">Chef en cuisine</div>
          <div className="intro-photo intro-photo-alt">Plat signature</div>
        </div>

        <Link to="/menu" className="btn btn-primary">
          Voir la carte
        </Link>
      </section>

      <section className="reservation-banner">
        <h2>Réservez votre table en ligne</h2>
        <p>Disponibilités en temps réel · Confirmation par email · Programme fidélité</p>
        <Link to="/reservation" className="btn btn-primary">
          Réserver maintenant
        </Link>
      </section>

      <section className="chef-section">
        <div className="chef-photo">Le Chef</div>
        <div className="chef-content">
          <h2>Chef Karim Mansouri</h2>
          <p className="eyebrow">Chef exécutif &amp; fondateur</p>
          <p>
            Passionné de gastronomie méditerranéenne depuis l'enfance, Karim Mansouri a forgé son
            talent dans les plus grandes maisons avant de créer Amoura. Chaque assiette est une
            œuvre d'art culinaire alliant saveurs, textures et présentation pour une expérience
            inoubliable.
          </p>
        </div>
      </section>
    </div>
  );
}
