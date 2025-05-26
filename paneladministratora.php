<?php
// Set session timeout to 30 minutes (1800 seconds)
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.cookie_lifetime', 1800);

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: dziennik.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "szkola";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Sprawdzamy czy mamy akcję do wykonania
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_post':
                $tytul = $_POST['tytul'];
                $opis = $_POST['opis'];
                
                $stmt = $conn->prepare("INSERT INTO aktualnosci (data, tytul, opis) VALUES (NOW(), ?, ?)");
                $stmt->bind_param("ss", $tytul, $opis);
                $stmt->execute();
                break;
                
            case 'edit_post':
                $id = $_POST['id'];
                $tytul = $_POST['tytul'];
                $opis = $_POST['opis'];
                
                $stmt = $conn->prepare("UPDATE aktualnosci SET tytul = ?, opis = ? WHERE id = ?");
                $stmt->bind_param("ssi", $tytul, $opis, $id);
                $stmt->execute();
                break;
                
            case 'delete_post':
                $id = $_POST['id'];
                $stmt = $conn->prepare("DELETE FROM aktualnosci WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                break;
                
            case 'add_teacher':
                $imie = $_POST['imie'];
                $nazwisko = $_POST['nazwisko'];
                $login = $_POST['login'];
                $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);
                $id_przedmiotu = $_POST['id_przedmiotu'];
                $id_klasy = $_POST['id_klasy_wychowawca'] ?: NULL;
                
                $stmt = $conn->prepare("INSERT INTO nauczyciele (imie, nazwisko, login, haslo, id_przedmiotu, id_klasy_wychowawca) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssii", $imie, $nazwisko, $login, $haslo, $id_przedmiotu, $id_klasy);
                $stmt->execute();
                break;
                
                case 'edit_teacher':
                    $id = $_POST['id'];
                    $imie = $_POST['imie'];
                    $nazwisko = $_POST['nazwisko'];
                    $login = $_POST['login'];
                    $haslo = !empty($_POST['haslo']) ? password_hash($_POST['haslo'], PASSWORD_DEFAULT) : null;
                    $id_przedmiotu = $_POST['id_przedmiotu'];
                    $id_klasy = $_POST['id_klasy_wychowawca'] ?: NULL;
                    
                    if ($haslo) {
                        $stmt = $conn->prepare("UPDATE nauczyciele SET imie = ?, nazwisko = ?, login = ?, haslo = ?, id_przedmiotu = ?, id_klasy_wychowawca = ? WHERE id = ?");
                        $stmt->bind_param("ssssiii", $imie, $nazwisko, $login, $haslo, $id_przedmiotu, $id_klasy, $id);
                    } else {
                        $stmt = $conn->prepare("UPDATE nauczyciele SET imie = ?, nazwisko = ?, login = ?, id_przedmiotu = ?, id_klasy_wychowawca = ? WHERE id = ?");
                        $stmt->bind_param("sssiii", $imie, $nazwisko, $login, $id_przedmiotu, $id_klasy, $id);
                    }
                    $stmt->execute();
                    break;
                    
                case 'delete_teacher':
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM nauczyciele WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    break;
                    
                case 'add_student':
                    $imie = $_POST['imie'];
                    $nazwisko = $_POST['nazwisko'];
                    $id_klasy = $_POST['id_klasy'];
                    $login = $_POST['login'];
                    $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);
                    
                    $stmt = $conn->prepare("INSERT INTO uczniowie (imie, nazwisko, id_klasy, login, haslo) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssiss", $imie, $nazwisko, $id_klasy, $login, $haslo);
                    $stmt->execute();
                    break;
                    
                case 'edit_student':
                    $id = $_POST['id'];
                    $imie = $_POST['imie'];
                    $nazwisko = $_POST['nazwisko'];
                    $id_klasy = $_POST['id_klasy'];
                    $login = $_POST['login'];
                    $haslo = !empty($_POST['haslo']) ? password_hash($_POST['haslo'], PASSWORD_DEFAULT) : null;
                    
                    if ($haslo) {
                        $stmt = $conn->prepare("UPDATE uczniowie SET imie = ?, nazwisko = ?, id_klasy = ?, login = ?, haslo = ? WHERE id = ?");
                        $stmt->bind_param("ssissi", $imie, $nazwisko, $id_klasy, $login, $haslo, $id);
                    } else {
                        $stmt = $conn->prepare("UPDATE uczniowie SET imie = ?, nazwisko = ?, id_klasy = ?, login = ? WHERE id = ?");
                        $stmt->bind_param("ssisi", $imie, $nazwisko, $id_klasy, $login, $id);
                    }
                    $stmt->execute();
                    break;
                    
                case 'delete_student':
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM uczniowie WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    break;
                    
                case 'add_classroom':
                    $numer = $_POST['numer'];
                    $stmt = $conn->prepare("INSERT INTO sale (numer) VALUES (?)");
                    $stmt->bind_param("s", $numer);
                    $stmt->execute();
                    break;
                    
                case 'edit_classroom':
                    $id = $_POST['id'];
                    $numer = $_POST['numer'];
                    $stmt = $conn->prepare("UPDATE sale SET numer = ? WHERE id = ?");
                    $stmt->bind_param("si", $numer, $id);
                    $stmt->execute();
                    break;
                    
                case 'delete_classroom':
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM sale WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    break;
                    
                case 'delete_class':
                    $id_klasy = $_POST['id_klasy'];
                    // Usuwanie uczniów z klasy
                    $stmt = $conn->prepare("DELETE FROM uczniowie WHERE id_klasy = ?");
                    $stmt->bind_param("i", $id_klasy);
                    $stmt->execute();
                    
                    // Resetowanie wychowawcy dla tej klasy
                    $id_klasy_wychowawca = $_POST['id_klasy_wychowawca'];
                    $stmt = $conn->prepare("UPDATE nauczyciele SET id_klasy_wychowawca = NULL WHERE id_klasy_wychowawca = ?");
                    $stmt->bind_param("i", $id_klasy_wychowawca);
                    $stmt->execute();
                    break;
                    
                case 'delete_message':
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM wiadomosci WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    break;
                    
                case 'delete_registration':
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM rejestracja_zatwierdzenie WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    break;
                    case 'add_income':
                        $kwota = $_POST['kwota'];
                        $tytul = $_POST['tytul'];
                        $opis = $_POST['opis'];
                        $data_wydatku = $_POST['data_wydatku'];
                        
                        $stmt = $conn->prepare("INSERT INTO przychody (kwota, tytul, opis, data_dodania_wpisu, data_wydatku) VALUES (?, ?, ?, NOW(), ?)");
                        $stmt->bind_param("dsss", $kwota, $tytul, $opis, $data_wydatku);
                        $stmt->execute();
                        break;
                        
                    case 'add_expense':
                        $kwota = $_POST['kwota'];
                        $tytul = $_POST['tytul'];
                        $opis = $_POST['opis'];
                        $data_wydatku = $_POST['data_wydatku'];
                        
                        $stmt = $conn->prepare("INSERT INTO wydatki (kwota, tytul, opis, data_dodania_wpisu, data_wydatku) VALUES (?, ?, ?, NOW(), ?)");
                        $stmt->bind_param("dsss", $kwota, $tytul, $opis, $data_wydatku);
                        $stmt->execute();
                        break;
                        
                    case 'delete_income':
                        $id = $_POST['id'];
                        $stmt = $conn->prepare("DELETE FROM przychody WHERE id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        break;
                        
                    case 'delete_expense':
                        $id = $_POST['id'];
                        $stmt = $conn->prepare("DELETE FROM wydatki WHERE id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        break;
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Pobranie danych administratora
$sql_admin = "SELECT Imie, Nazwisko FROM administratorzy WHERE ID = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$wynik_admin = $stmt->get_result();
$admin = $wynik_admin->fetch_assoc();

// Pobranie danych z bazy
$aktualnosci = $conn->query("SELECT * FROM aktualnosci ORDER BY data DESC")->fetch_all(MYSQLI_ASSOC);
$nauczyciele = $conn->query("SELECT * FROM nauczyciele")->fetch_all(MYSQLI_ASSOC);
$uczniowie = $conn->query("SELECT * FROM uczniowie")->fetch_all(MYSQLI_ASSOC);
$sale = $conn->query("SELECT * FROM sale")->fetch_all(MYSQLI_ASSOC);
$klasy = $conn->query("SELECT * FROM klasy")->fetch_all(MYSQLI_ASSOC);
$przedmioty = $conn->query("SELECT * FROM przedmioty")->fetch_all(MYSQLI_ASSOC);
$wiadomosci = $conn->query("SELECT * FROM wiadomosci ORDER BY data_dodania DESC")->fetch_all(MYSQLI_ASSOC);
$rejestracje = $conn->query("SELECT * FROM rejestracja_zatwierdzenie ORDER BY data_rejestracji DESC")->fetch_all(MYSQLI_ASSOC);
$przychody = $conn->query("SELECT * FROM przychody ORDER BY data_wydatku DESC")->fetch_all(MYSQLI_ASSOC);
$wydatki = $conn->query("SELECT * FROM wydatki ORDER BY data_wydatku DESC")->fetch_all(MYSQLI_ASSOC);

$conn->close();

// Sprawdzamy parametry GET
$show_messages = isset($_GET['show']) && $_GET['show'] == 'messages';
$show_registrations = isset($_GET['show']) && $_GET['show'] == 'registrations';
$show_posts = isset($_GET['show']) && $_GET['show'] == 'posts';
$show_finances = isset($_GET['show']) && $_GET['show'] == 'finances';
$show_management = !$show_messages && !$show_registrations && !$show_posts && !$show_finances;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Akademia Wiedzy</title>
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 2rem;
            border: 1px solid #888;
            border-radius: var(--border-radius);
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--primary-color);
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: var(--accent-color);
        }

        .modal-footer {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        textarea {
            font-family: 'Open Sans', sans-serif;
            transition: var(--transition);
        }

        textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.2);
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
            
            .message-content {
                max-width: 200px;
            }
            
            .modal-content {
                width: 95%;
                margin: 20% auto;
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
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['Imie'] . '+' . $admin['Nazwisko']); ?>&background=007bff&color=fff" alt="Administrator">
                    <h3><?php echo htmlspecialchars($admin['Imie'] . ' ' . $admin['Nazwisko']); ?></h3>
                    <p>Administrator systemu</p>
                </div>
                
                <ul class="admin-menu">
                    <li><a href="?show=management" class="<?php echo $show_management ? 'active' : ''; ?>"><i class="fas fa-users-cog"></i> Zarządzanie</a></li>
                    <li><a href="?show=messages" class="<?php echo $show_messages ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Wiadomości</a></li>
                    <li><a href="?show=registrations" class="<?php echo $show_registrations ? 'active' : ''; ?>"><i class="fas fa-user-check"></i> Zatwierdzenie konta</a></li>
                    <li><a href="?show=posts" class="<?php echo $show_posts ? 'active' : ''; ?>"><i class="fas fa-newspaper"></i> Wpisy</a></li>
                    <li><a href="?show=finances" class="<?php echo $show_finances ? 'active' : ''; ?>"><i class="fas fa-money-bill-wave"></i> Finanse</a></li>
                    <li><a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Wyloguj się</a></li>
                </ul>
            </div>
            
            <!-- Główna zawartość -->
            <div class="admin-content">
                <?php if ($show_management): ?>
                    <!-- Sekcja zarządzania -->
                    <div class="welcome-header">
                        <h2><i class="fas fa-user-shield"></i> Panel Administratora</h2>
                        <p>Zarządzaj uczniami, nauczycielami i zasobami szkoły</p>
                    </div>
                    
                    <!-- ... (sekcje zarządzania nauczycielami, uczniami, salami pozostają bez zmian) ... -->
                    <div class="admin-section">
                        <h3><i class="fas fa-chalkboard-teacher"></i> Zarządzanie nauczycielami</h3>
                        
                        <form method="post" class="form-row">
                            <input type="hidden" name="action" value="add_teacher">
                            <div class="form-group">
                                <label>Imię</label>
                                <input type="text" name="imie" required>
                            </div>
                            <div class="form-group">
                                <label>Nazwisko</label>
                                <input type="text" name="nazwisko" required>
                            </div>
                            <div class="form-group">
                                <label>Login</label>
                                <input type="text" name="login" required>
                            </div>
                            <div class="form-group">
                                <label>Hasło</label>
                                <input type="password" name="haslo" required>
                            </div>
                            <div class="form-group">
                                <label>Przedmiot</label>
                                <select name="id_przedmiotu" required>
                                    <?php foreach ($przedmioty as $p): ?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nazwa']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Klasa (wychowawca)</label>
                                <select name="id_klasy_wychowawca">
                                    <option value="">- Brak -</option>
                                    <?php foreach ($klasy as $k): ?>
                                        <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazwa']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn">Dodaj nauczyciela</button>
                        </form>
                        
                        <h4>Lista nauczycieli</h4>
                        <?php if (!empty($nauczyciele)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imię i nazwisko</th>
                                        <th>Login</th>
                                        <th>Przedmiot</th>
                                        <th>Wychowawca</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($nauczyciele as $n): ?>
                                        <tr>
                                            <td><?php echo $n['id']; ?></td>
                                            <td><?php echo htmlspecialchars($n['imie'] . ' ' . $n['nazwisko']); ?></td>
                                            <td><?php echo htmlspecialchars($n['login']); ?></td>
                                            <td>
                                                <?php 
                                                $nazwa_przedmiotu = '';
                                                foreach ($przedmioty as $p) {
                                                    if ($p['id'] == $n['id_przedmiotu']) {
                                                        $nazwa_przedmiotu = $p['nazwa'];
                                                        break;
                                                    }
                                                }
                                                echo htmlspecialchars($nazwa_przedmiotu);
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if (isset($n['id_klasy_wychowawca']) && $n['id_klasy_wychowawca'] != '') {
                                                    $nazwa_klasy = '';
                                                    foreach ($klasy as $k) {
                                                        if ($k['id'] == $n['id_klasy_wychowawca']) {
                                                            $nazwa_klasy = $k['nazwa'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($nazwa_klasy);
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td class="action-buttons">
                                                <button onclick="openEditTeacherModal(<?php echo $n['id']; ?>, '<?php echo htmlspecialchars($n['imie'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($n['nazwisko'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($n['login'], ENT_QUOTES); ?>', <?php echo $n['id_przedmiotu']; ?>, <?php echo $n['id_klasy_wychowawca'] ?? 'null'; ?>)" class="btn btn-secondary btn-sm">Edytuj</button>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_teacher">
                                                    <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak nauczycieli w systemie</div>
                        <?php endif; ?>
                    </div>
                    <div id="editTeacherModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Edytuj nauczyciela</h3>
                                <span class="close" onclick="closeModal('editTeacherModal')">&times;</span>
                            </div>
                            <form method="post" id="editTeacherForm">
                                <input type="hidden" name="action" value="edit_teacher">
                                <input type="hidden" name="id" id="editTeacherId">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Imię</label>
                                        <input type="text" name="imie" id="editTeacherImie" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nazwisko</label>
                                        <input type="text" name="nazwisko" id="editTeacherNazwisko" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Login</label>
                                        <input type="text" name="login" id="editTeacherLogin" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nowe hasło (opcjonalne)</label>
                                        <input type="password" name="haslo" id="editTeacherHaslo">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Przedmiot</label>
                                        <select name="id_przedmiotu" id="editTeacherPrzedmiot" required>
                                            <?php foreach ($przedmioty as $p): ?>
                                                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nazwa']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Klasa (wychowawca)</label>
                                        <select name="id_klasy_wychowawca" id="editTeacherKlasa">
                                            <option value="">- Brak -</option>
                                            <?php foreach ($klasy as $k): ?>
                                                <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazwa']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('editTeacherModal')">Anuluj</button>
                                    <button type="submit" class="btn">Zapisz zmiany</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="admin-section">
                        <h3><i class="fas fa-user-graduate"></i> Zarządzanie uczniami</h3>
                        
                        <form method="post" class="form-row">
                            <input type="hidden" name="action" value="add_student">
                            <div class="form-group">
                                <label>Imię</label>
                                <input type="text" name="imie" required>
                            </div>
                            <div class="form-group">
                                <label>Nazwisko</label>
                                <input type="text" name="nazwisko" required>
                            </div>
                            <div class="form-group">
                                <label>Klasa</label>
                                <select name="id_klasy" required>
                                    <?php foreach ($klasy as $k): ?>
                                        <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazwa']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Login</label>
                                <input type="text" name="login" required>
                            </div>
                            <div class="form-group">
                                <label>Hasło</label>
                                <input type="password" name="haslo" required>
                            </div>
                            <button type="submit" class="btn">Dodaj ucznia</button>
                        </form>
                        
                        <h4>Lista uczniów</h4>
                        <?php if (!empty($uczniowie)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imię i nazwisko</th>
                                        <th>Klasa</th>
                                        <th>Login</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($uczniowie as $u): 
                                        $nazwa_klasy = '';
                                        foreach ($klasy as $k) {
                                            if ($k['id'] == $u['id_klasy']) {
                                                $nazwa_klasy = $k['nazwa'];
                                                break;
                                            }
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $u['id']; ?></td>
                                            <td><?php echo htmlspecialchars($u['imie'] . ' ' . $u['nazwisko']); ?></td>
                                            <td><?php echo htmlspecialchars($nazwa_klasy); ?></td>
                                            <td><?php echo htmlspecialchars($u['login']); ?></td>
                                            <td class="action-buttons">
                                                <button onclick="openEditStudentModal(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['imie'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($u['nazwisko'], ENT_QUOTES); ?>', <?php echo $u['id_klasy']; ?>, '<?php echo htmlspecialchars($u['login'], ENT_QUOTES); ?>')" class="btn btn-secondary btn-sm">Edytuj</button>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_student">
                                                    <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak uczniów w systemie</div>
                        <?php endif; ?>
                        
                        <!-- Modal edycji ucznia -->
                        <div id="editStudentModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Edytuj ucznia</h3>
                                    <span class="close" onclick="closeModal('editStudentModal')">&times;</span>
                                </div>
                                <form method="post" id="editStudentForm">
                                    <input type="hidden" name="action" value="edit_student">
                                    <input type="hidden" name="id" id="editStudentId">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Imię</label>
                                            <input type="text" name="imie" id="editStudentImie" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nazwisko</label>
                                            <input type="text" name="nazwisko" id="editStudentNazwisko" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Klasa</label>
                                            <select name="id_klasy" id="editStudentKlasa" required>
                                                <?php foreach ($klasy as $k): ?>
                                                    <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazwa']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Login</label>
                                            <input type="text" name="login" id="editStudentLogin" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Nowe hasło (opcjonalne)</label>
                                            <input type="password" name="haslo" id="editStudentHaslo">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="closeModal('editStudentModal')">Anuluj</button>
                                        <button type="submit" class="btn">Zapisz zmiany</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <h4>Usuwanie całej klasy</h4>
                        <form method="post" class="form-row">
                            <input type="hidden" name="action" value="delete_class">
                            <div class="form-group">
                                <label>Wybierz klasę do usunięcia</label>
                                <select name="id_klasy" required>
                                    <?php foreach ($klasy as $k): ?>
                                        <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazwa']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">Usuń klasę i wszystkich uczniów</button>
                        </form>
                    </div>
                    <div class="admin-section">
                        <h3><i class="fas fa-door-open"></i> Zarządzanie salami</h3>
                        
                        <form method="post" class="form-row">
                            <input type="hidden" name="action" value="add_classroom">
                            <div class="form-group">
                                <label>Numer sali</label>
                                <input type="text" name="numer" required>
                            </div>
                            <button type="submit" class="btn">Dodaj salę</button>
                        </form>
                        
                        <h4>Lista sal</h4>
                        <?php if (!empty($sale)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Numer sali</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sale as $s): ?>
                                        <tr>
                                            <td><?php echo $s['id']; ?></td>
                                            <td><?php echo htmlspecialchars($s['numer']); ?></td>
                                            <td class="action-buttons">
                                                <button onclick="openEditClassroomModal(<?php echo $s['id']; ?>, '<?php echo htmlspecialchars($s['numer'], ENT_QUOTES); ?>')" class="btn btn-secondary btn-sm">Edytuj</button>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_classroom">
                                                    <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak sal w systemie</div>
                        <?php endif; ?>
                    </div>
                    <div id="editClassroomModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Edytuj salę</h3>
                                <span class="close" onclick="closeModal('editClassroomModal')">&times;</span>
                            </div>
                            <form method="post" id="editClassroomForm">
                                <input type="hidden" name="action" value="edit_classroom">
                                <input type="hidden" name="id" id="editClassroomId">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Numer sali</label>
                                        <input type="text" name="numer" id="editClassroomNumer" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('editClassroomModal')">Anuluj</button>
                                    <button type="submit" class="btn">Zapisz zmiany</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    
                <?php elseif ($show_posts): ?>
                    <!-- Sekcja wpisów -->
                    <div class="welcome-header">
                        <h2><i class="fas fa-newspaper"></i> Zarządzanie wpisami</h2>
                        <p>Dodawaj i edytuj aktualności na stronie</p>
                    </div>
                    
                    <div class="admin-section">
                        <h3><i class="fas fa-plus-circle"></i> Dodaj nowy wpis</h3>
                        
                        <form method="post" class="form-row">
                            <input type="hidden" name="action" value="add_post">
                            <div class="form-group">
                                <label>Tytuł</label>
                                <input type="text" name="tytul" required>
                            </div>
                            <div class="form-group" style="flex: 1 0 100%;">
                                <label>Opis</label>
                                <textarea name="opis" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--border-radius);" required></textarea>
                            </div>
                            <button type="submit" class="btn">Dodaj wpis</button>
                        </form>
                        
                        <h3><i class="fas fa-list"></i> Lista wpisów</h3>
                        <?php if (!empty($aktualnosci)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Data</th>
                                        <th>Tytuł</th>
                                        <th>Opis</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($aktualnosci as $a): ?>
                                        <tr>
                                            <td><?php echo $a['id']; ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($a['data'])); ?></td>
                                            <td><?php echo htmlspecialchars($a['tytul']); ?></td>
                                            <td class="message-content"><?php echo nl2br(htmlspecialchars(substr($a['opis'], 0, 100))) . (strlen($a['opis']) > 100 ? '...' : ''); ?></td>
                                            <td class="action-buttons">
                                                <button onclick="openEditPostModal(<?php echo $a['id']; ?>, '<?php echo htmlspecialchars($a['tytul'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($a['opis'], ENT_QUOTES); ?>')" class="btn btn-secondary btn-sm">Edytuj</button>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_post">
                                                    <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak wpisów</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Modal edycji wpisu -->
                    <div id="editPostModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Edytuj wpis</h3>
                                <span class="close" onclick="closeModal('editPostModal')">&times;</span>
                            </div>
                            <form method="post" id="editPostForm">
                                <input type="hidden" name="action" value="edit_post">
                                <input type="hidden" name="id" id="editPostId">
                                <div class="form-row">
                                    <div class="form-group" style="flex: 1 0 100%;">
                                        <label>Tytuł</label>
                                        <input type="text" name="tytul" id="editPostTytul" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group" style="flex: 1 0 100%;">
                                        <label>Opis</label>
                                        <textarea name="opis" id="editPostOpis" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--border-radius);" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('editPostModal')">Anuluj</button>
                                    <button type="submit" class="btn">Zapisz zmiany</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                <?php elseif ($show_messages): ?>
                    <!-- Sekcja wiadomości -->
                    <div class="welcome-header">
                        <h2><i class="fas fa-envelope"></i> Wiadomości od użytkowników</h2>
                        <p>Przeglądaj i zarządzaj wiadomościami wysłanymi przez użytkowników</p>
                    </div>
                    
                    <div class="admin-section">
                        <?php if (!empty($wiadomosci)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Imię i nazwisko</th>
                                        <th>Email</th>
                                        <th>Temat</th>
                                        <th>Wiadomość</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wiadomosci as $w): ?>
                                        <tr>
                                            <td><?php echo date('Y-m-d H:i', strtotime($w['data_dodania'])); ?></td>
                                            <td><?php echo htmlspecialchars($w['imie_i_nazwisko']); ?></td>
                                            <td><?php echo htmlspecialchars($w['email']); ?></td>
                                            <td><?php echo htmlspecialchars($w['temat']); ?></td>
                                            <td class="message-content"><?php echo nl2br(htmlspecialchars($w['wiadomosc'])); ?></td>
                                            <td class="action-buttons">
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_message">
                                                    <input type="hidden" name="id" value="<?php echo $w['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak wiadomości</div>
                        <?php endif; ?>
                    </div>
                    
                <?php elseif ($show_registrations): ?>
                    <!-- Sekcja zatwierdzania kont -->
                    <div class="welcome-header">
                        <h2><i class="fas fa-user-check"></i> Zatwierdzenie konta</h2>
                        <p>Przeglądaj i zarządzaj wnioskami o rejestrację kont</p>
                    </div>
                    
                    <div class="admin-section">
                        <?php if (!empty($rejestracje)): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imię</th>
                                        <th>Nazwisko</th>
                                        <th>Numer telefonu</th>
                                        <th>Stanowisko</th>
                                        <th>Data rejestracji</th>
                                        <th>Uwagi (Email i klasa)</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rejestracje as $r): ?>
                                        <tr>
                                            <td><?php echo $r['id']; ?></td>
                                            <td><?php echo htmlspecialchars($r['imie']); ?></td>
                                            <td><?php echo htmlspecialchars($r['nazwisko']); ?></td>
                                            <td><?php echo htmlspecialchars($r['numer_telefonu']); ?></td>
                                            <td><?php echo htmlspecialchars($r['stanowisko']); ?></td>
                                            <td><?php echo htmlspecialchars($r['data_rejestracji']); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($r['uwagi'])); ?></td>
                                            <td class="action-buttons">
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_registration">
                                                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Zatwierdzony</button>
                                                </form>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_registration">
                                                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Nie zatwierdzony</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">Brak wniosków o rejestrację</div>
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
    <?php elseif ($show_finances): ?>
    <!-- Sekcja finansów -->
    <div class="welcome-header">
        <h2><i class="fas fa-money-bill-wave"></i> Zarządzanie finansami</h2>
        <p>Dodawaj i przeglądaj przychody oraz wydatki szkoły</p>
    </div>
    
    <div class="admin-section">
        <h3><i class="fas fa-plus-circle"></i> Dodaj nowy przychód</h3>
        
        <form method="post" class="form-row">
            <input type="hidden" name="action" value="add_income">
            <div class="form-group">
                <label>Kwota</label>
                <input type="number" step="0.01" name="kwota" required>
            </div>
            <div class="form-group">
                <label>Tytuł</label>
                <input type="text" name="tytul" required>
            </div>
            <div class="form-group">
                <label>Data przychodu</label>
                <input type="date" name="data_wydatku" required>
            </div>
            <div class="form-group" style="flex: 1 0 100%;">
                <label>Opis</label>
                <textarea name="opis" rows="3" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--border-radius);" required></textarea>
            </div>
            <button type="submit" class="btn">Dodaj przychód</button>
        </form>
        
        <h3><i class="fas fa-list"></i> Lista przychodów</h3>
        <?php if (!empty($przychody)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kwota</th>
                        <th>Tytuł</th>
                        <th>Opis</th>
                        <th>Data przychodu</th>
                        <th>Data dodania</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($przychody as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo number_format($p['kwota'], 2); ?> zł</td>
                            <td><?php echo htmlspecialchars($p['tytul']); ?></td>
                            <td class="message-content"><?php echo nl2br(htmlspecialchars($p['opis'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($p['data_wydatku'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($p['data_dodania_wpisu'])); ?></td>
                            <td class="action-buttons">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_income">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Brak przychodów</div>
        <?php endif; ?>
    </div>
    
    <div class="admin-section">
        <h3><i class="fas fa-minus-circle"></i> Dodaj nowy wydatek</h3>
        
        <form method="post" class="form-row">
            <input type="hidden" name="action" value="add_expense">
            <div class="form-group">
                <label>Kwota</label>
                <input type="number" step="0.01" name="kwota" required>
            </div>
            <div class="form-group">
                <label>Tytuł</label>
                <input type="text" name="tytul" required>
            </div>
            <div class="form-group">
                <label>Data wydatku</label>
                <input type="date" name="data_wydatku" required>
            </div>
            <div class="form-group" style="flex: 1 0 100%;">
                <label>Opis</label>
                <textarea name="opis" rows="3" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--border-radius);" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Dodaj wydatek</button>
        </form>
        
        <h3><i class="fas fa-list"></i> Lista wydatków</h3>
        <?php if (!empty($wydatki)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kwota</th>
                        <th>Tytuł</th>
                        <th>Opis</th>
                        <th>Data wydatku</th>
                        <th>Data dodania</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wydatki as $w): ?>
                        <tr>
                            <td><?php echo $w['id']; ?></td>
                            <td><?php echo number_format($w['kwota'], 2); ?> zł</td>
                            <td><?php echo htmlspecialchars($w['tytul']); ?></td>
                            <td class="message-content"><?php echo nl2br(htmlspecialchars($w['opis'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($w['data_wydatku'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($w['data_dodania_wpisu'])); ?></td>
                            <td class="action-buttons">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_expense">
                                    <input type="hidden" name="id" value="<?php echo $w['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Brak wydatków</div>
        <?php endif; ?>
    </div>
    
    <div class="admin-section">
        <h3><i class="fas fa-calculator"></i> Podsumowanie finansowe</h3>
        <?php
        $suma_przychodow = array_sum(array_column($przychody, 'kwota'));
        $suma_wydatkow = array_sum(array_column($wydatki, 'kwota'));
        $bilans = $suma_przychodow - $suma_wydatkow;
        ?>
        <div style="display: flex; justify-content: space-around; text-align: center; margin: 2rem 0;">
            <div style="background: #e8f5e9; padding: 1.5rem; border-radius: var(--border-radius); flex: 1; margin: 0 0.5rem;">
                <h4>Suma przychodów</h4>
                <p style="font-size: 1.5rem; font-weight: bold; color: #2e7d32;"><?php echo number_format($suma_przychodow, 2); ?> zł</p>
            </div>
            <div style="background: #ffebee; padding: 1.5rem; border-radius: var(--border-radius); flex: 1; margin: 0 0.5rem;">
                <h4>Suma wydatków</h4>
                <p style="font-size: 1.5rem; font-weight: bold; color: #c62828;"><?php echo number_format($suma_wydatkow, 2); ?> zł</p>
            </div>
            <div style="background: <?php echo $bilans >= 0 ? '#e8f5e9' : '#ffebee'; ?>; padding: 1.5rem; border-radius: var(--border-radius); flex: 1; margin: 0 0.5rem;">
                <h4>Bilans</h4>
                <p style="font-size: 1.5rem; font-weight: bold; color: <?php echo $bilans >= 0 ? '#2e7d32' : '#c62828'; ?>;"><?php echo number_format($bilans, 2); ?> zł</p>
            </div>
        </div>
    </div>
<?php endif; ?>

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

    <script>
        // Funkcje do obsługi modalów
        function openEditTeacherModal(id, imie, nazwisko, login, id_przedmiotu, id_klasy_wychowawca) {
            document.getElementById('editTeacherId').value = id;
            document.getElementById('editTeacherImie').value = imie;
            document.getElementById('editTeacherNazwisko').value = nazwisko;
            document.getElementById('editTeacherLogin').value = login;
            document.getElementById('editTeacherPrzedmiot').value = id_przedmiotu;
            document.getElementById('editTeacherKlasa').value = id_klasy_wychowawca || '';
            document.getElementById('editTeacherModal').style.display = 'block';
        }

        function openEditStudentModal(id, imie, nazwisko, id_klasy, login) {
            document.getElementById('editStudentId').value = id;
            document.getElementById('editStudentImie').value = imie;
            document.getElementById('editStudentNazwisko').value = nazwisko;
            document.getElementById('editStudentKlasa').value = id_klasy;
            document.getElementById('editStudentLogin').value = login;
            document.getElementById('editStudentModal').style.display = 'block';
        }

        function openEditClassroomModal(id, numer) {
            document.getElementById('editClassroomId').value = id;
            document.getElementById('editClassroomNumer').value = numer;
            document.getElementById('editClassroomModal').style.display = 'block';
        }

        function openEditPostModal(id, tytul, opis) {
            document.getElementById('editPostId').value = id;
            document.getElementById('editPostTytul').value = tytul;
            document.getElementById('editPostOpis').value = opis;
            document.getElementById('editPostModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Zamknij modal po kliknięciu poza nim
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>