function gp_init_inline_edit(area_id, section_object){

  $gp.LoadStyle(AddScript_base + '/thirdparty/codemirror/lib/codemirror.min.css', false);
  $gp.LoadStyle(AddScript_base + '/css/edit.css', false);
  gp_editing.editor_tools();

  var $textarea, cache;
  var edit_div = gp_editing.get_edit_area(area_id);

  //set up textarea
  $textarea = $('<textarea placeholder="' + AddScript_i18n['textarea_placeholder']['js'] + '"></textarea>').val(section_object.script);
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
        gp_editor.checkInput();
      }
      return 'script=' 
        + encodeURIComponent(gp_editor.codeMirror.getValue())
        + '&script_type=' + gp_editor.script_type;
    },


    resetDirty : function(){
      cache = gp_editor.gp_saveData(true);
    },


    updateElement : function(){},


    checkInput : function(){
      edit_div
        .removeClass('addscript-has-errors')
        .find('.addscript-msg').remove();

      var current_input = gp_editor.codeMirror.getValue();

      switch( gp_editor.script_type ){
        case 'raw':
          return true; // we do not check raw output. It may be anything including HTML or plain text

        case 'url':
          if( !gp_editor.url_regex.test(current_input) ){
            $msg = AddScript_i18n['warning'] + ': ' + AddScript_i18n['invalid_url'];
            gp_editor.showErrors('warning', $msg);
            return false;
          }
          break;

        case 'js':
        case 'jQuery':
          try{
            eval(current_input); // yep, it's the evil eval
          }catch(e){
            // console.log('e = ', e);
            gp_editor.showErrors('error', e);
            return false;
          }
          break;
      }

      return true; // no errors found
    },


    showErrors : function(type, msg){
      edit_div
        .addClass('addscript-has-errors')
        .append('<div class="addscript-msg addscript-msg-' + type + '">'
        +   '<a class="addscript-msg-btn" onclick="gp_editor.checkInput()">' + AddScript_i18n['check_again'] + '</a>'
        +   '<span class="addscript-msg-text">'
        +     '<i class="fa fa-exclamation-circle"></i> '
        +     msg
        +   '</span>'
        + '</div>');
    },


    codeMirror : null,


    createCodeMirror : function(){
      if( gp_editor.codeMirror ){
        gp_editor.codeMirror.toTextArea(); // destroy current
      }

      $textarea.attr('placeholder', AddScript_i18n['textarea_placeholder'][gp_editor.script_type]);

      var codeMirrorConfig = {
        lineWrapping    : true,
        showInvisibles  : true,
        viewportMargin  : Infinity
      };

      switch(gp_editor.script_type){
        case 'js':
        case 'jQuery':
          codeMirrorConfig.mode = 'text/javascript';
          break;
        case 'raw':
          codeMirrorConfig.mode = 'text/html';
          break;
      }
      gp_editor.codeMirror = CodeMirror.fromTextArea($textarea.get(0), codeMirrorConfig);
      gp_editor.checkInput();
    },


    createEditorUi : function(){
      gp_editor.ui.changeType = $('<div class="addscript-change-type">'
        + '<label><input type="radio" name="changetype" value="js" />' + AddScript_i18n['types']['js'] + '</label>'
        + '<label><input type="radio" name="changetype" value="jQuery" />' + AddScript_i18n['types']['jQuery'] + '</label>'
        + '<label><input type="radio" name="changetype" value="url" />' + AddScript_i18n['types']['url'] + '</label>'
        + '<label><input type="radio" name="changetype" value="raw" />' + AddScript_i18n['types']['raw'] + '</label>'
        + '</div>')
        .appendTo('#ckeditor_controls');

      gp_editor.ui.changeType
        .find('input[value="' + gp_editor.script_type + '"]')
          .prop('checked', true)
          .closest('label').addClass('is-checked');

      gp_editor.ui.changeType
        .find('input[type="radio"]')
        .on('change', function(){
          $(this).closest('label').addClass('is-checked')
            .siblings().removeClass('is-checked');
          gp_editor.switchMode( $(this).val() );
        });
    },


    switchMode : function(type){
      gp_editor.script_type = type;
      gp_editor.createCodeMirror( gp_editor.script_type );
    },


    // Regular Expression for URL validation
    // Author: Diego Perini, Created: 2010/12/05, Updated: 2018/09/12, 
    // License: MIT
    // Copyright (c) 2010-2018 Diego Perini (http://www.iport.it)
    // Github gist: https://gist.github.com/dperini/729294
    url_regex : new RegExp(
      "^" +
        // protocol identifier (optional)
        // short syntax // still required
        "(?:(?:(?:https?|ftp):)?\\/\\/)" +
        // user:pass BasicAuth (optional)
        "(?:\\S+(?::\\S*)?@)?" +
        "(?:" +
        // IP address exclusion
        // private & local networks
        "(?!(?:10|127)(?:\\.\\d{1,3}){3})" +
        "(?!(?:169\\.254|192\\.168)(?:\\.\\d{1,3}){2})" +
        "(?!172\\.(?:1[6-9]|2\\d|3[0-1])(?:\\.\\d{1,3}){2})" +
        // IP address dotted notation octets
        // excludes loopback network 0.0.0.0
        // excludes reserved space >= 224.0.0.0
        // excludes network & broadcast addresses
        // (first & last IP address of each class)
        "(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])" +
        "(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}" +
        "(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))" +
        "|" +
        // host & domain names, may end with dot
        // can be replaced by a shortest alternative
        // (?![-_])(?:[-\\w\\u00a1-\\uffff]{0,63}[^-_]\\.)+
        "(?:" +
          "(?:" +
          "[a-z0-9\\u00a1-\\uffff]" +
          "[a-z0-9\\u00a1-\\uffff_-]{0,62}" +
          ")?" +
          "[a-z0-9\\u00a1-\\uffff]\\." +
        ")+" +
        // TLD identifier name, may end with dot
        "(?:[a-z\\u00a1-\\uffff]{2,}\\.?)" +
        ")" +
        // port number (optional)
        "(?::\\d{2,5})?" +
        // resource path (optional)
        "(?:[/?#]\\S*)?" +
      "$", "i"
    )

  }; // end of gp_editor


  /* ############ */
  /* ### INIT ### */
  /* ############ */

  gp_editor.init();

}
