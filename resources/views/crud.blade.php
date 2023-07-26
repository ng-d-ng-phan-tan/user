<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CRUD</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
    <?php
    function GUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
    $id = GUID();
    echo $id;
    ?>
    <div class="container">
        <div class="row">
            <form method="post" action="/crud/update" class="d-flex w-100 row">
                @csrf
                <div class="form-group col-6">
                    <label for="id">ID</label>
                    <!-- <input type="text" class="form-control" name="user_id" value="<?php echo $id ?>"> -->
                    <input type="text" class="form-control" name="user_id" value="3b015c4f-1a95-4ab5-b794-cf00cb01c34d">
                </div>
                <div class="form-group col-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group col-6">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email">
                </div>
                <div class="form-group col-6">
                    <label for="avatar">avatar</label>
                    <input type="text" class="form-control" name="avatar">
                </div>
                <div class="form-group col-6">
                    <label for="gender">gender</label>
                    <input type="checkbox" class="form-control" name="gender" value=true>
                </div>
                <div class="form-group col-6">
                    <label for="dateOfBirth">dateOfBirth</label>
                    <input type="date" class="form-control" name="dateOfBirth">
                </div>
                <div class="form-group col-6">
                    <label for="receiveNotify">receiveNotify</label>
                    <input type="checkbox" class="form-control" name="receiveNotify">
                </div>
                <div class="form-group col-6">
                    <label for="roleID">roleID</label>
                    <input type="text" class="form-control" name="roleID">
                </div>
                <button type="submit" class="btn btn-primary">Add</button>

            </form>
        </div>
    </div>
    <script src="" async defer></script>
</body>








</html>