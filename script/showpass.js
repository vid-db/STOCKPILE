let state = false;
const password = document.getElementById('pass');
const show = document.getElementById('eye');
 
function showpass(){
  if(state){
    password.setAttribute("type","password");
    state = false;
    show.style.color="#222";

  }
  else{
    password.setAttribute("type","text");
    state = true;
    show.style.color="#5887ef";

  }
}