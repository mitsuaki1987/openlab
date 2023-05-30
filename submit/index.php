<?php
  $fp = fopen('data.csv', 'a+b');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittime = date("c");
    $submitid = date("U");
    $uploadfile = $submitid . "_" . $_FILES['avatar']['name'];
    $thisurl = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

    if($_FILES['avatar']['name'] != '') {
      $rssxml = fopen('rss.xml', 'w');
      fwrite($rssxml, "<?xml version='1.0' encoding='UTF-8'?>\n");
      fwrite($rssxml, "<rss version='2.0'>\n");
      fwrite($rssxml, "  <channel>\n");
      fwrite($rssxml, "    <title>身の回りにある結晶を投稿してみよう</title>\n");
      fwrite($rssxml, "    <link>" . $thisurl . "</link>\n");
      fwrite($rssxml, "    <description></description>\n");
      fwrite($rssxml, "    <item>\n");
      fwrite($rssxml, "      <title>" . $_POST['name'] . " がコメントしました。</title>\n");
      fwrite($rssxml, "      <link>" . $thisurl . "</link>\n");
      fwrite($rssxml, "      <guid>" . $thisurl . "#". $submitid . "</guid>\n");
      fwrite($rssxml, "      <description></description>\n");
      fwrite($rssxml, "      <pubDate>" . $submittime . "</pubDate>\n");
      fwrite($rssxml, "    </item>\n");
      fwrite($rssxml, "  </channel>\n");
      fwrite($rssxml, "</rss>\n");
      fclose($rssxml);

      fputcsv($fp, [$_POST['name'], $uploadfile, $_POST['comment'], $submittime, $submitid]);
      move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile);
      chmod($uploadfile, 0777);
      rewind($fp);
    }
  }
  
  while ($row = fgetcsv($fp)) {
    $rows[] = $row;
  }
  
  fclose($fp);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" 
          content="text/html; charset=utf-8">
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({ tex2jax: { inlineMath: [['$','$'], ["\\(","\\)"]] }, displayAlign: "left" });
    </script>
    <script type="text/javascript"
            src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML">
    </script>
    <meta http-equiv="X-UA-Compatible" CONTENT="IE=EmulateIE7" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
  function makePreview() {
    input = $('#comment').val();
    $('#preview').html(input);
    MathJax.Hub.Queue(["Typeset",MathJax.Hub,"preview"]);
  }
  $('body').keyup(function(){makePreview()});
  $('body').bind('updated',function(){makePreview()});
  makePreview();
});
</script>
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
	readyFunction: null
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
    <title>身の回りにある結晶を投稿してみよう</title>
  </head>
  <body bgcolor="CCFFCC">
    <h1>身の回りにある結晶を投稿してみよう</h1>

    <h2>
    <p>身の回りにある結晶を投稿してみましょう！
    下にある結晶構造を見ることができます。
    </p>
    </h2>

    <h3>ホームページ製作(HTML)に詳しい人向けの情報</h3>
    <ul>
      <li><code>\[ \]</code> : LaTex 数式</li>
      <li><code>$ $</code> : LaTex 数式</li>
      <li><code>&lt;ul&gt;&lt;li&gt;&lt;/li&gt;&lt;/ul&gt;</code> : 箇条書き</li>
      <li><code>&lt;br&gt;</code> : 改行</li>
      <li><a href="./rss.xml">RSS</a></li>
    </ul>
    
	<span id=mydiv></span>
	<span id=btns></span>
	URL: <input type="text" name="name" id="cifurl" value="">
	<input type="button" value="URLから開く" id="openurl" onclick="opencifurl()">
	<br />
	<a href="javascript:opencif('../cif/1502689.cif')">アルミニウム</a>
	<a href="javascript:opencif('../cif/2101052.cif')">ルビーやサファイア</a>
	<a href="javascript:opencif('../cif/1532664.cif')">高温超伝導体</a>
	<a href="javascript:opencif('../cif/1010060.cif')">ドライアイス</a>
	<a href="javascript:opencif('../cif/9008564.cif')">ダイヤモンド</a>
	<a href="javascript:opencif('../cif/9006598.cif')">鉄</a>
	<a href="javascript:opencif('../cif/9008868.cif')">青色発光ダイオード</a>
	<a href="javascript:opencif('../cif/1200018.cif')">黒鉛</a>
	<a href="javascript:opencif('../cif/1011023.cif')">氷</a>
	<a href="javascript:opencif('../cif/4505482.cif')">リチウムイオン電池正極</a>
	<a href="javascript:opencif('../cif/1521011.cif')">下部マントル</a>
	<a href="javascript:opencif('../cif/1000041.cif')">しお(岩塩)</a>
	<a href="javascript:opencif('../cif/1008718.cif')">ネオジム磁石</a>
	<a href="javascript:opencif('../cif/9012600.cif')">水晶</a>
	<a href="javascript:opencif('../cif/3500015.cif')">砂糖(スクロース)</a>
	<a href="javascript:opencif('../cif/9011048.cif')">重曹</a>
	<a href="javascript:opencif('../cif/2100992.cif')">チョーク(炭酸カルシウム)</a>
	<a href="javascript:opencif('../cif/9008463.cif')">金</a>
	<a href="javascript:opencif('../cif/9008459.cif')">銀</a>
	<a href="javascript:opencif('../cif/9008468.cif')">銅</a>

<script type="text/javascript">
function opencif(source) {
			var jmolscript = 'load '
				+ source
				+ ' {3,3,3};'
				+ 'set perspectiveDepth ON; select; set defaultLabelXYZ \"%e\";'
				+ 'set labelToggle;'
				+ 'set labelAtom;'
				+ 'set labelOffset 0 0;'
				+ 'color labels black';

			Jmol.script(jmolApplet0, jmolscript);
}

		function opencifurl(){
        var name = document.getElementById('cifurl').value;
		opencif(name);
        }
	</script>

    <h2>投稿した物質の写真や絵</h2>

    <?php if (!empty($rows)): ?>
      <ul>
        <?php foreach ($rows as $row): ?>
          <li>
            <img src="<?=$row[1]?>" height="400" align="middle"> 
            <?=$row[2]?>
            (by <?=$row[0]?> at <?=$row[3]?>)
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>投稿はまだありません。</p>
    <?php endif; ?>

    <h2>投稿する</h2>

    <form enctype="multipart/form-data" id="submitting" action="" method="post">
      名前(ニックネームなど): <input type="text" name="name" id="submitname" value="">
      </br>
      物質の写真や絵: <input type="file" id="file" name="filename">
      <br>
      コメント:
      </br>
      <textarea name="comment" id="comment" cols="40" rows="5" maxlength="1000" wrap="hard"></textarea>
      </br>
      プレビュー:
      </br>
      <div id="preview"></div>
      </br>
      <input type="button" value="投稿する" id="canvassubmit">
    </form>
<script type="text/javascript">
window.onload = function() {
  document.getElementById('canvassubmit').onclick = function() {
    post();
  };
};

function post() {
    var fd = new FormData();
    
    var name = document.getElementById('submitname').value;
    fd.append('name',name);
    const file = document.getElementById("file").files[0];
    fd.append('avatar', file);
    var comment = document.getElementById('comment').value;
    fd.append('comment', comment);
    
    const param = {
        method: "POST",
        body: fd
    }
    fetch("./index.php", param).then((res) => {
        if (res.ok) {
            window.location.reload();
        }
    });
}
</script>
  </body>
</html>
