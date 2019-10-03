/**
 *  Copied and changed from "Execute PHP" plugin
 */
function gp_init_inline_edit(area_id,section_object){
	var textarea, cache;

	loaded();
	gp_editing.editor_tools();
	var edit_div = gp_editing.get_edit_area(area_id);

	//set up textarea
	textarea = $('<textarea style="width:100%;margin:0;border:0 none;" />')
				.val(section_object.content);
	edit_div.html(textarea);

	gp_editor = {
		save_path: gp_editing.get_path(area_id),

		destroy:function(){},
		checkDirty:function(){
			if( cache == textarea.val() ){
				return false;
			}
			return true;
		},
		gp_saveData:function(){
			return 'gpcontent='+encodeURIComponent(textarea.val());
		},
		resetDirty:function(){
			cache = textarea.val();
		},
		updateElement:function(){}
	}
}
