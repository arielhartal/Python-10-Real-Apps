<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Levels Gallery - Zeta Flow - Sky Logic</title>
	<link rel="stylesheet" type="text/css" href="/assets/css/zf_style.css"/>
	
	<script type="text/javascript" src="/assets/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.dimensions.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.tooltip.js"></script>
	<script type="text/javascript" src="/assets/js/lib.js"></script>
	<script type="text/javascript" src="/assets/js/jsencrypt.min.js"></script>
	<script type="text/javascript">
	//<!--
	clearedFirstTime = false;
	function clearInput() {
		if (!clearedFirstTime) {
			document.getElementById("usernameInput").value = "";
			clearedFirstTime = true;
		}
	}
	console.log("Password is encrypted locally via javascript.");

	function login () {
		$("#userHeader").css({"opacity":"0.6"});
	
		url = "/backend/login";
		
		username = document.getElementById("usernameInput").value;
		password = document.getElementById("passwordInput").value;

		// Encrypt with the public key...
		var pubkey = "-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDW897jhY8puSDVwZ/u/0M6AA2UwIgC9j03KuBfZt+eR28QsAObMzOEob4O2d2SLW5ccXXNVoMsQiBWjSjvw60fkiFg6giJdSFGkHAqDE8RqAyAcCjgTPqte16a8CJixoO8rR4zusG7dh4vKhZ3LI5mIuUG7q9Jz4FSuWi/zQZAyQIDAQAB-----END PUBLIC KEY-----"
		var encrypt = new JSEncrypt();
		encrypt.setPublicKey(pubkey);
		var ePassword = encrypt.encrypt(password);

		postData = { username:username, ePassword:ePassword };
		
		$.post(url, postData,
			function(data){
				if (data=="success") {
										
					$("#userHeader").css({"opacity":"1"});
					$("#userHeader").fadeOut("slow", 
						function() {
							$("#userHeader").html("Welcome "+username+" - <a href=\"/user/home/\">Home</a> - <a id=\"logoutBtn\" class=\"a\">Logout</a>");
							docReady();
							$("#userHeader").fadeIn("slow");
						}
					);
				} else if (data=="invalid") {
					$("#outputMessage").html("Invalid credentials");
					$("#outputMessage").css({"opacity":"0", "right":"-40px"});
					$("#outputMessage").animate({"opacity":"1","right":"20px"}, 500);
				} else if (data=="incomplete") {
					$("#outputMessage").html("Must complete both fields");
					$("#outputMessage").css({"opacity":"0", "right":"-40px", "top":"0px"});
					$("#outputMessage").animate({"opacity":"1","right":"20px"}, 500);
				} else if (data=="banned") {
					alert("Sorry, you have been banned for inappropriate activity");
					$("#outputMessage").html("User banned");
					$("#outputMessage").css({"opacity":"0", "right":"-40px", "top":"0px"});
					$("#outputMessage").animate({"opacity":"1","right":"20px"}, 500);
				} else if (data=="deactivated") {
					alert("Sorry, this account has been deactivated.");
					$("#outputMessage").html("Account deactivated");
					$("#outputMessage").css({"opacity":"0", "right":"-40px", "top":"0px"});
					$("#outputMessage").animate({"opacity":"1","right":"20px"}, 500);
				} else {
					alert("Error\n"+data);
				}
				$("#userHeader").css({"opacity":"1.0"});
			}
		);
	}
	
	$(document).ready(function() {
		docReady();
	});
	
	function docReady() {
		$("#loginForm").submit(
			function(e) {
				login();
				e.preventDefault();
				return false;
			}
		);
		
		$("#logoutBtn").click(
			function() {
				$.post("/user/processLogout", "");
				
				$("#userHeader").fadeOut("slow", 
					function() {
						$("#userHeader").html("Logged out, refresh the page to login again");
						$("#userHeader").fadeIn("slow");
					}
				);
			}
		);
		
		//$("#menu div ul li").css({"opacity":"0"});
		
		$(".tooltip").tooltip({
			track: true, 
			delay: 300, 
			showURL: false, 
			showBody: " - ", 
			fade: 250 
		});
	}

	//-->
	</script>
	
	</head>

<body id="main">
	<div id="menu">
		<a href="/"><img style="border: 0px solid ; width: 227px; height: 66px; float: left;" alt="Zeta Flow - return to home" src="/assets/menu-title.png"/></a>
		
		<div class="top"><a href="/game/play"><img style="border: 0px solid ; width: 114px; height: 33px;" alt="Play Zeta Flow" src="/assets/menu-playBtn.png"/></a></div>
		<div class="top"><a href="/game/edit"><img style="border: 0px solid ; width: 134px; height: 32px;" alt="Editor to make your own levels!" src="/assets/menu-editorBtn.png"/></a></div>
		<div class="top"><a href="/game/options"><img style="border: 0px solid ; width: 148px; height: 32px;" alt="Options - set controls for Zeta Flow" src="/assets/menu-optionsBtn.png"/></a><br/></div>
		
		<div class="bottom"><a href="/game/levels"><img style="border: 0px solid ; width: 147px; height: 33px;" alt="Gallery of user created levels" src="/assets/menu-galleryBtn.png"/></a></div>
		<div class="bottom"><a href="http://zetaflow.skylogic.ca/forum/index.php"><img style="border: 0px solid ; width: 152px; height: 32px;" alt="Forums - discussions about Zeta Flow" src="/assets/menu-forumsBtn.png"/></a></div>
		<div class="bottom"><a href="/welcome/contact"><img style="border: 0px solid ; width: 148px; height: 27px;" alt="Contact me" src="/assets/menu-contactBtn.png"/></a></div>
	</div>
	<div id="userHeader">
					<form id="loginForm" action="">
				<div class="statusError" id="outputMessage" style="float: left; position: relative; top: 0px"></div>
				<div><input id="usernameInput" name="username" type="text" value="Username" style="width:100px;" onclick="clearInput()" class="tooltip" title="Enter your username"/></div>
				<div><input id="passwordInput" name="password" type="password" style="width:100px;" class="tooltip" title="Enter your password"/></div>
				<div><input type="submit" value="Login"/><br/></div>
				<div style="font-size: 0.8em; float:right"><a href="/user/register/">Register Free Account</a></div>
			</form>
			</div>

	
	<div style="position: absolute; top: 120px; position: relative; height: 100%">
<script type="text/javascript">
<!--
function zfBookmark (LID) {
	url = "/backend/bookmark/"
	postData = { LID: LID};
	
	$.post(url, postData,
		function(data){
			if (data=="notLoggedIn")
				alert("You must login to that. Registration is free.");
			else if (data=="inserted")
				document.getElementById("bookmarkColumn"+LID).style.color = "#00FF00";
			else if (data=="replaced")
				document.getElementById("bookmarkColumn"+LID).style.color = "#0000FF";
			else if (data=="failed")
				alert("Error");
			else
				alert("erm:"+data);
		}
	);

}
//-->
</script>

<div class="body">

<div class="block">
	<div class="center">
		<a href="/game/levels"><img src="/assets/menu-galleryBtn.png" alt="Gallery"/></a>
		<br/>Currently <b>11018</b> available levels!	</div>
</div><br/>

<div class="block"><div class="padded">
		<a href="/game/search/" title="Search">
			<img src="/assets/gallery-search.png" alt="Search"/>
		</a>
	<div>
		<div class="pagination"> [ &nbsp;<strong>111</strong>&nbsp;<a href="/game/levels/10900/">110</a>&nbsp;<a href="/game/levels/10800/">109</a>&nbsp;<a href="/game/levels/10700/">108</a>&nbsp;<a href="/game/levels/10600/">107</a>&nbsp;<a href="/game/levels/10500/">106</a>&nbsp;<a href="/game/levels/10400/">105</a>&nbsp;<a href="/game/levels/10300/">104</a>&nbsp;<a href="/game/levels/10200/">103</a>&nbsp;<a href="/game/levels/10100/">102</a>&nbsp;<a href="/game/levels/10000/">101</a> ] &nbsp;<a href="/game/levels/10900/">Prev ></a><a href="/game/levels/0/"> First >></a>&nbsp;</div>		<br/>
	</div>
	
		<table width="100%" border="0" class="dataTable"><thead><tr><th><a href="/game/levels/11000/username/all">Username</a></th><th><a href="/game/levels/11000/name/all">Name</a></th><th style="width:100px"><a href="/game/levels/11000/rating/all">Rating</a></th><th><a href="/game/levels/11000/type/all">Type</a></th><th><a href="/game/levels/11000/plays/all">Plays</a></th><th><a href="/game/levels/11000/success/all">Success rate</a></th><th class="tooltip" title="Number of comments"><a href="/game/levels/11000/comments/all"><img src="/assets/commentIcon.png" alt="Msgs"/></a></th><th><a href="/game/levels/11000/date/all">Date</a></th></tr></thead><tbody><tr class="alt"  id="gallery_row14700"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14700"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14700">Waves: Released</a></td><td><div class="ratingStars_s"><div style="width:100%"></div></div></td><td>action</td><td>16</td><td>63%</td><td>1</td><td>2019-01-08 &nbsp;02:30</td></tr><tr class=""  id="gallery_row14699"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14699"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14699">Random aggressive plant</a></td><td><div class="ratingStars_s"><div style="width:92.5%"></div></div></td><td>action</td><td>17</td><td>59%</td><td></td><td>2019-01-07 &nbsp;03:57</td></tr><tr class="alt"  id="gallery_row14697"><td><a href="/user/profile/asdasdg">asdasdg</a></td><td><a href="/game/play/14697"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14697">new year 2019</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td></td><td>13</td><td>77%</td><td>4</td><td>2019-01-06 &nbsp;18:59</td></tr><tr class=""  id="gallery_row14696"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14696"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14696">Jigsaw</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>art</td><td>15</td><td>73%</td><td>1</td><td>2019-01-06 &nbsp;04:33</td></tr><tr class="alt"  id="gallery_row14693"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14693"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14693">Challenge mode: Level 10</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>series</td><td>83</td><td>2%</td><td>1</td><td>2019-01-04 &nbsp;10:04</td></tr><tr class=""  id="gallery_row14692"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14692"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14692">Challenge mode: Level 9</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>series</td><td>33</td><td>12%</td><td></td><td>2019-01-04 &nbsp;10:03</td></tr><tr class="alt"  id="gallery_row14691"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14691"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14691">Challenge mode: Level 8</a></td><td><div class="ratingStars_s"><div style="width:100%"></div></div></td><td>series</td><td>35</td><td>17%</td><td></td><td>2019-01-04 &nbsp;10:03</td></tr><tr class=""  id="gallery_row14690"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14690"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14690">Challenge mode: Level 7</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>series</td><td>95</td><td>7%</td><td>8</td><td>2019-01-04 &nbsp;10:03</td></tr><tr class="alt"  id="gallery_row14689"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14689"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14689">Challenge mode: Level 6</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>series</td><td>21</td><td>24%</td><td></td><td>2019-01-04 &nbsp;10:02</td></tr><tr class=""  id="gallery_row14688"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14688"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14688">Challenge mode: Level 5</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>series</td><td>24</td><td>21%</td><td></td><td>2019-01-04 &nbsp;10:02</td></tr><tr class="alt"  id="gallery_row14687"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14687"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14687">Challenge mode: Level 4</a></td><td><div class="ratingStars_s"><div style="width:0%"></div></div></td><td>series</td><td>25</td><td>28%</td><td></td><td>2019-01-04 &nbsp;10:02</td></tr><tr class=""  id="gallery_row14686"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14686"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14686">Challenge mode: Level 3</a></td><td><div class="ratingStars_s"><div style="width:90%"></div></div></td><td>series</td><td>44</td><td>16%</td><td></td><td>2019-01-04 &nbsp;10:02</td></tr><tr class="alt"  id="gallery_row14685"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14685"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14685">Challenge mode: Level 2</a></td><td><div class="ratingStars_s"><div style="width:75%"></div></div></td><td>series</td><td>15</td><td>53%</td><td></td><td>2019-01-04 &nbsp;10:01</td></tr><tr class=""  id="gallery_row14684"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14684"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14684">Challenge mode: Level 1</a></td><td><div class="ratingStars_s"><div style="width:65%"></div></div></td><td>series</td><td>19</td><td>58%</td><td>2</td><td>2019-01-04 &nbsp;10:01</td></tr><tr class="alt"  id="gallery_row14668"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14668"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14668">EX-Transferatorium</a></td><td><div class="ratingStars_s"><div style="width:96.25%"></div></div></td><td>action</td><td>30</td><td>53%</td><td>3</td><td>2018-12-30 &nbsp;03:55</td></tr><tr class=""  id="gallery_row14666"><td><a href="/user/profile/KittyBeast">KittyBeast</a></td><td><a href="/game/play/14666"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14666">Spiral</a></td><td><div class="ratingStars_s"><div style="width:72.5%"></div></div></td><td>action</td><td>58</td><td>59%</td><td>4</td><td>2018-12-27 &nbsp;03:13</td></tr><tr class="alt"  id="gallery_row14663"><td><a href="/user/profile/asdasdg">asdasdg</a></td><td><a href="/game/play/14663"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14663">Flying raider</a></td><td><div class="ratingStars_s"><div style="width:65%"></div></div></td><td></td><td>59</td><td>71%</td><td></td><td>2018-06-14 &nbsp;10:18</td></tr><tr class=""  id="gallery_row14656"><td><a href="/user/profile/Speedemon">Speedemon</a></td><td><a href="/game/play/14656"><img src="/assets/playBtn.png" alt="Play"/></a>&nbsp;<a href="/game/profile/14656">The Core 2 redux</a></td><td><div class="ratingStars_s"><div style="width:85%"></div></div></td><td>action</td><td>173</td><td>22%</td><td>3</td><td>2018-04-06 &nbsp;18:45</td></tr></tbody></table>	
	<div>
		<br/>
		<div class="pagination"> [ &nbsp;<strong>111</strong>&nbsp;<a href="/game/levels/10900/">110</a>&nbsp;<a href="/game/levels/10800/">109</a>&nbsp;<a href="/game/levels/10700/">108</a>&nbsp;<a href="/game/levels/10600/">107</a>&nbsp;<a href="/game/levels/10500/">106</a>&nbsp;<a href="/game/levels/10400/">105</a>&nbsp;<a href="/game/levels/10300/">104</a>&nbsp;<a href="/game/levels/10200/">103</a>&nbsp;<a href="/game/levels/10100/">102</a>&nbsp;<a href="/game/levels/10000/">101</a> ] &nbsp;<a href="/game/levels/10900/">Prev ></a><a href="/game/levels/0/"> First >></a>&nbsp;</div>	</div>
	
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<ins class="adsbygoogle"
	     style="display:inline-block;width:728px;height:90px"
	     data-ad-client="ca-pub-7307480853799435"
	     data-ad-slot="4648537948"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>
	
</div></div><!-- class block, class padded -->
</div><!-- class body --></div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1143718-2");
pageTracker._trackPageview();
} catch(err) {}</script>

<script>
// tippy('.tooltip') //tippify only elements with this class
tippy('[title]') //tippify all elements with title attribute
</script>

</body>
</html>
