#DROP TABLE questions;
#RENAME TABLE questions TO question_list;
RENAME TABLE webct TO questions;
RENAME TABLE webct_mc TO questions_mc;
RENAME TABLE webct_m TO questions_m;
RENAME TABLE webct_c TO questions_c;
RENAME TABLE webct_p TO questions_p;
RENAME TABLE webct_s TO questions_s;
RENAME TABLE concept3 TO concepts;
ALTER TABLE questions_c change formula formula1 varchar(256);
ALTER TABLE questions_c add column (formula2 varchar(256),
				formula3 varchar(256),
				formula4 varchar(256)
);
ALTER TABLE questions_c add column (weight1 int(11),
				weight2 int(11),
				weight3 int(11),
				weight4 int(11)
);
ALTER TABLE questions_c add column (text1 varchar(256),
				text2 varchar(256),
				text3 varchar(256),
				text4 varchar(256)
);
UPDATE questions_c SET weight1=100; 
UPDATE questions SET answers=1 WHERE qtype="c";
CREATE TABLE IF NOT EXISTS questions_tags (
  questions_id int NOT NULL,
  tags_id int NOT NULL,
  PRIMARY KEY (questions_id,tags_id),
  FOREIGN KEY (questions_id) REFERENCES questions(id),
  FOREIGN KEY (tags_id) REFERENCES tags(id)
);
CREATE TABLE IF NOT EXISTS concepts_tags (
  concepts_id int NOT NULL,
  tags_id int NOT NULL,
  PRIMARY KEY (concepts_id,tags_id),
  FOREIGN KEY (concepts_id) REFERENCES questions(id),
  FOREIGN KEY (tags_id) REFERENCES tags(id)
);

#ALTER TABLE questions_mc add column (formula2 varchar(256),
