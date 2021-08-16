$(document).ready(function(){

  
 oTable = $(".mailtable").dataTable({
    "bPaginate": true,
    "bInfo": true,
    "bFilter": true,
    "pageLength": 25,
    "pagingType": "simple",
    "bLengthChange": false,
    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
        $('.pagenumber').text(iStart +" - "+ iEnd+"/"+iTotal);
      }
 });
 
 $(".input-sm").on("keyup", function() {
    oTable.fnFilter($(this).val());
});
 
$('.prev_btn').click(function(){
oTable.fnPageChange( 'previous' );
});

 $('.next_btn').click(function(){
oTable.fnPageChange( 'next' );
});

$('.dataTables_paginate').addClass('hide');
$('.dataTables_filter').addClass('hide');
 
});