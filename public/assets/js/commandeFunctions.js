$(".fournisseur ").change(function(e) {
    
    let id=$(".fournisseur option:selected").val();
    console.log(id); 
    $.ajax({
        type: "POST",
        url: "http://localhost:8000/commande/load",
        data: {id:id},
        success: function (response) {
        console.log(response);
        }
        });
})


$(".quantity").each(function(e,s) {
    line=s.getAttribute("data-line");
    console.log(line);


})