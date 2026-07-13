<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prod = $_POST['id_production']; $id_intrant = $_POST['id_intrant']; $qte = $_POST['quantite']; $unite = $_POST['unite']; $date = $_POST['date_utilisation'];
    $stmt = $pdo->prepare("INSERT INTO utilisation_intrant (id_production, id_intrant, quantite, unite, date_utilisation) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_prod, $id_intrant, $qte, $unite, $date]);
}
$utilisations = $pdo->query("SELECT u.*, p.nom as parcelle, c.nom_espece as culture, i.nom as intrant_nom, i.type_intrant FROM utilisation_intrant u JOIN production pr ON u.id_production = pr.id_production JOIN parcelle p ON pr.id_parcelle = p.id_parcelle JOIN culture c ON pr.id_culture = c.id_culture JOIN intrant i ON u.id_intrant = i.id_intrant")->fetchAll();
$prods = $pdo->query("SELECT pr.id_production, p.nom as parcelle, c.nom_espece FROM production pr JOIN parcelle p ON pr.id_parcelle=p.id_parcelle JOIN culture c ON pr.id_culture=c.id_culture")->fetchAll();
$intrants = $pdo->query("SELECT * FROM intrant")->fetchAll();
include 'header.php';
?>
<h2>Suivi des Intrants (F4)</h2>
<form method="post" class="mb-4 bg-light p-3 border">
    <h4>Enregistrer l'utilisation d'un intrant</h4>
    <select name="id_production">
        <?php foreach ($prods as $pr): ?><option value="<?= $pr['id_production'] ?>"><?= $pr['parcelle'].' - '.$pr['nom_espece'] ?></option><?php endforeach; ?>
    </select>
    <select name="id_intrant">
        <?php foreach ($intrants as $i): ?><option value="<?= $i['id_intrant'] ?>"><?= $i['nom'] ?> (<?= $i['type_intrant'] ?>)</option><?php endforeach; ?>
    </select>
    <input type="number" step="0.1" name="quantite" placeholder="Quantité" required>
    <input type="text" name="unite" placeholder="Unité (kg, L...)" required>
    <input type="date" name="date_utilisation" required value="<?= date('Y-m-d') ?>">
    <button type="submit" class="btn btn-sm btn-primary">Ajouter</button>
</form>
<table class="table table-striped">
    <tr><th>Parcelle (Culture)</th><th>Intrant</th><th>Quantité</th><th>Date</th></tr>
    <?php foreach ($utilisations as $u): ?>
    <tr><td><?= htmlspecialchars($u['parcelle'].' ('.$u['culture'].')') ?></td><td><?= htmlspecialchars($u['intrant_nom']) ?></td><td><?= $u['quantite'].' '.$u['unite'] ?></td><td><?= $u['date_utilisation'] ?></td></tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>