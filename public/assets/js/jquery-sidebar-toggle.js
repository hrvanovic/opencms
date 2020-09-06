let appsidebar = document.getElementById("app-sidebar");
let appsidebartoggle = document.getElementById("app-sidebar-toggle");
var isSidebarHidden = false;

$(appsidebartoggle).click(function () {
  $(appsidebartoggle).blur().delay(100);
  if (isSidebarHidden) {
    $(appsidebar).animate({ width: 305 }, 400, function () {
      $("aside").fadeIn();
    });
    isSidebarHidden = false;
  } else {
    $(appsidebar).animate({ width: 0 }, 400, function () {
      $("aside").hide();
    });
    isSidebarHidden = true;
  }
});
