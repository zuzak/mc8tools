<?php require '../shared.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Wikinews dashboard</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.0.0/spacelab/bootstrap.min.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="wikinews.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h3 class="text-primary"><span><?php _e('dashboard-developing');?></span><span class="pull-right dev-arrow">&rarr;</span></h3>
          <ul class="list-group developing"></ul>
        </div>
        <div class="col-md-4">
          <div class="reviews">
            <h3 class="text-warning"><span><?php _e('dashboard-warning');?>/span><span class="pull-right rev-arrow">&rarr;</span></h3>
            <ul class="list-group review"></ul>
            <div class="underreviews">
              <h3 class="text-info"><span><?php _e('dashboard-underreview');?></span><span class="pull-right prog-arrow">&rarr;</span></h3>
              <ul class="list-group under-review"></ul>
            </div>
          </div>
          <div class="disputes">
            <h3 class="text-danger"><span><?php _e('dashboard-disputed');?></span><small class="pull-right dis-arrow"><?php _e('dashboard-pendingreview-none');?></small></h3>
            <ul class="list-group disputed"></ul>
          </div>
        </div>
        <div class="col-md-4">
          <h3 class="text-success"><span><?php _e('dashboard-published');?></span><span class="pub-arrow"></span></h3>
          <ul class="list-group published"></ul>
        </div>
      </div>
      <footer>
        <noscript><span><?php _e('dashboard-noscript'); ?>·</span></noscript><span><?php _e('dashboard-updatenote', array(
		'variables' => array('//en.wikinews.org',30)));?></span></span>
      </footer>
    </div>
  </body>
</html>
