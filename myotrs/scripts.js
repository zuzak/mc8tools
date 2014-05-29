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
	$( '.' + type ).text( text );
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
			getContribs( 0, data[key], $( '.username' ).val() );
			if ( data[key].is_closed ) {
				$('.notes').html( '<strong>Warning:</strong> wiki is closed.' );
			}
		},
		error: function ( data ) {
			setStatus( 'Invalid project. Retry?' );
		}

	} );
}
function getContribs( count, project, user, from ) {
	setStatus( count + ' contributions found' );
	if ( count >= $('.limit').val() ) {
		setStatus( 'Aborting due to limit at ' + count + ' contributions' );
		return;
	}
	var contribsurl = project.data.apiurl + '?action=query&list=usercontribs';
	contribsurl += '&ucnamespace=6&callback=callback&format=json&ucuser=' + user;
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
			count += data.query.usercontribs.length;
			for ( var i = 0; i < data.query.usercontribs.length; i++ ) {
				writeTable( data.query.usercontribs[i], project );
			}
			if ( data['query-continue'] ) {
				getContribs( count, project, user, data );
			} else {
				setStatus( 'Done for ' + user + '@' + project.data.wikicode );
			}
		}
	});
}

function writeTable( entry, project ) {
	console.log('gettemp',entry);
	if( !$('.otrs tr[data-id="' + entry.pageid + '"]' ).text() ) {
		var html = '<tr data-id="' + entry.pageid + '"><td><a href="';
		html += project.data.url + '/wiki/' + entry.title + '">' + entry.title;
		html += '</a></td><td class="otrs-loading">Loading</td></tr>';
		$('.otrs').append(html);

		getTemplates( entry, project );

	}
}

function getTemplates( entry, project ) {
	var url = project.data.apiurl + '?action=query&titles=' + entry.title;
	url += '&prop=templates&tltemplates=Template:OTRS_received|Template:';
	url += 'PermissionOTRS|Template:OTRS_pending&format=json&callback=callback';
	$.ajax( {
		url: url,
		jsonp: 'callback',
		dataType: 'jsonp',
		success: function ( data ) {
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

			if ( result !== 'none' ) {
				$('.otrs tr[data-id="' + entry.pageid + '"]').slideDown();
			}
		}
	} );
}

