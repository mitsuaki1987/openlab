<!DOCTYPE html>
<html>
<head>
<title>結晶と物性のせかい</title>
<meta charset="utf-8">
<script type="text/javascript" src="../JSmol.min.js"></script>
<script type="text/javascript"> 

// supersimple2.htm - illustrating the use of jQuery(document).ready to 
// populate all spans and divs AFTER the page is loaded.
// This is good programming practice.

$(document).ready(

function() {

Info = {
	width: 600,
	height: 600,
	debug: false,
	j2sPath: "../j2s",
	color: "0xC0C0C0",
  disableJ2SLoadMonitor: true,
  disableInitialConsole: true,
	addSelectionOptions: false,
	serverURL: "https://chemapps.stolaf.edu/jmol/jsmol/php/jsmol.php",
	use: "HTML5",
	readyFunction: null,
    script: "load <?=$_GET['ciffile']?> {3,3,3}; set perspectiveDepth ON; select;set defaultLabelXYZ \"%e\"; set labelToggle; set labelAtom; set labelOffset 0 0; color labels black"
}

$("#mydiv").html(Jmol.getAppletHtml("jmolApplet0",Info))
$("#btns").html(
	Jmol.jmolButton(jmolApplet0, "write frames {*} \"crystal.jpg\"","画像を保存")
 +Jmol.jmolButton(jmolApplet0, "stereo on","立体視オン")
 +Jmol.jmolButton(jmolApplet0, "stereo off","立体視オフ")
 )
}


);



</script>
</head>
<body bgcolor="CCFFCC">
<span id=mydiv></span>
<span id=btns></span>
</body>
</html>
