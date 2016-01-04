$(function(){
    $( '#menu_button' ).doubleTapToGo();
    FastClick.attach(document.body);
    body = document.body    


    // set variable for all image containers
    // we use map() method to select each element and extract the data values
    imageContainer = $('#page_header,.entry');
    $.map($(imageContainer), function(el){
        //store data value in a variable for cleanliness
        myimage = $(el).data('image');
        // apply image value to background of element
        $(el).css({'background-image':'url('+myimage+')'})
    })

    //Disable Overlay when page loads
    $('.overlay').css('display','none')
    $('#menu_button').on('click',function(evt){
        evt.preventDefault();
        $(body).toggleClass('open_menu');
        $('.overlay').fadeToggle();
        $('.media_loader').css('display','none')
    });


var submitbtn = $('input[type="submit"]').on('click',function(){
    submitbtn = $(this).val();
});
function submitForm(){
            function getInputs() {
            var postData = {}; 
                  $("._formdata").each(function(index, elem) 
                  {
                      postData[ $(this).attr('name') ] = $(this).val();
                  });
                        postData.usersubmit = submitbtn;
                  return postData;
            }//end getInputs    

            var userinputs = getInputs();  

      console.log(submitbtn);
      $.ajax({
          url: '_app/actions.php',
          type: "POST",
          data: userinputs,  
          success: function(data,status){
                $('#results').html(data);
                // if ( status === 'success') {window.location.replace("/profile");};
          },
          error: function(){
                console.log('oops!');
          }
        });//end ajax
      return false;
}
$('input[type="submit"]').on('click',function(){
    submitForm();
});





    


})
