<?php
session_start();

if (!isset($_SESSION['wlasciciel_id'])) {
    header("Location: dziennik.php");
    exit();
}

// Połączenie z bazą danych
$host = "localhost";
$user = "root";
$pass = "";
$db = "szkola";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie danych właściciela
$sql_wlasciciel = "SELECT * FROM wlasciciel WHERE id = ?";
$stmt = $conn->prepare($sql_wlasciciel);
$stmt->bind_param("i", $_SESSION['wlasciciel_id']);
$stmt->execute();
$wynik_wlasciciel = $stmt->get_result();
$wlasciciel = $wynik_wlasciciel->fetch_assoc();

// Pobranie danych finansowych
$przychody = $conn->query("SELECT * FROM przychody ORDER BY data_wydatku DESC")->fetch_all(MYSQLI_ASSOC);
$wydatki = $conn->query("SELECT * FROM wydatki ORDER BY data_wydatku DESC")->fetch_all(MYSQLI_ASSOC);

// Obliczenia podsumowania
$suma_przychodow = array_sum(array_column($przychody, 'kwota'));
$suma_wydatkow = array_sum(array_column($wydatki, 'kwota'));
$bilans = $suma_przychodow - $suma_wydatkow;

// Sprawdzanie zakresu dat
$wynik_analizy = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['analizuj'])) {
    $data_od = $_POST['data_od'];
    $data_do = $_POST['data_do'];
    
    $sql_przychody = $conn->prepare("SELECT SUM(kwota) AS suma FROM przychody WHERE data_wydatku BETWEEN ? AND ?");
    $sql_przychody->bind_param("ss", $data_od, $data_do);
    $sql_przychody->execute();
    $przychody_okres = $sql_przychody->get_result()->fetch_assoc()['suma'] ?? 0;
    
    $sql_wydatki = $conn->prepare("SELECT SUM(kwota) AS suma FROM wydatki WHERE data_wydatku BETWEEN ? AND ?");
    $sql_wydatki->bind_param("ss", $data_od, $data_do);
    $sql_wydatki->execute();
    $wydatki_okres = $sql_wydatki->get_result()->fetch_assoc()['suma'] ?? 0;
    
    $bilans_okres = $przychody_okres - $wydatki_okres;
    
    $wynik_analizy = "<div class='wynik-analizy'>";
    $wynik_analizy .= "<h3>Analiza okresu od $data_od do $data_do</h3>";
    $wynik_analizy .= "<p>Przychody: " . number_format($przychody_okres, 2) . " zł</p>";
    $wynik_analizy .= "<p>Wydatki: " . number_format($wydatki_okres, 2) . " zł</p>";
    
    if ($bilans_okres >= 0) {
        $wynik_analizy .= "<p class='wynik-dodatni'>Bilans: +" . number_format($bilans_okres, 2) . " zł</p>";
    } else {
        $wynik_analizy .= "<p class='wynik-ujemny'>Bilans: " . number_format($bilans_okres, 2) . " zł</p>";
    }
    
    $wynik_analizy .= "</div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Właściciela - Akademia Wiedzy</title>
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

        .admin-dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
        }

        .admin-sidebar {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            height: fit-content;
        }

        .admin-profile {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .admin-profile img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--light-color);
            margin-bottom: 1rem;
        }

        .admin-profile h3 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .admin-profile p {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .admin-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-menu li {
            margin-bottom: 0.5rem;
        }

        .admin-menu a {
            display: block;
            padding: 0.8rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .admin-menu a:hover, .admin-menu a.active {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .admin-menu a i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .admin-content {
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

        .admin-section {
            margin-bottom: 3rem;
        }

        .admin-section h3 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--light-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .finanse-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .finanse-box {
            background: var(--light-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }

        .finanse-box h4 {
            color: var(--dark-color);
            margin-top: 0;
            margin-bottom: 1rem;
            text-align: center;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Open Sans', sans-serif;
            transition: var(--transition);
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.2);
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
            margin-top: 0.5rem;
        }

        .btn:hover {
            background: #0069d9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background: var(--secondary-color);
        }

        .btn-secondary:hover {
            background: #138496;
        }

        .btn-danger {
            background: var(--accent-color);
        }

        .btn-danger:hover {
            background: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th {
            background: var(--light-color);
            color: var(--dark-color);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: rgba(23, 162, 184, 0.03);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .no-data {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-style: italic;
        }

        .message-content {
            max-width: 400px;
            word-wrap: break-word;
        }

        .podsumowanie {
            display: flex;
            justify-content: space-around;
            text-align: center;
            margin: 2rem 0;
        }

        .podsumowanie-box {
            background: #e8f5e9;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            flex: 1;
            margin: 0 0.5rem;
        }

        .podsumowanie-box.wydatki {
            background: #ffebee;
        }

        .podsumowanie-box.bilans {
            background: #e3f2fd;
        }

        .podsumowanie-box h4 {
            margin-top: 0;
            color: var(--dark-color);
        }

        .podsumowanie-box p {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .podsumowanie-box.przychody p {
            color: #2e7d32;
        }

        .podsumowanie-box.wydatki p {
            color: #c62828;
        }

        .podsumowanie-box.bilans p {
            color: #1565c0;
        }

        .analiza-form {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
        }

        .wynik-analizy {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-top: 1rem;
        }

        .wynik-analizy h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .wynik-dodatni {
            color: #2e7d32;
            font-weight: bold;
        }

        .wynik-ujemny {
            color: #c62828;
            font-weight: bold;
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
            .admin-dashboard {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
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
            
            .finanse-grid {
                grid-template-columns: 1fr;
            }
            
            .podsumowanie {
                flex-direction: column;
            }
            
            .podsumowanie-box {
                margin: 0.5rem 0;
            }
            
            .message-content {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <h1>Akademia Wiedzy</h1>
                <p>Dziennik Elektroniczny</p>
            </div>
            <div class="header-contact">
                <p><i class="fas fa-phone"></i> +48 123 456 789</p>
                <p><i class="fas fa-envelope"></i> kontakt@akademiawiedzy.edu.pl</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="szkola.php"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="plan.php"><i class="fas fa-calendar-alt"></i> Plan lekcji</a></li>
                <li><a href="dziennik.php" class="active-nav"><i class="fas fa-book"></i> Dziennik</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <div class="admin-dashboard">
            <!-- Panel boczny -->
            <div class="admin-sidebar">
                <div class="admin-profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($wlasciciel['imie'] . '+' . $wlasciciel['nazwisko']); ?>&background=007bff&color=fff" alt="Właściciel">
                    <h3><?php echo htmlspecialchars($wlasciciel['imie'] . ' ' . $wlasciciel['nazwisko']); ?></h3>
                    <p>Właściciel szkoły</p>
                </div>
                
                <ul class="admin-menu">
                    <li><a href="panel_wlasciciela.php" class="active"><i class="fas fa-chart-line"></i> Finanse szkoły</a></li>
                    <li><a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Wyloguj się</a></li>
                </ul>
            </div>
            
            <!-- Główna zawartość -->
            <div class="admin-content">
                <div class="welcome-header">
                    <h2><i class="fas fa-crown"></i> Panel Właściciela</h2>
                    <p>Zarządzanie finansami szkoły</p>
                </div>
                
                <div class="podsumowanie">
                    <div class="podsumowanie-box przychody">
                        <h4>Suma przychodów</h4>
                        <p><?php echo number_format($suma_przychodow, 2); ?> zł</p>
                    </div>
                    <div class="podsumowanie-box wydatki">
                        <h4>Suma wydatków</h4>
                        <p><?php echo number_format($suma_wydatkow, 2); ?> zł</p>
                    </div>
                    <div class="podsumowanie-box bilans">
                        <h4>Bilans</h4>
                        <p><?php echo ($bilans >= 0 ? '+' : '') . number_format($bilans, 2); ?> zł</p>
                    </div>
                </div>
                
                <div class="finanse-grid">
                    <div class="finanse-box">
                        <h4><i class="fas fa-money-bill-wave"></i> Przychody</h4>
                        <?php if (!empty($przychody)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kwota</th>
                                        <th>Tytuł</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($przychody as $p): ?>
                                        <tr>
                                            <td><?php echo number_format($p['kwota'], 2); ?> zł</td>
                                            <td><?php echo htmlspecialchars($p['tytul']); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($p['data_wydatku'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak przychodów</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="finanse-box">
                        <h4><i class="fas fa-receipt"></i> Wydatki</h4>
                        <?php if (!empty($wydatki)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kwota</th>
                                        <th>Tytuł</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wydatki as $w): ?>
                                        <tr>
                                            <td><?php echo number_format($w['kwota'], 2); ?> zł</td>
                                            <td><?php echo htmlspecialchars($w['tytul']); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($w['data_wydatku'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak wydatków</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="analiza-form">
                    <h3><i class="fas fa-search-dollar"></i> Analiza finansowa</h3>
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Data od</label>
                                <input type="date" name="data_od" required>
                            </div>
                            <div class="form-group">
                                <label>Data do</label>
                                <input type="date" name="data_do" required>
                            </div>
                        </div>
                        <button type="submit" name="analizuj" class="btn btn-secondary">
                            <i class="fas fa-calculator"></i> Analizuj okres
                        </button>
                    </form>
                    
                    <?php if (!empty($wynik_analizy)): ?>
                        <?php echo $wynik_analizy; ?>
                    <?php endif; ?>
                </div>
                
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