<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

                <div class="card text-white bg-dark">
                    <h2 class="card-header">Add new Course</h2>
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




    <form action="<?= route_to('insertcourse') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <div class="form-group">
            <label for="internal_id">Kürzel</label>
            <input type="text" class="form-control" id="abbreviation" placeholder="Enter abbreviation" name="internal_id">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="<?= route_to('courses') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
    </form>


                    </div>
                </div>

            </div>
        </div>
    </div>

    <?= $this->endSection() ?>








