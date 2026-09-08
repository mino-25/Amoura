import { useState } from 'react';
import { CATEGORIES, MENU } from '../data/menu';
import './Menu.css';

export default function Menu() {
  const [active, setActive] = useState('entrees');

  return (
    <div className="menu-page">
      <header className="page-hero">
        <h1>Notre Carte</h1>
        <p className="eyebrow">Cuisine de saison · Produits locaux · Méditerranée</p>
      </header>

      <div className="container">
        <div className="menu-tabs" role="tablist">
          {CATEGORIES.map((cat) => (
            <button
              key={cat.key}
              role="tab"
              aria-selected={active === cat.key}
              className={`menu-tab${active === cat.key ? ' active' : ''}`}
              onClick={() => setActive(cat.key)}
            >
              {cat.label}
            </button>
          ))}
        </div>

        <div className="menu-grid">
          {MENU[active].map((plat) => (
            <article key={plat.nom} className="menu-item">
              <img className="menu-item-photo" src={plat.photo} alt={plat.nom} />
              <div className="menu-item-body">
                <h3>{plat.nom}</h3>
                <p>{plat.description}</p>
              </div>
              <span className="menu-item-price">{plat.prix} €</span>
            </article>
          ))}
        </div>

        <p className="menu-note">Les allergènes sont disponibles sur demande</p>
      </div>
    </div>
  );
}
