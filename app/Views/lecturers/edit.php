<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark">

    <h2 class="card-header">Edit Dozent
        <?= (!empty($lecturer->username && !empty($lecturer->firstname) && !empty($lecturer->lastname))) ? ($lecturer->firstname .' '. $lecturer->lastname) : ('') ?>
    </h2>
    <div class="card-body">
        <?php if (session()->has('message')) : ?>
        <div class="alert alert-danger">
            <?= session('message') ?>
        </div>
        <?php endif ?>

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
        <form action="<?= route_to('savelecturer') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <input type="hidden" name="lecturers_id" value="<?= $lecturer->id ?>" />
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput0">Username</label>
                <input type="text" name="username" class="form-control" id="exampleFormControlInput0" value="<?= (!empty($lecturer->username)) ? ($lecturer->username) : ('') ?>">
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Firstname</label>
                <input type="text" name="firstname" class="form-control" id="exampleFormControlInput1" value="<?= (!empty($lecturer->firstname)) ? ($lecturer->firstname) : ('') ?>">
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput2">Lastname</label>
                <input type="text" name="lastname" class="form-control" id="exampleFormControlInput2" value="<?= (!empty($lecturer->lastname)) ? ($lecturer->lastname) : ('') ?>">
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput3">E-Mail</label>
                <input type="text" name="email" class="form-control" id="exampleFormControlInput3" value="<?= (!empty($lecturer->email)) ? ($lecturer->email) : ('') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= route_to('showactivelecturers') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
        </form>




    </div>
</div>
<?= $this->endSection() ?>