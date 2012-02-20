jQuery(document).ready(function($)
{
    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();

    $('#title').change(function() {
        delay(function(){
          generateUrl( $('#title').val() );
        }, 1000 );
    });
});

function generateUrl(value)
{
    $.ajax({
       type: "POST",
        url: "/ajax/generateurl/",
        data: "from=" + value,
        dataType: "json",
        success: function(data){
            if (data.RESULT)
            {
                $("#url").val(data.url);
            }
        }
    });
}