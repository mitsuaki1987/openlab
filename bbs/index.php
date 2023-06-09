<?php
  $fp = fopen('data.csv', 'a+b');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittime = date("c");
    $submitid = date("U");
    $uploadfile = $submitid . "_" . $_FILES['avatar']['name'];
    $thisurl = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

    if ($submittype === 'text' or $submittype === 'file' or $submittype === 'image' or $submittype === 'freehand'){
      $rssxml = fopen('rss.xml', 'w');
      fwrite($rssxml, "<?xml version='1.0' encoding='UTF-8'?>\n");
      fwrite($rssxml, "<rss version='2.0'>\n");
      fwrite($rssxml, "  <channel>\n");
      fwrite($rssxml, "    <title>結晶とシミュレーションなんでも掲示板</title>\n");
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
    }

    if ($_POST['submittype'] === 'text') {
      fputcsv($fp, [$_POST['name'], $_POST['comment'], $_POST['submittype'], $submittime, $submitid]);
    }
    elseif ($_POST['submittype'] === 'image') {
      fputcsv($fp, [$_POST['name'], $uploadfile, $_POST['submittype'], $submittime, $submitid, $_FILES['avatar']['tmp_name']]);
      move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile);
      chmod($uploadfile, 0777);
    }
    elseif ($_POST['submittype'] === 'file') {
      fputcsv($fp, [$_POST['name'], $uploadfile, $_POST['submittype'], $submittime, $submitid, $_FILES['avatar']['tmp_name']]);
      move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile);
      chmod($uploadfile, 0777);
    }
    elseif ($_POST['submittype'] === 'freehand') {
      $canvas = $_POST['comment'];
      $canvas = base64_decode($canvas);
      $image = imagecreatefromstring($canvas);
      $uploadfile = date("U") . ".png";
      imagesavealpha($image, TRUE);
      imagepng($image , $uploadfile);
  
      fputcsv($fp, [$_POST['name'], $uploadfile, $_POST['submittype'], $submittime, $submitid]);
      chmod($uploadfile, 0777);
    }
    rewind($fp);
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
    <title>なんでも掲示板</title>
  </head>
  <body bgcolor="CCFFCC">
    <h1>なんでも掲示板</h1>

    <h2>
    <p>結晶のこと、物性のこと、コンピューターシミュレーションのことや研究室生活など、なんでも聞いてみましょう！</p>
    <p>iPadなどのタッチパネルで絵を描くことも出来ます。</p>    
    </h2>

    <h3>ホームページ製作(HTML)に詳しい人向けの情報</h3>
    <ul>
      <li><code>\[ \]</code> : LaTex 数式</li>
      <li><code>$ $</code> : LaTex 数式</li>
      <li><code>&lt;ul&gt;&lt;li&gt;&lt;/li&gt;&lt;/ul&gt;</code> : 箇条書き</li>
      <li><code>&lt;br&gt;</code> : 改行</li>
      <li><a href="./rss.xml">RSS</a></li>
    </ul>
    
    <h2>コメント</h2>

    <?php if (!empty($rows)): ?>
      <ol>
        <?php foreach ($rows as $row): ?>
          <?php if ($row[2] == 'text'): ?>
            <li><?=$row[1]?> (<?=$row[2]?> by <?=$row[0]?> at <?=$row[3]?>)</li>
          <?php elseif ($row[2] == 'freehand'): ?>
            <li><img src="<?=$row[1]?>" height="400" align="middle"> (<?=$row[2]?> by <?=$row[0]?> at <?=$row[3]?>)</li>
          <?php elseif ($row[2] == 'image'): ?>
            <li><img src="<?=$row[1]?>" height="400" align="middle"> (<?=$row[2]?> by <?=$row[0]?> at <?=$row[3]?>)</li>
          <?php elseif ($row[2] == 'file'): ?>
            <li><a href="<?=$row[1]?>"><?=$row[1]?></a> (<?=$row[2]?> by <?=$row[0]?> at <?=$row[3]?>)</li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ol>
    <?php else: ?>
      <p>コメントはまだありません。</p>
    <?php endif; ?>

    <h2>コメントする</h2>

    <input type="button" value="投稿する" id="canvassubmit">
    <form id="submittype">
      <input type="radio" name="subtype" value="text" checked="checked">文章
      <input type="radio" name="subtype" value="image">画像貼り付け
      <input type="radio" name="subtype" value="file">ファイル貼り付け
      <input type="radio" name="subtype" value="freehand">フリーハンド
    </form>
    <form enctype="multipart/form-data" id="submitting" action="" method="post">
      名前(ニックネームなど): <input type="text" name="name" id="submitname" value="">
      </br>
      コメント:
      </br>
      <textarea name="comment" id="comment" cols="40" rows="5" maxlength="1000" wrap="hard"></textarea>
      </br>
      プレビュー:
      </br>
      <div id="preview"></div>
      </br>
      ファイル: <input type="file" id="file" name="filename">
      <br>
    </form>
    フリーハンドお絵描き(タッチパネルなど):
    <input type="button" value="キャンバスのクリア" onclick="clearCanvas();">
    <br>
    キャンバスの幅: <input type="text" id="width" value="600">
    高さ: <input type="text" id="height" value="600">
    <input type="button" value="キャンバスサイズ変更" onclick="resizeCanvas();">
    <br>
    <form id="linecolor">
      <input type="radio" name="color" value="black" checked="checked">黒
      <input type="radio" name="color" value="white">白
      <input type="radio" name="color" value="red">赤
      <input type="radio" name="color" value="green">緑
      <input type="radio" name="color" value="blue">青
    </form>
    <canvas id="canvas" width="600" height="600" style="border:solid black 1px;"></canvas>
    <pre id="log" style="border: 1px solid #ccc;"></pre>
<script type="text/javascript">
function startup() {
    var el = document.getElementById("canvas");
    el.addEventListener("touchstart", handleStart, false);
    el.addEventListener("touchend", handleEnd, false);
    el.addEventListener("touchcancel", handleCancel, false);
    el.addEventListener("touchmove", handleMove, false);
}

document.addEventListener("DOMContentLoaded", startup);
var ongoingTouches = [];

function handleStart(evt) {
    evt.preventDefault();
    var el = document.getElementById("canvas");
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    
    for (var i = 0; i < touches.length; i++) {
        ongoingTouches.push(copyTouch(touches[i]));
    }
}

function handleMove(evt) {
    evt.preventDefault();
    var el = document.getElementById("canvas");
    var ctx = el.getContext("2d");
    var rect = el.getBoundingClientRect()
    var touches = evt.changedTouches;
    var linecolor = document.getElementById('linecolor').color.value;
    
    for (var i = 0; i < touches.length; i++) {
        var idx = ongoingTouchIndexById(touches[i].identifier);

        if (idx == 0) {
            ctx.beginPath();
            ctx.moveTo(ongoingTouches[idx].clientX -  rect.left, ongoingTouches[idx].clientY - rect.top);
            ctx.lineTo(touches[i].clientX - rect.left, touches[i].clientY - rect.top);
            ctx.lineWidth = 4;
            ctx.strokeStyle = linecolor;
            ctx.stroke();

            ongoingTouches.splice(idx, 1, copyTouch(touches[i]));  // swap in the new touch record
        }
    }
}
function handleEnd(evt) {
    evt.preventDefault();
    var el = document.getElementById("canvas");
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;

    //log("touchend");
    for (var i = 0; i < touches.length; i++) {
        var idx = ongoingTouchIndexById(touches[i].identifier);

        if (idx >= 0) {
            ongoingTouches.splice(idx, 1);  // remove it; we're done
        }
    }
}
function handleCancel(evt) {
    evt.preventDefault();
    var touches = evt.changedTouches;
    
    for (var i = 0; i < touches.length; i++) {
        var idx = ongoingTouchIndexById(touches[i].identifier);
        ongoingTouches.splice(idx, 1);  // remove it; we're done
    }
}
function copyTouch({ identifier, clientX, clientY }) {
    return { identifier, clientX, clientY };
}

function ongoingTouchIndexById(idToFind) {
    for (var i = 0; i < ongoingTouches.length; i++) {
        var id = ongoingTouches[i].identifier;
        
        if (id == idToFind) {
            return i;
        }
    }
    return -1;    // not found
}
function clearCanvas() {
    var el = document.getElementById("canvas");
    var ctx = el.getContext("2d");

    ctx.clearRect(0, 0, el.width, el.height);
}
function resizeCanvas() {
    var el = document.getElementById("canvas");
    var width = document.getElementById('width').value;
    var height = document.getElementById('height').value;

    el.setAttribute("width", width);
    el.setAttribute("height", height);
}
function log(msg) {
  var p = document.getElementById('log');
  p.innerHTML = msg + "\n" + p.innerHTML;
}

window.onload = function() {
  document.getElementById('canvassubmit').onclick = function() {
    post();
  };
};

function post() {
    var fd = new FormData();
    
    var submittype = document.getElementById('submittype').subtype.value;
    fd.append('submittype',submittype);

    var name = document.getElementById('submitname').value;
    fd.append('name',name);

    if(submittype == "text"){
        var comment = document.getElementById('comment').value;
        fd.append('comment', comment);
    }
    else if(submittype == "image"){
        const file = document.getElementById("file").files[0];
        fd.append('avatar', file);
    }
    else if(submittype == "file"){
        const file = document.getElementById("file").files[0];
        fd.append('avatar', file);
    }
    else if(submittype == "freehand"){
        img_url = canvas.toDataURL("image/png").replace(new RegExp("data:image/png;base64,"),"");
        fd.append('comment',img_url);
    }
    
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
    </hr>
    This BBS operates with <a href="https://ja.osdn.net/projects/sciencebbs/">ScienceBBS</a>.
  </body>
</html>
