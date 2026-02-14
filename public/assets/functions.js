function success(msg='',title='Good Job!',pos='topRight' ) {
/*var success=document.getElementById("success");
success.play();*/

  iziToast.success({
    title: title,
    message: msg,
    position: pos,
    closeOnEscape: true,
    timeout:3000

  });

}


function info(msg='',title='Info!',pos='topRight' ) {
/*var success=document.getElementById("success");
success.play();*/

  iziToast.info({
    title: title,
    message: msg,
    position: pos,
    closeOnEscape: true,
    timeout:3000

  });

}

function error(msg='',title='Sorry!',pos='topRight' ) {
/*var error=document.getElementById("error");
error.play();*/

  iziToast.error({
    title: title,
    message: msg,
    position: pos,
    closeOnEscape: true,
    timeout:3000
  });

}

function warning(msg='',title='Oops!',pos='topRight' ) {
/*var warning=document.getElementById("warning");
warning.play();*/

iziToast.warning({
	title:title,
	message: msg,
	position: pos,
  closeOnEscape: true,
  timeout:3000

});

}


function Twofloating(vlu) {
  return parseFloat(vlu).toFixed(2);
}
