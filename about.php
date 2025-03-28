<?php include 'includes/HTML-head.php'; ?>
<link href="css/about.css" rel="stylesheet">
<style>
    /* Team Section Styles */
    .team-section {
        padding: 80px 0;
        background-color: #f9f9f9;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-header h1 {
        font-size: 2.8rem;
        margin-bottom: 15px;
        color: #2c3e50;
        position: relative;
        display: inline-block;
    }

    .section-header h1:after {
        content: '';
        position: absolute;
        width: 70px;
        height: 3px;
        background: #3498db;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }

    .section-header .lead {
        color: #7f8c8d;
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .team-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin-bottom: 30px;
    }

    .team-member {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.4s ease;
        width: calc(25% - 30px); /* 4 items per row */
        position: relative;
    }

    .team-row-2 .team-member {
        width: calc(33.33% - 30px); /* 3 items per row */
    }

    .team-member:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .member-photo {
        height: 220px;
        overflow: hidden;
        position: relative;
    }

    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .team-member:hover .member-photo img {
        transform: scale(1.1);
    }

    .member-info {
        padding: 25px 20px;
        text-align: center;
        position: relative;
    }

    .member-info:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #3498db, #9b59b6);
    }

    .member-info h4 {
        margin: 0 0 8px;
        color: #2c3e50;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .member-info p {
        color: #7f8c8d;
        font-size: 0.95rem;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 15px;
    }

    .member-info p:after {
        content: '';
        position: absolute;
        width: 40px;
        height: 2px;
        background: #3498db;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .social-links a {
        color: #fff;
        background: #3498db;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: #2980b9;
        transform: translateY(-3px);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .team-member,
        .team-row-2 .team-member {
            width: calc(50% - 30px);
        }
    }

    @media (max-width: 768px) {
        .team-member,
        .team-row-2 .team-member {
            width: 100%;
            max-width: 350px;
        }
    }
</style>
</head>

<body>

<?php include 'includes/navbar2.php'; ?>

<section class="team-section">
    <div class="container">
        <div class="section-header">
            <h1>Our Development Team</h1>
            <p class="lead">Meet the creative minds and technical experts who bring our vision to life</p>
        </div>

        <!-- First Row - 4 Members -->
        <div class="team-grid">
            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-1.jpg" alt="Arabela Grace Deza" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>Arabela Grace Deza</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/joyceann.calvez" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-4.jpg" alt="Melvin Custodio" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>Melvin Custodio</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/cstd09/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-6.png" alt="JB Rey Locsin" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>JB Rey Locsin</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/jbrey.locsin.9?mibextid=ZbWKwL" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-7.jpg" alt="Brian Agraviador" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>Brian Agraviador</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/teresa.pebrero" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row - 3 Members -->
        <div class="team-grid team-row-2">
            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-3.jpg" alt="Kelly Ann Alinsub" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>Kelly Ann Alinsub</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/teresa.pebrero" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-10.jpg" alt="Brian Bucio" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>Brian Bucio</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/teresa.pebrero" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-photo">
                    <img src="images/pic-9.jpg" alt="Eliza Talion" class="img-fluid">
                </div>
                <div class="member-info">
                    <h4>Eliza Talion</h4>
                    <p>Developer</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/teresa.pebrero" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>