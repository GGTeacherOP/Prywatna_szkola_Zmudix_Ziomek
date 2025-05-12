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
    <title>Rejestracja - Prywatna Szkoła</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --text: #333;
            --text-light: #7f8c8d;
            --success: #27ae60;
            --error: #e74c3c;
        }
        
        .registration-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .registration-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .registration-header h1 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .registration-header p {
            color: var(--text-light);
        }
        
        .registration-form {
            margin-top: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .submit-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        
        .form-footer a {
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .form-footer a:hover {
            color: var(--accent);
            text-decoration: underline;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            text-align: center;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error);
            border: 1px solid var(--error);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <h1>Prywatna Szkoła</h1>
                <p>System rejestracji</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="szkola.html"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="dziennik.php"><i class="fas fa-book"></i> Dziennik</a></li>
                <li><a href="rejestracja.php" class="active-nav"><i class="fas fa-user-plus"></i> Rejestracja</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="registration-container">
            <div class="registration-header">
                <h1><i class="fas fa-user-plus"></i> Rejestracja konta</h1>
                <p>Wypełnij formularz, aby założyć nowe konto</p>
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
                    <input type="text" name="telefon" id="telefon" required placeholder="123456789">
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
        <div class="footer-bottom">
            <p>&copy; 2025 Prywatna Szkoła. Wszelkie prawa zastrzeżone.</p>
        </div>
    </footer>
</body>
</html>
