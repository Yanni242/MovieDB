/*  File: 1-db-setup.sql                                */
/*  Authors: Destiny Delancey & Yanni Turnquest         */
/*  Date: March 29, 2023                                */

CREATE SCHEMA movies;

CREATE TABLE movies.movie 
(
  movie_id INT NOT NULL GENERATED BY DEFAULT AS IDENTITY,
  title varchar(1000) DEFAULT NULL,
  budget INT DEFAULT NULL,
  homepage varchar(1000) DEFAULT NULL,
  overview varchar(1000) DEFAULT NULL,
  popularity decimal(12,6) DEFAULT NULL,
  release_date date DEFAULT NULL,
  revenue BIGINT DEFAULT NULL, 
  runtime INT DEFAULT NULL,
  movie_status varchar(50) DEFAULT NULL,
  tagline varchar(1000) DEFAULT NULL,
  vote_average decimal(4,2) DEFAULT NULL,
  vote_count INT DEFAULT NULL,
  CONSTRAINT pk_movie PRIMARY KEY (movie_id)
);

CREATE TABLE movies.genre 
(
  genre_id INT NOT NULL,
  genre_name varchar(100) DEFAULT NULL,
  CONSTRAINT pk_genre PRIMARY KEY (genre_id)
);

CREATE TABLE movies.gender 
(
  gender_id INT NOT NULL,
  gender varchar(20) DEFAULT NULL,
  CONSTRAINT pk_gender PRIMARY KEY (gender_id)
);

CREATE TABLE movies.keyword 
(
  keyword_id INT NOT NULL,
  keyword_name varchar(100) DEFAULT NULL,
  CONSTRAINT pk_keyword PRIMARY KEY (keyword_id)
);

CREATE TABLE movies.person 
(
  person_id INT NOT NULL,
  person_name varchar(500) DEFAULT NULL,
  CONSTRAINT pk_person PRIMARY KEY (person_id)
);

CREATE TABLE movies.movie_cast 
(
  movie_id INT DEFAULT NULL,
  person_id INT DEFAULT NULL,
  character_name varchar(400) DEFAULT NULL,
  gender_id INT DEFAULT NULL,
  cast_order INT DEFAULT NULL,
  CONSTRAINT fk_mca_gender FOREIGN KEY (gender_id) REFERENCES movies.gender (gender_id),
  CONSTRAINT fk_mca_movie FOREIGN KEY (movie_id) REFERENCES movies.movie (movie_id),
  CONSTRAINT fk_mca_per FOREIGN KEY (person_id) REFERENCES movies.person (person_id)
);

CREATE TABLE movies.movie_genres 
(
  movie_id INT DEFAULT NULL,
  genre_id INT DEFAULT NULL,
  CONSTRAINT fk_mg_genre FOREIGN KEY (genre_id) REFERENCES movies.genre (genre_id),
  CONSTRAINT fk_mg_movie FOREIGN KEY (movie_id) REFERENCES movies.movie (movie_id)
);

CREATE TABLE movies.movie_keywords 
(
  movie_id INT DEFAULT NULL,
  keyword_id INT DEFAULT NULL,
  CONSTRAINT fk_mk_keyword FOREIGN KEY (keyword_id) REFERENCES movies.keyword (keyword_id),
  CONSTRAINT fk_mk_movie FOREIGN KEY (movie_id) REFERENCES movies.movie (movie_id)
);

COMMIT;