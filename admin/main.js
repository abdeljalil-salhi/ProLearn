function toggleSub(sidepage, sideitem){
    var x = document.getElementById(sidepage);
    var y = document.getElementById(sideitem);
    if(x.classList.item(0) == 'side-hidden'){
        x.classList.remove('side-hidden');
        y.classList.add('side-active');
    }else{
        x.classList.add('side-hidden');
        y.classList.remove('side-active');
    }
}

function openPage(page){
    var x = document.getElementById(page);
    if(x.classList.length > 0){
        var list = document.getElementById('main').children;
        for(i = 0; i < list.length; i++){
            list[i].classList.add('side-hidden');
        }
        x.classList.remove('side-hidden');
    }
}