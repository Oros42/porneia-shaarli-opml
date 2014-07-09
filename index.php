<?php
#           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
#                   Version 2, December 2004
#Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>
#Everyone is permitted to copy and distribute verbatim or modified
#copies of this license document, and changing it is allowed as long
#as the name is changed.
#           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
#  TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
# 0. You just DO WHAT THE FUCK YOU WANT TO.
error_reporting(0);
//http://porneia.free.fr/pub/links/ou-est-shaarli.html
if(((fileatime('shaarlis.opml')+60*60*24*15) < time()) OR !file_exists('shaarlis.opml')) {
	$file = file_get_contents('http://porneia.free.fr/pub/links/ou-est-shaarli.html');
	if(!empty($file)){
		// function http://www.developpez.net/forums/d1405041/php/bibliotheques-frameworks/xml/dom/conversion-table-html-array-php/
		$dom = new DOMDocument();
		$html = $dom->loadHTML($file);
		$dom->preserveWhiteSpace = false;
		$tables = $dom->getElementsByTagName('table');
		foreach ($tables as $table) {
			$rows = $table->getElementsByTagName('tr');
			$cols = $table->getElementsByTagName('th');
			$row_headers = NULL;
			foreach ($cols as $node) {
				$row_headers[] = $node->nodeValue;
			}
			$output = array();
			$rows = $table->getElementsByTagName('tr');
			foreach ($rows as $row) {
				$cols = $row->getElementsByTagName('td');
				$row = array();
				$i=0;
				foreach ($cols as $node) {
					# code...
					if($row_headers==NULL)
						$row[] = $node->nodeValue;
					else
						$row[$row_headers[$i]] = $node->nodeValue;
					$i++;
				}
				$output[] = $row;
			}
		}
		//GENERATE OPML
		$file = '<opml version="1.1">'.PHP_EOL;
		$file .= "\t".'<head>'.PHP_EOL;
		$file .= "\t\t".'<title>Flux RSS des Shaarlis Porneia</title>'.PHP_EOL;
		$file .= "\t\t".'<dateCreated>2014-07-09T20:34:03+00:00</dateCreated>'.PHP_EOL;
		$file .= "\t\t".'<dateModified>'.date('c').'</dateModified>'.PHP_EOL; // 		$file .= "\t\t".'<dateCreated>2014-07-09T22:40:47+01:00</dateCreated>'.PHP_EOL;
		$file .= "\t".'</head>'.PHP_EOL;
		$file .= "\t".'<body>'.PHP_EOL;
		unset($output[0]);
		foreach($output as $k=>$o) {
			$file .= "\t\t".'<outline text="'.htmlentities($output[$k]['Nom']).'" htmlUrl="'.$output[$k]['Lien'].'" xmlUrl="'.$output[$k]['Lien'];
			if(substr($output[$k]['Lien'], -1)!='/'){
				$file .= '/';
			}
			$file .='?do=rss"/>'.PHP_EOL;
		}
		$file .= "\t".'</body>'.PHP_EOL;
		$file .= '</opml>'.PHP_EOL;
		file_put_contents('shaarlis.opml', $file);
	}else{
		echo "Access error to http://porneia.free.fr/pub/links/ou-est-shaarli.html";
	}
}
?>
