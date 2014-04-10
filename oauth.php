<!DOCTYPE html>
<html>
<head>
  <title>Topic Cat</title>
  <link href="vector.css" rel="stylesheet">
  <meta charset="utf-8">
</head>
<body>
  <span class="hatnote">Mockup</span>
  <h1>Topic cat tool</h1>
  <p>
    You are successfully authenticated as <strong>User</strong> on
    <strong>test.wikipedia.org</strong>.
  </p>
  <ul>
    <li class="yes">You are able to edit pages.</li>
    <li class="yes">You are able to create pages.</li>
    <li class="no">You are unable to protect pages.</li>
  </ul>
  <p>
    Please enter the name of the category you wish to create.
  </p>
  <input type="text" class="textbox center" placeholder="Gyles Brandreth">
  <p class="desc">
    British writer, broadcaster and former Member of Parliament
  </p>
  <p>
    Now complete the template:
  </p>
  <form class="template">
    <label>Intro</label>
    <input type="text" placeholder="a British broadcaster">
    <label>Image</label>
    <input type="text" placeholder="Brandreth.jpg">
    <label>Caption</label>
    <input type="text" placeholder="Brandreth at the RSC, 2007">
  </form>
  <p class="preview">
    This is the category for <strong>Gyles Brandreth</strong>,
    <strong>a British broadcaster</strong>.
  </p>
  <form class="projects">
      <ul class="col">
        <li class="wikibooks">
          <label for="wikibooks">Wikibooks</label>
          <input type="checkbox" name="wikibooks" id="wikibooks">
        </li>
        <li class="commons">
          <label for="commons">Commons</label>
          <input type="checkbox" name="commons" id="commons">
        </li>
        <li class="wikidata">
          <label for="wikidata">Wikidata</label>
          <input type="checkbox" name="wikidata" id="wikidata">
        </li>
      </ul>
      <ul>
        <li class="wikipedia">
          <label for="wikipedia">Wikipedia</label>
          <input type="checkbox" name="wikipedia" id="wikipedia">
        </li>
        <li class="wikiquote">
          <label for="wikiquote">Wikiquote</label>
          <input type="checkbox" name="wikiquote" id="wikiquote">
        </li>
        <li class="wikisource">
          <label for="wikisource">Wikisource</label>
          <input type="checkbox" name="wikisource" id="wikisource">
        </li>
      </ul>
      <ul class="col">
        <li class="wiktionary">
          <label for="wiktionary">Wiktionary</label>
          <input type="checkbox" name="wiktionary" id="wiktionary">
        </li>
        <li class="wikiversity">
          <label for="wikiversity">Wikiversity</label>
          <input type="checkbox" name="wikiversity" id="wikiversity">
        </li>
        <li class="wikivoyage">
          <label for="wikivoyage">Wikivoyage</label>
          <input type="checkbox" name="wikivoyage" id="wikivoyage">
        </li>
      </ul>
  </form>
  <p class="warning">
    Clicking the button below will perform the following actions as <strong>User</strong> at
    <strong>test.wikipedia.org</strong>.<br>
    Remember, you are responsible for the use of this tool.
  </p>
  <form class="actions">
    <ul>
      <li>
        <input type="checkbox" name="edit" id="edit" checked="checked">
        <label for="edit">Add {{topic cat}} to <strong>Category:Gyles Brandreth</strong></label>
      </li>
      <li>
        <input type="checkbox" name="redirect" id="redirect" checked="checked">
        <label for="redirect">Redirect <strong>Gyles Brandreth</strong> to <strong>Category:Gyles Brandreth</strong></label>
      </li>
      <li>
        <input type="checkbox" name="protect" id="protect" checked="checked">
        <label for="protect">Protect <strong>Gyles Brandreth</strong> indefinitely for non-sysops</label>
      </li>
    </ul>
  </form>

  <button class="good">Run commands as User</button>


  <p class="footer">
    Created by <a href="https://en.wikinews.org/wiki/User:Microchip08">Microchip08</a>
    for <a href="https://en.wikinews.org/">English Wikinews</a>.

    Powered by <a href="https://wikitech.wikimedia.org">Wikimedia Labs</a>.
  </p>
</body>
</html>
