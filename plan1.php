<?php
$klasa_id = isset($_GET['klasa_id']) ? (int)$_GET['klasa_id'] : 0;

$conn = new mysqli("localhost", "root", "", "szkola");
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$sql = "
SELECT
  ROW_NUMBER() OVER (ORDER BY godzina_start) - 1 AS nr_lekcji,
  CONCAT(godzina_start, ' - ', godzina_koniec) AS godziny,
  MAX(CASE WHEN dzien_tygodnia = 'Poniedzialek' THEN p.nazwa END) AS Poniedzialek,
  MAX(CASE WHEN dzien_tygodnia = 'Wtorek' THEN p.nazwa END) AS Wtorek,
  MAX(CASE WHEN dzien_tygodnia = 'Sroda' THEN p.nazwa END) AS Sroda,
  MAX(CASE WHEN dzien_tygodnia = 'Czwartek' THEN p.nazwa END) AS Czwartek,
  MAX(CASE WHEN dzien_tygodnia = 'Piatek' THEN p.nazwa END) AS Piatek
FROM plan_lekcji pl
JOIN przedmioty p ON pl.przedmiot_id = p.id
WHERE klasa_id = $klasa_id
GROUP BY godzina_start, godzina_koniec
ORDER BY godzina_start
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Plan lekcji</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 6px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h1>Plan lekcji - Klasa <?php echo htmlspecialchars($klasa_id); ?></h1>

    <table>
        <tr>
            <th>Lekcja</th>
            <th>Godziny</th>
            <th>Poniedziałek</th>
            <th>Wtorek</th>
            <th>Środa</th>
            <th>Czwartek</th>
            <th>Piątek</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nr_lekcji']; ?></td>
                <td><?php echo $row['godziny']; ?></td>
                <td><?php echo $row['Poniedzialek']; ?></td>
                <td><?php echo $row['Wtorek']; ?></td>
                <td><?php echo $row['Sroda']; ?></td>
                <td><?php echo $row['Czwartek']; ?></td>
                <td><?php echo $row['Piatek']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="plan.html">Wróć do listy klas</a></p>
    <P><a href onclick="window.print(); return false;">Drukuj stronę</a></p>

</body>
</html>

<?php $conn->close(); ?>
