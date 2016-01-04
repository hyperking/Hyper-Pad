/******************************************
EQUAL HEIGHT MODULE 
*****/




/******************************************
 NAVIGATION TOGGLE 
*****/
var showLeftPush = document.getElementById( 'showLeftPush' ),
        showRightPush = document.getElementById( 'showRightPush' ),
        body = document.body,
        openMenu = document.getElementById('menu');

      // openMenu.onmouseenter = function(){
      //   classie.addClass(body,'side-nav-open')
      // };
      // openMenu.onmouseleave = function(){
      //   classie.removeClass(body,'side-nav-open')
      // };
      showLeftPush.onclick = function() {
        classie.toggle( body, 'side-nav-open' );
      };
      showRightPush.onclick = function() {
        // classie.toggle( body, 'side-nav-right-open');
        paypal.minicart.view.toggle();   
      };



