@ECHO OFF

REM Launcher for any Java games that require a browser to run in Flashpoint.
REM Usage: startJavaInBrowser.bat <Game URL> [Optional Java Command Line Arguments]
REM E.g: startJavaInBrowser.bat http://www.example.com/game.html -Duser.language=en -Duser.country=US
REM
REM When adding a Java game that uses this launch command you must follow the next two steps for it to work properly:
REM
REM 1. Add these two parameters to the applet/embed tag:
REM 	<param name="java_version" value="1.8.0_181">
REM 	<param name="separate_jvm" value="true">
REM Games launched with this script must always use Java 8u181. These parameters have no effect if the same applet is launched in
REM the AppletViewer via startJava.bat
REM
REM 2. Add the game's domain to the exception list file in FPSoftware\Java\JDK_1.8.0_181\jre\lib\exception.sites
REM
REM Written by Strawrat. Special thanks to nosamu for his assistance.
REM Version 9 - Modified 2020-05-30

SETLOCAL EnableDelayedExpansion
	PUSHD "%~dp0"

		REM Change FLASHPOINT_DEBUG_MODE to ON to always show the Java console on startup and to put a Java icon in the system
		REM tray (which you can use to bring up the console at any time). Set it to OFF to turn it off.
		SET FLASHPOINT_DEBUG_MODE=OFF
		REM Add any command line options here to pass them to the JVM. Unlike in startJava.bat, these are NOT prefixed with -J
		REM Don't use the -XshowSettings option. Instead, set FLASHPOINT_DEBUG_MODE to ON and press 's' in the Java console to
		REM show the settings related to system properties, locale, and VM settings.
		SET "FLASHPOINT_DEBUG_OPTIONS="

		REM -----------------------------------------------------------------------------------------------------
		SET FLASHPOINT_PROXY_PORT=22500
		REM -----------------------------------------------------------------------------------------------------

		SET "FLASHPOINT_URL=%~1"

		REM Get any arguments that come after the launch command, while also keeping the equals character.
		REM If the URL contains spaces, these must be replaced with %20. E.g: http://www.example.com/the%20java%20game.html
		REM To do this, we must first get all the arguments, which requires adding an extra pairs of quotes because of cases
		REM where there's ampersands, and then stripping these extra quotes so they're not included in any user Java options.
		REM If the URL contains ampersands, it must be wrapped in quotes. E.g: "http://www.example.com/game.php?foo=1&bar=2"
		SET "FLASHPOINT_ALL_ARGS="%*""
		SET "FLASHPOINT_ALL_ARGS=%FLASHPOINT_ALL_ARGS:"=%"
		SET FLASHPOINT_USER_OPTIONS=
		FOR /F "tokens=1,* delims= " %%A IN ("%FLASHPOINT_ALL_ARGS%") DO (
			SET "FLASHPOINT_USER_OPTIONS=%%B"
			REM %%A = URL
		)

		SET FLASHPOINT_DEPLOYMENT_CONSOLE_STARTUP_MODE=HIDE
		SET FLASHPOINT_DEPLOYMENT_SYSTEM_TRAY_ICON=false
		IF "%FLASHPOINT_DEBUG_MODE%"=="ON" (
			ECHO Enabling debug deployment properties...
			SET FLASHPOINT_DEPLOYMENT_CONSOLE_STARTUP_MODE=SHOW
			SET FLASHPOINT_DEPLOYMENT_SYSTEM_TRAY_ICON=true
		)

		REM Directories where the JDK tools and the JRE are, relative to this script.
		REM Variables that contain the current Flashpoint path are accessed using delayed expansions in case
		REM they have any characters that might be interpreted by the batch file (like ampersands).
		SET "FLASHPOINT_JAVA_PATH=%~dp0Java\JDK_1.8.0_181"
		SET "FLASHPOINT_JRE_PATH=!FLASHPOINT_JAVA_PATH!\jre"
		SET "FLASHPOINT_JRE_LIB_PATH=!FLASHPOINT_JRE_PATH!\lib"
		SET "FLASHPOINT_JRE_BIN_PATH=!FLASHPOINT_JRE_PATH!\bin"

		REM Create three properties that rely on absolute paths, while also changing/escaping certain characters:
		REM - in deployment.config: \ to / and spaces to \u0020
		REM - in deployment.properties: \ to \\ and : to \: and spaces to \u0020
		REM
		REM We'll also use the native2ascii tool that comes with the JDK to escape other non-alphanumeric characters
		REM with the \uXXXX notation.
		SET "FLASHPOINT_NATIVE2ASCII_PATH=!FLASHPOINT_JAVA_PATH!\bin\native2ascii.exe"

		SET "FLASHPOINT_DEPLOYMENT_SYSTEM_CONFIG=file:///!FLASHPOINT_JRE_LIB_PATH!\deployment.properties"
		SET "FLASHPOINT_DEPLOYMENT_SYSTEM_CONFIG=!FLASHPOINT_DEPLOYMENT_SYSTEM_CONFIG:\=/!"
		SET "FLASHPOINT_DEPLOYMENT_SYSTEM_CONFIG=!FLASHPOINT_DEPLOYMENT_SYSTEM_CONFIG: =\u0020!"

		SET "FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH=!FLASHPOINT_JRE_BIN_PATH!\javaw.exe"
		SET "FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH=!FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH:\=\\!"
		SET "FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH=!FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH::=\:!"
		SET "FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH=!FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH: =\u0020!"

		SET "FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES=!FLASHPOINT_JRE_LIB_PATH!\exception.sites"
		SET "FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES=!FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES:\=\\!"
		SET "FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES=!FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES::=\:!"
		SET "FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES=!FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES: =\u0020!"
		
		REM Create the deployment configuration file and the system-level deployment configuration properties.
		REM To learn more about these two files, see: https://docs.oracle.com/javase/8/docs/technotes/guides/deploy/properties.html
		REM To learn more about the Java Control Panel, see: https://docs.oracle.com/javase/8/docs/technotes/guides/deploy/jcp.html
		REM
		REM We'll first change the active console Code Page to UTF-8 so the two files are created with that encoding.
		REM This will allow us to always pick the correct encoding for native2ascii.exe, and avoids any issues that
		REM might arise because of default character encodings on Windows and in this tool.
		REM See: https://docs.microsoft.com/en-us/windows/win32/intl/code-page-identifiers
		SET FLASHPOINT_UTF_8_CODE_PAGE=65001
		SET FLASHPOINT_PREVIOUS_CODE_PAGE=
		FOR /F "tokens=2 delims=:" %%A IN ('CHCP') DO (
			SET "FLASHPOINT_PREVIOUS_CODE_PAGE=%%A"
		)
		CHCP %FLASHPOINT_UTF_8_CODE_PAGE% >NUL

		PUSHD "!FLASHPOINT_JRE_LIB_PATH!"

			ECHO Creating deployment.config...
			(
				ECHO # Deployment configuration for Flashpoint generated by %~nx0
				ECHO deployment.system.config=!FLASHPOINT_DEPLOYMENT_SYSTEM_CONFIG!
				ECHO deployment.system.config.mandatory=true
			) > deployment.config

			"!FLASHPOINT_NATIVE2ASCII_PATH!" -encoding UTF-8 deployment.config deployment.config

			ECHO Creating deployment.properties...
			(
				ECHO # Deployment properties for Flashpoint generated by %~nx0

				ECHO # --- Java Control Panel

				ECHO # General - Network Settings
				ECHO deployment.proxy.type=1
				ECHO deployment.proxy.type.locked
				ECHO deployment.proxy.http.host=127.0.0.1
				ECHO deployment.proxy.http.host.locked
				ECHO deployment.proxy.http.port=%FLASHPOINT_PROXY_PORT%
				ECHO deployment.proxy.http.port.locked
				ECHO deployment.proxy.bypass.local=
				ECHO deployment.proxy.bypass.local.locked
				ECHO deployment.proxy.socks.host=127.0.0.1
				ECHO deployment.proxy.socks.host.locked
				ECHO deployment.proxy.socks.port=
				ECHO deployment.proxy.socks.port.locked
				ECHO deployment.proxy.same=true
				ECHO deployment.proxy.same.locked
				ECHO deployment.proxy.bypass.list=
				ECHO deployment.proxy.bypass.list.locked

				ECHO # General - Temporary Internet Files
				ECHO deployment.cache.enabled=true
				ECHO deployment.cache.enabled.locked
				ECHO deployment.cache.jarcompression=0
				ECHO deployment.cache.jarcompression.locked
				ECHO deployment.cache.max.size=-1
				ECHO deployment.cache.max.size.locked

				ECHO # Update
				ECHO deployment.javaws.autodownload=NEVER
				ECHO deployment.javaws.autodownload.locked
				ECHO deployment.expiration.check.enabled=false
				ECHO deployment.expiration.check.enabled.locked
				ECHO deployment.expiration.decision=NEVER
				ECHO deployment.expiration.decision.locked
				ECHO deployment.expiration.decision.suppression=true
				ECHO deployment.expiration.decision.suppression.locked

				ECHO # Java
				ECHO deployment.javaws.jre.0.platform=1.8
				ECHO deployment.javaws.jre.0.platform.locked
				ECHO deployment.javaws.jre.0.product=1.8.0_181
				ECHO deployment.javaws.jre.0.product.locked
				ECHO deployment.javaws.jre.0.location=http\://java.sun.com/products/autodl/j2se
				ECHO deployment.javaws.jre.0.location.locked
				ECHO deployment.javaws.jre.0.path=!FLASHPOINT_DEPLOYMENT_JAVAWS_JRE_0_PATH!
				ECHO deployment.javaws.jre.0.path.locked
				ECHO deployment.javaws.jre.0.args=
				ECHO deployment.javaws.jre.0.args.locked
				ECHO deployment.javaws.jre.0.enabled=true
				ECHO deployment.javaws.jre.0.enabled.locked
				ECHO deployment.javaws.jre.0.osarch=x86
				ECHO deployment.javaws.jre.0.osarch.locked
				ECHO deployment.javaws.jre.0.osname=Windows
				ECHO deployment.javaws.jre.0.osname.locked
				ECHO deployment.javaws.jre.0.registered=false
				ECHO deployment.javaws.jre.0.registered.locked
				ECHO deployment.version=8
				ECHO deployment.version.locked

				ECHO # Security - Enable Java content for browser and Web Start applications
				ECHO deployment.webjava.enabled=true
				ECHO deployment.webjava.enabled.locked

				ECHO # Security - Security level for applications not on the Exception Site list
				ECHO deployment.security.level=HIGH
				ECHO deployment.security.level.locked

				ECHO # Security - Exception Site List
				ECHO deployment.user.security.exception.sites=!FLASHPOINT_DEPLOYMENT_USER_SECURITY_EXCEPTION_SITES!
				ECHO deployment.user.security.exception.sites.locked

				ECHO # Advanced - Debugging
				ECHO deployment.trace=false
				ECHO deployment.trace.locked
				ECHO deployment.log=false
				ECHO deployment.log.locked
				ECHO deployment.javapi.lifecycle.exception=false
				ECHO deployment.javapi.lifecycle.exception.locked

				ECHO # Advanced - Java console
				ECHO deployment.console.startup.mode=%FLASHPOINT_DEPLOYMENT_CONSOLE_STARTUP_MODE%
				ECHO deployment.console.startup.mode.locked

				ECHO # Advanced - Default Java for browsers
				ECHO deployment.browser.vm.iexplorer=true
				ECHO deployment.browser.vm.iexplorer.locked
				ECHO deployment.browser.vm.mozilla=true
				ECHO deployment.browser.vm.mozilla.locked

				ECHO # Advanced - Shortcut Creation
				ECHO deployment.javaws.associations=ALWAYS
				ECHO deployment.javaws.associations.locked

				ECHO # Advanced - JNLP File/MIME Association
				ECHO deployment.javaws.shortcut=NEVER
				ECHO deployment.javaws.shortcut.locked

				ECHO # Advanced - Application Installation
				ECHO deployment.javaws.install=NEVER
				ECHO deployment.javaws.install.locked

				ECHO # Advanced - Execution Environment Security Settings
				ECHO deployment.security.askgrantdialog.show=true
				ECHO deployment.security.askgrantdialog.show.locked
				ECHO deployment.security.sandbox.awtwarningwindow=true
				ECHO deployment.security.sandbox.awtwarningwindow.locked
				ECHO deployment.security.sandbox.jnlp.enhanced=true
				ECHO deployment.security.sandbox.jnlp.enhanced.locked
				ECHO deployment.security.clientauth.keystore.auto=true
				ECHO deployment.security.clientauth.keystore.auto.locked
				ECHO deployment.security.jsse.hostmismatch.warning=false
				ECHO deployment.security.jsse.hostmismatch.warning.locked
				ECHO deployment.security.https.warning.show=false
				ECHO deployment.security.https.warning.show.locked

				ECHO # Advanced - Mixed code, sandboxed vs. trusted, security verification
				ECHO deployment.security.mixcode=DISABLE
				ECHO deployment.security.mixcode.locked

				ECHO # Advanced - Perform signed code certificate revocation checks on
				ECHO deployment.security.revocation.check=NO_CHECK
				ECHO deployment.security.revocation.check.locked

				ECHO # Advanced - Check for signed code certificate revocation using
				ECHO deployment.security.validation.ocsp=false
				ECHO deployment.security.validation.ocsp.locked
				ECHO deployment.security.validation.crl=false
				ECHO deployment.security.validation.crl.locked

				ECHO # Advanced - Perform TLS certificate revocation checks on
				ECHO deployment.security.tls.revocation.check=NO_CHECK
				ECHO deployment.security.tls.revocation.check.locked

				ECHO # Advanced - Advanced Security Settings
				ECHO deployment.security.use.native.sandbox=false
				ECHO deployment.security.use.native.sandbox.locked
				ECHO deployment.security.browser.keystore.use=false
				ECHO deployment.security.browser.keystore.use.locked
				ECHO deployment.security.blacklist.check=false
				ECHO deployment.security.blacklist.check.locked
				ECHO deployment.security.password.cache=false
				ECHO deployment.security.password.cache.locked
				ECHO deployment.security.SSLv2Hello=false
				ECHO deployment.security.SSLv2Hello.locked
				ECHO deployment.security.TLSv1=false
				ECHO deployment.security.TLSv1.locked
				ECHO deployment.security.TLSv1.1=false
				ECHO deployment.security.TLSv1.1.locked
				ECHO deployment.security.TLSv1.2=false
				ECHO deployment.security.TLSv1.2.locked

				ECHO # Advanced - Miscellaneous
				ECHO deployment.roaming.profile=false
				ECHO deployment.roaming.profile.locked
				ECHO deployment.system.tray.icon=%FLASHPOINT_DEPLOYMENT_SYSTEM_TRAY_ICON%
				ECHO deployment.system.tray.icon.locked
				ECHO install.disable.sponsor.offers=true
				ECHO install.disable.sponsor.offers.locked
				
				ECHO # --- Other properties not listed in the Java Control Panel

				ECHO deployment.baseline.url=
				ECHO deployment.baseline.url.locked

				ECHO deployment.security.askgrantdialog.notinca=true
				ECHO deployment.security.askgrantdialog.notinca.locked
				ECHO deployment.security.notinca.warning=false
				ECHO deployment.security.notinca.warning.locked

				ECHO deployment.security.tls.validation.crl=false
				ECHO deployment.security.tls.validation.crl.locked
				ECHO deployment.security.tls.validation.ocsp=false
				ECHO deployment.security.tls.validation.ocsp.locked

				ECHO deployment.security.permissions.manifest.required=false
				ECHO deployment.security.permissions.manifest.required.locked

			) > deployment.properties

			"!FLASHPOINT_NATIVE2ASCII_PATH!" -encoding UTF-8 deployment.properties deployment.properties

		POPD

		CHCP %FLASHPOINT_PREVIOUS_CODE_PAGE% >NUL

		REM Environment variable that helps disable Java's update prompt
		SET deployment.expiration.check.enabled=false

		REM Path to the master security properties file that we want to use instead of the local one.
		REM Even when we use the java_version parameter to force Java to select a specific version to run the applet,
		REM it will start by running it with the latest installed version before quickly running it again with the
		REM one we told it to use. This can stop an applet from running if it uses a cryptographic algorithm that is disabled
		REM in the local security properties file. We'll tell it to use our own java.security file which has no disabled algorithms.
		SET "FLASHPOINT_JAVA_SECURITY_PROPERTIES_PATH=file:///!FLASHPOINT_JRE_LIB_PATH!\security\java.security"
		SET "FLASHPOINT_JAVA_SECURITY_PROPERTIES_PATH=!FLASHPOINT_JAVA_SECURITY_PROPERTIES_PATH:\=/!"

		REM Define the Java command line arguments that should be passed to the JVM. We'll use the security properties file
		REM option (which is necessary to get Java games working in a browser in Flashpoint ) and user defined options (which may
		REM be used to get certain games working or for debugging).
		REM Two equals to override the local security properties file. One equals to append to it.
		REM Wrapped in quotes in case the path has spaces. We can't escape characters using the \uXXXX notation here.
		SET "FLASHPOINT_BASE_OPTIONS=-Djava.security.properties=="!FLASHPOINT_JAVA_SECURITY_PROPERTIES_PATH!""
		SET "FLASHPOINT_JAVA_OPTIONS=!FLASHPOINT_BASE_OPTIONS! %FLASHPOINT_DEBUG_OPTIONS% %FLASHPOINT_USER_OPTIONS%"

		IF DEFINED FLASHPOINT_DEBUG_OPTIONS (
			ECHO Passing the debug arguments %FLASHPOINT_DEBUG_OPTIONS% to the JVM...
		)
		IF DEFINED FLASHPOINT_USER_OPTIONS (
			ECHO Passing the launch command arguments %FLASHPOINT_USER_OPTIONS% to the JVM...
		)

		REM Environment variable used to pass command line arguments to the JVM.
		REM See: https://docs.oracle.com/javase/8/docs/technotes/guides/troubleshoot/envvars002.html
		SET "JAVA_TOOL_OPTIONS=!FLASHPOINT_JAVA_OPTIONS!"
		SET _JAVA_OPTIONS=

		REM Path to user level deployment properties file. We'll delete this file before and after running applets in our portable browser.
		REM This is mainly to prevent any unwanted interactions, like being able to still see the portable Java version in your 
		REM local Java Control Panel after using Flashpoint.
		REM This file is created every time an applet, a Java Web Start application or the Java Control Panel are run.
		SET "FLASHPOINT_JAVA_USER_DEPLOYMENT_PROPERTIES_PATH=%USERPROFILE%\AppData\LocalLow\Sun\Java\Deployment\deployment.properties"

		IF EXIST "%FLASHPOINT_JAVA_USER_DEPLOYMENT_PROPERTIES_PATH%" (
			DEL "%FLASHPOINT_JAVA_USER_DEPLOYMENT_PROPERTIES_PATH%"
		)

		REM Environment variable that tells Basilisk where to look for the Java Plugin files. This path will be added to MOZ_PLUGIN_PATH when
		REM Basilisk runs, as specified in the Basilisk-Portable.ini file. This variable is only set when an applet is launched using this script.
		REM When Basilisk is launched normally, the literal value "%FP_JAVA_PATH%" will be added to MOZ_PLUGIN_PATH instead.
		REM Note that the plugin.scan.plid.all preference in about:config must be set to false to prevent Basilisk from finding any plugins
		REM using the Windows registry. This would be problem since it could potentially find and use a newer version of the Java plugin.
		REM See: https://developer.mozilla.org/en-US/docs/Plugins/Guide/Plug-in_Basics#How_Gecko_Finds_Plug-ins
		SET "FP_JAVA_PATH=!FLASHPOINT_JRE_BIN_PATH!\plugin2"
		
		REM Change DPI settings to System (Enhanced).
		SET __COMPAT_LAYER=GDIDPISCALING DPIUNAWARE
		
		ECHO Running Java applet in Basilisk...
		"Basilisk-Portable\Basilisk-Portable.exe" "!FLASHPOINT_URL!"
		REM The user.dir system property in Java will be set to "Basilisk-Portable\Bin\basilisk"
		REM We need to wait for the browser to finish running before continuing.

		IF EXIST "%FLASHPOINT_JAVA_USER_DEPLOYMENT_PROPERTIES_PATH%" (
			DEL "%FLASHPOINT_JAVA_USER_DEPLOYMENT_PROPERTIES_PATH%"
		)

	POPD
ENDLOCAL
