function upload(form) {
  $form = $('#upload-form');
  fd = new FormData($form[0]);
  console.log(fd);
  $.ajax(
      '/index.php/post',
      {
      type: 'post',
      processData: false,
      contentType: false,
      data: fd,
      dataType: "json",
      success: function(data) {
          alert('success');
          console.log(data);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert( "ERROR" );
          alert( textStatus );
          alert( errorThrown );
      }
  });
  return false;
}
