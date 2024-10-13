
function RedirigerSubmit(){
  var id = { id };


  window.location.href = "{{ path('app_sub', {'id': 'placeholder'}) }}".replace('placeholder', id);
}
