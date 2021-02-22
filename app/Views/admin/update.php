



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Stundenplan</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            padding-top: 5rem;
        }
    </style>


</head>

<body>

<?= view('_navbar') ?>

<main role="main" class="container">

    <h3>Admin</h3>



    <?php if (!empty($user)) : ?>


        <div class="card">

            <div class="card-header">
                Edit user <?= $user->username ?>


            </div>

            <div class="card-body">

                <form action="<?= route_to('update', $user->id) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="userid" value="<?= $user->id ?>" />
                    <?php if (!empty($user->roles)) : ?>
                        <?php foreach ($roles as $role) : ?>
                            <div class="form-check">
                                <input type="checkbox" name="roles[]" value="<?= $role->id ?>" <?= ( in_array($role->name, $userroles ) ? ('checked') :('')) ?>>
                                <label><?= $role->name ?></label>
                            </div>

                        <?php endforeach ?>
                        <button type="submit" class="btn btn-primary">Update</button>

                    <?php endif ?>

                </form>

            </div>
        </div>




    <?php endif ?>



</main><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


</body>
</html>











