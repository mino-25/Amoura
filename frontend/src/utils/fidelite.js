export const NIVEAUX = [
  { key: 'bronze', label: 'Bronze', seuil: 0 },
  { key: 'argent', label: 'Argent', seuil: 300 },
  { key: 'or', label: 'Or', seuil: 600 },
  { key: 'platine', label: 'Platine', seuil: 1000 },
];

export function getNiveau(points) {
  return [...NIVEAUX].reverse().find((n) => points >= n.seuil) || NIVEAUX[0];
}

export function getNiveauSuivant(points) {
  return NIVEAUX.find((n) => n.seuil > points) || null;
}

export function getBadges(points, nbVisites) {
  const niveau = getNiveau(points);
  return [
    { key: 'fidele', label: 'Fidèle', symbol: '★', unlocked: nbVisites >= 1 },
    { key: 'gourmet', label: 'Gourmet', symbol: '◆', unlocked: nbVisites >= 5 },
    { key: 'connaisseur', label: 'Connaisseur', symbol: '✦', unlocked: nbVisites >= 10 },
    { key: 'platine', label: 'Platine', symbol: '◈', unlocked: niveau.key === 'platine' },
  ];
}
