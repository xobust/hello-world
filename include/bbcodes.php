<?php


	function bbcodeParser($bbcodes)
	{
		
		$bbcodes = htmlspecialchars($bbcodes, ENT_QUOTES);

		$urlmatch = "([a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+)";

		$match =  array();
		$replace = array();

		$match["strong"] = "/\[b\](.*?)\[\/b\]/is";
		$replace["strong"] = "<strong>$1</strong>";


		$match["i"] = "/\[i\](.*?)\[\/i\]/is";
		$replace["i"] = "<i>$1</i>";

		$match["u"] = "/\[u\](.*?)\[\/u\]/is";
		$replace["u"] = "<u>$1</u>";


		$match["color"] = "/\[color=([a-zA-Z]+|#[a-fA-F0-9]{3}[a-fA-F0-9]{0,3})\](.*?)\[\/color\]/is";
		$replace["color"] = "<span style=\"color: $1\">$2</span>";

		$match["colour"] = "/\[colour=([a-zA-Z]+|#[a-fA-F0-9]{3}[a-fA-F0-9]{0,3})\](.*?)\[\/colour\]/is";
		$replace["colour"] = $replace["color"];

		$match["img"] = "/\[img\]".$urlmatch."\[\/img\]/is";
		$replace["img"] = "<img src=\"$1\" />";

		$match["url"] = "/\[url=".$urlmatch."\](.*?)\[\/url\]/is";
		$replace["url"] = "<a href=\"$1\">$2</a>";

		$match["surl"] = "/\[url\]".$urlmatch."\[\/url\]/is";
		$replace["surl"] = "<a href=\"$1\">$1</a>";

		$match["youtube"] = "/\[yt\]([A-Za-z0-9])\[\/yt\]/is";
		$replace["youtube"] = '<iframe id="vid" width="640" height="390" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';


		/* youtube id extracter by Kise @ Stackoverflow */
		$bbcodes = preg_replace_callback('#\[yt\](.*)\[/yt\]#i', function ($matches) {
		    $regex = '#(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})#i';
		        preg_match($regex, $matches[1], $found);
		        if (isset($found[1]))
		            return '<iframe id="vid" width="640" height="390" src="http://www.youtube.com/embed/'.$found[1].'" frameborder="0" allowfullscreen></iframe>';

		}, $bbcodes);
		/* ------------------------------------------- */

		$match["soundcloud"] = "/\[s\]([0-9])\[\/s\]/is";
		$replace["soundcloud"] = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2$1"></iframe>';

		$match["quote"] = "/\[quote\](.*?)\[\/quote\]/ism";
		$replace["quote"] = "<div class=\"bbcode-quote\">�$1�</div>";

		$match["quote"] = "/\[quote=(.*?)\](.*?)\[\/quote\]/ism";
		$replace["quote"] = "<div class=\"bbcode-quote\"><span class=\"bbcode-quote-user\" style=\"font-weight:bold;\">$1 said:</span><br />�$2�</div>";


		$match["code"] = "/\[code\](.*?)\[\/code\]/ism";
		$replace["code"] = "<div class=\"bbcode-code\">�$1�</div>";





		$result = preg_replace($match, $replace, $bbcodes);

		$result = nl2br($result);

		// code by Kenneth Tsang removes brakes 
		$result = Ken($result);


		
		return $result;

	}

	/*-- code by Kenneth Tsang removes brakes --*/
	function pre_special($matches)
	{
		        $prep = preg_replace("/\<br \/\>/","",$matches[1]);
		        return "�<pre>$prep</pre>�";
	}

	function Ken($result)
	{

		$result = preg_replace_callback("/\[code\](.*?)\[\/code\]/ism","pre_special",$result);

		$result=str_replace("�<br />","",$result);
		$result=str_replace("�","",$result); 


		return $result;
	}

	/*-----------------------------------------*/


?>