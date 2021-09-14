const btn = document.querySelector('.btn-hamburger');
const div = document.querySelector('#hamburger');

btn.addEventListener('click',(e)=>{
  if(div.classList.contains('d-none')){
      div.classList.remove('d-none')
  }else{
      div.classList.add('d-none')
  }
})