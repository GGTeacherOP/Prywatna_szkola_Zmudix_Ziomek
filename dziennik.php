<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "dziennik";

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
        $tabela = $rola === 'uczen' ? 'uczniowie' : 'nauczyciele';
        $sql = "SELECT * FROM $tabela WHERE login = ? AND haslo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login, $haslo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $_SESSION['proby'] = 0;
            $user = $result->fetch_assoc(); // pobiera dane użytkownika
            if ($rola === 'uczen') {
                $_SESSION['uczen_id'] = $user['id'];
                $_SESSION['id_klasy'] = $user['id_klasy']; // jeśli używasz gdzieś klasy ucznia
            } else {
                $_SESSION['nauczyciel_id'] = $user['id'];
            }
        
            $_SESSION['login'] = $login;
            $_SESSION['rola'] = $rola;

            header("Location: " . ($rola === 'uczen' ? "panel_ucznia.php" : "panel_nauczyciela.php"));
            exit;
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
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Plany lekcji</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='plan.css' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1>Dziennik Elektorniczny</h1>
        <h2>Zaloguj się lub stworz konto</h2>
    </header>
    <main>
        <section id="class-list">
        <div class="form-container">
  <h2>Logowanie</h2>
  <form>
    <div class="form-group">
      <label for="username">Nazwa użytkownika</label>
      <input type="text" id="username" placeholder="Wprowadź nazwę użytkownika">
    </div>
    
    <div class="form-group">
      <label for="password">Hasło</label>
      <input type="password" id="password" placeholder="Wprowadź hasło">
    </div>
    
    <input type="submit" id="zaloguj" value="Zaloguj się">
    
    <div class="form-footer">
      <a href="#">Zapomniałeś hasła?</a> | <a href="rejestracja.php">Zarejestruj się</a>
    </div>
  </form>
</div>      
    </main>
    <footer>
        <p>&copy;szkola</p>
    </footer>

 
</body>
</html>
