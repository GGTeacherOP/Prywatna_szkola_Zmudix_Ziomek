<?php
// Set session timeout to 30 minutes (1800 seconds)
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.cookie_lifetime', 1800);

session_start();

// Sprawdzenie czy użytkownik jest zalogowany jako nauczyciel
if (!isset($_SESSION['user_id'])) {
    header("Location: dziennik.php");
    exit();
}

$conn = new mysqli('localhost', 'root','', 'szkola');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Pobranie danych nauczyciela
$nauczyciel_id = $_SESSION['user_id'];
$query = "SELECT * FROM nauczyciele WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $nauczyciel_id);
$stmt->execute();
$result = $stmt->get_result();
$nauczyciel = $result->fetch_assoc();

if (!$nauczyciel) {
    header("Location: dziennik.php");
    exit();
}

// Pobranie klas, w których nauczyciel jest wychowawcą
$wychowawca_klasy = [];
if ($nauczyciel['id_klasy_wychowawca']) {
    $query = "SELECT * FROM klasy WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $nauczyciel['id_klasy_wychowawca']);
    $stmt->execute();
    $result = $stmt->get_result();
    $wychowawca_klasy = $result->fetch_assoc();
}

// Pobranie przedmiotów nauczyciela
$przedmioty = [];
$query = "SELECT p.id, p.nazwa FROM przedmioty p WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $nauczyciel['id_przedmiotu']);
$stmt->execute();
$result = $stmt->get_result();
$przedmioty = $result->fetch_all(MYSQLI_ASSOC);

// Obsługa dodawania ocen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dodaj_ocene'])) {
    // Walidacja tokena CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_msg = "Nieprawidłowy token zabezpieczający";
    } else {
        $uczen_id = (int)$_POST['uczen_id'];
        $przedmiot_id = (int)$_POST['przedmiot_id'];
        $ocena = (int)$_POST['ocena'];
        $opis = $conn->real_escape_string($_POST['opis']);
        $data_dodania = date('Y-m-d H:i:s');
        $nauczyciel_id = (int)$_SESSION['user_id'];
        
        // Sprawdź czy ocena już istnieje (zabezpieczenie przed duplikatami)
        $check_query = "SELECT id FROM oceny WHERE id_ucznia = ? AND id_przedmiotu = ? AND id_nauczyciela = ? AND ocena = ? AND opis = ? LIMIT 1";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("iiiis", $uczen_id, $przedmiot_id, $nauczyciel_id, $ocena, $opis);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_msg = "Ta ocena już istnieje w systemie";
        } else {
            // Dodanie nowej oceny
            $query = "INSERT INTO oceny (id_ucznia, id_przedmiotu, id_nauczyciela, ocena, opis, data_dodania) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            
            if ($stmt) {
                $stmt->bind_param("iiiiss", $uczen_id, $przedmiot_id, $nauczyciel_id, $ocena, $opis, $data_dodania);
                
                if ($stmt->execute()) {
                    $_SESSION['success_msg'] = "Ocena została dodana pomyślnie!";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $error_msg = "Błąd bazy danych: " . $stmt->error;
                }
            } else {
                $error_msg = "Błąd przygotowania zapytania: " . $conn->error;
            }
        }
    }
}

// Obsługa edycji oceny
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edytuj_ocene'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_msg = "Nieprawidłowy token zabezpieczający";
    } else {
        $ocena_id = (int)$_POST['ocena_id'];
        $ocena = (int)$_POST['ocena'];
        $opis = $conn->real_escape_string($_POST['opis']);
        
        $query = "UPDATE oceny SET ocena = ?, opis = ? WHERE id = ? AND id_nauczyciela = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isii", $ocena, $opis, $ocena_id, $nauczyciel_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Ocena została zaktualizowana pomyślnie!";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_msg = "Błąd podczas aktualizacji oceny: " . $stmt->error;
        }
    }
}

// Obsługa usuwania oceny
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usun_ocene'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_msg = "Nieprawidłowy token zabezpieczający";
    } else {
        $ocena_id = (int)$_POST['ocena_id'];
        
        $query = "DELETE FROM oceny WHERE id = ? AND id_nauczyciela = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $ocena_id, $nauczyciel_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Ocena została usunięta pomyślnie!";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_msg = "Błąd podczas usuwania oceny: " . $stmt->error;
        }
    }
}

// Pobranie wszystkich ocen wystawionych przez nauczyciela
$query = "SELECT o.*, u.imie, u.nazwisko, p.nazwa as przedmiot_nazwa 
          FROM oceny o 
          JOIN uczniowie u ON o.id_ucznia = u.id 
          JOIN przedmioty p ON o.id_przedmiotu = p.id 
          WHERE o.id_nauczyciela = ? 
          ORDER BY o.data_dodania DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $nauczyciel_id);
$stmt->execute();
$result = $stmt->get_result();
$oceny = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

// Pobranie uczniów z klasy wychowawcy (jeśli jest wychowawcą)
$uczniowie = [];
if ($wychowawca_klasy) {
    $query = "SELECT id, imie, nazwisko FROM uczniowie WHERE id_klasy = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $wychowawca_klasy['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $uczniowie = $result->fetch_all(MYSQLI_ASSOC);
}

// Pobranie uczniów z klas, które mają lekcje z nauczycielem
$query = "SELECT DISTINCT u.id, u.imie, u.nazwisko, k.nazwa as klasa 
          FROM uczniowie u 
          JOIN klasy k ON u.id_klasy = k.id 
          JOIN plan_lekcji pl ON k.id = pl.klasa_id 
          WHERE pl.przedmiot_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $nauczyciel['id_przedmiotu']);
$stmt->execute();
$result = $stmt->get_result();
$wszyscy_uczniowie = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Nauczyciela - Akademia Wiedzy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            background-color: #b7deeb;
            color: var(--text-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #003d7a);
            box-shadow: var(--shadow);
        }

        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .main-content {
            padding: 30px;
            transition: var(--transition);
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #0069d9);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 15px 20px;
            border-bottom: none;
        }

        .card-header h4 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--light-color);
            border-top: none;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-info {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        .alert {
            border-radius: var(--border-radius);
        }

        .form-control, .form-select {
            border-radius: var(--border-radius);
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
        }

        .teacher-profile {
            text-align: center;
            padding: 20px;
            background: var(--dark-color);
            color: white;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
        }

        .teacher-profile img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 15px;
        }

        .teacher-profile h4 {
            margin-bottom: 5px;
            color: white;
        }

        .teacher-profile p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0;
        }

        .welcome-message {
            background: linear-gradient(135deg, var(--secondary-color), #138496);
            color: white;
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
        }

        .welcome-message h2 {
            color: white;
            margin-bottom: 10px;
        }

        .stats-card {
            text-align: center;
            padding: 20px;
            border-radius: var(--border-radius);
            color: white;
            margin-bottom: 20px;
        }

        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stats-card h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .stats-card p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        .stats-card.primary {
            background: linear-gradient(135deg, var(--primary-color), #0069d9);
        }

        .stats-card.success {
            background: linear-gradient(135deg, #28a745, #218838);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--accent-color), #dc3545);
        }

        .nav-tabs {
            margin-bottom: 20px;
        }

        .nav-tabs .nav-link {
            color: var(--text-color);
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }
        .right{
            text-align: right;
        }

        /* Style dla modali */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-window {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            min-width: 300px;
            max-width: 500px;
            width: 90%;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            margin: 0;
            font-size: 1.2em;
            color: var(--dark-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            color: var(--text-light);
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-school me-2"></i>Akademia Wiedzy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="szkola.php"><i class="fas fa-home"></i> Strona główna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="plan.php"><i class="fas fa-calendar-alt"></i> Plan lekcji</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj się</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="teacher-profile">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nauczyciel['imie'] . '+' . $nauczyciel['nazwisko']); ?>&background=random" alt="Nauczyciel">
            <h4><?php echo htmlspecialchars($nauczyciel['imie'] . ' ' . $nauczyciel['nazwisko']); ?></h4>
            <p><?php echo htmlspecialchars($przedmioty[0]['nazwa']); ?></p>
            <?php if ($wychowawca_klasy): ?>
                <p>Wychowawca: <?php echo htmlspecialchars($wychowawca_klasy['nazwa']); ?></p>
            <?php endif; ?>
        </div>

        <div class="welcome-message">
            <h2><i class="fas fa-chalkboard-teacher me-2"></i> Panel Nauczyciela</h2>
            <p>Witaj, <?php echo htmlspecialchars($nauczyciel['imie'] . ' ' . $nauczyciel['nazwisko']); ?>. Masz pełny dostęp do funkcji nauczyciela.</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card primary">
                    <i class="fas fa-users"></i>
                    <h3><?php echo count($wszyscy_uczniowie); ?></h3>
                    <p>Twoich uczniów</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <i class="fas fa-book"></i>
                    <h3><?php echo count($przedmioty); ?></h3>
                    <p>Przedmiotów</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <i class="fas fa-chalkboard"></i>
                    <h3><?php 
                        $query = "SELECT COUNT(DISTINCT klasa_id) as count FROM plan_lekcji WHERE przedmiot_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $nauczyciel['id_przedmiotu']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        echo $result->fetch_assoc()['count'];
                    ?></h3>
                    <p>Klas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card danger">
                    <i class="fas fa-clock"></i>
                    <h3><?php 
                        $query = "SELECT COUNT(*) as count FROM plan_lekcji WHERE przedmiot_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $nauczyciel['id_przedmiotu']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        echo $result->fetch_assoc()['count'];
                    ?></h3>
                    <p>Godzin lekcyjnych</p>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dodaj-tab" data-bs-toggle="tab" data-bs-target="#dodaj-ocene" type="button" role="tab">
                    <i class="fas fa-plus-circle me-2"></i>Dodaj ocenę
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="klasy-tab" data-bs-toggle="tab" data-bs-target="#moje-klasy" type="button" role="tab">
                    <i class="fas fa-users me-2"></i>Moje klasy
                </button>
            </li>
            <?php if ($wychowawca_klasy): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="wychowawca-tab" data-bs-toggle="tab" data-bs-target="#klasa" type="button" role="tab">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Moja klasa
                    </button>
                </li>
            <?php endif; ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="zarzadzaj-tab" data-bs-toggle="tab" data-bs-target="#zarzadzaj-ocenami" type="button" role="tab">
                    <i class="fas fa-edit me-2"></i>Zarządzaj ocenami
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Sekcja dodawania ocen -->
            <div class="tab-pane fade show active" id="dodaj-ocene" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <?php if (isset($success_msg)): ?>
                            <div class="alert alert-success"><?php echo $success_msg; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error_msg)): ?>
                            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="col-md-6 mb-3">
                                    <label for="uczen_id" class="form-label">Uczeń:</label>
                                    <select class="form-select" id="uczen_id" name="uczen_id" required>
                                        <option value="">Wybierz ucznia</option>
                                        <?php foreach ($wszyscy_uczniowie as $uczen): ?>
                                            <option value="<?php echo $uczen['id']; ?>">
                                                <?php echo htmlspecialchars($uczen['imie'] . ' ' . $uczen['nazwisko'] . ' (' . $uczen['klasa'] . ')'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="przedmiot_id" class="form-label">Przedmiot:</label>
                                    <select class="form-select" id="przedmiot_id" name="przedmiot_id" required>
                                        <option value="">Wybierz przedmiot</option>
                                        <?php foreach ($przedmioty as $przedmiot): ?>
                                            <option value="<?php echo $przedmiot['id']; ?>">
                                                <?php echo htmlspecialchars($przedmiot['nazwa']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="ocena" class="form-label">Ocena:</label>
                                    <select class="form-select" id="ocena" name="ocena" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label for="opis" class="form-label">Opis (np. sprawdzian, odpowiedź ustna):</label>
                                    <input type="text" class="form-control" id="opis" name="opis" required>
                                </div>
                            </div>
                            <button type="submit" name="dodaj_ocene" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Dodaj ocenę
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sekcja moich klas -->
            <div class="tab-pane fade" id="moje-klasy" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Klasa</th>
                                        <th class="right">Liczba uczniów</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        // Pobranie klas, w których nauczyciel prowadzi zajęcia
                                        $query = "SELECT k.id, k.nazwa, COUNT(u.id) as liczba_uczniow 
                                                  FROM klasy k 
                                                  JOIN plan_lekcji pl ON k.id = pl.klasa_id 
                                                  LEFT JOIN uczniowie u ON k.id = u.id_klasy 
                                                  WHERE pl.przedmiot_id = ? 
                                                  GROUP BY k.id, k.nazwa";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("i", $nauczyciel['id_przedmiotu']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $klasy = $result->fetch_all(MYSQLI_ASSOC);
                                        
                                        foreach ($klasy as $klasa): 
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($klasa['nazwa']); ?></td>
                                            <td class="right"><?php echo $klasa['liczba_uczniow']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sekcja mojej klasy (tylko dla wychowawców) -->
            <?php if ($wychowawca_klasy): ?>
                <div class="tab-pane fade" id="klasa" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chalkboard-teacher me-2"></i>Moja klasa - <?php echo htmlspecialchars($wychowawca_klasy['nazwa']); ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Lp.</th>
                                            <th>Imię i nazwisko</th>
                                            <th>Średnia ocen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($uczniowie as $index => $uczen): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo htmlspecialchars($uczen['imie'] . ' ' . $uczen['nazwisko']); ?></td>
                                                <td>
                                                    <?php 
                                                        // Obliczanie średniej ocen ucznia
                                                        $query = "SELECT AVG(ocena) as srednia FROM oceny WHERE id_ucznia = ?";
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->bind_param("i", $uczen['id']);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $srednia = $result->fetch_assoc()['srednia'];
                                                        echo number_format($srednia, 2);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sekcja zarządzania ocenami -->
            <div class="tab-pane fade" id="zarzadzaj-ocenami" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-edit me-2"></i>Zarządzanie ocenami</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success_msg)): ?>
                            <div class="alert alert-success"><?php echo $success_msg; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error_msg)): ?>
                            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Uczeń</th>
                                        <th>Przedmiot</th>
                                        <th>Ocena</th>
                                        <th>Opis</th>
                                        <th>Data</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($oceny as $ocena): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($ocena['imie'] . ' ' . $ocena['nazwisko']); ?></td>
                                            <td><?php echo htmlspecialchars($ocena['przedmiot_nazwa']); ?></td>
                                            <td><?php echo $ocena['ocena']; ?></td>
                                            <td><?php echo htmlspecialchars($ocena['opis']); ?></td>
                                            <td><?php echo date('d.m.Y H:i', strtotime($ocena['data_dodania'])); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="openEditModal(<?php echo $ocena['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="openDeleteModal(<?php echo $ocena['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edycji -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-window">
            <div class="modal-header">
                <h5 class="modal-title">Edytuj ocenę</h5>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form method="POST" id="editForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="ocena_id" id="editOcenaId">
                    
                    <div class="form-group">
                        <label class="form-label">Uczeń:</label>
                        <input type="text" class="form-control" id="editUczen" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Przedmiot:</label>
                        <input type="text" class="form-control" id="editPrzedmiot" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Ocena:</label>
                        <select class="form-select" name="ocena" required>
                            <?php for($i = 1; $i <= 6; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Opis:</label>
                        <input type="text" class="form-control" name="opis" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Anuluj</button>
                    <button type="submit" name="edytuj_ocene" class="btn btn-primary">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal usuwania -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-window">
            <div class="modal-header">
                <h5 class="modal-title">Potwierdź usunięcie</h5>
                <button type="button" class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmText"></p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="ocena_id" id="deleteOcenaId">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Anuluj</button>
                    <button type="submit" name="usun_ocene" class="btn btn-danger">Usuń</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dane ocen
        const oceny = <?php echo json_encode($oceny); ?>;

        // Funkcje do obsługi modali
        function openEditModal(ocenaId) {
            const ocena = oceny.find(o => o.id == ocenaId);
            if (!ocena) return;

            document.getElementById('editOcenaId').value = ocena.id;
            document.getElementById('editUczen').value = ocena.imie + ' ' + ocena.nazwisko;
            document.getElementById('editPrzedmiot').value = ocena.przedmiot_nazwa;
            document.querySelector('#editForm select[name="ocena"]').value = ocena.ocena;
            document.querySelector('#editForm input[name="opis"]').value = ocena.opis;

            document.getElementById('editModal').style.display = 'block';
        }

        function openDeleteModal(ocenaId) {
            const ocena = oceny.find(o => o.id == ocenaId);
            if (!ocena) return;

            document.getElementById('deleteOcenaId').value = ocena.id;
            document.getElementById('deleteConfirmText').textContent = 
                `Czy na pewno chcesz usunąć ocenę ${ocena.ocena} z przedmiotu ${ocena.przedmiot_nazwa} dla ucznia ${ocena.imie} ${ocena.nazwisko}?`;

            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Zamykanie modalu po kliknięciu poza nim
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>