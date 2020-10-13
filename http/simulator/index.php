<html>
  <head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=utf-8">
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({ tex2jax: { inlineMath: [['$','$'], ["\\(","\\)"]] } });
    </script>
    <script type="text/javascript"
            src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML">
    </script>
    <meta http-equiv="X-UA-Compatible" CONTENT="IE=EmulateIE7" />

    <title>物性研の計算機でシミュレーションする</title>

  </head>
  <body bgcolor="CCFFCC">

  <h2>計算をしています・・・</h2>

<?php
    $submitid = date("U");
    $scfname = $submitid . ".in";
    $pwcommand = "./pw.x -in " . $scfname;
    $fermicommand = "./fermi_velocity.x -in " . $scfname;

    $scfin = fopen($scfname, 'w');
    fwrite($scfin, "&CONTROL\n");
    fwrite($scfin, "  calculation = 'scf'\n");
    fwrite($scfin, "  prefix = '" . $submitid . "'\n");
    fwrite($scfin, "  pseudo_dir = 'pseudo'\n");
    fwrite($scfin, "/\n");
    fwrite($scfin, "&SYSTEM\n");
    fwrite($scfin, "  ibrav = 1\n");
    fwrite($scfin, "  celldm(1) = 5.0\n");
    fwrite($scfin, "  nat = 2\n");
    fwrite($scfin, "  ntyp = 2\n");
    fwrite($scfin, "  ecutwfc = 50.0\n");
    fwrite($scfin, "  ecutrho = 200.0\n");
    fwrite($scfin, "  occupations = 'tetrahedra_opt'\n");
    fwrite($scfin, "/\n");
    fwrite($scfin, "&ELECTRONS\n");
    fwrite($scfin, "  electron_maxstep = 20\n");
    fwrite($scfin, "/\n");
    fwrite($scfin, "ATOMIC_SPECIES\n");
    fwrite($scfin, "  Cu 1.0 Cu.UPF\n");
    fwrite($scfin, "  Al 1.0 Al.UPF\n");
    fwrite($scfin, "ATOMIC_POSITIONS crystal\n");
    fwrite($scfin, "  Cu 0.0 0.0 0.0\n");
    fwrite($scfin, "  Al 0.5 0.5 0.5\n");
    fwrite($scfin, "K_POINTS automatic\n");
    fwrite($scfin, "8 8 8 0 0 0");
    fclose($scfin);

    echo "<pre>";
    system($pwcommand);
    echo "</pre>";
    echo "<hr>";
    echo "<pre>";
    system($fermicommand);
    echo "</pre>";
    echo "<hr>";
    echo "<h2>計算完了</h2>";
    echo '<h3><a href="' .  $submitid . '.frmsf" download="' .  $submitid . '.frmsf">フェルミ面のダウンロード</a></h3>';
?>

  </body>
</html>
