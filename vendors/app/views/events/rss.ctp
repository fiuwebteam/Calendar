<?php 
function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );
    return strtr($string, $table);
}
function clean_url($text) {
	$code_entities_match = array('&', "<br>", "<br/>");
	$code_entities_replace = array('&amp;', '', '');
	$text = str_replace($code_entities_match, $code_entities_replace, $text);
	return $text;
}
header("Content-Type:application/rss+xml");

?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title><?= normalize(clean_url($calendarTitle)) ?></title>
	<link><?= $html->url(array('controller' => 'events', 'action' => 'index', 'calendar' => $this->params["calendar"] ), true);?></link>	
	<description><?= normalize(clean_url($calendarDescription)) ?></description>
	<atom:link href="<?= $html->url(array('controller' => 'events', 'action' => 'index', 'calendar' => $this->params["calendar"] ), true) . $this->params["calendar"] ?>/events/rss" rel="self" type="application/rss+xml" />
	<language>en-us</language>
	<pubDate><?= date("D, d M Y H:i:s T")  ?></pubDate>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>
	<generator>FIU Calendar Tool</generator>
	<managingEditor>amolive@fiu.edu (Andre Oliveira)</managingEditor>
	<webMaster>amolive@fiu.edu (Andre Oliveira) </webMaster>
<?php foreach ($events as $key => $event) { ?>
	<item>
	<title><?= normalize(clean_url($event['Event']['title'])) ?></title>
	<?php $thisLink = $html->url( array('controller' => 'events', 'action' => 'view', $event['Event']['id'] ), true) ?>
	<link><?= $thisLink ?></link>
	<guid><?= $thisLink  . "-" .  date("Y-m-d-G-i-s", strtotime($event["CalendarsEvent"]["start_date_time"]))  ?></guid>
	<description><?= normalize(clean_url($event['Event']['description'])) ?></description>		
	<pubDate><?= date("D, d M Y H:i:s T", strtotime($event["CalendarsEvent"]["start_date_time"]) ) ?></pubDate>
	</item>
<?php } ?>
</channel>
</rss>	
