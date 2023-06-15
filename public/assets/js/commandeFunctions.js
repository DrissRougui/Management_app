$(".fournisseur ").change(function(e,s) {
    line = e.currentTarget.getAttribute("data-line")
    let id=$(".fournisseur option:selected").val();
    console.log(line); 
    function updateProduits(produits) {
        console.log(produits);
        $(".produit[data-line="+line+"]").empty()
        produits.forEach((p) => {
            
            var option = $('<option></option>').attr("value", p.id).text(p.nomProduit);
            $(".produit[data-line="+line+"]").append(option);
            
        });
        
       
      }
    $.ajax({
        type: "POST",
        url: "http://localhost:8000/commande/load",
        data: {id:id},
        success: function (response) {
        updateProduits(response);
        }
        });
})


$(".quantity").each(function(e,s) {
    line=s.getAttribute("data-line");
    //console.log(line);


})