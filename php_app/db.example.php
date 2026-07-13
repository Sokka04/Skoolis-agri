<?php
// Exemple de configuration de la base de données.
// Renommez ce fichier en db.php et renseignez vos identifiants.
$host = 'localhost';
$dbname = 'skoolis_agri';
$username = 'root';
$password = 'VOTRE_MOT_DE_PASSE_ICI';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
