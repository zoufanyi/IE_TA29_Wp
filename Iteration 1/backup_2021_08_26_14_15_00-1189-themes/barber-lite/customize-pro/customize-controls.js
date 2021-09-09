( function( api ) {
	// Extends our custom "barber-lite" section.
	api.sectionConstructor['barber-lite'] = api.Section.extend( {
		// No events for this type of section.
		attachEvents: function () {},
		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );
} )( wp.customize );