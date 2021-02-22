<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Your School Online</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
<?php if (logged_in()) : ?>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
           <li class="nav-item active">
               <a class="nav-link" href="<?= route_to('timetable') ?>">Stundenplan<span class="sr-only"></span></a>
           </li>
           <?php if (in_groups('students')) : ?>
           <li class="nav-item active">
               <a class="nav-link" href="<?= route_to('dashboard') ?>">Dashboard<span class="sr-only"></span></a>
           </li>
           <?php endif ?>
           <li class="nav-item active">
               <a class="nav-link" href="<?= route_to('virtualclassroom') ?>">Virtuelles Klassenzimmer<span class="sr-only"></span></a>
           </li>
           <li class="nav-item active">
               <a class="nav-link" href="<?= route_to('news') ?>" id="newsid">News<span class="sr-only"></span></a>
           </li>
           <li class="nav-item active">
               <a class="nav-link" href="<?= route_to('health') ?>">Krankmeldung<span class="sr-only"></span></a>
           </li>
           <li class="nav-item active">
               <a class="nav-link" href="<?= route_to('jobs') ?>">Arbeit und Praktika<span class="sr-only"></span></a>
           </li>
        </ul>


    </div>

    <?php if (in_groups('admins')) : ?>

        <div class="nav navbar-nav navbar-right">

            <div class="btn-group dropleft">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= user()->username ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="/logout" class="dropdown-item"><h2><strong>Logout</strong></h2></a>
                    
                        <div class="nav navbar-nav navbar-right">
                            <a href="/admin" class="dropdown-item">Benutzerverwaltung</a>
                            <a href="<?= route_to('classes') ?>" class="dropdown-item">Klassenverwaltung</a>
                            <a href="<?= route_to('showactivestudents') ?>" class="dropdown-item">Schülerverwaltung</a>
                            <a href="<?= route_to('showactivelecturers') ?>" class="dropdown-item">Dozentenverwaltung</a>
                            <a href="<?= route_to('courses') ?>" class="dropdown-item">Kursverwaltung</a>
                            <a href="<?= route_to('rooms') ?>" class="dropdown-item">Raumverwaltung</a>
                            <a href="<?= route_to('vacation') ?>" class="dropdown-item">Ferienverwaltung</a>
                            <a href="<?= route_to('illnessmanagement') ?>" class="dropdown-item">Krankenverwaltung</a>
                            <a href="<?= route_to('createnews') ?>" class="dropdown-item">News veröffentlichen</a>
                            <a href="<?= route_to('createjobs') ?>" class="dropdown-item">Jobangebote veröffentlichen</a>

                        </div>


                    
                    <a class="dropdown-item" href="<?= route_to('register') ?>">Register new User</a>
                    
                </div>
            </div>
        </div>
        <?php else : ?>
    <a class="navbar-brand" href="/logout"><strong>Logout</strong><span class="sr-only"></span></a>
<?php endif ?>

    <?php endif ?>


</nav>


