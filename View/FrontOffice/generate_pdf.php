<?php
require_once('tcpdf/tcpdf.php');
require_once(__DIR__ . '../../../controller/Articlee.php');

// Récupérer l'ID de l'article
$Id_article = isset($_GET['Id_article']) ? $_GET['Id_article'] : null;

// Vérifier si l'ID est présent
if (!$Id_article) {
    die("ID de l'article non spécifié");
}

try {
    // Instancier le contrôleur
    $articlesController = new ArticlesController();
    $article = $articlesController->getArticleById($Id_article);

    // Vérifier si l'article existe
    if (!$article) {
        die("Article non trouvé");
    }

    // Créer un nouveau document PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Supprimer les en-têtes et pieds de page par défaut
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Définir les marges
    $pdf->SetMargins(15, 15, 15);

    // Ajouter une page
    $pdf->AddPage();

    // Définir la police pour le titre
    $pdf->SetFont('helvetica', 'B', 16);

    // Ajouter le titre
    $pdf->Cell(0, 10, 'Article: ' . $article['Titre_article'], 0, 1, 'C');
    $pdf->Ln(5);

    // Ajouter l'image
    if (!empty($article['Image_article'])) {
        $imagePath = __DIR__ . '/' . $article['Image_article'];
        if (file_exists($imagePath)) {
            // Obtenir les dimensions de l'image
            $imageSize = getimagesize($imagePath);
            $imageWidth = $imageSize[0];
            $imageHeight = $imageSize[1];
            
            // Calculer les dimensions proportionnelles pour le PDF
            $maxWidth = 180; // Largeur maximale en mm
            $ratio = $imageWidth / $imageHeight;
            $newWidth = min($maxWidth, $imageWidth);
            $newHeight = $newWidth / $ratio;
            
            // Centrer l'image
            $x = ($pdf->getPageWidth() - $newWidth) / 2;
            
            // Ajouter l'image
            $pdf->Image($imagePath, $x, $pdf->GetY(), $newWidth);
            $pdf->Ln($newHeight + 10); // Ajouter un espace après l'image
        }
    }

    // Ajouter la description
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Description:', 0, 1);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, $article['Description_article'], 0, 'L');
    $pdf->Ln(5);

    // Ajouter la bibliographie
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Bibliographie:', 0, 1);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, $article['bibliographie'], 0, 'L');

    // Générer le PDF
    ob_end_clean(); // Nettoyer le buffer de sortie
    $pdf->Output('Article_' . $Id_article . '.pdf', 'D');
    exit;

} catch (Exception $e) {
    die("Une erreur est survenue : " . $e->getMessage());
}
?> 