<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_parc = $_POST['id_parcelle']; $id_cult = $_POST['id_culture']; $id_saison = $_POST['id_saison']; $annee = $_POST['annee'];
    $stmt = $pdo->prepare("INSERT INTO production (id_parcelle, id_culture, id_saison, annee) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_parc, $id_cult, $id_saison, $annee]);
}
$prods = $pdo->query("SELECT pr.*, p.nom as parcelle, c.nom_espece as culture, s.nom as saison FROM production pr JOIN parcelle p ON pr.id_parcelle = p.id_parcelle JOIN culture c ON pr.id_culture = c.id_culture JOIN saison s ON pr.id_saison = s.id_saison")->fetchAll();
$parcelles = $pdo->query("SELECT * FROM parcelle")->fetchAll();
$cultures = $pdo->query("SELECT * FROM culture")->fetchAll();
$saisons = $pdo->query("SELECT * FROM saison")->fetchAll();
include 'header.php';
?>
<h2>Gestion des Cultures et Rotations (F3)</h2>
<form method="post" class="mb-4 bg-light p-3 border">
    <h4>Associer une culture à une parcelle (Rotation)</h4>
    <select name="id_parcelle">
        <?php foreach ($parcelles as $p): ?><option value="<?= $p['id_parcelle'] ?>"><?= $p['nom'] ?></option><?php endforeach; ?>
    </select>
    <select name="id_culture">
        <?php foreach ($cultures as $c): ?><option value="<?= $c['id_culture'] ?>"><?= $c['nom_espece'] ?></option><?php endforeach; ?>
    </select>
    <select name="id_saison">
        <?php foreach ($saisons as $s): ?><option value="<?= $s['id_saison'] ?>"><?= $s['nom'] ?></option><?php endforeach; ?>
    </select>
    <input type="number" name="annee" placeholder="Année" value="<?= date('Y') ?>" required>
    <button type="submit" class="btn btn-sm btn-primary">Ajouter</button>
</form>
<table class="table table-striped">
    <tr><th>ID Prod</th><th>Parcelle</th><th>Culture</th><th>Saison</th><th>Année</th></tr>
    <?php foreach ($prods as $pr): ?>
    <tr><td><?= $pr['id_production'] ?></td><td><?= htmlspecialchars($pr['parcelle']) ?></td><td><?= htmlspecialchars($pr['culture']) ?></td><td><?= htmlspecialchars($pr['saison']) ?></td><td><?= $pr['annee'] ?></td></tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>