var ThemeSwitcher = (function () {
  var ThemeButton = (function () {
    function ThemeButton(themeName, themeFileSrc) {
      this.className = "ThemeSwitcher-button";
      this.themeName = themeName;
      this.themeFileSrc = themeFileSrc;
      this.createElement = this.createElement.bind(this);
      this.elementSetActive = this.elementSetActive.bind(this);
      this.elementSetInactive = this.elementSetInactive.bind(this);
      this.onClick = this.onClick.bind(this);
      this.themeSwitch = this.themeSwitch.bind(this);
      this.createElement();
    }
    ThemeButton.prototype.createElement = function () {
      var button = document.createElement("button");
      button.innerText = this.themeName;
      button.style.display = "block";
      button.style.width = "100%";
      button.style.padding = "5px 10px";
      button.style.border = "0";
      button.style.borderRadius = "0";
      button.style.marginBottom = "10px";
      button.style.fontSize = "12xpx";
      button.style.textAlign = "center";
      button.style.cursor = "pointer";
      button.style.outline = "0";
      button.setAttribute("class", this.className);
      button.addEventListener("click", this.onClick);
      this.el = button;
    };
    ThemeButton.prototype.elementSetActive = function () {
      this.el.style.textDecoration = "underline";
    };
    ThemeButton.prototype.elementSetInactive = function () {
      var siblings = Array.prototype.slice.call(
        this.el.parentNode.querySelectorAll("." + this.className)
      );
      for (var i = 0, t = siblings.length; i < t; i++) {
        siblings[i].style.textDecoration = null;
      }
    };
    ThemeButton.prototype.onClick = function () {
      this.themeSwitch();
      this.elementSetInactive();
      this.elementSetActive();
    };
    ThemeButton.prototype.themeSwitch = function () {
      var style = document.getElementById("theme-style");
      if (!style) {
        this.themeStyleCreate();
        style = document.getElementById("theme-style");
      }
      style.href = this.themeFileSrc;
    };
    ThemeButton.prototype.themeStyleCreate = function () {
      var style = document.createElement("link");
      style.id = "theme-style";
      style.rel = "stylesheet";
      document.getElementsByTagName("head")[0].appendChild(style);
    };
    return ThemeButton;
  })();
  function ThemeSwitcher(themes) {
    this.init = this.init.bind(this);
    this.DOMInit = this.DOMInit.bind(this);
    this.themes = themes;
  }
  ThemeSwitcher.prototype.DOMInit = function () {
    var container = document.createElement("div");
    var fragment = document.createDocumentFragment();
    container.style.position = "fixed";
    container.style.right = "0px";
    container.style.top = "90px";
    container.style.padding = "10px";
    container.style.background = "#fff";
    container.style.boxShadow = "2px 5px 5px rgba(0,0,0,0.2)";
    container.setAttribute("class", "ThemeSwitcher");
    for (var i = 0, t = this.themes.length, th = this.themes; i < t; i++) {
      var btn = new ThemeButton(
        Object.keys(th[i])[0],
        th[i][Object.keys(th[i])[0]]
      ).el;
      if (i + 1 === t) btn.style.marginBottom = "0";
      fragment.appendChild(btn);
    }
    container.appendChild(fragment);
    document.querySelector("#modu_main").appendChild(container);
  };
  ThemeSwitcher.prototype.init = function () {
    this.DOMInit();
  };
  return ThemeSwitcher;
})();
window.onload = new ThemeSwitcher(
  window.ModuThemes || [{ Theme1: "/theme3.css" }]
).init;
