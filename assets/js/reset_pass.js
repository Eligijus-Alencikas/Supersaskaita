$("#submit").click((e) => {
  e.preventDefault();

  let data = {
    supersaskaita: true,
    use: "reset-pass",
    password: $("#password").val(),
  };

  $.post("/", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);
    console.log(jqXHR);

    window.location.href = "/supersaskaita/password-changed";
  }).fail((data, textStatus, jqXHR) => {
    $("#warning").text(data.responseText);
  });
});
