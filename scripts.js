var projects = {
  "enwikibooks": "wikibooks",
  "commonswiki": "commons",
  "enwiki": "wikipedia",
  "enwikiquote": "wikiquote",
  "enwikisource": "wikisource",
  "enwiktionary": "wiktionary",
  "enwikiversity": "wikiversity",
  "enwikivoyage": "wikivoyage"
}
$(document).ready(function() {
  $('.name').on('input',function() {
    getDesc($('.name').val());
    updatePreview();
  })
  $('.blurb').on('input', updatePreview)
  $('input').on('input', function(){updateWikitext()});
  $('.img').on('input', function() {
    $('.image').hide().attr('src','https://commons.wikimedia.org/wiki/Special:Filepath/' + $('.img').val()).show();
  })
  $('.img').on('load', function() {
    $('.img').slideDown();
  })
})

function getDesc(name) {
  $('.desc').text('Working…');
  $('.editlink').attr('href',"https://en.wikinews.org/w/index.php?action=edit&title=" + encodeURIComponent(name));
  $.getJSON('wikidata.php?name=' + encodeURIComponent(name), function (data) {
    var entry = data[Object.keys(data)[0]]; // use the first
    if(!entry) {
      return;
    }
    if(entry.desc) {
      $('.desc').text(entry.desc)
    } else {
      $('.desc').html('&nbsp;');
    }
    $('input').prop('checked', false);
    for(var i = 0; i < entry.sites.length; i++ ) {
      if(projects[entry.sites[i]]) {
        $('.' + projects[entry.sites[i]] + ' input').prop('checked', true);
      }
    }
    if(entry.image) {
      $('.img').val(entry.image);
      $('.image').attr('src','https://commons.wikimedia.org/wiki/Special:Filepath/' + $('.img').val()).show();
    } else {
      $('.img').val('');
      $('.image').slideUp();
    }
    updateWikitext();
  }).error(function() {
    $('.desc').text('');
  })
}

function updatePreview() {
  console.log("X");
  var blurb = $('.blurb').val();
  var name = $('.name').val();
  if (blurb && name) {
    $('.preview').html('This is the category for <strong>' + name + '</strong>, <strong>' + blurb + '</strong>.');
  } else if (name) {
    $('.preview').html('This is the category for <strong>' + name + '</strong>.');
  } else {
    $('.preview').html('');
  }
}

function updateWikitext() {
  var wikitext = "{{topic cat\n|offset={{{offset|0}}}";
  if($('.blurb').val()) {
    wikitext += "\n|intro=" + $('.blurb').val();
  }
  if($('.img').val()) {
    var img = $('.img').val();
    wikitext += "\n|image=" + img.substr(img.indexOf(':')+1);
  }
  if($('.caption').val()) {
    var img = $('.img').val();
    wikitext += "\n|caption=" + img.substr(img.indexOf(':')+1);
  }
  for (var key in projects) {
    if($('.' + projects[key] + ' input').is(':checked')) {
      wikitext += "\n|" + projects[key] + "={{PAGENAME}}"
    }
  }
  wikitext += "\n}}";
  $('.wikitext').text(wikitext);
}
