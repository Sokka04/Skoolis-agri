<?php
require 'db.php';
$rendements = $pdo->query("SELECT c.nom_espece, SUM(r.rendement_kg) AS rdt_total, SUM(p.superficie) AS sup_totale, (SUM(r.rendement_kg) / SUM(p.superficie)) AS rdt_moyen FROM recolte r JOIN production pr ON r.id_production = pr.id_production JOIN culture c ON pr.id_culture = c.id_culture JOIN parcelle p ON pr.id_parcelle = p.id_parcelle GROUP BY c.nom_espece")->fetchAll();
include 'header.php';
?>
<h2>Bilan (Rendement moyen par culture)</h2>
<table class="table table-bordered mt-4">
    <tr class="table-dark"><th>Culture</th><th>Production Totale (kg)</th><th>Superficie Totale (ha)</th><th>Rendement Moyen (kg/ha)</th></tr>
    <?php foreach ($rendements as $r): ?>
    <tr>
        <td><?= htmlspecialchars($r['nom_espece']) ?></td>
        <td><?= round($r['rdt_total'], 2) ?></td>
        <td><?= round($r['sup_totale'], 2) ?></td>
        <td><strong><?= round($r['rdt_moyen'], 2) ?></strong></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>