<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom']; $loc = $_POST['localisation']; $sup = $_POST['superficie']; $ze = $_POST['zone_ecologique']; $id_agri = $_POST['id_agriculteur'];
    $stmt = $pdo->prepare("INSERT INTO parcelle (nom, localisation, superficie, zone_ecologique, id_agriculteur) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $loc, $sup, $ze, $id_agri]);
}
$parcelles = $pdo->query("SELECT p.*, a.nom as agri_nom, a.prenom as agri_prenom FROM parcelle p LEFT JOIN agriculteur a ON p.id_agriculteur = a.id_agriculteur")->fetchAll();
$agris = $pdo->query("SELECT * FROM agriculteur")->fetchAll();
include 'header.php';
?>
<h2>Gestion des Parcelles (F2)</h2>
<form method="post" class="mb-4 bg-light p-3 border">
    <h4>Ajouter une parcelle</h4>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="localisation" placeholder="Localisation">
    <input type="number" step="0.1" name="superficie" placeholder="Superficie (ha)" required>
    <input type="text" name="zone_ecologique" placeholder="Zone Ecologique">
    <select name="id_agriculteur">
        <?php foreach ($agris as $a): ?><option value="<?= $a['id_agriculteur'] ?>"><?= $a['nom'] . ' ' . $a['prenom'] ?></option><?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-sm btn-primary">Ajouter</button>
</form>
<table class="table table-striped">
    <tr><th>ID</th><th>Nom</th><th>Localisation</th><th>Superficie</th><th>Agriculteur</th></tr>
    <?php foreach ($parcelles as $p): ?>
    <tr><td><?= $p['id_parcelle'] ?></td><td><?= htmlspecialchars($p['nom']) ?></td><td><?= htmlspecialchars($p['localisation']) ?></td><td><?= $p['superficie'] ?> ha</td><td><?= htmlspecialchars($p['agri_nom'] . ' ' . $p['agri_prenom']) ?></td></tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>