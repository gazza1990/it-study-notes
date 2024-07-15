<!DOCTYPE html>
<html>
<head>
	<title>Manuale - PHP</title>
</head>
<body>
<?php

	// Cookies - Approfonditi più in basso, sono da dichiarare all'inizio del file
	$expire = 30*24*60*60;
	setcookie("lang", "it", time() + $expire);

	// Commento
	# Commento
	/* Commento
	Multiriga */
	
	// Stampa a video
	echo "Hello World!";
	
	// Variabili e tipi semplici
	$nome = 'Mattia';					// Stringa
	$cognome = "Gazzaniga";				// Stringa
	$eta = 34;							// Integer
	$altezza = 1.95;					// Float
	$maschio = true;					// Booleano
	
	echo "$nome è " . gettype($nome) . " mentre eta è " . gettype($eta) . "<br />";
	
	echo "Echo con doppi Apici: Mi chiamo $nome, ho $eta anni<br />";
	echo 'Echo con singoli Apici: Mi chiamo $nome, ho $eta anni<br />';
	
	//Booleani
	$isTrueInt = 1;
	$isTrueInt = -1; 
	$isFalseInt = 0;
	$isTrueStr = "abc";
	$isFalseStr = "";
	$isFalseNull = Null;
	$isFalseFloat = 0.0;
	
	// Interi
	// Costanti PHP_INT_MAX e PHP_INT_MIN per verificare valori massimo e minimo
	// Rappresentazione in basi diverse
	$dec = 40; // decimale
	$oct = 0321; // ottale
	$hex = 0xBC; // esadecimale
	$bin = 0b11111; // binario
	$neg = -125; // negativo

	echo "$dec<br />"; // 40
	echo "$oct<br />"; // 209
	echo "$hex<br />"; // 188
	echo "$bin<br />"; // 31
	echo "$neg<br />"; // -125
	
	// Float
	$float1 = 123.30; // Sintassi con il punto
	$float2 = 120e-3; // Sintassi con esponente, vale come un'elevazione alla potenza di 10, per esempio 10 alla -3

	echo "$float1\n"; // 123.30
	echo "$float2\n"; // 0.12, ovvero 120 * 10 alla -3
	
	// Alcune funzioni con Float
	echo ceil($float1)."<br />";
	echo floor($float1)."<br />";
	echo pi()."<br />";
	echo sqrt($float1)."<br />";
	echo "Altre all'indirizzo <a href='https://www.php.net/manual/en/ref.math.php' target='blank'>https://www.php.net/manual/en/ref.math.php</a><br />";
	
	// Stringhe
	$car = "Ferrari";
	$model = 'F40';

	echo "Auto $car <br />"; // Auto Ferrari
	echo "Modello $model <br />"; // Modello F40
	
	// è possibile utilizzare le sequenze di escape \n \r \t cambiando il contesto da HTML a testo con header('Content-type: text/plain');
	// è possibile stampare i caratteri \ " $ utilizzando il backslash	
	echo "Test con backslash: \\ \$ \" <br />";
	
	// Le stringhe possono essere trattate come array di caratteri.
	echo "Iniziali: " . $nome[0] . $cognome[0] . "<br />";
	
	
	//Alcune funzioni
	echo strlen($nome)."<br />"; // 6
	echo strpos($nome, "tia")."<br />"; // 3
	echo substr($cognome, 2, 4)."<br />"; // zzan
	echo str_replace('tia', 'teo', $nome)."<br />"; // Matteo
	echo "Altre all'indirizzo <a href='https://guidaphp.it/base/funzioni/stringhe' target='blank'>https://guidaphp.it/base/funzioni/stringhe</a><br />";
	
	// Costanti
	define('NAZIONALITA', 'Italiana'); // Costante (Non modificabile)
	echo NAZIONALITA . "<br />"; // Richiamabile senza $
	
	// Costanti Magihe
	echo __LINE__ . "<br />";
	echo __FILE__ . "<br />";
	echo __FUNCTION__ . "<br />";
	echo __CLASS__ . "<br />";
	echo __METHOD__ . "<br />";
	
	// Array
	$carBrand = ['Ferrari', 'Porsche', 'Lamborghini'];
	$carBrand = array('Ferrari', 'Porsche', 'Lamborghini');

	echo "$carBrand[0]<br />";
	echo "$carBrand[1]<br />";
	echo "$carBrand[2]<br />";
	
	// Si può accedere all'elemento di un array tramite alcune funzioni speciali che tengono traccia degli elementi tramite un puntatore
	echo "Elemento corrente ".current($carBrand).", ".key($carBrand)."<br />"; // Elemento corrente Ferrari, 0
	next($carBrand);
	echo "Elemento corrente ".current($carBrand).", ".key($carBrand)."<br />"; // Elemento corrente Porsche, 1
	prev($carBrand);
	echo "Elemento corrente ".current($carBrand).", ".key($carBrand)."<br />"; // Elemento corrente Ferrari, 0
	reset($carBrand);
	echo "Elemento corrente ".current($carBrand).", ".key($carBrand)."<br />"; // Elemento corrente Ferrari, 0
	end($carBrand);
	echo "Elemento corrente ".current($carBrand).", ".key($carBrand)."<br />"; // Elemento corrente Lamborghini, 2	
	
	// Aggiunta nuovi elementi
	$carBrand[] = "Fiat"; 
	$carBrand[20] = "Renault";
	
	// Modifica elemento
	$carBrand[1] = "Peugeot";
	
	// Eliminare elemento
	unset($carBrand[2]); //L'array non viene risistemato, l'indice 2 diventa inesistente
	$carBrand = array_values($carBrand); // Riassegna gli indici dell'array, utile in seguito a cancellazioni
	
	// Stampa a video di un array
	print_r($carBrand);
	echo "<br />";
	var_dump($carBrand);
	echo "<br />";
	
	// Array associativi
	// Array formati da coppie chiave=>valore
	$array = [
		'nome' => 'Mario', 
		'cognome' => 'Rossi', 
		'eta' => '35', 
		'lavoro' => 'programmatore'
	];

	echo $array['nome']."<br />"; // Mario
	echo "$array[lavoro]<br />"; // Programmatore
	// l'istruzione echo "$array['lavoro']"; genererebbe un errore di sintassi
	
	$langs = [
		'it' => 'italiano',
		'en' => 'inglese',
		'es' => 'spagnolo',
		'fr' => 'francese',
		'de' => 'tedesco',
		'ru' => 'russo'
	];
	
	// Funzione in_array per verificare se un elemento è presente fra i valori dell'array
	if (in_array('spagnolo', $langs)) {
		echo "Lingua supportata";
	} else {
		echo "Lingua non supportata";
	}
	echo "<br />";
	
	// Array multidimensionali
	// Array che contengono altri array
	
	$maps = [
		"valletta" => [
			"longitudine" => "35.917973",
			"latitudine" => "14.449943"
		],
		"londra" => [
			"longitudine" => "52.9085300",
			"latitudine" => "-3.8257400"
		],
		"roma" => [
			"longitudine" => "41.924824",
			"latitudine" => "12.497353"
		]
	];

	// Ordinamento Array

	$names = ["Mario", "Alberto", "Pippo", "Tizio", "Caio", "Sempronio"];

	sort($names); // Ordinamento Crescente
	var_dump($names);
	echo "<br />";
	rsort($names); // Ordinamento Decrescente
	var_dump($names);
	echo "<br />";

	$countries = [
		"en" => "Inghilterra", 
		"de" => "Germania", 
		"es" => "Spagna", 
		"it" => "Italia", 
		"fr" => "Francia"
	];
	
	asort($countries); // Ordinamento crescente per valore
	var_dump($countries);
	echo "<br />";
	arsort($countries); // Ordinamento decrescente per valore
	var_dump($countries);
	echo "<br />";
	ksort($countries);
	var_dump($countries); // Ordinamento crescente per chiave
	echo "<br />";
	krsort($countries);
	var_dump($countries); // Ordinamento decrescente per chiave
	echo "<br />";

	// Operatori
	// Ci sono operatori uniari, binari e ternari
	// https://guidaphp.it/base/operatori

	// Operatori di incremento / decremento
	$x = 15;
	echo ++$x; // 16
	echo "<br />";
	echo $x;   // 16
	echo "<br />";

	$x = 15;
	echo $x++; // 15
	echo "<br />";
	echo $x;   // 16	
	echo "<br />";

	// Operatori di stringa

	$x = "Hello ";
	$y = "World!";
	echo $x . $y; // Hello World!
	echo "<br />";
	
	$x .= $y;
	echo $x; // Hello World!
	echo "<br />";

	// Operatori artimetici

	$x = 9;
	$y = 2;
	$z = 4;

	echo $x + $y; // 11
	echo "<br />";
	echo $x - $y; // 7
	echo "<br />";
	echo $x * $y; // 18
	echo "<br />";
	echo $x / $y; // 4.5
	echo "<br />";
	echo $x % $y; // 1
	echo "<br />";
	echo $x + $y * $z; // 17
	echo "<br />";
	echo ($x + $y) * $z; // 44
	echo "<br />";

	// Operatori di assegnazione

	$x = 30;
	echo $x; // 30
	echo "<br />";

	$x += 30;
	echo $x; // 60
	echo "<br />";

	$x -= 20;
	echo $x; // 40
	echo "<br />";

	$x *= 3;
	echo $x; // 120
	echo "<br />";

	$x /= 10;
	echo $x; // 12
	echo "<br />";

	$x %= 5;
	echo $x; // 2
	echo "<br />";

	$x **= 4;
	echo $x; // 16
	echo "<br />";

	$x = 'Ciao';
	$y = ' a tutti!';

	$x .= $y;

	echo $x; // Ciao a tutti!
	echo "<br />";

	// Operatori di confronto
	
	$x = 10;
	$y = "10";

	var_dump($x == $y);  // Uguale a - bool(true)
	echo "<br />";
	var_dump($x === $y); // Uguale e dello stesso tipo di - bool(false)
	echo "<br />";
	var_dump($x != $y);  // Non uguale a - bool(false)
	echo "<br />";
	var_dump($x <> $y);  // Non uguale a (identico a !=) - bool(false)
	echo "<br />";
	var_dump($x !== $y); // Non uguale e di tipo diverso di - bool(true)
	echo "<br />";
	var_dump($x < $y);   // Minore di - bool(false)
	echo "<br />";
	var_dump($x <= $y);  // Minore o uguale di - bool(true)
	echo "<br />";
	var_dump($x <=> $y); //Operatore spaceship introdotto con PHP 7. Confronta $x e $y e restituisce 1 se il $x > $y, -1 se $x < $y, 0 se $x == $y.
	echo "<br />";

	// Operatori logici
	$score = 15;

	if ($score > 0 && $score <= 100) {
		echo "Vero se $score è maggiore di 0 E minore di 100";
	}
	echo "<br />";

	if ($score > 0 || $score <= 100) { //
		echo "Vero se $score è maggiore di 0 OPPURE minore di 100";
	}
	echo "<br />";

	if (!$score) { //
		echo "Vero se $score è uguale a 0";
	}
	echo "<br />";

	if ($score > 0 xor $score <= 100) { //
		echo "Vero se $score è maggiore di 0 e maggiore di 100 OPPURE se minore di 0 e minore di 100";
	}
	echo "<br />";

	// Operatori Bitwise
	// Gli operatori bitwise agiscono direttamente sui bit dei numeri.
	// Confronto dei numeri bit a bit.

	$x = 127; // 1111111 in base 2
	$y = 85; // 1010101 in base 2

	var_dump(decbin($x & $y)); // string(7) 1010101
	echo "<br />";
	var_dump(decbin($x | $y)); // string(7) 1111111
	echo "<br />";
	var_dump(decbin($x ^ $y)); // string(6) 101010
	echo "<br />";
	var_dump(decbin(~ $x)); // string(64) 1111111111111111111111111111111111111111111111111111111110000000
	echo "<br />";
	var_dump(decbin($x << 1)); // string(8) 11111110
	echo "<br />";
	var_dump(decbin($y >> 2)); // string(5) 10101
	echo "<br />";

	// Strutture di controllo

	// if elseid else

	$x = 10;
	$y = 8;

	if ($x > $y){
		echo "$x è maggiore di $y";
	} elseif ($x == $y){
		echo "$x è uguale a $y";
	} else {
		echo "$x è minore di $y";
	}
	echo "<br />";

	// if operatore ternario

	echo ($x >= $y) ? "$x è maggiore o uguale a $y" : "$x è minore di $y";
	echo "<br />";

	// switch

	$day = 'sabato';

	switch ($day) {
		case 'lunedì':
			echo "Oggi è lunedì";
			break;
		case 'martedì':
			echo "Oggi è martedì";
			break;
		case 'mercoledì':
			echo "Oggi è mercoledì";
			break;
		case 'giovedì':
			echo "Oggi è giovedì";
			break;
		case 'venerdì':
			echo "Oggi è venerdì";
			break;
		case 'sabato':
			echo "Oggi è sabato";
			break;
		case 'domenica':
			echo "Oggi è domenica";
			break;
		default:
			echo "Impossibile stabilire che giorno è oggi!";
	}
	// Output: Oggi è sabato
	echo "<br />";

	// sintassi alternative
	$isLogged=True;

?>
    <?php if ($isLogged):?>
        Utente loggato
    <?php else:?>
        Utente non loggato
    <?php endif;?>

<?php

	// Funziona anche con lo switch
	echo "<br />";

	// while

	$x = 0;

	while($x < 5){
		$x++;
	}
	echo $x;
	echo "<br />";

	// do while

	$x = 0;

	do{
		$x++;
	} while ($x < 5);
	echo $x;
	echo "<br />";

	// for

	$colors = ['blu', 'giallo', 'verde', 'rosso', 'bianco'];

	for ($i = 0; $i < count($colors); $i++) {
		echo "$colors[$i], ";
	}
	echo "<br />";

	// Il counter può essere assegnato ad una variabile all'interno del ciclo for in modo che non venga ricalcolato ad ogni giro
	for ($i = 0, $count = count($colors); $i < $count; $i++) {
		echo "$colors[$i], ";
	}
	echo "<br />";

	// foreach
	// Comodo per array associativi e multidimensionali

	$colors = ['blu', 'giallo', 'verde', 'rosso', 'bianco'];

	// Prima versione

	foreach ($colors as $color) {
		echo "$color, ";
	}
	echo "<br />";

	// Seconda versione

	$days = [
		'lun' => 'lunedì',
		'mar' => 'martedì',
		'mer' => 'mercoledì', 
		'gio' => 'giovedì', 
		'ven' => 'venerdì', 
		'sab' => 'sabato', 
		'dom' => 'domenica'
	];

	foreach ($days as $key => $value) {
		echo "$key = $value, ";
	}
	echo "<br />";

	// Break e continue
	// L'istruzione break consente di terminare l'esecuzione di un ciclo
	// L'istruzione continue consente di saltare una o più iterazioni, senza interrompere l'esecuzione del ciclo

	// Sintassi Alternativa

	$menu = [
		'home' => "https://www.sito.it",
		'about' => "https://www.sito.it/about",
		'blog' => "https://www.sito.it/blog",
		'contatti' => "https://www.sito.it/contatti"
	];

?>

		<?php foreach ($menu as $key => $value):?>
        <li><a href="<?php echo $value;?>"><?php echo $key;?></a></li>
        <?php endforeach;?>	

<?php

	// Include e Require
	// Servono per includere file esterni nello script ed eseguirli
	// Utile per non ripetere il codice su varie pagine (nel caso di pagine web), in quanto
	// si possono inserire in alcuni file a parte l'header, il footer, la navbar, etc.. per
	// poi richiamarli nelle altre pagine attraverso include o require.

	// La sintassi dei due comandi è identica: 
	// include('nomescript.php');
	// require('nomescript.php');

	// La differenza è la seguente:
	// Con include, se il file da includere non viene trovato, viene mostrato un errore, ma il codice prosegue
	// Con require, se il file da includere non viene trovato, viene mostrato un errore fatale e il codice si interrompe

	// Con include, il messaggio di errore viene mostrato solamente se è abilitata la visualizzazione degli
	// errori da php.ini o con la seguente istruzione
	// ini_set("display_errors", 1);

	// Con require, il messaggio di errore viene mostrato anche se non è abilitata la visualizzazione degli errori

	// Include si usa quando si vogliono includere dei files non strettamente essenziali per il funzionamento 
	// del programma, diversamente è preferibile usare require.

	// Esistono anche i costrutti include_once e require_once, che risolve il problema dell'inclusione accidentale
	// dello stesso file più volte all'interno di uno script, questi costrutti includono il file solo una volta

	// https://guidaphp.it/base/strutture-di-controllo/include-require

	// Return
	// 1. Se viene chiamato all'interno di una funzione, il costrutto return termina immediatamente l'esecuzione della 
	//    funzione corrente e restituisce il suo argomento come valore della funzione chiamata.
	// 2. Se viene chiamato a livello globale, ossia non all'interno di una funzione, verrà terminata l'esecuzione 
	//    dello script corrente.
	// 3. Nel caso in cui lo script contenente return sia un file incluso tramite i costrutti include o require, il 
	//    controllo viene passato al file chiamante e il valore dato da return verrà restituito come valore della chiamata 
	//    a include, quindi potrà essere memorizzato dentro una variabile.
	
	// https://guidaphp.it/base/strutture-di-controllo/return

	// Funzioni
	
	// Dichiarazione
	function rectanglePerimeter($base, $height)
	{
		$perimeter = ($base + $height) * 2;
		return $perimeter;
	}

	// Chiamata
	$perimeter = rectanglePerimeter(4, 5);

	var_dump($perimeter); // int(18)

	echo "<br />";

	// Per aggiungere dei campi opzionali bisogna impostare un valore di default
	// I parametri opzionali vanno inseriti sempre per ultimi
	function sum($val1, $val2 = 4)
	{
		return $val1 + $val2;
	}
	
	echo sum(4); // 8
	echo "<br />";

	// Funzioni ricorsive
	// Sono funzioni che richiamano se stesse più volte fino a quando viene soddisfatta una condizione
	$num = 5;

	function factorial($num)
	{
		if ($num == 1) {
			return 1;
		} else {
			return $num * factorial($num - 1);
		}
	}
	
	echo "Il fattoriale di $num è ".factorial($num); // 120
	echo "<br />";

	// Scope delle variabili
	// Le variabili dichiarate dentro una funzione sono accessibili solo da dentro la stessa
	// Le variabili dichiarate fuori da una funzione NON sono accessibili dalle funzioni
	// Si possono dichiarare delle variabili Globali con l'istruzione "global", ma meglio evitarlo
	// in quanto rende difficile l'individuazione di errori

	function test()
	{
		$x = 'dentro';
		echo $x;
	}
	echo "<br />";

	$x = 'fuori';

	test(); // dentro

	echo $x; // fuori
	echo "<br />";

	// Passaggio variabile a funzione per riferimento
	// è possibile passare ad una funzione il riferimento (locazione di memoria) di una variabile, in
	// modo da renderla accessibile/modificabile dall'interno della funzione. Per farlo si utilizz
	// il carattere "&".

	function myFunction(&$x)
	{
		$x = 5;
		return $x;
	}
	
	$x = 10;

	echo $x; // 10
	echo "<br />";

	myFunction($x);

	echo $x; // 5
	echo "<br />";

	// Funzioni anonime
	// Sono funzionie che non hanno nome, utili nei casi in cui è necessario passare una funzione
	// come argomento di un'altra funzione.
	// Le funzioni anonime chiedono un ";" dopo le parentesi graffe, ciò indica che le funzioni
	// anonime sono trattate come espressioni, a differenza delle funzioni normali che sono
	// considerate dei costrutti.

	// Si possono assegnare ad una variabile
	$sum = function($val1, $val2) {
		return $val1 + $val2;
	};
	
	echo $sum(3, 4); // Output: 7
	echo "<br />";

	// Si possono usare come funzioni di callback
	// Una funzione di callback è una funzione che è possibile passare a un'altra funzione come argomento.
	$array = [2, 4, 6];

	array_map(function($val) {
		echo ($val * 2)."\n";
	}, $array);
	echo "<br />";

	// La precedente funzione può essere scritta anche nel seguente modo

	$array = [2, 4, 6];

	$double = function($val) {
		echo ($val * 2)."\n";
	};

	array_map($double, $array);
	echo "<br />";

	// Funzioni anonime e Closure
	// È possibile utilizzare le funzioni anonime per la creazione di closure (letteralmente chiusura).
	// Con una closure è possibile utilizzare variabili esterne all'interno di una funzione anonima, 
	// anche se tali variabili non sono accessibili all'interno della stessa, in quanto hanno ambito locale.

	function helloFunction() {

		$hello = "buongiorno";
	
		return function($name) use ($hello) { // la keyword "use" permette di utilizzare la variabile esterna $hello nella funzione anonima
			$hello = ucfirst($hello); 
			return "$hello $name!";
		};
	};
	
	$helloFunction = helloFunction();
	echo $helloFunction("Programmatori PHP"); // Buongiorno Programmatori PHP!
	echo "<br />";

	// Dichiarazione dei tipi
	// Da PHP 7 è possibile indicare il tipo di dati in ingresso e in uscita nelle funzioni.
	// Per attivare il controllo sul tipo di dati si usa l'istruzione "declare(strict_types=1);" che deve essere messa all'inizio dello script
	// Dopo aver attivato il controllo sui tipi, PHP può restituire degi errori TypeError quando si sbaglia il tipo di dato.



	// Dichiarazione del tipo in ingresso
	function printHello(string $name) // la funzione accetta solo valori di tipo string
	{
		echo "Hello $name";
	}

	printHello('Flavio');
	echo "<br />";
	// Dichiarazione del tipo in uscita

	function somma(int $a, int $b): int
	{
		return $a + $b;
	}

	echo somma(4, 9);
	echo "<br />";


	// Funzioni per gestire gli Array
	// Elenco con descrizioni ed esempi: https://guidaphp.it/base/funzioni/array

	// Funzioni per gestire le Stringhe
	// Elenco con descrizioni ed esempi: https://guidaphp.it/base/funzioni/stringhe

	// Variabili SuperGlobali
	// Approfondimento ed elenchi: https://guidaphp.it/base/variabili-superglobali
	// Sono variabili speciali che consentono di gestire i dati inviati tramite un form e di recuperare informazioni
	// sul browser (client) e sul sito (server)
	// Le variabili Superglobali sono:
	// $_SERVER: contiene tutto ciò che viene inviato dal server nella riposta HTTP.
	// $_GET: contiene i parametri passati tramite il metodo GET.
	// $_POST: contiene i parametri passati tramite il metodo POST.
	// $_REQUEST: contiene i valori degli array $_GET, $_POST e $_COOKIE.
	// $_COOKIE: contiene i cookie inviati dal client al server.
	// $_SESSION: contiene variabili registrate come variabili di sessione.
	// $_FILES: contiene informazioni sui file inviati tramite il metodo POST.

	echo($_SERVER['HTTP_HOST'])."<br>";
	echo($_SERVER['HTTP_USER_AGENT'])."<br>";
	echo($_SERVER['REMOTE_ADDR'])."<br>";
	echo($_SERVER['SERVER_PROTOCOL'])."<br>";
	echo($_SERVER['REQUEST_METHOD'])."<br>";
	echo($_SERVER['QUERY_STRING'])."<br>";

	// La variabile $_REQUEST può essere utilizzata per recuperare i dati inviati tramite GET e POST
	if (isset($_POST['submit'])) {
		var_dump($_REQUEST);
	} else {
?>

	<form action="" method="post">
		<input type="text" name="name" value="Mario">
		<input type="text" name="lastname" value="Rossi">
		<input type="submit" name="submit" value="submit">
	</form>

<?php 
	}

	// Header
	// La funzione header() di PHP consente l'invio di intestazioni HTTP al browser.
	// Un esempio è header('Location: /'); che consente di reindirizzare l'utente verso l'URL specificato dopo i due punti.

	// Cookies
	// https://guidaphp.it/base/variabili-superglobali/cookie
	// setcookie(string $name, string $value = "", int $expire = 0, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false);
	// $name è il nome del cookie. Deve essere di tipo stringa ed è l'unico parametro obbligatorio, i successivi sono opzionali.
	// $value è il valore del cookie. Deve essere di tipo stringa.
	// $expire è la data di scadenza nel formato Unix timestamp. Trascorso tale tempo i cookie saranno inaccessibili. Il valore di default è 0.
	// $path specifica il percorso in cui il cookie sarà disponibile. Se impostato su /, il cookie sarà disponibile all'interno dell'intero dominio.
	// $domain specifica il dominio in cui il cookie è disponibile, ad esempio www.dominio.it
	// $secure se impostato a 1, specifica che il cookie può essere inviato solo tramite HTTPS.
	// $httpOnly è la modalità che rende il cookie non accessibile via Javascript.

	// Essendo un header, il cookie deve essere impostato prima di qualsiasi output HTML (anche una riga vuota), altrimenti viene generato un 
	// avviso come il seguente (solo se la direttiva output_buffering del php.ini è impostata a Off)
	// Warning: Cannot modify header information - headers already sent by...

	echo $_COOKIE["lang"];

	// Per modificare un cookie possiamo semplicemente sovrascrivere il vecchio valore con il nuovo tramite la funzione setcookie()
	// Va sempre fatto all'inizio della pagina php.

	// Per eliminare un cookie è sufficiente impostare il suo valore a "" (stringa vuota), oppure a 0 o qualsiasi valore che PHP interpreta come false.
	// Per essere certi di eliminare il cookie, dobbiamo passare il parametro $expire con un valore che fa riferimento al passato, come mostrato di seguito
	// $expire = time() -3600; // impostiamo $expire a un'ora fa
	// secookie('lang', '', $time);

	// Sessioni
	// Le sessioni sono un insieme di informazioni memorizzate in un file che si trova sul server in una directory designata al salvataggio delle sessioni.
	// A differenza dei cookies sono più sicure in quanto non memorizzano nulla sul computer dell'utente.
	// Ad ogni sessione viene assegnato un identificativo PHPSESSID rappresentato da una stringa casuale generata tramite una funzione di hash.
	// Quando uno script PHP desidera recuperare il valore da una variabile di sessione, PHP preleva il valore dell'identificatore di sessione PHPSESSID e lo 
	// confronta con quello memorizzato sul server per vedere se sono uguali. In caso affermativo la sessione continua, altrimenti viene interrotta.
	// Una sessione può anche terminare quando l'utente chiude il browser o dopo aver fatto il logout dalla propria area riservata, oppure si interrompe 
	// automaticamente dopo un certo periodo di inattività.
	// La posizione della directory contenente i file di sessione è determinata da un'impostazione del file php.ini chiamata session.save_path.
	// Le sessioni vengono utilizzate per le seguenti funzionalità:
	// 1. Area riservata sito
	// 2. Carrello spesa sito e-commerce
	// 3. Messaggi flah (messaggi di errore o di successo dopo la compilazione di un Form)

	// Per inizializzare una sessione si usa la funzione session_start(), da richiamare all'inizio dello script, questa funzione deve essere presente in tutti
	// gli script in cui si vogliono gestire le sessioni

	
	
?>
</body>
</html>