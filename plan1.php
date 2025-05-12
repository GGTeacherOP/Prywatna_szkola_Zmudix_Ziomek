<?php
$klasa_id = isset($_GET['klasa_id']) ? (int)$_GET['klasa_id'] : 0;

// Funkcja zwracająca pełną nazwę klasy na podstawie ID
function getNazwaKlasy($id) {
    $klasy = [
        1 => "1a Liceum",
        2 => "2a Liceum",
        3 => "3a Liceum",
        4 => "4a Liceum",
        5 => "1T Technikum",
        6 => "2T Technikum",
        7 => "3T Technikum",
        8 => "4T Technikum",
        9 => "5T Technikum",
        10 => "1a Szkoła Podstawowa",
        11 => "2a Szkoła Podstawowa",
        12 => "3a Szkoła Podstawowa",
        13 => "4a Szkoła Podstawowa",
        14 => "5a Szkoła Podstawowa",
        15 => "6a Szkoła Podstawowa",
        16 => "7a Szkoła Podstawowa",
        17 => "8a Szkoła Podstawowa",
        18 => "Muchomorki - Przedszkole"
    ];
    
    return $klasy[$id] ?? "Nieznana klasa (ID: $id)";
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan lekcji - <?php echo getNazwaKlasy($klasa_id); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9f7fe;
        }
        
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        
        .actions a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .actions a:hover {
            background-color: #2980b9;
        }
        
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Plan lekcji - <?php echo getNazwaKlasy($klasa_id); ?></h1>

    <table>
        <thead>
            <tr>
                <th>Lekcja</th>
                <th>Godziny</th>
                <th>Poniedziałek</th>
                <th>Wtorek</th>
                <th>Środa</th>
                <th>Czwartek</th>
                <th>Piątek</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>

    <div class="actions">
        <a href="plan.html">Wróć do listy klas</a>
        <a href="#" onclick="window.print(); return false;">Drukuj plan</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>