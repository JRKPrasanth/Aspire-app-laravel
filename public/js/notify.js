//Notify Success Message
function notysuccess(message,status)
{
swal({
  position: 'top-end',
  type: status,
  title: message,
  showConfirmButton: false,
  timer: 1500
});
}