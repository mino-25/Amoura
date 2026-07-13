export const MENU = {
  entrees: [
    {
      nom: 'Tartare de thon & avocat',
      description: 'Thon rouge, avocat crémeux, citron vert, coriandre fraîche, huile de sésame',
      prix: 18,
    },
    {
      nom: 'Velouté de saison',
      description: 'Légumes du marché, crème fraîche, herbes aromatiques, croûtons maison',
      prix: 14,
    },
    {
      nom: 'Foie gras mi-cuit',
      description: 'Foie gras de canard, brioche toastée, chutney de figues, fleur de sel',
      prix: 22,
    },
    {
      nom: 'Saint-Jacques snackées',
      description: 'Noix de Saint-Jacques, risotto crémeux, émulsion de bisque, caviar d’Aquitaine',
      prix: 26,
    },
  ],
  plats: [
    {
      nom: 'Agneau confit 7 heures',
      description: 'Épaule d’agneau confite, purée d’aubergine fumée, jus corsé au thym',
      prix: 32,
    },
    {
      nom: 'Loup de mer grillé',
      description: 'Loup de mer, légumes méditerranéens, sauce vierge, huile d’olive AOP',
      prix: 29,
    },
    {
      nom: 'Risotto aux cèpes',
      description: 'Riz carnaroli, cèpes de saison, parmesan 24 mois, huile de truffe',
      prix: 24,
    },
  ],
  desserts: [
    {
      nom: 'Baklava revisité',
      description: 'Pâte filo, pistaches, miel de fleurs, glace fleur d’oranger',
      prix: 12,
    },
    {
      nom: 'Tarte au citron de Menton',
      description: 'Crème citron, meringue italienne, sablé breton',
      prix: 11,
    },
  ],
  boissons: [
    { nom: 'Sélection de vins méditerranéens', description: 'Rouge, blanc ou rosé — verre', prix: 8 },
    { nom: 'Thé à la menthe', description: 'Thé vert, menthe fraîche, sucre de canne', prix: 5 },
  ],
};

export const CATEGORIES = [
  { key: 'entrees', label: 'Entrées' },
  { key: 'plats', label: 'Plats' },
  { key: 'desserts', label: 'Desserts' },
  { key: 'boissons', label: 'Boissons' },
];
