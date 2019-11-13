function gp_init_inline_edit(area_id, section_object){
	var textarea, cache;

	$gp.LoadStyle(AddScript_base + '/thirdparty/codemirror/lib/codemirror.min.css', false);
	$gp.LoadStyle(AddScript_base + '/css/edit.css', false);
	gp_editing.editor_tools();
	var edit_div = gp_editing.get_edit_area(area_id);

	//set up textarea
	$textarea = $('<textarea placeholder="Add your custom code here..."></textarea>').val(section_object.script);
	edit_div.html($textarea);


	gp_editor = {

		init : function(){
			gp_editor.createEditorUi();
			gp_editor.createCodeMirror(gp_editor.script_type);
			gp_editor.resetDirty();
			loaded();
		},


		ui : {},


		script_type : section_object.script_type, 


		CanAutoSave : function(){ 
			return false;  // prevent saving invalid script fragments
		},


		sleep : function(){ 
			edit_div.find('.CodeMirror').slideUp(); 
			edit_div.find('.addscript-error-msg').hide(); 
		},


		wake : function(){ 
			edit_div.find('.CodeMirror').slideDown(); 
			edit_div.find('.addscript-error-msg').show(); 
		},


		save_path : gp_editing.get_path(area_id),


		destroy : function(){},


		checkDirty : function(){
			return cache != gp_editor.gp_saveData(true);
		},


		gp_saveData : function(get_cache){
			var script = '';
			if( typeof(get_cache) != 'boolean' ){
				gp_editor.checkJs();
			}
			return 'script=' 
				+ encodeURIComponent(gp_editor.codeMirror.getValue())
				+ '&script_type=' + gp_editor.script_type;
		},



		resetDirty : function(){
			cache = gp_editor.gp_saveData(true);
		},


		updateElement : function(){},


		checkJs : function(){
			edit_div
				.removeClass('addscript-has-errors')
				.find('.addscript-error-msg')
					.remove();
			if( gp_editor.script_type == 'url' || gp_editor.script_type == 'raw' ){
				return true;
			}
			try{
				eval(gp_editor.codeMirror.getValue()); // yep, it's the evil eval
			}catch(e){
				if( e instanceof SyntaxError ){
					edit_div
						.addClass('addscript-has-errors')
						.append('<div class="addscript-error-msg">'
						+	'<a class="addscript-error-msg-btn" onclick="gp_editor.checkJs()">Check again</a>'
						+	'<span class="addscript-error-msg-text"><i class="fa fa-exclamation-circle"></i> '
						+	'Syntax Error: ' + e.message + '</span>'
						+ '</div>');
						return false;
				}else{
					return true;
				}
			}
		},


		codeMirror : null,


		createCodeMirror : function(script_type){
			if( gp_editor.codeMirror ){
				gp_editor.codeMirror.toTextArea(); // destroy current
			}
			var codeMirrorConfig = { lineWrapping : true };
			switch(script_type){
				case 'js':
				case 'jQuery':
					codeMirrorConfig.mode = 'text/javascript';
					break;
				case 'raw':
					codeMirrorConfig.mode = 'text/html';
					break;
			}
			gp_editor.codeMirror = CodeMirror.fromTextArea($textarea.get(0), codeMirrorConfig);
			gp_editor.checkJs();
		},


		createEditorUi : function(){
			gp_editor.ui.changeType = $('<div class="addscript-change-type">'
				+ '<label><input type="radio" name="changetype" value="js" />JavaScript</label>'
				+ '<label><input type="radio" name="changetype" value="jQuery" />jQuery</label>'
				+ '<label><input type="radio" name="changetype" value="url" />Script URL</label>'
				+ '<label><input type="radio" name="changetype" value="raw" />Raw Output (in place)</label>'
				+ '</div>')
				.appendTo('#ckeditor_controls');

			gp_editor.ui.changeType
				.find('input[value="' + gp_editor.script_type + '"]')
					.prop('checked', true)
					.closest('label').addClass('is-checked');

			gp_editor.ui.changeType
				.find('input[type="radio"]')
				.on('change', function(){
					gp_editor.script_type = $(this).val();
					gp_editor.createCodeMirror( gp_editor.script_type );
					$(this).closest('label').addClass('is-checked')
						.siblings().removeClass('is-checked');
				});
		}

	};


	/* ############ */
	/* ### INIT ### */
	/* ############ */

	gp_editor.init();

}
