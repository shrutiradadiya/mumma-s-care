<?php
session_start();
$search   = isset($_GET['search'])   ? urlencode($_GET['search'])   : '';
$category = isset($_GET['category']) ? urlencode($_GET['category']) : 'all';
header("Location: category.php?category=$category&search=$search");
exit;
