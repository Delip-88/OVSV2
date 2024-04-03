$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#userData tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(3)").text().toLowerCase().indexOf(value) > -1);
        });
    });
    $("#searchInput2").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#userData tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(4)").text().toLowerCase().indexOf(value) > -1);
        });
    });
}); 
 
$(document).ready(function(){

}); 
 