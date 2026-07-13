-- Création de la base de données
CREATE DATABASE IF NOT EXISTS skoolis_agri;
USE skoolis_agri;

-- Table cooperative
CREATE TABLE cooperative (
    id_cooperative INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255)
);

-- Table agriculteur
CREATE TABLE agriculteur (
    id_agriculteur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    contact VARCHAR(50),
    id_cooperative INT,
    FOREIGN KEY (id_cooperative) REFERENCES cooperative(id_cooperative) ON DELETE SET NULL
);

-- Table parcelle
CREATE TABLE parcelle (
    id_parcelle INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    localisation VARCHAR(150),
    superficie FLOAT NOT NULL COMMENT 'en hectares',
    zone_ecologique VARCHAR(100),
    id_agriculteur INT,
    FOREIGN KEY (id_agriculteur) REFERENCES agriculteur(id_agriculteur) ON DELETE CASCADE
);

-- Table culture
CREATE TABLE culture (
    id_culture INT AUTO_INCREMENT PRIMARY KEY,
    nom_espece VARCHAR(100) NOT NULL,
    type_culture VARCHAR(50)
);

-- Table saison
CREATE TABLE saison (
    id_saison INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL -- Ex: Saison des pluies, Saison sèche
);

-- Table production (fait le lien entre une parcelle, une culture, à une période donnée pour gérer la rotation)
CREATE TABLE production (
    id_production INT AUTO_INCREMENT PRIMARY KEY,
    id_parcelle INT NOT NULL,
    id_culture INT NOT NULL,
    id_saison INT NOT NULL,
    annee INT NOT NULL,
    FOREIGN KEY (id_parcelle) REFERENCES parcelle(id_parcelle) ON DELETE CASCADE,
    FOREIGN KEY (id_culture) REFERENCES culture(id_culture),
    FOREIGN KEY (id_saison) REFERENCES saison(id_saison),
    UNIQUE (id_parcelle, id_saison, annee) -- Une parcelle ne peut avoir qu'une culture principale par saison et année
);

-- Table intrant
CREATE TABLE intrant (
    id_intrant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type_intrant VARCHAR(50) -- Ex: Engrais, Semence, Pesticide
);

-- Table utilisation_intrant
CREATE TABLE utilisation_intrant (
    id_utilisation INT AUTO_INCREMENT PRIMARY KEY,
    id_production INT NOT NULL,
    id_intrant INT NOT NULL,
    quantite FLOAT NOT NULL,
    unite VARCHAR(20) NOT NULL,
    date_utilisation DATE NOT NULL,
    FOREIGN KEY (id_production) REFERENCES production(id_production) ON DELETE CASCADE,
    FOREIGN KEY (id_intrant) REFERENCES intrant(id_intrant)
);

-- Table recolte
CREATE TABLE recolte (
    id_recolte INT AUTO_INCREMENT PRIMARY KEY,
    id_production INT NOT NULL,
    date_recolte DATE NOT NULL,
    rendement_kg FLOAT NOT NULL,
    qualite VARCHAR(50),
    FOREIGN KEY (id_production) REFERENCES production(id_production) ON DELETE CASCADE
);

-- ==========================================
-- INSERTION DES DONNEES DE TEST
-- ==========================================

INSERT INTO cooperative (nom, adresse) VALUES
('Coopérative de Kpalimé', 'Région des Plateaux'),
('Coopérative de Tsévié', 'Région Maritime');

INSERT INTO agriculteur (nom, prenom, contact, id_cooperative) VALUES
('Koffi', 'Ayao', '90112233', 1),
('Abla', 'Akou', '91223344', 1),
('Kodjo', 'Elias', '92334455', 2);

INSERT INTO parcelle (nom, localisation, superficie, zone_ecologique, id_agriculteur) VALUES
('Champ Nord', 'Village A', 2.5, 'Savane', 1),
('Champ Sud', 'Village B', 1.2, 'Forêt', 1),
('Plaine Est', 'Village C', 3.0, 'Savane arborée', 2),
('Vallée Verte', 'Village D', 5.0, 'Zone humide', 3);

INSERT INTO culture (nom_espece, type_culture) VALUES
('Maïs', 'Céréale'),
('Soja', 'Légumineuse'),
('Igname', 'Tubercule');

INSERT INTO saison (nom) VALUES
('Grande saison des pluies'),
('Petite saison des pluies');

INSERT INTO production (id_parcelle, id_culture, id_saison, annee) VALUES
(1, 1, 1, 2023), -- Maïs, Champ Nord
(1, 2, 2, 2023), -- Soja, Champ Nord (Rotation)
(2, 3, 1, 2023), -- Igname, Champ Sud
(3, 1, 1, 2023), -- Maïs, Plaine Est
(4, 2, 1, 2023); -- Soja, Vallée Verte

INSERT INTO intrant (nom, type_intrant) VALUES
('NPK 15-15-15', 'Engrais'),
('Semence Maïs Hybride', 'Semence'),
('Herbicide Total', 'Pesticide');

INSERT INTO utilisation_intrant (id_production, id_intrant, quantite, unite, date_utilisation) VALUES
(1, 2, 50, 'kg', '2023-04-10'),
(1, 1, 100, 'kg', '2023-05-15'),
(2, 1, 50, 'kg', '2023-09-10'),
(4, 2, 100, 'kg', '2023-04-12');

INSERT INTO recolte (id_production, date_recolte, rendement_kg, qualite) VALUES
(1, '2023-07-20', 3500, 'Bonne'),
(2, '2023-12-10', 1200, 'Moyenne'),
(3, '2023-10-05', 8000, 'Excellente'),
(4, '2023-08-01', 4200, 'Bonne');

-- ==========================================
-- REQUETES METIER (Au moins 5)
-- ==========================================

-- 1. Rendement moyen par culture (en kg par hectare)
SELECT 
    c.nom_espece, 
    SUM(r.rendement_kg) AS rendement_total_kg,
    SUM(p.superficie) AS superficie_totale_ha,
    (SUM(r.rendement_kg) / SUM(p.superficie)) AS rendement_moyen_kg_ha
FROM recolte r
JOIN production pr ON r.id_production = pr.id_production
JOIN culture c ON pr.id_culture = c.id_culture
JOIN parcelle p ON pr.id_parcelle = p.id_parcelle
GROUP BY c.nom_espece;

-- 2. Quantité d'intrants utilisés par saison et par type d'intrant
SELECT 
    s.nom AS saison, 
    i.type_intrant, 
    SUM(ui.quantite) AS quantite_totale, 
    ui.unite
FROM utilisation_intrant ui
JOIN intrant i ON ui.id_intrant = i.id_intrant
JOIN production pr ON ui.id_production = pr.id_production
JOIN saison s ON pr.id_saison = s.id_saison
GROUP BY s.nom, i.type_intrant, ui.unite;

-- 3. Liste des récoltes par agriculteur
SELECT 
    a.nom AS agriculteur_nom, 
    a.prenom AS agriculteur_prenom, 
    c.nom_espece AS culture, 
    r.date_recolte, 
    r.rendement_kg
FROM recolte r
JOIN production pr ON r.id_production = pr.id_production
JOIN parcelle p ON pr.id_parcelle = p.id_parcelle
JOIN agriculteur a ON p.id_agriculteur = a.id_agriculteur
JOIN culture c ON pr.id_culture = c.id_culture
ORDER BY a.nom, r.date_recolte DESC;

-- 4. Suivi des rotations de cultures pour une parcelle spécifique (ex: Champ Nord)
SELECT 
    p.nom AS parcelle, 
    pr.annee, 
    s.nom AS saison, 
    c.nom_espece AS culture
FROM production pr
JOIN parcelle p ON pr.id_parcelle = p.id_parcelle
JOIN saison s ON pr.id_saison = s.id_saison
JOIN culture c ON pr.id_culture = c.id_culture
WHERE p.nom = 'Champ Nord'
ORDER BY pr.annee DESC, s.id_saison ASC;

-- 5. Bilan des productions d'une coopérative donnée
SELECT 
    coop.nom AS nom_cooperative,
    COUNT(DISTINCT a.id_agriculteur) AS nombre_agriculteurs,
    SUM(p.superficie) AS surface_totale_exploitee_ha,
    SUM(r.rendement_kg) AS production_totale_kg
FROM cooperative coop
JOIN agriculteur a ON coop.id_cooperative = a.id_cooperative
JOIN parcelle p ON a.id_agriculteur = p.id_agriculteur
JOIN production pr ON p.id_parcelle = pr.id_parcelle
LEFT JOIN recolte r ON pr.id_production = r.id_production
GROUP BY coop.id_cooperative;
