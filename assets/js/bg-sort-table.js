jQuery(document).ready(function() {
	if (typeof bgSortableTables !== 'undefined') {
		for (var i=0; i<bgSortableTables.length; i++) {
			bgSortableTables[i] = JSON.parse(bgSortableTables[i]);
			var langUrl = bgPluginUrl+"English.json";
			if (bgSortableTables[i].lang) {
				switch (bgSortableTables[i].lang) {
					default:
					case 'en': 
						langUrl = bgPluginUrl+"English.json"; 
						break;
				}
			}
			jQuery.fn.dataTable.moment( 'MMMM D, YYYY' );
			jQuery.fn.dataTable.moment( 'MMMM Do, YYYY' );
			jQuery.fn.dataTable.moment( 'MM/DD/YYYY' );
			jQuery.fn.dataTable.moment( 'DD-MM-YYYY' );
			jQuery.fn.dataTable.moment( 'HH:mm MMM D, YY' );
			jQuery.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
			jQuery('#'+bgSortableTables[i].id).DataTable({
			"language": {
				"url": langUrl
			},
			paging: (bgSortableTables[i].pagination === '1') ? true : false,
			"pagingType": "numbers"
			});
		}
	}
});