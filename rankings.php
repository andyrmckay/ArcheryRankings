<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" media="all">
</head>
<body onload="sortTable('recurve-gents');sortTable('recurve-ladies');sortTable('compound-gents');sortTable('compound-ladies');sortTable('barebow-gents');sortTable('barebow-ladies');sortTable('longbow-gents');sortTable('longbow-ladies')">
<script src="script.js"></script>
<?php
require('/path/to/db.inc.php');

function rankingsTable($con, $queryFilter, $gender, $bow)
{
    $sql = "select * from archers where Age='".$_GET['age']."' and Gender='".$gender."' and Bow='".$bow."'";
    $output = $con->query($sql);
    echo '<h2>'.ucfirst($bow).' - '.ucfirst($gender).'</h2>';
    echo '<table border="0" cellspacing="2" cellpadding="2" id="'.$bow.'-'.$gender.'">
        <tr>
            <th class="rank"></th>
            <th> <font face="Arial">Name</font> </th>
            <th> <font face="Arial">Club</font> </th>
            <th class="score"> <font face="Arial">Score 1</font> </th>
            <th class="score"> <font face="Arial">Score 2</font> </th>
            <th class="score"> <font face="Arial">Score 3</font> </th>
            <th class="score"> <font face="Arial">Total</font> </th>
        </tr>';
    if ($output ->num_rows > 0) {
        while ($row = $output->fetch_assoc()) {
            $sql = "select * from Scores where Archer=".$row["ID"]." and Date >= DATE_SUB(NOW(),INTERVAL 1 YEAR) and (".$queryFilter.") order by AdjustedScore desc limit 3";
            $result = $con->query($sql);
            $totalScore = 0;
            $counter = 0;
            if ($result ->num_rows > 0) {
                echo '<tr class="count">
                  <td class="rank"></td>
                   <td>'.$row["Name"].'</td>
                   <td>'.$row["Club"].'</td>';
                while ($results = $result->fetch_assoc()) {
                    echo '<td class="score">'.$results["AdjustedScore"].'</td>';
                    $totalScore = $totalScore + $results["AdjustedScore"];
                    $counter++;
                }
                while ($counter < 3) {
                    echo '<td class="score"></td>';
                    $counter++;
                }
                echo '<td class="score">'.$totalScore.'</td></tr>';
            }
        }
    } else {
        echo "No results";
    }
    echo '</table>';
}

echo '<form action="" method="get">';
echo '<input type="radio" name="age" value="junior"';
if ($_GET['age'] == "junior") {
    echo 'checked';
}
echo '> Junior';
echo '<input type="radio" name="age" value="senior"';
if ($_GET['age'] == "senior") {
    echo 'checked';
}
echo '> Senior';
echo '<input type="radio" name="ranking" value="indoor"';
if ($_GET['ranking'] == "indoor") {
    echo 'checked';
}
echo '> Indoor';
echo '<input type="radio" name="ranking" value="outdoor"';
if ($_GET['ranking'] == "outdoor") {
    echo 'checked';
}
echo '> Outdoor';
echo '<input type="submit" name="submit" value="go">';
echo '</form>';

switch ($_GET['ranking']) {
  case "outdoor":
  $queryFilter = "Round='Albion' or Round='Bristol I' or Round='Bristol II' or Round='Bristol III' or Round='Bristol IV' or Round='Bristol V' or Round='Hereford' or Round='Junior Windsor' or Round='Metric I' or Round='Metric II' or Round='Metric III' or Round='Metric IV' or Round='Metric V' or Round='Short Junior Windsor' or Round='Short Windsor' or Round='WA1440-70' or Round='WA1440-90' or Round='Windsor' or Round='York' or Round='WA720-50' or Round='WA720-60' or Round='WA720-70'";
  break;
  case "indoor":
  $queryFilter = "Round='Portsmouth' or Round='WA18' or Round='WA25' or Round='Worcester'";
  break;
}

echo "<h1>".ucfirst($_GET['age'])." ".ucfirst($_GET['ranking'])." Rankings</h1>";

rankingsTable($con, $queryFilter, "gents", "recurve");
rankingsTable($con, $queryFilter, "ladies", "recurve");
rankingsTable($con, $queryFilter, "gents", "compound");
rankingsTable($con, $queryFilter, "ladies", "compound");
rankingsTable($con, $queryFilter, "gents", "barebow");
rankingsTable($con, $queryFilter, "ladies", "barebow");
rankingsTable($con, $queryFilter, "gents", "longbow");
rankingsTable($con, $queryFilter, "ladies", "longbow");

$con->close();
?>
</body>
</html>
