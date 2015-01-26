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
            <div class="col-md-12">
                <form method="post" class="form-inline">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">N</div>
                            <input type="text" class="form-control" name="N" placeholder="N" value="3" size="1" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">A</div>
                                <textarea cols="10" rows="5" name="A">##
#</textarea>
                        </div>
                    </div>
                    \( \otimes \)
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">A</div>
                                <textarea cols="10" rows="5" name="B">##
#</textarea>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-success btn-lg" value="compute" />
                </form>
            </div>
        </div>

        <? if (count($results) > 0): ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="well">
                    <?= $template_result ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="well">
                    <?= $template_result_norm ?>
                </div>
            </div>
        </div>
        <? endif ?>

        <? if (count($messages) > 0): ?>
        <div class="row">
            <div class="col-md-12">
                <h2>Messages</h2>
                <? foreach ($messages as $message): ?>
                <?= $message ?>
                <? endforeach ?>
            </div>
        </div>
        <? endif ?>
    </div>
    </body>
</html>
