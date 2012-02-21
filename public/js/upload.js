$(document).ready(function() {
      var button = $('#uploadButton'), interval;

      $.ajax_upload(button, {
            action : '/upload/portfolio/' + $("#pic_dir").val() + "/",
            name : 'file',
            onSubmit : function(file, ext) {
              $("img#load").show();
              this.disable();
            },
            onComplete : function(file, response) {
                $("img#load").hide();
                this.enable();
                if (response.RESULT)
                {

                }
            }
      });
});