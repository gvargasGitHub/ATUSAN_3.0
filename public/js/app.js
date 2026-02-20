var app = new Application("app");

app.onOpen = () => console.log(`${app.name} saludando`);

$("document").ready(() => {
  app.execute();

  if (typeof topbar != "undefined") {
    // Establece funcionalidad de menu
    topbar.onMenuItemClick = function (item) {
      if (item.name == "mi_close") {
        if (confirm('¿Cerrar?')) ats.openModule('/');
      } else {
        if (typeof mainTab != "undefined") {
          mainTab.openModule(item.module);
        } else {
          ats.openModule(item.module);
        }
      }
    };

    //
    // establece rutina programada cada 5 minutos para:
    // 1. Mantener activa la Sesion en el Servidor.
    // 2. Monitorea estado de bandera "logout" para
    //    cuestiones de mantenimiento de portal.
    // setInterval(function () {
    //   ats.send('/keepAlive', {
    //     onDone: rs => {
    //       if (rs.logout) {
    //         alert('El Sistema se cerrará por mantenimiento.');
    //         ats.openModule('/');
    //       }
    //     }
    //   });
    // }, (1000 * 60 * 2));
  }
});