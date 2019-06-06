<!-- <script src="/dashboard/js/tinymce/all.min.js"></script> -->
<script src="/dashboard/js/tinymce/tinymce.min.js"></script>
<script>
	tinymce.PluginManager.load('moxiecut', '/dashboard/js/tinymce/plugins/moxiecut/plugin.min.js');

	tinymce.init({
	
	menubar : false,
	height: 300,
	statusbar : false,
	resize: "vertical",

	extended_valid_elements : "article[itemprop|itemscope|itemtype|class],div[itemprop|itemscope|itemtype|style|class],h1[itemprop|style|class],h2[itemprop|style|class],h3[itemprop|style|class],h4[itemprop|style|class],p[itemprop|itemscope|itemtype|style|class],a[itemprop|href|style|class|target|title]",

	language : 'ru',
		selector: "textarea.editor",

		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste moxiecut textcolor colorpicker"
		],
		toolbar1: " undo redo | styleselect fontsizeselect | bold italic underline strikethrough forecolor | forecolor backcolor | superscript subscript",
		toolbar2: "link insertfile image media | alignleft aligncenter alignright alignjustify bullist numlist outdent indent | table hr charmap removeformat | preview code",
		
		tools: "inserttable",
		style_formats: [

                {title: 'Заголовок', items: [
					{title: 'Заголовок 1', block: 'h1'},
					{title: 'Заголовок 2', block: 'h2'},
					{title: 'Заголовок 3', block: 'h3'},
					{title: 'Заголовок 4', block: 'h4'},
					{title: 'Заголовок 5', block: 'h5'},
					{title: 'Заголовок 6', block: 'h6'}
                ]},

                {title: 'Шрифт', items: [
					{title: 'Open Sans', inline: 'span', styles: { 'font-family':'Open Sans'}},
					{title: 'Arial', inline: 'span', styles: { 'font-family':'arial'}},
					{title: 'Book Antiqua', inline: 'span', styles: { 'font-family':'book antiqua'}},
					{title: 'Comic Sans MS', inline: 'span', styles: { 'font-family':'comic sans ms,sans-serif'}},
					{title: 'Courier New', inline: 'span', styles: { 'font-family':'courier new,courier'}},
					{title: 'Georgia', inline: 'span', styles: { 'font-family':'georgia,palatino'}},
					{title: 'Helvetica', inline: 'span', styles: { 'font-family':'helvetica'}},
					{title: 'Impact', inline: 'span', styles: { 'font-family':'impact,chicago'}},
					{title: 'Symbol', inline: 'span', styles: { 'font-family':'symbol'}},
					{title: 'Tahoma', inline: 'span', styles: { 'font-family':'tahoma'}},
					{title: 'Terminal', inline: 'span', styles: { 'font-family':'terminal,monaco'}},
					{title: 'Times New Roman', inline: 'span', styles: { 'font-family':'times new roman,times'}},
					{title: 'Verdana', inline: 'span', styles: { 'font-family':'Verdana'}}
                ]},
				
        ],
		
		autosave_ask_before_unload: false,
		image_advtab: true,
		relative_urls: false

	});
</script>