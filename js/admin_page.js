
var AddScript_Admin = {

  ui              : {},
  code_type       : false,
  codeMirror      : false,
  strip_pattern   : false,

  init            : function(){
    AddScript_Admin.ui = {
      code_before     : $('div.addscript-code-before'),
      code_after      : $('div.addscript-code-after'),
      type_select     : $('select[name="script_type"]'),
      textarea        : $('textarea[name="script_code"]'),
      async_checkbox  : $('input[name="script_attr_async"]'),
      defer_checkbox  : $('input[name="script_attr_defer"]'),
      msg_area        : $('div.addscript-msg-area')
    };

    AddScript_Admin.ui.type_select.on('change', function(){
      AddScript_Admin.code_type = $(this).val();
      var disabled = AddScript_Admin.code_type != 'url';
      AddScript_Admin.ui.async_checkbox.prop('disabled', disabled);
      AddScript_Admin.ui.defer_checkbox.prop('disabled', disabled);
      AddScript_Admin.createCodeMirror();
    });

    AddScript_Admin.ui.async_checkbox.on('change', AddScript_Admin.setScriptAttributes );
    AddScript_Admin.ui.defer_checkbox.on('change', AddScript_Admin.setScriptAttributes );

    AddScript_Admin.code_type = AddScript_Admin.ui.type_select.val();

    AddScript_Admin.createCodeMirror();
  },


  setScriptAttributes    : function(){
    var async = AddScript_Admin.ui.async_checkbox.prop('checked') ? ' async="async"' : '';
    AddScript_Admin.ui.code_before.find('span.addscript-attr-async').text(async);
    var defer = AddScript_Admin.ui.defer_checkbox.prop('checked') ? ' defer="defer"' : '';
    AddScript_Admin.ui.code_before.find('span.addscript-attr-defer').text(defer);
  },


  setTextAreaPlaceholder : function(){
    var placeholder = AddScript_i18n['textarea_placeholder'][AddScript_Admin.code_type];
    AddScript_Admin.ui.textarea.attr('placeholder', placeholder);
  },


  createCodeMirror : function(){

    if( AddScript_Admin.codeMirror ){
      AddScript_Admin.codeMirror.toTextArea(); // destroy current
    }

    AddScript_Admin.setTextAreaPlaceholder();
  
    var codeMirrorConfig = {
      lineWrapping    : true,
      showInvisibles  : true,
      viewportMargin  : Infinity
    };

    // console.log('AddScript_Admin.code_type = ' + AddScript_Admin.code_type);
    switch( AddScript_Admin.code_type ){
      case 'js':
        AddScript_Admin.ui.code_before.html('<span>&lt;script&gt;</span>');
        AddScript_Admin.ui.code_after.html('<span>&lt;/script&gt;</span>');
        codeMirrorConfig.mode = 'text/javascript';
        AddScript_Admin.strip_pattern = /<\/?script>/ig;
        break;

      case 'url':
        AddScript_Admin.ui.code_before.html(
            '<span>&lt;script<span class="addscript-attr-async"></span>'
          + '<span class="addscript-attr-defer"></span> src=&quot;</span>'
        );
        AddScript_Admin.ui.code_after.html('<span>&quot;&gt;&lt;/script&gt;</span>');
        AddScript_Admin.setScriptAttributes();
        codeMirrorConfig.mode = 'text';
        AddScript_Admin.strip_pattern = /(<script.+src=["'])|(["'].+\/script>)/ig;
        break;
    }

    // console.log('createCodeMirror with config ', codeMirrorConfig);

    AddScript_Admin.codeMirror = CodeMirror
      .fromTextArea(AddScript_Admin.ui.textarea.get(0), codeMirrorConfig);
    AddScript_Admin.codeMirror.on('change', AddScript_Admin.checkInput);

    AddScript_Admin.checkInput();
  },


  checkInput    : function(){
    // console.log('CodeMirror.change event fired with arguments ', arguments);
    var code = AddScript_Admin.codeMirror.getValue();
    var strip_match = code.match(AddScript_Admin.strip_pattern);
    // console.log('code = ' + code + ' | strip_match = ', strip_match);
    if( strip_match ){
      code = code.replace(AddScript_Admin.strip_pattern, '');
      AddScript_Admin.codeMirror.setValue(code);
    }

    AddScript_Admin.ui.msg_area.find('.addscript-msg').remove();

    switch( AddScript_Admin.code_type ){
      case 'js':
        try{
          eval(code);
        }catch(e){
          AddScript_Admin.showErrors('error', e);
          return false;
        }
        break;

      case 'url':
        if( !AddScript_Admin.url_regex.test(code) ){
          var msg = AddScript_i18n['warning'] + ': ' + AddScript_i18n['invalid_url'];
          AddScript_Admin.showErrors('warning', msg);
          return false;
        }
        break;
    }

    return true; // no errors found
  },


  showErrors    : function(type, msg){
    AddScript_Admin.ui.msg_area
      .append('<div class="addscript-msg addscript-msg-' + type + '">'
      // +   '<a class="addscript-msg-btn" onclick="AddScript_Admin.checkInput()">'
      // +     AddScript_i18n['check_again'] + '</a>'
      +   '<span class="addscript-msg-text">'
      +     '<i class="fa fa-exclamation-circle"></i> '
      +     msg
      +   '</span>'
      + '</div>');
  },


  url_regex : new RegExp(
    // Regular Expression for URL validation
    // Author: Diego Perini, Created: 2010/12/05, Updated: 2018/09/12, 
    // License: MIT
    // Copyright (c) 2010-2018 Diego Perini (http://www.iport.it)
    // Github gist: https://gist.github.com/dperini/729294
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

};


/**
 * init
 */
$(function(){
  AddScript_Admin.init();
});
