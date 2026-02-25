(function (window) {
  var
    openModule = function (path, params) {
      if (!params) window.location.replace(path);

      if (params) {
        // Obtiene el formulario de app
        const form = document.getElementById("appForm");
        if (!form) throw new Error('El formulario de app no existe.');
        form.setAttribute("action", path);

        // integra parametros
        for (let prm in params) {
          let input = document.createElement("INPUT");
          input.setAttribute("type", "hidden");
          input.setAttribute("name", prm);
          input.setAttribute("value", params[prm]);
          form.appendChild(input);
        }
        // envia formulario
        form.requestSubmit();
      }
    },
    attachModule = function (module) {
      // Mueve cada uno de los componentes "modal" al final
      // de "body".
      const modals = document.querySelectorAll('content .ats-modal');
      const body = document.querySelector('body');
      if (modals && body) {
        for (let m = 0; m < modals.length; m++) body.appendChild(modals[m]);
      }
      ats.info(`Se movieron ${modals.length} modals para ${module.name}`);
      // Inicializa los componentes de Module
      module.initComponents();

      if (typeof module.onOpen != "undefined") module.onOpen();
    },
    attachForm = function () {
      const body = document.getElementsByTagName("body");

      const form = document.createElement("FORM");
      form.setAttribute("action", "/root");
      form.setAttribute("method", "POST");
      form.setAttribute("id", "appForm");
      form.addEventListener("submit", onSubmit);
      body[0].appendChild(form);
    },
    onSubmit = function (event) {
      console.log('Enviando appForm');
    },
    addEvent = function (eventName, callBack) {
      (window.addEventListener)
        ? window.addEventListener(eventName, callBack)
        : window.attachEvent("on" + eventName, callBack);
    },
    hideNavDropdown = function () {
      var x = document.querySelectorAll(".ats-navbar .dropdown");

      if (x != null) {
        var i = 0, n = x.length;
        for (; i < n; i++) {
          x[i].classList.remove("show");

          let c = x[i].querySelector(".caret");

          if (c != null) {
            c.classList.add("fa-caret-down");
            c.classList.remove("fa-caret-right");
          }
        }
      }

      // // remueve la modalidad Responsive
      // var navbars = document.getElementsByClassName("ats-navbar");
      // if (navbars != null) {
      //   for (let i = 0; i < navbars.length; i++)
      //     navbars[i].classList.remove("responsive");
      // }
      // var navresponsiveicons = document.getElementsByClassName("ats-nav-responsive-icon");
      // if (navresponsiveicons != null) {
      //   for (let i = 0; i < navresponsiveicons.length; i++)
      //     navresponsiveicons[i].classList.remove("transform");
      // }
    },
    hideMenuOptionsDropDown = function () {
      const x = document.querySelectorAll("table>tbody>tr>td.menu-options div.content");

      if (x == null) return;

      for (let i = 0; i < x.length; i++) x[i].classList.remove("show");
    },
    startLoader = function () {
      document.getElementById("ats-loader").style.display = "block";
    },
    stopLoader = function () {
      document.getElementById("ats-loader").style.display = "none";
    },
    info = (message) => console.info(message),
    /**
     * Send
     */
    send = function (url, options) {

      var fd = new FormData();
      if (typeof options.data == "object") {
        for (const key in options.data) {
          if (!Object.hasOwn(options.data, key)) continue;

          fd.append(key, options.data[key]);
        }
      }
      startLoader();
      $.ajax({
        url,
        method: 'POST',
        type: 'POST',
        processData: false,
        contentType: false,
        data: fd
      })
        .done(rs => {
          try {
            rs = JSON.parse(rs);
            if (rs.status == 'ok') {
              if (typeof options.onDone == "function")
                options.onDone(rs.data);
            } else if (rs.status == 'error') {
              console.error(rs.message);
            } else {
              if (typeof options.onFail == "function")
                options.onFail(rs.message);
              else
                console.warn(rs);
            }
          } catch (e) {
            console.error(e.message);
            console.error(rs);
          }
        })
        .fail((xhr, status, error) => console.error(error))
        .always(() => {
          stopLoader();
          info('Transacción terminada');
        });
    };

  addEvent("click", hideNavDropdown);
  addEvent("click", hideMenuOptionsDropDown);
  addEvent("unload", stopLoader);

  window.ats = {
    openModule,
    attachModule,
    attachForm,
    hideNavDropdown,
    hideMenuOptionsDropDown,
    startLoader,
    stopLoader,
    info,
    send
  };

  $("document").ready(() => {
    ats.info('Atusan listo!');

    ats.attachForm();
  });
})(window);