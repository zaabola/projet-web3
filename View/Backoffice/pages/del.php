<?php
include_once '../../../Controller/theme.php';
$travelOfferC = new ThemeController();
$travelOfferC->deleteTheme($_GET["id"]);
header('Location:bib.php');
