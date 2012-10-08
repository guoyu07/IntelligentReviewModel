<?php

for($k=1;$k<28;$k++){
echo 'ALTER TABLE questions_m MODIFY Limage'.$k.' INT DEFAULT NULL;<br>'.
	 'UPDATE questions_m SET Limage'.$k.'=NULL;<br>'.
	 'ALTER TABLE questions_m ADD FOREIGN KEY (`Limage'.$k.'`) REFERENCES `images` (`id`);<br>'.
	 'ALTER TABLE questions_m MODIFY Rimage'.$k.' INT DEFAULT NULL;<br>'.
	 'UPDATE questions_m SET Rimage'.$k.'=NULL;<br>'.
	 'ALTER TABLE questions_m ADD FOREIGN KEY (`Rimage'.$k.'`) REFERENCES `images` (`id`);<br>';
}
?>
