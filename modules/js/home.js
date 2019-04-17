/**
 * Javascript function library for home page
 */

//function to open the side navigation
function openNav() {
    document.getElementById("mySidenav").style.width = "300px";
    document.getElementById("container").style.marginLeft = "300px";
    //document.body.style.backgroundColor = "rgba(0,0,0,0.2)";
}

//function to close side navigation
function closeNav() {
    document.getElementById("mySidenav").style.width = "65px";
    document.getElementById("container").style.marginLeft= "0";
    document.body.style.backgroundColor = "#f5f5f5";
}

$('#mySidenav').mouseover(function(){
    openNav();
});
$('#mySidenav').mouseout(function(){
    closeNav();
});
//overlayscroll
$(function() {
	//The passed argument has to be at least a empty object or a object with your desired options
    $("#mySidenav").overlayScrollbars({ });
    $("#display-container").overlayScrollbars({ });
});

