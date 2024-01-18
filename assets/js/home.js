$("#btn-logout").click(function (e) {
  e.preventDefault();
  data = {
    use: "logout",
    supersaskaita: true,
  };

  $.post("/", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);
    console.log(jqXHR);

    window.location.href = "/supersaskaita/";
  });
});
