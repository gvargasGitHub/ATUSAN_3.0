/**
 * Application
 */
var app = new Application("app");

app.onOpen = () => console.log(`${app.name} saludando`);

$("document").ready(() => {
  app.execute();

  if (typeof topbar != "undefined") {
    topbar.onMenuItemClick = function (item) {
      if (item.name == "mi_close") {
        if (confirm('¿Cerrar?')) Module.active().send('/close', { onDone: rs => ats.openModule('/') });
      } else {
        ats.openModule(item.module);
      }
    };
  }
});