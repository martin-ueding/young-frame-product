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
        <div class="row">
            <div class="col-md-12">
                <h1>Young Frame Tensor Product Generator</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-4">
                <h2>What it does</h2>

                <p>In the <a href="http://www.itkp.uni-bonn.de/~juelich/WT14/lecture.html">group theory lecture</a> on <a href="http://www.itkp.uni-bonn.de/~juelich/WT14/Exercises/Exercise13pub.pdf">problem set 13 (PDF)</a> there is an algorithm given that lets you compute the tensor product “\( \otimes \)” of two Young tableaux. In exercise A 13.2 one is asked to compute some tensor products from irreducible representation of SU(3). In the first part, \( 3 \otimes 3 \) and \( 3 \otimes 3 \) is asked.</p>

                <p>During the group theory exercise on 2015-01-26 we went through the steps and computed those products by hand and trial and error. Since is a rather algorithmic task I wanted to write a program for this that does all the labor.</p>
            </div>

            <div class="col-sm-6 col-md-4">
                <h2>How it works</h2>
                <p>In the background, there is a PHP script which does all the work on this server. As always, you can find the source code <a href="https://github.com/martin-ueding/young-frame-product">on GitHub</a>. The file <code>yf.php</code> contains all the functions and actual logic. <code>young.php</code> contains some glue that brings this web interface and the back end together. <code>index.php</code> is the page you view right now, it just shows the results. The design was done with the <a href="http://getbootstrap.com/">Bootstrap framework</a>.</p>

                <p>You enter the Young frames A and B into the system. Then those are converted into arrays. A recursive function called <code>add_block</code> will take the current output frame (starting with A) and a position in B. Then it will try all positions where it can add the selected block from B onto A. Those positions are the end of all the lines as well as a new column at the bottom. The function <code>add_block</code> will then call itself again with the new A which has one more element and advances to the next block on B. You can see the messages that are generated during the process at the bottom of the page.</p>

                <p>If all the blocks of B are used up <em>and</em> the diagram is a legal one, it gets copied into the list of results. This list is displayed in the first well. Then it will go over it again and only take the unique ones and write it as a more compact tensor sum in the second well.</p>
            </div>

            <div class="col-sm-6 col-md-4">
                <h2>How to use it</h2>
                
                <p>Write the Young frames that you want to multiply into the text boxes with any character you like (although I would only take simple letters because Unicode characters probably will be interpreted as multiple boxes). Then hit the green button and get the result below. The first well shows all the generated legal diagrams with the letters in them. The second well will show the more compact tensor sum notation where multiple diagrams are taken together. And in the third well you find the dimension as calculated with the hook rule. In case there are multiple representations with the same dimension, they are still different. Look at their Young frames and decide which one should have a bar (overline).</p>

                <p>So once you have done the homework problem, you can then verify your results or find an error in my program :-)</p>
            </div>
        </div>

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
                            <div class="input-group-addon">B</div>
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
            <div class="col-lg-4 col-md-6">
                <h2>Possible combinations</h2>
                <div class="well">
                    <?= $template_result ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <h2>Unique combinations</h2>
                <div class="well">
                    <?= $template_result_norm ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <h2>Dimensionality</h2>
                <div class="well">
                    <p><?= $template_dim_format ?></p>
                </div>
                <? if ($input_dim == $total_dim): ?>
                <div class="alert alert-success">
                    <p>Total dimension by \( A \otimes B \) is <?= $input_dim ?>. The total dimension in the result is <?= $total_dim ?>.</p>
                </div>
                <? else: ?>
                <div class="alert alert-danger">
                    <p>Total dimension by \( A \otimes B \) is <?= $input_dim ?>. The total dimension in the result is <?= $total_dim ?>. This does not work out!</p>
                </div>
                <? endif ?>
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
