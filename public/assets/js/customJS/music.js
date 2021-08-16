//add music function
$('#MusicAddDataSend').click(function () {
  var form = $('#serviceData');
  var name = $("input[name=song_name]").val();
  var artist = $("input[name=artist_name]").val();
  var genre = $("#genre").val();
  var icon_urlnew = $('#music_file').prop('files')[0];

  $.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
  }, 'File size must be less than {0}');

  if (icon_urlnew) {
    logo_url = icon_urlnew;
  } else {
    logo_url = '';
  }

  form.validate({
    errorElement: 'span',
    errorClass: 'form-error',
    highlight: function (element, errorClass, validClass) {

      $(element).closest('.form-group').addClass("has-error");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass("has-error");
    },
    rules: {
      song_name: 'required',
      artist_name: 'required',
      genre_name : 'required',
      music_file: {
        required: true,
        extension: "mp3",
      },
    },
    messages: {
      song_name: 'Please enter your Song Name',
      artist_name: 'Please enter Artist',
      genre_name: 'Please enter Genre',
      music_file: {
          filesize: "",
          extension: "Please upload .mp3 file extension",
      },
    }
  });

  if (form.valid() === true) {
    var form1 = new FormData();
    form1.append('song_name', name);
    form1.append('artist_name', artist);
    form1.append('genre_name', genre);
    form1.append('music_file', icon_urlnew);
    $.ajax({
      type: 'post',
      url: 'registermusic',
      data: form1,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $("#MusicAddDataSend").prop('disabled', true); 
      },
      success: function (result) {
      if (result.Code === 200) {
        setTimeout(function() {
          swal({
              title: "Added!",
              text: result.Message,
              type: "success"
          }, function() {
              window.location.href = baseURL + 'musics';
          });
        }, 2000);
      } else if (result.Code === 400) {
        swal('Error!!', result.Message, 'error');
        $("#MusicAddDataSend").prop('disabled', false);
        return false;
      }
      },
        error: function (jqXHR, exception) {
        console.log('jqXHR' + jqXHR);
        console.log('exception' + exception);
      }
    });
  }
});


//list music function
$(function() {
  var base_url = window.location.origin;
  var pathname = window.location.pathname.split("/").pop();
   if (pathname == 'inactivemusics') {
    url = 'inactivemusiclist';
  }else{
    url = 'getmusiclist';
  }
  
  $('#music-table').DataTable({
    "destroy": true,
    ajax:  {
      url: apiurl+url,
      dataSrc: 'Data'
    }, 
    columns: [
      {
        "render": function(data, type, full, meta) {
          return meta.row + 1;
        }
      },
      { data: "song" },
      { data: "artist" },
      { data: "genre" },
      {
        "data": null,
        "bSortable": false,
           "mRender": function (o) { 
            if(url == 'inactivemusiclist'){
                return '<a onClick="makeMusicActive(' +o.id+ ')" style="cursor:pointer" ><i class="glyphicon glyphicon-thumbs-up"></i></a>'; 
            }
            return '<a onClick="editMusic(' + o.id + ')" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a onClick="deleteMusic(' + o.id + ')" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</a>'; 
        },
      }
    ]
  });  
});


//editMusic list function
function editMusic($id) {
$.ajax({
    type: "GET",
    url: '/music/edit/'+$id,
    success: function(result) {
      var data = result.Data;
      $("#m_id").val(data.id);
      $("#m_name").val(data.song);
      $("#m_artist").val(data.artist);
      //$("#m_genre").val(data.genre);
      $("#m_genre option[value='"+data.genre+"']").remove();
      $('#m_genre').append('<option value="'+data.genre+'" selected>'+data.genre+'</option>');
      $("#m_url").val(data.song_url);
      $("#old_musicfile").attr("src", data.song_url);
      //$("#old_musicfile").val(data.song_file_name);
      $('#myModal').modal('show');
    }
  });
}

$("#m_file").change(function(){
readURL(this);
});
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('#m_url').html(e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

//delete music function
function deleteMusic($id) {
  swal({
    title: "Are you sure?",
    text: "Are you sure you want to delete this track?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes, delete",
    cancelButtonText: "No, cancel",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function(isConfirm) {
    if (isConfirm) {
      $.ajax({
        method: 'POST',
        url: "music/delete"+'/'+$id,
        deferRender: true,
        success: function(response){ 
          if(response.Code == 200){
              swal("Deleted!", "Your track has been deleted.", "success");
          }else if(response.Code == 400){
             swal("Error", "Your track is not deleted.", "error");
          }
          $('#music-table').DataTable().ajax.reload(); 
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
  } else {
      swal("Cancelled", "Your track is safe :)", "error");
    }
  }); 
}

//make music active music function
function makeMusicActive($id) {
  swal({
    title: "Are you sure?",
    text: "You want to activate this music!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes, activate it!",
    cancelButtonText: "No, cancel plx!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function(isConfirm) {
    if (isConfirm) {
      $.ajax({
        method: 'POST',
        url: "music/makeactive"+'/'+$id,
        success: function(response){ // What to do if we succeed
            if(response.Code == 200){
                swal("Activated!", "Your Music has been Activated.", "success");
            }else if(response.Code == 400){
               swal("Error", "Your Music is not activated.", "error");
            }
            $('#music-table').DataTable().ajax.reload(); 
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    } else {
      swal("Cancelled", "Your Music is not activated :)", "error");
    }
  }); 
}  

//edit music function
$('#MusicEditDataSend').click(function () {
  var form = $('#updateMusic');
  var id = $("#music_id").val();
  var name = $("#m_name").val();
  var artist = $("#m_artist").val();
  var genre = $("#m_genre").val();
  var song_urlnew = $('#music_file').prop('files')[0];
  var old_url = $('#old_musicfile').val();

  $.validator.addMethod('filesize', function (value, element, param) {
      return this.optional(element) || (element.files[0].size <= param)
  }, 'File size must be less than {0}');

  if (song_urlnew!='') {
      song_url = song_urlnew;
  } else {
      song_url = old_url;
  }
  form.validate({
    errorElement: 'span',
    errorClass: 'form-error',
    highlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').addClass("has-error");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass("has-error");
    },
    rules: {
        song: 'required',
        artist: 'required',
        genre : 'required',
    },
    messages: {
        song: 'Please enter your Song Name',
        artist: 'Please enter Artist',
        genre: 'Please enter Genre',
      /*  music_file: {
             //music_file: 'required',
            extension: "Please upload .mp3 file extension",
        },*/
    }
  });
}); 