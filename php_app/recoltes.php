<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prod = $_POST['id_production']; $date = $_POST['date_recolte']; $rdt = $_POST['rendement_kg']; $qual = $_POST['qualite'];
    $stmt = $pdo->prepare("INSERT INTO recolte (id_production, date_recolte, rendement_kg, qualite) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_prod, $date, $rdt, $qual]);
}
$recoltes = $pdo->query("SELECT r.*, p.nom as parcelle, c.nom_espece as culture, a.nom as agri_nom FROM recolte r JOIN production pr ON r.id_production = pr.id_production JOIN parcelle p ON pr.id_parcelle = p.id_parcelle JOIN culture c ON pr.id_culture = c.id_culture JOIN agriculteur a ON p.id_agriculteur = a.id_agriculteur")->fetchAll();
$prods = $pdo->query("SELECT pr.id_production, p.nom as parcelle, c.nom_espece FROM production pr JOIN parcelle p ON pr.id_parcelle=p.id_parcelle JOIN culture c ON pr.id_culture=c.id_culture")->fetchAll();
include 'header.php';
?>
<h2>Enregistrement des Récoltes (F5)</h2>
<form method="post" class="mb-4 bg-light p-3 border">
    <h4>Ajouter une récolte</h4>
    <select name="id_production">
        <?php foreach ($prods as $pr): ?><option value="<?= $pr['id_production'] ?>"><?= $pr['parcelle'].' - '.$pr['nom_espece'] ?></option><?php endforeach; ?>
    </select>
    <input type="date" name="date_recolte" required value="<?= date('Y-m-d') ?>">
    <input type="number" step="0.1" name="rendement_kg" placeholder="Rendement (kg)" required>
    <input type="text" name="qualite" placeholder="Qualité">
    <button type="submit" class="btn btn-sm btn-primary">Ajouter</button>
</form>
<table class="table table-striped">
    <tr><th>Parcelle</th><th>Culture</th><th>Agriculteur</th><th>Date</th><th>Rendement</th><th>Qualité</th></tr>
    <?php foreach ($recoltes as $r): ?>
    <tr><td><?= htmlspecialchars($r['parcelle']) ?></td><td><?= htmlspecialchars($r['culture']) ?></td><td><?= htmlspecialchars($r['agri_nom']) ?></td><td><?= $r['date_recolte'] ?></td><td><?= $r['rendement_kg'] ?> kg</td><td><?= htmlspecialchars($r['qualite']) ?></td></tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>