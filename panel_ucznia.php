<?php
session_start();
if (!isset($_SESSION['uczen_id'])) {
    header("Location: dziennik.php");
    exit;
}

$uczen_id = $_SESSION['uczen_id'];
$id_klasy = $_SESSION['id_klasy'];

$host = "localhost";
$user = "root";
$pass = "";
$db = "dziennik";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$sql_wychowawca = "SELECT imie, nazwisko FROM nauczyciele WHERE id_klasy_wychowawca = ?";
$stmt = $conn->prepare($sql_wychowawca);
$stmt->bind_param("i", $id_klasy);
$stmt->execute();
$wynik = $stmt->get_result();
$wychowawca = $wynik->fetch_assoc();
$tekst_wychowawca = $wychowawca ? $wychowawca['imie'] . " " . $wychowawca['nazwisko'] : "Brak";


$sql = "
    SELECT p.nazwa AS przedmiot, o.ocena, o.opis, n.imie, n.nazwisko
    FROM oceny o
    JOIN przedmioty p ON o.id_przedmiotu = p.id
    JOIN nauczyciele n ON o.id_nauczyciela = n.id
    WHERE o.id_ucznia = ?
    ORDER BY p.nazwa, o.data_dodania
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uczen_id);
$stmt->execute();
$result = $stmt->get_result();


$oceny = [];
while ($row = $result->fetch_assoc()) {
    $przedmiot = $row['przedmiot'] . " ( " . $row['imie'] . " " . $row['nazwisko'] . " )";
    $oceny[$przedmiot][] = [
        'ocena' => $row['ocena'],
        'opis' => $row['opis']
    ];
}
$conn->close();
?>

<?php
if (!isset($_SESSION['uczen_id'])) {
    header("Location: dziennik.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Panel Ucznia - Prywatna Szkoła</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --text: #333;
            --text-light: #7f8c8d;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: #f5f7fa;
        }
        
        .panel-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .panel-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .panel-header h2 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .wychowawca-badge {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, var(--secondary), #2980b9);
            color: white;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            margin: 1rem 0;
        }
        
        .grades-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2.5rem;
        }
        
        .grades-header {
            background: var(--primary);
            color: white;
            padding: 1.2rem;
            font-size: 1.2rem;
        }
        
        .grades-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .grades-table th {
            background: var(--light);
            color: var(--dark);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .grades-table td {
            padding: 1.2rem;
            border-bottom: 1px solid #eee;
        }
        
        .grades-table tr:last-child td {
            border-bottom: none;
        }
        
        .grades-table tr:hover {
            background: rgba(52, 152, 219, 0.05);
        }
        
        .subject-col {
            font-weight: 600;
            color: var(--primary);
            width: 40%;
        }
        
        .grades-col {
            width: 60%;
        }
        
        .grade-bubble {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            margin: 0.2rem;
            background: var(--light);
            border-radius: 4px;
            position: relative;
            cursor: help;
            transition: all 0.2s ease;
        }
        
        .grade-bubble:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-2px);
        }
        
        .no-grades {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-style: italic;
        }
        
        .logout-btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .footer-credits {
            text-align: center;
            margin-top: 3rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <div class="panel-header">
            <h2><i class="fas fa-user-graduate"></i> Twój Panel Ucznia</h2>
            <div class="wychowawca-badge">
                <i class="fas fa-chalkboard-teacher"></i> Wychowawca: <?= htmlspecialchars($tekst_wychowawca) ?>
            </div>
        </div>
        
        <div class="grades-card">
            <div class="grades-header">
                <i class="fas fa-award"></i> Twoje oceny
            </div>
            
            <table class="grades-table">
                <thead>
                    <tr>
                        <th class="subject-col">Przedmiot (Nauczyciel)</th>
                        <th class="grades-col">Oceny</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($oceny)): ?>
                        <?php foreach ($oceny as $przedmiot => $lista): ?>
                            <tr>
                                <td class="subject-col"><?= htmlspecialchars($przedmiot) ?></td>
                                <td class="grades-col">
                                    <?php foreach ($lista as $o): ?>
                                        <span class="grade-bubble" title="<?= htmlspecialchars($o['opis']) ?>">
                                            <?= htmlspecialchars($o['ocena']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="no-grades">
                                <i class="fas fa-info-circle"></i> Brak ocen do wyświetlenia
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="text-align: center;">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Wyloguj się
            </a>
        </div>
        
        <div class="footer-credits">
            Prywatna Szkoła © <?= date('Y') ?> | Wersja 1.0
        </div>
    </div>
</body>
</html>

