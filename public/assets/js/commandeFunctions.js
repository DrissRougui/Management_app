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
              
              
            $(".prix[data-line="+line+"]").text(produitFound.prixUnitaire)
            
            

           })
        




        //Quantity on change function
        $(".quantity[data-line="+line+"]").change((e,s)=>{

            amount=parseInt($(".quantity[data-line="+line+"]").val())
            total=amount*produitFound.prixUnitaire
            $(".total[data-line="+line+"]").text(total)
            
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




$("#calculer").click(()=>{
    somme=0
    $(".total[data-line]").each((e,s)=>{
        if(s.innerText){
            somme+=parseFloat(s.innerText)
        }
        
    })
    if(somme<0){
        alert("Verifier vos données");
    }
    else{
        alert("Commande Valide \nle totale est : " + somme );

        panier=[{
            "id":3,
            "quantity":2
        },
        {
            "id":4,
            "quantity":3
        }
    ]
        $.ajax({
            type: "POST",
            url: "http://localhost:8000/commande/check",
            data: {panier:panier},
            success: function (response) {
            console.log(response);
            }
            });
    }
    
})