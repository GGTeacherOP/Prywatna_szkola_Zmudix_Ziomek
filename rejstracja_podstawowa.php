<?php
// Połączenie z bazą danych
$conn = new mysqli('localhost', 'root', '', 'szkola');

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobranie zdjęć z bazy danych, pomijając ID = 3 (logo)
$sql = "SELECT * FROM zdjecia WHERE ID != 3 and ID !=7";

$result = $conn->query($sql);

$images = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}

$__xq = $conn->query("SELECT dane_zdjecia, typ_mime FROM zdjecia WHERE ID = 3");

$__imgTag = '';
if ($__xq && $__row = $__xq->fetch_assoc()) {
    $__imgData = base64_encode($__row['dane_zdjecia']);
    $__mime = htmlspecialchars($__row['typ_mime']);
    $__imgTag = '<img src="data:' . $__mime . ';base64,' . $__imgData . '" alt="Logo Akademii Wiedzy">';
}

$conn->close(); 
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Rejestracja do Szkoły Podstawowej - Akademia Wiedzy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
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
                <li><a href="dziennik.php"><i class="fas fa-book"></i> Dziennik</a></li>
                <li><a href="rejestracja.php" class="cta-button"><i class="fas fa-user-plus"></i>Stwórz swój dziennik!</a></li>
            </ul>
        </nav>
    </header>
    <div class="school-logo">
    <?php echo $__imgTag; ?>
    </div>

    <section class="image-gallery">
        <div class="gallery-container">
            <div class="gallery-slider">
                <?php foreach ($images as $index => $image): ?>
                <div class="gallery-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="data:<?php echo $image['typ_mime']; ?>;base64,<?php echo base64_encode($image['dane_zdjecia']); ?>" alt="<?php echo htmlspecialchars($image['opis']); ?>">
                    <div class="slide-caption"><?php echo htmlspecialchars($image['opis']); ?></div>
                </div>
                <?php endforeach; ?>
                
                <div class="gallery-nav">
                    <button onclick="prevSlide()">❮</button>
                    <button onclick="nextSlide()">❯</button>
                </div>
            </div>
            
            <div class="gallery-dots">
                <?php foreach ($images as $index => $image): ?>
                <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></span>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="left-section">
            <ul>
                <li onclick="showSection(1)"><a href="#aktualnosci"><i class="fas fa-newspaper"></i>Rejstracja do szkoły podstawowej</a></li>
                
            </ul>
        </div>
        <div class="right-section">
            <section id="szkola-podstawowa" class="section active">
                <h2 class="section-header">Rejestracja do Szkoły Podstawowej</h2>
                
                <div class="news-highlight" style="background: linear-gradient(135deg, #6b6bff, #4b4bff);">
                    <div class="highlight-content">
                        <h3>Zapisz swoje dziecko do naszej szkoły podstawowej</h3>
                        <p>Wypełnij poniższy formularz, aby zgłosić dziecko do naszej szkoły podstawowej na rok szkolny 2025/2026. Skontaktujemy się z Tobą w celu potwierdzenia rejestracji i przekazania dalszych informacji.</p>
                    </div>
                </div>
                
                <?php
                    $host = 'localhost';
                    $user = 'root';
                    $password = '';
                    $database = 'szkola';
                    $table = 'wiadomosci';

                    $conn = new mysqli($host, $user, $password, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $imie_i_nazwisko = $_POST['imie_i_nazwisko'];
                        $email = $_POST['email'];
                        $temat = "Zapis do szkoły podstawowej na rok 2025/2026";
                        $wiadomosc = $_POST['wiadomosc'];

                        $imie_i_nazwisko = mysqli_real_escape_string($conn, $imie_i_nazwisko);
                        $email = mysqli_real_escape_string($conn, $email);
                        $temat = mysqli_real_escape_string($conn, $temat);
                        $wiadomosc = mysqli_real_escape_string($conn, $wiadomosc);

                        $sql = "INSERT INTO $table (imie_i_nazwisko, email, temat, wiadomosc) 
                                VALUES ('$imie_i_nazwisko', '$email', '$temat', '$wiadomosc')";

                        if ($conn->query($sql) === TRUE) {
                            echo "<p class='success'>Formularz rejestracyjny został wysłany pomyślnie! Skontaktujemy się z Tobą w ciągu 7 dni roboczych.</p>";
                        } else {
                            echo "<p class='error'>Błąd: " . $sql . "<br>" . $conn->error . "</p>";
                        }

                        $conn->close();
                    }
                ?>

                <div class="contact-form">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <input type="text" name="imie_i_nazwisko" placeholder="Imię i nazwisko rodzica" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="E-mail kontaktowy" required>
                        </div>
                     
                        <div class="form-group">
                            <textarea name="wiadomosc" placeholder="Imie, nazwisko dziecka. Rok urodzenia dziecka. Dodatkowe informacje (alergie, szczególne potrzeby dziecka, poprzednia szkoła itp.)" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn">Zarejestruj dziecko</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h4>Akademia Wiedzy</h4>
                <p>Prywatna szkoła z 25-letnią tradycją, oferująca kompleksową edukację od przedszkola po liceum.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="footer-column">
                <h4>Szybkie linki</h4>
                <ul class="footer-links">
                    <li><a href="#aktualnosci" onclick="showSection(1)"><i class="fas fa-newspaper"></i> Rejstracja do Szkoły podstawowej!</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Dokumenty</h4>
                <ul>
                    <li><a href="#">Statut szkoły</a></li>
                    <li><a href="#">Regulamin</a></li>
                    <li><a href="#">Rekrutacja</a></li>
                    <li><a href="#">Ochrona danych</a></li>
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
    <script src="script.js"></script>
</body>
</html>