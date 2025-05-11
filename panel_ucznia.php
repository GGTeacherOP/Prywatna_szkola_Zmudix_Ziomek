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

// Pobierz wychowawcę
$sql_wychowawca = "SELECT imie, nazwisko FROM nauczyciele WHERE id_klasy_wychowawca = ?";
$stmt = $conn->prepare($sql_wychowawca);
$stmt->bind_param("i", $id_klasy);
$stmt->execute();
$wynik = $stmt->get_result();
$wychowawca = $wynik->fetch_assoc();
$tekst_wychowawca = $wychowawca ? $wychowawca['imie'] . " " . $wychowawca['nazwisko'] : "Brak";

// Pobierz oceny ucznia
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

// Grupuj dane
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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Panel ucznia</title>
    <link rel="stylesheet" href="plan.css">
    <style>
        td span {
            cursor: help;
            border-bottom: 1px dotted #333;
        }
    </style>
</head>
<body>
<header>
    <h1>Dziennik Elektroniczny</h1>
    <h2>Panel ucznia</h2>
</header>
<main>
    <section id="class-list">
        <h3>Wychowawca: <?= htmlspecialchars($tekst_wychowawca) ?></h3>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Przedmiot</th>
                <th>Oceny</th>
            </tr>
            <?php foreach ($oceny as $przedmiot => $lista): ?>
                <tr>
                    <td><?= htmlspecialchars($przedmiot) ?></td>
                    <td>
                        <?php foreach ($lista as $o): ?>
                            <span title="<?= htmlspecialchars($o['opis']) ?>"><?= htmlspecialchars($o['ocena']) ?></span>,
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($oceny)): ?>
                <tr><td colspan="2">Brak ocen do wyświetlenia.</td></tr>
            <?php endif; ?>
        </table>
        <br><a href="logout.php">Wyloguj się</a>
    
    </section>
</main>
<footer>
    <p>&copy; szkoła</p>
</footer>
</body>
</html>
