<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="young.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="bootstrap.min.js"></script>
        <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
       <title></title>
    </head>
    <body>
    <?php
    require('young.php');
    ?>
    <div class="container-fluid">
        <h1>Young Frame Tensor Product</h1>

        <div class="row">
            <form method="post">
                <div class="col-md-6">
                    <textarea cols="10" rows="5" name="A"></textarea>
                    \( \otimes \)
                    <textarea cols="10" rows="5" name="B"></textarea>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-success btn-lg" value="compute" />
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="well">
                    <?= $result ?>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>