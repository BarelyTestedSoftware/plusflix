<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/5d20f47750.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
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
        <a href="/login" class="btn btn-primary btn-sm" style="margin-left: 60px; text-decoration: none;">
            <i class="fas fa-user" style="margin-right: 8px;"></i> Logowanie
        </a>
        </div>
    </header>
</nav>

<main><?= $params['main'] ?></main>

<footer>&copy;<?= date('Y') ?> Custom Framework</footer>
</body>
</html>
