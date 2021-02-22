<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'tables') ?>
<?= $this->section('content') ?>
<style>
    .pagination li a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
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
</style>
<div class="card bg-light mb-3">
    <h3 class="card-header">Edit Post</h3>

    <div class="card-body">
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

        <form action="<?= route_to('insertdatepostedit') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="post_id" value="<?= $post->id ?>" />

            <input id="x" value="<?= esc($post->post) ?>" type="hidden" name="post">
            <trix-editor class="trix-content" input="x"></trix-editor>
            <?php if (!empty($post->images)) : ?>
            <?php foreach ($post->images as $image) : ?>


            <img class="img-fluid rounded mx-auto d-block img-thumbnail"
                src="<?= base_url() .'/assets/uploads/'.$image->filename ?>" alt="image" srcset="">


            <?php endforeach ?>
            <?php endif ?>
            <button type="submit" class="btn btn-primary float-right"><i class="far fa-save fa-2x"></i></button>
            <a href="<?= route_to('virtualclassroom') ?>"><button type="button" class="btn btn-info float-left"><i
                        class="fas fa-step-backward fa-2x"></i></button></a>
        </form>

    </div>

</div>


<script>
    (function () {

        document.addEventListener("trix-file-accept", function (event) {
            event.preventDefault();
        });


    })();
</script>










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



<?= $this->endSection() ?>