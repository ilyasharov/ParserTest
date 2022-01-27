<!DOCTYPE html>
<html>
<head>
<title>Парсинг</title>
</head>
<body>
    
<?php 
    
    include_once('lib/simple_html_dom.php');    // Библиотека для парсинга HTML
    include_once('lib/curl_query.php');
    include_once('lib/db.php');
    
    $page = curlGetPage('https://etp.eltox.ru/registry/procedure');
    $html = str_get_html($page);

    // Строка для сравнения
    $strCompare1 = 'Запрос цен (котировок)';

    /************Получение числа записей***********/
    foreach($html->find('.procedure-list') AS $element){
        $number = $element->find('a', 1);
    }
    $numberPages = $number->plaintext;
    $countPages = ltrim($numberPages, '№ ');
    /************************************/

    for( $i = $countPages; $i > 1; $i--){

        $page = curlGetPage('https://etp.eltox.ru/procedure/read/' . $i . '/');
        $html = str_get_html($page);
        $link = 'https://etp.eltox.ru/procedure/read/' . $i . '/';
        
        foreach($html->find('.tab-content') AS $element){

            // Сравнение строк
            $strCompare2 = $element->find('span', 5);
            $result = strcmp($strCompare1, $strCompare2);
            
            if($result == 1){
    
                $amount = $element->find('span', 0);
                $ooc = $element->find('span', 2);
                $email = $element->find('td', 11);
                
                $arr = [
                    'amount' => $amount->plaintext,
                    'ooc' => $ooc->plaintext,
                    'email' => $email->plaintext,
                    'link' => $link,
                ];

                echo "Номер процедуры: ", $arr['amount'], "</br>";
                echo "OOC номер: ", $arr['ooc'], "</br>";
                echo "E-mail: ", $arr['email'], "</br>";
                echo "Ссылка: ", $arr['link'], "</br>";
                echo "</br>";

                $db->query("INSERT IGNORE INTO posts (`amount`, `ooc`, `email`, `link`) 
                    VALUES ('{$arr['amount']}','{$arr['ooc']}','{$arr['email']}','{$arr['link']}')");
            }
        }
    }
?>

</body>

</html>
            