<?Php

echo "pregmatch: "+preg_match("/@diall.ru/", 'booogie@diall.ru');
echo "<br>";
echo "strpos: "+strpos("booogie@hiall.ru", '@diall.ru') !== false;


?>