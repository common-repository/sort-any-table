(function() {
	tinymce.PluginManager.add('bg_sort_table_tc_button', function( editor, url ) {

        editor.addButton('bg_sort_table_tc_button', {
            text: 'Add sorting',
            icon: 'icon dashicons-randomize',
			title: 'Make table sortable by this column',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Make table sortable',
					classes: 'bg-sort-table',
					width: 460,
					height: 300,
					body: [
						{
							type: 'listbox',
							name: 'bgPagination',
							label: 'Use pagination?',
							values: [
								{ text: 'Yes', value: 1 },
								{ text: 'No', value: 0 }
							]
						},
						{
							type   : 'container',
							name   : 'tooltip',
							html   : '<b style="font-weight:700">Options below are available in <a href="http://bunte-giraffe.de/sort-any-table" style="color:#008ec2; text-decoration:underline;" target="_new">PRO version (3.99 EUR)</a></b>'
						},						
						{
							type: 'textbox',
							name: 'bgPerPage',
							label: 'Rows per page:',
							value: '10'
						},
						{
							type: 'listbox',
							name: 'bgSearch',
							label: 'Show search box?',
							values: [
								{ text: 'Yes', value: 1 },
								{ text: 'No', value: 0 }
							]
						},
						{
							type: 'listbox',
							name: 'bgInfo',
							label: 'Show paging info?',
							values: [
								{ text: 'Yes', value: 1 },
								{ text: 'No', value: 0 }
							]
						},
						{
							type: 'listbox',
							name: 'bgResponsive',
							label: 'Make responsive for mobile?',
							values: [
								{ text: 'Yes', value: 1 },
								{ text: 'No', value: 0 }
							]
						},
						{
							type: 'listbox',
							name: 'bgLanguage',
							label: 'Language (more on request):',
							values: [
								{ text: 'en', value: 'en' },
								{ text: 'de', value: 'de' },
								{ text: 'ru', value: 'ru' },
								{ text: 'it', value: 'it' },
								{ text: 'fr', value: 'fr' },
							]
						}
					],
					onsubmit: function( e ) {
						var contents = editor.selection.getContent();
						var bg_shortcode_name = 'bg_sort_this_table';
						var bg_shortcode = 'pagination='+e.data.bgPagination;
						editor.insertContent('[' + bg_shortcode_name + ' ' + bg_shortcode + ']');
					}
				});
			}
		});
	});
	
})();
