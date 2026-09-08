import { Link } from 'react-router-dom';
import grillPoster from '../assets/grill-poster.jpg';
import grillVideo from '../assets/grill.mp4';
import chefCookingFire from '../assets/chef-cooking-fire.jpg';
import chickenSalad from '../assets/chicken-salad.jpg';
import chefCookingSalad from '../assets/chef-cooking-salad.jpg';
import './Home.css';

export default function Home() {
  return (
    <div className="home">
      <section className="hero">
        <video
          className="hero-video"
          poster={grillPoster}
          autoPlay
          loop
          muted
          playsInline
          aria-hidden="true"
        >
          <source src={grillVideo} type="video/mp4" />
        </video>
        <div className="hero-overlay" aria-hidden="true" />
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
          <img className="intro-photo" src={chefCookingFire} alt="Le chef d'Amoura en cuisine devant les flammes" />
          <img className="intro-photo intro-photo-alt" src={chickenSalad} alt="Plat signature du restaurant Amoura" />
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
        <img className="chef-photo" src={chefCookingSalad} alt="Chef Karim Mansouri en cuisine" />
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
