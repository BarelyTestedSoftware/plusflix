PRAGMA foreign_keys = OFF;

INSERT INTO media (id, src, alt) VALUES
    (1, 'https://image.tmdb.org/t/p/w1280/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'Interstellar - Cover Image'),
    (2, 'https://image.tmdb.org/t/p/w1280/dg9e5fPRRId8PoBE0F6jl5y85Eu.jpg', 'The Office - Cover Image'),
    (3, 'https://image.tmdb.org/t/p/w1280/xlaY2zyzMfkhk0HSC5VUwzoZPU1.jpg', 'Inception - Cover Image'),
    (4, 'https://image.tmdb.org/t/p/w1280/gl66K7zRdtNYGrxyS2YDUP5ASZd.jpg', 'Lady Bird - Cover Image'),
    (5, 'https://image.tmdb.org/t/p/w1280/uOOtwVbSr4QDjAGIifLDwpb2Pdl.jpg', 'Stranger Things - Cover Image');

INSERT INTO category (id, name) VALUES
    (1, 'Sci-Fi'), (2, 'Comedy'), (3, 'Drama'), (4, 'Horror'), (5, 'Action');

INSERT INTO streaming (id, name, logoImageId) VALUES
    (1, 'Netflix', 3), (2, 'HBO', 4), (3, 'Apple TV', NULL), (4, 'Prime Video', NULL), (5, 'Disney+', NULL);

INSERT INTO person (id, name, type) VALUES
    (1, 'Christopher Nolan', 2), (2, 'Steve Carell', 1), (3, 'Matthew McConaughey', 1), (4, 'Anne Hathaway', 1), (5, 'Saoirse Ronan', 2);

INSERT INTO show (id, title, description, type, productionDate, numberOfEpisodes, coverImageId, backgroundImageId, directorId) VALUES
    (1, 'Interstellar', 'Gdy zasoby Ziemi wyczerpują się, grupa badaczy wyrusza w najważniejszą misję w dziejach ludzkości: podróż poza naszą galaktykę, by sprawdzić, czy człowiek ma szansę przetrwać wśród gwiazd.', 1, '2014-11-07', 1, 1, 1, 1),
    (2, 'The Office', 'Satyryczne spojrzenie na codzienne życie pracowników biurowych w firmie papierniczej Dunder Mifflin, gdzie absurdalne sytuacje i specyficzny humor szefa, Michaela Scotta, są na porządku dziennym.', 2, '2005-03-24', 201, 2, 2, NULL),
    (3, 'Inception', 'Dom Cobb jest mistrzem ekstrakcji – kradnie cenne sekrety z głębi podświadomości podczas snu. Teraz otrzymuje szansę na odkupienie, jeśli uda mu się dokonać niemożliwego: incepcji, czyli zaszczepienia myśli w ludzkim umyśle.', 1, '2010-07-16', 1, NULL, NULL, 1),
    (4, 'Lady Bird', 'Buntownicza nastolatka o artystycznej duszy próbuje odnaleźć swoją drogę w konserwatywnym liceum w Sacramento, mierząc się z trudnymi relacjami z matką i marzeniami o wyjeździe na studia do Nowego Jorku.', 1, '2017-11-03', 1, NULL, NULL, 5),
    (5, 'Stranger Things', 'W spokojnym miasteczku Hawkins znika chłopiec. Poszukiwania prowadzą jego przyjaciół na trop mrocznej tajemnicy rządu, nadprzyrodzonych sił i spotkania z niezwykłą dziewczynką.', 2, '2016-07-15', 34, NULL, NULL, NULL);

INSERT INTO showStreaming (showId, streamingId, linkToShow) VALUES (1, 1, '/watch/interstellar'), (2, 1, '/watch/the-office'), (5, 1, '/watch/stranger-things');
INSERT INTO showCategory (showId, categoryId) VALUES (1, 1), (2, 2), (3, 1), (4, 3), (5, 4);
INSERT INTO showActor (showId, personId) VALUES (1, 3), (1, 4), (2, 2);

INSERT INTO rating (value, showId) VALUES (4, 1), (4, 2), (4, 3), (3, 4), (4, 5);

PRAGMA foreign_keys = ON;