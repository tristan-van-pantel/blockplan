<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>
                <div class="card text-white bg-dark">
                    <h2 class="card-header">Edit Class <?= $class->name ?></h2>
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

    <?php if (!empty($class)) : ?>


        <form action="<?= route_to('commitedit') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="classid" value="<?= $class->id ?>" />
            <div class="form-group">
                <label for="nameid">Name</label>
                <input type="text" class="form-control" placeholder="<?= $class->name ?>" value="<?= $class->name ?>" name="name" id="nameid">

            </div>
            <div class="form-group">
                <label for="beginid">Begin</label>
                <input type="date" class="form-control" value="<?= date("Y-m-d", strtotime($class->begin)) ?>" name="begin" id="beginid">

            </div>
            <div class="form-group">
                <label for="endid">End</label>
                <input type="date" class="form-control" value="<?= date("Y-m-d", strtotime($class->end)) ?>" name="end" id="endid">

            </div>
            <div class="form-row">
            <div class="form-group col-md-6">

                <label for="lstBox1">Schüler der Klasse</label>
                <select multiple class="form-control" id="lstBox1" name="classmates[]">

                    <?php foreach ($students as $student) : ?>

                        <option value="<?= $student->id ?>"><?= (!empty($student->firstname) && !empty($student->lastname)) ? ($student->firstname . ' ' . $student->lastname) : ($student->username) ?></option>

                    <?php endforeach ?>

                </select>
                <button type="button" class="btn btn-light float-right" id='btnRight'><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L12.793 8l-2.647-2.646a.5.5 0 0 1 0-.708z"/>
                        <path fill-rule="evenodd" d="M2 8a.5.5 0 0 1 .5-.5H13a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8z"/>
                    </svg></button>
            </div>
            <div class="form-group col-md-6">
                <label for="lstBox2">klassenlose Schüler</label>
                <select multiple class="form-control" id="lstBox2" name="school[]">

                    <?php foreach ($allStudentsInSchool as $student) : ?>

                        <option value="<?= $student->user_id ?>"><?= (!empty($student->firstname) && !empty($student->lastname)) ? ($student->firstname . ' ' . $student->lastname) : ($student->username) ?></option>

                    <?php endforeach ?>

                </select>
                <button type="button" class="btn btn-light float-left" id='btnLeft'><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 0 1 0 .708L3.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
                        <path fill-rule="evenodd" d="M2.5 8a.5.5 0 0 1 .5-.5h10.5a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg></button>
            </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
            <a href="<?= route_to('classes') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
        </form>
    <?php endif ?>


                    </div>
                </div>









<script>
    function strDes(a, b) {
        if (a.value>b.value) return 1;
        else if (a.value<b.value) return -1;
        else return 0;
    }

    console.clear();
    (function () {
        $('#btnRight').click(function (e) {
            var selectedOpts = $('#lstBox1 option:selected');
            if (selectedOpts.length === 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }
            $('#lstBox2').append($(selectedOpts).clone());
            $(selectedOpts).remove();
            e.preventDefault();
        });


        $('#btnLeft').click(function (e) {
            var selectedOpts = $('#lstBox2 option:selected');
            if (selectedOpts.length === 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $('#lstBox1').append($(selectedOpts).clone());
            $(selectedOpts).remove();
            e.preventDefault();
        });




        $('#submit').click(function() {
            $('#lstBox1 option').prop('selected', true);
        });

        $('#submit').click(function() {
            $('#lstBox2 option').prop('selected', true);
        });


    }(jQuery));

</script>






<?= $this->endSection() ?>
