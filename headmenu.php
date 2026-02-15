<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.js"></script>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

<style type="text/css">
    <!--
    .stylesum {
        color: #00CCFF
    }

    #head {
        padding: 15px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #8B0000;
        margin-bottom: 20px;
    }

    #logo {
        margin: 0;
        padding: 0;
    }

    #logo img {
        max-height: 85px;
        transition: transform 0.3s ease;
    }

    #logo img:hover {
        transform: scale(1.05);
    }

    #navigation {
        display: flex;
        margin: 0;
        padding: 0;
        list-style: none;
        align-items: center;
    }

    #navigation li {
        margin: 0 5px;
    }

    #navigation li a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.3s;
        display: block;
    }

    #navigation li a:hover,
    #navigation li.highlighted a:hover {
        background-color: #8B0000;
        color: white;
    }

    #navigation li.highlighted a {
        background-color: #f8f9fa;
        color: #8B0000;
        font-weight: bold;
    }

    /* Dropdown menu styles */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-radius: 5px;
        min-width: 200px;
        padding: 5px 0;
        margin-top: 5px;
        right: 0;
    }

    .dropdown-content ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .dropdown-content li {
        margin: 0 !important;
    }

    .dropdown-content a {
        color: #333 !important;
        padding: 10px 20px !important;
        display: block;
        text-decoration: none;
        transition: all 0.2s;
        font-weight: normal !important;
        text-align: left;
    }

    .dropdown-content a:hover {
        background-color: #f8f9fa !important;
        color: #8B0000 !important;
    }

    .dropdown {
        position: relative;
    }

    .show {
        display: block;
    }

    /* Responsive Navbar */
    @media (max-width: 768px) {
        #head {
            flex-direction: column;
            text-align: center;
        }

        #navigation {
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 15px;
        }

        #navigation li {
            margin: 5px;
        }

        .dropdown-content {
            position: static;
            box-shadow: none;
            margin-top: 5px;
            width: 100%;
        }
    }
    -->
</style>
<?php
include "backend/includes/connection.php";
$NOW = date("Y-m-d");
?>
<!-- begin: head -->
<div id="head" class="non-printable">
    <!-- logo -->
    <h1 id="logo" name="logo">
        <a href="index.php">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/35/LogoIPSI_%281%29.png/1200px-LogoIPSI_%281%29.png" alt="Logo IPSI" height="85" />
        </a>
    </h1>
    <!-- Main menu -->
    <ul id="navigation" class="nav">
        <li class="highlighted">
            <a href="index.php"><i class="bi bi-house-fill"></i> Home</a>
        </li>
        <li class="highlighted">
            <a href="mulai_pendaftaran.php"><i class="bi bi-person-plus-fill"></i> Pendaftaran</a>
        </li>
        <li class="highlighted"><a href="tanding/juri"><i class="bi bi-stopwatch-fill"></i> Juri</a></li>
        <li class="highlighted"><a href="tanding/operator" target="_blank"><i class="bi bi-clipboard-data-fill"></i> Operator</a></li>
        <li class="highlighted"><a href="tanding/dewan" target="_blank"><i class="bi bi-clipboard-data-fill"></i> Dewan</a></li>
        <li class="highlighted"><a href="admin" target="_blank"><i class="bi bi-gear-fill"></i> Admin</a></li>
    </ul>
    <!-- end: head -->
</div>
<!-- begin: promo-box -->
<div id="promo-box" class="printable">
    <div class="inner">
    </div>
</div>
<script type="text/javascript">
    // JavaScript untuk membuat dropdown muncul hanya saat diklik
    $(document).ready(function() {
        $('#dropdownBtn').click(function(e) {
            e.preventDefault();
            $('#dropdownMenu').toggleClass('show');
        });

        // Menutup dropdown ketika klik di luar dropdown
        $(document).click(function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('#dropdownMenu').removeClass('show');
            }
        });

        // Mencegah dropdown tertutup ketika klik di dalam dropdown
        $('.dropdown-content').click(function(e) {
            // Jangan lakukan apa-apa jika mengklik link menu
            if (!$(e.target).is('a')) {
                e.stopPropagation();
            }
        });
    });
</script>