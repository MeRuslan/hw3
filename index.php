<meta charset="utf8">
<title>Интернет магазин</title>
</head>
<body>
<div align="left">
<form action="" method="post" >
имя:
<input type="text" name="fio">
<br><br>

комментарий
<br>
<textarea name="comments" rows=5 cols=70></textarea>
<br><br>

наименование товара 
<br>
<select name="menu" size="1">
<option value="guitar">гитара</option>
<option value="cello">виолончель</option>
<option value="piano">фортепиано</option>
<option value="pipe">дудка</option>
</select>
<br>
количество инструментов:
<input type="number" name="amount">
<br><br>

<input type="submit"  value="заказать">
<br><br>


 <?php
 
   if (empty($_POST['fio']) or empty($_POST['amount']) or empty($_POST['comments'])) exit('заполните все поля');
   if (isset($_POST['fio']) and isset($_POST['amount'])) 
   {

		$fio=$_POST['fio']; 
		$amount=(int)$_POST['amount']; 
		$comments=$_POST['comments']; 
		$menu=$_POST['menu']; 
		
		if ($menu == "guitar"){ $price = 26900; $item = "гитара";}
		if ($menu == "cello"){ $price = 70900; $item = "виолончель";}
		if ($menu == "piano"){ $price = 26790; $item = "фортепиано";}
		if ($menu == "pipe"){ $price = 1499; $item = "дудка";}
		$sum = $amount * $price;


		try {
		    $csv = new CSV("1.csv");

		    $arr = array("$fio;$amount;$item;$price;$sum;");
		    $csv->setCSV($arr);
		}
		catch (Exception $e) { //Если csv файл не существует, выводим сообщение
		    echo "Ошибка: " . $e->getMessage();
		}

		/*
		echo "ваше имя:  <b> $fio</b><br>"; 
		echo "товар:  <b> $item</b><br>";
		echo "цена: <b> $price</b><br>";
		echo "количество: <b> $amount</b><br>";
		echo "итого к оплате: <b> $sum </b><br>";
		*/
   }
   ?>


<table>
    <tr>
        <th>имя</th>
        <th>количество</th>
        <th>товар</th>
        <th>цена</th>
        <th>итого</th>
    </tr>
<?php
$handle = fopen("1.csv", "r");
$size=filesize("1.csv");
while (($data = fgetcsv($handle, $size, ";")) != FALSE) {
$row[]=$data;
}
fclose($handle);
foreach($row as $item){
    ?>

    <tr>
        <td><?=$item[0]?></td>
        <td><?=$item[1]?></td>
        <td><?=$item[2]?></td>
        <td><?=$item[3]?></td>
        <td><?=$item[4]?></td>
        <td><?=$item[5]?></td>
    </tr>


<?php
}
?>


 
<?php
/**
 * Класс для работы с csv-файлами 
 * @author дизайн студия ox2.ru  
 */
class CSV {
 
    private $_csv_file = null;
 
    /**
     * @param string $csv_file  - путь до csv-файла
     */
    public function __construct($csv_file) {
        if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        }
        else { //Если файл не найден то вызываем исключение
            throw new Exception("Файл " +$csv_file+" не найден"); 
        }
    }
 
    public function setCSV(Array $csv) {
        //Открываем csv для до-записи, 
        //если указать w, то  ифнормация которая была в csv будет затерта
		$handle = fopen($this->_csv_file, "a"); 
         
        foreach ($csv as $value) { //Проходим массив
            //Записываем, 3-ий параметр - разделитель поля
            fputcsv($handle, explode(";", $value), ";"); 
        }
        fclose($handle); //Закрываем
    }
 
    /**
     * Метод для чтения из csv-файла. Возвращает массив с данными из csv
     * @return array;
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения
 
        $array_line_full = array(); //Массив будет хранить данные из csv
        //Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) { 
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle); //Закрываем файл
        return $array_line_full; //Возвращаем прочтенные данные
    }
 
}
?>
