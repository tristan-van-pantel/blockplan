<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

                <div class="card text-white bg-dark">
                    <h2 class="card-header">Add new Class</h2>
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




    <form action="<?= route_to('insertclass') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <div class="form-group">
            <label for="internal_id">Kürzel</label>
            <input type="text" class="form-control" id="abbreviation" placeholder="Enter abbreviation" name="internal_id">
        </div>
        <div class="form-group">

                <label for="beginid">Begin:</label>
                <input type="date" id="beginid" name="begin"  class="form-control">

        </div>
        <div class="form-group">
            <label for="endid">End:</label>
            <input type="date" id="endid" name="end" class="form-control">
        </div>



<!--        <div class="form-group">-->
<!---->
<!--                <label for="size">Size (in students):</label>-->
<!--                <input type="text" id="size" name="enrolled_students" class="form-control">-->
<!---->
<!--        </div>-->


        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="<?= route_to('classes') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
    </form>


                    </div>
                </div>







<?= $this->endSection() ?>


