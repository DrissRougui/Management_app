//Fournisseur on change function
$(".fournisseur").change(function(e,s) {
    line = e.currentTarget.getAttribute("data-line")
    id=$(".fournisseur[data-line="+line+"] option:selected").val();
    $(".produit[data-line="+line+"]").val([])
    $(".quantity[data-line="+line+"]").val("")
    $(".prix[data-line="+line+"]").text("")
    $(".total[data-line="+line+"]").text("")

    $.ajax({
        type: "POST",
        url: "http://localhost:8000/commande/load",
        data: {id:id},
        success: function (produits) {
        
            $(".produit[data-line="+line+"]").empty()
        
            produits.forEach((p) => {
                
                let option = $('<option></option>').attr("prix",p.prixUnitaire).attr("value", p.id).text(p.nomProduit);
                $(".produit[data-line="+line+"]").append(option);
                
            });
        }
     });
})


//Produit on change function
$(".produit").change((e,s)=>{
    line = e.currentTarget.getAttribute("data-line")
   
    idprod= $(".produit[data-line="+line+"] option:selected").val()
    prix= $(".produit[data-line="+line+"] option:selected").attr("prix")
    $(".prix[data-line="+line+"]").text(prix)
})


//Quantity on change function
$(".quantity").change((e,s)=>{
    line = e.currentTarget.getAttribute("data-line")
    prix= $(".produit[data-line="+line+"] option:selected").attr("prix")
    amount=parseInt($(".quantity[data-line="+line+"]").val())
    $(".total[data-line="+line+"]").text((amount*prix).toFixed(1))

})

    





$("#calculer").click(()=>{
    // somme=0
    // $(".total[data-line]").each((e,s)=>{
    //     if(s.innerText){
    //         somme+=parseFloat(s.innerText)
    //     }
        
    // })
    // if(somme<0){
    //     alert("Verifier vos données");
    // }
    // else{
    //     alert("Commande Valide \nle totale est : " + somme );

    //     panier=[{
    //         "id":3,
    //         "quantity":2
    //     },
    //     {
    //         "id":4,
    //         "quantity":3
    //     }
    // ]
    panier=[]
    $(".produit option:selected").each((e,s)=>{
        line=s.parentElement.getAttribute("data-line")

        id=parseInt(s.getAttribute("value"))
        quantity=parseInt($(".quantity[data-line="+line+"]").val())
        
        if(Number.isInteger(id) && Number.isInteger(quantity)){
            produit={
                "id":id,
                "quantity": quantity
            }
            panier.push(produit)
        }
        
        
        


    })
    
    if (panier.length==0){
        alert("remplir au moin une ligne de commande")
    } else{
        $.ajax({
            type: "POST",
            url: "http://localhost:8000/commande/check",
            data: {panier:panier},
            success: function (response) {
            console.log(response);
            }
            });
    }}
    
)