import salleAmoura from '../assets/young-waitress-setting-table.jpg';
import './About.css';

const VALUES = [
  {
    icon: '★',
    title: 'Excellence',
    text: 'Chaque plat est préparé avec le plus grand soin, dans le respect des techniques culinaires les plus exigeantes.',
  },
  {
    icon: '◷',
    title: 'Authenticité',
    text: 'Des ingrédients frais, locaux, soigneusement sélectionnés auprès de producteurs partenaires qui partagent nos valeurs.',
  },
  {
    icon: '◎',
    title: 'Convivialité',
    text: 'Un cadre chaleureux et raffiné où chaque convive se sent attendu, accueilli et choyé tout au long du repas.',
  },
];

export default function About() {
  return (
    <div className="about-page">
      <header className="page-hero">
        <h1>Notre Histoire</h1>
      </header>

      <section className="about-intro">
        <img className="about-photo" src={salleAmoura} alt="Mise en place d'une table au restaurant Amoura" />
        <div className="about-text">
          <p className="eyebrow">Depuis 2022</p>
          <h2>Un lieu pensé pour l'excellence culinaire</h2>
          <p>
            Né d'une passion profonde pour la gastronomie méditerranéenne, Amoura est un espace où
            chaque détail compte. Fondé par des amoureux de la table, notre restaurant incarne la
            rencontre entre tradition et modernité, offrant une expérience unique à chaque visite.
          </p>
          <p>
            Notre équipe sélectionne rigoureusement des produits locaux et de saison pour composer
            une carte qui évolue au rythme des mois, sublimant les saveurs authentiques de notre
            terroir.
          </p>
        </div>
      </section>

      <section className="about-values container">
        {VALUES.map((value) => (
          <article key={value.title} className="value-card">
            <span className="value-icon" aria-hidden="true">
              {value.icon}
            </span>
            <h3>{value.title}</h3>
            <p>{value.text}</p>
          </article>
        ))}
      </section>
    </div>
  );
}
