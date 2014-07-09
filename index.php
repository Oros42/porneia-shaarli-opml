<?php
#SOFTWARE IN MIT/X11 LICENCE
#Copyright ©  2014-07-09, <http://github.com/qwertygc>
#Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do #so, subject to the following conditions:
#The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
#The Software is provided "as is", without warranty of any kind, express or implied, including but not limited to the warranties of merchantability, fitness for a particular purpose and noninfringement. In no event shall the authors or copyright holders X be liable for any claim, damages or other liability, whether in an action of contract, tort or otherwise, arising from, out of or in connection with the software or the use or other dealings in the Software.
#Except as contained in this notice, the name of the <http://github.com/qwertygc> shall not be used in advertising or otherwise to promote the sale, use or other dealings in this Software without prior written #authorization from the <http://github.com/qwertygc>.

error_reporting(0);
//http://porneia.free.fr/pub/links/ou-est-shaarli.html
if(((fileatime('table.html')+60*60*24*15) < time()) OR !file_exists('table.html')) {
	$file = file_get_contents('http://porneia.free.fr/pub/links/ou-est-shaarli.html');
	file_put_contents('table.html', $file);
}
// function http://www.developpez.net/forums/d1405041/php/bibliotheques-frameworks/xml/dom/conversion-table-html-array-php/
$dom = new DOMDocument();
$html = $dom->loadHTMLFile('table.html');
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
	// print_r($output);
}
//GENERATE OPML
$file = '<opml version="1.1">'.PHP_EOL;
$file .= "\t".'<head>'.PHP_EOL;
$file .= "\t\t".'<title>Flux RSS des Shaarlis Porneia</title>'.PHP_EOL;
$file .= "\t\t".'<dateCreated>2013-11-29T22:40:47+01:00</dateCreated>'.PHP_EOL;
$file .= "\t\t".'<dateModified>'.date('c',fileatime('table.html')).'</dateModified>'.PHP_EOL;
$file .= "\t".'</head>'.PHP_EOL;
$file .= "\t".'<body>'.PHP_EOL;
foreach($output as $k=>$o) {
	if($k !=0) {
		$file .= "\t\t".'<outline text="'.htmlentities($output[$k]['Nom']).'" htmlUrl="'.$output[$k]['Lien'].'" xmlUrl="'.$output[$k]['Lien'].'/?do=rss"/>'.PHP_EOL;
	}
}
$file .= "\t".'</body>'.PHP_EOL;
$file .= '</opml>'.PHP_EOL;
if(((fileatime('shaarlis.opml')+60*60*24*15) < time()) OR !file_exists('shaarlis.opml')) {
	file_put_contents('shaarlis.opml', $file);
}

?>
