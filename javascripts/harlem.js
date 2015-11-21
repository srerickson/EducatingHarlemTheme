if (!Harlem) {
  var Harlem = {};
}

(function ($) {
  Harlem.menuDropDown = function(){
    var dropdownMenu = $('#mobile-nav');
    dropdownMenu.prepend('<a href="#" class="menu">Menu</a>');
    //Hide the rest of the menu
    $('#mobile-nav .navigation').hide();

    //function the will toggle the menu
    $('.menu').click(function() {
      $("#mobile-nav .navigation").slideToggle();
    });
  };


  Harlem.fileListToggle = function(){
    $("#item-files h3").click(function(e){
      $(this).siblings("ul").toggle();
    });
  }

  Harlem.initMediaElements = function(){
    $("audio").mediaelementplayer({
      success: function(mediaElement, originalNode) {
        console.log("success!")
      }
    });
  }

})(jQuery)