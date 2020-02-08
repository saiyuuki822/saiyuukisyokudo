$(function() {
  $('#settings_tabs .menu').click(function() {
    var current = $('.active .menu').data('form');
    $('#' + current + '-form .panel').hide();
    $('.active').removeClass('active');
    var target = $(this).data('form');
    $('#' + target + '-form .panel').show();
    $(this).parent().addClass('active');
  });
  $(document).on('change', '.type', function() {
    var idx = $(this).parents('.page_content').data('idx');
    var wrap = $(this).parents('.page_content');
    wrap.find('.table').hide().find('input, textarea, select').prop('disabled', true);
    wrap.find('.only_sentence').hide().find('input, textarea, select').prop('disabled', true);
    wrap.find('.left_image_caption').hide().find('input, textarea, select').prop('disabled', true);
    wrap.find('.right_image_caption').hide().find('input, textarea, select').prop('disabled', true);
    $(this).parents('.page_content').find('.' + $(this).val()).show().find('input, textarea, select').prop('disabled', false);
    $(this).parents('.page_content').find('.' + $(this).val()).find('.title').attr('name', 'title'+idx);
    $(this).parents('.page_content').find('.' + $(this).val()).find('.type').attr('name', 'type'+idx);
    $(this).parents('.page_content').find('.' + $(this).val()).find('.sentence').attr('name', 'sentence'+idx);
    $(this).parents('.page_content').find('.' + $(this).val()).find('.image').attr('name', 'image'+idx);
    if ($(this).val() == 'table') {
      var idx = wrap.data('idx');
      wrap.find('.table .table_title').attr('name', wrap.find('.table .table_title').data('name') + idx + "_1");
      wrap.find('.table .table_title').attr('data-name', wrap.find('.table .table_title').data('name') + idx);
      wrap.find('.table .table_sentence').attr('name', wrap.find('.table .table_sentence').data('name') + idx + "_1");
      wrap.find('.table .table_sentence').attr('data-name', wrap.find('.table .table_sentence').data('name') + idx);
    }
  });
  $(document).on('click', '.delete_page_content', function() {
    var wrap = $(this).parents('.page_content');
    var add_hidden = $(this).closest('#page_content_form').find('.delete_sort:first').prop('outerHTML');
    $(this).closest('#page_content_form').find('.delete_sort:last').after(add_hidden);
    $(this).closest('#page_content_form').find('.delete_sort:last').val(wrap.data('idx'));
    wrap.remove();
    $(this).closest('#page_content_form').find('.page_content').each(function(key, value) {
      $(this).attr('data-idx', key+1);
      $(this).find('.title').attr('name', 'title'+key+1);
      $(this).find('.type').attr('name', 'type'+key+1);
      $(this).find('.image').attr('name', 'image'+key+1);
      $(this).find('.sentence').attr('name', 'sentence'+key+1);
    }); 
    return false;
  });
  
  $(document).on('click', '.delete_table_row', function() {
     var wrap = $(this).parents('.page_content');
     var idx = $(this).parents('tr').find('.num').html();
     $(this).parents('tr').remove();
     wrap.find('.table table tbody .add').each(function(index, element) {
      if(index != 0) {
        $(this).find('.num').html(index + 1);
        var title = $(this).find('.table_title').data('name');
        $(this).find('.table_title').attr('name', title+"_"+(index+1));
        var name = $(this).find('.table_sentence').data('name');
        $(this).find('.table_sentence').attr('name', name+"_"+(index+1));
      }
    });    
    return false;
  });
  
$(document).on('click', '.add_other', function() {
  var wrap = $(this).parents('.page_content');
  var add_element = $('#page_other_template').prop('outerHTML');
  wrap.after(add_element);
  wrap.parent().find('.page_content:last').show();
  var idx = wrap.find('.page_content').length + 1;
  wrap.parent().find('.page_content:last').data('idx', idx);
  wrap.parent().find('.page_content:last').find('.title').attr('name', 'title'+idx);
  wrap.parent().find('.page_content:last').find('.table_title').attr('name', 'table_title'+idx+"_1");
  wrap.parent().find('.page_content:last').find('.table_sentence').attr('name', 'table_sentence'+idx+"_1");
  wrap.parent().find('.page_content:last').find('.table_title').attr('data-name', 'table_title'+idx);
  wrap.parent().find('.page_content:last').find('.table_sentence').attr('data-name', 'table_sentence'+idx);
  wrap.parent().find('.page_content:last').find('.title').attr('name', 'title'+idx);
  return false;
  /*
    var wrap = $(this).parents('.page_content');
    var add_hidden = $(this).closest('#page_content_form').find('.delete_sort:first').prop('outerHTML');
    $(this).closest('#page_content_form').find('.delete_sort:last').after(add_hidden);
    $(this).closest('#page_content_form').find('.delete_sort:last').val(wrap.data('idx'));
    wrap.remove();
    $(this).closest('#page_content_form').find('.page_content').each(function(key, value) {
      $(this).attr('data-idx', key+1);
      $(this).find('.title').attr('name', 'title'+key+1);
      $(this).find('.type').attr('name', 'type'+key+1);
      $(this).find('.image').attr('name', 'image'+key+1);
      $(this).find('.sentence').attr('name', 'sentence'+key+1);
    }); ter
    return false;
    */
  });
  $(document).on('touchstart', '[name="type"]', function() {
    $('.table').hide();
    $('.only_sentence').hide();
    $('.left_image_caption').hide();
    $('.right_image_caption').hide();
    $(this).parents('.page_content').find('.' + $(this).val()).show();
  });
  $(document).on('click', '.add_table_row', function() {
    var wrap = $(this).parents('.page_content');
    var num = wrap.find('.table table tbody .add').length;
    wrap.find('.table table tbody').append(wrap.find('.table table tbody .add').prop('outerHTML'));
    wrap.find('.table table tbody .add:last .table_title').val('');
    wrap.find('.table table tbody .add:last .table_sentence').val('');
    $('.table table tbody .add:last .table_title').val('');
    $('.table table tbody .add:last .table_sentence').html('');
    wrap.find('.table table tbody .add').each(function(index, element) {
      if(index != 0) {
        $(this).find('.num').html(index + 1);
        var title = $(this).find('.table_title').data('name');
        $(this).find('.table_title').attr('name', title+"_"+(index+1));
        var name = $(this).find('.table_sentence').data('name');
        $(this).find('.table_sentence').attr('name', name+"_"+(index+1));
      }
    });
    //$('.table table tbody tr:last').removeClass('add');
    return false;
  });
  $(document).on('click', '.add_content', function() {
    var idx = $(this).closest('#page_content_form').find('.page_content:last').data('idx') + 1;
    $('#page_content_template .title').attr('name', 'title'+idx);
    $('#page_content_template .type').attr('name', 'type'+idx);
    var add_element = $('#page_content_template').prop('outerHTML');
    $(this).hide();
    var wrap = $(this).parents('.page_content');
    $('#page_content_form .media-list').append(add_element);
    var title = wrap.children('.title');
    //alert($(this).parent().parent().find('.title').attr('class', 'aaaaaaaaaaaaaa'));
    //$(this).parent().parent().find('.title').removeAttr('name');
    //$(this).parent().parent().find('.title').attr('name', 'aaa');
    //$(this).parent().parent().find('.title').attr('name', 'title'+idx);
    var type = wrap.find('.type');
    //type.attr('name', type.data('name') + idx);
    $('#page_content_form .media-list #page_content_template').removeClass('hide');
    $('#page_content_form .media-list #page_content_template').attr('id', '');
    //var sentence = wrap.find('.sentence').each(function(index,value) {
      //$(this).attr('name', "sentence"+idx);
    //});

    //var image = wrap.find('.image');
    //image.attr('name', image.data('name') + idx);
    //var sentence = wrap.find('.sentence');
    //sentence.attr('name', sentence.data('name') + idx);
    $(this).parents('#page_content_form').find('.page_content:last').attr('data-idx', idx);
    $(this).parents('#page_content_form').attr('data-hoge', 'aaaa');
    return false;
  })
});