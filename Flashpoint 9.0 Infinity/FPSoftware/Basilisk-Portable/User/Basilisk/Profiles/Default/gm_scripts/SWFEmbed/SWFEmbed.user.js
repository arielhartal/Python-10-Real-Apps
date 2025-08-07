// ==UserScript==
// @name         SWFEmbed
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  try to take over the world!
// @author       Flashpoint
// @match        http://*/*.swf*
// @run-at       document-start
// @grant        none
// ==/UserScript==

window.stop();
if (window.location.hash) {
  window.document.documentElement.innerHTML = `
<body marginwidth="0" marginheight="0">
  <object data="${window.location.href}" type="application/x-shockwave-flash" width="100%" height="100%">
    <param name="wmode" value="direct">
    <param name="allowfullscreen" value="true">
    <param name="allowscriptaccess" value="always">
    <param name="flashvars" value="${window.location.search.substring(1)}">
  </object>
</body>`;
  return;
}

var path = window.location.href.split('?')[0].substring(7);
var req = new XMLHttpRequest();
req.open('GET', 'http://localhost:22500/embed.php?file=' + path, false);
req.send();
var data = JSON.parse(req.responseText);
var player = window.open('', '_blank', `width=${data['FrameWidth']},height=${data['FrameHeight']},location=no`);
var style;
if (data['FrameHeight'] < data['FrameWidth']) {
  style = `margin: 0px auto; height: 100%; width: calc(calc(${data['FrameWidth']}/${data['FrameHeight']}) * 100vh)`;
} else {
  style = `margin: 0px auto; height: calc(calc(${data['FrameWidth']}/${data['FrameHeight']}) * 100vh); width: 100%`;
}
player.document.documentElement.innerHTML = `
<body marginwidth="0" marginheight="0" style="background-color: black">
  <div style="${style}">
    <iframe src="${window.location.href}#popup" width="100%" height="100%" style="border: 0px"></iframe>
  </div>
</body>`
window.close();