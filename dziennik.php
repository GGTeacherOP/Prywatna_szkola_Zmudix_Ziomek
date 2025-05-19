<?php
session_start();
if (isset($_SESSION['uczen_id'])) {
    header("Location: panel_ucznia.php");
    exit();
} 
elseif (isset($_SESSION['nauczyciel_id'])) {
    header("Location: panel_nauczyciela.php");
    exit();
}
elseif (isset($_SESSION['admin_id'])) {
    header("Location: paneladministratora.php");
    exit();
}

$query = "SELECT * FROM uczniowie WHERE id = ?";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "szkola";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    if (!isset($_SESSION['proby'])) $_SESSION['proby'] = 0;
    if (!isset($_SESSION['ostatnia_proba'])) $_SESSION['ostatnia_proba'] = time();

    if ($_SESSION['proby'] >= 3) {
        $czas_od_blokady = time() - $_SESSION['ostatnia_proba'];
        if ($czas_od_blokady < 60) {
            die("Za dużo nieudanych prób. Spróbuj za " . (60 - $czas_od_blokady) . " sekund.");
        } else {
            $_SESSION['proby'] = 0;
        }
    }

    $login = $_POST['login'] ?? '';
    $haslo = $_POST['haslo'] ?? '';
    $rola = $_POST['rola'] ?? '';

    if ($login && $haslo && $rola) {
        if ($rola === 'administrator') {
            $tabela = 'administratorzy';
        } else {
            $tabela = $rola === 'uczen' ? 'uczniowie' : 'nauczyciele';
        }
        
        $sql = "SELECT * FROM $tabela WHERE login = ? AND haslo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login, $haslo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $_SESSION['proby'] = 0;
            $user = $result->fetch_assoc();
            
            if ($rola === 'uczen') {
                $_SESSION['uczen_id'] = $user['id'];
                $_SESSION['id_klasy'] = $user['id_klasy'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login'] = $login;
                $_SESSION['rola'] = $rola;
                header("Location: panel_ucznia.php");
                exit();
            } elseif ($rola === 'nauczyciel') {
                $_SESSION['nauczyciel_id'] = $user['id'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login'] = $login;
                $_SESSION['rola'] = $rola;
                header("Location: panel_nauczyciela.php");
                exit();
            } elseif ($rola === 'administrator') {
                $_SESSION['admin_id'] = $user['ID'];
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['login'] = $login;
                $_SESSION['rola'] = $rola;
                header("Location: paneladministratora.php");
                exit();
            }
        } else {
            $_SESSION['proby']++;
            $_SESSION['ostatnia_proba'] = time();
            $komunikat = "ZŁY LOGIN lub ZŁE HASŁO. Spróbuj ponownie.";
        }
    } else {
        $komunikat = "Wszystkie pola są wymagane.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Dziennik Elektroniczny - Akademia Wiedzy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .login-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .login-header h2 {
            color: var(--text-light);
            font-size: 1.2rem;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 0.8rem 0.5rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Open Sans', sans-serif;
            transition: var(--transition);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.2);
        }
        
        .radio-group {
            margin: 1.5rem 0;
        }
        
        .radio-group label {
            display: inline-block;
            margin-right: 1.5rem;
            font-weight: 400;
            cursor: pointer;
        }
        
        .radio-group input[type="radio"] {
            margin-right: 0.5rem;
        }
        
        #zaloguj {
            width: 100%;
            padding: 1rem;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }
        
        #zaloguj:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }
        
        .form-footer {
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .form-footer a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .form-footer a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        .error-message {
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
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
                <li><a href="#zapisz" class="cta-button"><i class="fas fa-user-plus"></i> Zapisz się!</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="login-container">
            <div class="login-header">
                <h1><i class="fas fa-user-lock"></i> Logowanie</h1>
                <h2>Zaloguj się do dziennika elektronicznego</h2>
            </div>
            
            <?php if (isset($komunikat)): ?>
                <div class="error-message">
                    <?php echo $komunikat; ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="login"><i class="fas fa-user"></i> Login:</label>
                    <input type="text" name="login" id="login" required>
                </div>
                
                <div class="form-group">
                    <label for="haslo"><i class="fas fa-lock"></i> Hasło:</label>
                    <input type="password" name="haslo" id="haslo" required>
                </div>
                
                
                <div class="radio-group">
                    <label>Kim jesteś?</label><br>
                    <label>
                        <input type="radio" name="rola" value="uczen" required> <i class="fas fa-user-graduate"></i> Uczeń
                    </label>
                    <label>
                        <input type="radio" name="rola" value="nauczyciel"> <i class="fas fa-chalkboard-teacher"></i> Nauczyciel
                    </label>
                    <label>
                        <input type="radio" name="rola" value="administrator"> <i class="fas fa-user-shield"></i> Administrator
                    </label>
                </div>
                
                <button type="submit" id="zaloguj"><i class="fas fa-sign-in-alt"></i> Zaloguj się</button>
                
                <div class="form-footer">
                    <a href="rejestracja.php"><i class="fas fa-user-plus"></i> Zarejestruj się</a>
                </div>
            </form>
        </div>      
    </main>
    
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