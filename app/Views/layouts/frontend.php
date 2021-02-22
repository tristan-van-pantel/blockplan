<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Stundenplan</title>

    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">



    <script src="<?= base_url('jquery/jquery-3.5.1.min.js') ?>"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
    <!-- <script src="<?= base_url('bootstrap/js/bootstrap.min.js') ?>"></script> -->
    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('tooltips/tooltip.min.js') ?>"></script>

    <style>
        body {
            padding-top: 5rem;
            /* background-color: black; */
            /* background-color: d; */

        }

        .card {
            /* border-radius:6px;  */
            -webkit-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.5);
            box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>

    <?= view('_navbar') ?>

    <main role="main" class="container">




        <?= $this->renderSection('content') ?>





    </main><!-- /.container -->


</body>

</html>