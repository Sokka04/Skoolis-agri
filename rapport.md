# Projet Skoolis-AGRI - Rapport

## 1. Introduction et présentation du contexte
Le secteur agricole togolais souffre d'un manque de traçabilité dans la gestion de ses exploitations. Les données liées aux parcelles, cultures, intrants et récoltes sont souvent gérées manuellement, limitant les capacités de prévision et de suivi.
Skoolis-AGRI est un Système d'Information conçu pour pallier ces difficultés. Il offre aux coopératives et agriculteurs une interface centralisée pour suivre l'activité agricole, depuis l'enregistrement des parcelles jusqu'à la comptabilisation des récoltes, en passant par le suivi des cultures et des intrants.

## 2. Analyse des besoins
### Acteurs
- **Administrateur** : gère les référentiels, coopératives et comptes globaux.
- **Agriculteur** : acteur de terrain, il interagit avec ses parcelles et saisit les intrants et récoltes.
- **Responsable de coopérative** : suit les bilans globaux.

### Fonctionnalités implémentées (F1 à F5)
- **F1** : Gestion des agriculteurs et des coopératives.
- **F2** : Gestion des parcelles.
- **F3** : Gestion des cultures et des rotations.
- **F4** : Suivi des intrants utilisés.
- **F5** : Enregistrement des récoltes.

### Diagramme des Cas d'Usage (Relations include/extend)
Toutes les actions principales (F1 à F6) nécessitent que l'utilisateur soit connecté (cas `S'authentifier`). C'est pourquoi une relation **<<include>>** lie ces fonctionnalités au cas d'usage d'authentification.
La fonctionnalité **F7 (Alertes sur les rotations)** est un processus optionnel qui étend la gestion des cultures (**F3**) uniquement lorsque les conditions l'exigent, justifiant l'usage d'une relation **<<extend>>**.

## 3. Modélisation conceptuelle (MCD)
- **AGRICULTEUR et COOPERATIVE** : Cardinalités (1,1) pour l'agriculteur (il appartient à 1 coop) et (0,N) pour la coop.
- **AGRICULTEUR et PARCELLE** : (1,1) pour la parcelle (appartient à 1 agriculteur) et (0,N) pour l'agriculteur.
- **PRODUCTION (Ternaire)** : Relie PARCELLE, CULTURE et SAISON (avec la propriété `annee`). Permet de modéliser avec précision le concept de rotation : *sur telle parcelle, à telle saison et telle année, on a planté telle culture*.
- **PRODUCTION et INTRANT (UTILISE)** : Une production utilise (0,N) intrants, et un intrant est utilisé dans (0,N) productions. Les propriétés de l'association (quantité, date) capturent le fait d'utilisation.
- **PRODUCTION et RECOLTE** : (0,1) côté production, car une production donnée donne 0 ou 1 récolte. (1,1) côté récolte.

## 4. Modélisation logique (MLD)
Les règles de transformation suivantes ont été appliquées :
- Chaque entité devient une table.
- Les identifiants (ex: id_agriculteur) deviennent des clés primaires (PK).
- **Transformation des relations 1,n / 1,1** : La clé primaire côté (0,N) ou (1,N) migre dans la table côté (1,1) comme clé étrangère (FK). Par exemple, `id_cooperative` migre dans `agriculteur`, et `id_agriculteur` migre dans `parcelle`.
- **Transformation de l'association ternaire PRODUCTION** : Elle devient une table `production` avec 3 clés étrangères (`id_parcelle`, `id_culture`, `id_saison`) et la clé primaire auto-incrémentée `id_production`.
- **Transformation de l'association N,M (UTILISE)** : Elle devient une table de jonction `utilisation_intrant` avec `id_production` et `id_intrant` en FK.
- L'entité RECOLTE récupère `id_production` comme FK suite à la relation 1,1.

## 5. Base de données et requêtes SQL
Le script SQL `skoolis_agri.sql` implémente ce MLD. Les 5 requêtes demandées sont présentes et permettent notamment de :
1. Calculer le rendement moyen par culture (kg/ha).
2. Suivre les intrants par saison.
3. Lister les récoltes par agriculteur.
4. Suivre les rotations (historique sur une parcelle).
5. Établir le bilan d'une coopérative.

## 6. Présentation de l'application PHP
L'application adopte une architecture simple et efficace (séparation `db.php` pour la connexion PDO, et interfaces).
- **Interface** : Utilisation de Bootstrap pour un rendu clair.
- **Logique** : Scripts PHP gérant les requêtes préparées (`$pdo->prepare()`) pour la sécurité contre les injections.
- **F1 à F5** disposent de formulaires d'ajout et de tableaux d'affichage. La page Bilan présente un tableau récapitulatif des rendements.

## 7. Conclusion et perspectives d'amélioration
Skoolis-AGRI répond au besoin de centralisation des données agricoles. En l'état, le SI assure la traçabilité des opérations. 
En perspectives d'amélioration (fonctionnalités optionnelles non implémentées), le système pourrait :
- Intégrer les fonctionnalités **F6** (Génération de rapports avancés en PDF par parcelle).
- Intégrer **F7** (Alertes automatiques sur les rotations, ex: prévenir si une culture gourmande en azote est replantée 2 années de suite).
