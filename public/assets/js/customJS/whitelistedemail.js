$('#add_whurl').click(function(){
        $('#wh_modal').modal('show');
  });

    var base_url = window.location.origin;

    $(function() {

      $('#wh_modal').on('hidden.bs.modal', function () {
        $('#modal-title').html('Add Whitelisted URL');
        $('#wh_submit').html('Add');
        $('#whForm').attr('action', 'addwhemail');
        $('#')
        $("#provider").attr("placeholder", "Enter provider eg: Facebook ,Gmail,GED").val('');
        $("#email").attr("placeholder", "Enter Email address").val('');
        $("#wh_id").remove();
      });


        $('#wh-email').DataTable({
             language: {
            processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
            },
            processing: true,
            serverSide: true,
            ajax: base_url+'/getwhemaildata',

            columns: [
            {data: 'DT_RowIndex'},
            {data: 'provider'},
            {data: 'email'},
            {data: 'updated_at'}
        ],
        order: [[1, 'asc']]
        });

    });
    //submit form
    $("#whForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), 
               success: function(data)
               {
                   if (data.Status == 'Validation error occured') {
                        $.each(data.Message, function(key,val) {
                                    toastr.error(val);
                                });
                   } else if(data.Status == 'Success'){
                        $('#wh_modal').modal('hide');
                         toastr.success(data.Message);
                         $('#wh-email').DataTable().ajax.reload();
                   }else{
                      toastr.error('Something went wrong !');
                   }
               }
             });
        });
    //edit email
    function edit_email($id){

        $.ajax({
                url: base_url+'/getwhemaildetail/' + $id,
                type: 'GET',
                dataType: 'json', // added data type
                success: function(res) {
                    console.log(res.Data);
                    $('#whForm').append('<input type="hidden" name="id" id="wh_id" value="'+res.Data.id+'" />');
                    $('#provider').val(res.Data.provider);
                    $('#email').val(res.Data.email);
                    $('#modal-title').html('Edit Whitelisted URL');
                    $('#wh_submit').html('Update');
                    $('#whForm').attr('action', 'updatewh_email');
                    $('#wh_modal').modal('show');
                }
            });
    }
    function deletewh_email($id) {
      swal({
      title: "Are you sure?",
      text: 'You want to delete this email address !',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: "Confirm",
      cancelButtonText: "Cancel",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(inputValue){
       
      //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
      if (inputValue===false) {
        swal.close()
      } else {
        swal.close()
        $.ajax({
                    url: base_url+'/deletewh_email/'+$id,
                    type: "post",
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data){
                        if (data.status == 'success') {
                            toastr.success(data.msg);
                            $('#wh-email').DataTable().ajax.reload();
                        }else{
                            toastr.error(data.msg);
                        }

                    }
                });
            }
        });       
      };