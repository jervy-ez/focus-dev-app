function handleMouseUp(e, sbar){
  $(sbar).unbind('mousemove');       
}

function handleMouseDown(e, sbar, target){
  if (e.button == 0){       
    $(sbar).bind('mousemove', function(event){
      handleMouseMove(event, sbar, target);
    });
  }
}

function handleMouseMove(e, sbar, target){
  var init_x = $('.'+target).css('left');
  var h_width = e.pageX - parseInt(init_x);
  $('.'+target).css('width',h_width);
  $('.'+target).css('display','block');
  $('.'+target).css('opacity','0.5');
  $('.'+target).css('position','absolute');
  $('.'+target).css('background','yellow');

}

//document.domain = "yourdomain.com";

$(document).ready(function(){


  $("#highlight_line").draggable();

  $('.display_pdf_editor').click(function(){
    var contents  = $("#job_book_area").html();
    $('.editor_body').html(contents);
  });

  var highlight_count = 0;
  var text_count = 0;
  var lastSeenXmouse = 0;

  $('.highlight').click(function(en){

    $('html,body').css('cursor','text');

    var can_add = 1;
    var can_hold = 1;

    var offset = $(this).offset();
    var x_coor = (en.pageX - offset.left);
    var y_coor = (en.pageY - offset.top);
    en.preventDefault;

    $('.canvas_area').mousedown(function(e){

      if(can_add == 1){

        var offset = $(this).offset();
        var x_coor = (e.pageX - offset.left);
        var y_coor = (e.pageY - offset.top) - 17;

        highlight_count++;
        $(".canvas_area").prepend('<div id="highlight_line" class="cou_nter_'+highlight_count+' draggable" style="width: 2px;  left: '+x_coor+'px; top: '+y_coor+'px;"></div>');
        handleMouseDown(e, this,'cou_nter_'+highlight_count );
        $("#highlight_line").draggable();
        can_add = 0;
      }
    });

    $(".canvas_area").mouseup(function(event){
      if(can_hold == 1){
        handleMouseUp(event, this);
        can_hold = 0;
        $('html,body').css('cursor','default');
      }
      //event.stopPropagation();
    });
  }); 



  $('.remove_elm_pdf').click(function(e){
    e.preventDefault;
    $('html,body').css('cursor','pointer');
    $('.draggable').click(function(){
      $(this).remove();
      $('html,body').css('cursor','default');
    });
  }); 


  $('.add_text').click(function(e){
    e.preventDefault;
    var can_add = 1;
    $('html,body').css('cursor','copy');

    $('.canvas_area').click(function(e){
      if(can_add == 1){
        var offset = $(this).offset();
        text_count++;
        var x_coor = (e.pageX - offset.left);
        var y_coor = (e.pageY - offset.top) - 17;
        $(".canvas_area").prepend('<div id="draggable" class="ui-widget-content draggable" style="left: '+x_coor+'px; top: '+y_coor+'px; display: block; position: absolute;"><textarea class="counter_'+text_count+'"></textarea></div>');
        $(".draggable").draggable();
        $("textarea.counter_"+text_count).focus();
        can_add = 0;
        $('html,body').css('cursor','default');
      }
    });

  });



  $('.produce_pdf').click(function(e){

    $( "#draggable textarea").each(function(){
      var text_custom = $(this).val();
      $(this).after(text_custom);
      $(this).remove();
    });

    $('.canvas_area').each( function(){ 
      $(this).html($(this).html().replace(/style="/g,'pdf_mrkp_styl="'));
    });


    var canvas_area = $('.canvas_area').html();

    $('textarea#content').val(canvas_area);
    $(".set_invoice_modal_submit", parent.document.body).trigger('click');


  }); 


  $('.hilight_format').click(function(e){
    e.preventDefault;
    var highlight_text = window.getSelection();

    $(".editor_body").highlight(highlight_text);

  /*
  var spn = '<span class="pdf_highlight_doc_mod">' + highlight + '</span>';
  var text = $('.editor_body').html();
  $('.editor_body').html(text.replace(highlight, spn));*/
});

});