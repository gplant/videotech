
// If Create new question selected launch New Question modal
function verifyCategory(selectedObj){
  if(selectedObj.value == -2){
    $('#newCategoryModal').modal('show')
  }
}


function clearNewCategoryValues(){
  $("#categoryName").val("");
}


// Close New category modal
function closeNewCategory(){
  clearNewCategoryValues();
  $('#categorySelecter').val('-1').prop('selected', true);
  $('.has-error').removeClass('has-error');
}


// Submit a form but don't leav the current page.
// Returns an Ajax element (jqXHR) that contains the respons data
function submitFormAndStay(formToSend){
  return $.post(formToSend.attr( "action" ), formToSend.serialize());
}



// Submits the New Type created in the type modal
function submitNewCategory(){

    // Submit data
    var posting = submitFormAndStay($('#newCategoryModalForm'));

    //On success ( 200 response code)
    posting.done(function(data){
      if(data.response == 'success'){
        // Type has been saved

        // Add new typeQuestion to thr select list and selecte it
        $('#categorySelecter').append('<option value="' + data.data.id + '">' + data.data.text + '</option>');
        $('#categorySelecter').val(data.data.id);

        //Clean up the type (this) modal
        clearNewCategoryValues();
        //close it !
        $('#newCategoryModal').modal('hide');
        
      }else {
        alert("Error !!!\n"+data.message);
      }
    });
    //On fail (other than 200 response code, exemple 404 Page not found, timeout, 500 Internal server error ...
    posting.fail(function(){
      alert("Request Failed ! Server hasn't sent an expected response");
    });
      
  
}

