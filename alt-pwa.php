<?php
/**
Plugin Name: altPWA
Author URI: http://familia.capan.ro
Plugin URI: http://www.cnet.ro/wordpress/altpwa
Description: altPWA is a plugin that will allow you to easily embed a Picasa Web Album in your pages.
Author: Radu Capan
Version: 1.1.2

LOCALIZATION
Place your language file within this plugin directory and name it "altpwa-{language}.mo" replace {language} with your language value from wp-config.php

CHANGELOG
See readme.txt
*/


if(!get_option('altpwa')){
	$options = array("dimth" => "160", "dimthm" => "64", "dimimg" => "640", "linii" => "3", "coloane" => "3", "spatiere" => "3", "pagalbume" => "10", "lightbox" => _e('no','altpwa')."", "sprepicasa" => _e('no','altpwa')."");
	add_option("altpwa", $options);
}
$options = get_option('altpwa');
$baseurl = "http://picasaweb.google.com/data/feed/api/user/";
$baseurl2 = "http://picasaweb.google.com/data/feed/base/user/";
$pagalbume = $options["pagalbume"]; //paginare albume (cate albume pe o pagina);
$dimthm = $options["dimthm"]; //32,48,64,160 sunt patrate, cu crop; 144,200,288,400,512 sunt fara crop  ============
$dimth = $options["dimth"]; //32,48,64,160 sunt patrate, cu crop; 144,200,288,400,512 sunt fara crop ============
$dimimg = $options["dimimg"]; //576,640,720,800 ===================
$linii = $options["linii"];
$coloane = $options["coloane"];
$pagpoze = $linii * $coloane;
$spatiere = $options["spatiere"];
$lightbox = $options["lightbox"];
$sprepicasa = $options["sprepicasa"];
define('CALEIMG', get_option('siteurl').'/wp-content/plugins/altpwa/images/');

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace("?","",$uri);
$precale = get_option('siteurl').$uri."?";

add_action('wp_head', 'alt_pwa_header');
add_action('admin_menu', 'alt_pwa_meniu');
add_shortcode('altpwa', 'alt_pwa_func');
load_plugin_textdomain( 'altpwa', '/wp-content/plugins/altpwa' );

function alt_pwa_meniu() {
	add_options_page(__('Options','altpwa').' altPWA', 'altPWA', 8, basename(__FILE__), 'alt_pwa_admin');
}

function alt_pwa_admin(){
	$dimth_a = array(32,48,64,144,160,200,288,400,512);
	$dimthm_a = array(32,48,64,144,160);
	$dimimg_a = array(576,640,720,800);
	$lightbox_a = array(__('yes','altpwa')."",__('no','altpwa')."");
	$sprepicasa_a = array(__('yes','altpwa')."",__('no','altpwa')."");
	$options = get_option('altpwa');
	if ( !is_array($options) ) {
		$defaults = array('dimth' => 160, 'dimthm' => 64, 'dimimg' => 640, 'linii' => 3, 'coloane' => 3, 'spatiere' => 3, 'pagalbume' => 10, 'lightbox' => _e('no','altpwa')."", 'sprepicasa' => _e('no','altpwa')."");
		$options = array_merge($defaults);
	}
	if (isset($_POST['update_altpwa'])) {
		$options['dimth'] = $_POST['dimth'];
		$options['dimthm'] = $_POST['dimthm'];
		$options['dimimg'] = $_POST['dimimg'];
		$options['linii'] = strip_tags(stripslashes($_POST['linii']));
		$options['coloane'] = strip_tags(stripslashes($_POST['coloane']));
		$options['spatiere'] = strip_tags(stripslashes($_POST['spatiere']));
		$options['pagalbume'] = strip_tags(stripslashes($_POST['pagalbume']));
		$options['lightbox'] = $_POST['lightbox'];
		$options['sprepicasa'] = $_POST['sprepicasa'];

		update_option('altpwa', $options);
		echo "<div class='updated'><p>".__("Done with updating","altpwa")."...</p></div>";
	}
	echo "<div class='wrap'>";
	echo "<h2>".__("Options","altpwa")." altPicasaWebAlbum (altPWA)</h2>";
	?>
		<form method="post">

		<table class="form-table"><tbody>
			<tr valign="top"><th scope="row"><label for="dimth"><?php _e('Thumb size','altpwa'); ?>:</label></th>
			<td><label><select name="dimth" id="dimth">
				<?php foreach ($dimth_a as $size) { ?>
					<option <?php if ($options['dimth'] == $size) { echo 'selected'; } ?> value="<?php echo attribute_escape($size); ?>"><?php echo $size; ?>px</option>
				<?php } ?>
				</select></label></td></tr>
			<tr valign="top"><th scope="row"><label for="dimthm"><?php _e('Thumb 2 size','altpwa'); ?>:</label></th>
			<td><label><select name="dimthm" id="dimthm">
				<?php foreach ($dimthm_a as $size) { ?>
					<option <?php if ($options['dimthm'] == $size) { echo 'selected'; } ?> value="<?php echo attribute_escape($size); ?>"><?php echo $size; ?>px</option>
				<?php } ?>
				</select></label></td></tr>
			<tr valign="top"><th scope="row"><label for="dimimg"><?php _e('Image size','altpwa'); ?>:</label></th>
			<td><label><select name="dimimg" id="dimimg">
				<?php foreach ($dimimg_a as $size) { ?>
					<option <?php if ($options['dimimg'] == $size) { echo 'selected'; } ?> value="<?php echo attribute_escape($size); ?>"><?php echo $size; ?>px</option>
				<?php } ?>
				</select></label></td></tr>
			<tr valign="top"><th scope="row"><label for="linii"><?php _e('Number of rows','altpwa'); ?>:</label></th>
			<td><label><input name="linii" type="text" id="linii" value="<?php echo attribute_escape($options['linii']); ?>" size="2" /></label></td></tr>
			<tr valign="top"><th scope="row"><label for="coloane"><?php _e('Number of cols','altpwa'); ?>:</label></th>
			<td><label><input name="coloane" type="text" id="coloane" value="<?php echo attribute_escape($options['coloane']); ?>" size="2" /></label></td></tr>
			<tr valign="top"><th scope="row"><label for="spatiere"><?php _e('Spacing','altpwa'); ?>:</label></th>
			<td><label><input name="spatiere" type="text" id="spatiere" value="<?php echo attribute_escape($options['spatiere']); ?>" size="2" /></label></td></tr>
			<tr valign="top"><th scope="row"><label for="pagalbume"><?php _e('Albums/page','altpwa'); ?>:</label></th>
			<td><label><input name="pagalbume" type="text" id="pagalbume" value="<?php echo attribute_escape($options['pagalbume']); ?>" size="2" /></label></td></tr>
			<tr valign="top"><th scope="row"><label for="lightbox"><?php _e('Lightbox','altpwa'); ?>:</label></th>
			<td><label><select name="lightbox" id="lightbox">
				<?php foreach ($lightbox_a as $size) { ?>
					<option <?php if ($options['lightbox'] == $size) { echo 'selected'; } ?> value="<?php echo ($size==__('yes','altpwa')?1:0); ?>"><?php echo $size; ?></option>
				<?php } ?>
				</select></label></td></tr>
			<tr valign="top"><th scope="row"><label for="lightbox"><?php _e('Link to Picasa','altpwa'); ?>:</label></th>
			<td><label><select name="sprepicasa" id="sprepicasa">
				<?php foreach ($sprepicasa_a as $size) { ?>
					<option <?php if ($options['sprepicasa'] == $size) { echo 'selected'; } ?> value="<?php echo ($size==__('yes','altpwa')?1:0); ?>"><?php echo $size; ?></option>
				<?php } ?>
				</select></label></td></tr>
		</table>

		<p class="submit">
			<input type="submit" name="update_altpwa" value="<?php echo attribute_escape(__('Update options','update_altpwa')); ?> &raquo;" style="font-weight:bold;" />
		</p>
		</form>       
	<?php
	echo "</div>";
}

function alt_pwa_header(){
	global $wp_query, $wpdb,$lightbox;
	$id = $wp_query->post->ID;
	$rezs = $wpdb->get_results("SELECT post_content FROM wp_posts WHERE (id='".$id."')");
	foreach ($rezs as $rez){
		$temp = $rez->post_content;
		if( stristr($temp,"[altpwa user=")!==FALSE && ($lightbox==1)) {
			echo '<script type="text/javascript">'."\n";
			echo '/* <![CDATA[ */'."\n";
			echo "\t".'var path_to_lg_load = \''.get_bloginfo("url")."/wp-content/plugins/altpwa/images/loading.gif';\n";
			echo "\t".'var path_to_lg_close = \''.get_bloginfo("url")."/wp-content/plugins/altpwa/images/closelabel".(WPLANG!="" && file_exists(realpath(".")."/wp-content/plugins/altpwa/images/closelabel-".WPLANG.".gif")?"-".WPLANG:"").".gif';\n";
			echo "\t".'var text_lg_image ="'.__('Image','altpwa').'";';
			echo "\t".'var text_lg_of ="'.__('of','altpwa').'";';
			echo '/* ]]> */'."\n";
			echo '</script>'."\n";
			echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/altpwa/lb-js/prototype.js"></script>'."\n";
			echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/altpwa/lb-js/scriptaculous.js?load=effects,builder"></script>'."\n";
			echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/altpwa/lb-js/lightbox.js"></script>'."\n";
			echo '<link rel="stylesheet" href="'.get_settings('siteurl').'/wp-content/plugins/altpwa/lb-css/lightbox.css" type="text/css" media="screen" />'."\n";
			echo '<style type="text/css">'."\n";
			echo '#prevLink:hover, #prevLink:visited:hover { background: url('.get_settings('siteurl').'/wp-content/plugins/altpwa/images/prevlabel'.(WPLANG!="" && file_exists(realpath(".")."/wp-content/plugins/altpwa/images/prevlabel-".WPLANG.".gif")?"-".WPLANG:"").'.gif) left 15% no-repeat; }'."\n";
			echo '#nextLink:hover, #nextLink:visited:hover { background: url('.get_settings('siteurl').'/wp-content/plugins/altpwa/images/nextlabel'.(WPLANG!="" && file_exists(realpath(".")."/wp-content/plugins/altpwa/images/nextlabel-".WPLANG.".gif")?"-".WPLANG:"").'.gif) right 15% no-repeat; }'."\n";
			echo '</style>'."\n";
		}
	}
}

function pwa_albume(){
	global $baseurl,$baseurl2,$user,$pagalbume,$user,$album,$precale,$spatiere,$month;
	if(is_feed()){
		$html .= "<p>Albumele pot fi vazute doar online.</p>";
		return $html;
	}
	if(isset($_REQUEST["album"])){
		$album = $_REQUEST["album"];
		$html = pwa_album();
		return $html;
	}
	$html = "";
	if(isset($_REQUEST["pagina"]))
		$pagina=$_REQUEST["pagina"]-0;
	else
		$pagina=1;
	$url = "http://picasaweb.google.com/data/feed/api/user/$user?alt=json&thumbsize=144";
	$continut = file_get_contents($url);
	$feed = json_decode($continut,true);
	$dim = count($feed["feed"]["entry"]);
	$url = "http://picasaweb.google.com/data/feed/api/user/$user?alt=json&start-index=".(($pagina-1)*$pagalbume+1)."&max-results=".$pagalbume."&thumbsize=160";
	$continut = file_get_contents($url);
	$feed = json_decode($continut,true);
	$pagini = ceil($dim / $pagalbume);
	$precale = str_replace("??","?",str_replace("&pagina=".$pagina,"",$precale));
	if($pagini>1){
		$html .= "<p align=center>";
		if($pagina>1)
			$html .= "<a href=".$precale."&pagina=".($pagina-1)."#spwa><img class=zero src=".CALEIMG."back.png hspace=5 border=0></a>";
		else
			$html .= "<img class=zero src=".CALEIMG."back_.png hspace=5 border=0>";
		$html .= "&nbsp;";
		if($dim>$pagina*$pagalbume)
			$html .= "<a href=".$precale."&pagina=".($pagina+1)."#spwa><img class=zero src=".CALEIMG."forward.png hspace=5 border=0></a>";
		else
			$html .= "<img class=zero src=".CALEIMG."forward_.png hspace=5 border=0>";
		$html .= "<br>".__('Pages','altpwa').": ";
		for($i=1;$i <= $pagini; $i++)
	  		if($i==$pagina)
	  			$html .= "".$i." ";
	  		else
	  			$html .= "<a href=".$precale."&pagina=".$i."#spwa>".$i."</a> ";
		$html .= "</p>";
	}
	
	$html .= "<table width=100% cellpadding=4 cellspacing=0 border=0>";
	for($i = 0; $i < count($feed["feed"]["entry"]); $i++){
		$html .= "<tr><td valign=top align=center><p><a href=".$precale."&album=".$feed["feed"]["entry"][$i]["gphoto\$name"]["\$t"]."#spwa><img src=".$feed["feed"]["entry"][$i]["media\$group"]["media\$thumbnail"][0]["url"]." border=0></a></td><td valign=center align=left>";
		$html .= "<a name=spwa></a><p><strong>".$feed["feed"]["entry"][$i]["title"]["\$t"]."</strong> (".$feed["feed"]["entry"][$i]["gphoto\$numphotos"]["\$t"]." ".__('photos','altpwa').")<br>";
		$cand = $feed["feed"]["entry"][$i]["published"]["\$t"];
		$html .= (substr($cand,8,2)-0)." ".$month[substr($cand,5,2)]." ".substr($cand,0,4);
		//$html .= date(get_option('date_format'),strtotime($cand));
		if($feed["feed"]["entry"][$i]["gphoto\$location"]["\$t"]!="")
			$html .= ", ".$feed["feed"]["entry"][$i]["gphoto\$location"]["\$t"]."</p>";
		if($feed["feed"]["entry"][$i]["summary"]["\$t"]!="")
			$html .= "<p>".$feed["feed"]["entry"][$i]["summary"]["\$t"]."</p>";
		$html .= "</td></tr>";
	}
	$html .= "</table>";
	return $html;
}

function pwa_album(){
	global $baseurl,$baseurl2,$user,$pagpoze,$pagalbume,$user,$album,$dimth,$dimimg,$spatiere,$coloane,$linii,$lightbox,$precale,$sprepicasa,$month;
	if(isset($_REQUEST["foto"])){
		$album = $_REQUEST["album"];
		$foto = $_REQUEST["foto"];
		$html = pwa_foto($album,$foto);
		return $html;
	}
	if(isset($_REQUEST["pagina"]))
		$pagina=$_REQUEST["pagina"]-0;
	else
		$pagina=1;
	$html = "";
	$precale = str_replace("&album=".$album,"",str_replace("&pagina=".$pagina,"",$precale));
	
	$url = "http://picasaweb.google.com/data/feed/api/user/$user?alt=json&thumbsize=144";
	$continut = file_get_contents($url);
	$feed = json_decode($continut,true);
	for($i=0;$i<count($feed["feed"]["entry"]);$i++)
		if($feed["feed"]["entry"][$i]["gphoto\$name"]["\$t"] == $album)
			$idcrt = $i;
	
	$url = "http://picasaweb.google.com/data/feed/api/user/$user/album/$album?alt=json&start-index=".(($pagina-1)*$pagpoze+1)."&max-results=".$pagpoze."&thumbsize=144";
	$continut = file_get_contents($url);
	//echo $url;
	$feed = json_decode($continut,true);
	$html .= "<a name=spwa></a><p><strong>".$feed["feed"]["title"]["\$t"]."</strong> (".$feed["feed"]["gphoto\$numphotos"]["\$t"]." ".__('photos','altpwa').")<br>";
	$cand = $feed["feed"]["updated"]["\$t"];
	$html .= date('j', $feed["feed"]["gphoto\$timestamp"]["\$t"]/1000)." ".$month["".date('m', $feed["feed"]["gphoto\$timestamp"]["\$t"]/1000)]." ".date('Y', $feed["feed"]["gphoto\$timestamp"]["\$t"]/1000);
	//$html .= date(get_option('date_format'),strtotime($cand));
	if($feed["feed"]["gphoto\$location"]["\$t"] != "")
		$html .= ", ".$feed["feed"]["gphoto\$location"]["\$t"];
	$html .= "<br>".$feed["feed"]["subtitle"]["\$t"]."</p>";
	if(is_feed()){
		$html .= "<p>Albumul poate fi vazut doar online.</p>";
		return $html;
	}
	$path_album = $feed["feed"]["link"][1]["href"];
	$dim = $feed["feed"]["gphoto\$numphotos"]["\$t"]-0;
	$cate = count($feed["feed"]["entry"]);
	$pagini = ceil($dim / $pagpoze);
	
	if($pagini>1){
		$html .= "<p align=center>";
		if($pagina>1)
			$html .= "<a href=".$precale."&album=".$album."&pagina=".($pagina-1)."#spwa><img class=zero src=".CALEIMG."back.png hspace=5 border=0></a>";
		else
			$html .= "<img class=zero src=".CALEIMG."back_.png hspace=5 border=0>";
		$html .= "&nbsp;<a href=".str_replace("&inceput","",str_replace("&album=".$_REQUEST["album"],"",$precale))."&pagina=".ceil(($idcrt+1) / $pagalbume)."#spwa><img class=zero src=".CALEIMG."up.png hspace=5 border=0></a>&nbsp;";
		if($dim>$pagina*$pagpoze)
			$html .= "<a href=".$precale."&album=".$album."&pagina=".($pagina+1)."#spwa><img class=zero src=".CALEIMG."forward.png hspace=5 border=0></a>";
		else
			$html .= "<img class=zero src=".CALEIMG."forward_.png hspace=5 border=0>";
		$html .= "<br>".__('Pages','altpwa').": ";
		for($i=1;$i <= $pagini; $i++)
	  		if($i==$pagina)
	  			$html .= "".$i." ";
	  		else
	  			$html .= "<a href=".$precale."&album=".$album."&pagina=".$i."#spwa>".$i."</a> ";
		$html .= "</p>";
	}
	else
		if(isset($_REQUEST["album"]))
			$html .= "<p align=center><a href=".str_replace("&inceput","",str_replace("&album=".$_REQUEST["album"],"",$precale))."#spwa><img class=zero src=wp-content/plugins/altpwa/images/up.png hspace=5 border=0></a></p>";
	
	$html .= "<table width=100% cellpadding=0 cellspacing=0 border=0>";
	$incheiat=0;
	
	for($i=0;$i<$cate;$i++){
		if($i%$coloane == 0) {
			echo "<tr>";
			$incheiat = 0;
		}
		$html .= "<td align=center valign=center><p>";
		if($lightbox==1)
			$html .= "<a href=".str_replace("/s144/","/s$dimimg/",$feed["feed"]["entry"][$i]["media\$group"]["media\$thumbnail"][0]["url"])." rel='lightbox[albume]' title='".$feed["feed"]["entry"][$i]["summary"]["\$t"]."'>";
		else
			//$html .= "<a href=".$precale."&foto=".($i+($pagina-1)*$pagpoze)." alt='".$feed["feed"]["entry"][$i]["summary"]["\$t"]."' title='".$feed["feed"]["entry"][$i]["summary"]["\$t"]."'>";
			$html .= "<a href=".$precale."&album=".$album."&foto=".$feed["feed"]["entry"][$i]["gphoto\$id"]["\$t"]."#spwa alt='".$feed["feed"]["entry"][$i]["summary"]["\$t"]."' title='".$feed["feed"]["entry"][$i]["summary"]["\$t"]."'>";
		$html .= "<img src=".str_replace("/s144/","/s$dimth".(pwa_square()?"-c":"")."/",$feed["feed"]["entry"][$i]["media\$group"]["media\$thumbnail"][0]["url"])." hspace=$spatiere vspace=$spatiere></a></td>";
		if($i%$coloane == ($coloane-1)){
			$html .= "</tr>";
			$incheiat = 1;
		}
	}
	
	if(!$incheiat){
			$i--;
			while(($i%$coloane)!=($coloane-1)){
				$html .= "<td><img src=".CALEIMG."gol.gif height=$dimth width=$dimth hspace=".$spatiere." vspace=".$spatiere." border=0></td>";
				$i++;
			}
			$html .= "</tr>";
		}
	$html .= "</table>";
	if($sprepicasa==1)
		$html .= "<p><b>".__("Note","altpwa").":</b> ".__("To see the pictures in the original Picasa album, click","altpwa")." <a href='$path_album' target=_blank>".__("here","altpwa")."</a>";
	//$html .= "<p><b>Notă:</b> Pentru a vedea fotografiile cu o viteză mai bună şi la o dimensiune mai mare mergeţi <a href=".$path_album.">aici</a>.";
	
	return $html;
}

function pwa_foto($album,$foto){
	global $baseurl,$baseurl2,$user,$pagpoze,$user,$album,$dimth,$dimthm,$dimimg,$spatiere,$coloane,$linii,$lightbox,$precale,$month;
	$html = "";
	$precale = str_replace("&album=".$album,"",str_replace("&foto=".$foto,"",$precale));	
	$url = "http://picasaweb.google.com/data/feed/api/user/$user/album/$album?alt=json&thumbsize=144";
	//echo $url;
	$continut = file_get_contents($url);
	$feed = json_decode($continut,true);
	$html .= "<a name=spwa></a><p><strong>".$feed["feed"]["title"]["\$t"]."</strong> (".$feed["feed"]["gphoto\$numphotos"]["\$t"]." ".__('photos','altpwa').")<br>";
	$cand = $feed["feed"]["updated"]["\$t"];
	$html .= date('j', $feed["feed"]["gphoto\$timestamp"]["\$t"]/1000)." ".$month["".date('m', $feed["feed"]["gphoto\$timestamp"]["\$t"]/1000)]." ".date('Y', $feed["feed"]["gphoto\$timestamp"]["\$t"]/1000);
	//$html .= date(get_option('date_format'),strtotime($cand));
	if($feed["feed"]["gphoto\$location"]["\$t"] != "")
		$html .= ", ".$feed["feed"]["gphoto\$location"]["\$t"];
	$html .= "<br>".$feed["feed"]["subtitle"]["\$t"]."</p>";
	$path_album = $feed["feed"]["link"][2]["href"];
	$dim = $feed["feed"]["gphoto\$numphotos"]["\$t"]-0;
	for($i=0;$i<count($feed["feed"]["entry"]);$i++)
		if($feed["feed"]["entry"][$i]["gphoto\$id"]["\$t"] == $foto)
			$idcrt = $i;
	//echo "Este: ".$idcrt;
	//$idcrt = $foto;
	
	$html .= "<p align=center>";
	if($idcrt)
		$html .= "<a href=".$precale."&album=".$album."&foto=".$feed["feed"]["entry"][$idcrt-1]["gphoto\$id"]["\$t"]."#spwa><img class=zero src=".CALEIMG."back.png hspace=5 border=0></a>";
	else
		$html .= "<img class=zero src=".CALEIMG."back_.png hspace=5></td>";
	$html .= "&nbsp;<a href=".$precale."&album=".$album."&pagina=".ceil(($idcrt+1) / $pagpoze)."#spwa><img class=zero src=".CALEIMG."up.png hspace=5 border=0></a>&nbsp;";
	if(count($feed["feed"]["entry"])>($idcrt+1))
		$html .= "<a href=".$precale."&album=".$album."&foto=".$feed["feed"]["entry"][$idcrt+1]["gphoto\$id"]["\$t"]."#spwa><img class=zero src=".CALEIMG."forward.png hspace=5 border=0></a>";
	else
		$html .= "<img class=zero src=".CALEIMG."forward_.png hspace=5></td>";
	$html .= "</p>";
	
	
	$html .= "<p><strong>".$feed["feed"]["entry"][$idcrt]["summary"]["\$t"]."</strong></p>";
	$html .= "<p align=center><img src=".str_replace("/s144/","/s$dimimg/",$feed["feed"]["entry"][$idcrt]["media\$group"]["media\$thumbnail"][0]["url"])." border=0>";
	if($sprepicasa==1)
		$html .= "<br><a href=".$feed["feed"]["entry"][$idcrt]["link"][1]["href"]." target=_blank>".__("original image","altpwa")."</a> (".$feed["feed"]["entry"][$idcrt]["gphoto\$width"]["\$t"]." x ".$feed["feed"]["entry"][$idcrt]["gphoto\$height"]["\$t"].")";
	$html .= "</p>";
	
	$html .= "<table width=$dimimg align=center cellpadding=0 cellspacing=0 border=0><td>";
	$crt = $idcrt;
	while(3-$crt>0){
		$html .= "<td><img src=".CALEIMG."gol.gif width=$dimthm hspace=$spatiere></td>";
		$crt++;
	}
	$crt = $idcrt - 1;
	for($i=0;$i<3;$i++)
		if(($crt-(2-$i))>=0)
			$html .= "<td><p><a href=".$precale."&album=".$album."&foto=".$feed["feed"]["entry"][$crt-(2-$i)]["gphoto\$id"]["\$t"]."#spwa><img src=".str_replace("/s144/","/s$dimthm".(pwa_square(2)?"-c":"")."/",$feed["feed"]["entry"][$crt-(2-$i)]["media\$group"]["media\$thumbnail"][0]["url"])." hspace=$spatiere></a></td>";
	$crt = $idcrt + 1;
	$html .= "<td width=90%></td>";
	for($i=0;($i<3) && ($crt<count($feed["feed"]["entry"]));$i++,$crt++){
		$html .= "<td><p><a href=".$precale."&album=".$album."&foto=".$feed["feed"]["entry"][$crt]["gphoto\$id"]["\$t"]."#spwa><img src=".str_replace("/s144/","/s$dimthm".(pwa_square(2)?"-c":"")."/",$feed["feed"]["entry"][$crt]["media\$group"]["media\$thumbnail"][0]["url"])." hspace=$spatiere></a></td>";
	}
	for(;$i<3;$i++)
		$html .= "<td><img src=".CALEIMG."gol.gif width=$dimthm hspace=$spatiere></td>";
	$html .= "</tr></table>";
		
	return $html;
}

function pwa_square($care=1){
	global $dimth,$dimthm;
	if($care==2)
		return ($dimthm ==32) || ($dimthm ==48) || ($dimthm ==64) || ($dimthm ==160);
	else
		return ($dimth ==32) || ($dimth ==48) || ($dimth ==64) || ($dimth ==160);
}

function alt_pwa_func($atts){
	global $baseurl,$user,$album,$dimth,$precale;
	$precale = "".get_permalink();
	if(stripos($precale,"?")===false)
		$precale .= "?";
	extract(shortcode_atts(array('user'=>'','album' => ''), $atts));
	$result = "";
	if($album=="")
		$result .= pwa_albume();
	else
		$result .= pwa_album();
	return $result;
}

?>