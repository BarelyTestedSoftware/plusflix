<?php
/**
 * Strona główna
 */

$router = $params['router'];
$hello = $params['hello'];

?>

<h1><?= $hello ?></h1>
<?php component('select-with-search', ['options' => ["example", "example1"]]); ?>
<?php component('select-with-search', ['options' => ["example3", "example4"]]); ?>