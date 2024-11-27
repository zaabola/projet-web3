<?php
include_once '../../../controller/theme.php';
$travelOfferC = new ThemeController();
$travelOfferC->deleteTheme($_GET["id"]);
header('Location:bib.php');
