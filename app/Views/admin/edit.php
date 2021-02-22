<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>



<?php if (!empty($user)) : ?>


<div class="card text-white bg-dark">


    <h2 class="card-header">Edit user <?= $user->username ?></h2>




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


        <form action="<?= route_to('update', $user->id) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="userid" value="<?= $user->id ?>" />
            <?php if (!empty($user->roles)) : ?>
            <?php foreach ($roles as $role) : ?>
            <div class="form-check">
                <input type="checkbox" name="roles[]" value="<?= $role->id ?>"
                    <?= ( in_array($role->name, $userroles ) ? ('checked') :('')) ?>>
                <label><?= $role->name ?></label>
            </div>

            <?php endforeach ?>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= route_to('admin') ?>"><button type="button" class="btn btn-secondary">Back</button></a>

            <?php endif ?>

        </form>




    </div>
</div>





<?php endif ?>


<?= $this->endSection() ?>