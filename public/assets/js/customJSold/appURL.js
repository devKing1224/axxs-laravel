$(document).ready( function(){
    
    //apiURL = 'http://localhost/axxs/public/index.php/api/';
    //baseURL = 'http://localhost/axxs/public/';
    //apiURL = 'http://172.16.10.117:8000/api/';
    //baseURL = 'http://172.16.10.117/axxs/public/';
    //alert(window.location.origin);return false;
    apiURL = window.location.origin+'/index.php/api/';
    baseURL = window.location.origin+'/index.php/';
    if(sessionStorage.SidebarMenu) {  
        $('#'+sessionStorage.SidebarMenu+'AfterClickOpen').addClass('active');  
        //sessionStorage.SidebarMenu = '';
    }
    
    $('.sidebar-menu').on('click',function(){
        var menuID = this.id;
        sessionStorage.SidebarMenu = menuID;
    });
    
    
});
//$('#example1').DataTable();

/*$('#example1').dataTable({
    //destroy: true,
     //"sDom": '<"top"i>rt<"bottom"flp><"clear">',
     //"bPaginate": true,
     // "dom": '<"pull-left"s><"pull-bottom-right"l>'
    //"dom": '<"top"i>rt<"bottom"flp><"clear">',
    //"dom": 'ftipr',
   // lengthChange: false,
   // retrieve: true,
    //example1_length: false,
     // searching: false,
     // paging: false,
   // "dom":' <"search"fl><"top">rt<"bottom"ip><"clear">'
});
*/