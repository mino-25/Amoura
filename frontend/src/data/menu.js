import tartareThon from '../assets/menu/tartare-thon.jpg';
import veloutéSaison from '../assets/menu/veloute-saison.jpg';
import foieGras from '../assets/menu/foie-gras.jpg';
import saintJacques from '../assets/menu/saint-jacques.jpg';
import agneauConfit from '../assets/menu/agneau-confit.jpg';
import loupDeMer from '../assets/menu/loup-de-mer.jpg';
import risottoCèpes from '../assets/menu/risotto-cepes.jpg';
import baklava from '../assets/menu/baklava.jpg';
import tarteCitron from '../assets/menu/tarte-citron.jpg';
import vin from '../assets/menu/vin.jpg';
import théMenthe from '../assets/menu/the-menthe.jpg';

export const MENU = {
  entrees: [
    {
      nom: 'Tartare de thon & avocat',
      description: 'Thon rouge, avocat crémeux, citron vert, coriandre fraîche, huile de sésame',
      prix: 18,
      photo: tartareThon,
    },
    {
      nom: 'Velouté de saison',
      description: 'Légumes du marché, crème fraîche, herbes aromatiques, croûtons maison',
      prix: 14,
      photo: veloutéSaison,
    },
    {
      nom: 'Foie gras mi-cuit',
      description: 'Foie gras de canard, brioche toastée, chutney de figues, fleur de sel',
      prix: 22,
      photo: foieGras,
    },
    {
      nom: 'Saint-Jacques snackées',
      description: 'Noix de Saint-Jacques, risotto crémeux, émulsion de bisque, caviar d’Aquitaine',
      prix: 26,
      photo: saintJacques,
    },
  ],
  plats: [
    {
      nom: 'Agneau confit 7 heures',
      description: 'Épaule d’agneau confite, purée d’aubergine fumée, jus corsé au thym',
      prix: 32,
      photo: agneauConfit,
    },
    {
      nom: 'Loup de mer grillé',
      description: 'Loup de mer, légumes méditerranéens, sauce vierge, huile d’olive AOP',
      prix: 29,
      photo: loupDeMer,
    },
    {
      nom: 'Risotto aux cèpes',
      description: 'Riz carnaroli, cèpes de saison, parmesan 24 mois, huile de truffe',
      prix: 24,
      photo: risottoCèpes,
    },
  ],
  desserts: [
    {
      nom: 'Baklava revisité',
      description: 'Pâte filo, pistaches, miel de fleurs, glace fleur d’oranger',
      prix: 12,
      photo: baklava,
    },
    {
      nom: 'Tarte au citron de Menton',
      description: 'Crème citron, meringue italienne, sablé breton',
      prix: 11,
      photo: tarteCitron,
    },
  ],
  boissons: [
    { nom: 'Sélection de vins méditerranéens', description: 'Rouge, blanc ou rosé — verre', prix: 8, photo: vin },
    { nom: 'Thé à la menthe', description: 'Thé vert, menthe fraîche, sucre de canne', prix: 5, photo: théMenthe },
  ],
};

export const CATEGORIES = [
  { key: 'entrees', label: 'Entrées' },
  { key: 'plats', label: 'Plats' },
  { key: 'desserts', label: 'Desserts' },
  { key: 'boissons', label: 'Boissons' },
];
