<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/main.css">
    <script src="https://kit.fontawesome.com/5d20f47750.js" crossorigin="anonymous"></script>
    <?= getCollectedStyles() ?>
    <title><?= e($params['title'] ?? 'Custom Framework') ?></title>
</head>
<body <?= !empty($params['bodyClass']) ? 'class="' . e($params['bodyClass']) . '"' : '' ?>>
<nav>
    <a href="/">Plusflix</a>
</nav>

<main><?= $params['main'] ?></main>

<footer>&copy;<?= date('Y') ?> Custom Framework</footer>
</body>
</html>
