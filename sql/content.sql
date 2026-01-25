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

INSERT INTO streaming (id, name, logoImageId) VALUES
    (1, 'Netflix', NULL), (2, 'HBO Max', NULL), (3, 'Disney+', NULL);

INSERT INTO person (id, name, type) VALUES
(1, 'Christopher Nolan', 2),
(2, 'Todd Phillips', 2),
(3, 'Duffer Brothers', 2),
(4, 'Greta Gerwig', 2),
(5, 'Steve Carell', 1);

INSERT INTO show (id, title, description, type, productionDate, numberOfEpisodes, coverImageId, backgroundImageId, directorId) VALUES

(1, 'Interstellar', 'Gdy zasoby Ziemi wyczerpują się, grupa badaczy wyrusza w podróż poza naszą galaktykę.', 1, '2014-11-07', 1, 1, 101, 1),
(2, 'Inception', 'Złodziej kradnie sekrety z podświadomości podczas snu.', 1, '2010-07-16', 1, 3, 103, 1),
(3, 'Matrix', 'Haker odkrywa, że rzeczywistość jest symulacją stworzoną przez maszyny.', 1, '1999-03-31', 1, 6, NULL, NULL),


(4, 'The Office', 'Codzienne życie pracowników biura w Scranton.', 2, '2005-03-24', 201, 2, 102, NULL),
(5, 'Friends', 'Przygody grupy przyjaciół mieszkających na Manhattanie.', 2, '1994-09-22', 236, 8, NULL, NULL),
(6, 'Lady Bird', 'Buntownicza nastolatka próbuje odnaleźć swoją drogę.', 1, '2017-11-03', 1, 4, NULL, 4),


(7, 'Joker', 'Komik Arthur Fleck popada w obłęd i staje się przestępcą.', 1, '2019-10-04', 1, 7, 105, 2),


(8, 'Stranger Things', 'W małym miasteczku znika chłopiec, co odsłania mroczne tajemnice.', 2, '2016-07-15', 34, 5, 104, 3),
(9, 'It (To)', 'Grupa dzieci musi zmierzyć się ze swoimi lękami i klownem Pennywise.', 1, '2017-09-08', 1, 9, NULL, NULL),


(10, 'Król Lew', 'Młody lew Simba musi odzyskać królestwo po śmierci ojca.', 1, '1994-06-15', 1, 10, NULL, NULL);


INSERT INTO showCategory (showId, categoryId) VALUES
                                                  (1, 1), (2, 1), (3, 1), -- Sci-Fi
                                                  (4, 2), (5, 2), (6, 2), -- Komedie
                                                  (6, 3), (7, 3), (1, 3), -- Dramaty (Lady Bird, Joker, Interstellar)
                                                  (8, 4), (9, 4),         -- Horrory (Stranger Things, It)
                                                  (10, 5);                -- Animacje


INSERT INTO rating (value, showId) VALUES
                                       (5, 1), (4, 1), (5, 4), (5, 8), (4, 7), (3, 6);

PRAGMA foreign_keys = ON;