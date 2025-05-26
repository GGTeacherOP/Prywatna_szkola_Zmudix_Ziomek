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
    <title>Prywatna Szkoła Akademia Wiedzy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>

<style>
        .calculator-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 999;
            transition: var(--transition);
        }
        
        .calculator-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .calculator-popup {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 300px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            z-index: 1000;
            transform: translateY(20px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            padding: 1.5rem;
        }
        
        .calculator-popup.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        .calculator-popup h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
            text-align: center;
        }
        
        .calculator-form .form-group {
            margin-bottom: 1rem;
        }
        
        .calculator-form label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .calculator-form input {
            width: 100%;
            padding: 0.6rem 0.1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .calculator-result {
            margin-top: 1rem;
            padding: 0.8rem;
            background: var(--light-color);
            border-radius: 4px;
            display: none;
            font-size: 0.9rem;
        }
        
        .calculator-result h4 {
            margin-bottom: 0.3rem;
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        .close-calculator {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-light);
            background: none;
            border: none;
        }
        
        .calculator-popup .btn {
            padding: 0.6rem;
            font-size: 0.9rem;
            width: 100%;
        }
        .calculator-form input[type="checkbox"] {
            width: auto;
            margin-right: 0.5rem;
        }

        .calculator-form .form-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: normal;
            margin-bottom: 0;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-links i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        @media (max-width: 400px) {
            .calculator-popup {
                width: 280px;
                right: 15px;
            }
        }
</style>
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
                <li onclick="showSection(1)" class="active-nav"><a href="#aktualnosci"><i class="fas fa-newspaper"></i> Aktualności</a></li>
                <li onclick="showSection(2)"><a href="#liceum"><i class="fas fa-graduation-cap"></i> Liceum</a></li>
                <li onclick="showSection(3)"><a href="#technikum"><i class="fas fa-laptop-code"></i> Technikum</a></li>
                <li onclick="showSection(4)"><a href="#szkola-podstawowa"><i class="fas fa-book-open"></i> Szkoła Podstawowa</a></li>
                <li onclick="showSection(5)"><a href="#przedszkole"><i class="fas fa-child"></i> Przedszkole</a></li>
                <li onclick="showSection(6)"><a href="#Kontakt"><i class="fas fa-envelope"></i> Kontakt</a></li>
            </ul>
        </div>
        <div class="right-section">
            <section id="aktualnosci" class="section active">
                <h2 class="section-header">Aktualności</h2>
                <div class="news-highlight">
                    <div class="highlight-content">
                        <h3>Rekrutacja 2025/2026</h3>
                        <p>Trwa nabór do wszystkich placówek edukacyjnych Akademii Wiedzy. Zapisy prowadzimy do 30 czerwca 2025.</p>
                        <a href="rejstracja_podstawowa.php" class="btn">Zapisz się do szkoły podstawowej</a><p> </p><a href="rejstracja_przedszkole.php" class="btn">Zapisz się do przedszkola!</a>
                    </div>
                </div>

                <div class="news-grid">
                    <article class="news-card">
                        <div class="news-date">
                            <span class="day">10</span>
                            <span class="month">Maj</span>
                            <span class="year">2025</span>
                        </div>
                        <div class="news-content">
                            <h4>„Mistrz Matematyki” – Wyniki konkursu</h4>
                            <p>Michał Kowalski z klasy 3B zdobył I miejsce w szkolnym konkursie matematycznym (98% punktów). II miejsce - Anna Nowak (2A, 94%), III - Jan Wiśniewski (3C, 91%). 
                                Laureaci otrzymali nagrody książkowe. Gratulacje dla nauczycieli przygotowujących!<span class="hidden-text"> 
                                    Michał zakwalifikował się do etapu ogólnopolskiego w Warszawie. Szkoła zapewnia mu dodatkowe konsultacje.</span></p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>

                    <article class="news-card">
                        <div class="news-date">
                            <span class="day">05</span>
                            <span class="month">Maj</span>
                            <span class="year">2025</span>
                        </div>
                        <div class="news-content">
                            <h4>Konkurs Plastyczny „Zielona Planeta”</h4>
                            <p>Julia Nowak z 5B wygrała ogólnopolski konkurs ekologiczny Ministerstwa Środowiska. Jeja praca "Ocalmy nasz świat" zachwyciła jury. 
                                Nagroda: tablet graficzny i warsztaty artystyczne.<span class="hidden-text"> 
                                    Wyróżnienia dla Michała Zawadzkiego (4A) i Oliwiery Piotrowskiej (6C). Ich prace w szkolnej galerii.</span></p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>

                    <article class="news-card">
                        <div class="news-date">
                            <span class="day">30</span>
                            <span class="month">Kwi</span>
                            <span class="year">2025</span>
                        </div>
                        <div class="news-content">
                            <h4>Zbiórka charytatywna</h4>
                            <p>Dziękujemy za udział w akcji charytatywnej - zebraliśmy ponad 200 paczek dla dzieci z domów dziecka...</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>

                    <article class="news-card">
                        <div class="news-date">
                            <span class="day">25</span>
                            <span class="month">Kwi</span>
                            <span class="year">2025</span>
                        </div>
                        <div class="news-content">
                            <h4>Zwycięstwo w zawodach matematycznych</h4>
                            <p>Nasza drużyna licealna zdobyła I miejsce w międzyszkolnym konkursie matematycznym...</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>

                    <article class="news-card">
                        <div class="news-date">
                            <span class="day">20</span>
                            <span class="month">Kwi</span>
                            <span class="year">2025</span>
                        </div>
                        <div class="news-content">
                            <h4>Sukces w konkursie recytatorskim</h4>
                            <p>Julia Tomaszewska z przedszkola dotarła do finału ogólnopolskiego konkursu "Mali Artyści"...</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>

                    <article class="news-card">
                        <div class="news-date">
                            <span class="day">15</span>
                            <span class="month">Kwi</span>
                            <span class="year">2025</span>
                        </div>
                        <div class="news-content">
                            <h4>Festyn Rodzinny</h4>
                            <p>Relacja z udanego festynu rodzinnego, który zgromadził ponad 300 uczestników...</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                </div>

                <div class="calendar-section">
                    <h3><i class="far fa-calendar-alt"></i> Nadchodzące wydarzenia</h3>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date">25 Maj</div>
                            <div class="event-info">
                                <h5>Dzień Sportu</h5>
                                <p>Turnieje i zawody sportowe dla wszystkich uczniów</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">01 Cze</div>
                            <div class="event-info">
                                <h5>Dzień Dziecka</h5>
                                <p>Specjalne atrakcje dla uczniów przedszkola i szkoły podstawowej</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">15 Cze</div>
                            <div class="event-info">
                                <h5>Zakończenie roku szkolnego</h5>
                                <p>Uroczyste zakończenie roku szkolnego 2024/2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="liceum" class="section">
                <h2 class="section-header">Liceum Ogólnokształcące</h2>
                
                <div class="news-highlight">
                    <div class="highlight-content">
                        <h3>Witamy w naszym liceum!</h3>
                        <p>Nasze liceum oferuje wysokiej jakości edukację, przygotowującą uczniów do dalszych etapów nauki, a także do życia zawodowego. Zajmujemy się kształtowaniem młodych ludzi, oferując im wszechstronną wiedzę teoretyczną oraz praktyczną.</p>
                    </div>
                </div>
                
                <h3>Oferta Edukacyjna</h3>
                <p>W naszym liceum oferujemy różne profile kształcenia, które dostosowane są do zainteresowań oraz predyspozycji uczniów. Każdy profil przygotowuje do matury i dalszego kształcenia na uczelniach wyższych.</p>

                <div class="news-grid">
                    <div class="news-card">
                        <div class="news-date">
                            <span class="day">01</span>
                            <span class="month">Profil</span>
                            <span class="year">Human.</span>
                        </div>
                        <div class="news-content">
                            <h4>Profil humanistyczny</h4>
                            <p>Rozszerzony program z języka polskiego i historii. Dodatkowo: retoryka, debaty oksfordzkie i przygotowanie do olimpiad.
                                <span class="hidden-text"> Współpraca z Uniwersytetem Warszawskim - comiesięczne wykłady profesorów.</span></p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date">
                            <span class="day">02</span>
                            <span class="month">Profil</span>
                            <span class="year">Mat-Fiz</span>
                        </div>
                        <div class="news-content">
                            <h4>Profil matematyczno-fizyczny</h4>
                            <p>Główne przedmioty to matematyka, fizyka, informatyka oraz chemia. Doskonały wybór dla uczniów, którzy planują kariery w naukach ścisłych i technicznych.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date">
                            <span class="day">03</span>
                            <span class="month">Profil</span>
                            <span class="year">Bio-Chem</span>
                        </div>
                        <div class="news-content">
                            <h4>Profil biologiczno-chemiczny</h4>
                            <p>Program skupiający się na biologii, chemii oraz matematyce, przeznaczony dla osób zainteresowanych medycyną, farmacją lub biotechnologią.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="calendar-section">
                    <h3><i class="fas fa-check-circle"></i> Dlaczego warto wybrać nasze liceum?</h3>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date">
                                <span>1</span>
                            </div>
                            <div class="event-info">
                                <h5>Doświadczona kadra pedagogiczna</h5>
                                <p>Nasi nauczyciele to specjaliści w swoich dziedzinach, z wieloletnim doświadczeniem w pracy z młodzieżą. Dbają o rozwój intelektualny oraz osobisty każdego ucznia.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <span>2</span>
                            </div>
                            <div class="event-info">
                                <h5>Indywidualne podejście do ucznia</h5>
                                <p>Stawiamy na małe klasy, co pozwala na lepsze dostosowanie programu nauczania do potrzeb i możliwości uczniów.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <span>3</span>
                            </div>
                            <div class="event-info">
                                <h5>Nowoczesne zaplecze dydaktyczne</h5>
                                <p>Posiadamy świetnie wyposażone sale lekcyjne, laboratoria, pracownie komputerowe oraz bibliotekę z bogatym księgozbiorem.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <span>4</span>
                            </div>
                            <div class="event-info">
                                <h5>Projekty i programy dodatkowe</h5>
                                <p>Uczniowie mogą brać udział w różnych projektach edukacyjnych, wyjazdach naukowych oraz warsztatach, które poszerzają ich horyzonty.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <span>5</span>
                            </div>
                            <div class="event-info">
                                <h5>Wysokie wyniki matur</h5>
                                <p>Nasze liceum regularnie osiąga wysokie wyniki matury, a nasi absolwenci dostają się na najlepsze uczelnie w kraju i za granicą.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>

            <section id="technikum" class="section">
                <h2 class="section-header">Technikum</h2>
                
                <div class="news-highlight" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <div class="highlight-content">
                        <h3>Technikum - praktyczne kształcenie</h3>
                        <p>Technikum to idealny wybór dla uczniów, którzy chcą połączyć naukę z praktyką. Oferujemy różnorodne kierunki kształcenia, które przygotowują młodzież do zawodu i umożliwiają zdobycie dyplomu technika w wybranej dziedzinie.</p>
                    </div>
                </div>
                
                <h3>Oferta Edukacyjna</h3>
                <p>W naszym technikum oferujemy różne specjalności, które łączą wiedzę teoretyczną z praktycznymi umiejętnościami w zawodach technicznych i zawodach związanych z nowoczesnymi technologiami.</p>

                <div class="news-grid">
                    <div class="news-card">
                        <div class="news-date" style="background: #ff6b6b;">
                            <span class="day">IT</span>
                            <span class="month">Technik</span>
                            <span class="year">Info.</span>
                        </div>
                        <div class="news-content">
                            <h4>Technik informatyki</h4>
                            <p>Nauka programowania, tworzenia aplikacji i administracji sieciami. Praktyki w firmach IT. 90% absolwentów znajduje pracę w zawodzie.
                                <span class="hidden-text"> Nowoczesne pracownie z profesjonalnym oprogramowaniem.</span></p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #6b6bff;">
                            <span class="day">EL</span>
                            <span class="month">Technik</span>
                            <span class="year">Elekt.</span>
                        </div>
                        <div class="news-content">
                            <h4>Technik elektronik</h4>
                            <p>Specjalność przygotowująca do pracy w sektorze elektroniki i automatyki. Uczniowie uczą się m.in. projektowania i naprawy urządzeń elektronicznych oraz obsługi nowoczesnych systemów automatyki.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #6bff6b;">
                            <span class="day">LOG</span>
                            <span class="month">Technik</span>
                            <span class="year">Log.</span>
                        </div>
                        <div class="news-content">
                            <h4>Technik logistyk</h4>
                            <p>Program nauczania obejmuje organizację transportu, zarządzanie łańcuchem dostaw oraz zarządzanie magazynami i dystrybucją. Idealna ścieżka dla osób zainteresowanych branżą logistyczną.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #ffb86b;">
                            <span class="day">MECH</span>
                            <span class="month">Technik</span>
                            <span class="year">Mech.</span>
                        </div>
                        <div class="news-content">
                            <h4>Technik mechanik</h4>
                            <p>Przygotowanie do pracy w przemyśle maszynowym i motoryzacyjnym. Uczniowie uczą się m.in. naprawy maszyn, technologii produkcji oraz projektowania konstrukcji mechanicznych.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="calendar-section">
                    <h3><i class="fas fa-check-circle"></i> Dlaczego warto wybrać nasze technikum?</h3>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date" style="background: #ff6b6b;">
                                <span>1</span>
                            </div>
                            <div class="event-info">
                                <h5>Praktyczne kształcenie</h5>
                                <p>Program nauczania łączy teorię z praktyką, a nasi uczniowie odbywają praktyki zawodowe w renomowanych firmach.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #6b6bff;">
                                <span>2</span>
                            </div>
                            <div class="event-info">
                                <h5>Wysokiej jakości wyposażenie</h5>
                                <p>Technikum dysponuje nowoczesnymi laboratoriami i warsztatami, które umożliwiają uczniom zdobywanie praktycznych umiejętności.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #6bff6b;">
                                <span>3</span>
                            </div>
                            <div class="event-info">
                                <h5>Wsparcie zawodowe</h5>
                                <p>Nasi uczniowie mają dostęp do doradztwa zawodowego, które pomaga w znalezieniu pracy po ukończeniu szkoły.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #ffb86b;">
                                <span>4</span>
                            </div>
                            <div class="event-info">
                                <h5>Certyfikaty i uprawnienia</h5>
                                <p>Uczniowie mogą zdobyć dodatkowe certyfikaty zawodowe, które zwiększają ich atrakcyjność na rynku pracy.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>

            <section id="szkola-podstawowa" class="section">
                <h2 class="section-header">Szkoła Podstawowa</h2>
                
                <div class="news-highlight" style="background: linear-gradient(135deg, #6b6bff, #4b4bff);">
                    <div class="highlight-content">
                        <h3>Szkoła podstawowa - fundament edukacji</h3>
                        <p>Nasza szkoła podstawowa to miejsce, w którym dzieci rozwijają swoje talenty, uczą się podstawowych umiejętności i zdobywają wiedzę niezbędną do dalszej edukacji. Stawiamy na indywidualne podejście do każdego ucznia, tworząc przyjazne i inspirujące środowisko.</p>
                    </div>
                </div>
                
                <h3>Oferta Edukacyjna</h3>
                <p>Oferujemy pełen program nauczania dla uczniów klas 1-8, który obejmuje wszystkie przedmioty obowiązkowe, a także dodatkowe zajęcia rozwijające talenty i pasje dzieci.</p>

                <div class="news-grid">
                    <div class="news-card">
                        <div class="news-date" style="background: #6b6bff;">
                            <span class="day">1-3</span>
                            <span class="month">Klasy</span>
                            <span class="year">Wczesne</span>
                        </div>
                        <div class="news-content">
                            <h4>Klasy 1-3</h4>
                            <p>Nauka przez zabawę z wykorzystaniem tablic interaktywnych i tabletów. Rozwijamy czytanie, pisanie i liczenie.
                                <span class="hidden-text"> Zajęcia dodatkowe: robotyka, programowanie i warsztaty przyrodnicze.</span></p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #6b6bff;">
                            <span class="day">4-6</span>
                            <span class="month">Klasy</span>
                            <span class="year">Średnie</span>
                        </div>
                        <div class="news-content">
                            <h4>Klasy 4-6</h4>
                            <p>W tym etapie uczniowie pogłębiają swoją wiedzę z przedmiotów ogólnych, takich jak matematyka, język polski, historia, przyroda, a także uczą się języków obcych.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #6b6bff;">
                            <span class="day">7-8</span>
                            <span class="month">Klasy</span>
                            <span class="year">Starsze</span>
                        </div>
                        <div class="news-content">
                            <h4>Klasy 7-8</h4>
                            <p>Ostatni etap nauki w szkole podstawowej, który przygotowuje uczniów do egzaminu ósmoklasisty oraz do wyboru dalszej drogi edukacyjnej.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="calendar-section">
                    <h3><i class="fas fa-check-circle"></i> Dlaczego warto wybrać naszą szkołę podstawową?</h3>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date" style="background: #6b6bff;">
                                <span>1</span>
                            </div>
                            <div class="event-info">
                                <h5>Indywidualne podejście</h5>
                                <p>Nasi nauczyciele dbają o rozwój każdego dziecka, dostosowując metody nauczania do potrzeb uczniów.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #6b6bff;">
                                <span>2</span>
                            </div>
                            <div class="event-info">
                                <h5>Przyjazna atmosfera</h5>
                                <p>Tworzymy atmosferę sprzyjającą nauce i rozwojowi, w której każde dziecko czuje się bezpiecznie i komfortowo.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #6b6bff;">
                                <span>3</span>
                            </div>
                            <div class="event-info">
                                <h5>Różnorodne zajęcia dodatkowe</h5>
                                <p>Oferujemy koła zainteresowań, zajęcia sportowe, artystyczne oraz edukacyjne, które wspierają wszechstronny rozwój dzieci.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="rejstracja_podstawowa.php" class="btn">Zapisz swoje dziecko</a>
            </section>

            <section id="przedszkole" class="section">
                <h2 class="section-header">Przedszkole</h2>
                
                <div class="news-highlight" style="background: linear-gradient(135deg, #ffb86b, #ffa64b);">
                    <div class="highlight-content">
                        <h3>Przedszkole - pierwszy krok w edukację</h3>
                        <p>Nasze przedszkole to miejsce, w którym dzieci w wieku przedszkolnym uczą się poprzez zabawę, rozwijają swoje talenty oraz umiejętności społeczne. Stawiamy na indywidualne podejście, aby każde dziecko mogło rozwijać się w swoim tempie i w zgodzie z własnymi zainteresowaniami.</p>
                    </div>
                </div>
                
                <h3>Oferta Edukacyjna</h3>
                <p>Oferujemy edukację przedszkolną dla dzieci w wieku 3-6 lat. Program nauczania obejmuje szeroki zakres zajęć rozwijających zdolności poznawcze, motoryczne i społeczne dzieci.</p>

                <div class="news-grid">
                    <div class="news-card">
                        <div class="news-date" style="background: #ffb86b;">
                            <span class="day">AD</span>
                            <span class="month">Program</span>
                            <span class="year">Adapt.</span>
                        </div>
                        <div class="news-content">
                            <h4>Program adaptacyjny</h4>
                            <p>Program adaptacyjny pomaga dzieciom w łatwiejszym przystosowaniu się do życia przedszkolnego. Uczy współpracy z rówieśnikami, rozwiązywania konfliktów i budowania poczucia bezpieczeństwa.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #ffb86b;">
                            <span class="day">EDU</span>
                            <span class="month">Zajęcia</span>
                            <span class="year">Eduk.</span>
                        </div>
                        <div class="news-content">
                            <h4>Zajęcia edukacyjne</h4>
                            <p>Przedszkole oferuje zajęcia rozwijające kreatywność i zdolności manualne, takie jak rysowanie, malowanie, lepienie z gliny, czy zabawy muzyczne.</p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="news-card">
                        <div class="news-date" style="background: #ffb86b;">
                            <span class="day">SP</span>
                            <span class="month">Zajęcia</span>
                            <span class="year">Sport.</span>
                        </div>
                        <div class="news-content">
                            <h4>Zajęcia ruchowe</h4>
                            <p>Gimnastyka korekcyjna i zabawy ruchowe w specjalnej sali z miękkim podłożem. Rozwój motoryki dużej i małej.
                                <span class="hidden-text"> Miesięczne "Dni sportu" z rodzicami i konsultacje z fizjoterapeutą.</span></p>
                            <a href="#" class="read-more">Czytaj więcej <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="calendar-section">
                    <h3><i class="fas fa-check-circle"></i> Dlaczego warto wybrać nasze przedszkole?</h3>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date" style="background: #ffb86b;">
                                <span>1</span>
                            </div>
                            <div class="event-info">
                                <h5>Przyjazna atmosfera</h5>
                                <p>Tworzymy ciepłą, przyjazną atmosferę, w której dzieci czują się bezpiecznie i komfortowo.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #ffb86b;">
                                <span>2</span>
                            </div>
                            <div class="event-info">
                                <h5>Wykwalifikowana kadra</h5>
                                <p>Nasi nauczyciele są profesjonalistami w pracy z dziećmi, posiadają doświadczenie i pasję do pracy pedagogicznej.</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date" style="background: #ffb86b;">
                                <span>3</span>
                            </div>
                            <div class="event-info">
                                <h5>Program rozwoju</h5>
                                <p>Oferujemy programy edukacyjne dostosowane do wieku i potrzeb dzieci, które stawiają na wszechstronny rozwój.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="rejstracja_przedszkole.php" class="btn">Zapisz swoje dziecko</a>
            </section>
            
            <section id="Kontakt" class="section">
                <h2 class="section-header">Kontakt</h2>
                <div class="contact-container">
                    <div class="contact-info">
                        <h3><i class="fas fa-map-marker-alt"></i> Nasza lokalizacja</h3>
                        <p>ul. Akademicka 15<br>00-001 Warszawa</p>
                        
                        <h3><i class="fas fa-phone"></i> Telefon</h3>
                        <p>+48 123 456 789</p>
                        
                        <h3><i class="fas fa-envelope"></i> E-mail</h3>
                        <p><a href="mailto:kontakt@akademiawiedzy.edu.pl">kontakt@akademiawiedzy.edu.pl</a></p>
                        
                        <h3><i class="fas fa-clock"></i> Godziny otwarcia</h3>
                        <p>Pon-Pt: 8:00-18:00<br>Sb: 9:00-14:00</p>
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
                            $temat = $_POST['temat'];
                            $wiadomosc = $_POST['wiadomosc'];


                            $imie_i_nazwisko = mysqli_real_escape_string($conn, $imie_i_nazwisko);
                            $email = mysqli_real_escape_string($conn, $email);
                            $temat = mysqli_real_escape_string($conn, $temat);
                            $wiadomosc = mysqli_real_escape_string($conn, $wiadomosc);

                            $sql = "INSERT INTO $table (imie_i_nazwisko, email, temat, wiadomosc) 
                                    VALUES ('$imie_i_nazwisko', '$email', '$temat', '$wiadomosc')";

                            if ($conn->query($sql) === TRUE) {
                                echo "<p class='success'>Wiadomość została wysłana pomyślnie! Do 7 dni roboczych odpiszemy ci! Sprawdź swojego e-maila!</p>";
                            } else {
                                echo "<p class='error'>Błąd: " . $sql . "<br>" . $conn->error . "</p>";
                            }

                            $conn->close();
                        }
                    ?>

                        <div class="contact-form">
                            <h3>Formularz kontaktowy</h3>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="form-group">
                                    <input type="text" name="imie_i_nazwisko" placeholder="Imię i nazwisko" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="E-mail" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="temat" placeholder="Temat">
                                </div>
                                <div class="form-group">
                                    <textarea name="wiadomosc" placeholder="Wiadomość" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn">Wyślij wiadomość</button>
                            </form>
                        </div>
                </div>
                
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2443.65064626096!2d21.01221531579689!3d52.22967597976094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc669a869f01%3A0x72f0be2a88ead3fc!2sWarszawa!5e0!3m2!1spl!2spl!4v1620000000000!5m2!1spl!2spl" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </section>
        </div>
    </div>
    <div class="calculator-btn" id="calculatorBtn">
        <i class="fas fa-calculator"></i>
    </div>
    
    <!-- Calculator Popup -->
    <div class="calculator-popup" id="calculatorPopup">
    <button class="close-calculator" id="closeCalculator">&times;</button>
    <h3>Kalkulator punktów</h3>
    <form class="calculator-form" id="calculatorForm">
        <div class="form-group">
            <label for="math">Matematyka:</label>
            <input type="number" id="math" min="0" max="100" placeholder="0-100" required>
        </div>
        <div class="form-group">
            <label for="polish">Język polski:</label>
            <input type="number" id="polish" min="0" max="100" placeholder="0-100" required>
        </div>
        <div class="form-group">
            <label for="english">Język angielski:</label>
            <input type="number" id="english" min="0" max="100" placeholder="0-100" required>
        </div>
        
        <div class="form-group" style="margin-top: 1rem;">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" id="volunteer" style="margin-right: 0.5rem;">
                Uczestnictwo w wolontariacie
            </label>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" id="contest" style="margin-right: 0.5rem;">
                Laureat konkursu
            </label>
        </div>
        
        <button type="submit" class="btn">Oblicz</button>
    </form>
    
    <div class="calculator-result" id="calculatorResult">
        <h4>Wynik:</h4>
        <p>Punkty: <span id="totalPoints">0</span></p>
        <p>Szansa na dostanie: <span id="admissionChance">-</span></p>
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
                    <li><a href="#aktualnosci" onclick="showSection(1)"><i class="fas fa-newspaper"></i> Aktualności</a></li>
                    <li><a href="#liceum" onclick="showSection(2)"><i class="fas fa-graduation-cap"></i> Liceum</a></li>
                    <li><a href="#technikum" onclick="showSection(3)"><i class="fas fa-laptop-code"></i> Technikum</a></li>
                    <li><a href="#szkola-podstawowa" onclick="showSection(4)"><i class="fas fa-book-open"></i> Szkoła Podstawowa</a></li>
                    <li><a href="#przedszkole" onclick="showSection(5)"><i class="fas fa-child"></i> Przedszkole</a></li>
                    <li><a href="#Kontakt" onclick="showSection(6)"><i class="fas fa-envelope"></i> Kontakt</a></li>
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
</body>
<script src="script.js"></script>
</html>