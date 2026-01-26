PRAGMA foreign_keys = OFF;

INSERT INTO media (id, src, alt) VALUES
                                     (1, 'https://image.tmdb.org/t/p/w1280/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'Interstellar - Cover Image'),
                                     (2, 'https://image.tmdb.org/t/p/w1280/dg9e5fPRRId8PoBE0F6jl5y85Eu.jpg', 'The Office - Cover Image'),
                                     (3, 'https://image.tmdb.org/t/p/w1280/xlaY2zyzMfkhk0HSC5VUwzoZPU1.jpg', 'Inception - Cover Image'),
                                     (4, 'https://image.tmdb.org/t/p/w1280/gl66K7zRdtNYGrxyS2YDUP5ASZd.jpg', 'Lady Bird - Cover Image'),
                                     (5, 'https://image.tmdb.org/t/p/w1280/uOOtwVbSr4QDjAGIifLDwpb2Pdl.jpg', 'Stranger Things - Cover Image'),
                                     (6, 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg', 'Matrix Poster'),
                                     (7, 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg', 'Joker Poster'),
                                     (8, 'https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg', 'Friends Poster'),
                                     (9, 'https://image.tmdb.org/t/p/w500/wHa6KOJAoNTFLFtp7wguUJKSnju.jpg', 'It Poster'),
                                     (10, 'https://image.tmdb.org/t/p/w500/6KErczPBROUzLfKgHQ653QV6dna.jpg', 'Lion King Poster'),
                                     (101, 'https://image.tmdb.org/t/p/w1280/rAiYTfKGqDCRIIqo664sY9XZIvQ.jpg', 'Interstellar BG'),
                                     (102, 'https://image.tmdb.org/t/p/w1280/uNyEVSPeAtJgUBehuQJ8XFidnxp.jpg', 'The Office BG'),
                                     (103, 'https://image.tmdb.org/t/p/w1280/s3TBrRGB1iav7gFOCNx3H31MoES.jpg', 'Inception BG'),
                                     (104, 'https://image.tmdb.org/t/p/w1280/5s934r2b81tq8a37h15a3t3b353.jpg', 'Stranger Things BG'),
                                     (105, 'https://image.tmdb.org/t/p/w1280/n6bUvigpRFqSwmPp1m2YADdbRBc.jpg', 'Joker BG');


INSERT INTO category (id, name) VALUES
    (1, 'Sci-Fi'), (2, 'Komedie'), (3, 'Dramaty'), (4, 'Horrory'), (5, 'Animacje');

INSERT INTO streaming (id, name, logo_image_id) VALUES
    (1, 'Netflix', 3), (2, 'HBO', 4), (3, 'Apple TV', NULL), (4, 'Prime Video', NULL), (5, 'Disney+', NULL);

INSERT INTO person (id, name, type) VALUES
(1, 'Christopher Nolan', 2),
(2, 'Todd Phillips', 2),
(3, 'Duffer Brothers', 2),
(4, 'Greta Gerwig', 2),
(5, 'Steve Carell', 1);

INSERT INTO show (id, title, description, type, production_date, number_of_episodes, cover_image_id, background_image_id, director_id) VALUES
    (1, 'Interstellar', 'Gdy zasoby Ziemi wyczerpują się, grupa badaczy wyrusza w najważniejszą misję w dziejach ludzkości: podróż poza naszą galaktykę, by sprawdzić, czy człowiek ma szansę przetrwać wśród gwiazd.', 1, '2014-11-07', 1, 1, 1, 1),
    (2, 'The Office', 'Satyryczne spojrzenie na codzienne życie pracowników biurowych w firmie papierniczej Dunder Mifflin, gdzie absurdalne sytuacje i specyficzny humor szefa, Michaela Scotta, są na porządku dziennym.', 2, '2005-03-24', 201, 2, 2, NULL),
    (3, 'Inception', 'Dom Cobb jest mistrzem ekstrakcji – kradnie cenne sekrety z głębi podświadomości podczas snu. Teraz otrzymuje szansę na odkupienie, jeśli uda mu się dokonać niemożliwego: incepcji, czyli zaszczepienia myśli w ludzkim umyśle.', 1, '2010-07-16', 1, NULL, NULL, 1),
    (4, 'Lady Bird', 'Buntownicza nastolatka o artystycznej duszy próbuje odnaleźć swoją drogę w konserwatywnym liceum w Sacramento, mierząc się z trudnymi relacjami z matką i marzeniami o wyjeździe na studia do Nowego Jorku.', 1, '2017-11-03', 1, NULL, NULL, 5),
    (5, 'Stranger Things', 'W spokojnym miasteczku Hawkins znika chłopiec. Poszukiwania prowadzą jego przyjaciół na trop mrocznej tajemnicy rządu, nadprzyrodzonych sił i spotkania z niezwykłą dziewczynką.', 2, '2016-07-15', 34, NULL, NULL, NULL);

INSERT INTO show_streaming (show_id, streaming_id, link_to_show) VALUES (1, 1, '/watch/interstellar'), (2, 1, '/watch/the-office'), (5, 1, '/watch/stranger-things');
INSERT INTO show_category (show_id, category_id) VALUES (1, 1), (2, 2), (3, 1), (4, 3), (5, 4);
INSERT INTO show_actor (show_id, person_id) VALUES (1, 3), (1, 4), (2, 2);

INSERT INTO rating (value, show_id) VALUES (4, 1), (4, 2), (4, 3), (3, 4), (4, 5);

PRAGMA foreign_keys = ON;