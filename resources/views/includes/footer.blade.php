<!-- REQUIRED JS SCRIPTS -->
<footer class="main-footer">
    <!-- To the right -->
    <!--<div class="pull-right hidden-xs">
        Anything you want
    </div>-->
    <!-- Default to the left -->
    <strong>Copyright © 2017 <a href="#"> TheAxxsTablet</a>.</strong> All rights reserved.
</footer>




<!-- jQuery 2.1.3 -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.2.3.min.js") }}"></script>

<script src="{{ asset('/assets/js/jquery-ui.js') }}" type="text/javascript"></script>


<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
   
<!-- AdminLTE App -->
<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>


<!-- Validate of jquery -->
<script src="{{ asset ("assets/js/jquery.validate.min.js") }}" type="text/javascript"></script>

<!-- Sweat alert javascript -->
<script src="{{ asset ("assets/js/sweatalert/sweetalert.min.js") }}" type="text/javascript"></script>



<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script>
$(function () {
   // $("#example1").DataTable();
    $('#example1').dataTable( {
        "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
        "<'row'<'col-sm-12 table-responsive'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>"
    });
   
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
});
</script>



</div>
<!-- jQuery 2.2.3 -->
<!-- Bootstrap 3.3.6 -->
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/buttons.html5.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jszip.min.js") }}"></script>

<!-- SlimScroll -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>

<!-- FastClick -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/fastclick/fastclick.js") }}"></script>

<!-- AdminLTE App -->
<!--<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}"></script>-->

<!-- AdminLTE for demo purposes -->
<script src="{{ asset ("/bower_components/admin-lte/dist/js/demo.js") }}"></script>
<!-- page script -->
<script src="{{ asset('/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}" type="text/javascript"></script>
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
<script src="<?php echo asset('/'); ?>assets/js/customJS/appURL.js" type="text/javascript"></script>