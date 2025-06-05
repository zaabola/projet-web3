<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('tcpdf/tcpdf.php');
require_once('../../Controller/Articlee.php');

$Id_article = isset($_GET['Id_article']) ? $_GET['Id_article'] : null;

if (!$Id_article) {
  die("ID was not provided");
}

try {
  $articlesController = new ArticlesController();
  $article = $articlesController->getArticleById($Id_article);
  var_dump($Id_article);

  if (!$article) {
    die("Article not found");
  }

  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);

  $pdf->SetMargins(15, 15, 15);

  $pdf->AddPage();

  $pdf->SetFont('helvetica', 'B', 16);

  $pdf->Cell(0, 10, 'Article: ' . $article['Titre_article'], 0, 1, 'C');
  $pdf->Ln(5);

  if (!empty($article['Image_article'])) {
    $imagePath = '../FrontOffice/images/' . $article['Image_article'];
    if (file_exists($imagePath)) {
      $imageSize = getimagesize($imagePath);
      $imageWidth = $imageSize[0];
      $imageHeight = $imageSize[1];

      $maxWidth = 180;
      $ratio = $imageWidth / $imageHeight;
      $newWidth = min($maxWidth, $imageWidth);
      $newHeight = $newWidth / $ratio;

      $x = ($pdf->getPageWidth() - $newWidth) / 2;

      $pdf->Image($imagePath, $x, $pdf->GetY(), $newWidth);
      $pdf->Ln($newHeight + 10);
    }
  }

  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->Cell(0, 10, 'Description:', 0, 1);
  $pdf->SetFont('helvetica', '', 11);
  $pdf->MultiCell(0, 10, $article['Description_article'], 0, 'L');
  $pdf->Ln(5);

  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->Cell(0, 10, 'Bibliography:', 0, 1);
  $pdf->SetFont('helvetica', '', 11);
  $pdf->MultiCell(0, 10, $article['bibliographie'], 0, 'L');

  ob_end_clean();
  $pdf->Output('Article_' . $Id_article . '.pdf', 'D');
  exit;
} catch (Exception $e) {
  die("Error occured : " . $e->getMessage());
}
