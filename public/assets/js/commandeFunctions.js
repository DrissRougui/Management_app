$(".fournisseur").change(function(e,s) {
    line = e.currentTarget.getAttribute("data-line")
    let id=$(".fournisseur[data-line="+line+"] option:selected").val();
    function updateProduits(produits) {
        
        $(".produit[data-line="+line+"]").empty()
        produits.forEach((p) => {
            
            let option = $('<option></option>').attr("value", p.id).text(p.nomProduit);
            $(".produit[data-line="+line+"]").append(option);
            
        });

        $(".produit[data-line="+line+"]").unbind('change');
        $(".produit[data-line="+line+"]").change((e,s)=>{
            idprod= $(".produit[data-line="+line+"] option:selected").val();
            console.log("id fournisseur",id,"liste des produits ",produits);
            produitFound = produits.filter(e => {
                return e.id == idprod
              })
              
              
           $(".prix[data-line="+line+"]").text(produitFound[0].prixUnitaire + "dt") //.val(produitFound.prixUnitaire)
           })
        //    console.log("id after jquery",id);
      
       
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