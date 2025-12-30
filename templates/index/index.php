<?php
/**
 * Strona główna
 */

$router = $params['router'];
$hello = $params['hello'];

?>
<?php

$bazaFilmow = [
    [
        'id' => 1,
        'tytul' => 'Incepcja',
        'opis' => 'Czasy, gdy technologia pozwala na wchodzenie w czyjeś sny. Złodziej wykrada sekrety z podświadomości.',
        'typ' => 'film',
        'rok_produkcji' => 2010,
        'ilosc_odcinkow' => null, // null dla filmów
        'zdjecie_okladki' => ["src"=>"https://ecsmedia.pl/cdn-cgi/image/format=webp,width=544,height=544,/c/breaking-bad-sezon-5-b-iext130000977.jpg", "alt"=>"Plakat serialu Breaking Bad"],
        'zdjecie_w_tle' => ["src"=>"https://www.tophifi.pl/media/wysiwyg/amc.jpg", "alt"=>""],
        'rezyser' => 'Christopher Nolan',
        'aktorzy' => ['Leonardo DiCaprio', 'Joseph Gordon-Levitt', 'Elliot Page'],
        'gatunki' => ['Sci-Fi', 'Thriller', 'Akcja'],
        'platformy_streamingowe' => ['Netflix', 'HBO Max']
    ],
    [
        'id' => 2,
        'tytul' => 'Breaking Bad',
        'opis' => 'Nauczyciel chemii dowiaduje się, że ma raka. Postanawia produkować metamfetaminę, by zabezpieczyć rodzinę.',
        'typ' => 'serial',
        'rok_produkcji' => 2008,
        'ilosc_odcinkow' => 62,
        'zdjecie_okladki' => ["src"=>"https://ecsmedia.pl/cdn-cgi/image/format=webp,width=544,height=544,/c/breaking-bad-sezon-5-b-iext130000977.jpg", "alt"=>"Plakat serialu Breaking Bad"],
        'zdjecie_w_tle' => ["src"=>"https://www.tophifi.pl/media/wysiwyg/amc.jpg", "alt"=>""],
        'rezyser' => 'Vince Gilligan',
        'aktorzy' => ['Bryan Cranston', 'Aaron Paul', 'Anna Gunn'],
        'gatunki' => ['Dramat', 'Kryminał'],
        'platformy_streamingowe' => ['Netflix']
    ],
    [
        'id' => 3,
        'tytul' => 'Wiedźmin',
        'opis' => 'Geralt z Rivii, zmutowany łowca potworów, szuka swojego miejsca w świecie, gdzie ludzie często są gorsi niż bestie.',
        'typ' => 'serial',
        'rok_produkcji' => 2019,
        'ilosc_odcinkow' => 24,
        'zdjecie_okladki' => ["src"=>"https://ecsmedia.pl/cdn-cgi/image/format=webp,width=544,height=544,/c/breaking-bad-sezon-5-b-iext130000977.jpg", "alt"=>"Plakat serialu Breaking Bad"],
        'zdjecie_w_tle' => ["src"=>"https://www.tophifi.pl/media/wysiwyg/amc.jpg", "alt"=>""],
        'rezyser' => 'Lauren Schmidt Hissrich',
        'aktorzy' => ['Henry Cavill', 'Anya Chalotra', 'Freya Allan'],
        'gatunki' => ['Fantasy', 'Przygodowy', 'Dramat'],
        'platformy_streamingowe' => ['Netflix']
    ],
    [
        'id' => 4,
        'tytul' => 'Zimna Wojna',
        'opis' => 'Historia trudnej miłości dwojga ludzi, którzy nie potrafią być ze sobą i jednocześnie nie mogą bez siebie żyć.',
        'typ' => 'film',
        'rok_produkcji' => 2018,
        'ilosc_odcinkow' => null,
        'zdjecie_okladki' => ["src"=>"https://ecsmedia.pl/cdn-cgi/image/format=webp,width=544,height=544,/c/breaking-bad-sezon-5-b-iext130000977.jpg", "alt"=>"Plakat serialu Breaking Bad"],
        'zdjecie_w_tle' => ["src"=>"https://www.tophifi.pl/media/wysiwyg/amc.jpg", "alt"=>""],
        'rezyser' => 'Paweł Pawlikowski',
        'aktorzy' => ['Joanna Kulig', 'Tomasz Kot', 'Borys Szyc'],
        'gatunki' => ['Dramat', 'Romans', 'Muzyczny'],
        'platformy_streamingowe' => ['Canal+', 'Player']
    ]
];
?>
<h1><?= $hello ?></h1>
<?php component('select-with-search', ['options' => ["example", "example1"]]); ?>
<?php component('admin-table', [
    'table_column_names' => ['id', 'tytuł', 'opis', 'typ', 'rok produkcji', 'ilość odcinków', 'zdjęcie okładki', 'zdjęcie w tle', 'reżyser', 'aktorzy', 'gatunki', 'platformy streamingowe'],
    'data' => $bazaFilmow,
    'no_data_message' => 'Brak filmów lub seriali w bazie.',
]); ?>