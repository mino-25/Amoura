-- Exécuté automatiquement par l'image MariaDB au premier démarrage du volume.
-- La base "amoura" et son utilisateur sont déjà créés via les variables
-- d'environnement MARIADB_DATABASE / MARIADB_USER. On ajoute ici la base de
-- test (utilisée par la suite PHPUnit) avec les mêmes droits.
CREATE DATABASE IF NOT EXISTS amoura_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON amoura_test.* TO 'amoura'@'%';
FLUSH PRIVILEGES;
