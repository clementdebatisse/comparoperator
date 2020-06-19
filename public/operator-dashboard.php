<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Document</title>
</head>

<body>

<div class="container bg-white"><!-- --------------------------------------------------- container -->

<!-- -------------------------------------------------------------navbar-------------------------------------------- -->

<?php include("includes/navbar.php"); ?><br><br>

<!-- --------------------------------------------------------------------------------------------------------------- -->


    <form action="index.php" method="POST" id="addDestination">
        <label for="location">
            Location : 
        </label>
        <input type="text" name="location">

        <label for="price"> 
            Price : 
        </label>
        <input type="number" name="price">

        <label for="thumbnail"> 
            Thumbnail :
        </label>
        <input type="file" name="thumbnail">

        <input type="submit" value="Ajouter destination">

    </form>

    <!-- ----------------------------------------footer------------------------------------------------------- -->

<?php include("includes/footer.php"); ?>


<!-- ----------------------------------------footer end------------------------------------ -->

    
</body>

</html>

