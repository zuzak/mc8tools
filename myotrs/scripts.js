var count = { contribs: 0, templates: 0, max: false};
$( document ).ready( function () {
	$( '.go' ).click( function() {
		setStatus( 'Getting project' );
		$('.otrs').show();
		$('.retry').fadeIn();
		$('.topmatter').slideUp();
		var project = getWiki( $( '.project' ).val() );
	} );
} );

function setStatus( text, type ) {
	type = type ? type : 'info';
	$( '.' + type ).html( text );
	return text;
}

function getWiki( project ) {
	var url = 'https://toolserver.org/~krinkle/getWikiAPI/?callback=callback&format=json';
	url += '&wikiids=' + encodeURI(project);
	$.ajax( {
		type: 'GET',
		url: url,
		dataType: 'jsonp',
		jsonp: 'callback',
		success: function ( data ) {
			for ( var key in data ) break;
			if ( data[key].match ) {
				var user = $('.username').val();
				getContribs( data[key], user );
				if ( data[key].is_closed ) {
					$('.notes').html( '<strong>Warning:</strong> wiki is closed.' );
				} else {
					var html = '<a href="' + data[key].data.url + '/wiki/User:' + user + '">';
					html += user + '</a> (<a href="' + data[key].data.url + '/wiki/User talk:' + user;
					html += '">talk</a> &middot; <a href="' + data[key].data.url + '/wiki/Special:Contrib';
					html += 'utions/' + user + '">contribs</a> &middot; <a href="' + data[key].data.url;
					html += '/wiki/Special:ListFiles/' + user + '">uploads</a>) has made edits to the following';
					html += ' file description pages on ' + data[key].data.domain + ':';
					$('.notes').html( html );
				}
			} else {
				setStatus( 'No project found matching "' + data[key].search +'". Retry?' );
				$('.notes').text('Please check your spelling of the project name.');
				return;
			}
		},
		error: function ( data ) {
			setStatus( 'Invalid project. Retry?' );
			$('.notes').text('Unable to access wiki lookup.');
		}

	} );
}
function getContribs(  project, user, from ) {
	updateCounter();
	if ( count.contribs >= $('.limit').val() ) {
		setStatus( 'Aborting due to limit at ' + count.contribs + ' contributions' );
		return;
	}
	var contribsurl = project.data.apiurl + '?action=query&list=usercontribs';
	contribsurl += '&uclimit=max&ucnamespace=6&callback=callback&format=json&ucuser=' + user;
	if ( from ) {
		contribsurl += '&uccontinue=' + from['query-continue'].usercontribs.uccontinue;
	}
	$.ajax( {
		url: contribsurl,
		jsonp: 'callback',
		dataType: 'jsonp',
		success: function ( data ) {
			if ( data.error ) {
				setStatus( data.error.info );
				return;
			}
			count.contribs += data.query.usercontribs.length;
			for ( var i = 0; i < data.query.usercontribs.length; i++ ) {
				writeTable( data.query.usercontribs[i], project );
			}
			if ( data['query-continue'] ) {
				getContribs( project, user, data );
			} else {
				count.max = true;
			}
		}
	});
}

function writeTable( entry, project ) {
	if ( $( '.otrs tr[data-id="' + entry.pageid + '"]').text() )  {
		if ( typeof entry.top !== 'undefined' ) {
			$( '.otrs tr[data-id="' + entry.pageid + '"] td:first-child').append('<span class="top">(top)</span>');
		}
		count.templates++;
		return;
	}
	var html = '<tr data-id="' + entry.pageid + '"><td><a href="';
	html += project.data.url + '/wiki/' + entry.title + '"';
	if ( entry.comment ) {
		html  += ' title="' + entry.comment + '"';
	}
	html += '>' + entry.title;
	html += '</a>';

	if ( typeof entry.top !== 'undefined' ) {
		html += '<span class="top">(top)</span>';
	}

	html += '</td><td class="otrs-loading">Loading</td></tr>';
	$('.otrs').append(html);

	getTemplates( entry, project );
}


function getTemplates( entry, project ) {
	var url = project.data.apiurl + '?action=query&titles=' + entry.title;
	url += '&prop=templates&tltemplates=Template:OTRS_received|Template:';
	url += 'PermissionOTRS|template:OTRS_pending&format=json&callback=callback';
	$.ajax( {
		url: url,
		jsonp: 'callback',
		dataType: 'jsonp',
		success: function ( data ) {
			count.templates++;
			updateCounter();
			if ( data.error ) {
				setStatus( data.error.info );
				return;
			}
			for ( var key in data.query.pages ) break;
			data.query = data.query.pages[key];
			var result = null;
			if ( typeof data.query.templates === 'undefined' ) {
				result = 'none';
			} else if ( data.query.templates.length !== 1 ) {
				result = 'multiple';
			} else {
				switch ( data.query.templates[0].title ) {
					case 'Template:PermissionOTRS':
						result = 'confirmed';
						break;
					case 'Template:OTRS received':
						result = 'received';
						break;
					case 'Template:OTRS pending':
						result = 'pending';
						break;
					default:
						result = 'error';
				}
			}
			$('.otrs tr[data-id="' + entry.pageid + '"] td:last-child' ).text( result );
			$('.otrs tr[data-id="' + entry.pageid + '"] td:last-child' ).addClass( 'otrs-' + result );
			$('.otrs tr[data-id="' + entry.pageid + '"] td:last-child' ).removeClass( 'otrs-loading' );

			if ( $( '.hide' ).is( ':checked' ) ) {
				if ( result !== 'none' ) {
					$('.otrs tr[data-id="' + entry.pageid + '"]').slideDown();
				}
			} else {
				$('.otrs tr[data-id="' + entry.pageid + '"]').slideDown();
			}

		}
	} );
}

function updateCounter() {
	var str = '';
	if ( count.templates === count.contribs ) {
		if ( count.max ) {
			str = count.contribs + ' contributions processed';
		} else {
			if ( count.contribs >= $('.limit').val() ) {
				str = 'Limit hit: ' + count.contribs + ' contributions processed';
			} else {
				str = '(Waiting for API) Processing ' + count.contribs + ' contributions';
			}
		}
	} else {
		str = 'Processing ' + count.templates + '/' + count.contribs;
		if ( !count.max ) {
			str += '+';
		}
		str += ' contributions';
	}
	setStatus( str );
}
