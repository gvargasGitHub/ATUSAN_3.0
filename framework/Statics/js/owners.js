/**
 * 
 */
class OwnerBase {
  constructor(name) {
    this.name = name;
  }
}
/**
 * 
 */
class Application extends OwnerBase {
  constructor(name) {
    super(name);
  }

  execute() {
    if (typeof this.onOpen == "function") this.onOpen();

    if (!Module.active()) throw new Error('No existe módulo.');

    ats.attachModule(Module.active());

    ats.stopLoader();
  }
}
/**
 * 
 */
class ModuleBase extends OwnerBase {
  /**
   * 
   * @param {String} name 
   * @param {String} uri 
   */
  constructor(name, uri) {
    super(name);
    this.uri = uri;
    ats.info(`Creando módulo ${name}`);
    this.components = [];
  }

  static active() {
    return window[__ModuleActive__];
  }

  registerResizeListener() {
    window.addEventListener("resize", this.onResize);
  }

  unregisterResizeListener() {
    window.removeEventListener("resize", this.onResize);
  }

  onOpen() {
    // TODO
  }

  onActivate() {
    return false;
  }

  onResize() {
    // TODO
  }

  addComponent(name) {
    this.components.push(name);
  }

  initComponents() {
    ats.info(`initComponents de ${this.name}:${this.components.length}`);
    for (let i = 0; i < this.components.length; i++)
      window[this.components[i]].init();
  }

  send(url, options) {

    var fd = new FormData();

    fd.append('module', this.name);
    if (typeof options.data == "object") {
      for (const key in options.data) {
        if (!Object.hasOwn(options.data, key)) continue;

        fd.append(key, options.data[key]);
      }
    }
    var headers = (fd.has('csrf')) ? { 'X-CSRF-TOKEN': fd.get('csrf') } : {};
    ats.startLoader();
    $.ajax({
      url,
      method: 'POST',
      type: 'POST',
      processData: false,
      contentType: false,
      headers,
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
        ats.stopLoader();
        ats.info('Transacción terminada');
      });
  }
}
/**
 * @var String __ModuleActive__
 * Almacena el nombre del objeto "Module" presente.
 * Esta variable se actualiza en el "constructor"
 * de la clase "Module".
 */
var __ModuleActive__ = undefined;

class Module extends ModuleBase {
  /**
   * 
   * @param {String} name 
   * @param {String} uri 
   */
  constructor(name, uri) {
    super(name, uri);
    __ModuleActive__ = this.name;
  }
}

class NestedModule extends ModuleBase { }
