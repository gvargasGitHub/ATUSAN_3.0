"use strict"
/**
 * 
 */
class ComponentBase {
  /**
   * 
   * @param {String} name 
   * @param {String} owner 
   */
  constructor(name, owner) {
    this.name = name;
    this.element;
    ats.info(`Constuyendo ${name} de ${owner}`);
    if (typeof window[owner] == "undefined") console.error(`${owner} no existe.`);

    if (window[owner] instanceof ModuleBase) {
      this.owner = window[owner];
      this.owner.addComponent(this.name);

      this.postConstructor();
    } else {
      console.error(`${owner} no es clase Module.`);
    }
  }

  postConstructor() {
    throw new Error(`${this.name} de ${this.owner.name} no ha implementado postConstructor.`);
  }

  init() {
    ats.info(`Init ${this.name}`);
    this.element = document.getElementById(this.name);

    if (!this.element) throw new Error(`${this.name} no existe.`);
  }

  fitToParentHeight(margin = 0) {
    // valida la dimension de la pantalla
    if (screen.availWidth <= 768) return;

    if (this.element.offsetParent == null) return;
    // offset parent es the nearest ancestor, if not exists, then offset parent is
    // document body. The nearest ancestor of all Components is "content" block.
    // w3schools.com/jsref
    ats.info(`${this.name} ajustándose a  ${this.element.offsetParent.tagName}`);
    let h = this.element.offsetParent.offsetHeight - (this.element.offsetTop + margin);

    this.element.style.height = `${h}px`;
  }

  fitToParentWidth() {
    // valida la dimension de la pantalla
    if (screen.availWidth <= 768) return;

    if (this.element.offsetParent == null) return;
    // offset parent es the nearest ancestor, if not exists, then offset parent is
    // document body. The nearest ancestor of all Components is "content" block.
    // w3schools.com/jsref

    let w = this.element.offsetParent.offsetWidth - (this.element.offsetLeft + 10);
    ats.info(`width fitting ${this.name}:${w}px`)
    this.element.style.width = `${w}px`;
  }
}
/**
 * NavBar
 */
class NavBar extends ComponentBase {
  // constructor(name) {
  //   super(name);
  // }

  postConstructor() { }

  init() {
    super.init();

    const items = this.element.querySelectorAll("li.item");

    for (let i = 0; i < items.length; i++)
      items[i].addEventListener('click', NavBar.handlerMenuItemEvent);

    const dropdowns = this.element.querySelectorAll("li.dropdown");

    for (let i = 0; i < dropdowns.length; i++)
      dropdowns[i].addEventListener('click', NavBar.handlerDropdownEvent);

    const bars = this.element.querySelector("div.bars");

    bars.addEventListener('click', NavBar.handlerBarsEvent);
  }

  static handlerMenuItemEvent(ev) {
    let module = ev.target.getAttribute("ats-module"),
      name = ev.target.getAttribute("ats-name"),
      owner = ev.target.getAttribute("ats-owner");

    if (window[owner].onMenuItemClick) window[owner].onMenuItemClick({ name, module });
  }

  static handlerDropdownEvent(ev) {
    ats.hideNavDropdown();

    ev.target.classList.toggle('show');

    var x = ev.target.querySelector(".caret");

    if (x != null) {
      x.classList.remove("fa-caret-down");
      x.classList.add("fa-caret-right");
    }

    ev.stopPropagation();
  }

  static handlerBarsEvent(ev) {
    ev.stopPropagation();

    const elm = (ev.target.classList.contains("bars"))
      ? ev.target : ev.target.parentElement;

    let view = elm.getAttribute("ats-owner");

    window[view].element.classList.toggle("responsive");
    elm.classList.toggle("transform");
  }

  onMenuItemClick(item) {
    // TODO
  }
}
/**
 * SideBar
 */
class SideBar extends ComponentBase {
  // constructor(name) {
  //   super(name);
  // }

  postConstructor() { }

  init() {
    super.init();

    const items = this.element.querySelectorAll("li.item");

    for (let i = 0; i < items.length; i++)
      items[i].addEventListener('click', SideBar.handlerMenuItemEvent);

    const dropdowns = this.element.querySelectorAll("li.dropdown");

    for (let i = 0; i < dropdowns.length; i++)
      dropdowns[i].addEventListener('click', SideBar.handlerDropdownEvent);

    const bars = this.element.querySelector("div.bars");

    bars.addEventListener('click', SideBar.handlerBarsEvent);
  }

  static handlerMenuItemEvent(ev) {
    let module = ev.target.getAttribute("ats-module"),
      name = ev.target.getAttribute("ats-name"),
      owner = ev.target.getAttribute("ats-owner");

    if (window[owner].onMenuItemClick) window[owner].onMenuItemClick({ name, module });
  }

  static handlerDropdownEvent(ev) {
    ats.hideNavDropdown();

    ev.target.classList.toggle('show');

    var x = ev.target.querySelector(".caret");

    if (x != null) {
      x.classList.remove("fa-caret-down");
      x.classList.add("fa-caret-right");
    }

    ev.stopPropagation();
  }

  static handlerBarsEvent(ev) {
    ev.stopPropagation();

    const elm = (ev.target.classList.contains("bars"))
      ? ev.target : ev.target.parentElement;

    let view = elm.getAttribute("ats-owner");

    window[view].element.classList.toggle("responsive");
    elm.classList.toggle("transform");
  }

  onMenuItemClick(item) {
    // TODO
  }

  fitToParentHeight(margin = 0) {
    // valida la dimension de la pantalla
    if (screen.availWidth <= 768) return;

    if (this.element.offsetParent == null) return;
    // offset parent es the nearest ancestor, if not exists, then offset parent is
    // document body. The nearest ancestor of all Components is "content" block.
    // w3schools.com/jsref

    let h = this.element.offsetParent.offsetHeight - ((this.element.offsetTop + 10) - margin);
    ats.info(`height fitting ${this.name}:${h}px`);
    this.element.style.height = `${h}px`;
  }

  fitToParentWidth() {
    // valida la dimension de la pantalla
    if (screen.availWidth <= 768) return;

    if (this.element.offsetParent == null) return;
    // offset parent es the nearest ancestor, if not exists, then offset parent is
    // document body. The nearest ancestor of all Components is "content" block.
    // w3schools.com/jsref

    let w = this.element.offsetParent.offsetWidth - (this.element.offsetLeft + 10);
    ats.info(`width fitting ${this.name}:${w}px`)
    this.element.style.width = `${w}px`;
  }
}
/**
 * DataViewBase
 */
class DataViewBase extends ComponentBase {
  postConstructor() { }

  init() {
    super.init();
  }

  static getControlId(element) {
    let id = element.id.split("-");

    let row = (typeof id[2] !== "undefined") ? Number(id[2]) : 1;

    return {
      view: id[0],
      control: id[1],
      row
    }
  }
}
/**
 * DataForm
 */
class DataForm extends DataViewBase {
  init() {
    super.init();
    this.entries = [];
    this.maplists = new Map();
    this.load();
  }

  load() {
    this.maplists.clear();

    const form = this.element.querySelector(".ats-dataform form");

    form.addEventListener("submit", DataForm.handlerFormSubmitEvent);
    form.addEventListener("reset", DataForm.handlerFormResetEvent);

    // Alimenta colección de controles
    const controls = this.element.querySelectorAll('input, select, textarea');

    controls.forEach(el => {
      if (el.hasAttribute('name')) this.entries.push(el.getAttribute('name'));
    });

    // Inputs
    const inputs = this.element.querySelectorAll(".inputEv");

    inputs.forEach(el => {
      el.addEventListener("input", DataForm.handlerControlEvent);
    });

    const files = this.element.querySelectorAll(".file");
    files.forEach(el => {
      // esto es para resolver bug de FireFox
      el.addEventListener('focus', () => el.classList.add('has-focus'));
      el.addEventListener('blur', () => el.classList.remove('has-focus'));
      // alimenta la colección de controles Files
      let { control } = DataViewBase.getControlId(el);
      if (!this.maplists.has(control)) this.maplists.set(control, new ControlFile(this, control, el));
    });

    const selects = this.element.querySelectorAll(".changeEv");

    selects.forEach(el => {
      el.addEventListener("change", DataForm.handlerControlEvent);
      // alimenta la colección  de controles Select
      let { control } = DataViewBase.getControlId(el);

      if (!this.maplists.has(control)) this.maplists.set(control, new ControlSelect(this, control, el));
    });

    // Controles Autocomplete
    const autocompletes = this.element.querySelectorAll(".autocomplete-control");
    // funcionalidad para sujetar movilidad en listado de items
    autocompletes.forEach(el => {
      el.addEventListener("keydown", ControlAutoComplete.handlerKeyDown);
      // alimenta la colección de controles Autocomplete
      let { control } = DataViewBase.getControlId(el);

      if (!this.maplists.has(control)) this.maplists.set(control, new ControlAutoComplete(this, control, el));
    });
  }

  feedList(control, list) {
    const elms = this.findElementsByName(control);

    if (elms.length == 0) throw new Error(`${control} no existe en ${this.name}`);

    if (this.maplists.has(control)) {
      this.maplists.get(control).feedList(list);
    } else
      throw new Error(`${control} no es una lista desplegable`);
  }

  inflate(content) {
    const body = this.element.querySelector(".body");
    while (body.hasChildNodes()) body.removeChild(body.firstChild);

    body.innerHTML = content;

    this.load();

    this.onPopulateDone();
  }

  getData() {
    var data = {};

    this.entries.forEach(c => data[c] = this.getItem(c));

    return data;
  }

  getItem(control) {
    const elms = this.findElementsByName(control);

    if (elms.length == 0) throw new Error(`${control} no existe en ${this.name}`);

    let type = elms[0].getAttribute("type");

    let resolver = {
      checkbox: el => (el[0].checked) ? 1 : 0,
      fileString: el => {
        let files = [];
        for (let i = 0; i < el[0].files.length; i++) files.push(el[0].files[i].name);

        return files.join(" ");
      },
      file: el => el[0].files,
      text: el => el[0].value,
      hidden: el => el[0].value,
      password: el => el[0].value,
      radio: el => {
        let value;
        el.forEach(r => {
          if (r.checked) value = r.value
        });
        return value;
      },
      select: el => el[0].value,
    };

    return (resolver[type]) ? resolver[type](elms) : null;
  }

  setItem(control, value) {
    const elms = this.findElementsByName(control);

    if (elms.length == 0) throw new Error(`${control} no existe en ${this.name}`);

    let type = elms[0].getAttribute("type");

    let resolver = {
      checkbox: el => {
        el[0].checked = (Number(value) == 1);
        el[0].value = Number(value);
      },
      hidden: el => el[0].value = value,
      text: el => el[0].value = value,
      password: el => el[0].value = value,
      radio: el => el.forEach(r => r.checked = (r.value == value)),
      select: el => el[0].value = value,
    };

    if (resolver[type]) resolver[type](elms);
  }

  setFocus(control) {
    const elms = this.findElementsByName(control);

    if (elms.length == 0) throw new Error(`${control} no existe en ${this.name}`);

    elms[0].focus();
  }

  findElementsByName(control) {
    return this.element.querySelectorAll(`[name="${control}"]`);
  }

  reset() {
    const form = this.element.querySelector(`#${this.name}-form`);
    if (form) form.reset();
  }

  lists() {
    return this.maplists;
  }

  static handlerControlEvent(ev) {

    let { view, control } = DataViewBase.getControlId(ev.target);

    let value = window[view].getItem(control);

    let eventName = 'on' + ev.type.substr(0, 1).toUpperCase() + ev.type.substr(1);

    if (typeof window[view][eventName] == "function")
      window[view][eventName]({ view, control, value });

    if (ev.target.type == "file" && window[view].lists.has(control)) {
      window[view].lists.get(control).feedList(value);
    }
  }

  static handlerFormSubmitEvent(ev) {
    ats.info(`Deteniendo a ${ev.type}`);
    ev.preventDefault();

    let { view } = DataViewBase.getControlId(ev.target);
    let route = ev.target.getAttribute("ats-route");

    var fd = new FormData(ev.target);

    var headers = (fd.has('csrf')) ? { 'X-CSRF-TOKEN': fd.get('csrf') } : {};

    $.ajax({
      url: route,
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
            if (typeof window[view].onSubmitDone == "function")
              window[view].onSubmitDone(rs.data);
          } else {
            if (typeof window[view].onSubmitFail == "function")
              window[view].onSubmitFail(rs.message);
          }
        } catch (e) {
          console.error(e.message);
          console.error(rs);
        }
      })
      .fail((xhr, status, error) => console.error(error))
      .always(() => ats.info('Transacción terminada'));

  }

  static handlerFormResetEvent(ev) {
    ats.info(`${ev.type} ha reestablecido el formulario`);
  }

  onInput(ar) {
    // TODO
  }

  onChange(ar) {
    // TODO
  }
}
/**
 * DataGrid
 */
class DataGrid extends DataViewBase {
  constructor(name, owner) {
    super(name, owner);
  }

  init() {
    super.init();

    this.load();
  }

  load() {
    this.controls = [];
    this.total_rows = 0;

    const view = this;
    const tds = this.element.querySelectorAll("table>tbody.detail>tr>td");

    tds.forEach((el, i) => {
      let { control, row } = DataViewBase.getControlId(el);
      if (typeof control == "undefined") return;

      if (view.controls.lastIndexOf(control) == -1) view.controls.push(control);
      if (view.total_rows < row) view.total_rows = row;

      el.addEventListener("click", DataGrid.handlerTableDataEvent);
    });

    const hiddens = this.element.querySelectorAll('input[type="hidden"]');

    hiddens.forEach(el => {
      let { control } = DataViewBase.getControlId(el);
      if (typeof control == "undefined") return;

      if (view.controls.lastIndexOf(control) == -1) view.controls.push(control);
    });

    const mopt = this.element.querySelectorAll("table>tbody.detail>tr>td.menu-options div.content a.option");

    mopt.forEach(el => {
      el.addEventListener("click", DataGrid.handlerMenuOptionEvent);
    });
  }

  inflate(content) {
    this.reset();

    const body = this.element.querySelector(".body");

    body.innerHTML = content;

    this.load();

    this.onPopulateDone();
  }

  getData(row) {
    if (row > this.total_rows || row == 0) return undefined;
    const view = this;
    let data = {};

    this.controls.forEach(control => {
      data[control] = view.getItem(row, control);
    });

    return data;
  }

  getItem(row, control) {
    const el = this.element.querySelector(`#${this.name}-${control}-${row}`);

    if (el == null) throw new Error(`${control}-${row} no existe en ${this.name}`);

    let type = el.getAttribute("type");

    let resolver = {
      data: () => el.innerText,
      checkbox: () => (el.firstElementChild.checked) ? 1 : 0,
      hidden: () => el.value,
      state: () => el.getAttribute('value')
    };

    return (resolver[type]) ? resolver[type]() : null;
  }

  setItem(row, control, value) {
    const el = this.element.querySelector(`#${this.name}-${control}-${row}`);

    if (el == null) throw new Error(`${control}-${row} no existe en ${this.name}`);

    let type = el.getAttribute("type");

    let resolver = {
      data: () => el.innerText = value,
      checkbox: () => el.firstElementChild.checked = (Number(value) == 1),
      hidden: () => el.value = value
    };

    resolver[type]();
  }

  selectRow(row) {
    const trs = this.element.querySelectorAll("table tbody.detail tr.row");

    trs.forEach((tr, i) => {
      if (row == (i + 1))
        tr.classList.add("selected");
      else
        tr.classList.remove("selected");
    });
  }

  onClick(ar) {
    // TODO
  }

  onMenuOptionClick(ar) {
    // TODO
  }

  onPopulateDone() {
    // TODO
  }

  reset() {
    const body = this.element.querySelector(".body");
    while (body.hasChildNodes()) body.removeChild(body.firstChild);
  }

  static handlerTableDataEvent(ev) {
    ev.stopPropagation();
    ats.hideMenuOptionsDropDown();

    let { view, control, row } = DataViewBase.getControlId(ev.target);

    let type = ev.target.getAttribute("type");

    let getValue = {
      data: () => window[view].getItem(row, control),
      checkbox: () => window[view].getItem(row, control),
      hidden: () => window[view].getItem(row, control),
      state: () => window[view].getItem(row, control)
    };
    // obtiene el dato/valor del control
    let value = (getValue[type]) ? getValue[type]() : undefined;

    let preactions = {
      menu: () => {
        // obtiene el div.content
        let div;
        if (ev.target.tagName == 'I')
          div = ev.target.nextElementSibling;
        else if (ev.target.tagName == 'TD')
          div = ev.target.querySelector("div.content");
        else
          return;

        div.classList.toggle("show");
      }
    };

    if (preactions[type]) preactions[type]();

    let eventName = 'on' + ev.type.substr(0, 1).toUpperCase() + ev.type.substr(1);

    if (typeof window[view][eventName] == "function")
      window[view][eventName]({ view, control, row, value });
  }

  static handlerMenuOptionEvent(ev) {
    ev.stopPropagation();
    let view = ev.target.getAttribute("ats-owner"),
      name = ev.target.getAttribute("ats-name"),
      row = ev.target.getAttribute("ats-row");

    ats.hideMenuOptionsDropDown();

    window[view].onMenuOptionClick({
      name, row
    });
  }
}
/**
 * DataTree
 */
class DataTree extends DataViewBase {
  // constructor(name) {
  //   super(name);
  // }

  init() {
    super.init();

    this.load();
  }

  load() {
    const tds = this.element.querySelectorAll("td");

    tds.forEach((el, i) => {
      if (el.classList.contains("caret")) el.addEventListener('click', DataTree.handlerCaretEvent);

      if (el.classList.contains("tree-control")) el.addEventListener('click', DataTree.handlerControlEvent);
    });
  }

  getData(index) {
    const item = this.element.querySelector(`#${this.name}-${index}`);

    if (item == null) return {};

    let data = {};
    for (let i = 0; i < item.attributes.length; i++) {
      let attr = item.attributes[i];
      if (!/^data-/.test(attr.name)) continue;
      let name = attr.name.substring(5);
      data[name] = attr.value;
    }

    return data;
  }

  selectItem(i_index) {
    const tables = this.element.querySelectorAll("table");

    tables.forEach(table => {
      let { index } = DataTree.getControlId(table);

      if (index == i_index)
        table.classList.add("selected");
      else
        table.classList.remove("selected");
    });
  }

  unselectItems() {
    const tables = this.element.querySelectorAll("table");

    tables.forEach(table => table.classList.remove("selected"));
  }

  static getControlId(element) {
    let id = element.id.split("-");

    let control = (typeof id[2] !== "undefined") ? id[2] : '';
    let action = (typeof id[3] !== "undefined") ? id[3] : '';
    return {
      view: id[0],
      index: id[1],
      control,
      action
    }
  }

  static handlerCaretEvent(ev) {
    let { view, index } = DataTree.getControlId(ev.target);

    ev.target.classList.toggle("down");

    const content = window[view].element.querySelector(`#${view}-${index}-content`);

    if (content != null) {
      if (ev.target.classList.contains("down"))
        content.classList.add('show');
      else
        content.classList.remove('show');
    }

    ev.stopPropagation();
  }

  static handlerControlEvent(ev) {
    let { view, index, control, action } = DataTree.getControlId(ev.target);

    let type = ev.target.getAttribute('type');

    let getValue = {
      action: () => action,
      text: () => ev.target.innerText,
      check: () => ev.target.firstElementChild.checked ? 1 : 0,
      checkbox: () => ev.target.checked ? 1 : 0
    };
    let value = (getValue[type]) ? getValue[type]() : undefined;

    let eventName = 'on' + ev.type.substr(0, 1).toUpperCase() + ev.type.substr(1);

    if (typeof window[view][eventName] == "function")
      window[view][eventName]({ view, control, index, action, value });

    ev.stopPropagation();
  }
}
/**
 * Modal
 */
class Modal extends ComponentBase {
  postConstructor() { }

  init() {
    super.init();

    const close = this.element.querySelector("i.close");

    close.addEventListener("click", Modal.handlerCloseModalEvent);
  }

  openModal(params) {
    this.element.style.display = "block";

    this.onOpen(params);
  }

  closeModal() {
    let close = this.onClose();

    if (typeof close == "undefined") close = true;

    if (close) this.element.style.display = "none";
  }

  onOpen() {
    // TODO
  }

  onClose() {
    // TODO
    return true;
  }

  static handlerCloseModalEvent(ev) {
    ev.stopPropagation();
    let modal = ev.target.getAttribute("ats-owner");

    window[modal].closeModal();
  }
}

/**
 * 
 */
class TabGroup extends ComponentBase {

  postConstructor() {
    this.contents = [];
    this._current;
  }

  init() {
    super.init();
    const btns = this.element.querySelectorAll(".buttons button");

    let first = true;
    btns.forEach(btn => {
      btn.addEventListener('click', TabGroup.handlerButtonEvent);

      let name = btn.getAttribute('ats-name');

      ats.info(`${name} de ${this.name} de ${this.owner.name}`);
      this[name] = new TabGroupContent(name, this.owner, this, btn);

      this.contents.push(name);

      if (first) {
        this[name].show();
        this.current = name;
        first = false;
      }
    });
  }

  /**
   * onClick
   * @param {String} ar
   */
  onClick(ar) {
    // TODO
  }

  /**
   * onChange
   * @param {Object} ar {String prior, String current}
   */
  onChange(ar) {
    // TODO
  }

  get current() {
    return this._current;
  }
  set current(x) {
    this._current = x;
  }

  /**
   * Select Content
   * @param {Button} button 
   */
  __selectContent(button) {
    let name = button.getAttribute('ats-name');

    this.contents.forEach(content => {
      if (content == name) {
        this[content].show();
        this.current = name;
      } else
        this[content].hide();
    });
  }

  /**
   * Open Module
   * @param {String} path 
   * @param {Object} data 
   */
  openModule(path, data) {
    if (typeof data == "undefined") data = {};

    this.owner.send(path, {
      data,
      onDone: module => {
        let button, content;

        let prior = this.current;

        let i = this.contents.findIndex(val => val == module.name);
        if (i === -1) {
          // No existe
          close = document.createElement("I");
          close.setAttribute("ats-owner", this.owner.name);
          close.setAttribute("ats-parent", this.name);
          close.setAttribute("ats-name", module.name);
          close.classList.add("close");
          close.addEventListener('click', TabGroup.handlerCloseEvent);


          button = document.createElement("BUTTON");
          button.setAttribute("ats-owner", this.owner.name);
          button.setAttribute("ats-parent", this.name);
          button.setAttribute("ats-name", module.name);
          button.classList.add("closeable");

          button.id = `${this.name}-${module.name}-button`;
          button.appendChild(document.createTextNode(module.title));
          button.appendChild(close);
          button.addEventListener('click', TabGroup.handlerButtonEvent);
          this.element.querySelector(".buttons")
            .append(button);

          content = document.createElement("DIV");
          content.id = `${this.name}-${module.name}`;
          content.classList.add("content");
          content.classList.add(this.name);

          this.__parseScripts(content, module.content);

          this.element.querySelector(".contents")
            .append(content);

          this[module.name] = new TabGroupContent(module.name, this.owner, this, button);

          this.contents.push(module.name);

          this.current = module.name;
        } else {
          // Existe
          button = this[module.name].button;
          this[module.name].update(module.content);
        }

        this.__selectContent(button);

        // Module onOpen Event
        if (window[module.name] == null) console.error(`${module.name} no fue creado.`);
        ats.attachModule(window[module.name]);

        // onChange Event
        if (prior != this.current && typeof this.onChange == "function") this.onChange({ prior, current: this.current });
      },
      onFail: rs => console.error(rs)
    });
  }

  /**
   * Parse Scripts
   * Generado por ChatGPT
   */
  __parseScripts(divContent, htmlString) {
    // 1. Crear contenedor temporal
    const temp = document.createElement('div');
    temp.innerHTML = htmlString;

    // 2. Extraer scripts
    const scripts = Array.from(temp.querySelectorAll('script'));

    scripts.forEach(s => s.remove()); // eliminar del HTML

    // 3. Insertar HTML restante
    divContent.innerHTML = temp.innerHTML;

    // 4. Crear e insertar scripts dinámicamente
    scripts.forEach(oldScript => {
      const newScript = document.createElement('script');

      if (oldScript.src) {
        newScript.src = oldScript.src;
      } else {
        newScript.textContent = oldScript.textContent;
      }

      divContent.appendChild(newScript);
    });

  }
  /**
   * Handler of Button
   * @param {Event} ev 
   */
  static handlerButtonEvent(ev) {
    let name = ev.target.getAttribute('ats-name'),
      view = ev.target.getAttribute('ats-parent'),
      prior = window[view].current;

    window[view].__selectContent(ev.target);

    let current = window[view].current;
    // onClick Event
    if (typeof window[view].onClick == "function") window[view].onClick({ name });

    // onChange Event
    if (prior != current && typeof window[view].onChange == "function") window[view].onChange({ prior, current });

    ev.stopPropagation();
  }

  /**
   * Handler of Close
   * @param {Event} ev 
   */
  static handlerCloseEvent(ev) {
    ev.stopPropagation();
    let view = ev.target.getAttribute("ats-parent"),
      name = ev.target.getAttribute("ats-name"),
      prior = window[view].current;

    // Module onClose Event
    if (window[name] == null) console.error(`${name} no fue creado.`);
    let proceed = (typeof window[name].onClose == "function")
      ? window[name].onClose() : true;

    if (typeof proceed !== "boolean") proceed = true;

    if (proceed === false) return;

    document.getElementById(`${view}-${name}-button`)
      .remove();

    document.getElementById(`${view}-${name}`)
      .remove();

    let i = window[view].contents.findIndex(val => val == name);

    window[view].contents.splice(i, 1);

    if (window[view].contents.length == 0) return;

    window[view].__selectContent(window[view][window[view].contents[--i]].button);

    // onChange Event
    if (prior != window[view].current && typeof window[view].onChange == "function")
      window[view].onChange({ prior, current: window[view].current });
  }
}

/**
 * TabGroupContent
 */
class TabGroupContent {
  /**
   * 
   * @param {String} name 
   * @param {Module} owner 
   * @param {TabGroup} parent 
   * @param {DIV} button 
   */
  constructor(name, owner, parent, button) {
    this.name = name;
    this.owner = owner;
    this.parent = parent;
    this.button = button;
    this.element;

    this.init();
  }

  init() {
    this.element = document.getElementById(`${this.parent.name}-${this.name}`);

    if (!this.element) throw new Error(`${this.name} no existe.`);
  }

  show() {
    this.button.classList.add("active");
    this.element.classList.add("show");
  }

  hide() {
    this.button.classList.remove("active");
    this.element.classList.remove("show");
  }

  update(content) {
    this.element.innerHTML = content;
  }

  fitToParentHeight(margin = 0) {
    if (this.element.offsetParent == null) return;
    // offset parent es the nearest ancestor, if not exists, then offset parent is
    // document body. The nearest ancestor of all Components is "content" block.
    // w3schools.com/jsref

    let h = this.element.offsetParent.offsetHeight - (this.element.offsetTop + margin);
    this.element.style.height = `${h}px`;
  }

  fitToParentWidth() {
    if (this.element.offsetParent == null) return;
    // offset parent es the nearest ancestor, if not exists, then offset parent is
    // document body. The nearest ancestor of all Components is "content" block.
    // w3schools.com/jsref

    let w = this.element.offsetParent.offsetWidth - (this.element.offsetLeft + 10);
    this.element.style.width = `${w}px`;
  }
}

/**
 * Panel
 */
class Panel extends ComponentBase {

  postConstructor() {
    this.panels = new Map();
  }

  init() {
    super.init();

    this.panels.set("left", this.element.querySelector('.panel.left'));
    this.panels.set("content", this.element.querySelector('.panel.content'));
    this.panels.set("right", this.element.querySelector('.panel.right'));
  }

  /**
   * Open Module
   * @param {String} path 
   * @param {Object} data 
   */
  openModule(path, data) {
    if (typeof data == "undefined") data = {};

    this.owner.send(path, {
      data,
      onDone: module => {
        // Limpia el contenido
        const panel = this.panels.get('content');
        while (panel.hasChildNodes()) panel.removeChild(panel.firstChild);

        // No existe
        var content = document.createElement("DIV");
        content.id = `${this.name}-${module.name}`;

        this.__parseScripts(content, module.content);

        panel.append(content);

        // Module onOpen Event
        if (window[module.name] == null) console.error(`${module.name} no fue creado.`);
        ats.attachModule(window[module.name]);
      },
      onFail: rs => console.error(rs)
    });
  }

  /**
   * Parse Scripts
   * Generado por ChatGPT
   */
  __parseScripts(divContent, htmlString) {
    // 1. Crear contenedor temporal
    const temp = document.createElement('div');
    temp.innerHTML = htmlString;

    // 2. Extraer scripts
    const scripts = Array.from(temp.querySelectorAll('script'));

    scripts.forEach(s => s.remove()); // eliminar del HTML

    // 3. Insertar HTML restante
    divContent.innerHTML = temp.innerHTML;

    // 4. Crear e insertar scripts dinámicamente
    scripts.forEach(oldScript => {
      const newScript = document.createElement('script');

      if (oldScript.src) {
        newScript.src = oldScript.src;
      } else {
        newScript.textContent = oldScript.textContent;
      }

      divContent.appendChild(newScript);
    });

  }
}