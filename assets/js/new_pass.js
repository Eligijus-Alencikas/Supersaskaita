$("#submit").click((e) => {
  e.preventDefault();

  data = {
    use: "create-reset-pass",
    supersaskaita: true,
    email: $("#email").val(),
  };

  $.post("/", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);
    console.log(jqXHR);

    window.location.href = "/supersaskaita/password-reset-sent";
  }).fail((data, textStatus, jqXHR) => {
    $("#warning").text(data.responseText);
  });
});
