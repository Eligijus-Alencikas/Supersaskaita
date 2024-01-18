$("#submit-login").click(function (e) {
  e.preventDefault();

  data = {
    use: "login",
    email: $("#email").val(),
    password: $("#password").val(),
    supersaskaita: true,
  };

  $.post("/", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);
    console.log(jqXHR);

    window.location.href = "/supersaskaita/";
  }).fail((data, textStatus, jqXHR) => {
    $("#warning").text(data.responseText);
  });
});
