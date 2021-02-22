<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

                <div class="card text-white bg-dark">
                    <h2 class="card-header">Add new Room</h2>
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




    <form action="<?= route_to('insertroom') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <div class="form-group">
            <label for="internal_id">Capacity</label>
            <input type="text" class="form-control" id="abbreviation" placeholder="Enter abbreviation" name="capacity">
        </div>
        <div class="form-group">
            <label for="internal_id">Equipment</label>
            <input type="text" class="form-control" id="abbreviation" placeholder="Enter abbreviation" name="installed_equipment">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="<?= route_to('rooms') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
    </form>


                    </div>
                </div>
                <?= $this->endSection() ?>











