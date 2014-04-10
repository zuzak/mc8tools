<!DOCTYPE html>
<html>
<head>
  <title>Topic Cat</title>
  <link href="vector.css" rel="stylesheet">
  <meta charset="utf-8">
</head>
<body>
  <span class="hatnote">Step one of four</span>
  <h1>Topic cat tool</h1>
  <?php if ( isset( $flash ) ) {
    echo '<p class="flash">' . $flash . '</p>';
  } ?>
  <p>
    This tool attempts to automate some of the more tedious parts of creating a
    new category on <a href="https://en.wikinews.org/wiki/">English Wikinews</a>.
  </p>
  <p>
    It will attempt to perform the following actions on your behalf.
    <ul>
      <li>Create a category page.</li>
      <li>Create a mainspace redirect to that category.</li>
      <li>Protect that redirect, where possible.</li>
      <li>Prompt for a <code>{{<a href="https://en.wikinews.org/wiki/Template:Topic cat">topic cat</a>}}</code></li>.
    </ul>
  </p>
  <p>
    In order for it to do that, it needs certain rights on your account.
  </p>
  <p>
    Please authenticate with OAuth to begin.
  </p>

  <a href="init.php" class="progress button">Sign in with Wikinews</a>

  <p class="footer">
    Created by <a href="https://en.wikinews.org/wiki/User:Microchip08">Microchip08</a>
    for <a href="https://en.wikinews.org/">English Wikinews</a>.

    Powered by <a href="https://wikitech.wikimedia.org">Wikimedia Labs</a>.
  </p>
</body>
</html>
