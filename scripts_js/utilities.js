//Function for getting cookie;
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
//Function for setting cookie
function setCookie(cname, cvalue, exmins) {
  const d = new Date();
  d.setTime(d.getTime() + (exmins*60*1000));
  let expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

//Setting dakmode's cookie to not Empty
if (getCookie("darkmodeToggled") == "" || null) {
  setCookie("darkmodeToggled", "FALSE");
}