<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'tables') ?>
<?= $this->section('content') ?>

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
<h1 class="text-center">Arbeitsangebote und Praktika</h1>


<?php if (!empty($jobs)) : ?>

    <div class="row justify-content-center">
        <?php if ($pager) :?>

        <?= $pager->links() ?>
        <?php endif ?>
    </div>


<?php foreach ($jobs as $item) : ?>

<div class="card mb-5">
    <h3 class="card-header text-white bg-dark">
        <?= (empty(($user->getUsername($item->users_id))[0]->firstname) || empty(($user->getUsername($item->users_id))[0]->lastname)) ? ( ($user->getUsername($item->users_id))[0]->username ) : (($user->getUsername($item->users_id))[0]->firstname . ' ' .($user->getUsername($item->users_id))[0]->lastname) ?>
    </h3>

    <div class="card-body m-3">

        <?= $item->jobs ?>
    </div>
    <div class="card-footer">
        <small>created: <?= $item->time_ago ?></small>
        <?php if ($item->time_ago_updated !== $item->time_ago) : ?>
        <small> | edited: <?= $item->time_ago_updated ?></small>
        <?php endif ?>
        <?php if (!empty($jobs_model->getJobsClasses($item->id))) : ?>
         | <small> recipients:</small>
            <?php foreach ($jobs_model->getJobsClasses($item->id) as $i) : ?>
            <span class="badge badge-pill badge-dark"><?= $i->name ?></span>
            <?php endforeach ?>
        

        <?php endif ?>
    </div>
</div>

<?php endforeach ?>

<div class="row justify-content-center">
        <?php if ($pager) :?>

        <?= $pager->links() ?>
        <?php endif ?>
    </div>

<?php else : ?>
<h4>no jobs to show</h4>
<?php endif ?>


    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre').forEach((block) => {
            hljs.highlightBlock(block);
            hljs.lineNumbersBlock(block);
        });
    });
</script>


<?= $this->endSection() ?>