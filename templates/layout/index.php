<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <?= getCollectedStyles() ?>
    <title><?= e($params['title'] ?? 'Custom Framework') ?></title>
</head>
<body <?= !empty($params['bodyClass']) ? 'class="' . e($params['bodyClass']) . '"' : '' ?>>
<nav>
    <header class="main-header">
        <div class="header-container">
            <a href="/" class="logo">Plusflix</a>

            <form class="search-bar" action="/search" method="GET">
                <input type="text" name="q" placeholder="Szukaj filmów, seriali, aktorów..." autocomplete="off">
                <button type="submit" class="search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        </div>
    </header>
</nav>

<main><?= $params['main'] ?></main>

<footer>&copy;<?= date('Y') ?> Custom Framework</footer>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
