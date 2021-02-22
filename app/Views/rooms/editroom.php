<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

                <div class="card text-white bg-dark">
                <?php if (!empty($room)) : ?>
                
                    <h2 class="card-header">Edit <?= $room->name ?>  </h2>
                    

                  

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


                    
                    <form action="<?= route_to('updateroom') ?>" method="post">
                    <?= csrf_field() ?>
                     <input type="hidden" name="room_id" value="<?= $room->id ?>" />


                    <div class="form-group">
                    <label for="nameid">Name</label>
                    <input type="text" class="form-control" name="name" id="name_id" value="<?= $room->name ?>">
                    </div>
                    <div class="form-group">
                    <label for="nameid">Capacity</label>
                    <input type="text" class="form-control" name="capacity" id="capacity_id" value="<?= $room->capacity ?>">
                    </div>
                    <div class="form-group">
                    <label for="nameid">Installed equipment</label>
                    <input type="text" class="form-control" name="installed_equipment" id="installed_equipment_id" value="<?= $room->installed_equipment ?>">
                    </div>
                   


                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    <a href="<?= route_to('rooms') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
                    </form>
                    <?php endif ?>
                       

                    </div>
                </div>
                <?= $this->endSection() ?>









