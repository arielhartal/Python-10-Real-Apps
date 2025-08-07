<?php
	require dirname(__FILE__) . '/../language_parameters.php';
	$language_params = get_playforia_language_parameters($_GET['language'] ?? 'english');
?>
<applet id="gameclient" class="java topspacer" codebase="http://game21.playforia.net/Poks2/" code="Poks2" archive="Poks2.jar,/Shared/HackedShared2.zip" width="700" height="523"> 
	<param name="initmessage" value="<?php echo $language_params['initmessage']; ?>" /> 
	<param name="permissions" value="all-permissions" /> 
	<param name="ld_page" value="javascript:Playray.Notify.delegate({ jvm: { version: '%v', vendor: '%w', t1: '%r', t2: '%f' } })" /> 
	<param name="image" value="/appletloader_playforia.gif" /> 
	<param name="server" value="game21.playforia.net:4450" />
	<param name="locale" value="<?php echo $language_params['locale']; ?>" /> 
	<param name="lang" value="<?php echo $language_params['lang']; ?>" /> 
	<param name="sitename" value="playray" /> 
	<param name="quitpage" value="http://www.playforia.net/games/" /> 
	<param name="regremindshowtime" value="3,8,15,25,50,100,1000" /> 
	<param name="registerpage" value="http://www.playforia.net/account/create/" /> 
	<param name="creditpage" value="http://www.playforia.net/shop/buy/" /> 
	<param name="userinfopage" value="http://www.playforia.net/community/user/" /> 
	<param name="userinfotarget" value="_blank" /> 
	<param name="userlistpage" value="javascript:Playray.GameFaceGallery('%n','#511D1D','poks2','%s')" /> 
	<param name="guestautologin" value="true" /> 
	<param name="disableguestlobbychat" value="true" /> 
	<param name="json" value="Playray.Notify.delegate(%o)" /> 
	<param name="centerimage" value="true" /> 
	<param name="java_arguments" value="-Xmx128m" /> 
	<param name="localizationUrl" value="" /> 
	<param name="sharedLocalizationUrl" value="" />
	<param name="session" value="SjogTxTF-SsojJVoWUa9diQcQTf" /> 
	<param name="sessionlocale" value="en" /> 
	<param name="tellfriendpage" value="http://www.playforia.net/q/tellfriend/superbubbles" /> 
	<param name="tellfriendtarget" value="tellfriend" /> 
	<param name="tellfriend" value="1" />

	<param name="flashpoint_products" value="2	playtime_reg	5	0	0	0	playtime_vip	1	0	0	0" />
</applet>