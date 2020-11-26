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
    $jscommand = "./frmsf2js.sh " . $submitid . ".frmsf";
    $lattice = $_GET['lattice'];
    $alat = $_GET['alat'];
    $atom1 = $_GET['atom1'];
    $atom2 = $_GET['atom2'];
    $atom = array(
        "1" => "H",
        "2" => "He",
        "3" => "Li",
        "4" => "Be",
        "5" => "B",
        "6" => "C",
        "7" => "N",
        "8" => "O",
        "9" => "F",
        "10" => "Ne",
        "11" => "Na",
        "12" => "Mg",
        "13" => "Al",
        "14" => "Si",
        "15" => "P",
        "16" => "S",
        "17" => "Cl",
        "18" => "Ar",
        "19" => "K",
        "20" => "Ca",
        "21" => "Sc",
        "22" => "Ti",
        "23" => "V",
        "24" => "Cr",
        "25" => "Mn",
        "26" => "Fe",
        "27" => "Co",
        "28" => "Ni",
        "29" => "Cu",
        "30" => "Zn",
        "31" => "Ga",
        "32" => "Ge",
        "33" => "As",
        "34" => "Se",
        "35" => "Br",
        "36" => "Kr",
        "37" => "Rb",
        "38" => "Sr",
        "39" => "Y",
        "40" => "Zr",
        "41" => "Nb",
        "42" => "Mo",
        "43" => "Tc",
        "44" => "Ru",
        "45" => "Rh",
        "46" => "Pd",
        "47" => "Ag",
        "48" => "Cd",
        "49" => "In",
        "50" => "Sn",
        "51" => "Sb",
        "52" => "Te",
        "53" => "I",
        "54" => "Xe",
        "55" => "Cs",
        "56" => "Ba",
        "57" => "La",
        "58" => "Ce",
        "59" => "Pr",
        "60" => "Nd",
        "61" => "Pm",
        "62" => "Sm",
        "63" => "Eu",
        "64" => "Gd",
        "65" => "Tb",
        "66" => "Dy",
        "67" => "Ho",
        "68" => "Er",
        "69" => "Tm",
        "70" => "Yb",
        "71" => "Lu",
        "72" => "Hf",
        "73" => "Ta",
        "74" => "W",
        "75" => "Re",
        "76" => "Os",
        "77" => "Ir",
        "78" => "Pt",
        "79" => "Au",
        "80" => "Hg",
        "81" => "Tl",
        "82" => "Pb",
        "83" => "Bi",
        "84" => "Po"
    );

    $scfin = fopen($scfname, 'w');
    fwrite($scfin, "&CONTROL\n");
    fwrite($scfin, "  calculation = 'scf'\n");
    fwrite($scfin, "  prefix = '" . $submitid . "'\n");
    fwrite($scfin, "  pseudo_dir = 'pseudo'\n");
    fwrite($scfin, "/\n");
    fwrite($scfin, "&SYSTEM\n");
    if ($lattice === 'nacl') {
      fwrite($scfin, "  ibrav = 2\n");
    }
    elseif ($lattice === 'cscl') {
      fwrite($scfin, "  ibrav = 1\n");
    }
    elseif ($lattice === 'znblende') {
      fwrite($scfin, "  ibrav = 2\n");
    }
    fwrite($scfin, "  celldm(1) = " . $alat . "\n");
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
    fwrite($scfin, "  " . $atom[$atom1] . " 1.0 " . $atom[$atom1] . ".UPF\n");
    fwrite($scfin, "  " . $atom[$atom2] . " 1.0 " . $atom[$atom2] . ".UPF\n");
    fwrite($scfin, "ATOMIC_POSITIONS crystal\n");
    fwrite($scfin, "  " . $atom[$atom1] . " 0.0 0.0 0.0\n");
    if ($lattice === 'nacl') {
      fwrite($scfin, "  " . $atom[$atom2] . " 0.5 0.5 0.5\n");
    }
    elseif ($lattice === 'cscl') {
      fwrite($scfin, "  " . $atom[$atom2] . " 0.5 0.5 0.5\n");
    }
    elseif ($lattice === 'znblende') {
      fwrite($scfin, "  " . $atom[$atom2] . " 0.25 0.25 0.25\n");
    }
    fwrite($scfin, "K_POINTS automatic\n");
    fwrite($scfin, "8 8 8 0 0 0");
    fclose($scfin);

    echo "<pre>";
    system("cat " . $scfname);
    echo "</pre>";
    echo "<hr>";
    echo "<pre>";
    system($pwcommand);
    echo "</pre>";
    echo "<hr>";
    echo "<pre>";
    system($fermicommand);
    system($jscommand);
    echo "</pre>";
    echo "<hr>";
    echo "<h2>計算完了</h2>";
    echo '<p><h3><a href="https://fermisurfer.osdn.jp/js/index.php?frmsf=https://macloud.issp.u-tokyo.ac.jp/simulator/' .  $submitid . '.js" target="_blank">フェルミ面の表示</a></h3></p>';
?>
    <h3>
      私たちはこのように、
      <ul>
        <li>コンピューターの中に物質を再現し</li>
        <li>シュレーディンガー方程式を解いて</li>
        <li>その中の電子や原子の動きをシミュレーション</li>
      </ul>
      しています。
    </h3>
  </body>
</html>
