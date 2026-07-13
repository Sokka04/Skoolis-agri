<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom']; $prenom = $_POST['prenom']; $contact = $_POST['contact']; $id_coop = $_POST['id_cooperative'];
    $stmt = $pdo->prepare("INSERT INTO agriculteur (nom, prenom, contact, id_cooperative) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $contact, $id_coop]);
}
$agriculteurs = $pdo->query("SELECT a.*, c.nom as coop FROM agriculteur a LEFT JOIN cooperative c ON a.id_cooperative = c.id_cooperative")->fetchAll();
$coops = $pdo->query("SELECT * FROM cooperative")->fetchAll();
include 'header.php';
?>
<h2>Gestion des Agriculteurs (F1)</h2>
<form method="post" class="mb-4 bg-light p-3 border">
    <h4>Ajouter un agriculteur</h4>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="text" name="contact" placeholder="Contact">
    <select name="id_cooperative">
        <option value="">Sélectionner une coopérative</option>
        <?php foreach ($coops as $c): ?><option value="<?= $c['id_cooperative'] ?>"><?= $c['nom'] ?></option><?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-sm btn-primary">Ajouter</button>
</form>
<table class="table table-striped">
    <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Contact</th><th>Coopérative</th></tr>
    <?php foreach ($agriculteurs as $a): ?>
    <tr><td><?= $a['id_agriculteur'] ?></td><td><?= htmlspecialchars($a['nom']) ?></td><td><?= htmlspecialchars($a['prenom']) ?></td><td><?= htmlspecialchars($a['contact']) ?></td><td><?= htmlspecialchars($a['coop']) ?></td></tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>