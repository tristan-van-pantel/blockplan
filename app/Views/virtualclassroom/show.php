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

    trix-toolbar .trix-button-group--file-tools {
        display: none;
    }

    [type="file"] {
        border: 0;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        white-space: nowrap;
        width: 1px;
    }

    [type="file"]+label {
        background-color: none;
        border-radius: 0.2rem;
        border: 1px solid lightgray;
        border-bottom-color: black;
        color: gray;
        cursor: pointer;
        display: inline-block;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        height: 1.5rem;
        line-height: 1.5rem;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        transition: background-color 0.3s;
    }


    [type="file"]:focus+label {
        outline: 1px dotted #000;
        background-color: #ccf2ff;
        outline: -webkit-focus-ring-color auto 5px;
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
    <h3 class="card-header text-white bg-dark"><?= $current_course->roomname ?>, <?= $current_course->name ?>, bei
        <?= ($current_course->lecturer)->firstname ?> <?= ($current_course->lecturer)->lastname ?> vom
        <?= date("d.m.Y", strtotime($current_course->begin)) ?> bis
        <?= date("d.m.Y", strtotime($current_course->end)) ?></h3>

    <div class="card-body">
        <?php if (session()->has('success')) : ?>
        <div class="alert alert-success">
            <?= session('success') ?>
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

        <ul class="list-group mb-3">
            <li class="list-group-item">
                <h4>In der Klasse sind <?= ($current_course->size) ?> Schüler</h4>
                
                    <?php foreach ($current_course->students as $item) : ?>
                    <a href="#"
                        class="list-group-item list-group-item-action"><i class="fas fa-user"></i>&nbsp&nbsp&nbsp<?= (!empty($item->firstname)) ? ($item->firstname . ' ' . $item->lastname) : ($item->username) ?>
                        aus der Klasse <?= $classesModel->find($item->classes_id)->name ?></a>

                    <?php endforeach ?>
        </ul>


    </div>
</div>






<div class="card mb-3">
    <h3 class="card-header text-white bg-dark">Wichtiges und Unterlagen
    </h3>

    <div class="card-body">

        <?php if (!empty($notice)) : ?>
        <div class="m-5"><?= $notice->notice ?></div>
        <?php endif ?>
        <?php if (($current_course->lecturer)->id === user_id()) : ?>
        <form action="<?= route_to('datenotice') ?>" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="date_id" value="<?= $current_course->dates_id ?>" />
            <?php if (!empty($notice)) : ?>
            <input type="hidden" name="notice_id" value="<?= $notice->id ?>" />
            <?php endif ?>
            <input id="z" type="hidden" value="<?= (!empty($notice)) ? (esc($notice->notice)) : ("") ?>" name="notice">
            <trix-editor class="trix-content" input="z"></trix-editor>

            <button type="submit" class="btn btn-primary float-right"><i class="far fa-save fa-2x"></i></button>

        </form>

        <?php endif ?>

    </div>
</div>







<div class="card mb-3">
    <h3 class="card-header text-white bg-dark">Diskussion</h3>


    <div class="card-body">

        <form action="<?= route_to('addclassroompost') ?>" method="post" enctype="multipart/form-data" class="mb-5">
            <?= csrf_field() ?>
            <input type="hidden" name="date_id" value="<?= $current_course->dates_id ?>" />



            <!-- <textarea id="summernote" name="post"></textarea> -->
            <div class="form-group">
                <input type="file" multiple name="file[]" id="file" class="inputfile"
                    data-multiple-caption="{count} files selected" multiple />
                <label for="file"><i class="fas fa-paperclip fa-lg"></i> <span></span></label>
            </div>
            <!-- <div class="form-group">
            <label for="formGroupExampleInput">Name</label>
            <input type="file" name="file" class="form-control" id="file">
          </div>  -->
            <div class="form-group">
                <input id="x" type="hidden" name="post">
                <trix-editor input="x"></trix-editor>

            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>



        <script>
            (function () {

                document.addEventListener("trix-file-accept", function (event) {
                    event.preventDefault();
                });


            })();
        </script>



        <?php if($datepostspages): ?>

            <div class="row justify-content-center">
                    <?php if ($pager) :?>

                    <?= $pager->links() ?>
                    <?php endif ?>
                </div>

        <?php foreach($datepostspages as $user): ?>


        <div class="card mb-3">
            <div
                class="<?= ( ($current_course->lecturer)->id === $user->user_id ) ? ("card-header text-white bg-danger") : ("card-header") ?> ">
                <?= (!empty($user_model->find($user->user_id)->firstname) && !empty($user_model->find($user->user_id)->lastname) ) ? ($user_model->find($user->user_id)->firstname .' '. $user_model->find($user->user_id)->lastname) : ($user_model->find($user->user_id)->username) ?>
            </div>
            <div class="card-body">

                <div class="card-text"><?= $user->post ?>
                    <?php if (!empty($user->images)) : ?>
                    <?php foreach ($user->images as $image) : ?>

                    <!-- <img src="<?= base_url().DIRECTORY_SEPARATOR. 'public'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$image->filename ?>" alt="image" srcset=""> -->
                    <img class="img-fluid rounded mx-auto d-block img-thumbnail"
                        src="<?= base_url() .'/assets/uploads/'.$image->filename ?>" alt="image" srcset="">


                    <?php endforeach ?>
                    <?php endif ?>
                </div>

            </div>
            <div class="card-footer">
                <small>created: <?= $user->time_ago ?></small>
                <?php if ($user->time_ago_updated !== $user->time_ago) : ?>
                <small> | edited: <?= $user->time_ago_updated ?></small>
                <?php endif ?>
                <?php if ($user->user_id === user_id() ) : ?>
                <a href=" <?= route_to('editdatepost', $user->id) ?>"><button type="button"
                        class="btn btn-primary float-right btn-sm">Edit</button></a>
                <button type="button" class="btn btn-warning btn-sm float-right" data-toggle="modal"
                    data-target="#Modal<?= $user->id ?>">Delete</button>
                <?php elseif (($current_course->lecturer)->id === user_id()) : ?>
                <button type="button" class="btn btn-warning btn-sm float-right" data-toggle="modal"
                    data-target="#Modal<?= $user->id ?>">Delete</button>
                <?php endif ?>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="Modal<?= $user->id ?>" tabindex="-1" role="dialog"
            aria-labelledby="Modal<?= $user->id ?>ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Modal<?= $user->id ?>ModalLabel">Achtung</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">
                        Wollen Sie den Post wirklich löschen?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                        <form action="<?= route_to('deletedatepost') ?>" method="post" class="float-left">
                            <?= csrf_field() ?>
                            <input type="hidden" name="post_id" value="<?= $user->id ?>" />


                            <button type="submit" class="btn btn-warning">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
        <?php endif; ?>

    </div>

    
    <div class="row justify-content-center">
            <?php if ($pager) :?>

            <?= $pager->links() ?>
            <?php endif ?>
        </div>
    

</div>







<script>
    /*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/

    'use strict';

    ;
    (function (document, window, index) {
        var inputs = document.querySelectorAll('.inputfile');
        Array.prototype.forEach.call(inputs, function (input) {
            var label = input.nextElementSibling,
                labelVal = label.innerHTML;

            input.addEventListener('change', function (e) {
                var fileName = '';
                if (this.files && this.files.length > 1)
                    fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}',
                        this.files.length);
                else
                    fileName = e.target.value.split('\\').pop();

                if (fileName)
                    label.querySelector('span').innerHTML = fileName;
                else
                    label.innerHTML = labelVal;
            });

            // Firefox bug fix
            input.addEventListener('focus', function () {
                input.classList.add('has-focus');
            });
            input.addEventListener('blur', function () {
                input.classList.remove('has-focus');
            });
        });
    }(document, window, 0));
</script>


<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre').forEach((block) => {
            hljs.highlightBlock(block);
            hljs.lineNumbersBlock(block);
        });
    });
</script>


<?= $this->endSection() ?>