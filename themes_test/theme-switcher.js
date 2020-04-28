var ThemeSwitcher = (function () {
  var ThemeButton = (function () {
    function ThemeButton(themeName, themeFileSrc) {
      this.themeName = themeName;
      this.themeFileSrc = themeFileSrc;
      this.createElement = this.createElement.bind(this);
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
      button.addEventListener("click", this.themeSwitch);
      this.el = button;
    };
    ThemeButton.prototype.themeSwitch = function () {
      var style = document.getElementById("theme-style");
      if (!style) {
        this.themeStyleCreate();
        style = document.getElementById("theme-style");
      }
      console.log(this);
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
  function ThemeSwitcher(sheetsPath) {
    this.init = this.init.bind(this);
    this.DOMInit = this.DOMInit.bind(this);
    this.sheetsPath = sheetsPath ? sheetsPath.toString() : "";
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
    for (var i = 0, t = 3; i < t; i++) {
      var btn = new ThemeButton(
        "Theme " + (i + 1),
        this.sheetsPath + "theme" + (i + 1) + ".css"
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
window.onload = new ThemeSwitcher("/").init;
