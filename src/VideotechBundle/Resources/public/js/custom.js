
// If Create new category selected launch New Category modal
function verifyCategory(selectedObj){
  if(selectedObj.value == -2){
    $('#newCategoryModal').modal('show')
  }
}

// Cleanup Category modal on close
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



// Submits the New Category created in the type modal
function submitNewCategory(){

    // Submit data
    var posting = submitFormAndStay($('#newCategoryModalForm'));

    //On success ( 200 response code)
    posting.done(function(data){
      if(data.response == 'success'){
        // Type has been saved

        // Add new category to thr select list and selecte it
        $('#categorySelecter').append('<option value="' + data.data.id + '">' + data.data.text + '</option>');
        $('#categorySelecter').val(data.data.id);

        //Clean up the (this) modal
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

//validate film
function validateFilm(){

  var err = false;

  if($('#title').val().length < 10 || $('#title').val().length > 255) {
    $('#title').addClass("is-invalid");
    err = true;
  } else {
    $('#title').removeClass("is-invalid");
  }

  if(!$('#description').val()) {
    $('#description').addClass("is-invalid");
    err = true;
  } else {
    $('#description').removeClass("is-invalid");
  }


  if($('#categorySelecter').val() <= 0 ) {
    $('#categorySelecter').addClass("is-invalid");
    err = true;
  } else {
    $('#categorySelecter').removeClass("is-invalid");
  }



  return !err;

}

// Validate New Film info

function validateNewFilm(){

  var err = false;

  if($('#title').val().length < 10 || $('#title').val().length > 255) {
    $('#title').addClass("is-invalid");
    err = true;
  } else {
    $('#title').removeClass("is-invalid");
  }

  if(!$('#description').val()) {
    $('#description').addClass("is-invalid");
    err = true;
  } else {
    $('#description').removeClass("is-invalid");
  }

  if(!$('#image').val()) {
    $('#image').addClass("is-invalid");
    err = true;
  } else {
    $('#image').removeClass("is-invalid");
  }

  if($('#categorySelecter').val() <= 0 ) {
    $('#categorySelecter').addClass("is-invalid");
    err = true;
  } else {
    $('#categorySelecter').removeClass("is-invalid");
  }



  return !err;

}


//fetch nb films

$(document).ready(function()
{
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '/getNbFilm');
  xhr.setRequestHeader('Content-Type', 'application/json');
  xhr.onload = function() {
    if (xhr.status === 200) {
      var data = JSON.parse(xhr.responseText);
    }
    $('#totalFilm').text("Nombnre de film total : " + data.data.nbFilm);
  };

  xhr.send();

});
