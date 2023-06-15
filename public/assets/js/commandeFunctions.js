//Fournisseur on change function
$(".fournisseur").change(function(e,s) {
    line = e.currentTarget.getAttribute("data-line")
    id=$(".fournisseur[data-line="+line+"] option:selected").val();
    idprod=""
    produitFound={}
    $(".prix[data-line="+line+"]").text("")
    //CallBack from ajax 
    function updateProduits(produits) {
        
        $(".produit[data-line="+line+"]").empty()
        produits.forEach((p) => {
            
            let option = $('<option></option>').attr("value", p.id).text(p.nomProduit);
            $(".produit[data-line="+line+"]").append(option);
            
        });

        $(".produit[data-line="+line+"]").unbind('change');

        //Produit on change function
        $(".produit[data-line="+line+"]").change((e,s)=>{
            $(".prix[data-line="+line+"]").text("")
            idprod= $(".produit[data-line="+line+"] option:selected").val();
           
            produitFound = produits.filter(e => {
                return e.id == idprod
              })[0]
              
              
            $(".prix[data-line="+line+"]").text(produitFound.prixUnitaire + "dt")
            
            

           })
        




        //Quantity on change function
        $(".quantity[data-line="+line+"]").change((e,s)=>{

            amount=parseInt($(".quantity[data-line="+line+"]").val())
            total=amount*produitFound.prixUnitaire
            $(".total[data-line="+line+"]").text(total+"dt")
            
        })

        $(".produit[data-line="+line+"]").val([])
        $(".quantity[data-line="+line+"]").val("")
        $(".total[data-line="+line+"]").text("")
       
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