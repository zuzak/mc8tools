/*
 * ISC license
 */

var projects = { // list of internal names mapped to projects
  "enwikibooks": "wikibooks",
  "commonswiki": "commons",
  "enwiki": "wikipedia",
  "enwikiquote": "wikiquote",
  "enwikisource": "wikisource",
  "enwiktionary": "wiktionary",
  "enwikiversity": "wikiversity",
  "enwikivoyage": "wikivoyage",
  "wikidata": "wikidata" // placeholder
};
$(document).ready(function() {
	updateWikitext();
	$('.name').keyup(function(e) {
		if(e.keyCode==13) { // the enter key
			getDesc($('.name').val());
			updatePreview();
		} else {
			$('.desc').text('<press return>');
			updatePreview();
			updateWikitext();
		}
	});

	$('.blurb').on('input', updatePreview);
	$('input').on('input', function() {
		updateWikitext();
	});
  	$('input').on('change', function() {
		updateWikitext();
	});
	$('.img').on('input', function() {
	  changeImage();
	})
	$('.image').on('load', function() {
		$('.image').slideDown();
	});
	$('.force').click(function(){updateWikitext()});
	$('.desc, .preview').click(function(){
		updatePreview();
		updateWikitext();
	});
});

function getDesc(name) {
	$('.desc').text('Working…');

	var url = "https://en.wikinews.org/w/index.php?action=edit&title=Category:"+encodeURIComponent(name);
	url += "&preload=User:Microchip08/placeholder&preloadparams%5b%5d="+encodeURI($('.wikitext').text());
	url += "&summary=Adding {{[[Template:Topic cat|topic cat]]}} ([[User:Microchip08/topiccat|assisted]])";
	url += "&editintro=User:Microchip08/topiccat";

	$('.editlink').attr('href', url);

	$.getJSON('wikidata.php?name=' + encodeURIComponent(name), function (data) {
		var key = Object.keys(data)[0]; // use the first
		var entry = data[key];
		$('.wikidatalink').attr('href','https://www.wikidata.org/wiki/'+Object.keys(data)[0]);
		$('.wikidatalink').text(key);
		if(!entry) {
			return;
		}
		if(entry.desc) {
			$('.desc').text(entry.desc);
			$('.blurb').val(indefiniteArticle($('.desc').text()) + ' '+ $('.desc').text());
			updatePreview();
			updateWikitext();
		} else {
      		$('.desc').html('[The Wikidata entry for ' + name + ' <a href="https://www.wikidata.org/wiki/'+Object.keys(data)[0]+'">needs a description</a>]');
			$('.blurb').val('');
		}
    	$('input').prop('checked', false);
		entry.sites["wikidata"] = key; // we want a wikidata link, but it will never be returned by the api
		for (project in entry.sites) {
			if(projects[project]) {
				$('.' + projects[project] + ' input').prop('checked', 'true');
				$('.' + projects[project] + ' input').attr('data-article', entry.sites[project]);
			}
		}
		if(entry.image) {
			$('.img').val(entry.image);
			changeImage();
		} else {
			$('.img').val('');
			changeImage();
			$('.image').slideUp();
		}
		updateWikitext();
	}).error(function() {
		$('.desc').text('[Nothing found.]');
		$('.wikidatalink').text('');
	})
}

function updatePreview() {
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
	var sp = false;
	for (var key in projects) {
		if($('.' + projects[key] + ' input').is(':checked')) {
			var article = $('.'+projects[key]+' input').attr('data-article');
      		if(typeof article == 'undefined') {
				article = '{{PAGENAME}}';
			} else {
				article = article.replace($('.name').val(),'{{PAGENAME}}');
			}
			wikitext += '\n|' + projects[key] + '=' + article;
		}
	}
	if (sp) {
		wikitext += "\n|sisterprojects=yes";
	}
	wikitext += "\n}}";
	$('.wikitext').text(wikitext);
}

function changeImage() {
	$('.image').slideUp().attr('src','https://commons.wikimedia.org/wiki/Special:Filepath/' + $('.img').val())
}
