<?php
require_once('tcpdf/tcpdf.php'); // Inclure TCPDF
include '../../config.php'; // Inclure la connexion à la base de données

if (isset($_POST['download_pdf'])) {
    // Charger la connexion depuis la classe config
    $pdo = config::getConnexion();

    // Récupérer les utilisateurs
    $query = "SELECT nom, prenom, email, mdp, role, adresse, telephone, nationalite FROM user";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll();

    // Créer une instance TCPDF
    $pdf = new TCPDF();

    // Configurer les marges et ajouter une page
    $pdf->SetMargins(2.5, 20, 10); // Marges gauche, haut, droite
    $pdf->SetAutoPageBreak(TRUE, 15); // Activer le retour à la ligne automatique

    // Ajouter une page
    $pdf->AddPage();

    // Définir le titre
    $pdf->SetFont('helvetica', 'B', 36);
    $pdf->SetTextColor(255, 0, 0);  
    $pdf->Cell(0, 10, 'Liste des Utilisateurs', 0, 1, 'C');
    
    // Espacement après le titre
    $pdf->Ln(10);

    // Définir la police pour le tableau
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0); // Couleur noire pour les données

    // Créer l'entête du tableau avec des couleurs
    $pdf->SetFillColor(66, 103, 178); // Couleur de fond de l'entête (bleu)
    $pdf->SetTextColor(255, 255, 255); // Couleur blanche pour le texte de l'entête

    // Ajustement de la largeur des colonnes
    $pdf->Cell(25, 10, 'Nom', 1, 0, 'C', 1); // Nom
    $pdf->Cell(20, 10, 'Prénom', 1, 0, 'C', 1); // Prénom
    $pdf->Cell(50, 10, 'Email', 1, 0, 'C', 1); // Email
    $pdf->Cell(20, 10, 'Rôle', 1, 0, 'C', 1); // Rôle
    $pdf->Cell(30, 10, 'Adresse', 1, 0, 'C', 1); // Adresse
    $pdf->Cell(30, 10, 'Téléphone', 1, 0, 'C', 1); // Téléphone
    $pdf->Cell(30, 10, 'Nationalité', 1, 1, 'C', 1); // Nationalité

    // Réinitialiser la couleur du texte pour les données
    $pdf->SetTextColor(0, 0, 0); // Couleur noire pour les données

    // Remplir la table avec les données des utilisateurs
    foreach ($users as $user) {
        // Remplir chaque ligne avec les données
        $pdf->Cell(25, 10, $user['nom'], 1, 0, 'C');
        $pdf->Cell(20, 10, $user['prenom'], 1, 0, 'C');
        $pdf->Cell(50, 10, $user['email'], 1, 0, 'C');
        $pdf->Cell(20, 10, $user['role'], 1, 0, 'C');
        $pdf->Cell(30, 10, $user['adresse'], 1, 0, 'C');
        $pdf->Cell(30, 10, $user['telephone'], 1, 0, 'C');
        $pdf->Cell(30, 10, $user['nationalite'], 1, 1, 'C'); // Nationalité
    }

    // Générer le PDF et forcer le téléchargement
    $pdf->Output('liste_users.pdf', 'D');
    exit();
}
?>
