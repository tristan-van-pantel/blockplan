<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Your School Online</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?=base_url('bootstrap/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('datatables/dataTables.bootstrap4.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url('datatables/datatables.min.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('fontawesome/css/svg-with-js.min.css')?>" />
    <link rel="stylesheet" href="<?=base_url('trix/trix.css')?>"/>
    <link rel="stylesheet"
      href="<?=base_url('highlight-js/styles/foundation.css')?>">
<link rel="stylesheet" href="<?=base_url('bs-stepper-master/src/css/bs-stepper.css')?>">




    <style>
        body {
            padding-top: 5rem;
            /* background-color: darkgray; */
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


</head>

<body>

    <?=view('_navbar')?>

    <main role="main" class="container">


        <div class="container">
            <div class="row">
                <div class="col-sm-12 offset-sm">

                    <?=$this->renderSection('content')?>


                </div>
            </div>
        </div>


    </main><!-- /.container -->
<script src="<?=base_url('jquery/jquery-3.5.1.min.js')?>"></script>
<script src="<?=base_url('bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('tooltips/tooltip.min.js')?>"></script>
<script src="<?=base_url('fontawesome/js/all.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('datatables/datatables.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('datatables/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('datatables/date-de.js')?>"></script>
<script src="<?=base_url('trix/trix.js')?>"></script>
<script src="<?=base_url('highlight-js/highlight.pack.js')?>"></script>
<script src="<?=base_url('highlight-js/highlightjs-line-numbers.js-master/src/highlightjs-line-numbers.js')?>"></script>
<script src="<?=base_url('bs-stepper-master/src/js/bs-stepper.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>


<script>
$(function () {
    setInterval(checkForNews, 3000);
function checkForNews() {
    if (<?=logged_in()?>) {
       
    function getName() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '<?=route_to('showUnread')?>', true);    
    xhr.onload = function(){
        
        //document.getElementById('newsid').innerHTML = 'News <span class="badge badge-light">' + xhr.responseText + '</span>';
        if (parseInt(xhr.responseText) > 0) {
            $("#newsid").notify(
                xhr.responseText + " ungelesene Nachrichten", 
                { position:"bottom" }
            );
        }

    }
    xhr.send();
}
getName();

    }

}
});
</script>




</body>



</html>