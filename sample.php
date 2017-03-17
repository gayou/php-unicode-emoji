<?php

require_once "src/UnicodeEmoji.php";

$emoji = new UnicodeEmoji();
$list = $emoji->getEmojiList();
echo "<pre style='font-size:20px'>";
foreach ($list as $key => $value) {
	echo $key." ";
	echo $emoji->get($key);
	// echo $emoji->get($key, UnicodeEmoji::MODE_EMOJIONE);
	echo "\n";
}
echo "</pre>"
?>