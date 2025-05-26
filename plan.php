<?php
// Połączenie z bazą danych
$conn = new mysqli('localhost', 'root', '', 'szkola');

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    <title>Plany lekcji - Akademia Wiedzy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Dodatkowe style specyficzne dla plan.html */
        .plans-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .plan-section {
            margin-bottom: 3rem;
            background: white; /* Dodane białe tło dla sekcji planów */
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .plan-section h2 {
            color: var(--primary-color);
            padding: 1rem;
            background: rgba(0, 86, 179, 0.1);
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .plan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            background: white; /* Dodane białe tło dla tabeli */
        }
        
        .plan-table thead {
            background: var(--primary-color);
            color: white;
        }
        
        .plan-table th, .plan-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .plan-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .plan-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            float: right;
        }
        
        .plan-btn:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .plan-table th:nth-child(2){
            text-align: right;
            padding-right: 2.5rem;
        }
        
        /* Styl dla kalendarza wydarzeń */
        .events-section {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .events-highlight {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .event-card {
            display: flex;
            align-items: center;
            background: #f9f9f9;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .event-date {
            background: var(--secondary-color);
            color: white;
            padding: 0.8rem;
            border-radius: 8px;
            text-align: center;
            margin-right: 1.5rem;
            min-width: 50px;
        }
        
        .event-day {
            font-size: 1.5rem;
            font-weight: bold;
            display: block;
            line-height: 1;
        }
        
        .event-month {
            font-size: 0.8rem;
            text-transform: uppercase;
            display: block;
        }
        
        .event-info h4 {
            margin: 0 0 0.3rem 0;
            color: var(--dark-color);
        }
        
        .event-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .view-all-btn {
            display: block;
            margin: 2rem auto 0;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
        }
        
        .view-all-btn:hover {
            background: #003d7a;
            transform: translateY(-2px);
        }
        
        /* Styl dla ogłoszeń */
        .announcements-section {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .announcements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .announcement-card {
            display: flex;
            background: #f9f9f9;
            border-radius: 8px;
            padding: 1.5rem;
            transition: var(--transition);
        }
        
        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .announcement-icon {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-right: 1.5rem;
            align-self: center;
        }
        
        .announcement-content h4 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .announcement-content p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6em;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: #fff !important;
            border: none;
            border-radius: 25px;
            padding: 0.7em 1.6em;
            font-size: 1.05em;
            font-weight: 600;
            box-shadow: 0 2px 12px rgba(0,86,179,0.10);
            margin: 1.5rem auto 1.5rem auto;
            transition: background 0.2s, transform 0.2s;
            text-decoration: none;
            cursor: pointer;
            letter-spacing: 0.03em;
        }
        .nav-btn i {
            font-size: 1.15em;
        }
        .nav-btn:hover {
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px) scale(1.04);
            color: #fff;
            text-decoration: none;
        }
        .nav-btn.top {
            display: block;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        .nav-btn.bottom {
            display: block;
            text-align: center;
            margin: 2.5rem auto 0 auto;
        }
        @media (max-width: 500px) {
            .announcements-grid {
                grid-template-columns: 1fr;
            }
            .announcement-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
            }
            .announcement-icon {
                margin-right: 0;
                margin-bottom: 0.7rem;
            }
        }
        @media (max-width: 600px) {
            .nav-btn, .top-link {
                width: 100%;
                font-size: 1em;
                padding: 0.9em 0.5em;
                margin-left: 0;
                margin-right: 0;
                border-radius: 18px;
                text-align: center;
            }
            .events-highlight {
                grid-template-columns: 1fr;
            }
            .event-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
            }
            .event-date {
                margin-right: 0;
                margin-bottom: 0.7rem;
            }
        }
        
        @media (max-width: 900px) {
            .events-highlight {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="school-logo">
        <?php echo $__imgTag; ?>
    </div>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <h1>Akademia Wiedzy</h1>
                <p>Plany lekcji</p>
            </div>
            <div class="header-contact">
                <p><i class="fas fa-phone"></i> +48 123 456 789</p>
                <p><i class="fas fa-envelope"></i> kontakt@akademiawiedzy.edu.pl</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="szkola.php"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="plan.php" class="active-nav"><i class="fas fa-calendar-alt"></i> Plan lekcji</a></li>
                <li><a href="dziennik.php"><i class="fas fa-book"></i> Dziennik</a></li>
                <li><a href="rejestracja.php" class="cta-button"><i class="fas fa-user-plus"></i>Stwórz swój dziennik!</a></li>
            </ul>
        </nav>
    </header>
    <main class="plans-container">
        <a href="#announcements" class="top-link nav-btn top"><i class="fas fa-newspaper"></i> Przejdź do wiadomości</a>
        <section class="events-section">
            <h2><i class="fas fa-calendar-alt"></i> Kalendarz wydarzeń</h2>
            <div class="events-highlight">
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">03</span>
                        <span class="event-month">WRZ</span>
                    </div>
                    <div class="event-info">
                        <h4>Rozpoczęcie roku szkolnego</h4>
                        <p>Godzina 9:00, aula główna</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">14</span>
                        <span class="event-month">PAŹ</span>
                    </div>
                    <div class="event-info">
                        <h4>Dzień Edukacji Narodowej</h4>
                        <p>Uroczysta akademia</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">11</span>
                        <span class="event-month">LIS</span>
                    </div>
                    <div class="event-info">
                        <h4>Dzień Niepodległości</h4>
                        <p>Apel szkolny</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">22</span>
                        <span class="event-month">GRU</span>
                    </div>
                    <div class="event-info">
                        <h4>Jasełka szkolne</h4>
                        <p>Godzina 17:00, sala gimnastyczna</p>
                    </div>
                </div>
            </div>
            <button class="view-all-btn">Zobacz wszystkie wydarzenia</button>
        </section>
        <section class="plan-section" id="timetables">
            <h2><i class="fas fa-graduation-cap"></i> Liceum Ogólnokształcące</h2>
            <table class="plan-table">
                <thead>
                    <tr>
                        <th>Klasy</th>
                        <th>Plan lekcji</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Klasa 1a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="1">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 2a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="2">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 3a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="3">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 4a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="4">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="plan-section">
            <h2><i class="fas fa-laptop-code"></i> Technikum</h2>
            <table class="plan-table">
                <thead>
                    <tr>
                        <th>Klasy</th>
                        <th>Plan lekcji</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Klasa 1T</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="5">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 2T</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="6">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 3T</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="7">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 4T</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="8">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 5T</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="9">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="plan-section">
            <h2><i class="fas fa-book-open"></i> Szkoła Podstawowa</h2>
            <table class="plan-table">
                <thead>
                    <tr>
                        <th>Klasy</th>
                        <th>Plan lekcji</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Klasa 1a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="10">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 2a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="11">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 3a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="12">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 4a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="13">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 5a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="14">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 6a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="15">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 7a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="16">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Klasa 8a</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="17">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="plan-section">
            <h2><i class="fas fa-child"></i> Przedszkole</h2>
            <table class="plan-table">
                <thead>
                    <tr>
                        <th>Grupy</th>
                        <th>Plan zajęć</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Muchomorki</td>
                        <td>
                            <form action="plan1.php" method="GET">
                                <input type="hidden" name="klasa_id" value="18">
                                <button type="submit" class="plan-btn">Zobacz plan <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="announcements-section" id="announcements">
            <h2><i class="fas fa-bullhorn"></i> Ogłoszenia</h2>
            <div class="announcements-grid">
                <div class="announcement-card">
                    <div class="announcement-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="announcement-content">
                        <h4>Dni wolne od zajęć</h4>
                        <p>14-16 października 2025 - dni wolne od zajęć dydaktycznych z okazji konferencji nauczycieli.</p>
                    </div>
                </div>
                <div class="announcement-card">
                    <div class="announcement-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="announcement-content">
                        <h4>Zmiany w podręcznikach</h4>
                        <p>Klasy 1-3 szkoły podstawowej - nowa lista podręczników dostępna w sekretariacie.</p>
                    </div>
                </div>
                <div class="announcement-card">
                    <div class="announcement-icon">
                        <i class="fas fa-bus"></i>
                    </div>
                    <div class="announcement-content">
                        <h4>Zmiany w rozkładzie autobusów</h4>
                        <p>Od 1 września nowy rozkład jazdy autobusów szkolnych. Sprawdź godziny odjazdów.</p>
                    </div>
                </div>
            </div>
            <a href="#timetables" class="top-link nav-btn bottom"><i class="fas fa-newspaper"></i>Wróć do planów lekcji</a>
    </section>
    </main>
    
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
                    <li><a href="szkola.php#aktualnosci"><i class="fas fa-newspaper"></i> Aktualności</a></li>
                    <li><a href="szkola.php#liceum"><i class="fas fa-graduation-cap"></i> Liceum</a></li>
                    <li><a href="szkola.php#technikum"><i class="fas fa-laptop-code"></i> Technikum</a></li>
                    <li><a href="szkola.php#szkola-podstawowa"><i class="fas fa-book-open"></i> Szkoła Podstawowa</a></li>
                    <li><a href="szkola.php#przedszkole"><i class="fas fa-child"></i> Przedszkole</a></li>
                    <li><a href="szkola.php#Kontakt"><i class="fas fa-envelope"></i> Kontakt</a></li>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const events = document.querySelectorAll('.event-card');
        const viewAllBtn = document.querySelector('.view-all-btn');
        let expanded = false;

        function updateEventsDisplay() {
            events.forEach((event, idx) => {
                if (!expanded && idx > 2) {
                    event.classList.add('hidden-event');
                } else {
                    event.classList.remove('hidden-event');
                }
            });
            viewAllBtn.textContent = expanded ? 'Pokaż mniej wydarzeń' : 'Zobacz wszystkie wydarzenia';
        }

        updateEventsDisplay();

        viewAllBtn.addEventListener('click', function() {
            expanded = !expanded;
            updateEventsDisplay();
        });
    });
    </script>
</body>
</html>