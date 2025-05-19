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
$db = "szkola";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie danych ucznia
$sql_uczen = "SELECT imie, nazwisko FROM uczniowie WHERE id = ?";
$stmt = $conn->prepare($sql_uczen);
$stmt->bind_param("i", $uczen_id);
$stmt->execute();
$wynik_uczen = $stmt->get_result();
$uczen = $wynik_uczen->fetch_assoc();

// Pobranie wychowawcy
$sql_wychowawca = "SELECT imie, nazwisko FROM nauczyciele WHERE id_klasy_wychowawca = ?";
$stmt = $conn->prepare($sql_wychowawca);
$stmt->bind_param("i", $id_klasy);
$stmt->execute();
$wynik = $stmt->get_result();
$wychowawca = $wynik->fetch_assoc();
$tekst_wychowawca = $wychowawca ? $wychowawca['imie'] . " " . $wychowawca['nazwisko'] : "Brak";

// Pobranie ocen
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

// Pobranie średniej ocen
$sql_srednia = "SELECT AVG(ocena) as srednia FROM oceny WHERE id_ucznia = ?";
$stmt = $conn->prepare($sql_srednia);
$stmt->bind_param("i", $uczen_id);
$stmt->execute();
$wynik_srednia = $stmt->get_result();
$srednia = $wynik_srednia->fetch_assoc()['srednia'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Ucznia - Akademia Wiedzy</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0056b3;
            --secondary-color: #17a2b8;
            --accent-color: #ff6b6b;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --text-color: #495057;
            --text-light: #6c757d;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
            --border-radius: 8px;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f5f5;
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(135deg, var(--primary-color), #003d7a);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .logo-container h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }

        .logo-container p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .header-contact p {
            margin: 0.2rem 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .header-contact i {
            margin-right: 0.5rem;
        }

        /* Ulepszona nawigacja */
        nav {
            background-color: var(--primary-color);
            padding: 0.5rem 0;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            justify-content: center;
        }

        nav li {
            margin: 0 0.5rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            display: block;
            transition: var(--transition);
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 50px;
            position: relative;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        nav a.active-nav {
            background-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(0, 86, 179, 0.3);
        }

        nav a.active-nav:after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }

        nav i {
            margin-right: 0.5rem;
        }

        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .student-dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
        }

        .student-sidebar {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            height: fit-content;
        }

        .student-profile {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .student-profile img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--light-color);
            margin-bottom: 1rem;
        }

        .student-profile h3 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .student-profile p {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .student-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .student-menu li {
            margin-bottom: 0.5rem;
        }

        .student-menu a {
            display: block;
            padding: 0.8rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .student-menu a:hover, .student-menu a.active {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .student-menu a i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .student-content {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
        }

        .welcome-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-header h2 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .welcome-header p {
            color: var(--text-light);
        }

        .class-teacher {
            background: linear-gradient(135deg, var(--secondary-color), #138496);
            color: white;
            padding: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .stat-card p {
            color: var(--text-light);
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .grades-table th {
            background: var(--light-color);
            color: var(--dark-color);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .grades-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .grades-table tr:last-child td {
            border-bottom: none;
        }

        .grades-table tr:hover {
            background: rgba(23, 162, 184, 0.03);
        }

        .grade-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            margin: 0.2rem;
            background: var(--light-color);
            border-radius: 20px;
            font-weight: 600;
            color: var(--dark-color);
            transition: var(--transition);
            position: relative;
            cursor: help;
        }

        .grade-badge:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .no-grades {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-style: italic;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn:hover {
            background: #0069d9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            background: var(--accent-color);
        }

        .btn-danger:hover {
            background: #dc3545;
        }

        footer {
            background: var(--dark-color);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .footer-column h4 {
            color: white;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .footer-column p, .footer-column a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
        }

        .footer-column a:hover {
            color: white;
            text-decoration: none;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 768px) {
            .student-dashboard {
                grid-template-columns: 1fr;
            }
            
            .student-sidebar {
                order: 2;
            }
            
            .header-container {
                flex-direction: column;
                text-align: center;
            }
            
            .header-contact {
                margin-top: 1rem;
                text-align: center;
            }
            
            nav ul {
                flex-direction: column;
                align-items: center;
            }
            
            nav li {
                margin: 0.3rem 0;
                width: 100%;
            }
            
            nav a {
                text-align: center;
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <h1>Akademia Wiedzy</h1>
                <p>Prywatna Szkoła z Tradycją</p>
            </div>
            <div class="header-contact">
                <p><i class="fas fa-phone"></i> +48 123 456 789</p>
                <p><i class="fas fa-envelope"></i> kontakt@akademiawiedzy.edu.pl</p>
            </div>
        </div>
        <nav>
            <ul>
<<<<<<< HEAD
                <li><a href="szkola.php"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="plan.php"><i class="fas fa-calendar-alt"></i> Plan lekcji</a></li>
=======
                <li><a href="szkola.html"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="plan.html"><i class="fas fa-calendar-alt"></i> Plan lekcji</a></li>
>>>>>>> 2f61e3d4a5a92dcc76d07ce1159b948dd02a66d1
                <li><a href="dziennik.php" class="active-nav"><i class="fas fa-book"></i> Dziennik</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <div class="student-dashboard">
            <!-- Panel boczny -->
            <div class="student-sidebar">
                <div class="student-profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($uczen['imie'] . '+' . $uczen['nazwisko']); ?>&background=random" alt="Uczeń">
                    <h3><?php echo htmlspecialchars($uczen['imie'] . ' ' . $uczen['nazwisko']); ?></h3>
                    <p>Uczeń Akademii Wiedzy</p>
                </div>
                
                <ul class="student-menu">
                    <li><a href="#" class="active"><i class="fas fa-award"></i> Oceny</a></li>
                    <li><a href="#"><i class="fas fa-tasks"></i> Zadania</a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i> Frekwencja</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> Wiadomości</a></li>
                    <li><a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Wyloguj się</a></li>
                </ul>
            </div>
            
            <!-- Główna zawartość -->
            <div class="student-content">
                <div class="welcome-header">
                    <h2><i class="fas fa-user-graduate"></i> Witaj w swoim panelu ucznia</h2>
                    <p>Masz dostęp do swoich ocen, planu lekcji i innych informacji</p>
                </div>
                
                <div class="class-teacher">
                    <h4><i class="fas fa-chalkboard-teacher"></i> Twój wychowawca: <?php echo htmlspecialchars($tekst_wychowawca); ?></h4>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-award"></i>
                        <h3><?php echo number_format($srednia, 2); ?></h3>
                        <p>Średnia ocen</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-book"></i>
                        <h3><?php echo count($oceny); ?></h3>
                        <p>Oceny z przedmiotów</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>95%</h3>
                        <p>Frekwencja</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-bell"></i>
                        <h3>2</h3>
                        <p>Nowe wiadomości</p>
                    </div>
                </div>
                
                <h3><i class="fas fa-award"></i> Twoje oceny</h3>
                
                <?php if (!empty($oceny)): ?>
                    <table class="grades-table">
                        <thead>
                            <tr>
                                <th>Przedmiot (Nauczyciel)</th>
                                <th>Oceny</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($oceny as $przedmiot => $lista): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($przedmiot); ?></td>
                                    <td>
                                        <?php foreach ($lista as $o): ?>
                                            <span class="grade-badge" title="<?php echo htmlspecialchars($o['opis']); ?>">
                                                <?php echo htmlspecialchars($o['ocena']); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-grades">
                        <i class="fas fa-info-circle"></i> Nie masz jeszcze żadnych ocen
                    </div>
                <?php endif; ?>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Wyloguj się
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h4>Akademia Wiedzy</h4>
                <p>Kompleksowa edukacja od przedszkola po liceum</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="footer-column">
                <h4>Dziennik</h4>
                <ul>
                    <li><a href="dziennik.php">Logowanie</a></li>
                    <li><a href="rejestracja.php">Rejestracja</a></li>
                    <li><a href="#">Pomoc</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Kontakt</h4>
                <p><i class="fas fa-map-marker-alt"></i> ul. Akademicka 15, 00-001 Warszawa</p>
                <p><i class="fas fa-phone"></i> +48 123 456 789</p>
                <p><i class="fas fa-envelope"></i> kontakt@akademiawiedzy.edu.pl</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Akademia Wiedzy. Wszelkie prawa zastrzeżone.</p>
        </div>
    </footer>
</body>
</html>