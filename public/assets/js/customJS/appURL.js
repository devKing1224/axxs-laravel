$(document).ready(function () {

    //apiURL = 'http://localhost/axxs/public/index.php/api/';
    //baseURL = 'http://localhost/axxs/public/';
    //apiURL = 'http://172.16.10.117:8000/api/';
    //baseURL = 'http://172.16.10.117/axxs/public/';

    apiURL = window.location.origin + '/index.php/api/';
    baseURL = window.location.origin + '/index.php/';
    if (sessionStorage.SidebarMenu) {
        $('#' + sessionStorage.SidebarMenu + 'AfterClickOpen').addClass('active');
        //sessionStorage.SidebarMenu = '';
    }

    $('.sidebar-menu').on('click', function () {
        var menuID = this.id;
        sessionStorage.SidebarMenu = menuID;
    });


});
