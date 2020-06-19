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

<div class="container">
  <h2>Ajouter un TO</h2>
  <form class="form-horizontal" action="">
    <div class="form-group">
      <label class="control-label col-sm-2" for="name">Name :</label>
      <div class="col-sm-10">
        <input type="name" class="form-control" id="addTO" placeholder="Enter TO name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="url">URL:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" placeholder="Enter URL" name="url">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="logo">Logo</label>
      <div class="col-sm-10">          
        <input type="file" class="form-control" placeholder="logo" name="logo">
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label><input type="checkbox" name="prenium">Prenium TO ?</label>
        </div>
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>


<!-- ----------------------------------------footer------------------------------------------------------- -->

<?php include("includes/footer.php"); ?>


<!-- ----------------------------------------footer end------------------------------------ -->
    </body>
</html>
