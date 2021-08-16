$("body").on("click", "#movieAddDataSend", function (e) {
        // $('#serviceAddDataSend').click(function () {
        var form = $('#serviceData');
        var _token = $("input[name=_token]").val();
        var type = $("select[name=type]").val();
        var service_category_id = $("select[name=service_category_id]").val();
        var name = $("input[name=name]").val();
        var movie_url = $("textarea[name=movie_url]").val();
        var charge = $("input[name=charge]").val();
        var icon_urlnew = $('#logo_urlfile').prop('files')[0];
        var auto_logout = $("input[name=auto_logout]").val();
        var msg = $("textarea#popup_msg").val();

        

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
                name: 'required',
                movie_url: 'required',
                logo_urlfile: {
                    required: true,
                    extension: "jpg,jpeg,png",
                    filesize: 100000,
                },
                base_urlfile: {
                    extension: "pdf,docx",
                    filesize: 10000000,
                },
                charge: {
                    number: true,
                    required: true,
                },
            },
            messages: {
                name: 'Please enter your movie name',
                movie_url: 'Please enter your movie url',
                /*charge: 'Please enter your charge',*/
                logo_urlfile: {
                    filesize: " File size must be less than 100 KB",
                    extension: "Please upload .jpg or .png or .jpeg file extension",
                    required: "Please upload image"
                },
                base_urlfile: {
                    filesize: " File size must be less than 10 MB",
                    extension: "Please upload .pdf or .docx file extension",
                }
            }
        });

        if (form.valid() === true) {
            var form1 = new FormData();
            form1.append('logo_url', logo_url);
            form1.append('_token', _token);
            /*form1.append('type', type);*/
            /*form1.append('service_category_id', service_category_id);*/
            form1.append('name', name);
            form1.append('movie_url', movie_url);
            /*form1.append('base_urlfile', base_urlfile);
            form1.append('charge', charge);
            form1.append('auto_logout', auto_logout);
            form1.append('msg', msg);*/
            $.ajax({
                type: 'post',
                url: 'registermovie',
                data: form1,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 201) {
                        
                        window.location.href = baseURL + 'movies';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
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