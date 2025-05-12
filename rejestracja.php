<?php
$komunikat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $imie = trim($_POST['imie'] ?? '');
    $nazwisko = trim($_POST['nazwisko'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $stanowisko = $_POST['stanowisko'] ?? '';

    // Walidacja
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
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Rejestracja</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='plan.css'>
</head>
<body>
    <header>
        <h1>Dziennik Elektroniczny</h1>
        <h2>Stwórz konto</h2>
    </header>
    <main>
        <section id="class-list">
            <form method="post" class="form-container">
                <h2>Rejestracja</h2>
                <label class="form-group">Imię:<br>
                    <input type="text" name="imie" required>
                </label><br>

                <label class="form-group">Nazwisko:<br>
                    <input type="text" name="nazwisko" required>
                </label><br>

                <label class="form-group">Numer telefonu:<br>
                    <input type="text" name="telefon" required>
                </label><br>

                <label class="form-group">Email:<br>
                    <input type="email" name="email" required>
                </label><br>

                <div class="form-group">
                    <label for="position">Stanowisko:</label>
                    <select name="stanowisko" id="position" required>
                        <option value="">-- wybierz --</option>
                        <option value="uczen">Uczeń</option>
                        <option value="nauczyciel">Nauczyciel</option>
                    </select>
                    </div>

                <input type="submit" value="Wyślij" id="zarejestruj">
                <div class="form-footer">
                    <a href="dziennik.php">Wróć do logowania</a>
                </div>
            </form>

            <?php if ($komunikat): ?>
                <p style="color: <?= $komunikat === "Dziękujemy! Skontaktujemy się z Tobą wkrótce." ? 'green' : 'red' ?>;">
                    <?= htmlspecialchars($komunikat) ?>
                </p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy;szkola</p>
    </footer>
</body>
</html>
