<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Your School Online</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('datatables/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('datatables/datatables.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('fontawesome/css/svg-with-js.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('trix/trix.css') ?>"/>
    <link rel="stylesheet"
      href="<?= base_url('highlight-js/styles/foundation.css') ?>">
    <link rel="stylesheet" href="<?= base_url('bs-stepper-master/src/css/bs-stepper.css') ?>">
    <style>
        body {
            padding-top: 5rem;            
            background-color: darkgray;
            /* background-color: floralwhite; */

        }

        .btn-group-xs>.btn,
        .btn-xs {
            padding: .25rem .4rem;
            font-size: .875rem;
            line-height: .5;
            border-radius: .2rem;
        }

        .modal-title,
        .modal-body {
            color: black;
        }
        .card 
    { 
        /* border-radius:6px;  */
        -webkit-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.5); 
        -moz-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.5); 
        box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.5); 
    } 
    </style>
<?= $this->include('scripts') ?>

</head>

<body>

    <?= view('_navbar') ?>

    <main role="main" class="container">


        <div class="container">
            <div class="row">
                <div class="col-sm-12 offset-sm">

                    <?= $this->renderSection('content') ?>


                </div>
            </div>
        </div>


    </main><!-- /.container -->


</body>


</html>