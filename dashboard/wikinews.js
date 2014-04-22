$(document).ready(function() {
  upd()
  $('.reviews, .disputes, .underreviews').hide()
})
function upd() {
  $.getJSON('api.php', function (data) {
    for (var section in data) {
      a(section, data)
    }
    if(data.review.length != 0) {
      $('.reviews').fadeIn()
      $('.disputes').slideUp()
        $('.dev-arrow').text('→')
      if(data['under-review'].length != 0) {
        $('.underreviews').fadeIn()
        $('.rev-arrow').text('↴')
      } else {
        $('.underreviews').fadeOut()
        $('.rev-arrow').text('→')
      }
    } else {
      $('.reviews').slideUp()
      $('.disputes').fadeIn()
      $('.dev-arrow').text('')
    }
  })
  window.setTimeout(upd, 30000)
}

function a(section, data) {
  $.each(data[section], function (datum) {
    if(data[section].length == 0) {
      //$('ul.' + section).append('<li class="disabled list-group-item">Nothing here!</li>')
    } else if ($('ul.' + section + ' li:contains(' + data[section][datum] +')').length == 0) {
      var li = '<li class="list-group-item"><a href="https://en.wikinews.org/wiki/'
      li += data[section][datum] + '">' + data[section][datum] + '</a></li>'
      $('ul.' + section).append(li)
    }
  })
  $('ul.' + section + ' li').each(function(id, li) {
    if (data[section].indexOf(li.firstChild.innerHTML) == -1) {
      $(li).slideUp()
    }
  })
  /*var li = '<li><a href="https://en.wikinews.org/wiki/Category:' + section + '">'
  li += data[section].length + ' ' + section + ' articles</a></li>'
  $('ul.footer').append(li)*/
}

