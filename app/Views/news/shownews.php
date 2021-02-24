<?=$this->extend('layouts' . DIRECTORY_SEPARATOR . 'tables')?>
<?=$this->section('content')?>

<style>
    .pagination li a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        border: 1px solid;
    }

    .pagination li a.active {
        background-color: #4CAF50;
        color: white;
    }

    .pagination li a:hover:not(.active) {
        background-color: #ddd;
    }

       /* -------------------------------------------------------------------------- */
    /*                       Style highlight.js line-numbers                      */
    /* -------------------------------------------------------------------------- */

    /* for block of numbers */
    .hljs-ln-numbers {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

        text-align: center;
        color: #ccc;
        border-right: 1px solid #CCC;
        vertical-align: top;
        padding-right: 5px;

        /* your custom style here */
    }

    /* for block of code */
    .hljs-ln-code {
        padding-left: 10px;
    }




</style>
<h1 class="text-center">News </h1>


<?php if (!empty($news)): ?>

    <div class="row justify-content-center">
        <?php if ($pager): ?>

        <?=$pager->links()?>
        <?php endif?>
    </div>


<?php foreach ($news as $item): ?>

<div class="card mb-5">
    <h3 class="card-header text-white bg-dark">
        <?=(empty(($user->getUsername($item->users_id))[0]->firstname) || empty(($user->getUsername($item->users_id))[0]->lastname)) ? (($user->getUsername($item->users_id))[0]->username) : (($user->getUsername($item->users_id))[0]->firstname . ' ' . ($user->getUsername($item->users_id))[0]->lastname)?>
    </h3>

    <div class="card-body m-3">

        <?=$item->news?>
    </div>
    <div class="card-footer">
        <small>created: <?=$item->time_ago?></small>
        <?php if ($item->time_ago_updated !== $item->time_ago): ?>
        <small> | edited: <?=$item->time_ago_updated?></small>
        <?php endif?>
        <?php if (!empty($news_model->getNewsClasses($item->id))): ?>
         | <small> recipients:</small>
            <?php foreach ($news_model->getNewsClasses($item->id) as $i): ?>
            <span class="badge badge-pill badge-dark"><?=$i->name?></span>
            <?php endforeach?>


                <?php if ($item->read != 1): ?>
                    <form action="<?=route_to('markread')?>" method="post" class="post_read">
            <!-- CSRF token --> 
   <input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

            <div class="form-group">
                <input type="hidden" name="news_id" value="<?=$item->id?>" />
            </div>
  <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> als gelesen markieren</button>
</form>



                <?php endif?>
        <?php endif?>
    </div>
</div>

<?php endforeach?>

<div class="row justify-content-center">
        <?php if ($pager): ?>

        <?=$pager->links()?>
        <?php endif?>
    </div>

<?php else: ?>
<h4>no news to show</h4>
<?php endif?>




    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre').forEach((block) => {
            hljs.highlightBlock(block);
            hljs.lineNumbersBlock(block);
        });
    });
</script>


<script>
/* document.getElementById.('post_read').addEventListener.('submit', postRead);

function postRead(e) {
    e.preventDefault;

    var news_id = document.getElementById('post_read').value;

    var params = "news_id="+news_id;

    var xhr = new XMLHttpRequest();

    xhr.open('POST', '<?=route_to('markread')?>', true);
    xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        console.log(this.responseText);
    }

    xhr.send();

} */
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script type='text/javascript'>
   //wait for DOM to load
   $(document).ready(function(){
    // if one of the forms is submitted
     $('.post_read').submit(function(e){
         // prevent forms default behaviour (e.g. page-reload)
        e.preventDefault();
   

       // CSRF Hash
       var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
       var csrfHash = $('.txt_csrfname').val(); // CSRF hash
        //of the submittet form (this), get the value of the hidden input, which class is of type .news_id
        var news_id = $(this.news_id).val();
        $(this).hide(); 
 



       // AJAX request
       $.ajax({
          url: "<?=route_to('markread')?>",
          method: 'post',
          data: {username: news_id,[csrfName]: csrfHash },
          dataType: 'json',
          success: function(response){

            // Update CSRF hash
            $('.csrf_test_name').val(response.token);



            if(response.success == 1){
                // $(this.btn).hide();


            }else{
               // Error
               alert(response.error);
            }

          }
       });
     });
   });
   </script>




<?=$this->endSection()?>