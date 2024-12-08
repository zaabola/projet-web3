<?php
require_once('tcpdf/tcpdf.php');

// Créer un nouveau document PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Désactiver l'en-tête et le pied de page
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Ajouter une page
$pdf->AddPage();

// Définir la police
$pdf->SetFont('helvetica', '', 12);

// Ajouter du texte
$pdf->Cell(0, 10, 'Test PDF - Si vous voyez ce message, TCPDF fonctionne !', 0, 1);

// Générer le PDF
$pdf->Output('test.pdf', 'D'); 