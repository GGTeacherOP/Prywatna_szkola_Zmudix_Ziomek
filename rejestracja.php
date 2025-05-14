<?php
$komunikat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $imie = trim($_POST['imie'] ?? '');
    $nazwisko = trim($_POST['nazwisko'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $stanowisko = $_POST['stanowisko'] ?? '';

    if (!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$/u", $imie)) {
        $komunikat = "Imię może zawierać tylko litery.";
    } elseif (!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$/u", $nazwisko)) {
        $komunikat = "Nazwisko może zawierać tylko litery.";
    } elseif (!preg_match("/^\d{9}$/", $telefon)) {
        $komunikat = "Numer telefonu musi zawierać dokładnie 9 cyfr.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $komunikat = "Nieprawidłowy adres e-mail.";
    } elseif ($stanowisko !== "uczen" && $stanowisko !== "nauczyciel") {
        $komunikat = "Wybierz stanowisko.";
    } else {
        $conn = new mysqli("localhost", "root", "", "dziennik");
        if ($conn->connect_error) {
            die("Błąd połączenia: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO nowe_konta (imie, nazwisko, numer_telefonu, mail, stanowisko) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $imie, $nazwisko, $telefon, $email, $stanowisko);

        if ($stmt->execute()) {
            $komunikat = "Dziękujemy! Skontaktujemy się z Tobą wkrótce.";
        } else {
            $komunikat = "Wystąpił błąd przy dodawaniu danych.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Rejestracja - Akademia Wiedzy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Dodatkowe style specyficzne dla rejestracja.php */
        .registration-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .registration-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .registration-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .registration-header p {
            color: var(--text-light);
            font-size: 1.1rem;
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
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem 0.5rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Open Sans', sans-serif;
            transition: var(--transition);
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.2);
        }
        
        .submit-btn {
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
            margin: 1.5rem 0;
        }
        
        .submit-btn:hover {
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
        
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin: 1rem 0;
            text-align: center;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: #27ae60;
            border: 1px solid #27ae60;
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <h1>Akademia Wiedzy</h1>
                <p>System rejestracji</p>
            </div>
            <div class="header-contact">
                <p><i class="fas fa-phone"></i> +48 123 456 789</p>
                <p><i class="fas fa-envelope"></i> kontakt@akademiawiedzy.edu.pl</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="szkola.html"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="plan.html"><i class="fas fa-calendar-alt"></i> Plan lekcji</a></li>
                <li><a href="dziennik.php"><i class="fas fa-book"></i> Dziennik</a></li>
                <li><a href="#zapisz" class="cta-button"><i class="fas fa-user-plus"></i> Zapisz się!</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="registration-container">
            <div class="registration-header">
                <h1><i class="fas fa-user-plus"></i> Rejestracja konta</h1>
                <p>Wypełnij formularz, aby założyć nowe konto w systemie</p>
            </div>
            
            <?php if ($komunikat): ?>
                <div class="alert <?= strpos($komunikat, 'Dziękujemy') !== false ? 'alert-success' : 'alert-error' ?>">
                    <?= htmlspecialchars($komunikat) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="registration-form">
                <div class="form-group">
                    <label for="imie"><i class="fas fa-user"></i> Imię:</label>
                    <input type="text" name="imie" id="imie" required>
                </div>
                
                <div class="form-group">
                    <label for="nazwisko"><i class="fas fa-user-tag"></i> Nazwisko:</label>
                    <input type="text" name="nazwisko" id="nazwisko" required>
                </div>
                
                <div class="form-group">
                    <label for="telefon"><i class="fas fa-phone"></i> Numer telefonu:</label>
                    <input type="text" name="telefon" id="telefon" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="position"><i class="fas fa-briefcase"></i> Stanowisko:</label>
                    <select name="stanowisko" id="position" required>
                        <option value="">-- wybierz --</option>
                        <option value="uczen">Uczeń</option>
                        <option value="nauczyciel">Nauczyciel</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Zarejestruj się</button>
                
                <div class="form-footer">
                    <a href="dziennik.php"><i class="fas fa-sign-in-alt"></i> Masz już konto? Zaloguj się</a>
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