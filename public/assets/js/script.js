const stars = document.querySelectorAll(".fa-star");
const rating = document.querySelector('.rating')

init();

function init(){
    stars.forEach(star => {
        star.addEventListener("click", saveRating)
        star.addEventListener("mouseover", addCss);
        star.addEventListener("mouseleave", removeCss);
        
    });
}

function saveRating(e){
    removeEventListenerToAllStars();
    rating.innerText = e.target.dataset.star;
    console.log(e.target.dataset, e.target.nodeName, e.target.nodeType); // cible la clés et sa valeur  NodeName récup le type de noeux
}
function removeEventListenerToAllStars(){
    stars.forEach(star => {
        star.removeEventListener("click", saveRating);
        star.removeEventListener("mouseover", addCss);
        star.removeEventListener("mouseleave", removeCss);
    })
}

function addCss(e, css = "checked"){
    const overedStar = e.target; // colorie l'etoile selon le Css quand on survole avec la sourie 
    overedStar.classList.add(css);
    const previousSiblings = getPreviousSiblings(overedStar);
    console.log("previousSiblings", previousSiblings);
    previousSiblings.forEach(elem => elem.classList.add(css)); // boucle sur les elem  previous et les colorie
}
function removeCss(e, css = "checked"){
    const overedStar= e.target;// quand on retire la sourie la class "checked" et supprimer 
    overedStar.classList.remove(css);
    const previousSiblings = getPreviousSiblings(overedStar);
    previousSiblings.forEach(elem => elem.classList.remove(css)); // quand retir la souris fais un remove 


}
function getPreviousSiblings(elem){//récup tout les étoiles/sibling précédant
    console.log("elem.previousSibling", elem.previousSibling);
    let siblings = []; // crée un tableau pour récup  toutes les étoiles 
    const iNodeType = 1; // dit que ns sommes intérésser que par les i qui on un nodetype = a 1
    while(elem = elem.previousSibling){ //appel récursive tant que l'élement a un précedent on e récup 
        if(elem.nodeType === iNodeType){ // si c'est bien un i 
            siblings = [elem, ...siblings]; // on conserve dan sle tableau 
        }
    }
    return siblings; // return les tableau élément du tableau  

}  
