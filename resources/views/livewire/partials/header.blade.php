<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lora:ital@1&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
<style>
    @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700");

    $navbar-white: #fafafa;
    $navbar-black: #131313;
    $navbar-active-color: #fafafa;
    $navbar-bg-active-color: #ff9900;
    $bg-light-blue: #00c6ff;
    $bg-dark-blue: #0072ff;

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100vh;
    }

    body {
        font-family: "Open Sans", sans-serif;
        background: #f8edd2;
    }

    .shrink {
        .navbar {
            color: $navbar-white;
            background: $bg-light-blue;
            background: -webkit-linear-gradient(55deg, $bg-light-blue 10%, $bg-dark-blue 90%);
            background: linear-gradient(35deg, $bg-light-blue 10%, $bg-dark-blue 90%);
        }
    }

    @media (max-width: 991.98px) {
        .custom-dropdown {
            position: absolute;
            top: 98%;
            right: 0;
            background-color: #333;
            padding: 1rem;
            border: 3px solid #333;
            z-index: 999;
            border-radius: 1rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
        }

        .custom-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .custom-dropdown .nav-link {
            color: white;
            padding: 0.5rem 1rem;
        }
    

    }

    .navbar .container {
        position: relative;
    }

    .jumbo {
        position: relative;
        width: 90;
        /* full screen width */
        height: 100vh;
        /* full screen height if desired, or use fixed px */
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .jumbo img.fullscreen-bg {
        position: absolute;
        /* relative to .jumbo */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(90%);
        z-index: -1;
    }

    /* Default transparent navbar */
    nav.navbar {
        background-color: transparent !important;
        /* position: fixed; */
        width: 100%;
        z-index: 10;
        transition: background-color 0.2s ease, box-shadow 0.2s ease;
    }

    /* Background when scrolled */
    nav.navbar.scrolled {
        background-color: #333 !important;
        /* or any solid color you prefer */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }


    .jumbo-content {
        position: relative;
        z-index: 1;
        color: white;
        text-align: center;
        padding: 2rem;
    }

    .img-section1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        /* space between image and buttons */
    }

    .img-section1 img {
        width: 250px;
        height: auto;
    }

    .img-section1-text {
        font-size: 1.5rem;
        font-weight: 400;
        text-transform: uppercase;
        color: white;
        letter-spacing: 1px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
    }

    .button-group {
        display: flex;
        gap: 1rem;
    }

    .button-group button {
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        background: transparent;
        border: 2px solid white;
        color: white;
        text-transform: uppercase;
        font-weight: 300;
        transition: all 0.3s ease;
    }

    .button-group button:hover {
        background: white;
        color: black;
    }

    /* .shrink {
        .navbar {
            color: $navbar-white;
            background: $bg-light-blue;
            background: -webkit-linear-gradient(55deg, $bg-light-blue 10%, $bg-dark-blue 90%);
            background: linear-gradient(35deg, $bg-light-blue 10%, $bg-dark-blue 90%);
            z-index: 10;

        } */
    /* } */

    /* Second Section */
    /* Container with flex, aligned at top-left */
    .custom-container {
        width: 100%;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
        height: 50vh;
        display: flex;
        align-items: flex-start;
        /* Align content at top */
        justify-content: flex-start;
        /* Align content at left */
    }

    /* Row as flex container with full width */
    .custom-row {
        display: flex;
        width: 100%;
        justify-content: flex-start;
        align-items: flex-start;
        margin-top: 0;
        /* Remove top margin so it hugs the top */
    }

    /* Column styling */
    .custom-col {
        width: 100% !important;
        text-align: left !important;
        padding: 2rem 15px;
        font-family: 'Lora', serif;
        background-color: #f7eddc;
        color: #333;
    }

    .custom-col h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .custom-col p {
        font-style: italic;
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 700px;
        margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
        .custom-col {
            width: 50%;
            text-align: left;
        }
    }


    /* Button styling similar to btn btn-primary */
    .read-more {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        padding: 0.5rem 1.5rem;
        background: none;
        border: 1px solid #333;
        border-radius: 999px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background-color 0.3s ease;
        margin-left: -12px;
        margin-top: 7px;
    }

    .read-more:hover {
        background-color: #333;
        color: #fff;
    }

    /* section 3 */
    /* Google Fonts (add to <head> of your HTML) */
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lora&family=Montserrat:wght@500&display=swap');

    .menu-container {
        width: 100%;
        height: 50vh;
        display: flex;
        /* align-items: stretch; */
        font-family: 'Lora', serif;
    }

    .menu-row {
        display: flex;
        width: 100%;
    }

    .menu-image,
    .menu-content {
        width: 50%;
        height: 90vh;
    }



    .menu-image img {
        width: 100%;
        height: 100%;
        /* object-fit: cover; */
    }

    .menu-content {
        background-color: #c7b89e;
        padding: 4rem 3rem;
        display: flex;
        flex-direction: column;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
    }

    .menu-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
    }

    .menu-content p {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        max-width: 600px;
    }

    .view-menu {
        font-family: 'Montserrat', sans-serif;
        padding: 0.7rem 2rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        border: 1px solid #333;
        border-radius: 999px;
        background: none;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 1px;
    }

    .view-menu:hover {
        background-color: #333;
        color: #fff;
    }
</style>
