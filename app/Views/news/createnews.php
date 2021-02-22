<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
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

<div class="card mb-3">
    <h3 class="card-header text-white bg-dark">Create News
    </h3>

    <div class="card-body">


        <div class="m-5">
            <?php if (session()->has('message')) : ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
            <?php endif ?>

            <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
            <?php endif ?>

            <?php if (session()->has('errors')) : ?>
            <ul class="alert alert-danger">
                <?php foreach (session('errors') as $error) : ?>
                <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>


            <form action="<?=route_to('insertnews')?>" method="post">
                <?=csrf_field()?>

                <div class="form-group">
                    <label for="who1">Für welche Klassen ist die Nachricht bestimmt? </label>
                    <select multiple class="form-control" name="classes[]" id="who1"
                        size="<?= (!empty($classes)) ? (count($classes)) : ("3") ?>">
                        <?php if (!empty($classes)) : ?>
                        <?php foreach ($classes as $class) : ?>

                        <option value="<?= $class->id ?>"><?= $class->name?></option>
                        <?php endforeach ?>
                        <?php endif ?>
                    </select>
                </div>



                <input id="z" type="hidden" name="news">
                <trix-editor class="trix-content" input="z"></trix-editor>

                <button type="submit" class="btn btn-primary float-right"><i class="far fa-save fa-2x"></i></button>

            </form>



        </div>
    </div>
</div>

<div class="card mb-5">
    <h3 class="card-header text-white bg-dark"> Manage News</h3>
    <div class="card-body m-3">
        <div class="col-md-10">
            <div class="row">
                <?php if ($pager) :?>

                <?= $pager->links() ?>
                <?php endif ?>
            </div>
        </div>
        <?php foreach ($news as $item) : ?>

        <div class="card mb-5">
            <h4 class="card-header text-white bg-dark">
                <?= (empty(($user->getUsername($item->users_id))[0]->firstname) || empty(($user->getUsername($item->users_id))[0]->lastname)) ? ( ($user->getUsername($item->users_id))[0]->username ) : (($user->getUsername($item->users_id))[0]->firstname . ' ' .($user->getUsername($item->users_id))[0]->lastname) ?>
            </h4>

            <div class="card-body m-3">

                <?= $item->news ?>
            </div>
            <div class="card-footer">
                <small>created: <?= $item->time_ago ?></small>
                <?php if ($item->time_ago_updated !== $item->time_ago) : ?>
                <small> | edited: <?= $item->time_ago_updated ?></small>
                <?php endif ?>
                <?php if (!empty($news_model->getNewsClasses($item->id))) : ?>
                | <small> recipients:</small>
                <?php foreach ($news_model->getNewsClasses($item->id) as $i) : ?>
                <span class="badge badge-pill badge-dark"><?= $i->name ?></span>
                <?php endforeach ?>


                <?php endif ?>
                <button type="button" class="btn btn-warning btn-sm float-right" data-toggle="modal"
                    data-target="#Modal<?= $item->id ?>" class="float-left">Delete</button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="Modal<?= $item->id ?>" tabindex="-1" role="dialog"
            aria-labelledby="Modal<?= $item->id ?>ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Modal<?= $item->id ?>ModalLabel">Achtung</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">
                        Wollen Sie die Mitteilung wirklich löschen?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                        <form action="<?= route_to('deletenews') ?>" method="post" class="float-left">
                            <?= csrf_field() ?>
                            <input type="hidden" name="news_id" value="<?= $item->id ?>" />
                            <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach ?>
        <div class="col-md-10">
            <div class="row">
                <?php if ($pager) :?>

                <?= $pager->links() ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre').forEach((block) => {
            hljs.highlightBlock(block);
            hljs.lineNumbersBlock(block);
        });
    });
</script>


<?=$this->endSection()?>